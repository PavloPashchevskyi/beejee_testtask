<?php

/**
 * Description of EntityManager
 *
 * @author ppd
 */
class EntityManager 
{
    private $model;
    
    public function getModel($modelPath)
    {
        $modulePathParts = explode(':', $modelPath);
        $moduleName = strtolower($modulePathParts[0]);
        $modelName = ucfirst($modulePathParts[1]);
        $modelFile = 'application/modules/'.$moduleName.'/models/'.$modelName.'.php';
        if(file_exists($modelFile)) {
            include_once $modelFile;
        }
        else {
            throw new ModelNotFoundException($moduleName, $modelName);
        }
        if(class_exists($modelName, false)) {
            $this->model = new $modelName();
            return $this->model;
        }
        else {
            throw new ModelClassNotDefinedException($moduleName, $modelName);
        }
    }
}
