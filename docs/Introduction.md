# Documentation MailboxMailing

## Introduction

The MailboxMailing extension provides a way to connect shared mailboxes to CiviCRM. These mailboxes are configured to be scanned for incoming massages regularly, which allows the creation and sending of CiviCRM mailings via e-mail. These mailings will be sent to a configurable group of recipients without using the CiviCRM user interface. The extension is especially useful for organizations that manage large volumes of emails or want to centralize their email campaign responses.

### Features

- Configurable mailboxes (or folders within a single mailbox) to be used as mailing lists
    
- Regular scanning and processing of mailings via cron job
    
- Support for file attachments and multipart e-mail
    
- Bounce processing and reporting to the mailing author
    
- Configurable allowed senders and recipients using CiviCRM groups
    
- Notification of recipients about disallowed answers to a mailing e-mail
    
- Archiving sent mailings
    
- Token replacement in mailing subject
    

## Installation

Install as any other CiviCRM extension.