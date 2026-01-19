<?php

use core\Controller;


class DashboardController extends Controller {

    
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
