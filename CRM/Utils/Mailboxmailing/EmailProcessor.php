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

class CRM_Utils_Mailboxmailing_EmailProcessor {

  /**
   * @param $parameters
   *
   * @return array
   *   A sequential list of processed e-mails with the following values:
   *   - mail_settings_id: The ID of the MailboxmailingMailSettings being used.
   *   - message_id: The message_id of the e-mail.
   *   - mailing_created: Whether a CiviMail mailing has been created.
   *   - mailing_id: The ID of the CiviMail mailing created.
   *   - folder: The mailbox folder, relative to the source folder configured in
   *     the MailboxmailingMailSettings, the e-mail was moved into.
   *   - sender_notified: Whether the sender has been notified about not being
   *     allowed to initiate CiviMail mailings.
   *   - is_error: Whether an error occurred.
   *   - error_message: When an error occurred, the description of what went
   *     wrong.
   *
   * @throws \Exception
   */
  public static function process($parameters) {
    $result = array();

    $mailSetting = new CRM_Mailboxmailing_BAO_MailboxmailingMailSettings();

    //multi-domain support for mail settings. CRM-5244
    $mailSetting->domain_id = CRM_Core_Config::domainID();

    $mailSetting->copyValues($parameters);

    //find all mail settings.
    $mailSetting->find();
    while ($mailSetting->fetch()) {

      // Get contacts in sender and recipient groups.
      try {
        $allowed_senders = $mailSetting->getSenders();
        $recipients = $mailSetting->getRecipients();
      }
      catch (Exception $exception) {
        // Add the error message to the results array and go on to the next mail
        // settings set.
        $mail_setting_result['mail_settings_id'] = $mailSetting->id;
        $mail_setting_result['error_message'] = $exception->getMessage();
        continue;
      }

      // Connect to the mail store.
      try {
        $store = static::getMailStore($mailSetting);
      }
      catch (Exception $e) {
        // Add the error message to the results array and go on to the next mail
        // settings set.
        $mail_setting_result['mail_settings_id'] = $mailSetting->id;
        $mail_setting_result['error_message']
          = E::ts('Could not connect to MailStore for %1', array(
            1 => $mailSetting->username . '@' . $mailSetting->server,
          ))
          . E::ts('Error message: %1', array(
            1 => $e->getMessage(),
          ));
        continue;
      }

      // Process e-mails.
      // TODO: Fetching mails may create corrupt files for attachment parts.
      //   Find out under which circumstances and how to fix this.
      while($mails = $store->fetchNext()) {
        foreach ($mails as $nr => $mail) {
          /* @var \ezcMail $mail */

          $processed = FALSE;
          $mailing = NULL;
          $mail_result = array();
          $mail_result['mail_settings_id'] = $mailSetting->id;
          $mail_result['message_id'] = $mail->messageId;

          // Check if sender is allowed to initiate mailing creation.
          if ($sender_id = array_search(strtolower($mail->from->email), $allowed_senders)) {
            // Create, schedule and archive CiviCRM Mailing.
            try {
              $mailing = static::createMailing($mail, $mailSetting, $sender_id);

              $mail_result['mailing_id'] = $mailing->id;
              $processed = TRUE;
            }
            catch (\Exception $exception) {
              $mail_result['error_message'] = $exception->getMessage();
            }
          }

          // Check if sender is in recipient group.
          elseif ($sender_id = array_search(strtolower($mail->from->email), $recipients)) {
            // Send notice to sender informing them about not being allowed to
            // send mailings as recipient.
            try {
              static::sendDisallowedSenderNotification($mail, $mailSetting, $sender_id);

              $mail_result['sender_notified'] = (int) TRUE;
            }
            catch (\Exception $exception) {
              $mail_result['sender_notified'] = (int) FALSE;
              $mail_result['error_message'] = $exception->getMessage();
            }

            $processed = TRUE;
          }
          else {
            // Ignore e-mails from any other e-mail addresses.
            $processed = TRUE;
          }

          $mail_result['mailing_created'] = (int) !empty($mailing);
          $mail_result['is_error'] = (int) !$processed;

          // Checking if methods exist, although for all implemented mail store
          // protocol types they do.
          if (!empty($mailing) && method_exists($store, 'markProcessed')) {
            $store->markProcessed($nr);
            $mail_result['folder'] = 'CiviMail.processed';
          }
          elseif (empty($mailing) && method_exists($store, 'markIgnored')) {
            $store->markIgnored($nr);
            $mail_result['folder'] = 'CiviMail.ignored';
          }

          $mail_setting_result[] = $mail_result;
        }
      }
      $result[] = $mail_setting_result;
    }

    return $result;
  }

