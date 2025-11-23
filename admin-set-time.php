<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "🔴 ADMIN: Setting Estimated Time\n";
echo "==================================\n\n";

$order = App\Models\Order::find(5);

if (!$order) {
    echo "❌ Order not found!\n";
    exit(1);
}

$estimatedTime = 20;

echo "Setting estimated time: {$estimatedTime} minutes\n\n";

// Update estimated time
$order->update(['estimated_time' => $estimatedTime]);

// Fire the event with custom message
$message = "⏱️ Estimated time updated: {$estimatedTime} minutes remaining";
echo "🔴 Broadcasting OrderUpdated event...\n";
event(new App\Events\OrderUpdated($order, $message));

echo "✅ Event dispatched!\n\n";

echo "📊 User should see notification:\n";
echo "   Message: {$message}\n";
echo "   Estimated Time: {$estimatedTime} minutes\n";
