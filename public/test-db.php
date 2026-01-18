<?php
/**
 * Database Connection Test
 * This file tests the PostgreSQL connection
 */

require_once "../config/config.php";

echo "=== Database Connection Test ===\n\n";

// Display configuration
echo "Database Configuration:\n";
echo "Host: " . DB_HOST . "\n";
echo "Name: " . DB_NAME . "\n";
echo "User: " . DB_USER . "\n";
echo "Pass: " . (DB_PASS ? "***" : "EMPTY") . "\n\n";

// Build DSN
$dsn = 'pgsql:host=' . DB_HOST . ';dbname=' . DB_NAME;
echo "DSN: " . $dsn . "\n\n";

// Try to connect
echo "Attempting connection...\n";
try {
    $pdo = new PDO($dsn, DB_USER, DB_PASS);
    echo "✅ SUCCESS: Connected to PostgreSQL!\n";
    echo "Driver: " . $pdo->getAttribute(PDO::ATTR_DRIVER_NAME) . "\n";
    echo "Server Version: " . $pdo->getAttribute(PDO::ATTR_SERVER_VERSION) . "\n";
} catch (PDOException $e) {
    echo "❌ ERROR: Connection failed!\n";
    echo "Error Code: " . $e->getCode() . "\n";
    echo "Error Message: " . $e->getMessage() . "\n";
    echo "\nPossible causes:\n";
    echo "1. PostgreSQL server is not running\n";
    echo "2. Host is incorrect (should be 'localhost' or '127.0.0.1')\n";
    echo "3. Database '" . DB_NAME . "' does not exist\n";
    echo "4. Username or password is incorrect\n";
    echo "5. PostgreSQL is not installed or accessible\n";
}
?>
