<?php
/**
 * Database Connection Test
 * Upload this to your server and visit it in browser
 * DELETE THIS FILE after testing!
 */

// Test credentials
$host = 'localhost';
$user = 'u446437128_auravibe';
$pass = 'YOUR_DATABASE_PASSWORD_HERE';  // ← Update this!
$dbname = 'u446437128_auravibe';

echo "<h2>Database Connection Test</h2>";

// Try connection
$conn = @mysqli_connect($host, $user, $pass, $dbname);

if ($conn) {
    echo "<p style='color: green;'>✓ SUCCESS! Database connection established.</p>";

    // Test queries
    $result = mysqli_query($conn, "SHOW TABLES");
    if ($result) {
        $tables = [];
        while ($row = mysqli_fetch_array($result)) {
            $tables[] = $row[0];
        }
        echo "<p>Found " . count($tables) . " tables:</p>";
        echo "<ul>";
        foreach ($tables as $table) {
            echo "<li>$table</li>";
        }
        echo "</ul>";
    }

    mysqli_close($conn);
} else {
    echo "<p style='color: red;'>✗ FAILED! Could not connect to database.</p>";
    echo "<p>Error: " . mysqli_connect_error() . "</p>";
    echo "<p>Please check your credentials in Hostinger control panel.</p>";
}

echo "<hr>";
echo "<p><strong>Credentials being tested:</strong></p>";
echo "<ul>";
echo "<li>Host: $host</li>";
echo "<li>Username: $user</li>";
echo "<li>Password: " . (strlen($pass) > 0 ? str_repeat('*', strlen($pass)) : '<span style="color:red;">NOT SET</span>') . "</li>";
echo "<li>Database: $dbname</li>";
echo "</ul>";

echo "<p style='background: #ffe; padding: 10px; border: 1px solid #cc0;'><strong>⚠️ SECURITY WARNING:</strong> Delete this file after testing!</p>";
?>
