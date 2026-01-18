<?php
/**
 * Dashboard View
 *
 * Main dashboard/home page for authenticated users
 * Shows financial overview, recent transactions, and navigation to features
 * Responsive design that works on all devices
 */
?>

<!-- Include Header -->
<?php require_once '../app/views/layouts/header.php'; ?>

<!-- Main Dashboard Container -->
<div class="dashboard-container">
    <!-- Sidebar -->
    <?php require_once '../app/views/layouts/sidebar.php'; ?>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Welcome Section -->
        <div class="welcome-section">
            <h1 class="welcome-title">Welcome, <?php echo htmlspecialchars($userName ?? 'User', ENT_QUOTES, 'UTF-8'); ?>!</h1>
            <p class="welcome-subtitle">Manage your personal finances with Smart Wallet</p>
        </div>

        <!-- Dashboard Grid -->
        <div class="dashboard-grid">
            <!-- Quick Actions -->
            <div class="dashboard-card quick-actions-card">
                <h2 class="card-title">Quick Actions</h2>
                <div class="action-buttons">
                    <a href="<?php echo BASE_URL; ?>/income/create" class="btn btn-primary btn-action">
                        <span class="icon">+</span>
                        <span class="text">Add Income</span>
                    </a>
                    <a href="<?php echo BASE_URL; ?>/expense/create" class="btn btn-danger btn-action">
                        <span class="icon">-</span>
                        <span class="text">Add Expense</span>
                    </a>
                    <a href="<?php echo BASE_URL; ?>/category/index" class="btn btn-info btn-action">
                        <span class="icon">⚙</span>
                        <span class="text">Manage Categories</span>
                    </a>
                </div>
            </div>

            <!-- Financial Overview - This area will show stats when Phase 5 is built -->
            <div class="dashboard-card stats-card">
                <h2 class="card-title">Financial Overview</h2>
                <div class="stats-placeholder">
                    <p class="placeholder-text">💰 Financial statistics will appear here once you add transactions</p>
                    <p class="placeholder-description">Track your total income, expenses, and balance in real-time</p>
                </div>
            </div>

            <!-- Recent Transactions - Placeholder for Phase 5 -->
            <div class="dashboard-card recent-card">
                <div class="card-header">
                    <h2 class="card-title">Recent Transactions</h2>
                    <a href="<?php echo BASE_URL; ?>/income/index" class="view-all-link">View All</a>
                </div>
                <div class="transactions-placeholder">
                    <p class="placeholder-text">📝 Your recent transactions will appear here</p>
                    <p class="placeholder-description">Start by adding your first income or expense</p>
                </div>
            </div>

            <!-- Getting Started Guide -->
            <div class="dashboard-card getting-started-card">
                <h2 class="card-title">Getting Started</h2>
                <div class="guide-steps">
                    <div class="guide-step">
                        <div class="step-number">1</div>
                        <div class="step-content">
                            <h3>Set Up Categories</h3>
                            <p>Create income and expense categories that match your needs</p>
                        </div>
                    </div>
                    <div class="guide-step">
                        <div class="step-number">2</div>
                        <div class="step-content">
                            <h3>Add Your First Transaction</h3>
                            <p>Record your income and expenses to start tracking finances</p>
                        </div>
                    </div>
                    <div class="guide-step">
                        <div class="step-number">3</div>
                        <div class="step-content">
                            <h3>Monitor Your Progress</h3>
                            <p>View detailed statistics and charts of your financial data</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Features Overview -->
            <div class="dashboard-card features-card">
                <h2 class="card-title">Features</h2>
                <div class="features-grid">
                    <div class="feature-item">
                        <div class="feature-icon">📊</div>
                        <div class="feature-details">
                            <h4>Income Management</h4>
                            <p>Track and categorize all your income sources</p>
                        </div>
                    </div>
                    <div class="feature-item">
                        <div class="feature-icon">💳</div>
                        <div class="feature-details">
                            <h4>Expense Tracking</h4>
                            <p>Monitor and organize your spending habits</p>
                        </div>
                    </div>
                    <div class="feature-item">
                        <div class="feature-icon">📈</div>
                        <div class="feature-details">
                            <h4>Financial Dashboard</h4>
                            <p>Visualize your financial status at a glance</p>
                        </div>
                    </div>
                    <div class="feature-item">
                        <div class="feature-icon">🏷️</div>
                        <div class="feature-details">
                            <h4>Smart Categories</h4>
                            <p>Organize transactions with custom categories</p>
                        </div>
                    </div>
                    <div class="feature-item">
                        <div class="feature-icon">🔒</div>
                        <div class="feature-details">
                            <h4>Secure & Private</h4>
                            <p>Your data is encrypted and private</p>
                        </div>
                    </div>
                    <div class="feature-item">
                        <div class="feature-icon">📱</div>
                        <div class="feature-details">
                            <h4>Responsive Design</h4>
                            <p>Access from any device, anytime, anywhere</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Navigation Links Section -->
        <div class="dashboard-links">
            <h2 class="section-title">Quick Navigation</h2>
            <div class="links-grid">
                <a href="<?php echo BASE_URL; ?>/income/index" class="link-card">
                    <div class="link-icon">📥</div>
                    <h3>View Income</h3>
                    <p>See all your income records</p>
                </a>
                <a href="<?php echo BASE_URL; ?>/expense/index" class="link-card">
                    <div class="link-icon">📤</div>
                    <h3>View Expenses</h3>
                    <p>See all your expense records</p>
                </a>
                <a href="<?php echo BASE_URL; ?>/category/index" class="link-card">
                    <div class="link-icon">📂</div>
                    <h3>Categories</h3>
                    <p>Manage your categories</p>
                </a>
                <a href="<?php echo BASE_URL; ?>/auth/logout" class="link-card logout-card">
                    <div class="link-icon">🚪</div>
                    <h3>Logout</h3>
                    <p>Sign out of your account</p>
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Styles for Dashboard -->
<style>
    /* Dashboard Container */
    .dashboard-container {
        display: flex;
        min-height: calc(100vh - 60px);
        background-color: #f5f7fa;
    }

    .main-content {
        flex: 1;
        padding: 2rem;
        overflow-y: auto;
    }

    /* Welcome Section */
    .welcome-section {
        margin-bottom: 2rem;
        padding: 2rem;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 10px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }

    .welcome-title {
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
    }

    .welcome-subtitle {
        font-size: 1rem;
        opacity: 0.95;
    }

    /* Dashboard Grid */
    .dashboard-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
        gap: 2rem;
        margin-bottom: 3rem;
    }

    /* Dashboard Cards */
    .dashboard-card {
        background: white;
        border-radius: 10px;
        padding: 1.5rem;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
    }

    .dashboard-card:hover {
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.12);
        transform: translateY(-2px);
    }

    .card-title {
        font-size: 1.3rem;
        font-weight: 600;
        margin-bottom: 1rem;
        color: #333;
    }

    .card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
    }

    .view-all-link {
        color: #667eea;
        text-decoration: none;
        font-size: 0.9rem;
        font-weight: 500;
        transition: color 0.3s ease;
    }

    .view-all-link:hover {
        color: #764ba2;
    }

    /* Action Buttons */
    .action-buttons {
        display: flex;
        gap: 1rem;
        flex-wrap: wrap;
    }

    .btn-action {
        flex: 1;
        min-width: 150px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 1rem;
        border: none;
        border-radius: 8px;
        text-decoration: none;
        font-size: 0.9rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .btn-primary {
        background: #667eea;
        color: white;
    }

    .btn-primary:hover {
        background: #5568d3;
        transform: scale(1.05);
    }

    .btn-danger {
        background: #f56565;
        color: white;
    }

    .btn-danger:hover {
        background: #e53e3e;
        transform: scale(1.05);
    }

    .btn-info {
        background: #48bb78;
        color: white;
    }

    .btn-info:hover {
        background: #38a169;
        transform: scale(1.05);
    }

    .btn-action .icon {
        font-size: 1.5rem;
        margin-bottom: 0.5rem;
    }

    /* Placeholders */
    .stats-placeholder,
    .transactions-placeholder {
        padding: 2rem;
        text-align: center;
        background: #f9fafb;
        border-radius: 8px;
        border: 2px dashed #e5e7eb;
    }

    .placeholder-text {
        font-size: 1rem;
        font-weight: 600;
        color: #333;
        margin-bottom: 0.5rem;
    }

    .placeholder-description {
        font-size: 0.9rem;
        color: #666;
    }

    /* Guide Steps */
    .guide-steps {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .guide-step {
        display: flex;
        gap: 1rem;
        align-items: flex-start;
    }

    .step-number {
        width: 40px;
        height: 40px;
        background: #667eea;
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        flex-shrink: 0;
    }

    .step-content h3 {
        font-size: 0.95rem;
        font-weight: 600;
        margin-bottom: 0.3rem;
        color: #333;
    }

    .step-content p {
        font-size: 0.85rem;
        color: #666;
    }

    /* Features Grid */
    .features-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1rem;
    }

    .feature-item {
        display: flex;
        gap: 1rem;
        padding: 1rem;
        background: #f9fafb;
        border-radius: 8px;
        border-left: 4px solid #667eea;
    }

    .feature-icon {
        font-size: 2rem;
        flex-shrink: 0;
    }

    .feature-details h4 {
        font-size: 0.95rem;
        font-weight: 600;
        margin-bottom: 0.3rem;
        color: #333;
    }

    .feature-details p {
        font-size: 0.8rem;
        color: #666;
    }

    /* Dashboard Links */
    .dashboard-links {
        margin-bottom: 3rem;
    }

    .section-title {
        font-size: 1.3rem;
        font-weight: 600;
        margin-bottom: 1.5rem;
        color: #333;
    }

    .links-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1.5rem;
    }

    .link-card {
        background: white;
        border-radius: 10px;
        padding: 2rem;
        text-align: center;
        text-decoration: none;
        color: #333;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
        border: 2px solid transparent;
    }

    .link-card:hover {
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.12);
        transform: translateY(-5px);
        border-color: #667eea;
    }

    .link-card.logout-card:hover {
        border-color: #f56565;
    }

    .link-icon {
        font-size: 2.5rem;
        margin-bottom: 1rem;
        display: block;
    }

    .link-card h3 {
        font-size: 1.1rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
    }

    .link-card p {
        font-size: 0.85rem;
        color: #666;
    }

    /* Responsive Design */
    @media (max-width: 1024px) {
        .dashboard-grid {
            grid-template-columns: 1fr;
        }

        .welcome-title {
            font-size: 1.5rem;
        }
    }

    @media (max-width: 768px) {
        .main-content {
            padding: 1rem;
        }

        .welcome-section {
            padding: 1.5rem;
        }

        .welcome-title {
            font-size: 1.3rem;
        }

        .welcome-subtitle {
            font-size: 0.9rem;
        }

        .action-buttons {
            flex-direction: column;
        }

        .btn-action {
            flex-direction: row;
            justify-content: center;
        }

        .btn-action .icon {
            margin-right: 0.5rem;
            margin-bottom: 0;
        }

        .features-grid {
            grid-template-columns: 1fr;
        }

        .links-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 480px) {
        .welcome-title {
            font-size: 1rem;
        }

        .action-buttons {
            gap: 0.5rem;
        }

        .links-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<!-- Include Footer -->
<?php require_once '../app/views/layouts/footer.php'; ?>
