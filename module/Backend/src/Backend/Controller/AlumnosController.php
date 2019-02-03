<?php
/**
 * Created by PhpStorm.
 * User: bel
 * Date: 03/11/2018
 * Time: 21:26
 */

namespace Backend\Controller;


use Model\BusinessModel\Documentation;
use Model\BusinessModel\Usuario;
use Model\DataModel\Sanitizer;
use Model\Form\AlumnoNewForm;
use Model\Form\DocumentationUserForm;
use Zend\Validator\Db\NoRecordExists;
use Zend\View\Model\ViewModel;

class AlumnosController extends BackendController
{
    public function indexAction()
    {
        $this->setSessionToken(true);
        return $this->renderIndex('ALUMNOS');
    }

    protected function renderIndex( $containerName )
    {
        if (empty($containerName)) {
            $containerName = $this->getAdminSession()->containerName;
        } else {
            $this->getAdminSession()->containerName = $containerName;
        }

        if (empty($containerName)) {
            return $this->redirect()->toUrl('/admin');
        }

        $pageTitle = 'Alumnos';


        $alumnosRecsArr = $this->getBusinessModel()->getAlumnos();
        $alumnosReturn = array();

        foreach($alumnosRecsArr as $alumno){
            $alumnoReturn = $alumno;
            $tutor= $this->getBusinessModel()->getUserById($alumno['id_tutor']);
            $alumnoReturn['tutor'] = $tutor[0];
            array_push($alumnosReturn, $alumnoReturn);
        }

        $arrVariables = array(
            'pageTitle' => $pageTitle,
            'alumnosArr' => $alumnosReturn,
            'token' => $this->getSessionToken(true),
        );

        $contentVM = new ViewModel();
        //control por URL
        if (!$_SESSION['admin_session']['userName']){
            return $this->redirect()->toUrl('/mpfnimda/login');

        }

        if($this->getAdminRole() == 3 && $_SESSION['admin_session']['adminId']){
            $contentVM->setTemplate('error/404');

        } else {
            $contentVM->setTemplate('backend/alumnos/index')->setVariables($arrVariables);

        }
        return $this->layoutBackendView( $contentVM, 1 );
    }

    public function editAction()
    {

        $form = new AlumnoNewForm();

        $professArr = $this->getBusinessModel()->getProfesores();

        $profesOptions = array();

        $alumnoId = Sanitizer::sanitizeIntInput($this->params()->fromRoute('id'));
        $alumnoTable = $this->getBusinessModel()->getUsuarioTable();
        $user = $alumnoTable->getRecordByIdUser($alumnoId);


        if ($alumnoId > 0) {
            $pass = $user->getPassword();

            $formData = $user->getDataArray(true);

            $form->get('password')->setValue(base64_decode($pass));
            $form->get('password_rep')->setValue(base64_decode($pass));
            $formData['password']= base64_decode($pass);
            $form->setData($formData);
        }

        if ( $this->getRequest()->isPost() ) {

            $formData = $this->getRequest()->getPost();
            $form->setData($formData);
            $datosFormData = Sanitizer::filterFormParams($form->getElements());

            $noRecordExist = new NoRecordExists(
                array(
                    'table' => 'usuarios',
                    'field' => 'dni',
                    'adapter' => $this->getCommonContentsModel()->getDbAdapter(),
                )
            );
            $noRecordExist->setMessage('Ya existe un usuario registrado con este documento');

            if($alumnoId > 0) {
                $noRecordExist->setExclude(array(
                    'field' => 'dni',
                    'value' => $datosFormData['dni']
                ));
            }

            $form->getInputFilter()->get('dni')->getValidatorChain()->addValidator($noRecordExist);

            $nif = $this->validaNIF($formData['dni']);

            if (!$nif){
                $nif  = $this->valida_nie($formData['dni']);
            }

            if ( $form->isValid() && $nif) {
                return $this->procesoForm($user, $formData, $alumnoTable);

            } else {
                if(!$nif){
                    $this->flashMessenger()->addErrorMessage('Revise el formato del documento de identidad introducido');
                }
            }
        }

        if($alumnoId > 0){

            $tutor = $this->getBusinessModel()->getUserById($user->getIdTutor());

            $profesOptions[$tutor[0]['id_user']] = $tutor[0]['name']." ".$tutor[0]['lastname'];
            foreach ($professArr as $profesAux) {

                $profesOptions[$profesAux['id_user']] = $profesAux['name']." ".$profesAux['lastname'];

            }
        } else {

            $profesOptions[''] = 'Seleccione un profesor';
            foreach ($professArr as $profesAux) {

                $profesOptions[$profesAux['id_user']] = $profesAux['name']." ".$profesAux['lastname'];

            }
        }


        $form->get('tutor')->setValueOptions($profesOptions);

        $arrVariables = array(
            'user' => $user,
            'form' => $form,
            'profesores' => $professArr,
        );

        $contentVM = new ViewModel();
        //control por URL
        if (!$_SESSION['admin_session']['userName']){
            return $this->redirect()->toUrl('/mpfnimda/login');

        }

        if($this->getAdminRole() == 3 && $_SESSION['admin_session']['adminId']){
            $contentVM->setTemplate('error/404');

        } else {
            $contentVM->setTemplate('backend/alumnos/edit')->setVariables($arrVariables);

        }

        return $this->layoutBackendView( $contentVM, 1 );

    }

