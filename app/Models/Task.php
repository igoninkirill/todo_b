<?php declare(strict_types=1);

namespace App\Models;

use PDO;

class Task extends Model
{
    public function getPaginated(string $sortBy, string $orderBy, int $page, int $perPage): array
    {
        $allowedSorts = ['id', 'username', 'email', 'status'];
        $allowedOrders = ['asc', 'desc'];
        $sortBy = in_array($sortBy, $allowedSorts) ? $sortBy : 'id';
        $orderBy = in_array($orderBy, $allowedOrders) ? $orderBy : 'asc';

        $offset = ($page - 1) * $perPage;

        $stmt = self::$db->prepare("SELECT
        tasks.id, tasks.username, tasks.description, tasks.email, tasks.status, users.username as updater_name
            FROM tasks
            LEFT JOIN users ON tasks.updated_by = users.id
            ORDER BY tasks.$sortBy $orderBy
            LIMIT :limit OFFSET :offset");

        $stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTotalCount(): int
    {
        $stmt = self::$db->query("SELECT COUNT(*) FROM tasks");
        return (int)$stmt->fetchColumn();
    }

    public function create(array $data): bool
    {
        $stmt = self::$db->prepare("INSERT INTO tasks (username, email, description)
            VALUES (:username, :email, :description)");
        return $stmt->execute([
            ':username' => $data['username'],
            ':email' => $data['email'],
            ':description' => $data['description'],
        ]);
    }

    public function find(mixed $id): ?array
    {
        $stmt = self::$db->prepare("SELECT * FROM tasks WHERE id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function update(mixed $id, array $data): bool
    {
        $stmt = self::$db->prepare("UPDATE tasks SET description = :description, status = :status, updated_by = :updated_by
             WHERE id = :id");
        return $stmt->execute([
            ':description' => $data['description'],
            ':updated_by' => $data['updated_by'] ?? null,
            ':status' => $data['status'] ? 1 : 0,
            ':id' => $id,
        ]);
    }
}
