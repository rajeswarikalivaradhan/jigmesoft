<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Mdyeingvendor extends CI_Controller {
    public function __construct() {
        parent::__construct();
        fnIfCheckUserLoggedIn();
        $this->load->model(CNFCOMPANY . "mdyeingvendormodel");
        $this->load->helper('xssclean');
        $this->limit = LIMITPERPAGE;
    }
    public function addedit() {
        $ArrData = array('ArrBasicInfo' => array(), 'VarId' => '');
        $VarHashedId = $this->uri->segment(4);
        $ArrData['Edit'] = $this->uri->segment(5);
        if ($VarHashedId <> '' && base64_decode(urldecode($VarHashedId))) {
            $VarId = base64_decode(urldecode($VarHashedId));
            $ArrResults = $this->mdyeingvendormodel->fnGetInfo('', '', $VarId);
            $ArrData['ArrBasicInfo'] = $ArrResults[0];
            $ArrData['VarId'] = $ArrResults[0]['id'];
        } else {
        }
        $this->load->view(CNFCOMPANY . 'addeditdyeingvendor', $ArrData);
    }
    public function updateInfo() {
        $VarUserInfo = fnGetUserLoggedInfo(1);
        $VarCompanyId = $VarUserInfo['companyid'];
        $ArrResult = array();
        $VarContactName = xssclean($this->input->post('vcn'));
        $VarVendorAddr = xssclean($this->input->post('vaddr'));
        $VarVendorname = xssclean($this->input->post('vn'));
        $VarEmailid = xssclean($this->input->post('e'));
        $VarPhone = xssclean($this->input->post('p'));
        $VarMobile = xssclean($this->input->post('m'));
        $VarGstno = xssclean($this->input->post('gst'));
        $VarIecode = xssclean($this->input->post('ie'));
        $VarBankname = xssclean($this->input->post('bn'));
        $VarAccountname = xssclean($this->input->post('an'));
        $VarAccountno = xssclean($this->input->post('ano'));
        $VarIfscode = xssclean($this->input->post('ifs'));
        $VarRtgs = xssclean($this->input->post('rtg'));
        $VarSwiftcode = xssclean($this->input->post('sc'));
        $VarIban = xssclean($this->input->post('iba'));
        $VarStatus = xssclean($this->input->post('s'));
        $VarId = xssclean($this->input->post('id'));
        if ($VarVendorname <> '') {
            $ArrUpdateData = array('id' => $VarId, 'address' => $VarVendorAddr, 'emailid' => $VarEmailid, 'mobile' => $VarMobile, 'phone' => $VarPhone, 'companyid' => $VarCompanyId,
                'vendorname' => $VarVendorname, 'contactpersonname' => $VarContactName, 'gstno' => $VarGstno, 'iecode' => $VarIecode, 'bankname' => $VarBankname, 'accountname' => $VarAccountname,
                'accountno' => $VarAccountno, 'ifscode' => $VarIfscode, 'rtgs' => $VarRtgs, 'swiftcode' => $VarSwiftcode, 'iban' => $VarIban, 'status' => $VarStatus,
                'updatedby' => $VarUserInfo['id'], 'dateupdated' => date('Y-m-d H:i:s'));
            if (empty($VarId)) {
                $ArrUpdateData['datecreated'] = date('Y-m-d H:i:s');
            }
            $ArrResult = $this->mdyeingvendormodel->saveInfo($ArrUpdateData);
        } else {
            $ArrResult['errcode'] = -1;
            $ArrResult['msg'] = 'Invalid Input';
        }
        echo json_encode($ArrResult);
    }
    public function managedyeingvendor() {
        $VarFrom = xssclean($this->input->post('rfrom'));
        $VarVendor = xssclean($this->input->post('vn'));
        $VarContatname = xssclean($this->input->post('cn'));
        $VarEmailId = xssclean($this->input->post('e'));
        $VarStatus = xssclean($this->input->post('s'));
        $clickedColumnId = xssclean($this->input->post('columnId'));
        $newsortorder = xssclean($this->input->post('sortorder'));
        $VarAfilter = xssclean($this->input->post('afilter'));
        $ArrDbCols = array('vendorname', 'contactpersonname', 'emailid', 'phone', 'mobile', 'contactname', 'status', 'dateupdated');
        if ($VarFrom == 1) {
            $VarURLSegment = 4;
            $this->load->library('pagination');
            $config['base_url'] = base_url() . CNFCOMPANY . 'mdyeingvendor/managedyeingvendor/';
            $config['total_rows'] = $this->mdyeingvendormodel->fnCount($VarVendor, $VarContatname, $VarEmailId, $VarStatus, $VarAfilter);
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
            $ArrFabricTypeList = $this->mdyeingvendormodel->fnList($VarVendor, $VarContatname, $VarEmailId, $VarStatus, $VarAfilter, $this->limit, $offset, $sortby, $sortorder)->result();
            $data['pagination'] = $this->pagination->create_linkswithajax('embellishmentvendor');
            $i = 0;
            $ArrFnlList = array();
            $ArrStatus = unserialize(ARRSTATUS);
            foreach ($ArrFabricTypeList as $ObjFabric) {
                $ArrFnlList[$i]['id'] = $ObjFabric->id;
                $ArrFnlList[$i]['addr'] = $ObjFabric->address;
                $ArrFnlList[$i]['vn'] = $ObjFabric->vendorname;
                $ArrFnlList[$i]['vcn'] = $ObjFabric->contactpersonname;
                $ArrFnlList[$i]['e'] = $ObjFabric->emailid;
                $ArrFnlList[$i]['p'] = $ObjFabric->phone;
                $ArrFnlList[$i]['m'] = $ObjFabric->mobile;
                $ArrFnlList[$i]['ub'] = $ObjFabric->contactname;
                $ArrFnlList[$i]['s'] = $ArrStatus[$ObjFabric->status];
                $ArrFnlList[$i]['du'] = date('d-m-Y H:i:s', strtotime($ObjFabric->dateupdated));
                $i = $i + 1;
            }
            echo json_encode(array('errcode' => 1, 'cn' => $config['total_rows'], 'ct' => $i, 're' => $ArrFnlList, 'pa' => base64_encode($data['pagination'])));
            unset($ArrFnlList);
            die;
        } else {
            $this->load->view(CNFCOMPANY . 'managedyeingvendor');
        }
    }
    function changemStatus() {
        $VarActDeactOption = xssclean($this->input->post('actdeactFabType'));
        $VarCid = xssclean($this->input->post('cid'));
        if ($VarActDeactOption <> '' && $VarCid <> '') {
            if ($this->mdyeingvendormodel->fnChangeComStat($VarCid, $VarActDeactOption))
                echo json_encode(array('errcode' => 1));
        }
    }
    function delInfo() {
        $VarYarnId = xssclean($this->input->post('id'));
        $VarUpdatedBy = fnGetUserLoggedInfo();
        if ($VarYarnId >= 1) {
            $ArrResult = $this->mbommodel->fnBDel($VarYarnId, $VarUpdatedBy);
        } else {
            $ArrResult['errcode'] = -1;
            $ArrResult['msg'] = '';
        }
        echo json_encode($ArrResult);
    }
}