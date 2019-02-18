<?php
use CRM_Mailboxmailing_ExtensionUtil as E;

class CRM_Mailboxmailing_BAO_MailboxmailingMailSettings extends CRM_Mailboxmailing_DAO_MailboxmailingMailSettings {

  /**
   * Class constructor.
   */
  public function __construct() {
    parent::__construct();
  }

  /**
   * Return the "include message ID" flag from the default set of settings.
   *
   * @return bool
   *   default include message ID
   */
  public static function includeMessageId() {
    return Civi::settings()->get('include_message_id');
  }

  /**
   * Retrieve DB object based on input parameters.
   *
   * It also stores all the retrieved values in the default array.
   *
   * @param array $params
   *   (reference ) an assoc array of name/value pairs.
   * @param array $defaults
   *   (reference ) an assoc array to hold the flattened values.
   *
   * @return CRM_Mailboxmailing_BAO_MailboxmailingMailSettings
   */
  public static function retrieve(&$params, &$defaults) {
    $mailSettings = new CRM_Mailboxmailing_DAO_MailboxmailingMailSettings();
    $mailSettings->copyValues($params);

    $result = NULL;
    if ($mailSettings->find(TRUE)) {
      CRM_Core_DAO::storeValues($mailSettings, $defaults);
      $result = $mailSettings;
    }

    return $result;
  }

  /**
   * Add new mail Settings.
   *
   * @param array $params
   *   Reference array contains the values submitted by the form.
   *
   *
   * @return object
   */
  public static function add(&$params) {
    $result = NULL;
    if (empty($params)) {
      return $result;
    }

    if (empty($params['id'])) {
      $params['is_ssl'] = CRM_Utils_Array::value('is_ssl', $params, FALSE);
    }

    $mailSettings = new CRM_Mailboxmailing_DAO_MailboxmailingMailSettings();
    $mailSettings->copyValues($params);
    $result = $mailSettings->save();

    return $result;
  }

  /**
   * Takes an associative array and creates a mail settings object.
   *
   * @param array $params
   *   (reference ) an assoc array of name/value pairs.
   *
   * @return CRM_Mailboxmailing_BAO_MailboxmailingMailSettings
   */
  public static function create(&$params) {
    $transaction = new CRM_Core_Transaction();

    $mailSettings = self::add($params);
    if (is_a($mailSettings, 'CRM_Core_Error')) {
      $mailSettings->rollback();
      return $mailSettings;
    }

    $transaction->commit();
    CRM_Mailboxmailing_BAO_MailboxmailingMailSettings::defaultDAO(TRUE);
    return $mailSettings;
  }

  /**
   * Delete the mail settings.
   *
   * @param int $id
   *   Mail settings id.
   *
   * @return mixed|null
   */
  public static function deleteMailSettings($id) {
    $results = NULL;
    $transaction = new CRM_Core_Transaction();

    $mailSettings = new CRM_Mailboxmailing_DAO_MailboxmailingMailSettings();
    $mailSettings->id = $id;
    $results = $mailSettings->delete();

    $transaction->commit();

    return $results;
  }

  /**
   * @return array
   * @throws \Exception
   */
  public function getSenders() {
    $senders = array();

    if (!empty($this->sender_group_id)) {
      $senders = civicrm_api3('Contact', 'get', array(
        'group' => $this->sender_group_id,
        'options' => array(
          'limit' => 0,
        ),
        'return' => array(
          'email',
        ),
      ));
      if ($senders['is_error']) {
        throw new Exception(
          E::ts('Error retrieving recipients from group: %1', array(
            1 => $senders['error_message'],
          ))
        );
      }
      $senders = array_map(function($contact) {
        return $contact['email'];
      }, $senders['values']);
      $senders = array_filter($senders);
    }

    return $senders;
  }

  /**
   * @return array
   * @throws \Exception
   */
  public function getRecipients() {
    $recipients = array();

    if (!empty($this->sender_group_id)) {
      $recipients = civicrm_api3('Contact', 'get', array(
        'group' => $this->recipient_group_id,
        'options' => array(
          'limit' => 0,
        ),
        'return' => array(
          'email',
        ),
      ));
      if ($recipients['is_error']) {
        throw new Exception(
          E::ts('Error retrieving recipients from group: %1', array(
            1 => $recipients['error_message'],
          ))
        );
      }
      $recipients = array_map(function($contact) {
        return $contact['email'];
      }, $recipients['values']);
      $recipients = array_filter($recipients);
    }

    return $recipients;
  }

}
