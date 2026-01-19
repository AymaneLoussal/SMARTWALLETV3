<?php

use core\Controller;


class CategoryController extends Controller {

    private $categoryModel;

    
    public function __construct() {
        $this->categoryModel = $this->model('Category');
    }

    
    public function index() {
        // Redirect to login if not authenticated
        if (!$this->isAuthenticated()) {
            $this->redirect('/auth/login');
            return;
        }

        try {
            $userId = $_SESSION['user_id'];

            // Get all categories from database
            $allCategories = $this->categoryModel->getAllByUser($userId);

            // Separate into income and expense categories
            $incomeCategories = [];
            $expenseCategories = [];

            if (!empty($allCategories)) {
                // Get predefined category lists to determine type
                $incomeList = array_keys(\Category::getIncomeCategories());
                $expenseList = array_keys(\Category::getExpenseCategories());

                foreach ($allCategories as $category) {
                    if (in_array($category['name'], $incomeList)) {
                        $incomeCategories[] = $category;
                    } elseif (in_array($category['name'], $expenseList)) {
                        $expenseCategories[] = $category;
                    }
                }
            }

            $data = [
                'page_title' => 'Category Management',
                'userName' => $_SESSION['user_name'] ?? 'User',
                'incomeCategories' => $incomeCategories,
                'expenseCategories' => $expenseCategories
            ];

            // If categories are empty, show info message
            if (empty($incomeCategories) && empty($expenseCategories)) {
                $data['info'] = 'Categories are not populated yet. Please visit the setup page to initialize categories.';
            }

            // Render categories view
            $this->render('categories/index', $data);

        } catch (Exception $e) {
            $data = [
                'page_title' => 'Category Management',
                'error' => 'Failed to load categories: ' . $e->getMessage(),
                'userName' => $_SESSION['user_name'] ?? 'User',
                'incomeCategories' => [],
                'expenseCategories' => []
            ];
            $this->render('categories/index', $data);
        }
    }
}
