# Script Migrasi ke MySQL (Lebih Mudah untuk Development)
# Jalankan: .\migrate-to-mysql.ps1

Write-Host ""
Write-Host "========================================================" -ForegroundColor Cyan
Write-Host "   Migrasi Database: SQLite -> MySQL (Easy Mode)       " -ForegroundColor Cyan
Write-Host "========================================================" -ForegroundColor Cyan
Write-Host ""

# Step 1: Check MySQL
Write-Host "[Step 1] Check MySQL Service" -ForegroundColor Yellow
Write-Host "--------------------------------------------------------" -ForegroundColor DarkGray

$mysqlService = Get-Service | Where-Object { $_.Name -like "*mysql*" }

if ($mysqlService -and $mysqlService.Status -eq "Running") {
    Write-Host "✅ MySQL is running: $($mysqlService.DisplayName)" -ForegroundColor Green
} else {
    Write-Host "⚠️  MySQL is not running!" -ForegroundColor Yellow
    Write-Host ""
    Write-Host "Starting MySQL in Laragon..." -ForegroundColor White
    Write-Host "Please start MySQL manually in Laragon if this fails." -ForegroundColor Gray
    Write-Host ""
    Read-Host "Press Enter after starting MySQL in Laragon"
}

Write-Host ""
Write-Host "Testing MySQL connection..." -ForegroundColor Gray

# Test MySQL connection (Laragon default: no password)
$testResult = mysql -u root -e "SELECT 'OK' as status;" 2>&1

if ($LASTEXITCODE -eq 0) {
    Write-Host "✅ MySQL connection successful!" -ForegroundColor Green
} else {
    Write-Host "❌ Cannot connect to MySQL!" -ForegroundColor Red
    Write-Host ""
    Write-Host "Please check:" -ForegroundColor Yellow
    Write-Host "  1. MySQL service is running in Laragon" -ForegroundColor White
    Write-Host "  2. Default password is empty (no password)" -ForegroundColor White
    exit 1
}

Write-Host ""
Read-Host "Press Enter to continue..."
Write-Host ""

# Step 2: Backup SQLite
Write-Host "[Step 2] Backup SQLite Data" -ForegroundColor Yellow
Write-Host "--------------------------------------------------------" -ForegroundColor DarkGray

if (Test-Path ".\backup-sqlite-data.ps1") {
    & .\backup-sqlite-data.ps1
} else {
    Write-Host "❌ backup-sqlite-data.ps1 not found!" -ForegroundColor Red
    exit 1
}

Write-Host ""
Read-Host "Press Enter to continue..."
Write-Host ""

# Step 3: Create Database
Write-Host "[Step 3] Create MySQL Database" -ForegroundColor Yellow
Write-Host "--------------------------------------------------------" -ForegroundColor DarkGray

Write-Host "Creating database 'coffe_express'..." -ForegroundColor Gray
mysql -u root -e "DROP DATABASE IF EXISTS coffe_express;"
mysql -u root -e "CREATE DATABASE coffe_express CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

if ($LASTEXITCODE -eq 0) {
    Write-Host "✅ Database 'coffe_express' created!" -ForegroundColor Green
} else {
    Write-Host "❌ Failed to create database!" -ForegroundColor Red
    exit 1
}

Write-Host ""
Read-Host "Press Enter to continue..."
Write-Host ""

# Step 4: Update .env
Write-Host "[Step 4] Update .env Configuration" -ForegroundColor Yellow
Write-Host "--------------------------------------------------------" -ForegroundColor DarkGray

