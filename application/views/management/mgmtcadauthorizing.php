<?php $this->load->view(CNFCOMPANY . 'template/pageheader'); ?>
<link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>assets/css/bootstrap-datetimepicker-standalone.css"/>
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
                            <div class="col-md-12 pd0 no-padding">
                                <?php $this->load->view('commonBasicInfoOrderEntry'); ?>
                            </div>
                        </div>
                    </div>
                    <div class="box-header with-border" style="padding: 0 10px">
                        <h3 class="box-title">
                            CAD REQUEST
                        </h3>
                    </div>
                    <div class="box box-info">
                        <div class="box-header with-border with-bgColor">
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
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="frmBasicRequestType" class="col-sm-4 control-label">Request Type</label>
                                        <div class="col-sm-8">
                                            <select name="" id="frmBasicRequestType" class="form-control" disabled>
                                                <option value="">Choose</option>
                                                <?php
                                                $ArrReqType = unserialize(ARRREQUESTTYPE);
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
                                            echo dateTimeHelp(@$ArrBasicInfo->datecreated,false) ?>">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-4 control-label">Cutoff Date & Time</label>
                                        <div class="col-sm-8">
                                            <div class='input-group date' id='datetimepicker1'>
                                                <input type='text' readonly class="form-control" id="frmBasicCutoffdatetime" value="<?php if(isset($ArrBasicInfo->cutoffdatetime)) echo date('d-m-Y H:i:s',strtotime($ArrBasicInfo->cutoffdatetime)) ?>" />
                                                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                            </div>
                                            <div class="herr" id="ErrfrmBasicCutoffdatetime"></div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-4 control-label">Merchant Note</label>
                                        <div class="col-sm-8">
                                            <textarea id="frmBasicMerchantNote" readonly class="form-control" style="height: 65px"><?php if(isset($ArrBasicInfo->merchantnote)) echo $ArrBasicInfo->merchantnote ?></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group">

                                        <label for="inputEmail3" class="col-sm-4 control-label">Authorization Status</label>
                                        <div class="col-sm-8">
                                            <select class="form-control" id="mgmtAuthStatus">
                                                <option value="1" <?php if($ArrBasicInfo->mgmtcurrentstatus == 1) echo 'selected' ?>>PENDING</option>
                                                <option value="2" <?php if($ArrBasicInfo->mgmtcurrentstatus == 2) echo 'selected' ?>>APPROVE</option>
                                                <option value="3" <?php if($ArrBasicInfo->mgmtcurrentstatus == 3) echo 'selected' ?>>DECLINE</option>
                                            </select>
                                            <div class="herr" id="ErrMgmtAuthStatus"></div>
                                            <?php
                                            ?>
                                        </div>

                                    </div>
                                </div>
                                <div class="col-md-4">

                                    <div class="form-group">
                                        <label for="ApprovalType" class="col-sm-4 control-label">
                                            Authorization Type
                                        </label>
                                        <div class="col-sm-8">
                                            <select name="" id="ApprovalType" class="form-control">
                                                <option value="">Choose</option>
                                                <?php
                                                foreach ($ArrReqType as $key => $approvaltype) {
                                                    ?>
                                                    <option
                                                            value="<?php echo $approvaltype ?>" <?php echo @$ArrBasicInfo->approvaltype == $approvaltype ? 'selected' : '' ?>><?php echo $approvaltype ?></option> <?php
                                                }
                                                ?>
                                            </select>
                                            <div class="herr" id="ErrApprovalType"></div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="enqdate" class="col-sm-4 control-label">Authorized By</label>
                                        <div class="col-sm-8">
                                            <input type="text" name="" class="form-control" id="" readonly value="<?php
                                             echo @$VarAuthMgmtInfo[0]['contactname']; ?>">
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
                                            <textarea class="form-control" id="MgmtRemarks" style="height: 64px"><?php if(!empty($ArrBasicInfo->mgmtremarks)) echo $ArrBasicInfo->mgmtremarks ?></textarea>
                                            <div class="herr" id="ErrMgmtRemarks"></div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="enqdate" class="col-sm-4 control-label">Request Status</label>
                                        <div class="col-sm-8">
                                            <select disabled id="" class="form-control">
                                                <option value="1" <?php if($ArrBasicInfo->deptcurrentstatus == 1) echo 'selected' ?>>PENDING</option>
                                                <option value="2" <?php if($ArrBasicInfo->deptcurrentstatus == 2) echo 'selected' ?>>ACCEPT</option>
                                                <option value="3" <?php if($ArrBasicInfo->deptcurrentstatus == 3) echo 'selected' ?>>REJECT</option>
                                            </select>
                                        </div>
                                    </div>

                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="enqdate" class="col-sm-4 control-label">Assign CAD Queue No.</label>
                                        <div class="col-sm-8">
                                            <input type="text" name="" class="form-control" id="" readonly value="<?php echo @$ArrBasicInfo->queueno ?>">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="enqdate" class="col-sm-4 control-label">Queue No. Assigned Date & Time</label>
                                        <div class="col-sm-8">
                                            <input type="text" name="" class="form-control" id="" readonly value="<?php if(empty($ArrBasicInfo->queueno_assigned_date)) echo ''; else echo date('d-m-Y H:i:s',strtotime($ArrBasicInfo->queueno_assigned_date)) ?>">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="enqdate" class="col-sm-4 control-label">Job Scheduled Date & Time</label>
                                        <div class="col-sm-8">
                                            <input type="text" name="" class="form-control" id="" readonly value="<?php if(empty($ArrBasicInfo->jobschedule)) echo ''; else echo date('d-m-Y H:i:s',strtotime($ArrBasicInfo->jobschedule)) ?>">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="enqdate" class="col-sm-4 control-label">Cad Dept. Remarks</label>
                                        <div class="col-sm-8">
                                            <textarea readonly class="form-control" style="height: 64px"><?php if(!empty($ArrBasicInfo->deptremarks)) echo $ArrBasicInfo->deptremarks ?></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="enqdate" class="col-sm-4 control-label">Current Status</label>
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
                                            <input type="text" class="form-control" readonly
                                                   id="MgmtCurrentStatus" value="<?php echo $VarCs ?>">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="enqdate" class="col-sm-4 control-label">Recent Update</label>
                                        <div class="col-sm-8"><span class="form-control" id="recentupdate" readonly="readonly"><?php echo @dateTimeHelp($ArrBasicInfo->dateupdated) ?></span>
                                            <span class="form-control hide" id="recentupdateCs" readonly="readonly"></span>
                                        </div>
                                    </div>
                                </div>
                            </form>
                            <!--Content Ends-->
                        </div>
                        <div class="box-body">
                            <div class="form-group">
                                <div class="form-group">
                                    <div class="col-md-12" style="padding-top: 20px">
                                        <label class="control-label">MERCHANT ATTACHMENTS</label>
                                    </div>
                                    <div class="col-sm-5" style="border:2px dotted #A5A5C7; padding: 10px; background-color: #eee">
                                        <ul class="list-group" style="list-style: none;">
                                            <?php
                                            $VarReqTypeFdrName = 'cadrequest';
                                            $VarBy = 'Merchant';
                                            $VarFdr = UPLOADS_SLASH.$VarReqTypeFdrName.DIRECTORY_SEPARATOR.$VarId.DIRECTORY_SEPARATOR.$VarBy.DIRECTORY_SEPARATOR;
                                            $ArrDwnExtensions = DWN_FILE_EXTENSIONS;
                                            if (file_exists($VarFdr)) {
                                                if ($dh = opendir($VarFdr)) {
                                                    while (($file = readdir($dh)) !== false) {
                                                        if (is_file($VarFdr.$file)) {
                                                            ?>
                                                            <li>
                                                                <div style="padding: 10px 0;">
                                                                    <?php echo $file . ' ';
                                                                    $VarFileExt = pathinfo($file, PATHINFO_EXTENSION);
                                                                    $downUrl = base_url()."dashboard/commonSimpleDownload?id=".urlencode(base64_encode($VarId))."&fileName=".urlencode($file)."&folder=".$VarReqTypeFdrName."&by=".$VarBy;
                                                                    ?>&nbsp;
                                                                    <a href="<?php echo $downUrl ?>">
                                                                        <i class="fa fa-download fa-lg" aria-hidden="true"></i>
                                                                    </a>&nbsp;&nbsp;
                                                <?php
                                            if(!in_array($VarFileExt,$ArrDwnExtensions)) {
                                                ?>
                                                <a href="<?php echo base_url()."dashboard/openFileInBrowser?id=".$VarId."&fileName=".urlencode($file)."&folder=".$VarReqTypeFdrName."&by=".$VarBy ?>"
                                                   target="_blank">
                                                    <i class="fa fa-file fa-lg"
                                                       aria-hidden="true"></i>
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
                                            } else {
                                                echo 'No attachments';
                                            }
                                            ?>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php
                        if (empty($ArrBasicInfo->queueno)) {
                            if ($ArrBasicInfo->status == 1) {
                                if ($mgmtcurrentstatus != 2) {
                                    ?>
                                        <!--<div class="alert alert-success alert-dismissable hide" id="divSuccessBasicInfoMsg"></div>-->
                                        <div class="box-footer nopadding" id="divSaveOrderBtn">
                                            <!--<button type="button" class="btn btn-default"
                                                    onclick="fnShowHideEndUserSub(1,'divShowBasicInfo');">Cancel
                                            </button>-->
                                            <button type="submit" class="btn btn-info pull-right addrights" id="saveCadRequestMgmtAuth" onclick="fnSaveAllRequestAuth()">
                                                Save Changes
                                            </button>
                                        </div><!-- /.box-footer -->
                                <?php
                                }
                            }
                        }
                        ?>
                        <div class="alert alert-success alert-dismissable hide" id="divSuccessBasicInfoMsg"></div>
                    </div>
                    <div class="herr" id="ErrfrmBasicErr"></div>
                </div>

            </div><!-- /.row -->

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
                                        <input id="frmPin" type="password" maxlength="4" autocomplete="off">
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
    <?php $this->load->view(CNFCOMPANY . 'template/templatefooter'); ?>
    <div class="control-sidebar-bg"></div>
</div><!-- ./wrapper -->
<script src="<?php echo base_url();?>assets/js/moment.min.js"></script>
<script src="<?php echo base_url();?>assets/js/bootstrap-datetimepicker.js"></script>
<script src="<?php echo base_url(); ?>assets/js/jexcel4/jsuites.js"></script>
<script src="<?php echo base_url(); ?>assets/js/jexcel4/jexcel.js"></script>
<script>
    var GlbId = "<?php echo @$VarId ?>";
    var GlbOrderId = '<?php echo @$orderid ?>';

    var GlbCurrentData = '<?php echo @$jsonDataGrid ?>';
    var GlbJsonAttachmentJxl = '<?php echo @$jsonAttachmentJxl ?>';
    if(GlbCurrentData == '') {
        GlbCurrentData = 0;
    }
    else {
        var jsonCurrentData = JSON.parse(GlbCurrentData);
        console.log(jsonCurrentData,'jsonCurrentData');
    }
    if(GlbJsonAttachmentJxl != '') {
        GlbJsonAttachmentJxl = JSON.parse(GlbJsonAttachmentJxl);
    }
    else {
        GlbJsonAttachmentJxl = [[]];
    }
    var jxlTbl = jexcel(document.getElementById('merchantCadReqJxl'), {
        columns:[
            { title:'Combo', width:150, readOnly: true },
            { title:'Component', width:150, readOnly: true },
            { title:'P. O. No.', width:150, readOnly: true },
            { title:'Size Spec Code', width:150, readOnly: true },
            { title:'Requirement', width:120, readOnly: true },
            { title:'Purpose', width:120, readOnly: true },
            { title:'Category', width:120, readOnly: true },
            { title:'If Revised or In-line Pre. CAD Ref. No.', width:160, readOnly: true },
            { title:'Required Size(s)', width:65, readOnly: true },
            { type:'text',title:'Assigned CAD Ref. No.', width:160, readOnly: true },
            { type: 'hidden'}
        ],
        onchange: function(instance, cell, x, y, value) {

        },
        columnDrag:true,
        allowInsertColumn: false,
        data: jsonCurrentData
    });

    jexcel(document.getElementById('jxlAttachmentDetails'), {
        data: GlbJsonAttachmentJxl,
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
        allowInsertColumn: false
    });
    var url = $(location).attr('href'); var lasturlpart = url.substr(url.lastIndexOf('/')+1);
    if(lasturlpart == 'managecadrequest') {

    }
    else if(lasturlpart == 'managemgmtcadrequest') {
        //alert(lasturlpart);
    }

    var GlbMgmtAuthStatus = "", GlbMgmtRemarks = "", GlbApprovalType = "";
    function fnSaveAllRequestAuth() {
        try {
            $('.form-control').css("border", "1px solid #cccccc");
            $('div.herr').text('');
            GlbMgmtAuthStatus = $("#mgmtAuthStatus").val();
            GlbMgmtRemarks = $("#MgmtRemarks").val();
            GlbApprovalType = $("#ApprovalType").val();
            if (GlbMgmtAuthStatus == 1) {
                $('#ErrMgmtAuthStatus').html("Select APPROVE / DECLINE");
                $('#mgmtAuthStatus').focus();
                $('#mgmtAuthStatus').css("border", "1px solid #B94A48");
                return false;
            }
            if(GlbApprovalType == 0) {
                $('#ErrApprovalType').html("Select Authorization Type");
                $('#ApprovalType').focus();
                $('#ApprovalType').css("border", "1px solid #B94A48");
                return false;
            }
            if (jsTrim(GlbMgmtRemarks) == "") {
                $('#ErrMgmtRemarks').html("Please fill the Management Remarks");
                $('#MgmtRemarks').focus();
                $('#MgmtRemarks').css("border", "1px solid #B94A48");
                return false;
            }
            if(GlbMgmtAuthStatus != "") {
                $('#myModal').modal('show');
                return false;
            }
            else {
                $('#ErrfrmBasicErr').text("Select Accept / Reject");
                return false;
            }
            if(GlbApprovalType == "") {
                $('#ErrApprovalType').text("Please Select Approval Type");
                $('#ApprovalType').focus();
                $('#ApprovalType').css("border", "1px solid #B94A48");
                return false;
            }

        } catch(e) {
            alert(e);
        }
    }

    function fnCheckPin() {
        $(".herr").text('');
        try {
            var pw = $("#frmPin").val();
            if(jsTrim(pw) == "") {
                $("#ErrfrmPin").text('Enter PIN');
                return false;
            }
            if(GlbMgmtAuthStatus == 2) {
                var currentStatus = 'APPROVED';
            }
            else if(GlbMgmtAuthStatus == 3) {
                var currentStatus = 'DECLINED';
            }
            MakePostRequest(base_path + 'management/cadRequestCheckPin',"rfrom=1&pwd="+pw+"&id="+GlbId+"&mgmtStatus="+
                GlbMgmtAuthStatus+"&mgmtRemarks="+GlbMgmtRemarks+"&approvalType="+GlbApprovalType+"&currentStatus="+currentStatus,'json',fnAuthRes);
            return false;
        } catch (e) {
            alert(e);
        }
    }

    function fnAuthRes(data) {
        console.log(data,'data');
        if(data!='') {
            if(data.errcode == '404') {
                fnCallSessionExpire();
                return false;
            } else if(data.errcode=='-1'){
                $("#ErrfrmPin").text(data.msg);
                return false;
            } else if(data.errcode=='1') {
                $('#myModal').modal('hide');
                if(data.cs==2) {
                    $("#MgmtCurrentStatus").val('APPROVED');
                    //fnRedirectPageTimeOut(base_path+'management/manageauthorizationrequest');
                }
                else if(data.cs==3) {
                    $("#MgmtCurrentStatus").val('DECLINED');
                }
                //$("#saveCadRequestMgmtAuth").remove();
                $("#authdatetime").val(data.dateTime);
                $("#divSuccessBasicInfoMsg").removeClass('hide');
                $("#divSuccessBasicInfoMsg").text('Saved Successfully');
                fnRedirectPageTimeOut(base_path+'management/manageAuthorizationRequest');
            }
        }
    }

    $("#divLog").hide();
    //$("#divCompleteLog").hide();
    function fnShowCadRequestLog(showdivid,hidedivid) {
        $("#logcircle").removeClass('fa fa-circle-o');
        $("#logcircle").addClass('fa fa-circle');
        $("#basicInfoCircle").removeClass('fa fa-circle');
        $("#basicInfoCircle").addClass('fa fa-circle-o');
        $("#"+showdivid).show();
        $("#"+hidedivid).hide();
        fnCadLogList();
    }

    /*function fnShowCadCompleteLog(showdivid,hidedivid,hidethistwo) {
        $("#logcircle").removeClass('fa fa-circle');
        $("#logcircle").addClass('fa fa-circle-o');
        $("#basicInfoCircle").removeClass('fa fa-circle');
        $("#basicInfoCircle").addClass('fa fa-circle-o');
        $("#logcompletecircle").removeClass('fa fa-circle-o');
        $("#logcompletecircle").addClass('fa fa-circle');
        $("#"+showdivid).show();
        $("#"+hidedivid).hide();
        $("#"+hidethistwo).hide();
        fnCadCompleteLogList();
    }*/

    function fnCadLogList() {
        MakePostRequest(base_path + 'dashboard/cadloglist',"rfrom=1"+"&id="+GlbId,'json',fnCadLogListRes);
    }

    function fnCadLogListRes(data) {
        if(data!='') {
            console.log(data,'data');
            if(data.errcode!=undefined) {
                if(data.errcode == '404') {
                    fnCallSessionExpire();
                    return false;
                } else {
                    var PageContent='';
                    if(data.cn>0) {
                        ListCount	= '<div style="font-weight:bold;">Number of Record(s) : '+data.cn+'</div>';
                        if(data.ct>0) {
                            $.each(data.re,function(index,value) {
                                PageContent=PageContent+'<tr><td>'+value.du+'</td>' +
                                    '<td>'+value.cs+'</td>'+
                                    '<td>'+value.rem+'</td>'+
                                    '<td><a href="'+base_path+'dashboard/cadreqLogDetail/'+encodeURIComponent(base64_encode(value.id))+'">View</a></td>';
                                PageContent=PageContent+'</tr>';
                            });
                        }
                        $("#DivTotalCntResult").html(ListCount);
                    } else {
                        PageContent	= PageContent+'<tr><td colspan="6" class="pdl15 herr text-center" style="padding-left:10px;">No Records(s) found</td></tr>';
                        $("#DivTotalCntResult").html('');
                    }

                    if(data.pa!=undefined) {
                        $("#ResPagination").html(base64_decode(data.pa));
                    }
                    //$("tbody").empty();
                    $("#cadLogList").append(PageContent);
                }
            }
        }
    }

    function fnShowBasicInfo() {
        window.location.href = base_path+'management/mgmtcadauthorizing/'+lasturlpart;
    }
</script>
<?php $this->load->view(CNFCOMPANY . 'template/pagefooter'); ?>