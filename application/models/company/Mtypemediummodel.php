<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
class mtypemediummodel extends CI_Model {
    function fnCheck($VarData = '', $VarId = '',$VarCompanyId='') {
        $this->db->from(KN_MASTER_TYPE_MEDIUM);
        $ArrWhere = array('status' => "1");
        if ($VarId <> '') {
            $this->db->where_not_in('id', array($VarId));
        }
        if ($VarData <> '') {
            $ArrWhere['type_medium'] = $VarData;
        }
        if ($VarCompanyId <> '') {
            $ArrWhere['companyid'] = $VarCompanyId;
        }
        $countAll = $this->db->where($ArrWhere)->count_all_results();
        return $countAll;
    }
    function saveInfo($ArrSaveData) {
        $VarCompanyId = $ArrSaveData['companyid'];
        $VarId = $ArrSaveData['id'];
        $ArrResult = [];
        if ($VarId == "") {
            $ArrCheckExist = $this->fnGetInfo($ArrSaveData['type_medium'], 1,'',$ArrSaveData['companyid']);
            if (empty($ArrCheckExist)) {
                unset($ArrSaveData['id']);
                $this->db->insert(KN_MASTER_TYPE_MEDIUM, $ArrSaveData);
                $VarInsId = $this->db->insert_id();
                $ArrResult['errcode'] = 1;
                $ArrResult['msg'] = '';
                $ArrResult['id'] = $VarInsId;
                $ArrResult['eid'] = urlencode(base64_encode($VarInsId));
            } else {
                $ArrResult['errcode'] = -1;
                $ArrResult['msg'] = "This yarn count already exists";
            }
        } else {
            $ArrCheckExist = $this->fnCheck($ArrSaveData['type_medium'],$VarId,$ArrSaveData['companyid']);
            if (empty($ArrCheckExist)) {
                if ($this->db->update(KN_MASTER_TYPE_MEDIUM, $ArrSaveData, array('id' => $VarId))) {
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
                $ArrResult['msg'] = "This yarn count already exists";
            }
        }
        return $ArrResult;
    }
    function fnGetInfo($VarData, $VarStatus, $VarId = '',$VarCompanyId='') {
        $this->db->select('id,type_medium,status')->from(KN_MASTER_TYPE_MEDIUM);
        if ($VarData <> '') {
            $ArrWhere['type_medium'] = $VarData;
        }
        if ($VarStatus <> '') {
            $this->db->where_in('status', array($VarStatus));
        } else {
            $this->db->where_in('status', array(1, 2));
        }
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
    function fnCount($VarYarn = '', $VarStatus = '') {
        $ArrWhere = array();
        if ($VarYarn <> '') {
            $ArrWhere[] = "type_medium LIKE '%$VarYarn%' ";
        }
        if ($VarStatus <> '') {
            $ArrWhere[] = "y.status=" . $VarStatus;
        } else {
            $ArrWhere[] = "y.status in(1,2)";
        }
        $VarWhere = '';
        $ArrUserLoggedInfo = fnGetUserLoggedInfo('1');
        if (!empty($ArrWhere)) {
            $VarWhere = 'WHERE ' . implode(" and ", $ArrWhere) . " AND companyid = " . $ArrUserLoggedInfo['companyid'];
        }
        $VarSqlLab = "SELECT count(1) as trec  FROM " . KN_MASTER_TYPE_MEDIUM . " AS y $VarWhere ";
        $ObjRows = $this->db->query($VarSqlLab)->row();
        return $ObjRows->trec;
    }
    function fnList($VarYarn = '', $VarStatus = '', $VarLimit = 10, $offset = 0, $VarSortBy, $VarSortOrder) {
        $VarSortOrder = ($VarSortOrder == 'desc') ? 'desc' : 'asc';
        $VarSortCols = array('type_medium', 'y.status', 'y.dateupdated');
        $VarSortBy = (in_array($VarSortBy, $VarSortCols)) ? $VarSortBy : 'y.dateupdated';
        $VarLimitInfo = $VarLimit;
        if ($offset >= 1) {
            $VarLimitInfo = $offset . "," . $VarLimit;
        }
        $ArrWhere = array();
        if ($VarYarn <> '') {
            $ArrWhere[] = "type_medium LIKE '%$VarYarn%' ";
        }
        if ($VarStatus <> '') {
            $ArrWhere[] = "y.status=" . $VarStatus;
        } else {
            $ArrWhere[] = "y.status in(1,2)";
        }
        $ArrUserLoggedInfo = fnGetUserLoggedInfo('1');
        $VarWhere = '';
        if (!empty($ArrWhere)) {
            $VarWhere = 'WHERE ' . implode(" and ", $ArrWhere) . " AND companyid = " . $ArrUserLoggedInfo['companyid'];
        }
        $VarSqlLab = "SELECT id,type_medium,datecreated,DATE_FORMAT(dateupdated,\"%d-%m-%Y\ %H:%i:%s\") as formattedDateUpdated,status FROM " . KN_MASTER_TYPE_MEDIUM . " AS y 
        $VarWhere order by " . $VarSortBy . " " . $VarSortOrder . " limit " . $VarLimitInfo;
        $ObjResult = $this->db->query($VarSqlLab);
        return $ObjResult;
    }
    function fnChangeComStat($checkboxids, $optvalue) {
        $Arrids = json_decode($checkboxids, true);
        $this->db->where_in('id', $Arrids);
        if ($this->db->update(KN_MASTER_TYPE_MEDIUM, array('status' => $optvalue))) {
            return true;
        }
        return false;
    }
}