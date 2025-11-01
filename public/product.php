<?php require_once __DIR__ . '/config-paths.php';
include_view('header.php');
include_view('navbar.php');

$product_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Get product details
$product = null;
if (can_query() && $product_id > 0) {
    $stmt = mysqli_prepare($conn, "SELECT p.*, c.name as category_name, c.id as category_id
                                     FROM products p
                                     LEFT JOIN categories c ON p.category_id = c.id
                                     
                                     WHERE p.id = ?");
    mysqli_stmt_bind_param($stmt, "i", $product_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    if ($result && mysqli_num_rows($result) > 0) {
        $product = mysqli_fetch_assoc($result);
    }
    mysqli_stmt_close($stmt);
}

// Redirect to shop if product not found
if (!$product) {
    header('Location: shop.php');
    exit();
}

$page_title = $product['name'];
// Note: specifications column doesn't exist in database - using empty array
$specifications = [];
?>

<!-- Product Details Section -->
<section class="product-details">
    <div class="container">
        <div class="breadcrumb">
            <a href="<?php echo base_url(); ?>">Home</a>
            <span>/</span>
            <a href="shop.php">Shop</a>
            <span>/</span>
            <span><?php echo htmlspecialchars($product['name']); ?></span>
        </div>

        <div class="product-layout">
            <!-- Product Images -->
            <div class="product-gallery">
                <div class="main-image">
                    <img id="mainImage" src="<?php echo base_url($product['image']); ?>"
                         alt="<?php echo htmlspecialchars($product['name']); ?>"
                         onerror="this.src='https://via.placeholder.com/600x600/1a1a1a/D4AF37?text=<?php echo urlencode($product['name']); ?>'">
                    <button class="wishlist-btn-large">
                        <i class="far fa-heart"></i>
                    </button>
                </div>
                <div class="thumbnail-gallery">
                    <img src="<?php echo base_url($product['image']); ?>"
                         alt="Main View"
                         onclick="changeImage(this.src)"
                         onerror="this.src='https://via.placeholder.com/150x150/1a1a1a/D4AF37?text=Main'">
                </div>
            </div>

            <!-- Product Info -->
            <div class="product-main-info">
                <div class="product-category"><?php echo htmlspecialchars($product['category_name']); ?></div>
                <h1><?php echo htmlspecialchars($product['name']); ?></h1>
                <div class="product-brand">by <?php echo htmlspecialchars($product['brand']); ?></div>

                <div class="product-rating">
                    <div class="stars">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star-half-alt"></i>
                    </div>
                    <span class="rating-count">(47 reviews)</span>
                </div>

                <div class="product-price-large">
                    $<?php echo number_format($product['price'], 2); ?>
                </div>

                <div class="product-stock">
                    <?php if ($product['stock'] > 0): ?>
                        <span class="in-stock"><i class="fas fa-check-circle"></i> In Stock (<?php echo $product['stock']; ?> available)</span>
                    <?php else: ?>
                        <span class="out-of-stock"><i class="fas fa-times-circle"></i> Out of Stock</span>
                    <?php endif; ?>
                </div>

                <div class="product-description">
                    <p><?php echo nl2br(htmlspecialchars($product['description'])); ?></p>
                </div>

                <!-- Add to Cart Form -->
                <form class="add-to-cart-form">
                    <div class="quantity-selector">
                        <label>Quantity:</label>
                        <div class="quantity-controls">
                            <button type="button" class="qty-btn" onclick="decreaseQty()">-</button>
                            <input type="number" id="quantity" name="quantity" value="1" min="1" max="<?php echo $product['stock']; ?>" style="text-align: center;">
                            <button type="button" class="qty-btn" onclick="increaseQty()">+</button>
                        </div>
                    </div>

                    <button type="button" class="btn btn-primary btn-large btn-block" onclick="addToCart(<?php echo $product['id']; ?>)">
                        <i class="fas fa-shopping-cart"></i> Add to Cart
                    </button>

                    <button type="button" class="btn btn-outline btn-large btn-block">
                        <i class="fas fa-bolt"></i> Buy Now
                    </button>
                </form>

                <!-- Product Features -->
                <div class="product-features">
                    <div class="feature-item">
                        <i class="fas fa-shield-alt"></i>
                        <div>
                            <strong>Authenticity Guaranteed</strong>
                            <p>100% genuine products</p>
                        </div>
                    </div>
                    
                    <div class="feature-item">
                        <i class="fas fa-award"></i>
                        <div>
                            <strong>Warranty</strong>
                            <p>5 years international</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Product Tabs -->
        <div class="product-tabs">
            <div class="tab-buttons">
                <button class="tab-btn active" onclick="openTab('specifications')">Specifications</button>
                <button class="tab-btn" onclick="openTab('reviews')">Reviews (47)</button>
                <button class="tab-btn" onclick="openTab('shipping')">Shipping & Returns</button>
            </div>

            <div id="specifications" class="tab-content active">
                <h3>Technical Specifications</h3>
                <table class="specs-table">
                    <?php if ($specifications && is_array($specifications)): ?>
                        <?php foreach ($specifications as $key => $value): ?>
                            <tr>
                                <th><?php echo htmlspecialchars($key); ?></th>
                                <td><?php echo htmlspecialchars($value); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </table>
            </div>

            <div id="reviews" class="tab-content">
                <h3>Customer Reviews</h3>
                <div class="review-summary">
                    <div class="review-score">
                        <div class="score">4.5</div>
                        <div class="stars">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star-half-alt"></i>
                        </div>
                        <div class="total">Based on 47 reviews</div>
                    </div>
                </div>

                <!-- Sample Reviews -->
                <div class="reviews-list">
                    <div class="review-item">
                        <div class="review-header">
                            <div class="reviewer-name">John D.</div>
                            <div class="review-stars">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                            </div>
                        </div>
                        <div class="review-date">2 weeks ago</div>
                        <div class="review-text">
                            <strong>Absolutely stunning!</strong>
                            <p>This watch exceeded all my expectations. The craftsmanship is impeccable, and it looks even better in person. Worth every penny!</p>
                        </div>
                    </div>

                    <div class="review-item">
                        <div class="review-header">
                            <div class="reviewer-name">Sarah M.</div>
                            <div class="review-stars">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="far fa-star"></i>
                            </div>
                        </div>
                        <div class="review-date">1 month ago</div>
                        <div class="review-text">
                            <strong>Beautiful timepiece</strong>
                            <p>Elegant design and excellent quality. The only minor issue is it's slightly heavier than I expected, but still very comfortable.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div id="shipping" class="tab-content">
                <h3>Shipping Information</h3>
                <p><strong>Free Standard Shipping</strong> on all orders over $100</p>
                <p>Estimated delivery: 3-5 business days</p>
                <p>Express shipping available at checkout</p>

                <h3>Returns & Exchanges</h3>
                <p>No returns are accepted after inspection and purchase.</p>
                <p>Contact our customer service team to if there are any problem.</p>
            </div>
        </div>

        <!-- Related Products -->
        <section class="related-products">
            <h2>You May Also Like</h2>
            <div class="products-grid">
                <?php
                // Load related products from same category
                if (can_query()) {
                    $related_stmt = mysqli_prepare($conn, "SELECT p.*
                                                           FROM products p
                                                           
                                                           WHERE p.category_id = ? AND p.id != ? AND p.stock > 0
                                                           LIMIT 4");
                    mysqli_stmt_bind_param($related_stmt, "ii", $product['category_id'], $product_id);
                    mysqli_stmt_execute($related_stmt);
                    $related_result = mysqli_stmt_get_result($related_stmt);

                    if ($related_result && mysqli_num_rows($related_result) > 0):
                        while($related_product = mysqli_fetch_assoc($related_result)):
                ?>
                    <div class="product-card">
                        <div class="product-image">
                            <img src="<?php echo base_url($related_product['image']); ?>"
                                 alt="<?php echo htmlspecialchars($related_product['name']); ?>"
                                 onerror="this.src='https://via.placeholder.com/400x400/1a1a1a/D4AF37?text=<?php echo urlencode($related_product['name']); ?>'">
                            <div class="product-overlay">
                                <a href="product.php?id=<?php echo $related_product['id']; ?>" class="btn btn-primary">View Details</a>
                            </div>
                        </div>
                        <div class="product-info">
                            <h3><?php echo htmlspecialchars($related_product['name']); ?></h3>
                            <div class="product-brand"><?php echo htmlspecialchars($related_product['brand']); ?></div>
                            <div class="product-price">$<?php echo number_format($related_product['price'], 2); ?></div>
                        </div>
                    </div>
                <?php
                        endwhile;
                    else:
                ?>
                    <div class="no-products" style="grid-column: 1/-1; text-align: center; padding: 40px 20px;">
                        <p style="color: #999;">No related products available at this time.</p>
                    </div>
                <?php
                    endif;
                    mysqli_stmt_close($related_stmt);
                }
                ?>
            </div>
        </section>
    </div>
</section>

<script>
function changeImage(src) {
    document.getElementById('mainImage').src = src.replace('150x150', '600x600');
}

function increaseQty() {
    let qty = document.getElementById('quantity');
    let max = parseInt(qty.max);
    if (parseInt(qty.value) < max) {
        qty.value = parseInt(qty.value) + 1;
    }
}

function decreaseQty() {
    let qty = document.getElementById('quantity');
    if (parseInt(qty.value) > 1) {
        qty.value = parseInt(qty.value) - 1;
    }
}

function addToCart(productId) {
    let quantity = parseInt(document.getElementById('quantity').value);

    fetch('api/cart-add.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            product_id: productId,
            quantity: quantity
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification(data.message, 'success');
            // Update cart count in navbar if exists
            if (data.cart_count) {
                const cartBadge = document.querySelector('.cart-count');
                if (cartBadge) {
                    cartBadge.textContent = data.cart_count;
                }
            }
        } else {
            showNotification(data.message || '<?php echo __('error_occurred'); ?>', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('<?php echo __('error_occurred'); ?>', 'error');
    });
}

function openTab(tabName) {
    // Hide all tab contents
    let contents = document.querySelectorAll('.tab-content');
    contents.forEach(content => content.classList.remove('active'));

    // Remove active from all buttons
    let buttons = document.querySelectorAll('.tab-btn');
    buttons.forEach(btn => btn.classList.remove('active'));

    // Show selected tab
    document.getElementById(tabName).classList.add('active');
    event.target.classList.add('active');
}
</script>

<?php include_view('footer.php'); ?>
