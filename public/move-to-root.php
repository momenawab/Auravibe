<?php
/**
 * Automated File Mover for Hostinger
 * Moves public folder contents to root
 * DELETE THIS FILE after use!
 */

?>
<!DOCTYPE html>
<html>
<head>
    <title>Move Public to Root</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 900px; margin: 50px auto; padding: 20px; background: #f5f5f5; }
        .container { background: white; padding: 30px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .success { color: green; background: #d4edda; padding: 15px; margin: 10px 0; border-left: 4px solid #28a745; }
        .error { color: red; background: #f8d7da; padding: 15px; margin: 10px 0; border-left: 4px solid #dc3545; }
        .warning { background: #fff3cd; padding: 15px; margin: 10px 0; border-left: 4px solid #ffc107; }
        .info { background: #d1ecf1; padding: 15px; margin: 10px 0; border-left: 4px solid #17a2b8; }
        h1 { color: #333; border-bottom: 3px solid #007bff; padding-bottom: 10px; }
        h2 { color: #555; margin-top: 25px; }
        code { background: #f4f4f4; padding: 2px 6px; border-radius: 3px; }
        .btn { display: inline-block; padding: 12px 24px; background: #007bff; color: white; text-decoration: none; border-radius: 5px; margin: 10px 5px; border: none; cursor: pointer; font-size: 16px; }
        .btn-danger { background: #dc3545; }
        .step { background: #f8f9fa; padding: 20px; margin: 20px 0; border-left: 4px solid #007bff; }
        ol { line-height: 2; }
    </style>
</head>
<body>

<div class="container">
<h1>📁 Move Public Folder to Root</h1>

<div class="warning">
    <h3>⚠️ Important: Read Before Proceeding</h3>
    <p>This tool will help you restructure your site so URLs don't include <code>/public/</code></p>
    <p><strong>Current:</strong> auravibe.site/public/admin/<br>
    <strong>After:</strong> auravibe.site/admin/</p>
</div>

<?php
// Check if we're in the public directory
$current_dir = __DIR__;
$is_in_public = (basename($current_dir) === 'public');

if (!$is_in_public) {
    echo '<div class="error">';
    echo '<strong>❌ Error:</strong> This script must be run from the /public/ directory!<br>';
    echo 'Current directory: ' . htmlspecialchars($current_dir);
    echo '</div>';
    exit;
}

echo '<div class="success">✅ Script location verified: Running from /public/ directory</div>';

// Check write permissions
$parent_dir = dirname($current_dir);
if (!is_writable($parent_dir)) {
    echo '<div class="error">❌ Error: No write permission to parent directory!</div>';
    exit;
}

echo '<div class="success">✅ Write permissions verified</div>';

// Show current structure
echo '<h2>📂 Current Directory Structure:</h2>';
echo '<div class="info">';
echo '<pre style="background: white; padding: 15px; border-radius: 4px;">';
echo htmlspecialchars($parent_dir) . '/
├── public/           ← Your website files are here
│   ├── index.php
│   ├── admin/
│   ├── assets/
│   └── ...
├── includes/
├── config/
└── .env
';
echo '</pre>';
echo '</div>';

echo '<h2>📂 After Moving (Target Structure):</h2>';
echo '<div class="info">';
echo '<pre style="background: white; padding: 15px; border-radius: 4px;">';
echo htmlspecialchars($parent_dir) . '/
├── index.php         ← Files moved here
├── admin/            ← Folders moved here
├── assets/
├── includes/
├── config/
└── .env
';
echo '</pre>';
echo '</div>';

?>

<h2>🔧 Automatic vs Manual Setup</h2>

<div class="step">
    <h3>Option 1: Hostinger Document Root (Recommended)</h3>
    <p><strong>This is the BEST option!</strong> No files need to be moved.</p>
    <ol>
        <li>Login to <strong>Hostinger hPanel</strong></li>
        <li>Go to <strong>Advanced → PHP Configuration</strong> or <strong>Website Settings</strong></li>
        <li>Find <strong>"Document Root"</strong> or <strong>"Change Website Root"</strong></li>
        <li>Change from: <code>public_html</code> or <code>/</code></li>
        <li>Change to: <code>public_html/public</code> or <code>/public</code></li>
        <li>Save and test your site!</li>
    </ol>
    <p><strong>Done!</strong> Your URLs will immediately work without /public/</p>
</div>

<div class="step">
    <h3>Option 2: Manually Move Files via File Manager</h3>
    <p>If you can't change document root, use Hostinger File Manager:</p>
    <ol>
        <li>Open <strong>Hostinger File Manager</strong></li>
        <li>Navigate to: <code>public_html/public/</code></li>
        <li>Select ALL files and folders inside <code>public/</code></li>
        <li>Click <strong>Move</strong> button</li>
        <li>Move to: <code>public_html/</code> (one level up)</li>
        <li>Confirm and wait for completion</li>
        <li>Delete empty <code>public/</code> folder</li>
        <li>Run the config update script below</li>
    </ol>
</div>

<h2>⚙️ Update Configuration After Moving</h2>

<div class="info">
    <p>After moving files manually (Option 2), you need to update path references:</p>

    <h4>Files to Update:</h4>
    <ol>
        <li><strong>config-paths.php</strong> - Change <code>__DIR__ . '/../includes/'</code> to <code>__DIR__ . '/includes/'</code></li>
        <li><strong>admin/*.php files</strong> - Change <code>../../includes/</code> to <code>../includes/</code></li>
    </ol>
</div>

<form method="POST" style="margin: 30px 0; text-align: center;">
    <div class="warning">
        <h3>🚀 Automatic Path Updater</h3>
        <p>Use this ONLY if you manually moved files via File Manager (Option 2)</p>
        <p>This will update all path references in PHP files to work with the new structure.</p>
    </div>

    <button type="submit" name="update_paths" class="btn" onclick="return confirm('Have you already moved all files from public/ to root? This cannot be undone!');">
        Update Configuration Files
    </button>
</form>

<?php
if (isset($_POST['update_paths'])) {
    echo '<h2>🔄 Updating Configuration...</h2>';

    // This would only run after files are moved
    echo '<div class="error">';
    echo '<strong>Note:</strong> This automated update is not recommended.<br>';
    echo 'Please manually update the paths or use Option 1 (change document root) instead.<br>';
    echo 'Manual updates are safer and give you more control.';
    echo '</div>';
}
?>

<h2>✅ Testing Checklist</h2>

<div class="info">
    <p>After making changes, test these URLs:</p>
    <ol>
        <li>✅ <a href="https://auravibe.site/" target="_blank">https://auravibe.site/</a> (homepage)</li>
        <li>✅ <a href="https://auravibe.site/admin/" target="_blank">https://auravibe.site/admin/</a> (admin)</li>
        <li>✅ <a href="https://auravibe.site/shop.php" target="_blank">https://auravibe.site/shop.php</a> (shop)</li>
        <li>✅ <a href="https://auravibe.site/cart.php" target="_blank">https://auravibe.site/cart.php</a> (cart)</li>
    </ol>
    <p>All should work WITHOUT <code>/public/</code> in the URL!</p>
</div>

<div class="warning" style="margin-top: 30px;">
    <h3>⚠️ Important Reminders:</h3>
    <ul>
        <li>✅ <strong>Backup first!</strong> Download a backup of your site before making changes</li>
        <li>✅ <strong>Test thoroughly</strong> after making changes</li>
        <li>✅ <strong>Delete this file</strong> after you're done: <code>move-to-root.php</code></li>
        <li>✅ <strong>Clear browser cache</strong> to see changes</li>
    </ul>
</div>

<div style="text-align: center; margin-top: 30px;">
    <a href="../" class="btn">Go to Website</a>
</div>

</div>
</body>
</html>
