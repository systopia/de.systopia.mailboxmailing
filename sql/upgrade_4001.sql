ALTER TABLE `civicrm_mailboxmailing_mail_settings`
  ADD COLUMN
    `notify_disallowed_sender_template`
      longtext
      COMMENT 'A Smarty template to use for notifications sent to disallowed senders.'
    AFTER `notify_disallowed_sender`,
  ADD COLUMN
    `notify_sender_errors_template`
      longtext
      COMMENT 'A Smarty template to use for notifications about errors sent to senders.'
    AFTER `notify_sender_errors`;
