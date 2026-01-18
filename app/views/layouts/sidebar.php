<?php
/**
 * Sidebar Layout
 *
 * Navigation sidebar for authenticated users
 * Displays main menu with collapsible navigation
 * Responsive design that collapses on mobile
 */
?>

<!-- Sidebar Navigation -->
<aside class="sidebar" id="sidebar">
    <!-- Sidebar Header -->
    <div class="sidebar-header">
        <h3>Menu</h3>
        <button class="sidebar-close" id="sidebarClose" aria-label="Close sidebar">
            ✕
        </button>
    </div>

    <!-- Navigation Menu -->
    <nav class="sidebar-nav">
        <ul class="sidebar-menu">
            <!-- Dashboard -->
            <li class="menu-item">
                <a href="<?php echo BASE_URL; ?>/dashboard/index" class="menu-link active">
                    <span class="menu-icon">📊</span>
                    <span class="menu-text">Dashboard</span>
                </a>
            </li>

            <!-- Income Section -->
            <li class="menu-item">
                <a href="<?php echo BASE_URL; ?>/income/index" class="menu-link">
                    <span class="menu-icon">📈</span>
                    <span class="menu-text">Income</span>
                </a>
                <ul class="submenu">
                    <li><a href="<?php echo BASE_URL; ?>/income/index">View All</a></li>
                    <li><a href="<?php echo BASE_URL; ?>/income/create">Add New</a></li>
                </ul>
            </li>

            <!-- Expense Section -->
            <li class="menu-item">
                <a href="<?php echo BASE_URL; ?>/expense/index" class="menu-link">
                    <span class="menu-icon">📉</span>
                    <span class="menu-text">Expenses</span>
                </a>
                <ul class="submenu">
                    <li><a href="<?php echo BASE_URL; ?>/expense/index">View All</a></li>
                    <li><a href="<?php echo BASE_URL; ?>/expense/create">Add New</a></li>
                </ul>
            </li>

            <!-- Categories -->
            <li class="menu-item">
                <a href="<?php echo BASE_URL; ?>/category/index" class="menu-link">
                    <span class="menu-icon">🏷️</span>
                    <span class="menu-text">Categories</span>
                </a>
            </li>

            <!-- Divider -->
            <li class="menu-divider"></li>

            <!-- Settings -->
            <li class="menu-item">
                <a href="<?php echo BASE_URL; ?>/user/settings" class="menu-link">
                    <span class="menu-icon">⚙️</span>
                    <span class="menu-text">Settings</span>
                </a>
            </li>

            <!-- Help -->
            <li class="menu-item">
                <a href="<?php echo BASE_URL; ?>/help" class="menu-link">
                    <span class="menu-icon">❓</span>
                    <span class="menu-text">Help</span>
                </a>
            </li>
        </ul>
    </nav>

    <!-- Sidebar Footer -->
    <div class="sidebar-footer">
        <a href="<?php echo BASE_URL; ?>/user/profile" class="user-info">
            <div class="user-avatar">
                <?php
                // Display first letter of user name as avatar
                $userName = htmlspecialchars($_SESSION['user_name'] ?? 'U', ENT_QUOTES, 'UTF-8');
                echo strtoupper(substr($userName, 0, 1));
                ?>
            </div>
            <div class="user-details">
                <div class="user-name"><?php echo $userName; ?></div>
                <div class="user-email"><?php echo htmlspecialchars($_SESSION['user_email'] ?? '', ENT_QUOTES, 'UTF-8'); ?></div>
            </div>
        </a>
    </div>
</aside>

<!-- Sidebar Overlay (for mobile) -->
<div class="sidebar-overlay" id="sidebarOverlay"></div>

