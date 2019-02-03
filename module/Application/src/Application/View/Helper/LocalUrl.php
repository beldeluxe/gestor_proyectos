<?php

namespace Application\View\Helper;

use Zend\View\Helper\AbstractHelper;

class LocalUrl extends AbstractHelper
{

    private static $HOST_NAME;

    private function getHostName()
    {
        if (!LocalUrl::$HOST_NAME) {
            $configArray = include "config/autoload/local.php";
            LocalUrl::$HOST_NAME = $configArray['http-prefix'].$configArray['host-name'];
        }
        return LocalUrl::$HOST_NAME;
    }   

    public function __construct()
    {
        //$this->defaultCaption    = 'ES';
    }

    public function setCurrentLangCaption($caption)
    {
        return $this;
    }

    public function __invoke($controller = "", $action = "", $id = "") 
    {

        $strResultUrl = $this->getHostName();

        if ($controller) {
            $strResultUrl .= "/" . $controller;
        }

        if ($action) {
            $strResultUrl .= "/" . $action;
        }

        if ($id) {
            $strResultUrl .= "/" . $id;
        }

        return $strResultUrl;
    }

}
