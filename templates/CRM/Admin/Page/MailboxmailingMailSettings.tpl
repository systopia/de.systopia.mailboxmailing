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

{if $action eq 1 or $action eq 2 or $action eq 8}
{include file="CRM/Admin/Form/MailboxmailingMailSettings.tpl"}
{else}

<div class="crm-block crm-content-block">
  {if $rows}
    <div id="mSettings">
      <div class="form-item">
        {strip}
          <table cellpadding="0" cellspacing="0" border="0" class="row-highlight">
            <thead class="sticky">
            <th>{ts}Name{/ts}</th>
            <th>{ts}Server{/ts}</th>
            <th>{ts}Username{/ts}</th>
            <th>{ts}Protocol{/ts}</th>
            <th>{ts}Source{/ts}</th>
            <!--<th>{ts}Port{/ts}</th>-->
            <th>{ts}Use SSL?{/ts}</th>
            <th></th>
            </thead>
            {foreach from=$rows item=row}
              <tr id='rowid{$row.id}' class="crm-mailboxmailingMailSettings {cycle values="odd-row,even-row"}">
                <td class="crm-mailboxmailingMailSettings-name">{$row.name}</td>
                <td class="crm-mailboxmailingMailSettings-server">{$row.server}</td>
                <td class="crm-mailboxmailingMailSettings-username">{$row.username}</td>
                <td class="crm-mailboxmailingMailSettings-protocol">{$row.protocol}</td>
                <td class="crm-mailboxmailingMailSettings-source">{$row.source}</td>
                <!--<td>{$row.port}</td>-->
                <td class="crm-mailboxmailingMailSettings-is_ssl">{if $row.is_ssl eq 1} {ts}Yes{/ts} {else} {ts}No{/ts} {/if}</td>
                <td>{$row.action|replace:'xx':$row.id}</td>
              </tr>
            {/foreach}
          </table>
        {/strip}

      </div>
    </div>
  {else}
    <div class="messages status no-popup">
      <img src="{$config->resourceBase}i/Inform.gif" alt="{ts}status{/ts}"/>
      {ts}None found.{/ts}
    </div>
  {/if}
  <div class="action-link">
    {crmButton q="action=add&reset=1" id="newMailboxmailingMailSettings"  icon="plus-circle"}{ts}Add Mail Account{/ts}{/crmButton}
    {crmButton p="civicrm/admin" q="reset=1" class="cancel" icon="times"}{ts}Done{/ts}{/crmButton}
  </div>
  {/if}
</div>
