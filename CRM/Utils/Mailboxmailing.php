<?php

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
   * @throws \CiviCRM_API3_Exception
   */
  public static function resolveCustomField($field_name) {
    $custom_field = civicrm_api3('CustomField', 'getsingle', array(
      'custom_group_id' => 'mailing_mailboxmailing',
      'name' => $field_name,
    ));
    return 'custom_' . $custom_field['id'];
  }

  /**
   * @param array $input_vars
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
          $variables[$input_var_name][$mail_property] = json_decode(json_encode(isset($mail) ? $mail->$mail_property : ''), TRUE);
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
