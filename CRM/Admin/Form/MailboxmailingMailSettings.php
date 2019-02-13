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
    $this->setPageTitle(ts('Mail Account'));

    if ($this->_action & CRM_Core_Action::DELETE) {
      return;
    }

    $this->applyFilter('__ALL__', 'trim');

    //get the attributes.
    $attributes = CRM_Core_DAO::getAttribute('CRM_Mailboxmailing_DAO_MailboxmailingMailSettings');

    //build setting form
    $this->add('text', 'name', ts('Name'), $attributes['name'], TRUE);

    $this->add('text', 'domain', ts('Email Domain'), $attributes['domain'], TRUE);
    $this->addRule('domain', ts('Email domain must use a valid internet domain format (e.g. \'example.org\').'), 'domain');

    $this->add('text', 'localpart', ts('Localpart'), $attributes['localpart']);

    $this->add('text', 'return_path', ts('Return-Path'), $attributes['return_path']);
    $this->addRule('return_path', ts('Return-Path must use a valid email address format.'), 'email');

    $this->add('select', 'protocol',
      ts('Protocol'),
      array('' => ts('- select -')) + CRM_Core_PseudoConstant::get('CRM_Mailboxmailing_DAO_MailboxmailingMailSettings', 'protocol'),
      TRUE
    );

    $this->add('text', 'server', ts('Server'), $attributes['server']);

    $this->add('text', 'username', ts('Username'), array('autocomplete' => 'off'));

    $this->add('password', 'password', ts('Password'), array('autocomplete' => 'off'));

    $this->add('text', 'source', ts('Source'), $attributes['source']);

    $this->add('checkbox', 'is_ssl', ts('Use SSL?'));
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
      $errors['domain'] = ts('Please enter a valid domain for this mailbox account (the part after @).');
    }

    return empty($errors) ? TRUE : $errors;
  }

  /**
   * Process the form submission.
   */
  public function postProcess() {
    if ($this->_action & CRM_Core_Action::DELETE) {
      CRM_Mailboxmailing_BAO_MailboxmailingMailSettings::deleteMailSettings($this->_id);
      CRM_Core_Session::setStatus("", ts('Mail Setting Deleted.'), "success");
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
    $status = ts('Your New  Email Settings have been saved.');
    if ($this->_action & CRM_Core_Action::UPDATE) {
      $params['id'] = $this->_id;
      $status = ts('Your Email Settings have been updated.');
    }

    $mailSettings = CRM_Mailboxmailing_BAO_MailboxmailingMailSettings::create($params);

    if ($mailSettings->id) {
      CRM_Core_Session::setStatus($status, ts("Saved"), "success");
    }
    else {
      CRM_Core_Session::setStatus("", ts('Changes Not Saved.'), "info");
    }
  }

}
