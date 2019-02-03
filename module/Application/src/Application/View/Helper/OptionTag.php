<?php

namespace Application\View\Helper;

use Zend\View\Helper\AbstractHelper;
use Model\DataModel\Sanitizer;

class OptionTag extends AbstractHelper
{
    
    public function __invoke( $elementId, $elementLabel,  $selected=false )
    {

        $optionTagStr = ($selected)?
            '<option value="%s" selected>%s</option>' : 
            '<option value="%s">%s</option>';

        return sprintf($optionTagStr,(string)$elementId,$elementLabel);
    }

}
