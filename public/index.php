<?php require_once __DIR__ . '/config-paths.php';
$page_title = __('home');
include_view('header.php');
include_view('navbar.php');
?>

<!-- Hero Section with 3D Background -->
<section class="hero">
    <div id="hero-canvas"></div>
    <div class="hero-overlay"></div>
    <div class="hero-content">
        <div class="container">
            <div class="hero-text">
                <h1 class="hero-title">
                    <span class="gold-text"><?php echo __('Timeless Elegance'); ?></span> <?php echo __(''); ?>
                </h1>
                <p class="hero-subtitle"><?php echo __('discover_luxury_watches'); ?></p>
                <div class="hero-buttons">
                    <a href="shop.php" class="btn btn-primary"><?php echo __('explore_collection'); ?></a>
                    <a href="about.php" class="btn btn-secondary"><?php echo __('learn_more'); ?></a>
                </div>
            </div>
        </div>
    </div>
    <div class="scroll-indicator">
        <div class="mouse">
            <div class="wheel"></div>
        </div>
    </div>
</section>

<!-- Featured Categories -->
<section class="categories-section">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title"><?php echo __('explore_our_collections'); ?></h2>
            <p class="section-subtitle"><?php echo __('curated_timepieces'); ?></p>
        </div>

        <div class="categories-grid">
            <?php
            // Fetch categories from database
            if (can_query()) {
                $cat_query = "SELECT c.*, COUNT(p.id) as product_count
                              FROM categories c
                              LEFT JOIN products p ON c.id = p.category_id
                              GROUP BY c.id
                              ORDER BY c.name ASC
                              LIMIT 4";
                $cat_result = mysqli_query($conn, $cat_query);

                if ($cat_result && mysqli_num_rows($cat_result) > 0):
                    while ($category = mysqli_fetch_assoc($cat_result)):
                        // Get category name in current language
                        $cat_name = is_rtl() && !empty($category['name_ar']) ? $category['name_ar'] : $category['name'];
            ?>
            <div class="category-card">
                <div class="category-image">
                    <img src="assets/images/category-<?php echo $category['slug']; ?>.jpg"
                         alt="<?php echo htmlspecialchars($cat_name); ?>"
                         onerror="this.src='https://via.placeholder.com/400x300/1a1a1a/D4AF37?text=<?php echo urlencode($cat_name); ?>'">
                    <div class="category-overlay">
                        <h3><?php echo htmlspecialchars($cat_name); ?></h3>
                        <p><?php echo $category['product_count']; ?> <?php echo __('products_count'); ?></p>
                        <a href="shop.php?category=<?php echo $category['slug']; ?>" class="category-link"><?php echo __('view_collection'); ?></a>
                    </div>
                </div>
            </div>
            <?php
                    endwhile;
                endif;
            }
            ?>
        </div>
    </div>
</section>

<!-- Featured Products -->
<section class="featured-products">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title"><?php echo __('featured_timepieces'); ?></h2>
            <p class="section-subtitle"><?php echo __('handpicked_selections'); ?></p>
        </div>

        <div class="products-grid">
            <?php
            // Fetch featured products from database
            if (can_query()) {
                $query = "SELECT p.*
                          FROM products p
                          WHERE p.is_featured = 1 AND p.stock > 0
                          ORDER BY p.created_at DESC
                          LIMIT 8";
                $result = mysqli_query($conn, $query);

                if ($result && mysqli_num_rows($result) > 0):
                    while ($product = mysqli_fetch_assoc($result)):
                        // Get product name in current language
                        $prod_name = is_rtl() && !empty($product['name_ar']) ? $product['name_ar'] : $product['name'];
            ?>
            <div class="product-card" data-product-id="<?php echo $product['id']; ?>">
                <div class="product-image">
                    <img src="<?php echo base_url($product['image']); ?>" alt="<?php echo htmlspecialchars($prod_name); ?>">
                    <div class="product-badge"><?php echo __('new'); ?></div>
                    <div class="product-actions">
                        <button class="action-btn wishlist-btn" 
                                data-product-id="<?php echo $product['id']; ?>"
                                data-product-name="<?php echo htmlspecialchars($prod_name); ?>"
                                data-product-price="<?php echo $product['price']; ?>"
                                data-product-image="<?php echo base_url($product['image']); ?>"
                                data-product-brand="<?php echo htmlspecialchars($product['brand']); ?>"
                                title="<?php echo __('add_to_wishlist'); ?>">
                            <i class="far fa-heart"></i>
                        </button>
                    </div>
                </div>
                <div class="product-info">
                    <h3 class="product-name"><?php echo htmlspecialchars($prod_name); ?></h3>
                    <p class="product-brand"><?php echo htmlspecialchars($product['brand']); ?></p>
                    <div class="product-price">
                        <span class="price number"><?php echo __('egp'); ?> <?php echo number_format($product['price'], 2); ?></span>
                    </div>
                    <button class="btn btn-add-cart add-to-cart" 
                            data-product-id="<?php echo $product['id']; ?>"
                            data-product-name="<?php echo htmlspecialchars($prod_name); ?>"
                            data-product-price="<?php echo $product['price']; ?>"
                            data-product-image="<?php echo base_url($product['image']); ?>"
                            data-product-brand="<?php echo htmlspecialchars($product['brand']); ?>">
                        <i class="fas fa-shopping-cart"></i> <?php echo __('add_to_cart'); ?>
                    </button>
                    <a href="product.php?id=<?php echo $product['id']; ?>" class="btn btn-secondary" style="margin-top: 10px;">
                        <?php echo __('view_details'); ?>
                    </a>
                </div>
            </div>
            <?php
                    endwhile;
                else:
            ?>
                <div class="no-products" style="grid-column: 1/-1; text-align: center; padding: 60px 20px;">
                    <i class="fas fa-watch" style="font-size: 4rem; color: #D4AF37; opacity: 0.3; margin-bottom: 20px;"></i>
                    <h3 style="color: #D4AF37; margin-bottom: 10px;"><?php echo __('no_featured_products'); ?></h3>
                    <p style="color: #999;"><?php echo __('check_back_soon'); ?></p>
                    <a href="shop.php" class="btn btn-primary" style="margin-top: 20px;"><?php echo __('browse_all_products'); ?></a>
                </div>
            <?php
                endif;
            } else {
            ?>
                <div class="no-products" style="grid-column: 1/-1; text-align: center; padding: 60px 20px;">
                    <i class="fas fa-exclamation-triangle" style="font-size: 4rem; color: #dc2626; opacity: 0.5; margin-bottom: 20px;"></i>
                    <h3 style="color: #dc2626; margin-bottom: 10px;"><?php echo __('database_connection_error'); ?></h3>
                    <p style="color: #999;"><?php echo __('unable_to_load'); ?></p>
                </div>
            <?php
            }
            ?>
        </div>

        <div class="section-footer">
            <a href="shop.php" class="btn btn-primary"><?php echo __('view_all_products'); ?></a>
        </div>
    </div>
