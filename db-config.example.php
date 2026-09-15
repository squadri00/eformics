<?php
/* ===========================================================================
   Database + admin configuration  (TEMPLATE)
   ---------------------------------------------------------------------------
   1. Copy this file to  db-config.php
   2. Fill in your real database credentials.
   3. Replace the admin password hash — generate one with:
        php -r "echo password_hash('your-new-password', PASSWORD_DEFAULT), PHP_EOL;"
   4. Keep db-config.php OUT of version control (see .gitignore).
   =========================================================================== */

return [
    'db' => [
        'host'    => '127.0.0.1',
        'name'    => 'eformicsdb',
        'user'    => 'eformicsdb',
        'pass'    => 'CHANGE_ME',
        'charset' => 'utf8mb4',
    ],

    // Password for the /admin panel, stored as a bcrypt hash (never plain text).
    'admin_password_hash' => 'PASTE_A_HASH_HERE',
];
