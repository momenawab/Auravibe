<?php
/**
 * Admin User Setup Script
 * Creates or resets admin user credentials
 * DELETE THIS FILE after use!
 */

// Database credentials (same as test-db.php that worked)
$host = 'localhost';
$user = 'u446437128_auravibe';
$pass = 'YOUR_DATABASE_PASSWORD_HERE';  // ← Use same password from test-db.php
$dbname = 'u446437128_auravibe';

echo "<h2>Admin User Setup</h2>";

// Connect to database
$conn = @mysqli_connect($host, $user, $pass, $dbname);

if (!$conn) {
    die("<p style='color: red;'>✗ Database connection failed: " . mysqli_connect_error() . "</p>");
}

echo "<p style='color: green;'>✓ Database connected successfully</p>";

// Check if admins table exists
$result = mysqli_query($conn, "SHOW TABLES LIKE 'admins'");
if (mysqli_num_rows($result) == 0) {
    die("<p style='color: red;'>✗ 'admins' table does not exist. Please import database.sql first.</p>");
}

echo "<p style='color: green;'>✓ 'admins' table exists</p>";

// Admin credentials
$username = 'admin';
$email = 'admin@auravibe.com';
$password = 'admin123';
$full_name = 'Administrator';
$role = 'admin';

// Hash password
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Check if admin exists
$check_query = "SELECT id FROM admins WHERE username = ? OR email = ?";
$stmt = mysqli_prepare($conn, $check_query);
mysqli_stmt_bind_param($stmt, "ss", $username, $email);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) > 0) {
    // Update existing admin
    $admin = mysqli_fetch_assoc($result);
    $update_query = "UPDATE admins SET password = ?, full_name = ?, role = ?, is_active = 1 WHERE id = ?";
    $stmt = mysqli_prepare($conn, $update_query);
    mysqli_stmt_bind_param($stmt, "sssi", $hashed_password, $full_name, $role, $admin['id']);

    if (mysqli_stmt_execute($stmt)) {
        echo "<p style='color: green;'>✓ Admin user updated successfully!</p>";
    } else {
        echo "<p style='color: red;'>✗ Failed to update admin user: " . mysqli_error($conn) . "</p>";
    }
} else {
    // Insert new admin
    $insert_query = "INSERT INTO admins (username, email, password, full_name, role, is_active) VALUES (?, ?, ?, ?, ?, 1)";
    $stmt = mysqli_prepare($conn, $insert_query);
    mysqli_stmt_bind_param($stmt, "sssss", $username, $email, $hashed_password, $full_name, $role);

    if (mysqli_stmt_execute($stmt)) {
        echo "<p style='color: green;'>✓ Admin user created successfully!</p>";
    } else {
        echo "<p style='color: red;'>✗ Failed to create admin user: " . mysqli_error($conn) . "</p>";
    }
}

echo "<hr>";
echo "<h3>Admin Login Credentials:</h3>";
echo "<table border='1' cellpadding='10' style='border-collapse: collapse;'>";
echo "<tr><td><strong>Admin URL:</strong></td><td><a href='admin/login.php'>admin/login.php</a></td></tr>";
echo "<tr><td><strong>Username:</strong></td><td>admin</td></tr>";
echo "<tr><td><strong>Email:</strong></td><td>admin@auravibe.com</td></tr>";
echo "<tr><td><strong>Password:</strong></td><td>admin123</td></tr>";
echo "</table>";

echo "<p style='background: #ffe; padding: 10px; border: 1px solid #cc0; margin-top: 20px;'>";
echo "<strong>⚠️ IMPORTANT SECURITY STEPS:</strong><br>";
echo "1. Delete this file immediately after use<br>";
echo "2. Change the admin password after first login<br>";
echo "3. Delete test-db.php if still present";
echo "</p>";

mysqli_close($conn);
?>
