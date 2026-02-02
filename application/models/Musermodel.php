<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
class Musermodel extends CI_Model {
    public function fnGetInfo($VarLoginId = '', $VarStatus = '', $VarUserId = '', $VarCompanyId = '') {
        //$this->db->select('id,contactname,mobile,username,status,desgnid,desgn')->from(KN_MUSERS)->join(KN_USER_DESGN,'desgnid = designationid');
        $this->db->select('*')->from(KN_MUSERS);
        // if ($VarStatus <> '') {
        //     $this->db->where_in('status', array($VarStatus));
        // } else {
        //     $this->db->where_in('status', array(1, 2));
        // }
        if ($VarUserId <> '') {
            $ArrWhere['id'] = $VarUserId;
        }
        if ($VarLoginId <> '') {
            $ArrWhere['username'] = trim($VarLoginId);
        }
        /*if ($VarCompanyId <> '') {
            $ArrWhere['companyid'] = $VarCompanyId;
        }*/
        if (!empty($ArrWhere)) {
            $this->db->where($ArrWhere);
        }
        $ArrResultList = $this->db->get()->result_array();
        return $ArrResultList;
    }
    function fnCheck($VarData = '', $VarId = '') {
        $this->db->from(KN_MUSERS);
        //$ArrWhere = array('status' => "1");
        if ($VarId <> '') {
            $this->db->where_not_in('id', array($VarId));
        }
        if ($VarData <> '') {
            $ArrWhere['username'] = trim($VarData);
        }
        $countAll = $this->db->where($ArrWhere)->count_all_results();
        return $countAll;
    }

    public function saveMerchantUser($ArrUpdateData) {
        $VarId = $ArrUpdateData['id'];
        if ($VarId == "") {
            $ArrCheckExist = $this->fnGetInfo($ArrUpdateData['username'], 1, '');
            if (empty($ArrCheckExist)) {
                unset($ArrUpdateData['id']);
                $this->db->insert(KN_MUSERS, $ArrUpdateData);
                $VarUserId = $this->db->insert_id();
                $VarSql = 'SELECT COUNT(*) as nos FROM '.KN_MUSERS.' WHERE companyid = '.$ArrUpdateData['companyid'].' AND status = 1';
                $Result = $this->db->query($VarSql)->result();
                $VarCompanyUserNo = empty($Result[0]->nos) ? 0 : $Result[0]->nos;
                $this->db->insert(KN_MERCHANT_TEAM, array('merchantid'=>$VarUserId,'companyid'=>$ArrUpdateData['companyid']));
                $ArrShortForm = UT_SHORT_FORM;
                $VarUserTypeCode = $ArrShortForm[3];
                $VarCode = $ArrUpdateData['companyid'].'/'.$VarUserTypeCode.'-'.$VarCompanyUserNo.'/'.$VarUserId;
                $ArrCadData = array('userid' => $VarUserId, 'code' => $VarCode);
                $this->db->insert(KN_USER_DETAILS, $ArrCadData);
                $ArrResult['errcode'] = 1;
                $ArrResult['msg'] = '';
                $ArrResult['id'] = $VarId;
                $ArrResult['eid'] = urlencode(base64_encode($VarUserId));
            } else {
                $ArrResult['errcode'] = -1;
                $ArrResult['msg'] = "This Login Id already exists";
            }
        } else {
            $ArrCheckExist = $this->fnCheck($ArrUpdateData['username'], $VarId);
            if (empty($ArrCheckExist)) {
                $this->db->update(KN_MUSERS, $ArrUpdateData, array('id' => $VarId));
                $ArrResult['errcode'] = 1;
                $ArrResult['msg'] = '';
                $ArrResult['id'] = $VarId;
                $ArrResult['eid'] = urlencode(base64_encode($VarId));
            } else {
                $ArrResult['errcode'] = -1;
                $ArrResult['msg'] = 'This Login Id already exists';
            }
        }
        return $ArrResult;
    }
    public function saveUser($ArrUpdateData) {
        $VarId = $ArrUpdateData['id'];
        if ($VarId == "") {
            $ArrCheckExist = $this->fnGetInfo($ArrUpdateData['username'], 1, '');
            if (empty($ArrCheckExist)) {
                unset($ArrUpdateData['id']);
                $this->db->insert(KN_MUSERS, $ArrUpdateData);
                $VarUserId = $this->db->insert_id();
                $VarSql = 'SELECT COUNT(*) as nos FROM '.KN_MUSERS.' WHERE companyid = '.$ArrUpdateData['companyid'].' AND status = 1';
                $Result = $this->db->query($VarSql)->result();
                $VarCompanyUserNo = empty($Result[0]->nos) ? 0 : $Result[0]->nos;
                $ArrShortForm = UT_SHORT_FORM;
                $VarUserTypeCode = $ArrShortForm[$ArrUpdateData['usertype']];
                $VarCode = $ArrUpdateData['companyid'].'/'.$VarUserTypeCode.'-'.$VarCompanyUserNo.'/'.$VarUserId;
                $ArrCadData = array('userid' => $VarUserId, 'code' => $VarCode);
                $this->db->insert(KN_USER_DETAILS, $ArrCadData);
                $ArrResult['errcode'] = 1;
                $ArrResult['msg'] = '';
                $ArrResult['id'] = $VarId;
                $ArrResult['eid'] = urlencode(base64_encode($VarUserId));
            } else {
                $ArrResult['errcode'] = -1;
                $ArrResult['msg'] = "This Login Id already exists";
            }
        } else {
            $ArrCheckExist = $this->fnCheck($ArrUpdateData['username'], $VarId);
            if (empty($ArrCheckExist)) {
                $this->db->update(KN_MUSERS, $ArrUpdateData, array('id' => $VarId));
                $ArrResult['errcode'] = 1;
                $ArrResult['msg'] = '';
                $ArrResult['id'] = $VarId;
                $ArrResult['eid'] = urlencode(base64_encode($VarId));
            } else {
                $ArrResult['errcode'] = -1;
                $ArrResult['msg'] = 'This Login Id already exists';
            }
        }
        return $ArrResult;
    }

