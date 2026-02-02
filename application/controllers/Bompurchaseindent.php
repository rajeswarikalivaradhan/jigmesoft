<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Bompurchaseindent extends CI_Controller {
    public $companyid;
    public $userid;
    public $mysqldatetime;

    public function __construct() {
        parent::__construct();
        $this->load->helper('xssclean');
        fnIfCheckUserLoggedIn();
        $ArrUserLoggedInfo = fnGetUserLoggedInfo('1');
        $this->companyid   = $ArrUserLoggedInfo['companyid'];
        $this->userid      = $ArrUserLoggedInfo['id'];
        $this->mysqldatetime = date('Y-m-d H:i:s');
        $this->load->model('commonmodel');
        $this->load->model("mbompurchaserequestmodel");
        $this->load->model("mbompurcahseindentmodel");
        $this->load->model(CNFCOMPANY.'mcadrequestmodel');
        $this->load->model(CNFCOMPANY.'orderentrymodel');
    }

    public function preprarebompind() {
        $VarBomPurReqId = base64_decode(urldecode($this->uri->segment(3)));
        //$this->mbompurchaserequestmodel->createBomPITbl($VarTblName = 'bompi');
        $RequestData = $this->mbompurchaserequestmodel->getBomPurchaseRequest($VarBomPurReqId, $this->companyid);
        //echo '<pre>'; print_r($RequestData); die('die');
        $bomPurchaseRequestId = $RequestData->id;
        $articleTypeId = $RequestData->articletypeid;
        $VarOrderId = $RequestData->orderid;
        $dynamicTblName = ''; $jsDynamicBPIGridData = 0; $ArrBomPiGrid = [];
        if(!empty($RequestData->bompirequestgrid_tblname)) {
            $dynamicTblName = $RequestData->bompirequestgrid_tblname;
            $dynamicTblBomPI = $this->mbompurcahseindentmodel->getbomPIDynamicTbldata($dynamicTblName);
            //echo '<pre>'; print_r($dynamicTblBomPI); die('die');
            if (!empty($dynamicTblBomPI)) {
                foreach ($dynamicTblBomPI as $item) {
                    $ArrBomPiGrid[] = array($item['itemdesc'], $item['garmentsize'], $item['itemcode'], $item['itemcolorcode'], $item['sizeordim'],
                        $item['uom1'], $item['planbomqty'], $item['progbomqty'], $item['uom2'], $item['currency'], $item['unitrate'],$item['amount'],
                        $item['id'],$item['tempselect'],$item['hiddenstatus']
                    );
                }
                $jsDynamicBPIGridData = json_encode($ArrBomPiGrid);
            }
        }
        //
        $VarCommonOrderEntryInfo = $this->commonmodel->commonBasicInfoOrderEntry($VarOrderId, $this->companyid);
        $ArrOrderEnqData = $this->orderentrymodel->fnGetOrderEnquiryInfo($VarOrderId, $this->companyid,$VarStatus = 1);
        $ArrData['VarCommonOrderEntryInfo'] = $VarCommonOrderEntryInfo;
        $ArrData['ArrCompanyInfo'] = $VarCommonOrderEntryInfo['ArrCompanyInfo'];
        $ArrData['ArrMerchant'] = $VarCommonOrderEntryInfo['ArrMerchant'];
        $ArrData['ArrTeamInfo'] = $VarCommonOrderEntryInfo['ArrTeamInfo'];
        $ArrData['ArrOrderEnqData'] = $ArrOrderEnqData[0];
        $ArrData['VarOrderId'] = $VarOrderId;
        $ArrData['brandName'] = $VarCommonOrderEntryInfo['brandName'];
        $ArrData['buyerName'] = $VarCommonOrderEntryInfo['buyerName'];
        $ArrData['ArrCurrencyNCode'] = json_encode(unserialize(ARRCURRENCYLIST));
        $ArrData['ArrOrderCommonData'] = $VarCommonOrderEntryInfo['ArrOrderCommonData'];
        $ArrData['ArrBasicInfo'] = $RequestData;
        $ArrData['VarArtType'] = $RequestData->articletypeid;
        $ArrData['dynamicTblBomPI']          = $jsDynamicBPIGridData;
        $ArrData['dynamicTblName']          = $dynamicTblName;
        $ArrData['VarBomPurReqId']           = $VarBomPurReqId;
        $this->db->from(KN_BOM_PI_APPROVAL_REQUEST);
        $this->db->where('orderId',$VarOrderId);
        $this->db->where('bomPurchaseRequestId',$bomPurchaseRequestId);

        $previewExists = $this->db->count_all_results();
        $ArrData['previewExists'] = $previewExists;
        //
        /** Save bom items from order entry
         * to db for purchase indent approval request
        **/
        /*$this->load->model(CNFCOMPANY . 'orderentrymodel');
        $jsonFromTwelfthRes = $this->orderentrymodel->getFromBomConsTwelfth($VarOrderId, $this->companyid, $articleTypeId, 'asc');
        $BomConsTwelfth = $jsonFromTwelfthRes->jsondatagrid;
        $consolidatedForBomPI = json_decode($jsonFromTwelfthRes->jsondatagrid);
        foreach ($consolidatedForBomPI as $item) {
            $BomConsForBomPurInd[] = array(
                $item[0].'|#|'.$item[1].'|#|'.$item[2].'|#|'.$item[3].'|#|'.$item[4].'|#|'.$item[5].'|#|'.$item[9]
                'companyid'=>$this->companyid,
                'orderid'=>$VarOrderId,
                'bomitemid' => $bompurindent[0],
                'garmentsize' => $bompurindent[1],
                'itemcode' => $bompurindent[2],
                'itemcolorcode' => $bompurindent[3],
                'sizeordim' => $bompurindent[4],
                'uom1' => $bompurindent[5],
                'planbomqty'=>$bompurindent[9]
            );
        }
        $dynamicTblRows = $this->mbompurcahseindentmodel->createBomPurIndApprRequestTbl($BomConsForBomPurInd, $dynamicTblName, $VarRequestId);*/
        //
        //echo '<pre>'; print_r($ArrData); die('die');
        $this->load->view('purchase/preprarebompind', $ArrData);
    }

    public function updatePrepareBomPI() {
        $VarRfrom = xssclean($this->input->post('rfrom'));
        $this->load->model('mbompurcahseindentmodel');
        $this->load->model('mbompurchaserequestmodel');
        if ($VarRfrom == 1) {
            $VarBomPurReqId = xssclean($this->input->post('rid'));
            $tblName = xssclean($this->input->post('tblName'));
            $VarTaxTypeId = xssclean($this->input->post('bomPiTaxTypeId'));
            $ArrBomPurIndGrid = json_decode(xssclean($this->input->post('tempSavePrepareBomPi')));
            $jsonBomPi = xssclean($this->input->post('tempSavePrepareBomPi'));
            $this->mbompurchaserequestmodel->savePreparePi($jsonBomPi,$VarTaxTypeId,$VarBomPurReqId);
            foreach ($ArrBomPurIndGrid as $PurIndentItem) {
                $ArrUpdateData = array('bomPurchaseRequestId'=>$VarBomPurReqId,'progbomqty' => $PurIndentItem[7],'uom2' => $PurIndentItem[8],
                    'currency' => $PurIndentItem[9],'unitrate' => $PurIndentItem[10], 'amount' => $PurIndentItem[11],
                    'tempselect'=>$PurIndentItem[14]);

                $this->mbompurcahseindentmodel->savePurchaseIndentDynamicTbl($tblName,$ArrUpdateData,$PurIndentItem[12]);
            }
            echo json_encode(array('errcode' => '1'));

        }
    }

    public function bompiapprovalrequest() {
        $VarHashedRequestId = $this->uri->segment(3);
        $VarRequestId = base64_decode(urldecode($this->uri->segment(3)));
        if($VarRequestId > 0) {
            $ArrBomPurRequestInfo = $this->mbompurchaserequestmodel->getBomPurchaseRequest($VarRequestId, $this->companyid);
            $VarOrderId = $ArrBomPurRequestInfo->orderid;
            $ArrLoggedUserInfo = fnGetUserLoggedInfo(1);
            $VarUserTypeId = $ArrLoggedUserInfo['usertype'];
            $Query = $this->db->select('id,orderId,jsonBomJxl,advancePaymentJxl,otherDetails,taxTypeId')
                ->get_where(KN_BOM_PI_APPROVAL_REQUEST,array('bomPurchaseRequestId'=>$VarRequestId,'orderId'=>$VarOrderId));
            $Res = $Query->row();
            $otherDetailsId = 0; $otherDetails = '';
            $ArrBomPi = []; $VarTaxTypeId = 0;
            if(!empty($Res)) {
                $otherDetailsId = $Res->id;
                $otherDetails = $Res->otherDetails;
                $ArrBomPi = $Res->jsonBomJxl;
                $VarTaxTypeId = $Res->taxTypeId;
            }
            else {
                $previewJxlSelected = $this->mbompurchaserequestmodel->getPreparePiForPreview($VarRequestId);
                //echo '<pre>'; print_r($previewJxlSelected); die('die');
                if(!empty($previewJxlSelected->taxtypeid)) {
                    $VarTaxTypeId = $previewJxlSelected->taxtypeid;
                }
                if(!empty($previewJxlSelected->jsonPurchaseIndent)) {
                    $ArrPreviewJxl = json_decode($previewJxlSelected->jsonPurchaseIndent,true);
                    if($VarTaxTypeId == 1) {
                        foreach ($ArrPreviewJxl as $item) {
                            $ArrBomPi[] = array($item[0], $item[1],$item[2], $item[3], $item[4],
                                $item[5],$item[6], $item[7], $item[8], $item[9],'','','','','',$item[10],$item[11]);
                        }
                    }
                    elseif ($VarTaxTypeId == 2) {
                        foreach ($ArrPreviewJxl as $item) {
                            $ArrBomPi[] = array($item[0], $item[1],$item[2], $item[3], $item[4],
                                $item[5],$item[6], $item[7], $item[8], $item[9],'','','',$item[10],$item[11]);
                        }
                    }
                    elseif ($VarTaxTypeId == 3) {
                        foreach ($ArrPreviewJxl as $item) {
                            $ArrBomPi[] = array($item[0], $item[1],$item[2], $item[3], $item[4],
                                $item[5],$item[6], $item[7], $item[8], $item[9],'','','',$item[10],$item[11]);
                        }
                        //echo '<pre>'; print_r($ArrBomPi); die('die');
                    }
                }
            }
            $VarPiNoPrefix = $ArrBomPurRequestInfo->queueno.'/'.BOMPURIND_PREFIX;
            $ArrData['PiNoPreFix'] = $VarPiNoPrefix;
            $ArrData['otherDetails'] = $otherDetails;
            $ArrData['otherDetailsId'] = $otherDetailsId;
            $VarSqlVendor = "SELECT id,vendorname FROM " . KN_MASTER_BOM_VENDOR . "  WHERE companyid = '$this->companyid' ";
            $ArrData['ArrObjToVendorname'] = $this->db->query($VarSqlVendor)->result();
            $VarCommonOrderEntryInfo = $this->commonmodel->commonBasicInfoOrderEntry($VarOrderId, $this->companyid);
            $ArrOrderEnqData = $this->orderentrymodel->fnGetOrderEnquiryInfo($VarOrderId, $this->companyid,$VarStatus=1);
            $ArrData['VarCommonOrderEntryInfo'] = $VarCommonOrderEntryInfo;
            $ArrData['ArrCompanyInfo'] = $VarCommonOrderEntryInfo['ArrCompanyInfo'];
            $ArrData['ArrMerchant'] = $VarCommonOrderEntryInfo['ArrMerchant'];
            $ArrData['ArrTeamInfo'] = $VarCommonOrderEntryInfo['ArrTeamInfo'];
            $ArrData['ArrOrderEnqData'] = $ArrOrderEnqData[0];
            $ArrData['brandName'] = $VarCommonOrderEntryInfo['brandName'];
            $ArrData['buyerName'] = $VarCommonOrderEntryInfo['buyerName'];
            /*$ArrData['ArrOrderEnqData'] = $VarCommonOrderEntryInfo['ArrOrderEnqData'];
            $ArrData['ArrBB'] = $VarCommonOrderEntryInfo['ArrBB'];*/
            $ArrData['OrderId'] = $VarOrderId;
            $ArrData['ArrCurrencyNCode'] = unserialize(ARRCURRENCYLIST);
            $ArrData['ArrOrderCommonData'] = $VarCommonOrderEntryInfo['ArrOrderCommonData'];
            $VarMerchantInfo                    = $this->commonmodel->getUserInfo($ArrBomPurRequestInfo->merchantid, '', $this->companyid);
            $ArrData['VarMerchantInfo'] = $VarMerchantInfo;
            $VarMgmt                    = $this->commonmodel->getUserInfo($ArrBomPurRequestInfo->mgmtid, '', $this->companyid);
            $VarPurchaseDept            = $this->commonmodel->getUserInfo($ArrBomPurRequestInfo->alldeptid, '', $this->companyid);
            $ArrData['VarAuthorizedBy'] = $VarMgmt[0]['contactname'];
            $ArrData['VarApprovedBy']   = $VarPurchaseDept[0]['contactname'];
            $ArrData['VarRequestId'] = $VarRequestId;
            $ArrData['VarBomPurIndentId'] = 0;
            $ArrData['ArrBasicInfo']   = $ArrBomPurRequestInfo;
            //echo '<pre>'; print_r($dynamicTblName); die('die');
            //$ArrData['dynamicTblName']   = $dynamicTblName;
            $ArrData['TaxTypeId']   = $VarTaxTypeId;
            $ArrData['hashedRequestId']   = $VarHashedRequestId;
            //$ArrData['previewJxl']   = json_encode($ArrBomPi);
            $ArrData['jsonBomPiModeOfPayment'] = json_encode(unserialize(BOMPI_MODEOFPAYMENT));
            $ArrData['ArrBomPiModeOfPayment'] = unserialize(BOMPI_MODEOFPAYMENT);
            $ArrData['UserTypeId'] = $VarUserTypeId;
            $this->load->view('purchase/bompiapprovalrequest', $ArrData);
        }
    }

    public function getBomPurchaseIndentJxl() {
        $VarBomPurchaseReqId = xssclean($this->input->post('bomPurchaseReqId'));
        $ArrBomPurRequestInfo = $this->mbompurchaserequestmodel->getBomPurchaseRequest($VarBomPurchaseReqId, $this->companyid);
            $jsonBomPurchaseIndent = $this->commonmodel->fnGetAllTableInfo(KN_BOM_PI_APPROVAL_REQUEST,'jsonBomJxl,advancePaymentJxl,otherDetails,taxTypeId',
                array('bomPurchaseRequestId'=>$VarBomPurchaseReqId),3);
            if(!empty($jsonBomPurchaseIndent)) {
                $jsonBomJxl = json_decode($jsonBomPurchaseIndent[0]['jsonBomJxl'],true);
            }
            else {
                $previewJxlSelected = $this->mbompurchaserequestmodel->getPreparePiForPreview($VarBomPurchaseReqId);
                if(!empty($ArrBomPurRequestInfo->bompirequestgrid_tblname)) {
                    $dynamicTblName = $ArrBomPurRequestInfo->bompirequestgrid_tblname;
                }
                if(!empty($previewJxlSelected->taxtypeid)) {
                    $VarTaxTypeId = $previewJxlSelected->taxtypeid;
                }
                if(!empty($previewJxlSelected->jsonPurchaseIndent)) {
                    $ArrPreviewJxl = json_decode($previewJxlSelected->jsonPurchaseIndent,true);
                    if($VarTaxTypeId == 1) {
                        foreach ($ArrPreviewJxl as $item) {
                            $jsonBomJxl[] = array($item[0], $item[1],$item[2], $item[3], $item[4],
                                $item[5],$item[6], $item[7], $item[8], $item[9],'','','','','',$item[10],$item[11]);
                        }
                    }
                    elseif ($VarTaxTypeId == 2) {
                        foreach ($ArrPreviewJxl as $item) {
                            $jsonBomJxl[] = array($item[0], $item[1],$item[2], $item[3], $item[4],
                                $item[5],$item[6], $item[7], $item[8], $item[9],'','','',$item[10],$item[11]);
                        }
                    }
                    elseif ($VarTaxTypeId == 3) {
                        foreach ($ArrPreviewJxl as $item) {
                            $jsonBomJxl[] = array($item[0], $item[1],$item[2], $item[3], $item[4],
                                $item[5],$item[6], $item[7], $item[8], $item[9],'','','',$item[10],$item[11]);
                        }
                        //echo '<pre>'; print_r($jsonBomJxl); die('die');
                    }
                }
            }
            echo json_encode(array('previewJxl'=>$jsonBomJxl));

    }

    /*TODO remove this if not needed
    */
    public function saveBomPiApprovalRequest() {
        $VarFrom = xssclean($this->input->post('rFrom'));
        if ($VarFrom == 1) {
            $basicData = xssclean($this->input->post('basicData'));
            $otherDetailsId = xssclean($this->input->post('otherDetailsId'));
            $advancePayment = xssclean($this->input->post('advancePayment'));
            $bomJxl = xssclean($this->input->post('bomJxl'));
            $taxTypeId = xssclean($this->input->post('taxTypeId'));
            $bomPurchaseRequestId = xssclean($this->input->post('bomPurchaseRequestId'));
            $orderId = xssclean($this->input->post('oid'));
            $Arr['otherDetails'] = $basicData;
            $Arr['advancePaymentJxl'] = $advancePayment;
            $Arr['jsonBomJxl'] = $bomJxl;
            $Arr['taxTypeId'] = $taxTypeId;
            $Arr['bomPurchaseRequestId'] = $bomPurchaseRequestId;
            $Arr['orderId'] = $orderId;
            if(empty($otherDetailsId)) {
                $this->db->insert(KN_BOM_PI_APPROVAL_REQUEST,$Arr);
                echo json_encode(array('id'=>$this->db->insert_id()));
            }
            else {
                $this->db->update(KN_BOM_PI_APPROVAL_REQUEST,$Arr,array('id'=>$otherDetailsId));
                echo json_encode(array('id'=>$otherDetailsId));
            }
        }
    }

    public function mgmtbompurchaseindentreq() {
        $VarBomPurIndId = base64_decode(urldecode($this->uri->segment(3)));
        //echo '<pre>'; print_r($VarBomPurIndId); die('die');
        if($VarBomPurIndId > 0) {
            $this->load->model(CNFCOMPANY.'mbomvendormodel');
            $ArrBasicInfo = $this->mbompurcahseindentmodel->getBomPIDetails($VarBomPurIndId);
            $ArrBasicAdvPaymentInfo = $this->mbompurcahseindentmodel->getBomPIAdvPayment($VarBomPurIndId);
            $ArrBomPurRequestInfo = $this->mbompurchaserequestmodel->getBomPurchaseRequest($ArrBasicInfo->bompurrequestid, $this->companyid);
            $VarOrderId = $ArrBasicInfo->orderid;
            $ArrData['PiNo'] = $ArrBasicInfo->isriorcode . '/' . $ArrBasicInfo->purchaseindent_no;
            $VarCommonOrderEntryInfo = $this->commonmodel->commonBasicInfoOrderEntry($VarOrderId, $this->companyid);
            $ArrOrderEnqData = $this->orderentrymodel->fnGetOrderEnquiryInfo($VarOrderId, $this->companyid, $VarStatus = 1);
            $ArrData['VarCommonOrderEntryInfo'] = $VarCommonOrderEntryInfo;
            $ArrData['ArrCompanyInfo'] = $VarCommonOrderEntryInfo['ArrCompanyInfo'];
            $ArrData['ArrMerchant'] = $VarCommonOrderEntryInfo['ArrMerchant'];
            $ArrData['ArrTeamInfo'] = $VarCommonOrderEntryInfo['ArrTeamInfo'];
            $ArrData['ArrOrderEnqData'] = $ArrOrderEnqData[0];
            $ArrData['brandName'] = $VarCommonOrderEntryInfo['brandName'];
            $ArrData['buyerName'] = $VarCommonOrderEntryInfo['buyerName'];
            $ArrData['ArrCurrencyNCode'] = unserialize(ARRCURRENCYLIST);
            $ArrData['ArrOrderCommonData'] = $VarCommonOrderEntryInfo['ArrOrderCommonData'];
            $ArrData['VarOrderId'] = $VarOrderId;
            $ArrData['VarBomPurIndentId'] = $VarBomPurIndId;
            $ArrData['ArrBasicInfo'] = $ArrBasicInfo;
            $ArrData['VendorInfo'] = $this->mbomvendormodel->fnGetInfo('','1',$ArrBasicInfo->vendorid);
            $ArrData['BomPurReqId'] = $ArrBasicInfo->bompurrequestid;
            $ArrData['ArrBasicPurRequestInfo'] = $ArrBomPurRequestInfo;
            $ArrData['RequestRaisedby'] = $this->commonmodel->getUserInfo($ArrBomPurRequestInfo->merchantid);
            $PurchaseDeptUserInfo = $this->commonmodel->getUserInfo($ArrBasicInfo->purchasedeptid);
            $ApprovedMgmtInfo = $this->commonmodel->getUserInfo($ArrBasicInfo->approvedbymgmtid);
            $ArrData['PurchaseDeptUserInfo'] = $PurchaseDeptUserInfo[0];
            $ArrData['ApprovedMgmtInfo'] = $ApprovedMgmtInfo;
            $ArrData['jsonBomPiModeOfPayment'] = json_encode(unserialize(BOMPI_MODEOFPAYMENT));
            $ArrData['ArrBomPiModeOfPayment'] = unserialize(BOMPI_MODEOFPAYMENT);
            $ArrData['jsonpaymentpaidgrid'] = empty($ArrBasicAdvPaymentInfo->paymentpaidgrid) ? 0 : $ArrBasicAdvPaymentInfo->paymentpaidgrid;
            $ArrData['advPaymentRequestJxl'] = empty($ArrBasicInfo->advPaymentReqJxl) ? 0 : $ArrBasicInfo->advPaymentReqJxl;
            if(empty($ArrBasicAdvPaymentInfo->paymentpaidgrid)) {
            }
            else {
                foreach (json_decode($ArrBasicAdvPaymentInfo->paymentpaidgrid,true) as $paymentPaidData) {
                    $ArrData['paymentpaidinfotoprint'] = $paymentPaidData[1].' / '.$paymentPaidData[2].' / '.$paymentPaidData[4].' / '.$paymentPaidData[6];
                }
            }

            $this->load->view('management/mgmtbompurchaseindentreq', $ArrData);
        }
    }

    public function stores_bompidetails() {
        $VarBomPurIndId = base64_decode(urldecode($this->uri->segment(3)));
        if($VarBomPurIndId > 0) {
            $this->load->model(CNFCOMPANY.'mbomvendormodel');
            $ArrBasicInfo = $this->mbompurcahseindentmodel->getBomPIDetails($VarBomPurIndId);
            $ArrBasicAdvPaymentInfo = $this->mbompurcahseindentmodel->getBomPIAdvPayment($VarBomPurIndId);
            $ArrBomPurRequestInfo = $this->mbompurchaserequestmodel->getBomPurchaseRequest($ArrBasicInfo->bompurrequestid, $this->companyid);
            $VarOrderId = $ArrBasicInfo->orderid;
            $ArrData['PiNo'] = $ArrBasicInfo->isriorcode . '/' . $ArrBasicInfo->purchaseindent_no;
            $VarCommonOrderEntryInfo = $this->commonmodel->commonBasicInfoOrderEntry($VarOrderId, $this->companyid);
            $ArrData['VarCommonOrderEntryInfo'] = $VarCommonOrderEntryInfo;
            $ArrData['ArrCompanyInfo'] = $VarCommonOrderEntryInfo['ArrCompanyInfo'];
            $ArrData['ArrMerchant'] = $VarCommonOrderEntryInfo['ArrMerchant'];
            $ArrData['ArrTeamInfo'] = $VarCommonOrderEntryInfo['ArrTeamInfo'];
            $ArrData['ArrOrderEnqData'] = $VarCommonOrderEntryInfo['ArrOrderEnqData'];
            $ArrData['ArrBB'] = $VarCommonOrderEntryInfo['ArrBB'];
            $ArrData['ArrCurrencyNCode'] = unserialize(ARRCURRENCYLIST);
            $ArrData['ArrOrderCommonData'] = $VarCommonOrderEntryInfo['ArrOrderCommonData'];
            $ArrData['VarOrderId'] = $VarOrderId;
            $ArrData['VarBomPurIndentId'] = $VarBomPurIndId;
            $ArrData['ArrBasicInfo'] = $ArrBasicInfo;
            $ArrData['VendorInfo'] = $this->mbomvendormodel->fnGetInfo('1',$ArrBasicInfo->vendorid);
            $ArrData['BomPurReqId'] = $ArrBasicInfo->bompurrequestid;
            $ArrData['ArrBasicPurRequestInfo'] = $ArrBomPurRequestInfo;
            $ArrData['RequestRaisedby'] = $this->commonmodel->getUserInfo($ArrBomPurRequestInfo->merchantid);
            $PurchaseDeptUserInfo = $this->commonmodel->getUserInfo($ArrBasicInfo->purchasedeptid);
            $ApprovedMgmtInfo = $this->commonmodel->getUserInfo($ArrBasicInfo->approvedbymgmtid);
            $ArrData['PurchaseDeptUserInfo'] = $PurchaseDeptUserInfo[0];
            $ArrData['ApprovedMgmtInfo'] = $ApprovedMgmtInfo;
            $ArrData['jsonBomPiModeOfPayment'] = json_encode(unserialize(BOMPI_MODEOFPAYMENT));

            $ArrData['jsonpaymentpaidgrid'] = empty($ArrBasicAdvPaymentInfo->paymentpaidgrid) ? 0 : $ArrBasicAdvPaymentInfo->paymentpaidgrid;
            if(empty($ArrBasicAdvPaymentInfo->paymentpaidgrid)) {

            }
            else {
                foreach (json_decode($ArrBasicAdvPaymentInfo->paymentpaidgrid,true) as $paymentPaidData) {
                    $ArrData['paymentpaidinfotoprint'] = $paymentPaidData[1].' / '.$paymentPaidData[2].' / '.$paymentPaidData[4].' / '.$paymentPaidData[6];
                }
            }
            $this->load->view('stores/stores_bompidetails', $ArrData);
        }
    }
}