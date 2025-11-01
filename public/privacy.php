<?php require_once __DIR__ . '/config-paths.php';
session_start();
include_view('config.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Privacy Policy - Aura Vibe</title>
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
        
        .info-card {
            background: linear-gradient(135deg, rgba(212,175,55,0.05) 0%, rgba(212,175,55,0.08) 100%);
            border: 2px solid var(--light-gold);
            border-radius: 15px;
            padding: 25px;
            margin: 25px 0;
        }
        
        .info-card h4 {
            color: var(--dark-gold);
            font-weight: 700;
            margin-bottom: 15px;
            font-size: 1.2rem;
        }
        
        .info-card ul {
            margin-bottom: 0;
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
            <h1><i class="fas fa-shield-alt"></i> Privacy Policy</h1>
            <p>Your privacy is important to us</p>
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
                    Welcome to Aura Vibe's Privacy Policy. We are committed to protecting your personal information and your right to privacy. This Privacy Policy explains how we collect, use, disclose, and safeguard your information when you visit our website and make purchases from our luxury watch store.
                </p>
                <p>
                    Please read this Privacy Policy carefully. If you do not agree with the terms of this Privacy Policy, please do not access our website.
                </p>
                
                <div class="highlight-box">
                    <p>
                        <strong><i class="fas fa-lock"></i> Our Commitment:</strong> We respect your privacy and are committed to protecting your personal data. We will never sell or rent your personal information to third parties.
                    </p>
                </div>
                
                <h2>2. Information We Collect</h2>
                <p>We collect information that you provide directly to us and information that is automatically collected when you use our website.</p>
                
                <h3>2.1 Information You Provide</h3>
                <div class="info-card">
                    <h4>Personal Information</h4>
                    <ul>
                        <li><strong>Account Information:</strong> Name, email address, password</li>
                        <li><strong>Contact Information:</strong> Phone number, billing address, shipping address</li>
                        <li><strong>Payment Information:</strong> Credit card details, billing information (processed securely by payment providers)</li>
                        <li><strong>Communication:</strong> Messages you send to us, customer service inquiries</li>
                        <li><strong>Profile Information:</strong> Preferences, interests, purchase history</li>
                    </ul>
                </div>
                
                <h3>2.2 Automatically Collected Information</h3>
                <div class="info-card">
                    <h4>Technical Information</h4>
                    <ul>
                        <li><strong>Device Information:</strong> IP address, browser type, operating system</li>
                        <li><strong>Usage Data:</strong> Pages visited, time spent on pages, click patterns</li>
                        <li><strong>Cookies:</strong> Small data files stored on your device (see Cookie Policy below)</li>
                        <li><strong>Location Data:</strong> General geographic location based on IP address</li>
                    </ul>
                </div>
                
                <h2>3. How We Use Your Information</h2>
                <p>We use the information we collect for various purposes:</p>
                
                <h3>3.1 Primary Uses</h3>
                <ul>
                    <li><strong>Order Processing:</strong> To process and fulfill your orders, including shipping and delivery</li>
                    <li><strong>Account Management:</strong> To create and manage your account</li>
                    <li><strong>Customer Service:</strong> To respond to your inquiries and provide support</li>
                    <li><strong>Payment Processing:</strong> To process payments securely</li>
                    <li><strong>Communication:</strong> To send order confirmations, shipping updates, and important notices</li>
                </ul>
                
                <h3>3.2 Secondary Uses</h3>
                <ul>
                    <li><strong>Marketing:</strong> To send promotional materials and special offers (with your consent)</li>
                    <li><strong>Personalization:</strong> To customize your shopping experience</li>
                    <li><strong>Analytics:</strong> To analyze website usage and improve our services</li>
                    <li><strong>Security:</strong> To detect and prevent fraud and security threats</li>
                    <li><strong>Legal Compliance:</strong> To comply with legal obligations and protect our rights</li>
                </ul>
                
                <h2>4. Information Sharing and Disclosure</h2>
                <p>We may share your information in the following circumstances:</p>
                
                <h3>4.1 Service Providers</h3>
                <p>
                    We share information with third-party service providers who perform services on our behalf, including:
                </p>
                <ul>
                    <li>Payment processors (e.g., Stripe, PayPal)</li>
                    <li>Shipping and delivery services</li>
                    <li>Email service providers</li>
                    <li>Website hosting providers</li>
                    <li>Analytics services</li>
                </ul>
                
                <h3>4.2 Legal Requirements</h3>
                <p>
                    We may disclose your information if required to do so by law or in response to valid requests by public authorities (e.g., court orders, subpoenas).
                </p>
                
                <h3>4.3 Business Transfers</h3>
                <p>
                    In the event of a merger, acquisition, or sale of assets, your information may be transferred to the acquiring entity.
                </p>
                
                <div class="highlight-box">
                    <p>
                        <strong><i class="fas fa-hand-holding-heart"></i> Important:</strong> We will never sell or rent your personal information to third parties for their marketing purposes without your explicit consent.
                    </p>
                </div>
                
                <h2>5. Data Security</h2>
                <p>
                    We implement appropriate technical and organizational security measures to protect your personal information against unauthorized access, alteration, disclosure, or destruction. These measures include:
                </p>
                <ul>
                    <li>SSL/TLS encryption for data transmission</li>
                    <li>Secure payment processing through PCI-DSS compliant providers</li>
                    <li>Regular security audits and monitoring</li>
                    <li>Access controls and authentication</li>
                    <li>Employee training on data protection</li>
                </ul>
                <p>
                    However, no method of transmission over the internet or electronic storage is 100% secure. While we strive to protect your personal information, we cannot guarantee absolute security.
                </p>
                
                <h2>6. Cookies and Tracking Technologies</h2>
                <h3>6.1 What Are Cookies?</h3>
                <p>
                    Cookies are small text files stored on your device that help us provide and improve our services. We use cookies and similar tracking technologies to track activity on our website.
                </p>
                
                <h3>6.2 Types of Cookies We Use</h3>
                <ul>
                    <li><strong>Essential Cookies:</strong> Required for website functionality (e.g., shopping cart, login)</li>
                    <li><strong>Performance Cookies:</strong> Help us understand how visitors use our website</li>
                    <li><strong>Functionality Cookies:</strong> Remember your preferences and settings</li>
                    <li><strong>Marketing Cookies:</strong> Track your browsing to provide relevant advertisements</li>
                </ul>
                
                <h3>6.3 Managing Cookies</h3>
                <p>
                    You can control and manage cookies through your browser settings. However, disabling cookies may affect website functionality.
                </p>
                
                <h2>7. Your Privacy Rights</h2>
                <p>Depending on your location, you may have the following rights regarding your personal information:</p>
                
                <div class="info-card">
                    <h4>Your Rights</h4>
                    <ul>
                        <li><strong>Access:</strong> Request access to your personal information</li>
                        <li><strong>Correction:</strong> Request correction of inaccurate information</li>
                        <li><strong>Deletion:</strong> Request deletion of your personal information</li>
                        <li><strong>Portability:</strong> Request a copy of your data in a portable format</li>
                        <li><strong>Opt-Out:</strong> Opt-out of marketing communications</li>
                        <li><strong>Restriction:</strong> Request restriction of processing</li>
                        <li><strong>Objection:</strong> Object to processing of your personal information</li>
                    </ul>
                </div>
                
                <p>
                    To exercise any of these rights, please contact us using the information provided at the end of this policy.
                </p>
                
                <h2>8. Data Retention</h2>
                <p>
                    We retain your personal information for as long as necessary to fulfill the purposes outlined in this Privacy Policy, unless a longer retention period is required or permitted by law. When we no longer need your information, we will securely delete or anonymize it.
                </p>
                
                <h2>9. International Data Transfers</h2>
                <p>
                    Your information may be transferred to and processed in countries other than your country of residence. These countries may have different data protection laws. We ensure appropriate safeguards are in place to protect your information in accordance with this Privacy Policy.
                </p>
                
                <h2>10. Children's Privacy</h2>
                <p>
                    Our website is not intended for children under the age of 18. We do not knowingly collect personal information from children. If you are a parent or guardian and believe your child has provided us with personal information, please contact us.
                </p>
                
                <h2>11. Third-Party Links</h2>
                <p>
                    Our website may contain links to third-party websites. We are not responsible for the privacy practices of these websites. We encourage you to read the privacy policies of any third-party sites you visit.
                </p>
                
                <h2>12. Changes to This Privacy Policy</h2>
                <p>
                    We may update this Privacy Policy from time to time. We will notify you of any changes by posting the new Privacy Policy on this page and updating the "Last Updated" date. We encourage you to review this Privacy Policy periodically.
                </p>
                
                <h2>13. California Privacy Rights</h2>
                <p>
                    If you are a California resident, you have specific rights under the California Consumer Privacy Act (CCPA), including the right to know what personal information we collect, the right to delete your information, and the right to opt-out of the sale of your information (we do not sell personal information).
                </p>
                
                <h2>14. GDPR Compliance</h2>
                <p>
                    If you are a resident of the European Economic Area (EEA), you have certain data protection rights under the General Data Protection Regulation (GDPR). We process your personal data based on legal grounds such as consent, contract performance, or legitimate interests.
                </p>
                
                <div class="contact-box">
                    <h3><i class="fas fa-envelope"></i> Privacy Questions or Concerns?</h3>
                    <p>If you have questions about this Privacy Policy or wish to exercise your privacy rights, please contact us:</p>
                    <p><strong>Email:</strong> <a href="mailto:privacy@auravibe.com">privacy@auravibe.com</a></p>
                    <p><strong>Phone:</strong> +1 (555) 123-4567</p>
                    <p><strong>Address:</strong> Aura Vibe, Privacy Department</p>
                </div>
            </div>
        </div>
    </section>
    
    <?php include_view('footer.php'); ?>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
