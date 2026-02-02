<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class mfinance extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->helper('xssclean');
        $this->load->model('commonmodel');
        $ArrUserLoggedInfo = fnGetUserLoggedInfo('1');
        $this->companyid     = $ArrUserLoggedInfo['companyid'];
        $this->userid        = $ArrUserLoggedInfo['id'];
        $this->mysqldatetime = date('Y-m-d H:i:s');
        $this->limit = LIMITPERPAGE;

    }



    /*public function paymentreceivedlist() {
        $VarFrom         = xssclean($this->input->post('rfrom'));
        $VarWip          = xssclean($this->input->post('wip'));
        $VarIsrIorType   = xssclean($this->input->post('IsrIorType'));
        $VarBB           = xssclean($this->input->post('bb'));
        $VarAllReq       = xssclean($this->input->post('allreq'));
        $VarRequirement  = xssclean($this->input->post('req'));
        $VarCoffDateFrom = '';
        $VarCoffDateTo   = '';
        if (xssclean($this->input->post('cutfrom')) != '' && xssclean($this->input->post('cutto')) != '') {
            $VarCoffDateFrom = date('Y-m-d H:i:s', strtotime(xssclean($this->input->post('cutfrom'))));
            $VarCoffDateTo   = date('Y-m-d H:i:s', strtotime(xssclean($this->input->post('cutto'))));
        }
        $VarApprovalType = xssclean($this->input->post('at'));
        $VarCStatus      = xssclean($this->input->post('cs'));
        $clickedColumnId = xssclean($this->input->post('columnId'));
        $newsortorder    = xssclean($this->input->post('sortorder'));
        $VarAfilter      = xssclean($this->input->post('afilter'));
        $ArrDbCols       = array('c.status', 'u.contactname', 'c.dateupdated');
        $ArrBB           = $this->commonmodel->fnGetBuyerAndBrand();
        $ArrUserInfo     = fnGetUserLoggedInfo('1');
        $ArrRequirement  = unserialize(ARRBOMREQUIREMENT);
        $ArrData         = array('ArrBB' => $ArrBB, 'ArrMerchant' => $this->commonmodel->getMerchantData($this->companyid, 1), 'usertype' => $ArrUserInfo['usertype'],
            'ArrRequirements' => $ArrRequirement);
        if ($VarFrom == 1) {
            $ArrProfileInfo = fnGetUserLoggedInfo(1);
            $VarURLSegment = 3;
            $this->load->library('pagination');
            $config['base_url']    = base_url('dashboard/allqueuelist/');
            $config['total_rows']  = $this->bomPaymentRecdCount();
            $config['per_page']    = $this->limit;
            $config['uri_segment'] = $VarURLSegment;
            $offset                = $this->uri->segment($VarURLSegment);
            $this->pagination->initialize($config);
            $sortby    = "dateupdated";
            $sortorder = "desc";
            if ($clickedColumnId <> '' && $newsortorder <> '') {
                if (array_key_exists($clickedColumnId, $ArrDbCols)) {
                    $sortby = $ArrDbCols[$clickedColumnId];
                }
                $sortorder = $newsortorder;
            }
            $ArrList     = $this->bomPaymentRecdList()->result();
            $data['pagination']    = $this->pagination->create_linkswithajax('AllQList');
            $i                     = 0;
            $ArrFnlList            = array(); $ArrApprStatus = unserialize(REQUESTSTATUSARR); $ArrStatus = unserialize(ARRSTATUS);
            foreach ($ArrList as $ObjData) {
                $ArrFnlList[$i]['id']  = $ObjData->id;
                $ArrFnlList[$i]['wip'] = $ObjData->isriorcode;
                $ArrFnlList[$i]['ven'] = $ObjData->vendorname;
                $ArrFnlList[$i]['pino'] = $ObjData->purchaseindentno.'/'.$ObjData->isriorcode;
                $ArrFnlList[$i]['req'] = empty($ObjData->requirementid) ? '-' : $ArrRequirement[$ObjData->requirementid];
                $ArrFnlList[$i]['reqdt'] = date('d-m-Y',strtotime($ObjData->requestdt));
                $ArrFnlList[$i]['cutoff'] = date('d-m-Y',strtotime($ObjData->cutoffdatetime));
                $ArrFnlList[$i]['apprstatus'] = $ArrApprStatus[$ObjData->apprstatus];
                $VarApprBy = $this->commonmodel->getUserInfo($ObjData->approvedby,'',$this->companyid);
                $ArrFnlList[$i]['apprby'] = empty($VarApprBy) ? '-' : $VarApprBy[0]->contactname;
                $ArrFnlList[$i]['cs'] = $ObjData->apprstatus == 2 ? 'APPROVED' : 'PENDING';
                $ArrFnlList[$i]['ru'] = date('d-m-Y H:i:s',strtotime($ObjData->dateupdated));
                $ArrFnlList[$i]['s'] = $ArrStatus[$ObjData->status];
                $i                    = $i + 1;
            }
            echo json_encode(array('errcode' => 1, 'cn' => $config['total_rows'], 'ct' => $i, 're' => $ArrFnlList, 'pa' => base64_encode($data['pagination'])));
            unset($ArrFnlList);
            die;
        } else {
            $this->load->view('mfinance/paymentreceivedlist',$ArrData);
        }
    }*/
    public function bomPaymentRecdCount() {
        $VarSqlCount = "SELECT count(1) as trec  FROM " . KN_BOMPAYMENT_REQUEST . " WHERE companyid = '$this->companyid' AND status = '1' ";
        $ObjRows     = $this->db->query($VarSqlCount)->row();
        return $ObjRows->trec;
    }
    public function bomPaymentRecdList() {
        $VarSqlList = "SELECT p.id,a.isriorcode,v.vendorname,purchaseindentno,vendorid,articletype,requestdt,cutoffdatetime,p.apprstatus,p.approvedby,
p.status,p.dateupdated,a.requirementid FROM ".KN_BOMPURCHASEINDENTINVOICE." AS inv INNER JOIN ".KN_MASTER_BOM_VENDOR." AS v ON v.id = inv.purchasetovendor INNER JOIN ".KN_BOMPAYMENT_REQUEST." AS p
 ON inv.id = p.bompurchaseinvoiceid INNER JOIN ".KN_ALLREQUEST." AS a ON a.id = p.bompurchaseindreqid WHERE p.companyid = '2' AND p.status = '1' ORDER BY p.dateupdated DESC";
        $ObjResult = $this->db->query($VarSqlList);
        return $ObjResult;
    }
