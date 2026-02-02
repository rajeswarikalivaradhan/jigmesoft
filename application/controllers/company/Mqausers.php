<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class mqausers extends CI_Controller {
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
        $this->usertype = 12;
    }

    public function index() {
        $this->load->view('mqausers/userdashboard');
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
        $this->load->view('mqausers/addeditqauser', $ArrData);
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
            $config['base_url']    = base_url().CNFCOMPANY.'mqausers/manage/';
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
            $data['pagination'] = $this->pagination->create_linkswithajax('QaUser');
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
            $this->load->view('mqausers/manageqausers',array('ArrDesignation' => $ArrDesignation,
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

    public function qareceivedlist() {
        $data['brands'] = $this->getBrandList();
        $this->load->view('qa/qareceivedlist',$data);
    }

    public function getRequestList() {
        header('Access-Control-Allow-Origin: *');
        $output = $this->mqausersmodel->getRequestListt();
        echo json_encode($output);
    }

    public function cadqareceivedlist() {
         $data['brands'] = $this->getBrandList();
        $this->load->view('qa/cadqareceivedlist',$data);
    }

    public function getCADRequestList() {
        header('Access-Control-Allow-Origin: *');
        $output = $this->mqausersmodel->getCADRequestListt();
        echo json_encode($output);
    }

    public function sampleqareceivedlist() {
        $data['brands'] = $this->getBrandList();
        $this->load->view('qa/sampleqareceivedlist',$data);
    }

    public function getSampleRequestList() {
        header('Access-Control-Allow-Origin: *');
        $output = $this->mqausersmodel->getSampleRequestListt();
        echo json_encode($output);
    }

    public function productionqareceivedlist() {
        $this->load->view('qa/productionqareceivedlist');
    }

    public function getProductionRequestList() {
        header('Access-Control-Allow-Origin: *');
        $output = $this->mqausersmodel->getProductionRequestListt();
        echo json_encode($output);
    }

    public function qaqueuelist() {
          $data['brands'] = $this->getBrandList();
          $this->load->view('qa/qaqueuelist',$data);
    }

    public function getQueueList() {
        header('Access-Control-Allow-Origin: *');
        $output = $this->mqausersmodel->getQueueListt();
        echo json_encode($output);
    }

    public function getQAQueueList() {
        header('Access-Control-Allow-Origin: *');
        $output = $this->mqausersmodel->getQAQueueListt();
        echo json_encode($output);
    }

    public function getmanagmenetgetQueueList() {
        header('Access-Control-Allow-Origin: *');
        $output = $this->mqausersmodel->getmanagmenetgetQueueListt();
        echo json_encode($output);
    }

    public function cadqaqueuelist() {
        $data['brands'] = $this->getBrandList();
        $this->load->view('qa/cadqaqueuelist',$data);
    }

    public function getCADQAQueueList() {
        header('Access-Control-Allow-Origin: *');
        $output = $this->mqausersmodel->getCADQAQueueListt();
        echo json_encode($output);
    }

    public function sampleqaqueuelist() {
         $data['brands'] = $this->getBrandList();
        $this->load->view('qa/sampleqaqueuelist',$data);
    }

    public function getSampleQAQueueList() {
        header('Access-Control-Allow-Origin: *');
        $output = $this->mqausersmodel->getSampleQAQueueListt();
        echo json_encode($output);
    }

    public function productionqaqueuelist() {
        $this->load->view('qa/productionqaqueuelist');
    }

    public function getProductionQAQueueList() {
        header('Access-Control-Allow-Origin: *');
        $output = $this->mqausersmodel->getProductionQAQueueListt();
        echo json_encode($output);
    }

    public function getManagementQueueList() {
        header('Access-Control-Allow-Origin: *');
        $output = $this->mqausersmodel->getManagementQueueListt();
        echo json_encode($output);
    }

    public function getSampleQueueList() {
        header('Access-Control-Allow-Origin: *');
        $output = $this->mqausersmodel->getSampleQueueListt();
        echo json_encode($output);
    }

    public function merchantsamplequeue() {
        $data['brands'] = $this->getBrandList();
        $this->load->view('qa/merchantsamplequeue', $data);

        //$this->load->view('qa/merchantsamplequeue');
    }

    public function managementsamplequeue() {
        $data['brands'] = $this->getBrandList();
        $this->load->view('qa/managementsamplequeue', $data);
    }

    public function getBomQueueList() {
        header('Access-Control-Allow-Origin: *');
        $output = $this->mqausersmodel->getBomQueueListt();
        echo json_encode($output);
    }

    public function getBom2QueueList() {
        header('Access-Control-Allow-Origin: *');
        $output = $this->mqausersmodel->getBom2QueueListt();
        echo json_encode($output);
    }

    public function merchantbomqueue() {
        $data['brands'] = $this->getBrandList();

        $this->load->view('qa/merchantbomqueue',$data);
    }

     public function merchantbom2queue() {
          $data['brands'] = $this->getBrandList();
        $this->load->view('qa/merchantbom2queue',$data);
    }

    public function managementbomqueue() {
         $data['brands'] = $this->getBrandList();
        $this->load->view('qa/managementbomqueue',$data);
    }

    public function managementbom2queue() {
         $data['brands'] = $this->getBrandList();
        $this->load->view('qa/managementbom2queue',$data);
    }

    public function merchantfabricqueue() {
        $this->load->view('qa/merchantfabricqueue');
    }

    public function managementfabricqueue() {
        $this->load->view('qa/managementfabricqueue');
    }

    public function getFabricQueueList() {
        header('Access-Control-Allow-Origin: *');
        $output = $this->mqausersmodel->getFabricQueueListt();
        echo json_encode($output);
    }
    
    public function merchantallqueue() {
         $data['brands'] = $this->getBrandList();
        $this->load->view('qa/merchantallqueue',$data);
    }

    /**
     * 
     * @return void
     */
    public function managmentallqueue() {
          $data['brands'] = $this->getBrandList();
        $this->load->view('qa/managmentallqueue',$data);
    }

    public function getAllQueueList() {
        header('Access-Control-Allow-Origin: *');
        $output = $this->mqausersmodel->getAllQueueListt();
        echo json_encode($output);
    }

    public function merchantcadqueue() {
        //$this->load->view('qa/merchantcadqueue');
        $data['brands'] = $this->getBrandList();
        $this->load->view('qa/merchantcadqueue', $data);
    }

    public function managementcadqueue() {
         $data['brands'] = $this->getBrandList();
        $this->load->view('qa/managementcadqueue', $data);
    }

    public function getCadQueueList() {
        header('Access-Control-Allow-Origin: *');
        $output = $this->mqausersmodel->getCadQueueListt();
        echo json_encode($output);
    }


     public function searchAllQueueList() {
        header('Access-Control-Allow-Origin: *');
        $data = $this->input->post();
        $output = $this->mqausersmodel->searchAllQueueListt($data);
        echo json_encode($output);
    }

    public function searchcadqaList() {
        header('Access-Control-Allow-Origin: *');
        $data = $this->input->post();
        $output = $this->mqausersmodel->searchcadqaList($data);
        echo json_encode($output);
    }

   public function searchsampleqaList() {
        header('Access-Control-Allow-Origin: *');
        $data = $this->input->post();
        //print_r($data);
        $output = $this->mqausersmodel->searchsampleqaList($data);
        echo json_encode($output);
    }
public function searchbom1qaList() {
        header('Access-Control-Allow-Origin: *');
        $data = $this->input->post();
        //print_r($data);
        $output = $this->mqausersmodel->searchbom1qaList($data);
        echo json_encode($output);
    }


    // public function merchantbom2queue() {
    //     $this->load->view('qa/merchantbom2queue');
    // }

    

    public function merchantembellishmentqueue() {
        $this->load->view('qa/merchantembellishmentqueue');
    }

    public function getEstablishmentQueueList() {
        header('Access-Control-Allow-Origin: *');
        $output = $this->mqausersmodel->getEstablishmentQueueListt();
        echo json_encode($output);
    }

    public function merchantproductionqueue() {
        $this->load->view('qa/merchantproductionqueue');
    }

    public function getProductionQueueList() {
        header('Access-Control-Allow-Origin: *');
        $output = $this->mqausersmodel->getProductionQueueListt();
        echo json_encode($output);
    }

    public function merchantvesselqueue() {
        $this->load->view('qa/merchantvesselqueue');
    }

    public function getVesselQueueList() {
        header('Access-Control-Allow-Origin: *');
        $output = $this->mqausersmodel->getVesselQueueListt();
        echo json_encode($output);
    }

    public function merchantstationeryqueue() {
        $this->load->view('qa/merchantstationeryqueue');
    }
   public function getBrandList() 
    {
        header('Access-Control-Allow-Origin: *');
        $output = $this->mqausersmodel->getBrandListt();
        return $output;
    }
    public function getStationeryQueueList() {
        header('Access-Control-Allow-Origin: *');
        $output = $this->mqausersmodel->getStationeryQueueListt();
        echo json_encode($output);
    }

}