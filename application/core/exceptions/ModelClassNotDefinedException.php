<?php

/**
 * Description of ModelClassNotDefinedException
 *
 * @author ppd
 */
class ModelClassNotDefinedException extends Exception
{
    public function __construct($moduleName = "", $modelName = "", \Exception $previous = null) {
        $message = 'Model class '.$modelName.' is not defined in the module '.$moduleName.' or the name of model class is not related to file in which it is defined!';
        $code = 22;
        parent::__construct($message, $code, $previous);
    }
}
