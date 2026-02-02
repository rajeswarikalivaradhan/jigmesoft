<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class login extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model("loginmodel");
        $this->load->model(CNFBADMIN . "subscriptionmodal");
        $this->load->helper('xssclean');
        //print_r($_SESSION);
    }
    public function index() {
        //echo '<pre>'; print_r($this->session->all_userdata()); die();
        //echo '<pre>'; print_r($this->session->userdata('UI'));
        $userinfo = $this->session->userdata('UI');
        //echo '<pre>'; print_r($userinfo); die;
        if (isset($userinfo) && $userinfo['id'] >= 1) {
            redirect(base_url('dashboard'));
        } else {
            $this->load->view('login');
        }
    }
    public function validate() {
        $VarUsername = xssclean($this->input->post('e'));
        /* Password removed */
        //$VarPassword									= base64_encode(xssclean($this->input->post('p')));
        $VarPassword = xssclean($this->input->post('p'));
        $ObjUserInfo = $this->loginmodel->fnValidate($VarUsername, $VarPassword, 2);
        $userinfo=$this->loginmodel->fngetuser($VarUsername);
        $ArrResult = array();
        if (!empty($ObjUserInfo)) {
            $ArrSess = array("UI" => array('id' => $ObjUserInfo->id,'subscriber_id'=> $ObjUserInfo->subscriber_id,
                'name' => $ObjUserInfo->contactname, 'email' => $ObjUserInfo->username, 'desgnid' => $ObjUserInfo->desgnid,'dept_usercount'=>$ObjUserInfo->dept_usercount,
                'usertype' => $ObjUserInfo->usertype, 'companyid' => $ObjUserInfo->companyid, 'pp' => $ObjUserInfo->profilepermission, 'mobile' => $ObjUserInfo->mobile));
            $this->session->set_userdata($ArrSess);
            $ArrResult['errcode'] = '1';
            $ArrResult['ut'] = $ObjUserInfo->usertype;
            $ArrResult['msg'] = "success";
        } else {
            if (!empty($userinfo)) {
            $subscinfo=$this->subscriptionmodal->fngetsubscriptionlist($userinfo->subscriber_id);
            }else{
            $subscinfo=[];
            }
            $ArrResult['errcode'] = '-1';
            $ArrResult['ut'] = 0;
            if (!empty($subscinfo)) {
                $noofdaysrenewal=$subscinfo[0]['renewal_daysleft'];
                if($noofdaysrenewal==0){
                    $ArrResult['msg'] = "Your account is on hold awaiting renewal. Kindly renew your account.";
                }else{
                    $ArrResult['msg'] = "Invalid Username/Password";
                }
            }else{
                 $ArrResult['msg'] = "Invalid Username/Password";
            }
            
        }
        echo json_encode($ArrResult);
        die;
    }
    function signout() {
        fnIfCheckUserLoggedIn();
        $ArrProfileInfo = fnGetUserLoggedInfo(1);
        if (empty($ArrProfileInfo)) {
            //$this->session->unset_userdata();
            //$this->session->sess_destroy();
            //$this->_userdata = '';
            //redirect(base_url(''));
        } else {
            if ($ArrProfileInfo['id'] >= 1) {
                $this->session->unset_userdata('UI');
                $this->session->sess_destroy();
                $this->_userdata = '';
                //echo '<pre>'; print_r($this->session->all_userdata()); die;
                redirect(base_url());
            } else {
                //redirect(base_url());
            }
        }
    }
    public function forgotpassword() {
        $VarFrom = xssclean($this->input->post('rfrom'));
        $VarEmail = xssclean($this->input->post('e'));

        if ($VarFrom == 1) {
            $ArrProfileInfo = $this->loginmodel->fnValidate($VarEmail, '', 2);
            $ArrResult = array();
            if (count($ArrProfileInfo) >= 1) {
                $ArrProfileStatus = $ArrProfileInfo[0];
                $VarName = $ArrProfileStatus['contactname'];
                $VarEmail = $ArrProfileStatus['username'];
                $VarUserId = $ArrProfileStatus['id'];
                $VarPassword = uniqid('fgt_');
                $VarDomainName = APPDOMAINNAME;
                $ArrSaveDetails = array('password' => $VarPassword, 'dateupdated' => date('Y-m-d H:i:s'));
                $VarResult = $this->loginmodel->fnUpdateUser($ArrSaveDetails, $VarUserId);
                if ($VarResult) {
                    $ArrEmailReplaceArgs = array("##NAME##" => $VarName, "##DOMAINNAME##" => $VarDomainName, "##EMAILID##" => $VarEmail, "##PASSWORD##" => $VarPassword,
                        "##DOMAINURL##" => base_url());
                    if (SendEmail($VarEmail, 'New Password Request', 'EmployeeForgotPassword', $ArrEmailReplaceArgs)) {
                        $ArrResult['errcode'] = 1;
                        $ArrResult['msg'] = "Your new password has been sent to your E-mail Id!";
                    }
                } else {
                    $ArrResult['errcode'] = -1;
                    $ArrResult['msg'] = "Invalid E-mail Id!";
                }
            } else {
                $ArrResult['errcode'] = -1;
                $ArrResult['msg'] = "Invalid E-mail Id!";
            }
            echo json_encode($ArrResult);
            die;
        }
    }
    public function masterBagCartonBox() {
        $this->load->view("masterBagCartonBox");
    }

    public function test_jxl_tabs() {
        $this->load->view("test_jxl_tabs");
    }
}