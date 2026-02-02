<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class LeftJoinController extends CI_Controller {
	
	function __construct() {
        parent::__construct();
        $this->load->model('leftjoinmodel');
    }
	
	public function index()	{
	    $Var = str_rand(8);
	    print_r($Var);
	    echo '<br>';
	    die('die');
		$data['blogs'] = $this->leftjoinmodel->left_outer_join();
        $this->load->view('left_join_view', $data);
	}
}