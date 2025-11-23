# 📚 Documentation

Semua dokumentasi project tersimpan di folder `docs/`.

## Quick Navigation

### Getting Started
- **[01-SETUP.md](docs/01-SETUP.md)** - Instalasi dan setup awal project
- **[02-FEATURES.md](docs/02-FEATURES.md)** - Overview fitur aplikasi

### Feature Guides
- **[03-REALTIME.md](docs/03-REALTIME.md)** - Setup real-time notifications dengan Pusher
- **[04-GOOGLE-MAPS.md](docs/04-GOOGLE-MAPS.md)** - Integrasi Google Maps API
- **[06-MENU-MANAGEMENT.md](docs/06-MENU-MANAGEMENT.md)** - Admin menu CRUD operations

### Deployment & Development
- **[05-DEPLOYMENT.md](docs/05-DEPLOYMENT.md)** - Deploy ke production
- **[07-ROUTES.md](docs/07-ROUTES.md)** - API routes dan endpoints

### Reference Documentation
- **[CHANGELOG.md](docs/CHANGELOG.md)** - Version history
- **[REALTIME_SETUP.md](docs/REALTIME_SETUP.md)** - Detailed real-time setup
- **[GOOGLE_MAPS_INTEGRATION.md](docs/GOOGLE_MAPS_INTEGRATION.md)** - Maps integration details
- **[MENU_CRUD_DOCUMENTATION.md](docs/MENU_CRUD_DOCUMENTATION.md)** - Menu system documentation
- **[MODERN_UI_2025_GUIDE.md](docs/MODERN_UI_2025_GUIDE.md)** - UI/UX guidelines
- **[AESTHETIC_ENHANCEMENTS.md](docs/AESTHETIC_ENHANCEMENTS.md)** - Design enhancements

---

## Project Info

- **Name**: Coffee Express ☕
- **Type**: Web Application
- **Framework**: Laravel 12
- **Database**: MySQL / PostgreSQL / SQLite
- **Frontend**: Blade, Alpine.js, Tailwind CSS
- **Real-time**: Pusher Broadcasting

## Quick Start

```bash
# Install dependencies
composer install
npm install

# Setup environment
cp .env.example .env
php artisan key:generate

# Database
php artisan migrate
php artisan db:seed

# Build & run
npm run build
php artisan serve
```

Access at: `http://localhost:8000`

**Test Accounts:**
- Admin: `admin@coffee.com` / `password`
- User: `user@coffee.com` / `password`

---

**For detailed documentation, see `docs/README.md`**
