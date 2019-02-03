<?php
return array(
    'console' => array(
        'router' => array(
            'routes' => array(
                'mailing' => array(
                    'options' => array(
                        'route' => 'mailing [--date=date]',
                        'defaults' => array(
                            '__NAMESPACE__' => 'Console\Controller',
                            'controller' => 'commands',
                            'action'     => 'mailing'
                        ),
                    ),
                ),
                'exportBdgiTxt' => array(
                    'options' => array(
                        'route' => 'exportBdgiTxt [--date=date] [--local=int]',
                        'defaults' => array(
                            '__NAMESPACE__' => 'Console\Controller',
                            'controller' => 'commands',
                            'action'     => 'exportBdgiTxt'
                        ),
                    ),
                ),
            )
        )
    ),
    'controllers' => array(
        'invokables' => array(
            'Console\Controller\Commands' => 'Console\Controller\CommandsController'
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
