<?php
session_start();
require_once '../../includes/db.php';
require_once '../../includes/functions.php';

// Redirect if already logged in
if (is_admin_logged_in()) {
    redirect('dashboard.php');
}

$error = '';

// Handle login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = sanitize($_POST['username']);
    $password = $_POST['password'];

    if (!empty($username) && !empty($password)) {
        // Check if database is available
        if (can_query() && $conn !== null) {
            // Database authentication
            $query = "SELECT * FROM admins WHERE (username = ? OR email = ?) AND is_active = 1";
            $stmt = mysqli_prepare($conn, $query);
            mysqli_stmt_bind_param($stmt, "ss", $username, $username);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            if ($admin = mysqli_fetch_assoc($result)) {
                if (password_verify($password, $admin['password'])) {
                    // Set session
                    $_SESSION['admin_id'] = $admin['id'];
                    $_SESSION['admin_username'] = $admin['username'];
                    $_SESSION['admin_role'] = $admin['role'];
                    $_SESSION['admin_logged_in'] = true;

                    // Update last login
                    $update_query = "UPDATE admins SET last_login = NOW() WHERE id = ?";
                    $update_stmt = mysqli_prepare($conn, $update_query);
                    mysqli_stmt_bind_param($update_stmt, "i", $admin['id']);
                    mysqli_stmt_execute($update_stmt);

                    redirect('dashboard.php');
                } else {
                    $error = 'Invalid username or password';
                }
            } else {
                $error = 'Invalid username or password';
            }
        } else {
            // Database connection error
            $error = 'Database connection error. Please contact the administrator.';
        }
    } else {
        $error = 'Please fill in all fields';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Aura Vibe</title>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Montserrat:wght@400;500;600&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Montserrat', sans-serif;
            background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .login-container {
            background: #ffffff;
            width: 100%;
            max-width: 450px;
            padding: 3rem;
            box-shadow: 0 20px 60px rgba(0,0,0,0.5);
            position: relative;
            overflow: hidden;
        }

        .login-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 5px;
            background: linear-gradient(90deg, #D4AF37, #C9A65C);
        }

        .login-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .login-logo {
            width: 150px;
            margin: 0 auto 1.5rem;
        }

        .login-title {
            font-family: 'Playfair Display', serif;
            font-size: 2rem;
            color: #1a1a1a;
            margin-bottom: 0.5rem;
        }

        .login-subtitle {
            color: #999;
            font-size: 0.95rem;
        }

        .gold-text {
            background: linear-gradient(135deg, #D4AF37, #C9A65C);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .error-message {
            background: #f8d7da;
            color: #721c24;
            padding: 1rem;
            margin-bottom: 1.5rem;
            border: 1px solid #f5c6cb;
            border-radius: 4px;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            color: #333;
            font-weight: 500;
            font-size: 0.95rem;
        }

        .form-input {
            width: 100%;
            padding: 0.9rem 1rem;
            padding-left: 2.8rem;
            border: 2px solid #e0e0e0;
            background: #f8f8f8;
            font-size: 1rem;
            transition: all 0.3s ease;
            outline: none;
        }

        .form-input:focus {
            border-color: #D4AF37;
            background: #ffffff;
        }

        .input-wrapper {
            position: relative;
        }

        .input-icon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #999;
            font-size: 1.1rem;
        }

        .form-input:focus ~ .input-icon {
            color: #D4AF37;
        }

        .btn-login {
            width: 100%;
            padding: 1rem;
            background: linear-gradient(135deg, #D4AF37, #B8941F);
            color: #ffffff;
            border: none;
            font-size: 1rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 1rem;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(212, 175, 55, 0.4);
        }

        .btn-login:active {
            transform: translateY(0);
        }

        .login-footer {
            text-align: center;
            margin-top: 2rem;
            padding-top: 2rem;
            border-top: 1px solid #e0e0e0;
        }

        .login-footer a {
            color: #D4AF37;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .login-footer a:hover {
            color: #B8941F;
        }

        @media (max-width: 480px) {
            .login-container {
                padding: 2rem 1.5rem;
            }

            .login-title {
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <div class="login-title">
                <span class="gold-text">Aura Vibe</span> Admin
            </div>
            <p class="login-subtitle">Sign in to manage your store</p>
        </div>

        <?php if ($error): ?>
            <div class="error-message">
                <i class="fas fa-exclamation-circle"></i>
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-group">
                <label class="form-label">Username or Email</label>
                <div class="input-wrapper">
                    <input
                        type="text"
                        name="username"
                        class="form-input"
                        placeholder="Enter your username or email"
                        required
                        autofocus
                    >
                    <i class="fas fa-user input-icon"></i>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Password</label>
                <div class="input-wrapper">
                    <input
                        type="password"
                        name="password"
                        class="form-input"
                        placeholder="Enter your password"
                        required
                    >
                    <i class="fas fa-lock input-icon"></i>
                </div>
            </div>

            <button type="submit" class="btn-login">
                <i class="fas fa-sign-in-alt"></i> Sign In
            </button>
        </form>

        <div class="login-footer">
            <a href="../index.php">
                <i class="fas fa-arrow-left"></i> Back to Website
            </a>
        </div>
    </div>
</body>
</html>
