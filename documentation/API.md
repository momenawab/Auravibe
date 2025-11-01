# AuraVibe API Documentation

This document provides detailed information about the AuraVibe REST API endpoints.

## Table of Contents

- [Authentication](#authentication)
- [Response Format](#response-format)
- [Error Handling](#error-handling)
- [Cart API](#cart-api)
- [Product API](#product-api)
- [Order API](#order-api)
- [User API](#user-api)

## Authentication

Most API endpoints use PHP session-based authentication. Ensure that:
- Cookies are enabled
- Session is active
- CSRF tokens are included (where applicable)

### Session Management

Sessions are automatically managed by PHP. No additional headers required for basic operations.

## Response Format

All API responses are in JSON format.

### Success Response

```json
{
  "success": true,
  "message": "Operation completed successfully",
  "data": {
    // Response data
  }
}
```

### Error Response

```json
{
  "success": false,
  "message": "Error message describing what went wrong",
  "errors": {
    // Validation errors (optional)
  }
}
```

## Error Handling

### HTTP Status Codes

- `200 OK` - Request successful
- `400 Bad Request` - Invalid parameters
- `401 Unauthorized` - Authentication required
- `404 Not Found` - Resource not found
- `500 Internal Server Error` - Server error

### Error Messages

Errors are returned in both English and Arabic based on current language setting.

## Cart API

### Add to Cart

Add a product to the shopping cart.

**Endpoint:** `POST /api/cart-add.php`

**Request Headers:**
```
Content-Type: application/json
```

**Request Body:**
```json
{
  "product_id": 123,
  "quantity": 2
}
```

**Parameters:**

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| product_id | integer | Yes | ID of the product to add |
| quantity | integer | Yes | Quantity to add (must be > 0) |

**Success Response (200):**
```json
{
  "success": true,
  "message": "Titanium Sport Pro added to cart",
  "cart_count": 5,
  "cart": [
    {
      "id": 123,
      "name": "Titanium Sport Pro",
      "name_ar": null,
      "brand": "SPORT MAX",
      "price": 2299.00,
      "quantity": 2,
      "image": "uploads/products/watch1.jpg"
    }
  ]
}
```

**Error Responses:**

Invalid Product ID (400):
```json
{
  "success": false,
  "message": "Invalid product ID"
}
```

Product Not Found (404):
```json
{
  "success": false,
  "message": "Product not found"
}
```

Out of Stock (400):
```json
{
  "success": false,
  "message": "Product out of stock"
}
```

Exceeds Available Stock (400):
```json
{
  "success": false,
  "message": "Requested quantity exceeds available stock"
}
```

**cURL Example:**
```bash
curl -X POST http://localhost/api/cart-add.php \
  -H "Content-Type: application/json" \
  -d '{"product_id": 123, "quantity": 2}' \
  --cookie-jar cookies.txt \
  --cookie cookies.txt
```

**JavaScript Example:**
```javascript
fetch('/api/cart-add.php', {
  method: 'POST',
  headers: {
    'Content-Type': 'application/json'
  },
  body: JSON.stringify({
    product_id: 123,
    quantity: 2
  })
})
.then(response => response.json())
.then(data => {
  if (data.success) {
    console.log('Added to cart:', data.cart_count);
  } else {
    console.error('Error:', data.message);
  }
});
```

---

### Update Cart Quantity

Update the quantity of an item in the cart.

**Endpoint:** `POST /api/cart-update.php`

**Request Headers:**
```
Content-Type: application/x-www-form-urlencoded
```

**Request Body:**
```
action=update_quantity
index=0
change=1
```

**Parameters:**

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| action | string | Yes | Must be "update_quantity" |
| index | integer | Yes | Index of cart item (0-based) |
| change | integer | Yes | Amount to change (+1, -1, etc.) |

**Success Response (200):**
```json
{
  "success": true,
  "message": "Quantity updated",
  "cart": [
    {
      "id": 123,
      "name": "Titanium Sport Pro",
      "quantity": 3,
      "price": 2299.00
    }
  ]
}
```

**Error Response (400):**
```json
{
  "success": false,
  "message": "Invalid cart index"
}
```

**cURL Example:**
```bash
curl -X POST http://localhost/api/cart-update.php \
  -d "action=update_quantity&index=0&change=1" \
  --cookie-jar cookies.txt \
  --cookie cookies.txt
```

**JavaScript Example:**
```javascript
const formData = new FormData();
formData.append('action', 'update_quantity');
formData.append('index', 0);
formData.append('change', 1);

fetch('/api/cart-update.php', {
  method: 'POST',
  body: formData
})
.then(response => response.json())
.then(data => {
  if (data.success) {
    location.reload();
  }
});
```

---

### Remove from Cart

Remove an item from the shopping cart.

**Endpoint:** `POST /api/cart-update.php`

**Request Headers:**
```
Content-Type: application/x-www-form-urlencoded
```

**Request Body:**
```
action=remove
index=0
```

**Parameters:**

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| action | string | Yes | Must be "remove" |
| index | integer | Yes | Index of cart item to remove (0-based) |

**Success Response (200):**
```json
{
  "success": true,
  "message": "Item removed from cart"
}
```

**Error Response (400):**
```json
{
  "success": false,
  "message": "Invalid cart index"
}
```

**cURL Example:**
```bash
curl -X POST http://localhost/api/cart-update.php \
  -d "action=remove&index=0" \
  --cookie-jar cookies.txt \
  --cookie cookies.txt
```

**JavaScript Example:**
```javascript
const formData = new FormData();
formData.append('action', 'remove');
formData.append('index', 0);

fetch('/api/cart-update.php', {
  method: 'POST',
  body: formData
})
.then(response => response.json())
.then(data => {
  if (data.success) {
    location.reload();
  }
});
```

---

### Get Cart Contents

Get the current cart contents from the session.

**Method:** Access via PHP session

```php
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

$cart_items = $_SESSION['cart'];
$cart_count = 0;
foreach ($cart_items as $item) {
    $cart_count += $item['quantity'];
}
```

**Cart Item Structure:**
```php
[
    'id' => 123,                          // Product ID
    'name' => 'Titanium Sport Pro',       // Product name
    'name_ar' => 'تيتانيوم سبورت برو',    // Arabic name (optional)
    'brand' => 'SPORT MAX',               // Brand name
    'price' => 2299.00,                   // Unit price
    'quantity' => 2,                      // Quantity in cart
    'image' => 'uploads/products/...'     // Image path
]
```

---

## Product API

### Get Product Details

While not a dedicated API endpoint, products are accessed via standard PHP pages.

**Get Single Product:**
```
GET /product.php?id=123
```

**Get Products List:**
```
GET /shop.php?category=watches&sort=price_asc&search=titanium
```

**Query Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| category | string | Filter by category |
| sort | string | Sort order (price_asc, price_desc, name_asc, name_desc) |
| search | string | Search query |
| min_price | float | Minimum price filter |
| max_price | float | Maximum price filter |

---

## Order API

### Create Order

Orders are created through the checkout process.

**Endpoint:** `POST /process-order.php`

**Request Body (Form Data):**
```
first_name=John
last_name=Doe
email=john@example.com
phone=+201234567890
address=123 Main St
city=Cairo
governorate=Cairo
postal_code=12345
payment_method=card
```

**Required Fields:**

| Field | Type | Description |
|-------|------|-------------|
| first_name | string | Customer first name |
| last_name | string | Customer last name |
| email | string | Customer email |
| phone | string | Customer phone |
| address | string | Shipping address |
| city | string | City |
| governorate | string | Governorate/State |
| postal_code | string | Postal code |
| payment_method | string | Payment method (card, wallet, cash) |

**Response:**

Redirects to payment gateway or order success page.

---

### Get Order Status

**Endpoint:** `GET /account.php?section=orders`

Requires user authentication.

---

## User API

### Register

**Endpoint:** `POST /register.php`

**Request Body (Form Data):**
```
name=John Doe
email=john@example.com
phone=+201234567890
password=SecurePass123
confirm_password=SecurePass123
```

**Success:**
- Creates user account
- Redirects to login page

---

### Login

**Endpoint:** `POST /login.php`

**Request Body (Form Data):**
```
email=john@example.com
password=SecurePass123
```

**Success:**
- Creates session
- Redirects to account page or previous page

---

### Logout

**Endpoint:** `GET /logout.php`

Destroys session and redirects to homepage.

---

## Payment Gateway Integration

### Paymob Payment Flow

1. **Create Order:** Submit checkout form
2. **Get Payment Key:** Server creates payment intention with Paymob
3. **Redirect to Paymob:** User redirected to payment page
4. **Payment Processing:** User completes payment
5. **Callback:** Paymob sends callback to server
6. **Verification:** Server verifies HMAC signature
7. **Order Update:** Order status updated
8. **Redirect:** User redirected to success/failure page

### Paymob Callback

**Endpoint:** `POST /paymob-callback.php`

**Request Body:**
Paymob sends transaction data with HMAC signature.

**Verification:**
```php
$hmac_secret = getenv('PAYMOB_HMAC_SECRET');
$calculated_hmac = hash_hmac('sha512', $concatenated_string, $hmac_secret);

if ($calculated_hmac === $received_hmac) {
    // Valid transaction
}
```

---

## Rate Limiting

Currently no rate limiting is implemented. Consider adding:

- Maximum requests per IP per minute
- Maximum failed login attempts
- Cart manipulation limits

## Webhook Events

Future implementations may include webhooks for:

- Order status changes
- Payment confirmations
- Stock updates
- Customer notifications

## API Versioning

Current API version: **v1**

Future versions will be prefixed: `/api/v2/endpoint`

---

## Development Tools

### Postman Collection

A Postman collection is available for testing API endpoints:

1. Import collection from `docs/postman_collection.json`
2. Set environment variables:
   - `base_url`: http://localhost
   - `session_cookie`: Obtained after login

### Testing

Example test script:

```bash
#!/bin/bash

# Test add to cart
curl -X POST http://localhost/api/cart-add.php \
  -H "Content-Type: application/json" \
  -d '{"product_id": 1, "quantity": 2}' \
  -c cookies.txt \
  -b cookies.txt

# Test update quantity
curl -X POST http://localhost/api/cart-update.php \
  -d "action=update_quantity&index=0&change=1" \
  -b cookies.txt

# Test remove from cart
curl -X POST http://localhost/api/cart-update.php \
  -d "action=remove&index=0" \
  -b cookies.txt
```

---

## Support

For API questions and issues:
- Email: api-support@auravibe.com
- GitHub Issues: [Report an issue](https://github.com/yourusername/auravibe/issues)

---

**Last Updated:** 2024-10-30
**API Version:** 1.0.0
