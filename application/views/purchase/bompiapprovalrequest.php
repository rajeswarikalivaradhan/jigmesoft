<?php $this->load->view(CNFCOMPANY . 'template/pageheader'); ?>
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/jexcel4/jsuites.css"/>
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/jexcel4/jexcel.css"/>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/plugins/datepicker/datepicker3.css">
    <link rel="stylesheet" href="<?php echo base_url();?>assets/css/uploadfile-order.css"/>
    <style type="text/css">
        td div {
            font-family: Verdana, Geneva, sans-serif;
            font-size: 12px;
            line-height: 15px;
            /*padding: 5px 2px;*/
        }

        td {
            font-family: Verdana, Geneva, sans-serif;
        }

        table, .control-label {
            margin-bottom: 0 !important;
            font-size: 12px;
        }

        .form-control {
            height: 25px;
            padding: 3px 2px !important;
            font-size: 12px;
        }

        .table, .secondheading {
            background-color: #ecf0f5;
        }

        .table td.secondheading {
            padding-top: 15px;
        }
    </style>
<body class="sidebar-mini skin-black wysihtml5-supported sidebar-collapse">
<div class="wrapper">
    <?php $this->load->view(CNFCOMPANY . 'template/templateheader'); ?>
    <!-- Left side column. contains the logo and sidebar -->
    <aside class="main-sidebar">
        <?php $this->load->view(CNFCOMPANY . 'template/templateleftmenu'); ?>
    </aside>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <section class="content">
            <!-- Default box -->
            <div class="box-body">
                <?php $this->load->view('commonBasicInfoOrderEntry') ?>
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h4 class="text-center"><b>PURCAHSE INDENT</b></h4>
                        <div class="box-tools pull-right">
                            <div id="bomPiTaxType"></div>
                        </div>
                    </div>
                    <div class="box-body">
                        <form class="" action="#" name="frmBasicInfo" id="frmBasicInfo" method="post"
                              autocomplete="off">
                            <table class="table">
                                <tr>
                                    <td>
                                        <table class="table">
                                            <tr>
                                                <th style="background-color: #f3f3f3" colspan="2">FROM</th>
                                            </tr>
                                            <tr>
                                                <td>NAME</td>
                                                <td><?php echo $ArrCompanyInfo[0]['ceoname'] ?></td>
                                            </tr>
                                            <tr>
                                                <td>ADDRESS :</td>
                                                <td><?php echo $ArrCompanyInfo[0]['address'] ?></td>
                                            </tr>
                                            <tr>
                                                <td>CONTACT NO :</td>
                                                <td><?php echo $ArrCompanyInfo[0]['mobile'] ?></td>
                                            </tr>
                                            <tr>
                                                <td>E-MAIL ID :</td>
                                                <td><?php echo $ArrCompanyInfo[0]['emailid'] ?></td>
                                            </tr>
                                            <tr>
                                                <td>GST No :</td>
                                                <td><?php echo @$ArrCompanyInfo[0]['username'] ?></td>
                                            </tr>
                                            <tr>
                                                <td>IE CODE :</td>
                                                <td><?php echo @$ArrCompanyInfo[0]['username'] ?></td>
                                            </tr>
                                        </table>
                                    </td>
                                    <td>
                                        <table class="table">
                                            <tr>
                                                <th style="background-color: #f3f3f3" colspan="2">TO</th>
                                            </tr>
                                            <tr>
                                                <td style="padding-top: 15px">NAME</td>
                                                <td>
                                                    <select class="form-control" id="frmSelToVendorname"
                                                            onchange="fnSelToVendorname(this.value)">
                                                        <option value="">Choose Vendor</option>
                                                        <?php
                                                        foreach ($ArrObjToVendorname as $VarVname) {
                                                            ?>
                                                            <option value="<?php echo $VarVname->id ?>" <?php if (@$ArrBasicInfo->purchasetovendor == $VarVname->id) echo 'selected' ?>>
                                                                <?php echo $VarVname->vendorname ?></option>
                                                            <?php
                                                        }
                                                        ?>
                                                    </select>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>ADDRESS</td>
                                                <td id="toaddress"><?php echo @$ArrBasicInfo->address ?></td>
                                            </tr>
                                            <tr>
                                                <td>CONTACT NO:</td>
                                                <td id="contactno"><?php echo @$ArrBasicInfo->phone ?></td>
                                            </tr>
                                            <tr>
                                                <td>E-MAIL ID:</td>
                                                <td id="emailid"><?php echo @$ArrBasicInfo->emailid ?></td>
                                            </tr>
                                            <tr>
                                                <td>GST NO:</td>
                                                <td id="gstno"><?php echo @$ArrBasicInfo->gstno ?></td>
                                            </tr>
                                            <tr>
                                                <td>IE CODE:</td>
                                                <td id="iecode"><?php echo @$ArrBasicInfo->iecode ?></td>
                                            </tr>
                                        </table>
                                    </td>
                                    <td>
                                        <table class="table">
                                            <tr>
                                                <th style="background-color: #f3f3f3; text-align: center" colspan="2">
                                                    PURCHASE REFERENCE
                                                </th>
                                            </tr>
                                            <tr>
                                                <td><b>P.I. REF. NO:</b></td>
                                                <td><div id="PurchaseIndentNo"></div></td>
                                            </tr>
                                            <tr>
                                                <td>DATE & TIME:</td>
                                                <td>
                                                    <?php if (empty($ArrBasicInfo->datecreated))
                                                        echo date('d-m-Y');
                                                    else echo date('d-m-Y', strtotime($ArrBasicInfo->datecreated)) ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>AGREED SUPPLY DATE:</td>
                                                <td>
                                                    <div class="input-group">
                                                        <input type='text' class="form-control"
                                                               placeholder="Agreed Supply Date" id="agreedsupplydate"/>
                                                        <span class="input-group-addon"><span
                                                                    class="glyphicon glyphicon-calendar"></span></span>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>SUPPLY CUTOFF DATE:</td>
                                                <td><?php
                                                    if (!empty($ArrBasicInfo->cutoffdatetime))
                                                        echo date('d-m-Y H:i:s', strtotime($ArrBasicInfo->cutoffdatetime))
                                                    ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>PAYMENT TERMS:</td>
                                                <td>
                                                    <input type="text" class="form-control" id="frmBasicPaymentTerms">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><b>INTERNAL REFERENCE</b></td>
                                            </tr>
                                            <tr>
                                                <td>WIP NO:</td>
                                                <td id="wiprefno"><?php echo $ArrOrderEnqData['isriorcode'] ?></td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </form>
                    </div>
                    <div class="box-body table-responsive">
                        <div class="row"></div>
                        <small>Herewith, we place an order for the following items:</small>
                        <div id="bomPurchaseIndentJxl"></div>
                    </div>
                    <div class="box-body">
                        <form class="form-horizontal">
                            <div class="form-group">
                                <label class="col-md-2"><b>Amount in words:</b></label>
                                <div class="col-md-10">
                                    <input type="text" id="frmAmountInWords" class="form-control">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2"><b>Advance Paid Details:</b></label>
                                <div class="col-md-10">
                                    <input type="text" class="form-control" id="advancePaidDetailsRef" readonly value="<?php echo '' ?>">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-2" style="border: 1px solid #f4f4f4">
                                    <label><b>Note:</b></label>
                                    <p style="font-size: 11px; display: inline">If goods are supplied beyond cutoff
                                        date, it is up to the discretion of the company to accept or reject the goods.
                                        Terms and conditions as agreed upon.</p>
                                </div>
                                <label class="col-md-2"><b>Remarks:</b></label>
                                <div class="col-md-10">
                                    <textarea id="frmRemarks" class="form-control"></textarea>
                                </div>
                            </div>
                        </form>
                        <div class="row">
                            <div class="col-md-2">
                                <div class="table-responsive">
                                    <table>
                                        <tr>
                                            <td colspan="2"><b>PURCHASER CONTACT DETAILS</b></td>
                                        </tr>
                                        <tr>
                                            <td>NAME:</td>
                                            <td><input class="form-control" id="frmBasicPurchaserName" type="text"></td>
                                        </tr>
                                        <tr>
                                            <td>MOBILE:</td>
                                            <td><input class="form-control" id="frmBasicPurchaserMobile" type="text"></td>
                                        </tr>
                                        <tr>
                                            <td>EMAIL:</td>
                                            <td><input class="form-control" id="frmBasicPurchaserEmail" type="text"></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="table-responsive">
                                    <table>
                                        <tr>
                                            <td colspan="2"><b>VENDOR CONTACT DETAILS</b></td>
                                        </tr>
                                        <tr>
                                            <td>NAME:</td>
                                            <td><input class="form-control" id="frmExtraVendorName" type="text"></td>
                                        </tr>
                                        <tr>
                                            <td>MOBILE:</td>
                                            <td><input class="form-control" id="frmExtraVendorMobile" type="text"></td>
                                        </tr>
                                        <tr>
                                            <td>EMAIL:</td>
                                            <td><input class="form-control" id="frmExtraVendorEmail" type="text"></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <label class="control-label">Request Raised by:</label>
                                <br/>
                                <br/>
                                <br/>
                                <?php echo $VarMerchantInfo[0]['contactname']; ?>
                                <br/>
                                <label class="control-label">Name & Signature</label>
                                <br/>

                            </div>
                            <div class="col-md-2">
                                <label class="control-label">Authorized by:</label>
                                <br/>
                                <br/>
                                <br/>
                                <span class="approvedByMgmt"><?php if(!empty($VarAuthorizedBy)) echo $VarAuthorizedBy; ?></span>
                                <br/>
                                <label class="control-label">Name & Signature</label>
                                <br/>
                                <?php echo '' ?>
                            </div>
                            <div class="col-md-2">
                                <label class="control-label">Approved by:</label><br/>
                                <?php echo '' ?>
                                <br/><br/>
                                <br/>
                                <label class="control-label">Name & Signature</label>
                                <br/>
                                <span class="approvedBymgmt2"><?php echo '' ?></span>
                            </div>
                            <div class="col-md-2">
                                <label class="control-label">For</label>
                                <br/>
                                <br/>
                                <br/>
                                <?php echo $ArrCompanyInfo[0]['companyname']; ?>
                                <br/>
                                <label class="control-label">Authorized Signatory</label>
                                <br/>
                                <span class="companyAuthSign"><?php echo '' ?></span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title"><b>APPROVAL DETAILS</b></h3>
                    </div>
                    <div class="box-body">
                        <form class="form-horizontal" id="frmBomPIAdvPaymentAppr">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="col-md-8">PREPARED BY</label>
                                    <div class="col-md-12">
                                        <span class="form-control" id="bomPIAdvPaymentApprStatus">
                                            <?php
                                            if(!empty($ArrBasicInfo->approvedbymgmtid))
                                                echo $ArrBasicInfo->approvedbymgmtid;
                                            ?>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="col-md-8">APPROVAL STATUS</label>
                                    <div class="col-md-12">
                                        <span class="form-control" id="bomPIAdvPaymentApprStatus">
                                            <?php
                                            if(!empty($ArrBasicInfo->approvedstatus))
                                                echo $ArrBasicInfo->approvedstatus;
                                            ?>
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="col-md-8">APPROVED / DECLINED BY</label>
                                    <div class="col-md-12">
                                        <span class="form-control" id="bomPIAdvPaymentApprBy">
                                            <?php
                                            if(!empty($ArrBasicInfo->approvedbymgmtid))
                                                echo $ArrBasicInfo->approvedbymgmtid;
                                            ?>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="col-md-8">APPROVED DATE & TIME</label>
                                    <div class="col-md-12">
                                        <span class="form-control" id="bomPIAdvPaymentApprBy">
                                            <?php
                                            if(!empty($ArrBasicInfo->approvedDatetime))
                                                echo $ArrBasicInfo->approvedDatetime
                                            ?>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>

                </div>

                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title"><b>BOM ADVANCE PAYMENT - APPROVAL REQUEST</b></h3>
                    </div>
                    <div class="box-body" id="bomPiAdvancePaymentDiv">
                        <div id="advPaymentRequestJxl"></div>
                        <!--<form name="frmAdvPayment" id="frmAdvPayment" class="">
                            <table class="table">
                                <tr>
                                    <th>WIP No.</th>
                                    <th>Proforma Date</th>
                                    <th>Proforma No.</th>
                                    <th>Currency</th>
                                    <th>Proforma Value</th>
                                    <th>Advance Payable Amount</th>
                                    <th>Requested Mode of Payment</th>
                                    <th>Pay by Date</th>
                                </tr>
                                <tr>
                                    <td><?php /*echo $ArrOrderEnqData['isriorcode'] */?></td>
                                    <td>
                                        <input type="text" id="proformaDate" name="proformaDate" style="width: 90px"
                                               placeholder="Choose" class="form-control"
                                               value="<?php /*if (!empty($ArrBasicPaymentInfo['proformadate'])) echo date('d-m-Y', (strtotime($ArrBasicPaymentInfo['proformadate']))) */?>">
                                    </td>
                                    <td>
                                        <input type="text" id="proformaNo" name="proformaNo" style="width: 110px" class="form-control"
                                               value="<?php /*if (!empty($ArrBasicPaymentInfo['proformano'])) echo $ArrBasicPaymentInfo['proformano'] */?>">
                                    </td>

                                    <td>
                                        <select id="frmCurrencyCode" class="form-control">
                                            <?php
