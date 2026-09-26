<?php
declare(strict_types=1);

namespace App\Core;

class Router
{
    private array $routes = [];

    public function get(string $path, string|callable $handler): void
    {
        $this->addRoute('GET', $path, $handler);
    }

    public function post(string $path, string|callable $handler): void
    {
        $this->addRoute('POST', $path, $handler);
    }

    private function addRoute(string $method, string $path, string|callable $handler): void
    {
        $this->routes[] = [
            'method'  => $method,
            'path'    => $path,
            'handler' => $handler
        ];
    }

    public function dispatch(string $uri, string $method): void
    {
        $parsedUri = parse_url($uri, PHP_URL_PATH) ?? '/';

        foreach ($this->routes as $route) {
            if ($route['method'] === $method && $route['path'] === $parsedUri) {
                $handler = $route['handler'];

                if (is_callable($handler)) {
                    call_user_func($handler);
                    return;
                }

                if (is_string($handler) && str_contains($handler, '@')) {
                    [$controllerName, $action] = explode('@', $handler);
                    $fullClass = "App\\Controllers\\{$controllerName}";

                    if (!class_exists($fullClass)) {
                        http_response_code(500);
                        die("Controller {$fullClass} not found.");
                    }

                    $controller = new $fullClass();

                    if (!method_exists($controller, $action)) {
                        http_response_code(500);
                        die("Method {$action} not found in {$fullClass}.");
                    }

                    $controller->$action();
                    return;
                }
            }
        }

        // 404 Not Found Page
        http_response_code(404);
        echo "<!DOCTYPE html><html lang='en'><head><meta charset='UTF-8'><title>404 - Not Found</title><style>body{background:#0b0f19;color:#f8fafc;font-family:sans-serif;display:flex;height:100vh;align-items:center;justify-content:center;flex-direction:column}a{color:#38bdf8;text-decoration:none}</style></head><body><h1>404 - Page Not Found</h1><p>The requested resource does not exist.</p><a href='/'>← Back to Homepage</a></body></html>";
    }
}
