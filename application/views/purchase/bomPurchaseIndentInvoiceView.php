<link rel="stylesheet" href="<?php echo base_url();?>assets/css/jexcel/jquery.jexcel.min.css" />
<style type="text/css">
    table#fromto {
        border: 1px solid #f3f3f3;
        font-size: 12px;
    }

    table#fromtobottom {
        font-size: 12px;
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
                                $ArrTaxType = unserialize(BOMPURCHASETAXTYPE);
                                if(!empty($ArrBasicInfo->taxtype))
                                    echo $ArrTaxType[$ArrBasicInfo->taxtype];
                                ?>
                            </div>
                        </div><!-- /.box-header -->
                        <div class="box-body">
                            <form class="" action="#" name="frmBasicInfo" id="frmBasicInfo" method="post" autocomplete="off">
                                <table class="table">
                                    <tr>
                                        <td>
                                            <table class="table" id="fromto">
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
                                            <table class="table" id="fromto">
                                                <tr><th style="background-color: #f3f3f3" colspan="2">TO</th></tr>
                                                <tr>
                                                    <td style="padding-top: 15px">NAME</td>
                                                    <td>
                                                        <?php
                                                        echo $VendorInfo->contactname
                                                        ?>
                                                    </td>
                                                </tr>
                                                <tr><td>ADDRESS</td><td id="toaddress"><?php echo @$VendorInfo->address ?></td></tr>
                                                <tr><td>CONTACT NO:</td><td id="contactno"><?php echo @$VendorInfo->phone ?></td></tr>
                                                <tr><td>E-MAIL ID:</td><td id="emailid"><?php echo @$VendorInfo->emailid ?></td></tr>
                                                <tr><td>GST NO:</td><td id="gstno"><?php echo @$VendorInfo->gstno ?></td></tr>
                                                <tr><td>IE CODE:</td><td id="iecode"><?php echo @$VendorInfo->iecode ?></td></tr>

                                            </table>
                                        </td>
                                        <td>
                                            <table class="table" id="fromto">
                                                <tr><th style="background-color: #f3f3f3; text-align: center" colspan="2">PURCHASE REFERENCE</th></tr>
                                                <tr><td><b>P.I. REF. NO:</b></td><td id="pino"><?php echo $PiNo ?></td></tr>
                                                <tr><td>DATE & TIME:</td>
                                                    <td>
                                                        <?php if(empty($ArrBasicInfo->datecreated))
                                                            echo date('d-m-Y');
                                                        else echo date('d-m-Y',strtotime($ArrBasicInfo->datecreated)) ?>
                                                    </td></tr>
                                                <tr><td>AGREED SUPPLY DATE:</td>
                                                    <td>
                                                        <div class="input-group">
                                                        <input type='text' class="form-control" placeholder="Agreed Supply Date" id="agreedsupplydate" value="<?php if(empty($ArrBasicInfo->agreedsupplydate)) echo '-'; else echo date('d-m-Y',strtotime($ArrBasicInfo->agreedsupplydate)) ?>" />
                                                        <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                                        </div>
                                                    </td></tr>
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
                            </form>
                        </div>
                        <div class="box-body">
                            <small>Herewith, we place an order for the following items:</small>
                            <div id="bomPurchaseIndentInvoice"></div>
                            <br/>
                            <form class="form-horizontal" action="#" name="frmBasicInfo" id="frmBasicInfo" method="post" autocomplete="off">
                                <div class="col-md-12">
                                    <label class="" style="position:relative; left: 600px">Total : <span id="frmTotalCurrVal"></span></label>
                                        <input type="text" style="position: relative; left: 717px; width: 100px" id="frmBasicTotal" value="<?php /*if(!empty($ArrBasicInfo->total)) echo $ArrBasicInfo->total */?>">
                                    <input type="text" style="position:relative; left: 763px; width: 100px; " id="frmBasicTotal" value="<?php /*if(!empty($ArrBasicInfo->total)) echo $ArrBasicInfo->total */?>">
                                    <input type="text" style="position:relative; left: 800px; width: 100px; " id="frmBasicTotal" value="<?php /*if(!empty($ArrBasicInfo->total)) echo $ArrBasicInfo->total */?>">
                                    <input type="text" style="position:relative; left: 798px; width: 100px; " id="frmBasicTotal" value="<?php /*if(!empty($ArrBasicInfo->total)) echo $ArrBasicInfo->total */?>">
                                </div>



                                <!--<div class="col-md-6 pull-right">
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-4 control-label">Total : <span id="frmTotalCurrVal"></span></label>
                                        <?php
