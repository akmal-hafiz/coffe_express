<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Coffee Express') }} - Sign In</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Playfair+Display:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        :root {
            --primary-brown: #2D1810;
            --secondary-brown: #4A2C1A;
            --accent-gold: #D4A574;
            --light-cream: #F5F1EB;
            --warm-white: #FEFCF8;
            --text-dark: #1A1A1A;
            --text-medium: #6B6B6B;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, var(--primary-brown) 0%, var(--secondary-brown) 100%);
            min-height: 100vh;
            position: relative;
            overflow-x: hidden;
        }

        .bg-pattern {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: 
                url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23FFFFFF' fill-opacity='0.03'%3E%3Ccircle cx='30' cy='30' r='2'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E"),
                radial-gradient(circle at 20% 20%, rgba(212, 165, 116, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 80% 80%, rgba(212, 165, 116, 0.05) 0%, transparent 50%);
        }

        .main-container {
            position: relative;
            z-index: 1;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 1rem;
        }

        .auth-card {
            background: var(--warm-white);
            border-radius: 24px;
            box-shadow: 
                0 32px 64px rgba(45, 24, 16, 0.2),
                0 16px 32px rgba(45, 24, 16, 0.1),
                inset 0 1px 0 rgba(255, 255, 255, 0.1);
            width: 100%;
            max-width: 480px;
            padding: 3rem;
            position: relative;
            overflow: hidden;
        }

        .auth-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--accent-gold) 0%, var(--primary-brown) 100%);
        }

        .brand-section {
            text-align: center;
            margin-bottom: 2.5rem;
        }

        .brand-logo {
            width: 72px;
            height: 72px;
            background: linear-gradient(135deg, var(--primary-brown) 0%, var(--secondary-brown) 100%);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            box-shadow: 0 8px 24px rgba(45, 24, 16, 0.3);
            position: relative;
        }

        .brand-logo::after {
            content: '';
            position: absolute;
            inset: 2px;
            background: linear-gradient(135deg, var(--secondary-brown) 0%, var(--primary-brown) 100%);
            border-radius: 18px;
        }

        .brand-logo svg {
            position: relative;
            z-index: 1;
        }

        .brand-title {
            font-family: 'Playfair Display', serif;
            font-size: 2rem;
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 0.5rem;
            letter-spacing: -0.02em;
        }

        .brand-subtitle {
            color: var(--text-medium);
            font-size: 1rem;
            font-weight: 400;
            line-height: 1.5;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            display: block;
            font-size: 0.875rem;
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 0.5rem;
            letter-spacing: 0.01em;
        }

        .input-wrapper {
            position: relative;
        }

        .input-icon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-medium);
            z-index: 1;
        }

        .form-input {
            width: 100%;
            padding: 1rem 1rem 1rem 3rem;
            border: 2px solid #E5E7EB;
            border-radius: 12px;
            font-size: 1rem;
            font-weight: 400;
            color: var(--text-dark);
            background: var(--warm-white);
            transition: all 0.2s ease;
            outline: none;
        }

        .form-input::placeholder {
            color: var(--text-medium);
            opacity: 0.7;
        }

        .form-input:focus {
            border-color: var(--accent-gold);
            box-shadow: 0 0 0 4px rgba(212, 165, 116, 0.1);
            background: #FFFFFF;
        }

        .form-options {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 2rem;
        }

        .checkbox-wrapper {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .checkbox-input {
            width: 1rem;
            height: 1rem;
            border: 2px solid #D1D5DB;
            border-radius: 4px;
            background: var(--warm-white);
            cursor: pointer;
        }

        .checkbox-input:checked {
            background: var(--primary-brown);
            border-color: var(--primary-brown);
        }

        .checkbox-label {
            font-size: 0.875rem;
            color: var(--text-medium);
            cursor: pointer;
        }

        .forgot-link {
            font-size: 0.875rem;
            color: var(--accent-gold);
            text-decoration: none;
            font-weight: 500;
            transition: color 0.2s ease;
        }

        .forgot-link:hover {
            color: var(--primary-brown);
        }

        .submit-btn {
            width: 100%;
            padding: 1rem 2rem;
            background: linear-gradient(135deg, var(--primary-brown) 0%, var(--secondary-brown) 100%);
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 16px rgba(45, 24, 16, 0.3);
            margin-bottom: 1.5rem;
        }

        .submit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(45, 24, 16, 0.4);
        }

        .submit-btn:active {
            transform: translateY(0);
        }

        .auth-footer {
            text-align: center;
            padding-top: 1.5rem;
            border-top: 1px solid #E5E7EB;
        }

        .auth-footer p {
            color: var(--text-medium);
            font-size: 0.875rem;
            margin-bottom: 0.5rem;
        }

        .auth-link {
            color: var(--accent-gold);
            text-decoration: none;
            font-weight: 600;
            transition: color 0.2s ease;
        }

        .auth-link:hover {
            color: var(--primary-brown);
        }

        .coffee-decoration {
            position: absolute;
            top: -50px;
            right: -50px;
            width: 100px;
            height: 100px;
            background: linear-gradient(135deg, var(--accent-gold) 0%, transparent 70%);
            border-radius: 50%;
            opacity: 0.1;
        }

        @media (max-width: 640px) {
            .auth-card {
                padding: 2rem 1.5rem;
                margin: 1rem;
                border-radius: 20px;
            }
            
            .brand-title {
                font-size: 1.75rem;
            }
        }
    </style>
