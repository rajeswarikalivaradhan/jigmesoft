<?php $this->load->view(CNFCOMPANY . 'template/pageheader'); ?>
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/jexcel4/jsuites.css"/>
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/jexcel4/jexcel.css"/>
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
                                <h1 class="box-title">SAMPLE REQUEST</h1>
                                <div class="box-tools pull-right"></div>
                            </div>
                            <div id="jxlSampleReq" class="table"></div>
                        </div>
                        <div class="box-body">
                            <h4>ATTACHMENT & REFERENCE DETAILS:</h4>
                            <div id="samAttachmentReferenceJxl" class="table"></div>
                        </div>
                        <div class="box-body">
                            <h4 style="">SAMPLE STATUS & REFERENCE NO. ASSIGNED ON JOB COMPLETION</h4>
                            <div id="samStatusRefNoJxl"></div>
                        </div>
                        <div class="box-body">
                            <form class="form-horizontal" name="frmBasicInfo" id="frmBasicInfo" method="post"
                                  action="<?php echo base_url('msamplerequest/updateInfo') ?>">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-4 control-label">Request Type</label>
                                        <div class="col-sm-8">
                                            <?php $ArrApprovalType = unserialize(ARRREQUESTTYPE); ?>
                                            <input type="text" class="form-control" readonly value="<?php if(!empty($ArrBasicInfo->requesttype))
                                                echo $ArrBasicInfo->requesttype ?>">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-4 control-label">Request Date & Time</label>
                                        <div class="col-sm-8">
                                            <input type="text" name="" class="form-control" id="frmBasicReqDate" readonly
                                                   value="<?php if (!empty($ArrBasicInfo)) echo dateTimeHelp($ArrBasicInfo->datecreated, false); ?>">

                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-4 control-label">Cutoff Date & Time</label>
                                        <div class="col-sm-8">
                                            <div class='input-group date' id='datetimepicker1'>
                                                <input type='text' class="form-control" id="frmBasicCutoffdatetime"
                                                       value="<?php if (!empty($ArrBasicInfo)) echo dateTimeHelp($ArrBasicInfo->cutoffdatetime, false); ?>"/>
                                                <span class="input-group-addon"><span
                                                            class="glyphicon glyphicon-calendar"></span></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-4 control-label">Merchant Note</label>
                                        <div class="col-sm-8">
                                        <textarea id="frmBasicMerchantNote" class="form-control"
                                                  style="height: 64px"><?php if (!empty($ArrBasicInfo)) echo $ArrBasicInfo->merchantnote ?></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">Authorization
                                            Status</label>
                                        <div class="col-sm-8">
                                            <select class="form-control" disabled>
                                                <option value="1" <?php if($ArrBasicInfo->mgmtcurrentstatus == 1) echo 'selected' ?>>PENDING</option>
                                                <option value="2" <?php if($ArrBasicInfo->mgmtcurrentstatus == 2) echo 'selected' ?>>APPROVE</option>
                                                <option value="3" <?php if($ArrBasicInfo->mgmtcurrentstatus == 3) echo 'selected' ?>>DECLINE</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-4 control-label">Authorization
                                            Type</label>
                                        <div class="col-sm-8">
                                            <?php $ArrApprovalType = unserialize(ARRREQUESTTYPE); ?>
                                            <select name="" id="frmSamApprovalType" class="form-control" disabled>
                                                <option value="">Choose</option>
                                                <?php
                                                $selVal = '';
                                                foreach ($ArrApprovalType as $key => $approvaltype) {
                                                    if(!empty($ArrBasicInfo->approvaltype))
                                                        if($ArrBasicInfo->approvaltype == $approvaltype) $selVal = 'selected';
                                                    echo '<option value="" '.$approvaltype.'>'.$approvaltype.'</option>';
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">Authorized By</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" readonly value="<?php if(!empty($AuthorizedByInfo[0])) echo $AuthorizedByInfo[0]['contactname'] ?>">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="enqdate" class="col-sm-4 control-label">Management Remarks</label>
                                        <div class="col-sm-8">
                                        <textarea readonly class="form-control"
                                                  style="height: 64px"><?php if (!empty($ArrBasicInfo->mgmtremarks)) echo $ArrBasicInfo->mgmtremarks ?></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="enqdate" class="col-sm-4 control-label">Request Status</label>
                                        <div class="col-sm-8">
                                            <select name="" id="frmCadDeptAcceptReject" class="form-control" disabled>
                                                <option value="">Choose</option>
                                                <option value="1" <?php if ($ArrBasicInfo->deptcurrentstatus == 1) echo 'selected' ?>>
                                                    REQUEST PENDING
                                                </option>
                                                <option value="2" <?php if ($ArrBasicInfo->deptcurrentstatus == 2) echo 'selected' ?>>
                                                    ACCEPT
                                                </option>
                                                <option value="3" <?php if ($ArrBasicInfo->deptcurrentstatus == 3) echo 'selected' ?>>
                                                    REJECT
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="enqdate" class="col-sm-4 control-label">Sample Queue No.</label>
                                        <div class="col-sm-8">
                                            <input type="text" name="" class="form-control" id="" readonly
                                                   value="<?php echo $ArrBasicInfo->queueno ?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="enqdate" class="col-sm-4 control-label">Queue No. Assigned Date &
                                            Time</label>
                                        <div class="col-sm-8">
                                            <input type="text" name="" class="form-control" id="" readonly
                                                   value="<?php if(!empty($ArrBasicInfo)) echo dateTimeHelp($ArrBasicInfo->queueno_assigned_date,false); ?>">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="enqdate" class="col-sm-4 control-label">Sample Dept. Remarks</label>
                                        <div class="col-sm-8">
                                        <textarea readonly class="form-control"
                                                  style="height: 64px"><?php if (!empty($ArrBasicInfo->deptremarks)) echo $ArrBasicInfo->deptremarks; ?></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">Current Status</label>
                                        <div class="col-sm-8">
                                            <?php
                                            $VarCs = '';
                                            if(!empty($ArrBasicInfo->deptcurrentstatus)) {
                                                if($ArrBasicInfo->deptcurrentstatus == 1) {
                                                    $VarCs = 'REQUEST PENDING';
                                                }
                                                if($ArrBasicInfo->deptcurrentstatus == 2) {
                                                    $VarCs = 'REQUEST ACCEPTED';
                                                }
                                                if($ArrBasicInfo->deptcurrentstatus == 3) {
                                                    $VarCs = 'REQUEST REJECTED';
                                                }

                                            }
                                            if(!empty($ArrBasicInfo->mgmtcurrentstatus)) {
                                                if($ArrBasicInfo->mgmtcurrentstatus == 1) {
                                                    $VarCs = 'AUTHORIZATION PENDING';
                                                }
                                                if($ArrBasicInfo->mgmtcurrentstatus == 2) {
                                                    $VarCs = 'AUTHORIZATION APPROVED';
                                                }
                                                if($ArrBasicInfo->mgmtcurrentstatus == 3) {
                                                    $VarCs = 'AUTHORIZATION DECLINED';
                                                }

                                            }
                                            ?>
                                            <input type="text" id="CurrentStatus" class="form-control" readonly
                                                   value="<?php echo $VarCs ?>">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">Recent Update</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" readonly id="recentupdate"
                                                   value="<?php
                                                   echo dateTimeHelp(@$ArrBasicInfo->dateupdated,false) ?>">
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="box-body">
                            <div class="form-group">
                                <label for="" class="col-md-12">ATTACHMENTS</label>
                                <?php
                                if ($VarReqId == 0) {
                                    ?>
                                    <div class="col-sm-12">
                                        <div id="uploadsamplerequest" class="pdt10"></div>
                                    </div>
                                    <?php
                                } else { ?>
                                    <div class="form-group">
                                        <div class="col-sm-5"
                                             style="border:2px dotted #A5A5C7; padding: 10px; background-color: #eee">
                                            <ul class="list-group" style="list-style: none;">
                                                <?php
                                                $VarFdr = UPLOADS_SLASH."samplerequest".DIRECTORY_SEPARATOR.$VarReqId.DIRECTORY_SEPARATOR."Merchant".DIRECTORY_SEPARATOR;
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
                                                        $downUrl = base_url()."dashboard/commonSimpleDownload?id=".urlencode(base64_encode($VarReqId))."&fileName=".urlencode($file)."&folder=samplerequest&by=Merchant" ?>&nbsp;<a href="<?php echo $downUrl ?>">
                                                            <i class="fa fa-download fa-lg" aria-hidden="true"></i>
                                                        </a>&nbsp;&nbsp;
                                                        <?php
                                                        if(in_array($VarFileExt,$ArrDwnExtensions)) {

                                                        }
                                                        else {
                                                            ?>
                                                            <a href="<?php echo base_url()."dashboard/openFileInBrowser?id=".$VarReqId."&fileName=".$file."&folder=samplerequest&by=Merchant" ?>" target="_blank">
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
                                    <?php
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                            <div id="indentgrids">
                                <div class="box-header with-border">
                                    <h3 class="box-title">CAD - MATERIAL INDENT</h3>
                                </div>
                                <div class="box box-info">
                                    <div class="box-body">

                                        <div id="gridCadIndent" class=""></div>
                                        <div id="cadIndentHere"></div>
                                        <form class="form-horizontal" id="frmBasicCadMatIndent">
                                            <div class="box-body pdl0">
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="col-sm-4 control-label">Material Indent Ref. No.</label>
                                                        <div class="col-sm-8">
                                                            <input type="text" class="form-control" readonly value="
<?php if(!empty($IndentDetails['cad_mat_ind_ref_no']))
                                                                echo $IndentDetails['cad_mat_ind_ref_no']; ?>">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="col-sm-4 control-label">Issue to Dept.</label>
                                                        <div class="col-sm-8">
                                                            <input type="text" class="form-control" readonly value="<?php
                                                            if(!empty($IndentDetails['cadissuedto']))
                                                                echo $IndentDetails['cadissuedto']; ?>">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="col-sm-4 control-label">Request Date & Time</label>
                                                        <div class="col-sm-8">
                                                            <input type="text" class="form-control" readonly value="<?php
                                                             echo dateTimeHelp($ArrBasicInfo->datecreated,false); ?>">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="col-sm-4 control-label">Cutoff Date & Time</label>
                                                        <div class="col-sm-8">
                                                            <input type="text" class="form-control" readonly value="<?php if(!empty($IndentDetails['cadindentcutoffdatetime']))
                                                                echo dateTimeHelp($IndentDetails['cadindentcutoffdatetime'],false);
                                                            ?>">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        </form>
                                    </div>
                                </div>


                                <div class="tab">
                                    <div class="box-header with-border">
                                        <h3 class="box-title">FABRIC - MATERIAL INDENT</h3>
                                    </div>
                                    <div class="box box-info">
                                        <div class="box-body">

                                            <div id="gridFabIndent" class="table"></div>
                                            <div id="fabIndentHere"></div>
                                            <form class="form-horizontal" id="frmBasicFabMatIndent">
                                                <div class="box-body pdl0">
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label class="col-sm-4 control-label">Material Indent Ref.
                                                                No.</label>
                                                            <div class="col-sm-8">
                                                                <input type="text" class="form-control" readonly value="<?php if(!empty($IndentDetails['fab_mat_ind_ref_no']))
                                                                    echo $IndentDetails['fab_mat_ind_ref_no']; ?>">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label class="col-sm-4 control-label">Issue to Dept.</label>
                                                            <div class="col-sm-8">
                                                                <input type="text" class="form-control" readonly value="<?php if(!empty($IndentDetails['fabissuedto']))
                                                                    echo $IndentDetails['fabissuedto']; ?>">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label class="col-sm-4 control-label">Request Date & Time</label>
                                                            <div class="col-sm-8">
                                                                <input type="text" class="form-control" readonly value="<?php
                                                                 echo dateTimeHelp($ArrBasicInfo->datecreated,false); ?>">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label class="col-sm-4 control-label">Cutoff Date & Time</label>
                                                            <div class="col-sm-8">
                                                                <input type="text" class="form-control" readonly value="<?php if(!empty($IndentDetails['fabindentcutoffdatetime']))
                                                                    echo dateTimeHelp($IndentDetails['fabindentcutoffdatetime'],false);
                                                                ?>">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                                <div class="tab">
                                    <div class="box-header with-border">
                                        <h3 class="box-title">BOM - MATERIAL INDENT</h3>
                                    </div>
                                    <div class="box box-info">
                                        <div class="box-body">

                                            <div id="gridBomIndent" class="table"></div>
                                            <div id="bomIndentHere"></div>
                                            <form class="form-horizontal" id="frmBasicBomMatIndent">
                                                <div class="box-body pdl0">
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label class="col-sm-4 control-label">Material Indent Ref.
                                                                No.</label>
                                                            <div class="col-sm-8">
                                                                <input type="text" class="form-control" readonly value="<?php if(!empty($IndentDetails['bom_mat_ind_ref_no']))
                                                                    echo $IndentDetails['bom_mat_ind_ref_no']; ?>">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label class="col-sm-4 control-label">Issue to Dept.</label>
                                                            <div class="col-sm-8">
                                                                <input type="text" class="form-control" readonly value="<?php if(!empty($IndentDetails['bomissuedto']))
                                                                    echo $IndentDetails['bomissuedto']; ?>">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label class="col-sm-4 control-label">Request Date & Time</label>
                                                            <div class="col-sm-8">
                                                                <input type="text" class="form-control" readonly value="<?php
                                                                echo dateTimeHelp($ArrBasicInfo->datecreated,false) ?>">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label class="col-sm-4 control-label">Cutoff Date & Time</label>
                                                            <div class="col-sm-8">
                                                                <input type="text" class="form-control" readonly value="<?php if(!empty($IndentDetails['bomindentcutoffdatetime']))
                                                                    echo dateTimeHelp($IndentDetails['bomindentcutoffdatetime'],false);
                                                                ?>">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>


                </div>
            </div>


        </section>
    </div>
    <?php $this->load->view(CNFCOMPANY . 'template/templatefooter'); ?>
    <div class="control-sidebar-bg"></div>
</div>
<script src="<?php echo base_url(); ?>assets/js/moment.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/bootstrap-datetimepicker.js"></script>
<script src="<?php echo base_url(); ?>assets/js/jexcel4/jsuites.js"></script>
<script src="<?php echo base_url(); ?>assets/js/jexcel4/jexcel.js"></script>
<script>
    var GlbId = '<?php echo $VarReqId ?>';
    var GlbCurrentData = '<?php echo @$jsonDataGrid ?>';
    var jsonCurrentData = 0; var GlbSampleReqJxlGrid = 0;
    if (GlbCurrentData == '') {
        GlbCurrentData = 0;
    }
    else {
        var jsonCurrentData = JSON.parse(GlbCurrentData);
    }

    var GlbAttachmentRefJxl = '<?php echo @$ArrBasicInfo->attachment_jxl ?>';
    if(GlbAttachmentRefJxl != '') {
        console.log(GlbAttachmentRefJxl,'GlbAttachmentRefJxl');
        GlbAttachmentRefJxl = JSON.parse(GlbAttachmentRefJxl);
    }
    else {
        GlbAttachmentRefJxl = [[]];
    }

    jexcel(document.getElementById('jxlSampleReq'), {
        columns: [
            {type: 'text', title: 'Combo', width: 110, readOnly: true, wordWrap: true},
            {type: 'text', title: 'Component', width: 110, readOnly: true, wordWrap: true},
            {type: 'text', title: 'Color', width: 110, readOnly: true, wordWrap: true},
            {type: 'text', title: 'P. O. No.', width: 110, readOnly: true, wordWrap: true},
            {type: 'text', title: 'Size Spec Code', width: 110, readOnly: true, wordWrap: true},
            {type: 'text', title: 'Requirement', width: 100, readOnly: true, wordWrap: true},
            {type: 'text', title: 'Purpose', width: 100, readOnly: true, wordWrap: true},
            {type: 'text', title: 'Category', width: 100, readOnly: true, wordWrap: true},
            {type: 'text', title: 'If Revised or In-line Pre. SAMPLE Ref. No.', width: 150, readOnly: true, wordWrap: true},
            {type: 'text', title: 'Required Size(s)', width: 70, readOnly: true, wordWrap: true},
            {type: 'text', title: 'Qty.', width: 70, readOnly: true, wordWrap: true},
            {type: 'text', title: 'Assigned SAMPLE Ref. No.', width: 120, readOnly: true, wordWrap: true},
            {type: 'checkbox', title: 'Select Assign.', width: 60, readOnly: true, wordWrap: true},
        ],
        columnDrag: true,
        allowInsertColumn: false,
        allowInsertRow: false,
        data: jsonCurrentData
    });

    jexcel(document.getElementById('samAttachmentReferenceJxl'), {
        columns: [
            {title: 'Combo', width: 150, wordWrap: true, readOnly: true},
            {title: 'Component', width: 150, wordWrap: true, readOnly: true},
            {title: 'Color', width: 150, wordWrap: true, readOnly: true},
            {title: 'P. O. No.', width: 150, wordWrap: true, readOnly: true},
            {title: 'Size Spec Code', width: 150, wordWrap: true, readOnly: true},
            {title: 'Approved & Graded<br/>Measurement Chart', width: 120, wordWrap: true, readOnly: true},
            {title: 'Complete<br/>Artwork', width: 120, wordWrap: true, readOnly: true},
            {title: 'How to Measure<br/>Details', width: 100, wordWrap: true, readOnly: true},
            {title: 'Buyer`s Original<br/>Sample', width: 108, wordWrap: true, readOnly: true},
            {title: 'Buyer`s<br/>Comments', width: 150, wordWrap: true, readOnly: true},
        ],
        data: GlbAttachmentRefJxl,
        onchange: function(instance, cell, x, y, value) {

        }
    });

    jexcel(document.getElementById('samStatusRefNoJxl'), {
        columns: [
            {title: 'Combo', width: 150, wordWrap: true, readOnly: true},
            {title: 'Component', width: 150, wordWrap: true, readOnly: true},
            {title: 'Color', width: 150, wordWrap: true, readOnly: true},
            {title: 'P. O. No.', width: 150, wordWrap: true, readOnly: true},
            {title: 'Size Spec Code', width: 150, wordWrap: true, readOnly: true},
            {title: 'Job Scheduled<br/>Date & Time', width: 160, wordWrap: true, readOnly: true},
            {title: 'Assigned<br/>Sample Ref. No.', width: 160, wordWrap: true, readOnly: true},
            {title: 'Ref. No. Assigned<br/>Date & Time', width: 160, wordWrap: true, readOnly: true},
            {title: 'Sample Job <br/> Completion Status', width: 118, wordWrap: true, readOnly: true},
        ],
        data:[[]],
        onchange: function(instance, cell, x, y, value) {

        }
    });

    MakePostRequest(base_path+"msamplerequest/getIndents","requestid="+GlbId,"json",function (data) {
        console.log(data,'data');
        if(data.sampleReqJxlGrid.length != 0) {
            GlbSampleReqJxlGrid = JSON.parse(data.sampleReqJxlGrid);
        }
        var c = 0; var f = 0; var b = 0;
        $.each(data.moreCadIndent,function (index,cadValue) {
            var cadIndentTblCount = c + 1;
            $("#cadIndentHere").append('<div class="jxlGridHeader"><label class="">Sample No - '+cadIndentTblCount+'</label>' +
                '<span id="gridContent_'+c+'" class="font-weight-normal"> : '+GlbSampleReqJxlGrid[c][0]+' / '+GlbSampleReqJxlGrid[c][1]+' / '+GlbSampleReqJxlGrid[c][2]+' / ' +GlbSampleReqJxlGrid[c][3]+ ' / ' + GlbSampleReqJxlGrid[c][4]+'</span>' +
                '</div>');
            $("#cadIndentHere").append('<div id="gridCadIndent_'+c+'"></div>');
            jexcel(document.getElementById('gridCadIndent_'+c), {
                columns: [
                    {type: 'text', title: 'CAD Ref. No.', width: 450, readOnly: true},
                    {type: 'text', title: 'Requirement', width: 250, readOnly: true},
                    {type: 'text', title: 'Required Size(s)', width: 80, readOnly: true},
                    {type: 'text', title: 'No. of Sizes Issued', width: 120, readOnly: true},
                    {type: 'text', title: 'Total No. of Parts Issued', width: 120, readOnly: true,},
                    {type: 'text', title: 'Total No. of Parts Returned', width: 120, readOnly: true,},
                    {type: 'text', title: 'Parts Returned Status', width: 208, readOnly: true}
                ],
                columnDrag: true,
                allowInsertColumn: false,
                allowInsertRow: false,
                data: JSON.parse(cadValue)
            });
            c++;
        });

        $.each(data.moreFabIndent,function (index,fabValue) {
            var fabIndentTblCount = f + 1;
            $("#fabIndentHere").append('<div class="jxlGridHeader"><label class="">Sample No - '+fabIndentTblCount+'</label>' +
                '<span id="gridContent_'+f+'" class="font-weight-normal"> : '+GlbSampleReqJxlGrid[f][0]+' / '+GlbSampleReqJxlGrid[f][1]+' / '+GlbSampleReqJxlGrid[f][2]+' / ' +GlbSampleReqJxlGrid[f][3]+ ' / ' + GlbSampleReqJxlGrid[f][4]+'</span>' +
                '</div>');
            $("#fabIndentHere").append('<div id="gridFabIndent_'+f+'"></div>');
            jexcel(document.getElementById('gridFabIndent_'+f), {
                columns: [
                    {type: 'text', title: 'Fab. Ref. No.', width: 100, readOnly: true},
                    {type: 'text', title: 'Garment Parts', width: 100, wordWrap: true, readOnly: true},
                    {type: 'text', title: 'Fabric Details (%)Blend / Content / Fabric', width: 150, readOnly: true},
                    {type: 'text', title: 'GSM', width: 150, readOnly: true},
                    {type: 'text', title: 'Colour', width: 150, readOnly: true},
                    {type: 'text', title: 'Dyeing Type', width: 100, readOnly: true},
                    {type: 'text', title: 'Dia / Dim. (W*H)', width: 80, readOnly: true},
                    {type: 'text', title: 'UOM', width: 80, readOnly: true},
                    {type: 'text', title: 'Material Indent Qty.', width: 100, readOnly: true},
                    {type: 'text', title: 'UOM', width: 80, readOnly: true},
                    {type: 'text', title: 'Material Issued Qty.', width: 100, readOnly: true, readOnly: true},
                    {type: 'text', title: 'UOM', width: 80, readOnly: true, readOnly: true},
                ],
                data: JSON.parse(fabValue),
                allowInsertColumn: false,
                allowInsertRow: false
            });
            f++;
        });

        $.each(data.moreBomIndent,function (index,bomValue) {
            var bomIndentTblCount = b + 1;
            $("#bomIndentHere").append('<div class="jxlGridHeader"><label class="">Sample No - '+bomIndentTblCount+'</label>' +
                '<span id="gridContent_'+b+'" class="font-weight-normal"> : '+GlbSampleReqJxlGrid[b][0]+' / '+GlbSampleReqJxlGrid[b][1]+' / '+GlbSampleReqJxlGrid[b][2]+' / ' +GlbSampleReqJxlGrid[b][3]+ ' / ' + GlbSampleReqJxlGrid[b][4]+'</span>' +
                '</div>');
            $("#bomIndentHere").append('<div id="gridBomIndent_'+b+'"></div>');
            jexcel(document.getElementById('gridBomIndent_'+b), {
                columns: [
                    {type: 'text', title: 'Item Description / Content / Material', width: 300, readOnly: true},
                    {type: 'text', title: 'Gar. Size', width: 170, wordWrap: true, readOnly: true},
                    {type: 'text', title: 'Item Code', width: 170, wordWrap: true, readOnly: true},
                    {type: 'text', title: 'Item Colour Code', width: 170, wordWrap: true, readOnly: true},
                    {type: 'text', title: 'Size / Dimension', width: 100, wordWrap: true, readOnly: true},
                    {type: 'text', title: 'UOM', width: 80, wordWrap: true, readOnly: true},
                    {type: 'text', title: 'Material Indent Qty.', width: 120, wordWrap: true, readOnly: true},
                    {type: 'text', title: 'UOM', width: 80, wordWrap: true, readOnly: true},
                    {type: 'text', title: 'Material Issued Qty.', width: 100, wordWrap: true, readOnly: true},
                    {type: 'text', title: 'UOM', width: 80, wordWrap: true, readOnly: true},
                ],
                data: JSON.parse(bomValue),
                allowInsertColumn: false,
                allowInsertRow: false,
            });
            b++;
        });
    });

</script>
<?php $this->load->view(CNFCOMPANY . 'template/pagefooter'); ?>