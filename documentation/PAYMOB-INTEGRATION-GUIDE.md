# Paymob Payment Gateway Integration Guide

This guide will help you set up and configure Paymob payment gateway integration for your e-commerce website.

## Prerequisites

- PHP 7.4 or higher
- MySQL database
- Paymob merchant account
- cURL enabled in PHP

## Your Current Paymob Integrations

You have the following integrations set up in your Paymob account:

1. **Online Card Payment (MIGS-online)**
   - Integration ID: `5362354`
   - Currency: EGP
   - Status: Live

2. **Tap on Phone (MIGS-tap_on_phone)**
   - Integration ID: `5362355`
   - Currency: EGP
   - Status: Live

3. **Mobile Wallet (UIG-in_store)**
   - Integration ID: `5362356`
   - Currency: EGP
   - Status: Live

## Installation Steps

### 1. Database Setup

Run the SQL file to create the necessary database tables:

```bash
mysql -u your_username -p your_database < paymob-database.sql
```

Or import it via phpMyAdmin.

This will create the following tables:
- `orders` - Stores order information
- `order_items` - Stores individual items in each order
- `paymob_transactions` - Logs all Paymob transactions for debugging

### 2. Configure Paymob Credentials

Edit `includes/paymob-config.php` and add your Paymob credentials:

```php
// Replace these with your actual Paymob credentials
define('PAYMOB_API_KEY', 'YOUR_API_KEY_HERE');
define('PAYMOB_IFRAME_ID', 'YOUR_IFRAME_ID_HERE');
define('PAYMOB_HMAC_SECRET', 'YOUR_HMAC_SECRET_HERE');
```

#### Where to Find Your Credentials:

1. **API Key**:
   - Log in to your Paymob dashboard
   - Go to Settings → Account Info
   - Copy your API Key

2. **iFrame ID**:
   - Go to Developers → iFrames
   - Copy the iFrame ID for the integration you want to use

3. **HMAC Secret**:
   - Go to Developers → HMAC
   - Copy your HMAC secret (used to verify callbacks)

### 3. Set Up Callback URLs in Paymob Dashboard

You need to register these URLs in your Paymob dashboard:

1. **Transaction Processed Callback**:
   ```
   https://yourdomain.com/paymob-callback.php
   ```

2. **Transaction Response Callback**:
   ```
   https://yourdomain.com/paymob-callback.php
   ```

To set this up:
- Log in to Paymob dashboard
- Go to Settings → Integration Settings
- Add the callback URL for each integration

### 4. Update Checkout Form (Already Done)

The checkout page has been updated to include payment method selection with:
- Online Card Payment (Credit/Debit cards)
- Mobile Wallet
- Cash on Delivery

### 5. Test the Integration

Before going live, test the integration:

1. Make a test purchase on your website
2. Select "Credit/Debit Card" as payment method
3. Complete the checkout form
4. You'll be redirected to Paymob's payment page
5. Use Paymob test cards for testing:
   - **Success**: Card Number: `4987654321098769`, CVV: `123`, Expiry: Any future date
   - **Failure**: Card Number: `4000000000000002`, CVV: `123`, Expiry: Any future date

## File Structure

```
auravibe/
├── includes/
│   ├── paymob-config.php      # Paymob configuration
│   ├── Paymob.php             # Paymob API class
│   ├── db.php                 # Database connection
│   └── ...
├── checkout.php               # Updated checkout page with payment options
├── process-order.php          # Handles order processing and payment
├── paymob-callback.php        # Handles Paymob callbacks
├── order-success.php          # Order confirmation page
└── paymob-database.sql        # Database schema
```

## How It Works

### Payment Flow:

1. **Customer fills checkout form**
   - Enters shipping and billing information
   - Selects payment method

2. **Order is created**
   - `process-order.php` creates order in database
   - Generates unique order ID

3. **Paymob payment initiation**
   - Authenticate with Paymob API (get auth token)
   - Register order with Paymob
   - Get payment key
   - Redirect customer to Paymob payment page

4. **Customer completes payment**
   - Enters card details on Paymob's secure page
   - Paymob processes the payment

5. **Callback handling**
   - Paymob sends callback to `paymob-callback.php`
   - HMAC verification ensures callback authenticity
   - Order status is updated
   - Customer is redirected to success/failure page

## API Class Usage

### Basic Example:

```php
require_once 'includes/Paymob.php';

$paymob = new Paymob();

// Prepare order data
$orderData = [
    'amount_cents' => Paymob::toCents(250.50), // EGP 250.50
    'merchant_order_id' => 'ORD-123456',
    'items' => [
        [
            'name' => 'Product Name',
            'amount_cents' => 25050,
            'description' => 'Product description',
            'quantity' => 1
        ]
    ]
];

// Prepare billing data
$billingData = [
    'email' => 'customer@example.com',
    'first_name' => 'John',
    'last_name' => 'Doe',
    'phone' => '+201234567890',
    'address' => '123 Street Name',
    'city' => 'Cairo',
    'state' => 'Cairo',
    'country' => 'EG',
    'postal_code' => '12345'
];

// Create payment and get iframe URL
$iframeUrl = $paymob->createPayment(
    $orderData,
    $billingData,
    PAYMOB_INTEGRATION_ONLINE_CARD
);

if ($iframeUrl) {
    // Redirect to payment page
    header('Location: ' . $iframeUrl);
} else {
    // Handle error
    echo "Payment initialization failed";
}
```

## Troubleshooting

### Common Issues:

1. **"Payment initialization failed"**
   - Check that your API key is correct in `paymob-config.php`
   - Verify your integration IDs are correct
   - Check error logs for detailed error messages

2. **Callback not working**
   - Ensure callback URL is registered in Paymob dashboard
   - Check HMAC secret is correct
   - Verify your server is accessible from Paymob servers

3. **Order not updating after payment**
   - Check callback URL is publicly accessible
   - Review error logs in `paymob-callback.php`
   - Verify database tables exist and have correct structure

### Enable Debug Logging:

Check PHP error logs for detailed debugging information:

```bash
tail -f /var/log/apache2/error.log
# or
tail -f /var/log/php_errors.log
```

## Security Best Practices

1. **Always verify HMAC** in callback handler to ensure requests are from Paymob
2. **Use HTTPS** for all payment-related pages
3. **Never expose API keys** in frontend code
4. **Validate all user inputs** before processing
5. **Store sensitive data securely** in database

## Currency Support

Currently configured for **EGP (Egyptian Pounds)**. All amounts must be in cents:

```php
// Convert EGP 100.50 to cents
$amountCents = Paymob::toCents(100.50); // Returns 10050
```

## Going Live Checklist

- [ ] Replace test API credentials with live credentials
- [ ] Test all payment methods (card, wallet, COD)
- [ ] Verify callback URL is working
- [ ] Enable HTTPS on your website
- [ ] Test HMAC verification
- [ ] Set up email notifications for orders
- [ ] Configure proper error handling
- [ ] Set up database backups
- [ ] Review and test refund process

## Support

For Paymob-specific issues:
- Paymob Support: support@paymob.com
- Paymob Documentation: https://docs.paymob.com

For integration issues:
- Check error logs
- Review this guide
- Test with Paymob test cards

## Additional Features

You can extend this integration with:

1. **Refund Processing**: Add refund functionality using Paymob API
2. **Email Notifications**: Send order confirmation emails
3. **Order Tracking**: Add order tracking system
4. **Invoice Generation**: Create PDF invoices
5. **Admin Panel**: Build admin interface to manage orders

## License

This integration code is provided as-is for use with your Paymob account.