/*                                            foreach ($ArrCurrencyNCode as $currencyCode) {
                                                echo '<option value="' . $currencyCode . '">' . $currencyCode . '</option>';
                                            }
                                            */?>
                                        </select>
                                    </td>

                                    <td>
                                        <input type="text" id="proformaValue" name="proformaValue" style="width: 110px" class="form-control"
                                               value="<?php /*if (!empty($ArrBasicPaymentInfo['proformavalue'])) echo $ArrBasicPaymentInfo['proformavalue'] */?>">
                                    </td>
                                    <td><input type="text" id="bomPIAdvancePayableAmount" style="width: 110px" class="form-control" value="<?php /**/?>"></td>
                                    <td>
                                        <?php /*$ArrBomPiModeOfPayment */?>
                                        <select id="ReqdModeOfPayment" name="ReqdModeOfPayment" class="form-control">
                                            <option value="">Select</option>
                                            <?php
/*                                            foreach ($ArrBomPiModeOfPayment as $mopKey => $mop) {
                                                */?>
                                                <option value="<?php /*echo $mopKey */?>" <?php /*if (@$ArrBasicPaymentInfo['reqmodeofpayment'] == $mopKey) echo 'selected' */?>>
                                                <?php /*echo $mop */?>
                                                </option>
                                                <?php
