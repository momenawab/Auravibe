<?php
/**
 * Simple Admin Setup - Uses same DB connection as test-db.php
 * UPDATE THE PASSWORD BELOW, then visit this page
 * DELETE THIS FILE after use!
 */

// ============================================
// STEP 1: UPDATE PASSWORD HERE (same as test-db.php)
// ============================================
$DB_PASSWORD = 'rI$n5W9:!4';  // ← CHANGE THIS!

// Database credentials
$host = 'localhost';
$user = 'u446437128_auravibe';
$pass = $DB_PASSWORD;
$dbname = 'u446437128_auravibe';

// Admin credentials to create
$admin_username = 'admin';
$admin_email = 'admin@auravibe.com';
$admin_password = 'admin123';  // You can change this later
$admin_fullname = 'Administrator';
$admin_role = 'admin';

?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Setup</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 800px; margin: 50px auto; padding: 20px; }
        .success { color: green; background: #d4edda; padding: 10px; border: 1px solid #c3e6cb; border-radius: 5px; }
        .error { color: red; background: #f8d7da; padding: 10px; border: 1px solid #f5c6cb; border-radius: 5px; }
        .warning { background: #fff3cd; padding: 10px; border: 1px solid #ffeaa7; border-radius: 5px; }
        table { border-collapse: collapse; width: 100%; margin: 20px 0; }
        td { padding: 10px; border: 1px solid #ddd; }
        .btn { display: inline-block; padding: 10px 20px; background: #007bff; color: white; text-decoration: none; border-radius: 5px; }
    </style>
</head>
<body>

<h1>🔧 Admin Setup Tool</h1>

<?php
// Check if password is updated
if ($DB_PASSWORD === 'YOUR_DATABASE_PASSWORD_HERE') {
    echo '<div class="error">';
    echo '<strong>❌ ERROR:</strong> Please update the database password in this file (line 11).<br>';
    echo 'Use the same password you used in test-db.php that showed SUCCESS.';
    echo '</div>';
    exit;
}

// Connect to database
$conn = @mysqli_connect($host, $user, $pass, $dbname);

if (!$conn) {
    echo '<div class="error">';
    echo '<strong>❌ Database Connection Failed</strong><br>';
    echo 'Error: ' . mysqli_connect_error() . '<br>';
    echo 'Please check your database password.';
    echo '</div>';
    exit;
}

mysqli_set_charset($conn, 'utf8mb4');

echo '<div class="success">✅ Database connected successfully!</div>';

// Check if admins table exists
$result = mysqli_query($conn, "SHOW TABLES LIKE 'admins'");
if (mysqli_num_rows($result) == 0) {
    echo '<div class="error">❌ Table "admins" does not exist. Please import database.sql first.</div>';
    mysqli_close($conn);
    exit;
}

echo '<div class="success">✅ Table "admins" exists</div>';

// Hash the password
$hashed_password = password_hash($admin_password, PASSWORD_DEFAULT);

// Check if admin already exists
$check_stmt = mysqli_prepare($conn, "SELECT id, username FROM admins WHERE username = ? OR email = ?");
mysqli_stmt_bind_param($check_stmt, "ss", $admin_username, $admin_email);
mysqli_stmt_execute($check_stmt);
$result = mysqli_stmt_get_result($check_stmt);

if (mysqli_num_rows($result) > 0) {
    // Update existing admin
    $admin = mysqli_fetch_assoc($result);

    $update_stmt = mysqli_prepare($conn, "UPDATE admins SET password = ?, full_name = ?, role = ?, is_active = 1, email = ? WHERE id = ?");
    mysqli_stmt_bind_param($update_stmt, "ssssi", $hashed_password, $admin_fullname, $admin_role, $admin_email, $admin['id']);

    if (mysqli_stmt_execute($update_stmt)) {
        echo '<div class="success">✅ Admin user updated successfully!</div>';
        $action = 'updated';
    } else {
        echo '<div class="error">❌ Failed to update admin: ' . mysqli_error($conn) . '</div>';
        mysqli_close($conn);
        exit;
    }
} else {
    // Create new admin
    $insert_stmt = mysqli_prepare($conn, "INSERT INTO admins (username, email, password, full_name, role, is_active) VALUES (?, ?, ?, ?, ?, 1)");
    mysqli_stmt_bind_param($insert_stmt, "sssss", $admin_username, $admin_email, $hashed_password, $admin_fullname, $admin_role);

    if (mysqli_stmt_execute($insert_stmt)) {
        echo '<div class="success">✅ Admin user created successfully!</div>';
        $action = 'created';
    } else {
        echo '<div class="error">❌ Failed to create admin: ' . mysqli_error($conn) . '</div>';
        mysqli_close($conn);
        exit;
    }
}

mysqli_close($conn);

// Show credentials
echo '<h2>🎉 Setup Complete!</h2>';

echo '<table>';
echo '<tr><td><strong>Admin Panel URL:</strong></td><td><a href="admin/" target="_blank">admin/</a></td></tr>';
echo '<tr><td><strong>Username:</strong></td><td>' . htmlspecialchars($admin_username) . '</td></tr>';
echo '<tr><td><strong>Email:</strong></td><td>' . htmlspecialchars($admin_email) . '</td></tr>';
echo '<tr><td><strong>Password:</strong></td><td>' . htmlspecialchars($admin_password) . '</td></tr>';
echo '</table>';

echo '<p><a href="admin/" class="btn">Go to Admin Panel →</a></p>';

echo '<div class="warning">';
echo '<h3>⚠️ IMPORTANT - Security Steps:</h3>';
echo '<ol>';
echo '<li><strong>DELETE these files immediately:</strong>';
echo '<ul>';
echo '<li>setup-admin.php (this file)</li>';
echo '<li>create-admin.php</li>';
echo '<li>test-db.php</li>';
echo '</ul>';
echo '</li>';
echo '<li>Login to admin panel and <strong>change your password</strong></li>';
echo '<li>Never use "admin123" in production!</li>';
echo '</ol>';
echo '</div>';
?>

</body>
</html>
