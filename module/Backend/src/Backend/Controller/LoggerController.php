<?php

namespace Backend\Controller;

use Model\CommonContentsModel\Admin;
use Model\DataModel\Sanitizer;
use Model\Form\AdminForm;
use Zend\Validator\Db\NoRecordExists;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;


class LoggerController extends BackendController
{
    public function indexAction(){
        $archivo = 'log/cambiosBack.log';
        $textoFinal = '';
        $fin = false;

        $fp = fopen($archivo,'a+');

        $puntero = Sanitizer::sanitizeIntInput($this->params()->fromQuery('puntero'));
        if ($puntero != 0){
            fseek($fp, $puntero);
        }else{
            fseek($fp, -1, SEEK_END);
        }
        $punteroPosicion = ftell($fp);

        $vuelta = 0;
        $char = fgetc($fp);

        $line = '';
        while ($char !== false && $vuelta<20) {
            if ($char === "\n"){
                $textoFinal = $textoFinal . $line .'</br>';
                $vuelta++;
                $line = '';
            }else{
                $line = $char . $line;
            }
            fseek($fp, $punteroPosicion--);
            $char = fgetc($fp);
        }
        $punteroPosicion = ftell($fp);

        fclose($fp);

        if ($punteroPosicion == 1){
            $fin = true;
        }

        if ($puntero == 0) {
            $arrVariables = array(
                'texto'     => $textoFinal,
                'punteroPosicion' => $punteroPosicion,
                'fin' => $fin
            );
            $contentVM = new ViewModel();
            $contentVM->setTemplate('backend/logger/index')->setVariables($arrVariables);
            return $this->layoutBackendView($contentVM, 10);
        }else{
            $result['puntero'] = $punteroPosicion;
            $result['texto'] = $textoFinal;
            $result['fin'] = $fin;

            return new JsonModel($result);
        }
    }

    public function downloadLogCambiosAction(){
        $archivo = 'log/cambiosBack.log';
        $this->downloadLog($archivo);

    }

    protected function downloadLog($archivo)
    {

        if (file_exists($archivo)) {
            header('Pragma: public');   // required
            header('Expires: 0');       // no cache
            header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
            //header('Last-Modified: '.gmdate ('D, d M Y H:i:s', filemtime ($file_name)).' GMT');
            header('Cache-Control: private',false);
            header('Content-Type: application/force-download');
            header('Content-Disposition: attachment; filename="'.basename($archivo).'"');
            header('Content-Transfer-Encoding: binary');
            header('Content-Length: '.filesize($archivo));    // provide file size
            header('Connection: close');
            readfile($archivo);
            exit;
        }
    }


}

