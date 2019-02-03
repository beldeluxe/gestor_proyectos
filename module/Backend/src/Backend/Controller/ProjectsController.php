<?php

namespace Backend\Controller;

use Model\BusinessModel\Documentation;
use Model\Form\ProjectForm;
use Zend\View\Model\ViewModel;

use Model\DataModel\Sanitizer;

use Model\BusinessModel\Project;


class ProjectsController extends BackendController
{

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

        $pageTitle = 'Proyectos';


        $projectRecsArr = $this->getBusinessModel()->getProjects();
        $projectsReturn = array();

        foreach($projectRecsArr as $project){
            $projectReturn = $project;
           // $tutor= $this->getBusinessModel()->getUserById($project['id_tutor']);
            //$projectReturn['tutor'] = $tutor[0];
            array_push($projectsReturn, $projectReturn);
        }

        $arrVariables = array(
            'pageTitle' => $pageTitle,
            'projectsArr' => $projectsReturn,
            'token' => $this->getSessionToken(true),
        );

        $contentVM = new ViewModel();
        $contentVM->setTemplate('backend/projects/index')->setVariables($arrVariables);

        //control por URL
        if (!$_SESSION['admin_session']['userName']){
            return $this->redirect()->toUrl('/mpfnimda/login');

        }
        return $this->layoutBackendView( $contentVM, 1 );
    }

    public function indexAction()
    {
        $this->setSessionToken(true);
        return $this->renderIndex('PROJECTS');
    }

    public function editAction()
    {

        $form = new ProjectForm();
        $alumnosArr = $this->getBusinessModel()->getAlumnosSinProyecto();

        $alumnosOptions = array();

        $projectId = Sanitizer::sanitizeIntInput($this->params()->fromRoute('id'));
        $projectTable = $this->getBusinessModel()->getProjectTable();
        $project = $projectTable->getRecordByIdProject($projectId);


        if ($projectId > 0) {
            $formData = $project->getDataArray(true);
            $form->setData($formData);
            $alumno = $this->getBusinessModel()->getUserById($project->getIdAlumn());
        } else {
            $alumno = [];
        }

        if ( $this->getRequest()->isPost() ) {

            $formData = $this->getRequest()->getPost();
            $form->setData($formData);
            $datosFormData = Sanitizer::filterFormParams($form->getElements());


            if ( $form->isValid() ) {

                return $this->procesoForm($project, $formData, $alumno, $projectTable);

            }
        }

        $alumnosOptions[''] = 'Seleccione un alumno';
        foreach ($alumnosArr as $alumnosAux) {

            $alumnosOptions[$alumnosAux['id_user']] = $alumnosAux['name']." ".$alumnosAux['lastname'];

        }

        $form->get('id_alumn')->setValueOptions($alumnosOptions);

        $arrVariables = array(
            'form' => $form,
            'alumnos' => $alumnosArr,
            'alumno' => $alumno,
        );




        //control por URL
        if (!$_SESSION['admin_session']['userName']){
            return $this->redirect()->toUrl('/mpfnimda/login');

        }

        $contentVM = new ViewModel();

        if($this->getAdminRole() == 3 && $_SESSION['admin_session']['adminId']){
            $contentVM->setTemplate('error/404');

        } else {
            $contentVM->setTemplate('backend/projects/edit')->setVariables($arrVariables);
        }
        return $this->layoutBackendView( $contentVM, 1 );


    }


    public function delAction(){


        $projectId = Sanitizer::sanitizeIntInput($this->params()->fromRoute('id'));

        //borrado logico -> solo lo inactivo
        if ($projectId > 0){
            $project =  $this->getBusinessModel()->getProjectTable()->getRecordByIdProject($projectId);
            $project->setDeletedAt(date('Y-m-d H:i:s'));

            $projectTable = $this->getBusinessModel()->getProjectTable();
            $projectTable->updateProjectRecord($project);


            $this->logCambiosBack(sprintf('PROYECTOS: el usuario de username ' . $_SESSION['admin_session']['userName'] . '(' . $_SESSION['admin_session']['adminId'] . ') ha eliminado el proyecto con identificador '.$projectId));

            //documentación

            //Le cambio el has_project al alumno
            $alumno = $this->getBusinessModel()->getUsuarioTable()->getRecordByIdUser($project->getIdAlumn());
            $alumno->setHasProject(0);
            $alumno->setUpdatedAt(date('Y-m-d H:i:s'));

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

            $alumno->setHasDocumentation(0);

            $usuarioTable = $this->getBusinessModel()->getUsuarioTable();
            $usuarioTable->updateUserRecord($alumno);



            $this->flashMessenger()->addSuccessMessage('Se ha borrado el proyecto correctamente');
        }else{
            $this->flashMessenger()->addErrorMessage('Ha habido un error al borrar el proyecto');
        }

        return $this->redirect()->toUrl('/admin/projects');
    }

    /**
     * @param $project
     * @param $formData
     * @param $alumno
     * @param $projectTable
     * @return \Zend\Http\Response
     */
    public function procesoForm($project, $formData, $alumno, $projectTable)
    {
        if (!$project) {
            $project = new Project();

            $this->logCambiosBack(sprintf('PROYECTOs: el usuario de username ' . $_SESSION['admin_session']['userName'] . '(' . $_SESSION['admin_session']['adminId'] . ') ha dado de alta al un proyecto '));
            $idTutor = $this->getBusinessModel()->getIdTutorByAlumnId($formData['id_alumn']);

        } else {
            $idTutor = $alumno[0]['id_tutor'];

            $this->logCambiosBack(sprintf('PROYECTOs: el usuario de username ' . $_SESSION['admin_session']['userName'] . '(' . $_SESSION['admin_session']['adminId'] . ') ha editado un proyecto '));
        }

        $formData['name'] = Sanitizer::sanitizeInput($formData['name']);
        $formData['id_alumn'] = Sanitizer::sanitizeInput($formData['id_alumn']);
        $formData['comments'] = Sanitizer::sanitizeInput($formData['comments']);
        $formData['documentation'] = Sanitizer::sanitizeInput($formData['documentation']);

        $project->setName($formData['name']);
        $project->setIdAlumn($formData['id_alumn']);
        $project->setComments($formData['comments']);
        if ($project->getCreatedAt() == null) {
            $project->setCreatedAt(date('Y-m-d H:i:s'));
            $project->setUpdatedAt(date('Y-m-d H:i:s'));
        } else {
            $project->setUpdatedAt(date('Y-m-d H:i:s'));

        }

        $alumnoTable = $this->getBusinessModel()->getUsuarioTable();
        $user = $alumnoTable->getRecordByIdUser($formData['id_alumn']);

        $projectTable->updateProjectRecord($project);
        $user->setHasProject(1);

        if ($_FILES['documentation']['name'][0] != "") {
            $this->procesoFormFiles($formData, $user);

        }

        $userTable = $this->getBusinessModel()->getUsuarioTable();

        if ($userTable->updateUserRecord($user)) {
            $this->flashMessenger()->addSuccessMessage('Los datos se han guardado con éxito');

        }

        return $this->redirect()->toUrl('/admin/projects');
    }

    /**
     * @param $formData
     * @param $user
     */
    public function procesoFormFiles($formData, $user)
    {
        $cont = 0;
        foreach ($_FILES['documentation']['name'] as $doc) {

            $documentation = new Documentation();
            $documentation->setIdAlumn($formData['id_alumn']);
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
                $user->setHasDocumentation(1);

                $docTable->updateDocumentationRecord($documentation);

            }
            $cont++;
        }
    }

}

