<?php

use CRM_Mailboxmailing_ExtensionUtil as E;

/**
 * Job.MailboxmailingProcess API specification (optional)
 * This is used for documentation and validation.
 *
 * @param array $spec description of fields supported by this API call
 * @return void
 * @see http://wiki.civicrm.org/confluence/display/CRMDOC/API+Architecture+Standards
 */
function _civicrm_api3_job_process_mailboxmailing_spec(&$spec) {
  $spec['id'] = array(
    'name' => 'id',
    'title' => E::ts('MailboxmailingMailSettings ID'),
    'type' => CRM_Utils_Type::T_INT,
    'api.required' => 0,
    'description' => E::ts('The ID of the MailboxmailingMailSettings configuration to process.'),
  );
}

/**
 * Job.MailboxmailingProcess API
 *
 * @param array $params
 * @return array API result descriptor
 * @see civicrm_api3_create_success
 * @see civicrm_api3_create_error
 * @throws API_Exception
 */
function civicrm_api3_job_process_mailboxmailing($params) {
  // TODO: Check what the locking is all about.
//  $lock = Civi::lockManager()->acquire('worker.mailing.EmailProcessor');
//  if (!$lock->isAcquired()) {
//    return civicrm_api3_create_error('Could not acquire lock, another EmailProcessor process is running');
//  }
  CRM_Utils_Mailboxmailing_MailboxmailingMailSettingsProcessor::process($params);
//  $lock->release();

  return civicrm_api3_create_success(1, $params, 'Job', 'MailboxmailingProcess');
}
