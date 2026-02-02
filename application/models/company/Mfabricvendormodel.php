<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
class Mfabricvendormodel extends CI_Model {
    function fnGetInfo($VarData = '', $VarStatus = '', $VarId = '')
    {
        $this->db->select('*');
        if ($VarData <> '') {
            $ArrWhere['vendor_name'] = trim($VarData);
        }
        // if ($VarStatus <> '') {
        //     $this->db->where_in('status', array($VarStatus));
        // } else {
        //     $this->db->where_in('status', array(1, 2));
        // }
        if ($VarId <> '') {
            $ArrWhere['id'] = $VarId;
        }
        if (!empty($ArrWhere)) {
            $this->db->where($ArrWhere);
        }
        return $this->db->from(KN_MASTER_FABRICVENDOR)->get()->result_array();
    }
    function fnCheck($VarData = '', $VarId = '') {
        $this->db->from(KN_MASTER_FABRICVENDOR);
        //$ArrWhere = array('status' => "1");
        if ($VarId <> '') {
            $this->db->where_not_in('id', array($VarId));
        }
        if ($VarData <> '') {
            $ArrWhere['vendor_name'] = trim($VarData);
        }
        $countAll = $this->db->where($ArrWhere)->count_all_results();
        return $countAll;
    }

    function saveInfo($ArrUpdateData) {
        $VarId = $ArrUpdateData['id'];
        if ($VarId == "") {
            $ArrCheckExist = $this->fnGetInfo($ArrUpdateData['vendor_name'], 1);
            if (empty($ArrCheckExist)) {
                unset($ArrUpdateData['id']);
                $this->db->insert(KN_MASTER_FABRICVENDOR, $ArrUpdateData);
                $VarInsertId = $this->db->insert_id();
                $ArrResult['errcode'] = 1;
                $ArrResult['msg'] = '';
                $ArrResult['id'] = $VarInsertId;
                $ArrResult['eid'] = urlencode(base64_encode($VarInsertId));
            } else {
                $ArrResult['errcode'] = -1;
                $ArrResult['msg'] = "This fabric vendor already exists";
            }
        } else {
            $ArrCheckExist = $this->fnCheck($ArrUpdateData['vendor_name'], $VarId);
            if (empty($ArrCheckExist)) {
                if ($this->db->update(KN_MASTER_FABRICVENDOR, $ArrUpdateData, array('id' => $VarId))) {
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
                $ArrResult['msg'] = "This fabric vendor already exists";
            }
        }
        return $ArrResult;
    }
    function fnCount($VarVendor_name = '', $VarContactPerson = '', $VarEmailId = '', $VarMobno = '',$VarVendorCategory = '',$Varprimarypdtline = '',$VarStatus = '', $VarAfilter = '') {
        $userInfo = fnGetUserLoggedInfo(1);
        $ArrWhere = array();
        if ($VarVendor_name <> '') {
            $ArrWhere[] = "b.vendor_name LIKE '%$VarVendor_name%'";
        }
        if ($VarContactPerson <> '') {
            $ArrWhere[] = "b.contactperson LIKE '%$VarContactPerson%' ";
        }
        if ($VarEmailId <> '') {
            $ArrWhere[] = "b.emailid LIKE '%$VarEmailId%' ";
        }
        if ($VarMobno <> '') {
            $ArrWhere[] = "b.mobile like '%" . $VarMobno . "%'";
        }
        if ($VarVendorCategory <> '') {
            $ArrWhere[] = "b.vendor_categoryid like '%" . $VarVendorCategory . "%'";
        }
        if ($Varprimarypdtline <> '') {
            $ArrWhere[] = "b.primary_pdtline like '%" . $Varprimarypdtline . "%'";
        }
        if ($VarAfilter <> '') {
            $ArrWhere[] = "b.vendor_name LIKE '$VarAfilter%' ";
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
        $VarSqlLab = "SELECT count(1) as trec  FROM " . KN_MASTER_FABRICVENDOR . " AS b LEFT JOIN ".KN_USERS." AS u ON b.updatedby = u.id WHERE " . $VarWhere;
        $ObjRows = $this->db->query($VarSqlLab)->row();
        return $ObjRows->trec;
    }
    function fnList($VarVendor_name = '', $VarContactPerson = '', $VarEmailId = '', $VarMobno = '',$VarVendorCategory = '',$Varprimarypdtline = '',$VarStatus = '', $VarAfilter = '', $VarLimit = 10, $offset = 0, $VarSortBy, $VarSortOrder='') {
        $userInfo = fnGetUserLoggedInfo(1);
        $VarSortOrder = ($VarSortOrder == 'desc') ? 'desc' : 'asc';
        $VarSortCols = array('vendor_name', 'contactperson', 'emailid', 'phone', 'mobile', 'contactname', 'status', 'dateupdated');
        $VarSortBy = (in_array($VarSortBy, $VarSortCols)) ? $VarSortBy : 'b.dateupdated';
        $VarLimitInfo = $VarLimit;
        if ($offset >= 1) {
            $VarLimitInfo = $offset . "," . $VarLimit;
        }
        $ArrWhere = array();
        if ($VarVendor_name <> '') {
            $ArrWhere[] = "b.vendor_name LIKE '%$VarVendor_name%'";
        }
        if ($VarContactPerson <> '') {
            $ArrWhere[] = "b.contactperson LIKE '%$VarContactPerson%' ";
        }
        if ($VarEmailId <> '') {
            $ArrWhere[] = "b.emailid LIKE '%$VarEmailId%' ";
        }
        if ($VarMobno <> '') {
            $ArrWhere[] = "b.mobile like '%" . $VarMobno . "%'";
        }
        if ($VarVendorCategory <> '') {
            $ArrWhere[] = "b.vendor_categoryid like '%" . $VarVendorCategory . "%'";
        }
        if ($Varprimarypdtline <> '') {
            $ArrWhere[] = "b.primary_pdtline like '%" . $Varprimarypdtline . "%'";
        }
        if ($VarAfilter <> '') {
            $ArrWhere[] = "b.vendor_name LIKE '$VarAfilter%' ";
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
        // removed this line  . " limit " . $VarLimitInfo in below query
        $VarSqlLab = "SELECT b.id,b.vendor_name,b.vendor_categoryid,b.emailid,b.phone,b.mobile,b.contactperson,b.primary_pdtline,b.datecreated,b.dateupdated,b.status,u.contactname 
                      FROM " . KN_MASTER_FABRICVENDOR . " AS b LEFT JOIN ".KN_USERS." AS u ON b.updatedby = u.id WHERE " . $VarWhere . " order by " . $VarSortBy . " " . $VarSortOrder;
        $ObjResult = $this->db->query($VarSqlLab);
        return $ObjResult;
    }
    function fnDel($VarId = '', $VarUpdatedBy = '') {
        if (@$VarId >= 1) {
            $ArrUpdateData = array('status' => 3, 'dateupdated' => date('Y-m-d H:i:s'), 'updatedby' => $VarUpdatedBy);
            if ($this->db->update(KN_MASTER_FABRICVENDOR, $ArrUpdateData, array('id' => $VarId))) {
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
}