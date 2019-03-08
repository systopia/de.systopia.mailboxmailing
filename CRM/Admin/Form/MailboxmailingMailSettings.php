<?php

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
    $this->setPageTitle(E::ts('Mail Account'));

    if ($this->_action & CRM_Core_Action::DELETE) {
      return;
    }

    $this->applyFilter('__ALL__', 'trim');

    //get the attributes.
    $attributes = CRM_Core_DAO::getAttribute('CRM_Mailboxmailing_DAO_MailboxmailingMailSettings');

    //build setting form
    $this->add('text', 'name', E::ts('Name'), $attributes['name'], TRUE);

    $this->add('text', 'domain', E::ts('Email Domain'), $attributes['domain'], TRUE);
    $this->addRule('domain', E::ts('Email domain must use a valid internet domain format (e.g. \'example.org\').'), 'domain');

    $this->add('text', 'localpart', E::ts('Localpart'), $attributes['localpart']);

    $this->add('text', 'return_path', E::ts('Return-Path'), $attributes['return_path']);
    $this->addRule('return_path', E::ts('Return-Path must use a valid email address format.'), 'email');

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
      ts('Subject'),
      $attributes['subject']
    );

    $this->add(
      'select',
      'from_email_address_id',
      E::ts('From E-Mail Address'),
      CRM_Core_BAO_Email::domainEmails()
    );

    $this->add(
      'checkbox',
      'notify_disallowed_sender',
      E::ts('Notify disallowed sender')
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
    // Check for default from email address and organization (domain) name. Force them to change it.
    if ($fields['domain'] == 'EXAMPLE.ORG') {
      $errors['domain'] = E::ts('Please enter a valid domain for this mailbox account (the part after @).');
    }

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
      'domain',
      'localpart',
      'server',
      'return_path',
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
      'notify_disallowed_sender_template',
      'notify_sender_errors',
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
    $status = E::ts('Your New  Email Settings have been saved.');
    if ($this->_action & CRM_Core_Action::UPDATE) {
      $params['id'] = $this->_id;
      $status = E::ts('Your Email Settings have been updated.');
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
