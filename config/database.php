<?php
// ============================================================
// PHONEZONE - Database Configuration
// ============================================================

define('DB_HOST', Env::get('DB_HOST', 'localhost'));
define('DB_PORT', Env::get('DB_PORT', '3306'));
define('DB_NAME', Env::get('DB_NAME', 'phonezone'));
define('DB_USER', Env::get('DB_USER', 'root'));
define('DB_PASS', Env::get('DB_PASS', ''));
define('DB_CHARSET', 'utf8mb4');
