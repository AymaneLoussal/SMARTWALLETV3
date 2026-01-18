<?php
/**
 * Login View
 *
 * Displays login form with email and password fields
 * Includes CSRF token protection and error handling
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - <?php echo APP_NAME; ?></title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/style.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .auth-container {
            background: white;
            border-radius: 10px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 400px;
            padding: 40px;
        }

        .auth-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .auth-header h1 {
            color: #333;
            font-size: 28px;
            margin-bottom: 10px;
        }

        .auth-header p {
            color: #666;
            font-size: 14px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #333;
            font-weight: 500;
            font-size: 14px;
        }

        .form-group input {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e0e0e0;
            border-radius: 5px;
            font-size: 14px;
            transition: border-color 0.3s;
        }

        .form-group input:focus {
            outline: none;
            border-color: #667eea;
        }

        .form-group.error input {
            border-color: #e74c3c;
        }

        .error-message {
            color: #e74c3c;
            font-size: 12px;
            margin-top: 5px;
        }

        .alert {
            padding: 12px 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            font-size: 14px;
        }

        .alert-error {
            background-color: #fadbd8;
            color: #c0392b;
            border: 1px solid #e74c3c;
        }

        .alert-success {
            background-color: #d5f4e6;
            color: #27ae60;
            border: 1px solid #2ecc71;
        }

        .btn-login {
            width: 100%;
            padding: 12px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }

        .btn-login:active {
            transform: translateY(0);
        }

        .auth-footer {
            text-align: center;
            margin-top: 20px;
        }

        .auth-footer p {
            color: #666;
            font-size: 14px;
        }

        .auth-footer a {
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s;
        }

        .auth-footer a:hover {
            color: #764ba2;
        }

        .auth-link {
            display: inline-block;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="auth-container">
        <!-- Header -->
        <div class="auth-header">
            <h1><?php echo APP_NAME; ?></h1>
            <p>Access your account</p>
        </div>

        <!-- Error Message -->
        <?php if (!empty($error)): ?>
            <div class="alert alert-error">
                <?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?>
            </div>
        <?php endif; ?>

        <!-- Success Message -->
        <?php if (!empty($success)): ?>
            <div class="alert alert-success">
                <?php echo htmlspecialchars($success, ENT_QUOTES, 'UTF-8'); ?>
            </div>
        <?php endif; ?>

        <!-- Login Form -->
        <form method="POST" action="<?php echo BASE_URL; ?>/auth/handleLogin" novalidate>
            <!-- CSRF Token -->
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf_token, ENT_QUOTES, 'UTF-8'); ?>">

            <!-- Email Field -->
            <div class="form-group">
                <label for="email">Email Address</label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    placeholder="Enter your email"
                    required
                    autocomplete="email"
                >
            </div>

            <!-- Password Field -->
            <div class="form-group">
                <label for="password">Password</label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    placeholder="Enter your password"
                    required
                    autocomplete="current-password"
                >
            </div>

            <!-- Submit Button -->
            <button type="submit" class="btn-login">Login</button>
        </form>

        <!-- Footer -->
        <div class="auth-footer">
            <p>
                Don't have an account?
                <a href="<?php echo BASE_URL; ?>/auth/register" class="auth-link">Register here</a>
            </p>
        </div>
    </div>

    <script>
        // Simple client-side validation
        document.querySelector('form').addEventListener('submit', function(e) {
            const email = document.getElementById('email').value.trim();
            const password = document.getElementById('password').value.trim();

            if (!email || !password) {
                e.preventDefault();
                alert('Please fill in all fields.');
                return false;
            }

            if (!email.includes('@')) {
                e.preventDefault();
                alert('Please enter a valid email address.');
                return false;
            }

            if (password.length < 6) {
                e.preventDefault();
                alert('Password must be at least 6 characters.');
                return false;
            }
        });
    </script>
</body>
</html>
