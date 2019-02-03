<?php

namespace Application\View\Helper;

use Zend\View\Helper\AbstractHelper;
use Model\DataModel\Sanitizer;

class TextToHTML extends AbstractHelper
{

    public function __construct()
    {
    }

    public function __invoke($text)
    {
    	$result = array();

    	foreach ( explode("\n", $text) as $paragraph) {
			$result[] = $paragraph;		    		
    	}

    	$result = implode("<br>", $result);

    	return $result;
    }

}
