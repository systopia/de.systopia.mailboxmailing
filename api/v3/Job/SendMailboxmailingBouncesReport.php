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
 * Job.send_mailboxmailing_bounces_report API specification (optional)
 * This is used for documentation and validation.
 *
 * @param array $spec description of fields supported by this API call
 * @return void
 * @see http://wiki.civicrm.org/confluence/display/CRMDOC/API+Architecture+Standards
 */
function _civicrm_api3_job_send_mailboxmailing_bounces_report_spec(&$spec) {
  $spec['id'] = array(
    'name' => 'id',
    'title' => E::ts('MailboxmailingMailSettings ID'),
    'type' => CRM_Utils_Type::T_INT,
    'api.required' => 0,
    'description' => E::ts('The ID of the MailboxmailingMailSettings configuration used by Mailings to create bounce reports for.'),
  );
  $spec['mid'] = array(
    'name' => 'mid',
    'title' => E::ts('Mailing ID'),
    'type' => CRM_Utils_Type::T_INT,
    'api.required' => 0,
    'description' => E::ts('The ID of the Mailing to create a bounce report for.'),
  );
}

/**
 * Job.send_mailboxmailing_bounces_report API
 *
 * @param array $params
 * @return array API result descriptor
 * @see civicrm_api3_create_success
 * @see civicrm_api3_create_error
 * @throws API_Exception
 */
function civicrm_api3_job_send_mailboxmailing_bounces_report($params) {
  try {
    $lock = Civi::lockManager()->acquire('worker.mailboxmailing.BouncesReportProcessor');
    if (!$lock->isAcquired()) {
      return civicrm_api3_create_error('Could not acquire lock, another mailboxmailing.BouncesReportProcessor process is running');
    }
    $result = CRM_Utils_Mailboxmailing_BouncesReportProcessor::process($params);
    $lock->release();
    return civicrm_api3_create_success($result, $params, 'Job', 'send_mailboxmailing_bounces_report');
  }
  catch (Exception $exception) {
    return civicrm_api3_create_error($exception->getMessage());
  }
}
