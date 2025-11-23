<?php

require __DIR__.'/vendor/autoload.php';

// Load environment
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

echo "🔴 BROADCASTING TO ORDER #5\n";
echo "============================\n\n";

try {
    $pusher = new Pusher\Pusher(
        $_ENV['PUSHER_APP_KEY'],
        $_ENV['PUSHER_APP_SECRET'],
        $_ENV['PUSHER_APP_ID'],
        [
            'cluster' => $_ENV['PUSHER_APP_CLUSTER'],
            'useTLS' => true
        ]
    );

    echo "✅ Pusher connected\n";
    echo "   App ID: {$_ENV['PUSHER_APP_ID']}\n";
    echo "   Key: {$_ENV['PUSHER_APP_KEY']}\n";
    echo "   Cluster: {$_ENV['PUSHER_APP_CLUSTER']}\n\n";

    // Broadcast to order #5
    $orderId = 5;
    $channelName = "private-order.{$orderId}";
    $eventName = "order.updated";
    
    $data = [
        'order_id' => $orderId,
        'status' => 'ready',
        'estimated_time' => 10,
        'message' => '🎉 Your coffee is ready for pickup!',
        'customer_name' => 'mahda',
        'total_price' => 50000,
        'pickup_option' => 'pickup'
    ];

    echo "📤 Broadcasting...\n";
    echo "   Channel: {$channelName}\n";
    echo "   Event: {$eventName}\n";
    echo "   Message: {$data['message']}\n\n";

    $result = $pusher->trigger($channelName, $eventName, $data);

    if ($result) {
        echo "✅ BROADCAST SUCCESSFUL!\n\n";
        echo "🔍 NOW CHECK:\n";
        echo "   1. Browser at: http://127.0.0.1:8000/test-echo\n";
        echo "   2. Click 'Start Listening' button\n";
        echo "   3. Run this script again\n";
        echo "   4. You should see SweetAlert popup!\n\n";
        echo "📊 Pusher Dashboard:\n";
        echo "   https://dashboard.pusher.com/\n";
        echo "   Check Debug Console for the event\n";
    } else {
        echo "❌ Broadcast failed!\n";
    }

} catch (Exception $e) {
    echo "❌ ERROR: " . $e->getMessage() . "\n";
    echo "\nStack trace:\n";
    echo $e->getTraceAsString() . "\n";
}
