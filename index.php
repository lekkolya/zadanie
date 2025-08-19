<?php 

    use Controllers\TopicController;

    define("ROOT", dirname(__FILE__));
    
    include_once ROOT . '/controllers/TopicController.php';

    $data = json_decode(file_get_contents(ROOT . '/data/content.json'), true);

    $routes = include ROOT . '/core/config/routes.php';

    $topicController = new TopicController($routes, $data, $_SERVER['REQUEST_URI']);
    $topicController->index();
?>