/*                                            }
                                            */?>

                                        </select>
                                    </td>
                                    <td>
                                        <input type="text" id="payByDate" name="payByDate" style="width: 90px"
                                               placeholder="Choose" class="form-control"
                                               value="<?php /*if (!empty($ArrBasicPaymentInfo['paybydate'])) echo date('d-m-Y', strtotime($ArrBasicPaymentInfo['paybydate'])) */?>">
                                    </td>
                                    <td>
                                        <?php
/*                                        $ArrApprStatu = unserialize(REQUESTSTATUSARR);
                                        if (!empty($ArrBasicPaymentInfo['apprstatus'])) echo $ArrApprStatu[$ArrBasicPaymentInfo['apprstatus']];
                                        */?>
                                    </td>
                                    <td id="ApprdBy"><?php /*echo @$apprbyname */?></td>
                                </tr>
                            </table>
                        </form>-->


                            <div class="box-header with-border">
                                <h3 class="box-title"><b>Vendors' Bank Details</b></h3>
                            </div>
                            <div id="vendorBankDetailsJxl"></div>
                            <!--<div class="box-tools pull-right"></div>
                            <div class="box-body">
                                <table class="table">
                                    <tr>
                                        <th>Vendor Name</th>
                                        <th>Bank Name</th>
                                        <th>Account Name</th>
                                        <th>Account No.</th>
                                        <th>IFS Code</th>
                                        <th>RTGS</th>
                                        <th>SWIFT Code</th>
                                        <th>IBAN</th>
                                    </tr>
                                    <tr>
                                        <td><span id="v_name" class="form-control" readonly></span> <?php /*//echo $ArrBasicInfo->contactname */?></td>
                                        <td><span id="v_BankName" class="form-control" readonly></span> <?php /*//echo $ArrBasicInfo->bankname */?> </td>
                                        <td><span id="v_accName" class="form-control" readonly></span> <?php /*//echo $ArrBasicInfo->accountname */?></td>
                                        <td><span id="v_accNo" class="form-control" readonly></span> <?php /*//echo $ArrBasicInfo->accountno */?></td>
                                        <td><span id="v_ifscode" class="form-control" readonly></span> <?php /*//echo $ArrBasicInfo->ifscode */?> </td>
                                        <td><span id="v_rtgs" class="form-control" readonly></span> <?php /*//echo $ArrBasicInfo->rtgs */?></td>
                                        <td><span id="v_swiftcode" class="form-control" readonly></span> <?php /*//echo $ArrBasicInfo->swiftcode */?></td>
                                        <td><span id="v_iban" class="form-control" readonly></span> <?php /*//echo $ArrBasicInfo->iban */?> </td>
                                    </tr>
                                </table>
                            </div>-->

                        <div class="box-footer pull-right">
                            <a href="<?php echo base_url('bompurchaseindent/preprarebompind') .'/'.urlencode(base64_encode($VarRequestId)) ?>">Go Back</a>
                            <button class="btn btn-info" type="button" title="For preview" onclick="fnSaveTemp()">Save Changes
                            </button>
                        </div>

                    </div>
                    <div class="alert alert-success alert-dismissible hide" data-dismiss="alert" id="divTempSuccess"></div>
                    <!--<div class="alert alert-success alert-dismissible" role="alert" id="divTempSuccess">
                        <div id="divMsg" class="">Saved</div>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>-->
                </div>

                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title"><b>PAYMENT PAID DETAILS</b></h3>
                    </div>
                </div>

                <div class="box box-info">
                        <form name="frmAdvPaymentFinance" id="frmMerchantBomPurReqDetails" class="form-horizontal">
                            <div class="box-body" id="merchantBomPurReqDetails">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-4 control-label">Purpose</label>
                                        <div class="col-sm-8">
                                            <?php
                                            //echo '<pre>'; print_r($ArrBasicInfo);
                                            ?>
                                            <input type="text" class="form-control" readonly value="<?php echo $ArrBasicInfo->purpose ?>">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-4 control-label">Request Type</label>
                                        <div class="col-sm-8">
                                            <?php $ArrRequestTimeType = unserialize(ARRREQUESTTYPE) ?>
                                            <input type="text" class="form-control" readonly value="<?php if (!empty($ArrBasicInfo->requesttype)) echo $ArrRequestTimeType[$ArrBasicInfo->requesttype] ?>">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-4 control-label">Request Date & Time</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" readonly value="<?php if (!empty($ArrBasicInfo->requestdt)) echo date('d-m-Y H:i:s', strtotime($ArrBasicInfo->requestdt)); ?>">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-4 control-label">CuttOff Date & Time</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" readonly value="<?php if (!empty($ArrBasicInfo->cutoffdatetime)) echo date('d-m-Y H:i:s', strtotime($ArrBasicInfo->cutoffdatetime)) ?>">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-4 control-label">Merchant Note</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" readonly value="<?php if (!empty($ArrBasicInfo->merchantnote)) echo $ArrBasicInfo->merchantnote ?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-4 control-label">Authorization Status</label>
                                        <div class="col-sm-8">
                                            <?php
                                            $VarCs = '';
                                            if ($ArrBasicInfo->mgmtcurrentstatus == 1) {
                                                $VarCs = 'AUTHORIZATION PENDING';
                                            } elseif ($ArrBasicInfo->mgmtcurrentstatus == 2) {
                                                $VarCs = 'AUTHORIZED';
                                            } elseif ($ArrBasicInfo->mgmtcurrentstatus == 3) {
                                                $VarCs = 'NOT AUTHORIZED';
                                            } elseif ($ArrBasicInfo->mgmtcurrentstatus == 4) {
                                                $VarCs = 'RE REQUEST';
                                            }
                                            ?>
                                            <input type="text" class="form-control" readonly value="<?php echo $VarCs; ?>">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">Authorization Type</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" readonly value="<?php if (!empty($ArrBasicInfo->approvaltype)) echo $ArrRequestTimeType[$ArrBasicInfo->approvaltype]; ?>">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">Authorization Date & Time</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" readonly value="<?php if (!empty($ArrBasicInfo->authdatetime)) echo date('d-m-Y H:i:s', strtotime($ArrBasicInfo->authdatetime)); ?>">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="" class="col-sm-4 control-label">Management Remarks</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" readonly value="<?php if (!empty($ArrBasicInfo->mgmtremarks)) echo $ArrBasicInfo->mgmtremarks ?>">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="" class="col-sm-4 control-label">Request Status</label>
                                        <div class="col-sm-8">
                                            <?php
                                                if ($ArrBasicInfo->deptcurrentstatus == 1) $rs = 'REQUEST PENDING';
                                                elseif ($ArrBasicInfo->deptcurrentstatus == 2) $rs = 'ACCEPTED' ;
                                                elseif ($ArrBasicInfo->deptcurrentstatus == 3) $rs = 'DECLINED';
                                            ?>
                                                <input type="text" class="form-control" readonly value="<?php echo $rs ?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="" class="col-sm-4 control-label">Assign Purchase Queue.
                                            No</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" readonly value="<?php if(!empty($ArrBasicInfo->queueno)) echo $ArrBasicInfo->queueno ?>">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="" class="col-sm-4 control-label">Queue No. Assigned Date &
                                            Time</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" readonly value="<?php if (!empty($ArrBasicInfo->queueno_assigned_date)) echo date('d-m-Y H:i:s', strtotime($ArrBasicInfo->queueno_assigned_date)); ?>">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="" class="col-sm-4 control-label">Purchase Dept. Remarks</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" readonly value="<?php if (!empty($ArrBasicInfo->deptremarks)) echo $ArrBasicInfo->deptremarks ?>">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">Current Status</label>
                                        <div class="col-sm-8">
                                            <?php
                                            if ($ArrBasicInfo->deptcurrentstatus == 1) $cs = 'REQUEST PENDING';
                                            if ($ArrBasicInfo->deptcurrentstatus == 2) $cs = 'ACCEPTED';
                                            if ($ArrBasicInfo->deptcurrentstatus == 3) $cs = 'DECLINED';
                                            ?>
                                            <input type="text" class="form-control" readonly value="<?php echo $cs ?>">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">Recent Update</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" readonly value="<?php if (!empty($ArrBasicInfo->dateupdated)) echo date('d-m-Y H:i:s', strtotime($ArrBasicInfo->dateupdated)) ?>">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>

                    <div class="box-body">
                        <div class="form-group">
                            <div class="col-md-12">
                                <label class="control-label">Merchant Attachments</label>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-12"
                                 style="border:2px dotted #A5A5C7; padding: 10px; background-color: #eee">
                                <ul style="list-style: none;">
                                    <?php
                                    $VarFdr = FCPATH . "uploads".DIRECTORY_SEPARATOR."bompurchaserequest" . DIRECTORY_SEPARATOR.$VarRequestId;
                                    if (file_exists($VarFdr)) {
                                        if ($dh = opendir($VarFdr)) {
                                            while (($file = readdir($dh)) !== false) {
                                                    if(is_file($VarFdr.DIRECTORY_SEPARATOR.$file)) {
                                                        ?>
                                                        <li>
                                                            <div style="padding: 10px 0;">
                                                                <?php echo $file . ' '; ?>&nbsp;<a
                                                                        href="<?php echo base_url() . "menquiry/download?enqid=" . $hashedRequestId . "&filename=" . $file."&folder=bompurchaserequest" ?>">
                                                                    <i class="fa fa-download fa-lg"
                                                                       aria-hidden="true"></i>
                                                                </a>&nbsp;&nbsp;<a
                                                                        href="<?php echo base_url() . "uploads/bompurchaserequest/" . $VarRequestId . "/" . $file ?>"
                                                                        target="_blank">
                                                                    <i class="fa fa-file fa-lg"
                                                                       aria-hidden="true"></i>
                                                                </a>
                                                            </div>
                                                        </li>
                                                        <?php
                                                    }
                                                    ?>

                                                    <?php

                                            }
                                            closedir($dh);
                                        }
                                        ?>
                                        <?php
                                    } else {
                                        echo 'No attachments';
                                    }
                                    ?>
                                </ul>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-12">
                                <label class="control-label">Purchase Dept Upload</label>
                            </div>
                            <div class="col-sm-12">
                                <div id="purchaseIndentUpl" class="pdt10"></div>
                            </div>
                    </div>
                </div>

                <div class="box-footer nopadding">
                    <div class="alert alert-success alert-dismissable hide" id="divSuccessBasicInfoMsg"> </div>
                    <div class="herr" id="ErrfrmPage"></div>
                    <button class="btn btn-info pull-right" type="button" onclick="return fnSavePurchaseIndent()">Save Changes </button>
                </div>

        </section>
    </div><!-- /.content-wrapper -->
    <?php $this->load->view(CNFCOMPANY . 'template/templatefooter'); ?>
    <div class="control-sidebar-bg"></div>
