<?php $this->load->view(CNFCOMPANY . 'template/pageheader'); ?>
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/uploadfile-order.css"/>
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
    <div class="content-wrapper order-entry">
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
                                <h1 class="box-title">BOM REQUEST</h1>
                                <div class="box-tools pull-right">
                                    <select class="form-control" id="frmBomConsType"
                                            onchange="fnChangeArtTypeGetGridBOMData()">
                                        <option value="">Choose</option>
                                        <option value="1">BOM Article 1</option>
                                        <option value="2">BOM Article 2</option>
                                        <option value="3">BOM Article 1 Shortage</option>
                                        <option value="4">BOM Article 2 Shortage</option>
                                    </select>
                                    <div class="herr" id="ErrfrmBomConsType"></div>
                                </div>
                            </div><!-- /.box-header -->
                        </div>
                        <div class="box-body">
                            <!--Content-->
                            <div id="bomconslidatedtwelfthtblonetwo"></div>
                            <div id="bomconslidatedShortage" class="table"></div>
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
                    <div class="box">
                        <form class="form-horizontal">
                            <div class="box-body box-bodyPd2010">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-4 control-label">Purpose</label>
                                        <div class="col-sm-8">
                                            <?php
                                            //echo '<pre>'; print_r($ArrBasicInfo);
                                            //print_r($ArrObjPurpose); die;
                                            ?>
                                            <select name="" id="frmBasicPurpose" class="form-control">
                                                <option value="">Choose</option>
                                                <?php
                                                foreach ($ArrPurpose as $VarKey => $value) { ?>
                                                    <option
                                                        value="<?php echo $value ?>" <?php if (!empty($ArrBasicInfo->purpose)) echo $ArrBasicInfo->purpose == $value ? 'selected' : '' ?>><?php echo $value ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-4 control-label">Request Type</label>
                                        <div class="col-sm-8">
                                            <?php $ArrRequestTimeType = unserialize(ARRREQUESTTYPE) ?>
                                            <select name="" id="frmBasicRequestType" class="form-control">
                                                <option value="">Choose</option>
                                                <?php
                                                foreach ($ArrRequestTimeType as $keyid => $item) {
                                                    ?>
                                                    <option
                                                        value="<?php echo $keyid ?>" <?php if (!empty($ArrBasicInfo->requesttype)) echo $ArrBasicInfo->requesttype == $keyid ? 'selected' : '' ?>><?php echo $item ?></option>
                                                    <?php
                                                }
                                                ?>
                                                <!--<option value="1" <?php /*if(isset($ArrBasicInfo->requesttype)) echo $ArrBasicInfo->requesttype == 1 ? 'selected' : '' */ ?>>Normal</option>
                                                    <option value="2" <?php /*if(isset($ArrBasicInfo->requesttype)) echo $ArrBasicInfo->requesttype == 2 ? 'selected' : '' */ ?>>Regular.</option>
                                                    <option value="3" <?php /*if(isset($ArrBasicInfo->requesttype)) echo $ArrBasicInfo->requesttype == 3 ? 'selected' : '' */ ?>>Priority</option>
                                                    <option value="4" <?php /*if(isset($ArrBasicInfo->requesttype)) echo $ArrBasicInfo->requesttype == 4 ? 'selected' : '' */ ?>>H. Priority</option>
                                                    <option value="5" <?php /*if(isset($ArrBasicInfo->requesttype)) echo $ArrBasicInfo->requesttype == 5 ? 'selected' : '' */ ?>>Immed.</option>-->
                                            </select>
                                            <div class="herr" id="ErrRequestType"></div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-4 control-label">Request Date &
                                            Time</label>
                                        <div class="col-sm-8">
                                            <input type='text' class="form-control"
                                                   value="<?php if (!empty($ArrBasicInfo->requestdt)) echo date('d-m-Y H:i:s', strtotime($ArrBasicInfo->requestdt)); else echo date('d-m-Y H:i:s'); ?>"/>
                                            <div class="herr" id=""></div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-4 control-label">CuttOff Date &<br/>Time</label>
                                        <div class="col-sm-8">
                                            <div class='input-group date' id='cutoffdatetimepicker'>
                                                <input type='text' class="form-control" id="frmBasicCutoffdatetime"
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
                                                      placeholder="Merchant Note"><?php if (!empty($ArrBasicInfo->merchantnote)) echo $ArrBasicInfo->merchantnote ?></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-4 control-label">Authorization
                                            Status</label>
                                        <div class="col-sm-8">
                                            <?php
                                            $VarCs = '';
                                            if (@$mgmtcurrentstatus == 1) {
                                                $VarCs = 'AUTHORIZATION PENDING';
                                            } elseif (@$mgmtcurrentstatus == 2) {
                                                $VarCs = 'AUTHORIZED';
                                            } elseif (@$mgmtcurrentstatus == 3) {
                                                $VarCs = 'NOT AUTHORIZED';
                                            } elseif (@$mgmtcurrentstatus == 4) {
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
                                                <option value=""></option>
                                                <?php
                                                foreach ($ArrApprovalType as $KeyId => $value) {
                                                    ?>
                                                    <option
                                                        value="<?php echo $KeyId ?>" <?php if (!empty($ArrBasicInfo->approvaltype)) if ($ArrBasicInfo->approvaltype == $KeyId) echo 'selected' ?>>
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
                                        <?php $ArrApprovalType = unserialize(ARRREQUESTTYPE) ?>
                                        <div class="col-sm-8">
                                            <input type="text" id="frmAuthDateTime" readonly class="form-control"
                                                   value="<?php if (!empty($ArrBasicInfo->queueno_assigned_date)) echo date('d-m-Y H:i:s', strtotime($ArrBasicInfo->queueno_assigned_date)); ?>">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="enqdate" class="col-sm-4 control-label">Management Remarks</label>
                                        <div class="col-sm-8">
                                            <textarea readonly class="form-control"
                                                      style="height: 64px"><?php if (!empty($mgmtremarks)) echo $mgmtremarks ?></textarea>
                                        </div>
                                    </div>
                                    <!--<div class="form-group">
                                                <label for="inputEmail3" class="col-sm-4 control-label">Authorization Type</label>
                                                <div class="col-sm-8">
                                                    <input type="text" name="frmBasicName" class="form-control" id="frmBasicName" placeholder="Name" value="<?php /**/ ?>">
                                                    <div class="herr" id="ErrfrmBasicName"></div>
                                                </div>
                                            </div>-->
                                    <div class="form-group">
                                        <label for="enqdate" class="col-sm-4 control-label">Request Status</label>
                                        <div class="col-sm-8">
                                            <?php
                                            ?>
                                            <select name="" id="frmCadDeptAcceptReject" class="form-control" disabled>
                                                <option value="">Choose</option>
                                                <option
                                                    value="1" <?php if (@$ArrBasicInfo->caddeptcurrentstatus == 1) echo 'selected' ?>>
                                                    REQUEST PENDING
                                                </option>
                                                <option
                                                    value="2" <?php if (@$ArrBasicInfo->caddeptcurrentstatus == 2) echo 'selected' ?>>
                                                    ACCEPTED
                                                </option>
                                                <option
                                                    value="3" <?php if (@$ArrBasicInfo->caddeptcurrentstatus == 3) echo 'selected' ?>>
                                                    DECLINED
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="enqdate" class="col-sm-4 control-label">Assign Purchase Queue.
                                            No</label>
                                        <div class="col-sm-8">
                                            <input type="text" name="" class="form-control" id="" readonly
                                                   value="<?php echo @$ArrBasicInfo->queueno ?>">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="enqdate" class="col-sm-4 control-label">Queue No. Assigned Date &
                                            Time</label>
                                        <div class="col-sm-8">
                                            <input type="text" name="" class="form-control" id="" readonly
                                                   value="<?php if (!empty($ArrBasicInfo->queueno_assigned_date)) echo date('d-m-Y H:i:s', strtotime($ArrBasicInfo->queueno_assigned_date)); ?>">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="enqdate" class="col-sm-4 control-label">Purchase Dept.
                                            Remarks</label>
                                        <div class="col-sm-8">
                                            <textarea readonly class="form-control"
                                                      style="height: 64px"><?php if (!empty($purdeptremarks)) echo $purdeptremarks ?></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="enqdate" class="col-sm-4 control-label">Current Status</label>
                                        <div class="col-sm-8"><span class="form-control" id="recentupdate"
                                                                    readonly="readonly"><?php if (!empty($ArrBasicInfo->dateupdated))
                                                    echo date('d-m-Y H:i:s', strtotime($ArrBasicInfo->dateupdated)) ?></span>
                                            <span class="form-control hide" id="recentupdateCs"
                                                  readonly="readonly"></span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="enqdate" class="col-sm-4 control-label">Recent Update</label>
                                        <div class="col-sm-8"><span class="form-control" id="recentupdate"
                                                                    readonly="readonly"><?php if (!empty($ArrBasicInfo->dateupdated))
                                                    echo date('d-m-Y H:i:s', strtotime($ArrBasicInfo->dateupdated)) ?></span>
                                            <span class="form-control hide" id="recentupdateCs"
                                                  readonly="readonly"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="box-body box-bodyPd2010">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="" class="col-sm-3">Merchant Attachments</label>
                                        <div id="uploadbompurchaserequest" class="pdt10"></div>

                                    </div>
                                </div>
                                <div class="box-footer">
                                    <button type="submit" class="btn btn-info pull-right addrights"
                                            onclick="return fnSaveChanges();">Save Changes
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="alert alert-success alert-dismissable hide" id="divSuccessBasicInfoMsg"></div>
                </div>
            </div>
        </section><!-- /.content -->
    </div><!-- /.content-wrapper -->
    <?php $this->load->view(CNFCOMPANY . 'template/templatefooter'); ?>
    <div class="control-sidebar-bg"></div>
</div><!-- ./wrapper -->
<script>
</script>
<script src="<?php echo base_url(); ?>assets/js/moment.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/jquery.uploadfile.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/bootstrap-datetimepicker.js"></script>
<script src="<?php echo base_url(); ?>assets/js/jexcel4/jsuites.js"></script>
<script src="<?php echo base_url(); ?>assets/js/jexcel4/jexcel.js"></script>
<script>
    var GlbId = '<?php echo $VarId ?>';
    var GlbOrderId = '<?php echo $VarOrderId ?>';
    var GlbBomConsolidated = 0;
    var GlbBomsourcingdetail = 0;
    var GlbSamplingAppr = 0;
    var GlbItemDesc = 0;
    var GlbSizeChartSizes = 0;
    var GlbItemCode = 0;
    var GlbItemColorCode = 0;
    var GlbSizeDim = 0;
    var GlbUofm = 0;
    var GlbArtType = 0;
    function fnChangeArtTypeGetGridBOMData() {
        let articleType = $("#frmBomConsType").val();
        localStorage.setItem("bomPurchaseArticleType", articleType);
        window.location.reload();
    }
    function selectElement(id, valueToSelect) {
        let element = document.getElementById(id);
        element.value = valueToSelect;
    }
    if (localStorage.getItem("bomPurchaseArticleType", GlbArtType)) {
        let articleType = localStorage.getItem("bomPurchaseArticleType");
        selectElement('frmBomConsType', articleType);
        if (articleType > 2) {
            MakeAsynPostRequest(base_path + 'mpurchase/getBomForBOMPurResShortages', "rfrom=1&at=" + articleType + "&refid=" + GlbOrderId,
                'json', fnGetGridBOMDataRes);
        }
        else {
            MakeAsynPostRequest(base_path + 'mpurchase/getAddeditBOMDatas', "rfrom=1&at=" + articleType + "&refid=" + GlbOrderId, 'json',
                fnGetGridBOMDataRes);
        }
        GlbBomConsolidated = 0;
        GlbBomsourcingdetail = 0;
        GlbSamplingAppr = 0;
    }
    function fnGetGridBOMDataRes(data) {
        if (localStorage.getItem("bomPurchaseArticleType")) {
            GlbArtType = localStorage.getItem("bomPurchaseArticleType");
            console.log(data, 'fnGetGridBOMDataRes data');
            if (data.errcode == 1) {
                GlbBomConsolidated = data.bomConsolidated;
                GlbBomsourcingdetail = data.bomSourcingDetail;
                GlbSamplingAppr = data.ArrSamplingAppr;
                uom = data.unitofmeasure;
                console.log(GlbBomConsolidated, 'GlbBomConsolidated');
                console.log(GlbArtType, 'GlbArtType');
                if (GlbArtType == 1 || GlbArtType == 2) {
                    $("#bomsamplingapprvaldiv").show();
                    $("#bomconslidatedtwelfthtblonetwo").jexcel({
                        colHeaders: ['Item Description / Blend (%) /<br/>Content / Material', 'Gar.<br/>Size', 'Item Code', 'Item Color<br/>Code', 'Size / Dim.<br/>(W*L*H)', 'Unit of<br/>Measure',
                            'Consld.<br/>BOM<br/>Reqd. Qty.', 'Ex. Qty.<br/>(%)', 'Ex. Qty.<br/>Nos.', 'Plan. BOM Qty.', 'Unit of<br/>Measure'],
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
                            {
                                title: 'Item Description / Blend (%) /<br/>Content / Material',
                                type: 'text',
                                width: 300,
                                wordWrap: true,
                                readOnly: true
                            },
                            {title: 'Gar. Size', type: 'text', wordWrap: true, width: 70, readOnly: true},
                            {title: 'Item Code', type: 'text', wordWrap: true, width: 100, readOnly: true},
                            {title: 'Item Color Code', type: 'text', wordWrap: true, width: 100, readOnly: true},
                            {title: 'Sourcing<br/>Advice', type: 'text', wordWrap: true, width: 110, readOnly: true},
                            {title: 'Vendor<br/>Location', type: 'text', wordWrap: true, width: 80, readOnly: true},
                            {
                                title: 'Vendors<br/>Name / Address',
                                type: 'text',
                                wordWrap: true,
                                width: 120,
                                readOnly: true
                            },
                            {
                                title: 'GST /<br/>IE code<br/>Details',
                                type: 'text',
                                wordWrap: true,
                                width: 80,
                                readOnly: true
                            },
                            {
                                title: 'If On-line<br/>Ordering System:<br/>Website / User ID /<br/>Password',
                                type: 'text',
                                width: 120,
                                wordWrap: true,
                                readOnly: true
                            },
                            {
                                title: 'P.W. Expiry<br/>Date',
                                type: 'calendar',
                                options: {format: 'DD/MM/YYYY'},
                                width: 130,
                                readOnly: true
                            },
                            {
                                title: 'Contact : Person / E-mail Id<br/>/ Phone / Mobile',
                                type: 'text',
                                width: 80,
                                wordWrap: true,
                                readOnly: true
                            }
                        ],
                        data: JSON.parse(GlbBomsourcingdetail)
                    });
                    console.log(GlbSamplingAppr, 'GlbSamplingAppr');
                    //
                    jexcel(document.getElementById("bomsamplingappr_grid"), {
                        allowInsertColumn: false,
                        columns: [
                            {
                                title: 'Item Description / Blend (%) / Content / Material',
                                width: 250,
                                wordWrap: true,
                                readOnly: true
                            },
                            {title: 'Category', width: 80, wordWrap: true, readOnly: true},
                            {title: 'Sample Sub.\nfor Approval', width: 80, wordWrap: true, readOnly: true},
                            {title: 'Sample\nSub. Size', width: 70, wordWrap: true, readOnly: true},
                            {title: 'Reqd. No.\nof Samples', width: 80, wordWrap: true, readOnly: true},
                            {title: 'Sample Sub. Date', width: 80, wordWrap: true, readOnly: true},
                            {title: 'Approval\nStatus', width: 80, wordWrap: true, readOnly: true},
                            {title: 'Approved Sample /\nItem Code', width: 140, wordWrap: true, readOnly: true},
                            {title: 'Approved Sample /\nItem Color Code', width: 140, wordWrap: true, readOnly: true},
                            {title: 'Approved By', width: 100, readOnly: true},
                            {title: 'Approved Date', width: 80, readOnly: true},
                            //{title: 'Remarks', width: 177, wordWrap: true, readOnly: true },
                        ],
                        data: GlbSamplingAppr
                    });
                    //
                }
                else {
                    $("#bomsamplingapprvaldiv").hide();
                    console.log(GlbArtType, 'GlbArtType');
                    console.log(data.ShoItemDesc, 'data.ShoItemDesc');
                    console.log(GlbSizeChartSizes, 'GlbSizeChartSizes');
                    GlbItemDesc = data.ShoItemDesc;
                    GlbSizeChartSizes = data.SizeChartSizes;
                    GlbSizeChartSizes = getUnique(GlbSizeChartSizes);
                    GlbItemCode = data.ShoItemCode;
                    GlbItemColorCode = data.ShoItemColorCode;
                    GlbSizeDim = data.ShoSizeDim;
                    GlbUofm = data.ShoUom;
                    GlbUofm = getUnique(GlbUofm);
                    //console.log(GlbItemDesc, 'GlbItemDesc');
                    jexcel(document.getElementById("bomconslidatedShortage"), {
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
                            {type: 'dropdown', title: 'UOM', width: 100, source: GlbUofm},
                        ],
                        data: [
                            []
                        ],
                        allowInsertColumn: false
                    });
                    console.log(GlbBomsourcingdetail, 'GlbBomsourcingdetail');
                    jexcel(document.getElementById("bomsourcingdetailsgridShortage"), {
                        columns: [
                            {
                                title: 'Item Description / Blend (%) / Content / Material',
                                width: 300,
                                wordWrap: true,
                                readOnly: true
                            },
                            {title: 'Gar. Size', width: 70, wordWrap: true, readOnly: true},
                            {title: 'Item Code', width: 100, wordWrap: true, readOnly: true},
                            {title: 'Item Color Code', width: 100, wordWrap: true, readOnly: true},
                            {title: 'Sourcing Advice', width: 100, wordWrap: true, readOnly: true},
                            {title: 'Vendor Location', width: 100, wordWrap: true, readOnly: true},
                            {title: 'Vendor Name / Address', width: 110, wordWrap: true, readOnly: true},
                            {title: 'GST / IE code Details', width: 80, wordWrap: true, readOnly: true},
                            {
                                title: 'Contact Details: Person / E-mail Id / Phone / Mobile',
                                width: 120,
                                wordWrap: true,
                                readOnly: true
                            },
                            {
                                title: 'If On-line Ordering System: Website / Userid / Password',
                                width: 120,
                                wordWrap: true,
                                readOnly: true
                            },
                            {
                                type: 'calendar',
                                title: 'P.W. Expiry Date',
                                options: {format: 'DD/MM/YYYY'},
                                width: 80,
                                wordWrap: true,
                                readOnly: true
                            },
                        ],
                        data: JSON.parse(GlbBomsourcingdetail)
                    });
                }
            }
            else {
                alert(data.msg);
            }
        }
    }
    function fnSaveChanges() {
        if (GlbArtType == '') {
            $('#ErrfrmBomConsType').html("Please select article type");
            $('#frmBomConsType').focus();
            $('#frmBomConsType').css("border", "1px solid #B94A48");
            return false;
        }
        var Pur = $("#frmBasicPurpose").val();
        var reqType = $("#frmBasicRequestType").val();
        if (reqType == "") {
            $('#ErrRequestType').text("Select Request Type");
            $('#frmBasicRequestType').focus();
            $('#frmBasicRequestType').css("border", "1px solid #B94A48");
            return false;
        }
        var Cutoff = $("#frmBasicCutoffdatetime").val();
        var mernote = $("#frmBasicmerNote").val();
        var isriorcode = $("#frmBasicWipRefNo").text();
        if (GlbArtType > 2) {
            var bomConsolidatedShortage = $("#bomconslidatedShortage").jexcel('getData');
        }
        else {
            var bomConsolidatedShortage = '';
        }
        var param = "rfrom=1&id=" + GlbId + "&oid=" + GlbOrderId + "&at=" + GlbArtType + "&pur=" + Pur + "&reqt=" + reqType + "&coff=" +
            Cutoff + "&mn=" + mernote + "&isrior=" + isriorcode + "&bomConsolidatedShortage=" + JSON.stringify(bomConsolidatedShortage);
        MakePostRequest(base_path + 'mpurchase/updateBOMPurchasereq', param, 'json', fnSaveChangesRes);
        return false;
    }
    function fnSaveChangesRes(data) {
        if (data != '') {
            localStorage.removeItem("bomPurchaseArticleType");
            console.log(data, 'data');
            if (data.errcode == '404') {
                fnCallSessionExpire();
                return false;
            } else if (data.errcode == '-1') {
                return false;
            } else if (data.errcode == 1) {
                GlbId = data.id;
                extraObj.startUpload();
                $("#divSuccessBasicInfoMsg").removeClass('hide');
                $("#divSuccessBasicInfoMsg").html("BOM Purchase Request has updated at successfully!");
                fnRedirectPageTimeOut(base_path + 'merchant/manageallrequest');
            }
        }
    }
    $(function () {
        $('#cutoffdatetimepicker').datetimepicker({
            format: 'DD-MM-YYYY HH:mm:ss'
        });
        extraObj = $("#uploadbompurchaserequest").uploadFile({
            dragDrop: true,
            multiple: true,
            url: base_path + 'dashboard/commonBasicFileUpload',
            returnType: "json",
            fileName: "myfile",
            dynamicFormData: function () {
                return "id=" + GlbId + "&folderName=bomrequest/";
            },
            autoSubmit: false
        });
        console.log(extraObj, 'extraObj');
    });
</script>
<?php $this->load->view(CNFCOMPANY . 'template/pagefooter'); ?>