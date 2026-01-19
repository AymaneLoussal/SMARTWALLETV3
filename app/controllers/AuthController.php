<?php

use core\Controller;


class AuthController extends Controller {

    private $userModel;

   
    public function __construct() {
        $this->userModel = $this->model('User');
    }

    
    public function register() {
        // Redirect if already authenticated
        if ($this->isAuthenticated()) {
            $this->redirect('/dashboard/index');
        }

        // Generate CSRF token for form
        $csrf_token = $this->generateCSRFToken();

        $data = [
            'csrf_token' => $csrf_token,
            'errors' => $_SESSION['errors'] ?? [],
            'old' => $_SESSION['old'] ?? []
        ];

        // Clear session messages after displaying
        unset($_SESSION['errors'], $_SESSION['old']);

        $this->view('auth/register', $data);
    }

    
    public function handleRegister() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/auth/register');
        }

        // Validate CSRF token
        if (!$this->validateCSRFToken($_POST['csrf_token'] ?? '')) {
            $_SESSION['error'] = "Invalid form submission. Please try again.";
            $this->redirect('/auth/register');
        }

        // Sanitize input data
        $data = [
            'full_name' => $this->sanitize($_POST['full_name'] ?? ''),
            'email' => $this->sanitize($_POST['email'] ?? ''),
            'password' => $_POST['password'] ?? '',
            'confirm_password' => $_POST['confirm_password'] ?? ''
        ];

        // Validate input data
        $errors = $this->validate($data, [
            'full_name' => 'required|min:3',
            'email' => 'required|email',
            'password' => 'required|min:6'
        ]);

        // Check password confirmation match
        if ($data['password'] !== $data['confirm_password']) {
            $errors['confirm_password'] = "Passwords do not match.";
        }

        // Check if email already exists
        if ($this->userModel->emailExists($data['email'])) {
            $errors['email'] = "Email is already registered.";
        }

        // If validation errors, return to form
        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['old'] = $data;
            $this->redirect('/auth/register');
        }

        // Attempt registration
        try {
            $this->userModel->register(
                $data['full_name'],
                $data['email'],
                $data['password']
            );

            $_SESSION['success'] = "Registration successful! You can now login.";
            $this->redirect('/auth/login');
        } catch (Exception $e) {
            $_SESSION['error'] = "Registration failed: " . $e->getMessage();
            $_SESSION['old'] = $data;
            $this->redirect('/auth/register');
        }
    }

    
    public function login() {
        // Redirect if already authenticated
        if ($this->isAuthenticated()) {
            $this->redirect('/dashboard/index');
        }

        // Generate CSRF token for form
        $csrf_token = $this->generateCSRFToken();

        $data = [
            'csrf_token' => $csrf_token,
            'error' => $_SESSION['error'] ?? null,
            'success' => $_SESSION['success'] ?? null
        ];

        // Clear session messages after displaying
        unset($_SESSION['error'], $_SESSION['success']);

        $this->view('auth/login', $data);
    }

   
    public function handleLogin() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/auth/login');
        }

        // Validate CSRF token
        if (!$this->validateCSRFToken($_POST['csrf_token'] ?? '')) {
            $_SESSION['error'] = "Invalid form submission. Please try again.";
            $this->redirect('/auth/login');
        }

        // Sanitize and validate input
        $email = $this->sanitize($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        if (empty($email) || empty($password)) {
            $_SESSION['error'] = "Email and password are required.";
            $this->redirect('/auth/login');
        }

        // Attempt authentication
        try {
            // Attempt login
            $loginSuccess = $this->userModel->login($email, $password);

            if ($loginSuccess) {
                // Get user data for session
                $user = $this->userModel->findByEmail($email);

                if ($user) {
                    // Regenerate session ID for security
                    session_regenerate_id(true);

                    // Set session variables
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['user_name'] = $user['full_name'];
                    $_SESSION['user_email'] = $user['email'];

                    $_SESSION['success'] = "Welcome back, " . $user['full_name'] . "!";
                    $this->redirect('/dashboard/index');
                }
            }

            // This should not happen but handle it
            $_SESSION['error'] = "Login failed. Please try again.";
            $this->redirect('/auth/login');

        } catch (Exception $e) {
            // Generic error message (don't reveal if email exists)
            $_SESSION['error'] = "Invalid email or password.";
            $this->redirect('/auth/login');
        }
    }

    public function logout() {
        // Destroy session securely
        $_SESSION = array();

        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params["path"],
                $params["domain"],
                $params["secure"],
                $params["httponly"]
            );
        }

        session_destroy();

        $_SESSION = array(); // Clear session array
        $this->redirect('/auth/login');
    }

   
    protected function generateCSRFToken() {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    
    protected function validateCSRFToken($token) {
        return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
    }
}