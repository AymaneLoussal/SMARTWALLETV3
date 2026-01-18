<?php
/**
 * Application Test - Verify no constructor errors
 */

session_start();
require_once "../config/config.php";
require_once "../app/core/Database.php";
require_once "../app/core/Model.php";
require_once "../app/core/Controller.php";
require_once "../app/core/App.php";

try {
    echo "✅ Application loading...\n";
    new \core\App();
    echo "✅ Application loaded successfully!\n";
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    die();
}
?>
