<?php

namespace Model\BusinessModel;

use Frontend\Controller\DomiciliationsController;
use Model\CommonContentsModel\Country;

use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Where;


class BusinessModel
{

    protected $sm;
    protected $dbAdapter;

    /**
     * @return mixed
     */
    public function getDbAdapter()
    {
        return $this->dbAdapter;
    }

    public function __construct ($serviceManager)
    {
        $this->sm = $serviceManager;
        $this->dbAdapter = $this->sm->get('Zend\Db\Adapter\Adapter');
    }

    public function executeStatement( $sqlQueryStr, $singleField = '' )
    {
        $statement = $this->getDbAdapter()->query( $sqlQueryStr );
        $resultSet = $statement->execute();
        $arrResult = array();

        if ($resultSet) {
            if ($singleField!='') {
                foreach ($resultSet as $row) {
                    $arrResult[] = $row[$singleField];
                }
            } else {
                foreach ($resultSet as $row) {
                    $arrResult[] = $row;
                }
            }
        }

        return $arrResult;
    }

    // -------------------------------------------------------------------
    // Tablas:

    protected $projectTable;
    public function getProjectTable()
    {
        if (!$this->projectTable) {
            $this->projectTable = $this->sm->get('Model\ProjectTable');
        }
        return $this->projectTable;
    }

    protected $docTable;
    public function getDocumentationTable()
    {
        if (!$this->docTable) {
            $this->docTable = $this->sm->get('Model\DocumentationTable');
        }
        return $this->docTable;
    }

    protected $configTextTable;

    public function getConfigTextTable()
    {
        if (!$this->configTextTable) {
            $this->configTextTable = $this->sm->get('Model\ConfigTextTable');
        }
        return $this->configTextTable;
    }

    protected $mensajeTable;
    public function getMensajeTable()
    {
        if (!$this->mensajeTable) {
            $this->mensajeTable = $this->sm->get('Model\MensajeTable');
        }
        return $this->mensajeTable;
    }

    protected $provinceTable;

    public function getProvinceTable()
    {
        if (!$this->provinceTable) {
            $this->provinceTable = $this->sm->get('Model\ProvinceTable');
        }
        return $this->provinceTable;
    }

    protected $noticiaTable;

    public function getNoticiaTable()
    {
        if (!$this->noticiaTable) {
            $this->noticiaTable = $this->sm->get('Model\NoticiaTable');
        }
        return $this->noticiaTable;
    }

    protected $usuariosTable;

    public function getUsuarioTable()
    {
        if (!$this->usuariosTable) {
            $this->usuariosTable = $this->sm->get('Model\UsuarioTable');
        }
        return $this->usuariosTable;
    }


    // PROJECTS //

    public function getProjects($offset=null, $limit=null, $status=''){

        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();
        $select->columns( array('id_project' => 'id_project','titulo' => 'name',  'id_alumn' => 'id_alumn', 'created_at' => 'created_at'));

        $select->from(Project::TABLE);

        $select->join(Usuario::TABLE, Usuario::TABLE.'.id_user = '.Project::TABLE.'.id_alumn', array('name', 'lastname', 'dni','email', 'course', 'id_tutor'));

        $where = new Where();

        if (!empty($status)) {
            $select->where(Project::TABLE.'.state = '.$status );
        }

        $select->where(Project::TABLE.'.deleted_at is null');

        $select->order(Project::TABLE.'.id_project DESC');

        if ($limit !=null){
            $select->offset($offset);
            $select->limit($limit);
        }
        $statement = $sql->prepareStatementForSqlObject($select);
        $results = $statement->execute();

        $projects = array();
        foreach($results as $result){
            $projects[$result['id_project']] = $result;
        }
        return $projects;
    }

    public function getProjectsByTutor($offset=null, $limit=null, $status=''){

        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();
        $select->from(Project::TABLE);
        $select->join(Usuario::TABLE, Usuario::TABLE.'.id_user = '.Project::TABLE.'.id_tutor', array('name', 'lastname', 'dni','email', 'course'));

        $where = new Where();

        if (!empty($status)) {
            $select->where(Project::TABLE.'.state = '.$status );
        }

        $select->order(Project::TABLE.'.id_project DESC');

        if ($limit !=null){
            $select->offset($offset);
            $select->limit($limit);
        }
        $statement = $sql->prepareStatementForSqlObject($select);
        $results = $statement->execute();

        $projects = array();
        foreach($results as $result){
            $projects[$result['id']] = $result;
        }
        return $projects;
    }

    // MENSAJES //


