<?php

use core\Controller;

/**
 * Income Controller
 *
 * Handles all income-related requests
 * Manages income CRUD operations and views
 *
 * @package App\Controllers
 */
class IncomeController extends Controller {

    private $incomeModel;
    private $categoryModel;

    /**
     * Constructor
     */
    public function __construct() {
        // Only call parent constructor if it exists
        if (method_exists(get_parent_class($this), '__construct')) {
            parent::__construct();
        }

        // Load models using parent class method
        $this->incomeModel = $this->model('Income');
        $this->categoryModel = $this->model('Category');
    }

    /**
     * Display list of all incomes for current user
     * GET /income/index
     */
    public function index() {
        // Verify user is logged in
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('/auth/login');
            return;
        }

        try {
            $userId = $_SESSION['user_id'];

            // Get all incomes for user
            $incomes = $this->incomeModel->getAll($userId);

            // Get categories for filter
            $categories = $this->categoryModel->getAllByUser($userId);

            // Calculate statistics
            $totalIncome = $this->incomeModel->getTotal($userId);

            // Prepare data for view
            $data = [
                'page_title' => 'Income Management',
                'incomes' => $incomes,
                'categories' => $categories,
                'totalIncome' => $totalIncome,
                'userName' => $_SESSION['user_name'] ?? 'User'
            ];

            $this->render('incomes/index', $data);

        } catch (Exception $e) {
            $data = [
                'page_title' => 'Income Management',
                'error' => 'Failed to load incomes: ' . $e->getMessage()
            ];
            $this->render('incomes/index', $data);
        }
    }

    /**
     * Display income creation form
     * GET /income/create
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
                'page_title' => 'Add Income',
                'categories' => $categories,
                'userName' => $_SESSION['user_name'] ?? 'User'
            ];

            $this->render('incomes/create', $data);

        } catch (Exception $e) {
            $data = [
                'page_title' => 'Add Income',
                'error' => 'Failed to load form: ' . $e->getMessage()
            ];
            $this->render('incomes/create', $data);
        }
    }

    /**
     * Store new income in database
     * POST /income/store
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
                'page_title' => 'Add Income',
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

            $this->render('incomes/create', $data);
            return;
        }

        try {
            $userId = $_SESSION['user_id'];

            // Create income
            $this->incomeModel->create(
                $userId,
                (float)$amount,
                $categoryId ? (int)$categoryId : null,
                $description ? htmlspecialchars($description, ENT_QUOTES, 'UTF-8') : null,
                $date
            );

            // Redirect to income list with success message
            $_SESSION['success'] = 'Income added successfully!';
            $this->redirect('/income/index');

        } catch (Exception $e) {
            $userId = $_SESSION['user_id'];
            $categories = $this->categoryModel->getAllByUser($userId);

            $data = [
                'page_title' => 'Add Income',
                'categories' => $categories,
                'error' => 'Failed to add income: ' . $e->getMessage(),
                'formData' => [
                    'amount' => htmlspecialchars($amount ?? '', ENT_QUOTES, 'UTF-8'),
                    'category_id' => htmlspecialchars($categoryId ?? '', ENT_QUOTES, 'UTF-8'),
                    'description' => htmlspecialchars($description ?? '', ENT_QUOTES, 'UTF-8'),
                    'date' => htmlspecialchars($date ?? '', ENT_QUOTES, 'UTF-8')
                ],
                'userName' => $_SESSION['user_name'] ?? 'User'
            ];

            $this->render('incomes/create', $data);
        }
    }

    /**
     * Display income edit form
     * GET /income/edit/:id
     */
    public function edit($id = null) {
        // Verify user is logged in
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('/auth/login');
            return;
        }

        if (!$id) {
            $this->redirect('/income/index');
            return;
        }

        try {
            $userId = $_SESSION['user_id'];

            // Get income by ID (with user ownership check)
            $income = $this->incomeModel->getById((int)$id, $userId);

            if (!$income) {
                $_SESSION['error'] = 'Income not found';
                $this->redirect('/income/index');
                return;
            }

            // Get categories for dropdown
            $categories = $this->categoryModel->getAllByUser($userId);

            $data = [
                'page_title' => 'Edit Income',
                'income' => $income,
                'categories' => $categories,
                'userName' => $_SESSION['user_name'] ?? 'User'
            ];

            $this->render('incomes/edit', $data);

        } catch (Exception $e) {
            $_SESSION['error'] = 'Failed to load income: ' . $e->getMessage();
            $this->redirect('/income/index');
        }
    }

    /**
     * Update income in database
     * POST /income/update/:id
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
            $this->redirect('/income/index');
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
            $income = $this->incomeModel->getById((int)$id, $userId);
            $categories = $this->categoryModel->getAllByUser($userId);

            $data = [
                'page_title' => 'Edit Income',
                'income' => $income,
                'categories' => $categories,
                'errors' => $errors,
                'userName' => $_SESSION['user_name'] ?? 'User'
            ];

            $this->render('incomes/edit', $data);
            return;
        }

        try {
            $userId = $_SESSION['user_id'];

            // Update income
            $this->incomeModel->update(
                (int)$id,
                $userId,
                (float)$amount,
                $categoryId ? (int)$categoryId : null,
                $description ? htmlspecialchars($description, ENT_QUOTES, 'UTF-8') : null,
                $date
            );

            // Redirect to income list with success message
            $_SESSION['success'] = 'Income updated successfully!';
            $this->redirect('/income/index');

        } catch (Exception $e) {
            $userId = $_SESSION['user_id'];
            $income = $this->incomeModel->getById((int)$id, $userId);
            $categories = $this->categoryModel->getAllByUser($userId);

            $data = [
                'page_title' => 'Edit Income',
                'income' => $income,
                'categories' => $categories,
                'error' => 'Failed to update income: ' . $e->getMessage(),
                'userName' => $_SESSION['user_name'] ?? 'User'
            ];

            $this->render('incomes/edit', $data);
        }
    }

    /**
     * Delete income from database
     * POST /income/delete/:id
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
            $this->redirect('/income/index');
            return;
        }

        try {
            $userId = $_SESSION['user_id'];

            // Delete income (user ownership check built into model)
            $deleted = $this->incomeModel->delete((int)$id, $userId);

            if (!$deleted) {
                $_SESSION['error'] = 'Income not found or already deleted';
            } else {
                $_SESSION['success'] = 'Income deleted successfully!';
            }

            $this->redirect('/income/index');

        } catch (Exception $e) {
            $_SESSION['error'] = 'Failed to delete income: ' . $e->getMessage();
            $this->redirect('/income/index');
        }
    }
}
