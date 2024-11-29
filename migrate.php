<?php declare(strict_types=1);

require_once __DIR__ . '/vendor/autoload.php';


$dotenv = \Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$host = $_ENV['DB_HOST'] ?? 'pgsql';
$port = $_ENV['DB_PORT'] ?? '5432';
$dbName = $_ENV['DB_NAME'] ?? 'DB_NAME';

$dsn = "pgsql:host={$host};port={$port};dbname={$dbName}";
$username = $_ENV['DB_USERNAME'] ?? 'user';
$password = $_ENV['DB_PASSWORD'] ?? 'password';

try {
    $pdo = new PDO($dsn, $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);

    echo "Connected to the database successfully.\n";

    $pdo->exec("
        CREATE TABLE IF NOT EXISTS users (
            id SERIAL PRIMARY KEY,
            username VARCHAR(255) NOT NULL UNIQUE,
            password VARCHAR(255) NOT NULL,
            role VARCHAR(50) DEFAULT 'user',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT NULL
        )
    ");
    echo "Table 'users' created successfully.\n";

    $pdo->exec("
        CREATE TABLE tasks (
            id SERIAL PRIMARY KEY,
            username VARCHAR(255) NOT NULL,
            email VARCHAR(255) NOT NULL,
            description TEXT NOT NULL,
            status BOOLEAN DEFAULT FALSE,
            created_at TIMESTAMP DEFAULT NOW(),
            updated_at TIMESTAMP DEFAULT NULL,
            updated_by INT REFERENCES users(id) ON DELETE SET NULL
        )
    ");
    echo "Table 'tasks' created successfully.\n";

    $adminPassword = password_hash('123', PASSWORD_DEFAULT);
    $pdo->exec("
        INSERT INTO users (username, password, role)
        VALUES ('admin', '$adminPassword', 'admin')
        ON CONFLICT (username) DO NOTHING
    ");
    echo "Admin user created successfully.\n";

} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}
