<?php

namespace core;
/**
 * Controller Base Class
 *
 * Provides common functionality for all controllers
 */
class Controller
{

    /**
     * Render a view with data
     * Alias for view() method - supports both naming conventions
     *
     * @param string $view - View file path
     * @param array $data - Data to pass to view
     */
    protected function render($view, $data = [])
    {
        $this->view($view, $data);
    }

    /**
     * Display a view with data
     *
     * @param string $view - View file path (without .php extension)
     * @param array $data - Data to pass to view
     */
    protected function view($view, $data = [])
    {
        extract($data);

        $viewPath = "../app/views/{$view}.php";

        if (file_exists($viewPath)) {
            require_once $viewPath;
        } else {
            die("View '{$view}' not found at path: {$viewPath}");
        }
    }

    /**
     * Load and instantiate a model
     *
     * @param string $model - Model class name
     * @return object - Instantiated model
     */
    protected function model($model)
    {
        $modelPath = "../app/models/{$model}.php";

        if (file_exists($modelPath)) {
            require_once $modelPath;
            return new $model();
        } else {
            die("Model '{$model}' not found at path: {$modelPath}");
        }
    }

    /**
     * Redirect to a different URL
     *
     * @param string $url - URL to redirect to (relative to BASE_URL)
     */
    protected function redirect($url)
    {
        header("Location: " . BASE_URL . $url);
        exit();
    }

    /**
     * Check if user is authenticated
     *
     * @return bool
     */
    protected function isAuthenticated()
    {
        return isset($_SESSION['user_id']);
    }

    /**
     * Get current user ID from session
     *
     * @return int|null
     */
    protected function getUserId()
    {
        return $_SESSION['user_id'] ?? null;
    }

    /**
     * Require authentication - redirect to login if not authenticated
     */
    protected function requireAuth()
    {
        if (!$this->isAuthenticated()) {
            $this->redirect('/auth/login');
        }
    }

    /**
     * Validate input data against rules
     *
     * @param array $data - Data to validate
     * @param array $rules - Validation rules
     * @return array - Array of errors (empty if valid)
     */
    protected function validate($data, $rules)
    {
        $errors = [];

        foreach ($rules as $field => $rule) {
            $value = $data[$field] ?? null;

            if (strpos($rule, 'required') !== false && empty($value)) {
                $errors[$field] = "The {$field} field is required.";
                continue;
            }

            if (strpos($rule, 'email') !== false && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                $errors[$field] = "The {$field} must be a valid email address.";
            }

            if (preg_match('/min:(\d+)/', $rule, $matches)) {
                $min = $matches[1];
                if (strlen($value) < $min) {
                    $errors[$field] = "The {$field} must be at least {$min} characters.";
                }
            }

            if (strpos($rule, 'numeric') !== false && !is_numeric($value)) {
                $errors[$field] = "The {$field} must be numeric.";
            }
        }

        return $errors;
    }

    /**
     * Sanitize input data - remove tags and escape special characters
     *
     * @param mixed $data - Data to sanitize
     * @return mixed - Sanitized data
     */
    protected function sanitize($data)
    {
        if (is_array($data)) {
            return array_map([$this, 'sanitize'], $data);
        }
        return htmlspecialchars(strip_tags(trim($data)), ENT_QUOTES, 'UTF-8');
    }

    /**
     * Return JSON response
     *
     * @param array $data - Data to encode as JSON
     * @param int $statusCode - HTTP status code
     */
    protected function json($data, $statusCode = 200)
    {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit();
    }

    /**
     * Generate a CSRF token and store in session
     *
     * @return string - CSRF token
     */
    protected function generateCSRFToken()
    {
        if (!isset($_SESSION['csrf_token']) || empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    /**
     * Validate CSRF token from form submission
     *
     * @param string $token - Token to validate
     * @return bool - True if valid, false otherwise
     */
    protected function validateCSRFToken($token)
    {
        if (!isset($_SESSION['csrf_token'])) {
            return false;
        }

        return hash_equals($_SESSION['csrf_token'], $token ?? '');
    }
}

