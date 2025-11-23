# 📋 MENU CRUD MANAGEMENT - DOCUMENTATION

## Coffee Express - Admin Menu Management System

Dokumentasi lengkap untuk sistem CRUD (Create, Read, Update, Delete) menu di admin dashboard.

---

## 🎯 OVERVIEW

Admin sekarang dapat mengelola menu Coffee Express dengan mudah melalui dashboard admin:

### **Features:**
✅ **Create** - Tambah menu baru
✅ **Read** - Lihat daftar semua menu
✅ **Update** - Edit informasi menu
✅ **Delete** - Hapus menu
✅ **Upload Image** - Upload gambar menu
✅ **Set Category** - Pilih Coffee atau Non-Coffee
✅ **Set Price** - Tentukan harga menu
✅ **Toggle Status** - Aktifkan/nonaktifkan menu

---

## 📊 DATABASE SCHEMA

### **Table: `menus`**

```sql
CREATE TABLE menus (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    description TEXT NULL,
    price DECIMAL(10,2) NOT NULL,
    category ENUM('coffee', 'non-coffee') DEFAULT 'coffee',
    image VARCHAR(255) NULL,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);
```

### **Columns Explanation:**

| Column | Type | Description |
|--------|------|-------------|
| `id` | BIGINT | Primary key, auto increment |
| `name` | VARCHAR(255) | Nama menu (required) |
| `description` | TEXT | Deskripsi menu (optional) |
| `price` | DECIMAL(10,2) | Harga menu (required) |
| `category` | ENUM | 'coffee' atau 'non-coffee' |
| `image` | VARCHAR(255) | Path gambar di storage |
| `is_active` | BOOLEAN | Status aktif/nonaktif |
| `created_at` | TIMESTAMP | Waktu dibuat |
| `updated_at` | TIMESTAMP | Waktu diupdate |

---

## 🗂️ FILE STRUCTURE

```
app/
├── Models/
│   └── Menu.php                          # Menu model
└── Http/Controllers/Admin/
    └── MenuController.php                # CRUD controller

database/migrations/
└── 2025_11_09_122856_create_menus_table.php

resources/views/admin/menus/
├── index.blade.php                       # List all menus
├── create.blade.php                      # Create form
└── edit.blade.php                        # Edit form

routes/
└── web.php                               # Routes definition

storage/app/public/menus/                 # Image storage
```

---

## 🚀 ROUTES

### **Admin Menu Routes:**

```php
Route::middleware(['auth', 'isAdmin'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('menus', AdminMenuController::class);
});
```

### **Generated Routes:**

| Method | URI | Name | Action |
|--------|-----|------|--------|
| GET | `/admin/menus` | admin.menus.index | List all menus |
| GET | `/admin/menus/create` | admin.menus.create | Show create form |
| POST | `/admin/menus` | admin.menus.store | Store new menu |
| GET | `/admin/menus/{menu}` | admin.menus.show | Show single menu |
| GET | `/admin/menus/{menu}/edit` | admin.menus.edit | Show edit form |
| PUT/PATCH | `/admin/menus/{menu}` | admin.menus.update | Update menu |
| DELETE | `/admin/menus/{menu}` | admin.menus.destroy | Delete menu |

---

## 💻 USAGE GUIDE

### **1. ACCESS MENU MANAGEMENT**

**Step 1:** Login sebagai admin
```
URL: http://localhost:8000/login
Email: admin@example.com
Password: your_password
```

**Step 2:** Go to admin dashboard
```
URL: http://localhost:8000/admin/dashboard
```

**Step 3:** Klik tombol "Kelola Menu" di header
```
URL: http://localhost:8000/admin/menus
```

---

### **2. CREATE NEW MENU**

**Step 1:** Klik tombol "Tambah Menu Baru"

**Step 2:** Isi form:
- **Nama Menu** (required): Contoh "Cappuccino"
- **Deskripsi** (optional): Contoh "Espresso dengan susu steamed"
- **Harga** (required): Contoh "25000"
- **Kategori** (required): Pilih "Coffee" atau "Non-Coffee"
- **Gambar** (optional): Upload gambar (JPG, PNG, GIF, max 2MB)
- **Status Aktif** (checkbox): Centang untuk aktifkan menu

**Step 3:** Klik "Simpan Menu"

**Result:**
- Menu tersimpan di database
- Gambar tersimpan di `storage/app/public/menus/`
- Redirect ke halaman index dengan success message

---

### **3. VIEW MENUS**

**URL:** `/admin/menus`

