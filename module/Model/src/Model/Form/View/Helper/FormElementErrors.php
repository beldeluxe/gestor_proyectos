<?php

namespace Model\Form\View\Helper;

use Zend\Form\View\Helper\FormElementErrors as OriginalFormElementErrors;

class FormElementErrors extends OriginalFormElementErrors
{
    protected $messageCloseString     = '</li></ul>';
    protected $messageOpenFormat      = '<ul%s><li class="invalid row__error-text">';
    protected $messageSeparatorString = '</li><li class="invalid row__error-text">';
}