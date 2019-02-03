<?php

namespace Application\Controller;

use Model\DataModel\FileManPrivate;
use Model\Form\AdminLoginForm;
use Zend\File\Transfer\Adapter\Http;
use Zend\Mime\Mime;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Validator\File\Extension;
use Zend\Validator\File\Size;
use Zend\View\Model\ViewModel;

use Model\DataModel\Sanitizer;
use Model\DataModel\DateTime;

use Model\BusinessModel\BusinessModel;

use Zend\Session\Container;

use Zend\Mail;
use Zend\Mail\Transport\Smtp as SmtpTransport;
use Zend\Mail\Transport\SmtpOptions;

class ApplicationController extends AbstractActionController
{
    const APP_ZONE_FRONT   = 'APP_ZONE_FRONT';
    const APP_ZONE_MEMBERS = 'APP_ZONE_MEMBERS';
    const APP_ZONE_BACKEND = 'APP_ZONE_BACKEND';

    const EXPS_PER_PAGE = 20;

    // Globales:
    // -------------------------------------------------------------------

    public static $LANG_DATA;  // Array currentCap, currentId y langsArr => [id=>[id,caption,label],...]

    // -------------------------------------------------------------------

    protected $hostAndProtocol;

    // -------------------------------------------------------------------
    // Model:

    protected $businessModel;
    public function getBusinessModel()
    {
        if (!$this->businessModel) {
            $sm = $this->getServiceLocator();
            $this->businessModel = $sm->get('BusinessModel');
        }

        return $this->businessModel;
    }

    protected $contentsModel;
    public function getCommonContentsModel()
    {
        if (!$this->contentsModel) {
            $sm = $this->getServiceLocator();
            $this->contentsModel = $sm->get('CommonContentsModel');
        }
    
        return $this->contentsModel;
    }

    // -------------------------------------------------------------------
    // Config:

    protected function getHostAndProtocol()
    {
        if (empty($this->hostAndProtocol)) {
            $config = $this->getServiceLocator()->get('Config');       
            $this->hostAndProtocol = $config['http-prefix'].$config['host-name'];
        }

        return $this->hostAndProtocol;
    }

    protected function getAppBaseUrl( $zone = ApplicationController::APP_ZONE_FRONT )
    {
        $zoneUrl = '';
        if ($zone == ApplicationController::APP_ZONE_MEMBERS) $zoneUrl = '/usr'; 
        if ($zone == ApplicationController::APP_ZONE_BACKEND) $zoneUrl = '/mpfnimda';

        return $this->getHostAndProtocol().$zoneUrl;
    }

    protected function setCurrentLanguage( $languageCaption )
    {
        $strLocale = 'es_ES';

        switch ($languageCaption) {
            case 'EN':
                $strLocale = 'en_EN'; 
                break;
            default:
                $strLocale = 'es_ES';
        }

        $translator = $this->getServiceLocator()->get('translator');
        $translator->setLocale($strLocale);
    }

    // -------------------------------------------------------------------
    // Send Mail:

    protected function sendMail( $email, $strAddTo, $subject, $content )
    {
        $config = $this->getServiceLocator()->get('Config');
        if (empty($config['mail-server'])) {
            return 0;
        }
        if (!empty($config['email-test-user'])) {
            $email = $config['email-test-user'];
        }

        // Setup SMTP transport using PLAIN authentication
        $transport = new SmtpTransport();
        $options   = new SmtpOptions($config['mail-server']);
        $transport->setOptions($options);

        DateTime::setTimeZone();
        $mail = new Mail\Message();
        $mail->setFrom($config['email-test-user'], 'Gestión de Moodle');
        $mail->addTo($email, $strAddTo );
        $mail->setSubject($subject);
        $bodyPart = new \Zend\Mime\Message();
        $bodyMessage = new \Zend\Mime\Part($content);
        $bodyMessage->type = 'text/html';
        $bodyPart->setParts(array($bodyMessage));
        $mail->setBody($bodyPart);
        $mail->setEncoding('UTF-8');

        try {
            $transport->send($mail);
        } catch(\Exception $e) {
            return 0;
        }

        return 1;
    }