    public function getMensajesByUser($id){
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();
        $select->from(Mensaje::TABLE);
        $select->join(Usuario::TABLE, Mensaje::TABLE.'.id_remitente = '.Usuario::TABLE.'.id_user');


        $select->where("id_destinatario =".$id);
        $select->where(Mensaje::TABLE.".deleted_at is null");
        $select->order(Mensaje::TABLE.'.created_at DESC');

        $statement = $sql->prepareStatementForSqlObject($select);
        $results = $statement->execute();

        $resultSet = new ResultSet;
        $resultSet->initialize($results);

        return $resultSet->toArray();

    }

    public function getMensaje($id){
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();
        $select->from(Mensaje::TABLE);

        $select->where("id =".$id);

        $statement = $sql->prepareStatementForSqlObject($select);
        $results = $statement->execute();

        $resultSet = new ResultSet;
        $resultSet->initialize($results);

        return $resultSet->toArray();

    }

    public function getMensajesEnviadosByUser($id){
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();
        $select->from(Mensaje::TABLE);

       // $select->columns(array('total_amount' => new \Zend\Db\Sql\Expression('SUM(receipts.amount)')));

        $select->join(Usuario::TABLE, Mensaje::TABLE.'.id_destinatario = '.Usuario::TABLE.'.id_user');

        $select->where(Mensaje::TABLE.".id_remitente =".$id);
        $select->where(Mensaje::TABLE.".deleted_at_by_remitente is null");
        $select->order(Mensaje::TABLE.'.created_at DESC');

        $statement = $sql->prepareStatementForSqlObject($select);
        $results = $statement->execute();

        $resultSet = new ResultSet;
        $resultSet->initialize($results);

        return $resultSet->toArray();

    }


    public function getDestinatariosByUserRol($rol){
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();
        $select->from(Usuario::TABLE);

        if($rol == 3){
            $select->where("rol <> 3");
        }

        $select->where('deleted_at is null');

        $statement = $sql->prepareStatementForSqlObject($select);
        $results = $statement->execute();

        $resultSet = new ResultSet;
        $resultSet->initialize($results);

        return $resultSet->toArray();

    }



    // USUARIOS //



    public function getUserObById($id){

        $queryArr = array('id_user'=>$id);
        $rowSet = $this->tableGateway->select($queryArr);

        if (!empty($rowSet)) {
            return $rowSet->current();
        }

        return NULL;

    }

    public function getUserById($id){
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();
        $select->from(Usuario::TABLE);

        $select->where("id_user =".$id);

        $statement = $sql->prepareStatementForSqlObject($select);
        $results = $statement->execute();

        $resultSet = new ResultSet;
        $resultSet->initialize($results);

        return $resultSet->toArray();

    }

    // NOTICIAS

    public function getNoticias(){
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();
        $select->from(Noticia::TABLE);

        $select->where("deleted_at is null or deleted_at = '0000-00-00 00:00:00'");
        $select->order('created_at DESC');

        $statement = $sql->prepareStatementForSqlObject($select);
        $results = $statement->execute();

        $resultSet = new ResultSet;
        $resultSet->initialize($results);

        return $resultSet->toArray();

    }


    // ALUMNOS //

    public function getAlumnos(){
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();
        $select->from(Usuario::TABLE);

        $select->where("rol = 3");
        $select->where("deleted_at is null or deleted_at = '0000-00-00 00:00:00'");

        $statement = $sql->prepareStatementForSqlObject($select);
        $results = $statement->execute();

        $resultSet = new ResultSet;
        $resultSet->initialize($results);

        return $resultSet->toArray();

    }


    public function getUserByDni($dni){
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();
        $select->from(Usuario::TABLE);

        $select->where("dni = ".$dni);

        $statement = $sql->prepareStatementForSqlObject($select);
        $results = $statement->execute();

        $resultSet = new ResultSet;
        $resultSet->initialize($results);

        return $resultSet->toArray();

    }

    public function getAlumnosSinProyecto(){
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();
        $select->from(Usuario::TABLE);

        $select->where("rol = 3");
        $select->where("has_project = 0");
        $select->where("deleted_at is null");


        $statement = $sql->prepareStatementForSqlObject($select);
        $results = $statement->execute();

        $resultSet = new ResultSet;
        $resultSet->initialize($results);

        return $resultSet->toArray();
    }

    public function getAlumnosByIdTutor($idtutor){
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();
        $select->from(Usuario::TABLE);

        $select->where("rol = 3");
        $select->where("id_tutor = ".$idtutor);
        $select->where("deleted_at is null");


        $statement = $sql->prepareStatementForSqlObject($select);
        $results = $statement->execute();

        $resultSet = new ResultSet;
        $resultSet->initialize($results);

        return $resultSet->toArray();
    }