    public function showAction(){
        $alumnoId = Sanitizer::sanitizeIntInput($this->params()->fromRoute('id'));
        $alumno = $this->getBusinessModel()->getAlumno($alumnoId);
        $tutorid = $this->getBusinessModel()->getIdTutorByAlumnId($alumnoId);
        $tutor = $this->getBusinessModel()->getUserById($tutorid);


        if($alumno['has_project'] ==1){
            $proyecto = $this->getBusinessModel()->getProjectDataByAlumn($alumnoId);

        } else {
            $proyecto = [];
        }

        if($alumno['has_documentation'] ==1){
            $documentacion = $this->getBusinessModel()->getDocumentationByAlumn($alumnoId);

        } else {
            $documentacion = [];
        }


        $arrVariables = array(
            'alumno'  => $alumno,
            'documentacion' => $documentacion,
            'proyecto' => $proyecto,
            'tutor' => $tutor,
        );
        $contentVM = new ViewModel();

        if($this->getAdminRole() == 3 && $_SESSION['admin_session']['adminId'] != $alumno['id_user']){
            $contentVM->setTemplate('error/404');

        } else {
            $contentVM->setTemplate('backend/alumnos/show')->setVariables($arrVariables);

        }

        //control por URL
        if (!$_SESSION['admin_session']['userName']){
            return $this->redirect()->toUrl('/mpfnimda/login');

        }
        return $this->layoutBackendView( $contentVM, 3 );
    }