</div><!-- ./wrapper -->
<script src="<?php echo base_url(); ?>assets/js/jexcel4/jsuites.js"></script>
<script src="<?php echo base_url(); ?>assets/js/jexcel4/jexcel.js"></script>
<script src="<?php echo base_url();?>assets/js/jquery.uploadfile-order.js"></script>
<script src="<?php echo base_url(); ?>assets/plugins/datepicker/bootstrap-datepicker.js"></script>
<script type="text/javascript">
    //var deletedRows = [];
    var GlbBomPurIndentId = '<?php echo $VarBomPurIndentId ?>';
    var GlbRequestId = '<?php echo $VarRequestId ?>';
    var GlbUserTypeId = '<?php echo $UserTypeId ?>';
    var GlbPiNoPrefix = '<?php echo $PiNoPreFix ?>';
    var GlbOrderId = '<?php echo $OrderId ?>';
    var GlbTaxTypeId = '<?php echo $TaxTypeId ?>';

    var lsPrepareBomPIJxlTwo = []; var GlbVendorId = 0; var GlbValidateVendorSelection = ''; var GlbBomPurchaseGrid = [[]];
    var GlbVendorBankJxl = [[]]; var GlbAdvPaymentRequest = [[]];
    console.log(GlbTaxTypeId,'GlbTaxTypeId');
    if (GlbTaxTypeId == 1) {
        GlbTaxType = "SGST / CGST RATE";
    }
    else if (GlbTaxTypeId == 2) {
        GlbTaxType = "IGST RATE";
    }
    else if (GlbTaxTypeId == 3) {
        GlbTaxType = "IMPORT DUTY";
    }
    else {
        GlbTaxType = "";
    }
    var GlbWipNo = $("#wiprefno").text();
    var GlbCurrencynCode = '<?php echo json_encode(unserialize(ARRCURRENCYLIST)); ?>';
    var GlbModeOfPayment = '<?php echo json_encode(unserialize(BOMPI_MODEOFPAYMENT)) ?>';
    $("#bomPiTaxType").text(GlbTaxType);
    MakePostRequest(base_path+"bompurchaseindent/getBomPurchaseIndentJxl","bomPurchaseReqId="+GlbRequestId,"json",function (data) {
        console.log(data,'data');
        GlbBomPurchaseGrid = data.previewJxl;
    });
    console.log(GlbBomPurchaseGrid,'GlbBomPurchaseGrid');
    var GlbBasicInfo = '<?php echo $otherDetails ?>';
    var GlbOtherDetailsId = '<?php echo $otherDetailsId ?>';
    console.log(GlbBasicInfo,'GlbBasicInfo');
        var savedBasicData = '';
        /*fnSelToVendorname(basicDatasInLs.vendorId);
        $("#agreedsupplydate").val(basicDatasInLs.AgreedSupplyDate);
        $("#frmBasicPaymentTerms").val(basicDatasInLs.PaymentTerms);
        $("#frmAmountInWords").val(basicDatasInLs.AmountInWords);
        $("#frmRemarks").val(basicDatasInLs.Remarks);
        $("#frmBasicPurchaserName").val(basicDatasInLs.PurchaserConName);
        $("#frmBasicPurchaserMobile").val(basicDatasInLs.PurchaserConMob);
        $("#frmBasicPurchaserEmail").val(basicDatasInLs.PurchaserConEmail);

        $("#frmExtraVendorName").val(basicDatasInLs.VendorConName);
        $("#frmExtraVendorMobile").val(basicDatasInLs.VendorConMob);
        $("#frmExtraVendorEmail").val(basicDatasInLs.VendorConEmail);*/


    console.log(GlbBomPurchaseGrid,'GlbBomPurchaseGrid');
    function fnSelToVendorname(thisvalue) {
        GlbVendorId = thisvalue;
        MakePostRequest(base_path + GlbCompanyFdr+'mpurchaseuser/getVendorsInfo', "rfrom=1&vid=" + thisvalue, 'json', fnSelToVendornameRes);
    }
    function fnSelToVendornameRes(data) {
        if(data != '') {
            if (data.errCode == 1) {
                console.log(data,'ven data');
                GlbValidateVendorSelection = 1;
                $("#toaddress").text(data.vendorDetails.address);
                $("#contactno").text(data.vendorDetails.phone);
                $("#emailid").text(data.vendorDetails.emailid);
                $("#gstno").text(data.vendorDetails.gstno);
                $("#iecode").text(data.vendorDetails.iecode);
                GlbVendorBankJxl = data.vendorBankJxl;
                console.log(GlbVendorBankJxl,'GlbVendorBankJxl');
                $("#vendorBankDetailsJxl").empty();
                jexcel(document.getElementById('vendorBankDetailsJxl'), {
                    columns: [
                        {type: 'text', title: 'Vendor Name', width: 120},
                        {type: 'text', title: 'Bank Name', width: 120},
                        {type: 'text', title: 'Account Name', width: 150},
                        {type: 'text', title: 'Account No.', width: 120},
                        {type: 'text', title: 'IFS Code', width: 120},
                        {type: 'text', title: 'RTGS', width: 120},
                        {type: 'text', title: 'SWIFT Code', width: 120},
                        {type: 'text', title: 'IBAN', width: 120}
                    ],
                    data: GlbVendorBankJxl
                });

            }
            else {
                alert('err');
            }
        }
    }
    var bomPIJxl = 0;
    console.log(GlbTaxTypeId, 'GlbTaxTypeId');
    // A custom method to SUM all the cells in the current column
    var SUMCOL = function (instance, columnId) {
        var total = 0;
        for (var j = 0; j < instance.options.data.length; j++) {
            if (Number(instance.records[j][columnId - 1].innerHTML)) {
                total += Number(instance.records[j][columnId - 1].innerHTML);
            }
        }
        return total.toFixed(2);
    }

    if (GlbTaxTypeId == 1) {
        console.log(GlbBomPurchaseGrid);
        bomPIJxl = jexcel(document.getElementById('bomPurchaseIndentJxl'), {
            columns: [
                {type: 'text', title: 'Item Description / Blend (%) / Content / Material', width: 150, wordWrap: true, readOnly: true },
                {type: 'text', title: 'Gar. Size', width: 40, readOnly: true, wordWrap: true},
                {type: 'text', title: 'Item Code', width: 100, readOnly: true, wordWrap: true},
                {type: 'text', title: 'Item Color Code', width: 100, readOnly: true, wordWrap: true},
                {type: 'text', title: 'Size / Dim. (W*L*H)', width: 80, readOnly: true, wordWrap: true},
                {type: 'text', title: 'UOM', width: 80, readOnly: true, wordWrap: true},
                {type: 'text', title: 'Quantity', width: 90, wordWrap: true},
                {type: 'text', title: 'UOM', width: 90, wordWrap: true},
                {type: 'text', title: 'Unit Rate', width: 40},
                {type: 'text', title: 'Amount (Rs.)', width: 100},
                {type: 'numeric', title: 'SGST (%)', width: 40},
                {type: 'text', title: 'SGST Value (Rs.)', width: 70},
                {type: 'numeric', title: 'CGST (%)', width: 40},
                {type: 'text', title: 'CGST Value (Rs.)', width: 70},
                {type: 'text', title: 'Sub Total (Rs.)', width: 100},
                {type: 'hidden'},
                {type: 'checkbox', title: 'Select', width: 50},
            ],
            footers: [['', '', '', '', '','', '', '', 'Total', '=SUMCOL(TABLE(), COLUMN())', '', '=SUMCOL(TABLE(), COLUMN())', '', '=SUMCOL(TABLE(), COLUMN())', '=SUMCOL(TABLE(), COLUMN())']],
            data: GlbBomPurchaseGrid,
            allowInsertRow: false,
            onchange: function(instance, cell, x, y, value) {
                if (x == 16) {
                    var newid = cell.previousSibling.innerHTML;
                    if (value == false) {
                        if (window.confirm("Are yiu sure want to Remove?")) {
                            console.log(y,'y');
                            bomPIJxl.setRowData(y,[0,0,0,0,0,0,0,0,0,0,0,0,0,0]);
                            cell.style.backgroundColor = '#ff0000';
                            //deletedRows.push(newid);
                        }
                        else {
                            window.location.reload();
                        }
                    }
                    else {
                        //var delrow = deletedRows.indexOf(newid);
                        //if (delrow > -1) {
                            //deletedRows.splice(delrow, 1);
                        //}
                        cell.style.backgroundColor = '';
                    }
                }
            },
            updateTable: function (instance, cell, col, row, val, label, cellName) {
                if (col == 9) {
                    amount = Number($(cell).text());
                }
                if (col == 10) {
                    sgstpercent = Number($(cell).text());
                }
                if (col == 11) {
                    sgst_division = sgstpercent / 100;
                    s_gstvalue = Number(sgst_division) * Number(amount);
                    $(cell).text(s_gstvalue.toFixed(2));
                    instance.jexcel.options.data[row][col] = s_gstvalue.toFixed(2);
                }
                if (col == 12) {
                    c_gstpercent = Number($(cell).text());
                }
                if (col == 13) {
                    cgst_division = c_gstpercent / 100;
                    c_gstvalue = Number(cgst_division) * Number(amount);
                    $(cell).text(c_gstvalue.toFixed(2));
                    instance.jexcel.options.data[row][col] = c_gstvalue.toFixed(2);
                }
                if (col == 14) {
                    subtotal = s_gstvalue + c_gstvalue + Number(amount);
                    $(cell).text(subtotal.toFixed(2));
                    instance.jexcel.options.data[row][col] = subtotal.toFixed(2);
                }
            }
        });
    }
    else if (GlbTaxTypeId == 2) {
        bomPIJxl = jexcel(document.getElementById('bomPurchaseIndentJxl'), {
            columns: [
                {type: 'text', title: 'Item Description / Blend (%) / Content / Material', width: 150, wordWrap: true, readOnly: true },
                {type: 'text', title: 'Gar. Size', width: 40, readOnly: true, wordWrap: true},
                {type: 'text', title: 'Item Code', width: 120, readOnly: true, wordWrap: true},
                {type: 'text', title: 'Item Color Code', width: 120, readOnly: true, wordWrap: true},
                {type: 'text', title: 'Size / Dim. (W*L*H)', width: 80, readOnly: true, wordWrap: true},
                {type: 'text', title: 'UOM', width: 80, readOnly: true, wordWrap: true},
                {type: 'text', title: 'Quantity', width: 90, readOnly: true, wordWrap: true},
                {type: 'text', title: 'UOM', width: 90, readOnly: true, wordWrap: true},
                {type: 'text', title: 'Unit Rate', width: 40},
                {type: 'text', title: 'Amount (Rs.)', width: 100},
                {type: 'text', title: 'IGST (%)', width: 40},
                {type: 'text', title: 'IGST Value (Rs.)', width: 70},
                {type: 'text', title: 'Sub Total (Rs.)', width: 100},
                {type: 'hidden'},
                {type: 'checkbox', title: 'Select', width: 50},
            ],
            footers: [['', '', '', '', '', '','', '', 'Total', '=SUMCOL(TABLE(), COLUMN())', '', '=SUMCOL(TABLE(), COLUMN())', '=SUMCOL(TABLE(), COLUMN())']],
            data: GlbBomPurchaseGrid,
            allowInsertRow: false,
            onchange: function(instance, cell, x, y, value) {
                if (x == 14) {
                    var newid = cell.previousSibling.innerHTML;
                    if (value == false) {
                        if (window.confirm("Are yiu sure want to Remove?")) {
                            console.log(y,'y');
                            bomPIJxl.setRowData(y,[0,0,0,0,0,0,0,0,0]);
                            cell.style.backgroundColor = '#ff0000';
                            //deletedRows.push(newid);
                        }
                        else {
                            window.location.reload();
                        }
                    }
                    else {
                        //var delrow = deletedRows.indexOf(newid);
                        //if (delrow > -1) {
                            //deletedRows.splice(delrow, 1);
                        //}
                        cell.style.backgroundColor = '';
                    }
                }
            },
            updateTable: function (instance, cell, col, row, val, label, cellName) {
                if (col == 9) {
                    amount = Number($(cell).text());
                }
                if (col == 10) {
                    i_gstpercent = Number($(cell).text());
                }
                if (col == 11) {
                    igst_division = i_gstpercent / 100;
                    i_gstvalue = Number(igst_division) * Number(amount);
                    $(cell).text(i_gstvalue.toFixed(2));
                    instance.jexcel.options.data[row][col] = i_gstvalue.toFixed(2)
                }
                if (col == 12) {
                    subtotal = amount + i_gstvalue;
                    $(cell).text(subtotal.toFixed(2));
                    instance.jexcel.options.data[row][col] = subtotal.toFixed(2);
                }
            }
        });
    }
    else if (GlbTaxTypeId == 3) {
        bomPIJxl = jexcel(document.getElementById('bomPurchaseIndentJxl'), {
            columns: [
                {type: 'text', title: 'Item Description / Blend (%) / Content / Material', width: 150, wordWrap: true, readOnly: true },
                {type: 'text', title: 'Gar. Size', width: 40, readOnly: true, wordWrap: true},
                {type: 'text', title: 'Item Code', width: 120, readOnly: true, wordWrap: true},
                {type: 'text', title: 'Item Color Code', width: 120, readOnly: true, wordWrap: true},
                {type: 'text', title: 'Size / Dim. (W*L*H)', width: 80, readOnly: true, wordWrap: true},
                {type: 'text', title: 'UOM', width: 80, readOnly: true, wordWrap: true},
                {type: 'text', title: 'Quantity', width: 90, wordWrap: true},
                {type: 'text', title: 'UOM', width: 40},
                {type: 'text', title: 'Unit Rate', width: 40},
                {type: 'text', title: 'Amount (Rs.)', width: 100},
                {type: 'text', title: 'Duty (%)', width: 40},
                {type: 'text', title: 'Duty Value (Rs.)', width: 70},
                {type: 'text', title: 'Sub Total (Rs.)', width: 100},
                {type: 'hidden'},
                {type: 'checkbox', title: 'Select', width: 50},
            ],
            allowInsertRow: false,
            footers: [['', '', '', '', '','', '', '', 'Total', '=SUMCOL(TABLE(), COLUMN())', '', '=SUMCOL(TABLE(), COLUMN())', '=SUMCOL(TABLE(), COLUMN())']],
            data: GlbBomPurchaseGrid,
            onchange: function(instance, cell, x, y, value) {
                if (x == 14) {
                    var newid = cell.previousSibling.innerHTML;
                    if (value == false) {
                        if (window.confirm("Are yiu sure want to Remove?")) {
                            console.log(y,'y');
                            bomPIJxl.setRowData(y,[0,0,0,0,0,0,0,0,0]);
                            cell.style.backgroundColor = '#ff0000';
                            //deletedRows.push(newid);
                        }
                        else {
                            window.location.reload();
                        }
                    }
                    else {
                        //var delrow = deletedRows.indexOf(newid);
                        //if (delrow > -1) {
                            //deletedRows.splice(delrow, 1);
                        //}
                        cell.style.backgroundColor = '';
                    }
                }
            },
            updateTable: function (instance, cell, col, row, val, label, cellName) {
                if (col == 9) {
                    amount = Number($(cell).text());
                }
                if (col == 10) {
                    duty_percent = Number($(cell).text());
                }
                if (col == 11) {
                    duty_division = duty_percent / 100;
                    duty_value = Number(duty_division) * Number(amount);
                    $(cell).text(duty_value.toFixed(2));
                    instance.jexcel.options.data[row][col] = duty_value.toFixed(2);
                }
                if (col == 12) {
                    subtotal = amount + duty_value;
                    $(cell).text(subtotal.toFixed(2));
                    instance.jexcel.options.data[row][col] = subtotal.toFixed(2);
                }
            }
        });
    }

    function fnSavePurchaseIndent() {
        var agreedSupplyDate = $("#agreedsupplydate").val();
        var paymentTerms = $("#frmBasicPaymentTerms").val();
        var AmountInWords = $("#frmAmountInWords").val();
        var Remarks = $("#frmRemarks").val();
        var PurchaserName = $("#frmBasicPurchaserName").val();
        var PurchaserMobile = $("#frmBasicPurchaserMobile").val();
        var PurchaserEmail = $("#frmBasicPurchaserEmail").val();
        var VendorName = $("#frmExtraVendorName").val();
        var VendorMobile = $("#frmExtraVendorMobile").val();
        var VendorEmail = $("#frmExtraVendorEmail").val();
        $('.herr').text('');
        //console.log(deletedRows,'deleted');
        if (GlbValidateVendorSelection == 0) {
            $("#ErrfrmPage").text('Choose a Vendor');
            return false;
        }
        if (paymentTerms == '') {
            $("#ErrfrmPage").text('Enter Payment terms');
            return false;
        }
        if (agreedSupplyDate == '') {
            $("#ErrfrmPage").text('Enter Supply date');
            return false;
        }
        var bomPIndentJxlData    = $("#bomPurchaseIndentJxl").jexcel('getData');
        let advPaymentRequestJxl = $("#advPaymentRequestJxl").jexcel('getData');
        //var vendorBankDetailsJxl = $("#vendorBankDetailsJxl").jexcel('getData');
        //var filterBomPIGrid = [];
        /**Uncommented because 14th hidden Col not getting value in php
         so send all and filter in php
         */
        /*for(var i = 0; i < bomPIndentJxlData.length; i++) {
            if(bomPIndentJxlData[i][15]) {
                filterBomPIGrid.push([bomPIndentJxlData[i][0], bomPIndentJxlData[i][1],
                    bomPIndentJxlData[i][2], bomPIndentJxlData[i][3], bomPIndentJxlData[i][4], bomPIndentJxlData[i][5], bomPIndentJxlData[i][6],
                    bomPIndentJxlData[i][7], bomPIndentJxlData[i][8], bomPIndentJxlData[i][9], bomPIndentJxlData[i][10], bomPIndentJxlData[i][11],
                    bomPIndentJxlData[i][12], bomPIndentJxlData[i][13],bomPIndentJxlData[14],bomPIndentJxlData[15]]);
            }
        }*/
        console.log(bomPIndentJxlData,'bomPIndentJxlData');
        MakePostRequest(base_path+GlbCompanyFdr+'mpurchaseuser/updatePurchaseIndent',"rfrom=1&bomPurIndentId="+GlbBomPurIndentId+
            "&bomPurReqId="+GlbRequestId+"&vendorId="+GlbVendorId+"&supplyDate="+agreedSupplyDate+
            "&paymentterms=" + paymentTerms + "&amountinwords=" +AmountInWords + "&remarks=" + Remarks + "&oid="+GlbOrderId+
            "&pname=" +PurchaserName + "&pmobile=" + PurchaserMobile + "&pemail=" + PurchaserEmail + "&vname=" +VendorName+
            "&vmobile="+VendorMobile + "&vemail=" + VendorEmail+
            "&TaxTypeId="+GlbTaxTypeId+"&bomPIndentJxlData="+JSON.stringify(bomPIndentJxlData)
            +"&advPaymentRequestJxl="+JSON.stringify(advPaymentRequestJxl),
                'json', fnSavePurchaseIndentRes);
            return false;
    }

    function fnSavePurchaseIndentRes(data) {
        console.log(data,' data');
        if(data != '') {
            if(data.errcode == 1) {
                extraObj.startUpload();
                GlbBomPurIndentId = data.id;
                let piNo = GlbPiNoPrefix+GlbBomPurIndentId;
                $("#divSuccessBasicInfoMsg").removeClass('hide');
                $("#divSuccessBasicInfoMsg").text("BOM Purchase Indent saved.");
                /** localStorage saved in prepare BOM (previous page) **/
                localStorage.removeItem("lsPrepareBomPIFirstStageJxl");
                //localStorage.removeItem("lsBasicData");
                //localStorage.removeItem("lsAdvPaymentRequestJxl");
                $("#PurchaseIndentNo").text(piNo);
            }
        }
    }

    function fnSaveTemp() {
        //if (typeof(Storage) !== "undefined") {
            //localStorage.setItem("lsAdvPaymentRequestJxl",JSON.stringify(advPaymentJxlData));
        let BasicObj = {
            vendorId: GlbVendorId,
            AgreedSupplyDate: $("#agreedsupplydate").val(),
            PaymentTerms: $("#frmBasicPaymentTerms").val(),
            AmountInWords: $("#frmAmountInWords").val(),
            Remarks: $("#frmRemarks").val(),
            PurchaserConName: $("#frmBasicPurchaserName").val(),
            PurchaserConMob: $("#frmBasicPurchaserMobile").val(),
            PurchaserConEmail: $("#frmBasicPurchaserEmail").val(),
            VendorConName: $("#frmExtraVendorName").val(),
            VendorConMob: $("#frmExtraVendorMobile").val(),
            VendorConEmail: $("#frmExtraVendorEmail").val()
        };
        let advPaymentJxlData = JSON.stringify($("#advPaymentRequestJxl").jexcel('getData'));
        let bomPIJxl          = JSON.stringify($("#bomPurchaseIndentJxl").jexcel('getData'));
        //var bomPiSecondStageJxl = $("#bomPurchaseIndentJxl").jexcel('getData');
        //localStorage.setItem("lsBasicData",JSON.stringify(BasicObj));
        let Param = "rFrom=1&basicData="+JSON.stringify(BasicObj)+"&advancePayment="+advPaymentJxlData+"&bomJxl="+bomPIJxl+
            "&taxTypeId="+GlbTaxTypeId+"&bomPurchaseRequestId="+GlbRequestId+"&oid="+GlbOrderId+"&otherDetailsId="+GlbOtherDetailsId;

        MakePostRequest(base_path+"bompurchaseindent/saveBomPiApprovalRequest",Param,"json",function (data) {
                console.log(data,'data');
            if(data != '') {
                $("#divTempSuccess").removeClass('hide');
                $("#divTempSuccess").text("saved Successfully");
            }

        });
        //}
        //else {
            //alert('Sorry! No Web Storage support..');
        //}
    }

    console.log(typeof GlbArrCurrency,'typeof');
    jexcel(document.getElementById('advPaymentRequestJxl'), {
        columns: [
            {type: 'text', title: 'WIP No.', width: 120},
            {type: 'calendar', title: 'Proforma Date', width: 120},
            {type: 'text', title: 'Proforma No.', width: 120},
            {type: 'dropdown', source: JSON.parse(GlbCurrencynCode), title: 'Currency', width: 120},
            {type: 'text', title: 'Proforma Value', width: 120},
            {type: 'text', title: 'Advance Amount Payable', width: 120},
            {type: 'dropdown', source: JSON.parse(GlbModeOfPayment), title: 'Requested Mode of Payment', width: 120},
            {type: 'calendar', title: 'Pay By Date', width: 120}
        ],
        data: GlbAdvPaymentRequest,
        updateTable:function(instance, cell, col, row, val, label, cellName) {
            if(col == 0) {
                $(cell).text(GlbWipNo);
                instance.jexcel.options.data[row][col] = GlbWipNo;
            }
        }
    });

    $("#agreedsupplydate").datepicker({
        format: 'dd-mm-yyyy',
        todayHighlight: true,
        autoclose: true
    });
    $(document).ready(function() {
        extraObj     = $("#purchaseIndentUpl").uploadFile({
            dragDrop: true,
            multiple:true,
            url:base_path+'dashboard/commonFileUpload',
            fileName:"bimage",
            returnType: "json",
            fileName:"myfile",
            dynamicFormData:function () {
                return "id="+GlbRequestId+"&bomPurIndentId="+GlbBomPurIndentId+"&ut="+GlbUserTypeId+"&folderName=bompurchaserequest";
            },
            autoSubmit:false
        });
    });

</script>
<?php $this->load->view(CNFCOMPANY . 'template/pagefooter'); ?>