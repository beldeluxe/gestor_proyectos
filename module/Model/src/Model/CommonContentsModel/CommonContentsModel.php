<?php

namespace Model\CommonContentsModel;

use Model\BasicTableGateway;

use Model\CommonContentsModel\Admin;
use Model\CommonContentsModel\Language;
use Model\CommonContentsModel\MenuEntry;
use Model\CommonContentsModel\MenuEntryLabel;
use Model\CommonContentsModel\Page;
use Model\CommonContentsModel\PageContent;
use Model\CommonContentsModel\MediaContainer;
use Model\CommonContentsModel\MediaElementContainer;
use Model\CommonContentsModel\MediaElement;
use Model\CommonContentsModel\MediaElementLabel;
use Model\CommonContentsModel\PageRole;
use Model\CommonContentsModel\UserRole;

use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Select;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Adapter\Adapter;

class CommonContentsModel
{

    protected $sm;
    protected $dbAdapter;

    /**
     * @param mixed $dbAdapter
     */
    public function setDbAdapter($dbAdapter)
    {
        $this->dbAdapter = $dbAdapter;
    }

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

    // GENERIC SQL STATEMENT EXECUTION:


    /**
        * executeStatement
        *
        * Ejecuta sqlQueryStr directamente en el adaptador
        *
        * Para consultas complejas que involucren joins de varias tablas 
        *
        *
        * @param  string $sqlQueryStr --> consulta completa en sql
        * @param  string $singleField --> cuando queramos el valor de un único campo
        * @return array --> array donde cada registro es un array de los campos en la consulta
        *                 ó array de valores para un único campo (especificado en $singleField)
    */
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

        protected $adminTable;
        public function getAdminTable()
        {
            if (!$this->adminTable) {
                $this->adminTable = $this->sm->get('Model\AdminTable');
            }
            return $this->adminTable;
        }


    protected $usuarioTable;
    public function getUsuarioTable()
    {
        if (!$this->usuarioTable) {
            $this->usuarioTable = $this->sm->get('Model\UsuarioTable');
        }
        return $this->usuarioTable;
    }


    protected $noticiaTable;

    public function getNoticiaTable()
    {
        if (!$this->noticiaTable) {
            $this->noticiaTable = $this->sm->get('Model\NoticiaTable');
        }
        return $this->noticiaTable;
    }

    protected $menuEntryTable;
    public function getMenuEntryTable()
    {
        if (!$this->menuEntryTable) {
            $this->menuEntryTable = $this->sm->get('Model\MenuEntryTable');
        }
        return $this->menuEntryTable;
    }

    protected $menuEntryLabelTable;
    public function getMenuEntryLabelTable()
    {
        if (!$this->menuEntryLabelTable) {
            $this->menuEntryLabelTable = $this->sm->get('Model\MenuEntryLabelTable');
        }
        return $this->menuEntryLabelTable;
    }

    protected $pageTable;
    public function getPageTable()
    {
        if (!$this->pageTable) {
            $this->pageTable = $this->sm->get('Model\PageTable');
        }
        return $this->pageTable;
    }


    protected $pageTermsTable;
    public function getPageTermsTable()
    {
        if (!$this->pageTermsTable) {
            $this->pageTermsTable = $this->sm->get('Model\PageTermTable');
        }
        return $this->pageTermsTable;
    }

    protected $termTable;
    public function getTermTable()
    {
        if (!$this->termTable) {
            $this->termTable = $this->sm->get('Model\TermTable');
        }
        return $this->termTable;
    }

    protected $termsDefinitionTable;
    public function getTermDefinitionTable()
    {
        if (!$this->termsDefinitionTable) {
            $this->termsDefinitionTable = $this->sm->get('Model\TermDefinitionTable');
        }
        return $this->termsDefinitionTable;
    }


    protected $pageContentTable;
    public function getPageContentTable()
    {
        if (!$this->pageContentTable) {
            $this->pageContentTable = $this->sm->get('Model\PageContentTable');
        }
        return $this->pageContentTable;
    }

    protected $pageRoleTable;
    public function getPageRoleTable()
    {
        if (!$this->pageRoleTable) {
            $this->pageRoleTable = $this->sm->get('Model\PageRoleTable');
        }
        return $this->pageRoleTable;
    }

    protected $userRoleTable;
    public function getUserRoleTable()
    {
        if (!$this->userRoleTable) {
            $this->userRoleTable = $this->sm->get('Model\UserRoleTable');
        }
        return $this->userRoleTable;
    }

