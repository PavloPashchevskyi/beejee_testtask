<?php

/**
 * Description of ModuleNotFoundException
 *
 * @author ppd
 */
class ModuleNotFoundException extends Exception
{
    public function __construct($moduleName = "", \Exception $previous = null) {
        $message = 'Module '.$moduleName.' not found in the application!';
        $code = 11;
        parent::__construct($message, $code, $previous);
    }
}
