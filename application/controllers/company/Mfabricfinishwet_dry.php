<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class mfabricfinishwet_dry extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model(CNFCOMPANY . "mfabricfinishwet_drymodel");
        $this->load->helper('xssclean');
        fnIfCheckUserLoggedIn();
        $this->limit = LIMITPERPAGE;
    }
    public function addedit() {
        $ArrData = array('ArrBasicInfo' => array(), 'VarId' => '');
        $VarId = $this->uri->segment(4);
        $ArrData['Edit'] = $this->uri->segment(5);
        if ($VarId <> '' && base64_decode(urldecode($VarId))) {
            $VarId = base64_decode(urldecode($VarId));
            $ArrResults = $this->mfabricfinishwet_drymodel->fnGetInfo('', '', $VarId);
            $ArrData['ArrBasicInfo'] = $ArrResults[0];
            $ArrData['VarId'] = $ArrResults[0]['id'];
        } else {
        }
        $this->load->view(CNFCOMPANY . 'addeditfabricfinishwet_dry', $ArrData);
    }
    public function updateInfo() {
        $ArrResult = array();
        $VarFf = xssclean($this->input->post('ff'));
        $VarType = xssclean($this->input->post('ty'));
        $ArrUserLoggedInfo = fnGetUserLoggedInfo('1');
        $VarCompanyId = $ArrUserLoggedInfo['companyid'];
        $VarStatus = xssclean($this->input->post('s'));
        $VarId = xssclean($this->input->post('id'));
        $VarUpdatedBy = fnGetUserLoggedInfo();
        if ($VarFf <> '') {
            $ArrUpdateData = array('id' => $VarId, 'fabricfinish' => $VarFf, 'processingtype' => $VarType, 'companyid' => $VarCompanyId,
                'status' => $VarStatus, 'updatedby' => $VarUpdatedBy, 'dateupdated' => date('Y-m-d H:i:s'));
            if ($VarId == '' || $VarId == 0) {
                $ArrUpdateData['datecreated'] = date('Y-m-d H:i:s');
            }
            $ArrResult = $this->mfabricfinishwet_drymodel->saveInfo($ArrUpdateData);
        } else {
            $ArrResult['errcode'] = -1;
            $ArrResult['msg'] = 'Invalid Input!';
        }
        echo json_encode($ArrResult);
    }
    public function manage() {
        $VarFrom = xssclean($this->input->post('rfrom'));
        $VarFf = trim(xssclean($this->input->post('ff')));
        $VarPType = xssclean($this->input->post('pt'));
        $VarStatus = xssclean($this->input->post('s'));
        $clickedColumnId = xssclean($this->input->post('columnId'));
        $newsortorder = xssclean($this->input->post('sortorder'));
        $VarAfilter = xssclean($this->input->post('afilter'));
        $ArrDbCols = array('fabricfinish', 'processingtype', 'ff.status', 'u.contactname', 'ff.dateupdated');
        $ArrStatus = unserialize(ARRSTATUS);
        if ($VarFrom == 1) {
            $VarURLSegment = 4;
            $this->load->library('pagination');
            $config['base_url'] = base_url() . CNFCOMPANY . 'mfabricfinishwet_dry/manage/';
            $config['total_rows'] = $this->mfabricfinishwet_drymodel->fnCount($VarFf, $VarPType, $VarStatus, $VarAfilter);
            $config['per_page'] = $this->limit;
            $config['uri_segment'] = $VarURLSegment;
            $offset = $this->uri->segment($VarURLSegment);
            $this->pagination->initialize($config);
            $sortby = "ff.dateupdated";
            $sortorder = "desc";
            if ($clickedColumnId <> '' && $newsortorder <> '') {
                if (array_key_exists($clickedColumnId, $ArrDbCols)) {
                    $sortby = $ArrDbCols[$clickedColumnId];
                }
                $sortorder = $newsortorder;
            }
            $ArrList = $this->mfabricfinishwet_drymodel->fnList($VarFf, $VarPType, $VarStatus, $this->limit, $offset, $sortby, $sortorder, $VarAfilter)->result();
            $data['pagination'] = $this->pagination->create_linkswithajax('FFDryWet');
            $i = 0;
            $ArrFnlList = array();
            foreach ($ArrList as $ObjRes) {
                $ArrFnlList[$i]['id'] = $ObjRes->id;
                $ArrFnlList[$i]['ff'] = $ObjRes->fabricfinish;
                $ArrFnlList[$i]['pt'] = $ObjRes->processingtype;
                $ArrFnlList[$i]['ub'] = $ObjRes->contactname;
                $ArrFnlList[$i]['s'] = $ArrStatus[$ObjRes->status];
                $ArrFnlList[$i]['du'] = date('d-m-Y H:i A', strtotime($ObjRes->dateupdated));
                $i = $i + 1;
            }
            echo json_encode(array('errcode' => 1, 'cn' => $config['total_rows'], 'ct' => $i, 're' => $ArrFnlList, 'pa' => base64_encode($data['pagination'])));
            unset($ArrFnlList);
            die;
        } else {
            $this->load->view(CNFCOMPANY . 'managefabricfinishwet_dry', array('ArrStatus' => $ArrStatus));
        }
    }
    function changeStatus() {
        $VarActDeactOption = xssclean($this->input->post('actDeact'));
        $VarCid = xssclean($this->input->post('cid'));
        if ($VarActDeactOption <> '' && $VarCid <> '') {
            $this->mfabricfinishwet_drymodel->fnChangeStat($VarCid, $VarActDeactOption);
        }
        echo json_encode(array('errcode' => 1));
        die;
    }
}