    protected $mediaContainerTable;
    public function getMediaContainerTable()
    {
        if (!$this->mediaContainerTable) {
            $this->mediaContainerTable = $this->sm->get('Model\MediaContainerTable');
        }
        return $this->mediaContainerTable;
    }

    protected $mediaElementContainerTable;
    public function getMediaElementContainerTable()
    {
        if (!$this->mediaElementContainerTable) {
            $this->mediaElementContainerTable = $this->sm->get('Model\MediaElementContainerTable');
        }
        return $this->mediaElementContainerTable;
    }


    protected $mediaElementTable;
    public function getMediaElementTable()
    {
        if (!$this->mediaElementTable) {
            $this->mediaElementTable = $this->sm->get('Model\MediaElementTable');
        }
        return $this->mediaElementTable;
    }

    protected $mediaElementLabelTable;
    public function getMediaElementLabelTable()
    {
        if (!$this->mediaElementLabelTable) {
            $this->mediaElementLabelTable = $this->sm->get('Model\MediaElementLabelTable');
        }
        return $this->mediaElementLabelTable;
    }

    protected $languageTable;
    public function getLanguageTable()
    {
        if (!$this->languageTable) {
            $this->languageTable = $this->sm->get('Model\LanguageTable');
        }
        return $this->languageTable;
    }

    protected $adminLoginRequestTable;
    public function getAdminLoginRequestTable()
    {
        if (!$this->adminLoginRequestTable) {
            $this->adminLoginRequestTable = $this->sm->get('Model\AdminLoginRequestTable');
        }
        return $this->adminLoginRequestTable;
    }

    protected $adminOldPasswordsTable;
    public function getAdminOldPasswordsTable()
    {
        if (!$this->adminOldPasswordsTable) {
            $this->adminOldPasswordsTable = $this->sm->get('Model\AdminOldPasswordsTable');
        }
        return $this->adminOldPasswordsTable;
    }

    protected $countryTable;
    public function getCountryTable()
    {
        if (!$this->countryTable) {
            $this->countryTable = $this->sm->get('Model\CountryTable');
        }
        return $this->countryTable;
    }

    // -------------------------------------------------------------------
    // Funciones:

    // IDIOMAS:

    public function getLangData( $queryLangCaption )
    {
        $langsTable  = $this->getLanguageTable();

        if (empty($queryLangCaption)) {
            $queryLangCaption = Language::DEFAULT_LANG_CAPTION;
        }

        $language = $langsTable->getSingleRecord(array('caption'=>$queryLangCaption));

        if (!$language) {
            $language = $langsTable->getRecordById(Language::DEFAULT_LANG_ID);
        }

        if (!$language) { // Si la BD no está inicializada con idiomas creo el default...
            $language = new Language();
            $language
                ->setId(Language::DEFAULT_LANG_ID)
                ->setCaption(Language::DEFAULT_LANG_CAPTION)
                ->setLabel('Español');
            $langsTable->updateRecord($language, NULL, TRUE );
        }

        return array(
            'currentCap' => $language->getCaption(),
            'currentId'  => $language->getId(),
            'langsArr'   => $langsTable->fetchRecordsArr()
        );
    }

    //LoginRequest

    public function newLoginRequest(AdminLoginRequest $request){
        $lastValue = $this->getAdminLoginRequestTable()->updateRecord($request);
        return $lastValue;
    }

    public function updateLoginRequestToLogout(AdminLoginRequest $request){
        $this->getAdminLoginRequestTable()->updateRecord($request);
    }

    public function getAdminLoginRequest($requestId){
        $rowSet = $this->getAdminLoginRequestTable()->getSingleRecord(array('id' => $requestId));
        return $rowSet;
    }

    // MENÚS:

    public function getMenuEntryElementsByParentId( $menuEntryId, $type = '' )
    {

        $sqlQueryStr = ( $menuEntryId>0 )? 'parent_element_id='.$menuEntryId : 'parent_element_id is NULL';

        if ($type != ''){
            $sqlQueryStr = $sqlQueryStr. ' AND type = "'.$type.'"';
        }

        return $this->getMenuEntryTable()->fetchRecords( $sqlQueryStr, 0, 0, $order = 'order ASC' );
    }


