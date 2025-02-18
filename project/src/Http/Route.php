<?php

namespace Src\Http;

class Route 
{
    public Request $request ;
    public static array $routes = [];
    public function __construct($request)
    {
        $this->request = $request;
    }


    public static function get($route , $action){
        self::$routes['get'][$route] = $action;
    }

    public static function post($route , $action){
        self::$routes['post'][$route] = $action;
    }
   
   

    public function resolve(){
        $path = $this->request->path(); 
        $method = $this->request->Methode();
        


        if(isset(self::$routes[$method][$path])){

        $action = self::$routes[$method][$path];
    } else {
        // Vérifie si une route dynamique correspond
        foreach (self::$routes[$method] as $route => $action) {
            $pattern = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '([a-zA-Z0-9_-]+)', $route);
            
            if (preg_match("#^$pattern$#", $path, $matches)) {
                array_shift($matches); 
                $params = $matches;  
                break;              
            }
        }
        
    }

        if (is_string($action)){

            [$controllerAction , $methodeAction] = explode('@',$action);

            $controllerAction = "App\\Controllers\\$controllerAction";
          
            if(!class_exists($controllerAction)){
                echo "class not exist";
                exit;
            }

    if (is_callable($action)) {
        call_user_func_array($action, $params ?? []);
        return;
    }

    if (is_string($action)) {
        [$controller, $method] = explode('@', $action);
        $controller = "App\\Controllers\\$controller";

        if (!class_exists($controller)) {
            echo "Class $controller does not exist";
            exit;
        }

        $object = new $controller();
        if (!method_exists($object, $method)) {
            echo "Method $method does not exist in $controller";
            exit;
        }
        
        return call_user_func_array([$object, $method], $params ?? []);
    }else{
        echo'error 404';
        
    }

}
    }
}


