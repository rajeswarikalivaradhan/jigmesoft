<?php $this->load->view(CNFCOMPANY.'template/pageheader'); ?>
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/jexcel4/jsuites.css"/>
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/jexcel4/jexcel.css"/>
    <link rel="stylesheet" href="<?php echo base_url();?>assets/css/uploadfile-order.css"/>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>assets/css/bootstrap-datetimepicker-standalone.css">
    <body class="sidebar-mini skin-black wysihtml5-supported sidebar-collapse">
<div class="wrapper">
    <?php $this->load->view(CNFCOMPANY.'template/templateheader');?>
    <!-- Left side column. contains the logo and sidebar -->
    <aside class="main-sidebar">
        <?php $this->load->view(CNFCOMPANY.'template/templateleftmenu');?>
    </aside>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper order-entry">
        <!-- Content Header (Page header) -->
        <!--<section class="content-header">
            <h1>CAD Dept. CAD QUEUE NO. LIST DETAILS</h1>
        </section>-->
        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-info">
                        <!-- /.box-header -->
                        <div class="box-body" style="padding: 0">
                            <div class="col-md-12 pd0 no-padding">
                                <?php $this->load->view('commonBasicInfoOrderEntry'); ?>
                            </div>
                        </div>
                    </div>
                    <div class="box-header with-border" style="padding: 0 10px">
                        <h3 class="box-title">
                            CAD REQUEST RECEIVED
                        </h3>
                    </div>
                    <div class="box box-info">
                        <div class="box-header with-bgColor">
                            <h3 class="box-title box-titleFontSize">
                                DETAILS
                            </h3>
                        </div>
                        <div class="box-body table-responsive">
                            <div id="merchantCadReqJxl"></div>
                        </div>
                        <div class="box-body table-responsive">
                            <h4>ATTACHMENT & REFERENCE SAMPLE DETAILS:</h4>
                            <div id="jxlAttachmentDetails"></div>
                        </div>
                        <div class="box-body">
                            <form class="form-horizontal" name="frmBasicInfo" id="frmBasicInfo" method="post" autocomplete="off">
                                <div class="col-md-4">

                                    <div class="form-group">
                                        <label for="reqType" class="col-sm-4 control-label">Request Type</label>
                                        <div class="col-sm-8">
                                            <?php $ArrReqType = unserialize(ARRREQUESTTYPE) ?>
                                            <select class="form-control" id="reqType" disabled>
                                                <?php
                                                foreach ($ArrReqType as $key => $reqType) {
                                                    ?>
                                                    <option value="<?php echo $reqType ?>" <?php echo @$ArrBasicInfo->requesttype == $reqType ? 'selected' : '' ?>>
                                                        <?php echo $reqType ?>
                                                    </option>
                                                    <?php
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="frmBasicReqDate" class="col-sm-4 control-label">Request Date & Time</label>
                                        <div class="col-sm-8">
                                            <input type="text" name="" class="form-control" id="frmBasicReqDate" readonly value="<?php
                                            echo dateTimeHelp($ArrBasicInfo->datecreated,false) ?>">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-4 control-label">Cutoff Date & Time</label>
                                        <div class="col-sm-8">
                                            <input type='text' class="form-control" readonly value="<?php if(isset($ArrBasicInfo->cutoffdatetime)) echo date('d-m-Y H:i:s',strtotime($ArrBasicInfo->cutoffdatetime)) ?>" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-4 control-label">Merchant Note</label>
                                        <div class="col-sm-8">
                                            <textarea id="" readonly class="form-control" readonly style="height: 64px"><?php if(isset($ArrBasicInfo->merchantnote)) echo $ArrBasicInfo->merchantnote ?></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-4 control-label">
                                            Authorization Status</label>
                                        <div class="col-sm-8">
                                            <?php
                                            $VarAuthStatus = '';
                                            if(!empty($ArrBasicInfo->mgmtcurrentstatus)) {
                                                if($ArrBasicInfo->mgmtcurrentstatus == 1) {
                                                    $VarAuthStatus = 'PENDING';
                                                }
                                                if($ArrBasicInfo->mgmtcurrentstatus == 2) {
                                                    $VarAuthStatus = 'APPROVED';
                                                }
                                                if($ArrBasicInfo->mgmtcurrentstatus == 3) {
                                                    $VarAuthStatus = 'DECLINED';
                                                }
                                            }
                                            ?>
                                            <input type="text" class="form-control" readonly
                                                   value="<?php echo $VarAuthStatus; ?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-4 control-label">
                                            Authorization Type
                                        </label>
                                        <div class="col-sm-8">
                                            <select name="" id="" class="form-control" disabled>
                                                <option value="">Choose</option>
                                                <?php
                                                foreach ($ArrReqType as $key => $approvaltype) {
                                                    ?>
                                                    <option value="<?php echo $approvaltype ?>" <?php echo @$ArrBasicInfo->approvaltype == $approvaltype ? 'selected' : '' ?>>
                                                        <?php echo $approvaltype ?>
                                                    </option> <?php
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">
                                            Authorization By
                                        </label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" readonly value="<?php echo @$VarMgmtInfo[0]['contactname']; ?>">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label" for="authdatetime">Authorized Date & Time</label>
                                        <div class="col-sm-8">
                                            <input type="text" name="" class="form-control" id="authdatetime" readonly value="<?php echo @dateTimeHelp($ArrBasicInfo->authdatetime,false) ?>">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="enqdate" class="col-sm-4 control-label">Management Remarks</label>
                                        <div class="col-sm-8">
                                            <textarea readonly class="form-control" style="height: 64px"><?php if(!empty($ArrBasicInfo->mgmtremarks)) echo $ArrBasicInfo->mgmtremarks ?></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="enqdate" class="col-sm-4 control-label">Request Status</label>
                                        <div class="col-sm-8">
                                            <select class="form-control" id="frmCadDeptReqStatus" <?php if($ArrBasicInfo->deptcurrentstatus == 2) echo 'disabled' ?>>
                                                <option value="1" <?php if($ArrBasicInfo->deptcurrentstatus == 1) echo 'selected' ?>>PENDING</option>
                                                <option value="2" <?php if($ArrBasicInfo->deptcurrentstatus == 2) echo 'selected' ?>>ACCEPT</option>
                                                <option value="3" <?php if($ArrBasicInfo->deptcurrentstatus == 3) echo 'selected' ?>>REJECT</option>
                                            </select>
                                            <div class="herr" id="ErrfrmCadDeptReqStatus"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="enqdate" class="col-sm-4 control-label">Assign CAD Queue No.</label>
                                        <div class="col-sm-8">
                                            <?php
                                            //echo '<pre>'; print_r($ArrBasicInfo); die('');
                                            ?>
                                            <input type="text" name="" class="form-control" id="" readonly value="<?php echo @$ArrBasicInfo->queueno ?>">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="enqdate" class="col-sm-4 control-label">Queue No. Assigned Date & Time</label>
                                        <div class="col-sm-8">
                                            <input type="text" name="" class="form-control" id="" readonly value="<?php if(!empty($ArrBasicInfo->queueno_assigned_date))
                                                echo date('d-m-Y H:i:s',strtotime($ArrBasicInfo->queueno_assigned_date)) ?>">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="enqdate" class="col-sm-4 control-label">Job Scheduled Date & Time</label>
                                        <div class="col-sm-8">
                                            <?php
                                            ?>
                                            <div class='input-group date' id='datetimepicker1'>
                                                <input type='text' class="form-control" id="frmBasicJobSchedule">
                                                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                            </div>

                                        </div>
                                        <div class="herr" id="ErrfrmBasicJobSchedule"></div>
                                    </div>
                                    <!--<div class="form-group">
                                        <label for="completionStatus" class="col-sm-4 control-label">Job Completion Status</label>
                                        <div class="col-sm-8">
                                            <select class="form-control" id="completionStatus">
                                                <option value="1">PENDING</option>
                                                <option value="2">JOB COMPLETED</option>
                                            </select>

                                        </div>
                                    </div>-->
                                    <div class="form-group">
                                        <label for="enqdate" class="col-sm-4 control-label">Cad Dept. Remarks</label>
                                        <div class="col-sm-8">
                                            <textarea id="frmBasicCadDeptRemarks" class="form-control" style="height: 64px"><?php if(!empty($ArrBasicInfo->deptremarks)) echo $ArrBasicInfo->deptremarks ?></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="" class="col-sm-4 control-label">Current Status</label>
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
                                            if(!empty($ArrBasicInfo->current_status)) {
                                                $VarCs = $ArrBasicInfo->current_status;
                                            }
                                            ?>
                                            <input type="text" class="form-control" readonly id="CurrentStatus" value="<?php echo $VarCs ?>">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="enqdate" class="col-sm-4 control-label">Recent Update</label>
                                        <div class="col-sm-8">
                                                    <span class="form-control" id="recentUpdate" readonly="readonly">
                                                        <?php if(isset($ArrBasicInfo->dateupdated)) echo dateTimeHelp($ArrBasicInfo->dateupdated,false) ?>
                                                    </span>
                                        </div>
                                    </div>
                                    <div class="herr" id="ErrfrmBasicErr"></div>
                                </div>
                                <!--Content Ends-->
                            </form>
                        </div>
                        <div class="box-body">
                            <div class="form-group">
                                <label class="col-md-12">MERCHANT ATTACHMENTS</label>
                                <div class="form-group">
                                    <div class="col-sm-5" style="border:2px dotted #A5A5C7; padding: 10px; background-color: #eee">
                                        <ul class="list-group" style="list-style: none;">
                                            <?php
                                            $VarReqTypeFdrName = 'cadrequest';
                                            $VarMerchant = 'Merchant';
                                            $VarFdr = UPLOADS_SLASH.$VarReqTypeFdrName.DIRECTORY_SEPARATOR.$VarId.DIRECTORY_SEPARATOR.$VarMerchant.DIRECTORY_SEPARATOR;
                                            $ArrDwnExtensions = DWN_FILE_EXTENSIONS;
                                            if(file_exists($VarFdr)) {
                                                if ($dh = opendir($VarFdr)) {
                                                    while (($file = readdir($dh)) !== false) {
                                                        if(is_file($VarFdr . $file)) {
                                                            ?>
                                                            <li><div style="padding: 10px 0;">
                                                                    <?php echo $file .' ';
                                                                    $VarFileExt = pathinfo($file, PATHINFO_EXTENSION);
                                                                    $downUrl = base_url()."dashboard/commonSimpleDownload?id=".urlencode(base64_encode($VarId))."&fileName=".urlencode($file)."&folder=cadrequest&by=Merchant" ?>&nbsp;<a href="<?php echo $downUrl ?>">
                                                                        <i class="fa fa-download fa-lg" aria-hidden="true"></i>
                                                                    </a>&nbsp;&nbsp;
                                                <?php
                                            if(!in_array($VarFileExt,$ArrDwnExtensions)) {
                                                ?>
                                                <a href="<?php echo base_url()."dashboard/openFileInBrowser?id=".$VarId."&fileName=".$file."&folder=cadrequest&by=Merchant" ?>" target="_blank">
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
                        <div class="box-body">
                            <div class="form-group">
                                <?php
                                $VarReqTypeFdrName = 'cadrequest';
                                $VarCADDept = 'CADDept';
                                $VarFdr = UPLOADS_SLASH.$VarReqTypeFdrName.DIRECTORY_SEPARATOR.$VarId.DIRECTORY_SEPARATOR.$VarCADDept.DIRECTORY_SEPARATOR;
                                $ArrDwnExtensions = DWN_FILE_EXTENSIONS;
                                //echo '<pre>'; print_r($ArrBasicInfo->queuecompletestatus);
                                //echo '<pre>'; print_r($VarCurrentUserType);
                                //echo '<pre>'; print_r(getUserTypeId("CAD Dept.")); die('die');
                                if($ArrBasicInfo->queuecompletestatus != 1) {
                                if($VarCurrentUserType == getUserTypeId("CAD Dept.")) {
                                    //No Uploaded Files
                                    ?>
                                    <!-- CAD. Dept Upload -->
                                    <div class="box-body">
                                        <div class="form-group">
                                            <label>CAD Dept Upload</label>
                                            <div class="col-md-12">
                                                <div id="uploadFileCadDept" class="pdt10"></div>
                                            </div>
                                        </div>
                                        <!--<div class="form-group">
                                            <div class="col-sm-5"
                                                 style="border:2px dotted #A5A5C7; padding: 10px; background-color: #eee">
                                                <?php
/*                                                if(file_exists($VarFdr)) {
                                                    if ($dh = opendir($VarFdr)) {
                                                        while (($file = readdir($dh)) !== false) {
                                                            if(is_file($VarFdr.$file)) {
                                                                echo $file;
                                                            }
                                                        }
                                                    }
                                                }
                                                */?>

                                            </div>
                                        </div>-->
                                    </div>

                                    <!-- CAD. Dept Upload ENDS -->
                                    <?php
                                }
                                }
                                //else {
                                    //File present
                                    ?>
                                    <div class="form-group">
                                        <div class="col-md-12" style="padding-top: 20px">
                                            <label class="control-label">CAD DEPT. ATTACHMENTS</label>
                                        </div>
                                        <div class="col-sm-5"
                                             style="border:2px dotted #A5A5C7; padding: 10px; background-color: #eee">
                                            <ul class="list-group" style="list-style: none;">
                                            <?php
                                                if(file_exists($VarFdr)) {
                                                    if ($dh = opendir($VarFdr)) {
                                                        while (($file = readdir($dh)) !== false) {
                                                            if(is_file($VarFdr.$file)) {
                                                                ?>
                                                                <li><div style="padding: 10px 0;">
                                                                        <?php echo $file .' ';
                                                                        $VarFileExt = pathinfo($file, PATHINFO_EXTENSION);
                                                                        $downUrl = base_url()."dashboard/commonSimpleDownload?id=".urlencode(base64_encode($VarId))."&fileName=".urlencode($file)."&folder=cadrequest&by=CADDept";
                                                                        ?>&nbsp;<a href="<?php echo $downUrl ?>">
                                                                            <i class="fa fa-download fa-lg" aria-hidden="true"></i>
                                                                        </a>&nbsp;&nbsp;
                                                                        <?php
                                                                        if(in_array($VarFileExt,$ArrDwnExtensions)) {

                                                                        }
                                                                        else {
                                                                            ?>
                                                                            <a href="<?php echo base_url()."dashboard/openFileInBrowser?id=".$VarId."&folder=cadrequest&fileName=".$file."&by=CADDept" ?>" target="_blank">
                                                                                <i class="fa fa-file fa-lg" aria-hidden="true"></i>
                                                                            </a>
                                                                            <?php
                                                                        } ?>
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
                                    <?php
                                //}
                                ?>
                            </div>
                        </div>
                        <?php
                        if($VarCurrentUserType == getUserTypeId("CAD Dept.")) {
                            if($ArrBasicInfo->queuecompletestatus != 1) {
                            if($ArrBasicInfo->status == 1) {
                                ?>
                                <div class="box-footer nopadding" id="divSaveOrderBtn">
                                    <button type="submit" class="btn btn-info pull-right addrights" id="saveCadQueuenoAssign" onclick="return saveCadDeptStatus();">Save Changes</button>
                                </div>
                                <!-- /.box-footer -->
                                <?php
                            }
                            }
                        }
                        ?>
                    </div>
                    <div class="alert alert-success alert-dismissable hide" id="responseMsg"></div>
                </div>
            </div>
            <!-- /.row -->
            <!-- Password Modal Starts Here -->
            <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="myModalLabel">Enter PIN</h4>
                        </div>
                        <div class="modal-body">
                            <form class="form-horizontal col-md-3" method="post" id="frmPinformId">
                                <div id="divOuter">
                                    <div id="divInner">
                                        <input id="frmPin" type="password" maxlength="4"  />
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary" onclick="return fnCheckPin()">Continue</button>
                            <div class="herr pull-left" id="ErrAuthPin"></div>
                        </div>
                    </div>
                </div>
            </div>
        </section><!-- /.content -->
    </div><!-- /.content-wrapper -->
    <?php $this->load->view(CNFCOMPANY.'template/templatefooter');?>
    <div class="control-sidebar-bg"></div>
</div><!-- ./wrapper -->
<script src="<?php echo base_url();?>assets/js/moment.min.js"></script>
<script src="<?php echo base_url();?>assets/js/bootstrap-datetimepicker.js"></script>
<script src="<?php echo base_url();?>assets/plugins/datepicker/bootstrap-datepicker.js"></script>
<script src="<?php echo base_url();?>assets/js/jquery.uploadfile.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/jexcel4/jsuites.js"></script>
<script src="<?php echo base_url(); ?>assets/js/jexcel4/jexcel.js"></script>
<script type="text/javascript">
    var GlbId = "<?php echo @$VarId ?>";
    var attachmentDetails = '<?php echo $jsonAttachmentJxl ?>';
    var GlbJobDate = '';
    var GlbCurrentData = '<?php echo @$jsonDataGrid ?>';
    var cadRequestCount = 0;
    if(GlbCurrentData == '') {
        GlbCurrentData = [[]];
    }
    else {
        var jsonCurrentData = JSON.parse(GlbCurrentData);
        cadRequestCount = jsonCurrentData.length;
        console.log(jsonCurrentData,'jsonCurrentData');
    }
    var GlbIsrIorCode = $("#frmBasicWipRefNo").text();
    if(attachmentDetails != "") GlbJxlAttachmentDetails = JSON.parse(attachmentDetails);

    var GlbGeneratedRefNo = ''; var GlbRow = 0;
    var GlbCurrentStatus = ''; var GlbCadReqComplete = '';
    var jxlTbl = jexcel(document.getElementById('merchantCadReqJxl'), {
        columns:[
            { title:'Combo', width:150,readOnly:true },
            { title:'Component', width:150,readOnly:true },
            { title:'P. O. No.', width:150,readOnly:true },
            { title:'Size Spec Code', width:150,readOnly:true },
            { title:'Requirement', width:100,readOnly:true },
            { title:'Purpose', width:100,readOnly:true},
            { title:'Category', width:100,readOnly:true},
            { title:'If Revised or In-line Pre. CAD Ref. No.', width:160,readOnly:true },
            { title:'Required Size(s)', width:65,readOnly:true },
            { type:'text',title:'Assigned CAD Ref. No.', width:160,readOnly:true },
            { type:'checkbox',title:'Select Assign.', width:60 },
        ],
        allowInsertColumn: false,
        allowInsertRow: false,
        data: jsonCurrentData,
        onchange: function(instance, cell, x, y, value) {
            //var lastcol = instance.getColumnData('10');
            if(x == 10) {
                if(value) {
                    MakePostRequest(base_path+GlbCompanyFdr+'mcaduser/getCadRefNo',"rfrom=1&id="+GlbId+"&isrIorCode="+GlbIsrIorCode,
                        'json',fnGenerateCadRefNoRes);
                    function fnGenerateCadRefNoRes(data) {
                        if (data != '') {
                            if (data.errCode != undefined) {
                                if (data.errCode == '404') {
                                    fnCallSessionExpire();
                                    return false;
                                } else {
                                    if(data.refNo != '') {
                                        GlbGeneratedRefNo = data.refNo;
                                        GlbRow = y;
                                        console.log(GlbRow,'GlbRow');
                                        let tickCount = Number(GlbRow) + 1;
                                        console.log(tickCount,'tickCount');
                                        console.log(cadRequestCount,'cadRequestCount');
                                        if(cadRequestCount == tickCount) {
                                            GlbCurrentStatus = 'JOB COMPLETED';
                                            GlbCadReqComplete = 1;
                                        }
                                        else {
                                            GlbCurrentStatus = 'PARTLY COMPLETED';
                                            GlbCadReqComplete = 0;
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
                else {
                    GlbRow = 0;
                    GlbGeneratedRefNo = 0;
                }
            }
        },
        updateTable:function(instance, cell, col, row, val, label, cellName) {
            if(GlbGeneratedRefNo != '') {
                if(col == 9 && row == GlbRow) {
                    var sno = row + 1;
                    $(cell).html(GlbGeneratedRefNo+'-'+sno);
                    instance.jexcel.options.data[row][col] = GlbGeneratedRefNo+'-'+sno;
                }
            }
            if(col == 9) {
                if(GlbGeneratedRefNo == '') {
                    //$(cell).html('');
                    //instance.jexcel.options.data[row][col] = '';
                }
            }
            if(col == 10) {
                console.log(val,'val');
                if(val == true) {
                    $(cell).find('input').attr('disabled',true);
                }
            }
        }
    });
    $(document).ready(function() {
        extraObj     = $("#uploadFileCadDept").uploadFile({
            dragDrop: true,
            multiple:true,
            url:base_path+'dashboard/commonBasicFileUpload',
            returnType: "json",
            fileName:"myFile",
            allowedTypes: allowedFileTypes,
            dynamicFormData:function () {
                return "id="+GlbId+"&folderName=cadrequest&by=CADDept";
            },
            autoSubmit:false
        });
    });

    function saveCadDeptStatus() {
        $('.form-control').css("border", "1px solid #cccccc");
        $('div.herr').text('');
        GlbJobDate = $("#frmBasicJobSchedule").val();
        GlbStatusChange = 1;
        console.log(GlbStatusChange,'GlbStatusChange');
        $('#myModal').modal('show');
    }

    function fnCheckPin() {
        try {
            var pw = $("#frmPin").val();
            var jxlGrid = jxlTbl.getData();
            var deptRemarks = $("#frmBasicCadDeptRemarks").val();
            let completionStatus = $("#completionStatus").val();
            if (jsTrim(pw) == "") {
                $("#ErrAuthPin").text('Enter PIN');
                return false;
            }
            MakeAsynPostRequest(base_path+GlbCompanyFdr+'mcaduser/updateCadReqWithPIN',"rfrom=1&i="+pw+"&reqId="+GlbId+"&completed="+GlbCadReqComplete+"&current_status="+
                GlbCurrentStatus+"&js="+GlbJobDate+"&jxlGrid="+JSON.stringify(jxlGrid)+"&deptRemarks="+deptRemarks,'json',saveCadDeptStatusRes);
        }
        catch(e) {
            alert(e);
        }
    }

    function saveCadDeptStatusRes(data) {
        if(data != '') {
            if(data.errcode == 1) {
                if(data.dt != '') {
                    $('#myModal').modal('hide');
                    extraObj.startUpload();
                    if(GlbJobDate != '' && GlbJobDate != '00-00-0000 00:00:00') {
                        if(GlbCurrentStatus == '') {
                            GlbCurrentStatus = 'JOB SCHEDULED';
                            GlbCadReqComplete = 0;
                        }
                    }
                    $("#CurrentStatus").val(GlbCurrentStatus);
                    $("#recentUpdate").text(data.dt);
                    $("#responseMsg").removeClass('hide');
                    $("#responseMsg").text('Saved Successfully');
                    //fnRedirectPageTimeOut(base_path+GlbCompanyFdr+'mcaduser/cadqueuelist');
                }
            }
            else {
                $("#ErrAuthPin").text(data.msg)
            }
        }
    }

    jexcel(document.getElementById('jxlAttachmentDetails'), {
        columns:[
            { title:'Combo', width:150, readOnly:true },
            { title:'Component', width:150, readOnly:true },
            { title:'P. O. No.', width:150, readOnly:true },
            { title:'Size Spec Code', width:150, readOnly:true },
            { title:'Approved & Graded Measurement Chart', width:150, readOnly:true },
            { title:'Complete Artwork', width:150, readOnly:true },
            { title:'How to Measure Details', width:150, readOnly:true },
            { title:'Buyer’s Original Sample or Pattern', width:150, readOnly:true },
            { title:'Buyer’s Comments', width:145, readOnly:true },
        ],
        onchange: function(instance, cell, x, y, value) {

        },
        updateTable: function (instance, cell, col, row, val, label, cellName) {

        },
        columnDrag:true,
        allowInsertColumn: false,
        data : GlbJxlAttachmentDetails
    });
    $('#datetimepicker1').datetimepicker({
        defaultDate: '<?php echo $ArrBasicInfo->jobschedule ?>',
        format: 'DD-MM-YYYY HH:mm:ss'
    });
</script>
<?php $this->load->view(CNFCOMPANY.'template/pagefooter'); ?>