    protected function sendMailwithTemplate( $email, $strAddTo,  $subject, $content, $title, $attachment )
    {

        $config = $this->getServiceLocator()->get('Config');

        $viewHelperManager = $this->getServiceLocator()->get('ViewHelperManager');
        $translator = $this->getServiceLocator()->get('ViewHelperManager')->get('translate');
        $localUrl = $viewHelperManager->get('localUrl');


       // $linkProyecto = $this->getAppBaseUrl().'/donations/donate?lang='.$language['caption'].'&projectId='.$donation['project_id'];

        if (empty($config['mail-server'])) {
            return 0;
        }

        // Setup SMTP transport using PLAIN authentication
        $transport = new SmtpTransport();
        $options   = new SmtpOptions($config['mail-server']);
        $transport->setOptions($options);

        $mail = new Mail\Message();
        $mail->setFrom('no-reply@'.$config['host-name'], 'Gestion de Proyectos');
        $mail->addTo($email);
        $mail->setSubject($subject);
        $mail->setEncoding("UTF-8");

        $view = new ViewModel(array(
            'title'  => $subject,
            'message' => $content,

        ));
        $view->setTemplate('application/index/emailTemplate');
        $view->setTerminal(true);
        $serviceManager = $this->getServiceLocator();
        $emailBody = $serviceManager->get('ViewRenderer')->render($view);

        $body =  new \Zend\Mime\Message();

        $bodyMessage = new \Zend\Mime\Message();
        $bodyPart = new \Zend\Mime\Part($emailBody);
        $bodyPart->type = 'text/html';
        $bodyMessage->setParts(array($bodyMessage));

        $body->addPart($bodyPart);

         if ($attachment) {
             $attachmentFile = file_get_contents('public'.$attachment);
             $attachmentPart = new \Zend\Mime\Part($attachmentFile);
             $attachmentPart->filename = basename($attachment);
             $attachmentPart->type = Mime::TYPE_OCTETSTREAM;
             $attachmentPart->encoding = Mime::ENCODING_BASE64;
             $attachmentPart->disposition = Mime::DISPOSITION_ATTACHMENT;

             $body->addPart($attachmentPart);

         }

        $mail->setBody($body);

        try {
            $transport->send($mail);
            return true;
        } catch(\Exception $e) {
            return false;
        }

    }

    // -------------------------------------------------------------------
    // Session:

    protected $userSession;
    public function getUserSession()
    {
        if (!$this->userSession) {
            $this->userSession = new Container('user_session');
        }

        return $this->userSession;
    }

    protected function loadSessionData( $expId, $surveyData )
    {

        $this->getUserSession();

        $this->userSession->expId = $expId;
        $this->userSession->surveyData = $surveyData;

        return $this->userSession;
    }    

    protected $adminUserSession;
    public function getAdminSession()
    {
        if (!$this->adminUserSession) {
            $this->adminUserSession = new Container('admin_session');
        }

        return $this->adminUserSession;
    }

   public function getAdminRole()
    {
        $table = $this->getCommonContentsModel()->getUsuarioTable();
        $currentAdmin  = $table->getRecordByIdUser($this->getAdminSession()->adminId);

        if(!$_SESSION['admin_session'] || !$this->getAdminSession()->adminId){
            $this->flashMessenger()->addErrorMessage('la sesión ha caducado, vuelva a loguearse');

            return $this->redirect()->toUrl('/mpfnimda/login');



        } else {
            $rol = $currentAdmin->getRol();
        }


        return $rol;
    }

    public function getUserRole()
    {
        $table = $this->getCommonContentsModel()->getUsuarioTable();
        $currentAdmin  = $table->getRecordById($this->getAdminSession()->adminId);

        $rol = $currentAdmin->getRoleType();

        return $rol;
    }

    protected function loadAdminSessionData( $adminId, $loginReqId, $userName, $role)
    {
        $this->getAdminSession();

        $this->adminUserSession->adminId = $adminId;
        $this->adminUserSession->loginReqId = $loginReqId;
        $this->adminUserSession->userName = $userName;
        $this->adminUserSession->role = $role;

        return $this->adminUserSession;
    }

    protected function setSessionToken( $isAdminSession )
    {
        $token = md5(uniqid(rand(), true));

        $session = ($isAdminSession)? $this->getAdminSession() : $this->getUserSession();

        $session->sesToken = $token;

        return $token;
    }

