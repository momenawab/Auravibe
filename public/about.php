<?php require_once __DIR__ . '/config-paths.php';
$page_title = __('about_us');
include_view('header.php');
include_view('navbar.php');
?>

<!-- About Hero Section -->
<section class="about-hero">
    <div class="container">
        <h1><?php echo __('about_aura_vibe'); ?></h1>
        <p><?php echo __('crafting_timeless_elegance'); ?></p>
    </div>
</section>

<!-- About Section -->
<section class="about-section">
    <div class="container">
        <!-- Our Story -->
        <div class="about-story">
            <div class="story-content">
                <h2><?php echo __('our_story'); ?></h2>
                <p><?php echo __('our_story_para1'); ?></p>
                <p><?php echo __('our_story_para2'); ?></p>
                <p><?php echo __('our_story_para3'); ?></p>
            </div>
            <div class="story-image">
                <img src="assets/images/img1.jpg" alt="<?php echo __('our_story'); ?>">
            </div>
        </div>

        <!-- Our Values -->
        <div class="about-values">
            <h2><?php echo __('our_values'); ?></h2>
            <div class="values-grid">
                <div class="value-card">
                    <div class="value-icon">
                        <i class="fas fa-gem"></i>
                    </div>
                    <h3><?php echo __('authenticity'); ?></h3>
                    <p><?php echo __('authenticity_value_desc'); ?></p>
                </div>
                <div class="value-card">
                    <div class="value-icon">
                        <i class="fas fa-award"></i>
                    </div>
                    <h3><?php echo __('excellence'); ?></h3>
                    <p><?php echo __('excellence_value_desc'); ?></p>
                </div>
                <div class="value-card">
                    <div class="value-icon">
                        <i class="fas fa-handshake"></i>
                    </div>
                    <h3><?php echo __('trust'); ?></h3>
                    <p><?php echo __('trust_value_desc'); ?></p>
                </div>
                <div class="value-card">
                    <div class="value-icon">
                        <i class="fas fa-star"></i>
                    </div>
                    <h3><?php echo __('luxury'); ?></h3>
                    <p><?php echo __('luxury_value_desc'); ?></p>
                </div>
            </div>
        </div>

        <!-- Why Choose Us -->
        <div class="why-choose">
            <h2><?php echo __('why_choose_aura_vibe'); ?></h2>
            <div class="choose-layout">
                <div class="choose-image">
                    <img src="assets/images/logo.jpg" alt="<?php echo __('why_choose_aura_vibe'); ?>">
                </div>
                <div class="choose-content">
                    <div class="choose-item">
                        <div class="choose-icon">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <div class="choose-text">
                            <h3><?php echo __('authenticity_guarantee_abbrev'); ?></h3>
                            <p><?php echo __('authenticity_guarantee_desc'); ?></p>
                        </div>
                    </div>
                    <div class="choose-item">
                        <div class="choose-icon">
                            <i class="fas fa-shipping-fast"></i>
                        </div>
                        <div class="choose-text">
                            <h3><?php echo __('worldwide_shipping'); ?></h3>
                            <p><?php echo __('worldwide_shipping_desc'); ?></p>
                        </div>
                    </div>
                    <div class="choose-item">
                        <div class="choose-icon">
                            <i class="fas fa-headset"></i>
                        </div>
                        <div class="choose-text">
                            <h3><?php echo __('expert_support'); ?></h3>
                            <p><?php echo __('expert_support_desc'); ?></p>
                        </div>
                    </div>
                    <div class="choose-item">
                        <div class="choose-icon">
                            <i class="fas fa-lock"></i>
                        </div>
                        <div class="choose-text">
                            <h3><?php echo __('secure_transactions'); ?></h3>
                            <p><?php echo __('secure_transactions_desc'); ?></p>
                        </div>
                    </div>

                    <div class="choose-item">
                        <div class="choose-icon">
                            <i class="fas fa-certificate"></i>
                        </div>
                        <div class="choose-text">
                            <h3><?php echo __('certified_pre_owned'); ?></h3>
                            <p><?php echo __('certified_pre_owned_desc'); ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats Section -->
        <div class="about-stats">
            <div class="stat-item">
                <div class="stat-number number">10K+</div>
                <div class="stat-label"><?php echo __('happy_customers'); ?></div>
            </div>
            <div class="stat-item">
                <div class="stat-number number">500+</div>
                <div class="stat-label"><?php echo __('luxury_brands'); ?></div>
            </div>
            <div class="stat-item">
                <div class="stat-number number">50+</div>
                <div class="stat-label"><?php echo __('countries_served'); ?></div>
            </div>
            <div class="stat-item">
                <div class="stat-number number">99%</div>
                <div class="stat-label"><?php echo __('satisfaction_rate'); ?></div>
            </div>
        </div>

        <!-- Call to Action -->
        <div class="about-cta">
            <h2><?php echo __('begin_luxury_watch_journey'); ?></h2>
            <p><?php echo __('explore_exquisite_collection'); ?></p>
            <div class="cta-buttons">
                <a href="shop.php" class="btn btn-primary btn-large"><?php echo __('explore_collection'); ?></a>
                <a href="contact.php" class="btn btn-secondary btn-large"><?php echo __('contact_us'); ?></a>
            </div>
        </div>
    </div>
</section>

<?php include_view('footer.php'); ?>
