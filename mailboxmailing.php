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
 * Implements hook_civicrm_install().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_install
 */
function mailboxmailing_civicrm_install() {
  // Make the Mailing entity fieldable (add to cg_extend_objects OptionGroup).
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

  // Add a CustomGroup for CustomFields for Mailing entities.
  $custom_group = civicrm_api3('CustomGroup', 'create', array(
    'title' => 'Mailboxmailing',
    'extends' => 'Mailing',
    'name' => 'mailing_mailboxmailing',
    'table_name' => 'civicrm_value_mailing_mailboxmailing',
    'is_reserved' => 1,
  ));

  // Attach a custom field to the Mailing entity for referencing a
  // MailboxmailingMailSettings entity.
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

  // Attach a custom field to the Mailing entity for tracking when a bounce
  // report has been sent to the mailing author.
  civicrm_api3('CustomField', 'create', array(
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

/**
 * Implementation of hook_civicrm_postMailing()
 */
function mailboxmailing_civicrm_postMailing($mailingId) {
  // Archive sent mailings if configured.
  $field = civicrm_api3('CustomField', 'getsingle', array(
    'custom_group_id' => 'mailing_mailboxmailing',
    'name' => 'MailboxmailingMailSettingsId',
  ));
  $mailing_result = civicrm_api3('Mailing', 'getsingle', array(
    'id' => $mailingId,
    'return' => array(
      'custom_' . $field['id'],
    ),
  ));

  $mailSettings = CRM_Mailboxmailing_BAO_MailboxmailingMailSettings::findById($mailing_result['custom_' . $field['id']]);

  if ($mailSettings->archive_mailing) {
    civicrm_api3('Mailing', 'create', array(
      'id' => $mailingId,
      'is_archived' => 1,
    ));
  }
}

/**
 * Implementation of hook_civicrm_alterMailingParams()
 */
function mailboxmailing_civicrm_alterMailParams(&$params, $context) {
  try {
    if (!empty($params['attachments'])) {
      if (!empty($params['job_id'])) {
        $job = civicrm_api3('MailingJob', 'getsingle', array('id' => $params['job_id']));
        if (!empty($job['mailing_id'])) {
          $field = civicrm_api3('CustomField', 'getsingle', array(
            'custom_group_id' => 'mailing_mailboxmailing',
            'name' => 'MailboxmailingMailSettingsId',
          ));
          $mailing = civicrm_api3('Mailing', 'getsingle', array(
            'id' => $job['mailing_id'],
            'return' => array(
              'custom_' . $field['id'],
            ),
          ));
          $mailSettings = CRM_Mailboxmailing_BAO_MailboxmailingMailSettings::findById($mailing['custom_' . $field['id']]);
          // Since we've identified the MailboxmailingMailSettings for this
          // mail, we can assume it's a mailing created by the MailboxMailing
          // extension.

          // We need to create attachments with UTF-8 filenames separately as an
          // instance of Mail_mimePart, since this allows encoding and charset
          // attributes be provided for the mail part.
          // Otherwise, those would be set to CiviCRM's default "ISO 8859-1" and
          // UTF-8 filenames would not be encoded correctly, causing broken
          // filenames in e-mail clients.
          foreach ($params['attachments'] as $file_id => $attachment) {
            $mime_part = new Mail_mimePart('', array(
              'content_type' => $attachment['mime_type'],
              'encoding' => 'base64',
              'charset' => 'UTF-8',
              'disposition' => 'attachment',
              'filename' => $attachment['cleanName'],
              'description' => $attachment['description'],
//              'name_encoding' => 'quoted_printable',
//              'filename_encoding' => 'quoted-printable',
              'headers_charset' => 'UTF-8', // Needed for UTF-8 filenames.
//              'eol' => "\n",
//              'headers' => array(),
              'body_file' => $attachment['fullPath'],
//              'preamble' => NULL,
            ));

            // Set $attachment['fullPath'] to be a Mail_mimePart object, since
            // CRM_Mailing_BAO_Mailing::compose() passes that as the first
            // parameter to Mail_mime::addAttachment(), which in turn checks
            // that with instanceof Mail_mimePart.
            $params['attachments'][$file_id]['fullPath'] = $mime_part;
          }
        }
      }
    }
  }
   catch (Exception $exception) {
    // The processed mail is not relevant to the mailboxmailing extension.
   }
}

// --- Functions below this ship commented out. Uncomment as required. ---

/**
 * Implements hook_civicrm_preProcess().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_preProcess
 *

 // */

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