    public function deleteAction(){

        $userId = Sanitizer::sanitizeIntInput($this->params()->fromRoute('id'));

        //borrado logico -> solo lo inactivo
        if ($userId > 0){
            $user =  $this->getBusinessModel()->getUsuarioTable()->getRecordByIdUser($userId);
            $user->setDeletedAt(date('Y-m-d H:i:s'));
            $user->setDni('deleted_'.$user->getDni());
            $user->setEmail('deleted_'.$user->getEmail());

            $userTable = $this->getBusinessModel()->getUsuarioTable();

            $this->logCambiosBack(sprintf('Usuario: el usuario de username ' . $_SESSION['admin_session']['userName'] . '(' . $_SESSION['admin_session']['adminId'] . ') ha eliminado al usuario con identificador '.$userId));

            //documentación

            //Le cambio el has_project al alumno

            if($user->getHasProject() == 1){
                $project=  $this->getBusinessModel()->getProjectTable()->getProjectRecordByIdUser($userId);
                $project->setDeletedAt(date('Y-m-d H:i:s'));
                $projectTable = $this->getBusinessModel()->getProjectTable();
                $projectTable->updateProjectRecord($project);
                $user->setHasProject(0);

                //compruebo si el proyecto tiene documentación
                $docs = $this->getBusinessModel()->getDocumentationObByAlumn($project->getIdAlumn());
                $docTable = $this->getBusinessModel()->getDocumentationTable();

                if (!is_null($docs->count())){
                    foreach ($docs as $doc){
                        $docu =  $this->getBusinessModel()->getDocumentationTable()->getRecordByIdDocumentation($doc['id_documentation']);

                        $docu->setDeletedAt(date('Y-m-d H:i:s'));

                        //lo borro fisicamente
                        $fileToDelete = $docu->getFilename();
                        unlink($fileToDelete);
                        rmdir('public/data/'.$docu->getIdAlumn());
                        $docTable->updateDocumentationRecord($docu);

                    }
                }

            }

            $userTable->updateUserRecord($user);

            $this->flashMessenger()->addSuccessMessage('El alumno ha sido eliminado correctamente');
        } else {
            $this->flashMessenger()->addErrorMessage('Se produjo un error al eliminar al alumno');
        }


        $contentVM = new ViewModel();

        if($this->getAdminRole() == 3 && $_SESSION['admin_session']['adminId']){
            $contentVM->setTemplate('error/404');

        } else {
            return $this->redirect()->toUrl('/admin/alumnos');

        }

    }


    public function documentacionAction()
    {

        $form = new DocumentationUserForm();

        $alumnoId = Sanitizer::sanitizeIntInput($this->params()->fromRoute('id'));
        $alumnoTable = $this->getBusinessModel()->getUsuarioTable();
        $user = $alumnoTable->getRecordByIdUser($alumnoId);


        if ($alumnoId > 0) {
            $tutor = $this->getBusinessModel()->getUserById($user->getIdTutor());

            $formData = $user->getDataArray(true);
            $form->setData($formData);
        }

        if ( $this->getRequest()->isPost() ) {

            $formData = $this->getRequest()->getPost();
            $form->setData($formData);
            $datosFormData = Sanitizer::filterFormParams($form->getElements());

            if ( $form->isValid() ) {


                if($_FILES['documentation']['name'][0] != ""){
                    $cont = 0;
                    foreach ($_FILES['documentation']['name'] as $doc){

                        $documentation = new Documentation();
                        $documentation->setIdAlumn($alumnoId);
                        $documentation->setDescription($doc);
                        $documentation->setFilename('public/data/'.$user->getIdUser().'/'.str_replace(" ", "_",$doc));
                        $documentation->setCreatedAt(date('Y-m-d H:i:s'));
                        $docTable = $this->getBusinessModel()->getDocumentationTable();

                        $fichero = $_FILES['documentation']['tmp_name'][$cont];
                        $nuevo_fichero = 'public/data/'.$user->getIdUser().'/'.str_replace(" ", "_",$doc);

                        if (!file_exists('public/data/'.$user->getIdUser())) {
                            mkdir('public/data/'.$user->getIdUser(), 0777, true);
                        }

                        if (!move_uploaded_file($fichero, $nuevo_fichero)) {
                            $this->flashMessenger()->addErrorMessage('Ha habido un error al copiar los archivos');
                            $errors= error_get_last();
                            echo "COPY ERROR: ".$errors['type'];
                            echo "<br />\n".$errors['message'];
                        } else {

                            $alumnoTable = $this->getBusinessModel()->getUsuarioTable();
                            $user2 = $alumnoTable->getRecordByIdUser($alumnoId);
                            $user2->setHasDocumentation(1);
                            $alumnoTable->updateUserRecord($user2);

                            $docTable->updateDocumentationRecord($documentation);

                        }
                        $cont++;
                    }
                    //enviar Mail a tutor
                    $contentTutor = 'Hola '.$tutor[0]['name'].', El alumno '.$user->getName().' '.$user->getLastname().' con email: '.$user->getEmail().' ha subido documentación a su proyecto';

                    $enviado = $this->sendMailwithTemplate($tutor[0]['email'], 'gestor', 'Subida de documentación a proyecto', $contentTutor, 'Alta de alumno en Moodle', null);

                }

                return $this->redirect()->toUrl('/admin/alumnos/show/'.$alumnoId);

            }
        }


        $arrVariables = array(
            'user' => $user,
            'form' => $form,
        );

        $contentVM = new ViewModel();
        $contentVM->setTemplate('backend/alumnos/documentacion')->setVariables($arrVariables);
        //control por URL
        if (!$_SESSION['admin_session']['userName']){
            return $this->redirect()->toUrl('/mpfnimda/login');

        }
        return $this->layoutBackendView( $contentVM, 1 );

    }

