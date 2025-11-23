<?php

// Script untuk copy data dari SQLite ke MySQL
// Jalankan: php copy-data-sqlite-to-mysql.php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "\n";
echo "========================================================\n";
echo "   Copy Data: SQLite -> MySQL                          \n";
echo "========================================================\n";
echo "\n";

// Step 1: Connect to SQLite and get data
echo "[Step 1] Reading data from SQLite...\n";

$sqliteConfig = [
    'driver' => 'sqlite',
    'database' => database_path('database.sqlite'),
    'prefix' => '',
];

$sqliteConnection = new \Illuminate\Database\Capsule\Manager;
$sqliteConnection->addConnection($sqliteConfig, 'sqlite_temp');
$sqliteConnection->setAsGlobal();
$sqliteConnection->bootEloquent();

try {
    $sqliteDb = \Illuminate\Support\Facades\DB::connection('sqlite_temp');
    
    $users = $sqliteDb->table('users')->get()->toArray();
    $orders = $sqliteDb->table('orders')->get()->toArray();
    $menus = $sqliteDb->table('menus')->get()->toArray();
    
    echo "  Found:\n";
    echo "    - Users: " . count($users) . "\n";
    echo "    - Orders: " . count($orders) . "\n";
    echo "    - Menus: " . count($menus) . "\n";
    echo "[OK] Data read from SQLite\n\n";
    
} catch (\Exception $e) {
    echo "[ERROR] Failed to read from SQLite: " . $e->getMessage() . "\n";
    exit(1);
}

// Step 2: Insert to MySQL
echo "[Step 2] Inserting data to MySQL...\n";

try {
    $mysqlDb = \Illuminate\Support\Facades\DB::connection('mysql');
    
    // Insert users
    if (count($users) > 0) {
        echo "  - Inserting users...\n";
        foreach ($users as $user) {
            $mysqlDb->table('users')->insert((array)$user);
        }
        echo "  [OK] Inserted " . count($users) . " users\n";
    }
    
    // Insert menus
    if (count($menus) > 0) {
        echo "  - Inserting menus...\n";
        foreach ($menus as $menu) {
            $mysqlDb->table('menus')->insert((array)$menu);
        }
        echo "  [OK] Inserted " . count($menus) . " menus\n";
    }
    
    // Insert orders
    if (count($orders) > 0) {
        echo "  - Inserting orders...\n";
        foreach ($orders as $order) {
            $mysqlDb->table('orders')->insert((array)$order);
        }
        echo "  [OK] Inserted " . count($orders) . " orders\n";
    }
    
    echo "[OK] All data inserted to MySQL\n\n";
    
} catch (\Exception $e) {
    echo "[ERROR] Failed to insert to MySQL: " . $e->getMessage() . "\n";
    exit(1);
}

// Step 3: Verify
echo "[Step 3] Verifying data in MySQL...\n";

$mysqlUsers = $mysqlDb->table('users')->count();
$mysqlOrders = $mysqlDb->table('orders')->count();
$mysqlMenus = $mysqlDb->table('menus')->count();

echo "  MySQL now has:\n";
echo "    - Users: $mysqlUsers\n";
echo "    - Orders: $mysqlOrders\n";
echo "    - Menus: $mysqlMenus\n";

echo "\n";
echo "========================================================\n";
echo "     Data Copy Completed Successfully!                 \n";
echo "========================================================\n";
echo "\n";
