<?php

namespace Model\Form;

use Zend\Form\Form;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterInterface;
use Zend\InputFilter\InputFilterAwareInterface;

use Zend\Form\Element;
use Zend\Form\Element\Csrf as CsrfElement;
use Zend\Validator\Csrf as CsrfValidator;

class SourceForm extends Form implements InputFilterAwareInterface
{

    public function __construct(  )
    {

        parent::__construct( );

        $this->add( array(
            'type' => 'text',
            'name' => 'name',
            'attributes' => array(
                'type' => 'text',
                'autocomplete' => 'off',
                'required' => true

            )
        ) );


        $csrfValidator = new CsrfValidator(array('name'=> 'csrf','salt' => 'rew932093dseDDD'));
        $csrf = new CsrfElement('csrf');
        $csrf->setCsrfValidator($csrfValidator);
        $this->add($csrf);
        $this->csrf = $csrf;

        $this->setAttribute('method', 'POST');
        $this->setAttribute('class', 'smart-form');
        $this->setAttribute('id','source-form');
        $this->setAttribute('enctype' , "multipart/form-data");
        $this->setAttribute('autocomplete' , "off");

    }

    public function getInputFilter()
    {
        if (! $this->filter) {

            $inputFilter = new InputFilter();

            $inputFilter->add( InputValidatorDefaults::createStringValidator( 'name', true ) );
            $inputFilter->add( InputValidatorDefaults::createCsrfValidator('csrf', $this->csrf->getCsrfValidator() ) );

            $this->filter = $inputFilter;
        }

        return $this->filter;
    }

    public function setInputFilter(InputFilterInterface $inputFilter)
    {
        throw new \Exception('SourceForm -> setInputFilter not allowed');
    }

}
