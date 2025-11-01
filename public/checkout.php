<?php require_once __DIR__ . '/config-paths.php';
// Session already started in bootstrap.php
$page_title = __('checkout');
include_view('header.php');
include_view('navbar.php');

// Calculate totals (same as cart.php)
$demo_cart = [
    ['id' => 1, 'name' => 'Royal Gold Chronograph', 'brand' => 'PRESTIGE', 'price' => 2499.00, 'quantity' => 1],
    ['id' => 3, 'name' => 'Platinum Diver', 'brand' => 'OCEANIC', 'price' => 3799.00, 'quantity' => 1],
];

$cart_items = isset($_SESSION['cart']) && count($_SESSION['cart']) > 0 ? $_SESSION['cart'] : $demo_cart;
$subtotal = 0;
foreach ($cart_items as $item) {
    $subtotal += $item['price'] * $item['quantity'];
}

// Shipping will be calculated based on governorate selection
$shipping = 0;
$tax = $subtotal * 0.14; // 14% VAT in Egypt
$total = $subtotal + $shipping + $tax;

// Fetch shipping costs from database
$shipping_costs_from_db = [];
if (can_query() && $conn !== null) {
    $query = "SELECT governorate, cost FROM shipping_costs WHERE is_active = 1 ORDER BY display_order ASC";
    $result = mysqli_query($conn, $query);
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $shipping_costs_from_db[$row['governorate']] = (float)$row['cost'];
        }
    }
}
?>

