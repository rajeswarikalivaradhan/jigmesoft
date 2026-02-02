<?php $this->load->view(CNFCOMPANY . 'template/pageheader');
$ArrLoggedUserInfo = fnGetUserLoggedInfo(1);
$ArrUserDetails = fnGetUserLoggedInfo(); ?>
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/jexcel4/jsuites.css"/>
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/jexcel4/jexcel.css"/>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/plugins/datepicker/datepicker3.css">
    <style type="text/css">
        #frmPin {
            padding-left: 15px;
            letter-spacing: 42px;
            border: 0;
            background-image: linear-gradient(to left, black 70%, rgba(255, 255, 255, 0) 0%);
            background-position: bottom;
            background-size: 50px 1px;
            background-repeat: repeat-x;
            background-position-x: 35px;
            width: 220px;
            min-width: 220px;
        }

        #divInner {
            left: 0;
            position: sticky;
        }

        #divOuter {
            width: 190px;
            overflow: hidden
        }

        td div {
            font-family: Verdana, Geneva, sans-serif;
            font-size: 12px;
            line-height: 15px;
            /*padding: 5px 2px;*/
        }

        td {
            font-family: Verdana, Geneva, sans-serif;
            align: top;
        }

        table, .control-label {
            margin-bottom: 0px !important;
            font-size: 12px;
        }

        .form-control {
            height: 25px;
            padding: 3px 2px !important;
            font-size: 12px;
        }

        .mainheading {
            background-color: #bffff9;
        }

        .secondheading {
            background-color: #e3f9f7;
            height: 27px;
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
                                                        //echo '<pre>'; print_r($VendorInfo); die('die');
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
                                                    <?php
                                                    //echo '<pre>'; print_r($ArrBasicInfo); die('die');
                                                    if (empty($ArrBasicInfo->datecreated))
                                                        echo date('d-m-Y H:i:s');
                                                    else echo date('d-m-Y H:i:s', strtotime($ArrBasicInfo->datecreated)) ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>AGREED SUPPLY DATE:</td>
                                                <td>

                                                        <input type="text" class="form-control" readonly
                                                               value="<?php echo ($ArrBasicInfo->agreedsupplydate == '00-00-0000 00:00:00') ? '' : $ArrBasicInfo->agreedsupplydate ?>" id="agreedsupplydate">

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
                        <div id="bomPurchaseIndentJxl"></div>
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
                                <?php echo $PurchaseDeptUserInfo->contactname; ?>
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
                        <div class="box-footer">
                            <!--<button class="btn btn-info pull-right" type="button" onclick="return fnBomPiItemizedPage()">Prepare Itemized Page</button>-->
                        </div>
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
                                        <span class="form-control" id="bomPIAdvPaymentApprStatus">
                                            <?php
                                            echo $PurchaseDeptUserInfo->contactname;
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
                                            $ArrStatus = unserialize(ORDERENQUIRYSTATUS);
                                            if(!empty($ArrBasicInfo->approvedstatus))
                                                echo $ArrStatus[$ArrBasicInfo->approvedstatus];
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
                    <form name="frmAdvPaymentFinance" id="frmMerchantBomPurReqDetails" class="form-horizontal">
                        <div class="box-body" id="merchantBomPurReqDetails">
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
                        <div class="form-group">
                            <div class="col-md-12">
                                <label class="control-label">Merchant Attachments</label>
                            </div>
                            <label class="col-sm-12 control-label"> <a
                                        href="//docs.google.com/gview?url=http://www.picssel.com/demos/downloads/Fancybox.doc&embedded=true"
                                        target="_blank" class="word">Document1.doc</a> </label>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-5"
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
                                                                    href="<?php echo base_url() . "dashbaord/downloadFileFromUploads?id=" . $BomPurReqId . "&filename=" . $file ?>">
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
                        <div class="form-group">
                            <div class="col-md-12">
                                <label class="control-label">Purchase Dept. Attachments</label>
                            </div>
                            <label class="col-sm-12 control-label"> <a
                                        href="//docs.google.com/gview?url=http://www.picssel.com/demos/downloads/Fancybox.doc&embedded=true"
                                        target="_blank" class="word">Document1.doc</a> </label>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-5"
                                 style="border:2px dotted #A5A5C7; padding: 10px; background-color: #eee">
                                <ul style="list-style: none;">
                                    <?php
                                    $VarFdr = FCPATH . "uploads".DIRECTORY_SEPARATOR."bompurchaserequest" . DIRECTORY_SEPARATOR.$BomPurReqId.DIRECTORY_SEPARATOR."purchaseindent".DIRECTORY_SEPARATOR.$VarBomPurIndentId;
                                    if (file_exists($VarFdr)) {
                                        //echo '<pre>'; print_r($VarFdr); die('die');
                                        if ($dh = opendir($VarFdr)) {
                                            while (($file = readdir($dh)) !== false) {
                                                echo '<pre>'; print_r($file); die('die');
                                                    if(is_file($VarFdr.DIRECTORY_SEPARATOR.$file)) {
                                                        ?>
                                                        <li>
                                                            <div style="padding: 10px 0;">
                                                                <?php echo $file . ' '; ?>&nbsp;<a
                                                                        href="<?php echo base_url() . "menquiry/download?enqid=" . $BomPurReqId . "&filename=" . $file."&folder=bompurchaserequest&for=purchaseindent&PiPK=".$VarBomPurIndentId ?>">
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
                    </div>
                </div>
                <div class="box-footer nopadding">
                    <div class="herr" id="ErrfrmPage"></div>
                    <button class="btn btn-info pull-right" type="button" onclick="return fnSavePurchaseIndent()"> Save Changes </button>
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
<script src="<?php echo base_url(); ?>assets/js/ajax.js"></script>
<script src="<?php echo base_url(); ?>assets/js/jexcel4/jsuites.js"></script>
<script src="<?php echo base_url(); ?>assets/js/jexcel4/jexcel.js"></script>
<script src="<?php echo base_url(); ?>assets/plugins/datepicker/bootstrap-datepicker.js"></script>
<script type="text/javascript">
    var GlbBomPurIndentId = '<?php echo $VarBomPurIndentId ?>';
    var GlbRequestId = '<?php echo $BomPurReqId ?>';
    var GlbOrderId = '<?php echo $VarOrderId ?>';
    var GlbTaxTypeId = '<?php echo $ArrBasicInfo->taxtype ?>';
    var GlbCurrencynCode = '<?php echo json_encode(unserialize(ARRCURRENCYLIST)); ?>';
    var GlbForSavingIssuePIDynamicTbl = 0;
    var GlbBomPurchaseGrid = 0;
    var GlbPurchaseToId = 0;
    var GlbValidateVendorSelection = '';
    var GlbFinDeptAdvPaymentPaidGrid = '<?php echo $jsonpaymentpaidgrid ?>';

    GlbBomPurchaseGrid = '<?php echo $ArrBasicInfo->purchaseindgrid ?>';
    var bomPIJxl = 0; var GlbFinanceDeptPayPaidJxl = 0;
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
    console.log(JSON.parse(GlbBomPurchaseGrid),'GlbBomPurchaseGrid');

    if (GlbTaxTypeId == 1) {
        console.log(GlbBomPurchaseGrid);
        bomPIJxl = jexcel(document.getElementById('bomPurchaseIndentJxl'), {
            columns: [
                {type: 'text', title: 'Item Description / (%)Blend / Content / Material', width: 350, wordWrap: true, readOnly: true },
                {type: 'text', title: 'Gar. Size', width: 40, wordWrap: true, readOnly: true},
                {type: 'text', title: 'Item Code', width: 100, wordWrap: true, readOnly: true},
                {type: 'text', title: 'Item Color Code', width: 100, wordWrap: true, readOnly: true},
                {type: 'text', title: 'Size / Dim. (W*L*H)', width: 80, wordWrap: true, readOnly: true},
                {type: 'text', title: 'UOM', width: 80, wordWrap: true, readOnly: true},
                {type: 'text', title: 'Quantity', width: 90, wordWrap: true, readOnly: true},
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
            footers: [['', '', '', '', '', '', '', 'Total', '=SUMCOL(TABLE(), COLUMN())', '', '=SUMCOL(TABLE(), COLUMN())', '', '=SUMCOL(TABLE(), COLUMN())', '=SUMCOL(TABLE(), COLUMN())']],
            data: JSON.parse(GlbBomPurchaseGrid),
            allowInsertRow: false
        });
    }
    else if (GlbTaxTypeId == 2) {
        bomPIJxl = jexcel(document.getElementById('bomPurchaseIndentJxl'), {
            columns: [
                {type: 'text', title: 'Item Description / (%)Blend / Content / Material', width: 350, wordWrap: true, readOnly: true },
                {type: 'text', title: 'Gar. Size', width: 40, wordWrap: true, readOnly: true},
                {type: 'text', title: 'Item Code', width: 120, wordWrap: true, readOnly: true},
                {type: 'text', title: 'Item Color Code', width: 120, wordWrap: true, readOnly: true},
                {type: 'text', title: 'Size / Dim. (W*L*H)', width: 80, wordWrap: true, readOnly: true},
                {type: 'text', title: 'UOM', width: 80, wordWrap: true, readOnly: true},
                {type: 'text', title: 'Quantity', width: 90, wordWrap: true, readOnly: true},
                {type: 'text', title: 'Unit Rate', width: 40, readOnly: true},
                {type: 'text', title: 'Amount (Rs.)', width: 100, readOnly: true},
                {type: 'text', title: 'IGST (%)', width: 40, readOnly: true},
                {type: 'text', title: 'IGST Value (Rs.)', width: 70, readOnly: true},
                {type: 'text', title: 'Sub Total (Rs.)', width: 100, readOnly: true},
                {type: 'hidden'},
                {type: 'hidden'}
            ],
            footers: [['', '', '', '', '', '', '', 'Total', '=SUMCOL(TABLE(), COLUMN())', '', '=SUMCOL(TABLE(), COLUMN())', '=SUMCOL(TABLE(), COLUMN())']],
            data: JSON.parse(GlbBomPurchaseGrid)
        });
    }
    else if (GlbTaxTypeId == 3) {
        bomPIJxl = jexcel(document.getElementById('bomPurchaseIndentJxl'), {
            columns: [
                {type: 'text', title: 'Item Description / (%)Blend / Content / Material', width: 350, wordWrap: true, readOnly: true },
                {type: 'text', title: 'Gar. Size', width: 40, wordWrap: true, readOnly: true},
                {type: 'text', title: 'Item Code', width: 120, wordWrap: true, readOnly: true},
                {type: 'text', title: 'Item Color Code', width: 120, wordWrap: true, readOnly: true},
                {type: 'text', title: 'Size / Dim. (W*L*H)', width: 80, wordWrap: true, readOnly: true},
                {type: 'text', title: 'UOM', width: 80, wordWrap: true, readOnly: true},
                {type: 'text', title: 'Quantity', width: 90, wordWrap: true, readOnly: true},
                {type: 'text', title: 'Unit Rate', width: 40, readOnly: true},
                {type: 'text', title: 'Amount (Rs.)', width: 100, readOnly: true},
                {type: 'text', title: 'Duty (%)', width: 40, readOnly: true},
                {type: 'text', title: 'Duty Value (Rs.)', width: 70, readOnly: true},
                {type: 'text', title: 'Sub Total (Rs.)', width: 100, readOnly: true},
                {type: 'hidden'},
                {type: 'hidden'}
            ],
            footers: [['', '', '', '', '', '', '', 'Total', '=SUMCOL(TABLE(), COLUMN())', '', '=SUMCOL(TABLE(), COLUMN())', '=SUMCOL(TABLE(), COLUMN())']],
            data: JSON.parse(GlbBomPurchaseGrid)
        });
    }
    
    function fnBomPiItemizedPage() {

    }

</script>
<?php $this->load->view(CNFCOMPANY . 'template/pagefooter'); ?>