<?php
// ============================================================
// PHONEZONE - App Configuration
// ============================================================

// URL gốc của dự án
define('APP_URL',   Env::get('APP_URL',   'http://localhost/phonezone'));
define('APP_NAME',  Env::get('APP_NAME',  'PhoneZone'));
define('APP_ENV',   Env::get('APP_ENV',   'development'));
define('APP_DEBUG', Env::get('APP_DEBUG', 'true') === 'true');

// Đường dẫn thư mục
define('VIEW_PATH',    ROOT_PATH . '/app/views/');
define('UPLOAD_PATH',  ROOT_PATH . '/public/uploads/');
define('UPLOAD_URL',   APP_URL   . '/public/uploads/');

// Upload
define('UPLOAD_MAX_SIZE', (int) Env::get('UPLOAD_MAX_SIZE', '5242880')); // 5MB
define('ALLOWED_IMG_TYPES', ['image/jpeg', 'image/png', 'image/webp', 'image/gif']);

// Bảo mật
define('SECRET_KEY', Env::get('SECRET_KEY', 'change_this_secret'));

// Phân trang
define('ITEMS_PER_PAGE',       12);
define('ADMIN_ITEMS_PER_PAGE', 20);

// Hiển thị lỗi khi dev
if (APP_DEBUG) {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', 0);
    error_reporting(0);
}

// Timezone
date_default_timezone_set('Asia/Ho_Chi_Minh');

// Múi tiền tệ
define('CURRENCY', 'VNĐ');
