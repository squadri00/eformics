<?php
/* ===========================================================================
   Recodik download — file location  (TEMPLATE)
   ---------------------------------------------------------------------------
   1. Copy this file to  recodik-config.php
   2. Point 'file_path' at the actual Recodik.exe on THIS server, stored
      OUTSIDE the web root (public_html) so it can never be fetched directly
      by URL -- only recodik-download.php, after checking a verified email,
      is allowed to read and stream it.
   3. Keep recodik-config.php OUT of version control (see .gitignore).
   =========================================================================== */

return [
    'file_path'     => '/absolute/path/outside/webroot/Recodik.exe',
    'download_name' => 'Recodik.exe',
];
