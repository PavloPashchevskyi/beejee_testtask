<?php

/**
 * Description of DBConnection
 *
 * @author ppd
 */
class DBConnection 
{
    protected $connection;
    public function __construct() 
    {
        global $defaultOptions;
        $dsn = $defaultOptions['dbconnection']['server'].
                ':host='.$defaultOptions['dbconnection']['host'];
        if(!empty($defaultOptions['dbconnection']['port'])) {
            $dsn.=':'.$defaultOptions['dbconnection']['port'];
        }
        $dsn.=';dbname='.
                $defaultOptions['dbconnection']['dbname'];
        $username = $defaultOptions['dbconnection']['user'];
        $password = $defaultOptions['dbconnection']['password'];
        $options[PDO::ATTR_PERSISTENT] = $defaultOptions['dbconnection']['persistent'];
        try {
            $this->connection = new PDO($dsn, $username, $password, $options);
        }
        catch (PDOException $e) {
            echo $e->getCode()." ".$e->getMessage()."<br>";
        }
    }
    
    public function __destruct() {
        $this->connection = null;
    }
}
