<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
error_reporting(0);
class Merchant extends CI_Controller {
    public $companyid;
    public $userid;
    public $mysqldatetime;
    public function __construct() {
        parent::__construct();
        //error_reporting(E_ALL);
        $this->load->helper('xssclean');
        fnIfCheckUserLoggedIn();
        $this->load->model('commonmodel');
        $this->load->model('merchantmodel');
        $this->load->model(CNFCOMPANY . 'menquirymodel');
        $this->load->model(CNFCOMPANY . 'workinprogressmodel');
        $this->load->model(CNFCOMPANY . "mcadrequestmodel");
		$this->load->model(CNFCOMPANY . "orderentrymodel");
        $ArrUserLoggedInfo = fnGetUserLoggedInfo('1');
        $this->companyid = $ArrUserLoggedInfo['companyid'];
        $this->subscriberid = $ArrUserLoggedInfo['subscriber_id'];
       
          
        

        $this->userid = $ArrUserLoggedInfo['id'];
        $this->mysqldatetime = date('Y-m-d H:i:s');
        //Note: table name of KN_MASTER_BRANDS renamed to KN_MASTER_BRANDBUYER 
    }

    public function index() {
        $this->load->view('merchant/merchantdashboard');
    }

    public function orderEnquiryList() {
        $data['brands'] = $this->getBrandList();
        $data['ArrEnqType'] = ARRENQUIRYTYPE;
        $data['checkDraftorNot']= $this->merchantmodel->getdraftdata();
        $this->load->view('merchant/orderEnquiryList', $data);
    }
    
    public function iorenquirylist() {
        $data['brands'] = $this->getBrandList();
        $data['ArrEnqType'] = ARRENQUIRYTYPE;
        $data['checkDraftorNot']= $this->merchantmodel->getdraftdata();
        $this->load->view('merchant/enquiry_iorlist', $data);
    }
    public function getEnquiryIORList() 
    {
        header('Access-Control-Allow-Origin: *');
        $output = $this->merchantmodel->getEnquiryIORListt();
        echo json_encode($output);
    }
    public function isrenquirylist() {
        $data['brands'] = $this->getBrandList();
        $data['ArrEnqType'] = ARRENQUIRYTYPE;
        $data['checkDraftorNot']= $this->merchantmodel->getdraftdata();
        $this->load->view('merchant/enquiry_isrlist', $data);
    }
    public function getEnquiryISRList() 
    {
        header('Access-Control-Allow-Origin: *');
        $output = $this->merchantmodel->getEnquiryISRListt();
        echo json_encode($output);
    }
    function changemStatus() {
        $VarActDeactOption = xssclean($this->input->post('actdeact'));
        $VarCid = xssclean($this->input->post('cid'));
        if ($VarActDeactOption <> '' && $VarCid <> '') {
            if ($this->menquirymodel->fnChangeStatus($VarCid, $VarActDeactOption)) {
                echo json_encode(array('errcode' => 1));
                die;
            } else                 echo json_encode(array('errcode' => -1));
        }
    }

    // function fnCheckPin() {
    //     $VarPw = xssclean($this->input->post('i'));
    //     $ArrUserLoggedId = fnGetUserLoggedInfo(1);
    //     $this->companyid = $ArrUserLoggedId['companyid'];
    //     if ($this->commonmodel->fnValidatePin($ArrUserLoggedId['id'], $VarPw)) {
    //         $VarEnquiryId = xssclean($this->input->post('enqid'));
    //         $VarIsrOrIor = xssclean($this->input->post('ty'));
    //         $VarApprStatus = xssclean($this->input->post('s'));
    //         $VarComments = addslashes(xssclean($this->input->post('c')));
    //         $ArrRes = $this->menquirymodel->fnAuthorize($this->companyid, $VarEnquiryId, $VarApprStatus, $VarComments, $VarIsrOrIor);
    //         if ($ArrRes <> '') {
    //             echo json_encode(array('errcode' => '1', 'assignedno' => $ArrRes, 'dupdated' => date('d-m-Y / H:i:s')));
    //         } else {
    //             echo json_encode(array('errcode' => '1', 'assignedno' => '0', 'dupdated' => date('d-m-Y / H:i:s')));
    //         }
    //     } else {
    //         echo json_encode(array('errcode' => '-1', 'msg' => 'Invalid PIN'));
    //     }
    // }

    function fnCheckPin() {
        $VarPw = xssclean($this->input->post('i'));
        $ArrUserLoggedId = fnGetUserLoggedInfo(1);
        $this->companyid = $ArrUserLoggedId['companyid'];
        if ($this->commonmodel->fnValidatePin($ArrUserLoggedId['id'], $VarPw)) {
            echo json_encode(array('errcode' => '1', 'dupdated' => date('d-m-Y / H:i:s')));
        } else {
            echo json_encode(array('errcode' => '-1', 'msg' => 'Invalid PIN'));
        }
    }

    function enquiryDetail() {
        /*
         * For getting folder name of uploads in for order enquiry we need to send user type
         * */
        $ArrUserType = unserialize(ARRUSERTYPE);
        $VarId = $this->uri->segment(3);
        $VarEnqId = '';
        $ArrEnquiryInfo = array();
        if ($VarId <> '' && is_numeric(base64_decode(urldecode($VarId)))) {
            $VarEnqId = base64_decode(urldecode($VarId));
            $ArrEnquiryInfo = $this->menquirymodel->fnGetInfo('', $VarEnqId, $this->companyid);

        }
        $ArrUserInfo = fnGetUserLoggedInfo(1);
        $VarUserType = $ArrUserType[$ArrUserInfo['usertype']];
        $ArrOrderStatus = unserialize(ORDERENQUIRYSTATUS);
        $ArrCountries = unserialize(ARRCOUNTRYLIST);
        $ArrEnquiryType = ARRENQUIRYTYPE;
        $ArrModeType = $this->commonmodel->fnGetAllTableInfo(KN_MASTER_MODEOFENQUIRY, 'id,modeofenquiry as name', array('status' => '1', 'companyid' => $this->companyid));
        $ArrCurrency = unserialize(ARRCURRENCYLIST);
        $ArrBrand = $this->commonmodel->fnGetAllTableInfo(KN_MASTER_BRANDS  . ' AS br', 'br.id,br.brandname', array('br.status' => '1', 'br.companyid' => $this->companyid),3);
        $ArrBuyer = $this->commonmodel->fnGetAllTableInfo(KN_MASTER_BUYER . ' AS byr', 'byr.id,byr.buyername', array('byr.status' =>'1', 'byr.companyid' => $this->companyid),3);
        echo '<pre>';
        print_r($ArrEnquiryInfo);
        echo '</pre>';
        exit;
        $this->load->view("merchant/merchantenquirydetailview", array('ArrEnquiryType' => $ArrEnquiryType, 'ArrCountries' => $ArrCountries,
            'ArrModeType' => $ArrModeType, 'ArrCurrency' => $ArrCurrency,'ArrBrand' => $ArrBrand,'ArrBuyer'=>$ArrBuyer, 'VarEnqId' => $VarEnqId,
            'ArrEnquiryInfo' => $ArrEnquiryInfo, 'ArrOrderStatus' => $ArrOrderStatus,'UserType'=>$VarUserType
        ));
    }

