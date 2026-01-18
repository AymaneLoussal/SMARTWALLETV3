<?php

use core\Controller;

/**
 * Dashboard Controller
 *
 * Handles dashboard/home page functionality
 */
class DashboardController extends Controller {

    /**
     * Display main dashboard
     * GET /dashboard/index
     */
    public function index() {
        // Redirect to login if not authenticated
        if (!$this->isAuthenticated()) {
            $this->redirect('/auth/login');
            return;
        }

        $data = [
            'page_title' => 'Dashboard',
            'userName' => $_SESSION['user_name'] ?? 'User'
        ];

        // Render dashboard view
        $this->render('dashboard/index', $data);
    }
}
