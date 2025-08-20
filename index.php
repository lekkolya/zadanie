<?php 

use Core\Router;
use Core\Render;

define("ROOT", dirname(__FILE__));

include_once ROOT . '/core/Router.php';
include_once ROOT . '/core/Render.php';

$data = json_decode(file_get_contents(ROOT . '/data/content.json'), true);
$routes = include ROOT . '/core/config/routes.php';

$router = new Router($routes, $_SERVER['REQUEST_URI'], $data);
echo (new Render) -> render(...$router->index());