<!doctype html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Lancar Ekspedisi Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Inter', sans-serif; box-sizing: border-box; margin: 0; }

        /* Orange palette from Flutter AppColors */
        :root {
            --orange-500: #F97316;
            --orange-600: #EA580C;
            --orange-400: #FB923C;
            --dark-bg: #0A0A0A;
            --dark-surface: #141414;
            --dark-surface2: #1F1F1F;
            --dark-border: rgba(255,255,255,0.08);
            --text-primary: #F9FAFB;
            --text-secondary: #9CA3AF;
        }

        body {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--dark-bg);
            overflow: hidden;
        }

        /* Animated glow orbs */
        .orb {
            position: fixed;
            border-radius: 50%;
            pointer-events: none;
            filter: blur(80px);
            opacity: 0.15;
        }
        .orb-1 {
            width: 600px; height: 600px;
            background: var(--orange-500);
            top: -200px; left: -200px;
            animation: drift 12s ease-in-out infinite;
        }
        .orb-2 {
            width: 400px; height: 400px;
            background: var(--orange-600);
            bottom: -100px; right: -100px;
            animation: drift 10s ease-in-out infinite reverse;
        }
        @keyframes drift {
            0%, 100% { transform: translate(0,0) scale(1); }
            50% { transform: translate(30px, 30px) scale(1.1); }
        }

        .login-card {
            background: var(--dark-surface);
            border: 1px solid var(--dark-border);
            border-radius: 24px;
            padding: 48px 40px;
            width: 100%;
            max-width: 420px;
            box-shadow: 0 30px 80px rgba(0,0,0,0.6);
            position: relative;
            z-index: 1;
        }

        .brand-logo {
            width: 68px; height: 68px;
            background: linear-gradient(135deg, var(--orange-400), var(--orange-600));
            border-radius: 20px;
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 20px;
            font-size: 28px; color: white;
            box-shadow: 0 8px 30px rgba(249, 115, 22, 0.4);
        }

        .login-card h2 {
            color: var(--text-primary);
            font-size: 22px;
            font-weight: 700;
            text-align: center;
            margin-bottom: 4px;
        }

        .subtitle {
            color: var(--text-secondary);
            text-align: center;
            font-size: 13.5px;
            margin-bottom: 36px;
        }

        .form-label {
            color: #D1D5DB;
            font-size: 13px;
            font-weight: 500;
            margin-bottom: 6px;
            display: block;
        }

        .input-wrap {
            position: relative;
        }
        .input-wrap .input-icon {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #6B7280;
            font-size: 16px;
            z-index: 2;
        }
        .input-wrap input {
            padding-left: 42px;
        }

        .form-control {
            background: var(--dark-surface2) !important;
            border: 1px solid rgba(255,255,255,0.09) !important;
            border-radius: 12px !important;
            color: var(--text-primary) !important;
            padding: 13px 14px;
            font-size: 14px;
            transition: border-color .2s, box-shadow .2s;
            width: 100%;
        }
        .form-control:focus {
            border-color: var(--orange-500) !important;
            box-shadow: 0 0 0 3px rgba(249,115,22,.2) !important;
            outline: none;
        }
        .form-control::placeholder { color: #4B5563 !important; }

        .mb-3 { margin-bottom: 16px; }
        .mb-4 { margin-bottom: 24px; }

        .btn-login {
            background: linear-gradient(135deg, var(--orange-400), var(--orange-600));
            border: none;
            border-radius: 12px;
            color: white;
            font-weight: 700;
            font-size: 15px;
            padding: 14px;
            width: 100%;
            cursor: pointer;
            transition: transform .15s, box-shadow .15s;
            box-shadow: 0 4px 20px rgba(249,115,22,.4);
        }
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 30px rgba(249,115,22,.55);
        }
        .btn-login:active { transform: translateY(0); }
        .btn-login i { margin-right: 8px; }

        .alert-danger {
            background: rgba(220,38,38,.12);
            border: 1px solid rgba(220,38,38,.3);
            color: #F87171;
            border-radius: 10px;
            font-size: 13px;
            padding: 12px 14px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="orb orb-1"></div>
    <div class="orb orb-2"></div>

    <div class="login-card">
        <div class="brand-logo">
            <i class="bi bi-truck-front-fill"></i>
        </div>
        <h2>Lancar Ekspedisiiiiiiiiiiii</h2>
        <p class="subtitle">Masuk ke panel manajemen admin</p>

        @if(session('error'))
            <div class="alert-danger">
                <i class="bi bi-exclamation-triangle-fill"></i>&nbsp;&nbsp;{{ session('error') }}
            </div>
        @endif

        <form method="POST" action="/login">
            @csrf
            <div class="mb-3">
                <label class="form-label">Email</label>
                <div class="input-wrap">
                    <i class="bi bi-envelope input-icon"></i>
                    <input type="email" name="email" class="form-control" placeholder="admin@ekspedisi.com" required>
                </div>
            </div>
            <div class="mb-4">
                <label class="form-label">Password</label>
                <div class="input-wrap">
                    <i class="bi bi-lock input-icon"></i>
                    <input type="password" name="password" class="form-control" placeholder="••••••••" required>
                </div>
            </div>
            <button class="btn-login">
                <i class="bi bi-box-arrow-in-right"></i>Masuk
            </button>
        </form>
    </div>
</body>
</html>
