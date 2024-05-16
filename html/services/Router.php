<?php

/**
 * Basic router class for a simple project.
 */
class Router {

    private $routes = array();
    private const ERROR_404_PATH = "_404_error_path";

    /**
     * add a new route
     * @param string $path the path name (for instance "/" for the default route)
     * @param string $viewPath the view path name (for instance "views/home.php")
     */
    public function add($path, $viewPath) {
        $this->routes[$path] = dirname(__DIR__)."/".$viewPath;
    }

    /**
     * set the 404 error view
     * @param string $viewPath a view path
     */
    public function set404View($viewPath) {
        $this->add(self::ERROR_404_PATH, $viewPath);
    }

    /**
     * Handle a view
     * @param string $path a route path (for instance "/")
     */
    private function handle($path) {
        if(!isset($path) || !array_key_exists($path, $this->routes)) {
            // manage if the 404 page isn't defined (to avoid loop)
            if($path === self::ERROR_404_PATH) {
                header("HTTP/1.0 404 Not Found");
                exit;
            }
            $this->handle(self::ERROR_404_PATH);
            return;
        }
        try {
            $viewPath = $this->routes[$path];
            require_once($viewPath);
        } catch(Exception $e) {
            print_r("Failed to load view '".$viewPath."': " . $e->getMessage());
        }
    }

    /**
     * start the router system
     */
    public function start() {
        $requestPath = $_SERVER["REQUEST_URI"];
        // remove expression "?" from the URL
        $requestPath = preg_replace("/\?.*/mi", "", $requestPath);
        if(!isset($requestPath))
            $this->handle(self::ERROR_404_PATH);
        $this->handle($requestPath);
    }

}