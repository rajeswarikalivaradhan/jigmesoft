 <?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
class Subscribermodel extends CI_Model {
    function saveInfo($ArrSaveData) {
        $VarId = $ArrSaveData['id'];
        $ArrResult = [];
        if ($VarId == "") {
            $ArrCheckExist = $this->fnGetInfo($ArrSaveData['companyname'], 1,'','');
            if (empty($ArrCheckExist)) {
                unset($ArrSaveData['id']);
                $this->db->insert(KN_SUBSCRIBERENQUIRY, $ArrSaveData);
                $VarInsId = $this->db->insert_id();
                $ArrResult['errcode'] = 1;
                $ArrResult['msg'] = '';
                $ArrResult['mode'] = 'inserted';
                $ArrResult['id'] = $VarInsId;
                $ArrResult['eid'] = urlencode(base64_encode($VarInsId));
            } else {
                $ArrResult['errcode'] = -1;
                $ArrResult['msg'] = "This company name already exists";
            }
        } else {
            $ArrCheckExist = $this->fnCheck($ArrSaveData['companyname'], $VarId,'');
            if (empty($ArrCheckExist)) {
                if ($this->db->update(KN_SUBSCRIBERENQUIRY, $ArrSaveData, array('id' => $VarId))) {
                    $ArrResult['errcode'] = 1;
                    $ArrResult['msg'] = '';
                    $ArrResult['mode'] = 'updated';
                    $ArrResult['id'] = $VarId;
                    $ArrResult['eid'] = urlencode(base64_encode($VarId));
                } else {
                    $ArrResult['errcode'] = -1;
                    $ArrResult['msg'] = 'Invalid Data';
                }
            } else {
                $ArrResult['errcode'] = -1;
                $ArrResult['msg'] = "This company name already exists";
            }

        }
        return $ArrResult;
    }
    function saveDraftInfo($ArrSaveData) {
        $VarId = $ArrSaveData['id'];
        $ArrResult = [];
        if ($VarId == "") {
            unset($ArrSaveData['id']);
             if ($this->db->insert(KN_SUBSCRIBERENQUIRY, $ArrSaveData)){
                 $VarInsId = $this->db->insert_id();
                $ArrResult['errcode'] = 1;
                $ArrResult['msg'] = '';
                $ArrResult['id'] = $VarInsId;
                $ArrResult['eid'] = urlencode(base64_encode($VarInsId));
             }  else {
                $ArrResult['errcode'] = -1;
                $ArrResult['msg'] = 'Invalid Data';
            }
            
        } else {
           
            if ($this->db->update(KN_SUBSCRIBERENQUIRY, $ArrSaveData, array('id' => $VarId))) {
                $ArrResult['errcode'] = 1;
                $ArrResult['msg'] = '';
                $ArrResult['id'] = $VarId;
                $ArrResult['eid'] = urlencode(base64_encode($VarId));
            } else {
                $ArrResult['errcode'] = -1;
                $ArrResult['msg'] = 'Invalid Data';
            }

        }
        return $ArrResult;
    }
    function fnGetInfo($VarData, $VarStatus, $VarId = '',$VarCompanyId='') {
        $this->db->select('se.*,DATE_FORMAT(se.reqdatetime,\'%d-%m-%Y\ %H:%i %p\') as reqdatetime,u.contactname as request_raised_by')->from(KN_SUBSCRIBERENQUIRY . ' AS se');
        $this->db->join(KN_USERS . ' AS u', 'u.id = se.mrkt_dept_userid','LEFT');
        if ($VarData <> '') {
            $ArrWhere['companyname'] = trim($VarData);
        }
        // if ($VarStatus <> '') {
        //     $this->db->where_in('status', array($VarStatus));
        // } else {
        //     $this->db->where_in('status', array(1, 2));
        // }
        if ($VarId <> '') {
            $ArrWhere['se.id'] = $VarId;
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
        $this->db->from(KN_SUBSCRIBERENQUIRY);
        //$ArrWhere = array('status' => "1"); 
        if ($VarId <> '') {
            $this->db->where_not_in('id', array($VarId));
        }
        if ($VarData <> '') {
            $ArrWhere['companyname'] = trim($VarData);
        }
        if ($VarCompanyId <> '') {
            $ArrWhere['companyid'] = $VarCompanyId;
        }
        $countAll = $this->db->where($ArrWhere)->count_all_results();
        return $countAll;
    }

    function fnCount($Varcmpny, $Varcntperson,$Varmobno,$Varcty,$Varpckdetid,$Varreqstatus) {
        $ArrWhere = array();
        if ($Varcmpny <> '') {
            $ArrWhere[] = "b.companyname=" . "'$Varcmpny'";
        }
        if ($Varcntperson <> '') {
            $ArrWhere[] = "b.contactperson=" . "'$Varcntperson'";
        }
        if ($Varmobno <> '') {
            $ArrWhere[] = "b.mobile_no=" . "'$Varmobno'";
        } 
        if ($Varcty <> '') {
            $ArrWhere[] = "b.city=" . "'$Varcty'";
        } 
        if ($Varpckdetid <> '') {
            $ArrWhere[] = "b.package_id=" . "'$Varpckdetid'";
        }
        if ($Varreqstatus <> '') {
            $ArrWhere[] = "b.requeststatus=" . "'$Varreqstatus'";
        }
        $ArrUserLoggedInfo = fnGetUserLoggedInfo('1');
        $ArrWhere[] = "b.requeststatus <> 2 AND b.draft_status=2 AND b.mrkt_dept_userid=$ArrUserLoggedInfo[id]";
        $VarWhere = '';
        if (!empty($ArrWhere)) {
            $VarWhere = 'WHERE ' . implode(" and ", $ArrWhere);
        }
        $VarSqlLab = "SELECT count(1) as trec  FROM " . KN_SUBSCRIBERENQUIRY . " AS b " . $VarWhere;
        $ObjRows = $this->db->query($VarSqlLab)->row();
        return $ObjRows->trec;
    }
    
    function fnList($Varcmpny, $Varcntperson,$Varmobno,$Varcty,$Varpckdetid,$Varreqstatus,$VarLimit = 10, $offset = 0, $VarSortBy, $VarSortOrder) {
        $VarSortOrder = ($VarSortOrder == 'desc') ? 'desc' : 'asc';
        $VarSortCols = array('companyname', 'b.status', 'b.id'); // already based on updated date
        //$VarSortBy = (in_array($VarSortBy, $VarSortCols)) ? $VarSortBy : 'b.id';
          $VarSortBy = (in_array($VarSortBy, $VarSortCols)) ? $VarSortBy : 'b.log';
        $VarLimitInfo = $VarLimit;
        if ($offset >= 1) {
            $VarLimitInfo = $offset . "," . $VarLimit;
        }
        $ArrWhere = array();
        if ($Varcmpny <> '') {
            $ArrWhere[] = "b.companyname=" . "'$Varcmpny'";
        }
        if ($Varcntperson <> '') {
            $ArrWhere[] = "b.contactperson=" . "'$Varcntperson'";
        }
        if ($Varmobno <> '') {
            $ArrWhere[] = "b.mobile_no=" . "'$Varmobno'";
        } 
        if ($Varcty <> '') {
            $ArrWhere[] = "b.city=" . "'$Varcty'";
        } 
        if ($Varpckdetid <> '') {
            $ArrWhere[] = "b.package_id=" . "'$Varpckdetid'";
        }
        if ($Varreqstatus <> '') {
            $ArrWhere[] = "b.requeststatus=" . "'$Varreqstatus'";
        }
        $ArrUserLoggedInfo = fnGetUserLoggedInfo('1');
        $ArrWhere[] = "b.requeststatus <> 2 AND b.draft_status=2 AND b.mrkt_dept_userid=$ArrUserLoggedInfo[id]";
        $VarWhere = '';
        if (!empty($ArrWhere)) {
            $VarWhere = 'WHERE ' . implode(" and ", $ArrWhere);
        }
        
         $VarSqlLab = "SELECT b.id,b.companyname,b.contactperson,b.email_id,b.mobile_no,b.city,b.state,b.package_id,b.requeststatus,DATE_FORMAT(b.log,\"%d/%m/%Y %h:%i %p\") as formattedDate,b.status
                      FROM " . KN_SUBSCRIBERENQUIRY . " AS b LEFT JOIN " . KN_USERS . " AS u ON b.mrkt_dept_userid = u.id " . $VarWhere . " order by " . $VarSortBy . " " . $VarSortOrder;
                    $ObjResult = $this->db->query($VarSqlLab);
                    return $ObjResult;
    }
    function fnChangeComStat($checkboxids, $optvalue) {
        $Arrids = json_decode($checkboxids, true);
        $this->db->where_in('id', $Arrids);
        if ($this->db->update(KN_SUBSCRIBERENQUIRY, array('status' => $optvalue))) {
            return true;
        }
        return false;
    }
    
     public function checkDraftorNot($id)
    {
        $result = $this->db->from(KN_SUBSCRIBERENQUIRY)->where('id', $id)->where('draft_status', 1)->get()->num_rows();
        return $result;
    }
    public function getdraftdata()
    {
        $result = $this->db->from(KN_SUBSCRIBERENQUIRY)->where('draft_status', 1)->get()->row();
        return $result;
    }
    
    public function cleardraft($id)
    {   
        if($this->db->delete(KN_SUBSCRIBERENQUIRY,array('id' => $id))){
        $ArrResult['success']					    = 1;
        }else{
        $ArrResult['success']					    = 0;
        }
        return $ArrResult;
    }
    function updatesubscriberInfo($ArrSaveData) {
        $VarId = $ArrSaveData['id'];
        $ArrResult = [];
        if (!empty($VarId)) {
           if ($this->db->update(KN_PROFORMAINVOICE, $ArrSaveData, array('id' => $VarId))) {
                $ArrResult['errcode'] = 1;
                $ArrResult['msg'] = '';
                $ArrResult['id'] = $VarId;
                $ArrResult['eid'] = urlencode(base64_encode($VarId));
            } else {
                $ArrResult['errcode'] = -1;
                $ArrResult['msg'] = 'Invalid Data';
            } 
            
        } 
        return $ArrResult;
    }
}