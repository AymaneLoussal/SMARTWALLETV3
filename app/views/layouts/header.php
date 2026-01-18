<?php
/**
 * Header Layout
 *
 * Main navigation header for authenticated users
 * Displays logo, navigation menu, and user profile dropdown
 * Responsive design with mobile hamburger menu
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? htmlspecialchars($page_title, ENT_QUOTES, 'UTF-8') . ' - ' . APP_NAME : APP_NAME; ?></title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/style.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/layout.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f5f5;
            display: flex;
            min-height: 100vh;
            flex-direction: column;
        }

        /* Header Styles */
        header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 0;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .header-container {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1rem 2rem;
            max-width: 100%;
        }

        /* Logo */
        .logo {
            display: flex;
            align-items: center;
            font-size: 1.5rem;
            font-weight: bold;
            color: white;
            text-decoration: none;
            transition: opacity 0.3s;
        }

        .logo:hover {
            opacity: 0.8;
        }

        .logo-icon {
            display: inline-block;
            width: 32px;
            height: 32px;
            margin-right: 10px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 1.2rem;
        }

        /* Navigation Menu */
        nav {
            display: flex;
            align-items: center;
            gap: 2rem;
        }

        .nav-menu {
            display: flex;
            list-style: none;
            gap: 2rem;
        }

        .nav-menu a {
            color: white;
            text-decoration: none;
            font-size: 0.95rem;
            font-weight: 500;
            padding: 0.5rem 1rem;
            border-radius: 4px;
            transition: background 0.3s;
        }

        .nav-menu a:hover {
            background: rgba(255, 255, 255, 0.1);
        }

        .nav-menu a.active {
            background: rgba(255, 255, 255, 0.2);
            border-bottom: 2px solid white;
        }

        /* User Dropdown */
        .user-menu {
            display: flex;
            align-items: center;
            gap: 1.5rem;
        }

        .user-profile {
            position: relative;
            display: inline-block;
        }

        .user-profile-btn {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 4px;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.95rem;
            transition: background 0.3s;
        }

        .user-profile-btn:hover {
            background: rgba(255, 255, 255, 0.3);
        }

        .user-profile-btn::after {
            content: '▼';
            font-size: 0.7rem;
            transition: transform 0.3s;
        }

        .user-profile-btn.active::after {
            transform: rotate(180deg);
        }

        .dropdown-menu {
            position: absolute;
            top: 100%;
            right: 0;
            background: white;
            color: #333;
            min-width: 200px;
            border-radius: 4px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            margin-top: 0.5rem;
            display: none;
            z-index: 1001;
            overflow: hidden;
        }

        .dropdown-menu.active {
            display: block;
        }

        .dropdown-menu a,
        .dropdown-menu button {
            display: block;
            width: 100%;
            padding: 0.75rem 1.5rem;
            text-align: left;
            border: none;
            background: none;
            color: #333;
            cursor: pointer;
            text-decoration: none;
            font-size: 0.95rem;
            transition: background 0.2s;
        }

        .dropdown-menu a:hover,
        .dropdown-menu button:hover {
            background-color: #f0f0f0;
        }

        .dropdown-menu a.logout-btn,
        .dropdown-menu button.logout-btn {
            color: #e74c3c;
            border-top: 1px solid #e0e0e0;
        }

        /* Mobile Menu Toggle */
        .mobile-toggle {
            display: none;
            background: none;
            border: none;
            color: white;
            font-size: 1.5rem;
            cursor: pointer;
            padding: 0.5rem;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .header-container {
                padding: 1rem;
            }

            .nav-menu {
                display: none;
                position: absolute;
                top: 60px;
                left: 0;
                right: 0;
                flex-direction: column;
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                gap: 0;
                padding: 1rem 0;
            }

            .nav-menu.active {
                display: flex;
            }

            .nav-menu a {
                padding: 0.75rem 2rem;
                border-radius: 0;
                border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            }

            .nav-menu a.active {
                border-bottom: 2px solid white;
                background: rgba(255, 255, 255, 0.15);
            }

            .mobile-toggle {
                display: block;
            }

            nav {
                width: 100%;
                flex-direction: column;
                gap: 0;
            }
        }

        /* Notification Badge */
        .notification-badge {
            position: absolute;
            top: -8px;
            right: -8px;
            background: #e74c3c;
            color: white;
            border-radius: 50%;
            width: 24px;
            height: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.75rem;
            font-weight: bold;
        }

        /* Breadcrumb Navigation */
        .breadcrumb {
            background: white;
            padding: 0.75rem 2rem;
            font-size: 0.9rem;
            color: #666;
            border-bottom: 1px solid #e0e0e0;
            display: none;
        }

        .breadcrumb.active {
            display: block;
        }

        .breadcrumb a {
            color: #667eea;
            text-decoration: none;
            margin: 0 0.25rem;
        }

        .breadcrumb a:hover {
            text-decoration: underline;
        }

        .breadcrumb-separator {
            margin: 0 0.5rem;
            color: #999;
        }
    </style>
