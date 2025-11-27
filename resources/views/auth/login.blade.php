<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Sistem Inventory</title>
    
    <link rel="stylesheet" href="{{ asset('css/login-style.css') }}">
</head>
<body>

    <div class="login-wrapper">

        <div class="welcome-panel">
            <h1>Selamat Datang, Admin!</h1>
            <p>Silakan login untuk melanjutkan.</p>

            <div class="monster-container">
                <div class="monster" id="monster-1">
                    <div class="eye-container">
                        <div class="eye"><div class="pupil"></div></div>
                        <div class="eye"><div class="pupil"></div></div>
                    </div>
                </div>
                <div class="monster" id="monster-2">
                    <div class="eye-container">
                        <div class="eye"><div class="pupil"></div></div>
                        <div class="eye"><div class="pupil"></div></div>
                    </div>
                </div>
                <div class="monster" id="monster-3">
                    <div class="eye-container">
                        <div class="eye"><div class="pupil"></div></div>
                        <div class="eye"><div class="pupil"></div></div>
                    </div>
                </div>
                <div class="monster" id="monster-4">
                    <div class="eye-container">
                        <div class="eye"><div class="pupil"></div></div>
                        <div class="eye"><div class="pupil"></div></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="login-panel">
            
            <form method="POST" action="{{ route('login') }}">
                @csrf <h2>Login</h2>

                @if ($errors->any())
                    <div class="error-message">
                        {{ $errors->first() }}
                    </div>
                @endif

                @csrf
                
                <div class="input-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" value="{{ old('username') }}" required autofocus>
                </div>
                
                <div class="input-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                </div>
                
                <button type="submit" class="btn-login">Masuk</button>
            </form>
        </div>

    </div>

    <script src="{{ asset('js/login-script.js') }}"></script>
</body>
</html>