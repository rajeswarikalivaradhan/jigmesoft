<?php $this->load->view(CNFCOMPANY.'template/pageheader');?>
<link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>assets/css/bootstrap-datetimepicker-standalone.css">
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/jexcel4/jsuites.css"/>
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/jexcel4/jexcel.css"/>
<body class="sidebar-mini skin-black wysihtml5-supported sidebar-collapse">
<div class="wrapper">
    <?php $this->load->view(CNFCOMPANY.'template/templateheader');?>
    <!-- Left side column. contains the logo and sidebar -->
    <aside class="main-sidebar">
        <?php $this->load->view(CNFCOMPANY.'template/templateleftmenu');?>
    </aside>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper order-entry">
        <!-- Main content -->
        <!--<section class="content-header">
            <h1>CAD REQUEST RECEIVED</h1>
        </section>-->
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
                            <!--Content-->
                            <form class="form-horizontal" name="frmBasicInfo" id="frmBasicInfo" method="post" autocomplete="off">
                                <div class="alert alert-success alert-dismissable hide" id="divSuccessBasicInfoMsg"></div>
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
                                        <label for="inputEmail3" class="col-sm-4 control-label">Request Date & Time</label>
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
                                        <label for="inputEmail3" class="col-sm-4 control-label">Authorization
                                            Status</label>
                                        <div class="col-sm-8">
                                            <?php
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
                                    <?php

                                    ?>
                                    <div class="form-group">
                                        <label for="reqAuthType" class="col-sm-4 control-label">Authorization
                                            Type</label>
                                        <div class="col-sm-8">
                                            <select name="" id="reqAuthType" class="form-control" disabled>
                                                <option value="">Choose</option>
                                                <?php
                                                foreach ($ArrReqType as $key => $approvaltype) {
                                                    ?>
                                                    <option
                                                            value="<?php echo $approvaltype ?>" <?php echo @$ArrBasicInfo->approvaltype == $approvaltype ? 'selected' : '' ?>><?php echo $approvaltype ?></option> <?php
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
                                            <input type="text" class="form-control" readonly
                                                   value="<?php echo @$VarMgmtInfo[0]['contactname']; ?>">
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
                                            <select name="" id="frmCadDeptAcceptReject" class="form-control">
                                                <option value="" <?php if($ArrBasicInfo->deptcurrentstatus == 1) echo 'selected' ?>>PENDING</option>
                                                <option value="2" <?php if($ArrBasicInfo->deptcurrentstatus == 2) echo 'selected' ?>>ACCEPT</option>
                                                <option value="3" <?php if($ArrBasicInfo->deptcurrentstatus == 3) echo 'selected' ?>>REJECT</option>
                                            </select>
                                            <div class="herr" id="ErrfrmCadDeptAcceptReject"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="enqdate" class="col-sm-4 control-label">Assign CAD Queue No.</label>
                                        <div class="col-sm-8">
                                            <input type="text" name="" class="form-control" id="cadqueueno" readonly value="<?php echo @$ArrBasicInfo->queueno ?>">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="enqdate" class="col-sm-4 control-label">Queue No. Assigned Date & Time</label>
                                        <div class="col-sm-8">
                                            <input type="text" name="" class="form-control" id="assigneddatetime" readonly value="<?php if(empty($ArrBasicInfo->queueno_assigned_date)) echo ''; else echo date('d-m-Y H:i:s',strtotime($ArrBasicInfo->queueno_assigned_date)) ?>">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="enqdate" class="col-sm-4 control-label">Job Scheduled Date & Time</label>
                                        <div class="col-sm-8">
                                            <div class='input-group date' id='datetimepicker1'>
                                                <input type='text' class="form-control" id="frmBasicJobSchedule">
                                                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                            </div>
                                        </div>
                                        <div class="herr" id="ErrfrmBasicJobSchedule"></div>
                                    </div>
                                    <div class="form-group">
                                        <label for="enqdate" class="col-sm-4 control-label"><?php echo $ArrBasicInfo->request_type_dept ?> Dept. Remarks</label>
                                        <div class="col-sm-8">
                                            <textarea id="frmBasicCadDeptRemarks" class="form-control" style="height: 64px"><?php if(!empty($ArrBasicInfo->deptremarks)) echo $ArrBasicInfo->deptremarks ?></textarea>
                                        </div>
                                        <div class="herr" id="ErrfrmBasicCadDeptRemarks"></div>
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
                                            ?>
                                            <input type="text" id="CurrentStatus" readonly class="form-control" value="<?php echo $VarCs ?>">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="enqdate" class="col-sm-4 control-label">Recent Update</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" id="recentupdate" readonly value="<?php if(isset($ArrBasicInfo->dateupdated)) echo date('d-m-Y H:i:s',strtotime($ArrBasicInfo->dateupdated)) ?>">
                                            <span class="form-control hide" id="recentupdateCs" readonly="readonly"></span>
                                        </div>
                                    </div>
                                    <div class="herr" id="ErrfrmBasicErr"></div>
                                </div>
                            </form>
                            <!--Content Ends-->

                        </div>
                        <div class="box-body">
                            <div class="form-group">
                                <label for="enqdate" class="col-md-12">MERCHANT ATTACHMENTS</label>
                                <div class="form-group">
                                    <div class="col-sm-5" style="border:2px dotted #A5A5C7; padding: 10px; background-color: #eee">
                                        <ul class="list-group" style="list-style: none;">
                                            <?php
                                            $VarReqTypeFdrName = 'cadrequest';
                                            $VarBy = 'Merchant';
                                            $VarFdr = UPLOADS_SLASH.$VarReqTypeFdrName.DIRECTORY_SEPARATOR.$VarId.DIRECTORY_SEPARATOR.$VarBy.DIRECTORY_SEPARATOR;
                                            $ArrDwnExtensions = DWN_FILE_EXTENSIONS;
                                            if(file_exists($VarFdr)) {
                                                if ($dh = opendir($VarFdr)) {
                                                    while (($file = readdir($dh)) !== false) {
                                                        if(is_file($VarFdr.$file)) {
                                                            ?>
                                                            <li><div style="padding: 10px 0;">
                                                                    <?php echo $file .' ';
                                                                    $VarFileExt = pathinfo($file, PATHINFO_EXTENSION);
                                                                    $downUrl = base_url()."dashboard/commonSimpleDownload?id=".urlencode(base64_encode($VarId))."&fileName=".urlencode($file)."&folder=cadrequest&by=".$VarBy ?>&nbsp;
                                                                    <a href="<?php echo $downUrl ?>">
                                                                        <i class="fa fa-download fa-lg" aria-hidden="true"></i>
                                                                    </a>&nbsp;&nbsp;
                                                <?php
                                            if(in_array($VarFileExt,$ArrDwnExtensions)) {

                                            }
                                            else {
                                                ?>
                                                <a href="<?php echo base_url()."dashboard/openFileInBrowser?id=".$VarId."&fileName=".$file."&folder=cadrequest&by=".$VarBy ?>" target="_blank">
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
                        <div class="box-footer nopadding" id="divSaveOrderBtn">
                            <?php
                            if($ArrBasicInfo->status == 1) {
                                ?>
                                <button type="submit" class="btn btn-info pull-right" id="saveCadQueueNoAssign" onclick="return fnSaveCadQueueNo();">Save Changes</button>
                                <?php
                            }
                            ?>
                        </div><!-- /.box-footer -->
                        <div class="alert alert-success alert-dismissable hide" id="successResponseMsg"></div>
                    </div>
                </div>
            </div>
            <!-- Password Modal Starts Here -->
            <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="myModalLabel">Enter PIN</h4>
                        </div>
                        <div class="modal-body">
                            <form class="form-horizontal col-md-3" method="post" id="frmPinformId" autocomplete="off">
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
                            <div class="herr pull-left" id="ErrfrmPin"></div>
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
<script src="<?php echo base_url(); ?>assets/js/jexcel4/jsuites.js"></script>
<script src="<?php echo base_url(); ?>assets/js/jexcel4/jexcel.js"></script>
<script>
    var GlbId          	        = "<?php echo @$VarId;?>";
    var GlbRequestListType      = "<?php echo @$ArrBasicInfo->request_type_dept ?>";
    var GlbCurrentStatus = '';
    var GlbJsonAttachmentJxl = '<?php echo @$jsonAttachmentJxl ?>';
    if(GlbJsonAttachmentJxl != '') {
        GlbJsonAttachmentJxl = JSON.parse(GlbJsonAttachmentJxl);
    }
    else {
        GlbJsonAttachmentJxl = [[]];
    }
    var GlbPrevCadRefNo = '<?php echo @$ArrPrevCadRefNo ?>';
    console.log(GlbPrevCadRefNo,'GlbPrevCadRefNo');

    var GlbCurrentData = '<?php echo @$jsonDataGrid ?>';
    if(GlbCurrentData == '') {
        GlbCurrentData = 0;
    }
    else {
        var jsonCurrentData = JSON.parse(GlbCurrentData);
        console.log(jsonCurrentData,'jsonCurrentData');
    }
    var url = $(location).attr('href'); var lasturlpart = url.substr(url.lastIndexOf('/')+1);
    if(lasturlpart == 'managecadrequest') {

    }
    else if(lasturlpart == 'managemgmtcadrequest') {
        //alert(lasturlpart);
    }
    var jxlTbl = jexcel(document.getElementById('merchantCadReqJxl'), {
        columns:[
            { title:'Combo', width:150 },
            { title:'Component', width:150 },
            { title:'P. O. No.', width:150 },
            { title:'Size Spec Code', width:150 },
            { title:'Requirement', width:100 },
            { title:'Purpose', width:100 },
            { title:'Category', width:100 },
            //{ type:'dropdown',title:'If Revised or In-line Pre. CAD Ref. No.', width:110,source: JSON.parse(GlbPrevCadRefNo) },
            { title:'If Revised or In-line Pre. CAD Ref. No.', width:160 },
            { title:'Required Size(s)', width:65 },
            { type:'text',title:'Assigned CAD Ref. No.', width:160, wordWrap: true, readOnly: true },
            { type:'checkbox',title:'Select Assign.', width:60, readOnly: true },
        ],
        columnDrag:true,
        allowInsertColumn: false,
        allowInsertRow: false,
        minDimensions: [6,1],
        data: jsonCurrentData,
    });


    var GlbfrmCadDeptAcceptReject = '', GlbfrmBasicCadDeptRemarks = '', GlbfrmBasicJobSchedule = '';
    function fnSaveCadQueueNo() {
        try {
            $('.form-control').css("border", "1px solid #cccccc");
            $('div.herr').text('');
            GlbfrmCadDeptAcceptReject     = $("#frmCadDeptAcceptReject").val();
            GlbfrmBasicCadDeptRemarks     = $("#frmBasicCadDeptRemarks").val();
            GlbfrmBasicJobSchedule        = $("#frmBasicJobSchedule").val();
            if(GlbfrmBasicCadDeptRemarks == "") {
                $('#ErrfrmBasicCadDeptRemarks').text("Please Enter Remarks");
                $('#frmBasicCadDeptRemarks').focus();
                $('#frmBasicCadDeptRemarks').css("border", "1px solid #B94A48");
                return false;
            }
            if(GlbfrmCadDeptAcceptReject != "") {
                $('#myModal').modal('show');
                return false;
            }
            else {
                $('#ErrfrmBasicErr').text("Choose Accept or Reject");
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
            var GlbIsriorcode = $("#frmBasicWipRefNo").text();
            if(jsTrim(pw) == "") {
                $("#ErrfrmPin").text('Enter PIN');
                return false;
            }
            var newJxlData = jxlTbl.getData();
            MakePostRequest(base_path + GlbCompanyFdr+'mcaduser/fnCheckPinForCadQueueNo',"rfrom=1&reqlisttypeid="+GlbRequestListType+"&i="+pw+
                "&crid="+GlbId+"&s="+GlbfrmCadDeptAcceptReject+"&jS="+GlbfrmBasicJobSchedule+"&rem="+
                GlbfrmBasicCadDeptRemarks+"&isriorcode="+GlbIsriorcode+"&jxl="+JSON.stringify(newJxlData),'json',fnAuthRes);
            return false;
        } catch (e) {
            alert(e);
        }
    }

    function fnAuthRes(data) {
        if(data!='') {
            if(data.errcode == '404') {
                fnCallSessionExpire();
                return false;
            } else if(data.errcode=='-1'){
                $('#ErrfrmPin').text(data.msg);
                return false;
            } else if(data.errcode=='1') {
                $('#myModal').modal('hide');
                $("#saveCadQueueNoAssign").remove();
                if(data.qno != '') {
                    $("#cadqueueno").val(data.qno);
                    $("#assigneddatetime").val(data.adt);
                    //$("#divSuccessBasicInfoMsg").text("APPROVED");
                }
                if(GlbfrmCadDeptAcceptReject == "2") {
                    if(GlbfrmBasicJobSchedule != '') {
                        GlbCurrentStatus = 'JOB SCHEDULED';
                    }
                    else {
                        GlbCurrentStatus = 'ACCEPTED';
                    }

                }
                else if (GlbfrmCadDeptAcceptReject == "3") {
                    GlbCurrentStatus = 'REJECTED';
                }
                $("#CurrentStatus").val(GlbCurrentStatus);
                $("#successResponseMsg").removeClass('hide');
                $("#successResponseMsg").text('Saved Successfully');
                fnRedirectPageTimeOut(base_path+GlbCompanyFdr+'mcaduser/cadqueuelist');
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
        data : GlbJsonAttachmentJxl
    });

    $(function () {
        $('#datetimepicker1').datetimepicker({
            format: 'DD-MM-YYYY HH:mm:ss'
        });
    });

</script>
<?php $this->load->view(CNFCOMPANY.'template/pagefooter'); ?>