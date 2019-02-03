<?php

namespace Model\CommonContentsModel;

class PageRole
{
    const TABLE = 'page_role';
   
    protected $id; 
    protected $user_role_id;
    protected $page_id;


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
     * @return int
     */
    public function getUserRoleId()
    {
        return $this->user_role_id;
    }

    /**
     * @param int $value
     */
    public function setUserRoleId($value)
    {
        $this->user_role_id = $value;
    }

    /**
     * @return int
     */
    public function getPageId()
    {
        return $this->page_id;
    }

    /**
     * @param int $value
     */
    public function setPageId($value)
    {
        $this->page_id = $value;
    }


    public function exchangeArray($data)
    {
        $this->setId( (!empty($data['id'])) ? $data['id'] : 0 );
        $this->setUserRoleId( (!empty($data['user_role_id'])) ? $data['user_role_id'] : 0 );
        $this->setPageId( (!empty($data['page_id'])) ? $data['page_id'] : 0 );
    }


    public function getDataArray( $includeId = false )
    {
        $data = array();
        if ($includeId) $data['id'] = $this->getId();
        $data['user_role_id'] = $this->getUserRoleId();
        $data['page_id'] = $this->getPageId();
        return $data;
    }
    
}