    public function whereCond($VarDept = '', $VarUsername='',$VarDesgn='',$VarLoginid = '',$VarEmail_id='',$VarMobno='',$VarStatus='',$varsubscriber_id='',$varproforma_id='') {
         // newly included u.usertype<>0 for filtering business admin user in user admin login
         $ArrUserType = unserialize(ARRUSERTYPE);
         $coutusertype = count($ArrUserType); 

        if ($VarDept <> '') {
            $ArrWhere[] = "usertype= '" . $VarDept . "'";
        }
        if ($VarUsername <> '') {
            $ArrWhere[] = "contactname ='" . $VarUsername . "'";   
        }
        if ($VarDesgn <> '') {
            $ArrWhere[] = "designation ='" . $VarDesgn . "'"; 
        }
        if ($VarLoginid <> '') {
            $ArrWhere[] = "username ='" . $VarLoginid . "'"; 
        }
        if ($VarEmail_id <> '') {
            $ArrWhere[] = "email_id = '" . $VarEmail_id . "'";
        }
        if ($VarMobno <> '') {
            $ArrWhere[] = "mobile = '" . $VarMobno . "'"; 
        }
        if ($VarStatus <> '') {
            $ArrWhere[] = "status=" . $VarStatus;
        } else {
            $ArrWhere[] = "status in(1,2) AND usertype not in(0,$coutusertype)";
        }
        if ($varsubscriber_id <> '') {
            $ArrWhere[] = "subscriber_id = '" . $varsubscriber_id . "'"; 
            $ArrWhere[] = "badminstatus = '" . 1 . "'"; //newly added
        }else{
            $ArrWhere[] = "subscriber_id IS NULL"; 
        }
        if ($varproforma_id <> '') {
            $ArrWhere[] = "proforma_id = '" . $varproforma_id . "'"; 
        }
        
        $VarWhere = " WHERE ". implode(" AND ", $ArrWhere);
        return $VarWhere;
    }

