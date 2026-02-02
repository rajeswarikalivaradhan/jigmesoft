<link rel="stylesheet" href="<?php echo base_url();?>assets/css/jexcel/jquery.jexcel.min.css" />
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
        min-width:220px;
    }
    #divInner{
        left: 0;
        position: sticky;
    }
    #divOuter{
        width:190px;
        overflow:hidden
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
<?php $this->load->view(CNFCOMPANY.'template/pageheader'); $ArrLoggedUserInfo = fnGetUserLoggedInfo(1); $VarUserType = $ArrLoggedUserInfo['usertype']; $ArrUserDetails = fnGetUserLoggedInfo(); ?>
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
                    <div class="box box-info">
                        <div class="box-header with-border" style="text-align: center">
                            <h3 class="box-title"><b>PURCAHSE INDENT</b></h3>
                            <div class="box-tools pull-right">
                                <?php
                                $VarTaxType = '';
                                if(!empty($_COOKIE['pi_tt'])) {
                                    $VarTaxType = $_COOKIE['pi_tt'];
                                    if ($VarTaxType == 1) {
                                        echo 'SGST / CGST RATE';
                                    } elseif ($VarTaxType == 2) {
                                        echo 'IGST RATE';
                                    } elseif ($VarTaxType == 3) {
                                        echo 'IMPORT DUTY';
                                    }
                                }
                                else {
                                }
                                ?>
                            </div>
                        </div><!-- /.box-header -->
                        <div class="box-body">
                            <form class="" action="#" name="frmBasicInfo" id="frmBasicInfo" method="post" autocomplete="off">
                                <table class="table">
                                    <tr>
                                        <td>
                                            <table class="table">
                                                <tr><th style="background-color: #f3f3f3" colspan="2">FROM</th>
                                                </tr>
                                                <tr><td>NAME</td><td><?php echo $ArrFromCompanyInfo['contactname'] ?></td></tr>
                                                <tr><td>ADDRESS :</td><td><?php echo $ArrFromCompanyInfo['address'] ?></td></tr>
                                                <tr><td>CONTACT NO :</td><td><?php echo $ArrFromCompanyInfo['mobile'] ?></td></tr>
                                                <tr><td>E-MAIL ID :</td><td><?php echo $ArrFromCompanyInfo['username'] ?></td></tr>
                                                <tr><td>GST No :</td><td><?php echo $ArrFromCompanyInfo['username'] ?></td></tr>
                                                <tr><td>IE CODE :</td><td><?php echo $ArrFromCompanyInfo['username'] ?></td></tr>
                                            </table>
                                        </td>
                                        <td>
                                            <table class="table">
                                                <tr><th style="background-color: #f3f3f3" colspan="2">TO</th></tr>
                                                <tr>
                                                    <td style="padding-top: 15px">NAME</td>
                                                    <td>
                                                        <select class="form-control" onchange="fnSelToVendorname(this.value)">
                                                            <option value="">Choose Vendor</option>
                                                            <?php
                                                            foreach ($ArrObjToVendorname as $VarVname) {
                                                                ?>
                                                                <option value="<?php echo $VarVname->id ?>" <?php if(@$ArrBasicInfo->vendorid == $VarVname->id) echo 'selected' ?>>
                                                                    <?php echo $VarVname->vendorname ?></option>
                                                                <?php
                                                            }
                                                            ?>
                                                        </select>
                                                    </td>
                                                </tr>
                                                <tr><td>ADDRESS</td><td id="toaddress"><?php echo @$ArrBasicInfo->address ?></td></tr>
                                                <tr><td>CONTACT NO:</td><td id="contactno"><?php echo @$ArrBasicInfo->phone ?></td></tr>
                                                <tr><td>E-MAIL ID:</td><td id="emailid"><?php echo @$ArrBasicInfo->emailid ?></td></tr>
                                                <tr><td>GST NO:</td><td id="gstno"><?php echo @$ArrBasicInfo->gstno ?></td></tr>
                                                <tr><td>IE CODE:</td><td id="iecode"><?php echo @$ArrBasicInfo->iecode ?></td></tr>

                                            </table>
                                        </td>
                                        <td>
                                            <table class="table">
                                                <tr><th style="background-color: #f3f3f3; text-align: center" colspan="2">PURCHASE REFERENCE</th></tr>
                                                <tr><td><b>P.I. REF. NO:</b></td><td><?php echo $PiNo ?></td></tr>
                                                <tr><td>DATE & TIME:</td>
                                                    <td>
                                                        <?php if(empty($ArrBasicInfo->datecreated))
                                                            echo date('d-m-Y');
                                                        else echo date('d-m-Y',strtotime($ArrBasicInfo->datecreated)) ?>
                                                    </td></tr>
                                                <tr><td>AGREED SUPPLY DATE:</td>
                                                    <td>
                                                        <div class="input-group">
                                                        <input type='text' class="form-control" placeholder="Agreed Supply Date" id="agreedsupplydate"/>
                                                        <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                                        </div>
                                                    </td></tr>
                                                <tr><td>SUPPLY CUTOFF DATE:</td>
                                                    <td><?php if(empty($ArrBasicInfo->datecreated)) echo date('d-m-Y');
                                                    else echo date('d-m-Y',strtotime($ArrBasicInfo->datecreated)) ?>
                                                    </td></tr>
                                                <tr><td>PAYMENT TERMS:</td>
                                                    <td>
                                                        <input type="text" class="form-control" id="frmBasicPaymentTerms">
                                                    </td></tr>
                                                <tr><td><b>INTERNAL REFERENCE</b></td></tr>
                                                <tr>
                                                    <td>WIP NO:</td>
                                                    <td id="wiprefno"><?php if(!empty($isiorcode)) echo $isiorcode; else echo $ArrBasicInfo->isriorcode ?></td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                </table>
                            </form>
                        </div>
                        <div class="box-body">
                            <div class="row">
                            </div>
                            <small>Herewith, we place an order for the following items:</small>
                            <div id="bomPurchaseIndentInvoice"></div>
                            <form class="form-horizontal" action="#" name="frmBasicInfo" id="frmBasicInfo" method="post" autocomplete="off">
                                <div class="col-md-12">
                                    <?php
                                    if($VarTaxType == 3) {
                                        ?>
                                        <label class="" style="position:relative; left: 778px">Total : </label>
                                        <div class="" style="position:relative; left: 783px; display: inline-block">
                                            <select class="" id="frmBasicCurrency">
                                                <option value="">Currency</option>
                                                <?php
                                                foreach ($ArrCurrencyCode as $KeyId => $item) {
                                                    ?>
                                                    <option value="<?php echo $KeyId ?>"><?php echo $item ?></option>
                                                    <?php
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <input type="text" style="position: relative; left: 784px; width: 100px" id="frmBasicTotal" value="<?php /*if(!empty($ArrBasicInfo->total)) echo $ArrBasicInfo->total */?>">
                                        <input type="text" style="position:relative; left: 831px; width: 80px; " id="frmBasicDutyvalueTotal" value="<?php /*if(!empty($ArrBasicInfo->total)) echo $ArrBasicInfo->total */?>">
                                        <input type="text" style="position:relative; left: 831px; width: 100px; " id="frmBasicSubtotalTotal" value="<?php /*if(!empty($ArrBasicInfo->total)) echo $ArrBasicInfo->total */?>">
                                        <?php
                                    }
                                    elseif($VarTaxType == 1) {
                                        ?>
                                        <label class="" style="position:relative; left: 670px">Total : </label>
                                        <div class="" style="position:relative; left: 685px; display: inline-block">&#8377;</div>
                                        <input type="hidden" id="frmBasicCurrency" value="21">
                                        <input type="text" style="position: relative; left: 713px; width: 100px" id="frmBasicTotal" value="<?php echo 'sd'/*if(!empty($ArrBasicInfo->total)) echo $ArrBasicInfo->total */?>">
                                        <input type="text" style="position:relative; left: 760px; width: 90px; " id="frmBasicSgstTotal" value="<?php /*if(!empty($ArrBasicInfo->total)) echo $ArrBasicInfo->total */?>">
                                        <input type="text" style="position:relative; left: 808px; width: 90px; " id="frmBasicCgstTotal" value="<?php /*if(!empty($ArrBasicInfo->total)) echo $ArrBasicInfo->total */?>">
                                        <input type="text" style="position:relative; left: 810px; width: 100px; " id="frmBasicSubtotalTotal" value="<?php /*if(!empty($ArrBasicInfo->total)) echo $ArrBasicInfo->total */?>">
                                        <?php
                                    }
                                    elseif($VarTaxType == 2) {
                                        ?>
                                        <label class="" style="position:relative; left: 818px">Total : </label>
                                        <div class="" style="position:relative; left: 825px; display: inline-block">&#8377;</div>
                                        <input type="hidden" id="frmBasicCurrency" value="21">
                                        <input type="text" style="position: relative; left: 835px; width: 100px" id="frmBasicTotal" value="<?php /*if(!empty($ArrBasicInfo->total)) echo $ArrBasicInfo->total */?>">
                                        <input type="text" style="position:relative; left: 891px; width: 90px; " id="frmBasicIgstTotal" value="<?php /*if(!empty($ArrBasicInfo->total)) echo $ArrBasicInfo->total */?>">
                                        <input type="text" style="position:relative; left: 891px; width: 90px; " id="frmBasicSubtotalTotal" value="<?php /*if(!empty($ArrBasicInfo->total)) echo $ArrBasicInfo->total */?>">
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
                                        <input type="text" id="frmAmountInWords" class="form-control">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-2" style="border: 1px solid #f4f4f4">
                                        <label><b>Note:</b></label>
                                        <p style="font-size: 11px; display: inline">If goods are supplied beyond cutoff date, it is up to the discretion of the company to accept or reject the goods. Terms and conditions as agreed upon.</p>
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
                                        <table class="table">
                                            <thead><tr><th style="background-color: #f3f3f3" colspan="2">PURCHASER CONTACT DETAILS</th></tr></thead>
                                            <tbody><tr>
                                                <th>NAME:</th>
                                                <td><input class="form-control" id="frmBasicPurchaserName" type="text"></td>
                                            </tr>
                                            <tr>
                                                <th>MOBILE:</th>
                                                <td><input class="form-control" id="frmBasicPurchaserMobile" type="text"></td>
                                            </tr>
                                            <tr>
                                                <th>EMAIL:</th>
                                                <td><input class="form-control" id="frmBasicPurchaserEmail" type="text"></td>
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
                                                <td><input class="form-control" id="frmBasicVendorName" type="text"></td>
                                            </tr>
                                            <tr>
                                                <th>MOBILE:</th>
                                                <td><input class="form-control" id="frmBasicVendorMobile" type="text"></td>
                                            </tr>
                                            <tr>
                                                <th>EMAIL:</th>
                                                <td><input class="form-control" id="frmBasicVendorEmail" type="text"></td>
                                            </tr>
                                            </tbody></table>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <label class="control-label">Prepared by:</label>
                                    <br/>
                                    <?php echo $VarThisUserName ?>
                                    <br/><br/>
                                    <label class="control-label">Name & Signature</label>
                                    <br/>

                                </div>
                                <div class="col-md-2">
                                    <label class="control-label">Authorized by:</label><br/>
                                    <?php echo $VarAuthorizedBy ?>
                                    <br/><br/>
                                    <label class="control-label">Name & Signature</label>
                                    <br/>
                                    <?php echo '' ?>
                                </div>
                                <div class="col-md-2">
                                    <label class="control-label">Approved by:</label><br/>
                                    <?php echo $VarApprovedBy ?>
                                    <br/><br/>
                                    <label class="control-label">Name & Signature</label>
                                    <br/>
                                    <?php echo '' ?>
                                </div>
                                <div class="col-md-2">
                                    <label class="control-label">For</label><br/>
                                    <?php echo 'Mgmt1' ?>
                                    <br/><br/>
                                    <label class="control-label">Authorized Signatory</label>
                                    <br/>
                                    <?php echo '' ?>
                                </div>
                            </div>
                        <a href="<?php echo base_url('purchaseuser/bompurchaseinvoicepdf/'.$VarId) ?>">Save as pdf</a>
                        </div>
                        <?php if(empty($pireferenceno)) { ?>
                            <div class="box-footer nopadding">
                                <div class="herr" id="ErrfrmPage"></div>
                                <button class="btn btn-info pull-right addrights" type="submit" onclick="return fnSavePurchaseIndent()">Save</button>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </section>
    </div><!-- /.content-wrapper -->
    <?php $this->load->view(CNFCOMPANY.'template/templatefooter');?>
    <div class="control-sidebar-bg"></div>
</div><!-- ./wrapper -->
<script src="<?php echo base_url();?>assets/js/ajax.js"></script>
<script src="<?php echo base_url();?>assets/js/jexcel/jquery.jexcel.js"></script>
<script src="<?php echo base_url();?>assets/js/jexcel/numeral.min.js"></script>
<script src="<?php echo base_url(); ?>assets/plugins/datepicker/bootstrap-datepicker.js"></script>
<script>
    var GlbId = '<?php echo @$VarId ?>';
    var GlbOrderId = '<?php echo @$VarOrderId ?>';
    var GlbTaxType = '<?php echo @$VarTaxType ?>';
    var SavedInvoiceGrid = '<?php echo @$SavedInvoiceGrid ?>';
    var GlbDynamicTblName = '<?php echo @$DynamicTblName ?>';
    var GlbPiNo = '<?php echo @$PiNoAlone ?>';
    var GlbPurchaseToId = '', GlbValidateVendorSelection = 0, GlbBomPurchaseGrid = '', GlbForSavingIssuePIDynamicTbl = '';
    var GlbAmounttotal = 0; var GlbSgsttotal = 0; var GlbCgsttotal = 0; var GlbIgstTotal = 0; var GlbDutyvaluetotal = 0; var GlbSubtotalTol = 0;
    //var url = $(location).attr('href'); var lasturlpart = url.substr(url.lastIndexOf('/')+1);
    $('#agreedsupplydate').datepicker({
        format: 'dd-mm-yyyy',
        todayHighlight: true,
        autoclose: true
    });
    if (typeof(Storage) !== "undefined") {
        GlbBomPurchaseGrid = localStorage.getItem("forInvoiceGridLs");
        GlbForSavingIssuePIDynamicTbl = localStorage.getItem("forSavingIssuePIDynamicTbl");
        // Code for localStorage/sessionStorage.
    } else {
        alert('Sorry! No Web Storage support..');
    }
    if(SavedInvoiceGrid != '') {
        GlbBomPurchaseGrid = SavedInvoiceGrid;
    }
    function fnSelToVendorname(thisvalue) {
        GlbPurchaseToId = thisvalue;
        MakeAsynPostRequest(base_path+'purchaseuser/getVendorsInfo',"rfrom=1&vid="+thisvalue,'json',fnSelToVendornameRes);
    }
    function fnSelToVendornameRes(data) {
        if(data.errcode=='1') {
            if (data.re != '') {
                GlbValidateVendorSelection = 1;
                $("#toaddress").text(data.re.address);
                $("#contactno").text(data.re.phone);
                $("#emailid").text(data.re.emailid);
                $("#gstno").text(data.re.gstno);
                $("#iecode").text(data.re.iecode);
            }
            else {
                alert('err');
            }
        }
    }
    if(GlbTaxType == 1) {
        $("#bomPurchaseIndentInvoice").jexcel({
            colHeaders: ['Item Description / (%) Blend /<br/>Content / Material', 'Gar.<br/>','Item Code', 'Item Color<br/>Code', 'Size / Dim.<br/>(W*L*H)',
                'Unit of<br/>Measure', 'Quantity','Unit<br/>Rate', 'Amount', 'SGST<br/>(%)', 'SGST<br/>Value', 'CGST<br/>(%)', 'CGST<br/>Value', 'Sub Total'],
            colWidths: [300, 50,80, 80, 80, 70,70,50, 100, 50, 90, 50, 90, 70],
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
                {type: 'text'},
                {type: 'text', readOnly: true},
                {type: 'text'},
                {type: 'text', readOnly: true},
                {type: 'text', readOnly: true},
            ],
            data: GlbBomPurchaseGrid
        });
        $("#bomPurchaseIndentInvoice").jexcel('updateSettings', {
            table: function (instance, cell, col, row, val, id) {
                if(col == 8) {
                    amount = Number($(cell).text());
                }
                if(col == 9) {
                    sgstpercent = Number($(cell).text());
                }
                if(col == 10) {
                    sgst_division = sgstpercent / 100;
                    s_gstvalue = Number(sgst_division) * Number(amount);
                    $(cell).text(s_gstvalue.toFixed(2));
                }
                if(col == 11) {
                    c_gstpercent = Number($(cell).text());
                }
                if(col == 12) {
                    cgst_division = c_gstpercent / 100;
                    c_gstvalue = Number(cgst_division) * Number(amount);
                    $(cell).text(c_gstvalue.toFixed(2));
                }
                if(col == 13) {
                    subtotal = s_gstvalue + c_gstvalue + Number(amount);
                    $(cell).text(subtotal.toFixed(2));
                }
                GlbAmounttotal = 0; GlbSgsttotal = 0; GlbCgsttotal = 0; GlbSubtotalTol = 0;
                var amounttotalCol = $("#bomPurchaseIndentInvoice").jexcel('getColumnData',8);
                for (var i = 0; i < amounttotalCol.length; i++) {
                    GlbAmounttotal += Number(amounttotalCol[i]);
                }
                var SgsttotalCol = $("#bomPurchaseIndentInvoice").jexcel('getColumnData',10);
                for (var i = 0; i < SgsttotalCol.length; i++) {
                    GlbSgsttotal += Number(SgsttotalCol[i]);
                }
                var CgsttotalCol = $("#bomPurchaseIndentInvoice").jexcel('getColumnData',12);
                for (var i = 0; i < CgsttotalCol.length; i++) {
                    GlbCgsttotal += Number(CgsttotalCol[i]);
                }
                var SubtotalCol = $("#bomPurchaseIndentInvoice").jexcel('getColumnData',13);
                for (var i = 0; i < SubtotalCol.length; i++) {
                    GlbSubtotalTol += Number(SubtotalCol[i]);
                }
                $("#frmBasicTotal").val(GlbAmounttotal.toFixed(2));
                $("#frmBasicSgstTotal").val(GlbSgsttotal.toFixed(2));
                $("#frmBasicCgstTotal").val(GlbCgsttotal.toFixed(2));
                $("#frmBasicSubtotalTotal").val(GlbSubtotalTol.toFixed(2));
            }
        });
    }
    else if(GlbTaxType == 2) {
        $("#bomPurchaseIndentInvoice").jexcel({
            colHeaders: ['Item Description / (%) Blend /<br/>Content / Material', 'Gar.<br/>Size','Item Code', 'Item Color<br/>Code', 'Size / Dim.<br/>(W*L*H)', 'Unit of<br/>Measure',
                'Quantity','Unit<br/>Rate','Amount', 'IGST<br/>(%)', 'IGST value', 'Sub Total'],
            colWidths: [380, 50,90, 90, 80, 80, 70, 60, 100, 60, 90, 70],
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
                {type: 'text'},
                {type: 'text', readOnly: true},
                {type: 'text', readOnly: true},
            ],
            data: GlbBomPurchaseGrid
        });
        $("#bomPurchaseIndentInvoice").jexcel('updateSettings', {
            table: function (instance, cell, col, row, val, id) {
                if (col == 8) {
                    amount = Number($(cell).text());
                }
                if(col == 9) {
                    i_gstpercent = Number($(cell).text());
                }
                if(col == 10) {
                    igst_division = i_gstpercent / 100;
                    i_gstvalue = Number(igst_division) * Number(amount);
                    $(cell).text(i_gstvalue.toFixed(2));
                }
                if(col == 11) {
                    subtotal = amount + i_gstvalue;
                    $(cell).text(subtotal.toFixed(2));
                }
                GlbAmounttotal = 0; GlbIgstTotal = 0; GlbSubtotalTol = 0;
                var totalamount = $("#bomPurchaseIndentInvoice").jexcel('getColumnData',8);
                var igsttotal = $("#bomPurchaseIndentInvoice").jexcel('getColumnData',10);
                for (var i = 0; i < totalamount.length; i++) {
                    GlbAmounttotal += Number(totalamount[i]);
                }
                for (var ii = 0; ii < igsttotal.length; ii++) {
                    GlbIgstTotal += Number(igsttotal[ii]);
                }
                var subtotalBox = $("#bomPurchaseIndentInvoice").jexcel('getColumnData',11);
                for (var i = 0; i < subtotalBox.length; i++) {
                    GlbSubtotalTol += Number(subtotalBox[i]);
                }
                $("#frmBasicTotal").val(GlbAmounttotal.toFixed(2));
                $("#frmBasicIgstTotal").val(GlbIgstTotal.toFixed(2));
                $("#frmBasicSubtotalTotal").val(GlbSubtotalTol.toFixed(2));
            }
        });
    }
    else if(GlbTaxType == 3) {
        $("#bomPurchaseIndentInvoice").jexcel({
            colHeaders: ['Item Description / (%) Blend /<br/>Content / Material', 'Gar.<br/>Size','Item Code', 'Item Color<br/>Code', 'Size / Dim.<br/>(W*L*H)','Unit of<br/>Measure',
                'Quantity', 'Unit<br/>Rate', 'Amount', 'Duty<br/>(%)', 'Duty<br/>value', 'Sub Total'],
            colWidths: [380, 50,90, 90, 90, 80, 80, 60, 100, 50, 80, 70],
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
                {type: 'text'},
                {type: 'text'},
                {type: 'text'}
            ],
            data: GlbBomPurchaseGrid
        });
        $("#bomPurchaseIndentInvoice").jexcel('updateSettings',{
            table: function (instance, cell, col, row, val, idc) {
                if (col == 8) {
                    amount = Number($(cell).text());
                }
                if(col == 9) {
                    duty_percent = Number($(cell).text());
                }
                if(col == 10) {
                    duty_division = duty_percent / 100;
                    duty_value = Number(duty_division) * Number(amount);
                    $(cell).text(duty_value.toFixed(2));
                }
                if(col == 11) {
                    subtotal = amount + duty_value;
                    $(cell).text(subtotal.toFixed(2));
                }
                GlbAmounttotal = 0; GlbDutyvaluetotal = 0; GlbSubtotalTol = 0;
                var amounttotalCol = $("#bomPurchaseIndentInvoice").jexcel('getColumnData',8);
                for (var i = 0; i < amounttotalCol.length; i++) {
                    GlbAmounttotal += Number(amounttotalCol[i]);
                }
                var DutyvalueCol = $("#bomPurchaseIndentInvoice").jexcel('getColumnData',10);
                for (var j = 0; j < DutyvalueCol.length; j++) {
                    GlbDutyvaluetotal += Number(DutyvalueCol[j]);
                }
                var SubtotalCol = $("#bomPurchaseIndentInvoice").jexcel('getColumnData',11);
                for (var m = 0; m < SubtotalCol.length; m++) {
                    GlbSubtotalTol += Number(SubtotalCol[m]);
                }
                $("#frmBasicTotal").val(GlbAmounttotal.toFixed(2));
                $("#frmBasicDutyvalueTotal").val(GlbDutyvaluetotal.toFixed(2));
                $("#frmBasicSubtotalTotal").val(GlbSubtotalTol.toFixed(2));
            }
        });
    }
    function fnSavePurchaseIndent() {
        var agreedsupplydate = $("#agreedsupplydate").val();
        var paymentterms = $("#frmBasicPaymentTerms").val();
        var currency = $("#frmBasicCurrency").val();
        var AmountInWords = $("#frmAmountInWords").val();
        var Remarks = $("#frmRemarks").val();
        var AmountTotal = $("#frmBasicTotal").val();
        var SgstTotal = $("#frmBasicSgstTotal").val();
        var PurchaserName = $("#frmBasicPurchaserName").val(); var PurchaserMobile = $("#frmBasicPurchaserMobile").val(); var PurchaserEmail = $("#frmBasicPurchaserEmail").val();
        var VendorName = $("#frmBasicVendorName").val(); var VendorMobile = $("#frmBasicVendorMobile").val(); var VendorEmail = $("#frmBasicVendorEmail").val();
        $('.herr').text('');
        if(GlbValidateVendorSelection==0) {
            $("#ErrfrmPage").text('Choose a Vendor');
            return false;
        }
        if(paymentterms=='') {
            $("#ErrfrmPage").text('Enter Payment terms');
            return false;
        }
        if(agreedsupplydate=='') {
            $("#ErrfrmPage").text('Enter Supply date');
            return false;
        }
        if(currency=='') {
            $("#ErrfrmPage").text('Choose a currency');
            return false;
        }
        var bomPurchaseIndentInvoice = $("#bomPurchaseIndentInvoice").jexcel('getData');
        MakeAsynPostRequest(base_path+'purchaseuser/updatePurchaseIndentInvoice',"rfrom=1&id="+GlbId+"&oid="+GlbOrderId+"&purchaseto="+GlbPurchaseToId+"&supplydate="+
            agreedsupplydate+"&paymentterms="+paymentterms+"&amountinwords="+AmountInWords+"&remarks="+Remarks+"&taxtype="+GlbTaxType+"&curr="+currency+"&amounttotal="+
            AmountTotal+"&sgsttotal="+GlbSgsttotal+"&cgsttotal="+GlbCgsttotal+"&igsttotal="+GlbIgstTotal+"&dutytotal="+GlbDutyvaluetotal+"&subtotal_total="+
            GlbSubtotalTol+"&pname="+PurchaserName+"&pmobile="+PurchaserMobile+"&pemail="+PurchaserEmail+"&vname="+VendorName+"&vmobile="+
            VendorMobile+"&vemail="+VendorEmail+"&bompurindinvoicegrid="+JSON.stringify(bomPurchaseIndentInvoice)+"&savingIssuePIDynamicTbl="+
            GlbForSavingIssuePIDynamicTbl+"&pino="+GlbPiNo+"&tblname="+GlbDynamicTblName,'json',fnSavePurchaseIndentRes);
    }
    function fnSavePurchaseIndentRes(data) {
        console.log(data,'data');
        if(data.errcode == '1') {
            fnRedirectPageTimeOut(base_path+'dashboard/bompurchaseindentlist');
        }
    }
</script>
<?php $this->load->view(CNFCOMPANY.'template/pagefooter');?>