  /**
   * Return the proper mail store implementation, based on config settings.
   *
   * @param \CRM_Mailboxmailing_BAO_MailboxmailingMailSettings $mailSetting
   *   The settings set object from civimail_mailboxmailing_mail_settings to
   *   use.
   *
   * @throws Exception
   * @return \CRM_Mailing_MailStore
   *   The mail store implementation for processing e-mails.
   */
  public static function getMailStore($mailSetting) {
    $protocols = CRM_Core_PseudoConstant::get('CRM_Mailboxmailing_DAO_MailboxmailingMailSettings', 'protocol');
    if (empty($protocols[$mailSetting->protocol])) {
      throw new Exception("Empty mail protocol");
    }

    switch ($protocols[$mailSetting->protocol]) {
      case 'IMAP':
        return new CRM_Mailing_MailStore_Imap($mailSetting->server, $mailSetting->username, $mailSetting->password, (bool) $mailSetting->is_ssl, $mailSetting->source);

      case 'POP3':
        return new CRM_Mailing_MailStore_Pop3($mailSetting->server, $mailSetting->username, $mailSetting->password, (bool) $mailSetting->is_ssl);

      case 'Maildir':
        return new CRM_Mailing_MailStore_Maildir($mailSetting->source);

      case 'Localdir':
        return new CRM_Mailing_MailStore_Localdir($mailSetting->source);

      // DO NOT USE the mbox transport for anything other than testing
      // in particular, it does not clear the mbox afterwards

      case 'mbox':
        return new CRM_Mailing_MailStore_Mbox($mailSetting->source);

      default:
        throw new Exception("Unknown protocol {$mailSetting->protocol}");
    }
  }