    public function _getMenuEntriesArray( $menuEntryElement, $languageId, $elementType, &$flatArray, $level )
    {
        $menuEntryElementLabel = 
            $this->getMenuEntryLabelTable()
                ->getSingleRecord( array( 'menu_entry_id'=>$menuEntryElement->getId(), 'language_id' => $languageId ));

        $entryLabel = (!empty($menuEntryElementLabel))? $menuEntryElementLabel->getLabel() : '';

        $arrResult = $menuEntryElement->getDataArray(true);

        $arrResult['label'] = $entryLabel;
        $arrResult['level'] = $level;
        $level++;

        if ( !is_array($flatArray) ) {
            $arrResult['subElements'] = array();
        } else {
            if (empty($elementType) || ($elementType==$menuEntryElement->getType())) {
                $flatArray[] = $arrResult;
            }
        } 

        if ( $menuEntryElement->getType()==MenuEntry::TYPE_MENU ) {
            
            $resultSet = $this->getMenuEntryElementsByParentId( $menuEntryElement->getId() );

            $subElements = array();
            foreach ($resultSet as $subElement) {
                $subElements[] = $this->_getMenuEntriesArray( $subElement, $languageId, $elementType, $flatArray, $level );
            }

            if (!is_array($flatArray)) {
                $arrResult['subElements'] = $subElements;    
            }
        }

        return $arrResult;
    }

    /**
        * Recibe la etiqueta del elemento menú 
        *
        * Retorna array con los campos de un registro del menú en la tabla menu_entries
        *
        * Al registro se le añaden uno dos campos*:
        * 'label' => con la etiqueta obtenida de MenuEntryLabel en el idioma corresp.
        * 'level' => con el nivel de profundidad en el árbol
        * 'subElements' => un array donde cada elemento es un registro como éste mismo
        *   definiendose así una estructura de árbol
        * 
        * *Dependiendo del campo $returnFlatArray --> si 
        *
        * @param  string  $menuName
        * @param  string  $elementType --> sólo se retornarán las menu entries de ese tipo
        *                                   P. ej: 'MENU'
        * @param  boolean $returnFlatArray --> si se pone a true no se incluirá el campo 
        *                                      subElements en los registros, sino que se
        *                                      retornará un array plano con todos los
        *                                      registros según se hayan recorrido
        * @return array 
    */
    public function getMenuEntriesArray( $menuName, $languageId = Language::DEFAULT_LANG_ID, $returnFlatArray = false, $elementType = '' ) {

        $arrResult = array();

        $menuElement = 
            $this->getMenuEntryTable()->getSingleRecord(
                array('type' => 'MENU', 'parent_element_id is NULL', 'menu_name' => $menuName ) );

        if (!$menuElement) { return array(); }

        $flatArray = NULL;

        if ($returnFlatArray) {
            $flatArray = array();
        }

        $arrResult = $this->_getMenuEntriesArray( $menuElement, $languageId, $elementType, $flatArray, 0 );

        if ($returnFlatArray) {
            return $flatArray;
        }

        return $arrResult;
    }


    /**
        * Recibe un id de página 
        *
        * Retorna un array con todos los menú entries desde el root hasta el elemento
        * que apunta a dicha página
        *
        * @param  int     $pageId
        * @return array   --> array de objetos MenuEntry
    */
    public function getMenuEntriesBreadCrumbs( $pageId  ) {

        $arrResult = array();

        $menuEntryTable = $this->getMenuEntryTable();

        $menuEntry = $menuEntryTable->getSingleRecord('target_page_id = ' . $pageId);

        while ($menuEntry) {
            $arrResult[] = $menuEntry;
            $menuEntryParentElementId = $menuEntry->getParentElementId();
            $menuEntry = $menuEntryTable->getSingleRecord('id = ' . $menuEntryParentElementId);
        }

        return array_reverse($arrResult);
    }

    // ADMINS:

    public function getOldPasswordsForAdmin( $adminId, $limit = 3 )
    {
        $arrResult = array();

        $resultSet =
            $this->getAdminOldPasswordsTable()->fetchRecords( "admin_id = $adminId", 0, $limit,'password_modif_date DSC' );

        foreach ($resultSet as $reg) {
            $arrResult[] = $reg->getPassword();
        }            

        return $arrResult;
    }


    // MEDIA ELEMENTS:

