<?php

namespace Core;

class Router {

    private string $uri = '';
    private array $routes = [];
    private array $data = [];

    public function __construct(array $routes, string $uri, array $data) {
        $this->routes = $routes;
        $this->uri = $uri;
        $this->data = $data;
    }

    public function index() {
        return $this->getTrack();
    }

    private function getTrack() {
        foreach ($this->routes as $route) {
            $pattern = $this->createPattern($route['path']);
            if(preg_match($pattern, $this->uri, $params)) {
                $params = $this->clearParams($params);
                return ["view" => $route['view'], "data" => $this->getCurrentSubtopic($params)];
            }
        }
    }

    private function getCurrentSubtopic($params) {
        $arr = [];
        $url = $this->getArrUri();

        if(isset($this->data[$url[0]])) {
            if(isset($this->data[$url[0]]['list'][$url[1]])) {
                $arr = $this->data;
            }
        }
        
        return $arr;
    }

    private function getArrUri() {
        $url = explode('/', trim($this->uri, '/'));

        if(empty($url[0]) || ($url[0] == 'crud')) {
            $url = [0 => "contacts", 1 => 15,];
        }
        return $url;
    }

    private function createPattern(string $path): string {
        return "#^" . preg_replace('#/:([^/]+)#', '/(?<$1>[^/]+)', $path) .  "/?$#";
    }

    private function clearParams($params){
        return array_filter($params, function($key) {
            return !is_int($key);
        }, ARRAY_FILTER_USE_KEY);
    }

}