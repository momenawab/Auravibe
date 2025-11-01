<?php require_once __DIR__ . '/config-paths.php';
session_start();
$page_title = 'Register';
include_view('header.php');
include_view('navbar.php');

// Handle registration
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    if ($password !== $confirm_password) {
        $error = "Passwords do not match";
    } elseif (can_query()) {
        $email = mysqli_real_escape_string($conn, $email);
        $name = mysqli_real_escape_string($conn, $name);
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $query = "INSERT INTO users (name, email, password, role, created_at)
                  VALUES ('$name', '$email', '$hashed_password', 'customer', NOW())";

        if (mysqli_query($conn, $query)) {
            $_SESSION['user_id'] = mysqli_insert_id($conn);
            $_SESSION['user_name'] = $name;
            $_SESSION['user_email'] = $email;
            header('Location: account.php');
            exit;
        } else {
            $error = "Email already exists";
        }
    } else {
        $success = "Demo mode: Registration simulated successfully!";
    }
}
?>

<!-- Register Section -->
<section class="auth-section">
    <div class="container">
        <div class="auth-container">
            <div class="auth-box">
                <h1>Create Account</h1>
                <p class="auth-subtitle">Join the Aura Vibe luxury watch community</p>

                <?php if (isset($error)): ?>
                    <div class="alert alert-error">
                        <i class="fas fa-exclamation-circle"></i> <?php echo $error; ?>
                    </div>
                <?php endif; ?>

                <?php if (isset($success)): ?>
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle"></i> <?php echo $success; ?>
                    </div>
                <?php endif; ?>

                <form method="POST" action="register.php" class="auth-form">
                    <div class="form-group">
                        <label>Full Name</label>
                        <input type="text" name="name" required placeholder="John Doe">
                    </div>

                    <div class="form-group">
                        <label>Email Address</label>
                        <input type="email" name="email" required placeholder="your@email.com">
                    </div>

                    <div class="form-group">
                        <label>Password</label>
                        <input type="password" name="password" required placeholder="Create a strong password" minlength="8">
                        <small>Must be at least 8 characters</small>
                    </div>

                    <div class="form-group">
                        <label>Confirm Password</label>
                        <input type="password" name="confirm_password" required placeholder="Confirm your password">
                    </div>

                    <div class="form-group">
                        <label class="checkbox">
                            <input type="checkbox" required>
                            <span>I agree to the <a href="terms.php" class="link-gold">Terms & Conditions</a> and <a href="privacy.php" class="link-gold">Privacy Policy</a></span>
                        </label>
                    </div>

                    <button type="submit" class="btn btn-primary btn-large btn-block">
                        Create Account
                    </button>
                </form>

                <div class="auth-divider">
                    <span>or sign up with</span>
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
                    Already have an account? <a href="login.php" class="link-gold">Sign in</a>
                </div>
            </div>

            <!-- Benefits -->
            <div class="auth-benefits">
                <h2>Member Benefits</h2>
                <div class="benefit-item">
                    <i class="fas fa-tag"></i>
                    <div>
                        <h3>Exclusive Offers</h3>
                        <p>Get access to member-only discounts and early access to sales</p>
                    </div>
                </div>
                <div class="benefit-item">
                    <i class="fas fa-bell"></i>
                    <div>
                        <h3>New Arrivals Alerts</h3>
                        <p>Be the first to know about new luxury timepieces</p>
                    </div>
                </div>
                <div class="benefit-item">
                    <i class="fas fa-history"></i>
                    <div>
                        <h3>Order History</h3>
                        <p>Track your orders and manage your watch collection</p>
                    </div>
                </div>
                <div class="benefit-item">
                    <i class="fas fa-heart"></i>
                    <div>
                        <h3>Wishlist</h3>
                        <p>Save your favorite watches for later purchase</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include_view('footer.php'); ?>
