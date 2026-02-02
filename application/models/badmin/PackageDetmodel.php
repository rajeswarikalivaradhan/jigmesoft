<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
class PackageDetmodel extends CI_Model {
    function saveInfo($ArrSaveData) {
        $VarId = $ArrSaveData['id'];
        $ArrResult = [];
        if ($VarId == "") {
            $ArrCheckExist = $this->fnGetInfo($ArrSaveData['description'], 1,'',$ArrSaveData['companyid']);
            if (empty($ArrCheckExist)) {
                unset($ArrSaveData['id']);
                $this->db->insert(KN_MASTER_PACKAGE, $ArrSaveData);
                $VarInsId = $this->db->insert_id();
                $ArrResult['errcode'] = 1;
                $ArrResult['msg'] = '';
                $ArrResult['id'] = $VarInsId;
                $ArrResult['eid'] = urlencode(base64_encode($VarInsId));
            } else {
                $ArrResult['errcode'] = -1;
                $ArrResult['msg'] = "This description already exists";
            }
        } else {
            $ArrCheckExist = $this->fnCheck($ArrSaveData['description'], $VarId,$ArrSaveData['companyid']);
            if (empty($ArrCheckExist)) {
                if ($this->db->update(KN_MASTER_PACKAGE, $ArrSaveData, array('id' => $VarId))) {
                    $ArrResult['errcode'] = 1;
                    $ArrResult['msg'] = '';
                    $ArrResult['id'] = $VarId;
                    $ArrResult['eid'] = urlencode(base64_encode($VarId));
                } else {
                    $ArrResult['errcode'] = -1;
                    $ArrResult['msg'] = 'Invalid Data';
                }
            } else {
                $ArrResult['errcode'] = -1;
                $ArrResult['msg'] = "This description already exists";
            }

        }
        return $ArrResult;
    }
    
    function fnGetInfo($VarData, $VarStatus, $VarId = '',$VarCompanyId='') {
        $this->db->select('*')->from(KN_MASTER_PACKAGE);
        if ($VarData <> '') {
            $ArrWhere['description'] = trim($VarData);
        }
        // if ($VarStatus <> '') {
        //     $this->db->where_in('status', array($VarStatus));
        // } else {
        //     $this->db->where_in('status', array(1, 2));
        // }
        if ($VarId <> '') {
            $ArrWhere['id'] = $VarId;
        }
        if ($VarCompanyId <> '') {
            $ArrWhere['companyid'] = $VarCompanyId;
        }
        
        if (!empty($ArrWhere)) {
            $this->db->where($ArrWhere);
        }
        return $this->db->get()->result();
    }

    function fnCheck($VarData = '', $VarId = '',$VarCompanyId='') {
        $this->db->from(KN_MASTER_PACKAGE);
        //$ArrWhere = array('status' => "1");
        if ($VarId <> '') {
            $this->db->where_not_in('id', array($VarId));
        }
        if ($VarData <> '') {
            $ArrWhere['description'] = trim($VarData);
        }
        if ($VarCompanyId <> '') {
            $ArrWhere['companyid'] = $VarCompanyId;
        }
        $countAll = $this->db->where($ArrWhere)->count_all_results();
        return $countAll;
    }

    function fnCount($Vardescription = '', $Varnoofusers = '',$Vardata_limit = '',$Varfile_limit = '', $VarStatus = '') {
        $ArrWhere = array();
        if ($Vardescription <> '') {
            $ArrWhere[] = "b.description like '%" . $Vardescription . "%'";
        }
        if ($Varnoofusers <> '') {
            $ArrWhere[] = "b.no_of_users=". "'$Varnoofusers'";
        }
        if ($Vardata_limit <> '') {
            $ArrWhere[] = "b.data_limit=" . "'$Vardata_limit'";
        }
        if ($Varfile_limit <> '') {
            $ArrWhere[] = "b.file_limit=". "'$Varfile_limit'";
        }
        if ($VarStatus <> '') {
            $ArrWhere[] = "b.status=" . "'$VarStatus'";
        } else {
            $ArrWhere[] = "b.status in(1,2)";
        }
        
        $VarWhere = '';
        $ArrUserLoggedInfo = fnGetUserLoggedInfo('1');
        if (!empty($ArrWhere)) {
            $VarWhere = 'WHERE ' . implode(" and ", $ArrWhere) . " AND b.companyid = " . $ArrUserLoggedInfo['companyid'];
        }
        $VarSqlLab = "SELECT count(1) as trec  FROM " . KN_MASTER_PACKAGE . " AS b " . $VarWhere;
        $ObjRows = $this->db->query($VarSqlLab)->row();
        return $ObjRows->trec;
    }
    
