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

class AdminForm extends Form implements InputFilterAwareInterface
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

        $this->add( array(
            'type' => 'text',
            'name' => 'surname',
            'attributes' => array(
                'type' => 'text',
                'autocomplete' => 'off'
            )
        ) );

        $this->add( array(
            'type' => 'email',
            'name' => 'email',
            'attributes' => array(
                'type' => 'email',
                'required' => true,
                'autocomplete' => 'off'
            )
        ) );

        $this->add(array(
            'type' => 'select',
            'name' => 'status',
            'value'        => '0',
            'options' => array(
                'value_options' => array(
                    'ACTIVE' => 'ACTIVADO',
                    'INACTIVE' => 'DESACTIVADO',
                ),
            ),
            'attributes' => array(
                'required' => true,
                'autocomplete' => 'off'
            )
        ));

        $this->add( array(
            'type' => 'text',
            'name' => 'password',
            'attributes' => array(
                'type' => 'password',
                'autocomplete' => 'off',
                'class' => 'pwd'
            )
        ) );

        $this->add( array(
            'type' => 'text',
            'name' => 'password_rep',
            'attributes' => array(
                'type' => 'password',
                'autocomplete' => 'off',
                'class' => 'pwd'

            )
        ) );

        $this->add(array(
            'type' => 'select',
            'name' => 'role_type',
            'value'        => '0',
            'options' => array(
                'value_options' => array(
                    0 => 'ADMIN',
                    1 => 'ADMON',
                    2 => 'GESTOR'
                ),
            ),
            'attributes' => array(
                'required' => true,
                'autocomplete' => 'off'
            )
        ));

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


        $this->setAttribute('method', 'POST');
        $this->setAttribute('class', 'smart-form');
        $this->setAttribute('id','edit-form');
        $this->setAttribute('enctype' , "multipart/form-data");
        $this->setAttribute('autocomplete' , "off");
        //$this->setAttribute('action', '/admin/admins/edit');


    }

    public function getInputFilter()
    {
        if (! $this->filter) {

            $inputFilter = new InputFilter();

            $inputFilter->add( InputValidatorDefaults::createStringValidator( 'name', true ) );
            $inputFilter->add( InputValidatorDefaults::createStringValidator( 'surname', false ) );
            $inputFilter->add( InputValidatorDefaults::createEmailValidator( 'email', true ) );
            $inputFilter->add( InputValidatorDefaults::createStringValidator('status', true) );
            $inputFilter->add( InputValidatorDefaults::createIntValidator('role_type', true) );
            $inputFilter->add( InputValidatorDefaults::createIdenticalValidator( 'password', 'password_rep' ) );
            $inputFilter->add( InputValidatorDefaults::createStringLengthValidator( 'password', 8, 18 ) );
            $inputFilter->add( InputValidatorDefaults::createStringLengthValidator( 'password_rep', 8, 18 ) );
            $inputFilter->add( InputValidatorDefaults::createCsrfValidator('csrf', $this->csrf->getCsrfValidator() ) );

            $this->filter = $inputFilter;
        }

        return $this->filter;
    }

    public function setInputFilter(InputFilterInterface $inputFilter)
    {
        throw new \Exception('AdminForm -> setInputFilter not allowed');
    }

}
