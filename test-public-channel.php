<?php

require __DIR__.'/vendor/autoload.php';

// Load environment
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

echo "🔴 TESTING PUBLIC CHANNEL (No Auth Required)\n";
echo "=============================================\n\n";

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

    // Test with PUBLIC channel (no auth needed)
    $channelName = "test-channel";
    $eventName = "test-event";
    
    $data = [
        'message' => '🎉 Hello from Public Channel!',
        'timestamp' => date('Y-m-d H:i:s')
    ];

    echo "📤 Broadcasting to PUBLIC channel...\n";
    echo "   Channel: {$channelName}\n";
    echo "   Event: {$eventName}\n\n";

    $result = $pusher->trigger($channelName, $eventName, $data);

    if ($result) {
        echo "✅ BROADCAST SUCCESSFUL!\n\n";
        echo "🔍 To test, add this to your browser console:\n\n";
        echo "var pusher = new Pusher('{$_ENV['PUSHER_APP_KEY']}', {cluster: '{$_ENV['PUSHER_APP_CLUSTER']}'});\n";
        echo "var channel = pusher.subscribe('test-channel');\n";
        echo "channel.bind('test-event', function(data) {\n";
        echo "  console.log('Received:', data);\n";
        echo "  alert('Message: ' + data.message);\n";
        echo "});\n";
    } else {
        echo "❌ Broadcast failed!\n";
    }

} catch (Exception $e) {
    echo "❌ ERROR: " . $e->getMessage() . "\n";
}
