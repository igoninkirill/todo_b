<?php

namespace App\Models;

class User extends Model
{
    public function findByUsername(string $username): ?array
    {
        $stmt = self::$db->prepare('SELECT * FROM users WHERE username = :username');
        $stmt->execute(['username' => $username]);

        return $stmt->fetch() ?: null;
    }
}
