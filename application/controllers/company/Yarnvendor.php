<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Yarnvendor extends CI_Controller {
    public function __construct() {
        parent::__construct();
        fnIfCheckUserLoggedIn();
        $this->load->model(CNFCOMPANY . "Yarnvendormodel");
        $this->load->helper('xssclean');
        $this->limit = LIMITPERPAGE;
    }
   
    public function addedit() {
        $ArrData = array('ArrBasicInfo' => array(), 'VarId' => '');
        $VarHashedId = $this->uri->segment(4);
        $ArrData['Edit'] = $this->uri->segment(5);
        if ($VarHashedId <> '' && base64_decode(urldecode($VarHashedId))) {
            $VarId = base64_decode(urldecode($VarHashedId));
            $ArrResults = $this->Yarnvendormodel->fnGetInfo('', '', $VarId);
            $ArrData['ArrBasicInfo'] = $ArrResults[0];
            $ArrData['VarId'] = $ArrResults[0]['id'];
        } else {
        }
        $this->load->view(CNFCOMPANY . 'addedit_yarnvendor', $ArrData);
    }
    public function updateInfo()
    {
        $VarUserInfo = fnGetUserLoggedInfo(1);
        if (isset($VarUserInfo['companyid'])) {
            $VarCompanyId = $VarUserInfo['companyid'];
        }
        $ArrResult = array();
        $VarVendor_name = xssclean($this->input->post('vendor_name'));
        $VarAddr = xssclean($this->input->post('addr'));
        $VarEmailId = xssclean($this->input->post('em'));
        $VarMobno = xssclean($this->input->post('mbno'));
        $VarPhno = xssclean($this->input->post('phno'));
        $VarContacperson= xssclean($this->input->post('cp'));
        $VarVendorCategory= xssclean($this->input->post('fvc'));
        $Varprimarypdtline= xssclean($this->input->post('pmpdtln'));
        $Varsecondarypdtline=xssclean($this->input->post('scpdtln'));
        $VarGSTno = xssclean($this->input->post('gst'));
        $VarIECode = xssclean($this->input->post('iec'));
        $VarBnkname = xssclean($this->input->post('bnk'));
        $VarAcntname = xssclean($this->input->post('actn'));
        $VarAcntno = xssclean($this->input->post('actno'));
        $VarIFSC = xssclean($this->input->post('ifsc'));
        $VarSwift = xssclean($this->input->post('swift'));
        $VarStatus = xssclean($this->input->post('s'));
        $VarId = xssclean($this->input->post('id'));
        $VarUpdatedBy = fnGetUserLoggedInfo();
        
        if ($VarVendor_name <> '') {
            $ArrData = array('id' => $VarId, 
                             'vendor_name' => $VarVendor_name, 
                             'address'=>$VarAddr,
                             'emailid'=>$VarEmailId,
                             'phone'=>$VarPhno,
                             'mobile'=>$VarMobno,
                             'contactperson'=>$VarContacperson,
                             'vendor_categoryid '=>$VarVendorCategory,
                             'primary_pdtline'=>$Varprimarypdtline,
                             'secondary_pdtline'=>$Varsecondarypdtline,
                             'gstno'=>$VarGSTno,
                             'iecode'=>$VarIECode,
                             'bankname'=>$VarBnkname,
                             'accountname'=>$VarAcntname,
                             'accountno'=>$VarAcntno,
                             'ifsccode'=>$VarIFSC,
                             'swiftcode'=>$VarSwift,
                             'companyid' => $VarCompanyId,
                             'status' => $VarStatus, 
                             'updatedby' => $VarUpdatedBy, 
                             'dateupdated' => date('Y-m-d H:i:s'));
            if (empty($VarId)) {
                $ArrData['datecreated'] = date('Y-m-d H:i:s');
            }
            $ArrResult = $this->Yarnvendormodel->saveInfo($ArrData);
        } else {
            $ArrResult['errcode'] = -1;
            $ArrResult['msg'] = 'Invalid Input!';
        }
        echo json_encode($ArrResult);
    }
    public function manage() {
        $VarFrom = xssclean($this->input->post('rfrom'));
        $VarVendor_name =trim(xssclean($this->input->post('vendor_name')));
        $VarContactPerson =trim(xssclean($this->input->post('cp')));
        $VarEmailId = trim(xssclean($this->input->post('em')));
        $VarMobno =xssclean(preg_replace('/^\s+/', '+ ',  trim($this->input->post('mno'))));
        $VarVendorCategory =xssclean($this->input->post('fvc'));
        $Varprimarypdtline =trim(xssclean($this->input->post('pmrypdtln')));
        $VarStatus = xssclean($this->input->post('s'));
        $clickedColumnId = xssclean($this->input->post('columnId'));
        $newsortorder = xssclean($this->input->post('sortorder'));
        $VarAfilter = xssclean($this->input->post('afilter'));
        $ArrDbCols = array('vendor_name', 'contactperson', 'emailid', 'phone', 'mobile', 'status', 'dateupdated');
        if ($VarFrom == 1) {
            $VarURLSegment = 4;
            $this->load->library('pagination');
            $config['base_url'] = base_url() . CNFCOMPANY . 'yarnvendor/manage';
            $config['total_rows'] = $this->Yarnvendormodel->fnCount($VarVendor_name, $VarContactPerson, $VarEmailId, $VarMobno,$VarVendorCategory,$Varprimarypdtline,$VarStatus, $VarAfilter);
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
            $ArrList = $this->Yarnvendormodel->fnList($VarVendor_name, $VarContactPerson, $VarEmailId,$VarMobno,$VarVendorCategory,$Varprimarypdtline, $VarStatus, $VarAfilter, $this->limit, $offset, $sortby, $sortorder)->result();
            $data['pagination'] = $this->pagination->create_linkswithajax('Yarnvendordet');
            $i = 0;
            $ArrFnlList = array();
            $ArrStatus = unserialize(ARRSTATUS);
            $ArrAgentCategory = unserialize(ARRVENDORCATEGORY);
            foreach ($ArrList as $Obj) {
                $ArrFnlList[$i]['id'] = $Obj->id;
                $ArrFnlList[$i]['vendor_name'] = $Obj->vendor_name;
                $ArrFnlList[$i]['cp'] = $Obj->contactperson;
                $ArrFnlList[$i]['em'] = $Obj->emailid;
                $ArrFnlList[$i]['phno'] = $Obj->phone;
                $ArrFnlList[$i]['mno'] = $Obj->mobile;
                $ArrFnlList[$i]['fvc'] = !empty($Obj->vendor_categoryid)?$ArrAgentCategory[$Obj->vendor_categoryid]:'';;
                $ArrFnlList[$i]['prmypdtln'] = $Obj->primary_pdtline;
                $ArrFnlList[$i]['s'] = $ArrStatus[$Obj->status];
                $ArrFnlList[$i]['ub'] = $Obj->contactname;
                $ArrFnlList[$i]['du'] = date('d-m-Y H:i A', strtotime($Obj->dateupdated));
                $i = $i + 1;
            }
            echo json_encode(array('errcode' => 1, 'cn' => $config['total_rows'], 'ct' => $i, 're' => $ArrFnlList, 'pa' => base64_encode($data['pagination'])));
            unset($ArrFnlList);
            die;
        } else {
            $this->load->view(CNFCOMPANY . 'manage_yarnvendor');
        }
    }
    function changeStatus() {
        $VarRfrom = xssclean($this->input->post('rfrom'));
        if ($VarRfrom == 1) {
            $VarStatus = xssclean($this->input->post('status'));
            $VarCid = xssclean($this->input->post('cid'));
            if ($VarStatus <> '' && $VarCid <> '') {
                $Arrids = json_decode($VarCid, true);
                $this->db->where_in('id', $Arrids);
                if ($this->db->update(KN_MASTER_YARNVENDOR, array('status' => $VarStatus))) {
                    echo json_encode(array('errcode' => 1));
                }
                return false;
            }
            die;
        }
    }
}