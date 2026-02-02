<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Mstoreuser extends CI_Controller {
    public function __construct() {
        parent::__construct();
        fnIfCheckUserLoggedIn();
        $this->load->helper('xssclean');
        $this->load->model('commonmodel');
        $this->load->model('commonusermodel');
        $this->load->model("bom_store_model");
        $this->load->model("mbompurcahseindentmodel");
        $this->load->model("mbompurchaserequestmodel");
        $ArrUserLoggedInfo = fnGetUserLoggedInfo('1');
        $this->companyid = $ArrUserLoggedInfo['companyid'];
        $this->userid = $ArrUserLoggedInfo['id'];
        $this->mysqldatetime = date('Y-m-d H:i:s');
        $this->limit = LIMITPERPAGE;
        $this->ArrDbCols = array('contactname', 'desgn', 'username', 'mobile', 'status', 'updatedby', 'dateupdated');
        $this->usertype = getUserTypeId("BOM Store");
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
        $this->load->view('stores/addeditstoresuser', $ArrData);
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
            $config['base_url']    = base_url().CNFCOMPANY.'mstoreuser/manage/';
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
            $data['pagination'] = $this->pagination->create_linkswithajax('StoreUser');
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
            $this->load->view('stores/managestoresusers',array('ArrDesignation' => $ArrDesignation,
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
        $this->load->view('stores/userdashboard');
    }

    public function bomitem_received_details() {
        $rFrom = xssclean($this->input->post('rfrom'));
        if ($rFrom == 1) {
            $BomPurIndentNo = [];
            $VarOrderId = xssclean($this->input->post('oid'));
            $bomPurRequestId = xssclean($this->input->post('bomPurRequestId'));
            $VarQNo = fnGetAllRequestQueueNo($bomPurRequestId);
            $BomPurIndentNoRes = $this->mbompurcahseindentmodel->getPurchaseIndentNo($VarOrderId);
            if (empty($BomPurIndentNoRes)) {
                echo json_encode(array('errcode' => 1, 'purIndentNo' => $BomPurIndentNo));
            } else {
                foreach ($BomPurIndentNoRes as $BomPurIndentNoResItem) {
                    $BomPurIndentNo[] = $VarQNo->queueno . '/' . BOMPURIND_PREFIX . '-' . $BomPurIndentNoResItem->purchaseindent_no;
                }
                echo json_encode(array('errcode' => 1, 'purIndentNo' => $BomPurIndentNo));
            }
        } else {
            $VarBomPurIndentId = base64_decode(urldecode($this->uri->segment(3)));
            $ArrData['ArrItemizedBomRecdInvoiceJxl'] = 0; $ArrData['invoiceTblPrimaryKey'] = 0;
            $ArrData['ArrLotApprovalInfo'] = '';
            $ArrData['VarInvoiceCount'] = 0;
            $VarItemRefNo = empty($this->uri->segment(4)) ? 0 : $this->uri->segment(4);
            $BomPurIndentInfo = $this->mbompurcahseindentmodel->getBomPIDetails($VarBomPurIndentId);
            $VarBomPurReqId = $BomPurIndentInfo->bompurrequestid;
            $RequestData = $this->mbompurchaserequestmodel->getBomPurchaseRequest($VarBomPurReqId, $this->companyid);
            $dynamicTblName = $RequestData->bompirequestgrid_tblname;
            $VarOrderId = $BomPurIndentInfo->orderid;
            //echo '<pre>'; print_r($VarBomPurReqId);
            //echo '<pre>'; print_r($VarBomPurIndentId); die('die');
            $ArrPiData = $this->mbompurcahseindentmodel->getBomPIDynamicTblData($dynamicTblName,'','',$VarBomPurReqId,$VarBomPurIndentId,$VarOrderId,$VarStatus=1);
            if(!empty($ArrPiData)) {
                $ArrData['VarTotalItem'] = count($ArrPiData);
            }

            $ArrData['PurchaseIndentNo'] = $RequestData->queueno . '/' . BOMPURIND_PREFIX.$VarBomPurIndentId;
            $ArrData['VarBomPurIndentId'] = $VarBomPurIndentId;
            $ArrItemizedBomRecdInvoice = $this->commonmodel->getItemizedBomRecdInvoice($VarBomPurIndentId, $VarItemRefNo);
            $ArrLotApprovalDataRes     = $this->commonmodel->getBomStoreLotApprovalData($VarBomPurIndentId, $VarItemRefNo);
            //print_r($ArrLotApprovalDataRes);
            if(!empty($ArrLotApprovalDataRes)) {
                $ArrLotApprovalData = $ArrLotApprovalDataRes[0];
                $ArrData['ArrLotApprovalInfo'] = $ArrLotApprovalData;
                //echo '<pre>'; print_r(unserialize($ArrLotApprovalDataRes[0]->itemverifyauthstatus));
                //echo '<pre>'; print_r(unserialize($ArrLotApprovalData[0]->qtyverifyauthstatus)); die('die');
            }
            //echo '<pre>'; print_r($ArrItemizedBomRecdInvoice); die('die');
            if (!empty($ArrItemizedBomRecdInvoice->itemrecdinvoicegrid)) {
                $ArrData['invoiceTblPrimaryKey'] = $ArrItemizedBomRecdInvoice->id;
                $ArrData['ArrItemizedBomRecdInvoiceJxl'] = $ArrItemizedBomRecdInvoice->itemrecdinvoicegrid;
                $VarInvoiceCount = count(json_decode($ArrItemizedBomRecdInvoice->itemrecdinvoicegrid, true));
                $ArrData['ArrLotApprovalInfo'] = $ArrItemizedBomRecdInvoice->itemverifyauthstatus;
                $ArrData['VarInvoiceCount'] = $VarInvoiceCount;
            }
            $ArrData['ArrStatus1'] = array('1' => 'Pending', '2' => 'Accept', '3' => 'Discrepancy', '4' => 'Reject');
            $ArrData['ArrMgmtStatus'] = array('1' => 'Pending', '2' => 'Approved', '3' => 'Declined');
            $ArrData['ArrStoresreadyStatus'] = array('1' => 'Pending', '2' => 'Ready to Issue', '3' => 'Item Returned');
            $bomItemIdRes = $this->commonmodel->getAllBom($VarOrderId,$ArrPiData[$VarItemRefNo]['itemdesc'],
                $ArrPiData[$VarItemRefNo]['garmentsize'],$ArrPiData[$VarItemRefNo]['itemcode'],
                $ArrPiData[$VarItemRefNo]['itemcolorcode'],$ArrPiData[$VarItemRefNo]['sizeordim'],
                $ArrPiData[$VarItemRefNo]['uom1'],$ArrPiData[$VarItemRefNo]['planbomqty']);

            $bomItemId = 0;
            if(!empty($bomItemIdRes)) {
                $bomItemId = $bomItemIdRes[0]->id;
            }
            //$ArrData['bomItem'] = $bomItem;
            $ArrData['bomItemId'] = $bomItemId;
            $ArrData['ArrPiItems'] = $ArrPiData;
            $ArrData['VarBomPurReqId'] = $VarBomPurReqId;
            $ArrData['VarItemRefNo'] = $VarItemRefNo;
            $ArrData['VarBomPurIndentId'] = $VarBomPurIndentId;
            $ArrData['jsonUnitMeasure'] = json_encode(array_values(unserialize(ARRUNITOFMEASURE)));
            $ArrData['ArrLotAppruserType'] = unserialize(ARRLOTAPPRUSERTYPE);
            $VarCommonOrderEntryInfo = $this->commonmodel->commonBasicInfoOrderEntry($VarOrderId, $this->companyid);
            $ArrData['VarCommonOrderEntryInfo'] = $VarCommonOrderEntryInfo;
            $ArrData['ArrCompanyInfo'] = $VarCommonOrderEntryInfo['ArrCompanyInfo'];
            $ArrData['ArrMerchant'] = $VarCommonOrderEntryInfo['ArrMerchant'];
            $ArrData['ArrTeamInfo'] = $VarCommonOrderEntryInfo['ArrTeamInfo'];
            $ArrData['ArrOrderEnqData'] = $VarCommonOrderEntryInfo['ArrOrderEnqData'];
            $ArrData['ArrBB'] = $VarCommonOrderEntryInfo['ArrBB'];
            $ArrData['ArrOrderCommonData'] = $VarCommonOrderEntryInfo['ArrOrderCommonData'];
            $ArrData['VarOrderId'] = $VarOrderId;
            $ArrData['ArrStatus1'] = array('1' => 'Pending', '2' => 'Accept', '3' => 'Discrepancy', '4' => 'Reject');
            $ArrData['ArrMgmtStatus'] = array('1' => 'Pending', '2' => 'Approved', '3' => 'Declined');
            $ArrData['ArrStoresreadyStatus'] = array('1' => 'Pending', '2' => 'Ready to Issue', '3' => 'Item Returned');
            $this->load->view('stores/bomitemreceived_details', $ArrData);
        }
    }

    public function bomitemissuedreturn() {
        $VarPiRefId = $this->uri->segment(3);
        $VarIdesc = base64_decode(urldecode($this->uri->segment(4)));
        $VarIcode = base64_decode(urldecode($this->uri->segment(5)));

        $ArrInvDataRes = $this->commonmodel->getBomPurchaseIndentInvoice($VarPiRefId, $this->companyid);
        $VarOrderId = $ArrInvDataRes[0]->orderid;
        $ArrData = $this->commonmodel->commonBasicInfoOrderEntry($VarOrderId, $this->companyid);
        $this->load->view('stores/bomitemissuedRretuemdetails_inline', $ArrData);
    }

    public function getBomIndents() {
        $VarRequestId = xssclean($this->input->post('requestid'));
        $ArrBomIndentDetails = $this->commonmodel->getSampleRequestBomIndents($VarRequestId);
        if (!empty($ArrBomIndentDetails)) {
            foreach ($ArrBomIndentDetails as $b) {
                $bomIndentDetails[$b->id] = $b->gridindent;
            }
            echo json_encode(array('errcode' => 1, 'bomIndentGrid' => $bomIndentDetails));
        }
    }

    public function bomindentlist() {
        $rFrom = xssclean($this->input->post('rfrom'));
        if ($rFrom == 1) {
            $this->load->model('indentsmodel');
            $ArrList = $this->indentsmodel->bomIndentListDataTables();
            $data = array();
            $ArrStatus = unserialize(ARRSTATUS);
            foreach ($ArrList as $Obj) {
                $row = array();
                $row[] = '<input type="checkbox" class="allcbox" id="' . $Obj->id . '">';
                $row[] = '<a href="' . base_url(CNFCOMPANY.'mstoreuser/indentdetails') . '/' . urlencode(base64_encode($Obj->id)) . '">' . $Obj->isriorcode . '</a>';
                $row[] = $Obj->brandname;
                $row[] = $Obj->queueno;
                $row[] = $Obj->bomissuedto;
                $row[] = $Obj->bom_mat_ind_ref_no;
                $row[] = $Obj->formattedDateCreated;
                $row[] = $Obj->indentcutoffdt;
                $row[] = $Obj->approvaltype;
                $row[] = $Obj->contactname;
                $row[] = '<a href="javascript:void(0)">Current Status</a>';
                $row[] = $Obj->formattedDateUpdated;
                $row[] = isset($ArrStatus[$Obj->status]) ? $ArrStatus[$Obj->status] : '-';
                $data[] = $row;
            }
            $output = array(
                "draw" => $_POST['draw'],
                "recordsTotal" => $this->indentsmodel->bomIndentCount_all(),
                "recordsFiltered" => $this->indentsmodel->bomIndentCount_filtered(),
                "data" => $data,
            );
            echo json_encode($output);
        } else {
            $this->load->view('stores/indentlist', array());
        }
    }

    public function indentdetails() {
        $this->load->model(CNFCOMPANY . 'orderentrymodel');
        $this->load->model(CNFCOMPANY . 'msamplerequestmodel');
        $VarRequestId = base64_decode(urldecode($this->uri->segment(4)));
        if ($VarRequestId > 0) {
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
            $ArrData['ArrCurrencyNCode'] = json_encode(unserialize(ARRCURRENCYLIST));
            $ArrData['ArrOrderCommonData'] = $VarCommonOrderEntryInfo['ArrOrderCommonData'];
            $ArrData['ArrBasicInfo'] = $ArrRequestData;
            $ArrData['VarRequestId'] = $VarRequestId;
            $ArrData['AuthorizedByInfo'] = $this->commonmodel->getUserInfo($ArrRequestData->mgmtid);
            $this->load->view('stores/indentdetails', $ArrData);
        }
    }

    /** Save Only the id from the table which has checked row
     *  and compare the data with bom all data and get array keys
     */
    public function updateSend2StockListBomIndent() {

        //$BomIndentKey = json_decode(xssclean($this->input->post('BomIndentKey')));
        $ArrGridData 	= json_decode(xssclean($this->input->post('BomIndent')));
        $jsonBomIndent 	= xssclean($this->input->post('BomIndent'));
        $VarBomIndToStoresStockId = json_decode(xssclean($this->input->post('BomIndToStoresStockId')));
        $VarSampleRequestId = base64_decode(urldecode(xssclean($this->input->post('reqid'))));
        $VarIndentCutoff   = date_format(date_create(xssclean($this->input->post('indentCutoff'))), 'Y-m-d H:i:s');
        $VarIndentRefNo  = xssclean($this->input->post('indrefno'));
        $VarOrderId     = xssclean($this->input->post('oid'));
        $ArrBomItemId = [];
        foreach ($ArrGridData as $key => $gridData) {
            $ArrBomItemIdRes = $this->commonmodel->getAllBom($VarOrderId,$gridData[0],$gridData[1],$gridData[2],$gridData[3],$gridData[4],$gridData[5]);
            if(!empty($ArrBomItemIdRes[0])) {
                $ArrBomItemId[] = $ArrBomItemIdRes[0]->id;
            }
        }
        $ArrUpdate = array('companyid' => $this->companyid, 'orderid' => $VarOrderId,'requestid' => $VarSampleRequestId,
            'indcutoffdt' =>$VarIndentCutoff, 'indentrefno' => $VarIndentRefNo,
            'datecreated' => $this->mysqldatetime);
        $Res = $this->commonmodel->updateSend2StockListBomIndent($ArrUpdate,$ArrBomItemId,$VarSampleRequestId,$jsonBomIndent);
        if ($Res) {
            echo json_encode(array('errcode' => '1', 'id' => $Res, 'reqid' => $VarSampleRequestId, 'oid' => $VarOrderId));
        }
    }

    public function updateNewItemList() {
        $VarPiRefNo   = base64_decode(urldecode(xssclean($this->input->post('pirefno'))));
        //echo '<pre>'; print_r($VarPiRefNo);
        $bomPurchaseRequestId = xssclean($this->input->post('requestId'));
        $VarItemRefNo = xssclean($this->input->post('itemrefno'));
        $VarOrderId   = xssclean($this->input->post('oid'));
        $bomItemId    = xssclean($this->input->post('bomItemId'));
        $LotApprovalRes = $this->commonmodel->bomInvoiceAndLotApproval($VarPiRefNo,$bomItemId);
        //echo '<pre>'; print_r($LotApprovalRes); die('die');
        if(!empty($LotApprovalRes[0])) {
            if(!empty($LotApprovalRes[0]->itemreadyauthstatus)) {
                $ArrApprovalStatus = unserialize($LotApprovalRes[0]->itemreadyauthstatus);
                //echo '<pre>'; print_r($ArrApprovalStatus);
                if($ArrApprovalStatus['approveReject'] == 2) {
                    $ArrData = array('companyid' => $this->companyid, 'orderid' => $VarOrderId,'bomPurchaseRequestId'=>
                        $bomPurchaseRequestId,'bompurindentid' => $VarPiRefNo,'purchaseIndentBomId' => $bomItemId,
                        'status' => '1', 'datecreated' => $this->mysqldatetime);
                    $this->commonmodel->saveNewItemList($ArrData);
                    echo json_encode(array('errcode' => 1,'msg'=>'Success'));
                }
                else {
                    echo json_encode(array('errcode' => -1,'msg'=>'Lot may be Rejected'));
                }
            }
        }
        else {
            echo json_encode(array('errcode' => -1,'msg'=>''));
        }

        //$VarBomGroupToSend = xssclean($this->input->post('BomGroupToSend'));
        //$ArrAllBomDataGroup = $this->commonmodel->getAllBom($VarOrderId);
        //$ArrKeys = array_keys($ArrAllBomDataGroup,$VarBomGroupToSend);
        //echo '<pre>'; print_r($VarPiRefNo);
        //echo '<pre>'; print_r($ArrKeys); die('die');
        //foreach ($ArrKeys as $bomId) {
        //}
    }

    public function newitemlist() {
        $rFrom = xssclean($this->input->post('rfrom'));
        if ($rFrom == 1) {
            $ArrList = $this->mbompurcahseindentmodel->newItemListDataTables();
            $data = array();
            $ArrAllUserTypes = unserialize(ARRUSERTYPE);
            $ArrStatus = unserialize(ARRSTATUS);
            $ArrRequestType = unserialize(ARRREQUESTTYPE);
            foreach ($ArrList as $Obj) {
                $row = array();
                //$ArrItems = explode('|#|',$Obj->bom);
                $row[] = '<input type="checkbox" class="allcbox" id="' . $Obj->id . '">';
                $row[] = '<a href="#">' . $Obj->isriorcode . '</a>';
                $row[] = $Obj->brandname . ' / ' . $Obj->buyername;
                $row[] = '<a href="'.base_url('storesuser/newItemDetails').'/'.$Obj->id.'">'.$Obj->itemdesc.'</a>';
                $row[] = $Obj->garmentsize;
                $row[] = $Obj->itemcode;
                $row[] = $Obj->itemcolorcode;
                $row[] = $Obj->sizeordim;
                $row[] = $Obj->uom1;
                //if(!empty($ArrItems)) {
                /*$row[] = !empty($ArrItems[0]) ? $ArrItems[0] : '';
                $row[] = !empty($ArrItems[1]) ? $ArrItems[1] : '';
                $row[] = !empty($ArrItems[2]) ? $ArrItems[2] : '';
                $row[] = !empty($ArrItems[3]) ? $ArrItems[3] : '';
                $row[] = !empty($ArrItems[4]) ? $ArrItems[4] : '';
                $row[] = !empty($ArrItems[5]) ? $ArrItems[5] : '';*/
                //}
                $row[] = '<a href="javascript:void(0)">Current Status</a>';
                $row[] = date('d-m-Y H:i:s', strtotime($Obj->date_created));
                $row[] = 'Active';
                $data[] = $row;
            }
            $output = array(
                "draw" => $_POST['draw'],
                "recordsTotal" => $this->mbompurcahseindentmodel->count_allNewItemListDataTables(),
                "recordsFiltered" => $this->mbompurcahseindentmodel->count_filteredNewItemListDataTables(),
                "data" => $data,
            );
            echo json_encode($output);
        } else {
            $this->load->view('misc/newitemlist');
        }
    }

    public function matIndentReceivedDetails($VarId) {
        $Sql = "SELECT al.jsondatagrid,al.datecreated,al.mgmtid,al.merchantid,bi.gridindent,s.bom_item_id,s.requestid,
ni.purchaseIndentBomId,sreq.bom_mat_ind_ref_no,sreq.bomissuedto,sreq.bomindentcutoffdatetime 
FROM ".KN_SEND_BOMIND2STORES_STOCKLIST." AS s INNER JOIN ".KN_STORES_NEW_ITEM_LIST." AS ni 
        ON s.bom_item_id = ni.purchaseIndentBomId INNER JOIN ".KN_ALLREQUEST." as al ON al.id = s.requestid INNER JOIN ".KN_MERCHANT_SAMPLE_BOM_INDENT." 
        AS bi ON s.requestid = bi.requestid INNER JOIN ".KN_SAMPLE_REQUEST." AS sreq ON sreq.requestrefid = al.id WHERE ni.id = '$VarId' ";
        $ArrObjResult = $this->db->query($Sql)->result();
        $ArrIndentRefNo = [];
        if(!empty($ArrObjResult)) {
            foreach ($ArrObjResult as $res) {
                $VarSampleRequestId = $res->requestid;
                $VarIndentRefNo = $res->bom_mat_ind_ref_no;
                $matRaisedDataTime = $res->datecreated;
                $matIndentCutOffDataTime = $res->bomindentcutoffdatetime;
                $ArrSampleRequestJxl = json_decode($res->jsondatagrid,true);
                $VarPurpose = $ArrSampleRequestJxl[0][6];
                $matRaisedByInfo = $this->commonmodel->getUserInfo($res->merchantid);
                $matAuthorizedByInfo = $this->commonmodel->getUserInfo($res->mgmtid);
                $matIssuedToDept = $res->bomissuedto;
                $ArrIndentJxl = json_decode($res->gridindent,true);
                $matIndentQty = $ArrIndentJxl[0][6];
                $matUnitOfMeasure = $ArrIndentJxl[0][7];
                $ArrMatIndent[] = array($VarIndentRefNo,$matRaisedDataTime,$matIndentCutOffDataTime,$VarPurpose,
                    $matRaisedByInfo[0]->contactname,$matAuthorizedByInfo[0]->contactname,$matIssuedToDept,$matIndentQty,$matUnitOfMeasure);
            }
            //return array($ArrMatIndent,$ArrIndentRefNo);
            //return $ArrMatIndent;
            return array($ArrMatIndent,$VarSampleRequestId);
        }
    }


    public function newItemDetails() {
        $VarId      = $this->uri->segment(3);
        $Res = $this->matIndentReceivedDetails($VarId);
        $materialIndentReceivedDetails = $Res[0];
        $VarSampleRequestId = $Res[1];
        $ArrMatIndentRefNo = [];
        foreach ($materialIndentReceivedDetails as $material) {
            $ArrMatIndentRefNo[] = $material[0];
        }
        $ArrData['matIndentReceivedDetailsJxl'] = json_encode($materialIndentReceivedDetails);
        $ArrData['ArrMatIndentRefNo'] = json_encode($ArrMatIndentRefNo);
        $ArrNewItemDetails = $this->bom_store_model->newItemListDetail($VarId);
        $bomPurchaseRequestId = $ArrNewItemDetails->bomPurchaseRequestId;
        $VarOrderId = $ArrNewItemDetails->orderid;
        $RequestData = $this->mbompurchaserequestmodel->getBomPurchaseRequest($bomPurchaseRequestId, $this->companyid);
        $VarBomItemId = $ArrNewItemDetails->purchaseIndentBomId;
        $VarBomPurIndentId = $ArrNewItemDetails->bompurindentid;
        $VarPiNo = $RequestData->queueno.'/'.BOMPURIND_PREFIX.$VarBomPurIndentId;
        $invoiceAndLotApprovalRes = $this->commonmodel->bomInvoiceAndLotApproval('',$VarBomItemId);
        $MaterialReceivedQty = [];
        if(!empty($invoiceAndLotApprovalRes)) {
            foreach ($invoiceAndLotApprovalRes as $invoiceAndLot) {
                if(!empty($invoiceAndLot->itemrecdinvoicegrid)) {
                    $ArrInvoices = json_decode($invoiceAndLot->itemrecdinvoicegrid,true);
                    $MaterialReceivedQty[] = $ArrInvoices[0][5];
                }
            }
        }
        $VarAllBomTblName = 'bom_companyid_'.$this->companyid;
        $allBomRes = $this->db->select('itemdesc,garmentsize,itemcode,itemcolorcode,sizeordim,uom1,planbomqty,uom2')
            ->where(array('id'=>$VarBomItemId,'orderid'=>$VarOrderId))
            ->from($VarAllBomTblName)
            ->get()
            ->row();
        if(!empty($allBomRes)) {
            $bom = $allBomRes;
            $ArrBom = array($bom->itemdesc,$bom->garmentsize,$bom->itemcode,$bom->itemcolorcode,$bom->sizeordim,
                $bom->uom1,$VarPiNo,$bom->planbomqty,array_sum($MaterialReceivedQty),$bom->uom2);
        }
        $VarCommonOrderEntryInfo = $this->commonmodel->commonBasicInfoOrderEntry($VarOrderId, $this->companyid);
        $ArrData['ArrPiData'] = json_encode($ArrBom);
        $ArrData['VarCommonOrderEntryInfo'] = $VarCommonOrderEntryInfo;
        $ArrData['ArrCompanyInfo'] = $VarCommonOrderEntryInfo['ArrCompanyInfo'];
        $ArrData['ArrMerchant'] = $VarCommonOrderEntryInfo['ArrMerchant'];
        $ArrData['ArrTeamInfo'] = $VarCommonOrderEntryInfo['ArrTeamInfo'];
        $ArrData['ArrOrderEnqData'] = $VarCommonOrderEntryInfo['ArrOrderEnqData'];
        $ArrData['ArrBB']  = $VarCommonOrderEntryInfo['ArrBB'];
        $ArrData['newItemId'] = $VarId;
        $ArrData['bomItemId'] = $VarBomItemId;
        $ArrData['sampleRequestId'] = $VarSampleRequestId;
        $ArrData['VarOrderId'] = $VarOrderId;
        $ArrData['ArrOrderCommonData'] = $VarCommonOrderEntryInfo['ArrOrderCommonData'];
        $this->load->view('misc/bomNewItemQtyIssueDetails',$ArrData);
    }

    public function getNewItemDC_QtyIssueDetailsJxl($VarId) {
        $Res = $this->matIndentReceivedDetails($VarId);
        $materialIndentReceivedDetails = $Res[0];
        $VarSampleRequestId = $Res[1];
        $ArrMatIndentRefNo = [];
        foreach ($materialIndentReceivedDetails as $material) {
            $ArrMatIndentRefNo[] = $material[0];
        }
        $ArrData['matIndentReceivedDetailsJxl'] = json_encode($materialIndentReceivedDetails);
        $ArrData['ArrMatIndentRefNo'] = json_encode($ArrMatIndentRefNo);
        $ArrNewItemDetails = $this->bom_store_model->newItemListDetail($VarId);
        $bomPurchaseRequestId = $ArrNewItemDetails->bomPurchaseRequestId;
        $VarOrderId = $ArrNewItemDetails->orderid;
        $RequestData = $this->mbompurchaserequestmodel->getBomPurchaseRequest($bomPurchaseRequestId, $this->companyid);
        $VarBomItemId = $ArrNewItemDetails->purchaseIndentBomId;
        $VarBomPurIndentId = $ArrNewItemDetails->bompurindentid;
        $VarPiNo = $RequestData->queueno.'/'.BOMPURIND_PREFIX.$VarBomPurIndentId;
        $invoiceAndLotApprovalRes = $this->commonmodel->bomInvoiceAndLotApproval('',$VarBomItemId);
        $MaterialReceivedQty = $ArrMatReceivedQty = [];
        if(!empty($invoiceAndLotApprovalRes)) {
            foreach ($invoiceAndLotApprovalRes as $invoiceAndLot) {
                if(!empty($invoiceAndLot->itemrecdinvoicegrid)) {
                    $ArrInvoices = json_decode($invoiceAndLot->itemrecdinvoicegrid,true);
                    $MaterialReceivedQty[] = $ArrInvoices[0][5];
                }
            }
        }
        $VarAllBomTblName = 'bom_companyid_'.$this->companyid;
        $allBomRes = $this->db->select('itemdesc,garmentsize,itemcode,itemcolorcode,sizeordim,uom1,planbomqty,uom2')
            ->where(array('id'=>$VarBomItemId,'orderid'=>$VarOrderId))
            ->from($VarAllBomTblName)
            ->get()
            ->row();
        if(!empty($allBomRes)) {
            $bom = $allBomRes;
            $ArrBom = array($bom->itemdesc,$bom->garmentsize,$bom->itemcode,$bom->itemcolorcode,$bom->sizeordim,
                $bom->uom1,$VarPiNo,$bom->planbomqty,array_sum($MaterialReceivedQty),$bom->uom2);
            $ArrMatReceivedQty[] = array_sum($MaterialReceivedQty);
        }
        $VarUom2 = $allBomRes->uom2;
        $ArrAllRequiredQty = [];
        $VarCommonOrderEntryInfo = $this->commonmodel->commonBasicInfoOrderEntry($VarOrderId, $this->companyid);
        $invoiceAndLotApprovalRes = $this->commonmodel->bomInvoiceAndLotApproval($VarBomPurIndentId,$VarBomItemId);
        if(!empty($invoiceAndLotApprovalRes[0])) {
            $VarItemInvoiceGrid = $invoiceAndLotApprovalRes[0]->itemrecdinvoicegrid;
            $VarItemRefNo = $invoiceAndLotApprovalRes[0]->itemrefno;
            if(!empty($VarItemInvoiceGrid)) {
                $ArrItemInvoiceGrid = json_decode($VarItemInvoiceGrid,true);
                foreach ($ArrItemInvoiceGrid as $invoice) {
                    if(!empty($invoice[5])) {
                        $ArrAllRequiredQty[] = $invoice[5];
                    }
                }
                $VarRequiredQty = array_sum($ArrAllRequiredQty);
                $ArrData['RequiredQty'] = $VarRequiredQty;
                $ArrData['VarItemRefNo'] = $VarItemRefNo;
            }
        }
        $matIssuedByInfo = $this->commonmodel->getUserInfo($this->userid);
        $newItemQtyIssuedDetails = array(array('','','',$matIssuedByInfo[0]->contactname,'',array_sum($MaterialReceivedQty),'',$VarUom2));
        echo json_encode($newItemQtyIssuedDetails);
    }

    public function old_newItemDetails() {
        $rFrom = xssclean($this->input->post('rFrom'));
        $VarNewItemId = xssclean($this->input->post('newItemId'));
        $VarOrderId = xssclean($this->input->post('oid'));
        $VarId = xssclean($this->input->post('id'));
        $matReceivedQty_Total = xssclean($this->input->post('matReceivedQty_Total'));
        if($rFrom == 1) {
            $materialIndentReceivedDetails = $this->bom_store_model->matIndentReceivedDetails($VarId);
            $ArrIndentRefNo = $materialIndentReceivedDetails[1];
            $ArrMatIndentInfo = $materialIndentReceivedDetails[0];
            $newItemQtyIssuedDetailsRes = $this->db->select('*')->from(KN_NEW_ITEM_QTY_ISSUED_DETAIL_JXL)->
            where(['newItemId'=>$VarNewItemId,'orderid'=>$VarOrderId])->get()->result();

            //echo '<pre>'; print_r($newItemQtyIssuedDetailsRes); die('die');
            $newItemQtyIssuedDetails = [];
            $matIssuedByInfo = $this->commonmodel->getUserInfo($this->userid);
            if(!empty($newItemQtyIssuedDetailsRes)) {
                foreach ($newItemQtyIssuedDetailsRes as $value) {
                    $newItemQtyIssuedDetails[] = array('','','',$matIssuedByInfo[0]->contactname,'',$matReceivedQty_Total,'','UOM');
                }
            }
            echo json_encode(array('ArrIndentRefNo'=>$ArrIndentRefNo,'ArrMatIndentInfo'=>$ArrMatIndentInfo,'newItemQtyIssuedDetails'=>$newItemQtyIssuedDetails));
        }
        else {
            //$VarOrderId        = $this->uri->segment(3);
            $VarId      = $this->uri->segment(3);
            $ArrNewItemDetails = $this->bom_store_model->newItemListDetail($VarId);
            $bomPurchaseRequestId = $ArrNewItemDetails->bomPurchaseRequestId;
            $VarOrderId = $ArrNewItemDetails->orderid;
            $RequestData = $this->mbompurchaserequestmodel->getBomPurchaseRequest($bomPurchaseRequestId, $this->companyid);
            $VarBomItemId = $ArrNewItemDetails->purchaseIndentBomId;
            $VarBomPurIndentId = $ArrNewItemDetails->bompurindentid;
            $invoiceAndLotApprovalRes = $this->commonmodel->bomInvoiceAndLotApproval('',$VarBomItemId);
            $allBomRes = $this->commonmodel->getAllBom($VarOrderId,'','','','','','','','',$VarBomItemId);
            $MaterialReceivedQty = $ArrMatReceivedQty = [];
            if(!empty($invoiceAndLotApprovalRes)) {
                foreach ($invoiceAndLotApprovalRes as $invoiceAndLot) {
                    if(!empty($invoiceAndLot->itemrecdinvoicegrid)) {
                        $ArrInvoices = json_decode($invoiceAndLot->itemrecdinvoicegrid,true);
                        $MaterialReceivedQty[] = $ArrInvoices[0][5];
                    }
                }
            }
            $VarPiNo = $RequestData->queueno.'/'.BOMPURIND_PREFIX.$VarBomPurIndentId;
            foreach($allBomRes as $bom) {
                $ArrBom[] = array($bom->itemdesc,$bom->garmentsize,$bom->itemcode,$bom->itemcolorcode,$bom->sizeordim,
                    $bom->uom1,$VarPiNo,$bom->planbomqty,array_sum($MaterialReceivedQty),$bom->uom2);
                $ArrMatReceivedQty[] = array_sum($MaterialReceivedQty);
            }
            //echo '<pre>'; print_r($ArrBom); die('die');
            $ArrAllRequiredQty = [];
            $VarCommonOrderEntryInfo = $this->commonmodel->commonBasicInfoOrderEntry($VarOrderId, $this->companyid);
            $invoiceAndLotApprovalRes = $this->commonmodel->bomInvoiceAndLotApproval($VarBomPurIndentId,$VarBomItemId);
            if(!empty($invoiceAndLotApprovalRes[0])) {
                $VarItemInvoiceGrid = $invoiceAndLotApprovalRes[0]->itemrecdinvoicegrid;
                $VarItemRefNo = $invoiceAndLotApprovalRes[0]->itemrefno;
                if(!empty($VarItemInvoiceGrid)) {
                    $ArrItemInvoiceGrid = json_decode($VarItemInvoiceGrid,true);
                    foreach ($ArrItemInvoiceGrid as $invoice) {
                        if(!empty($invoice[5])) {
                            $ArrAllRequiredQty[] = $invoice[5];
                        }
                    }
                    $VarRequiredQty = array_sum($ArrAllRequiredQty);
                    $ArrData['RequiredQty'] = $VarRequiredQty;
                    $ArrData['VarItemRefNo'] = $VarItemRefNo;
                }
            }
            $ArrData['matReceivedQty_Total'] = json_encode(array_sum($ArrMatReceivedQty));
            $ArrData['ArrPiData'] = json_encode($ArrBom[0]);
            $ArrData['VarCommonOrderEntryInfo'] = $VarCommonOrderEntryInfo;
            $ArrData['ArrCompanyInfo'] = $VarCommonOrderEntryInfo['ArrCompanyInfo'];
            $ArrData['ArrMerchant'] = $VarCommonOrderEntryInfo['ArrMerchant'];
            $ArrData['ArrTeamInfo'] = $VarCommonOrderEntryInfo['ArrTeamInfo'];
            $ArrData['ArrOrderEnqData'] = $VarCommonOrderEntryInfo['ArrOrderEnqData'];
            $ArrData['ArrBB']  = $VarCommonOrderEntryInfo['ArrBB'];
            $ArrData['ArrBom'] = json_encode($ArrBom);
            $ArrData['newItemId'] = $VarNewItemId;
            $ArrData['VarId'] = $VarId;
            $ArrData['VarOrderId'] = $VarOrderId;
            $ArrData['ArrOrderCommonData'] = $VarCommonOrderEntryInfo['ArrOrderCommonData'];
            $this->load->view('misc/bomNewItemQtyIssueDetails',$ArrData);
        }
    }

    public function saveNewItemQtyIssuedDetails() {
        //echo '<pre>'; print_r($_REQUEST); die('die');
        $VarNewItemId = xssclean($this->input->post('newItemId'));
        $VarOrderId = xssclean($this->input->post('orderId'));
        $ArrNewItemQtyIssuedJxl = json_decode(xssclean($this->input->post('newItemQtyIssueDetailsJxl')),true);
        foreach($ArrNewItemQtyIssuedJxl as $value) {
            $ArrInsert = array('orderid'=>$VarOrderId,'newItemId'=>$VarNewItemId,'issuedQty'=>$value[5],'balanceQty'=>$value[6],'selected'=>$value[8]);
            $this->db->insert(KN_NEW_ITEM_QTY_ISSUED_DETAIL_JXL,$ArrInsert);
        }
    }

    public function saveDeliveryChallanPreview() {
        $VarFrom    = xssclean($this->input->post('rFrom'));
        if($VarFrom == 1) {
            $VarDcType    = xssclean($this->input->post('dcType'));
            $VarOrderId   = xssclean($this->input->post('oid'));
            $VarNewItemListId = xssclean($this->input->post('newItemId'));
            $VarSampleRequestId = xssclean($this->input->post('sampleRequestId'));
            $VarBomItemId = xssclean($this->input->post('bom_item_id'));
            $newItemDC_QtyIssueDetailsJxl = json_decode(xssclean($this->input->post('jxl')),true);
            $this->db->from('kn_bom_dc');
            $this->db->where('new_item_list_id',$VarNewItemListId);
            $this->db->where('orderid',$VarOrderId);
            $checkExists = $this->db->count_all_results();
            if($checkExists == 1) {
                //$this->db->update(KN_PREVIEW_STORES_DC,$ArrUpdate,['new_item_list_id'=>$VarNewItemListId,'orderid'=>$VarOrderId]);
                foreach($newItemDC_QtyIssueDetailsJxl as $val) {
                    $val['orderid'] = $VarOrderId;
                    $val['dc_type'] = $VarDcType;
                    $this->db->update('kn_bom_dc',$val,['new_item_list_id'=>$VarNewItemListId,'orderid'=>$VarOrderId]);
                }
                echo json_encode(array('errCode'=>1,'id'=>$VarNewItemListId));
            }
            else {
                foreach($newItemDC_QtyIssueDetailsJxl as $val) {
                    $val['new_item_list_id'] = $VarNewItemListId;
                    $val['orderid'] = $VarOrderId;
                    $val['dc_type'] = $VarDcType;
                    $val['sample_request_id'] = $VarSampleRequestId;
                    $val['bom_item_id'] = $VarBomItemId;
                    $val['date_created'] = date('Y-m-d H:i:s');
                    $this->db->insert('kn_bom_dc',$val);
                }
                if($this->db->insert_id()) {
                    echo json_encode(array('errCode'=>1,'id'=>$this->db->insert_id()));
                }
                else {
                    echo json_encode(array('errCode'=>-1,'id'=>0));
                }
            }

        }
    }

    public function bomStoresDeliveryChallan() {
        //$ArrData[] = '';
        $VarOrderId = $this->uri->segment(3);
        $VarNewItemId = $this->uri->segment(4);
        $ArrCompanyRes = $this->companymodel->fnGetCompanyInfo($this->companyid,'',$VarStatus = 1,$VarUserType = 2);
        $ArrData['ArrCompanyRes'] = $ArrCompanyRes;
        $ArrUserInfo = $this->commonmodel->getUserInfo($this->userid);
        $ArrData['fromInfo'] = $ArrUserInfo;
        $ArrData['newItemId'] = $VarNewItemId;
        $ArrData['orderId'] = $VarOrderId;
        $preview = $this->db->select('sample_request_id,new_item_dc_qty_issue_details_jxl,bom_item_id')
            ->from('kn_bom_dc')
            ->where(['new_item_list_id'=>$VarNewItemId,'orderid'=>$VarOrderId])
            ->get()
            ->row();
        //echo '<pre>'; print_r($ObjPreviewDc); die('die');
        if(!empty($preview)) {
            //foreach ($ObjPreviewDc as $preview) {
            $VarBomItemId = $preview->bom_item_id;
            $VarSampleRequestId = $preview->sample_request_id;
            $ObjSampleRequestInfo = $this->db->select('a.merchantid,a.mgmtid,a.jsondatagrid,bomissuedto,bomindentcutoffdatetime')
                ->from('kn_sample_request'. ' AS s')
                ->join('kn_allrequest'. ' AS a','a.id = s.requestrefid')
                ->where(['requestrefid'=>$VarSampleRequestId,'a.orderid'=>$VarOrderId])
                ->get()
                ->row();
            $merchantInfo = $this->commonmodel->getUserInfo($ObjSampleRequestInfo->merchantid);
            $mgmtInfo = $this->commonmodel->getUserInfo($ObjSampleRequestInfo->mgmtid);
            $jsonDataGrid = $ObjSampleRequestInfo->jsondatagrid;
            $bomIssuedTo  = $ObjSampleRequestInfo->bomissuedto;
            $bomIndentCutOffDatetime = $ObjSampleRequestInfo->bomindentcutoffdatetime;
            //$ArrData['from'] = $this->userid;
            $VarAllBomTblName = 'bom_companyid_'.$this->companyid;
            $bom = $this->db->select('itemdesc,garmentsize,itemcode,itemcolorcode,sizeordim,uom1,planbomqty,uom2')
                ->where(array('id'=>$VarBomItemId,'orderid'=>$VarOrderId))
                ->from($VarAllBomTblName)
                ->get()
                ->row();
            $ArrJson = json_decode($preview->new_item_dc_qty_issue_details_jxl,true);
            $VarIssuedQty = '';
            if(!empty($ArrJson)) {
                if(!empty($ArrJson[0][6]))
                    $VarIssuedQty = $ArrJson[0][6];
            }
            $ArrBom[] = array($bom->itemdesc,$bom->garmentsize,$bom->itemcode,$bom->itemcolorcode,$bom->sizeordim,
                $bom->uom1,$VarIssuedQty,$bom->uom2);
            //}
            //echo '<pre>'; print_r($ArrBom); die('die');
            $ArrData['merchantInfo'] = $merchantInfo;
            $ArrData['mgmtInfo'] = $mgmtInfo;
            $ArrData['jsonDataGrid'] = $jsonDataGrid;
            $ArrData['bomIssuedTo'] = $bomIssuedTo;
            $ArrData['bomIndentCutOffDatetime'] = $bomIndentCutOffDatetime;
            $ArrData['matDetail'] = json_encode($ArrBom);
        }
        //echo '<pre>'; print_r($ArrData); die('die');
        $this->load->view('misc/storesIntDelChallan',$ArrData);
    }


    // ******** purchase indent list ******** //
    
    public function purchaseindentlist() {
          $data['brands'] = $this->getBrandList();
         $this->load->view('request/bom/store/purchase_indent_list',$data);
    }
    public function getBrandList() 
    {
        header('Access-Control-Allow-Origin: *');
        $output = $this->bom_store_model->getBrandListt();
        return $output;
    }
     public function purchaseindentlistBOM1() {
        $data['brands'] = $this->getBrandList();
        $this->load->view('request/bom/store/purchase_indent_list_BOM1', $data);
    }
     public function purchaseindentlistBOM2() {
        $data['brands'] = $this->getBrandList();
        $this->load->view('request/bom/store/purchase_indent_list_BOM2', $data);
    }

    public function surpluspurchaseindentlist() {
         $data['brands'] = $this->getBrandList();
        $this->load->view('request/bom/store/surplus_purchase_indent_list', $data);
    }
     public function surpluspurchaseindentlistBOM1() {
         $data['brands'] = $this->getBrandList();
        $this->load->view('request/bom/store/surplus_purchase_indent_list_BOM1', $data);
    }
     public function surpluspurchaseindentlistBOM2() {
         $data['brands'] = $this->getBrandList();
        $this->load->view('request/bom/store/surplus_purchase_indent_list_BOM2', $data);
    }

    public function getPurchaseIndentList() {
        $data = $this->bom_store_model->getPurchaseIndentListt();
        echo json_encode($data);
    }
    public function getPurchaseIndentListBOM1() {
        $data = $this->bom_store_model->getPurchaseIndentListtBOM1();
        echo json_encode($data);
    }
    public function getPurchaseIndentListBOM2() {
        $data = $this->bom_store_model->getPurchaseIndentListtBOM2();
        echo json_encode($data);
    }

    public function getSurplusPurchaseIndentList() {
        $data = $this->bom_store_model->getSurplusPurchaseIndentListt();
        echo json_encode($data);
    }
    public function getSurplusPurchaseIndentListBOM1() {
        $data = $this->bom_store_model->getSurplusPurchaseIndentListtBOM1();
        echo json_encode($data);
    }
    public function getSurplusPurchaseIndentListBOM2() {
        $data = $this->bom_store_model->getSurplusPurchaseIndentListtBOM2();
        echo json_encode($data);
    }

    // ******** supply closure list ******** //

    public function supplyclosurelist() {
         $data['brands'] = $this->getBrandList();
        $this->load->view('request/bom/store/supply_closure_list', $data);
    }
    public function supplyclosurelistBOM1() {
         $data['brands'] = $this->getBrandList();
        $this->load->view('request/bom/store/supply_closure_list_BOM1', $data);
    }
    public function supplyclosurelistBOM2() {
         $data['brands'] = $this->getBrandList();
        $this->load->view('request/bom/store/supply_closure_list_BOM2', $data);
    }
    
    public function getSupplyclosurelist() {
        $data = $this->bom_store_model->getSupplyclosurelistt();
        echo json_encode($data);
    }
    public function getSupplyclosurelistBOM1() {
        $data = $this->bom_store_model->getSupplyclosurelisttBOM1();
        echo json_encode($data);
    }
    public function getSupplyclosurelistBOM2() {
        $data = $this->bom_store_model->getSupplyclosurelisttBOM2();
        echo json_encode($data);
    }
    
    public function mireceivedlist() {
         $data['brands'] = $this->getBrandList();
        $this->load->view('request/bom/store/mi_received_list', $data);
    }


    public function getBOMMIReceivedList() {
        $data = $this->bom_store_model->getBOMMIReceivedListt();
        echo json_encode($data);
    }
    
    public function mipendinglist() {
         $data['brands'] = $this->getBrandList();
        $this->load->view('request/bom/store/mi_pending_list', $data);
    }
    
    public function getBOMMIPendingList() {
        $data = $this->bom_store_model->getBOMMIPendingListt();
        echo json_encode($data);
    }
     public function mipartpendinglist() {
        $data['brands'] = $this->getBrandList();
        $this->load->view('request/bom/store/mi_partissued_list', $data);
    }
    
    public function getBOMMIpartPendingList() {
        $data = $this->bom_store_model->getBOMMIpartPendingListt();
        echo json_encode($data);
    }
    
    public function miissuedlist() {
         $data['brands'] = $this->getBrandList();
        $this->load->view('request/bom/store/mi_issued_list', $data);
    }
    
    public function getBOMMIIssuedList() {
        $data = $this->bom_store_model->getBOMMIIssuedListt();
        echo json_encode($data);
    }

    public function orderstocklist() {
        $this->load->view('request/bom/store/order_stock_list', array());
    }

    public function getOrderStockList() {
        $data = $this->bom_store_model->getOrderStockListt();
        echo json_encode($data);
    }
    
    public function orderIssuedlist() {
        $this->load->view('request/bom/store/order_issued_list', array());
    }

    public function getOrderIssuedList() {
        $data = $this->bom_store_model->getOrderIssuedListt();
        echo json_encode($data);
    }
    
    public function itemList() {
         $data['brands'] = $this->getBrandList();
        $this->load->view('request/bom/store/itemList', $data);
    }
    public function itemListBOM1() {
         $data['brands'] = $this->getBrandList();
        $this->load->view('request/bom/store/itemListBOM1', $data);
    }
    public function itemListBOM2() {
         $data['brands'] = $this->getBrandList();
        $this->load->view('request/bom/store/itemListBOM2', $data);
    }
    
    public function getItemList() {
        $data = $this->bom_store_model->getOrderStockListt_old();
        echo json_encode($data);
    }
     public function getItemListBOM1() {
        $data = $this->bom_store_model->getOrderStockListt_oldBOM1();
        echo json_encode($data);
    }
     public function getItemListBOM2() {
        $data = $this->bom_store_model->getOrderStockListt_oldBOM2();
        echo json_encode($data);
    }

    public function dclist() {
           $data['brands'] = $this->getBrandList();
        $this->load->view('request/bom/store/dclist', $data);
    }

    public function getDClist() {
        $data = $this->bom_store_model->getDClistt();
        echo json_encode($data);
    }
    
    public function orderclosurelist() {
        $data['brands'] = $this->getBrandList();
        $this->load->view('request/bom/store/orderclosurelist', $data);
    }
    public function orderclosurelistBOM1() {
         $data['brands'] = $this->getBrandList();
        $this->load->view('request/bom/store/orderclosurelistBOM1', $data);
    }
    public function orderclosurelistBOM2() {
         $data['brands'] = $this->getBrandList();
        $this->load->view('request/bom/store/orderclosurelistBOM2', $data);
    }
    
    public function getOrderClosurelist() {
        $data = $this->bom_store_model->getOrderClosurelistt();
        echo json_encode($data);
    }

    public function getOrderClosurelistBOM1() {
        $data = $this->bom_store_model->getOrderClosurelisttBOM1();
        echo json_encode($data);
    }
    public function getOrderClosurelistBOM2() {
        $data = $this->bom_store_model->getOrderClosurelisttBOM2();
        echo json_encode($data);
    }
    
    public function surplusstocklist() {
         $data['brands'] = $this->getBrandList();
        $this->load->view('request/bom/store/surplusstocklist', $data);
    }
    
    public function stockTransferMemoList() {
         $data['brands'] = $this->getBrandList();
        $this->load->view('request/purchase/stockTransferMemoList', $data);
    }


}