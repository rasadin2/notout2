<?php
/**
 * Emergency Login Diagnostic & Fix Script
 * Place this in WordPress root directory
 * Access via: http://localhost/notout/emergency-login-fix.php
 * DELETE THIS FILE after fixing the issue!
 */

// Load WordPress
require_once('wp-load.php');

// Security check - only allow from localhost
if ($_SERVER['REMOTE_ADDR'] !== '127.0.0.1' && $_SERVER['REMOTE_ADDR'] !== '::1') {
    die('Access denied. This script only works from localhost.');
}

echo '<h1>WordPress Login Diagnostic Tool</h1>';
echo '<hr>';

// 1. Check PHP Sessions
echo '<h2>1. PHP Session Check</h2>';
session_start();
if (isset($_SESSION)) {
    echo '✅ PHP Sessions: <strong>WORKING</strong><br>';
    echo 'Session ID: ' . session_id() . '<br>';
    echo 'Session Save Path: ' . session_save_path() . '<br>';
} else {
    echo '❌ PHP Sessions: <strong>NOT WORKING</strong><br>';
}
echo '<hr>';

// 2. Check WordPress Constants
echo '<h2>2. WordPress Configuration</h2>';
echo 'WP_HOME: <strong>' . (defined('WP_HOME') ? WP_HOME : 'Not set') . '</strong><br>';
echo 'WP_SITEURL: <strong>' . (defined('WP_SITEURL') ? WP_SITEURL : 'Not set') . '</strong><br>';
echo 'COOKIE_DOMAIN: <strong>' . (defined('COOKIE_DOMAIN') ? COOKIE_DOMAIN : 'Not set') . '</strong><br>';
echo 'COOKIEPATH: <strong>' . (defined('COOKIEPATH') ? COOKIEPATH : 'Not set') . '</strong><br>';
echo 'SITECOOKIEPATH: <strong>' . (defined('SITECOOKIEPATH') ? SITECOOKIEPATH : 'Not set') . '</strong><br>';
echo 'ADMIN_COOKIE_PATH: <strong>' . (defined('ADMIN_COOKIE_PATH') ? ADMIN_COOKIE_PATH : 'Not set') . '</strong><br>';
echo '<hr>';

// 3. Check Database Connection
echo '<h2>3. Database Connection</h2>';
global $wpdb;
if ($wpdb->check_connection()) {
    echo '✅ Database: <strong>CONNECTED</strong><br>';
    $user_count = $wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->users}");
    echo "Total Users: <strong>{$user_count}</strong><br>";
} else {
    echo '❌ Database: <strong>NOT CONNECTED</strong><br>';
}
echo '<hr>';

// 4. List Users
echo '<h2>4. WordPress Users</h2>';
$users = get_users(['number' => 10]);
foreach ($users as $user) {
    echo "ID: {$user->ID} | Username: <strong>{$user->user_login}</strong> | Email: {$user->user_email}<br>";
}
echo '<hr>';

// 5. Emergency Login Form
echo '<h2>5. Emergency Login (Direct)</h2>';
if (isset($_POST['emergency_login'])) {
    $username = sanitize_text_field($_POST['username']);
    $password = $_POST['password'];

    $user = wp_authenticate($username, $password);

    if (is_wp_error($user)) {
        echo '<div style="background: #fee; padding: 15px; border: 2px solid #c00; border-radius: 5px;">';
        echo '❌ <strong>Login Failed</strong><br>';
        echo 'Error: ' . $user->get_error_message();
        echo '</div><br>';
    } else {
        // Manually set auth cookie
        wp_set_auth_cookie($user->ID, true);

        echo '<div style="background: #efe; padding: 15px; border: 2px solid #0c0; border-radius: 5px;">';
        echo '✅ <strong>Login Successful!</strong><br>';
        echo 'User: ' . $user->user_login . '<br>';
        echo 'Redirecting to dashboard...<br>';
        echo '</div>';
        echo '<script>setTimeout(function(){ window.location.href = "' . admin_url() . '"; }, 2000);</script>';
    }
}

echo '<form method="POST" style="background: #f0f0f0; padding: 20px; border-radius: 5px;">';
echo '<label>Username: <input type="text" name="username" required style="margin: 10px; padding: 8px;"></label><br>';
echo '<label>Password: <input type="password" name="password" required style="margin: 10px; padding: 8px;"></label><br>';
echo '<button type="submit" name="emergency_login" style="margin: 10px; padding: 10px 20px; background: #0073aa; color: white; border: none; border-radius: 3px; cursor: pointer;">Emergency Login</button>';
echo '</form>';
echo '<hr>';

// 6. Cookie Information
echo '<h2>6. Current Cookies</h2>';
if (empty($_COOKIE)) {
    echo '⚠️ No cookies detected<br>';
} else {
    foreach ($_COOKIE as $name => $value) {
        if (strpos($name, 'wordpress') !== false || strpos($name, 'wp-') !== false) {
            echo "Cookie: <strong>{$name}</strong><br>";
        }
    }
}
echo '<hr>';

// 7. Recommended Actions
echo '<h2>7. Recommended Actions</h2>';
echo '<ol>';
echo '<li><strong>Clear browser cookies</strong> for localhost</li>';
echo '<li>Try the <strong>Emergency Login</strong> form above</li>';
echo '<li>If login fails, check the error message</li>';
echo '<li>Verify your username is: <strong>notout</strong></li>';
echo '<li><strong>DELETE THIS FILE</strong> after fixing the issue for security</li>';
echo '</ol>';
echo '<hr>';

echo '<p style="color: #c00;"><strong>⚠️ SECURITY WARNING:</strong> Delete this file (emergency-login-fix.php) after fixing the login issue!</p>';
?>
