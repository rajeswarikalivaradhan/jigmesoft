<?php $this->load->view(CNFCOMPANY.'template/pageheader');?>
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/jexcel4/jsuites.css"/>
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/jexcel4/jexcel.css"/>
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/uploadfile-order.css"/>
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
        <!-- Content Header (Page header)
        <section class="content-header">
            <h1></h1>
        </section>
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
                        <h3 class="box-title" style="font-weight: 600">
                            <?php if(empty($VarId)) { ?>
                                CAD REQUEST
                            <?php } else { ?>
                                CAD REQUEST SENT DETAIL
                                <?php
                            } ?>
                        </h3>
                    </div>
                    <div class="box box-info">
                        <div class="box-header with-bgColor">
                            <h3 class="box-title box-titleFontSize">
                                DETAILS
                            </h3>
                        </div>
                        <div class="box-body">

                            <div id="merchantCadReqJxl"></div>
                        </div>
                        <h4 class="greyBgColor" style="margin-bottom: 0">ATTACHMENT & REFERENCE DETAILS:</h4>

                        <div class="box-body">
                            <div id="jxlAttachmentDetails"></div>
                        </div>
                        <div class="box-body box-bodyPd2010">
                            <!--Content-->
                            <form class="form-horizontal" name="frmBasicInfo" id="frmBasicInfo" method="post" autocomplete="off">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="frmBasicRequestType" class="col-sm-4 control-label">Request Type</label>
                                        <div class="col-sm-8">
                                            <?php $ArrReqType = unserialize(ARRREQUESTTYPE) ?>
                                            <select name="" id="frmBasicRequestType" class="form-control">
                                                <?php
                                                foreach ($ArrReqType as $key => $reqType) {
                                                    ?>
                                                    <option
                                                        value="<?php echo $reqType ?>" <?php echo @$ArrBasicInfo->requesttype == $reqType ? 'selected' : '' ?>><?php echo $reqType ?></option> <?php
                                                }
                                                ?>
                                            </select>
                                            <div class="herr" id="ErrfrmBasicCombo"></div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-4 control-label">Request Date & Time</label>
                                        <div class="col-sm-8">
                                            <input type="text" name="" class="form-control" id="frmBasicReqDate" readonly value="<?php echo @dateTimeHelp($ArrBasicInfo->datecreated); ?>">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-4 control-label">Cutoff Date & Time</label>
                                        <div class="col-sm-8">
                                            <div class='input-group date' id='datetimepicker1'>
                                                <input type='text' class="form-control" id="frmBasicCutoffdatetime" value="<?php if(!empty($ArrBasicInfo->cutoffdatetime)) echo date('d-m-Y H:i:s',strtotime($ArrBasicInfo->cutoffdatetime)) ?>" />
                                                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                            </div>
                                            <div class="herr" id="ErrfrmBasicCutoffdatetime"></div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-4 control-label">Merchant Note</label>
                                        <div class="col-sm-8">
                                            <textarea id="frmBasicMerchantNote" class="form-control" style="height: 65px"><?php if(!empty($ArrBasicInfo->merchantnote)) echo $ArrBasicInfo->merchantnote ?></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-4 control-label">
                                            Authorization Status
                                        </label>
                                        <div class="col-sm-8">
                                            <?php
                                            $VarAuthStatus = '';
                                            if(!empty($ArrBasicInfo->mgmtcurrentstatus)) {
                                                if($ArrBasicInfo->mgmtcurrentstatus == 1) $VarAuthStatus = 'PENDING';
                                                if($ArrBasicInfo->mgmtcurrentstatus == 2) $VarAuthStatus = 'APPROVED';
                                                if($ArrBasicInfo->mgmtcurrentstatus == 3) $VarAuthStatus = 'DECLINED';
                                            }
                                            ?>
                                            <input type="text" class="form-control" readonly value="<?php echo $VarAuthStatus; ?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-4 control-label">Authorization
                                            Type</label>
                                        <div class="col-sm-8">
                                            <input type="text" readonly class="form-control"
                                                   value="<?php if(!empty($ArrBasicInfo->approvaltype)) echo $ArrBasicInfo->approvaltype ?>">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">Authorized By</label>
                                        <div class="col-sm-8">
                                            <input type="text" name="" class="form-control" id="" readonly value="<?php if(!empty($AuthMgmtInfo)) echo $AuthMgmtInfo ?>">
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
                                            <?php
                                            $VarReqStatus = '';
                                            if(!empty($ArrBasicInfo->deptcurrentstatus)) {
                                                if($ArrBasicInfo->deptcurrentstatus == 1) $VarReqStatus = 'PENDING';
                                                if($ArrBasicInfo->deptcurrentstatus == 2) $VarReqStatus = 'ACCEPTED';
                                                if($ArrBasicInfo->deptcurrentstatus == 3) $VarReqStatus = 'REJECTED';
                                            }
                                            ?>
                                            <input type="text" class="form-control" readonly value="<?php echo $VarReqStatus ?>">
                                        </div>
                                    </div>

                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="enqdate" class="col-sm-4 control-label">Assign CAD Queue No.</label>
                                        <div class="col-sm-8">
                                            <input type="text" name="" class="form-control" id="" readonly value="<?php echo @$ArrBasicInfo->cadqueueno ?>">
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
                                            <textarea readonly class="form-control" style="height: 64px"><?php if(!empty($caddeptremarks)) echo $caddeptremarks ?></textarea>
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
                                        <label for="enqdate" class="col-sm-4 control-label">Recent Update</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" readonly id="recentupdate"
                                                   value="<?php if(isset($ArrBasicInfo->dateupdated))
                                                       echo date('d-m-Y H:i:s',strtotime($ArrBasicInfo->dateupdated)) ?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12" style="margin-top: 10px">
                                    <div class="form-group">
                                        <div class="col-md-12">
                                            <label class="control-label">ATTACHMENTS</label>
                                        </div>
                                        <?php
                                        if($VarId == '') {
                                            ?>
                                            <div class="col-sm-12">
                                                <div id="uploadCadRequest" class="pdt10"></div>
                                            </div>
                                            <?php
                                        }
                                        else { ?>
                                            <div class="col-sm-12">
                                                    <div class="uploadedFiles pdt10">
                                                        <div class="ajax-upload-dragdrop">
                                                            <ul class="list-group" style="list-style: none;">
                                                            <?php
                                                            $VarFdr = UPLOADS_SLASH."cadrequest".DIRECTORY_SEPARATOR.$VarId.DIRECTORY_SEPARATOR.$UserType.DIRECTORY_SEPARATOR;
                                                            $ArrDwnExtensions = DWN_FILE_EXTENSIONS;
                                                            if(file_exists($VarFdr)) {
                                                                if ($dh = opendir($VarFdr)) {
                                                                    while (($file = readdir($dh)) !== false) {
                                                                        if(is_file($VarFdr.$file)) {
                                                                            ?>
                                                                            <li>
                                                                                <div class="uploadedFileName">
                                                                                    <?php
                                                                                    $VarFileExt = pathinfo($file, PATHINFO_EXTENSION);
                                                                                    echo $file .' ';?>&nbsp;
                                                                                    <a href="<?php echo base_url()."dashboard/commonSimpleDownload?id=".urlencode(base64_encode($VarId))."&fileName=".urlencode($file)."&folder=cadrequest&by=".$UserType ?>">
                                                                                        <i class="fa fa-download fa-lg" aria-hidden="true"></i>
                                                                                    </a>&nbsp;&nbsp;
                                                                                    <?php
                                                                                    if(in_array($VarFileExt,$ArrDwnExtensions)) {

                                                                                    }
                                                                                    else {
                                                                                        ?>
                                                                                        <a href="<?php echo base_url()."dashboard/openFileInBrowser?id=".$VarId."&fileName=".$file."&folder=cadrequest&by=".$UserType ?>" target="_blank">
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
                                                                echo '<div class="uploadedFileName">No attachments</div>';
                                                            }
                                                            ?>
                                                        </ul>
                                                        </div>
                                                    </div>
                                            </div>
                                            <?php
                                        }
                                        ?>
                                    </div>
                                        <?php if(empty($VarId)) {
                                            ?>
                                            <button type="button" class="btn btn-info pull-right" style="margin-bottom: 10px" onclick="return fnSaveCadRequest();">
                                                Save Changes</button>
                                            <?php
                                        }
                                        else {
                                            ?>
                                            <?php
                                        }
                                        ?>
                                </div>
                            </form>
                        </div>
                        <div class="alert alert-success alert-dismissable hide" id="divSuccessBasicInfoMsg"></div>
                    </div>
                </div>
            </div><!-- /.row -->
        </section><!-- /.content -->
    </div><!-- /.content-wrapper -->
    <?php $this->load->view(CNFCOMPANY.'template/templatefooter');?>
    <div class="control-sidebar-bg"></div>
</div><!-- ./wrapper -->
<script src="<?php echo base_url();?>assets/js/moment.min.js"></script>
<script src="<?php echo base_url();?>assets/js/bootstrap-datetimepicker.js"></script>
<script src="<?php echo base_url(); ?>assets/js/jquery.uploadfile.min.js?s=<?php echo str_rand(5) ?>"></script>
<script src="<?php echo base_url(); ?>assets/js/jexcel4/jsuites.js"></script>
<script src="<?php echo base_url(); ?>assets/js/jexcel4/jexcel.js"></script>
<script>
    var GlbId          	        = "<?php echo @$VarId;?>";
    var GlbOrderId          	= "<?php echo @$orderid;?>";
    var GlbMgmtCurrentStatus    = "<?php echo @$mgmtcurrentstatus; ?>";
    //var GlbPoNo = '<?php //echo $ArrPoNumber ?>';

    var GlbCombo = '<?php echo @$combo ?>';
    var GlbComponent = '<?php echo @$component ?>';
    var GlbSizeSpecCode = '<?php echo @$spc ?>';
    var GlbReq = '<?php echo $ArrRequirement ?>';
    console.log(GlbCadRequestPurpose,'GlbCadRequestPurpose');
    var GlbCategory = '<?php echo @$ArrCategory ?>';
    var GlbPrevCadRefNo = '<?php echo @$ArrPrevCadRefNo ?>';

    var GlbOeSize = '<?php echo @$ArrReqSize ?>';
    var GlbCurrentData = '<?php echo @$jsonDataGrid ?>';
    var GlbJsonAttachmentJxl = '<?php echo @$jsonAttachmentJxl ?>';
    var jsonFourthJxl = '<?php echo @$jsonFourth ?>';
    var GlbFourthTbl = '';
    if(jsonFourthJxl != "") {
        GlbFourthTbl = JSON.parse(jsonFourthJxl);
    }

    var groupForSpc = [];
    for (let ii = 0; ii < GlbFourthTbl.length; ii++) {
        groupForSpc[GlbFourthTbl[ii][0]+"|#|"+GlbFourthTbl[ii][1]+"|#|"+GlbFourthTbl[ii][4]] = GlbFourthTbl[ii][5];
    }

    var GlbIsrIorCode = $("#frmBasicWipRefNo").text();
    if(GlbCurrentData == 0) {
        jsonCurrentData = [[]];
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
    sizeSpecCodeFilter = function (instance,cell,c,r,source) {
        var firstCol = instance.jexcel.getValueFromCoords(c - 3, r);
        var secondCol = instance.jexcel.getValueFromCoords(c - 2, r);
        var poNumber = instance.jexcel.getValueFromCoords(c - 1, r);
        var keys = firstCol+"|#|"+secondCol+"|#|"+poNumber;
        var ArrSsc = [];
        if(groupForSpc[keys]) {
            ArrSsc.push(groupForSpc[keys]);
            return ArrSsc;
        }
        else {
            return ArrSsc;
        }
    };

    preCadRefNoFilter = function (instance,cell,c,r,source) {
        let category = instance.jexcel.getValueFromCoords(c - 1, r);
        if(category == "New") {
            return ["-"];
        }
        else {
            console.log(JSON.parse(GlbPrevCadRefNo),'GlbPrevCadRefNo');
            let prevCadRefNo = JSON.parse(GlbPrevCadRefNo);
            if(prevCadRefNo.length) {
                return prevCadRefNo;
            }
            else {
                return ["-"];
            }
        }
    };
    /*console.log(GlbPoNo,'GlbPoNo');
    console.log(typeof GlbPoNo,'GlbPoNo type');
    console.log(getUnique(GlbPoNo),'getUnique GlbPoNo');
    console.log(JSON.parse(GlbPoNo),'JSON.parse(GlbPoNo)');*/
    //let poNo = Object.values(GlbPoNo);
    GlbPoNo = [];
    console.log(GlbPoNo,'poNo b4');
    MakePostRequest(base_path+"merchant/addcadrequest","rFrom=1&oId="+GlbOrderId,"json",function (data) {
        console.log(data,'ajax data');
        GlbPoNo = data.poNo;
        console.log(GlbPoNo,'poNo');
    });
    console.log(getUnique(GlbPoNo),'poNo after');
    var jxlTbl = jexcel(document.getElementById('merchantCadReqJxl'), {
        columns:[
            { type:'dropdown',title:'Combo', width:150,source: getUnique(JSON.parse(GlbCombo)) },
            { type:'dropdown',title:'Component', width:150,source: getUnique(JSON.parse(GlbComponent)) },
            { type:'dropdown',title:'P. O. No.', width:150,source: getUnique(GlbPoNo) },
            { type:'dropdown',title:'Size Spec Code', width:150,source: getUnique(JSON.parse(GlbSizeSpecCode)), filter: sizeSpecCodeFilter },
            { type:'dropdown',title:'Requirement', width:120,source: JSON.parse(GlbReq) },
            { type:'dropdown',title:'Purpose', width:120,source: GlbCadRequestPurpose },
            { type:'dropdown',title:'Category', width:120,source: JSON.parse(GlbCategory) },
            { type:'dropdown',title:'If Revised or In-line Pre. CAD Ref. No.', width:160,source: JSON.parse(GlbPrevCadRefNo), filter: preCadRefNoFilter },
            { type:'dropdown',title:'Required Size(s)', width:65, source: JSON.parse(GlbOeSize) },
            { type:'text',title:'Assigned CAD Ref. No.', width:160, readOnly: true },
            { type:'hidden',title:'Select Assign.', width:60, readOnly: true },
        ],
        onchange: function(instance, cell, x, y, value) {
            //$("#jxlAttachmentDetails").jexcel('insertRow',[value],[0]);
            let cadReq = $("#merchantCadReqJxl").jexcel('getData');
            $("#jxlAttachmentDetails").jexcel('setData', cadReq);
            console.log(cadReq,'cadReq');
            /*if(col == 0) {
                for(let ii = 0; ii < cadReq.length; ii++) {
                    $(cell).html(cadReq[ii][0]);
                    instance.jexcel.options.data[row][col] = cadReq[ii][0];
                }
            }*/
        },
        columnDrag:true,
        allowInsertColumn: false,
        minDimensions: [6,1],
        data: jsonCurrentData
    });

    var url = $(location).attr('href'); var lasturlpart = url.substr(url.lastIndexOf('/')+1);

    if(lasturlpart == 'managecadrequest') {

    }
    else if(lasturlpart == 'managemgmtcadrequest') {

    }
    function fnSaveCadRequest() {

            $('.form-control').css("border", "1px solid #cccccc");
            $('div.herr').text('');
            var ProfileFormData							= '';
            var frmBasicRequestType     					    = $("#frmBasicRequestType").val();
            var frmBasicCutoffDt     					    = $("#frmBasicCutoffdatetime").val();
            var frmBasicMerchantNote     					    = $("#frmBasicMerchantNote").val();
            if(frmBasicCutoffDt == "") {
                $('#ErrfrmBasicCutoffdatetime').html("Enter Cutoff Date Time");
                $('#frmBasicCutoffdatetime').focus();
                $('#frmBasicCutoffdatetime').css("border", "1px solid #B94A48");
                return false;
            }
            /*if(frmBasicRequestType == 0) {
                $('#ErrfrmBasicCutoffdatetime').html("Please fill the Cutoff date time");
                $('#frmBasicCutoffdatetime').focus();
                $('#frmBasicCutoffdatetime').css("border", "1px solid #B94A48");
                console.log(frmBasicCutoffDt,'frmBasicCutoffDt');
                return false;
            }*/
            let jxlData = $("#merchantCadReqJxl").jexcel('getData');
            let jxlAttachmentDetails = $("#jxlAttachmentDetails").jexcel('getData');
            console.log(jxlData,'jxlData');
            var Param = "reqtype="+frmBasicRequestType+"&cutoff="+frmBasicCutoffDt+"&mnote="+frmBasicMerchantNote+"&oid="+
                GlbOrderId+"&id="+GlbId+"&cs="+GlbMgmtCurrentStatus+"&isriorcode="+GlbIsrIorCode+
                "&jxldata="+JSON.stringify(jxlData)+"&AttachmentDetailsJxl="+JSON.stringify(jxlAttachmentDetails);
        if (confirm("To confirm click OK, else CANCEL")) {
            MakePostRequest(base_path+'merchant/updateCadRequestInfo',Param,'json',fnSaveCadReqRes);
        }
        else {
            return false;
        }
    }

    function fnSaveCadReqRes(data) {
        if(data!='') {
            if(data.errcode == '404') {
                fnCallSessionExpire();
                return false;
            } else if(data.errcode=='-1'){
                $('#ErrfrmBasicBomName').text(data.msg);
                return false;
            } else if(data.errcode==1) {
                GlbId       = data.id;
                extraObj.startUpload();
                //$("#divCurrentStatus").text('CAD Request Sent');
                $("#recentupdate").val(data.dateTime);
                $("#divSuccessBasicInfoMsg").removeClass('hide');
                $("#divSuccessBasicInfoMsg").text("Saved Successfully");
                console.log(extraObj,'extraObj');
                $("#CurrentStatus").val('AUTHORIZATION PENDING');
                $("#frmBasicReqDate").val(data.dateTime);
                //fnRedirectPageTimeOut(base_path+'merchant/manageallrequest');
            }
        }
    }

    $(document).ready(function() {
        extraObj     = $("#uploadCadRequest").uploadFile({
            dragDrop: true,
            multiple:true,
            url:base_path+'dashboard/commonBasicFileUpload',
            returnType: "json",
            fileName:"myFile",
            allowedTypes: allowedFileTypes,
            dynamicFormData:function () {
                return "id="+GlbId+"&folderName=cadrequest/";
            },
            autoSubmit:false
        });
    });

    jexcel(document.getElementById('jxlAttachmentDetails'), {
        data: GlbJsonAttachmentJxl,
        columns:[
            { title:'Combo', width:150 },
            { title:'Component', width:150 },
            { title:'P. O. No.', width:150 },
            { title:'Size Spec Code', width:150 },
            { type:'dropdown',title:'Approved & Graded Measurement Chart', width:150, source: ["Attached","Pending","N.A."] },
            { type:'dropdown',title:'Complete Artwork', width:150,source: ["Attached","Pending","N.A."] },
            { type:'dropdown',title:'How to Measure Details', width:150, source: ["Attached","Pending","N.A."] },
            { type:'dropdown',title:'Buyer’s Original Sample or Pattern', width:150, source: ["Yes","No"] },
            { type:'dropdown',title:'Buyer’s Comments', width:145, source: ["Attached","Pending","N.A."] },
        ],
        onchange: function(instance, cell, x, y, value) {

        },
        updateTable: function (instance, cell, col, row, val, label, cellName) {

        },
        columnDrag:true,
        allowInsertColumn: false,
        minDimensions: [9,1]
    });

    $(function () {
        $('#datetimepicker1').datetimepicker({
            format: 'DD-MM-YYYY HH:mm:ss'
        });
    });
</script>
<?php $this->load->view(CNFCOMPANY.'template/pagefooter');?>