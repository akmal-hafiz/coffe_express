# 🗺️ GOOGLE MAPS API KEY SETUP

## Panduan Lengkap Mendapatkan API Key Gratis

---

## 📋 LANGKAH-LANGKAH:

### **STEP 1: Buka Google Cloud Console**

1. Buka browser
2. Go to: **https://console.cloud.google.com/**
3. Login dengan Google Account Anda

---

### **STEP 2: Create New Project**

1. Klik **"Select a project"** (pojok kiri atas, sebelah logo Google Cloud)
2. Klik **"NEW PROJECT"**
3. Isi form:
   ```
   Project name: Coffee Express
   Organization: (leave default)
   Location: (leave default)
   ```
4. Klik **"CREATE"**
5. Wait ~30 seconds untuk project dibuat
6. Pastikan project "Coffee Express" sudah selected

---

### **STEP 3: Enable APIs (3 APIs Required)**

#### **A. Enable Maps JavaScript API**

1. Di sidebar kiri, klik **"APIs & Services"** → **"Library"**
2. Di search box, ketik: `Maps JavaScript API`
3. Klik pada **"Maps JavaScript API"**
4. Klik tombol **"ENABLE"**
5. Wait sampai enabled (hijau)

#### **B. Enable Places API**

1. Klik **"Library"** lagi (di sidebar)
2. Search: `Places API`
3. Klik pada **"Places API"**
4. Klik **"ENABLE"**

#### **C. Enable Geocoding API**

1. Klik **"Library"** lagi
2. Search: `Geocoding API`
3. Klik pada **"Geocoding API"**
4. Klik **"ENABLE"**

---

### **STEP 4: Create API Key**

1. Di sidebar, klik **"Credentials"**
2. Klik tombol **"+ CREATE CREDENTIALS"** (di atas)
3. Select **"API key"**
4. API key akan muncul di popup
5. **COPY KEY INI!** (Contoh: `AIzaSyAbc123...`)
6. Jangan close popup dulu!

---

### **STEP 5: Restrict API Key (PENTING!)**

**Jangan skip step ini! Untuk keamanan!**

1. Di popup API key, klik **"EDIT API KEY"**
2. Atau klik nama API key di list

#### **Set Application Restrictions:**
1. Under "Application restrictions"
2. Select: **"HTTP referrers (web sites)"**
3. Klik **"ADD AN ITEM"**
4. Masukkan: `http://localhost:8000/*`
5. Klik **"ADD AN ITEM"** lagi
6. Masukkan: `http://127.0.0.1:8000/*`
7. (Optional) Tambahkan domain production nanti

#### **Set API Restrictions:**
1. Under "API restrictions"
2. Select: **"Restrict key"**
3. Dari dropdown, pilih:
   - ✅ **Maps JavaScript API**
   - ✅ **Places API**
   - ✅ **Geocoding API**

4. Klik **"SAVE"** (di bawah)

---

### **STEP 6: Update Code**

1. Buka file: `resources/views/checkout.blade.php`
2. Cari line ~27:
   ```html
   <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY_HERE&libraries=places"></script>
   ```
3. Replace `YOUR_API_KEY_HERE` dengan API key Anda:
   ```html
   <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAbc123YourActualKey&libraries=places"></script>
   ```
4. Save file

---

### **STEP 7: Test**

1. Refresh browser (Ctrl + F5)
2. Go to: `http://localhost:8000/checkout`
3. Select **"Delivery"** option
4. **Map should load!** 🎉

---

## ✅ VERIFICATION CHECKLIST

Pastikan semua sudah benar:

- [ ] Project "Coffee Express" created
- [ ] Maps JavaScript API enabled (hijau)
- [ ] Places API enabled (hijau)
- [ ] Geocoding API enabled (hijau)
- [ ] API Key created
- [ ] HTTP referrers added (localhost:8000)
- [ ] API restrictions set (3 APIs)
- [ ] API key saved
- [ ] Code updated with new key
- [ ] Browser refreshed
- [ ] Map loads without error

---

## 🎨 VISUAL GUIDE

### **What You Should See:**

#### **1. After Enabling APIs:**
```
✅ Maps JavaScript API - ENABLED
✅ Places API - ENABLED
✅ Geocoding API - ENABLED
```

#### **2. API Key Restrictions:**
```
Application restrictions:
  HTTP referrers
    http://localhost:8000/*
    http://127.0.0.1:8000/*

API restrictions:
  Restrict key
    Maps JavaScript API
    Places API
    Geocoding API
```

