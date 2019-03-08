<?php

/**
 * @package CRM
 * @copyright CiviCRM LLC (c) 2004-2019
 *
 * Generated from /home/jens/repositories/de.systopia.mailboxmailing/xml/schema/CRM/Mailboxmailing/MailboxmailingMailSettings.xml
 * DO NOT EDIT.  Generated by CRM_Core_CodeGen
 * (GenCodeChecksum:4fbdbbe853ab2c9c984b6e3419398410)
 */

/**
 * Database access object for the MailboxmailingMailSettings entity.
 */
class CRM_Mailboxmailing_DAO_MailboxmailingMailSettings extends CRM_Core_DAO {

  /**
   * Static instance to hold the table name.
   *
   * @var string
   */
  static $_tableName = 'civicrm_mailboxmailing_mail_settings';

  /**
   * Should CiviCRM log any modifications to this table in the civicrm_log table.
   *
   * @var bool
   */
  static $_log = TRUE;

  /**
   * primary key
   *
   * @var int unsigned
   */
  public $id;

  /**
   * Which Domain is this match entry for
   *
   * @var int unsigned
   */
  public $domain_id;

  /**
   * name of this group of settings
   *
   * @var string
   */
  public $name;

  /**
   * email address domain (the part after @)
   *
   * @var string
   */
  public $domain;

  /**
   * optional local part (like civimail+ for addresses like civimail+s.1.2@example.com)
   *
   * @var string
   */
  public $localpart;

  /**
   * contents of the Return-Path header
   *
   * @var string
   */
  public $return_path;

  /**
   * name of the protocol to use for polling (like IMAP, POP3 or Maildir)
   *
   * @var string
   */
  public $protocol;

  /**
   * server to use when polling
   *
   * @var string
   */
  public $server;

  /**
   * port to use when polling
   *
   * @var int unsigned
   */
  public $port;

  /**
   * username to use when polling
   *
   * @var string
   */
  public $username;

  /**
   * password to use when polling
   *
   * @var string
   */
  public $password;

  /**
   * whether to use SSL or not
   *
   * @var boolean
   */
  public $is_ssl;

  /**
   * folder to poll from when using IMAP, path to poll from when using Maildir, etc.
   *
   * @var string
   */
  public $source;

  /**
   * The group of contacts allowed to send mailings using these MailSettings.
   *
   * @var int unsigned
   */
  public $sender_group_id;

  /**
   * The group of contacts to receive mailings using these MailSettings.
   *
   * @var int unsigned
   */
  public $recipient_group_id;

  /**
   * A pattern to use as the subject for the Mailing.
   *
   * @var string
   */
  public $subject;

  /**
   * Whether to notify disallowed sender.
   *
   * @var boolean
   */
  public $notify_disallowed_sender;

  /**
   * A Smarty template to use for notifications sent to disallowed senders.
   *
   * @var string
   */
  public $notify_disallowed_sender_template;

  /**
   * Whether to notify senders about errors.
   *
   * @var boolean
   */
  public $notify_sender_errors;

  /**
   * A Smarty template to use for notifications about errors sent to senders.
   *
   * @var string
   */
  public $notify_sender_errors_template;

  /**
   * The activity type to use for activities to notify senders about errors.
   *
   * @var int unsigned
   */
  public $notification_activity_type_id;

  /**
   * Whether to archive mailings after sending.
   *
   * @var boolean
   */
  public $archive_mailing;

  /**
   * Class constructor.
   */
  public function __construct() {
    $this->__table = 'civicrm_mailboxmailing_mail_settings';
    parent::__construct();
  }

  /**
   * Returns foreign keys and entity references.
   *
   * @return array
   *   [CRM_Core_Reference_Interface]
   */
  public static function getReferenceColumns() {
    if (!isset(Civi::$statics[__CLASS__]['links'])) {
      Civi::$statics[__CLASS__]['links'] = static ::createReferenceColumns(__CLASS__);
      Civi::$statics[__CLASS__]['links'][] = new CRM_Core_Reference_Basic(self::getTableName(), 'domain_id', 'civicrm_domain', 'id');
      Civi::$statics[__CLASS__]['links'][] = new CRM_Core_Reference_Basic(self::getTableName(), 'sender_group_id', 'civicrm_group', 'id');
      Civi::$statics[__CLASS__]['links'][] = new CRM_Core_Reference_Basic(self::getTableName(), 'recipient_group_id', 'civicrm_group', 'id');
      CRM_Core_DAO_AllCoreTables::invoke(__CLASS__, 'links_callback', Civi::$statics[__CLASS__]['links']);
    }
    return Civi::$statics[__CLASS__]['links'];
  }