    function addenquiry() {
        $ArrCountriesList = unserialize(ARRCOUNTRYLIST);
        $ArrCountries = fnConvertKeyPair($ArrCountriesList);
        $ArrEnquiryType = ARRENQUIRYTYPE;
        $ArrModeType = $this->commonmodel->fnGetAllTableInfo(KN_MASTER_MODEOFENQUIRY, 'id,modeofenquiry as name', array('status' => '1', 'companyid' => $this->companyid));
        $ArrCurrency = unserialize(ARRCURRENCYLIST);
        foreach ($ArrCurrency as $key => $item) {
            $ArrCurr[] = array('id' => $key, 'name' => $item);
        }
        $ArrMerchantcode = $this->commonmodel->getMerchantData($this->companyid, 1);
        //SELECT * FROM kn_master_garment_part_desc;

        $GarmentParts = [];
        $ArrGarmentParts = $this->commonmodel->fnGetAllTableInfo(KN_MASTER_GARMENT_PART_DESC . ' AS gr', 'gr.id,gr.gpdname', array('gr.status' => '1', 'gr.companyid' => $this->companyid));
        if (!empty($ArrGarmentParts)) {
            foreach ($ArrGarmentParts as $item) {
                $GarmentParts[] = $item['gpdname'];
            }
        }
        $ArrBrand = $this->commonmodel->fnGetAllTableInfo(KN_MASTER_BRANDS  . ' AS br', 'br.id,br.brandname', array('br.status' => '1', 'br.companyid' => $this->companyid),3);
        $ArrBuyer = $this->commonmodel->fnGetAllTableInfo(KN_MASTER_BUYER . ' AS byr', 'byr.id,byr.buyername', array('byr.status' =>'1', 'byr.companyid' => $this->companyid),3);
        $VarEnquiryId = base64_decode(urldecode($this->uri->segment(3)));
        $ArrSizeChartData = $this->orderentrymodel->getSizeChart($VarEnquiryId, $this->companyid);
        $jsonFirstTbl = $this->orderentrymodel->getFirstTable($VarEnquiryId, $this->companyid);
        $checkDraftorNot = $this->merchantmodel->checkDraftorNot($VarEnquiryId);
        $last = $this->uri->segment_array();
        $lastURI = end($last);

        $this->load->view("merchant/addeditenquiry", array('ArrEnqType' => $ArrEnquiryType, 'ArrCountries' => $ArrCountries,
            'modetype' => $ArrModeType, 'ArrCurrency' => $ArrCurr, 'ArrMerchantcode' => $ArrMerchantcode,
            'ArrSizeChartData' => @$ArrSizeChartData,
            'GarmentParts' => @$GarmentParts,
            'jsonFirstTbl' => empty($jsonFirstTbl->jsondatagrid) ? 0 : $jsonFirstTbl->jsondatagrid,
            'checkDraftorNot'=> $checkDraftorNot, 
            'ArrBrand' => $ArrBrand, 'ArrBuyer' => $ArrBuyer,'VarEnquiryId' => '', 'lastURI' => $lastURI ));
    }

    public function getBuyerInfoByBrandId()
    {
        $VarFrom = xssclean($this->input->post('rFrom'));
        if ($VarFrom == 1) {
            $VarBrandId = xssclean($this->input->post('id'));
            $Res = $this->commonmodel->getBuyerInfoFromBrand($VarBrandId);
            if (!empty($Res->id)) {
                echo json_encode(array('buyername' => $Res->buyername,'country' => $Res->country));
            } else {
                echo json_encode(array('buyername' => '','country' => ''));
            }
        }
    }

