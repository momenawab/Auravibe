<?php
// Load functions if not already loaded
if (!function_exists('base_url')) {
    require_once __DIR__ . '/functions.php';
}
?>
<!DOCTYPE html>
<html lang="<?php echo get_current_language(); ?>" dir="<?php echo get_language_direction(); ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Aura Vibe - Premium Luxury Watches Collection">
    <meta name="keywords" content="watches, luxury watches, premium watches, timepieces">
    <title><?php echo isset($page_title) ? $page_title . ' - ' : ''; ?>Aura Vibe | <?php echo __('luxury_watches'); ?></title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="<?php echo base_url('assets/images/favicon.png'); ?>">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700;800&family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Arabic Fonts -->
    <?php if (is_rtl()): ?>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700;800&family=Tajawal:wght@300;400;500;700&display=swap" rel="stylesheet">
    <?php endif; ?>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Main CSS -->
    <link rel="stylesheet" href="<?php echo base_url('assets/css/style.css'); ?>?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="<?php echo base_url('assets/css/enhancements.css'); ?>?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="<?php echo base_url('assets/css/pages.css'); ?>?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="<?php echo base_url('assets/css/contact.css'); ?>?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="<?php echo base_url('assets/css/about.css'); ?>?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="<?php echo base_url('assets/css/utilities.css'); ?>?v=<?php echo time(); ?>">

    <!-- RTL CSS for Arabic -->
    <?php if (is_rtl()): ?>
    <link rel="stylesheet" href="<?php echo base_url('assets/css/rtl.css'); ?>?v=<?php echo time(); ?>">
    <?php endif; ?>
</head>
<body dir="<?php echo get_language_direction(); ?>">
