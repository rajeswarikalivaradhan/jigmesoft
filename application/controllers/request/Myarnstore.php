<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

    class Myarnstore extends CI_Controller {

        public function __construct() {
            parent::__construct();
            //error_reporting(E_ALL);
            $this->load->helper('xssclean');
            fnIfCheckUserLoggedIn();
            $ArrUserLoggedInfo = fnGetUserLoggedInfo('1');
            $this->companyid = $ArrUserLoggedInfo['companyid'];
            $this->userid = $ArrUserLoggedInfo['id'];
            $this->mysqldatetime = date('Y-m-d H:i:s');
            $this->load->model('commonmodel');
            $this->load->model("request/myarnstoremodel");
            $this->load->model(CNFCOMPANY . 'orderentrymodel');
        }

        public function index() {
            $this->load->view('yarnstore/purchaseindentlist');
        }
        
        public function getStorepiList() {
            $data = $this->myarnstoremodel->getStorepiListt();
            echo json_encode($data);
        }

        public function getPIRequestSendDetails() {
            $reqId = xssclean($this->input->post('reqId'));
            $enqId = xssclean($this->input->post('enquiry_id'));
            $data = $this->myarnstoremodel->getPIRequestSendDetailss($enqId, $reqId);
            echo json_encode($data);
        }

        public function purchaseindentdetails() {
            $VarId = $this->uri->segment(4);
            $reqEnqId = $this->uri->segment(6);
            if ($VarId <> '' && is_numeric(base64_decode(urldecode($VarId))))
            {
                $VarEnqId       = base64_decode(urldecode($VarId));
                $reqEnqId       = base64_decode(urldecode($reqEnqId));
            }
            $ArrCommonHeaderData = $this->commonheaderdata($VarEnqId);
            $requestData = $this->myarnstoremodel->getRequestDataa($VarEnqId, $reqEnqId);
            $this->load->view('yarnstore/purchaseindentdetails',
                         array('VarEnqId'=>$VarEnqId, 'ArrCommonHeaderData' => $ArrCommonHeaderData, 'requestData' => $requestData));
        }

        public function getMerchantBomQueueDetails() {
            $reqId = xssclean($this->input->post('reqId'));
            $enqId = xssclean($this->input->post('enquiry_id'));
            $data = $this->myarnstoremodel->getMerchantBomQueueDetailss($enqId, $reqId);
            echo json_encode($data);
        }

        public function editpidetails()
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
            $requestData = $this->myarnstoremodel->getRequestDataa($VarEnqId, $reqEnqId);
            $this->load->view('yarnstore/edit_pi_details', 
                         array('VarEnqId'=>$VarEnqId,
                               'ArrCommonHeaderData' => $ArrCommonHeaderData,
                               'request_id' => $reqEnqId,
                               'requestData' => $requestData
                            ));
        }

        public function orderstocklist() {
            $this->load->view('yarnstore/orderstocklist');
        }

        public function orderstockdetails() {
            $VarId = $this->uri->segment(4);
            $reqEnqId = $this->uri->segment(6);
            if ($VarId <> '' && is_numeric(base64_decode(urldecode($VarId))))
            {
                $VarEnqId       = base64_decode(urldecode($VarId));
                $reqEnqId       = base64_decode(urldecode($reqEnqId));
            }
            $ArrCommonHeaderData = $this->commonheaderdata($VarEnqId);
            $requestData = $this->myarnstoremodel->getRequestDataa($VarEnqId, $reqEnqId);
            $this->load->view('yarnstore/orderstockdetails',
                         array('VarEnqId'=>$VarEnqId, 'ArrCommonHeaderData' => $ArrCommonHeaderData, 'requestData' => $requestData));
        }

        public function generalstocklist() {
            $this->load->view('yarnstore/generalstocklist');
        }

        public function generalstockdetails() {
            $VarId = $this->uri->segment(4);
            $reqEnqId = $this->uri->segment(6);
            if ($VarId <> '' && is_numeric(base64_decode(urldecode($VarId))))
            {
                $VarEnqId       = base64_decode(urldecode($VarId));
                $reqEnqId       = base64_decode(urldecode($reqEnqId));
            }
            $ArrCommonHeaderData = $this->commonheaderdata($VarEnqId);
            $requestData = $this->myarnstoremodel->getRequestDataa($VarEnqId, $reqEnqId);
            $this->load->view('yarnstore/generalstockdetails',
                         array('VarEnqId'=>$VarEnqId, 'ArrCommonHeaderData' => $ArrCommonHeaderData, 'requestData' => $requestData));
        }

        public function surplusstocklist() {
            $this->load->view('yarnstore/surplusstocklist');
        }

        public function surplusstockdetails() {
            $VarId = $this->uri->segment(4);
            $reqEnqId = $this->uri->segment(6);
            if ($VarId <> '' && is_numeric(base64_decode(urldecode($VarId))))
            {
                $VarEnqId       = base64_decode(urldecode($VarId));
                $reqEnqId       = base64_decode(urldecode($reqEnqId));
            }
            $ArrCommonHeaderData = $this->commonheaderdata($VarEnqId);
            $requestData = $this->myarnstoremodel->getRequestDataa($VarEnqId, $reqEnqId);
            $this->load->view('yarnstore/surplusstockdetails',
                         array('VarEnqId'=>$VarEnqId, 'ArrCommonHeaderData' => $ArrCommonHeaderData, 'requestData' => $requestData));
        }

        public function getOrderStockList() {
            $enqId = xssclean($this->input->post('enquiry_id'));
            $data = $this->myarnstoremodel->getOrderStockListt($enqId);
            echo json_encode($data);
        }

        public function getSurplusStockList() {
            $enqId = xssclean($this->input->post('enquiry_id'));
            $data = $this->myarnstoremodel->getSurplusStockListt($enqId);
            echo json_encode($data);
        }

        public function getGeneralStockList() {
            $enqId = xssclean($this->input->post('enquiry_id'));
            $data = $this->myarnstoremodel->getGeneralStockListt($enqId);
            echo json_encode($data);
        }

        public function department() {
            $VarId = $this->uri->segment(4);
            if ($VarId <> '' && is_numeric(base64_decode(urldecode($VarId))))
            {
                $VarEnqId       = base64_decode(urldecode($VarId));
            }
            $ArrCommonHeaderData = $this->commonheaderdata($VarEnqId);
            $this->load->view('request/sample/departmentsamplerequest', 
                         array('VarEnqId'=>$VarEnqId, 'ArrCommonHeaderData' => $ArrCommonHeaderData));
        }

        public function qa() {
            $VarId = $this->uri->segment(4);
            if ($VarId <> '' && is_numeric(base64_decode(urldecode($VarId))))
            {
                $VarEnqId       = base64_decode(urldecode($VarId));
            }
            $ArrCommonHeaderData = $this->commonheaderdata($VarEnqId);
            $this->load->view('request/sample/qasamplerequest', 
                         array('VarEnqId'=>$VarEnqId, 'ArrCommonHeaderData' => $ArrCommonHeaderData));
        }

        public function qarequest() {
            $VarId = $this->uri->segment(4);
            if ($VarId <> '' && is_numeric(base64_decode(urldecode($VarId))))
            {
                $VarEnqId       = base64_decode(urldecode($VarId));
            }
            $ArrCommonHeaderData = $this->commonheaderdata($VarEnqId);
            $this->load->view('request/sample/qarequest', 
                         array('VarEnqId'=>$VarEnqId, 'ArrCommonHeaderData' => $ArrCommonHeaderData));
        }

        public function queuelist() {
            $VarId = $this->uri->segment(4);
            if ($VarId <> '' && is_numeric(base64_decode(urldecode($VarId))))
            {
                $VarEnqId       = base64_decode(urldecode($VarId));
            }
            $ArrCommonHeaderData = $this->commonheaderdata($VarEnqId);
            $this->load->view('request/sample/queuelist', 
                         array('VarEnqId'=>$VarEnqId, 'ArrCommonHeaderData' => $ArrCommonHeaderData));
        }

        public function merchantqueue() {
            $VarId = $this->uri->segment(4);
            if ($VarId <> '' && is_numeric(base64_decode(urldecode($VarId))))
            {
                $VarEnqId       = base64_decode(urldecode($VarId));
            }
            $ArrCommonHeaderData = $this->commonheaderdata($VarEnqId);
            $this->load->view('request/sample/merchantqueue', 
                         array('VarEnqId'=>$VarEnqId, 'ArrCommonHeaderData' => $ArrCommonHeaderData));
        }

        public function queuelistdetail() {
            $VarId = $this->uri->segment(4);
            if ($VarId <> '' && is_numeric(base64_decode(urldecode($VarId))))
            {
                $VarEnqId       = base64_decode(urldecode($VarId));
            }
            $ArrCommonHeaderData = $this->commonheaderdata($VarEnqId);
            $this->load->view('request/sample/queuelistdetail',
                         array('VarEnqId'=>$VarEnqId, 'ArrCommonHeaderData' => $ArrCommonHeaderData));
        }
    
        // ********** ORDER PROCESSING STARTS HERE *********** /
    
        public function commonheaderdata($VarEnquiryId)
        {
            $sizeChart    = $this->myarnstoremodel->getSizeChart($VarEnquiryId);
            $sizeMaster   = $this->myarnstoremodel->getSizeMaster($sizeChart);
    
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

        // ********** SAMPLE REQUEST STARTS HERE *********** /

        public function getSampleRequestDetails() {
            $enqId = xssclean($this->input->post('enquiry_id'));
            $data = $this->myarnstoremodel->getSampleRequestDetailss($enqId);
            echo json_encode($data);
        }
        
        public function createSampleRequest() {
            $object = xssclean($this->input->post('data'));
            $id = xssclean($this->input->post('enquiry_id'));
            $req_type = xssclean($this->input->post('req_type'));
            $cutoff_date = xssclean($this->input->post('cutoff_date'));
            $merchant_note = xssclean($this->input->post('merchant_note'));
            $req_data = json_decode($object);
            $data = $this->myarnstoremodel->createSampleRequestt($req_data, $id, $req_type, $cutoff_date, $merchant_note);
            echo json_encode($data);
        }

        // ********** SAMPLE REQUEST ENDS HERE ************ /

        // ********** SAMPLE MANAGEMENT REQUEST STARTS HERE *********** /

        public function getManagementSampleRequestDetails() {
            $reqId = xssclean($this->input->post('request_id'));
            $data = $this->myarnstoremodel->getManagementSampleRequestDetailss($reqId);
            echo json_encode($data);
        }
        
        public function updateManagementSampleRequest() {
            $id = xssclean($this->input->post('request_id'));
            $auth_status = xssclean($this->input->post('auth_status'));
            $auth_type = xssclean($this->input->post('auth_type'));
            $mgmt_remark = xssclean($this->input->post('mgmt_remark'));
            $data = $this->myarnstoremodel->updateManagementSampleRequestt($id, $auth_status, $auth_type, $mgmt_remark);
            echo json_encode($data);
        }

        // ********** SAMPLE MANAGEMENT REQUEST ENDS HERE ************ /

        // ********** SAMPLE DEPARTMENT REQUEST STARTS HERE *********** /

        public function getDepartmentSampleRequestDetails() {
            $reqId = xssclean($this->input->post('request_id'));
            $data = $this->myarnstoremodel->getDepartmentSampleRequestDetailss($reqId);
            echo json_encode($data);
        }
        
        public function updateDepartmentSampleRequest() {
            $id = xssclean($this->input->post('request_id'));
            $req_status = xssclean($this->input->post('req_status'));
            $data = $this->myarnstoremodel->updateDepartmentSampleRequestt($id, $req_status);
            echo json_encode($data);
        }

        // ********** SAMPLE DEPARTMENT REQUEST ENDS HERE ************ /

        // ********** SAMPLE QA REQUEST STARTS HERE *********** /

        public function getQASampleRequestDetails() {
            $reqId = xssclean($this->input->post('request_id'));
            $data = $this->myarnstoremodel->getQASampleRequestDetailss($reqId);
            echo json_encode($data);
        }
        
        // public function updateQASampleRequest() {
        //     $id = xssclean($this->input->post('request_id'));
        //     $req_status = xssclean($this->input->post('req_status'));
        //     $data = $this->myarnstoremodel->updateQASampleRequestt($id, $req_status);
        //     echo json_encode($data);
        // }

        // ********** SAMPLE QA REQUEST ENDS HERE ************ /

        // ********** SAMPLE QA REQUEST STARTS HERE *********** /

        public function getQARequestDetails() {
            $reqId = xssclean($this->input->post('request_id'));
            $enqId = xssclean($this->input->post('enquiry_id'));
            $data = $this->myarnstoremodel->getQARequestDetailss($enqId, $reqId);
            echo json_encode($data);
        }
        
        public function updateQARequestDetails() {
            $id = xssclean($this->input->post('request_id'));
            $qa_cutoff_date = xssclean($this->input->post('qa_cutoff_date'));
            $sam_dept_note = xssclean($this->input->post('sam_dept_note'));
            $req_data = xssclean($this->input->post('data'));
            $data = $this->myarnstoremodel->updateQARequestDetailss($id, $req_data, $qa_cutoff_date, $sam_dept_note);
            echo json_encode($data);
        }

        // ********** SAMPLE QA REQUEST ENDS HERE ************ /

        // ********** SAMPLE QA QUEUE STARTS HERE *********** /

        public function getQAQueueDetails() {
            $samReqId = xssclean($this->input->post('samReqId'));
            $enqId = xssclean($this->input->post('enquiry_id'));
            $data = $this->myarnstoremodel->getQAQueueDetailss($enqId, $samReqId);
            echo json_encode($data);
        }
        
        public function updateQAQueueDetails() {
            $id = xssclean($this->input->post('samReqId'));
            $req_data = xssclean($this->input->post('data'));
            $qa_dept_remarks = xssclean($this->input->post('qa_dept_remarks'));
            $data = $this->myarnstoremodel->updateQAQueueDetailss($id, $req_data, $qa_dept_remarks);
            echo json_encode($data);
        }

        // ********** SAMPLE QA QUEUE ENDS HERE ************ /

        // ********** MERCHANT SAMPLE QUEUE STARTS HERE *********** /

        public function getMerchantSampleQueueDetails() {
            $samReqId = xssclean($this->input->post('samReqId'));
            $enqId = xssclean($this->input->post('enquiry_id'));
            $data = $this->myarnstoremodel->getMerchantSampleQueueDetailss($enqId, $samReqId);
            echo json_encode($data);
        }

        // ********** MERCHANT SAMPLE QUEUE ENDS HERE ************ /

        // ********** SAMPLE QUEUE LIST DETAIL STARTS HERE *********** /

        public function getSampleQueueDetails() {
            $samReqId = xssclean($this->input->post('samReqId'));
            $enqId = xssclean($this->input->post('enquiry_id'));
            $data = $this->myarnstoremodel->getSampleQueueDetailss($enqId, $samReqId);
            echo json_encode($data);
        }

        // ********** SAMPLE QUEUE LIST DETAIL ENDS HERE ************ /

    }

?>