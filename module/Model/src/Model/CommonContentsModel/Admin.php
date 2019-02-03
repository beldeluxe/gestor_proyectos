<?php

namespace Model\CommonContentsModel;

use Zend\Crypt\Password\Bcrypt;

class Admin
{
    const TABLE = 'admins';

    const ACTIVE_ADMIN   = 'ACTIVE';
    const INACTIVE_ADMIN = 'INACTIVE';
    const BANNED_ADMIN   = 'BANNED';

    const ADMIN_DEFAULT_ROLE =  0;
    const ADMIN_ROOT_ROLE    = 10;
    const ADMON_ROLE = 1;
    const GESTOR_ROLE = 2;

    protected $id;
    protected $name;
    protected $surname;
    protected $email;
    protected $password;
    protected $status;
    protected $role_type;
    protected $password_modif_date;


    public function __construct()
    {
        $this->exchangeArray(array());
    }    

    /**
     * Id
     * @return int
     */
    public function getId ()
    {
        return (int) $this->id;
    }
    /**
     * @param int 
     */
    public function setId ( $value )
    {
        $this->id = (int) $value;
    
        return $this;
    } 

    /**
     * Name
     * @return string
     */
    public function getName ()
    {
        return (string) $this->name;
    }
    /**
     * @param string maxlength: 45
     */
    public function setName ( $value )
    {
        $this->name = substr((string) $value, 0, 45);
    
        return $this;
    } 

    /**
     * Surname
     * @return string
     */
    public function getSurname ()
    {
        return (string) $this->surname;
    }
    /**
     * @param string maxlength: 64
     */
    public function setSurname ( $value )
    {
        $this->surname = substr((string) $value, 0, 64);
    
        return $this;
    } 

    /**
     * Email
     * @return string
     */
    public function getEmail ()
    {
        return (string) $this->email;
    }
    /**
     * @param string maxlength: 64
     */
    public function setEmail ( $value )
    {
        $this->email = $value;
    
        return $this;
    } 

    /**
     * @param string maxlength: 64
     */
    public function getPassword()
    {
        return (string) $this->password;
    }

    public function setPassword ( $value, $encriptar=FALSE )
    {
        $securePass = $value;
        if ($encriptar) {
            $bcrypt = new Bcrypt();
            $securePass = $bcrypt->create($value);
        }

        $this->password = $securePass;

        return $this;
    } 
    
    public function checkPasswd( $password )
    {
        $bcrypt = new Bcrypt();

        return ($bcrypt->verify($password, $this->password));
    }

    /**
     * @return bool
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param bool $value
     */
    public function setStatus ( $value )
    {
        $this->status = $value;
        return $this;
    }

    /**
     * @return int
     */
    public function getRoleType()
    {
        return (int)$this->role_type;
    }

    /**
     * @param int $role
     */
    public function setRoleType($roleType)
    {
        $this->role_type = $roleType;
    }

    /**
     * @return mixed
     */
    public function getPasswordModifDate()
    {
        return $this->password_modif_date;
    }

    /**
     * @param mixed $password_modif_date
     */
    public function setPasswordModifDate($password_modif_date)
    {
        $this->password_modif_date = $password_modif_date;
    }

    public function getRoleTypeName(){
        $role = $this->getRoleType();
        switch($role){
            case Admin::ADMIN_DEFAULT_ROLE:
                $roleName = 'ADMIN';
        }
        return $roleName;
    }

    public function exchangeArray($data)
    {
        $this->setId( (!empty($data['id'])) ? $data['id'] : 0 );
        $this->setName( (!empty($data['name'])) ? $data['name'] : '' );
        $this->setSurname( (!empty($data['surname'])) ? $data['surname'] : '' );
        $this->setEmail( (!empty($data['email'])) ? $data['email'] : '' );
        $this->setPassword( (!empty($data['password'])) ? $data['password'] : 0 );
        $this->setStatus( (!empty($data['status'])) ? $data['status'] : Admin::ACTIVE_ADMIN );
        $this->setRoleType( (!empty($data['role_type'])) ? $data['role_type'] : 0 );
        $this->setPasswordModifDate( (!empty($data['password_modif_date'])) ? $data['password_modif_date'] : 0 );


    }

    public function getDataArray( $includeId = false )
    {
        $data = array();
        if ($includeId) $data['id'] = $this->getId();
        $data['name'] = $this->getName();
        $data['surname'] = $this->getSurname();
        $data['email'] = $this->getEmail();
        $data['status'] = $this->getStatus();
        $data['role_type'] = $this->getRoleType();
        $data['password_modif_date'] = $this->getPasswordModifDate();
        $data['password'] = $this->getPassword();

        return $data;
    }



}