    public function deleteDocsAction(){
        $docId = Sanitizer::sanitizeIntInput($this->params()->fromRoute('id'));

        $doc =  $this->getBusinessModel()->getDocumentationTable()->getRecordByIdDocumentation($docId);

        if($doc){
            $doc->setDeletedAt(date('Y-m-d H:i:s'));
            $docTable = $this->getBusinessModel()->getDocumentationTable();
            $docTable->updateDocumentationRecord($doc);

            //lo borro fisicamente
            $fileToDelete = $doc->getFilename();
            unlink($fileToDelete);
            rmdir('public/data/'.$doc->getIdAlumn());

            $documentacion = $this->getBusinessModel()->getDocumentationByAlumn($doc->getIdAlumn());

            if(count($documentacion)== 0){
                //resetear has_doc de user
                $table = $this->getCommonContentsModel()->getUsuarioTable();

                $user = $table->getRecordByIdUser($doc->getIdAlumn());

                $user->setHasDocumentation(0);
               $userTable = $this->getBusinessModel()->getUsuarioTable();
                $userTable->updateUserRecord($user);
            }
        }

        $contentVM = new ViewModel();

        if($this->getAdminRole() == 3 && $_SESSION['admin_session']['adminId']){
            $contentVM->setTemplate('error/404');

        } else {
            return $this->redirect()->toUrl('/admin/alumnos/show/'.$doc->getIdAlumn());

        }

    }

