<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $data['title'] ?> | BLISS Admin</title>
    <link rel="stylesheet" href="/php/Webdev/public/css/style.css">
    <link rel="stylesheet" href="/php/Webdev/public/css/auth.css">
    <style>
        @keyframes gradient-animation {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        body {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            margin: 0;
            font-family: 'Inter', -apple-system, sans-serif;
            color: white;
            background: linear-gradient(-45deg, #0f172a, #1e293b, #334155, #1d4ed8);
            background-size: 400% 400%;
            animation: gradient-animation 15s ease infinite;
        }

        .admin-auth-card {
            background: rgba(15, 23, 42, 0.6);
            backdrop-filter: blur(25px) saturate(180%);
            border: 1px solid rgba(255, 255, 255, 0.1);
            padding: 40px;
            border-radius: 24px;
            width: 100%;
            max-width: 400px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.6);
        }
        .admin-auth-card h2 {
            font-size: 1.8rem;
            font-weight: 800;
            margin-bottom: 8px;
            letter-spacing: -0.02em;
        }
        .admin-auth-card p {
            color: #94a3b8;
            margin-bottom: 30px;
            font-size: 0.95rem;
        }
        .form-group label {
            color: #cbd5e1;
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 8px;
            display: block;
        }
        .form-group input {
            background: rgba(15, 23, 42, 0.6);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: white;
            padding: 14px;
            border-radius: 12px;
            width: 100%;
            margin-bottom: 20px;
            font-size: 1rem;
            transition: all 0.2s;
        }
        .form-group input:focus {
            outline: none;
            border-color: #3b82f6;
            background: rgba(30, 41, 59, 0.8);
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
        }
        .btn-admin-login {
            background: linear-gradient(to right, #3b82f6, #6366f1);
            color: white;
            border: none;
            padding: 16px;
            border-radius: 12px;
            width: 100%;
            font-weight: 700;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 10px 20px -5px rgba(59, 130, 246, 0.3);
        }
        .btn-admin-login:hover {
            transform: translateY(-3px) scale(1.02);
            box-shadow: 0 15px 25px -5px rgba(59, 130, 246, 0.4);
            filter: brightness(1.1);
        }
        .error-msg {
            background: rgba(239, 68, 68, 0.1);
            border: 1px solid rgba(239, 68, 68, 0.2);
            color: #f87171;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 0.85rem;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="admin-auth-card">
        <h2>Admin Portal</h2>
        <p>Restricted access for authorized staff only.</p>

        <?php if(isset($_GET['error']) && $_GET['error'] == 'invalid'): ?>
            <div class="error-msg">Invalid credentials or insufficient permissions.</div>
        <?php endif; ?>

        <?php if(isset($_GET['status']) && $_GET['status'] == 'suspended'): ?>
            <div class="error-msg" style="background: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.2); color: #f87171;">
                Your account has been suspended.
            </div>
        <?php endif; ?>

        <?php if(isset($_GET['status']) && $_GET['status'] == 'password_reset'): ?>
            <div class="error-msg" style="background: rgba(16, 185, 129, 0.1); border: 1px solid rgba(16, 185, 129, 0.2); color: #10b981;">
                A password reset was initiated. Please use your new password or contact support.
            </div>
        <?php endif; ?>

        <form action="/php/Webdev/public/adminauth/process_login" method="POST">
            <div class="form-group">
                <label>Staff Email</label>
                <input type="email" name="email" required placeholder="admin@bliss.com">
            </div>
            <div class="form-group">
                <label>Secure Password</label>
                <input type="password" name="password" required placeholder="••••••••">
            </div>
            <button type="submit" class="btn-admin-login">Authorize Access</button>
        </form>
    </div>
</body>
</html>