    function fnList($Vardescription = '',$Varnoofusers = '',$Vardata_limit = '',$Varfile_limit = '', $VarStatus = '', $VarLimit = 10, $offset = 0, $VarSortBy, $VarSortOrder) {
        $VarSortOrder = ($VarSortOrder == 'desc') ? 'desc' : 'asc';
        $VarSortCols = array('description','no_of_users','data_limit','file_limit', 'b.status', 'b.dateupdated');
       // $VarSortBy = (in_array($VarSortBy, $VarSortCols)) ? $VarSortBy : 'b.dateupdated';
         $VarSortBy = (in_array($VarSortBy, $VarSortCols)) ? $VarSortBy : 'b.log';
        $VarLimitInfo = $VarLimit;
        if ($offset >= 1) {
            $VarLimitInfo = $offset . "," . $VarLimit;
        }
        $ArrWhere = array();
        if ($Vardescription <> '') {
            $ArrWhere[] = "b.description like '%" . $Vardescription . "%'";
        }
        if ($Varnoofusers <> '') {
            $ArrWhere[] = "b.no_of_users=". "'$Varnoofusers'";
        }
        if ($Vardata_limit <> '') {
            $ArrWhere[] = "b.data_limit=" . "'$Vardata_limit'";
        }
        if ($Varfile_limit <> '') {
            $ArrWhere[] = "b.file_limit=". "'$Varfile_limit'";
        }
        if ($VarStatus <> '') {
            $ArrWhere[] = "b.status=" . $VarStatus;
        } else {
            $ArrWhere[] = "b.status in(1,2)";
        }
        
        $ArrUserLoggedInfo = fnGetUserLoggedInfo('1');
        $VarWhere = '';
        if (!empty($ArrWhere)) {
            $VarWhere = 'WHERE ' . implode(" and ", $ArrWhere) . " AND b.companyid = " . $ArrUserLoggedInfo['companyid'];
        }
        
         //$VarSqlLab = "SELECT b.id,b.description,b.no_of_users,b.data_limit,b.file_limit,b.datecreated,DATE_FORMAT(b.dateupdated,\"%d-%m-%Y\ %H:%i %p\") as formattedDateUpdated,b.status,contactname
                      //FROM " . KN_MASTER_PACKAGE . " AS b LEFT JOIN " . KN_USERS . " AS u ON b.updatedby = u.id " . $VarWhere . " order by " . $VarSortBy . " " . $VarSortOrder;
        $VarSqlLab = "SELECT b.id,b.description,b.no_of_users,b.data_limit,b.file_limit,b.datecreated,DATE_FORMAT(b.log,\"%d/%m/%Y %h:%i %p\")  as formattedDateUpdated,b.status,contactname
                      FROM " . KN_MASTER_PACKAGE . " AS b LEFT JOIN " . KN_USERS . " AS u ON b.updatedby = u.id " . $VarWhere . " order by " . $VarSortBy . " " . $VarSortOrder;
        
        $ObjResult = $this->db->query($VarSqlLab);
        return $ObjResult;
    }
    function fnChangeComStat($checkboxids, $optvalue) {
        $Arrids = json_decode($checkboxids, true);
        $this->db->where_in('id', $Arrids);
        if ($this->db->update(KN_MASTER_PACKAGE, array('status' => $optvalue))) {
            return true;
        }
        return false;
    }
}