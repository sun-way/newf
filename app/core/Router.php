<?php
class Router
{
    public static $base_route = 'index';
    private static $dirController = 'app/controllers/';
    private static $urls = [];
    private static $curRouteName;
    private static $curRouteAction;
    private static $curRouteUrl = '';

    public static function get($url, $controllerAndAction, $routeName = '', $params = [])
    {
        self::add('GET', $url, $controllerAndAction, $routeName, $params);
    }

    public static function add($method, $url, $controllerAndAction, $routeName, $params)
    {
        list($controller, $action) = explode('@', $controllerAndAction);
        self::$urls[$method][$url] = [
            'controller' => $controller,
            'action' => $action,
            'routeName' => $routeName,
            'params' => $params
        ];
    }
  
    public static function resource($resource, $controller)
    {
        //route('contacts.edit', ['id' => $contact->id])
        self::add('GET', "/$resource", "$controller@index", "$resource.index", []);
        self::add('GET', "/$resource/create", "$controller@create", "$resource.create", []);
        self::add('POST', "/$resource", "$controller@store", "$resource.store", []);
        self::add('GET', "/$resource/(\d+)", "$controller@show", "$resource.show", ['id' => 1]);
        self::add('GET', "/$resource/(\d+)/edit", "$controller@edit", "$resource.edit", ['id' => 1]);
        self::add('PUT', "/$resource/(\d+)", "$controller@update", "$resource.update", ['id' => 1]);
        self::add('DELETE', "/$resource/(\d+)", "$controller@destroy", "$resource.destroy", ['id' => 1]);
    }

    public static function post($url, $controllerAndAction, $routeName = '', $params = [])
    {
        self::add('POST', $url, $controllerAndAction, $routeName, $params);
    }

    public static function currentRouteName()
    {
        if (!empty(self::$curRouteName))
            return self::$curRouteName;
        else
            return '';
       // return !empty(/*self::*/$curRouteName) ? /*self::*/$curRouteName : '';
    }

    public static function currentRouteAction()
    {
        if (!empty(self::$curRouteAction))
            return self::$curRouteAction;
        else
            return '';
        //return !empty(/*self::*/$curRouteAction) ? /*self::*/$curRouteAction : '';
    }

    public static function currentRouteUrl()
    {
        if (!empty(self::$curRouteUrl))
            return self::$curRouteUrl;
        else
            return '';
        //return !empty(self::$curRouteUrl) ? self::$curRouteUrl : '';
    }

    public static function redirect($action)
    {
        if (!headers_sent()) {
            header('Location: ' . self::route($action));
        }
        die;
    }

    public static function route($routeName, $params = [])
    {
        if (isset(self::$urls)) {
            foreach (self::$urls as $urlMethod) {
                foreach ($urlMethod as $url => $urlData) {
                    if ($urlData['routeName'] === $routeName) {
                        if (count($params) > 0) {
                            foreach ($params as $key => $param) {
                                $url = str_replace('(\d+)', $param, $url);
                            }
                        }
                        return '?' . $url;
                    }
                }
            }
        }
        return '';
    }

    public static function run($currentUrl)
    {
        $routeFound = false;
        $requestMethod = Request::has('_method') ? Request::get('_method') : $_SERVER['REQUEST_METHOD'];
        if (isset(self::$urls[$requestMethod])) {
            foreach (self::$urls[$requestMethod] as $url => $urlData) {
                if (preg_match('(^' . $url . '$)', $currentUrl, $matchList)) {
                    $params = [];
                    foreach ($urlData['params'] as $param => $i) {
                        $params[$param] = $matchList[$i];
                    }
                    $controllerName = $urlData['controller'];
                    $action = $urlData['action'];
                    self::$curRouteName = $urlData['routeName'];
                    self::$curRouteAction = $urlData['action'];
                    self::$curRouteUrl = $currentUrl;
                    $controllerFile = self::$dirController . $controllerName . '.php';
                    if (is_file($controllerFile)) {
                        include $controllerFile;
                    } else {
                        throw new Exception("Файл контроллера $controllerFile не обнаружен", '404');
                    }
                    $controller = new $urlData['controller']();
                    if (method_exists($controller, $action)) {
                        $controller->$action($params);
                    } else {
                        throw new Exception("Метод $action не обнаружен в контроллере $controllerName", '404');
                    }
                    $routeFound = true;
                }
            }
        }
        if ($routeFound === false) {
            throw new Exception("Маршрут $currentUrl не обнаружен, метод: $requestMethod", '404');
        }
    }
}
