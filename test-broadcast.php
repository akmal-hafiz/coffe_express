<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "🔴 TESTING REALTIME BROADCAST\n";
echo "==============================\n\n";

// Get latest order
$order = App\Models\Order::latest()->first();

if (!$order) {
    echo "❌ No orders found!\n";
    exit(1);
}

echo "Testing with Order #{$order->id}\n";
echo "Customer: {$order->customer_name}\n";
echo "Current Status: {$order->status}\n";
echo "User: {$order->user->email}\n\n";

// Update status to trigger event
echo "📤 Updating status to 'preparing'...\n";
$order->update(['status' => 'preparing']);

// Fire the event
echo "🔴 Broadcasting OrderUpdated event...\n";
event(new App\Events\OrderUpdated($order));

echo "\n✅ Event dispatched!\n\n";

echo "📊 Check the following:\n";
echo "  1. Queue worker terminal (should show 'Processing')\n";
echo "  2. Pusher dashboard (should show event)\n";
echo "  3. User browser console (should show 'Order Update Received')\n";
echo "  4. User should see SweetAlert popup!\n\n";

echo "🎯 To test in browser:\n";
echo "  1. Login as: {$order->user->email}\n";
echo "  2. Go to: http://127.0.0.1:8000/order-status\n";
echo "  3. Keep page open\n";
echo "  4. Run this script again\n";
echo "  5. Watch for popup notification!\n";
