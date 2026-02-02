<?php $this->load->view(CNFCOMPANY . 'template/pageheader'); ?>
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/uploadfile-order.css"/>
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
                            <?php $this->load->view('commonBasicInfoOrderEntry'); ?>
                        </div>
                    </div>
                    <div class="box-header with-border" style="padding: 0 10px">
                        <h3 class="box-title" style="font-weight: 600">SAMPLE REQUEST</h3>
                    </div>
                    <div class="box box-info">
                        <div class="box-header with-bgColor">
                            <h3 class="box-title box-titleFontSize">
                                DETAILS
                            </h3>
                        </div>
                        <div class="box-body">

                            <div id="jxlSampleReqJxl" class="table"></div>
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
                                        <label for="frmBasicRequestType" class="col-sm-4 control-label">Request Type</label>
                                        <div class="col-sm-8">
                                            <?php $ArrApprovalType = unserialize(ARRREQUESTTYPE);
                                            ?>
                                            <select name="" id="frmBasicRequestType" class="form-control">
                                                <option value="">Choose</option>
                                                <?php
                                                foreach ($ArrApprovalType as $KeyId => $value) {
                                                    ?>
                                                    <option value="<?php echo $value ?>" <?php if (!empty($ArrBasicInfo[0]->requesttype)) echo $ArrBasicInfo[0]->requesttype == $value ? 'selected' : '' ?>>
                                                        <?php echo $value ?>
                                                    </option>
                                                    <?php
                                                }
                                                ?>
                                            </select>
                                            <div class="herr" id="ErrRequestType"></div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-4 control-label">Request Date & Time</label>
                                        <div class="col-sm-8">
                                            <input type="text" name="" class="form-control" id="frmBasicReqDate" readonly>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-4 control-label">Cutoff Date & Time</label>
                                        <div class="col-sm-8">
                                            <div class='input-group date' id='datetimepicker1'>
                                                <input type='text' class="form-control" id="frmBasicCutOffDt"
                                                       value="<?php if (!empty($ArrBasicInfo[0])) echo dateTimeHelp($ArrBasicInfo[0]->cutoffdatetime, false); ?>"/>
                                                <span class="input-group-addon"><span
                                                        class="glyphicon glyphicon-calendar"></span></span>
                                            </div>
                                            <div class="herr" id="ErrBasicCutOffDt"></div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-4 control-label">Merchant Note</label>
                                        <div class="col-sm-8">
                                        <textarea id="frmBasicMerchantNote" class="form-control"
                                                  style="height: 64px"><?php if (!empty($ArrBasicInfo[0])) echo $ArrBasicInfo[0]->merchantnote ?></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-4 control-label">Authorization
                                            Status</label>
                                        <div class="col-sm-8">
                                            <?php
                                            $VarCs = '';
                                            if (!empty($ArrBasicInfo[0])) {
                                                if ($ArrBasicInfo[0]->mgmtcurrentstatus == 1) {
                                                    $VarCs = 'AUTHORIZATION PENDING';
                                                }
                                                elseif ($ArrBasicInfo[0]->mgmtcurrentstatus == 2) {
                                                    $VarCs = 'AUTHORIZED';
                                                } elseif ($ArrBasicInfo[0]->mgmtcurrentstatus == 3) {
                                                    $VarCs = 'NOT AUTHORIZED';
                                                } elseif ($ArrBasicInfo[0]->mgmtcurrentstatus == 4) {
                                                    $VarCs = 'RE REQUEST';
                                                }
                                            }
                                            ?>
                                            <span class="form-control" id="" readonly>
                                            <?php echo $VarCs; ?>
                                        </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">

                                    <div class="form-group">
                                        <label for="frmSamApprovalType" class="col-sm-4 control-label">Authorization
                                            Type</label>
                                        <div class="col-sm-8">
                                            <select name="" id="frmSamApprovalType" class="form-control" disabled>
                                                <option value="">Choose</option>
                                                <?php
                                                $selVal = '';
                                                foreach ($ArrApprovalType as $key => $approvaltype) {
                                                    if(!empty($ArrBasicInfo[0]->approvaltype))
                                                        if($ArrBasicInfo[0]->approvaltype == $approvaltype) $selVal = 'selected';

                                                    echo '<option value="" '.$approvaltype.'>'.$approvaltype.'</option>';
                                                }
                                                ?>
                                            </select>
                                            <div class="herr" id="ErrfrmCadApprovalType"></div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">Authorized By</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" readonly value="<?php echo @$VarAuthorizedBy ?>">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="enqdate" class="col-sm-4 control-label">Management Remarks</label>
                                        <div class="col-sm-8">
                                        <textarea readonly class="form-control"
                                                  style="height: 64px"><?php if (!empty($ArrBasicInfo[0])) echo $ArrBasicInfo[0]->mgmtremarks ?></textarea>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="enqdate" class="col-sm-4 control-label">Request Status</label>
                                        <div class="col-sm-8">
                                            <?php

                                            ?>
                                            <select name="" id="frmCadDeptAcceptReject" class="form-control" disabled>
                                                <option value="">Choose</option>
                                                <option value="1" <?php if (@$ArrBasicInfo[0]->deptcurrentstatus == 1) echo 'selected' ?>>
                                                    REQUEST PENDING
                                                </option>
                                                <option value="2" <?php if (@$ArrBasicInfo[0]->deptcurrentstatus == 2) echo 'selected' ?>>
                                                    ACCEPT
                                                </option>
                                                <option value="3" <?php if (@$ArrBasicInfo[0]->deptcurrentstatus == 3) echo 'selected' ?>>
                                                    REJECT
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="enqdate" class="col-sm-4 control-label">Sample Queue No.</label>
                                        <div class="col-sm-8">
                                            <input type="text" name="" class="form-control" id="" readonly
                                                   value="<?php if(!empty($ArrBasicInfo[0])) echo $ArrBasicInfo[0]->queueno ?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">

                                    <div class="form-group">
                                        <label for="enqdate" class="col-sm-4 control-label">Queue No. Assigned Date &
                                            Time</label>
                                        <div class="col-sm-8">
                                            <input type="text" name="" class="form-control" id="" readonly
                                                   value="<?php if(!empty($ArrBasicInfo[0])) echo dateTimeHelp($ArrBasicInfo[0]->queueno_assigned_date,false); ?>">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="enqdate" class="col-sm-4 control-label">Sample Dept. Remarks</label>
                                        <div class="col-sm-8">
                                        <textarea readonly class="form-control"
                                                  style="height: 64px"><?php if (!empty($ArrBasicInfo[0])) echo $ArrBasicInfo[0]->deptremarks; ?></textarea>
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
                                <label for="" class="col-sm-3">ATTACHMENTS</label>
                                <?php
                                if ($VarReqId == 0) {
                                    ?>
                                    <div class="col-sm-12">
                                        <div id="uploadSampleRequest" class="pdt10"></div>
                                    </div>
                                    <?php
                                } else { ?>
                                    <div class="form-group">
                                        <div class="col-md-12" style="padding-top: 20px">
                                            <label class="control-label">Download Attachments: </label>
                                        </div>
                                        <div class="col-sm-5"
                                             style="border:2px dotted #A5A5C7; padding: 10px; background-color: #eee">
                                            <ul style="list-style: none;">
                                                <?php
                                                $VarFdr = UPLOADS_SLASH . "samplerequest" . DIRECTORY_SEPARATOR. $VarReqId.DIRECTORY_SEPARATOR;
                                                if (file_exists($VarFdr)) {
                                                    if ($dh = opendir($VarFdr)) {
                                                        while (($file = readdir($dh)) !== FALSE) {
                                                            if(is_file($file)) {
                                                                ?>
                                                                <li>
                                                                    <div style="padding: 10px 0;">
                                                                        <?php echo $file . ' '; ?>&nbsp;
                                                                        <a href="<?php echo base_url() ."dashboard/commonSimpleDownload?id=".urlencode(base64_encode($VarReqId))."&fileName=".urlencode($file)."&folder=samplerequest&by=SamplingDept" ?>">
                                                                            <i class="fa fa-download fa-lg"
                                                                               aria-hidden="true"></i>
                                                                        </a>&nbsp;&nbsp;
                                                                        <a href="<?php echo base_url() . "uploads/samplerequest/" . $VarReqId . "/" . $file ?>"
                                                                           target="_blank">
                                                                            <i class="fa fa-file fa-lg"
                                                                               aria-hidden="true"></i>
                                                                        </a>
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
                                    <?php
                                }
                                ?>
                            </div>
                            <button type="button" class="pull-right btn btn-info" onclick="saveChanges()">Save</button>
                        </div>
                        <div class="alert alert-success alert-dismissable hide" id="divSuccessInfoMsg"></div>
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
<script src="<?php echo base_url(); ?>assets/js/jquery.uploadfile.min.js?s=<?php echo str_rand(5) ?>"></script>
<script src="<?php echo base_url(); ?>assets/js/jexcel4/jsuites.js"></script>
<script src="<?php echo base_url(); ?>assets/js/jexcel4/jexcel.js"></script>
<script>
    const GlbId = "<?php echo $VarReqId;?>";
    const GlbOrderId = "<?php echo $orderid;?>";
    GlbParam = "rFrom=1";
    InsertId = 0;
    GlbCombo = []; GlbComponent = []; GlbColor = [];
    GlbColorKeys = []; sizeSpecCodeKeys = [];
    jxlTbl = '';
    GlbOeSize = '';
    GlbPono = '<?php echo $ArrPoNumber ?>';
    GlbSizeSpecCode = '<?php echo $spc ?>';
    attachmentRef = [];
    GlbSamreqRequirement = '<?php echo $ArrRequirement ?>';
    GlbCategory = '<?php echo $ArrCategory ?>';
    PrevSamRefNo = '<?php echo $ArrPrevSampleRefNo ?>';

    GlbCurrentData = '<?php echo $jsonDataGrid ?>';

    if(PrevSamRefNo != '') {
        var GlbPrevSamRefNo = JSON.parse(PrevSamRefNo);
    }
    else {
        var GlbPrevSamRefNo = [];
    }

    console.log(GlbCurrentData, 'GlbCurrentData');

    if(GlbSizeSpecCode != "") {
        GlbSizeSpecCode = JSON.parse(GlbSizeSpecCode);
        console.log(GlbSizeSpecCode,'GlbSizeSpecCode');
    }
    MakeAsynPostRequest(base_path+"msamplerequest/addeditsamplerequest","rFrom=1&enqId="+GlbOrderId,"json",function (data) {
        console.log(data,'data');
        fourthTbl = data.jsonFourth;
        GlbOeSize = data.ArrFinalSizes;

        if(fourthTbl != "") {
            fourthTbl = JSON.parse(fourthTbl);
            for (let ii = 0; ii < fourthTbl.length; ii++) {
                GlbCombo.push(fourthTbl[ii][0]);
                GlbComponent.push(fourthTbl[ii][1]);
                GlbColor.push(fourthTbl[ii][2]);
                GlbColorKeys[fourthTbl[ii][0]+"|#|"+fourthTbl[ii][1]] = fourthTbl[ii][2];
                sizeSpecCodeKeys[fourthTbl[ii][0]+"|#|"+fourthTbl[ii][1]+"|#|"+fourthTbl[ii][2]+"|#|"+fourthTbl[ii][4]] = fourthTbl[ii][5];
            }
            GlbCombo = getUnique(GlbCombo);
            GlbComponent = getUnique(GlbComponent);
            GlbColor = getUnique(GlbColor);
            console.log(GlbCombo,'GlbCombo');
            console.log(GlbComponent,'GlbComponent');
            console.log(GlbColor,'GlbColor');
        }
        console.log(GlbCombo,'GlbCombo');
        jxlTbl = jexcel(document.getElementById('jxlSampleReqJxl'), {
            data: [[]],
            rowResize: true,
            columnDrag: true,
            columns: [
                {type: 'dropdown', title: 'Combo', width: 150, source: GlbCombo, wordWrap: true},
                {type: 'dropdown', title: 'Component', width: 150, source: GlbComponent, wordWrap: true},
                {type: 'dropdown', title: 'Color', width: 150, source: GlbColor, wordWrap: true, filter: colorDropdownFilter},
                {type: 'dropdown', title: 'P. O. No.', width: 150, source: getUnique(JSON.parse(GlbPono)), wordWrap: true},
                {type: 'dropdown', title: 'Size Spec Code', width: 150, source: GlbSizeSpecCode, wordWrap: true, filter: sizeSpecCodeFilter},
                {type: 'dropdown', title: 'Requirement', width: 100, source: JSON.parse(GlbSamreqRequirement), wordWrap: true },
                {type: 'dropdown', title: 'Purpose', width: 100, source: GlbSampleRequestPurpose, wordWrap: true},
                {type: 'dropdown', title: 'Category', width: 100, source: JSON.parse(GlbCategory), wordWrap: true},
                {type: 'dropdown', title: 'If Revised or In-line Pre. SAMPLE Ref. No.', width: 140, source: GlbPrevSamRefNo, filter: preSampleRefNoFilter, wordWrap: true },
                {type: 'dropdown', title: 'Required Size(s)', width: 65, source: GlbOeSize, wordWrap: true},
                {type: 'text', title: 'Qty. (Nos.)', width: 100, wordWrap: true}
            ],
            onchange: function(instance, cell, x, y, value) {
                let sampleReqJxl = jxlTbl.getData();
                console.log(sampleReqJxl,'sampleReqJxl');
                attachmentRef = [];
                for(let ii = 0; ii < sampleReqJxl.length; ii++) {
                    attachmentRef.push([sampleReqJxl[ii][0],sampleReqJxl[ii][1],sampleReqJxl[ii][2],sampleReqJxl[ii][3],sampleReqJxl[ii][4]]);
                }
                $("#samAttachmentReferenceJxl").jexcel('setData', attachmentRef);
            },
            allowInsertColumn: false,
            allowInsertRow: false
        });
    });

    sizeSpecCodeFilter = function (instance, cell, c, r, source) {
        var comboData = instance.jexcel.getValueFromCoords(c - 4, r);
        var componentData = instance.jexcel.getValueFromCoords(c - 3, r);
        var colorData = instance.jexcel.getValueFromCoords(c - 2, r);
        var poNoData = instance.jexcel.getValueFromCoords(c - 1, r);
        let key = comboData + "|#|" + componentData + "|#|" + colorData+"|#|"+poNoData;
        if(sizeSpecCodeKeys[key]) {
            let ssc = sizeSpecCodeKeys[key];
            return [ssc]
        }
        else {
            return ["-"];
        }
        return [];
    };

    console.log(GlbPrevSamRefNo,'GlbPrevSamRefNo');
    //var url = $(location).attr('href'); var lasturlpart = url.substr(url.lastIndexOf('/')+1);
    preSampleRefNoFilter = function (instance, cell, c, r, source) {
        var value = instance.jexcel.getValueFromCoords(c - 1, r);
        if (value == "New") {
            return ["-"];
        } else {
            console.log(GlbPrevSamRefNo,'GlbPrevSamRefNo');
            if(GlbPrevSamRefNo.length)
                return GlbPrevSamRefNo;
            else return ["-"];
        }
    };

    colorDropdownFilter = function (instance, cell, c, r, source) {
        var combo = instance.jexcel.getValueFromCoords(c - 2, r);
        var component = instance.jexcel.getValueFromCoords(c - 1, r);
        var keys = combo+"|#|"+component;
        if(GlbColorKeys[keys]) {
            let color = GlbColorKeys[keys];
            return [color];
        }
        else return ["-"];
    };


    let commonAttachmentDropdown = ["Attached","Pending","N.A."];
    let yesNoDropdown = ["Yes","No"];

    attachmentJxl = jexcel(document.getElementById('samAttachmentReferenceJxl'), {
        columns: [
            {title: 'Combo', width: 150, wordWrap: true, readOnly: true},
            {title: 'Component', width: 150, wordWrap: true, readOnly: true},
            {title: 'Color', width: 150, wordWrap: true, readOnly: true},
            {title: 'P. O. No.', width: 150, wordWrap: true, readOnly: true},
            {title: 'Size Spec Code', width: 150, wordWrap: true, readOnly: true},
            {type: 'dropdown', source: commonAttachmentDropdown, title: 'Approved & Graded<br/>Measurement Chart', width: 120, wordWrap: true},
            {type: 'dropdown', source: commonAttachmentDropdown, title: 'Complete<br/>Artwork', width: 120, wordWrap: true},
            {type: 'dropdown', source: commonAttachmentDropdown, title: 'How to Measure<br/>Details', width: 100, wordWrap: true},
            {type: 'dropdown', source: yesNoDropdown, title: 'Buyer`s Original<br/>Sample', width: 110, wordWrap: true},
            {type: 'dropdown', source: commonAttachmentDropdown, title: 'Buyer`s<br/>Comments', width: 155, wordWrap: true},
        ],
        data:[[]],
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
            {type:'dropdown', title: 'Sample Job <br/> Completion Status', width: 125, wordWrap: true, readOnly: true},
        ],
        data:[[]],
        onchange: function(instance, cell, x, y, value) {
        }
    });

    $(document).ready(function () {
        extraObj = $("#uploadSampleRequest").uploadFile({
            dragDrop: true,
            multiple: true,
            url: base_path + 'dashboard/commonBasicFileUpload',
            returnType: "json",
            fileName: "myFile",
            allowedTypes: allowedFileTypes,
            dynamicFormData: function () {
                return "id=" + InsertId + "&folderName=samplerequest";
            },
            autoSubmit: false
        });
    });

    $(function () {
        $('#datetimepicker1').datetimepicker({
            format: 'DD-MM-YYYY HH:mm:ss'
        });
    });

    function saveChanges() {
        let rT = $("#frmBasicRequestType").val();
        let cT = $("#frmBasicCutOffDt").val();
        let note = $("#frmBasicMerchantNote").val();
        let isrIorCode = $("#frmBasicWipRefNo").text();
        if (rT == "") {
            $('#ErrRequestType').text("Select Request Type");
            $('#frmBasicRequestType').focus();
            $('#frmBasicRequestType').css("border", "1px solid #B94A48");
            return false;
        }
        let sampleReqJxl = jxlTbl.getData();
        let attachmentRef = attachmentJxl.getData();
        if (confirm("To confirm click OK, else CANCEL")) {
            try {
                $('.form-control').css("border", "1px solid #cccccc");
                $('div.herr').text('');
                if (jsTrim(cT) == "") {
                    $('#ErrFrmBasicCutOffDt').text("Please fill the Cutoff date time");
                    $('#frmBasicCutOffDt').focus();
                    $('#frmBasicCutOffDt').css("border", "1px solid #B94A48");
                    return false;
                }

                let param = GlbParam+"&id=" + GlbId + "&reqtype=" + rT + "&cutoff=" + cT + "&mNote=" + note +
                    "&oid=" + GlbOrderId + "&isriorcode=" + isrIorCode + "&jxlSamReq=" + JSON.stringify(sampleReqJxl)+"&attach="+JSON.stringify(attachmentRef);

                MakeAsynPostRequest(base_path + 'msamplerequest/updateInfo', param, 'json', function (data) {
                    if (data != '') {
                        if (data.errcode == '404') {
                            fnCallSessionExpire();
                            return false;
                        } else if (data.errcode === -1) {
                            //$('#ErrfrmBasicBomName').text(data.msg);
                            return false;
                        } else if (data.errcode === 1) {
                            InsertId = data.id;
                            $("#divSuccessInfoMsg").removeClass('hide');
                            $("#divSuccessInfoMsg").text(data.msg);
                            $("#frmBasicReqDate").val(data.datecreated);
                            extraObj.startUpload();
                            console.log(extraObj,'extraObj');
                        }
                    }
                });
            } catch (e) {
                alert(e);
            }
        }
    }
</script>
<?php $this->load->view(CNFCOMPANY . 'template/pagefooter'); ?>