    protected function getSessionToken( $isAdminSession )
    {
        $session = ($isAdminSession)? $this->getAdminSession() : $this->getUserSession();

        if (empty($session->sesToken)) {
            return $this->setSessionToken($isAdminSession);
        } else {
            return $session->sesToken;    
        }
    }

    protected function getAndcheckToken( $isAdminSession, $token, $echoMsg = true )
    {
        $token   = (string) Sanitizer::sanitizeInput($token);

        if ( $this->getSessionToken( $isAdminSession ) == $token ) {
            return true;
        }

        if ($echoMsg) {
            $this->flashMessenger()->addErrorMessage('El token de sesión no se corresponde con el actual');
        }
        

        return false;
    }


    protected function getAndcheckTokenFromQuery( $isAdminSession, $echoMsg = true )
    {
        $token = $this->params()->fromQuery('token');

        return $this->getAndcheckToken( $isAdminSession, $token, $echoMsg );
    }

    // -------------------------------------------------------------------
    // Paginator array:

    protected function getPaginatorDataArray( $totalRecords, $currentPage, $actionUrl, $limit )
    {

        $offset   = 0;
        if (!in_array($limit, array(20,40,80))) {
            $limit = 20;
        }   

        $totalPages = (int)($totalRecords/$limit);
        $totalPages = ($totalRecords%$limit)? $totalPages+1 : $totalPages;

        if ( $currentPage <= 0 || $currentPage > $totalPages ) {
            $currentPage = 1;
            $offset  = 0;
        } else {
            $offset  = ($currentPage - 1) * $limit;
        }

        $arrPag = array(
            'offset'       => $offset,
            'limit'        => $limit,
            'currentPage'  => $currentPage,
            'totalPages'   => $totalPages,
            'actionUrl'    => $actionUrl,
            'totalRecords' => $totalRecords
        );

        return $arrPag;
    }

    protected function checkBrowser()
    {
         $browser = array(
            'version'   => '0.0.0',
            'majorver'  => 0,
            'minorver'  => 0,
            'build'     => 0,
            'name'      => 'unknown',
            'useragent' => ''
        );

        $browsers = array(
            'firefox', 'msie', 'opera', 'chrome', 'safari', 'mozilla', 'seamonkey', 'konqueror', 'netscape',
            'gecko', 'navigator', 'mosaic', 'lynx', 'amaya', 'omniweb', 'avant', 'camino', 'flock', 'aol'
        );

        if (isset($_SERVER['HTTP_USER_AGENT'])) {
            $browser['useragent'] = $_SERVER['HTTP_USER_AGENT'];
            $user_agent = strtolower($browser['useragent']);
            foreach($browsers as $_browser) {
                if (preg_match("/($_browser)[\/ ]?([0-9.]*)/", $user_agent, $match)) {
                    $browser['name'] = $match[1];
                    $browser['version'] = $match[2];
                    @list($browser['majorver'], $browser['minorver'], $browser['build']) = explode('.', $browser['version']);
                    break;
                }
            }
        }

        return $browser;
    }

    protected function isOldBrowser()
    {
        $browser = $this->checkBrowser();

        if ($browser['name']=='msie' && ($browser['majorver']<10) ) {
            return true;
        }

        return false;
    }

    // -------------------------------------------------------------------
    // Test function:

    protected function showAndDie($var)
    {
        echo '<pre>showAndDie<br>---------------<br>';
        print_r($var);
        echo '<br>---------------<br></pre>';
        die();
    }

    // -------------------------------------------------------------------
    // Logger:

    protected $loggerHandler;
    protected $loggerPaypal;
    protected $loggerWriter;
    protected $loggerServicios;
    protected $loggerTareas;
    protected $logCambiosBack;

    protected function getLogger()
    {
        if (!$this->loggerHandler) {
            DateTime::setTimeZone();
            $this->loggerHandler = new \Zend\Log\Logger;
            $loggerWriter  = new \Zend\Log\Writer\Stream('log/app.log');
            $this->loggerHandler->addWriter($loggerWriter);
        }

        return $this->loggerHandler;
    }

    protected function getLoggerPaypal()
    {
        if (!$this->loggerPaypal) {
            DateTime::setTimeZone();
            $this->loggerPaypal = new \Zend\Log\Logger;
            $loggerWriter  = new \Zend\Log\Writer\Stream('log/paypal.log');
            $this->loggerPaypal->addWriter($loggerWriter);
        }

        return $this->loggerPaypal;
    }

