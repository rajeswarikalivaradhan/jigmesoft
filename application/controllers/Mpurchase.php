<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Mpurchase extends CI_Controller {
    private $limit = 10;
    public $companyid;
    public $userid;
    public $mysqldatetime;
    public $purchaseusertblname;
    public $usertblname;

    public function __construct() {
        parent::__construct();
        error_reporting(E_ALL);
        fnIfCheckUserLoggedIn();
        $this->load->helper('xssclean');
        $ArrUserLoggedInfo = fnGetUserLoggedInfo('1');
        $this->load->model('commonmodel');
        $this->load->model(CNFCOMPANY . 'mcadrequestmodel');
        $this->load->model(CNFCOMPANY . "orderentrymodel");
        $this->companyid = $ArrUserLoggedInfo['companyid'];
        $this->userid = $ArrUserLoggedInfo['id'];
        $this->purchaseusertblname = 'kn_purchaseusers';
        $this->mysqldatetime = date('Y-m-d H:i:s');
    }

    public function addeditpurchaseuser() {
        $VarRemainingUser = $this->commonmodel->remaininguseravailable($this->companyid, 1);
        if ($VarRemainingUser == 0) {
            die('User Limit Ended. Can\'t add more users');
        }
        $ArrData = array('ArrBasicInfo' => array(), 'VarId' => '', 'VarNew' => 0);

        $VarId = $this->uri->segment(3);
        if ($VarId <> '' && base64_decode(urldecode($VarId))) {
            $VarUserId = base64_decode(urldecode($VarId));
            echo '<pre>';
            print_r();
            die('die');
            $ArrResults = $this->fnGetInfo('', '', $VarUserId);
            $ArrData['ArrBasicInfo'] = $ArrResults[0];
            $ArrData['VarId'] = $ArrResults[0]['id'];
        } else {
            $ArrData['VarNew'] = 1;
        }
        $this->load->view('purchase/addeditpurchaseuser', $ArrData);
    }

    public function fnGetInfo($VarEmailId = '', $VarStatus = '', $VarUserId = '') {
        $this->db->from(KN_USERS . ' AS u');
        if ($VarStatus <> '') {
            $this->db->where_in('u.status', array($VarStatus));
        } else {
            $this->db->where_in('u.status', array(1, 2));
        }
        if ($VarUserId <> '') {
            $ArrWhere['u.id'] = $VarUserId;
        }
        if ($VarEmailId <> '') {
            $ArrWhere['username'] = $VarEmailId;
        }
        if (@count($ArrWhere) >= 1) {
            $this->db->where($ArrWhere);
        }
        $ArrResultList = $this->db->get()->result_array();
        return $ArrResultList;
    }

    public function updatePurchaseUser() {
        $ArrUpdateData = array();
        $ArrUpdateData['id'] = xssclean($this->input->post('id'));
        $ArrUpdateData['username'] = xssclean($this->input->post('e'));
        $ArrUpdateData['contactname'] = xssclean($this->input->post('n'));
        $ArrUpdateData['mobile'] = xssclean($this->input->post('m'));
        $ArrUpdateData['companyid'] = $this->companyid;
        $ArrUpdateData['dateupdated'] = $this->mysqldatetime;
        $ArrUpdateData['updatedby'] = $ArrOData['updatedby'] = $this->userid;
        $ArrOData['dateupdated'] = $this->mysqldatetime;
        $ArrOData['companyid'] = $this->companyid;
        $ArrOData['status'] = 1;
        $ArrUpdateData['status'] = xssclean($this->input->post('s'));
        $ArrUpdateData['password'] = 'Password123';
        $ArrUpdateData['usertype'] = '8';
        $ArrUpdateData['profilepermission'] = '8';

        if ($ArrUpdateData['username'] <> '') {
            if ($ArrUpdateData['id'] == '' || $ArrUpdateData['id'] == 0) {
                $ArrOData['code'] = mt_rand();
                $ArrUpdateData['datecreated'] = $this->mysqldatetime;
                $ArrOData['datecreated'] = $this->mysqldatetime;
            }
            $ArrResult = $this->savePurchaseUser($ArrUpdateData, $ArrOData);
        } else {
            $ArrResult['errcode'] = '-1';
            $ArrResult['msg'] = 'Invalid Input!';
        }
        echo json_encode($ArrResult);
    }

    public function savePurchaseUser($ArrUpdateData, $ArrOData) {
        $VarId = $ArrUpdateData['id'];
        if ($VarId == "") {
            $ArrCheckExist = $this->fnGetInfo($ArrUpdateData['username'], 1);
            if (@$ArrCheckExist[0]['id'] == '' && @$ArrCheckExist[0]['id'] == 0) {
                unset($ArrUpdateData['id']);
                $this->db->insert(KN_USERS, $ArrUpdateData);
                $VarUserId = $this->db->insert_id();
                $ArrOData['userid'] = $VarUserId;
                $this->db->insert($this->purchaseusertblname, $ArrOData);
                $ArrResult['errcode'] = 1;
                $ArrResult['msg'] = 'Successfully updated';
                $ArrResult['id'] = $VarId;
                $ArrResult['eid'] = urlencode(base64_encode($VarUserId));
            } else {
                $ArrResult['errcode'] = '-1';
                $ArrResult['msg'] = "This E-mail Id already exists!!";
            }
        } else {
            if ($this->db->update(KN_USERS, $ArrUpdateData, array('id' => $VarId))) {
                $this->db->update($this->purchaseusertblname, $ArrOData, array('userid' => $VarId));
                $ArrResult['errcode'] = 1;
                $ArrResult['msg'] = 'Successfully updated';
                $ArrResult['id'] = $VarId;
                $ArrResult['eid'] = urlencode(base64_encode($VarId));
            } else {
                $ArrResult['errcode'] = '-1';
                $ArrResult['msg'] = 'Invalid Data!';
            }
        }
        return $ArrResult;
    }

    public function managepurchaseusers() {
        $VarFrom = xssclean($this->input->post('rfrom'));
        $VarName = xssclean($this->input->post('n'));
        $VarEmail = xssclean($this->input->post('e'));
        $VarStatus = xssclean($this->input->post('s'));
        $clickedColumnId = xssclean($this->input->post('columnId'));
        $newsortorder = xssclean($this->input->post('sortorder'));
        $VarAfilter = xssclean($this->input->post('afilter'));
        $ArrDbCols = array('u.contactname', 'u.username', 'u.mobile', 'u.status', 'u.dateupdated');
        if ($VarFrom == 1) {
            $VarURLSegment = 4;
            $this->load->library('pagination');
            $config['base_url'] = base_url() . 'mpurchase/managepurchaseusers/';
            $config['total_rows'] = $this->fnCount($VarName, $VarEmail, $this->companyid, $VarStatus, $VarAfilter);
            $config['per_page'] = 10;
            $config['uri_segment'] = $VarURLSegment;
            $offset = $this->uri->segment($VarURLSegment);
            $this->pagination->initialize($config);
            $sortby = "dateupdated";
            $sortorder = "desc";
            if ($clickedColumnId <> '' && $newsortorder <> '') {
                if (array_key_exists($clickedColumnId, $ArrDbCols)) {
                    $sortby = $ArrDbCols[$clickedColumnId];
                }
                $sortorder = $newsortorder;
            }
            $ArrList = $this->fnList($VarName, $VarEmail, $this->companyid, $VarStatus, $this->limit, $offset, $sortby, $sortorder, $VarAfilter)->result();
            $data['pagination'] = $this->pagination->create_linkswithajax('Purchaseusers');
            $i = 0;
            $ArrFnlList = array();
            $ArrStatus = unserialize(ARRSTATUS);
            foreach ($ArrList as $ObjUnit) {
                $ArrFnlList[$i]['id'] = $ObjUnit->id;
                $ArrFnlList[$i]['n'] = $ObjUnit->contactname;
                $ArrFnlList[$i]['e'] = $ObjUnit->username;
                $ArrFnlList[$i]['m'] = $ObjUnit->mobile;
                $VarUser = $this->commonmodel->getUserInfo($ObjUnit->updatedby);
                $ArrFnlList[$i]['ub'] = @$VarUser[0]->contactname;
                //$ArrFnlList[$i]['s']  = $ObjUnit->status == 1 ? '<span back' $ArrStatus[$ObjUnit->status];
                $ArrFnlList[$i]['s'] = $ArrStatus[$ObjUnit->status];
                $ArrFnlList[$i]['du'] = date('d-m-Y H:i:s', strtotime($ObjUnit->dateupdated));
                $i = $i + 1;
            }
            echo json_encode(array('errcode' => '1', 'cn' => $config['total_rows'], 'ct' => $i, 're' => $ArrFnlList, 'pa' => base64_encode($data['pagination'])));
            unset($ArrFnlList);
            die;
        } else {
            $this->load->view('purchase/managepurchaseusers');
        }
    }

    public function fnCount($VarName = '', $VarEmailId = '', $VarCompanyId = '', $VarStatus = '', $VarAfilter = '') {
        $ArrWhere = array();
        if ($VarName <> '') {
            $ArrWhere[] = "u.contactname like '%" . $VarName . "%'";
        }
        if ($VarCompanyId <> '') {
            $ArrWhere[] = "u.companyid =" . $VarCompanyId;
        }
        if ($VarEmailId <> '') {
            $ArrWhere[] = "u.username = '" . $VarEmailId . "'";
        }

        if ($VarStatus <> '') {
            $ArrWhere[] = "u.status=" . $VarStatus;
        } else {
            $ArrWhere[] = "u.status in(1,2)";
        }
        if ($VarAfilter <> '') {
            $ArrWhere[] = "u.contactname like '" . $VarAfilter . "%'";
        }
        $VarWhere = '';
        if (@$ArrWhere[0] <> '') {
            $VarWhere = implode(" and ", $ArrWhere) . " AND usertype = 8";
        }

        $VarSql = "SELECT count(1) as trec  FROM " . $this->purchaseusertblname . " AS c INNER JOIN " . KN_USERS . " AS u ON c.userid = u.id WHERE " . $VarWhere;
        $ObjRows = $this->db->query($VarSql)->row();
        return $ObjRows->trec;
    }

    function fnList($VarMgmtname = '', $VarEmailId = '', $VarCompanyId = '', $VarStatus = '', $VarLimit = 10, $offset = 0, $VarSortBy, $VarSortOrder, $VarAfilter = '') {
        $VarSortOrder = ($VarSortOrder == 'desc') ? 'desc' : 'asc';
        $VarSortCols = array("1" => 'u.contactname', '2' => 'u.username', '3' => 'u.mobile', '4' => 'u.status', '5' => 'u.dateupdated');
        $VarSortBy = (in_array($VarSortBy, $VarSortCols)) ? $VarSortBy : 'u.dateupdated';
        $VarLimitInfo = $VarLimit;
        if ($offset >= 1) {
            $VarLimitInfo = $offset . "," . $VarLimit;
        }
        $ArrWhere = array();
        if ($VarMgmtname <> '') {
            $ArrWhere[] = "u.contactname like '%" . $VarMgmtname . "%'";
        }
        if ($VarCompanyId <> '') {
            $ArrWhere[] = "u.companyid =" . $VarCompanyId;
        }
        if ($VarEmailId <> '') {
            $ArrWhere[] = "u.username = '" . $VarEmailId . "'";
        }
        if ($VarStatus <> '') {
            $ArrWhere[] = "u.status=" . $VarStatus;
        } else {
            $ArrWhere[] = "u.status in(1,2)";
        }
        if ($VarAfilter <> '') {
            $ArrWhere[] = "u.contactname like '" . $VarAfilter . "%'";
        }
        $VarWhere = '';
        if (@$ArrWhere[0] <> '') {
            $VarWhere = implode(" and ", $ArrWhere) . " AND usertype = 8";
        }
        $VarSqlLab = "SELECT u.id,u.contactname,u.mobile,u.username,u.datecreated,u.dateupdated,u.status,u.updatedby FROM " . $this->purchaseusertblname . " 
        AS c INNER JOIN " . KN_USERS . " AS u ON c.userid = u.id WHERE " . $VarWhere . " order by " . $VarSortBy . " " . $VarSortOrder . " limit " . $VarLimitInfo;
        $ObjResult = $this->db->query($VarSqlLab);
        return $ObjResult;
    }

    public function changemStatus() {
        $VarActDeactOption = xssclean($this->input->post('actdeact'));
        $VarCid = xssclean($this->input->post('cid'));
        if ($VarActDeactOption <> '' && $VarCid <> '') {
            $Arrids = json_decode($VarCid, true);
            $this->db->where_in('userid', $Arrids);
            if ($this->db->update($this->purchaseusertblname, array('status' => $VarActDeactOption))) {
                $this->db->where_in('id', $Arrids);
                $this->db->update(KN_USERS, array('status' => $VarActDeactOption));
                echo json_encode(array('errcode' => '1'));
                die;
            } else {
                echo json_encode(array('errcode' => '-1'));
            }
        }
    }

    public function deletedata() {
        $VarId = xssclean($this->input->post('id'));
        if ($VarId >= 1) {
            $ArrUpdateData = array('status' => '3', 'dateupdated' => $this->mysqldatetime, 'updatedby' => $this->userid);
            if ($this->db->update($this->purchaseusertblname, $ArrUpdateData, array('userid' => $VarId))) {
                $this->db->update(KN_USERS, $ArrUpdateData, array('id' => $VarId));
                $ArrResult['errcode'] = 1;
                $ArrResult['msg'] = '';
                $ArrResult['id'] = $VarId;
                $ArrResult['eid'] = urlencode(base64_encode($VarId));
            } else {
                $ArrResult['errcode'] = '-1';
                $ArrResult['msg'] = 'Invalid Data!';
            }
        } else {
            $ArrResult['errcode'] = '-1';
            $ArrResult['msg'] = '';
        }
        echo json_encode($ArrResult);
    }

    public function bompurchaseRequestDetails() {
        $ArrPurpose = [];
        $this->load->model("mbompurchaserequestmodel");
        $VarBomRequestId = base64_decode(urldecode($this->uri->segment(3)));
        $RequestData = $this->mbompurchaserequestmodel->getBomPurchaseRequest($VarBomRequestId, $this->companyid);
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

        $ArrObjPurpose = $this->mcadrequestmodel->getPurpose();
        if(!empty($ArrObjPurpose)) {
            foreach ($ArrObjPurpose as $purpose)
                $ArrPurpose[] = $purpose->purpose;
        }
        $ArrData['ArrPurpose'] = $ArrPurpose;
        $ArrData['ArrBasicInfo'] = $RequestData;
        $ArrData['VarArtType'] = $RequestData->articletypeid;
        $ArrData['VarId'] = $VarBomRequestId;
        $this->load->view('purchase/bompurchasereqdetails', $ArrData);
    }

    public function addeditbompurchase() {
        $VarOrderId = base64_decode(urldecode($this->uri->segment(3)));
        if ($VarOrderId > 0) {
            $ArrPurpose = [];
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
            /*TODO Purpose added in common.php. Removed master pages for now.*/
            /*$ArrObjPurpose = $this->mcadrequestmodel->getPurpose();
            foreach ($ArrObjPurpose as $purpose) {
                $ArrPurpose[] = $purpose->purpose;
            }*/
            $ArrPurpose = ARR_BOM_PURCHASE_REQUEST_PURPOSE;
            $ArrData['ArrPurpose'] = $ArrPurpose;
            $ArrData['VarId'] = 0;
            $this->load->view('purchase/addeditbompurchasereq', $ArrData);
        }
    }

    public function getAddeditBOMDatas() {
        $this->load->model(CNFCOMPANY.'bommodel');
        $VarRfrom = xssclean($this->input->post('rfrom'));
        $VarOrderId = xssclean($this->input->post('refid'));
        $VarArticleType = xssclean($this->input->post('at'));
        $VarShortagesId = xssclean($this->input->post('shortages'));
        $bomPurRequestId = xssclean($this->input->post('bomPurRequestId'));
        if ($VarRfrom == 1) {
            $SourcingSamplingApprGrid = 0;

            $jsonBomConsolidated     = $this->bommodel->getConsolidated($VarOrderId, $this->companyid, $VarArticleType);
            //$jsonFromTwelfthRes     = $this->orderentrymodel->getFromBomConsTwelfth($VarOrderId, $this->companyid, $VarArticleType, 'asc');

            $ArrSourcingApprovalRes = $this->bommodel->getBomSourcingDetails($VarOrderId, $this->companyid, $VarArticleType);
            //$ArrSourcingApprovalRes = $this->orderentrymodel->getBomSourcingDetails($VarOrderId, $this->companyid, $VarArticleType);

            $ResSamplingAppr        = $this->bommodel->getSamplingAndApprovalDetails($VarOrderId, $this->companyid, $VarArticleType);
            //$ResSamplingAppr        = $this->orderentrymodel->getBomSamplingAndApprovalDetailsBothArticle($VarOrderId, $this->companyid, $VarArticleType);
            //$ResSamplingAppr        = $this->orderentrymodel->getBomSampling_ApprThirteenth($VarOrderId, $this->companyid, $VarArticleType);

            if (!empty($jsonBomConsolidated) && !empty($ArrSourcingApprovalRes->jsondatagrid) && !empty($ResSamplingAppr->jsondatagrid)) {
                $ArrSourcingApproval = $ArrSourcingApprovalRes->jsondatagrid;
                $ArrBomSampling = json_decode($ResSamplingAppr->jsondatagrid, true);
                //echo '<pre>'; print_r($ArrBomSampling); die('die');
                foreach ($ArrBomSampling as $bomsrcappr) {
                    $ArrSamplingAppr[] = array($bomsrcappr[0], $bomsrcappr[1], $bomsrcappr[2], $bomsrcappr[3], $bomsrcappr[4], $bomsrcappr[5], $bomsrcappr[6],
                        $bomsrcappr[7], $bomsrcappr[8], $bomsrcappr[9], $bomsrcappr[10]);
                }
                $BomConsTwelfth = $jsonBomConsolidated[0]['jsondatagrid'];
                $consolidatedForBomPI = json_decode($jsonBomConsolidated[0]['jsondatagrid']);
                foreach ($consolidatedForBomPI as $key => $item) {
                    $BomConsForBomPurInd[] = array($item[0], $item[1], $item[2], $item[3], $item[4], $item[5]);
                }
                echo json_encode(array('errcode' => '1',
                    'bomConsolidated' => $BomConsTwelfth,
                    'bomSourcingDetail' => $ArrSourcingApproval,
                    'ArrSamplingAppr' => $ArrSamplingAppr,
                    'BomConsForBomPurInd' => json_encode($BomConsForBomPurInd),
                    'unitofmeasure' => json_encode(array_values(unserialize(ARRUNITOFMEASURE))),
                ));
            } else {
                echo json_encode(array('errcode' => -1,'msg'=>'No data'));
            }
        }
    }

    public function getBomForBOMPurResShortages() {
        $VarFrom = xssclean($this->input->post('rfrom'));
        $VarOrderId = xssclean($this->input->post('refid'));
        $VarArticleType = xssclean($this->input->post('at'));
        $bomPurRequestId = xssclean($this->input->post('bomPurRequestId'));
        if ($VarFrom == 1) {
            if($VarArticleType == 3) {
                $bomConsolidated = $this->orderentrymodel->getFromBomConsTwelfth($VarOrderId, $this->companyid, 1, 'asc');
                $sourcingApprovalRes = $this->orderentrymodel->getBomSourcingDetails($VarOrderId, $this->companyid, 1);
            }
            elseif ($VarArticleType == 4) {
                $bomConsolidated = $this->orderentrymodel->getFromBomConsTwelfth($VarOrderId, $this->companyid, 2, 'asc');
                $sourcingApprovalRes = $this->orderentrymodel->getBomSourcingDetails($VarOrderId, $this->companyid, 2);
            }
            $jsonSourcingDetail = '';
            if(!empty($sourcingApprovalRes->jsondatagrid)) {
                $jsonSourcingDetail = $sourcingApprovalRes->jsondatagrid;
            }
            if(!empty($bomConsolidated->jsondatagrid)) {
                $ArrBomConsolidated = json_decode($bomConsolidated->jsondatagrid);
                foreach ($ArrBomConsolidated as $shortagesItem) {
                    $ShoItemDesc[] = $shortagesItem[0];
                    $ArrBomGarSizes[] = $shortagesItem[1];
                    $ShoItemCode[] = $shortagesItem[2];
                    $ShoItemColorCode[] = $shortagesItem[3];
                    $ShoSizeDim[] = $shortagesItem[4];
                    $ShoUom[] = $shortagesItem[5];
                }
                echo json_encode(
                    array(
                    'errcode' => 1,
                    'ShoItemDesc' => $ShoItemDesc,
                    'SizeChartSizes' => $ArrBomGarSizes,
                    'ShoItemCode' => $ShoItemCode,
                    'ShoItemColorCode' => $ShoItemColorCode,
                    'ShoSizeDim' => $ShoSizeDim,
                    'ShoUom' => $ShoUom,
                    'bomSourcingDetail'=>$jsonSourcingDetail
                    )
                );
            }
            else {
                echo json_encode(array('errcode' => -1,'msg'=>'No Data'));
            }
        }
        else {
            echo json_encode(array('errcode' => -1,'msg'=>'Error'));
        }
    }

    public function updateBOMPurchasereq() {
        $this->load->model('mbompurchaserequestmodel');
        $VarRfrom = xssclean($this->input->post('rfrom'));
        if ($VarRfrom == 1) {
            $VarId = xssclean($this->input->post('id'));
            $VarOrderId = xssclean($this->input->post('oid'));
            $VarArticleType = xssclean($this->input->post('at'));
            $VarPurpose = xssclean($this->input->post('pur'));
            $VarRequesttype = xssclean($this->input->post('reqt'));
            $VarIsrIorCode = xssclean($this->input->post('isrior'));
            /*
             * @TODO shortages pending $bomConsolidatedShortage in new item list integration
             * */
            $bomConsolidatedShortage = xssclean($this->input->post('bomConsolidatedShortage'));
            $this->commonmodel->bulkInsertBomCompanyBased($VarOrderId);
            $VarCutoff = date('Y-m-d H:i:s', strtotime(xssclean($this->input->post('coff'))));
            $VarMerNote = xssclean($this->input->post('mn'));
            if ($VarId == '' || $VarId == 0) {
                $VarMgmtcurrentstatus = 1;
                $VarDeptcurrentstatus = 1;
            } else {
                $ArrBomRequestData = $this->commonmodel->getAllRequestDetails($VarId, $this->companyid);
                $VarMgmtcurrentstatus = @$ArrBomRequestData->mgmtcurrentstatus;
                $VarDeptcurrentstatus = @$ArrBomRequestData->deptcurrentstatus;
            }
            $ArrAllreqCommonData = array('id' => $VarId, 'merchantid' => $this->userid, 'merchantnote' => $VarMerNote,
                'requesttype' => $VarRequesttype, 'isriorcode' => $VarIsrIorCode, 'companyid' => $this->companyid, 'status' => '1',
                'datecreated' => $this->mysqldatetime, 'dateupdated' => $this->mysqldatetime,'mgmtcurrentstatus' => $VarMgmtcurrentstatus,
                'requirementforbom' => $VarArticleType, 'orderid' => $VarOrderId, 'request_type_dept' => 'BOM', 'cutoffdatetime' =>
                    $VarCutoff,'deptcurrentstatus' => $VarDeptcurrentstatus, 'alldeptid' => $this->userid);
            $ArrStatus = $this->mbompurchaserequestmodel->saveBomPurchaseRequest($ArrAllreqCommonData, $VarPurpose, $VarArticleType,$bomConsolidatedShortage);
            echo json_encode($ArrStatus);
            unset($ArrStatus);
            unset($ArrAllreqCommonData);
            die;
        }
    }
}