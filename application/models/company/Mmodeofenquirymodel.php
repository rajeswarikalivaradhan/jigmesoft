<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Mmodeofenquirymodel extends CI_Model {
    function fnSaveInfo($ArrUpdateData) {
        $VarId = $ArrUpdateData['id'];
        if ($VarId == "") {
            $ArrCheckExist = $this->fnGetInfo($ArrUpdateData['modeofenquiry'], 1);
            if (empty($ArrCheckExist)) {
                unset($ArrUpdateData['id']);
                $this->db->insert(KN_MASTER_MODEOFENQUIRY, $ArrUpdateData);
                $VarId = $this->db->insert_id();
                $ArrResult['errcode'] = 1;
                $ArrResult['msg'] = '';
                $ArrResult['id'] = $VarId;
                $ArrResult['eid'] = urlencode(base64_encode($VarId));
            } else {
                $ArrResult['errcode'] = -1;
                $ArrResult['msg'] = "This mode of enquiry already exists";
            }
        } else {
            $VarAlreadyExists = $this->fnCheck($ArrUpdateData['modeofenquiry'], $VarId);
            if (empty($VarAlreadyExists)) {
                if ($this->db->update(KN_MASTER_MODEOFENQUIRY, $ArrUpdateData, array('id' => $VarId))) {
                    $ArrResult['errcode'] = 1;
                    $ArrResult['msg'] = '';
                    $ArrResult['id'] = $VarId;
                    $ArrResult['eid'] = urlencode(base64_encode($VarId));
                } else {
                    $ArrResult['errcode'] = -1;
                    $ArrResult['msg'] = 'Invalid Data!';
                }
            } else {
                $ArrResult['errcode'] = -1;
                $ArrResult['msg'] = "This mode of enquiry has already exist";
            }
        }
        return $ArrResult;
    }
    function fnGetInfo($VarData = '', $VarStatus = '', $VarId = '') {
        $this->db->select('id,modeofenquiry,updatedby,status,datecreated,dateupdated');
        $this->db->from(KN_MASTER_MODEOFENQUIRY);
        // if ($VarStatus <> '') {
        //     $this->db->where_in('status', array($VarStatus));
        // } else {
        //     $this->db->where_in('status', array(1, 2));
        // }
        if ($VarId <> '') {
            $ArrWhere['id'] = $VarId;
        }
        if ($VarData <> '') {
            $ArrWhere['modeofenquiry'] = $VarData;
        }
        if (@count($ArrWhere) >= 1) {
            $this->db->where($ArrWhere);
        }
        $ArrResultList = $this->db->get()->result_array();
        return $ArrResultList;
    }
    function fnCheck($VarData = '', $VarId = '') {
        $this->db->from(KN_MASTER_MODEOFENQUIRY);
       // $ArrWhere = array('status' => "1");
        if ($VarId <> '') {
            $this->db->where_not_in('id', array($VarId));
        }
        if ($VarData <> '') {
            $ArrWhere['modeofenquiry'] = $VarData;
        }
        $VarCount = $this->db->where($ArrWhere)->count_all_results();
        return $VarCount;
    }
    function fnCount($Varmode = '', $VarStatus = '') {
        $ArrUserLoggedInfo = fnGetUserLoggedInfo('1');
        $ArrWhere = array();
        if ($Varmode <> '') {
            $ArrWhere[] = "c.modeofenquiry like '%" . $Varmode . "%'";
        }
        if ($VarStatus <> '') {
            $ArrWhere[] = "c.status=" . $VarStatus;
        } else {
            $ArrWhere[] = "c.status in(1,2)";
        }
        $VarWhere = '';
        if (@$ArrWhere[0] <> '') {
            $VarWhere = implode(" and ", $ArrWhere) . " AND c.companyid = " . $ArrUserLoggedInfo['companyid'];
        }
        $VarSqlLab = "SELECT count(1) as trec  FROM " . KN_MASTER_MODEOFENQUIRY . " AS c LEFT JOIN " . KN_USERS . " AS u ON c.updatedby = u.id WHERE " . $VarWhere;
        $ObjRows = $this->db->query($VarSqlLab)->row();
        return $ObjRows->trec;
    }
    function fnList($Varmode = '', $VarStatus = '', $VarLimit = 10, $offset = 0, $VarSortBy, $VarSortOrder = '') {
        $ArrUserLoggedInfo = fnGetUserLoggedInfo('1');
        $VarSortOrder = ($VarSortOrder == 'desc') ? 'desc' : 'asc';
        $VarSortCols = array("1" => 'c.modeofenquiry', '2' => 'status', '3' => 'u.contactname', '4' => 'c.dateupdated');
        $VarSortBy = (in_array($VarSortBy, $VarSortCols)) ? $VarSortBy : 'c.dateupdated';
        $VarLimitInfo = $VarLimit;
        if ($offset >= 1) {
            $VarLimitInfo = $offset . "," . $VarLimit;
        }
        $ArrWhere = array();
        if ($Varmode <> '') {
            $ArrWhere[] = "c.modeofenquiry like '%" . $Varmode . "%'";
        }
        if ($VarStatus <> '') {
            $ArrWhere[] = "c.status=" . $VarStatus;
        } else {
            $ArrWhere[] = "c.status in(1,2)";
        }
        $VarWhere = '';
        if (@$ArrWhere[0] <> '') {
            $VarWhere = implode(" and ", $ArrWhere) . " AND c.companyid = " . $ArrUserLoggedInfo['companyid'];
        }
       // $VarSqlLab = "SELECT c.id,c.modeofenquiry,u.contactname,c.datecreated,c.dateupdated,c.status FROM " . KN_MASTER_MODEOFENQUIRY . " AS c LEFT JOIN " . KN_USERS . " AS u ON c.updatedby = u.id WHERE " . $VarWhere . " order by " . $VarSortBy . " " . $VarSortOrder . " limit " . $VarLimitInfo;
        $VarSqlLab = "SELECT c.id,c.modeofenquiry,u.contactname,c.datecreated,c.dateupdated,c.status FROM " . KN_MASTER_MODEOFENQUIRY . " AS c LEFT JOIN " . KN_USERS . " AS u ON c.updatedby = u.id WHERE " . $VarWhere . " order by " . $VarSortBy . " " . $VarSortOrder;
        $ObjResult = $this->db->query($VarSqlLab);
        return $ObjResult;
    }
    function fnChangeStatus($checkboxids, $optvalue) {
        $Arrids = json_decode($checkboxids, true);
        $this->db->where_in('id', $Arrids);
        if ($this->db->update(KN_MASTER_MODEOFENQUIRY, array('status' => $optvalue))) {
            return true;
        }
        return false;
    }
}
?>