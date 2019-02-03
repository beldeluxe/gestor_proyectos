<?php
namespace Application;

use Model\BusinessModel\Usuario;
use Model\CommonContentsModel\Admin;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;

use Zend\Authentication\AuthenticationService;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Db\ResultSet\ResultSet;

use Zend\Session\SaveHandler\DbTableGateway;
use Zend\Session\SaveHandler\DbTableGatewayOptions;
use Zend\Session\SessionManager;
use Zend\Session\Config\SessionConfig;
use Zend\Session\Container;

use Zend\Config\Config;


class AccessModule extends AbstractActionController
{
 
    static function BACKEND_ACCESS_CONTROLLERS ()
    {
        return array(
            "Backend"
        );        
    }

    public function registerAuthHandler($namespace, MvcEvent $event)
    {
        $eventManager       = $event->getApplication()->getEventManager();
        $sharedEventManager = $eventManager->getSharedManager(); // The shared event manager
        $sharedEventManager->attach($namespace,MvcEvent::EVENT_DISPATCH,array($this,'checkControllerAuth'),100);
    }

    public function checkControllerAuth(MvcEvent $event)
    {
        $controller     = $event->getTarget();
        $route          = $controller->getEvent()->getRouteMatch();
        $controllerName = $route->getParam('controller');
        $module         = explode("\\", $controllerName)[0];
        $action         = $route->getParam('action');
        $adminZone      = false;
        $sm             = $controller->getServiceLocator();
        $can = false;

        if (in_array($module, AccessModule::BACKEND_ACCESS_CONTROLLERS()) ) {
              $adminZone = true;  }

          if ($adminZone && $action!='login' && $action != 'logout') {

              // Verificar la sesión
                  $adminSession = new Container('admin_session');

                $can = true;
                if (!$can){
                    $this->flashMessenger()->addErrorMessage('No tiene permitido acceder a esa sección son su rol');
                    return $controller->redirect()->toUrl('/admin/projects');
                }
            }

        }

  //  }

    public function checkSession(MvcEvent $event,$namespace) 
    {
        $this->initSessionManager($event);

        $eventManager        = $event->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);

        $this->registerAuthHandler($namespace,$event);
    }

    protected function initSessionManager(MvcEvent $event)
    {
        $config = new Config(include "config/autoload/local.php");

        $sessionConfig = new SessionConfig();
        $sessionConfig->setOptions($config->sessionConfig);

        $sm  = $event->getApplication()->getServiceManager();
        $adapter = $sm->get('Zend\Db\Adapter\Adapter');        
        $tableGateway = new TableGateway('sessions', $adapter);
        $saveHandler  = new DbTableGateway($tableGateway, new DbTableGatewayOptions());

        $sessionManager = new SessionManager($sessionConfig);
        $sessionManager->setSaveHandler($saveHandler);
        $sessionManager->start();

        Container::setDefaultManager($sessionManager);
    }    

}
