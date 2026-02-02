<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
class mdyeingvendormodel extends CI_Model {
    function fnGetInfo($VarData = '', $VarStatus = '', $VarId = '')
    {
        $this->db->select('id,vendorname,contactpersonname,address,emailid,phone,mobile,gstno,iecode,bankname,accountname,accountno,ifscode,rtgs,swiftcode,iban,status');
        if ($VarData <> '') {
            $ArrWhere['vendorname'] = $VarData;
        }
        if ($VarStatus <> '') {
            $this->db->where_in('status', array($VarStatus));
        } else {
            $this->db->where_in('status', array(1, 2));
        }
        if ($VarId <> '') {
            $ArrWhere['id'] = $VarId;
        }
        if (!empty($ArrWhere)) {
            $this->db->where($ArrWhere);
        }
        return $this->db->from(KN_MASTER_DYEING_VENDOR)->get()->result_array();
    }
    function fnCheck($VarData = '', $VarId = '') {
        $this->db->from(KN_MASTER_DYEING_VENDOR);
        $ArrWhere = array('status' => "1");
        if ($VarId <> '') {
            $this->db->where_not_in('id', array($VarId));
        }
        if ($VarData <> '') {
            $ArrWhere['vendorname'] = $VarData;
        }
        $countAll = $this->db->where($ArrWhere)->count_all_results();
        return $countAll;
    }

    function saveInfo($ArrUpdateData) {
        $VarId = $ArrUpdateData['id'];
        if ($VarId == "") {
            $ArrCheckExist = $this->fnGetInfo($ArrUpdateData['vendorname'], 1);
            if (empty($ArrCheckExist)) {
                unset($ArrUpdateData['id']);
                $this->db->insert(KN_MASTER_DYEING_VENDOR, $ArrUpdateData);
                $VarInsertId = $this->db->insert_id();
                $ArrResult['errcode'] = 1;
                $ArrResult['msg'] = '';
                $ArrResult['id'] = $VarInsertId;
                $ArrResult['eid'] = urlencode(base64_encode($VarInsertId));
            } else {
                $ArrResult['errcode'] = -1;
                $ArrResult['msg'] = "This vendor already exists";
            }
        } else {
            $ArrCheckExist = $this->fnCheck($ArrUpdateData['vendorname'], $VarId);
            if (empty($ArrCheckExist)) {
                if ($this->db->update(KN_MASTER_DYEING_VENDOR, $ArrUpdateData, array('id' => $VarId))) {
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
                $ArrResult['msg'] = "This vendor already exists";
            }
        }
        return $ArrResult;
    }
    function fnCount($VarVendor = '', $VarContatname = '',$VarEmailId='', $VarStatus = '', $VarAfilter = '') {
        $userInfo = fnGetUserLoggedInfo(1);
        $ArrWhere = array();
        if ($VarVendor <> '') {
            $ArrWhere[] = "b.vendorname LIKE '%$VarVendor%'";
        }
        if ($VarContatname <> '') {
            $ArrWhere[] = "b.contactpersonname LIKE '%$VarContatname%' ";
        }
        if ($VarEmailId <> '') {
            $ArrWhere[] = "b.emailid LIKE '%$VarEmailId%' ";
        }
        if ($VarAfilter <> '') {
            $ArrWhere[] = "b.vendorname LIKE '$VarAfilter%' ";
        }
        if ($VarStatus <> '') {
            $ArrWhere[] = "b.status=" . $VarStatus;
        } else {
            $ArrWhere[] = "b.status in(1,2)";
        }
        $ArrWhere[] = "b.companyid = " . $userInfo['companyid'];
        $VarWhere = '';
        if (@$ArrWhere[0] <> '') {
            $VarWhere = implode(" and ", $ArrWhere);
        }
        $VarSqlLab = "SELECT count(1) as trec  FROM " . KN_MASTER_DYEING_VENDOR . " AS b INNER JOIN ".KN_USERS." AS u ON b.updatedby = u.id WHERE " . $VarWhere;
        $ObjRows = $this->db->query($VarSqlLab)->row();
        return $ObjRows->trec;
    }
    function fnList($VarVendor = '', $VarContatname = '', $VarEmailId='',$VarStatus = '', $VarAfilter = '', $VarLimit = 10, $offset = 0, $VarSortBy, $VarSortOrder='') {
        $userInfo = fnGetUserLoggedInfo(1);
        $VarSortOrder = ($VarSortOrder == 'desc') ? 'desc' : 'asc';
        $VarSortCols = array('vendorname', 'contactpersonname', 'emailid', 'phone', 'mobile', 'contactname', 'status', 'dateupdated');
        $VarSortBy = (in_array($VarSortBy, $VarSortCols)) ? $VarSortBy : 'b.dateupdated';
        $VarLimitInfo = $VarLimit;
        if ($offset >= 1) {
            $VarLimitInfo = $offset . "," . $VarLimit;
        }
        $ArrWhere = array();
        if ($VarVendor <> '') {
            $ArrWhere[] = "b.vendorname LIKE '%$VarVendor%'";
        }
        if ($VarContatname <> '') {
            $ArrWhere[] = "b.contactpersonname LIKE '%$VarContatname%' ";
        }
        if ($VarEmailId <> '') {
            $ArrWhere[] = "b.emailid LIKE '%$VarEmailId%' ";
        }
        if ($VarAfilter <> '') {
            $ArrWhere[] = "b.vendorname LIKE '$VarAfilter%' ";
        }
        if ($VarStatus <> '') {
            $ArrWhere[] = "b.status=" . $VarStatus;
        } else {
            $ArrWhere[] = "b.status in(1,2)";
        }
        $ArrWhere[] = "b.companyid = " . $userInfo['companyid'];
        $VarWhere = '';
        if (@$ArrWhere[0] <> '') {
            $VarWhere = implode(" and ", $ArrWhere);
        }
        $VarSqlLab = "SELECT b.id,vendorname,b.address,b.emailid,b.phone,b.mobile,b.contactpersonname,b.datecreated,b.dateupdated,b.status,u.contactname 
FROM " . KN_MASTER_DYEING_VENDOR . " AS b INNER JOIN ".KN_USERS." AS u ON b.updatedby = u.id WHERE " . $VarWhere . " order by " . $VarSortBy . " " . $VarSortOrder . " limit " . $VarLimitInfo;
        $ObjResult = $this->db->query($VarSqlLab);
        return $ObjResult;
    }
    function fnDel($VarId = '', $VarUpdatedBy = '') {
        if (@$VarId >= 1) {
            $ArrUpdateData = array('status' => 3, 'dateupdated' => date('Y-m-d H:i:s'), 'updatedby' => $VarUpdatedBy);
            if ($this->db->update(KN_MASTER_DYEING_VENDOR, $ArrUpdateData, array('id' => $VarId))) {
                $ArrResult['errcode'] = 1;
                $ArrResult['msg'] = '';
                $ArrResult['id'] = $VarId;
                $ArrResult['eid'] = urlencode(base64_encode($VarId));
            } else {
                $ArrResult['errcode'] = '-1';
                $ArrResult['msg'] = 'Invalid Data!';
            }
        }
        return $ArrResult;
    }
    function fnChangeComStat($checkboxids, $optvalue) {
        $Arrids = json_decode($checkboxids, true);
        $this->db->where_in('id', $Arrids);
        if ($this->db->update(KN_MASTER_DYEING_VENDOR, array('status' => $optvalue))) {
            return true;
        }
        return false;
    }
}