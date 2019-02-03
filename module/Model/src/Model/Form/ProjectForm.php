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

class ProjectForm extends Form implements InputFilterAwareInterface
{

    public function __construct( $name=null, $options=array())
    {

        parent::__construct( $name, $options );

        $this->add( array(
            'type' => 'text',
            'name' => 'name',
            'attributes' => array(
                'type' => 'text',
                'id'   => 'name',
                'required' => true,
            )
        ) );


        $this->add(array(
            'type' => 'Zend\Form\Element\Select',
            'name' => 'id_alumn',
            'options' => array(
            ),
            'attributes' => array(
                'required' => true,
                'id' => 'id_alumn'
            )
        ));


        $this->add( array(
            'type' => 'Zend\Form\Element\Textarea',
            'name' => 'comments',
            'attributes' => array(
                'id'   => 'comments',
                'required' => false,
                'cols' => 40,
                'rows' => 5
            )
        ) );


        $this->add( array(
            'isArray' => 'true',
            'type' => 'file',
            'name' => 'documentation',
            'attributes' => array(
                'id'   => 'documentation',
                'required' => false,

                'multiple' => 'multiple',
            )
        ) );


        $csrfValidator = new CsrfValidator(array('name'=> 'csrf','salt' => 'rew932093dseDDD'));
        $csrf = new CsrfElement('csrf');
        $csrf->setCsrfValidator($csrfValidator);
        $this->add($csrf);
        $this->csrf = $csrf;


        $this->setAttribute('method', 'POST');
        $this->setAttribute('enctype' , "multipart/form-data");
        $this->setAttribute('autocomplete' , "off");
        $this->setAttribute('class', 'smart-form');
        $this->setAttribute('id', 'projectForm');



    }

    public function getInputFilter()
    {
        if (! $this->filter) {

            $inputFilter = new InputFilter();


            $inputFilter->add( InputValidatorDefaults::createStringValidator( 'name' , true) );
            $inputFilter->add( InputValidatorDefaults::createStringValidator( 'id_alumn' , true) );
            $inputFilter->add( InputValidatorDefaults::createCsrfValidator('csrf', $this->csrf->getCsrfValidator() ) );

            $this->filter = $inputFilter;
        }

        return $this->filter;
    }

    public function setInputFilter(InputFilterInterface $inputFilter)
    {
        throw new \Exception('ProjectForm -> setInputFilter not allowed');
    }

}
