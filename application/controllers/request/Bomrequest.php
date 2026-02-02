<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

    class Bomrequest extends CI_Controller {

        public function __construct() {
            parent::__construct();
            //error_reporting(E_ALL);
            $this->load->helper('xssclean');
            fnIfCheckUserLoggedIn();
            $this->load->model(CNFCOMPANY . 'menquirymodel');
            $this->load->model(CNFCOMPANY . 'workinprogressmodel');
            $ArrUserLoggedInfo = fnGetUserLoggedInfo('1');
            $this->companyid = $ArrUserLoggedInfo['companyid'];
            $this->userid = $ArrUserLoggedInfo['id'];
            $this->userid_type = $ArrUserLoggedInfo['usertype'];
            $this->mysqldatetime = date('Y-m-d H:i:s');
            $this->load->model('WorkInProcessModel');
            $this->load->model('request/RequestBomModel');
            $this->load->model('RequestSampleModel');
            $this->load->model('commonmodel');
            $this->load->model('managementmodel');
            $this->load->model(CNFCOMPANY . "msamplerequestmodel");
            $this->load->model(CNFCOMPANY . 'orderentrymodel');
             $ArrObjsubscriber_id = $this->commonmodel->getsubscriberid($this->userid = $ArrUserLoggedInfo['id']);
            $this->subscriber_id = 'Sub_Id_' . $ArrObjsubscriber_id->subscriber_id;

             $this->subb_id = $ArrObjsubscriber_id->subscriber_id;


      
     
        
        
        }

        public function index() {
            $VarId = $this->uri->segment(4);
            if ($VarId <> '' && is_numeric(base64_decode(urldecode($VarId))))
            {
                $VarEnqId       = base64_decode(urldecode($VarId));
            }
            $ArrCommonHeaderData = $this->commonheaderdata($VarEnqId);
            $draftVal = $this->RequestBomModel->get_draft_valuee($VarEnqId);
            $req_datas = $this->RequestBomModel->get_req_datass($VarEnqId);
            $sql1 = "SELECT * FROM tbl_bom_article_1_req_consld as a 
                LEFT JOIN tbl_request as b ON a.request_id=b.request_id
                WHERE a.enquiry_id='$VarEnqId' AND a.flag=1";
            $data1 = $this->db->query($sql1)->result_array();
            for($i=0; $i < sizeof($data1); $i++)
            {
                if( isset($data1[$i]['bom_1_req_consld_id'])) {
                    if($data1[$i]['request_id'] != '') {
                        $request_id[] = $data1[$i]['request_id'];    
                    }
                    
                }
            }
            
            if(isset($request_id[0]) == '') {
                $request_id1 = 0;
            } else {
                $request_id1 = $request_id[0];
            }
            $subid=$this->subb_id;
           
            $subcompany_data = $this->RequestBomModel->getsubscribercompanydetail($subid);
            //print_r($request_id); exit;
            $this->load->view('request/bom/purchaserequest',
                         array('VarEnqId'=>$VarEnqId, 'ArrCommonHeaderData' => $ArrCommonHeaderData,'draftVal' => $draftVal, 'request_id' => $request_id1, 'req_datas' => $req_datas,'subcompany_data' => $subcompany_data));
        }

        public function get_draft_value()
        {
            $enqId = xssclean($this->input->post('enquiry_id'));
            $data = $this->RequestBomModel->get_draft_typee($enqId);
            echo json_encode($data);

        }

        public function merchantqueue() {
            $VarId = $this->uri->segment(4);
            $reqEnqId = $this->uri->segment(6);
             $subid=$this->subb_id;

            if ($VarId <> '' && is_numeric(base64_decode(urldecode($VarId))))
            {
                $VarEnqId       = base64_decode(urldecode($VarId));
                $reqEnqId       = base64_decode(urldecode($reqEnqId));
            }
            $ArrCommonHeaderData = $this->commonheaderdata($VarEnqId);
              $subcompany_data= $this->RequestBomModel->getsubscribercompanydetail($subid);

            $requestData = $this->RequestBomModel->getBomRequestDataa($VarEnqId, $reqEnqId);
             $requesttype=$requestData[0]['type'];
             //print_r($requesttype);
             if($requesttype == 3){
                 $requesttypedata = '(Art - 1)';
             }else if($requesttype == 4){
                 $requesttypedata = '(Art - 2)';
             }
            $this->load->view('request/bom/merchantqueue',
                         array('VarEnqId'=>$VarEnqId, 'ArrCommonHeaderData' => $ArrCommonHeaderData, 'requestData' => $requestData,  'requesttypedata' => $requesttypedata, 'subcompany_data' => $subcompany_data));
        }

        public function managementqueue() {
            $VarId = $this->uri->segment(4);
            $reqEnqId = $this->uri->segment(6);
            if ($VarId <> '' && is_numeric(base64_decode(urldecode($VarId))))
            {
                $VarEnqId       = base64_decode(urldecode($VarId));
                $reqId       = base64_decode(urldecode($reqEnqId));
            }
             $subid=$this->subb_id;
            $subcompany_data = $this->RequestBomModel->getsubscribercompanydetail($subid);

            $ArrCommonHeaderData = $this->commonheaderdata($VarEnqId);
            $requestData = $this->RequestBomModel->getBomRequestDataa($VarEnqId, $reqId);
             $requesttype=$requestData[0]['type'];
             //print_r($requesttype);
             if($requesttype == 3){
                 $requesttypedata = '(Art - 1)';
             }else if($requesttype == 4){
                 $requesttypedata = '(Art - 2)';
             }
            $this->load->view('request/bom/managementqueue',
                         array('VarEnqId'=>$VarEnqId, 'ArrCommonHeaderData' => $ArrCommonHeaderData, 'requestData' => $requestData, 'req_id' =>$reqId, 'requesttypedata' => $requesttypedata, 'subcompany_data' => $subcompany_data));
        }

        public function draftpi() {
            $VarId = $this->uri->segment(4);
            $reqEnqId = $this->uri->segment(6);
            if ($VarId <> '' && is_numeric(base64_decode(urldecode($VarId))))
            {
                $VarEnqId       = base64_decode(urldecode($VarId));
            }
            if ($reqEnqId <> '' && is_numeric(base64_decode(urldecode($reqEnqId))))
            {
                $reqId       = base64_decode(urldecode($reqEnqId));
            }
           // print_r($VarEnqId);
            $subid=$this->subb_id;
           
  $subcompany_data = $this->RequestBomModel->getsubscribercompanydetail($subid);

            $ArrCommonHeaderData = $this->commonheaderdata($VarEnqId);
            $requestData = $this->RequestBomModel->getBomRequestDataa($VarEnqId, $reqId);
            $piData = $this->RequestBomModel->getBomPIDataa($VarEnqId, $reqId);
            //print_r($piData[0]['pi_draft_status']); exit;
            if(@$piData[0]['pi_draft_status'] == 1) {
                $this->load->view('request/purchase/editdraftpi',
                         array('VarEnqId'=>$VarEnqId, 'ArrCommonHeaderData' => $ArrCommonHeaderData, 
                         'reqId'=> $reqId, 'requestData' => $requestData, 'piData' => $piData,'subcompany_data' => $subcompany_data,
));
            } else {
                $this->load->view('request/purchase/draftpi',
                         array('VarEnqId'=>$VarEnqId, 'ArrCommonHeaderData' => $ArrCommonHeaderData, 
                         'reqId'=> $reqId, 'requestData' => $requestData, 'piData' => $piData,'subcompany_data' => $subcompany_data,
));
            }
        }

        // ********** ORDER PROCESSING STARTS HERE *********** /
    
        public function commonheaderdata($VarEnquiryId)
        {
            $sizeChart    = $this->RequestBomModel->getSizeChart($VarEnquiryId);
            $sizeMaster   = $this->RequestBomModel->getSizeMaster($sizeChart);
    
            $sizeArray = [];
            foreach ($sizeMaster as $key => $value) {
                array_push($sizeArray, $value['size_name']);
            }
            $sizeValue = implode(", ",$sizeArray);
    
            $ArrCompanyRes       = $this->companymodel->fnGetCompanyInfo($this->companyid);
            $ArrEnquiryDetails   = $this->orderentrymodel->fnGetOrderEnquiryInfo($VarEnquiryId, $this->companyid);
            $ArrEnquiryDetails   = @$ArrEnquiryDetails[0];
            $VarHashEnquiryId    = $this->uri->segment(3);
            $ArrMerchant         = $this->commonmodel->getMerchantData($this->companyid, 1, $ArrEnquiryDetails['merchantid']);
            $ArrTeam             = $this->commonmodel->getTeamDetails($this->companyid, $ArrEnquiryDetails['merchantid']);
            $ArrCommonData       = $this->orderentrymodel->getCommonData($VarEnquiryId, $this->companyid);
            $ArrMgmt             = $this->commonmodel->getMgmtDetails($this->companyid, $ArrEnquiryDetails['mgmtid']);
            // echo "<pre>";
            // print_r($ArrEnquiryDetails);
            // echo "</pre>";
            $ArrCommonHeaderData = array(
                'companyName'       => @$ArrCompanyRes[0]['companyname'], 'companyAddress'    => @$ArrCompanyRes[0]['address'],'gst'    => @$ArrCompanyRes[0]['gst'],
                'VarEnquiryId'      => $VarEnquiryId, 'VarHashEnquiryId'  => @$VarHashEnquiryId, 'merchantName'      => @$ArrMerchant[0]['contactname'],
                'merchantMobile'    => @$ArrMerchant[0]['mobile'], 'merchantCode'      => @$ArrMerchant[0]['code'],
                'merchantEmail'     => @$ArrMerchant[0]['username'], 'ArrEnquiryDetails' => $ArrEnquiryDetails,
                'ArrCommonData'     => @$ArrCommonData, 'ArrTeam' => @$ArrTeam[0], 'sizeValue' => $sizeValue,
                'companyAddress'    => @$ArrCompanyRes[0]['address'], 'companyMobile' => @$ArrCompanyRes[0]['mobile'],
                'companyEmail' => @$ArrCompanyRes[0]['emailid'], 'ArrMgmt' => @$ArrMgmt[0]
            );
            return $ArrCommonHeaderData;
        }
        
        // ********** ORDER PROCESSING ENDS HERE *********** /

        // ********** BOM REQUEST STARTS HERE *********** /

        public function getPurchaseRequestDetails() {
            $enqId = xssclean($this->input->post('enquiry_id'));
            $data = $this->RequestBomModel->getPurchaseRequestDetailss($enqId);
            echo json_encode($data);
        }

        public function getPurchaseRequestDetails_bulk() {
            $enqId = xssclean($this->input->post('enquiry_id'));
            $data = $this->RequestBomModel->getPurchaseRequestDetailss_bulk($enqId);
            echo json_encode($data);
        }
        
        public function createPurchaseRequest() {
            //echo "<pre>"; print_r($_POST); exit;
            $object = xssclean($this->input->post('data'));
            $bom_object = xssclean($this->input->post('bom_data'));
            $id = xssclean($this->input->post('enquiry_id'));
            $req_type = xssclean($this->input->post('req_type'));
            $cutoff_date = xssclean($this->input->post('cutoff_date'));
            $merchant_note = xssclean($this->input->post('merchant_note'));
            $purchase_req_type = xssclean($this->input->post('purchase_req_type'));
            $mode = xssclean($this->input->post('mode'));
            $type = xssclean($this->input->post('type'));
            $req_id = xssclean($this->input->post('req_id'));
            $req_data = json_decode($object);
            $bom_data = json_decode($bom_object);
            $data = $this->RequestBomModel->createPurchaseRequestt($req_data, $id, $req_type, $cutoff_date, $merchant_note, $purchase_req_type, $mode, $type, $req_id, $bom_data);
            echo json_encode($data);
        }

        public function clearPurchaseRequest()
        {
            //echo "<pre>"; print_r($_POST); exit;
            $id = xssclean($this->input->post('enquiry_id'));
            
            $data = $this->RequestBomModel->clearPurchaseRequest( $id);
            echo json_encode($data);
        }

        // ********** BOM REQUEST ENDS HERE ************ /

        // ********** MERCHANT BOM QUEUE STARTS HERE *********** /

        public function getMerchantBomQueueDetails() {
            $reqId = xssclean($this->input->post('reqId'));
            $enqId = xssclean($this->input->post('enquiry_id'));
            $data = $this->RequestBomModel->getMerchantBomQueueDetailss($enqId, $reqId);
            echo json_encode($data);
        }


        public function getSupplyClosureList() {
            $reqId = xssclean($this->input->post('reqId'));
            $enqId = xssclean($this->input->post('enquiry_id'));
            $data = $this->RequestBomModel->getSupplyClosureListt($enqId, $reqId);
            echo json_encode($data);
        }

        // ********** MERCHANT BOM QUEUE ENDS HERE ************ /

        // ********** UPDATE MERCHANT BOM QUEUE STARTS HERE *********** /

        public function updateMerchantQueue() {
            $object = xssclean($this->input->post('data'));
            $enqId = xssclean($this->input->post('enquiry_id'));
            $req_data = json_decode($object);
            $data = $this->RequestBomModel->updateMerchantQueuee($req_data, $enqId);
            echo json_encode($data);
        }

        // ********** UPDATE MERCHANT BOM QUEUE ENDS HERE ************ /

        // ********** MANAGEMENT BOM QUEUE STARTS HERE *********** /

        public function getManagementBomQueueDetails() {
            $reqId = xssclean($this->input->post('reqId'));
            $enqId = xssclean($this->input->post('enquiry_id'));
            $data = $this->RequestBomModel->getManagementBomQueueDetailss($enqId, $reqId);
            echo json_encode($data);
        }

        // ********** MANAGEMENT BOM QUEUE ENDS HERE ************ /

        // ********** UPDATE MANAGEMENT BOM QUEUE STARTS HERE *********** /

        public function updateManagementQueue() {
            //echo "<pre>"; print_r($_POST); exit;
            $object = xssclean($this->input->post('data'));
            $enqId = xssclean($this->input->post('enquiry_id'));
            $reqId = xssclean($this->input->post('reqId'));
            $req_data = json_decode($object);
            $data = $this->RequestBomModel->updateManagementQueuee($req_data, $enqId, $reqId);
            echo json_encode($data);
        }

        // ********** UPDATE MANAGEMENT BOM QUEUE ENDS HERE ************ /

        // ********** MANAGEMENT BOM QUEUE STARTS HERE *********** /

        public function getDraftPIRequestDetails() {
            $reqId = xssclean($this->input->post('reqId'));
            $enqId = xssclean($this->input->post('enquiry_id'));
            //print_r($enqId);
            
            $data = $this->RequestBomModel->getDraftPIRequestDetailss($enqId, $reqId);
            echo json_encode($data);
        }

        // ********** MANAGEMENT BOM QUEUE ENDS HERE ************ /

        // ********** UPDATE PURCHASE INDENT STARTS HERE *********** /

        public function updatePurchaseIndent() {
            //echo "<pre>"; print_r($_POST); exit;
           
            $object = xssclean($this->input->post('data'));
            $log_object = xssclean($this->input->post('log_data'));
            $purchaseobject = xssclean($this->input->post('purchaseIndentData'));
            $reqId = xssclean($this->input->post('reqId'));
            $enqId = xssclean($this->input->post('enquiry_id'));
            $pi_cutoff_dt = xssclean($this->input->post('pi_cutoff_dt'));
            $purchase_dept_note = xssclean($this->input->post('purchase_dept_note'));
            $mode = xssclean($this->input->post('mode'));
            $vendorOption = xssclean($this->input->post('vendorOption'));
            $purchase_type = xssclean($this->input->post('purchase_type'));
            $payment_terms = xssclean($this->input->post('payment_terms'));
            $amount_in_words = xssclean($this->input->post('amount_in_words'));
            $exp_dod = xssclean($this->input->post('exp_dod'));
            $type = xssclean($this->input->post('type'));
            $p_type = xssclean($this->input->post('p_type'));
            $purchase_indent_id = xssclean($this->input->post('purchase_indent_id'));
            $req_data = json_decode($object);
            $log_data = json_decode($log_object);
            $pur_req_data = json_decode($purchaseobject);
            $data = $this->RequestBomModel->updatePurchaseIndentt($req_data, $enqId, $reqId, $pi_cutoff_dt, $purchase_dept_note,
                $mode, $p_type, $pur_req_data, $vendorOption, $purchase_type, $payment_terms, $log_data, $amount_in_words, $exp_dod,$type,$purchase_indent_id);
            echo json_encode($data);
        }
        
        public function updatePurchaseIndent_request() {
            // echo "<pre>"; print_r($_POST); exit;
            $object = xssclean($this->input->post('data'));
            $log_object = xssclean($this->input->post('log_data'));
            $purchaseobject = xssclean($this->input->post('purchaseIndentData'));
            $reqId = xssclean($this->input->post('reqId'));
            $enqId = xssclean($this->input->post('enquiry_id'));
            $pi_cutoff_dt = xssclean($this->input->post('pi_cutoff_dt'));
            $purchase_dept_note = xssclean($this->input->post('purchase_dept_note'));
            $mode = xssclean($this->input->post('mode'));
            $vendorOption = xssclean($this->input->post('vendorOption'));
            $purchase_type = xssclean($this->input->post('purchase_type'));
            $payment_terms = xssclean($this->input->post('payment_terms'));
            $amount_in_words = xssclean($this->input->post('amount_in_words'));
            $exp_dod = xssclean($this->input->post('exp_dod'));
            $type = xssclean($this->input->post('type'));
            $p_type = xssclean($this->input->post('p_type'));
            $purchase_indent_id = xssclean($this->input->post('purchase_indent_id'));
            $req_data = json_decode($object);
            $log_data = json_decode($log_object);
            $pur_req_data = json_decode($purchaseobject);
            $data = $this->RequestBomModel->updatePurchaseIndent_requestt($req_data, $enqId, $reqId, $pi_cutoff_dt, $purchase_dept_note,
                $mode, $p_type, $pur_req_data, $vendorOption, $purchase_type, $payment_terms, $log_data, $amount_in_words, $exp_dod,$type,$purchase_indent_id);
            echo json_encode($data);
        }


         public function updatePurchaseIndent_cancelpi() {
            // echo "<pre>"; print_r($_POST); exit;
            $object = xssclean($this->input->post('data'));
            $log_object = xssclean($this->input->post('log_data'));
            $purchaseobject = xssclean($this->input->post('purchaseIndentData'));
            $reqId = xssclean($this->input->post('reqId'));
            $enqId = xssclean($this->input->post('enquiry_id'));
            $pi_cutoff_dt = xssclean($this->input->post('pi_cutoff_dt'));
            $purchase_dept_note = xssclean($this->input->post('purchase_dept_note'));
            $mode = xssclean($this->input->post('mode'));
            $vendorOption = xssclean($this->input->post('vendorOption'));
            $purchase_type = xssclean($this->input->post('purchase_type'));
            $payment_terms = xssclean($this->input->post('payment_terms'));
            $amount_in_words = xssclean($this->input->post('amount_in_words'));
            $exp_dod = xssclean($this->input->post('exp_dod'));
            $type = xssclean($this->input->post('type'));
            $p_type = xssclean($this->input->post('p_type'));
            $purchase_indent_id = xssclean($this->input->post('purchase_indent_id'));
            $req_data = json_decode($object);
            $log_data = json_decode($log_object);
            $pur_req_data = json_decode($purchaseobject);
            $data = $this->RequestBomModel->updatePurchaseIndent_cancelpii($req_data, $enqId, $reqId, $pi_cutoff_dt, $purchase_dept_note,
                $mode, $p_type, $pur_req_data, $vendorOption, $purchase_type, $payment_terms, $log_data, $amount_in_words, $exp_dod,$type,$purchase_indent_id);
            echo json_encode($data);
        }

        // ********** UPDATE PURCHASE INDENT ENDS HERE ************ /

        
        // *********************************************************************************************************** 
        // MANAGEMENT DEPARTMENT STARTS HERE 
        // **********************************************************************************************************//

        public function getManagementPIApprovalList() {
            $data = $this->RequestBomModel->getManagementPIApprovalListt();
            echo json_encode($data);
        }

        public function managementpurchaseindentapproval() {

            $data['brands'] = $this->getBrandList();
            $this->load->view('request/bom/mgmt_pi_appl_list',$data);
        }

        public function managementpurchaseindentapprovaldetails() {
            $VarId = $this->uri->segment(4);
            $reqId = $this->uri->segment(6);
            $purchaseId = $this->uri->segment(7);
            if ($VarId <> '' && is_numeric(base64_decode(urldecode($VarId))))
            {
                $VarEnqId = base64_decode(urldecode($VarId));
            }
            if ($reqId <> '' && is_numeric(base64_decode(urldecode($reqId))))
            {
                $reqId = base64_decode(urldecode($reqId));
            }
            if ($purchaseId <> '' && is_numeric(base64_decode(urldecode($purchaseId))))
            {
                $pId = base64_decode(urldecode($purchaseId));
            }
             $subid=$this->subb_id;
            $subcompany_data = $this->RequestBomModel->getsubscribercompanydetail($subid);
            $ArrCommonHeaderData = $this->commonheaderdata($VarEnqId);
            $requestData = $this->RequestBomModel->getRequestDataa_pi($VarEnqId, $reqId, $pId);
            $requesttype=$requestData[0]['type'];
             //print_r($requesttype);
             if($requesttype == 3){
                 $requesttypedata = '(Art - 1)';
             }else if($requesttype == 4){
                 $requesttypedata = '(Art - 2)';
             }


            //print_r($requestData); exit;
            $this->load->view('request/bom/mgmt_pi_appl_details', 
                         array('VarEnqId'=>$VarEnqId,
                               'ArrCommonHeaderData' => $ArrCommonHeaderData,
                               'reqId' => $reqId,
                               'pId' => $pId,
                               'requestData' => $requestData,
                               'requesttypedata' => $requesttypedata,
                               'subcompany_data' => $subcompany_data
                            ));
        }
        
        public function updateManagementPurchaseIndentAppl() {
            //echo "<pre>"; print_r($_POST); exit;
            $eId = xssclean($this->input->post('enquiry_id'));
            $reqId = xssclean($this->input->post('reqId'));
            $pId = xssclean($this->input->post('pId'));
            $amt_pay = xssclean($this->input->post('amt_pay'));
            $object = xssclean($this->input->post('data'));
            $object2 = xssclean($this->input->post('inv_data'));
            $req_data = json_decode($object);
            $inv_data = json_decode($object2);
            $data = $this->RequestBomModel->updateManagementPurchaseIndentAppll($eId, $reqId, $req_data, $inv_data, $amt_pay,$pId);
            echo json_encode($data);
        }
        
        public function updateManagementPIData() {
            //echo "<pre>"; print_r($_POST); exit;
            $eId = xssclean($this->input->post('enquiry_id'));
            $reqId = xssclean($this->input->post('reqId'));
            $pId = xssclean($this->input->post('pId'));
            $amt_pay = xssclean($this->input->post('amt_pay'));
            $object = xssclean($this->input->post('data'));
            $object2 = xssclean($this->input->post('inv_data'));
            $req_data = json_decode($object);
            $inv_data = json_decode($object2);
            $data = $this->RequestBomModel->updateManagementPIDataa($eId, $reqId, $req_data, $inv_data, $amt_pay,$pId);
            echo json_encode($data);
        }
        
        public function updateManagementCreditData() {
            //echo "<pre>"; print_r($_POST); exit;
            $eId = xssclean($this->input->post('enquiry_id'));
            $reqId = xssclean($this->input->post('reqId'));
            $pId = xssclean($this->input->post('pId'));
            $object = xssclean($this->input->post('crediData'));
            $credit_data = json_decode($object);
            $data = $this->RequestBomModel->updateManagementCreditDataa($eId, $reqId, $pId, $credit_data);
            echo json_encode($data);
        }
        
        public function updateAdvanceDetails() {
            //echo "<pre>"; print_r($_POST); exit;
            $eId = xssclean($this->input->post('enquiry_id'));
            $reqId = xssclean($this->input->post('reqId'));
            $pId = xssclean($this->input->post('pId'));
            $amt_pay = xssclean($this->input->post('amt_pay'));
            $object = xssclean($this->input->post('req_data'));
            
            $req_data = json_decode($object);
            
            //print_r($req_data); exit;
            $data = $this->RequestBomModel->updateAdvanceDetailss($eId, $reqId, $pId, $req_data);
            echo json_encode($data);
        }
        
        public function updateOthersDetails() {
            //echo "<pre>"; print_r($_POST); exit;
            $eId = xssclean($this->input->post('enquiry_id'));
            $reqId = xssclean($this->input->post('reqId'));
            $pId = xssclean($this->input->post('pId'));
            $amt_pay = xssclean($this->input->post('amt_pay'));
            $object = xssclean($this->input->post('others_data'));
            
            $others_data = json_decode($object);
            
            //print_r($req_data); exit;
            $data = $this->RequestBomModel->updateOthersDetailss($eId, $reqId, $pId, $others_data);
            echo json_encode($data);
        }
        
        public function updateBillDetails() {
            //echo "<pre>"; print_r($_POST); exit;
            $eId = xssclean($this->input->post('enquiry_id'));
            $reqId = xssclean($this->input->post('reqId'));
            $pId = xssclean($this->input->post('pId'));
            $amt_pay = xssclean($this->input->post('amt_pay'));
            $object = xssclean($this->input->post('bill_data'));
            
            $bill_data = json_decode($object);
            
            //print_r($req_data); exit;
            $data = $this->RequestBomModel->updateBillDetailss($eId, $reqId, $pId, $bill_data);
            echo json_encode($data);
        }
        
        
        public function updatePIList() {
            //echo "<pre>"; print_r($_POST); exit;
            $eId = xssclean($this->input->post('enquiry_id'));
            $reqId = xssclean($this->input->post('reqId'));
            $pId = xssclean($this->input->post('pId'));
            $object = xssclean($this->input->post('data'));
            
            $req_data = json_decode($object);
            //print_r($req_data); exit;
            $data = $this->RequestBomModel->updatePIListt($eId, $reqId, $pId, $req_data);
            echo json_encode($data);
        }
        
        public function getManagementRequestDetails() {
            $enqId = xssclean($this->input->post('enquiry_id'));
            $reqId = xssclean($this->input->post('request_id'));
            $data = $this->RequestBomModel->getManagementRequestDetailss($enqId, $reqId);
            echo json_encode($data);
        }
        
        public function updateManagementBomRequest() {
            //echo "<pre>"; print_r($_POST); exit;       
            $usertype = $_SESSION['UI']['usertype'];
            $id = xssclean($this->input->post('request_id'));
            $auth_status = xssclean($this->input->post('auth_status'));
            $auth_type = xssclean($this->input->post('auth_type'));
            $mgmt_remark = xssclean($this->input->post('mgmt_remark'));
            $bom_object = xssclean($this->input->post('bom_data'));
            $bom_data = json_decode($bom_object);
            $req_type = xssclean($this->input->post('req_type'));
            $cutoff_date = xssclean($this->input->post('cutoff_date'));
            $merchant_note = xssclean($this->input->post('merchant_note'));
            if($usertype == 2) {
                $data = $this->RequestBomModel->updateManagementBomRequestt($id, $auth_status, $auth_type, $mgmt_remark);    
            } else {
                $data = $this->RequestBomModel->updateManagementBomRequestt_new($id, $req_type, $cutoff_date, $merchant_note, $bom_data);    
            }
            
            echo json_encode($data);
        }

        public function getManagementPIList() {
            $data = $this->RequestBomModel->getManagementPIListt();
            echo json_encode($data);
        }
         public function getPIList() {
            $data = $this->RequestBomModel->getManagementPIListt();
            echo json_encode($data);
        }
        
        public function getMerchantPIList() {
            $data = $this->RequestBomModel->getMerchantPIListt();
            echo json_encode($data);
        }

        public function getFinanceReqRecList() {
            $data = $this->RequestBomModel->getFinanceReqRecListt();
            echo json_encode($data);
        }
         public function getFinanceReqRecList_bom1() {
            $data = $this->RequestBomModel->getFinanceReqRecListt_bom1();
            echo json_encode($data);
        }
        public function getFinanceReqRecList_bom2() {
            $data = $this->RequestBomModel->getFinanceReqRecListt_bom2();
            echo json_encode($data);
        }

        public function getMgmtBillPaidList() {
            $data = $this->RequestBomModel->getMgmtBillPaidListt();
            echo json_encode($data);
        }

        public function getMgmtBillPaidListBOM1() {
            $data = $this->RequestBomModel->getMgmtBillPaidListtBOM1();
            echo json_encode($data);
        }

        public function getMgmtBillPaidListBOM2() {
            $data = $this->RequestBomModel->getMgmtBillPaidListtBOM2();
            echo json_encode($data);
        }

         public function purchaseindent() {
            $this->load->view('request/bom/pi_list');
        }

        public function managamentpurchaseindent() {
                $data['brands'] = $this->getBrandList();

            $this->load->view('request/bom/mgmt_pi_list',$data);
        }

        public function managamentpurchaseindentdetails() {
            $VarId = $this->uri->segment(4);
            $reqId = $this->uri->segment(6);
            $purchaseId = $this->uri->segment(7);
            
            if ($VarId <> '' && is_numeric(base64_decode(urldecode($VarId))))
            {
                $VarEnqId = base64_decode(urldecode($VarId));
            }
            if ($reqId <> '' && is_numeric(base64_decode(urldecode($reqId))))
            {
                $reqEnqId = base64_decode(urldecode($reqId));
            }
            if ($purchaseId <> '' && is_numeric(base64_decode(urldecode($purchaseId))))
            {
                $pId = base64_decode(urldecode($purchaseId));
            }
             $subid=$this->subb_id;
            $subcompany_data = $this->RequestBomModel->getsubscribercompanydetail($subid);
            $Usertype=8;
            $bomstorelogin_data = $this->RequestBomModel->getuserdetail($Usertype,$subid);
             $Usertype1=7;
            $purchaselogin_data = $this->RequestBomModel->getuserdetail($Usertype1,$subid);

           //print_r($purchaselogin_data);
            $ArrCommonHeaderData = $this->commonheaderdata($VarEnqId);
            $requestData = $this->RequestBomModel->getRequestDataa_apprpi($VarEnqId, $reqId, $pId);
            $requesttype=$requestData[0]['type'];
             //print_r($requesttype);
             if($requesttype == 3){
                 $requesttypedata = '(Art - 1)';
             }else if($requesttype == 4){
                 $requesttypedata = '(Art - 2)';
             }
             //print_r($requesttypedata);


            $this->load->view('request/bom/mgmt_pi_details', 
                         array('VarEnqId'=>$VarEnqId,
                               'ArrCommonHeaderData' => $ArrCommonHeaderData,
                               'request_id' => $reqEnqId,
                               'pId' => $pId,
                               'requestData' => $requestData,
                               'requesttypedata' => $requesttypedata,
                               'subcompany_data' => $subcompany_data,
                               'bomstorelogin_data' => $bomstorelogin_data,
                               'purchaselogin_data' => $purchaselogin_data
                            ));
        }

        public function billpaidlist() {
           
            $data['brands'] = $this->getBrandList();
            $this->load->view('request/bom/bill_paid_list',$data);
        }

        public function billpaiddetails() {
            $VarId = $this->uri->segment(4);
            $reqId = $this->uri->segment(6);
            $pId = $this->uri->segment(7);
             $subid=$this->subb_id;

            if ($VarId <> '' && is_numeric(base64_decode(urldecode($VarId))))
            {
                $VarEnqId = base64_decode(urldecode($VarId));
            }
            if ($reqId <> '' && is_numeric(base64_decode(urldecode($reqId))))
            {
                $reqEnqId = base64_decode(urldecode($reqId));
            }
            if ($pId <> '' && is_numeric(base64_decode(urldecode($pId))))
            {
                $pId = base64_decode(urldecode($pId));
            }
            $ArrCommonHeaderData = $this->commonheaderdata($VarEnqId);
            $subcompany_data = $this->RequestBomModel->getsubscribercompanydetail($subid);

            $requestData = $this->RequestBomModel->getRequestDataa_apprpi($VarEnqId, $reqEnqId,$pId);
            $this->load->view('request/bom/bill_paid_details', 
                         array('VarEnqId'=>$VarEnqId,
                               'ArrCommonHeaderData' => $ArrCommonHeaderData,
                               'request_id' => $reqEnqId,
                               'pId' => $pId,
                               'requestData' => $requestData,
                               'subcompany_data' => $subcompany_data
                            ));
        }

        // *********************************************************************************************************** 
        // MANAGEMENT DEPARTMENT ENDS HERE 
        // **********************************************************************************************************//

        // *********************************************************************************************************** 
        // PURCHASE DEPARTMENT STARTS HERE 
        // **********************************************************************************************************//

        public function department() {
            $VarId = $this->uri->segment(4);
            $reqId = $this->uri->segment(6);
            if ($VarId <> '' && is_numeric(base64_decode(urldecode($VarId))))
            {
                $VarEnqId = base64_decode(urldecode($VarId));
            }
            if ($reqId <> '' && is_numeric(base64_decode(urldecode($reqId))))
            {
                $reqEnqId = base64_decode(urldecode($reqId));
            }
            $ArrCommonHeaderData = $this->commonheaderdata($VarEnqId);
            $requestData = $this->RequestBomModel->getRequestDataa($VarEnqId, $reqEnqId);
            $this->load->view('request/bom/purchase_receive_details', 
                         array('VarEnqId'=>$VarEnqId,
                               'ArrCommonHeaderData' => $ArrCommonHeaderData,
                               'request_id' => $reqEnqId,
                               'requestData' => $requestData
                            ));
        }

        public function getPIRequestSendDetails() {
            $reqId = xssclean($this->input->post('reqId'));
            $enqId = xssclean($this->input->post('enquiry_id'));
            $pId = xssclean($this->input->post('pId'));
            $data = $this->RequestBomModel->getPIRequestSendDetailss($enqId, $reqId, $pId);
            echo json_encode($data);
        }

         public function getPIRequestSendDetails_spi() {
            $reqId = xssclean($this->input->post('reqId'));
            $enqId = xssclean($this->input->post('enquiry_id'));
            $pId = xssclean($this->input->post('pId'));
            $data = $this->RequestBomModel->getPIRequestSendDetailss_spi($enqId, $reqId, $pId);
            echo json_encode($data);
        }

        public function getBillPaidDetails() {
            $reqId = xssclean($this->input->post('reqId'));
            $enqId = xssclean($this->input->post('enquiry_id'));
            $pId = xssclean($this->input->post('pId'));
            $data = $this->RequestBomModel->getBillPaidDetailss($enqId, $reqId, $pId);
            echo json_encode($data);
        }
        
        public function getMgmtPIApplDetails() {
            $reqId = xssclean($this->input->post('reqId'));
            $enqId = xssclean($this->input->post('enquiry_id'));
            $type = xssclean($this->input->post('type'));
            $pId = xssclean($this->input->post('pId'));
            $data = $this->RequestBomModel->getMgmtPIApplDetailss($enqId, $reqId, $type, $pId);
            echo json_encode($data);
        }

        public function getMgmtPIRequestSendDetails() {
            $reqId = xssclean($this->input->post('reqId'));
            $enqId = xssclean($this->input->post('enquiry_id'));
            $type = xssclean($this->input->post('type'));
            $pId = xssclean($this->input->post('pId'));
            $data = $this->RequestBomModel->getMgmtPIRequestSendDetailss($enqId, $reqId, $type, $pId);
            echo json_encode($data);
        }
        
        public function updateDepartmentPurchaseRequest() {
            $id = xssclean($this->input->post('request_id'));
            $req_status = xssclean($this->input->post('req_status'));
            $data = $this->RequestBomModel->updateDepartmentPurchaseRequestt($id, $req_status);
            echo json_encode($data);
        }
        
        public function purchaseQueueDetails() {
            $VarId = $this->uri->segment(4);
            $reqId = $this->uri->segment(6);
            if ($VarId <> '' && is_numeric(base64_decode(urldecode($VarId))))
            {
                $VarEnqId = base64_decode(urldecode($VarId));
            }
            if ($reqId <> '' && is_numeric(base64_decode(urldecode($reqId))))
            {
                $reqId = base64_decode(urldecode($reqId));
            }
            $subid=$this->subb_id;
           
  $subcompany_data = $this->RequestBomModel->getsubscribercompanydetail($subid);

            $ArrCommonHeaderData = $this->commonheaderdata($VarEnqId);
            $requestData = $this->RequestBomModel->getRequestDataa($VarEnqId, $reqId);
            $draft_status = $this->RequestBomModel->getDraftStatus($VarEnqId, $reqId);
            $getTotalStatus = $this->RequestBomModel->getTotalStatus($VarEnqId, $reqId);
             //print_r($getTotalStatus); exit;
            $this->load->view('request/bom/purchase_queue_details', 
                         array('VarEnqId'=>$VarEnqId,
                               'ArrCommonHeaderData' => $ArrCommonHeaderData,
                               'request_id' => $reqId,
                               'requestData' => $requestData,
                               'draft_status' => $draft_status,
                               'enqEncode' => $VarId,
                               'reqEncode' => $reqId,
                               'getTotalStatus' => $getTotalStatus,
                                'subcompany_data' => $subcompany_data,

                            ));
        }
        
        // *********************************************************************************************************** 
        // PURCHASE DEPARTMENT ENDS HERE 
        // **********************************************************************************************************//

        // *********************************************************************************************************** 
        // PURCHASE REQUEST SENT STARTS HERE 
        // **********************************************************************************************************//
        
        public function purchaseSentDetails() {
            $VarId = $this->uri->segment(4);
            $reqEnqId = $this->uri->segment(6);
            if ($VarId <> '' && is_numeric(base64_decode(urldecode($VarId))))
            {
                $VarEnqId       = base64_decode(urldecode($VarId));
                $reqEnqId       = base64_decode(urldecode($reqEnqId));
            }
            $ArrCommonHeaderData = $this->commonheaderdata($VarEnqId);
            $requestData = $this->RequestBomModel->getRequestDataa($VarEnqId, $reqEnqId);
            $this->load->view('request/purchase/draftpi',
                         array('VarEnqId'=>$VarEnqId, 
                                'ArrCommonHeaderData' => $ArrCommonHeaderData, 
                                'requestData' => $requestData,
                                'enqEncode' => $VarId,
                                'reqEncode' => $reqEnqId,
                            ));
        }
        

        public function purchaseindentlist() {
            $this->load->view('request/bom/purchase_pi_list');
        }

        public function purchaseindentdetails() {
            $VarId = $this->uri->segment(4);
            $reqId = $this->uri->segment(6);
            $purchaseId = $this->uri->segment(7);
            $subid=$this->subb_id;

            if ($VarId <> '' && is_numeric(base64_decode(urldecode($VarId))))
            {
                $VarEnqId = base64_decode(urldecode($VarId));
            }
            if ($reqId <> '' && is_numeric(base64_decode(urldecode($reqId))))
            {
                $reqEnqId = base64_decode(urldecode($reqId));
            }
            if ($purchaseId <> '' && is_numeric(base64_decode(urldecode($purchaseId))))
            {
                $pId = base64_decode(urldecode($purchaseId));
            }
            $ArrCommonHeaderData = $this->commonheaderdata($VarEnqId);
              $subcompany_data = $this->RequestBomModel->getsubscribercompanydetail($subid);
              $Usertype=8;
              $bomstorelogin_data = $this->RequestBomModel->getuserdetail($Usertype,$subid);


            $requestData = $this->RequestBomModel->getRequestDataa_apprpi($VarEnqId, $reqEnqId, $pId);
            $requesttype=$requestData[0]['type'];
             //print_r($requesttype);
             if($requesttype == 3){
                 $requesttypedata = '(Art - 1)';
             }else if($requesttype == 4){
                 $requesttypedata = '(Art - 2)';
             }

            $this->load->view('request/purchase/purchase_indent_details', 
                         array('VarEnqId'=>$VarEnqId,
                               'ArrCommonHeaderData' => $ArrCommonHeaderData,
                               'request_id' => $reqEnqId,
                               'pId' => $pId,
                               'requestData' => $requestData,
                               'requesttypedata' => $requesttypedata,
                               'subcompany_data' => $subcompany_data,
                               'bomstorelogin_data' => $bomstorelogin_data,
                            ));
        }

        // *********************************************************************************************************** 
        // PURCHASE REQUEST SENT ENDS HERE 
        // **********************************************************************************************************//
        
        // *********************************************************************************************************** 
        // MERCHANT PURCHASE REQUEST SENT STARTS HERE 
        // **********************************************************************************************************//

        public function merchantpurchaseindentlist() {
            
            $data['brands'] = $this->getBrandList();
            $this->load->view('request/bom/merchant_purchase_pi_list',$data);
        }
        public function getBrandList() 
    {
        header('Access-Control-Allow-Origin: *');
        $output = $this->RequestBomModel->getBrandListt();
        return $output;
    }

        public function merchantpurchaseindentdetails_old() {

                $VarId = $this->uri->segment(4);
            $reqId = $this->uri->segment(6);
            if ($VarId <> '' && is_numeric(base64_decode(urldecode($VarId))))
            {
                $VarEnqId = base64_decode(urldecode($VarId));
            }
            if ($reqId <> '' && is_numeric(base64_decode(urldecode($reqId))))
            {
                $reqEnqId = base64_decode(urldecode($reqId));
            }
            $ArrCommonHeaderData = $this->commonheaderdata($VarEnqId);
            $requestData = $this->RequestBomModel->getRequestDataa($VarEnqId, $reqEnqId);
          
            $this->load->view('request/bom/merchant_purchase_pi_details', 
                         array('VarEnqId'=>$VarEnqId,
                               'ArrCommonHeaderData' => $ArrCommonHeaderData,
                               'request_id' => $reqEnqId,
                               'requestData' => $requestData
                            ));
        }
         public function merchantpurchaseindentdetails() {
            $VarId = $this->uri->segment(4);
            $reqId = $this->uri->segment(6);
            $purchaseId = $this->uri->segment(7);
            if ($VarId <> '' && is_numeric(base64_decode(urldecode($VarId))))
            {
                $VarEnqId = base64_decode(urldecode($VarId));
            }
            if ($reqId <> '' && is_numeric(base64_decode(urldecode($reqId))))
            {
                $reqEnqId = base64_decode(urldecode($reqId));
            }
            if ($purchaseId <> '' && is_numeric(base64_decode(urldecode($purchaseId))))
            {
                $pId = base64_decode(urldecode($purchaseId));
            }
            $ArrCommonHeaderData = $this->commonheaderdata($VarEnqId);
             $subid=$this->subb_id;
            $subcompany_data = $this->RequestBomModel->getsubscribercompanydetail($subid);
            $Usertype=8;
            $bomstorelogin_data = $this->RequestBomModel->getuserdetail($Usertype,$subid);
            $Usertype1=7;
            $purchaselogin_data = $this->RequestBomModel->getuserdetail($Usertype1,$subid);


           $requestData = $this->RequestBomModel->getRequestDataa_apprpi($VarEnqId, $reqId, $pId);
             $requesttype=$requestData[0]['type'];
             //print_r($requesttype);
             if($requesttype == 3){
                 $requesttypedata = '(Art - 1)';
             }else if($requesttype == 4){
                 $requesttypedata = '(Art - 2)';
             }
            $this->load->view('request/bom/merchant_purchase_pi_details', 
                         array('VarEnqId'=>$VarEnqId,
                               'ArrCommonHeaderData' => $ArrCommonHeaderData,
                               'request_id' => $reqEnqId,
                               'pId' => $pId,
                               'requesttypedata' => $requesttypedata,
                               'requestData' => $requestData,
                               'subcompany_data' => $subcompany_data,
                               'bomstorelogin_data' => $bomstorelogin_data,
                               'purchaselogin_data' => $purchaselogin_data
                            ));
        }

        // *********************************************************************************************************** 
        // MERCHANT PURCHASE REQUEST SENT ENDS HERE 
        // **********************************************************************************************************//

        // ***********************************************************************************************************
        // BOM FINANCE DEPARTMENT STARTS HERE
        // **********************************************************************************************************//
        public function getPaymentReceivedList() {
            $data = $this->RequestBomModel->getPaymentReceivedListt();
            echo json_encode($data);
        }
        public function paymentReceiveList() {
            $VarId = $this->uri->segment(4);
            $reqId = $this->uri->segment(6);
            if ($VarId <> '' && is_numeric(base64_decode(urldecode($VarId))))
            {
                $VarEnqId = base64_decode(urldecode($VarId));
            }
            if ($reqId <> '' && is_numeric(base64_decode(urldecode($reqId))))
            {
                $reqEnqId = base64_decode(urldecode($reqId));
            }
            $ArrCommonHeaderData = $this->commonheaderdata($VarEnqId);
            $requestData = $this->RequestBomModel->getRequestDataa($VarEnqId, $reqEnqId);
            $this->load->view('request/bom/finance/receive_list_details',
                         array('VarEnqId'=>$VarEnqId,
                               'ArrCommonHeaderData' => $ArrCommonHeaderData,
                               'request_id' => $reqEnqId,
                               'requestData' => $requestData
                            ));
        }
        public function getPaymentRequestReceiveDetails() {
            $reqId = xssclean($this->input->post('reqId'));
            $enqId = xssclean($this->input->post('enquiry_id'));
            $data = $this->RequestBomModel->getPaymentRequestReceiveDetailss($enqId, $reqId);
            echo json_encode($data);
        }
        public function updatePaymentAdvanceDetails() {
            $object = xssclean($this->input->post('data'));
            $reqId = xssclean($this->input->post('reqId'));
            $enqId = xssclean($this->input->post('enquiry_id'));
            $req_data = json_decode($object);
            $data = $this->RequestBomModel->updatePaymentAdvanceDetailss($req_data, $enqId, $reqId);
            echo json_encode($data);
        }
        public function getAdvancePadiList() {
            $data = $this->RequestBomModel->getAdvancePadiListt();
            echo json_encode($data);
        }
        public function advanceDetails() {
            $VarId = $this->uri->segment(4);
            $reqId = $this->uri->segment(6);
            if ($VarId <> '' && is_numeric(base64_decode(urldecode($VarId))))
            {
                $VarEnqId = base64_decode(urldecode($VarId));
            }
            if ($reqId <> '' && is_numeric(base64_decode(urldecode($reqId))))
            {
                $reqEnqId = base64_decode(urldecode($reqId));
            }
            $ArrCommonHeaderData = $this->commonheaderdata($VarEnqId);
            $requestData = $this->RequestBomModel->getRequestDataa($VarEnqId, $reqEnqId);
            $this->load->view('request/bom/finance/advance_paid_details',
                         array('VarEnqId'=>$VarEnqId,
                               'ArrCommonHeaderData' => $ArrCommonHeaderData,
                               'request_id' => $reqEnqId,
                               'requestData' => $requestData
                            ));
        }
        public function getAdvancePaidDetails() {
            $reqId = xssclean($this->input->post('reqId'));
            $enqId = xssclean($this->input->post('enquiry_id'));
            $data = $this->RequestBomModel->getAdvancePaidDetailss($enqId, $reqId);
            echo json_encode($data);
        }

        // ***********************************************************************************************************
        // BOM FINANCE DEPARTMENT ENDS HERE
        // **********************************************************************************************************//

        // *********************************************************************************************************** 
        // STORE DEPARTMENT STARTS HERE 
        // **********************************************************************************************************//
        
        public function bompurchaseindentlist() {
            $this->load->view('request/bom/store/pilist');
        }
        
        public function getStorepiList() {
            $data = $this->RequestBomModel->getStorepiListt();
            echo json_encode($data);
        }

        public function storePiDetails() {
            $VarId = $this->uri->segment(4);
            $reqId = $this->uri->segment(6);
            if ($VarId <> '' && is_numeric(base64_decode(urldecode($VarId))))
            {
                $VarEnqId = base64_decode(urldecode($VarId));
            }
            if ($reqId <> '' && is_numeric(base64_decode(urldecode($reqId))))
            {
                $reqEnqId = base64_decode(urldecode($reqId));
            }
            $ArrCommonHeaderData = $this->commonheaderdata($VarEnqId);
            $requestData = $this->RequestBomModel->getRequestDataa($VarEnqId, $reqEnqId);
            $this->load->view('request/bom/store/advance_paid_details', 
                         array('VarEnqId'=>$VarEnqId,
                               'ArrCommonHeaderData' => $ArrCommonHeaderData,
                               'request_id' => $reqEnqId,
                               'requestData' => $requestData
                            ));
        }

        public function storpurchaseindentdetails()
        {
            $VarId = $this->uri->segment(4);
            $reqId = $this->uri->segment(6);
            if ($VarId <> '' && is_numeric(base64_decode(urldecode($VarId))))
            {
                $VarEnqId = base64_decode(urldecode($VarId));
            }
            if ($reqId <> '' && is_numeric(base64_decode(urldecode($reqId))))
            {
                $reqEnqId = base64_decode(urldecode($reqId));
            }
            $ArrCommonHeaderData = $this->commonheaderdata($VarEnqId);
            $requestData = $this->RequestBomModel->getRequestDataa($VarEnqId, $reqEnqId);
            $this->load->view('request/bom/store/pi_details', 
                         array('VarEnqId'=>$VarEnqId,
                               'ArrCommonHeaderData' => $ArrCommonHeaderData,
                               'request_id' => $reqEnqId,
                               'requestData' => $requestData
                            ));
        }

        public function editstorepidetails()
        {
            $VarId = $this->uri->segment(4);
            $reqId = $this->uri->segment(6);
            if ($VarId <> '' && is_numeric(base64_decode(urldecode($VarId))))
            {
                $VarEnqId = base64_decode(urldecode($VarId));
            }
            if ($reqId <> '' && is_numeric(base64_decode(urldecode($reqId))))
            {
                $reqEnqId = base64_decode(urldecode($reqId));
            }
            $ArrCommonHeaderData = $this->commonheaderdata($VarEnqId);
            $requestData = $this->RequestBomModel->getRequestDataa($VarEnqId, $reqEnqId);
            $this->load->view('request/bom/store/edit_pi_details', 
                         array('VarEnqId'=>$VarEnqId,
                               'ArrCommonHeaderData' => $ArrCommonHeaderData,
                               'request_id' => $reqEnqId,
                               'requestData' => $requestData
                            ));
        }

        public function updateStorePiDetails() {
            //echo "<pre>"; print_r($_POST); exit;
            $object = xssclean($this->input->post('inHouseData'));
            $object2 = xssclean($this->input->post('itemAccept'));
            $object3 = xssclean($this->input->post('inHouseConsolidate'));
            $enqId = xssclean($this->input->post('enquiry_id'));
            $reqId = xssclean($this->input->post('reqId'));
            $pId = xssclean($this->input->post('pId'));
            $req_data = json_decode($object);
            $req_data2 = json_decode($object2);
            $req_data3 = json_decode($object3);
            $data = $this->RequestBomModel->updateStorePiDetailss($req_data, $req_data2, $req_data3, $enqId, $reqId, $pId);
            echo json_encode($data);
        }

        public function moveToOrderStockList() {
           // echo "<pre>"; print_r($_POST); exit;
            $object = xssclean($this->input->post('inHouseStatus'));
            $enqId = xssclean($this->input->post('enquiry_id'));
            $reqId = xssclean($this->input->post('reqId'));
            $pId = xssclean($this->input->post('pId'));
            $req_data = json_decode($object);
            $data = $this->RequestBomModel->moveToOrderStockListt($req_data, $enqId, $reqId, $pId);
            echo json_encode($data);
        }

        public function newitemdetails() {
            $VarId = $this->uri->segment(4);
            $reqId = $this->uri->segment(6);
            if ($VarId <> '' && is_numeric(base64_decode(urldecode($VarId))))
            {
                $VarEnqId = base64_decode(urldecode($VarId));
            }
            if ($reqId <> '' && is_numeric(base64_decode(urldecode($reqId))))
            {
                $reqEnqId = base64_decode(urldecode($reqId));
            }
            $ArrCommonHeaderData = $this->commonheaderdata($VarEnqId);
            $requestData = $this->RequestBomModel->getRequestDataa($VarEnqId, $reqEnqId);
            $this->load->view('request/bom/store/newitemdetails', 
                         array('VarEnqId'=>$VarEnqId,
                               'ArrCommonHeaderData' => $ArrCommonHeaderData,
                               'request_id' => $reqEnqId,
                               'requestData' => $requestData
                            ));
        }

        public function getNewItemDetails() {
            $reqId = xssclean($this->input->post('reqId'));
            $enqId = xssclean($this->input->post('enquiry_id'));
            $data = $this->RequestBomModel->getNewItemDetailss($enqId, $reqId);
            echo json_encode($data);
        }

        public function surplusstocklist() {
            $this->load->view('request/bom/store/surplusstocklist');
        }       

        public function surplusstockdetails() {
            $VarId = $this->uri->segment(4);
            $reqId = $this->uri->segment(6);
            $itemCode = $this->uri->segment(8);
            $pIds = $this->uri->segment(10);
            if ($VarId <> '' && is_numeric(base64_decode(urldecode($VarId))))
            {
                $VarEnqId = base64_decode(urldecode($VarId));
            }
            //print_r($VarEnqId);
            if ($reqId <> '' && is_numeric(base64_decode(urldecode($reqId))))
            {
                $reqEnqId = base64_decode(urldecode($reqId));
            }
            if ($itemCode <> '' && base64_decode(urldecode($itemCode)))
            {
                $itemCodes = base64_decode(urldecode($itemCode));
            }
            if ($pIds <> '' && base64_decode(urldecode($pIds)))
            {
                $pId = base64_decode(urldecode($pIds));
            }
            $itemCodes = "'".$itemCodes."'";
             $subid=$this->subb_id;
           
  $subcompany_data = $this->RequestBomModel->getsubscribercompanydetail($subid);

            $ArrCommonHeaderData = $this->commonheaderdata($VarEnqId);
            $requestData = $this->RequestBomModel->getRequestDataa($VarEnqId, $reqEnqId);
            $draftData = $this->RequestBomModel->getsurplusDraftCountt($itemCodes);
            $draftCount = count($draftData); 
            $this->load->view('request/bom/store/surplusstockdetails', 
                         array('VarEnqId'=>$VarEnqId,
                               'ArrCommonHeaderData' => $ArrCommonHeaderData,
                               'request_id' => $reqEnqId,
                               'pId' => $pId,
                               'itemCode' => $itemCodes,
                               'requestData' => $requestData,
                               'draftCount' => $draftCount,
                                'subcompany_data' => $subcompany_data,

                            ));
        }

        public function getSurplusStockDetails() {
            $reqId = xssclean($this->input->post('reqId'));
            $enqId = xssclean($this->input->post('enquiry_id'));
            $pId = xssclean($this->input->post('pId'));
            $itemCode = xssclean($this->input->post('itemCode'));
            $data = $this->RequestBomModel->getSurplusStockDetailss($enqId, $reqId, $pId, $itemCode);
            echo json_encode($data);
        }

         public function updateOrderClosestatus() {
            $enqId = xssclean($this->input->post('enquiry_id'));
            $itemCode = xssclean($this->input->post('itemCode'));
            $object = xssclean($this->input->post('inHouseConsolidate'));
            $req_data = json_decode($object);
            //print_r($req_data);


            $data = $this->RequestBomModel->updateOrderClosestatus($req_data, $enqId, $itemCode);
            echo json_encode($data);
        }

        // *********************************************************************************************************** 
        // STORE DEPARTMENT STARTS HERE 
        // **********************************************************************************************************//

        public function getPurchaseRequestSentDetails() {
            $enqId = xssclean($this->input->post('enquiry_id'));
            $reqId = xssclean($this->input->post('reqId'));
            $data = $this->RequestBomModel->getPurchaseRequestSentDetailss($enqId, $reqId);
            echo json_encode($data);
        }

        public function departmentapproval() {
            $VarId = $this->uri->segment(4);
            $reqId = $this->uri->segment(6);
            $purchaseId = $this->uri->segment(7);
            if ($VarId <> '' && is_numeric(base64_decode(urldecode($VarId))))
            {
                $VarEnqId = base64_decode(urldecode($VarId));
            }
            if ($reqId <> '' && is_numeric(base64_decode(urldecode($reqId))))
            {
                $reqId = base64_decode(urldecode($reqId));
            }
            $subid=$this->subb_id;
           
          $subcompany_data = $this->RequestBomModel->getsubscribercompanydetail($subid);
            $ArrCommonHeaderData = $this->commonheaderdata($VarEnqId);
            $requestData = $this->RequestBomModel->getRequestDataa($VarEnqId, $reqId);
            $this->load->view('request/bom/dept_approval', 
                         array('VarEnqId'=>$VarEnqId,
                               'ArrCommonHeaderData' => $ArrCommonHeaderData,
                               'reqId' => $reqId,
                               'requestData' => $requestData,
                                'subcompany_data' => $subcompany_data,
                            ));
        }

        public function updateDeptBOMRequest() {
            $enquiry_id = xssclean($this->input->post('enquiry_id'));
            $reqId = xssclean($this->input->post('request_id'));
            $req_status = xssclean($this->input->post('req_status'));
            $data = $this->RequestBomModel->updateDeptBOMRequestt($enquiry_id, $reqId, $req_status);
            echo json_encode($data);
        }

        public function getPurchaseBomQueueDetails() {
            $reqId = xssclean($this->input->post('reqId'));
            $enqId = xssclean($this->input->post('enquiry_id'));
            $data = $this->RequestBomModel->getPurchaseBomQueueDetailss($enqId, $reqId);
            echo json_encode($data);
        }

        public function updateDeptNoteequest() {
            $reqId = xssclean($this->input->post('request_id'));
            $dep_remarks = xssclean($this->input->post('dep_remarks'));
            $data = $this->RequestBomModel->updateDeptNoteequestt($reqId, $dep_remarks);
            echo json_encode($data);
        }

        public function requestsentdetails() {
            $VarId = $this->uri->segment(4);
            $reqId = $this->uri->segment(6);
            $subid=$this->subb_id;

            $purchaseId = $this->uri->segment(7);
            if ($VarId <> '' && is_numeric(base64_decode(urldecode($VarId))))
            {
                $VarEnqId = base64_decode(urldecode($VarId));
            }
            if ($reqId <> '' && is_numeric(base64_decode(urldecode($reqId))))
            {
                $reqId = base64_decode(urldecode($reqId));
            }
            if ($purchaseId <> '' && is_numeric(base64_decode(urldecode($purchaseId))))
            {
                $pId = base64_decode(urldecode($purchaseId));
            }
            $subcompany_data = $this->RequestBomModel->getsubscribercompanydetail($subid);

            $ArrCommonHeaderData = $this->commonheaderdata($VarEnqId);
            $requestData = $this->RequestBomModel->getRequestDataa_pi($VarEnqId, $reqId, $pId);
            $requesttype=$requestData[0]['type'];
             //print_r($requesttype);
             if($requesttype == 3){
                 $requesttypedata = '(Art - 1)';
             }else if($requesttype == 4){
                 $requesttypedata = '(Art - 2)';
             }
            // echo "<pre>"; print_r($requestData); exit;
            $this->load->view('request/bom/request_sent_details', 
                         array('VarEnqId'=>$VarEnqId,
                               'ArrCommonHeaderData' => $ArrCommonHeaderData,
                               'request_id' => $reqId,
                               'pId' => $pId,
                               'requestData' => $requestData,
                               'requesttypedata' => $requesttypedata,
                               'subcompany_data' => $subcompany_data
                            ));
        }

        public function financereqreceiveddetails() {
            $VarId = $this->uri->segment(4);
            $reqId = $this->uri->segment(6);
            $purchaseId = $this->uri->segment(7);
            if ($VarId <> '' && is_numeric(base64_decode(urldecode($VarId))))
            {
                $VarEnqId = base64_decode(urldecode($VarId));
            }
            if ($reqId <> '' && is_numeric(base64_decode(urldecode($reqId))))
            {
                $reqEnqId = base64_decode(urldecode($reqId));
            }
            if ($purchaseId <> '' && is_numeric(base64_decode(urldecode($purchaseId))))
            {
                $pId = base64_decode(urldecode($purchaseId));
            }
            //echo $pId; exit;
             $subid=$this->subb_id;
           $subcompany_data = $this->RequestBomModel->getsubscribercompanydetail($subid);
           $ArrCommonHeaderData = $this->commonheaderdata($VarEnqId);
            $requestData = $this->RequestBomModel->getRequestDataa_apprpi($VarEnqId, $reqEnqId, $pId);
            $requesttype=$requestData[0]['type'];
             //print_r($requesttype);
             if($requesttype == 3){
                 $requesttypedata = '(Art - 1)';
             }else if($requesttype == 4){
                 $requesttypedata = '(Art - 2)';
             }

            $this->load->view('request/finance/request_received_details',
                        array('VarEnqId'=>$VarEnqId,
                            'ArrCommonHeaderData' => $ArrCommonHeaderData,
                            'request_id' => $reqEnqId,
                            'pId' => $pId,
                            'requestData' => $requestData,
                            'requesttypedata' => $requesttypedata,
                            'subcompany_data' => $subcompany_data
                            ));
        }

        public function storepurchaseindentdetails() {
            $VarId = $this->uri->segment(4);
            $reqId = $this->uri->segment(6);
               $subid=$this->subb_id;
            $purchaseId = $this->uri->segment(7);
            if ($VarId <> '' && is_numeric(base64_decode(urldecode($VarId))))
            {
                $VarEnqId = base64_decode(urldecode($VarId));
            }
            if ($reqId <> '' && is_numeric(base64_decode(urldecode($reqId))))
            {
                $reqEnqId = base64_decode(urldecode($reqId));
            }
            if ($purchaseId <> '' && is_numeric(base64_decode(urldecode($purchaseId))))
            {
                $pId = base64_decode(urldecode($purchaseId));
            }
            $subcompany_data = $this->RequestBomModel->getsubscribercompanydetail($subid);
         
            $ArrCommonHeaderData = $this->commonheaderdata($VarEnqId);
            $requestData = $this->RequestBomModel->getRequestDataa_apprpi($VarEnqId, $reqId, $pId);
            $requesttype=$requestData[0]['type'];
             //print_r($requesttype);
             if($requesttype == 3){
                 $requesttypedata = '(Art - 1)';
             }else if($requesttype == 4){
                 $requesttypedata = '(Art - 2)';
             }

            $this->load->view('request/bom/store/purchase_indent_details',
                        array('VarEnqId'=>$VarEnqId,
                            'ArrCommonHeaderData' => $ArrCommonHeaderData,
                            'request_id' => $reqEnqId,
                            'pId' => $pId,
                            'requestData' => $requestData,
                            'requesttypedata' => $requesttypedata,
                            'subcompany_data' => $subcompany_data
                            ));
        }
        public function storepurchaseindentdetailspi() {
            $VarId = $this->uri->segment(4);
            $reqId = $this->uri->segment(6);
               $subid=$this->subb_id;
            $purchaseId = $this->uri->segment(7);
            if ($VarId <> '' && is_numeric(base64_decode(urldecode($VarId))))
            {
                $VarEnqId = base64_decode(urldecode($VarId));
            }
            if ($reqId <> '' && is_numeric(base64_decode(urldecode($reqId))))
            {
                $reqEnqId = base64_decode(urldecode($reqId));
            }
            if ($purchaseId <> '' && is_numeric(base64_decode(urldecode($purchaseId))))
            {
                $pId = base64_decode(urldecode($purchaseId));
            }
            $subcompany_data = $this->RequestBomModel->getsubscribercompanydetail($subid);
         
            $ArrCommonHeaderData = $this->commonheaderdata($VarEnqId);
            $requestData = $this->RequestBomModel->getRequestDataa_apprpi($VarEnqId, $reqId, $pId);
            $requesttype=$requestData[0]['type'];
             //print_r($requesttype);
             if($requesttype == 3){
                 $requesttypedata = '(Art - 1)';
             }else if($requesttype == 4){
                 $requesttypedata = '(Art - 2)';
             }

            $this->load->view('request/bom/store/purchase_indent_details',
                        array('VarEnqId'=>$VarEnqId,
                            'ArrCommonHeaderData' => $ArrCommonHeaderData,
                            'request_id' => $reqEnqId,
                            'pId' => $pId,
                            'requestData' => $requestData,
                            'requesttypedata' => $requesttypedata,
                            'subcompany_data' => $subcompany_data
                            ));
        }


         public function storepurchaseindentdetails_print() {
             $ArrLoggedUserInfo        = fnGetUserLoggedInfo(1);
             $subid=$this->subb_id;
           
        
             $VarEnqId = $_POST['VarEnqId'];
             $reqId = $_POST['request_id'];
             $pId = $_POST['pId'];
               $subid=$this->subb_id;
            $output['company_data'] = $this->commonheaderdata($VarEnqId);
            $output['subcompany_data'] = $this->RequestBomModel->getsubscribercompanydetail($subid);
            $output['purchase_data'] = $this->RequestBomModel->getRequestDataa_apprpi($VarEnqId, $reqId, $pId);
           $output['newpurchase_data'] = $this->RequestBomModel->getPIRequestSendDetailss($enqId, $reqId, $pId);
            $vendors = $output['newpurchase_data']['vendor_data'];
            $vendorid = $output['purchase_data'][0]['vendor_id'];
            $vendorids = $vendorid;
            $filtered = array_filter($vendors, function($vendor) use ($vendorids) {
       return $vendor['id'] == $vendorids;
       });
       $filtered = array_values($filtered); // optional

              $output['vendor_data']=$filtered[0];

         
           $this->load->view('request/bom/store/purchase_indent_details_print',$output);
           
        }
         public function storepurchaseindentdetails_pdf() {
             $VarEnqId = $_POST['VarEnqId'];
             $reqId = $_POST['request_id'];
             $pId = $_POST['pId'];
               $subid=$this->subb_id;
           $usertype=7;
           $output['company_data'] = $this->commonheaderdata($VarEnqId);
           $output['subcompany_data'] = $this->RequestBomModel->getsubscribercompanydetail($subid);
           
           $output['purchase_data'] = $this->RequestBomModel->getRequestDataa_apprpi($VarEnqId, $reqId, $pId);
           $output['newpurchase_data'] = $this->RequestBomModel->getPIRequestSendDetailss($enqId, $reqId, $pId);
           $vendors = $output['newpurchase_data']['vendor_data'];
           $vendorid = $output['purchase_data'][0]['vendor_id'];
            $vendorids = $vendorid;
           $filtered = array_filter($vendors, function($vendor) use ($vendorids) {
       return $vendor['id'] == $vendorids;
       });
       $filtered = array_values($filtered); // optional

              $output['vendor_data']=$filtered[0];

              //print_r($output['vendor_data']);



         
           $this->load->view('request/bom/store/purchase_indent_details_pdf',$output);
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
         public function surpluspurchaseindentdetailswip() {
            $VarId = $this->uri->segment(4);
            $reqId = $this->uri->segment(6);
            $purchaseId = $this->uri->segment(7);
            $subid=$this->subb_id;
            if ($VarId <> '' && is_numeric(base64_decode(urldecode($VarId))))
            {
                $VarEnqId = base64_decode(urldecode($VarId));
            }
            if ($reqId <> '' && is_numeric(base64_decode(urldecode($reqId))))
            {
                $reqEnqId = base64_decode(urldecode($reqId));
            }
            if ($purchaseId <> '' && is_numeric(base64_decode(urldecode($purchaseId))))
            {
                $pId = base64_decode(urldecode($purchaseId));
            }
            $ArrCommonHeaderData = $this->commonheaderdata($VarEnqId);
            $subcompany_data = $this->RequestBomModel->getsubscribercompanydetail($subid);
            $Usertype=8;
            $bomstorelogin_data = $this->RequestBomModel->getuserdetail($Usertype,$subid);



            $requestData = $this->RequestBomModel->getRequestDataa_apprpi($VarEnqId, $reqId, $pId);
            $requesttype=$requestData[0]['type'];
             //print_r($requesttype);
             if($requesttype == 3){
                 $requesttypedata = '(Art - 1)';
             }else if($requesttype == 4){
                 $requesttypedata = '(Art - 2)';
             }

            $this->load->view('request/bom/store/purchase_indent_details',
                        array('VarEnqId'=>$VarEnqId,
                            'ArrCommonHeaderData' => $ArrCommonHeaderData,
                            'request_id' => $reqEnqId,
                            'pId' => $pId,
                            'requestData' => $requestData,
                            'requesttypedata' => $requesttypedata,
                            'subcompany_data' => $subcompany_data,
                            'bomstorelogin_data' => $bomstorelogin_data
                            ));
        }
        public function surpluspurchaseindentdetailspiref() {
            $VarId = $this->uri->segment(4);
            $reqId = $this->uri->segment(6);
            $purchaseId = $this->uri->segment(7);
            $Usertype=7;
            $subid=$this->subb_id;
            //print_r($purchaseId);
            if ($VarId <> '' && is_numeric(base64_decode(urldecode($VarId))))
            {
                $VarEnqId = base64_decode(urldecode($VarId));
            }
            if ($reqId <> '' && is_numeric(base64_decode(urldecode($reqId))))
            {
                $reqEnqId = base64_decode(urldecode($reqId));
            }
            if ($purchaseId <> '' && is_numeric(base64_decode(urldecode($purchaseId))))
            {
                $pId = base64_decode(urldecode($purchaseId));
            }

            $draf_dc = $this->RequestBomModel->getstmstatus($VarEnqId, $reqId, $pId);

            $subcompany_data = $this->RequestBomModel->getsubscribercompanydetail($subid);
            $purchaselogin_data = $this->RequestBomModel->getuserdetail($Usertype,$subid);


            $s_s_status = $this->RequestBomModel->getsplstatus($VarEnqId, $reqId, $pId);
            $move_sst_status = $this->RequestBomModel->getsststatus($VarEnqId, $reqId, $pId);
            $dcstatus_sst_status = $this->RequestBomModel->getdcsststatus($VarEnqId, $reqId, $pId);
            //print_r($dcstatus_sst_status);
            $ArrCommonHeaderData = $this->commonheaderdata($VarEnqId);
            $requestData = $this->RequestBomModel->getRequestDataa_apprpi($VarEnqId, $reqId, $pId);
            $requesttype=$requestData[0]['type'];
             //print_r($requesttype);
             if($requesttype == 3){
                 $requesttypedata = '(Art - 1)';
             }else if($requesttype == 4){
                 $requesttypedata = '(Art - 2)';
             }

            $this->load->view('request/bom/store/purchase_indent_details_surplus',
                        array('VarEnqId'=>$VarEnqId,
                            'ArrCommonHeaderData' => $ArrCommonHeaderData,
                            'surplustockstatus' => $s_s_status,
                            'movesststatus' => $move_sst_status,
                            'dcmovesststatus' => $dcstatus_sst_status,
                            'request_id' => $reqEnqId,
                            'pId' => $pId,
                            'requestData' => $requestData,
                            'draf_dc' => $draf_dc,
                            'requesttypedata' => $requesttypedata,
                            'subcompany_data' => $subcompany_data,
                            'purchaselogin_data' => $purchaselogin_data
                            
                            
                            ));
        }

         public function surpluspurchaseindentdetailspiref_print() {

            $VarEnqId = $_POST['VarEnqId'];
            $reqId = $_POST['request_id'];
            $pId = $_POST['pId'];
            $Usertype=7;
           $subid=$this->subb_id;


            //print_r($sub_id);
             
            
               $output['company_data'] = $this->commonheaderdata($VarEnqId);
               $output['purchaselogin_data'] = $this->RequestBomModel->getuserdetail($Usertype,$subid);
                $output['subcompany_data'] = $this->RequestBomModel->getsubscribercompanydetail($subid);

           
               $output['purchase_data'] = $this->RequestBomModel->getRequestDataa_apprpi($VarEnqId, $reqId, $pId);
               $output['spipurchase_data'] = $this->RequestBomModel->getPIRequestSendDetailss_spi($VarEnqId, $reqId, $pId);;
          //print_r($output['purchaselogin_data']);
                  $this->load->view('request/bom/store/purchase_indent_details_surplus_print',$output);
          

            
           
           
           
        }
        public function surpluspurchaseindentdetailspiref_pdf() {

           $VarEnqId = $_POST['VarEnqId'];
            $reqId = $_POST['request_id'];
            $pId = $_POST['pId'];
            $Usertype=7;
             $subid=$this->subb_id;

             
            
               $output['company_data'] = $this->commonheaderdata($VarEnqId);
               $output['purchaselogin_data'] = $this->RequestBomModel->getuserdetail($Usertype,$subid);
                $output['subcompany_data'] = $this->RequestBomModel->getsubscribercompanydetail($subid);

               $output['purchase_data'] = $this->RequestBomModel->getRequestDataa_apprpi($VarEnqId, $reqId, $pId);
               $output['spipurchase_data'] = $this->RequestBomModel->getPIRequestSendDetailss_spi($VarEnqId, $reqId, $pId);;
          //print_r($output['purchaselogin_data']);
            
                  $this->load->view('request/bom/store/purchase_indent_details_surplus_pdf',$output);
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




         public function surplusissuedetails() {
            $VarId = $this->uri->segment(4);
            $reqId = $this->uri->segment(6);
            $purchaseId = $this->uri->segment(8);
            //print_r($purchaseId);
            if ($VarId <> '' && is_numeric(base64_decode(urldecode($VarId))))
            {
                $VarEnqId = base64_decode(urldecode($VarId));
            }
            if ($reqId <> '' && is_numeric(base64_decode(urldecode($reqId))))
            {
                $reqEnqId = base64_decode(urldecode($reqId));
            }
            if ($purchaseId <> '' && is_numeric(base64_decode(urldecode($purchaseId))))
            {
                $pId = base64_decode(urldecode($purchaseId));
            }
          // print_r($pId);
            //sprint_r($pId);
            $ArrCommonHeaderData = $this->commonheaderdata($VarEnqId);
            $requestData = $this->RequestBomModel->getRequestDataa_apprpi($VarEnqId, $reqId, $pId);
            $savestatus = $this->RequestBomModel->getspisavestatus($VarEnqId, $reqId, $pId);
            $saveasstatus = $this->RequestBomModel->getspisavestatus1($VarEnqId, $reqId, $pId);


            $this->load->view('request/bom/store/purchase_indent_details_surplus_issue',
                        array('VarEnqId'=>$VarEnqId,
                            'ArrCommonHeaderData' => $ArrCommonHeaderData,
                            'request_id' => $reqEnqId,
                            'pId' => $pId,
                            'requestData' => $requestData,
                             'savestatus' => $savestatus,
                            'saveasstatus' => $saveasstatus,
                            ));
        }

        public function getsurplusissuedetails() {
            $reqId = xssclean($this->input->post('reqId'));
            $enqId = xssclean($this->input->post('enquiry_id'));
            $pId = xssclean($this->input->post('pId'));
           // $itemCode = xssclean($this->input->post('itemCode'));
            $data = $this->RequestBomModel->getsurplusissuedetailss($enqId, $reqId, $pId);
            echo json_encode($data);
        }
        public function updateSurplusSSTDCList() {
            // $VarId = $this->uri->segment(4);
            // $reqId = $this->uri->segment(6);
            // $purchaseId = $this->uri->segment(7);
            // //print_r($purchaseId);
            // if ($VarId <> '' && is_numeric(base64_decode(urldecode($VarId))))
            // {
            //     $VarEnqId = base64_decode(urldecode($VarId));
            // }
            // if ($reqId <> '' && is_numeric(base64_decode(urldecode($reqId))))
            // {
            //     $reqEnqId = base64_decode(urldecode($reqId));
            // }
            // if ($purchaseId <> '' && is_numeric(base64_decode(urldecode($purchaseId))))
            // {
            //     $pIdS = base64_decode(urldecode($purchaseId));
            // }
            PRINT_R($pIdS);
            $reqId = xssclean($this->input->post('reqId'));
            $enqId = xssclean($this->input->post('enquiry_id'));
            $pId = xssclean($this->input->post('pId'));
           //$itemCode = xssclean($this->input->post('itemCode'));
            $data = $this->RequestBomModel->updateSurplusSSTDCListt($reqId, $enqId, $pId);
            echo json_encode($data);
        }

       
         public function clearsurplusstockdatatransfer() {
            $reqId = xssclean($this->input->post('reqId'));
            $enqId = xssclean($this->input->post('enquiry_id'));
            $purchase_id = xssclean($this->input->post('purchase_id'));
            print_r($purchase_id);
            $object = xssclean($this->input->post('data'));
            $req_data = json_decode($object);
            $data = $this->RequestBomModel->clearsurplusstockdatatransferr($enqId, $reqId, $purchase_id, $req_data);
            echo json_encode($data);
        }



        public function storepurchaseindentdetails1() {
            $VarId = $this->uri->segment(4);
            $reqId = $this->uri->segment(6);
            $purchaseId = $this->uri->segment(7);
            if ($VarId <> '' && is_numeric(base64_decode(urldecode($VarId))))
            {
                $VarEnqId = base64_decode(urldecode($VarId));
            }
            if ($reqId <> '' && is_numeric(base64_decode(urldecode($reqId))))
            {
                $reqEnqId = base64_decode(urldecode($reqId));
            }
            if ($purchaseId <> '' && is_numeric(base64_decode(urldecode($purchaseId))))
            {
                $pId = base64_decode(urldecode($purchaseId));
            }
            $subid=$this->subb_id;
           
            $subcompany_data = $this->RequestBomModel->getsubscribercompanydetail($subid);
            $Usertype=8;
            $bomstorelogin_data = $this->RequestBomModel->getuserdetail($Usertype,$subid);


            $ArrCommonHeaderData = $this->commonheaderdata($VarEnqId);
            $requestData = $this->RequestBomModel->getRequestDataa_apprpi($VarEnqId, $reqId, $pId);
            $requesttype=$requestData[0]['type'];
             //print_r($requesttype);
             if($requesttype == 3){
                 $requesttypedata = '(Art - 1)';
             }else if($requesttype == 4){
                 $requesttypedata = '(Art - 2)';
             }

            $this->load->view('request/bom/store/purchase_indent_details',
                        array('VarEnqId'=>$VarEnqId,
                            'ArrCommonHeaderData' => $ArrCommonHeaderData,
                            'request_id' => $reqEnqId,
                            'pId' => $pId,
                            'requestData' => $requestData,
                            'requesttypedata' => $requesttypedata,
                            'subcompany_data' => $subcompany_data,
                            'bomstorelogin_data' => $bomstorelogin_data
                            ));
        }

        public function storepiupdate() {
            $VarId = $this->uri->segment(4);
            $reqId = $this->uri->segment(6);
            $purchaseId = $this->uri->segment(7);
            if ($VarId <> '' && is_numeric(base64_decode(urldecode($VarId))))
            {
                $VarEnqId = base64_decode(urldecode($VarId));
            }
            if ($reqId <> '' && is_numeric(base64_decode(urldecode($reqId))))
            {
                $reqEnqId = base64_decode(urldecode($reqId));
            }
            if ($purchaseId <> '' && is_numeric(base64_decode(urldecode($purchaseId))))
            {
                $pId = base64_decode(urldecode($purchaseId));
            }
             $subid=$this->subb_id;
           
            $subcompany_data = $this->RequestBomModel->getsubscribercompanydetail($subid);
            $ArrCommonHeaderData = $this->commonheaderdata($VarEnqId);
            $requestData = $this->RequestBomModel->getRequestDataa_apprpi($VarEnqId, $reqId, $pId);
            $suplaycloseData = $this->RequestBomModel->getsupplayclose($VarEnqId, $reqId, $pId);
            $orderstockData = $this->RequestBomModel->getorderstock($VarEnqId, $reqId, $pId);
            $requesttype=$requestData[0]['type'];
             //print_r($requesttype);
             if($requesttype == 3){
                 $requesttypedata = '(Art - 1)';
             }else if($requesttype == 4){
                 $requesttypedata = '(Art - 2)';
             }

          
            $this->load->view('request/bom/store/store_pi_update',
                        array('VarEnqId'=>$VarEnqId,
                            'ArrCommonHeaderData' => $ArrCommonHeaderData,
                            'request_id' => $reqEnqId,
                            'pId' => $pId,
                            'suplaycloseData' => $suplaycloseData,
                            'orderstockData' => $orderstockData,
                            'requestData' => $requestData,
                            'requesttypedata' => $requesttypedata,
                            'subcompany_data' => $subcompany_data,

                            ));
        }
        
        public function supplyclosuredetails() {
            $VarId = $this->uri->segment(4);
            $reqId = $this->uri->segment(6);
            $purchaseId = $this->uri->segment(7);
            if ($VarId <> '' && is_numeric(base64_decode(urldecode($VarId))))
            {
                $VarEnqId = base64_decode(urldecode($VarId));
            }
            if ($reqId <> '' && is_numeric(base64_decode(urldecode($reqId))))
            {
                $reqEnqId = base64_decode(urldecode($reqId));
            }
            if ($purchaseId <> '' && is_numeric(base64_decode(urldecode($purchaseId))))
            {
                $pId = base64_decode(urldecode($purchaseId));
            }
            $ArrCommonHeaderData = $this->commonheaderdata($VarEnqId);
           
              $subid=$this->subb_id;
           
            $subcompany_data = $this->RequestBomModel->getsubscribercompanydetail($subid);
            $requestData = $this->RequestBomModel->getRequestDataa_apprpi($VarEnqId, $reqId, $pId);
             $requesttype=$requestData[0]['type'];
             //print_r($requesttype);
             if($requesttype == 3){
                 $requesttypedata = '(Art - 1)';
             }else if($requesttype == 4){
                 $requesttypedata = '(Art - 2)';
             }
            $this->load->view('request/bom/store/supplyclosuredetails',
                        array('VarEnqId'=>$VarEnqId,
                            'ArrCommonHeaderData' => $ArrCommonHeaderData,
                            'request_id' => $reqEnqId,
                            'pId' => $pId,
                            'requestData' => $requestData,
                            'requesttypedata' => $requesttypedata,
                              'subcompany_data' => $subcompany_data,

                            ));
        }
        
        // public function supplyclosuredetails() {
        //     $VarId = $this->uri->segment(4);
        //     //echo $varEnqId = base64_decode($VarId); exit;
        //     $reqId = $this->uri->segment(6);
        //     $itemCode = $this->uri->segment(8);
        //     $purId = $this->uri->segment(10);
            
        //     if ($VarId <> '' && is_numeric(base64_decode(urldecode($VarId))))
        //     {
        //         $VarEnqId = base64_decode(urldecode($VarId));
        //     }
        //     if ($reqId <> '' && is_numeric(base64_decode(urldecode($reqId))))
        //     {
        //         $reqEnqId = base64_decode(urldecode($reqId));
        //     }
        //     if ($itemCode <> '')
        //     {
        //         $itemCode = base64_decode(urldecode($itemCode));
        //     }
        //     if ($purId <> '' && is_numeric(base64_decode(urldecode($purId))))
        //     {
        //         $pId = base64_decode(urldecode($purId));
        //     }
        //     $itemCode = "'" . $itemCode . "'";
        //     $ArrCommonHeaderData = $this->commonheaderdata($VarEnqId);
        //     $draftData = $this->RequestBomModel->getdraftData($itemCode);
        //     $count = sizeof($draftData);
        //     $requestData = $this->RequestBomModel->getRequestDataa($VarEnqId, $reqEnqId, $pId);
        //     $this->load->view('request/bom/store/supply_closure_details',
        //                 array('VarEnqId'=>$VarEnqId,
        //                     'ArrCommonHeaderData' => $ArrCommonHeaderData,
        //                     'request_id' => $reqEnqId,
        //                     'requestData' => $requestData,
        //                     'itemCode'=> $itemCode,
        //                     'draft_count' => $count,
        //                     'pId' => $pId,
        //                 ));
        // }
        
        public function orderclosuredetails() {
            $VarId = $this->uri->segment(4);
            $reqId = $this->uri->segment(6);
            $itemCode = $this->uri->segment(8);
            $purId = $this->uri->segment(10);
            if ($VarId <> '' && is_numeric(base64_decode(urldecode($VarId))))
            {
                $VarEnqId = base64_decode(urldecode($VarId));
            }
            if ($reqId <> '' && is_numeric(base64_decode(urldecode($reqId))))
            {
                $reqEnqId = base64_decode(urldecode($reqId));
            }
            if ($itemCode <> '')
             {
                 $itemCode = base64_decode(urldecode($itemCode));
             }
             if ($purId <> '' && is_numeric(base64_decode(urldecode($purId))))
             {
                 $pId = base64_decode(urldecode($purId));
             }
             $itemCode = "'" . $itemCode . "'";
            $ArrCommonHeaderData = $this->commonheaderdata($VarEnqId);
             $subid=$this->subb_id;
           
           $subcompany_data = $this->RequestBomModel->getsubscribercompanydetail($subid);

            $requestData = $this->RequestBomModel->getRequestDataa_apprpi($VarEnqId, $reqId, $pId);
            $this->load->view('request/bom/store/order_closure_details',
                        array('VarEnqId'=>$VarEnqId,
                            'ArrCommonHeaderData' => $ArrCommonHeaderData,
                            'request_id' => $reqEnqId,
                            'pId' => $pId,
                            'itemCode'=> $itemCode,
                            'requestData' => $requestData,
                             'subcompany_data' => $subcompany_data,

                            ));
        }
        
        public function updateCreditDetails()
        {
            $eId = xssclean($this->input->post('enquiry_id'));
            $reqId = xssclean($this->input->post('reqId'));
            $pId = xssclean($this->input->post('pId'));
            $object = xssclean($this->input->post('credit_data'));
            $object1 = xssclean($this->input->post('payment_data'));
            
            $credit_data = json_decode($object);
            $payment_data = json_decode($object1);
            
            //print_r($req_data); exit;
            $data = $this->RequestBomModel->updateCreditDetailss($eId, $reqId, $pId, $credit_data, $payment_data);
            echo json_encode($data);
        }
        
        public function updatePaymentDetails()
        {
            $eId = xssclean($this->input->post('enquiry_id'));
            $reqId = xssclean($this->input->post('reqId'));
            $pId = xssclean($this->input->post('pId'));
            $amt_pay = xssclean($this->input->post('amt_pay'));
            $object = xssclean($this->input->post('paid_data'));
            
            $paid_data = json_decode($object);
            
            //print_r($req_data); exit;
            $data = $this->RequestBomModel->updatePaymentDetailss($eId, $reqId, $pId, $paid_data);
            echo json_encode($data);
        }
        
        public function updateFinanceReqRecDetails() {
            //echo "<pre>"; print_r($_POST); exit;
            $eId = xssclean($this->input->post('enquiry_id'));
            $reqId = xssclean($this->input->post('reqId'));
            $pId = xssclean($this->input->post('pId'));
            $object = xssclean($this->input->post('data'));
            
            $req_data = json_decode($object);
            
            $data = $this->RequestBomModel->updateFinanceReqRecDetailss($eId, $reqId, $pId, $req_data);
            echo json_encode($data);
        }

        // Image upload 

        public function imageUploadDetails() {
            $ArrExtensions = FILE_EXTENSIONS;
            //$VarDir = UPLOADS_SLASH . "orderenquiry" . DIRECTORY_SEPARATOR . $VarFdrName . DIRECTORY_SEPARATOR;
            $id = xssclean($this->input->post('enquiry_id'));
            $reqId = xssclean($this->input->post('request_id'));
            $type = "bom_request";
            //$filepath = 'uploads/request/bom/';
              $filepath = 'uploads/request/bom'. DIRECTORY_SEPARATOR . $this->subscriber_id . DIRECTORY_SEPARATOR;

               if (file_exists($filepath)) {
            } else {
            mkdir($filepath, 0777, true);
             }
            
            if (isset($_FILES["myFile"])) {
                $ret = array();
                // echo $_FILES["myFile"]["name"];
                $fName = $_FILES['myFile']['name'];
                $fName = pathinfo($fName, PATHINFO_FILENAME);

                $extension = pathinfo($_FILES["myFile"]["name"], PATHINFO_EXTENSION);
                if (in_array($extension, $ArrExtensions)) {
                    $rand = rand();
                    $fileName = str_replace('&', '_', $_FILES["myFile"]["name"]);
                    $fileName = $fName.$rand.'.'.$extension;
                     /////////////////////////////////
                    $fileName = preg_replace('/\s+/', '_', $fileName); // Replace spaces with underscores
                    $fileName = preg_replace('/[^A-Za-z0-9_\-\.]/', '', $fileName); // Remove unwanted characters
                    /////////////////////////////////
                    /**MAX file size 7 MB**/
                    if ($_FILES["myFile"]["size"] <= MAXUPLSIZE) {
                        if (move_uploaded_file($_FILES["myFile"]["tmp_name"], $filepath . $fileName))
                            $ret[] = $this->RequestBomModel->imageUploadDetailss($type, $id, $reqId, $fileName);
                    } else {
                        $ret[] = 'Err';
                    }
                } else {
                    $ret[] = 'Err';
                }
                echo json_encode($ret);
            }
    
        }

        public function purchaseImageUploadDetails() {
            $ArrExtensions = FILE_EXTENSIONS;
            //$VarDir = UPLOADS_SLASH . "orderenquiry" . DIRECTORY_SEPARATOR . $VarFdrName . DIRECTORY_SEPARATOR;
            $id = xssclean($this->input->post('enquiry_id'));
            $reqId = xssclean($this->input->post('request_id'));
            $type = "purchase_dept";
            //$filepath = 'uploads/request/bom/purchase/';
             $filepath = 'uploads/request/bom/purchase'.DIRECTORY_SEPARATOR . $this->subscriber_id . DIRECTORY_SEPARATOR;
          
               if (file_exists($filepath)) {
            } else {
            mkdir($filepath, 0777, true);
             }
             
            if (isset($_FILES["myFile"])) {
                $ret = array();
                // echo $_FILES["myFile"]["name"];
                $fName = $_FILES['myFile']['name'];
                $fName = pathinfo($fName, PATHINFO_FILENAME);

                $extension = pathinfo($_FILES["myFile"]["name"], PATHINFO_EXTENSION);
                if (in_array($extension, $ArrExtensions)) {
                    $rand = rand();
                    $fileName = str_replace('&', '_', $_FILES["myFile"]["name"]);
                    $fileName = $fName.$rand.'.'.$extension;
                      /////////////////////////////////
                    $fileName = preg_replace('/\s+/', '_', $fileName); // Replace spaces with underscores
                    $fileName = preg_replace('/[^A-Za-z0-9_\-\.]/', '', $fileName); // Remove unwanted characters
                    /////////////////////////////////
                    /**MAX file size 7 MB**/
                    if ($_FILES["myFile"]["size"] <= MAXUPLSIZE) {
                        if (move_uploaded_file($_FILES["myFile"]["tmp_name"], $filepath . $fileName))
                            $ret[] = $this->RequestBomModel->imageUploadDetailss($type, $id, $reqId, $fileName);
                    } else {
                        $ret[] = 'Err';
                    }
                } else {
                    $ret[] = 'Err';
                }
                echo json_encode($ret);
            }
    
        }
        
        public function purchaseImageUploadDetails_decline() {
            $ArrExtensions = FILE_EXTENSIONS;
            //$VarDir = UPLOADS_SLASH . "orderenquiry" . DIRECTORY_SEPARATOR . $VarFdrName . DIRECTORY_SEPARATOR;
            $id = xssclean($this->input->post('enquiry_id'));
            $reqId = xssclean($this->input->post('request_id'));
            $pId = xssclean($this->input->post('pId'));
            $type = "purchase_dept";
            $filepath = 'uploads/request/bom/purchase/';
            
            if (isset($_FILES["myFile"])) {
                $ret = array();
                // echo $_FILES["myFile"]["name"];
                $fName = $_FILES['myFile']['name'];
                $fName = pathinfo($fName, PATHINFO_FILENAME);

                $extension = pathinfo($_FILES["myFile"]["name"], PATHINFO_EXTENSION);
                if (in_array($extension, $ArrExtensions)) {
                    $rand = rand();
                    $fileName = str_replace('&', '_', $_FILES["myFile"]["name"]);
                    $fileName = $fName.$rand.'.'.$extension;
                    /**MAX file size 7 MB**/
                    if ($_FILES["myFile"]["size"] <= MAXUPLSIZE) {
                        if (move_uploaded_file($_FILES["myFile"]["tmp_name"], $filepath . $fileName))
                            $ret[] = $this->RequestBomModel->imageUploadDetailss_decline($type, $id, $reqId, $fileName, $pId);
                    } else {
                        $ret[] = 'Err';
                    }
                } else {
                    $ret[] = 'Err';
                }
                echo json_encode($ret);
            }
    
        }

        public function pirefdetails() {
            $VarId = $this->uri->segment(4);
            $reqId = $this->uri->segment(6);
            if ($VarId <> '' && is_numeric(base64_decode(urldecode($VarId))))
            {
                $VarEnqId = base64_decode(urldecode($VarId));
            }
            if ($reqId <> '' && is_numeric(base64_decode(urldecode($reqId))))
            {
                $reqEnqId = base64_decode(urldecode($reqId));
            }
            $ArrCommonHeaderData = $this->commonheaderdata($VarEnqId);
            $requestData = $this->RequestBomModel->getRequestDataa($VarEnqId, $reqEnqId);
            $this->load->view('request/bom/store/pi_ref_details',
                        array('VarEnqId'=>$VarEnqId,
                            'ArrCommonHeaderData' => $ArrCommonHeaderData,
                            'request_id' => $reqEnqId,
                            'requestData' => $requestData
                        ));
        }

        public function mireceiveddetails() {
            
            $VarId = $this->uri->segment(4);
            $reqId = $this->uri->segment(6);
            if ($VarId <> '' && is_numeric(base64_decode(urldecode($VarId))))
            {
                $VarEnqId = base64_decode(urldecode($VarId));
            }
            if ($reqId <> '' && is_numeric(base64_decode(urldecode($reqId))))
            {
                $reqEnqId = base64_decode(urldecode($reqId));
            }
             $userid_types=$this->userid_type ;
              $subid=$this->subb_id;
           
            $subcompany_data = $this->RequestBomModel->getsubscribercompanydetail($subid);
            $ArrCommonHeaderData = $this->commonheaderdata($VarEnqId);
            $requestData = $this->RequestBomModel->getRequestDataa($VarEnqId, $reqEnqId);
            $bomMITableData = $this->RequestSampleModel->sendbomMITableData($VarEnqId, $reqEnqId);
            $miDetails = $this->RequestSampleModel->sendmiDetails($VarEnqId, $reqEnqId);
            $bomStatus = $this->RequestBomModel->getBomStatus($reqEnqId);
            $mIDraftData = $this->RequestBomModel->getMIDraftData($reqEnqId);
            $mIDraftData2 = $this->RequestBomModel->getMIDraftData2($reqEnqId);
            $mIDraftsaveData = $this->RequestBomModel->getmIDraftsaveData($reqEnqId);
          //print_r(sizeof($mIDraftsaveData));
            $this->load->view('request/bom/store/mi_received_details',
                        array('VarEnqId'=>$VarEnqId,
                            'ArrCommonHeaderData' => $ArrCommonHeaderData,
                            'request_id' => $reqEnqId,
                            'requestData' => $requestData,
                            'bomMITableData' => $bomMITableData,
                            'miDetails' => $miDetails,
                            'bomStatus' => $bomStatus,
                            'mIDraftData' => $mIDraftData,
                            'mIDraftData2' => $mIDraftData2,
                             'mIDraftsaveData' => $mIDraftsaveData,
                            'loguserid' => $userid_types,
                             'subcompany_data' => $subcompany_data,

                            
                        ));
        }

        public function mireceiveddetails_print() {
            
             $VarEnqId = $_POST['enquiry_id'];
             $reqEnqId = $_POST['request_id'];
             $userid_types=$this->userid_type ;
             $Usertype = 5;
             $subid=$this->subb_id;

            $ArrCommonHeaderData = $this->commonheaderdata($VarEnqId);
            $miDetails = $this->RequestSampleModel->sendmiDetails($VarEnqId, $reqEnqId);
            
            //$data = $this->RequestBomModel->getMIReceivedDetailss($VarEnqId, $reqEnqId);
            $output['samplelogin_data'] = $this->RequestBomModel->getuserdetail($Usertype,$subid);
            $output['subcompany_data'] = $this->RequestBomModel->getsubscribercompanydetail($subid);
            $output['company_data'] = $this->commonheaderdata($VarEnqId);
             $output['miDetails'] = $this->RequestSampleModel->sendmiDetails($VarEnqId, $reqEnqId);;
             $output['mi_data'] = $this->RequestBomModel->getMIReceivedDetailss($VarEnqId, $reqEnqId);
           $this->load->view('request/bom/store/mi_received_details_print',$output);
          
            
        }

         public function mireceiveddetails_pdf() {
            
             $VarEnqId = $_POST['enquiry_id'];
             $reqEnqId = $_POST['request_id'];
             $userid_types=$this->userid_type ;
             $Usertype = 5;
             $subid=$this->subb_id;
            $ArrCommonHeaderData = $this->commonheaderdata($VarEnqId);
            $miDetails = $this->RequestSampleModel->sendmiDetails($VarEnqId, $reqEnqId);
            
            //$data = $this->RequestBomModel->getMIReceivedDetailss($VarEnqId, $reqEnqId);
            $output['samplelogin_data'] = $this->RequestBomModel->getuserdetail($Usertype,$subid);
            $output['subcompany_data'] = $this->RequestBomModel->getsubscribercompanydetail($subid);

             $output['company_data'] = $this->commonheaderdata($VarEnqId);
             $output['miDetails'] = $this->RequestSampleModel->sendmiDetails($VarEnqId, $reqEnqId);;
             $output['mi_data'] = $this->RequestBomModel->getMIReceivedDetailss($VarEnqId, $reqEnqId);
           $this->load->view('request/bom/store/mi_received_details_pdf',$output);
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
       
        
        
        public function getMIBOMReceivedDetails() {
            $enqId = xssclean($this->input->post('enquiry_id'));
            $reqId = xssclean($this->input->post('reqId'));
            $data = $this->RequestBomModel->getMIBOMReceivedDetailss($enqId, $reqId);
            echo json_encode($data);
        }

        public function getMIReceivedDetails() {
            $enqId = xssclean($this->input->post('enquiry_id'));
            $reqId = xssclean($this->input->post('reqId'));
            $data = $this->RequestBomModel->getMIReceivedDetailss($enqId, $reqId);
            echo json_encode($data);
        }
        
        public function getDraftDcDetails() {
            $enqId = xssclean($this->input->post('enquiry_id'));
            $reqId = xssclean($this->input->post('reqId'));
            $drafdc = xssclean($this->input->post('drafId'));
            $data = $this->RequestBomModel->getDraftDcDetailss($enqId, $reqId,$drafdc);
            echo json_encode($data);
        }

        public function updateOrderStockBom() {
            // echo "<pre>"; print_r($_POST); exit;
            $enqId = xssclean($this->input->post('enquiry_id'));
            $reqId = xssclean($this->input->post('reqId'));
            $object = xssclean($this->input->post('bom_data'));
            $req_data = json_decode($object);
            
            $data = $this->RequestBomModel->updateOrderStockBomm($enqId, $reqId, $req_data);
            echo json_encode($data);
        }
         public function updatesurplusstockdata() {
            // echo "<pre>"; print_r($_POST); exit;
            $enqId = xssclean($this->input->post('enquiry_id'));
            $reqId = xssclean($this->input->post('reqId'));
            $pId = xssclean($this->input->post('pId'));
            $object = xssclean($this->input->post('data'));
            $req_data = json_decode($object);
            
            $data = $this->RequestBomModel->updatesurplusstockdatas($enqId, $reqId,$pId, $req_data);
            echo json_encode($data);
        }
         public function updatesurplusstockdatatransfer() {
            // echo "<pre>"; print_r($_POST); exit;
            $enqId = xssclean($this->input->post('enquiry_id'));
            $reqId = xssclean($this->input->post('reqId'));
            $pId = xssclean($this->input->post('pid'));
            $object = xssclean($this->input->post('data'));
            $save_status = xssclean($this->input->post('save_status'));
            $issuedData = json_decode($object);
            
            $data = $this->RequestBomModel->updatesurplusstockdatatransferr($enqId, $reqId,$pId,$issuedData,$save_status);
            echo json_encode($data);
        }
         public function updateOrderStockissuedc() {
            // echo "<pre>"; print_r($_POST); exit;
            $enqId = xssclean($this->input->post('enquiry_id'));
            $reqId = xssclean($this->input->post('reqId'));
            $object = xssclean($this->input->post('bom_data'));
            $req_data = json_decode($object);
            
            $data = $this->RequestBomModel->updateOrderStockissuedcc($enqId, $reqId, $req_data);
            echo json_encode($data);
        }
        
        public function miDraftDc() {
            $VarId = $this->uri->segment(4);
            $reqId = $this->uri->segment(6);
            $drafid = $this->uri->segment(8);
            $movedc = $this->uri->segment(10);
            if ($VarId <> '' && is_numeric(base64_decode(urldecode($VarId))))
            {
                $VarEnqId = base64_decode(urldecode($VarId));
            }
            if ($reqId <> '' && is_numeric(base64_decode(urldecode($reqId))))
            {
                $reqEnqId = base64_decode(urldecode($reqId));
            }
            if ($drafid <> '' && is_numeric(base64_decode(urldecode($drafid))))
            {
                $draf_id = base64_decode(urldecode($drafid));
            }
             if ($movedc <> '' && is_numeric(base64_decode(urldecode($movedc))))
            {
                $movedc = base64_decode(urldecode($movedc));
            }
            //print_r($draf_id);
            $ArrCommonHeaderData = $this->commonheaderdata($VarEnqId);
            $requestData = $this->RequestBomModel->getRequestDataa($VarEnqId, $reqEnqId);
            $bomMITableData = $this->RequestSampleModel->sendbomMITableData($VarEnqId, $reqEnqId);
            //$recived_bymessage = $this->RequestBomModel->getRecivedByMessage($VarEnqId, $reqEnqId);
            $savestatus = $this->RequestBomModel->getsavestatus($VarEnqId,$draf_id,$reqEnqId);
            $saveasstatus = $this->RequestBomModel->getsavestatus1($VarEnqId,$draf_id,$reqEnqId);
            //print_r(count($savestatus));
            $mIDraftData = $this->RequestBomModel->getMIDraftData3($reqEnqId,$draf_id);
            $miDetails = $this->RequestSampleModel->sendmiDetails($VarEnqId, $reqEnqId);
            $bomStatus = $this->RequestSampleModel->getBomStatus($reqEnqId);
            // print_r($bomStatus); exit;
            $this->load->view('request/bom/store/mi_draft_dc',
                        array('VarEnqId'=>$VarEnqId,
                            'ArrCommonHeaderData' => $ArrCommonHeaderData,
                            'request_id' => $reqEnqId,
                            'requestData' => $requestData,
                            'bomMITableData' => $bomMITableData,
                            'miDetails' => $miDetails,
                            'bomStatus' => $bomStatus,
                            'draf_id' => $draf_id,
                            'movedc' => $movedc,
                            'mIDraftData' => $mIDraftData,
                            'savestatus' => $savestatus,
                            'saveasstatus' => $saveasstatus
                            
                        ));
        }

        public function orderstockdetails() {
            $VarId = $this->uri->segment(4);
            //echo $varEnqId = base64_decode($VarId); exit;
            $reqId = $this->uri->segment(6);
            $itemCode = $this->uri->segment(8);
            $purId = $this->uri->segment(10);

            //print_r($itemCode);
            //die;

          

            
            if ($VarId <> '' && is_numeric(base64_decode(urldecode($VarId))))
            {
                $VarEnqId = base64_decode(urldecode($VarId));
            }
            if ($reqId <> '' && is_numeric(base64_decode(urldecode($reqId))))
            {
                $reqEnqId = base64_decode(urldecode($reqId));
            }
            if ($itemCode <> '')
            {
                $itemCode = base64_decode(urldecode($itemCode));
            }
            if ($purId <> '' && is_numeric(base64_decode(urldecode($purId))))
            {
                $pId = base64_decode(urldecode($purId));
            }
            //$itemCode = "'" . $itemCode . "'";
            //print_r($reqEnqId); exit;
             $subid=$this->subb_id;
           
            $subcompany_data = $this->RequestBomModel->getsubscribercompanydetail($subid);
             $ArrCommonHeaderData = $this->commonheaderdata($VarEnqId);
            $draftData = $this->RequestBomModel->getdraftData($itemCode,$VarEnqId);
            $draftMINo = $this->RequestBomModel->getdraftMINo($itemCode);
            //print_r("draftMINo"+$draftMINo);
            $count = sizeof($draftData);    
            $requestData = $this->RequestBomModel->getRequestDataa($VarEnqId, $reqEnqId, $pId);
            //$orderclosuredata  = $this->RequestBomModel->getorderclosure($VarEnqId);
             $orderclosuremoveddata  = $this->RequestBomModel->getorderclosuremoved($VarEnqId, $reqEnqId,$itemCode);
            

            //print_r($orderclosuremoveddata); exit;
            if($count > 0) {
                $dreq_id = $draftData[0]['request_id']; 
            } else {
                $dreq_id = 0;
            }
            $this->load->view('request/bom/store/order_stock_details',
                        array('VarEnqId'=>$VarEnqId,
                            'ArrCommonHeaderData' => $ArrCommonHeaderData,
                            'request_id' => $reqEnqId,
                            'requestData' => $requestData,
                            'itemCode'=> $itemCode,
                            'draft_count' => $count,
                            'draftMINo' => $draftMINo,
                            'orderclosuredata' => $orderclosuremoveddata,
                            'pId' => $pId,
                            'dreq_id' => $dreq_id,
                             'subcompany_data' => $subcompany_data,

                        ));
        }

        public function getOrderStockDetails() {
            $reqId = xssclean($this->input->post('reqId'));
            $enqId = xssclean($this->input->post('enquiry_id'));
            $itemCode = xssclean($this->input->post('itemCode'));
            $pId = xssclean($this->input->post('pId'));
           
            $data = $this->RequestBomModel->getOrderStockDetailss($enqId, $reqId, $itemCode, $pId);
            echo json_encode($data);
        }

        public function updateorderclosurelist() {
            $reqId = xssclean($this->input->post('reqId'));
            $enqId = xssclean($this->input->post('enquiry_id'));
            $item_code = xssclean($this->input->post('itemCode'));
            $data = $this->RequestBomModel->updateorderclosurelistt($enqId, $reqId,$item_code);
            echo json_encode($data);
        }
        
        public function supplyclosuredata() {
            $reqId = xssclean($this->input->post('reqId'));
            $enqId = xssclean($this->input->post('enquiry_id'));
            $itemCode = xssclean($this->input->post('itemCode'));
            $pId = xssclean($this->input->post('pId'));
            $data = $this->RequestBomModel->supplyclosuredataa($enqId, $reqId, $itemCode, $pId);
            echo json_encode($data);
        }

        public function updateOrderStockDetails() {
            // echo "<pre>"; print_r($_POST); exit;
            $reqId = xssclean($this->input->post('reqId'));
            $enqId = xssclean($this->input->post('enquiry_id'));
            $itemCode = xssclean($this->input->post('itemCode'));
            $object = xssclean($this->input->post('in_house_qty'));
            $object2 = xssclean($this->input->post('mi_issued_details'));
            $object3 = xssclean($this->input->post('shipment_order_details'));
            $in_house_qty = json_decode($object);
            $mi_issued_details = json_decode($object2);
            $shipment_order_details = json_decode($object3);
            $data = $this->RequestBomModel->updateOrderStockDetailss($enqId, $reqId, $itemCode, $in_house_qty, $mi_issued_details, $shipment_order_details);
            echo json_encode($data);
        }
        
        public function updateDraftStockDetails() {
            // echo "<pre>"; print_r($_POST); exit;
            $reqId = xssclean($this->input->post('reqId'));
            $enqId = xssclean($this->input->post('enquiry_id'));
            $bom_ref_no = xssclean($this->input->post('bom_ref_no'));
            $bom_dept = xssclean($this->input->post('bom_dept'));
            $object = xssclean($this->input->post('issued_data'));
             $draf_id = xssclean($this->input->post('draf_id'));
            $mi_issued_details = json_decode($object);
            
            $data = $this->RequestBomModel->updateDraftStockDetailss($enqId, $reqId, $mi_issued_details, $bom_ref_no, $bom_dept, $draf_id);
            echo json_encode($data);
        }
        
        public function updateSaveStockDetails() {
            // echo "<pre>"; print_r($_POST); exit;
            $reqId = xssclean($this->input->post('reqId'));
            $enqId = xssclean($this->input->post('enquiry_id'));
            $bom_ref_no = xssclean($this->input->post('bom_ref_no'));
            $bom_dept = xssclean($this->input->post('bom_dept'));
            $received_by = xssclean($this->input->post('received_by'));
            $object = xssclean($this->input->post('issued_data'));
            $draf_id = xssclean($this->input->post('draf_id'));
            $save_status = xssclean($this->input->post('save_status'));
            $mi_issued_details = json_decode($object);
            
            $data = $this->RequestBomModel->updateSaveStockDetailss($enqId, $reqId, $mi_issued_details, $bom_ref_no, $bom_dept, $received_by, $draf_id, $save_status);
            echo json_encode($data);
        }

         public function updateSaveStockDetailsdc() {
            // echo "<pre>"; print_r($_POST); exit;
            $reqId = xssclean($this->input->post('reqId'));
            $enqId = xssclean($this->input->post('enquiry_id'));
            $bom_ref_no = xssclean($this->input->post('bom_ref_no'));
            $bom_dept = xssclean($this->input->post('bom_dept'));
            $received_by = xssclean($this->input->post('received_by'));
            $object = xssclean($this->input->post('issued_data'));
            $draf_id = xssclean($this->input->post('draf_id'));
            $mi_issued_details = json_decode($object);
            
            $data = $this->RequestBomModel->updateSaveStockDetailsdc($enqId, $reqId, $mi_issued_details, $bom_ref_no, $bom_dept, $received_by, $draf_id);
            echo json_encode($data);
        }
        
        public function updateClearDraftDetails() {
            // echo "<pre>"; print_r($_POST); exit;
            
            $object = xssclean($this->input->post('issued_data'));
            $mi_issued_details = json_decode($object);
            $draf_id = xssclean($this->input->post('draf_id'));
            
            $data = $this->RequestBomModel->updateClearDraftDetailss( $mi_issued_details,$draf_id);
            echo json_encode($data);
        }
        
        
        public function updateOrderCloseDetails() {
            // echo "<pre>"; print_r($_POST); exit;
            $reqId = xssclean($this->input->post('reqId'));
            $pId = xssclean($this->input->post('pId'));
            $enqId = xssclean($this->input->post('enquiry_id'));
            $object = xssclean($this->input->post('shipment_order_details'));
            $object1 = xssclean($this->input->post('itemDetails'));
            $object2 = xssclean($this->input->post('in_house_qty'));
            $shipment_order_details = json_decode($object);
            $itemDetails = json_decode($object1);
            $in_house_qty = json_decode($object2);
            $data = $this->RequestBomModel->updateOrderCloseDetailss($enqId, $reqId, $pId, $shipment_order_details, $itemDetails, $in_house_qty);
            echo json_encode($data);
        }
        
        public function updateStatusDetails() {
            $reqId = xssclean($this->input->post('reqId'));
            $enqId = xssclean($this->input->post('enquiry_id'));
            $object = xssclean($this->input->post('shipment_order_details'));
            $object1 = xssclean($this->input->post('itemDetails'));
            $shipment_order_details = json_decode($object);
            $itemDetails = json_decode($object1);
            $data = $this->RequestBomModel->updateStatusDetailss($enqId, $reqId, $pId, $shipment_order_details, $itemDetails);
            echo json_encode($data);
        }

        // ***** DC LIST STARTS ***** //

        public function draftdc()
        {
            $VarId = $this->uri->segment(4);
            $reqId = $this->uri->segment(6);
            $miId = $this->uri->segment(8);
            $item_code = $this->uri->segment(10);
            $pId = $this->uri->segment(12);
            if ($VarId <> '' && is_numeric(base64_decode(urldecode($VarId))))
            {
                $VarEnqId = base64_decode(urldecode($VarId));
            }

            if ($reqId <> '' && is_numeric(base64_decode(urldecode($reqId))))
            {
                $reqEnqId = base64_decode(urldecode($reqId));
            }

            if ($miId <> '' && is_numeric(base64_decode(urldecode($miId))))
            {
                $miId = base64_decode(urldecode($miId));
            }
            
            $itemCode = base64_decode(urldecode($item_code));
        
            if ($pId <> '' && is_numeric(base64_decode(urldecode($pId))))
            {
                $pId = base64_decode(urldecode($pId));
            }
            // echo $reqEnqId; exit;
            //$itemCode = "'".$itemCode1."'";
            $requestData = $this->RequestBomModel->getDCDetails($VarEnqId, $reqEnqId);
            $miDetails = $this->RequestBomModel->getMIData($miId);
            $ArrCommonHeaderData = $this->commonheaderdata($VarEnqId);
            $piDetails = $this->RequestBomModel->getPIData($pId);
            // print_r($miDetails); exit;
            $this->load->view('request/bom/draftdc',
                array( 'VarEnqId'=>$VarEnqId, 'reqId' => $reqEnqId, 'miId'=> $miId, 'itemCode'=> $itemCode, 'pId'=> $pId,
                'ArrCommonHeaderData' => $ArrCommonHeaderData, 
                 'requestData'=> $requestData,
                'miDetails'=> $miDetails,
                'piDetails' => $piDetails,
            ));
        }
        
        public function getDCList() {
            $id = xssclean($this->input->post('enquiry_id'));
            $reqId = xssclean($this->input->post('reqId'));
            $miId = xssclean($this->input->post('miId'));
            $data = $this->RequestBomModel->getDCListt($id, $reqId, $miId);
            echo json_encode($data);
        }
        
        public function updateDCList() {
            //echo "<pre>"; print_r($_POST); exit;
            $object = xssclean($this->input->post('data'));
            $enqId = xssclean($this->input->post('enquiry_id'));
            $reqId = xssclean($this->input->post('reqId'));
            $received_by = xssclean($this->input->post('received_by'));
            $mi_type = xssclean($this->input->post('mi_type'));
            $miId = xssclean($this->input->post('miId'));
            $req_data = json_decode($object);
            $data = $this->RequestBomModel->updateDCListt($enqId, $reqId, $miId, $req_data, $received_by, $mi_type);
            echo json_encode($data);
        }


        public function updatePurchaseIndentList() {
            $object = xssclean($this->input->post('data'));
            $id = xssclean($this->input->post('enquiry_id'));
            $req_type = xssclean($this->input->post('req_type'));
            $cutoff_date = xssclean($this->input->post('cutoff_date'));
            $merchant_note = xssclean($this->input->post('merchant_note'));
            $req_data = json_decode($object);
            $data = $this->RequestCadModel->createCadRequestt($req_data, $id, $req_type, $cutoff_date, $merchant_note);
            echo json_encode($data);
        }
        
        public function get_pi_draft_value()
        {
            $req_id = xssclean($this->input->post('reqId'));
            $data = $this->RequestBomModel->get_pi_draft_valuee($req_id);
            echo json_encode($data);

        }
        
        public function clearPiDraft()
        {
            $id = xssclean($this->input->post('request_id'));
            $data = $this->RequestBomModel->clearPiDraftt( $id);
            echo json_encode($data);
        }
        
    public function getpurchaserequestImages()
    {
        header('Access-Control-Allow-Origin: *');
        $data = $this->input->post();
        $output = $this->RequestBomModel->getpurchaserequestImagess($data);
          $ArrUserLoggedInfo = fnGetUserLoggedInfo('1');
            $user_id=$this->userid = $ArrUserLoggedInfo['id'];
            $ArrObjsubscriber_id = $this->commonmodel->getsubscriberid($user_id);
            $subscriber_id=$ArrObjsubscriber_id->subscriber_id;
            //$output['subscriber_id'] = 'Sub_Id_'.$subscriber_id;
            $result = [
        'subscriber_id' => 'Sub_Id_' . $subscriber_id,
        'images' => $output // Assuming $output contains the images data
    ];
        
        echo json_encode($result);
    }
    
    public function getbomrequestImages()
    {
        header('Access-Control-Allow-Origin: *');
        $data = $this->input->post();
        $output = $this->RequestBomModel->getbomrequestImagess($data);
        echo json_encode($output);
    }


    public function invoicebill()
    {
        header('Access-Control-Allow-Origin: *');
        $enqId = $this->uri->segment(4);
          $reqId = $this->uri->segment(5);  
          $pId = $this->uri->segment(6);
        if ($pId <> '' && is_numeric(base64_decode(urldecode($pId))))
        {
            $purchase_id       = base64_decode(urldecode($pId));
        }
          if ($enqId <> '' && is_numeric(base64_decode(urldecode($enqId))))
        {
            $enqIdd       = base64_decode(urldecode($enqId));
        }
         if ($reqId <> '' && is_numeric(base64_decode(urldecode($reqId))))
        {
            $reqIdd       = base64_decode(urldecode($reqId));
        }
        $data['pId'] = $purchase_id;
        $output = $this->RequestBomModel->invoicebilll($data);
      //echo "<pre>"; print_r($pId); exit;
       $subid=$this->subb_id;
       $Usertype=8;
        $output['purchase_data'] = $this->RequestBomModel->getRequestDataa_apprpi($enqIdd, $reqIdd, $purchase_id);
       $output['subcompany_data'] = $this->RequestBomModel->getsubscribercompanydetail($subid);

       $output['bomstorelogin_data'] = $this->RequestBomModel->getuserdetail($Usertype,$subid);
       $output['newpurchase_data'] = $this->RequestBomModel->getPIRequestSendDetailss($enqIdd, $reqIdd, $purchase_id);
            $vendors = $output['newpurchase_data']['vendor_data'];
            $vendorid = $output['purchase_data'][0]['vendor_id'];
            $vendorids = $vendorid;
            $filtered = array_filter($vendors, function($vendor) use ($vendorids) {
       return $vendor['id'] == $vendorids;
       });
       $filtered = array_values($filtered); // optional

              $output['vendor_data']=$filtered[0];

              //print_r($output['vendor_data']);

         

        $data  = $this->load->view('request/purchase/invoice_bill_print',$output);
        return $data;
    }

    public function invoicebill_pdf()
    {
       $enqId = $this->uri->segment(4);
          $reqId = $this->uri->segment(5);  
          $pId = $this->uri->segment(6);
        if ($pId <> '' && is_numeric(base64_decode(urldecode($pId))))
        {
            $purchase_id       = base64_decode(urldecode($pId));
        }
          if ($enqId <> '' && is_numeric(base64_decode(urldecode($enqId))))
        {
            $enqIdd       = base64_decode(urldecode($enqId));
        }
         if ($reqId <> '' && is_numeric(base64_decode(urldecode($reqId))))
        {
            $reqIdd       = base64_decode(urldecode($reqId));
        }
        $data['pId'] = $purchase_id;
        $output = $this->RequestBomModel->invoicebilll($data);
      //echo "<pre>"; print_r($purchase_id); exit;
       $subid=$this->subb_id;
       $Usertype=8;
        $output['purchase_data'] = $this->RequestBomModel->getRequestDataa_apprpi($enqIdd, $reqIdd, $purchase_id);
       $output['subcompany_data'] = $this->RequestBomModel->getsubscribercompanydetail($subid);

       $output['bomstorelogin_data'] = $this->RequestBomModel->getuserdetail($Usertype,$subid);
       $output['newpurchase_data'] = $this->RequestBomModel->getPIRequestSendDetailss($enqIdd, $reqIdd, $purchase_id);
            $vendors = $output['newpurchase_data']['vendor_data'];
            $vendorid = $output['purchase_data'][0]['vendor_id'];
            $vendorids = $vendorid;
            $filtered = array_filter($vendors, function($vendor) use ($vendorids) {
       return $vendor['id'] == $vendorids;
       });
       $filtered = array_values($filtered); // optional

              $output['vendor_data']=$filtered[0];

              //print_r($output['vendor_data']);
        $data  = $this->load->view('request/purchase/invoice_bill_pdf',$output);
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
        return $data;
    }
    
    public function getRCData()
    {
        header('Access-Control-Allow-Origin: *');
        $data = $this->input->post();
        //print_r($data); exit;
        $datas = $this->RequestBomModel->getRCDataa($data);
        echo json_encode($datas);
        
    }
    
    public function updateBillClosed()
    {
        header('Access-Control-Allow-Origin: *');
        $eId = xssclean($this->input->post('enquiry_id'));
        $reqId = xssclean($this->input->post('reqId'));
        $pId = xssclean($this->input->post('pId'));
        $output = $this->RequestBomModel->updateBillClosedd($eId,$reqId,$pId);
        echo json_encode($output);
        
    }
    
    public function getWithinSateDetails() {
        $reqId = xssclean($this->input->post('reqId'));
        $data = $this->RequestBomModel->getWithinSateDetailss($reqId);
        echo json_encode($data);
    }
        

        // ***** DC LIST ENDS ***** //
     // ********* BOM Store **** // 
        
    public function getBomStoreDetails() {
            $reqId = xssclean($this->input->post('reqId'));
            $enqId = xssclean($this->input->post('enquiry_id'));
            $pId = xssclean($this->input->post('pId'));
            $data = $this->RequestBomModel->getBomStoreDetailss($enqId, $reqId, $pId);
            echo json_encode($data);
        }
        
    public function updateMerchantInHouseDetails() {
        //print_r($_POST); exit;
        $object = xssclean($this->input->post('data'));
        $enqId = xssclean($this->input->post('enquiry_id'));
        $reqId = xssclean($this->input->post('reqId'));
        $req_data = json_decode($object);
        $data = $this->RequestBomModel->updateMerchantInHouseDetailss($req_data, $enqId, $reqId);
        echo json_encode($data);
    }
    
    public function updateStoreAcceptDetails() {
        //print_r($_POST); exit;
        $object = xssclean($this->input->post('itemAccept'));
        $enqId = xssclean($this->input->post('enquiry_id'));
        $reqId = xssclean($this->input->post('reqId'));
        $pId = xssclean($this->input->post('pId'));
        $itemAccept = json_decode($object);
        $data = $this->RequestBomModel->updateStoreAcceptDetailss($itemAccept, $enqId, $reqId,$pId);
        echo json_encode($data);
    }
    
    public function updateSupplyClosedDetails() {
        //print_r($_POST); exit;
        $object = xssclean($this->input->post('consolidated'));
        $enqId = xssclean($this->input->post('enquiry_id'));
        $reqId = xssclean($this->input->post('reqId'));
        $pId = xssclean($this->input->post('pId'));
        $consolidated = json_decode($object);
        $data = $this->RequestBomModel->updateSupplyClosedDetailss($consolidated, $enqId, $reqId,$pId);
        echo json_encode($data);
    }
    
    public function updateSupplyClosureDetails() {
        //print_r($_POST); exit;
        $object = xssclean($this->input->post('inHouseStatus'));
        $object1 = xssclean($this->input->post('consolidated'));
        $supply_status = xssclean($this->input->post('supply_status'));
        $enqId = xssclean($this->input->post('enquiry_id'));
        $reqId = xssclean($this->input->post('reqId'));
        $pId = xssclean($this->input->post('pId'));
        $inHouseStatus = json_decode($object);
        $consolidated = json_decode($object1);
        $data = $this->RequestBomModel->updateSupplyClosureDetailss($consolidated, $inHouseStatus, $enqId, $reqId, $pId, $supply_status);
        echo json_encode($data);
    }
    
    
    public function getItemData()
    {
        header('Access-Control-Allow-Origin: *');
        $data = $this->input->post();
        //print_r($data); exit;
        $datas = $this->RequestBomModel->getItemDataa($data);
        echo json_encode($datas);
        
    }
    
    public function getDebitData()
    {
        header('Access-Control-Allow-Origin: *');
        $data = $this->input->post();
        //print_r($data); exit;
        $datas = $this->RequestBomModel->getDebitDatas($data);
        echo json_encode($datas);
        
    }
    
    public function getDept()
    {
        header('Access-Control-Allow-Origin: *');
        $data = $this->input->post();
        $datas = $this->RequestBomModel->getDeptt($data);
        //echo json_encode($datas);
        
        echo $datas;
    }
    
    public function getMIUom()
    {
        header('Access-Control-Allow-Origin: *');
        $data = $this->input->post();
        $datas = $this->RequestBomModel->getMIUomm($data);
        //echo json_encode($datas);
        
        echo $datas;
    }
    
    public function getMIUom1()
    {
        header('Access-Control-Allow-Origin: *');
        $data = $this->input->post();
        $datas = $this->RequestBomModel->getMIUom11($data);
        //echo json_encode($datas);
        
        echo $datas;
    }
    
    public function bomDCDetails()
        {
            $VarId = $this->uri->segment(4);
            $reqId = $this->uri->segment(6);
            $miId = $this->uri->segment(8);
            $dc = $this->uri->segment(10);
            if ($VarId <> '' && is_numeric(base64_decode(urldecode($VarId))))
            {
                $VarEnqId = base64_decode(urldecode($VarId));
            }
    
            if ($reqId <> '' && is_numeric(base64_decode(urldecode($reqId))))
            {
                $reqEnqId = base64_decode(urldecode($reqId));
            }
    
            if ($miId <> '' && is_numeric(base64_decode(urldecode($miId))))
            {
                $miId = base64_decode(urldecode($miId));
            }

            if ($dc <> '' && base64_decode(urldecode($dc)))
            {
                $dc = base64_decode(urldecode($dc));
            }

            $dc = '"'.$dc.'"';
            $subid=$this->subb_id;
         
            $subcompany_data = $this->RequestBomModel->getsubscribercompanydetail($subid);

            
            $requestData = $this->RequestBomModel->getBOMDCData($VarEnqId, $reqEnqId);
            $miDetails = $this->RequestBomModel->getBOMMIData($miId, $dc);
            $itemDetails = $this->RequestBomModel->getMIReceivedData($miId, $dc);
            $ArrCommonHeaderData = $this->commonheaderdata($VarEnqId);
            
            //print_r($requestData);
            //die;

            $this->load->view('request/bom/store/bomDcList',
                array( 'VarEnqId'=>$VarEnqId, 'reqId' => $reqEnqId, 'miId'=> $miId, 'dc'=> $dc,
                'ArrCommonHeaderData' => $ArrCommonHeaderData, 'requestData'=> $requestData,
                'miDetails'=> $miDetails, 'itemDetails' => $itemDetails, 'subcompany_data' => $subcompany_data));
        }
        
        public function getBOMDCDetails() {
            $id = xssclean($this->input->post('enquiry_id'));
            $miId = xssclean($this->input->post('miId'));
            $dc = xssclean($this->input->post('dc'));
            $data = $this->RequestBomModel->getBOMDCDetailss($id, $miId, $dc);
            echo json_encode($data);
        }
        
    public function bomstore_dc_print()
    {
        //echo "<pre>"; print_r($_POST); exit;
        header('Access-Control-Allow-Origin: *');
        $enquiry_id = $_POST['enquiry_id'];
        $request_id = $_POST['request_id'];
        $miId = $_POST['miId'];
        $dc = $_POST['dc'];
        $dc1 = '"'.$dc.'"';
        $subid=$this->subb_id;
          $Usertype=3;
        $output['requestData'] = $this->RequestBomModel->getBOMDCData($enquiry_id, $request_id);
        $output['miDetails'] = $this->RequestBomModel->getBOMMIData($miId, $dc1);
        $output['itemDetails'] = $this->RequestBomModel->getMIReceivedData($miId, $dc1);
        $output['ArrCommonHeaderData'] = $this->commonheaderdata($enquiry_id);
        $output['subcompany_data'] = $this->RequestBomModel->getsubscribercompanydetail($subid);
        $output['samplelogin_data'] = $this->RequestBomModel->getuserdetail($Usertype,$subid);


        $output['data'] = $this->RequestBomModel->getBOMDCprintData($enquiry_id, $miId, $dc);
        $output['DCNO'] = $dc;
        $company_id = $_SESSION['UI']['companyid']; 
        $output['company_data'] = $this->db->from('kn_company_details')->where('id', $company_id)->get()->result_array();
        //print_r( $output['miDetails']);
        $data  = $this->load->view('request/bom/store/bomstore_dc_print',$output);
        return $data;

        

    
    }
    public function bomstore_dc_pdf()
    {
        //echo "<pre>"; print_r($_POST); exit;
        header('Access-Control-Allow-Origin: *');
        $enquiry_id = $_POST['enquiry_id'];
        $request_id = $_POST['request_id'];
        $miId = $_POST['miId'];
        $dc = $_POST['dc'];
        $dc1 = '"'.$dc.'"';
         $subid=$this->subb_id;
         $Usertype=3;
        $output['requestData'] = $this->RequestBomModel->getBOMDCData($enquiry_id, $request_id);
        $output['subcompany_data'] = $this->RequestBomModel->getsubscribercompanydetail($subid);
         $output['samplelogin_data'] = $this->RequestBomModel->getuserdetail($Usertype,$subid);

        $output['miDetails'] = $this->RequestBomModel->getBOMMIData($miId, $dc1);
        $output['itemDetails'] = $this->RequestBomModel->getMIReceivedData($miId, $dc1);
        $output['ArrCommonHeaderData'] = $this->commonheaderdata($enquiry_id);
        $output['data'] = $this->RequestBomModel->getBOMDCprintData($enquiry_id, $miId, $dc);
        $output['DCNO'] = $dc;
        $company_id = $_SESSION['UI']['companyid']; 
        $output['company_data'] = $this->db->from('kn_company_details')->where('id', $company_id)->get()->result_array();
        
      

       
       $this->load->view('request/bom/store/bomstore_dc_pdf',$output);
   
        

        
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
    
    public function clearDraftFunction()
    {
        //echo "<pre>"; print_r($_POST); exit;
        $object = xssclean($this->input->post('data'));
        $enqId = xssclean($this->input->post('enquiry_id'));
        $reqId = xssclean($this->input->post('reqId'));
        $received_by = xssclean($this->input->post('received_by'));
        $mi_type = xssclean($this->input->post('mi_type'));
        $miId = xssclean($this->input->post('miId'));
        $req_data = json_decode($object);
        $data = $this->RequestBomModel->clearDraftFunctionn($enqId, $reqId, $miId, $req_data, $received_by, $mi_type);
        echo json_encode($data);    
    }
    
    public function getsurplusstocklist() {
        $data = $this->RequestBomModel->getsurplusstocklistt();
        echo json_encode($data);
    }
    
    public function updateSurplusDraft()
    {
        //echo "<pre>"; print_r($_POST); exit;
        $object = xssclean($this->input->post('issuedData'));
        $enqId = xssclean($this->input->post('enquiry_id'));
        $reqId = xssclean($this->input->post('reqId'));
        $received_by = xssclean($this->input->post('received_by'));
        $mi_type = xssclean($this->input->post('mi_type'));
        $pId = xssclean($this->input->post('pId'));
        $itemCode = xssclean($this->input->post('itemCode'));
        $issuedData = json_decode($object);
        $data = $this->RequestBomModel->updateSurplusDraftt($enqId, $reqId, $pId, $itemCode, $issuedData);
        echo json_encode($data);    
    }
    
    public function surplus_draftdc()
    {
            $VarId = $this->uri->segment(4);
            $reqId = $this->uri->segment(6);
            $item_code = $this->uri->segment(8);
            $pId = $this->uri->segment(10);
            if ($VarId <> '' && is_numeric(base64_decode(urldecode($VarId))))
            {
                $VarEnqId = base64_decode(urldecode($VarId));
            }
            //print_r($VarEnqId);

            if ($reqId <> '' && is_numeric(base64_decode(urldecode($reqId))))
            {
                $reqEnqId = base64_decode(urldecode($reqId));
            }
            
            $itemCode1 = base64_decode(urldecode($item_code));
        
            if ($pId <> '' && is_numeric(base64_decode(urldecode($pId))))
            {
                $pId = base64_decode(urldecode($pId));
            }
            $itemCode = "'".$itemCode1."'";
            $ArrCommonHeaderData = $this->commonheaderdata($VarEnqId);
            $surplusData = $this->RequestBomModel->getsurplusDraftCountt($itemCode);
            $draftDetails = $this->RequestBomModel->getdraftDataa($reqEnqId);
            //print_r($draftDetails); exit;
            $this->load->view('request/bom/store/surplus_draftdc',
                array( 'VarEnqId'=>$VarEnqId, 'reqId' => $reqEnqId, 'itemCode'=> $itemCode, 'pId'=> $pId,
                'ArrCommonHeaderData' => $ArrCommonHeaderData, 
                'draftDetails'=> $draftDetails,
                'surplusData' => $surplusData
            ));
        }
        
        public function getSurplusDCList() {
            $id = xssclean($this->input->post('enquiry_id'));
            $reqId = xssclean($this->input->post('reqId'));
            $itemCode = xssclean($this->input->post('itemCode'));
            $pid = xssclean($this->input->post('pId'));

            $data = $this->RequestBomModel->getSurplusDCList($id, $reqId, $itemCode,$pid);
            echo json_encode($data);
        }
        
        public function clearSurplusDraftFunction()
        {
            //echo "<pre>"; print_r($_POST); exit;
            $object = xssclean($this->input->post('data'));
            $enqId = xssclean($this->input->post('enquiry_id'));
            $reqId = xssclean($this->input->post('reqId'));
            $itemCode = xssclean($this->input->post('itemCode'));
            $req_data = json_decode($object);
            $itemCode = "'".itemCode."'";
            $data = $this->RequestBomModel->clearSurplusDraftFunctionn($enqId, $reqId, $itemCode, $req_data );
            echo json_encode($data);    
            
        }
        
        public function updateSurplusDCList() {
            //echo "<pre>"; print_r($_POST); exit;
            $object = xssclean($this->input->post('data'));
            $enqId = xssclean($this->input->post('enquiry_id'));
            $reqId = xssclean($this->input->post('reqId'));
            $pId = xssclean($this->input->post('pId'));
            $received_by = xssclean($this->input->post('received_by'));
            $amt_words = xssclean($this->input->post('amount_in_words'));
            $pay_terms = xssclean($this->input->post('payment_terms'));
            $itemCode = xssclean($this->input->post('itemCode'));
            $req_data = json_decode($object);
            //print_r($req_data);
            $data = $this->RequestBomModel->updateSurplusDCListt($enqId, $reqId, $pId, $itemCode, $req_data, $received_by, $amt_words, $pay_terms);
            echo json_encode($data);
        }
        
    public function getstocktransferlist() {
        $data = $this->RequestBomModel->getstocktransferlistt();
        echo json_encode($data);
    }
    
    public function stocktransferdetails() {
            $VarId = $this->uri->segment(4);
            $stm_ref_no1 = $this->uri->segment(6); 
            $subid=$this->subb_id;
            if ($VarId <> '' && is_numeric(base64_decode(urldecode($VarId))))
            {
                $VarEnqId       = base64_decode(urldecode($VarId));
            }
            if ($stm_ref_no1 <> '' && base64_decode(urldecode($stm_ref_no1)))
            {
                $stm_ref_no = base64_decode(urldecode($stm_ref_no1));
            }
            // echo $stm_ref_no; exit;
             $subcompany_data = $this->RequestBomModel->getsubscribercompanydetail($subid);

            $ArrCommonHeaderData = $this->commonheaderdata($VarEnqId);
            $surplusData = $this->RequestBomModel->getsurplusData($stm_ref_no);
            $surplusDatasspi = $this->RequestBomModel->getsurplusDataspi($stm_ref_no);
          
            //$requestData = $this->RequestBomModel->getRequestDataa($VarEnqId, $reqEnqId);
            //print_r($surplusData); exit;
            
            $this->load->view('request/bom/store/stocktransferdetails', 
                         array('VarEnqId'=>$VarEnqId,
                               'ArrCommonHeaderData' => $ArrCommonHeaderData,
                               'requestData' => $requestData,
                               'surplusData' => $surplusData,
                                'surplusDatasspi' => $surplusDatasspi,
                               'stm_ref_no' => $stm_ref_no,
                               'subcompany_data' => $subcompany_data
                            ));
    }

    
    
    public function getStockTransferDetails() {
        $stm_ref_no1 = xssclean($this->input->post('stm_ref_no'));
        $stm_ref_no = base64_decode(urldecode($stm_ref_no1));
        $data = $this->RequestBomModel->getStockTransferDetailss($stm_ref_no);

       
        echo json_encode($data);
    }


    public function StockTransferDetails_print() {

          $enquiry_id = $_POST['enquiry_id'];
          $stm_ref_no = $_POST['stm_ref_no'];
           $subid=$this->subb_id;

          

           
           $output['company_data'] = $this->commonheaderdata($enquiry_id);
           $output['subcompany_data'] = $this->RequestBomModel->getsubscribercompanydetail($subid);


           $output['surplusDatas'] = $this->RequestBomModel->getsurplusData($stm_ref_no);
            $output['surplusDatasspi'] = $this->RequestBomModel->getsurplusDataspi($stm_ref_no);
           //print_r($output['surplusDatas']);
           $output['surplus_data'] = $this->RequestBomModel->getStockTransferDetailss($stm_ref_no);

           //print_r($output['company_data']);

           //print_r($output['subcompany_data']);
           $this->load->view('request/bom/store/stocktransfer_print',$output);
        
        
    }


    public function StockTransferDetails_pdf() {

          $enquiry_id = $_POST['enquiry_id'];
          $stm_ref_no = $_POST['stm_ref_no'];
            $subid=$this->subb_id;
          

           
           $output['company_data'] = $this->commonheaderdata($enquiry_id);
           $output['surplusDatas'] = $this->RequestBomModel->getsurplusData($stm_ref_no);
            $output['surplusDatasspi'] = $this->RequestBomModel->getsurplusDataspi($stm_ref_no);
            $output['subcompany_data'] = $this->RequestBomModel->getsubscribercompanydetail($subid);

           //print_r($output['surplusDatas']);
           $output['surplus_data'] = $this->RequestBomModel->getStockTransferDetailss($stm_ref_no);

           //print_r($output['surplus_data']);
           $this->load->view('request/bom/store/stocktransfer_pdf',$output);
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




    
    
    
    

    }

?>