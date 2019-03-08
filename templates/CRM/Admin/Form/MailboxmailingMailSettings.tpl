<div class="crm-block crm-form-block crm-mailboxmailing-mail-settings-form-block">
  <div class="crm-submit-buttons">{include file="CRM/common/formButtons.tpl" location="top"}</div>
  {if $action eq 8}
    <div class="messages status no-popup">
      <div class="icon inform-icon"></div>
      {ts}WARNING: Deleting this option will result in the loss of mail settings data.{/ts} {ts}Do you want to continue?{/ts}
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
        <td class="description">{ts}Name of this group of settings.{/ts}</td>
      </tr>

      <tr class="crm-mailboxmailing-mail-settings-form-block-server">
        <td class="label">{$form.server.label}</td>
        <td>{$form.server.html}</td>
      </tr>
      <tr>
        <td class="label">&nbsp;</td>
        <td class="description">{ts}Name or IP address of mail server machine.{/ts}</td>
      </tr>

      <tr class="crm-mailboxmailing-mail-settings-form-block-username">
        <td class="label">{$form.username.label}</td>
        <td>{$form.username.html}</td>
      </tr>
      <tr>
        <td class="label">&nbsp;</td>
        <td class="description">{ts}Username to use when polling (for IMAP and POP3).{/ts}</td>
      </tr>

      <tr class="crm-mailboxmailing-mail-settings-form-block-password">
        <td class="label">{$form.password.label}</td>
        <td>{$form.password.html}</td>
      </tr>
      <tr>
        <td class="label">&nbsp;</td>
        <td class="description">{ts}Password to use when polling (for IMAP and POP3).{/ts}</td>
      </tr>

      <tr class="crm-mailboxmailing-mail-settings-form-block-localpart">
        <td class="label">{$form.localpart.label}</td>
        <td>{$form.localpart.html}</td>
      </tr>
      <tr>
        <td class="label">&nbsp;</td>
        <td class="description">{ts}Optional local part (e.g., 'civimail+' for addresses like civimail+s.1.2@example.com).{/ts}</td>
      </tr>

      <tr class="crm-mailboxmailing-mail-settings-form-block-domain">
        <td class="label">{$form.domain.label}</td>
        <td>{$form.domain.html}</td>
      </tr>
      <tr>
        <td class="label">&nbsp;</td>
        <td class="description">{ts}Email address domain (the part after @).{/ts}</td>
      </tr>

      <tr class="crm-mailboxmailing-mail-settings-form-block-return_path">
        <td class="label">{$form.return_path.label}</td>
        <td>{$form.return_path.html}</td>
      <tr>
      <tr>
        <td class="label">&nbsp;</td>
        <td class="description">{ts}Contents of the Return-Path header.{/ts}</td>
      </tr>

      <tr class="crm-mailboxmailing-mail-settings-form-block-protocol">
        <td class="label">{$form.protocol.label}</td>
        <td>{$form.protocol.html}</td>
      </tr>
      <tr>
        <td class="label">&nbsp;</td>
        <td class="description">{ts}Name of the protocol to use for polling.{/ts}</td>
      </tr>

      <tr class="crm-mailboxmailing-mail-settings-form-block-source">
        <td class="label">{$form.source.label}</td>
        <td>{$form.source.html}</td>
      </tr>
      <tr>
        <td class="label">&nbsp;</td>
        <td class="description">{ts}Folder to poll from when using IMAP (will default to INBOX when empty), path to poll from when using Maildir, etc..{/ts}</td>
      </tr>

      <tr class="crm-mailboxmailing-mail-settings-form-block-is_ssl">
        <td class="label">{$form.is_ssl.label}</td>
        <td>{$form.is_ssl.html}</td>
      </tr>
      <tr>
        <td class="label">&nbsp;</td>
        <td class="description">{ts}Whether to use SSL for IMAP and POP3 or not.{/ts}</td>
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
        <td class="label">{$form.subject.label}&nbsp;<a onclick='CRM.help("Foobar", {literal}{"id":"id-subject","file":"CRM\/Admin\/Form\/MailboxmailingMailSettings"}{/literal}); return false;' href="#" title="{ts domain="de.systopia.mailboxmailing"}Help{/ts}" class="helpicon">&nbsp;</a></td>
        <td>{$form.subject.html}</td>
      </tr>
      <tr>
        <td class="label">&nbsp;</td>
        <td class="description">{ts domain="de.systopia.mailboxmailing"}A pattern to use as the subject for the Mailing.{/ts}</td>
      </tr>

      <tr class="crm-mailboxmailing-mail-settings-form-block-notify_disallowed_sender">
        <td class="label">{$form.notify_disallowed_sender.label}</td>
        <td>{$form.notify_disallowed_sender.html}</td>
      </tr>
      <tr>
        <td class="label">&nbsp;</td>
        <td class="description">{ts domain="de.systopia.mailboxmailing"}Whether to notify disallowed sender.{/ts}</td>
      </tr>

      <tr class="crm-mailboxmailing-mail-settings-form-block-notify_disallowed_sender_template">
        <td class="label">{$form.notify_disallowed_sender_template.label}</td>
        <td>{$form.notify_disallowed_sender_template.html}</td>
      </tr>
      <tr>
        <td class="label">&nbsp;</td>
        <td class="description">{ts domain="de.systopia.mailboxmailing"}Whether to notify disallowed sender.{/ts}</td>
      </tr>

      <tr class="crm-mailboxmailing-mail-settings-form-block-notify_sender_errors">
        <td class="label">{$form.notify_sender_errors.label}</td>
        <td>{$form.notify_sender_errors.html}</td>
      </tr>
      <tr>
        <td class="label">&nbsp;</td>
        <td class="description">{ts domain="de.systopia.mailboxmailing"}Whether to notify senders about errors.{/ts}</td>
      </tr>

      <tr class="crm-mailboxmailing-mail-settings-form-block-notify_sender_errors_template">
        <td class="label">{$form.notify_sender_errors_template.label}</td>
        <td>{$form.notify_sender_errors_template.html}</td>
      </tr>
      <tr>
        <td class="label">&nbsp;</td>
        <td class="description">{ts domain="de.systopia.mailboxmailing"}Whether to notify senders about errors.{/ts}</td>
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
