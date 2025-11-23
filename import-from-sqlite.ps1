# Import Data dari SQLite ke MySQL
# Jalankan: .\import-from-sqlite.ps1

Write-Host ""
Write-Host "========================================================" -ForegroundColor Cyan
Write-Host "   Import Data: SQLite -> MySQL                        " -ForegroundColor Cyan
Write-Host "========================================================" -ForegroundColor Cyan
Write-Host ""

# Step 1: Backup .env
Write-Host "[Step 1] Backup current .env" -ForegroundColor Yellow
$timestamp = Get-Date -Format "yyyyMMdd_HHmmss"
Copy-Item ".env" ".env.mysql.backup_$timestamp"
Write-Host "[OK] Backed up to .env.mysql.backup_$timestamp" -ForegroundColor Green
Write-Host ""

# Step 2: Temporarily switch to SQLite
Write-Host "[Step 2] Switch to SQLite temporarily" -ForegroundColor Yellow
$envContent = Get-Content ".env" -Raw
$envContent = $envContent -replace "DB_CONNECTION=mysql", "DB_CONNECTION=sqlite"
$envContent | Set-Content ".env" -NoNewline
Write-Host "[OK] Switched to SQLite" -ForegroundColor Green
Write-Host ""

# Step 3: Clear cache
Write-Host "[Step 3] Clear cache" -ForegroundColor Yellow
php artisan config:clear | Out-Null
Write-Host "[OK] Cache cleared" -ForegroundColor Green
Write-Host ""

# Step 4: Export data from SQLite
Write-Host "[Step 4] Export data from SQLite" -ForegroundColor Yellow

$backupDir = "database\backups"
if (-not (Test-Path $backupDir)) {
    New-Item -ItemType Directory -Path $backupDir | Out-Null
}

$exportTime = Get-Date -Format "yyyyMMdd_HHmmss"

Write-Host "  - Exporting users..." -ForegroundColor Gray
php artisan tinker --execute="echo json_encode(\App\Models\User::all()->toArray());" > "$backupDir\users_export_$exportTime.json"

Write-Host "  - Exporting orders..." -ForegroundColor Gray
php artisan tinker --execute="echo json_encode(\App\Models\Order::all()->toArray());" > "$backupDir\orders_export_$exportTime.json"

Write-Host "  - Exporting menus..." -ForegroundColor Gray
php artisan tinker --execute="echo json_encode(\App\Models\Menu::all()->toArray());" > "$backupDir\menus_export_$exportTime.json"

Write-Host "[OK] Data exported" -ForegroundColor Green
Write-Host ""

# Step 5: Switch back to MySQL
Write-Host "[Step 5] Switch back to MySQL" -ForegroundColor Yellow
$envContent = Get-Content ".env" -Raw
$envContent = $envContent -replace "DB_CONNECTION=sqlite", "DB_CONNECTION=mysql"
$envContent | Set-Content ".env" -NoNewline
php artisan config:clear | Out-Null
Write-Host "[OK] Switched to MySQL" -ForegroundColor Green
Write-Host ""

# Step 6: Import to MySQL
Write-Host "[Step 6] Import data to MySQL" -ForegroundColor Yellow

# Read exported data
$usersJson = Get-Content "$backupDir\users_export_$exportTime.json" -Raw | ConvertFrom-Json
$ordersJson = Get-Content "$backupDir\orders_export_$exportTime.json" -Raw | ConvertFrom-Json
$menusJson = Get-Content "$backupDir\menus_export_$exportTime.json" -Raw | ConvertFrom-Json

Write-Host "  Found:" -ForegroundColor Gray
Write-Host "    - Users: $($usersJson.Count)" -ForegroundColor Gray
Write-Host "    - Orders: $($ordersJson.Count)" -ForegroundColor Gray
Write-Host "    - Menus: $($menusJson.Count)" -ForegroundColor Gray
Write-Host ""

Write-Host "  Importing to MySQL..." -ForegroundColor Gray
php artisan db:seed --class=ImportBackupSeeder

Write-Host "[OK] Data imported" -ForegroundColor Green
Write-Host ""

# Step 7: Verify
Write-Host "[Step 7] Verify data" -ForegroundColor Yellow
php artisan tinker --execute="echo 'Users: ' . \App\Models\User::count() . PHP_EOL; echo 'Orders: ' . \App\Models\Order::count() . PHP_EOL; echo 'Menus: ' . \App\Models\Menu::count() . PHP_EOL;"

Write-Host ""
Write-Host "========================================================" -ForegroundColor Green
Write-Host "     Import Completed Successfully!                    " -ForegroundColor Green
Write-Host "========================================================" -ForegroundColor Green
Write-Host ""
