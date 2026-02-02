<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

    class Cadrequest extends CI_Controller {

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
            $this->load->model('RequestCadModel');
            $this->load->model('commonmodel');
            $this->load->model('managementmodel');
            $this->load->model(CNFCOMPANY . "mcadrequestmodel");
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
            $subid=$this->subb_id;
           
            $subcompany_data = $this->RequestCadModel->getsubscribercompanydetail($subid);
            $this->load->view('request/cadrequest', 
                         array('VarEnqId'=>$VarEnqId, 'ArrCommonHeaderData' => $ArrCommonHeaderData, 'subcompany_data' => $subcompany_data));
        }
    
        // ********** ORDER PROCESSING STARTS HERE *********** /
    
        public function commonheaderdata($VarEnquiryId)
        {
            $sizeChart    = $this->RequestCadModel->getSizeChart($VarEnquiryId);
            $sizeMaster   = $this->RequestCadModel->getSizeMaster($sizeChart);
    
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

        // ********** CAD REQUEST STARTS HERE *********** /

        public function getCadRequestDetails() {
            $enqId = xssclean($this->input->post('enquiry_id'));
            $data = $this->RequestCadModel->getCadRequestDetailss($enqId);
            echo json_encode($data);
        }
        
        public function createCadRequest() {
            $object = xssclean($this->input->post('data'));
            $id = xssclean($this->input->post('enquiry_id'));
            $req_type = xssclean($this->input->post('req_type'));
            $cutoff_date = xssclean($this->input->post('cutoff_date'));
            $merchant_note = xssclean($this->input->post('merchant_note'));
            $req_data = json_decode($object);
            $data = $this->RequestCadModel->createCadRequestt($req_data, $id, $req_type, $cutoff_date, $merchant_note);
            echo json_encode($data);
        }
        
        public function editCadRequest() {
            $object = xssclean($this->input->post('data'));
            $reqId = xssclean($this->input->post('request_id'));
            $req_type = xssclean($this->input->post('req_type'));
            $cutoff_date = xssclean($this->input->post('cutoff_date'));
            $merchant_note = xssclean($this->input->post('merchant_note'));
            $req_data = json_decode($object);
            $data = $this->RequestCadModel->editCadRequestt($req_data, $reqId, $req_type, $cutoff_date, $merchant_note);
            echo json_encode($data);
        }

        // ********** CAD REQUEST ENDS HERE ************ /

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
            
            $requestData = $this->RequestCadModel->getRequestDataa($VarEnqId, $reqEnqId);
            $ArrCommonHeaderData = $this->commonheaderdata($VarEnqId);
            $this->load->view('request/cad/mgmtReceivedList', 
                         array('VarEnqId'=>$VarEnqId, 'pending_status' => 1, 
                                'reqId' => $reqEnqId, 
                                'ArrCommonHeaderData' => $ArrCommonHeaderData, 
                                'requestData' => $requestData, 
                            ));
        }

        public function updateMgmtAuthorization() {

            $id = xssclean($this->input->post('enquiry_id'));
            $request_id = xssclean($this->input->post('request_id'));
            $auth_status = xssclean($this->input->post('auth_status'));
            $auth_type = xssclean($this->input->post('auth_type'));
            $mgmt_remark = xssclean($this->input->post('mgmt_remark'));

            $data = $this->RequestCadModel->updateMgmtAuthorizationn($id, $request_id, $auth_status, $auth_type, $mgmt_remark);
            echo json_encode($data);
        }
       
        // *************************************************************************************** //
        // CAD DEPARTMENT STARTS HERE 
        // ************************************************************************************** //
       
        public function cadDeptDetails() {
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
             $subid=$this->subb_id;
           
  $subcompany_data = $this->RequestCadModel->getsubscribercompanydetail($subid);

            $requestData = $this->RequestCadModel->getRequestDataa($VarEnqId, $reqEnqId);
            //print_r($requestData);die;
            $ArrCommonHeaderData = $this->commonheaderdata($VarEnqId);
            $this->load->view('request/cad/cadDeptDetails', 
                         array('VarEnqId'=>$VarEnqId, 'pending_status' => 1,
                               'reqId' => $reqEnqId, 
                               'ArrCommonHeaderData' => $ArrCommonHeaderData,
                               'requestData' => $requestData,
                               'subcompany_data' => $subcompany_data,
 
                            ));
        }
       
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

            $requestData = $this->RequestCadModel->getRequestDataa($VarEnqId, $reqEnqId);
            $ArrCommonHeaderData = $this->commonheaderdata($VarEnqId);
            $subid=$this->subb_id;
           $subcompany_data = $this->RequestCadModel->getsubscribercompanydetail($subid);

            $this->load->view('request/cad/merchantqueuedetails', 
                         array('VarEnqId'=>$VarEnqId, 'pending_status' => 1,
                               'reqId' => $reqEnqId, 
                               'ArrCommonHeaderData' => $ArrCommonHeaderData,
                               'requestData' => $requestData, 'subcompany_data' => $subcompany_data,
 
                            ));
        }

        public function updateCadDepartStatus() {

            $object = xssclean($this->input->post('data'));
            $id = xssclean($this->input->post('enquiry_id'));
            $req_id = xssclean($this->input->post('request_id'));
            $deprt_approval = xssclean($this->input->post('req_status'));
            $dep_remarks = xssclean($this->input->post('dep_remarks'));
            $req_data = json_decode($object);
            $data = $this->RequestCadModel->updateCadDepartStatuss($id, $req_id, $deprt_approval, $dep_remarks, $req_data);
            echo json_encode($data);
        }

        public function cadDeptQueueDetails() {
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
              $subid=$this->subb_id;
           
  $subcompany_data = $this->RequestCadModel->getsubscribercompanydetail($subid);

            $requestData = $this->RequestCadModel->getRequestDataa($VarEnqId, $reqEnqId);
            $qaStatus = $this->RequestCadModel->getQAStatus($VarEnqId, $reqEnqId);
            $jobqaStatus = $this->RequestCadModel->getQAjobStatus($VarEnqId, $reqEnqId);
            $jobreqstatus = $this->RequestCadModel->getreqStatus($VarEnqId, $reqEnqId);
            $qaCompStatus = $this->RequestCadModel->getQACompletedStatus($VarEnqId, $reqEnqId);
            //echo "<pre>"; print_r($qaCompStatus); exit;
            $ArrCommonHeaderData = $this->commonheaderdata($VarEnqId);
            $this->load->view('request/cad/cadDeptQueueDetails', 
                         array('VarEnqId'=>$VarEnqId, 'pending_status' => 1,
                               'reqId' => $reqEnqId, 
                               'ArrCommonHeaderData' => $ArrCommonHeaderData,
                               'requestData' => $requestData, 
                               'qaStatus' => $qaStatus,
                                'jobqaStatus' => $jobqaStatus,
                               'qaCompStatus' => $qaCompStatus,
                               'jobreqstatus' => $jobreqstatus,
                               'subcompany_data' => $subcompany_data,

                            ));
        }

        public function UpdateCadQueueRemark() {

            $id = xssclean($this->input->post('enquiry_id'));
            $req_id = xssclean($this->input->post('request_id'));
            $dep_remarks = xssclean($this->input->post('dep_remarks'));
            $object = xssclean($this->input->post('job_status_data'));
            $req_data = json_decode($object);
            $data = $this->RequestCadModel->UpdateCadQueueRemarkk($id, $req_id, $dep_remarks, $req_data);
            echo json_encode($data);
        }
        
        public function updateJobCompleted() {

            $id = xssclean($this->input->post('enquiry_id'));
            $req_id = xssclean($this->input->post('request_id'));
            $object = xssclean($this->input->post('job_status_data'));
            $req_data = json_decode($object);
            $data = $this->RequestCadModel->updateJobCompletedd($id, $req_id, $req_data);
            echo json_encode($data);
        }

        public function getrequestDetails() {
            $enqId = xssclean($this->input->post('enquiry_id'));
            $reqId = xssclean($this->input->post('reqId'));
            $data = $this->RequestCadModel->getrequestDetailss($enqId, $reqId);
            echo json_encode($data);
        }        

        public function getcadqarequestDetails() {
            $enqId = xssclean($this->input->post('enquiry_id'));
            $reqId = xssclean($this->input->post('reqId'));
            $data = $this->RequestCadModel->getcadqarequestDetailss($enqId, $reqId);
            // var_dump($data);
            // file_put_contents('error_log', print_r($data, true));
            // die;
            echo json_encode($data);
        }        

        public function qarequest() {
            $VarId = $this->uri->segment(4);
            $reqId = $this->uri->segment(6);
            $checkboxid = $this->uri->segment(8);
            
            if ($VarId <> '' && is_numeric(base64_decode(urldecode($VarId))))
            {
                $VarEnqId = base64_decode(urldecode($VarId));
            }
    
            if ($reqId <> '' && is_numeric(base64_decode(urldecode($reqId))))
            {
                $reqEnqId = base64_decode(urldecode($reqId));
            }
            
            if ($checkboxid <> '')
            {
                $checkboxeEnqId = base64_decode(urldecode($checkboxid));
            }

            $checkboxIds = explode(',', $checkboxeEnqId); 

            //print_r($checkboxeEnqId);
            //die;
               $subid=$this->subb_id;
           
             $subcompany_data = $this->RequestCadModel->getsubscribercompanydetail($subid);
            $requestData = $this->RequestCadModel->getRequestDataa($VarEnqId, $reqEnqId);
            $ArrCommonHeaderData = $this->commonheaderdata($VarEnqId);
            $this->load->view('request/cad/qarequest', 
                         array('VarEnqId'=>$VarEnqId, 'pending_status' => 1,
                               'reqId' => $reqEnqId, 
                               'ArrCommonHeaderData' => $ArrCommonHeaderData,
                               'requestData' => $requestData, 
                               'checkboxid' => $checkboxIds, 
                               'subcompany_data' => $subcompany_data,
                            ));
        }

        public function updateCadQARequest() {

            $id = xssclean($this->input->post('enquiry_id'));
            $req_id = xssclean($this->input->post('request_id'));
            $qa_cutoff_date = xssclean($this->input->post('qa_cutoff_date'));
            $dep_note = xssclean($this->input->post('dep_note'));
            $object = xssclean($this->input->post('cad_req_data'));
            $req_data = json_decode($object);
            $data = $this->RequestCadModel->updateCadQARequestt($id, $req_id, $qa_cutoff_date, $dep_note, $req_data);
            echo json_encode($data);
        }     

        public function cadDeptSentDetails() {
            $VarId = $this->uri->segment(4);
            $reqId = $this->uri->segment(6);
            $cadId = $this->uri->segment(8);
            
            if ($VarId <> '' && is_numeric(base64_decode(urldecode($VarId))))
            {
                $VarEnqId = base64_decode(urldecode($VarId));
            }
    
            if ($reqId <> '' && is_numeric(base64_decode(urldecode($reqId))))
            {
                $reqEnqId = base64_decode(urldecode($reqId));
            }

            if ($cadId <> '' && is_numeric(base64_decode(urldecode($cadId))))
            {
                $cadEnqId = base64_decode(urldecode($cadId));
            }
            $subid=$this->subb_id;
           
  $subcompany_data = $this->RequestCadModel->getsubscribercompanydetail($subid);


            $requestData = $this->RequestCadModel->getRequestDataa($VarEnqId, $reqEnqId);
            $ArrCommonHeaderData = $this->commonheaderdata($VarEnqId);
            $this->load->view('request/cad/cadsentdetails', 
                         array('VarEnqId'=>$VarEnqId, 'pending_status' => 1,
                               'reqId' => $reqEnqId, 
                               'cadId' => $cadEnqId, 
                               'ArrCommonHeaderData' => $ArrCommonHeaderData,
                               'requestData' => $requestData,
                               'subcompany_data' => $subcompany_data,
 

                            ));
        }

        public function qareceiveddetails() {
            $VarId = $this->uri->segment(4);
            $reqId = $this->uri->segment(6);
            $cadId = $this->uri->segment(8);
            if ($VarId <> '' && is_numeric(base64_decode(urldecode($VarId))))
            {
                $VarEnqId = base64_decode(urldecode($VarId));
            }
    
            if ($reqId <> '' && is_numeric(base64_decode(urldecode($reqId))))
            {
                $reqEnqId = base64_decode(urldecode($reqId));
            }
            if ($cadId <> '' && is_numeric(base64_decode(urldecode($cadId))))
            {
                $cadEnqId = base64_decode(urldecode($cadId));
            }
            $subid=$this->subb_id;
            $subcompany_data = $this->RequestCadModel->getsubscribercompanydetail($subid);
            $requestData = $this->RequestCadModel->getRequestDataa($VarEnqId, $reqEnqId);
            $ArrCommonHeaderData = $this->commonheaderdata($VarEnqId);
            $this->load->view('request/cad/qareceiveddetails',
                         array('VarEnqId'=>$VarEnqId, 'pending_status' => 1,
                               'reqId' => $reqEnqId,
                               'cadId' => $cadEnqId,
                               'ArrCommonHeaderData' => $ArrCommonHeaderData,
                               'requestData' => $requestData,'subcompany_data' => $subcompany_data,
                            ));
        }

        public function updateCadQAReceivedList() {

            $object = xssclean($this->input->post('tbl_data'));
            $id = xssclean($this->input->post('enquiry_id'));
            $req_id = xssclean($this->input->post('request_id'));
            $qa_req_status = xssclean($this->input->post('qa_req_status'));
            $qa_dept_remarks = xssclean($this->input->post('qa_dept_remarks'));
            $req_data = json_decode($object);
            $data = $this->RequestCadModel->updateCadQAReceivedListt($id, $req_id, $qa_req_status, $qa_dept_remarks, $req_data);
            echo json_encode($data);
        }

        public function queuelist() {
            $VarId = $this->uri->segment(4);
            $reqId = $this->uri->segment(6);
            $cadId = $this->uri->segment(8);
            if ($VarId <> '' && is_numeric(base64_decode(urldecode($VarId))))
            {
                $VarEnqId = base64_decode(urldecode($VarId));
            }
    
            if ($reqId <> '' && is_numeric(base64_decode(urldecode($reqId))))
            {
                $reqEnqId = base64_decode(urldecode($reqId));
            }
    
            if ($cadId <> '' && is_numeric(base64_decode(urldecode($cadId))))
            {
                $cadEnqId = base64_decode(urldecode($cadId));
            }

             $subid=$this->subb_id;
            $subcompany_data = $this->RequestCadModel->getsubscribercompanydetail($subid);
            $requestData = $this->RequestCadModel->getRequestDataa($VarEnqId, $reqEnqId);
            $ArrCommonHeaderData = $this->commonheaderdata($VarEnqId);
            $this->load->view('request/cad/queuelist',
                         array('VarEnqId'=>$VarEnqId, 'pending_status' => 1,
                               'reqId' => $reqEnqId,
                               'cadId' => $cadEnqId,
                               'ArrCommonHeaderData' => $ArrCommonHeaderData,
                               'requestData' => $requestData,'subcompany_data' => $subcompany_data,

                            ));
        }

        public function getqaqueueDetails() {
            $enqId = xssclean($this->input->post('enquiry_id'));
            $reqId = xssclean($this->input->post('reqId'));
            $cadId = xssclean($this->input->post('cadId'));
            $data = $this->RequestCadModel->getqaqueueDetailss($enqId, $reqId, $cadId);
            echo json_encode($data);
        }  

        public function UpdateCadQAQueueList() {
            $enqId = xssclean($this->input->post('enquiry_id'));
            $reqId = xssclean($this->input->post('reqId'));
            $qa_dept_remarks = xssclean($this->input->post('qa_dept_remarks'));
            $object = xssclean($this->input->post('data'));
            $req_data = json_decode($object);
            $data = $this->RequestCadModel->UpdateCadQAQueueListt($enqId, $reqId, $qa_dept_remarks, $req_data);
            echo json_encode($data);
        }  

        // *************************************************************************************** //
        // CAD DEPARTMENT ENDS HERE 
        // ************************************************************************************** //


        // *************************************************************************************** //
        // IMAGE UPLOAD
        // ************************************************************************************** //

        public function uploadCADReqImages() {
            $ArrExtensions = FILE_EXTENSIONS;
            $id = xssclean($this->input->post('enquiry_id'));
            $reqId = xssclean($this->input->post('request_id'));
            $deptId = xssclean($this->input->post('deptId'));
            $type = xssclean($this->input->post('type'));
                   ///////////////////////
            $ArrUserLoggedInfo = fnGetUserLoggedInfo('1');
            $user_id=$this->userid = $ArrUserLoggedInfo['id'];
            $ArrObjsubscriber_id = $this->commonmodel->getsubscriberid($user_id);
            $subscriber_ids=$ArrObjsubscriber_id->subscriber_id;
            $subscriber_id='Sub_Id_'.$subscriber_ids;
            ///////////////
            $filepath = "";
          
                //$filepath = 'uploads/request/cad/';
                $filepath = 'uploads/request/cad'. DIRECTORY_SEPARATOR . $subscriber_id . DIRECTORY_SEPARATOR;
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
                    /////////////////////////////
                     $fileName = preg_replace('/\s+/', '_', $fileName); // Replace spaces with underscores
                    $fileName = preg_replace('/[^A-Za-z0-9_\-\.]/', '', $fileName); // Remove unwanted characters
                    ////////////////////////////
                    /**MAX file size 7 MB**/
                    if ($_FILES["myFile"]["size"] <= MAXUPLSIZE) {
                        if (move_uploaded_file($_FILES["myFile"]["tmp_name"], $filepath . $fileName));
                        $ret = $this->RequestCadModel->uploadCADReqImagess($type, $id, $fileName, $reqId, $deptId);
                    } else {
                        $ret[] = 'Error';
                    }
                } else {
                    $ret[] = 'Error';
                }
                echo json_encode($ret);
            }
            else {
                $res['status'] = "not upload";
                echo json_encode($res);
            }
    
        }

        // ************* CAD MI START ************* //

        public function cadIndentDetails() {
            $VarId = $this->uri->segment(4);
            $reqId = $this->uri->segment(6);
            $miId = $this->uri->segment(8);
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
              $subid=$this->subb_id;
           
          $subcompany_data = $this->RequestCadModel->getsubscribercompanydetail($subid);


            $requestData = $this->RequestCadModel->getRequestDataa($VarEnqId, $reqEnqId);
            $miDetails = $this->RequestCadModel->getMIData($miId);
            $ArrCommonHeaderData = $this->commonheaderdata($VarEnqId);
            $dcStatus = $this->RequestCadModel->checkDCStatus($miId);
             $ArrUserLoggedInfo = fnGetUserLoggedInfo('1');
             $login_id=$this->userid_type = $ArrUserLoggedInfo['usertype'];
            $this->load->view('request/cad/mi/cadindentdetails',
                         array('VarEnqId'=>$VarEnqId, 'pending_status' => 1,
                               'reqId' => $reqEnqId,
                               'miId' => $miId,
                               'miDetails' => $miDetails,
                               'dcStatus' => $dcStatus,
                               'login_id' => $login_id,
                               'ArrCommonHeaderData' => $ArrCommonHeaderData,
                               'requestData' => $requestData,
                               'subcompany_data' => $subcompany_data,

                            ));
        }

        public function getCadMIDetails()
        {
            $enqId = xssclean($this->input->post('enquiry_id'));
            $reqId = xssclean($this->input->post('reqId'));
            $miId = xssclean($this->input->post('miId'));
            $data = $this->RequestCadModel->getCadMIDetailss($enqId, $reqId, $miId);
            echo json_encode($data);
        }

        public function updateCadIndentDetails() {
            $id = xssclean($this->input->post('enquiry_id'));
            $reqId = xssclean($this->input->post('reqId'));
            $object = xssclean($this->input->post('cad_mi_tbl_data'));
            $req_data = json_decode($object);
            $data = $this->RequestCadModel->updateCadIndentDetailss($id, $reqId, $req_data);
            echo json_encode($data);
        }

        // ************* CAD MI END ************* //

        // ************* CAD DC END ************* //

        public function dclist()
        {
            $VarId = $this->uri->segment(4);
            $reqId = $this->uri->segment(6);
            $miId = $this->uri->segment(8);
            $Usertype=5;
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
            $subid=$this->subb_id;
           
            $subcompany_data = $this->RequestCadModel->getsubscribercompanydetail($subid);
            $requestData = $this->RequestCadModel->getDCDetails($VarEnqId, $reqEnqId, $miId);
            $miDetails = $this->RequestCadModel->getMIData($miId);
            $ArrCommonHeaderData = $this->commonheaderdata($VarEnqId);
             $samplelogin_data = $this->RequestCadModel->getuserdetail($Usertype,$subid);

            $this->load->view('request/cad/mi/dclist',
                array( 'VarEnqId'=>$VarEnqId, 'reqId' => $reqEnqId, 'miId'=> $miId,
                'ArrCommonHeaderData' => $ArrCommonHeaderData, 'requestData'=> $requestData,
                'miDetails'=> $miDetails ,
                'subcompany_data' => $subcompany_data,
                'samplelogin_data' => $samplelogin_data,));
        }

        public function cadDCDetails()
        {
            $VarId = $this->uri->segment(4);
            $reqId = $this->uri->segment(6);
            $miId = $this->uri->segment(8);
            $dc = $this->uri->segment(10);
            $Usertype=5;
            $subid=$this->subb_id;

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
            
            $dc1 = $dc;
            
            
            $requestData = $this->RequestCadModel->getDCDetails($VarEnqId, $reqEnqId, $miId);
            $miDetails = $this->RequestCadModel->getCADMIData($miId, $dc);
            $ArrCommonHeaderData = $this->commonheaderdata($VarEnqId);
            $subcompany_data = $this->RequestCadModel->getsubscribercompanydetail($subid);
            $samplelogin_data = $this->RequestCadModel->getuserdetail($Usertype,$subid);

            //print_r($miDetails);



            $this->load->view('request/cad/mi/caddclist',
                array( 'VarEnqId'=>$VarEnqId, 'reqId' => $reqEnqId, 'miId'=> $miId, 'usertype'=> $this->userid_type,'dc'=> $dc, 'dc1'=> $dc1,
                'ArrCommonHeaderData' => $ArrCommonHeaderData, 'requestData'=> $requestData,
                'miDetails'=> $miDetails,'subcompany_data'=>$subcompany_data,'samplelogin_data'=>$samplelogin_data ));
        }

        public function getDCList() {
            $id = xssclean($this->input->post('enquiry_id'));
            $miId = xssclean($this->input->post('miId'));
            $data = $this->RequestCadModel->getDCListt($id, $miId);
            echo json_encode($data);
        }

        public function getCADDCList() {
            $id = xssclean($this->input->post('enquiry_id'));
            $miId = xssclean($this->input->post('miId'));
            $dc = xssclean($this->input->post('dc'));
            $data = $this->RequestCadModel->getCADDCListt($id, $miId, $dc);
            echo json_encode($data);
        }
        
        public function updateDCList() {
            $object = xssclean($this->input->post('data'));
            $enqId = xssclean($this->input->post('enquiry_id'));
            $reqId = xssclean($this->input->post('reqId'));
            $received_by = xssclean($this->input->post('received_by'));
            $mi_type = xssclean($this->input->post('mi_type'));
            $miId = xssclean($this->input->post('miId'));
            $req_data = json_decode($object);
            $data = $this->RequestCadModel->updateDCListt($enqId, $reqId, $miId, $req_data, $received_by, $mi_type);
            echo json_encode($data);
        }


         public function updateMIDCList() {
           
            $dc = xssclean($this->input->post('dc'));
            $enqId = xssclean($this->input->post('enquiry_id'));
            $reqId = xssclean($this->input->post('reqId'));
            $item_received_status = xssclean($this->input->post('item_received_status'));
            $data = $this->RequestCadModel->updateMIDCListt($enqId, $reqId, $dc, $item_received_status);
            echo json_encode($data);
        }
        
        public function qarequestcad() {
            $VarId = $this->uri->segment(4);
            $reqId = $this->uri->segment(6);
            $cadId = $this->uri->segment(8);
            if ($VarId <> '' && is_numeric(base64_decode(urldecode($VarId))))
            {
                $VarEnqId = base64_decode(urldecode($VarId));
            }
    
            if ($reqId <> '' && is_numeric(base64_decode(urldecode($reqId))))
            {
                $reqEnqId = base64_decode(urldecode($reqId));
            }
    
            if ($cadId <> '' && is_numeric(base64_decode(urldecode($cadId))))
            {
                $cadEnqId = base64_decode(urldecode($cadId));
            }

            $requestData = $this->RequestCadModel->getRequestDataa($VarEnqId, $reqEnqId);
            $ArrCommonHeaderData = $this->commonheaderdata($VarEnqId);
            $this->load->view('request/cad/qarequestcad',
                         array('VarEnqId'=>$VarEnqId, 'pending_status' => 1,
                               'reqId' => $reqEnqId,
                               'cadId' => $cadEnqId,
                               'ArrCommonHeaderData' => $ArrCommonHeaderData,
                               'requestData' => $requestData,
                            ));
        }
        
        public function getqacadqueueDetails() {
            $enqId = xssclean($this->input->post('enquiry_id'));
            $reqId = xssclean($this->input->post('reqId'));
            $cadId = xssclean($this->input->post('cadId'));
            $data = $this->RequestCadModel->getqacadqueueDetailss($enqId, $reqId, $cadId);
            echo json_encode($data);
        }
        
        public function UpdateCadQAQueueData() {
            $enqId = xssclean($this->input->post('enquiry_id'));
            $reqId = xssclean($this->input->post('reqId'));
            $qa_dept_remarks = xssclean($this->input->post('qa_dept_remarks'));
            $object = xssclean($this->input->post('data'));
            $req_data = json_decode($object);
            $data = $this->RequestCadModel->UpdateCadQAQueueDataa($enqId, $reqId, $qa_dept_remarks, $req_data);
            echo json_encode($data);
        } 
        
    public function dc_print()
        {
            header('Access-Control-Allow-Origin: *');
            $VarId = $this->uri->segment(4);
            $reqId = $this->uri->segment(6);
            $miId = $this->uri->segment(8);
            $dc = $this->uri->segment(10);
             $subid=$this->subb_id;
            
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

            $dc1 = '"'.$dc.'"';
            
            //$dc1 = $dc;
            $output['subcompany_data'] = $this->RequestCadModel->getsubscribercompanydetail($subid);
            
            $output['requestData'] = $this->RequestCadModel->getDCDetails($VarEnqId, $reqEnqId, $miId);
            $output['miDetails'] = $this->RequestCadModel->getCADMIData($miId, $dc1);
            
            $output['ArrCommonHeaderData'] = $this->commonheaderdata($VarEnqId);
            $output['itemDetails'] = $this->RequestCadModel->getMIReceivedData($miId, $dc1);
            $output['data'] = $this->RequestCadModel->getCADDCListt_print($VarEnqId, $miId, $dc);
            
            $company_id = $_SESSION['UI']['companyid']; 
            $output['company_data'] = $this->db->from('kn_company_details')->where('id', $company_id)->get()->result_array();
            
            $this->load->view('request/cad/mi/dc_print',$output);
        }
         public function dc_print_pdf()
        {
            header('Access-Control-Allow-Origin: *');
            $VarId = $this->uri->segment(4);
            $reqId = $this->uri->segment(6);
            $miId = $this->uri->segment(8);
            $dc = $this->uri->segment(10);
             $subid=$this->subb_id;
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

            $dc1 = '"'.$dc.'"';
            
            //$dc1 = $dc;
            $output['subcompany_data'] = $this->RequestCadModel->getsubscribercompanydetail($subid);
            
            $output['requestData'] = $this->RequestCadModel->getDCDetails($VarEnqId, $reqEnqId, $miId);
            $output['miDetails'] = $this->RequestCadModel->getCADMIData($miId, $dc1);
            $output['ArrCommonHeaderData'] = $this->commonheaderdata($VarEnqId);
            $output['itemDetails'] = $this->RequestCadModel->getMIReceivedData($miId, $dc1);
            $output['data'] = $this->RequestCadModel->getCADDCListt_print($VarEnqId, $miId, $dc);
            // echo "<pre>"; print_r($output['data']); exit;
            $company_id = $_SESSION['UI']['companyid']; 
            $output['company_data'] = $this->db->from('kn_company_details')->where('id', $company_id)->get()->result_array();
            //  print_r($output['itemDetails']); exit;
            $this->load->view('request/cad/mi/dc_print_pdf',$output);
            //$this->load->view('request/bom/store/bomstore_dc_pdf',$output);
   
        

        
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
        
        
        public function dc_print1()
    {
        //echo "<pre>"; print_r($_POST); exit;
        header('Access-Control-Allow-Origin: *');
        $enquiry_id = $_POST['enquiry_id'];
        $request_id = $_POST['request_id'];
        $miId = $_POST['miId'];
        $dc = $_POST['dc'];
        $dc1 = '"'.$dc.'"';
        $output['requestData'] = $this->RequestCadModel->getBOMDCData($enquiry_id, $request_id);
        $output['miDetails'] = $this->RequestCadModel->getBOMMIData($miId, $dc1);
        $output['itemDetails'] = $this->RequestCadModel->getMIReceivedData($miId, $dc1);
        $output['ArrCommonHeaderData'] = $this->commonheaderdata($enquiry_id);
        $output['data'] = $this->RequestCadModel->getBOMDCprintData($enquiry_id, $miId, $dc);
        $company_id = $_SESSION['UI']['companyid']; 
        $output['company_data'] = $this->db->from('kn_company_details')->where('id', $company_id)->get()->result_array();
        //echo "<pre>"; print_r($output); exit;
        $data  = $this->load->view('request/bom/store/bomstore_dc_print',$output);
        return $data;
    }
        
        

        // ************* CAD DC END ************* //

    }

?>