    function updateenquiry() {
        $ArrUpdateData = array();
        $ArrEnquiryStatus = unserialize(ORDERENQUIRYSTATUS);
        $ArrUpdateData['orderenqrefno'] = xssclean($this->input->post('orderenqrefno'));
        $ArrUpdateData['enquirytype'] = xssclean($this->input->post('et'));
        $VarEnquiryId = xssclean($this->input->post('enquiryid'));
        $ArrUpdateData['orderstatus'] = xssclean($this->input->post('os'));
        $ArrUpdateData['draft_status'] = xssclean($this->input->post('draftstatus'));
          //$ArrUpdateData['draft_status'] = "2";
        $ArrUpdateData['modeofenquiry'] = xssclean($this->input->post('me'));
        $ArrUpdateData['brandId'] = xssclean($this->input->post('brn'));
        $ArrUpdateData['buyerId'] = xssclean($this->input->post('byr'));
        $ArrUpdateData['countryid'] = xssclean($this->input->post('conty'));
        $ArrUpdateData['stylenamerefno'] = xssclean($this->input->post('styref'));
        $ArrUpdateData['styledesc'] = xssclean($this->input->post('sd'));
        $ArrUpdateData['enquirydate'] = dateHelp(xssclean($this->input->post('enqdt')));
        $ArrUpdateData['quotedprice'] = xssclean($this->input->post('qp'));
        $ArrUpdateData['buyerprice'] = xssclean($this->input->post('bp'));
        $ArrUpdateData['confirmprice'] = xssclean($this->input->post('cp'));
        $ArrUpdateData['currency'] = xssclean($this->input->post('crncy'));
        $ArrUpdateData['exporderqty'] = xssclean($this->input->post('proq'));
        $ArrUpdateData['pcsorset'] = xssclean($this->input->post('ps'));
        $ArrUpdateData['merchantid'] = $this->userid;
        $ArrUpdateData['subscriberid'] = $this->subscriberid;
        $ArrUpdateData['reqforisrior'] = xssclean($this->input->post('rt'));       

        $ArrUpdateData['isrrefany'] = xssclean($this->input->post('israny'));       

        $ArrUpdateData['companyid'] = $this->companyid;
        if(!empty($this->input->post('mt')))
        {
           $ArrUpdateData['merchantnote'] = xssclean($this->input->post('mt'));
        }
        $ArrUpdateData['pricequotedfor'] = xssclean($this->input->post('pricequotedfor'));
        $ArrUpdateData['order_status_value'] = $ArrEnquiryStatus[xssclean($this->input->post('os'))];
        $ArrUpdateData['dateupdated'] = date('Y-m-d H:i:s');
        
        if(!empty($VarEnquiryId))
        {
            $ArrUpdateData['status'] = '1';
        }
        
        $ArrUpdateData['totalcombo'] = xssclean($this->input->post('frmcombo'));
        $ArrUpdateData['totalcomponents'] = xssclean($this->input->post('frmComponents'));
        $ArrUpdateData['shipmentdate'] = xssclean($this->input->post('frmShipmentDate'));
        
        if($ArrUpdateData['shipmentdate'] <> '')
        {
           $ArrUpdateData['shipmentdate'] =  date('Y-m-d', strtotime($ArrUpdateData['shipmentdate']));
        }
        
        if ($ArrUpdateData['enquirytype'] <> '') {
            if ($VarEnquiryId == '' || $VarEnquiryId == 0) {
                $ArrUpdateData['datecreated'] = date('Y-m-d H:i:s');
            }
            $ArrResult = $this->menquirymodel->saveEnquiryInfo($ArrUpdateData, $VarEnquiryId);
        } else {
            $ArrResult['errcode'] = '-1';
            $ArrResult['msg'] = 'Invalid Input!';
        }
        echo json_encode($ArrResult);
    }

    function changepassword() {
    }

    function addcadrequest() {
        $VarFrom = xssclean($this->input->post('rFrom'));
        $VarOrderId = xssclean($this->input->post('oId'));
        if($VarFrom == 1) {
            $ArrPoNumber = $this->orderentrymodel->getAllPoNumber($VarOrderId, $this->companyid);
            echo json_encode(array('poNo'=>$ArrPoNumber));
        }
        else {
            $VarOrderId = base64_decode(urldecode($this->uri->segment(3)));
            $ArrCombo = $ArrComponent = $ArrSizeSpecCode = array();
            $jsonFourth = '';
            $ArrOrderEntryRes = $this->orderentrymodel->getFromNewFourthTbl($VarOrderId, $this->companyid);
            if (!empty($ArrOrderEntryRes)) {
                if (!empty($ArrOrderEntryRes->jsondatagrid)) {
                    $jsonFourth = $ArrOrderEntryRes->jsondatagrid;
                    $ArrOrderEntry = json_decode($ArrOrderEntryRes->jsondatagrid, true);
                    foreach ($ArrOrderEntry as $item) {
                        $ArrCombo[] = $item[0];
                        $ArrComponent[] = $item[1];
                        $ArrSizeSpecCode[] = $item[5];
                    }
                }
            } else {
                die('Error No Combo, Component, Size Spec Code');
            }
            $ArrRequirement = ARRCADREQUIREMENT;
            /*TODO removed this and kept in commonfunction.js as static array*/
            //$ArrObjPurpose = $this->mcadrequestmodel->getPurpose();
            $PrevCadRefNo = $this->commonmodel->getPrevCadRefNo($this->companyid, $VarOrderId);
            $ArrPrevCadRefNo = array_filter($PrevCadRefNo);
            if ($VarOrderId >= 1) {
                $ArrOrderDatas = $this->orderentrymodel->getOrderDataByWip($VarOrderId, $this->companyid);
                if (empty($ArrOrderDatas)) {
                    die('Order Entry not completed');
                } else {
                    $ArrOrderDataSizeChart = $this->orderentrymodel->getSizeChart($VarOrderId, $this->companyid);
                    $ArrOrderEnqData = $this->orderentrymodel->fnGetOrderEnquiryInfo($VarOrderId, $this->companyid, '');
                    $ArrCategory = unserialize(ARRCADCATEGORY);
                    if(!empty($ArrOrderDataSizeChart->sizechartvalue)) {
                        $ArrFinalSizes = explode(',',$ArrOrderDataSizeChart->sizechartvalue);
                    }
                    $ArrFinalSizes[] = "All";
                    $VarCommonOrderEntryInfo = $this->commonmodel->commonBasicInfoOrderEntry($VarOrderId, $this->companyid);
                    $ArrData = array(
                        'ArrCompanyInfo' => $VarCommonOrderEntryInfo['ArrCompanyInfo'],
                        'ArrMerchant' => $VarCommonOrderEntryInfo['ArrMerchant'],
                        'ArrTeamInfo' => empty($VarCommonOrderEntryInfo['ArrTeamInfo']) ? '' : $VarCommonOrderEntryInfo['ArrTeamInfo'],
                        'brandName' => $VarCommonOrderEntryInfo['brandName'],
                        'buyerName' => $VarCommonOrderEntryInfo['buyerName'],
                        'ArrOrderCommonData' => $VarCommonOrderEntryInfo['ArrOrderCommonData'],
                        'VarId' => '',
                        'combo' => json_encode($ArrCombo),
                        'component' => json_encode($ArrComponent),
                        'spc' => json_encode($ArrSizeSpecCode),
                        'ArrOrderEnqData' => $ArrOrderEnqData[0],
                        'ArrRequirement' => json_encode(array_values($ArrRequirement)),
                        'ArrCategory' => json_encode($ArrCategory),
                        'ArrPrevCadRefNo' => json_encode(array_values($ArrPrevCadRefNo)),
                        'ArrReqSize' => json_encode($ArrFinalSizes),
                        'orderid' => $VarOrderId,
                        'jsonDataGrid' => 0,
                        'queueno' => 0,
                        'jsonFourth' => $jsonFourth
                    );
                    $this->load->view('merchant/addeditcadrequest', $ArrData);
                }
            }
        }
    }

