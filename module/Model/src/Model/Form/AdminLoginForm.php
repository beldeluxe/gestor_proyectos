<?php

namespace Model\Form;

use Zend\Form\Form;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterInterface;
use Zend\InputFilter\InputFilterAwareInterface;

use Zend\Form\Element;
use Zend\Form\Fieldset;
use Zend\Form\Element\Csrf as CsrfElement;
use Zend\Validator\Csrf as CsrfValidator;

class AdminLoginForm extends Form implements InputFilterAwareInterface
{

    public function __construct( $name=null, $options=array() )
    {

        parent::__construct( $name, $options );

        $this->add( array(
            'type' => 'email',
            'name' => 'email',
            'attributes' => array(
                'type' => 'email',
                'required' => true,
                'autocomplete' => 'off'
            )
        ) );

        $this->add( array(
            'type' => 'password',
            'name' => 'password',
            'attributes' => array(
                'type' => 'password',
                'required' => false,
                'autocomplete' => 'off'
            )
        ) );

        $csrfValidator = new CsrfValidator(array('name'=> 'csrf','salt' => 'rew932093dseDDD')); 
        $csrf = new CsrfElement('csrf');
        $csrf->setCsrfValidator($csrfValidator);
        $this->add($csrf);
        $this->csrf = $csrf;
        
        $this->add( array(
            'type' => 'submit',
            'name' => 'submit',
            'attributes' => array(
                'value' => 'Login',
                'required' => false
            )
        ) );

    }

    public function getInputFilter()
    {
        if (! $this->filter) {

            $inputFilter = new InputFilter();

            $inputFilter->add( InputValidatorDefaults::createEmailValidator( 'email' ) );

            $inputFilter->add( InputValidatorDefaults::createCsrfValidator('csrf', $this->csrf->getCsrfValidator() ) ); 

            $this->filter = $inputFilter;
        }

        return $this->filter;
    }

    public function setInputFilter(InputFilterInterface $inputFilter)
    {
        throw new \Exception('LoginForm -> setInputFilter not allowed');
    }

}
