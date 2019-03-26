<?php

use CRM_Mailboxmailing_ExtensionUtil as E;

class CRM_Utils_Mailboxmailing_BouncesReportProcessor {

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

    $mailing_parameters = array(
      // Only mailings with a bounce report count less than the limit.
      // TODO: Make the limit configurable.
      CRM_Utils_Mailboxmailing::resolveCustomField('MailboxmailingBouncesReportCount') => array(
        '<' => 3,
      ),
      // Only mailings created by the Mailboxmailing extension.
      CRM_Utils_Mailboxmailing::resolveCustomField('MailboxmailingMailSettingsId') => array(
        'IS NOT NULL' => 1,
      ),
      // Only completed mailings.
      'is_completed' => 1,
    );
    if (!empty($parameters['mid'])) {
      $mailing_parameters['id'] = $parameters['mid'];
    }
    elseif(!empty($parameters['id'])) {
      $mailing_parameters[CRM_Utils_Mailboxmailing::resolveCustomField('MailboxmailingMailSettingsId')] = $parameters['id'];
    }

    $mailings = civicrm_api3('Mailing', 'get', $mailing_parameters);

    foreach ($mailings['values'] as $mailing) {
      // TODO: Check for bounces and generate report e-mail - but only if the corresponding MailSettings is configured to do that.
    }

    return $result;
  }

  /**
   * @param \CRM_Mailing_BAO_Mailing $mailing
   *
   * @param \CRM_Mailboxmailing_BAO_MailboxmailingMailSettings $mailSetting
   *
   * @throws \Exception
   */
  public static function sendBouncesReportNotification($mailing, $mailSetting) {

  }

  /**
   * @param \CRM_Mailboxmailing_BAO_MailboxmailingMailSettings $mailSetting
   *
   * @return array
   */
  public static function getSmartyVariables($mailSetting) {
    $variables = array(
      'mailSetting' => $mailSetting->toArray(),
    );

    return $variables;
  }

}
