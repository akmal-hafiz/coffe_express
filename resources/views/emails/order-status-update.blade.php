<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Status Update - Coffee Express</title>
</head>
<body style="margin: 0; padding: 0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #F5EBDD;">
    <table role="presentation" style="width: 100%; border-collapse: collapse;">
        <tr>
            <td align="center" style="padding: 40px 0;">
                <table role="presentation" style="width: 600px; border-collapse: collapse; background-color: #ffffff; border-radius: 16px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
                    <!-- Header -->
                    <tr>
                        <td style="background: linear-gradient(135deg, #6F4E37 0%, #6B4F4F 100%); padding: 30px 40px; border-radius: 16px 16px 0 0;">
                            <table role="presentation" style="width: 100%;">
                                <tr>
                                    <td>
                                        <h1 style="margin: 0; color: #ffffff; font-size: 28px; font-weight: 700;">☕ Coffee Express</h1>
                                        <p style="margin: 5px 0 0; color: rgba(255,255,255,0.8); font-size: 14px;">Order Status Update</p>
                                    </td>
                                    <td align="right">
                                        <span style="background-color: rgba(255,255,255,0.2); color: #ffffff; padding: 8px 16px; border-radius: 20px; font-size: 14px; font-weight: 600;">
                                            Order #{{ $order->id }}
                                        </span>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- Main Content -->
                    <tr>
                        <td style="padding: 40px;">
                            <!-- Status Badge -->
                            @php
                                $statusColors = [
                                    'pending' => ['bg' => '#F3F4F6', 'text' => '#4B5563', 'icon' => '⏳'],
                                    'preparing' => ['bg' => '#FEF3C7', 'text' => '#D97706', 'icon' => '👨‍🍳'],
                                    'ready' => ['bg' => '#D1FAE5', 'text' => '#059669', 'icon' => '✅'],
                                    'completed' => ['bg' => '#DBEAFE', 'text' => '#2563EB', 'icon' => '🎉'],
                                ];
                                $colors = $statusColors[$currentStatus] ?? $statusColors['pending'];
                            @endphp

                            <div style="text-align: center; margin-bottom: 30px;">
                                <div style="display: inline-block; background-color: {{ $colors['bg'] }}; padding: 16px 32px; border-radius: 50px;">
                                    <span style="font-size: 32px;">{{ $colors['icon'] }}</span>
                                    <span style="color: {{ $colors['text'] }}; font-size: 20px; font-weight: 700; margin-left: 10px; vertical-align: middle;">
                                        {{ $statusText }}
                                    </span>
                                </div>
                            </div>

                            <!-- Greeting -->
                            <h2 style="margin: 0 0 20px; color: #2C2C2C; font-size: 24px; text-align: center;">
                                Hello, {{ $customerName }}! 👋
                            </h2>
                            <p style="margin: 0 0 30px; color: #666666; font-size: 16px; line-height: 1.6; text-align: center;">
                                {{ $statusMessage }}
                            </p>

                            @if($estimatedTime && in_array($currentStatus, ['preparing', 'ready']))
                            <!-- Estimated Time -->
                            <div style="background-color: #FEF3C7; border-radius: 12px; padding: 20px; margin-bottom: 30px; text-align: center;">
                                <p style="margin: 0 0 8px; color: #92400E; font-size: 14px; font-weight: 600;">
                                    ⏰ Estimated Time
                                </p>
                                <p style="margin: 0; color: #D97706; font-size: 28px; font-weight: 700;">
                                    {{ $estimatedTime }} minutes
                                </p>
                            </div>
                            @endif

                            <!-- Status Tracker -->
                            <div style="background-color: #F9F7F4; border-radius: 12px; padding: 24px; margin-bottom: 30px;">
                                <h3 style="margin: 0 0 20px; color: #6F4E37; font-size: 16px; font-weight: 600; text-align: center;">
                                    Order Progress
                                </h3>
                                @php
                                    $statuses = ['pending', 'preparing', 'ready', 'completed'];
                                    $currentIndex = array_search($currentStatus, $statuses);
                                @endphp
                                <table role="presentation" style="width: 100%;">
                                    <tr>
                                        @foreach($statuses as $index => $status)
                                        <td align="center" style="width: 25%;">
                                            @if($index <= $currentIndex)
                                            <div style="width: 40px; height: 40px; background-color: #6F4E37; border-radius: 50%; margin: 0 auto 8px; line-height: 40px; text-align: center;">
                                                <span style="color: #ffffff; font-size: 16px;">✓</span>
                                            </div>
                                            <p style="margin: 0; color: #6F4E37; font-size: 12px; font-weight: 600;">{{ ucfirst($status) }}</p>
                                            @else
                                            <div style="width: 40px; height: 40px; background-color: #E5E0DA; border-radius: 50%; margin: 0 auto 8px;"></div>
                                            <p style="margin: 0; color: #999999; font-size: 12px;">{{ ucfirst($status) }}</p>
                                            @endif
                                        </td>
                                        @endforeach
                                    </tr>
                                </table>
                            </div>

                            <!-- Order Summary -->
                            <div style="background-color: #F9F7F4; border-radius: 12px; padding: 24px; margin-bottom: 30px;">
                                <h3 style="margin: 0 0 16px; color: #6F4E37; font-size: 18px; font-weight: 600;">
                                    📋 Order Summary
                                </h3>

                                <table role="presentation" style="width: 100%; border-collapse: collapse;">
                                    @foreach($orderItems as $item)
                                    <tr>
                                        <td style="padding: 10px 0; border-bottom: 1px solid #E5E0DA;">
                                            <span style="color: #2C2C2C; font-weight: 500;">{{ $item['name'] }}</span>
                                            <span style="color: #888888; font-size: 14px;"> × {{ $item['qty'] }}</span>
                                        </td>
                                        <td align="right" style="padding: 10px 0; border-bottom: 1px solid #E5E0DA;">
                                            <span style="color: #6F4E37; font-weight: 600;">Rp{{ number_format($item['price'] * $item['qty'], 0, ',', '.') }}</span>
                                        </td>
                                    </tr>
                                    @endforeach
                                    <tr>
                                        <td style="padding: 14px 0 0;">
                                            <span style="color: #2C2C2C; font-size: 16px; font-weight: 700;">Total</span>
                                        </td>
                                        <td align="right" style="padding: 14px 0 0;">
                                            <span style="color: #6F4E37; font-size: 18px; font-weight: 700;">Rp{{ number_format($totalPrice, 0, ',', '.') }}</span>
                                        </td>
                                    </tr>
                                </table>
                            </div>

                            <!-- Pickup/Delivery Info -->
                            <table role="presentation" style="width: 100%; border-collapse: collapse; margin-bottom: 30px;">
                                <tr>
                                    <td style="padding: 16px; background-color: {{ $pickupOption === 'pickup' ? '#EBF5FF' : '#F0FDF4' }}; border-radius: 10px;">
                                        <p style="margin: 0 0 4px; color: {{ $pickupOption === 'pickup' ? '#3B82F6' : '#22C55E' }}; font-size: 12px; font-weight: 600; text-transform: uppercase;">
                                            {{ $pickupOption === 'pickup' ? '🏪 Pickup Location' : '🚚 Delivery Address' }}
                                        </p>
                                        <p style="margin: 0; color: {{ $pickupOption === 'pickup' ? '#1E40AF' : '#166534' }}; font-size: 14px; font-weight: 500;">
                                            @if($pickupOption === 'pickup')
                                                Coffee Express Store - Ready for pickup!
                                            @else
                                                {{ $address ?? 'Your registered address' }}
                                            @endif
                                        </p>
                                    </td>
                                </tr>
                            </table>

                            @if($currentStatus === 'ready')
                            <!-- Ready for Pickup Notice -->
                            <div style="background: linear-gradient(135deg, #059669 0%, #10B981 100%); border-radius: 12px; padding: 24px; margin-bottom: 30px; text-align: center;">
                                <p style="margin: 0 0 8px; color: #ffffff; font-size: 24px;">🎉</p>
                                <p style="margin: 0 0 8px; color: #ffffff; font-size: 18px; font-weight: 700;">
                                    Your Order is Ready!
                                </p>
                                <p style="margin: 0; color: rgba(255,255,255,0.9); font-size: 14px;">
                                    @if($pickupOption === 'pickup')
                                        Please come to our store to pick up your order.
                                    @else
                                        Our delivery person is on the way!
                                    @endif
                                </p>
                            </div>
                            @endif

                            @if($currentStatus === 'completed')
                            <!-- Completed - Ask for Review -->
                            <div style="background: linear-gradient(135deg, #6F4E37 0%, #6B4F4F 100%); border-radius: 12px; padding: 24px; margin-bottom: 30px; text-align: center;">
                                <p style="margin: 0 0 8px; color: #ffffff; font-size: 24px;">⭐</p>
                                <p style="margin: 0 0 8px; color: #ffffff; font-size: 18px; font-weight: 700;">
                                    How was your experience?
                                </p>
                                <p style="margin: 0 0 16px; color: rgba(255,255,255,0.9); font-size: 14px;">
                                    We'd love to hear your feedback!
                                </p>
                                <a href="{{ route('reviews.create', $order->id) }}" style="display: inline-block; background-color: #ffffff; color: #6F4E37; text-decoration: none; padding: 12px 28px; border-radius: 8px; font-size: 14px; font-weight: 600;">
                                    Leave a Review
                                </a>
                            </div>
                            @endif

                            <!-- CTA Button -->
                            <table role="presentation" style="width: 100%;">
                                <tr>
                                    <td align="center">
                                        <a href="{{ route('order.status') }}" style="display: inline-block; background-color: #6F4E37; color: #ffffff; text-decoration: none; padding: 14px 32px; border-radius: 8px; font-size: 16px; font-weight: 600;">
                                            View Order Details
                                        </a>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="background-color: #F9F7F4; padding: 30px 40px; border-radius: 0 0 16px 16px; text-align: center;">
                            <p style="margin: 0 0 10px; color: #6F4E37; font-size: 16px; font-weight: 600;">
                                ☕ Coffee Express
                            </p>
                            <p style="margin: 0 0 15px; color: #888888; font-size: 14px;">
                                Bringing quality coffee to your doorstep
                            </p>
                            <p style="margin: 0; color: #AAAAAA; font-size: 12px;">
                                Questions? Reply to this email or contact us at support@coffeeexpress.com
                            </p>
                        </td>
                    </tr>
                </table>

                <!-- Footer Text -->
                <p style="margin: 20px 0 0; color: #999999; font-size: 12px; text-align: center;">
                    You received this email because you placed an order at Coffee Express.
                </p>
            </td>
        </tr>
    </table>
</body>
</html>
