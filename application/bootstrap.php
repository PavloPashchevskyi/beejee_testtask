<?php
$defaultOptions = require_once 'config/options.php';
require_once 'core/DBConnection.php';
//router
require_once 'core/Application.php';
//exception classes
require_once 'core/exceptions/ModuleNotFoundException.php';
require_once 'core/exceptions/ControllerNotFoundException.php';
require_once 'core/exceptions/ControllerClassNotDefinedException.php';
require_once 'core/exceptions/ActionNotFoundException.php';
require_once 'core/exceptions/ModelNotFoundException.php';
require_once 'core/exceptions/ModelClassNotDefinedException.php';
require_once 'core/exceptions/ModelInstanceNotFoundException.php';
require_once 'core/exceptions/InvalidDataException.php';
//entity manager
require_once 'core/EntityManager.php';
//MVC
require_once 'core/Model.php';
require_once 'core/View.php';
require_once 'core/Controller.php';

Application::start($defaultOptions['default_route']);
?>