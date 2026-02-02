<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
class fabricnamemodel extends CI_Model {
    function fnCheck($VarData = '', $VarId = '',$VarCompanyId='') {
        $this->db->from(KN_MASTER_FABRIC_MISC);
        //$ArrWhere = array('status' => "1");
        if ($VarId <> '') {
            $this->db->where_not_in('id', array($VarId));
        }
        if ($VarData <> '') {
            $ArrWhere['misc_name'] = trim($VarData);
        }
        if ($VarCompanyId <> '') {
            $ArrWhere['companyid'] = $VarCompanyId;
        }
        $ArrWhere['misc_type'] = '3';
        $countAll = $this->db->where($ArrWhere)->count_all_results();
        return $countAll;
    }
    function saveInfo($ArrSaveData) {
        $VarCompanyId = $ArrSaveData['companyid'];
        $VarId = $ArrSaveData['id'];
        $ArrResult = [];
        if ($VarId == "") {
            $ArrCheckExist = $this->fnGetInfo($ArrSaveData['misc_name'], 1,'',$ArrSaveData['companyid']);
            if (empty($ArrCheckExist)) {
                unset($ArrSaveData['id']);
                $this->db->insert(KN_MASTER_FABRIC_MISC, $ArrSaveData);
                $VarInsId = $this->db->insert_id();
                $ArrResult['errcode'] = 1;
                $ArrResult['msg'] = '';
                $ArrResult['id'] = $VarInsId;
                $ArrResult['eid'] = urlencode(base64_encode($VarInsId));
            } else {
                $ArrResult['errcode'] = -1;
                $ArrResult['msg'] = "This fabric already exists";
            }
        } else {
            $ArrCheckExist = $this->fnCheck($ArrSaveData['misc_name'],$VarId,$ArrSaveData['companyid']);
            if (empty($ArrCheckExist)) {
                if ($this->db->update(KN_MASTER_FABRIC_MISC, $ArrSaveData, array('id' => $VarId))) {
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
                $ArrResult['msg'] = "This fabric already exists";
            }
        }
        return $ArrResult;
    }
    function fnGetInfo($VarData, $VarStatus, $VarId = '',$VarCompanyId='') {
        $this->db->select('id,misc_name,status')->from(KN_MASTER_FABRIC_MISC);
        if ($VarData <> '') {
            $ArrWhere['misc_name'] = $VarData;
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
    function fnCount($VarFabric = '', $VarStatus = '') {
        $ArrWhere = array();
        if ($VarFabric <> '') {
            $ArrWhere[] = "misc_name LIKE '%$VarFabric%' ";
        }
        if ($VarStatus <> '') {
            $ArrWhere[] = "m.status=" . $VarStatus;
        } else {
            $ArrWhere[] = "m.status in(1,2)";
        }
        $ArrWhere[] = "misc_type = '3' ";
        $VarWhere = '';
        $ArrUserLoggedInfo = fnGetUserLoggedInfo('1');
        if (!empty($ArrWhere)) {
            $VarWhere = 'WHERE ' . implode(" and ", $ArrWhere) . " AND companyid = " . $ArrUserLoggedInfo['companyid'];
        }
        $VarSqlLab = "SELECT count(1) as trec  FROM " . KN_MASTER_FABRIC_MISC . " AS m $VarWhere ";
        $ObjRows = $this->db->query($VarSqlLab)->row();
        return $ObjRows->trec;
    }
    function fnList($VarFabric = '', $VarStatus = '', $VarLimit = 10, $offset = 0, $VarSortBy, $VarSortOrder) {
        $VarSortOrder = ($VarSortOrder == 'desc') ? 'desc' : 'asc';
        $VarSortCols = array('m.misc_name', 'm.status', 'm.dateupdated');
        $VarSortBy = (in_array($VarSortBy, $VarSortCols)) ? $VarSortBy : 'm.dateupdated';
        $VarLimitInfo = $VarLimit;
        if ($offset >= 1) {
            $VarLimitInfo = $offset . "," . $VarLimit;
        }
        $ArrWhere = array();
        if ($VarFabric <> '') {
            $ArrWhere[] = "m.misc_name LIKE '%$VarFabric%' ";
        }
        if ($VarStatus <> '') {
            $ArrWhere[] = "m.status=" . $VarStatus;
        } else {
            $ArrWhere[] = "m.status in(1,2)";
        }
        $ArrWhere[] = "m.misc_type = '3' ";
        $ArrUserLoggedInfo = fnGetUserLoggedInfo('1');
        $VarWhere = '';
        if (!empty($ArrWhere)) {
            $VarWhere = 'WHERE ' . implode(" and ", $ArrWhere) . " AND m.companyid = " . $ArrUserLoggedInfo['companyid'];
        }
        // removed this line  . " limit " . $VarLimitInfo in below query
        $VarSqlLab = "SELECT m.id, m.misc_name, m.datecreated, DATE_FORMAT(m.dateupdated,\"%d-%m-%Y\ %H:%i %p\") as formattedDateUpdated, m.status,contactname
        FROM " . KN_MASTER_FABRIC_MISC . " AS m LEFT JOIN " . KN_USERS . " AS u ON m.updatedby = u.id "
        .$VarWhere . " order by " . $VarSortBy . " " . $VarSortOrder;
        $ObjResult = $this->db->query($VarSqlLab);
        return $ObjResult;
    }
    function fnChangeComStat($checkboxids, $optvalue) {
        $Arrids = json_decode($checkboxids, true);
        $this->db->where_in('id', $Arrids);
        if ($this->db->update(KN_MASTER_FABRIC_MISC, array('status' => $optvalue))) {
            return true;
        }
        return false;
    }
}