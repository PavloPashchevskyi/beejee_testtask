<?php

/**
 * Description of ControllerClassNotDefinedException
 *
 * @author ppd
 */
class ControllerClassNotDefinedException extends Exception
{
    public function __construct($moduleName = "", $controllerName = "", \Exception $previous = null) {
        $message = 'Controller class '.$controllerName.'Controller is not defined in the module '.$moduleName.' or the name of controller class is not related to file in which it is defined!';
        $code = 21;
        parent::__construct($message, $code, $previous);
    }
}
