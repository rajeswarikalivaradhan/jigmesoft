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
                <h1 class="firstHeading">CAD INDENT DETAILS</h1>
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
                    <div class="box-header with-border">
                        <h1 class="box-title">SAMPLE REQUEST</h1>
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
                        </div>
                    </div>

                    <!--Indent Grids -->
                    <div id="indentgrids">
                        <div class="box-header with-border">
                            <h3 class="box-title">CAD - MATERIAL INDENT</h3>
                        </div>
                        <div class="box box-info">
                            <div class="box-body">
                                <div id="cadIndentHere"></div>
                                <form class="form-horizontal" id="frmBasicBomMatIndent">
                                    <div class="box-body pdl0">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="col-sm-4 control-label">Material Indent Ref. No.</label>
                                                <div class="col-sm-8">
                                                    <input type="text" class="form-control" readonly id="BomMatIndRefNo"
                                                           style="height: 45px" value="<?php echo $ArrBasicInfo->cad_mat_ind_ref_no; ?>">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="col-sm-4 control-label">Issue to Dept.</label>
                                                <div class="col-sm-8">
                                                    <input type="text" class="form-control" readonly
                                                           value="<?php echo $ArrBasicInfo->cadissuedto; ?>">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="col-sm-4 control-label">Request Date & Time</label>
                                                <div class="col-sm-8">
                                                    <input type="text" class="form-control" readonly
                                                           value="<?php echo dateTimeHelp($ArrBasicInfo->datecreated,false); ?>">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="col-sm-4 control-label">Cutoff Date & Time</label>
                                                <div class="col-sm-8">
                                                    <input type="text" class="form-control" readonly
                                                           value="<?php echo dateTimeHelp($ArrBasicInfo->cadindentcutoffdatetime,false) ?>">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <!--Indent Grids ENDS -->

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
    var GlbOrderId = '<?php echo $VarOrderId ?>';
    var jsonSamReqGrid = '<?php echo $jsonSamReqGrid ?>';
    var GlbRequestId = '<?php echo $VarRequestId ?>';
    
    //TODO there is one hidden file in this jxl
    var jxlSampleReq = jexcel(document.getElementById('jxlSampleReq'), {
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
            {type: 'text', title: 'Qty.', width: 70, readOnly: true},
            /*{type: 'text', title: 'Assigned SAMPLE Ref. No.', width: 120, readOnly: true, wordWrap: true},
            {type: 'hidden'}*/
        ],
        columnDrag: true,
        allowInsertColumn: false,
        allowInsertRow: false,
        data: JSON.parse(jsonSamReqGrid)
    });

    var sampleRequestCount = $("#jxlSampleReq").find("tbody tr").length;

    MakePostRequest(base_path+GlbCompanyFdr+"mcaduser/getCadIndents","requestid="+GlbRequestId,"json",function (data) {
        console.log(data,'data');
        var c = 1;
        $.each(data.cadIndentGrid,function (index,cadValue) {
            console.log(cadValue,'c');
            $("#cadIndentHere").append('<div id="gridCadIndent_'+c+'" class="table table-responsive"></div>');
            jexcel(document.getElementById('gridCadIndent_'+c), {
                columns: [
                    {type: 'text', title: 'CAD Ref. No.', width: 400, readOnly: true},
                    {type: 'text', title: 'Requirement', width: 200, readOnly: true},
                    {type: 'text', title: 'Required Size(s)', width: 80, readOnly: true},
                    {type: 'text', title: 'No. of Sizes Issued', width: 150, readOnly: true},
                    {type: 'text', title: 'Total No. of Parts Issued', width: 100, readOnly: true,},
                    {type: 'text', title: 'Total No. of Parts Returned', width: 150, readOnly: true,},
                    {type: 'text', title: 'Parts Returned Status', width: 200, readOnly: true},
                ],
                columnDrag: true,
                allowInsertColumn: false,
                allowInsertRow: false,
                data: JSON.parse(cadValue)
            });
            c++;
        });
    });
</script>
<?php $this->load->view(CNFCOMPANY . 'template/pagefooter'); ?>