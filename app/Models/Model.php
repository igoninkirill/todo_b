<?php declare(strict_types=1);

namespace App\Models;

use PDO;

abstract class Model
{
    protected static PDO $db;

    public function __construct()
    {
        $this->init();
    }

    protected function init(): void
    {
        $host = $_ENV['DB_HOST'] ?? 'pgsql';
        $port = $_ENV['DB_PORT'] ?? '5432';
        $dbName = $_ENV['DB_NAME'] ?? 'DB_NAME';
        $username = $_ENV['DB_USERNAME'] ?? 'user';
        $password = $_ENV['DB_PASSWORD'] ?? 'password';

        self::$db = new PDO("pgsql:host={$host};port={$port};dbname={$dbName}", $username, $password);
    }
}
