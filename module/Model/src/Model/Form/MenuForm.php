<?php

namespace Model\Form;

use Model\CommonContentsModel\MenuEntry;
use Zend\Form\Form;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;
use Zend\Stdlib\Hydrator\ClassMethods as ClassMethodsHydrator;

use Zend\Form\Element\Csrf as CsrfElement;
use Zend\Validator\Csrf as CsrfValidator;

class MenuForm extends Form implements InputFilterAwareInterface
{
    public function __construct()
    {
        parent::__construct('menu');

        $this
            ->setAttribute('method', 'post')
            //->setHydrator(new ClassMethodsHydrator(false))
            //->setInputFilter(new InputFilter())
            ->setAttribute('class','smart-form')
            ->setAttribute('enctype' , "multipart/form-data");
            //->setAttribute('action', '/admin/menus/edit');


        $this->add(array(
            'type' => 'Zend\Form\Element\Select',
            'name' => 'type',
            'options' => array(
                'required' => 'required',
                'value_options' => array(
                    MenuEntry::TYPE_TERMINAL => 'TERMINAL',
                    MenuEntry::TYPE_MENU => 'SUBMENU'
                ),
            ),
            'attributes' =>array(
                'id' => 'type'
            )
        ));

        $this->add(array(
            'type' => 'Zend\Form\Element\Select',
            'name' => 'type_terminal',
            'options' => array(
                'required' => 'required',
                'value_options' => array(
                    MenuEntry::TYPE_PAGE => 'PAGE',
                    MenuEntry::TYPE_URL => 'URL'
                ),
            ),
            'attributes' =>array(
                'id' => 'type_terminal'
            )
        ));

        $this->add(array(
            'type' => 'Zend\Form\Element\Select',
            'name' => 'parent_element_id',
            'options' => array(
                'value_options' => array(),
            )
        ));

        $this->add(array(
            'type' => 'Zend\Form\Element\Select',
            'name' => 'is_visible',
            'options' => array(
                'required' => 'required',
                'value_options' => array(
                    '1' => 'VISIBLE',
                    '0' => 'NO VISIBLE'
                ),
            )
        ));

        $this->add(array(
            'type' => 'Zend\Form\Element\Select',
            'name' => 'target_page_id',
            'options' => array(
                'value_options' => array(),
            ),
            'attributes' =>array(
                'id' => 'target_page_id'
            )
        ));

        $this->add( array(
            'type' => 'text',
            'name' => 'target_url',
            'attributes' => array(
                'maxlength' => '100',
                'placeholder' => 'http://www.example.com',
                'class' => 'form-control',
                'style' => 'height:30px',
                'id' => 'target_url'
            ),

        ));

        $this->add(array(
            'type' => 'Zend\Form\Element\Select',
            'name' => 'page_blank',
            'options' => array(
                //'required' => 'required',
                'value_options' => array(
                    '0' => 'NO',
                    '1' => 'SI'
                ),
            )
        ));

        $this->add( array(
            'type' => 'Zend\Form\Element\Collection',
            'name' => 'entry_labels',
            'options' => array(
                'should_create_template' => true,
                'count' => 0,
                'allow_add' => true,
                'target_element' => array(
                    'type' => 'Model\Form\MenuEntryLabelFieldset'
                ),
            ),
        ));


        $csrfValidator = new CsrfValidator(array('name'=> 'csrf','salt' => 'rew932093dseDDD')); 
        $csrf = new CsrfElement('csrf');
        $csrf->setCsrfValidator($csrfValidator);
        $this->add($csrf);
        $this->csrf = $csrf;     
    }


    public function getInputFilter()
    {
        if (! $this->filter) {

            $inputFilter = new InputFilter();

            $inputFilter->add( InputValidatorDefaults::createStringValidator( 'type' ) );
            $inputFilter->add( InputValidatorDefaults::createStringValidator( 'type_terminal' ) );
           // $inputFilter->add( InputValidatorDefaults::createIntValidator( 'order' ) );
            $inputFilter->add( InputValidatorDefaults::createIntValidator( 'target_page_id' ) );
            $inputFilter->add( InputValidatorDefaults::createIntValidator( 'is_visible' ) );
            $inputFilter->add( InputValidatorDefaults::createIntValidator( 'page_blank' ) );
            $inputFilter->add( InputValidatorDefaults::createStringValidator( 'target_url' ) );
            $inputFilter->add( InputValidatorDefaults::createCsrfValidator('csrf', $this->csrf->getCsrfValidator() ) );

            $this->filter = $inputFilter;
        }

        return $this->filter;
    }

    public function setInputFilter(InputFilterInterface $inputFilter)
    {
        throw new \Exception('MenuForm -> setInputFilter not allowed');
    }


}
