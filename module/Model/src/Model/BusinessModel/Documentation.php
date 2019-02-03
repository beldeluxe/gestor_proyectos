<?php
/**
 * Created by PhpStorm.
 * User: bel
 * Date: 24/11/2018
 * Time: 12:27
 */

namespace Model\BusinessModel;


class Documentation
{

    const TABLE = 'documentation';


    protected $id_documentation;
    protected $filename;
    protected $description;
    protected $id_alumn;
    protected $created_at;
    protected $deleted_at;

    /**
     * @return mixed
     */
    public function getIdDocumentation()
    {
        return $this->id_documentation;
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

    /**
     * @param mixed $id_documentation
     */
    public function setIdDocumentation($id_documentation)
    {
        $this->id_documentation = $id_documentation;
    }

    /**
     * @return mixed
     */
    public function getFilename()
    {
        return $this->filename;
    }

    /**
     * @param mixed $filename
     */
    public function setFilename($filename)
    {
        $this->filename = $filename;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
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


    public function exchangeArray($data)
    {
        $this->setIdDocumentation( (!empty($data['id_documentation'])) ? $data['id_documentation'] : 0 );
        $this->setFilename( (!empty($data['filename'])) ? $data['filename'] : '' );
        $this->setDescription( (!empty($data['description'])) ? $data['description'] : '' );
        $this->setIdAlumn( (!empty($data['id_alumn'])) ? $data['id_alumn'] : 0 );
        $this->setCreatedAt( (!empty($data['created_at'])) ? $data['created_at'] : null );
        $this->setDeletedAt( (!empty($data['deleted_at'])) ? $data['deleted_at'] : null );

    }

    public function getDataArray( $includeId = false )
    {
        $data = array();
        if ($includeId) $data['id_documentation'] = $this->getIdDocumentation();
        $data['filename'] = $this->getFilename();
        $data['description'] = $this->getDescription();
        $data['id_alumn'] = $this->getIdAlumn();
        $data['created_at'] = $this->getCreatedAt();
        $data['deleted_at'] = $this->getDeletedAt();

        return $data;
    }

}