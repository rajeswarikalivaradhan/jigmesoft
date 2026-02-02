<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Dashboard extends CI_Controller {
    public function __construct() {
        parent::__construct();
        //fnIfCheckUserLoggedIn();
        $this->load->helper('xssclean');
        //$this->load->library('email');
        //$this->load->helper('email');
        //print_r($_SESSION);
    }
    public function index() {
        $this->load->model("commonmodel");
        $ArrProfileInfo   = fnGetUserLoggedInfo(1);
        //echo '<pre>'; print_r($ArrProfileInfo); die('die');
        $VarRemainingUser = $this->commonmodel->remaininguseravailable($ArrProfileInfo['companyid'], 1);
        if($VarRemainingUser == 0) {
            //die('User Limit Ended. Can\'t add more users');
        }

        if (empty($ArrProfileInfo)) {
            //redirect(base_url());
        } else {
            if ($ArrProfileInfo['usertype'] == 1) {
                //redirect(base_url('cadmin/dashboard'));
                $this->load->view('cadmin/dashboard');
            } else if ($ArrProfileInfo['usertype'] == 2) {
                //redirect(base_url('company/dashboard'));
                $this->load->view('company/dashboard', array('VarRemainingUser' => $VarRemainingUser));
            }
        }
    }
    public function changeReqStatus() {
        $VarActInActiveOption = xssclean($this->input->post('cs'));
        $ArrCheckBoxId = json_decode(xssclean($this->input->post('id')), true);
        $tblName = xssclean($this->input->post('tblname'));
        $idName = xssclean($this->input->post('idName'));
        if (!empty($VarActInActiveOption)) {
            if (!empty($ArrCheckBoxId)) {
                if ($this->commonmodel->changeReqStatus($ArrCheckBoxId, $VarActInActiveOption, $tblName, $idName)) {
                    echo json_encode(array('errCode' => 1));
                    die;
                } else                 echo json_encode(array('errCode' => -1));
            }
        }
    }
    public function changeReqStatusactive() {
        $status = xssclean($this->input->post('id'));
        $tblName = xssclean($this->input->post('tblname'));
        $idName = xssclean($this->input->post('idName'));
        if (!empty($status)) {
            if (!empty($status)) {
                if ($this->commonmodel->changeReqStatus($tblName, $idName,$status)) {
                    echo json_encode(array('errCode' => 1));
                    die;
                } else                 echo json_encode(array('errCode' => -1));
            }
        }
    }
    public function planBillingDetails() {
        $this->load->model('cadmin/companymodel');
        $this->load->model('commonmodel');
        $ArrProfileInfo = fnGetUserLoggedInfo(1);
        if ($ArrProfileInfo['companyid'] <> 0 && $ArrProfileInfo['companyid'] <> '') {
            $VarCompanyId = $ArrProfileInfo['companyid'];
            $ArrCompanyInfo = $this->companymodel->fnGetCompanyInfo($VarCompanyId);
            $ArrData['ArrCompanyBasicInfo'] = $ArrCompanyInfo[0];
            $ArrCompanyPanInfo = $this->commonmodel->getPlanDetails($VarCompanyId);
            //echo '<pre>'; print_r($ArrCompanyPanInfo); die();
            $ArrData['ArrCompanyPlan'] = array();
            if ($ArrCompanyPanInfo) {
                $ArrData['ArrCompanyPlan'] = $ArrCompanyPanInfo;
            }
            $ArrData['VarCompanyId'] = $VarCompanyId;
        }
        $this->load->view('planBillingDetailsMain', $ArrData);
    }
}