    public function updateMediaElementLabel( $element, $label, $type, $languageId = Language::DEFAULT_LANG_ID, $isVisible = 1 )
    {
        $elementId = $element->getId();

        $elementLabel = $this->getMediaElementLabelTable()->getSingleRecord(
            array( 'media_element_id' => $elementId, 'language_id' => $languageId, 'type' => $type )
        );
        
        if (!$elementLabel) {
            $elementLabel = new MediaElementLabel();
        }            

        $elementLabel
            ->setMediaElementId($elementId)
            ->setLanguageId($languageId)
            ->setLabel($label)
            ->setType($type)
            ->setIsVisible($isVisible);

        $this->getMediaElementLabelTable()->updateRecord($elementLabel);
    }

    /**
        * updateMediaElementInContainer
        *
        * @param  MediaElementContainer $mediaContainer --> contenedor
        * @param  MediaElement $element --> elemento que vamos a incluir/actualizar 
        * @param  int $order --> orden del elemento en el contenedor
        * @param  int $is_visible --> 1 si está visible o 0 si no
    */
    public function updateMediaElementInContainer( $mediaContainer, $element, $order = 0, $is_visible = 1 )
    {
        $mediaElementInContainerRel = $this->getMediaElementContainerTable()->
                getSingleRecord(array(
                    'container_id'=>$mediaContainer->getId(),
                    'element_id'  =>$element->getId()
                ));

        if (!$mediaElementInContainerRel) {
            $mediaElementInContainerRel = new MediaElementContainer();
            $mediaElementInContainerRel->setContainerId($mediaContainer->getId());
            $mediaElementInContainerRel->setElementId($element->getId());
        }

        if ($order == 0) {
            $order = $this->getMediaElementContainerTable()->getLastInsertValue();
        }

        $mediaElementInContainerRel->setOrderInContainer($order);
        $mediaElementInContainerRel->setIsVisible($is_visible);
        $this->getMediaElementContainerTable()->updateRecord($mediaElementInContainerRel);
    }

    /**
        * updateMediaElementInContainerNamed
        * 
        * Lo mismo que updateMediaElementInContainer pero pasando el nombre del contenedor,
        * lo localiza o lo crea si no existía y luego llama a updateMediaElementInContainer
        *
        * @param  string $containerName --> nombre del contenedor, p. ej. "MAIN SLIDER"
        * @param  MediaElement $element --> elemento que vamos a incluir/actualizar 
        * @param  int $order --> orden del elemento en el contenedor
        * @param  int $is_visible --> 1 si está visible o 0 si no
    */
    public function updateMediaElementInContainerNamed( $containerName, $element, $order = 0, $is_visible = 1 )
    {
        $mediaContainer = 
            $this->getMediaContainerTable()->getSingleRecord(array('container_name'=>$containerName));

        if (!$mediaContainer) { 
            $mediaContainer = new MediaContainer();
            $mediaContainer->setContainerName($containerName);
            $mediaContainer->setId( $this->getMediaContainerTable()->updateRecord($mediaContainer) );
        }

        $this->updateMediaElementInContainer($mediaContainer,$element,$order,$is_visible);
    }

    /**
        * removeElementFromContainerNamed
        * 
        * @param  string $containerName --> nombre del contenedor, p. ej. "MAIN SLIDER"
        * @param  MediaElement $element --> elemento que vamos extraer del contenedor
    */
    public function removeElementFromContainerNamed( $containerName, $element )
    {
        $mediaContainer = 
            $this->getMediaContainerTable()->getSingleRecord(array('container_name'=>$containerName));

        if (!$mediaContainer) return;

        $mediaElementInContainerRel = $this->getMediaElementContainerTable()->
            deleteRecord(array(
                'container_id'=>$mediaContainer->getId(),
                'element_id'=>$element->getId() ) );
    }