</head>
<body>
    <div class="bg-pattern"></div>
    
    <div class="main-container">
        <div class="auth-card">
            <div class="coffee-decoration"></div>
            
            <div class="brand-section">
                <div class="brand-logo">
                    <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M18.5,3H6A3,3 0 0,0 3,6V18A3,3 0 0,0 6,21H18.5A2.5,2.5 0 0,0 21,18.5V5.5A2.5,2.5 0 0,0 18.5,3M18.5,18.5H6A0.5,0.5 0 0,1 5.5,18V6A0.5,0.5 0 0,1 6,5.5H18.5V18.5M12,7A3,3 0 0,0 9,10A3,3 0 0,0 12,13A3,3 0 0,0 15,10A3,3 0 0,0 12,7M12,11A1,1 0 0,1 11,10A1,1 0 0,1 12,9A1,1 0 0,1 13,10A1,1 0 0,1 12,11M12,15C10.67,15 8,15.67 8,17V18H16V17C16,15.67 13.33,15 12,15Z"/>
                    </svg>
                </div>
                <h1 class="brand-title">Welcome Back</h1>
                <p class="brand-subtitle">Sign in to continue your coffee journey</p>
            </div>

            <!-- Session Status -->
            <x-auth-session-status class="mb-4" :status="session('status')" />

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <!-- Email Address -->
                <div class="form-group">
                    <label for="email" class="form-label">Email Address</label>
                    <div class="input-wrapper">
                        <div class="input-icon">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/>
                            </svg>
                        </div>
                        <input id="email" 
                               type="email" 
                               name="email" 
                               value="{{ old('email') }}" 
                               required 
                               autofocus 
                               autocomplete="username"
                               class="form-input"
                               placeholder="Enter your email address">
                    </div>
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <!-- Password -->
                <div class="form-group">
                    <label for="password" class="form-label">Password</label>
                    <div class="input-wrapper">
                        <div class="input-icon">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                        </div>
                        <input id="password" 
                               type="password" 
                               name="password" 
                               required 
                               autocomplete="current-password"
                               class="form-input"
                               placeholder="Enter your password">
                    </div>
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <!-- Remember Me & Forgot Password -->
                <div class="form-options">
                    <div class="checkbox-wrapper">
                        <input id="remember_me" 
                               type="checkbox" 
                               name="remember"
                               class="checkbox-input">
                        <label for="remember_me" class="checkbox-label">Remember me</label>
                    </div>

                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="forgot-link">
                            Forgot password?
                        </a>
                    @endif
                </div>

                <!-- Submit Button -->
                <button type="submit" class="submit-btn">
                    Sign In to Coffee Express
                </button>

                <!-- Register Link -->
                <div class="auth-footer">
                    <p>New to Coffee Express?</p>
                    <a href="{{ route('register') }}" class="auth-link">
                        Create your account
                    </a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