    protected function getLoggerServicios()
    {
        if (!$this->loggerServicios) {
            DateTime::setTimeZone();
            $this->loggerServicios = new \Zend\Log\Logger;
            $loggerWriter  = new \Zend\Log\Writer\Stream('log/servicios.log');
            $this->loggerServicios->addWriter($loggerWriter);
        }

        return $this->loggerServicios;
    }

    protected function getLoggerTareas()
    {
        if (!$this->loggerTareas) {
            DateTime::setTimeZone();
            $this->loggerTareas = new \Zend\Log\Logger;
            $loggerWriter  = new \Zend\Log\Writer\Stream('log/tareas.log');
            $this->loggerTareas->addWriter($loggerWriter);
        }

        return $this->loggerTareas;
    }

    protected function getLoggerCambiosBack()
    {
        if (!$this->logCambiosBack) {
            DateTime::setTimeZone();
            $this->logCambiosBack = new \Zend\Log\Logger;
            $loggerWriter  = new \Zend\Log\Writer\Stream('log/cambiosBack.log');
            $this->logCambiosBack->addWriter($loggerWriter);
        }

        return $this->logCambiosBack;
    }

    protected function _logMsg($type,$msg, $logger) {
        if (is_object($msg) || is_array($msg) ) {
            ob_start();
            var_dump($msg);
            $msg = ob_get_clean();
        }
        $logger->log($type,$msg);
        return $this;
    }

    protected function logError($msg)
    {
        return $this->_logMsg(\Zend\Log\Logger::EMERG,$msg,  $this->getLogger());
    }

    protected function logWarning($msg)
    {
        return $this->_logMsg(\Zend\Log\Logger::WARN,$msg,  $this->getLogger());
    }

    protected function log($msg)
    {
        return $this->_logMsg(\Zend\Log\Logger::DEBUG,$msg,  $this->getLogger());
    }

    protected function logServicios($msg)
    {
        return $this->_logMsg(\Zend\Log\Logger::INFO,$msg, $this->getLoggerServicios());
    }

    protected function logTareas($msg)
    {
        return $this->_logMsg(\Zend\Log\Logger::INFO,$msg, $this->getLoggerTareas());
    }

    protected function logCambiosBack($msg)
    {
        return $this->_logMsg(\Zend\Log\Logger::INFO,$msg, $this->getLoggerCambiosBack());
    }

    // -------------------------------------------------------------------
    // View Models:

    protected function getHeaderVM($module, $arrMenu = NULL, $loginName = '', $sectionName = null, $layoutVar=array())
    {


        $aux = array();
        $arrVariables = array(
            'arrMenu' => $arrMenu,
            'loginName' => $loginName,
            'sectionName' => $sectionName,
        );

        if (!empty($layoutVar)){
            foreach($layoutVar as $key=> $value){
                $arrVariables[$key] = $value;
            }
        }

        $headerVM = new ViewModel();
        $headerVM->setTemplate($module.'/layout/header')->setVariables($arrVariables);
        return $headerVM;
    }

    protected function getFooterVM($module)
    {

        $footerVM = new ViewModel();
        $footerVM->setTemplate($module.'/layout/footer'); //->setVariables($arrVariables);
        return $footerVM;
    }

    protected function layoutViews( $module, $headerVM, $contentVM, $footerVM, $layoutVariables = NULL, $metaTags = NULL, $jsVM = NULL, $includeFBSDK = FALSE )
    {
        $layoutViewModel = new ViewModel();
        if (empty($layoutVariables)) {
            $layoutVariables = array(
                'bodyClass' => '',
                'backgroundClass' => 'background'
            );
        }        

        if ( empty($metaTags) ) {
            $metaTags = array(
                'canonical' => $this->getAppBaseUrl(),
                'seo_title' => 'Gestor de Proyectos',
                'description' => '',
                'keywords' => '',
                'author' => '',
            );        
        }
        $layoutVariables['metaTags'] = $metaTags;        

        $layoutViewModel->setTemplate($module.'/layout/layout')->setVariables($layoutVariables);
        $layoutViewModel->setTerminal(true);

        if (!empty($headerVM)) {
            $layoutViewModel->addChild($headerVM,'header');    
        }

        $layoutViewModel->addChild($contentVM,'content');

        if (!empty($footerVM)) {
            $layoutViewModel->addChild($footerVM,'footer');
        }

        if (!empty($jsVM)) {
            $layoutViewModel->addChild($jsVM,'javascript');
        }

        return $layoutViewModel;
    }   

