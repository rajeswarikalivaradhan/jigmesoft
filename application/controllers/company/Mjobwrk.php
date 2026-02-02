<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Mjobwrk extends CI_Controller {
    public function __construct() {
        parent::__construct();
        fnIfCheckUserLoggedIn();
        $this->load->model(CNFCOMPANY . "Mdyeingjobworkmodel");
        $this->load->model(CNFCOMPANY . "Membelljobworkmodel");
        $this->load->helper('xssclean');
        $this->limit = LIMITPERPAGE;
    }
   
    public function addedit() {
        $ArrData = array('ArrBasicInfo' => array(), 'VarId' => '');
        $VarHashedId = $this->uri->segment(4);
        $ArrData['Edit'] = $this->uri->segment(5);
        if ($VarHashedId <> '' && base64_decode(urldecode($VarHashedId))) {
            $VarId = base64_decode(urldecode($VarHashedId));
            $ArrResults = $this->Mdyeingjobworkmodel->fnGetInfo('', '', $VarId);
            $ArrData['ArrBasicInfo'] = $ArrResults[0];
            $ArrData['VarId'] = $ArrResults[0]['id'];
        } else {
        }
        $this->load->view(CNFCOMPANY . 'addedit_dyeingjobwrk', $ArrData);
    }
    public function addeditembl() {
        $ArrData = array('ArrBasicInfo' => array(), 'VarId' => '');
        $VarHashedId = $this->uri->segment(4);
        $ArrData['Edit'] = $this->uri->segment(5);
        if ($VarHashedId <> '' && base64_decode(urldecode($VarHashedId))) {
            $VarId = base64_decode(urldecode($VarHashedId));
            $ArrResults = $this->Membelljobworkmodel->fnGetInfo('', '', $VarId);
            $ArrData['ArrBasicInfo'] = $ArrResults[0];
            $ArrData['VarId'] = $ArrResults[0]['id'];
        } else {
        }
        $this->load->view(CNFCOMPANY . 'addedit_embljobwrk', $ArrData);
    }
    
    public function updatedyeingInfo()
    {
        $VarUserInfo = fnGetUserLoggedInfo(1);
        if (isset($VarUserInfo['companyid'])) {
            $VarCompanyId = $VarUserInfo['companyid'];
        }
        $ArrResult = array();
        $VarJobwrkname = xssclean($this->input->post('jbwrkname'));
        $VarAddr = xssclean($this->input->post('addr'));
        $VarEmailId = xssclean($this->input->post('em'));
        $VarMobno = xssclean($this->input->post('mbno'));
        $VarPhno = xssclean($this->input->post('phno'));
        $VarContacperson= xssclean($this->input->post('cp'));
        $VarJobwrkCategory= xssclean($this->input->post('jbwrkc'));
        $Varprimaryjobln= xssclean($this->input->post('pmln'));
        $Varsecondaryjobln=xssclean($this->input->post('scln'));
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
        
        if ($VarJobwrkname <> '') {
            $ArrData = array('id' => $VarId, 
                             'jobwrkname' => $VarJobwrkname, 
                             'address'=>$VarAddr,
                             'emailid'=>$VarEmailId,
                             'phone'=>$VarPhno,
                             'mobile'=>$VarMobno,
                             'contactperson'=>$VarContacperson,
                             'wrk_categoryid '=>$VarJobwrkCategory,
                             'primaryjob_wrkline'=>$Varprimaryjobln,
                             'secondaryjob_wrkline'=>$Varsecondaryjobln,
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
            $ArrResult = $this->Mdyeingjobworkmodel->saveInfo($ArrData);
        } else {
            $ArrResult['errcode'] = -1;
            $ArrResult['msg'] = 'Invalid Input!';
        }
        echo json_encode($ArrResult);
    }
     public function updateemblInfo()
    {
        $VarUserInfo = fnGetUserLoggedInfo(1);
        if (isset($VarUserInfo['companyid'])) {
            $VarCompanyId = $VarUserInfo['companyid'];
        }
        $ArrResult = array();
        $VarJobwrkname = xssclean($this->input->post('jbwrkname'));
        $VarAddr = xssclean($this->input->post('addr'));
        $VarEmailId = xssclean($this->input->post('em'));
        $VarMobno = xssclean($this->input->post('mbno'));
        $VarPhno = xssclean($this->input->post('phno'));
        $VarContacperson= xssclean($this->input->post('cp'));
        $VarJobwrkCategory= xssclean($this->input->post('jbwrkc'));
        $Varprimaryjobln= xssclean($this->input->post('pmln'));
        $Varsecondaryjobln=xssclean($this->input->post('scln'));
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
        
        if ($VarJobwrkname <> '') {
            $ArrData = array('id' => $VarId, 
                             'jobwrkname' => $VarJobwrkname, 
                             'address'=>$VarAddr,
                             'emailid'=>$VarEmailId,
                             'phone'=>$VarPhno,
                             'mobile'=>$VarMobno,
                             'contactperson'=>$VarContacperson,
                             'wrk_categoryid '=>$VarJobwrkCategory,
                             'primaryjob_wrkline'=>$Varprimaryjobln,
                             'secondaryjob_wrkline'=>$Varsecondaryjobln,
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
            $ArrResult = $this->Membelljobworkmodel->saveInfo($ArrData);
        } else {
            $ArrResult['errcode'] = -1;
            $ArrResult['msg'] = 'Invalid Input!';
        }
        echo json_encode($ArrResult);
    }
    public function mdyeing() {
        $VarFrom = xssclean($this->input->post('rfrom'));
        $VarJobwrkname =trim(xssclean($this->input->post('jbwrkname')));
        $VarContactPerson =trim(xssclean($this->input->post('cp')));
        $VarEmailId = trim(xssclean($this->input->post('em')));
        $VarMobno =xssclean(preg_replace('/^\s+/', '+ ',  trim($this->input->post('mno'))));
        $VarJobwrkCategory =xssclean($this->input->post('jbwrkc'));
        $Varprimaryjobln =trim(xssclean($this->input->post('pmryln')));
        $VarStatus = xssclean($this->input->post('s'));
        $clickedColumnId = xssclean($this->input->post('columnId'));
        $newsortorder = xssclean($this->input->post('sortorder'));
        $VarAfilter = xssclean($this->input->post('afilter'));
        $ArrDbCols = array('jobwrkname', 'contactperson', 'emailid', 'phone', 'mobile', 'status', 'dateupdated');
        if ($VarFrom == 1) {
            $VarURLSegment = 4;
            $this->load->library('pagination');
            $config['base_url'] = base_url() . CNFCOMPANY . 'mjobwrk/mdyeing/';
            $config['total_rows'] = $this->Mdyeingjobworkmodel->fnCount($VarJobwrkname, $VarContactPerson, $VarEmailId, $VarMobno,$VarJobwrkCategory,$Varprimaryjobln,$VarStatus, $VarAfilter);
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
            $ArrList = $this->Mdyeingjobworkmodel->fnList($VarJobwrkname, $VarContactPerson, $VarEmailId,$VarMobno,$VarJobwrkCategory,$Varprimaryjobln, $VarStatus, $VarAfilter, $this->limit, $offset, $sortby, $sortorder)->result();
            $data['pagination'] = $this->pagination->create_linkswithajax('dyeingjobwrkdet');
            $i = 0;
            $ArrFnlList = array();
            $ArrStatus = unserialize(ARRSTATUS);
            $ArrAgentCategory = unserialize(ARRVENDORCATEGORY);
            foreach ($ArrList as $Obj) {
                $ArrFnlList[$i]['id'] = $Obj->id;
                $ArrFnlList[$i]['jbwrkname'] = $Obj->jobwrkname;
                $ArrFnlList[$i]['cp'] = $Obj->contactperson;
                $ArrFnlList[$i]['em'] = $Obj->emailid;
                $ArrFnlList[$i]['phno'] = $Obj->phone;
                $ArrFnlList[$i]['mno'] = $Obj->mobile;
                $ArrFnlList[$i]['jbwrkc'] = !empty($Obj->wrk_categoryid)?$ArrAgentCategory[$Obj->wrk_categoryid]:'';;
                $ArrFnlList[$i]['prmyln'] = $Obj->primaryjob_wrkline;
                $ArrFnlList[$i]['s'] = $ArrStatus[$Obj->status];
                $ArrFnlList[$i]['ub'] = $Obj->contactname;
                $ArrFnlList[$i]['du'] = date('d-m-Y H:i A', strtotime($Obj->dateupdated));
                $i = $i + 1;
            }
            echo json_encode(array('errcode' => 1, 'cn' => $config['total_rows'], 'ct' => $i, 're' => $ArrFnlList, 'pa' => base64_encode($data['pagination'])));
            unset($ArrFnlList);
            die;
        } else {
            $this->load->view(CNFCOMPANY . 'manage_dyeingjobwrk');
        }
    }
    public function membelish() {
        $VarFrom = xssclean($this->input->post('rfrom'));
        $VarJobwrkname =trim(xssclean($this->input->post('jbwrkname')));
        $VarContactPerson =trim(xssclean($this->input->post('cp')));
        $VarEmailId = trim(xssclean($this->input->post('em')));
        $VarMobno =xssclean(preg_replace('/^\s+/', '+ ',  trim($this->input->post('mno'))));
        $VarJobwrkCategory =xssclean($this->input->post('jbwrkc'));
        $Varprimaryjobln =trim(xssclean($this->input->post('pmryln')));
        $VarStatus = xssclean($this->input->post('s'));
        $clickedColumnId = xssclean($this->input->post('columnId'));
        $newsortorder = xssclean($this->input->post('sortorder'));
        $VarAfilter = xssclean($this->input->post('afilter'));
        $ArrDbCols = array('jobwrkname', 'contactperson', 'emailid', 'phone', 'mobile', 'status', 'dateupdated');
        if ($VarFrom == 1) {
            $VarURLSegment = 4;
            $this->load->library('pagination');
            $config['base_url'] = base_url() . CNFCOMPANY . 'mjobwrk/membelish/';
            $config['total_rows'] = $this->Membelljobworkmodel->fnCount($VarJobwrkname, $VarContactPerson, $VarEmailId, $VarMobno,$VarJobwrkCategory,$Varprimaryjobln,$VarStatus, $VarAfilter);
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
            $ArrList = $this->Membelljobworkmodel->fnList($VarJobwrkname, $VarContactPerson, $VarEmailId,$VarMobno,$VarJobwrkCategory,$Varprimaryjobln, $VarStatus, $VarAfilter, $this->limit, $offset, $sortby, $sortorder)->result();
            $data['pagination'] = $this->pagination->create_linkswithajax('embelljobwrkdet');
            $i = 0;
            $ArrFnlList = array();
            $ArrStatus = unserialize(ARRSTATUS);
            $ArrAgentCategory = unserialize(ARRVENDORCATEGORY);
            foreach ($ArrList as $Obj) {
                $ArrFnlList[$i]['id'] = $Obj->id;
                $ArrFnlList[$i]['jbwrkname'] = $Obj->jobwrkname;
                $ArrFnlList[$i]['cp'] = $Obj->contactperson;
                $ArrFnlList[$i]['em'] = $Obj->emailid;
                $ArrFnlList[$i]['phno'] = $Obj->phone;
                $ArrFnlList[$i]['mno'] = $Obj->mobile;
                $ArrFnlList[$i]['jbwrkc'] = !empty($Obj->wrk_categoryid)?$ArrAgentCategory[$Obj->wrk_categoryid]:'';;
                $ArrFnlList[$i]['prmyln'] = $Obj->primaryjob_wrkline;
                $ArrFnlList[$i]['s'] = $ArrStatus[$Obj->status];
                $ArrFnlList[$i]['ub'] = $Obj->contactname;
                $ArrFnlList[$i]['du'] = date('d-m-Y H:i A', strtotime($Obj->dateupdated));
                $i = $i + 1;
            }
            echo json_encode(array('errcode' => 1, 'cn' => $config['total_rows'], 'ct' => $i, 're' => $ArrFnlList, 'pa' => base64_encode($data['pagination'])));
            unset($ArrFnlList);
            die;
        } else {
            $this->load->view(CNFCOMPANY . 'manage_emblljobwrk');
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
                if ($this->db->update(KN_MASTER_JOBWRK, array('status' => $VarStatus))) {
                    echo json_encode(array('errcode' => 1));
                }
                return false;
            }
            die;
        }
    }
}