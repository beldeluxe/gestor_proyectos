<?php

namespace Backend\Controller;

use Model\BusinessModel\ConfigText;
use Model\Form\ConfigTextForm;
use Zend\View\Model\ViewModel;
use Model\DataModel\Sanitizer;
use Model\DataModel\DateTime;


class ConfigTextController extends BackendController
{

    public function indexAction()
    {
        $pageTitle = 'Configuración';


        $arrVariables = array(
            'pageTitle' => $pageTitle,
        );

        $contentVM = new ViewModel();
        $contentVM->setTemplate('backend/configurations/index')->setVariables($arrVariables);

        return $this->layoutBackendView($contentVM, 9);
    }

}

