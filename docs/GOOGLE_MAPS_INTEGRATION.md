# 🗺️ GOOGLE MAPS INTEGRATION DOCUMENTATION

## Coffee Express - Delivery Location Picker

Dokumentasi lengkap untuk integrasi Google Maps API pada sistem delivery Coffee Express.

---

## 📋 TABLE OF CONTENTS

1. [Overview](#overview)
2. [Features](#features)
3. [Database Schema](#database-schema)
4. [Implementation](#implementation)
5. [User Guide](#user-guide)
6. [Admin View](#admin-view)
7. [API Key Setup](#api-key-setup)

---

## 🎯 OVERVIEW

Google Maps telah diintegrasikan ke dalam checkout page untuk memudahkan user memilih lokasi pengiriman yang tepat saat memilih opsi **Delivery**.

### Key Features:
✅ **Interactive Map** - User bisa klik dan drag pin
✅ **Address Autocomplete** - Search lokasi dengan cepat
✅ **Current Location** - Auto-detect lokasi user
✅ **Reverse Geocoding** - Convert koordinat ke alamat
✅ **Coordinate Storage** - Simpan latitude & longitude

---

## 🎨 FEATURES

### 1. **Map Picker**
- Map muncul otomatis saat user pilih "Delivery"
- Default location: Jakarta (-6.2088, 106.8456)
- Zoom level: 13 (city view)
- Custom styling (POI labels hidden)

### 2. **Draggable Marker**
- Pin merah yang bisa di-drag
- Drop animation saat pertama muncul
- Update address otomatis saat di-drag

### 3. **Address Autocomplete**
- Google Places Autocomplete
- Restricted to Indonesia only
- Dropdown suggestions saat mengetik
- Auto-zoom ke lokasi yang dipilih

### 4. **Click to Select**
- Klik di map untuk pindahkan pin
- Instant address update
- Smooth marker movement

### 5. **Current Location Detection**
- Auto-detect lokasi user (jika izin diberikan)
- Fallback ke Jakarta jika ditolak
- Geolocation API integration

### 6. **Address Display**
- Input field dengan autocomplete
- "Alamat terpilih" display di bawah map
- Real-time update

---

## 🗄️ DATABASE SCHEMA

### Migration: `add_coordinates_to_orders_table`

```php
Schema::table('orders', function (Blueprint $table) {
    $table->decimal('latitude', 10, 8)->nullable()->after('address');
    $table->decimal('longitude', 11, 8)->nullable()->after('latitude');
});
```

### Order Model

```php
protected $fillable = [
    'user_id',
    'customer_name',
    'phone',
    'address',
    'latitude',      // NEW
    'longitude',     // NEW
    'items',
    'total_price',
    'pickup_option',
    'payment_method',
    'status',
    'estimated_time',
    'completed_at',
];
```

### Data Types:
- **latitude**: DECIMAL(10, 8) - Range: -90.00000000 to 90.00000000
- **longitude**: DECIMAL(11, 8) - Range: -180.00000000 to 180.00000000
- **nullable**: TRUE - Opsional untuk pickup orders

---

## 💻 IMPLEMENTATION

### 1. **Google Maps API Script**

```html
<script src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&libraries=places&callback=initMap" async defer></script>
```

**Libraries Used:**
- `places` - For autocomplete functionality

### 2. **HTML Structure**

```html
<!-- Address Input with Autocomplete -->
<input type="text" id="address-input" name="address" 
       placeholder="Cari alamat atau klik di peta..."
       autocomplete="off">

<!-- Map Container -->
<div id="map"></div>

<!-- Selected Address Display -->
<p id="selected-address">-</p>

<!-- Hidden Coordinate Inputs -->
<input type="hidden" id="latitude" name="latitude">
<input type="hidden" id="longitude" name="longitude">
```

### 3. **JavaScript Functions**

#### Initialize Map
```javascript
function initMap() {
  const defaultLocation = { lat: -6.2088, lng: 106.8456 };
  
  map = new google.maps.Map(document.getElementById('map'), {
    center: defaultLocation,
    zoom: 13,
    // ... options
  });
  
  marker = new google.maps.Marker({
    position: defaultLocation,
    map: map,
    draggable: true,
    // ... options
  });
}
```

#### Update Address
```javascript
function updateAddress(location) {
  const lat = location.lat();
  const lng = location.lng();
  
  // Save coordinates
  document.getElementById('latitude').value = lat;
  document.getElementById('longitude').value = lng;
  
  // Reverse geocode
  geocoder.geocode({ location: location }, function(results, status) {
    if (status === 'OK' && results[0]) {
      const address = results[0].formatted_address;
      document.getElementById('address-input').value = address;
      document.getElementById('selected-address').textContent = address;
    }
  });
}
```

### 4. **Event Listeners**

```javascript
// Autocomplete selection
autocomplete.addListener('place_changed', function() {
  const place = autocomplete.getPlace();
  map.setCenter(place.geometry.location);
  marker.setPosition(place.geometry.location);
  updateAddress(place.geometry.location);
});

// Marker drag
marker.addListener('dragend', function(event) {
  updateAddress(event.latLng);
});

// Map click
map.addListener('click', function(event) {
  marker.setPosition(event.latLng);
  updateAddress(event.latLng);
});
```

### 5. **Controller Update**

```php
// OrderController.php
public function store(Request $request)
{
    $validated = $request->validate([
        'customer_name' => 'required|string|max:255',
        'phone' => 'required|string|max:20',
        'address' => 'nullable|string',
        'latitude' => 'nullable|numeric',   // NEW
        'longitude' => 'nullable|numeric',  // NEW
        'pickup_option' => 'required|in:pickup,delivery',
        'payment_method' => 'required|string',
        'items' => 'required|json',
        'total_price' => 'required|numeric|min:0',
    ]);

    $order = Order::create([
        // ... other fields
        'latitude' => $validated['latitude'] ?? null,
        'longitude' => $validated['longitude'] ?? null,
    ]);
}
```

---

## 📱 USER GUIDE

### How to Use (User Perspective):

#### **Step 1: Choose Delivery Option**
1. Go to checkout page
2. Select "Delivery" radio button
3. Map akan muncul otomatis

#### **Step 2: Select Location**

**Option A: Search Address**
1. Ketik alamat di search box
2. Pilih dari dropdown suggestions
3. Map akan zoom ke lokasi

**Option B: Drag Pin**
1. Drag pin merah ke lokasi yang diinginkan
2. Address akan update otomatis

**Option C: Click on Map**
1. Klik di map pada lokasi yang diinginkan
2. Pin akan pindah ke lokasi tersebut

**Option D: Use Current Location**
1. Browser akan minta izin lokasi
2. Jika diizinkan, map akan center ke lokasi user
3. Pin akan otomatis di lokasi user

#### **Step 3: Verify Address**
1. Cek "Alamat terpilih" di bawah map
2. Pastikan alamat sudah benar
3. Edit manual di search box jika perlu

#### **Step 4: Complete Order**
1. Isi data lainnya (nama, phone, payment)
2. Klik "Bayar Sekarang"
3. Koordinat akan tersimpan otomatis

---

## 👨‍💼 ADMIN VIEW

### Viewing Delivery Locations

Admin bisa melihat koordinat delivery di database:

```sql
SELECT 
    id,
    customer_name,
    address,
    latitude,
    longitude,
    pickup_option
FROM orders
WHERE pickup_option = 'delivery';
```

### Future Enhancement Ideas:

1. **Admin Map View**
   - Show all delivery locations on map
   - Route optimization
   - Distance calculation

2. **Delivery Tracking**
   - Real-time driver location
   - ETA calculation
   - Push notifications

3. **Delivery Zones**
   - Define delivery areas
   - Auto-calculate delivery fee
   - Restrict out-of-zone orders

---

## 🔑 API KEY SETUP

### Current API Key:
```
AIzaSyBFw0Qbyq9zTFTd-tUY6dZWTgaQzuU17R8
```

### Getting Your Own API Key:

1. **Go to Google Cloud Console**
   - Visit: https://console.cloud.google.com/

2. **Create New Project**
   - Click "Select a project" → "New Project"
   - Name: "Coffee Express"

3. **Enable APIs**
   - Go to "APIs & Services" → "Library"
   - Enable: "Maps JavaScript API"
   - Enable: "Places API"
   - Enable: "Geocoding API"

4. **Create API Key**
   - Go to "Credentials"
   - Click "Create Credentials" → "API Key"
   - Copy the key

5. **Restrict API Key** (Important!)
   - Click on the key
   - Under "Application restrictions":
     - Select "HTTP referrers"
     - Add: `localhost:8000/*`
     - Add: `yourdomain.com/*`
   - Under "API restrictions":
     - Select "Restrict key"
     - Choose: Maps JavaScript API, Places API, Geocoding API
   - Save

6. **Update Code**
   ```html
   <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_NEW_KEY&libraries=places&callback=initMap"></script>
   ```

### API Usage Limits:

**Free Tier (Monthly):**
- Maps JavaScript API: $200 credit
- Places API: $200 credit
- Geocoding API: $200 credit

**Estimated Usage:**
- ~28,000 map loads per month (free)
- ~28,000 autocomplete requests (free)
- ~28,000 geocoding requests (free)

---

## 🎨 CUSTOMIZATION

### Map Styling

```javascript
styles: [
  {
    featureType: 'poi',
    elementType: 'labels',
    stylers: [{ visibility: 'off' }]  // Hide POI labels
  },
  {
    featureType: 'water',
    elementType: 'geometry',
    stylers: [{ color: '#a2daf2' }]  // Custom water color
  }
]
```

### Marker Icon

```javascript
marker = new google.maps.Marker({
  position: location,
  map: map,
  icon: {
    url: '/images/coffee-pin.png',  // Custom icon
    scaledSize: new google.maps.Size(40, 40)
  }
});
```

### Map Controls

```javascript
map = new google.maps.Map(document.getElementById('map'), {
  mapTypeControl: false,      // Hide map type selector
  streetViewControl: false,   // Hide street view
  fullscreenControl: true,    // Show fullscreen button
  zoomControl: true,          // Show zoom buttons
  gestureHandling: 'greedy'   // Allow one-finger zoom on mobile
});
```

---

## 🐛 TROUBLESHOOTING

### Map Not Loading

**Problem**: Map container is blank

**Solutions**:
1. Check API key is valid
2. Check console for errors
3. Ensure `initMap` is defined globally
4. Check `callback=initMap` in script URL

### Autocomplete Not Working

**Problem**: No suggestions appear

**Solutions**:
1. Check Places API is enabled
2. Check API key restrictions
3. Clear browser cache
4. Check console for quota errors

### Geolocation Permission Denied

**Problem**: Current location not detected

**Solutions**:
1. User must allow location permission
2. HTTPS required for geolocation (not localhost)
3. Fallback to default location (Jakarta)

### Coordinates Not Saving

**Problem**: Latitude/longitude are null in database

**Solutions**:
1. Check hidden inputs have values
2. Check form submission includes coordinates
3. Check validation rules
4. Check fillable array in model

---

## 📊 TESTING CHECKLIST

### Functional Testing:

- [ ] Map loads correctly
- [ ] Marker appears at default location
- [ ] Autocomplete shows suggestions
- [ ] Selecting autocomplete updates map
- [ ] Dragging marker updates address
- [ ] Clicking map moves marker
- [ ] Current location detection works
- [ ] Address field updates correctly
- [ ] Coordinates save to database
- [ ] Pickup option hides map
- [ ] Delivery option shows map

### UI/UX Testing:

- [ ] Map is responsive on mobile
- [ ] Info box is visible and clear
- [ ] Selected address is readable
- [ ] Map controls are accessible
- [ ] Loading states are handled
- [ ] Error messages are clear

---

## 🚀 FUTURE ENHANCEMENTS

### Phase 2:
1. **Delivery Fee Calculator**
   - Calculate distance from store
   - Dynamic delivery fee based on distance
   - Show fee before checkout

2. **Multiple Store Locations**
   - Find nearest store
   - Route to nearest location
   - Store-specific delivery zones

3. **Delivery Time Estimation**
   - Calculate ETA based on distance
   - Traffic-aware estimates
   - Real-time updates

### Phase 3:
1. **Driver App Integration**
   - Driver sees delivery location on map
   - Turn-by-turn navigation
   - Mark as delivered

2. **Live Tracking**
   - User sees driver location
   - Estimated arrival time
   - Push notifications

3. **Heatmap Analytics**
   - Popular delivery areas
   - Optimize delivery routes
   - Identify new store locations

---

## 📝 CONCLUSION

Google Maps integration memberikan pengalaman yang lebih baik untuk user dalam memilih lokasi pengiriman:

✅ **Akurat** - Koordinat GPS yang presisi
✅ **User-Friendly** - Multiple ways to select location
✅ **Fast** - Autocomplete untuk search cepat
✅ **Reliable** - Fallback options jika geolocation gagal
✅ **Scalable** - Ready untuk future enhancements

---

**Last Updated**: November 9, 2025
**Version**: 1.0 (Initial Release)
**API Key**: Restricted to localhost:8000
