<?php $this->load->view(CNFCOMPANY . 'template/pageheader'); ?>
    <link rel="stylesheet" type="text/css"
          href="<?php echo base_url() ?>assets/css/bootstrap-datetimepicker-standalone.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/jexcel4/jsuites.css"/>
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/jexcel4/jexcel.css"/>
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
        <!-- Main content -->
        <section class="content">
            <!-- Default box -->
            <div class="box-body">
                <?php $this->load->view('commonBasicInfoOrderEntry') ?>
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h1 class="box-title">BOM PURCHASE REQUEST DETAILS</h1>
                        <div class="box-tools pull-right">
                            <?php
                            if($VarArtType == 1) echo 'BOM Article '.$VarArtType;
                            if($VarArtType == 2) echo 'BOM Article '.$VarArtType;
                            if($VarArtType == 3) echo 'BOM Article 1 Shortages';
                            if($VarArtType == 4) echo 'BOM Article 2 Shortages';
                            ?>
                        </div>
                    </div>

                    <div class="box-body">
                        <!--Content-->
                        <div id="bomconslidatedtwelfthtblonetwo"></div>
                        <!--<div id="bomconslidatedShortage"></div>-->

                        <!--<div class="box-footer pull-right">

                        </div>-->
                        <div class="box-footer pull-right">
                            <div class="alert alert-success alert-dismissable hide" id="divSuccessBasicInfoMsg"></div>

                            <!--<button type="button" class="btn btn-info" onclick="fnSavePILocalStore()">Save</button>-->
                            <button type="button" class="btn btn-info" onclick="fnPreparePI()">Prepare P.I.</button>
                            <?php
                            if($previewExists) {
                                ?>
                                <button type="button" class="btn btn-info" onclick="return PreviewBomPIRequestPage()">Preview</button>
                                <?php
                            }
                            ?>

                            <div class="herr" id="divJxlValidationErrorMsg"></div>
                        </div>
                    </div>

                    <!--<div class="box-header with-border">
                        <h1 class="box-title">BOM PURCHASE INDENT ITEMS PROCESSED</h1>
                    </div>
                    <div id="bomPurchaseIndentSentType1Jxl"></div>
                    <div id="bomPurchaseIndentSentType2Jxl"></div>
                    <div id="bomPurchaseIndentSentType3Jxl"></div>-->
                </div>

                <div class="box box-info">
                    <div class="box-header with-border">
                        <h1 class="box-title">BOM SOURCING DETAILS</h1>
                        <div class="box-tools pull-right">
                        </div>
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <div id="bomsourcingdetailsgrid"></div>
                        <!--<div id="bomsourcingdetailsgridShortage"></div>-->

                    </div>
                </div>
                <div class="box box-primary" id="bomsamplingapprvaldiv">
                    <div class="box-header with-border">
                        <h1 class="box-title">BOM SAMPLING & APPROVAL DETAILS</h1>
                    </div>
                    <div class="box-body">
                        <div id="bomsamplingappr_grid"></div>
                    </div>
                </div>

                <div class="box box-info">
                    <!--<div class="box-header with-border">
                    </div>-->
                    <div class="box-body box-bodyPd2010">
                        <form class="form-horizontal">

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="purpose" class="col-sm-4 control-label">Purpose</label>
                                    <div class="col-sm-8">
                                        <input type="text" id="purpose" class="form-control" readonly value="<?php echo @$ArrBasicInfo->purpose ?>">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="inputEmail3" class="col-sm-4 control-label">Request Type</label>
                                    <div class="col-sm-8">
                                        <?php $ArrRequestTimeType = unserialize(ARRREQUESTTYPE) ?>
                                        <select name="" id="frmBasicRequestType" class="form-control" disabled>
                                            <option value="">Choose</option>
                                            <?php
                                            foreach ($ArrRequestTimeType as $keyid => $item) {
                                                ?>
                                                <option value="<?php echo $keyid ?>" <?php if (!empty($ArrBasicInfo->requesttype)) echo $ArrBasicInfo->requesttype == $keyid ? 'selected' : '' ?>><?php echo $item ?></option>
                                                <?php
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="inputEmail3" class="col-sm-4 control-label">Request Date & Time</label>
                                    <div class="col-sm-8">
                                        <input type='text' class="form-control" readonly id="frmBasicReqDt"
                                               value="<?php if (!empty($ArrBasicInfo->requestdt)) echo date('d-m-Y H:i:s', strtotime($ArrBasicInfo->requestdt)); else echo date('d-m-Y H:i:s'); ?>"/>
                                        <div class="herr" id=""></div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="inputEmail3" class="col-sm-4 control-label">CuttOff Date
                                        &<br/>Time</label>
                                    <div class="col-sm-8">
                                        <div class='input-group date' id='cutoffdatetimepicker'>
                                            <input type='text' class="form-control" readonly id="frmBasicCutoffdatetime"
                                                   value="<?php if (!empty($ArrBasicInfo->cutoffdatetime)) echo date('d-m-Y H:i:s', strtotime($ArrBasicInfo->cutoffdatetime)) ?>"/>
                                            <span class="input-group-addon"><span
                                                        class="glyphicon glyphicon-calendar"></span></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="inputEmail3" class="col-sm-4 control-label">Merchant Note</label>
                                    <div class="col-sm-8">
                                    <textarea class="form-control" style="height: 64px" id="frmBasicmerNote"
                                              readonly
                                              placeholder="Merchant Note"><?php if (!empty($ArrBasicInfo->merchantnote)) echo $ArrBasicInfo->merchantnote ?></textarea>
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
                                        <span class="form-control" id=""
                                              readonly="readonly"><?php echo $VarCs; ?></span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Authorization Type</label>
                                    <?php $ArrApprovalType = unserialize(ARRREQUESTTYPE) ?>
                                    <div class="col-sm-8">
                                        <select class="form-control" id="frmApprovalType" disabled>
                                            <?php
                                            foreach ($ArrApprovalType as $KeyId => $value) {
                                                ?>
                                                <option value="<?php echo $KeyId ?>" <?php if (!empty($ArrBasicInfo->approvaltype)) if ($ArrBasicInfo->approvaltype == $KeyId) echo 'selected' ?>>
                                                    <?php echo $value ?>
                                                </option>
                                                <?php
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Authorization Date & Time</label>
                                    <div class="col-sm-8">
                                        <input type="text" id="frmAuthDateTime" readonly class="form-control"
                                               value="<?php if (!empty($ArrBasicInfo->authdatetime)) echo date('d-m-Y H:i:s', strtotime($ArrBasicInfo->authdatetime)); ?>">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="" class="col-sm-4 control-label">Management Remarks</label>
                                    <div class="col-sm-8">
                                    <textarea readonly class="form-control"
                                              style="height: 64px"><?php if (!empty($ArrBasicInfo->mgmtremarks)) echo $ArrBasicInfo->mgmtremarks ?></textarea>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="" class="col-sm-4 control-label">Request Status</label>
                                    <div class="col-sm-8">
                                        <?php

                                        ?>
                                        <select name="" id="frmCadDeptAcceptReject" class="form-control" disabled>
                                            <option value="">Choose</option>
                                            <option value="1" <?php if ($ArrBasicInfo->deptcurrentstatus == 1) echo 'selected' ?>>
                                                REQUEST PENDING
                                            </option>
                                            <option value="2" <?php if ($ArrBasicInfo->deptcurrentstatus == 2) echo 'selected' ?>>
                                                ACCEPTED
                                            </option>
                                            <option value="3" <?php if ($ArrBasicInfo->deptcurrentstatus == 3) echo 'selected' ?>>
                                                DECLINED
                                            </option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="" class="col-sm-4 control-label">Assign Purchase Queue.
                                        No</label>
                                    <div class="col-sm-8">
                                        <input type="text" name="" class="form-control" id="" readonly
                                               value="<?php echo @$ArrBasicInfo->queueno ?>">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="" class="col-sm-4 control-label">Queue No. Assigned Date &
                                        Time</label>
                                    <div class="col-sm-8">
                                        <input type="text" name="" class="form-control" id="" readonly
                                               value="<?php if (!empty($ArrBasicInfo->queueno_assigned_date)) echo date('d-m-Y H:i:s', strtotime($ArrBasicInfo->queueno_assigned_date)); ?>">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="" class="col-sm-4 control-label">Purchase Dept. Remarks</label>
                                    <div class="col-sm-8">
                                    <textarea readonly class="form-control"
                                              style="height: 64px"><?php if (!empty($ArrBasicInfo->deptremarks)) echo $ArrBasicInfo->deptremarks ?></textarea>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="" class="col-sm-4 control-label">Current Status</label>
                                    <div class="col-sm-8">
                                    <span class="form-control" readonly="readonly">
                                        <?php
                                        if ($ArrBasicInfo->deptcurrentstatus == 1) echo 'REQUEST PENDING';
                                        if ($ArrBasicInfo->deptcurrentstatus == 2) echo 'ACCEPTED';
                                        if ($ArrBasicInfo->deptcurrentstatus == 3) echo 'DECLINED';
                                        ?>
                                    </span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="" class="col-sm-4 control-label">Recent Update</label>
                                    <div class="col-sm-8"><span class="form-control" id="recentupdate"
                                                                readonly="readonly"><?php if (!empty($ArrBasicInfo->dateupdated))
                                                echo date('d-m-Y H:i:s', strtotime($ArrBasicInfo->dateupdated)) ?></span>
                                        <span class="form-control hide" id="recentupdateCs" readonly="readonly"></span>
                                    </div>
                                </div>
                            </div>

                            <!--<div class="col-md-12">
                                <div class="form-group">
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
/*                                                $VarFdr = FCPATH . "uploads/bompurchaserequest/" . $VarBomPurReqId;
                                                if (file_exists($VarFdr)) {
                                                    if ($dh = opendir($VarFdr)) {
                                                        while (($file = readdir($dh)) !== false) {
                                                            if ($file != "." && $file != "..") {
                                                                */?>
                                                                <li>
                                                                    <div style="padding: 10px 0;">
                                                                        <?php /*echo $file . ' '; */?>&nbsp;<a
                                                                                href="<?php /*echo base_url() . "dashbaord/downloadFileFromUploads?id=" . $VarBomPurReqId . "&filename=" . $file */?>">
                                                                            <i class="fa fa-download fa-lg"
                                                                               aria-hidden="true"></i>
                                                                        </a>&nbsp;&nbsp;<a
                                                                                href="<?php /*echo base_url() . "uploads/bompurchaserequest/" . $VarBomPurReqId . "/" . $file */?>"
                                                                                target="_blank">
                                                                            <i class="fa fa-file fa-lg"
                                                                               aria-hidden="true"></i>
                                                                        </a>
                                                                    </div>
                                                                </li>
                                                                <?php
/*                                                            }
                                                        }
                                                        closedir($dh);
                                                    }
                                                    */?>
                                                    <?php
/*                                                } else {
                                                    echo 'No attachments';
                                                }
                                                */?>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>-->

                        </form>
                    </div>

                </div>
            </div>
            <!-- /.box-body -->
            <!--<div class="box-footer">
                <div class="alert alert-success alert-dismissable hide" id="divSuccessBasicInfoMsg"></div>
            </div>-->
            <!-- /.box-footer-->

            <!-- Modal Starts Here -->
            <div class="modal fade" id="BomPITaxOptionModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <form class="form-horizontal" action="#" method="post" id="frmPinformId" autocomplete="off">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                        aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="myModalLabel">Choose a Tax Type</h4>
                        </div>
                        <div class="modal-body">
                                <div class="form-group">

                                    <div class="col-md-4">
                                        <input type="radio" name="frmSelectTaxType" id="inputRadio1" value="1" class="">
                                        <label for="inputRadio1" class="">SGST / CGST RATE</label>
                                    </div>
                                    <div class="col-md-4">
                                        <input type="radio" name="frmSelectTaxType" id="inputRadio2" value="2" class="">
                                        <label for="inputRadio2" class="">IGST RATE</label>
                                    </div>
                                    <div class="col-md-4">
                                        <input type="radio" name="frmSelectTaxType" id="inputRadio3" value="3" class="">
                                        <label for="inputRadio3" class="">IMPORT DUTY</label>
                                    </div>
                                </div>


                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary" onclick="return fnBomPITaxType()">Continue
                            </button>
                            <div class="herr pull-left" id="ErrfrmSelectTaxType"></div>
                        </div>
                        </form>
                    </div>
                </div>
            </div>
        </section><!-- /.content -->
    </div><!-- /.content-wrapper -->
    <?php $this->load->view(CNFCOMPANY . 'template/templatefooter'); ?>
    <div class="control-sidebar-bg"></div>
</div><!-- ./wrapper -->

<script src="<?php echo base_url(); ?>assets/js/moment.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/bootstrap-datetimepicker.js"></script>
<script src="<?php echo base_url(); ?>assets/js/jexcel4/jsuites.js"></script>
<script src="<?php echo base_url(); ?>assets/js/jexcel4/jexcel.js"></script>
<script>
    var GlbBomPurReqIdId = '<?php echo $VarBomPurReqId ?>';
    //var purchaseIndentKey = '<?php echo @$piPK ?>';
    var GlbOrderId = '<?php echo $VarOrderId ?>';
    var GlbArtType = '<?php echo $VarArtType ?>';
    var GlbCurrencynCode = '<?php echo $ArrCurrencyNCode ?>';
    var dynamicTblBomPI = '<?php echo $dynamicTblBomPI ?>';
    var dynamicTblName = '<?php echo $dynamicTblName ?>';
    var GlbBomConsForBomPurInd = 0; var GlbBomSourcingDetail = 0; var GlbSamplingAppr = 0; var GlbUom = 0; var PrePareBomPIJxl = 0;
    var checkGridData = []; var checkGridDataTwo = [];

    MakePostRequest(base_path+'mpurchase/getAddeditBOMDatas',"rfrom=1&at="+GlbArtType+"&refid="+GlbOrderId+
        "&bomPurRequestId="+GlbBomPurReqIdId,"json",fnGetGridBOMDataRes);

    function fnGetGridBOMDataRes(data) {
        console.log(dynamicTblBomPI, 'dynamicTblBomPI');
        if(dynamicTblBomPI != '') {
            let bomPi = JSON.parse(dynamicTblBomPI);
            GlbBomConsForBomPurInd = bomPi;
        }
        //GlbBomConsForBomPurInd = dynamicTblBomPI;
        console.log(GlbBomConsForBomPurInd,'GlbBomConsForBomPurInd');
        GlbBomSourcingDetail = data.bomSourcingDetail;
        GlbSamplingAppr = data.ArrSamplingAppr;
        GlbUom = data.unitofmeasure;
        if (GlbArtType == 1 || GlbArtType == 2) {
            PrePareBomPIJxl = jexcel(document.getElementById('bomconslidatedtwelfthtblonetwo'), {
                columns: [
                    {type: 'text', title: 'Item Description / Blend (%) / Content / Material', width: 150, wordWrap: true, readOnly: true},
                    {type: 'text', title: 'Gar. Size', width: 40, readOnly: true},
                    {type: 'text', title: 'Item Code', width: 100, readOnly: true},
                    {type: 'text', title: 'Item Color Code', width: 100, readOnly: true},
                    {type: 'text', title: 'Size / Dim. (W*L*H)', width: 80, readOnly: true},
                    {type: 'text', title: 'UOM', width: 60, readOnly: true},
                    {type: 'text', title: 'Planned BOM Qty.', width: 80, readOnly:true},
                    {type: 'numeric', title: 'Programmed BOM Qty.', width: 90},
                    {type: 'dropdown', title: 'UOM', width: 80, source: JSON.parse(GlbUom)},
                    {type: 'dropdown', title: 'Currency', width: 70, source: JSON.parse(GlbCurrencynCode)},
                    {type: 'numeric', title: 'Unit Rate', width: 40},
                    {type: 'numeric', title: 'Amount', width: 80},
                    {type: 'hidden'},
                    {type: 'hidden'},
                    {type: 'checkbox', title: 'Select', width: 50},
                ],
                data: GlbBomConsForBomPurInd,
                allowInsertColumn: false,
                updateTable: function (instance, cell, col, row, val, label, cellName) {
                    if (col == 7) {
                        ProgBomQty = val;
                    }
                    if (col == 10) {
                        UnitRate = val;
                    }
                    if (col == 11) {
                        var Amount = ProgBomQty * UnitRate;
                        $(cell).html(Amount);
                        instance.jexcel.options.data[row][col] = Amount;
                    }
                    if (col == 12) {
                        //primarykey = val;
                    }
                    if (col == 13) {
                        //hidestatus = val;
                    }
                    if (col == 14) {
                        //console.log(hidestatus,'hidestatus');
                        //if (hidestatus == 1) {
                            //console.log(hidestatus,'hidestatus');
                            //$(cell).find('input:checkbox').hide();
                            //instance.jexcel.options.data[row][col] = 1;
                        //}

                    }
                }
            });

            $("#bomsourcingdetailsgrid").jexcel({
                columns: [
                    {title: 'Item Description / Blend (%) / Content / Material', width: 200, wordWrap: true, readOnly: true},
                    {type: 'text', title: 'Gar. Size', width: 50, wordWrap: true,  readOnly: true},
                    {type: 'text', title: 'Item Code', width: 150, wordWrap: true,  readOnly: true},
                    {type: 'text', title: 'Item Color Code', width: 160, wordWrap: true,  readOnly: true},
                    {title: 'Sourcing<br/>Advice', type: 'text', wordWrap: true, width: 110,  readOnly: true},
                    {title: 'Vendor<br/>Location', type: 'text', wordWrap: true,  width: 80, readOnly: true},
                    {title: 'Vendors<br/>Name / Address', type: 'text', wordWrap: true,  width: 120, readOnly: true},
                    {title: 'GST /<br/>IE code<br/>Details', type: 'text', wordWrap: true,  width: 80, readOnly: true},
                    {title: 'If On-line<br/>Ordering System:<br/>Website / User ID /<br/>Password', type: 'text',  width: 120, wordWrap: true, readOnly: true},
                    {title: 'P.W. Expiry<br/>Date', type: 'calendar', options: {format: 'DD/MM/YYYY'},  width: 130, readOnly: true},
                    {title: 'Contact : Person / E-mail Id<br/>/ Phone / Mobile', type: 'text',  width: 80, wordWrap: true, readOnly: true}
                ],
                data: JSON.parse(GlbBomSourcingDetail),
                allowInsertColumn: false
            });


            jexcel(document.getElementById("bomsamplingappr_grid"), {
                allowInsertColumn: false,
                columns: [
                    {title: 'Item Description / Blend (%) / Content / Material', width: 250, wordWrap: true, readOnly: true},
                    {title: 'Category', width: 80, wordWrap: true, readOnly: true},
                    {title: 'Sample Sub.\nfor Approval', width: 80, wordWrap: true, readOnly: true},
                    {title: 'Sample\nSub. Size', width: 70, wordWrap: true, readOnly: true },
                    {title: 'Reqd. No.\nof Samples', width: 80, wordWrap: true, readOnly: true },
                    {title: 'Sample Sub. Date', width: 80, wordWrap: true, readOnly: true},
                    {title: 'Approval\nStatus', width: 80, wordWrap: true, readOnly: true },
                    {title: 'Approved Sample /\nItem Code', width: 140, wordWrap: true, readOnly: true },
                    {title: 'Approved Sample /\nItem Color Code', width: 140, wordWrap: true, readOnly: true },
                    {title: 'Approved By', width: 100, readOnly: true},
                    {title: 'Approved Date', width: 80, readOnly: true},
                    {title: 'Remarks', width: 177, wordWrap: true, readOnly: true },
                ],
                data: GlbSamplingAppr
            });

        }
        else {
            $("#bomsamplingapprvaldiv").hide();
            //console.log(GlbArtType, 'GlbArtType');
            if ($("#bomconslidatedtwelfthtblonetwo").find('table').length) {
                $("#bomconslidatedtwelfthtblonetwo").jexcel('destroy');
                $("#bomsourcingdetailsgrid").jexcel('destroy');
                $("#bomsamplingappr_grid").jexcel('destroy');
            }
            //console.log(data.ShoItemDesc, 'data.ShoItemDesc');
            //console.log(GlbSizeChartSizes, 'GlbSizeChartSizes');
            GlbItemDesc = data.ShoItemDesc;
            GlbSizeChartSizes = data.SizeChartSizes;
            GlbItemCode = data.ShoItemCode;
            GlbItemColorCode = data.ShoItemColorCode;
            GlbSizeDim = data.ShoSizeDim;
            GlbUofm = data.ShoUom;
            //console.log(GlbItemDesc, 'GlbItemDesc');
            $("#bomconslidatedShortage").jexcel({
                columns: [
                    {
                        type: 'dropdown',
                        title: 'Item Description / Blend (%) / Content / Material',
                        width: 300,
                        source: GlbItemDesc
                    },
                    {type: 'dropdown', title: 'Gar. Size', width: 70, source: GlbSizeChartSizes},
                    {type: 'dropdown', title: 'Item Code', width: 100, source: GlbItemCode},
                    {type: 'dropdown', title: 'Item Color Code', width: 100, source: GlbItemColorCode},
                    {type: 'dropdown', title: 'Size / Dim. (W*L*H)', width: 100, source: GlbSizeDim},
                    {type: 'dropdown', title: 'UOM', width: 100, source: GlbUofm},
                    {type: 'text', title: 'Req. BOM Qty.', width: 100},
                    {type: 'text', title: 'Ex. Qty. (%)', width: 100},
                    {type: 'text', title: 'Ex. Qty. (Nos.)', width: 100},
                    {type: 'text', title: 'Shortage BOM Qty.', width: 100},
                    {type: 'dropdown', title: 'UOM', width: 100},
                ],
                minDimensions: [11, 1],
                allowInsertColumn: false
            });
        }
    }

    /**Save button to store in localStorage for Temporary use*/
    /*function fnSavePILocalStore() {
        let datas = $("#bomconslidatedtwelfthtblonetwo").jexcel('getData');
        let errAlert = 0;
        for (let i = 0; i < datas.length; i++) {
            if(datas[i][14] == 1) {

            }
            else {
                    errAlert = 1;
            }
        }
        if(errAlert == 0) {
            MakePostRequest(base_path + 'dashboard/updatePrepareBomPI', "rfrom=1&tempSavePrepareBomPi=" + JSON.stringify(datas)
                + "&rid=" + GlbBomPurReqIdId + "&tblName="+TblName, "json", function (data) {
                console.log(data, 'data save only res');
            });
        }
        else {
            $("#divJxlValidationErrorMsg").text('Please Select a Item');
        }

    }*/

    var currentUrl = window.location.href; var lastUrlPart = currentUrl.substr(currentUrl.lastIndexOf('/')+1);

    function PreviewBomPIRequestPage() {
        /*if(localStorage.getItem("lsBomPIApprovalReqJxl")) {
            window.location.replace(base_path+'bompurchaseindent/bompiapprovalrequest/'+lastUrlPart);
        }
        else {
            alert('No Preview');
        }*/
        window.location.replace(base_path+'bompurchaseindent/bompiapprovalrequest/'+lastUrlPart);
    }
    
    function fnPreparePI() {
        let bomPi = $("#bomconslidatedtwelfthtblonetwo").jexcel('getData');
        let selectCol = $("#bomconslidatedtwelfthtblonetwo").jexcel('getColumnData',14);
        if(selectCol.includes(true)) {
            if (window.confirm("Do you really want to Prepare Purchase Indent?")) {
                $('#BomPITaxOptionModal').modal('show');
            }
            else {
                $('#BomPITaxOptionModal').modal('hide');
            }
        }
        else {
            alert('Please Select a Item to prepare Purchase Indent');
        }

    }

    function fnBomPITaxType() {
        $(".herr").text("");
        var taxtype = $('input[name="frmSelectTaxType"]:checked').val();
        //console.log(taxtype,'taxtype');
        if(taxtype) {
            let datas = $("#bomconslidatedtwelfthtblonetwo").jexcel('getData');
            //console.log(datas,'datas');
            let selectCol = $("#bomconslidatedtwelfthtblonetwo").jexcel('getColumnData',14);
            //console.log(selectCol,'selectCol');
            //console.log(selectCol.includes(true),'selectCol.includes(true)');
            if(selectCol.includes(true)) {
                MakePostRequest(base_path + 'bompurchaseindent/updatePrepareBomPI', "rfrom=1&tempSavePrepareBomPi=" +
                    JSON.stringify(datas) + "&rid=" + GlbBomPurReqIdId + "&bomPiTaxTypeId=" + taxtype+"&tblName="+
                    dynamicTblName, "json", function (data) {
                    $('#BomPITaxOptionModal').modal('hide');
                    window.location.replace(base_path+'bompurchaseindent/bompiapprovalrequest/'+lastUrlPart);
                });
            }
            else {
            }
        }
        else {
            $("#ErrfrmSelectTaxType").text("Please Select a Tax Type");
            $('#frmSelectTaxType').css("border", "1px solid #B94A48");
            $("#frmSelectTaxType").focus();
            return false;
        }
    }
</script>
<?php $this->load->view(CNFCOMPANY . 'template/pagefooter'); ?>