<?php $this->load->view(CNFCOMPANY . 'template/pageheader'); ?>
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
        <!-- Content Header (Page header) -->
        <div class="col-md-12">
            <section class="content-header">
                <h1 class="firstHeading">SAMPLE REQUEST</h1>
            </section>
        </div>
        <section class="content">
            <!-- Default box -->
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-info">
                        <div class="box-body" style="padding: 0">
                            <?php $this->load->view('commonBasicInfoOrderEntry'); ?>
                        </div>
                    </div>
                    <div class="box box-info">
                        <div class="box-body table-responsive">
                            <div id="jxlSampleReq" class="table table-responsive"></div>
                        </div>
                        <div class="box-body">
                            <!--Content-->
                            <form class="form-horizontal" name="frmBasicInfo" id="frmBasicInfo" method="post"
                                  autocomplete="off">
                                <div class="alert alert-success alert-dismissable hide" id="divSuccessBasicInfoMsg"></div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-4 control-label">Request Type</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" readonly
                                                   value="<?php echo @$ArrBasicInfo->requesttype ?>">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-4 control-label">Request Date &
                                            Time</label>
                                        <div class="col-sm-8">
                                            <input type="text" name="" class="form-control" id="frmBasicReqDate" readonly
                                                   value="<?php if (empty($ArrBasicInfo->requestdt)) echo ''; else echo date('d-m-Y H:i:s', strtotime($ArrBasicInfo->requestdt)); ?>">
                                            <div class="herr" id="ErrfrmBasicReqDate"></div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-4 control-label">CutOff Date &
                                            Time</label>
                                        <div class="col-sm-8">
                                            <input type='text' class="form-control" readonly id=""
                                                   value="<?php if (isset($ArrBasicInfo->cutoffdatetime)) echo date('d-m-Y H:i:s', strtotime($ArrBasicInfo->cutoffdatetime)) ?>"/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-4 control-label">Merchant
                                            Note</label>
                                        <div class="col-sm-8">
                                                <textarea id="" readonly class="form-control"
                                                          style="height: 65px"><?php if (isset($ArrBasicInfo->merchantnote)) echo $ArrBasicInfo->merchantnote ?></textarea>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-4 control-label">
                                            Authorization Status
                                        </label>

                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" readonly
                                                   value="<?php echo ($ArrBasicInfo->mgmtcurrentstatus == 2) ? 'AUTHORIZED' : 'NOT AUTHORIZED'; ?>">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-4 control-label">
                                            Authorization Type
                                        </label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" readonly value="<?php echo @$ArrBasicInfo->approvaltype ?>">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">Authorized By</label>
                                        <div class="col-sm-8">
                                            <?php

                                            ?>
                                            <input type="text" class="form-control" readonly value="<?php if(!empty($AuthorizedByInfo[0])) echo $AuthorizedByInfo[0]['contactname'] ?>">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="enqdate" class="col-sm-4 control-label">Management
                                            Remarks</label>
                                        <div class="col-sm-8">
                                        <textarea class="form-control"
                                                  id="frmBasicMgmtRemarks" readonly style="height: 64px"><?php if (!empty($ArrBasicInfo->mgmtremarks)) echo $ArrBasicInfo->mgmtremarks ?></textarea>
                                            <div class="herr" id="ErrfrmBasicMgmtRemarks"></div>
                                        </div>
                                    </div>

                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="enqdate" class="col-sm-4 control-label">Request Status</label>
                                        <div class="col-sm-8">
                                            <?php
                                            ?>
                                            <select class="form-control" id="" disabled>
                                                <option value="">Choose</option>
                                                <option value="1" <?php if ($ArrBasicInfo->deptcurrentstatus == '1') echo 'selected' ?>>
                                                    REQUEST PENDING
                                                </option>
                                                <option value="2" <?php if ($ArrBasicInfo->deptcurrentstatus == '2') echo 'selected' ?>>
                                                    ACCEPT
                                                </option>
                                                <option value="3" <?php if ($ArrBasicInfo->deptcurrentstatus == '3') echo 'selected' ?>>
                                                    REJECT
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">Assign SAMPLE Queue. No</label>
                                        <div class="col-sm-8">
                                            <input type="text" name="" class="form-control" id="" readonly
                                                   value="<?php echo $ArrBasicInfo->queueno ?>">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="enqdate" class="col-sm-4 control-label">Queue No. Assigned Date
                                            &
                                            Time</label>
                                        <div class="col-sm-8">
                                            <input type="text" name="" class="form-control" id="" readonly
                                                   value="<?php if (empty($ArrBasicInfo->queueno_assigned_date)) echo ''; else echo date('d-m-Y H:i:s', strtotime($ArrBasicInfo->queueno_assigned_date)) ?>">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="enqdate" class="col-sm-4 control-label">
                                            CAD Dept. Remarks
                                        </label>
                                        <div class="col-sm-8">
                                                <textarea readonly class="form-control"
                                                          style="height: 64px"><?php if (!empty($ArrBasicInfo->deptremarks)) echo $ArrBasicInfo->deptremarks ?></textarea>
                                        </div>
                                    </div>
                                </div>
                            </form>
                            <!--Content Ends-->

                            <div class="box-header with-border">
                                <h3 class="box-title">BOM - MATERIAL INDENT</h3>
                            </div>
                            <div id="bomIndentJxl"></div>
                            <form class="form-horizontal" id="frmBasicFabMatIndent">
                                <div class="box-body pdl0">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label">Material Indent Ref.
                                                No.</label>
                                            <div class="col-sm-8">
                                                <input type="text" class="form-control" readonly value="">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label">Issue to Dept.</label>
                                            <div class="col-sm-8">
                                                <select class="form-control" id="FabMatIssuedToDept">
                                                    <option value="">Choose</option>
                                                    <?php
                                                    foreach ($ArrAllUsertypes as $key => $user) {
                                                        echo '<option value="' . $user . '">' . $user . '</option>';
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label">Request Date & Time</label>
                                            <div class="col-sm-8">
                                                <input type="text" class="form-control" readonly value="<?php
                                                if (!empty($fabIndentDetails))
                                                    echo dateTimeHelp($fabIndentDetails[0]['requestdt'], false);
                                                ?>">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label">Cutoff Date & Time</label>
                                            <div class="col-sm-8">
                                                <div class='input-group date' id='fabCutOffDt'>
                                                    <input type='text' class="form-control" id="frmBasicFabCutOffDt">
                                                    <span class="input-group-addon"><span
                                                            class="glyphicon glyphicon-calendar"></span></span>
                                                </div>
                                                <div class="herr" id="ErrFrmBasicFabCutOffDt"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </form>
                        </div>
                    </div>

                    <div class="box-body">
                        <div class="form-group">
                            <label for="enqdate" class="col-sm-3">Attachments</label>
                            <div class="form-group">
                                <div class="col-sm-12"
                                     style="border:2px dotted #A5A5C7; padding: 10px; background-color: #eee">
                                    <ul style="list-style: none;">
                                        <?php
                                        $VarFdr = UPLOADS_SLASH."samplerequest".DIRECTORY_SEPARATOR.$VarRequestId.DIRECTORY_SEPARATOR."Merchant".DIRECTORY_SEPARATOR;
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
                                                                $downUrl = base_url()."dashboard/commonSimpleDownload?id=".urlencode(base64_encode($VarRequestId))."&fileName=".urlencode($file)."&folder=samplerequest&by=Merchant" ?>&nbsp;<a href="<?php echo $downUrl ?>">
                                                                    <i class="fa fa-download fa-lg" aria-hidden="true"></i>
                                                                </a>&nbsp;&nbsp;
                                                                <?php
                                                                if(in_array($VarFileExt,$ArrDwnExtensions)) {
                                                                }
                                                                else {
                                                                    ?>
                                                                    <a href="<?php echo base_url()."dashboard/openFileInBrowser?id=".$VarRequestId."&fileName=".$file."&folder=samplerequest&by=Merchant" ?>" target="_blank">
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
                    </div>
<a href="<?php echo base_url('msamplerequest/bom_ind/48') ?>" class="btn btn-info">Next</a>
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
    var GlbOrderId = '<?php echo $VarOrderId ?>';
    var samReqGrid = '<?php echo $jsonSamReqGrid ?>';
    var GlbRequestId = '<?php echo $VarRequestId ?>';
    let jsonSamReqGrid = JSON.parse(samReqGrid);
    console.log(jsonSamReqGrid,'JSON.parse(jsonSamReqGrid)');
    colorGroupCboComp = {}; groupCcc = {}; groupCcc_Gparts = {}; groupCcc_GpartsBlend = {};
    groupCcc_GpartsBlendContent = {}; groupCcc_GpartsBlendContentFabric = {};
    groupCcc_GpartsBlendContentFabricGsm = {}; groupCcc_GpartsBlendContentFabricGsmDyeingType = {};
    function fnPopulateValueArray(ArrName, KeyValue, InsertVal) {
        if (jQuery.inArray(KeyValue, ArrName)) {
            ArrName[KeyValue] = InsertVal + "|-|" + ArrName[KeyValue];
        }
        return ArrName;
    }

    varSampleReq = jexcel(document.getElementById('jxlSampleReq'), {
        columns: [
            {type: 'text', title: 'Combo', width: 110, readOnly: true},
            {type: 'text', title: 'Component', width: 110, readOnly: true},
            {type: 'text', title: 'Color', width: 110, readOnly: true},
            {type: 'text', title: 'P. O. No.', width: 110, readOnly: true},
            {type: 'text', title: 'Size Spec Code', width: 110, readOnly: true},
            {type: 'text', title: 'Requirement',width: 100, readOnly: true},
            {type: 'text', title: 'Purpose', width: 100, readOnly: true},
            {type: 'text', title: 'Category', width: 100, readOnly: true},
            {type: 'text', title: 'If Revised or In-line Pre. SAMPLE Ref. No.', width: 150, eadOnly: true},
            {type: 'text', title: 'Required Size(s)', width: 70, readOnly: true},
            {type: 'text', title: 'Qty.', width: 70, readOnly: true}
        ],
        columnDrag: true,
        allowInsertColumn: false,
        allowInsertRow: false,
        data: jsonSamReqGrid
    });
    Ccc = '';
    ArrSampleReq = varSampleReq.getData();
    for(let j = 0; j < ArrSampleReq.length; j++) {
        Ccc = ArrSampleReq[j][0]+'|#|'+ArrSampleReq[j][1]+'|#|'+ArrSampleReq[j][2]+'|#|' +ArrSampleReq[j][3]+ '|#|' + ArrSampleReq[j][4];
    }
    MakeAsynPostRequest(base_path+"msamplerequest/bom_ind","rFrom=1&enqId="+GlbOrderId+"&ccc="+Ccc,"json",function (data) {
        ArrBomItem = getUnique(data.ArrBomItem);
        ArrBISizes = getUnique(data.ArrBISizes);
        ArrBIItemCode = getUnique(data.ArrBIItemCode);
        ArrBIItemColorCode = getUnique(data.ArrBIItemColorCode);
        ArrBISizeDim = getUnique(data.ArrBISizeDim);
        ArrBIUom = getUnique(data.ArrBIUom);
        bomIndentBomItemFilter = function (instance, cell, c, r, source) {
            var tblId = instance.id.substring(instance.id.indexOf('_')+1);
            var cbo  = varSampleReq.getColumnData(0);
            var comp = varSampleReq.getColumnData(1);
            var col  = varSampleReq.getColumnData(2);
            var poNo = varSampleReq.getColumnData(3);
            var spc  = varSampleReq.getColumnData(4);
            //let bomItem = instance.jexcel.getValueFromCoords(c - 1,r);
            console.log(objBomItemGroup,'objBomItemGroup');
            let group = objBomItemGroup[cbo+"|#|"+comp+"|#|"+col+"|#|"+poNo+"|#|"+spc];
            console.log(group,'group bom itemfilter');
            if(group) {
                let ArrGroup = group.split('|-|');
                let bomItem = ArrGroup.filter((val)=> val !== "undefined");
                console.log(bomItem,'bomItem');
                return getUnique(bomItem);
            }
            else {
                return [];
            }
        };

        bomIndentSizeFilter = function (instance, cell, c, r, source) {
            let tblId = instance.id.substring(instance.id.indexOf('_')+1);
            let cbo  = sR[tblId][0];
            let comp = sR[tblId][1];
            let col  = sR[tblId][2];
            let poNo = sR[tblId][3];
            let spc  = sR[tblId][4];
            let bomItem = instance.jexcel.getValueFromCoords(c - 1,r);
            let group = objGarmentSizeGroup[cbo+"|#|"+comp+"|#|"+col+"|#|"+poNo+"|#|"+spc+"|#|"+bomItem];
            if(group) {
                let ArrGroup = group.split('|-|');
                let garmentSizes = ArrGroup.filter((val)=> val !== "undefined");
                return getUnique(garmentSizes);
            }
            else return [];
        };

        bomItemCodeFilter = function (instance, cell, c, r, source) {
            let tblId = instance.id.substring(instance.id.indexOf('_')+1);
            let cbo  = sR[tblId][0];
            let comp = sR[tblId][1];
            let col  = sR[tblId][2];
            let poNo = sR[tblId][3];
            let spc  = sR[tblId][4];
            let bomItem = instance.jexcel.getValueFromCoords(c - 2,r);
            let size = instance.jexcel.getValueFromCoords(c - 1,r);
            let group = objItemCodeGroup[cbo+"|#|"+comp+"|#|"+col+"|#|"+poNo+"|#|"+spc+"|#|"+bomItem+"|#|"+size];
            if(group) {
                let ArrGroup = group.split('|-|');
                let itemCode = ArrGroup.filter((val)=> val !== "undefined");
                return getUnique(itemCode);
            }
            else return [];
        };
        jexcel(document.getElementById('bomIndentJxl'), {
            columns: [
                {type: 'dropdown', title: 'Item Description / Blend (%) / Content / Material', width: 440, source: ArrBomItem, filter: bomIndentBomItemFilter},
                {type: 'dropdown', title: 'Gar. / Label Size', width: 70, source: ArrBISizes, wordWrap: true, filter: bomIndentSizeFilter},
                {type: 'dropdown', title: 'Item Code', width: 192, source: ArrBIItemCode, wordWrap: true, filter:bomItemCodeFilter},
                {type: 'dropdown', title: 'Item Colour Code', width: 190, source: ArrBIItemColorCode, wordWrap: true},
                {type: 'dropdown', title: 'Size / Dimension', width: 160, source: ArrBISizeDim, wordWrap: true},
                {type: 'dropdown', title: 'UOM', width: 100, source: ArrBIUom, wordWrap: true},
                {type: 'text', title: 'Material Indent Qty.', width: 100, wordWrap: true},
                {type: 'dropdown', title: 'UOM', width: 100, source: JSON.parse(GlbArrUnitOfMeasure), wordWrap: true},
            ],
            data: [
                []
            ]
        });

    });
    cadRefNoFilter = function(instance, cell, c, r, source) {
        var tblId = instance.id.substring(instance.id.indexOf('_')+1);
        var component = sR[tblId][1];
        var spc     = sR[tblId][4];
        console.log(GlbCadRefNoWithFilter,'GlbCadRefNoWithFilter');
        let ArrCadRefNoMix = GlbCadRefNoWithFilter[component+"|#|"+spc];
        console.log(ArrCadRefNoMix,'ArrCadRefNoMix');
        if(ArrCadRefNoMix) {
            return getUnique(ArrCadRefNoMix);
        }
        else {
            return [];
        }
    };
</script>
<?php $this->load->view(CNFCOMPANY . 'template/pagefooter'); ?>