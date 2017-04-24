<?php

/**
 * Description of ModelNotFoundException
 *
 * @author ppd
 */
class ModelNotFoundException extends Exception
{
    public function __construct($moduleName = "", $modelName = "", \Exception $previous = null) 
    {
        $message = 'Model '.$modelName.' was not found in module '.$moduleName.' of the application.';
        $code = 14;
        parent::__construct($message, $code, $previous);
    }
}