/*                                        */?>
                                        <div class="col-sm-4">
                                            <input type="text" class="form-control" id="frmBasicTotal" value="<?php /*if(!empty($ArrBasicInfo->total)) echo $ArrBasicInfo->total */?>">
                                            <div class="herr" id="ErrfrmBasicTotal"></div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-md-3">
                                            <input type="text" class="form-control" id="frmBasicOtherChargesifany" placeholder="Other Charges if any" value="<?php /*if(!empty($ArrBasicInfo->otherchargestext)) echo $ArrBasicInfo->otherchargestext */?>">
                                        </div>
                                        <div class="col-md-1" style="padding-top: 5px">
                                            <div><span id="frmOtherChargesCurrVal"></span></div>
                                        </div>
                                        <div class="col-sm-4">
                                            <input type="text" class="form-control" id="frmBasicOtherChargesifanyValue" value="<?php /*if(!empty($ArrBasicInfo->othercharges)) echo $ArrBasicInfo->othercharges */?>">
                                            <div class="herr" id="ErrfrmBasicOtherChargesifanyValue"></div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-4 control-label">Grand Total : <span id="frmGrandTotalCurrVal"></span> </label>
                                        <div class="col-sm-4">
                                            <input type="text" class="form-control" id="frmBasicGrandTotal" value="<?php /*if(!empty($ArrBasicInfo->total) && !empty($ArrBasicInfo->othercharges))  echo $ArrBasicInfo->total + $ArrBasicInfo->othercharges */?>">
                                            <div class="herr" id="ErrfrmBasicGrandTotal"></div>
                                        </div>
                                    </div>
                                </div>-->
                            </form>
                        </div>
                        <div class="box-body">
                            <form class="form-horizontal">
                                <div class="form-group">
                                    <label class="col-md-2"><b>Amount in words:</b></label>
                                    <div class="col-md-10">
                                        <input type="text" id="frmAmountInWords" class="form-control" value="<?php if(!empty($ArrBasicInfo->amountinwords)) echo $ArrBasicInfo->amountinwords ?>">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-2" style="border: 1px solid #f4f4f4">
                                        <label><b>Note:</b></label>
                                        <p style="font-size: 11px; display: inline">If goods are supplied beyond cutoff date, it is up to the discretion of the company to accept or reject the goods. Terms and conditions as agreed upon.</p>
                                    </div>
                                    <label class="col-md-2"><b>Remarks:</b></label>
                                    <div class="col-md-10">
                                        <textarea id="frmRemarks" class="form-control"><?php if(!empty($ArrBasicInfo->remarks)) echo $ArrBasicInfo->remarks ?></textarea>
                                    </div>
                                </div>
                            </form>
                            <div class="row">
                                <div class="col-md-2">
                                    <div class="table-responsive">
                                        <table class="table" id="fromtobottom">
                                            <thead><tr><th style="background-color: #f3f3f3" colspan="2">PURCHASER CONTACT DETAILS</th></tr></thead>
                                            <tbody><tr>
                                                <th>NAME:</th>
                                                <td><input class="form-control" id="frmBasicPurchaserName" type="text" value="<?php echo $ArrBasicInfo->purchasername ?>"></td>
                                            </tr>
                                            <tr>
                                                <th>MOBILE:</th>
                                                <td><input class="form-control" id="frmBasicPurchaserMobile" type="text" value="<?php echo $ArrBasicInfo->purchasermobile ?>"></td>
                                            </tr>
                                            <tr>
                                                <th>EMAIL:</th>
                                                <td><input class="form-control" id="frmBasicPurchaserEmail" type="text" value="<?php echo $ArrBasicInfo->purchaseremail ?>"></td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="table-responsive">
                                        <table class="table" id="fromtobottom">
                                            <thead><tr><th style="background-color: #f3f3f3" colspan="2">VENDOR CONTACT DETAILS</th></tr></thead>
                                            <tbody><tr>
                                                <th>NAME:</th>
                                                <td><input class="form-control" id="frmBasicVendorName" type="text" value="<?php echo $ArrBasicInfo->vendorname ?>"></td>
                                            </tr>
                                            <tr>
                                                <th>MOBILE:</th>
                                                <td><input class="form-control" id="frmBasicVendorMobile" type="text" value="<?php echo $ArrBasicInfo->vendormobile ?>"></td>
                                            </tr>
                                            <tr>
                                                <th>EMAIL:</th>
                                                <td><input class="form-control" id="frmBasicVendorEmail" type="text" value="<?php echo $ArrBasicInfo->vendoremail ?>"></td>
                                            </tr>
                                            </tbody></table>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <label class="control-label">Prepared by:</label>
                                    <br/>
                                    <?php echo @$VarThisUserName ?>
                                    <br/><br/>
                                    <label class="control-label">Name & Signature</label>
                                    <br/>
                                </div>
                                <div class="col-md-2">
                                    <label class="control-label">Authorized by:</label><br/>
                                    <?php echo @$VarAuthorizedBy ?>
                                    <br/><br/>
                                    <label class="control-label">Name & Signature</label>
                                    <br/>
                                    <?php echo '' ?>
                                </div>
                                <div class="col-md-2">
                                    <label class="control-label">Approved by:</label><br/>
                                    <?php echo @$VarApprovedBy ?>
                                    <br/><br/>
                                    <label class="control-label">Name & Signature</label>
                                    <br/>
                                    <?php echo '' ?>
                                </div>
                                <div class="col-md-2">
                                    <label class="control-label">For</label><br/>
                                    <?php echo ''; ?>
                                    <br/><br/>
                                    <label class="control-label">Authorized Signatory</label>
                                    <br/>
                                    <?php echo ''; ?>
                                </div>
                            </div>
                        <a href="<?php echo base_url('purchaseuser/bompurchaseinvoicepdf/'.$ArrBasicInfo->id) ?>">Save as pdf</a>
                        </div>
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
    var GlbCurrencyCode = '<?php echo @$ArrCurrencyCode ?>';
    var GlbTaxType = '<?php echo @$ArrBasicInfo->taxtype ?>';
    var SavedInvoiceGrid = '<?php echo @$SavedInvoiceGrid ?>';
    var GlbGrandtotal = 0; var GlbPurchaseToId = '', GlbValidateVendorSelection = 0, GlbBomPurchaseGrid = '';
    //var url = $(location).attr('href'); var lasturlpart = url.substr(url.lastIndexOf('/')+1);
    console.log(GlbTaxType,'GlbTaxType');
    if(GlbTaxType == 1) {
        $("#bomPurchaseIndentInvoice").jexcel({
            colHeaders: ['Item Description / (%) Blend /<br/>Content / Material', 'Item Code', 'Item Color<br/>Code', 'Size / Dim.<br/>(W*L*H)',
                'Unit of<br/>Measure', 'Quantity', 'Currency','Unit<br/>Rate', 'Amount', 'SGST<br/>(%)', 'SGST<br/>Value', 'CGST<br/>(%)', 'CGST<br/>Value', 'Sub Total'],
            colWidths: [240, 80, 80, 80, 70, 70, 70,50, 100, 50, 90, 50, 90, 100],
            columns: [
                {type: 'text', readOnly: true, wordWrap: true},
                {type: 'text', readOnly: true, wordWrap: true},
                {type: 'text', readOnly: true, wordWrap: true},
                {type: 'text', readOnly: true, wordWrap: true},
                {type: 'text', readOnly: true, wordWrap: true},
                {type: 'text', readOnly: true},
                {type: 'dropdown', source : JSON.parse(GlbCurrencyCode), readOnly: true },
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
                'Quantity','Currency','Unit<br/>Rate','Amount', 'IGST<br/>(%)', 'IGST Value', 'Sub Total'],
            colWidths: [360, 80, 80, 80, 80, 70, 70, 60, 100, 60, 80, 100],
            columns: [
                {type: 'text', wordWrap: true, readOnly: true},
                {type: 'text', readOnly: true, wordWrap: true},
                {type: 'text', readOnly: true, wordWrap: true},
                {type: 'text', readOnly: true, wordWrap: true},
                {type: 'text', readOnly: true, wordWrap: true},
                {type: 'text', readOnly: true},
                {type: 'dropdown', source : JSON.parse(GlbCurrencyCode), readOnly: true },
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
                'Quantity', 'Currency', 'Unit<br/>Rate', 'Amount', 'Duty<br/>(%)', 'Duty value', 'Sub Total'],
            colWidths: [360, 80, 80, 80, 80, 70, 80, 60, 80, 60, 80, 100],
            columns: [
                {type: 'text', wordWrap: true, readOnly: true},
                {type: 'text', readOnly: true, wordWrap: true},
                {type: 'text', readOnly: true, wordWrap: true},
                {type: 'text', readOnly: true, wordWrap: true},
                {type: 'text', readOnly: true, wordWrap: true},
                {type: 'text', readOnly: true},
                {type: 'dropdown', source : JSON.parse(GlbCurrencyCode), readOnly: true },
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