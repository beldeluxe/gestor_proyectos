<?php
/**
 * Created by PhpStorm.
 * User: bel
 * Date: 30/11/2018
 * Time: 18:53
 */

namespace Backend\Controller;


use Model\BusinessModel\Mensaje;
use Model\DataModel\Sanitizer;
use Model\Form\NewEmailForm;
use Zend\View\Model\ViewModel;

class MensajesController extends BackendController
{

    public function indexAction()
    {
        //control por URL
        if (!$_SESSION['admin_session']['userName']){
            return $this->redirect()->toUrl('/mpfnimda/login');

        }

        $mensajes_recibidos = $this->getBusinessModel()->getMensajesByUser($_SESSION['admin_session']['adminId']);
        $mensajes_enviados = $this->getBusinessModel()->getMensajesEnviadosByUser($_SESSION['admin_session']['adminId']);

        $arrVariables = array(
            'mensajes_recibidos' => $mensajes_recibidos,
            'mensajes_enviados' => $mensajes_enviados,
        );

        $contentVM = new ViewModel();
        $contentVM->setTemplate('backend/mensajes/index')->setVariables($arrVariables);
        return $this->layoutBackendView( $contentVM, 8);
    }

    public function newAction()
    {
        $userId = Sanitizer::sanitizeIntInput($this->params()->fromRoute('id'));

        if($userId > 0) {
            $userTable = $this->getBusinessModel()->getUsuarioTable();
            $user = $userTable->getRecordByIdUser($userId);

        }


        $form = new NewEmailForm();

        $usuario = $this->getBusinessModel()->getUserById($_SESSION['admin_session']['adminId']);

        $destinatarios = $this->getBusinessModel()->getDestinatariosByUserRol($usuario[0]['rol']);

        $destinatariosOptions = array();

        if($userId > 0){
            $destinatariosOptions[$userId] = $user->getName()." ".$user->getLastname();
        } else {
            $destinatariosOptions[''] = 'Seleccione un destinatario';
            foreach ($destinatarios as $destinatarioAux) {

                $destinatariosOptions[$destinatarioAux['id_user']] = $destinatarioAux['name']." ".$destinatarioAux['lastname'];

            }
        }

        $form->get('destinatario')->setValueOptions($destinatariosOptions);


        if ( $this->getRequest()->isPost() ) {

            $formData = $this->getRequest()->getPost();
            //$formData['name'] = Sanitizer::sanitizeInput($formData['name']);
            $form->setData($formData);

            if ( $form->isValid() ) {

                return $this->procesoForm($formData);

            } else {
                $this->flashMessenger()->addErrorMessage('debe rellenar el formulario correctamente');

            }
        }

        $arrVariables = array(
            'form' => $form
        );

        $contentVM = new ViewModel();
        $contentVM->setTemplate('backend/mensajes/new')->setVariables($arrVariables);
        return $this->layoutBackendView( $contentVM, 8);
    }


    public function showAction()
    {
        $mensajeId = Sanitizer::sanitizeIntInput($this->params()->fromRoute('id'));
        $mensaje_1 = $this->getBusinessModel()->getMensaje($mensajeId);
        $remitente = $this->getBusinessModel()->getUserById($mensaje_1[0]['id_remitente']);
        $destinatario = $this->getBusinessModel()->getUserById($mensaje_1[0]['id_destinatario']);


        $arrVariables = array(
            'mensaje'  => $mensaje_1[0],
            'remitente' => $remitente[0],
            'destinatario' => $destinatario[0],
        );
        $contentVM = new ViewModel();

        $contentVM->setTemplate('backend/mensajes/show')->setVariables($arrVariables);

        $mensajeTable = $this->getBusinessModel()->getMensajeTable();
        $mensaje = $mensajeTable->getRecordById($mensajeId);
        if($mensaje->getIdDestinatario() == $_SESSION['admin_session']['adminId']){
            $mensaje->setEstado(1);
        }
        $mensajeTable->updateRecord($mensaje);

        //control por URL
        if (!$_SESSION['admin_session']['userName']){
            return $this->redirect()->toUrl('/mpfnimda/login');

        }
        return $this->layoutBackendView( $contentVM, 3 );
    }

    public function deleteAction()
    {
        $recordId = Sanitizer::sanitizeIntInput($this->params()->fromRoute('id'));
        $table = $this->getBusinessModel()->getMensajeTable();

        $mensaje = $table->getRecordById($recordId);
        if ($_SESSION['admin_session']['adminId'] == $mensaje->getIdDestinatario()){
            $mensaje->setDeletedAt(date('Y-m-d H:i:s'));
        } else {
            $mensaje->setDeletedAtByRemitente(date('Y-m-d H:i:s'));
        }

        if($this->getBusinessModel()->getMensajeTable()->updateRecord($mensaje)) {
            $this->flashMessenger()->addSuccessMessage('Mensaje eliminado correctamente');
        } else {
            $this->flashMessenger()->addErrorMessage('No se ha mensaje eliminar la noticia');
        }

        if (!$_SESSION['admin_session']['userName']){
            return $this->redirect()->toUrl('/mpfnimda/login');

        }

        return $this->indexAction();
    }

    /**
     * @param $formData
     * @return \Zend\Http\Response
     */
    public function procesoForm($formData)
    {
        $mensaje = new Mensaje();

        $mensaje->setAsunto($formData['asunto']);
        $mensaje->setContent($formData['comments']);
        $mensaje->setIdRemitente($_SESSION['admin_session']['adminId']);
        $mensaje->setIdDestinatario($formData['destinatario']);
        $mensaje->setEstado(0);
        $mensaje->setCreatedAt(date('Y-m-d H:i:s'));

        if ($this->getBusinessModel()->getMensajeTable()->updateRecord($mensaje)) {
            $content = 'Ha recibido un mensaje en la plataforma Moodle, consulte su perfil para acceder al contenido';
            $destinatario = $this->getBusinessModel()->getUserById($formData['destinatario']);
            $enviado = $this->sendMailwithTemplate($destinatario[0]['email'], '', 'Mensajes en gestión de proyectos', $content, 'Alta en Moodle', null);
            if ($enviado) {
                $this->flashMessenger()->addSuccessMessage('Se ha enviado el mensaje con éxito');

            }
        }

        return $this->redirect()->toUrl('/admin/mensajes');
    }


}