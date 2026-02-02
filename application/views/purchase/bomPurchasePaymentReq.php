<link rel="stylesheet" href="<?php echo base_url();?>assets/css/jexcel/jquery.jexcel.min.css" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/plugins/datepicker/datepicker3.css">
<style type="text/css">
    /*table {
        font-size: 12px;
    }
    table th, table th, table th {
        background-color: #ffdddd;
        border: 1px solid white;
    }*/
</style>
<?php $this->load->view(CNFCOMPANY.'template/pageheader'); ?>
<body class="sidebar-mini skin-black wysihtml5-supported sidebar-collapse">
<div class="wrapper">
    <?php $this->load->view(CNFCOMPANY.'template/templateheader'); ?>
    <!-- Left side column. contains the logo and sidebar -->
    <aside class="main-sidebar">
        <?php $this->load->view(CNFCOMPANY.'template/templateleftmenu'); ?>
    </aside>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <section class="content">
            <div class="row">
                <div class="col-md-12" id="divBasicInfo">
                    <?php $this->load->view('commonBasicInfoOrderEntry') ?>
                    <div class="box box-info">
                        <div class="box-header with-border">
                            <h3 class="box-title"><b>BOM PURCHASE INDENT</b></h3>
                            <div class="box-tools pull-right">
                                <?php
                                if ($ArrBasicInfo->taxtype == 1) {
                                    echo 'SGST / CGST RATE';
                                } elseif ($ArrBasicInfo->taxtype == 2) {
                                    echo 'IGST RATE';
                                } elseif ($ArrBasicInfo->taxtype == 3) {
                                    echo 'IMPORT DUTY';
                                }
                                ?>
                            </div>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <table class="table">
                                <tr>
                                    <td>
                                        <table class="table" id="fromto">
                                            <tr><th style="background-color: #f3f3f3" colspan="2">FROM</th> </tr>
                                            <tr><td>NAME</td><td><?php echo $ArrCompanyInfo[0]['ceoname'] ?></td></tr>
                                            <tr><td>ADDRESS :</td><td><?php echo $ArrCompanyInfo[0]['address'] ?></td></tr>
                                            <tr><td>CONTACT NO :</td><td><?php echo $ArrCompanyInfo[0]['mobile'] ?></td></tr>
                                            <tr><td>E-MAIL ID :</td><td><?php echo $ArrCompanyInfo[0]['emailid'] ?></td></tr>
                                            <tr><td>GST No :</td><td><?php //echo $ArrCompanyInfo[0]['gstno'] ?></td></tr>
                                            <tr><td>IE CODE :</td><td><?php //echo $ArrCompanyInfo[0]['iecode'] ?></td></tr>
                                        </table>
                                    </td>
                                    <td>
                                        <table class="table" id="fromto">
                                            <tr><th style="background-color: #f3f3f3" colspan="2">TO</th></tr>
                                            <tr>
                                                <td style="padding-top: 15px">NAME</td>
                                                <td>
                                                    <?php
                                                    echo $ArrBasicInfo->contactname
                                                    ?>
                                                </td>
                                            </tr>
                                            <tr><td>ADDRESS</td><td id="toaddress"><?php echo $ArrBasicInfo->address ?></td></tr>
                                            <tr><td>CONTACT NO:</td><td id="contactno"><?php echo $ArrBasicInfo->phone ?></td></tr>
                                            <tr><td>E-MAIL ID:</td><td id="emailid"><?php echo $ArrBasicInfo->emailid ?></td></tr>
                                            <tr><td>GST NO:</td><td id="gstno"><?php echo $ArrBasicInfo->gstno ?></td></tr>
                                            <tr><td>IE CODE:</td><td id="iecode"><?php echo $ArrBasicInfo->iecode ?></td></tr>
                                        </table>
                                    </td>
                                    <td>
                                        <table class="table" id="fromto">
                                            <tr><th style="background-color: #f3f3f3; text-align: center" colspan="2">PURCHASE REFERENCE</th></tr>
                                            <tr><td><b>P.I. REF. NO:</b></td><td id="pino">
                                                    <?php echo $ArrBasicInfo->purchaseindentno.'/'.$ArrBasicInfo->isriorcode ?>
                                                </td></tr>
                                            <tr><td>DATE & TIME:</td>
                                                <td>
                                                    <?php if(empty($ArrBasicInfo->datecreated))
                                                        echo date('d-m-Y');
                                                    else echo date('d-m-Y',strtotime($ArrBasicInfo->datecreated)) ?>
                                                </td></tr>
                                            <tr>
                                                <td>AGREED SUPPLY DATE:</td>
                                                <td>
                                                    <input type='text' class="form-control" placeholder="Agreed Supply Date" id="agreedsupplydate" value="<?php if(empty($ArrBasicInfo->agreedsupplydate)) echo '-'; else echo date('d-m-Y',strtotime($ArrBasicInfo->agreedsupplydate)) ?>" />
                                                </td>
                                            </tr>
                                            <tr><td>SUPPLY CUTOFF DATE:</td>
                                                <td><?php if(empty($ArrBasicInfo->datecreated)) echo date('d-m-Y');
                                                    else echo date('d-m-Y',strtotime($ArrBasicInfo->datecreated)) ?>
                                                </td></tr>
                                            <tr><td>PAYMENT TERMS:</td>
                                                <td>
                                                    <input type="text" class="form-control" id="frmBasicPaymentTerms" value="<?php if(!empty($ArrBasicInfo->paymentterms)) echo $ArrBasicInfo->paymentterms ?>">
                                                </td></tr>
                                            <tr><td><b>INTERNAL REFERENCE</b></td></tr>
                                            <tr><td>WIP NO:</td>
                                                <td id="wiprefno">
                                                    <?php echo $ArrBasicInfo->isriorcode ?>
                                                </td></tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="box-body">
                            <small>Herewith, we place an order for the following items:</small>
                            <div id="bomPurchaseIndentInvoice"></div>
                            <form class="form-horizontal" action="#" name="frmBasicInfo" id="frmBasicInfo" method="post">
                                <div class="col-md-12">
                                    <?php
                                    if($ArrBasicInfo->taxtype == 3) {
                                        ?>
                                        <label class="" style="position:relative; left: 778px">Total : </label>
                                        <div class="" style="position:relative; left: 783px; display: inline-block">
                                            <select class="" id="frmBasicCurrency" disabled>
                                                <option value="">Currency</option>
                                                <?php
                                                foreach ($ArrCurrencyCode as $KeyId => $item) {
                                                    ?>
                                                    <option value="<?php echo $KeyId ?>" <?php if(!empty($ArrBasicInfo->currencycode)) echo $ArrBasicInfo->currencycode == $KeyId ? 'selected' : '' ?>><?php echo $item ?></option>
                                                    <?php
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <input type="text" style="position: relative; left: 784px; width: 100px" readonly id="frmBasicTotal" value="<?php if(!empty($ArrBasicInfo->amounttotal)) echo $ArrBasicInfo->amounttotal ?>">
                                        <input type="text" style="position:relative; left: 831px; width: 80px; " readonly id="frmBasicDutyvalueTotal" value="<?php if(!empty($ArrBasicInfo->dutytotal)) echo $ArrBasicInfo->dutytotal ?>">
                                        <input type="text" style="position:relative; left: 831px; width: 100px; " readonly id="frmBasicSubtotalTotal" value="<?php if(!empty($ArrBasicInfo->subtotal_total)) echo $ArrBasicInfo->subtotal_total ?>">
                                        <?php
                                    }
                                    elseif($ArrBasicInfo->taxtype == 1) {
                                        ?>
                                        <label class="" style="position:relative; left: 670px">Total : </label>
                                        <div class="" style="position:relative; left: 685px; display: inline-block">&#8377;</div>
                                        <input type="text" style="position: relative; left: 713px; width: 100px" readonly id="frmBasicTotal" value="<?php if(!empty($ArrBasicInfo->amounttotal)) echo $ArrBasicInfo->amounttotal ?>">
                                        <input type="text" style="position:relative; left: 760px; width: 90px; " readonly id="frmBasicSgstTotal" value="<?php if(!empty($ArrBasicInfo->sgsttotal)) echo $ArrBasicInfo->sgsttotal ?>">
                                        <input type="text" style="position:relative; left: 808px; width: 90px; " readonly id="frmBasicCgstTotal" value="<?php if(!empty($ArrBasicInfo->cgsttotal)) echo $ArrBasicInfo->cgsttotal ?>">
                                        <input type="text" style="position:relative; left: 810px; width: 100px; " readonly id="frmBasicSubtotalTotal" value="<?php if(!empty($ArrBasicInfo->subtotal_total)) echo $ArrBasicInfo->subtotal_total ?>">
                                        <?php
                                    }
                                    elseif($ArrBasicInfo->taxtype == 2) {
                                        ?>
                                        <label class="" style="position:relative; left: 818px">Total : </label>
                                        <div class="" style="position:relative; left: 825px; display: inline-block">&#8377;</div>
                                        <input type="text" style="position: relative; left: 835px; width: 100px" readonly id="frmBasicTotal" value="<?php if(!empty($ArrBasicInfo->amounttotal)) echo $ArrBasicInfo->amounttotal ?>">
                                        <input type="text" style="position:relative; left: 891px; width: 90px; " readonly id="frmBasicIgstTotal" value="<?php if(!empty($ArrBasicInfo->igsttotal)) echo $ArrBasicInfo->igsttotal ?>">
                                        <input type="text" style="position:relative; left: 891px; width: 90px; " readonly id="frmBasicSubtotalTotal" value="<?php if(!empty($ArrBasicInfo->subtotal_total)) echo $ArrBasicInfo->subtotal_total ?>">
                                        <?php
                                    }
                                    ?>
                                </div>
                            </form>
                        </div>
                        <div class="box-body">
                            <form class="form-horizontal">
                                <div class="form-group">
                                    <label class="col-md-2"><b>Amount in words:</b></label>
                                    <div class="col-md-10">
                                        <input type="text" id="frmAmountInWords" class="form-control" readonly value="<?php echo $ArrBasicInfo->amountinwords ?>">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-2" style="border: 1px solid #f4f4f4">
                                        <label><b>Note:</b></label>
                                        <p style="font-size: 11px; display: inline">If goods are supplied beyond cutoff date, it is up to the discretion of the company to accept or reject the goods. Terms and conditions as agreed upon.</p>
                                    </div>
                                    <label class="col-md-2"><b>Remarks:</b></label>
                                    <div class="col-md-10">
                                        <textarea id="frmRemarks" readonly class="form-control"><?php echo $ArrBasicInfo->remarks ?></textarea>
                                    </div>
                                </div>
                            </form>
                            <div class="row">
                                <div class="col-md-2">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead><tr><th style="background-color: #f3f3f3" colspan="2">PURCHASER CONTACT DETAILS</th></tr></thead>
                                            <tbody><tr>
                                                <th>NAME:</th>
                                                <td><input class="form-control" readonly id="frmBasicPurchaserName" type="text" value="<?php echo $ArrBasicInfo->purchasername ?>"></td>
                                            </tr>
                                            <tr>
                                                <th>MOBILE:</th>
                                                <td><input class="form-control" readonly id="frmBasicPurchaserMobile" type="text" value="<?php echo $ArrBasicInfo->purchasermobile ?>"></td>
                                            </tr>
                                            <tr>
                                                <th>EMAIL:</th>
                                                <td><input class="form-control" readonly id="frmBasicPurchaserEmail" type="text" value="<?php echo $ArrBasicInfo->purchaseremail ?>"></td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead><tr><th style="background-color: #f3f3f3" colspan="2">VENDOR CONTACT DETAILS</th></tr></thead>
                                            <tbody><tr>
                                                <th>NAME:</th>
                                                <td><input class="form-control" readonly id="frmBasicVendorName" type="text" value="<?php echo $ArrBasicInfo->vendorname ?>"></td>
                                            </tr>
                                            <tr>
                                                <th>MOBILE:</th>
                                                <td><input class="form-control" readonly id="frmBasicVendorMobile" type="text" value="<?php echo $ArrBasicInfo->vendormobile ?>"></td>
                                            </tr>
                                            <tr>
                                                <th>EMAIL:</th>
                                                <td><input class="form-control" readonly id="frmBasicVendorEmail" type="text" value="<?php echo $ArrBasicInfo->vendoremail ?>"></td>
                                            </tr>
                                            </tbody>
                                            </table>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <label class="control-label">Prepared by:</label>
                                    <br/>
                                    <?php echo '' ?>
                                    <br/><br/>
                                    <label class="control-label">Name & Signature</label>
                                    <br/>

                                </div>
                                <div class="col-md-2">
                                    <label class="control-label">Authorized by:</label><br/>
                                    <?php echo '' ?>
                                    <br/><br/>
                                    <label class="control-label">Name & Signature</label>
                                    <br/>
                                    <?php echo '' ?>
                                </div>
                                <div class="col-md-2">
                                    <label class="control-label">Approved by:</label><br/>
                                    <?php echo '' ?>
                                    <br/><br/>
                                    <label class="control-label">Name & Signature</label>
                                    <br/>
                                    <?php echo '' ?>
                                </div>
                                <div class="col-md-2">
                                    <label class="control-label">For</label><br/>
                                    <?php echo '' ?>
                                    <br/><br/>
                                    <label class="control-label">Authorized Signatory</label>
                                    <br/>
                                    <?php echo '' ?>
                                </div>
                            </div>
                            <a href="<?php echo base_url('purchaseuser/bompurchaseinvoicepdf/'.$VarBomPurIndReqId) ?>">Save as pdf</a>
                        </div>
                    </div>
                    <div class="box box-info">
                        <div class="box-header with-border">
                            <h3 class="box-title"><b>BOM PAYMENT REQUEST</b></h3>
                            <div class="box-tools pull-right">
                            <?php
                            if(empty($VarId)) {
                                ?>
                                <select class="" id="AdvPaymentBillPaymentType" onchange="fnAdvPaymentBillPaymentType(this.value)">
                                    <option value="">Choose Payment Type</option>
                                    <option value="1">Advance Payment</option>
                                    <option value="2">Bill Payment</option>
                                </select>
                                <?php
                            }
                            else {
                            }
                            ?>
                            </div>
                        </div><!-- /.box-header -->
                            <div class="box-body <?php if(!empty($VarId)) { if($ArrBasicPaymentInfo['paymenttype'] == 2) echo 'hide'; } else echo 'hide' ?>" id="advPaymentDiv">
                                <h5 class="box-title"><b>Advance Payment</b></h5>
                                <form name="frmAdvPayment" id="frmAdvPayment" class="">
                                <table class="table">
                                    <tr>
                                        <th>WIP No.</th> <th>Proforma No.</th> <th>Proforma Date</th> <th>P.I. No.</th> <th>P.I. Date</th> <th>Currency</th><th>Proforma Value</th>
                                        <th>P.I. Value</th> <th>Advance Payable</th> <th>Reqt. Mode of Payment</th> <th>Pay by Date</th> <th>Approval Status</th> <th>Approved By</th>
                                    </tr>
                                    <tr>
                                        <td><?php echo $ArrBasicInfo->isriorcode ?></td>
                                        <td>
                                            <input type="text" id="proformaNo" name="proformaNo" style="width: 110px" value="<?php if(!empty($ArrBasicPaymentInfo['proformano'])) echo $ArrBasicPaymentInfo['proformano'] ?>">
                                        </td>
                                        <td>
                                            <input type="text" id="proformaDate" name="proformaDate" style="width: 80px" placeholder="Choose"
                                                   value="<?php if(!empty($ArrBasicPaymentInfo['proformadate'])) echo date('d-m-Y',(strtotime($ArrBasicPaymentInfo['proformadate']))) ?>">
                                        </td>
                                        <td><?php echo $ArrBasicInfo->purchaseindentno.'/'.$ArrBasicInfo->isriorcode ?></td>
                                        <td><?php echo date('d-m-Y',strtotime( $ArrBasicInfo->datecreated)) ?> </td>
                                        <td><input type="hidden" id="frmCurrency" name="frmCurrency" value="<?php echo $ArrBasicInfo->currencycode ?>"> <?php echo $VarCurrency; ?></td>
                                        <td>
                                            <input type="text" id="proformaValue" name="proformaValue" style="width: 110px" value="<?php if(!empty($ArrBasicPaymentInfo['proformavalue'])) echo $ArrBasicPaymentInfo['proformavalue'] ?>">
                                        </td>
                                        <td><?php echo $ArrBasicInfo->subtotal_total ?></td>
                                        <td><input type="text" id="advPayable" name="advPayable" value="<?php if(!empty($ArrBasicPaymentInfo['advpayable'])) echo $ArrBasicPaymentInfo['advpayable'] ?>"> </td>
                                        <td>
                                            <select id="ReqdModeOfPayment" name="ReqdModeOfPayment">
                                                <option value="1" <?php if(@$ArrBasicPaymentInfo['reqmodeofpayment'] == 1) echo 'selected' ?>>Cheque</option>
                                                <option value="2" <?php if(@$ArrBasicPaymentInfo['reqmodeofpayment'] == 2) echo 'selected' ?>>DD</option>
                                                <option value="3" <?php if(@$ArrBasicPaymentInfo['reqmodeofpayment'] == 3) echo 'selected' ?>>RTGS</option>
                                                <option value="4" <?php if(@$ArrBasicPaymentInfo['reqmodeofpayment'] == 4) echo 'selected' ?>>SWIFT</option>
                                            </select>
                                        </td>
                                        <td>
                                            <input type="text" id="payByDate" name="payByDate" style="width: 80px" placeholder="Choose"
                                                   value="<?php if(!empty($ArrBasicPaymentInfo['paybydate'])) echo date('d-m-Y',strtotime($ArrBasicPaymentInfo['paybydate'])) ?>">
                                        </td>
                                        <td>
                                            <?php
                                            $ArrApprStatu = unserialize(REQUESTSTATUSARR);
                                            if(!empty($ArrBasicPaymentInfo['apprstatus'])) echo $ArrApprStatu[$ArrBasicPaymentInfo['apprstatus']];
                                            ?>
                                        </td>
                                        <td id="ApprdBy"><?php echo @$apprbyname ?></td>
                                    </tr>
                                </table>
                                </form>
                            </div>
                            <div class="box-body <?php if(!empty($VarId)) { if($ArrBasicPaymentInfo['paymenttype'] == 1) echo 'hide'; } else echo 'hide' ?>" id="billPaymentDiv">
                            <h5 class="box-title"><b>Bill Payment</b></h5>
                                <form id="frmBillPayment" name="frmBillPayment" class="">
                            <table class="table">
                                <tr>
                                    <th>WIP No.</th> <th>Invoice No.</th> <th>Invoice Date</th> <th>Currency</th><th>Invoice Value</th> <th>Advance Paid</th>
                                    <th>Debit Value</th> <th>Amount<br/>Payable</th> <th>Reqt. Mode<br/>of<br/>Payment</th> <th>Payment<br/>Due<br/>Date</th> <th>Approval<br/>Status</th>
                                    <th>Approved By</th>
                                </tr>
                                <tr>
                                    <td><?php echo $ArrBasicInfo->isriorcode ?></td>
                                    <td><input type="text" id="invoiceNo" name="invoiceNo" style="width: 110px" value="<?php ?>"> </td>
                                    <td><input type="text" id="invoiceDate" name="invoiceDate" style="width: 80px" placeholder="Choose" value="<?php ?>"></td>
                                    <td><input type="hidden" id="frmCurrency" name="frmCurrency" value="<?php echo $ArrBasicInfo->currencycode ?>"> <?php echo $VarCurrency; ?></td>
                                    <td><input type="text" id="invoiceValue" name="invoiceValue" style="width: 110px"></td>
                                    <td>
                                        <input type="text" id="BillAdvPaid" name="BillAdvPaid" style="width: 110px" value="<?php echo '' ?>">
                                    </td>
                                    <td><input type="text" id="debitValue" name="debitValue" style="width: 110px"> </td>
                                    <td><input type="text" id="amountPayable" name="amountPayable" style="width: 110px"> </td>
                                    <td>
                                        <select id="ReqdModeOfPayment" name="ReqdModeOfPayment">
                                            <option value="1">Cheque</option>
                                            <option value="2">DD</option>
                                            <option value="3">RTGS</option>
                                            <option value="4">SWIFT</option>
                                        </select>
                                    </td>
                                    <td><input type="text" id="paymentDueDate" name="paymentDueDate" style="width: 80px;"></td>
                                    <td>
                                        <select id="ApprStatus" name="ApprStatus" disabled>
                                            <option value="1">Pending</option>
                                            <option value="2">Approved</option>
                                            <option value="3">Declined</option>
                                        </select>
                                    </td>
                                    <td id="ApprdBy"></td>
                                </tr>
                            </table>
                                </form>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="box box-info">
                        <div class="box-header with-border">
                            <h3 class="box-title"><b>Vendors' Bank Details</b></h3>
                        </div>
                        <div class="box-tools pull-right"></div>
                        <div class="box-body">
                            <table class="table">
                                <tr>
                                    <th>Vendor Name</th> <th>Bank Name</th> <th>Account Name</th> <th>Account No.</th> <th>IFS Code</th> <th>RTGS</th><th>SWIFT Code</th>
                                    <th>IBAN</th>
                                </tr>
                                <tr>
                                    <td><?php echo $ArrBasicInfo->contactname ?></td>
                                    <td id="bankName"><?php echo $ArrBasicInfo->bankname ?> </td>
                                    <td id="accName"><?php echo $ArrBasicInfo->accountname ?></td>
                                    <td id="accNo"><?php echo $ArrBasicInfo->accountno ?></td>
                                    <td id="ifscode"><?php echo $ArrBasicInfo->ifscode ?> </td>
                                    <td id="rtgs"><?php echo $ArrBasicInfo->rtgs ?></td>
                                    <td id="swiftcode"><?php echo $ArrBasicInfo->swiftcode ?></td>
                                    <td id="iban"><?php echo $ArrBasicInfo->iban ?> </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div class="box box-info">
                        <div class="box-header with-border">
                            <h3 class="box-title"><b>PAYMENT PAID DETAILS</b></h3>
                            <div class="box-tools pull-right"></div>
                        </div><!-- /.box-header -->
                        <div class="box-body <?php if(!empty($VarId)) { if($ArrBasicPaymentInfo['paymenttype'] == 2) echo 'hide'; } else echo 'hide' ?>" id="advPaymentDiv">
                            <h5 class="box-title"><b>Advance Payment</b></h5>
                            <form name="frmAdvPaymentFinance" id="frmAdvPaymentFinance" class="">
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th>Paid in Favour of</th> <th>Mode of Payment</th> <th>Cheque No.</th> <th>Cheque Date</th> <th>Transaction ID / Code</th>
                                        <th>Transaction Date</th><th>Currency</th> <th>Advance Paid</th> <th>Paid in Full / Part</th> <th>Balance to Pay</th>
                                        <th>P.I. Details Verified By</th> <th>Passed By</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php

                                    foreach ($ArrBasicFinanceDeptInfo as $item) {
                                        ?>
                                        <tr>
                                            <td><?php echo $ArrBasicInfo->accountname ?></td>
                                            <td>
                                                <select id="" name="">
                                                    <option value="1" <?php if(@$item['modeofpayment'] == 1) echo 'selected' ?>>Cheque</option>
                                                    <option value="2" <?php if(@$item['modeofpayment'] == 2) echo 'selected' ?>>DD</option>
                                                    <option value="3" <?php if(@$item['modeofpayment'] == 3) echo 'selected' ?>>RTGS</option>
                                                    <option value="4" <?php if(@$item['modeofpayment'] == 4) echo 'selected' ?>>SWIFT</option>
                                                </select>
                                            </td>
                                            <td><input type="text" id="" name="" style="width: 110px" value="<?php if(!empty($item['chequeno'])) echo $item['chequeno'] ?>"> </td>
                                            <td>
                                                <input type="text" id="" name="" style="width: 80px" value="<?php if(!empty($item['chequedate'])) echo date('d-m-Y',strtotime($item['chequedate'])) ?>">
                                            </td>
                                            <td><input type="text" id="" name="" style="width: 110px" value="<?php echo $item['transid'] ?>"></td>
                                            <td>
                                                <input type="text" id="" name="" style="width: 80px" value="<?php echo empty($item['transdate']) ? '-' : date('d-m-Y',strtotime($item['transdate'])) ?>">
                                            </td>
                                            <td><?php echo $VarCurrency ?></td>
                                            <td><input type="text" id="" name="" style="width: 130px" value="<?php echo $item['amountpaid'] ?>"></td>
                                            <td>
                                                <select id="" name="">
                                                    <option value="">Choose</option>
                                                    <option value="1" <?php echo $item['full_part_id'] == 1 ? 'selected' : '' ?>>Full</option>
                                                    <option value="2" <?php echo $item['full_part_id'] == 2 ? 'selected' : '' ?>>Part</option>
                                                </select>
                                            </td>
                                            <td><input type="text" id="" name="" style="width: 140px" value="<?php echo $item['baltopay'] ?>"></td>
                                            <td><?php ?></td>
                                            <td><?php ?></td>
                                        </tr>
                                        <?php
                                    }
                                    ?>
                                    <tr id="xtra" class="hide">
                                        <td><?php echo $ArrBasicInfo->accountname ?></td>
                                        <td>
                                            <select id="ReqdModeOfPayment" name="ReqdModeOfPayment">
                                                <option value="1">Cheque</option>
                                                <option value="2" selected="">DD</option>
                                                <option value="3">RTGS</option>
                                                <option value="4">SWIFT</option>
                                            </select>
                                        </td>
                                        <td><input type="text" id="frmCheqNo" name="frmCheqNo" style="width: 110px" value=""> </td>
                                        <td>
                                            <input type="text" id="frmCheqDate" name="frmCheqDate" style="width: 80px" value="01-01-1970">
                                        </td>
                                        <td><input type="text" id="frmTransId" name="frmTransId" style="width: 110px" value=""></td>
                                        <td>
                                            <input type="text" id="frmTransDate" name="frmTransDate" style="width: 80px" value="01-01-1970">
                                        </td>
                                        <td></td>
                                        <td><input type="text" id="frmAdvPaid" name="frmAdvPaid" style="width: 130px" value=""></td>
                                        <td>
                                            <select id="frmFullPart" name="frmFullPart">
                                                <option value="">Choose</option>
                                                <option value="1">Full</option>
                                                <option value="2">Part</option>
                                            </select>
                                        </td>
                                        <td><input type="text" id="frmBaltoPay" name="frmBaltoPay" style="width: 140px" value=""></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    </tbody>
                                </table>
                            </form>
                        </div>
                        <div class="box-body <?php if(!empty($VarId)) { if($ArrBasicPaymentInfo['paymenttype'] == 1) echo 'hide'; } else echo 'hide' ?>" id="billPaymentDiv">
                            <h5 class="box-title"><b>Bill Payment</b></h5>
                            <form id="frmBillPaymentFinance" name="frmBillPaymentFinance" class="">
                                <table class="table">
                                    <tr>
                                        <th>Paid in Favour of</th> <th>Mode of Payment</th> <th>Cheque No.</th> <th>Cheque Date</th> <th>Transaction ID / Code</th>
                                        <th>Transaction Date</th><th>Currency</th> <th>Advance Paid</th> <th>Paid in Full / Part</th> <th>Balance to Pay</th>
                                        <th>Bill Details Verified By</th> <th>Passed By</th>
                                    </tr>
                                    <tr>
                                        <td><?php echo $ArrBasicInfo->accountname ?></td>
                                        <td>
                                            <select id="ReqdModeOfPayment" name="ReqdModeOfPayment">
                                                <option value="1" <?php if(@$ArrBasicPaymentInfo['reqmodeofpayment'] == 1) echo 'selected' ?>>Cheque</option>
                                                <option value="2" <?php if(@$ArrBasicPaymentInfo['reqmodeofpayment'] == 2) echo 'selected' ?>>DD</option>
                                                <option value="3" <?php if(@$ArrBasicPaymentInfo['reqmodeofpayment'] == 3) echo 'selected' ?>>RTGS</option>
                                                <option value="4" <?php if(@$ArrBasicPaymentInfo['reqmodeofpayment'] == 4) echo 'selected' ?>>SWIFT</option>
                                            </select>
                                        </td>
                                        <td><input type="text" id="frmCheqNo" name="frmCheqNo" value="<?php ?>"> </td>
                                        <td>
                                            <input type="text" id="frmCheqDate" name="frmCheqDate" value="<?php echo date('d-m-Y',strtotime(0)) ?>">
                                        </td>
                                        <td><input type="text" id="frmTransId" name="frmTransId" value="<?php ?>"></td>
                                        <td>
                                            <input type="text" id="frmTransDate" name="frmTransDate" value="<?php echo date('d-m-Y',strtotime(0)) ?>">
                                        </td>
                                        <td></td>
                                        <td><input type="text" id="frmAdvPaid" name="frmAdvPaid" value="<?php ?>"></td>
                                        <td>
                                            <select id="frmFullPart" name="frmFullPart">
                                                <option value="">Choose</option>
                                                <option value="1">Full</option>
                                                <option value="2">Part</option>
                                            </select>
                                        </td>
                                        <td><input type="text" id="frmBaltoPay" name="frmBaltoPay" value="<?php ?>"></td>
                                        <td><?php ?></td>
                                        <td><?php ?></td>
                                    </tr>
                                </table>
                            </form>
                        </div>
                    </div>
                    <div id="divSuccessBasicInfoMsg" class="alert alert-success alert-dismissable hide"></div>
                    <?php
                    if(empty($VarCountRequest)) { ?>
                        <div class="box-footer nopadding">
                            <div class="herr" id="ErrfrmPage"></div>
                            <button class="btn btn-info pull-right addrights" type="submit" onclick="return fnSavePurDeptRequest()">Send Payment Request</button>
                        </div>
                        <?php
                    }
                    else {
                        ?>
                        <div class="alert alert-info" role="alert">
                            BOM Payment Request Sent Already!
                        </div>
                        <?php
                    }
                    ?>
                </div>
            </div>
        </section>
    </div><!-- /.content-wrapper -->
    <?php $this->load->view(CNFCOMPANY.'template/templatefooter');?>
    <div class="control-sidebar-bg"></div>
</div><!-- ./wrapper -->
<script src="<?php echo base_url();?>assets/js/ajax.js"></script>
<script src="<?php echo base_url(); ?>assets/plugins/datepicker/bootstrap-datepicker.js"></script>
<script src="<?php echo base_url();?>assets/js/jexcel/jquery.jexcel.js"></script>
<script src="<?php echo base_url();?>assets/js/jexcel/numeral.min.js"></script>
<script>
    $('#proformaDate').datepicker({
        format: 'dd-mm-yyyy',
        todayHighlight: true,
        autoclose: true
    });
    $('#payByDate').datepicker({
        format: 'dd-mm-yyyy',
        todayHighlight: true,
        autoclose: true
    });

    $('#invoiceDate').datepicker({
        format: 'dd-mm-yyyy',
        todayHighlight: true,
        autoclose: true
    });
    $('#paymentDueDate').datepicker({
        format: 'dd-mm-yyyy',
        todayHighlight: true,
        autoclose: true
    });
    var GlbPaymentType = 0; var GlbBomPurchaseInvoiceId = '<?php echo $VarBomPurchaseInvoiceId ?>'; var GlbBomPurIndReqId = '<?php echo $VarBomPurIndReqId ?>';
    var SavedInvoiceGrid = '<?php echo @$SavedInvoiceGrid ?>'; var GlbTaxType = '<?php echo @$ArrBasicInfo->taxtype ?>';
    function fnAdvPaymentBillPaymentType(thisobj) {
        GlbPaymentType = thisobj;
        console.log(GlbPaymentType,'GlbPaymentType');
        if(GlbPaymentType == 1) {
            $("#advPaymentDiv").removeClass('hide');
            $("#billPaymentDiv").addClass('hide');
        }
        else {
            $("#advPaymentDiv").addClass('hide');
            $("#billPaymentDiv").removeClass('hide');
        }
    }
    function fnSavePurDeptRequest() {
        if(GlbPaymentType == 1) {
            var advformdatas = $("#frmAdvPayment").serialize();
            //console.log(advformdatas,'advformdatas');
            var Param = "bompurchaseinvoiceid="+GlbBomPurchaseInvoiceId+"&bompurchaseindreqid="+GlbBomPurIndReqId+"&advancepayment=1"+"&"+advformdatas;
            MakeAsynPostRequest(base_path+'purchaseuser/updateBomPaymentReq',Param,'json',fnSavePurDeptRequestRes);
        }
        else {
            var billformdatas = $("#frmBillPayment").serialize();
            var Param = "bompurchaseinvoiceid="+GlbBomPurchaseInvoiceId+"&bompurchaseindreqid="+GlbBomPurIndReqId+"&billpayment=1"+"&"+billformdatas;
            MakeAsynPostRequest(base_path+'purchaseuser/updateBomPaymentReq',Param,'json',fnSavePurDeptRequestRes);
            //console.log(billformdatas,'billformdatas');
        }
    }
    function fnSavePurDeptRequestRes(data) {
        console.log(data,'data');
        if(data!='') {
            if(data.errcode == '1') {
                $("#divSuccessBasicInfoMsg").removeClass('hide');
                $("#divSuccessBasicInfoMsg").text("BOM Payment Request has been updated successfully!");
                //fnRedirectPageTimeOut(base_path+'purchaseuser/managepaymentsentlist');
            }
        }
    }
    //var url = $(location).attr('href'); var lasturlpart = url.substr(url.lastIndexOf('/')+1);
    if(GlbTaxType == 1) {
        $("#bomPurchaseIndentInvoice").jexcel({
            colHeaders: ['Item Description / (%) Blend /<br/>Content / Material','Gar.<br/>Size', 'Item Code', 'Item Color<br/>Code', 'Size / Dim.<br/>(W*L*H)',
                'Unit of<br/>Measure', 'Quantity', 'Unit<br/>Rate', 'Amount', 'SGST<br/>(%)', 'SGST<br/>Value', 'CGST<br/>(%)', 'CGST<br/>Value', 'Sub Total'],
            colWidths: [350, 50,80, 80, 80, 70,70,50, 100, 50, 90, 50, 90, 100],
            columns: [
                {type: 'text', readOnly: true, wordWrap: true},
                {type: 'text', readOnly: true, wordWrap: true},
                {type: 'text', readOnly: true, wordWrap: true},
                {type: 'text', readOnly: true, wordWrap: true},
                {type: 'text', readOnly: true, wordWrap: true},
                {type: 'text', readOnly: true, wordWrap: true},
                {type: 'text', readOnly: true},
                {type: 'text', readOnly: true},
                {type: 'text', readOnly: true},
                {type: 'text', readOnly: true},
                {type: 'text', readOnly: true},
                {type: 'text', readOnly: true},
                {type: 'text', readOnly: true},
                {type: 'text', readOnly: true},
            ],
            data: SavedInvoiceGrid
        });
    }
    else if(GlbTaxType == 2) {
        $("#bomPurchaseIndentInvoice").jexcel({
            colHeaders: ['Item Description / (%) Blend /<br/>Content / Material', 'Gar. Size','Item Code', 'Item Color<br/>Code', 'Size / Dim.<br/>(W*L*H)', 'Unit of<br/>Measure',
                'Quantity','Unit<br/>Rate','Amount', 'IGST<br/>(%)', 'IGST value', 'Sub Total'],
            colWidths: [350, 50, 90, 90, 80, 80, 70, 60, 100, 60, 90, 100],
            columns: [
                {type: 'text', wordWrap: true, readOnly: true},
                {type: 'text', readOnly: true, wordWrap: true},
                {type: 'text', readOnly: true, wordWrap: true},
                {type: 'text', readOnly: true, wordWrap: true},
                {type: 'text', readOnly: true, wordWrap: true},
                {type: 'text', readOnly: true, wordWrap: true},
                {type: 'text', readOnly: true},
                {type: 'text', readOnly: true},
                {type: 'text', readOnly: true},
                {type: 'text', readOnly: true},
                {type: 'text', readOnly: true},
                {type: 'text', readOnly: true},
            ],
            data: SavedInvoiceGrid
        });
    }
    else if(GlbTaxType == 3) {
        $("#bomPurchaseIndentInvoice").jexcel({
            colHeaders: ['Item Description / (%) Blend /<br/>Content / Material','Gar.<br/>Size','Item Code', 'Item Color<br/>Code', 'Size / Dim.<br/>(W*L*H)','Unit of<br/>Measure',
                'Quantity', 'Unit<br/>Rate', 'Amount', 'Duty<br/>(%)', 'Duty<br/>Value', 'Sub Total'],
            colWidths: [350, 50, 90, 90, 90, 80, 80, 60, 100, 50, 80, 100],
            columns: [
                {type: 'text', wordWrap: true, readOnly: true},
                {type: 'text', readOnly: true, wordWrap: true},
                {type: 'text', readOnly: true, wordWrap: true},
                {type: 'text', readOnly: true, wordWrap: true},
                {type: 'text', readOnly: true, wordWrap: true},
                {type: 'text', readOnly: true},
                {type: 'text', readOnly: true},
                {type: 'text', readOnly: true},
                {type: 'text', readOnly: true},
                {type: 'text', readOnly: true},
                {type: 'text', readOnly: true}
            ],
            data: SavedInvoiceGrid
        });
    }
</script>
<?php $this->load->view(CNFCOMPANY.'template/pagefooter');?>