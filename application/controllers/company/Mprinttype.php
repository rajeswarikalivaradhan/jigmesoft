<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class mprinttype extends CI_Controller {

    private $limit = 10;
    public function __construct() {
        parent::__construct();
        error_reporting(E_ALL);
        $this->load->model(CNFCOMPANY."/mprintmodel");
        $this->load->helper('cookie');
        $this->load->helper('xssclean');
        $this->load->library('email');
        $this->load->helper('email');
        $this->load->helper('common');
        fnIfCheckUserLoggedIn();
    }

    public function addedit() {
        $ArrData												= array('ArrBasicInfo'=>array(),'VarId'=>'','VarNew'=>0);
        $VarId		    									    = $this->uri->segment(4);
        if($VarId<>'' && base64_decode(urldecode($VarId))) {
            $VarUserId                                          = base64_decode(urldecode($VarId));
            $ArrResults     									= $this->mprintmodel->fnGetPrintTypeInfo('','',$VarUserId);
            $ArrData['ArrBasicInfo']	    				    = $ArrResults[0];
            $ArrData['VarId']					                = $ArrResults[0]['id'];
        } else {
            $ArrData['VarNew']  							    = 1;
        }
        $this->load->view(CNFCOMPANY.'/addeditprinttype',$ArrData);
    }

    public function updatePrintInfo() {
        $ArrResult                                              = array();
        $VarPrintName				    						= xssclean($this->input->post('pn'));
        $VarStatus												= xssclean($this->input->post('s'));
        $VarId       											= xssclean($this->input->post('id'));
        $VarUpdatedBy											= fnGetUserLoggedInfo();
        if($VarPrintName<>'') {
            $ArrUpdateData                                      = array('id'=>$VarId,'printname'=>$VarPrintName,'status'=>$VarStatus,'updatedby'=>$VarUpdatedBy,'dateupdated'=>date('Y-m-d H:i:s'));
            if($VarId=='' || $VarId==0) {
                $ArrUpdateData['datecreated']                   = date('Y-m-d H:i:s');
            }
            $ArrStatus                                          = $this->mprintmodel->savePrintTypeInfo($ArrUpdateData);
            echo json_encode($ArrStatus);unset($ArrStatus);unset($ArrUpdateData);die;
        } else {
            $ArrResult['errcode']							    = '-1';
            $ArrResult['msg']								    = 'Invalid Input!';
        }
        echo json_encode($ArrResult);
    }

    public function manageprinttype() {
        $VarFrom												= xssclean($this->input->post('rfrom'));
        $VarPrintName											= xssclean($this->input->post('pn'));
        $VarStatus  											= xssclean($this->input->post('s'));
        if($VarFrom==1) {
            $VarURLSegment										= 4;
            $this->load->library('pagination');
            $config['base_url']									= base_url().CNFCOMPANY.'mprinttype/manageprinttype/';
            $config['total_rows']								= $this->mprintmodel->fnCountPrintType($VarPrintName,$VarStatus);
            $config['per_page']									= 10;
            $config['uri_segment']								= $VarURLSegment;
            $offset												= $this->uri->segment($VarURLSegment);
            $this->pagination->initialize($config);
            $sortby												= "dateupdated";
            $sortorder											= "desc";
            $ArrPrintTypeList								    = $this->mprintmodel->fnListPrintType($VarPrintName,$VarStatus,$this->limit,$offset,$sortby,$sortorder)->result();
            $data['pagination']									= $this->pagination->create_linkswithajax('PrintType');
            $i=0;
            $ArrFnlList										    = array();
            $ArrStatus                                          = unserialize(ARRSTATUS);
            foreach ($ArrPrintTypeList as $ObjFabric){
                $ArrFnlList[$i]['id']						    = $ObjFabric->id;
                $ArrFnlList[$i]['n']						    = $ObjFabric->printname;
                $ArrFnlList[$i]['ub']						    = $ObjFabric->contactname;
                $ArrFnlList[$i]['s']						    = $ArrStatus[$ObjFabric->status];
                $ArrFnlList[$i]['du']						    = date('d-m-Y H:i:s',strtotime($ObjFabric->dateupdated));
                $i=$i+1;
            }
            echo json_encode(array('errcode'=>1,'cn'=>$config['total_rows'],'ct'=>$i,'re'=>$ArrFnlList,'pa'=>base64_encode($data['pagination'])));
            unset($ArrFnlList);
            die;
        } else {
            $this->load->view(CNFCOMPANY.'/manageprinttype');
        }
    }

    function delPrintTypeInfo() {
        $VarFabricId                                            = xssclean($this->input->post('id'));
        $VarUpdatedBy                                           = fnGetUserLoggedInfo();
        if($VarFabricId>=1) {
            $ArrResult             					            = $this->mprintmodel->fnDelPrintType($VarFabricId,$VarUpdatedBy);
        } else {
            $ArrResult['errcode']						        = -1;
            $ArrResult['msg']							        = '';
        }
        echo json_encode($ArrResult);
    }

}