<?php
/**
 * Created by PhpStorm.
 * User: bel
 * Date: 19/11/2018
 * Time: 20:01
 */

namespace Backend\Controller;


use Model\BusinessModel\Usuario;
use Model\DataModel\Sanitizer;
use Model\Form\ProfesorNewForm;
use Zend\Validator\Db\NoRecordExists;
use Zend\View\Model\ViewModel;

class ProfesorsController extends BackendController
{

    public function indexAction()
    {

        $profesArr = $this->getBusinessModel()->getProfesores();

        $arrVariables = array(
            'profesores'    => $profesArr,
            'token'        => $this->setSessionToken(true)
        );

        $contentVM = new ViewModel();
        $contentVM->setTemplate('backend/admins/index')->setVariables($arrVariables);

        if (!$_SESSION['admin_session']['userName']){
            return $this->redirect()->toUrl('/mpfnimda/login');

        }

        return $this->layoutBackendView( $contentVM, 7);
    }

    public function editAction()
    {

        $form = new ProfesorNewForm();

        $profesorId = Sanitizer::sanitizeIntInput($this->params()->fromRoute('id'));
        $profesorTable = $this->getBusinessModel()->getUsuarioTable();
        $user = $profesorTable->getRecordByIdUser( $profesorId);


        if ($profesorId > 0) {
            $pass = $user->getPassword();


            $form->get('password')->setValue($pass);
            $form->get('password_rep')->setValue(base64_decode($pass));

            $formData = $user->getDataArray(true);
            $formData['password'] = base64_decode($pass);
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

            $noRecordExist->setMessage('Ya existe un Profesor registrado con este documento');

            if($profesorId > 0) {
                $noRecordExist->setExclude(array(
                    'field' => 'dni',
                    'value' => $datosFormData['dni']
                ));
            }

            $form->getInputFilter()->get('dni')->getValidatorChain()->attach($noRecordExist);

            $nif = $this->validaNIF($formData['dni']);

            if (!$nif){
                $nif  = $this->valida_nie($formData['dni']);
            }


            if ( $form->isValid() && $nif) {

                return $this->procesoForm($user, $formData, $profesorTable);

            } else {
                if(!$nif){
                    $this->flashMessenger()->addErrorMessage('Revise el formato del documento de identidad introducido');
                }
            }
        }


        $arrVariables = array(
            'form' => $form,
        );


        $contentVM = new ViewModel();

        if($this->getAdminRole() == 3 && $_SESSION['admin_session']['adminId']){
            $contentVM->setTemplate('error/404');

        } else {
            $contentVM->setTemplate('backend/admins/edit')->setVariables($arrVariables);
            $jsVM = new ViewModel();
            $jsVM->setTemplate('backend/admins/edit-js')->setVariables($arrVariables);
        }

        if (!$_SESSION['admin_session']['userName']){
            return $this->redirect()->toUrl('/mpfnimda/login');

        }

        return $this->layoutBackendView( $contentVM, 7, $jsVM );

    }

    public function deleteAction()
    {

        $recordId = Sanitizer::sanitizeIntInput($this->params()->fromRoute('id'));
        $table = $this->getCommonContentsModel()->getUsuarioTable();

        $adminToDelete = $table->getRecordByIdUser($recordId);

        $adminToDelete->setDeletedAt(date('Y-m-d H:i:s'));
        $adminToDelete->setDni('deleted_'.$adminToDelete->getDni());
        $adminToDelete->setEmail('deleted_'.$adminToDelete->getEmail());

        //asigno los alumnos si tuviese

        $alumnos = $this->getBusinessModel()->getAlumnosByIdTutor($recordId);

        if(!empty($alumnos)){
            foreach ($alumnos as $alumno){
                $alumnoCh = $this->getCommonContentsModel()->getUsuarioTable()->getRecordByIdUser($alumno['id_user']);
                $alumnoCh->setIdTutor($_SESSION['admin_session']['adminId']);
                $table->updateUserRecord($alumnoCh);

            }
        }


        if($table->updateUserRecord($adminToDelete)){
            $this->logCambiosBack(sprintf('ADMIN: el usuario de username '. $_SESSION['admin_session']['userName'] . '('.$_SESSION['admin_session']['adminId'].') ha eliminado al usuario de nombre '. $adminToDelete->getName().' '.$adminToDelete->getLastname()));
            $this->flashMessenger()->addSuccessMessage('El profesor ha sido eliminado correctamente');

        } else {
            $this->flashMessenger()->addErrorMessage('se produjo un error durante el proceso de borrado');

        }

        $contentVM = new ViewModel();

        if($this->getAdminRole() == 3 && $_SESSION['admin_session']['adminId']){
            $contentVM->setTemplate('error/404');
            return $this->layoutBackendView( $contentVM, 7);

        }

        if (!$_SESSION['admin_session']['userName']){
            return $this->redirect()->toUrl('/mpfnimda/login');

        }

        return $this->indexAction();
    }

