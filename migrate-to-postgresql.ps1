# Script Migrasi Lengkap dari SQLite ke PostgreSQL
# Jalankan: .\migrate-to-postgresql.ps1

param(
    [string]$DbPassword = ""
)

$ErrorActionPreference = "Stop"

Write-Host ""
Write-Host "╔════════════════════════════════════════════════════════╗" -ForegroundColor Cyan
Write-Host "║   Migrasi Database: SQLite → PostgreSQL               ║" -ForegroundColor Cyan
Write-Host "╚════════════════════════════════════════════════════════╝" -ForegroundColor Cyan
Write-Host ""

# Step 1: Backup SQLite Data
Write-Host "📦 Step 1: Backup SQLite Data" -ForegroundColor Yellow
Write-Host "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━" -ForegroundColor DarkGray

if (Test-Path ".\backup-sqlite-data.ps1") {
    & .\backup-sqlite-data.ps1
} else {
    Write-Host "❌ backup-sqlite-data.ps1 not found!" -ForegroundColor Red
    exit 1
}

Write-Host ""
Read-Host "Press Enter to continue to Step 2..."
Write-Host ""

# Step 2: Check PostgreSQL
Write-Host "🔍 Step 2: Check PostgreSQL Connection" -ForegroundColor Yellow
Write-Host "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━" -ForegroundColor DarkGray

if ($DbPassword -eq "") {
    Write-Host "⚠️  PostgreSQL password not provided" -ForegroundColor Yellow
    Write-Host ""
    Write-Host "Please enter your PostgreSQL password:" -ForegroundColor White
    Write-Host "(Common defaults: empty, 'root', or 'postgres')" -ForegroundColor Gray
    $DbPassword = Read-Host "Password"
}

$env:PGPASSWORD = $DbPassword

Write-Host "Testing PostgreSQL connection..." -ForegroundColor Gray
try {
    $result = psql -U postgres -c "SELECT version();" 2>&1
    if ($LASTEXITCODE -eq 0) {
        Write-Host "✅ PostgreSQL connection successful!" -ForegroundColor Green
    } else {
        throw "Connection failed"
    }
} catch {
    Write-Host "❌ Cannot connect to PostgreSQL!" -ForegroundColor Red
    Write-Host ""
    Write-Host "Please check:" -ForegroundColor Yellow
    Write-Host "  1. PostgreSQL service is running in Laragon" -ForegroundColor White
    Write-Host "  2. Password is correct" -ForegroundColor White
    Write-Host "  3. Port 5432 is not blocked" -ForegroundColor White
    exit 1
}

Write-Host ""
Read-Host "Press Enter to continue to Step 3..."
Write-Host ""

# Step 3: Create Database
Write-Host "🗄️  Step 3: Create PostgreSQL Database" -ForegroundColor Yellow
Write-Host "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━" -ForegroundColor DarkGray

Write-Host "Creating database 'coffe_express'..." -ForegroundColor Gray
try {
    psql -U postgres -c "DROP DATABASE IF EXISTS coffe_express;" 2>&1 | Out-Null
    psql -U postgres -c "CREATE DATABASE coffe_express;" 2>&1 | Out-Null
    Write-Host "✅ Database 'coffe_express' created!" -ForegroundColor Green
} catch {
    Write-Host "❌ Failed to create database!" -ForegroundColor Red
    exit 1
}

Write-Host ""
Read-Host "Press Enter to continue to Step 4..."
Write-Host ""

# Step 4: Update .env
Write-Host "⚙️  Step 4: Update .env Configuration" -ForegroundColor Yellow
Write-Host "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━" -ForegroundColor DarkGray

if (Test-Path ".env") {
    # Backup .env
    Copy-Item ".env" ".env.sqlite.backup"
    Write-Host "✅ Backed up .env to .env.sqlite.backup" -ForegroundColor Green
    
    # Read .env
    $envContent = Get-Content ".env" -Raw
    
    # Replace database config
    $envContent = $envContent -replace "DB_CONNECTION=sqlite", "DB_CONNECTION=pgsql"
    $envContent = $envContent -replace "# DB_HOST=127.0.0.1", "DB_HOST=127.0.0.1"
    $envContent = $envContent -replace "# DB_PORT=3306", "DB_PORT=5432"
    $envContent = $envContent -replace "# DB_DATABASE=laravel", "DB_DATABASE=coffe_express"
    $envContent = $envContent -replace "# DB_USERNAME=root", "DB_USERNAME=postgres"
    $envContent = $envContent -replace "# DB_PASSWORD=", "DB_PASSWORD=$DbPassword"
    
    # Ensure uncommented
    if ($envContent -notmatch "DB_HOST=") {
        $envContent = $envContent -replace "DB_CONNECTION=pgsql", "DB_CONNECTION=pgsql`nDB_HOST=127.0.0.1`nDB_PORT=5432`nDB_DATABASE=coffe_express`nDB_USERNAME=postgres`nDB_PASSWORD=$DbPassword"
    }
    
    # Save .env
    $envContent | Set-Content ".env" -NoNewline
    Write-Host "✅ Updated .env for PostgreSQL" -ForegroundColor Green
} else {
    Write-Host "❌ .env file not found!" -ForegroundColor Red
    exit 1
}

Write-Host ""
Read-Host "Press Enter to continue to Step 5..."
Write-Host ""

# Step 5: Clear Cache & Run Migrations
Write-Host "🔄 Step 5: Run Migrations" -ForegroundColor Yellow
Write-Host "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━" -ForegroundColor DarkGray

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
Read-Host "Press Enter to continue to Step 6..."
Write-Host ""

# Step 6: Import Data
Write-Host "📥 Step 6: Import Backup Data" -ForegroundColor Yellow
Write-Host "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━" -ForegroundColor DarkGray

Write-Host "Importing data from backup..." -ForegroundColor Gray
php artisan db:seed --class=ImportBackupSeeder

if ($LASTEXITCODE -eq 0) {
    Write-Host "✅ Data import completed!" -ForegroundColor Green
} else {
    Write-Host "❌ Data import failed!" -ForegroundColor Red
    exit 1
}

Write-Host ""
Read-Host "Press Enter to continue to Step 7..."
Write-Host ""

# Step 7: Verify
Write-Host "✅ Step 7: Verification" -ForegroundColor Yellow
Write-Host "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━" -ForegroundColor DarkGray

Write-Host ""
php artisan db:show
Write-Host ""

Write-Host "Checking data counts..." -ForegroundColor Gray
php artisan tinker --execute="echo 'Users: ' . \App\Models\User::count() . PHP_EOL; echo 'Orders: ' . \App\Models\Order::count() . PHP_EOL; echo 'Menus: ' . \App\Models\Menu::count() . PHP_EOL;"

Write-Host ""
Write-Host "╔════════════════════════════════════════════════════════╗" -ForegroundColor Green
Write-Host "║          🎉 Migration Completed Successfully!         ║" -ForegroundColor Green
Write-Host "╚════════════════════════════════════════════════════════╝" -ForegroundColor Green
Write-Host ""
Write-Host "Next steps:" -ForegroundColor White
Write-Host "  1. Test your application: php artisan serve" -ForegroundColor Gray
Write-Host "  2. Check all features are working" -ForegroundColor Gray
Write-Host "  3. Keep SQLite backup until confirmed working" -ForegroundColor Gray
Write-Host ""
Write-Host "Backup files location: database\backups\" -ForegroundColor Yellow
Write-Host "SQLite backup: .env.sqlite.backup" -ForegroundColor Yellow
Write-Host ""
