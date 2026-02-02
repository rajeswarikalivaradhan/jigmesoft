<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Mpurchaseuser extends CI_Controller {
    public function __construct() {
        parent::__construct();
        fnIfCheckUserLoggedIn();
        $this->load->helper('xssclean');
        $this->load->model('commonmodel');
        $this->load->model('commonusermodel');
        $this->load->model("mbompurchaserequestmodel");
        $this->load->model("mbompurcahseindentmodel");
        $this->load->model(CNFCOMPANY . 'mcadrequestmodel');
        $ArrUnitofmeasure = unserialize(ARRUNITOFMEASURE);
        foreach ($ArrUnitofmeasure as $uofmitem) $this->unitofmeasure[] = $uofmitem;
        $ArrUserLoggedInfo = fnGetUserLoggedInfo('1');
        $this->companyid = $ArrUserLoggedInfo['companyid'];
        $this->userid = $ArrUserLoggedInfo['id'];
        $this->mysqldatetime = date('Y-m-d H:i:s');
        $this->limit = LIMITPERPAGE;
        $this->ArrDbCols = array('contactname', 'desgn', 'username', 'mobile', 'status', 'updatedby', 'dateupdated');
        $this->usertype = getUserTypeId("Purchase Dept.");
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
        $this->load->view('purchase/addeditpurchaseuser', $ArrData);
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
            $config['base_url']    = base_url().CNFCOMPANY.'mpurchaseuser/manage/';
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
            $data['pagination'] = $this->pagination->create_linkswithajax('PurUser');
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
            $this->load->view('purchase/managepurchaseusers',array('ArrDesignation' => $ArrDesignation,
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
        $this->load->view('purchase/userdashboard');
    }

    // public function purchasereceivedlist() {
    //     $VarFrom = xssclean($this->input->post('rfrom'));
    //     if($VarFrom == 1) {
    //         $ArrList = $this->mbompurchaserequestmodel->recdlistDatatables();
    //         $data = array();
    //         $ArrStatus = unserialize(ARRSTATUS);
    //         $ArrBomRequirement = unserialize(ARRBOMREQUIREMENT);
    //         $ArrCommonStatus = unserialize(ORDERENQUIRYSTATUS);
    //         foreach ($ArrList as $Obj) {
    //             $row = array();
    //             $row[] = '<input type="checkbox" class="allcbox" id="' . $Obj->allid . '">';
    //             $row[] = '<a href="' . base_url(CNFCOMPANY.'mpurchaseuser/queuenoassign') . '/' . urlencode(base64_encode($Obj->allid)) . '">' . $Obj->isriorcode . '</a>';
    //             $row[] = $Obj->brandname;
    //             $row[] = $ArrBomRequirement[$Obj->articletypeid];
    //             $row[] = $Obj->reqType;
    //             $row[] = $Obj->formattedDateCreated;
    //             $row[] = $Obj->formattedCutOffDt;
    //             $row[] = $Obj->appType;
    //             $row[] = $Obj->mgmt;
    //             $row[] = $Obj->merchant;
    //             if($Obj->deptcurrentstatus == 1) {
    //                 $VarCs = 'MANAGEMENT '.$ArrCommonStatus[$Obj->mgmtcurrentstatus];
    //             }
    //             else {
    //                 $VarCs = 'PURCHASE DEPT. '.$ArrCommonStatus[$Obj->deptcurrentstatus];
    //             }
    //             $row[] = '<a href="#">'.$VarCs.'</a>';
    //             $row[] = $Obj->formattedDateUpdated;
    //             $row[] = $ArrStatus[$Obj->status];
    //             $data[] = $row;
    //         }
    //         $output = array(
    //             "draw" => $_POST['draw'],
    //             "recordsTotal" => $this->mbompurchaserequestmodel->count_RecdList(),
    //             "recordsFiltered" => $this->mbompurchaserequestmodel->count_recdListFiltered(),
    //             "data" => $data,
    //         );
    //         echo json_encode($output);
    //     }
    //     else {
    //         $this->load->view('purchase/reqreceivedlist', array());
    //     }
    // }

    // public function bomPurchaseReqQueueList() {
    //     $VarFrom = xssclean($this->input->post('rFrom'));
    //     if($VarFrom == 1) {
    //         $ArrList = $this->mbompurchaserequestmodel->bomQueueListDataTables();
    //         $data = array();
    //         $ArrStatus = unserialize(ARRSTATUS);
    //         $ArrORDERENQUIRYSTATUS = unserialize(ORDERENQUIRYSTATUS);
    //         $ArrBomRequestRequirement = unserialize(ARRBOMREQUIREMENT);
    //         foreach ($ArrList as $Obj) {
    //             $row = array();
    //             $row[] = '<input type="checkbox" class="allcbox" id="' . $Obj->id . '">';
    //             $row[] = $Obj->isriorcode;
    //             $row[] = $Obj->brandname;
    //             $row[] = '<a href="' . base_url('bompurchaseindent/preprarebompind') . '/' . urlencode(base64_encode($Obj->id)) . '">' . $Obj->queueno . '</a>';
    //             $row[] = $ArrBomRequestRequirement[$Obj->requirementforbom];
    //             $row[] = $Obj->formattedDateCreated;
    //             $row[] = $Obj->formattedCutOffDt;
    //             $row[] = $Obj->appType;
    //             $row[] = $Obj->mgmt;
    //             $row[] = $Obj->merchant;
    //             if ($Obj->deptcurrentstatus == 0) {
    //                 $VarCs = '-';
    //             } elseif ($Obj->queuecompletestatus == 1) {
    //                 $VarCs = 'JOB DONE';
    //             } elseif ($Obj->queuecompletestatus == 2) {
    //                 $VarCs = 'RE SCHEDULED';
    //             } else {
    //                 $VarCs = $ArrORDERENQUIRYSTATUS[$Obj->deptcurrentstatus];
    //             }
    //             $row[] = $VarCs;
    //             $row[] = $Obj->formattedDateUpdated;
    //             $row[] = $ArrStatus[$Obj->status];
    //             $data[] = $row;
    //         }
    //         $output = array(
    //             "draw" => $_POST['draw'],
    //             "recordsTotal" => $this->mbompurchaserequestmodel->bomQueueListCountAll(),
    //             "recordsFiltered" => $this->mbompurchaserequestmodel->bomQueueListCountFiltered(),
    //             "data" => $data,
    //         );
    //         echo json_encode($output);
    //     }
    //     else {
    //         $this->load->view('purchase/bomPurchaseReqQueuelist', array());
    //     }
    // }

    /*******************************Data Tables END******************************************************************************************/
    public function queuenoassign() {
        $VarId = base64_decode(urldecode($this->uri->segment(4)));
        $this->load->model(CNFCOMPANY . 'orderentrymodel');
        if ($VarId > 0) {
            $ArrPurpose = [];
            $RequestData = $this->mbompurchaserequestmodel->getBomPurchaseRequest($VarId, $this->companyid);
            $VarOrderId = $RequestData->orderid;
            $VarCommonOrderEntryInfo = $this->commonmodel->commonBasicInfoOrderEntry($VarOrderId, $this->companyid);
            $ArrOrderEnqData = $this->orderentrymodel->fnGetOrderEnquiryInfo($VarOrderId, $this->companyid,$VarStatus = 1);
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
                foreach ($ArrObjPurpose as $purpose) $ArrPurpose[] = $purpose->purpose;
            }
            $ArrData['ArrPurpose'] = $ArrPurpose;
            $ArrData['ArrBasicInfo'] = $RequestData;
            $ArrData['VarArtType'] = $RequestData->articletypeid;
            $ArrData['VarId'] = $VarId;
            $this->load->view('purchase/queuenoassign', $ArrData);
        }
    }

    public function fnCheckPinForQueueNo() {
        $this->load->model(CNFCOMPANY . 'orderentrymodel');
        $this->load->model(CNFCOMPANY . 'bommodel');
        $VarPwd = xssclean($this->input->post('i'));
        $VarRequestId = xssclean($this->input->post('rid'));
        $VarApproveReject = xssclean($this->input->post('s'));
        $VarRemarks = xssclean($this->input->post('rem'));
        $VarIsrIorCode = xssclean($this->input->post('isriorcode'));
        $VarRequestListType = "BOM";
        $VarQueueNoRes = $this->commonmodel->getAllQueueNo($VarRequestListType, $this->companyid);
        if(empty($VarQueueNoRes)) {
            $Qno = 1;
        }
        elseif (empty($VarQueueNoRes->qid)) {
            $Qno = 1;
        }
        else {
            $Qno = $VarQueueNoRes->qid + 1;
        }
        if ($this->commonmodel->fnValidatePin($this->userid, $VarPwd)) {
            $VarQno = $VarIsrIorCode . '/' . BOMQNO_PREFIX . $Qno;
            if ($VarApproveReject == 2) {
                $VarMsg = 'BOM Purchase Request Approved';
            } else {
                $VarMsg = 'BOM Purchase Request Rejected';
            }
            $ArrValues = array(
                'alldeptid' => $this->userid,
                'queueno' => $VarQno,
                'qid'=>$Qno,
                'queueno_assigned_date' => date('Y-m-d H:i:s'),
                'deptremarks' => $VarRemarks,
                'deptcurrentstatus' => $VarApproveReject,
                'dateupdated' => date('Y-m-d H:i:s'),
                'requestrefno' => 0
            );
            $Res = $this->mbompurchaserequestmodel->saveBomQueueNo($VarRequestId, $ArrValues);
            /** Save bom items from order entry
             * to db for purchase indent approval request (Dynamic Table)
             *
             */
            /**remove this instead use id (bomitemid)**/
            $RequestData = $this->mbompurchaserequestmodel->getBomPurchaseRequest($VarRequestId, $this->companyid);
            $articleTypeId = $RequestData->articletypeid;
            $VarOrderId = $RequestData->orderid;
            $jsonFromTwelfthRes = $this->bommodel->getConsolidated($VarOrderId, $this->companyid, $articleTypeId);
            //$jsonFromTwelfthRes = $this->orderentrymodel->getFromBomConsTwelfth($VarOrderId, $this->companyid, $articleTypeId, 'asc');
            $consolidatedForBomPI = json_decode($jsonFromTwelfthRes[0]['jsondatagrid']);
            $dynamicTblName = '';
            if (!empty($RequestData->bompirequestgrid_tblname)) {
                $dynamicTblName = $RequestData->bompirequestgrid_tblname;
            }
            foreach ($consolidatedForBomPI as $bom) {
                $ArrBomData[] = array(
                    'companyid'=>$this->companyid,
                    'orderid'=>$VarOrderId,
                    'itemdesc' => $bom[0],
                    'garmentsize' => $bom[1],
                    'itemcode' => $bom[2],
                    'itemcolorcode' => $bom[3],
                    'sizeordim' => $bom[4],
                    'uom1' => $bom[5],
                    'planbomqty'=>$bom[9]
                );
            }
            $dynamicTblRows = $this->mbompurcahseindentmodel->createBomPurIndApprRequestTbl($ArrBomData, $dynamicTblName, $VarRequestId);
            if ($Res) {
                echo json_encode(array('errcode' => '1', 'qno' => $VarQno, 'qnodatetime' => date('d-m-Y H:i:s'), 'msg' => $VarMsg,
                    'approvereject' => $VarApproveReject));
            } else {
                echo json_encode(array('errcode' => '-1', 'qno' => 0, 'qnodatetime' => date('d-m-Y H:i:s'), 'msg' => $VarMsg));
            }
        } else {
            echo json_encode(array('errcode' => '-1', 'qno' => '0', 'qnodatetime' => '0', 'msg' => 'Invalid PIN', 'approvereject' => ''));
        }
    }

    public function updatePurchaseIndent() {
        $VarFrom = xssclean($this->input->post('rfrom'));
        if ($VarFrom == 1) {
            //'bomPIndentJxlData'
            $VarOrderId 	= xssclean($this->input->post('oid'));
            $VarBomPurReqId = xssclean($this->input->post('bomPurReqId'));
            $VarPiNo 		= BOMPURIND_PREFIX.$VarBomPurReqId;
            $VarBomPurIndentId = xssclean($this->input->post('bomPurIndentId'));
            $jsonBomPIFinal    = xssclean($this->input->post('bomPIndentJxlData'));
            $BomPIFinal		   = json_decode(xssclean($this->input->post('bomPIndentJxlData')));
            $TaxTypeId 		   = xssclean($this->input->post('TaxTypeId'));
            $ArrUpdateData 	   = $ArrIdsAndData = [];
            $VarVendorId = xssclean($this->input->post('vendorId'));
            if (empty(xssclean($this->input->post('supplyDate'))))
                $VarAgreedSupDate = '0000-00-00';
            else
                $VarAgreedSupDate = date('Y-m-d', strtotime(xssclean($this->input->post('supplyDate'))));
            $paymentTerms = xssclean($this->input->post('paymentterms'));
            $VarAmountinWords = xssclean($this->input->post('amountinwords'));
            $VarRemarks = xssclean($this->input->post('remarks'));
            $VarPurchaserName = xssclean($this->input->post('pname'));
            $VarPurchaserMobile = xssclean($this->input->post('pmobile'));
            $VarPurchaserEmail = xssclean($this->input->post('pemail'));
            $VarVendorName = xssclean($this->input->post('vname'));
            $VarVendorMobile = xssclean($this->input->post('vmobile'));
            $VarVendorEmail = xssclean($this->input->post('vemail'));
            $jsonAdvPaymentJxl = xssclean($this->input->post('advPaymentRequestJxl'));

            $ArrBomPi = array(
                'bompurrequestid' => $VarBomPurReqId,
                'orderid' => $VarOrderId,
                //'bomitemidanddata'=>serialize($ArrIdsAndData),
                'taxtype' => $TaxTypeId,
                'purchaseindent_no' => $VarPiNo,
                'purchaseindgrid'=>$jsonBomPIFinal,
                'purchasedeptid' => $this->userid,
                'vendorid' => $VarVendorId,
                'agreedsupplydate' => $VarAgreedSupDate,
                'paymentterms' => $paymentTerms,
                'amountinwords' => $VarAmountinWords,
                'purdeptremarks' => $VarRemarks,
                'purchasername' => $VarPurchaserName,
                'purchasermobile' => $VarPurchaserMobile,
                'purchaseremail' => $VarPurchaserEmail,
                'xtravendorname' => $VarVendorName,
                'xtravendormobile' => $VarVendorMobile,
                'xtravendoremail' => $VarVendorEmail,
                'advPaymentReqJxl' => $jsonAdvPaymentJxl,
                'status' => '1',
                'dateupdated' => $this->mysqldatetime
            );
            if(empty($VarBomPurIndentId)) {
                $ArrBomPi['datecreated'] = $this->mysqldatetime;
            }
            $allBomPurchaseIndent = [];
            if ($VarInsertId = $this->mbompurcahseindentmodel->saveBomPurchaseIndent($ArrBomPi, $VarBomPurIndentId)) {
                if($TaxTypeId == 1) {
                    foreach ($BomPIFinal as $BomPIFinalItem) {
                        if($BomPIFinalItem[16] == 1) {
                            /*$bomItemId = $this->commonmodel->getAllBom($VarOrderId,$BomPIFinalItem[0],$BomPIFinalItem[1],
                                $BomPIFinalItem[2],$BomPIFinalItem[3],$BomPIFinalItem[4],$BomPIFinalItem[5]);*/

                            $allBomPurchaseIndent[] = array('orderid'=>$VarOrderId,'bomPurchaseRequestId'=>$VarBomPurReqId,
                                'bompurchaseindentid'=>$VarInsertId,'bom_item_id'=>$BomPIFinalItem[15],'progbomqty'=>$BomPIFinalItem[6]
                            );

                            $ArrUpdateData[] = array('amount'=>$BomPIFinalItem[9],'sgstpercent'=>$BomPIFinalItem[10],'sgstvalue'=>$BomPIFinalItem[11],
                                'cgstpercent'=>$BomPIFinalItem[12],'cgstvalue'=>$BomPIFinalItem[13],'subtotal'=>$BomPIFinalItem[14],
                                'id'=>$BomPIFinalItem[15], 'selectcheckbox'=>$BomPIFinalItem[16],'tempselect'=>0,'hiddenstatus'=>1,'status'=>1,
                                'bomPurchaseRequestId'=>$VarBomPurReqId,'bomPurchaseIndentId'=>$VarInsertId);
                        }
                    }
                }
                elseif ($TaxTypeId == 2) {
                    foreach ($BomPIFinal as $BomPIFinalItem) {
                        if($BomPIFinalItem[14] == 1) {
                            $allBomPurchaseIndent[] = array('orderid'=>$VarOrderId,'bomPurchaseRequestId'=>$VarBomPurReqId,
                                'bompurchaseindentid'=>$VarInsertId,'bom_item_id'=>$BomPIFinalItem[13],'progbomqty'=>$BomPIFinalItem[6]
                            );
                            $ArrUpdateData[] = array('amount'=>$BomPIFinalItem[9],
                                'igstpercent'=>$BomPIFinalItem[10],'igstvalue'=>$BomPIFinalItem[11],'subtotal'=>$BomPIFinalItem[12],
                                'id'=>$BomPIFinalItem[13], 'selectcheckbox'=>$BomPIFinalItem[14],'tempselect'=>0,'hiddenstatus'=>1,'status'=>1,
                                'bomPurchaseRequestId'=>$VarBomPurReqId,'bomPurchaseIndentId'=>$VarInsertId);
                        }
                    }
                }
                else {
                    foreach ($BomPIFinal as $BomPIFinalItem) {
                        if($BomPIFinalItem[14] == 1) {
                            $allBomPurchaseIndent[] = array('companyid'=>$this->companyid,'orderid'=>$VarOrderId,'bomPurchaseRequestId'=>$VarBomPurReqId,
                                'bompurchaseindentid'=>$VarInsertId,'bom_item_id'=>$BomPIFinalItem[13],'progbomqty'=>$BomPIFinalItem[6]
                            );
                            $ArrUpdateData[] = array('amount'=>$BomPIFinalItem[9],
                                'dutypercent'=>$BomPIFinalItem[10],'dutyvalue'=>$BomPIFinalItem[11],'subtotal'=>$BomPIFinalItem[12],
                                'id'=>$BomPIFinalItem[13], 'selectcheckbox'=>$BomPIFinalItem[14],'tempselect'=>0,'hiddenstatus'=>1,'status'=>1,
                                'bomPurchaseRequestId'=>$VarBomPurReqId,'bomPurchaseIndentId'=>$VarInsertId);
                        }
                    }
                }
                $ArrBomPurRequestInfo = $this->mbompurchaserequestmodel->getBomPurchaseRequest($VarBomPurReqId, $this->companyid);
                $dynamicTblName = $ArrBomPurRequestInfo->bompirequestgrid_tblname;
                /*$VarTblName 	= '';
                echo '<pre>'; print_r($ArrUpdateData);
                echo '<pre>'; print_r($VarTblName);
                echo '<pre>'; print_r($allBomPurchaseIndent); die('die');*/
                $this->mbompurcahseindentmodel->savePurchaseIndentFinal($ArrUpdateData,$dynamicTblName,$allBomPurchaseIndent);
                echo json_encode(array('errcode' => 1,'msg'=>'Payment Details Saved','id'=>$VarInsertId));
            } else {
                echo json_encode(array('errcode' => -1,'msg'=>'','id'=>0));
            }
        }
    }

    public function getVendorsInfo() {
        $VarId = xssclean($this->input->post('vid'));
        if ($VarId <> '') {
            $VarSqlVendor = "SELECT ven.id,address,vendorname,contactpersonname,phone,emailid,mobile,gstno,iecode,
bankname,accountname,accountno,ifscode,rtgs,swiftcode,iban FROM " . KN_MASTER_BOM_VENDOR . " AS ven 
WHERE ven.companyid = '$this->companyid' AND ven.id = '$VarId'";
            $ObjVendor = $this->db->query($VarSqlVendor)->row();
            if (empty($ObjVendor)) {
                echo json_encode(array('errCode' => -1, 'vendorBankJxl' => '','vendorDetails'=>''));
            } else {

                $ArrVendorBankData[] = array($ObjVendor->vendorname,$ObjVendor->bankname,$ObjVendor->accountname,
                    $ObjVendor->accountno,$ObjVendor->ifscode,$ObjVendor->rtgs,$ObjVendor->swiftcode,$ObjVendor->iban);

                echo json_encode(array('errCode' => 1, 'vendorBankJxl' => $ArrVendorBankData,'vendorDetails'=>$ObjVendor));
            }
        } else {
            echo json_encode(array('errCode' => -1, 'vendorBankJxl' => '','vendorDetails'=>''));
        }
    }

    public function updateBomPaymentReq() {
        $VarAdvPayment = xssclean($this->input->post('advancepayment'));
        $VarBillPayment = xssclean($this->input->post('billpayment'));
        $VarReplyForUpdateId = xssclean($this->input->post('id'));
        $VarBompurchaseinvoiceid = xssclean($this->input->post('bompurchaseinvoiceid'));
        $VarBompurchaseindreqid = xssclean($this->input->post('bompurchaseindreqid'));
        $VarCurrency = xssclean($this->input->post('frmCurrency'));

        if ($VarAdvPayment) {
            $VarProformaNo = xssclean($this->input->post('proformaNo'));
            if (xssclean($this->input->post('proformaDate')))
                $VarProformaDate = date('Y-m-d', strtotime(xssclean($this->input->post('proformaDate'))));
            else
                $VarProformaDate = NULL;
            $VarProformaValue = xssclean($this->input->post('proformaValue'));
            $VarAdvPayable = xssclean($this->input->post('advPayable'));
            $VarReqModeofpay = xssclean($this->input->post('ReqdModeOfPayment'));

            if (xssclean($this->input->post('payByDate')))
                $VarPaybyDate = date('Y-m-d', strtotime(xssclean($this->input->post('payByDate'))));
            else
                $VarPaybyDate = NULL;
            $ArrUpdate = array(
                'companyid' => $this->companyid,
                'bompurchaseinvoiceid' => $VarBompurchaseinvoiceid,
                'bompurchaseindreqid' => $VarBompurchaseindreqid,
                'proformano' => $VarProformaNo,
                'proformadate' => $VarProformaDate,
                'proformavalue' => $VarProformaValue,
                'advpayable' => $VarAdvPayable,
                'reqmodeofpayment' => $VarReqModeofpay,
                'paybydate' => $VarPaybyDate,
                'datecreated' => $this->mysqldatetime,
                'dateupdated' => $this->mysqldatetime,
                'status' => '1',
                'advancepayment' => $VarAdvPayment,
                'currency' => $VarCurrency
            );
        } elseif ($VarBillPayment) {
            $VarInvoiceNo = xssclean($this->input->post('invoiceNo'));
            if (empty(xssclean($this->input->post('invoiceDate')))) {
                $VarInvoiceDate = NULL;
            } else {
                $VarInvoiceDate = date('Y-m-d', strtotime(xssclean($this->input->post('invoiceDate'))));
            }
            $VarInvoiceValue = xssclean($this->input->post('invoiceValue'));
            $VarDebitValue = xssclean($this->input->post('debitValue'));
            $VarAmountPayable = xssclean($this->input->post('amountPayable'));
            $VarReqdModeOfPayment = xssclean($this->input->post('ReqdModeOfPayment'));
            if (empty(xssclean($this->input->post('paymentDueDate')))) {
                $VarPaymentDueDate = NULL;
            } else {
                $VarPaymentDueDate = date('Y-m-d', strtotime(xssclean($this->input->post('paymentDueDate'))));
            }
            //$VarApprStatus           = xssclean($this->input->post('ApprStatus'));
            $ArrUpdate = array(
                'companyid' => $this->companyid,
                'bompurchaseinvoiceid' => $VarBompurchaseinvoiceid,
                'bompurchaseindreqid' => $VarBompurchaseindreqid,
                'reqmodeofpayment' => $VarReqdModeOfPayment,
                'invoiceno' => $VarInvoiceNo,
                'invoicedate' => $VarInvoiceDate,
                'invoicevalue' => $VarInvoiceValue,
                'debitvalue' => $VarDebitValue,
                'amountpayable' => $VarAmountPayable,
                'paymentduedate' => $VarPaymentDueDate,
                'datecreated' => $this->mysqldatetime,
                'dateupdated' => $this->mysqldatetime,
                'status' => '1',
                'billpayment' => $VarBillPayment,
                'currency' => $VarCurrency
            );
        } else {
            //
        }
        $Res = $this->saveBomPaymentReq($ArrUpdate, $VarReplyForUpdateId);
        echo json_encode($Res);
    }

    public function saveBomPaymentReq($ArrUpdate = array(), $VarReplyForUpdate = '') {
        if (empty($VarReplyForUpdate)) {
            $this->db->insert(KN_BOMPAYMENT_REQUEST, $ArrUpdate);
            $VarId = $this->db->insert_id();
            if ($VarId) {
                $ArrResult['errcode'] = 1;
                $ArrResult['msg'] = '';
                $ArrResult['eid'] = urlencode(base64_encode($VarId));
                return $ArrResult;
            }
        } else {
            $this->db->where('id', $VarReplyForUpdate);
            $this->db->update(KN_BOMPAYMENT_REQUEST, $ArrUpdate);
            if ($this->db->affected_rows()) {
                $ArrResult['errcode'] = 1;
                $ArrResult['msg'] = '';
                $ArrResult['eid'] = urlencode(base64_encode($VarReplyForUpdate));
                return $ArrResult;
            }
        }
    }

    // *********************************** // 
    // NEW ********* //
    // *********************************** //

    // *** PURCHASE REQUEST RECEIVE LIST STARTS HERE *** //
    public function purchasereceivedlist() {
         $data['brands'] = $this->getBrandList();
        $this->load->view('purchase/reqreceivedlist',$data);
    }
    public function getBrandList() 
    {
        header('Access-Control-Allow-Origin: *');
        $output = $this->mbompurchaserequestmodel->getBrandListt();
        return $output;
    }
    public function getPurchaseList() {
        $data = $this->mbompurchaserequestmodel->getPurchaseListt();
        echo json_encode($data);
    }

    public function purchasereceivedlistbom1() {
         $data['brands'] = $this->getBrandList();
        $this->load->view('purchase/reqreceivedlistbom1', $data);
    }
    
    public function getPurchaseListbom1() {
        $data = $this->mbompurchaserequestmodel->getPurchaseListtbom1();
        echo json_encode($data);
    }

     public function purchasereceivedlistbom2() {
         $data['brands'] = $this->getBrandList();
        $this->load->view('purchase/reqreceivedlistbom2', $data);
    }
    
    public function getPurchaseListbom2() {
        $data = $this->mbompurchaserequestmodel->getPurchaseListtbom2();
        echo json_encode($data);
    }


    
    // *** PURCHASE QUEUE LIST STARTS HERE *** //
    public function bomPurchaseReqQueueList() {
        $data['brands'] = $this->getBrandList();
        $this->load->view('purchase/bomPurchaseReqQueuelist', $data);
    }
    
    public function getBomQueueList() {
        $data = $this->mbompurchaserequestmodel->getBomQueueListt();
        echo json_encode($data);
    }

    public function bom1PurchaseReqQueueList() {
         $data['brands'] = $this->getBrandList();
        $this->load->view('purchase/bom1PurchaseReqQueuelist', $data);
    }
    
    public function getBom1QueueList() {
        $data = $this->mbompurchaserequestmodel->getBom1QueueListt();
        echo json_encode($data);
    }
    public function bom2PurchaseReqQueueList() {
        $data['brands'] = $this->getBrandList();
        $this->load->view('purchase/bom2PurchaseReqQueuelist', $data);
    }
    
    public function getBom2QueueList() {
        $data = $this->mbompurchaserequestmodel->getBom2QueueListt();
        echo json_encode($data);
    }
    
    // *** PURCHASE QUEUE LIST STARTS HERE *** //
    public function purchasesentlist() {
         $data['brands'] = $this->getBrandList();
        $this->load->view('request/purchase/purchase_sent_list', $data);
    }

    public function getBomPurchaseSentList() {
        $data = $this->mbompurchaserequestmodel->getBomPurchaseSentListt();
        echo json_encode($data);
    }

    public function purchasesentlistbom1() {
        $data['brands'] = $this->getBrandList();
        $this->load->view('request/purchase/purchase_sent_list_bom1', $data);
    }

    public function getBom1PurchaseSentList() {
        $data = $this->mbompurchaserequestmodel->getBom1PurchaseSentListt();
        echo json_encode($data);
    }

    public function purchasesentlistbom2() {
         $data['brands'] = $this->getBrandList();
        $this->load->view('request/purchase/purchase_sent_list_bom2', $data);
    }

    public function getBom2PurchaseSentList() {
        $data = $this->mbompurchaserequestmodel->getBom2PurchaseSentListt();
        echo json_encode($data);
    }

    public function purchaseindentlist()
    {
        $data['brands'] = $this->getBrandList();
        $this->load->view('request/purchase/purchase_indent_list', $data);
    }

     public function purchaseindentlistbom1()
    {
        $data['brands'] = $this->getBrandList();
        $this->load->view('request/purchase/purchase_indent_list_bom1', $data);
    }

     public function purchaseindentlistbom2()
    {
        $this->load->view('request/purchase/purchase_indent_list_bom2', array());
    }

    
    public function getBomPurchaseIndentList() {
        $data = $this->mbompurchaserequestmodel->getBomPurchaseIndentListt();
        echo json_encode($data);
    }
     public function getBomPurchaseIndentListbom1() {
        $data = $this->mbompurchaserequestmodel->getBomPurchaseIndentListtbom1();
        echo json_encode($data);
    }
    public function getBomPurchaseIndentListbom2() {
        $data = $this->mbompurchaserequestmodel->getBomPurchaseIndentListtbom2();
        echo json_encode($data);
    }

    public function billpaidlist()
    {
        $data['brands'] = $this->getBrandList();
        $this->load->view('request/purchase/bill_paid_list', $data);
    }

    public function getMgmtBillPaidList() {
        $data = $this->mbompurchaserequestmodel->getMgmtBillPaidListt();
        echo json_encode($data);
    }
    
    public function stockTransferMemoList() {
          $data['brands'] = $this->getBrandList();
        $this->load->view('request/purchase/stockTransferMemoList', $data);
    }
    
    public function getstocktransferlist() {
        $data = $this->mbompurchaserequestmodel->getstocktransferlistt();
        echo json_encode($data);
    }
    
    public function stocktransferdetails() {
            $VarId = $this->uri->segment(4);
            $stm_ref_no1 = $this->uri->segment(6); 
            if ($VarId <> '' && is_numeric(base64_decode(urldecode($VarId))))
            {
                $VarEnqId       = base64_decode(urldecode($VarId));
            }
            if ($stm_ref_no1 <> '' && base64_decode(urldecode($stm_ref_no1)))
            {
                $stm_ref_no = base64_decode(urldecode($stm_ref_no1));
            }
            // echo $stm_ref_no; exit;
            $ArrCommonHeaderData = $this->commonheaderdata($VarEnqId);
            $surplusData = $this->RequestBomModel->getsurplusData($stm_ref_no);
            //$requestData = $this->RequestBomModel->getRequestDataa($VarEnqId, $reqEnqId);
            //print_r($surplusData); exit;
            
            $this->load->view('request/purchase/stocktransferdetails', 
                         array('VarEnqId'=>$VarEnqId,
                               'ArrCommonHeaderData' => $ArrCommonHeaderData,
                               'requestData' => $requestData,
                               'surplusData' => $surplusData,
                               'stm_ref_no' => $stm_ref_no
                            ));
    }
    
}