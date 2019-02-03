<?php

namespace Model\Form\Element\Validator;

use Zend\Validator\EmailAddress as EmailAddressValidator;
use Zend\Validator\ValidatorInterface;
use Zend\Validator\HostName;

class EmailListValidator implements ValidatorInterface
{
    
    public function isValid($value) {   

        $validator = new EmailAddressValidator(array(
                                    'messages' => array(
                                        EmailAddressValidator::INVALID_HOSTNAME => 'Nombre de Host no válido'
                                    ) ) );
        $result = TRUE;
        
        foreach (explode(",", $value) as $email) {
            $result = $result && $validator->isValid($email);
            if (!$result) break;
        }

        return $result;
    }

    public function getMessages() {
        return array();
    }


}