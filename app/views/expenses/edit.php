<?php require_once '../app/views/layouts/header.php'; ?>

<main class="container">
    <!-- Breadcrumb Navigation -->
    <nav class="breadcrumb active">
        <a href="<?php echo BASE_URL; ?>/dashboard/index">Dashboard</a>
        <span class="breadcrumb-separator">/</span>
        <a href="<?php echo BASE_URL; ?>/expense/index">Expenses</a>
        <span class="breadcrumb-separator">/</span>
        <span>Edit Expense</span>
    </nav>

    <!-- Page Header -->
    <div class="page-header" style="margin-bottom: 2rem;">
        <h1>Edit Expense</h1>
        <p class="text-muted">Update expense details</p>
    </div>

    <!-- Error Messages -->
    <?php if (isset($error)): ?>
        <div class="alert alert-error">
            <?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?>
        </div>
    <?php endif; ?>

    <!-- Form -->
    <div class="card" style="max-width: 600px;">
        <form method="POST" action="<?php echo BASE_URL; ?>/expense/update/<?php echo htmlspecialchars($expense['id'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" novalidate>
            <!-- CSRF Token -->
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token'] ?? ''; ?>">

            <!-- Amount Field -->
            <div class="form-group">
                <label for="amount" class="form-label">Amount <span style="color: #e74c3c;">*</span></label>
                <input type="number"
                       id="amount"
                       name="amount"
                       class="form-control"
                       placeholder="0.00"
                       step="0.01"
                       min="0.01"
                       value="<?php echo htmlspecialchars($expense['amount'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                       required>
                <?php if (isset($errors['amount'])): ?>
                    <div class="form-error">
                        <?php echo htmlspecialchars($errors['amount'], ENT_QUOTES, 'UTF-8'); ?>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Category Field -->
            <div class="form-group">
                <label for="category_id" class="form-label">Category</label>
                <select id="category_id" name="category_id" class="form-control">
                    <option value="">-- Select Category --</option>
                    <?php if (isset($categories) && !empty($categories)): ?>
                        <?php foreach ($categories as $category): ?>
                            <option value="<?php echo htmlspecialchars($category['id'], ENT_QUOTES, 'UTF-8'); ?>"
                                <?php if (isset($expense['category_id']) && $expense['category_id'] == $category['id']) echo 'selected'; ?>>
                                <?php echo htmlspecialchars($category['name'], ENT_QUOTES, 'UTF-8'); ?>
                            </option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>

            <!-- Date Field -->
            <div class="form-group">
                <label for="date" class="form-label">Date <span style="color: #e74c3c;">*</span></label>
                <input type="date"
                       id="date"
                       name="date"
                       class="form-control"
                       value="<?php echo htmlspecialchars($expense['date'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                       required>
                <?php if (isset($errors['date'])): ?>
                    <div class="form-error">
                        <?php echo htmlspecialchars($errors['date'], ENT_QUOTES, 'UTF-8'); ?>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Description Field -->
            <div class="form-group">
                <label for="description" class="form-label">Description</label>
                <textarea id="description"
                          name="description"
                          class="form-control"
                          rows="4"
                          placeholder="Add notes about this expense..."><?php echo htmlspecialchars($expense['description'] ?? '', ENT_QUOTES, 'UTF-8'); ?></textarea>
            </div>

            <!-- Form Actions -->
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Update Expense</button>
                <a href="<?php echo BASE_URL; ?>/expense/index" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
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

    .card {
        background: white;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        padding: 2rem;
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-label {
        display: block;
        margin-bottom: 0.5rem;
        font-weight: 600;
        color: #333;
        font-size: 0.95rem;
    }

    .form-control {
        width: 100%;
        padding: 0.75rem;
        border: 1px solid #ddd;
        border-radius: 6px;
        font-size: 1rem;
        font-family: inherit;
        transition: all 0.3s ease;
    }

    .form-control:focus {
        outline: none;
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }

    .form-error {
        color: #e74c3c;
        font-size: 0.85rem;
        margin-top: 0.3rem;
    }

    .form-actions {
        display: flex;
        gap: 1rem;
        margin-top: 2rem;
    }

    .btn {
        padding: 0.75rem 1.5rem;
        border-radius: 6px;
        text-decoration: none;
        border: none;
        cursor: pointer;
        font-weight: 600;
        transition: all 0.3s ease;
        font-size: 1rem;
    }

    .btn-primary {
        background: #667eea;
        color: white;
    }

    .btn-primary:hover {
        background: #5568d3;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
    }

    .btn-secondary {
        background: #95a5a6;
        color: white;
    }

    .btn-secondary:hover {
        background: #7f8c8d;
    }

    .alert {
        padding: 1rem;
        border-radius: 6px;
        margin-bottom: 1.5rem;
    }

    .alert-error {
        background: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
    }

    @media (max-width: 768px) {
        .card {
            padding: 1.5rem;
        }

        .form-actions {
            flex-direction: column;
        }

        .btn {
            width: 100%;
            text-align: center;
        }
    }
</style>

<?php require_once '../app/views/layouts/footer.php'; ?>
