<?php $this->load->view(CNFCOMPANY . 'template/pageheader'); ?>
<link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>assets/css/bootstrap-datetimepicker-standalone.css">
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
                            <!--Content-->
                            <form class="form-horizontal" name="frmBasicInfo" id="frmBasicInfo" method="post"
                                  autocomplete="off">
                                <div class="alert alert-success alert-dismissable hide"
                                     id="divSuccessBasicInfoMsg"></div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-4 control-label">Request
                                            Type</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" readonly value="<?php
                                            if(!empty($ArrBasicInfo->requesttype)) echo $ArrBasicInfo->requesttype; ?>">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-4 control-label">Request Date & Time</label>
                                        <div class="col-sm-8">
                                            <input type="text" name="" class="form-control" id="frmBasicReqDate" readonly
                                                   value="<?php echo dateTimeHelp($ArrBasicInfo->datecreated,false) ?>">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-4 control-label">CutOff Date &
                                            Time</label>
                                        <div class="col-sm-8">
                                            <input type='text' class="form-control" readonly
                                                   value="<?php if (isset($ArrBasicInfo->cutoffdatetime)) echo date('d-m-Y H:i:s', strtotime($ArrBasicInfo->cutoffdatetime)) ?>"/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-4 control-label">Merchant
                                            Note</label>
                                        <div class="col-sm-8">
                                                    <textarea id="" readonly class="form-control" readonly
                                                              style="height: 65px"><?php if (isset($ArrBasicInfo->merchantnote)) echo $ArrBasicInfo->merchantnote ?></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-4 control-label">Authorization
                                            Status</label>
                                        <div class="col-sm-8">
                                            <?php
                                            $ArrORDERENQUIRYSTATUS = unserialize(ORDERENQUIRYSTATUS);
                                            $VarCs                 = '';
                                            if ($ArrBasicInfo->mgmtcurrentstatus == 1) {
                                                $VarCs = 'AUTHORIZATION ' . $ArrORDERENQUIRYSTATUS[$ArrBasicInfo->mgmtcurrentstatus];
                                            } elseif ($ArrBasicInfo->mgmtcurrentstatus == 2) {
                                                $VarCs = 'APPROVE';
                                            } elseif ($ArrBasicInfo->mgmtcurrentstatus == 3) {
                                                $VarCs = 'DECLINE';
                                            } elseif ($ArrBasicInfo->mgmtcurrentstatus == 4) {
                                                $VarCs = 'RE REQUEST';
                                            }
                                            ?>
                                            <span class="form-control" id=""
                                                  readonly="readonly"><?php echo $VarCs; ?></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">

                                    <div class="form-group">
                                        <label for="aType" class="col-sm-4 control-label">Authorization
                                            Type</label>
                                        <div class="col-sm-8">
                                            <?php $ArrApprovalType = unserialize(ARRREQUESTTYPE) ?>
                                            <select name="" id="aType" class="form-control" disabled>
                                                <option value="">Choose</option>
                                                <?php
                                                foreach ($ArrApprovalType as $key => $approvaltype) {
                                                    ?>
                                                    <option
                                                            value="<?php echo $approvaltype ?>" <?php echo @$ArrBasicInfo->approvaltype == $approvaltype ? 'selected' : '' ?>><?php echo $approvaltype ?></option> <?php
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
                                        <label for="enqdate" class="col-sm-4 control-label">Management
                                            Remarks</label>
                                        <div class="col-sm-8">
                                                    <textarea readonly class="form-control"
                                                              style="height: 64px"><?php if (!empty($ArrBasicInfo->mgmtremarks)) echo $ArrBasicInfo->mgmtremarks ?></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="enqdate" class="col-sm-4 control-label">Request
                                            Status</label>
                                        <div class="col-sm-8">
                                            <?php $ArrAppRejStatus = unserialize(REQUESTSTATUSARR);
                                            unset($ArrAppRejStatus[1]);
                                            unset($ArrAppRejStatus[4]); ?>
                                            <select name="" id="frmCadDeptAcceptReject" class="form-control" disabled>
                                                <?php
                                                foreach ($ArrAppRejStatus as $key => $arrCadStatus) {
                                                    echo '<option value="'.$key.'" '.($ArrBasicInfo->deptcurrentstatus == $key ? "selected" : "").'>'.$arrCadStatus.'</option>';
                                                }
                                                ?>
                                            </select>
                                            <div class="herr" id="ErrfrmCadDeptAcceptReject"></div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="enqdate"
                                               class="col-sm-4 control-label">Sample Queue No.</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" id="samplequeueno" readonly
                                                   value="<?php if(!empty($ArrBasicInfo->queueno)) echo $ArrBasicInfo->queueno ?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="enqdate" class="col-sm-4 control-label">Queue No. Assigned
                                            Date & Time</label>
                                        <div class="col-sm-8">
                                            <input type="text" name="" class="form-control" id="assigneddatetime" readonly
                                                   value="<?php echo dateTimeHelp($ArrBasicInfo->queueno_assigned_date,false) ?>">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="enqdate"
                                               class="col-sm-4 control-label">Sample Dept. Remarks</label>
                                        <div class="col-sm-8">
                                                    <textarea id="frmBasicDeptRemarks" class="form-control"
                                                              style="height: 64px"><?php if (!empty($ArrBasicInfo->deptremarks)) echo $ArrBasicInfo->deptremarks ?></textarea>
                                        </div>
                                        <div class="herr" id="ErrfrmBasicDeptRemarks"></div>
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
                                    <div class="herr" id="ErrfrmBasicErr"></div>
                                </div>

                            </form>
                            <div class="form-group">
                                <div class="col-md-12">
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
                                </div>
                            </div>
                            <!--Content Ends-->
                        </div>
                    </div>
                    <!--Indent Grids -->
                    <div id="indentgrids">
                        <div class="tab">
                            <div class="box-header with-border"> <h3 class="box-title">CAD - MATERIAL INDENT</h3></div>
                            <div class="box box-info">
                                <div class="box-body">
                                    <div class="box-header"> <h3 class="box-title">DETAILS</h3></div>
                                    <div id="cadIndentHere"></div>
                                    <form class="form-horizontal" id="frmBasicCadMatIndent">
                                        <div class="box-body pdl0">
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label class="col-sm-4 control-label">Material Indent Ref. No.</label>
                                                    <div class="col-sm-8">
                                                        <div id="CadMatIndRefNo" style="height: 45px"
                                                             class="customcontrol-readonly"> <?php echo $ArrBasicInfo->cad_mat_ind_ref_no; ?></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label class="col-sm-4 control-label">Issue to Dept.</label>
                                                    <div class="col-sm-8">
                                                        <div class="customcontrol-readonly">
                                                            <?php $ArrBasicInfo->cadissuedto; ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label class="col-sm-4 control-label">Request Date & Time</label>
                                                    <div class="col-sm-8">
                                                        <div class="customcontrol-readonly"> <?php echo dateTimeHelp($ArrBasicInfo->datecreated,false) ?></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label class="col-sm-4 control-label">Cutoff Date & Time</label>
                                                    <div class="col-sm-8">
                                                    <span class="customcontrol-readonly">
                                                        <?php echo dateTimeHelp($ArrBasicInfo->cadindentcutoffdatetime,false); ?>
                                                    </span>
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
                                        <h3 class="box-title">DETAILS</h3>
                                    </div>
                                    <div id="fabIndentHere"></div>
                                    <form class="form-horizontal" id="frmBasicFabMatIndent">
                                        <div class="box-body pdl0">
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label class="col-sm-4 control-label">Material Indent Ref. No.</label>
                                                    <div class="col-sm-8">
                                                        <div id="FabMatIndRefNo" style="height: 45px"
                                                             class="customcontrol-readonly"> <?php echo $ArrBasicInfo->fab_mat_ind_ref_no ?></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label class="col-sm-4 control-label">Issue to Dept.</label>
                                                    <div class="col-sm-8">
                                                        <div class="customcontrol-readonly">
                                                            <?php echo $ArrBasicInfo->fabissuedto; ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label class="col-sm-4 control-label">Request Date & Time</label>
                                                    <div class="col-sm-8">
                                                        <div class="customcontrol-readonly"> <?php echo dateTimeHelp($ArrBasicInfo->datecreated,false) ?></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label class="col-sm-4 control-label">Cutoff Date & Time</label>
                                                    <div class="col-sm-8">
                                                    <span class="customcontrol-readonly">
                                                        <?php echo dateTimeHelp($ArrBasicInfo->fabindentcutoffdatetime,false); ?>
                                                    </span>
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
                                        <h3 class="box-title">DETAILS</h3>
                                    </div>
                                    <div id="bomIndentHere"></div>
                                    <form class="form-horizontal" id="frmBasicBomMatIndent">
                                        <div class="box-body pdl0">
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label class="col-sm-4 control-label">Material Indent Ref. No.</label>
                                                    <div class="col-sm-8">
                                                        <div id="BomMatIndRefNo" style="height: 45px"
                                                             class="customcontrol-readonly"> <?php echo $ArrBasicInfo->bom_mat_ind_ref_no; ?></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label class="col-sm-4 control-label">Issue to Dept.</label>
                                                    <div class="col-sm-8">
                                                        <div class="customcontrol-readonly">
                                                            <?php echo $ArrBasicInfo->bomissuedto; ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label class="col-sm-4 control-label">Request Date & Time</label>
                                                    <div class="col-sm-8">
                                                        <div class="customcontrol-readonly"> <?php echo dateTimeHelp($ArrBasicInfo->datecreated,false) ?></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label class="col-sm-4 control-label">Cutoff Date & Time</label>
                                                    <div class="col-sm-8">
                                                    <span class="customcontrol-readonly">
                                                        <?php echo dateTimeHelp($ArrBasicInfo->bomindentcutoffdatetime,false); ?>
                                                    </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <?php if($ArrBasicInfo->status == 1) {
                                ?>
                                    <div class="box-footer nopadding" id="divSaveOrderBtn">

                                    <button type="submit" class="btn btn-info pull-right" id="saveSamQueuenoAssign"
                                    onclick="return fnUpdateSampleRequest();">Save Changes
                                    </button>
                                    </div>
                                <?php
                                }
                                ?>
                            </div>
                        </div>
                        <div class="alert alert-success alert-dismissable hide" id="divSuccessInfoMsg"></div>
                        <div class="alert alert-danger alert-dismissable hide" id="divErrorInfoMsg"></div>
                    </div>
                    <!--Indent Grids ENDS -->

                </div>
            </div>

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
                            <form class="form-horizontal col-md-3" method="post" id="frmPinformId"
                                  autocomplete="off">
                                <div id="divOuter">
                                    <div id="divInner">
                                        <input id="frmPin" type="password" maxlength="4"/>
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
<script src="<?php echo base_url(); ?>assets/js/moment.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/bootstrap-datetimepicker.js"></script>
<script src="<?php echo base_url(); ?>assets/js/jexcel4/jsuites.js"></script>
<script src="<?php echo base_url(); ?>assets/js/jexcel4/jexcel.js"></script>
<script src="<?php echo base_url(); ?>assets/js/loadingoverlay.min.js"></script>

<script>
    var GlbId = "<?php echo @$VarId;?>";
    var GlbOrderId = "<?php echo @$VarOrderId; ?>";
    var jsonUnitMeasure = '<?php echo $jsonUnitMeasure ?>';
    var PartsReturnStatus = ['Pending','Returned','Partly Returned','Parts Missing','Missing'];
    var MatIndQtyUom = '<?php echo json_encode(array_values(unserialize(ARRUNITOFMEASURE))) ?>';
    var GlbAllIndentId = []; var GlbSampleReqJxlGrid = []; var GlbSamStatusRefNoJxl = '';
    var GlbAttachmentRefJxl = '<?php echo $ArrBasicInfo->attachment_jxl ?>';
    if(GlbAttachmentRefJxl != '') {
        //console.log(GlbAttachmentRefJxl,'GlbAttachmentRefJxl');
        GlbAttachmentRefJxl = JSON.parse(GlbAttachmentRefJxl);
    }
    else {
        GlbAttachmentRefJxl = [[]];
    }
    /*Common function not working
    let currentDate = getCurrentDt();
    */
    let GlbIsrIorCode = $("#frmBasicWipRefNo").text();
    MakePostRequest(base_path+"msamplerequest/getIndents","requestid="+GlbId,"json",function (data) {
        //console.log(data,'data');
        //console.log(data.moreCadIndent.length,'cad ind length');
        var c = 0; var f = 0; var b = 0;
        $.each(data.moreCadIndent,function (index,cadValue) {
            var cadIndentTblCount = c + 1;
            //console.log(index,'id');
            GlbAllIndentId.push(index);
            if(data.sampleReqJxlGrid != '') {
                GlbSampleReqJxlGrid = JSON.parse(data.sampleReqJxlGrid);
            }
            $("#cadIndentHere").append('<div class="jxlGridHeader"><label>Sample No - '+cadIndentTblCount+'</label>' +
                '<span class="font-weight-normal"> : '+GlbSampleReqJxlGrid[c][0]+' / '+GlbSampleReqJxlGrid[c][1]+' / '+GlbSampleReqJxlGrid[c][2]+' / ' +GlbSampleReqJxlGrid[c][3]+ ' / ' + GlbSampleReqJxlGrid[c][4]+'</span></div>');

            $("#cadIndentHere").append('<div id="gridCadIndent_'+c+'"></div>');
            jexcel(document.getElementById('gridCadIndent_'+c), {
                columns: [
                    {type: 'text', title: 'CAD Ref. No.', width: 455, readOnly: true},
                    {type: 'text', title: 'Requirement', width: 250, readOnly: true},
                    {type: 'text', title: 'Required Size(s)', width: 80, readOnly: true},
                    {type: 'text', title: 'No. of Sizes Issued', width: 120},
                    {type: 'text', title: 'Total No. of Parts Issued', width: 120},
                    {type: 'text', title: 'Total No. of Parts Returned', width: 120},
                    {type: 'dropdown', title: 'Parts Returned Status', width: 200, source: PartsReturnStatus, wordWrap: true},
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
                '<span class="font-weight-normal"> : '+GlbSampleReqJxlGrid[f][0]+' / '+GlbSampleReqJxlGrid[f][1]+' / '+GlbSampleReqJxlGrid[f][2]+' / ' +GlbSampleReqJxlGrid[f][3]+ ' / ' + GlbSampleReqJxlGrid[f][4]+'</span>' +
                '</div>');
            $("#fabIndentHere").append('<div id="gridFabIndent_'+f+'"></div>');

            jexcel(document.getElementById('gridFabIndent_'+f), {
                columns: [
                    {type: 'text', title: 'Fab. Ref. No.', width: 200, readOnly: true},
                    {type: 'text', title: 'Garment Parts', width: 96, wordWrap: true, readOnly: true},
                    {type: 'text', title: 'Fabric Blend (%)', width: 130, readOnly: true},
                    {type: 'text', title: 'Fabric Content', width: 130, readOnly: true},
                    {type: 'text', title: 'Fabric', width: 130, readOnly: true},
                    {type: 'text', title: 'GSM', width: 70, readOnly: true},
                    {type: 'text', title: 'Colour', width: 140, readOnly: true},
                    {type: 'text', title: 'Dyeing Type', width: 70, readOnly: true},
                    {type: 'text', title: 'Dia / Dim. (W*H)', width: 140, readOnly: true},
                    {type: 'text', title: 'UOM', width: 80, readOnly: true},
                    {type: 'text', title: 'Material Indent Qty.', width: 80},
                    {type: 'dropdown', title: 'UOM', width: 80, source: JSON.parse(jsonUnitMeasure)},
                    /*{type: 'text', title: 'Material Issued Qty.', width: 100},
                    {type: 'dropdown', title: 'UOM', width: 80, source: JSON.parse(jsonUnitMeasure)},*/
                ],
                columnDrag: true,
                allowInsertColumn: false,
                allowInsertRow: false,
                data: JSON.parse(fabValue)
            });
            f++;
        });

        $.each(data.moreBomIndent,function (index,bomValue) {
            var bomIndentTblCount = b + 1;
            $("#bomIndentHere").append('<div class="jxlGridHeader"><label class="">Sample No - '+bomIndentTblCount+'</label>' +
                '<span class="font-weight-normal"> : '+GlbSampleReqJxlGrid[b][0]+' / '+GlbSampleReqJxlGrid[b][1]+' / '+GlbSampleReqJxlGrid[b][2]+' / ' +GlbSampleReqJxlGrid[b][3]+ ' / ' + GlbSampleReqJxlGrid[b][4]+'</span>' +
                '</div>');
            $("#bomIndentHere").append('<div id="gridBomIndent_'+b+'"></div>');

            jexcel(document.getElementById('gridBomIndent_'+b), {
                columns: [
                    {type: 'text', title: 'Item Description / Blend (%) / Content / Material', width: 445, readOnly: true},
                    {type: 'text', title: 'Gar. Size', width: 70, wordWrap: true, readOnly: true},
                    {type: 'text', title: 'Item Code', width: 190, wordWrap: true, readOnly: true},
                    {type: 'text', title: 'Item Colour Code', width: 190, wordWrap: true, readOnly: true},
                    {type: 'text', title: 'Size / Dimension', width: 160, wordWrap: true, readOnly: true},
                    {type: 'text', title: 'UOM', width: 100, wordWrap: true, readOnly: true},
                    {type: 'text', title: 'Material Indent Qty.', width: 90, wordWrap: true, readOnly: true},
                    {type: 'text', title: 'UOM', width: 100, wordWrap: true, readOnly: true},
                    /*{type: 'text', title: 'Material Issued Qty.', width: 100, wordWrap: true},
                    {type: 'dropdown', title: 'UOM', width: 80, source: JSON.parse(jsonUnitMeasure), wordWrap: true},*/
                ],
                data: JSON.parse(bomValue),
                allowInsertColumn: false,
                allowInsertRow: false,
            });
            b++
        });
    });

    var url = $(location).attr('href');
    var lasturlpart = url.substr(url.lastIndexOf('/') + 1);
    if (lasturlpart == 'managecadrequest') {
    }
    else if (lasturlpart == 'managemgmtcadrequest') {
        //alert(lasturlpart);
    }

    function fnUpdateSampleRequest() {
        try {
            $('.form-control').css("border", "1px solid #cccccc");
            $('div.herr').text('');
            $('#myModal').modal('show');
        } catch (e) {
            alert(e);
        }
    }

    function fnCheckPin() {
        $(".herr").text('');
            var pw = $("#frmPin").val();
            var deptRemarks = $("#frmBasicDeptRemarks").val();
            //var jobSchedule = $("#frmBasicJobSchedule").val();
            var samStatusRefNo = $("#samStatusRefNoJxl").jexcel('getData');
            if (jsTrim(pw) == "") {
                $("#ErrfrmPin").text('Enter PIN');
                return false;
            }
            MakePostRequest(base_path + GlbCompanyFdr+'msamplinguser/updateSamQueueStatusnPIN',"rfrom=1&i=" + pw + "&srid=" +GlbId+"&oId="+GlbOrderId+
                "&rem=" +deptRemarks +"&sampleReqMisc="+ JSON.stringify(samStatusRefNo),'json', fnAuthRes);
            return false;

    }

    function fnAuthRes(data) {
        if (data != '') {
            if (data.errcode == '404') {
                fnCallSessionExpire();
                return false;
            } else if (data.errcode == '-1') {
                $('#ErrfrmPin').text(data.msg);
                return false;
            } else if (data.errcode == 1) {
                $('#myModal').modal('hide');
                var sampleRequestCount  = $("#jxlSampleReq").find("tbody tr").length;
                var ArrMoreCadIndentsJxl = [];
                var ArrMoreFabIndentsJxl = [];
                var ArrMoreBomIndentsJxl = [];
                for (var e = 0; e < sampleRequestCount; e++) {
                    var moreCadIndentsJxl = $("#gridCadIndent_"+e).jexcel('getData');
                    ArrMoreCadIndentsJxl.push(moreCadIndentsJxl);
                    var moreFabIndentsJxl = $("#gridFabIndent_"+e).jexcel('getData');
                    ArrMoreFabIndentsJxl.push(moreFabIndentsJxl);
                    var moreBomIndentsJxl = $("#gridBomIndent_"+e).jexcel('getData');
                    ArrMoreBomIndentsJxl.push(moreBomIndentsJxl);
                }
                var CadMatIndentCutoff = $("#CadMatIndentCutoff").val();
                var FabMatIndentCutoff = $("#FabMatIndentCutoff").val();
                var BomMatIndentCutoff = $("#BomMatIndentCutoff").val();
                var jxlSampleReqData = $("#jxlSampleReq").jexcel('getData');
                MakePostRequest(base_path + 'dashboard/updateIndentGridInfoinReceiver',
                    "rfrom=1&srid=" +GlbId+
                    "&moreCadJxl="+JSON.stringify(ArrMoreCadIndentsJxl)+
                    "&moreFabJxl="+JSON.stringify(ArrMoreFabIndentsJxl)+
                    "&moreBomJxl="+JSON.stringify(ArrMoreBomIndentsJxl)+
                    "&jxlSampleReqData="+JSON.stringify(jxlSampleReqData)+
                    "&CadMatIndentCutoff="+CadMatIndentCutoff+
                    "&FabMatIndentCutoff="+FabMatIndentCutoff+
                    "&BomMatIndentCutoff="+BomMatIndentCutoff+"&indentid="+JSON.stringify(GlbAllIndentId),
                    "json",fnSaveRequestIndentsRes);
            }
        }
    }

    function fnSaveRequestIndentsRes(data) {
        if (data != '') {
            if (data.errcode == '404') {
                fnCallSessionExpire();
                return false;
            } else if (data.errcode == '-1') {
                $('#ErrfrmPin').text(data.msg);
                return false;
            } else if (data.errcode == 1) {
                $("#divSuccessInfoMsg").removeClass("hide");
                $("#divSuccessInfoMsg").text(data.msg);
                fnRedirectPageTimeOut(base_path+GlbCompanyFdr+'msamplinguser/samplequeuelist');
            }
        }
    }

    var GlbGeneratedRefNo = ''; var GlbRow = 0;
    var jxlTbl = jexcel(document.getElementById('jxlSampleReq'), {
        columns:[
            { type:'text',title:'Combo', width:150, readOnly:true },
            { type:'text',title:'Component', width:150, readOnly:true },
            { type:'text',title:'Color', width:150, readOnly:true },
            { type:'text',title:'P. O. No.', width:150, readOnly:true },
            { type:'text',title:'Size Spec Code', width:150, readOnly:true },
            { type:'text',title:'Requirement', width:100, readOnly:true },
            { type:'text',title:'Purpose', width:100, readOnly:true },
            { type:'text',title:'Category', width:100, readOnly:true },
            { type:'text',title:'If Revised or In-line Pre. SAMPLE Ref. No.', width:135, readOnly:true },
            { type:'text',title:'Required Size(s)', width:60, readOnly:true },
            { type:'text',title:'Qty.', width:100, wordWrap: true, readOnly:true}
        ],
        columnDrag:true,
        allowInsertColumn: false,
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
        data: GlbAttachmentRefJxl
    });
    MakePostRequest(base_path+"msamplerequest/getSampleStatusRefNoJxl","id="+GlbId,"json",function (data) {
        //console.log(data,'data');
        if(data.re != '')
            GlbSamStatusRefNoJxl = JSON.parse(data.re);
        else {
            GlbSamStatusRefNoJxl = [[]];
        }
console.log(GlbSamStatusRefNoJxl,'GlbSamStatusRefNoJxl');
        jexcel(document.getElementById('samStatusRefNoJxl'), {
            columns: [
                {title: 'Combo', width: 140, wordWrap: true, readOnly: true},
                {title: 'Component', width: 140, wordWrap: true, readOnly: true},
                {title: 'Color', width: 140, wordWrap: true, readOnly: true},
                {title: 'P. O. No.', width: 140, wordWrap: true, readOnly: true},
                {title: 'Size Spec Code', width: 140, wordWrap: true, readOnly: true},
                {type:  'calendar', title: 'Job Scheduled<br/>Date & Time', width: 150, wordWrap: true,options: { format:'DD/MM/YYYY HH12:MI AM/PM', time:1 }},
                {title: 'Assigned<br/>Sample Ref. No.', width: 160, wordWrap: true, readOnly: true},
                {title: 'Ref. No. Assigned<br/>Date & Time', width: 160, wordWrap: true, readOnly: true},
                {type: 'dropdown', source: ["Pending","Completed"], title: 'Sample Job <br/> Completion Status', width: 118, wordWrap: true},
                {type: 'checkbox', title: 'Select', width: 60},
            ],
            data: GlbSamStatusRefNoJxl,
            onchange: function(instance, cell, x, y, value) {
                if(x == 9) {
                    if(value) {
                        console.log(GlbIsrIorCode,'GlbIsrIorCode');
                        MakePostRequest(base_path + 'msamplerequest/getSampleRefNo', "rFrom=1&id=" + GlbId+
                            "&isrIorCode="+GlbIsrIorCode, 'json', fnGenerateRefNoRes);
                        function fnGenerateRefNoRes(data) {
                            if (data.refNo != '') {
                                GlbGeneratedRefNo = data.refNo;
                                GlbRow = y;
                            }
                        }
                    }
                    else {
                        GlbRow = 0;
                    }
                }
            },
            updateTable:function(instance, cell, col, row, val, label, cellName) {
                if(GlbGeneratedRefNo != '') {
                    //console.log(row,'row');
                    //console.log(GlbRow,'GlbRow');
                    if(col == 6 && row == GlbRow) {
                        var sno = row + 1;
                        $(cell).html(GlbGeneratedRefNo+'-'+sno);
                        instance.jexcel.options.data[row][col] = GlbGeneratedRefNo+'-'+sno;
                    }
                    if(col == 7 && row == GlbRow) {
                        var date;
                        date = new Date();
                        date =
                            ('00' + date.getDate()).slice(-2) + '-' +
                            ('00' + (date.getMonth()+1)).slice(-2) + '-' +
                            date.getFullYear() + ' ' +
                            ('00' + date.getHours()).slice(-2) + ':' +
                            ('00' + date.getMinutes()).slice(-2) + ':' +
                            ('00' + date.getSeconds()).slice(-2);
                        //console.log(date);
                        $(cell).html(date);
                        instance.jexcel.options.data[row][col] = date;
                    }
                    if(col == 9) {
                        if(val) {
                            $(cell).find('input').attr('disabled',true);
                        }
                    }
                }
            }
        });
    });


    $(function () {
        $('#cadcutoffdt').datetimepicker({
            format: 'DD-MM-YYYY HH:mm:ss'
        });
        $('#fabcutoffdt').datetimepicker({
            format: 'DD-MM-YYYY HH:mm:ss'
        });
        $('#bomcutoffdt').datetimepicker({
            format: 'DD-MM-YYYY HH:mm:ss'
        });

            $('#datetimepicker1').datetimepicker({
                //format: 'MM/YYYY',
                format: 'DD-MM-YYYY HH:mm'
                /*useCurrent: false,
                toolbarPlacement: 'bottom',
                viewMode: 'months'*/
            });

    });

    $(document).ajaxStart(function (a) {
        $.LoadingOverlay("show", {image: base_path + "assets/img/fullpage.gif"});
    });
    $(document).ajaxStop(function () {
        $.LoadingOverlay("hide");
    });

</script>
<?php $this->load->view(CNFCOMPANY . 'template/pagefooter'); ?>