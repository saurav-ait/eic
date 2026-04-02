<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Register - {{ config('app.name', 'EIC') }}</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">

        <!-- Existing Styles -->
        <link rel="stylesheet" href="{{ asset('public/css/styles.css') }}">

        <style>
            /* Register Page Specific Styles */
            body.register-page {
                background: linear-gradient(135deg, #1E4BA6 0%, #0f2f6d 100%);
                display: flex;
                align-items: center;
                justify-content: center;
                min-height: 100vh;
                padding: 20px;
            }

            .register-container {
                background: white;
                padding: 40px;
                border-radius: 10px;
                box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
                width: 100%;
                max-width: 500px;
            }

            .register-header {
                text-align: center;
                margin-bottom: 30px;
            }

            .register-header h1 {
                font-size: 28px;
                color: #1E4BA6;
                margin-bottom: 5px;
            }

            .register-header p {
                color: #666;
                font-size: 14px;
            }

            .register-form label {
                display: block;
                margin-top: 15px;
                font-weight: 600;
                color: #333;
                font-size: 14px;
            }

            .register-form input[type="text"],
            .register-form input[type="email"],
            .register-form input[type="password"] {
                width: 100%;
                margin-top: 8px;
                padding: 12px;
                border-radius: 6px;
                border: 1px solid #ddd;
                font-size: 14px;
                font-family: 'Poppins', sans-serif;
                transition: 0.3s;
                box-sizing: border-box;
            }

            .register-form input[type="text"]:focus,
            .register-form input[type="email"]:focus,
            .register-form input[type="password"]:focus {
                outline: none;
                border-color: #1E4BA6;
                box-shadow: 0 0 0 3px rgba(30, 75, 166, 0.1);
            }

            .error-message {
                color: #e74c3c;
                font-size: 13px;
                margin-top: 5px;
            }

            .form-row {
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 15px;
            }

            @media (max-width: 600px) {
                .form-row {
                    grid-template-columns: 1fr;
                }
            }

            .register-form button {
                margin-top: 25px;
                width: 100%;
                cursor: pointer;
                background: #F4C542;
                color: #111;
                font-weight: bold;
                font-size: 16px;
                border: none;
                padding: 12px;
                border-radius: 6px;
                transition: 0.3s;
            }

            .register-form button:hover {
                background: #e0b62e;
                transform: translateY(-2px);
                box-shadow: 0 4px 12px rgba(244, 197, 66, 0.3);
            }

            .terms-agreement {
                margin-top: 15px;
                font-size: 13px;
                color: #666;
            }

            .terms-agreement a {
                color: #1E4BA6;
                text-decoration: none;
                font-weight: 500;
            }

            .terms-agreement a:hover {
                text-decoration: underline;
            }

            .login-link {
                text-align: center;
                margin-top: 20px;
                font-size: 14px;
                color: #666;
            }

            .login-link a {
                color: #1E4BA6;
                text-decoration: none;
                font-weight: 600;
                transition: 0.3s;
            }

            .login-link a:hover {
                color: #0f2f6d;
                text-decoration: underline;
            }

            .back-home {
                text-align: center;
                margin-top: 20px;
            }

            .back-home a {
                color: white;
                text-decoration: none;
                font-size: 14px;
                transition: 0.3s;
            }

            .back-home a:hover {
                opacity: 0.8;
                text-decoration: underline;
            }

            @media (max-width: 600px) {
                .register-container {
                    padding: 30px 20px;
                }

                .register-header h1 {
                    font-size: 24px;
                }
            }
        </style>
    </head>
    <body class="register-page">
        <div class="register-container">
            <!-- Header -->
            <div class="register-header">
                <h1>Create Account</h1>
                <p>Join us and start your journey</p>
            </div>

            <!-- Register Form -->
            <form method="POST" action="{{ route('auth-register') }}" class="register-form">
                @csrf

                <!-- Name and Email in row -->
                <div class="form-row">
                    <!-- Full Name -->
                    <div>
                        <label for="name">Full Name</label>
                        <input 
                            type="text" 
                            name="name" 
                            id="name"
                            value="{{ old('name') }}"
                            required
                            placeholder="Mr. Someone"
                        >
                        @error('name')
                            <p class="error-message">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email">Email Address</label>
                        <input 
                            type="email" 
                            name="email" 
                            id="email"
                            value="{{ old('email') }}"
                            required
                            placeholder="you@example.com"
                        >
                        @error('email')
                            <p class="error-message">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Password -->
                <label for="password">Password</label>
                <input 
                    type="password" 
                    name="password" 
                    id="password"
                    required
                    placeholder="••••••••"
                >
                @error('password')
                    <p class="error-message">{{ $message }}</p>
                @enderror

                <!-- Confirm Password -->
                <label for="password_confirmation">Confirm Password</label>
                <input 
                    type="password" 
                    name="password_confirmation" 
                    id="password_confirmation"
                    required
                    placeholder="••••••••"
                >
                @error('password_confirmation')
                    <p class="error-message">{{ $message }}</p>
                @enderror

                <!-- Terms Agreement -->
                <div class="terms-agreement">
                    I agree to the <a href="{{ route('terms') ?? '#' }}">Terms of Service</a> and <a href="{{ route('privacy') ?? '#' }}">Privacy Policy</a>
                </div>

                <!-- Submit Button -->
                <button type="submit">Create Account</button>
            </form>

            <!-- Login Link -->
            <div class="login-link">
                Already have an account?
                <a href="{{ route('login') }}">Sign in</a>
            </div>

            <!-- Back to Home -->
            <div class="back-home">
                <a href="/">← Back to home</a>
            </div>
        </div>
    </body>
</html>