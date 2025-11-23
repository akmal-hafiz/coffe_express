<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "🔴 ADMIN: Setting Order to READY\n";
echo "=================================\n\n";

$order = App\Models\Order::find(5);

if (!$order) {
    echo "❌ Order not found!\n";
    exit(1);
}

echo "Current Status: {$order->status}\n";

// Change status to ready
$order->update(['status' => 'ready']);

echo "Updated to: READY\n\n";

// Fire the event
echo "🔴 Broadcasting OrderUpdated event...\n";
event(new App\Events\OrderUpdated($order));

echo "✅ Event dispatched!\n\n";

echo "📊 User should see notification:\n";
echo "   Message: 🎉 Your coffee is ready for pickup!\n";
echo "   Status: Ready\n";
echo "   Icon: Success (green)\n";
