<?php

namespace Model\CommonContentsModel;

class UserRole
{
    const TABLE = 'user_roles';
   
    protected $id; 
    protected $role_name;


    public function __construct()
    {
        $this->exchangeArray( array() );
    }

    /**
        * @return int
    */
    public function getId ()
    {
        return $this->id;
    }
    
    /**
        * @param int $value
    */
    public function setId ( $value )
    {
        $this->id = $value;
    
        return $this;
    }

    /**
     * @return string
     */
    public function getRoleName()
    {
        return $this->role_name;
    }

    /**
     * @param string $value maxLength: 45
     */
    public function setRoleName($value)
    {
        $this->role_name = substr( (string) $value, 0, 45);

        return $this;
    }



    public function exchangeArray($data)
    {
        $this->setId( (!empty($data['id'])) ? $data['id'] : 0 );
        $this->setRoleName( (!empty($data['role_name'])) ? $data['role_name'] : 0 );
    }

    public function getDataArray( $includeId = false )
    {
        $data = array();
        if ($includeId) $data['id'] = $this->getId();
        $data['role_name'] = $this->getRoleName();
        return $data;
    }
    
}
