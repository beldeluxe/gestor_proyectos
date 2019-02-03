<?php

namespace Model\BusinessModel;

use Model\DataModel\DateTime;
use Zend\Crypt\Password\Bcrypt;

class Project
{
    const TABLE = 'projects';


    protected $id_project;
    protected $name;
    protected $id_alumn;
    protected $created_at;
    protected $updated_at;
    protected $deleted_at;
    protected $comments;
    protected $has_documentation;

    /**
     * @return mixed
     */
    public function getIdProject()
    {
        return $this->id_project;
    }

    /**
     * @return mixed
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * @param mixed $comments
     */
    public function setComments($comments)
    {
        $this->comments = $comments;
    }

    /**
     * @return mixed
     */
    public function getHasDocumentation()
    {
        return $this->has_documentation;
    }

    /**
     * @param mixed $has_documentation
     */
    public function setHasDocumentation($has_documentation)
    {
        $this->has_documentation = $has_documentation;
    }

    /**
     * @param mixed $id_project
     */
    public function setIdProject($id_project)
    {
        $this->id_project = $id_project;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getIdAlumn()
    {
        return $this->id_alumn;
    }

    /**
     * @param mixed $id_alumn
     */
    public function setIdAlumn($id_alumn)
    {
        $this->id_alumn = $id_alumn;
    }

    /**
     * @return mixed
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * @param mixed $created_at
     */
    public function setCreatedAt($created_at)
    {
        $this->created_at = $created_at;
    }

    /**
     * @return mixed
     */
    public function getUpdatedAt()
    {
        return $this->updated_at;
    }

    /**
     * @param mixed $updated_at
     */
    public function setUpdatedAt($updated_at)
    {
        $this->updated_at = $updated_at;
    }

    /**
     * @return mixed
     */
    public function getDeletedAt()
    {
        return $this->deleted_at;
    }

    /**
     * @param mixed $deleted_at
     */
    public function setDeletedAt($deleted_at)
    {
        $this->deleted_at = $deleted_at;
    }



    public function exchangeArray($data)
    {
        $this->setIdProject( (!empty($data['id_project'])) ? $data['id_project'] : 0 );
        $this->setName( (!empty($data['name'])) ? $data['name'] : '' );
        $this->setIdAlumn( (!empty($data['id_alumn'])) ? $data['id_alumn'] : 0 );
        $this->setCreatedAt( (!empty($data['created_at'])) ? $data['created_at'] : '' );
        $this->setUpdatedAt( (!empty($data['updated_at'])) ? $data['updated_at'] : '' );
        $this->setDeletedAt( (!empty($data['deleted_at'])) ? $data['deleted_at'] : null );
        $this->setComments( (!empty($data['comments'])) ? $data['comments'] : '' );
        $this->setHasDocumentation( (!empty($data['has_documentation'])) ? $data['has_documentation'] : '' );

    }

    public function getDataArray( $includeId = false )
    {
        $data = array();
        if ($includeId) $data['id_project'] = $this->getIdProject();
        $data['name'] = $this->getName();
        $data['id_alumn'] = $this->getIdAlumn();
        $data['created_at'] = $this->getCreatedAt();
        $data['updated_at'] = $this->getUpdatedAt();
        $data['deleted_at'] = $this->getDeletedAt();
        $data['comments'] = $this->getComments();
        $data['has_documentation'] = $this->getHasDocumentation();

        return $data;
    }

}
