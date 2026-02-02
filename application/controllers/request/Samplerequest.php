<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

    class Samplerequest extends CI_Controller {

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
             $subid=$this->subb_id;
           
            $subcompany_data = $this->RequestSampleModel->getsubscribercompanydetail($subid);
            $ArrCommonHeaderData = $this->commonheaderdata($VarEnqId);
            $checkDraftorNot = $this->RequestSampleModel->checkDraftorNot($VarEnqId);
            $bomMITableData = $this->RequestSampleModel->bomMITableData($VarEnqId);
            $bomMITableDataqty = $this->RequestSampleModel->bomMITableDataqty($VarEnqId);
            $fabricMITableData = $this->RequestSampleModel->fabricMITableData($VarEnqId);
            $miDetails = $this->RequestSampleModel->miDetails($VarEnqId);
            
            $this->load->view('request/sample/samplerequest',
                         array('VarEnqId'=>$VarEnqId, 'ArrCommonHeaderData' => $ArrCommonHeaderData, 'checkDraftorNot'=> $checkDraftorNot, 
                         'bomMITableData'=> $bomMITableData, 'bomMITableDataqty'=> $bomMITableDataqty,'fabricMITableData'=> $fabricMITableData, 'miDetails'=> $miDetails, 'subcompany_data' => $subcompany_data));
        }

        public function managament() {
            $VarId = $this->uri->segment(4);
            $reqId = $this->uri->segment(6);
            if ($VarId <> '' && is_numeric(base64_decode(urldecode($VarId))))
            {
                $VarEnqId       = base64_decode(urldecode($VarId));
            }
    
            if ($reqId <> '' && is_numeric(base64_decode(urldecode($reqId))))
            {
                $reqId       = base64_decode(urldecode($reqId));
            }
            $ArrCommonHeaderData = $this->commonheaderdata($VarEnqId);
            $subid=$this->subb_id;
            $subcompany_data = $this->RequestSampleModel->getsubscribercompanydetail($subid);
            $requestData = $this->RequestSampleModel->getRequestData($VarEnqId, $reqId);
            $bomMITableData = $this->RequestSampleModel->sendbomMITableData($VarEnqId, $reqId);
            $miDetails = $this->RequestSampleModel->sendmiDetails($VarEnqId, $reqId);
            $vendorDetails = $this->RequestSampleModel->getVendorDetailss();

            $this->load->view('request/sample/managementsamplerequest',
                         array('VarEnqId'=>$VarEnqId, 'pending_status' => 1, 'reqId' => $reqId, 'subcompany_data' => $subcompany_data,
                         'ArrCommonHeaderData' => $ArrCommonHeaderData, 'requestData'=> $requestData,
                         'bomMITableData'=> $bomMITableData, 'miDetails'=> $miDetails, 'vendorDetails' => $vendorDetails));

        }

        public function department() {
            $VarId = $this->uri->segment(4);
            $reqId = $this->uri->segment(6);
            if ($VarId <> '' && is_numeric(base64_decode(urldecode($VarId))))
            {
                $VarEnqId       = base64_decode(urldecode($VarId));
            }
    
            if ($reqId <> '' && is_numeric(base64_decode(urldecode($reqId))))
            {
                $reqId       = base64_decode(urldecode($reqId));
            }
            $subid=$this->subb_id;
           
               $subcompany_data = $this->RequestSampleModel->getsubscribercompanydetail($subid);

            $ArrCommonHeaderData = $this->commonheaderdata($VarEnqId);
            $requestData = $this->RequestSampleModel->getRequestData($VarEnqId, $reqId);
            $bomMITableData = $this->RequestSampleModel->sendbomMITableData($VarEnqId, $reqId);
            $miDetails = $this->RequestSampleModel->sendmiDetails($VarEnqId, $reqId);
            $vendorDetails = $this->RequestSampleModel->getVendorDetailss();

            $this->load->view('request/sample/departmentsamplerequest', 
                    array('VarEnqId'=>$VarEnqId, 'pending_status' => 1, 'reqId' => $reqId, 
                    'ArrCommonHeaderData' => $ArrCommonHeaderData, 'requestData'=> $requestData,
                    'bomMITableData'=> $bomMITableData, 'miDetails'=> $miDetails, 'vendorDetails' => $vendorDetails,'subcompany_data' => $subcompany_data,
));
        }

        public function qa() {
            $VarId = $this->uri->segment(4);
            $reqId = $this->uri->segment(6);
            if ($VarId <> '' && is_numeric(base64_decode(urldecode($VarId))))
            {
                $VarEnqId       = base64_decode(urldecode($VarId));
            }
    
            if ($reqId <> '' && is_numeric(base64_decode(urldecode($reqId))))
            {
                $reqId       = base64_decode(urldecode($reqId));
            }
            $subid=$this->subb_id;
           
           $subcompany_data = $this->RequestSampleModel->getsubscribercompanydetail($subid);

            $ArrCommonHeaderData = $this->commonheaderdata($VarEnqId);
            $requestData = $this->RequestSampleModel->getRequestData($VarEnqId, $reqId);
            $bomMITableData = $this->RequestSampleModel->sendbomMITableData($VarEnqId, $reqId);
            $miDetails = $this->RequestSampleModel->sendmiDetails($VarEnqId, $reqId);
            $qaStatus = $this->RequestSampleModel->getQaStatus($VarEnqId, $reqId);
            $dcStatus = $this->RequestSampleModel->getDCStatus($VarEnqId, $reqId);
            $qaCompStatus = $this->RequestSampleModel->getQACompletedStatus($VarEnqId, $reqId);
            $vendorDetails = $this->RequestSampleModel->getVendorDetailss();

            $this->load->view('request/sample/qasamplerequest', 
                array( 'VarEnqId'=>$VarEnqId, 'pending_status' => 1, 'reqId' => $reqId, 
                'ArrCommonHeaderData' => $ArrCommonHeaderData, 'requestData'=> $requestData,
                'bomMITableData'=> $bomMITableData, 'miDetails'=> $miDetails, 'qaStatus'=> $qaStatus, 
            'dcStatus'=> $dcStatus, 'qaCompStatus'=>$qaCompStatus, 'vendorDetails' => $vendorDetails,'subcompany_data' => $subcompany_data,
 ));
        }

        public function qarequest() {
            $VarId = $this->uri->segment(4);
            $reqId = $this->uri->segment(6);
            $checkboxid = $this->uri->segment(8);
            if ($VarId <> '' && is_numeric(base64_decode(urldecode($VarId))))
            {
                $VarEnqId       = base64_decode(urldecode($VarId));
            }
            if ($reqId <> '' && is_numeric(base64_decode(urldecode($reqId))))
            {
                $reqId       = base64_decode(urldecode($reqId));
            }
             if ($checkboxid <> '')
            {
                $checkboxeEnqId = base64_decode(urldecode($checkboxid));
            }

            $checkboxIds = explode(',', $checkboxeEnqId); 
            $ArrCommonHeaderData = $this->commonheaderdata($VarEnqId);
            $requestData = $this->RequestSampleModel->getRequestData($VarEnqId, $reqId);
            $this->load->view('request/sample/qarequest', 
                         array('VarEnqId'=>$VarEnqId, 'reqId'=>$reqId, 'ArrCommonHeaderData' => $ArrCommonHeaderData,
                        "requestData"=> $requestData,'checkboxid' => $checkboxIds, ));
                       
        }

        public function qareceiveddetails() {
            $VarId = $this->uri->segment(4);
            $reqId = $this->uri->segment(6);
            $samId = $this->uri->segment(8);
            if ($VarId <> '' && is_numeric(base64_decode(urldecode($VarId))))
            {
                $VarEnqId       = base64_decode(urldecode($VarId));
            }
            if ($reqId <> '' && is_numeric(base64_decode(urldecode($reqId))))
            {
                $reqId       = base64_decode(urldecode($reqId));
            }
            if ($samId <> '' && is_numeric(base64_decode(urldecode($samId))))
            {
                $samId       = base64_decode(urldecode($samId));
            }
            $ArrCommonHeaderData = $this->commonheaderdata($VarEnqId);
             $subid=$this->subb_id;
             $subcompany_data = $this->RequestSampleModel->getsubscribercompanydetail($subid);

            $requestData = $this->RequestSampleModel->getRequestData($VarEnqId, $reqId);
            $this->load->view('request/sample/qareceiveddetails', 
                         array('VarEnqId'=>$VarEnqId, 'reqId'=> $reqId, 'samId'=> $samId, 'ArrCommonHeaderData' => $ArrCommonHeaderData,
                         "requestData"=> $requestData,'subcompany_data' => $subcompany_data));
        }

        public function queuelist() {
            $VarId = $this->uri->segment(4);
            $reqId = $this->uri->segment(6);
            $samId = $this->uri->segment(8);
            if ($VarId <> '' && is_numeric(base64_decode(urldecode($VarId))))
            {
                $VarEnqId       = base64_decode(urldecode($VarId));
            }
            if ($reqId <> '' && is_numeric(base64_decode(urldecode($reqId))))
            {
                $reqId       = base64_decode(urldecode($reqId));
            }
            if ($samId <> '' && is_numeric(base64_decode(urldecode($samId))))
            {
                $samId       = base64_decode(urldecode($samId));
            }
            $ArrCommonHeaderData = $this->commonheaderdata($VarEnqId);
             $subid=$this->subb_id;
            $subcompany_data = $this->RequestSampleModel->getsubscribercompanydetail($subid);
           $requestData = $this->RequestSampleModel->getSampleRequestData($VarEnqId, $reqId, $samId);
            $this->load->view('request/sample/queuelist', 
                         array('VarEnqId'=>$VarEnqId, 'ArrCommonHeaderData' => $ArrCommonHeaderData, 
                         "requestData"=> $requestData, 'reqId'=> $reqId, 'samId'=> $samId,'subcompany_data' => $subcompany_data));
        }

        public function merchantqueue() {
            $VarId = $this->uri->segment(4);
            $reqId = $this->uri->segment(6);
            $samId = $this->uri->segment(8);
            if ($VarId <> '' && is_numeric(base64_decode(urldecode($VarId))))
            {
                $VarEnqId       = base64_decode(urldecode($VarId));
            }
    
            if ($reqId <> '' && is_numeric(base64_decode(urldecode($reqId))))
            {
                $reqId       = base64_decode(urldecode($reqId));
            }

            if ($samId <> '' && is_numeric(base64_decode(urldecode($samId))))
            {
                $samId       = base64_decode(urldecode($samId));
            }

            $ArrCommonHeaderData = $this->commonheaderdata($VarEnqId);
             $subid=$this->subb_id;
           $subcompany_data = $this->RequestSampleModel->getsubscribercompanydetail($subid);
           $requestData = $this->RequestSampleModel->getRequestData($VarEnqId, $reqId);
            $bomMITableData = $this->RequestSampleModel->sendbomMITableData($VarEnqId, $reqId, $samId);
            $miDetails = $this->RequestSampleModel->sendmiDetails($VarEnqId, $reqId);
            $vendorDetails = $this->RequestSampleModel->getVendorDetailss();
            $this->load->view('request/sample/merchantqueue',
                        array('VarEnqId'=>$VarEnqId, 'pending_status' => 1, 'reqId' => $reqId, 'samId'=> $samId,
                        'ArrCommonHeaderData' => $ArrCommonHeaderData, 'requestData'=> $requestData, 'subcompany_data' => $subcompany_data,
                        'bomMITableData'=> $bomMITableData, 'miDetails'=> $miDetails,'vendorDetails'=>$vendorDetails));
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
            $sizeChart    = $this->RequestSampleModel->getSizeChart($VarEnquiryId);
            $sizeMaster   = $this->RequestSampleModel->getSizeMaster($sizeChart);
    
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
            $ArrMgmt             = $this->commonmodel->getMgmtDetails($this->companyid, $ArrEnquiryDetails['mgmtid']);
            $ArrCommonData       = $this->orderentrymodel->getCommonData($VarEnquiryId, $this->companyid);
            // echo "<pre>";
            // print_r($ArrEnquiryDetails);
            // echo "</pre>";
            $ArrCommonHeaderData = array(
                'companyName'       => @$ArrCompanyRes[0]['companyname'], 'companyAddress'    => @$ArrCompanyRes[0]['address'],
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

        // ********** SAMPLE REQUEST STARTS HERE *********** /

        public function getSampleRequestDetails() {
            $enqId = xssclean($this->input->post('enquiry_id'));
            $data = $this->RequestSampleModel->getSampleRequestDetailss($enqId);
            echo json_encode($data);
        }

        public function getVendorDetails() {
            $data = $this->RequestSampleModel->getVendorDetailss();
            echo json_encode($data);
        }
        
        public function createSampleRequest() {
            $object = xssclean($this->input->post('data'));
            $id = xssclean($this->input->post('enquiry_id'));
            $req_type = xssclean($this->input->post('req_type'));
            $cutoff_date = xssclean($this->input->post('cutoff_date'));
            $merchant_note = xssclean($this->input->post('merchant_note'));
            $mode = xssclean($this->input->post('mode'));
            $req_id = xssclean($this->input->post('req_id'));
            $req_data = json_decode($object);
            $data = $this->RequestSampleModel->createSampleRequestt($req_data, $id, $req_type, $cutoff_date, $merchant_note, $mode, $req_id);
            echo json_encode($data);
        }
        
        public function checkDraftorNot()
        {
            $enqId = xssclean($this->input->post('enquiry_id'));
            $data = $this->RequestSampleModel->checkDraftorNot($enqId);
            echo json_encode($data);
        }
        
        public function clearSampleReqDetails() {
            $object = xssclean($this->input->post('data'));
            $req_id = xssclean($this->input->post('req_id'));
            $req_data = json_decode($object);
            $data = $this->RequestSampleModel->clearSampleReqDetailss($req_data, $req_id);
            echo json_encode($data);
        }

        // ********** SAMPLE REQUEST ENDS HERE ************ /

        // ********** SAMPLE MANAGEMENT REQUEST STARTS HERE *********** /

        public function getManagementSampleRequestDetails() {
            $reqId = xssclean($this->input->post('request_id'));
            $data = $this->RequestSampleModel->getManagementSampleRequestDetailss($reqId);
            echo json_encode($data);
        }
        
        public function updateManagementSampleRequest() {
            //echo "<pre>"; print_r($_POST); exit;
            $eId = xssclean($this->input->post('enquiry_id'));
            $id = xssclean($this->input->post('request_id'));
            $auth_status = xssclean($this->input->post('auth_status'));
            $auth_type = xssclean($this->input->post('auth_type'));
            $mgmt_remark = xssclean($this->input->post('mgmt_remark'));
            $data = $this->RequestSampleModel->updateManagementSampleRequestt($eId, $id, $auth_status, $auth_type, $mgmt_remark);
            echo json_encode($data);
        }

        // ********** SAMPLE MANAGEMENT REQUEST ENDS HERE ************ /

        // ********** SAMPLE DEPARTMENT REQUEST STARTS HERE *********** /

        public function getDepartmentSampleRequestDetails() {
            $reqId = xssclean($this->input->post('request_id'));
            $data = $this->RequestSampleModel->getDepartmentSampleRequestDetailss($reqId);
            echo json_encode($data);
        }
        
        public function updateDepartmentSampleRequest() {
            //echo "<pre>"; print_r($_POST); exit;
            $object = xssclean($this->input->post('data'));
            $eid = xssclean($this->input->post('enquiry_id'));
            $id = xssclean($this->input->post('request_id'));
            $req_status = xssclean($this->input->post('req_status'));
            $dep_remarks = xssclean($this->input->post('dep_remarks'));
            $req_data = json_decode($object);
            $data = $this->RequestSampleModel->updateDepartmentSampleRequestt($eid, $id, $req_status,$dep_remarks, $req_data);
            echo json_encode($data);
        }

        // ********** SAMPLE DEPARTMENT REQUEST ENDS HERE ************ /

        // ********** SAMPLE QA REQUEST STARTS HERE *********** /
        
        public function updateQASampleRequest() {
            $object = xssclean($this->input->post('data'));
            $enquiry_id = xssclean($this->input->post('enquiry_id'));
            $id = xssclean($this->input->post('request_id'));
            $dep_remarks = xssclean($this->input->post('dep_remarks'));
            $req_data = json_decode($object);
            $data = $this->RequestSampleModel->updateQASampleRequestt($enquiry_id, $id, $dep_remarks, $req_data);
            echo json_encode($data);
        }

        // ********** SAMPLE QA REQUEST ENDS HERE ************ /

        // ********** SAMPLE QA REQUEST STARTS HERE *********** /

        public function getQARequestDetails() {
            $reqId = xssclean($this->input->post('request_id'));
            $enqId = xssclean($this->input->post('enquiry_id'));
            $data = $this->RequestSampleModel->getQARequestDetailss($enqId, $reqId);
            echo json_encode($data);
        }
        
        public function updateQARequestDetails() {
            $id = xssclean($this->input->post('request_id'));
            $enq_id = xssclean($this->input->post('enquiry_id'));
            $qa_cutoff_date = xssclean($this->input->post('qa_cutoff_date'));
            $sam_dept_note = xssclean($this->input->post('sam_dept_note'));
            $req_data = xssclean($this->input->post('data'));
            $data = $this->RequestSampleModel->updateQARequestDetailss($enq_id, $id, $req_data, $qa_cutoff_date, $sam_dept_note);
            echo json_encode($data);
        }

        // ********** SAMPLE QA REQUEST ENDS HERE ************ /

        // ********** SAMPLE QA QUEUE STARTS HERE *********** /

        public function getQAQueueDetails() {
            $samId = xssclean($this->input->post('samId'));
            $reqId = xssclean($this->input->post('reqId'));
            $enqId = xssclean($this->input->post('enquiry_id'));
            $data = $this->RequestSampleModel->getQAQueueDetailss($enqId, $reqId, $samId);
            echo json_encode($data);
        }
        
        public function updateQAQueueDetails() {
            $id = xssclean($this->input->post('samReqId'));
            $object = xssclean($this->input->post('data'));
            $qa_dept_remarks = xssclean($this->input->post('qa_dept_remarks'));
            $req_data = json_decode($object);
            $data = $this->RequestSampleModel->updateQAQueueDetailss($id, $req_data, $qa_dept_remarks);
            echo json_encode($data);
        }

        // ********** SAMPLE QA QUEUE ENDS HERE ************ /

        // ********** MERCHANT SAMPLE QUEUE STARTS HERE *********** /

        public function getMerchantSampleQueueDetails() {
            $samReqId = xssclean($this->input->post('samReqId'));
            $enqId = xssclean($this->input->post('enquiry_id'));
            $data = $this->RequestSampleModel->getMerchantSampleQueueDetailss($enqId, $samReqId);
            echo json_encode($data);
        }

        // ********** MERCHANT SAMPLE QUEUE ENDS HERE ************ /

        // ********** SAMPLE QUEUE LIST DETAIL STARTS HERE *********** /

        public function getSampleQueueDetails() {
            $samReqId = xssclean($this->input->post('samReqId'));
            $enqId = xssclean($this->input->post('enquiry_id'));
            $data = $this->RequestSampleModel->getSampleQueueDetailss($enqId, $samReqId);
            echo json_encode($data);
        }

        // ********** SAMPLE QUEUE LIST DETAIL ENDS HERE ************ /
        
        public function saveSampleReqDraft() {
            //echo "<pre>"; print_r($_POST); exit;
            $data = $this->input->post();
            $result = $this->RequestSampleModel->saveSampleReqDraftt($data);
            echo json_encode($result);
        }
        
        public function saveSampleReqDetails() {
            //echo "<pre>"; print_r($_POST); exit;
            $data = $this->input->post();
            $result = $this->RequestSampleModel->saveSampleReqDetailss($data);
            echo json_encode($result);
        }

        public function getSampleRequestList() {
            $enqId = xssclean($this->input->post('enquiry_id'));
            $data = $this->RequestSampleModel->getSampleRequestListt($enqId);
            echo json_encode($data);
        }

        public function getSampleRequestSentList() {
            $enqId = xssclean($this->input->post('enquiry_id'));
            $reqId = xssclean($this->input->post('reqId'));
            $data = $this->RequestSampleModel->getSampleRequestSentListt($enqId, $reqId);
            echo json_encode($data);
        }

        public function getQASampleRequestList() {
            $enqId = xssclean($this->input->post('enquiry_id'));
            $reqId = xssclean($this->input->post('reqId'));
            $data = $this->RequestSampleModel->getQASampleRequestListt($enqId, $reqId);
            echo json_encode($data);
        }

        public function getSeperateQASampleRequestList() {
            $enqId = xssclean($this->input->post('enquiry_id'));
            $reqId = xssclean($this->input->post('reqId'));
            $samId = xssclean($this->input->post('samId'));
            $data = $this->RequestSampleModel->getSeperateQASampleRequestListt($enqId, $reqId, $samId);
            echo json_encode($data);
        }

        public function getQAReceivedDetails() {
            $reqId = xssclean($this->input->post('request_id'));
            $enqId = xssclean($this->input->post('enquiry_id'));
            $samId = xssclean($this->input->post('sample_id'));
            $data = $this->RequestSampleModel->getQAReceivedDetailss($enqId, $reqId, $samId);
            echo json_encode($data);
        }

        public function updateQAReceivedDetails() {
            $object = xssclean($this->input->post('data'));
            $reqId = xssclean($this->input->post('request_id'));
            $enqId = xssclean($this->input->post('enquiry_id'));
            $qa_status = xssclean($this->input->post('qa_status'));
            $req_data = json_decode($object);
            $data = $this->RequestSampleModel->updateQAReceivedDetailss($enqId, $reqId, $qa_status, $req_data);
            echo json_encode($data);
        }

        // Image upload 

        public function imageUploadDetails() {
            $ArrExtensions = FILE_EXTENSIONS;
            //$VarDir = UPLOADS_SLASH . "orderenquiry" . DIRECTORY_SEPARATOR . $VarFdrName . DIRECTORY_SEPARATOR;
            $id = xssclean($this->input->post('enquiry_id'));
            $reqId = xssclean($this->input->post('request_id'));
            $deptId = xssclean($this->input->post('deptId'));
            $type = xssclean($this->input->post('type'));
           // $filepath = 'uploads/request/sample/';
            $filepath = 'uploads/request/sample'. DIRECTORY_SEPARATOR . $this->subscriber_id . DIRECTORY_SEPARATOR;
            
              if (file_exists($filepath)) {
            } else {
            mkdir($filepath, 0777, true);
             }
             //print_r($filepath);
             //die;
            
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
                            $ret[] = $this->RequestSampleModel->imageUploadDetailss($type, $id, $reqId, $fileName, $deptId);
                    } else {
                        $ret[] = 'Err';
                    }
                } else {
                    $ret[] = 'Err';
                }
                echo json_encode($ret);
            }
    
        }

         public function uploadSAMPLEReqImages() {
            $ArrExtensions = FILE_EXTENSIONS;
            //$VarDir = UPLOADS_SLASH . "orderenquiry" . DIRECTORY_SEPARATOR . $VarFdrName . DIRECTORY_SEPARATOR;
            $id = xssclean($this->input->post('enquiry_id'));
            $reqId = xssclean($this->input->post('request_id'));
            $deptId = xssclean($this->input->post('deptId'));
            $type = xssclean($this->input->post('type'));
           // $filepath = 'uploads/request/sample/';
            $filepath = 'uploads/request/sample'. DIRECTORY_SEPARATOR . $this->subscriber_id . DIRECTORY_SEPARATOR;
            
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
                            $ret[] = $this->RequestSampleModel->imageUploadDetailss($type, $id, $reqId, $fileName, $deptId);
                    } else {
                        $ret[] = 'Err';
                    }
                } else {
                    $ret[] = 'Err';
                }
                echo json_encode($ret);
            }
    
        }

        public function samplesentlist() {
            $VarId = $this->uri->segment(4);
            $reqId = $this->uri->segment(6);
            $samId = $this->uri->segment(8);
            if ($VarId <> '' && is_numeric(base64_decode(urldecode($VarId))))
            {
                $VarEnqId       = base64_decode(urldecode($VarId));
            }
    
            if ($reqId <> '' && is_numeric(base64_decode(urldecode($reqId))))
            {
                $reqId       = base64_decode(urldecode($reqId));
            }
    
            if ($samId <> '' && is_numeric(base64_decode(urldecode($samId))))
            {
                $samId       = base64_decode(urldecode($samId));
            }
           $subid=$this->subb_id;
           $subcompany_data = $this->RequestSampleModel->getsubscribercompanydetail($subid);
            $ArrCommonHeaderData = $this->commonheaderdata($VarEnqId);
            $requestData = $this->RequestSampleModel->getSampleRequestData($VarEnqId, $reqId, $samId);
            $bomMITableData = $this->RequestSampleModel->sendbomMITableData($VarEnqId, $reqId);
            $miDetails = $this->RequestSampleModel->sendmiDetails($VarEnqId, $reqId);

            $qaStatus = $this->RequestSampleModel->getQaStatus($VarEnqId, $reqId);

            $this->load->view('request/sample/samplerequestsent', 
                array( 'VarEnqId'=>$VarEnqId, 'pending_status' => 1, 'reqId' => $reqId, 'samId' => $samId,
                'ArrCommonHeaderData' => $ArrCommonHeaderData, 'requestData'=> $requestData,
                'bomMITableData'=> $bomMITableData, 'miDetails'=> $miDetails, 'qaStatus'=>$qaStatus,'subcompany_data' => $subcompany_data,
 ));
        }

        public function getRequestSentdetails() {
            $samId = xssclean($this->input->post('samId'));
            $reqId = xssclean($this->input->post('request_id'));
            $enqId = xssclean($this->input->post('enquiry_id'));
            $data = $this->RequestSampleModel->getRequestSentdetailss($enqId, $reqId, $samId);
            echo json_encode($data);
        }

        public function getDCList() {
            $enqId = xssclean($this->input->post('enquiry_id'));
            $reqId = xssclean($this->input->post('reqId'));
            $data = $this->RequestSampleModel->getDCListt($enqId, $reqId);
            echo json_encode($data);
        }

        public function updateDCList() {
            $object = xssclean($this->input->post('data'));
            $enqId = xssclean($this->input->post('enquiry_id'));
            $reqId = xssclean($this->input->post('reqId'));
            $received_by = xssclean($this->input->post('received_by'));
            $req_data = json_decode($object);
            $data = $this->RequestSampleModel->updateDCListt($enqId, $reqId, $req_data, $received_by);
            echo json_encode($data);
        }

         public function updategarmentDCList() {
            $object = xssclean($this->input->post('data'));
            $enqId = xssclean($this->input->post('enquiry_id'));
            $reqId = xssclean($this->input->post('reqId'));
            $received_by = xssclean($this->input->post('received_by'));
            $req_data = json_decode($object);
            $data = $this->RequestSampleModel->updategarmentDCListt($enqId, $reqId, $req_data, $received_by);
            echo json_encode($data);
        }

        public function dclist() {
            $VarId = $this->uri->segment(4);
            $reqId = $this->uri->segment(6);
            if ($VarId <> '' && is_numeric(base64_decode(urldecode($VarId))))
            {
                $VarEnqId       = base64_decode(urldecode($VarId));
            }
    
            if ($reqId <> '' && is_numeric(base64_decode(urldecode($reqId))))
            {
                $reqId       = base64_decode(urldecode($reqId));
            }
            $ArrCommonHeaderData = $this->commonheaderdata($VarEnqId);
            $requestData = $this->RequestSampleModel->getRequestData($VarEnqId, $reqId);
            $bomMITableData = $this->RequestSampleModel->sendbomMITableData($VarEnqId, $reqId);
            $miDetails = $this->RequestSampleModel->sendmiDetails($VarEnqId, $reqId);

            $this->load->view('request/sample/dclist',
                array( 'VarEnqId'=>$VarEnqId, 'reqId' => $reqId, 
                'ArrCommonHeaderData' => $ArrCommonHeaderData, 'requestData'=> $requestData,
                'bomMITableData'=> $bomMITableData, 'miDetails'=> $miDetails ));
        }

        public function garmentissueddetails() {
            $VarId = $this->uri->segment(4);
            $reqId = $this->uri->segment(6);
            $samId = $this->uri->segment(8);
            $subid=$this->subb_id;

            if ($VarId <> '' && is_numeric(base64_decode(urldecode($VarId))))
            {
                $VarEnqId       = base64_decode(urldecode($VarId));
            }
    
            if ($reqId <> '' && is_numeric(base64_decode(urldecode($reqId))))
            {
                $reqId       = base64_decode(urldecode($reqId));
            }

            if ($samId <> '' && is_numeric(base64_decode(urldecode($samId))))
            {
                $samId       = base64_decode(urldecode($samId));
            }

            $ArrCommonHeaderData = $this->commonheaderdata($VarEnqId);
            $subcompany_data = $this->RequestSampleModel->getsubscribercompanydetail($subid);

            $requestData = $this->RequestSampleModel->getGarmentRecListt($VarEnqId, $reqId,$samId);
            $bomMITableData = $this->RequestSampleModel->sendbomMITableData($VarEnqId, $reqId);
            $miDetails = $this->RequestSampleModel->sendmiDetails($VarEnqId, $reqId);

           
         
            $this->load->view('request/sample/garmentissueddetails',
                array( 'VarEnqId'=>$VarEnqId, 'reqId' => $reqId, 
                'ArrCommonHeaderData' => $ArrCommonHeaderData, 'requestData'=> $requestData,
                'bomMITableData'=> $bomMITableData, 'miDetails'=> $miDetails, 'samId'=>$samId, 'subcompany_data'=>$subcompany_data ));
        }

        public function garmentreceiveddetails() {
            $VarId = $this->uri->segment(4);
            $reqId = $this->uri->segment(6);
            $samId = $this->uri->segment(8);
            if ($VarId <> '' && is_numeric(base64_decode(urldecode($VarId))))
            {
                $VarEnqId       = base64_decode(urldecode($VarId));
            }
    
            if ($reqId <> '' && is_numeric(base64_decode(urldecode($reqId))))
            {
                $reqId       = base64_decode(urldecode($reqId));
            }
    
            if ($samId <> '' && is_numeric(base64_decode(urldecode($samId))))
            {
                $samId       = base64_decode(urldecode($samId));
            }
             $subid=$this->subb_id;
            $ArrCommonHeaderData = $this->commonheaderdata($VarEnqId);
            $requestData = $this->RequestSampleModel->getGarmentRecListt($VarEnqId, $reqId, $samId);
            $bomMITableData = $this->RequestSampleModel->sendbomMITableData($VarEnqId, $reqId);
            $miDetails = $this->RequestSampleModel->sendmiDetails($VarEnqId, $reqId);
            $subcompany_data = $this->RequestSampleModel->getsubscribercompanydetail($subid);

            //print_r($subcompany_data);

            $this->load->view('request/sample/garmentreceiveddetails',
                array( 'VarEnqId'=>$VarEnqId, 'reqId' => $reqId, 
                'ArrCommonHeaderData' => $ArrCommonHeaderData, 'requestData'=> $requestData,
                'bomMITableData'=> $bomMITableData, 'miDetails'=> $miDetails,'subcompany_data'=>$subcompany_data, 'samId'=>$samId ));
        }

       
       public function gdc_print()
        {
            header('Access-Control-Allow-Origin: *');
           

           $enquiry_id = $_POST['enquiry_id'];
           $request_id = $_POST['request_id'];
           $gdcno = $_POST['gdcno'];
            $samId = $_POST['samId'];
             $subid=$this->subb_id;
          
             $output['requestData']  = $this->RequestSampleModel->getGarmentRecListt($enquiry_id, $request_id, $samId);
            $output['ArrCommonHeaderData']  = $this->commonheaderdata($enquiry_id);
            $output['miDetails'] = $this->RequestSampleModel->getGarmentRecListt($enquiry_id, $request_id, $samId);
            $output['sample_data'] = $this->RequestSampleModel->getGarmentReceivedListt_print($enquiry_id, $request_id, $gdcno);
           $output['subcompany_data'] = $this->RequestSampleModel->getsubscribercompanydetail($subid);
  

          // print_r($output['sample_data']);
            $this->load->view('request/sample/gdclist_print',$output);
            
         
            
        
        }

        public function gdc_print_pdf()
        {
            header('Access-Control-Allow-Origin: *');
           

           $enquiry_id = $_POST['enquiry_id'];
           $request_id = $_POST['request_id'];
           $gdcno = $_POST['gdcno'];
            $samId = $_POST['samId'];
             $subid=$this->subb_id;
          
             $output['requestData']  = $this->RequestSampleModel->getGarmentRecListt($enquiry_id, $request_id, $samId);
            $output['ArrCommonHeaderData']  = $this->commonheaderdata($enquiry_id);
            $output['miDetails'] = $this->RequestSampleModel->getGarmentRecListt($enquiry_id, $request_id, $samId);
            $output['sample_data'] = $this->RequestSampleModel->getGarmentReceivedListt_print($enquiry_id, $request_id, $gdcno);
            $output['subcompany_data'] = $this->RequestSampleModel->getsubscribercompanydetail($subid);

            $this->load->view('request/sample/gdclist_pdf',$output);
            $html = $this->output->get_output();
      
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

        public function getGarmentReceivedList() {
            $enqId = xssclean($this->input->post('enquiry_id'));
            $reqId = xssclean($this->input->post('reqId'));
            $gdcno = xssclean($this->input->post('gdcno'));
            $data = $this->RequestSampleModel->getGarmentReceivedListt($enqId, $reqId, $gdcno);
            //print_r($data);
            echo json_encode($data);
        }

        public function updateGarmentReceivedList() {
            $object = xssclean($this->input->post('data'));
            $enqId = xssclean($this->input->post('enquiry_id'));
            $reqId = xssclean($this->input->post('reqId'));
            $item_received_status = xssclean($this->input->post('item_received_status'));
            $req_data = json_decode($object);
            $data = $this->RequestSampleModel->updateGarmentReceivedListt($enqId, $reqId, $req_data, $item_received_status);
            echo json_encode($data);
        }

        public function sampleDCDetails() {
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
            $subcompany_data = $this->RequestSampleModel->getsubscribercompanydetail($subid);
            $requestData = $this->RequestSampleModel->getDCDetails($VarEnqId, $reqEnqId, $miId);
            $miDetails = $this->RequestSampleModel->getCADMIData($miId, $dc);
            $itemDetails = $this->RequestSampleModel->getMIReceivedData($miId, $dc);
            $storeDetails = $this->RequestSampleModel->getStoreDetails();
            $ArrCommonHeaderData = $this->commonheaderdata($VarEnqId);
            
            //print_r($miDetails); exit;

            $this->load->view('request/sample/dcdetails',
                array( 'VarEnqId'=>$VarEnqId, 'reqId' => $reqEnqId, 'miId'=> $miId, 'dc'=> $dc,
                'ArrCommonHeaderData' => $ArrCommonHeaderData, 'requestData'=> $requestData,
                'miDetails'=> $miDetails, 'itemDetails' => $itemDetails, 'storeDetails' => $storeDetails, 'subcompany_data' => $subcompany_data));
        }

        public function updateMIDCList() {
            //echo "<pre>"; print_r($_POST); exit;
            $dc = xssclean($this->input->post('dc'));
            $enqId = xssclean($this->input->post('enquiry_id'));
            $reqId = xssclean($this->input->post('reqId'));
            $item_received_status = xssclean($this->input->post('item_received_status'));
            $data = $this->RequestSampleModel->updateMIDCListt($enqId, $reqId, $dc, $item_received_status);
            echo json_encode($data);
        }
        
        public function getSamDCDetails() {
        $id = xssclean($this->input->post('enquiry_id'));
        $miId = xssclean($this->input->post('miId'));
        $dc = xssclean($this->input->post('dc'));
        $data = $this->RequestSampleModel->getSamDCDetailss($id, $miId, $dc);
        echo json_encode($data);
    }
    
    public function qarequestSample() {
            $VarId = $this->uri->segment(4);
            $reqId = $this->uri->segment(6);
            $samId = $this->uri->segment(8);
            if ($VarId <> '' && is_numeric(base64_decode(urldecode($VarId))))
            {
                $VarEnqId       = base64_decode(urldecode($VarId));
            }
            if ($reqId <> '' && is_numeric(base64_decode(urldecode($reqId))))
            {
                $reqId       = base64_decode(urldecode($reqId));
            }
            if ($samId <> '' && is_numeric(base64_decode(urldecode($samId))))
            {
                $samId       = base64_decode(urldecode($samId));
            }
            $ArrCommonHeaderData = $this->commonheaderdata($VarEnqId);
            $requestData = $this->RequestSampleModel->getSampleRequestData($VarEnqId, $reqId, $samId);
            $this->load->view('request/sample/qarequestSample', 
                         array('VarEnqId'=>$VarEnqId, 'ArrCommonHeaderData' => $ArrCommonHeaderData, 
                         "requestData"=> $requestData, 'reqId'=> $reqId, 'samId'=> $samId ));
    }
    
    public function getQASampleQueueDetails() {
            $samId = xssclean($this->input->post('samId'));
            $reqId = xssclean($this->input->post('reqId'));
            $enqId = xssclean($this->input->post('enquiry_id'));
            $data = $this->RequestSampleModel->getQASampleQueueDetailss($enqId, $reqId, $samId);
            echo json_encode($data);
    }

    public function qarequest_garment() {
             $VarId = $this->uri->segment(4);
            $reqId = $this->uri->segment(6);
            if ($VarId <> '' && is_numeric(base64_decode(urldecode($VarId))))
            {
                $VarEnqId       = base64_decode(urldecode($VarId));
            }
    
            if ($reqId <> '' && is_numeric(base64_decode(urldecode($reqId))))
            {
                $reqId       = base64_decode(urldecode($reqId));
            }
            $ArrCommonHeaderData = $this->commonheaderdata($VarEnqId);
            $requestData = $this->RequestSampleModel->getRequestData($VarEnqId, $reqId);
            $bomMITableData = $this->RequestSampleModel->sendbomMITableData($VarEnqId, $reqId);
            $miDetails = $this->RequestSampleModel->sendmiDetails($VarEnqId, $reqId);

           
          

            $this->load->view('request/sample/garmentlist',
                array( 'VarEnqId'=>$VarEnqId, 'reqId' => $reqId, 
                'ArrCommonHeaderData' => $ArrCommonHeaderData, 'requestData'=> $requestData,
                'bomMITableData'=> $bomMITableData, 'miDetails'=> $miDetails, 'jobstatusData'=> $jobstatusData ));
    }


    public function getjobdclist() {
            //$samId = xssclean($this->input->post('samId'));
            $reqId = xssclean($this->input->post('reqId'));
            $enqId = xssclean($this->input->post('enquiry_id'));
            $data = $this->RequestSampleModel->getjobstatusData($enqId, $reqId);
            echo json_encode($data);

            
    }
    
    public function updateQASampleQueueDetails() {
            $id = xssclean($this->input->post('samReqId'));
            $object = xssclean($this->input->post('data'));
            $qa_dept_remarks = xssclean($this->input->post('qa_dept_remarks'));
            $req_data = json_decode($object);
            $data = $this->RequestSampleModel->updateQASampleQueueDetailss($id, $req_data, $qa_dept_remarks);
            echo json_encode($data);
    }
    
    public function updateJobCompleted() {
            
            $id = xssclean($this->input->post('enquiry_id'));
            $req_id = xssclean($this->input->post('request_id'));
            $object = xssclean($this->input->post('job_status_data'));
            $req_data = json_decode($object);
            $data = $this->RequestSampleModel->updateJobCompletedd($id, $req_id, $req_data);
            echo json_encode($data);
        }
    
    
    
    }

?>