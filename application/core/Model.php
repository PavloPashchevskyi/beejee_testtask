<?php

/**
 * Description of Model
 *
 * @author ppd
 */
class Model extends DBConnection
{
    protected $tableName;
    protected $modelName;
    
    public function __construct() {
        parent::__construct();
        global $defaultOptions;
        $tableNamePrefix = $defaultOptions['dbtable']['prefix'];
        $tableNameSuffix = $defaultOptions['dbtable']['suffix'];
        $beginpos = strpos($this->tableName, $tableNamePrefix)+strlen($tableNamePrefix);
        $endpos = strrpos($this->tableName, $tableNameSuffix);
        $length = $endpos-$beginpos;
        $this->modelName = ucfirst(substr($this->tableName, $beginpos, $length));
    }
    
    /**
     * 
     * @param array $orderBy
     * @return array
     */
    public function findAll($orderBy = [])
    {
        $orderByClause = '';
        if(!empty($orderBy)) {
            $orderByClause.='ORDER BY ';
            $i = 0;
            foreach($orderBy as $field => $order) {
                $orderByClause.=$field.' '.$order;
                if($i<count($orderBy)-1) $orderByClause.=', ';
                $i++;
            }
        }
        $sql = 'SELECT * FROM '.$this->tableName.' '.$orderByClause; 
        $query = $this->connection->query($sql);
        if(!$query) {
            exit("Unable to execute the query ".$sql."<br>");
        }
        $results = $query->fetchAll(PDO::FETCH_ASSOC);
        return $results;
    }
    
    /**
     * Returns one record from table by primary key
     *
     * @param int $id
     * @return array
     */
    public function findOne($id)
    {
        $primaryKeyName = strtolower($this->modelName).'_id';
        $sql = 'SELECT * FROM '.$this->tableName.' WHERE '.$primaryKeyName.'='.$id;
        $query = $this->connection->query($sql);
        if(!$query) {
            exit("Unable to execute the query ".$sql."<br>");
        }
        $result = $query->fetch(PDO::FETCH_ASSOC);
        return $result;
    }

    /**
     * 
     * @param array $criteria
     * @param string $operation
     * @param array $orderBy
     * @return array
     */
    public function findBy($criteria, $operation = 'AND', $orderBy = [])
    {
        if(!empty($criteria)) {
            $whereclause = 'WHERE ';
        }
        else {
            $whereclause = '';
        }
        $i = 0;
        foreach($criteria as $key => $value) {
            $whereclause.=$key.'='.$value;
            if($i<count($criteria)-1) {
                $whereclause.=' '.$operation.' ';
            }
            $i++;
        }
        if(!empty($orderBy)) {
            $orderByClause.='ORDER BY ';
        }
        else {
            $orderByClause = '';
        }
        $i = 0;
        foreach($orderBy as $field => $order) {
            $orderByClause.=$field.' '.$order;
            if($i<count($orderBy)-1) $orderByClause.=', ';
            $i++;
        }
        $sql = 'SELECT * FROM '.$this->tableName.' '.$whereclause.' '.$orderByClause;
        $query = $this->connection->query($sql);
        if(!$query) {
            exit("Unable to execute the query ".$sql."<br>");
        }
        $results = $query->fetchAll(PDO::FETCH_ASSOC);
        return $results;
    }
    
    /**
     * 
     * @param array $what
     * @return boolean
     */
    public function insert($what)
    {
        $whatclause = '';
        $i = 0;
        foreach($what as $key => $value) {
            $whatclause.=$key.'='.$value;
            if($i<count($what)-1) {
                $whatclause.=', ';
            }
            $i++;
        }
        $sql = 'INSERT INTO '.$this->tableName.' SET '.$whatclause;
        $query = $this->connection->query($sql);
        if(!$query) {
            echo "Unable to execute the query ".$sql."<br>";
        }
        return $query;
    }
    
    /**
     * 
     * @param array $what
     * @param array $criteria
     * @param string $operation
     * @return boolean
     */
    public function update($what, $criteria, $operation = 'AND')
    {
        $whatclause = '';
        $i = 0;
        foreach($what as $key => $value) {
            $whatclause.=$key.'='.$value;
            if($i<count($what)-1) {
                $whatclause.=', ';
            }
            $i++;
        }
        $whereclause = '';
        $i = 0;
        foreach($criteria as $key => $value) {
            $whereclause.=$key.'='.$value;
            if($i<count($criteria)-1) {
                $whereclause.=' '.$operation.' ';
            }
            $i++;
        }
        $sql = 'UPDATE '.$this->tableName.' SET '.$whatclause.' WHERE '.$whereclause;
        $query = $this->connection->query($sql);
        if(!$query) {
            echo "Unable to execute the query ".$sql."<br>";
        }
        return $query;
    }
    
    /**
     * 
     * @param array $criteria
     * @param string $operation
     * @return boolean
     */
    public function delete($criteria, $operation = 'AND')
    {
        if(!empty($criteria)) {
            $whereclause = 'WHERE ';
        }
        else {
            $whereclause = '';
        }
        $i = 0;
        foreach($criteria as $key => $value) {
            $whereclause.=$key.'='.$value;
            if($i<count($criteria)-1) {
                $whereclause.=' '.$operation.' ';
            }
            $i++;
        }
        $sql = 'DELETE FROM '.$this->tableName.' '.$whereclause;
        $query = $this->connection->query($sql);
        if(!$query) {
            echo "Unable to execute the query ".$sql."<br>";
        }
        return $query;
    }
    
    /**
     * Returns next record id
     * 
     * @return int
     */
    public function calculateNextID()
    {
        $fieldName = strtolower($this->modelName).'_id';
        $sql = 'SELECT MAX('.$fieldName.') AS maxid FROM '.$this->tableName;
        $query = $this->connection->query($sql);
        if(!$query) {
            exit("Unable to execute the query ".$sql."<br>");
        }
        $results = $query->fetchAll(PDO::FETCH_ASSOC);
        $nextid = $results[0]['maxid']+1;
        return $nextid;
    }
}
