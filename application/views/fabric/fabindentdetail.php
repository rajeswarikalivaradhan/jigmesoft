<?php $this->load->view(CNFCOMPANY . 'template/pageheader');
$ArrLoggedUserInfo = fnGetUserLoggedInfo(1);
$ArrUserDetails = fnGetUserLoggedInfo(); ?>
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/jexcel4/jsuites.css"/>
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/jexcel4/jexcel.css"/>

    <style type="text/css">
        #frmPin {
            padding-left: 15px;
            letter-spacing: 42px;
            border: 0;
            background-image: linear-gradient(to left, black 70%, rgba(255, 255, 255, 0) 0%);
            background-position: bottom;
            background-size: 50px 1px;
            background-repeat: repeat-x;
            background-position-x: 35px;
            width: 220px;
            min-width: 220px;
        }

        #divInner {
            left: 0;
            position: sticky;
        }

        #divOuter {
            width: 190px;
            overflow: hidden
        }

        td div {
            font-family: Verdana, Geneva, sans-serif;
            font-size: 12px;
            line-height: 15px;
            /*padding: 5px 2px;*/
        }

        td {
            font-family: Verdana, Geneva, sans-serif;
            align: top;
        }

        table, .control-label {
            margin-bottom: 0px !important;
            font-size: 12px;
        }

        .form-control {
            height: 25px;
            padding: 3px 2px !important;
            font-size: 12px;
        }

        .mainheading {
            background-color: #bffff9;
        }

        .secondheading {
            background-color: #e3f9f7;
            height: 27px;
        }

        .table, .secondheading {
            background-color: #ecf0f5;
        }

        .table td.secondheading {
            padding-top: 15px;
        }
    </style>
    <body class="sidebar-mini skin-black wysihtml5-supported sidebar-collapse">
