<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Mcaduser extends CI_Controller {
    public function __construct() {
        parent::__construct();
        fnIfCheckUserLoggedIn();
        $this->load->helper('xssclean');
        $this->load->model('commonmodel');
        $this->load->model('commonusermodel');
        $this->load->model('cadmodel');
        $this->load->model(CNFCOMPANY . 'mcadrequestmodel');
        $this->load->model(CNFCOMPANY . 'orderentrymodel');
        //$this->load->library('form_validation');
        $ArrUserLoggedInfo = fnGetUserLoggedInfo('1');
        $this->companyid = $ArrUserLoggedInfo['companyid'];
        $this->usertype = $ArrUserLoggedInfo['usertype'];
        $this->userid = $ArrUserLoggedInfo['id'];
        $this->mysqldatetime = date('Y-m-d H:i:s');
        $this->limit = LIMITPERPAGE;
        $this->ArrDbCols = array('contactname', 'desgn', 'username', 'mobile', 'status', 'updatedby', 'dateupdated');
        $this->load->helper('pdf');
    }
    public function index() {
        $this->load->view('cad/dashboard');
    }
    public function addedit() {
        /*$VarRemainingUser = $this->commonmodel->remaininguseravailable($this->companyid, 1);
        if ($VarRemainingUser == 0) {
            die('User Limit Ended. Can\'t add more users');
        }*/
        $ArrData = array('ArrBasicInfo' => array(), 'VarId' => '');
        $VarId = base64_decode(urldecode($this->uri->segment(4)));
        $ArrData['ArrStatus'] = unserialize(ARRSTATUS);
        $ArrData['Edit'] = $this->uri->segment(5);
        $VarDesgn = $this->commonmodel->getUserDesignation($this->usertype, '', 1);
        $ArrData['ArrDesgn'] = $VarDesgn;
        if (is_numeric($VarId)) {
            $ArrArrResults     									= $this->commonusermodel->fnGetInfo('','',$VarId);
            $ArrResults = $ArrArrResults[0];
            $VarDesignationId = $ArrResults['desgnid'];
            $ArrObjDesgn =$this->commonmodel->getUserDesignation('',$VarDesignationId);
            $ArrData['VarDesignation']	    				    = $ArrObjDesgn[0]['desgn'];
            $ArrData['ArrBasicInfo']	    				    = $ArrResults;
            $ArrData['VarId']					                = $ArrResults['id'];
        } else {
        }
        $this->load->view('cad/addeditcaduser', $ArrData);
    }
    public function updateUser() {
        $ArrUpdateData = array();
        $ArrUpdateData['id'] = xssclean($this->input->post('id'));
        $ArrUpdateData['username'] = xssclean($this->input->post('e'));
        $ArrUpdateData['contactname'] = xssclean($this->input->post('n'));
        $ArrUpdateData['mobile'] = xssclean($this->input->post('m'));
        $ArrUpdateData['companyid'] = $this->companyid;
        $ArrUpdateData['dateupdated'] = $this->mysqldatetime;
        $ArrUpdateData['updatedby'] = $this->userid;
        $ArrUpdateData['status'] = xssclean($this->input->post('s'));
        $ArrUpdateData['desgnid'] = xssclean($this->input->post('did'));
        $ArrUpdateData['password'] = COMMONPWD;
        $ArrUpdateData['usertype'] = $this->usertype;
        $ArrUpdateData['profilepermission'] = $this->usertype;
        if (!empty($ArrUpdateData['username'])) {
            if (empty($ArrUpdateData['id'])) {
                $ArrUpdateData['pin'] = '1234';
                $ArrUpdateData['datecreated'] = date('Y-m-d H:i:s');
            }
            $ArrResult = $this->commonusermodel->saveUser($ArrUpdateData);
        } else {
            $ArrResult['errcode'] = -1;
            $ArrResult['msg'] = 'Invalid Input';
        }
        echo json_encode($ArrResult);
    }
    public function manage() {
        $VarFrom = xssclean($this->input->post('rfrom'));
        $VarName = xssclean($this->input->post('n'));
        $VarMobile = xssclean($this->input->post('m'));
        $VarDesgnId = xssclean($this->input->post('d'));
        $VarStatus = xssclean($this->input->post('s'));
        $clickedColumnId = xssclean($this->input->post('columnId'));
        $VarSortOrder = xssclean($this->input->post('sortorder'));
        if ($VarFrom == 1) {
            $VarURLSegment = 4;
            $this->load->library('pagination');
            $config['base_url'] = base_url(CNFCOMPANY.'mcaduser/manage/');
            $config['total_rows'] = $this->commonusermodel->fnCount($VarName, $VarMobile, $VarDesgnId, $VarStatus, $this->usertype,$this->companyid);
            $config['per_page'] = $this->limit;
            $config['uri_segment'] = $VarURLSegment;
            $VarOffset = $this->uri->segment($VarURLSegment);
            $this->pagination->initialize($config);
            $ArrDbCols = $this->ArrDbCols;
            if(empty($VarSortOrder)) $VarSortOrder = 'desc';
            if (array_key_exists($clickedColumnId, $ArrDbCols)) $VarSortBy = $ArrDbCols[$clickedColumnId]; else $VarSortBy = '';
            $ArrList = $this->commonusermodel->fnList($VarName, $VarMobile, $VarDesgnId, $VarStatus, $this->usertype,$this->companyid,
                $this->limit, $VarOffset, $VarSortBy, $VarSortOrder);
            $data['pagination'] = $this->pagination->create_linkswithajax('CadUser');
            $i = 0;
            $ArrFnlList = array(); $ArrStatus = unserialize(ARRSTATUS);
            foreach ($ArrList['listData'] as $Obj) {
                $ArrFnlList[$i]['id'] = $Obj->id;
                $ArrFnlList[$i]['n'] = $Obj->contactname;
                $ArrFnlList[$i]['e'] = $Obj->username;
                $ArrFnlList[$i]['m'] = $Obj->mobile;
                $ArrFnlList[$i]['ds'] = $Obj->desgn;
                $ArrFnlList[$i]['s'] = $ArrStatus[$Obj->status];
                $ArrFnlList[$i]['ub'] = $ArrList['updatedByData'][$Obj->updatedby];
                $ArrFnlList[$i]['du'] = date('d-m-Y H:i:s', strtotime($Obj->dateupdated));
                $i = $i + 1;
            }
            echo json_encode(array('errcode' => '1', 'cn' => $config['total_rows'], 'ct' => $i, 're' => $ArrFnlList, 'pa' => base64_encode($data['pagination'])));
            unset($ArrFnlList);
            die;
        } else {
            $ArrDesignation = $this->commonmodel->getUserDesignation($this->usertype, '', 1);
            $this->load->view('cad/managecadusers', array('ArrDesignation' => $ArrDesignation,
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
    // public function cadreceivedlist() {
    //     $VarFrom = xssclean($this->input->post('rfrom'));
    //     if($VarFrom == 1) {
    //         $ArrList = $this->mcadrequestmodel->recdlistDatatables();
    //         $data = array();
    //         $ArrStatus = unserialize(ARRSTATUS);
    //         foreach ($ArrList as $Obj) {
    //             $row = array();
    //             $jsonData = json_decode($Obj->jsondatagrid);
    //             $VarRequirement = empty($jsonData[0][4]) ? '-' : $jsonData[0][4];
    //             $row[] = '<input type="checkbox" class="allcbox" id="' . $Obj->allid . '">';
    //             $row[] = '<a href="' . base_url(CNFCOMPANY.'mcaduser/cadqueuenoassign') . '/' . urlencode(base64_encode($Obj->allid)) . '">' . $Obj->isriorcode . '</a>';
    //             $row[] = $Obj->brandname;
    //             $row[] = $VarRequirement;
    //             $row[] = $Obj->requesttype;
    //             $row[] = $Obj->formattedDateCreated;
    //             $row[] = $Obj->formattedCutOffDt;
    //             $row[] = $Obj->approvaltype;
    //             $row[] = $Obj->mgmt;
    //             $row[] = $Obj->merchant;
    //             $row[] = $Obj->current_status;
    //             $row[] = $Obj->formattedDateUpdated;
    //             $row[] = $ArrStatus[$Obj->status];
    //             $data[] = $row;
    //         }
    //         $output = array(
    //             "draw" => $_POST['draw'],
    //             "recordsTotal" => $this->mcadrequestmodel->count_RecdList(),
    //             "recordsFiltered" => $this->mcadrequestmodel->count_recdListFiltered(),
    //             "data" => $data,
    //         );
    //         echo json_encode($output);

    //     }
    //     else {
    //         $this->load->view('cad/cadreceivedlist', array());
    //     }

    // }

    public function cadqueuenoassign() {
        $VarId = base64_decode(urldecode($this->uri->segment(4)));
        $HashedCadRequestId = $this->uri->segment(4);
        if ($VarId >= 1) {
            $ArrCadRequestData = $this->mcadrequestmodel->getCadRequestData($VarId, $this->companyid);
            $jsonAttachmentJxl = $ArrCadRequestData->jsonAttachmentDetails;
            $VarOrderId = $ArrCadRequestData->orderid;
            if (empty($ArrCadRequestData)) {
                die('Order Entry not completed');
            } else {
                $jsonDataGrid = 0;
                $ArrFinalSizes = [];
                if (!empty($ArrCadRequestData->jsondatagrid)) {
                    $jsonDataGrid = $ArrCadRequestData->jsondatagrid;
                }
                $ArrOrderEnqData = $this->orderentrymodel->fnGetOrderEnquiryInfo($VarOrderId, $this->companyid, '');
                $VarCommonOrderEntryInfo = $this->commonmodel->commonBasicInfoOrderEntry($VarOrderId, $this->companyid);
                $ArrData['ArrCompanyInfo'] = $VarCommonOrderEntryInfo['ArrCompanyInfo'];
                $ArrData['ArrMerchant'] = $VarCommonOrderEntryInfo['ArrMerchant'];
                $ArrData['ArrTeamInfo'] = $VarCommonOrderEntryInfo['ArrTeamInfo'];
                $ArrData['ArrOrderEnqData'] = $ArrOrderEnqData[0];
                $ArrData['brandName'] = $VarCommonOrderEntryInfo['brandName'];
                $ArrData['buyerName'] = $VarCommonOrderEntryInfo['buyerName'];
                $ArrData['ArrOrderCommonData'] = $VarCommonOrderEntryInfo['ArrOrderCommonData'];
                $ArrData['ArrBasicInfo'] = $ArrCadRequestData;
                $ArrPrevCadRefNo = $this->commonmodel->getPrevRefNo(1, $this->companyid, $VarOrderId);
                $VarMgmtInfo = $this->commonmodel->getUserInfo($ArrCadRequestData->mgmtid);
                $ArrData['ArrPrevCadRefNo'] = json_encode($ArrPrevCadRefNo);
                $ArrData['ArrReqSize'] = json_encode($ArrFinalSizes);
                $ArrData['VarId'] = $VarId;
                $ArrData['VarMgmtInfo'] = $VarMgmtInfo;
                $ArrData['HashedCadRequestId'] = $HashedCadRequestId;
                $ArrData['jsonDataGrid'] = $jsonDataGrid;
                $ArrData['jsonAttachmentJxl'] = $jsonAttachmentJxl;
            }
        }
        $this->load->view('cad/cadqueuenoassign', $ArrData);
    }

    public function fnCheckPinForCadQueueNo() {
        $currentStatus = ''; $VarMsg = '';
        $VarPwd = xssclean($this->input->post('i'));
        $VarCadRequestId = xssclean($this->input->post('crid'));
        $VarIsrIorCode = xssclean($this->input->post('isriorcode'));
        $VarApproveReject = xssclean($this->input->post('s'));
        $VarRemarks = xssclean($this->input->post('rem'));
        $VarJs = xssclean($this->input->post('jS'));
        $jxlGrid = xssclean($this->input->post('jxl'));
        if ($VarJs == '0000-00-00 00:00:00') {
            $VarJobDateTime = NULL;
        } elseif ($VarJs == '00-00-0000 00:00:00') {
            $VarJobDateTime = NULL;
        } elseif (empty($VarJs)) {
            $VarJobDateTime = NULL;
        } else {
            $VarJobDateTime = date('Y-m-d H:i:s',strtotime($VarJs));
            $currentStatus = 'JOB SCHEDULED';
        }
        $VarRequestListTypeId = xssclean($this->input->post('reqlisttypeid'));
        $VarQueueNoRes = $this->commonmodel->getAllQueueNo($VarRequestListTypeId, $this->companyid);
        if(empty($VarQueueNoRes->qid)) {
            $Qno = 1;
        }
        else {
            $Qno = $VarQueueNoRes->qid + 1;
        }
        if ($this->commonmodel->fnValidatePin($this->userid, $VarPwd)) {
            $VarQueueNo = $VarIsrIorCode . '/' . CADQNO_PREFIX . $Qno;
            if ($VarApproveReject == 2) {
                $VarMsg = 'Approved';
            } elseif ($VarApproveReject == 3) {
                $VarMsg = 'Rejected';
                $currentStatus = 'REQUEST REJECTED';
            }
            $ArrValues = array(
                'alldeptid' => $this->userid,
                'queueno' => $VarQueueNo,
                'qid'=>$Qno,
                'queueno_assigned_date' => date('Y-m-d H:i:s'),
                'deptremarks' => $VarRemarks,
                'jobschedule' => $VarJobDateTime,
                'deptcurrentstatus' => $VarApproveReject,
                'current_status' => $currentStatus,
                'dateupdated' => date('Y-m-d H:i:s'),
                'jsondatagrid' => $jxlGrid
            );
            $Res = $this->mcadrequestmodel->saveCadDeptAuthorise($VarCadRequestId, $this->companyid, $ArrValues);
            if ($Res) {
                $ArrLoggedInUserInfo = fnGetUserLoggedInfo('1');
                echo json_encode(array('errcode' => '1', 'qno' => $VarQueueNo, 'adt' => date('d-m-Y H:i:s'), 'msg' => $VarMsg,
                    'matIssuedByName' => $ArrLoggedInUserInfo['name'], 'ru' => date('d-m-Y H:i:s'), 'id' => $VarCadRequestId));
            } else {
                echo json_encode(array('errcode' => '-1', 'qno' => 0, 'adt' => 0, 'msg' => $VarMsg, 'id' => $VarCadRequestId));
            }
        } else {
            echo json_encode(array('errcode' => '-1', 'qno' => '0', 'adt' => '0', 'msg' => 'Invalid PIN', 'id' => ''));
        }
    }

    public function validatePin() {
        $rFrom = xssclean($this->input->post('rFrom'));
        $VarPwd = xssclean($this->input->post('i'));
        $VarId = xssclean($this->input->post('cadReqId'));
        $VarIsrIorCode = xssclean($this->input->post('isrIorCode'));
        if($rFrom == 1) {
            if ($this->commonmodel->fnValidatePin($this->userid, $VarPwd)) {
                $VarSql = "SELECT misc.id,sample_no FROM " . KN_ALLREQUEST . " AS a 
            INNER JOIN KN_SAMPLE_REQUEST_MISC AS misc ON misc.orderid = a.orderid WHERE a.id = '$VarId' AND 
            requestlisttypeid = '1' ORDER BY misc.id DESC";
                $MiscRes = $this->db->query($VarSql)->row();
                if(!empty($MiscRes)) {
                    $VarPriId = $MiscRes->id + 1;
                    $VarRefNo = $VarPriId;
                }
                else {
                    $VarRefNo = '1';
                }
                $VarRefNo = $VarIsrIorCode.'/'.CAD_REFNO_PREFIX.$VarRefNo;
                echo json_encode(array('errCode' => 1, 'refNo' => $VarRefNo,'msg' => 'OK'));
            } else {
                echo json_encode(array('errCode' => -1, 'refNo' => '','msg' => 'Invalid PIN'));
            }
        }
    }

    // public function cadqueuelist() {
    //     $From = xssclean($this->input->post('rFrom'));
    //     if($From == 1) {
    //         $ArrList = $this->cadmodel->getQueueListDataTables();
    //         $data = array();
    //         $ArrStatus = unserialize(ARRSTATUS);
    //         foreach ($ArrList as $Obj) {
    //             $row = array();
    //             $row[] = '<input type="checkbox" class="allcbox" id="' . $Obj->id . '">';
    //             $row[] = $Obj->isriorcode;
    //             $row[] = $Obj->brandname;
    //             $row[] = '<a href="' . base_url(CNFCOMPANY.'mcaduser/cadqueuelistdetail') . '/' . urlencode(base64_encode($Obj->id)) . '">' . $Obj->queueno . '</a>';
    //             if(!empty($Obj->jsondatagrid)) {
    //                 $ArrJsonData = json_decode($Obj->jsondatagrid, true);
    //                 $row[] = $ArrJsonData[0][4];
    //             }
    //             else {
    //                 $row[] = '';
    //             }
    //             $row[] = $Obj->formattedDateCreated;
    //             $row[] = $Obj->formattedCutOffDt;
    //             $row[] = $Obj->approvaltype;
    //             $row[] = $Obj->mgmt;
    //             $row[] = $Obj->merchantName;
    //             $row[] = $Obj->current_status;
    //             $row[] = $Obj->formattedDateUpdated;
    //             $row[] = $ArrStatus[$Obj->status];
    //             $data[] = $row;
    //         }
    //         $output = array(
    //             "draw" => $_POST['draw'],
    //             "recordsTotal" => $this->cadmodel->queueCountAll('1'),
    //             "recordsFiltered" => $this->cadmodel->queueCountFiltered('1'),
    //             "data" => $data,
    //         );
    //         echo json_encode($output);
    //     }
    //     else {
    //         $this->load->view('cad/cadqueuelist', array());
    //     }
    // }

    function cadloglist() {
        $VarId = xssclean($this->input->post('id'));
        $VarFrom = xssclean($this->input->post('rfrom'));
        if ($VarFrom == 1) {
            $ArrFnlList = array();
            $i = 0;
            $ArrOrderStatus = unserialize(ORDERENQUIRYSTATUS);
            $ArrCadLog = $this->mcadrequestmodel->getCadLogList($VarId);
            foreach ($ArrCadLog as $key => $Obj) {
                $ArrFnlList[$i]['id'] = $Obj->id;
                $ArrFnlList[$i]['du'] = date('d-m-Y', strtotime($Obj->dateupdated));
                $ArrFnlList[$i]['cs'] = $ArrOrderStatus[$Obj->mgmtcurrentstatus];
                $ArrFnlList[$i]['rem'] = $Obj->mgmtremarks;
                $i++;
            }
            echo json_encode(array('errcode' => '1', 'cn' => '2', 'ct' => $i, 're' => $ArrFnlList));
            unset($ArrFnlList);
            die();
        }
    }

    function changemStatus() {

        $VarActDeactOption = xssclean($this->input->post('actdeact'));
        $VarCid = xssclean($this->input->post('cid'));
        if ($VarActDeactOption <> '' && $VarCid <> '') {
            $Arrids = json_decode($VarCid, true);
            $this->db->where_in('id', $Arrids);
            if ($this->db->update(KN_CAD_REQUEST, array('status' => $VarActDeactOption))) {
                echo json_encode(array('errcode' => 1));
                die;
            } else                 echo json_encode(array('errcode' => -1));
        }
    }

    function cadqueuelistdetail() {
        $VarId = base64_decode(urldecode($this->uri->segment(4)));
        $HashedCadRequestId = $this->uri->segment(4);
        if ($VarId >= 1) {
            $ArrCadRequestData = $this->mcadrequestmodel->getCadRequestData($VarId, $this->companyid);
            $jsonAttachmentJxl = $ArrCadRequestData->jsonAttachmentDetails;
            $jsonDataGrid = 0;
            if (!empty($ArrCadRequestData->jsondatagrid)) {
                $jsonDataGrid = $ArrCadRequestData->jsondatagrid;
            }
            $VarOrderId = $ArrCadRequestData->orderid;
            $ArrOrderDatas = $this->orderentrymodel->getOrderDataByWip($VarOrderId, $this->companyid);
            if (empty($ArrOrderDatas)) {
                die('Order Entry not completed');
            } else {
                $ArrOrderEnqData = $this->orderentrymodel->fnGetOrderEnquiryInfo($VarOrderId, $this->companyid, '');
                $VarCommonOrderEntryInfo = $this->commonmodel->commonBasicInfoOrderEntry($VarOrderId, $this->companyid);
                $VarMgmtInfo = $this->commonmodel->getUserInfo($ArrCadRequestData->mgmtid);
                $ArrData['ArrCompanyInfo'] = $VarCommonOrderEntryInfo['ArrCompanyInfo'];
                $ArrData['ArrMerchant'] = $VarCommonOrderEntryInfo['ArrMerchant'];
                $ArrData['ArrTeamInfo'] = $VarCommonOrderEntryInfo['ArrTeamInfo'];
                $ArrData['ArrOrderEnqData'] = $ArrOrderEnqData[0];
                $ArrData['brandName'] = $VarCommonOrderEntryInfo['brandName'];
                $ArrData['buyerName'] = $VarCommonOrderEntryInfo['buyerName'];
                $ArrData['ArrOrderCommonData'] = $VarCommonOrderEntryInfo['ArrOrderCommonData'];
                $ArrData['ArrBasicInfo'] = $ArrCadRequestData;
                $ArrData['VarId'] = $VarId;
                $ArrData['VarMgmtInfo'] = $VarMgmtInfo;
                $ArrData['ArrCategory'] = json_encode(unserialize(ARRCADCATEGORY));
                $ArrData['HashedCadRequestId'] = $HashedCadRequestId;
                $ArrData['jsonDataGrid'] = $jsonDataGrid;
                $ArrData['VarCurrentUserType'] = $this->usertype;
                $ArrData['jsonAttachmentJxl'] = $jsonAttachmentJxl;
            }
        }
        $this->load->view('cad/cadqueuelistdetail', $ArrData);
    }

    public function updateCadReqWithPIN() {
        $VarPwd = xssclean($this->input->post('i'));
        if ($this->commonmodel->fnValidatePin($this->userid, $VarPwd)) {
            $VarId = xssclean($this->input->post('reqId'));
            $VarDeptRemarks = xssclean($this->input->post('deptRemarks'));
            $VarJxlGrid = xssclean($this->input->post('jxlGrid'));
            $VarCurrentStatus = xssclean($this->input->post('current_status'));
            $VarCompleted = xssclean($this->input->post('completed'));

            $jobDateTime = xssclean($this->input->post('js'));
            if ($jobDateTime == '0000-00-00 00:00:00') {
                $VarJobDateTime = NULL;
            } elseif ($jobDateTime == '00-00-0000 00:00:00') {
                $VarJobDateTime = NULL;
            } elseif (empty($jobDateTime)) {
                $VarJobDateTime = NULL;
            } else {
                $VarJobDateTime = date('Y-m-d H:i:s',strtotime($jobDateTime));
            }
            $this->db->where('id', $VarId);
            $this->db->update(KN_ALLREQUEST, array('jsondatagrid' => $VarJxlGrid, 'deptremarks' => $VarDeptRemarks,
                'jobschedule' => $VarJobDateTime, 'current_status'=>$VarCurrentStatus, 'dateupdated' => $this->mysqldatetime,
                'queuecompletestatus'=>$VarCompleted));
            echo json_encode(array('errcode' => '1', 'msg'=>'OK', 'dt' => date('d-m-Y H:i:s')));
        } else {
            echo json_encode(array('errcode' => '-1', 'msg' => 'Invalid PIN', 'dt' => ''));
        }
    }

    public function getCadRefNo() {
        $VarId = xssclean($this->input->post('id'));
        $VarIsrIorCode = xssclean($this->input->post('isrIorCode'));
        $Qid = '1';
        $Cad = $this->commonmodel->getAllQueueNo($VarReqType = "CAD",$this->companyid);
        if(!empty($Cad)) {
            $Qid = $Cad->qid;
        }
        $VarCadRefNo = $VarIsrIorCode.'/'.CAD_REFNO_PREFIX.$Qid;
        echo json_encode(array('errCode' => '1', 'refNo' => $VarCadRefNo));

    }

    // public function cadindentlist() {
    //     $ArrStatus = unserialize(ARRSTATUS);
    //     if (xssclean($this->input->post('rfrom')) == 1) {
    //         $this->load->model('indentsmodel');
    //         $ArrList = $this->indentsmodel->cadIndentListDataTables();
    //         $data = array();
    //         $ArrAllUserTypes = unserialize(ARRUSERTYPE);

    //         $ArrRequestType = unserialize(ARRREQUESTTYPE);
    //         foreach ($ArrList as $Obj) {
    //             $row = array();
    //             $row[] = '<input type="checkbox" class="allcbox" id="' . $Obj->id . '">';
    //             $row[] = '<a href="' . base_url(CNFCOMPANY.'mcaduser/cadindentdetails') . '/' . urlencode(base64_encode($Obj->id)) . '">' . $Obj->isriorcode . '</a>';
    //             $row[] = $Obj->brandname;
    //             $row[] = $Obj->queueno;
    //             $row[] = $Obj->cadissuedto;
    //             $row[] = $Obj->cad_mat_ind_ref_no;
    //             $row[] = $Obj->formattedDateCreated;
    //             $row[] = $Obj->indentcutoffdt;
    //             $row[] = $Obj->approvaltype;
    //             $row[] = $Obj->contactname;
    //             $row[] = '<a href="javascript:void(0)">Current Status</a>';
    //             $row[] = $Obj->formattedDateUpdated;
    //             $row[] = isset($ArrStatus[$Obj->status]) ? $ArrStatus[$Obj->status] : '-';
    //             $data[] = $row;
    //         }
    //         $output = array(
    //             "draw" => $_POST['draw'],
    //             "recordsTotal" => $this->indentsmodel->cadIndentListCountAll(),
    //             "recordsFiltered" => $this->indentsmodel->cadIndentListCountFiltered(),
    //             "data" => $data,
    //         );
    //         echo json_encode($output);
    //     } else {
    //         $this->load->view('cad/cadindentlist',array());
    //     }
    // }

    public function getCadIndents() {
        $VarRequestId = xssclean($this->input->post('requestid'));
        $ArrCadIndentDetails = $this->commonmodel->getSampleRequestCadIndents($VarRequestId);
        if (!empty($ArrCadIndentDetails)) {
            foreach ($ArrCadIndentDetails as $c) {
                $cadIndentDetails[$c->id] = $c->gridindent;
            }
            echo json_encode(array('errcode' => 1, 'cadIndentGrid' => $cadIndentDetails));
        }
    }


    public function cadindentdetails() {
        $VarRequestId = base64_decode(urldecode($this->uri->segment(4)));
        $this->load->model(CNFCOMPANY . 'msamplerequestmodel');
        if ($VarRequestId >= 1) {
            $ArrObjRequestData       = $this->msamplerequestmodel->getSamRequestData($VarRequestId, $this->companyid);
            $ArrRequestData = $ArrObjRequestData[0];
            $VarOrderId = $ArrRequestData->orderid;
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
            $ArrData['VarRequestId'] = $VarRequestId;
            $ArrData['ArrCurrencyNCode'] = json_encode(unserialize(ARRCURRENCYLIST));
            $ArrData['ArrOrderCommonData'] = $VarCommonOrderEntryInfo['ArrOrderCommonData'];
            $ArrData['ArrBasicInfo'] = $ArrRequestData;
            $ArrData['jsonSamReqGrid'] = $ArrRequestData->jsondatagrid;
            $ArrData['AuthorizedByInfo'] = $this->commonmodel->getUserInfo($ArrRequestData->mgmtid);
            $this->load->view('cad/cadindentdetail', $ArrData);
        }

    }

    public function updateCadIndentDetail() {
        $VarReqId = xssclean($this->input->post('id'));
        $VarCadgridData = xssclean($this->input->post('cadgridData'));
        $VarMaterialIssuedBy = xssclean($this->input->post('MaterialIssuedBy'));
        $VarIndentRefNo = 'CAD-I' . $VarReqId;
        $ArrUpdateData = array('cadindentgrid' => $VarCadgridData, 'recentupdates' => $this->mysqldatetime, 'currentstatus' => '2',
            'indentrefno' => $VarIndentRefNo);
        $Res = $this->commonmodel->updateCadIndentDetail($ArrUpdateData, $VarReqId);
        if ($Res >= 1) {
            echo json_encode(array('errcode' => '1', 'msg' => 'ok', 'indrefno' => $VarIndentRefNo));
        } else {
            echo json_encode(array('errcode' => '-1', 'msg' => '', 'indrefno' => ''));
        }
    }

    // *** New *** /

   public function cadreceivedlist() {
          $data['brands'] = $this->getBrandList();
       $this->load->view('cad/cadreceivedlist', $data);
   }
    public function getBrandList() 
    {
        header('Access-Control-Allow-Origin: *');
        $output = $this->mcadrequestmodel->getBrandListt();
        return $output;
    }
   
   public function getcadreceivedlist() {
       $data = $this->mcadrequestmodel->getcadreceivedlistt();
       echo json_encode($data);
   }

   public function cadqueuelist() {
      $data['brands'] = $this->getBrandList();
       $this->load->view('cad/cadqueuelist', $data);
   }

   public function getcadQueuelist() {
       $data = $this->mcadrequestmodel->getcadQueuelistt();
       echo json_encode($data);
   }

   public function cadsentlist() {
      $data['brands'] = $this->getBrandList();
       $this->load->view('cad/cadsentlist', $data);
   }

   public function getcadSentlist() {
       $data = $this->mcadrequestmodel->getcadSentlistt();
       echo json_encode($data);
   }

   public function cadindentlist() {
       $data['brands'] = $this->getBrandList();
       $this->load->view('cad/cadindentlist', $data);
   }

   public function getcadIndentlist() {
       $data = $this->mcadrequestmodel->getcadIndentlistt();

    //print_r($data);
       echo json_encode($data);
   }

   public function caddclist() {
       $data['brands'] = $this->getBrandList();
       $this->load->view('cad/caddclist', $data);
   }

   public function getcadDClist() {
       $data = $this->mcadrequestmodel->getcadDClistt();
       echo json_encode($data);
   }


}