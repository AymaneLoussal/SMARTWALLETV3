<?php

use core\Controller;

/**
 * Expense Controller
 *
 * Handles all expense-related requests
 * Manages expense CRUD operations and views
 *
 * @package App\Controllers
 */
class ExpenseController extends Controller {

    private $expenseModel;
    private $categoryModel;

    /**
     * Constructor
     */
    public function __construct() {
//        parent::__construct();

        // Load models using parent class method
        $this->expenseModel = $this->model('Expense');
        $this->categoryModel = $this->model('Category');
    }

    /**
     * Display list of all expenses for current user
     * GET /expense/index
     */
    public function index() {
        // Verify user is logged in
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('/auth/login');
            return;
        }

        try {
            $userId = $_SESSION['user_id'];

            // Get all expenses for user
            $expenses = $this->expenseModel->getAll($userId);

            // Get categories for filter
            $categories = $this->categoryModel->getAllByUser($userId);

            // Calculate statistics
            $totalExpenses = $this->expenseModel->getTotal($userId);

            // Prepare data for view
            $data = [
                'page_title' => 'Expense Management',
                'expenses' => $expenses,
                'categories' => $categories,
                'totalExpenses' => $totalExpenses,
                'userName' => $_SESSION['user_name'] ?? 'User'
            ];

            $this->render('expenses/index', $data);

        } catch (Exception $e) {
            $data = [
                'page_title' => 'Expense Management',
                'error' => 'Failed to load expenses: ' . $e->getMessage()
            ];
            $this->render('expenses/index', $data);
        }
    }

    /**
     * Display expense creation form
     * GET /expense/create
     */
    public function create() {
        // Verify user is logged in
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('/auth/login');
            return;
        }

        try {
            $userId = $_SESSION['user_id'];

            // Get categories for dropdown
            $categories = $this->categoryModel->getAllByUser($userId);

            $data = [
                'page_title' => 'Add Expense',
                'categories' => $categories,
                'userName' => $_SESSION['user_name'] ?? 'User'
            ];

            $this->render('expenses/create', $data);

        } catch (Exception $e) {
            $data = [
                'page_title' => 'Add Expense',
                'error' => 'Failed to load form: ' . $e->getMessage()
            ];
            $this->render('expenses/create', $data);
        }
    }

    /**
     * Store new expense in database
     * POST /expense/store
     */
    public function store() {
        // Verify user is logged in
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('/auth/login');
            return;
        }

        // Verify CSRF token
        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== ($_SESSION['csrf_token'] ?? '')) {
            http_response_code(403);
            die('CSRF token validation failed');
        }

        // Validate required fields
        $errors = [];

        $amount = $_POST['amount'] ?? null;
        $categoryId = $_POST['category_id'] ?? null;
        $description = $_POST['description'] ?? null;
        $date = $_POST['date'] ?? null;

        if (!$amount || $amount <= 0) {
            $errors['amount'] = 'Amount must be a positive number';
        }

        if (!$date) {
            $errors['date'] = 'Date is required';
        }

        if (!empty($errors)) {
            // Re-render form with errors
            $userId = $_SESSION['user_id'];
            $categories = $this->categoryModel->getAllByUser($userId);

            $data = [
                'page_title' => 'Add Expense',
                'categories' => $categories,
                'errors' => $errors,
                'formData' => [
                    'amount' => htmlspecialchars($amount ?? '', ENT_QUOTES, 'UTF-8'),
                    'category_id' => htmlspecialchars($categoryId ?? '', ENT_QUOTES, 'UTF-8'),
                    'description' => htmlspecialchars($description ?? '', ENT_QUOTES, 'UTF-8'),
                    'date' => htmlspecialchars($date ?? '', ENT_QUOTES, 'UTF-8')
                ],
                'userName' => $_SESSION['user_name'] ?? 'User'
            ];

            $this->render('expenses/create', $data);
            return;
        }

        try {
            $userId = $_SESSION['user_id'];

            // Create expense
            $this->expenseModel->create(
                $userId,
                (float)$amount,
                $categoryId ? (int)$categoryId : null,
                $description ? htmlspecialchars($description, ENT_QUOTES, 'UTF-8') : null,
                $date
            );

            // Redirect to expense list with success message
            $_SESSION['success'] = 'Expense added successfully!';
            $this->redirect('/expense/index');

        } catch (Exception $e) {
            $userId = $_SESSION['user_id'];
            $categories = $this->categoryModel->getAllByUser($userId);

            $data = [
                'page_title' => 'Add Expense',
                'categories' => $categories,
                'error' => 'Failed to add expense: ' . $e->getMessage(),
                'formData' => [
                    'amount' => htmlspecialchars($amount ?? '', ENT_QUOTES, 'UTF-8'),
                    'category_id' => htmlspecialchars($categoryId ?? '', ENT_QUOTES, 'UTF-8'),
                    'description' => htmlspecialchars($description ?? '', ENT_QUOTES, 'UTF-8'),
                    'date' => htmlspecialchars($date ?? '', ENT_QUOTES, 'UTF-8')
                ],
                'userName' => $_SESSION['user_name'] ?? 'User'
            ];

            $this->render('expenses/create', $data);
        }
    }

    /**
     * Display expense edit form
     * GET /expense/edit/:id
     */
    public function edit($id = null) {
        // Verify user is logged in
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('/auth/login');
            return;
        }

        if (!$id) {
            $this->redirect('/expense/index');
            return;
        }

        try {
            $userId = $_SESSION['user_id'];

            // Get expense by ID (with user ownership check)
            $expense = $this->expenseModel->getById((int)$id, $userId);

            if (!$expense) {
                $_SESSION['error'] = 'Expense not found';
                $this->redirect('/expense/index');
                return;
            }

            // Get categories for dropdown
            $categories = $this->categoryModel->getAllByUser($userId);

            $data = [
                'page_title' => 'Edit Expense',
                'expense' => $expense,
                'categories' => $categories,
                'userName' => $_SESSION['user_name'] ?? 'User'
            ];

            $this->render('expenses/edit', $data);

        } catch (Exception $e) {
            $_SESSION['error'] = 'Failed to load expense: ' . $e->getMessage();
            $this->redirect('/expense/index');
        }
    }

    /**
     * Update expense in database
     * POST /expense/update/:id
     */
    public function update($id = null) {
        // Verify user is logged in
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('/auth/login');
            return;
        }

        // Verify CSRF token
        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== ($_SESSION['csrf_token'] ?? '')) {
            http_response_code(403);
            die('CSRF token validation failed');
        }

        if (!$id) {
            $this->redirect('/expense/index');
            return;
        }

        // Validate required fields
        $errors = [];

        $amount = $_POST['amount'] ?? null;
        $categoryId = $_POST['category_id'] ?? null;
        $description = $_POST['description'] ?? null;
        $date = $_POST['date'] ?? null;

        if (!$amount || $amount <= 0) {
            $errors['amount'] = 'Amount must be a positive number';
        }

        if (!$date) {
            $errors['date'] = 'Date is required';
        }

        if (!empty($errors)) {
            // Re-render form with errors
            $userId = $_SESSION['user_id'];
            $expense = $this->expenseModel->getById((int)$id, $userId);
            $categories = $this->categoryModel->getAllByUser($userId);

            $data = [
                'page_title' => 'Edit Expense',
                'expense' => $expense,
                'categories' => $categories,
                'errors' => $errors,
                'userName' => $_SESSION['user_name'] ?? 'User'
            ];

            $this->render('expenses/edit', $data);
            return;
        }

        try {
            $userId = $_SESSION['user_id'];

            // Update expense
            $this->expenseModel->update(
                (int)$id,
                $userId,
                (float)$amount,
                $categoryId ? (int)$categoryId : null,
                $description ? htmlspecialchars($description, ENT_QUOTES, 'UTF-8') : null,
                $date
            );

            // Redirect to expense list with success message
            $_SESSION['success'] = 'Expense updated successfully!';
            $this->redirect('/expense/index');

        } catch (Exception $e) {
            $userId = $_SESSION['user_id'];
            $expense = $this->expenseModel->getById((int)$id, $userId);
            $categories = $this->categoryModel->getAllByUser($userId);

            $data = [
                'page_title' => 'Edit Expense',
                'expense' => $expense,
                'categories' => $categories,
                'error' => 'Failed to update expense: ' . $e->getMessage(),
                'userName' => $_SESSION['user_name'] ?? 'User'
            ];

            $this->render('expenses/edit', $data);
        }
    }

    /**
     * Delete expense from database
     * POST /expense/delete/:id
     */
    public function delete($id = null) {
        // Verify user is logged in
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('/auth/login');
            return;
        }

        // Verify CSRF token
        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== ($_SESSION['csrf_token'] ?? '')) {
            http_response_code(403);
            die('CSRF token validation failed');
        }

        if (!$id) {
            $this->redirect('/expense/index');
            return;
        }

        try {
            $userId = $_SESSION['user_id'];

            // Delete expense (user ownership check built into model)
            $deleted = $this->expenseModel->delete((int)$id, $userId);

            if (!$deleted) {
                $_SESSION['error'] = 'Expense not found or already deleted';
            } else {
                $_SESSION['success'] = 'Expense deleted successfully!';
            }

            $this->redirect('/expense/index');

        } catch (Exception $e) {
            $_SESSION['error'] = 'Failed to delete expense: ' . $e->getMessage();
            $this->redirect('/expense/index');
        }
    }
}

