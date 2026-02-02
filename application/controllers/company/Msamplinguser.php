<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Msamplinguser extends CI_Controller {
    public function __construct() {
        parent::__construct();
        fnIfCheckUserLoggedIn();
        $this->load->helper('xssclean');
        $this->load->model('commonmodel');
        $this->load->model('commonusermodel');
        $this->load->model(CNFCOMPANY . 'orderentrymodel');
        $this->load->model(CNFCOMPANY . 'mcadrequestmodel');
        $this->load->model(CNFCOMPANY . 'msamplerequestmodel');
        $ArrUserLoggedInfo = fnGetUserLoggedInfo('1');
        $this->companyid = $ArrUserLoggedInfo['companyid'];
        $this->userid = $ArrUserLoggedInfo['id'];
        $this->subscriberid     = $ArrUserLoggedInfo['subscriber_id'];
        $this->mysqldatetime = date('Y-m-d H:i:s');
        $this->limit = LIMITPERPAGE;
        $this->ArrDbCols = array('contactname', 'desgn', 'username', 'mobile', 'status', 'updatedby', 'dateupdated');
        $this->usertype = getUserTypeId("Sampling Dept.");
    }
    public function addedit() {
        $VarRemainingUser = $this->commonmodel->remaininguseravailable($this->companyid,1);
        if($VarRemainingUser == 0) {
            die('User Limit Ended. Can\'t add more users');
        }
        $ArrData												= array('ArrBasicInfo'=>array(),'VarId'=>'');
        $VarId = base64_decode(urldecode($this->uri->segment(4)));
        $VarDesgn = $this->commonmodel->getUserDesignation($this->usertype, '', 1);
        $ArrData['ArrDesgn'] = $VarDesgn;
        $ArrData['Edit'] = $this->uri->segment(5);
        $ArrData['ArrStatus'] = unserialize(ARRSTATUS);
        if(is_numeric($VarId)) {
            $ArrArrResults     									= $this->commonusermodel->fnGetInfo('','',$VarId);
            $ArrResults = $ArrArrResults[0];
            $VarDesignationId = $ArrResults['desgnid'];
            $ArrObjDesgn =$this->commonmodel->getUserDesignation('',$VarDesignationId);
            $ArrData['VarDesignation']	    				    = $ArrObjDesgn[0]['desgn'];
            $ArrData['ArrBasicInfo']	    				    = $ArrResults;
            $ArrData['VarId']					                = $ArrResults['id'];
        } else {
        }
        $this->load->view('sampling/addedituser', $ArrData);
    }
    public function updateUser() {
        $ArrUpdateData                  = array();
        $ArrUpdateData['id']            = xssclean($this->input->post('id'));
        $ArrUpdateData['username']      = xssclean($this->input->post('e'));
        $ArrUpdateData['contactname']   = xssclean($this->input->post('n'));
        $ArrUpdateData['mobile']        = xssclean($this->input->post('m'));
        $ArrUpdateData['companyid']     = $this->companyid;
        $ArrUpdateData['dateupdated']   = $this->mysqldatetime;
        $ArrUpdateData['updatedby']     = $this->userid;
        $ArrUpdateData['status']        = xssclean($this->input->post('s'));
        $ArrUpdateData['desgnid'] = xssclean($this->input->post('did'));
        $ArrUpdateData['password']      = COMMONPWD;
        $ArrUpdateData['usertype']      = $this->usertype;
        $ArrUpdateData['profilepermission'] = $this->usertype;
        if($ArrUpdateData['username']<>'') {
            if($ArrUpdateData['id']=='' || $ArrUpdateData['id']==0) {
                $ArrUpdateData['datecreated']                   = $this->mysqldatetime;
            }
            $ArrResult                                          = $this->commonusermodel->saveUser($ArrUpdateData);
        } else {
            $ArrResult['errcode']							    = -1;
            $ArrResult['msg']								    = 'Invalid Input!';
        }
        echo json_encode($ArrResult);
    }
    public function manage() {
        $VarFrom         = xssclean($this->input->post('rfrom'));
        $VarName = xssclean($this->input->post('n'));
        $VarMobile = xssclean($this->input->post('m'));
        $VarDesgnId = xssclean($this->input->post('d'));
        $VarStatus = xssclean($this->input->post('s'));
        $clickedColumnId = xssclean($this->input->post('columnId'));
        $VarSortOrder    = xssclean($this->input->post('sortorder'));
        if ($VarFrom == 1) {
            $VarURLSegment = 4;
            $this->load->library('pagination');
            $config['base_url']    = base_url().CNFCOMPANY.'msamplinguser/manage/';
            $config['total_rows']  = $this->commonusermodel->fnCount($VarName, $VarMobile,$VarDesgnId,$VarStatus,$this->usertype,$this->companyid);
            $config['per_page']    = $this->limit;
            $config['uri_segment'] = $VarURLSegment;
            $VarOffset                = $this->uri->segment($VarURLSegment);
            $this->pagination->initialize($config);
            $ArrDbCols = $this->ArrDbCols;
            if(empty($VarSortOrder)) $VarSortOrder = 'desc';
            if (array_key_exists($clickedColumnId, $ArrDbCols)) $VarSortBy = $ArrDbCols[$clickedColumnId]; else $VarSortBy = '';
            $ArrList         = $this->commonusermodel->fnList($VarName, $VarMobile,$VarDesgnId,$VarStatus, $this->usertype,$this->companyid,
                $this->limit, $VarOffset, $VarSortBy, $VarSortOrder);
            $data['pagination'] = $this->pagination->create_linkswithajax('SamUser');
            $i                  = 0;
            $ArrFnlList         = array();
            $ArrStatus          = unserialize(ARRSTATUS);
            foreach ($ArrList['listData'] as $Obj) {
                $ArrFnlList[$i]['id'] = $Obj->id;
                $ArrFnlList[$i]['n']  = $Obj->contactname;
                $ArrFnlList[$i]['e']  = $Obj->username;
                $ArrFnlList[$i]['ds']  = $Obj->desgn;
                $ArrFnlList[$i]['m']  = $Obj->mobile;
                $ArrFnlList[$i]['ub'] = $ArrList['updatedByData'][$Obj->updatedby];
                $ArrFnlList[$i]['s']  = $ArrStatus[$Obj->status];
                $ArrFnlList[$i]['du'] = date('d-m-Y H:i:s', strtotime($Obj->dateupdated));
                $i                    = $i + 1;
            }
            echo json_encode(array('errcode' => '1', 'cn' => $config['total_rows'], 'ct' => $i, 're' => $ArrFnlList, 'pa' => base64_encode($data['pagination'])));
            unset($ArrFnlList);
            die;
        } else {
            $ArrDesignation = $this->commonmodel->getUserDesignation($this->usertype, '', 1);
            $this->load->view('sampling/manageusers',array('ArrDesignation' => $ArrDesignation,
                'ArrStatus' => unserialize(ARRSTATUS)));
        }
    }

    public function changeStatus() {
        $VarStatus = xssclean($this->input->post('actDeact'));
        $VarCid = xssclean($this->input->post('cid'));
        if ($VarStatus <> '' && $VarCid <> '') {
            $ArrIds = json_decode($VarCid, true);
            $ArrResult = $this->commonusermodel->changeStatus($ArrIds, $VarStatus);
            echo json_encode($ArrResult);
        }
    }
    /****************************** USER MODEULE ENDS *****************************/
    public function index() {
        $this->load->view('sampling/userdashboard');
    }

    public function samplereceivedlist() {
        $VarFrom = xssclean($this->input->post('rfrom'));
        if($VarFrom == 1) {
            $ArrList = $this->msamplerequestmodel->recdlistDatatables();
            $data = array();
            $ArrStatus = unserialize(ARRSTATUS);
            foreach ($ArrList as $Obj) {
                $row = array();
                $row[] = '<input type="checkbox" class="allcbox" id="'.$Obj->allid.'">';
                $row[] = '<a href="'.base_url(CNFCOMPANY.'msamplinguser/queuenoassign').'/'.urlencode(base64_encode($Obj->allid)).'">'.$Obj->isriorcode.'</a>';
                $row[] = $Obj->brandname;
                $gridData = json_decode($Obj->jsondatagrid);
                $row[] = $gridData[0][5];
                $row[] = $Obj->requesttype;
                $row[] = $Obj->formattedDateCreated;
                $row[] = $Obj->formattedCutOffDt;
                $row[] = $Obj->approvaltype;
                $row[] = $Obj->mgmt;
                $row[] = $Obj->merchant;
                $row[] = '<a href="#">Current Status</a>';
                $row[] = $Obj->formattedDateUpdated;
                $row[] = $ArrStatus[$Obj->status];
                $data[] = $row;
            }
            $output = array(
                "draw" => $_POST['draw'],
                "recordsTotal" => $this->msamplerequestmodel->count_RecdList(),
                "recordsFiltered" => $this->msamplerequestmodel->count_recdListFiltered(),
                "data" => $data,
            );
            echo json_encode($output);

        }
        else {
            $data['brands'] = $this->getBrandList();
             $this->load->view('sampling/reqreceivedlist', $data);
            //$this->load->view('sampling/reqreceivedlist', array());
        }
    }
public function getBrandList() 
    {
        header('Access-Control-Allow-Origin: *');
        $output = $this->msamplerequestmodel->getBrandListt();
        return $output;
    }
    public function samplequeuelist() {
        $VarFrom         = xssclean($this->input->post('rfrom'));
        if($VarFrom == 1) {
            $ArrList = $this->msamplerequestmodel->sampleQueueListDataTables();
            $data = array();
            $ArrStatus = unserialize(ARRSTATUS);
            foreach ($ArrList as $Obj) {
                $row = array();
                $row[] = '<input type="checkbox" class="allcbox" id="'.$Obj->id.'">';
                $row[] = $Obj->isriorcode;
                $row[] = $Obj->brandname;
                $row[] = '<a href="'.base_url(CNFCOMPANY.'msamplinguser/queuelistdetail').'/'.urlencode(base64_encode($Obj->id)).'">'.$Obj->queueno.'</a>';
                $gridData = json_decode($Obj->jsondatagrid);
                $row[] = @$gridData[0][5];
                $row[] = $Obj->formattedDateCreated;
                $row[] = $Obj->formattedCutOffDt;
                $row[] = $Obj->approvaltype;
                $row[] = $Obj->mgmt;
                $row[] = $Obj->merchant;
                $row[] = '<a href="#">Current Status</a>';
                $row[] = $Obj->formattedDateUpdated;
                $row[] = $ArrStatus[$Obj->status];
                $data[] = $row;
            }
            $output = array(
                "draw" => $_POST['draw'],
                "recordsTotal" => $this->msamplerequestmodel->countAllSampleQueueList(),
                "recordsFiltered" => $this->msamplerequestmodel->countSampleQueueListFiltered(),
                "data" => $data,
            );
            echo json_encode($output);
        } else {
             $data['brands'] = $this->getBrandList();
              //$this->load->view('sampling/samplequeuelist', array());
            $this->load->view('sampling/samplequeuelist', $data);
        }
    }

    public function updateSamQueueStatusnPIN() {
        $VarPwd = xssclean($this->input->post('i'));
        if ($this->commonmodel->fnValidatePin($this->userid, $VarPwd)) {
            $VarId               = xssclean($this->input->post('srid'));
            $orderId               = xssclean($this->input->post('oId'));
            $VarRemarks           = xssclean($this->input->post('rem'));
            $jsonSampleRequestMisc           = xssclean($this->input->post('sampleReqMisc'));
            //$VarStatus           = xssclean($this->input->post('comstatus'));
            $ArrUpdate = array(
                'dateupdated' => $this->mysqldatetime,
                'deptremarks'=>$VarRemarks
            );

            $this->msamplerequestmodel->sampleRequestMisc($jsonSampleRequestMisc,$VarId,$orderId);

            if($this->msamplerequestmodel->saveAuthoriseSamRequest($ArrUpdate,$VarId))
                echo json_encode(array('errcode' => 1,'msg'=>'Saved','dt'=>date('d-m-Y H:i:s')));
        }
        else {
            echo json_encode(array('errcode' => '-1','msg' => 'Invalid PIN','dt'=>date('d-m-Y H:i:s')));
        }
    }

    public function queuenoassign() {
        $this->load->model(CNFCOMPANY.'msamplerequestmodel');
        $VarId              = base64_decode(urldecode($this->uri->segment(4)));
        $HashedCadRequestId = $this->uri->segment(4);
        $ArrObjRequestData       = $this->msamplerequestmodel->getSamRequestData($VarId, $this->companyid);
        $ArrRequestData = $ArrObjRequestData[0];
        $VarOrderId           = $ArrRequestData->orderid;
        $ArrData['ArrBasicInfo'] = $ArrRequestData;
        $ArrOrderDatas           = $this->orderentrymodel->getOrderDataByWip($VarOrderId, $this->companyid);
        $cadIndentDetails = $this->commonmodel->fnGetAllTableInfo(KN_SAMPLEREQUESTINDENTDETAILS,'issuedtodept',
            array('requestid'=>$VarId,'indenttype'=>1));
        $fabIndentDetails = $this->commonmodel->fnGetAllTableInfo(KN_SAMPLEREQUESTINDENTDETAILS,'issuedtodept',
            array('requestid'=>$VarId,'indenttype'=>2));
        $bomIndentDetails = $this->commonmodel->fnGetAllTableInfo(KN_SAMPLEREQUESTINDENTDETAILS,'issuedtodept',
            array('requestid'=>$VarId,'indenttype'=>3));
        if (empty($ArrOrderDatas)) {
            die('Order Entry not completed');
        } else {
            $VarCommonOrderEntryInfo = $this->commonmodel->commonBasicInfoOrderEntry($VarOrderId, $this->companyid);
            $ArrOrderEnqData = $this->orderentrymodel->fnGetOrderEnquiryInfo($VarOrderId, $this->companyid, '');
            $ArrData['VarReqId'] = $VarId;
            //$ArrData['VarCommonOrderEntryInfo'] =
            $ArrData['ArrCompanyInfo'] = $VarCommonOrderEntryInfo['ArrCompanyInfo'];
            $ArrData['ArrMerchant'] = $VarCommonOrderEntryInfo['ArrMerchant'];
            $ArrData['ArrTeamInfo'] = $VarCommonOrderEntryInfo['ArrTeamInfo'];
            $ArrData['ArrOrderEnqData'] = $ArrOrderEnqData[0];
            $ArrData['brandName'] = $VarCommonOrderEntryInfo['brandName'];
            $ArrData['buyerName'] = $VarCommonOrderEntryInfo['buyerName'];
            $ArrData['ArrOrderCommonData'] = $VarCommonOrderEntryInfo['ArrOrderCommonData'];
            $ArrPoNumber         = $this->orderentrymodel->getAllPoNumber($VarOrderId,$this->companyid);
            $ArrPrevSampleRefNo = $this->commonmodel->getPrevRefNo(2,$this->companyid,$VarOrderId);
            $ArrObjPurpose                     = $this->mcadrequestmodel->getPurpose();
            foreach ($ArrObjPurpose as $purpose) {
                $ArrPurpose[] = $purpose->purpose;
            }
            $ArrData['ArrRequirement']      = json_encode(array_values(unserialize(ARRSAMPLEREQREQUIREMENT)));

            $ArrData['VarId']               = $VarId;
            //$ArrData['oedata']              = $this->mcadrequestmodel->getOrderEntryDataFromFifthTbl($VarOrderId,$this->companyid);
            $ArrData['HashedCadRequestId']  = $HashedCadRequestId;

            $ArrData['ArrAllUsertypes']     = unserialize(ARRUSERTYPE);
            $ArrData['jsonUnitMeasure'] = json_encode(array_values(unserialize(ARRUNITOFMEASURE)));
            $ArrData['VarOrderId']      = $VarOrderId;
            $ArrData['AuthorizedByInfo']  = $this->commonmodel->getUserInfo($ArrRequestData->mgmtid);
            $ArrData['cadIndentDetails'] = $cadIndentDetails;
            $ArrData['fabIndentDetails'] = $fabIndentDetails;
            $ArrData['bomIndentDetails'] = $bomIndentDetails;
        }

        $this->load->view('sampling/queuenoassign', $ArrData);
    }

    public function queuelistdetail() {
        $this->load->model(CNFCOMPANY.'msamplerequestmodel');
        $VarId              = base64_decode(urldecode($this->uri->segment(4)));
        $HashedCadRequestId = $this->uri->segment(4);
        $ArrObjRequestData       = $this->msamplerequestmodel->getSamRequestData($VarId, $this->companyid);
        //echo '<pre>'; print_r($ArrObjRequestData); die('die');
        $ArrRequestData = $ArrObjRequestData[0];
        $VarOrderId           = $ArrRequestData->orderid;
        $ArrData['ArrBasicInfo'] = $ArrRequestData;
        $ArrOrderDatas           = $this->orderentrymodel->getOrderDataByWip($VarOrderId, $this->companyid);
        if (empty($ArrOrderDatas)) {
            die('Order Entry not completed');
        } else {
            $VarCommonOrderEntryInfo = $this->commonmodel->commonBasicInfoOrderEntry($VarOrderId, $this->companyid);
            $ArrOrderEnqData = $this->orderentrymodel->fnGetOrderEnquiryInfo($VarOrderId, $this->companyid, '');
            $ArrData['VarReqId'] = $VarId;
            $ArrData['ArrCompanyInfo'] = $VarCommonOrderEntryInfo['ArrCompanyInfo'];
            $ArrData['ArrMerchant'] = $VarCommonOrderEntryInfo['ArrMerchant'];
            $ArrData['ArrTeamInfo'] = $VarCommonOrderEntryInfo['ArrTeamInfo'];
            $ArrData['ArrOrderEnqData'] = $ArrOrderEnqData[0];
            $ArrData['brandName'] = $VarCommonOrderEntryInfo['brandName'];
            $ArrData['buyerName'] = $VarCommonOrderEntryInfo['buyerName'];
            $ArrData['ArrOrderCommonData'] = $VarCommonOrderEntryInfo['ArrOrderCommonData'];
            $ArrData['VarId']               = $VarId;
            $ArrData['VarOrderId']               = $VarOrderId;
            $ArrData['HashedCadRequestId']  = $HashedCadRequestId;
            $ArrData['ArrAllUsertypes']     = unserialize(ARRUSERTYPE);
            $ArrData['jsonUnitMeasure'] = json_encode(array_values(unserialize(ARRUNITOFMEASURE)));
            $ArrData['AuthorizedByInfo']  = $this->commonmodel->getUserInfo($ArrRequestData->mgmtid);
        }
        $this->load->view('sampling/samplequeuelistdetail', $ArrData);

    }

    public function updateIndentGridInfoinReceiver() {
        $VarRfrom = xssclean($this->input->post('rfrom'));
        if($VarRfrom == 1) {
            $VarCadRequestId = xssclean($this->input->post('crid'));
            $cadgridData = xssclean($this->input->post('cadgridData'));
            $gridFabIndent = xssclean($this->input->post('gridFabIndent'));
            $gridBomIndent = xssclean($this->input->post('gridBomIndent'));
            $cadMatIssuedBy = xssclean($this->input->post('cadMatIssuedBy'));
            $fabMatIssuedBy = xssclean($this->input->post('fabMatIssuedBy'));
            $bomMatIssuedBy = xssclean($this->input->post('bomMatIssuedBy'));
            if(!empty($cadgridData)) {
                $ArrCadIndentData = array('requestid' => $VarCadRequestId, 'cadindentgrid' => $cadgridData, 'indentrefno'=>$VarIndentRefNo,'matissuedby'=>$cadMatIssuedBy);
                $this->commonmodel->saveCadIndentDetails($ArrCadIndentData);
            }
            if(!empty($gridFabIndent)) {
                $ArrFabIndentData = array('requestid' => $VarCadRequestId, 'fabindentgrid' => $gridFabIndent, 'currentstatus' => '2', 'recentupdates' => $this->mysqldatetime,
                    'matissuedby'=>$fabMatIssuedBy);
                $this->commonmodel->saveFabIndentDetails($ArrFabIndentData);
            }
            if(!empty($gridBomIndent)) {
                $ArrBomIndentData = array('requestid' => $VarCadRequestId, 'bomindentgrid' => $gridBomIndent,'currentstatus' => '2', 'recentupdates' => $this->mysqldatetime,
                    'matissuedby'=>$bomMatIssuedBy);
                $this->commonmodel->saveBomIndentDetails($ArrBomIndentData);
            }
            echo json_encode(array('errcode'=>'1'));
            die;
        }
        else {
            echo json_encode(array('errcode'=>'-1'));
        }
    }

    public function fnCheckPinForSampleQueueNo() {
        $VarPwd               = xssclean($this->input->post('i'));
        $VarIsrIorCode        = xssclean($this->input->post('isriorcode'));
        $VarSamRequestId      = xssclean($this->input->post('srid'));
        $VarOrderId      = xssclean($this->input->post('oid'));
        $VarApproveReject     = xssclean($this->input->post('s'));
        $VarRemarks           = xssclean($this->input->post('rem'));
        $samStatusRefNoMisc           = xssclean($this->input->post('samStatusRefNoJxl'));
        $VarRequestListType = "SAMPLE";
        $VarQueueNoRes = $this->commonmodel->getAllQueueNo($VarRequestListType, $this->companyid);
        if(empty($VarQueueNoRes->qid)) {
            $Qno = 1;
        }
        else {
            $Qno = $VarQueueNoRes->qid + 1;
        }
        if ($this->commonmodel->fnValidatePin($this->userid, $VarPwd)) {
            $VarQno = $VarIsrIorCode.'/'.SAMQNO_PREFIX.$Qno;
            if ($VarApproveReject == 2) {
                $VarMsg = 'Approved';
            }
            elseif ($VarApproveReject == 3) {
                $VarMsg = 'Rejected';
            }
            $ArrValues = array(
                'alldeptid' => $this->userid,
                'queueno' => $VarQno,
                'qid'=>$Qno,
                'queueno_assigned_date' => date('Y-m-d H:i:s'),
                'deptremarks' => $VarRemarks,
                'deptcurrentstatus' => $VarApproveReject,
                'dateupdated' => date('Y-m-d H:i:s')
            );
            $this->msamplerequestmodel->sampleRequestMisc($samStatusRefNoMisc,$VarSamRequestId,$VarOrderId);
            $Res = $this->msamplerequestmodel->saveAuthoriseSamRequest($ArrValues,$VarSamRequestId);
            if ($Res) {
                $ArrLoggedInUserInfo = fnGetUserLoggedInfo('1');
                echo json_encode(array('errcode' => '1', 'qno' => $VarQno, 'adt' => date('d-m-Y H:i:s'), 'msg' => $VarMsg,
                    'matIssuedByName' => $ArrLoggedInUserInfo['name'], 'ru' => date('d-m-Y H:i:s'),'id'=>$VarSamRequestId));
            }
            else {
                echo json_encode(array('errcode' => '-1', 'qno' => 0, 'adt' => 0, 'msg' => $VarMsg,'id'=>$VarSamRequestId));
            }
        }
        else {
            echo json_encode(array('errcode' => '-1', 'qno' => '0', 'adt' => '0', 'msg' => 'Invalid PIN','id'=>''));
        }
    }

    public function getReqRequestList() {
        header('Access-Control-Allow-Origin: *');
        $output = $this->msamplerequestmodel->getReqRequestListt();
        echo json_encode($output);
    }

    public function getQueueList() {
        header('Access-Control-Allow-Origin: *');
        $output = $this->msamplerequestmodel->getQueueListt();
        echo json_encode($output);
    }

    public function samplesentlist() {
        $VarFrom = xssclean($this->input->post('rfrom'));
        if($VarFrom == 1) {
            $ArrList = $this->msamplerequestmodel->recdlistDatatables();
            $data = array();
            $ArrStatus = unserialize(ARRSTATUS);
            foreach ($ArrList as $Obj) {
                $row = array();
                $row[] = '<input type="checkbox" class="allcbox" id="'.$Obj->allid.'">';
                $row[] = '<a href="'.base_url(CNFCOMPANY.'msamplinguser/queuenoassign').'/'.urlencode(base64_encode($Obj->allid)).'">'.$Obj->isriorcode.'</a>';
                $row[] = $Obj->brandname;
                $gridData = json_decode($Obj->jsondatagrid);
                $row[] = $gridData[0][5];
                $row[] = $Obj->requesttype;
                $row[] = $Obj->formattedDateCreated;
                $row[] = $Obj->formattedCutOffDt;
                $row[] = $Obj->approvaltype;
                $row[] = $Obj->mgmt;
                $row[] = $Obj->merchant;
                $row[] = '<a href="#">Current Status</a>';
                $row[] = $Obj->formattedDateUpdated;
                $row[] = $ArrStatus[$Obj->status];
                $data[] = $row;
            }
            $output = array(
                "draw" => $_POST['draw'],
                "recordsTotal" => $this->msamplerequestmodel->count_RecdList(),
                "recordsFiltered" => $this->msamplerequestmodel->count_recdListFiltered(),
                "data" => $data,
            );
            echo json_encode($output);

        }
        else {
             $data['brands'] = $this->getBrandList();
            $this->load->view('sampling/reqsentlist', $data);
        }
    }     

    public function getReqSentList() {
        header('Access-Control-Allow-Origin: *');
        $output = $this->msamplerequestmodel->getReqSentListt();
        echo json_encode($output);
    }

    public function garmentissuedlist() {
        $data['brands'] = $this->getBrandList();
        $this->load->view('sampling/garmentissuedlist', $data);
    }

    public function getGarmentIssuedList() {
        header('Access-Control-Allow-Origin: *');
        $output = $this->msamplerequestmodel->getGarmentIssuedListt();
        echo json_encode($output);
    }

    public function midclist() {
         $data['brands'] = $this->getBrandList();
        $this->load->view('sampling/midclist', $data);
    }
    
    public function getMIDCList() {
        header('Access-Control-Allow-Origin: *');
        $output = $this->msamplerequestmodel->getMIDCListt();
        echo json_encode($output);
    }

    public function micaddclist() {
          $data['brands'] = $this->getBrandList();
        $this->load->view('sampling/micaddclist', $data);
    }
    
    public function getMICAddCList() {
        header('Access-Control-Allow-Origin: *');
        $output = $this->msamplerequestmodel->getMICAddCListt();
        echo json_encode($output);
    }

     public function mibomdclist() {
         $data['brands'] = $this->getBrandList();
        $this->load->view('sampling/mibomdclist', $data);
    }
    
    public function getMIBOMdCList() {
        header('Access-Control-Allow-Origin: *');
        $output = $this->msamplerequestmodel->getMIBOMdCListt();
        echo json_encode($output);
    }

}