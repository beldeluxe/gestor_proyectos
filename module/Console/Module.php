<?php
namespace Console;

use Zend\Console\Adapter\AdapterInterface as Console;

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

    public function getConsoleUsage(Console $console)
    {
        return array(
            // Describe available commands
            'mailing'  => 'Send appreciation e-mail to users',
            array( '--date', '(optional date yyyy-mm-dd) Set date (def. current date -1 day)' ),
        );
    }
}
