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

class CRM_Utils_Mailboxmailing {

  /**
   * Retrieves the CiviCRM API parameter name representation of a custom field
   * within the Mailboxmailing extension's custom group for Mailing entities.
   *
   * @param $field_name
   *   The name of the custom field.
   *
   * @return string
   *   The CiviCRM API parameter name representation of the custom field
   *   ("custom_<id>").
   * @throws \CRM_Core_Exception
   */
  public static function resolveCustomField($field_name) {
    $custom_field = civicrm_api3('CustomField', 'getsingle', array(
      'custom_group_id' => 'mailing_mailboxmailing',
      'name' => $field_name,
    ));
    return 'custom_' . $custom_field['id'];
  }

  /**
   * Prepares several object types used by this extension for usage as Smarty
   * variables, transforming them into arrays and filtering for relevant fields.
   *
   * @param array $input_vars
   *   An associative array of variables to make Smarty-compatible. The key of
   *   each array element will be the name of the Smarty variable. Depending on
   *   the class or type of the element, the returned value will be a - possibly
   *   filtered - array representation of the original value.
   *
   * @return array
   */
  public static function getSmartyVariables($input_vars) {
    $variables = array();

    foreach ($input_vars as $input_var_name => $input_var) {
      if (is_a($input_var, '\ezcMail')) {
        /* @var \ezcMail $input_var */
        // All properties accessible through magic getter \ezcMail::__get().
        $mail_properties = array(
          'to',
          'cc',
          'bcc',
          'from',
          'subject',
          'subjectCharset',
          'body',
          'messageId',
          'returnPath',
          'timestamp',
        );
        foreach ($mail_properties as $mail_property) {
          $variables[$input_var_name][$mail_property] = json_decode(json_encode($input_var->$mail_property), TRUE);
        }
      }
      elseif (is_a($input_var, '\CRM_Mailboxmailing_BAO_MailboxmailingMailSettings')) {
        /* @var \CRM_Mailboxmailing_BAO_MailboxmailingMailSettings $input_var */
        $variables[$input_var_name] = $input_var->toArray();
      }
      elseif (is_object($input_var)) {
        /* @var object $input_var */
        $variables[$input_var_name] = (array) $input_var;
      }
      else {
        $variables[$input_var_name] = $input_var;
      }
    }

    return $variables;
  }

}