</head>
<body>
    <!-- Header Navigation -->
    <header>
        <div class="header-container">
            <!-- Logo -->
            <a href="<?php echo BASE_URL; ?>/dashboard/index" class="logo">
                <div class="logo-icon">💰</div>
                <span><?php echo APP_NAME; ?></span>
            </a>

            <!-- Mobile Menu Toggle -->
            <button class="mobile-toggle" id="menuToggle" aria-label="Toggle menu">
                ☰
            </button>

            <!-- Main Navigation -->
            <nav>
                <ul class="nav-menu" id="navMenu">
                    <li>
                        <a href="<?php echo BASE_URL; ?>/dashboard/index"
                           class="<?php echo strpos($_SERVER['REQUEST_URI'], 'dashboard') !== false ? 'active' : ''; ?>">
                            📊 Dashboard
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo BASE_URL; ?>/income/index"
                           class="<?php echo strpos($_SERVER['REQUEST_URI'], 'income') !== false ? 'active' : ''; ?>">
                            📈 Income
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo BASE_URL; ?>/expense/index"
                           class="<?php echo strpos($_SERVER['REQUEST_URI'], 'expense') !== false ? 'active' : ''; ?>">
                            📉 Expenses
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo BASE_URL; ?>/category/index"
                           class="<?php echo strpos($_SERVER['REQUEST_URI'], 'category') !== false ? 'active' : ''; ?>">
                            🏷️ Categories
                        </a>
                    </li>
                </ul>

                <!-- User Menu -->
                <div class="user-menu">
                    <div class="user-profile">
                        <button class="user-profile-btn" id="userBtn" aria-label="User menu">
                            👤 <?php echo htmlspecialchars($_SESSION['user_name'] ?? 'User', ENT_QUOTES, 'UTF-8'); ?>
                        </button>
                        <div class="dropdown-menu" id="userDropdown">
                            <a href="<?php echo BASE_URL; ?>/user/profile" title="View your profile">
                                👤 Profile
                            </a>
                            <a href="<?php echo BASE_URL; ?>/user/settings" title="Account settings">
                                ⚙️ Settings
                            </a>
                            <a href="<?php echo BASE_URL; ?>/auth/logout" class="logout-btn" title="Sign out">
                                🚪 Logout
                            </a>
                        </div>
                    </div>
                </div>
            </nav>
        </div>
    </header>

    <!-- Optional Breadcrumb Navigation -->
    <?php if (isset($breadcrumb) && !empty($breadcrumb)): ?>
        <div class="breadcrumb active">
            <a href="<?php echo BASE_URL; ?>/dashboard/index">Home</a>
            <?php foreach ($breadcrumb as $item): ?>
                <span class="breadcrumb-separator">/</span>
                <?php if (isset($item['url'])): ?>
                    <a href="<?php echo htmlspecialchars($item['url'], ENT_QUOTES, 'UTF-8'); ?>">
                        <?php echo htmlspecialchars($item['name'], ENT_QUOTES, 'UTF-8'); ?>
                    </a>
                <?php else: ?>
                    <span><?php echo htmlspecialchars($item['name'], ENT_QUOTES, 'UTF-8'); ?></span>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <!-- Main Content Wrapper -->
    <div style="display: flex; flex: 1; width: 100%;">
        <!-- Sidebar will be included here by views -->

        <!-- Main Content Area -->
        <main style="flex: 1; padding: 2rem; overflow-y: auto;">
            <?php
            // Content will be injected here by the Controller
            // This is the main content area for each page
            ?>
