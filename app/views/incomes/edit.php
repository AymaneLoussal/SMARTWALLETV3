<?php require_once '../app/views/layouts/header.php'; ?>

<main class="container">
    <!-- Breadcrumb Navigation -->
    <nav class="breadcrumb active">
        <a href="<?php echo BASE_URL; ?>/dashboard/index">Dashboard</a>
        <span class="breadcrumb-separator">/</span>
        <a href="<?php echo BASE_URL; ?>/income/index">Income</a>
        <span class="breadcrumb-separator">/</span>
        <span>Edit Income</span>
    </nav>

    <!-- Page Header -->
    <div class="page-header" style="margin-bottom: 2rem;">
        <h1>Edit Income</h1>
        <p class="text-muted">Update income entry #<?php echo htmlspecialchars($income['id'] ?? '', ENT_QUOTES, 'UTF-8'); ?></p>
    </div>

    <!-- Error Messages -->
    <?php if (isset($error)): ?>
        <div class="alert alert-error">
            <?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?>
        </div>
    <?php endif; ?>

    <!-- Form -->
    <div class="card" style="max-width: 600px;">
        <?php if (isset($income) && is_array($income)): ?>
            <form method="POST" action="<?php echo BASE_URL; ?>/income/update/<?php echo htmlspecialchars($income['id'], ENT_QUOTES, 'UTF-8'); ?>" novalidate>
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
                           value="<?php echo htmlspecialchars($income['amount'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                           required>
                    <?php if (isset($errors['amount'])): ?>
                        <div class="form-error">
                            <?php echo htmlspecialchars($errors['amount'], ENT_QUOTES, 'UTF-8'); ?>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Category Field -->
                <div class="form-group">
                    <label for="category_id" class="form-label">Category <span style="color: #999;">(Optional)</span></label>
                    <select id="category_id"
                            name="category_id"
                            class="form-control">
                        <option value="">-- Select a category --</option>
                        <?php if (!empty($categories)): ?>
                            <?php foreach ($categories as $category): ?>
                                <option value="<?php echo $category['id']; ?>"
                                        <?php echo (isset($income['category_id']) && $income['category_id'] == $category['id']) ? 'selected' : ''; ?>>
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
                           value="<?php echo htmlspecialchars($income['date'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"
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
                              style="resize: vertical;"><?php echo htmlspecialchars($income['description'] ?? '', ENT_QUOTES, 'UTF-8'); ?></textarea>
                    <?php if (isset($errors['description'])): ?>
                        <div class="form-error">
                            <?php echo htmlspecialchars($errors['description'], ENT_QUOTES, 'UTF-8'); ?>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Created At Info -->
                <div style="background: #f9f9f9; padding: 1rem; border-radius: 6px; margin-bottom: 2rem;">
                    <p style="margin: 0; font-size: 0.85rem; color: #999;">
                        Created on: <strong><?php echo isset($income['created_at']) ? date('M d, Y H:i', strtotime($income['created_at'])) : 'N/A'; ?></strong>
                    </p>
                </div>

                <!-- Form Actions -->
                <div style="display: flex; gap: 1rem; margin-top: 2rem;">
                    <button type="submit" class="btn btn-primary" style="flex: 1;">
                        ✅ Update Income
                    </button>
                    <a href="<?php echo BASE_URL; ?>/income/index" class="btn btn-secondary" style="flex: 1; text-align: center;">
                        ❌ Cancel
                    </a>
                </div>

                <!-- Delete Option -->
                <div style="border-top: 1px solid #e0e0e0; padding-top: 2rem; margin-top: 2rem;">
                    <h4 style="color: #e74c3c; margin-top: 0;">Danger Zone</h4>
                    <p style="color: #666; font-size: 0.9rem;">
                        This action cannot be undone.
                    </p>
                    <form method="POST" action="<?php echo BASE_URL; ?>/income/delete/<?php echo htmlspecialchars($income['id'], ENT_QUOTES, 'UTF-8'); ?>"
                          onsubmit="return confirm('Are you sure you want to permanently delete this income? This cannot be undone.');">
                        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token'] ?? ''; ?>">
                        <button type="submit" class="btn btn-danger" style="width: 100%;">
                            🗑️ Delete This Income
                        </button>
                    </form>
                </div>
            </form>
        <?php else: ?>
            <div style="padding: 2rem; text-align: center; color: #999;">
                <h3>Income not found</h3>
                <p><a href="<?php echo BASE_URL; ?>/income/index" style="color: #667eea;">← Back to income list</a></p>
            </div>
        <?php endif; ?>
    </div>
</main>

<?php require_once '../app/views/layouts/footer.php'; ?>
