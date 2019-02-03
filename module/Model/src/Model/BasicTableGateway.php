<?php

namespace Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Select;


class BasicTableGateway
{
    public $tableGateway;
    public $tableName;

    public function __construct (TableGateway $tableGateway, $tableName )
    {
        $this->tableGateway = $tableGateway;
        $this->tableName    = $tableName;
    }

    public function getSingleRecord ( $queryArr )
    {
        $rowSet = $this->tableGateway->select($queryArr);

        if (!empty($rowSet)) {
            return $rowSet->current();
        }
        
        return NULL;
    }

    public function getRecordById ($elementId)
    {
        return $this->getSingleRecord(array('id'=>$elementId));
    }

    public function getRecordByIdUser ($elementId)
    {
        return $this->getSingleRecord(array('id_user'=>$elementId));
    }

    public function getRecordByIdProject ($elementId)
    {
        return $this->getSingleRecord(array('id_project'=>$elementId));
    }

    public function getRecordByIdDocumentation ($elementId)
    {
        return $this->getSingleRecord(array('id_documentation'=>$elementId));
    }

    public function getRecordByIdTutor($elementId)
    {
        return $this->getSingleRecord(array('id_tutor'=>$elementId));
    }


    public function getProjectRecordByIdUser ($elementId)
    {
        return $this->getSingleRecord(array('id_alumn'=>$elementId));
    }


    public function updateRecord( $record, $excludedFieldsInDataArray = NULL, $forceInsert = false )
    {

        $arrData = $record->getDataArray(TRUE);

        if ( !empty($excludedFieldsInDataArray) ) {
            $arrData = array_diff_key($arrData,$excludedFieldsInDataArray);
        }

        if (!$forceInsert && !empty($arrData['id'])) {
            $this->tableGateway->update( $arrData, array( 'id'=>$arrData['id'] ) );    
            return $arrData['id'];
        } else {
            if (!$forceInsert) {
                unset($arrData['id']);    
            }
            $this->tableGateway->insert( $arrData );
        }

        return $this->tableGateway->lastInsertValue;
    }

    public function updateUserRecord( $record, $excludedFieldsInDataArray = NULL, $forceInsert = false )
    {

        $arrData = $record->getDataArray(TRUE);

        if ( !empty($excludedFieldsInDataArray) ) {
            $arrData = array_diff_key($arrData,$excludedFieldsInDataArray);
        }

        if (!$forceInsert && !empty($arrData['id_user'])) {
            $this->tableGateway->update( $arrData, array( 'id_user'=>$arrData['id_user'] ) );
            return $arrData['id_user'];
        } else {
            if (!$forceInsert) {
                unset($arrData['id_user']);
            }
            $this->tableGateway->insert( $arrData );
        }

        return $this->tableGateway->lastInsertValue;
    }

    public function updateProjectRecord( $record, $excludedFieldsInDataArray = NULL, $forceInsert = false )
    {

        $arrData = $record->getDataArray(TRUE);

        if ( !empty($excludedFieldsInDataArray) ) {
            $arrData = array_diff_key($arrData,$excludedFieldsInDataArray);
        }

        if (!$forceInsert && !empty($arrData['id_project'])) {
            $this->tableGateway->update( $arrData, array( 'id_project'=>$arrData['id_project'] ) );
            return $arrData['id_project'];
        } else {
            if (!$forceInsert) {
                unset($arrData['id_project']);
            }
            $this->tableGateway->insert( $arrData );
        }

        return $this->tableGateway->lastInsertValue;
    }

    public function updateDocumentationRecord( $record, $excludedFieldsInDataArray = NULL, $forceInsert = false )
    {

        $arrData = $record->getDataArray(TRUE);

        if ( !empty($excludedFieldsInDataArray) ) {
            $arrData = array_diff_key($arrData,$excludedFieldsInDataArray);
        }

        if (!$forceInsert && !empty($arrData['id_documentation'])) {
            $this->tableGateway->update( $arrData, array( 'id_documentation'=>$arrData['id_documentation'] ) );
            return $arrData['id_documentation'];
        } else {
            if (!$forceInsert) {
                unset($arrData['id_documentation']);
            }
            $this->tableGateway->insert( $arrData );
        }

        return $this->tableGateway->lastInsertValue;
    }



    public function insertRecord( $record, $excludedFieldsInDataArray = NULL) {
        return $this->updateRecord( $record, $excludedFieldsInDataArray, TRUE);
    }
    
    public function fetchRecords( $whereClause = '', $offset = 0, $limit = 1000, $order = 'id ASC' )
    {
        $resultSet = 
            $this->tableGateway->select(
                function(Select $select) use ($whereClause,$offset,$limit,$order) {
                    if (!empty($whereClause)) {
                        $select->where($whereClause);
                    }
                    if (!empty($limit)) {
                        $select->offset($offset)->limit($limit);    
                    }
                    if (!empty($order)) {
                        $select->order($order);
                    }
                } 
            );

        $arrResult = array();
        foreach ($resultSet as $record) {
            $arrResult[] = $record;
        }
        return $arrResult;
    }

    public function fetchRecordsArr( $whereClause = '', $offset = 0, $limit = 1000, $order = 'id ASC' )
    {
        $arrResult = array();

        $resultSet = $this->fetchRecords($whereClause, $offset, $limit, $order);

        foreach ($resultSet as $record) {
            $recordArr = $record->getDataArray(true);
            $arrResult[(int)$recordArr['id']] = $recordArr;
        }

        return $arrResult;
    }

    public function recordsCount( $whereClause = '' )
    {
        $sql  = "SELECT COUNT(*) as total FROM " . $this->tableName;

        if (!empty($whereClause)) {
            $sql  .= " WHERE " . $whereClause; 
        }

        $statement = $this->tableGateway->getAdapter()->query($sql);

        $resultSet = $statement->execute();

        if ($resultSet) {
            $row = $resultSet->next();
            return (int) $row['total'];
        }

        return 0;
    }

    public function deleteRecord( $arrSql )
    {
        $this->tableGateway->delete($arrSql);
    }

    public function deleteRecordById( $recordId )
    {
        $this->deleteRecord(array('id'=>$recordId));
    }

    public function getLastInsertValue()
    {
        $sql  = "SHOW TABLE STATUS LIKE '" . $this->tableName . "'";

        $statement = $this->tableGateway->getAdapter()->query($sql);

        $resultSet = $statement->execute();

        if ($resultSet) {
            $row = $resultSet->next();
            if ( !empty($row['Auto_increment']) ) return (int) $row['Auto_increment'];
        }

        return 0;
    }

    public function executeStatement( $sqlQueryStr, $singleField = '' )
    {
        $statement = $this->tableGateway->getAdapter()->query($sqlQueryStr);
        
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

}
