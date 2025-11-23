<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "🔴 TESTING ORDER #6\n";
echo "====================\n\n";

$order = App\Models\Order::find(6);

if (!$order) {
    echo "❌ Order not found!\n";
    exit(1);
}

echo "Order #6 Details:\n";
echo "  Customer: {$order->customer_name}\n";
echo "  Current Status: {$order->status}\n";
echo "  User: {$order->user->email}\n\n";

// Update to READY
echo "📤 Updating status to: READY\n";
$order->update(['status' => 'ready']);

echo "🔴 Broadcasting OrderUpdated event...\n";
event(new App\Events\OrderUpdated($order));

echo "\n✅ Event dispatched!\n\n";

echo "📊 User should see:\n";
echo "   🎉 Your coffee is ready for pickup!\n";
echo "   Status: Ready\n";
echo "   Order ID: 6\n";
