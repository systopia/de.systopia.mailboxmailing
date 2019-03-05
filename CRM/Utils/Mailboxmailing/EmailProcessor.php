<?php

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
        $result['mail_settings_id'] = $mailSetting->id;
        $result['error_message'] = $exception->getMessage();
        continue;
      }

      // Connect to the mail store.
      try {
        $store = static::getMailStore($mailSetting);
      }
      catch (Exception $e) {
        // Add the error message to the results array and go on to the next mail
        // settings set.
        $result['mail_settings_id'] = $mailSetting->id;
        $result['error_message']
          = E::ts('Could not connect to MailStore for %1', array(
            1 => $mailSetting->username . '@' . $mailSetting->server,
          ))
          . E::ts('Error message: %1', array(
            1 => $e->getMessage(),
          ));
        continue;
      }

      // Process e-mails.
      while($mails = $store->fetchNext()) {
        foreach ($mails as $nr => $mail) {
          /* @var \ezcMail $mail */

          $processed = FALSE;
          $mailing = NULL;
          $mail_result = array();
          $mail_result['mail_settings_id'] = $mailSetting->id;
          $mail_result['message_id'] = $mail->messageId;

          // Check if sender is allowed to initiate mailing creation.
          if ($sender_id = array_search($mail->from->email, $allowed_senders)) {
            // Create, schedule and archive CiviCRM Mailing.
            try {
              $mailing = static::createMailing($mail, $mailSetting, $sender_id);
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

              $mail_result['mailing_id'] = $mailing->id;
              $processed = TRUE;
            }
            catch (Exception $exception) {
              $mail_result['error_message'] = $exception->getMessage();
            }
          }

          // Check if sender is in recipient group.
          elseif ($sender_id = array_search($mail->from->email, $recipients)) {
            // TODO: Send notice to sender informing them about not being
            //       allowed to send mailings as recipient.
            $mail_result['sender_notified'] = (int) TRUE;
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

          $result[] = $mail_result;
        }
      }
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
    // TODO: Remove defaults from the array, as they will be set within
    //       CRM_Mailing_BAO_Mailing::create().
    $mailingParams = array(
      'override_verp' => TRUE,
      'forward_replies' => FALSE,
      'open_tracking' => TRUE,
      'url_tracking' => TRUE,
      'visibility' => 'User and User Admin Only',
      'replyto_email' => $mail->from->email,
      'header_id' => CRM_Mailing_PseudoConstant::defaultComponent('header_id', ''),
      'footer_id' => CRM_Mailing_PseudoConstant::defaultComponent('footer_id', ''),
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
    $variables = static::getSmartyVariables($mailSetting, $mail);
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
            $mailingParams[$mailingParamName] = $part->text;
            $hasBodyParts = TRUE;
          }
          break;
        case 'ezcMailFile':
          /* @var \ezcMailFile $part */
          // Add file attachments.
          static $attachmentCount = 0;
          $attachmentCount++;
          $mailingParams["attachFile_$attachmentCount"]['location'] = $part->fileName;
          $mailingParams["attachFile_$attachmentCount"]['type'] = implode('/', array($part->contentType, $part->mimeType));
          break;
      }
    }
    if (!$hasBodyParts) {
      throw new Exception(E::ts('Could not create mailing: No plain text or html body parts could be extracted from the e-mail.'));
    }

    /* @var \CRM_Mailing_BAO_Mailing $mailing */
    $mailing = CRM_Mailing_BAO_Mailing::create($mailingParams);

    return $mailing;
  }

  /**
   * @param \CRM_Mailboxmailing_BAO_MailboxmailingMailSettings $mailSetting
   * @param \ezcMail | NULL $mail
   *
   * @return array
   */
  public static function getSmartyVariables($mailSetting, $mail = NULL) {
    $variables = array(
      'mail' => array(),
      'mailSetting' => $mailSetting->toArray(),
    );
    // All properties accessible through magic getter \ezcMail::__get().
    $mail_properties = array(
      'to',
      'cc',
      'bcc',
      'from',
      'subject',
      'subjectCharset',
      'body',
      'messageId',
      'returnPath',
      'timestamp',
    );
    foreach ($mail_properties as $mail_property) {
      $variables['mail'][$mail_property] = json_decode(json_encode(isset($mail) ? $mail->$mail_property : ''), TRUE);
    }

    return $variables;
  }

}