    public function getCadReqGrid() {
    }

    public function updateCadRequestInfo() {
        $VarRequestTypeDept = "CAD";
        $this->load->model(CNFCOMPANY . 'mcadrequestmodel');
        $ArrStatus = array();
        $VarRequestType = xssclean($this->input->post('reqtype'));
        $VarCutoff = date('Y-m-d H:i:s', strtotime(xssclean($this->input->post('cutoff'))));
        $VarMerchantId = $this->userid;
        $VarMerchantNote = xssclean($this->input->post('mnote'));
        $VarId = xssclean($this->input->post('id'));
        $VarIsrIorCode = xssclean($this->input->post('isriorcode'));
        $VarJxlData = xssclean($this->input->post('jxldata'));
        $jsonAttachmentDetailsJxl = xssclean($this->input->post('AttachmentDetailsJxl'));
        if ($VarId == '' || $VarId == 0) {
            $Varmgmtcurrentstatus = 1;
            $VarCaddeptcurrentstatus = 1;
        } else {
            $ArrCadRequestData = $this->mcadrequestmodel->getCadRequestData($VarId, $this->companyid);
            $Varmgmtcurrentstatus = @$ArrCadRequestData->mgmtcurrentstatus;
            $VarCaddeptcurrentstatus = @$ArrCadRequestData->deptcurrentstatus;
        }
        $VarOrderId = xssclean($this->input->post('oid'));
        if (xssclean($this->input->post('cs') == '3')) $Varmgmtcurrentstatus = '4';
        if ($VarCutoff <> '') {
            $ArrUpdateData = array('jsonAttachmentDetails'=>$jsonAttachmentDetailsJxl);
            $ArrAllRequest = array('id' => $VarId, 'companyid' => $this->companyid, 'orderid' => $VarOrderId,
                'merchantid' => $VarMerchantId, 'jsondatagrid' => $VarJxlData,'cutoffdatetime' => $VarCutoff,
                'mgmtcurrentstatus' => $Varmgmtcurrentstatus, 'deptcurrentstatus' => $VarCaddeptcurrentstatus,
                'merchantnote' => $VarMerchantNote, 'requesttype' => $VarRequestType,'isriorcode'=>$VarIsrIorCode,
                'status' => '1', 'current_status'=>'AUTHORIZATION PENDING','request_type_dept'=>$VarRequestTypeDept,
                'datecreated' => $this->mysqldatetime,'dateupdated' => $this->mysqldatetime);

            $ArrStatus = $this->mcadrequestmodel->saveCadRequestInfo($ArrAllRequest, $ArrUpdateData);
        } else {
            $ArrStatus['errcode'] = '-1';
            $ArrStatus['msg'] = 'Invalid Input!';
        }
        echo json_encode($ArrStatus);
    }

    public function editcadrequest() {
        $VarHashedCadRequestId = $this->uri->segment(3);
        $VarCadRequestId = base64_decode(urldecode($this->uri->segment(3)));
        $ArrFinalSizes = [];
        $ArrCadRequestData = $this->mcadrequestmodel->getCadRequestData($VarCadRequestId, $this->companyid);
        $jsonAttachmentJxl = $ArrCadRequestData->jsonAttachmentDetails;
        $jsonDataGrid = $ArrCadRequestData->jsondatagrid;
        $VarOrderId = $ArrCadRequestData->orderid;
        $PrevCadRefNo = $this->commonmodel->getPrevCadRefNo($this->companyid, $VarOrderId);
        $ArrPrevCadRefNo = array_filter($PrevCadRefNo);
        $ArrOrderEntryRes = $this->orderentrymodel->getFromNewFourthTbl($VarOrderId,$this->companyid);

        if (!empty($ArrOrderEntryRes)) {
            if(!empty($ArrOrderEntryRes->jsondatagrid)) {
                $ArrOrderEntry = json_decode($ArrOrderEntryRes->jsondatagrid,true);
                foreach ($ArrOrderEntry as $item) {
                    $ArrCombo[] = $item[0];
                    $ArrComponent[] = $item[1];
                    $ArrSizespeccode[] = $item[5];
                }
            }
        }
        else {
            die('Error No Combo, Component, Size Spec Code');
        }
        $ArrPoNumber = $this->orderentrymodel->getAllPoNumber($VarOrderId, $this->companyid);
        $ArrRequirement = ARRCADREQUIREMENT;
        /*TODO removed this and kept in commonfunction.js as static array*/
        //$ArrObjPurpose = $this->mcadrequestmodel->getPurpose();
        $ArrCompanyRes = $this->companymodel->fnGetCompanyInfo($this->companyid);
        if ($VarCadRequestId >= 1) {
            $ArrOrderDatas = $this->orderentrymodel->getOrderDataByWip($VarOrderId, $this->companyid);
            $ArrOrderDataSizeChart = $this->orderentrymodel->getSizeChart($VarOrderId, $this->companyid);
            if(!empty($ArrOrderDataSizeChart->sizechartvalue)) {
                $ArrFinalSizes = explode(',',$ArrOrderDataSizeChart->sizechartvalue);
            }
            $ArrFinalSizes[] = "All";
            $ArrOrderEnqData = $this->orderentrymodel->fnGetOrderEnquiryInfo($VarOrderId, $this->companyid, '');
            $ArrCategory = unserialize(ARRCADCATEGORY);
            $VarMgmtId = $ArrCadRequestData->mgmtid;
            $AuthMgmtInfo = $this->commonmodel->getUserInfo($VarMgmtId);
            $VarCommonOrderEntryInfo = $this->commonmodel->commonBasicInfoOrderEntry($VarOrderId, $this->companyid);
            $VarUserInfo = fnGetUserLoggedInfo(1);
            $ArrUserType = unserialize(ARRUSERTYPE);
            $VarUserType = $ArrUserType[$VarUserInfo['usertype']];
            $ArrData = array(
                'ArrCompanyInfo' => $VarCommonOrderEntryInfo['ArrCompanyInfo'],
                'ArrMerchant' => $VarCommonOrderEntryInfo['ArrMerchant'],
                'ArrTeamInfo' => $VarCommonOrderEntryInfo['ArrTeamInfo'],
                'brandName' => $VarCommonOrderEntryInfo['brandName'],
                'buyerName' => $VarCommonOrderEntryInfo['buyerName'],
                'ArrOrderCommonData' => $VarCommonOrderEntryInfo['ArrOrderCommonData'],
                'ArrBasicInfo' => $ArrCadRequestData,
                'VarNew' => 0,
                'combo' => json_encode($ArrCombo),
                'component' => json_encode($ArrComponent),
                'spc' => json_encode($ArrSizespeccode),
                'ArrOrderDatas' => @$ArrOrderDatas[0],
                'ArrOrderEnqData' => $ArrOrderEnqData[0],
                'ArrPoNumber' => json_encode($ArrPoNumber),
                'ArrRequirement' => json_encode(array_values($ArrRequirement)),
                'ArrCategory' => json_encode($ArrCategory),
                'ArrPrevCadRefNo' => json_encode(array_values($ArrPrevCadRefNo)),
                'ArrReqSize' => json_encode($ArrFinalSizes),
                'VarId' => $VarCadRequestId,
                'orderid' => $VarOrderId,
                'queueno' => $ArrCadRequestData->queueno,
                'HashedCadRequestId' => $VarHashedCadRequestId,
                'jsonDataGrid' => $jsonDataGrid,
                'UserType' => $VarUserType,
                'AuthMgmtInfo'=>empty($AuthMgmtInfo[0]['contactname']) ? '-' : $AuthMgmtInfo[0]['contactname'],
                'jsonAttachmentJxl'=>$jsonAttachmentJxl
            );
        } else {
            $ArrData['VarNew'] = 1;
        }
        $this->load->view('merchant/addeditcadrequest', $ArrData);
    }



