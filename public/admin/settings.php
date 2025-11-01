<?php
session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit();
}

require_once '../../includes/db.php';
require_once '../../includes/functions.php';

$page_title = "Site Settings";

// Handle form submission
$success_message = '';
$error_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_settings'])) {
    if (can_query()) {
        try {
            // Settings to save
            $settings = [
                'site_name' => trim($_POST['site_name'] ?? ''),
                'site_email' => trim($_POST['site_email'] ?? ''),
                'site_phone' => trim($_POST['site_phone'] ?? ''),
                'tax_rate' => floatval($_POST['tax_rate'] ?? 0),
                'shipping_cost' => floatval($_POST['shipping_cost'] ?? 0),
                'facebook_url' => trim($_POST['facebook_url'] ?? ''),
                'instagram_url' => trim($_POST['instagram_url'] ?? ''),
                'twitter_url' => trim($_POST['twitter_url'] ?? ''),
                'linkedin_url' => trim($_POST['linkedin_url'] ?? '')
            ];

            // Save each setting using prepared statements
            foreach ($settings as $key => $value) {
                $stmt = $conn->prepare("INSERT INTO settings (setting_key, setting_value) VALUES (?, ?) ON DUPLICATE KEY UPDATE setting_value = ?");
                $stmt->bind_param("sss", $key, $value, $value);
                $stmt->execute();
                $stmt->close();
            }

            $success_message = 'Settings saved successfully!';
        } catch (Exception $e) {
            $error_message = "Error saving settings: " . $e->getMessage();
        }
    } else {
        $error_message = "Database not available.";
    }
}

// Load current settings from database
$current_settings = [
    'site_name' => 'Aura Vibe',
    'site_email' => 'contact@auravibe.com',
    'site_phone' => '+1 (555) 123-4567',
    'tax_rate' => '10.00',
    'shipping_cost' => '15.00',
    'facebook_url' => 'https://facebook.com/auravibe',
    'instagram_url' => 'https://instagram.com/auravibe',
    'twitter_url' => 'https://twitter.com/auravibe',
    'linkedin_url' => 'https://linkedin.com/company/auravibe'
];

// Load settings from database if available
if (can_query()) {
    try {
        $result = $conn->query("SELECT setting_key, setting_value FROM settings");
        while ($row = $result->fetch_assoc()) {
            $current_settings[$row['setting_key']] = $row['setting_value'];
        }
    } catch (Exception $e) {
        // Use default settings if error occurs
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?> - Aura Vibe Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body class="admin-body">
<div class="admin-layout">
    <?php include 'includes/sidebar.php'; ?>

    <div class="admin-main">
        <header class="admin-header">
            <h1><?php echo $page_title; ?></h1>
            <div class="admin-user">
                <span><?php echo htmlspecialchars($_SESSION['admin_username']); ?></span>
                <i class="fas fa-user-circle"></i>
            </div>
        </header>

        <div class="admin-content">
            <div style="margin-bottom: 20px;">
                <p style="color: #ccc; font-size: 14px;">Manage your site configuration and settings</p>
            </div>

        <?php if (!empty($success_message)): ?>
            <div class="alert alert-success">
                <?php echo htmlspecialchars($success_message); ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($error_message)): ?>
            <div class="alert alert-danger">
                <?php echo htmlspecialchars($error_message); ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="">
            <!-- General Settings -->
            <div class="settings-card">
                <h3>General Settings</h3>

                <div class="form-group">
                    <label for="site_name" class="form-label">Site Name</label>
                    <input type="text" class="form-control" id="site_name" name="site_name"
                           value="<?php echo htmlspecialchars($current_settings['site_name']); ?>" required>
                </div>

                <div class="form-group">
                    <label for="site_email" class="form-label">Contact Email</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                        <input type="email" class="form-control" id="site_email" name="site_email"
                               value="<?php echo htmlspecialchars($current_settings['site_email']); ?>" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="site_phone" class="form-label">Contact Phone</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-phone"></i></span>
                        <input type="text" class="form-control" id="site_phone" name="site_phone"
                               value="<?php echo htmlspecialchars($current_settings['site_phone']); ?>" required>
                    </div>
                </div>
            </div>

            <!-- E-commerce Settings -->
            <div class="settings-card">
                <h3>E-commerce Settings</h3>

                <div class="form-group">
                    <label for="tax_rate" class="form-label">Tax Rate (%)</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-percent"></i></span>
                        <input type="number" class="form-control" id="tax_rate" name="tax_rate"
                               value="<?php echo htmlspecialchars($current_settings['tax_rate']); ?>"
                               step="0.01" min="0" max="100" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="shipping_cost" class="form-label">Shipping Cost ($)</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-dollar-sign"></i></span>
                        <input type="number" class="form-control" id="shipping_cost" name="shipping_cost"
                               value="<?php echo htmlspecialchars($current_settings['shipping_cost']); ?>"
                               step="0.01" min="0" required>
                    </div>
                </div>
            </div>

            <!-- Social Media Settings -->
            <div class="settings-card">
                <h3>Social Media Links</h3>

                <div class="form-group">
                    <label for="facebook_url" class="form-label">Facebook URL</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fab fa-facebook"></i></span>
                        <input type="url" class="form-control" id="facebook_url" name="facebook_url"
                               value="<?php echo htmlspecialchars($current_settings['facebook_url']); ?>"
                               placeholder="https://facebook.com/yourpage">
                    </div>
                </div>

                <div class="form-group">
                    <label for="instagram_url" class="form-label">Instagram URL</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fab fa-instagram"></i></span>
                        <input type="url" class="form-control" id="instagram_url" name="instagram_url"
                               value="<?php echo htmlspecialchars($current_settings['instagram_url']); ?>"
                               placeholder="https://instagram.com/yourprofile">
                    </div>
                </div>

                <div class="form-group">
                    <label for="twitter_url" class="form-label">Twitter URL</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fab fa-twitter"></i></span>
                        <input type="url" class="form-control" id="twitter_url" name="twitter_url"
                               value="<?php echo htmlspecialchars($current_settings['twitter_url']); ?>"
                               placeholder="https://twitter.com/yourhandle">
                    </div>
                </div>

                <div class="form-group">
                    <label for="linkedin_url" class="form-label">LinkedIn URL</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fab fa-linkedin"></i></span>
                        <input type="url" class="form-control" id="linkedin_url" name="linkedin_url"
                               value="<?php echo htmlspecialchars($current_settings['linkedin_url']); ?>"
                               placeholder="https://linkedin.com/company/yourcompany">
                    </div>
                </div>
            </div>

            <div class="text-center">
                <button type="submit" name="save_settings" class="btn-save">
                    Save Settings
                </button>
            </div>
        </form>
        </div>
    </div>
</div>
</body>
</html>
