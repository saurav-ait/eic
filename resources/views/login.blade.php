<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Login - {{ config('app.name', 'EIC') }}</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">

        <!-- Existing Styles -->
        <link rel="stylesheet" href="{{ asset('public/css/styles.css') }}">

        <style>
            /* Login Page Specific Styles */
            body.login-page {
                background: linear-gradient(135deg, #1E4BA6 0%, #0f2f6d 100%);
                display: flex;
                align-items: center;
                justify-content: center;
                min-height: 100vh;
                padding: 20px;
            }

            .login-container {
                background: white;
                padding: 40px;
                border-radius: 10px;
                box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
                width: 100%;
                max-width: 420px;
            }

            .login-header {
                text-align: center;
                margin-bottom: 30px;
            }

            .login-header h1 {
                font-size: 28px;
                color: #1E4BA6;
                margin-bottom: 5px;
            }

            .login-header p {
                color: #666;
                font-size: 14px;
            }

            .login-form label {
                display: block;
                margin-top: 15px;
                font-weight: 600;
                color: #333;
                font-size: 14px;
            }

            .login-form input[type="email"],
            .login-form input[type="password"] {
                width: 100%;
                margin-top: 8px;
                padding: 12px;
                border-radius: 6px;
                border: 1px solid #ddd;
                font-size: 14px;
                font-family: 'Poppins', sans-serif;
                transition: 0.3s;
            }

            .login-form input[type="email"]:focus,
            .login-form input[type="password"]:focus {
                outline: none;
                border-color: #1E4BA6;
                box-shadow: 0 0 0 3px rgba(30, 75, 166, 0.1);
            }

            .error-message {
                color: #e74c3c;
                font-size: 13px;
                margin-top: 5px;
            }

            .form-options {
                display: flex;
                justify-content: space-between;
                align-items: center;
                margin-top: 15px;
                margin-bottom: 20px;
                font-size: 14px;
            }

            .form-options label {
                margin-top: 0;
                font-weight: 400;
                display: flex;
                align-items: center;
            }

            .form-options input[type="checkbox"] {
                margin-right: 8px;
                width: 16px;
                height: 16px;
                cursor: pointer;
            }

            .forgot-password {
                color: #1E4BA6;
                text-decoration: none;
                font-weight: 500;
                transition: 0.3s;
            }

            .forgot-password:hover {
                color: #0f2f6d;
                text-decoration: underline;
            }

            .login-form button {
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

            .login-form button:hover {
                background: #e0b62e;
                transform: translateY(-2px);
                box-shadow: 0 4px 12px rgba(244, 197, 66, 0.3);
            }

            .signup-link {
                text-align: center;
                margin-top: 20px;
                font-size: 14px;
                color: #666;
            }

            .signup-link a {
                color: #1E4BA6;
                text-decoration: none;
                font-weight: 600;
                transition: 0.3s;
            }

            .signup-link a:hover {
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
                .login-container {
                    padding: 30px 20px;
                }

                .login-header h1 {
                    font-size: 24px;
                }

                .form-options {
                    flex-direction: column;
                    align-items: flex-start;
                    gap: 10px;
                }
            }
        </style>
        <script>
            setTimeout(() => {
                document.querySelector('.alert')?.remove();
            }, 4000); // 4 seconds
        </script>

    </head>
    <body class="login-page">
        <div class="login-container">
            <!-- Header -->
            <div class="login-header">
                <h1>Welcome Back</h1>
                <p>Sign in to your account</p>
            </div>

            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Login Form -->
            <form method="POST" action="{{ route('auth-login') }}" class="login-form">
                @csrf

                <!-- Email -->
                <label for="email">Email Address</label>
                <input 
                    type="email" 
                    name="email" 
                    id="email"
                    value="{{ old('email') }}"
                    required
                    autofocus
                    placeholder="you@example.com"
                >
                @error('email')
                    <p class="error-message">{{ $message }}</p>
                @enderror

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

                <!-- Remember Me & Forgot Password -->
                <div class="form-options">
                    <label>
                        <input 
                            type="checkbox" 
                            name="remember" 
                            id="remember"
                            {{ old('remember') ? 'checked' : '' }}
                        >
                        Remember me
                    </label>
                    
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="forgot-password">
                            Forgot password?
                        </a>
                    @endif
                </div>

                <!-- Submit Button -->
                <button type="submit">Sign In</button>
            </form>

            <!-- Register Link -->
            @if (Route::has('register'))
                <div class="signup-link">
                    Don't have an account?
                    <a href="{{ route('register') }}">Sign up</a>
                </div>
            @endif

            <!-- Back to Home -->
            <div class="back-home">
                <a href="/">← Back to home</a>
            </div>
        </div>
    </body>
</html>
