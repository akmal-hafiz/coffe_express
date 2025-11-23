<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "🔴 TRIGGERING REALTIME UPDATE\n";
echo "==============================\n\n";

$order = App\Models\Order::find(4);

if (!$order) {
    echo "❌ Order not found!\n";
    exit(1);
}

echo "Current Status: {$order->status}\n";

// Cycle through statuses
$statuses = ['pending', 'preparing', 'ready', 'completed'];
$currentIndex = array_search($order->status, $statuses);
$nextIndex = ($currentIndex + 1) % count($statuses);
$newStatus = $statuses[$nextIndex];

echo "Updating to: {$newStatus}\n\n";

$order->update(['status' => $newStatus]);
event(new App\Events\OrderUpdated($order));

echo "✅ Update sent!\n";
echo "🔔 User should see notification NOW!\n\n";

echo "Messages by status:\n";
echo "  - pending: ☕ Your order has been received\n";
echo "  - preparing: ☕ Your coffee is being prepared with love\n";
echo "  - ready: 🎉 Your coffee is ready for pickup!\n";
echo "  - completed: ✅ Order completed\n";
