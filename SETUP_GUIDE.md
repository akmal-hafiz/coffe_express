# Panduan Setup

Panduan lengkap untuk install dan menjalankan Coffee Express di komputer Anda.

## Persiapan

Pastikan sudah terinstall:
- PHP versi 8.2 ke atas
- Composer (download di getcomposer.org)
- Node.js dan NPM (download di nodejs.org)
- MySQL atau MariaDB
- Git

## Langkah-langkah

### 1. Download Project

```bash
git clone <url-repository>
cd coffe_express
```

### 2. Install Dependencies

Install library PHP:
```bash
composer install
```

Install library JavaScript:
```bash
npm install
```

### 3. Setup Environment

Copy file contoh environment:
```bash
copy .env.example .env
```

Generate key aplikasi:
```bash
php artisan key:generate
```

### 4. Konfigurasi Database

Buka file `.env` dengan text editor, lalu edit bagian database:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=coffe_express
DB_USERNAME=root
DB_PASSWORD=
```

Sesuaikan `DB_USERNAME` dan `DB_PASSWORD` dengan setting MySQL .

### 5. Buat Database

Buat database baru bernama `coffe_express` di MySQL.

Bisa pakai phpMyAdmin, MySQL Workbench, atau command line:
```sql
CREATE DATABASE coffe_express;
```

### 6. Jalankan Migration

Migration akan membuat tabel-tabel yang dibutuhkan:
```bash
php artisan migrate
```

### 7. Isi Data Awal (Seeding)

Seeder akan mengisi database dengan data contoh:
```bash
php artisan db:seed
```

Ini akan membuat:
- Akun admin (email: admin@example.com, password: password)
- Beberapa menu contoh
- Promo dan berita contoh
- Tingkatan loyalitas

### 8. Link Storage

Buat symbolic link untuk folder storage:
```bash
php artisan storage:link
```

### 9. Build Assets

Compile CSS dan JavaScript:
```bash
npm run dev
```

Biarkan command ini tetap berjalan di terminal.

### 10. Jalankan Server

Buka terminal baru, lalu jalankan:
```bash
php artisan serve
```

Aplikasi sekarang bisa diakses di: **http://localhost:8000**

## Login Admin

Gunakan akun ini untuk masuk sebagai admin:
- **Email**: admin@example.com
- **Password**: password

## Troubleshooting

### Error: "could not find driver"

Install atau aktifkan extension MySQL di PHP:
- **Laragon**: Sudah terinstall otomatis
- **XAMPP**: Edit `php.ini`, uncomment baris `extension=pdo_mysql`

### Error: "No application encryption key"

Jalankan:
```bash
php artisan key:generate
```

### Error: "Class not found"

Jalankan:
```bash
composer dump-autoload
```

### Error: Permission denied (Linux/Mac)

Set permission yang benar:
```bash
chmod -R 775 storage bootstrap/cache
```

### Port 8000 sudah dipakai

Gunakan port lain:
```bash
php artisan serve --port=8080
```

## Command Tambahan

Clear semua cache:
```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

Reset database (HATI-HATI: akan hapus semua data):
```bash
php artisan migrate:fresh --seed
```

Build untuk production:
```bash
npm run build
```

## Butuh Bantuan?

Kalau masih ada masalah, cek dokumentasi di folder `docs/` atau buat issue di repository.

---

Selamat mencoba! ☕
