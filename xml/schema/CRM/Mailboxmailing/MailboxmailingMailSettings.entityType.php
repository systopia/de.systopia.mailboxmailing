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

// This file declares a new entity type. For more details, see "hook_civicrm_entityTypes" at:
// http://wiki.civicrm.org/confluence/display/CRMDOC/Hook+Reference
return array (
  0 => 
  array (
    'name' => 'MailboxmailingMailSettings',
    'class' => 'CRM_Mailboxmailing_DAO_MailboxmailingMailSettings',
    'table' => 'civicrm_mailboxmailing_mail_settings',
  ),
);
