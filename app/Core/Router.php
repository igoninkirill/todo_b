<?php declare(strict_types=1);

namespace App\Core;

class Router
{
    protected array $routes = [
        '/' => 'TaskController@index',
        '/tasks' => 'TaskController@index',
        '/tasks/create' => 'TaskController@create',
        '/tasks/edit/{id}' => 'TaskController@edit',
        '/login' => 'AuthController@login',
        '/logout' => 'AuthController@logout',
    ];

    public function __invoke(string $uri): void
    {
        $route = strtok($uri, '?');
        foreach ($this->routes as $pattern => $action) {
            $regex = $this->convertPatternToRegex($pattern);
            if (preg_match($regex, $route, $matches)) {
                [$controller, $method] = explode('@', $action);
                $controllerClass = "\\App\\Controllers\\$controller";
                $params = array_slice($matches, 1);
                (new $controllerClass)->$method(...array_values($params));
                return;
            }
        }

        echo '404 Not Found';
    }

    private function convertPatternToRegex(string $pattern): string
    {
        return '#^' . preg_replace('/\{(\w+)\}/', '([^/]+)', $pattern) . '$#';
    }
}
