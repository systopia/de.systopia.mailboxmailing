{*-------------------------------------------------------+
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
+--------------------------------------------------------*}

<div class="crm-block crm-form-block crm-mailboxmailing-mail-settings-form-block">
  <div class="crm-submit-buttons">{include file="CRM/common/formButtons.tpl" location="top"}</div>
  {if $action eq 8}
    <div class="messages status no-popup">
      <div class="icon inform-icon"></div>
      {ts domain="de.systopia.mailboxmailing"}WARNING: Deleting this option will result in the loss of mail settings data.{/ts} {ts domain="de.systopia.mailboxmailing"}Do you want to continue?{/ts}
    </div>
    <div class="crm-submit-buttons">{include file="CRM/common/formButtons.tpl" location="top"}</div>
  {else}
    <table class="form-layout-compressed">

      <tr class="crm-mailboxmailing-mail-settings-form-block-name">
        <td class="label">{$form.name.label}</td>
        <td>{$form.name.html}</td>
      </tr>
      <tr>
        <td class="label">&nbsp;</td>
        <td class="description">{ts domain="de.systopia.mailboxmailing"}Name of this group of settings.{/ts}</td>
      </tr>

      <tr class="crm-mailboxmailing-mail-settings-form-block-server">
        <td class="label">{$form.server.label}</td>
        <td>{$form.server.html}</td>
      </tr>
      <tr>
        <td class="label">&nbsp;</td>
        <td class="description">{ts domain="de.systopia.mailboxmailing"}Name or IP address of mail server machine.{/ts}</td>
      </tr>

      <tr class="crm-mailboxmailing-mail-settings-form-block-username">
        <td class="label">{$form.username.label}</td>
        <td>{$form.username.html}</td>
      </tr>
      <tr>
        <td class="label">&nbsp;</td>
        <td class="description">{ts domain="de.systopia.mailboxmailing"}Username to use when polling (for IMAP and POP3).{/ts}</td>
      </tr>

      <tr class="crm-mailboxmailing-mail-settings-form-block-password">
        <td class="label">{$form.password.label}</td>
        <td>{$form.password.html}</td>
      </tr>
      <tr>
        <td class="label">&nbsp;</td>
        <td class="description">{ts domain="de.systopia.mailboxmailing"}Password to use when polling (for IMAP and POP3).{/ts}</td>
      </tr>

      <tr class="crm-mailboxmailing-mail-settings-form-block-protocol">
        <td class="label">{$form.protocol.label}</td>
        <td>{$form.protocol.html}</td>
      </tr>
      <tr>
        <td class="label">&nbsp;</td>
        <td class="description">{ts domain="de.systopia.mailboxmailing"}Name of the protocol to use for polling.{/ts}</td>
      </tr>

      <tr class="crm-mailboxmailing-mail-settings-form-block-source">
        <td class="label">{$form.source.label}</td>
        <td>{$form.source.html}</td>
      </tr>
      <tr>
        <td class="label">&nbsp;</td>
        <td class="description">{ts domain="de.systopia.mailboxmailing"}Folder to poll from when using IMAP (will default to INBOX when empty), path to poll from when using Maildir, etc..{/ts}</td>
      </tr>

      <tr class="crm-mailboxmailing-mail-settings-form-block-is_ssl">
        <td class="label">{$form.is_ssl.label}</td>
        <td>{$form.is_ssl.html}</td>
      </tr>
      <tr>
        <td class="label">&nbsp;</td>
        <td class="description">{ts domain="de.systopia.mailboxmailing"}Whether to use SSL for IMAP and POP3 or not.{/ts}</td>
      </tr>

      <tr class="crm-mailboxmailing-mail-settings-form-block-sender_group">
        <td class="label">{$form.sender_group_id.label}</td>
        <td>{$form.sender_group_id.html}</td>
      </tr>
      <tr>
        <td class="label">&nbsp;</td>
        <td class="description">{ts domain="de.systopia.mailboxmailing"}The group of contacts allowed to send mailings using these MailSettings.{/ts}</td>
      </tr>

      <tr class="crm-mailboxmailing-mail-settings-form-block-recipient_group">
        <td class="label">{$form.recipient_group_id.label}</td>
        <td>{$form.recipient_group_id.html}</td>
      </tr>
      <tr>
        <td class="label">&nbsp;</td>
        <td class="description">{ts domain="de.systopia.mailboxmailing"}The group of contacts to receive mailings using these MailSettings.{/ts}</td>
      </tr>

      <tr class="crm-mailboxmailing-mail-settings-form-block-subject">
        <td class="label">{$form.subject.label}&nbsp;<a onclick='CRM.help("Smarty", {literal}{"id":"id-smarty","file":"CRM\/Admin\/Form\/MailboxmailingMailSettings"}{/literal}); return false;' href="#" title="{ts domain="de.systopia.mailboxmailing"}Help{/ts}" class="helpicon">&nbsp;</a></td>
        <td>{$form.subject.html}</td>
      </tr>
      <tr>
        <td class="label">&nbsp;</td>
        <td class="description">{ts domain="de.systopia.mailboxmailing"}A pattern to use as the subject for the Mailing.{/ts}</td>
      </tr>

      <tr class="crm-mailboxmailing-mail-settings-form-block-from_email_address_id">
        <td class="label">{$form.from_email_address_id.label}</td>
        <td>{$form.from_email_address_id.html}</td>
      </tr>
      <tr>
        <td class="label">&nbsp;</td>
        <td class="description">{ts domain="de.systopia.mailboxmailing"}The from email address to use when sending notifications.{/ts}</td>
      </tr>

      <tr class="crm-mailboxmailing-mail-settings-form-block-notify_disallowed_sender">
        <td class="label">{$form.notify_disallowed_sender.label}</td>
        <td>{$form.notify_disallowed_sender.html}</td>
      </tr>
      <tr>
        <td class="label">&nbsp;</td>
        <td class="description">{ts domain="de.systopia.mailboxmailing"}Whether to notify disallowed sender.{/ts}</td>
      </tr>

      <tr class="crm-mailboxmailing-mail-settings-form-block-notify_disallowed_sender_subject">
        <td class="label">{$form.notify_disallowed_sender_subject.label}&nbsp;<a onclick='CRM.help("Smarty", {literal}{"id":"id-notify_disallowed_sender_subject","file":"CRM\/Admin\/Form\/MailboxmailingMailSettings"}{/literal}); return false;' href="#" title="{ts domain="de.systopia.mailboxmailing"}Help{/ts}" class="helpicon">&nbsp;</a></td>
        <td>{$form.notify_disallowed_sender_subject.html}</td>
      </tr>
      <tr>
        <td class="label">&nbsp;</td>
        <td class="description">{ts domain="de.systopia.mailboxmailing"}The subject to use for notifications sent to disallowed senders.{/ts}</td>
      </tr>

      <tr class="crm-mailboxmailing-mail-settings-form-block-notify_disallowed_sender_template">
        <td class="label">{$form.notify_disallowed_sender_template.label}&nbsp;<a onclick='CRM.help("Smarty", {literal}{"id":"id-notify_disallowed_sender_template","file":"CRM\/Admin\/Form\/MailboxmailingMailSettings"}{/literal}); return false;' href="#" title="{ts domain="de.systopia.mailboxmailing"}Help{/ts}" class="helpicon">&nbsp;</a></td>
        <td>{$form.notify_disallowed_sender_template.html}</td>
      </tr>
      <tr>
        <td class="label">&nbsp;</td>
        <td class="description">{ts domain="de.systopia.mailboxmailing"}A Smarty template to use for notifications sent to disallowed senders.{/ts}</td>
      </tr>

      <tr class="crm-mailboxmailing-mail-settings-form-block-notify_sender_errors">
        <td class="label">{$form.notify_sender_errors.label}</td>
        <td>{$form.notify_sender_errors.html}</td>
      </tr>
      <tr>
        <td class="label">&nbsp;</td>
        <td class="description">{ts domain="de.systopia.mailboxmailing"}Whether to notify senders about errors.{/ts}</td>
      </tr>

      <tr class="crm-mailboxmailing-mail-settings-form-block-notify_sender_errors_subject">
        <td class="label">{$form.notify_sender_errors_subject.label}&nbsp;<a onclick='CRM.help("Smarty", {literal}{"id":"id-notify_sender_errors_subject","file":"CRM\/Admin\/Form\/MailboxmailingMailSettings"}{/literal}); return false;' href="#" title="{ts domain="de.systopia.mailboxmailing"}Help{/ts}" class="helpicon">&nbsp;</a></td>
        <td>{$form.notify_sender_errors_subject.html}</td>
      </tr>
      <tr>
        <td class="label">&nbsp;</td>
        <td class="description">{ts domain="de.systopia.mailboxmailing"}The subject to use for notifications about errors sent to senders.{/ts}</td>
      </tr>

      <tr class="crm-mailboxmailing-mail-settings-form-block-notify_sender_errors_template">
        <td class="label">{$form.notify_sender_errors_template.label}&nbsp;<a onclick='CRM.help("Smarty", {literal}{"id":"id-notify_sender_errors_template","file":"CRM\/Admin\/Form\/MailboxmailingMailSettings"}{/literal}); return false;' href="#" title="{ts domain="de.systopia.mailboxmailing"}Help{/ts}" class="helpicon">&nbsp;</a></td>
        <td>{$form.notify_sender_errors_template.html}</td>
      </tr>
      <tr>
        <td class="label">&nbsp;</td>
        <td class="description">{ts domain="de.systopia.mailboxmailing"}A Smarty template to use for notifications about errors sent to senders.{/ts}</td>
      </tr>

      <tr class="crm-mailboxmailing-mail-settings-form-block-notification_activity_type_id">
        <td class="label">{$form.notification_activity_type_id.label}</td>
        <td>{$form.notification_activity_type_id.html}</td>
      </tr>
      <tr>
        <td class="label"> </td>
        <td class="description">{ts domain="de.systopia.mailboxmailing"}The activity type to use for activities to notify senders about errors.{/ts}</td>
      </tr>

      <tr class="crm-mailboxmailing-mail-settings-form-block-archive_mailing">
        <td class="label">{$form.archive_mailing.label}</td>
        <td>{$form.archive_mailing.html}</td>
      </tr>
      <tr>
        <td class="label">&nbsp;</td>
        <td class="description">{ts domain="de.systopia.mailboxmailing"}Whether to archive mailings after sending.{/ts}</td>
      </tr>

    </table>
    <div class="crm-submit-buttons">{include file="CRM/common/formButtons.tpl" location="bottom"}</div>
  {/if}
</div>
