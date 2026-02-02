<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Magent extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        
        $this->load->model(CNFCOMPANY . "ClearingAgentmodel");
        $this->load->model(CNFCOMPANY . "ForwardingAgentmodel");
        $this->load->model(CNFCOMPANY . "Importermodel");
        $this->load->model(CNFCOMPANY . "Consignormodel");
        $this->load->model(CNFCOMPANY . "Consigneemodel");
        $this->load->model(CNFCOMPANY.'Brandmodel');
        $this->load->helper('xssclean');
        fnIfCheckUserLoggedIn();
        $this->limit = LIMITPERPAGE;
    }

    public function addedit()
    {
        $ArrData = array('ArrBasicInfo' => array(), 'VarId' => '');
        $VarHashId = $this->uri->segment(4);
        $ArrData['Edit'] = $this->uri->segment(5);
        $brand_data = $this->db->select('id,brandname as name')->order_by('id', 'desc')->get_where(KN_MASTER_BRANDBUYER, array('status' =>1))->result_array();
        $ArrData['branddata'] = $brand_data;
        $VarUserInfo = fnGetUserLoggedInfo(1);
        if (isset($VarUserInfo['companyid'])) {
            $VarCompanyId = $VarUserInfo['companyid'];
        }

        if ($VarHashId <> '' && base64_decode(urldecode($VarHashId))) {
            $VarId = base64_decode(urldecode($VarHashId));
            $ArrResults = $this->ClearingAgentmodel->fnGetInfo('', '', $VarId);
            $buyerinfo=$this->db->select('buyername')->get_where(KN_MASTER_BRANDBUYER,array('id' =>$ArrResults[0]['brand_id']))->row();
            $ArrResults[0]['buyername'] = $buyerinfo->buyername; // assign to fetched results
            $ArrData['ArrBasicInfo'] = $ArrResults[0];
            $ArrData['VarId'] = $ArrResults[0]['id'];
        } else {
           
        }
        $this->load->view(CNFCOMPANY . 'addedit_clearingagent', $ArrData);
    }
    public function fwdaddedit()
    {
        $ArrData = array('ArrBasicInfo' => array(), 'VarId' => '');
        $VarHashId = $this->uri->segment(4);
        $ArrData['Edit'] = $this->uri->segment(5);
        $brand_data = $this->db->select('id,brandname as name')->order_by('id', 'desc')->get_where(KN_MASTER_BRANDBUYER, array('status' =>1))->result_array();
        $ArrData['branddata'] = $brand_data;
        $VarUserInfo = fnGetUserLoggedInfo(1);
        if (isset($VarUserInfo['companyid'])) {
            $VarCompanyId = $VarUserInfo['companyid'];
        }

        if ($VarHashId <> '' && base64_decode(urldecode($VarHashId))) {
            $VarId = base64_decode(urldecode($VarHashId));
            $ArrResults = $this->ForwardingAgentmodel->fnGetInfo('', '', $VarId);
            $buyerinfo=$this->db->select('buyername')->get_where(KN_MASTER_BRANDBUYER,array('id' =>$ArrResults[0]['brand_id']))->row();
            $ArrResults[0]['buyername'] = $buyerinfo->buyername; // assign to fetched results
            $ArrData['ArrBasicInfo'] = $ArrResults[0];
            $ArrData['VarId'] = $ArrResults[0]['id'];
        } else {
           
        }
        $this->load->view(CNFCOMPANY . 'addedit_fwdingagent', $ArrData);
    }
    public function importaddedit()
    {
        $ArrData = array('ArrBasicInfo' => array(), 'VarId' => '');
        $VarHashId = $this->uri->segment(4);
        $ArrData['Edit'] = $this->uri->segment(5);
        $brand_data = $this->db->select('id,brandname as name')->order_by('id', 'desc')->get_where(KN_MASTER_BRANDBUYER, array('status' =>1))->result_array();
        $ArrData['branddata'] = $brand_data;
        $VarUserInfo = fnGetUserLoggedInfo(1);
        if (isset($VarUserInfo['companyid'])) {
            $VarCompanyId = $VarUserInfo['companyid'];
        }

        if ($VarHashId <> '' && base64_decode(urldecode($VarHashId))) {
            $VarId = base64_decode(urldecode($VarHashId));
            $ArrResults = $this->Importermodel->fnGetInfo('', '', $VarId);
            $buyerinfo=$this->db->select('buyername')->get_where(KN_MASTER_BRANDBUYER,array('id' =>$ArrResults[0]['brand_id']))->row();
            $ArrResults[0]['buyername'] = $buyerinfo->buyername; // assign to fetched results
            $ArrData['ArrBasicInfo'] = $ArrResults[0];
            $ArrData['VarId'] = $ArrResults[0]['id'];
        } else {
           
        }
        $this->load->view(CNFCOMPANY . 'addedit_importer.php', $ArrData);
    }
    public function addeditconsignor()
    {
        $ArrData = array('ArrBasicInfo' => array(), 'VarId' => '');
        $VarHashId = $this->uri->segment(4);
        $ArrData['Edit'] = $this->uri->segment(5);
        $brand_data = $this->db->select('id,brandname as name')->order_by('id', 'desc')->get_where(KN_MASTER_BRANDBUYER, array('status' =>1))->result_array();
        $ArrData['branddata'] = $brand_data;
        $VarUserInfo = fnGetUserLoggedInfo(1);
        if (isset($VarUserInfo['companyid'])) {
            $VarCompanyId = $VarUserInfo['companyid'];
        }

        if ($VarHashId <> '' && base64_decode(urldecode($VarHashId))) {
            $VarId = base64_decode(urldecode($VarHashId));
            $ArrResults = $this->Consignormodel->fnGetInfo('', '', $VarId);
            $buyerinfo=$this->db->select('buyername')->get_where(KN_MASTER_BRANDBUYER,array('id' =>$ArrResults[0]['brand_id']))->row();
            $ArrResults[0]['buyername'] = $buyerinfo->buyername; // assign to fetched results
            $ArrData['ArrBasicInfo'] = $ArrResults[0];
            $ArrData['VarId'] = $ArrResults[0]['id'];
        } else {
           
        }
        $this->load->view(CNFCOMPANY . 'addedit_consignor.php', $ArrData);
    }
    public function addeditconsignee()
    {
        $ArrData = array('ArrBasicInfo' => array(), 'VarId' => '');
        $VarHashId = $this->uri->segment(4);
        $ArrData['Edit'] = $this->uri->segment(5);
        $brand_data = $this->db->select('id,brandname as name')->order_by('id', 'desc')->get_where(KN_MASTER_BRANDBUYER, array('status' =>1))->result_array();
        $ArrData['branddata'] = $brand_data;
        $VarUserInfo = fnGetUserLoggedInfo(1);
        if (isset($VarUserInfo['companyid'])) {
            $VarCompanyId = $VarUserInfo['companyid'];
        }

        if ($VarHashId <> '' && base64_decode(urldecode($VarHashId))) {
            $VarId = base64_decode(urldecode($VarHashId));
            $ArrResults = $this->Consigneemodel->fnGetInfo('', '', $VarId);
            $buyerinfo=$this->db->select('buyername')->get_where(KN_MASTER_BRANDBUYER,array('id' =>$ArrResults[0]['brand_id']))->row();
            $ArrResults[0]['buyername'] = $buyerinfo->buyername; // assign to fetched results
            $ArrData['ArrBasicInfo'] = $ArrResults[0];
            $ArrData['VarId'] = $ArrResults[0]['id'];
        } else {
           
        }
        $this->load->view(CNFCOMPANY . 'addedit_consignee.php', $ArrData);
    }
    public function updateClearingAgentInfo()
    {
        $VarUserInfo = fnGetUserLoggedInfo(1);
        if (isset($VarUserInfo['companyid'])) {
            $VarCompanyId = $VarUserInfo['companyid'];
        }
        $ArrResult = array();
        $VarAgent = xssclean($this->input->post('agent'));
        $VarAddr = xssclean($this->input->post('addr'));
        $VarCity = xssclean($this->input->post('cty'));
        $VarState= xssclean($this->input->post('st'));
        $VarCountry = xssclean($this->input->post('cy'));
        $VarPincode = xssclean($this->input->post('pin'));
        $VarEmailId = xssclean($this->input->post('em'));
        $VarMobno = xssclean($this->input->post('mbno'));
        $VarPhno = xssclean($this->input->post('phno'));
        $VarContacperson= xssclean($this->input->post('cp'));
        $VarAgentCategory= xssclean($this->input->post('ac'));
        $VarBrand_id= xssclean($this->input->post('brnid'));
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
        
        if ($VarAgent <> '') {
            $ArrData = array('id' => $VarId, 
                             'agentname' => $VarAgent, 
                             'address'=>$VarAddr,
                             'emailid'=>$VarEmailId,
                             'phone'=>$VarPhno,
                             'mobile'=>$VarMobno,
                             'city'=>$VarCity,
                             'state'=>$VarState,
                             'country'=>$VarCountry,
                             'pincode'=>$VarPincode,
                             'contactperson'=>$VarContacperson,
                             'agent_categoryid'=>$VarAgentCategory,
                             'brand_id'=>$VarBrand_id,
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
                             'type' => 2, 
                             'dateupdated' => date('Y-m-d H:i:s'));
            if (empty($VarId)) {
                $ArrData['datecreated'] = date('Y-m-d H:i:s');
            }
            $ArrResult = $this->ClearingAgentmodel->saveInfo($ArrData);
        } else {
            $ArrResult['errcode'] = -1;
            $ArrResult['msg'] = 'Invalid Input!';
        }
        echo json_encode($ArrResult);
    }
    public function updateFwdingAgentInfo()
    {
        $VarUserInfo = fnGetUserLoggedInfo(1);
        if (isset($VarUserInfo['companyid'])) {
            $VarCompanyId = $VarUserInfo['companyid'];
        }
        $ArrResult = array();
        $VarAgent = xssclean($this->input->post('agent'));
        $VarAddr = xssclean($this->input->post('addr'));
        $VarCity = xssclean($this->input->post('cty'));
        $VarState= xssclean($this->input->post('st'));
        $VarCountry = xssclean($this->input->post('cy'));
        $VarPincode = xssclean($this->input->post('pin'));
        $VarEmailId = xssclean($this->input->post('em'));
        $VarMobno = xssclean($this->input->post('mbno'));
        $VarPhno = xssclean($this->input->post('phno'));
        $VarContacperson= xssclean($this->input->post('cp'));
        $VarAgentCategory= xssclean($this->input->post('ac'));
        $VarBrand_id= xssclean($this->input->post('brnid'));
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
        
        if ($VarAgent <> '') {
            $ArrData = array('id' => $VarId, 
                             'agentname' => $VarAgent, 
                             'address'=>$VarAddr,
                             'emailid'=>$VarEmailId,
                             'phone'=>$VarPhno,
                             'mobile'=>$VarMobno,
                             'city'=>$VarCity,
                             'state'=>$VarState,
                             'country'=>$VarCountry,
                             'pincode'=>$VarPincode,
                             'contactperson'=>$VarContacperson,
                             'agent_categoryid'=>$VarAgentCategory,
                             'brand_id'=>$VarBrand_id,
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
                             'type' => 1, 
                             'dateupdated' => date('Y-m-d H:i:s'));
            if (empty($VarId)) {
                $ArrData['datecreated'] = date('Y-m-d H:i:s');
            }
            $ArrResult = $this->ForwardingAgentmodel->saveInfo($ArrData);
        } else {
            $ArrResult['errcode'] = -1;
            $ArrResult['msg'] = 'Invalid Input!';
        }
        echo json_encode($ArrResult);
    }
    public function updateImporterInfo()
    {
        $VarUserInfo = fnGetUserLoggedInfo(1);
        if (isset($VarUserInfo['companyid'])) {
            $VarCompanyId = $VarUserInfo['companyid'];
        }
        $ArrResult = array();
        $VarAgent = xssclean($this->input->post('agent'));
        $VarAddr = xssclean($this->input->post('addr'));
        $VarCity = xssclean($this->input->post('cty'));
        $VarState= xssclean($this->input->post('st'));
        $VarCountry = xssclean($this->input->post('cy'));
        $VarPincode = xssclean($this->input->post('pin'));
        $VarEmailId = xssclean($this->input->post('em'));
        $VarMobno = xssclean($this->input->post('mbno'));
        $VarPhno = xssclean($this->input->post('phno'));
        $VarContacperson= xssclean($this->input->post('cp'));
        $VarAgentCategory= xssclean($this->input->post('ac'));
        $VarBrand_id= xssclean($this->input->post('brnid'));
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
        
        if ($VarAgent <> '') {
            $ArrData = array('id' => $VarId, 
                             'agentname' => $VarAgent, 
                             'address'=>$VarAddr,
                             'emailid'=>$VarEmailId,
                             'phone'=>$VarPhno,
                             'mobile'=>$VarMobno,
                             'city'=>$VarCity,
                             'state'=>$VarState,
                             'country'=>$VarCountry,
                             'pincode'=>$VarPincode,
                             'contactperson'=>$VarContacperson,
                             'agent_categoryid'=>$VarAgentCategory,
                             'brand_id'=>$VarBrand_id,
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
                             'type' => 3, 
                             'dateupdated' => date('Y-m-d H:i:s'));
            if (empty($VarId)) {
                $ArrData['datecreated'] = date('Y-m-d H:i:s');
            }
            $ArrResult = $this->Importermodel->saveInfo($ArrData);
        } else {
            $ArrResult['errcode'] = -1;
            $ArrResult['msg'] = 'Invalid Input!';
        }
        echo json_encode($ArrResult);
    }
    public function updateConsignorInfo()
    {
        $VarUserInfo = fnGetUserLoggedInfo(1);
        if (isset($VarUserInfo['companyid'])) {
            $VarCompanyId = $VarUserInfo['companyid'];
        }
        $ArrResult = array();
        $VarAgent = xssclean($this->input->post('agent'));
        $VarAddr = xssclean($this->input->post('addr'));
        $VarCity = xssclean($this->input->post('cty'));
        $VarState= xssclean($this->input->post('st'));
        $VarCountry = xssclean($this->input->post('cy'));
        $VarPincode = xssclean($this->input->post('pin'));
        $VarEmailId = xssclean($this->input->post('em'));
        $VarMobno = xssclean($this->input->post('mbno'));
        $VarPhno = xssclean($this->input->post('phno'));
        $VarContacperson= xssclean($this->input->post('cp'));
        $VarAgentCategory= xssclean($this->input->post('ac'));
        $VarBrand_id= xssclean($this->input->post('brnid'));
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
        
        if ($VarAgent <> '') {
            $ArrData = array('id' => $VarId, 
                             'agentname' => $VarAgent, 
                             'address'=>$VarAddr,
                             'emailid'=>$VarEmailId,
                             'phone'=>$VarPhno,
                             'mobile'=>$VarMobno,
                             'city'=>$VarCity,
                             'state'=>$VarState,
                             'country'=>$VarCountry,
                             'pincode'=>$VarPincode,
                             'contactperson'=>$VarContacperson,
                             'agent_categoryid'=>$VarAgentCategory,
                             'brand_id'=>$VarBrand_id,
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
                             'type' => 4, 
                             'dateupdated' => date('Y-m-d H:i:s'));
            if (empty($VarId)) {
                $ArrData['datecreated'] = date('Y-m-d H:i:s');
            }
            $ArrResult = $this->Consignormodel->saveInfo($ArrData);
        } else {
            $ArrResult['errcode'] = -1;
            $ArrResult['msg'] = 'Invalid Input!';
        }
        echo json_encode($ArrResult);
    }
    public function updateConsigneeInfo()
    {
        $VarUserInfo = fnGetUserLoggedInfo(1);
        if (isset($VarUserInfo['companyid'])) {
            $VarCompanyId = $VarUserInfo['companyid'];
        }
        $ArrResult = array();
        $VarAgent = xssclean($this->input->post('agent'));
        $VarAddr = xssclean($this->input->post('addr'));
        $VarCity = xssclean($this->input->post('cty'));
        $VarState= xssclean($this->input->post('st'));
        $VarCountry = xssclean($this->input->post('cy'));
        $VarPincode = xssclean($this->input->post('pin'));
        $VarEmailId = xssclean($this->input->post('em'));
        $VarMobno = xssclean($this->input->post('mbno'));
        $VarPhno = xssclean($this->input->post('phno'));
        $VarContacperson= xssclean($this->input->post('cp'));
        $VarAgentCategory= xssclean($this->input->post('ac'));
        $VarBrand_id= xssclean($this->input->post('brnid'));
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
        
        if ($VarAgent <> '') {
            $ArrData = array('id' => $VarId, 
                             'agentname' => $VarAgent, 
                             'address'=>$VarAddr,
                             'emailid'=>$VarEmailId,
                             'phone'=>$VarPhno,
                             'mobile'=>$VarMobno,
                             'city'=>$VarCity,
                             'state'=>$VarState,
                             'country'=>$VarCountry,
                             'pincode'=>$VarPincode,
                             'contactperson'=>$VarContacperson,
                             'agent_categoryid'=>$VarAgentCategory,
                             'brand_id'=>$VarBrand_id,
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
                             'type' => 5, 
                             'dateupdated' => date('Y-m-d H:i:s'));
            if (empty($VarId)) {
                $ArrData['datecreated'] = date('Y-m-d H:i:s');
            }
            $ArrResult = $this->Consigneemodel->saveInfo($ArrData);
        } else {
            $ArrResult['errcode'] = -1;
            $ArrResult['msg'] = 'Invalid Input!';
        }
        echo json_encode($ArrResult);
    }
     //////////////newly added////////////////
    public function manage()
    {
        $VarFrom = xssclean($this->input->post('rfrom'));
        $VarAgent =trim(xssclean($this->input->post('agent')));
        $VarEmail_id = trim(xssclean($this->input->post('em')));
        $VarMobno =xssclean(preg_replace('/^\s+/', '+ ',  trim($this->input->post('mno'))));
        $VarContactPerson =trim(xssclean($this->input->post('cp')));
        $VarAgentCategory =xssclean($this->input->post('ac'));
        $VarBrand_id =xssclean($this->input->post('brand_id'));
        $VarStatus = xssclean($this->input->post('s'));
        $clickedColumnId = xssclean($this->input->post('columnId'));
        $newsortorder = xssclean($this->input->post('sortorder'));
        $VarAfilter = xssclean($this->input->post('afilter'));
        $brand_data = $this->db->select('id,brandname as name')->order_by('id', 'desc')->get_where(KN_MASTER_BRANDBUYER, array('status' =>1))->result();
        $ArrDbCols = array('agentname', 'contactname', 'status', 'dateupdated');
        if ($VarFrom == 1) {

            $VarURLSegment = 4;

            $this->load->library('pagination');

            $config['base_url'] = base_url() . CNFCOMPANY . 'Magent/manage/';

            $config['total_rows'] = $this->ClearingAgentmodel->fnCount($VarAgent,$VarEmail_id,$VarMobno,$VarContactPerson,$VarAgentCategory,$VarBrand_id,$VarStatus,$VarAfilter);
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


            $ArrList = $this->ClearingAgentmodel->fnList($VarAgent,$VarEmail_id,$VarMobno,$VarContactPerson,$VarAgentCategory,$VarBrand_id,$VarStatus,$this->limit, $offset, $sortby, $sortorder, $VarAfilter)->result();

            $data['pagination'] = $this->pagination->create_linkswithajax('ClearingAgent');

            $i = 0;

            $ArrFnlList = array();

            $ArrStatus = unserialize(ARRSTATUS);
            $ArrAgentCategory = unserialize(ARRVENDORCATEGORY);
          
            foreach ($ArrList as $ObjUnit) {
                //   var_dump($ArrAgentCategory[$ObjUnit->agent_categoryid]);
                $brand = $this->db->select('brandname')->get_where(KN_MASTER_BRANDBUYER, array('id' => $ObjUnit->brand_id))->row();
                $ArrFnlList[$i]['id'] = $ObjUnit->id;
                $ArrFnlList[$i]['agent'] = $ObjUnit->agentname;
                $ArrFnlList[$i]['em'] =!empty($ObjUnit->emailid)?$ObjUnit->emailid:'';
                $ArrFnlList[$i]['mbno']=!empty($ObjUnit->mobile)?$ObjUnit->mobile:'';
                $ArrFnlList[$i]['cp'] =!empty($ObjUnit->contactperson)?$ObjUnit->contactperson:'';
                $ArrFnlList[$i]['ac'] =!empty($ObjUnit->agent_categoryid)?$ArrAgentCategory[$ObjUnit->agent_categoryid]:'';
                $ArrFnlList[$i]['brand'] =!empty($brand)?$brand->brandname :'';
                $ArrFnlList[$i]['s'] = $ArrStatus[$ObjUnit->status];
                $ArrFnlList[$i]['ub'] = !empty($ObjUnit->contactname)?$ObjUnit->contactname:"";
                $ArrFnlList[$i]['du'] = date('d-m-Y H:i A', strtotime($ObjUnit->dateupdated));
                
                
                $i = $i + 1;

            }

            echo json_encode(array('errcode' => 1, 'cn' => $config['total_rows'], 'ct' => $i, 're' => $ArrFnlList, 'pa' => base64_encode($data['pagination'])));

            unset($ArrFnlList);

            die;

        } else {
            $dropdowndata['brand_data']=$brand_data;
            $this->load->view(CNFCOMPANY . 'manage_clearing_agent',$dropdowndata);

        }

    }
    public function forwarding()
    {
        $VarFrom = xssclean($this->input->post('rfrom'));
        $VarAgent =trim(xssclean($this->input->post('agent')));
        $VarEmail_id = trim(xssclean($this->input->post('em')));
        $VarMobno =xssclean(preg_replace('/^\s+/', '+ ',  trim($this->input->post('mno'))));
        $VarContactPerson =trim(xssclean($this->input->post('cp')));
        $VarAgentCategory =xssclean($this->input->post('ac'));
        $VarBrand_id =xssclean($this->input->post('brand_id'));
        $VarStatus = xssclean($this->input->post('s'));
        $clickedColumnId = xssclean($this->input->post('columnId'));
        $newsortorder = xssclean($this->input->post('sortorder'));
        $VarAfilter = xssclean($this->input->post('afilter'));
        $brand_data = $this->db->select('id,brandname as name')->order_by('id', 'desc')->get_where(KN_MASTER_BRANDBUYER, array('status' =>1))->result();
        $ArrDbCols = array('agentname', 'contactname', 'status', 'dateupdated');
        if ($VarFrom == 1) {

            $VarURLSegment = 4;

            $this->load->library('pagination');

            $config['base_url'] = base_url() . CNFCOMPANY . 'Magent/forwarding/';

            $config['total_rows'] = $this->ForwardingAgentmodel->fnCount($VarAgent,$VarEmail_id,$VarMobno,$VarContactPerson,$VarAgentCategory,$VarBrand_id,$VarStatus,$VarAfilter);
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


            $ArrList = $this->ForwardingAgentmodel->fnList($VarAgent,$VarEmail_id,$VarMobno,$VarContactPerson,$VarAgentCategory,$VarBrand_id,$VarStatus,$this->limit, $offset, $sortby, $sortorder, $VarAfilter)->result();

            $data['pagination'] = $this->pagination->create_linkswithajax('ForwardingAgent');

            $i = 0;

            $ArrFnlList = array();

            $ArrStatus = unserialize(ARRSTATUS);
            $ArrAgentCategory = unserialize(ARRVENDORCATEGORY);
          
            foreach ($ArrList as $ObjUnit) {
                //   var_dump($ArrAgentCategory[$ObjUnit->agent_categoryid]);
                $brand = $this->db->select('brandname')->get_where(KN_MASTER_BRANDBUYER, array('id' => $ObjUnit->brand_id))->row();
                $ArrFnlList[$i]['id'] = $ObjUnit->id;
                $ArrFnlList[$i]['agent'] = $ObjUnit->agentname;
                $ArrFnlList[$i]['em'] =!empty($ObjUnit->emailid)?$ObjUnit->emailid:'';
                $ArrFnlList[$i]['mbno']=!empty($ObjUnit->mobile)?$ObjUnit->mobile:'';
                $ArrFnlList[$i]['cp'] =!empty($ObjUnit->contactperson)?$ObjUnit->contactperson:'';
                $ArrFnlList[$i]['ac'] =!empty($ObjUnit->agent_categoryid)?$ArrAgentCategory[$ObjUnit->agent_categoryid]:'';
                $ArrFnlList[$i]['brand'] =!empty($brand)?$brand->brandname :'';
                $ArrFnlList[$i]['s'] = $ArrStatus[$ObjUnit->status];
                $ArrFnlList[$i]['ub'] = !empty($ObjUnit->contactname)?$ObjUnit->contactname:"";
                $ArrFnlList[$i]['du'] = date('d-m-Y H:i A', strtotime($ObjUnit->dateupdated));
                
                
                $i = $i + 1;

            }

            echo json_encode(array('errcode' => 1, 'cn' => $config['total_rows'], 'ct' => $i, 're' => $ArrFnlList, 'pa' => base64_encode($data['pagination'])));

            unset($ArrFnlList);

            die;

        } else {
            $dropdowndata['brand_data']=$brand_data;
            $this->load->view(CNFCOMPANY . 'manage_forwarding_agent',$dropdowndata);

        }

    }
    public function importer()
    {
        $VarFrom = xssclean($this->input->post('rfrom'));
        $VarAgent =trim(xssclean($this->input->post('agent')));
        $VarEmail_id = trim(xssclean($this->input->post('em')));
        $VarMobno =xssclean(preg_replace('/^\s+/', '+ ',  trim($this->input->post('mno'))));
        $VarContactPerson =trim(xssclean($this->input->post('cp')));
        $VarAgentCategory =xssclean($this->input->post('ac'));
        $VarBrand_id =xssclean($this->input->post('brand_id'));
        $VarStatus = xssclean($this->input->post('s'));
        $clickedColumnId = xssclean($this->input->post('columnId'));
        $newsortorder = xssclean($this->input->post('sortorder'));
        $VarAfilter = xssclean($this->input->post('afilter'));
        $brand_data = $this->db->select('id,brandname as name')->order_by('id', 'desc')->get_where(KN_MASTER_BRANDBUYER, array('status' =>1))->result();
        $ArrDbCols = array('agentname', 'contactname', 'status', 'dateupdated');
        if ($VarFrom == 1) {

            $VarURLSegment = 4;

            $this->load->library('pagination');

            $config['base_url'] = base_url() . CNFCOMPANY . 'Magent/importer/';

            $config['total_rows'] = $this->Importermodel->fnCount($VarAgent,$VarEmail_id,$VarMobno,$VarContactPerson,$VarAgentCategory,$VarBrand_id,$VarStatus,$VarAfilter);
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


            $ArrList = $this->Importermodel->fnList($VarAgent,$VarEmail_id,$VarMobno,$VarContactPerson,$VarAgentCategory,$VarBrand_id,$VarStatus,$this->limit, $offset, $sortby, $sortorder, $VarAfilter)->result();

            $data['pagination'] = $this->pagination->create_linkswithajax('Importer');

            $i = 0;

            $ArrFnlList = array();

            $ArrStatus = unserialize(ARRSTATUS);
            $ArrAgentCategory = unserialize(ARRVENDORCATEGORY);
          
            foreach ($ArrList as $ObjUnit) {
                //   var_dump($ArrAgentCategory[$ObjUnit->agent_categoryid]);
                $brand = $this->db->select('brandname')->get_where(KN_MASTER_BRANDBUYER, array('id' => $ObjUnit->brand_id))->row();
                $ArrFnlList[$i]['id'] = $ObjUnit->id;
                $ArrFnlList[$i]['agent'] = $ObjUnit->agentname;
                $ArrFnlList[$i]['em'] =!empty($ObjUnit->emailid)?$ObjUnit->emailid:'';
                $ArrFnlList[$i]['mbno']=!empty($ObjUnit->mobile)?$ObjUnit->mobile:'';
                $ArrFnlList[$i]['cp'] =!empty($ObjUnit->contactperson)?$ObjUnit->contactperson:'';
                $ArrFnlList[$i]['ac'] =!empty($ObjUnit->agent_categoryid)?$ArrAgentCategory[$ObjUnit->agent_categoryid]:'';
                $ArrFnlList[$i]['brand'] =!empty($brand)?$brand->brandname :'';
                $ArrFnlList[$i]['s'] = $ArrStatus[$ObjUnit->status];
                $ArrFnlList[$i]['ub'] = !empty($ObjUnit->contactname)?$ObjUnit->contactname:"";
                $ArrFnlList[$i]['du'] = date('d-m-Y H:i A', strtotime($ObjUnit->dateupdated));
                
                
                $i = $i + 1;

            }

            echo json_encode(array('errcode' => 1, 'cn' => $config['total_rows'], 'ct' => $i, 're' => $ArrFnlList, 'pa' => base64_encode($data['pagination'])));

            unset($ArrFnlList);

            die;

        } else {
            $dropdowndata['brand_data']=$brand_data;
            $this->load->view(CNFCOMPANY . 'manage_importer',$dropdowndata);

        }

    }
    public function consignor()
    {
        $VarFrom = xssclean($this->input->post('rfrom'));
        $VarAgent =trim(xssclean($this->input->post('agent')));
        $VarEmail_id = trim(xssclean($this->input->post('em')));
        $VarMobno =xssclean(preg_replace('/^\s+/', '+ ',  trim($this->input->post('mno'))));
        $VarContactPerson =trim(xssclean($this->input->post('cp')));
        $VarAgentCategory =xssclean($this->input->post('ac'));
        $VarBrand_id =xssclean($this->input->post('brand_id'));
        $VarStatus = xssclean($this->input->post('s'));
        $clickedColumnId = xssclean($this->input->post('columnId'));
        $newsortorder = xssclean($this->input->post('sortorder'));
        $VarAfilter = xssclean($this->input->post('afilter'));
        $brand_data = $this->db->select('id,brandname as name')->order_by('id', 'desc')->get_where(KN_MASTER_BRANDBUYER, array('status' =>1))->result();
        $ArrDbCols = array('agentname', 'contactname', 'status', 'dateupdated');
        if ($VarFrom == 1) {

            $VarURLSegment = 4;

            $this->load->library('pagination');

            $config['base_url'] = base_url() . CNFCOMPANY . 'Magent/consignor/';

            $config['total_rows'] = $this->Consignormodel->fnCount($VarAgent,$VarEmail_id,$VarMobno,$VarContactPerson,$VarAgentCategory,$VarBrand_id,$VarStatus,$VarAfilter);
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


            $ArrList = $this->Consignormodel->fnList($VarAgent,$VarEmail_id,$VarMobno,$VarContactPerson,$VarAgentCategory,$VarBrand_id,$VarStatus,$this->limit, $offset, $sortby, $sortorder, $VarAfilter)->result();

            $data['pagination'] = $this->pagination->create_linkswithajax('Consignor');

            $i = 0;

            $ArrFnlList = array();

            $ArrStatus = unserialize(ARRSTATUS);
            $ArrAgentCategory = unserialize(ARRVENDORCATEGORY);
          
            foreach ($ArrList as $ObjUnit) {
                //   var_dump($ArrAgentCategory[$ObjUnit->agent_categoryid]);
                $brand = $this->db->select('brandname')->get_where(KN_MASTER_BRANDBUYER, array('id' => $ObjUnit->brand_id))->row();
                $ArrFnlList[$i]['id'] = $ObjUnit->id;
                $ArrFnlList[$i]['agent'] = $ObjUnit->agentname;
                $ArrFnlList[$i]['em'] =!empty($ObjUnit->emailid)?$ObjUnit->emailid:'';
                $ArrFnlList[$i]['mbno']=!empty($ObjUnit->mobile)?$ObjUnit->mobile:'';
                $ArrFnlList[$i]['cp'] =!empty($ObjUnit->contactperson)?$ObjUnit->contactperson:'';
                $ArrFnlList[$i]['ac'] =!empty($ObjUnit->agent_categoryid)?$ArrAgentCategory[$ObjUnit->agent_categoryid]:'';
                $ArrFnlList[$i]['brand'] =!empty($brand)?$brand->brandname :'';
                $ArrFnlList[$i]['s'] = $ArrStatus[$ObjUnit->status];
                $ArrFnlList[$i]['ub'] = !empty($ObjUnit->contactname)?$ObjUnit->contactname:"";
                $ArrFnlList[$i]['du'] = date('d-m-Y H:i A', strtotime($ObjUnit->dateupdated));
                
                
                $i = $i + 1;

            }

            echo json_encode(array('errcode' => 1, 'cn' => $config['total_rows'], 'ct' => $i, 're' => $ArrFnlList, 'pa' => base64_encode($data['pagination'])));

            unset($ArrFnlList);

            die;

        } else {
            $dropdowndata['brand_data']=$brand_data;
            $this->load->view(CNFCOMPANY . 'manage_consignor',$dropdowndata);

        }

    }
    public function consignee()
    {
        $VarFrom = xssclean($this->input->post('rfrom'));
        $VarAgent =trim(xssclean($this->input->post('agent')));
        $VarEmail_id = trim(xssclean($this->input->post('em')));
        $VarMobno =xssclean(preg_replace('/^\s+/', '+ ',  trim($this->input->post('mno'))));
        $VarContactPerson =trim(xssclean($this->input->post('cp')));
        $VarAgentCategory =xssclean($this->input->post('ac'));
        $VarBrand_id =xssclean($this->input->post('brand_id'));
        $VarStatus = xssclean($this->input->post('s'));
        $clickedColumnId = xssclean($this->input->post('columnId'));
        $newsortorder = xssclean($this->input->post('sortorder'));
        $VarAfilter = xssclean($this->input->post('afilter'));
        $brand_data = $this->db->select('id,brandname as name')->order_by('id', 'desc')->get_where(KN_MASTER_BRANDBUYER, array('status' =>1))->result();
        $ArrDbCols = array('agentname', 'contactname', 'status', 'dateupdated');
        if ($VarFrom == 1) {

            $VarURLSegment = 4;

            $this->load->library('pagination');

            $config['base_url'] = base_url() . CNFCOMPANY . 'Magent/consignor/';

            $config['total_rows'] = $this->Consigneemodel->fnCount($VarAgent,$VarEmail_id,$VarMobno,$VarContactPerson,$VarAgentCategory,$VarBrand_id,$VarStatus,$VarAfilter);
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


            $ArrList = $this->Consigneemodel->fnList($VarAgent,$VarEmail_id,$VarMobno,$VarContactPerson,$VarAgentCategory,$VarBrand_id,$VarStatus,$this->limit, $offset, $sortby, $sortorder, $VarAfilter)->result();

            $data['pagination'] = $this->pagination->create_linkswithajax('Consignee');

            $i = 0;

            $ArrFnlList = array();

            $ArrStatus = unserialize(ARRSTATUS);
            $ArrAgentCategory = unserialize(ARRVENDORCATEGORY);
          
            foreach ($ArrList as $ObjUnit) {
                //   var_dump($ArrAgentCategory[$ObjUnit->agent_categoryid]);
                $brand = $this->db->select('brandname')->get_where(KN_MASTER_BRANDBUYER, array('id' => $ObjUnit->brand_id))->row();
                $ArrFnlList[$i]['id'] = $ObjUnit->id;
                $ArrFnlList[$i]['agent'] = $ObjUnit->agentname;
                $ArrFnlList[$i]['em'] =!empty($ObjUnit->emailid)?$ObjUnit->emailid:'';
                $ArrFnlList[$i]['mbno']=!empty($ObjUnit->mobile)?$ObjUnit->mobile:'';
                $ArrFnlList[$i]['cp'] =!empty($ObjUnit->contactperson)?$ObjUnit->contactperson:'';
                $ArrFnlList[$i]['ac'] =!empty($ObjUnit->agent_categoryid)?$ArrAgentCategory[$ObjUnit->agent_categoryid]:'';
                $ArrFnlList[$i]['brand'] =!empty($brand)?$brand->brandname :'';
                $ArrFnlList[$i]['s'] = $ArrStatus[$ObjUnit->status];
                $ArrFnlList[$i]['ub'] =!empty($ObjUnit->contactname)?$ObjUnit->contactname:"";
                $ArrFnlList[$i]['du'] = date('d-m-Y H:i A', strtotime($ObjUnit->dateupdated));
                
                
                $i = $i + 1;

            }

            echo json_encode(array('errcode' => 1, 'cn' => $config['total_rows'], 'ct' => $i, 're' => $ArrFnlList, 'pa' => base64_encode($data['pagination'])));

            unset($ArrFnlList);

            die;

        } else {
            $dropdowndata['brand_data']=$brand_data;
            $this->load->view(CNFCOMPANY . 'manage_consignee',$dropdowndata);

        }

    }
    public function getBuyerInfoByBrandId()
    {
        $VarFrom = xssclean($this->input->post('rFrom'));
        if ($VarFrom == 1) {
            $VarBrandId = xssclean($this->input->post('id'));
            $Res = $this->Brandmodel->fnGetInfo('', '', $VarBrandId);
            
            if (!empty($Res[0]['id']) && count($Res)>0) {
                echo json_encode(array('buyername' => $Res[0]['buyername']));
            } else {
                echo json_encode(array('buyername' => ''));
            }
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
                if ($this->db->update(KN_MASTER_AGENTS, array('status' => $VarStatus))) {
                    echo json_encode(array('errcode' => 1));
                }
                return false;
            }
            die;
        }
    }
}