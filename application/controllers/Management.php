<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Management extends CI_Controller {

    public $companyid = '';
    public $userid = '';
    public $usertypeid = '';
    public $mysqldatetime = '';

    public function __construct() {
        parent::__construct();
        error_reporting(E_ALL);
        $this->load->helper('xssclean');
        fnIfCheckUserLoggedIn();
        $this->load->model(CNFCOMPANY . 'menquirymodel');
        $ArrUserLoggedInfo = fnGetUserLoggedInfo('1');
        $this->companyid = $ArrUserLoggedInfo['companyid'];
        $this->userid = $ArrUserLoggedInfo['id'];
        $this->usertypeid = $ArrUserLoggedInfo['usertype'];
        $this->mysqldatetime = date('Y-m-d H:i:s');
        $this->load->model('commonmodel');
        $this->load->model('managementmodel');
        $this->load->model(CNFCOMPANY . "mcadrequestmodel");
        $this->load->model(CNFCOMPANY . 'orderentrymodel');
        $ArrObjsubscriber_id = $this->commonmodel->getsubscriberid($this->userid = $ArrUserLoggedInfo['id']);
        $this->subb_id = $ArrObjsubscriber_id->subscriber_id;
    }

    public function index() {
        $this->load->view('management/mgmtdashboard');
    }

    public function common_list() {
        $data['type'] = 'ALL';
        $data['title'] = 'REQUEST SENT LIST';
         $data['brands'] = $this->getBrandList();
        $this->load->view('management/common_list',$data);
    }

    // public function common_list() {
    //     $data['type'] = 'ALL';
    //     $data['title'] = 'REQUEST SENT LIST';
    //     $this->load->view('management/common_list',$data);
    // }
      // public function common_list() {
    //     $data['type'] = 'ALL';
    //     $data['title'] = 'REQUEST SENT LIST';
    //     $this->load->view('management/common_list',$data);
    // }
    
    public function enquiryview() {
        $VarId = $this->uri->segment(3);
        $ArrUserType = unserialize(ARRUSERTYPE);

        $ArrEnquiryInfo = array();
        


        if ($VarId <> '' && base64_decode(urldecode($VarId))) {
            $VarEnqId = base64_decode(urldecode($VarId));
            /*4 - Merchant
             * */
            $VarUserType = $ArrUserType[3];
            $ResEnquiry = $this->menquirymodel->fnGetInfo('', $VarEnqId, $this->companyid);
            $VarMerUser = $this->commonmodel->getUserInfo($ResEnquiry[0]->merchantid);
            $ObjEnquiry = $ResEnquiry[0];
            $ArrCountryList = unserialize(ARRCOUNTRYLIST);
            $ArrCurrencyList = unserialize(ARRCURRENCYLIST);
            $ArrIsrIor = unserialize(ARRISRIOR);
            $ArrPcsOrSet = unserialize(ARRPCSSET);
            $ArrOrderStatus = unserialize(ORDERENQUIRYSTATUS);
            $Brand = $this->commonmodel->fnGetAllTableInfo(KN_MASTER_BRANDS . ' AS br', 'br.id,br.brandname', array('br.status' => '1', 'br.companyid' => $this->companyid),3);
            $ArrBrand = array_column($Brand,'brandname','id');
            $Buyer = $this->commonmodel->fnGetAllTableInfo(KN_MASTER_BUYER . ' AS byr', 'byr.id,byr.buyername', array('byr.status' =>'1', 'byr.companyid' => $this->companyid),3);
            $ArrBuyer = array_column($Buyer,'buyername','id');
            
            $userInfoQry = 'SELECT c.id AS component_id, c.comp_name AS component_name, c.dying_type FROM tbl_components c WHERE c.enquiry_id=' . $VarEnqId;
            $components  = $this->db->query($userInfoQry)->result_array();

            // new 
            $ArrEnquiryInfo = $this->menquirymodel->fnGetInfo('', $VarEnqId, $this->companyid);
            $ArrModeType    = $this->commonmodel->fnGetAllTableInfo(KN_MASTER_MODEOFENQUIRY, 'id,modeofenquiry as name', array('status' => '1', 'companyid' => $this->companyid));
            $ArrEnquiryType = ARRENQUIRYTYPE;
            $ArrCommonHeaderData = $this->commonheaderdata($VarEnqId);
            
        }
        $this->load->view('management/orderenquiryview', 
            array('ObjEnquiry' => $ObjEnquiry, 
                  'VarIsrIor' => $ArrIsrIor[$ObjEnquiry->reqforisrior],
                  'VarIsrIorId' => $ObjEnquiry->reqforisrior, 
                  'VarCurrency' => @$ArrCurrencyList[$ObjEnquiry->currency], 
                  'VarCountry' => @$ArrCountryList[$ObjEnquiry->countryid], 
                  'VarMerUser' => @$VarMerUser[0], 
                  'VarPcsorSet' => @$ArrPcsOrSet[$ObjEnquiry->pcsorset], 
                  'VarEnqId' => $VarEnqId, 
                  'ArrOrderStatus' => $ArrOrderStatus, 
                  'ArrBrand'=>$ArrBrand,
                  'ArrBuyer'=>$ArrBuyer,
                  'UserType'=>$VarUserType, 
                  'components' => $components,
                  'VarEnquiryId' => '',
                  'ArrEnquiryInfo' => $ArrEnquiryInfo,
                  'ArrCurrency' => $ArrCurrencyList,
                  'ArrModeType' => $ArrModeType, 
                  'ArrCountries'   => $ArrCountryList, 
                  'ArrEnquiryType' => $ArrEnquiryType,
                  'ArrCommonHeaderData' => $ArrCommonHeaderData
            ));
    }

    function fnCheckPin_old() {
        $VarPw = xssclean($this->input->post('i'));
        $ArrIsrIor = unserialize(ARRISRIOR);
        if ($this->commonmodel->fnValidatePin($this->userid, $VarPw)) {
            $VarEnquiryId = xssclean($this->input->post('enqid'));
            $VarRefType = xssclean($this->input->post('ty'));
            $VarApprovalStatus = xssclean($this->input->post('s'));
            $VarComments = addslashes(xssclean($this->input->post('c')));
            $VarAssignedNo = 0;
            $this->menquirymodel->fnAuthorize($this->companyid, $VarEnquiryId, $VarApprovalStatus, $VarComments, $VarRefType);
            if ($VarApprovalStatus == 2) {
                echo json_encode(array('errcode' => '1', 'msg'=>'Save Successfully','redirectUrl'=>'management/manageWip'));
                
            } elseif ($VarApprovalStatus == 3) {
                echo json_encode(array('errcode' => '1', 'msg'=>'Rejected','redirectUrl'=>'management/orderEnquiryList'));
            }
            else {

            }
        } else {
            echo json_encode(array('errcode' => '-1', 'msg' => 'Invalid PIN'));
        }
    }

    


    function fnCheckPin() {
        $VarPw = xssclean($this->input->post('i'));
      
         if ($this->commonmodel->fnValidatePin($this->userid, $VarPw)) {
            echo json_encode(array('errcode' => '1', 'dupdated' => date('d-m-Y / H:i:s')));
        } else {
            echo json_encode(array('errcode' => '-1', 'msg' => 'Invalid PIN'));
        }
    }

    public function submitAuthRequest() {
        header('Access-Control-Allow-Origin: *');
        $data = $this->input->post();
         $VarPw = isset($data['id']) ? $data['id'] : null;
    
            $VarEnquiryId = xssclean($this->input->post('enqid'));
            $VarRefType = xssclean($this->input->post('ty'));
            $VarApprovalStatus = xssclean($this->input->post('s'));
            $VarComments = addslashes(xssclean($this->input->post('c')));
            $VarAssignedNo = 0;
            $result =$this->menquirymodel->fnAuthorize($this->companyid, $VarEnquiryId, $VarApprovalStatus, $VarComments, $VarRefType);
          if ($result) {
   
   
         echo json_encode(array('statusCode' => '200', 'msg' => 'success to process the request'));
} else {
    // If the result is false or an error occurred in the model
    echo json_encode(array('statusCode' => '203', 'msg' => 'Failed to process the request'));
}             
    //}
}

    function changemStatus() {
        $VarActDeactOption = xssclean($this->input->post('actdeact'));
        $VarCid = xssclean($this->input->post('cid'));
        if ($VarActDeactOption <> '' && $VarCid <> '') {
            if ($this->managementmodel->fnChangeStatus($VarCid, $VarActDeactOption)) {
                echo json_encode(array('errcode' => '1'));
                die;
            } else {
                echo json_encode(array('errcode' => '-1'));
            }
        }
    }

    function assignroles() {
        $VarMode = $this->uri->segment(3);
        if ($VarMode == 'edit') {
            $ArrData['VarEditMode'] = 1;
        } else {
            $ArrData['VarEditMode'] = 0;
        }
        //}
        //else {
        $ArrLabList = $this->managementmodel->getMgmtUsers($this->companyid);
        $i = 0;
        $ArrFnlList = array();
        foreach ($ArrLabList as $ObjUnit) {
            //echo '<pre>'; print_r($ResRolesData);
            $ArrFnlList[$i]['id'] = $ObjUnit->id;
            $ArrFnlList[$i]['n'] = $ObjUnit->contactname;
            $ArrFnlList[$i]['e'] = $ObjUnit->username;
            $ArrFnlList[$i]['modules'] = array('1' => 'Enquiry', '2' => 'CAD');
            $ArrFnlList[$i]['datas'] = $this->managementmodel->getMgmtRolesById($ObjUnit->id, $this->companyid);
            $i = $i + 1;
        }
        //die;
        $ArrData['mgmtusers'] = $ArrFnlList;
        //}
        $this->load->view('assignroles', array('ArrData' => $ArrData));
    }

    function updateMgmtRoles() {
        $VarRfrom = xssclean($this->input->post('rfrom'));
        $VarRoles = json_decode(xssclean($this->input->post('r')));
        $VarEditMode = xssclean($this->input->post('editmode'));
        //echo '<pre>'; print_r($VarRoles);
        foreach ($VarRoles as $role) {
            //echo substr($role,strpos($role,'_')+1);
            $VarUid = substr($role, strpos($role, '_') + 1);
            $VarModuleId = explode('_', $role);
            //echo '<pre>'; print_r($VarModuleId[0]);
            $FinalArr[$VarUid][] = $VarModuleId[0];
            //$ArrRolesData[] = array('mgmtuserid'=>$VarUid,'enquiryrole'=>$VarModuleId[0],'cadrole'=>$VarModuleId[0],'updatedby'=>$this->userid,'companyid'=>$this->companyid,'datecreated'=>$this->mysqldatetime);
        }
        //echo '<pre>'; print_r($ArrRolesData);
        //echo '<pre>'; print_r($FinalArr);
        //$VarId = xssclean($this->input->post('id'));
        //$VarRoles = array('42'=>array('1','2','3'),'41'=>array('4','5'));
        //$ArrUpdateData = array('role'=>serialize($FinalArr),'updatedby'=>$this->userid,'companyid'=>$this->companyid,'datecreated'=>$this->mysqldatetime);
        $Res = $this->managementmodel->saveMgmtRoles($FinalArr, $this->companyid, $this->mysqldatetime, $VarEditMode);
        if ($Res) {
            echo json_encode(array('errcode' => '1', 'msg' => 'Roles Saved'));
        } else {
            echo json_encode(array('errcode' => '-1', 'msg' => 'Err'));
        }
    }

    function getMgmtRoles() {
        $VarRfrom = xssclean($this->input->post('rfrom'));
        if ($VarRfrom == 1) {
            $ResRoles = $this->commonmodel->getRolesByCompanyId($this->companyid);
            $ArrRoles = unserialize($ResRoles[0]->role);
            if (empty($ArrRoles)) {
                echo json_encode(array('errcode' => '1', 're' => array()));
            } else {
                echo json_encode(array('errcode' => '1', 're' => array($ArrRoles)));
            }
        }
    }

    public function manageAuthorizationRequest() {
        $VarFrom = xssclean($this->input->post('rFrom'));
        if($VarFrom == 1) {
            $ArrList = $this->managementmodel->authListDataTables();
            //echo '<pre>'; print_r($ArrList); die('die');
            $data = array();
            $ArrStatus = unserialize(ARRSTATUS);
            $ArrBomRequirement = unserialize(ARRBOMREQUIREMENT);
            $ArrCommonStatus = unserialize(ORDERENQUIRYSTATUS);
            $VarRequirement = '';
            foreach ($ArrList as $Obj) {
                $row = array();
                /*CAD request,sample and bom has jxl grid and its saved in as JSON in DB*/
                $jsonData = json_decode($Obj->jsondatagrid,true);
                $row[] = '<input type="checkbox" class="allcbox" id="' . $Obj->id . '">';
                if ($Obj->request_type_dept == "CAD") {
                    $reqSentListDetailUrl = 'management/mgmtcadauthorizing';
                    $VarRequirement = empty($jsonData[0][4]) ? '-' : $jsonData[0][4];
                    $VarCs = 'CAD DEPT. '.$ArrCommonStatus[$Obj->deptcurrentstatus];
                }
                elseif ($Obj->request_type_dept == "SAMPLE") {
                    $reqSentListDetailUrl = 'management/mgmtsamauthorizing';
                    $jsonData = json_decode($Obj->jsondatagrid);
                    $VarRequirement = empty($jsonData[0][5]) ? '-' : $jsonData[0][5];
                    $VarCs = 'SAMPLE DEPT. '.$ArrCommonStatus[$Obj->deptcurrentstatus];
                }
                elseif ($Obj->request_type_dept == "BOM") {
                    $reqSentListDetailUrl = 'management/mgmtbompurauthorizing';
                    $VarRequirement = $ArrBomRequirement[$Obj->requirementforbom];
                    $VarCs = 'PURCHASE DEPT. '.$ArrCommonStatus[$Obj->deptcurrentstatus];
                }
                else
                {
					$reqSentListDetailUrl = '';
                }

                $row[] = '<a href="' . base_url($reqSentListDetailUrl) . '/' . urlencode(base64_encode($Obj->id)) . '">' . $Obj->isriorcode . '</a>';
                $row[] = $Obj->brandname;
                $row[] = $Obj->request_type_dept;
                $row[] = $VarRequirement;
                $jsonData = array(array());
                if(!empty($Obj->jsondatagrid)) {
                    $jsonData = json_decode($Obj->jsondatagrid,true);
                }
                $row[] = $Obj->requesttype;
                $row[] = $Obj->formattedDateCreated;
                $row[] = $Obj->formatCutOffDt;
                $row[] = $Obj->approvaltype;
                $row[] = $Obj->contactname;
				if($Obj->deptcurrentstatus == 1) {
					$VarCs = 'MANAGEMENT '.$ArrCommonStatus[$Obj->mgmtcurrentstatus];
				}
				else {

				}
                $row[] = '<a href="javascript:void(0)">' . $VarCs . '</a>';
                $row[] = $Obj->formattedDateUpdated;
                $row[] = $ArrStatus[$Obj->status];
                $data[] = $row;
            }
            $output = array(
                "draw" => $_POST['draw'],
                "recordsTotal" => $this->managementmodel->authListCountAll(),
                "recordsFiltered" => $this->managementmodel->authListCountFiltered(),
                "data" => $data,
            );
            echo json_encode($output);
        }
        else {
            $this->load->view('management/manageauthorizationrequest', array());
        }
    }

    public function getAllRequestList() {
        header('Access-Control-Allow-Origin: *');
        $output = $this->managementmodel->getAllRequestListt();
        echo json_encode($output);
    }

    public function mgmtcadauthorizing() {
        $VarId = base64_decode(urldecode($this->uri->segment(3)));
        //$VarId		    									    = $this->uri->segment(3);
        $VarHashedCadRequestId = $this->uri->segment(3);
        if ($VarId >= 1) {
            $ArrReqData = $this->mcadrequestmodel->getCadRequestData($VarId, $this->companyid);
            $jsonAttachmentJxl = $ArrReqData->jsonAttachmentDetails;
            $jsonDataGrid = $ArrReqData->jsondatagrid;
            $VarOrderId = $ArrReqData->orderid;
            if ($VarOrderId >= 1) {
                $this->load->model(CNFCOMPANY . "orderentrymodel");
                if ($VarOrderId >= 1) {
                    $ArrOrderDatas = $this->orderentrymodel->getOrderDataByWip($VarOrderId, $this->companyid);
                    if (empty($ArrOrderDatas)) {
                        die('Order Entry not completed');
                    } else {
                        $VarAuthMgmtInfo = $this->commonmodel->getUserInfo($ArrReqData->mgmtid);
                        $ArrOrderEnqData = $this->orderentrymodel->fnGetOrderEnquiryInfo($VarOrderId, $this->companyid, '');
                        $VarCommonOrderEntryInfo = $this->commonmodel->commonBasicInfoOrderEntry($VarOrderId, $this->companyid);
                        $ArrData = array(
                            'ArrBasicInfo' => '',
                            'ArrCompanyInfo' => $VarCommonOrderEntryInfo['ArrCompanyInfo'],
                            'ArrMerchant' => $VarCommonOrderEntryInfo['ArrMerchant'],
                            'ArrTeamInfo' => $VarCommonOrderEntryInfo['ArrTeamInfo'],
                            'ArrOrderEnqData' => $ArrOrderEnqData[0],
                            'brandName' => $VarCommonOrderEntryInfo['brandName'],
                            'buyerName' => $VarCommonOrderEntryInfo['buyerName'],
                            'ArrOrderCommonData' => $VarCommonOrderEntryInfo['ArrOrderCommonData'],
                            'VarId' => '',
                            'VarAuthMgmtInfo' => $VarAuthMgmtInfo,
                            'VarNew' => '0',
                            'mgmtcurrentstatus' => '0',
                            'cadcurrentstatus' => 0,
                            'orderid' => $VarOrderId,
                            'queueno' => 0,
                            'jsonDataGrid' => $jsonDataGrid,
                            'jsonAttachmentJxl'=>$jsonAttachmentJxl
                        );
                    }
                }
            }
            $ArrData['ArrBasicInfo'] = $ArrReqData;
            $ArrData['mgmtcurrentstatus'] = $ArrReqData->mgmtcurrentstatus;
            $ArrData['cadcurrentstatus'] = $ArrReqData->deptcurrentstatus;
            $ArrData['VarId'] = $ArrData['ArrBasicInfo']->id;
            //$ArrData['usertypeid'] = $this->usertypeid;
            $ArrData['HashedCadRequestId'] = $VarHashedCadRequestId;
        }
        $this->load->view('management/mgmtcadauthorizing', $ArrData);
    }

    public function mgmtsamauthorizing() {
        $this->load->model(CNFCOMPANY . 'msamplerequestmodel');
        $VarId = base64_decode(urldecode($this->uri->segment(3)));
        //$VarId		    									    = $this->uri->segment(3);
        $VarHashedCadRequestId = $this->uri->segment(3);
        if ($VarId >= 1) {
            $ArrObjRequestData = $this->msamplerequestmodel->getSamRequestData($VarId, $this->companyid);
            $ArrRequestData    = $ArrObjRequestData[0];
            $VarOrderId        = $ArrRequestData->orderid;
            $ArrData['ArrBasicInfo'] = $ArrRequestData;
                $cadIndentDetails = $this->commonmodel->fnGetAllTableInfo(KN_SAMPLEREQUESTINDENTDETAILS,'issuedtodept',
                    array('requestid'=>$VarId,'indenttype'=>1));
                //echo '<pre>'; print_r($cadIndentDetails); die('die');
                $fabIndentDetails = $this->commonmodel->fnGetAllTableInfo(KN_SAMPLEREQUESTINDENTDETAILS,'issuedtodept',
                    array('requestid'=>$VarId,'indenttype'=>2));
                $bomIndentDetails = $this->commonmodel->fnGetAllTableInfo(KN_SAMPLEREQUESTINDENTDETAILS,'issuedtodept',
                    array('requestid'=>$VarId,'indenttype'=>3));
                $ArrOrderDatas = $this->orderentrymodel->getOrderDataByWip($VarOrderId, $this->companyid);
                if (empty($ArrOrderDatas)) {
                    die('Order Entry not completed');
                } else {
                    $VarCommonOrderEntryInfo = $this->commonmodel->commonBasicInfoOrderEntry($VarOrderId, $this->companyid);
                    $ArrOrderEnqData = $this->orderentrymodel->fnGetOrderEnquiryInfo($VarOrderId, $this->companyid, '');
                    //$ArrData['ArrRequirement'] = json_encode(array_values(unserialize(ARRSAMPLEREQREQUIREMENT)));
                    $ArrData['VarReqId'] = $VarId;
                    //$ArrData['VarCommonOrderEntryInfo'] =
                    $ArrData['ArrCompanyInfo'] = $VarCommonOrderEntryInfo['ArrCompanyInfo'];
                    $ArrData['ArrMerchant'] = $VarCommonOrderEntryInfo['ArrMerchant'];
                    $ArrData['ArrTeamInfo'] = $VarCommonOrderEntryInfo['ArrTeamInfo'];
                    $ArrData['ArrOrderEnqData'] = $ArrOrderEnqData[0];
                    $ArrData['brandName'] = $VarCommonOrderEntryInfo['brandName'];
                    $ArrData['buyerName'] = $VarCommonOrderEntryInfo['buyerName'];
                    $ArrData['ArrOrderCommonData'] = $VarCommonOrderEntryInfo['ArrOrderCommonData'];
                    $ArrData['ArrAllUsertypes'] = unserialize(ARRUSERTYPE);
                }
            $ArrData['ArrBasicInfo'] = $ArrRequestData;
            $VarMerchantData = $this->commonmodel->getMerchantData($this->companyid, 1, $ArrRequestData->merchantid);
            $ArrData['merchantdata'] = @$VarMerchantData[0];
            $ArrData['VarId'] = $ArrData['ArrBasicInfo']->id;
            $ArrData['cadIndentDetails'] = $cadIndentDetails;
            $ArrData['fabIndentDetails'] = $fabIndentDetails;
            $ArrData['bomIndentDetails'] = $bomIndentDetails;
            $ArrData['AuthorizedByInfo']  = $this->commonmodel->getUserInfo($ArrRequestData->mgmtid);
            $ArrData['HashedCadRequestId'] = $VarHashedCadRequestId;
        }
        $this->load->view('management/mgmtsamauthorizing', $ArrData);
    }

    public function mgmtbompurauthorizing() {
        $this->load->model('mbompurchaserequestmodel');
        $VarId = base64_decode(urldecode($this->uri->segment(3)));
        if ($VarId >= 1) {
            $RequestData = $this->mbompurchaserequestmodel->getBomPurchaseRequest($VarId, $this->companyid);
            $VarOrderId = $RequestData->orderid;
            $VarCommonOrderEntryInfo = $this->commonmodel->commonBasicInfoOrderEntry($VarOrderId, $this->companyid);
            $ArrOrderEnqData = $this->orderentrymodel->fnGetOrderEnquiryInfo($VarOrderId, $this->companyid, '');
            $ArrData['VarCommonOrderEntryInfo'] = $VarCommonOrderEntryInfo;
            $ArrData['ArrCompanyInfo'] = $VarCommonOrderEntryInfo['ArrCompanyInfo'];
            $ArrData['ArrMerchant'] = $VarCommonOrderEntryInfo['ArrMerchant'];
            $ArrData['ArrTeamInfo'] = $VarCommonOrderEntryInfo['ArrTeamInfo'];
            $ArrData['ArrOrderEnqData'] = $ArrOrderEnqData[0];
            $ArrData['brandName'] = $VarCommonOrderEntryInfo['brandName'];
            $ArrData['buyerName'] = $VarCommonOrderEntryInfo['buyerName'];
            $ArrData['VarOrderId'] = $VarOrderId;
            $ArrData['ArrOrderCommonData'] = $VarCommonOrderEntryInfo['ArrOrderCommonData'];

            $ArrData['ArrBasicInfo'] = $RequestData;
            $ArrData['VarArtType'] = $RequestData->articletypeid;
            $ArrData['VarId'] = $VarId;
            $this->load->view('management/mgmtbompurauthorizing', $ArrData);
        }
    }

    function cadRequestCheckPin() {
        $VarPwd = xssclean($this->input->post('pwd'));
        $VarCadRequestId = xssclean($this->input->post('id'));
        $VarApproveReject = xssclean($this->input->post('mgmtStatus'));
        $VarRemarks = xssclean($this->input->post('mgmtRemarks'));
        $VarApprovalType = xssclean($this->input->post('approvalType'));
        $VarCurrentStatus = xssclean($this->input->post('currentStatus'));
        if ($this->commonmodel->fnValidatePin($this->userid, $VarPwd)) {
            $ArrSaveRequest = array(
                'id' => $VarCadRequestId,
                'mgmtid' => $this->userid,
                'mgmtremarks' => $VarRemarks,
                'mgmtcurrentstatus' => $VarApproveReject,
                'approvaltype' => $VarApprovalType,
                'current_status'=> $VarCurrentStatus,
                'dateupdated' => $this->mysqldatetime,
                'authdatetime'=>$this->mysqldatetime
            );
            if ($this->mcadrequestmodel->saveAuthoriseCadRequest($ArrSaveRequest)) {
                echo json_encode(array('errcode' => '1', 'msg' => '', 'cs' => $VarApproveReject,'dateTime'=>date('d-m-Y H:i:s')));
            }
        } else echo json_encode(array('errcode' => '-1', 'msg' => 'Invalid PIN','dateTime'=>date('d-m-Y H:i:s')));
    }

    function samRequestCheckPin() {
        $VarPwd = xssclean($this->input->post('pwd'));
        $VarCadRequestId = xssclean($this->input->post('id'));
        $VarApproveReject = xssclean($this->input->post('cs'));
        $VarRemarks = xssclean($this->input->post('mgmtremarks'));
        $VarApprovalType = xssclean($this->input->post('approvaltype'));
        if ($this->commonmodel->fnValidatePin($this->userid, $VarPwd)) {
            $this->load->model(CNFCOMPANY . 'msamplerequestmodel');
            $ArrSaveRequest = array(
                'mgmtid' => $this->userid,
                'mgmtremarks' => $VarRemarks,
                'mgmtcurrentstatus' => $VarApproveReject,
                'approvaltype' => $VarApprovalType,
                'dateupdated' => date('Y-m-d H:i:s'),
                'authdatetime'=> date('Y-m-d H:i:s')
            );
            if($VarApproveReject == 2) {
                $VarMsg = 'SAMPLE Request Authorized';
            }
            elseif($VarApproveReject == 3) {
                $VarMsg = 'SAMPLE Request Rejected';
            }
            $VarMsg = 'SAMPLE Request Authorized';
            if ($this->msamplerequestmodel->saveAuthoriseSamRequest($ArrSaveRequest, $VarCadRequestId)) {
                echo json_encode(array('errcode' => '1', 'msg' => $VarMsg, 'cs' => $VarApproveReject));
            }
        } else echo json_encode(array('errcode' => '-1', 'msg' => 'Invalid PIN'));
    }

    public function bomPiApprovalList() {
        $rFrom = xssclean($this->input->post('rfrom'));
        if ($rFrom == 1) {
            $ArrList = $this->bomPiApprovalListDataTables();
            $data = array();
            $ArrStatus = unserialize(ARRSTATUS);
            $ArrRequestType = unserialize(ARRREQUESTTYPE);
            $VarCs = '';
            $VarCs = 'Current Status';
            $ArrBomRequirement = unserialize(ARRBOMREQUIREMENT);
            foreach ($ArrList as $Obj) {
                $row = array();
                $row[] = '<input type="checkbox" class="allcbox" id="' . $Obj->id . '">';
                $row[] = $Obj->isriorcode;
                $row[] = $Obj->brandname;
                $row[] = '<a href="' . base_url('bompurchaseindent/mgmtbompurchaseindentreq') . '/' . urlencode(base64_encode($Obj->id)) . '">P.I. Approval</a>';
                $row[] = $ArrBomRequirement[$Obj->requirementforbom];
                $row[] = $Obj->datecreated;
                $row[] = $Obj->cutoffdatetime;
                if ($Obj->approvedstatus == 2) $row[] = 'Authorized';
                elseif ($Obj->approvedstatus == 3) $row[] = 'Not Authorized';
                else $row[] = '-';
                $row[] = $Obj->contactname;
                $row[] = '<a href="javascript:void(0)">' . $VarCs . '</a>';
                $row[] = date('d-m-Y H:i:s', strtotime($Obj->dateupdated));
                $row[] = $ArrStatus[$Obj->status];
                $data[] = $row;
            }
            $output = array(
                "draw" => $_POST['draw'],
                "recordsTotal" => $this->countAllBomPiApproval(),
                "recordsFiltered" => $this->countBomPiApprovalFiltered(),
                "data" => $data,
            );
            echo json_encode($output);
        } else {
            $this->load->view('management/bomPiApprovalList', array());
        }
    }

    public function bomPiApprovalListDataTables() {
        $this->bomPiApprovalListQry();
        if($_POST['length'] != -1)
            $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }

    public function bomPiApprovalListQry() {
        $this->db->select('pi.id,a.isriorcode,v.vendorname,purchaseindent_no,vendorid,articletype,pi.status,
        DATE_FORMAT(cutoffdatetime,"%d-%m-%Y %H:%i:%s") as cutoffdatetime,DATE_FORMAT(pi.dateupdated,"%d-%m-%Y %H:%i:%s") as dateupdated,
        approvedstatus,purchasedeptid,oe.isriorcode,brandname,DATE_FORMAT(pi.datecreated,"%d-%m-%Y %H:%i:%s") as datecreated,
        u.contactname,requirementforbom');
        $this->db->from(KN_BOM_PURCHASEINDENT.' AS pi');
        $this->db->join(KN_ALLREQUEST.' AS a','a.id = pi.bompurrequestid');
        $this->db->where('a.companyid',$this->companyid);
        $this->db->join(KN_MASTER_BOM_VENDOR.' AS v','v.id = pi.vendorid');
        $this->db->join(KN_ORDER_ENQUIRY.' AS oe','a.orderid = oe.id');
        $this->db->join(KN_MASTER_BRANDS.' AS br','br.id = oe.brandId');
        $this->db->join(KN_USERS.' AS u','pi.purchasedeptid = u.id');

        $bomPiApprListColOrder = array('pi.dateupdated','oe.isriorcode', 'brandname', 'a.id', 'a.requestdt', 'a.cutoffdatetime', 'a.merchantid',
            'a.approvaltype', 'a.mgmtid', 'a.dateupdated', 'a.status');
        $bomPiApprListColSearch = array('oe.isriorcode', 'brandname', 'a.id', 'a.requestdt', 'a.cutoffdatetime', 'a.merchantid',
            'a.approvaltype', 'a.mgmtid', 'a.dateupdated', 'a.status');
        $bomPiApprListOrder = array('pi.dateupdated' => 'desc');

        $i = 0;
        foreach ($bomPiApprListColSearch as $item) {
            if($_POST['search']['value']) {
                if($i===0) {
                    $this->db->group_start();
                    $this->db->like($item, $_POST['search']['value']);
                }
                else {
                    $this->db->or_like($item, $_POST['search']['value']);
                }
                if(count($bomPiApprListColSearch) - 1 == $i) {
                    $this->db->group_end();
                }
            }
            $i++;
        }
        if(isset($_POST['order'])) {
            $this->db->order_by($bomPiApprListColOrder[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        }
        else if(isset($this->order)) {
            $order = $bomPiApprListColOrder;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }

    public function countAllBomPiApproval() {
        $this->db->from(KN_BOM_PURCHASEINDENT.' AS pi');
        $this->db->join(KN_ALLREQUEST.' AS a','a.id = pi.bompurrequestid');
        $this->db->where('a.companyid',$this->companyid);
        $this->db->join(KN_MASTER_BOM_VENDOR.' AS v','v.id = pi.vendorid');
        $this->db->join(KN_ORDER_ENQUIRY.' AS oe','a.orderid = oe.id');
        $this->db->join(KN_MASTER_BRANDS.' AS br','br.id = oe.brandId');
        $this->db->join(KN_USERS.' AS u','a.merchantid = u.id');
        return $this->db->count_all_results();
    }

    public function countBomPiApprovalFiltered() {
        $this->bomPiApprovalListQry();
        $query = $this->db->get();
        return $query->num_rows();
    }

    function bomPurchaseRequestCheckPin() {
        $this->load->model('mbompurchaserequestmodel');
        $VarPwd = xssclean($this->input->post('pwd'));
        $VarRequestId = xssclean($this->input->post('id'));
        $VarApproveReject = xssclean($this->input->post('approveReject'));
        $VarRemarks = xssclean($this->input->post('mgmtremarks'));
        $VarApprovalType = xssclean($this->input->post('approvaltype'));
        $VarMsg = '';
        if ($this->commonmodel->fnValidatePin($this->userid, $VarPwd)) {
            if ($VarApproveReject == 2) {
                $VarMsg = 'BOM Purchase Request Authorized';
            } elseif ($VarApproveReject == 3) {
                $VarMsg = 'BOM Purchase Request Not Authorized';
            }
            $ArrSaveRequest = array(
                'id' => $VarRequestId,
                'mgmtid' => $this->userid,
                'mgmtremarks' => $VarRemarks,
                'mgmtcurrentstatus' => $VarApproveReject,
                'approvaltype' => $VarApprovalType,
                'authdatetime' => date('Y-m-d H:i:s'),
                'dateupdated' => date('Y-m-d H:i:s')
            );


            if ($this->mbompurchaserequestmodel->saveAuthoriseBomPurRequest($ArrSaveRequest)) {
                echo json_encode(array('errcode' => '1', 'msg' => $VarMsg, 'cs' => $VarApproveReject));
            }
        } else echo json_encode(array('errcode' => '-1', 'msg' => 'Invalid PIN', 'cs' => $VarApproveReject));

    }

    public function updateBomPurIndRequest() {
        $VarRfrom = xssclean($this->input->post('rfrom'));
        if ($VarRfrom == 1) {
            $VarPwd = xssclean($this->input->post('i'));
            $VarApprStatusCol = json_decode(xssclean($this->input->post('apprstatusCol')));
            $VarTblName = xssclean($this->input->post('tblname'));
            if ($this->commonmodel->fnValidatePin($this->userid, $VarPwd)) {
                $ArrUserLoggedInfo = fnGetUserLoggedInfo('1');
                foreach ($VarApprStatusCol as $value) {
                    $ArrUpatedData[] = array('approvalstatus' => $value, 'approvedby' => $ArrUserLoggedInfo['name'], 'dateupdated' => $this->mysqldatetime);
                }
                if ($this->commonmodel->updateBomPiApprReq($ArrUpatedData, $VarTblName)) {
                    echo json_encode(array('errcode' => '1'));
                    unset($ArrUpatedData);
                    die;
                } else {
                    echo json_encode(array('errcode' => '-1'));
                }
            }
        }
    }

    public function updateBomPaymentReq() {
        $VarId = xssclean($this->input->post('id'));
        $ArrApprStatus = xssclean($this->input->post('ApprStatus'));
        $ArrUpdate = array();
        $ArrbillpaymentApprstatus = xssclean($this->input->post('billpaymentApprstatus'));
        if (!empty($ArrApprStatus)) {
            $ArrUpdate = array('apprstatus' => $ArrApprStatus, 'approvedby' => $this->userid, 'dateupdated' => $this->mysqldatetime);
        }
        if (!empty($ArrbillpaymentApprstatus)) {
            $ArrUpdate = array('approvedby' => $this->userid, 'billpayapprstatus' => $ArrbillpaymentApprstatus, 'dateupdated' => $this->mysqldatetime);
        }
        $this->db->where('id', $VarId);
        $this->db->update(KN_BOMPAYMENT_REQUEST, $ArrUpdate);
        if ($this->db->affected_rows()) {
            echo json_encode(array('errcode' => '1'));
            die;
        } else {
            echo json_encode(array('errcode' => '-1'));
            die;
        }
    }

    public function BomPIApprovalCheckPin() {
        $rFrom = xssclean($this->input->post('rform'));
        if ($rFrom == 1) {
            $VarPwd = xssclean($this->input->post('i'));
            $bomPurIndId = xssclean($this->input->post('bomPurIndId'));
            $apprStatus = xssclean($this->input->post('apprStatus'));
            $this->load->model('mbompurcahseindentmodel');
            if ($this->commonmodel->fnValidatePin($this->userid, $VarPwd)) {
                $ArrJsonRes = $this->mbompurcahseindentmodel->saveBomPurchaseIndent(array('approvedbymgmtid' => $this->userid,
                    'approvedstatus' => $apprStatus,
                    'dateupdated' => $this->mysqldatetime,
                ), $bomPurIndId);
                echo $ArrJsonRes;
            } else {
                echo json_encode(array('errcode' => '-1', 'msg' => 'Invalid PIN', ''));
            }
        }
    }

    public function updatePurchaseIndentApprStatus() {
        $rFrom = xssclean($this->input->post('rfrom'));
        if ($rFrom == 1) {
            $VarBomPurIndId = xssclean($this->input->post('bomPurIndentId'));
            $apprStatus = xssclean($this->input->post('apprStatus'));
            $this->load->model('mbompurcahseindentmodel');
            $ArrBomPi = array('approvedbymgmtid' => $this->userid, 'approvedstatus' => $apprStatus, 'approvedDatetime' => $this->mysqldatetime);
            $ArrJsonRes = $this->mbompurcahseindentmodel->saveBomPurchaseIndent($ArrBomPi, $VarBomPurIndId);
            if($ArrJsonRes) {
                $ArrMgmtInfo = $this->commonmodel->getUserInfo($this->userid);
                echo json_encode(array('errcode'=>1,'msg'=>'saved Successfully','mgmtInfo'=>$ArrMgmtInfo));
            }
            else {
                echo json_encode(array('errcode'=>-1,'msg'=>'','mgmtInfo'=>''));
            }
        }
    }

    // public function manageWip() {
    //     $VarFrom = xssclean($this->input->post('rFrom'));
    //     if($VarFrom == 1) {
    //         $ArrList = $this->managementmodel->getWipDataTables();
    //         $i            = 0;
    //         $ArrFinalRes = $ArrIds = $VarKeysForPoqty = array();
    //         $ArrActiveInStatus = unserialize(ARRSTATUS);
    //         foreach ($ArrList as $key => $item) {
    //             $ArrFinalRes[$i]['poNoEnqRefNo']       = !empty($item->poNoEnqRefNo) ? $item->poNoEnqRefNo : '-';
    //             $ArrFinalRes[$i]['poQtySampleQty']       = !empty($item->poQtySampleQty) ? $item->poQtySampleQty : '-';
    //             $ArrFinalRes[$i]['shipmentSubDate']       = !empty($item->formattedShipmentSubDate) ? $item->formattedShipmentSubDate : '-';
    //             $ArrFinalRes[$i]['id']       = $item->id;
    //             $ArrFinalRes[$i]['isriorcode'] = $item->isriorcode;
    //             $ArrFinalRes[$i]['date']     = $item->formattedDateCreated;
    //             $ArrFinalRes[$i]['orderEnqRefNo']   = $item->orderenqrefno;
    //             $ArrFinalRes[$i]['styref']   = $item->stylenamerefno;
    //             $ArrFinalRes[$i]['pcsorset']  = !empty($item->pcs_set) ? $item->pcs_set : '-';
    //             $ArrFinalRes[$i]['bb']          = $item->brandname;
    //             $ArrFinalRes[$i]['reupd'] = $item->formattedDateUpdated;
    //             $ArrFinalRes[$i]['s'] = $ArrActiveInStatus[$item->status];
    //             $ArrFinalRes[$i]['merchant']       = $item->contactname;
    //             /*Only for Merchant. Management cannot add cad, fab and bom purchase request
    //              * */
    //             $ArrFinalRes[$i]['show'] = $item->status;
    //             //Hide cad,fab, and bom purchase request in wip list of brand/buyer if inactive
    //             $i++;
    //         }
    //         $output = array(
    //             "draw" => $_POST['draw'],
    //             "recordsTotal" => $this->managementmodel->wipCountAll(),
    //             "recordsFiltered" => $this->managementmodel->wipCountFiltered(),
    //             "data" => $ArrFinalRes,
    //         );
    //         echo json_encode($output);
    //     }
    //     else $this->load->view('management/workInProcess', array());

    // }

    public function manageWipOld() {
        $VarFrom = xssclean($this->input->post('rFrom'));
        if($VarFrom == 1) {
            $this->load->model(CNFCOMPANY . 'workinprogressmodel');
            $ArrList = $this->managementmodel->getWipDataTables();
            $i            = 0;
            $ArrFinanlRes = $ArrIds = $VarKeysForPoqty = array();
            $ArrPcsSet    = unserialize(ARRPCSSET); $ArrActiveInStatus = unserialize(ARRSTATUS);
            foreach ($ArrList as $item) {
                $ArrIds[] = $item->id;
            }
            $ArrPonumberStatus = $this->workinprogressmodel->getPonumberStatus($ArrIds,$this->companyid);
            $ArrExtraDetails = $this->workinprogressmodel->getExtraDetailsOfWip($ArrIds,$this->companyid);
            //echo '<pre>'; print_r($ArrList); die('die');
            foreach ($ArrList as $key => $item) {
                if(empty($ArrIds)) {
                    $ArrFinanlRes[$i]['pono'] = '';
                }
                else {
                    if(empty($ArrExtraDetails['pono'][$item->id])) {
                        $ArrFinanlRes[$i]['griddatas'][] = array();
                        $ArrFinanlRes[$i]['cs'][] = array();
                        $ArrFinanlRes[$i]['pono']     = '-';
                        $ArrFinanlRes[$i]['shipdate'][] = '';
                    }
                    else {
                        if (count($ArrExtraDetails['pono'][$item->id]) !== @count(array_unique($ArrExtraDetails['pono'][$item->id]))) {
                            $ArrPo       = get_keys_for_duplicate_values($ArrExtraDetails['pono'][$item->id]);
                            $ArrShipDate = get_keys_for_duplicate_values($ArrExtraDetails['shipdate'][$item->id]);
                            if (empty($ArrShipDate)) {
                                $ArrFinanlRes[$i]['shipdate'][] = '';
                            } else {
                                $ArrFinanlRes[$i]['shipdate'][] = '';
                            }
                            $ArrFinanlRes[$i]['pono'] = '-';
                            if (count($ArrPo) >= 1) {
                                foreach ($ArrPo as $key2 => $item2) {
                                    $ArrPoqToSum = 0;
                                    foreach ($item2 as $ids) {
                                        if(is_numeric($ArrExtraDetails['poq'][$item->id][$ids])) {
                                            $ArrPoqToSum += $ArrExtraDetails['poq'][$item->id][$ids];
                                        }
                                        else {
                                            $ArrPoqToSum += 0;
                                        }
                                    }
                                    $ArrFinanlRes[$i]['griddatas'][] = array('ids' => $item->id, 'pono' => $key2, 'sumpoq' => $ArrPoqToSum, 'poids' => $item2);
                                }
                            } else {
                                $ArrFinanlRes[$i]['griddatas'][] = array();
                            }
                            foreach ($ArrShipDate as $shipmentKey2 => $item2) {
                                $ArrFinanlRes[$i]['shipdate'][] = $shipmentKey2;
                            }
                            if (empty($ArrPonumberStatus)) {
                                $ArrFinanlRes[$i]['cs'][] = array();
                            } else {
                                if (empty($ArrPonumberStatus[$item->id]['commonstatus'])) {
                                    $ArrFinanlRes[$i]['cs'][] = array();
                                } else {
                                    foreach ($ArrPonumberStatus[$item->id]['commonstatus'] as $poid) {
                                        $ArrFinanlRes[$i]['cs'][] = @$ArrShipmentStatus[$poid[0]];
                                    }
                                }
                            }
                        } else {
                            $ArrP = $ArrExtraDetails['pono'][$item->id];
                            foreach ($ArrP as $keyid => $ponos) {
                                $ArrFinanlRes[$i]['griddatas'][] = array('ids'=>$item->id,'pono'=>$ponos,'sumpoq'=>$ArrExtraDetails['poq'][$item->id][$keyid],'poids'=>$keyid);
                            }
                            $ArrFinanlRes[$i]['pono']     = $ArrExtraDetails['pono'][$item->id];
                            $ArrFinanlRes[$i]['shipdate'][] = $ArrExtraDetails['shipdate'][$item->id];
                            $ArrFinanlRes[$i]['cs'][] = array();
                        }
                    }
                }
                $ArrFinanlRes[$i]['oenqrefno']       = $item->orderenqrefno;
                $ArrFinanlRes[$i]['id']       = $item->id;
                $ArrFinanlRes[$i]['isriorno'] = $item->isriorcode;
                $ArrFinanlRes[$i]['date']     = $item->formattedDateCreated;
                $ArrFinanlRes[$i]['styref']   = $item->stylenamerefno;
                $ArrFinanlRes[$i]['pcsorset']  = $ArrPcsSet[$item->pcsorset];
                $ArrFinanlRes[$i]['bb']          = $item->brandname;
                $ArrFinanlRes[$i]['merchant']       = $item->contactname;
                $ArrFinanlRes[$i]['reupd'] = $item->formattedDateUpdated;
                $ArrFinanlRes[$i]['s'] = $ArrActiveInStatus[$item->status];
                $i++;
            }
            $output = array(
                "draw" => $_POST['draw'],
                "recordsTotal" => $this->managementmodel->wipCountAll(),
                "recordsFiltered" => $this->managementmodel->wipCountFiltered(),
                "data" => $ArrFinanlRes,
            );
            echo json_encode($output);
        }
        else {
            $this->load->view('management/workInProcess', array());
        }
    }

    public function queueList() {
        $rFrom = xssclean($this->input->post('rFrom'));
        if($rFrom == 1) {
            $ArrList = $this->managementmodel->queueListDataTables();
            $data = array();
            $ArrStatus = unserialize(ARRSTATUS);
            $ArrBomRequirement = unserialize(ARRBOMREQUIREMENT);
            $QueueListDetailPageUrl = '';
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
                $row[] = $Obj->merchant;
                $row[] = $Obj->current_status;
                $row[] = $Obj->formatDateUpdated;
                $row[] = $ArrStatus[$Obj->status];
                $data[] = $row;
            }
            $output = array(
                "draw" => $_POST['draw'],
                "recordsTotal" => $this->managementmodel->queueListCountAll(),
                "recordsFiltered" => $this->managementmodel->queueListCountFiltered(),
                "data" => $data,
            );
            echo json_encode($output);
        }
        else {
            $this->load->view('management/queueList', array());
        }
    }

    public function orderEnquiryList() {
        $data['brands'] = $this->getBrandList();
        $data['ArrEnqType'] = ARRENQUIRYTYPE;
        $data['checkDraftorNot']= $this->managementmodel->getdraftdata();
        $this->load->view('management/orderEnquiryList', $data);
        
    }

    
    public function getOrderEnquiryList() 
    {
        header('Access-Control-Allow-Origin: *');
        $output = $this->managementmodel->getOrderEnquiryListt();
        echo json_encode($output);
    }

    public function iorenquirylist() {
        $data['brands'] = $this->getBrandList();
        $data['ArrEnqType'] = ARRENQUIRYTYPE;
        //$data['checkDraftorNot']= $this->merchantmodel->getdraftdata();
        $this->load->view('management/management_enquiry_iorlist', $data);
    }
    public function getEnquiryIORList() 
    {
        header('Access-Control-Allow-Origin: *');
        $output = $this->managementmodel->getEnquiryIORListt();
        echo json_encode($output);
    }
    public function isrenquirylist() {
        $data['brands'] = $this->getBrandList();
        $data['ArrEnqType'] = ARRENQUIRYTYPE;
        //ata['checkDraftorNot']= $this->merchantmodel->getdraftdata();
        $this->load->view('management/management_enquiry_isrlist', $data);
    }
    
    public function getEnquiryISRList() 
    {
        header('Access-Control-Allow-Origin: *');
        $output = $this->managementmodel->getEnquiryISRListt();
        echo json_encode($output);
    }
    

    public function getBrandList() 
    {
        header('Access-Control-Allow-Origin: *');
        $output = $this->managementmodel->getBrandListt();
        return $output;
    }

    public function searchEnquiryList() {
        header('Access-Control-Allow-Origin: *');
        $data = $this->input->post();
        $output = $this->managementmodel->searchEnquiryListt($data);
        // print_r($output);
        // die;
        echo json_encode($output);
    }
    
    public function searchEnquiryIORList() {
        header('Access-Control-Allow-Origin: *');
        $data = $this->input->post();
        $output = $this->managementmodel->searchEnquiryIORListt($data);
        echo json_encode($output);
    }
    
    public function searchEnquiryISRList() {
        header('Access-Control-Allow-Origin: *');
        $data = $this->input->post();
        $output = $this->managementmodel->searchEnquiryISRListt($data);
        echo json_encode($output);
    }

    // public function commonheaderdata($VarEnquiryId)
    // {
    //     $ArrCompanyRes       = $this->companymodel->fnGetCompanyInfo($this->companyid);
    //     $ArrEnquiryDetails   = $this->orderentrymodel->fnGetOrderEnquiryInfo($VarEnquiryId, $this->companyid);
    //     $ArrEnquiryDetails   = @$ArrEnquiryDetails[0];
    //     $VarHashEnquiryId    = $this->uri->segment(3);
    //     $ArrMerchant         = $this->commonmodel->getMerchantData($this->companyid, 1, $ArrEnquiryDetails['merchantid']);
    //     $ArrTeam             = $this->commonmodel->getTeamDetails($this->companyid, $ArrEnquiryDetails['merchantid']);
    //     $ArrCommonData       = $this->orderentrymodel->getCommonData($VarEnquiryId, $this->companyid);
    //     $ArrCommonHeaderData = array(
    //         'companyName'       => @$ArrCompanyRes[0]['companyname'], 'companyAddress'    => @$ArrCompanyRes[0]['address'],
    //         'VarEnquiryId'      => $VarEnquiryId, 'VarHashEnquiryId'  => @$VarHashEnquiryId, 'merchantName'      => @$ArrMerchant[0]['contactname'],
    //         'merchantMobile'    => @$ArrMerchant[0]['mobile'], 'merchantCode'      => @$ArrMerchant[0]['code'],
    //         'merchantEmail'     => @$ArrMerchant[0]['username'], 'ArrEnquiryDetails' => $ArrEnquiryDetails,
    //         'ArrCommonData'     => @$ArrCommonData, 'ArrTeam'           => @$ArrTeam[0]
    //     );
    //     return $ArrCommonHeaderData;
    // }

    public function commonheaderdata($VarEnquiryId)
    {
        $sizeChart    = $this->managementmodel->getSizeChart($VarEnquiryId);
        $sizeMaster   = $this->managementmodel->getSizeMaster($sizeChart);

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
        $ArrCommonHeaderData = array(
            'companyName'       => @$ArrCompanyRes[0]['companyname'], 'companyAddress'    => @$ArrCompanyRes[0]['address'],
            'VarEnquiryId'      => $VarEnquiryId, 'VarHashEnquiryId'  => @$VarHashEnquiryId, 'merchantName'      => @$ArrMerchant[0]['contactname'],
            'merchantMobile'    => @$ArrMerchant[0]['mobile'], 'merchantCode'      => @$ArrMerchant[0]['code'],
            'merchantEmail'     => @$ArrMerchant[0]['username'], 'ArrEnquiryDetails' => $ArrEnquiryDetails,
            'ArrCommonData'     => @$ArrCommonData, 'ArrTeam' => @$ArrTeam[0], 'sizeValue' => $sizeValue
        );
        return $ArrCommonHeaderData;
    }

    public function getAllMgmtPIApprovalList() {
        $data = $this->managementmodel->getAllMgmtPIApprovalListt();
        echo json_encode($data);
    }

    public function allmgmtpiapproval() {
         $data['brands'] = $this->getBrandList();
        $this->load->view('management/allmgmtpiapproval',$data);
    }

    public function getFabricMgmtPIApprovalList() {
        $data = $this->managementmodel->getFabricMgmtPIApprovalListt();
        echo json_encode($data);
    }

    public function fabricmgmtpiapproval() {
        $this->load->view('management/fabricmgmtpiapproval');
    }

    public function getStationeryMgmtPIApprovalList() {
        $data = $this->managementmodel->getStationeryMgmtPIApprovalListt();
        echo json_encode($data);
    }

    public function stationerypiapproval() {
        $this->load->view('management/stationerypiapproval');
    }

    public function getAllManagementPIList() {
        $data = $this->managementmodel->getAllManagementPIListt();
        echo json_encode($data);
    }

    public function allmanagamentpurchaseindent() {
            $data['brands'] = $this->getBrandList();
        $this->load->view('management/all_mgmt_pi_list',$data);
    }

    public function getYarnManagementPIList() {
        $data = $this->managementmodel->getYarnManagementPIListt();
        echo json_encode($data);
    }

    public function yarnmanagamentpurchaseindent() {
         $data['brands'] = $this->getBrandList();
        $this->load->view('management/yarn_mgmt_pi_list',$data);
    }

    public function getFabricManagementPIList() {
        $data = $this->managementmodel->getFabricManagementPIListt();
        echo json_encode($data);
    }

    public function fabricmanagamentpurchaseindent() {
        $this->load->view('management/fabric_mgmt_pi_list');
    }

    public function getStationeryManagementPIList() {
        $data = $this->managementmodel->getStationeryManagementPIListt();
        echo json_encode($data);
    }

    public function stationerymanagamentpurchaseindent() {
        $this->load->view('management/stationery_mgmt_pi_list');
    }

    // ********** CAD STARTS HERE *********** /

    // public function cad() {
    //     $this->load->view('request/cad/mgmt_auth_list');
    // }

    public function cad() {
        $data['type'] = 'CAD';
        $data['title'] = 'CAD AURHORIZATION LIST';
        $data['brands'] = $this->getBrandList();
        $this->load->view('management/common_list',$data);
    }

    public function getCADList() 
    {
        header('Access-Control-Allow-Origin: *');
        $output = $this->managementmodel->getCADListt();
        echo json_encode($output);
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
        $subcompany_data = $this->managementmodel->getsubscribercompanydetail($subid);
        $requestData = $this->managementmodel->getRequestData($VarEnqId, $reqId);
        $this->load->view('request/cad/mgmt_cad_details', 
                     array('VarEnqId'=>$VarEnqId, 'pending_status' => 1, 'reqId' => $reqId, 'ArrCommonHeaderData' => $ArrCommonHeaderData,
                        'requestData' => $requestData, "subcompany_data" => $subcompany_data));
    }
    
    public function getrequestDetails() {
        $enqId = xssclean($this->input->post('enquiry_id'));
        $reqId = xssclean($this->input->post('reqId'));
        $data = $this->managementmodel->getrequestDetailss($enqId, $reqId);
        echo json_encode($data);
    }

    public function updateCadAuthorization() {
        header('Access-Control-Allow-Origin: *');
        $data = $this->input->post();
        $output = $this->managementmodel->updateCadAuthorizationn($data);
        echo json_encode($output);
    }

    // ********** CAD ENDS HERE *********** /

    // ********** SAMPLE STARTS HERE *********** /

    // public function sample() {
    //     $this->load->view('request/sample/mgmt_auth_list');
    // }

    public function sample() {
        $data['type'] = 'SAMPLE';
        $data['title'] = 'SAMPLE AURHORIZATION LIST';
        $data['brands'] = $this->getBrandList();
        $this->load->view('management/common_list',$data);
    }

    public function getSampleList() 
    {
        header('Access-Control-Allow-Origin: *');
        $output = $this->managementmodel->getSampleListt();
        echo json_encode($output);
    }

    // public function samplerequestlist() {
    //     $VarId = $this->uri->segment(3);
    //     $reqId = $this->uri->segment(5);
    //     if ($VarId <> '' && is_numeric(base64_decode(urldecode($VarId))))
    //     {
    //         $VarEnqId       = base64_decode(urldecode($VarId));
    //     }

    //     if ($reqId <> '' && is_numeric(base64_decode(urldecode($reqId))))
    //     {
    //         $reqId       = base64_decode(urldecode($reqId));
    //     }
    //     $ArrCommonHeaderData = $this->commonheaderdata($VarEnqId);
    //     $this->load->view('request/cad/mgmt_cad_details', 
    //                  array('VarEnqId'=>$VarEnqId, 'pending_status' => 1, 'reqId' => $reqId, 'ArrCommonHeaderData' => $ArrCommonHeaderData));
    // }

    // public function updateSampleAuthorization() {
    //     header('Access-Control-Allow-Origin: *');
    //     $data = $this->input->post();
    //     $output = $this->managementmodel->updateCadAuthorizationn($data);
    //     echo json_encode($output);
    // }

    // ********** SAMPLE ENDS HERE *********** /

    // public function bom() {
    //     $this->load->view('request/bom/mgmt_auth_list');
    // }

    public function bom() {
        $data['type'] = 'BOM1';
        $data['title'] = 'BOM (A1) AURHORIZATION LIST';
        $this->load->view('management/common_list',$data);
    }

    public function getBOMList() 
    {
        header('Access-Control-Allow-Origin: *');
        $output = $this->managementmodel->getBOMListt();
        echo json_encode($output);
    }

     public function bom2() {
        $data['type'] = 'BOM2';
        $data['title'] = 'BOM (A2) AURHORIZATION LIST';
        $this->load->view('management/common_list',$data);
    }

    public function getBOM2List() 
    {
        header('Access-Control-Allow-Origin: *');
        $output = $this->managementmodel->getBOM2Listt();
        echo json_encode($output);
    }

    public function bomrequestlist() {
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
        $subcompany_data = $this->managementmodel->getsubscribercompanydetail($subid);
        $requestData = $this->managementmodel->getRequestDataa($VarEnqId, $reqId);
        $bomtype="BOM(ART -1) ";
        $this->load->view('request/bom/managementbomrequest', 
                     array('VarEnqId'=>$VarEnqId, 'pending_status' => 1, 'reqId' => $reqId, 'bomtype' => $bomtype,'ArrCommonHeaderData' => $ArrCommonHeaderData,
                    "requestData" => $requestData, "subcompany_data" => $subcompany_data));
    }

     public function bomr2equestlist() {
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
        $subcompany_data = $this->managementmodel->getsubscribercompanydetail($subid);
        $requestData = $this->managementmodel->getRequestDataa($VarEnqId, $reqId);
         $bomtype="BOM(ART -2) ";
        $this->load->view('request/bom/managementbomrequest', 
                     array('VarEnqId'=>$VarEnqId, 'pending_status' => 1,  'subcompany_data' => $subcompany_data,
'reqId' => $reqId,'bomtype' => $bomtype, 'ArrCommonHeaderData' => $ArrCommonHeaderData,
                    "requestData" => $requestData ));
    }

    public function updateBomAuthorization() {
        header('Access-Control-Allow-Origin: *');
        $data = $this->input->post();
        $output = $this->managementmodel->updateBomAuthorizationn($data);
        echo json_encode($output);
    }

    // WIP LIST 

    public function getWIPList() 
    {
        header('Access-Control-Allow-Origin: *');
        $output = $this->managementmodel->getWIPListt();
        echo json_encode($output);
         //echo json_encode($output);
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


    public function manageWip() {
        $data['brands'] = $this->getBrandList();
        $this->load->view('management/workinProcess', $data);
    }

    public function manageIOR() {
        $data['brands'] = $this->getBrandList();
        $this->load->view('management/iorlist', $data);
    }

    public function manageISR() {
        $data['brands'] = $this->getBrandList();
        $this->load->view('management/isrlist', $data);
    }
    
    public function getISRList() 
    {
        header('Access-Control-Allow-Origin: *');
        $output = $this->managementmodel->getISRListt();
        echo json_encode($output);
    }

    public function searchWIPList() {
        header('Access-Control-Allow-Origin: *');
        $data = $this->input->post();
        $output = $this->managementmodel->searchWIPListt($data);
        echo json_encode($output);
    }
    public function searchWIPList2() {
        header('Access-Control-Allow-Origin: *');
        $data = $this->input->post();
        $output = $this->managementmodel->search2wiplistt($data);
        echo json_encode($output);
    }


    public function getIORList() 
    {
        header('Access-Control-Allow-Origin: *');
        $output = $this->managementmodel->getIORListt();
        echo json_encode($output);
    }

    // public function bom2() {
    //     $data['type'] = 'BOM2';
    //     $data['title'] = 'BOM (A2) AURHORIZATION LIST';
    //     $this->load->view('management/common_list',$data);
    // }

    // public function getBOM2List() 
    // {
    //     header('Access-Control-Allow-Origin: *');
    //     $output = $this->managementmodel->getBOM2Listt();
    //     echo json_encode($output);
    // }

    public function embellishment() {
        $data['type'] = 'EMBELLISHMENT';
        $data['title'] = 'EMBELLISHMENT AURHORIZATION LIST';
        $this->load->view('management/common_list',$data);
    }

    public function getembellishmentList() 
    {
        header('Access-Control-Allow-Origin: *');
        $output = $this->managementmodel->getBOMListt();
        echo json_encode($output);
    }

    public function fabric() {
        $data['type'] = 'FABRIC';
        $data['title'] = 'FABRIC AURHORIZATION LIST';
        $this->load->view('management/common_list',$data);
    }

    public function getfabricList() 
    {
        header('Access-Control-Allow-Origin: *');
        $output = $this->managementmodel->getBOMListt();
        echo json_encode($output);
    }

    public function production() {
        $data['type'] = 'PRODUCTION';
        $data['title'] = 'PRODUCTION AURHORIZATION LIST';
        $this->load->view('management/common_list',$data);
    }

    public function getproductionList() 
    {
        header('Access-Control-Allow-Origin: *');
        $output = $this->managementmodel->getBOMListt();
        echo json_encode($output);
    }

    public function vessel() {
        $data['type'] = 'VESSEL';
        $data['title'] = 'VESSEL AURHORIZATION LIST';
        $this->load->view('management/common_list',$data);
    }

    public function getvesselList() 
    {
        header('Access-Control-Allow-Origin: *');
        $output = $this->managementmodel->getBOMListt();
        echo json_encode($output);
    }

    public function stationery() {
        $data['type'] = 'STATIONERY';
        $data['title'] = 'STATIONERY AURHORIZATION LIST';
        $this->load->view('management/common_list',$data);
    }

    public function getstationeryList() 
    {
        header('Access-Control-Allow-Origin: *');
        $output = $this->managementmodel->getBOMListt();
        echo json_encode($output);
    }   

}