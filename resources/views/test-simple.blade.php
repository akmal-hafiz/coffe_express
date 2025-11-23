<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Simple Pusher Test</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body style="font-family: Arial; padding: 50px;">
    <h1>🔴 Simple Pusher Test</h1>
    
    @auth
    <p>✅ Logged in as: {{ Auth::user()->email }}</p>
    
    @php
        $order = Auth::user()->orders()->whereIn('status', ['pending', 'preparing', 'ready'])->latest()->first();
    @endphp
    
    @if($order)
    <p>✅ Order ID: {{ $order->id }}</p>
    <button onclick="testPublic()" style="padding: 10px 20px; margin: 5px; background: #3b82f6; color: white; border: none; border-radius: 5px; cursor: pointer;">
        Test Public Channel
    </button>
    <button onclick="testPrivate()" style="padding: 10px 20px; margin: 5px; background: #6F4E37; color: white; border: none; border-radius: 5px; cursor: pointer;">
        Test Private Channel
    </button>
    
    <div id="log" style="margin-top: 20px; padding: 20px; background: #f5f5f5; border-radius: 8px; font-family: monospace; font-size: 12px;"></div>
    @else
    <p>❌ No active order</p>
    @endif
    @else
    <p>❌ Not logged in. <a href="{{ route('login') }}">Login</a></p>
    @endauth

    <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/laravel-echo@1.15.3/dist/echo.iife.js"></script>
    
    <script>
        function log(msg) {
            const logDiv = document.getElementById('log');
            const time = new Date().toLocaleTimeString();
            logDiv.innerHTML += `[${time}] ${msg}<br>`;
            console.log(msg);
        }

        @if($order ?? false)
        // Test Public Channel
        function testPublic() {
            log('🔵 Testing PUBLIC channel...');
            
            const pusher = new Pusher('{{ env('PUSHER_APP_KEY') }}', {
                cluster: '{{ env('PUSHER_APP_CLUSTER') }}'
            });

            const channel = pusher.subscribe('test-channel');
            
            channel.bind('pusher:subscription_succeeded', () => {
                log('✅ Subscribed to PUBLIC channel');
            });
            
            channel.bind('test-event', (data) => {
                log('🎉 PUBLIC EVENT RECEIVED: ' + JSON.stringify(data));
                Swal.fire({
                    icon: 'success',
                    title: 'Public Channel Works!',
                    text: data.message
                });
            });
        }

        // Test Private Channel
        function testPrivate() {
            log('🔴 Testing PRIVATE channel...');
            
            window.Echo = new Echo({
                broadcaster: 'pusher',
                key: '{{ env('PUSHER_APP_KEY') }}',
                cluster: '{{ env('PUSHER_APP_CLUSTER') }}',
                forceTLS: true,
                authEndpoint: '/broadcasting/auth',
                auth: {
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                }
            });

            log('🔴 Subscribing to private-order.{{ $order->id }}...');

            window.Echo.private('order.{{ $order->id }}')
                .subscribed(() => {
                    log('✅ Subscribed to PRIVATE channel!');
                })
                .listen('.order.updated', (event) => {
                    log('🎉 PRIVATE EVENT RECEIVED: ' + JSON.stringify(event));
                    Swal.fire({
                        icon: 'success',
                        title: 'Private Channel Works!',
                        text: event.message
                    });
                })
                .error((error) => {
                    log('❌ ERROR: ' + JSON.stringify(error));
                });
        }
        @endif
    </script>
</body>
</html>
