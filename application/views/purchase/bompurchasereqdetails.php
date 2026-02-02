<?php $this->load->view(CNFCOMPANY . 'template/pageheader'); ?>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>assets/css/bootstrap-datetimepicker-standalone.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/jexcel4/jsuites.css"/>
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/jexcel4/jexcel.css"/>
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
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-info">
                        <div class="box-body" style="padding: 0">
                            <?php $this->load->view('commonBasicInfoOrderEntry'); ?>
                        </div>
                    </div>
                    <div class="box box-info">
                        <div class="box-body">
                            <div class="box-header with-border">
                                <h1 class="box-title">BOM PURCHASE REQUEST DETAILS</h1>
                            </div>
                        </div>
                        <div class="box-body">
                            <!--Content-->
                            <div id="bomconslidatedtwelfthtblonetwo"></div>
                            <div id="bomconslidatedShortage"></div>
                        </div>
                    </div>
                    <div class="box box-info">
                        <div class="box-header with-border">
                            <h1 class="box-title">BOM SOURCING DETAILS</h1>
                            <div class="box-tools pull-right">
                            </div>
                        </div><!-- /.box-header -->
                        <div class="box-body">
                            <div id="bomsourcingdetailsgrid"></div>
                            <div id="bomsourcingdetailsgridShortage"></div>

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
                        <div class="box-body box-bodyPd2010">
                            <form class="form-horizontal" id="frmMgmtBomPurchaseRequest" method="post">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-4 control-label">Purpose</label>
                                        <div class="col-sm-8">
                                            <input type="text" id="frmBasicPurpose" class="form-control" readonly value="<?php echo $ArrBasicInfo->purpose ?>">
                                            <?php
                                            ?>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-4 control-label">Request Type</label>
                                        <div class="col-sm-8">
                                            <?php
                                            $ArrRequestTimeType    = unserialize(ARRREQUESTTYPE);
                                            $ArrORDERENQUIRYSTATUS = unserialize(ORDERENQUIRYSTATUS);
                                            ?>
                                            <input type="text" id="frmBasicRequestType" class="form-control" readonly value="<?php echo $ArrRequestTimeType[$ArrBasicInfo->requesttype] ?>">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-4 control-label">Request Date & Time</label>
                                        <div class="col-sm-8">
                                            <input type='text' class="form-control" readonly id="frmBasicReqDt" value="<?php if(!empty($ArrBasicInfo->requestdt)) echo date('d-m-Y H:i:s',strtotime($ArrBasicInfo->requestdt)); else echo date('d-m-Y H:i:s'); ?>" />
                                            <div class="herr" id=""></div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-4 control-label">CuttOff Date &<br/>Time</label>
                                        <div class="col-sm-8">
                                            <input type='text' class="form-control" readonly id="frmBasicCutoffdatetime" value="<?php if(!empty($ArrBasicInfo->cutoffdatetime)) echo date('d-m-Y H:i:s',strtotime($ArrBasicInfo->cutoffdatetime)) ?>">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-4 control-label">Merchant Note</label>
                                        <div class="col-sm-8">
                                            <textarea class="form-control" style="height: 64px" id="frmBasicmerNote" readonly placeholder="Merchant Note"><?php if(!empty($ArrBasicInfo->merchantnote)) echo $ArrBasicInfo->merchantnote ?></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-4 control-label">Authorization Status</label>
                                        <div class="col-sm-8">
                                            <select id="frmApproveReject" class="form-control" disabled>
                                                <option value="">Choose</option>
                                                <option value="2" <?php if($ArrBasicInfo->mgmtcurrentstatus == 2) echo 'selected' ?>>AUTHORIZE</option>
                                                <option value="3" <?php if($ArrBasicInfo->mgmtcurrentstatus == 3) echo 'selected' ?>>REJECT</option>
                                            </select>
                                            <div class="herr" id="frmApproveReject"></div>
                                            <?php
                                            $VarCs = '';
                                            if(@$mgmtcurrentstatus == 1) {
                                                $VarCs = 'AUTHORIZATION PENDING';
                                            }
                                            elseif(@$mgmtcurrentstatus == 2) {
                                                $VarCs = 'AUTHORIZED';
                                            }
                                            elseif (@$mgmtcurrentstatus == 3) {
                                                $VarCs = 'NOT AUTHORIZED';
                                            }
                                            elseif (@$mgmtcurrentstatus == 4) {
                                                $VarCs = 'RE REQUEST';
                                            }
                                            ?>
                                            <!--<span class="form-control" id="" readonly="readonly"><?php /*echo $VarCs; */?></span>-->
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
                                                    <option value="<?php echo $KeyId ?>" <?php if(!empty($ArrBasicInfo->approvaltype)) if($ArrBasicInfo->approvaltype == $KeyId) echo 'selected' ?>>
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
                                            <input type="text" id="frmAuthDateTime" readonly class="form-control" value="<?php if(!empty($ArrBasicInfo->authdatetime) && $ArrBasicInfo->authdatetime !== "0000-00-00 00:00:00") echo date('d-m-Y H:i:s',strtotime($ArrBasicInfo->authdatetime)); ?>">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">Management Remarks</label>
                                        <div class="col-sm-8">
                                            <textarea class="form-control" id="frmMgmtRemarks" readonly style="height: 64px"><?php if(!empty($ArrBasicInfo->mgmtremarks)) echo $ArrBasicInfo->mgmtremarks ?></textarea>
                                        </div>
                                        <div class="herr" id="ErrfrmMgmtRemarks"></div>
                                    </div>
                                    <div class="form-group">
                                        <label for="enqdate" class="col-sm-4 control-label">Request Status</label>
                                        <div class="col-sm-8">
                                            <?php

                                            ?>
                                            <select id="frmDeptApprReject" class="form-control" disabled>
                                                <option value="1" <?php if($ArrBasicInfo->deptcurrentstatus == 1) echo 'selected' ?>>PENDING</option>
                                                <option value="2" <?php if($ArrBasicInfo->deptcurrentstatus == 2) echo 'selected' ?>>ACCEPT</option>
                                                <option value="3" <?php if($ArrBasicInfo->deptcurrentstatus == 3) echo 'selected' ?>>REJECT</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="enqdate" class="col-sm-4 control-label">Assign Purchase Queue. No</label>
                                        <div class="col-sm-8">
                                            <input type="text" name="" class="form-control" id="" readonly value="<?php echo @$ArrBasicInfo->queueno ?>">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="enqdate" class="col-sm-4 control-label">Queue No. Assigned Date & Time</label>
                                        <div class="col-sm-8">
                                            <input type="text" name="" class="form-control" id="" readonly value="<?php if(!empty($ArrBasicInfo->queueno_assigned_date)) echo date('d-m-Y H:i:s',strtotime($ArrBasicInfo->queueno_assigned_date)); ?>">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="enqdate" class="col-sm-4 control-label">Purchase Dept. Remarks</label>
                                        <div class="col-sm-8">
                                            <textarea readonly class="form-control" style="height: 64px"><?php if(!empty($ArrBasicInfo->deptremarks)) echo $ArrBasicInfo->deptremarks ?></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="enqdate" class="col-sm-4 control-label">Current Status</label>
                                        <div class="col-sm-8">
                                            <?php
                                            //echo '<pre>'; print_r($ArrBasicInfo); die('die');
                                            if($ArrBasicInfo->deptcurrentstatus == 1) {
                                                $VarCs = 'AUTHORIZATION '.$ArrORDERENQUIRYSTATUS[$ArrBasicInfo->mgmtcurrentstatus];
                                            }
                                            else {
                                                $VarCs = 'Sample Dept. '.$ArrORDERENQUIRYSTATUS[$ArrBasicInfo->deptcurrentstatus];
                                            }
                                            ?>
                                            <input type="text" class="form-control" id="updateCurrentStatus" readonly value="<?php echo $VarCs ?>">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="enqdate" class="col-sm-4 control-label">Recent Update</label>
                                        <div class="col-sm-8"><span class="form-control" id="recentupdate" readonly="readonly"><?php if(!empty($ArrBasicInfo->dateupdated))
                                                    echo date('d-m-Y H:i:s',strtotime($ArrBasicInfo->dateupdated)) ?></span>
                                            <span class="form-control hide" id="recentupdateCs" readonly="readonly"></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="herr" id="ErrfrmBasicErr"></div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label">Merchant Attachments</label>
                                        <div class="col-md-12" style="border:2px dotted #A5A5C7; padding: 10px; background-color: #eee">
                                            <ul style="list-style: none;">
                                                <?php
                                                $VarFdr = UPLOADS_SLASH."bomrequest".DIRECTORY_SEPARATOR.$VarId.DIRECTORY_SEPARATOR."Merchant".DIRECTORY_SEPARATOR;
                                                $ArrDwnExtensions = DWN_FILE_EXTENSIONS;
                                                if(file_exists($VarFdr)) {
                                                    if ($dh = opendir($VarFdr)) {
                                                        while (($file = readdir($dh)) !== false) {
                                                            if(is_file($VarFdr . $file)) {
                                                                ?>
                                                                <li>
                                                                    <div style="padding: 10px 0;">
                                                                        <?php echo $file .' ';
                                                                        $VarFileExt = pathinfo($file, PATHINFO_EXTENSION);
                                                                        $downUrl = base_url()."dashboard/commonSimpleDownload?id=".urlencode(base64_encode($VarId))."&fileName=".urlencode($file)."&folder=bomrequest&by=Merchant" ?>&nbsp;<a href="<?php echo $downUrl ?>">
                                                                            <i class="fa fa-download fa-lg" aria-hidden="true"></i>
                                                                        </a>&nbsp;&nbsp;
                                                                        <?php
                                                                        if(in_array($VarFileExt,$ArrDwnExtensions)) {

                                                                        }
                                                                        else {
                                                                            ?>
                                                                            <a href="<?php echo base_url()."dashboard/openFileInBrowser?id=".$VarId."&fileName=".$file."&folder=bomrequest&by=Merchant" ?>" target="_blank">
                                                                                <i class="fa fa-file fa-lg" aria-hidden="true"></i>
                                                                            </a>
                                                                            <?php
                                                                        }
                                                                        ?>
                                                                    </div>
                                                                </li>
                                                                <?php
                                                            }
                                                        }
                                                        closedir($dh);
                                                    }
                                                }
                                                else {
                                                    echo 'No attachments';
                                                }
                                                ?>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>




                </div>

            </div>
        </section><!-- /.content -->
    </div><!-- /.content-wrapper -->
    <?php $this->load->view(CNFCOMPANY . 'template/templatefooter'); ?>
    <div class="control-sidebar-bg"></div>
