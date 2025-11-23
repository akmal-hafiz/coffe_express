<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "📋 CHECKING ORDERS FOR TESTING\n";
echo "================================\n\n";

$orders = App\Models\Order::with('user')->latest()->take(3)->get();

if ($orders->count() > 0) {
    echo "Found {$orders->count()} orders:\n\n";
    
    foreach ($orders as $order) {
        echo "Order #{$order->id}\n";
        echo "  Customer: {$order->customer_name}\n";
        echo "  Status: {$order->status}\n";
        echo "  User ID: {$order->user_id}\n";
        echo "  User Email: {$order->user->email}\n";
        echo "  Created: {$order->created_at->diffForHumans()}\n";
        echo "\n";
    }
    
    $latestOrder = $orders->first();
    echo "🎯 USE THIS FOR TESTING:\n";
    echo "  Order ID: {$latestOrder->id}\n";
    echo "  User Email: {$latestOrder->user->email}\n";
    echo "  Current Status: {$latestOrder->status}\n";
    
} else {
    echo "❌ No orders found!\n";
    echo "Please create an order first:\n";
    echo "  1. Login as user@coffee.com\n";
    echo "  2. Go to Menu\n";
    echo "  3. Add items to cart\n";
    echo "  4. Checkout\n";
}
