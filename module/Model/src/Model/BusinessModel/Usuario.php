<?php
/**
 * Created by PhpStorm.
 * User: bel
 * Date: 01/11/2018
 * Time: 19:26
 */

namespace Model\BusinessModel;


use Zend\Crypt\Password\Bcrypt;

class Usuario
{
    const TABLE = 'usuarios';

    const ROL_SUPERADMIN =  1;
    const ROL_PROFESOR   = 2;
    const ROL_ALUMNO = 3;


    protected $id_user;
    protected $name;
    protected $lastname;
    protected $dni;
    protected $email;
    protected $rol;
    protected $course;
    protected $password;
    protected $created_at;
    protected $updated_at;
    protected $deleted_at;
    protected $comments;
    protected $id_tutor;
    protected $has_documentation;
    protected $has_project;

    /**
     * @return mixed
     */
    public function getHasProject()
    {
        return $this->has_project;
    }

    /**
     * @param mixed $has_project
     */
    public function setHasProject($has_project)
    {
        $this->has_project = $has_project;
    }

    /**
     * @return mixed
     */
    public function getIdTutor()
    {
        return $this->id_tutor;
    }

    /**
     * @param mixed $id_tutor
     */
    public function setIdTutor($id_tutor)
    {
        $this->id_tutor = $id_tutor;
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
     * @return mixed
     */
    public function getIdUser()
    {
        return $this->id_user;
    }

    /**
     * @param mixed $id_user
     */
    public function setIdUser($id_user)
    {
        $this->id_user = $id_user;
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
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * @param mixed $lastname
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;
    }

    /**
     * @return mixed
     */
    public function getDni()
    {
        return $this->dni;
    }

    /**
     * @param mixed $dni
     */
    public function setDni($dni)
    {
        $this->dni = $dni;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return mixed
     */
    public function getRol()
    {
        return $this->rol;
    }

    /**
     * @param mixed $rol
     */
    public function setRol($rol)
    {
        $this->rol = $rol;
    }

    /**
     * @return mixed
     */
    public function getCourse()
    {
        return $this->course;
    }

    /**
     * @param mixed $course
     */
    public function setCourse($course)
    {
        $this->course = $course;
    }

    /**
     * @return bool|string
     */

    public function getPassword()
    {
        return  $this->password;
    }

    public function setPassword ( $password )
    {

        $this->password = $password;


    }

    public function checkPasswd( $password )
    {

        if( base64_encode($password) == $this->password){
            return true;
        } else {
            return false;
        }
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

    public function __construct()
    {
        $this->exchangeArray(array());
    }


    public function exchangeArray($data)
    {
        $this->setIdUser( (!empty($data['id_user'])) ? $data['id_user'] : 0 );
        $this->setName( (!empty($data['name'])) ? $data['name'] : '' );
        $this->setLastname( (!empty($data['lastname'])) ? $data['lastname'] : '' );
        $this->setDni( (!empty($data['dni'])) ? $data['dni'] : '' );
        $this->setEmail( (!empty($data['email'])) ? $data['email'] : '' );
        $this->setRol( (!empty($data['rol'])) ? $data['rol'] : 0 );
        $this->setCourse( (!empty($data['course'])) ? $data['course'] : 0 );
        $this->setPassword( (!empty($data['password'])) ? $data['password'] : '' );
        $this->setCreatedAt( (!empty($data['created_at'])) ? $data['created_at'] : '' );
        $this->setUpdatedAt( (!empty($data['updated_at'])) ? $data['updated_at'] : null );
        $this->setDeletedAt( (!empty($data['deletad_at'])) ? $data['deleted_at'] : null );
        $this->setComments( (!empty($data['comments'])) ? $data['comments'] : '' );
        $this->setIdTutor( (!empty($data['id_tutor'])) ? $data['id_tutor'] : 0 );
        $this->setHasDocumentation( (!empty($data['has_documentation'])) ? $data['has_documentation'] : 0 );
        $this->setHasProject( (!empty($data['has_project'])) ? $data['has_project'] : 0 );

    }

    public function getDataArray( $includeId = false )
    {
        $data = array();
        if ($includeId) $data['id_user'] = $this->getIdUser();
        $data['name'] = $this->getName();
        $data['lastname'] = $this->getLastname();
        $data['dni'] = $this->getDni();
        $data['email'] = $this->getEmail();
        $data['rol'] = $this->getRol();
        $data['course'] = $this->getCourse();
        $data['password'] = $this->getPassword();
        $data['created_at'] = $this->getCreatedAt();
        $data['updated_at'] = $this->getUpdatedAt();
        $data['deleted_at'] = $this->getDeletedAt();
        $data['comments'] = $this->getComments();
        $data['id_tutor'] = $this->getIdTutor();
        $data['has_documentation'] = $this->getHasDocumentation();
        $data['has_project'] = $this->getHasProject();

        return $data;
    }

}