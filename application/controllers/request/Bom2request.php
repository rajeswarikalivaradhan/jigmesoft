<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

    class Bom2request extends CI_Controller {

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
            $this->mysqldatetime = date('Y-m-d H:i:s');
            $this->load->model('WorkInProcessModel');
            $this->load->model('request/RequestBom2Model');
          
            $this->load->model('commonmodel');
            $this->load->model('managementmodel');
            $this->load->model(CNFCOMPANY . "msamplerequestmodel");
            $this->load->model(CNFCOMPANY . 'orderentrymodel');
            $ArrObjsubscriber_id = $this->commonmodel->getsubscriberid($this->userid = $ArrUserLoggedInfo['id']);
            $this->subscriber_id = 'Sub_Id_' . $ArrObjsubscriber_id->subscriber_id;

             $this->subb_id = $ArrObjsubscriber_id->subscriber_id;

        }

        // public function index() {
        //     $VarId = $this->uri->segment(4);
        //     if ($VarId <> '' && is_numeric(base64_decode(urldecode($VarId))))
        //     {
        //         $VarEnqId       = base64_decode(urldecode($VarId));
        //     }
        //     $ArrCommonHeaderData = $this->commonheaderdata($VarEnqId);
        //     $this->load->view('request/bom2/purchaserequest',
        //                  array('VarEnqId'=>$VarEnqId, 'ArrCommonHeaderData' => $ArrCommonHeaderData));
        // }


        public function index() {
            $VarId = $this->uri->segment(4);
            if ($VarId <> '' && is_numeric(base64_decode(urldecode($VarId))))
            {
                $VarEnqId       = base64_decode(urldecode($VarId));
            }
            $ArrCommonHeaderData = $this->commonheaderdata($VarEnqId);
            $draftVal = $this->RequestBom2Model->get_draft_valuee($VarEnqId);
            $req_datas = $this->RequestBom2Model->get_req_datass($VarEnqId);
            $sql1 = "SELECT * FROM tbl_bom_article_2_req_consld as a 
                LEFT JOIN tbl_request as b ON a.request_id=b.request_id
                WHERE a.enquiry_id='$VarEnqId' ";
            $data1 = $this->db->query($sql1)->result_array();
            for($i=0; $i < sizeof($data1); $i++)
            {
                if( isset($data1[$i]['bom_2_req_consld_id'])) {
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
            
            //print_r($request_id); exit;
             $subid=$this->subb_id;
           
            $subcompany_data = $this->RequestBom2Model->getsubscribercompanydetail($subid);
            $this->load->view('request/bom2/purchaserequest',
                         array('VarEnqId'=>$VarEnqId, 'ArrCommonHeaderData' => $ArrCommonHeaderData,'draftVal' => $draftVal, 'request_id' => $request_id1, 'req_datas' => $req_datas, 'subcompany_data' => $subcompany_data));
        }
          public function get_draft_value()
        {
            $enqId = xssclean($this->input->post('enquiry_id'));
            $data = $this->RequestBom2Model->get_draft_typee($enqId);
            echo json_encode($data);

        }

          public function getPurchaseRequestDetails_bulk() {
            $enqId = xssclean($this->input->post('enquiry_id'));
            $data = $this->RequestBom2Model->getPurchaseRequestDetailss_bulk($enqId);
            echo json_encode($data);
        }


        public function merchantqueue() {
            $VarId = $this->uri->segment(4);
            $reqEnqId = $this->uri->segment(6);
            if ($VarId <> '' && is_numeric(base64_decode(urldecode($VarId))))
            {
                $VarEnqId       = base64_decode(urldecode($VarId));
                $reqEnqId       = base64_decode(urldecode($reqEnqId));
            }
            $ArrCommonHeaderData = $this->commonheaderdata($VarEnqId);
            $requestData = $this->RequestBom2Model->getBomRequestDataa($VarEnqId, $reqEnqId);
            $this->load->view('request/bom/merchantqueue',
                         array('VarEnqId'=>$VarEnqId, 'ArrCommonHeaderData' => $ArrCommonHeaderData, 'requestData' => $requestData));
        }

        public function managementqueue() {
            $VarId = $this->uri->segment(4);
            $reqEnqId = $this->uri->segment(6);
            if ($VarId <> '' && is_numeric(base64_decode(urldecode($VarId))))
            {
                $VarEnqId       = base64_decode(urldecode($VarId));
                $reqEnqId       = base64_decode(urldecode($reqEnqId));
            }
            $ArrCommonHeaderData = $this->commonheaderdata($VarEnqId);
            $requestData = $this->RequestBom2Model->getBomRequestDataa($VarEnqId, $reqEnqId);
            $this->load->view('request/bom/managementqueue',
                         array('VarEnqId'=>$VarEnqId, 'ArrCommonHeaderData' => $ArrCommonHeaderData, 'requestData' => $requestData));
        }

        public function draftpi() {
            $VarId = $this->uri->segment(4);
            $reqEnqId = $this->uri->segment(6);
            if ($VarId <> '' && is_numeric(base64_decode(urldecode($VarId))))
            {
                $VarEnqId       = base64_decode(urldecode($VarId));
                $reqEnqId       = base64_decode(urldecode($reqEnqId));
            }
            $ArrCommonHeaderData = $this->commonheaderdata($VarEnqId);
            $requestData = $this->RequestBom2Model->getBomRequestDataa($VarEnqId, $reqEnqId);
            $this->load->view('request/purchase/draftpi',
                         array('VarEnqId'=>$VarEnqId, 'ArrCommonHeaderData' => $ArrCommonHeaderData, 'requestData' => $requestData));
        }

        // ********** ORDER PROCESSING STARTS HERE *********** /
    
        public function commonheaderdata($VarEnquiryId)
        {
            $sizeChart    = $this->RequestBom2Model->getSizeChart($VarEnquiryId);
            $sizeMaster   = $this->RequestBom2Model->getSizeMaster($sizeChart);
    
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
            // echo "<pre>";
            // print_r($ArrEnquiryDetails);
            // echo "</pre>";
            $ArrCommonHeaderData = array(
                'companyName'       => @$ArrCompanyRes[0]['companyname'], 'companyAddress'    => @$ArrCompanyRes[0]['address'],
                'VarEnquiryId'      => $VarEnquiryId, 'VarHashEnquiryId'  => @$VarHashEnquiryId, 'merchantName'      => @$ArrMerchant[0]['contactname'],
                'merchantMobile'    => @$ArrMerchant[0]['mobile'], 'merchantCode'      => @$ArrMerchant[0]['code'],
                'merchantEmail'     => @$ArrMerchant[0]['username'], 'ArrEnquiryDetails' => $ArrEnquiryDetails,
                'ArrCommonData'     => @$ArrCommonData, 'ArrTeam' => @$ArrTeam[0], 'sizeValue' => $sizeValue
            );
            return $ArrCommonHeaderData;
        }
        
        // ********** ORDER PROCESSING ENDS HERE *********** /

        // ********** BOM REQUEST STARTS HERE *********** /

        public function getPurchaseRequestDetails() {
            $enqId = xssclean($this->input->post('enquiry_id'));
            $data = $this->RequestBom2Model->getPurchaseRequestDetailss($enqId);
            echo json_encode($data);
        }
        
        public function createPurchaseRequest() {
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
            $data = $this->RequestBom2Model->createPurchaseRequestt($req_data, $id, $req_type, $cutoff_date, $merchant_note, $purchase_req_type, $mode, $type, $req_id,$bom_data);
            echo json_encode($data);
        }

        // ********** BOM REQUEST ENDS HERE ************ /

        // ********** MERCHANT BOM QUEUE STARTS HERE *********** /

        public function getMerchantBomQueueDetails() {
            $reqId = xssclean($this->input->post('reqId'));
            $enqId = xssclean($this->input->post('enquiry_id'));
            $data = $this->RequestBom2Model->getMerchantBomQueueDetailss($enqId, $reqId);
            echo json_encode($data);
        }

        // ********** MERCHANT BOM QUEUE ENDS HERE ************ /

        // ********** UPDATE MERCHANT BOM QUEUE STARTS HERE *********** /

        public function updateMerchantQueue() {
            $object = xssclean($this->input->post('data'));
            $enqId = xssclean($this->input->post('enquiry_id'));
            $req_data = json_decode($object);
            $data = $this->RequestBom2Model->updateMerchantQueuee($req_data, $enqId);
            echo json_encode($data);
        }

        // ********** UPDATE MERCHANT BOM QUEUE ENDS HERE ************ /

        // ********** MANAGEMENT BOM QUEUE STARTS HERE *********** /

        public function getManagementBomQueueDetails() {
            $reqId = xssclean($this->input->post('reqId'));
            $enqId = xssclean($this->input->post('enquiry_id'));
            $data = $this->RequestBom2Model->getManagementBomQueueDetailss($enqId, $reqId);
            echo json_encode($data);
        }
        
        public function getbomrequestImages()
    {
        header('Access-Control-Allow-Origin: *');
        $data = $this->input->post();
        $output = $this->RequestBom2Model->getbomrequestImagess($data);
        echo json_encode($output);
    }

        // ********** MANAGEMENT BOM QUEUE ENDS HERE ************ /

        // ********** UPDATE MANAGEMENT BOM QUEUE STARTS HERE *********** /

        public function updateManagementQueue() {
            $object = xssclean($this->input->post('data'));
            $enqId = xssclean($this->input->post('enquiry_id'));
            $req_data = json_decode($object);
            $data = $this->RequestBom2Model->updateManagementQueuee($req_data, $enqId);
            echo json_encode($data);
        }

        // ********** UPDATE MANAGEMENT BOM QUEUE ENDS HERE ************ /

        // ********** MANAGEMENT BOM QUEUE STARTS HERE *********** /

        public function getDraftPIRequestDetails() {
            $reqId = xssclean($this->input->post('reqId'));
            $enqId = xssclean($this->input->post('enquiry_id'));
            $data = $this->RequestBom2Model->getDraftPIRequestDetailss($enqId, $reqId);
            echo json_encode($data);
        }

        // ********** MANAGEMENT BOM QUEUE ENDS HERE ************ /

        // ********** UPDATE PURCHASE INDENT STARTS HERE *********** /

        public function updatePurchaseIndent() {
            $object = xssclean($this->input->post('data'));
            $purchaseobject = xssclean($this->input->post('purchaseIndentData'));
            $reqId = xssclean($this->input->post('reqId'));
            $enqId = xssclean($this->input->post('enquiry_id'));
            $pi_cutoff_dt = xssclean($this->input->post('pi_cutoff_dt'));
            $purchase_dept_note = xssclean($this->input->post('purchase_dept_note'));
            $mode = xssclean($this->input->post('mode'));
            $vendorOption = xssclean($this->input->post('vendorOption'));
            $supply_lead_time = xssclean($this->input->post('supply_lead_time'));
            $payment_terms = xssclean($this->input->post('payment_terms'));
            $req_data = json_decode($object);
            $pur_req_data = json_decode($purchaseobject);
            $data = $this->RequestBom2Model->updatePurchaseIndentt($req_data, $enqId, $reqId, $pi_cutoff_dt, $purchase_dept_note, 
                $mode, $pur_req_data, $vendorOption, $supply_lead_time, $payment_terms);
            echo json_encode($data);
        }

        // ********** UPDATE PURCHASE INDENT ENDS HERE ************ /

        
        // *********************************************************************************************************** 
        // MANAGEMENT DEPARTMENT STARTS HERE 
        // **********************************************************************************************************//

        public function getManagementPIApprovalList() {
            $data = $this->RequestBom2Model->getManagementPIApprovalListt();
            echo json_encode($data);
        }

        public function managementpurchaseindentapproval() {
            $data['brands'] = $this->getBrandList();
            $this->load->view('request/bom2/mgmt_pi_appl_list', $data);
        }

        public function managementpurchaseindentapprovaldetails() {
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
            $requestData = $this->RequestBom2Model->getRequestDataa($VarEnqId, $reqEnqId);
            $this->load->view('request/bom/mgmt_pi_appl_details', 
                         array('VarEnqId'=>$VarEnqId,
                               'ArrCommonHeaderData' => $ArrCommonHeaderData,
                               'request_id' => $reqEnqId,
                               'requestData' => $requestData
                            ));
        }
        
        public function updateManagementPurchaseIndentAppl() {
            $eId = xssclean($this->input->post('enquiry_id'));
            $reqId = xssclean($this->input->post('reqId'));
            $pi_req_status = xssclean($this->input->post('pi_req_status'));
            $mgmt_appl_remarks = xssclean($this->input->post('mgmt_appl_remarks'));
            $data = $this->RequestBom2Model->updateManagementPurchaseIndentAppll($eId, $reqId, $pi_req_status, $mgmt_appl_remarks);
            echo json_encode($data);
        }
        
        public function getManagementRequestDetails() {
            $enqId = xssclean($this->input->post('enquiry_id'));
            $reqId = xssclean($this->input->post('request_id'));
            $data = $this->RequestBom2Model->getManagementRequestDetailss($enqId, $reqId);
            echo json_encode($data);
        }
        
        public function updateManagementBomRequest() {
            $id = xssclean($this->input->post('request_id'));
            $auth_status = xssclean($this->input->post('auth_status'));
            $auth_type = xssclean($this->input->post('auth_type'));
            $mgmt_remark = xssclean($this->input->post('mgmt_remark'));
            $data = $this->RequestBom2Model->updateManagementBomRequestt($id, $auth_status, $auth_type, $mgmt_remark);
            echo json_encode($data);
        }
        
         public function clearPurchaseRequest()
        {
            //echo "<pre>"; print_r($_POST); exit;
            $id = xssclean($this->input->post('enquiry_id'));
            
            $data = $this->RequestBom2Model->clearPurchaseRequest( $id);
            echo json_encode($data);
        }

        public function getManagementPIList() {
            $data = $this->RequestBom2Model->getManagementPIListt();
            echo json_encode($data);
        }
         public function getPIList() {
            $data = $this->RequestBom2Model->getManagementPIListt();
            echo json_encode($data);
        }

        public function managamentpurchaseindent() {
             $data['brands'] = $this->getBrandList();
            $this->load->view('request/bom2/mgmt_pi_list', $data);
        }
         public function purchaseindent() {
            $this->load->view('request/bom2/pi_list');
        }

        public function managamentpurchaseindentdetails() {
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
            $requestData = $this->RequestBom2Model->getRequestDataa($VarEnqId, $reqEnqId);
            $this->load->view('request/bom/mgmt_pi_details', 
                         array('VarEnqId'=>$VarEnqId,
                               'ArrCommonHeaderData' => $ArrCommonHeaderData,
                               'request_id' => $reqEnqId,
                               'requestData' => $requestData
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
            $requestData = $this->RequestBom2Model->getRequestDataa($VarEnqId, $reqEnqId);
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
            $data = $this->RequestBom2Model->getPIRequestSendDetailss($enqId, $reqId);
            echo json_encode($data);
        }
        
        public function updateDepartmentPurchaseRequest() {
            $id = xssclean($this->input->post('request_id'));
            $req_status = xssclean($this->input->post('req_status'));
            $data = $this->RequestBom2Model->updateDepartmentPurchaseRequestt($id, $req_status);
            echo json_encode($data);
        }
        
        public function getPurchaseQueueDetails() {
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
            $requestData = $this->RequestBom2Model->getRequestDataa($VarEnqId, $reqEnqId);
            $this->load->view('request/bom/purchase_queue_details', 
                         array('VarEnqId'=>$VarEnqId,
                               'ArrCommonHeaderData' => $ArrCommonHeaderData,
                               'request_id' => $reqEnqId,
                               'requestData' => $requestData,
                               'enqEncode' => $VarId,
                               'reqEncode' => $reqId,
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
            $requestData = $this->RequestBom2Model->getRequestDataa($VarEnqId, $reqEnqId);
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

        public function getMerchantPIList() {
            $data = $this->RequestBom2Model->getMerchantPIListt();
            echo json_encode($data);
        }


        public function purchaseindentdetails() {
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
            $requestData = $this->RequestBom2Model->getRequestDataa($VarEnqId, $reqEnqId);
            $this->load->view('request/bom/purchase_pi_details', 
                         array('VarEnqId'=>$VarEnqId,
                               'ArrCommonHeaderData' => $ArrCommonHeaderData,
                               'request_id' => $reqEnqId,
                               'requestData' => $requestData
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
            $this->load->view('request/bom2/merchant_purchase_pi_list', $data);
        }

        public function getBrandList() 
    {
        header('Access-Control-Allow-Origin: *');
        $output = $this->RequestBom2Model->getBrandListt();
        return $output;
    }

        public function merchantpurchaseindentdetails() {
           
                            
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

        // *********************************************************************************************************** 
        // MERCHANT PURCHASE REQUEST SENT ENDS HERE 
        // **********************************************************************************************************//

        // ***********************************************************************************************************
        // BOM FINANCE DEPARTMENT STARTS HERE
        // **********************************************************************************************************//
        public function getPaymentReceivedList() {
            $data = $this->RequestBom2Model->getPaymentReceivedListt();
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
            $requestData = $this->RequestBom2Model->getRequestDataa($VarEnqId, $reqEnqId);
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
            $data = $this->RequestBom2Model->getPaymentRequestReceiveDetailss($enqId, $reqId);
            echo json_encode($data);
        }
        public function updatePaymentAdvanceDetails() {
            $object = xssclean($this->input->post('data'));
            $reqId = xssclean($this->input->post('reqId'));
            $enqId = xssclean($this->input->post('enquiry_id'));
            $req_data = json_decode($object);
            $data = $this->RequestBom2Model->updatePaymentAdvanceDetailss($req_data, $enqId, $reqId);
            echo json_encode($data);
        }
        public function getAdvancePadiList() {
            $data = $this->RequestBom2Model->getAdvancePadiListt();
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
            $requestData = $this->RequestBom2Model->getRequestDataa($VarEnqId, $reqEnqId);
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
            $data = $this->RequestBom2Model->getAdvancePaidDetailss($enqId, $reqId);
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
            $data = $this->RequestBom2Model->getStorepiListt();
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
            $requestData = $this->RequestBom2Model->getRequestDataa($VarEnqId, $reqEnqId);
            $this->load->view('request/bom/store/advance_paid_details', 
                         array('VarEnqId'=>$VarEnqId,
                               'ArrCommonHeaderData' => $ArrCommonHeaderData,
                               'request_id' => $reqEnqId,
                               'requestData' => $requestData
                            ));
        }

        public function storepurchaseindentdetails()
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
            $requestData = $this->RequestBom2Model->getRequestDataa($VarEnqId, $reqEnqId);
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
            $requestData = $this->RequestBom2Model->getRequestDataa($VarEnqId, $reqEnqId);
            $this->load->view('request/bom/store/edit_pi_details', 
                         array('VarEnqId'=>$VarEnqId,
                               'ArrCommonHeaderData' => $ArrCommonHeaderData,
                               'request_id' => $reqEnqId,
                               'requestData' => $requestData
                            ));
        }

        public function updateStorePiDetails() {
            $object = xssclean($this->input->post('inHouseData'));
            $object2 = xssclean($this->input->post('itemAccept'));
            $object3 = xssclean($this->input->post('inHouseConsolidate'));
            $enqId = xssclean($this->input->post('enquiry_id'));
            $req_data = json_decode($object);
            $req_data2 = json_decode($object2);
            $req_data3 = json_decode($object3);
            $data = $this->RequestBom2Model->updateStorePiDetailss($req_data, $req_data2, $req_data3, $enqId);
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
            $requestData = $this->RequestBom2Model->getRequestDataa($VarEnqId, $reqEnqId);
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
            $data = $this->RequestBom2Model->getNewItemDetailss($enqId, $reqId);
            echo json_encode($data);
        }

        public function surplusstocklist() {
            $this->load->view('request/bom/store/surplusstocklist');
        }       

        public function surplusstockdetails() {
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
            $requestData = $this->RequestBom2Model->getRequestDataa($VarEnqId, $reqEnqId);
            $this->load->view('request/bom/store/surplusstockdetails', 
                         array('VarEnqId'=>$VarEnqId,
                               'ArrCommonHeaderData' => $ArrCommonHeaderData,
                               'request_id' => $reqEnqId,
                               'requestData' => $requestData
                            ));
        }

        public function getSurplusStockDetails() {
            $reqId = xssclean($this->input->post('reqId'));
            $enqId = xssclean($this->input->post('enquiry_id'));
            $data = $this->RequestBom2Model->getSurplusStockDetailss($enqId, $reqId);
            echo json_encode($data);
        }

        // *********************************************************************************************************** 
        // STORE DEPARTMENT STARTS HERE 
        // **********************************************************************************************************//


    }

?>