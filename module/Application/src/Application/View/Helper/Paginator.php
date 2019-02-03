<?php

namespace Application\View\Helper;

use Zend\View\Helper\AbstractHelper;

class Paginator extends AbstractHelper
{


    public function __construct()
    {
    }


    public function __invoke( $url, $currentPage, $pageCount, $limit )
    {

        if (!$pageCount || $pageCount == 1) return '';

        $result = '<div style="text-align:center">';

            $startPage = ($currentPage > 2)? ($currentPage - 2) : 1;
            $endPage   = 
                  ( ($currentPage + 2) <= $pageCount )? ($currentPage + 2) : 
                ( ( ($currentPage + 1) <= $pageCount )? ($currentPage + 1) : $currentPage );

            $prev = (($currentPage - 5) >= 1 )?          ($currentPage - 5) : 1;
            $next = (($currentPage + 5) <= $pageCount )? ($currentPage + 5) : $pageCount;

            $result .= '<ul class="pagination pagination-sm">';
            $result .= '<li><a class="paginator-page-btn" href="#" data-url='. $url .' data-limit='. $limit .' data-page="'. $prev .'">&lt;&lt;</a></li>';
            for ($p=$startPage; $p<=$endPage ; $p++) { 
                $result .= ($p == $currentPage)? '<li class="active">' : '<li>';
                $result .= '<a class="paginator-page-btn" href="#" data-url='. $url .' data-limit='. $limit .' data-page="'. $p .'">' . $p . '</a>';
                $result .= '</li>' . "\n";
            }
            $result .= '<li><a class="paginator-page-btn" href="#" data-url='. $url .' data-limit='. $limit .' data-page="'. $next .'">&gt;&gt;</a></li>';
            $result .= '</ul>';

        $result .= '</div>';

        return $result;
    }

}
