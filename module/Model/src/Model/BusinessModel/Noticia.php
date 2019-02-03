<?php
/**
 * Created by PhpStorm.
 * User: bel
 * Date: 27/11/2018
 * Time: 11:39
 */

namespace Model\BusinessModel;


class Noticia
{
    const TABLE = 'noticias';


    protected $id;
    protected $title;
    protected $content;
    protected $excerpt;
    protected $media;
    protected $id_autor;
    protected $created_at;
    protected $deleted_at;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return mixed
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param mixed $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

    /**
     * @return mixed
     */
    public function getExcerpt()
    {
        return $this->excerpt;
    }

    /**
     * @param mixed $excerpt
     */
    public function setExcerpt($excerpt)
    {
        $this->excerpt = $excerpt;
    }

    /**
     * @return mixed
     */
    public function getMedia()
    {
        return $this->media;
    }

    /**
     * @param mixed $media
     */
    public function setMedia($media)
    {
        $this->media = $media;
    }

    /**
     * @return mixed
     */
    public function getIdAutor()
    {
        return $this->id_autor;
    }

    /**
     * @param mixed $id_autor
     */
    public function setIdAutor($id_autor)
    {
        $this->id_autor = $id_autor;
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
     * Noticia constructor.
     */
    public function __construct()
    {
        $this->exchangeArray(array());
    }

    public function exchangeArray($data)
    {
        $this->setId( (!empty($data['id'])) ? $data['id'] : 0 );
        $this->setTitle( (!empty($data['title'])) ? $data['title'] : '' );
        $this->setContent( (!empty($data['comments'])) ? $data['comments'] : '' );
        $this->setExcerpt( (!empty($data['excerpt'])) ? $data['excerpt'] : '' );
        $this->setMedia( (!empty($data['mediafile'])) ? $data['mediafile'] : '' );
        $this->setIdAutor( (!empty($data['id_autor'])) ? $data['id_autor'] : 0 );
        $this->setCreatedAt( (!empty($data['created_at'])) ? $data['created_at'] : '' );
        $this->setDeletedAt( (!empty($data['deletad_at'])) ? $data['deleted_at'] : null );
    }


    public function getDataArray( $includeId = false )
    {
        $data = array();
        if ($includeId) $data['id'] = $this->getId();
        $data['title'] = $this->getTitle();
        $data['comments'] = $this->getContent();
        $data['excerpt'] = $this->getExcerpt();
        $data['mediafile'] = $this->getMedia();
        $data['id_autor'] = $this->getIdAutor();
        $data['created_at'] = $this->getCreatedAt();
        $data['deleted_at'] = $this->getDeletedAt();

        return $data;
    }

}