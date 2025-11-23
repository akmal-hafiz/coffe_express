# Script untuk Backup Data dari SQLite
# Jalankan: .\backup-sqlite-data.ps1

Write-Host "=== Backup Data SQLite ===" -ForegroundColor Cyan
Write-Host ""

# Create backup directory
$backupDir = "database\backups"
if (-not (Test-Path $backupDir)) {
    New-Item -ItemType Directory -Path $backupDir | Out-Null
    Write-Host "✓ Created backup directory: $backupDir" -ForegroundColor Green
}

$timestamp = Get-Date -Format "yyyyMMdd_HHmmss"

# Backup Users
Write-Host "Backing up Users..." -ForegroundColor Yellow
$usersFile = "$backupDir/users_$timestamp.json"
php artisan tinker --execute="file_put_contents('$usersFile', json_encode(\App\Models\User::all()->toArray(), JSON_PRETTY_PRINT));"
Write-Host "[OK] Users backed up" -ForegroundColor Green

# Backup Orders
Write-Host "Backing up Orders..." -ForegroundColor Yellow
$ordersFile = "$backupDir/orders_$timestamp.json"
php artisan tinker --execute="file_put_contents('$ordersFile', json_encode(\App\Models\Order::all()->toArray(), JSON_PRETTY_PRINT));"
Write-Host "[OK] Orders backed up" -ForegroundColor Green

# Backup Menus
Write-Host "Backing up Menus..." -ForegroundColor Yellow
$menusFile = "$backupDir/menus_$timestamp.json"
php artisan tinker --execute="file_put_contents('$menusFile', json_encode(\App\Models\Menu::all()->toArray(), JSON_PRETTY_PRINT));"
Write-Host "[OK] Menus backed up" -ForegroundColor Green

# Copy SQLite file
Write-Host "Copying SQLite database file..." -ForegroundColor Yellow
Copy-Item "database\database.sqlite" "$backupDir\database_$timestamp.sqlite"
Write-Host "✓ SQLite file backed up" -ForegroundColor Green

Write-Host ""
Write-Host "=== Backup Complete ===" -ForegroundColor Cyan
Write-Host "Backup location: $backupDir" -ForegroundColor White
Write-Host "Timestamp: $timestamp" -ForegroundColor White
Write-Host ""
Write-Host "Files created:" -ForegroundColor White
Get-ChildItem $backupDir | Where-Object { $_.Name -like "*$timestamp*" } | ForEach-Object {
    Write-Host "  - $($_.Name) ($([math]::Round($_.Length/1KB, 2)) KB)" -ForegroundColor Gray
}