/*    public function commonBasicInfoOrderEntry($VarOrderId='') {
        $this->load->model(CNFCOMPANY.'orderentrymodel');
        $ArrOrderDatas           = $this->orderentrymodel->getOrderDataByWip('', $VarOrderId, $this->companyid);
        //$ArrOrderEnqData               = $this->orderentrymodel->fnGetOrderEnquiryInfo($VarOrderId, $this->companyid, '');
        $ArrOrderEnqData = $this->commonmodel->fnGetAllTableInfo(KN_ORDER_ENQUIRY,'id,stylenamerefno,styledesc,pcsorset,exporderqty,brandbuyerid,merchantid,isriorcode',
            array('id'=>$VarOrderId,'companyid'=>$this->companyid,'status'=>'1'),3);
        $ArrBB                         = $this->orderentrymodel->fnBuyerbyBrandId($ArrOrderEnqData[0]['brandbuyerid']);
        $ArrMerchant                   = $this->commonmodel->getMerchantData($this->companyid, 1);
        $ArrTeamInfo                   = $this->commonmodel->getTeamDetails('', $ArrOrderDatas[0]->teamid, 1);

        $ArrCompanyRes = $this->commonmodel->fnGetAllTableInfo(KN_COMPANY_DETAILS,'ceoname,companyname,address,emailid,mobile',array('id'=>$this->companyid),3);
        $VarMerchantInfo               = $this->commonmodel->getUserInfo($ArrOrderEnqData[0]['merchantid']);
        $ArrData['merchantname']       = $VarMerchantInfo[0]->contactname;
        $ArrData['ArrBB']              = $ArrBB[0];
        $ArrData['ArrMerchant']        = $ArrMerchant[0];
        $ArrData['ArrTeamInfo']        = $ArrTeamInfo[0];
        $ArrData['ArrCompanyInfo']     = $ArrCompanyRes;
        $ArrData['ArrOrderEnqData']    = $ArrOrderEnqData[0];
        $ArrData['ArrOrderDatas']      = $ArrOrderDatas[0];
        return $ArrData;
    }*/
