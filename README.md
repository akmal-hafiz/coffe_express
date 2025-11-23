<<<<<<< HEAD
# Coffee Express ☕

Aplikasi web untuk manajemen pesanan kopi dengan fitur real-time tracking, admin dashboard, dan integrasi Google Maps.

## Fitur Utama

- **User Authentication** - Registrasi dan login user
- **Menu Management** - Tampilan menu kopi dan non-kopi
- **Order System** - Sistem pemesanan dengan tracking real-time
- **Admin Dashboard** - Kelola pesanan dan update status
- **Real-time Notifications** - Notifikasi live untuk update pesanan
- **Google Maps Integration** - Lokasi toko dan pengiriman
- **Responsive Design** - Mobile-friendly interface

## Tech Stack

- **Backend**: Laravel 12
- **Frontend**: Blade Templates, Alpine.js, Tailwind CSS
- **Database**: MySQL / PostgreSQL / SQLite
- **Real-time**: Pusher Broadcasting
- **Build Tool**: Vite

## Requirements

- PHP 8.2+
- Composer
- Node.js & npm
- MySQL/PostgreSQL/SQLite

## Installation

1. Clone repository
```bash
git clone https://github.com/username/coffe_express.git
cd coffe_express
```

2. Install dependencies
```bash
composer install
npm install
```

3. Setup environment
```bash
cp .env.example .env
php artisan key:generate
```

4. Setup database
```bash
php artisan migrate
php artisan db:seed
```

5. Build assets
```bash
npm run build
```

6. Start server
```bash
php artisan serve
```

Aplikasi akan berjalan di `http://localhost:8000`

## Documentation

Lihat folder `docs/` untuk dokumentasi lengkap:
- `SETUP_GUIDE.md` - Panduan setup awal
- `MENU_CRUD_DOCUMENTATION.md` - Fitur menu
- `REALTIME_SETUP.md` - Setup real-time features
- `GOOGLE_MAPS_INTEGRATION.md` - Integrasi maps

## License

MIT License
=======
# coffe_express
Coffee Express - Laravel 12 App
>>>>>>> 886b939cedbccda21463f9c469b585f117c49c30
