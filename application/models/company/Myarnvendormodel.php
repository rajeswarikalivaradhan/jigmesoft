<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class myarnvendormodel extends CI_Model {
    function fnGetInfo($VarYarnvendor = '', $VarStatus = '', $VarId = '', $VarCompanyId = '') {
        $this->db->select('id,yarnvendor,status');
        $this->db->from(KN_MASTER_YARN_VENDOR);
        if ($VarStatus <> '') {
            $this->db->where_in('status', array($VarStatus));
        } else {
            $this->db->where_in('status', array(1, 2));
        }
        if ($VarId <> '') {
            $ArrWhere['id'] = $VarId;
        }
        if ($VarYarnvendor <> '') {
            $ArrWhere['yarnvendor'] = $VarYarnvendor;
        }
        if ($VarCompanyId <> '') {
            $ArrWhere['companyid'] = $VarCompanyId;
        }
        if (@count($ArrWhere) >= 1) {
            $this->db->where($ArrWhere);
        }
        $ArrResultList = $this->db->get()->result_array();
        return $ArrResultList;
    }
    function fnCheck($VarYarnvendor = '', $VarId = '',$VarCompanyId='') {
        $this->db->from(KN_MASTER_YARN_VENDOR);
        $ArrWhere = array('status' => "1");
        if ($VarId <> '') {
            $this->db->where_not_in('id', array($VarId));
        }
        if ($VarYarnvendor <> '') {
            $ArrWhere['yarnvendor'] = $VarYarnvendor;
        }
        if ($VarCompanyId <> '') {
            $ArrWhere['companyid'] = $VarCompanyId;
        }
        return $this->db->where($ArrWhere)->count_all_results();
    }
    function saveInfo($ArrUpdateData) {
        $VarId = $ArrUpdateData['id'];
        if ($VarId == "") {
            $ArrCheckExist = $this->fnGetInfo($ArrUpdateData['yarnvendor'], 1,'',$ArrUpdateData['companyid']);
            if(empty($ArrCheckExist)) {
                unset($ArrUpdateData['id']);
                $this->db->insert(KN_MASTER_YARN_VENDOR, $ArrUpdateData);
                $VarId = $this->db->insert_id();
                $ArrResult['errcode'] = 1;
                $ArrResult['msg'] = '';
                $ArrResult['id'] = $VarId;
                $ArrResult['eid'] = urlencode(base64_encode($VarId));
            } else {
                $ArrResult['errcode'] = -1;
                $ArrResult['msg'] = "This yarn vendor already exists";
            }
        } else {
            $ArrCheckExist = $this->fnCheck($ArrUpdateData['yarnvendor'], '',$ArrUpdateData['companyid']);
            if (empty($ArrCheckExist)) {
                if ($this->db->update(KN_MASTER_YARN_VENDOR, $ArrUpdateData, array('id' => $VarId))) {
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
                $ArrResult['msg'] = "This yarn vendor has already exist";
            }
        }
        return $ArrResult;
    }
    function fnCount($VarYarnvendor = '', $VarStatus = '') {
        $userInfo = fnGetUserLoggedInfo(1);
        $ArrWhere = array();
        if ($VarYarnvendor <> '') {
            $ArrWhere[] = "y.yarnvendor like '%" . $VarYarnvendor . "%'";
        }
        if ($VarStatus <> '') {
            $ArrWhere[] = "y.status=" . $VarStatus;
        } else {
            $ArrWhere[] = "y.status in(1,2)";
        }
        $ArrWhere[] = "y.companyid = " . $userInfo['companyid'];
        $VarWhere = '';
        if (@$ArrWhere[0] <> '') {
            $VarWhere = implode(" and ", $ArrWhere);
        }
        $VarSqlLab = "SELECT count(1) as trec  FROM " . KN_MASTER_YARN_VENDOR . " AS y INNER JOIN " . KN_USERS . " AS u ON y.updatedby = u.id WHERE " . $VarWhere;
        $ObjRows = $this->db->query($VarSqlLab)->row();
        return $ObjRows->trec;
    }
    function fnList($VarYarnvendor = '', $VarStatus = '', $VarLimit = 10, $offset = 0, $VarSortBy, $VarSortOrder) {
        $userInfo = fnGetUserLoggedInfo(1);
        $VarSortOrder = ($VarSortOrder == 'desc') ? 'desc' : 'asc';
        $VarSortCols = array('yarnvendor', 'y.dateupdated', 'y.status', 'u.contactname');
        $VarSortBy = (in_array($VarSortBy, $VarSortCols)) ? $VarSortBy : 'y.dateupdated';
        $VarLimitInfo = $VarLimit;
        if ($offset >= 1) {
            $VarLimitInfo = $offset . "," . $VarLimit;
        }
        $ArrWhere = array();
        if ($VarYarnvendor <> '') {
            $ArrWhere[] = "y.yarnvendor like '%" . $VarYarnvendor . "%'";
        }

        if ($VarStatus <> '') {
            $ArrWhere[] = "y.status=" . $VarStatus;
        } else {
            $ArrWhere[] = "y.status in(1,2)";
        }
        $ArrWhere[] = "y.companyid = " . $userInfo['companyid'];
        $VarWhere = '';
        if (@$ArrWhere[0] <> '') {
            $VarWhere = implode(" and ", $ArrWhere);
        }
        $VarSqlLab = "SELECT y.id,y.yarnvendor,u.contactname,y.datecreated,y.dateupdated,y.status FROM " . KN_MASTER_YARN_VENDOR . " AS y INNER JOIN " . KN_USERS . " AS u ON y.updatedby = u.id WHERE " . $VarWhere . " order by " . $VarSortBy . " " . $VarSortOrder . " limit " . $VarLimitInfo;
        $ObjResult = $this->db->query($VarSqlLab);
        return $ObjResult;
    }
    function fnChangeComStat($checkboxids, $optvalue) {
        $Arrids = json_decode($checkboxids, true);
        $this->db->where_in('id', $Arrids);
        if ($this->db->update(KN_MASTER_YARN_VENDOR, array('status' => $optvalue))) {
            return true;
        }
        return false;
    }
}