**Display:**
- Grid layout (3 columns di desktop)
- Card untuk setiap menu menampilkan:
  - Gambar menu
  - Nama menu
  - Deskripsi (truncated)
  - Harga
  - Category badge
  - Status badge (jika inactive)
  - Action buttons (Edit, Delete)

**Pagination:**
- 10 menus per page
- Pagination links di bawah

---

### **4. EDIT MENU**

**Step 1:** Klik tombol "Edit" pada menu card

**Step 2:** Update informasi:
- Semua field dapat diubah
- Gambar lama ditampilkan
- Upload gambar baru akan replace gambar lama

**Step 3:** Klik "Update Menu"

**Result:**
- Menu terupdate di database
- Gambar lama dihapus jika ada gambar baru
- Redirect ke index dengan success message

---

### **5. DELETE MENU**

**Step 1:** Klik tombol "Hapus" pada menu card

**Step 2:** Konfirmasi delete

**Result:**
- Menu dihapus dari database
- Gambar dihapus dari storage
- Redirect ke index dengan success message

---

## 🎨 UI/UX FEATURES

### **Index Page:**
```
✅ Grid layout responsive
✅ Beautiful card design
✅ Image preview
✅ Category badges (color-coded)
✅ Status indicators
✅ Quick actions (Edit, Delete)
✅ Empty state message
✅ Pagination
```

### **Create/Edit Form:**
```
✅ Clean form layout
✅ Input validation
✅ Error messages
✅ Image upload with preview
✅ Price input with Rp prefix
✅ Category dropdown with icons
✅ Active status toggle
✅ Cancel button
```

### **Success Messages:**
```
✅ Green alert box
✅ Icon indicator
✅ Clear message text
✅ Auto-dismiss (optional)
```

---

## 🔧 CONTROLLER METHODS

### **MenuController.php**

#### **index()**
```php
public function index()
{
    $menus = Menu::latest()->paginate(10);
    return view('admin.menus.index', compact('menus'));
}
```
**Purpose:** Display all menus with pagination

#### **create()**
```php
public function create()
{
    return view('admin.menus.create');
}
```
**Purpose:** Show create form

#### **store(Request $request)**
```php
public function store(Request $request)
{
    // Validate input
    // Upload image if exists
    // Create menu
    // Redirect with success message
}
```
**Purpose:** Store new menu

**Validation Rules:**
- `name`: required, string, max 255
- `description`: nullable, string
- `price`: required, numeric, min 0
- `category`: required, in:coffee,non-coffee
- `image`: nullable, image, mimes:jpeg,png,jpg,gif, max:2048
- `is_active`: boolean

#### **edit(Menu $menu)**
```php
public function edit(Menu $menu)
{
    return view('admin.menus.edit', compact('menu'));
}
```
**Purpose:** Show edit form

#### **update(Request $request, Menu $menu)**
```php
public function update(Request $request, Menu $menu)
{
    // Validate input
    // Delete old image if new image uploaded
    // Upload new image
    // Update menu
    // Redirect with success message
}
```
**Purpose:** Update existing menu

#### **destroy(Menu $menu)**
```php
public function destroy(Menu $menu)
{
    // Delete image if exists
    // Delete menu
    // Redirect with success message
}
```
**Purpose:** Delete menu

---

## 📦 MODEL FEATURES

### **Menu.php**

#### **Fillable Fields:**
```php
protected $fillable = [
    'name',
    'description',
    'price',
    'category',
    'image',
    'is_active',
];
```

#### **Casts:**
```php
protected $casts = [
    'price' => 'decimal:2',
    'is_active' => 'boolean',
];
```

#### **Query Scopes:**

**Active Menus:**
```php
Menu::active()->get();
```

**Coffee Category:**
```php
Menu::coffee()->get();
```

**Non-Coffee Category:**
```php
Menu::nonCoffee()->get();
```

**Combined:**
```php
Menu::active()->coffee()->get();
```

---

## 🖼️ IMAGE HANDLING

### **Upload Process:**

1. **Validation:**
   - File type: JPEG, PNG, JPG, GIF
   - Max size: 2MB (2048 KB)

2. **Storage:**
   - Disk: `public`
   - Path: `storage/app/public/menus/`
   - Filename: Auto-generated by Laravel

3. **Database:**
   - Stored as: `menus/filename.jpg`

4. **Display:**
   - URL: `{{ asset('storage/' . $menu->image) }}`

### **Delete Process:**

1. Check if image exists
2. Delete from storage: `Storage::disk('public')->delete($menu->image)`
3. Update database

---

## 🧪 TESTING GUIDE

### **Test Create Menu:**

1. Go to `/admin/menus/create`
2. Fill form:
   ```
   Name: Test Cappuccino
   Description: Test description
   Price: 25000
   Category: Coffee
   Image: Upload test image
   Active: Checked
   ```
