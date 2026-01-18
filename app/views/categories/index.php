<?php require_once '../app/views/layouts/header.php'; ?>

<main class="container">
    <!-- Breadcrumb Navigation -->
    <nav class="breadcrumb active">
        <a href="<?php echo BASE_URL; ?>/dashboard/index">Dashboard</a>
        <span class="breadcrumb-separator">/</span>
        <span>Category Management</span>
    </nav>

    <!-- Page Header -->
    <div class="page-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <div>
            <h1>Category Management</h1>
            <p class="text-muted">Create and manage your income and expense categories</p>
        </div>
        <button class="btn btn-primary" onclick="toggleCreateForm()">
            <span style="margin-right: 0.5rem;">➕</span>New Category
        </button>
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

    <!-- Create Category Form (Hidden by default) -->
    <div id="createForm" class="card" style="display: none; margin-bottom: 2rem;">
        <div class="card-header">
            <h3>Create New Category</h3>
        </div>
        <div class="card-body">
            <form method="POST" action="<?php echo BASE_URL; ?>/category/store" class="form">
                <div class="form-group">
                    <label for="name">Category Name</label>
                    <input type="text" id="name" name="name" placeholder="e.g., Groceries, Salary, Freelance" required>
                </div>

                <div class="form-group">
                    <label for="type">Category Type</label>
                    <select id="type" name="type" required>
                        <option value="">-- Select Type --</option>
                        <option value="income">Income</option>
                        <option value="expense">Expense</option>
                    </select>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Create Category</button>
                    <button type="button" class="btn btn-secondary" onclick="toggleCreateForm()">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Income Categories Section -->
    <div class="section">
        <h2 class="section-title">📥 Income Categories</h2>
        <div class="categories-grid">
            <?php if (isset($incomeCategories) && count($incomeCategories) > 0): ?>
                <?php foreach ($incomeCategories as $category): ?>
                    <div class="category-card income">
                        <div class="category-info">
                            <h3><?php echo htmlspecialchars($category['name'] ?? 'Unknown', ENT_QUOTES, 'UTF-8'); ?></h3>
                            <p class="category-meta">
                                <?php echo isset($category['count']) ? $category['count'] . ' transaction(s)' : '0 transactions'; ?>
                            </p>
                        </div>
                        <div class="category-actions">
                            <button class="btn btn-danger btn-sm" onclick="deleteCategory(<?php echo $category['id']; ?>)">Delete</button>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="empty-state">
                    <p>No income categories yet. <a href="#" onclick="toggleCreateForm(); return false;">Create one</a></p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Expense Categories Section -->
    <div class="section">
        <h2 class="section-title">📤 Expense Categories</h2>
        <div class="categories-grid">
            <?php if (isset($expenseCategories) && count($expenseCategories) > 0): ?>
                <?php foreach ($expenseCategories as $category): ?>
                    <div class="category-card expense">
                        <div class="category-info">
                            <h3><?php echo htmlspecialchars($category['name'] ?? 'Unknown', ENT_QUOTES, 'UTF-8'); ?></h3>
                            <p class="category-meta">
                                <?php echo isset($category['count']) ? $category['count'] . ' transaction(s)' : '0 transactions'; ?>
                            </p>
                        </div>
                        <div class="category-actions">
                            <button class="btn btn-danger btn-sm" onclick="deleteCategory(<?php echo $category['id']; ?>)">Delete</button>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="empty-state">
                    <p>No expense categories yet. <a href="#" onclick="toggleCreateForm(); return false;">Create one</a></p>
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
        margin-left: 0.5rem;
    }

    .btn-secondary:hover {
        background: #7f8c8d;
    }

    .btn-danger {
        background: #e74c3c;
        color: white;
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

    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-group label {
        display: block;
        margin-bottom: 0.5rem;
        font-weight: 600;
        color: #333;
    }

    .form-group input,
    .form-group select {
        width: 100%;
        padding: 0.75rem;
        border: 1px solid #ddd;
        border-radius: 6px;
        font-size: 1rem;
        font-family: inherit;
    }

    .form-group input:focus,
    .form-group select:focus {
        outline: none;
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }

    .form-actions {
        display: flex;
        gap: 1rem;
    }

    .section {
        margin-bottom: 3rem;
    }

    .section-title {
        font-size: 1.3rem;
        font-weight: 600;
        margin-bottom: 1.5rem;
        color: #333;
    }

    .categories-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
        gap: 1.5rem;
    }

    .category-card {
        background: white;
        border-radius: 8px;
        padding: 1.5rem;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        display: flex;
        justify-content: space-between;
        align-items: center;
        transition: all 0.3s ease;
        border-left: 4px solid;
    }

    .category-card.income {
        border-left-color: #27ae60;
    }

    .category-card.expense {
        border-left-color: #e74c3c;
    }

    .category-card:hover {
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.12);
        transform: translateY(-2px);
    }

    .category-info h3 {
        margin: 0 0 0.5rem 0;
        font-size: 1.1rem;
        color: #333;
    }

    .category-meta {
        margin: 0;
        font-size: 0.85rem;
        color: #666;
    }

    .category-actions {
        flex-shrink: 0;
    }

    .empty-state {
        padding: 2rem;
        text-align: center;
        background: #f9fafb;
        border-radius: 8px;
        color: #666;
        grid-column: 1 / -1;
    }

    .empty-state a {
        color: #667eea;
        text-decoration: none;
        font-weight: 600;
    }

    .empty-state a:hover {
        text-decoration: underline;
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

        .categories-grid {
            grid-template-columns: 1fr;
        }

        .category-card {
            flex-direction: column;
            align-items: flex-start;
        }

        .category-actions {
            margin-top: 1rem;
            width: 100%;
        }

        .category-actions button {
            width: 100%;
        }
    }
</style>

<!-- Scripts -->
<script>
    function toggleCreateForm() {
        const form = document.getElementById('createForm');
        form.style.display = form.style.display === 'none' ? 'block' : 'none';
    }

    function deleteCategory(categoryId) {
        if (confirm('Are you sure you want to delete this category?')) {
            window.location.href = '<?php echo BASE_URL; ?>/category/delete/' + categoryId;
        }
    }
</script>

<?php require_once '../app/views/layouts/footer.php'; ?>