    protected function layoutError( $module, $errorCode )
    {
        $this->getResponse()->setStatusCode($errorCode);

        $layoutViewModel = new ViewModel();
        $layoutViewModel->setTemplate('error/404');
        $layoutViewModel->setTerminal(TRUE);

        return $layoutViewModel;
    }

    protected function imageResult( $fileName, $type )
    {
        // get image content
        $response = $this->getResponse();


        // open the file in a binary mode
        $fp = fopen($fileName, 'rb');

        ob_start();
        fpassthru($fp);
        $imgContent = ob_get_clean ();

        $response->setContent($imgContent);
        $response
            ->getHeaders()
            ->addHeaderLine('Content-Transfer-Encoding', 'binary')
            ->addHeaderLine('Content-Type', 'image/' . $type)
            ->addHeaderLine('Content-Length', filesize($fileName));

        return $response;
    }

    public function notFoundAction()
    {
        return $this->layoutError('frontend',404);
    }


    public function completaCerosNif($nif){

        $nifFinal = strtoupper($nif);

        //Agrego ceros delante si tiene menos de 9 caracteres
        if (strlen($nifFinal) < 9){
            $numeroCeros = 9 -  strlen($nifFinal);

            for ($i=0; $i<$numeroCeros; $i++){
                $nifFinal = '0'.$nifFinal;
            }
        }

        return $nifFinal;

    }

    public function quitaCerosNif($nif){

        $nifFinal = strtoupper($nif);

        $nifFinal = ltrim($nifFinal, '0');

        return $nifFinal;

    }

    public function validaNIF($nif){
        $nif = strtoupper($nif);

        //Agrego ceros delante si tiene menos de 9 caracteres
        if (strlen($nif) < 9){
            $numeroCeros = 9 -  strlen($nif);

            for ($i=0; $i<$numeroCeros; $i++){
                $nif = '0'.$nif;
            }
        }

        $nifRegEx = '/^[0-9]{8}[A-Z]$/i';

        $letras = "TRWAGMYFPDXBNJZSQVHLCKE";

        if (preg_match($nifRegEx, $nif)) return ($letras[(substr($nif, 0, 8) % 23)] == $nif[8]);

        else return false;

    }

    public function valida_nie($nie) {

        $nie = strtoupper($nie);

        //Agrego ceros delante si tiene menos de 9 caracteres
        if (strlen($nie) < 9){
            $numeroCeros = 9 -  strlen($nie);

            for ($i=0; $i<$numeroCeros; $i++){
                $nie = '0'.$nie;
            }
        }

        $nieRegEx = '/^[XYZ]([0-9]{7})([A-Z])$/i';


        $letras = "TRWAGMYFPDXBNJZSQVHLCKE";

        // Si es un NIE hay que cambiar la primera letra por 0, 1 ó 2 dependiendo de si es X, Y o Z.
        $letraInicial = substr($nie, 0, 1);
        $letraInicialNumero = str_replace(array('X', 'Y', 'Z'), array(0, 1, 2), $letraInicial);

        $numeros = substr($nie, 1, 8);

        $numeroFinal = $letraInicialNumero.$numeros;

        if (preg_match($nieRegEx, $nie)) return ($letras[(substr($numeroFinal, 0, 8) % 23)] == $nie[8]);
        else return false;
    }

    public function renderLayout($contentVM,$jsVM, $metaTags=array(), $layoutVariables=array())
    {
        $headerVM  = $this->getHeaderVM('frontend', $arrMenu = NULL, $loginName = '', $sectionName = null, $layoutVariables );
        $footerVM  = $this->getFooterVM('frontend');

        return $this->layoutViews( 'frontend', $headerVM, $contentVM,  $footerVM, $layoutVariables = NULL, $metaTags = NULL, $jsVM );
    }


    public function getLanguageFromSession(){
        $locale = $_SESSION['language']->language;
        $localeArray = explode('_', $locale);
        $language = $localeArray[0];

        return $language;
    }

}