  /**
   * @param \ezcMail $mail
   * @param \CRM_Mailboxmailing_BAO_MailboxmailingMailSettings $mailSetting
   * @param int $sender_id
   *
   * @return \CRM_Mailing_BAO_Mailing
   * @throws \Exception
   */
  public static function createMailing($mail, $mailSetting, $sender_id) {
    $attachmentCount = 0;
    $mailingParams = array(
      'override_verp' => TRUE,
      'forward_replies' => FALSE,
      'open_tracking' => FALSE,
      'url_tracking' => FALSE,
      'visibility' => 'User and User Admin Only',
      'replyto_email' => $mail->from->email,
      'header_id' => NULL,
      'footer_id' => NULL,
      'from_email' => $mail->from->email,
      'from_name' => $mail->from->name,
      'msg_template_id' => NULL,
      'created_id' => $sender_id,
      'approver_id' => NULL,
      'auto_responder' => 0,
      'created_date' => date('YmdHis', $mail->timestamp),
      'scheduled_date' => date('YmdHis'),
      'scheduled_id' => $sender_id,
      'approval_date' => NULL,
      'groups' => array(
        'include' => array(
          $mailSetting->recipient_group_id,
        ),
      ),
    );

    // Evaluate subject pattern.
    $smarty = CRM_Core_Smarty::singleton();
    $variables = CRM_Utils_Mailboxmailing::getSmartyVariables(array(
      'mailSetting' => $mailSetting,
      'mail' => $mail,
    ));
    $subject = $smarty->fetchWith('string:' . $mailSetting->subject, $variables);
    $mailingParams['subject'] = $subject;
    $mailingParams['name'] = $subject;

    /* @var \ezcMailPart[] $parts */
    $parts = $mail->fetchParts();
    $hasBodyParts = FALSE;
    foreach ($parts as $part) {
      switch (get_class($part)) {
        case 'ezcMailText':
          /* @var \ezcMailText $part */
          // Add body contents.
          switch ($part->subType) {
            case 'plain':
              $mailingParamName = 'body_text';
              break;
            case 'html':
              $mailingParamName = 'body_html';
              break;
          }
          $parser = ezcMailPartParser::createPartParserForHeaders($part->headers);
          if (isset($mailingParamName)) {
            $parser->parseBody($part->generateBody());

            // If CiviCRM e-mail contents is passed through the Smarty parser,
            // wrap the whole body inside {literal} tags to preserve CSS;
            // actually, we don't want Smarty to fiddle with the e-mail at all.
            if (defined('CIVICRM_MAIL_SMARTY') && CIVICRM_MAIL_SMARTY ? TRUE : FALSE) {
              $body_text = $part->text;
              // Remove any closing {/literal} Smarty tag to prevent executing
              // Smarty logic.
              $body_text = str_replace('{/literal}', '', $body_text);
              $mailingParams[$mailingParamName] = '{literal}' . $body_text . '{/literal}';
            }
            else {
              $mailingParams[$mailingParamName] = $part->text;
            }
            $hasBodyParts = TRUE;
          }
          break;
        case 'ezcMailFile':
          /* @var \ezcMailFile $part */
          // Add file attachments.
          $attachmentCount++;

          // Copy the attachment file using its display name, since its raw file
          // name in $part->fileName might not be readable when containing
          // special characters.
          // @link http://zetacomponents.org/documentation/trunk/Mail/tutorial.html#parsing-attachment-file-names
          $path = explode('/', $part->fileName);
          array_pop($path);
          array_push($path, $part->contentDisposition->displayFileName);
          $displayFileName = implode('/', $path);
          copy($part->fileName, $displayFileName);

          $mailingParams["attachFile_$attachmentCount"]['location'] = $displayFileName;
          $mailingParams["attachFile_$attachmentCount"]['type'] = implode('/', array($part->contentType, $part->mimeType));
          break;
      }
    }
    if (!$hasBodyParts) {
      throw new Exception(E::ts('Could not create mailing: No plain text or html body parts could be extracted from the e-mail.'));
    }

    /* @var \CRM_Mailing_BAO_Mailing $mailing */
    $mailing = CRM_Mailing_BAO_Mailing::create($mailingParams);

    // Store the MailSettings ID in a custom field for later
    // traceability.
    $field = civicrm_api3('CustomField', 'getsingle', array(
      'custom_group_id' => "mailing_mailboxmailing",
      'name' => "MailboxmailingMailSettingsId",
    ));
    civicrm_api3('Mailing', 'create', array(
      'id' => $mailing->id,
      'custom_' . $field['id'] => $mailSetting->id,
    ));

    return $mailing;
  }

  /**
   * @param \ezcMail $mail
   * @param \CRM_Mailboxmailing_BAO_MailboxmailingMailSettings $mailSetting
   * @param int $sender_id
   *
   * @throws \Exception
   */
  public static function sendDisallowedSenderNotification($mail, $mailSetting, $sender_id) {
    $sender_contact = civicrm_api3('Contact', 'getsingle', array(
      'id' => $sender_id,
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
      'mail' => $mail,
    ));
    $subject = $smarty->fetchWith('string:' . $mailSetting->notify_disallowed_sender_subject, $variables);

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
      'string:' . $mailSetting->notify_disallowed_sender_template,
      CRM_Utils_Mailboxmailing::getSmartyVariables(array(
        'mailSetting' => $mailSetting,
        'mail' => $mail,
      ))
    );
    $mail_params['text'] = $text;
    $mail_params['html'] = str_replace("<br />\n<br />\n", "</p>\n<p>", '<p>'.nl2br($text).'</p>');

    if (!CRM_Utils_Mail::send($mail_params)) {
      throw new \Exception(E::ts('Sending notification to disallowed sender failed.'));
    }
  }

}
