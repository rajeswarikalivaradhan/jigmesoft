<?php $this->load->view(CNFCOMPANY . 'template/pageheader');
$ArrLoggedUserInfo = fnGetUserLoggedInfo(1);
$ArrUserDetails = fnGetUserLoggedInfo(); ?>
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/jexcel4/jsuites.css"/>
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/jexcel4/jexcel.css"/>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/plugins/datepicker/datepicker3.css">
    <style type="text/css">
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
                            <div id="bomPiTaxType">
                            <?php
                            $TaxTypeId = $ArrBasicInfo->taxtype;
                            $ArrTaxType = unserialize(BOMPURCHASETAXTYPE);
                            echo $ArrTaxType[$TaxTypeId];
                            ?>
                            </div>
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
                                                    <span class="form-control">
                                                        <?php

                                                        echo $VendorInfo[0]['vendorname'] ?>
                                                    </span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>ADDRESS</td>
                                                <td id="toaddress"><?php echo $VendorInfo[0]['address'] ?></td>
                                            </tr>
                                            <tr>
                                                <td>CONTACT NO:</td>
                                                <td id="contactno"><?php echo $VendorInfo[0]['phone'] ?></td>
                                            </tr>
                                            <tr>
                                                <td>E-MAIL ID:</td>
                                                <td id="emailid"><?php echo $VendorInfo[0]['emailid'] ?></td>
                                            </tr>
                                            <tr>
                                                <td>GST NO:</td>
                                                <td id="gstno"><?php echo $VendorInfo[0]['gstno'] ?></td>
                                            </tr>
                                            <tr>
                                                <td>IE CODE:</td>
                                                <td id="iecode"><?php echo $VendorInfo[0]['iecode'] ?></td>
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
                                                <td>
                                                    <div id="PurchaseIndentNo"><?php echo $PiNo ?></div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>DATE & TIME:</td>
                                                <td>
                                                    <?php if (empty($ArrBasicInfo->datecreated))
                                                        echo date('d-m-Y H:i:s');
                                                    else echo date('d-m-Y H:i:s', strtotime($ArrBasicInfo->datecreated)) ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>AGREED SUPPLY DATE:</td>
                                                <td>

                                                        <input type="text" class="form-control" readonly
                                                               value="<?php echo ($ArrBasicInfo->agreedsupplydate == '0000-00-00') ? '' : $ArrBasicInfo->agreedsupplydate ?>" id="agreedsupplydate">

                                                </td>
                                            </tr>
                                            <tr>
                                                <td>SUPPLY CUTOFF DATE:</td>
                                                <td><?php
                                                    if (!empty($ArrBasicPurRequestInfo->cutoffdatetime))
                                                        echo date('d-m-Y H:i:s', strtotime($ArrBasicPurRequestInfo->cutoffdatetime))
                                                    ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>PAYMENT TERMS:</td>
                                                <td>
                                                    <input type="text" class="form-control" readonly id="frmBasicPaymentTerms" value="<?php echo $ArrBasicInfo->paymentterms ?>">
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
                    <div class="box-body">
                        <div class="row"></div>
                        <small>Herewith, we place an order for the following items:</small>
                        <div id="bomPurchaseIndentJxl" class="table"></div>
                    </div>
                    <div class="box-body">
                        <form class="form-horizontal">
                            <div class="form-group">
                                <label class="col-md-2"><b>Amount in words:</b></label>
                                <div class="col-md-10">
                                    <input type="text" readonly id="frmAmountInWords" class="form-control" value="<?php echo $ArrBasicInfo->amountinwords ?>">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2"><b>Advance Paid Details:</b></label>
                                <div class="col-md-10">
                                    <input type="text" readonly class="form-control" value="<?php if(!empty($paymentpaidinfotoprint)) echo $paymentpaidinfotoprint; ?>">

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
                                    <textarea id="frmRemarks" readonly class="form-control"><?php echo $ArrBasicInfo->purdeptremarks ?></textarea>
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
                                            <td>
                                                <input class="form-control" id="frmBasicPurchaserName" type="text" value="<?php echo $ArrBasicInfo->purchasername ?>">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>MOBILE:</td>
                                            <td>
                                                <input class="form-control" id="frmBasicPurchaserMobile" type="text" value="<?php echo $ArrBasicInfo->purchasermobile ?>">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>EMAIL:</td>
                                            <td>
                                                <input class="form-control" id="frmBasicPurchaserEmail" type="text" value="<?php echo $ArrBasicInfo->purchaseremail ?>">
                                            </td>
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
                                            <td>
                                                <input class="form-control" id="frmBasicVendorName" type="text" value="<?php echo $ArrBasicInfo->xtravendorname ?>">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>MOBILE:</td>
                                            <td>
                                                <input class="form-control" id="frmBasicVendorMobile" type="text" value="<?php echo $ArrBasicInfo->xtravendormobile ?>">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>EMAIL:</td>
                                            <td>
                                                <input class="form-control" id="frmBasicVendorEmail" type="text" value="<?php echo $ArrBasicInfo->xtravendoremail ?>">
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <label class="control-label">Prepared by:</label>
                                <br/>
                                <?php echo $PurchaseDeptUserInfo['contactname']; ?>
                                <br/><br/>
                                <label class="control-label">Name & Signature</label>
                                <br/>

                            </div>
                            <div class="col-md-2">
                                <label class="control-label">Authorized by:</label><br/>
                                <?php echo ' Management 1 ' ?>
                                <br/><br/>
                                <label class="control-label">Name & Signature</label>
                                <br/>
                                <?php echo '' ?>
                            </div>
                            <div class="col-md-2">
                                <label class="control-label">Approved by:</label><br/>
                                <?php echo ' Management 2 ' ?>
                                <br/><br/>
                                <label class="control-label">Name & Signature</label>
                                <br/>
                                <?php echo '' ?>
                            </div>
                            <div class="col-md-2">
                                <label class="control-label">For</label><br/>
                                <?php echo $ArrCompanyInfo[0]['companyname']; ?>
                                <br/><br/>
                                <label class="control-label">Authorized Signatory</label>
                                <br/>
                                <?php echo '' ?>
                            </div>
                        </div>
                        <!--<div class="box-footer">
                            <button class="btn btn-info pull-right" type="button"
                                    onclick="return fnSavePIinLocalStorage()">Save
                            </button>
                        </div>-->
                        <!--<a href="<?php /*echo base_url('purchaseuser/bompurchaseinvoicepdf/' . $VarRequestId) */ ?>">Save as
                            pdf</a>-->
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
                                        <span class="form-control" id="">
                                            <?php
                                            echo $PurchaseDeptUserInfo['contactname'];
                                            ?>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="col-md-8">APPROVAL STATUS</label>
                                    <div class="col-md-12">
                                        <select id="bomPIAdvPaymentApprStatus" class="form-control">
                                            <option value="">Choose</option>
                                            <option value="1" <?php //if($ArrBasicInfo->approvedstatus == 1) echo 'selected' ?>>PENDING</option>
                                            <option value="2" <?php //if($ArrBasicInfo->approvedstatus == 2) echo 'selected' ?>>AUTHORIZE</option>
                                            <option value="3" <?php //if($ArrBasicInfo->approvedstatus == 3) echo 'selected' ?>>REJECT</option>
                                        </select>

                                            <?php

                                            ?>

                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="col-md-8">APPROVED / DECLINED BY</label>
                                    <div class="col-md-12">
                                        <span class="form-control" id="bomPIAdvPaymentApprBy">
                                            <?php
                                            if(!empty($ArrBasicInfo->approvedbymgmtid)) {
                                                $Res = $this->commonmodel->getUserInfo($ArrBasicInfo->approvedbymgmtid);
                                                echo $Res[0]->contactname;
                                            }
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
                                            //echo '<pre>'; print_r($ArrBasicInfo); die('die');
                                            if(!empty($ArrBasicInfo->approvedDatetime) && $ArrBasicInfo->approvedDatetime != '0000-00-00 00:00:00')
                                                echo date('d-m-Y H:i:s',strtotime($ArrBasicInfo->approvedDatetime));
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
                        <div class="box-header with-border">
                            <h3 class="box-title"><b>Vendors' Bank Details</b></h3>
                        </div>
                        <div id="vendorBankDetailsJxl"></div>


                    </div>
                </div>
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title"><b>PAYMENT PAID DETAILS</b></h3>
                    </div>

                    <div class="box-body" id="bomPipaymentPaidDetails">
                        <div id="financeDeptPayPaidJxl" class=""></div>
                    </div>
                </div>
                <div class="box box-info">
                    <form name="frmAdvPaymentFinance" id="frmMerchantBomPurReqDetails" class="form-horizontal">
                        <div class="box-body box-bodyPd2010" id="merchantBomPurReqDetails">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="inputEmail3" class="col-sm-4 control-label">Purpose</label>
                                    <div class="col-sm-8">
                                        <?php
                                        //echo '<pre>'; print_r($ArrBasicInfo);
                                        ?>
                                        <input type="text" class="form-control" readonly value="<?php echo $ArrBasicPurRequestInfo->purpose ?>">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="inputEmail3" class="col-sm-4 control-label">Request Type</label>
                                    <div class="col-sm-8">
                                        <?php $ArrRequestTimeType = unserialize(ARRREQUESTTYPE) ?>
                                        <input type="text" class="form-control" readonly value="<?php if (!empty($ArrBasicPurRequestInfo->requesttype)) echo $ArrRequestTimeType[$ArrBasicPurRequestInfo->requesttype] ?>">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="inputEmail3" class="col-sm-4 control-label">Request Date & Time</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" readonly value="<?php if (!empty($ArrBasicPurRequestInfo->requestdt)) echo date('d-m-Y H:i:s', strtotime($ArrBasicPurRequestInfo->requestdt)); ?>">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="inputEmail3" class="col-sm-4 control-label">CuttOff Date & Time</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" readonly value="<?php if (!empty($ArrBasicPurRequestInfo->cutoffdatetime)) echo date('d-m-Y H:i:s', strtotime($ArrBasicPurRequestInfo->cutoffdatetime)) ?>">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="inputEmail3" class="col-sm-4 control-label">Merchant Note</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" readonly value="<?php if (!empty($ArrBasicPurRequestInfo->merchantnote)) echo $ArrBasicPurRequestInfo->merchantnote ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="inputEmail3" class="col-sm-4 control-label">Authorization Status</label>
                                    <div class="col-sm-8">
                                        <?php
                                        $VarCs = '';
                                        if ($ArrBasicPurRequestInfo->mgmtcurrentstatus == 1) {
                                            $VarCs = 'AUTHORIZATION PENDING';
                                        } elseif ($ArrBasicPurRequestInfo->mgmtcurrentstatus == 2) {
                                            $VarCs = 'AUTHORIZED';
                                        } elseif ($ArrBasicPurRequestInfo->mgmtcurrentstatus == 3) {
                                            $VarCs = 'NOT AUTHORIZED';
                                        } elseif ($ArrBasicPurRequestInfo->mgmtcurrentstatus == 4) {
                                            $VarCs = 'RE REQUEST';
                                        }
                                        ?>
                                        <input type="text" class="form-control" readonly value="<?php echo $VarCs; ?>">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Authorization Type</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" readonly value="<?php if (!empty($ArrBasicPurRequestInfo->approvaltype)) echo $ArrRequestTimeType[$ArrBasicPurRequestInfo->approvaltype]; ?>">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Authorization Date & Time</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" readonly value="<?php if (!empty($ArrBasicPurRequestInfo->authdatetime)) echo date('d-m-Y H:i:s', strtotime($ArrBasicPurRequestInfo->authdatetime)); ?>">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="" class="col-sm-4 control-label">Management Remarks</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" readonly value="<?php if (!empty($ArrBasicPurRequestInfo->mgmtremarks)) echo $ArrBasicPurRequestInfo->mgmtremarks ?>">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="" class="col-sm-4 control-label">Request Status</label>
                                    <div class="col-sm-8">
                                        <?php
                                        if ($ArrBasicPurRequestInfo->deptcurrentstatus == 1) $rs = 'REQUEST PENDING';
                                        elseif ($ArrBasicPurRequestInfo->deptcurrentstatus == 2) $rs = 'ACCEPTED' ;
                                        elseif ($ArrBasicPurRequestInfo->deptcurrentstatus == 3) $rs = 'DECLINED';
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
                                        <input type="text" class="form-control" readonly value="<?php if(!empty($ArrBasicPurRequestInfo->queueno)) echo $ArrBasicPurRequestInfo->queueno ?>">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="" class="col-sm-4 control-label">Queue No. Assigned Date &
                                        Time</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" readonly value="<?php if (!empty($ArrBasicPurRequestInfo->queueno_assigned_date)) echo date('d-m-Y H:i:s', strtotime($ArrBasicPurRequestInfo->queueno_assigned_date)); ?>">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="" class="col-sm-4 control-label">Purchase Dept. Remarks</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" readonly value="<?php if (!empty($ArrBasicPurRequestInfo->deptremarks)) echo $ArrBasicPurRequestInfo->deptremarks ?>">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Current Status</label>
                                    <div class="col-sm-8">
                                        <?php
                                        if ($ArrBasicPurRequestInfo->deptcurrentstatus == 1) $cs = 'REQUEST PENDING';
                                        if ($ArrBasicPurRequestInfo->deptcurrentstatus == 2) $cs = 'ACCEPTED';
                                        if ($ArrBasicPurRequestInfo->deptcurrentstatus == 3) $cs = 'DECLINED';
                                        ?>
                                        <input type="text" class="form-control" readonly value="<?php echo $cs ?>">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Recent Update</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" readonly value="<?php if (!empty($ArrBasicPurRequestInfo->dateupdated)) echo date('d-m-Y H:i:s', strtotime($ArrBasicPurRequestInfo->dateupdated)) ?>">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>

                    <div class="box-body">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label">MERCHANT ATTACHMENTS</label>
                                <div class="col-sm-12"
                                     style="border:2px dotted #A5A5C7; padding: 10px; background-color: #eee">
                                    <ul style="list-style: none;">
                                    <?php
                                    $VarFdr = FCPATH . "uploads".DIRECTORY_SEPARATOR."bompurchaserequest" . DIRECTORY_SEPARATOR.$BomPurReqId;
                                    if (file_exists($VarFdr)) {
                                        if ($dh = opendir($VarFdr)) {
                                            while (($file = readdir($dh)) !== false) {
                                                if (is_file($VarFdr.DIRECTORY_SEPARATOR.$file)) {
                                                    ?>
                                                    <li>
                                                        <div style="padding: 10px 0;">
                                                            <?php echo $file . ' '; ?>&nbsp;<a
                                                                href="<?php echo base_url() . "menquiry/download?enqid=" . urlencode(base64_encode($BomPurReqId)) . "&filename=" . $file."&folder=bompurchaserequest" ?>">
                                                                <i class="fa fa-download fa-lg"
                                                                   aria-hidden="true"></i>
                                                            </a>&nbsp;&nbsp;<a
                                                                href="<?php echo base_url() . "uploads/bompurchaserequest/" . $BomPurReqId . "/" . $file ?>"
                                                                target="_blank">
                                                                <i class="fa fa-file fa-lg"
                                                                   aria-hidden="true"></i>
                                                            </a>
                                                        </div>
                                                    </li>
                                                    <?php
                                                }
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
                        </div>
                    </div>
                    <div class="box-body box-bodyPd2010">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label">PURCHASE DEPARTMENT ATTACHMENTS</label>
                                <div class="col-sm-12"
                                     style="border:2px dotted #A5A5C7; padding: 10px; background-color: #eee">
                                    <ul style="list-style: none;">
                                        <?php
                                        $VarFdr = FCPATH . "uploads".DIRECTORY_SEPARATOR."bompurchaserequest" . DIRECTORY_SEPARATOR.$BomPurReqId.DIRECTORY_SEPARATOR."purchaseindent".DIRECTORY_SEPARATOR.$VarBomPurIndentId;
                                        //echo '<pre>'; print_r($VarFdr); die('die');
                                        if (file_exists($VarFdr)) {
                                            if ($dh = opendir($VarFdr)) {
                                                while (($file = readdir($dh)) !== false) {
                                                    if (is_file($VarFdr.DIRECTORY_SEPARATOR.$file)) {
                                                        ?>
                                                        <li>
                                                            <div style="padding: 10px 0;">
                                                                <?php echo $file . ' '; ?>&nbsp;<a
                                                                    href="<?php echo base_url() . "menquiry/download?enqid=" . urlencode(base64_encode($BomPurReqId)) . "&filename=" . $file."&folder=bompurchaserequest&for=purchaseindent&PiPK=".$VarBomPurIndentId ?>">
                                                                    <i class="fa fa-download fa-lg"
                                                                       aria-hidden="true"></i>
                                                                </a>&nbsp;&nbsp;<a
                                                                    href="<?php echo base_url() . "uploads/bompurchaserequest/" . $BomPurReqId . "/purchaseindent/" . $VarBomPurIndentId."/".$file ?>"
                                                                    target="_blank">
                                                                    <i class="fa fa-file fa-lg"
                                                                       aria-hidden="true"></i>
                                                                </a>
                                                            </div>
                                                        </li>
                                                        <?php
                                                    }
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
                        </div>
                    </div>
                    <div class="box-footer nopadding">
                        <div class="herr" id="ErrfrmPage"></div>
                        <div class="alert alert-success alert-dismissable hide" id="divSuccessBasicInfoMsg"></div>
                        <button class="btn btn-info pull-right" type="button" onclick="return fnSavePurchaseIndent()"> Save Changes </button>
                    </div>

                </div>

                <!-- Password Modal Starts Here -->
                <div class="modal fade" id="BomPiMgmtApprovalModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title" id="myModalLabel">Enter PIN</h4>
                            </div>
                            <div class="modal-body">
                                <form class="form-horizontal col-md-3" method="post" id="frmPinformId" autocomplete="off">
                                    <div id="divOuter">
                                        <div id="divInner">
                                            <input id="frmPin" type="password" maxlength="4"  />
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary" onclick="return fnCheckBomPiMgmtApprovalPin()">Continue</button>
                                <div class="herr pull-left" id="ErrfrmPin"></div>
                            </div>
                        </div>
                    </div>
                </div>

        </section>
    </div><!-- /.content-wrapper -->
    <?php $this->load->view(CNFCOMPANY . 'template/templatefooter'); ?>
    <div class="control-sidebar-bg"></div>
</div><!-- ./wrapper -->
<script src="<?php echo base_url(); ?>assets/js/jexcel4/jsuites.js"></script>
<script src="<?php echo base_url(); ?>assets/js/jexcel4/jexcel.js"></script>
<script src="<?php echo base_url(); ?>assets/plugins/datepicker/bootstrap-datepicker.js"></script>
<script type="text/javascript">
    var GlbBomPurIndentId = '<?php echo $VarBomPurIndentId ?>';
    var GlbRequestId      = '<?php echo $BomPurReqId ?>';
    var GlbOrderId        = '<?php echo $VarOrderId ?>';
    var GlbTaxTypeId      = '<?php echo $ArrBasicInfo->taxtype ?>';
    var GlbBomPurchaseIndentGrid = '<?php echo @$ArrBasicInfo->purchaseindgrid ?>';
    var GlbCurrencynCode  = '<?php echo json_encode(unserialize(ARRCURRENCYLIST)); ?>';

    var GlbFinDeptAdvPaymentPaidGrid = '<?php echo $jsonpaymentpaidgrid ?>';
    var GlbAdvPaymentRequest = '<?php echo $advPaymentRequestJxl ?>';

    console.log(GlbTaxTypeId, 'GlbTaxTypeId');
    // A custom method to SUM all the cells in the current column
    var SUMCOL = function (instance, columnId) {
        var total = 0;
        for (var j = 0; j < instance.options.data.length; j++) {
            if (Number(instance.records[j][columnId - 1].innerHTML)) {
                total += Number(instance.records[j][columnId - 1].innerHTML);
            }
        }
        return total.toFixed(2) ;
    }
    console.log(GlbTaxTypeId,'GlbTaxTypeId');
    if (GlbTaxTypeId == 1) {
        jexcel(document.getElementById('bomPurchaseIndentJxl'), {
            columns: [
                {type: 'text', title: 'Item Description / Blend (%) / Content / Material', width: 300, wordWrap: true, readOnly: true },
                {type: 'text', title: 'Gar. Size', width: 40, wordWrap: true, readOnly: true},
                {type: 'text', title: 'Item Code', width: 100, wordWrap: true, readOnly: true},
                {type: 'text', title: 'Item Color Code', width: 100, wordWrap: true, readOnly: true},
                {type: 'text', title: 'Size / Dim. (W*L*H)', width: 80, wordWrap: true, readOnly: true},
                {type: 'text', title: 'UOM', width: 80, wordWrap: true, readOnly: true},
                {type: 'text', title: 'Quantity', width: 80, wordWrap: true, readOnly: true},
                {type: 'text', title: 'UOM', width: 90, wordWrap: true, readOnly: true},
                {type: 'text', title: 'Unit Rate', width: 40, readOnly: true},
                {type: 'text', title: 'Amount (Rs.)', width: 100, readOnly: true},
                {type: 'text', title: 'SGST (%)', width: 40, readOnly: true},
                {type: 'text', title: 'SGST Value (Rs.)', width: 70, readOnly: true},
                {type: 'text', title: 'CGST (%)', width: 40, readOnly: true},
                {type: 'text', title: 'CGST Value (Rs.)', width: 70, readOnly: true},
                {type: 'text', title: 'Sub Total (Rs.)', width: 100, readOnly: true},
                {type: 'hidden'},
                {type: 'hidden'}
            ],
            footers: [['', '', '', '', '', '','', '', 'Total', '=SUMCOL(TABLE(), COLUMN())', '', '=SUMCOL(TABLE(), COLUMN())', '', '=SUMCOL(TABLE(), COLUMN())', '=SUMCOL(TABLE(), COLUMN())']],
            data: JSON.parse(GlbBomPurchaseIndentGrid),
            allowInsertRow: false
        });
    }
    else if (GlbTaxTypeId == 2) {
        jexcel(document.getElementById('bomPurchaseIndentJxl'), {
            columns: [
                {type: 'text', title: 'Item Description / Blend (%) / Content / Material', width: 150, wordWrap: true, readOnly: true },
                {type: 'text', title: 'Gar. Size', width: 40, wordWrap: true, readOnly: true},
                {type: 'text', title: 'Item Code', width: 120, wordWrap: true, readOnly: true},
                {type: 'text', title: 'Item Color Code', width: 120, wordWrap: true, readOnly: true},
                {type: 'text', title: 'Size / Dim. (W*L*H)', width: 80, wordWrap: true, readOnly: true},
                {type: 'text', title: 'UOM', width: 80, wordWrap: true, readOnly: true},
                {type: 'text', title: 'Quantity', width: 80, wordWrap: true, readOnly: true},
                {type: 'text', title: 'UOM', width: 90, wordWrap: true, readOnly: true},
                {type: 'text', title: 'Unit Rate', width: 40, readOnly: true},
                {type: 'text', title: 'Amount (Rs.)', width: 100, readOnly: true},
                {type: 'text', title: 'IGST (%)', width: 40, readOnly: true},
                {type: 'text', title: 'IGST Value (Rs.)', width: 70, readOnly: true},
                {type: 'text', title: 'Sub Total (Rs.)', width: 100, readOnly: true},
                {type: 'hidden'},
                {type: 'hidden'}
            ],
            footers: [['', '', '', '', '', '', '', '', 'Total', '=SUMCOL(TABLE(), COLUMN())', '', '=SUMCOL(TABLE(), COLUMN())', '=SUMCOL(TABLE(), COLUMN())']],
            data: JSON.parse(GlbBomPurchaseIndentGrid)
        });
    }
    else if (GlbTaxTypeId == 3) {
        jexcel(document.getElementById('bomPurchaseIndentJxl'), {
            columns: [
                {type: 'text', title: 'Item Description / Blend (%) / Content / Material', width: 150, wordWrap: true, readOnly: true },
                {type: 'text', title: 'Gar. Size', width: 40, wordWrap: true, readOnly: true},
                {type: 'text', title: 'Item Code', width: 120, wordWrap: true, readOnly: true},
                {type: 'text', title: 'Item Color Code', width: 120, wordWrap: true, readOnly: true},
                {type: 'text', title: 'Size / Dim. (W*L*H)', width: 80, wordWrap: true, readOnly: true},
                {type: 'text', title: 'UOM', width: 80, wordWrap: true, readOnly: true},
                {type: 'text', title: 'Quantity', width: 80, wordWrap: true, readOnly: true},
                {type: 'text', title: 'UOM', width: 90, wordWrap: true, readOnly: true},
                {type: 'text', title: 'Unit Rate', width: 40, readOnly: true},
                {type: 'text', title: 'Amount (Rs.)', width: 100, readOnly: true},
                {type: 'text', title: 'Duty (%)', width: 40, readOnly: true},
                {type: 'text', title: 'Duty Value (Rs.)', width: 70, readOnly: true},
                {type: 'text', title: 'Sub Total (Rs.)', width: 100, readOnly: true},
                {type: 'hidden'},
                {type: 'hidden'}
            ],
            footers: [['', '', '', '', '', '', '', '', 'Total', '=SUMCOL(TABLE(), COLUMN())', '', '=SUMCOL(TABLE(), COLUMN())', '=SUMCOL(TABLE(), COLUMN())']],
            data: JSON.parse(GlbBomPurchaseIndentGrid)
        });
    }

    function fnSavePurchaseIndent() {
        try {
            var bomPIAdvPaymentApprStatus = $("#bomPIAdvPaymentApprStatus").val();
            MakePostRequest(base_path + 'management/updatePurchaseIndentApprStatus',"rfrom=1&bomPurIndentId="+GlbBomPurIndentId+
                "&apprStatus="+bomPIAdvPaymentApprStatus,"json",fnSavePurchaseIndentRes);
            return false;
        } catch (e) {
            console.log(e.message, 'err');
        }
    }

    function fnSavePurchaseIndentRes(data) {
        console.log(data, ' data');
        if (data.errcode == 1) {
            $("#divSuccessBasicInfoMsg").removeClass('hide');
            $("#divSuccessBasicInfoMsg").text('Saved Successfully');
            $("#bomPIAdvPaymentApprBy").val(data.mgmtInfo[0].contactname);
            //window.location.replace(base_path+'dashboard/bompurchaseindentlist');
        }
    }

    function fnCheckBomPiMgmtApprovalPin() {
        $(".herr").text('');
        try {
            var pw = $("#frmPin").val();
            var ApprStatus = $("#bomPIAdvPaymentApprStatus").val();
            if(jsTrim(pw) == "") {
                $("#ErrfrmPin").text('Enter PIN');
                return false;
            }
            var newJxlData = jxlTbl.getData();
            MakePostRequest(base_path + 'management/BomPIApprovalCheckPin',"rfrom=1&i="+pw+"&apprStatus="+ApprStatus+"&bomPurIndId="+
                GlbBomPurIndentId,'json',function (data) {
                if(data!='') {
                    if(data.errcode == '404') {
                        fnCallSessionExpire();
                        return false;
                    } else if(data.errcode=='-1'){
                        $('#ErrfrmPin').text(data.msg);
                        return false;
                    } else if(data.errcode=='1') {
                        $('#myModal').modal('hide');
                    }
                }
            });
            return false;
        } catch (e) {
            alert(e);
        }
    }

    console.log(GlbFinDeptAdvPaymentPaidGrid,'GlbFinDeptAdvPaymentPaidGrid');
    var GlbAdvPayModeOfPayment = '<?php echo $jsonBomPiModeOfPayment ?>';
    jexcel(document.getElementById('financeDeptPayPaidJxl'), {
        columns:[
            { type:'text',title:'Paid in Favour of', width:300, readOnly: true },
            { type:'dropdown',title:'Mode of Payment', width:150, source: JSON.parse(GlbAdvPayModeOfPayment), readOnly: true },
            { type:'text',title:'Transaction ID / Code', width:150, readOnly: true },
            { type:'text',title:'Cheque No.', width:150, readOnly: true },
            { type:'calendar', title:'Transaction / Cheque Date', width:150, readOnly: true },
            { type:'dropdown',title:'Currency', width:150, source: JSON.parse(GlbCurrencynCode), readOnly: true },
            { type:'text',title:'Advance Paid Amount', width:150, readOnly: true },
            { type:'dropdown',title:'Advance Paid in Full / Part', width:100, source : ["Full","Part"], readOnly: true },
        ],
        data: JSON.parse(GlbFinDeptAdvPaymentPaidGrid),
        minDimensions:[8,1]
    });

    console.log(GlbAdvPaymentRequest,'GlbAdvPaymentRequest');
    if(GlbAdvPaymentRequest != "") {
        //GlbAdvPaymentRequest = JSON.parse(GlbAdvPaymentRequest);
        jexcel(document.getElementById('advPaymentRequestJxl'), {
            columns: [
                {type: 'text', title: 'WIP No.', width: 120},
                {type: 'calendar', title: 'Proforma Date', width: 120},
                {type: 'text', title: 'Proforma No.', width: 120},
                {title: 'Currency', width: 120},
                {type: 'text', title: 'Proforma Value', width: 120},
                {type: 'text', title: 'Advance Amount Payable', width: 120},
                {title: 'Requested Mode of Payment', width: 120},
                {type: 'calendar', title: 'Pay By Date', width: 120}
            ],
            data: JSON.parse(GlbAdvPaymentRequest),
            updateTable:function(instance, cell, col, row, val, label, cellName) {
                if(col == 0) {
                    //$(cell).text(GlbWipNo);
                    //instance.jexcel.options.data[row][col] = GlbWipNo;
                }
            }
        });
    }
    else {
        GlbAdvPaymentRequest = [
            []
        ];
    }

    var GlbVendorId = '<?php echo $ArrBasicInfo->vendorid ?>';
    MakePostRequest(base_path + GlbCompanyFdr+'mpurchaseuser/getVendorsInfo', "rfrom=1&vid=" + GlbVendorId, 'json', fnVendorRes);
    function fnVendorRes(data) {
        let GlbVendorBankJxl = [
            []
        ];
        if(data.vendorBankJxl != "") {
            GlbVendorBankJxl = data.vendorBankJxl;
        }

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

</script>
<?php $this->load->view(CNFCOMPANY . 'template/pagefooter'); ?>