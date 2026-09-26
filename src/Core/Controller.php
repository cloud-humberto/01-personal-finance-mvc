<?php
declare(strict_types=1);

namespace App\Core;

abstract class Controller
{
    /**
     * Render a PHP view with provided data
     */
    protected function render(string $view, array $data = []): void
    {
        extract($data);
        $csrfToken = Session::generateCsrfToken();
        $currentUser = Session::get('user');

        $viewPath = __DIR__ . '/../Views/' . $view . '.php';

        if (!file_exists($viewPath)) {
            http_response_code(500);
            die("View '{$view}' not found at {$viewPath}");
        }

        require_once $viewPath;
    }

    /**
     * Return structured JSON response
     */
    protected function json(array $data, int $statusCode = 200): void
    {
        http_response_code($statusCode);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        exit;
    }

    /**
     * Redirect to another internal route
     */
    protected function redirect(string $url): void
    {
        header("Location: {$url}");
        exit;
    }

    /**
     * Guard to enforce user authentication
     */
    protected function requireAuth(): void
    {
        if (!Session::has('user')) {
            Session::setFlash('error', 'Please log in to access this page.');
            $this->redirect('/login');
        }
    }
}
