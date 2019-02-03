<?php

namespace Model\Form\Element\Validator;

use Zend\Validator\ValidatorInterface;

class NIFValidator implements ValidatorInterface
{
    public $validationResultData;
    
    public function isValid($value) {   
        
        $str = trim(strtoupper($value));

        $body = preg_replace('/^[XYZ]/i', '', $str, -1, $sust);
        
        $initialChar  = '';
        $valid        = false;
        $type         = 'NIF';

        if ($sust) { 
            $type   = 'NIE';
        } else {
            $body = preg_replace('/^[KLM]/i', '', $str, -1, $sust);
            if ($sust) { 
                $type   = 'NIF';
            } else {
                $body = preg_replace('/^[ABCDEFGHJNPQRSUVW]/i', '', $str, -1, $sust);
                if ($sust) { 
                    $type   = 'CIF';
                }
            }
        }

        if ($sust) {
            $initialChar = $str[0];
        }
        
        // Relleno a ceros por la izquierda y me quedo con 
        // 9 caracteres
        $body = "00000000" . $body;
        $body = substr($body, -9);
        $dc     = substr($body, 8, 1);

        // El formato es de un NIF o un NIE
        if (preg_match('/[0-9]{8}[A-Z]/i', $body))
        {
            if ( $type=='NIF' ) {
                //Dígito control
                $stack = 'TRWAGMYFPDXBNJZSQVHLCKE';
                $pos   = ((int) substr($body, 0, 8)) % 23;
                $valid = ( $dc == substr($stack, $pos, 1) );
            } else {
                $valid = true;
            }
        }

        $this->validationResultData = array('valid'=>$valid,'type'=>$type,'initialChar'=>$initialChar,'body'=>$body,'dc'=>$dc);

        return $valid;
    }

    public function getMessages() {
        return array (
            'isEmpty' => 'NIF/NIE no válido'
        );
    }


}