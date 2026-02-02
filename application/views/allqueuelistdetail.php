<link rel="stylesheet" href="<?php echo base_url();?>assets/css/jexcel/jquery.jexcel.min.css" />
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

    .customcontrol {
        border-radius: 0;
        box-shadow: none;
        display: block;
        width: 100%;
        height: 34px;
        padding: 6px 12px;
        font-size: 12px;
        line-height: 1.42857143;
        color: #555;
        background-color: #fff;
        background-image: none;
        border: 1px solid #ccc;
    }

    .wdtp75 {
        width: 75%;
    }

    .table, .secondheading {
        background-color: #ecf0f5;
    }

    .table td.secondheading {
        padding-top: 15px;
    }
</style>
<?php $this->load->view(CNFCOMPANY . 'template/pageheader'); ?>
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
                <?php
                $ArrProfileInfo = fnGetUserLoggedInfo(1);
                if (isset($ArrProfileInfo['uertype'])) {
                    $ArrUt = unserialize(ARRUSERTYPE);
                    echo $ArrUt[$ArrProfileInfo['uertype']];
                }
                ?>
                QUEUE LIST DETAILS
                <small><a href="<?php echo base_url('dashboard/cadloglist').'/'.$HashedCadRequestId ?>" class="small-box-footer">
                        <?php if ($ArrBasicInfo->requestlisttypeid == 1) echo 'CAD'; else echo 'SAMPLE' ?> Request Log List
                        <i class="fa fa-arrow-circle-right"></i></a></small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="<?php echo base_url('dashboard') ?>"><i class="fa fa-dashboard"></i> Home</a></li>
                <li><a href="<?php echo base_url('dashboard') ?>"></a></li>
                <li class="active">QUEUE LIST DETAILS</li>
            </ol>
        </section>
        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-md-12" id="divBasicInfo">
                    <div class="box box-info">
                        <div class="box-header with-border">
                            <h3 class="box-title">Basic Information</h3>
                            <div class="box-tools pull-right">
                            </div>
                        </div><!-- /.box-header -->
                        <div class="box-body">
                            <table id="tableList" class="table table-bordered table-responsive">
                                <tr>
                                    <td class="mainheading no-padding">
                                        <table id="tableList" class="table table-bordered table-responsive">
                                            <tr>
                                                <td width="25%" class="no-padding">
                                                    <div class="mainheading" style="font-size: 14px">
                                                        <div class="secondheading">COMPANY NAME <strong>
                                                                <?php echo $ArrCompanyInfo[0]['companyname'];
                                                                $ArrStatus = unserialize(ORDERENQUIRYSTATUS);
                                                                ?>
                                                            </strong>
                                                        </div>
                                                    </div>
                                                    Address <strong><?php echo $ArrCompanyInfo[0]['address'] ?></strong>
                                                </td>
                                                <td width="50%" class="no-padding">
                                                    <table id="tableList" class="table table-bordered table-responsive">
                                                        <tr>
                                                            <td class="secondheading">Merch. Name</td>
                                                            <td>
                                                                <div class="customcontrol"><?php echo @$ArrMerchant['contactname'] ?></div>
                                                            </td>
                                                            <td class="secondheading">Team Name</td>
                                                            <td>
                                                                <div class="customcontrol"><?php
                                                                    //echo '<pre>'; print_r($ArrTeam); die('');
                                                                    echo @$ArrTeamInfo->contactname ?></div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="secondheading">Merch. Code</td>
                                                            <td id="merchantCode">
                                                                <div class="customcontrol"><?php echo @$ArrMerchant['code']; ?></div>
                                                            </td>
                                                            <td class="secondheading">Team Code</td>
                                                            <td id="teamcode">
                                                                <div class="customcontrol"><?php echo @$ArrTeamInfo->code; ?></div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="secondheading">Contact No.</td>
                                                            <td id="merchantContactNo">
                                                                <div class="customcontrol"><?php echo @$ArrMerchant['mobile']; ?></div>
                                                            </td>
                                                            <td class="secondheading">Contact No.</td>
                                                            <td id="mobileNo">
                                                                <div class="customcontrol"><?php echo @$ArrTeamInfo->mobile; ?></div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="secondheading">E-Mail Id</td>
                                                            <td id="merchantEmail">
                                                                <div class="customcontrol"><?php echo @$ArrMerchant['username'] ?></div>
                                                            </td>
                                                            <td class="secondheading">E-Mail Id</td>
                                                            <td id="emailId">
                                                                <div class="customcontrol"><?php echo @$ArrTeamInfo->email ?></div>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                                <td width="25%" class="no-padding">
                                                    <table id="tableList" class="table table-bordered table-responsive">
                                                        <tr>
                                                            <td class="mainheading text-center" colspan="4"
                                                                style="height:36px;"><strong>INTERNAL REFERENCE
                                                                    NO</strong></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="secondheading">WIP No.</td>
                                                            <td>
                                                                <div class="customcontrol"
                                                                     id="frmBasicWipRefNo"><?php echo @$ArrOrderEnqData['isriorcode'] ?></div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="secondheading">Date & Time</td>
                                                            <td>
                                                                <div class="customcontrol" id="frmBasicWipDate"><?php
                                                                    echo isset($ArrOrderEnqData['datecreated']) ? date('d-m-Y H:i:s', strtotime($ArrOrderEnqData['datecreated'])) : date('d-m-Y H:i:s');
                                                                    ?>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="secondheading" style="height:36px;">Total Qty.
                                                            </td>
                                                            <td>
                                                                <?php $VarPcsOrSet = unserialize(ARRPCSSET); ?>
                                                                <div class="customcontrol"><?php echo @$ArrOrderEnqData['exporderqty'] . ' ' . @$VarPcsOrSet[$ArrOrderEnqData['pcsorset']] ?>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div class="box box-info">
                        <div class="box-header with-border">
                            <h3 class="box-title">Order Details</h3>
                        </div>
                        <div class="box-body">
                            <table class="table table-bordered table-responsive">
                                <tr>
                                    <td class="no-padding">
                                        <table id="tableList" class="table table-bordered table-responsive">
                                            <tr>
                                                <td class="no-padding wdtp75">
                                                    <table id="tableList" class="table table-bordered table-responsive">
                                                        <tr>
                                                            <td class="secondheading">Order Ref. No.</td>
                                                            <td>
                                                                <div class="customcontrol"><?php echo "Order " . $ArrOrderEnqData['id']; ?></div>
                                                            </td>
                                                            <td class="secondheading">Brand</td>
                                                            <td>
                                                                <div class="customcontrol"
                                                                     id="frmBrandName"><?php echo $ArrBB['brandname']; ?></div>
                                                            </td>
                                                            <td class="secondheading">Season</td>
                                                            <td>
                                                                <div class="customcontrol"><?php echo @$ArrOrderDatas->season ?></div>
                                                            </td>
                                                            <td class="secondheading">Class</td>
                                                            <td>
                                                                <div class="customcontrol"><?php echo @$ArrOrderDatas->class ?></div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="secondheading">Style Ref. No.</td>
                                                            <td>
                                                                <div class="customcontrol"
                                                                     id="frmBasicStyleRefNo"><?php echo $ArrOrderEnqData['stylenamerefno'] ?></div>
                                                            </td>
                                                            <td class="secondheading">Buyer</td>
                                                            <td>
                                                                <div class="customcontrol"
                                                                     id="frmBuyerName"><?php echo $ArrBB['buyername'] ?></div>
                                                            </td>
                                                            <td class="secondheading">Div./Dept.</td>
                                                            <td>
                                                                <div class="customcontrol"><?php echo @$ArrOrderDatas->divdept ?></div>
                                                            </td>
                                                            <td class="secondheading">Sub Class</td>
                                                            <td>
                                                                <div class="customcontrol"><?php echo @$ArrOrderDatas->sclass ?></div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="secondheading">Style Name</td>
                                                            <td style="padding:9px;" colspan="7">
                                                                <div class="customcontrol"
                                                                     id="frmBasicStyleDesc"><?php echo $ArrOrderEnqData['styledesc'] ?></div>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div class="box box-info">
                        <div class="box-header with-border">
                            <h3 class="box-title"><?php if ($ArrBasicInfo->requestlisttypeid == 1) echo 'CAD'; else echo 'SAMPLE' ?>
                                 QUEUE NO. LIST DETAILS</h3>
                        </div>
                        <div class="box-body">
                            <form class="form-horizontal" name="frmBasicInfo" id="frmBasicInfo" method="post" autocomplete="off">
                                <div class="alert alert-success alert-dismissable hide"
                                     id="divSuccessBasicInfoMsg"></div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-4 control-label">P.O. Ref. No.</label>
                                        <div class="col-sm-8">
                                            <?php
                                            ?>
                                            <select name="" id="frmBasicPono" class="form-control" disabled>
                                                <option value="">Choose</option>
                                                <?php
                                                foreach ($ArrPoNumber as $VarKey => $value) { ?>
                                                    <option value="<?php echo $value->ponumber ?>" <?php if (isset($ArrBasicInfo->ponoenqrefno)) echo $ArrBasicInfo->ponoenqrefno == $value->ponumber ? 'selected' : '' ?> ><?php echo $value->ponumber ?></option>
                                                <?php } ?>
                                            </select>
                                            <div class="herr" id="ErrfrmBasicPono"></div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-4 control-label">Country</label>
                                        <div class="col-sm-8">
                                            <input type="text" readonly class="form-control"
                                                   value="<?php echo @$VarCountry->countryname; ?>">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-4 control-label">Combo</label>
                                        <div class="col-sm-8">
                                            <?php
                                            //echo '<pre>'; print_r($combo); die('');
                                            ?>
                                            <select name="" id="frmBasicCombo" class="form-control" disabled>
                                                <option value="">Choose</option>
                                                <?php
                                                foreach ($combo as $VarKey => $value) { ?>
                                                    <option value="<?php echo $value->combo ?>" <?php if (isset($ArrBasicInfo->combo)) echo $ArrBasicInfo->combo == $value->combo ? 'selected' : '' ?>><?php echo $value->combo ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-4 control-label">Component</label>
                                        <div class="col-sm-8">
                                            <select name="" id="frmBasicComponent" class="form-control" disabled>
                                                <option value="">Choose</option>
                                                <?php
                                                foreach ($component as $VarKey => $value) { ?>
                                                    <option value="<?php echo $value->component ?>" <?php if (isset($ArrBasicInfo->component)) echo $ArrBasicInfo->component == $value->component ? 'selected' : '' ?>><?php echo $value->component ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-4 control-label">Color</label>
                                        <div class="col-sm-8">
                                            <select name="" id="" class="form-control" disabled>
                                                <option value="">Choose</option>
                                                <?php
                                                foreach ($color as $VarKey => $value) { ?>
                                                    <option value="<?php echo $value->color ?>" <?php if (isset($ArrBasicInfo->color)) echo $ArrBasicInfo->color == $value->color ? 'selected' : '' ?>><?php echo $value->color ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-4 control-label">Size Spec Code</label>
                                        <div class="col-sm-8">
                                            <select name="" id="" class="form-control" disabled>
                                                <option value="">Choose</option>
                                                <?php
                                                foreach ($spc as $VarKey => $value) { ?>
                                                    <option value="<?php echo $value->sizespeccode ?>" <?php if (isset($ArrBasicInfo->sizespeccode)) echo $ArrBasicInfo->sizespeccode == $value->sizespeccode ? 'selected' : '' ?>><?php echo $value->sizespeccode ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-4 control-label">Requirement</label>
                                        <div class="col-sm-8">
                                            <?php
                                            //echo '<pre>'; print_r($ArrRequirement); die('');
                                            ?>
                                            <select name="" id="frmBasicRequirement" class="form-control" disabled>
                                                <?php
                                                foreach ($ArrRequirement as $VarKey => $value) { ?>
                                                    <option value="" <?php if (isset($ArrBasicInfo->requirementid)) echo $ArrBasicInfo->requirementid == $VarKey ? 'selected' : '' ?>>
                                                        <?php echo $value ?>
                                                    </option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-4 control-label">Purpose</label>
                                        <div class="col-sm-8">
                                            <select name="" id="frmBasicPurpose" class="form-control" disabled>
                                                <option value="">Choose</option>
                                                <?php
                                                foreach ($ArrPurpose as $VarKey => $value) { ?>
                                                    <option value="<?php echo $VarKey ?>" <?php if (isset($ArrBasicInfo->purpose)) echo $ArrBasicInfo->purpose == $VarKey ? 'selected' : '' ?>><?php echo $value ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-4 control-label">Category</label>
                                        <div class="col-sm-8">
                                            <?php $ArrCategory = unserialize(ARRCADCATEGORY); ?>
                                            <select name="" id="" class="form-control" disabled>
                                                <option value="">Choose</option>
                                                <?php
                                                foreach ($ArrCategory as $VarKey => $value) { ?>
                                                    <option value="<?php echo $VarKey ?>" <?php if (isset($ArrBasicInfo->category)) echo $ArrBasicInfo->category == $VarKey ? 'selected' : '' ?>><?php echo $value ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-4 control-label">If Revised or inline
                                            Pre. CAD Ref No.</label>
                                        <div class="col-sm-8">
                                            <textarea id="frmBasicCadRefNo" readonly class="form-control"
                                                      style="height: 64px"><?php if (isset($ArrBasicInfo->cadrefno)) echo $ArrBasicInfo->cadrefno ?></textarea>
                                        </div>
                                    </div>
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
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-4 control-label">Required Size</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" id="frmBasicReqSize" readonly
                                                   value="<?php echo @$ArrBasicInfo->requiredsize ?>">
                                            <?php
                                            //echo '<pre>'; print_r($ArrReqSize); die('');
                                            ?>
                                        </div>
                                    </div>
                                    <?php
                                    if($ArrBasicInfo->requestlisttypeid == 1) {
                                        ?>
                                        <div class="form-group">
                                            <label for="inputEmail3" class="col-sm-4 control-label">Knitting Type</label>
                                            <div class="col-sm-8">
                                                <select name="" id="frmBasicKnittingType" class="form-control" disabled>
                                                    <option value="">Choose</option>
                                                    <?php unset($ArrKnittingType[0]);
                                                    foreach ($ArrKnittingType as $VarKey => $value) { ?>
                                                        <option value="<?php echo $VarKey ?>" <?php if (isset($ArrBasicInfo->knittingtype)) echo $ArrBasicInfo->knittingtype == $VarKey ? 'selected' : '' ?>><?php echo $value ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="inputEmail3" class="col-sm-4 control-label">Dyeing Type</label>
                                            <div class="col-sm-8">
                                                <select name="" id="frmBasicDyeingType" class="form-control" disabled>
                                                    <option value="">Choose</option>
                                                    <?php unset($ArrDyeingType[0]);
                                                    foreach ($ArrDyeingType as $VarKey => $value) { ?>
                                                        <option value="<?php echo $VarKey ?>" <?php if (isset($ArrBasicInfo->dyeingtype)) echo $ArrBasicInfo->dyeingtype == $VarKey ? 'selected' : '' ?>><?php echo $value ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="inputEmail3" class="col-sm-4 control-label">Compacting Type</label>
                                            <div class="col-sm-8">
                                                <select name="" id="frmBasicCompactType" class="form-control" disabled>
                                                    <option value="">Choose</option>
                                                    <?php unset($ArrCompactType[0]);
                                                    foreach ($ArrCompactType as $VarKey => $value) { ?>
                                                        <option value="<?php echo $VarKey ?>" <?php if (isset($ArrBasicInfo->compactingtype)) echo $ArrBasicInfo->compactingtype == $VarKey ? 'selected' : '' ?>><?php echo $value ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                        <?php
                                    }
                                    else {
                                        ?>
                                        <div class="form-group">
                                            <label for="inputEmail3" class="col-sm-4 control-label">Required Total No. Of Samples</label>
                                            <div class="col-sm-8">
                                                <input class="form-control" type="text" readonly id="frmBasicRequiredTotNoofSam" value="<?php echo @$ArrBasicInfo->reqtotalsam ?>">
                                            </div>
                                            <div class="herr" id="ErrfrmBasicRequiredTotNoofSam"></div>
                                        </div>
                                        <?php
                                    }
                                    ?>
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-4 control-label">Request Date &
                                            Time</label>
                                        <div class="col-sm-8">
                                            <input type="text" name="" class="form-control" id="frmBasicReqDate"
                                                   readonly value="<?php echo empty($ArrBasicInfo->requestdt) ? '-' : date('d-m-Y H:i:s',strtotime($ArrBasicInfo->requestdt)) ?>">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-4 control-label">CuttOff Date &
                                            Time</label>
                                        <div class="col-sm-8">
                                            <input type='text' class="form-control" readonly
                                                   value="<?php if (isset($ArrBasicInfo->cutoffdatetime)) echo date('d-m-Y H:i:s', strtotime($ArrBasicInfo->cutoffdatetime)) ?>"/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-4 control-label">Merchant Note</label>
                                        <div class="col-sm-8">
                                            <textarea id="" readonly class="form-control" readonly
                                                      style="height: 64px"><?php if (isset($ArrBasicInfo->merchantnote)) echo $ArrBasicInfo->merchantnote ?></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-4 control-label">Authorization
                                            Status</label>
                                        <div class="col-sm-8">
                                            <?php
                                            $ArrORDERENQUIRYSTATUS = unserialize(ORDERENQUIRYSTATUS);
                                            $VarCs                 = '';
                                            if ($mgmtcurrentstatus == 1) {
                                                $VarCs = 'AUTHORIZATION PENDING ' . $ArrORDERENQUIRYSTATUS[$mgmtcurrentstatus];
                                            } elseif ($mgmtcurrentstatus == 2) {
                                                $VarCs = 'AUTHORIZED';
                                            } elseif ($mgmtcurrentstatus == 3) {
                                                $VarCs = 'NOT AUTHORIZED ' . $ArrORDERENQUIRYSTATUS[$mgmtcurrentstatus];
                                            } elseif ($mgmtcurrentstatus == 4) {
                                                $VarCs = 'RE REQUEST ' . $ArrORDERENQUIRYSTATUS[$mgmtcurrentstatus];
                                            }
                                            ?>
                                            <span class="form-control" id=""
                                                  readonly="readonly"><?php echo $VarCs; ?></span>
                                        </div>
                                    </div>
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
                                                            value="<?php echo $key ?>" <?php echo @$ArrBasicInfo->approvaltype == $key ? 'selected' : '' ?>><?php echo $approvaltype ?></option> <?php
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
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
                                            <?php
                                            //$ArrAppRejStatus = unserialize(ORDERENQUIRYSTATUS);
                                            //unset($ArrAppRejStatus[1]);
                                            //unset($ArrAppRejStatus[4]);
                                            ?>
                                            <select name="" id="frmCadDeptAcceptReject" class="form-control" disabled>
                                                <option value="">Choose</option>
                                                <option value="2" <?php if(@$ArrBasicInfo->caddeptcurrentstatus == 2) echo 'selected' ?>>ACCEPT</option>
                                                <option value="3" <?php if(@$ArrBasicInfo->caddeptcurrentstatus == 3) echo 'selected' ?>>REJECT</option>
                                            </select>
                                            <div class="herr" id="ErrfrmCadDeptAcceptReject"></div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="enqdate" class="col-sm-4 control-label">Assign <?php if($ArrBasicInfo->requestlisttypeid == 1) echo 'CAD'; else echo 'SAMPLE' ?> Queue. No</label>
                                        <div class="col-sm-8">
                                            <input type="text" name="" class="form-control" id="" readonly
                                                   value="<?php echo @$ArrBasicInfo->cadqueueno ?>">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="enqdate" class="col-sm-4 control-label">Queue No. Assigned Date &
                                            Time</label>
                                        <div class="col-sm-8">
                                            <input type="text" name="" class="form-control" id="" readonly
                                                   value="<?php echo @$ArrBasicInfo->dateupdated ?>">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="enqdate" class="col-sm-4 control-label">Job Scheduled Date &
                                            Time</label>
                                        <div class="col-sm-8">
                                            <div class='input-group date' id='datetimepicker1'>
                                                <input type='text' class="form-control" readonly
                                                       id="frmBasicJobSchedule"
                                                       value="<?php if (!empty($ArrBasicInfo->jobschedule)) echo date('d-m-Y H:i:s', strtotime($ArrBasicInfo->jobschedule)) ?>"/>
                                                <span class="input-group-addon"><span
                                                            class="glyphicon glyphicon-calendar"></span></span>
                                            </div>
                                        </div>
                                        <div class="herr" id="ErrfrmBasicJobSchedule"></div>
                                    </div>
                                    <div class="form-group">
                                        <label for="enqdate" class="col-sm-4 control-label"><?php if($ArrBasicInfo->requestlisttypeid == 1) echo 'CAD'; else echo 'SAMPLE' ?> Ref. No</label>
                                        <div class="col-sm-8">
                                            <input type="text" name="" class="form-control" id="" readonly
                                                   value="<?php echo @$ArrBasicInfo->cadrefno ?>">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="enqdate" class="col-sm-4 control-label"><?php if($ArrBasicInfo->requestlisttypeid == 1) echo 'CAD'; else echo 'SAMPLE' ?> Dept. Remarks</label>
                                        <div class="col-sm-8">
                                            <textarea id="frmBasicCadDeptRemarks" class="form-control" readonly
                                                      style="height: 64px"><?php if (!empty($ArrBasicInfo->caddeptremarks)) echo $ArrBasicInfo->caddeptremarks ?></textarea>
                                        </div>
                                        <div class="herr" id="ErrfrmBasicCadDeptRemarks"></div>
                                    </div>
                                    <div class="form-group">
                                        <label for="" class="col-sm-4 control-label">Current Status</label>
                                        <div class="col-sm-8">
                                        <?php
                                                    if($ArrBasicInfo->deptcurrentstatus == 2) {
                                                    ?>
                                                        <select name="" id="" class="form-control" disabled>
                                                            <option value="">Choose Status</option>
                                                            <option value="1" <?php if($ArrBasicInfo->queuecompletestatus == 1) echo 'selected' ?>>Job Done</option>
                                                            <option value="2" <?php if($ArrBasicInfo->queuecompletestatus == 2) echo 'selected' ?>>Job Re-Scheduled</option>
                                                        </select>
                                                    <?php
                                                    }
                                                    else {
                                                    ?>
                                                    <select name="" id="" class="form-control" disabled>
                                                    <option value="2" <?php if($ArrBasicInfo->deptcurrentstatus == 2) echo 'selected' ?>>ACCEPT</option>
                                                    <option value="3" <?php if($ArrBasicInfo->deptcurrentstatus == 3) echo 'selected' ?>>REJECT</option>
                                                    </select>
                                                    <?php
                                                    }
                                            /*$ArrAppRejStatus = unserialize(ORDERENQUIRYSTATUS);
                                            unset($ArrAppRejStatus[1]);
                                            unset($ArrAppRejStatus[4]); */?><!--
                                            <select name="" id="frmCadDeptAcceptReject"
                                                    class="form-control" <?php /*if ($ArrBasicInfo->caddeptcurrentstatus == 2) echo 'disabled' */?>>
                                                <option value="">Choose</option>
                                                <?php
/*                                                foreach ($ArrAppRejStatus as $key => $arrCadStatus) {
                                                    */?>
                                                    <option
                                                    value="<?php /*echo $key */?>" <?php /*if ($ArrBasicInfo->caddeptcurrentstatus == $key) {
                                                        echo 'selected';
                                                    } */?>><?php /*echo $arrCadStatus */?></option> <?php
/*                                                }
                                                */?>
                                            </select>-->
                                            <div class="herr" id="ErrfrmCadDeptAcceptReject"></div>
                                        </div>
                                        <div class="herr" id="ErrfrmBasicCadDeptRemarks"></div>
                                    </div>
                                    <div class="form-group">
                                        <label for="enqdate" class="col-sm-4 control-label">Recent Update</label>
                                        <div class="col-sm-8"><span class="form-control" id="recentupdate"
                                                                    readonly="readonly"><?php if (isset($ArrBasicInfo->dateupdated)) echo date('d-m-Y H:i:s', strtotime($ArrBasicInfo->dateupdated)) ?></span>
                                            <span class="form-control hide" id="recentupdateCs"
                                                  readonly="readonly"></span>
                                        </div>
                                    </div>
                                    <div class="herr" id="ErrfrmBasicErr"></div>
                                </div>
                                <!--Content Ends-->
                            </form>
                        </div>
                        <!-- Upload Info -->
                        <div class="box-body" style="background-color: #dedede">
                            <?php
                            if ($ArrBasicInfo->requestlisttypeid == 1) {
                                ?>
                                <form class="form-horizontal" id="frmBasicAttachmentDetails">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label">Approved Graded Measurement
                                                Chart</label>
                                            <div class="col-sm-8">
                                                <select name="" id="frmAppGradMeasChartDd" class="form-control"
                                                        disabled>
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
                                                <select name="" id="frmMeasureDetailsArtwork" class="form-control"
                                                        disabled>
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
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label">Buyers Original Sample</label>
                                            <div class="col-sm-8">
                                                <select name="" id="frmBuyersOriginalSample" class="form-control"
                                                        disabled>
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
                                    <div class="col-md-4">
                                        <div class="form-group">
                                        </div>
                                    </div>
                                </form>
                                <?php
                            } else {
                                ?>
                                <form class="form-horizontal" id="frmBasicAttachmentDetails">
                                    <div class="col-md-4">
                                        <h3>Attachment Details</h3>
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label">Approved Graded Measurement
                                                Chart</label>
                                            <div class="col-sm-8">
                                                <select name="" id="frmAppGradMeasChartDd" class="form-control"
                                                        disabled>
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
                                                <select name="" id="frmMeasureDetailsArtwork" class="form-control"
                                                        disabled>
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
                                        <h3>Materials Indent Details</h3>
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label">CAD Indent</label>
                                            <div class="col-sm-8">
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
                                                        Attached
                                                    </option>
                                                    <option value="2" <?php echo (@$ArrBasicInfo->bomindentattach == '2') ? 'selected' : '' ?>>
                                                        Pending
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
                                        <h3>Ref. Sample & Other Details</h3>
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label">Buyer's Original Sample</label>
                                            <div class="col-sm-8">
                                                <select name="" id="frmBuyersOriginalSample" class="form-control"
                                                        disabled>
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
                                            <label class="col-sm-4 control-label">In-line Ref. Sample</label>
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
                                <?php
                            }
                            ?>
                        </div>
                        <!-- Upload Info ENDS-->
                        <div class="box-body">
                            <div class="form-group">
                                <label for="enqdate" class="col-sm-3">Attachments</label>
                                <div class="form-group">
                                    <div class="col-md-12">
                                        <label class="control-label">View Attached Documents</label>
                                    </div>
                                    <label class="col-sm-12 control-label"> <a
                                                href="//docs.google.com/gview?url=http://www.picssel.com/demos/downloads/Fancybox.doc&embedded=true"
                                                target="_blank" class="word">Document1.doc</a> </label>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-12" style="padding-top: 20px">
                                        <label class="control-label">Download Attachments: </label>
                                    </div>
                                    <div class="col-sm-5"
                                         style="border:2px dotted #A5A5C7; padding: 10px; background-color: #eee">
                                        <ul style="list-style: none;">
                                            <?php
                                            $VarFdr = FCPATH . "uploads/cadrequest/" . $VarId;
                                            if (file_exists($VarFdr)) {
                                                if ($dh = opendir($VarFdr)) {
                                                    while (($file = readdir($dh)) !== false) {
                                                        if ($file != "." && $file != "..") {
                                                            ?>
                                                            <li>
                                                                <div style="padding: 10px 0;">
                                                                    <?php echo $file . ' '; ?>&nbsp;<a
                                                                            href="<?php echo base_url() . "caduser/download?crid=" . $VarId . "&filename=" . $file ?>">
                                                                        <i class="fa fa-download fa-lg"
                                                                           aria-hidden="true"></i>
                                                                    </a>&nbsp;&nbsp;<a
                                                                            href="<?php echo base_url() . "uploads/cadrequest/" . $VarId . "/" . $file ?>"
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
                            </div>
                        </div>

                        <?php
                        if(@$ArrBasicInfo->requestlisttypeid == 2) {
                            ?>
                            <div id="indentgrids">
                                <div class="box box-info">
                                    <div class="box-header with-border">
                                        <h3 class="box-title">CAD INDENT DETAILS</h3>
                                    </div>
                                    <div class="box-body">
                                        <div id="gridCadIndent">

                                        </div>

                                    </div>

                                </div>

                                <div class="box no-border">
                                    <div class="box-body">
                                        <div class="row">
                                            <div class="col-md-2">
                                                <label class="">Material Issue To</label>
                                            </div>
                                            <div class="col-md-2">
                                                <label class="">Cutoff Date & Time</label>
                                            </div>
                                            <div class="col-md-2">
                                                <label class="">Current Status</label>
                                            </div>
                                            <div class="col-md-2">
                                                <label class="">Recent Updates</label>
                                            </div>
                                            <div class="col-md-2">
                                                <label class="">Material Issued by</label>
                                            </div>
                                            <div class="col-md-2">
                                                <label class="">Indent Ref. No.</label>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-2">
                                                <?php unset($ArrAllUsertypes[1]); unset($ArrAllUsertypes[2]); ?>
                                                <select class="form-control" id="MatIssuedToCadIndent">
                                                    <option>Choose</option>
                                                    <?php
                                                    /*                                            foreach ($ArrCadUsers as $caduser) {
                                                                                                    echo '<option value="'.$caduser['id'].'">'.$caduser['contactname'].'</option>';
                                                                                                }*/
                                                    foreach ($ArrAllUsertypes as $key => $user) {
                                                        echo '<option value="'.$key.'">'.$user.'</option>';
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                            <div class="col-md-2">
                                                <div class='input-group date' id='datetimepicker2'>
                                                    <input type='text' class="form-control" id="CutoffCadIndent" value="<?php ?>" />
                                                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                                </div>
                                                <div class="herr" id="ErrCutoffCadIndent"></div>
                                            </div>
                                            <div class="col-md-2">
                                                <select class="form-control">
                                                    <option></option>
                                                </select>
                                            </div>
                                            <div class="col-md-2">
                                                <input type="text" class="form-control" id="CadIndentRecentUpdate" value="<?php echo date('d-m-Y H:i:s') ?>">
                                            </div>
                                            <div class="col-md-2">
                                                <select class="form-control" disabled>
                                                    <option>Choose</option>
                                                </select>
                                            </div>
                                            <div class="col-md-2">
                                                <select class="form-control" disabled>
                                                    <option>Choose</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="box box-info">
                                    <div class="box-header with-border">
                                        <h3 class="box-title">FAB INDENT DETAILS</h3>
                                    </div>
                                    <div class="box-body">
                                        <div id="gridFabIndent">

                                        </div>
                                    </div>
                                </div>
                                <div class="box no-border">
                                    <div class="box-body">
                                        <div class="row">
                                            <div class="col-md-2">
                                                <label class="">Material Issue To</label>
                                            </div>
                                            <div class="col-md-2">
                                                <label class="">Cutoff Date & Time</label>
                                            </div>
                                            <div class="col-md-2">
                                                <label class="">Current Status</label>
                                            </div>
                                            <div class="col-md-2">
                                                <label class="">Recent Updates</label>
                                            </div>
                                            <div class="col-md-2">
                                                <label class="">Material Issued by</label>
                                            </div>
                                            <div class="col-md-2">
                                                <label class="">Indent Ref. No.</label>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-2">
                                                <select class="form-control" id="MatIssuedToFabIndent">
                                                    <option>Choose</option>
                                                    <?php
                                                    /*foreach ($ArrFabUsers as $fabuser) {
                                                        echo '<option value="'.$fabuser['id'].'">'.$fabuser['contactname'].'</option>';
                                                    }*/
                                                    foreach ($ArrAllUsertypes as $key => $user) {
                                                        echo '<option value="'.$key.'">'.$user.'</option>';
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                            <div class="col-md-2">
                                                <div class='input-group date' id='datetimepicker3'>
                                                    <input type='text' class="form-control" id="CutoffFabIndent" value="<?php ?>" />
                                                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                                </div>
                                                <div class="herr" id="ErrCutoffdatetimeforFabIndent"></div>
                                            </div>
                                            <div class="col-md-2">
                                                <select class="form-control">
                                                    <option></option>
                                                </select>
                                            </div>
                                            <div class="col-md-2">
                                                <input type="text" class="form-control" id="FabIndentRecentUpdate" value="<?php echo date('d-m-Y H:i:s') ?>">
                                            </div>
                                            <div class="col-md-2">
                                                <select class="form-control" disabled>
                                                    <option>Choose</option>
                                                </select>
                                            </div>
                                            <div class="col-md-2">
                                                <select class="form-control" disabled>
                                                    <option>Choose</option>
                                                </select>
                                            </div>

                                        </div>
                                    </div>
                                </div>

                                <div class="box box-info">
                                    <div class="box-header with-border">
                                        <h3 class="box-title">BOM INDENT DETAILS</h3>
                                    </div>
                                    <div class="box-body">
                                        <div id="gridBomIndent"></div>
                                    </div>
                                </div>
                                <div class="box no-border">
                                    <div class="box-body">
                                        <div class="row">
                                            <div class="col-md-2">
                                                <label class="">Material Issue To</label>
                                            </div>
                                            <div class="col-md-2">
                                                <label class="">Cutoff Date & Time</label>
                                            </div>
                                            <div class="col-md-2">
                                                <label class="">Current Status</label>
                                            </div>
                                            <div class="col-md-2">
                                                <label class="">Recent Updates</label>
                                            </div>
                                            <div class="col-md-2">
                                                <label class="">Material Issued by</label>
                                            </div>
                                            <div class="col-md-2">
                                                <label class="">Indent Ref. No.</label>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-2">
                                                <select class="form-control" id="MatIssuedToBomIndent">
                                                    <option>Choose</option>
                                                    <?php
                                                    foreach ($ArrAllUsertypes as $key => $user) {
                                                        echo '<option value="'.$key.'">'.$user.'</option>';
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                            <div class="col-md-2">
                                                <div class='input-group date' id='datetimepicker4'>
                                                    <input type='text' class="form-control" id="CutoffBomIndent" value="<?php ?>" />
                                                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                                </div>
                                                <div class="herr" id="ErrCutoffBomIndent"></div>
                                            </div>
                                            <div class="col-md-2">
                                                <select class="form-control">
                                                    <option></option>
                                                </select>
                                            </div>
                                            <div class="col-md-2">
                                                <input type="text" class="form-control" id="BomIndentRecentUpdate" value="<?php echo date('d-m-Y H:i:s') ?>">
                                            </div>
                                            <div class="col-md-2">
                                                <select class="form-control" disabled>
                                                    <option></option>
                                                </select>
                                            </div>
                                            <div class="col-md-2">
                                                <select class="form-control" disabled>
                                                    <option></option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="">
                                            <button class="btn btn-info" onclick="fnOpenPrintWindow()">Print to PDF</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php
                        }
                        else {

                        }
                        ?>

                        <div class="box-footer nopadding" id="divSaveOrderBtn">
                        </div>
                        <!-- /.box-footer -->
                    </div>
                </div>
            </div>
        </section><!-- /.content -->
    </div><!-- /.content-wrapper -->
    <?php $this->load->view(CNFCOMPANY . 'template/templatefooter'); ?>
    <div class="control-sidebar-bg"></div>
</div><!-- ./wrapper -->

<script src="<?php echo base_url();?>assets/js/ajax.js"></script>
<script src="<?php echo base_url();?>assets/js/moment.min.js"></script>
<script src="<?php echo base_url();?>assets/js/jexcel/jquery.jexcel.js"></script>
<script src="<?php echo base_url();?>assets/js/jexcel/numeral.min.js"></script>

<script>
    var GlbId          	= "<?php echo @$VarId ?>";
    var GlbRequestListType = "<?php echo @$ArrBasicInfo->requestlisttypeid ?>";
    if(GlbRequestListType == 2) {
        fnGetGridDatas();
        function fnGetGridDatas() {
            MakeAsynPostRequest(base_path + 'dashboard/getIndentGrids', "rfrom=1&crid=" + GlbId, 'json', fnGetRes);
        }
        function fnGetRes(data) {
            if (data.cadindentgrid != '') {
                var GlbgridCadIndent = JSON.parse(data.cadindentgrid);
                $('#gridCadIndent').jexcel({
                    colHeaders: ['CAD Ref. No.', 'Requirement Description', 'Size(s)', 'Issued Qty.', 'Unit of <br/> Measure', 'No. of Parts<br/>per Size', 'Returnable / <br/> Non-Returnable',
                        'Returnable<br/>Status'],
                    colWidths: [100, 300, 100, 150, 100, 150, 120, 100],
                    allowInsertColumn: false,
                    columns: [
                        {type: 'text'},
                        {type: 'text'},
                        {type: 'text'},
                        {type: 'text'},
                        {type: 'text'},
                        {type: 'text'},
                        {type: 'text'},
                        {type: 'text'}
                    ],
                    data: GlbgridCadIndent
                });
            }
            if (data.fabindentgrid != '') {
                var GlbgridFabIndent = JSON.parse(data.fabindentgrid);
                $('#gridFabIndent').jexcel({
                    colHeaders: ['Fab. Ref. No.', '(%) Blend / Content / Fabric', 'GSM', 'Colour', 'Garment Parts', 'Dyeing<br/>Type', 'Dia / Dim. <br/> (W*H)', 'UOM', 'Indent Qty.',
                        'UOM', 'Issued Qty.'],
                    colWidths: [100, 280, 80, 100, 150, 100, 100, 80, 90, 80, 80],
                    allowInsertColumn: false,
                    columns: [
                        {type: 'text'},
                        {type: 'text'},
                        {type: 'text'},
                        {type: 'text'},
                        {type: 'text'},
                        {type: 'text'},
                        {type: 'text'},
                        {type: 'text'},
                        {type: 'text'},
                        {type: 'text'},
                        {type: 'text'}
                    ],
                    data: GlbgridFabIndent
                });
            }
            if (data.bomindentgrid != '') {
                var GlbgridBomIndent = JSON.parse(data.bomindentgrid);
                $('#gridBomIndent').jexcel({
                    colHeaders: ['BOM Ref. No.', 'Item Description / Content / Material', 'Item Code', 'Item Colour Code', 'Size / <br/> Dimension', 'UOM', 'Indent Qty.', 'UOM',
                        'Issued Qty.'],
                    colWidths: [100, 300, 100, 150, 90, 100, 100, 100, 100],
                    allowInsertColumn: false,
                    columns: [
                        {type: 'text'},
                        {type: 'text'},
                        {type: 'text'},
                        {type: 'text'},
                        {type: 'text'},
                        {type: 'text'},
                        {type: 'text'},
                        {type: 'text'},
                        {type: 'text'}
                    ],
                    data: GlbgridBomIndent
                });
            }
        }
    }
</script>
<?php $this->load->view(CNFCOMPANY . 'template/pagefooter'); ?>