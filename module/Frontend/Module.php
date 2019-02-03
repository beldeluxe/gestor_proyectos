<?php
namespace Frontend;

use Locale;
use Zend\Mvc\MvcEvent;

use Application\AccessModule;
use Zend\Session\Container;

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
        $this->checkSession($event, __NAMESPACE__);
        $application = $event->getApplication();
        $translator = $application->getServiceManager()->get('translator');

        if (isset($_GET['lang'])){
            $lang = $_GET['lang'];
            if ($lang == 'ES') $lang = 'es_ES';
            if ($lang == 'EN') $lang = 'en_US';
            if ($lang == 'PT') $lang = 'pt_PT';

            $session = new Container('language');

            $availableLanguage = array('es_ES', 'en_US', 'pt_PT');

            if (in_array($lang, $availableLanguage)) {
                $languagePart = explode('_', $lang);
                $caption = $languagePart[0];
                $session->language = $lang;
                $session->caption = $caption;

                $translator->setLocale($lang)->setFallbackLocale($lang);
            }
        }else{
            if (isset($_SESSION['language']['caption'])){
                $translator->setLocale($_SESSION['language']['caption'])->setFallbackLocale($_SESSION['language']['caption']);
            }
        }

    }

}
