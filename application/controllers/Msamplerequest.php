<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Msamplerequest extends CI_Controller
{
    public $companyid;
    public $userid;
    public $mysqldatetime;
    public $datetime;

    public function __construct()
    {
        parent::__construct();
        error_reporting(E_ALL);
        $this->load->helper('xssclean');
        $this->load->helper('download');
        fnIfCheckUserLoggedIn();
        $ArrUserLoggedInfo = fnGetUserLoggedInfo('1');
        $this->companyid = $ArrUserLoggedInfo['companyid'];
        $this->userid = $ArrUserLoggedInfo['id'];
        $this->mysqldatetime = date('Y-m-d H:i:s');
        $this->datetime = date('d-m-Y H:i:s');
        $this->load->model(CNFCOMPANY . 'mcadrequestmodel');
        $this->load->model(CNFCOMPANY . 'msamplerequestmodel');
        $this->load->model(CNFCOMPANY . "orderentrymodel");
        $this->load->model("commonmodel");
        $this->load->model(CNFCOMPANY."bommodel");
    }

    public function addeditsamplerequest() {
        $VarFrom = xssclean($this->input->post('rFrom'));
        if($VarFrom == 1) {
            $this->load->model(CNFCOMPANY."bommodel");
            $VarEnqId = xssclean($this->input->post('enqId'));
            $jsonFourthRes = $this->orderentrymodel->getFromNewFourthTbl($VarEnqId, $this->companyid);
            if (!empty($jsonFourthRes->jsondatagrid)) {
                $jsonFourth = $jsonFourthRes->jsondatagrid;
            }
            $ArrSizeChartData = $this->orderentrymodel->getSizeChart($VarEnqId, $this->companyid);
            if(!empty($ArrSizeChartData->sizechartvalue)) {
                $ArrFinalSizes = explode(',',$ArrSizeChartData->sizechartvalue);
            }
            $ArrFinalSizes[] = "All";
            echo json_encode(array('jsonFourth'=>$jsonFourth,'ArrFinalSizes'=>$ArrFinalSizes));
        }
        else {
            $VarEdit = $this->uri->segment(3);
            if ($VarEdit == 'edit') {
                $VarSampleRequestId = base64_decode(urldecode($this->uri->segment(4)));
                $ArrSamRequestData = $this->msamplerequestmodel->getSamRequestData($VarSampleRequestId, $this->companyid);
                $VarOrderId = $ArrSamRequestData[0]->orderid;
                $jsonDataGrid = $ArrSamRequestData[0]->jsondatagrid;
            } else {
                $VarSampleRequestId = 0;
                $VarOrderId = base64_decode(urldecode($this->uri->segment(3)));
                $ArrSamRequestData = $jsonDataGrid = $cadIndentDetails = $fabIndentDetails = $bomIndentDetails = '';
            }



            $ArrSizeSpecCode = $this->orderentrymodel->getAllSizeSpecCode($VarOrderId, $this->companyid);
            $ArrAllPoNumber = $this->orderentrymodel->getAllPoNumber($VarOrderId, $this->companyid);
            $prevSampleRefNoRes = $this->msamplerequestmodel->prevSampleRefNo($VarOrderId);
            $sampleRefNo = []; $jsonPrevSamRefNo = '';
            if (!empty($prevSampleRefNoRes)) {
                foreach ($prevSampleRefNoRes as $value) {
                    $sampleRefNoJxl = json_decode($value['samRefJxl'],true);
                    if(!empty($sampleRefNoJxl[0][6])) {
                        $sampleRefNo[] = $sampleRefNoJxl[0][6];
                    }
                    //$sampleRefNoJxl
                }
                $jsonPrevSamRefNo = json_encode($sampleRefNo);
            }
            $VarCommonOrderEntryInfo = $this->commonmodel->commonBasicInfoOrderEntry($VarOrderId, $this->companyid);
            $ArrOrderEnqData = $this->orderentrymodel->fnGetOrderEnquiryInfo($VarOrderId, $this->companyid, '');
            $ArrData = array(
                'VarReqId' => $VarSampleRequestId,
                'ArrBasicInfo' => $ArrSamRequestData,
                'ArrCompanyInfo' => $VarCommonOrderEntryInfo['ArrCompanyInfo'],
                'ArrMerchant' => $VarCommonOrderEntryInfo['ArrMerchant'],
                'ArrTeamInfo' => $VarCommonOrderEntryInfo['ArrTeamInfo'],
                'ArrOrderEnqData' => $ArrOrderEnqData[0],
                'ArrCategory' => json_encode(unserialize(ARRCADCATEGORY)),
                'ArrPrevSampleRefNo' => $jsonPrevSamRefNo,
                'brandName' => $VarCommonOrderEntryInfo['brandName'],
                'buyerName' => $VarCommonOrderEntryInfo['buyerName'],
                'ArrOrderCommonData' => $VarCommonOrderEntryInfo['ArrOrderCommonData'],
                'spc' => json_encode(array_values($ArrSizeSpecCode)),
                'ArrPoNumber' => json_encode($ArrAllPoNumber),
                'ArrRequirement' => json_encode(unserialize(ARRSAMPLEREQREQUIREMENT)),
                'orderid' => $VarOrderId,
                'jsonDataGrid' => $jsonDataGrid
            );
            $this->load->view('sam_request/addeditsamplerequest', $ArrData);
        }


    }

    public function getSampleStatusRefNoJxl() {
        $VarId = xssclean($this->input->post('id'));
        $sampleRequestRes = $this->commonmodel->fnGetAllTableInfo(KN_SAMPLE_REQUEST_MISC, 'samRefJxl',array('requestid' => $VarId));
        if(!empty($sampleRequestRes)) {
            $jsonSampleRefNoJxl = $sampleRequestRes[0]['samRefJxl'];
            //echo '<pre>'; print_r($jsonSampleRefNoJxl); die('die');
            echo json_encode(array('re'=>$jsonSampleRefNoJxl));
        }
        else {
            echo json_encode(array('re'=>''));
        }
    }

    public function matIndentJxlData() {
        $VarCcc = xssclean($this->input->post('ccc'));
        $VarOrderId = xssclean($this->input->post('oId'));
        $bomEleventhRes = $this->bommodel->getBomArticle($VarOrderId, $this->companyid);
        $ArrAllBom = $ArrBomItem = $ArrBISizes = $ArrBIItemCode = $ArrBIItemColorCode = $ArrBISizeDim = $ArrBIUom = [];
        $ArrCadRefNoWithFilter = [];
        $ArrCadRefNoWithoutFilter = [];
        $ArrCadRequirementWithFilter = [];
        if (!empty($bomEleventhRes[0]['jsondatagrid']) && !empty($bomEleventhRes[1]['jsondatagrid'])) {
            $ArrBomArticle1Res = json_decode($bomEleventhRes[0]['jsondatagrid']);
            $ArrBomArticle2Res = json_decode($bomEleventhRes[1]['jsondatagrid']);
            $ArrAllBom = array_merge($ArrBomArticle1Res, $ArrBomArticle2Res);
            foreach ($ArrAllBom as $bom) {
                $ArrBomItem[] = $bom[5];
                $ArrBISizes[] = $bom[6];
                $ArrBIItemCode[] = $bom[7];
                $ArrBIItemColorCode[] = $bom[8];
                $ArrBISizeDim[] = $bom[9];
                $ArrBIUom[] = $bom[10];
            }
            //echo '<pre>'; print_r($ArrBomItemGroup); die('die');
        }
        if(!empty($finalBomArr[$VarCcc])) {
            foreach ($finalBomArr[$VarCcc] as $bom) {
                $bomItem = explode('|#|',$bom);
                $itemDesc[] = $bomItem[0];
                $garmentSize[] = $bomItem[1];
                $itemCode[] = $bomItem[2];
                $itemColorCode[] = $bomItem[3];
                $sizeDim[] = $bomItem[4];
                //$unitOfMeasure[] = $bomItem[5];
            }
        }
        /*
         * Fabric Request from fabric Program is not stored in DB
         * Instead replicate by JS using other saved data
         * For entering DIA / DIMENSION in fabric indent
         *  */
        $ArrCadRequestData = $this->commonmodel->fnGetAllTableInfo(KN_ALLREQUEST, 'jsondatagrid', array('request_type_dept' => 'CAD'));
        foreach ($ArrCadRequestData as $cadreq) {
            if (!empty($cadreq['jsondatagrid'])) {
                $ArrJson = json_decode($cadreq['jsondatagrid'], true);
                foreach ($ArrJson as $item) {
                    if (!empty($item[9])) { //CAD Ref No
                        $ArrCadRefNoWithFilter[$item[1]."|#|".$item[3]][] = $item[9];
                        $ArrCadRefNoWithoutFilter[] = $item[9];
                    }
                    if(!empty($item[4])) { //Requirement
                        $ArrCadRequirementWithFilter[$item[9]][] = $item[4];
                    }
                }
            }
        }
        $jsonFabProFabricDetails = $this->commonmodel->fnGetAllTableInfo(FABRIC_PROGRAM_ALL_JXL, 'jsondatagrid',
            array('referenceid' => $VarOrderId, 'companyid' => $this->companyid,'tableid'=>'fF'));
        if(!empty($jsonFabProFabricDetails)) {
            //echo '<pre>'; print_r($FabIndentFromKnitRes); die('die');
            /*$FabIndentFromWoven = $this->commonmodel->fnGetAllTableInfo(ORDERENTRY_WOVENEIGHTH_TBL, 'jsondatagrid',
                array('referenceid' => $VarOrderId, 'companyid' => $this->companyid));*/
            $ArrFabricKnit = json_decode($jsonFabProFabricDetails[0]['jsondatagrid']);
            if (!empty($ArrFabricKnit)) {
                foreach ($ArrFabricKnit as $fabItem) {
                    $ArrFabricColor[] = $fabItem[2];
                    $ArrGarmentParts[] = $fabItem[3];
                    $ArrFabBlend[] = $fabItem[4];
                    $ArrFabContent[] = $fabItem[5];
                    $ArrFabName[] = $fabItem[6];
                    $ArrGsm[] = $fabItem[7];
                    $ArrFIDyeingType[] = $fabItem[9];
                }
            }
        }
        $ArrFIDyeingType = array_unique($ArrFIDyeingType);
        //cadRequirementWithFilter
        //$ArrFinalCadRefNo = $ArrCadRefNo[$VarCompSpc];
        echo json_encode(array(
            'ArrAllBom'=>$ArrAllBom,'ArrBomItem'=>$ArrBomItem,'ArrBISizes'=>$ArrBISizes,'ArrBIItemCode'=>$ArrBIItemCode,
            'ArrBIItemColorCode'=>$ArrBIItemColorCode,'ArrBISizeDim'=>$ArrBISizeDim,'ArrBIUom'=>$ArrBIUom,
            'ArrCadRefNoWithFilter'=>$ArrCadRefNoWithFilter,
            'ArrCadRefNoWithoutFilter'=>$ArrCadRefNoWithoutFilter,'ArrCadRequirement'=>ARRCADREQUIREMENT,
            'ArrCadRequirementWithFilter'=>$ArrCadRequirementWithFilter,'ArrFIColor'=>$ArrFabricColor,
            'ArrFIGarmentParts'=>$ArrGarmentParts,'ArrFIBlend'=>$ArrFabBlend,'ArrFIContent'=>$ArrFabContent,
            'ArrFIName'=>$ArrFabName,'ArrFIGsm'=>$ArrGsm,'ArrFIDyeingType'=>array_values($ArrFIDyeingType)
            )
        );
    }

    public function getSampleRefNo() {
        $VarId = xssclean($this->input->post('id'));
        $VarIsrIorCode = xssclean($this->input->post('isrIorCode'));
        $VarRequestListType = "SAMPLE";
        $QidRes = $this->commonmodel->getAllQueueNo($VarRequestListType, $this->companyid);
        if(!empty($QidRes)) {
            $Qid = $QidRes->qid;
            $samRefNo = $VarIsrIorCode.'/'.SAM_REFNO_PREFIX.$Qid;
            echo json_encode(array('errCode' => '1', 'refNo' => $samRefNo));
        }
    }

    public function sample_request_details()
    {
        $VarSampleRequestId = base64_decode(urldecode($this->uri->segment(3)));
        if ($VarSampleRequestId > 0) {
            $ArrSamRequestData = $this->msamplerequestmodel->getSamRequestData($VarSampleRequestId, $this->companyid);
            $VarOrderId = $ArrSamRequestData[0]->orderid;
            $VarCommonOrderEntryInfo = $this->commonmodel->commonBasicInfoOrderEntry($VarOrderId, $this->companyid);
            $ArrOrderEnqData = $this->orderentrymodel->fnGetOrderEnquiryInfo($VarOrderId, $this->companyid, '');
            $ArrData['VarCommonOrderEntryInfo'] = $VarCommonOrderEntryInfo;
            $ArrData['ArrCompanyInfo'] = $VarCommonOrderEntryInfo['ArrCompanyInfo'];
            $ArrData['ArrMerchant'] = $VarCommonOrderEntryInfo['ArrMerchant'];
            $ArrData['ArrTeamInfo'] = $VarCommonOrderEntryInfo['ArrTeamInfo'];
            $ArrData['ArrOrderEnqData'] = $ArrOrderEnqData[0];
            $ArrData['ArrOrderCommonData'] = $VarCommonOrderEntryInfo['ArrOrderCommonData'];
            $ArrData['brandName'] = $VarCommonOrderEntryInfo['brandName'];
            $ArrData['buyerName'] = $VarCommonOrderEntryInfo['buyerName'];
            $ArrData['OrderId'] = $ArrSamRequestData[0]->orderid;
            $ArrData['ArrBasicInfo'] = $ArrSamRequestData[0];
            $ArrData['VarReqId'] = $VarSampleRequestId;
            $IndentDetails = $this->commonmodel->fnGetAllTableInfo(KN_SAMPLE_REQUEST, 'cadindentcutoffdatetime,fabindentcutoffdatetime,
            bomindentcutoffdatetime,cadissuedto,fabissuedto,bomissuedto,cad_mat_ind_ref_no,fab_mat_ind_ref_no,bom_mat_ind_ref_no',
                array('requestrefid' => $VarSampleRequestId, 'orderid' => $VarOrderId));
            if (!empty($IndentDetails)) {
                $ArrData['IndentDetails'] = $IndentDetails[0];
            } else {
                $ArrData['IndentDetails'] = '';
            }
            $jsonData = $ArrSamRequestData[0]->jsondatagrid;
            $ArrData['jsonDataGrid'] = $jsonData;
            $ArrData['AuthorizedByInfo'] = $this->commonmodel->getUserInfo($ArrSamRequestData[0]->mgmtid);
            $this->load->view('sampleRequestDetails', $ArrData);
        }
    }

    /*    public function getDatasForCadIndentGridDropdown() {
            $ArrRequiremtnForDd = unserialize(ARRCADREQUIREMENT);
            foreach ($ArrRequiremtnForDd as $cadreq) $ArrCadRequirement[] = $cadreq;
            $ArrCadRefNoForDd = array();
            $VarOrderId = xssclean($this->input->post('oid'));
            $ArrDatasForCadIndentDropdown = $this->commonmodel->getDatasForCadIndentGridDropdown($VarOrderId,$this->companyid);
            foreach ($ArrDatasForCadIndentDropdown as $item) {
                $ArrJsonData = json_decode($item->jsondatagrid);
                if(!empty($ArrJsonData)) {
                    $ArrCadRefNoForDd['ArrCadrefno'][] = $ArrJsonData[0][9];
                    $ArrCadRefNoForDd['ArrRequiremtnForDd'][] = $ArrJsonData[0][4];
                }
            }
            echo json_encode($ArrCadRefNoForDd);
            die;
        }*/

    public function updateInfo() {
        $ArrResult = array();
        $VarRequestType = xssclean($this->input->post('reqtype'));
        $VarCutoff = date('Y-m-d H:i:s', strtotime(xssclean($this->input->post('cutoff'))));
        $VarMerchantNote = xssclean($this->input->post('mNote'));
        $VarId = xssclean($this->input->post('id'));
        $jsonSamReq = xssclean($this->input->post('jxlSamReq'));
        $jsonAttachmentJxl = xssclean($this->input->post('attach'));
        $ArrRequestData = $this->msamplerequestmodel->getSamRequestData($VarId, $this->companyid);
        if ($VarId == '' || $VarId == 0) {
            $VarMgmtCurrentStatus = 1;
            $VarDeptCurrentStatus = 1;
        } else {
            if ($ArrRequestData->mgmtcurrentstatus == 2) {
                if ($ArrRequestData->deptcurrentstatus == 3) {
                    $VarMgmtCurrentStatus = $ArrRequestData->mgmtcurrentstatus;
                    $VarDeptCurrentStatus = 4;
                }
            } else
                $VarDeptCurrentStatus = $ArrRequestData->deptcurrentstatus;
        }
        $VarOrderId = xssclean($this->input->post('oid'));
        if (xssclean($this->input->post('cs') == '3')) $VarMgmtCurrentStatus = '4';
        if ($VarOrderId <> '') {
            $ArrAllReqData = array(
                'id' => $VarId,
                'companyid' => $this->companyid,
                'deptcurrentstatus' => $VarDeptCurrentStatus,
                'merchantnote' => $VarMerchantNote,
                'merchantid' => $this->userid,
                'request_type_dept' => 'SAMPLE',
                'jsondatagrid' => $jsonSamReq,
                'mgmtcurrentstatus' => $VarMgmtCurrentStatus,
                'orderid' => $VarOrderId,
                'status' => '1',
                'datecreated' => $this->mysqldatetime,
                'dateupdated' => $this->mysqldatetime,
                'cutoffdatetime' => $VarCutoff,
                'requesttype' => $VarRequestType
            );
            $ArrResult = $this->commonmodel->saveSampleRequestInfo($ArrAllReqData,$jsonAttachmentJxl);
        } else {
            $ArrResult['errcode'] = -1;
            $ArrResult['msg'] = 'Invalid Input!';
        }
        echo json_encode($ArrResult); die;
    }

    public function updateIndentGridInfo()
    {
        $this->load->model('merchantmodel');
        $VarRfrom = xssclean($this->input->post('rfrom'));
        if ($VarRfrom == 1) {
            $VarRequestId = xssclean($this->input->post('requestid'));
            $VarOrderId = xssclean($this->input->post('oid'));
            $VarAttachmentRef = xssclean($this->input->post('aR'));
            $ArrMoreCadJxl = json_decode(xssclean($this->input->post('moreCadJxl')), true);
            $ArrMoreFabJxl = json_decode(xssclean($this->input->post('moreFabJxl')), true);
            $ArrMoreBomJxl = json_decode(xssclean($this->input->post('moreBomJxl')), true);
            $CadMaterialIssuedTo = xssclean($this->input->post('CadMaterialIssuedTo'));
            $FabMaterialIssuedTo = xssclean($this->input->post('FabMaterialIssuedTo'));
            $BomMaterialIssuedTo = xssclean($this->input->post('BomMaterialIssuedTo'));
            if(xssclean($this->input->post('cadIndentCutOffDateTime')) != '')
                $cadIndentCutOffDateTime = date('Y-m-d H:i:s', strtotime(xssclean($this->input->post('cadIndentCutOffDateTime'))));
            else
                $cadIndentCutOffDateTime = NULL;


            if(xssclean($this->input->post('fabIndentCutOffDateTime')) != '')
                $fabIndentCutOffDateTime = date('Y-m-d H:i:s', strtotime(xssclean($this->input->post('fabIndentCutOffDateTime'))));
            else
                $fabIndentCutOffDateTime = NULL;

            if(xssclean($this->input->post('bomIndentCutOffDateTime')) != '')
                $bomIndentCutOffDateTime = date('Y-m-d H:i:s', strtotime(xssclean($this->input->post('bomIndentCutOffDateTime'))));
            else
                $bomIndentCutOffDateTime = NULL;

            $ArrOtherData = array(
                'requestrefid' => $VarRequestId, 'orderid' => $VarOrderId,
                'cadindentcutoffdatetime' => $cadIndentCutOffDateTime, 'cadissuedto' => $CadMaterialIssuedTo,
                'fabindentcutoffdatetime' => $fabIndentCutOffDateTime, 'fabissuedto' => $FabMaterialIssuedTo,
                'bomindentcutoffdatetime' => $bomIndentCutOffDateTime, 'bomissuedto' => $BomMaterialIssuedTo,
                'attachment_jxl' => $VarAttachmentRef
            );

            //echo '<pre>'; print_r($ArrOtherData); die('die');
            $this->merchantmodel->updateSampleRequestIndentDetails($ArrOtherData);
            $this->commonmodel->saveMoreRequestIndents($VarRequestId, $VarOrderId, $ArrMoreCadJxl, $ArrMoreFabJxl, $ArrMoreBomJxl, $updateFlag = 0);
            echo json_encode(array('errcode' => '1', 'msg' => 'Sample Request Sent!', 'datecreated' => date('d-m-Y H:i:s')));
            die;
        }
    }

    public function getSampleRequest($VarId, $VarCompanyId)
    {
        $VarSampleSql = "SELECT a.id,s.requestrefid,a.requesttype,a.cutoffdatetime,a.merchantid,a.queueno_assigned_date,
a.requestrefno,a.merchantnote,BuyersOriginalSample,BuyersComments,AppGradMeasChart,CompleteArtwork,MeasureDetailsArtwork,a.orderid,a.mgmtcurrentstatus,
a.deptcurrentstatus,a.mgmtremarks,a.deptremarks,a.queueno,jobschedule,a.approvaltype,a.request_type_dept,a.requestdt,
s.cadqueuecompletestatus,oe.isriorcode,oe.stylenamerefno,oe.styledesc,oe.brandbuyerid,oe.datecreated as wipdatecreated,ci.cadindentgrid,ci.matissuedto,
ci.indentrefno as cadindentrefno
,ci.cutoffdatetime as cadindentcutoffdt,bi.matissuedto as bommatissuedto,bi.cutoffdatetime as bomindentcutoffdt,bi.matissuedby as bommatissuedby,
bi.indentrefno as bomindentrefno FROM
 " . KN_ALLREQUEST . " AS a INNER JOIN " . KN_SAMPLE_REQUEST . " AS s ON a.id = s.requestrefid INNER JOIN " . KN_ORDER_ENQUIRY . " AS oe
 ON a.orderid = oe.id INNER JOIN " . CADINDENTDETAILS . " AS ci ON a.id = ci.requestid INNER JOIN " . BOMINDENTDETAILS . " AS bi ON a.id = bi.requestid WHERE a.id = '$VarId' 
 AND a.companyid = '$VarCompanyId' LIMIT 1";
        return $this->db->query($VarSampleSql)->row();
    }

    /** Get Sample Request in CAD,Fabric and Bom Indents jxl grids saved in merchant at first with sample Request jxl Grid
     *  Here id (inside foreach) is for updating in sample dept. queue no assign , queue list detail pages. Its used in $.each as index
     **/
    public function getIndents()
    {
        $ArrLoopId = [];
        $loop = 0;
        $VarRequestId = xssclean($this->input->post('requestid'));
        $ArrCadIndentDetails = $this->commonmodel->getSampleRequestCadIndents($VarRequestId);
        foreach ($ArrCadIndentDetails as $c) {
            $ArrLoopId[] = $loop++;
            $cadIndentDetails[$c->id] = $c->gridindent;
        }

        $ArrFabIndentDetails = $this->commonmodel->getSampleRequestFabIndents($VarRequestId);
        foreach ($ArrFabIndentDetails as $f) {
            $fabIndentDetails[$f->id] = $f->gridindent;
        }

        $ArrBomIndentDetails = $this->commonmodel->getSampleRequestBomIndents($VarRequestId);
        foreach ($ArrBomIndentDetails as $b) {
            $bomIndentDetails[$b->id] = $b->gridindent;
        }
        $ArrSamRequestData = $this->msamplerequestmodel->getSamRequestData($VarRequestId, $this->companyid);
        $jsonData = $ArrSamRequestData[0]->jsondatagrid;
        echo json_encode(array('moreCadIndent' => $cadIndentDetails, 'moreFabIndent' => $fabIndentDetails, 'moreBomIndent' =>
            $bomIndentDetails, 'sampleReqJxlGrid' => $jsonData, 'ArrLoopId' => $ArrLoopId));
    }

    //TODO remove getIndents wherever used from management controller

    public function cad_ind() {
        $VarFrom = xssclean($this->input->post('rFrom'));
        if($VarFrom == 1) {
            $VarSamReqId = xssclean($this->input->post('samReqId'));
            $VarEnqId = xssclean($this->input->post('enqId'));
            $ArrCadRequestData = $this->commonmodel->fnGetAllTableInfo(KN_ALLREQUEST, 'jsondatagrid', array('request_type_dept' => 'CAD'));
            foreach ($ArrCadRequestData as $cadreq) {
                if (!empty($cadreq['jsondatagrid'])) {
                    $ArrJson = json_decode($cadreq['jsondatagrid'], true);
                    foreach ($ArrJson as $item) {
                        if (!empty($item[9])) { //CAD Ref No
                            $ArrCadRefNoWithFilter[$item[1]."|#|".$item[3]][] = $item[9];
                            $ArrCadRefNoWithoutFilter[] = $item[9];
                        }
                        if(!empty($item[4])) { //Requirement
                            $ArrCadRequirementWithFilter[$item[9]][] = $item[4];
                        }
                    }
                }
            }
            $ArrSizeChartData = $this->orderentrymodel->getSizeChart($VarEnqId, $this->companyid);
            if(!empty($ArrSizeChartData->sizechartvalue)) {
                $ArrFinalSizes = explode(',',$ArrSizeChartData->sizechartvalue);
            }
            $ArrFinalSizes[] = "All";
            echo json_encode(array(
                'ArrCadRefNoWithFilter'=>$ArrCadRefNoWithFilter,
                'ArrCadRefNoWithoutFilter'=>$ArrCadRefNoWithoutFilter,
                'ArrCadRequirement'=>ARRCADREQUIREMENT,
                'ArrFinalSizes'=>$ArrFinalSizes,
            ));
        }
        else {
            $VarRequestId = $this->uri->segment(3);
            if ($VarRequestId >= 1) {
                $ArrObjRequestData = $this->msamplerequestmodel->getSamRequestData($VarRequestId, $this->companyid);
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
                $this->load->view('sam_request/samReqCadInd', $ArrData);
            }
        }
    }

    public function fab_ind() {
        $VarFrom = xssclean($this->input->post('rFrom'));
        if($VarFrom == 1) {
            $VarSamReqId = xssclean($this->input->post('samReqId'));
            $VarEnqId = xssclean($this->input->post('enqId'));
            $jsonFabProFabricDetails = $this->commonmodel->fnGetAllTableInfo(FABRIC_PROGRAM_ALL_JXL, 'jsondatagrid',
                array('referenceid' => $VarEnqId, 'companyid' => $this->companyid,'tableid'=>'fF'));
            if(!empty($jsonFabProFabricDetails)) {
                $ArrFabricKnit = json_decode($jsonFabProFabricDetails[0]['jsondatagrid']);
                if (!empty($ArrFabricKnit)) {
                    foreach ($ArrFabricKnit as $fabItem) {
                        $ArrFabricColor[] = $fabItem[2];
                        $ArrGarmentParts[] = $fabItem[3];
                        $ArrFabBlend[] = $fabItem[4];
                        $ArrFabContent[] = $fabItem[5];
                        $ArrFabName[] = $fabItem[6];
                        $ArrGsm[] = $fabItem[7];
                        $ArrFIDyeingType[] = $fabItem[9];
                    }
                }
            }
            $ArrSizes = [];
            $ArrSizeChartData = $this->orderentrymodel->getSizeChart($VarEnqId, $this->companyid);
            if(!empty($ArrSizeChartData->sizechartvalue)) {
                $ArrSizes = explode(',',$ArrSizeChartData->sizechartvalue);
            }
            $jsonDiaDimension = '';
            $fabProDiaDimension = $this->commonmodel->fnGetAllTableInfo(FABRIC_PROGRAM_ALL_JXL, 'jsondatagrid',
                array('referenceid' => $VarEnqId, 'companyid' => $this->companyid,'tableid'=>'dDimension'));
            if(!empty($fabProDiaDimension[0]['jsondatagrid'])) {
                $jsonDiaDimension = $fabProDiaDimension[0]['jsondatagrid'];
            }
            echo json_encode(array(
                'ArrFIColor'=>$ArrFabricColor,
                'ArrFIGarmentParts'=>$ArrGarmentParts,
                'ArrFIBlend'=>$ArrFabBlend,
                'ArrFIContent'=>$ArrFabContent,
                'ArrFIName'=>$ArrFabName,
                'ArrFIGsm'=>$ArrGsm,
                'ArrFIDyeingType'=>array_values($ArrFIDyeingType),
                'ArrFabricKnit'=>$ArrFabricKnit,
                'ArrSizes'=>$ArrSizes,
                'ArrUnitOfMeasure'=>array_values(unserialize(ARRUNITOFMEASURE)),
                'jsonDiaDimension'=>$jsonDiaDimension
            ));
        }
        else {
            $VarRequestId = $this->uri->segment(3);
            if ($VarRequestId >= 1) {
                $ArrObjRequestData = $this->msamplerequestmodel->getSamRequestData($VarRequestId, $this->companyid);
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
                $this->load->view('sam_request/samReqFabInd', $ArrData);
            }
        }
    }

    public function bom_ind() {
        $VarFrom = xssclean($this->input->post('rFrom'));
        if($VarFrom == 1) {
            $VarCcc = xssclean($this->input->post('ccc'));
            $VarSamReqId = xssclean($this->input->post('samReqId'));
            $VarEnqId = xssclean($this->input->post('enqId'));
            $bomEleventhRes = $this->bommodel->getBomArticle($VarEnqId, $this->companyid);
            $ArrAllBom = $ArrBomItem = $ArrBISizes = $ArrBIItemCode = $ArrBIItemColorCode = $ArrBISizeDim = $ArrBIUom = [];
            if (!empty($bomEleventhRes[0]['jsondatagrid']) && !empty($bomEleventhRes[1]['jsondatagrid'])) {
                $ArrBomArticle1Res = json_decode($bomEleventhRes[0]['jsondatagrid']);
                $ArrBomArticle2Res = json_decode($bomEleventhRes[1]['jsondatagrid']);
                $ArrAllBom = array_merge($ArrBomArticle1Res, $ArrBomArticle2Res);
                foreach ($ArrAllBom as $bom) {
                    $ArrBomItem[] = $bom[5];
                    $ArrBISizes[] = $bom[6];
                    $ArrBIItemCode[] = $bom[7];
                    $ArrBIItemColorCode[] = $bom[8];
                    $ArrBISizeDim[] = $bom[9];
                    $ArrBIUom[] = $bom[10];
                }
                //echo '<pre>'; print_r($ArrBomItemGroup); die('die');
            }
            if(!empty($finalBomArr[$VarCcc])) {
                foreach ($finalBomArr[$VarCcc] as $bom) {
                    $bomItem = explode('|#|',$bom);
                    $itemDesc[] = $bomItem[0];
                    $garmentSize[] = $bomItem[1];
                    $itemCode[] = $bomItem[2];
                    $itemColorCode[] = $bomItem[3];
                    $sizeDim[] = $bomItem[4];
                }
            }
            echo json_encode(array(
                'ArrAllBom'=>$ArrAllBom,'ArrBomItem'=>$ArrBomItem,'ArrBISizes'=>$ArrBISizes,'ArrBIItemCode'=>$ArrBIItemCode,
                'ArrBIItemColorCode'=>$ArrBIItemColorCode,'ArrBISizeDim'=>$ArrBISizeDim,'ArrBIUom'=>$ArrBIUom,
            ));
        }
        else {
            $VarRequestId = $this->uri->segment(3);
            if ($VarRequestId >= 1) {
                $ArrObjRequestData = $this->msamplerequestmodel->getSamRequestData($VarRequestId, $this->companyid);
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
                $this->load->view('sam_request/samReqBomInd', $ArrData);
            }
        }
    }
}