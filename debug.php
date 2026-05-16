<?php
// TEMPORARY DEBUG FILE - DELETE AFTER USE
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<pre>";

// 1. Check PHP version
echo "PHP: " . PHP_VERSION . "\n\n";

// 2. Check .env exists
$envPath = __DIR__ . '/.env';
echo ".env exists: " . (file_exists($envPath) ? "YES" : "NO") . "\n";

// 3. Check vendor/autoload.php
echo "vendor/autoload.php: " . (file_exists(__DIR__ . '/vendor/autoload.php') ? "YES" : "NO") . "\n";

// 4. Load autoloader
try {
    require 'vendor/autoload.php';
    echo "autoload: OK\n";
} catch (Throwable $e) {
    echo "autoload ERROR: " . $e->getMessage() . "\n";
    exit;
}

// 5. Load .env
try {
    if (file_exists($envPath)) {
        $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
        $dotenv->load();
        echo ".env loaded: OK\n";
        echo "DB_HOST: " . ($_ENV['DB_HOST'] ?? 'NOT SET') . "\n";
        echo "DB_NAME: " . ($_ENV['DB_NAME'] ?? 'NOT SET') . "\n";
        echo "DB_USER: " . ($_ENV['DB_USER'] ?? 'NOT SET') . "\n";
        echo "DB_PASS: " . (isset($_ENV['DB_PASS']) ? '***SET***' : 'NOT SET') . "\n";
    } else {
        echo ".env: NOT FOUND - using fallback\n";
    }
} catch (Throwable $e) {
    echo ".env ERROR: " . $e->getMessage() . "\n";
}

// 6. Test DB connection
try {
    $dsn  = 'mysql:host=' . ($_ENV['DB_HOST'] ?? 'localhost') . ';port=' . ($_ENV['DB_PORT'] ?? '3306') . ';dbname=' . ($_ENV['DB_NAME'] ?? 'prayer_times');
    $user = $_ENV['DB_USER'] ?? 'root';
    $pass = $_ENV['DB_PASS'] ?? '';
    $pdo  = new PDO($dsn, $user, $pass);
    echo "DB connection: OK\n";
} catch (Throwable $e) {
    echo "DB ERROR: " . $e->getMessage() . "\n";
}

// 7. Check F3
try {
    if (!class_exists('Base')) {
        require __DIR__ . '/vendor/bcosca/fatfree-core/base.php';
    }
    $f3 = \Base::instance();
    echo "F3: OK\n";
    echo "F3 version: " . \Base::VERSION . "\n";
} catch (Throwable $e) {
    echo "F3 ERROR: " . $e->getMessage() . "\n";
}

echo "</pre>";
echo "<br><strong style='color:red'>DELETE THIS FILE AFTER DEBUGGING</strong>";
