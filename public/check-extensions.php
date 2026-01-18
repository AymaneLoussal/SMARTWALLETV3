<?php
/**
 * PHP Extension Check
 * Check what PDO drivers are available
 */

echo "=== PHP Extension Check ===\n\n";

echo "PHP Version: " . phpversion() . "\n";
echo "PHP SAPI: " . php_sapi_name() . "\n\n";

echo "=== PDO Drivers Available ===\n";
$drivers = PDO::getAvailableDrivers();
if (count($drivers) > 0) {
    foreach ($drivers as $driver) {
        echo "  ✅ " . $driver . "\n";
    }
} else {
    echo "  ❌ NO PDO DRIVERS AVAILABLE\n";
}

echo "\n=== Loaded PHP Extensions ===\n";
$extensions = get_loaded_extensions();
$pdo_extensions = array_filter($extensions, function($ext) {
    return stripos($ext, 'pdo') !== false;
});

if (count($pdo_extensions) > 0) {
    foreach ($pdo_extensions as $ext) {
        echo "  ✅ " . $ext . "\n";
    }
} else {
    echo "  ❌ NO PDO EXTENSIONS LOADED\n";
}

echo "\n=== PostgreSQL Support ===\n";
if (in_array('pgsql', $drivers)) {
    echo "  ✅ PostgreSQL PDO driver is available\n";
} else {
    echo "  ❌ PostgreSQL PDO driver is NOT available\n";
    echo "\n  To fix this:\n";
    echo "  1. Using Laragon:\n";
    echo "     - Open Laragon Menu → PHP → Extensions\n";
    echo "     - Enable: php_pdo.dll\n";
    echo "     - Enable: php_pdo_pgsql.dll\n";
    echo "     - Restart Apache\n\n";
    echo "  2. Manual fix:\n";
    echo "     - Edit php.ini\n";
    echo "     - Find: ;extension=pdo_pgsql\n";
    echo "     - Remove the semicolon: extension=pdo_pgsql\n";
    echo "     - Restart PHP/Apache\n";
}

echo "\n";
?>