if (Test-Path ".env") {
    # Backup .env
    $timestamp = Get-Date -Format "yyyyMMdd_HHmmss"
    Copy-Item ".env" ".env.sqlite.backup_$timestamp"
    Write-Host "✅ Backed up .env to .env.sqlite.backup_$timestamp" -ForegroundColor Green
    
    # Read .env
    $envContent = Get-Content ".env" -Raw
    
    # Replace database config
    $envContent = $envContent -replace "DB_CONNECTION=sqlite", "DB_CONNECTION=mysql"
    $envContent = $envContent -replace "# DB_HOST=127.0.0.1", "DB_HOST=127.0.0.1"
    $envContent = $envContent -replace "# DB_PORT=3306", "DB_PORT=3306"
    $envContent = $envContent -replace "# DB_DATABASE=laravel", "DB_DATABASE=coffe_express"
    $envContent = $envContent -replace "# DB_USERNAME=root", "DB_USERNAME=root"
    $envContent = $envContent -replace "# DB_PASSWORD=", "DB_PASSWORD="
    
    # Save .env
    $envContent | Set-Content ".env" -NoNewline
    Write-Host "✅ Updated .env for MySQL" -ForegroundColor Green
} else {
    Write-Host "❌ .env file not found!" -ForegroundColor Red
    exit 1
}

Write-Host ""
Read-Host "Press Enter to continue..."
Write-Host ""

# Step 5: Run Migrations
Write-Host "[Step 5] Run Migrations" -ForegroundColor Yellow
Write-Host "--------------------------------------------------------" -ForegroundColor DarkGray

Write-Host "Clearing cache..." -ForegroundColor Gray
php artisan config:clear
php artisan cache:clear

Write-Host "Running migrations..." -ForegroundColor Gray
php artisan migrate:fresh --force

if ($LASTEXITCODE -eq 0) {
    Write-Host "✅ Migrations completed!" -ForegroundColor Green
} else {
    Write-Host "❌ Migrations failed!" -ForegroundColor Red
    exit 1
}

Write-Host ""
Read-Host "Press Enter to continue..."
Write-Host ""

# Step 6: Import Data
Write-Host "[Step 6] Import Backup Data" -ForegroundColor Yellow
Write-Host "--------------------------------------------------------" -ForegroundColor DarkGray

Write-Host "Importing data from backup..." -ForegroundColor Gray
php artisan db:seed --class=ImportBackupSeeder

if ($LASTEXITCODE -eq 0) {
    Write-Host "✅ Data import completed!" -ForegroundColor Green
} else {
    Write-Host "❌ Data import failed!" -ForegroundColor Red
    exit 1
}

Write-Host ""
Read-Host "Press Enter to continue..."
Write-Host ""

# Step 7: Verify
Write-Host "[Step 7] Verification" -ForegroundColor Yellow
Write-Host "--------------------------------------------------------" -ForegroundColor DarkGray

Write-Host ""
php artisan db:show
Write-Host ""

Write-Host "Checking data counts..." -ForegroundColor Gray
php artisan tinker --execute="echo 'Users: ' . \App\Models\User::count() . PHP_EOL; echo 'Orders: ' . \App\Models\Order::count() . PHP_EOL; echo 'Menus: ' . \App\Models\Menu::count() . PHP_EOL;"

Write-Host ""
Write-Host "========================================================" -ForegroundColor Green
Write-Host "     Migration to MySQL Completed Successfully!        " -ForegroundColor Green
Write-Host "========================================================" -ForegroundColor Green
Write-Host ""
Write-Host "Database: MySQL (Development)" -ForegroundColor White
Write-Host "Connection: localhost:3306" -ForegroundColor White
Write-Host "Database: coffe_express" -ForegroundColor White
Write-Host "Username: root" -ForegroundColor White
Write-Host "Password: (empty)" -ForegroundColor White
Write-Host ""
Write-Host "Next steps:" -ForegroundColor Yellow
Write-Host "  1. Test your application: php artisan serve" -ForegroundColor Gray
Write-Host "  2. All features should work normally" -ForegroundColor Gray
Write-Host "  3. When deploying to production, use PostgreSQL" -ForegroundColor Gray
Write-Host ""
Write-Host "Note: MySQL is easier for local development!" -ForegroundColor Cyan
Write-Host "      Railway/Render will use PostgreSQL automatically." -ForegroundColor Cyan
Write-Host ""