    public function showAction(){
        $profeId = Sanitizer::sanitizeIntInput($this->params()->fromRoute('id'));
        $profe = $this->getBusinessModel()->getAlumno($profeId);
        $alumnos = $this->getBusinessModel()->getAlumnosByIdTutor($profe['id_user']);

        $arrVariables = array(
            'profesor'  => $profe,
            'alumnos'  => $alumnos,
        );
        $contentVM = new ViewModel();

        $contentVM->setTemplate('backend/admins/show')->setVariables($arrVariables);

        //control por URL
        if (!$_SESSION['admin_session']['userName']){
            return $this->redirect()->toUrl('/mpfnimda/login');

        }
        return $this->layoutBackendView( $contentVM, 3 );
    }

    /**
     * @param $user
     * @param $formData
     * @param $profesorTable
     * @return \Zend\Http\Response
     */
    public function procesoForm($user, $formData, $profesorTable)
    {
        if (!$user) {
            $user = new Usuario();
            $new = true;
            $this->logCambiosBack(sprintf('ADMIN: el usuario de username ' . $_SESSION['admin_session']['userName'] . ' ha creado un profesor de nombre ' . Sanitizer::sanitizeInput($formData['name']) . ' ' . Sanitizer::sanitizeInput($formData['surname'])));

        } else {
            $new = false;
            $this->logCambiosBack(sprintf('ADMIN: el usuario de username ' . $_SESSION['admin_session']['userName'] . ' ha editado un profesor de nombre ' . Sanitizer::sanitizeInput($formData['name']) . ' ' . Sanitizer::sanitizeInput($formData['surname'])));
        }

        $formData['name'] = Sanitizer::sanitizeInput($formData['name']);
        $formData['surname'] = Sanitizer::sanitizeInput($formData['surname']);
        $formData['email'] = Sanitizer::sanitizeInput($formData['email']);
        $formData['password'] = Sanitizer::sanitizeInput($formData['password']);


        $user->setName($formData['name']);
        $user->setLastName($formData['lastname']);
        $user->setEmail($formData['email']);
        $user->setDni($formData['dni']);

        //si es admin o es super

        $user->setRol($formData['rol']);
        $user->setCourse($formData['course']);
        $user->setIdTutor($formData['tutor']);
        $user->setPassword(base64_encode($formData['password']));


        if ($user->getCreatedAt() == null) {
            $user->setCreatedAt(date('Y-m-d H:i:s'));
            $user->setUpdatedAt(date('Y-m-d H:i:s'));
        } else {
            $user->setUpdatedAt(date('Y-m-d H:i:s'));

        }

        $profesorTable->updateUserRecord($user);

        if ($new) {
            $content = 'Hola ' . $formData['name'] . ', has sido dado de alta en el Moodle para gestionar tu curso y tus comunicaciones con alumnos y demás profesores. Su usuario: ' . $formData['email'] . ' su contaseña: ' . $formData['password'];
            $enviado = $this->sendMailwithTemplate($formData['email'], '', 'Alta en gestión de proyectos', $content, 'Alta en Moodle', null);
            //set log error si hubo error en el mensaje
        }

        return $this->redirect()->toUrl('/admin/profesors');
    }

}