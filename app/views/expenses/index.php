<?php require_once '../app/views/layouts/header.php'; ?>

<main class="container">
    <!-- Breadcrumb Navigation -->
    <nav class="breadcrumb active">
        <a href="<?php echo BASE_URL; ?>/dashboard/index">Dashboard</a>
        <span class="breadcrumb-separator">/</span>
        <span>Expense Management</span>
    </nav>

    <!-- Page Header -->
    <div class="page-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <div>
            <h1>Expense Management</h1>
            <p class="text-muted">Manage all your expense entries</p>
        </div>
        <a href="<?php echo BASE_URL; ?>/expense/create" class="btn btn-primary">
            <span style="margin-right: 0.5rem;">➕</span>Add Expense
        </a>
    </div>

    <!-- Success Message -->
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success">
            <?php echo htmlspecialchars($_SESSION['success'], ENT_QUOTES, 'UTF-8'); ?>
        </div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <!-- Error Message -->
    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-error">
            <?php echo htmlspecialchars($_SESSION['error'], ENT_QUOTES, 'UTF-8'); ?>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <!-- Error from Controller -->
    <?php if (isset($error)): ?>
        <div class="alert alert-error">
            <?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?>
        </div>
    <?php endif; ?>

    <!-- Statistics Cards -->
    <div class="content-grid" style="margin-bottom: 2rem;">
        <div class="card">
            <div class="card-body" style="text-align: center;">
                <p class="text-muted" style="margin: 0 0 0.5rem 0;">Total Expenses</p>
                <h2 style="margin: 0; color: #e74c3c;">
                    $<?php echo isset($totalExpenses) ? number_format($totalExpenses, 2) : '0.00'; ?>
                </h2>
            </div>
        </div>
    </div>

    <!-- Expenses Table -->
    <div class="card">
        <div class="card-header">
            <h3>Your Expenses</h3>
        </div>
        <div class="card-body">
            <?php if (isset($expenses) && count($expenses) > 0): ?>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Category</th>
                                <th>Description</th>
                                <th>Amount</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($expenses as $expense): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($expense['date'] ?? 'N/A', ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td><span class="badge"><?php echo htmlspecialchars($expense['category'] ?? 'Uncategorized', ENT_QUOTES, 'UTF-8'); ?></span></td>
                                    <td><?php echo htmlspecialchars($expense['description'] ?? 'No description', ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td><strong style="color: #e74c3c;">$<?php echo number_format($expense['amount'], 2); ?></strong></td>
                                    <td>
                                        <a href="<?php echo BASE_URL; ?>/expense/edit/<?php echo $expense['id']; ?>" class="btn btn-sm btn-secondary">Edit</a>
                                        <a href="<?php echo BASE_URL; ?>/expense/delete/<?php echo $expense['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?');">Delete</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div style="padding: 2rem; text-align: center; background: #f9fafb; border-radius: 8px;">
                    <p style="color: #666; margin: 0;">No expenses yet. <a href="<?php echo BASE_URL; ?>/expense/create">Create your first expense</a></p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</main>

<!-- Styles -->
<style>
    .breadcrumb {
        margin-bottom: 2rem;
        padding: 0.5rem 0;
    }

    .breadcrumb a {
        color: #667eea;
        text-decoration: none;
    }

    .breadcrumb a:hover {
        text-decoration: underline;
    }

    .breadcrumb-separator {
        margin: 0 0.5rem;
        color: #ccc;
    }

    .page-header {
        margin-bottom: 2rem;
    }

    .page-header h1 {
        margin: 0 0 0.5rem 0;
        font-size: 2rem;
        color: #333;
    }

    .page-header .text-muted {
        color: #666;
        margin: 0;
    }

    .btn {
        padding: 0.5rem 1rem;
        border-radius: 6px;
        text-decoration: none;
        display: inline-block;
        transition: all 0.3s ease;
        border: none;
        cursor: pointer;
        font-weight: 500;
    }

    .btn-primary {
        background: #667eea;
        color: white;
    }

    .btn-primary:hover {
        background: #5568d3;
    }

    .btn-secondary {
        background: #95a5a6;
        color: white;
        font-size: 0.85rem;
        padding: 0.3rem 0.8rem;
    }

    .btn-secondary:hover {
        background: #7f8c8d;
    }

    .btn-danger {
        background: #e74c3c;
        color: white;
        font-size: 0.85rem;
        padding: 0.3rem 0.8rem;
    }

    .btn-danger:hover {
        background: #c0392b;
    }

    .btn-sm {
        padding: 0.3rem 0.8rem;
        font-size: 0.85rem;
    }

    .alert {
        padding: 1rem;
        border-radius: 6px;
        margin-bottom: 1.5rem;
    }

    .alert-success {
        background: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
    }

    .alert-error {
        background: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
    }

    .badge {
        background: #e74c3c;
        color: white;
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.85rem;
    }

    .card {
        background: white;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        margin-bottom: 1.5rem;
    }

    .card-header {
        padding: 1.5rem;
        border-bottom: 1px solid #eee;
    }

    .card-header h3 {
        margin: 0;
        font-size: 1.3rem;
        color: #333;
    }

    .card-body {
        padding: 1.5rem;
    }

    .content-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
    }

    .table-responsive {
        overflow-x: auto;
    }

    .table {
        width: 100%;
        border-collapse: collapse;
    }

    .table thead {
        background: #f9fafb;
    }

    .table th {
        padding: 1rem;
        text-align: left;
        font-weight: 600;
        color: #333;
        border-bottom: 2px solid #e5e7eb;
    }

    .table td {
        padding: 1rem;
        border-bottom: 1px solid #e5e7eb;
    }

    .table tbody tr:hover {
        background: #f9fafb;
    }

    .text-muted {
        color: #666;
    }

    @media (max-width: 768px) {
        .page-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 1rem;
        }

        .table {
            font-size: 0.9rem;
        }

        .table th,
        .table td {
            padding: 0.75rem;
        }
    }
</style>

<?php require_once '../app/views/layouts/footer.php'; ?>
