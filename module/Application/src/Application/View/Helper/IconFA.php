<?php

namespace Application\View\Helper;

use Zend\View\Helper\AbstractHelper;
use Model\DataModel\Sanitizer;

class IconFA extends AbstractHelper
{
    
    private static $ICONS_ARR;

    public function __construct()
    {
    }

    public function __invoke( $iconType, $large = true, $returnJustIconClass = false )
    {

        $sz = ($large)? 'fa-lg' : '';

        if (!isset($ICONS_ARR)) {
            $ICONS_ARR = array(
                'IMG' => 'fa-picture-o',
                'VID' => 'fa-video-camera',
                'DOC' => 'fa-file-o',
                'VISIBLE' => 'fa-eye',
                'NOTVISIBLE' => 'fa-eye-slash',
                'EDIT' => 'fa-edit',
                'DELETE' => 'fa-times',
                'UP' => 'fa-arrow-up',
                'DOWN' => 'fa-arrow-down',
                'DETAIL' => 'fa-plus-circle',
                'ACTIVE' => 'fa-thumbs-up',
                'INACTIVE' => 'fa-thumbs-down',
                'BANNED' => 'fa-times',
                'SHOW'   => 'fa-search'
            );
        }

        $icon = (isset($ICONS_ARR[(string)$iconType]))? $ICONS_ARR[(string)$iconType] : 'fa-ban';

        if ($returnJustIconClass) {
            return $icon;
        }

        return 'fa' .' '. $sz .' '. $icon;
    }

}
