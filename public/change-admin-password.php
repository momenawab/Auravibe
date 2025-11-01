<?php
/**
 * Change Admin Credentials Script
 * Use this to update admin username/password
 * DELETE THIS FILE after use!
 */

// Database credentials
$DB_PASSWORD = 'rI$n5W9:!4';  // Your database password

$host = 'localhost';
$user = 'u446437128_auravibe';
$pass = $DB_PASSWORD;
$dbname = 'u446437128_auravibe';

// ============================================
// CONFIGURE NEW ADMIN CREDENTIALS HERE
// ============================================
$new_username = 'admin';  // ← Change this
$new_email = 'admin@auravibe.site';  // ← Change this
$new_password = 'YourSecurePassword123!';  // ← CHANGE THIS to a strong password
$new_fullname = 'Administrator';  // ← Change if needed

?>
<!DOCTYPE html>
<html>
<head>
    <title>Change Admin Credentials</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 800px; margin: 50px auto; padding: 20px; background: #f5f5f5; }
        .container { background: white; padding: 30px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .success { color: green; background: #d4edda; padding: 15px; margin: 20px 0; border-left: 4px solid #28a745; border-radius: 4px; }
        .error { color: red; background: #f8d7da; padding: 15px; margin: 20px 0; border-left: 4px solid #dc3545; border-radius: 4px; }
        .warning { background: #fff3cd; padding: 15px; margin: 20px 0; border-left: 4px solid #ffc107; border-radius: 4px; }
        .info { background: #d1ecf1; padding: 15px; margin: 20px 0; border-left: 4px solid #17a2b8; border-radius: 4px; }
        h1 { color: #333; border-bottom: 3px solid #007bff; padding-bottom: 10px; }
        h2 { color: #555; margin-top: 30px; }
        table { border-collapse: collapse; width: 100%; margin: 20px 0; }
        th { background: #007bff; color: white; padding: 12px; text-align: left; }
        td { padding: 12px; border: 1px solid #ddd; }
        .btn { display: inline-block; padding: 12px 24px; background: #007bff; color: white; text-decoration: none; border-radius: 5px; margin: 10px 5px; font-weight: bold; }
        .btn-danger { background: #dc3545; }
        code { background: #f4f4f4; padding: 2px 6px; border-radius: 3px; font-family: monospace; }
    </style>
</head>
<body>

<div class="container">
<h1>🔐 Change Admin Credentials</h1>

<?php
// Check if new password is set
if ($new_password === 'YourSecurePassword123!') {
    echo '<div class="error">';
    echo '<strong>❌ ERROR:</strong> Please configure your new credentials in this file (lines 14-17).';
    echo '</div>';
    echo '<div class="info">';
    echo '<h3>Instructions:</h3>';
    echo '<ol>';
    echo '<li>Edit this file: <code>change-admin-password.php</code></li>';
    echo '<li>Update lines 14-17 with your desired credentials</li>';
    echo '<li>Use a strong password (at least 12 characters, mix of letters, numbers, symbols)</li>';
    echo '<li>Save the file and refresh this page</li>';
    echo '</ol>';
    echo '</div>';
    exit;
}

// Validate password strength
if (strlen($new_password) < 8) {
    echo '<div class="error">❌ Password must be at least 8 characters long!</div>';
    exit;
}

// Connect to database
$conn = @mysqli_connect($host, $user, $pass, $dbname);

if (!$conn) {
    echo '<div class="error">❌ Database Connection Failed: ' . mysqli_connect_error() . '</div>';
    exit;
}

mysqli_set_charset($conn, 'utf8mb4');
echo '<div class="success">✅ Database connected successfully!</div>';

// Hash the new password
$hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

// Find admin user
$check_stmt = mysqli_prepare($conn, "SELECT id, username, email FROM admins WHERE username = 'admin' OR email = 'admin@auravibe.com' LIMIT 1");
mysqli_stmt_execute($check_stmt);
$result = mysqli_stmt_get_result($check_stmt);

if (mysqli_num_rows($result) > 0) {
    // Update existing admin
    $admin = mysqli_fetch_assoc($result);
    $admin_id = $admin['id'];

    $update_stmt = mysqli_prepare($conn, "UPDATE admins SET username = ?, email = ?, password = ?, full_name = ?, is_active = 1 WHERE id = ?");
    mysqli_stmt_bind_param($update_stmt, "ssssi", $new_username, $new_email, $hashed_password, $new_fullname, $admin_id);

    if (mysqli_stmt_execute($update_stmt)) {
        echo '<div class="success">';
        echo '<h2>✅ Admin Credentials Updated Successfully!</h2>';
        echo '<p>Your admin account has been updated.</p>';
        echo '</div>';

        echo '<h2>📋 New Login Credentials:</h2>';
        echo '<table>';
        echo '<tr><th>Field</th><th>Value</th></tr>';
        echo '<tr><td><strong>Login URL</strong></td><td><a href="admin/login.php" target="_blank">admin/login.php</a></td></tr>';
        echo '<tr><td><strong>Username</strong></td><td>' . htmlspecialchars($new_username) . '</td></tr>';
        echo '<tr><td><strong>Email</strong></td><td>' . htmlspecialchars($new_email) . '</td></tr>';
        echo '<tr><td><strong>Password</strong></td><td>' . htmlspecialchars($new_password) . '</td></tr>';
        echo '<tr><td><strong>Full Name</strong></td><td>' . htmlspecialchars($new_fullname) . '</td></tr>';
        echo '</table>';

        echo '<div class="warning">';
        echo '<h3>⚠️ IMPORTANT SECURITY STEPS:</h3>';
        echo '<ol>';
        echo '<li><strong>Write down your new credentials</strong> in a secure location</li>';
        echo '<li><strong>DELETE this file immediately:</strong> <code>change-admin-password.php</code></li>';
        echo '<li><strong>Test login</strong> with new credentials before closing this window</li>';
        echo '<li><strong>Never reuse</strong> this password on other sites</li>';
        echo '</ol>';
        echo '</div>';

        echo '<p style="text-align: center; margin-top: 30px;">';
        echo '<a href="admin/login.php" class="btn">Login Now →</a>';
        echo '</p>';

        // Verify password hash
        echo '<div class="info" style="margin-top: 30px;">';
        echo '<h3>🔍 Verification:</h3>';
        echo '<p>Password hash stored in database:</p>';
        echo '<code style="display: block; word-break: break-all; padding: 10px; background: #f4f4f4;">' . $hashed_password . '</code>';
        echo '<p style="margin-top: 10px;">✅ Password encryption: <strong>bcrypt</strong> (secure)</p>';
        echo '</div>';

    } else {
        echo '<div class="error">❌ Failed to update admin credentials: ' . mysqli_error($conn) . '</div>';
    }
} else {
    echo '<div class="error">❌ No admin user found. Please run the migration script first.</div>';
}

mysqli_close($conn);
?>

<div class="warning" style="margin-top: 30px; background: #ffebee; border-left-color: #c62828;">
    <h3 style="color: #c62828;">🚨 CRITICAL: Delete This File!</h3>
    <p style="font-size: 16px; font-weight: bold;">This file contains your password in plain text. You MUST delete it immediately after use!</p>
    <p>To delete via SSH/FTP: <code>rm change-admin-password.php</code></p>
</div>

</div>
</body>
</html>
