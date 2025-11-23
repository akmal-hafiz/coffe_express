<?php

require __DIR__.'/vendor/autoload.php';

// Load environment
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

echo "🔴 TESTING DIRECT PUSHER BROADCAST\n";
echo "===================================\n\n";

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

    echo "✅ Pusher connected\n\n";

    // Test broadcast to order channel
    $orderId = 4;
    $channelName = "private-order.{$orderId}";
    $eventName = "order.updated";
    
    $data = [
        'order_id' => $orderId,
        'status' => 'ready',
        'estimated_time' => 15,
        'message' => '🎉 TEST: Your coffee is ready for pickup!',
        'customer_name' => 'Test Customer',
        'total_price' => 50000,
        'pickup_option' => 'pickup'
    ];

    echo "📤 Broadcasting to channel: {$channelName}\n";
    echo "📤 Event: {$eventName}\n";
    echo "📤 Data: " . json_encode($data, JSON_PRETTY_PRINT) . "\n\n";

    $result = $pusher->trigger($channelName, $eventName, $data);

    if ($result) {
        echo "✅ Broadcast successful!\n\n";
        echo "🔍 CHECK USER BROWSER:\n";
        echo "  - Open order-status page\n";
        echo "  - Check console for: 'Order Update Received'\n";
        echo "  - Should see SweetAlert popup!\n\n";
        echo "🔍 CHECK PUSHER DASHBOARD:\n";
        echo "  - https://dashboard.pusher.com/\n";
        echo "  - Debug Console should show the event\n";
    } else {
        echo "❌ Broadcast failed!\n";
    }

} catch (Exception $e) {
    echo "❌ ERROR: " . $e->getMessage() . "\n";
}
