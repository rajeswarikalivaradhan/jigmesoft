<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
class mbomblendmodel extends CI_Model {
    function saveInfo($ArrSaveData) {
        $VarId = $ArrSaveData['id'];
        $ArrResult = [];
        if ($VarId == "") {
            $ArrCheckExist = $this->fnGetInfo($ArrSaveData['misc_name'], 1,'',$ArrSaveData['companyid']);
            if (empty($ArrCheckExist)) {
                unset($ArrSaveData['id']);
                $this->db->insert(KN_MASTER_BOM_MISC, $ArrSaveData);
                $VarInsId = $this->db->insert_id();
                $ArrResult['errcode'] = 1;
                $ArrResult['msg'] = '';
                $ArrResult['id'] = $VarInsId;
                $ArrResult['eid'] = urlencode(base64_encode($VarInsId));
            } else {
                $ArrResult['errcode'] = -1;
                $ArrResult['msg'] = "This yarn blend already exists";
            }
        } else {
            $ArrCheckExist = $this->fnCheck($ArrSaveData['misc_name'], $VarId,$ArrSaveData['companyid']);
            if (empty($ArrCheckExist)) {
                if ($this->db->update(KN_MASTER_BOM_MISC, $ArrSaveData, array('id' => $VarId))) {
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
                $ArrResult['msg'] = "This yarn blend already exists";
            }

        }
        return $ArrResult;
    }
    function fnGetInfo($VarData, $VarStatus, $VarId = '',$VarCompanyId='') {
        $this->db->select('id,misc_name,status')->from(KN_MASTER_BOM_MISC);
        if ($VarData <> '') {
            $ArrWhere['misc_name'] = $VarData;
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
        $ArrWhere['misc_type'] = '1';
        if (!empty($ArrWhere)) {
            $this->db->where($ArrWhere);
        }
        return $this->db->get()->result();
    }
    function fnCheck($VarData = '', $VarId = '',$VarCompanyId='') {
        $this->db->from(KN_MASTER_BOM_MISC);
        $ArrWhere = array('status' => "1");
        if ($VarId <> '') {
            $this->db->where_not_in('id', array($VarId));
        }
        if ($VarData <> '') {
            $ArrWhere['misc_name'] = $VarData;
        }
        if ($VarCompanyId <> '') {
            $ArrWhere['companyid'] = $VarCompanyId;
        }
        $countAll = $this->db->where($ArrWhere)->count_all_results();
        return $countAll;
    }
    function fnCount($Varmisc_name = '', $VarStatus = '') {
        $ArrWhere = array();
        if ($Varmisc_name <> '') {
            $ArrWhere[] = "b.misc_name like '%" . $Varmisc_name . "%'";
        }
        if ($VarStatus <> '') {
            $ArrWhere[] = "b.status=" . $VarStatus;
        } else {
            $ArrWhere[] = "b.status in(1,2)";
        }
        $ArrWhere[] = "misc_type = '1' ";
        $VarWhere = '';
        $ArrUserLoggedInfo = fnGetUserLoggedInfo('1');
        if (!empty($ArrWhere)) {
            $VarWhere = 'WHERE ' . implode(" and ", $ArrWhere) . " AND b.companyid = " . $ArrUserLoggedInfo['companyid'];
        }
        $VarSqlLab = "SELECT count(1) as trec  FROM " . KN_MASTER_BOM_MISC . " AS b " . $VarWhere;
        $ObjRows = $this->db->query($VarSqlLab)->row();
        return $ObjRows->trec;
    }
    function fnList($Varmisc_name = '', $VarStatus = '', $VarLimit = 10, $offset = 0, $VarSortBy, $VarSortOrder) {
        $VarSortOrder = ($VarSortOrder == 'desc') ? 'desc' : 'asc';
        $VarSortCols = array('misc_name', 'b.status', 'b.dateupdated');
        $VarSortBy = (in_array($VarSortBy, $VarSortCols)) ? $VarSortBy : 'b.dateupdated';
        $VarLimitInfo = $VarLimit;
        if ($offset >= 1) {
            $VarLimitInfo = $offset . "," . $VarLimit;
        }
        $ArrWhere = array();
        if ($Varmisc_name <> '') {
            $ArrWhere[] = "b.misc_name like '%" . $Varmisc_name . "%'";
        }
        if ($VarStatus <> '') {
            $ArrWhere[] = "b.status=" . $VarStatus;
        } else {
            $ArrWhere[] = "b.status in(1,2)";
        }
        $ArrWhere[] = "misc_type = '1' ";
        $ArrUserLoggedInfo = fnGetUserLoggedInfo('1');
        $VarWhere = '';
        if (!empty($ArrWhere)) {
            $VarWhere = 'WHERE ' . implode(" and ", $ArrWhere) . " AND b.companyid = " . $ArrUserLoggedInfo['companyid'];
        }
        $VarSqlLab = "SELECT b.id,b.misc_name,b.datecreated,DATE_FORMAT(b.dateupdated,\"%d-%m-%Y\ %H:%i:%s\") as formattedDateUpdated,b.status
FROM " . KN_MASTER_BOM_MISC . " AS b " . $VarWhere . " order by " . $VarSortBy . " " . $VarSortOrder . " limit " . $VarLimitInfo;
        $ObjResult = $this->db->query($VarSqlLab);
        return $ObjResult;
    }
    function fnChangeComStat($checkboxids, $optvalue) {
        $Arrids = json_decode($checkboxids, true);
        $this->db->where_in('id', $Arrids);
        if ($this->db->update(KN_MASTER_BOM_MISC, array('status' => $optvalue))) {
            return true;
        }
        return false;
    }
}