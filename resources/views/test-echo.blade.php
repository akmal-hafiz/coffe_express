<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Test Echo Connection</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background: #f5f5f5;
        }
        .status {
            padding: 20px;
            margin: 10px 0;
            border-radius: 8px;
            background: white;
        }
        .success { border-left: 4px solid #22c55e; }
        .error { border-left: 4px solid #ef4444; }
        .info { border-left: 4px solid #3b82f6; }
        pre {
            background: #1e293b;
            color: #e2e8f0;
            padding: 15px;
            border-radius: 8px;
            overflow-x: auto;
        }
        button {
            background: #6F4E37;
            color: white;
            padding: 12px 24px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 16px;
            margin: 5px;
        }
        button:hover {
            background: #5a3d2b;
        }
    </style>
</head>
<body>
    <h1>🔴 Test Realtime Echo Connection</h1>
    
    <div class="status info">
        <h3>📊 Configuration</h3>
        <pre>PUSHER_APP_KEY: {{ env('PUSHER_APP_KEY') }}
PUSHER_APP_CLUSTER: {{ env('PUSHER_APP_CLUSTER') }}
BROADCAST_DRIVER: {{ env('BROADCAST_DRIVER') }}</pre>
    </div>

    @auth
    <div class="status success">
        <h3>✅ User Authenticated</h3>
        <p>Email: {{ Auth::user()->email }}</p>
        <p>User ID: {{ Auth::user()->id }}</p>
    </div>

    @php
        $testOrder = Auth::user()->orders()->whereIn('status', ['pending', 'preparing', 'ready'])->latest()->first();
    @endphp

    @if($testOrder)
    <div class="status success">
        <h3>✅ Active Order Found</h3>
        <p>Order ID: {{ $testOrder->id }}</p>
        <p>Status: {{ $testOrder->status }}</p>
        <p>Customer: {{ $testOrder->customer_name }}</p>
    </div>

    <div class="status info">
        <h3>🎯 Testing Instructions</h3>
        <p>1. Click "Start Listening" button below</p>
        <p>2. Open new terminal and run:</p>
        <pre>php test-direct-broadcast.php</pre>
        <p>3. You should see a notification popup!</p>
    </div>

    <button onclick="startListening()">🔴 Start Listening</button>
    <button onclick="testBroadcast()">📤 Test Broadcast (Admin)</button>

    <div id="log" style="margin-top: 20px;"></div>

    @else
    <div class="status error">
        <h3>❌ No Active Order</h3>
        <p>Please create an order first from the menu page.</p>
    </div>
    @endif

    @else
    <div class="status error">
        <h3>❌ Not Authenticated</h3>
        <p>Please <a href="{{ route('login') }}">login</a> first.</p>
    </div>
    @endauth

    <!-- Scripts -->
    <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/laravel-echo@1.15.3/dist/echo.iife.js"></script>

    <script>
        function log(message, type = 'info') {
            const logDiv = document.getElementById('log');
            const entry = document.createElement('div');
            entry.className = 'status ' + type;
            entry.innerHTML = '<p>' + message + '</p>';
            logDiv.insertBefore(entry, logDiv.firstChild);
            console.log(message);
        }

        @auth
        @if($testOrder)
        let echoInstance = null;

        function startListening() {
            log('🔴 Initializing Laravel Echo...', 'info');

            try {
                // Initialize Echo
                echoInstance = new Echo({
                    broadcaster: 'pusher',
                    key: '{{ env('PUSHER_APP_KEY') }}',
                    cluster: '{{ env('PUSHER_APP_CLUSTER') }}',
                    forceTLS: true,
                    encrypted: true,
                    authEndpoint: '/broadcasting/auth',
                    auth: {
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    }
                });

                log('✅ Echo initialized', 'success');

                // Listen to order updates
                const orderId = {{ $testOrder->id }};
                const channelName = 'order.' + orderId;

                log('🔴 Subscribing to channel: private-' + channelName, 'info');

                echoInstance.private(channelName)
                    .listen('.order.updated', (event) => {
                        log('🎉 EVENT RECEIVED!', 'success');
                        log('Data: ' + JSON.stringify(event, null, 2), 'success');

                        // Show SweetAlert
                        Swal.fire({
                            icon: 'success',
                            title: 'Order Update!',
                            html: `<div style="text-align: left;">
                                <p><strong>Message:</strong> ${event.message}</p>
                                <p><strong>Status:</strong> ${event.status}</p>
                                <p><strong>Order ID:</strong> ${event.order_id}</p>
                            </div>`,
                            confirmButtonColor: '#6F4E37'
                        });
                    })
                    .subscribed(() => {
                        log('✅ Successfully subscribed to channel!', 'success');
                    })
                    .error((error) => {
                        log('❌ Subscription error: ' + JSON.stringify(error), 'error');
                    });

                log('✅ Listening for updates on order.' + orderId, 'success');

            } catch (error) {
                log('❌ Error: ' + error.message, 'error');
            }
        }

        function testBroadcast() {
            log('📤 Triggering test broadcast...', 'info');
            log('⚠️ This requires admin privileges. Run the PHP script instead.', 'info');
        }

        // Auto-start on page load
        window.addEventListener('load', () => {
            log('📄 Page loaded. Click "Start Listening" to begin.', 'info');
        });
        @endif
        @endauth
    </script>
</body>
</html>