    public function manageAllRequest() {
        $rFrom = xssclean($this->input->post('rFrom'));
        if($rFrom == 1) {
            $ArrList = $this->merchantmodel->getAllReqListDataTables();
            $data = array();
            $ArrStatus = unserialize(ARRSTATUS);
            $ArrBomRequirement = unserialize(ARRBOMREQUIREMENT);
            $VarRequirement = '';
            foreach ($ArrList as $Obj) {
                $row = array();
                if ($Obj->request_type_dept == "CAD") {
                    $reqSentListDetailUrl = 'merchant/editcadrequest';
                    $jsonData = json_decode($Obj->jsondatagrid);
                    $VarRequirement = empty($jsonData[0][4]) ? '-' : $jsonData[0][4];
                }
                elseif ($Obj->request_type_dept == "SAMPLE") {
                    $reqSentListDetailUrl = 'msamplerequest/sample_request_details';
                    $jsonData = json_decode($Obj->jsondatagrid);
                    $VarRequirement = empty($jsonData[0][5]) ? '-' : $jsonData[0][5];
                }
                elseif ($Obj->request_type_dept == "BOM") {
                    $reqSentListDetailUrl = 'mpurchase/bompurchaseRequestDetails';
                    $VarRequirement = $ArrBomRequirement[$Obj->requirementforbom];
                }
                else
                    $reqSentListDetailUrl = '';

                $row[] = '<input type="checkbox" class="allcbox" id="'.$Obj->id.'">';
                $row[] = '<a href="'.base_url($reqSentListDetailUrl.'/'.urlencode(base64_encode($Obj->id))).'">'.$Obj->isriorcode.'</a>';
                $row[] = $Obj->brandname;
                $row[] = $Obj->request_type_dept;
                $row[] = $VarRequirement;
                $row[] = $Obj->requesttype;
                $row[] = $Obj->formatDateCreated;
                $row[] = $Obj->formattedCutOffDt;
                $row[] = $Obj->approvaltype;
                $row[] = $Obj->mgmt;
                $row[] = $Obj->current_status;
                $row[] = $Obj->formattedDateUpdated;
                $row[] = $ArrStatus[$Obj->status];
                $row[] = '';
                $row[] = '';
                $row[] = '';
                $data[] = $row;
            }
            $output = array(
                "draw" => $_POST['draw'],
                "recordsTotal" => $this->merchantmodel->allReqListCountAll(),
                "recordsFiltered" => $this->merchantmodel->allReqListCountFiltered(),
                "data" => $data,
            );
            echo json_encode($output);
        }
        else {
            $this->load->view('merchant/manageallrequest', array());
        }
    }

