<?php

namespace Model\CommonContentsModel;

class AdminLoginRequest
{
    const TABLE = 'admin_log_in_requests';
    
    protected $id;
    protected $admin_id;
    protected $login_time; 
    protected $logout_time; 
    protected $status; 
    protected $attempt_n;

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
     * AdminId
     * @return int
     */
    public function getAdminId ()
    {
        return (int) $this->admin_id;
    }
    /**
     * @param int 
     */
    public function setAdminId ( $value )
    {
        $this->admin_id = (int) $value;
    
        return $this;
    } 

    /**
     * LoginTime
     * @return string
     */
    public function getLoginTime ()
    {
        return (string) $this->login_time;
    }
    /**
     * @param string 
     */
    public function setLoginTime ( $value )
    {
        $this->login_time = (string) $value;
    
        return $this;
    } 

    /**
     * LogoutTime
     * @return string
     */
    public function getLogoutTime ()
    {
        return (string) $this->logout_time;
    }
    /**
     * @param string 
     */
    public function setLogoutTime ( $value )
    {
        $this->logout_time = (string) $value;
    
        return $this;
    } 

    /**
     * Status
     * @return string
     */
    public function getStatus ()
    {
        return (string) $this->status;
    }
    /**
     * @param string enum: 'ATMP','IN','OUT' default: 'ATMP'
     */
    public function setStatus ( $value )
    {
        if ( !in_array( $value, array( 'ATMP','IN','OUT' ) ) ) {
            $value = 'ATMP';
        }
    
        $this->status = $value;
    
        return $this;
    } 

    /**
     * AttemptN
     * @return int
     */
    public function getAttemptN ()
    {
        return (int) $this->attempt_n;
    }
    /**
     * @param int 
     */
    public function setAttemptN ( $value )
    {
        $this->attempt_n = (int) $value;
    
        return $this;
    } 

    public function exchangeArray($data)
    {
        $this->setId( (!empty($data['id'])) ? $data['id'] : 0 );
        $this->setAdminId( (!empty($data['admin_id'])) ? $data['admin_id'] : 0 );
        $this->setLoginTime( (!empty($data['login_time'])) ? $data['login_time'] : 0 );
        $this->setLogoutTime( (!empty($data['logout_time'])) ? $data['logout_time'] : 0 );
        $this->setStatus( (!empty($data['status'])) ? $data['status'] : 0 );
        $this->setAttemptN( (!empty($data['attempt_n'])) ? $data['attempt_n'] : 0 );
    }

    public function getDataArray( $includeId = false )
    {
        $data = array();
        if ($includeId) $data['id'] = $this->getId();
        $data['admin_id'] = $this->getAdminId();
        $data['login_time'] = $this->getLoginTime();
        $data['logout_time'] = $this->getLogoutTime();
        $data['status'] = $this->getStatus();
        $data['attempt_n'] = $this->getAttemptN();
        return $data;
    }
    
}