  /**
   * Returns all the column names of this table
   *
   * @return array
   */
  public static function &fields() {
    if (!isset(Civi::$statics[__CLASS__]['fields'])) {
      Civi::$statics[__CLASS__]['fields'] = [
        'id' => [
          'name' => 'id',
          'type' => CRM_Utils_Type::T_INT,
          'title' => CRM_Mailboxmailing_ExtensionUtil::ts('Mail Settings ID'),
          'description' => CRM_Mailboxmailing_ExtensionUtil::ts('primary key'),
          'required' => TRUE,
          'table_name' => 'civicrm_mailboxmailing_mail_settings',
          'entity' => 'MailboxmailingMailSettings',
          'bao' => 'CRM_Mailboxmailing_DAO_MailboxmailingMailSettings',
          'localizable' => 0,
        ],
        'domain_id' => [
          'name' => 'domain_id',
          'type' => CRM_Utils_Type::T_INT,
          'title' => CRM_Mailboxmailing_ExtensionUtil::ts('Mail Settings Domain'),
          'description' => CRM_Mailboxmailing_ExtensionUtil::ts('Which Domain is this match entry for'),
          'required' => TRUE,
          'table_name' => 'civicrm_mailboxmailing_mail_settings',
          'entity' => 'MailboxmailingMailSettings',
          'bao' => 'CRM_Mailboxmailing_DAO_MailboxmailingMailSettings',
          'localizable' => 0,
          'pseudoconstant' => [
            'table' => 'civicrm_domain',
            'keyColumn' => 'id',
            'labelColumn' => 'name',
          ]
        ],
        'name' => [
          'name' => 'name',
          'type' => CRM_Utils_Type::T_STRING,
          'title' => CRM_Mailboxmailing_ExtensionUtil::ts('Mail Settings Name'),
          'description' => CRM_Mailboxmailing_ExtensionUtil::ts('name of this group of settings'),
          'maxlength' => 255,
          'size' => CRM_Utils_Type::HUGE,
          'table_name' => 'civicrm_mailboxmailing_mail_settings',
          'entity' => 'MailboxmailingMailSettings',
          'bao' => 'CRM_Mailboxmailing_DAO_MailboxmailingMailSettings',
          'localizable' => 0,
        ],
        'domain' => [
          'name' => 'domain',
          'type' => CRM_Utils_Type::T_STRING,
          'title' => CRM_Mailboxmailing_ExtensionUtil::ts('email Domain'),
          'description' => CRM_Mailboxmailing_ExtensionUtil::ts('email address domain (the part after @)'),
          'maxlength' => 255,
          'size' => CRM_Utils_Type::HUGE,
          'table_name' => 'civicrm_mailboxmailing_mail_settings',
          'entity' => 'MailboxmailingMailSettings',
          'bao' => 'CRM_Mailboxmailing_DAO_MailboxmailingMailSettings',
          'localizable' => 0,
        ],
        'localpart' => [
          'name' => 'localpart',
          'type' => CRM_Utils_Type::T_STRING,
          'title' => CRM_Mailboxmailing_ExtensionUtil::ts('email Local Part'),
          'description' => CRM_Mailboxmailing_ExtensionUtil::ts('optional local part (like civimail+ for addresses like civimail+s.1.2@example.com)'),
          'maxlength' => 255,
          'size' => CRM_Utils_Type::HUGE,
          'table_name' => 'civicrm_mailboxmailing_mail_settings',
          'entity' => 'MailboxmailingMailSettings',
          'bao' => 'CRM_Mailboxmailing_DAO_MailboxmailingMailSettings',
          'localizable' => 0,
        ],
        'return_path' => [
          'name' => 'return_path',
          'type' => CRM_Utils_Type::T_STRING,
          'title' => CRM_Mailboxmailing_ExtensionUtil::ts('Return Path'),
          'description' => CRM_Mailboxmailing_ExtensionUtil::ts('contents of the Return-Path header'),
          'maxlength' => 255,
          'size' => CRM_Utils_Type::HUGE,
          'table_name' => 'civicrm_mailboxmailing_mail_settings',
          'entity' => 'MailboxmailingMailSettings',
          'bao' => 'CRM_Mailboxmailing_DAO_MailboxmailingMailSettings',
          'localizable' => 0,
        ],
        'protocol' => [
          'name' => 'protocol',
          'type' => CRM_Utils_Type::T_STRING,
          'title' => CRM_Mailboxmailing_ExtensionUtil::ts('Protocol'),
          'description' => CRM_Mailboxmailing_ExtensionUtil::ts('name of the protocol to use for polling (like IMAP, POP3 or Maildir)'),
          'maxlength' => 255,
          'size' => CRM_Utils_Type::HUGE,
          'table_name' => 'civicrm_mailboxmailing_mail_settings',
          'entity' => 'MailboxmailingMailSettings',
          'bao' => 'CRM_Mailboxmailing_DAO_MailboxmailingMailSettings',
          'localizable' => 0,
          'html' => [
            'type' => 'Select',
          ],
          'pseudoconstant' => [
            'optionGroupName' => 'mail_protocol',
            'optionEditPath' => 'civicrm/admin/options/mail_protocol',
          ]
        ],
        'server' => [
          'name' => 'server',
          'type' => CRM_Utils_Type::T_STRING,
          'title' => CRM_Mailboxmailing_ExtensionUtil::ts('Mail Server'),
          'description' => CRM_Mailboxmailing_ExtensionUtil::ts('server to use when polling'),
          'maxlength' => 255,
          'size' => CRM_Utils_Type::HUGE,
          'table_name' => 'civicrm_mailboxmailing_mail_settings',
          'entity' => 'MailboxmailingMailSettings',
          'bao' => 'CRM_Mailboxmailing_DAO_MailboxmailingMailSettings',
          'localizable' => 0,
        ],
        'port' => [
          'name' => 'port',
          'type' => CRM_Utils_Type::T_INT,
          'title' => CRM_Mailboxmailing_ExtensionUtil::ts('Mail Port'),
          'description' => CRM_Mailboxmailing_ExtensionUtil::ts('port to use when polling'),
          'table_name' => 'civicrm_mailboxmailing_mail_settings',
          'entity' => 'MailboxmailingMailSettings',
          'bao' => 'CRM_Mailboxmailing_DAO_MailboxmailingMailSettings',
          'localizable' => 0,
        ],
        'username' => [
          'name' => 'username',
          'type' => CRM_Utils_Type::T_STRING,
          'title' => CRM_Mailboxmailing_ExtensionUtil::ts('Mail Account Username'),
          'description' => CRM_Mailboxmailing_ExtensionUtil::ts('username to use when polling'),
          'maxlength' => 255,
          'size' => CRM_Utils_Type::HUGE,
          'table_name' => 'civicrm_mailboxmailing_mail_settings',
          'entity' => 'MailboxmailingMailSettings',
          'bao' => 'CRM_Mailboxmailing_DAO_MailboxmailingMailSettings',
          'localizable' => 0,
        ],
        'password' => [
          'name' => 'password',
          'type' => CRM_Utils_Type::T_STRING,
          'title' => CRM_Mailboxmailing_ExtensionUtil::ts('Mail Account Password'),
          'description' => CRM_Mailboxmailing_ExtensionUtil::ts('password to use when polling'),
          'maxlength' => 255,
          'size' => CRM_Utils_Type::HUGE,
          'table_name' => 'civicrm_mailboxmailing_mail_settings',
          'entity' => 'MailboxmailingMailSettings',
          'bao' => 'CRM_Mailboxmailing_DAO_MailboxmailingMailSettings',
          'localizable' => 0,
        ],
        'is_ssl' => [
          'name' => 'is_ssl',
          'type' => CRM_Utils_Type::T_BOOLEAN,
          'title' => CRM_Mailboxmailing_ExtensionUtil::ts('Mail Account Uses SSL'),
          'description' => CRM_Mailboxmailing_ExtensionUtil::ts('whether to use SSL or not'),
          'table_name' => 'civicrm_mailboxmailing_mail_settings',
          'entity' => 'MailboxmailingMailSettings',
          'bao' => 'CRM_Mailboxmailing_DAO_MailboxmailingMailSettings',
          'localizable' => 0,
        ],
        'source' => [
          'name' => 'source',
          'type' => CRM_Utils_Type::T_STRING,
          'title' => CRM_Mailboxmailing_ExtensionUtil::ts('Mail Folder'),
          'description' => CRM_Mailboxmailing_ExtensionUtil::ts('folder to poll from when using IMAP, path to poll from when using Maildir, etc.'),
          'maxlength' => 255,
          'size' => CRM_Utils_Type::HUGE,
          'table_name' => 'civicrm_mailboxmailing_mail_settings',
          'entity' => 'MailboxmailingMailSettings',
          'bao' => 'CRM_Mailboxmailing_DAO_MailboxmailingMailSettings',
          'localizable' => 0,
        ],
        'sender_group_id' => [
          'name' => 'sender_group_id',
          'type' => CRM_Utils_Type::T_INT,
          'title' => CRM_Mailboxmailing_ExtensionUtil::ts('Sender Group'),
          'description' => CRM_Mailboxmailing_ExtensionUtil::ts('The group of contacts allowed to send mailings using these MailSettings.'),
          'table_name' => 'civicrm_mailboxmailing_mail_settings',
          'entity' => 'MailboxmailingMailSettings',
          'bao' => 'CRM_Mailboxmailing_DAO_MailboxmailingMailSettings',
          'localizable' => 0,
          'html' => [
            'type' => 'Select',
          ],
          'pseudoconstant' => [
            'table' => 'civicrm_group',
            'keyColumn' => 'id',
            'labelColumn' => 'title',
          ]
        ],
        'recipient_group_id' => [
          'name' => 'recipient_group_id',
          'type' => CRM_Utils_Type::T_INT,
          'title' => CRM_Mailboxmailing_ExtensionUtil::ts('Recipient Group'),
          'description' => CRM_Mailboxmailing_ExtensionUtil::ts('The group of contacts to receive mailings using these MailSettings.'),
          'table_name' => 'civicrm_mailboxmailing_mail_settings',
          'entity' => 'MailboxmailingMailSettings',
          'bao' => 'CRM_Mailboxmailing_DAO_MailboxmailingMailSettings',
          'localizable' => 0,
          'html' => [
            'type' => 'Select',
          ],
          'pseudoconstant' => [
            'table' => 'civicrm_group',
            'keyColumn' => 'id',
            'labelColumn' => 'title',
          ]
        ],
        'subject' => [
          'name' => 'subject',
          'type' => CRM_Utils_Type::T_STRING,
          'title' => CRM_Mailboxmailing_ExtensionUtil::ts('Mailing Subject'),
          'description' => CRM_Mailboxmailing_ExtensionUtil::ts('A pattern to use as the subject for the Mailing.'),
          'maxlength' => 255,
          'size' => CRM_Utils_Type::HUGE,
          'table_name' => 'civicrm_mailboxmailing_mail_settings',
          'entity' => 'MailboxmailingMailSettings',
          'bao' => 'CRM_Mailboxmailing_DAO_MailboxmailingMailSettings',
          'localizable' => 0,
        ],
        'notify_disallowed_sender' => [
          'name' => 'notify_disallowed_sender',
          'type' => CRM_Utils_Type::T_BOOLEAN,
          'title' => CRM_Mailboxmailing_ExtensionUtil::ts('Notify disallowed sender'),
          'description' => CRM_Mailboxmailing_ExtensionUtil::ts('Whether to notify disallowed sender.'),
          'table_name' => 'civicrm_mailboxmailing_mail_settings',
          'entity' => 'MailboxmailingMailSettings',
          'bao' => 'CRM_Mailboxmailing_DAO_MailboxmailingMailSettings',
          'localizable' => 0,
        ],
        'notify_disallowed_sender_template' => [
          'name' => 'notify_disallowed_sender_template',
          'type' => CRM_Utils_Type::T_LONGTEXT,
          'title' => CRM_Mailboxmailing_ExtensionUtil::ts('Notify Disallowed Sender Template'),
          'description' => CRM_Mailboxmailing_ExtensionUtil::ts('A Smarty template to use for notifications sent to disallowed senders.'),
          'table_name' => 'civicrm_mailboxmailing_mail_settings',
          'entity' => 'MailboxmailingMailSettings',
          'bao' => 'CRM_Mailboxmailing_DAO_MailboxmailingMailSettings',
          'localizable' => 0,
        ],
        'notify_sender_errors' => [
          'name' => 'notify_sender_errors',
          'type' => CRM_Utils_Type::T_BOOLEAN,
          'title' => CRM_Mailboxmailing_ExtensionUtil::ts('Notify sender about errors'),
          'description' => CRM_Mailboxmailing_ExtensionUtil::ts('Whether to notify senders about errors.'),
          'table_name' => 'civicrm_mailboxmailing_mail_settings',
          'entity' => 'MailboxmailingMailSettings',
          'bao' => 'CRM_Mailboxmailing_DAO_MailboxmailingMailSettings',
          'localizable' => 0,
        ],
        'notify_sender_errors_template' => [
          'name' => 'notify_sender_errors_template',
          'type' => CRM_Utils_Type::T_LONGTEXT,
          'title' => CRM_Mailboxmailing_ExtensionUtil::ts('Notify Sender Errors Template'),
          'description' => CRM_Mailboxmailing_ExtensionUtil::ts('A Smarty template to use for notifications about errors sent to senders.'),
          'table_name' => 'civicrm_mailboxmailing_mail_settings',
          'entity' => 'MailboxmailingMailSettings',
          'bao' => 'CRM_Mailboxmailing_DAO_MailboxmailingMailSettings',
          'localizable' => 0,
        ],
        'notification_activity_type_id' => [
          'name' => 'notification_activity_type_id',
          'type' => CRM_Utils_Type::T_INT,
          'title' => CRM_Mailboxmailing_ExtensionUtil::ts('Notification Activity Type'),
          'description' => CRM_Mailboxmailing_ExtensionUtil::ts('The activity type to use for activities to notify senders about errors.'),
          'table_name' => 'civicrm_mailboxmailing_mail_settings',
          'entity' => 'MailboxmailingMailSettings',
          'bao' => 'CRM_Mailboxmailing_DAO_MailboxmailingMailSettings',
          'localizable' => 0,
          'html' => [
            'type' => 'Select',
          ],
          'pseudoconstant' => [
            'optionGroupName' => 'activity_type',
            'optionEditPath' => 'civicrm/admin/options/activity_type',
          ]
        ],
        'archive_mailing' => [
          'name' => 'archive_mailing',
          'type' => CRM_Utils_Type::T_BOOLEAN,
          'title' => CRM_Mailboxmailing_ExtensionUtil::ts('Archive Mailing'),
          'description' => CRM_Mailboxmailing_ExtensionUtil::ts('Whether to archive mailings after sending.'),
          'table_name' => 'civicrm_mailboxmailing_mail_settings',
          'entity' => 'MailboxmailingMailSettings',
          'bao' => 'CRM_Mailboxmailing_DAO_MailboxmailingMailSettings',
          'localizable' => 0,
        ],
      ];
      CRM_Core_DAO_AllCoreTables::invoke(__CLASS__, 'fields_callback', Civi::$statics[__CLASS__]['fields']);
    }
    return Civi::$statics[__CLASS__]['fields'];
  }