/*    public function bomPurchasePayemntReqFinance() {
        $VarId = $this->uri->segment(3); $Varpayment_completed_status = '';
        $PaymentTblRes = $this->commonmodel->fnGetAllTableInfo(KN_BOMPAYMENT_REQUEST,'bompurchaseinvoiceid,bompurchaseindreqid,advancepayment,billpayment,proformano,
        proformadate,proformadate,proformavalue,advpayable,reqmodeofpayment,paybydate,apprstatus,billpayapprstatus,approvedby,invoiceno,invoicedate,invoicevalue,
        advpaid,debitvalue,amountpayable,paymentduedate,currency',array('id'=>$VarId),3);
        $PaymentTbl = $PaymentTblRes[0];
        $varInv = $PaymentTbl['invoicevalue'];
        $advPayable = $PaymentTbl['advpayable'];
        $varBal = $varInv - $advPayable;
        if($varBal == '0.00') {
            $Varpayment_completed_status = 1;
        }
        else {
            $Varpayment_completed_status = 0;
        }

        $ArrAllRequestData      = $this->commonmodel->getAllRequestDetails($PaymentTbl['bompurchaseindreqid'], $this->companyid);
        $VarOrderId = $ArrAllRequestData->orderid;
        $ArrInvDataRes = $this->commonmodel->getBomPurchaseIndentInvoice($PaymentTbl['bompurchaseinvoiceid'],$this->companyid);
        $ArrData = $this->commonmodel->commonBasicInfoOrderEntry($VarOrderId,$this->companyid);
        $ArrInvData = $ArrInvDataRes[0];
        $ArrCurrency                = unserialize(ARRCURRENCYLIST);
        $ArrFinanceDeptInfoRes = $this->commonmodel->fnGetAllTableInfo(KN_BOMPAYMENT_REQUEST_CHILD,'parentid,bompurchaseinvoiceid,
        bompurchaseindreqid,financedeptid,advancepayment,billpayment,modeofpayment,chequeno,chequedate,transid,transdate,advpaid,
        currency,amountpaid,full_part_id,baltopay,status',array('parentid'=>$VarId,'companyid'=>$this->companyid),3);

        $ArrData['ArrCurrencyCode'] = $ArrCurrency;
        $ArrData['ArrBasicInfo'] = $ArrInvData;
        $ArrData['ArrBasicPaymentInfo'] = $PaymentTbl;
        $VarApprBy = $this->commonmodel->getUserInfo($PaymentTbl['approvedby'],'',$this->companyid);
        $ArrData['apprbyname'] = (empty($VarApprBy)) ? '-' : $VarApprBy[0]->contactname;
        $ArrData['ArrBasicFinanceDeptInfo'] = $ArrFinanceDeptInfoRes;
        $ArrData['VarId'] = $VarId;
        $ArrData['VarBomPurchaseInvoiceId'] = $PaymentTbl['bompurchaseinvoiceid'];
        $ArrData['VarBomPurIndReqId'] = $PaymentTbl['bompurchaseindreqid'];
        $ArrData['VarBomPurIndReqId'] = $PaymentTbl['bompurchaseindreqid'];
        $VarPassedBy = $this->commonmodel->getUserInfo(@$PaymentTbl['approvedby'],'',$this->companyid);
        $VarVerifiedBy = $this->commonmodel->getUserInfo(@$ArrFinanceDeptInfoRes[0]['financedeptid'],'',$this->companyid);

        $ArrData['VarBillVerifiedby'] = empty($VarVerifiedBy) ? '-' : $VarVerifiedBy[0]->contactname;
        $ArrData['VarPassedBy'] = empty($VarPassedBy) ? '-' : $VarPassedBy[0]->contactname;
        $ArrData['SavedInvoiceGrid'] = $ArrInvData->invoicegrid;
        $ArrData['VarCurrency'] = $ArrCurrency[$ArrInvData->currencycode];
        $ArrData['VarBalancetoPay'] = $ArrCurrency[$ArrInvData->currencycode];
        $ArrData['Varpayment_completed_status'] = $Varpayment_completed_status;

        $this->load->view('mfinance/bomPurchasePayemntReqFinance',$ArrData);
    }*/
    /*public function updateBomPaymentReq() {
        $VarId = xssclean($this->input->post('id'));
        $VarAdvPayment = xssclean($this->input->post('advancepayment'));
        $VarBillPayment = xssclean($this->input->post('billpayment'));
        $VarBompurchaseinvoiceid = xssclean($this->input->post('bompurchaseinvoiceid'));
        $VarBompurchaseindreqid = xssclean($this->input->post('bompurchaseindreqid'));
        $VarBillPaymentfrmCurrency = xssclean($this->input->post('BillPaymentfrmCurrency'));
        $VarfrmCurrency = xssclean($this->input->post('frmCurrency'));
        $ArrUpdate = array(); $VarPaymentComplatedStatus = '';
        if($VarAdvPayment) {
            $VarModeofPayment = xssclean($this->input->post('ReqdModeOfPayment'));
            $VarCheqNo = xssclean($this->input->post('frmCheqNo'));
            if(xssclean($this->input->post('frmCheqDate')))
                $VarCheqDate = date('Y-m-d',strtotime(xssclean($this->input->post('frmCheqDate'))));
            else
                $VarCheqDate = NULL;
            $VarTransId = xssclean($this->input->post('frmTransId'));

            if(empty(xssclean($this->input->post('frmTransDate'))))
                $VarTransDate = NULL;
            else
                $VarTransDate = date('Y-m-d',strtotime(xssclean($this->input->post('frmTransDate'))));

            $VarAdvPaid = xssclean($this->input->post('frmAdvPaid'));
            $VarPaidFullPart = xssclean($this->input->post('frmFullPart'));
            $VarBaltoPay = xssclean($this->input->post('frmBaltoPay'));
            if($VarBaltoPay == 0.00) {
                $VarPaymentComplatedStatus = 1;
            }
            else {
                $VarPaymentComplatedStatus = 0;
            }
            $ArrUpdate = array(
                'companyid'=>$this->companyid,
                'parentid'=>$VarId,
                'bompurchaseinvoiceid'=>$VarBompurchaseinvoiceid,
                'bompurchaseindreqid'=>$VarBompurchaseindreqid,
                'modeofpayment'=>$VarModeofPayment,
                'chequeno'=>$VarCheqNo,
                'chequedate'=>$VarCheqDate,
                'transid'=>$VarTransId,
                'transdate'=>$VarTransDate,
                'advpaid'=>$VarAdvPaid,
                'full_part_id'=>$VarPaidFullPart,
                'baltopay'=>$VarBaltoPay,
                'financedeptid'=>$this->userid,
                'datecreated'=>$this->mysqldatetime,
                'dateupdated'=>$this->mysqldatetime,
                'status'=>'1',
                'currency'=>$VarfrmCurrency,
                'payment_completed_status'=>$VarPaymentComplatedStatus,
                'advancepayment'=>$VarAdvPayment
            );
        }
        elseif ($VarBillPayment) {
            $VarModeofPayment = xssclean($this->input->post('BillpaymentReqdModeOfPayment'));
            $VarCheqNo = xssclean($this->input->post('BillPaymentfrmCheqNo'));
            if(xssclean($this->input->post('BillPaymentfrmCheqDate')))
                $VarCheqDate = date('Y-m-d',strtotime(xssclean($this->input->post('BillPaymentfrmCheqDate'))));
            else
                $VarCheqDate = NULL;
            $VarTransId = xssclean($this->input->post('BillPaymentfrmTransId'));
            if(empty(xssclean($this->input->post('BillpaymentfrmTransDate'))))
                $VarTransDate = NULL;
            else
                $VarTransDate = date('Y-m-d',strtotime(xssclean($this->input->post('BillpaymentfrmTransDate'))));
            $VarAmountpaid = xssclean($this->input->post('BillPaymentfrmAmountPaid'));
            $VarPaidFullPart = xssclean($this->input->post('BillPaymentfrmFullPart'));
            $VarBaltoPay = xssclean($this->input->post('BillPaymentfrmBaltoPay'));
            if($VarBaltoPay == 0.00) {
                $VarPaymentComplatedStatus = 0;
            }
            else {
                $VarPaymentComplatedStatus = 1;
            }
            $ArrUpdate = array(
                'companyid'=>$this->companyid,
                'parentid'=>$VarId,
                'bompurchaseinvoiceid'=>$VarBompurchaseinvoiceid,
                'bompurchaseindreqid'=>$VarBompurchaseindreqid,
                'modeofpayment'=>$VarModeofPayment,
                'chequeno'=>$VarCheqNo,
                'chequedate'=>$VarCheqDate,
                'transid'=>$VarTransId,
                'transdate'=>$VarTransDate,
                'amountpaid'=>$VarAmountpaid,
                'full_part_id'=>$VarPaidFullPart,
                'baltopay'=>$VarBaltoPay,
                'financedeptid'=>$this->userid,
                'datecreated'=>$this->mysqldatetime,
                'dateupdated'=>$this->mysqldatetime,
                'status'=>'1',
                'currency'=>$VarBillPaymentfrmCurrency,
                'payment_completed_status'=>$VarPaymentComplatedStatus,
                'billpayment'=>$VarBillPayment
            );
        }
        $this->db->insert(KN_BOMPAYMENT_REQUEST_CHILD,$ArrUpdate);
        $VarId = $this->db->insert_id();
        $ArrResult = array();
        if($VarId) {
            $ArrResult['errcode'] = 1;
            $ArrResult['msg']     = '';
            $ArrResult['eid']     = urlencode(base64_encode($VarId));
        }
        echo json_encode($ArrResult);
        die;
    }*/

    public function bomPiAdvPaymentList() {
        $Rfrom = xssclean($this->input->post('rfrom'));
        if($Rfrom == 1) {
            $this->load->model('mbompurcahseindentmodel');
            $ArrList = $this->mbompurcahseindentmodel->bomPIAdvpayListDatatablesAjax();
            $data = array();
            $ArrStatus = unserialize(ARRSTATUS);
            foreach ($ArrList as $Obj) {
                $row = array();
                $row[] = '<input type="checkbox" class="allcbox" id="' . $Obj->id . '">';
                $row[] = $Obj->isriorcode;
                $row[] = $Obj->brandname;
                $row[] = '<a href="' . base_url('mfinance/bom_purchase_indent_details') . '/' . urlencode(base64_encode($Obj->id)) . '">' . $Obj->isriorcode . '/' . BOMPURIND_PREFIX . '-' . $Obj->purchaseindent_no . '</a>';
                $row[] = 'Requirement';
                $row[] = $Obj->datecreated;
                $row[] = $Obj->cutoffdatetime;
                $row[] = $Obj->approvedByMgmt;
                $row[] = $Obj->contactname;
                $row[] = '<a href="javascript:void(0)">' . $Obj->current_status . '</a>';
                $row[] = date('d-m-Y H:i:s', strtotime($Obj->dateupdated));
                $row[] = $ArrStatus[$Obj->status];
                $data[] = $row;
            }
            $output = array(
                "draw" => $_POST['draw'],
                "recordsTotal" => $this->mbompurcahseindentmodel->countBomPIAdvPayListAll(),
                "recordsFiltered" => $this->mbompurcahseindentmodel->countBomPIAdvpayListFiltered(),
                "data" => $data,
            );
            echo json_encode($output);
        }
        else {
            $this->load->view('mfinance/bomPIAdvPayemntList',array());
        }
    }

    public function bom_purchase_indent_details() {
        $VarBomPurchaseIndentId = base64_decode(urldecode($this->uri->segment(3)));
        $this->load->model('mbompurcahseindentmodel');
        $this->load->model('mbompurchaserequestmodel');
        $this->load->model(CNFCOMPANY.'mbomvendormodel');
        $this->load->model(CNFCOMPANY.'orderentrymodel');
        $ArrBasicInfo = $this->mbompurcahseindentmodel->getBomPIDetails($VarBomPurchaseIndentId);
        $jsonPurchaseIndentJxl = '';
        if(!empty($ArrBasicInfo->purchaseindgrid)) {
            $jsonPurchaseIndentJxl = $ArrBasicInfo->purchaseindgrid;
        }
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
        $ArrData['VarBomPurIndentId'] = $VarBomPurchaseIndentId;
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
        $ArrData['advPaymentRequestJxl'] = empty($ArrBasicInfo->advPaymentReqJxl) ? 0 : $ArrBasicInfo->advPaymentReqJxl;
        $ArrData['purchaseIndSavedGrid'] = $jsonPurchaseIndentJxl;
        $this->load->view('mfinance/bom_purchase_indent_details',$ArrData);
    }

    public function updateBomPIPayAdvDetails() {
        $rFrom  = xssclean($this->input->post('rfrom'));
        if($rFrom == 1) {
            $VarBomPurIndId  = xssclean($this->input->post('bomPurIndentId'));
            $bomPurRequestId  = xssclean($this->input->post('bompurrequestid'));
            //$VarAdvPaymentId  = xssclean($this->input->post('AdvPaymentId'));
            //$VarPaymentStatus  = xssclean($this->input->post('paymentstatus'));
            $financeDeptPayPaidJxl  = xssclean($this->input->post('financeDeptPayPaidJxl'));
            $this->load->model('mbompurcahseindentmodel');
            $bomAdvPayPaid = array('bompurrequestid'=>$bomPurRequestId,'bompurindentid'=>$VarBomPurIndId,'financedeptid'=>$this->userid,
                'paymentpaidgrid'=>$financeDeptPayPaidJxl,'paymentstatus'=>0);
    //if($VarAdvPaymentId) {
        //$bomAdvPayPaid['dateupdated'] = $this->mysqldatetime;
    //}
    //else {
            $bomAdvPayPaid['datecreated'] = $this->mysqldatetime;
    //}
            $ArrJsonRes = $this->mbompurcahseindentmodel->saveBomPurIndPayPaidDetails($bomAdvPayPaid,$VarBomPurIndId);
            echo json_encode(array('errcode'=>1,'msg'=>'Saved Successfully'));
            die;
        }
    }
}