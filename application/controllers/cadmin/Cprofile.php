<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class cprofile extends CI_Controller {

    private $limit = 10;
    public function __construct() {
        parent::__construct();
        $this->load->helper('cookie');
        $this->load->helper('xssclean');
        $this->load->library('email');
        $this->load->helper('email');
        $this->load->helper('common');
        fnIfCheckUserLoggedIn();
    }

    public function managecompany() {
        $this->load->view(CNFCADMIN.'managecompany');
    }

    public function addeditcompany() {
        $this->load->view(CNFCADMIN.'addeditcompany');
    }

}
?>