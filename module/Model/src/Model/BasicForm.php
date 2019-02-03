<?php

namespace Model;

use Zend\Form\Form;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterInterface;
use Zend\InputFilter\InputFilterAwareInterface;

use Zend\Form\Element;
use Zend\Form\Fieldset;
use Zend\Form\Element\Csrf as CsrfElement;
use Zend\Validator\Csrf as CsrfValidator;

use Model\Form\Element\SpDate;
use Model\Form\Element\Phone;
use Model\Form\Element\NIF;
use Model\Form\InputValidatorDefaults;

class BasicForm extends Form implements InputFilterAwareInterface
{

    private $csrf;
    private $inputFilterValidators; 
    private $defaultValues;

    public function __construct( $formArray, $withCSRFToken=true, $name=null, $options=array() )
    {
     
        $this->inputFilterValidators = array();

        parent::__construct( $name, $options );

        foreach ($formArray as $field => $optionsArray) {

            $fieldsArray = array();
            if (!empty($optionsArray['multiValue'])) {
                foreach ($optionsArray['multiValue'] as $value) {
                    $fieldsArray[] = $field . "_" . $value;
                }
            } else {
                $fieldsArray[] = $field;
            }

            foreach ($fieldsArray as $fieldName) {

                $defaultValues[$fieldName] = 
                    (isset($optionsArray['default']))? $optionsArray['default'] : '';

                $defaultValidator = true;
                    
                switch ($optionsArray['type']) {
                    case 'radio': 
                        $optionsArray['type'] = 'Zend\Form\Element\Radio'; 
                        break;
                    case 'checkbox': 
                        $optionsArray['type'] = 'Zend\Form\Element\Checkbox'; 
                        break;
                    case 'tel':  
                        $phoneElement = new Phone($fieldName);
                        $phoneElement->setAttributes($optionsArray['formElementAttributes']);
                        $this->add($phoneElement);
                        if ($optionsArray['validate']) {
                            $this->inputFilterValidators[] = 
                                $phoneElement->getInputSpecification(true);    
                        }
                        $defaultValidator = false;
                        break;
                    case 'nif':  
                        $nifElement = new NIF($fieldName);
                        $nifElement->setAttributes($optionsArray['formElementAttributes']);
                        $this->add($nifElement);
                        if ($optionsArray['validate']) {
                            $this->inputFilterValidators[] = 
                                $nifElement->getInputSpecification(true);    
                        }
                        $defaultValidator = false;
                        break;
                    case 'email':  
                        $this->add(
                            array(
                                'type'       => 'email',
                                'name'       => $fieldName,
                                'attributes' => $optionsArray['formElementAttributes'],
                            )
                        );
                        if ($optionsArray['validate']) {
                            $this->inputFilterValidators[] = 
                                InputValidatorDefaults::createEmailValidator($fieldName,true);
                        }
                        $defaultValidator = false;
                        break;
                    case 'date':  
                        $this->add(
                            array(
                                'type'       => 'date',
                                'name'       => $fieldName,
                                'attributes' => $optionsArray['formElementAttributes'],
                            )
                        );
                        if ($optionsArray['validate']) {
                            $this->inputFilterValidators[] = 
                                InputValidatorDefaults::createDateValidator($fieldName,true);
                        }
                        $defaultValidator = false;
                        break;
                    case 'int':
                        $this->add(
                            array(
                                'type'       => 'number',
                                'name'       => $fieldName,
                                'attributes' => $optionsArray['formElementAttributes'],
                            )
                        );
                        if ($optionsArray['validate']) {
                            $this->inputFilterValidators[] = 
                                InputValidatorDefaults::createIntValidator($fieldName,true);
                        }
                        $defaultValidator = false;
                        break;
                    case 'file':
                        $this->add(
                            array(
                                'type'       => 'file',
                                'name'       => $fieldName,
                                'attributes' => $optionsArray['formElementAttributes'],
                            )
                        );
                        if ($optionsArray['validate']) {
                            $this->inputFilterValidators[] =
                                InputValidatorDefaults::createStringValidator($fieldName,false);
                        }
                        $defaultValidator = false;
                        break;
                    default:
                        $defaultValidator = true;
                        break;
                }

                if ($defaultValidator) {
                    $this->add(
                        array(
                            'type'       => $optionsArray['type'],
                            'name'       => $fieldName,
                            'options'    => $optionsArray['formElementOptions'],
                            'attributes' => $optionsArray['formElementAttributes'],
                        )
                    );

                    if ($optionsArray['validate']) {
                        $this->inputFilterValidators[] = 
                            InputValidatorDefaults::createStringValidator($fieldName);
                    }
                }
            }
        }

        if ($withCSRFToken) {
            $csrfElement   = new CsrfElement('csrf');
            $csrfValidator = new CsrfValidator(array('name'=> 'csrf','salt' => 'ert34sfdajjKJU')); 
            $csrfElement->setCsrfValidator($csrfValidator);
            $this->add($csrfElement);
            $this->inputFilterValidators[] = 
                InputValidatorDefaults::createCsrfValidator('csrf', $csrfElement->getCsrfValidator() );
        }

    }

    public function getInputFilter()
    {
        if (! $this->filter) {

            $inputFilter = new InputFilter();

            foreach ($this->inputFilterValidators as $validator) {
                $inputFilter->add( $validator );
            }

            $this->filter = $inputFilter;
        }

        return $this->filter;
    }

    public function setInputFilter(InputFilterInterface $inputFilter)
    {
        throw new \Exception('LoginForm -> setInputFilter not allowed');
    }

    public function getFormDataDefaults( )
    {
        return $this->defaultValues; 
    }

}
