# Script untuk Test dan Set Password PostgreSQL
# Jalankan: .\setup-postgresql-password.ps1

Write-Host ""
Write-Host "╔════════════════════════════════════════════════════════╗" -ForegroundColor Cyan
Write-Host "║        PostgreSQL Password Setup & Test                ║" -ForegroundColor Cyan
Write-Host "╚════════════════════════════════════════════════════════╝" -ForegroundColor Cyan
Write-Host ""

# Check PostgreSQL service
Write-Host "🔍 Checking PostgreSQL service..." -ForegroundColor Yellow
$pgService = Get-Service | Where-Object { $_.Name -like "*postgres*" }

if ($pgService -and $pgService.Status -eq "Running") {
    Write-Host "✅ PostgreSQL service is running: $($pgService.DisplayName)" -ForegroundColor Green
} else {
    Write-Host "❌ PostgreSQL service is not running!" -ForegroundColor Red
    Write-Host ""
    Write-Host "Please start PostgreSQL in Laragon:" -ForegroundColor Yellow
    Write-Host "  1. Open Laragon" -ForegroundColor White
    Write-Host "  2. Click Menu → PostgreSQL → Start" -ForegroundColor White
    exit 1
}

Write-Host ""
Write-Host "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━" -ForegroundColor DarkGray
Write-Host ""

# Test common passwords
Write-Host "🔐 Testing common PostgreSQL passwords..." -ForegroundColor Yellow
Write-Host ""

$commonPasswords = @(
    @{Name="Empty (no password)"; Value=""},
    @{Name="root"; Value="root"},
    @{Name="postgres"; Value="postgres"},
    @{Name="admin"; Value="admin"},
    @{Name="password"; Value="password"}
)

$foundPassword = $null

foreach ($pwd in $commonPasswords) {
    Write-Host "  Testing: $($pwd.Name)..." -NoNewline -ForegroundColor Gray
    
    $env:PGPASSWORD = $pwd.Value
    $result = psql -U postgres -w -c "SELECT 1;" 2>&1
    
    if ($LASTEXITCODE -eq 0) {
        Write-Host " ✅ SUCCESS!" -ForegroundColor Green
        $foundPassword = $pwd.Value
        break
    } else {
        Write-Host " ❌ Failed" -ForegroundColor Red
    }
}

Write-Host ""
Write-Host "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━" -ForegroundColor DarkGray
Write-Host ""

if ($foundPassword -ne $null) {
    Write-Host "🎉 Password found!" -ForegroundColor Green
    Write-Host ""
    
    if ($foundPassword -eq "") {
        Write-Host "Your PostgreSQL password is: (empty/no password)" -ForegroundColor White
        $displayPassword = ""
    } else {
        Write-Host "Your PostgreSQL password is: $foundPassword" -ForegroundColor White
        $displayPassword = $foundPassword
    }
    
    Write-Host ""
    Write-Host "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━" -ForegroundColor DarkGray
    Write-Host ""
    
    # Ask to update .env
    $updateEnv = Read-Host "Do you want to update .env file now? (Y/n)"
    
    if ($updateEnv -eq "" -or $updateEnv -eq "Y" -or $updateEnv -eq "y") {
        Write-Host ""
        Write-Host "📝 Updating .env file..." -ForegroundColor Yellow
        
        if (Test-Path ".env") {
            # Backup .env
            Copy-Item ".env" ".env.backup_$(Get-Date -Format 'yyyyMMdd_HHmmss')"
            Write-Host "✅ Backed up .env" -ForegroundColor Green
            
            # Read .env
            $envContent = Get-Content ".env" -Raw
            
            # Update database config
            if ($envContent -match "DB_CONNECTION=sqlite") {
                $envContent = $envContent -replace "DB_CONNECTION=sqlite", "DB_CONNECTION=pgsql"
                
                # Add PostgreSQL config if not exists
                if ($envContent -notmatch "DB_HOST=") {
                    $dbConfig = "DB_HOST=127.0.0.1`nDB_PORT=5432`nDB_DATABASE=coffe_express`nDB_USERNAME=postgres`nDB_PASSWORD=$displayPassword"
                    $envContent = $envContent -replace "DB_CONNECTION=pgsql", "DB_CONNECTION=pgsql`n$dbConfig"
                } else {
                    # Update existing config
                    $envContent = $envContent -replace "# DB_HOST=.*", "DB_HOST=127.0.0.1"
                    $envContent = $envContent -replace "# DB_PORT=.*", "DB_PORT=5432"
                    $envContent = $envContent -replace "# DB_DATABASE=.*", "DB_DATABASE=coffe_express"
                    $envContent = $envContent -replace "# DB_USERNAME=.*", "DB_USERNAME=postgres"
                    $envContent = $envContent -replace "# DB_PASSWORD=.*", "DB_PASSWORD=$displayPassword"
                }
            }
            
            # Save .env
            $envContent | Set-Content ".env" -NoNewline
            Write-Host "✅ Updated .env for PostgreSQL" -ForegroundColor Green
            
            Write-Host ""
            Write-Host "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━" -ForegroundColor DarkGray
            Write-Host ""
            Write-Host "✨ Next steps:" -ForegroundColor Cyan
            Write-Host ""
            Write-Host "1. Run migration script:" -ForegroundColor White
            Write-Host "   .\migrate-to-postgresql.ps1 -DbPassword '$displayPassword'" -ForegroundColor Yellow
            Write-Host ""
            Write-Host "   OR manually:" -ForegroundColor White
            Write-Host "   .\backup-sqlite-data.ps1" -ForegroundColor Yellow
            Write-Host "   psql -U postgres -c `"CREATE DATABASE coffe_express;`"" -ForegroundColor Yellow
            Write-Host "   php artisan migrate:fresh" -ForegroundColor Yellow
            Write-Host "   php artisan db:seed --class=ImportBackupSeeder" -ForegroundColor Yellow
            Write-Host ""
        } else {
            Write-Host "❌ .env file not found!" -ForegroundColor Red
        }
    }
    
} else {
    Write-Host "❌ Could not find password automatically" -ForegroundColor Red
    Write-Host ""
    Write-Host "Please try manually:" -ForegroundColor Yellow
    Write-Host ""
    Write-Host "1. Check Laragon PostgreSQL settings" -ForegroundColor White
    Write-Host "2. Or set password manually:" -ForegroundColor White
    Write-Host ""
    Write-Host "   # Open pgAdmin from Laragon" -ForegroundColor Gray
    Write-Host "   # Right-click 'postgres' user → Properties → Definition" -ForegroundColor Gray
    Write-Host "   # Set a new password (e.g., 'root')" -ForegroundColor Gray
    Write-Host ""
    Write-Host "3. Then update .env manually:" -ForegroundColor White
    Write-Host ""
    Write-Host "   DB_CONNECTION=pgsql" -ForegroundColor Yellow
    Write-Host "   DB_HOST=127.0.0.1" -ForegroundColor Yellow
    Write-Host "   DB_PORT=5432" -ForegroundColor Yellow
    Write-Host "   DB_DATABASE=coffe_express" -ForegroundColor Yellow
    Write-Host "   DB_USERNAME=postgres" -ForegroundColor Yellow
    Write-Host "   DB_PASSWORD=your_password_here" -ForegroundColor Yellow
    Write-Host ""
}

Write-Host ""
