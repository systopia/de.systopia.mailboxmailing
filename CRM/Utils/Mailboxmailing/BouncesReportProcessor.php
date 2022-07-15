<?php
/*-------------------------------------------------------+
| SYSTOPIA Mailbox Mailing Extension                     |
| Copyright (C) 2019 SYSTOPIA                            |
| Author: J. Schuppe (schuppe@systopia.de)               |
+--------------------------------------------------------+
| This program is released as free software under the    |
| Affero GPL license. You can redistribute it and/or     |
| modify it under the terms of this license which you    |
| can read by viewing the included agpl.txt or online    |
| at www.gnu.org/licenses/agpl.html. Removal of this     |
| copyright header is strictly prohibited without        |
| written permission from the original author(s).        |
+--------------------------------------------------------*/

use CRM_Mailboxmailing_ExtensionUtil as E;

class CRM_Utils_Mailboxmailing_BouncesReportProcessor {

  const BOUNCE_REPORT_COMPLETED = -1;

  const BOUNCE_REPORT_SKIPPED = NULL;

  /**
   * @param $parameters
   *
   * @return array
   *   TODO: Documentation
   *
   * @throws \Exception
   */
  public static function process($parameters) {
    $result = array();

    $custom_field_mail_settings_id = CRM_Utils_Mailboxmailing::resolveCustomField('MailboxmailingMailSettingsId');
    $custom_field_bounces_report_count = CRM_Utils_Mailboxmailing::resolveCustomField('MailboxmailingBouncesReportCount');

    $mailing_parameters = array(
      // Override default API limit.
      'limit' => 0,
      // Only mailings not marked to be skipped.
      $custom_field_bounces_report_count => array(
        'IS NOT NULL' => 1,
      ),
      // Only mailings created by the Mailboxmailing extension.
      $custom_field_mail_settings_id => array(
        'IS NOT NULL' => 1,
      ),
      // Only completed mailings.
      'is_completed' => 1,
    );
    // If given a Mailing ID, restrict to that single mailing.
    if (!empty($parameters['mid'])) {
      $mailing_parameters['id'] = $parameters['mid'];
    }
    // If given a MailSettings ID, restrict to mailings using these settings.
    elseif(!empty($parameters['id'])) {
      $mailing_parameters[$custom_field_mail_settings_id] = $parameters['id'];
    }

    $mailings = civicrm_api3('Mailing', 'get', $mailing_parameters);

    foreach ($mailings['values'] as $mailing) {
      // Ignore mailings marked completely checked for bounces.
      if ($mailing[$custom_field_bounces_report_count] == static::BOUNCE_REPORT_COMPLETED) {
        continue;
      }

      $mailSettings = new CRM_Mailboxmailing_BAO_MailboxmailingMailSettings();

      //multi-domain support for mail settings. CRM-5244
      $mailSettings->domain_id = CRM_Core_Config::domainID();

      $mailSettings->id = (int) $mailing[$custom_field_mail_settings_id];

      //find and fetch mail settings.
      $mailSettings->find();
      $mailSettings->fetch();

      // TODO: Make the limit configurable.
      // $bounce_report_limit = $mailSettings->bounce_report_limit;
      $bounce_report_limit = 3;

      if ($mailSettings->notify_sender_errors) {
        $bounce_report_count = $mailing[$custom_field_bounces_report_count];

        if (
          // Not marked for skipping.
          $bounce_report_count !== static::BOUNCE_REPORT_SKIPPED
          // Not yet completed
          && $bounce_report_count != static::BOUNCE_REPORT_COMPLETED
          // Not yet reached the current limit.
          && $bounce_report_count < $bounce_report_limit
        ) {
          $bounces = CRM_Mailing_Event_BAO_Bounce::getRows($mailing['id']);
          if (!empty($bounces)) {
            static::sendBouncesReportNotification($mailing, $mailSettings, $bounces);
          }

          // Increase bounce report count or mark completed.
          if (++$bounce_report_count >= $bounce_report_limit) {
            $bounce_report_count = static::BOUNCE_REPORT_COMPLETED;
          }
        }
      }
      else {
        // Mark skipped.
        $bounce_report_count = static::BOUNCE_REPORT_SKIPPED;
      }

      // Set the bounce report count on the mailing.
      civicrm_api3('Mailing', 'create', array(
        'id' => $mailing['id'],
        $custom_field_bounces_report_count => $bounce_report_count,
      ));

      $mailing_result = array(
        'mailing_id' => $mailing['id'],
        'mail_settings_id' => $mailing[$custom_field_mail_settings_id],
        'bounce_report_limit' => $bounce_report_limit,
        'bounce_report_count' => (isset($bounce_report_count) ? ($bounce_report_count == static::BOUNCE_REPORT_COMPLETED ? 'completed' : $bounce_report_count) : 'skipped'),
      );
      if (isset($bounces)) {
        $mailing_result['bounces'] = count($bounces);
      }
      $result[] = $mailing_result;
    }

    return $result;
  }

  /**
   * @param \CRM_Mailing_BAO_Mailing $mailing
   *
   * @param \CRM_Mailboxmailing_BAO_MailboxmailingMailSettings $mailSetting
   *
   * @param array $bounces
   *
   * @throws \Exception
   */
  public static function sendBouncesReportNotification($mailing, $mailSetting, $bounces) {
    $sender_contact = civicrm_api3('Contact', 'getsingle', array(
      'id' => $mailing['created_id'],
    ));
    if (isset($mailSetting->from_email_address_id)) {
      $from_email_address = $mailSetting->from_email_address_id;
    }
    else {
      $from_email_address = CRM_Core_BAO_Domain::getNameAndEmail(FALSE, TRUE);
      $from_email_address = reset($from_email_address);
    }

    // Evaluate subject pattern.
    $smarty = CRM_Core_Smarty::singleton();
    $variables = CRM_Utils_Mailboxmailing::getSmartyVariables(array(
      'mailSetting' => $mailSetting,
      'bounces' => $bounces,
    ));
    $subject = $smarty->fetchWith('string:' . $mailSetting->notify_sender_errors_subject, $variables);

    $mail_params = array(
      'from' => $from_email_address,
      'toName' => $sender_contact['display_name'],
      'toEmail' => $sender_contact['email'],
      'cc' => '',
      'bc' => '',
      'subject' => $subject,
      'replyTo' => $from_email_address,
    );

    // Render Smarty template.
    $text = CRM_Core_Smarty::singleton()->fetchWith(
      'string:' . $mailSetting->notify_sender_errors_template,
      CRM_Utils_Mailboxmailing::getSmartyVariables(array(
        'mailSetting' => $mailSetting,
        'bounces' => $bounces,
      ))
    );
    $mail_params['text'] = $text;
    $mail_params['html'] = str_replace("<br />\n<br />\n", "</p>\n<p>", '<p>'.nl2br($text).'</p>');

    if (!CRM_Utils_Mail::send($mail_params)) {
      throw new \Exception(E::ts('Sending bounce report to mailing sender failed.'));
    }
  }

}
