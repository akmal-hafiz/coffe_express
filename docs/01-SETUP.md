# Setup Project

## Quick Start

### 1. Install Dependencies
```bash
composer install
npm install
```

### 2. Setup Environment
```bash
cp .env.example .env
php artisan key:generate
```

### 3. Database Setup
```bash
php artisan migrate
php artisan db:seed
```

### 4. Build Assets
```bash
npm run build
```

### 5. Run Server
```bash
php artisan serve
```

Aplikasi akan berjalan di `http://localhost:8000`

---

## Test Accounts

**Admin:**
- Email: `admin@coffee.com`
- Password: `password`
- Access: `/admin/dashboard`

**User:**
- Email: `user@coffee.com`
- Password: `password`
- Access: Homepage & order features

---

## Database

Aplikasi mendukung MySQL, PostgreSQL, atau SQLite. Konfigurasi di `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=coffe_express
DB_USERNAME=root
DB_PASSWORD=
```

---

## Troubleshooting

**Error: "Class not found"**
```bash
composer dump-autoload
```

**Error: "No application encryption key"**
```bash
php artisan key:generate
```

**Error: "Database connection failed"**
- Pastikan database sudah dibuat
- Check `.env` configuration
- Jalankan `php artisan migrate`
