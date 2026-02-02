<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

    class Fabricrequest extends CI_Controller {

        public function __construct() {
            parent::__construct();
            //error_reporting(E_ALL);
            $this->load->helper('xssclean');
            fnIfCheckUserLoggedIn();
            $ArrUserLoggedInfo = fnGetUserLoggedInfo('1');
            $this->companyid = $ArrUserLoggedInfo['companyid'];
            $this->userid = $ArrUserLoggedInfo['id'];
            $this->mysqldatetime = date('Y-m-d H:i:s');
            $this->load->model('request/RequestFabricModel');
            $this->load->model('commonmodel');
            $this->load->model(CNFCOMPANY . 'orderentrymodel');
        }

        public function index() {
            $VarId = $this->uri->segment(4);
            if ($VarId <> '' && is_numeric(base64_decode(urldecode($VarId))))
            {
                $VarEnqId       = base64_decode(urldecode($VarId));
            }
            $ArrCommonHeaderData = $this->commonheaderdata($VarEnqId);
            $this->load->view('request/fabric/fabricrequest', 
                         array('VarEnqId'=>$VarEnqId, 'ArrCommonHeaderData' => $ArrCommonHeaderData));
        }

        
        public function createFabricRequest() {
            $id = xssclean($this->input->post('enquiry_id'));
            $req_type = xssclean($this->input->post('req_type'));
            $cutoff_date = xssclean($this->input->post('cutoff_date'));
            $merchant_note = xssclean($this->input->post('merchant_note'));
            $purchase_req_type = xssclean($this->input->post('purchase_req_type'));
            $data = $this->RequestFabricModel->createFabricRequestt($id, $req_type, $cutoff_date, $merchant_note, $purchase_req_type);
            echo json_encode($data);
        }

        // ********** YARN REQUEST STARTS HERE *********** /

        public function YarnRequestDetails() {
            $VarId = $this->uri->segment(4);
            if ($VarId <> '' && is_numeric(base64_decode(urldecode($VarId))))
            {
                $VarEnqId       = base64_decode(urldecode($VarId));
            }
            $ArrCommonHeaderData = $this->commonheaderdata($VarEnqId);
            $this->load->view('request/fabric/yarn/merchant/yarnRequestDetails', 
                         array('VarEnqId'=>$VarEnqId,
                               'ArrCommonHeaderData' => $ArrCommonHeaderData
                         ));
        }

        public function getYarnRequestDetails() {
            $enqId = xssclean($this->input->post('enquiry_id'));
            $data = $this->FabricrequestModel->getYarnRequestDetailss($enqId);
            echo json_encode($data);
        }
        
        public function sendFabricRequest() {
            $object = xssclean($this->input->post('data'));
            $id = xssclean($this->input->post('enquiry_id'));
            $req_type = xssclean($this->input->post('req_type'));
            $cutoff_date = xssclean($this->input->post('cutoff_date'));
            $merchant_note = xssclean($this->input->post('merchant_note'));
            $purchase_req_type = xssclean($this->input->post('purchase_req_type'));
            $req_data = json_decode($object);
            $data = $this->FabricrequestModel->sendFabricRequestt($req_data, $id, $req_type, $cutoff_date, $merchant_note, $purchase_req_type);
            echo json_encode($data);
        }

        // ********** YARN REQUEST ENDS HERE ************ /


        // ********** MERCHANT REQUEST SENT STARTS HERE ************ /

        public function yarnRequestlist() {
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

            $requestData = $this->FabricrequestModel->getRequestDataa($VarEnqId, $reqEnqId);
            $ArrCommonHeaderData = $this->commonheaderdata($VarEnqId);
            $this->load->view('request/fabric/yarn/merchant/requestSentList', 
                         array('VarEnqId'=>$VarEnqId, 
                               'pending_status' => 1, 
                               'reqId' => $reqEnqId, 
                               'ArrCommonHeaderData' => $ArrCommonHeaderData,
                               'requestData' => $requestData
                            ));
        }
    
        public function getYarnReceivedDetails() {
            $enqId = xssclean($this->input->post('enquiry_id'));
            $reqId = xssclean($this->input->post('request_id'));
            $data = $this->FabricrequestModel->getYarnReceivedDetails($enqId, $reqId);
            echo json_encode($data);
        }
        
        // ********** MERCHANT REQUEST SENT ENDS HERE ************ /

        // *************************************************************************************** //
        // MANAGEMENT YARN DEPARTMENT STARTS HERE 
        // ************************************************************************************** //

        public function managament() {
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

            $requestData = $this->RequestFabricModel->getRequestDetails($VarEnqId, $reqEnqId);
            $ArrCommonHeaderData = $this->commonheaderdata($VarEnqId);
            $this->load->view('request/fabric/mgmt/reqreceivedlist', 
                         array('VarEnqId'=>$VarEnqId, 'pending_status' => 1, 
                                'reqId' => $reqEnqId, 
                                'ArrCommonHeaderData' => $ArrCommonHeaderData, 
                                'requestData' => $requestData, 
                            ));
        }

        public function updateMgmtAuthorization() {

            $id = xssclean($this->input->post('enquiry_id'));
            $request_id = xssclean($this->input->post('req_id'));
            $auth_status = xssclean($this->input->post('auth_status'));
            $auth_type = xssclean($this->input->post('auth_type'));
            $mgmt_remark = xssclean($this->input->post('mgmt_remark'));

            $data = $this->RequestFabricModel->updateMgmtAuthorizationn($id, $request_id, $auth_status, $auth_type, $mgmt_remark);
            echo json_encode($data);

        }

        // *************************************************************************************** //
        // FABRIC DEPARTMENT STARTS HERE 
        // ************************************************************************************** //

        public function reqreceivedlist() {
            $this->load->view('request/fabric/dept/reqreceivedlist');
        }

        public function reqreceiveddetails() {
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
            
            $requestData = $this->RequestFabricModel->getRequestData($VarEnqId, $reqEnqId);
            $ArrCommonHeaderData = $this->commonheaderdata($VarEnqId);
            $this->load->view('request/fabric/dept/reqreceiveddetails', 
                         array('VarEnqId'=>$VarEnqId, 'pending_status' => 1, 
                                'reqId' => $reqEnqId, 
                                'ArrCommonHeaderData' => $ArrCommonHeaderData, 
                                'requestData' => $requestData, 
                            ));
        }
        
        public function updateDeptAuthorization() {
            $id = xssclean($this->input->post('req_id'));
            $req_status = xssclean($this->input->post('req_status'));
            $data = $this->RequestFabricModel->updateDeptAuthorizationn($id, $req_status);
            echo json_encode($data);
        }

        public function qalist() {
            $this->load->view('request/fabric/dept/qalist');
        }

        public function qadetails() {
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
            
            $requestData = $this->RequestFabricModel->getRequestData($VarEnqId, $reqEnqId);
            $ArrCommonHeaderData = $this->commonheaderdata($VarEnqId);
            $this->load->view('request/fabric/dept/qadetails', 
                         array('VarEnqId'=>$VarEnqId, 'pending_status' => 1, 
                                'reqId' => $reqEnqId, 
                                'ArrCommonHeaderData' => $ArrCommonHeaderData, 
                                'requestData' => $requestData, 
                            ));
        }
        
        public function updateQADetails() {
            $id = xssclean($this->input->post('req_id'));
            $dep_remarks = xssclean($this->input->post('dep_remarks'));
            $data = $this->RequestFabricModel->updateQADetailss($id, $dep_remarks);
            echo json_encode($data);
        }
        
        // ********** KNITTING PROGRAMME DETAILS STARTS HERE *********** /

        public function getKnittingProgrammeDetails() {
            $enqId = xssclean($this->input->post('enquiry_id'));
            $data = $this->RequestFabricModel->getKnittingProgrammeDetailss($enqId);
            echo json_encode($data);
        }

        // ********** KNITTING PROGRAMME ITEMIZED YARN REQUIREMENT DETAILS STARTS HERE *********** /

        public function getKnittingProgrammeItemizedYarnRequirementDetails() {
            $enqId = xssclean($this->input->post('enquiry_id'));
            $data = $this->RequestFabricModel->getKnittingProgrammeItemizedYarnRequirementDetailss($enqId);
            echo json_encode($data);
        }

        // ********** KNITTING PROGRAMME ITEMIZED YARN REQUIREMENT DETAILS ENDS HERE *********** /
       
        // *************************************************************************************** //
        // MANAGEMENT YARN DEPARTMENT STARTS HERE 
        // ************************************************************************************** //
    
        // ********** ORDER PROCESSING STARTS HERE *********** /
    
        public function commonheaderdata($VarEnquiryId)
        {
            $sizeChart    = $this->RequestFabricModel->getSizeChart($VarEnquiryId);
            $sizeMaster   = $this->RequestFabricModel->getSizeMaster($sizeChart);
    
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

        // ********** FABRIC REQUEST STARTS HERE *********** /

        public function getFabricRequestDetails() {
            $enqId = xssclean($this->input->post('enquiry_id'));
            $data = $this->RequestFabricModel->getFabricRequestDetailss($enqId);
            echo json_encode($data);
        }
        
        // ********** FABRIC REQUEST ENDS HERE ************ /

        // ********** FABRIC MANAGEMENT REQUEST STARTS HERE *********** /

        public function getManagementFabricRequestDetails() {
            $reqId = xssclean($this->input->post('request_id'));
            $data = $this->RequestFabricModel->getManagementFabricRequestDetailss($reqId);
            echo json_encode($data);
        }
        
        public function updateManagementFabricRequest() {
            $id = xssclean($this->input->post('request_id'));
            $auth_status = xssclean($this->input->post('auth_status'));
            $auth_type = xssclean($this->input->post('auth_type'));
            $mgmt_remark = xssclean($this->input->post('mgmt_remark'));
            $data = $this->RequestFabricModel->updateManagementFabricRequestt($id, $auth_status, $auth_type, $mgmt_remark);
            echo json_encode($data);
        }

        // ********** FABRIC MANAGEMENT REQUEST ENDS HERE ************ /

        // ********** FABRIC DEPARTMENT REQUEST STARTS HERE *********** /

        public function getDepartmentFabricRequestDetails() {
            $reqId = xssclean($this->input->post('request_id'));
            $data = $this->RequestFabricModel->getDepartmentFabricRequestDetailss($reqId);
            echo json_encode($data);
        }
        
        public function updateDepartmentFabricRequest() {
            $id = xssclean($this->input->post('request_id'));
            $req_status = xssclean($this->input->post('req_status'));
            $data = $this->RequestFabricModel->updateDepartmentFabricRequestt($id, $req_status);
            echo json_encode($data);
        }

        // ********** FABRIC DEPARTMENT REQUEST ENDS HERE ************ /

        // ********** FABRIC QA REQUEST STARTS HERE *********** /

        public function getQAFabricRequestDetails() {
            $reqId = xssclean($this->input->post('request_id'));
            $data = $this->RequestFabricModel->getQAFabricRequestDetailss($reqId);
            echo json_encode($data);
        }

        // ********** FABRIC QA REQUEST ENDS HERE ************ /

        // ********** FABRIC QA REQUEST STARTS HERE *********** /

        public function getQARequestDetails() {
            $reqId = xssclean($this->input->post('request_id'));
            $enqId = xssclean($this->input->post('enquiry_id'));
            $data = $this->RequestFabricModel->getQARequestDetailss($enqId, $reqId);
            echo json_encode($data);
        }
        
        public function updateQARequestDetails() {
            $id = xssclean($this->input->post('request_id'));
            $qa_cutoff_date = xssclean($this->input->post('qa_cutoff_date'));
            $sam_dept_note = xssclean($this->input->post('sam_dept_note'));
            $req_data = xssclean($this->input->post('data'));
            $data = $this->RequestFabricModel->updateQARequestDetailss($id, $req_data, $qa_cutoff_date, $sam_dept_note);
            echo json_encode($data);
        }

        // ********** FABRIC QA REQUEST ENDS HERE ************ /

        // ********** FABRIC QA QUEUE STARTS HERE *********** /

        public function getQAQueueDetails() {
            $samReqId = xssclean($this->input->post('samReqId'));
            $enqId = xssclean($this->input->post('enquiry_id'));
            $data = $this->RequestFabricModel->getQAQueueDetailss($enqId, $samReqId);
            echo json_encode($data);
        }
        
        public function updateQAQueueDetails() {
            $id = xssclean($this->input->post('samReqId'));
            $req_data = xssclean($this->input->post('data'));
            $qa_dept_remarks = xssclean($this->input->post('qa_dept_remarks'));
            $data = $this->RequestFabricModel->updateQAQueueDetailss($id, $req_data, $qa_dept_remarks);
            echo json_encode($data);
        }

        // ********** FABRIC QA QUEUE ENDS HERE ************ /

        // ********** MERCHANT FABRIC QUEUE STARTS HERE *********** /

        public function getMerchantFabricQueueDetails() {
            $samReqId = xssclean($this->input->post('samReqId'));
            $enqId = xssclean($this->input->post('enquiry_id'));
            $data = $this->RequestFabricModel->getMerchantFabricQueueDetailss($enqId, $samReqId);
            echo json_encode($data);
        }

        // ********** MERCHANT FABRIC QUEUE ENDS HERE ************ /

        // ********** FABRIC QUEUE LIST DETAIL STARTS HERE *********** /

        public function getFabricQueueDetails() {
            $samReqId = xssclean($this->input->post('samReqId'));
            $enqId = xssclean($this->input->post('enquiry_id'));
            $data = $this->RequestFabricModel->getFabricQueueDetailss($enqId, $samReqId);
            echo json_encode($data);
        }

        // ********** FABRIC QUEUE LIST DETAIL ENDS HERE ************ /

        // ********************     FABRIC REQUEST STARTS HERE    **************** //
        
        public function getYarnRequirementDetails() {
            $enqId = xssclean($this->input->post('enquiry_id'));
            $data = $this->RequestFabricModel->getYarnRequirementDetailss($enqId);
            echo json_encode($data);
        }
        
        public function getYarnReqRequirementDetails() {
            $enqId = xssclean($this->input->post('enquiry_id'));
            $data = $this->RequestFabricModel->getYarnReqRequirementDetailss($enqId);
            echo json_encode($data);
        }

        // ********************     FABRIC REQUEST ENDS HERE    **************** //

        // ************************ DYEING STARTS HERE ************************* //

        // ******** FABRIC DYEING PROGRAMME - COLOUR & DIA WISE QTY. DETAILS (FD, SDB & DDB) STARTS HERE ********* //

        public function getFabricDyeingProgramme_qty() {
            $enqId = xssclean($this->input->post('enquiry_id'));
            $data = $this->RequestFabricModel->getFabricDyeingProgramme_qtyy($enqId);
            echo json_encode($data);
        }

        // ******** FABRIC DYEING PROGRAMME - COLOUR & DIA WISE QTY. DETAILS (FD, SDB & DDB) ends HERE ********* //

        // ******** FABRIC DYEING PROGRAMME - COLOUR REFERENCE & FINISHING DETAILS (FD, SDB & DDB) STARTS HERE ********* /

        public function getFabricDyeingProgramme_finish() {
            $enqId = xssclean($this->input->post('enquiry_id'));
            $data = $this->RequestFabricModel->getFabricDyeingProgramme_finishh($enqId);
            echo json_encode($data);
        }

        // ******** FABRIC DYEING PROGRAMME - COLOUR REFERENCE & FINISHING DETAILS (FD, SDB & DDB) ENDS HERE ********* /

        // ******** YARN DYEING PROGRAMME - COLOUR WISE QTY. DETAILS CONSOLIDATED (YDS & YDJ) STARTS HERE ********* /

        public function getYarnDyeingProgramme_qty() {
            $enqId = xssclean($this->input->post('enquiry_id'));
            $data = $this->RequestFabricModel->getYarnDyeingProgramme_qtyy($enqId);
            echo json_encode($data);
        }

        // ******** YARN DYEING PROGRAMME - COLOUR WISE QTY. DETAILS CONSOLIDATED (YDS & YDJ) ends HERE ********* /

        // ******** YARN DYEING PROGRAMME - COLOUR REFERENCE & FINISHING DETAILS (YDS & YDJ) STARTS HERE ********* /

        public function getYarnDyeingProgramme_finish() {
            $enqId = xssclean($this->input->post('enquiry_id'));
            $data = $this->RequestFabricModel->getYarnDyeingProgramme_finishh($enqId);
            echo json_encode($data);
        }

        // ******** YARN DYEING PROGRAMME - COLOUR REFERENCE & FINISHING DETAILS (YDS & YDJ) ENDS HERE ********* /

        // ************************ DYEING ENDS HERE ************************* //
        
        // ************************ COMPACTING STARTS HERE ************************* //

        // ******** FABRIC WASHING COMPACTING & HEAT SETTING DETAILS STARTS HERE ********* //

        public function getFabricWashingCompatingDetails() {
            $enqId = xssclean($this->input->post('enquiry_id'));
            $data = $this->RequestFabricModel->getFabricWashingCompatingDetailss($enqId);
            echo json_encode($data);
        }

        // ************************ COMPACTING ENdS HERE ************************* //

        public function getAllRequestReceivedList() {
            header('Access-Control-Allow-Origin: *');
            $output = $this->RequestFabricModel->getAllRequestReceivedListt();
            echo json_encode($output);
        }

        public function getAllQAList() {
            header('Access-Control-Allow-Origin: *');
            $output = $this->RequestFabricModel->getAllQAListt();
            echo json_encode($output);
        }

        // Fabric QA List Details

        public function merchantqueue() {
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
            
            $requestData = $this->RequestFabricModel->getRequestDataa($VarEnqId, $reqEnqId);
            $ArrCommonHeaderData = $this->commonheaderdata($VarEnqId);
            $this->load->view('request/fabric/merchant/merchantqueue', 
                         array('VarEnqId'=>$VarEnqId, 'pending_status' => 1, 
                                'reqId' => $reqEnqId, 
                                'ArrCommonHeaderData' => $ArrCommonHeaderData, 
                                'requestData' => $requestData, 
                            ));
        }

        public function managementqueue() {
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
            
            $requestData = $this->RequestFabricModel->getRequestDataa($VarEnqId, $reqEnqId);
            $ArrCommonHeaderData = $this->commonheaderdata($VarEnqId);
            $this->load->view('request/fabric/mgmt/managementqueue', 
                         array('VarEnqId'=>$VarEnqId, 'pending_status' => 1, 
                                'reqId' => $reqEnqId, 
                                'ArrCommonHeaderData' => $ArrCommonHeaderData, 
                                'requestData' => $requestData, 
                            ));
        }

        public function yarnrequest() {
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
            
            $requestData = $this->RequestFabricModel->getRequestData($VarEnqId, $reqEnqId);
            $ArrCommonHeaderData = $this->commonheaderdata($VarEnqId);
            $this->load->view('request/fabric/yarn/yarnrequest', 
                         array('VarEnqId'=>$VarEnqId, 'pending_status' => 1, 
                                'reqId' => $reqEnqId, 
                                'ArrCommonHeaderData' => $ArrCommonHeaderData, 
                                'requestData' => $requestData, 
                                'enqEncode' => $VarId,
                                'reqEncode' => $reqId
                            ));
        }

        // FABRIC YARN REQUEST
        
        public function sendYarnReq() {
            $object = xssclean($this->input->post('data'));
            $id = xssclean($this->input->post('enquiry_id'));
            $req_id = xssclean($this->input->post('req_id'));
            $req_data = json_decode($object);
            $data = $this->RequestFabricModel->sendYarnReqq($req_data, $id, $req_id);
            echo json_encode($data);
        }        
        
        // FABRIC DRAFT PI 

        public function draftpi() {
            $VarId = $this->uri->segment(4);
            $reqEnqId = $this->uri->segment(6);
            if ($VarId <> '' && is_numeric(base64_decode(urldecode($VarId))))
            {
                $VarEnqId       = base64_decode(urldecode($VarId));
                $reqEnqId       = base64_decode(urldecode($reqEnqId));
            }
            $ArrCommonHeaderData = $this->commonheaderdata($VarEnqId);
            $requestData = $this->RequestFabricModel->getRequestData($VarEnqId, $reqEnqId);
            $this->load->view('request/fabric/yarn/draftpi',
                        array('VarEnqId'=>$VarEnqId,
                            'ArrCommonHeaderData' => $ArrCommonHeaderData,
                            'requestData' => $requestData,
                        ));
        }

        public function getDraftPIRequestDetails() {
            $reqId = xssclean($this->input->post('reqId'));
            $enqId = xssclean($this->input->post('enquiry_id'));
            $data = $this->RequestFabricModel->getDraftPIRequestDetailss($enqId, $reqId);
            echo json_encode($data);
        }

        // ********** UPDATE PURCHASE INDENT STARTS HERE *********** /

        public function updatePurchaseIndent() {
            $object = xssclean($this->input->post('data'));
            $purchaseobject = xssclean($this->input->post('purchaseIndentData'));
            $reqId = xssclean($this->input->post('reqId'));
            $req_yarn_id = xssclean($this->input->post('req_yarn_id'));
            $enqId = xssclean($this->input->post('enquiry_id'));
            $pi_cutoff_dt = xssclean($this->input->post('pi_cutoff_dt'));
            $purchase_dept_note = xssclean($this->input->post('purchase_dept_note'));
            $mode = xssclean($this->input->post('mode'));
            $vendorOption = xssclean($this->input->post('vendorOption'));
            $supply_lead_time = xssclean($this->input->post('supply_lead_time'));
            $payment_terms = xssclean($this->input->post('payment_terms'));
            $req_data = json_decode($object);
            $pur_req_data = json_decode($purchaseobject);
            $data = $this->RequestFabricModel->updatePurchaseIndentt($req_data, $enqId, $reqId, $pi_cutoff_dt, $purchase_dept_note, 
                $mode, $pur_req_data, $vendorOption, $supply_lead_time, $payment_terms, $req_yarn_id);
            echo json_encode($data);
        }

        // ********** UPDATE PURCHASE INDENT ENDS HERE ************ /

        // ***** REQUEST SENT LIST DETAILS ***** //

        public function getReqSentList() {
            $data = $this->RequestFabricModel->getReqSentListt();
            echo json_encode($data);
        }

        public function reqsentlist() {
            $this->load->view('request/fabric/dept/reqsentlist');
        }

        public function reqsentdetails() {
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
            
            $requestData = $this->RequestFabricModel->getRequestDataa($VarEnqId, $reqEnqId);
            $ArrCommonHeaderData = $this->commonheaderdata($VarEnqId);
            $this->load->view('request/fabric/dept/reqsentdetails', 
                         array('VarEnqId'=>$VarEnqId, 'pending_status' => 1, 
                                'reqId' => $reqEnqId, 
                                'ArrCommonHeaderData' => $ArrCommonHeaderData, 
                                'requestData' => $requestData, 
                            ));
        }

        public function getReqSentListDetails() {
            $reqYarnId = xssclean($this->input->post('reqYarnId'));
            $enqId = xssclean($this->input->post('enquiry_id'));
            $data = $this->RequestFabricModel->getReqSentListDetailss($enqId, $reqYarnId);
            echo json_encode($data);
        }

        // ***** REQUEST SENT LIST DETAILS ***** //

        // ****** YARN MANAGEMENT PI APPROVAL LIST ****** //

        public function getYarnMgmtPIApprovalList() {
            $data = $this->RequestFabricModel->getYarnMgmtPIApprovalListt();
            echo json_encode($data);
        }

        public function yarnmgmtpiapproval() {
            $this->load->view('request/fabric/mgmt/yarnmgmtpiapproval');
        }

        public function yarnmgmtpiapprovaldetails() {
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
            $requestData = $this->RequestFabricModel->getRequestDataa($VarEnqId, $reqEnqId);
            $this->load->view('request/fabric/mgmt/yarnmgmtpiapprovaldetails', 
                         array('VarEnqId'=>$VarEnqId,
                               'ArrCommonHeaderData' => $ArrCommonHeaderData,
                               'request_id' => $reqEnqId,
                               'requestData' => $requestData
                            ));
        }
        
        public function updateYarnMgmtPIAppl() {
            $eId = xssclean($this->input->post('enquiry_id'));
            $reqYarnId = xssclean($this->input->post('req_yarn_id'));
            $pi_appl_status = xssclean($this->input->post('pi_appl_status'));
            $mgmt_appl_remarks = xssclean($this->input->post('mgmt_appl_remarks'));
            $data = $this->RequestFabricModel->updateYarnMgmtPIAppll($eId, $reqYarnId, $pi_appl_status, $mgmt_appl_remarks);
            echo json_encode($data);
        }

        // ****** YARN MANAGEMENT PI APPROVAL LIST ****** //

        // ****** YARN MANAGEMENT PI APPROVAL LIST ****** //

        public function getYarnMgmtPIList() {
            $data = $this->RequestFabricModel->getYarnMgmtPIListt();
            echo json_encode($data);
        }

        public function yarnmgmtpilist()
        {
            $this->load->view('request/fabric/mgmt/yarn_mgmt_pi_list');
        }

        public function yarnmgmtpidetails() {
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
            $requestData = $this->RequestFabricModel->getRequestDataa($VarEnqId, $reqEnqId);
            $this->load->view('request/fabric/mgmt/yarn_mgmt_pi_details', 
                         array('VarEnqId'=>$VarEnqId,
                               'ArrCommonHeaderData' => $ArrCommonHeaderData,
                               'request_id' => $reqEnqId,
                               'requestData' => $requestData
                            ));
        }

        // ****** YARN MANAGEMENT PI APPROVAL LIST ****** //

        // ****** FABRIC PI LIST ****** //

        public function yarndeptpilist()
        {
            $this->load->view('request/fabric/dept/yarn_dept_pi_list');
        }

        public function yarndeptpidetails() {
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
            $requestData = $this->RequestFabricModel->getRequestDataa($VarEnqId, $reqEnqId);
            $this->load->view('request/fabric/dept/yarn_dept_pi_details', 
                         array('VarEnqId'=>$VarEnqId,
                               'ArrCommonHeaderData' => $ArrCommonHeaderData,
                               'request_id' => $reqEnqId,
                               'requestData' => $requestData
                            ));
        }

        // ****** FABRIC PI LIST ****** //

        // ****** YARN PAYMENT PAID LIST ****** //

        public function yarnpaymentpaidlist()
        {
            $this->load->view('request/fabric/finance/yarn_payment_paid_list');
        }

        public function yarnpaymentpaiddetails() {
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
            $requestData = $this->RequestFabricModel->getRequestDataa($VarEnqId, $reqEnqId);
            $this->load->view('request/fabric/finance/yarn_payment_paid_details',
                         array('VarEnqId'=>$VarEnqId,
                               'ArrCommonHeaderData' => $ArrCommonHeaderData,
                               'request_id' => $reqEnqId,
                               'requestData' => $requestData
                            ));
        }

        // ****** YARN PAYMENT PAID LIST ****** //

    }

?>