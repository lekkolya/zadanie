<?php 

namespace Core;

class Render 
{
    public function render($view, $data = []) {
        
        return $this->renderLayout($view, $data);
    }

    private function renderLayout($view, $data) {
        $layoutPath = ROOT . DIRECTORY_SEPARATOR . "layouts" . DIRECTORY_SEPARATOR . "main.php";

        if(file_exists($layoutPath)) {
            ob_start();
            $content = $this->renderView($view, $data);
            include $layoutPath;
            return ob_get_clean();
        }
    }    
 
    private function renderView($view, $data) {
        $viewPath = ROOT . DIRECTORY_SEPARATOR . "views" . DIRECTORY_SEPARATOR . $view . ".php";
        if(file_exists($viewPath)) {
            ob_start();
            extract($data);
            $url = $this->getArrUri();
            include $viewPath;
            return ob_get_clean();
        }
    }

    private function getArrUri() {
        $url = explode('/', trim($_SERVER['REQUEST_URI'], '/'));

        if(empty($url[0])) {
            $url = [0 => "contacts", 1 => 15];
        }
        return $url;
    }

}