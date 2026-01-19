<?php

namespace core;

class Controller
{

   
    protected function render($view, $data = [])
    {
        $this->view($view, $data);
    }

    
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

    
    protected function redirect($url)
    {
        header("Location: " . BASE_URL . $url);
        exit();
    }

    
    protected function isAuthenticated()
    {
        return isset($_SESSION['user_id']);
    }

   
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

    
    protected function sanitize($data)
    {
        if (is_array($data)) {
            return array_map([$this, 'sanitize'], $data);
        }
        return htmlspecialchars(strip_tags(trim($data)), ENT_QUOTES, 'UTF-8');
    }

    
    protected function json($data, $statusCode = 200)
    {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit();
    }

    
    protected function generateCSRFToken()
    {
        if (!isset($_SESSION['csrf_token']) || empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    
    protected function validateCSRFToken($token)
    {
        if (!isset($_SESSION['csrf_token'])) {
            return false;
        }

        return hash_equals($_SESSION['csrf_token'], $token ?? '');
    }
}

