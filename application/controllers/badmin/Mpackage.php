<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Mpackage extends CI_Controller {
    public function __construct() {
        parent::__construct();
        fnIfCheckUserLoggedIn();
        $this->load->model(CNFBADMIN . "PackageDetmodel");
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
            $ArrResults = $this->PackageDetmodel->fnGetInfo('', '', $VarId);
            $ArrData['BasicInfo'] = $ArrResults[0];
            $ArrData['VarId'] = $ArrResults[0]->id;
        } else {
        }
        $this->load->view(CNFBADMIN . 'package/addedit_form', $ArrData);
    }
    public function updateInfo() {
        $ArrResult = array();
        $Varpackage_det = xssclean($this->input->post('pd'));
        $Varnoofusers = xssclean($this->input->post('nu'));
        $Vardata_limit = xssclean($this->input->post('dl'));
        $Varfile_limit = xssclean($this->input->post('fl'));
        $VarStatus = xssclean($this->input->post('s'));
        $VarId = xssclean($this->input->post('id'));
        if ($Varpackage_det <> '') {
            $ArrData = array('id' => $VarId, 'description' => $Varpackage_det,'no_of_users' => $Varnoofusers,'data_limit' => $Vardata_limit,'file_limit' => $Varfile_limit, 'companyid' => $this->companyid, 'status' => $VarStatus,
                'updatedby' => $this->userid, 'dateupdated' => date('Y-m-d H:i:s'));
            if (empty($VarId)) {
                $ArrData['datecreated'] = date('Y-m-d H:i:s');
            }
            $ArrResult = $this->PackageDetmodel->saveInfo($ArrData);
        } else {
            $ArrResult['errcode'] = -1;
            $ArrResult['msg'] = 'Invalid Input';
        }
        echo json_encode($ArrResult);
    }
    
    public function manage() {
        $VarFrom = xssclean($this->input->post('rfrom'));
        $Varpackage_det = trim(xssclean($this->input->post('pd')));
        $Varnoofusers = trim(xssclean($this->input->post('nu')));
        $Vardata_limit = trim(xssclean($this->input->post('dl')));
        $Varfile_limit = trim(xssclean($this->input->post('fl')));
        $VarStatus = xssclean($this->input->post('s'));
        $clickedColumnId = xssclean($this->input->post('columnId'));
        $newsortorder = xssclean($this->input->post('sortorder'));
        $ArrDbCols = array('description', 'status', 'dateupdated');
        $ArrStatus = unserialize(ARRSTATUS);
        if ($VarFrom == 1) {
            $VarURLSegment = 5;
            $this->load->library('pagination');
            $config['base_url'] = base_url() . CNFBADMIN . 'mpackage/manage/';
            $config['total_rows'] = $this->PackageDetmodel->fnCount($Varpackage_det,$Varnoofusers, $Vardata_limit,$Varfile_limit,$VarStatus);
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
            $ArrList = $this->PackageDetmodel->fnList($Varpackage_det,$Varnoofusers,$Vardata_limit,$Varfile_limit, $VarStatus, $this->limit, $offset, $sortby, $sortorder)->result();
            $data['pagination'] = $this->pagination->create_linkswithajax('PackageDet');
            $i = 0;
            $ArrFnlList = array();
            foreach ($ArrList as $Obj) {
                $ArrFnlList[$i]['id'] = $Obj->id;
                $ArrFnlList[$i]['pd'] = $Obj->description;
                $ArrFnlList[$i]['nu'] = $Obj->no_of_users;
                $ArrFnlList[$i]['dl'] = $Obj->data_limit;
                $ArrFnlList[$i]['fl'] = $Obj->file_limit;
                $ArrFnlList[$i]['du'] = $Obj->formattedDateUpdated;
                $ArrFnlList[$i]['s'] = $ArrStatus[$Obj->status];
                $ArrFnlList[$i]['ub'] = !empty($Obj->contactname)? $Obj->contactname:"";
                $i = $i + 1;
            }
            echo json_encode(array('errcode' => 1, 'cn' => $config['total_rows'], 'ct' => $i, 're' => $ArrFnlList, 'pa' => base64_encode($data['pagination'])));
            unset($ArrFnlList);
            die;
        } else {
            $this->load->view(CNFBADMIN . 'package/packagedet_list', array('ArrStatus' => $ArrStatus));
        }
    }

    function changemStatus() {
        $VarType = xssclean($this->input->post('type'));
        $VarCid = xssclean($this->input->post('cid'));
        if ($VarType <> '' && $VarCid <> '') {
            $this->PackageDetmodel->fnChangeComStat($VarCid, $VarType);
        }
        echo json_encode(array('errcode' => 1));
        die;
    }
}