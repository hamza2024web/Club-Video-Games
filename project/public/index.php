<?php

use Src\Http\Request;
use Src\Http\Route;
use Config\Database;

require_once '../routes/web.php';

$route = new Route(new Request);
$route->resolve(); 