    /**
        * getMediaElementsForContainer
        * 
        * Retorna un array de registros de media_elements, cada registro es 
        * un array asociativo, con los campos de la tabla al que se le añade 
        * un campo 'labels' que es un array clave: type -> valor: etiqueta 
        * con el valor de la etiqueta para cada tipo
        *
        * @param  string $containerName --> nombre del contenedor, p. ej. "MAIN SLIDER"
        * @param  MediaElement $element --> elemento que vamos a incluir/actualizar 
        * @param  int $order --> orden del elemento en el contenedor
        * @param  int $is_visible --> 1 si está visible o 0 si no
        * @return array 
    */
    public function getMediaElementsForContainer( $containerName, $ignoreIsVisible = false, $languageId = Language::DEFAULT_LANG_ID, $elementId = 0, $offset = 0, $limit = 0 )
    {

        $arrResult = array();

        $mediaContainer = 
            $this->getMediaContainerTable()->getSingleRecord(array('container_name'=>$containerName));

        if (!$mediaContainer) return $arrResult;

        $sqlQueryStr = 
            'SELECT media_elements.*' .
            ' FROM media_elements LEFT OUTER JOIN media_element_container' .
            ' ON media_elements.id = media_element_container.element_id' .
            ' WHERE media_element_container.container_id ="' . $mediaContainer->getId() .'"';

        if (!$ignoreIsVisible) {
            $sqlQueryStr .= ' AND media_element_container.is_visible = 1';
        }

        if ($elementId>0) {
            $sqlQueryStr .= ' AND media_elements.id ="' . $elementId .'"';   
        }

        $sqlQueryStr .= ' ORDER by media_element_container.order_in_container ASC';

        $mediaElements  = $this->executeStatement( $sqlQueryStr );

        $arrResult = array();

        foreach ($mediaElements as $elementArr) {

            $elementArr['labels'] = array();

            $elementLabels = $this->getMediaElementLabelTable()
                ->fetchRecords( "media_element_id=" . $elementArr['id'] . " AND language_id = " . $languageId );

            foreach ($elementLabels as $label) {
                $elementArr['labels'][$label->getType()] = $label->getLabel();
            }

            if ($elementId>0) {
                return $elementArr;                
            }

            $arrResult[] = $elementArr;
        }        

        return $arrResult;
    }

    public function updateLabelForElement( $elementId, $labelType, $labelText, $languageId = Language::DEFAULT_LANG_ID )
    {
        $table = $this->getMediaElementLabelTable();

        $label = $table->getSingleRecord(
            array( 'media_element_id' => $elementId, 'language_id' => $languageId, 'type' => $labelType )
        );

        if ($label) {
            if (empty($labelText)) {
                $table->deleteRecordById($label->getId());
                return;
            }
        } else {
            $label = new MediaElementLabel();
            $label
                ->setMediaElementId($elementId)
                ->setLanguageId($languageId)
                ->setType($labelType);
        }

        $label->setLabel($labelText);

        $table->updateRecord($label);
    }

    // PAGE & PAGE CONTENT:

    /**
        * getPageContent obtiene un PageContent para idioma, 
        * si createIfNotExists=true crea el contenido
        *
        * @param  int $pageId --> nombre de la página en tabla "page"
        * @param  int $languageId
        * @return PageContent 
    */
    public function getPageContent( $pageId, $languageId = Language::DEFAULT_LANG_ID , $createIfNotExists = false ) 
    {

        $pageContent = $this->getPageContentTable()        
            ->getSingleRecord( array( 'page_id'=>$pageId, 'language_id'=>$languageId ) );

        if (!$pageContent && $createIfNotExists) {
            $pageContent = new PageContent();
            $pageContent
                ->setPageId($pageId)
                ->setLanguageId($languageId);
        }

        return $pageContent;
    }

    /**
        * getContentPageNamed
        * Obtiene un PageContent para un nombre e idioma, 
        * si createIfNotExists crea tanto la página como el contenido si 
        * no existiera alguno de los dos
        *
        * @param  string $pageName --> nombre de la página en tabla "page"
        * @param  int $languageId
        * @return PageContent 
    */
    public function getContentPageNamed( $pageName, $languageId = Language::DEFAULT_LANG_ID, $createIfNotExists = false ) 
    {

        $page = 
            $this->getPageTable()->getSingleRecord(array('name'=>$pageName));

        if (!$page && $createIfNotExists) {
            $page = new Page();
            $page->setName($pageName);
            $page->setId( $this->getPageTable()->updateRecord($page) );
        }

        if (!$page) return null;

        return $this->getPageContent($page->getId(), $languageId, $createIfNotExists);
    }


    public function getLanguagesArray( ) {

        $arrResult = array();

        $languages = $this->getLanguageTable()->fetchRecords();

        foreach ($languages as $lang) {

            $arrResult[$lang->getId()] = $lang->getCaption();
        }

        return $arrResult;

    }


