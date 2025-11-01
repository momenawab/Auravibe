<?php require_once __DIR__ . '/config-paths.php';
session_start();
include_view('config.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Terms & Conditions - Aura Vibe</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --gold: #D4AF37;
            --dark-gold: #B8941F;
            --light-gold: #F4E5B7;
            --black: #1a1a1a;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #ffffff;
            color: #333;
        }
        
        /* Hero Section */
        .page-hero {
            background: linear-gradient(135deg, var(--black) 0%, var(--dark-gold) 100%);
            color: white;
            padding: 80px 0 60px 0;
            text-align: center;
        }
        
        .page-hero h1 {
            font-size: 3rem;
            font-weight: 700;
            margin-bottom: 15px;
        }
        
        .page-hero p {
            font-size: 1.1rem;
            opacity: 0.9;
        }
        
        /* Content Section */
        .content-section {
            padding: 60px 0;
            background-color: #f8f9fa;
        }
        
        .content-container {
            background: white;
            padding: 50px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
            border-left: 5px solid var(--gold);
        }
        
        .last-updated {
            background: var(--light-gold);
            color: var(--dark-gold);
            padding: 15px 25px;
            border-radius: 10px;
            margin-bottom: 30px;
            font-weight: 600;
            display: inline-block;
        }
        
        .content-container h2 {
            color: var(--dark-gold);
            font-size: 1.8rem;
            font-weight: 700;
            margin-top: 35px;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid var(--light-gold);
        }
        
        .content-container h2:first-of-type {
            margin-top: 0;
        }
        
        .content-container h3 {
            color: var(--black);
            font-size: 1.4rem;
            font-weight: 600;
            margin-top: 25px;
            margin-bottom: 15px;
        }
        
        .content-container p {
            font-size: 1.05rem;
            line-height: 1.8;
            color: #555;
            margin-bottom: 15px;
        }
        
        .content-container ul, .content-container ol {
            margin-bottom: 20px;
            padding-left: 30px;
        }
        
        .content-container li {
            font-size: 1.05rem;
            line-height: 1.8;
            color: #555;
            margin-bottom: 10px;
        }
        
        .content-container strong {
            color: var(--dark-gold);
        }
        
        .highlight-box {
            background: linear-gradient(135deg, rgba(212,175,55,0.05) 0%, rgba(212,175,55,0.1) 100%);
            border-left: 4px solid var(--gold);
            padding: 20px 25px;
            border-radius: 10px;
            margin: 25px 0;
        }
        
        .highlight-box p {
            margin-bottom: 0;
            color: #444;
        }
        
        .contact-box {
            background: linear-gradient(135deg, var(--gold), var(--dark-gold));
            color: white;
            padding: 30px;
            border-radius: 15px;
            margin-top: 40px;
            text-align: center;
        }
        
        .contact-box h3 {
            color: white;
            margin-bottom: 15px;
        }
        
        .contact-box p {
            color: white;
            margin-bottom: 10px;
        }
        
        .contact-box a {
            color: white;
            font-weight: 600;
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <?php include_view('header.php'); ?>
    <?php include_view('navbar.php'); ?>
    
    <!-- Hero Section -->
    <section class="page-hero">
        <div class="container">
            <h1><i class="fas fa-file-contract"></i> Terms & Conditions</h1>
            <p>Please read these terms carefully before using our services</p>
        </div>
    </section>
    
    <!-- Content Section -->
    <section class="content-section">
        <div class="container">
            <div class="content-container">
                <div class="last-updated">
                    <i class="fas fa-calendar-alt"></i> Last Updated: October 15, 2025
                </div>
                
                <h2>1. Introduction</h2>
                <p>
                    Welcome to Aura Vibe. These Terms and Conditions ("Terms") govern your use of our website and the purchase of products from our luxury watch store. By accessing or using our website, you agree to be bound by these Terms. If you do not agree with any part of these Terms, please do not use our website.
                </p>
                
                <h2>2. Definitions</h2>
                <p>In these Terms:</p>
                <ul>
                    <li><strong>"We", "Us", "Our"</strong> refers to Aura Vibe</li>
                    <li><strong>"You", "Your"</strong> refers to the user or customer</li>
                    <li><strong>"Website"</strong> refers to auravibe.com and all associated pages</li>
                    <li><strong>"Products"</strong> refers to luxury watches and related items sold on our website</li>
                    <li><strong>"Services"</strong> refers to all services provided through our website</li>
                </ul>
                
                <h2>3. Use of Website</h2>
                <h3>3.1 Eligibility</h3>
                <p>
                    You must be at least 18 years old to make purchases on our website. By using our website, you represent and warrant that you are at least 18 years of age.
                </p>
                
                <h3>3.2 Account Registration</h3>
                <p>
                    To make purchases, you may need to create an account. You agree to:
                </p>
                <ul>
                    <li>Provide accurate, current, and complete information</li>
                    <li>Maintain and update your information to keep it accurate and current</li>
                    <li>Maintain the security of your password and account</li>
                    <li>Accept responsibility for all activities under your account</li>
                    <li>Notify us immediately of any unauthorized use of your account</li>
                </ul>
                
                <h3>3.3 Prohibited Activities</h3>
                <p>You agree not to:</p>
                <ul>
                    <li>Use the website for any unlawful purpose</li>
                    <li>Attempt to gain unauthorized access to any part of the website</li>
                    <li>Interfere with or disrupt the website or servers</li>
                    <li>Use any automated system to access the website</li>
                    <li>Reproduce, duplicate, or copy any part of the website without permission</li>
                    <li>Resell or make commercial use of the website without permission</li>
                </ul>
                
                <h2>4. Products and Pricing</h2>
                <h3>4.1 Product Information</h3>
                <p>
                    We strive to provide accurate product descriptions and images. However, we do not warrant that product descriptions, images, or other content are accurate, complete, reliable, current, or error-free.
                </p>
                
                <h3>4.2 Pricing</h3>
                <p>
                    All prices are listed in USD and are subject to change without notice. We reserve the right to modify prices at any time. The price charged will be the price displayed at the time of order placement.
                </p>
                
                <h3>4.3 Authenticity Guarantee</h3>
                <div class="highlight-box">
                    <p>
                        <strong><i class="fas fa-shield-alt"></i> Authenticity Guarantee:</strong> All watches sold by Aura Vibe are 100% authentic and come with appropriate documentation. We guarantee the authenticity of every timepiece in our collection.
                    </p>
                </div>
                
                <h2>5. Orders and Payment</h2>
                <h3>5.1 Order Acceptance</h3>
                <p>
                    Your order constitutes an offer to purchase products. We reserve the right to accept or decline your order for any reason. Order confirmation does not signify acceptance of your order or confirmation of product availability.
                </p>
                
                <h3>5.2 Payment</h3>
                <p>
                    Payment must be received before order fulfillment. We accept the following payment methods:
                </p>
                <ul>
                    <li>Credit Cards (Visa, MasterCard, American Express)</li>
                    <li>Debit Cards</li>
                    <li>PayPal</li>
                    <li>Bank Transfers (for orders above $5,000)</li>
                </ul>
                
                <h3>5.3 Payment Security</h3>
                <p>
                    All payment transactions are processed securely. We do not store credit card information on our servers. Payment information is transmitted using SSL encryption technology.
                </p>
                
                <h2>6. Shipping and Delivery</h2>
                <h3>6.1 Shipping</h3>
                <p>
                    We ship worldwide using trusted courier services. Shipping times and costs vary depending on destination and selected shipping method. All high-value items are shipped with full insurance and require signature upon delivery.
                </p>
                
                <h3>6.2 Risk of Loss</h3>
                <p>
                    Risk of loss and title for products pass to you upon delivery to the carrier. We are not responsible for delays caused by shipping carriers or customs.
                </p>
                
                <h2>7. Returns and Refunds</h2>
                <h3>7.1 Return Policy</h3>
                <p>
                    We accept returns within 14 days of delivery for unworn watches in original condition with all documentation and packaging. Custom orders and personalized items cannot be returned.
                </p>
                
                <h3>7.2 Refund Process</h3>
                <p>
                    Refunds will be processed to the original payment method within 7-10 business days after we receive and inspect the returned item. Original shipping costs are non-refundable.
                </p>
                
                <h2>8. Warranty</h2>
                <p>
                    All watches come with the manufacturer's warranty. Warranty terms vary by brand and model. Warranty information is provided with each purchase. We are not responsible for warranty service; all warranty claims must be directed to the manufacturer.
                </p>
                
                <h2>9. Limitation of Liability</h2>
                <p>
                    To the maximum extent permitted by law, Aura Vibe shall not be liable for any indirect, incidental, special, consequential, or punitive damages arising from your use of the website or purchase of products.
                </p>
                
                <h2>10. Intellectual Property</h2>
                <p>
                    All content on this website, including text, images, logos, and graphics, is the property of Aura Vibe or its content suppliers and is protected by copyright and trademark laws. You may not use any content without our express written permission.
                </p>
                
                <h2>11. Governing Law</h2>
                <p>
                    These Terms are governed by and construed in accordance with the laws of the jurisdiction in which Aura Vibe operates, without regard to conflict of law principles.
                </p>
                
                <h2>12. Changes to Terms</h2>
                <p>
                    We reserve the right to modify these Terms at any time. Changes will be effective immediately upon posting to the website. Your continued use of the website after changes constitutes acceptance of the modified Terms.
                </p>
                
                <h2>13. Severability</h2>
                <p>
                    If any provision of these Terms is found to be unenforceable or invalid, that provision will be limited or eliminated to the minimum extent necessary, and the remaining provisions will remain in full force and effect.
                </p>
                
                <div class="contact-box">
                    <h3><i class="fas fa-envelope"></i> Questions About Our Terms?</h3>
                    <p>If you have any questions about these Terms and Conditions, please contact us:</p>
                    <p><strong>Email:</strong> <a href="mailto:legal@auravibe.com">legal@auravibe.com</a></p>
                    <p><strong>Phone:</strong> +1 (555) 123-4567</p>
                </div>
            </div>
        </div>
    </section>
    
    <?php include_view('footer.php'); ?>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
