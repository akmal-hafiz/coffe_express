<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation - Coffee Express</title>
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
                                        <p style="margin: 5px 0 0; color: rgba(255,255,255,0.8); font-size: 14px;">Order Confirmation</p>
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
                            <!-- Greeting -->
                            <h2 style="margin: 0 0 20px; color: #2C2C2C; font-size: 24px;">
                                Hello, {{ $customerName }}! 👋
                            </h2>
                            <p style="margin: 0 0 30px; color: #666666; font-size: 16px; line-height: 1.6;">
                                Thank you for your order! We've received your order and will start preparing it shortly. Here's a summary of your order:
                            </p>

                            <!-- Order Details Box -->
                            <div style="background-color: #F9F7F4; border-radius: 12px; padding: 24px; margin-bottom: 30px;">
                                <h3 style="margin: 0 0 16px; color: #6F4E37; font-size: 18px; font-weight: 600;">
                                    📋 Order Details
                                </h3>

                                <!-- Order Items -->
                                <table role="presentation" style="width: 100%; border-collapse: collapse;">
                                    @foreach($orderItems as $item)
                                    <tr>
                                        <td style="padding: 12px 0; border-bottom: 1px solid #E5E0DA;">
                                            <span style="color: #2C2C2C; font-weight: 500;">{{ $item['name'] }}</span>
                                            <span style="color: #888888; font-size: 14px;"> × {{ $item['qty'] }}</span>
                                        </td>
                                        <td align="right" style="padding: 12px 0; border-bottom: 1px solid #E5E0DA;">
                                            <span style="color: #6F4E37; font-weight: 600;">Rp{{ number_format($item['price'] * $item['qty'], 0, ',', '.') }}</span>
                                        </td>
                                    </tr>
                                    @endforeach
                                    <tr>
                                        <td style="padding: 16px 0 0;">
                                            <span style="color: #2C2C2C; font-size: 18px; font-weight: 700;">Total</span>
                                        </td>
                                        <td align="right" style="padding: 16px 0 0;">
                                            <span style="color: #6F4E37; font-size: 20px; font-weight: 700;">Rp{{ number_format($totalPrice, 0, ',', '.') }}</span>
                                        </td>
                                    </tr>
                                </table>
                            </div>

                            <!-- Order Info Cards -->
                            <table role="presentation" style="width: 100%; border-collapse: collapse; margin-bottom: 30px;">
                                <tr>
                                    <td style="width: 50%; padding-right: 10px; vertical-align: top;">
                                        <div style="background-color: #EBF5FF; border-radius: 10px; padding: 16px;">
                                            <p style="margin: 0 0 4px; color: #3B82F6; font-size: 12px; font-weight: 600; text-transform: uppercase;">
                                                {{ $pickupOption === 'pickup' ? '🏪 Pickup' : '🚚 Delivery' }}
                                            </p>
                                            <p style="margin: 0; color: #1E40AF; font-size: 14px; font-weight: 500;">
                                                {{ $pickupOption === 'pickup' ? 'Pick up at store' : 'Delivery to your address' }}
                                            </p>
                                        </div>
                                    </td>
                                    <td style="width: 50%; padding-left: 10px; vertical-align: top;">
                                        <div style="background-color: #F0FDF4; border-radius: 10px; padding: 16px;">
                                            <p style="margin: 0 0 4px; color: #22C55E; font-size: 12px; font-weight: 600; text-transform: uppercase;">
                                                💳 Payment
                                            </p>
                                            <p style="margin: 0; color: #166534; font-size: 14px; font-weight: 500;">
                                                {{ ucfirst(str_replace('_', ' ', $paymentMethod)) }}
                                            </p>
                                        </div>
                                    </td>
                                </tr>
                            </table>

                            @if($pickupOption === 'delivery' && $address)
                            <!-- Delivery Address -->
                            <div style="background-color: #FEF3C7; border-radius: 10px; padding: 16px; margin-bottom: 30px;">
                                <p style="margin: 0 0 4px; color: #D97706; font-size: 12px; font-weight: 600; text-transform: uppercase;">
                                    📍 Delivery Address
                                </p>
                                <p style="margin: 0; color: #92400E; font-size: 14px; line-height: 1.5;">
                                    {{ $address }}
                                </p>
                            </div>
                            @endif

                            <!-- Status Tracker -->
                            <div style="background-color: #F9F7F4; border-radius: 12px; padding: 24px; margin-bottom: 30px;">
                                <h3 style="margin: 0 0 20px; color: #6F4E37; font-size: 16px; font-weight: 600;">
                                    Order Status
                                </h3>
                                <table role="presentation" style="width: 100%;">
                                    <tr>
                                        <td align="center" style="width: 25%;">
                                            <div style="width: 40px; height: 40px; background-color: #6F4E37; border-radius: 50%; margin: 0 auto 8px; display: flex; align-items: center; justify-content: center;">
                                                <span style="color: #ffffff; font-size: 16px;">✓</span>
                                            </div>
                                            <p style="margin: 0; color: #6F4E37; font-size: 12px; font-weight: 600;">Received</p>
                                        </td>
                                        <td align="center" style="width: 25%;">
                                            <div style="width: 40px; height: 40px; background-color: #E5E0DA; border-radius: 50%; margin: 0 auto 8px;"></div>
                                            <p style="margin: 0; color: #999999; font-size: 12px;">Preparing</p>
                                        </td>
                                        <td align="center" style="width: 25%;">
                                            <div style="width: 40px; height: 40px; background-color: #E5E0DA; border-radius: 50%; margin: 0 auto 8px;"></div>
                                            <p style="margin: 0; color: #999999; font-size: 12px;">Ready</p>
                                        </td>
                                        <td align="center" style="width: 25%;">
                                            <div style="width: 40px; height: 40px; background-color: #E5E0DA; border-radius: 50%; margin: 0 auto 8px;"></div>
                                            <p style="margin: 0; color: #999999; font-size: 12px;">Completed</p>
                                        </td>
                                    </tr>
                                </table>
                            </div>

                            <!-- CTA Button -->
                            <table role="presentation" style="width: 100%;">
                                <tr>
                                    <td align="center">
                                        <a href="{{ route('order.status') }}" style="display: inline-block; background-color: #6F4E37; color: #ffffff; text-decoration: none; padding: 14px 32px; border-radius: 8px; font-size: 16px; font-weight: 600;">
                                            Track Your Order
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

                <!-- Unsubscribe Link -->
                <p style="margin: 20px 0 0; color: #999999; font-size: 12px; text-align: center;">
                    You received this email because you placed an order at Coffee Express.
                </p>
            </td>
        </tr>
    </table>
</body>
</html>
