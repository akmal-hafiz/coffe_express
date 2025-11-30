<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to Coffee Express!</title>
</head>
<body style="margin: 0; padding: 0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #F5EBDD;">
    <table role="presentation" style="width: 100%; border-collapse: collapse;">
        <tr>
            <td align="center" style="padding: 40px 0;">
                <table role="presentation" style="width: 600px; border-collapse: collapse; background-color: #ffffff; border-radius: 16px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
                    <!-- Header -->
                    <tr>
                        <td style="background: linear-gradient(135deg, #6F4E37 0%, #6B4F4F 100%); padding: 40px; border-radius: 16px 16px 0 0; text-align: center;">
                            <div style="font-size: 60px; margin-bottom: 16px;">☕</div>
                            <h1 style="margin: 0; color: #ffffff; font-size: 32px; font-weight: 700;">Welcome to Coffee Express!</h1>
                            <p style="margin: 10px 0 0; color: rgba(255,255,255,0.8); font-size: 16px;">Your journey to great coffee starts here</p>
                        </td>
                    </tr>

                    <!-- Main Content -->
                    <tr>
                        <td style="padding: 40px;">
                            <!-- Greeting -->
                            <h2 style="margin: 0 0 20px; color: #2C2C2C; font-size: 24px;">
                                Hello, {{ $userName }}! 👋
                            </h2>
                            <p style="margin: 0 0 24px; color: #666666; font-size: 16px; line-height: 1.6;">
                                Thank you for joining Coffee Express! We're thrilled to have you as part of our coffee-loving community. Get ready to experience the finest coffee delivered right to your doorstep.
                            </p>

                            <!-- Features Grid -->
                            <div style="margin-bottom: 30px;">
                                <h3 style="margin: 0 0 20px; color: #6F4E37; font-size: 18px; font-weight: 600;">
                                    🎁 What You Can Do
                                </h3>

                                <!-- Feature 1 -->
                                <div style="background-color: #F9F7F4; border-radius: 12px; padding: 20px; margin-bottom: 12px;">
                                    <table role="presentation" style="width: 100%;">
                                        <tr>
                                            <td style="width: 50px; vertical-align: top;">
                                                <div style="width: 40px; height: 40px; background-color: #6F4E37; border-radius: 10px; text-align: center; line-height: 40px;">
                                                    <span style="color: #ffffff; font-size: 18px;">🛒</span>
                                                </div>
                                            </td>
                                            <td style="vertical-align: top; padding-left: 12px;">
                                                <h4 style="margin: 0 0 4px; color: #2C2C2C; font-size: 16px; font-weight: 600;">Order Your Favorite Coffee</h4>
                                                <p style="margin: 0; color: #888888; font-size: 14px;">Browse our extensive menu and order with just a few clicks</p>
                                            </td>
                                        </tr>
                                    </table>
                                </div>

                                <!-- Feature 2 -->
                                <div style="background-color: #F9F7F4; border-radius: 12px; padding: 20px; margin-bottom: 12px;">
                                    <table role="presentation" style="width: 100%;">
                                        <tr>
                                            <td style="width: 50px; vertical-align: top;">
                                                <div style="width: 40px; height: 40px; background-color: #22C55E; border-radius: 10px; text-align: center; line-height: 40px;">
                                                    <span style="color: #ffffff; font-size: 18px;">🚚</span>
                                                </div>
                                            </td>
                                            <td style="vertical-align: top; padding-left: 12px;">
                                                <h4 style="margin: 0 0 4px; color: #2C2C2C; font-size: 16px; font-weight: 600;">Fast Delivery or Pickup</h4>
                                                <p style="margin: 0; color: #888888; font-size: 14px;">Get your coffee delivered or pick it up at our store</p>
                                            </td>
                                        </tr>
                                    </table>
                                </div>

                                <!-- Feature 3 -->
                                <div style="background-color: #F9F7F4; border-radius: 12px; padding: 20px; margin-bottom: 12px;">
                                    <table role="presentation" style="width: 100%;">
                                        <tr>
                                            <td style="width: 50px; vertical-align: top;">
                                                <div style="width: 40px; height: 40px; background-color: #8B5CF6; border-radius: 10px; text-align: center; line-height: 40px;">
                                                    <span style="color: #ffffff; font-size: 18px;">⭐</span>
                                                </div>
                                            </td>
                                            <td style="vertical-align: top; padding-left: 12px;">
                                                <h4 style="margin: 0 0 4px; color: #2C2C2C; font-size: 16px; font-weight: 600;">Earn Loyalty Points</h4>
                                                <p style="margin: 0; color: #888888; font-size: 14px;">Collect points with every order and redeem exciting rewards</p>
                                            </td>
                                        </tr>
                                    </table>
                                </div>

                                <!-- Feature 4 -->
                                <div style="background-color: #F9F7F4; border-radius: 12px; padding: 20px;">
                                    <table role="presentation" style="width: 100%;">
                                        <tr>
                                            <td style="width: 50px; vertical-align: top;">
                                                <div style="width: 40px; height: 40px; background-color: #F59E0B; border-radius: 10px; text-align: center; line-height: 40px;">
                                                    <span style="color: #ffffff; font-size: 18px;">📱</span>
                                                </div>
                                            </td>
                                            <td style="vertical-align: top; padding-left: 12px;">
                                                <h4 style="margin: 0 0 4px; color: #2C2C2C; font-size: 16px; font-weight: 600;">Real-Time Tracking</h4>
                                                <p style="margin: 0; color: #888888; font-size: 14px;">Track your order status in real-time from preparation to delivery</p>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>

                            <!-- Welcome Bonus -->
                            <div style="background: linear-gradient(135deg, #6F4E37 0%, #6B4F4F 100%); border-radius: 12px; padding: 24px; margin-bottom: 30px; text-align: center;">
                                <p style="margin: 0 0 8px; color: #ffffff; font-size: 14px; font-weight: 600; text-transform: uppercase; letter-spacing: 1px;">
                                    Welcome Gift
                                </p>
                                <p style="margin: 0 0 8px; color: #ffffff; font-size: 28px; font-weight: 700;">
                                    🎉 50 Bonus Points!
                                </p>
                                <p style="margin: 0; color: rgba(255,255,255,0.8); font-size: 14px;">
                                    Start collecting points and unlock amazing rewards
                                </p>
                            </div>

                            <!-- Account Info -->
                            <div style="background-color: #EBF5FF; border-radius: 12px; padding: 20px; margin-bottom: 30px;">
                                <h3 style="margin: 0 0 12px; color: #1E40AF; font-size: 16px; font-weight: 600;">
                                    📧 Your Account Details
                                </h3>
                                <table role="presentation" style="width: 100%;">
                                    <tr>
                                        <td style="padding: 8px 0; color: #3B82F6; font-size: 14px;">Email:</td>
                                        <td style="padding: 8px 0; color: #1E40AF; font-size: 14px; font-weight: 500;">{{ $userEmail }}</td>
                                    </tr>
                                    <tr>
                                        <td style="padding: 8px 0; color: #3B82F6; font-size: 14px;">Member Since:</td>
                                        <td style="padding: 8px 0; color: #1E40AF; font-size: 14px; font-weight: 500;">{{ now()->format('F j, Y') }}</td>
                                    </tr>
                                </table>
                            </div>

                            <!-- CTA Buttons -->
                            <table role="presentation" style="width: 100%;">
                                <tr>
                                    <td align="center" style="padding-bottom: 12px;">
                                        <a href="{{ $menuUrl }}" style="display: inline-block; background-color: #6F4E37; color: #ffffff; text-decoration: none; padding: 16px 40px; border-radius: 8px; font-size: 16px; font-weight: 600;">
                                            ☕ Explore Our Menu
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <td align="center">
                                        <a href="{{ $loginUrl }}" style="display: inline-block; background-color: transparent; color: #6F4E37; text-decoration: none; padding: 12px 32px; border-radius: 8px; font-size: 14px; font-weight: 600; border: 2px solid #6F4E37;">
                                            Login to Your Account
                                        </a>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- Social Links -->
                    <tr>
                        <td style="padding: 0 40px 30px; text-align: center;">
                            <p style="margin: 0 0 16px; color: #888888; font-size: 14px;">Follow us on social media</p>
                            <table role="presentation" style="margin: 0 auto;">
                                <tr>
                                    <td style="padding: 0 8px;">
                                        <a href="#" style="display: inline-block; width: 40px; height: 40px; background-color: #F9F7F4; border-radius: 50%; text-align: center; line-height: 40px; text-decoration: none;">
                                            📘
                                        </a>
                                    </td>
                                    <td style="padding: 0 8px;">
                                        <a href="#" style="display: inline-block; width: 40px; height: 40px; background-color: #F9F7F4; border-radius: 50%; text-align: center; line-height: 40px; text-decoration: none;">
                                            📸
                                        </a>
                                    </td>
                                    <td style="padding: 0 8px;">
                                        <a href="#" style="display: inline-block; width: 40px; height: 40px; background-color: #F9F7F4; border-radius: 50%; text-align: center; line-height: 40px; text-decoration: none;">
                                            🐦
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
                    You received this email because you created an account at Coffee Express.<br>
                    © {{ date('Y') }} Coffee Express. All rights reserved.
                </p>
            </td>
        </tr>
    </table>
</body>
</html>
