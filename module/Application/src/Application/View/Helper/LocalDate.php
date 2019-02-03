<?php

namespace Application\View\Helper;

use Zend\View\Helper\AbstractHelper;
use Model\DataModel\DateTime;

class LocalDate extends AbstractHelper
{

    public function __construct()
    {
    }

    public function __invoke($dbDate, $withTime = 0) 
    {
    	if (empty($dbDate)) {
    		return '';
    	}

        $tvDate = DateTime::dbToTimeValue($dbDate);

        return DateTime::getDateFromTimeValue($tvDate, FALSE, $withTime );
    }

}
