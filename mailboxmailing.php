<?php
/*-------------------------------------------------------+
| SYSTOPIA Malbox Mailing Extension                      |
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

require_once 'mailboxmailing.civix.php';
use CRM_Mailboxmailing_ExtensionUtil as E;

/**
 * Implements hook_civicrm_config().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_config
 */
function mailboxmailing_civicrm_config(&$config) {
  _mailboxmailing_civix_civicrm_config($config);
}

/**
 * Implements hook_civicrm_xmlMenu().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_xmlMenu
 */
function mailboxmailing_civicrm_xmlMenu(&$files) {
  _mailboxmailing_civix_civicrm_xmlMenu($files);
}

/**
 * Implements hook_civicrm_install().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_install
 */
function mailboxmailing_civicrm_install() {
  // Make the Mailing entity fieldable (add to cg_extend_objects OptionGroup)
  // and attach a custom field to it to reference a MailboxmailingMailSettings
  // entity.
  try {
    civicrm_api3('OptionValue', 'getsingle', array(
      'option_group_id' => 'cg_extend_objects',
      'label' => 'Mailing',
      'value' => 'Mailing',
      'name' => 'civicrm_mailing',
    ));
  }
  catch (CiviCRM_API3_Exception $exception) {
    civicrm_api3('OptionValue', 'create', array(
      'option_group_id' => 'cg_extend_objects',
      'label' => 'Mailing',
      'value' => 'Mailing',
      'name' => 'civicrm_mailing',
      'is_reserved' => 1,
    ));
  }
  $custom_group = civicrm_api3('CustomGroup', 'create', array(
    'title' => 'Mailboxmailing',
    'extends' => 'Mailing',
    'name' => 'mailing_mailboxmailing',
    'table_name' => 'civicrm_value_mailing_mailboxmailing',
    'is_reserved' => 1,
  ));
  civicrm_api3('CustomField', 'create', array(
    'custom_group_id' => $custom_group['id'],
    'label' => 'Mailboxmailing Mail Settings ID',
    'name' => 'MailboxmailingMailSettingsId',
    'data_type' => 'Int',
    'is_searchable' => 0,
    'is_view' => 1,
    'in_selector' => 0,
    'html_type' => 'Text',
  ));

  _mailboxmailing_civix_civicrm_install();
}

/**
 * Implements hook_civicrm_postInstall().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_postInstall
 */
function mailboxmailing_civicrm_postInstall() {
  _mailboxmailing_civix_civicrm_postInstall();
}

/**
 * Implements hook_civicrm_uninstall().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_uninstall
 */
function mailboxmailing_civicrm_uninstall() {
  // Remove the custom field and the custom field group added during
  // installation.
  try {
    $field = civicrm_api3('CustomField', 'getsingle', array(
      'custom_group_id' => "mailing_mailboxmailing",
      'name' => "MailboxmailingMailSettingsId",
    ));
    civicrm_api3('CustomField', 'delete', array(
      'id' => $field['id'],
    ));
  }
  catch (CiviCRM_API3_Exception $exception) {
    // Nothing to do here.
  }

  try {
    $group = civicrm_api3('CustomGroup', 'getsingle', array(
      'name' => 'mailing_mailboxmailing',
    ));
    civicrm_api3('CustomGroup', 'delete', array(
      'id' => $group['id'],
    ));
  }
  catch (CiviCRM_API3_Exception $exception) {
    // Nothing to do here.
  }

  _mailboxmailing_civix_civicrm_uninstall();
}

/**
 * Implements hook_civicrm_enable().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_enable
 */
function mailboxmailing_civicrm_enable() {
  _mailboxmailing_civix_civicrm_enable();
}

/**
 * Implements hook_civicrm_disable().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_disable
 */
function mailboxmailing_civicrm_disable() {
  _mailboxmailing_civix_civicrm_disable();
}

/**
 * Implements hook_civicrm_upgrade().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_upgrade
 */
function mailboxmailing_civicrm_upgrade($op, CRM_Queue_Queue $queue = NULL) {
  return _mailboxmailing_civix_civicrm_upgrade($op, $queue);
}

/**
 * Implements hook_civicrm_managed().
 *
 * Generate a list of entities to create/deactivate/delete when this module
 * is installed, disabled, uninstalled.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_managed
 */
function mailboxmailing_civicrm_managed(&$entities) {
  _mailboxmailing_civix_civicrm_managed($entities);
}

/**
 * Implements hook_civicrm_caseTypes().
 *
 * Generate a list of case-types.
 *
 * Note: This hook only runs in CiviCRM 4.4+.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_caseTypes
 */
function mailboxmailing_civicrm_caseTypes(&$caseTypes) {
  _mailboxmailing_civix_civicrm_caseTypes($caseTypes);
}

/**
 * Implements hook_civicrm_angularModules().
 *
 * Generate a list of Angular modules.
 *
 * Note: This hook only runs in CiviCRM 4.5+. It may
 * use features only available in v4.6+.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_angularModules
 */
function mailboxmailing_civicrm_angularModules(&$angularModules) {
  _mailboxmailing_civix_civicrm_angularModules($angularModules);
}

/**
 * Implements hook_civicrm_alterSettingsFolders().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_alterSettingsFolders
 */
function mailboxmailing_civicrm_alterSettingsFolders(&$metaDataFolders = NULL) {
  _mailboxmailing_civix_civicrm_alterSettingsFolders($metaDataFolders);
}

/**
 * Implements hook_civicrm_entityTypes().
 *
 * Declare entity types provided by this module.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_entityTypes
 */
function mailboxmailing_civicrm_entityTypes(&$entityTypes) {
  _mailboxmailing_civix_civicrm_entityTypes($entityTypes);
}

/**
 * Implements hook_civicrm_permission().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_permission
 */
function mailboxmailing_civicrm_permission(&$permissions) {
  $permissions['process mailboxmailing'] = 'Process Mailboxmailing';
}

/**
 * Implements hook_civicrm_alterAPIPermissions().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_alterAPIPermissions
 */
function mailboxmailing_civicrm_alterAPIPermissions($entity, $action, &$params, &$permissions) {
  // Restrict API calls to the permission.
  $permissions['mailboxmailing_mail_settings']['create'] = array('administer CiviCRM');
  $permissions['mailboxmailing_mail_settings']['delete']  = array('administer CiviCRM');
  $permissions['mailboxmailing_mail_settings']['get']  = array('administer CiviCRM');
  $permissions['job']['process_mailboxmailing']  = array('process mailboxmailing');
}

// --- Functions below this ship commented out. Uncomment as required. ---

/**
 * Implements hook_civicrm_preProcess().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_preProcess
 *
function mailboxmailing_civicrm_preProcess($formName, &$form) {

} // */

/**
 * Implements hook_civicrm_navigationMenu().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_navigationMenu
 *
function mailboxmailing_civicrm_navigationMenu(&$menu) {
  _mailboxmailing_civix_insert_navigation_menu($menu, NULL, array(
    'label' => E::ts('The Page'),
    'name' => 'the_page',
    'url' => 'civicrm/the-page',
    'permission' => 'access CiviReport,access CiviContribute',
    'operator' => 'OR',
    'separator' => 0,
  ));
  _mailboxmailing_civix_navigationMenu($menu);
} // */
