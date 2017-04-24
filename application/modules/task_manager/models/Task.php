<?php

/**
 * Description of Task
 *
 * @author ppd
 */
class Task extends Model 
{
    protected $tableName = 'tTasks';
    
    public function getAllTasks()
    {
        $sql = 'SELECT '.
                $this->tableName.'.task_id, tUsers.Login, tUsers.Email, '.
                $this->tableName.'.TaskText, '.$this->tableName.'.TaskImage, '.
                $this->tableName.'.Done '.
                'FROM '.$this->tableName.' '.
                'JOIN tUsers ON ('.$this->tableName.'.UserID = tUsers.user_id)';
        $query = $this->connection->query($sql);
        $results = $query->fetchAll(PDO::FETCH_ASSOC);
        
    }
    
    public function getUserTasks($user_id)
    {
        $sql = 'SELECT '.
                $this->tableName.'.task_id, tUsers.Login, tUsers.Email, '.
                $this->tableName.'.TaskText, '.$this->tableName.'.TaskImage, '.
                $this->tableName.'.Done '.
                'FROM '.$this->tableName.' '.
                'JOIN tUsers ON ('.$this->tableName.'.UserID = tUsers.user_id) '.
                'WHERE '.$this->tableName.'.UserID =:uId;';
        $query = $this->connection->prepare($sql);
        $query->bindValue(':uId', $user_id, PDO::PARAM_INT);
        $query->execute();
        $results = $query->fetchAll(PDO::FETCH_ASSOC);
        return $results;
    }
}
