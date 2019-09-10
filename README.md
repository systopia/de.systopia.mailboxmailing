# Mailbox Mailing

This extension allows CiviCRM mailings be created by sending e-mails to
mailboxes, that are configured to be scanned for incoming messages regularly.
This makes it possible to let people create and schedule CiviCRM mailings sent
to a configurable group of recipients without using the CiviCRM user interface.

## Features

-   Configurable mailboxes (or folders within a single mailbox) to be used as
    mailing lists
-   Regular scanning and processing of mailings via cron job
-   Support for file attachments and multipart e-mail
-   Bounce processing and reporting to the mailing author
-   Configurable allowed senders and recipients using CiviCRM groups
-   Notification of recipients about disallowed answers to a mailing e-mail
-   Archiving sent mailings
-   Token replacement in mailing subject
