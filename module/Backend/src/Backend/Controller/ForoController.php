<?php

namespace Backend\Controller;

use Model\BusinessModel\Noticia;
use Model\BusinessModel\Source;
use Model\DataModel\Sanitizer;
use Model\Form\NoticiaForm;
use Model\Form\SourceForm;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;

class ForoController extends BackendController
{

    public function indexAction()
    {
        $sources = $this->getBusinessModel()->getNoticias();

        $arrVariables = array(
            'sources' => $sources,
            'token' => $this->getSessionToken(true),
        );

        $contentVM = new ViewModel();
        $contentVM->setTemplate('backend/foro/index')->setVariables($arrVariables);

        if (!$_SESSION['admin_session']['userName']){
            return $this->redirect()->toUrl('/mpfnimda/login');

        }

        return $this->layoutBackendView( $contentVM, 8);
    }

    public function deleteAction()
    {

            $recordId = Sanitizer::sanitizeIntInput($this->params()->fromRoute('id'));
            $table = $this->getBusinessModel()->getNoticiaTable();

            $noticia = $table->getRecordById($recordId);
            $this->logCambiosBack(sprintf('NOTICIAS: el usuario de username ' . $_SESSION['admin_session']['userName'] . '( ha eliminado la noticia '.$noticia->getId(). ' y nombre '.$noticia->getTitle()));
            if ( $noticia ) {
                $noticia->setDeletedAt(date('Y-m-d H:i:s'));
                if($this->getBusinessModel()->getNoticiaTable()->updateRecord($noticia)) {
                    $this->flashMessenger()->addSuccessMessage('Noticia eliminada correctamente');
                } else {
                    $this->flashMessenger()->addErrorMessage('No se ha podido eliminar la noticia');

                }

            } else {
                $this->flashMessenger()->addErrorMessage('No se puede eliminar la noticia');
            }

        if (!$_SESSION['admin_session']['userName']){
            return $this->redirect()->toUrl('/mpfnimda/login');

        }

        return $this->indexAction();
    }

    public function editAction()
    {
        $sourceId = Sanitizer::sanitizeIntInput($this->params()->fromRoute('id'));
        $token = '';

        $form = new NoticiaForm( );

        if ($sourceId > 0) {
            $source = $this->getBusinessModel()->getNoticiaTable()->getRecordById($sourceId);

            $formData = $source->getDataArray(true);
            $form->setData($formData);
        }

        if ( $this->getRequest()->isPost() ) {

            $formData = $this->getRequest()->getPost();
            //$formData['name'] = Sanitizer::sanitizeInput($formData['name']);
            $form->setData($formData);

            if ( $form->isValid() ) {

                return $this->procesoForm($source, $formData);

            }
        }

        $arrVariables = array(
            'form'     => $form,
            'token'     => $token
        );

        $contentVM = new ViewModel();
        $contentVM->setTemplate('backend/foro/edit')->setVariables($arrVariables);

        if (!$_SESSION['admin_session']['userName']){
            return $this->redirect()->toUrl('/mpfnimda/login');

        }

        return $this->layoutBackendView( $contentVM, 8 );
    }

    /**
     * @param $source
     * @param $formData
     * @return \Zend\Http\Response
     */
    public function procesoForm($source, $formData)
    {
        if (!$source) {
            $source = new Noticia();
            $this->logCambiosBack(sprintf('NOTICIAS: el usuario de username ' . $_SESSION['admin_session']['userName'] . '(' . $_SESSION['admin_session']['adminId'] . ') ha creadp una noticia con título ' . $formData['title']));
        } else {
             $this->logCambiosBack(sprintf('NOTICIAS: el usuario de username ' . $_SESSION['admin_session']['userName'] . '(' . $_SESSION['admin_session']['adminId'] . ') ha editado la fuente con identificador '.$source->getId(). ' y nombre '.$source->getName()));
        }

        $source->setTitle($formData['title']);
        $source->setExcerpt($formData['excerpt']);
        $source->setContent($formData['comments']);
        $source->setIdAutor($_SESSION['admin_session']['adminId']);
        $source->setCreatedAt(date('Y-m-d H:i:s'));

        if ($_FILES['mediafile']['name'] != "") {

            $this->procesoFormFiles($source);

        }
        if ($this->getBusinessModel()->getNoticiaTable()->updateRecord($source)) {
            $this->flashMessenger()->addSuccessMessage('Se han guardado los datos con éxito');
        }

        return $this->redirect()->toUrl('/admin/foro');
    }

    /**
     * @param $source
     */
    public function procesoFormFiles($source)
    {
        $fichero = $_FILES['mediafile']['tmp_name'];
        $nuevo_fichero = 'public/uploads/' . date('Y-m') . '/' . str_replace(" ", "_", $_FILES['mediafile']['name']);

        if (!file_exists('public/uploads/' . date('Y-m'))) {
            mkdir('public/uploads/' . date('Y-m'), 0777, true);
        }

        if (!move_uploaded_file($fichero, $nuevo_fichero)) {
            $this->flashMessenger()->addErrorMessage('Ha habido un error al copiar los archivos');
            $errors = error_get_last();
            echo "COPY ERROR: " . $errors['type'];
            echo "<br />\n" . $errors['message'];
        } else {
            $source->setMedia($nuevo_fichero);
        }
    }

}

