<?php

use core\Controller;

/**
 * Category Controller
 *
 * Handles category management functionality
 */
class CategoryController extends Controller {

    /**
     * Display all categories
     * GET /categories/index
     */
    public function index() {
        // Redirect to login if not authenticated
        if (!$this->isAuthenticated()) {
            $this->redirect('/auth/login');
            return;
        }

        $data = [
            'page_title' => 'Categories',
            'userName' => $_SESSION['user_name'] ?? 'User'
        ];

        // Render categories view
        $this->render('categories/index', $data);
    }
}
