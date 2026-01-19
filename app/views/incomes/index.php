<?php require_once '../app/views/layouts/header.php'; ?>

<main class="container">
    <!-- Breadcrumb Navigation -->
    <nav class="breadcrumb active">
        <a href="<?php echo BASE_URL; ?>/dashboard/index">Dashboard</a>
        <span class="breadcrumb-separator">/</span>
        <span>Income Management</span>
    </nav>

    <!-- Page Header -->
    <div class="page-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <div>
            <h1>Income Management</h1>
            <p class="text-muted">Manage all your income entries</p>
        </div>
        <a href="<?php echo BASE_URL; ?>/income/create" class="btn btn-primary">
            <span style="margin-right: 0.5rem;">➕</span>Add Income
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
                <p class="text-muted" style="margin: 0 0 0.5rem 0;">Total Income</p>
                <h2 style="margin: 0; color: #27ae60;">
                    $<?php echo number_format($totalIncome ?? 0, 2); ?>
                </h2>
            </div>
        </div>

        <div class="card">
            <div class="card-body" style="text-align: center;">
                <p class="text-muted" style="margin: 0 0 0.5rem 0;">Number of Entries</p>
                <h2 style="margin: 0; color: #667eea;">
                    <?php echo count($incomes ?? []); ?>
                </h2>
            </div>
        </div>

        <div class="card">
            <div class="card-body" style="text-align: center;">
                <p class="text-muted" style="margin: 0 0 0.5rem 0;">Average Income</p>
                <h2 style="margin: 0; color: #f39c12;">
                    $<?php echo count($incomes ?? []) > 0 ? number_format(($totalIncome ?? 0) / count($incomes), 2) : '0.00'; ?>
                </h2>
            </div>
        </div>
    </div>

    <!-- Income Table -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Income Entries</h3>
        </div>

        <div class="card-body">
            <?php if (!empty($incomes)): ?>
                <div style="overflow-x: auto;">
                    <table style="width: 100%; border-collapse: collapse;">
                        <thead>
                            <tr style="background-color: #f5f5f5; border-bottom: 2px solid #e0e0e0;">
                                <th style="padding: 1rem; text-align: left; font-weight: 600;">Date</th>
                                <th style="padding: 1rem; text-align: left; font-weight: 600;">Description</th>
                                <th style="padding: 1rem; text-align: left; font-weight: 600;">Category</th>
                                <th style="padding: 1rem; text-align: right; font-weight: 600;">Amount</th>
                                <th style="padding: 1rem; text-align: center; font-weight: 600;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($incomes as $income): ?>
                                <tr style="border-bottom: 1px solid #e0e0e0;">
                                    <td style="padding: 1rem;">
                                        <strong><?php echo date('M d, Y', strtotime($income['date'])); ?></strong>
                                    </td>
                                    <td style="padding: 1rem;">
                                        <?php echo htmlspecialchars($income['description'] ?? 'No description', ENT_QUOTES, 'UTF-8'); ?>
                                    </td>
                                    <td style="padding: 1rem;">
                                        <?php
                                            // Display category directly from income record (it's stored as a string)
                                            $categoryName = htmlspecialchars($income['category'] ?? 'Uncategorized', ENT_QUOTES, 'UTF-8');
                                            echo $categoryName;
                                        ?>
                                    </td>
                                    <td style="padding: 1rem; text-align: right;">
                                        <strong style="color: #27ae60;">$<?php echo number_format($income['amount'], 2); ?></strong>
                                    </td>
                                    <td style="padding: 1rem; text-align: center;">
                                        <a href="<?php echo BASE_URL; ?>/income/edit/<?php echo $income['id']; ?>"
                                           class="btn btn-small" style="background: #3498db; color: white; display: inline-block; margin-right: 0.5rem;">
                                            ✏️ Edit
                                        </a>
                                        <form method="POST" action="<?php echo BASE_URL; ?>/income/delete/<?php echo $income['id']; ?>"
                                              style="display: inline;"
                                              onsubmit="return confirm('Are you sure you want to delete this income?');">
                                            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token'] ?? ''; ?>">
                                            <button type="submit" class="btn btn-small" style="background: #e74c3c; color: white;">
                                                🗑️ Delete
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div style="text-align: center; padding: 3rem; color: #999;">
                    <h3>No incomes yet</h3>
                    <p>Start by <a href="<?php echo BASE_URL; ?>/income/create" style="color: #667eea; text-decoration: underline;">adding your first income</a></p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Responsive Table CSS -->
    <style>
        @media (max-width: 768px) {
            table {
                font-size: 0.9rem;
            }

            th, td {
                padding: 0.75rem !important;
            }

            .btn-small {
                display: block !important;
                margin: 0.25rem 0 !important;
                padding: 0.5rem 0.75rem !important;
            }
        }
    </style>
</main>

<?php require_once '../app/views/layouts/footer.php'; ?>
