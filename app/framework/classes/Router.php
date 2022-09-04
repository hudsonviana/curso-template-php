<?php

namespace app\framework\classes;

use Exception;

class Router {
    private string $path;
    private string $request;

    private function routerFound($routes) {
        if(!isset($routes[$this->request])) {
            throw new Exception("A rota {$this->path} não existe.");
        }
        
        if(!isset($routes[$this->request][$this->path])) {
            throw new Exception("A rota {$this->path} não existe.");
        }
    }

    private function controllerFound(string $controllerNamespace, string $controller, string $action) {
        if(!class_exists($controllerNamespace)) {
            throw new Exception("O controller {$controller} não existe.");
        }
        
        if(!method_exists($controllerNamespace, $action)) {
            throw new Exception("A action {$controller} não existe.");
        }
    }

    public function execute($routes) {
        $this->path = path();
        $this->request = request();

        $this->routerFound($routes);

        [$controller, $action] = explode('@', $routes[$this->request][$this->path]);

        $controllerNamespace = "app\\controllers\\{$controller}";

        $this->controllerFound($controllerNamespace, $controller, $action);

        $controllerInstance = new $controllerNamespace;
        $controllerInstance->$action();
    }
}