    public function getAlumno($alumnoId){
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();

        $select->from(Usuario::TABLE);

        $select->where("id_user = ".$alumnoId);

        $statement = $sql->prepareStatementForSqlObject($select);
        $results = $statement->execute();
        $result = $results->current();

        return $result;
    }

    public function getProjectDataByAlumn($alumnoId){
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();

        $select->from(Project::TABLE);

        $select->where("id_alumn = ".$alumnoId);

        $statement = $sql->prepareStatementForSqlObject($select);
        $results = $statement->execute();
        $result = $results->current();

        return $result;
    }

    public function getProjectObDataByAlumn($alumnoId){
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();

        $select->from(Project::TABLE);

        $select->where("id_alumn = ".$alumnoId);

        $statement = $sql->prepareStatementForSqlObject($select);
        $results = $statement->execute();
        $result = $results->current();


        return $result;
    }

    public function getDocumentationByAlumn($alumnoId) {
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();

        $select->from(Documentation::TABLE);

        $select->where("id_alumn = ".$alumnoId);
        $select->where("deleted_at is null");

        $statement = $sql->prepareStatementForSqlObject($select);
        $results = $statement->execute();

        $resultSet = new ResultSet;
        $resultSet->initialize($results);

        return $resultSet->toArray();
    }

    public function getDocumentationObByAlumn($alumnoId) {
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();

        $select->from(Documentation::TABLE);

        $select->where("id_alumn = ".$alumnoId);

        $statement = $sql->prepareStatementForSqlObject($select);
        $results = $statement->execute();

        return $results;
    }




    // PROFESORES //

    public function getProfesores(){
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();
        $select->from(Usuario::TABLE);

        $select->where("rol <> 3");
        $select->where("deleted_at is null");

        $statement = $sql->prepareStatementForSqlObject($select);
        $results = $statement->execute();

        $resultSet = new ResultSet;
        $resultSet->initialize($results);

        return $resultSet->toArray();
    }

    public function getIdTutorByAlumnId($id){
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();
        $select->from(Usuario::TABLE);

        $select->where("id_user = ".$id);

        $statement = $sql->prepareStatementForSqlObject($select);
        $results = $statement->execute();

        $resultSet = new ResultSet;
        $resultSet->initialize($results);
        $user = $resultSet->toArray();

        return $user[0]['id_tutor'];
    }

    public function getTutorByAlumId($id){
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();
        $select->from(Usuario::TABLE);

        $select->where("id_user = ".$id);

        $statement = $sql->prepareStatementForSqlObject($select);
        $results = $statement->execute();

        $resultSet = new ResultSet;
        $resultSet->initialize($results);
        $user = $resultSet->toArray();

        $tutor = $this->getUserById($user[0]);

        return $tutor;
    }


    // -------------------------------------------------------------------
    // FUNCIONES:


    public function getFullAddress($donation){
       $address = ucfirst(strtolower($donation['key']));
       $address .= ' '.$donation['address'];
       $address .= ', '.$donation['address_number'];
       $address .= ' '.$donation['address_complement'];
       $address .= ' '.$donation['zip'];
       $address .= ' '.$donation['city'];
       $address .= ', '.$donation['province_name'];
       return $address;

    }


    public function getUsers(){
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();

        $select->from(UserDonation::TABLE);

        $statement = $sql->prepareStatementForSqlObject($select);
        $results = $statement->execute();

        return $results;
    }

    public function getUsersQuery($formData, $limit=null, $offset=null){
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();

        $select->from(UserDonation::TABLE);

        if (!empty($formData['nif'])) {
            $select->where('nif like  "%'.$formData['nif'].'%"');
        }
        if((int)$formData['rol'] != 0){
            $select->where("cancelation is null ");

        }

        if ($limit != null) {
            $select->offset($offset)->limit($limit);
        }

        $statement = $sql->prepareStatementForSqlObject($select);
        $results = $statement->execute();

        return $results;
    }

    public function getUsersCount($formData){
        $users = $this->getUsersQuery($formData);
        $resul = iterator_to_array($users, true);

        return count($resul);
    }

    public function getUserData($userId){
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();

        $select->from(UserDonation::TABLE);
        $select->where(UserDonation::TABLE.'.id = '.$userId);

        $statement = $sql->prepareStatementForSqlObject($select);
        $results = $statement->execute();

        return $results->current();
    }

    public function getBusiness(){
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();
        $select->columns(array(new \Zend\Db\Sql\Expression('DISTINCT(business) as business')));
        $select->from(DonationNew::TABLE);
        $select->where(DonationNew::TABLE.'.business != ""');

        $statement = $sql->prepareStatementForSqlObject($select);
        $results = $statement->execute();

        return $results;

    }

}
