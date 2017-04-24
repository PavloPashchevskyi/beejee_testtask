<?php

/**
 * Description of ActionNotFoundException
 *
 * @author ppd
 */
class ActionNotFoundException extends Exception
{
    public function __construct($moduleName = "", $controllerName = "", $actionName = "", \Exception $previous = null) {
        $message = 'Action '.$actionName.' of controller '.$controllerName.' in module '.$moduleName.' was not found in the application!';
        $code = 13;
        parent::__construct($message, $code, $previous);
    }
}
