<?php
class Application
{
    private static $moduleName;
    private static $controllerName;
    private static $actionName;
    private static $actionArguments;

    /**
     * Router of MVC application
     * Includes needed files with model and controller classes,
     * creates instance of controller and calls controller action
     * 
     * @param array $defaultRouteOptions
     */
    public static function start($defaultRouteOptions = null)
    {
        $siteFolder = ($_SERVER['HTTP_HOST'] == 'localhost') ? 1 : 0;
        $routes = explode('/', $_SERVER['REQUEST_URI']);
        $namesValidation = [];
        foreach($routes as $i => $name) {
            $namesValidation[$siteFolder + $i] = preg_match('/$[a-zA-Z_]+.*^/', $name);
        }
        if(!empty($routes[$siteFolder + 1]) /*&& $namesValidation[$siteFolder + 1]*/) {
            if(is_dir('application/modules/'.$routes[$siteFolder + 1])) {
                $moduleName = $routes[$siteFolder + 1];
            }
            else {
                $moduleName = $defaultRouteOptions['module'];
                $controllerName = $routes[$siteFolder + 1];
            }
        }
        else {
            if(is_dir('application/modules/'.$defaultRouteOptions['module'])) {
                $moduleName = $defaultRouteOptions['module'];
                $controllerName = $defaultRouteOptions['controller'];
            }
            else {
                throw new ModuleNotFoundException($moduleName);
            }
        }
        if(!empty($routes[$siteFolder + 2])) {
            if(is_dir('application/modules/'.$routes[$siteFolder + 1])) {
                $controllerName = $routes[$siteFolder + 2];
            }
            else {
                $controllerName = $routes[$siteFolder + 1];
                $actionName = $routes[$siteFolder + 2];
            }
        }
        else {
            $controllerName = $defaultRouteOptions['controller'];
        }
        if(!empty($routes[$siteFolder + 3])) {
            if(is_dir('application/modules/'.$routes[$siteFolder + 1])) {
                $actionName = $routes[$siteFolder + 3];
            }
            else {
                $actionName = $routes[$siteFolder + 2];
                $recordId = $routes[$siteFolder + 3];
            }
        }
        else {
            if(is_dir('application/modules/'.$routes[$siteFolder + 1])) {
                $actionName = $defaultRouteOptions['action'];
            }
            else {
                $controllerName = !empty($routes[$siteFolder + 2]) ? $routes[$siteFolder + 2] : $defaultRouteOptions['controller'];
                $actionName = $defaultRouteOptions['action'];
            }
        }
        if(!empty($routes[$siteFolder + 4])) {
            $actionArguments = array_slice($routes, $siteFolder + 4);
        }
        self::$moduleName = $moduleName;
        self::$controllerName = $controllerName;
        self::$actionName = $actionName;
        self::$actionArguments = (!empty($actionArguments)) ? $actionArguments : [];
        $actionMethodName = $actionName.'Action';
        $moduleDir = 'application/modules/'.$moduleName;
        $modelControllerFirstNamePart = ucfirst($controllerName);
        $modelFile = $moduleDir.'/models/'.$modelControllerFirstNamePart.'.php';
        if(file_exists($modelFile)) {
            include_once $modelFile;
        }
        $controllerClassName = $modelControllerFirstNamePart.'Controller';
        $controllerFile = $moduleDir.'/controllers/'.$controllerClassName.'.php';
        if(file_exists($controllerFile)) {
            include_once $controllerFile;
        }
        else {
            throw new ControllerNotFoundException($moduleName, $controllerName);
        }
        if(class_exists($controllerClassName, false)) {
            $controllerObject = new $controllerClassName();
        }
        else {
            throw new ControllerClassNotDefinedException($moduleName, $controllerName);
        }
        if(method_exists($controllerObject, $actionMethodName)) {
            call_user_func_array([$controllerObject, $actionMethodName], self::$actionArguments);
        }
        else {
            throw new ActionNotFoundException($moduleName, $controllerName, $actionName);
        }
    }

    public static function getModuleName()
    {
        return self::$moduleName;
    }
    
    public static function getControllerName()
    {
        return self::$controllerName;
    }
    
    public static function getActionName()
    {
        return self::$actionName;
    }
}
?>