  /**
   * Return a mapping from field-name to the corresponding key (as used in fields()).
   *
   * @return array
   *   Array(string $name => string $uniqueName).
   */
  public static function &fieldKeys() {
    if (!isset(Civi::$statics[__CLASS__]['fieldKeys'])) {
      Civi::$statics[__CLASS__]['fieldKeys'] = array_flip(CRM_Utils_Array::collect('name', self::fields()));
    }
    return Civi::$statics[__CLASS__]['fieldKeys'];
  }

  /**
   * Returns the names of this table
   *
   * @return string
   */
  public static function getTableName() {
    return self::$_tableName;
  }

  /**
   * Returns if this table needs to be logged
   *
   * @return bool
   */
  public function getLog() {
    return self::$_log;
  }

  /**
   * Returns the list of fields that can be imported
   *
   * @param bool $prefix
   *
   * @return array
   */
  public static function &import($prefix = FALSE) {
    $r = CRM_Core_DAO_AllCoreTables::getImports(__CLASS__, 'mailboxmailing_mail_settings', $prefix, []);
    return $r;
  }

  /**
   * Returns the list of fields that can be exported
   *
   * @param bool $prefix
   *
   * @return array
   */
  public static function &export($prefix = FALSE) {
    $r = CRM_Core_DAO_AllCoreTables::getExports(__CLASS__, 'mailboxmailing_mail_settings', $prefix, []);
    return $r;
  }

  /**
   * Returns the list of indices
   *
   * @param bool $localize
   *
   * @return array
   */
  public static function indices($localize = TRUE) {
    $indices = [
      'UI_notification_activity_type_id' => [
        'name' => 'UI_notification_activity_type_id',
        'field' => [
          0 => 'notification_activity_type_id',
        ],
        'localizable' => FALSE,
        'sig' => 'civicrm_mailboxmailing_mail_settings::0::notification_activity_type_id',
      ],
    ];
    return ($localize && !empty($indices)) ? CRM_Core_DAO_AllCoreTables::multilingualize(__CLASS__, $indices) : $indices;
  }

}
