# Features Overview

## User Features

### Authentication
- Registrasi dan login user
- Role-based access (User & Admin)
- Secure password hashing

### Menu
- Tampilan menu kopi dan non-kopi
- Detail produk dengan harga
- Add to cart functionality

### Ordering
- Checkout dengan form validation
- Pilihan pickup atau delivery
- Multiple payment methods
- Order confirmation

### Order Tracking
- Real-time order status updates
- Progress tracking dari pending hingga completed
- Estimated completion time
- Order history

---

## Admin Features

### Dashboard
- Statistics cards (total orders, pending, ready, completed)
- Quick overview of business metrics

### Order Management
- View semua orders dalam table format
- Update order status (Pending → Preparing → Ready → Completed)
- Set estimated completion time
- Delete orders
- Filter by status

### Notifications
- Real-time updates ketika ada order baru
- Notifikasi status changes ke customer

---

## Technical Features

### Real-time Broadcasting
- Pusher integration untuk live updates
- Order status notifications
- Real-time dashboard updates

### Google Maps
- Lokasi toko
- Delivery location tracking
- Distance calculation

### UI/UX
- Responsive design (mobile & desktop)
- Coffee-themed color scheme
- Smooth animations & transitions
- Loading indicators
- Toast notifications (SweetAlert2)

---

## Database Structure

### Users Table
- id, name, email, password, role (user/admin)
- timestamps

### Orders Table
- id, user_id, customer_name, phone, address
- items (JSON), total_price
- pickup_option (pickup/delivery)
- payment_method, status
- estimated_time, timestamps

### Menus Table
- id, name, description, price
- category (coffee/non-coffee)
- image, is_active
- timestamps
