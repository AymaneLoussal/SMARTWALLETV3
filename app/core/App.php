<?php

namespace core;
/**
 * App Router/Front Controller
 *
 * Parses URL requests and routes them to appropriate controllers and method
 * Supports URL structure: /ControllerName/methodName/param1/param2
 */
class App
{
    protected $controller = 'DashboardController';
    protected $method = 'index';
    protected $params = [];

    /**
     * Constructor - Parse URL and dispatch to controllers
     */
    public function __construct()
    {
        $url = $this->parseUrl();

        // Check if controllers file exists
        if (file_exists("../app/controllers/" . ucfirst($url[0]) . "Controller.php")) {
            $this->controller = ucfirst($url[0]) . "Controller";
            unset($url[0]);
        }

        // Load and instantiate controllers
        require_once "../app/controllers/" . $this->controller . ".php";
        $this->controller = new $this->controller;

        // Check if method exists and set it
        if (isset($url[1]) && method_exists($this->controller, $url[1])) {
            $this->method = $url[1];
            unset($url[1]);
        }

        // Get remaining URL segments as parameters
        $this->params = $url ? array_values($url) : [];

        // Call controllers method with parameters
        call_user_func_array([$this->controller, $this->method], $this->params);
    }

    /**
     * Parse URL from GET parameter
     *
     * Expected format: /ControllerName/methodName/param1/param2
     *
     * @return array - URL segments
     */
    private function parseUrl()
    {
        return isset($_GET['url']) ? explode('/', filter_var(rtrim($_GET['url'], '/'), FILTER_SANITIZE_URL)) : [];
    }
}


