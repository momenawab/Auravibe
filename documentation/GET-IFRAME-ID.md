# 🎯 How to Get Your Paymob iFrame ID

## Problem
Your `.env` file has the **Integration ID** in the `PAYMOB_IFRAME_ID` field, but you need the actual **iFrame ID** (they are different!).

---

## 📋 Step-by-Step Guide

### Step 1: Log in to Paymob Dashboard
1. Go to: https://accept.paymob.com/portal2/en/login
2. Enter your credentials

### Step 2: Navigate to iFrames Section
1. In the left sidebar, click on **"Developers"**
2. Click on **"iFrames"**

### Step 3: Find Your iFrame ID
You'll see a page showing your iFrames. Look for:

```
Name: [Your iFrame Name]
iFrame ID: XXXXXX  ← This is what you need!
```

**Example:**
- If you see: `iFrame ID: 876543`
- That's your actual iFrame ID (NOT the Integration ID!)

### Step 4: Copy the iFrame ID
- Copy just the **number** (e.g., `876543`)
- This is usually a 6-7 digit number

---

## 🔧 Update Your .env File

Open `/home/believer/auravibe/.env` and replace:

```env
# WRONG - This is your Integration ID, not iFrame ID
PAYMOB_IFRAME_ID=5362354

# CORRECT - Use your actual iFrame ID from dashboard
PAYMOB_IFRAME_ID=876543  ← Replace with YOUR iFrame ID
```

---

## 📊 Understanding the Difference

| Field | Purpose | Where to Find | Example |
|-------|---------|---------------|---------|
| **Integration ID** | Identifies payment method (Card/Wallet) | Developers → Integrations | 5362354 |
| **iFrame ID** | Identifies payment page display | Developers → iFrames | 876543 |
| **API Key** | Authenticates your account | Settings → Account Info | ZXlKaG... |

You need **BOTH** - they serve different purposes!

---

## ✅ Current Status

Your Integration IDs are correct:
- ✓ PAYMOB_INTEGRATION_ONLINE_CARD=5362354
- ✓ PAYMOB_INTEGRATION_TAP_ON_PHONE=5362355
- ✓ PAYMOB_INTEGRATION_MOBILE_WALLET=5362356

What needs to be updated:
- ❌ PAYMOB_IFRAME_ID - Currently using Integration ID instead of iFrame ID

---

## 🧪 After Updating

1. Save your `.env` file
2. Restart your server:
   ```bash
   cd /home/believer/auravibe
   ./start.sh
   ```

3. Test checkout - you should see the Paymob payment page!

---

## 📞 Still Having Issues?

If you can't find your iFrame ID:

1. **Check if you have an iFrame created:**
   - Go to Developers → iFrames
   - If empty, click "Create New iFrame"
   - Select your integration
   - Copy the new iFrame ID

2. **Contact Paymob Support:**
   - Live chat in dashboard
   - Email: support@paymob.com
   - Ask: "What is my iFrame ID for displaying payment page?"

3. **Check API Response:**
   - Your API Key is working (authentication succeeds)
   - Orders are being created (you see them in dashboard)
   - Just need the correct iFrame ID to display payment page

---

## 🎉 What Happens After Fix

When you use the correct iFrame ID:

1. Customer clicks "Pay Now" ✓
2. Order is created in Paymob ✓ (already working!)
3. Payment page displays in iFrame ✓ (will work after fix!)
4. Customer enters card details ✓
5. Payment processes ✓
6. Customer redirects back to your site ✓

**You're almost there!** Just need the correct iFrame ID! 🚀
