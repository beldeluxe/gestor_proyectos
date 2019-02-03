<?php
return array(
    'router' => array(
        'routes' => array(
            'front-idx' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'       => '/[:action[/:detail[/:id]]]',
                    'constraints' => array(
                        'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'detail'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'         => '[0-9]*',
                    ),
                    'defaults' => array(
                        'controller' => 'Frontend\Controller\Index'
                    ),
                )
            ),
            'downloadPdf' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'       => '/donations/download/:donationId/:token',
                    'defaults' => array(
                        'controller' => 'Frontend\Controller\Donations',
                        'action'     => 'downloadPdf',
                    ),
                    'constraints' => array(
                        'id' => '[0-9]*',
                        'token' => '[a-zA-Z0-9_-]*',

                    )
                )
            ),
            'download-error' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'       => '/donations/downloadError',
                    'defaults' => array(
                        'controller' => 'Frontend\Controller\Donations',
                        'action'     => 'downloadError',
                    )
                )
            ),
            'change-locale' => array(
                'type' => 'literal',
                'options' => array(
                    'route' => '/change-locale',
                    'defaults' => array(
                        'controller' => 'Frontend\Controller\Index',
                        'action' => 'change-locale',

                    ),
                )
            ),
        ),
    ),
    'service_manager' => array(
        'factories' => array(
            'translator' => 'Zend\Mvc\Service\TranslatorServiceFactory',
        ),
    ),
    'translator' => array(
        'translation_file_patterns' => array(
            array(
                'type'     => 'phpArray',
                'base_dir' => __DIR__ . '/../language',
                'pattern' => '%s.php',
            )
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'Frontend\Controller\Index'    => 'Frontend\Controller\IndexController',


        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
        'strategies' => array(
            'ViewJsonStrategy',
        )
    ),
);
