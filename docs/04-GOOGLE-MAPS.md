# Google Maps Integration

## Overview

Aplikasi menggunakan Google Maps API untuk:
- Menampilkan lokasi toko
- Tracking delivery location
- Distance calculation

## Setup

### 1. Create Google Cloud Project
1. Buka https://console.cloud.google.com/
2. Create project baru
3. Enable Maps API:
   - Google Maps JavaScript API
   - Google Maps Geocoding API
   - Google Maps Distance Matrix API

### 2. Create API Key
1. Go to Credentials
2. Create API Key
3. Restrict key untuk Maps APIs

### 3. Configure .env
```env
GOOGLE_MAPS_API_KEY=your_api_key
STORE_LATITUDE=your_store_lat
STORE_LONGITUDE=your_store_lng
```

### 4. Add to .env.example
```env
GOOGLE_MAPS_API_KEY=
STORE_LATITUDE=
STORE_LONGITUDE=
```

---

## Usage

### Display Store Location
```blade
<div id="store-map"></div>

<script>
  const map = new google.maps.Map(document.getElementById('store-map'), {
    zoom: 15,
    center: { lat: {{ env('STORE_LATITUDE') }}, lng: {{ env('STORE_LONGITUDE') }} }
  });
  
  new google.maps.Marker({
    position: { lat: {{ env('STORE_LATITUDE') }}, lng: {{ env('STORE_LONGITUDE') }} },
    map: map,
    title: 'Coffee Express'
  });
</script>
```

### Calculate Distance
```php
// In controller
$distance = calculateDistance(
  $userLat, $userLng,
  env('STORE_LATITUDE'),
  env('STORE_LONGITUDE')
);
```

---

## Pricing

Google Maps API:
- Free tier: $200/month credit
- Cukup untuk development & small production
- Pay-as-you-go untuk usage lebih dari credit

---

## Troubleshooting

**Map tidak muncul:**
- Check API key di .env
- Verify Maps API enabled di Google Cloud
- Check browser console untuk error

**Distance calculation error:**
- Verify Geocoding API enabled
- Check latitude/longitude format
- Verify API key permissions

**Rate limit exceeded:**
- Upgrade Google Cloud plan
- Implement caching untuk requests
- Optimize API calls
