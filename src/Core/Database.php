<?php
declare(strict_types=1);

namespace App\Core;

use PDO;
use PDOException;

class Database
{
    private static ?PDO $instance = null;

    public static function getConnection(): PDO
    {
        if (self::$instance === null) {
            $dbPath = __DIR__ . '/../../database/finances.sqlite';
            $isNewDb = !file_exists($dbPath);

            try {
                self::$instance = new PDO('sqlite:' . $dbPath);
                self::$instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                self::$instance->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
                self::$instance->exec('PRAGMA foreign_keys = ON;');

                if ($isNewDb || filesize($dbPath) === 0) {
                    self::initializeSchema(self::$instance);
                }
            } catch (PDOException $e) {
                die("Database connection failed: " . htmlspecialchars($e->getMessage()));
            }
        }

        return self::$instance;
    }

    private static function initializeSchema(PDO $pdo): void
    {
        $sql = "
            CREATE TABLE IF NOT EXISTS users (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                name TEXT NOT NULL,
                email TEXT NOT NULL UNIQUE,
                password_hash TEXT NOT NULL,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP
            );

            CREATE TABLE IF NOT EXISTS categories (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                name TEXT NOT NULL,
                type TEXT NOT NULL CHECK(type IN ('income', 'expense')),
                icon TEXT DEFAULT 'tag',
                color TEXT DEFAULT '#6366f1'
            );

            CREATE TABLE IF NOT EXISTS transactions (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                user_id INTEGER NOT NULL,
                category_id INTEGER NOT NULL,
                description TEXT NOT NULL,
                amount REAL NOT NULL,
                type TEXT NOT NULL CHECK(type IN ('income', 'expense')),
                date DATE NOT NULL,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
                FOREIGN KEY (category_id) REFERENCES categories(id)
            );

            -- Default Categories
            INSERT INTO categories (name, type, icon, color) VALUES
            ('Salary', 'income', 'briefcase', '#10b981'),
            ('Investments', 'income', 'trending-up', '#06b6d4'),
            ('Freelance', 'income', 'code', '#8b5cf6'),
            ('Food & Dining', 'expense', 'coffee', '#ef4444'),
            ('Housing & Rent', 'expense', 'home', '#f97316'),
            ('Transportation', 'expense', 'truck', '#f59e0b'),
            ('Entertainment', 'expense', 'smile', '#ec4899'),
            ('Education', 'expense', 'book', '#3b82f6');
        ";

        $pdo->exec($sql);
    }
}
