# 🔧 Paymob Integration Fix Guide

## ❌ Current Error
```
Paymob API Error: ["Invalid Payment method integration"]
```

## 🔍 Root Cause
Your `.env` file contains **DEMO integration IDs** (5362354, 5362355, 5362356) instead of your **ACTUAL** Paymob integration IDs from your account.

---

## ✅ How to Fix

### Step 1: Log in to Paymob Dashboard
1. Go to: https://accept.paymob.com/portal2/en/login
2. Log in with your Paymob account credentials

### Step 2: Get Your Integration IDs

1. In the dashboard, click **"Developers"** in the left sidebar
2. Click **"Integrations"**
3. You'll see a list of your payment methods, each with an **Integration ID**

Example:
```
Card Payment           → Integration ID: 1234567
Mobile Wallet          → Integration ID: 1234568  
Installments/Valu      → Integration ID: 1234569
```

**IMPORTANT:** These are YOUR unique IDs - not the demo ones!

### Step 3: Get Your API Key

1. In the dashboard, go to **"Settings"** → **"Account Info"**
2. Copy your **API Key** (it's a long string)

### Step 4: Get Your HMAC Secret

1. Still in **"Settings"** → **"Account Info"**
2. Scroll down to find **HMAC Secret**
3. Copy it

### Step 5: Get Your iFrame ID

1. Go to **"Developers"** → **"iFrames"**
2. You'll see your iFrame ID (usually 6-7 digits)
3. Copy it

---

## 📝 Update Your .env File

Open `/home/believer/auravibe/.env` and replace these values:

```env
# OLD (DEMO VALUES - DON'T USE THESE!)
PAYMOB_API_KEY=your_api_key_here
PAYMOB_IFRAME_ID=your_iframe_id_here
PAYMOB_HMAC_SECRET=your_hmac_secret_here

PAYMOB_INTEGRATION_ONLINE_CARD=5362354
PAYMOB_INTEGRATION_TAP_ON_PHONE=5362355
PAYMOB_INTEGRATION_MOBILE_WALLET=5362356

# NEW (YOUR ACTUAL VALUES FROM DASHBOARD)
PAYMOB_API_KEY=ZXlKaGJHY2lPaUpJVXpVeE1pSXNJblI1...  ← Your actual API key
PAYMOB_IFRAME_ID=123456                              ← Your actual iFrame ID
PAYMOB_HMAC_SECRET=A1B2C3D4E5F6...                   ← Your actual HMAC secret

# Replace with YOUR integration IDs from Paymob dashboard
PAYMOB_INTEGRATION_ONLINE_CARD=1234567               ← Your Card Payment ID
PAYMOB_INTEGRATION_TAP_ON_PHONE=1234568              ← Your Wallet ID (if you have it)
PAYMOB_INTEGRATION_MOBILE_WALLET=1234569             ← Your Mobile Wallet ID (if you have it)
```

---

## 🎯 What Each Integration ID Is For

| Integration Type | When to Use | Description |
|-----------------|-------------|-------------|
| **ONLINE_CARD** | Credit/Debit Cards | For Visa, Mastercard payments |
| **TAP_ON_PHONE** | POS Terminal | For in-person tap payments (optional) |
| **MOBILE_WALLET** | Vodafone Cash, etc. | For mobile wallet payments |

**Note:** You might not have all three. Just use the ones you have enabled in your Paymob account.

---

## 🔄 After Updating .env

1. Save the `.env` file
2. Restart your PHP server (if running):
   ```bash
   # Stop current server (Ctrl+C in terminal)
   # Then restart:
   cd /home/believer/auravibe
   ./start.sh
   ```

3. Test checkout again - it should work now! 🎉

---

## 🧪 Testing Paymob Integration

### Test Mode
Paymob has a test mode with test cards:

**Test Card Numbers:**
- **Success:** 4987654321098769
- **Declined:** 5111111111111118

**Any CVV and future expiry date**

### Live Mode
Once you're ready for production:
1. In Paymob Dashboard → Settings → Account Mode
2. Switch from **Test Mode** to **Live Mode**
3. Use the same integration IDs (they work in both modes)

---

## ⚠️ Common Mistakes

❌ **Don't use the demo IDs** (5362354, etc.) - they won't work
❌ **Don't share your API Key publicly**
❌ **Don't commit .env file to git**
✅ **Use your actual Integration IDs from YOUR Paymob dashboard**
✅ **Keep your HMAC Secret secure**

---

## 📞 Need Help?

If you can't find your Integration IDs:
1. Contact Paymob Support: support@paymob.com
2. Or use their live chat in the dashboard
3. They can help you find or create integrations

---

## ✅ Verification Checklist

After updating, verify these work:
- [ ] Authentication succeeds (you see "SUCCESS" in logs)
- [ ] Order registration succeeds (Order ID returned)
- [ ] Payment key generation succeeds (no "Invalid Payment method integration" error)
- [ ] Checkout redirects to Paymob payment page
- [ ] Test transaction completes successfully

---

🎉 **Once you add your real Integration IDs, Paymob payments will work!**
