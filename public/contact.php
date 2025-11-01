<?php require_once __DIR__ . '/config-paths.php';
$page_title = __('contact_us');
include_view('header.php');
include_view('navbar.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $success = __('message_received_24h');
}
?>

<!-- Contact Hero -->
<section class="contact-hero">
    <div class="container">
        <h1><?php echo __('get_in_touch'); ?></h1>
        <p><?php echo __('here_to_help_luxury'); ?></p>
    </div>
</section>

<!-- Contact Section -->
<section class="contact-section">
    <div class="container">
        <div class="contact-layout">
            <!-- Contact Form -->
            <div class="contact-form-container">
                <h2><?php echo __('send_us_a_message'); ?></h2>

                <?php if (isset($success)): ?>
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle"></i> <?php echo $success; ?>
                    </div>
                <?php endif; ?>

                <form method="POST" action="contact.php" class="contact-form">
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

                    <div class="form-row">
                        <div class="form-group">
                            <label><?php echo __('email'); ?> *</label>
                            <input type="email" name="email" required>
                        </div>
                        <div class="form-group">
                            <label><?php echo __('phone'); ?></label>
                            <input type="tel" name="phone">
                        </div>
                    </div>

                    <div class="form-group">
                        <label><?php echo __('subject'); ?> *</label>
                        <select name="subject" required>
                            <option value=""><?php echo __('select_subject'); ?></option>
                            <option value="product"><?php echo __('product_inquiry'); ?></option>
                            <option value="order"><?php echo __('order_status'); ?></option>
                            <option value="return"><?php echo __('returns_exchanges'); ?></option>
                            <option value="warranty"><?php echo __('warranty_information'); ?></option>
                            <option value="other"><?php echo __('other'); ?></option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label><?php echo __('message'); ?> *</label>
                        <textarea name="message" rows="6" required placeholder="<?php echo __('how_can_we_help'); ?>"></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary btn-large">
                        <?php echo __('send_message'); ?> <i class="fas fa-paper-plane"></i>
                    </button>
                </form>
            </div>

            <!-- Contact Information -->
            <div class="contact-info">
               

                <div class="info-card">
                    <div class="info-icon">
                        <i class="fas fa-phone"></i>
                    </div>
                    <h3><?php echo __('call_us'); ?></h3>
                    <p>
                        <a href="tel:+201032034194">+201032034194</a><br>
                        <small><?php echo __('mon_fri_9_6'); ?></small>
                    </p>
                </div>

                <div class="info-card">
                    <div class="info-icon">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <h3><?php echo __('email_us'); ?></h3>
                    <p>
                        <a href="mailto:info@auravibe.site">info@auravibe.site</a><br>
                        <a href="mailto:support@auravibe.site">support@auravibe.site</a>
                    </p>
                </div>


                <div class="social-links">
                    <h3><?php echo __('follow_us'); ?></h3>
                    <div class="social-icons">
                         <a href="https://www.facebook.com/profile.php?id=61579847750175#" class="social-icon"><i class="fab fa-facebook-f"></i></a>
                    <a href="https://www.instagram.com/aura.vibe_95/" class="social-icon"><i class="fab fa-instagram"></i></a>
                    <a href="https://www.tiktok.com/@aura.vibe0" class="social-icon"><i class="fab fa-tiktok"></i></a>
               
                    </div>
                </div>
            </div>
        </div>

        <!-- FAQ Section -->
        <div class="faq-section">
            <h2><?php echo __('frequently_asked'); ?></h2>
            <div class="faq-grid">
                <div class="faq-item">
                    <h3><?php echo __('are_all_watches_authentic'); ?></h3>
                    <p><?php echo __('all_watches_authentic_answer'); ?></p>
                </div>
                <div class="faq-item">
                    <h3><?php echo __('what_is_return_policy'); ?></h3>
                    <p><?php echo __('no_returns_after_purchase'); ?></p>
                </div>

                <div class="faq-item">
                    <h3><?php echo __('what_payment_methods'); ?></h3>
                    <p><?php echo __('payment_methods_answer'); ?></p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Map Section -->
<section class="map-section">
    <div class="map-container">
        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3305.4447897667845!2d-118.40107492340846!3d34.073619973151874!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x80c2bc04d6d147ab%3A0x4a1c0a8f0b8e6b8e!2sBeverly%20Hills%2C%20CA%2090210!5e0!3m2!1sen!2sus!4v1234567890123!5m2!1sen!2sus"
                width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
    </div>
</section>

<?php include_view('footer.php'); ?>
