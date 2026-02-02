<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class mbomcontent extends CI_Controller {
    public function __construct() {
        parent::__construct();
        fnIfCheckUserLoggedIn();
        $this->load->model(CNFCOMPANY . "mbomcontentmodel");
        $this->load->helper('xssclean');
        $ArrUserLoggedInfo = fnGetUserLoggedInfo('1');
        $this->companyid = $ArrUserLoggedInfo['companyid'];
        $this->userid = $ArrUserLoggedInfo['id'];
        $this->limit = LIMITPERPAGE;
    }
    public function addedit() {
        $ArrData = array('BasicInfo' => '', 'VarId' => '');
        $VarId = base64_decode(urldecode($this->uri->segment(4)));
        $ArrData['Edit'] = $this->uri->segment(5);
        if (is_numeric($VarId)) {
            $ArrResults = $this->mbomcontentmodel->fnGetInfo('', '', $VarId);
            $ArrData['BasicInfo'] = $ArrResults[0];
            $ArrData['VarId'] = $ArrResults[0]->id;
        } else {
        }
        $this->load->view(CNFCOMPANY . 'addeditbomcontent', $ArrData);
    }
    public function updateInfo() {
        $VarUserInfo = fnGetUserLoggedInfo(1);
        if (isset($VarUserInfo['companyid'])) {
            $VarCompanyId = $VarUserInfo['companyid'];
        }
        $ArrResult = array();
        $VarContent = xssclean($this->input->post('c'));
        $VarStatus = xssclean($this->input->post('s'));
        $VarId = xssclean($this->input->post('id'));
        $VarUpdatedBy = fnGetUserLoggedInfo();
        if ($VarContent <> '') {
            $ArrData = array('id' => $VarId, 'misc_name' => $VarContent, 'misc_type' => 2, 'companyid' => $VarCompanyId, 'status' => $VarStatus,
                'updatedby' => $VarUpdatedBy, 'dateupdated' => date('Y-m-d H:i:s'));
            if (empty($VarId)) {
                $ArrData['datecreated'] = date('Y-m-d H:i:s');
            }
            $ArrResult = $this->mbomcontentmodel->saveInfo($ArrData);
        } else {
            $ArrResult['errcode'] = -1;
            $ArrResult['msg'] = 'Invalid Input!';
        }
        echo json_encode($ArrResult);
    }
    public function manage() {
        $VarFrom = xssclean($this->input->post('rfrom'));
        $VarContent = xssclean($this->input->post('c'));
        $VarStatus = xssclean($this->input->post('s'));
        $clickedColumnId = xssclean($this->input->post('columnId'));
        $newSortOrder = xssclean($this->input->post('sortorder'));
        $ArrDbCols = array('misc_name', 'y.status', 'y.dateupdated');
        $ArrStatus = unserialize(ARRSTATUS);
        if ($VarFrom == 1) {
            $VarURLSegment = 4;
            $this->load->library('pagination');
            $config['base_url'] = base_url() . CNFCOMPANY . 'mbomcontent/manage/';
            $config['total_rows'] = $this->mbomcontentmodel->fnCount($VarContent, $VarStatus);
            $config['per_page'] = $this->limit;
            $config['uri_segment'] = $VarURLSegment;
            $offSet = $this->uri->segment($VarURLSegment);
            $this->pagination->initialize($config);
            $sortBy = "dateupdated";
            $sortOrder = "desc";
            if ($clickedColumnId <> '' && $newSortOrder <> '') {
                if (array_key_exists($clickedColumnId, $ArrDbCols)) {
                    $sortBy = $ArrDbCols[$clickedColumnId];
                }
                $sortOrder = $newSortOrder;
            }
            $ArrList = $this->mbomcontentmodel->fnList($VarContent, $VarStatus, $this->limit, $offSet, $sortBy, $sortOrder)->result();
            $data['pagination'] = $this->pagination->create_linkswithajax('YarnContent');
            $i = 0;
            $ArrFnlList = array();
            foreach ($ArrList as $Obj) {
                $ArrFnlList[$i]['id'] = $Obj->id;
                $ArrFnlList[$i]['c'] = $Obj->misc_name;
                $ArrFnlList[$i]['du'] = $Obj->formattedDateUpdated;
                $ArrFnlList[$i]['s'] = $ArrStatus[$Obj->status];
                $i = $i + 1;
            }
            echo json_encode(array('errcode' => 1, 'cn' => $config['total_rows'], 'ct' => $i, 're' => $ArrFnlList, 'pa' => base64_encode($data['pagination'])));
            unset($ArrFnlList);
            die;
        } else {
            $this->load->view(CNFCOMPANY . 'managebomcontent', array('ArrStatus' => $ArrStatus));
        }
    }
    function changeStatus() {
        $VarType = xssclean($this->input->post('actDeact'));
        $VarCid = xssclean($this->input->post('cid'));
        if ($VarType <> '' && $VarCid <> '') {
            $this->mbomcontentmodel->fnChangeComStat($VarCid, $VarType);
        }
        echo json_encode(array('errcode' => 1));
        die;
    }
}