<footer class="footer">
    <div class="container">
        <div class="footer-content">
            <!-- Footer Column 1 - About -->
            <div class="footer-column">
                <h3><?php echo __('about_us'); ?></h3>
                <p><?php echo is_rtl() ? 'اكتشف الأناقة الخالدة مع مجموعتنا المختارة من الساعات الفاخرة. كل ساعة تحكي قصة فريدة من الحرفية والدقة.' : 'Experience timeless elegance with our curated collection of luxury watches. Each timepiece tells a unique story of craftsmanship and precision.'; ?></p>
                <div class="social-links">
                    <a href="https://www.facebook.com/profile.php?id=61579847750175#" class="social-icon"><i class="fab fa-facebook-f"></i></a>
                    <a href="https://www.instagram.com/aura.vibe_95/" class="social-icon"><i class="fab fa-instagram"></i></a>
                    <a href="https://www.tiktok.com/@aura.vibe0" class="social-icon"><i class="fab fa-tiktok"></i></a>
                </div>
            </div>

            <!-- Footer Column 2 - Quick Links -->
            <div class="footer-column">
                <h3><?php echo __('quick_links'); ?></h3>
                <ul class="footer-links">
                    <li><a href="<?php echo base_url('shop.php'); ?>"><?php echo __('shop'); ?></a></li>
                    <li><a href="<?php echo base_url('about.php'); ?>"><?php echo __('about'); ?></a></li>
                    <li><a href="<?php echo base_url('contact.php'); ?>"><?php echo __('contact'); ?></a></li>
                </ul>
            </div>

            <!-- Footer Column 3 - Customer Service -->
           
            <!-- Footer Column 4 - Newsletter -->
            <div class="footer-column">
                <h3><?php echo __('newsletter'); ?></h3>
                <p><?php echo is_rtl() ? 'اشترك لتلقي التحديثات والعروض الحصرية.' : 'Subscribe to receive updates and exclusive offers.'; ?></p>
                <form class="newsletter-form" method="POST" action="subscribe.php">
                    <input type="email" name="email" placeholder="<?php echo __('enter_email'); ?>" required>
                    <button type="submit"><i class="fas fa-paper-plane"></i></button>
                </form>
            </div>
        </div>

        <div class="footer-bottom">
            <p>&copy; <?php echo date('Y'); ?> Aura Vibe. <?php echo __('all_rights_reserved'); ?>.</p>
            <div class="footer-legal">
               
            </div>
        </div>
    </div>
</footer>

<!-- Privacy Policy Modal -->

<!-- Terms & Conditions Modal -->

<!-- Modal JavaScript -->
<script>
function openModal(modalId) {
    document.getElementById(modalId).style.display = 'flex';
    document.body.style.overflow = 'hidden';
}

function closeModal(modalId) {
    document.getElementById(modalId).style.display = 'none';
    document.body.style.overflow = 'auto';
}

// Close modal when clicking outside
document.querySelectorAll('.modal-overlay').forEach(modal => {
    modal.addEventListener('click', function(e) {
        if (e.target === this) {
            closeModal(this.id);
        }
    });
});

// Close modal with ESC key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        document.querySelectorAll('.modal-overlay').forEach(modal => {
            if (modal.style.display === 'flex') {
                closeModal(modal.id);
            }
        });
    }
});
</script>

<!-- Main JavaScript -->
<script src="<?php echo base_url('assets/js/main.js'); ?>"></script>
</body>
</html>
