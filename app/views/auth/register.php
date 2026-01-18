<?php
/**
 * Register View
 *
 * Displays registration form for new users
 * Includes CSRF token protection and error handling
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - <?php echo APP_NAME; ?></title>
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
            padding: 20px;
        }

        .auth-container {
            background: white;
            border-radius: 10px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 450px;
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
            display: block;
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

        .alert-info {
            background-color: #d6eaf8;
            color: #1f618d;
            border: 1px solid #3498db;
        }

        .btn-register {
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

        .btn-register:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }

        .btn-register:active {
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

        .password-requirements {
            background-color: #ecf0f1;
            border-left: 4px solid #667eea;
            padding: 12px 15px;
            border-radius: 3px;
            margin-top: 15px;
            font-size: 13px;
            color: #555;
        }

        .password-requirements ul {
            margin-left: 20px;
            margin-top: 8px;
        }

        .password-requirements li {
            margin-bottom: 4px;
        }
    </style>
</head>
<body>
    <div class="auth-container">
        <!-- Header -->
        <div class="auth-header">
            <h1><?php echo APP_NAME; ?></h1>
            <p>Create your account</p>
        </div>

        <!-- Errors Display -->
        <?php if (!empty($errors)): ?>
            <div class="alert alert-error">
                <strong>Registration failed:</strong>
                <ul style="margin-left: 20px; margin-top: 10px;">
                    <?php foreach ($errors as $field => $error): ?>
                        <li><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <!-- Info Message -->
        <div class="alert alert-info">
            <strong>Password Requirements:</strong> Minimum 6 characters
        </div>

        <!-- Registration Form -->
        <form method="POST" action="<?php echo BASE_URL; ?>/auth/handleRegister" novalidate>
            <!-- CSRF Token -->
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf_token, ENT_QUOTES, 'UTF-8'); ?>">

            <!-- Full Name Field -->
            <div class="form-group">
                <label for="full_name">Full Name</label>
                <input
                    type="text"
                    id="full_name"
                    name="full_name"
                    placeholder="Enter your full name"
                    value="<?php echo htmlspecialchars($old['full_name'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                    required
                >
                <?php if (isset($errors['full_name'])): ?>
                    <span class="error-message"><?php echo htmlspecialchars($errors['full_name'], ENT_QUOTES, 'UTF-8'); ?></span>
                <?php endif; ?>
            </div>

            <!-- Email Field -->
            <div class="form-group">
                <label for="email">Email Address</label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    placeholder="Enter your email"
                    value="<?php echo htmlspecialchars($old['email'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                    required
                    autocomplete="email"
                >
                <?php if (isset($errors['email'])): ?>
                    <span class="error-message"><?php echo htmlspecialchars($errors['email'], ENT_QUOTES, 'UTF-8'); ?></span>
                <?php endif; ?>
            </div>

            <!-- Password Field -->
            <div class="form-group">
                <label for="password">Password</label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    placeholder="Enter password (min. 6 characters)"
                    required
                    autocomplete="new-password"
                >
                <?php if (isset($errors['password'])): ?>
                    <span class="error-message"><?php echo htmlspecialchars($errors['password'], ENT_QUOTES, 'UTF-8'); ?></span>
                <?php endif; ?>
            </div>

            <!-- Confirm Password Field -->
            <div class="form-group">
                <label for="confirm_password">Confirm Password</label>
                <input
                    type="password"
                    id="confirm_password"
                    name="confirm_password"
                    placeholder="Confirm password"
                    required
                    autocomplete="new-password"
                >
                <?php if (isset($errors['confirm_password'])): ?>
                    <span class="error-message"><?php echo htmlspecialchars($errors['confirm_password'], ENT_QUOTES, 'UTF-8'); ?></span>
                <?php endif; ?>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="btn-register">Create Account</button>
        </form>

        <!-- Password Requirements Info -->
        <div class="password-requirements">
            <strong>Password must contain:</strong>
            <ul>
                <li>✓ At least 6 characters</li>
                <li>✓ Match in both fields</li>
            </ul>
        </div>

        <!-- Footer -->
        <div class="auth-footer">
            <p>
                Already have an account?
                <a href="<?php echo BASE_URL; ?>/auth/login" class="auth-link">Login here</a>
            </p>
        </div>
    </div>

    <script>
        // Simple client-side validation
        document.querySelector('form').addEventListener('submit', function(e) {
            const fullName = document.getElementById('full_name').value.trim();
            const email = document.getElementById('email').value.trim();
            const password = document.getElementById('password').value.trim();
            const confirmPassword = document.getElementById('confirm_password').value.trim();

            // Check all fields filled
            if (!fullName || !email || !password || !confirmPassword) {
                e.preventDefault();
                alert('Please fill in all fields.');
                return false;
            }

            // Check full name length
            if (fullName.length < 3) {
                e.preventDefault();
                alert('Full name must be at least 3 characters.');
                return false;
            }

            // Check email format
            if (!email.includes('@') || !email.includes('.')) {
                e.preventDefault();
                alert('Please enter a valid email address.');
                return false;
            }

            // Check password length
            if (password.length < 6) {
                e.preventDefault();
                alert('Password must be at least 6 characters.');
                return false;
            }

            // Check password match
            if (password !== confirmPassword) {
                e.preventDefault();
                alert('Passwords do not match.');
                return false;
            }
        });
    </script>
</body>
</html>
