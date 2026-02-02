<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Invoice extends CI_Controller {
    public function __construct() {
        parent::__construct();
        fnIfCheckUserLoggedIn();
        $this->load->model('commonmodel');
        $this->load->model(CNFBADMIN . "Subscribermodel");
        $this->load->model(CNFBADMIN . "Reqrecivedmodel");
        $this->load->model(CNFBADMIN . "Invoicemodel");
         $this->load->model(CNFBADMIN . "subscriptionmodal");
        $this->load->helper('xssclean');
        $ArrUserLoggedInfo = fnGetUserLoggedInfo('1');
        $this->companyid = $ArrUserLoggedInfo['companyid'];
        $this->userid = $ArrUserLoggedInfo['id'];
        $this->limit = LIMITPERPAGE;
        date_default_timezone_set('Asia/Kolkata');
    }
    public function view() {
        $ArrData = array('BasicInfo' => '', 'VarId' => '');
        $VarId = base64_decode(urldecode($this->uri->segment(3)));
        $ArrData['Edit'] = $this->uri->segment(2);
        $ArrPackage = $this->commonmodel->fnGetAllTableInfo(KN_MASTER_PACKAGE  . ' AS p', 'p.id,p.description', array('p.status' => '1', 'p.companyid' => $this->companyid),3);
        $ArrData['ArrPackage'] = $ArrPackage;
        $ArrData['ArrReqStatus'] = unserialize(ARRREQUESTSTATUS);
        $lastURI = $this->uri->segment(2);
       // var_dump($this->uri->segment(2));
       $ArrData['lastURI'] = $lastURI;
        if (is_numeric($VarId)) {
            $ArrResults = $this->Invoicemodel->fnGetInvoiceInfo('', '', $VarId);
            $packinfo=$this->commonmodel->getPackageInfoFromPckg($ArrResults[0]->invopackageid); // already showed based subscriber info package_id
            $ArrResults[0]->no_of_users=!empty($packinfo)?$packinfo->no_of_users:'';
            $ArrResults[0]->data_limit=!empty($packinfo)?$packinfo->data_limit:'';
            $ArrResults[0]->file_limit=!empty($packinfo)?$packinfo->file_limit:'';
            $ArrData['BasicInfo'] = $ArrResults[0];
            //print_r($ArrData['BasicInfo']);
            if(!empty($ArrResults[0]) && $ArrResults[0]->subscription_period==1){
                $subscriptionperiod=3; //Quarterly
            }elseif(!empty($ArrResults[0]) && $ArrResults[0]->subscription_period==2){
                 $subscriptionperiod=6; //Halfyearly
            }elseif(!empty($ArrResults[0]) &&  $ArrResults[0]->subscription_period==3){
                 $subscriptionperiod=12; //Annually
            }else{
                 $subscriptionperiod='0';
            } 
            $ArrData['subscription_period'] = $subscriptionperiod;
            $ArrData['VarId'] = $ArrResults[0]->pid;
            $prefix= date("my");
            $start="JIGME";
            $suffix="S"; 
            $invoprefix= date("m");
            $subscriber_refno =$this->commonmodel->keyGenerateSubscptn(KN_SUBSCRIBERLIST, $prefix,'subscriber_refno',$start,$suffix); 
            $invoiceno =$this->commonmodel->keyGenerateone(KN_PROFORMAINVOICE, $invoprefix,'invoiceno',$start); 
            // print_r($invoiceno);
            // die; 
           // $subscriber_refno =$this->commonmodel->keyGenerateSubscptn_copy(KN_SUBSCRIBERLIST, $prefix,'subscriber_refno');  
            $ArrData['subscriber_refno'] = $subscriber_refno;
            $ArrData['invoiceno'] = $invoiceno;
            $Varsubscriber_id= $ArrResults[0]->subscriber_id;
            $recentsubscrnproforma_id=$this->getActiveProformaId($Varsubscriber_id);
            $subscriptionuserinfo= $this->getsubscriptiondetail(KN_USERS,$recentsubscrnproforma_id);
            $subscriptionpckginfo= $this->getsubscriptiondetail(KN_MNGUSERDEPTCNT,$recentsubscrnproforma_id);
            $subscriptiondeptroleinfo= $this->getsubscriptiondetail(KN_ROLE_PERMISSION_MASTER,$recentsubscrnproforma_id);
            $ArrData['recent_subsrnuserinfo'] =!empty($subscriptionuserinfo)?1:2;
            $ArrData['recent_subsrnpckginfo'] =!empty($subscriptionpckginfo)?1:2;
            $ArrData['recent_subsrndeptroleinfo']=!empty($subscriptiondeptroleinfo)?1:2;
           //print_r($ArrData);
            //file_put_contents()

            //print_r($ArrData);
            file_put_contents("error_log", print_r($ArrData, true));


        } else {
        }
        $this->load->view(CNFBADMIN . 'invoice/view_form', $ArrData);
    }
    public function updateproformainvoiceInfo() {
        $ArrResult = array();
        $Varpayment_from = xssclean($this->input->post('payment_from'));
        $Vartransaction_no = xssclean($this->input->post('transaction_no'));
        $Varpayment_mode = xssclean($this->input->post('payment_mode'));
        $Vartransaction_value = xssclean($this->input->post('transaction_value'));
        $transdate=xssclean($this->input->post('transaction_date'));
        $Vartransaction_date = date('Y-m-d', strtotime($transdate));
        $Varpayment_status = xssclean($this->input->post('payment_status'));
        $Varsave_status = xssclean($this->input->post('save_status'));
        $VarId = xssclean($this->input->post('id')); // proforma_id 
        
        if ($VarId <> '') {
            $this->db->where('id', $VarId);
            $this->db->update(KN_PROFORMAINVOICE,array('save_status'=>$Varsave_status,'payment_from' => $Varpayment_from,'transaction_date' => $Vartransaction_date,'transaction_no' => $Vartransaction_no,'payment_mode'=>$Varpayment_mode,'transaction_value'=>$Vartransaction_value,'paymentstatus'=>$Varpayment_status,'dateupdated' => date('Y-m-d H:i:s')));        
            if ($this->db->affected_rows() == '1') {
               /// $ArrResult["status"] = "success";
                $ArrResult["statusCode"] = "200";
                $ArrResult["msg"] = "updated";
                 $ArrResult['errcode'] = 1;
            }
            else {
               // $ArrResult["status"] = "fail";
                $ArrResult["statusCode"] = "400";
                $ArrResult["msg"] = "notupdated";
                 $ArrResult['errcode'] = -1;
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
        $Varpckdetid =xssclean($this->input->post('pckdetid'));
        $Varpurchtype= trim(xssclean($this->input->post('purchtype')));
        $Varreqraisedby =trim( xssclean($this->input->post('reqraisedby')));
        $Varfromdate =trim( xssclean($this->input->post('fromdate')));
        $Vartodate =xssclean($this->input->post('todate'));
        $clickedColumnId = xssclean($this->input->post('columnId'));
        $newsortorder = xssclean($this->input->post('sortorder'));
        $ArrDbCols = array('companyname','package_id','purchasetype', 'mrkt_dept_userid', 'reqdatetime', 'invoice_refno','invoice_datetime','paymentstatus','dateupdated', 'status'); 
        $ArrStatus = unserialize(PROFARRSTATUS);
        $ArrPaymentStatus = unserialize(ARRPAYMENTSTATUS);
        $ArrPurchasetype = unserialize(ARRPURCHASETYPE);
        $ArrPackage = $this->commonmodel->fnGetAllTableInfo(KN_MASTER_PACKAGE  . ' AS p', 'p.id,p.description', array('p.status' => '1'),3);
        $checkDraftorNot= $this->Subscribermodel->getdraftdata();
        if ($VarFrom == 1) {
            $VarURLSegment = 5;
            $this->load->library('pagination');
            $config['base_url'] = base_url() . CNFBADMIN . 'invoice/manage/';
            $config['total_rows'] = $this->Invoicemodel->fnCount($Varcmpny, $Varpckdetid,$Varpurchtype,$Varreqraisedby,$Varfromdate,$Vartodate);
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
            $ArrList = $this->Invoicemodel->fnList($Varcmpny,$Varpckdetid,$Varpurchtype,$Varreqraisedby,$Varfromdate,$Vartodate, $this->limit, $offset, $sortby, $sortorder)->result();
            $data['pagination'] = $this->pagination->create_linkswithajax('Invoice');
            $i = 0;
            $ArrFnlList = array();

            foreach ($ArrList as $Obj) {
                $ArrFnlList[$i]['id'] = $Obj->id;
                $ArrFnlList[$i]['cmpny'] = $Obj->companyname;
                $ArrFnlList[$i]['pckdet'] = !empty($Obj->package_id)? $this->commonmodel->getPackageInfoFromPckg($Obj->package_id)->description:'';
                $ArrFnlList[$i]['purchtype'] = isset($Obj->purchasetype)?$ArrPurchasetype[$Obj->purchasetype]:'';
                $ArrFnlList[$i]['reqraisedby'] = $Obj->request_raised_by;
                $ArrFnlList[$i]['reqdatetime'] = $Obj->reqdatetime;
                $ArrFnlList[$i]['invoice_refno'] = $Obj->invoice_refno;
                $ArrFnlList[$i]['invoice_datetime'] = $Obj->invoice_datetime;
                $ArrFnlList[$i]['paymentstatus'] = isset($Obj->paymentstatus)?$ArrPaymentStatus[$Obj->paymentstatus]:'';
                $ArrFnlList[$i]['recent_updated_datetime'] = $Obj->recent_updated_datetime;
                $ArrFnlList[$i]['s'] = $ArrStatus[$Obj->status];
                $i = $i + 1;
            }
           
            
            echo json_encode(array('errcode' => 1, 'cn' => $config['total_rows'], 'ct' => $i, 're' => $ArrFnlList, 'pa' => base64_encode($data['pagination'])));
            unset($ArrFnlList); 
            die;
        } else {
            $this->load->view(CNFBADMIN . 'invoice/proformainvo_list', array('ArrStatus' => $ArrStatus,'ArrPackage'=>$ArrPackage,'ArrPaymentStatus'=>$ArrPaymentStatus,'checkDraftorNot'=>$checkDraftorNot,'ArrPurchasetype'=>$ArrPurchasetype));
        }
    }

    function changemStatus() {
        $VarType = xssclean($this->input->post('type'));
        $VarCid = xssclean($this->input->post('cid'));
        if ($VarType <> '' && $VarCid <> '') {
            $this->Invoicemodel->fnChangeComStat($VarCid, $VarType);
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
    public function proformainv() {
        $VarId = base64_decode(urldecode($this->uri->segment(3)));
        $ArrData = array('BasicInfo' => '', 'VarId' => '');
        $ArrData['lastURI'] = $VarId;
        $purchase_type = unserialize(ARRPURCHASETYPE);
        
        if (is_numeric($VarId)) {
            $ArrResults = $this->Invoicemodel->fnGetInvoiceInfo('', '', $VarId);
            $draftrslt= $this->Reqrecivedmodel->getdraftdata();
            $ArrData['BasicInfo'] = $ArrResults[0];
            $ArrData['amount']=!empty($ArrResults[0])?(($ArrResults[0]->subtotal!='0.00')?$this->commonmodel->getIndianCurrency($ArrResults[0]->subtotal).'only.':''):'';
            $ArrData['purchase_type']=!empty($ArrResults[0])?$purchase_type[$ArrResults[0]->invopurchasetype]:'';
           // $ArrData['packagedet']=!empty($ArrResults[0])?$this->commonmodel->getPackageInfoFromPckg($ArrResults[0]->package_id)->description:'';
            $ArrData['SubscriberId'] = $ArrResults[0]->subscriber_id;
            $ArrData['VarId'] = !empty($ArrResults[0])?$ArrResults[0]->pid:''; 
        } else {
        }
        $this->load->view(CNFBADMIN . 'invoice/proforma_invoice',$ArrData);
    }
    public function subproformainv() {
        $VarId = base64_decode(urldecode($this->uri->segment(3)));
        $ArrData = array('BasicInfo' => '', 'VarId' => '');
        $ArrData['lastURI'] = $VarId;
        $purchase_type = unserialize(ARRPURCHASETYPE);
        
        if (is_numeric($VarId)) {
            $ArrResults = $this->Invoicemodel->fnGetInvoiceInfo('', '', $VarId);
            $draftrslt= $this->Reqrecivedmodel->getdraftdata();
            $ArrData['BasicInfo'] = $ArrResults[0];
            $ArrData['amount']=!empty($ArrResults[0])?(($ArrResults[0]->subtotal!='0.00')?$this->commonmodel->getIndianCurrency($ArrResults[0]->subtotal).'only.':''):'';
            $ArrData['purchase_type']=!empty($ArrResults[0])?$purchase_type[$ArrResults[0]->invopurchasetype]:'';
           // $ArrData['packagedet']=!empty($ArrResults[0])?$this->commonmodel->getPackageInfoFromPckg($ArrResults[0]->package_id)->description:'';
            $ArrData['SubscriberId'] = $ArrResults[0]->subscriber_id;
            $ArrData['VarId'] = !empty($ArrResults[0])?$ArrResults[0]->pid:''; 
// print_r($ArrData);
// die;
            
        } else {
        }
        $this->load->view(CNFBADMIN . 'invoice/subscriber_proforma_invoice',$ArrData);
    }
    public function getquotedetails_igst()
    {
        $enqId = xssclean($this->input->post('enquiry_id'));
        $invotype= xssclean($this->input->post('invotype'));
        $data  = $this->Reqrecivedmodel->getQuotedetailsGrid_igst($enqId,$invotype);
        echo json_encode($data);
    }
    public function getquotedetails_cgst_sgst()
    {
        $enqId = xssclean($this->input->post('enquiry_id'));
        $invotype = xssclean($this->input->post('invotype'));
        $data  = $this->Reqrecivedmodel->getQuotedetailsGrid_cgst_sgst($enqId,$invotype);
        echo json_encode($data);
    }
    public function updateproformaInfo() {
        $ArrResult = array();
        $Varinvotype = xssclean($this->input->post('invotype'));
        $Varvalidity = xssclean($this->input->post('validity'));
        $Varterms = xssclean($this->input->post('terms'));
        $Vardraft_status = xssclean($this->input->post('draftstatus'));
        $Varsubsciber_id = xssclean($this->input->post('subsciber_id'));
        $VarId = xssclean($this->input->post('id'));
        $object = xssclean($this->input->post('object'));
        $data   = json_decode($object);
        $Varinvoice_no = ($Vardraft_status==2)?(xssclean($this->input->post('invoice_no'))):'';
        
        
        if ($Vardraft_status <> '') {
            $ArrData = array('id' => $VarId,'invoice_refno'=>$Varinvoice_no, 'invoice_type' => $Varinvotype,'invoice_validity' => $Varvalidity,'terms_and_condition' => $Varterms,
                             'subscriber_id' => $Varsubsciber_id,'draft_status'=>$Vardraft_status,'dateupdated' => date('Y-m-d H:i:s')
                            );
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
    public function saveSubscriptionInfo() {
        $ArrResult = array();
        $Varsubscriber_id= xssclean($this->input->post('subscriber_id'));
        $Varsubscriber_refno = xssclean($this->input->post('subscriber_refno'));
        $Varstart_date = date('Y-m-d');
        $Varsubscription_period=xssclean($this->input->post('subscription_period'));
        $Varend_date=date('Y-m-d', strtotime("+$Varsubscription_period months -1 days"));
        $VarId = xssclean($this->input->post('id'));
        $Varproforma_id = xssclean($this->input->post('proforma_id'));
        $daysleftrenewal=$this->commonmodel->getnoofdays($Varstart_date,$Varend_date);
        $Varinvoiceno = xssclean($this->input->post('invoiceno'));
        $Varproformatype = xssclean($this->input->post('proforma_type'));
        $Varinvopurchasetype= xssclean($this->input->post('invopurchasetype'));
        if ($VarId <> '') {
            $this->db->where('id', $VarId);
            $this->db->update(KN_PROFORMAINVOICE,array('invoice_status'=>2,'invoiceno'=>$Varinvoiceno,'invoicegen_date'=> date('Y-m-d'),'dateupdated' => date('Y-m-d H:i:s')));    

            
            if ($this->db->affected_rows() == '1') {
                if($Varproformatype=='NPI' && $Varinvopurchasetype!=2 && $Varinvopurchasetype!=3 ){
                    $this->db->insert(KN_SUBSCRIBERLIST, [
                            'subscriber_id' => $Varsubscriber_id,
                            'proforma_id' => $Varproforma_id,
                            'subscriber_refno' => $Varsubscriber_refno,
                            'subscriber_startdate' => $Varstart_date,
                            'subscriber_enddate' => $Varend_date,
                            'renewal_daysleft' =>$daysleftrenewal,
                            'datecreated' => date('Y-m-d H:i:s'),
                            'status'=>2,
                            'dateupdated' => date('Y-m-d H:i:s')
                        ]);
                }elseif ($Varproformatype === 'NPI' && in_array($Varinvopurchasetype, [2, 3])) {
                        $this->processProforma($VarId, $Varsubscriber_id, $Varinvopurchasetype, $Varproformatype);
                }else if ($Varproformatype === 'SPI') {
                    $query  = "SELECT *
                    FROM " . KN_PROFORMAINVOICE . " AS pi
                    WHERE pi.id = $Varproforma_id";
                    $supplementary_info   = $this->db->query($query)->row();
                    $masterproforma_id=$supplementary_info->master_proforma_id;
                    if(!empty($masterproforma_id)){
                        $this->db->where('id', $masterproforma_id);
                        $this->db->update(KN_PROFORMAINVOICE,array('confirm_status'=>2,'dateupdated' => date('Y-m-d H:i:s')));    
            
                    }
                }               
               /// $ArrResult["status"] = "success";
                $ArrResult["statusCode"] = "200";
                $ArrResult["msg"] = "updated";
                 $ArrResult['errcode'] = 1;
            }
            else {
               // $ArrResult["status"] = "fail";
                $ArrResult["statusCode"] = "400";
                $ArrResult["msg"] = "notupdated";
                 $ArrResult['errcode'] = -1;
            }
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
    public function info() 
    {   $ArrData = array('BasicInfo' => '', 'VarId' => '');
         $this->load->view(CNFBADMIN . 'info', $ArrData);
    }
     public  function csgstpdf()
 {
  $VarId = $this->uri->segment(3);
 
        $ArrData = array('BasicInfo' => '', 'VarId' => '');
        $ArrData['lastURI'] = $VarId;
        $purchase_type = unserialize(ARRPURCHASETYPE);
        
        if (is_numeric($VarId)) {
            $ArrResults = $this->Invoicemodel->fnGetInvoiceInfo('', '', $VarId);
            $ArrDetResults = $this->Invoicemodel->getproformadetails($VarId);
            //$draftrslt= $this->Reqrecivedmodel->getdraftdata();
            $ArrData['BasicInfo'] = $ArrResults[0];
            $ArrData['amount']=!empty($ArrResults[0])?(($ArrResults[0]->subtotal!='0.00')?$this->commonmodel->getIndianCurrency($ArrResults[0]->subtotal).'only.':''):'';
            $ArrData['purchase_type']=!empty($ArrResults[0])?$purchase_type[$ArrResults[0]->invopurchasetype]:'';
           // $ArrData['packagedet']=!empty($ArrResults[0])?$this->commonmodel->getPackageInfoFromPckg($ArrResults[0]->package_id)->description:'';
            $ArrData['SubscriberId'] = $ArrResults[0]->subscriber_id;
            $ArrData['VarId'] = !empty($ArrResults[0])?$ArrResults[0]->pid:''; 
            $ArrData['ArrDetResults'] = !empty($ArrDetResults[0])?$ArrDetResults:''; 

         //print_r($ArrDetResults);
        }
      
        if($ArrResults[0]->invoice_type=='within'){
             $this->load->view(CNFBADMIN .'invoice/csgst_invoice_pdf',$ArrData);
        }else{
             $this->load->view(CNFBADMIN .'invoice/invoice_pdf',$ArrData);
        }

        
        // Get output html
        $html = $this->output->get_output();
       // $html='<h1>Welcome to CodexWorld.com</h1>';
        // Load pdf library
        $this->load->library('Dompdfs');
        
        // Load HTML content
        $this->dompdf->loadHtml($html);
        
        // (Optional) Setup the paper size and orientation
        $this->dompdf->setPaper('A4', 'landscape');
        
        // Render the HTML as PDF
        $this->dompdf->render();
        
        // Output the generated PDF (1 = download and 0 = preview)
        $this->dompdf->stream("proformainvoice.pdf", array("Attachment"=>0));
      }

       public  function csgstpprint()
 {
  $VarId = $this->uri->segment(3);
 
        $ArrData = array('BasicInfo' => '', 'VarId' => '');
        $ArrData['lastURI'] = $VarId;
        $purchase_type = unserialize(ARRPURCHASETYPE);
        
        if (is_numeric($VarId)) {
            $ArrResults = $this->Invoicemodel->fnGetInvoiceInfo('', '', $VarId);
            $ArrDetResults = $this->Invoicemodel->getproformadetails($VarId);
            //$draftrslt= $this->Reqrecivedmodel->getdraftdata();
            $ArrData['BasicInfo'] = $ArrResults[0];
            $ArrData['amount']=!empty($ArrResults[0])?(($ArrResults[0]->subtotal!='0.00')?$this->commonmodel->getIndianCurrency($ArrResults[0]->subtotal).'only.':''):'';
            $ArrData['purchase_type']=!empty($ArrResults[0])?$purchase_type[$ArrResults[0]->invopurchasetype]:'';
           // $ArrData['packagedet']=!empty($ArrResults[0])?$this->commonmodel->getPackageInfoFromPckg($ArrResults[0]->package_id)->description:'';
            $ArrData['SubscriberId'] = $ArrResults[0]->subscriber_id;
            $ArrData['VarId'] = !empty($ArrResults[0])?$ArrResults[0]->pid:''; 
            $ArrData['ArrDetResults'] = !empty($ArrDetResults[0])?$ArrDetResults:''; 
        }
      
        if($ArrResults[0]->invoice_type=='within'){
             $this->load->view(CNFBADMIN .'invoice/csgst_invoice_pdf',$ArrData);
        }else{
             $this->load->view(CNFBADMIN .'invoice/invoice_pdf',$ArrData);
        }

        
    //     // Get output html
    //     $html = $this->output->get_output();
    //    // $html='<h1>Welcome to CodexWorld.com</h1>';
    //     // Load pdf library
    //     $this->load->library('Dompdfs');
        
    //     // Load HTML content
    //     $this->dompdf->loadHtml($html);
        
    //     // (Optional) Setup the paper size and orientation
    //     $this->dompdf->setPaper('A4', 'landscape');
        
    //     // Render the HTML as PDF
    //     $this->dompdf->render();
        
    //     // Output the generated PDF (1 = download and 0 = preview)
    //     $this->dompdf->stream("proformainvoice.pdf", array("Attachment"=>0));
      }

     public  function raiseivopdf()
 {
  
 $this->load->view(CNFBADMIN .'invoice/invoice_pdf');
        
        // Get output html
        $html = $this->output->get_output();
       // $html='<h1>Welcome to CodexWorld.com</h1>';
        // Load pdf library
        $this->load->library('Dompdfs');
        
        // Load HTML content
        $this->dompdf->loadHtml($html);
        
        // (Optional) Setup the paper size and orientation
        $this->dompdf->setPaper('A4', 'landscape');
        
        // Render the HTML as PDF
        $this->dompdf->render();
        
        // Output the generated PDF (1 = download and 0 = preview)
        $this->dompdf->stream("welcome.pdf", array("Attachment"=>0));
      }
  public  function invopdf()
 {
$this->load->library('Pdf');
$pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);
$pdf->SetTitle('Pdf Example');
$pdf->SetHeaderMargin(30);
$pdf->SetTopMargin(20);
$pdf->setFooterMargin(20);
$pdf->SetAutoPageBreak(true);
$pdf->SetAuthor('Author');
$pdf->SetDisplayMode('real', 'default');
$pdf->Write(5, 'CodeIgniter TCPDF Integration');
$pdf->Output('pdfexample.pdf', 'I');
      }
public function sss(){
    $trowss='';
for ($i=1;$i<3;$i++){
   $trowss .="<tr><td></td><td></td>"

               . "<td></td>"   

               . "<td></td>" 

               . "<td></td>"
               
               . "<td></td>"   

               . "<td></td>" 

               . "<td></td>"
               
               . "<td></td>"
               
               . "</tr>" ;
}
$html = <<<EOF
<table cellspacing="0" cellpadding="4"  border="1">
 <tr>
        <td align="" >
           <b style="font-size:150%;" >Jigme Soft Solutions Private Limited </b> <br>  
           No.88, Block - B, Bose Garden Layout, Saravanampatti, Coimbatore - 641035.<br> 
           Mobile: 9943931113, E-mail: jigmesoft@gmail.com,<br> 
           GST NO. :33AAFCJ2474F1ZR</td>
         </tr>
</table>
<table cellspacing="0" cellpadding="4" border="0">
        <tr>
         <td align="center" style="border-bottom:1px solid #151515;font-size:18px"><b>Proforma Invoice</b></td>
         </tr>
       </table>
<table style="padding:5px;" border="0">
        <tr>
        <td width="333"> To  </td>
        <td width="340" align="center">  Proforma Reference  </td>
        </tr>
</table>
<table cellspacing="0" cellpadding="4" border="1">
        
        <tr>
        <td  width="333" style="border-left:1px solid #151515;border-right:1px solid #151515;" align="left"><span>Name</span> <span  width="30" align="left">&nbsp;&nbsp;&nbsp;:</span></td>
        <td  width="340" style="border-left:1px solid #151515;border-right:1px solid #151515;" align="left"><span width="70" align="left">Prof. Invoice. Ref. No</span> <span  width="30"   align="left">:</span></td>

       </tr>
       <tr>
        <td  width="333" style="border-left:1px solid #151515;border-right:1px solid #151515;" align="left"><span>Address</span> <span  width="25" align="left">:</span> safasf</td>
        <td  width="340" style="border-left:1px solid #151515;border-right:1px solid #151515;" align="left"><span width="70" align="left">Date & Time</span> <span  width="30" align="left">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:</span></td>

       </tr>
       <tr>
        <td  width="333"  style="border-left:1px solid #151515;border-right:1px solid #151515;" align="left"><span style="color:#ffffff;">GST No</span> <span  width="25" style="color:#ffffff;" align="left">:</span>asfas</td>
        <td  width="340" style="border-left:1px solid #151515;border-right:1px solid #151515;" align="left"><span width="70" align="left">Prof. Invoice. Validity</span> <span  width="30" align="left">:</span></td>

       </tr>
        <tr>
        <td  width="333"  style="border-left:1px solid #151515;border-right:1px solid #151515;" align="left"><span style="color:#ffffff;">GST No</span> <span  width="25"   style="color:#ffffff;" align="left">:</span>saffsa</td>
        <td  width="340" style="border-left:1px solid #151515;border-right:1px solid #151515;" align="left"><span width="70" align="left">Subscription Period</span> <span  width="25" align="left">&nbsp;&nbsp;&nbsp;&nbsp;:</span></td>

       </tr>
        <tr>
        <td  width="333"  style="border-left:1px solid #151515;border-right:1px solid #151515;" align="left"><span width="70" align="left">GST No</span> <span  width="25"   align="left">:</span></td>
        <td  width="340" style="border-left:1px solid #151515;border-right:1px solid #151515;" align="left"><span width="70" align="left">Purchase Type</span> <span  width="25"   align="left">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:</span></td>
       </tr>
       
</table>
<table cellspacing="0" cellpadding="4" border="0">
    <tr>
        <td align="left" >Herewith, we quote for the following services:</td>
    </tr>
</table>
<table cellspacing="0" cellpadding="4" border="1">
      
       <tr>
       <td width="35" align="center">S.No</td>
       <td width="180" align="center">Description</td>
       <td width="151" align="center">Details</td>
       <td width="50" align="center">Unit Rate (Rs)</td>
       <td width="40" align="center">Qty. (Nos)</td>
       <td width="58" align="center">Amt. (Rs)</td>
       <td width="40" align="center">IGST (%)</td>
       <td width="60" align="center">IGST Value (Rs)</td>
       <td width="60" align="center">Sub Total (Rs)</td>
      </tr>
      
      {$trowss}
      
        <tr>
        <td width="494" ></td>
        <td width="55" align="right"><b>Total:</b> </td>
        <td width="60" align="right"><b></b> </td>
        <td width="60" align="right" ><b></b> </td>
        </tr>
        
        <tr>
        <td width="674" ></td>
        </tr>
        
        <tr>
        <td width="480" align="center"></td>
        <td width="135" align="center"><b>Tax-Percentage</b></td>
        <td width="59" align="center"><b>Amount</b></td>
        </tr>

        <tr>
        <td width="545" align="right">CGST: </td>
        <td width="65" align="right"></td>
        <td width="64" align="right"></td>
        </tr>

        <tr>
        <td width="545" align="right">SGST: </td>
        <td width="65" align="right"></td>
        <td width="64" align="right"></td>
        </tr>

        <tr>
        <td width="545" align="right">IGST: </td>
        <td width="65" align="right"></td>
        <td width="64" align="right"></td>
        </tr>
        
        <tr>
        <td width="610" align="right"><b>Final Amount</b></td>
        <td width="64" align="right"><b></b></td>
        </tr>
        
       </table>
EOF;


$this->load->library('Pdf');
$tcpdf = new Pdf(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set default monospaced font
$tcpdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set title of pdf
$tcpdf->SetTitle('Proforma Invoice');

// set margins
$tcpdf->SetMargins(10, 10, 10, 10);
$tcpdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$tcpdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// set header and footer in pdf
$tcpdf->setPrintHeader(false);
$tcpdf->setPrintFooter(false);
$tcpdf->setListIndentWidth(3);
// set auto page breaks
$tcpdf->SetAutoPageBreak(TRUE, 11);

// set image scale factor
$tcpdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

$tcpdf->AddPage();

$tcpdf->SetFont('times', '', 10.5);

$tcpdf->writeHTML($html, true, false, true, false, '');

//Close and output PDF document
$tcpdf->Output('demo.pdf', 'I');
}
public function ss(){
$trowss='';
for($i=1;$i<3;$i++){

           $trowss .="<tr><td></td><td></td>"   

               . "<td></td>" 

               . "<td></td>"

               . "<td></td>"

               . "<td></td></tr>" ;
       }
$pdf = '
<table cellspacing="0" cellpadding="4"  border="1">
 <tr>
        <td align="" >
           <b style="font-size:150%;" >Jigme Soft Solutions Private Limited </b> <br>  
           No.88, Block - B, Bose Garden Layout, Saravanampatti, Coimbatore - 641035.<br> 
           Mobile: 9943931113, E-mail: jigmesoft@gmail.com,<br> 
           GST NO. :33AAFCJ2474F1ZR</td>
         </tr>
</table>

';
$pdf .= '
<table cellspacing="0" cellpadding="4" border="0">
        <tr>
         <td align="center" style="border-bottom:1px solid #151515;font-size:18px"><b>Proforma Invoice</b></td>
         </tr>
       </table>
<table style="padding:5px;" border="0">
 <tr>
        <td width="333"> To  </td>
        <td width="340" align="center">  Proforma Reference  </td>
        </tr>
</table>
';
$pdf .= '
<table cellspacing="0" cellpadding="4" border="1">
        <tr>
        <td  width="333" style="border-left:1px solid #151515;border-right:1px solid #151515;" align="left"><span>Name</span> <span  width="30" align="left">&nbsp;&nbsp;&nbsp;:</span></td>
        <td  width="340" style="border-left:1px solid #151515;border-right:1px solid #151515;" align="left"><span width="70" align="left">Prof. Invoice. Ref. No</span> <span  width="30"   align="left">:</span></td>

       </tr>
       <tr>
        <td  width="333" style="border-left:1px solid #151515;border-right:1px solid #151515;" align="left"><span>Address</span> <span  width="25" align="left">:</span> safasf</td>
        <td  width="340" style="border-left:1px solid #151515;border-right:1px solid #151515;" align="left"><span width="70" align="left">Date & Time</span> <span  width="30" align="left">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:</span></td>

       </tr>
       <tr>
        <td  width="333"  style="border-left:1px solid #151515;border-right:1px solid #151515;" align="left"><span style="color:#ffffff;">GST No</span> <span  width="25" style="color:#ffffff;" align="left">:</span>asfas</td>
        <td  width="340" style="border-left:1px solid #151515;border-right:1px solid #151515;" align="left"><span width="70" align="left">Prof. Invoice. Validity</span> <span  width="30" align="left">:</span></td>

       </tr>
        <tr>
        <td  width="333"  style="border-left:1px solid #151515;border-right:1px solid #151515;" align="left"><span style="color:#ffffff;">GST No</span> <span  width="25"   style="color:#ffffff;" align="left">:</span>saffsa</td>
        <td  width="340" style="border-left:1px solid #151515;border-right:1px solid #151515;" align="left"><span width="70" align="left">Subscription Period</span> <span  width="25" align="left">&nbsp;&nbsp;&nbsp;&nbsp;:</span></td>

       </tr>
        <tr>
        <td  width="333"  style="border-left:1px solid #151515;border-right:1px solid #151515;" align="left"><span width="70" align="left">GST No</span> <span  width="25"   align="left">:</span></td>
        <td  width="340" style="border-left:1px solid #151515;border-right:1px solid #151515;" align="left"><span width="70" align="left">Purchase Type</span> <span  width="25"   align="left">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:</span></td>

       </tr>
       
</table>
<table cellspacing="0" cellpadding="4" border="0">
        <tr>
           <td align="left" >
           Herewith, we quote for the following services:</td>
         </tr>
       </table>
<table cellspacing="0" cellpadding="4" border="1">
       <tr>
       <td width="35" align="center">S.No</td>

       <td width="230" align="center">Description</td>

       <td width="215" align="center">Details</td>

       <td width="69" align="center">Unit Rate(Rs)</td>

       <td width="65" align="center">Qty.(Nos)</td>

       <td width="60" align="center">Amt.(Rs)</td>

      </tr>
     
      '.$trowss.'
        <tr>
        <td width="549" ></td>

        <td width="65" align="right"><b>Total:</b> </td>

        <td width="60" align="right" ><b></b> </td>

        </tr>

        

        <tr>

        <td width="674" ></td>

        </tr>

        

        <tr>

        <td width="480" align="center"></td>

        <td width="135" align="center"><b>Tax-Percentage</b></td>

        <td width="59" align="center"><b>Amount</b></td>

        </tr>

        <tr>

        <td width="545" align="right">CGST: </td>

        <td width="65" align="right"></td>

        <td width="64" align="right"></td>

        </tr>

        <tr>

        <td width="545" align="right">SGST: </td>

        <td width="65" align="right"></td>

        <td width="64" align="right"></td>

        </tr>

        <tr>

        <td width="545" align="right">IGST: </td>

        <td width="65" align="right"></td>

        <td width="64" align="right"></td>

        </tr>
        <tr>
        <td width="610" align="right"><b>Final Amount</b></td>

        <td width="64" align="right"><b></b></td>
        </tr>
       </table>
';
$this->load->library('Pdf');
//$tcpdf = new Pdf(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$tcpdf = new Pdf('L', 'mm', 'A4', true, 'UTF-8', false);
// set default monospaced font
$tcpdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set title of pdf
$tcpdf->SetTitle('Proforma Invoice');

// set margins
$tcpdf->SetMargins(10, 10, 10, 10);
$tcpdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$tcpdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// set header and footer in pdf
$tcpdf->setPrintHeader(false);
$tcpdf->setPrintFooter(false);
$tcpdf->setListIndentWidth(3);
// set auto page breaks
$tcpdf->SetAutoPageBreak(TRUE, 11);

// set image scale factor
$tcpdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

$tcpdf->AddPage();

$tcpdf->SetFont('times', '', 10.5);

$tcpdf->writeHTML($pdf, true, false, false, false, '');

//Close and output PDF document
$tcpdf->Output('demo.pdf', 'I');
}
public  function samplepdf()
 {
$this->load->library('Pdf');
//$pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);

ob_start(); // at the beggining of your script 

// create new PDF document

$pdf = new Pdf(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information

$pdf->SetCreator(PDF_CREATOR);

$pdf->SetAuthor('Sreenidhi Enterprises');

$report_title = "Invoice";

$pdf->SetTitle($report_title);

$pdf->SetSubject('');

$pdf->SetKeywords('Quotation,Invoice');

//$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.'.', PDF_HEADER_STRING, array(0,64,255), array(0,64,128));

$pdf->setFooterData(array(0,64,0), array(0,64,128));

// set header and footer fonts

$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));

$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font

$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins

$pdf->SetMargins(10, 30, 10);

//$pdf->SetHeaderMargin(10);


//$pdf->SetFooterMargin(10);

// set auto page breaks

$pdf->SetAutoPageBreak(TRUE, 13);

// set image scale factor

$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

$pdf->setFontSubsetting(true);
$pdf->SetFont('Helvetica', 'Italic', 10, '', true);
$pdf->AddPage();
$pageWidth = 200;
$pageHeight = 283;
//$margin = 11;
// Line
$pdf->Line(11, 284, 199, 284, '');
$trows = '';
$html = <<<EOD
        <table cellspacing="0" cellpadding="4" border="1">
        <tr>
        <td align="" >
           <b style="font-size:150%;" >Jigme Soft Solutions Private Limited </b> <br>  
           No.88, Block - B, Bose Garden Layout, Saravanampatti, Coimbatore - 641035.<br> 
           Mobile: 9943931113, E-mail: jigmesoft@gmail.com,<br> 
           GST NO. :33AAFCJ2474F1ZR</td>
         </tr>
        
        <tr>
        <td width="333">  DC NO. : <b></b> <br>  </td>
        <td width="332">  Date : <b></b>  <br></td>
        </tr>
        <tr>
        <td width="665" > TO : <b>   </b> <br></td>
        </tr>
       <tr>
       <td width="50" align="center"><b>S.No</b></td>
       <td width="350" align="center"><b>Description </b></td>
       <td width="67" align="center"><b>HSN</b></td>
       <td width="99" align="center"> <b>Quantity</b></td>
       <td width="99" align="center"><b>Value</b></td>
       </tr>
        <tr>
        <td width="467" ></td>
        <td width="99" align="right"><b>Total:</b> </td>
        <td width="99" align="right" ><b></b> </td>
        </tr>
        <tr>
        <td width="665" ></td>
        </tr>
        <tr>
        <td width="467" align="center"></td>
        <td width="132" align="center"><b>Tax-Percentage</b></td>
        <td width="66" align="center"><b>Amount</b></td>
        </tr>
        <tr>
        <td width="533" align="right">CGST: </td>
        <td width="66" align="right"></td>
        <td width="66" align="right"></td>
        </tr>
        <tr>
        <td width="533" align="right">SGST: </td>
        <td width="66" align="right"></td>
        <td width="66" align="right"></td>
        </tr>
        <tr>
        <td width="665" align="left"> <br> Returnable (or) Non-Returnable : <b>  </b>  -- For Kosh Innovation <br> <br><br> </td>
        </tr>
        <tr>
        <td width="333" align="left" >   Receiver's Signature <br><br> </td>
        <td width="332" align="right"> <br> Authorised Signatory <br><br></td>
        </tr>
       </table>
EOD;

$pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);
$htl = ob_get_clean();
$htl='
<table cellspacing="" cellpadding="1">
       <tr>
           <td>
            <p style="text-indent: 50px;">*This is computer generated Invoice, hence no signature required.</p><p></p>
           </td>
       </tr> 
   </table>';
$pdf->writeHTMLCell(0, 0, '', '', $htl, 0, 1, 0, true, '', true); 
ob_end_clean();// at the end of your script

$pdf->Output('sampleexample.pdf', 'I');

      }
public  function pdfs()
 {
  $VarId = $this->uri->segment(3);
 
        $ArrData = array('BasicInfo' => '', 'VarId' => '');
        $ArrData['lastURI'] = $VarId;
        $purchase_type = unserialize(ARRPURCHASETYPE);
        
        if (is_numeric($VarId)) {
            $ArrResults = $this->subscriptionmodal->fnGetInvoiceInfo('', '', $VarId);
            $ArrDetResults = $this->Invoicemodel->getproformadetails($VarId);
            //$draftrslt= $this->Reqrecivedmodel->getdraftdata();
            $ArrData['BasicInfo'] = $ArrResults[0];
            $ArrData['amount']=!empty($ArrResults[0])?(($ArrResults[0]->subtotal!='0.00')?$this->commonmodel->getIndianCurrency($ArrResults[0]->subtotal).'only.':''):'';
            $ArrData['purchase_type']=!empty($ArrResults[0])?$purchase_type[$ArrResults[0]->invopurchasetype]:'';
           // $ArrData['packagedet']=!empty($ArrResults[0])?$this->commonmodel->getPackageInfoFromPckg($ArrResults[0]->package_id)->description:'';
            $ArrData['SubscriberId'] = $ArrResults[0]->subscriber_id;
            $ArrData['VarId'] = !empty($ArrResults[0])?$ArrResults[0]->pid:''; 
            $ArrData['ArrDetResults'] = !empty($ArrDetResults[0])?$ArrDetResults:''; 
        }
      
        if($ArrResults[0]->invoice_type=='within'){
             $this->load->view(CNFBADMIN .'invoice/invoice_cgst_pdf',$ArrData);
        }else{
             $this->load->view(CNFBADMIN .'invoice/invoice_igst_pdf',$ArrData);
        }

        
        // Get output html
        $html = $this->output->get_output();
       // $html='<h1>Welcome to CodexWorld.com</h1>';
        // Load pdf library
        $this->load->library('Dompdfs');
        
        // Load HTML content
        $this->dompdf->loadHtml($html);
        
        // (Optional) Setup the paper size and orientation
        $this->dompdf->setPaper('A4', 'landscape');
        
        // Render the HTML as PDF
        $this->dompdf->render();
        
        // Output the generated PDF (1 = download and 0 = preview)
        $this->dompdf->stream("invoice.pdf", array("Attachment"=>0));
      }
      public  function printpdfs()
 {
  $VarId = $this->uri->segment(3);
 
        $ArrData = array('BasicInfo' => '', 'VarId' => '');
        $ArrData['lastURI'] = $VarId;
        $purchase_type = unserialize(ARRPURCHASETYPE);
        
        if (is_numeric($VarId)) {
            $ArrResults = $this->subscriptionmodal->fnGetInvoiceInfo('', '', $VarId);
            $ArrDetResults = $this->Invoicemodel->getproformadetails($VarId);
            //$draftrslt= $this->Reqrecivedmodel->getdraftdata();
            $ArrData['BasicInfo'] = $ArrResults[0];
            $ArrData['amount']=!empty($ArrResults[0])?(($ArrResults[0]->subtotal!='0.00')?$this->commonmodel->getIndianCurrency($ArrResults[0]->subtotal).'only.':''):'';
            $ArrData['purchase_type']=!empty($ArrResults[0])?$purchase_type[$ArrResults[0]->invopurchasetype]:'';
           // $ArrData['packagedet']=!empty($ArrResults[0])?$this->commonmodel->getPackageInfoFromPckg($ArrResults[0]->package_id)->description:'';
            $ArrData['SubscriberId'] = $ArrResults[0]->subscriber_id;
            $ArrData['VarId'] = !empty($ArrResults[0])?$ArrResults[0]->pid:''; 
            $ArrData['ArrDetResults'] = !empty($ArrDetResults[0])?$ArrDetResults:''; 
        }
      
        if($ArrResults[0]->invoice_type=='within'){
             $this->load->view(CNFBADMIN .'invoice/invoice_cgst_pdf',$ArrData);
        }else{
             $this->load->view(CNFBADMIN .'invoice/invoice_igst_pdf',$ArrData);
        }

       
      }
public function getproformadet() 
    {   
        $proforma_id = intval(xssclean($this->input->post('proforma_id')));
        if(!empty($proforma_id)){
        $ArrDetResults = $this->Invoicemodel->getproformadetails($proforma_id);
        echo json_encode($ArrDetResults);
        }else{
           echo false; 
        }
    }
    
// Process Proforma Data Cloning
public function processProforma($VarId, $Varsubscriber_id, $proforma_purchasetype, $proforma_type) {
    if (in_array($proforma_purchasetype, [2, 3]) && $proforma_type === 'NPI') {
        $activeproforma_id = $this->getActiveProformaId($Varsubscriber_id);

        if ($activeproforma_id) {
            $this->cloneTableRecords(KN_MNGUSERDEPTCNT, $VarId, $Varsubscriber_id, $activeproforma_id);
            $this->cloneTableRecords(KN_DEPTROLE_PERMISSION, $VarId, $Varsubscriber_id, $activeproforma_id);
            $this->cloneTableRecords(KN_ROLE_PERMISSION_MASTER, $VarId, $Varsubscriber_id, $activeproforma_id);
            $this->cloneUsers($VarId, $Varsubscriber_id, $activeproforma_id);
        }
    }
}

// Get Active Proforma ID
private function getActiveProformaId($Varsubscriber_id) {
    return $this->db->select('proforma_id as id')
        ->where('subscriber_id', $Varsubscriber_id)
        ->order_by('dateupdated', 'DESC')  // Get the most recent record
        ->limit(1)  // Ensure only one record is retrieved
        ->get(KN_SUBSCRIBERLIST)
        ->row()
        ->id ?? null;
}
// Get subscription detail of recent proforma_id
private function getsubscriptiondetail($table,$Varproforma_id) {
     // Build the query
     $this->db->select('*');
     $this->db->where('proforma_id', $Varproforma_id);
     $this->db->from($table);
     
     // Print the compiled query
    //  echo $this->db->get_compiled_select(); 
    //  exit(); // Stop further execution to view the query
     
     // Execute the query
     return $this->db->get()->result_array();
        
}
// Clone Table Records
private function cloneTableRecords($table, $VarId, $Varsubscriber_id, $activeproforma_id) {
    $records = $this->db->where(['proforma_id' => $activeproforma_id, 'subscriber_id' => $Varsubscriber_id])->get($table)->result_array();

    foreach ($records as $record) {
        unset($record['id']);
        $record['proforma_id'] = $VarId;
       // $record['updatedby'] = $this->userid;
        $record['datecreated'] = date('Y-m-d H:i:s');
        $record['dateupdated'] = date('Y-m-d H:i:s');

        $this->db->insert($table, $record);
    }
}

// Clone Users
private function cloneUsers($VarId, $Varsubscriber_id, $activeproforma_id) {
    $records = $this->db->where(['proforma_id' => $activeproforma_id, 'subscriber_id' => $Varsubscriber_id])->get(KN_MUSERS)->result_array();

    foreach ($records as $record) {
        unset($record['id']);
        $record['proforma_id'] = $VarId;
        $record['datecreated'] = date('Y-m-d H:i:s');
        $record['dateupdated'] = date('Y-m-d H:i:s');
        $record['status'] = 2;

        $this->db->insert(KN_MUSERS, $record);
        $VarUserId = $this->db->insert_id();

        if ($record['usertype'] == 3) {
            $this->db->insert(KN_MERCHANT_TEAM, ['merchantid' => $VarUserId, 'companyid' => $record['companyid']]);
        }

        $VarCompanyUserNo = $this->getCompanyUserCount($record['companyid']);
        $VarCode = $this->generateUserCode($record['companyid'], $record['usertype'], $VarCompanyUserNo, $VarUserId);

        $this->db->insert(KN_USER_DETAILS, ['userid' => $VarUserId, 'code' => $VarCode]);
    }
}

// Get Company User Count
private function getCompanyUserCount($companyid) {
    return $this->db->where(['companyid' => $companyid, 'status' => 1])->count_all_results(KN_MUSERS);
}

// Generate User Code
private function generateUserCode($companyid, $usertype, $companyUserNo, $userId) {
    $VarUserTypeCode = UT_SHORT_FORM[$usertype] ?? 'USR';
    return "$companyid/$VarUserTypeCode-$companyUserNo/$userId";
}
}