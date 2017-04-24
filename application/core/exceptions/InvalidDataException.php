<?php

/**
 * Description of InvalidDataException
 *
 * @author ppd
 */
class InvalidDataException extends Exception
{
    public function __construct($value, $field, \Throwable $previous = null)
    {
        $message = $value.' is invalid value of '.$field.'. Please, input valid value of '.$field.'.';
        $code = 41;
        parent::__construct($message, $code, $previous);
    }
}
