<?php

namespace Application;

return array(
    'router' => array(
        'routes' => array(
            'home' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '',
                    'defaults' => array(
                        'controller' => 'Frontend\Controller\Index',
                        'action'     => 'index',
                    ),
                ),
            ),
        ),
    ),
    'service_manager' => array(
        'abstract_factories' => array(
            'Zend\Cache\Service\StorageCacheAbstractServiceFactory',
            'Zend\Log\LoggerAbstractServiceFactory',
        ),
        'factories' => array(
            'translator' => 'Zend\Mvc\Service\TranslatorServiceFactory',
            'WSConnectService' => 'Application\Factory\WSConnectServiceFactory',

        ),
    ),
    'translator' => array(
        'locale' => 'en_US',
        'translation_file_patterns' => array(
            array(
                'type'     => 'gettext',
                'base_dir' => __DIR__ . '/../language',
                'pattern'  => '%s.mo',
            ),
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'Application\Controller\Index' => Controller\IndexController::class
        ),
    ),
    'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => array(
            'frontend/layout'         => __DIR__ . '/../../Frontend/view/frontend/layout/layout.phtml',
            'backend/layout'          => __DIR__ . '/../../Backend/view/backend/layout/layout.phtml',
            'error/404'               => __DIR__ . '/../../Application/view/error/404.phtml',
            'error/index'             => __DIR__ . '/../../Application/view/error/index.phtml',
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
        'strategies' => array(
            'ViewJsonStrategy',
        )
    ),
    'view_helpers' => array(
        'invokables' => array(
            'addQueryParameter' => 'Application\View\Helper\AddQueryParameter',
            'localDate'         => 'Application\View\Helper\LocalDate',
            'localUrl'          => 'Application\View\Helper\LocalUrl',
            'stringToSlug'      => 'Application\View\Helper\StringToSlug',
            'echoFlashMessages' => 'Application\View\Helper\EchoFlashMessages',
            'textToHTML'        => 'Application\View\Helper\TextToHTML',
            'iconFA'            => 'Application\View\Helper\IconFA',
            'paginator'         => 'Application\View\Helper\Paginator',
            'optionTag'         => 'Application\View\Helper\OptionTag',
            'countryList'       => 'Application\View\Helper\CountryList',
            'provinceList'       => 'Application\View\Helper\ProvinceList',
        ),
    ),     
);
