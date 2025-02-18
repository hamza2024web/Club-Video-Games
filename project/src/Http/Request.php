<?php 
namespace Src\Http;

class Request {
    public function Methode() {
        return strtolower($_SERVER['REQUEST_METHOD']);
    }

    public function path (){
        $path = $_SERVER["REQUEST_URI"];
         
        $path = str_replace( '/public/', '',$path);
          
        $path = trim($path, '/');
       
  
        if (str_contains($path, '?')) {
            return explode('?', $path)[0];
        }
     
        
        return $path;
       
    }
}
