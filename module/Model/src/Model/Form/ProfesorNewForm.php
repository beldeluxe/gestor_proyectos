<?php
/**
 * Created by PhpStorm.
 * User: bel
 * Date: 20/11/2018
 * Time: 18:44
 */

namespace Model\Form;


use Exception;
use Zend\Form\Form;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterInterface;
use Zend\InputFilter\InputFilterAwareInterface;


use Zend\Form\Element\Csrf as CsrfElement;
use Zend\Validator\Csrf as CsrfValidator;


class ProfesorNewForm extends Form implements InputFilterAwareInterface
{
    public function __construct( $name=null, $options=array() )
    {

        parent::__construct( $name, $options );

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
            'name' => 'lastname',
            'attributes' => array(
                'type' => 'text',
                'autocomplete' => 'off'
            )
        ) );

        $this->add( array(
            'type' => 'text',
            'name' => 'dni',
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

        $this->add( array(
            'type' => 'text',
            'name' => 'course',
            'attributes' => array(
                'type' => 'text',
                'autocomplete' => 'off'
            )
        ) );


        $this->add(array(
            'type' => 'Zend\Form\Element\Radio',
            'name' => 'rol',
            'attributes' => array(
                'required' => true,
                'class' =>'radiob'
            ),
            'options' => array(
                'label' => 'Selecciona un rol para el profesor',
                'value_options' => array(
                    '1' => 'Administrador',
                    '2' => 'Profesor',
                ),
            ),
        ));

        $this->add( array(
            'type' => 'text',
            'name' => 'password',
            'attributes' => array(
                'type' => 'text',
                'required' => true,
                'autocomplete' => 'off',
                'class' => 'pwd'
            )
        ) );


        $this->add( array(
            'type' => 'text',
            'name' => 'password_rep',
            'attributes' => array(
                'type' => 'text',
                'required' => true,
                'autocomplete' => 'off',
                'class' => 'pwd'
            )
        ) );

        $this->add( array(
            'type' => 'file',
            'name' => 'documentation',
            'attributes' => array(
                'type' => 'file',
                'required' => false,
                'multiple' => true,
            )
        ) );


        $this->add( array(
            'type' => 'textarea',
            'name' => 'observaciones',
            'attributes' => array(
                'type' => 'textarea',
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
                'value' => 'NewProfesor',
                'required' => false
            )
        ) );

        $this->setAttribute('method', 'POST');
        $this->setAttribute('class', 'smart-form');
        $this->setAttribute('id','new-profe-form');
        $this->setAttribute('enctype' , "multipart/form-data");
        $this->setAttribute('autocomplete' , "off");

    }

    public function getInputFilter()
    {
        if (! $this->filter) {

            $inputFilter = new InputFilter();

            $inputFilter->add( InputValidatorDefaults::createStringValidator( 'name', true ) );
            $inputFilter->add( InputValidatorDefaults::createStringValidator( 'lastname', true ) );
            $inputFilter->add( InputValidatorDefaults::createStringValidator( 'dni', true ) );
            $inputFilter->add( InputValidatorDefaults::createEmailValidator( 'email', true ) );


            $inputFilter->add( InputValidatorDefaults::createCsrfValidator('csrf', $this->csrf->getCsrfValidator() ) );

            $this->filter = $inputFilter;
        }

        return $this->filter;
    }

    public function setInputFilter(InputFilterInterface $inputFilter)
    {
        throw new Exception('ProfesorNewForm -> setInputFilter not allowed');
    }

}