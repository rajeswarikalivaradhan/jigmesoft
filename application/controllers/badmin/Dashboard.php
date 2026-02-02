<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Dashboard extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->helper('xssclean');
        //echo '<pre>'; print_r($_SESSION); die('die');
         //echo '<pre>'; print_r($_SESSION); die('die');

    }
    public function index() {
        $ArrProfileInfo = fnGetUserLoggedInfo(1);
        if (empty($ArrProfileInfo)) {
            redirect(base_url());
        } else {
            if ($ArrProfileInfo["usertype"] == 0) {
                $this->load->view(CNFBADMIN.'dashboard');
            } elseif ($ArrProfileInfo['usertype'] == 16) {
                $this->load->view(CNFBADMIN.'dashboard');
            }
        }
    }
}