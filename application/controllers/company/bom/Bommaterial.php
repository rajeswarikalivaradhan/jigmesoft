<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Bommaterial extends CI_Controller {
    public function __construct() {
        parent::__construct();
        fnIfCheckUserLoggedIn();
        $this->load->model(CNFCOMPANY . "Bommaterialmodel");
        $this->load->helper('xssclean');
        $ArrUserLoggedInfo = fnGetUserLoggedInfo('1');
        $this->companyid = $ArrUserLoggedInfo['companyid'];
        $this->userid = $ArrUserLoggedInfo['id'];
        $this->limit = LIMITPERPAGE;
    }
    public function addedit() {
        $ArrData = array('BasicInfo' => '', 'VarId' => '');
        $VarId = base64_decode(urldecode($this->uri->segment(5)));
        $ArrData['Edit'] = $this->uri->segment(6);
        if (is_numeric($VarId)) {
            $ArrResults = $this->Bommaterialmodel->fnGetInfo('', '', $VarId);
            $ArrData['BasicInfo'] = $ArrResults[0];
            $ArrData['VarId'] = $ArrResults[0]->id;
        } else {
        }
        $this->load->view(CNFCOMPANY . 'bom/material/addeditmaterial', $ArrData);
    }
    public function updateInfo() {
        $ArrResult = array();
        $VarBlend = xssclean($this->input->post('b'));
        $VarStatus = xssclean($this->input->post('s'));
        $VarId = xssclean($this->input->post('id'));
        if ($VarBlend <> '') {
            $ArrData = array('id' => $VarId, 'type' => 4, 'description' => $VarBlend, 'companyid' => $this->companyid, 'status' => $VarStatus,
                'updatedby' => $this->userid, 'dateupdated' => date('Y-m-d H:i:s'));
            if (empty($VarId)) {
                $ArrData['datecreated'] = date('Y-m-d H:i:s');
            }
            $ArrResult = $this->Bommaterialmodel->saveInfo($ArrData);
        } else {
            $ArrResult['errcode'] = -1;
            $ArrResult['msg'] = 'Invalid Input';
        }
        echo json_encode($ArrResult);
    }
    
    public function manage() {
        $VarFrom = xssclean($this->input->post('rfrom'));
        $VarBlend = xssclean($this->input->post('b'));
        $VarStatus = xssclean($this->input->post('s'));
        $clickedColumnId = xssclean($this->input->post('columnId'));
        $newsortorder = xssclean($this->input->post('sortorder'));
        $ArrDbCols = array('description', 'status', 'dateupdated');
        $ArrStatus = unserialize(ARRSTATUS);
        if ($VarFrom == 1) {
            $VarURLSegment = 5;
            $this->load->library('pagination');
            $config['base_url'] = base_url() . CNFCOMPANY . 'bom/Bommaterial/manage/';
            $config['total_rows'] = $this->Bommaterialmodel->fnCount($VarBlend, $VarStatus);
            $config['per_page'] = $this->limit;
            $config['uri_segment'] = $VarURLSegment;
            $offset = $this->uri->segment($VarURLSegment);
            $this->pagination->initialize($config);
            $sortby = "dateupdated";
            $sortorder = "desc";
            if ($clickedColumnId <> '' && $newsortorder <> '') {
                if (array_key_exists($clickedColumnId, $ArrDbCols)) {
                    $sortby = $ArrDbCols[$clickedColumnId];
                }
                $sortorder = $newsortorder;
            }
            $ArrList = $this->Bommaterialmodel->fnList($VarBlend, $VarStatus, $this->limit, $offset, $sortby, $sortorder)->result();
            $data['pagination'] = $this->pagination->create_linkswithajax('YarnBlend');
            $i = 0;
            $ArrFnlList = array();
            foreach ($ArrList as $Obj) {
                $ArrFnlList[$i]['id'] = $Obj->id;
                $ArrFnlList[$i]['b'] = $Obj->description;
                $ArrFnlList[$i]['du'] = $Obj->formattedDateUpdated;
                $ArrFnlList[$i]['s'] = $ArrStatus[$Obj->status];
                $ArrFnlList[$i]['ub'] =!empty($Obj->contactname)? $Obj->contactname:"";
                $i = $i + 1;
            }
            echo json_encode(array('errcode' => 1, 'cn' => $config['total_rows'], 'ct' => $i, 're' => $ArrFnlList, 'pa' => base64_encode($data['pagination'])));
            unset($ArrFnlList);
            die;
        } else {
            $this->load->view(CNFCOMPANY . 'bom/material/materiallist', array('ArrStatus' => $ArrStatus));
        }
    }

    function changemStatus() {
        $VarType = xssclean($this->input->post('type'));
        $VarCid = xssclean($this->input->post('cid'));
        if ($VarType <> '' && $VarCid <> '') {
            $this->Bommaterialmodel->fnChangeComStat($VarCid, $VarType);
        }
        echo json_encode(array('errcode' => 1));
        die;
    }
}