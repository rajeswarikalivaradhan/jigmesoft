<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
class Membelljobworkmodel extends CI_Model {
    function fnGetInfo($VarData = '', $VarStatus = '', $VarId = '')
    {
        $this->db->select('*');
        if ($VarData <> '') {
            $ArrWhere['jobwrkname'] = trim($VarData);
        }
        // if ($VarStatus <> '') {
        //     $this->db->where_in('status', array($VarStatus));
        // } else {
        //     $this->db->where_in('status', array(1, 2));
        // }
        if ($VarId <> '') {
            $ArrWhere['id'] = $VarId;
        }
        $ArrWhere['type'] = '2';
        if (!empty($ArrWhere)) {
            $this->db->where($ArrWhere);
        }
        return $this->db->from(KN_MASTER_JOBWRK)->get()->result_array();
    }
    function fnCheck($VarData = '', $VarId = '') {
        $this->db->from(KN_MASTER_JOBWRK);
        //$ArrWhere = array('status' => "1");
        if ($VarId <> '') {
            $this->db->where_not_in('id', array($VarId));
        }
        if ($VarData <> '') {
            $ArrWhere['jobwrkname'] = trim($VarData);
        }
        $ArrWhere['type'] = '2';
        $countAll = $this->db->where($ArrWhere)->count_all_results();
        return $countAll;
    }

    function saveInfo($ArrUpdateData) {
        $VarId = $ArrUpdateData['id'];
        if ($VarId == "") {
            $ArrCheckExist = $this->fnGetInfo($ArrUpdateData['jobwrkname'], 1);
            if (empty($ArrCheckExist)) {
                unset($ArrUpdateData['id']);
                $this->db->insert(KN_MASTER_JOBWRK, $ArrUpdateData);
                $VarInsertId = $this->db->insert_id();
                $ArrResult['errcode'] = 1;
                $ArrResult['msg'] = '';
                $ArrResult['id'] = $VarInsertId;
                $ArrResult['eid'] = urlencode(base64_encode($VarInsertId));
            } else {
                $ArrResult['errcode'] = -1;
                $ArrResult['msg'] = "This embellishment job work already exists";
            }
        } else {
            $ArrCheckExist = $this->fnCheck($ArrUpdateData['jobwrkname'], $VarId);
            if (empty($ArrCheckExist)) {
                if ($this->db->update(KN_MASTER_JOBWRK, $ArrUpdateData, array('id' => $VarId))) {
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
                $ArrResult['msg'] = "This embellishment job work already exists";
            }
        }
        return $ArrResult;
    }
    function fnCount($VarJobwrkname = '', $VarContactPerson = '', $VarEmailId = '', $VarMobno = '',$VarJobwrkCategory = '',$Varprimaryjobln = '',$VarStatus = '', $VarAfilter = '') {
        $userInfo = fnGetUserLoggedInfo(1);
        $ArrWhere = array();
        if ($VarJobwrkname <> '') {
            $ArrWhere[] = "b.jobwrkname LIKE '%$VarJobwrkname%'";
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
        if ($VarJobwrkCategory <> '') {
            $ArrWhere[] = "b.wrk_categoryid like '%" . $VarJobwrkCategory . "%'";
        }
        if ($Varprimaryjobln <> '') {
            $ArrWhere[] = "b.primaryjob_wrkline like '%" . $Varprimaryjobln . "%'";
        }
        if ($VarAfilter <> '') {
            $ArrWhere[] = "b.jobwrkname LIKE '$VarAfilter%' ";
        }
        if ($VarStatus <> '') {
            $ArrWhere[] = "b.status=" . $VarStatus;
        } else {
            $ArrWhere[] = "b.status in(1,2)";
        }
        $ArrWhere[] = "b.companyid = " . $userInfo['companyid'];
        $ArrWhere[] = "b.type = '2' ";
        $VarWhere = '';
        if (@$ArrWhere[0] <> '') {
            $VarWhere = implode(" and ", $ArrWhere);
        }
        $VarSqlLab = "SELECT count(1) as trec  FROM " . KN_MASTER_JOBWRK . " AS b INNER JOIN ".KN_USERS." AS u ON b.updatedby = u.id WHERE " . $VarWhere;
        $ObjRows = $this->db->query($VarSqlLab)->row();
        return $ObjRows->trec;
    }
    function fnList($VarJobwrkname = '', $VarContactPerson = '', $VarEmailId = '', $VarMobno = '',$VarJobwrkCategory = '',$Varprimaryjobln = '',$VarStatus = '', $VarAfilter = '', $VarLimit = 10, $offset = 0, $VarSortBy, $VarSortOrder='') {
        $userInfo = fnGetUserLoggedInfo(1);
        $VarSortOrder = ($VarSortOrder == 'desc') ? 'desc' : 'asc';
        $VarSortCols = array('jobwrkname', 'contactperson', 'emailid', 'phone', 'mobile', 'contactname', 'status', 'dateupdated');
        $VarSortBy = (in_array($VarSortBy, $VarSortCols)) ? $VarSortBy : 'b.dateupdated';
        $VarLimitInfo = $VarLimit;
        if ($offset >= 1) {
            $VarLimitInfo = $offset . "," . $VarLimit;
        }
        $ArrWhere = array();
        if ($VarJobwrkname <> '') {
            $ArrWhere[] = "b.jobwrkname LIKE '%$VarJobwrkname%'";
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
        if ($VarJobwrkCategory <> '') {
            $ArrWhere[] = "b.wrk_categoryid like '%" . $VarJobwrkCategory . "%'";
        }
        if ($Varprimaryjobln <> '') {
            $ArrWhere[] = "b.primaryjob_wrkline like '%" . $Varprimaryjobln . "%'";
        }
        if ($VarAfilter <> '') {
            $ArrWhere[] = "b.jobwrkname LIKE '$VarAfilter%' ";
        }
        if ($VarStatus <> '') {
            $ArrWhere[] = "b.status=" . $VarStatus;
        } else {
            $ArrWhere[] = "b.status in(1,2)";
        }
        $ArrWhere[] = "b.companyid = " . $userInfo['companyid'];
        $ArrWhere[] = "b.type = '2' ";
        $VarWhere = '';
        if (@$ArrWhere[0] <> '') {
            $VarWhere = implode(" and ", $ArrWhere);
        }
        // removed this line  . " limit " . $VarLimitInfo in below query
        $VarSqlLab = "SELECT b.id,jobwrkname,b.wrk_categoryid,b.emailid,b.phone,b.mobile,b.contactperson,b.primaryjob_wrkline,b.datecreated,b.dateupdated,b.status,u.contactname 
                      FROM " . KN_MASTER_JOBWRK . " AS b INNER JOIN ".KN_USERS." AS u ON b.updatedby = u.id WHERE " . $VarWhere . " order by " . $VarSortBy . " " . $VarSortOrder;
        $ObjResult = $this->db->query($VarSqlLab);
        return $ObjResult;
    }
    function fnDel($VarId = '', $VarUpdatedBy = '') {
        if (@$VarId >= 1) {
            $ArrUpdateData = array('status' => 3, 'dateupdated' => date('Y-m-d H:i:s'), 'updatedby' => $VarUpdatedBy);
            if ($this->db->update(KN_MASTER_JOBWRK, $ArrUpdateData, array('id' => $VarId))) {
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