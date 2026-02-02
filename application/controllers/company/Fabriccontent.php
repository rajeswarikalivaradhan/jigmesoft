<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class fabriccontent extends CI_Controller {
    public function __construct() {
        parent::__construct();
        fnIfCheckUserLoggedIn();
        $this->load->model(CNFCOMPANY . "fabriccontentmodel");
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
            $ArrResults = $this->fabriccontentmodel->fnGetInfo('', '', $VarId);
            $ArrData['BasicInfo'] = $ArrResults[0];
            $ArrData['VarId'] = $ArrResults[0]->id;
        } else {
        }
        $this->load->view(CNFCOMPANY . 'addEditContent', $ArrData);
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
            $ArrResult = $this->fabriccontentmodel->saveInfo($ArrData);
        } else {
            $ArrResult['errcode'] = -1;
            $ArrResult['msg'] = 'Invalid Input!';
        }
        echo json_encode($ArrResult);
    }
    public function manage() {
        $VarFrom = xssclean($this->input->post('rfrom'));
        $VarContent = trim(xssclean($this->input->post('c')));
        $VarStatus = xssclean($this->input->post('s'));
        $clickedColumnId = xssclean($this->input->post('columnId'));
        $newSortOrder = xssclean($this->input->post('sortorder'));
        $VarAfilter = xssclean($this->input->post('afilter'));
        $ArrDbCols = array('misc_name', 'status', 'dateupdated');
        $ArrStatus = unserialize(ARRSTATUS);
        if ($VarFrom == 1) {
            $VarURLSegment = 4;
            $this->load->library('pagination');
            $config['base_url'] = base_url() . CNFCOMPANY . 'fabriccontent/manage/';
            $config['total_rows'] = $this->fabriccontentmodel->fnCount($VarContent, $VarStatus, $VarAfilter);
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
            $ArrList = $this->fabriccontentmodel->fnList($VarContent, $VarStatus, $this->limit, $offSet, $sortBy, $sortOrder, $VarAfilter)->result();
            $data['pagination'] = $this->pagination->create_linkswithajax('Content');
            $i = 0;
            $ArrFnlList = array();
            foreach ($ArrList as $ObjUnit) {
                $ArrFnlList[$i]['id'] = $ObjUnit->id;
                $ArrFnlList[$i]['c'] = $ObjUnit->misc_name;
                $ArrFnlList[$i]['du'] = $ObjUnit->formattedDateUpdated;
                $ArrFnlList[$i]['s'] = $ArrStatus[$ObjUnit->status];
                $ArrFnlList[$i]['ub'] = !empty($ObjUnit->contactname)?$ObjUnit->contactname:'';
                $i = $i + 1;
            }
            echo json_encode(array('errcode' => 1, 'cn' => $config['total_rows'], 'ct' => $i, 're' => $ArrFnlList, 'pa' => base64_encode($data['pagination'])));
            unset($ArrFnlList);
            die;
        } else {
            $this->load->view(CNFCOMPANY . 'manageFabContent', array('ArrStatus' => $ArrStatus));
        }
    }
    function changeStatus() {
        $VarType = xssclean($this->input->post('actDeact'));
        $VarCid = xssclean($this->input->post('cid'));
        if ($VarType <> '' && $VarCid <> '') {
            $this->fabriccontentmodel->fnChangeComStat($VarCid, $VarType);
        }
        echo json_encode(array('errcode' => 1));
        die;
    }
}