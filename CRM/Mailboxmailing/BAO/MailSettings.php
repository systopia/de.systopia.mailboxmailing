<?php
use CRM_Mailboxmailing_ExtensionUtil as E;

class CRM_Mailboxmailing_BAO_MailSettings extends CRM_Mailboxmailing_DAO_MailSettings {

  /**
   * Create a new MailSettings based on array-data
   *
   * @param array $params key-value pairs
   * @return CRM_Mailboxmailing_DAO_MailSettings|NULL
   *
  public static function create($params) {
    $className = 'CRM_Mailboxmailing_DAO_MailSettings';
    $entityName = 'MailSettings';
    $hook = empty($params['id']) ? 'create' : 'edit';

    CRM_Utils_Hook::pre($hook, $entityName, CRM_Utils_Array::value('id', $params), $params);
    $instance = new $className();
    $instance->copyValues($params);
    $instance->save();
    CRM_Utils_Hook::post($hook, $entityName, $instance->id, $instance);

    return $instance;
  } */

}
