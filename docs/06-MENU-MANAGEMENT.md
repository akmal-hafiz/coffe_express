# Menu Management

## Overview

Admin dapat mengelola menu kopi dan non-kopi melalui dashboard admin.

## Features

- **Create** - Tambah menu baru
- **Read** - Lihat daftar menu
- **Update** - Edit menu
- **Delete** - Hapus menu
- **Upload Image** - Tambah gambar menu
- **Set Category** - Coffee atau Non-Coffee
- **Toggle Status** - Aktif/nonaktif

## Database Schema

```sql
CREATE TABLE menus (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    category ENUM('coffee', 'non-coffee'),
    image VARCHAR(255),
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);
```

## Admin Routes

```
GET    /admin/menus              - List all menus
GET    /admin/menus/create       - Create form
POST   /admin/menus              - Store menu
GET    /admin/menus/{id}/edit    - Edit form
PUT    /admin/menus/{id}         - Update menu
DELETE /admin/menus/{id}         - Delete menu
```

## File Structure

```
app/Models/Menu.php
app/Http/Controllers/Admin/MenuController.php
resources/views/admin/menus/
  ├── index.blade.php
  ├── create.blade.php
  └── edit.blade.php
storage/app/public/menus/  (image storage)
```

## Usage

### Create Menu
1. Go to Admin Dashboard
2. Click "Manage Menus"
3. Click "Add Menu"
4. Fill form:
   - Name (required)
   - Description (optional)
   - Price (required)
   - Category (coffee/non-coffee)
   - Image (optional)
5. Click "Save"

### Edit Menu
1. Go to Manage Menus
2. Click "Edit" on menu
3. Update fields
4. Click "Update"

### Delete Menu
1. Go to Manage Menus
2. Click "Delete" on menu
3. Confirm deletion

### Toggle Status
Click the status toggle to activate/deactivate menu.

## Image Upload

- Supported formats: JPG, PNG, GIF
- Max size: 2MB
- Stored in: `storage/app/public/menus/`
- Access via: `/storage/menus/filename.jpg`

## Validation

- Name: required, max 255 chars
- Price: required, decimal (10,2)
- Category: required, coffee or non-coffee
- Image: optional, image format, max 2MB
