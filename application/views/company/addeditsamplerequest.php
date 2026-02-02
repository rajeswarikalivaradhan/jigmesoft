<link rel="stylesheet" href="<?php echo base_url();?>assets/css/uploadfile-order.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>assets/plugins/datepicker/datepicker3.css">
<style type="text/css">
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
</style>
<?php $this->load->view(CNFCOMPANY.'template/pageheader');?>
    <body class="sidebar-mini skin-black wysihtml5-supported sidebar-collapse">
<div class="wrapper">
    <?php $this->load->view(CNFCOMPANY.'template/templateheader');?>

    <aside class="main-sidebar">
        <?php $this->load->view(CNFCOMPANY.'template/templateleftmenu');?>
    </aside>

    <div class="content-wrapper">

        <section class="content-header">
            <h1>Merchant Dept. - SAMPLE REQUEST SENT LIST</h1>
            <ol class="breadcrumb">
                <li><a href="<?php echo base_url().CNFCOMPANY?>dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
                <li><a href="<?php echo base_url().CNFCOMPANY?>mbom/managebom/">MERCHANT Dept.</a></li>
                <li class="active">SAMPLE REQUEST SENT LIST</li>
            </ol>
        </section>

        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-info">
                        <div class="box-header with-border">
                            <h3 class="box-title">Merchant Dept. - ORDER INFORMATION</h3>
                            <div class="box-tools pull-right">
                                <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                            </div>
                        </div>
                        <form class="form-horizontal">
                            <div class="box-body">
                                <div class="col-md-3">
                                    <strong><?php echo $ArrCompanyInfo['companyname'] ?></strong>
                                    <p><?php echo $ArrCompanyInfo['address'] ?></p>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-4 control-label">Merchant Name</label>
                                        <div class="col-sm-8">
                                            <div class="customcontrol"><?php echo $ArrMerchantDetails['contactname'] ?></div>

                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-4 control-label">Merchant Code</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" id="merchantname" value="<?php echo $ArrMerchantDetails['code'] ?>">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-4 control-label">Contact No.</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" id="inputEmail3" value="<?php echo $ArrMerchantDetails['mobile']?>">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-4 control-label">E-mail Id</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" id="inputEmail3" value="<?php echo $ArrMerchantDetails['username'] ?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-4 control-label">Team Name</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" id="inputEmail3" value="<?php echo $ArrTeamDetails->contactname ?>">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-4 control-label">Team Code</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" id="inputEmail3" value="<?php echo $ArrTeamDetails->code ?>">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-4 control-label">Contact No.</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" id="inputEmail3" value="<?php echo $ArrTeamDetails->mobile ?>">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-4 control-label">E-mail Id</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" id="inputEmail3" value="<?php echo $ArrTeamDetails->email ?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-8 control-label"><strong>Internal Reference No.</strong></label>
                                    </div>

                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-4 control-label">Wip No</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" id="merchantname" value="<?php echo $ArrOrderEntry['isriorcode']; ?>">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-4 control-label">Date</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" value="<?php echo date('d-m-Y H:i:s',strtotime(@$ArrOrderEntry['datecreated'])) ?>">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-4 control-label">Total Qty.</label>
                                        <div class="col-sm-8">
                                            <?php $ArrPcsSet = unserialize(ARRPCSSET); ?>
                                            <input type="text" class="form-control" id="merchantname" value="<?php echo $ArrOrderEntry['exporderqty'].' '.$ArrPcsSet[$ArrOrderEntry['pcsorset']] ?>">
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </form>
                        <form class="form-horizontal">
                            <div class="box-body">
                                <h3>order details</h3>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-4 control-label">Order Ref. No.</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" id="inputEmail3" value="<?php echo "Order ". $ArrOrderEntry['id'] ?>">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-4 control-label">Style Ref. No.</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" id="inputEmail3" value="<?php echo $ArrOrderEntry['stylenamerefno'] ?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-4 control-label">Brand</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" id="inputEmail3" value="<?php echo $VarBrand ?>">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-4 control-label">Buyer</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" id="inputEmail3" value="<?php echo $VarBuyer ?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-4 control-label">Season</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" id="inputEmail3" value="<?php echo $ArrOrderDatas->season ?>">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-4 control-label">Div / Dept.</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" id="inputEmail3" value="<?php echo $ArrOrderDatas->divdept ?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-4 control-label">Class</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" id="inputEmail3" value="<?php echo $ArrOrderDatas->class ?>">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-4 control-label">Sub-Class</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" id="inputEmail3" value="<?php echo $ArrOrderDatas->sclass ?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-2 control-label">Style Description</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" id="inputEmail3" value="<?php echo $ArrOrderEntry['styledesc'] ?>">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-12">
                <div class="row">
                    <div class="box box-info">
                        <div class="box-header with-border">
                            <h3 class="box-title">ORDER PROCESSING</h3>
                            <div class="box-tools pull-right">
                                <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                            </div>
                        </div>

                        <div class="box-body">
                            <div class="">
                                <div id="DivContBasicInfo">
                                    <div class="">
                                        <div class="">
                                            <!--Content-->
                                            <form class="form-horizontal" name="frmBasicInfo" id="frmBasicInfo" method="post">
                                                <div class="box-body">
                                                    <div class="alert alert-success alert-dismissable hide" id="divSuccessBasicInfoMsg"></div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="inputEmail3" class="col-sm-4 control-label">P.O. No.</label>
                                                            <div class="col-sm-8">
                                                                <select id="frmBasicPoNo" class="form-control">
                                                                    <option value="">Choose</option>
                                                                    <?php
                                                                    foreach ($VarOrderEntryCommon['pono'] as $item) {
                                                                        ?>
                                                                        <option value="<?php echo $item->id ?>"><?php echo $item->ponoenqrefno ?></option>
                                                                        <?php
                                                                    }
                                                                    ?>
                                                                </select>
                                                                <div class="herr" id="ErrfrmBasicPoNo"></div>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="inputEmail3" class="col-sm-4 control-label">Destination Country</label>
                                                            <div class="col-sm-8">
                                                                <select id="frmBasicDestiCountry" class="form-control">
                                                                    <option value="">Choose</option>
                                                                    <?php
                                                                    foreach ($desticountry as $item) {
                                                                        ?>
                                                                        <option value="<?php echo $item['id'] ?>"><?php echo $item['countryname'] ?></option>
                                                                        <?php
                                                                    }
                                                                    ?>
                                                                </select>
                                                                <div class="herr" id="ErrfrmBasicDestiCountry"></div>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="inputEmail3" class="col-sm-4 control-label">Combo</label>
                                                            <div class="col-sm-8">
                                                                <select name="" id="frmBasicCombo" class="form-control">
                                                                    <option value="">Choose</option>
                                                                    <?php
                                                                    foreach ($VarOrderEntryCommon['combo'] as $item) {
                                                                        ?>
                                                                        <option value="<?php echo $item->id ?>"><?php echo $item->combo ?></option>
                                                                        <?php
                                                                    }
                                                                    ?>
                                                                </select>
                                                                <div class="herr" id="ErrfrmBasicCombo"></div>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="inputEmail3" class="col-sm-4 control-label">Component</label>
                                                            <div class="col-sm-8">
                                                                <select name="" id="frmBasicComponent" class="form-control">
                                                                    <option value="">Choose</option>
                                                                    <?php
                                                                    foreach ($VarOrderEntryCommon['component'] as $item) {
                                                                        ?>
                                                                        <option value="<?php echo $item->id ?>"><?php echo $item->component ?></option>
                                                                        <?php
                                                                    }
                                                                    ?>
                                                                </select>
                                                                <div class="herr" id="ErrfrmBasicComponent"></div>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="inputEmail3" class="col-sm-4 control-label">Color</label>
                                                            <div class="col-sm-8">
                                                                <select name="" id="frmBasicColor" class="form-control">
                                                                    <option value="">Choose</option>
                                                                    <?php
                                                                    foreach ($VarOrderEntryCommon['color'] as $item) {
                                                                        ?>
                                                                        <option value="<?php echo $item->id ?>"><?php echo $item->color ?></option>
                                                                        <?php
                                                                    }
                                                                    ?>
                                                                </select>
                                                                <div class="herr" id="ErrfrmBasicColor"></div>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="inputEmail3" class="col-sm-4 control-label">Size Spec Code</label>
                                                            <div class="col-sm-8">
                                                                <select name="" id="frmBasicSpc" class="form-control">
                                                                    <option value="">Choose</option>
                                                                    <?php
                                                                    foreach ($VarOrderEntryCommon['spc'] as $item) {
                                                                        ?>
                                                                        <option value="<?php echo $item->id ?>"><?php echo $item->sizespeccode ?></option>
                                                                        <?php
                                                                    }
                                                                    ?>
                                                                </select>
                                                                <div class="herr" id="ErrfrmBasicSpc"></div>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="inputEmail3" class="col-sm-4 control-label">Requirement</label>
                                                            <div class="col-sm-8">
                                                                <select name="" id="frmBasicRequirement" class="form-control">
                                                                    <option value="">Choose</option>
                                                                    <?php
                                                                    foreach ($ArrRequirement as $item) {
                                                                        ?>
                                                                        <option value="<?php echo $item['id'] ?>"><?php echo $item['requirement'] ?></option>
                                                                        <?php
                                                                    }
                                                                    ?>
                                                                </select>
                                                                <div class="herr" id="ErrfrmBasicRequirement"></div>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="inputEmail3" class="col-sm-4 control-label">Purpose</label>
                                                            <div class="col-sm-8">
                                                                <select id="frmBasicPurpose" class="form-control">
                                                                    <option value="">Choose</option>
                                                                    <?php
                                                                    foreach ($ArrPurpose as $item) {
                                                                        ?>
                                                                        <option value="<?php echo $item['id'] ?>"><?php echo $item['purpose'] ?></option>
                                                                        <?php
                                                                    }
                                                                    ?>
                                                                </select>
                                                                <div class="herr" id="ErrfrmBasicPurpose"></div>
                                                            </div>
                                                        </div>
                                                        Attachment Details
                                                        <div class="form-group">
                                                            <label for="inputEmail3" class="col-sm-4 control-label">Complete Artwork</label>
                                                            <div class="col-sm-8">
                                                                <select id="frmAttachArtwork" class="form-control">
                                                                    <option value="">Choose</option>
                                                                    <option value="1">Attached</option>
                                                                    <option value="2">Pending</option>
                                                                    <option value="3">N.A.</option>
                                                                </select>
                                                                <div class="herr" id="ErrfrmAttachArtwork"></div>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="inputEmail3" class="col-sm-4 control-label">How to Measure Details</label>
                                                            <div class="col-sm-8">
                                                                <select name="" id="frmAttachMeasureDetails" class="form-control">
                                                                    <option value="">Choose</option>
                                                                    <option value="1">Attached</option>
                                                                    <option value="2">Pending</option>
                                                                    <option value="3">N.A.</option>
                                                                </select>
                                                                <div class="herr" id="ErrfrmAttachMeasureDetails"></div>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="inputEmail3" class="col-sm-4 control-label">Approved Grade Measurement chart</label>
                                                            <div class="col-sm-8">
                                                                <select name="" id="frmAttachApprovedGrade" class="form-control">
                                                                    <option value="">Choose</option>
                                                                    <option value="1">Attached</option>
                                                                    <option value="2">Pending</option>
                                                                    <option value="3">N.A.</option>
                                                                </select>
                                                                <div class="herr" id="ErrfrmAttachApprovedGrade"></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="inputEmail3" class="col-sm-4 control-label">Category</label>
                                                            <div class="col-sm-8">
                                                                <?php $ArrCategory = unserialize(ARRCADCATEGORY); ?>
                                                                <select name="" id="frmBasicCategory" class="form-control">
                                                                    <option value="">Choose</option>
                                                                    <?php
                                                                    foreach($ArrCategory as $VarKey => $value) { ?>
                                                                        <option value="<?php echo $VarKey ?>"><?php echo $value ?></option>
                                                                    <?php } ?>
                                                                </select>
                                                                <div class="herr" id="ErrfrmBasicCategory"></div>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="inputEmail3" class="col-sm-4 control-label">If Revised or inline Pre. CAD Ref No.</label>
                                                            <div class="col-sm-8">
                                                                <textarea id="frmBasicCadRefNo" class="form-control" style="height: 84px"><?php //if(isset($ArrBasicInfo->cadrefno)) echo $ArrBasicInfo->cadrefno ?></textarea>
                                                                <div class="herr" id="ErrfrmBasicCadRefNo"></div>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="inputEmail3" class="col-sm-4 control-label">Request Type</label>
                                                            <div class="col-sm-8">
                                                                <?php $ArrRequestType = unserialize(ARRREQUESTTYPE); ?>
                                                                <select name="" id="frmBasicSpc" class="form-control">
                                                                    <option value="">Choose</option>
                                                                    <?php
                                                                    foreach($ArrRequestType as $VarKey => $value) {?>
                                                                        <option value="<?php echo $VarKey ?>"><?php echo $value ?></option>
                                                                    <?php }?>
                                                                </select>
                                                                <div class="herr" id="ErrfrmBasicSpc"></div>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="inputEmail3" class="col-sm-4 control-label">Required Size</label>
                                                            <div class="col-sm-8">
                                                                <select name="" id="frmBasicSpc" class="form-control">
                                                                    <option value="">Choose</option>
                                                                    <?php
                                                                    foreach($ArrReqSize as $VarKey => $value) {?>
                                                                        <option value="<?php echo $VarKey ?>"><?php echo $value ?></option>
                                                                    <?php }?>
                                                                </select>
                                                                <div class="herr" id="ErrfrmBasicSpc"></div>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="inputEmail3" class="col-sm-4 control-label">Required No. of samples</label>
                                                            <div class="col-sm-8">
                                                                <input type="text" id="frmBasicRequiredSize" class="form-control">
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="inputEmail3" class="col-sm-4 control-label">Request Date & Time</label>
                                                            <div class="col-sm-8">
                                                                <div class='input-group date bootdtp' id='datetimepicker1'>
                                                                    <input type='text' class="form-control" id="frmBasicReqDatetime" value="<?php if(isset($ArrBasicInfo->cutoffdatetime)) echo date('d-m-Y H:i:s',strtotime($ArrBasicInfo->cutoffdatetime)) ?>" />
                                                                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                                                </div>
                                                                <div class="herr" id="ErrfrmBasicCutoffdatetime"></div>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="inputEmail3" class="col-sm-4 control-label">CuttOff Date & Time</label>
                                                            <div class="col-sm-8">
                                                                <div class='input-group date bootdtp' id='datetimepicker2'>
                                                                    <input type='text' class="form-control" id="frmBasicCutoffdatetime" value="<?php if(isset($ArrBasicInfo->cutoffdatetime)) echo date('d-m-Y H:i:s',strtotime($ArrBasicInfo->cutoffdatetime)) ?>" />
                                                                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                                                </div>

                                                                <!--<input type="text" name="" class="form-control" id="frmBasicCutoffdatetime" value="<?php /*if(isset($ArrBasicInfo->cutoffdatetime)) echo date('d-m-Y H:i:s',strtotime($ArrBasicInfo->cutoffdatetime)) */?>">-->
                                                                <div class="herr" id="ErrfrmBasicCutoffdatetime"></div>
                                                            </div>
                                                        </div>
                                                        Materials Indent Details
                                                        <div class="form-group">
                                                            <label for="inputEmail3" class="col-sm-4 control-label">CAD Indent</label>
                                                            <div class="col-sm-8">
                                                                <select name="" id="frmBasicCadIndent" class="form-control">
                                                                    <option value="">Choose</option>
                                                                    <option value="1">Issued</option>
                                                                    <option value="2">Pending</option>
                                                                    <option value="3">N.A.</option>
                                                                </select>
                                                                <div class="herr" id="ErrfrmBasicCadIndent"></div>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="inputEmail3" class="col-sm-4 control-label">Fabric Indent</label>
                                                            <div class="col-sm-8">
                                                                <select name="" id="frmBasicFabricIndent" class="form-control">
                                                                    <option value="">Choose</option>
                                                                    <option value="1">Issued</option>
                                                                    <option value="2">Pending</option>
                                                                    <option value="3">N.A.</option>
                                                                </select>
                                                                <div class="herr" id="ErrfrmBasicFabricIndent"></div>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="inputEmail3" class="col-sm-4 control-label">BOM Indent</label>
                                                            <div class="col-sm-8">
                                                                <select name="" id="frmBasicBomIndent" class="form-control">
                                                                    <option value="">Choose</option>
                                                                    <option value="1">Issued</option>
                                                                    <option value="2">Pending</option>
                                                                    <option value="3">N.A.</option>
                                                                </select>
                                                                <div class="herr" id="ErrfrmBasicBomIndent"></div>
                                                            </div>
                                                        </div>

                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="inputEmail3" class="col-sm-4 control-label">Merchant Note</label>
                                                            <div class="col-sm-8">
                                                                <textarea id="frmBasicMerchantNote" class="form-control" style="height: 84px"><?php if(isset($ArrBasicInfo->merchantnote)) echo $ArrBasicInfo->merchantnote ?></textarea>
                                                                <div class="herr" id="ErrfrmBasicMerchantNote"></div>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="enqdate" class="col-sm-4 control-label">Current Status</label>
                                                            <strong><div class="col-sm-2 alert alert-dismissable hide" id="divNewStatus"></div></strong>
                                                            <div class="col-sm-3 alert <?php //if($mgmtcurrentstatus == '4' || $mgmtcurrentstatus == '1') echo 'alert-warning ';
                                                            //if($mgmtcurrentstatus == '3') echo 'alert-danger' ?>alert-dismissable" id="divCurrentStatus">
                                                                <?php //$ArrStatus = unserialize(ORDERENQUIRYSTATUS);
                                                                //echo $mgmtcurrentstatus == 0 ? '-' : $ArrStatus[$mgmtcurrentstatus]
                                                                ?>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="enqdate" class="col-sm-4 control-label">Recent Update</label>
                                                            <div class="col-sm-6"><span class="form-control" id="recentupdate" readonly="readonly"><?php //if(isset($ArrBasicInfo->dateupdated))
                                                                    //echo date('d-m-Y H:i:s',strtotime($ArrBasicInfo->dateupdated)) ?></span>
                                                                <span class="form-control hide" id="recentupdateCs" readonly="readonly"></span>
                                                            </div>
                                                        </div>

                                                        <div class="form-group" style="padding-top: 229px">
                                                            Ref. Sample & Other Details
                                                            <label for="inputEmail3" class="col-sm-4 control-label">Buyer’s Original Sample</label>
                                                            <div class="col-sm-8">
                                                                <select id="frmBasicBuyersOriginalAttachStatus" class="form-control">
                                                                    <option value="">Choose</option>
                                                                    <option value="1">Yes</option>
                                                                    <option value="2">No</option>
                                                                    <option value="3">N.A.</option>
                                                                </select>
                                                                <div class="herr" id="ErrfrmBasicBuyersOriginalAttachStatus"></div>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="inputEmail3" class="col-sm-4 control-label">In-line Ref. Sample</label>
                                                            <div class="col-sm-8">
                                                                <select name="" id="frmBasicInlineReferenceAttachStatus" class="form-control">
                                                                    <option value="">Choose</option>
                                                                    <option value="1">Yes</option>
                                                                    <option value="2">No</option>
                                                                    <option value="3">N.A.</option>
                                                                </select>
                                                                <div class="herr" id="ErrfrmBasicInlineReferenceAttachStatus"></div>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="inputEmail3" class="col-sm-4 control-label">Buyer’s Comments</label>
                                                            <div class="col-sm-8">
                                                                <select name="" id="frmBasicBuyersCommentsAttachStatus" class="form-control">
                                                                    <option value="">Choose</option>
                                                                    <option value="1">Attached</option>
                                                                    <option value="2">Pending</option>
                                                                    <option value="3">N.A.</option>
                                                                </select>
                                                                <div class="herr" id="ErrfrmBasicBuyersCommentsAttachStatus"></div>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>
                                            </form>
                                            <!--Content Ends-->

                                            <form class="form-horizontal" method="post">
                                            <div class="form-group">
                                                <label class="col-sm-3">Attachments</label>
                                                <div class="col-md-12">
                                                    <div id="uploadSampleRequestAttachment" class="pdt10"></div>
                                                </div>
                                            </div>
                                            </form>
                                            <div class="box-header with-border">

                                                <h3 class="box-title">CAD Indent
                                                    <span>
                                                        <a href="javascript:void(0)" id="addButton"><i class="fa fa-plus-circle" aria-hidden="true"></i></a>
                                                        <button class="btn btn-box-tool" type="button" id="removeButton"><i class="fa fa-minus"></i></button>
                                                    </span>
                                                </h3>
                                                <div class="box-tools pull-right"></div>
                                            </div>
                                            <form class="form-horizontal" name="frmBasicInfo" id="frmBasicInfo" method="post">
                                                <div class="box-body">
                                                    <div id='TextBoxesGroup'>
                                                        <div id="cadIndentAddRemove">
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label for="inputEmail3" class="col-sm-4 control-label">CAD Ref No</label>
                                                                    <div class="col-sm-8">
                                                                        <input type="text" class="form-control" id="frmBasicCadRefNo" value="<?php //echo $ArrOrderEntry['class'] ?>">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label for="inputEmail3" class="col-sm-4 control-label">CuttOff Date & Time</label>
                                                                    <div class="col-sm-8">
                                                                        <div class="input-group date bootdtp" id="datetimepicker3">
                                                                            <input type="text" class="form-control" id="frmBasicCadRefNo" value="<?php //echo $ArrOrderEntry['class'] ?>">
                                                                            <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label for="inputEmail3" class="col-sm-4 control-label">Material Issued To</label>
                                                                    <div class="col-sm-8">
                                                                        <input type="text" class="form-control" id="frmBasicCadRefNo" value="<?php //echo $ArrOrderEntry['class'] ?>">
                                                                    </div>
                                                                </div>
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
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-info">
                        <div class="box-header with-border">
                            <h3 class="box-title">FABRIC INDENT</h3>
                            <div class="box-tools pull-right">
                                <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                            </div>
                        </div>
                        <form class="form-horizontal" name="frmBasicInfo" id="frmBasicInfo" method="post">
                            <h4 class="box-title">Fabric - 1 <span><a href="javascript:void(0)" onclick="fnFabricIndentAddExtra()"><i class="fa fa-plus-circle" aria-hidden="true"></i></a>
                            <button class="btn btn-box-tool" type="button" onclick="fnFabricIndentRemoveExtra()"><i class="fa fa-minus"></i></button></span></h4>
                            <div class="box-body">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-4 control-label">Fabric Ref No</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" id="frmBasicFabIndentCadRefNo" value="<?php //echo $ArrOrderEntry['class'] ?>">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-4 control-label">Color</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" id="frmBasicFabIndentColor" value="<?php //echo $ArrOrderEntry['class'] ?>">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-4 control-label">Garment Parts</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" id="frmBasicFabIndentGarmentParts" value="<?php //echo $ArrOrderEntry['class'] ?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-4 control-label">Fabric (%) Blend</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" id="frmBasicFabIndentBlend" value="<?php //echo $ArrOrderEntry['class'] ?>">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-4 control-label">Fabric Content</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" id="frmBasicFabIndentContent" value="<?php //echo $ArrOrderEntry['class'] ?>">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-4 control-label">Fabric</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" id="frmBasicFabIndentFabric" value="<?php //echo $ArrOrderEntry['class'] ?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-4 control-label">GSM</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" id="frmBasicFabIndentGsm" value="<?php //echo $ArrOrderEntry['class'] ?>">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-4 control-label">Dyeing Type</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" id="frmBasicFabIndentDyeingType" value="<?php //echo $ArrOrderEntry['class'] ?>">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-4 control-label">Fab. Dia / Dim. (W * H)</label>
                                        <div class="col-sm-4">
                                            <input type="text" class="form-control" id="frmBasicFabIndentFabDiaDim" value="<?php //echo $ArrOrderEntry['class'] ?>">
                                        </div>
                                        <div class="col-sm-4">
                                            <select class="form-control" id="frmBasicFabIndentUnit">
                                                <option value="">Unit of measure</option>
                                                <option>Inches</option>
                                                <option>Cms.</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-4 control-label">Qty.(Kgs.)</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" id="frmBasicFabIndentQtykgs" value="<?php //echo $ArrOrderEntry['class'] ?>">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-4 control-label">Qty. (Nos.)</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" id="frmBasicFabIndentQtyNos" value="<?php //echo $ArrOrderEntry['class'] ?>">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="box-body">
                                <div id="fnFabricIndentExtraHere"></div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-4 control-label">Cutoff Date & Time</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" id="frmBasicFabIndentcutoff" value="<?php //echo $ArrOrderEntry['class'] ?>">
                                            <div class="herr" id="ErrfrmBasicFabIndentcutoff"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-4 control-label">Material issue To</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" id="frmBasicFabIndentMaterialIssueTo" value="<?php //echo $ArrOrderEntry['class'] ?>">
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-info">
                        <div class="box-header with-border">
                            <h4 class="box-title">Merchant Dept. - BOM Indent <span>
                                    <a href="javascript:void(0)" onclick="fnBomIndentAddExtra()"><i class="fa fa-plus-circle" aria-hidden="true"></i></a>
                            <button class="btn btn-box-tool" type="button" onclick="fnBomIndentRemoveExtra()"><i class="fa fa-minus"></i></button>
                                </span></span></h4>
                            <div class="box-tools pull-right">
                                <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                            </div>
                        </div>
                        <form class="form-horizontal" id="bomindent">
                                    <div class="box-body table-responsive no-padding">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th style="width: 10px;">#</th>
                                                    <th style="width: 100px;">BOM Ref. No.</th>
                                                    <th style="width: 300px;">Item Description / Content / Material</th>
                                                    <th style="width: 100px;">Item Code</th>
                                                    <th style="width: 100px;">Item Color Code</th>
                                                    <th style="width: 100px;">Size Or Dimension</th>
                                                    <th style="width: 100px;">Unit Of Measure</th>
                                                    <th style="width: 100px;">Qty.</th>
                                                    <th style="width: 100px;">Unit Of Measure</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>1.</td>
                                                    <td><input type="text" id="frmBasicBomRefNo1" class="form-control"></td>
                                                    <td><select id="frmBasicItemDescription1" class="form-control bomitemdesc"><option value=""></option></select></td>
                                                    <td><select id="frmBasicItemCode1" class="form-control"><option value=""></option></select></td>
                                                    <td><input type="text" id="frmBasicItemColorCode1" class="form-control"></td>
                                                    <td><input type="text" id="frmBasicSizeDimension1" class="form-control"></td>
                                                    <td><input type="text" id="frmBasicUnit1" class="form-control"></td>
                                                    <td><input type="text" id="frmBasicQty1" class="form-control"></td>
                                                    <td><input type="text" id="frmBasicUnitofmeasure1" class="form-control"></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        <div id="tblBomIndentExtra"></div>
                                    </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="box-footer">
                <button type="submit" class="btn btn-default">Cancel</button>
                <button type="button" onclick="" class="btn btn-info pull-right">Save Changes</button>
            </div>
        </section>
    </div>
    <?php $this->load->view(CNFCOMPANY.'template/templatefooter');?>
    <div class="control-sidebar-bg"></div>
</div>
<script type="text/javascript">

</script>
<script src="<?php echo base_url();?>assets/js/ajax.js"></script>
<script src="<?php echo base_url();?>assets/js/moment.min.js"></script>
<script src="<?php echo base_url();?>assets/js/bootstrap-datetimepicker.js"></script>
<script src="<?php echo base_url();?>assets/plugins/datepicker/bootstrap-datepicker.js"></script>
<script src="<?php echo base_url();?>assets/js/commonfunctions.js?r=<?php echo CNFJSCSSRANDNO?>"></script>
<script src="<?php echo base_url();?>assets/js/jquery.uploadfile-order.js"></script>
<script src="<?php echo base_url();?>assets/js/<?php echo CNFCOMPANY?>addeditsamplerequest.js"></script>
<?php $this->load->view(CNFCOMPANY.'template/pagefooter');?>