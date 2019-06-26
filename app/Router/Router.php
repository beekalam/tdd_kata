<?php


namespace App\Router;


class Router
{
    private $searchPath;

    /**
     * Router constructor.
     * @param $searchPath
     */
    public function __construct($searchPath)
    {
        $this->searchPath = $searchPath;
    }

    public function getSearchPath()
    {
        return $this->searchPath;
    }

    public function controllers()
    {
        $controllers = [];
        foreach ($this->searchPathFiles() as $file)
            if ($this->isController($file))
                $controllers[] = pathinfo($file, PATHINFO_FILENAME);

        return $controllers;
    }

    private function searchPathFiles()
    {
        return GLOB($this->searchPath . DIRECTORY_SEPARATOR . '*.php');
    }

    private function isController($file)
    {
        return strpos($file, "Controller.php") !== false;
    }

    public function getRoutes()
    {
        $routes = [];
        foreach ($this->controllers() as $controller) {
            $routes = array_merge($routes, $this->getControllerRoutes($controller));
        }
        return $routes;
    }

    protected function getControllerRoutes($controller)
    {
        $routes = [];
        foreach ($this->getMethods($controller) as $method) {
            $docComment = $this->getRouteInComment($method->getDocComment());
            if ($docComment) {
                $routes[$docComment] = [
                    'controller' => "{$controller}",
                    'method'     => "{$method->getName()}",
                    'params'     => $this->extractRouteParams($docComment),
                    'parts'      => $this->getRouteParts($docComment)
                ];
            }
        }
        return $routes;
    }

    private function extractRouteParams($docComment)
    {
        $matches = [];
        if (preg_match_all("#\{\w+\}#", $docComment, $matches)) {
            $params = [];
            foreach ($matches[0] as $match) {
                $params[] = trim(trim($match, "{"), "}");
            }
            return $params;
        }
        return [];
    }

    private function getMethods($controller)
    {
        $this->includeOnceController($controller);
        $class = new \ReflectionClass("{$controller}");
        return $class->getMethods();
    }

    private function getRouteInComment($docComment)
    {
        $matches = [];
        if (preg_match("/@Route\((.*)\)/", $docComment, $matches)) {
            if (isset($matches[1])) {
                return trim($matches[1], '"');
            }
        }

        return null;
    }

    public function getRouteResult($route)
    {
        $routes = $this->getRoutes();
        if (isset($routes[$route])) {
            $controller = $this->getControllerInstance($routes[$route]['controller']);
            return $controller->{$routes[$route]['method']}();
        } else {
            $route_parts = $this->getRouteParts($route);
            $route_parts_count =  count($route_parts);
            foreach ($routes as $route) {
                if($route_parts_count == count($route['parts']) && $route_parts[0] == $route['parts'][0]){
                    $controller = $this->getControllerInstance($route['controller']);
                    return $controller->{$route['method']}();
                }
            }
        }

        return "";
    }

    private function getControllerInstance($controller)
    {
        $this->includeOnceController($controller);
        return (new \ReflectionClass($controller))->newInstance();
    }


    private function includeOnceController($controller)
    {
        $controllerPath = $this->searchPath . DIRECTORY_SEPARATOR . $controller . ".php";
        include_once($controllerPath);
    }

    private function getRouteParts($docComment)
    {
        return explode("/", ltrim($docComment, "/"));
    }


}