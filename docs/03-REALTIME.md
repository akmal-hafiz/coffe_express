# Real-time Features Setup

## Overview

Aplikasi menggunakan Pusher untuk real-time order notifications dan live dashboard updates.

## Setup Pusher

### 1. Create Pusher Account
1. Buka https://pusher.com/
2. Sign up untuk free account
3. Create aplikasi baru (Channels product)
4. Pilih cluster: **ap1** (Asia Pacific)

### 2. Get Credentials
Dari Pusher dashboard, copy:
- App ID
- Key
- Secret
- Cluster

### 3. Configure .env
```env
BROADCAST_DRIVER=pusher

PUSHER_APP_ID=your_app_id
PUSHER_APP_KEY=your_app_key
PUSHER_APP_SECRET=your_app_secret
PUSHER_APP_CLUSTER=ap1

VITE_PUSHER_APP_KEY="${PUSHER_APP_KEY}"
VITE_PUSHER_APP_CLUSTER="${PUSHER_APP_CLUSTER}"
```

### 4. Clear Cache
```bash
php artisan config:clear
php artisan cache:clear
```

---

## How It Works

### Broadcasting Events
Ketika admin update order status:
1. Event `OrderUpdated` di-trigger
2. Event di-broadcast ke Pusher
3. Frontend listening ke channel
4. Real-time update di UI

### Channels
- **Private channel**: `private-order.{user_id}`
- **Admin channel**: `private-admin-orders`

### Events
- `OrderUpdated` - Ketika status order berubah
- `OrderCreated` - Ketika order baru dibuat

---

## Testing

### Via Tinker
```bash
php artisan tinker
```

```php
event(new \App\Events\OrderUpdated(\App\Models\Order::first()));
```

Check Pusher dashboard untuk event yang di-broadcast.

### Via Browser
1. Buka order status page
2. Di tab lain, update order status dari admin
3. Lihat real-time update di user tab

---

## Troubleshooting

**Real-time tidak bekerja:**
- Check Pusher credentials di `.env`
- Verify `BROADCAST_DRIVER=pusher`
- Check browser console untuk error
- Check Pusher dashboard untuk events

**Event tidak di-broadcast:**
- Pastikan event class implements `ShouldBroadcast`
- Check channel authorization di `routes/channels.php`
- Verify user authenticated

---

## Pricing

Pusher free tier:
- 100 concurrent connections
- 200 messages/day
- Cukup untuk development & testing

Untuk production dengan traffic tinggi, upgrade ke paid plan.