    public function queueList() {
        $rFrom = xssclean($this->input->post('rFrom'));
        if($rFrom == 1) {
            $ArrList = $this->merchantmodel->queueListDataTables();
            $data = array();
            $ArrStatus = unserialize(ARRSTATUS);
            $ArrBomRequirement = unserialize(ARRBOMREQUIREMENT);
            foreach ($ArrList as $Obj) {
                $row = array();
                $row[] = '<input type="checkbox" class="allcbox" id="' . $Obj->id . '">';
                if ($Obj->request_type_dept == "CAD") {
                    $QueueListDetailPageUrl = base_url(CNFCOMPANY.'mcaduser/cadqueuelistdetail') . '/' . urlencode(base64_encode($Obj->id));
                    $row[] = '<a href="' . $QueueListDetailPageUrl . '">' . $Obj->isriorcode . '</a>';
                    $row[] = $Obj->brandname;
                    $row[] = $Obj->queueno;
                    $row[] = $Obj->request_type_dept;
                    if (empty($Obj->jsondatagrid)) {
                        $row[] = '-';
                    } else {
                        $jsonGrid = json_decode($Obj->jsondatagrid, true);
                        $row[] = $jsonGrid[0][4];
                    }
                } elseif ($Obj->request_type_dept == "SAMPLE") {
                    $QueueListDetailPageUrl = base_url(CNFCOMPANY.'msamplinguser/queuelistdetail') . '/' . urlencode(base64_encode($Obj->id));
                    $row[] = '<a href="' . $QueueListDetailPageUrl . '">' . $Obj->isriorcode . '</a>';
                    $row[] = $Obj->brandname;
                    $row[] = $Obj->queueno;
                    $row[] = $Obj->request_type_dept;
                    if (empty($Obj->jsondatagrid)) {
                        $row[] = '-';
                    } else {
                        $jsonGrid = json_decode($Obj->jsondatagrid, true);
                        $row[] = $jsonGrid[0][5];
                    }
                } elseif ($Obj->request_type_dept == "BOM") {
                    $QueueListDetailPageUrl = base_url('bompurchaseindent/preprarebompind') . '/' . urlencode(base64_encode($Obj->id));
                    $row[] = '<a href="' . $QueueListDetailPageUrl . '">' . $Obj->isriorcode . '</a>';
                    $row[] = $Obj->brandname;
                    $row[] = $Obj->queueno;
                    $row[] = $Obj->request_type_dept;
                    $row[] = $ArrBomRequirement[$Obj->requirementforbom];
                } else {
                }
                $row[] = $Obj->formatDateCreated;
                $row[] = $Obj->formattedCutOffDt;
                $row[] = $Obj->approvaltype;
                $row[] = $Obj->mgmt;
                $row[] = $Obj->current_status;
                $row[] = $Obj->formatDateUpdated;
                $row[] = $ArrStatus[$Obj->status];
                $data[] = $row;
            }
            $output = array(
                "draw" => $_POST['draw'],
                "recordsTotal" => $this->merchantmodel->queueListCountAll(),
                "recordsFiltered" => $this->merchantmodel->queueListCountFiltered(),
                "data" => $data,
            );
            echo json_encode($output);
        }
        else {
            $this->load->view('merchant/queueList', array());
        }
    }

/*    public function manageWip() {
        $VarFrom = xssclean($this->input->post('rFrom'));
        if($VarFrom == 1) {
            $ArrList = $this->merchantmodel->getWipDataTables();
            $data = array();
            $ArrActiveInStatus = unserialize(ARRSTATUS);
            $ArrPoNoEnqRefNo = $ArrPoQtySampleQty = $ArrPcs_set = $ArrShipmentSubDate = [];
            $ArrShipmentDateJoined = $ArrPcsSetJoined = $ArrPoNoPoQtyShipmentDate = [];
            foreach ($ArrList as $key => $item) {
                $ArrPoNoEnqRefNo[$item->delId]       = $item->poNoEnqRefNo;
                $ArrPoQtySampleQty[$item->delId]     = $item->poQtySampleQty;
                $ArrPcs_set[$item->delId]            = $item->pcs_set;
                $ArrShipmentSubDate[$item->delId]    = $item->formattedShipmentSubDate;
            }
            foreach ($ArrList as $key => $Obj) {
                if (@count($ArrPoNoEnqRefNo) !== @count(array_unique($ArrPoNoEnqRefNo))) {
                    $ArrPo       = get_keys_for_duplicate_values($ArrPoNoEnqRefNo);
                    foreach ($ArrPo as $poNo => $poNoItemId) {
                        $ArrPoqToSum = 0;
                        foreach ($poNoItemId as $ids) {
                            if(is_numeric($ArrPoQtySampleQty[$ids])) {
                                $ArrPoqToSum += $ArrPoQtySampleQty[$ids];
                            }
                            else {
                                $ArrPoqToSum += 0;
                            }
                            $ArrShipmentDateJoined = $ArrShipmentSubDate[$ids];
                            $ArrPcsSetJoined = $ArrPcs_set[$ids];
                        }
                        $ArrPoNoPoQtyShipmentDate[] = array('ids' => $ids, 'sumPoq' => $ArrPoqToSum,'pcsSet'=>$ArrPcsSetJoined,
                            'poNo' => $poNo,'shipmentDate'=>$ArrShipmentDateJoined);
                    }
                }
                else {

                }
                $ArrFinalRes = array();
                $ArrFinalRes['id'] = $Obj->id;
                $ArrFinalRes['delId'] = $Obj->delId;
                $ArrFinalRes['isriorcode'] = $Obj->isriorcode;
                $ArrFinalRes['date'] = $Obj->formattedDateCreated;
                $ArrFinalRes['bb'] = $Obj->brandname.' / '.$Obj->buyername;
                $ArrFinalRes['styleRefNoName'] = $Obj->stylenamerefno;
                $ArrFinalRes['orderEnqRefNo'] = $Obj->orderenqrefno;
                $ArrFinalRes['show'] = $Obj->status;
                $ArrFinalRes['poNo'][] = $ArrPoNoEnqRefNo;
                $ArrFinalRes['poQtySampleQty'][] = $ArrPoQtySampleQty;
                $ArrFinalRes['pcs_set'][] = $ArrPcs_set;
                $ArrFinalRes['shipmentSubDate'][] = $ArrShipmentSubDate;
                $ArrFinalRes['dateUpdated'] = $Obj->formattedDateUpdated;
                $ArrFinalRes['actInActStatus'] = $ArrActiveInStatus[$Obj->status];
                $ArrFinalRes['allDataJoined'] = $ArrPoNoPoQtyShipmentDate;
                $data[] = $ArrFinalRes;
            }
            $output = array(
                "draw" => $_POST['draw'],
                "recordsTotal" => $this->merchantmodel->wipCountAll(),
                "recordsFiltered" => $this->merchantmodel->wipCountFiltered(),
                "data" => $data,
            );
            echo json_encode($output);
        }
        else $this->load->view('merchant/workinprocess', array());
    }*/

    public function manageWip() {
        $data['brands'] = $this->getBrandList();
        $this->load->view('merchant/workinprocess', $data);
    }

    public function manageIOR() {
        $data['brands'] = $this->getBrandList();
        $this->load->view('merchant/iorlist', $data);
    }

    public function manageISR() {
        $data['brands'] = $this->getBrandList();
        $this->load->view('merchant/isrlist', $data);
    }
    
