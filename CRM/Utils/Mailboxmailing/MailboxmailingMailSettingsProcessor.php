<?php

class CRM_Utils_Mailboxmailing_MailboxmailingMailSettingsProcessor {

  public static function process($parameters) {
    //get all mail settings.
    $allMailSettings = array();
    $mailSetting = new CRM_Mailboxmailing_DAO_MailboxmailingMailSettings();

    $allProtocols = CRM_Core_PseudoConstant::get('CRM_Mailboxmailing_DAO_MailboxmailingMailSettings', 'protocol');

    //multi-domain support for mail settings. CRM-5244
    $mailSetting->domain_id = CRM_Core_Config::domainID();

    $mailSetting->copyValues($parameters);

    //find all mail settings.
    $mailSetting->find();
    while ($mailSetting->fetch()) {
      //replace protocol value with name
      $mailSetting->protocol = CRM_Utils_Array::value($mailSetting->protocol, $allProtocols);
      CRM_Core_DAO::storeValues($mailSetting, $allMailSettings[$mailSetting->id]);

      // TODO: Add all the drama here.
    }
  }

}
