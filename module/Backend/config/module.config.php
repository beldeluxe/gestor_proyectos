<?php
return array(
    'router' => array(
        'routes' => array(
            'backend' => array(
                'type'    => 'Literal',
                'options' => array(
                    'route'    => '/admin',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Backend\Controller',
                        'controller'    => 'Backend',
                        'action'        => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'default' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/[:controller[/:action[/:id]]]',
                            'constraints' => array(
                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'id'         => '[0-9]*',
                            ),
                            'defaults' => array(
                            ),
                        ),
                    ),
                ),
            ),
            'backend-login' => array(
                'type'    => 'Literal',
                'options' => array(
                    'route'    => '/mpfnimda/login',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Backend\Controller',
                        'controller'    => 'Backend',
                        'action'        => 'login',
                    ),
                ),
            ),
            'backend-logout' => array(
                'type'    => 'Literal',
                'options' => array(
                    'route'    => '/mpfnimda/logout',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Backend\Controller',
                        'controller'    => 'Backend',
                        'action'        => 'logout',
                    ),
                ),
            ),
            'cancel' => array(
                'type'    => 'Literal',
                'options' => array(
                    'route'    => '/users/cancel/:id',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Backend\Controller',
                        'controller'    => 'Backend',
                        'action'        => 'cancel',
                    ),
                ),
            ),
            'change-password' => array(
                'type'    => 'Literal',
                'options' => array(
                    'route'    => '/admin/change-password',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Backend\Controller',
                        'controller'    => 'Backend',
                        'action'        => 'change-password',
                    ),
                ),
            )
        ),
    ),
    'translator' => array(
        //'locale' => 'es_ES',
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
            'Backend\Controller\Backend' => 'Backend\Controller\BackendController',
            'Backend\Controller\Projects' => 'Backend\Controller\ProjectsController',
            'Backend\Controller\Admins' => 'Backend\Controller\AdminsController',
            'Backend\Controller\ConfigText' => 'Backend\Controller\ConfigTextController',
            'Backend\Controller\Foro' => 'Backend\Controller\ForoController',
            'Backend\Controller\Domiciliations' => 'Backend\Controller\DomiciliationsController',
            'Backend\Controller\Users' => 'Backend\Controller\UsersController',
            'Backend\Controller\Logger' => 'Backend\Controller\LoggerController',
            'Backend\Controller\Cancelation' => 'Backend\Controller\CancelationController',
            'Backend\Controller\Alumnos' => 'Backend\Controller\AlumnosController',
            'Backend\Controller\Profesors' => 'Backend\Controller\ProfesorsController',
            'Backend\Controller\Mensajes' => 'Backend\Controller\MensajesController',

        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
    'view_helpers' => array(
        'invokables'=> array(

            'admins_role_helper' => 'Backend\View\helper\Adminshelper'


        )
    ),
);