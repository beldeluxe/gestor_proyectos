<?php
/**
 * Created by PhpStorm.
 * User: bel
 * Date: 27/11/2018
 * Time: 13:05
 */

namespace Model\Form;


use Zend\Form\Form;
use Zend\InputFilter\InputFilter;
use Zend\Form\Element\Csrf as CsrfElement;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;
use Zend\Validator\Csrf as CsrfValidator;

class NoticiaForm extends Form implements InputFilterAwareInterface
{
    public function __construct( $name=null, $options=array())
    {

        parent::__construct( $name, $options );

        $this->add( array(
            'type' => 'text',
            'name' => 'title',
            'attributes' => array(
                'type' => 'text',
                'id'   => 'title',
                'required' => true,
            )
        ) );


        $this->add(array(
            'type' => 'text',
            'name' => 'excerpt',
            'options' => array(
            ),
            'attributes' => array(
                'required' => true,
                'id' => 'excerpt'
            )
        ));


        $this->add( array(
            'type' => 'text',
            'name' => 'comments',
            'attributes' => array(
                'id'   => 'comments',
                'required' => false,
            )
        ) );


        $this->add( array(
            'isArray' => 'true',
            'type' => 'file',
            'name' => 'mediafile',
            'attributes' => array(
                'id'   => 'mediafile',
                'required' => false,
            )
        ) );

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
        $this->setAttribute('id', 'noticiaForm');



    }

    public function getInputFilter()
    {
        if (! $this->filter) {

            $inputFilter = new InputFilter();

            //$inputFilter->add( InputValidatorDefaults::createStringValidator( 'project_id' , true) );
            $inputFilter->add( InputValidatorDefaults::createStringValidator( 'title' , true) );
            //$inputFilter->add( InputValidatorDefaults::createDigitValidator( 'budget' , true) );
            //$inputFilter->add( InputValidatorDefaults::createIntValidator( 'is_visible' , true) );
            $inputFilter->add( InputValidatorDefaults::createStringValidator( 'excerpt' , true) );
            $inputFilter->add( InputValidatorDefaults::createStringValidator( 'comments' , true) );
            //$inputFilter->add( InputValidatorDefaults::createStringValidator( 'email_text' , true) );
            //$inputFilter->add( InputValidatorDefaults::createIntValidator( 'visible' , true) );

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