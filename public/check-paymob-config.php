<?php
/**
 * Paymob Configuration Checker
 * 
 * This script verifies your Paymob configuration in .env file
 */

require_once __DIR__ . '/config-paths.php';

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paymob Configuration Check</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background: #f5f5f5;
        }
        .card {
            background: white;
            border-radius: 8px;
            padding: 30px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        h1 {
            color: #333;
            margin-top: 0;
        }
        .check-item {
            padding: 15px;
            margin: 10px 0;
            border-radius: 4px;
            display: flex;
            align-items: center;
            gap: 15px;
        }
        .check-success {
            background: #d4edda;
            border: 1px solid #c3e6cb;
            color: #155724;
        }
        .check-warning {
            background: #fff3cd;
            border: 1px solid #ffeeba;
            color: #856404;
        }
        .check-error {
            background: #f8d7da;
            border: 1px solid #f5c6cb;
            color: #721c24;
        }
        .icon {
            font-size: 24px;
            font-weight: bold;
        }
        .demo-warning {
            background: #d1ecf1;
            border: 1px solid #bee5eb;
            color: #0c5460;
            padding: 20px;
            border-radius: 4px;
            margin: 20px 0;
        }
        code {
            background: #f4f4f4;
            padding: 2px 6px;
            border-radius: 3px;
            font-family: monospace;
        }
        a {
            color: #007bff;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
        .next-steps {
            background: #e7f3ff;
            padding: 20px;
            border-radius: 4px;
            border-left: 4px solid #2196F3;
        }
    </style>
</head>
<body>
    <div class="card">
        <h1>🔍 Paymob Configuration Check</h1>
        
        <?php
        // Check if constants are defined
        $hasConfig = defined('PAYMOB_API_KEY') && defined('PAYMOB_INTEGRATION_ONLINE_CARD');
        
        if (!$hasConfig):
        ?>
            <div class="check-item check-error">
                <span class="icon">✗</span>
                <div>
                    <strong>Configuration Not Loaded</strong>
                    <p>Paymob constants are not defined. Check that config/paymob.php is loaded.</p>
                </div>
            </div>
        <?php else: ?>
            
            <h2>API Configuration</h2>
            
            <!-- API Key Check -->
            <div class="check-item <?php echo (empty(PAYMOB_API_KEY) || PAYMOB_API_KEY === 'your_api_key_here') ? 'check-error' : 'check-success'; ?>">
                <span class="icon"><?php echo (empty(PAYMOB_API_KEY) || PAYMOB_API_KEY === 'your_api_key_here') ? '✗' : '✓'; ?></span>
                <div>
                    <strong>API Key</strong>
                    <p>
                        <?php if (empty(PAYMOB_API_KEY) || PAYMOB_API_KEY === 'your_api_key_here'): ?>
                            ❌ Not configured or using placeholder value
                        <?php else: ?>
                            ✓ Configured (<?php echo substr(PAYMOB_API_KEY, 0, 20) . '...'; ?>)
                        <?php endif; ?>
                    </p>
                </div>
            </div>
            
            <!-- iFrame ID Check -->
            <?php 
            $iframeIdMatchesIntegration = (PAYMOB_IFRAME_ID == PAYMOB_INTEGRATION_ONLINE_CARD) || 
                                          (PAYMOB_IFRAME_ID == PAYMOB_INTEGRATION_TAP_ON_PHONE) || 
                                          (PAYMOB_IFRAME_ID == PAYMOB_INTEGRATION_MOBILE_WALLET);
            $iframeStatus = (empty(PAYMOB_IFRAME_ID) || PAYMOB_IFRAME_ID === 'your_iframe_id_here' || $iframeIdMatchesIntegration) ? 'check-error' : 'check-success';
            ?>
            <div class="check-item <?php echo $iframeStatus; ?>">
                <span class="icon"><?php echo ($iframeStatus === 'check-error') ? '✗' : '✓'; ?></span>
                <div>
                    <strong>iFrame ID</strong>
                    <p>
                        <?php if (empty(PAYMOB_IFRAME_ID) || PAYMOB_IFRAME_ID === 'your_iframe_id_here'): ?>
                            ❌ Not configured
                        <?php elseif ($iframeIdMatchesIntegration): ?>
                            ❌ Using Integration ID (<?php echo PAYMOB_IFRAME_ID; ?>) instead of iFrame ID!
                            <br><span style="color: #d9534f;"><strong>ERROR:</strong> iFrame ID and Integration ID are different!</span>
                            <br><small>Go to Paymob Dashboard → Developers → iFrames to get your actual iFrame ID</small>
                        <?php else: ?>
                            ✓ Configured: <?php echo PAYMOB_IFRAME_ID; ?>
                        <?php endif; ?>
                    </p>
                </div>
            </div>
            
            <!-- HMAC Secret Check -->
            <div class="check-item <?php echo (empty(PAYMOB_HMAC_SECRET) || PAYMOB_HMAC_SECRET === 'your_hmac_secret_here') ? 'check-warning' : 'check-success'; ?>">
                <span class="icon"><?php echo (empty(PAYMOB_HMAC_SECRET) || PAYMOB_HMAC_SECRET === 'your_hmac_secret_here') ? '⚠' : '✓'; ?></span>
                <div>
                    <strong>HMAC Secret</strong>
                    <p>
                        <?php if (empty(PAYMOB_HMAC_SECRET) || PAYMOB_HMAC_SECRET === 'your_hmac_secret_here'): ?>
                            ⚠ Not configured (needed for callback verification)
                        <?php else: ?>
                            ✓ Configured
                        <?php endif; ?>
                    </p>
                </div>
            </div>
            
            <h2>Integration IDs</h2>
            
            <?php
            $demoIds = [5362354, 5362355, 5362356];
            $hasDemoIds = in_array(PAYMOB_INTEGRATION_ONLINE_CARD, $demoIds) ||
                          in_array(PAYMOB_INTEGRATION_TAP_ON_PHONE, $demoIds) ||
                          in_array(PAYMOB_INTEGRATION_MOBILE_WALLET, $demoIds);
            
            if ($hasDemoIds):
            ?>
                <div class="demo-warning">
                    <h3 style="margin-top:0;">⚠️ WARNING: Using Demo Integration IDs</h3>
                    <p>You are using example/demo Integration IDs. These will NOT work with Paymob.</p>
                    <p><strong>You MUST replace them with your actual Integration IDs from your Paymob dashboard.</strong></p>
                </div>
            <?php endif; ?>
            
            <!-- Online Card Integration -->
            <div class="check-item <?php echo in_array(PAYMOB_INTEGRATION_ONLINE_CARD, $demoIds) ? 'check-error' : 'check-success'; ?>">
                <span class="icon"><?php echo in_array(PAYMOB_INTEGRATION_ONLINE_CARD, $demoIds) ? '✗' : '✓'; ?></span>
                <div>
                    <strong>Online Card Integration</strong>
                    <p>
                        ID: <code><?php echo PAYMOB_INTEGRATION_ONLINE_CARD; ?></code>
                        <?php if (in_array(PAYMOB_INTEGRATION_ONLINE_CARD, $demoIds)): ?>
                            <br><span style="color: #d9534f;">❌ This is a DEMO ID - won't work!</span>
                        <?php endif; ?>
                    </p>
                </div>
            </div>
            
            <!-- Mobile Wallet Integration -->
            <div class="check-item <?php echo in_array(PAYMOB_INTEGRATION_MOBILE_WALLET, $demoIds) ? 'check-error' : 'check-success'; ?>">
                <span class="icon"><?php echo in_array(PAYMOB_INTEGRATION_MOBILE_WALLET, $demoIds) ? '✗' : '✓'; ?></span>
                <div>
                    <strong>Mobile Wallet Integration</strong>
                    <p>
                        ID: <code><?php echo PAYMOB_INTEGRATION_MOBILE_WALLET; ?></code>
                        <?php if (in_array(PAYMOB_INTEGRATION_MOBILE_WALLET, $demoIds)): ?>
                            <br><span style="color: #d9534f;">❌ This is a DEMO ID - won't work!</span>
                        <?php endif; ?>
                    </p>
                </div>
            </div>
            
            <!-- Tap on Phone Integration -->
            <div class="check-item <?php echo in_array(PAYMOB_INTEGRATION_TAP_ON_PHONE, $demoIds) ? 'check-error' : 'check-success'; ?>">
                <span class="icon"><?php echo in_array(PAYMOB_INTEGRATION_TAP_ON_PHONE, $demoIds) ? '✗' : '✓'; ?></span>
                <div>
                    <strong>Tap on Phone Integration</strong>
                    <p>
                        ID: <code><?php echo PAYMOB_INTEGRATION_TAP_ON_PHONE; ?></code>
                        <?php if (in_array(PAYMOB_INTEGRATION_TAP_ON_PHONE, $demoIds)): ?>
                            <br><span style="color: #d9534f;">❌ This is a DEMO ID - won't work!</span>
                        <?php endif; ?>
                    </p>
                </div>
            </div>
            
        <?php endif; ?>
        
        <div class="next-steps">
            <h2 style="margin-top: 0;">📋 Next Steps</h2>
            
            <?php if ($hasDemoIds || empty(PAYMOB_API_KEY) || PAYMOB_API_KEY === 'your_api_key_here'): ?>
                <ol>
                    <li>Log in to <a href="https://accept.paymob.com/portal2/en/login" target="_blank">Paymob Dashboard</a></li>
                    <li>Go to <strong>Developers</strong> → <strong>Integrations</strong></li>
                    <li>Copy your <strong>Integration IDs</strong> for each payment method</li>
                    <li>Go to <strong>Settings</strong> → <strong>Account Info</strong></li>
                    <li>Copy your <strong>API Key</strong> and <strong>HMAC Secret</strong></li>
                    <li>Update your <code>.env</code> file with the real values</li>
                    <li>Refresh this page to verify</li>
                </ol>
                
                <p><strong>📖 See detailed guide:</strong> <a href="PAYMOB-INTEGRATION-FIX.md" target="_blank">PAYMOB-INTEGRATION-FIX.md</a></p>
            <?php else: ?>
                <p><strong>✅ Configuration looks good!</strong></p>
                <p>Try processing a test order to verify everything works.</p>
                <p>Test card: <code>4987654321098769</code> (any CVV, future expiry)</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
