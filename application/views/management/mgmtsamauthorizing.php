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
        <!-- Main content -->
        <section class="content">
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
                        <div class="box-body">
                            <div class="box-header with-border">
                                <h1 class="box-title">DETAILS</h1>
                            </div>
                            <div id="jxlSampleReq"></div>
                        </div>
                        <div class="box-body">
                            <h4 style="">ATTACHMENT & REFERENCE DETAILS:</h4>

                            <div id="samAttachmentReferenceJxl" class="table"></div>
                        </div>
                        <div class="box-body">
                            <h4 style="">SAMPLE STATUS & REFERENCE NO. ASSIGNED ON JOB COMPLETION</h4>
                            <div id="samStatusRefNoJxl"></div>
                        </div>

                        <div class="box-body">
                            <!--Content-->
                            <form class="form-horizontal" name="frmBasicInfo" id="frmBasicInfo" method="post"
                                  autocomplete="off">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="reqType" class="col-sm-4 control-label">Request Type</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" id="reqType" readonly value="<?php if(!empty($ArrBasicInfo->requesttype)) echo $ArrBasicInfo->requesttype; ?>">
                                        </span>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-4 control-label">Request Date &
                                            Time</label>
                                        <div class="col-sm-8">
                                            <input type="text" name="" class="form-control" readonly
                                                   value="<?php
                                                   echo dateTimeHelp($ArrBasicInfo->datecreated,false); ?>">

                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-4 control-label">Cutoff Date &
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
                                    <div class="form-group">
                                        <label for="frmApproveReject" class="col-sm-4 control-label">Authorization
                                            Status</label>
                                        <?php
                                        //echo $ArrBasicInfo->mgmtcurrentstatus;
                                        ?>
                                        <div class="col-sm-8">
                                            <select name="" id="frmApproveReject"
                                                    class="form-control" <?php if ($ArrBasicInfo->mgmtcurrentstatus == 2 || $ArrBasicInfo->mgmtcurrentstatus == 3) echo 'disabled'; ?>>
                                                <option value="">PENDING</option>
                                                <option value="2" <?php echo ($ArrBasicInfo->mgmtcurrentstatus == 2) ? 'selected' : ''; ?>>
                                                    APPROVE
                                                </option>
                                                <option value="3" <?php echo ($ArrBasicInfo->mgmtcurrentstatus == 3) ? 'selected' : ''; ?>>
                                                    REJECT
                                                </option>
                                                <?php

                                                ?>
                                            </select>
                                            <div class="herr" id="ErrfrmApproveReject"></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">

                                    <div class="form-group">
                                        <label for="frmApprovalType" class="col-sm-4 control-label">Authorization
                                            Type</label>
                                        <div class="col-sm-8">
                                            <?php $ArrApprovalType = unserialize(ARRREQUESTTYPE);
                                            ?>
                                            <select name="frmApprovalType" id="frmApprovalType"
                                                    class="form-control" <?php if ($ArrBasicInfo->mgmtcurrentstatus == 2 || $ArrBasicInfo->mgmtcurrentstatus == 3) echo 'disabled'; ?>>
                                                <option value="">Choose</option>
                                                <?php
                                                foreach ($ArrApprovalType as $key => $approvaltype) {
                                                    ?>
                                                    <option
                                                            value="<?php echo $approvaltype ?>" <?php if (!empty($ArrBasicInfo->approvaltype)) echo $ArrBasicInfo->approvaltype == $approvaltype ? 'selected' : ''; ?>><?php echo $approvaltype ?></option> <?php
                                                }
                                                ?>
                                            </select>
                                            <div class="herr" id="ErrApprovalType"></div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">Authorized By</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" readonly value="<?php if(!empty($AuthorizedByInfo)) echo $AuthorizedByInfo[0]['contactname'] ?>">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="enqdate" class="col-sm-4 control-label">Management
                                            Remarks</label>
                                        <div class="col-sm-8">
                                        <textarea class="form-control"
                                                  id="frmBasicMgmtRemarks" <?php if ($ArrBasicInfo->mgmtcurrentstatus == 2 || $ArrBasicInfo->mgmtcurrentstatus == 3) echo 'readonly'; ?>
                                                  style="height: 64px"><?php if (!empty($ArrBasicInfo->mgmtremarks)) echo $ArrBasicInfo->mgmtremarks ?></textarea>
                                            <div class="herr" id="ErrfrmBasicMgmtRemarks"></div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="enqdate" class="col-sm-4 control-label">Request Status</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" readonly value="PENDING">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="enqdate"
                                               class="col-sm-4 control-label">Sample Queue No.</label>
                                        <div class="col-sm-8">
                                            <input type="text" name="" class="form-control" id="" readonly
                                                   value="<?php echo @$ArrBasicInfo->cadqueueno ?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">

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
                                        <label for="enqdate" class="col-sm-4 control-label">Sample Dept. Remarks</label>
                                        <div class="col-sm-8">
                                                <textarea class="form-control" readonly
                                                          style="height: 64px"><?php if (!empty($caddeptremarks)) echo $caddeptremarks ?></textarea>
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
                                <div class="col-md-12">
                                    <div class="box-body">
                                        <div class="form-group">
                                            <label class="col-md-12">ATTACHMENTS</label>
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
                                                                        <li><div style="padding: 10px 0;">
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
                                                            ?>
                                                            <?php
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
                            </form>
                            <!--Content Ends-->
                        </div>

                    </div>
                    <!--Indent Grids-->
                    <div class="tab">
                        <div class="box-header with-border">
                            <h3 class="box-title">CAD - MATERIAL INDENT</h3>
                        </div>
                        <div class="box box-info">
                            <div class="box-body">
                                <div class="box-header">
                                    <h3 class="box-title" style="margin-bottom: 10px">DETAILS</h3>
                                </div>
                                <div id="cadIndentHere"></div>
                                <form class="form-horizontal" id="frmBasicCadMatIndent">
                                    <div class="box-body pdl0">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="col-sm-4 control-label">Material Indent Ref. No.</label>
                                                <div class="col-sm-8">
                                                    <input type="text" style="height: 45px" class="form-control" readonly value="<?php
                                                    echo $ArrBasicInfo->cad_mat_ind_ref_no ?>">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="col-sm-4 control-label">Issue to Dept.</label>
                                                <div class="col-sm-8">
                                                    <input type="text" class="form-control" readonly value="<?php echo $ArrBasicInfo->cadissuedto ?>">
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
                                                    <input type="text" class="form-control" readonly value="<?php
                                                    echo dateTimeHelp($ArrBasicInfo->cadindentcutoffdatetime,false)  ?>">
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
                            <h3 class="box-title">FABRIC - MATERIAL INDENT</h3>
                        </div>
                        <div class="box box-info">
                            <div class="box-body">
                                <div class="box-header">
                                    <h3 class="box-title" style="margin-bottom: 10px">DETAILS</h3>
                                </div>
                                <div id="gridFabIndent"></div>
                                <div id="fabIndentHere"></div>
                                <form class="form-horizontal" id="frmBasicFabMatIndent">
                                    <div class="box-body pdl0">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="col-sm-4 control-label">Material Indent Ref. No.</label>
                                                <div class="col-sm-8">
                                                    <input type="text" style="height: 45px" class="form-control" readonly value="<?php
                                                    echo $ArrBasicInfo->fab_mat_ind_ref_no ?>">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="col-sm-4 control-label">Issue to Dept.</label>
                                                <div class="col-sm-8">
                                                    <input type="text" class="form-control" readonly value="<?php echo $ArrBasicInfo->fabissuedto ?>">
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
                                                    <input type="text" class="form-control" readonly value="<?php
                                                    echo dateTimeHelp($ArrBasicInfo->fabindentcutoffdatetime,false) ?>">
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
                                <div class="box-header">
                                    <h3 class="box-title" style="margin-bottom: 10px">DETAILS</h3>
                                </div>
                                <div id="bomIndentHere"></div>
                                <form class="form-horizontal" id="frmBasicBomMatIndent">
                                    <div class="box-body pdl0">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="col-sm-4 control-label">Material Indent Ref. No.</label>
                                                <div class="col-sm-8">
                                                    <input type="text" style="height: 45px" class="form-control" readonly value="<?php
                                                    echo $ArrBasicInfo->bom_mat_ind_ref_no ?>">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="col-sm-4 control-label">Issue to Dept.</label>
                                                <div class="col-sm-8">
                                                    <input type="text" class="form-control" readonly value="<?php echo $ArrBasicInfo->bomissuedto ?>">
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
                                                    <input type="text" class="form-control" readonly value="<?php
                                                    echo dateTimeHelp($ArrBasicInfo->bomindentcutoffdatetime,false) ?>">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>

                            <?php
                            //echo '<pre>'; print_r($ArrBasicInfo); die('die');
                            if (empty($ArrBasicInfo->queueno)) {
                                if ($ArrBasicInfo->mgmtcurrentstatus != 2) {
                                if ($ArrBasicInfo->status == 1) {
                                    ?>
                                    <div class="box-footer nopadding" id="divSaveOrderBtn">
                                        <button type="submit" class="btn btn-info pull-right" id="saveCadRequestMgmtAuth" onclick="return fnSaveAllRequestAuth();">
                                            Save Changes
                                        </button>

                                    </div>
                                    <!-- /.box-footer -->
                                    <?php
                                }
                                }
                            }
                            ?>
                        </div>
                    </div>

                    <!--Indent Grids ENDS-->
                    <div class="herr" id="ErrfrmBasicErr"></div>

                </div>
            </div>
            <div class="alert alert-success alert-dismissable hide" id="divSuccessBasicInfoMsg"></div>
            <div class="alert alert-danger alert-dismissable hide" id="divRejStatus"></div>
            <!-- Password Modal Starts Here -->
            <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                        aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="myModalLabel">Enter PIN</h4>
                        </div>
                        <div class="modal-body">
                            <form class="form-horizontal col-md-3" method="post" id="frmPinformId" autocomplete="off">
                                <div id="divOuter">
                                    <div id="divInner">
                                        <input id="frmPin" type="password" maxlength="4" autocomplete="off">
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary" onclick="return fnCheckPin()">Continue
                            </button>
                            <div class="herr pull-left" id="ErrfrmPin"></div>
                        </div>
                    </div>
                </div>
            </div>
        </section><!-- /.content -->
    </div><!-- /.content-wrapper -->
    <?php $this->load->view(CNFCOMPANY . 'template/templatefooter'); ?>
    <div class="control-sidebar-bg"></div>
