<?php

/**
 * Description of Controller
 *
 * @author ppd
 */
class Controller extends Twig_Environment
{
    
    private $entityManager;
    protected $request;
    protected $get;
    protected $post;
    protected $server;

    public function __construct()
    {
        session_start();
        
        $moduleName = Application::getModuleName();
        $loader = new Twig_Loader_Filesystem('application/modules/'.$moduleName.'/views');
        parent::__construct($loader, array(
            'cache' => 'var/cache',
        ));
        $this->entityManager = new EntityManager();
        $this->request = $_REQUEST;
        $this->get = $_GET;
        $this->post = $_POST;
        $this->server = $_SERVER;
    }
    
    protected function getEntityManager()
    {
        return $this->entityManager;
    }
    
    protected function redirect($route)
    {
        header('Location: '.$route);
    }
}
