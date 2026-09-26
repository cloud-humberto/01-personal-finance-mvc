<?php
declare(strict_types=1);

namespace App\Models;

use App\Core\Database;

class Category
{
    public static function all(): array
    {
        $db = Database::getConnection();
        $stmt = $db->query("SELECT * FROM categories ORDER BY type DESC, name ASC");
        return $stmt->fetchAll();
    }

    public static function findById(int $id): ?array
    {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT * FROM categories WHERE id = :id LIMIT 1");
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }
}