</div><!-- ./wrapper -->
<script src="<?php echo base_url(); ?>assets/js/jexcel4/jsuites.js"></script>
<script src="<?php echo base_url(); ?>assets/js/jexcel4/jexcel.js"></script>
<script>
    var GlbId = '<?php echo @$VarReqId ?>';
    var GlbOrderId = '<?php echo @$orderid ?>';
    var GlbSampleReqJxlGrid = 0;
    var GlbAttachmentRefJxl = '<?php echo $ArrBasicInfo->attachment_jxl ?>';
    if(GlbAttachmentRefJxl != '') {
        console.log(GlbAttachmentRefJxl,'GlbAttachmentRefJxl');
        GlbAttachmentRefJxl = JSON.parse(GlbAttachmentRefJxl);
    }
    else {
        GlbAttachmentRefJxl = [[]];
    }
    //$.each(GlbgridCadIndent,)
    //var sampleRequestCount = $("#jxlSampleReq").find("tbody tr").length;

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
                    {type: 'text', title: 'Parts Returned Status', width: 208, readOnly: true},
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
                    {type: 'text', title: 'Fab. Ref. No.', width: 200, readOnly: true},
                    {type: 'text', title: 'Garment Parts', width: 132, wordWrap: true, readOnly: true},
                    {type: 'text', title: 'Fabric Blend (%)', width: 110, readOnly: true},
                    {type: 'text', title: 'Fabric Content', width: 130, readOnly: true},
                    {type: 'text', title: 'Fabric', width: 130, readOnly: true},
                    {type: 'text', title: 'GSM', width: 70, readOnly: true},
                    {type: 'text', title: 'Colour', width: 110, readOnly: true},
                    {type: 'text', title: 'Dyeing Type', width: 70, readOnly: true},
                    {type: 'text', title: 'Dia / Dim. (W*H)', width: 140, readOnly: true},
                    {type: 'text', title: 'UOM', width: 80, readOnly: true},
                    {type: 'text', title: 'Material Indent Qty.', width: 100, readOnly: true},
                    {type: 'text', title: 'UOM', width: 80, readOnly: true}
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
                    {type: 'text', title: 'Item Description / Blend (%) / Content / Material', width: 440, readOnly: true},
                    {type: 'text', title: 'Gar. / Label Size', width: 70, wordWrap: true, readOnly: true},
                    {type: 'text', title: 'Item Code', width: 190, wordWrap: true, readOnly: true},
                    {type: 'text', title: 'Item Colour Code', width: 190, wordWrap: true, readOnly: true},
                    {type: 'text', title: 'Size / Dimension', width: 160, wordWrap: true, readOnly: true},
                    {type: 'text', title: 'UOM', width: 100, wordWrap: true, readOnly: true},
                    {type: 'text', title: 'Material Indent Qty.', width: 100, wordWrap: true, readOnly: true},
                    {type: 'text', title: 'UOM', width: 100, wordWrap: true, readOnly: true}
                ],
                data: JSON.parse(bomValue),
                allowInsertColumn: false,
                allowInsertRow: false,
            });
            b++;
        });
    });

    var jxlTbl = jexcel(document.getElementById('jxlSampleReq'), {
        columns: [
            {type: 'text', title: 'Combo', width: 150, readOnly: true, wordWrap: true},
            {type: 'text', title: 'Component', width: 150, readOnly: true, wordWrap: true},
            {type: 'text', title: 'Color', width: 150, readOnly: true, wordWrap: true},
            {type: 'text', title: 'P. O. No.', width: 150, readOnly: true, wordWrap: true},
            {type: 'text', title: 'Size Spec Code', width: 150, readOnly: true, wordWrap: true},
            {type: 'text', title: 'Requirement', width: 100, readOnly: true, wordWrap: true},
            {type: 'text', title: 'Purpose', width: 100, readOnly: true, wordWrap: true},
            {type: 'text', title: 'Category', width: 100, readOnly: true, wordWrap: true},
            {type: 'text', title: 'If Revised or In-line Pre. SAMPLE Ref. No.', width: 135, readOnly: true, wordWrap: true},
            {type: 'text', title: 'Required Size(s)', width: 65, readOnly: true, wordWrap: true},
            {type: 'text', title: 'Qty.', width: 100, readOnly: true, wordWrap: true},
            /*{type: 'text', title: 'Assigned SAMPLE Ref. No.', width: 120, readOnly: true, wordWrap: true},
            {type: 'checkbox', title: 'Select Assign.', width: 60, readOnly: true, wordWrap: true},*/
        ],
        columnDrag: true,
        allowInsertColumn: false,
        allowInsertRow: false,
        data: GlbSampleReqJxlGrid
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

    var url = $(location).attr('href');
    var lasturlpart = url.substr(url.lastIndexOf('/') + 1);
    if (lasturlpart == 'managecadrequest') {

    }
    else if (lasturlpart == 'managemgmtcadrequest') {
        //alert(lasturlpart);
    }
    var GlbSearchParam = '';

    var GlbSortOrder = '';
    var GlbColumnId = '';

    var GlbfrmApproveReject = "", GlbfrmBasicMgmtRemarks = "", GlbApprovalType = "";

    function fnSaveAllRequestAuth() {
        try {
            $('.form-control').css("border", "1px solid #cccccc");
            $('div.herr').text('');
            GlbfrmBasicMgmtRemarks = $("#frmBasicMgmtRemarks").val();
            GlbfrmApproveReject = $("#frmApproveReject").val();
            //var frmBasicMgmtPassword = $("#frmBasicMgmtPassword").val();
            GlbApprovalType = $("#frmApprovalType").val();
            if (jsTrim(GlbfrmBasicMgmtRemarks) == "") {
                $('#ErrfrmBasicMgmtRemarks').html("Please fill the Management Remarks");
                $('#frmBasicMgmtRemarks').focus();
                $('#frmBasicMgmtRemarks').css("border", "1px solid #B94A48");
                return false;
            }
            if (GlbfrmApproveReject == "") {
                $('#ErrfrmApproveReject').html("Please Approve / Reject");
                $('#frmApproveReject').focus();
                $('#frmApproveReject').css("border", "1px solid #B94A48");
                return false;
            }
            if (GlbfrmApproveReject != "") {
                $('#myModal').modal('show');
                return false;
            }
            else {
                $('#ErrfrmBasicErr').text("Please Select Approve or Reject");
                return false;
            }
            if (GlbApprovalType == "") {
                $('#ErrApprovalType').text("Please Select Approval Type");
                $('#frmApprovalType').focus();
                $('#frmApprovalType').css("border", "1px solid #B94A48");
                return false;
            }
        } catch (e) {
            alert(e);
        }
    }

    function fnCheckPin() {
        $(".herr").text('');
        try {
            var pw = $("#frmPin").val();
            if (jsTrim(pw) == "") {
                $("#ErrfrmPin").text('Enter PIN');
                return false;
            }
            MakePostRequest(base_path + 'management/samRequestCheckPin', "rfrom=1&pwd=" + pw + "&id=" + GlbId + "&cs=" +
                GlbfrmApproveReject + "&mgmtremarks=" + GlbfrmBasicMgmtRemarks + "&approvaltype=" + GlbApprovalType, 'json', fnAuthRes);
            return false;
        } catch (e) {
            alert(e);
        }
    }

    function fnAuthRes(data) {
        console.log(data, 'data');
        if (data != '') {
            if (data.errcode == '404') {
                fnCallSessionExpire();
                return false;
            } else if (data.errcode == '-1') {
                $('#ErrfrmPin').text(data.msg);
                return false;
            } else if (data.errcode == '1') {
                $("#MgmtCurrentStatus").addClass('hide');
                $('#myModal').modal('hide');

                if (data.cs == 2) {
                    $("#divSuccessBasicInfoMsg").removeClass('hide');
                    $("#divSuccessBasicInfoMsg").text('Approved');
                }
                else if (data.cs == 3) {
                    $("#divRejStatus").removeClass('hide');
                    $("#divRejStatus").text('Rejected');
                }
                fnRedirectPageTimeOut(base_path + 'management/manageauthorizationrequest');
            }
        }
    }
</script>
<?php $this->load->view(CNFCOMPANY . 'template/pagefooter'); ?>