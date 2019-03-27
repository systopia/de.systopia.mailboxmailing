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

class CRM_Admin_Page_MailboxmailingMailSettings extends CRM_Core_Page_Basic {

  public $useLivePageJS = TRUE;

  /**
   * The action links that we need to display for the browse screen.
   *
   * @var array
   */
  static $_links = NULL;

  /**
   * Get BAO Name.
   *
   * @return string
   *   Classname of BAO.
   */
  public function getBAOName() {
    return 'CRM_Mailboxmailing_BAO_MailboxmailingMailSettings';
  }

  /**
   * Get action Links.
   *
   * @return array
   *   (reference) of action links
   */
  public function &links() {
    if (!(self::$_links)) {
      // helper variable for nicer formatting
      self::$_links = array(
        CRM_Core_Action::UPDATE => array(
          'name' => ts('Edit'),
          'url' => 'civicrm/admin/mailboxmailing',
          'qs' => 'action=update&id=%%id%%&reset=1',
          'title' => ts('Edit Mail Settings'),
        ),
        CRM_Core_Action::DELETE => array(
          'name' => ts('Delete'),
          'url' => 'civicrm/admin/mailboxmailing',
          'qs' => 'action=delete&id=%%id%%',
          'title' => ts('Delete Mail Settings'),
        ),
      );
    }

    return self::$_links;
  }

  /**
   * Browse all mail settings.
   */
  public function browse() {
    //get all mail settings.
    $allMailSettings = array();
    $mailSetting = new CRM_Mailboxmailing_DAO_MailboxmailingMailSettings();

    $allProtocols = CRM_Core_PseudoConstant::get('CRM_Mailboxmailing_DAO_MailboxmailingMailSettings', 'protocol');

    //multi-domain support for mail settings. CRM-5244
    $mailSetting->domain_id = CRM_Core_Config::domainID();

    //find all mail settings.
    $mailSetting->find();
    while ($mailSetting->fetch()) {
      //replace protocol value with name
      $mailSetting->protocol = CRM_Utils_Array::value($mailSetting->protocol, $allProtocols);
      CRM_Core_DAO::storeValues($mailSetting, $allMailSettings[$mailSetting->id]);

      //form all action links
      $action = array_sum(array_keys($this->links()));

      //add action links.
      $allMailSettings[$mailSetting->id]['action'] = CRM_Core_Action::formLink(self::links(), $action,
        array('id' => $mailSetting->id),
        ts('more'),
        FALSE,
        'mailboxmailingMailSettings.manage.action',
        'MailboxmailingMailSettings',
        $mailSetting->id
      );
    }

    $this->assign('rows', $allMailSettings);
  }

  /**
   * Get name of edit form.
   *
   * @return string
   *   Classname of edit form.
   */
  public function editForm() {
    return 'CRM_Admin_Form_MailboxmailingMailSettings';
  }

  /**
   * Get edit form name.
   *
   * @return string
   *   name of this page.
   */
  public function editName() {
    return 'Mail Settings';
  }

  /**
   * Get user context.
   *
   * @param null $mode
   *
   * @return string
   *   user context.
   */
  public function userContext($mode = NULL) {
    return 'civicrm/admin/mailboxmailing';
  }

}
