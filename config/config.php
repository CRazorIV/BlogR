<?php
// Prevent direct access to this file
if (basename($_SERVER['PHP_SELF']) === basename(__FILE__)) {
    die('Direct access not permitted');
}

// Database Configuration
define('DB_HOST', 'localhost');
define('DB_NAME', 'programming_community');
define('DB_USER', 'admin_blog');
define('DB_PASS', 'tschoppedhuzz');

// Application Configuration
define('SITE_NAME', 'Programming Community');
define('SITE_URL', 'http://localhost'); // Change this to your domain in production
define('MAX_POST_LENGTH', 1000);
define('MAX_UPLOAD_SIZE', 5242880); // 5MB in bytes
define('ALLOWED_IMAGE_TYPES', ['image/jpeg', 'image/png', 'image/gif']);
define('UPLOAD_PATH', __DIR__ . '/../uploads/');

// Security Configuration
define('HASH_COST', 12); // For password_hash()
define('SESSION_LIFETIME', 86400); // 24 hours in seconds
define('REMEMBER_ME_LIFETIME', 2592000); // 30 days in seconds

// Initialize secure session
function initSession() {
    ini_set('session.cookie_httponly', 1);
    ini_set('session.use_only_cookies', 1);
    ini_set('session.cookie_samesite', 'Strict');
    
    // Enable secure cookie in production (HTTPS only)
    if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
        ini_set('session.cookie_secure', 1);
    }
    
    session_start();
    
    // Generate CSRF token if not exists
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
}

// Error Reporting (disable in production)
if ($_SERVER['SERVER_NAME'] === 'localhost') {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}

// Time Zone
date_default_timezone_set('UTC');

// Initialize session with secure settings
initSession();

// Create uploads directory if it doesn't exist
if (!file_exists(UPLOAD_PATH)) {
    mkdir(UPLOAD_PATH, 0755, true);
}
?> 