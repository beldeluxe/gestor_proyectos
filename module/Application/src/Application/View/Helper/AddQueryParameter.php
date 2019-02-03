<?php

namespace Application\View\Helper;

use Zend\View\Helper\AbstractHelper;

class AddQueryParameter extends AbstractHelper
{

    public function __construct()
    {
    }

    public function __invoke($url,$newParameter,$newValue) 
    {
        $parsedUrl = parse_url($url);

        $resultQuery = '';
        $amp = '';
        $isUpdate = 0;
        $query = (isset($parsedUrl['query']))? $parsedUrl['query'] : '';

        foreach ( explode ( '&' , $query ) as $parameter) {
            $arrPV = explode ( '=' , $parameter );
            $parameterName  = $arrPV[0];
            $value = (isset($arrPV[1]))? $arrPV[1] : '';
            if ($parameterName==$newParameter) {
                $value = $newValue; 
                $isUpdate = 1;
            } 
            if (strlen($value)>0) {
                $resultQuery .= $amp.$parameterName.'='.$value;
                $amp = '&';
            }
        }

        if (!$isUpdate && (strlen($newValue)>0) ) {
            $resultQuery .= '&'.$newParameter.'='.$newValue;
        }

        return $parsedUrl['path'].'?'.$resultQuery;
    }

}
