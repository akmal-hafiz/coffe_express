# Script Sederhana untuk Cari Password PostgreSQL
# Jalankan: .\find-postgres-password.ps1

Write-Host ""
Write-Host "=== PostgreSQL Password Finder ===" -ForegroundColor Cyan
Write-Host ""

# Check service
$pgService = Get-Service | Where-Object { $_.Name -like "*postgres*" }
if ($pgService -and $pgService.Status -eq "Running") {
    Write-Host "[OK] PostgreSQL is running" -ForegroundColor Green
} else {
    Write-Host "[ERROR] PostgreSQL is not running!" -ForegroundColor Red
    Write-Host "Please start it in Laragon first." -ForegroundColor Yellow
    exit 1
}

Write-Host ""
Write-Host "Testing passwords..." -ForegroundColor Yellow
Write-Host ""

# Test passwords
$passwords = @("", "root", "postgres", "admin", "password", "laragon")

foreach ($pwd in $passwords) {
    $displayPwd = if ($pwd -eq "") { "(empty)" } else { $pwd }
    Write-Host "  Testing: $displayPwd ..." -NoNewline
    
    $env:PGPASSWORD = $pwd
    $null = psql -U postgres -w -c "SELECT 1;" 2>&1
    
    if ($LASTEXITCODE -eq 0) {
        Write-Host " SUCCESS!" -ForegroundColor Green
        Write-Host ""
        Write-Host "==================================" -ForegroundColor Green
        Write-Host "PASSWORD FOUND: $displayPwd" -ForegroundColor Green
        Write-Host "==================================" -ForegroundColor Green
        Write-Host ""
        Write-Host "Save this password: $pwd" -ForegroundColor Yellow
        Write-Host ""
        Write-Host "Now run migration with:" -ForegroundColor White
        if ($pwd -eq "") {
            Write-Host "  .\migrate-to-postgresql.ps1 -DbPassword ''" -ForegroundColor Cyan
        } else {
            Write-Host "  .\migrate-to-postgresql.ps1 -DbPassword '$pwd'" -ForegroundColor Cyan
        }
        Write-Host ""
        exit 0
    } else {
        Write-Host " Failed" -ForegroundColor Red
    }
}

Write-Host ""
Write-Host "[ERROR] No password worked!" -ForegroundColor Red
Write-Host ""
Write-Host "Manual steps:" -ForegroundColor Yellow
Write-Host "1. Open Laragon" -ForegroundColor White
Write-Host "2. Right-click PostgreSQL → Open pgAdmin" -ForegroundColor White
Write-Host "3. Set password for 'postgres' user" -ForegroundColor White
Write-Host "4. Try this script again" -ForegroundColor White
Write-Host ""
