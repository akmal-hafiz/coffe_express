# Coffee Express ☕

Aplikasi web untuk coffee shop dengan sistem pemesanan online, tracking pesanan real-time, dan dashboard admin.

## Fitur Utama

**Untuk Pelanggan:**
- Lihat menu kopi, makanan, dan minuman
- Pesan online dengan keranjang belanja
- Tracking status pesanan secara real-time
- Riwayat pesanan
- Program loyalitas dan poin reward
- Tulis review dan rating
- Dukungan bahasa Indonesia dan Inggris

**Untuk Admin:**
- Dashboard dengan statistik penjualan
- Kelola pesanan dan update status
- Manajemen menu (tambah, edit, hapus)
- Kelola promo dan berita
- Lihat dan balas pesan dari pelanggan
- Moderasi review pelanggan
- Laporan penjualan (export Excel/PDF)

## Teknologi

- Laravel 12
- MySQL
- Tailwind CSS + Alpine.js
- Pusher (real-time notifications)
- Google Maps API

## Instalasi

### Kebutuhan Sistem
- PHP 8.2 atau lebih baru
- Composer
- Node.js & NPM
- MySQL atau MariaDB

### Langkah Instalasi

1. Clone repository ini
```bash
git clone <url-repository>
cd coffe_express
```

2. Install dependencies
```bash
composer install
npm install
```

3. Setup environment
```bash
copy .env.example .env
php artisan key:generate
```

4. Konfigurasi database di file `.env`
```
DB_DATABASE=coffe_express
DB_USERNAME=root
DB_PASSWORD=
```

5. Buat database dan jalankan migration
```bash
php artisan migrate
php artisan db:seed
```

6. Link storage folder
```bash
php artisan storage:link
```

7. Build assets dan jalankan server
```bash
npm run dev
```

Di terminal lain:
```bash
php artisan serve
```

Buka browser: `http://localhost:8000`

### Akun Default

Setelah seeding, gunakan akun ini untuk login sebagai admin:
- Email: `admin@example.com`
- Password: `password`

## Struktur Database

Aplikasi ini menggunakan beberapa tabel utama:
- `users` - Data pengguna (customer dan admin)
- `orders` - Data pesanan
- `menus` - Daftar menu
- `promos` - Konten promosi
- `news` - Artikel berita/blog
- `contacts` - Pesan dari form kontak
- `reviews` - Review pelanggan
- `loyalty_tiers` - Tingkatan loyalitas
- `loyalty_points` - Poin loyalitas user

## Command Berguna

```bash
# Clear cache
php artisan cache:clear
php artisan config:clear

# Reset database (hati-hati, akan hapus semua data)
php artisan migrate:fresh --seed

# Build untuk production
npm run build
```

## Deployment

Untuk production:

1. Set environment di `.env`
```
APP_ENV=production
APP_DEBUG=false
```

2. Install dependencies production
```bash
composer install --optimize-autoloader --no-dev
npm run build
```

3. Set permission yang benar
```bash
chmod -R 775 storage bootstrap/cache
```

## Dokumentasi Lengkap

Lihat folder `docs/` untuk dokumentasi detail tentang:
- Setup real-time notifications
- Integrasi Google Maps
- Manajemen menu
- Dan lainnya

## Lisensi

MIT License

---

Dibuat dengan ☕ dan ❤️
