<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class fabricname extends CI_Controller {
    public function __construct() {
        parent::__construct();
        fnIfCheckUserLoggedIn();
        $this->limit = LIMITPERPAGE;
        $this->load->model(CNFCOMPANY . "fabricnamemodel");
        $this->load->helper('xssclean');
    }
    public function addedit() {
        $ArrData = array('BasicInfo' => '', 'VarId' => '');
        $VarHashId = $this->uri->segment(4);
        $ArrData['Edit'] = $this->uri->segment(5);
        $VarUserInfo = fnGetUserLoggedInfo(1);
        if (isset($VarUserInfo['companyid'])) {
            $VarCompanyId = $VarUserInfo['companyid'];
        }
        if ($VarHashId <> '' && base64_decode(urldecode($VarHashId))) {
            $VarId = base64_decode(urldecode($VarHashId));
            $ArrResults = $this->fabricnamemodel->fnGetInfo('', '', $VarId);
            $ArrData['BasicInfo'] = $ArrResults[0];
            $ArrData['VarId'] = $ArrResults[0]->id;
        } else {
        }
        $this->load->view(CNFCOMPANY . 'addEditFabricname', $ArrData);
    }
    public function updateInfo() {
        $VarUserInfo = fnGetUserLoggedInfo(1);
        if (isset($VarUserInfo['companyid'])) {
            $VarCompanyId = $VarUserInfo['companyid'];
        }
        $ArrResult = array();
        $VarFabric = xssclean($this->input->post('f'));
        $VarStatus = xssclean($this->input->post('s'));
        $VarId = xssclean($this->input->post('id'));
        $VarUpdatedBy = fnGetUserLoggedInfo();
        if ($VarFabric <> '') {
            $ArrData = array('id' => $VarId, 'misc_name' => $VarFabric, 'misc_type' => 3, 'companyid' => $VarCompanyId, 'status' => $VarStatus,
                'updatedby' => $VarUpdatedBy, 'dateupdated' => date('Y-m-d H:i:s'));
            if (empty($VarId)) {
                $ArrData['datecreated'] = date('Y-m-d H:i:s');
            }
            $ArrResult = $this->fabricnamemodel->saveInfo($ArrData);
        } else {
            $ArrResult['errcode'] = -1;
            $ArrResult['msg'] = 'Invalid Input!';
        }
        echo json_encode($ArrResult);
    }
    public function manage() {
        $VarFrom = xssclean($this->input->post('rfrom'));
        $VarFabric = trim(xssclean($this->input->post('f')));
        $VarStatus = xssclean($this->input->post('s'));
        $clickedColumnId = xssclean($this->input->post('columnId'));
        $newSortOrder = xssclean($this->input->post('sortorder'));
        $ArrDbCols = array('misc_name', 'm.status', 'm.dateupdated');
        $ArrStatus = unserialize(ARRSTATUS);
        if ($VarFrom == 1) {
            $VarURLSegment = 4;
            $this->load->library('pagination');
            $config['base_url'] = base_url() . CNFCOMPANY . 'fabricname/manage/';
            $config['total_rows'] = $this->fabricnamemodel->fnCount($VarFabric, $VarStatus);
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
            $ArrList = $this->fabricnamemodel->fnList($VarFabric, $VarStatus, $this->limit, $offSet, $sortBy, $sortOrder)->result();
            $data['pagination'] = $this->pagination->create_linkswithajax('Fabricname');
            $i = 0;
            $ArrFnlList = array();
            foreach ($ArrList as $ObjUnit) {
                $ArrFnlList[$i]['id'] = $ObjUnit->id;
                $ArrFnlList[$i]['f'] = $ObjUnit->misc_name;
                $ArrFnlList[$i]['du'] = $ObjUnit->formattedDateUpdated;
                $ArrFnlList[$i]['s'] = $ArrStatus[$ObjUnit->status];
                $ArrFnlList[$i]['ub'] = !empty($ObjUnit->contactname)?$ObjUnit->contactname:'';
                $i = $i + 1;
            }
            echo json_encode(array('errcode' => 1, 'cn' => $config['total_rows'], 'ct' => $i, 're' => $ArrFnlList, 'pa' => base64_encode($data['pagination'])));
            unset($ArrFnlList);
            die;
        } else {
            $this->load->view(CNFCOMPANY . 'manageFabricname', array('ArrStatus' => $ArrStatus));
        }
    }
    function changemStatus() {
        $VarType = xssclean($this->input->post('actDeact'));
        $VarCid = xssclean($this->input->post('cid'));
        if ($VarType <> '' && $VarCid <> '') {
            $this->fabricnamemodel->fnChangeComStat($VarCid, $VarType);
        }
        echo json_encode(array('errcode' => 1));
        die;
    }
}