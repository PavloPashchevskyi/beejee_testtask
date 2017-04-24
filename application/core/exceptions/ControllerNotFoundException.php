<?php

/**
 * Description of ControllerNotFoundException
 *
 * @author ppd
 */
class ControllerNotFoundException extends Exception
{
    public function __construct($moduleName = "", $controllerName = "", \Exception $previous = null) {
        $message = 'Controller '.$controllerName.' was not found in module '.$moduleName.'!';
        $code = 12;
        parent::__construct($message, $code, $previous);
    }
}
