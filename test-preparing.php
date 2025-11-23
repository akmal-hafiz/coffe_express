<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "🔴 TESTING: Update to PREPARING\n";
echo "================================\n\n";

$order = App\Models\Order::find(6);

if (!$order) {
    echo "❌ Order not found!\n";
    exit(1);
}

echo "Order #6: {$order->customer_name}\n";
echo "Current Status: {$order->status}\n\n";

echo "📤 Updating status to: PREPARING\n";
$order->update(['status' => 'preparing']);

echo "🔴 Broadcasting OrderUpdated event...\n";
event(new App\Events\OrderUpdated($order));

echo "\n✅ Event dispatched!\n\n";

echo "📊 User should see:\n";
echo "   ☕ Your coffee is being prepared with love\n";
echo "   Status: Preparing\n";
echo "   Icon: Info (blue)\n";
