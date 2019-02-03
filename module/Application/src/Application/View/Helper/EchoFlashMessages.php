<?php

namespace Application\View\Helper;

use Zend\View\Helper\AbstractHelper;

class EchoFlashMessages extends AbstractHelper
{

    public function __construct()
    {
    }

    public function __invoke( $flashMessenger, $href = "#", $domStyle='' )
    {
        $domStyle='margin-top:10px';
        if (!empty($domStyle)) {
            $domStyle = 'style="'.$domStyle.'"';
        } else {
            $domStyle = 'style="margin-top:10px"';
        }

        $flashMessenger->setMessageOpenFormat('<div %s  '.$domStyle.' ><a href="'.$href.'" class="close" data-dismiss="alert" aria-label="close">&times;</a>');
        $flashMessenger->setMessageCloseString('</div>');
        echo $flashMessenger->renderCurrent('error',   array('alert', 'alert-danger'));
        echo $flashMessenger->renderCurrent('success', array('box', 'box-green'));
        echo $flashMessenger->renderCurrent('default', array('box', 'box-green'));
        $flashMessenger->getPluginFlashMessenger()->clearCurrentMessagesFromNamespace('default');
        $flashMessenger->getPluginFlashMessenger()->clearCurrentMessagesFromNamespace('success');
        $flashMessenger->getPluginFlashMessenger()->clearCurrentMessagesFromNamespace('error');
    }

}
