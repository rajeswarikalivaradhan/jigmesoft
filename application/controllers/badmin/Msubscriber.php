<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Msubscriber extends CI_Controller {
    public function __construct() {
        parent::__construct();
        fnIfCheckUserLoggedIn();
        $this->load->model('commonmodel');
        $this->load->model(CNFBADMIN . "Subscribermodel");
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
        //$ArrPackage = $this->commonmodel->fnGetAllTableInfo(KN_MASTER_PACKAGE  . ' AS p', 'p.id,p.description', array('p.status' => '1', 'p.companyid' => $this->companyid),3);
        $ArrPackage = $this->commonmodel->fnGetAllTableInfo(KN_MASTER_PACKAGE  . ' AS p', 'p.id,p.description', array( 'p.companyid' => $this->companyid),3);
        $ArrData['ArrPackage'] = $ArrPackage;

       
        $ArrData['ArrReqStatus'] = unserialize(ARRREQUESTSTATUS);
         
        $checkDraftorNot = $this->Subscribermodel->checkDraftorNot($VarId);
        //$last = $this->uri->segment_array();
        $lastURI = $this->uri->segment(4);
       // var_dump($this->uri->segment(4));
       $ArrData['checkDraftorNot']=$checkDraftorNot;
       $ArrData['lastURI'] = $lastURI;
        if (is_numeric($VarId)) {
            $ArrResults = $this->Subscribermodel->fnGetInfo('', '', $VarId);
            $packinfo=$this->commonmodel->getPackageInfoFromPckg($ArrResults[0]->package_id);
            $ArrResults[0]->no_of_users=!empty($packinfo)?$packinfo->no_of_users:'';
            $ArrResults[0]->data_limit=!empty($packinfo)?$packinfo->data_limit:'';
            $ArrResults[0]->file_limit=!empty($packinfo)?$packinfo->file_limit:'';
            $ArrData['BasicInfo'] = $ArrResults[0];
            $ArrData['VarId'] = $ArrResults[0]->id;
        } else {
        }
        //print_r($ArrResults);
        $this->load->view(CNFBADMIN . 'subscriber/addedit_form', $ArrData);
    }
    public function updateInfo() {
        $ArrResult = array();
        $Varcmpny = xssclean($this->input->post('cmpny'));
        $Varbtype = xssclean($this->input->post('bt'));
        $Varcntperson = xssclean($this->input->post('cp'));
        $Vardesign = xssclean($this->input->post('desgn'));
        $Varemail_id = xssclean($this->input->post('em'));
        $Varmobno = xssclean($this->input->post('mbno'));
        $Vargstno = xssclean($this->input->post('gstno')); 
        $Variecodeno = xssclean($this->input->post('iecode')); 
        $Varaddrs = xssclean($this->input->post('addrs'));
        $Varcty = xssclean($this->input->post('cty'));
        $Varst = xssclean($this->input->post('st'));
        $Varctry = xssclean($this->input->post('ctry'));
        $Varpin = xssclean($this->input->post('pin'));
        $Varsubctgy = xssclean($this->input->post('subctgy'));
        $Varpckdetid = xssclean($this->input->post('pckdetid'));
        $Varpurchtype = xssclean($this->input->post('purchtype'));
        $Varadditionalusers = xssclean($this->input->post('additionalusers'));
        $Vardatastrlimit = xssclean($this->input->post('datastrlimit'));
        $Varfilestrlimit = xssclean($this->input->post('filestrlimit'));
        $Varremarks = xssclean($this->input->post('remarks'));
        $Vardraft_status = xssclean($this->input->post('draftstatus'));
        $Varmrkt_dept_userid = xssclean($this->input->post('mrkt_dept_userid'));
        $VarId = xssclean($this->input->post('id'));
        
        if ($Vardraft_status <> '') {
            $ArrData = array('id' => $VarId, 'companyname' => $Varcmpny,'businesstype' => $Varbtype,'contactperson' => $Varcntperson,
                             'designation' => $Vardesign,'email_id' => $Varemail_id,'mobile_no' => $Varmobno,'gst_no' => $Vargstno,'IECODE' => $Variecodeno,
                             'address' => $Varaddrs,'city' => $Varcty,'state' => $Varst,'country' => $Varctry,'pincode' => $Varpin,
                             'subscription_category' => $Varsubctgy,'package_id' => $Varpckdetid,'purchasetype' => $Varpurchtype,
                             'additional_users' => $Varadditionalusers,'data_storage_limit' => $Vardatastrlimit,'file_storage_limit' => $Varfilestrlimit,
                             'remarks' => $Varremarks,'mrkt_dept_userid'=>$Varmrkt_dept_userid,'draft_status'=>$Vardraft_status,'dateupdated' => date('Y-m-d H:i:s')
                            );
            if (empty($VarId)) {
                $ArrData['datecreated'] = date('Y-m-d H:i:s');
            }
            $ArrResult = ($Vardraft_status==2)?$this->Subscribermodel->saveInfo($ArrData):$this->Subscribermodel->saveDraftInfo($ArrData);
        } else {
            $ArrResult['errcode'] = -1;
            $ArrResult['msg'] = 'Invalid Input';
        }
        echo json_encode($ArrResult);
    }
    public function updatesubscriberInfo() {
        $ArrResult = array();
        $Varcmpny = xssclean($this->input->post('cmpny'));
        $Varbtype = xssclean($this->input->post('bt'));
        $Varcntperson = xssclean($this->input->post('cp'));
        $Vardesign = xssclean($this->input->post('desgn'));
        $Varemail_id = xssclean($this->input->post('em'));
        $Varmobno = xssclean($this->input->post('mbno'));
        $Vargstno = xssclean($this->input->post('gstno')); 
        $Variecodeno = xssclean($this->input->post('iecode'));
        $Varaddrs = xssclean($this->input->post('addrs'));
        $Varcty = xssclean($this->input->post('cty'));
        $Varst = xssclean($this->input->post('st'));
        $Varctry = xssclean($this->input->post('ctry'));
        $Varpin = xssclean($this->input->post('pin'));
        $Varsubctgy = xssclean($this->input->post('subctgy'));
        $Varpckdetid = xssclean($this->input->post('pckdetid'));
        $Varpurchtype = xssclean($this->input->post('purchtype'));
        $Varadditionalusers = xssclean($this->input->post('additionalusers'));
        $Vardatastrlimit = xssclean($this->input->post('datastrlimit'));
        $Varfilestrlimit = xssclean($this->input->post('filestrlimit'));
        $Varremarks = xssclean($this->input->post('remarks'));
        $Vardraft_status = xssclean($this->input->post('draftstatus'));
        $Varmrkt_dept_userid = xssclean($this->input->post('mrkt_dept_userid'));
        $VarsubscriberId = xssclean($this->input->post('id'));
        $Varproforma_id= ($this->input->post('proforma_id'))?xssclean($this->input->post('proforma_id')):''; 
      
            
        if ($Vardraft_status <> '') {
            if(!empty($Varproforma_id)){
                $ArrData =  array('id' => $Varproforma_id,
                                 'companyname' => $Varcmpny,
                                 'businesstype' => $Varbtype,
                                 'contactperson' => $Varcntperson,
                                 'designation' => $Vardesign,
                                 'email_id' => $Varemail_id,
                                 'mobile_no' => $Varmobno,
                                 'remarks' => $Varremarks,
                                 'gst_no' => $Vargstno,
                                 'IECODE' => $Variecodeno,
                                 'address' => $Varaddrs,
                                 'city' => $Varcty,
                                 'state' => $Varst,
                                 'country' => $Varctry,
                                 'pincode' => $Varpin,
                                 'additional_users' => $Varadditionalusers,
                                 'data_storage_limit' => $Vardatastrlimit,
                                 'file_storage_limit' => $Varfilestrlimit,
                                 'package_id' => $Varpckdetid,
                                 'purchasetype' => $Varpurchtype,
                                 'dateupdated' => date('Y-m-d H:i:s')
                                );
                                //print_r($ArrData);
                                //die;
                $ArrResult =$this->Subscribermodel->updatesubscriberInfo($ArrData);
            }
            
        } else {
            $ArrResult['errcode'] = -1;
            $ArrResult['msg'] = 'Invalid Input';
        }
        echo json_encode($ArrResult);
    }
    public function manage() {
        $VarFrom = xssclean($this->input->post('rfrom'));
        $Varcmpny = trim(xssclean($this->input->post('cmpny')));
        $Varcntperson = trim(xssclean($this->input->post('cp')));
        $Varmobno =trim( xssclean($this->input->post('mbno')));
        $Varcty =trim( xssclean($this->input->post('cty')));
        $Varpckdetid =xssclean($this->input->post('pckdetid'));
        $Varreqstatus =xssclean($this->input->post('reqstatus'));
        $clickedColumnId = xssclean($this->input->post('columnId'));
        $newsortorder = xssclean($this->input->post('sortorder'));
        $ArrDbCols = array('companyname', 'contactperson', 'email_id', 'mobile_no', 'city', 'state', 'package_id', 'requeststatus', 'status', 'dateupdated'); 
        $ArrStatus = unserialize(ARRSTATUS);
        $ArrReqStatus = unserialize(ARRREQUESTSTATUS);
        $ArrPackage = $this->commonmodel->fnGetAllTableInfo(KN_MASTER_PACKAGE  . ' AS p', 'p.id,p.description', array('p.status' => '1'),3);
        $checkDraftorNot= $this->Subscribermodel->getdraftdata();
        if ($VarFrom == 1) {
            $VarURLSegment = 5;
            $this->load->library('pagination');
            $config['base_url'] = base_url() . CNFBADMIN . 'msubscriber/manage/';
            $config['total_rows'] = $this->Subscribermodel->fnCount($Varcmpny, $Varcntperson,$Varmobno,$Varcty,$Varpckdetid,$Varreqstatus);
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
            $ArrList = $this->Subscribermodel->fnList($Varcmpny, $Varcntperson,$Varmobno,$Varcty,$Varpckdetid,$Varreqstatus, $this->limit, $offset, $sortby, $sortorder)->result();
            $data['pagination'] = $this->pagination->create_linkswithajax('Subscriber');
            $i = 0;
            $ArrFnlList = array();
            foreach ($ArrList as $Obj) {
                $ArrFnlList[$i]['id'] = $Obj->id;
                $ArrFnlList[$i]['cmpny'] = $Obj->companyname;
                $ArrFnlList[$i]['cp'] = $Obj->contactperson;
                $ArrFnlList[$i]['em'] = $Obj->email_id;
                $ArrFnlList[$i]['mbno'] = $Obj->mobile_no;
                $ArrFnlList[$i]['cty'] = $Obj->city;
                $ArrFnlList[$i]['st'] = $Obj->state;
                $ArrFnlList[$i]['pckdet'] = !empty($Obj->package_id)? $this->commonmodel->getPackageInfoFromPckg($Obj->package_id)->description:'';
                $ArrFnlList[$i]['reqstatus'] = isset($Obj->requeststatus)?$ArrReqStatus[$Obj->requeststatus]:'';
                $ArrFnlList[$i]['formateddate'] = $Obj->formattedDate;
                $ArrFnlList[$i]['s'] = $ArrStatus[$Obj->status];
                $i = $i + 1;
            }
            echo json_encode(array('errcode' => 1, 'cn' => $config['total_rows'], 'ct' => $i, 're' => $ArrFnlList, 'pa' => base64_encode($data['pagination'])));
            unset($ArrFnlList);
            die;
        } else {
            $this->load->view(CNFBADMIN . 'subscriber/subscriber_list', array('ArrStatus' => $ArrStatus,'ArrPackage'=>$ArrPackage,'ArrReqStatus'=>$ArrReqStatus,'checkDraftorNot'=>$checkDraftorNot));
        }
    }

    function changemStatus() {
        $VarType = xssclean($this->input->post('type'));
        $VarCid = xssclean($this->input->post('cid'));
        if ($VarType <> '' && $VarCid <> '') {
            $this->Subscribermodel->fnChangeComStat($VarCid, $VarType);
        }
        echo json_encode(array('errcode' => 1));
        die;
    }
    public function getPackageInfoByPckgId()
    {
        $VarFrom = xssclean($this->input->post('rFrom'));
        if ($VarFrom == 1) {
            $VarPckgId = xssclean($this->input->post('id'));
            $Res = $this->commonmodel->getPackageInfoFromPckg($VarPckgId);
            if (!empty($Res->id)) {
                echo json_encode(array('no_of_users' => $Res->no_of_users,'data_limit' => $Res->data_limit,'file_limit'=>$Res->file_limit));
            } else {
                echo json_encode(array('no_of_users' => '','data_limit' => '','file_limit' => ''));
            }
        }
    }
    public function getcleardraftstatus() 
    {   
        $enquiry_id = intval(xssclean($this->input->post('id')));
        if(!empty($enquiry_id)){
        $ArrResult=$this->Subscribermodel->cleardraft($enquiry_id);
        echo json_encode($ArrResult);
        }else{
           echo false; 
        }
    }
    public function proformareq()
    {

        $id = xssclean($this->input->post('id'));
        $reqstatus = xssclean($this->input->post('reqstatus'));
        $status = xssclean($this->input->post('status'));
        $reqdatetime = date('Y-m-d H:i:s');

        $this->db->where('requeststatus', $reqstatus);
        $this->db->where('status', $status);
        $this->db->where('id', $id);
        $this->db->from(KN_SUBSCRIBERENQUIRY);
        $get_query = $this->db->get();
        $detail = $get_query->result();
        if(sizeof($detail) > 0) {
            $result["status"] = "success";
            $result["statusCode"] = "203";
            $result["message"] = "Your request is already sent";
        }
        else {
            $this->db->where('id', $id);
            $this->db->update(KN_SUBSCRIBERENQUIRY,array('requeststatus' => $reqstatus,'status' => $status,'reqdatetime'=>$reqdatetime));        
            if ($this->db->affected_rows() == '1') {
                $result["status"] = "success";
                $result["statusCode"] = "200";
                $result["message"] = "Request sent successfully";
            }
            else {
                $result["status"] = "fail";
                $result["statusCode"] = "400";
                $result["message"] = "Error in request sending";
            }
        }
        echo json_encode($result);
    }
}