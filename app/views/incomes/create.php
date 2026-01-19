<?php require_once '../app/views/layouts/header.php'; ?>

<main class="container">
    <!-- Breadcrumb Navigation -->
    <nav class="breadcrumb active">
        <a href="<?php echo BASE_URL; ?>/dashboard/index">Dashboard</a>
        <span class="breadcrumb-separator">/</span>
        <a href="<?php echo BASE_URL; ?>/income/index">Income</a>
        <span class="breadcrumb-separator">/</span>
        <span>Add Income</span>
    </nav>

    <!-- Page Header -->
    <div class="page-header" style="margin-bottom: 2rem;">
        <h1>Add New Income</h1>
        <p class="text-muted">Create a new income entry</p>
    </div>

    <!-- Error Messages -->
    <?php if (isset($error)): ?>
        <div class="alert alert-error">
            <?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?>
        </div>
    <?php endif; ?>

    <!-- Form -->
    <div class="card" style="max-width: 600px;">
        <form method="POST" action="<?php echo BASE_URL; ?>/income/store" novalidate>
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
                       value="<?php echo htmlspecialchars($formData['amount'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                       required>
                <?php if (isset($errors['amount'])): ?>
                    <div class="form-error">
                        <?php echo htmlspecialchars($errors['amount'], ENT_QUOTES, 'UTF-8'); ?>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Category Field -->
           <!-- Category Field -->
<div class="form-group">
    <label for="category_id" class="form-label">Category <span style="color: #e74c3c;">*</span></label>
    <select id="category_id"
            name="category_id"
            class="form-control"
            required>
        <option value="">-- Select a category --</option>
        <?php if (!empty($categories)): ?>
            <?php foreach ($categories as $category): ?>
                <option value="<?php echo htmlspecialchars($category['id'], ENT_QUOTES, 'UTF-8'); ?>"
                        <?php echo (isset($formData['category_id']) && $formData['category_id'] == $category['id']) ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($category['name'], ENT_QUOTES, 'UTF-8'); ?>
                </option>
            <?php endforeach; ?>
        <?php endif; ?>
    </select>
    <?php if (isset($errors['category_id'])): ?>
        <div class="form-error">
            <?php echo htmlspecialchars($errors['category_id'], ENT_QUOTES, 'UTF-8'); ?>
        </div>
    <?php endif; ?>
</div>

            <!-- Date Field -->
            <div class="form-group">
                <label for="date" class="form-label">Date <span style="color: #e74c3c;">*</span></label>
                <input type="date"
                       id="date"
                       name="date"
                       class="form-control"
                       value="<?php echo htmlspecialchars($formData['date'] ?? date('Y-m-d'), ENT_QUOTES, 'UTF-8'); ?>"
                       required>
                <?php if (isset($errors['date'])): ?>
                    <div class="form-error">
                        <?php echo htmlspecialchars($errors['date'], ENT_QUOTES, 'UTF-8'); ?>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Description Field -->
            <div class="form-group">
                <label for="description" class="form-label">Description <span style="color: #999;">(Optional)</span></label>
                <textarea id="description"
                          name="description"
                          class="form-control"
                          placeholder="Add notes about this income"
                          rows="4"
                          style="resize: vertical;"><?php echo htmlspecialchars($formData['description'] ?? '', ENT_QUOTES, 'UTF-8'); ?></textarea>
                <?php if (isset($errors['description'])): ?>
                    <div class="form-error">
                        <?php echo htmlspecialchars($errors['description'], ENT_QUOTES, 'UTF-8'); ?>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Form Actions -->
            <div style="display: flex; gap: 1rem; margin-top: 2rem;">
                <button type="submit" class="btn btn-primary" style="flex: 1;">
                    ✅ Add Income
                </button>
                <a href="<?php echo BASE_URL; ?>/income/index" class="btn btn-secondary" style="flex: 1; text-align: center;">
                    ❌ Cancel
                </a>
            </div>
        </form>
    </div>

    <!-- Help Text -->
    <div style="background: #f0f7ff; padding: 1.5rem; border-radius: 6px; margin-top: 2rem; border-left: 4px solid #667eea;">
        <h4 style="margin-top: 0; color: #333;">💡 Tips:</h4>
        <ul style="margin: 0; padding-left: 1.5rem; color: #666;">
            <li>Amount is required and must be greater than 0</li>
            <li>Date defaults to today but can be changed</li>
            <li>Categories help organize your incomes</li>
            <li>Add descriptions to remember what the income was for</li>
        </ul>
    </div>
</main>

<?php require_once '../app/views/layouts/footer.php'; ?>
