<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
class loginmodel extends CI_Model {
    function fnValidate($VarUserName = '', $VarPassword = '', $Validate = 1, $UserType = '') {
        // commonted due to usertablename change $this->db->select('id,usertype,username,companyid,contactname,
        // updatedby,status,profilepermission,desgnid,mobile')->from(KN_USERS);
        if(KN_USERS=='kn_mngusers'){
            $this->db->select('id,usertype,username,companyid,contactname,subscriber_id,updatedby,status,profilepermission,designation as desgnid,mobile,dept_usercount')->from(KN_USERS);
        }else{
            $this->db->select('id,usertype,username,companyid,contactname,updatedby,subscriber_id,status,profilepermission,desgnid,mobile,"" as dept_usercount')->from(KN_USERS);
        }
        
        /*$this->db->select('id,password,usertype,username,companyid,contactname,mobile,
        updatedby,status,profilepermission,datecreated,dateupdated,desgn')
            ->from(KN_USERS)->join(KN_USER_DESGN,'desgnid = designationid');*/
        $ArrWhere = array('status' => "1");
        if ($VarUserName <> '') {
            $ArrWhere['username'] = $VarUserName;
        }
        if ($VarPassword <> '') {
            $ArrWhere['password'] = $VarPassword;
        }
        if ($UserType <> '') {
            $ArrWhere['usertype'] = $UserType;
        }
        $ObjUserInfo = $this->db->where($ArrWhere)->get();
        if ($Validate == 1) {
            return $ObjUserInfo->num_rows();
        } else {
            return $ObjUserInfo->row();
        }
    }
    function fnGetUserInfo($VarUserId = '', $VarUserType = '', $VarProfilePermission = '', $VarStatus = '', $VarEmailId = '') {
        $this->db->select('id,username,updatedby,usertype,password,contactname,profileimg,subscriber_id,profilepermission,updatedby,status,datecreated,dateupdated'); // Select field
        $this->db->from(KN_USERS);
        if ($VarStatus <> '') {
            $this->db->where_in('status', array($VarStatus));
        } else {
            $this->db->where_in('status', array(1, 2));
        }
        if ($VarUserType <> '') {
            $ArrWhere['usertype'] = $VarUserType;
        }
        if ($VarEmailId > '') {
            $ArrWhere['emailid'] = $VarEmailId;
        }
        if ($VarUserId <> '') {
            $ArrWhere['id'] = $VarUserId;
        }
        if ($VarProfilePermission <> '') {
            $ArrWhere['profilepermission'] = $VarProfilePermission;
        }
        if (@count($ArrWhere) >= 1) {
            $this->db->where($ArrWhere);
        }
        $ArrEmployeeList = $this->db->get()->result_array();
        return $ArrEmployeeList;
    }

    public function fnCheckUser($VarId='',$VarPassword='',$VarUserType='') {
        $this->db->where('id',$VarId);
        $this->db->where('password',$VarPassword);
        $this->db->from(KN_USERS);
        $ObjResult = $this->db->get();
        $VarNumRows = $ObjResult->num_rows();
        return $VarNumRows;
    }

    public function fnUpdateUser($ArrData = array(),  $VarUserId='') {
        $this->db->where('id',$VarUserId);
        if($this->db->update(KN_USERS,$ArrData)) {
            return true;
        }
    }
    function fngetuser($VarUserName = '',$Varstatus='',$UserType='') {
        // commonted due to usertablename change $this->db->select('id,usertype,username,companyid,contactname,
        // updatedby,status,profilepermission,desgnid,mobile')->from(KN_USERS);
        if(KN_USERS=='kn_mngusers'){
            $this->db->select('id,usertype,username,companyid,contactname,subscriber_id,updatedby,status,profilepermission,designation as desgnid,mobile,dept_usercount')->from(KN_USERS);
        }else{
            $this->db->select('id,usertype,username,companyid,contactname,updatedby,subscriber_id,status,profilepermission,desgnid,mobile,"" as dept_usercount')->from(KN_USERS);
        }
        
        
        if ($VarUserName <> '') {
            $ArrWhere['username'] = $VarUserName;
        }
         if ($Varstatus <> '') {
            $ArrWhere['staus'] = $Varstatus;
        }
        
        if ($UserType <> '') {
            $ArrWhere['usertype'] = $UserType;
        }
        $ObjUserInfo = $this->db->where($ArrWhere)->get();
        return $ObjUserInfo->row();
    }
}