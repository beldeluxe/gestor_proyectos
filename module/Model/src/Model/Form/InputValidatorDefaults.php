<?php

namespace Model\Form;

use Zend\InputFilter\Factory as InputFactory;

use Zend\Validator\HostName;
use Zend\Validator\Date;
use Zend\Validator\EmailAddress;
use Zend\Validator\Identical;
use Zend\Validator\Digits;
use Zend\Validator\StringLength;
use Zend\Validator\Regex;
use Zend\Validator\Db\NoRecordExists;

class InputValidatorDefaults
{

    public static function createDateValidator ( $name, $notEmpty = true )
    {
        $factory = new InputFactory();

        $validatorsArr = array(
            array (
                'name' => 'Date',
                'options' => array (
                    'messages' => array (
                            Date::INVALID => 'Fecha no válida',
                            Date::INVALID_DATE => 'Fecha no válida',
                            Date::FALSEFORMAT => 'Fecha no válida'
                        )
                )
            )
        );

        if ($notEmpty) {
            $validatorsArr[] = array (
                'name' => 'NotEmpty',
                'options' => array (
                    'messages' => array (
                        'isEmpty' => 'El campo es necesario'
                    )
                )
            );
        }

        return $factory->createInput ( array (
                'name' => $name,
                'filters' => array (
                    array ( 'name' => 'StripTags'  ),
                    array ( 'name' => 'StringTrim' )
                ),
                'validators' => $validatorsArr
        )   );

    }

    public static function createEmailValidator ( $name, $notEmpty = true )
    {
        $factory = new InputFactory();

        $validatorsArr = array(
            array (
                'name' => 'EmailAddress',
                'options' => array (
                    'messages' => array (
                            EmailAddress::INVALID_HOSTNAME => 'Nombre de Host no válido',
                            HostName::INVALID_HOSTNAME => 'Nombre de Host no válido',
                            HostName::LOCAL_NAME_NOT_ALLOWED => 'El nombre de Host parece de una red local',
                        )
                )
            )
        );

        if ($notEmpty) {
            $validatorsArr[] = array (
                'name' => 'NotEmpty',
                'options' => array (
                    'messages' => array (
                        'isEmpty' => 'El campo es necesario'
                    )
                )
            );
        }

        return $factory->createInput ( array (
                'name' => $name,
                'filters' => array (
                    array ( 'name' => 'StripTags'  ),
                    array ( 'name' => 'StringTrim' )
                ),
                'validators' => $validatorsArr
        )   );

    }

    public static function createStringValidator ( $name, $notEmpty = true )
    {
        $factory = new InputFactory();

        $validatorsArr = array();

        if ($notEmpty) {
            $validatorsArr[] = array (
                'name' => 'NotEmpty',
                'options' => array (
                    'messages' => array (
                        'isEmpty' => 'El campo es necesario'
                    )
                )
            );
        }

        return $factory->createInput ( array (
                'name' => $name,
                'filters' => array (
                    array ( 'name' => 'StripTags'  ),
                    array ( 'name' => 'StringTrim' )
                ),
                'required' => $notEmpty,
                'validators' => $validatorsArr
        )   );

    }

    public static function createIdenticalValidator ( $name1, $name2 )
    {
        $factory = new InputFactory();

        $validatorsArr = array();

        $validatorsArr[] = array (
            'name'    => 'Identical',
            'options' => array(
                'messages' => array (
                    Identical::NOT_SAME => 'Las contraseñas no coinciden'
                ),
                'token' => $name1,
            ),
        );

        return $factory->createInput ( array (
                'name' => $name2,
                'validators' => $validatorsArr
            )   
        );

    }

    public static function createCsrfValidator ( $name, $validator )
    {
        $factory = new InputFactory();

        return $factory->createInput( array(
                'name'     => $name,
                'required' => true,
                'validators' => array( $validator )
        ) );

    }

    public static function setGenericValidator ( $name, $validator )
    {
        $factory = new InputFactory();

        return $factory->createInput( array(
                'name'     => $name,
                'required' => true,
                'validators' => array( $validator )
        ) );

    }

    public static function createStringLengthValidator ($name, $min=null, $max=null){

        $validator = new StringLength();
        $arrayOptions =  array (
            'messages' => array (
                StringLength::TOO_LONG => 'Debe tener un máximo de ' .$max. ' caracteres',
                StringLength::TOO_SHORT => 'Debe tener un mínimo de ' . $min .' caracteres',
            )
        );
        $validator->setOptions($arrayOptions);

        if($min!= null)
            $validator->setMin($min);
        if ($max != null)
            $validator->setMax($max);

        $factory = new InputFactory();

        return $factory->createInput( array(
            'name'     => $name,
            'validators' => array( $validator )
        ));

    }

    public static function createIntValidator ( $name, $notEmpty = true )
    {
        $factory = new InputFactory();

        $validatorsArr = array();

        if ($notEmpty) {
            $validatorsArr[] = array (
                'name' => 'Digits',
//                'options' => array (
//                    'messages' => array (
//                        'isEmpty' => 'El campo es necesario'
//                    )
//                )
            );
        }

        return $factory->createInput ( array (
            'name' => $name,
            'filters' => array (
                array ( 'name' => 'StripTags'  ),
                array ( 'name' => 'StringTrim' )
            ),
            'required' => $notEmpty,
            'validators' => $validatorsArr
        )   );

    }

    public static function createDigitValidator ($name){

        $validator = new Digits();
        $arrayOptions =  array (
            'messages' => array (
                Digits::NOT_DIGITS => 'Deben ser solo números',
                Digits::INVALID => 'Formato inválido',
                Digits::STRING_EMPTY => 'El campo no puede estar vacío',

            )
        );
        $validator->setOptions($arrayOptions);

        $factory = new InputFactory();

        return $factory->createInput( array(
            'name'     => $name,
            'validators' => array( $validator )
        ));

    }

    public static function createFloatValidator ($name){

        $validator = new IsFloat();
        $arrayOptions =  array (
            'messages' => array (
                IsFloat::NOT_FLOAT => 'Deben ser solo números',
                IsFloat::INVALID => 'Formato inválido',

            )
        );
        $validator->setOptions($arrayOptions);

        $factory = new InputFactory();

        return $factory->createInput( array(
            'name'     => $name,
            'validators' => array( $validator )
        ));

    }

}
