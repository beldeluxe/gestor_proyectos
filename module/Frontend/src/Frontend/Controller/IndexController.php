<?php

namespace Frontend\Controller;

use Application\Controller\ApplicationController;

use Model\DataModel\Sanitizer;
use Model\DataModel\DateTime;
use Model\DataModel\FileMan;

use Model\BusinessModel\Donation;
use Model\BusinessModel\Project;
use Model\BusinessModel\User;
use Model\CommonContentsModel\Language;
use Model\CommonContentsModel\MediaElementLabel;

use Zend\Session\Container;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;

use Model\BasicForm;


class IndexController extends ApplicationController
{	



	// Funciones para retornar imágenes    

    protected function imageView($type)
    {
		$fileName = FileMan::APP_PUBLIC_DIR . "/images/" . basename($this->params()->fromRoute('detail')) . "." . $type;

		if (is_file($fileName)) {
			return $this->imageResult($fileName,$type);
		}
		
		return $this->imageResult(FileMan::APP_PUBLIC_DIR . "/images/notFound.png", "png");
    }

    public function pngAction()
	{
		return $this->imageView("png");
	}

    public function gifAction()
	{
		return $this->imageView("gif");
	}

    public function jpgAction()
	{
		return $this->imageView("jpg");
	}



}

