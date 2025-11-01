<?php
session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit();
}

require_once '../../includes/db.php';
require_once '../../includes/functions.php';

$page_title = 'Manage Shipping Costs';

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] === 'update' && isset($_POST['shipping_costs'])) {
        $updated = 0;
        foreach ($_POST['shipping_costs'] as $id => $cost) {
            $id = intval($id);
            $cost = floatval($cost);

            $stmt = mysqli_prepare($conn, "UPDATE shipping_costs SET cost = ? WHERE id = ?");
            mysqli_stmt_bind_param($stmt, "di", $cost, $id);
            if (mysqli_stmt_execute($stmt)) {
                $updated++;
            }
        }

        $success = "Successfully updated $updated shipping costs!";
    }
}

// Fetch all shipping costs grouped by region
$query = "SELECT * FROM shipping_costs ORDER BY display_order ASC";
$result = mysqli_query($conn, $query);
$shipping_costs = [];

if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $region = $row['region'] ?? 'Other';
        if (!isset($shipping_costs[$region])) {
            $shipping_costs[$region] = [];
        }
        $shipping_costs[$region][] = $row;
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
        <div class="content-header">
            <h1><i class="fas fa-shipping-fast"></i> Manage Shipping Costs</h1>
            <p>Set shipping costs for each governorate in Egypt</p>
        </div>

        <?php if (isset($success)): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i> <?php echo $success; ?>
            </div>
        <?php endif; ?>

        <?php if (isset($error)): ?>
            <div class="alert alert-error">
                <i class="fas fa-exclamation-circle"></i> <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="" class="shipping-form">
            <input type="hidden" name="action" value="update">

            <?php foreach ($shipping_costs as $region => $governorates): ?>
                <div class="region-section">
                    <h2><i class="fas fa-map-marked-alt"></i> <?php echo htmlspecialchars($region); ?></h2>

                    <div class="governorates-grid">
                        <?php foreach ($governorates as $gov): ?>
                            <div class="governorate-card">
                                <div class="gov-header">
                                    <span class="gov-name"><?php echo htmlspecialchars($gov['governorate']); ?></span>
                                    <span class="gov-status <?php echo $gov['is_active'] ? 'active' : 'inactive'; ?>">
                                        <?php echo $gov['is_active'] ? 'Active' : 'Inactive'; ?>
                                    </span>
                                </div>
                                <div class="gov-cost">
                                    <label>Shipping Cost (EGP)</label>
                                    <input
                                        type="number"
                                        name="shipping_costs[<?php echo $gov['id']; ?>]"
                                        value="<?php echo $gov['cost']; ?>"
                                        min="0"
                                        step="0.01"
                                        class="cost-input"
                                    >
                                    <?php if ($gov['cost'] == 0): ?>
                                        <span class="free-badge">FREE SHIPPING</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>

            <div class="form-actions sticky-actions">
                <button type="submit" class="btn btn-primary btn-large">
                    <i class="fas fa-save"></i> Save All Changes
                </button>
                <button type="reset" class="btn btn-outline">
                    <i class="fas fa-undo"></i> Reset
                </button>
            </div>
        </form>

        <!-- Quick Stats -->
        <div class="stats-section">
            <h3>Shipping Statistics</h3>
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon"><i class="fas fa-map-marker-alt"></i></div>
                    <div class="stat-details">
                        <div class="stat-value"><?php echo count($shipping_costs, COUNT_RECURSIVE) - count($shipping_costs); ?></div>
                        <div class="stat-label">Total Governorates</div>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon"><i class="fas fa-gift"></i></div>
                    <div class="stat-details">
                        <div class="stat-value">
                            <?php
                            $free_count = 0;
                            foreach ($shipping_costs as $region => $govs) {
                                foreach ($govs as $gov) {
                                    if ($gov['cost'] == 0) $free_count++;
                                }
                            }
                            echo $free_count;
                            ?>
                        </div>
                        <div class="stat-label">Free Shipping</div>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon"><i class="fas fa-money-bill-wave"></i></div>
                    <div class="stat-details">
                        <div class="stat-value">
                            <?php
                            $total_cost = 0;
                            $count = 0;
                            foreach ($shipping_costs as $region => $govs) {
                                foreach ($govs as $gov) {
                                    if ($gov['cost'] > 0) {
                                        $total_cost += $gov['cost'];
                                        $count++;
                                    }
                                }
                            }
                            echo $count > 0 ? number_format($total_cost / $count, 2) : '0';
                            ?> EGP
                        </div>
                        <div class="stat-label">Average Cost</div>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </div>
</div>

<style>
.shipping-form {
    background: white;
    border-radius: 8px;
    padding: 30px;
    margin-bottom: 30px;
}

.region-section {
    margin-bottom: 40px;
}

.region-section h2 {
    font-size: 20px;
    color: #1a1a1a;
    margin-bottom: 20px;
    padding-bottom: 10px;
    border-bottom: 2px solid #D4AF37;
}

.governorates-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
    gap: 15px;
}

.governorate-card {
    background: #f8f9fa;
    border: 1px solid #e9ecef;
    border-radius: 8px;
    padding: 15px;
    transition: all 0.3s;
}

.governorate-card:hover {
    border-color: #D4AF37;
    box-shadow: 0 2px 8px rgba(212, 175, 55, 0.1);
}

.gov-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 12px;
}

.gov-name {
    font-weight: 600;
    color: #1a1a1a;
}

.gov-status {
    font-size: 11px;
    padding: 3px 8px;
    border-radius: 12px;
    font-weight: 600;
}

.gov-status.active {
    background: #d4edda;
    color: #155724;
}

.gov-status.inactive {
    background: #f8d7da;
    color: #721c24;
}

.gov-cost label {
    display: block;
    font-size: 12px;
    color: #6c757d;
    margin-bottom: 5px;
}

.cost-input {
    width: 100%;
    padding: 8px 12px;
    border: 2px solid #dee2e6;
    border-radius: 4px;
    font-size: 16px;
    font-weight: 600;
    color: #1a1a1a;
    transition: border-color 0.3s;
}

.cost-input:focus {
    outline: none;
    border-color: #D4AF37;
}

.free-badge {
    display: inline-block;
    margin-top: 5px;
    font-size: 10px;
    color: #28a745;
    font-weight: 600;
}

.sticky-actions {
    position: sticky;
    bottom: 0;
    background: white;
    padding: 20px;
    border-top: 2px solid #e9ecef;
    margin: -30px;
    margin-top: 30px;
    display: flex;
    gap: 15px;
    box-shadow: 0 -2px 10px rgba(0,0,0,0.05);
}

.stats-section {
    background: white;
    border-radius: 8px;
    padding: 30px;
}

.stats-section h3 {
    font-size: 18px;
    margin-bottom: 20px;
    color: #1a1a1a;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
}

.stat-card {
    display: flex;
    align-items: center;
    gap: 15px;
    padding: 20px;
    background: #f8f9fa;
    border-radius: 8px;
}

.stat-icon {
    width: 50px;
    height: 50px;
    background: #D4AF37;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 24px;
}

.stat-value {
    font-size: 28px;
    font-weight: 700;
    color: #1a1a1a;
}

.stat-label {
    font-size: 12px;
    color: #6c757d;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.alert {
    padding: 15px 20px;
    border-radius: 8px;
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.alert-success {
    background: #d4edda;
    color: #155724;
    border-left: 4px solid #28a745;
}

.alert-error {
    background: #f8d7da;
    color: #721c24;
    border-left: 4px solid #dc3545;
}
</style>

</body>
</html>
