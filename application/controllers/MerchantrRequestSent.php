<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class MerchantrRequestSent extends CI_Controller {

    public function __construct() {
        parent::__construct();
        //error_reporting(E_ALL);
        $this->load->helper('xssclean');
        fnIfCheckUserLoggedIn();
        $this->load->model(CNFCOMPANY . 'menquirymodel');
        $this->load->model(CNFCOMPANY . 'workinprogressmodel');
        $ArrUserLoggedInfo = fnGetUserLoggedInfo('1');
        $this->companyid = $ArrUserLoggedInfo['companyid'];
        $this->userid = $ArrUserLoggedInfo['id'];
        $this->mysqldatetime = date('Y-m-d H:i:s');
        $this->load->model('WorkInProcessModel');
        $this->load->model('RequestCadModel');
        $this->load->model('commonmodel');
        $this->load->model('managementmodel');
        $this->load->model(CNFCOMPANY . "mcadrequestmodel");
        $this->load->model(CNFCOMPANY . 'orderentrymodel');
    }

    
    // ********** MANAGE ALL STARTS HERE *********** /

    public function index() {
        $this->load->view('merchantrequestsent/index');
    }

    public function getManageAllList() 
    {
        header('Access-Control-Allow-Origin: *');
        $output = $this->merchantmodel->getManageAllListt();
        echo json_encode($output);
    }

    // ********** MANAGE ALL ENDS HERE *********** /


}?>