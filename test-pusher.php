<?php

require __DIR__.'/vendor/autoload.php';

// Load environment variables
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

echo "🔴 TESTING PUSHER CONNECTION\n";
echo "============================\n\n";

echo "BROADCAST_DRIVER: " . $_ENV['BROADCAST_DRIVER'] . "\n";
echo "PUSHER_APP_ID: " . $_ENV['PUSHER_APP_ID'] . "\n";
echo "PUSHER_APP_KEY: " . $_ENV['PUSHER_APP_KEY'] . "\n";
echo "PUSHER_APP_CLUSTER: " . $_ENV['PUSHER_APP_CLUSTER'] . "\n\n";

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

    echo "✅ Pusher instance created successfully!\n\n";

    // Test trigger event
    echo "📤 Sending test event...\n";
    $result = $pusher->trigger('test-channel', 'test-event', [
        'message' => 'Hello from Coffee Express!'
    ]);

    if ($result) {
        echo "✅ Test event sent successfully!\n";
        echo "✅ Pusher connection is working!\n\n";
        echo "🎉 You can now use realtime notifications!\n";
    } else {
        echo "❌ Failed to send test event\n";
    }

} catch (Exception $e) {
    echo "❌ ERROR: " . $e->getMessage() . "\n";
    echo "\nPlease check your Pusher credentials in .env file\n";
}
