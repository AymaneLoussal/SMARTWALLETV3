<?php

namespace core;
/**
 * Database Singleton Class
 *
 * Manages PDO database connection using singleton pattern
 * Ensures only one database connection throughout application
 */
class Database
{
    private static $instance = null;
    private $pdo;

    /**
     * Private constructor - use getInstance() instead
     *
     * @param string $dsn - Database connection string
     * @param string $username - Database username
     * @param string $password - Database password
     * @throws Exception
     */
    private function __construct($dsn, $username, $password)
    {
        try {
            $this->pdo = new \PDO($dsn, $username, $password);
            $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            $this->pdo->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            // Log the actual error for debugging
            $errorMsg = "Database connection error: " . $e->getMessage() . " (DSN: {$dsn})";
            error_log($errorMsg);

            // In development, show the actual error; in production, show generic message
            if (defined('APP_DEBUG') && APP_DEBUG === true) {
                throw new \Exception("Database connection failed: " . $e->getMessage());
            } else {
                throw new \Exception("Database connection failed. Please contact administrator.");
            }
        }
    }

    /**
     * Get singleton instance of Database
     * Initializes connection if not already created
     *
     * @param string $dsn - Optional: Database connection string
     * @param string $username - Optional: Database username
     * @param string $password - Optional: Database password
     * @return Database - Singleton instance
     */
    public static function getInstance($dsn = null, $username = null, $password = null)
    {
        if (self::$instance === null) {
            // Use provided credentials or fall back to config constants
            $dsn = $dsn ?? 'pgsql:host=' . DB_HOST . ';dbname=' . DB_NAME;
            $username = $username ?? DB_USER;
            $password = $password ?? DB_PASS;

            self::$instance = new Database($dsn, $username, $password);
        }
        return self::$instance;
    }

    /**
     * Get PDO connection object
     *
     * @return PDO
     */
    public function getConnection()
    {
        return $this->pdo;
    }
}

