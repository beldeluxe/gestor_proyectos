<?php
/**
 * Created by PhpStorm.
 * User: bel
 * Date: 30/11/2018
 * Time: 19:24
 */

namespace Model\BusinessModel;


class Mensaje
{

    const TABLE = 'mensajes';


    protected $id;
    protected $asunto;
    protected $content;
    protected $id_remitente;
    protected $id_destinatario;
    protected $estado;
    protected $created_at;
    protected $deleted_at;
    protected $deleted_at_by_remitente;

    /**
     * @return mixed
     */
    public function getDeletedAtByRemitente()
    {
        return $this->deleted_at_by_remitente;
    }

    /**
     * @param mixed $deleted_at_by_remitente
     */
    public function setDeletedAtByRemitente($deleted_at_by_remitente)
    {
        $this->deleted_at_by_remitente = $deleted_at_by_remitente;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getAsunto()
    {
        return $this->asunto;
    }

    /**
     * @param mixed $asunto
     */
    public function setAsunto($asunto)
    {
        $this->asunto = $asunto;
    }

    /**
     * @return mixed
     */
    public function getIdRemitente()
    {
        return $this->id_remitente;
    }

    /**
     * @param mixed $id_remitente
     */
    public function setIdRemitente($id_remitente)
    {
        $this->id_remitente = $id_remitente;
    }

    /**
     * @return mixed
     */
    public function getIdDestinatario()
    {
        return $this->id_destinatario;
    }

    /**
     * @param mixed $id_destinatario
     */
    public function setIdDestinatario($id_destinatario)
    {
        $this->id_destinatario = $id_destinatario;
    }

    /**
     * @return mixed
     */
    public function getEstado()
    {
        return $this->estado;
    }

    /**
     * @param mixed $estado
     */
    public function setEstado($estado)
    {
        $this->estado = $estado;
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
        $this->setAsunto( (!empty($data['asunto'])) ? $data['asunto'] : '' );
        $this->setContent( (!empty($data['comments'])) ? $data['comments'] : '' );
        $this->setIdRemitente( (!empty($data['id_remitente'])) ? $data['id_remitente'] : 0 );
        $this->setIdDestinatario( (!empty($data['id_destinatario'])) ? $data['id_destinatario'] : '' );
        $this->setEstado( (!empty($data['estado'])) ? $data['estado'] : 0 );
        $this->setCreatedAt( (!empty($data['created_at'])) ? $data['created_at'] : '' );
        $this->setDeletedAt( (!empty($data['deletad_at'])) ? $data['deleted_at'] : null );
        $this->setDeletedAtByRemitente( (!empty($data['deletad_at_by_remitente'])) ? $data['deleted_at_by_remitente'] : null );
    }


    public function getDataArray( $includeId = false )
    {
        $data = array();
        if ($includeId) $data['id'] = $this->getId();
        $data['asunto'] = $this->getAsunto();
        $data['comments'] = $this->getContent();
        $data['id_remitente'] = $this->getIdRemitente();
        $data['id_destinatario'] = $this->getIdDestinatario();
        $data['estado'] = $this->getEstado();
        $data['created_at'] = $this->getCreatedAt();
        $data['deleted_at'] = $this->getDeletedAt();
        $data['deleted_at_by_remitente'] = $this->getDeletedAtByRemitente();

        return $data;
    }

}