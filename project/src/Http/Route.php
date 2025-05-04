<?php

namespace Src\Http;

class Route 
{
    public Request $request;
    public static array $routes = [];
    public static array $middlewares = [];

    public function __construct($request)
    {
        $this->request = $request;
    }

    /**
     * Register a GET route
     */
    public static function get($route, $action, $middlewares = [])
    {
        self::$routes['get'][self::normalize($route)] = [
            'action' => $action,
            'middlewares' => $middlewares
        ];
    }

    /**
     * Register a POST route
     */
    public static function post($route, $action, $middlewares = [])
    {
        self::$routes['post'][self::normalize($route)] = [
            'action' => $action,
            'middlewares' => $middlewares
        ];
    }

    /**
     * Normalize route path (remove multiple slashes)
     */
    private static function normalize($route)
    {
        return '/' . trim($route, '/');
    }

    /**
     * Resolve the requested route
     */
    public function resolve()
    {
        $path = self::normalize($this->request->path());
        $method = strtolower($this->request->Methode());

        $action = null;
        $params = [];

        // Exact route match
        if (isset(self::$routes[$method][$path])) {
            $routeInfo = self::$routes[$method][$path];
            $action = $routeInfo['action'];
            $middlewares = $routeInfo['middlewares'];
        } else {
            // Check for dynamic route matching
            foreach (self::$routes[$method] as $route => $routeInfo) {
                $pattern = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '([a-zA-Z0-9_-]+)', $route);
                
                if (preg_match("#^$pattern$#", $path, $matches)) {
                    array_shift($matches); // Remove full match
                    $action = $routeInfo['action'];
                    $middlewares = $routeInfo['middlewares'];
                    $params = $matches;
                    break;
                }
            }
        }

        if (!$action) {
            http_response_code(404);
            echo "Error 404: Route not found";
            exit;
        }

        foreach ($middlewares ?? [] as $middleware) {
            $middlewareClass = "App\\Middlewares\\$middleware";
            if (class_exists($middlewareClass)) {
                (new $middlewareClass())->handle();
            }
        }

        if (is_callable($action)) {
            return call_user_func_array($action, $params);
        }

        if (is_string($action)) {
            [$controller, $method] = explode('@', $action);
            $controller = "App\\Controllers\\$controller";

            if (!class_exists($controller)) {
                echo "Error: Controller $controller not found";
                exit;
            }

            $object = new $controller();
            if (!method_exists($object, $method)) {
                echo "Error: Method $method not found in $controller";
                exit;
            }

            return call_user_func_array([$object, $method], $params);
        }

        echo "Error 500: Invalid route definition";
    }
}
