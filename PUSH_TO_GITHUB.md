# Cara Push ke GitHub

Panduan singkat untuk push project ini ke GitHub dan mengganti repository lama.

## Langkah Cepat

```bash
# 1. Masuk ke folder project
cd c:\laragon\www\coffe_express_new2\coffe_express

# 2. Tambahkan semua file
git add .

# 3. Commit perubahan
git commit -m "Update project Coffee Express"

# 4. Push ke GitHub (mengganti file lama)
git push -f origin main
```

Selesai! File lama di GitHub akan terganti dengan yang baru.

## Tentang Database

**Database MySQL tidak akan ikut ke GitHub.**

Yang akan ter-push:
- Kode aplikasi
- Migration files (struktur database)
- Seeder files (data contoh)

Yang tidak akan ter-push:
- File `.env` (konfigurasi pribadi)
- Data database MySQL
- Folder `vendor` dan `node_modules`

## Cara Teman Anda Setup Project

Setelah clone dari GitHub:

```bash
# Install dependencies
composer install
npm install

# Setup environment
copy .env.example .env
php artisan key:generate

# Buat database, lalu jalankan
php artisan migrate
php artisan db:seed

# Jalankan aplikasi
npm run dev
php artisan serve
```

Login admin: `admin@coffeexpress.com` / `admin123`

## Backup Database (Opsional)

Jika ingin berbagi data database:

```powershell
.\export-database.ps1
```

Kirim file `.sql` yang dihasilkan ke teman via email/Google Drive.

Teman bisa import dengan:
```bash
mysql -u root -p coffe_express < backup_*.sql
```

## Tips

- Jangan push file `.env` (sudah otomatis diabaikan)
- Force push (`-f`) akan menghapus history lama
- Pastikan backup dulu sebelum force push
- Cek file yang akan di-push dengan `git status`

---

Butuh bantuan? Baca `SETUP_GUIDE.md` atau `README.md`