    public function enqFileUpload() {
        $ArrExtensions = FILE_EXTENSIONS;
        $VarFdrName = xssclean($this->input->post('id'));

         $subscriber_id = 'Sub_Id_'.$this->subscriberid;
        //$VarDir = UPLOADS_SLASH . "orderenquiry" . DIRECTORY_SEPARATOR . $VarFdrName . DIRECTORY_SEPARATOR;
    
        $VarDir = UPLOADS_SLASH . "orderenquiry". DIRECTORY_SEPARATOR . $subscriber_id .DIRECTORY_SEPARATOR . $VarFdrName . DIRECTORY_SEPARATOR;
        if (file_exists($VarDir)) {
        } else {
            mkdir($VarDir, 0777, true);
        }
        if (isset($_FILES["myFile"])) {
            $ret = array();
            $extension = pathinfo($_FILES["myFile"]["name"], PATHINFO_EXTENSION);
            if (in_array($extension, $ArrExtensions)) {
                $fileName = str_replace('&', '_', $_FILES["myFile"]["name"]);
                 /////////////////////////////////
                    $fileName = preg_replace('/\s+/', '_', $fileName); // Replace spaces with underscores
                    $fileName = preg_replace('/[^A-Za-z0-9_\-\.]/', '', $fileName); // Remove unwanted characters
                    /////////////////////////////////
                /**MAX file size 7 MB**/
                if ($_FILES["myFile"]["size"] <= MAXUPLSIZE) {
                    if (move_uploaded_file($_FILES["myFile"]["tmp_name"], $VarDir . $fileName))
                        $ret[] = $fileName;
                } else {
                    $ret[] = 'Err';
                }
            } else {
                $ret[] = 'Err';
            }
            echo json_encode($ret);
        }

    }

    function enqFileDownload() {
        $VarId = urldecode(base64_decode(xssclean($this->input->get('id'))));
        $fileName = urldecode((xssclean($this->input->get('fileName'))));
        $VarLocation = UPLOADS_SLASH . "orderenquiry". DIRECTORY_SEPARATOR . $VarId . DIRECTORY_SEPARATOR;
        if (isset($fileName)) {
            $file = $VarLocation . $fileName;
            $filePath = str_replace("..", "", $file);
            if (file_exists($filePath)) {
                header('Content-Type: application/octet-stream');
                header('Content-Disposition: attachment; filename=' . $fileName);
                header('Expires: 0');
                header('Cache-Control: must-revalidate');
                header('Pragma: public');
                header('Content-Length: ' . filesize($filePath));
                readfile($filePath);
                exit;
            }
        }
    }

    public function enqOpenFile() {
        $VarId = xssclean($this->input->get('id'));
        $VarFileName = urldecode(xssclean($this->input->get('fileName')));
         $subscriber_id = 'Sub_Id_'.$this->subscriberid;
        // get contents of a file into a string
        $filePath = UPLOADS_SLASH . "orderenquiry" . DIRECTORY_SEPARATOR . $subscriber_id . DIRECTORY_SEPARATOR . $VarId . DIRECTORY_SEPARATOR . $VarFileName;
       // $filePath = UPLOADS_SLASH . "orderenquiry" . DIRECTORY_SEPARATOR . $VarId . DIRECTORY_SEPARATOR . $VarFileName;
        $VarContentType = mime_content_type($filePath);
        header('Content-Type:' . $VarContentType);
        readfile($filePath);
        exit;
    }

      public function enqDeleteFile() {
        $VarId = urldecode(base64_decode(xssclean($this->input->post('enquiry_id'))));
        $VarFileName = urldecode(xssclean($this->input->post('filename')));
        $filePath = UPLOADS_SLASH . "orderenquiry" . DIRECTORY_SEPARATOR . $VarId . DIRECTORY_SEPARATOR . $VarFileName;
        unlink($filePath);
        exit;
    }

    public function getOrderEnquiryList() 
    {
        header('Access-Control-Allow-Origin: *');
        $output = $this->merchantmodel->getOrderEnquiryListt();
        echo json_encode($output);
    }
    
    public function getBrandList() 
    {
        header('Access-Control-Allow-Origin: *');
        $output = $this->merchantmodel->getBrandListt();
        return $output;
    }

    public function searchEnquiryList() {
        header('Access-Control-Allow-Origin: *');
        $data = $this->input->post();
        $output = $this->merchantmodel->searchEnquiryListt($data);
        echo json_encode($output);
    }
    
    public function searchEnquiryIORList() {
        header('Access-Control-Allow-Origin: *');
        $data = $this->input->post();
        $output = $this->merchantmodel->searchEnquiryIORListt($data);
        echo json_encode($output);
    }
    
    public function searchEnquiryISRList() {
        header('Access-Control-Allow-Origin: *');
        $data = $this->input->post();
        $output = $this->merchantmodel->searchEnquiryISRListt($data);
        echo json_encode($output);
    }
    
    public function submitAuthRequest() {
        header('Access-Control-Allow-Origin: *');
        $data = $this->input->post();
        $output = $this->merchantmodel->submitAuthRequestt($data);
        echo json_encode($output);
    }

    public function getSeperateOrderEnquiryList() 
    {
        header('Access-Control-Allow-Origin: *');
        $data = $this->input->post();
        $output = $this->merchantmodel->getSeperateOrderEnquiryListt($data);
        echo json_encode($output);
    }

    public function getWIPList() 
    {
        header('Access-Control-Allow-Origin: *');
        $output = $this->merchantmodel->getWIPListt();
        echo json_encode($output);
    }

    public function searchWIPList() {
        header('Access-Control-Allow-Origin: *');
        $data = $this->input->post();
        $output = $this->merchantmodel->searchWIPListt($data);
        echo json_encode($output);
    }

