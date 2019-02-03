<?php

namespace Model\Form\Element;

use Model\Form\Element\Validator\NIFValidator;

use Zend\Form\Element;
use Zend\InputFilter\InputProviderInterface;

class NIF extends Element implements InputProviderInterface
{
    /**
     * @var ValidatorInterface
     */
    protected $validator;

    public function __construct($name = null, $options = [])
    {
        parent::__construct($name,$options);
    }


    /**
    * Get a validator if none has been set.
    *
    * @return ValidatorInterface
    */
    public function getValidator()
    {
        if (null === $this->validator) {
            $this->validator = new NIFValidator();
        }

        return $this->validator;
    }

    /**
     * Sets the validator to use for this element
     *
     * @param  ValidatorInterface $validator
     * @return Application\Form\Element\Phone
     */
    public function setValidator(ValidatorInterface $validator)
    {
        $this->validator = $validator;
        return $this;
    }

    /**
     * Provide default input rules for this element
     *
     * @return array
     */
    public function getInputSpecification( $required = false )
    {

        $validatorsArr = array(
            $this->getValidator(),
        );

        if ($required) {
            $validatorsArr[] = array (
                'name' => 'NotEmpty',
                'options' => array (
                    'messages' => array (
                        'isEmpty' => 'El campo es necesario'
                    )
                )
            );
        }

        return array(
            'name' => $this->getName(),
            'required' => $required,
            'filters' => array(
                array('name' => 'Zend\Filter\StringTrim'),
            ),
            'validators' => $validatorsArr,
        );

    }
}