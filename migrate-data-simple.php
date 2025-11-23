<?php

// Simple script to migrate data from SQLite to MySQL
echo "\n=== Migrate Data: SQLite -> MySQL ===\n\n";

// SQLite connection
$sqlitePath = __DIR__ . '/database/database.sqlite';
if (!file_exists($sqlitePath)) {
    die("ERROR: SQLite database not found at $sqlitePath\n");
}

try {
    $sqlite = new PDO('sqlite:' . $sqlitePath);
    $sqlite->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "[OK] Connected to SQLite\n";
} catch (PDOException $e) {
    die("ERROR: Cannot connect to SQLite: " . $e->getMessage() . "\n");
}

// MySQL connection
try {
    $mysql = new PDO('mysql:host=127.0.0.1;dbname=coffe_express', 'root', '');
    $mysql->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "[OK] Connected to MySQL\n\n";
} catch (PDOException $e) {
    die("ERROR: Cannot connect to MySQL: " . $e->getMessage() . "\n");
}

// Migrate Users
echo "Migrating users...\n";
$users = $sqlite->query("SELECT * FROM users")->fetchAll(PDO::FETCH_ASSOC);
echo "  Found " . count($users) . " users\n";

if (count($users) > 0) {
    $stmt = $mysql->prepare("INSERT INTO users (id, name, email, email_verified_at, password, remember_token, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    
    foreach ($users as $user) {
        $stmt->execute([
            $user['id'],
            $user['name'],
            $user['email'],
            $user['email_verified_at'] ?? null,
            $user['password'],
            $user['remember_token'] ?? null,
            $user['created_at'],
            $user['updated_at']
        ]);
    }
    echo "  [OK] Inserted " . count($users) . " users\n\n";
}

// Migrate Menus
echo "Migrating menus...\n";
$menus = $sqlite->query("SELECT * FROM menus")->fetchAll(PDO::FETCH_ASSOC);
echo "  Found " . count($menus) . " menus\n";

if (count($menus) > 0) {
    $stmt = $mysql->prepare("INSERT INTO menus (id, name, description, price, category, image, is_active, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    
    foreach ($menus as $menu) {
        $stmt->execute([
            $menu['id'],
            $menu['name'],
            $menu['description'] ?? null,
            $menu['price'],
            $menu['category'] ?? 'coffee',
            $menu['image'] ?? null,
            $menu['is_available'] ?? 1,
            $menu['created_at'],
            $menu['updated_at']
        ]);
    }
    echo "  [OK] Inserted " . count($menus) . " menus\n\n";
}

// Migrate Orders
echo "Migrating orders...\n";
$orders = $sqlite->query("SELECT * FROM orders")->fetchAll(PDO::FETCH_ASSOC);
echo "  Found " . count($orders) . " orders\n";

if (count($orders) > 0) {
    $stmt = $mysql->prepare("INSERT INTO orders (id, user_id, customer_name, phone, address, latitude, longitude, items, total_price, pickup_option, payment_method, status, estimated_time, completed_at, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    
    foreach ($orders as $order) {
        $stmt->execute([
            $order['id'],
            $order['user_id'] ?? null,
            $order['customer_name'] ?? null,
            $order['phone'] ?? null,
            $order['address'] ?? null,
            $order['latitude'] ?? null,
            $order['longitude'] ?? null,
            $order['items'],
            $order['total_price'],
            $order['pickup_option'] ?? 'pickup',
            $order['payment_method'] ?? 'cash',
            $order['status'],
            $order['estimated_time'] ?? null,
            $order['completed_at'] ?? null,
            $order['created_at'],
            $order['updated_at']
        ]);
    }
    echo "  [OK] Inserted " . count($orders) . " orders\n\n";
}

// Verify
echo "Verifying data in MySQL...\n";
$mysqlUsers = $mysql->query("SELECT COUNT(*) FROM users")->fetchColumn();
$mysqlMenus = $mysql->query("SELECT COUNT(*) FROM menus")->fetchColumn();
$mysqlOrders = $mysql->query("SELECT COUNT(*) FROM orders")->fetchColumn();

echo "  Users: $mysqlUsers\n";
echo "  Menus: $mysqlMenus\n";
echo "  Orders: $mysqlOrders\n";

echo "\n=== Migration Completed Successfully! ===\n\n";
