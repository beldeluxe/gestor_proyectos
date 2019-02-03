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

class ConfigTextForm extends Form implements InputFilterAwareInterface
{

    public function __construct( $name=null, $options=array(), $languageMultiFields )
    {

        parent::__construct( $name, $options );

        foreach ($languageMultiFields as $language) {

            $this->add( array(
                'type' => 'text',
                'name' => 'donationLimitText_'.$language,
                'attributes' => array(
                    'type' => 'text',
                    'id'   => 'donationLimitText_'.$language,
                    'required' => true,
                )
            ) );

            $this->add( array(
                'type' => 'text',
                'name' => 'donationPendingText_'.$language,
                'attributes' => array(
                    'type' => 'text',
                    'id'   => 'donationPendingText_'.$language,
                    'required' => true,
                )
            ) );

            $this->add( array(
                'type' => 'text',
                'name' => 'documentation_pending_'.$language,
                'attributes' => array(
                    'type' => 'text',
                    'id'   => 'documentation_pending_'.$language,
                    'required' => true,
                )
            ) );


        }

        $this->add( array(
            'type' => 'email',
            'name' => 'emailDocumentation',
            'attributes' => array(
                'type' => 'email',
                'id'   => 'emailDocumentation',
                'required' => false,
            )
        ) );

        $this->add( array(
            'type' => 'email',
            'name' => 'emailAmount',
            'attributes' => array(
                'type' => 'email',
                'id'   => 'emailAmount',
                'required' => false,
            )
        ) );

        $this->add( array(
            'type' => 'email',
            'name' => 'email',
            'attributes' => array(
                'type' => 'email',
                'id'   => 'email',
                'required' => false,
            )
        ) );

        $this->add( array(
            'type' => 'checkbox',
            'name' => 'recaptcha',
            'attributes' => array(
                'id'   => 'recaptcha',
                'checked_value' => 1,
                'unchecked_value' => 0
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
        $this->setAttribute('id', 'configTextForm');
    }

    public function getInputFilter()
    {
        if (! $this->filter) {

            $inputFilter = new InputFilter();

            // $inputFilter->add( InputValidatorDefaults::createStringValidator( 'donationLimitTitle' , true) );
            //  $inputFilter->add( InputValidatorDefaults::createStringValidator( 'donationLimitText' , true) );

            $inputFilter->add( InputValidatorDefaults::createEmailValidator( 'emailDocumentation' , true) );
            $inputFilter->add( InputValidatorDefaults::createEmailValidator( 'emailAmount' , true) );

            $inputFilter->add( InputValidatorDefaults::createCsrfValidator('csrf', $this->csrf->getCsrfValidator() ) );

            $this->filter = $inputFilter;
        }

        return $this->filter;
    }

    public function setInputFilter(InputFilterInterface $inputFilter)
    {
        throw new \Exception('ConfigTextForm -> setInputFilter not allowed');
    }

}