    public function fnCount($VarDept = '', $VarUsername='',$VarDesgn='',$VarLoginid = '', $VarEmail_id='',$VarMobno='',$VarStatus='') {
        $VarWhere = $this->whereCond($VarDept, $VarUsername,$VarDesgn,$VarLoginid,$VarEmail_id,$VarMobno,$VarStatus);
        $VarSql = "SELECT count(1) as trec  FROM " . KN_MUSERS . "" . $VarWhere;
        $ObjRows = $this->db->query($VarSql)->row();
        return $ObjRows->trec;
    }
     public function fnsubuserCount($VarDept = '',$VarUsername='',$VarDesgn='',$VarLoginid = '', $VarEmail_id='',$VarMobno='',$VarStatus='',$VarSubscriber_id = '',$VarProforma_id = '') {
        $VarWhere = $this->whereCond($VarDept, $VarUsername,$VarDesgn,$VarLoginid,$VarEmail_id,$VarMobno,$VarStatus,$VarSubscriber_id,$VarProforma_id);
        $VarSql = "SELECT count(1) as trec  FROM " . KN_MUSERS . "" . $VarWhere;
        $ObjRows = $this->db->query($VarSql)->row();
        return $ObjRows->trec;
    }
    function fnList($VarDept = '', $VarUsername='',$VarDesgn='',$VarLoginid = '', $VarEmail_id='',$VarMobno='',$VarStatus='',$VarLimit = 10, $offset = 0, $VarSortBy, $VarSortOrder) {
        $VarSortOrder = ($VarSortOrder == 'desc') ? 'desc' : 'asc';
        $VarSortCols = $this->ArrDbCols;
        $VarSortBy = (in_array($VarSortBy, $VarSortCols)) ? $VarSortBy : 'dateupdated';
        $VarLimitInfo = $VarLimit;
        $ArrUserLoggedInfo = fnGetUserLoggedInfo('1');
        $varsubscriber_id=$ArrUserLoggedInfo['subscriber_id'];
        if ($offset >= 1) {
            $VarLimitInfo = $offset . "," . $VarLimit;
        }
        if(isset($varsubscriber_id) && !empty($varsubscriber_id)){ 
            //$ArrWhere['subscriber_id'] = $varsubscriber_id;;
            $VarWhere = $this->whereCond($VarDept, $VarUsername,$VarDesgn,$VarLoginid,$VarEmail_id,$VarMobno,$VarStatus,$varsubscriber_id);

        }else{
            $VarWhere = $this->whereCond($VarDept, $VarUsername,$VarDesgn,$VarLoginid,$VarEmail_id,$VarMobno,$VarStatus);
        }
        
       
        $VarSql = "SELECT * FROM " . KN_MUSERS . " AS u " . $VarWhere . " order by " . $VarSortBy . " " . $VarSortOrder;
        $ArrObjResult = $this->db->query($VarSql)->result(); 
        $ArrUpdatedbyId = $ArrUpdatedBy = [];
        if (!empty($ArrObjResult)) {
            foreach ($ArrObjResult as $Obj) {
                $ArrUpdatedbyId[] = $Obj->updatedby;
            }
        }
        if(!empty($ArrUpdatedbyId)) {
            $ArrObjUserInfo = $this->commonmodel->getUserInfo('', '', '', $ArrUpdatedbyId);
            if (!empty($ArrObjUserInfo)) {
                foreach ($ArrObjUserInfo as $userObj) {
                    $ArrUpdatedBy[$userObj['id']] = $userObj['contactname'];
                }
            }
        }
        return array('listData' => $ArrObjResult, 'updatedByData' => $ArrUpdatedBy);
        //return $this->db->query($VarSql);
    }
    public function fnGetRoleInfo($VarUserId = '', $VarCompanyId = '') {
        
        $this->db->select('GROUP_CONCAT(title) AS title')->from(KN_USERROLE_PERMISSION);
        
        if ($VarUserId <> '') {
            $ArrWhere['userid'] = $VarUserId;
        }
       
        /*if ($VarCompanyId <> '') {
            $ArrWhere['companyid'] = $VarCompanyId;
        }*/
        if (!empty($ArrWhere)) {
            $this->db->where($ArrWhere);
        }
        $ArrResultList = $this->db->get()->result_array();
        return $ArrResultList;
    }
    // public function changeStatus($ArrIds,$VarActDeactOption) {
    //     $this->db->where_in('id', $ArrIds);
    //     if($this->db->update(KN_MUSERS, array('status' => $VarActDeactOption))) $ArrResult = array('errcode'=>1);
    //     else $ArrResult = array('errcode'=>-1);
    //     return $ArrResult;
    // }
}