<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class MerchantRequestSent extends CI_Controller {

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
        $this->load->model('RequestCadModel');
        $this->load->model('commonmodel');
        $this->load->model('managementmodel');
        $this->load->model('MerchantRequestSentModel');
        $this->load->model(CNFCOMPANY . "mcadrequestmodel");
        $this->load->model(CNFCOMPANY . 'orderentrymodel');
         $ArrObjsubscriber_id = $this->commonmodel->getsubscriberid($this->userid = $ArrUserLoggedInfo['id']);
         $this->subb_id = $ArrObjsubscriber_id->subscriber_id;

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

    public function requestlist() {
        $VarId = $this->uri->segment(3);
        $reqId = $this->uri->segment(5);
        if ($VarId <> '' && is_numeric(base64_decode(urldecode($VarId))))
        {
            $VarEnqId       = base64_decode(urldecode($VarId));
        }

        if ($reqId <> '' && is_numeric(base64_decode(urldecode($reqId))))
        {
            $reqId       = base64_decode(urldecode($reqId));
        }
        $ArrCommonHeaderData = $this->commonheaderdata($VarEnqId);
          $requestData = $this->MerchantRequestSentModel->getRequestData($VarEnqId, $reqId);
        $this->load->view('request/sent_list/cadlist',
                     array('VarEnqId'=>$VarEnqId, 'pending_status' => 1, 'reqId' => $reqId, 'ArrCommonHeaderData' => $ArrCommonHeaderData,'requestData'=> $requestData));
    }

    public function cadrequestlist() {
        $VarId = $this->uri->segment(3);
        $reqId = $this->uri->segment(5);
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

         $subcompany_data = $this->MerchantRequestSentModel->getsubscribercompanydetail($subid);

        $requestData = $this->MerchantRequestSentModel->getRequestData($VarEnqId, $reqId);
        // print_r($requestData);
        // file_put_contents('error_log', print_r($requestData, true));
        // die;
        $this->load->view('request/sent_list/cadlist', 
                     array('VarEnqId'=>$VarEnqId, 'pending_status' => 1, 'reqId' => $reqId, 
                     'ArrCommonHeaderData' => $ArrCommonHeaderData, 'requestData'=> $requestData, 'subcompany_data' => $subcompany_data));
    }

    public function samplerequestlist() {
        $VarId = $this->uri->segment(3);
        $reqId = $this->uri->segment(5);
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
        $subcompany_data = $this->MerchantRequestSentModel->getsubscribercompanydetail($subid);
       $requestData = $this->MerchantRequestSentModel->getRequestData($VarEnqId, $reqId);
        $bomMITableData = $this->MerchantRequestSentModel->bomMITableData($VarEnqId, $reqId);
        $miDetails = $this->MerchantRequestSentModel->miDetails($reqId, $reqId);
        $vendorDetails = $this->MerchantRequestSentModel->getVendorDetailss();

        $this->load->view('request/sent_list/samplelist',
                     array('VarEnqId'=>$VarEnqId, 'pending_status' => 1, 'reqId' => $reqId, 
                     'ArrCommonHeaderData' => $ArrCommonHeaderData, 'requestData'=> $requestData,
                     'bomMITableData'=> $bomMITableData, 'miDetails'=> $miDetails, 'vendorDetails' => $vendorDetails, 'subcompany_data' => $subcompany_data));
    }

    public function bomrequestlist() {
        $VarId = $this->uri->segment(3);
        $reqId = $this->uri->segment(5);
        $subid=$this->subb_id;

        if ($VarId <> '' && is_numeric(base64_decode(urldecode($VarId))))
        {
            $VarEnqId       = base64_decode(urldecode($VarId));
        }

        if ($reqId <> '' && is_numeric(base64_decode(urldecode($reqId))))
        {
            $reqId       = base64_decode(urldecode($reqId));
        }
        $ArrCommonHeaderData = $this->commonheaderdata($VarEnqId);
        $subcompany_data= $this->MerchantRequestSentModel->getsubscribercompanydetail($subid);

        $requestData = $this->MerchantRequestSentModel->getRequestData($VarEnqId, $reqId);
        $requesttype=$requestData[0]['type'];
             //print_r($requesttype);
             if($requesttype == 3){
                 $requesttypedata = '(Art - 1)';
             }else if($requesttype == 4){
                 $requesttypedata = '(Art - 2)';
             }
        $this->load->view('request/sent_list/bomlist',
                     array('VarEnqId'=>$VarEnqId, 'pending_status' => 1, 'reqId' => $reqId, 
                     'ArrCommonHeaderData' => $ArrCommonHeaderData, 'requestData'=> $requestData, 'requesttypedata' => $requesttypedata, 'subcompany_data' => $subcompany_data));
    }

     public function bom2requestlist() {
        $VarId = $this->uri->segment(3);
        $reqId = $this->uri->segment(5);
        if ($VarId <> '' && is_numeric(base64_decode(urldecode($VarId))))
        {
            $VarEnqId       = base64_decode(urldecode($VarId));
        }

        if ($reqId <> '' && is_numeric(base64_decode(urldecode($reqId))))
        {
            $reqId       = base64_decode(urldecode($reqId));
        }
        $subid=$this->subb_id;
        $subcompany_data = $this->MerchantRequestSentModel->getsubscribercompanydetail($subid);
       $ArrCommonHeaderData = $this->commonheaderdata($VarEnqId);
        $requestData = $this->MerchantRequestSentModel->getRequestData($VarEnqId, $reqId);
       // print_r($requestData); exit;
        $this->load->view('request/sent_list/bom2list',
                     array('VarEnqId'=>$VarEnqId, 'pending_status' => 1, 'reqId' => $reqId, 
                     'ArrCommonHeaderData' => $ArrCommonHeaderData, 'requestData'=> $requestData, 'subcompany_data' => $subcompany_data));
    }


    public function fabricrequestlist() {
        $VarId = $this->uri->segment(3);
        $reqId = $this->uri->segment(5);
        if ($VarId <> '' && is_numeric(base64_decode(urldecode($VarId))))
        {
            $VarEnqId       = base64_decode(urldecode($VarId));
        }

        if ($reqId <> '' && is_numeric(base64_decode(urldecode($reqId))))
        {
            $reqId       = base64_decode(urldecode($reqId));
        }
        $ArrCommonHeaderData = $this->commonheaderdata($VarEnqId);
        $requestData = $this->MerchantRequestSentModel->getRequestData($VarEnqId, $reqId);
        $this->load->view('request/sent_list/fabriclist',
                     array('VarEnqId'=>$VarEnqId, 'pending_status' => 1, 'reqId' => $reqId, 
                     'ArrCommonHeaderData' => $ArrCommonHeaderData, 'requestData'=> $requestData));
    }
    
    // ********** MANAGE ALL STARTS HERE *********** /

    // public function index() {
    //     $this->load->view('merchantrequestsent/index');
    // }

    public function index() {
        $data['type'] = 'ALL';
        $data['title'] = 'REQUEST SENT LIST';
        $data['brands'] = $this->getBrandList();
        $this->load->view('merchantrequestsent/common_req_list',$data);
    }

    public function getManageAllList() 
    {
        header('Access-Control-Allow-Origin: *');
        $output = $this->MerchantRequestSentModel->getManageAllListt();
        echo json_encode($output);
    }

    public function getMerchantAllList() 
    {
        header('Access-Control-Allow-Origin: *');
        $output = $this->MerchantRequestSentModel->getMerchantAllListt();
        echo json_encode($output);
    }

    // ********** MANAGE ALL ENDS HERE *********** /

    // ********** CAD REQUEST SENT LIST STARTS HERE ********** /
    public function getrequestDetails() {
        $enqId = xssclean($this->input->post('enquiry_id'));
        $reqId = xssclean($this->input->post('reqId'));
        $data = $this->MerchantRequestSentModel->getrequestDetailss($enqId, $reqId);
        echo json_encode($data);
    }
    
    public function getcadqarequestDetails() {
        $enqId = xssclean($this->input->post('enquiry_id'));
        $reqId = xssclean($this->input->post('reqId'));
        $cadId = xssclean($this->input->post('cadId'));
        $data = $this->MerchantRequestSentModel->getcadqarequestDetailss($enqId, $reqId, $cadId);
        echo json_encode($data);
    }
    
    public function getcadqarequestsentDetails() {
        $enqId = xssclean($this->input->post('enquiry_id'));
        $reqId = xssclean($this->input->post('reqId'));
        $data = $this->MerchantRequestSentModel->getcadqarequestsentDetailss($enqId, $reqId);
        echo json_encode($data);
    }
    
    public function getReferenceDetails() {
        $enqId = xssclean($this->input->post('enquiry_id'));
        $reqId = xssclean($this->input->post('reqId'));
        $data = $this->MerchantRequestSentModel->getReferenceDetailss($enqId, $reqId);
        echo json_encode($data);
    }
    // ********** CAD REQUEST SENT LIST ENDS HERE *********** /

    // ********** CAD STARTS HERE *********** /

    // public function cad() {
    //     $this->load->view('merchantrequestsent/cad');
    // }

     public function getBrandList() 
    {
        header('Access-Control-Allow-Origin: *');
        $output = $this->MerchantRequestSentModel->getBrandListt();
        return $output;
    }

    public function cad() {
        $data['type'] = 'CAD';
        $data['title'] = 'CAD REQUEST SENT LIST';
        $data['brands'] = $this->getBrandList();
        $this->load->view('merchantrequestsent/common_req_list',$data);
    }

    public function getCADList() 
    {
        header('Access-Control-Allow-Origin: *');
        $output = $this->MerchantRequestSentModel->getCADListt();
        echo json_encode($output);
    }

     public function searchCADList() {
        header('Access-Control-Allow-Origin: *');
        $data = $this->input->post();

        //print_r($data);
        $output = $this->MerchantRequestSentModel->getCADListt_search($data);
        echo json_encode($output);
    }


     public function searchSAMPLEList() {
        header('Access-Control-Allow-Origin: *');
        $data = $this->input->post();

        print_r($data);
        $output = $this->MerchantRequestSentModel->getCADListt_search($data);
        echo json_encode($output);
    }

    // ********** CAD ENDS HERE *********** /

    // ********** SAMPLE STARTS HERE *********** /

    // public function sample() {
    //     $this->load->view('merchantrequestsent/sample');
    // }
    public function sample() {
        $data['type'] = 'SAMPLE';
        $data['title'] = 'SAMPLE REQUEST SENT LIST';
         $data['brands'] = $this->getBrandList();
        $this->load->view('merchantrequestsent/common_req_list',$data);
    }

    public function getSampleList() 
    {
        header('Access-Control-Allow-Origin: *');
        $output = $this->MerchantRequestSentModel->getSampleListt();
        echo json_encode($output);
    }

    // ********** SAMPLE ENDS HERE *********** /

    // ********** BOM STARTS HERE *********** /

    // public function bom() {
    //     $this->load->view('merchantrequestsent/bom');
    // }

    public function bom() {
        $data['type'] = 'BOM1';
        $data['title'] = 'BOM (A1) REQUEST SENT LIST';
        $data['brands'] = $this->getBrandList();
        $this->load->view('merchantrequestsent/common_req_list',$data);
    }

    public function getBOMList() 
    {
        header('Access-Control-Allow-Origin: *');
        $output = $this->MerchantRequestSentModel->getBOMListt();
        echo json_encode($output);
    }

    // ********** BOM ENDS HERE *********** /

    // ********** BOM 2 STARTS HERE *********** /

    // public function bom2() {
    //     $this->load->view('merchantrequestsent/bom2');
    // }

    public function bom2() {
        $data['type'] = 'BOM2';
        $data['title'] = 'BOM (A2) REQUEST SENT LIST';
          $data['brands'] = $this->getBrandList();
        $this->load->view('merchantrequestsent/common_req_list',$data);
    }

    public function getBOM2List() 
    {
        header('Access-Control-Allow-Origin: *');
        $output = $this->MerchantRequestSentModel->getBOM2Listt();
        echo json_encode($output);
    }

    // ********** BOM 2 ENDS HERE *********** /

    // ********** EMBELLISHMENT STARTS HERE *********** /

    // public function embellishment() {
    //     $this->load->view('merchantrequestsent/embellishment');
    // }

    public function embellishment() {
        $data['type'] = 'EMBELLISHMENT';
        $data['title'] = 'EMBELLISHMENT REQUEST SENT LIST';
        $this->load->view('merchantrequestsent/common_req_list',$data);
    }

    public function getEmbellishmentList() 
    {
        header('Access-Control-Allow-Origin: *');
        $output = $this->MerchantRequestSentModel->getEmbellishmentListt();
        echo json_encode($output);
    }

    // ********** EMBELLISHMENT ENDS HERE *********** /

    // ********** Fabric STARTS HERE *********** /

    // public function fabric() {
    //     $this->load->view('merchantrequestsent/fabric');
    // }

    public function fabric() {
        $data['type'] = 'FABRIC';
        $data['title'] = 'FABRIC REQUEST SENT LIST';
        $this->load->view('merchantrequestsent/common_req_list',$data);
    }

    public function getFabricList() 
    {
        header('Access-Control-Allow-Origin: *');
        $output = $this->MerchantRequestSentModel->getFabricListt();
        echo json_encode($output);
    }

    // ********** Fabric ENDS HERE *********** /

    // ********** Production STARTS HERE *********** /

    // public function production() {
    //     $this->load->view('merchantrequestsent/production');
    // }

    public function production() {
        $data['type'] = 'PRODUCTION';
        $data['title'] = 'PRODUCTION REQUEST SENT LIST';
        $this->load->view('merchantrequestsent/common_req_list',$data);
    }

    public function getProductionList() 
    {
        header('Access-Control-Allow-Origin: *');
        $output = $this->MerchantRequestSentModel->getProductionListt();
        echo json_encode($output);
    }

    // ********** Production ENDS HERE *********** /

    // ********** Vessel Booking STARTS HERE *********** /

    // public function vessel() {
    //     $this->load->view('merchantrequestsent/vessel_booking');
    // }

    public function vessel() {
        $data['type'] = 'VESSEL';
        $data['title'] = 'VESSEL BOOKING REQUEST SENT LIST';
        $this->load->view('merchantrequestsent/common_req_list',$data);
    }

    public function getVesselList() 
    {
        header('Access-Control-Allow-Origin: *');
        $output = $this->MerchantRequestSentModel->getVesselListt();
        echo json_encode($output);
    }

    // ********** Vessel Booking ENDS HERE *********** /

    // ********** Stationery STARTS HERE *********** /

    // public function stationery() {
    //     $this->load->view('merchantrequestsent/stationery');
    // }

    public function stationery() {
        $data['type'] = 'STATIONERY';
        $data['title'] = 'STATIONERY REQUEST SENT LIST';
        $this->load->view('merchantrequestsent/common_req_list',$data);
    }

    public function getStationeryList() 
    {
        header('Access-Control-Allow-Origin: *');
        $output = $this->MerchantRequestSentModel->getStationeryListt();
        echo json_encode($output);
    }

    // ********** Stationery ENDS HERE *********** /

    // GET CAD REQUEST IMAGES

    public function getcadrequestImages()
    {
        header('Access-Control-Allow-Origin: *');
        $data = $this->input->post();
        $output = $this->MerchantRequestSentModel->getcadrequestImagess($data);
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
        $output = $this->MerchantRequestSentModel->getbomrequestImagess($data);
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
    
    public function deleteImageDetails()
    {
        $data = $this->input->post();
        $result = $this->MerchantRequestSentModel->deleteImageDetailss($data);
        echo json_encode($result);
    }

}

?>