</div><!-- ./wrapper -->
<script src="<?php echo base_url();?>assets/js/moment.min.js"></script>
<script src="<?php echo base_url();?>assets/js/bootstrap-datetimepicker.js"></script>
<script src="<?php echo base_url();?>assets/js/jexcel/jquery.jexcel.js"></script>
<script src="<?php echo base_url(); ?>assets/js/jexcel4/jsuites.js"></script>
<script src="<?php echo base_url(); ?>assets/js/jexcel4/jexcel.js"></script>
<script>
    var GlbId = '<?php echo $VarId ?>';
    var GlbOrderId = '<?php echo $VarOrderId ?>';
    var GlbArtType = '<?php echo $VarArtType ?>';
    var GlbBomConsolidated = 0; var GlbBomsourcingdetail = 0; var GlbSamplingAppr = 0;
    MakePostRequest(base_path + 'mpurchase/getAddeditBOMDatas', "rfrom=1&at=" + GlbArtType + "&refid=" + GlbOrderId, 'json',
        fnGetGridBOMDataRes);
    function fnGetGridBOMDataRes(data) {
        console.log(data, 'fnGetGridBOMDataRes data');
        GlbBomConsolidated = data.bomConsolidated;
        GlbBomsourcingdetail = data.bomSourcingDetail;
        GlbSamplingAppr = data.ArrSamplingAppr;
        uom = data.unitofmeasure;

        if (GlbArtType == 1 || GlbArtType == 2) {
            $("#bomsamplingapprvaldiv").show();
            if($("#bomconslidatedtwelfthtblonetwo").find('table').length) {
                $("#bomconslidatedtwelfthtblonetwo").jexcel('destroy');
                $("#bomsourcingdetailsgrid").jexcel('destroy');
                $("#bomsamplingappr_grid").jexcel('destroy');
            }
            if($("#bomconslidatedShortage").find('table').length) {
                $("#bomconslidatedShortage").jexcel('destroy');
            }
            if($("#bomsourcingdetailsgridShortage").find('table').length) {
                $("#bomsourcingdetailsgridShortage").jexcel('destroy');
            }
            $("#bomconslidatedtwelfthtblonetwo").jexcel({
                colHeaders: ['Item Description / Blend (%) /<br/>Content / Material', 'Gar.<br/>Size', 'Item Code', 'Item Color<br/>Code', 'Size / Dim.<br/>(W*L*H)', 'Unit of<br/>Measure', 'Consld.<br/>BOM<br/>Reqd. Qty.',
                    'Ex. Qty.<br/>(%)', 'Ex. Qty.<br/>Nos.', 'Plan. BOM Qty.', 'Unit of<br/>Measure'],
                colWidths: [320, 50, 130, 150, 100, 80, 80, 70, 70, 70, 80],
                columns: [
                    {type: 'text', readOnly: true, wordWrap: true},
                    {type: 'text', readOnly: true},
                    {type: 'text', readOnly: true},
                    {type: 'text', readOnly: true},
                    {type: 'text', readOnly: true},
                    {type: 'text', readOnly: true},
                    {type: 'text', readOnly: true},
                    {type: 'text', readOnly: true},
                    {type: 'text', readOnly: true, wordWrap: true},
                    {type: 'text', readOnly: true},
                    {type: 'text', readOnly: true}
                ],
                data: JSON.parse(GlbBomConsolidated)
            });


            $("#bomsourcingdetailsgrid").jexcel({
                allowInsertColumn: false,
                columns: [
                    {title: 'Item Description / Blend (%) /<br/>Content / Material', type: 'text', width: 300, wordWrap: true, readOnly: true},
                    {title: 'Gar. Size', type: 'text', wordWrap: true, width: 70, readOnly: true},
                    {title: 'Item Code', type: 'text', wordWrap: true,  width: 100, readOnly: true},
                    {title: 'Item Color Code', type: 'text', wordWrap: true,  width: 100, readOnly: true},
                    {title: 'Sourcing<br/>Advice', type: 'text', wordWrap: true, width: 110,  readOnly: true},
                    {title: 'Vendor<br/>Location', type: 'text', wordWrap: true,  width: 80, readOnly: true},
                    {title: 'Vendors<br/>Name / Address', type: 'text', wordWrap: true,  width: 120, readOnly: true},
                    {title: 'GST /<br/>IE code<br/>Details', type: 'text', wordWrap: true,  width: 80, readOnly: true},
                    {title: 'If On-line<br/>Ordering System:<br/>Website / User ID /<br/>Password', type: 'text',  width: 120, wordWrap: true, readOnly: true},
                    {title: 'P.W. Expiry<br/>Date', type: 'calendar', options: {format: 'DD/MM/YYYY'},  width: 130, readOnly: true},
                    {title: 'Contact : Person / E-mail Id<br/>/ Phone / Mobile', type: 'text',  width: 80, wordWrap: true, readOnly: true}
                ],
                data: JSON.parse(GlbBomsourcingdetail)
            });

//
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
            //
        }
        else {
            $("#bomsamplingapprvaldiv").hide();
            console.log(GlbArtType,'GlbArtType');
            if($("#bomconslidatedtwelfthtblonetwo").find('table').length) {
                $("#bomconslidatedtwelfthtblonetwo").jexcel('destroy');
                $("#bomsourcingdetailsgrid").jexcel('destroy');
                $("#bomsamplingappr_grid").jexcel('destroy');
            }

            console.log(data.ShoItemDesc,'data.ShoItemDesc');
            console.log(GlbSizeChartSizes,'GlbSizeChartSizes');
            GlbItemDesc = data.ShoItemDesc;
            GlbSizeChartSizes = data.SizeChartSizes;
            GlbItemCode = data.ShoItemCode;
            GlbItemColorCode = data.ShoItemColorCode;
            GlbSizeDim = data.ShoSizeDim;
            GlbUofm = data.ShoUom;
            console.log(GlbItemDesc, 'GlbItemDesc');

            $("#bomconslidatedShortage").jexcel({
                columns:[
                    { type:'dropdown',title:'Item Description / Blend (%) / Content / Material', width:300,source: GlbItemDesc },
                    { type:'dropdown',title:'Gar. Size', width:70,source: GlbSizeChartSizes },
                    { type:'dropdown',title:'Item Code', width:100,source: GlbItemCode },
                    { type:'dropdown',title:'Item Color Code', width:100,source: GlbItemColorCode },
                    { type:'dropdown',title:'Size / Dim. (W*L*H)', width:100,source: GlbSizeDim },
                    { type:'dropdown',title:'UOM', width:100,source: GlbUofm },
                    { type:'text',title:'Req. BOM Qty.', width:100 },
                    { type:'text',title:'Ex. Qty. (%)', width:100 },
                    { type:'text',title:'Ex. Qty. (Nos.)', width:100 },
                    { type:'text',title:'Shortage BOM Qty.', width:100 },
                    { type:'dropdown',title:'UOM', width:100 },
                ],
                minDimensions: [11,1],
                allowInsertColumn: false
            });
            $("#bomsourcingdetailsgridShortage").jexcel({
                columns:[
                    { type:'dropdown',title:'Item Description / Blend (%) / Content / Material', width:300,source: GlbItemDesc },
                    { type:'dropdown',title:'Gar. Size', width:70,source: GlbSizeChartSizes },
                    { type:'dropdown',title:'Item Code', width:100,source: GlbItemCode },
                    { type:'dropdown',title:'Sourcing Advice', width:100,source: GlbItemCode },
                    { type:'text',title:'Vendor Location', width:100 },
                    { type:'text',title:'Vendor Name / Address', width:110 },
                    { type:'text',title:'GST / IE code Details', width:80 },
                    { type:'text',title:'Contact Details: Person / E-mail Id / Phone / Mobile', width:120 },
                    { type:'text',title:'If On-line Ordering System: Website / Userid / Password', width:120 },
                    { type: 'calendar', title:'P.W. Expiry Date', options: {format: 'DD/MM/YYYY'}, width:80 },
                ],
                minDimensions: [10,1],
                allowInsertColumn: false
            });

        }

    }

    //
    var GlbApprovalType = '';
    var GlbMgmtRemarks = '';
    var GlbApproveReject = '';

    //

</script>
<?php $this->load->view(CNFCOMPANY . 'template/pagefooter'); ?>