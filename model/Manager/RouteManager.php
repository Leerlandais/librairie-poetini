<?php

namespace model\Manager;

use model\MyPDO;
use Twig\Environment;

class RouteManager
{
    private array $routes = [];
    private Environment $twig;

    private MyPDO $db;

    public function __construct(Environment $twig, MyPDO $db)
    {
        $this->twig = $twig;

        $this->db = $db;
    }

    public function registerRoute(string $routeName, string $controllerClass, string $methodName): void
    {
        $this->routes[$routeName] = [
            'controller' => $controllerClass,
            'method' => $methodName
        ];
    }

    public function handleRequest($route): void
    {
        if (!isset($this->routes[$route])) {
            $route = '404';
        }

        $controllerClass = $this->routes[$route]['controller'];
        $method = $this->routes[$route]['method'];

        $controller = new $controllerClass($this->twig, $this->db);

        $controller->$method();
    }
}
