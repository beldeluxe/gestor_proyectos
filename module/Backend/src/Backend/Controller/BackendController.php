<?php

namespace Backend\Controller;

use Application\Controller\ApplicationController;


use Model\BusinessModel\Usuario;
use Model\DataModel\Sanitizer;


use Zend\File\Transfer\Adapter\Http;
use Zend\Validator\File\Extension;
use Zend\Validator\File\Size;
use Zend\View\Model\ViewModel;

use Model\CommonContentsModel\Admin;
use Model\Form\AdminLoginForm;

class  BackendController extends ApplicationController
{

    public function layoutBackendView( $contentVM, $menuId, $viewJSVM=NULL, $pagArr=NULL, $layoutVariables=NULL, $sectionName=null )
    {
        $headerVM  = $this->getHeaderVM('backend',NULL, $this->getAdminSession()->userName, null, array('activeMenuId' => $menuId) );
        $footerVM  = $this->getFooterVM('backend');

        if (empty($layoutVariables)) {
            $layoutVariables = array(
                'bodyClass' => '',
                'backgroundClass' => 'background',

            );
        }        

        $metaTags = array(
            'canonical' => $this->getAppBaseUrl(),
            'title' => 'Gestor de Proyectos',
            'description' => 'Gestor de Proyectos',
            'keywords' => 'Gestor de Proyectos',
            'author' => '',
        );    


        $arrVariables = array(
            'sessionToken' => $this->getSessionToken(true),
            'pagArr'       => $pagArr,
        );
        $jsVM = new ViewModel();
        $jsVM->setTemplate('backend/layout/backend-js')->setVariables($arrVariables);

        if ( !empty($viewJSVM) ) {
            $jsVM->addChild($viewJSVM,'viewJSVM');
        }

        return $this->layoutViews( 'backend', $headerVM, $contentVM,  $footerVM, $layoutVariables, $metaTags, $jsVM );    
    }

    protected function processLoginForm( $formData )
    {
        $email = Sanitizer::sanitizeInput($formData->email);
        $passwd = Sanitizer::sanitizeInput($formData->password);

        // Buscamos usuario
        //buscamos en la tabla de usuarios
        $adminUsr = $this->getCommonContentsModel()->getUsuarioTable()->getSingleRecord(array('email'=>$email));


        if (!$adminUsr) {
            $config = $this->getServiceLocator()->get('Config');
            if ($email =='root' && !empty($config['root-password']) ) {
                // Creamos el usuario root con la contraseña de configuracion
                $passwd = $config['root-password'];
                $email = (empty($config['root-email']))? '' : $config['root-email'];
                $rootUser = new Usuario();
                $rootUser
                    ->setName('root')
                    ->setLastname('root')
                    ->setPassword($passwd,TRUE)
                    ->setEmail($email)
                    ->setRol(Admin::ADMIN_ROOT_ROLE);
                $id = $this->getCommonContentsModel()->getAdminTable()->updateRecord($rootUser);
                $rootUser->setId($id);
                $adminUsr = $rootUser;
            } else {
                $this->flashMessenger()->addErrorMessage('Usuario o contraseña no válido');
                return 1; //error
            }
        }

        // Se comprueba passwd
      if ( !$adminUsr->checkPasswd($passwd) )  {
            $this->flashMessenger()->addErrorMessage('Usuario o contraseña no válido');
            return 2; //error
        }

        // Se registra el login

        $logReqId = 0;

        // No hay errores => creamos la sesión y retornamos:
        $this->loadAdminSessionData($adminUsr->getIdUser(),$logReqId,$adminUsr->getName(), $adminUsr->getRol());

        return 0;
    }    



    public function loginAction(){
        $loginForm = new AdminLoginForm();  

        if ( $this->getRequest()->isPost() ) {
            $formData = $this->getRequest()->getPost();
            $errorId = 1;
            $loginForm->setData($formData);
            if ($loginForm->isValid()) {
                $errorId = $this->processLoginForm($formData);    
            } 

            if ($errorId==0) {
                // Login:
                return $this->redirect()->toUrl('/admin/foro');
            }
        }

        $arrVariables = array(
            'loginForm' => $loginForm
        );
        $viewModel = new ViewModel();
        $viewModel->setTemplate('backend/login/login')->setVariables($arrVariables);

        return $viewModel;
    }

    public function logoutAction(){

        if ( $this->getAdminSession() ) {
            if (isset($this->adminUserSession->loginReqId) ) {

            }
            $sessionManager = $this->adminUserSession->getManager();
            $sessionManager->destroy();
        }
        return $this->redirect()->toUrl('/mpfnimda/login'); 
    }


    //condiciones archivos
    protected function compruebaCondicionesArchivos($size, $extension, $file){
        $error = '';
        if(!empty($file)) {
            $adapter = new Http();
            $size = new Size(array('max' => $size));
            $is_image = new Extension($extension);
            $adapter->setValidators(array($size, $is_image), $file);
            if (!$adapter->isValid($file)) {
                $error_file = true;
                $adapter_errors = $adapter->getMessages();
                $errors = array();
                foreach($adapter_errors as $key=>$row) {
                    $errors[] = $row;
                }
                if(!empty($errors)) {
                    $error = $errors;
                }

            }
        }
        return $error;
    }
}

