<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class msubscription extends CI_Controller {
    public function __construct() {
        parent::__construct();
        fnIfCheckUserLoggedIn();
        $this->load->model('commonmodel');
        $this->load->model(CNFBADMIN . "subscriptionmodal");
        $this->load->model(CNFBADMIN . "subscribermodel");
        $this->load->model(CNFBADMIN . "Reqrecivedmodel");
        $this->load->model(CNFBADMIN . "Invoicemodel");
        $this->load->model('Badminusermodel');
        $this->load->model("Musermodel");
        $this->load->helper('xssclean');
        $ArrUserLoggedInfo = fnGetUserLoggedInfo('1');
        $this->companyid = $ArrUserLoggedInfo['companyid'];
        $this->userid = $ArrUserLoggedInfo['id'];
        $this->limit = LIMITPERPAGE;
        date_default_timezone_set('Asia/Kolkata');
        $this->mysqldatetime = date('Y-m-d H:i:s');
    }
    public function view() {
        
        $ArrData = array('BasicInfo' => '', 'VarId' => '');
        $VarId = base64_decode(urldecode($this->uri->segment(4)));
        $ArrData['Edit'] = $this->uri->segment(3);
        $ArrPackage = $this->commonmodel->fnGetAllTableInfo(KN_MASTER_PACKAGE  . ' AS p', 'p.id,p.description', array('p.status' => '1', 'p.companyid' => $this->companyid),3);
        $ArrData['ArrPackage'] = $ArrPackage;
        $ArrData['ArrReqStatus'] = unserialize(ARRREQUESTSTATUS);
        $lastURI = $this->uri->segment(3);
        $checkDraftorNot = $this->subscribermodel->checkDraftorNot($VarId);
        $ArrData['checkDraftorNot']=$checkDraftorNot;
       $ArrData['lastURI'] = $lastURI;
        if (is_numeric($VarId)) {
            $ArrResults = $this->subscriptionmodal->fnGetInfo('', '', $VarId);
            $packinfo=$this->commonmodel->getPackageInfoFromPckg($ArrResults[0]->package_id);
            $ArrResults[0]->no_of_users=!empty($packinfo)?$packinfo->no_of_users:'';
            $ArrResults[0]->data_limit=!empty($packinfo)?$packinfo->data_limit:'';
            $ArrResults[0]->file_limit=!empty($packinfo)?$packinfo->file_limit:'';
            $ArrData['BasicInfo'] = $ArrResults[0];
            $ArrData['VarId'] = $ArrResults[0]->id;
            
        } else {
        }
        $this->load->view(CNFBADMIN . 'subscription/view_form', $ArrData);
    }
    
    public function manage() {
        $VarFrom = xssclean($this->input->post('rfrom'));
        $Varcmpny = trim(xssclean($this->input->post('cmpny')));
        $Varcity =xssclean($this->input->post('city'));
        $Varemailid= trim(xssclean($this->input->post('emailid')));
        $Varmobno =trim( xssclean($this->input->post('mobno')));
        $Varfromdate =trim( xssclean($this->input->post('fromdate')));
        $Vartodate =xssclean($this->input->post('todate'));
        $clickedColumnId = xssclean($this->input->post('columnId'));
        $newsortorder = xssclean($this->input->post('sortorder'));
        $ArrDbCols = array('subscriber_refno','companyname','city','email_id', 'mobile_no', 'subscription_category', 'package_id','startdate','enddate','renewal_daysleft', 'status'); 
        $ArrStatus = unserialize(ARRSTATUS);
        $subscription_category = unserialize(ARRSUBSCCATEGORY);
        $ArrPackage = $this->commonmodel->fnGetAllTableInfo(KN_MASTER_PACKAGE  . ' AS p', 'p.id,p.description', array('p.status' => '1'),3);
        
        if ($VarFrom == 1) {
            $VarURLSegment = 5;
            $this->load->library('pagination');
            $config['base_url'] = base_url() . CNFBADMIN . 'msubscription/manage/';
            $config['total_rows'] = $this->subscriptionmodal->fnCount($Varcmpny,$Varcity,$Varemailid,$Varmobno,$Varfromdate,$Vartodate);
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
            $ArrList = $this->subscriptionmodal->fnList($Varcmpny,$Varcity,$Varemailid,$Varmobno,$Varfromdate,$Vartodate,$this->limit, $offset, $sortby, $sortorder)->result();
            $data['pagination'] = $this->pagination->create_linkswithajax('Msubscription');
            $i = 0;
            $ArrFnlList = array();
            foreach ($ArrList as $Obj) {
                $ArrFnlList[$i]['id'] = $Obj->id;
                $ArrFnlList[$i]['subsrptnrefno'] = $Obj->subscriber_refno;
                $ArrFnlList[$i]['cmpny'] = $Obj->companyname;
                $ArrFnlList[$i]['city'] = $Obj->city;
                $ArrFnlList[$i]['email_id'] = $Obj->email_id;
                $ArrFnlList[$i]['mobile_no'] = $Obj->mobile_no;
                $ArrFnlList[$i]['subscription_category'] = isset($Obj->subscription_category)?$subscription_category[$Obj->subscription_category]:'';
                $ArrFnlList[$i]['pckdet'] = !empty($Obj->package_id)? $this->commonmodel->getPackageInfoFromPckg($Obj->package_id)->description:'';
                $ArrFnlList[$i]['startdate'] = $Obj->startdate;
                $ArrFnlList[$i]['enddate'] = $Obj->enddate;
                $ArrFnlList[$i]['renewal_daysleft'] = $Obj->renewal_daysleft;
                $ArrFnlList[$i]['s'] = $ArrStatus[$Obj->status];
                $ArrFnlList[$i]['subscriber_id']=$Obj->subscriber_id;
                $ArrFnlList[$i]['pckg_saved_status'] = $Obj->pckg_saved_status;
                $ArrFnlList[$i]['dept_saved_status'] = $Obj->dept_saved_status;
                $i = $i + 1;
            }
            echo json_encode(array('errcode' => 1, 'cn' => $config['total_rows'], 'ct' => $i, 're' => $ArrFnlList, 'pa' => base64_encode($data['pagination'])));
            unset($ArrFnlList); 
            die;
        } else {
            $this->load->view(CNFBADMIN . 'subscription/subscription_list');
        }
    }

    function changemStatus() {
        $VarType = xssclean($this->input->post('type'));
        $VarCid = xssclean($this->input->post('cid'));
        if ($VarType <> '' && $VarCid <> '') {
            $this->subscriptionmodal->fnChangeComStat($VarCid, $VarType);
        }
        echo json_encode(array('errcode' => 1));
        die;
    }
     public function detview() {
        $VarFrom = xssclean($this->input->post('rfrom'));
        $VarsubscriberId = xssclean($this->input->post('suscriberid'));
        $clickedColumnId = xssclean($this->input->post('columnId'));
        $newsortorder = xssclean($this->input->post('sortorder'));
        $ArrDbCols = array('invoice_refno','invoice_date','transaction_value','payment_mode', 'transaction_no', 'transaction_date'); 
        $ArrStatus = unserialize(ARRSTATUS);
        $paymentmode = unserialize(ARRPAYMENTMODE);
        $subscription_category = unserialize(ARRSUBSCCATEGORY);
        $ArrPackage = $this->commonmodel->fnGetAllTableInfo(KN_MASTER_PACKAGE  . ' AS p', 'p.id,p.description', array('p.status' => '1'),3);
        $ProfArrStatus = unserialize(PROFARRSTATUS);

        if ($VarFrom == 1) {
            $VarURLSegment = 5;
            $this->load->library('pagination'); 
            $config['base_url'] = base_url() . CNFBADMIN . 'msubscription/detview/';
            $config['total_rows'] = $this->subscriptionmodal->fndetCount($VarsubscriberId);
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
            $ArrList = $this->subscriptionmodal->fndetList("$VarsubscriberId","NPI",$this->limit, $offset, $sortby, $sortorder)->result();
            $query  = "SELECT pi.id as suppliment_id,pi.subscriber_id,pi.invoice_refno AS invoice_refnos,pi.invoiceno,pi.master_proforma_id,pi.status as proforma_status
                     FROM " . KN_PROFORMAINVOICE . " AS pi
                     WHERE pi.proforma_type='SPI' and pi.paymentstatus <> 3 and pi.invoice_status=2 and pi.subscriber_id = $VarsubscriberId order by pi.id desc";
            $supplmndata   = $this->db->query($query)->result();
           //var_dump($supplmndata);
            $data['pagination'] = $this->pagination->create_linkswithajax('Msubscription');
            $i = 0;
            $ArrFnlList = array();
            foreach ($ArrList as $Obj) {
                $ArrFnlList[$i]['id'] = $Obj->pid;
                $ArrFnlList[$i]['subscriber_refno'] = $Obj->subscriber_refno;
                $ArrFnlList[$i]['subscriber_id'] = $Obj->subscriber_id;
                $ArrFnlList[$i]['profinvno'] = $Obj->invoice_refno;
                $ArrFnlList[$i]['profinvdate'] = $Obj->invoice_date;
                $ArrFnlList[$i]['profinvval'] = $Obj->subtotal;
                 // Find matching supplementary data for the current subscriber ($Obj->pid==$v->master_proforma_id)
                $supplData = array();
                foreach ($supplmndata as $v) {
                    if ($Obj->pid==$v->master_proforma_id) {
                        $supplData[] = array(
                            'supplprofinvono' => !empty($v->invoice_refnos)?$v->invoice_refnos:'-',
                            'supplinvono' => !empty($v->invoiceno)?$v->invoiceno:'-',
                            'suppliment_id' => $v->suppliment_id
                        );
                    }
                }
            
                // Add supplementary data to $ArrFnlList
                $ArrFnlList[$i]['supplData'] = $supplData;

                
                    $ArrFnlList[$i]['paymentmode'] = !empty($Obj->payment_mode)?$paymentmode[$Obj->payment_mode]:'';
                    $ArrFnlList[$i]['chequeno'] = !empty($Obj->transaction_no)?$Obj->transaction_no:'';
                    $ArrFnlList[$i]['chequedate'] = !empty($Obj->transaction_date)?$Obj->transaction_date:'';
                    $ArrFnlList[$i]['transval'] = !empty($Obj->transaction_value)?$Obj->transaction_value:'';
                    $ArrFnlList[$i]['invno'] = !empty($Obj->invoiceno)?$Obj->invoiceno:'';
                    $ArrFnlList[$i]['invdate'] = !empty($Obj->invoiceno)?$Obj->invoice_date:'';
                    $ArrFnlList[$i]['invval'] = !empty($Obj->invoiceno)?$Obj->subtotal:'';
                    $ArrFnlList[$i]['proforma_status'] = !empty($Obj->proforma_status)?$Obj->proforma_status:'';
                    $ArrFnlList[$i]['proformainv_status'] = $ProfArrStatus[$Obj->proforma_status];
                
                    $i = $i + 1;
            }
            echo json_encode(array('errcode' => 1, 'cn' => $config['total_rows'], 'ct' => $i, 're' => $ArrFnlList, 'pa' => base64_encode($data['pagination'])));
            unset($ArrFnlList); 
            die;
        } else {
            $this->load->view(CNFBADMIN . 'subscription/detail_view_form');
        }
    }
     public function detviews() {
        // $ArrData = array('BasicInfo' => '', 'VarId' => '');
         //$ArrData['subscription_period'] = $subscriptionperiod;
           // $ArrData['VarId'] = $ArrResults[0]->pid;
           
         $subscriberId = base64_decode(urldecode($this->uri->segment(4)));
         $proformaId = base64_decode(urldecode($this->uri->segment(5)));
         $ArrData['Edit'] = $this->uri->segment(3);
         $ArrPackage = $this->commonmodel->fnGetAllTableInfo(KN_MASTER_PACKAGE  . ' AS p', 'p.id,p.description', array('p.status' => '1', 'p.companyid' => $this->companyid),3);
         $ArrData['ArrPackage'] = $ArrPackage;
         $ArrData['ArrReqStatus'] = unserialize(ARRREQUESTSTATUS);
         $lastURI = $this->uri->segment(3);
         $ArrData['lastURI'] = $lastURI;
       //var_dump(base64_decode(urldecode($this->uri->segment(5))));
        if (is_numeric($subscriberId)) {
            $ArrResults = $this->subscriptionmodal->fnGetInfo('', '', $subscriberId);
            //print_r($ArrResults);
            // $packinfo=$this->commonmodel->getPackageInfoFromPckg($ArrResults[0]->package_id);
            // $ArrResults[0]->no_of_users=!empty($packinfo)?$packinfo->no_of_users:'';
            // $ArrResults[0]->data_limit=!empty($packinfo)?$packinfo->data_limit:'';
            // $ArrResults[0]->file_limit=!empty($packinfo)?$packinfo->file_limit:'';
            $ArrData['BasicInfo'] = $ArrResults[0];
            $ArrData['VarId'] = $ArrResults[0]->id;
            $ArrResults = $this->subscriptionmodal->fnGetInvoiceInfo('','',$proformaId,'',$subscriberId);
             //print_r($ArrResults);
            $packinfo=$this->commonmodel->getPackageInfoFromPckg($ArrResults[0]->invopackageid);
           
            $ArrResults[0]->no_of_users=!empty($packinfo)?$packinfo->no_of_users:'';
            $ArrResults[0]->data_limit=!empty($packinfo)?$packinfo->data_limit:'';
            $ArrResults[0]->file_limit=!empty($packinfo)?$packinfo->file_limit:'';
            $ArrData['BasicSubInfo'] = $ArrResults[0];

          
            $ArrData['UserwisepackInfo'] = $this->subscriptionmodal->fnDeptCount($subscriberId,$proformaId);
            $ArrFnlList = array();

           //print_r($ArrData['BasicSubInfo']);
           ////////////////////////////////////////////
        //    $ArrDbCols = array('invoice_refno','invoice_date','transaction_value','payment_mode', 'transaction_no', 'transaction_date'); 
        
        //    $VarURLSegment = 5;
        //    $config['total_rows'] = $this->subscriptionmodal->fndetCount($subscriberId);
        //    $config['per_page'] = $this->limit;
        //    $config['uri_segment'] = $VarURLSegment;
        //    $offset = $this->uri->segment($VarURLSegment);
        //    //$this->pagination->initialize($config);
        //    $sortby = "dateupdated";
        //    $sortorder = "desc";

        //    $ArrList = $this->subscriptionmodal->fndetList("$subscriberId","NPI",$this->limit, $offset, $sortby, $sortorder)->result();
        //    $ArrData['BasicSubInfo1'] = $ArrList;

        //     $i = 0;
        //     $ArrFnlList = array();
        //     $ArrData['BasicSubInfo']=$ArrList;
            
        // foreach ($ArrList as $Obj) {
        //     $ArrData['BasicSubInfo'] = $Obj->invoice_refno;
        //     $ArrData['invoice_datetime'] = $Obj->invoice_datetime;
        //     $ArrData['profinvval'] = $Obj->profinvval;

        //     $ArrData['payment_from'] = $Obj->payment_from;
        //     $ArrData['transaction_no'] = $Obj->transaction_no;
        //     $ArrData['transaction_date'] = $Obj->transaction_date;
              
        //         $ArrData['payment_mode'] = $Obj->payment_mode;
        //         $ArrData['subtotal'] = $Obj->subtotal;
        //         $ArrData['paymentstatus'] = $Obj->paymentstatus;
                

                

               
        //         $i = $i + 1;
        //     }
            

          //print_r($ArrFnlList);

           ///////////////////////////////////

            
        }
       
        
         $this->load->view(CNFBADMIN . 'subscription/subscription_detail',$ArrData,$ArrFnlList);
     }
     public function showpackagedetails()
    {
        $subscriber_id = xssclean($this->input->post('subscriber_id'));
        $proforma_id = xssclean($this->input->post('proforma_id'));
        $data  = $this->subscriptionmodal->getPackagedetails($subscriber_id,$proforma_id);
        echo json_encode($data);
    }
    public function showpackagewiseuserdetails()
    {
        $subscriber_id = xssclean($this->input->post('subscriber_id'));
        $proforma_id = xssclean($this->input->post('proforma_id'));
        $data  = $this->subscriptionmodal->getPackagewiseuserdetails($subscriber_id,$proforma_id);
        echo json_encode($data);
    }
    public function updatedeptcountInfo()
    {
        $ArrResult = array();
        $object = xssclean($this->input->post('object'));
        $data   = json_decode($object);
        $Varsubsciber_id = xssclean($this->input->post('subscriber_id'));
        $Varproforma_id = xssclean($this->input->post('proforma_id'));
        $Varedit_status = xssclean($this->input->post('edit_status'));
        if(!empty($data)){
            $ArrResult  = $this->subscriptionmodal->savedeptcount($data,$Varsubsciber_id,$Varproforma_id,$Varedit_status);
        }else {
            $ArrResult['errcode'] = -1;
            $ArrResult['msg'] = 'Invalid Input';
        }
        echo json_encode($ArrResult);
      
    }
    public function invoice() {
        $VarId = base64_decode(urldecode($this->uri->segment(4)));
        $ArrData = array('BasicInfo' => '', 'VarId' => '');
        $ArrData['lastURI'] = $VarId;
        $purchase_type = unserialize(ARRPURCHASETYPE);
        
        if (is_numeric($VarId)) {
            $ArrResults = $this->subscriptionmodal->fnGetInvoiceInfo('','',$VarId,'','');
            $draftrslt= $this->Reqrecivedmodel->getdraftdata();
            $ArrData['BasicInfo'] = $ArrResults[0];
            $ArrData['amount']=!empty($ArrResults[0])?(($ArrResults[0]->subtotal!='0.00')?$this->commonmodel->getIndianCurrency($ArrResults[0]->subtotal).'only.':''):'';
            $ArrData['purchase_type']=!empty($ArrResults[0])?$purchase_type[$ArrResults[0]->invopurchasetype]:'';
           // $ArrData['packagedet']=!empty($ArrResults[0])?$this->commonmodel->getPackageInfoFromPckg($ArrResults[0]->package_id)->description:'';
            $ArrData['SubscriberId'] = $ArrResults[0]->subscriber_id;
            $ArrData['VarId'] = !empty($ArrResults[0])?$ArrResults[0]->pid:''; 
        } else {
        }
        $this->load->view(CNFBADMIN . 'invoice/invoice_form',$ArrData);
    }
    public function manage_dept() {
        $VarFrom = xssclean($this->input->post('rfrom'));
        $Varsubscriberid = trim(xssclean($this->input->post('subscriber_id')));
        $clickedColumnId = xssclean($this->input->post('columnId'));
        $newsortorder = xssclean($this->input->post('sortorder'));
        $ArrDbCols = array('usertype','id'); 
        $proformaId = trim(xssclean($this->input->post('proforma_id')));
        $ArrUserType = unserialize(ARRUSERTYPE);
        $ArrStatus = unserialize(ARRSTATUS);
        if ($VarFrom == 1) {
            $VarURLSegment = 5;
            $this->load->library('pagination');
            $config['base_url'] = base_url() . CNFBADMIN . 'msubscription/manage_dept/';
            $config['total_rows'] = $this->subscriptionmodal->fnDeptCount($Varsubscriberid,$proformaId);
            $config['per_page'] = $this->limit;
            $config['uri_segment'] = $VarURLSegment;
            $offset = $this->uri->segment($VarURLSegment);
            $this->pagination->initialize($config);
            $sortby = "id";
            $sortorder = "desc";
            if ($clickedColumnId <> '' && $newsortorder <> '') {
                if (array_key_exists($clickedColumnId, $ArrDbCols)) {
                    $sortby = $ArrDbCols[$clickedColumnId];
                }
                $sortorder = $newsortorder;
            }
            $ArrList = $this->subscriptionmodal->fnDeptList($Varsubscriberid,$proformaId,$this->limit, $offset, $sortby, $sortorder)->result();
            $data['pagination'] = $this->pagination->create_linkswithajax('Msubscription');
            $i = 0;
            $ArrFnlList = array();
            foreach ($ArrList as $Obj) {
                $ArrFnlList[$i]['id'] = $Obj->id;
                $ArrFnlList[$i]['usertype'] = $ArrUserType[$Obj->usertype];
                $ArrFnlList[$i]['status']   = $ArrStatus[$Obj->status];
                $ArrFnlList[$i]['subscriber_id'] = $Obj->subscriber_id;
                $ArrFnlList[$i]['proforma_id'] = $Obj->proforma_id;
                $i = $i + 1;
            }
            echo json_encode(array('errcode' => 1, 'cn' => $config['total_rows'], 'ct' => $i, 're' => $ArrFnlList, 'pa' => base64_encode($data['pagination'])));
            unset($ArrFnlList); 
            die;
        } else {
            $this->load->view(CNFBADMIN . 'subscription/subscription_detail');
        }
    }  
    function changemdeptStatus() {
        $VarType = xssclean($this->input->post('type'));
        $VarCid = xssclean($this->input->post('cid'));
        if ($VarType <> '' && $VarCid <> '') {
            $this->subscriptionmodal->fnChangeDeptStat($VarCid, $VarType);
        }
        echo json_encode(array('errcode' => 1));
        die;
    }
    public function addrole()
    {
        
        $ArrData = array('ArrBasicInfo' => array(), 'VarId' => '');
        $VarHashId = $this->uri->segment(4);
        $ArrData['Edit'] = $this->uri->segment(6);
        $Varproformaid= $this->uri->segment(5);
        $VarUserInfo = fnGetUserLoggedInfo(1);
        if (isset($VarUserInfo['companyid'])) {
            $VarCompanyId = $VarUserInfo['companyid'];
        }

        if ($VarHashId <> '' && base64_decode(urldecode($VarHashId))) {
            $VarId = base64_decode(urldecode($VarHashId)); // subscriberid
           
            // $ArrResults = $this->Musermodel->fnGetInfo('','',$VarId);
            // $ArrData['ArrBasicInfo'] = $ArrResults[0];
            // $ArrData['VarId'] = $ArrResults[0]['id'];
           $ArrData['ArrUserType'] = unserialize(ARRUSERTYPE);
           $usertypes=$this->getUserType($VarId); //getting usertype based on userid
           $ArrData['subscriber_id'] = $usertypes->subscriber_id;
           $ArrData['proforma_id'] = base64_decode(urldecode($Varproformaid));
           $ArrData['usertype_id'] = $usertypes->usertype;
           $ArrRoleInfo=$this->subscriptionmodal->fnGetRoleInfo($ArrData['subscriber_id'],$ArrData['proforma_id'],$ArrData['usertype_id'],KN_ROLE_PERMISSION_MASTER);
           $ArrData['ArrRoleInfo'] = count($ArrRoleInfo)>0 ? explode(',',$ArrRoleInfo[0]['title']):[];
           $ArrData['ArrListExsist'] = $this->fnCheck($ArrData['subscriber_id'],$ArrData['usertype_id'],$ArrData['proforma_id'],KN_DEPTROLE_PERMISSION);
           $ArrData['Arrdeptwiseinfo'] = $this->fnSubuserDeptinfo($ArrData['usertype_id'],$ArrData['subscriber_id'],$ArrData['proforma_id']);
           $ArrData['Arrdeptwiseinfostatus'] = $ArrData['Arrdeptwiseinfo']->status;
           $ArrResults = $this->subscriptionmodal->fnGetInvoiceInfo('','',$ArrData['proforma_id'],'',$ArrData['subscriber_id']);
           $ArrData['proformastatus'] = !empty($ArrResults[0])?$ArrResults[0]->proforma_status:'';
           $ArrData['confirmstatus'] = !empty($ArrResults[0])?$ArrResults[0]->confirm_status:'';
       
        } else {
           
        }
        
        if(!empty($usertypes) && ($usertypes->usertype==3 || $usertypes->usertype==15)){
          $this->load->view(CNFBADMIN . 'subscription/merchant_dept', $ArrData);  
        }else if(!empty($usertypes) && $usertypes->usertype==2){
          $this->load->view(CNFBADMIN . 'subscription/manage_dept', $ArrData);  
        }else if(!empty($usertypes) && $usertypes->usertype==4){
          $this->load->view(CNFBADMIN . 'subscription/cad_dept', $ArrData);  
        }else if(!empty($usertypes) && $usertypes->usertype==5){
          $this->load->view(CNFBADMIN . 'subscription/sample_dept', $ArrData);  
        }else if(!empty($usertypes) && $usertypes->usertype==12){
          $this->load->view(CNFBADMIN . 'subscription/finance_dept', $ArrData);  
        }else if(!empty($usertypes) && $usertypes->usertype==7){
          $this->load->view(CNFBADMIN . 'subscription/purchase_dept', $ArrData);  
        }else if(!empty($usertypes) && $usertypes->usertype==8){
          $this->load->view(CNFBADMIN . 'subscription/bom_store_dept', $ArrData);  
        }else if(!empty($usertypes) && $usertypes->usertype==11){
          $this->load->view(CNFBADMIN . 'subscription/qa_dept', $ArrData);  
        }else if(!empty($usertypes) && $usertypes->usertype==1){
          $this->load->view(CNFBADMIN . 'subscription/companyadmin_dept', $ArrData);  
        }else if(!empty($usertypes) && $usertypes->usertype==6){
          $this->load->view(CNFBADMIN . 'subscription/fabric_dept', $ArrData);  
        }else if(!empty($usertypes) && $usertypes->usertype==14){
          $this->load->view(CNFBADMIN . 'subscription/fabric_store1_dept', $ArrData);  
        }else if(!empty($usertypes) && $usertypes->usertype==16){
          $this->load->view(CNFBADMIN . 'subscription/fabric_store2_dept', $ArrData);  
        }else if(!empty($usertypes) && $usertypes->usertype==9){
          $this->load->view(CNFBADMIN . 'subscription/production_dept', $ArrData);  
        }else if(!empty($usertypes) && $usertypes->usertype==13){
          $this->load->view(CNFBADMIN . 'subscription/doc_logistic_dept', $ArrData);  
        }else if(!empty($usertypes) && $usertypes->usertype==10){
          $this->load->view(CNFBADMIN . 'subscription/lab_dept', $ArrData);  
        }else{
            $this->detviews();
        }
        
    }
    
    function getUserType($Var_id = '') { // getting usertype based on user id
        if ($Var_id <> '') {
             $this->db->select("usertype,subscriber_id");
            $ArrRes = $this->db->get_where(KN_MNGUSERDEPTCNT, array('id' => $Var_id));
            return $ArrRes->row();
        } 
    }
    public function showdepartment()
    {
        $subscriber_id = xssclean($this->input->post('subscriber_id'));
        $proforma_id = xssclean($this->input->post('proforma_id'));
        $dept_id = xssclean($this->input->post('dept_id'));
        $data  = $this->subscriptionmodal->getdeptdetails($subscriber_id,$proforma_id,$dept_id);
        echo json_encode($data);
    }
    public function showmerchdepartment()
    {
        $subscriber_id = xssclean($this->input->post('subscriber_id'));
        $proforma_id = xssclean($this->input->post('proforma_id'));
        $dept_id = xssclean($this->input->post('dept_id'));
        $data  = $this->subscriptionmodal->getmerchdeptdetails($subscriber_id,$proforma_id,$dept_id);
        echo json_encode($data);
    }
    public function showmngdepartment()
    {
        $subscriber_id = xssclean($this->input->post('subscriber_id'));
        $proforma_id = xssclean($this->input->post('proforma_id'));
        $dept_id = xssclean($this->input->post('dept_id'));
        $data  = $this->subscriptionmodal->getmngdeptdetails($subscriber_id,$proforma_id,$dept_id);
        echo json_encode($data);
    }
    public function showsampledepartment()
    {
        $subscriber_id = xssclean($this->input->post('subscriber_id'));
        $proforma_id = xssclean($this->input->post('proforma_id'));
        $dept_id = xssclean($this->input->post('dept_id'));
        $data  = $this->subscriptionmodal->getsampledetails($subscriber_id,$proforma_id,$dept_id);
        echo json_encode($data);
    }
    public function showpurchasedepartment()
    {
        $subscriber_id = xssclean($this->input->post('subscriber_id'));
        $proforma_id = xssclean($this->input->post('proforma_id'));
        $dept_id = xssclean($this->input->post('dept_id'));
        $data  = $this->subscriptionmodal->getpurchasedetails($subscriber_id,$proforma_id,$dept_id);
        echo json_encode($data);
    }
    public function showfabricdepartment()
    {
        $subscriber_id = xssclean($this->input->post('subscriber_id'));
        $proforma_id = xssclean($this->input->post('proforma_id'));
        $dept_id = xssclean($this->input->post('dept_id'));
        $data  = $this->subscriptionmodal->getfabricdetails($subscriber_id,$proforma_id,$dept_id);
        echo json_encode($data);
    }
    public function updateInfo()
{
    $ArrUpdateData = [];
    $ArrUpdateData['subscriber_id'] = xssclean($this->input->post('subscriber_id'));
    $ArrUpdateData['proforma_id'] = xssclean($this->input->post('proforma_id'));
    $ArrUpdateData['usertype'] = xssclean($this->input->post('dept_id'));
    $ArrUpdateData['dateupdated'] = $this->mysqldatetime;
    $ArrUpdateData['datecreated'] = date('Y-m-d H:i:s');
    $ArrUpdateData['companyid'] = $this->companyid;
    $ArrUpdateData['updatedby'] = $this->userid;
    $ArrUpdateData['status'] = 1;

    $title = xssclean($this->input->post('title')); // Form names
    $object = xssclean($this->input->post('object'));
    $data = !empty($object) ? json_decode($object) : '';
    $statuscheck = xssclean($this->input->post('statuscheck'));

    $rolewisedeptcnt = $this->subscriptionmodal->fngetDeptCount(
        $ArrUpdateData['subscriber_id'],
        $ArrUpdateData['proforma_id'],
        '',
        1,
        KN_ROLE_PERMISSION_MASTER
    );
    $Deptcnt = $this->subscriptionmodal->fngetDeptCount(
        $ArrUpdateData['subscriber_id'],
        $ArrUpdateData['proforma_id'],
        '',
        1,
        KN_MNGUSERDEPTCNT
    );
    $SubscriberAccntExist = $this->fnCheck($ArrUpdateData['subscriber_id'], '', $ArrUpdateData['proforma_id'], KN_MUSERS);

//var_dump($rolewisedeptcnt);die;
       // Save department permissions
        if (!empty($data)) {
            $ArrResults = $this->subscriptionmodal->savedeptpermission(
                $data,
                $ArrUpdateData['subscriber_id'],
                $ArrUpdateData['usertype'],
                $statuscheck,
                $ArrUpdateData['proforma_id']
            );
        }
    // Handle title-related updates
    if (!empty($title)) {
        $selected_title_array = explode(',', $title); // Titles from the checkbox input
        // Fetch existing records for the given subscriber, proforma, and usertype
        $existingRecords = $this->db
            ->get_where(KN_ROLE_PERMISSION_MASTER, [
                'subscriber_id' => $ArrUpdateData['subscriber_id'],
                'proforma_id' => $ArrUpdateData['proforma_id'],
                'usertype' => $ArrUpdateData['usertype']
            ])
            ->result_array();

        // Create a list of existing titles
        $existingTitles = array_column($existingRecords, 'title');

        // Mark existing titles that are not checked as status = 2 (unchecked)
        foreach ($existingRecords as $record) {
            if (!in_array($record['title'], $selected_title_array)) {
                // Update status to 2 (unchecked)
                $this->db->update(
                    KN_ROLE_PERMISSION_MASTER,
                    ['status' => 2, 'dateupdated' => $ArrUpdateData['dateupdated']],
                    ['id' => $record['id']]
                );
            }
        }

        // Iterate through selected titles (checked ones)
        foreach ($selected_title_array as $title) {
            $ArrUpdateData['title'] = $title;

            if (in_array($title, $existingTitles)) {
                // Update existing record to status = 1 (checked)
                $this->db->update(
                    KN_ROLE_PERMISSION_MASTER,
                    ['status' => 1, 'dateupdated' => $ArrUpdateData['dateupdated']],
                    [
                        'subscriber_id' => $ArrUpdateData['subscriber_id'],
                        'proforma_id' => $ArrUpdateData['proforma_id'],
                        'usertype' => $ArrUpdateData['usertype'],
                        'title' => $title
                    ]
                );
            } else {
                // Insert a new record for checked titles
                $ArrUpdateData['status'] = 1; // Checked status
                $this->db->insert(KN_ROLE_PERMISSION_MASTER, $ArrUpdateData);
            }
        }
        //var_dump($rolewisedeptcnt);die;
        // Update department saved status
        $this->subscriptionmodal->updateDeptSavedStatus(
            $ArrUpdateData['subscriber_id'],
            $ArrUpdateData['proforma_id'],
            $rolewisedeptcnt,
            $Deptcnt,
            $SubscriberAccntExist
        );

        $ArrResult['errcode'] = 1;
        $ArrResult['msg'] = 'Saved';
    } else if (empty($title)) {
        // Check if there are entries for the given subscriber, proforma, and usertype
        $existingRecords = $this->db
            ->get_where(KN_ROLE_PERMISSION_MASTER, [
                'subscriber_id' => $ArrUpdateData['subscriber_id'],
                'proforma_id' => $ArrUpdateData['proforma_id'],
                'usertype' => $ArrUpdateData['usertype']
            ])
            ->result_array();

        if (!empty($existingRecords)) {
            // Update existing entries to inactive (status = 2)
            $this->db->update(
                KN_ROLE_PERMISSION_MASTER,
                ['status' => 2, 'dateupdated' => $ArrUpdateData['dateupdated']],
                [
                    'subscriber_id' => $ArrUpdateData['subscriber_id'],
                    'proforma_id' => $ArrUpdateData['proforma_id'],
                    'usertype' => $ArrUpdateData['usertype']
                ]
            );
        }
        //var_dump($rolewisedeptcnt);die;
        // Update department saved status
       // $roledeptcnt = !empty($existingRecords) ? $rolewisedeptcnt - 1 : $rolewisedeptcnt + 1;

        $this->subscriptionmodal->updateDeptSavedStatus(
            $ArrUpdateData['subscriber_id'],
            $ArrUpdateData['proforma_id'],
            $rolewisedeptcnt,
            $Deptcnt,
            $SubscriberAccntExist
        );

        $ArrResult['errcode'] = 1;
        $ArrResult['msg'] = 'Saved';
    } else if (isset($ArrResults) && !empty($ArrResults)) {
        $ArrResult['errcode'] = ($ArrResults['errcode'] == 1) ? 2 : 3;
    } else {
        $ArrResult['errcode'] = -1;
        $ArrResult['msg'] = 'Invalid Input';
    }

    echo json_encode($ArrResult);
}

    
    function fnCheck($Var_SubscriberId = '',$Var_DeptId='',$Var_proformaId = '',$tablename) {
        $this->db->from($tablename);
        if ($Var_SubscriberId <> '') {
            $this->db->where_in('subscriber_id', array($Var_SubscriberId));
        }if ($Var_proformaId <> '') {
            $this->db->where_in('proforma_id', array($Var_proformaId));
        }if ($Var_DeptId <> '') {
            $this->db->where_in('usertype', array($Var_DeptId));
        }
        $countAll = $this->db->count_all_results();
        return $countAll;
    }
    public function subaccnt() {
        
        $ArrData = array('BasicInfo' => '', 'VarId' => '');
        $VarId = base64_decode(urldecode($this->uri->segment(4)));
        $lastURI = $this->uri->segment(3);
       $ArrData['lastURI'] = $lastURI;
        if (is_numeric($VarId)) {
            $ArrResult = $this->subscriptionmodal->fnGetsubscriber_LoginInfo('', 1, $VarId,1,'','');
            //var_dump($ArrResult[0]->companyprefix);
            //file_put_contents("error_log", print_r($ArrResult[0]->companyprefix, true));
            $ArrData['companyprefix'] =!empty($ArrResult[0]->companyprefix)?$ArrResult[0]->companyprefix:'';

            $ArrData['BasicInfo'] = !empty($ArrResult[0])?$ArrResult[0]:'';
            $ArrData['VaruserId'] =!empty($ArrResult[0]->id)?$ArrResult[0]->id:'';
            $ArrData['Edit'] = (!empty($ArrResult[0]))?'edit':'';
            $ArrResults = $this->subscriptionmodal->fngetsubscriptionlist($VarId);
            //var_dump($ArrResults);
            $ArrData['subscriberrefno'] = $ArrResults[0]['subscriber_refno'];
            $loginid=!empty($ArrResult[0])? explode('@',$ArrResult[0]->username):'';
            $ArrData['loginsuffix'] =  !empty($loginid)? '@'.$loginid[1]:'@'.substr($ArrResults[0]['subscriber_refno'], 11);
            $ArrData['loginprefix'] =  !empty($loginid)?$loginid[0]:'useradmin';
            $ArrData['randompwd'] =$this->generateRandomPassword(8);
            $ArrData['subscriber_id'] =$VarId;
            // var_dump(explode('@',$ArrResult[0]->username));
            //$ArrData['id'] ='';
        } else {
        }
        $this->load->view(CNFBADMIN . 'subscription/login', $ArrData);
    }
    function generateRandomPassword($length) {
    $charset = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_+{}[]:;<>,.?/~";
    $password = "";

    for ($i = 0; $i < $length; $i++) {
        $randomIndex = mt_rand(0, strlen($charset) - 1);
        $password .= $charset[$randomIndex];
    }

    return $password;
    }
    public function updateloginInfo()
    {
        $ArrUpdateData = array();
        $ArrUpdateroleData = array();
        $ArrResult = array();
        $Varsubsciber_id = xssclean($this->input->post('subscriber_id'));
        $Varid = xssclean($this->input->post('id'));
        $Varusername = xssclean($this->input->post('username'));
        $Varcompany_prefix = xssclean($this->input->post('companyprefix'));
        $Varlogin_prefix = xssclean($this->input->post('login_prefix'));
        $Varlogin_suffix = xssclean($this->input->post('login_suffix'));
        $Varpassword= xssclean($this->input->post('password'));
        $ArrUserLoggedInfo = fnGetUserLoggedInfo('1');
        $this->userid = $ArrUserLoggedInfo['id'];
        $login_id=$Varlogin_prefix.$Varlogin_suffix;
        date_default_timezone_set('Asia/Kolkata');
        
        $ArrResults = $this->subscriptionmodal->fnGetInfo('', '', $Varsubsciber_id);
            //var_dump($ArrResults);
        $ArrUpdateData['id'] = $Varid;
        $ArrUpdateData['usertype'] = 1;
        $ArrUpdateData['dept_usercount'] = 1;
        $ArrUpdateData['designation'] = $ArrResults[0]->designation;
        $ArrUpdateData['companyid'] = 3;
        $ArrUpdateData['subscriber_id'] = $Varsubsciber_id;
        $ArrUpdateData['contactname'] =$Varusername;
        $ArrUpdateData['address'] = $ArrResults[0]->address;
        $ArrUpdateData['username'] = $login_id;
        $ArrUpdateData['companyprefix'] = $Varcompany_prefix;
        $ArrUpdateData['password'] = xssclean($this->input->post('password'));
        $ArrUpdateData['state'] =$ArrResults[0]->state;
        $ArrUpdateData['city'] =$ArrResults[0]->city;
        $ArrUpdateData['email_id'] =$ArrResults[0]->email_id;
        $ArrUpdateData['mobile'] = $ArrResults[0]->mobile_no;
        $ArrUpdateData['doj'] = '0000-00-00';
        $ArrUpdateData['curr_salarypackage'] = 'Nil';
        $ArrUpdateData['bankname'] = 'Nil';
        $ArrUpdateData['accountname'] = 'Nil';
        $ArrUpdateData['accountno'] = 'Nil';
        $ArrUpdateData['ifsccode'] = 'Nil';
        $ArrUpdateData['swiftcode'] = 'Nil';
        $ArrUpdateData['status'] =1;
        $ArrUpdateData['dateupdated'] = $this->mysqldatetime;
        $ArrUpdateData['updatedby'] = $this->userid;
        $ArrUpdateData['profilepermission'] =  1;
            if (empty($Varid)) {
                $ArrUpdateData['pin'] = '1234';
                $ArrUpdateData['datecreated'] = date('Y-m-d H:i:s');
            }
        $ArrUpdateroleData['companyid']=3;
        $ArrUpdateroleData['subscriber_id']=$Varsubsciber_id;
        $ArrUpdateroleData['usertype'] = 1;
        $ArrUpdateroleData['datecreated'] = date('Y-m-d H:i:s');
        $ArrUpdateroleData['dateupdated'] = date('Y-m-d H:i:s');
        $ArrUpdateroleData['updatedby'] = $this->userid;
        
        if(empty($Varid)){
            $ArrCheckExist = $this->Musermodel->fnGetInfo($Varusername, 1, '');
             if (empty($ArrCheckExist)) {
                $this->db->insert(KN_MUSERS, $ArrUpdateData);
                $primaryId                          = $this->db->insert_id();
                if($primaryId){ // activating subscription here
                    $updateData = ['status' => 1, 'dateupdated' => date('Y-m-d H:i:s')];
                    $this->db->where('subscriber_id', $Varsubsciber_id)
                             ->update(KN_SUBSCRIBERLIST, $updateData);    
                }
                $ArrResult['errcode'] = 1;
                $ArrResult['msg'] = '';
                $ArrResult['id'] = $Varid;
                $VarCode='UserAdmin-1';
                $ArrUserdetData = array('userid' => $primaryId, 'code' => $VarCode);
                $this->db->insert(KN_USER_DETAILS, $ArrUserdetData);
                 $selected_title_array=array('Manage Master Data' => 'Manage Master Data','Manage Department & User' => 'Manage Department & User');
            // var_dump($selected_title_array);die;  
            foreach ($selected_title_array as $k=>$v){
                $ArrUpdateroleData['title']=$v;
                $this->db->insert(KN_ROLE_PERMISSION_MASTER, $ArrUpdateroleData);
               // $primaryId = $this->db->insert_id();
            }
             }else {
            $ArrResult['errcode'] = -1;
            $ArrResult['msg'] = 'This Login Id already exists';
            }
        
        }else{
            $ArrCheckExist = $this->Musermodel->fnCheck($Varusername, $Varid);
            if (empty($ArrCheckExist)) {
                $this->db->update(KN_MUSERS, $ArrUpdateData, array('id' => $Varid));
                $ArrResult['errcode'] = 1;
                $ArrResult['msg'] = '';
                $ArrResult['id'] = $Varid;
                $ArrResult['eid'] = urlencode(base64_encode($Varid));
            } else {
                $ArrResult['errcode'] = -1;
                $ArrResult['msg'] = 'This Login Id already exists';
            }
        }
        echo json_encode($ArrResult);
      
    }
    public function mng_subscriberuserlist()
    {
        $VarFrom = xssclean($this->input->post('rfrom'));
        $Varsubscriber_id =trim(xssclean($this->input->post('subscriber_id')));
        $Varproforma_id =trim(xssclean($this->input->post('proforma_id')));
        $clickedColumnId = xssclean($this->input->post('columnId'));
        $newsortorder = xssclean($this->input->post('sortorder'));
        //$VarAfilter = xssclean($this->input->post('afilter'));
        $ArrDbCols = array('usertype', 'dept_usercount','designation','contactname ','username ','email_id','mobile', 'status', 'dateupdated');
        if ($VarFrom == 1) {
            
            $VarURLSegment = 5;

            $this->load->library('pagination');

            $config['base_url'] = base_url() . CNFBADMIN . 'msubscription/mng_subscriberuserlist/';
           
            $config['total_rows'] = $this->subscriptionmodal->fngetsubscriberuserCount($Varsubscriber_id,$Varproforma_id);
           
			$config['per_page'] = $this->limit;
            
            $config['uri_segment'] = $VarURLSegment;
            
            $this->pagination->initialize($config);

            $sortby = "u.dateupdated";

            $sortorder = "desc";
			
            // if ($clickedColumnId <> '' && $newsortorder <> '') {

            //     if (array_key_exists($clickedColumnId, $ArrDbCols)) {

            //         $sortby = $ArrDbCols[$clickedColumnId];

            //     }

            //     $sortorder = $newsortorder;

            // }


            $ArrList = $this->subscriptionmodal->fnsubscriberuserList($Varsubscriber_id,$Varproforma_id,$this->limit,  $sortby, $sortorder);
            
            $data['pagination'] = $this->pagination->create_linkswithajax('subscriberuser');

            $i = 0;

            $ArrFnlList = array();

            $ArrStatus = unserialize(ARRSTATUS);
            $ArrUserType = unserialize(ARRUSERTYPE);
          
            foreach ($ArrList['listData'] as $ObjUnit) {
                
                $ArrFnlList[$i]['id'] = $ObjUnit->id;
                $ArrFnlList[$i]['dept'] = $ArrUserType[$ObjUnit->usertype];
                $ArrFnlList[$i]['usercount'] =!empty($ObjUnit->dept_usercount)?$ObjUnit->dept_usercount:'';
                $ArrFnlList[$i]['desgn'] =!empty($ObjUnit->designation)?$ObjUnit->designation:'';
                $ArrFnlList[$i]['username']=!empty($ObjUnit->contactname)?$ObjUnit->contactname:'';
                $ArrFnlList[$i]['loginid'] =!empty($ObjUnit->username)?$ObjUnit->username:'';
                $ArrFnlList[$i]['em'] =!empty($ObjUnit->email_id)?$ObjUnit->email_id:'';
                $ArrFnlList[$i]['mobno'] =!empty($ObjUnit->mobile)?$ObjUnit->mobile :'';
                $ArrFnlList[$i]['s'] = $ArrStatus[$ObjUnit->status];
                $ArrFnlList[$i]['badms'] = $ArrStatus[$ObjUnit->badminstatus];
                $ArrFnlList[$i]['ub'] = !empty($ObjUnit->updatedby)?$ArrList['updatedByData'][$ObjUnit->updatedby]:'';
                $ArrFnlList[$i]['du'] =($ObjUnit->dateupdated!='0000-00-00 00:00:00')? date('d-m-Y H:i A', strtotime($ObjUnit->dateupdated)):'';
                
                
                $i = $i + 1;

            }

            echo json_encode(array('errcode' => 1, 'cn' => $config['total_rows'], 'ct' => $i, 're' => $ArrFnlList, 'pa' => base64_encode($data['pagination'])));

            unset($ArrFnlList);

            die;

        } else {
            
            $this->load->view(CNFBADMIN . 'subscription/subscription_detail');

        }

    }
    public function addedit()
    {
        // $VarRemainingUser = $this->commonmodel->remaininguseravailable($this->companyid,1);
        // if($VarRemainingUser == 0) {
        //     die('User Limit Ended. Can\'t add more users');
        // }
        $ArrData = array('ArrBasicInfo' => array(), 'VarId' => '');
        $VarHashId = $this->uri->segment(4);
       
        $ArrData['Edit'] = $this->uri->segment(5);
        
        $VarUserInfo = fnGetUserLoggedInfo(1);
        if (isset($VarUserInfo['companyid'])) {
            $VarCompanyId = $VarUserInfo['companyid'];
        }

        if ($VarHashId <> '' && base64_decode(urldecode($VarHashId))) {
            $VarId = base64_decode(urldecode($VarHashId));
            $ArrResults = $this->Badminusermodel->fnGetInfo('','',$VarId);
            $ArrData['ArrBasicInfo'] = $ArrResults[0];
            $ArrData['VarId'] = $ArrResults[0]['id'];
            $ArrData['VarsubscriberId'] = $ArrResults[0]['subscriber_id'];
            $ArrData['Varproforma_id']=$ArrResults[0]['proforma_id'];
        } else {
           
        }
        $this->load->view(CNFBADMIN . 'subscription/addedit_user', $ArrData);
    }
    public function getactivedeptcount()
   {
       $VarFrom = xssclean($this->input->post('rFrom'));
       $Varsubscriberid = xssclean($this->input->post('subscriber_id'));
       $Varproformaid = xssclean($this->input->post('proforma_id'));

       if ($VarFrom == 1) {
            $VarSqlLab = "SELECT sum(total_users) as trec  FROM " . KN_MNGUSERDEPTCNT . " where  subscriber_id=$Varsubscriberid and proforma_id=$Varproformaid and status=1 ";
            $ObjRows = $this->db->query($VarSqlLab)->row();
            $Res = $ObjRows->trec;
               //var_dump($Rescond);
           echo json_encode(array('activeusercnt' => $Res));
       }
   }
   public function getactiveusercount()
   {
       $VarFrom = xssclean($this->input->post('rFrom'));
       $Varsubscriberid = xssclean($this->input->post('subscriber_id'));
       $Varproformaid = xssclean($this->input->post('proforma_id'));

       if ($VarFrom == 1) {
            $VarSqlLab = "SELECT count(1) as trec  FROM " . KN_MUSERS . " where  subscriber_id=$Varsubscriberid and proforma_id=$Varproformaid and badminstatus=1 ";
            $ObjRows = $this->db->query($VarSqlLab)->row();
            $Res = $ObjRows->trec;
               //var_dump($Rescond);
           echo json_encode(array('activeusercnt' => $Res));
       }
   }
   public function fnSubuserDeptinfo($Vardeptid,$Varsubscriberid,$Varproformaid) {
    $ArrWhere = array();
    if ($Varsubscriberid <> '') {
        $ArrWhere[] = "ud.subscriber_id=" . "'$Varsubscriberid'";
    }
    if ($Varproformaid <> '') {
        $ArrWhere[] = "ud.proforma_id=" . "'$Varproformaid'";
    }
    if ($Vardeptid <> '') {
        $ArrWhere[] = "ud.usertype=" . "'$Vardeptid'";
    }
    $VarWhere = '';
    if (!empty($ArrWhere)) {
        $VarWhere = 'WHERE ' . implode(" and ", $ArrWhere);
    }
    
    $VarSqlLab = "SELECT *  FROM " . KN_MNGUSERDEPTCNT . " AS ud " . $VarWhere;
    $ObjRows = $this->db->query($VarSqlLab)->row();
    return $ObjRows;
}
public function updateconfirmstatus()
{
    $ArrUpdateData = array();
    $ArrResult = array();
    $Varsubsciber_id = xssclean($this->input->post('subscriber_id'));
    $Varproforma_id = xssclean($this->input->post('proforma_id'));

    $ArrUpdateData['confirm_status'] = 1; // confirmed
    $ArrUpdateData['dateupdated'] = $this->mysqldatetime;
   
    if(!empty($Varsubsciber_id) && !empty($Varproforma_id)){
        $this->db->update(KN_PROFORMAINVOICE, $ArrUpdateData, array('id' => $Varproforma_id));
        $ArrResult['errcode'] = 1;
        $ArrResult['msg'] = 'updated';
        
    } else {
        $ArrResult['errcode'] = -1;
        $ArrResult['msg'] = 'notupdated';
    }
    echo json_encode($ArrResult);
  
}
}