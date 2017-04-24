<?php

/**
 * Description of ModelInstanceNotFoundException
 *
 * @author ppd
 */
class ModelInstanceNotFoundException extends Exception
{
    public function __construct($moduleName = "", $modelName = "", $id = 0, \Exception $previous = null)
    {
        $message = 'Model instance #'.$id.' has not been found in model '.$modelName.' of module '.$moduleName;
        $code = 31;
        parent::__construct($message, $code, $previous);
    }
}
