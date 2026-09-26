<?php
declare(strict_types=1);

namespace App\Models;

use App\Core\Database;

class Transaction
{
    public static function create(int $userId, int $categoryId, string $description, float $amount, string $type, string $date): int
    {
        $db = Database::getConnection();
        $stmt = $db->prepare("
            INSERT INTO transactions (user_id, category_id, description, amount, type, date)
            VALUES (:user_id, :category_id, :description, :amount, :type, :date)
        ");

        $stmt->execute([
            'user_id'     => $userId,
            'category_id' => $categoryId,
            'description' => trim($description),
            'amount'      => abs($amount),
            'type'        => $type,
            'date'        => $date
        ]);

        return (int) $db->lastInsertId();
    }

    public static function getByUser(int $userId, int $limit = 50, ?string $type = null): array
    {
        $db = Database::getConnection();
        $query = "
            SELECT t.*, c.name as category_name, c.color as category_color, c.icon as category_icon
            FROM transactions t
            JOIN categories c ON t.category_id = c.id
            WHERE t.user_id = :user_id
        ";

        $params = ['user_id' => $userId];

        if ($type && in_array($type, ['income', 'expense'], true)) {
            $query .= " AND t.type = :type";
            $params['type'] = $type;
        }

        $query .= " ORDER BY t.date DESC, t.id DESC LIMIT :limit";

        $stmt = $db->prepare($query);
        foreach ($params as $k => $v) {
            $stmt->bindValue($k, $v);
        }
        $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public static function getSummary(int $userId): array
    {
        $db = Database::getConnection();
        $stmt = $db->prepare("
            SELECT 
                COALESCE(SUM(CASE WHEN type = 'income' THEN amount ELSE 0 END), 0) as total_income,
                COALESCE(SUM(CASE WHEN type = 'expense' THEN amount ELSE 0 END), 0) as total_expense
            FROM transactions
            WHERE user_id = :user_id
        ");
        $stmt->execute(['user_id' => $userId]);
        $result = $stmt->fetch();

        $income = (float) $result['total_income'];
        $expense = (float) $result['total_expense'];
        $balance = $income - $expense;

        return [
            'income'  => $income,
            'expense' => $expense,
            'balance' => $balance
        ];
    }

    public static function getExpensesByCategory(int $userId): array
    {
        $db = Database::getConnection();
        $stmt = $db->prepare("
            SELECT c.name, c.color, SUM(t.amount) as total
            FROM transactions t
            JOIN categories c ON t.category_id = c.id
            WHERE t.user_id = :user_id AND t.type = 'expense'
            GROUP BY c.id, c.name, c.color
            ORDER BY total DESC
        ");
        $stmt->execute(['user_id' => $userId]);
        return $stmt->fetchAll();
    }

    public static function delete(int $id, int $userId): bool
    {
        $db = Database::getConnection();
        $stmt = $db->prepare("DELETE FROM transactions WHERE id = :id AND user_id = :user_id");
        return $stmt->execute(['id' => $id, 'user_id' => $userId]);
    }
}
