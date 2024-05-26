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

/**
 * Collection of upgrade steps.
 */
class CRM_Mailboxmailing_Upgrader extends CRM_Extension_Upgrader_Base {

  /**
   * @return bool TRUE on success
   * @throws Exception
   */
  public function upgrade_4001() {
    $this->ctx->log->info('Applying update 4001: Adding database table columns for notification templates.');
    // this path is relative to the extension base dir
    $this->executeSqlFile('sql/upgrade_4001.sql');
    return TRUE;
  }

  /**
   * @return bool TRUE on success
   * @throws \Exception
   */
  public function upgrade_4002() {
    // Attach a custom field to the Mailing entity for tracking how often a
    // bounce report has been sent to the mailing author.
    $custom_group = civicrm_api3('CustomGroup', 'getsingle', array(
      'name' => 'mailing_mailboxmailing',
    ));
    $custom_field = civicrm_api3('CustomField', 'create', array(
      'custom_group_id' => $custom_group['id'],
      'label' => 'Mailboxmailing Bounce Report Count',
      'name' => 'MailboxmailingBouncesReportCount',
      'data_type' => 'Int',
      'is_searchable' => 0,
      'is_view' => 1,
      'in_selector' => 0,
      'html_type' => 'Text',
      'default_value' => 0,
    ));
    if ($custom_field['is_error']) {
      throw new Exception(E::ts('Could not create custom field for Mailing entity.'));
    }
    return TRUE;
  }

  /**
   * @return bool TRUE on success
   */
  public function upgrade_4003() {
    $this->ctx->log->info('Applying update 4003: Dropping unnecessary database table columns.');
    // this path is relative to the extension base dir
    $this->executeSqlFile('sql/upgrade_4003.sql');
    return TRUE;
  }

  /**
   * @return bool TRUE on success
   */
  public function upgrade_4004() {
    $this->ctx->log->info('Applying update 4004: Adding database table columns for notification subjects.');
    // this path is relative to the extension base dir
    $this->executeSqlFile('sql/upgrade_4004.sql');
    return TRUE;
  }

}
