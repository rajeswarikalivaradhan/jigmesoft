<?php $this->load->view(CNFCOMPANY.'template/pageheader'); ?>
    <link rel="stylesheet" href="<?php echo base_url();?>assets/css/jexcel/jquery.jexcel.min.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/plugins/datepicker/datepicker3.css">
    <style type="text/css">

    </style>
<body class="sidebar-mini skin-black wysihtml5-supported sidebar-collapse">
<div class="wrapper">
    <?php $this->load->view(CNFCOMPANY.'template/templateheader');?>
    <!-- Left side column. contains the logo and sidebar -->
    <aside class="main-sidebar">
        <?php $this->load->view(CNFCOMPANY.'template/templateleftmenu');?>
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
                            </div>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <table class="table">
                                <tr>
                                    <td>
                                        <table class="table" id="fromto">
                                            <tr><th style="background-color: #f3f3f3" colspan="2">FROM</th>
                                            </tr>
                                            <tr><td>NAME</td><td><?php
                                                    echo $ArrCompanyInfo[0]['ceoname'] ?></td></tr>
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
                                        <input type="text" style="position: relative; left: 784px; width: 100px" id="frmBasicTotal" value="<?php if(!empty($ArrBasicInfo->amounttotal)) echo $ArrBasicInfo->amounttotal ?>">
                                        <input type="text" style="position:relative; left: 831px; width: 80px; " id="frmBasicDutyvalueTotal" value="<?php if(!empty($ArrBasicInfo->dutytotal)) echo $ArrBasicInfo->dutytotal ?>">
                                        <input type="text" style="position:relative; left: 831px; width: 100px; " id="frmBasicSubtotalTotal" value="<?php if(!empty($ArrBasicInfo->subtotal_total)) echo $ArrBasicInfo->subtotal_total ?>">
                                        <?php
                                    }
                                    elseif($ArrBasicInfo->taxtype == 1) {
                                        ?>
                                        <label class="" style="position:relative; left: 670px">Total : </label>
                                        <div class="" style="position:relative; left: 685px; display: inline-block">&#8377;</div>
                                        <input type="text" style="position: relative; left: 713px; width: 100px" id="frmBasicTotal" value="<?php if(!empty($ArrBasicInfo->amounttotal)) echo $ArrBasicInfo->amounttotal ?>">
                                        <input type="text" style="position:relative; left: 760px; width: 90px; " id="frmBasicSgstTotal" value="<?php if(!empty($ArrBasicInfo->sgsttotal)) echo $ArrBasicInfo->sgsttotal ?>">
                                        <input type="text" style="position:relative; left: 808px; width: 90px; " id="frmBasicCgstTotal" value="<?php if(!empty($ArrBasicInfo->cgsttotal)) echo $ArrBasicInfo->cgsttotal ?>">
                                        <input type="text" style="position:relative; left: 810px; width: 100px; " id="frmBasicSubtotalTotal" value="<?php if(!empty($ArrBasicInfo->subtotal_total)) echo $ArrBasicInfo->subtotal_total ?>">
                                        <?php
                                    }
                                    elseif($ArrBasicInfo->taxtype == 2) {
                                        ?>
                                        <label class="" style="position:relative; left: 818px">Total : </label>
                                        <div class="" style="position:relative; left: 825px; display: inline-block">&#8377;</div>
                                        <input type="text" style="position: relative; left: 835px; width: 100px" id="frmBasicTotal" value="<?php if(!empty($ArrBasicInfo->amounttotal)) echo $ArrBasicInfo->amounttotal ?>">
                                        <input type="text" style="position:relative; left: 891px; width: 90px; " id="frmBasicIgstTotal" value="<?php if(!empty($ArrBasicInfo->igsttotal)) echo $ArrBasicInfo->igsttotal ?>">
                                        <input type="text" style="position:relative; left: 891px; width: 90px; " id="frmBasicSubtotalTotal" value="<?php if(!empty($ArrBasicInfo->subtotal_total)) echo $ArrBasicInfo->subtotal_total ?>">
                                        <?php
                                    }
                                    ?>

                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="box box-info">
                        <div class="box-header with-border">
                            <h3 class="box-title"><b>BOM PAYMENT REQUEST</b></h3>
                            <div class="box-tools pull-right">

                            </div>
                        </div><!-- /.box-header -->
                            <div class="box-body <?php if(!empty($VarId)) { if($ArrBasicPaymentInfo['advancepayment'] != 1) echo 'hide'; } ?>" id="advPaymentDiv">
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
                                                <input type="text" readonly id="proformaNo" name="proformaNo" style="width: 110px" value="<?php if(!empty($ArrBasicPaymentInfo['proformano'])) echo $ArrBasicPaymentInfo['proformano'] ?>">
                                            </td>
                                            <td>
                                                <input type="text" readonly id="proformaDate" name="proformaDate" style="width: 80px" placeholder="Choose" value="<?php if(!empty($ArrBasicPaymentInfo['proformadate'])) echo date('d-m-Y',(strtotime($ArrBasicPaymentInfo['proformadate']))); ?>">
                                            </td>
                                            <td><?php echo $ArrBasicInfo->purchaseindentno.'/'.$ArrBasicInfo->isriorcode ?></td>
                                            <td><?php echo date('d-m-Y',strtotime( $ArrBasicInfo->datecreated)) ?> </td>
                                            <td><?php echo $VarCurrency; ?></td>
                                            <td>
                                                <input type="text" id="proformaValue" readonly name="proformaValue" style="width: 110px" value="<?php if(!empty($ArrBasicPaymentInfo['proformavalue'])) echo $ArrBasicPaymentInfo['proformavalue'] ?>">
                                            </td>
                                            <td><?php echo $ArrBasicInfo->subtotal_total ?></td>
                                            <td><input type="text" id="advPayable" readonly name="advPayable" value="<?php if(!empty($ArrBasicPaymentInfo['advpayable'])) echo $ArrBasicPaymentInfo['advpayable'] ?>"> </td>
                                            <td>
                                                <select id="ReqdModeOfPayment" name="ReqdModeOfPayment" disabled>
                                                    <option value="1" <?php if(@$ArrBasicPaymentInfo['reqmodeofpayment'] == 1) echo 'selected' ?>>Cheque</option>
                                                    <option value="2" <?php if(@$ArrBasicPaymentInfo['reqmodeofpayment'] == 2) echo 'selected' ?>>DD</option>
                                                    <option value="3" <?php if(@$ArrBasicPaymentInfo['reqmodeofpayment'] == 3) echo 'selected' ?>>RTGS</option>
                                                    <option value="4" <?php if(@$ArrBasicPaymentInfo['reqmodeofpayment'] == 4) echo 'selected' ?>>SWIFT</option>
                                                </select>
                                            </td>
                                            <td>
                                                <input type="text" id="payByDate" readonly name="payByDate" style="width: 80px" placeholder="Choose"
                                                       value="<?php if(!empty($ArrBasicPaymentInfo['paybydate'])) echo date('d-m-Y',(strtotime($ArrBasicPaymentInfo['paybydate']))) ?>">

                                            </td>
                                            <td>
                                                <select id="AdvPaymentApprStatus" name="AdvPaymentApprStatus">
                                                    <option value="1" <?php if(!empty($ArrBasicPaymentInfo['apprstatus'])) echo $ArrBasicPaymentInfo['apprstatus'] == '1' ? 'selected' : '' ?>>Pending</option>
                                                    <option value="2" <?php if(!empty($ArrBasicPaymentInfo['apprstatus'])) echo $ArrBasicPaymentInfo['apprstatus'] == '2' ? 'selected' : '' ?>>Approved</option>
                                                    <option value="3" <?php if(!empty($ArrBasicPaymentInfo['apprstatus'])) echo $ArrBasicPaymentInfo['apprstatus'] == '1' ? 'selected' : '' ?>>Declined</option>
                                                </select>
                                            </td>
                                            <td id="ApprdBy"><?php echo $apprbyname ?></td>
                                        </tr>
                                    </table>
                                </form>
                            </div>
                            <div class="box-body <?php if(!empty($VarId)) { if($ArrBasicPaymentInfo['billpayment'] != 1) echo 'hide'; } ?>" id="billPaymentDiv">
                            <h5 class="box-title"><b>Bill Payment</b></h5>
                                <form id="frmBillPayment" name="frmBillPayment" class="">
                                    <table class="table">
                                        <tr>
                                            <th>WIP No.</th> <th>Invoice No.</th> <th>Invoice Date</th> <th>Currency</th><th>Invoice Value</th> <th>Advance Paid</th>
                                            <th>Debit Value</th> <th>Amount<br/>Payable</th> <th>Reqt. Mode<br/>of<br/>Payment</th> <th>Payment<br/>Due<br/>Date</th>
                                            <th>Approval<br/>Status</th> <th>Approved By</th>
                                        </tr>
                                        <tr>
                                            <td><?php echo $ArrBasicInfo->isriorcode;
                                            //echo '<pre>'; print_r($ArrBasicFinanceDeptInfo); die('');
                                                ?>
                                            </td>
                                            <td><input type="text" id="invoiceNo" name="invoiceNo" readonly style="width: 110px" value="<?php echo $ArrBasicPaymentInfo['invoiceno']; ?>"> </td>
                                            <td><input type="text" id="invoiceDate" name="invoiceDate" readonly style="width: 82px" placeholder="Choose" value="<?php echo $ArrBasicPaymentInfo['invoicedate'] ?>"></td>
                                            <td>
                                                <input type="hidden" id="BillPayfrmCurrency" name="BillPayfrmCurrency" value="<?php echo $ArrBasicInfo->currencycode ?>">
                                                <?php echo @$ArrCurrencyCode[$ArrBasicInfo->currencycode] ?>
                                            </td>
                                            <td><input type="text" id="invoiceValue" name="invoiceValue" readonly style="width: 110px" value="<?php echo $ArrBasicPaymentInfo['invoicevalue'] ?>"></td>
                                            <td><?php //echo $ArrBasicPaymentInfo['advpaid']
                                                //echo '<pre>'; print_r($ArrBasicFinanceDeptInfo); die('');
                                                $totaladvance = 0;
                                                foreach ($ArrBasicFinanceDeptInfo as $finDeptChild) {
                                                    $totaladvance += $finDeptChild['advpaid'];
                                                }
                                                echo $totaladvance;
                                                ?>
                                                </td>
                                            <td><input type="text" id="debitValue" name="debitValue" readonly style="width: 110px" value="<?php echo $ArrBasicPaymentInfo['debitvalue'] ?>"> </td>
                                            <td><input type="text" id="amountPayable" name="amountPayable" readonly style="width: 110px" value="<?php echo $ArrBasicPaymentInfo['amountpayable'] ?>"> </td>
                                            <td>
                                                <?php
                                                ?>
                                                <select id="ReqdModeOfPayment" name="ReqdModeOfPayment" disabled>
                                                    <option value="1" <?php if($ArrBasicPaymentInfo['reqmodeofpayment'] == 1) echo 'selected' ?>>Cheque</option>
                                                    <option value="2" <?php if($ArrBasicPaymentInfo['reqmodeofpayment'] == 2) echo 'selected' ?>>DD</option>
                                                    <option value="3" <?php if($ArrBasicPaymentInfo['reqmodeofpayment'] == 3) echo 'selected' ?>>RTGS</option>
                                                    <option value="4" <?php if($ArrBasicPaymentInfo['reqmodeofpayment'] == 4) echo 'selected' ?>>SWIFT</option>
                                                </select>
                                            </td>
                                            <td><input type="text" id="paymentDueDate" name="paymentDueDate" readonly style="width: 82px;" value="<?php if(!empty($ArrBasicPaymentInfo['paymentduedate'])) echo date('d-m-Y',strtotime($ArrBasicPaymentInfo['paymentduedate'])) ?>"></td>
                                            <td>
                                                <select id="BillPaymentApprStatus" name="BillPaymentApprStatus">
                                                    <option value="1" <?php echo $ArrBasicPaymentInfo['billpayapprstatus'] == 1 ? 'selected' : '' ?>>Pending</option>
                                                    <option value="2" <?php echo $ArrBasicPaymentInfo['billpayapprstatus'] == 2 ? 'selected' : '' ?>>Approved</option>
                                                    <option value="3" <?php echo $ArrBasicPaymentInfo['billpayapprstatus'] == 3 ? 'selected' : '' ?>>Declined</option>
                                                </select>
                                            </td>
                                            <td id="ApprdBy"><?php echo $apprbyname ?></td>
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
                            <div class="box-tools pull-right"> </div>
                        </div><!-- /.box-header -->
                        <?php
                        $VarAdvPaymentPPdetailsShow = 0;
                        foreach ($ArrBasicFinanceDeptInfo as $finDeptChildAdv) {
                            if($finDeptChildAdv['advancepayment'] == 1) {
                                $VarAdvPaymentPPdetailsShow = 1;
                            }
                        }
                        if($VarAdvPaymentPPdetailsShow) {
                            ?>
                            <div class="box-body" id="advPaymentDiv">
                            <h5 class="box-title"><b>Advance Payment</b></h5>
                            <form name="frmAdvPaymentFinance" id="frmAdvPaymentFinance" class="">
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th>Paid in Favour of</th> <th>Mode of Payment</th> <th>Cheque No.</th> <th>Cheque Date</th> <th>Transaction ID / Code</th>
                                        <th>Transaction Date</th><th>Currency</th> <th>Advance Paid</th> <th>Paid in Full / Part</th> <th>Balance to Pay</th>
                                        <th>Bill Details Verified By</th> <th>Passed By</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    foreach ($ArrBasicFinanceDeptInfo as $item) {
                                        if($item['advancepayment']) {
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
                                                    <input type="text" id="" name="" style="width: 80px" value="<?php if(!empty($item['transdate'])) echo date('d-m-Y',strtotime($item['transdate'])) ?>">
                                                </td>
                                                <td>
                                                    <?php
                                                    echo @$ArrCurrencyCode[$ArrBasicPaymentInfo['currency']];
                                                    ?>
                                                </td>
                                                <td><input type="text" id="frmAdvPaid" name="frmAdvPaid" onkeyup="fnAdvPaymentCalcBalance(this.value)" class="frmAdvPaidCls" style="width: 130px" value="<?php echo @$item['advpaid'] ?>"></td>
                                                <td>
                                                    <select id="" name="">
                                                        <option value="">Choose</option>
                                                        <option value="1" <?php echo $item['full_part_id'] == 1 ? 'selected' : '' ?>>Full</option>
                                                        <option value="2" <?php echo $item['full_part_id'] == 2 ? 'selected' : '' ?>>Part</option>
                                                    </select>
                                                </td>
                                                <td>
                                                    <?php
                                                    //if(!empty($ArrBasicPaymentInfo['advpayable'])) $ArrBasicPaymentInfo['advpayable'] +
                                                    ?>
                                                    <input type="text" id="" name="" style="width: 140px" value="<?php echo @$item['baltopay'] ?>"></td>
                                                <td><?php echo $VarBillVerifiedby ?></td>
                                                <td><?php echo $VarPassedBy ?></td>
                                            </tr>
                                            <?php
                                        }
                                        ?>
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
                                        <td><input type="text" id="frmCheqNo" name="frmCheqNo" style="width: 110px"> </td>
                                        <td>
                                            <input type="text" id="frmCheqDate" name="frmCheqDate" style="width: 80px">
                                        </td>
                                        <td><input type="text" id="frmTransId" name="frmTransId" style="width: 110px"></td>
                                        <td>
                                            <input type="text" id="frmTransDate" name="frmTransDate" style="width: 80px">
                                        </td>
                                        <td>
                                            <?php  echo @$ArrCurrencyCode[$ArrBasicPaymentInfo['currency']]; ?>
                                        </td>
                                        <td><input type="text" class="frmAdvPaidCls" onkeyup="fnAdvPaymentCalcBalance(this.value)" id="frmAdvPaid" name="frmAdvPaid" style="width: 130px"></td>
                                        <td>
                                            <select id="frmFullPart" name="frmFullPart">
                                                <option value="">Choose</option>
                                                <option value="1">Full</option>
                                                <option value="2">Part</option>
                                            </select>
                                        </td>
                                        <td><input type="text" id="frmBaltoPay" name="frmBaltoPay" style="width: 140px"></td>
                                        <td><?php echo $VarBillVerifiedby ?></td>
                                        <td><?php echo $VarPassedBy ?></td>
                                    </tr>
                                    </tbody>
                                </table>
                            </form>
                        </div>
                            <?php
                        }

                        $VarBillPaymentPPdetailsShow = 0;
                        foreach ($ArrBasicFinanceDeptInfo as $finDeptChildAdv) {
                            if($finDeptChildAdv['billpayment'] == 1) {
                                $VarBillPaymentPPdetailsShow = 1;
                            }
                        }
                        if($VarBillPaymentPPdetailsShow) {
                            ?>
                            <div class="box-body" id="billPaymentDiv">
                                <h5 class="box-title"><b>Bill Payment</b></h5>
                                <form id="frmBillPaymentFinance" name="frmBillPaymentFinance" class="">
                                    <table class="table">
                                        <tr>
                                            <th>Paid in Favour of</th> <th>Mode of Payment</th> <th>Cheque No.</th> <th>Cheque Date</th> <th>Transaction ID / Code</th>
                                            <th>Transaction Date</th><th>Currency</th> <th>Amount Paid</th> <th>Paid in Full / Part</th> <th>Balance to Pay</th>
                                            <th>Bill Details Verified By</th> <th>Passed By</th>
                                        </tr>
                                        <?php
                                        //echo '<pre>'; print_r($ArrBasicFinanceDeptInfo); die('');
                                        foreach ($ArrBasicFinanceDeptInfo as $item) {
                                            if($item['billpayment']) {
                                                ?>
                                                <tr>
                                                    <td><?php echo $ArrBasicInfo->accountname ?></td>
                                                    <td>
                                                        <select id="" name="">
                                                            <option value="1" <?php if (@$item['modeofpayment'] == 1) echo 'selected' ?>>
                                                                Cheque
                                                            </option>
                                                            <option value="2" <?php if (@$item['modeofpayment'] == 2) echo 'selected' ?>>
                                                                DD
                                                            </option>
                                                            <option value="3" <?php if (@$item['modeofpayment'] == 3) echo 'selected' ?>>
                                                                RTGS
                                                            </option>
                                                            <option value="4" <?php if (@$item['modeofpayment'] == 4) echo 'selected' ?>>
                                                                SWIFT
                                                            </option>
                                                        </select>
                                                    </td>
                                                    <td><input type="text" id="" name="" style="width: 110px"
                                                               value="<?php if (!empty($item['chequeno'])) echo $item['chequeno'] ?>">
                                                    </td>
                                                    <td>
                                                        <input type="text" id="" name="" style="width: 80px"
                                                               value="<?php if (!empty($item['chequedate'])) echo date('d-m-Y', strtotime($item['chequedate'])) ?>">
                                                    </td>
                                                    <td><input type="text" id="" name="" style="width: 110px"
                                                               value="<?php echo $item['transid'] ?>"></td>
                                                    <td>
                                                        <input type="text" id="" name="" style="width: 80px"
                                                               value="<?php if (!empty($item['transdate'])) echo date('d-m-Y', strtotime($item['transdate'])) ?>">
                                                    </td>
                                                    <td><?php echo @$ArrCurrencyCode[$ArrBasicPaymentInfo['currency']]; ?></td>
                                                    <td><input type="text" id="" name="" class="BillPaymentAmountPaidCls"
                                                               style="width: 130px"
                                                               value="<?php echo $item['amountpaid'] ?>"></td>
                                                    <td>
                                                        <select id="" name="">
                                                            <option value="">Choose</option>
                                                            <option value="1" <?php echo $item['full_part_id'] == 1 ? 'selected' : '' ?>>
                                                                Full
                                                            </option>
                                                            <option value="2" <?php echo $item['full_part_id'] == 2 ? 'selected' : '' ?>>
                                                                Part
                                                            </option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <?php
                                                        //if(!empty($ArrBasicPaymentInfo['advpayable'])) $ArrBasicPaymentInfo['advpayable'] +
                                                        ?>
                                                        <input type="text" id="" name="" style="width: 140px"
                                                               value="<?php echo $item['baltopay'] ?>"></td>
                                                    <td>
                                                        <?php echo $VarBillVerifiedby ?>
                                                    </td>
                                                    <td>
                                                        <?php echo $VarPassedBy ?>
                                                    </td>
                                                </tr>
                                                <?php
                                            }
                                            ?>
                                            <?php
                                        }
                                        ?>
                                        <tr id="BillPaymentXtra" class="hide">
                                            <td><?php echo $ArrBasicInfo->accountname ?></td>
                                            <td>
                                                <select id="BillpaymentReqdModeOfPayment" name="BillpaymentReqdModeOfPayment">
                                                    <option value="1">Cheque</option>
                                                    <option value="2" selected="">DD</option>
                                                    <option value="3">RTGS</option>
                                                    <option value="4">SWIFT</option>
                                                </select>
                                            </td>
                                            <td><input type="text" id="BillPaymentfrmCheqNo" name="BillPaymentfrmCheqNo" style="width: 110px"> </td>
                                            <td>
                                                <input type="text" id="BillPaymentfrmCheqDate" name="BillPaymentfrmCheqDate" style="width: 80px">
                                            </td>
                                            <td><input type="text" id="BillPaymentfrmTransId" name="BillPaymentfrmTransId" style="width: 110px"></td>
                                            <td>
                                                <input type="text" id="BillpaymentfrmTransDate" name="BillpaymentfrmTransDate" style="width: 80px">
                                            </td>
                                            <td>
                                                <?php  echo @$ArrCurrencyCode[$ArrBasicPaymentInfo['currency']]; ?>
                                            </td>
                                            <td><input type="text" id="BillPaymentfrmAmountPaid" name="BillPaymentfrmAmountPaid" class="BillPaymentAmountPaidCls" style="width: 130px"></td>
                                            <td>
                                                <select id="BillPaymentfrmFullPart" name="BillPaymentfrmFullPart">
                                                    <option value="">Choose</option>
                                                    <option value="1">Full</option>
                                                    <option value="2">Part</option>
                                                </select>
                                            </td>
                                            <td><input type="text" id="BillPaymentfrmBaltoPay" name="BillPaymentfrmBaltoPay" style="width: 140px" value="<?php echo ''; ?>"></td>
                                            <td>
                                                <?php echo $VarBillVerifiedby ?>
                                            </td>
                                            <td>
                                                <?php echo $VarPassedBy ?>
                                            </td>
                                        </tr>
                                    </table>

                                </form>
                            </div>
                            <?php
                        }
                            ?>

                        <div id="divSuccessBasicInfoMsg" class="alert alert-success alert-dismissable hide"></div>
                    </div>

                    <div id="divSuccessBasicInfoMsg" class="alert alert-success alert-dismissable hide"></div>
                    <?php
                    if(@$ArrBasicPaymentInfo['apprstatus'] != 2 || @$ArrBasicPaymentInfo['billpayapprstatus'] != 2) {

                            ?>
                            <div class="box-footer nopadding">
                                <div class="herr" id="ErrfrmPage"></div>
                                <button class="btn btn-info pull-right addrights" type="submit" onclick="return fnSavePaymentReqMgmt()">Save</button>
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
    var GlbBomPurchaseInvoiceId = '<?php echo $VarBomPurchaseInvoiceId ?>'; var GlbBomPurIndReqId = '<?php echo $VarBomPurIndReqId ?>';
    var SavedInvoiceGrid = '<?php echo @$SavedInvoiceGrid ?>'; var GlbTaxType = '<?php echo @$ArrBasicInfo->taxtype ?>';
    var GlbId = '<?php echo $VarId ?>';
    var GlbAdvPayment = '<?php echo $ArrBasicPaymentInfo['advancepayment'] ?>';
    var GlbBillPayment = '<?php echo $ArrBasicPaymentInfo['billpayment'] ?>';
    function fnSavePaymentReqMgmt() {
        /*if(GlbPaymentType == 1) {
            var ApprStatus = $("#AdvPaymentApprStatus").val();
            var Param = "id="+GlbId+"&bompurchaseinvoiceid="+GlbBomPurchaseInvoiceId+"&bompurchaseindreqid="+GlbBomPurIndReqId+"&paymenttype="+GlbPaymentType+"&ApprStatus="+ApprStatus;
            MakeAsynPostRequest(base_path+'management/updateBomPaymentReq',Param,'json',fnSavePaymentReqMgmtRes);
        }
        else if(GlbPaymentType == 2) {*/

        if(GlbAdvPayment == 1) {
            var ApprStatus = $("#AdvPaymentApprStatus").val();
            var Param = "id="+GlbId+"&bompurchaseinvoiceid="+GlbBomPurchaseInvoiceId+"&bompurchaseindreqid="+GlbBomPurIndReqId+"&ApprStatus="+ApprStatus;
            MakeAsynPostRequest(base_path+'management/updateBomPaymentReq',Param,'json',fnSavePaymentReqMgmtRes);
        }
        if(GlbBillPayment == 1) {
            var BillPaymentApprStatus = $("#BillPaymentApprStatus").val();
            var Param = "id="+GlbId+"&bompurchaseinvoiceid="+GlbBomPurchaseInvoiceId+"&bompurchaseindreqid="+GlbBomPurIndReqId+"&billpaymentApprstatus="+BillPaymentApprStatus;
            MakeAsynPostRequest(base_path+'management/updateBomPaymentReq',Param,'json',fnSavePaymentReqMgmtRes);
        }
        /*}*/
    }
    function fnSavePaymentReqMgmtRes(data) {
        console.log(data,'data');
        if(data!='') {
            if(data.errcode == '1') {
                $("#divSuccessBasicInfoMsg").removeClass('hide');
                $("#divSuccessBasicInfoMsg").text("BOM Payment Request has been updated successfully!");
                //fnRedirectPageTimeOut(base_path+'management/approvallist');
            }
        }
    }
    //var url = $(location).attr('href'); var lasturlpart = url.substr(url.lastIndexOf('/')+1);
    if(GlbTaxType == 1) {
        $("#bomPurchaseIndentInvoice").jexcel({
            colHeaders: ['Item Description / (%) Blend /<br/>Content / Material', 'Item Code', 'Item Color<br/>Code', 'Size / Dim.<br/>(W*L*H)',
                'Unit of<br/>Measure', 'Quantity', 'Unit<br/>Rate', 'Amount', 'SGST<br/>(%)', 'SGST<br/>Value', 'CGST<br/>(%)', 'CGST<br/>Value', 'Sub Total'],
            colWidths: [320, 80, 80, 80, 70,70,50, 100, 50, 90, 50, 90, 100],
            columns: [
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
            colHeaders: ['Item Description / (%) Blend /<br/>Content / Material', 'Item Code', 'Item Color<br/>Code', 'Size / Dim.<br/>(W*L*H)', 'Unit of<br/>Measure',
                'Quantity','Unit<br/>Rate','Amount', 'IGST<br/>(%)', 'IGST value', 'Sub Total'],
            colWidths: [400, 90, 90, 80, 80, 70, 60, 100, 60, 90, 100],
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
                {type: 'text', readOnly: true},
            ],
            data: SavedInvoiceGrid
        });
    }
    else if(GlbTaxType == 3) {
        $("#bomPurchaseIndentInvoice").jexcel({
            colHeaders: ['Item Description / (%) Blend /<br/>Content / Material', 'Item Code', 'Item Color<br/>Code', 'Size / Dim.<br/>(W*L*H)','Unit of<br/>Measure',
                'Quantity', 'Unit<br/>Rate', 'Amount', 'Duty<br/>(%)', 'Duty<br/>Value', 'Sub Total'],
            colWidths: [400, 90, 90, 90, 80, 80, 60, 100, 50, 80, 100],
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