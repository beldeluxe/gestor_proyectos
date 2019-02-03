<?php

namespace Application\View\Helper;

use Zend\View\Helper\AbstractHelper;
use Model\DataModel\Sanitizer;

class StringToSlug extends AbstractHelper
{

    public function __construct()
    {
    }

    public function __invoke($string)
    {
    	return Sanitizer::slugFromString($string);
    }

}
