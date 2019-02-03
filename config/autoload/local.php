<?php
return array(
    'db' => array(
        'driver' => 'Pdo Mysql',
        'dsn' => 'mysql:dbname=proyecto;host=127.0.0.1',
        //'dsn' => 'mysql:dbname=u185970131_proj;host=127.0.0.1',
        'username' => 'root',
        //'username' => 'u185970131_root',
        'password' => '',
        //'password' => 'root01',
    ),
    'http-prefix' => 'http://',
    'host-name'   => 'proyecto.test',
    'root-password' => 'Prueba01',
    'root-email'    => '',

    'email-test-user' => 'moodle.proyecto.test@gmail.com',
    'sessionConfig' => array(
        'cache_expire'        => 86400,
        'cookie_lifetime'     => 12000,
        'use_cookies'         => true,
        'remember_me_seconds' => 12000,
        'cookie_httponly'     => true,
        'cookie_secure'       => false
    ),

    'mail-server' => array(
        'host'              => 'smtp.gmail.com',
        'name'              => 'localhost',
        'connection_class'  => 'login',
        'connection_config' => array(
            'username' => 'beldeluxe@gmail.com',
            'password' => '03127364p',
            'ssl' => 'tls',
            'port' => 587
        ),
    ),

    'locale' => array(
        'default' => 'es_ES',
        'available'     => array(
            'en_US' => 'Inglés',
            'es_ES' => 'Español',
            'pt_PT' => 'Portugués',
        ),
    ),

);