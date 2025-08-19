<?php 

namespace Controllers;

class TopicController 
{

    private array $data = [];
    private array $routes = [];

    private string $uri; 

    public function __construct(array $routes, array $data, string $uri) {
        $this->routes = $routes;
        $this->data   = $data;
        $this->uri    = $uri;
    }

    public function index() {
        $track = $this->getTrack();
        echo $track;
    }

    private function getTrack() {
        foreach ($this->routes as $route) {
            
            $pattern = $this->createPattern($route['path']);

            if(preg_match($pattern, $this->uri, $params)) {
                $params = $this->clearParams($params);
                return $this->renderLayout($route['view'], $this->findingPath($params));
            }
        }
        return $this->renderLayout('error', []);
    }


    public function findingPath($params) {
        foreach($this->data as $topic) {
            if(isset($params['topic'])) {
                if($topic['url'] === '/'.$params['topic']) {
                    foreach($topic['subtopics'] as $subtopic) {
                        if($subtopic['url'] === '/'.$params['topic'].'/'.$params['subtopic']) {
                            return ['topictitle' => $topic['title'], 'subtopicitle' => $subtopic['title'], 'subtopiccontent' => $subtopic['content'], 'params' => $params];
                        }
                    }
                }
            }else {
                return ['topictitle' => 'Подтема 1.1', 'subtopicitle' => 'Подтема 1.1.1', 'subtopiccontent' => 'Содержимое Подтемы 1.1.1', 'params' => ['topic' => 'topic-1', 'subtopic' => 'subtopic-1-1']];
            }
        }
    }
 
    private function renderLayout($view, $content) {
       $layoutPath = ROOT . DIRECTORY_SEPARATOR . "layouts" . DIRECTORY_SEPARATOR . "main.php";

       if(file_exists($layoutPath)) {
            ob_start();
            $content = $this->renderView($view, $content, $this->data);
            include $layoutPath;
            return ob_get_clean();
       }
    }

    private function renderView($view, $content, $data) {
        $viewPath = ROOT . DIRECTORY_SEPARATOR . "views" . DIRECTORY_SEPARATOR . $view . ".php";

        if(file_exists($viewPath)) {
            ob_start();
            extract($content);
            extract($data);
            include $viewPath;
            return ob_get_clean();
        }
    }

    private function createPattern($path) {
        return '#^' . preg_replace('#/:([^/]+)#', '/(?<$1>[^/]+)', $path) . '/?$#';
    }

    private function clearParams($params) {
        $result = [];

        foreach ($params as $key => $value) {
            if(!is_int($key)) {
                $result[$key] = $value;
            }
        }

        return $result;
    }

}