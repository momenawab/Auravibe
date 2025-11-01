<?php require_once __DIR__ . '/config-paths.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <title>Asset Path Test</title>
    <style>
        body { font-family: Arial; padding: 20px; }
        .test { margin: 10px 0; padding: 10px; background: #f0f0f0; }
        .success { color: green; }
        .error { color: red; }
    </style>
</head>
<body>
    <h1>Asset Path Test</h1>

    <div class="test">
        <strong>Server Info:</strong><br>
        SCRIPT_NAME: <?php echo $_SERVER['SCRIPT_NAME']; ?><br>
        Document Root: <?php echo $_SERVER['DOCUMENT_ROOT']; ?><br>
        Current Dir: <?php echo __DIR__; ?><br>
        ROOT constant: <?php echo ROOT; ?><br>
    </div>

    <div class="test">
        <strong>Base URL Function Test:</strong><br>
        <?php if (function_exists('base_url')): ?>
            <span class="success">✓ base_url() function exists</span><br>
            base_url('assets/css/style.css'): <?php echo base_url('assets/css/style.css'); ?><br>
            base_url('index.php'): <?php echo base_url('index.php'); ?><br>
        <?php else: ?>
            <span class="error">✗ base_url() function not found</span>
        <?php endif; ?>
    </div>

    <div class="test">
        <strong>File Existence Test:</strong><br>
        <?php
        $files = [
            'assets/css/style.css',
            'assets/css/enhancements.css',
            'assets/js/main.js',
            'assets/images/logo.svg'
        ];

        foreach ($files as $file) {
            $fullPath = __DIR__ . '/' . $file;
            $exists = file_exists($fullPath);
            $class = $exists ? 'success' : 'error';
            $symbol = $exists ? '✓' : '✗';
            echo "<span class='$class'>$symbol $file</span> (Full path: $fullPath)<br>";
        }
        ?>
    </div>

    <div class="test">
        <strong>Image Test:</strong><br>
        <img src="assets/images/logo.svg" alt="Logo Test" style="max-width: 200px; border: 1px solid #ccc;">
        <br>Image path: assets/images/logo.svg
    </div>

    <div class="test">
        <strong>CSS Test:</strong><br>
        <link rel="stylesheet" href="assets/css/style.css">
        <link rel="stylesheet" href="<?php echo base_url('assets/css/style.css'); ?>">
        Check browser console for any 404 errors.
    </div>

    <div class="test">
        <strong>Includes Test:</strong><br>
        INCLUDES_PATH: <?php echo INCLUDES_PATH; ?><br>
        <?php
        $includeFiles = ['header.php', 'navbar.php', 'footer.php', 'functions.php'];
        foreach ($includeFiles as $file) {
            $fullPath = INCLUDES_PATH . $file;
            $exists = file_exists($fullPath);
            $class = $exists ? 'success' : 'error';
            $symbol = $exists ? '✓' : '✗';
            echo "<span class='$class'>$symbol $file</span><br>";
        }
        ?>
    </div>

    <div class="test">
        <strong>Database Test:</strong><br>
        <?php if (defined('DB_CONNECTED')): ?>
            <span class="<?php echo DB_CONNECTED ? 'success' : 'error'; ?>">
                <?php echo DB_CONNECTED ? '✓ Database connected' : '✗ Database not connected'; ?>
            </span>
        <?php else: ?>
            <span class="error">✗ DB_CONNECTED not defined</span>
        <?php endif; ?>
    </div>

    <div class="test">
        <strong>Environment Test:</strong><br>
        <?php if (function_exists('env')): ?>
            <span class="success">✓ env() function exists</span><br>
            APP_NAME: <?php echo env('APP_NAME', 'Not set'); ?><br>
            DB_HOST: <?php echo env('DB_HOST', 'Not set'); ?><br>
            PAYMOB_CURRENCY: <?php echo env('PAYMOB_CURRENCY', 'Not set'); ?><br>
        <?php else: ?>
            <span class="error">✗ env() function not found</span>
        <?php endif; ?>
    </div>

    <hr>
    <p><a href="index.php">Back to Home</a></p>
</body>
</html>
