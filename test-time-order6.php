<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "🔴 TESTING ESTIMATED TIME UPDATE - ORDER #6\n";
echo "============================================\n\n";

$order = App\Models\Order::find(6);

if (!$order) {
    echo "❌ Order not found!\n";
    exit(1);
}

$estimatedTime = 15;

echo "Order #6: {$order->customer_name}\n";
echo "Current Status: {$order->status}\n\n";

echo "⏱️ Setting estimated time: {$estimatedTime} minutes\n";
$order->update(['estimated_time' => $estimatedTime]);

$message = "⏱️ Estimated time updated: {$estimatedTime} minutes remaining";

echo "🔴 Broadcasting OrderUpdated event...\n";
event(new App\Events\OrderUpdated($order, $message));

echo "\n✅ Event dispatched!\n\n";

echo "📊 User should see:\n";
echo "   Message: {$message}\n";
echo "   Order ID: 6\n";
