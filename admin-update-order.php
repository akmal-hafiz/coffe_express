<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "🔴 SIMULATING ADMIN UPDATE\n";
echo "===========================\n\n";

$order = App\Models\Order::find(5);

if (!$order) {
    echo "❌ Order not found!\n";
    exit(1);
}

echo "Current Order Status: {$order->status}\n";

// Change status to preparing
$order->update(['status' => 'preparing']);

echo "Updated to: preparing\n\n";

// Fire the event (like admin controller does)
echo "🔴 Broadcasting OrderUpdated event...\n";
event(new App\Events\OrderUpdated($order));

echo "✅ Event dispatched!\n\n";

echo "📊 User should see notification:\n";
echo "   Message: ☕ Your coffee is being prepared with love\n";
echo "   Status: Preparing\n";