<div class="wrapper">
    <?php $this->load->view(CNFCOMPANY . 'template/templateheader'); ?>
    <!-- Left side column. contains the logo and sidebar -->
    <aside class="main-sidebar">
        <?php $this->load->view(CNFCOMPANY . 'template/templateleftmenu'); ?>
    </aside>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                FABRIC INDENT DETAILS
            </h1>
            <!--<ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                <li><a href="#">Examples</a></li>
                <li class="active">User profile</li>
            </ol>-->
        </section>
        <section class="content">
            <!-- Default box -->
            <?php $this->load->view('commonBasicInfoOrderEntry') ?>
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title">SAMPLE REQUEST</h3>
                </div>
                <div class="box-tools pull-right"></div>
                <div class="box-body">
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
                                    <select name="" id="frmBasicRequestType" class="form-control" disabled>
                                        <option value="">Choose</option>
                                        <option value="1" <?php if (isset($ArrBasicInfo->requesttype)) echo $ArrBasicInfo->requesttype == 1 ? 'selected' : '' ?>>
                                            Normal - 120 Hrs.
                                        </option>
                                        <option value="2" <?php if (isset($ArrBasicInfo->requesttype)) echo $ArrBasicInfo->requesttype == 2 ? 'selected' : '' ?>>
                                            Regular - 72 Hrs.
                                        </option>
                                        <option value="3" <?php if (isset($ArrBasicInfo->requesttype)) echo $ArrBasicInfo->requesttype == 3 ? 'selected' : '' ?>>
                                            Priority - 48 Hrs.
                                        </option>
                                        <option value="4" <?php if (isset($ArrBasicInfo->requesttype)) echo $ArrBasicInfo->requesttype == 4 ? 'selected' : '' ?>>
                                            H. Priority - 24 Hrs.
                                        </option>
                                        <option value="5" <?php if (isset($ArrBasicInfo->requesttype)) echo $ArrBasicInfo->requesttype == 5 ? 'selected' : '' ?>>
                                            Immed. - 2 Hrs.
                                        </option>
                                    </select>
                                    <div class="herr" id="ErrfrmBasicCombo"></div>
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
                                <label for="inputEmail3" class="col-sm-4 control-label">CuttOff Date &
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
                                <label for="inputEmail3" class="col-sm-4 control-label">Authorization
                                    Status</label>
                                <?php
                                //echo $ArrBasicInfo->mgmtcurrentstatus;
                                ?>
                                <div class="col-sm-8">
                                    <select name="" id="frmApproveReject"
                                            class="form-control" <?php if ($ArrBasicInfo->mgmtcurrentstatus == 2 || $ArrBasicInfo->mgmtcurrentstatus == 3) echo 'disabled'; ?>>
                                        <option value="">Choose</option>
                                        <option value="2" <?php echo ($ArrBasicInfo->mgmtcurrentstatus == 2) ? 'selected' : ''; ?>>
                                            AUTHORIZED
                                        </option>
                                        <option value="3" <?php echo ($ArrBasicInfo->mgmtcurrentstatus == 3) ? 'selected' : ''; ?>>
                                            NOT AUTHORIZED
                                        </option>
                                        <?php

                                        ?>
                                    </select>
                                    <div class="herr" id="ErrfrmApproveReject"></div>
                                    <?php
                                    /*$ArrORDERENQUIRYSTATUS = unserialize(ORDERENQUIRYSTATUS);
                                    $VarCs = '';
                                    if($mgmtcurrentstatus == 1) {
                                        $VarCs = 'Management '.$ArrORDERENQUIRYSTATUS[$mgmtcurrentstatus];
                                    }
                                    elseif($mgmtcurrentstatus == 2) {
                                        $VarCs = 'Management '.$ArrORDERENQUIRYSTATUS[$mgmtcurrentstatus];
                                    }
                                    elseif ($mgmtcurrentstatus == 3) {
                                        $VarCs = 'Management '.$ArrORDERENQUIRYSTATUS[$mgmtcurrentstatus];
                                    }
                                    elseif ($mgmtcurrentstatus == 4) {
                                        $VarCs = 'Management '.$ArrORDERENQUIRYSTATUS[$mgmtcurrentstatus];
                                    }*/
                                    ?>
                                    <!--<span class="form-control" id="" readonly="readonly"><?php /*echo $VarCs; */ ?></span>-->
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="inputEmail3" class="col-sm-4 control-label">Authorization
                                    Type</label>
                                <div class="col-sm-8">
                                    <?php $ArrApprovalType = unserialize(ARRREQUESTTYPE) ?>
                                    <select name="" id="" class="form-control" disabled>
                                        <option value="">Choose</option>
                                        <?php
                                        foreach ($ArrApprovalType as $key => $approvaltype) {
                                            ?>
                                            <option
                                                    value="<?php echo $key ?>" <?php echo $ArrBasicInfo->approvaltype == $key ? 'selected' : '' ?>><?php echo $approvaltype ?></option> <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">Authorized By</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" readonly value="<?php if(!empty($AuthorizedByInfo[0])) echo $AuthorizedByInfo[0]->contactname ?>">
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
                                <label for="enqdate" class="col-sm-4 control-label">Job Scheduled Date &
                                    Time</label>
                                <div class="col-sm-8">
                                    <?php
                                    ?>
                                    <input type="text" name="" class="form-control" id="" readonly
                                           value="<?php if ($ArrBasicInfo->jobschedule == NULL) echo ''; else echo date('d-m-Y H:i:s', strtotime($ArrBasicInfo->jobschedule)) ?>">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="enqdate" class="col-sm-4 control-label">Fabric Dept.
                                    Remarks</label>
                                <div class="col-sm-8">
                                                <textarea readonly class="form-control"
                                                          style="height: 64px"><?php if (!empty($caddeptremarks)) echo $caddeptremarks ?></textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">Current Status</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="" readonly value="<?php ?>">
                                    <?php //echo 'Management '.$ArrORDERENQUIRYSTATUS[$mgmtcurrentstatus]; ?>
                                    <br/>
                                    <?php //echo 'CAD Dept. '.$ArrORDERENQUIRYSTATUS[$cadcurrentstatus]; ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="enqdate" class="col-sm-4 control-label">Recent Update</label>
                                <div class="col-sm-8"><span class="form-control" id="recentupdate"
                                                            readonly="readonly"><?php if (isset($ArrBasicInfo->dateupdated))
                                            echo date('d-m-Y H:i:s', strtotime($ArrBasicInfo->dateupdated)) ?></span>
                                    <span class="form-control hide" id="recentupdateCs"
                                          readonly="readonly"></span>
                                </div>
                            </div>
                        </div>
                    </form>
                    <!--Content Ends-->
                </div>

                <div class="box-body" style="background-color: #dedede">
                    <form class="form-horizontal" id="frmBasicAttachmentDetails">
                        <div class="col-md-4">
                            <h3>Attachment Details</h3>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">Approved Graded Measurement Chart</label>
                                <div class="col-sm-8">
                                    <select name="" id="frmAppGradMeasChartDd" class="form-control" disabled>
                                        <option value="">Choose</option>
                                        <option value="1" <?php echo (@$ArrBasicInfo->AppGradMeasChart == '1') ? 'selected' : '' ?>>
                                            Attached
                                        </option>
                                        <option value="2" <?php echo (@$ArrBasicInfo->AppGradMeasChart == '2') ? 'selected' : '' ?>>
                                            Pending
                                        </option>
                                        <option value="3" <?php echo (@$ArrBasicInfo->AppGradMeasChart == '3') ? 'selected' : '' ?>>
                                            N.A.
                                        </option>
                                    </select>
                                    <div class="herr" id="ErrfrmAppGradMeasChartDd"></div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-4 control-label">Complete Artwork</label>
                                <div class="col-sm-8">
                                    <select name="" id="frmCompleteArtwork" class="form-control" disabled>
                                        <option value="">Choose</option>
                                        <option value="1" <?php echo (@$ArrBasicInfo->CompleteArtwork == '1') ? 'selected' : '' ?>>
                                            Attached
                                        </option>
                                        <option value="2" <?php echo (@$ArrBasicInfo->CompleteArtwork == '2') ? 'selected' : '' ?>>
                                            Pending
                                        </option>
                                        <option value="3" <?php echo (@$ArrBasicInfo->CompleteArtwork == '3') ? 'selected' : '' ?>>
                                            N.A.
                                        </option>
                                    </select>
                                    <div class="herr" id="ErrfrmCompleteArtwork"></div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-4 control-label">How to Measure Details Artwork</label>
                                <div class="col-sm-8">
                                    <select name="" id="frmMeasureDetailsArtwork" class="form-control" disabled>
                                        <option value="">Choose</option>
                                        <option value="1" <?php echo (@$ArrBasicInfo->MeasureDetailsArtwork == '1') ? 'selected' : '' ?>>
                                            Attached
                                        </option>
                                        <option value="2" <?php echo (@$ArrBasicInfo->MeasureDetailsArtwork == '2') ? 'selected' : '' ?>>
                                            Pending
                                        </option>
                                        <option value="3" <?php echo (@$ArrBasicInfo->MeasureDetailsArtwork == '3') ? 'selected' : '' ?>>
                                            N.A.
                                        </option>
                                    </select>
                                    <div class="herr" id="ErrfrmMeasureDetailsArtwork"></div>
                                </div>

                            </div>

                        </div>
                        <div class="col-md-4">
                            <h3>&nbsp;</h3>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">CAD Indent</label>
                                <div class="col-sm-8">
                                    <?php
                                    //echo '<pre>'; print_r($ArrBasicInfo); die('die');
                                    ?>
                                    <select name="" id="frmCadIndent" class="form-control" disabled>
                                        <option value="">Choose</option>
                                        <option value="1" <?php echo (@$ArrBasicInfo->cadindentattach == '1') ? 'selected' : '' ?>>
                                            Yes
                                        </option>
                                        <option value="2" <?php echo (@$ArrBasicInfo->cadindentattach == '2') ? 'selected' : '' ?>>
                                            No
                                        </option>
                                        <option value="3" <?php echo (@$ArrBasicInfo->cadindentattach == '3') ? 'selected' : '' ?>>
                                            N.A.
                                        </option>
                                    </select>
                                    <div class="herr" id="ErrfrmCadIndent"></div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">Fabric Indent</label>
                                <div class="col-sm-8">
                                    <select name="" id="frmFabIndent" class="form-control" disabled>
                                        <option value="">Choose</option>
                                        <option value="1" <?php echo (@$ArrBasicInfo->fabindentattach == '1') ? 'selected' : '' ?>>
                                            Yes
                                        </option>
                                        <option value="2" <?php echo (@$ArrBasicInfo->fabindentattach == '2') ? 'selected' : '' ?>>
                                            No
                                        </option>
                                        <option value="3" <?php echo (@$ArrBasicInfo->fabindentattach == '3') ? 'selected' : '' ?>>
                                            N.A.
                                        </option>
                                    </select>
                                    <div class="herr" id="ErrfrmFabIndent"></div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">BOM Indent</label>
                                <div class="col-sm-8">
                                    <select name="" id="frmBomIndent" class="form-control" disabled>
                                        <option value="">Choose</option>
                                        <option value="1" <?php echo (@$ArrBasicInfo->bomindentattach == '1') ? 'selected' : '' ?>>
                                            Yes
                                        </option>
                                        <option value="2" <?php echo (@$ArrBasicInfo->bomindentattach == '2') ? 'selected' : '' ?>>
                                            No
                                        </option>
                                        <option value="3" <?php echo (@$ArrBasicInfo->bomindentattach == '3') ? 'selected' : '' ?>>
                                            N.A.
                                        </option>
                                    </select>
                                    <div class="herr" id="ErrfrmBomIndent"></div>
                                </div>

                            </div>
                        </div>
                        <div class="col-md-4">
                            <h3>&nbsp;</h3>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">Buyer's Original Sample</label>
                                <div class="col-sm-8">
                                    <select name="" id="frmBuyersOriginalSample" class="form-control" disabled>
                                        <option value="">Choose</option>
                                        <option value="1" <?php echo (@$ArrBasicInfo->BuyersOriginalSample == '1') ? 'selected' : '' ?>>
                                            Yes
                                        </option>
                                        <option value="2" <?php echo (@$ArrBasicInfo->BuyersOriginalSample == '2') ? 'selected' : '' ?>>
                                            No
                                        </option>
                                        <option value="3" <?php echo (@$ArrBasicInfo->BuyersOriginalSample == '3') ? 'selected' : '' ?>>
                                            N.A.
                                        </option>
                                    </select>
                                    <div class="herr" id="ErrfrmBuyersOriginalSample"></div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">Revised / In-line Sample Ref. No.</label>
                                <div class="col-sm-8">
                                    <select name="" id="frmInlineRefSample" class="form-control" disabled>
                                        <option value="">Choose</option>
                                        <option value="1" <?php echo (@$ArrBasicInfo->InlineRefSample == '1') ? 'selected' : '' ?>>
                                            Yes
                                        </option>
                                        <option value="2" <?php echo (@$ArrBasicInfo->InlineRefSample == '2') ? 'selected' : '' ?>>
                                            No
                                        </option>
                                        <option value="3" <?php echo (@$ArrBasicInfo->InlineRefSample == '3') ? 'selected' : '' ?>>
                                            N.A.
                                        </option>
                                    </select>
                                    <div class="herr" id="ErrfrmInlineRefSample"></div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">Buyers Comments</label>
                                <div class="col-sm-8">
                                    <select name="" id="frmBuyersComments" class="form-control" disabled>
                                        <option value="">Choose</option>
                                        <option value="1" <?php echo (@$ArrBasicInfo->BuyersComments == '1') ? 'selected' : '' ?>>
                                            Attached
                                        </option>
                                        <option value="2" <?php echo (@$ArrBasicInfo->BuyersComments == '2') ? 'selected' : '' ?>>
                                            Pending
                                        </option>
                                        <option value="3" <?php echo (@$ArrBasicInfo->BuyersComments == '3') ? 'selected' : '' ?>>
                                            N.A.
                                        </option>
                                    </select>
                                    <div class="herr" id="ErrfrmBuyersComments"></div>
                                </div>

                            </div>
                        </div>

                    </form>
                </div>
            </div>

            <!--Indent Grids -->
            <div id="indentgrids">
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title">FABRIC - MATERIAL INDENT</h3>
                    </div>
                    <div class="box-body">
                        <div id="fabIndentHere"></div>
                        <form class="form-horizontal" id="frmBasicBomMatIndent">
                            <div class="box-body pdl0">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">Material Indent Ref. No.</label>
                                        <div class="col-sm-8">
                                            <div id="BomMatIndRefNo" style="height: 45px"
                                                 class="customcontrol-readonly">
                                                <?php
                                                echo $ArrBasicInfo->fab_mat_ind_ref_no;
                                                ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">Issue to Dept.</label>
                                        <div class="col-sm-8">
                                            <div class="customcontrol-readonly">
                                                <?php
                                                echo $ArrBasicInfo->fabissuedto;

                                                ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">Request Date & Time</label>
                                        <div class="col-sm-8">
                                            <div class="customcontrol-readonly"> <?php echo dateTimeHelp($ArrBasicInfo->datecreated,false); ?></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">Cutoff Date & Time</label>
                                        <div class="col-sm-8">
                                            <span class="customcontrol-readonly">
                                                <?php echo dateTimeHelp($ArrBasicInfo->fabindentcutoffdatetime,false) ?>
                                            </span>
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
    var GlbOrderId = '<?php echo @$VarOrderId ?>';
    var jsonSamReqGrid = '<?php echo $jsonSamReqGrid ?>';
    var GlbRequestId = '<?php echo @$VarRequestId ?>';


    //TODO there is one hidden file in this jxl
    if(jsonSamReqGrid.length) {
        jexcel(document.getElementById('jxlSampleReq'), {
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
                {type: 'text', title: 'Assigned SAMPLE Ref. No.', width: 120, readOnly: true, wordWrap: true},
                {type: 'hidden'}
            ],
            columnDrag: true,
            allowInsertColumn: false,
            allowInsertRow: false,
            data: JSON.parse(jsonSamReqGrid)
        });
    }

    MakePostRequest(base_path+GlbCompanyFdr+"mfabricuser/getFabIndents","requestid="+GlbRequestId,"json",function (data) {
        console.log(data,'data');
        var f = 1;
        $.each(data.fabIndentGrid,function (index,fabValue) {
            console.log(fabValue,'val');
            $("#fabIndentHere").append('<div id="gridFabIndent_'+f+'" class="table table-responsive"></div>');
            jexcel(document.getElementById('gridFabIndent_'+f), {
                columns: [
                    {type: 'text', title: 'Fab. Ref. No.', width: 100, readOnly: true},
                    {type: 'text', title: 'Garment Parts', width: 100, wordWrap: true, readOnly: true},
                    {type: 'text', title: 'Fabric (%) Blend', width: 100, readOnly: true},
                    {type: 'text', title: 'Fabric Content', width: 100, readOnly: true},
                    {type: 'text', title: 'Fabric', width: 100, readOnly: true},
                    {type: 'text', title: 'GSM', width: 70, readOnly: true},
                    {type: 'text', title: 'Colour', width: 100, readOnly: true},
                    {type: 'text', title: 'Dyeing Type', width: 70, readOnly: true},
                    {type: 'text', title: 'Dia / Dim. (W*H)', width: 80, readOnly: true},
                    {type: 'text', title: 'UOM', width: 80, readOnly: true},
                    {type: 'text', title: 'Material Indent Qty.', width: 100, readOnly: true},
                    {type: 'text', title: 'UOM', width: 80, readOnly: true},
                    {type: 'text', title: 'Material Issued Qty.', width: 100, readOnly: true},
                    {type: 'text', title: 'UOM', width: 80, readOnly: true},
                ],
                data: JSON.parse(fabValue),
                allowInsertColumn: false,
                allowInsertRow: false
            });
            f++;
        });
    });
</script>
<?php $this->load->view(CNFCOMPANY . 'template/pagefooter'); ?>