<?php require_once __DIR__ . '/config-paths.php';
$page_title = __('shopping_cart');
include_view('header.php');
include_view('navbar.php');

// Initialize cart if not exists
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Use actual cart items (no demo)
$cart_items = $_SESSION['cart'];
$subtotal = 0;
foreach ($cart_items as $item) {
    $subtotal += $item['price'] * $item['quantity'];
}

$shipping = $subtotal > 100 ? 0 : 15.00;
$tax = $subtotal * 0.14; // 8% tax
$total = $subtotal + $shipping + $tax;
?>

<!-- Cart Section -->
<section class="cart-section">
    <div class="container">
        <h1><?php echo __('shopping_cart'); ?></h1>

        <?php if (count($cart_items) > 0): ?>
            <div class="cart-layout">
                <!-- Cart Items -->
                <div class="cart-items">
                    <?php foreach ($cart_items as $index => $item): ?>
                        <div class="cart-item">
                            <div class="item-image">
                                <img src="https://via.placeholder.com/150x150/1a1a1a/D4AF37?text=<?php echo urlencode($item['name']); ?>"
                                     alt="<?php echo htmlspecialchars($item['name']); ?>">
                            </div>
                            <div class="item-details">
                                <h3><?php echo htmlspecialchars($item['name']); ?></h3>
                                <div class="item-brand"><?php echo htmlspecialchars($item['brand'] ?? ''); ?></div>
                                <div class="item-price number"><?php echo __('egp'); ?> <?php echo number_format($item['price'], 2); ?></div>
                            </div>
                            <div class="item-quantity">
                                <label><?php echo __('quantity'); ?>:</label>
                                <div class="quantity-controls" style="display: flex !important; align-items: center; justify-content: center; border: 2px solid #ddd; border-radius: 12px; background: white; width: 160px; height: 48px; margin: 0 auto; overflow: hidden;">
                                    <button class="qty-btn qty-decrease" onclick="updateQuantity(<?php echo $index; ?>, -1)" style="width: 50px; height: 48px; background: #f0f0f0; border: none; font-size: 1.6rem; font-weight: 700; cursor: pointer; color: #333; display: flex; align-items: center; justify-content: center;">-</button>
                                    <span class="qty-value" id="qty-<?php echo $index; ?>" style="width: 60px; height: 48px; text-align: center; font-size: 1.2rem; font-weight: 700; color: #333 !important; background: #fafafa; display: flex; align-items: center; justify-content: center; border-left: 2px solid #eee; border-right: 2px solid #eee;"><?php echo $item['quantity']; ?></span>
                                    <button class="qty-btn qty-increase" onclick="updateQuantity(<?php echo $index; ?>, 1)" style="width: 50px; height: 48px; background: #f0f0f0; border: none; font-size: 1.6rem; font-weight: 700; cursor: pointer; color: #333; display: flex; align-items: center; justify-content: center;">+</button>
                                </div>
                            </div>
                            <div class="item-total number">
                                <?php echo __('egp'); ?> <?php echo number_format($item['price'] * $item['quantity'], 2); ?>
                            </div>
                            <button class="item-remove" onclick="removeItem(<?php echo $index; ?>)">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- Cart Summary -->
                <div class="cart-summary">
                    <h2><?php echo __('order_summary'); ?></h2>

                    <div class="summary-row">
                        <span><?php echo __('subtotal'); ?>:</span>
                        <span class="number"><?php echo __('egp'); ?> <?php echo number_format($subtotal, 2); ?></span>
                    </div>

                   

                    

                    <div class="summary-row">
                        <span><?php echo __('tax'); ?> (14%):</span>
                        <span class="number"><?php echo __('egp'); ?> <?php echo number_format($tax, 2); ?></span>
                    </div>

                    <div class="summary-divider"></div>

                    <div class="summary-row summary-total">
                        <span><?php echo __('total'); ?>:</span>
                        <span class="number"><?php echo __('egp'); ?> <?php echo number_format($total, 2); ?></span>
                    </div>

                    <a href="checkout.php" class="btn btn-primary btn-large btn-block">
                        <?php echo __('proceed_to_checkout'); ?> <i class="fas fa-arrow-<?php echo is_rtl() ? 'left' : 'right'; ?>"></i>
                    </a>

                    <a href="shop.php" class="btn btn-outline btn-block">
                        <i class="fas fa-arrow-<?php echo is_rtl() ? 'right' : 'left'; ?>"></i> <?php echo __('continue_shopping'); ?>
                    </a>

                    <!-- Promo Code -->
                    <div class="promo-code">
                        <h3><?php echo __('have_promo_code'); ?></h3>
                        <form class="promo-form">
                            <input type="text" placeholder="<?php echo __('enter_code'); ?>" name="promo_code">
                            <button type="submit" class="btn btn-primary"><?php echo __('apply'); ?></button>
                        </form>
                    </div>

                    <!-- Trust Badges -->
                    <div class="trust-badges">
                        <div class="badge-item">
                            <i class="fas fa-lock"></i>
                            <span><?php echo __('secure_payment'); ?></span>
                        </div>
                        <div class="badge-item">
                            <i class="fas fa-shield-alt"></i>
                            <span><?php echo __('buyer_protection'); ?></span>
                        </div>
                        <div class="badge-item">
                            <i class="fas fa-truck"></i>
                            <span><?php echo __('fast_delivery'); ?></span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recommended Products -->
            <section class="recommended-section">
                <h2><?php echo __('complete_your_collection'); ?></h2>
                <div class="products-grid">
                    <?php
                    $recommended = [
                        ['id' => 7, 'name' => 'Rose Gold Automatic', 'brand' => 'ETERNITY', 'price' => 3299.00],
                        ['id' => 8, 'name' => 'Carbon Fiber Racing', 'brand' => 'SPORT MAX', 'price' => 2799.00],
                        ['id' => 5, 'name' => 'Titanium Sport Pro', 'brand' => 'SPORT MAX', 'price' => 2299.00],
                    ];
                    foreach($recommended as $product):
                    ?>
                        <div class="product-card">
                            <div class="product-image">
                                <img src="https://via.placeholder.com/400x400/1a1a1a/D4AF37?text=<?php echo urlencode($product['name']); ?>"
                                     alt="<?php echo htmlspecialchars($product['name']); ?>">
                                <div class="product-overlay">
                                    <a href="product.php?id=<?php echo $product['id']; ?>" class="btn btn-primary"><?php echo __('view_details'); ?></a>
                                </div>
                            </div>
                            <div class="product-info">
                                <h3><?php echo htmlspecialchars($product['name']); ?></h3>
                                <div class="product-brand"><?php echo htmlspecialchars($product['brand']); ?></div>
                                <div class="product-price number"><?php echo __('egp'); ?> <?php echo number_format($product['price'], 2); ?></div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </section>

        <?php else: ?>
            <!-- Empty Cart -->
            <div class="empty-cart">
                <i class="fas fa-shopping-cart"></i>
                <h2><?php echo __('your_cart_is_empty'); ?></h2>
                <p><?php echo __('looks_like_empty_cart'); ?></p>
                <a href="shop.php" class="btn btn-primary btn-large"><?php echo __('start_shopping'); ?></a>
            </div>
        <?php endif; ?>
    </div>
</section>

<script>
function updateQuantity(index, change) {
    const formData = new FormData();
    formData.append('action', 'update_quantity');
    formData.append('index', index);
    formData.append('change', change);

    fetch('api/cart-update.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification('<?php echo __('quantity_updated'); ?>', 'success');
            location.reload();
        } else {
            showNotification(data.message || '<?php echo __('error_occurred'); ?>', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('<?php echo __('error_occurred'); ?>', 'error');
    });
}

function removeItem(index) {
    if (confirm('<?php echo __('remove_item_confirm'); ?>')) {
        const formData = new FormData();
        formData.append('action', 'remove');
        formData.append('index', index);

        fetch('api/cart-update.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification('<?php echo __('removed_from_cart'); ?>', 'success');
                setTimeout(() => location.reload(), 500);
            } else {
                showNotification(data.message || '<?php echo __('error_occurred'); ?>', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('<?php echo __('error_occurred'); ?>', 'error');
        });
    }
}
</script>

<?php include_view('footer.php'); ?>
