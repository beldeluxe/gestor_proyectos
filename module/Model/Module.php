<?php

namespace Model;

use Model\BusinessModel\AddressType;
use Model\BusinessModel\BusinessModel;
use Model\BusinessModel\ConfigText;
use Model\BusinessModel\Documentation;
use Model\BusinessModel\Domiciliation;
use Model\BusinessModel\DonationNew;
use Model\BusinessModel\Mensaje;
use Model\BusinessModel\Noticia;
use Model\BusinessModel\Partner;
use Model\BusinessModel\PartnerProjectRel;
use Model\BusinessModel\ProjectLabels;
use Model\BusinessModel\Province;
use Model\BusinessModel\Receipt;
use Model\BusinessModel\Project;

use Model\BusinessModel\UserDonation;
use Model\BusinessModel\UserRobinson;
use Model\BusinessModel\Usuario;
use Model\CommonContentsModel\CommonContentsModel;
use Model\CommonContentsModel\Country;
use Model\CommonContentsModel\Language;
use Model\CommonContentsModel\MenuEntry;
use Model\CommonContentsModel\MenuEntryLabel;
use Model\CommonContentsModel\MediaContainer;
use Model\CommonContentsModel\MediaElementContainer;
use Model\CommonContentsModel\MediaElement;
use Model\CommonContentsModel\MediaElementLabel;
use Model\CommonContentsModel\Admin;
use Model\CommonContentsModel\AdminLoginRequest;

use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;

use Model\BasicTableGateway;
use Zend\Mvc\MvcEvent;
use Zend\Validator\AbstractValidator;

class Module
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

    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'Model\ProjectTable' => function($serviceManager) {
                    $dbAdapter = $serviceManager->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Project());
                    $tableGateway = new TableGateway(Project::TABLE,$dbAdapter,null,$resultSetPrototype);
                    return ( new BasicTableGateway($tableGateway,Project::TABLE) );
                },
                'Model\AdminTable' => function($serviceManager) {
                    $dbAdapter = $serviceManager->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Admin());
                    $tableGateway = new TableGateway(Admin::TABLE,$dbAdapter,null,$resultSetPrototype);
                    return ( new BasicTableGateway($tableGateway,Admin::TABLE) );
                },
                'Model\MenuEntryLabelTable' => function($serviceManager) {
                    $dbAdapter = $serviceManager->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new MenuEntryLabel());
                    $tableGateway = new TableGateway(MenuEntryLabel::TABLE,$dbAdapter,null,$resultSetPrototype);
                    return ( new BasicTableGateway($tableGateway,MenuEntryLabel::TABLE) );
                },
                'BusinessModel' => function($serviceManager) {
                    return ( new BusinessModel($serviceManager) ); 
                },
                'CommonContentsModel' => function($serviceManager) {
                    return ( new CommonContentsModel($serviceManager) ); 
                },
                'Model\ConfigTextTable' => function($serviceManager) {
                    $dbAdapter = $serviceManager->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new ConfigText());
                    $tableGateway = new TableGateway(ConfigText::TABLE,$dbAdapter,null,$resultSetPrototype);
                    return ( new BasicTableGateway($tableGateway,ConfigText::TABLE) );
                },
                'Model\SourceTable' => function($serviceManager) {
                    $dbAdapter = $serviceManager->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Source());
                    $tableGateway = new TableGateway(Source::TABLE,$dbAdapter,null,$resultSetPrototype);
                    return ( new BasicTableGateway($tableGateway,Source::TABLE) );
                },
                'Model\UsuarioTable' => function($serviceManager) {
                    $dbAdapter = $serviceManager->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Usuario());
                    $tableGateway = new TableGateway(Usuario::TABLE,$dbAdapter,null,$resultSetPrototype);
                    return ( new BasicTableGateway($tableGateway,Usuario::TABLE) );
                },
                'Model\DocumentationTable' => function($serviceManager) {
                    $dbAdapter = $serviceManager->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Documentation());
                    $tableGateway = new TableGateway(Documentation::TABLE,$dbAdapter,null,$resultSetPrototype);
                    return ( new BasicTableGateway($tableGateway,Documentation::TABLE) );
                },
                'Model\NoticiaTable' => function($serviceManager) {
                    $dbAdapter = $serviceManager->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Noticia());
                    $tableGateway = new TableGateway(Noticia::TABLE,$dbAdapter,null,$resultSetPrototype);
                    return ( new BasicTableGateway($tableGateway,Noticia::TABLE) );
                },
                'Model\MensajeTable' => function($serviceManager) {
                    $dbAdapter = $serviceManager->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Mensaje());
                    $tableGateway = new TableGateway(Mensaje::TABLE,$dbAdapter,null,$resultSetPrototype);
                    return ( new BasicTableGateway($tableGateway,Mensaje::TABLE) );
                }
            )
        );
    }


    public function onBootstrap(MvcEvent $e)
    {

        $translator=$e->getApplication()->getServiceManager()->get('translator');
        $translator->addTranslationFile(
            'phpArray',
            './vendor/zendframework/zendframework/resources/languages/es/Zend_Validate.php'

        );
        AbstractValidator::setDefaultTranslator($translator);
    }

}