<!-- Checkout Section -->
<section class="checkout-section">
    <div class="container">
        <h1><?php echo __('checkout'); ?></h1>

        <!-- Progress Steps -->
        <div class="checkout-steps">
            <div class="step active">
                <div class="step-number">1</div>
                <div class="step-label"><?php echo __('information'); ?></div>
            </div>
            <div class="step">
                <div class="step-number">2</div>
                <div class="step-label"><?php echo __('shipping'); ?></div>
            </div>
            <div class="step">
                <div class="step-number">3</div>
                <div class="step-label"><?php echo __('payment'); ?></div>
            </div>
        </div>

        <div class="checkout-layout">
            <!-- Checkout Form -->
            <div class="checkout-form">
                <form id="checkoutForm" method="POST" action="process-order.php">
                    <!-- Contact Information -->
                    <div class="form-section">
                        <h2><?php echo __('contact_info'); ?></h2>
                        <div class="form-row">
                            <div class="form-group">
                                <label><?php echo __('email'); ?> *</label>
                                <input type="email" name="email" required placeholder="<?php echo __('your_email'); ?>">
                            </div>
                            <div class="form-group">
                                <label><?php echo __('phone'); ?> *</label>
                                <input type="tel" name="phone" required placeholder="+20 1234567890">
                            </div>
                        </div>
                    </div>

                    <!-- Shipping Address -->
                    <div class="form-section">
                        <h2><?php echo __('shipping_address'); ?></h2>
                        <div class="form-row">
                            <div class="form-group">
                                <label><?php echo __('first_name'); ?> *</label>
                                <input type="text" name="first_name" required>
                            </div>
                            <div class="form-group">
                                <label><?php echo __('last_name'); ?> *</label>
                                <input type="text" name="last_name" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label><?php echo __('address'); ?> *</label>
                            <input type="text" name="address" required placeholder="<?php echo __('street_address'); ?>">
                        </div>

                        <div class="form-group">
                            <label><?php echo __('apartment_suite'); ?></label>
                            <input type="text" name="address2" placeholder="<?php echo __('apartment_suite'); ?>">
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label><?php echo __('governorate'); ?> *</label>
                                <select name="governorate" id="governorate" required onchange="updateShippingCost()">
                                    <option value=""><?php echo __('select_governorate'); ?></option>
                                    <?php
                                    // Use database shipping costs if available, otherwise fallback to config
                                    if (!empty($shipping_costs_from_db)) {
                                        $current_region = '';
                                        $query = "SELECT governorate, cost, region FROM shipping_costs WHERE is_active = 1 ORDER BY display_order ASC";
                                        $result = mysqli_query($conn, $query);
                                        if ($result) {
                                            while ($row = mysqli_fetch_assoc($result)) {
                                                $region = $row['region'] ?? 'Other';
                                                if ($region !== $current_region) {
                                                    if ($current_region !== '') echo '</optgroup>';
                                                    echo '<optgroup label="' . htmlspecialchars($region) . '">';
                                                    $current_region = $region;
                                                }
                                                $cost_label = $row['cost'] == 0 ? __('free_shipping_caps') : $row['cost'] . ' ' . __('egp_caps');
                                                echo '<option value="' . htmlspecialchars($row['governorate']) . '">' . htmlspecialchars($row['governorate']) . ' (' . $cost_label . ')</option>';
                                            }
                                            if ($current_region !== '') echo '</optgroup>';
                                        }
                                    } else {
                                        // Fallback to config file
                                        $governorates = getGovernorateOptions();
                                        foreach ($governorates as $region => $govs) {
                                            echo '<optgroup label="' . htmlspecialchars($region) . '">';
                                            foreach ($govs as $value => $label) {
                                                echo '<option value="' . htmlspecialchars($value) . '">' . htmlspecialchars($label) . '</option>';
                                            }
                                            echo '</optgroup>';
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label><?php echo __('city_district'); ?> *</label>
                                <input type="text" name="city" required placeholder="<?php echo is_rtl() ? 'مثال: الدقي، مصر الجديدة' : 'e.g., Dokki, Masr El Gedida'; ?>">
                            </div>
                        </div>

                        <div class="form-group">
                            <label><?php echo __('postal_zip_optional'); ?></label>
                            <input type="text" name="zip" placeholder="11511">
                        </div>
                    </div>

                    <!-- Shipping Cost Display -->
                    <div class="form-section">
                        <h2><?php echo __('shipping_information'); ?></h2>
                        <div class="shipping-info-box" style="background: #f8f9fa; padding: 20px; border-radius: 8px; margin-bottom: 20px;">
                            <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 10px;">
                                <i class="fas fa-shipping-fast" style="color: #D4AF37; font-size: 24px;"></i>
                                <div>
                                    <div style="font-weight: 600; font-size: 16px;"><?php echo __('delivery_time_3_5_days'); ?></div>
                                    <div style="color: #666; font-size: 14px; margin-top: 4px;"><?php echo __('fast_secure_delivery'); ?></div>
                                </div>
                            </div>
                            <div id="shipping-cost-display" style="margin-top: 15px; padding: 15px; background: white; border-radius: 6px; border-<?php echo is_rtl() ? 'right' : 'left'; ?>: 4px solid #D4AF37;">
                                <div style="display: flex; justify-content: space-between; align-items: center;">
                                    <span style="font-weight: 500;"><?php echo __('shipping_cost'); ?>:</span>
                                    <span id="shipping-price" style="font-size: 18px; font-weight: 600; color: #D4AF37;"><?php echo __('select_governorate_to_view'); ?></span>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="shipping_cost" id="shipping_cost" value="0">
                    </div>

                    <!-- Payment Information -->
                    <div class="form-section">
                        <h2><?php echo __('payment_method'); ?></h2>

                        <div class="payment-methods">
                            <!-- Online Card Payment -->
                            <label class="payment-option">
                                <input type="radio" name="payment_method" value="online_card" checked>
                                <div class="option-content">
                                    <div class="payment-icon">
                                        <i class="fas fa-credit-card"></i>
                                    </div>
                                    <div class="payment-details">
                                        <div class="option-name"><?php echo __('online_card'); ?></div>
                                        <div class="option-description"><?php echo __('pay_securely_with_card'); ?></div>
                                    </div>
                                </div>
                                <div class="payment-badges">
                                    <i class="fab fa-cc-visa"></i>
                                    <i class="fab fa-cc-mastercard"></i>
                                </div>
                            </label>

                            <!-- Mobile Wallet -->
                            <label class="payment-option">
                                <input type="radio" name="payment_method" value="mobile_wallet">
                                <div class="option-content">
                                    <div class="payment-icon">
                                        <i class="fas fa-mobile-alt"></i>
                                    </div>
                                    <div class="payment-details">
                                        <div class="option-name"><?php echo __('mobile_wallet'); ?></div>
                                        <div class="option-description"><?php echo __('pay_with_mobile_wallet'); ?></div>
                                    </div>
                                </div>
                            </label>

                            <!-- Cash on Delivery -->
                            <label class="payment-option">
                                <input type="radio" name="payment_method" value="cash_on_delivery">
                                <div class="option-content">
                                    <div class="payment-icon">
                                        <i class="fas fa-money-bill-wave"></i>
                                    </div>
                                    <div class="payment-details">
                                        <div class="option-name"><?php echo __('cash_on_delivery'); ?></div>
                                        <div class="option-description"><?php echo __('pay_on_delivery'); ?></div>
                                    </div>
                                </div>
                            </label>
                        </div>

                        <div class="payment-note">
                            <i class="fas fa-shield-alt"></i>
                            <span><?php echo __('secure_encrypted_transactions'); ?></span>
                        </div>
                    </div>

                    <!-- Order Notes -->
                    <div class="form-section">
                        <h2><?php echo __('order_notes_optional'); ?></h2>
                        <div class="form-group">
                            <label><?php echo __('additional_information'); ?></label>
                            <textarea name="order_notes" rows="4" placeholder="<?php echo __('special_delivery_instructions'); ?>"></textarea>
                        </div>
                    </div>

                    <div class="form-actions">
                        <a href="cart.php" class="btn btn-outline">
                            <i class="fas fa-arrow-<?php echo is_rtl() ? 'right' : 'left'; ?>"></i> <?php echo __('return_to_cart'); ?>
                        </a>
                        <button type="submit" class="btn btn-primary btn-large">
                            <?php echo __('complete_order'); ?> <i class="fas fa-lock"></i>
                        </button>
                    </div>
                </form>
            </div>

            <!-- Order Summary -->
            <div class="checkout-summary">
                <h2><?php echo __('order_summary'); ?></h2>

                <div class="summary-items">
                    <?php foreach ($cart_items as $item): ?>
                        <div class="summary-item">
                            <div class="item-image">
                                <img src="https://via.placeholder.com/80x80/1a1a1a/D4AF37?text=<?php echo urlencode(substr($item['name'], 0, 1)); ?>"
                                     alt="<?php echo htmlspecialchars($item['name']); ?>">
                                <span class="item-quantity"><?php echo $item['quantity']; ?></span>
                            </div>
                            <div class="item-info">
                                <div class="item-name"><?php echo htmlspecialchars($item['name']); ?></div>
                                <div class="item-brand"><?php echo htmlspecialchars($item['brand'] ?? ''); ?></div>
                            </div>
                            <div class="item-price number">
                                <?php echo number_format($item['price'] * $item['quantity'], 2); ?> <?php echo __('egp_caps'); ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="summary-divider"></div>

                <div class="summary-row">
                    <span><?php echo __('subtotal'); ?>:</span>
                    <span class="number"><?php echo number_format($subtotal, 2); ?> <?php echo __('egp_caps'); ?></span>
                </div>

                <div class="summary-row">
                    <span><?php echo __('shipping'); ?>:</span>
                    <span id="summary-shipping-cost"><span style="color: #999; font-style: italic;"><?php echo __('select_governorate'); ?></span></span>
                </div>

                <div class="summary-row">
                    <span><?php echo __('tax'); ?> (14% <?php echo __('vat'); ?>):</span>
                    <span class="number"><?php echo number_format($tax, 2); ?> <?php echo __('egp_caps'); ?></span>
                </div>

                <div class="summary-divider"></div>

                <div class="summary-row summary-total">
                    <span><?php echo __('total'); ?>:</span>
                    <span id="summary-total-cost" class="number"><?php echo number_format($total, 2); ?> <?php echo __('egp_caps'); ?></span>
                </div>

                <!-- Security Badges -->
                <div class="security-badges">
                    <div class="badge">
                        <i class="fas fa-shield-alt"></i> <?php echo __('secure_payment'); ?>
                    </div>
                    <div class="badge">
                        <i class="fas fa-truck"></i> <?php echo __('safe_delivery'); ?>
                    </div>
                    <div class="badge">
                        <i class="fas fa-money-bill-wave"></i> <?php echo __('cash_on_delivery'); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
// Shipping prices by governorate (from database)
const shippingPrices = <?php echo json_encode(!empty($shipping_costs_from_db) ? $shipping_costs_from_db : SHIPPING_PRICES); ?>;

// Update shipping cost when governorate changes
function updateShippingCost() {
    const governorateSelect = document.getElementById('governorate');
    const selectedGovernorate = governorateSelect.value;
    const shippingPriceDisplay = document.getElementById('shipping-price');
    const shippingCostInput = document.getElementById('shipping_cost');

    if (selectedGovernorate && shippingPrices[selectedGovernorate] !== undefined) {
        const shippingCost = shippingPrices[selectedGovernorate];

        if (shippingCost === 0) {
            shippingPriceDisplay.innerHTML = '<span style="color: #28a745;"><?php echo __('free_shipping_caps'); ?></span>';
        } else {
            shippingPriceDisplay.textContent = shippingCost + ' <?php echo __('egp_caps'); ?>';
        }

        // Update hidden input
        shippingCostInput.value = shippingCost;

        // Update summary
        updateOrderSummary();
    } else {
        shippingPriceDisplay.textContent = '<?php echo __('select_governorate_to_view'); ?>';
        shippingCostInput.value = 0;
    }
}

// Update order summary totals
function updateOrderSummary() {
    const subtotal = <?php echo $subtotal; ?>;
    const tax = <?php echo $tax; ?>;
    const shippingCost = parseFloat(document.getElementById('shipping_cost').value) || 0;
    const total = subtotal + tax + shippingCost;

    // Update shipping display in summary
    const shippingDisplay = document.getElementById('summary-shipping-cost');
    if (shippingDisplay) {
        if (shippingCost === 0) {
            shippingDisplay.innerHTML = '<span style="color: #28a745; font-weight: 600;"><?php echo __('free'); ?></span>';
        } else {
            shippingDisplay.textContent = shippingCost + ' <?php echo __('egp_caps'); ?>';
        }
    }

    // Update total
    const totalDisplay = document.getElementById('summary-total-cost');
    if (totalDisplay) {
        totalDisplay.textContent = total.toFixed(2) + ' <?php echo __('egp_caps'); ?>';
    }
}
</script>

<?php include_view('footer.php'); ?>
