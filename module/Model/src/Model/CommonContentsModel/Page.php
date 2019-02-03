<?php

namespace Model\CommonContentsModel;

class Page
{
    const TABLE = 'pages';

    protected $id;
    protected $name;
    protected $type;


    public function __construct()
    {
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
        $this->id = (int) $value;
    
        return $this;
    } 

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $value
     */
    public function setName($value)
    {
        $this->name = $value;

        return $this;
    }

    /**
     * @return int
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param int $value
     */
    public function setType($value)
    {
        $this->type = $value;

        return $this;

    }


    public function exchangeArray($data)
    {
        $this->setId( (!empty($data['id'])) ? $data['id'] : 0 );
        $this->setName( (!empty($data['name'])) ? $data['name'] : 0 );
        $this->setType( (!empty($data['type'])) ? $data['type'] : 0 );
    }

    public function getDataArray( $includeId = false )
    {
        $data = array();
        if ($includeId) $data['id'] = $this->getId();
        $data['name'] = $this->getName();
        $data['type'] = $this->getType();
        return $data;
    }
    
}