<style>
    /* Sidebar Styles */
    .sidebar {
        position: fixed;
        left: 0;
        top: 60px;
        height: calc(100vh - 60px);
        width: 260px;
        background: white;
        border-right: 1px solid #e0e0e0;
        display: flex;
        flex-direction: column;
        box-shadow: 2px 0 5px rgba(0, 0, 0, 0.05);
        z-index: 999;
        transition: transform 0.3s ease;
    }

    /* Sidebar Header */
    .sidebar-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 1rem;
        border-bottom: 1px solid #e0e0e0;
    }

    .sidebar-header h3 {
        margin: 0;
        font-size: 1rem;
        color: #333;
    }

    .sidebar-close {
        display: none;
        background: none;
        border: none;
        font-size: 1.5rem;
        cursor: pointer;
        color: #666;
        padding: 0;
    }

    /* Navigation Menu */
    .sidebar-nav {
        flex: 1;
        overflow-y: auto;
    }

    .sidebar-menu {
        list-style: none;
        padding: 1rem 0;
        margin: 0;
    }

    .menu-item {
        margin: 0;
    }

    .menu-link {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 0.75rem 1rem;
        color: #666;
        text-decoration: none;
        transition: all 0.3s;
        font-size: 0.95rem;
        cursor: pointer;
        border: none;
        background: none;
        width: 100%;
        text-align: left;
    }

    .menu-link:hover {
        background-color: #f5f5f5;
        color: #667eea;
    }

    .menu-link.active {
        background: linear-gradient(90deg, #667eea 0%, #764ba2 100%);
        color: white;
        font-weight: 600;
    }

    .menu-icon {
        font-size: 1.3rem;
        min-width: 30px;
        text-align: center;
    }

    .menu-text {
        flex: 1;
    }

    /* Submenu */
    .submenu {
        list-style: none;
        padding: 0;
        margin: 0.5rem 0;
        background-color: #f9f9f9;
        display: none;
    }

    .submenu.active {
        display: block;
    }

    .submenu li {
        margin: 0;
    }

    .submenu a {
        display: block;
        padding: 0.5rem 1rem 0.5rem 3.5rem;
        color: #999;
        text-decoration: none;
        font-size: 0.9rem;
        transition: color 0.3s;
    }

    .submenu a:hover {
        color: #667eea;
    }

    /* Menu Divider */
    .menu-divider {
        height: 1px;
        background: #e0e0e0;
        margin: 0.5rem 1rem;
    }

    /* Sidebar Footer */
    .sidebar-footer {
        padding: 1rem;
        border-top: 1px solid #e0e0e0;
        background: #f9f9f9;
    }

    .user-info {
        display: flex;
        align-items: center;
        gap: 1rem;
        text-decoration: none;
        padding: 0.75rem;
        border-radius: 6px;
        transition: background 0.3s;
    }

    .user-info:hover {
        background-color: #f0f0f0;
    }

    .user-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        font-size: 1.1rem;
    }

    .user-details {
        flex: 1;
        min-width: 0;
    }

    .user-name {
        font-size: 0.9rem;
        font-weight: 600;
        color: #333;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .user-email {
        font-size: 0.75rem;
        color: #999;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    /* Sidebar Overlay */
    .sidebar-overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.5);
        z-index: 998;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: 260px;
            transform: translateX(-100%);
            z-index: 1002;
        }

        .sidebar.active {
            transform: translateX(0);
        }

        .sidebar-close {
            display: block;
        }

        .sidebar-overlay {
            display: none;
        }

        .sidebar-overlay.active {
            display: block;
        }

        /* Adjust main content for mobile */
        main {
            margin-left: 0 !important;
        }
    }

    /* Desktop Layout */
    @media (min-width: 769px) {
        main {
            margin-left: 260px;
        }
    }

    /* Scrollbar Styling */
    .sidebar-nav::-webkit-scrollbar {
        width: 6px;
    }

    .sidebar-nav::-webkit-scrollbar-track {
        background: #f1f1f1;
    }

    .sidebar-nav::-webkit-scrollbar-thumb {
        background: #ccc;
        border-radius: 3px;
    }

    .sidebar-nav::-webkit-scrollbar-thumb:hover {
        background: #999;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const sidebar = document.getElementById('sidebar');
        const sidebarOverlay = document.getElementById('sidebarOverlay');
        const sidebarClose = document.getElementById('sidebarClose');
        const menuToggle = document.getElementById('menuToggle');

        // Check if we're on a desktop view
        const isDesktop = () => window.innerWidth > 768;

        // Setup mobile menu interactions
        if (!isDesktop()) {
            // Show sidebar with overlay on mobile
            const showSidebar = () => {
                sidebar.classList.add('active');
                sidebarOverlay.classList.add('active');
            };

            const hideSidebar = () => {
                sidebar.classList.remove('active');
                sidebarOverlay.classList.remove('active');
            };

            // Menu toggle from header
            if (menuToggle) {
                menuToggle.addEventListener('click', showSidebar);
            }

            // Close button
            if (sidebarClose) {
                sidebarClose.addEventListener('click', hideSidebar);
            }

            // Overlay click
            sidebarOverlay.addEventListener('click', hideSidebar);

            // Close sidebar when clicking a link
            const menuLinks = sidebar.querySelectorAll('.menu-link');
            menuLinks.forEach(link => {
                link.addEventListener('click', hideSidebar);
            });
        }

        // Submenu toggle
        const menuItems = sidebar.querySelectorAll('.menu-item');
        menuItems.forEach(item => {
            const link = item.querySelector('.menu-link');
            const submenu = item.querySelector('.submenu');

            if (submenu && link) {
                link.addEventListener('click', function(e) {
                    // Don't toggle if it's just a link
                    if (!this.href.includes('#')) {
                        return;
                    }
                    e.preventDefault();
                    submenu.classList.toggle('active');
                });
            }
        });

        // Set active menu item based on current URL
        const currentUrl = window.location.pathname;
        const sidebarLinks = sidebar.querySelectorAll('.menu-link');

        sidebarLinks.forEach(link => {
            const href = link.getAttribute('href');
            if (href && currentUrl.includes(href.split('/').filter(Boolean).pop())) {
                link.classList.add('active');

                // Show parent submenu if link is in submenu
                const submenu = link.closest('.submenu');
                if (submenu) {
                    submenu.classList.add('active');
                }
            }
        });
    });
</script>