    /**
     * @param $user
     * @param $formData
     * @param $alumnoTable
     * @return \Zend\Http\Response
     */
    public function procesoForm($user, $formData, $alumnoTable)
    {

        if (!$user) {
            $user = new Usuario();
            $new = true;

            $this->logCambiosBack(sprintf('ALUMNO: el usuario de username ' . $_SESSION['admin_session']['userName'] . ' ha creado al usuario de nombre ' . Sanitizer::sanitizeInput($formData['name']) . ' ' . Sanitizer::sanitizeInput($formData['lastname'])));

        } else {
            $new = false;
            $this->logCambiosBack(sprintf('ALUMNO: el usuario de username ' . $_SESSION['admin_session']['userName'] . ' ha editado al usuario de nombre ' . Sanitizer::sanitizeInput($formData['name']) . ' ' . Sanitizer::sanitizeInput($formData['lastname'])));

        }

        $formData['name'] = Sanitizer::sanitizeInput($formData['name']);
        $formData['surname'] = Sanitizer::sanitizeInput($formData['surname']);
        $formData['email'] = Sanitizer::sanitizeInput($formData['email']);
        $formData['status'] = Sanitizer::sanitizeInput($formData['status']);
        $formData['password'] = base64_encode($formData['password']);


        $user->setName($formData['name']);
        $user->setLastName($formData['lastname']);
        $user->setEmail($formData['email']);
        $user->setDni($formData['dni']);
        $user->setRol(3);
        $user->setCourse($formData['course']);
        $user->setIdTutor($formData['tutor']);
        $user->setPassword($formData['password']);
        if ($user->getCreatedAt() == null) {
            $user->setCreatedAt(date('Y-m-d H:i:s'));
            $user->setUpdatedAt(date('Y-m-d H:i:s'));
        } else {
            $user->setUpdatedAt(date('Y-m-d H:i:s'));

        }

        //enviar mail al alumno comunicando cambio de contraseña

        $operation = $alumnoTable->updateUserRecord($user);

        if ($_FILES['documentation']['name'][0] != "") {

            $this->procesoFormFiles($formData, $alumnoTable, $operation);

        }

        if ($new) {
            $content = 'Hola ' . $formData['name'] . ', has sido dado de alta en el Moodle para gestionar su curso y sus comunicaciones con profesores. Su usuario: ' . $formData['email'] . ' su contaseña: ' . $formData['password'];
            $alumnoTable = $this->getBusinessModel()->getUsuarioTable();
            $tutor = $alumnoTable->getRecordByIdUser($formData['tutor']);

            //enviar Mail a tutor
            $contentTutor = 'Hola ' . $tutor->getName() . ', Se te ha asignado un alumno en tutoría. Estos son sus datos: ' . $formData['name'] . ' ' . $formData['lastname'] . ' con email: ' . $formData['email'];

            $this->sendMailwithTemplate($tutor->getEmail(), '', 'Alta en gestión de proyectos', $contentTutor, 'Alta de alumno en Moodle', null);

        } else {
            $content = 'Su información en Moodle ha sido editada, por favor compruebe sus datos desde la plataforma. Su usuario: ' . $formData['email'] . ' su contaseña: ' . $formData['password'];

        }
        //comunicación de mensaje
        $enviado = $this->sendMailwithTemplate($formData['email'], '', 'Alta en gestión de proyectos', $content, 'Alta en Moodle', null);
        if(!$enviado){
            $this->logCambiosBack(sprintf('hubo un error en el envio del email de comunicación '.$user->getEmail()));

        }

        return $this->redirect()->toUrl('/admin/alumnos');
    }

    /**
     * @param $formData
     * @param $alumnoTable
     * @param $operation
     * @return mixed
     */
    public function procesoFormFiles($formData, $alumnoTable, $operation)
    {
        $cont = 0;
        foreach ($_FILES['documentation']['name'] as $doc) {

            $documentation = new Documentation();
            $documentation->setIdAlumn($operation);
            $documentation->setDescription($doc);
            $documentation->setFilename('public/data/' . $formData['id_alumn'] . '/' . str_replace(" ", "_", $doc));
            $documentation->setCreatedAt(date('Y-m-d H:i:s'));
            $docTable = $this->getBusinessModel()->getDocumentationTable();

            $fichero = $_FILES['documentation']['tmp_name'][$cont];
            $nuevo_fichero = 'public/data/' . $formData['id_alumn'] . '/' . str_replace(" ", "_", $doc);

            if (!file_exists('public/data/' . $formData['id_alumn'])) {
                mkdir('public/data/' . $formData['id_alumn'], 0777, true);
            }

            if (!move_uploaded_file($fichero, $nuevo_fichero)) {
                $this->flashMessenger()->addErrorMessage('Ha habido un error al copiar los archivos');
                $errors = error_get_last();
                echo "COPY ERROR: " . $errors['type'];
                echo "<br />\n" . $errors['message'];
            } else {

                $alumnoTable = $this->getBusinessModel()->getUsuarioTable();
                $user2 = $alumnoTable->getRecordByIdUser($operation);
                $user2->setHasDocumentation(1);
                $result = $alumnoTable->updateUserRecord($user2);

                $docTable->updateDocumentationRecord($documentation);

            }
            $cont++;
        }
        return $result;
    }


}