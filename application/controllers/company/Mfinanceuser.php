<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class mfinanceuser extends CI_Controller {
    public function __construct() {
        parent::__construct();
        fnIfCheckUserLoggedIn();
        $this->load->helper('xssclean');
        $this->load->model('commonmodel');
        $this->load->model('commonusermodel');
        $this->load->model(CNFCOMPANY . 'mqausersmodel');
        $ArrUserLoggedInfo = fnGetUserLoggedInfo('1');
        $this->companyid = $ArrUserLoggedInfo['companyid'];
        $this->userid = $ArrUserLoggedInfo['id'];
        $this->mysqldatetime = date('Y-m-d H:i:s');
        $this->limit = LIMITPERPAGE;
        $this->ArrDbCols = array('contactname', 'desgn', 'username', 'mobile', 'status', 'updatedby', 'dateupdated');
        $this->usertype = getUserTypeId("Finance Dept.");
    }
    public function index() {
        $this->load->view('finance/userdashboard');
    }
    public function addedit() {
        $VarRemainingUser = $this->commonmodel->remaininguseravailable($this->companyid,1);
        if($VarRemainingUser == 0) {
            die('User Limit Ended. Can\'t add more users');
        }
        $ArrData												= array('ArrBasicInfo'=>array(),'VarId'=>'');
        $VarId = base64_decode(urldecode($this->uri->segment(4)));
        $VarDesgn = $this->commonmodel->getUserDesignation($this->usertype, '', 1);
        $ArrData['ArrDesgn'] = $VarDesgn;
        $ArrData['Edit'] = $this->uri->segment(5);
        $ArrData['ArrStatus'] = unserialize(ARRSTATUS);
        if(is_numeric($VarId)) {
            $ArrArrResults     									= $this->commonusermodel->fnGetInfo('','',$VarId);
            $ArrResults = $ArrArrResults[0];
            $VarDesignationId = $ArrResults['desgnid'];
            $ArrObjDesgn =$this->commonmodel->getUserDesignation('',$VarDesignationId);
            $ArrData['VarDesignation']	    				    = $ArrObjDesgn[0]['desgn'];
            $ArrData['ArrBasicInfo']	    				    = $ArrResults;
            $ArrData['VarId']					                = $ArrResults['id'];
        } else {
        }
        $this->load->view('finance/addeditfinanceuser', $ArrData);
    }
    public function updateUser() {
        $ArrUpdateData                  = array();
        $ArrUpdateData['id']            = xssclean($this->input->post('id'));
        $ArrUpdateData['username']      = xssclean($this->input->post('e'));
        $ArrUpdateData['contactname']   = xssclean($this->input->post('n'));
        $ArrUpdateData['mobile']        = xssclean($this->input->post('m'));
        $ArrUpdateData['companyid']     = $this->companyid;
        $ArrUpdateData['dateupdated']   = $this->mysqldatetime;
        $ArrUpdateData['updatedby']     = $this->userid;
        $ArrUpdateData['status']        = xssclean($this->input->post('s'));
        $ArrUpdateData['desgnid'] = xssclean($this->input->post('did'));
        $ArrUpdateData['password']      = COMMONPWD;
        $ArrUpdateData['usertype']      = $this->usertype;
        $ArrUpdateData['profilepermission'] = $this->usertype;
        if($ArrUpdateData['username']<>'') {
            if($ArrUpdateData['id']=='' || $ArrUpdateData['id']==0) {
                $ArrUpdateData['datecreated']                   = $this->mysqldatetime;
            }
            $ArrResult                                          = $this->commonusermodel->saveUser($ArrUpdateData);
        } else {
            $ArrResult['errcode']							    = -1;
            $ArrResult['msg']								    = 'Invalid Input!';
        }
        echo json_encode($ArrResult);
    }
    public function manage() {
        $VarFrom         = xssclean($this->input->post('rfrom'));
        $VarName = xssclean($this->input->post('n'));
        $VarMobile = xssclean($this->input->post('m'));
        $VarDesgnId = xssclean($this->input->post('d'));
        $VarStatus = xssclean($this->input->post('s'));
        $clickedColumnId = xssclean($this->input->post('columnId'));
        $VarSortOrder    = xssclean($this->input->post('sortorder'));
        if ($VarFrom == 1) {
            $VarURLSegment = 4;
            $this->load->library('pagination');
            $config['base_url']    = base_url().CNFCOMPANY.'mfinanceuser/manage/';
            $config['total_rows']  = $this->commonusermodel->fnCount($VarName, $VarMobile,$VarDesgnId,$VarStatus,$this->usertype,$this->companyid);
            $config['per_page']    = $this->limit;
            $config['uri_segment'] = $VarURLSegment;
            $VarOffset                = $this->uri->segment($VarURLSegment);
            $this->pagination->initialize($config);
            $ArrDbCols = $this->ArrDbCols;
            if(empty($VarSortOrder)) $VarSortOrder = 'desc';
            if (array_key_exists($clickedColumnId, $ArrDbCols)) $VarSortBy = $ArrDbCols[$clickedColumnId]; else $VarSortBy = '';
            $ArrList         = $this->commonusermodel->fnList($VarName, $VarMobile,$VarDesgnId,$VarStatus, $this->usertype,$this->companyid,
                $this->limit, $VarOffset, $VarSortBy, $VarSortOrder);
            $data['pagination'] = $this->pagination->create_linkswithajax('FinanceUser');
            $i                  = 0;
            $ArrFnlList         = array();
            $ArrStatus          = unserialize(ARRSTATUS);
            foreach ($ArrList['listData'] as $Obj) {
                $ArrFnlList[$i]['id'] = $Obj->id;
                $ArrFnlList[$i]['n']  = $Obj->contactname;
                $ArrFnlList[$i]['e']  = $Obj->username;
                $ArrFnlList[$i]['ds']  = $Obj->desgn;
                $ArrFnlList[$i]['m']  = $Obj->mobile;
                $ArrFnlList[$i]['ub'] = $ArrList['updatedByData'][$Obj->updatedby];
                $ArrFnlList[$i]['s']  = $ArrStatus[$Obj->status];
                $ArrFnlList[$i]['du'] = date('d-m-Y H:i:s', strtotime($Obj->dateupdated));
                $i                    = $i + 1;
            }
            echo json_encode(array('errcode' => '1', 'cn' => $config['total_rows'], 'ct' => $i, 're' => $ArrFnlList, 'pa' => base64_encode($data['pagination'])));
            unset($ArrFnlList);
            die;
        } else {
            $ArrDesignation = $this->commonmodel->getUserDesignation($this->usertype, '', 1);
            $this->load->view('finance/managefinanceusers',array('ArrDesignation' => $ArrDesignation,
                'ArrStatus' => unserialize(ARRSTATUS)));
        }
    }

    public function changeStatus() {
        $VarStatus = xssclean($this->input->post('actDeact'));
        $VarCid = xssclean($this->input->post('cid'));
        if ($VarStatus <> '' && $VarCid <> '') {
            $ArrIds = json_decode($VarCid, true);
            $ArrResult = $this->commonusermodel->changeStatus($ArrIds, $VarStatus);
            echo json_encode($ArrResult);
        }
    }

    public function requestreceivedlist()
    {
         $data['brands'] = $this->getBrandList();
        $this->load->view('request/finance/request_received_list',$data);
    }

     public function getBrandList() 
    {
        header('Access-Control-Allow-Origin: *');
        $output = $this->mqausersmodel->getBrandListt();
        return $output;
    }


     public function BOM1requestreceivedlist()
    {
         $data['brands'] = $this->getBrandList();
        $this->load->view('request/finance/BOM1_request_received_list',$data);
    }
     public function BOM2requestreceivedlist()
    {
         $data['brands'] = $this->getBrandList();
        $this->load->view('request/finance/BOM2_request_received_list',$data);
    }

    public function billpaidlist()
    {
         $data['brands'] = $this->getBrandList();
        $this->load->view('request/finance/bill_paid_list',$data);
    }

    public function billpaidlistbom1()
    {
         $data['brands'] = $this->getBrandList();
        $this->load->view('request/finance/bill_paid_list_bom1',$data);
    }
    public function billpaidlistbom2()
    {
         $data['brands'] = $this->getBrandList();
        $this->load->view('request/finance/bill_paid_list_bom2',$data);
    }
    
    public function stockTransferMemoList() {
        $this->load->view('request/purchase/stockTransferMemoList', array());
    }


}