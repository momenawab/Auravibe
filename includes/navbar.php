<nav class="navbar">
    <div class="container">
        <div class="nav-wrapper">
            <!-- Logo -->
            <a href="<?php echo base_url('index.php'); ?>" class="logo">
                <img src="<?php echo base_url('assets/images/logo.svg'); ?>" alt="Aura Vibe">
            </a>

            <!-- Mobile Menu Toggle -->
            <button class="mobile-toggle" id="mobileToggle">
                <span></span>
                <span></span>
                <span></span>
            </button>

            <!-- Navigation Menu -->
            <div class="nav-menu" id="navMenu">
                <ul class="nav-links">
                    <li><a href="<?php echo base_url('index.php'); ?>" class="nav-link"><?php echo __('home'); ?></a></li>
                    <li><a href="<?php echo base_url('shop.php'); ?>" class="nav-link"><?php echo __('shop'); ?></a></li>
                    <li><a href="<?php echo base_url('about.php'); ?>" class="nav-link"><?php echo __('about'); ?></a></li>
                    <li><a href="<?php echo base_url('contact.php'); ?>" class="nav-link"><?php echo __('contact'); ?></a></li>
                </ul>

                <!-- Right Side Icons -->
                <div class="nav-actions">
                    <!-- Language Switcher -->
                    <a href="?lang=<?php echo get_opposite_language(); ?>" class="language-switcher" title="<?php echo get_language_name(get_opposite_language()); ?>">
                        <i class="fas fa-globe"></i>
                        <span><?php echo get_language_name(get_opposite_language()); ?></span>
                    </a>

                   

                  

                    <a href="<?php echo base_url('cart.php'); ?>" class="nav-icon cart-icon" title="<?php echo __('cart'); ?>">
                        <i class="fas fa-shopping-bag"></i>
                        <?php
                        $cart_count = get_cart_count();
                        if ($cart_count > 0):
                        ?>
                            <span class="cart-count"><?php echo $cart_count; ?></span>
                        <?php endif; ?>
                    </a>
                </div>
            </div>
        </div>
    </div>
</nav>
