# Example and Hints

## Example

You can configure the extension to allow a selected subgroup of team members to send emails to all members of the team. In this example, you have to create a CiviCRM-group for the members allowed to send those emails (let’s call the group “Teammail – senders”). Then you need the group of recipients, that includes all team members (let’s call the group “Teammail”). As a next step, we need an email address to which the senders will address their team mails (i.e. [teammails@example.com](mailto:teammails@example.com)). After creating this new IMAP or POP3 mailbox, we can configure the Mailbox Mailing extension with the corresponding settings, set the sender and recipient groups and so on. Then, every email sent by the allowed team members to [teammails@example.com](mailto:teammails@example.com) will be forwarded (as a CiviCRM mailing) to all the team members. If you have activated this option, the mailing will be archived.

## Hints

- **Subfolders:** Instead of a special mailbox for each configuration you want to use, you can define mailbox rules in just one catchall mailbox. This way, emails matching a rule will be moved to a subfolder of this mailbox. As you can define precisely where the Mailbox Mailing extension should check for new mails, you can set different subfolders of the same mailbox as source of the different configurations.
    
- **Spam filters:** To avoid your mailings being considered spam, you should only allow senders who use email addresses that have the SMTP server of your CiviCRM listed in the SPF record of their DNS. This is important because the sender of the original email will be named as sender of the CiviCRM bulk mail.