    /**
        * fetchPagesArr
        *
        * @param  array $arrQuery --> array "campo consulta" => "valor consulta"
        * @param  int languageId
        * @param  boolean onlyCount --> retorna sólo el total de registros (para el paginador)
        * @param  int offset
        * @param  int limit --> si limit==0 sin límite
        * @return array --> array de registros (cada registro es a su vez un array con los campos de la tabla)
        *                   ó array con una sóla entrada => total de registros (si $onlyCount==true)
    */
    public function fetchPagesArr( $arrQuery, $languageId = Language::DEFAULT_LANG_ID, $onlyCount = false, $offset = 0, $limit = 0 )
    {
        $sqlQueryStr = ($onlyCount)? 
            "SELECT COUNT(*) as total " : 
            "SELECT pages.name,pages_content.* ";
        $sqlQueryStr .= 
            " FROM pages_content LEFT JOIN pages" . 
            " ON pages.id = pages_content.page_id";
   
        //$searchWhereArr = array('language_id' => $languageId);
        $searchWhereArr[] = 'language_id = '. $languageId;

        if (!empty($arrQuery['title'])) {
            $searchWhereArr[] = "pages_content.title LIKE '%".$arrQuery['title']."%'";
        }
        if (!empty($arrQuery['date_ini'])) {
            $searchWhereArr[] = "DATE(modification_date)>='".$arrQuery['date_ini']."'";
        }
        if (!empty($arrQuery['date_end'])) {
            $searchWhereArr[] = "DATE(modification_date)<='".$arrQuery['date_end']."'";
        }

        if (!empty($searchWhereArr)) {
            $sqlQueryStr .=  ' WHERE '. implode(" AND ", $searchWhereArr );
        }
      
        $sqlQueryStr .=  ' ORDER BY modification_date DESC ';

        if ($limit>0) {
            $sqlQueryStr .=  ' LIMIT '.$offset.','.$limit;
        }

        $pagesArr  = $this->executeStatement( $sqlQueryStr, ($onlyCount)? 'total' : '' );
        $resultArr = array(); 
        foreach ($pagesArr as $page) {
            $page['roles'] = $this->getPageRoles($page['page_id']);
            $resultArr[] = $page;
        }

        return $resultArr;
    }    




    public function orderContentInFather ($pageId)
    {

        $menuEntry = $this->getMenuEntryTable()->getSingleRecord ('target_page_id = '.$pageId);

        if (!$menuEntry) return 0;

        $menuEntrieParentElementId = $menuEntry->getParentElementId();
        $menuEntrieOrder = $menuEntry->getOrder();

        $sqlQueryStr =
            'SELECT count(*) as cont FROM menu_entries WHERE parent_element_id= '.$menuEntrieParentElementId.
        ' AND `order` < '.$menuEntrieOrder . ';';

        $order = $this->executeStatement( $sqlQueryStr );

        if (!empty($order[0]['cont'])) {
            return (int)$order[0]['cont'] + 1;            
        }

        return 0;
    }


    public function changeOrderMenuEntry($pageId, $orderFin){
        $menuEntryIni = $this->getMenuEntryTable()->getSingleRecord ('target_page_id = '.$pageId);
        if (!$menuEntryIni) return 0;

        $menuEntryIniParentElementId = $menuEntryIni->getParentElementId();
        $menuEntryIniOrder = $menuEntryIni->getOrder();

        $sqlQueryStr =
            'SELECT * FROM menu_entries WHERE parent_element_id = '.$menuEntryIniParentElementId.
            ' ORDER BY `order` LIMIT '.$orderFin.';';

        $arrResult = $this->executeStatement( $sqlQueryStr );

        //Me quedo con el ultimo elemento del array devuelto que es por el que quiero cambiar el orden
        $menuEntryAux = $arrResult[sizeof($arrResult)-1];

        $menuEntryFin = $this->getMenuEntryTable()->getSingleRecord ('id = '.$menuEntryAux['id']);
        $menuEntryFinOrder = $menuEntryFin->getOrder();

        $menuEntryIni->setOrder($menuEntryFinOrder);
        $this->getMenuEntryTable()->updateRecord($menuEntryIni, array('type_terminal' => ''));

        $menuEntryFin->setOrder($menuEntryIniOrder);
        $this->getMenuEntryTable()->updateRecord($menuEntryFin, array('type_terminal' => ''));
    }

    public function getLangById($languageId){
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();
        $select->from(Language::TABLE);

        $select->where(array(Language::TABLE.'.id' => $languageId));


        $statement = $sql->prepareStatementForSqlObject($select);
        $results = $statement->execute();

        return $results->current();
    }

    public function getCountriesForLang(){
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();

        $select->from(Country::TABLE);

        $statement = $sql->prepareStatementForSqlObject($select);
        $results = $statement->execute();

        return $results;
    }


}
