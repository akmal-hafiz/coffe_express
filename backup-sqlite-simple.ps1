# Simple Backup Script for SQLite Data
# Jalankan: .\backup-sqlite-simple.ps1

Write-Host ""
Write-Host "=== Backup Data SQLite ===" -ForegroundColor Cyan
Write-Host ""

# Create backup directory
$backupDir = "database\backups"
if (-not (Test-Path $backupDir)) {
    New-Item -ItemType Directory -Path $backupDir | Out-Null
    Write-Host "[OK] Created backup directory: $backupDir" -ForegroundColor Green
}

$timestamp = Get-Date -Format "yyyyMMdd_HHmmss"

# Backup Users
Write-Host "Backing up Users..." -ForegroundColor Yellow
$usersCmd = "file_put_contents('database/backups/users_$timestamp.json', json_encode(\App\Models\User::all()->toArray(), JSON_PRETTY_PRINT));"
php artisan tinker --execute=$usersCmd
if ($LASTEXITCODE -eq 0) {
    Write-Host "[OK] Users backed up" -ForegroundColor Green
}

# Backup Orders
Write-Host "Backing up Orders..." -ForegroundColor Yellow
$ordersCmd = "file_put_contents('database/backups/orders_$timestamp.json', json_encode(\App\Models\Order::all()->toArray(), JSON_PRETTY_PRINT));"
php artisan tinker --execute=$ordersCmd
if ($LASTEXITCODE -eq 0) {
    Write-Host "[OK] Orders backed up" -ForegroundColor Green
}

# Backup Menus
Write-Host "Backing up Menus..." -ForegroundColor Yellow
$menusCmd = "file_put_contents('database/backups/menus_$timestamp.json', json_encode(\App\Models\Menu::all()->toArray(), JSON_PRETTY_PRINT));"
php artisan tinker --execute=$menusCmd
if ($LASTEXITCODE -eq 0) {
    Write-Host "[OK] Menus backed up" -ForegroundColor Green
}

# Copy SQLite file
Write-Host "Copying SQLite database file..." -ForegroundColor Yellow
Copy-Item "database\database.sqlite" "database\backups\database_$timestamp.sqlite"
Write-Host "[OK] SQLite file backed up" -ForegroundColor Green

Write-Host ""
Write-Host "=== Backup Complete ===" -ForegroundColor Cyan
Write-Host "Backup location: $backupDir" -ForegroundColor White
Write-Host "Timestamp: $timestamp" -ForegroundColor White
Write-Host ""
Write-Host "Files created:" -ForegroundColor White
Get-ChildItem $backupDir | Where-Object { $_.Name -like "*$timestamp*" } | ForEach-Object {
    Write-Host "  - $($_.Name) ($([math]::Round($_.Length/1KB, 2)) KB)" -ForegroundColor Gray
}
Write-Host ""
