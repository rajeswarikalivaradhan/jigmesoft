<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class mtypemedium extends CI_Controller {
    public function __construct() {
        parent::__construct();
        fnIfCheckUserLoggedIn();
        $this->limit = LIMITPERPAGE;
        $this->load->model(CNFCOMPANY . "mtypemediummodel");
        $this->load->helper('xssclean');
    }
    public function addedit() {
        $ArrData = array('BasicInfo' => '', 'VarId' => '');
        $VarHashId = $this->uri->segment(4);
        $ArrData['Edit'] = $this->uri->segment(5);
        if ($VarHashId <> '' && base64_decode(urldecode($VarHashId))) {
            $VarId = base64_decode(urldecode($VarHashId));
            $ArrResults = $this->mtypemediummodel->fnGetInfo('', '', $VarId);
            $ArrData['BasicInfo'] = $ArrResults[0];
            $ArrData['VarId'] = $ArrResults[0]->id;
        } else {
        }
        $this->load->view(CNFCOMPANY . 'addedittypemedium', $ArrData);
    }
    public function updateInfo() {
        $VarUserInfo = fnGetUserLoggedInfo(1);
        if (isset($VarUserInfo['companyid'])) {
            $VarCompanyId = $VarUserInfo['companyid'];
        }
        $ArrResult = array();
        $VarYarn = xssclean($this->input->post('y'));
        $VarStatus = xssclean($this->input->post('s'));
        $VarId = xssclean($this->input->post('id'));
        $VarUpdatedBy = fnGetUserLoggedInfo();
        if ($VarYarn <> '') {
            $ArrData = array('id' => $VarId, 'type_medium' => $VarYarn, 'companyid' => $VarCompanyId, 'status' => $VarStatus,
                'updatedby' => $VarUpdatedBy, 'dateupdated' => date('Y-m-d H:i:s'));
            if (empty($VarId)) {
                $ArrData['datecreated'] = date('Y-m-d H:i:s');
            }
            $ArrResult = $this->mtypemediummodel->saveInfo($ArrData);
        } else {
            $ArrResult['errcode'] = -1;
            $ArrResult['msg'] = 'Invalid Input!';
        }
        echo json_encode($ArrResult);
    }
    public function manage() {
        $VarFrom = xssclean($this->input->post('rfrom'));
        $VarYarn = xssclean($this->input->post('y'));
        $VarStatus = xssclean($this->input->post('s'));
        $clickedColumnId = xssclean($this->input->post('columnId'));
        $newSortOrder = xssclean($this->input->post('sortorder'));
        $ArrDbCols = array('type_medium', 'y.status', 'y.dateupdated');
        $ArrStatus = unserialize(ARRSTATUS);
        if ($VarFrom == 1) {
            $VarURLSegment = 4;
            $this->load->library('pagination');
            $config['base_url'] = base_url() . CNFCOMPANY . 'mtypemedium/manage/';
            $config['total_rows'] = $this->mtypemediummodel->fnCount($VarYarn, $VarStatus);
            $config['per_page'] = $this->limit;
            $config['uri_segment'] = $VarURLSegment;
            $offSet = $this->uri->segment($VarURLSegment);
            $this->pagination->initialize($config);
            $sortBy = "y.dateupdated";
            $sortOrder = "desc";
            if ($clickedColumnId <> '' && $newSortOrder <> '') {
                if (array_key_exists($clickedColumnId, $ArrDbCols)) {
                    $sortBy = $ArrDbCols[$clickedColumnId];
                }
                $sortOrder = $newSortOrder;
            }
            $ArrList = $this->mtypemediummodel->fnList($VarYarn, $VarStatus, $this->limit, $offSet, $sortBy, $sortOrder)->result();
            $data['pagination'] = $this->pagination->create_linkswithajax('TypeMedium');
            $i = 0;
            $ArrFnlList = array();
            foreach ($ArrList as $Obj) {
                $ArrFnlList[$i]['id'] = $Obj->id;
                $ArrFnlList[$i]['y'] = $Obj->type_medium;
                $ArrFnlList[$i]['du'] = $Obj->formattedDateUpdated;
                $ArrFnlList[$i]['s'] = $ArrStatus[$Obj->status];
                $i = $i + 1;
            }
            echo json_encode(array('errcode' => 1, 'cn' => $config['total_rows'], 'ct' => $i, 're' => $ArrFnlList, 'pa' => base64_encode($data['pagination'])));
            unset($ArrFnlList);
            die;
        } else {
            $this->load->view(CNFCOMPANY . 'managetypemedium', array('ArrStatus' => $ArrStatus));
        }
    }
    function changemStatus() {
        $VarType = xssclean($this->input->post('actDeact'));
        $VarCid = xssclean($this->input->post('cid'));
        if ($VarType <> '' && $VarCid <> '') {
            $this->mtypemediummodel->fnChangeComStat($VarCid, $VarType);
        }
        echo json_encode(array('errcode' => 1));
        die;
    }
}