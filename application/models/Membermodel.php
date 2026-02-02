<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class membermodel extends CI_Model
{
    public function checklogin($VarEmail = '', $VarPass = '', $Validate = 1, $VarUserId = '')
    {
        $this->db->select('id,usertype,username,contactname,status,datecreated,dateupdated');
        $ArrWhere = array('status' => 1);
        if ($VarEmail <> '') {
            $ArrWhere['username'] = $VarEmail;
        }
        if ($VarPass <> '') {
            $ArrWhere['password'] = $VarPass;
        }
        if ($VarUserId <> '') {
            $ArrWhere['id'] = $VarUserId;
        }
        $ArrWhere['usertype'] = '2 OR 3';
        $this->db->where($ArrWhere);
        $ObjUserInfo = $this->db->get(KN_USERS);
        if ($Validate == 1) {
            return $ObjUserInfo->num_rows();
        } elseif ($Validate == 2) {
            return $ObjUserInfo->row();
        }
    }

    public function keepMeSigned($cookieUid)
    {
        $this->db->select('id,usertype,username,companyid,status,usertype,datecreated,dateupdated');
        $ArrWhere = array('status' => 1, 'usertype' => 3);
        if ($cookieUid <> '') {
            $ArrWhere['id'] = $cookieUid;
        }
        $VarWhere = implode(" AND ", $ArrWhere);
        $VarSql = $this->db->query("SELECT id,usertype,username,companyid,status,usertype,datecreated,dateupdated FROM " . KN_USERS . " WHERE " . $VarWhere . " LIMIT 1");
        return $VarSql->row();
    }

    public function updateMember($VarUserId = '', $ArrUser = array(), $ArrPassWord = array())
    {
        if ($VarUserId <> '') {
            unset($ArrUser['datecreated']);
            unset($ArrUser['username']);
            $this->db->update(KN_USERS, $ArrUser, array('id' => $VarUserId));
            return true;
        }
        if (count($ArrPassWord) >= 1) {
            $userid = $ArrPassWord['userid'];
            unset($ArrPassWord['userid']);
            $this->db->update(KN_USERS, $ArrPassWord, array('id' => $userid));
            return true;
        }
        if (count($ArrUser) >= 1) {
            $this->db->insert(KN_USERS, $ArrUser);
            return $this->db->insert_id();
        }
    }

    public function getProfile($VarUserId, $VarEmail = '', $VarStatus = '', $VarUserType = '')
    {
        $ArrWhere = array();
        $VarWhere = '';
        if ($VarUserId <> '') {
            $ArrWhere[] = "u.id = " . $VarUserId;
        }
        if ($VarEmail <> '') {
            $ArrWhere[] = "u.username = '$VarEmail'";
        }
        $ArrWhere[] = "u.status = " . $VarStatus;
        $VarWhere = implode(' AND ', $ArrWhere);
        if ($VarUserType <> '') {
            $VarSql = $this->db->query("SELECT id,usertype,username,address,contactname,mobile,datecreated,contactname,companyid,city,state,zipcode FROM " . KN_USERS . " AS u             WHERE " . $VarWhere . " AND u.usertype = " . $VarUserType);
        } else {
            $VarSql = $this->db->query("SELECT u.id,username,usertype,u.datecreated,u.contactname,ccd.contactmobile,ccd.contactphone,cd.address,cd.city,cd.state,cd.zipcode FROM " . KN_USERS . " AS u INNER JOIN " . KN_COMPANY_DETAILS . " AS cd ON u.companyid = cd.id INNER JOIN " . KN_COMPANY_CONTACT_DETAILS . " AS ccd ON ccd.companyid = cd.id WHERE " . $VarWhere);
        }
        return $VarSql->row();
    }

    public function updatePassword($VarEmail='',$VarPassword='') {

    }

}