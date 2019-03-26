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

}
