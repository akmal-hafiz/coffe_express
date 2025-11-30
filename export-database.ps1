# Export Database MySQL
# Script untuk backup database Coffee Express

$DB_NAME = "coffe_express"
$DB_USER = "root"
$DB_PASSWORD = ""  # Isi jika ada password
$BACKUP_FILE = "backup_$(Get-Date -Format 'yyyyMMdd_HHmmss').sql"

Write-Host "Exporting database: $DB_NAME" -ForegroundColor Cyan

try {
    if ($DB_PASSWORD -eq "") {
        mysqldump -u $DB_USER $DB_NAME > $BACKUP_FILE
    } else {
        mysqldump -u $DB_USER -p$DB_PASSWORD $DB_NAME > $BACKUP_FILE
    }
    
    if ($LASTEXITCODE -eq 0) {
        $fileSize = (Get-Item $BACKUP_FILE).Length / 1KB
        Write-Host "Backup berhasil!" -ForegroundColor Green
        Write-Host "File: $BACKUP_FILE ($([math]::Round($fileSize, 2)) KB)" -ForegroundColor Green
        Write-Host ""
        Write-Host "Untuk restore:" -ForegroundColor Yellow
        Write-Host "mysql -u root -p coffe_express < $BACKUP_FILE" -ForegroundColor Yellow
    } else {
        throw "Export gagal"
    }
} catch {
    Write-Host "Error: $_" -ForegroundColor Red
    Write-Host "Pastikan MySQL sudah berjalan dan database ada" -ForegroundColor Yellow
}