3. Click "Simpan Menu"
4. ✅ Should redirect to index with success message
5. ✅ Menu should appear in list

### **Test Edit Menu:**

1. Click "Edit" on a menu
2. Change name to "Updated Cappuccino"
3. Upload new image
4. Click "Update Menu"
5. ✅ Should update successfully
6. ✅ Old image should be deleted
7. ✅ New image should be displayed

### **Test Delete Menu:**

1. Click "Hapus" on a menu
2. Confirm deletion
3. ✅ Menu should be deleted
4. ✅ Image should be deleted from storage
5. ✅ Success message should appear

### **Test Validation:**

1. Try to submit empty form
2. ✅ Should show validation errors
3. Try to upload file > 2MB
4. ✅ Should show size error
5. Try to upload non-image file
6. ✅ Should show type error

---

## 🔒 SECURITY

### **Authorization:**

```php
Route::middleware(['auth', 'isAdmin'])
```

**Checks:**
- ✅ User must be logged in
- ✅ User must be admin
- ✅ Non-admin cannot access

### **Validation:**

```php
$validated = $request->validate([...]);
```

**Protects Against:**
- ✅ SQL injection
- ✅ XSS attacks
- ✅ Invalid data types
- ✅ Missing required fields

### **File Upload:**

```php
'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
```

**Protects Against:**
- ✅ Non-image files
- ✅ Large files (> 2MB)
- ✅ Malicious files

---

## 📊 DATABASE QUERIES

### **Get All Active Coffee Menus:**
```php
$coffeeMenus = Menu::active()->coffee()->get();
```

### **Get Latest 10 Menus:**
```php
$latestMenus = Menu::latest()->take(10)->get();
```

### **Get Menu by ID:**
```php
$menu = Menu::findOrFail($id);
```

### **Count Total Menus:**
```php
$totalMenus = Menu::count();
```

### **Get Menus by Category:**
```php
$coffeeCount = Menu::where('category', 'coffee')->count();
$nonCoffeeCount = Menu::where('category', 'non-coffee')->count();
```

---

## 🎯 FUTURE ENHANCEMENTS

### **Phase 2:**
- [ ] Bulk actions (delete multiple, activate/deactivate)
- [ ] Search and filter functionality
- [ ] Sort by name, price, category
- [ ] Export to CSV/Excel
- [ ] Import from CSV

### **Phase 3:**
- [ ] Menu categories (subcategories)
- [ ] Menu variations (sizes, add-ons)
- [ ] Stock management
- [ ] Popular/featured menu flag
- [ ] Menu availability schedule

### **Phase 4:**
- [ ] Menu analytics (most ordered, revenue)
- [ ] Customer reviews and ratings
- [ ] Menu recommendations
- [ ] Seasonal menus
- [ ] Promo/discount management

---

## 🐛 TROUBLESHOOTING

### **Image Not Displaying:**

**Problem:** Image uploaded but not showing

**Solutions:**
1. Check storage link exists:
   ```bash
   php artisan storage:link
   ```
2. Check file permissions:
   ```bash
   chmod -R 775 storage/
   ```
3. Check image path in database
4. Check browser console for 404 errors

### **Upload Failed:**

**Problem:** Image upload returns error

**Solutions:**
1. Check file size (max 2MB)
2. Check file type (JPG, PNG, GIF only)
3. Check storage folder exists
4. Check folder permissions

### **Validation Errors:**

**Problem:** Form shows validation errors

**Solutions:**
1. Check all required fields are filled
2. Check price is numeric
3. Check category is selected
4. Check image meets requirements

---

## 📝 CHANGELOG

### **Version 1.0 (Current)**
- ✅ Basic CRUD functionality
- ✅ Image upload
- ✅ Category selection
- ✅ Active/inactive toggle
- ✅ Responsive design
- ✅ Form validation
- ✅ Success messages

---

## 🎊 SUMMARY

**Menu CRUD System Features:**

✅ **Complete CRUD** - Create, Read, Update, Delete
✅ **Image Management** - Upload, preview, delete
✅ **Category System** - Coffee vs Non-Coffee
✅ **Price Management** - Set harga dengan format Rupiah
✅ **Status Toggle** - Aktifkan/nonaktifkan menu
✅ **Responsive Design** - Mobile-friendly
✅ **Form Validation** - Input validation & error handling
✅ **User Feedback** - Success/error messages
✅ **Secure** - Auth & admin middleware
✅ **Clean UI** - Modern, coffee-themed design

---

**Last Updated**: November 9, 2025
**Version**: 1.0
**Status**: Production Ready ✅
