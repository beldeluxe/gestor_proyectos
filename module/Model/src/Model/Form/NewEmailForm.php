<?php
/**
 * Created by PhpStorm.
 * User: bel
 * Date: 30/11/2018
 * Time: 21:54
 */

namespace Model\Form;


use Zend\Form\Form;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\Validator\Csrf as CsrfValidator;
use Zend\Form\Element\Csrf as CsrfElement;

use Zend\InputFilter\InputFilterInterface;



class NewEmailForm extends Form implements InputFilterAwareInterface
{
    public function __construct( $name=null, $options=array())
    {

        parent::__construct( $name, $options );

        $this->add( array(
            'type' => 'text',
            'name' => 'asunto',
            'attributes' => array(
                'type' => 'text',
                'id'   => 'asunto',
                'required' => true,
            )
        ) );

        $this->add( array(
            'type' => 'text',
            'name' => 'comments',
            'attributes' => array(
                'id'   => 'comments',
                'required' => false,
            )
        ) );


        $this->add(array(
            'type' => 'Zend\Form\Element\Select',
            'name' => 'destinatario',
            'options' => array(
            ),
            'attributes' => array(
                'required' => true,
                'id' => 'destinatario'
            )
        ));


        //image file
        /*$file = new Element\File('image_file');
        $file->setLabel('');
        $file->setAttribute('id', 'image_file');
        $file->setAttribute('accept','image/*');
        $this->add($file);*/



        $csrfValidator = new CsrfValidator(array('name'=> 'csrf','salt' => 'rew932093dseDDD'));
        $csrf = new CsrfElement('csrf');
        $csrf->setCsrfValidator($csrfValidator);
        $this->add($csrf);
        $this->csrf = $csrf;


        $this->setAttribute('method', 'POST');
        $this->setAttribute('enctype' , "multipart/form-data");
        $this->setAttribute('autocomplete' , "off");
        $this->setAttribute('class', 'smart-form');
        $this->setAttribute('id', 'newEmailForm');



    }

    public function getInputFilter()
    {
        if (! $this->filter) {

            $inputFilter = new InputFilter();

            $inputFilter->add( InputValidatorDefaults::createStringValidator( 'asunto' , true) );
            $inputFilter->add( InputValidatorDefaults::createStringValidator( 'comments' , true) );
            $inputFilter->add( InputValidatorDefaults::createStringValidator( 'destinatario' , true) );
            $inputFilter->add( InputValidatorDefaults::createCsrfValidator('csrf', $this->csrf->getCsrfValidator() ) );

            $this->filter = $inputFilter;
        }

        return $this->filter;
    }

    public function setInputFilter(InputFilterInterface $inputFilter)
    {
        throw new \Exception('Noticiaform -> setInputFilter not allowed');
    }
}