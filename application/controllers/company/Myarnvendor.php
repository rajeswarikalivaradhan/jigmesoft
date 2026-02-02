<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class myarnvendor extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model(CNFCOMPANY . "myarnvendormodel");
        $this->load->helper('xssclean');
        fnIfCheckUserLoggedIn();
        $ArrUserLoggedInfo = fnGetUserLoggedInfo('1');
        $this->companyid = $ArrUserLoggedInfo['companyid'];
        $this->userid = $ArrUserLoggedInfo['id'];
        $this->limit = LIMITPERPAGE;
    }
    public function addedit() {
        $ArrData = array('ArrBasicInfo' => array(), 'VarId' => '');
        $VarId = base64_decode(urldecode($this->uri->segment(4)));
        $ArrData['Edit'] = $this->uri->segment(5);
        if(is_numeric($VarId)) {
            $ArrResults = $this->myarnvendormodel->fnGetInfo('', '', $VarId);
            $ArrData['ArrBasicInfo'] = $ArrResults[0];
            $ArrData['VarId'] = $ArrResults[0]['id'];
        } else {
        }
        $this->load->view(CNFCOMPANY . 'addedityarnvendor', $ArrData);
    }
    public function updateInfo() {
        $ArrResult = array();
        $VarYarnvendor = xssclean($this->input->post('yv'));
        $VarStatus = xssclean($this->input->post('s'));
        $VarId = xssclean($this->input->post('id'));
        if ($VarYarnvendor <> '') {
            $ArrUpdateData = array('id' => $VarId, 'companyid' =>$this->companyid, 'yarnvendor' => $VarYarnvendor,
                'status' => $VarStatus, 'updatedby' =>  $this->userid, 'dateupdated' => date('Y-m-d H:i:s'));
            if ($VarId == '' || $VarId == 0) {
                $ArrUpdateData['datecreated'] = date('Y-m-d H:i:s');
            }
            $ArrResult = $this->myarnvendormodel->saveInfo($ArrUpdateData);
        } else {
            $ArrResult['errcode'] = -1;
            $ArrResult['msg'] = 'Invalid Input';
        }
        echo json_encode($ArrResult);
    }
    public function manage() {
        $VarFrom = xssclean($this->input->post('rfrom'));
        $VarName = xssclean($this->input->post('yn'));
        $VarStatus = xssclean($this->input->post('s'));
        $clickedColumnId = xssclean($this->input->post('columnId'));
        $newsortorder = xssclean($this->input->post('sortorder'));
        $ArrDbCols = array('yarnvendor', 'y.dateupdated', 'y.status', 'u.contactname');
        if ($VarFrom == 1) {
            $VarURLSegment = 4;
            $this->load->library('pagination');
            $config['base_url'] = base_url() . CNFCOMPANY . 'myarnvendor/manage/';
            $config['total_rows'] = $this->myarnvendormodel->fnCount($VarName, $VarStatus);
            $config['per_page'] = $this->limit;
            $config['uri_segment'] = $VarURLSegment;
            $offset = $this->uri->segment($VarURLSegment);
            $this->pagination->initialize($config);
            $sortby = "y.dateupdated";
            $sortorder = "desc";
            if ($clickedColumnId <> '' && $newsortorder <> '') {
                if (array_key_exists($clickedColumnId, $ArrDbCols)) {
                    $sortby = $ArrDbCols[$clickedColumnId];
                }
                $sortorder = $newsortorder;
            }
            $ArrList = $this->myarnvendormodel->fnList($VarName, $VarStatus, $this->limit, $offset, $sortby, $sortorder)->result();
            $data['pagination'] = $this->pagination->create_linkswithajax('YarnVendor');
            $i = 0;
            $ArrFnlList = array();
            $ArrStatus = unserialize(ARRSTATUS);
            foreach ($ArrList as $Obj) {
                $ArrFnlList[$i]['id'] = $Obj->id;
                $ArrFnlList[$i]['yv'] = $Obj->yarnvendor;
                $ArrFnlList[$i]['ub'] = $Obj->contactname;
                $ArrFnlList[$i]['s'] = $ArrStatus[$Obj->status];
                $ArrFnlList[$i]['du'] = date('d-m-Y H:i:s', strtotime($Obj->dateupdated));
                $i = $i + 1;
            }
            echo json_encode(array('errcode' => 1, 'cn' => $config['total_rows'], 'ct' => $i, 're' => $ArrFnlList, 'pa' => base64_encode($data['pagination'])));
            unset($ArrFnlList);
            die;
        } else {
            $this->load->view(CNFCOMPANY . 'manageyarnvendor');
        }
    }
    function changeStatus() {
        $VarActDeactOption = xssclean($this->input->post('actDeact'));
        $VarCid = xssclean($this->input->post('cid'));
        if ($VarActDeactOption <> '' && $VarCid <> '') {
            $this->myarnvendormodel->fnChangeComStat($VarCid, $VarActDeactOption);
        }
        echo json_encode(array('errcode' => 1));
        die;
    }
}