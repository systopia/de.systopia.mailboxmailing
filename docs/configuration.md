# Configuration

Before you start with the configuration process, please ensure that the extension is properly installed on a working CiviCRM and that you have access to a functioning mailbox that you can and want to use for your Mailbox Mailing setup.

## Compatibility

The Mailbox Mailing extension is compatible with every IMAP or POP3 mail server. You need access to the mailbox via webmail, or you can use basically every modern mailing program such as Gmail, Outlook, Apple icloud mail, yahoo and many others. You can also use Maildir or Localdir to use the extension without having to connect through a mail server like Outlook or Gmail.

## Set up the Mailbox Connection

To set up the Mailbox Connection, go to *Administer* - *Administration Console* - *Mailbox Mailing settings.* Here you can *add Mailbox settings.* You can set a name for your configuration of settings and create multiple configurations for different mailboxes.

In the process of setting up the Mailbox Connection, you will be asked to enter the following information (only "Name" and "Protocol" are required):

- **Name:** A name for this group of settings.
    
- **Server:** Name or IP address of the mail server machine you want to connect. Please note that the Server address is only required for IMAP and POP3 connections. For Maildir and Localdir, there is no server needed, since these refer to local directories.
    
- **Username:** The username or email address used to access the mailbox.
    
- **Password:** The password used to access the mailbox
    
- **Protocol:** You can select the type of protocol to be used for polling (IMAP, Maildir, POP3, Localdir).
    
- **Source:** Folder or path to poll from. For IMAP/POP3, this is typically "INBOX." For Maildir or Localdir, this is the directory path.
    
- **Use SSL?:** Whether to use SSL (Secure Sockets Layer) for IMAP and POP3 or not. SSL ensures that the connection to the mail server is encrypted and secure. For Maildir and Localdir, there is no need for SSL settings, since they establish no network connection.
    
- **Sender Group:** The group of contacts allowed to send mailings using these Mail Settings. Only emails from contacts in this group will be processed.
    
- **Recipient Group:** The group of contacts to receive mailings using these MailSettings. Only contacts in this group will be sent mailings.
    

The **Sender Group** and **Recipient Group** are CiviCRM contact groups which need to be created beforehand, in order to be available in those setting fields. This feature ensures that only authorized individuals can send emails and that specific groups receive the messages. This allows for effective management of incoming and outgoing emails.

- **Subject:** A pattern to use as the subject for the mailing. Here you can use Tokens to automatically fill in the required data.
    
- **From email address:** The from email address to use when sending notifications.
    
- **Notify disallowed sender:** When this setting is enabled and an email is received from a sender who is disallowed, the system will send a notification to that sender informing them that their email was not accepted or processed.
    
- **Disallowed sender notification subject:** The subject to use for notifications sent to disallowed senders.
    
- **Template for notifications of disallowed sender:** A Smarty template to use for notifications sent to disallowed senders.
    
- **Notify sender about errors:** Whether to notify senders about errors.
    
- **Error notification subject:** The subject to use for notifications about errors sent to senders.
    
- **Template for error notifications of sender:** A Smarty template to use for notifications about errors sent to senders.
    
- **Notification Activity Type:** The activity type to use for activities to notify senders about errors.
    
- **Archive Mailing:** If this option is disabled, mailings will be kept in the "Sent" status. If activated, the mailings will be moved into the "Archived" list.
    

After saving your settings, the Mailbox Connection is created. You can later on change the settings by going to *Administer* - *Administration Console* - *MailboxMailing settings* and clicking "Edit" on the settings that are to be changed.

## Set up a scheduled job

Setting up a scheduled job/cron job is essential for enabling the automatic processing of emails. This ensures that the system periodically or permanently checks for new emails in the configured mailbox and imports them into CiviCRM for further processing. To set up a scheduled job, it is necessary to go to *Administer* - *System Settings* - *Scheduled Jobs*. Create a new cron job by clicking on *\+ add new scheduled job*. There you need to:

- Set a **name**: You can name your scheduled job whatever you like - it makes sense to call it something like "Process MailboxMailing"
    
- (optional) Add a **description**
    
- Set the **frequency** to "Every time cron job is running"
    
- Set the **API CALL Entity** to "Job"
    
- Set the **API CALL Action** to "process_mailboxmailing"
    
- In the **Command parameters** field, you can pass additional parameters that may affect the email processing workflow. If left empty, the system will use default values. This feature allows for greater customization of the email import process.
    
- Leave **Scheduled Run date** empty
    
- Check the checkbox **Is this Scheduled Job active?** to yes.
    

After the configuration of the Scheduled Job, save these settings. Setting the **API CALL Entity** to 'Job' and the **API CALL Action** to 'process_mailboxmailing' is essential, as it triggers the job that regularly checks the mailbox for new messages and imports them into CiviCRM. This is a core mechanism of the extension.