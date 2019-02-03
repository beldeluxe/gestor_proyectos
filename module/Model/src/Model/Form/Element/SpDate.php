<?php

namespace Model\Form\Element;

use Zend\Form\Element;
use Zend\InputFilter\InputProviderInterface;
use Zend\Validator\Regex as RegexValidator;

class SpDate extends Element implements InputProviderInterface
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
            $validator = new RegexValidator("/^(0[1-9]|[1-2][0-9]|3[0-1])(\/|-)(0[1-9]|1[0-2])(\/|-)[0-9]{4}$/");
            $validator->setMessage('Fecha no válida (req. dd-mm-aaaa)',RegexValidator::NOT_MATCH);
            $this->validator = $validator;
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
     * Attaches a phone number validator.
     *
     * @return array
     */
    public function getInputSpecification( $required = true )
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