</section>

<!-- Why Choose Us -->
<section class="features-section">
    <div class="container">
        <div class="features-grid">
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-shield-alt"></i>
                </div>
                <h3><?php echo __('authenticity_guaranteed'); ?></h3>
                <p><?php echo __('authenticity_desc'); ?></p>
            </div>

            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-undo"></i>
                </div>
                <h3><?php echo __('return_policy'); ?></h3>
                <p><?php echo is_rtl() ? 'لا يتم قبول أي إرجاع بعد الفحص والشراء.' : 'No returns are accepted after inspection and purchase.'; ?></p>
            </div>

            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-headset"></i>
                </div>
                <h3><?php echo __('24_7_support'); ?></h3>
                <p><?php echo __('24_7_support_desc'); ?></p>
            </div>
        </div>
    </div>
</section>

<!-- Testimonials -->
<section class="testimonials-section">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title"><?php echo __('what_clients_say'); ?></h2>
            <p class="section-subtitle"><?php echo __('trusted_by_enthusiasts'); ?></p>
        </div>

        <div class="testimonials-grid">
            <div class="testimonial-card">
                <div class="testimonial-stars">
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                </div>
                <p class="testimonial-text"><?php echo is_rtl() ? '"جودة وخدمة استثنائية. وصلت ساعتي معبأة بشكل جميل مع جميع وثائق الأصالة. موصى به للغاية!"' : '"Exceptional quality and service. My watch arrived beautifully packaged with all authenticity documents. Highly recommended!"'; ?></p>
                <div class="testimonial-author">
                    <h4><?php echo is_rtl() ? 'محمد علي' : 'Michael Chen'; ?></h4>
                    <p><?php echo __('watch_collector'); ?></p>
                </div>
            </div>

            <div class="testimonial-card">
                <div class="testimonial-stars">
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                </div>
                <p class="testimonial-text"><?php echo is_rtl() ? '"ساعدتني معاينة ثلاثية الأبعاد في اختيار الساعة المثالية. كانت خدمة العملاء متميزة طوال العملية."' : '"The 3D preview feature helped me choose the perfect watch. Customer service was outstanding throughout the entire process."'; ?></p>
                <div class="testimonial-author">
                    <h4><?php echo is_rtl() ? 'سارة أحمد' : 'Sarah Williams'; ?></h4>
                    <p><?php echo __('first_time_buyer'); ?></p>
                </div>
            </div>

            <div class="testimonial-card">
                <div class="testimonial-stars">
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                </div>
                <p class="testimonial-text"><?php echo is_rtl() ? '"أفضل متجر ساعات فاخرة عبر الإنترنت. منتجات أصلية وأسعار تنافسية وشحن دولي سريع."' : '"Best luxury watch retailer I\'ve found online. Authentic products, competitive prices, and fast international shipping."'; ?></p>
                <div class="testimonial-author">
                    <h4><?php echo is_rtl() ? 'أحمد حسن' : 'James Rodriguez'; ?></h4>
                    <p><?php echo __('luxury_enthusiast'); ?></p>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include_view('footer.php'); ?>
