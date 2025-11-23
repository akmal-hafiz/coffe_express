<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Coffee Express') }} - Create Account</title>
    
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
            max-width: 520px;
            padding: 3rem;
            position: relative;
            overflow: hidden;        }

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

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
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

        .password-strength {
            margin-top: 0.5rem;
            font-size: 0.75rem;
            color: var(--text-medium);
        }

        .strength-indicator {
            display: flex;
            gap: 0.25rem;
            margin-top: 0.25rem;
        }

        .strength-bar {
            height: 3px;
            flex: 1;
            background: #E5E7EB;
            border-radius: 2px;
            transition: background-color 0.2s ease;
        }

        .strength-bar.active {
            background: var(--accent-gold);
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

        .terms-notice {
            font-size: 0.75rem;
            color: var(--text-medium);
            text-align: center;
            margin-bottom: 1.5rem;
            line-height: 1.4;
        }

        .terms-notice a {
            color: var(--accent-gold);
            text-decoration: none;
        }

        .terms-notice a:hover {
            text-decoration: underline;
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

            .form-row {
                grid-template-columns: 1fr;
                gap: 0;
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
                        <path d="M12,2A10,10 0 0,0 2,12A10,10 0 0,0 12,22A10,10 0 0,0 22,12A10,10 0 0,0 12,2M12,4A8,8 0 0,1 20,12A8,8 0 0,1 12,20A8,8 0 0,1 4,12A8,8 0 0,1 12,4M12,6A6,6 0 0,0 6,12A6,6 0 0,0 12,18A6,6 0 0,0 18,12A6,6 0 0,0 12,6M12,8A4,4 0 0,1 16,12A4,4 0 0,1 12,16A4,4 0 0,1 8,12A4,4 0 0,1 12,8Z"/>
                    </svg>
                </div>
                <h1 class="brand-title">Join Coffee Express</h1>
                <p class="brand-subtitle">Create your account and discover exceptional coffee experiences</p>
            </div>

            <form method="POST" action="{{ route('register') }}">
                @csrf

                <!-- Name -->
                <div class="form-group">
                    <label for="name" class="form-label">Full Name</label>
                    <div class="input-wrapper">
                        <div class="input-icon">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </div>
                        <input id="name" 
                               type="text" 
                               name="name" 
                               value="{{ old('name') }}" 
                               required 
                               autofocus 
                               autocomplete="name"
                               class="form-input"
                               placeholder="Enter your full name">
                    </div>
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>

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
                               autocomplete="username"
                               class="form-input"
                               placeholder="Enter your email address">
                    </div>
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <!-- Password Row -->
                <div class="form-row">
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
                                   autocomplete="new-password"
                                   class="form-input"
                                   placeholder="Create password">
                        </div>
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <!-- Confirm Password -->
                    <div class="form-group">
                        <label for="password_confirmation" class="form-label">Confirm Password</label>
                        <div class="input-wrapper">
                            <div class="input-icon">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <input id="password_confirmation" 
                                   type="password" 
                                   name="password_confirmation" 
                                   required 
                                   autocomplete="new-password"
                                   class="form-input"
                                   placeholder="Confirm password">
                        </div>
                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                    </div>
                </div>

                <!-- Terms Notice -->
                <div class="terms-notice">
                    By creating an account, you agree to our 
                    <a href="#" class="terms-link">Terms of Service</a> and 
                    <a href="#" class="privacy-link">Privacy Policy</a>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="submit-btn">
                    Create Your Coffee Express Account
                </button>

                <!-- Login Link -->
                <div class="auth-footer">
                    <p>Already have an account?</p>
                    <a href="{{ route('login') }}" class="auth-link">
                        Sign in to your account
                    </a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
