<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Mreqrcved extends CI_Controller {
    public function __construct() {
        parent::__construct();
        fnIfCheckUserLoggedIn();
        $this->load->model('commonmodel');
        $this->load->model(CNFBADMIN . "Subscribermodel");
        $this->load->model(CNFBADMIN . "Reqrecivedmodel");
        $this->load->helper('xssclean');
        $ArrUserLoggedInfo = fnGetUserLoggedInfo('1');
        $this->companyid = $ArrUserLoggedInfo['companyid'];
        $this->userid = $ArrUserLoggedInfo['id'];
        $this->limit = LIMITPERPAGE;
        date_default_timezone_set('Asia/Kolkata');
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
        $proformaDraftorNot = $this->Reqrecivedmodel->checkDraftorNot($VarId);
        //$last = $this->uri->segment_array();
        $lastURI = $this->uri->segment(4);
       // var_dump($this->uri->segment(4));
       $ArrData['checkDraftorNot']=$checkDraftorNot;
       $ArrData['proformaDraftorNot']=$proformaDraftorNot;
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
        $this->load->view(CNFBADMIN . 'reqreceived/addedit_form', $ArrData);
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
    
    public function manage() {
        $VarFrom = xssclean($this->input->post('rfrom'));
        $Varcmpny = trim(xssclean($this->input->post('cmpny')));
        $Varcty =trim( xssclean($this->input->post('cty')));
        $Varpckdetid =xssclean($this->input->post('pckdetid'));
        $Varpurchtype= trim(xssclean($this->input->post('purchtype')));
        $Varreqraisedby =trim( xssclean($this->input->post('reqraisedby')));
        $Varreqstatus =xssclean($this->input->post('reqstatus'));
        $clickedColumnId = xssclean($this->input->post('columnId'));
        $newsortorder = xssclean($this->input->post('sortorder'));
        $ArrDbCols = array('companyname','city', 'state', 'package_id','purchasetype', 'mrkt_dept_userid', 'reqdatetime', 'requeststatus','dateupdated', 'status'); 
        $ArrStatus = unserialize(ARRSTATUS);
        $ArrReqStatus = unserialize(ARRREQUESTSTATUS);
        $ArrPurchasetype = unserialize(ARRPURCHASETYPE);
        $ArrPackage = $this->commonmodel->fnGetAllTableInfo(KN_MASTER_PACKAGE  . ' AS p', 'p.id,p.description', array('p.status' => '1'),3);
        $checkDraftorNot= $this->Subscribermodel->getdraftdata();
        if ($VarFrom == 1) {
            $VarURLSegment = 5;
            $this->load->library('pagination');
            $config['base_url'] = base_url() . CNFBADMIN . 'mreqrcved/manage/';
            $config['total_rows'] = $this->Reqrecivedmodel->fnCount($Varcmpny, $Varcty,$Varpckdetid,$Varpurchtype,$Varreqraisedby,$Varreqstatus);
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
            $ArrList = $this->Reqrecivedmodel->fnList($Varcmpny, $Varcty,$Varpckdetid,$Varpurchtype,$Varreqraisedby,$Varreqstatus, $this->limit, $offset, $sortby, $sortorder)->result();
            $data['pagination'] = $this->pagination->create_linkswithajax('Reqrecved');
            $i = 0;
            $ArrFnlList = array();
            foreach ($ArrList as $Obj) {
                $ArrFnlList[$i]['id'] = $Obj->id;
                $ArrFnlList[$i]['cmpny'] = $Obj->companyname;
                $ArrFnlList[$i]['cty'] = $Obj->city;
                $ArrFnlList[$i]['st'] = $Obj->state;
                $ArrFnlList[$i]['pckdet'] = !empty($Obj->package_id)? $this->commonmodel->getPackageInfoFromPckg($Obj->package_id)->description:'';
                $ArrFnlList[$i]['purchtype'] = isset($Obj->purchasetype)?$ArrPurchasetype[$Obj->purchasetype]:'';
                $ArrFnlList[$i]['reqraisedby'] = $Obj->request_raised_by;
                $ArrFnlList[$i]['reqdatetime'] = $Obj->reqdatetime;
                $ArrFnlList[$i]['reqstatus'] = isset($Obj->requeststatus)?$ArrReqStatus[$Obj->requeststatus]:'';
                $ArrFnlList[$i]['recent_updated_datetime'] = $Obj->recent_updated_datetime;
                $ArrFnlList[$i]['s'] = $ArrStatus[$Obj->status];
                $i = $i + 1;
            }
            echo json_encode(array('errcode' => 1, 'cn' => $config['total_rows'], 'ct' => $i, 're' => $ArrFnlList, 'pa' => base64_encode($data['pagination'])));
            unset($ArrFnlList); 
            die;
        } else {
            $this->load->view(CNFBADMIN . 'reqreceived/reqrcved_list', array('ArrStatus' => $ArrStatus,'ArrPackage'=>$ArrPackage,'ArrReqStatus'=>$ArrReqStatus,'checkDraftorNot'=>$checkDraftorNot,'ArrPurchasetype'=>$ArrPurchasetype));
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
    public function raiseproforma() {
        $VarId = base64_decode(urldecode($this->uri->segment(4)));
        $type =urldecode($this->uri->segment(5));
        $ArrData = array('BasicInfo' => '', 'VarId' => '');
        $ArrData['lastURI'] = $VarId;
        $purchase_type = unserialize(ARRPURCHASETYPE);
        $subscription_period = unserialize(ARRSUBSCRIPTIONPERIOD);
        $start='JIGME';
        $prefix= date("m"); // getting current month in number format of 01-12
        $suffix='P';
       // $invoicerefno =$this->commonmodel->keyGenerate(KN_PROFORMAINVOICE, $prefix,'invoice_refno',$start);  
        $invoicerefno =$this->commonmodel->keyGenerateNewone(KN_PROFORMAINVOICE, $prefix,'invoice_refno',$start,$suffix);  
        $checkDraftorNot = $this->Reqrecivedmodel->checkDraftorNot($VarId);
        $ArrData['checkDraftorNot']=$checkDraftorNot;
        if (is_numeric($VarId)) {
            $ArrResults = $this->Subscribermodel->fnGetInfo('', '', $VarId);
            $draftrslt= $this->Reqrecivedmodel->getdraftdata();
            $ArrData['BasicInfo'] = $ArrResults[0];
            $ArrData['draftdata'] = !empty($draftrslt)?$draftrslt:'';
            $ArrData['amount']=!empty($draftrslt)?(($draftrslt->subtotal!='0.00')?$this->commonmodel->getIndianCurrency($draftrslt->subtotal).'only.':''):'';
            $ArrData['purchase_type']=!empty($ArrResults[0])?$purchase_type[$ArrResults[0]->purchasetype]:'';
            //$ArrData['packagedet']=!empty($ArrResults[0])?$this->commonmodel->getPackageInfoFromPckg($ArrResults[0]->package_id)->description:'';
            $ArrData['SubscriberId'] = $ArrResults[0]->id;
            $ArrData['VarId'] = !empty($draftrslt)?$draftrslt->id:'';
            $ArrData['invoicerefno'] = $invoicerefno;
        } else {
        }
        if(!empty($type) && $type=='sub'){
            $recentactiveproforma= $this->Reqrecivedmodel->get_recently_active_proforma($ArrResults[0]->id);
            $ArrData['recent_proforma_id'] = !empty($recentactiveproforma)?$recentactiveproforma->id:'';
            $this->load->view(CNFBADMIN . 'reqreceived/subsc_raise_proformainv',$ArrData);
        }else{
            $this->load->view(CNFBADMIN . 'reqreceived/raise_proforma_invoice',$ArrData);
        }
        
    }
    public function getquotedetails_igst()
    {
        $enqId = xssclean($this->input->post('enquiry_id'));
        $profromaId = xssclean($this->input->post('proforma_id'));
        $invotype= xssclean($this->input->post('invotype'));
        $proformatype = xssclean($this->input->post('proformatype'));
        $data  = $this->Reqrecivedmodel->getQuotedetailsGrid_igst($enqId,$invotype,$proformatype,$profromaId);
        echo json_encode($data);
    }
    public function getquotedetails_cgst_sgst()
    {
        $enqId = xssclean($this->input->post('enquiry_id'));
        $profromaId = xssclean($this->input->post('proforma_id'));
        $invotype = xssclean($this->input->post('invotype'));
        $proformatype = xssclean($this->input->post('proformatype'));
        $data  = $this->Reqrecivedmodel->getQuotedetailsGrid_cgst_sgst($enqId,$invotype,$proformatype,$profromaId);
        echo json_encode($data);
    }
    public function getsupplquotedetails_cgst_sgst()
    {
        $enqId = xssclean($this->input->post('enquiry_id'));
        $invotype = xssclean($this->input->post('invotype'));
        $proformatype = xssclean($this->input->post('proformatype'));
        $purchasetype = xssclean($this->input->post('purchasetype'));
        $data  = $this->Reqrecivedmodel->getsupplQuotedetailsGrid_cgst_sgst($enqId,$invotype,$proformatype,$purchasetype);
        echo json_encode($data);
    }
    public function getsupplquotedetails_igst()
    {
        $enqId = xssclean($this->input->post('enquiry_id'));
        $invotype= xssclean($this->input->post('invotype'));
        $proformatype = xssclean($this->input->post('proformatype'));
        $purchasetype = xssclean($this->input->post('purchasetype'));
        $data  = $this->Reqrecivedmodel->getsupplQuotedetailsGrid_igst($enqId,$invotype,$proformatype,$purchasetype);
        echo json_encode($data);
    }
    public function updateproformaInfo() {
        $ArrResult = array();
        $Varinvotype = xssclean($this->input->post('invotype'));
        $Varvalidity = xssclean($this->input->post('validity'));
        $Varterms = xssclean($this->input->post('terms'));
        $Vardraft_status = xssclean($this->input->post('draftstatus'));
        $Varsubsciber_id = xssclean($this->input->post('subsciber_id'));
        $Varsubscription_period = xssclean($this->input->post('subscription_period'));
        $VarId = xssclean($this->input->post('id'));
        $object = xssclean($this->input->post('object'));
        $data   = json_decode($object);
        $Varinvoice_no = ($Vardraft_status==2)?(xssclean($this->input->post('invoice_no'))):''; 
        $Subscriberdet = $this->Subscribermodel->fnGetInfo('', '', $Varsubsciber_id);
        $Varptype = xssclean($this->input->post('purchasetype')); 
        $master_proforma_id = xssclean($this->input->post('master_proforma_id')); 
        
        $varpurchasetype=!empty($Varptype)?$Varptype:(!empty($Subscriberdet[0])?$Subscriberdet[0]->purchasetype:'');
        $varcompanyname=!empty($Subscriberdet[0])?$Subscriberdet[0]->companyname:'';
        $varcity=!empty($Subscriberdet[0])?$Subscriberdet[0]->city:'';
        $varstate=!empty($Subscriberdet[0])?$Subscriberdet[0]->state:'';
        $varcountry=!empty($Subscriberdet[0])?$Subscriberdet[0]->country:'';
        $varpincode=!empty($Subscriberdet[0])?$Subscriberdet[0]->pincode:'';
        $varaddress=!empty($Subscriberdet[0])?$Subscriberdet[0]->address:'';
        $vargst_no=!empty($Subscriberdet[0])?$Subscriberdet[0]->gst_no:'';
        $variecode_no=!empty($Subscriberdet[0])?$Subscriberdet[0]->IECODE:'';
        $package_id=!empty($Subscriberdet[0])?$Subscriberdet[0]->package_id:'';
        $additional_users=!empty($Subscriberdet[0])?$Subscriberdet[0]->additional_users:'';
        $data_storage_limit=!empty($Subscriberdet[0])?$Subscriberdet[0]->data_storage_limit:'';
        $file_storage_limit=!empty($Subscriberdet[0])?$Subscriberdet[0]->file_storage_limit:'';
        $varbusinesstype =!empty($Subscriberdet[0])?$Subscriberdet[0]->businesstype:'';
        $varcntperson = !empty($Subscriberdet[0])?$Subscriberdet[0]->contactperson:'';
        $vardesign = !empty($Subscriberdet[0])?$Subscriberdet[0]->designation:'';
        $varemail_id = !empty($Subscriberdet[0])?$Subscriberdet[0]->email_id:'';
        $varmobno =!empty($Subscriberdet[0])?$Subscriberdet[0]->mobile_no:'';
        $varremarks =!empty($Subscriberdet[0])?$Subscriberdet[0]->remarks:'';
        //newly included to manage supplementary invoice
        $varproformatype=(!empty($varpurchasetype) && ($varpurchasetype=='1' || $varpurchasetype=='2' || $varpurchasetype=='3'))?'NPI':'SPI';
        $varproformastatus=(!empty($varpurchasetype) && ($varpurchasetype=='2' || $varpurchasetype=='3'))?'3':'1'; //3-standby,1-active,2-inactive
        
        if ($Vardraft_status <> '') {
            $ArrData = array('id' => $VarId,'businesstype' => $varbusinesstype, 'contactperson' => $varcntperson,'designation' => $vardesign,'email_id' => $varemail_id,'mobile_no' => $varmobno,'remarks' => $varremarks,'invoice_refno'=>$Varinvoice_no, 'invoice_type' => $Varinvotype,'invoice_validity' => $Varvalidity,'terms_and_condition' => $Varterms,
                             'subscriber_id' => $Varsubsciber_id,'master_proforma_id' => $master_proforma_id,'package_id'=>$package_id,'additional_users'=>$additional_users,'data_storage_limit'=>$data_storage_limit,'file_storage_limit'=>$file_storage_limit,'purchasetype'=>$varpurchasetype,'proforma_type'=>$varproformatype,'status'=>$varproformastatus,'companyname'=>$varcompanyname,'city'=>$varcity,'state'=>$varstate,'country'=>$varcountry,'pincode'=>$varpincode,'address'=>$varaddress,'gst_no'=>$vargst_no,'IECODE'=>$variecode_no,'subscription_period' => $Varsubscription_period,'draft_status'=>$Vardraft_status,'dateupdated' => date('Y-m-d H:i:s')
                            );
            //before change regards above code $ArrData = array('id' => $VarId,'invoice_refno'=>$Varinvoice_no, 'invoice_type' => $Varinvotype,'invoice_validity' => $Varvalidity,'terms_and_condition' => $Varterms,
            //                  'subscriber_id' => $Varsubsciber_id,'subscription_period' => $Varsubscription_period,'draft_status'=>$Vardraft_status,'dateupdated' => date('Y-m-d H:i:s')
            //                 );
            if (empty($VarId)) {
                $ArrData['datecreated'] = date('Y-m-d H:i:s');
            }
            if($Vardraft_status==2){
                $ArrData['invoice_datetime'] = date('Y-m-d H:i:s');
            }
            $ArrResult = ($Vardraft_status==1)?$this->Reqrecivedmodel->saveproformadraftinfo($ArrData,$data):$this->Reqrecivedmodel->saveproformainfo($ArrData,$data);
        } else {
            $ArrResult['errcode'] = -1;
            $ArrResult['msg'] = 'Invalid Input';
        }
        echo json_encode($ArrResult);
    }
    public function getproformacleardraftstatus() 
    {   
        $enquiry_id = intval(xssclean($this->input->post('id')));
        if(!empty($enquiry_id)){
        $ArrResult=$this->Reqrecivedmodel->cleardraft($enquiry_id);
        echo json_encode($ArrResult);
        }else{
           echo false; 
        }
    }
    public function getexsistproforma() 
    {   
        $purchase_type= intval(xssclean($this->input->post('purchase_type')));
        $subscriber_id = intval(xssclean($this->input->post('subscriber_id')));
        if(!empty($subscriber_id)){
        $ArrResult=$this->Reqrecivedmodel->get_renewal_or_pckgmigration_proforma($purchase_type,$subscriber_id);
        echo json_encode($ArrResult);
        }else{
           echo false; 
        }
    }
}