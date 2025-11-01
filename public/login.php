<?php require_once __DIR__ . '/config-paths.php';
session_start();
$page_title = 'Login';
include_view('header.php');
include_view('navbar.php');

// Handle login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    if (can_query()) {
        $email = mysqli_real_escape_string($conn, $email);
        $query = "SELECT * FROM users WHERE email = '$email' AND role = 'customer'";
        $result = mysqli_query($conn, $query);

        if ($result && mysqli_num_rows($result) > 0) {
            $user = mysqli_fetch_assoc($result);
            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['name'];
                $_SESSION['user_email'] = $user['email'];
                header('Location: account.php');
                exit;
            }
        }
    }
    $error = "Invalid email or password";
}
?>

<!-- Login Section -->
<section class="auth-section">
    <div class="container">
        <div class="auth-container">
            <div class="auth-box">
                <h1>Welcome Back</h1>
                <p class="auth-subtitle">Sign in to your account</p>

                <?php if (isset($error)): ?>
                    <div class="alert alert-error">
                        <i class="fas fa-exclamation-circle"></i> <?php echo $error; ?>
                    </div>
                <?php endif; ?>

                <form method="POST" action="login.php" class="auth-form">
                    <div class="form-group">
                        <label>Email Address</label>
                        <input type="email" name="email" required placeholder="your@email.com">
                    </div>

                    <div class="form-group">
                        <label>Password</label>
                        <input type="password" name="password" required placeholder="Enter your password">
                    </div>

                    <div class="form-options">
                        <label class="checkbox">
                            <input type="checkbox" name="remember">
                            <span>Remember me</span>
                        </label>
                        <a href="forgot-password.php" class="link-gold">Forgot password?</a>
                    </div>

                    <button type="submit" class="btn btn-primary btn-large btn-block">
                        Sign In
                    </button>
                </form>

                <div class="auth-divider">
                    <span>or continue with</span>
                </div>

                <div class="social-login">
                    <button class="btn btn-social">
                        <i class="fab fa-google"></i> Google
                    </button>
                    <button class="btn btn-social">
                        <i class="fab fa-facebook-f"></i> Facebook
                    </button>
                </div>

                <div class="auth-footer">
                    Don't have an account? <a href="register.php" class="link-gold">Sign up</a>
                </div>
            </div>

            <!-- Benefits -->
            <div class="auth-benefits">
                <h2>Why Shop With Us?</h2>
                <div class="benefit-item">
                    <i class="fas fa-shield-alt"></i>
                    <div>
                        <h3>Authenticity Guaranteed</h3>
                        <p>Every timepiece is 100% authentic and comes with certification</p>
                    </div>
                </div>
                <div class="benefit-item">
                    <i class="fas fa-shipping-fast"></i>
                    <div>
                        <h3>Free Worldwide Shipping</h3>
                        <p>On all orders over $100 with full insurance coverage</p>
                    </div>
                </div>
                <div class="benefit-item">
                    <i class="fas fa-award"></i>
                    <div>
                        <h3>5-Year Warranty</h3>
                        <p>Extended international warranty on all luxury watches</p>
                    </div>
                </div>
                <div class="benefit-item">
                    <i class="fas fa-headset"></i>
                    <div>
                        <h3>24/7 Support</h3>
                        <p>Expert customer service team always ready to help</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include_view('footer.php'); ?>
