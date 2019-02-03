<?php
namespace Backend;

use Locale;
use Zend\Mvc\MvcEvent;

use Application\AccessModule;

class Module extends AccessModule
{
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    public function onBootstrap(MvcEvent $event) 
    {
        // Load translator
        $translator = $event->getApplication()->getServiceManager()->get('translator');
        $translator->setFallbackLocale('es_ES');

        $this->checkSession($event,__NAMESPACE__);
    }

}
