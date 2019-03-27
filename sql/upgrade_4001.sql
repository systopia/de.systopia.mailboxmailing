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
    AFTER `notify_sender_errors`,
  ADD COLUMN
    `from_email_address_id`
      varchar(512)
      COMMENT 'Implicit FK to civicrm_option_value.value for from email address to use when sending notifications.'
    AFTER `subject`;
