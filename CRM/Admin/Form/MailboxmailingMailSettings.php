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
 * Form controller class
 *
 * @see https://wiki.civicrm.org/confluence/display/CRMDOC/QuickForm+Reference
 */
class CRM_Admin_Form_MailboxmailingMailSettings extends CRM_Admin_Form {

  /**
   * Build the form object.
   */
  public function buildQuickForm() {
    parent::buildQuickForm();
    $this->setPageTitle(E::ts('Mailbox'));

    if ($this->_action & CRM_Core_Action::DELETE) {
      return;
    }

    $this->applyFilter('__ALL__', 'trim');

    //get the attributes.
    $attributes = CRM_Core_DAO::getAttribute('CRM_Mailboxmailing_DAO_MailboxmailingMailSettings');

    //build setting form
    $this->add('text', 'name', E::ts('Name'), $attributes['name'], TRUE);

    $this->add('select', 'protocol',
      E::ts('Protocol'),
      array('' => E::ts('- select -')) + CRM_Core_PseudoConstant::get('CRM_Mailboxmailing_DAO_MailboxmailingMailSettings', 'protocol'),
      TRUE
    );

    $this->add('text', 'server', E::ts('Server'), $attributes['server']);

    $this->add('text', 'username', E::ts('Username'), array('autocomplete' => 'off'));

    $this->add('password', 'password', E::ts('Password'), array('autocomplete' => 'off'));

    $this->add('text', 'source', E::ts('Source'), $attributes['source']);

    $this->add('checkbox', 'is_ssl', E::ts('Use SSL?'));

    $this->add(
      'select',
      'sender_group_id',
      E::ts('Sender Group'),
      array('' => E::ts('- select group -'))
      + CRM_Contact_BAO_Group::getGroupsHierarchy(
        CRM_Core_PseudoConstant::group(),
        NULL,
        '└ ',
        TRUE
      )
    );

    $this->add(
      'select',
      'recipient_group_id',
      E::ts('Recipient Group'),
      array('' => E::ts('- select group -'))
      + CRM_Contact_BAO_Group::getGroupsHierarchy(
        CRM_Core_PseudoConstant::group(),
        NULL,
        '└ ',
        TRUE
      )
    );

    $this->add(
      'text',
      'subject',
      E::ts('Subject'),
      $attributes['subject']
    );

    $this->add(
      'select',
      'from_email_address_id',
      E::ts('From E-Mail Address'),
      array('' => E::ts('- Default -')) + CRM_Core_BAO_Email::domainEmails()
    );

    $this->add(
      'checkbox',
      'notify_disallowed_sender',
      E::ts('Notify disallowed sender')
    );

    $this->add(
      'text',
      'notify_disallowed_sender_subject',
      E::ts('Disallowed sender notification subject'),
      $attributes['notify_disallowed_sender_subject']
    );

    $this->add(
      'textarea',
      'notify_disallowed_sender_template',
      E::ts('Template for notifications of disallowed sender')
    );

    $this->add(
      'checkbox',
      'notify_sender_errors',
      E::ts('Notify sender about errors')
    );

    $this->add(
      'text',
      'notify_sender_errors_subject',
      E::ts('Error notification subject'),
      $attributes['notify_sender_errors_subject']
    );

    $this->add(
      'textarea',
      'notify_sender_errors_template',
      E::ts('Template for error notifications of sender')
    );

    $this->add(
      'select',
      'notification_activity_type_id',
      E::ts('Notification Activity Type'),
      array('' => E::ts('- Do not create activities -')) + CRM_Activity_BAO_Activity::buildOptions('activity_type_id')
    );

    $this->add(
      'checkbox',
      'archive_mailing',
      E::ts('Archive Mailing')
    );
  }

  /**
   * Add local and global form rules.
   */
  public function addRules() {
    $this->addFormRule(array('CRM_Admin_Form_MailboxmailingMailSettings', 'formRule'));
  }

  public function getDefaultEntity() {
    return 'MailboxmailingMailSettings';
  }

  /**
   * Add local and global form rules.
   */
  public function setDefaultValues() {
    $defaults = parent::setDefaultValues();

    return $defaults;
  }

  /**
   * Global validation rules for the form.
   *
   * @param array $fields
   *   Posted values of the form.
   *
   * @return array
   *   list of errors to be posted back to the form
   */
  public static function formRule($fields) {
    $errors = array();

    return empty($errors) ? TRUE : $errors;
  }

  /**
   * Process the form submission.
   */
  public function postProcess() {
    if ($this->_action & CRM_Core_Action::DELETE) {
      CRM_Mailboxmailing_BAO_MailboxmailingMailSettings::deleteMailSettings($this->_id);
      CRM_Core_Session::setStatus("", E::ts('Mail Setting Deleted.'), "success");
      return;
    }

    //get the submitted form values.
    $formValues = $this->controller->exportValues($this->_name);

    //form fields.
    $fields = array(
      'name',
      'server',
      'protocol',
      'port',
      'username',
      'password',
      'source',
      'is_ssl',
      'sender_group_id',
      'recipient_group_id',
      'subject',
      'from_email_address_id',
      'notify_disallowed_sender',
      'notify_disallowed_sender_subject',
      'notify_disallowed_sender_template',
      'notify_sender_errors',
      'notify_sender_errors_subject',
      'notify_sender_errors_template',
      'notification_activity_type_id',
      'archive_mailing',
    );

    $params = array();
    foreach ($fields as $f) {
      if (in_array($f, array(
        'is_ssl',
      ))) {
        $params[$f] = CRM_Utils_Array::value($f, $formValues, FALSE);
      }
      else {
        $params[$f] = CRM_Utils_Array::value($f, $formValues);
      }
    }

    $params['domain_id'] = CRM_Core_Config::domainID();

    // assign id only in update mode
    $status = E::ts('Mailbox settings have been saved.');
    if ($this->_action & CRM_Core_Action::UPDATE) {
      $params['id'] = $this->_id;
      $status = E::ts('Mailbox settings have been updated.');
    }

    $mailSettings = CRM_Mailboxmailing_BAO_MailboxmailingMailSettings::create($params);

    if ($mailSettings->id) {
      CRM_Core_Session::setStatus($status, E::ts("Saved"), "success");
    }
    else {
      CRM_Core_Session::setStatus("", E::ts('Changes Not Saved.'), "info");
    }
  }

}