#### **3. Working Map:**
```
[Delivery Option Selected]
├── Address Input (with autocomplete)
├── Info Box: "Klik dan drag pin merah..."
├── Google Map (400px height)
│   └── Red Draggable Marker
└── Selected Address Display
```

---

## 💰 PRICING INFO

### **Free Tier (Monthly):**
- $200 credit per month
- ~28,000 map loads (free)
- ~28,000 autocomplete requests (free)
- ~40,000 geocoding requests (free)

### **For Small Coffee Shop:**
- Estimated: **$0/month** (within free tier)
- No credit card required for testing
- Credit card required for production

---

## 🐛 TROUBLESHOOTING

### **Error: "This API project is not authorized to use this API"**

**Solution:**
- Make sure you enabled all 3 APIs
- Wait 5 minutes after enabling
- Refresh browser cache

### **Error: "RefererNotAllowedMapError"**

**Solution:**
- Check HTTP referrers includes `localhost:8000`
- Make sure format is: `http://localhost:8000/*` (with /*)
- Save restrictions and wait 5 minutes

### **Error: "ApiNotActivatedMapError"**

**Solution:**
- Enable Maps JavaScript API
- Enable Places API
- Enable Geocoding API
- Wait 5 minutes

### **Map Still Not Loading?**

1. **Clear browser cache:**
   ```
   Ctrl + Shift + Delete
   Clear cached images and files
   ```

2. **Hard refresh:**
   ```
   Ctrl + F5
   ```

3. **Check console:**
   ```
   F12 → Console tab
   Look for red errors
   ```

4. **Verify API key:**
   ```
   Open in browser:
   https://maps.googleapis.com/maps/api/js?key=YOUR_KEY
   
   Should return JavaScript code
   ```

---

## 🔒 SECURITY TIPS

### **DO:**
✅ Always restrict API keys
✅ Use HTTP referrers for web apps
✅ Only enable APIs you need
✅ Monitor usage in console
✅ Set budget alerts

### **DON'T:**
❌ Share API keys publicly
❌ Commit keys to GitHub
❌ Use unrestricted keys
❌ Ignore usage alerts

---

## 📊 MONITORING USAGE

### **Check Usage:**
1. Go to Google Cloud Console
2. Click "APIs & Services" → "Dashboard"
3. See requests per day graph
4. Check quota usage

### **Set Budget Alert:**
1. Go to "Billing" → "Budgets & alerts"
2. Click "CREATE BUDGET"
3. Set amount: $10
4. Set alerts: 50%, 90%, 100%

---

## 🚀 PRODUCTION SETUP

When deploying to production:

1. **Add Production Domain:**
   ```
   https://yourdomain.com/*
   https://www.yourdomain.com/*
   ```

2. **Enable Billing:**
   - Add credit card
   - Set budget alerts
   - Monitor usage

3. **Consider Separate Keys:**
   - Development key (localhost)
   - Production key (domain)

---

## 📞 NEED HELP?

### **Google Maps Support:**
- Docs: https://developers.google.com/maps/documentation
- Stack Overflow: https://stackoverflow.com/questions/tagged/google-maps

### **Common Issues:**
- API key not working? Wait 5 minutes after creating
- Map not loading? Check console for errors
- Quota exceeded? Check billing/usage

---

## 🎯 QUICK REFERENCE

### **Required APIs:**
```
1. Maps JavaScript API
2. Places API
3. Geocoding API
```

### **Restrictions:**
```
Application: HTTP referrers
  - http://localhost:8000/*
  - http://127.0.0.1:8000/*

API: Restrict key
  - Maps JavaScript API
  - Places API
  - Geocoding API
```

### **Code Location:**
```
File: resources/views/checkout.blade.php
Line: ~27
Replace: YOUR_API_KEY_HERE
```

---

## ✨ AFTER SETUP

Once map is working, you can:

✅ Search addresses with autocomplete
✅ Drag marker to select location
✅ Click on map to move marker
✅ Use current location (with permission)
✅ See selected address below map
✅ Coordinates saved to database

---

## 🎊 DONE!

**Your Google Maps integration is now ready!**

Test it:
1. Go to checkout
2. Select "Delivery"
3. Play with the map! 🗺️

**Enjoy your interactive delivery location picker! ☕**

---

**Last Updated**: November 9, 2025
**Estimated Setup Time**: 10-15 minutes
**Cost**: FREE (within $200/month credit)