    public function wipPrecosting() 
    {
        /*
         * For getting folder name of uploads in for order enquiry we need to send user type
         * */
        $ArrUserType    = unserialize(ARRUSERTYPE);
        $VarId          = $this->uri->segment(3);
        $VarEnqId       = '';
        $ArrEnquiryInfo = array();
        if ($VarId <> '' && is_numeric(base64_decode(urldecode($VarId))))
        {
            $VarEnqId       = base64_decode(urldecode($VarId));
            $ArrEnquiryInfo = $this->menquirymodel->fnGetInfo('', $VarEnqId, $this->companyid);
        }
        $ArrUserInfo    = fnGetUserLoggedInfo(1);
        $VarUserType    = $ArrUserType[$ArrUserInfo['usertype']];
        $ArrOrderStatus = unserialize(ORDERENQUIRYSTATUS);
        $ArrCountries   = unserialize(ARRCOUNTRYLIST);
        $ArrEnquiryType = ARRENQUIRYTYPE;
        $ArrModeType    = $this->commonmodel->fnGetAllTableInfo(KN_MASTER_MODEOFENQUIRY, 'id,modeofenquiry as name', array('status' => '1', 'companyid' => $this->companyid));
        $ArrCurrency    = unserialize(ARRCURRENCYLIST);
        $ArrBrand       = $this->commonmodel->fnGetAllTableInfo(KN_MASTER_BRANDS  . ' AS br', 'br.id,br.brandname', array('br.status' => '1', 'br.companyid' => $this->companyid), 3);
        $ArrBuyer       = $this->commonmodel->fnGetAllTableInfo(KN_MASTER_BUYER . ' AS byr', 'byr.id,byr.buyername', array('byr.status' => '1', 'byr.companyid' => $this->companyid), 3);

        // Size
        $size_ids = 'SELECT size_ids FROM tbl_pc_size_chart WHERE enquiry_id = ' . $VarEnqId;
        $ids      = $this->db->query($size_ids)->row();
        $sizeIds  = isset($ids->size_ids) ? $ids->size_ids : '';

        $size_wise_parts = array();
        if (!empty($sizeIds))
        {
            $userInfoQry     = "SELECT size_name FROM tbl_size_master sm WHERE sm.id IN (" . $sizeIds . ")";
            $size_wise_parts = $this->db->query($userInfoQry)->result_array();
        }

        $userInfoQry = 'SELECT c.id AS component_id, c.comp_name AS component_name, c.dying_type FROM tbl_components c WHERE c.enquiry_id=' . $VarEnqId;
        $components  = $this->db->query($userInfoQry)->result_array();
        $this->load->view('workinprocess/index', array('ArrEnquiryType' => $ArrEnquiryType, 'ArrCountries'   => $ArrCountries,
            'ArrModeType'    => $ArrModeType, 'ArrCurrency'    => $ArrCurrency, 'ArrBrand'       => $ArrBrand, 'ArrBuyer'       => $ArrBuyer, 'VarEnqId'       => $VarEnqId,
            'ArrEnquiryInfo' => $ArrEnquiryInfo, 'ArrOrderStatus' => $ArrOrderStatus, 'UserType'       => $VarUserType,
            'components'     => $components,
            'VarEnquiryId'   => ''
        ));
    }

    public function getISRList() 
    {
        header('Access-Control-Allow-Origin: *');
        $output = $this->merchantmodel->getISRListt();
        echo json_encode($output);
    }

    public function getIORList() 
    {
        header('Access-Control-Allow-Origin: *');
        $output = $this->merchantmodel->getIORListt();
        echo json_encode($output);
    }

    public function stationerylist() {
        $data['brands'] = $this->getBrandList();
        $this->load->view('merchant/stationerylist', $data);
    }

    public function getStationeryList() 
    {
        header('Access-Control-Allow-Origin: *');
        $output = $this->merchantmodel->getStationeryListt();
        echo json_encode($output);
    }

    public function allmaterialindent() {
        $data['brands'] = $this->getBrandList();
        $this->load->view('merchant/mi/all', $data);
    }

    public function getAllMIList() 
    {
        header('Access-Control-Allow-Origin: *');
        $output = $this->merchantmodel->getAllMIListt();
        echo json_encode($output);
    }

    public function cadmaterialindent() {
        $data['brands'] = $this->getBrandList();
        $this->load->view('merchant/mi/cad', $data);
    }

    public function getCADMIList() 
    {
        header('Access-Control-Allow-Origin: *');
        $output = $this->merchantmodel->getCADMIListt();
        echo json_encode($output);
    }

    public function bommaterialindent() {
        $data['brands'] = $this->getBrandList();
        $this->load->view('merchant/mi/bom', $data);
    }

    public function getBOMMIList() 
    {
        header('Access-Control-Allow-Origin: *');
        $output = $this->merchantmodel->getBOMMIListt();
        echo json_encode($output);
    }

    public function bom2materialindent() {
        $data['brands'] = $this->getBrandList();
        $this->load->view('merchant/mi/bom2', $data);
    }

    public function getBOM2MIList() 
    {
        header('Access-Control-Allow-Origin: *');
        $output = $this->merchantmodel->getBOM2MIListt();
        echo json_encode($output);
    }

    public function fabricmaterialindent() {
        $data['brands'] = $this->getBrandList();
        $this->load->view('merchant/mi/fabric', $data);
    }

    public function getFabricMIList() 
    {
        header('Access-Control-Allow-Origin: *');
        $output = $this->merchantmodel->getFabricMIListt();
        echo json_encode($output);
    }

    public function stationerymaterialindent() {
        $data['brands'] = $this->getBrandList();
        $this->load->view('merchant/mi/stationery', $data);
    }

    public function getStationeryMIList() 
    {
        header('Access-Control-Allow-Origin: *');
        $output = $this->merchantmodel->getStationeryMIListt();
        echo json_encode($output);
    }

    public function allpurchaseindent() {
        $data['brands'] = $this->getBrandList();
        $this->load->view('merchant/pi/all', $data);
    }

    public function getAllPIList() 
    {
        header('Access-Control-Allow-Origin: *');
        $output = $this->merchantmodel->getAllPIListt();
        echo json_encode($output);
    }

    public function fabricpurchaseindent() {
        $data['brands'] = $this->getBrandList();
        $this->load->view('merchant/pi/fabric', $data);
    }

    public function getFabricPIList() 
    {
        header('Access-Control-Allow-Origin: *');
        $output = $this->merchantmodel->getFabricPIListt();
        echo json_encode($output);
    }

    public function stationerypurchaseindent() {
        $data['brands'] = $this->getBrandList();
        $this->load->view('merchant/pi/stationery', $data);
    }

    public function getStationeryPIList() 
    {
        header('Access-Control-Allow-Origin: *');
        $output = $this->merchantmodel->getStationeryPIListt();
        echo json_encode($output);
    }

    public function garmentreceivedlist() {
         $data['brands'] = $this->getBrandList();
        $this->load->view('merchant/garmentreceivedlist', $data);
    }

    public function getGarmentIssuedList() {
        header('Access-Control-Allow-Origin: *');
        $output = $this->msamplerequestmodel->getGarmentIssuedListt();
        echo json_encode($output);
    }
    public function getcleardraftstatus() 
    {   
        $enquiry_id = intval(xssclean($this->input->post('id')));
        if(!empty($enquiry_id)){
        $ArrResult=$this->merchantmodel->cleardraft($enquiry_id);
        echo json_encode($ArrResult);
        }else{
           echo false; 
        }
    }
    public function Test()
    {
        $this->load->view('merchant/test','');
        
    }

}
