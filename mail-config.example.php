<?php
/* ===========================================================================
   Contact form — SMTP / delivery settings  (TEMPLATE)
   ---------------------------------------------------------------------------
   1. Copy this file to  mail-config.php
   2. Fill in the values below with credentials from your email host or a
      transactional email provider (Postmark, Brevo, SendGrid, Mailgun,
      Zoho, Google Workspace, Microsoft 365, …).
   3. Keep mail-config.php OUT of version control (see .gitignore).
   =========================================================================== */

return [

    /* ---- Where enquiries are delivered ---- */
    'to'         => 'hello@eformics.com',
    'to_name'    => 'Eformics Systems',

    /* ---- The "From" address on the email ----
       Must be an address on a domain the SMTP account is allowed to send as.
       Do NOT use the visitor's address here (that goes in Reply-To). */
    'from'       => 'website@eformics.com',
    'from_name'  => 'Eformics Website',

    /* ---- SMTP server ----
       Examples:
         Postmark : smtp.postmarkapp.com   port 587  tls
         Brevo    : smtp-relay.brevo.com   port 587  tls
         SendGrid : smtp.sendgrid.net      port 587  tls
         Gmail/GW : smtp.gmail.com         port 465  ssl   (use an App Password)
         M365     : smtp.office365.com     port 587  tls
    */
    'host'       => 'smtp.example.com',
    'port'       => 587,          // 587 = STARTTLS (recommended) · 465 = implicit TLS
    'encryption' => 'tls',        // 'tls' for 587 · 'ssl' for 465
    'username'   => 'SMTP_USERNAME',
    'password'   => 'SMTP_PASSWORD',

    /* ---- Troubleshooting ----
       Set to true to write a full SMTP conversation to the PHP error log,
       then set it back to false once delivery works. */
    'debug'      => false,
];
