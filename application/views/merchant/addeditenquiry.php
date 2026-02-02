<?php $this->load->view(CNFCOMPANY.'template/pageheader');?>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.10.23/datatables.min.css"/>
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css" />
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <?php $a = base64_decode(urldecode($lastURI));?>
    <style>
    .btn.btn-info{
        font-size:12px!important;
    }
    #frmBasicStyleDesc{
        height: 80px !important;
    }
    #frmManagementRemarks{
        height: 80px !important;
    }
    .herr {
        color: red;
    }
    .mandatory{
        color: red;
    }
    .select2-container .select2-selection--single{
    padding:2px 0px;
    }
    .nrml-arw-slt{
        top: 10px;
    }
    .page-header{
        margin:10px 0 10px 0!important;
        font-size:20px!important;
    }
    .form-control-feedback{
        width:62px;
    }
    .header-title {
        margin-top: 2px;
        margin-bottom: 8px;
        margin-left: 3px;
        font-size: 20px !important;
        font-family: Arial;
        font-weight: bold;
        padding: 15px;
    }
    #enquiry_add_form {
        background: #F7F7F7;
        padding: 18px;
    }
    .form-title{
        font-weight: bold;
        font-size: 15px;
        padding: 15px;
        margin-bottom: 15px;
        background: #dedede;
    }
    .text-royal-black {
    font-weight: 600;
    font-family:"Helvetica Neue",Helvetica,Arial!important;
    /* color: #022B61 !important; */
    color: #022B61 !important;
    /* color: #000000 !important; */
}
   .custbtn{
       font-family:"Open Sans", Arial, sans-serif!important;
       font-size:12px!important;
   }
    .placeholder-color{
        color: #9999a3!important;
    }
    .text-cyan-br {
        color: #055EE1;
    }
    .btn-royal-blue {
    color: #022B61;
    background-color: #ebecec;
    border-color: #D0D1D1;
    font-size:12px!important;
    }
    .btn-royal-blue:hover {
    color: #fff!important;
    background-color: #022B61;
    border-color: #00142f;
    font-size:12px!important;
}
    .table-condensed > tbody > tr > td,
    .table-condensed > tbody > tr > th,
    .table-condensed > tfoot > tr > td,
    .table-condensed > tfoot > tr > th,
    .table-condensed > thead > tr > td,
    .table-condensed > thead > tr > th {
        padding: 5px !important;
    }
.text-sm-right{
    text-align:right!important;
}
label{
    font-weight:normal!important;
    color:#022b61!important;
    font-size:12px!important;
}
<?php if($lastURI != 'addenquiry') {?>
.form-control[readonly]{
    background:#eee!important;
    color:#00050b!important;
}
.form-control[disabled]{
    color:#00050b!important;
}
<?php } ?>
.select2-container .select2-selection--single {
    font-size:12px!important;
}
.form-horizontal .control-label {
    padding-top:7px!important;
}
.btn-green {
    color: #fff!important;
    background-color: #29916c!important;
    border-color: #29916c!important;
    font-size:15px!important;;
}
.btn-red {
    color: #fff!important;
    background-color: #eb4343!important;
    border-color: #eb4343!important;
    font-size:15px!important;
}
.swal2-title {
    font-size: 21px!important;
}
</style>
    <body class="hold-transition layout-top-nav">
    <div class="wrapper">
    <?php $this->load->view(CNFCOMPANY . 'template/templateheader'); ?>
    <!-- Left side column. contains the logo and sidebar -->
    <section class="content">
          <!-- Your Page Content Here -->
        <!--<form class="form-horizontal">  -->
        <section class="invoice form-horizontal">
        <!-- title row -->
        <div class="row"> 
            <div class="col-xs-12">
                <h2 class="page-header text-royal-black mx-4" style="border-bottom:0px"> <?php echo ($lastURI == 'addenquiry') ? 'New Enquiry Details':'Enquiry Details' ?>
                  <div class="pull-right">
                                    <div class="ml-auto pr-3">
                                    <!-- <input type="hidden" id="draftstatus" value="1"> -->
                                    <?php if($checkDraftorNot > 0) {  ?>
                                    <a href="javascript:void(null)" id="backbtn" class="btn custbtn btn-sm mx-3 btn-royal-blue btn-text-slide-x">
                                        <!--<i class="btn-text-2  move-left fa fa-arrow-left text-120 align-text-bottom ml-3"></i>-->  Back
                                    </a>
                                    <?php } else { ?>
                                    <a href="javascript:void(null)" id="backbtn" class="btn custbtn btn-sm mx-3 btn-royal-blue btn-text-slide-x">
                                        <!--<i class="btn-text-2  move-left fa fa-arrow-left text-120 align-text-bottom ml-3"></i>-->  Back
                                    </a>
                                    <?php 
                                        if($lastURI == 'addenquiry' ) { echo ''; } else {
                                    ?>
                                    <a id="editEnable" class="btn custbtn btn-royal-blue btn-sm px-3" onclick="$('#enqsvbtn').show()">
                                        Edit
                                    </a>
                                    <?php } }?>
                                    </div>
                                </div>
                <div class="col-sm-12 " style="padding: 7px 25px;border-bottom: 1px solid #022B61;"></div>
                </h2>
                <h4 class="mr-4 py-4 text-royal-blue">
                    <?php 
                       // if($lastURI == 'addenquiry') { echo 'Enquiry Details'; } else { echo 'Enquiry Details'; }
                    ?>
                </h4>
            </div><!-- /.col -->
        </div>
        <div class="row no-rad-form add-form-mar" id="enquiry_add_form">
        <?php print_r($ArrEnquiryInfo); ?>
        <div class="col-md-4">
            <div class="col-md-12">
                <div class="form-group">
                        <label for="id-form-field-focus-1" class="col-sm-5 control-label">
                            Order / Enq. Ref. No <span class="mandatory">*</span>
                        </label>
                    <div class="col-sm-7">
                        <input type="text" class="form-control" id="frmOrderEnqRefNo" value="" placeholder="Free Text">
                        <div class="herr" id="ErrfrmOrderEnqRefNo"></div>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                        <label for="id-form-field-focus-1" class="col-sm-5 control-label">
                            Order / Enq. Date <span class="mandatory">*</span>
                        </label>
                    <div class="col-sm-7">
                        <input type="text" id="frmBasicEnqDate" name="frmBasicEnquiryDate" autocomplete="off" class="form-control date" placeholder="Select" value="">
                        <span class="glyphicon glyphicon-calendar form-control-feedback"></span>
                        <div class="herr" id="ErrfrmBasicEnqDate"></div>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    <label for="id-form-field-focus-1" class="col-sm-5 control-label">
                        Style Ref. No. / Name
                    </label>
                    <div class="col-sm-7">
                        <input type="text" id="frmBasicStyleRefNo" name="frmBasicStyleRefNo" class="form-control" placeholder="Free Text" value="">
                        <div class="herr" id="ErrfrmBasicStyleRefNo"></div>
                    </div>
                </div>
            </div>
			<div class="col-md-12">
                <div class="form-group">
                        <label for="id-form-field-focus-1" class="col-sm-5 control-label">
                           Style Description  <span class="mandatory">*</span>
                        </label>
                    
                    <div class="col-sm-7">
                        <textarea id="frmBasicStyleDesc" style="" name="frmBasicStyleDesc" class="form-control" placeholder="Free Text"></textarea>
                        <div class="herr" id="ErrfrmBasicStyleDesc"></div>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    <label for="id-form-field-focus-1" class="col-sm-5 control-label">
                            Order / Enq. Type <span class="mandatory">*</span>
                        </label>
                    <div class="col-sm-7">
                        <select class="cus-sel form-control js-example-basic-single" id="frmBasicEType">
                            <option value="" selected disabled hidden>Select</option>
                            <?php
                            foreach ($ArrEnqType as $item)
                            {
                                ?>
                                <option value="<?php echo $item ?>"><?php echo $item ?></option>
                                <?php
                            }
                            ?>
                        </select>
                        <div class="herr" id="ErrfrmBasicEType"></div>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    <label for="id-form-field-focus-1" class="col-sm-5 control-label">
                            Mode of Enquiry <span class="mandatory">*</span>
                        </label>
                    <div class="col-sm-7">
                        <?php if (empty($modetype)) echo 'Add Mode of Enquiry' ?>
                        <select class="cus-sel form-control js-example-basic-single" id="frmBasicMoE" name="frmBasicME">
                            <option value="" selected disabled hidden>Select</option>
                            <?php
                            foreach ($modetype as $item)
                            {
                                ?>
                                <option value="<?php echo $item['id'] ?>"><?php echo $item['name'] ?></option>
                                <?php
                            }
                            ?>
                        </select>
                        <div class="herr" id="ErrfrmBasicMoE"></div>
                    </div>
                </div>
            </div>
			<div class="col-md-12">
             <div class="form-group">
                        <label for="id-form-field-focus-1" class="col-sm-5 control-label">
                            Brand <span class="mandatory">*</span>
                        </label>
                    <div class="col-sm-7">
                        <select class="cus-sel form-control js-example-basic-single" id="frmBasicBrand" name="frmBasicBrand">
                            <option value="" selected disabled hidden>Select</option>
                            <?php
                            foreach ($ArrBrand as $item)
                            {
                                ?>
                                <option value="<?php echo $item['id'] ?>"><?php echo $item['brandname'] ?></option>
                                <?php
                            }
                            ?>
                        </select>
                        <div class="herr" id="ErrBasicBrand"></div>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                        <label for="id-form-field-focus-1" class="col-sm-5 control-label">
                            Buyer <span class="mandatory">*</span>
                        </label>
                    <div class="col-sm-7">
                        <input id="frmBasicBuyer" name="frmBasicBuyer"  class="form-control" type="text" placeholder="Auto Update" value="" disabled="disabled" readonly>
                        <!--<select class="cus-sel form-control placeholder-color app-none" id="frmBasicBuyer" name="frmBasicBuyer" disabled="disabled" readonly>
                            <option value="" selected disabled hidden>Auto Update</option>
                            <?php
                            foreach ($ArrBuyer as $item)
                            {
                                ?>
                                <option value="<?php echo $item['id'] ?>"><?php echo $item['buyername'] ?></option>
                                <?php
                            }
                            ?>
                        </select>-->
                        <div class="herr" id="ErrBasicBuyer"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="id-form-field-focus-1" class="col-sm-5 control-label">
                            Country <span class="mandatory">*</span>
                        </label>
                        <div class="col-sm-7">
                        <!--<select class="cus-sel form-control js-example-basic-single" id="frmBasicCountry">-->
                         <input id="frmBasicCountry" name="frmBasicCountry"  class="form-control" type="text" placeholder="Auto Update" value="" disabled="disabled" readonly>
                         <!-- <select class="cus-sel form-control placeholder-color app-none" id="frmBasicCountry" disabled="disabled" readonly>
                            <!--<option value="" selected disabled hidden>Select</option>
                            <option value="" selected disabled hidden>Auto Update</option>
                            <?php
                            foreach ((array) $ArrCountries as $item)
                            {
                                ?>
                                <option value="<?php echo $item['id'] ?>"><?php echo $item['name'] ?></option>
                                <?php
                            }
                            ?>
                        </select>-->
                        <div class="herr" id="ErrfrmBasicCountry"></div>
                    </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="id-form-field-focus-1" class="col-sm-5 control-label">
                            Total Order Qty. <span class="mandatory">*</span>
                        </label>
                        <div class="col-sm-7 input-group">
                            <div class="col-sm-6">
                                <input id="frmBasicPqty" name="frmBasicPqty" onkeypress="return onlyNumbernodecimal(event);" class="form-control" type="text" placeholder="Free Text" value="">
                                <div class="herr" id="ErrfrmBasicPqty"></div>
                            </div>
                            <div class="col-sm-6">
                                <?php $ArrPcsOrSet = unserialize(ARRPCSSET); ?>
                                
                                <select class="cus-sel form-control js-example-basic-single " id="frmBasicPs" readonly>
                                    <option value="" selected disabled hidden>Select</option>
                                    <?php
                                    foreach ($ArrPcsOrSet as $key => $item)
                                    {
                                        ?>
                                        <option value="<?php echo $key ?>"><?php echo $item ?></option>
                                        <?php
                                    }
                                    ?>
                                </select>
                                <div class="herr" id="ErrfrmBasicPs"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="id-form-field-focus-1" class="col-sm-5 control-label"> No. of Components <span class="mandatory">*</span></label>
                        <div class="col-sm-7">
                        <!-- <input type="text" id="frmComponents" name="frmComponents"  onkeyup="setUnit(this.value,'frmBasicPs')" onkeypress="return onlyNumbernodecimal(event);" class="form-control" placeholder="Free Text" value="">
                         -->
                        <input type="text" id="frmComponents" name="frmComponents"   onkeypress="return onlyNumbernodecimal(event);" class="form-control" placeholder="Free Text" value="">
                       
                        <div class="herr" id="ErrfrmfrmComponents"></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="id-form-field-focus-1" class="col-sm-5 control-label">
                            No. of Combo / Colour <span class="mandatory">*</span>
                        </label>
                        <div class="col-sm-7">
                        <input type="text" id="frmcombo" name="frmcombo" onkeypress="return onlyNumbernodecimal(event);" class="form-control" placeholder="Free Text" value="">
                        <div class="herr" id="Errfrmfrmcombo"></div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="id-form-field-focus-1" class="col-sm-5 control-label">
                            Shipment / Submission Date <span class="mandatory">*</span>
                        </label>
                        <div class="col-sm-7">
                            <input type="text" class="form-control date" id="frmShipmentDate" autocomplete="off" name="frmShipmentDate" value="" placeholder="Select">
                            <span class="glyphicon glyphicon-calendar form-control-feedback"></span>
                            <div class="herr" id="ErrfrmShipmentDate"></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="id-form-field-focus-1" class="col-sm-5 control-label">
                            Price Quoted For <span class="mandatory">*</span>
                        </label>
                        <div class="col-sm-7">
                            <select class="cus-sel form-control js-example-basic-single" id="frmPriceQuotedFor">
                                <option value="" selected disabled hidden>Select</option>
                                <?php
                                $ArrPriceQuotedFor = PRICEQUOTEDFOR;
                                foreach ($ArrPriceQuotedFor as $key => $item)
                                {
                                    ?>
                                    <option value="<?php echo $key ?>">
                                        <?php echo $item ?>
                                    </option>
                                    <?php
                                }
                                ?>
                            </select> 
                            <div class="herr" id="ErrfrmPriceQuotedFor"></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="id-form-field-focus-1" class="col-sm-5 control-label">
                            Quoted Price  <span class="mandatory hide">*</span>
                        </label>
                        <div class="col-sm-7 input-group">
                            <div class="col-sm-6">
                                <input id="frmBasicQprice" name="frmBasicQprice" onkeypress="return isNumber_or_isDecimal_Key(event)"  class="form-control" type="text" placeholder="Free Text" value="" onchange="validateFloatKeyPress(this);">
                                <div id="ErrfrmBasicQprice" class="herr"></div>
                            </div>
                            <div class="col-sm-6">
                                <select class="cus-sel form-control js-example-basic-single" id="frmBasicCurrency" onchange="setCurrency(this.value)">
                                    <option value="" selected disabled hidden>Select</option>
                                    <?php
                                    foreach ($ArrCurrency as $item)
                                    {
                                        ?>
                                        <option value="<?php echo $item['id'] ?>"><?php echo $item['name'] ?></option>
                                        <?php
                                    }
                                    ?>
                                </select>
                                <div class="herr" id="ErrfrmBasicCurrency"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="id-form-field-focus-1" class="col-sm-5 control-label">Buyer's Price <span class="mandatory hide">*</span></label>
                        <div class="col-sm-7 input-group">
                        <div class="col-sm-6">
                            <input id="frmBasicBprice" name="frmBasicBprice" onkeypress="return isNumber_or_isDecimal_Key(event)" class="form-control" type="text" placeholder="Free Text" value="" onchange="validateFloatKeyPress(this);">
                            <div class="herr" id="ErrfrmBasicBprice"></div>
                        </div>
                        <div class="col-sm-6">
                        <select class="cus-sel form-control placeholder-color app-none" id="frmBuyerCurrency" disabled="disabled" readonly>
                            <?php
                            foreach ($ArrCurrency as $item)
                            {
                                ?>
                                <option value="<?php echo $item['id'] ?>"><?php echo $item['name'] ?></option>
                                <?php
                            }
                            ?>
                        </select>
                        <div class="herr" id="ErrfrmBasicCurrency"></div>
                    </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="id-form-field-focus-1" class="col-sm-5 control-label">Confirmed Price <span class="mandatory hide">*</span></label>
                        <div class="col-sm-7 input-group">
                            <div class="col-sm-6">
                                <input id="frmBasicCprice" name="frmBasicCprice" onkeypress="return isNumber_or_isDecimal_Key(event)" class="form-control" type="text" placeholder="Free Text" value="" onchange="validateFloatKeyPress(this);">
                                <div class="herr" id="ErrfrmBasicCprice"></div>
                            </div>
                            <div class="col-sm-6">
                                <select class="cus-sel form-control placeholder-color app-none" id="frmConfirmCurrency" disabled="disabled" readonly>
                                    <?php
                                    foreach ($ArrCurrency as $item)
                                    {
                                        ?>
                                        <option value="<?php echo $item['id'] ?>"><?php echo $item['name'] ?></option>
                                        <?php
                                    }
                                    ?>
                                </select>
                                <div class="herr" id="ErrfrmBasicCurrency"></div>
                            </div>
                        </div>
                    </div>
                </div>
        </div>
        <div class="col-md-4">
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="id-form-field-focus-1" class="col-sm-5 control-label">
                            Request For <span class="mandatory">*</span>
                        </label>
                        <div class="col-sm-7">
                        <?php $ArrIsrIor = unserialize(ARRISRIOR); ?>
                        <select class="cus-sel form-control js-example-basic-single requestTypeSelecting" id="frmBasicRType">
                            <option value="" disabled selected hidden>Select</option>
                            <?php
                            foreach ($ArrIsrIor as $key => $item)
                            {
                                ?>
                                <option value="<?php echo $key ?>"><?php echo $item ?></option>
                                <?php
                            }
                            ?>
                        </select>
                        <div class="herr" id="ErrfrmBasicRType"></div>
                    </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="id-form-field-focus-1" class="col-sm-5 control-label">
                            Request Date & Time
                        </label>
                        <div class="col-sm-7">
                        <input type="text" class="form-control" id="frmBasicReqDT" readonly placeholder="Auto Update" value="">
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="id-form-field-focus-1" class="col-sm-5 control-label">
                            ISR Ref. If Any
                        </label>
                        <div class="col-sm-7">
                        <input type="text" class="form-control" id="frmBasicISRany" placeholder="Free Text">
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="id-form-field-focus-1" class="col-sm-5 control-label"> Merchant Name </label>
                        <div class="col-sm-7">
                        <input type="text" class="form-control" id="frmmercentname" readonly placeholder="Auto Update" value="">
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="id-form-field-focus-1" class="col-sm-5 control-label">
                            Authorization Status
                        </label>
                        <div class="col-sm-7">
                            <select class="cus-sel form-control placeholder-color app-none"  id="frmAuthoriaztionStatus" readonly>
                                <!--<option value="" selected=""></option>-->
                                <option value="" selected disabled hidden>Auto Update</option>
                            </select>  
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="id-form-field-focus-1" class="col-sm-5 control-label">
                            Authorized By
                        </label>
                        <div class="col-sm-7">
                            <input type="text" id="frmAuthorizedBy" name="frmAuthorizedBy" class="form-control" placeholder="Auto Update" value="" readonly>
                            <div class="herr" id="ErrfrmAuthorizedBy"></div> 
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="id-form-field-focus-1" class="col-sm-5 control-label">
                            Authorized Date & Time
                        </label>
                        <div class="col-sm-7">
                            <input type="text" id="frmAuthorizedDate" name="frmAuthorizedDate" class="form-control" placeholder="Auto Update" value="" readonly>
                            <div class="herr" id="ErrfrmAuthorizedDate"></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="id-form-field-focus-1" class="col-sm-5 control-label">
                            Management Remarks
                        </label>
                        <div class="col-sm-7">
                            <textarea id="frmManagementRemarks" class="form-control" readonly placeholder="Auto Update"></textarea>
                            <div class="herr" id="ErrfrmManagementRemarks"></div>
                        </div>
                    </div>
                </div>
        </div>
        </div>
        <div class="col-sm-12"><div class="form-group row"></div></div>
<div class="control-sidebar-bg"></div>
<!--</form>-->
<div class="row">
        <div class="col-xs-12 py-4" style="padding-right:30px">
        <button class="btn btn-info pull-right mx-2" id="enqsvbtn" onclick="fnSaveEnquiry();">Save</button>
        <div id="savedraft"><button class="btn btn-royal-blue pull-right mx-2" id="savedraftbtn" onclick="fnSaveEnquiryDraft(this.id);">Save as Draft</button></div>
         <?php if($checkDraftorNot > 0) { ?>
        <div id="cleardraft"><button class="btn btn-royal-blue pull-right mx-2" onclick="fncleardraft('<?php echo base64_decode(urldecode($lastURI)) ?>')">Clear Draft</button></div>
         <?php } ?>
        </div>
    </div>
        </section>
        
        </section>
    <?php $this->load->view(CNFCOMPANY.'template/templatefooter');?>
    <div class="control-sidebar-bg"></div>
</div><!-- ./wrapper -->

<script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.10.23/datatables.min.js"></script>
<script src="<?php echo base_url();?>assets/js/datatables.min.js"></script>
<script src="<?php echo base_url();?>assets/js/loadingoverlay.min.js"></script>
<!--<script src="<?php echo base_url();?>assets/custom/enquiry-list.js"></script>-->

<?php $this->load->view(CNFCOMPANY.'template/pagefooter');?>
<!--<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>-->
<!--<script type="text/javascript" src="<?php echo base_url(); ?>assets/custom/datepair.js"></script>-->
<!--<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>-->

<script src="<?php echo base_url(); ?>assets/plugins/jQuery/jquery-2.2.3.min.js?rn=<?php echo CNFJSCSSRANDNO ?>"></script>
<script src="<?php echo base_url(); ?>assets/js/jquery.uploadfile.min.js?s=<?php echo str_rand(5) ?>"></script>
<script src="<?php echo base_url(); ?>assets/js/commonfunctions.js?rn=<?php echo CNFJSCSSRANDNO ?>"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/custom/datepair.js"></script>
<script src="<?= base_url() ?>assets/ace/node_modules/sweetalert2/dist/sweetalert2.all.js"></script>
 <script src="<?php echo base_url(); ?>assets/js/merchant/addeditenquiry.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    
	$(document).ready(function() {
	    
	   //  $("#component_block").hide();
    //      $("#sizeblock").hide();
         
        function matchStart(params, data) {
            params.term = params.term || '';
            if (data.text.toUpperCase().indexOf(params.term.toUpperCase()) == 0) {
                return data;
            }
            return false;
        }

        $('.date').datepicker({
            'format': 'dd-mm-yyyy',
            'autoclose': true,
            'orientation': "bottom",
            'todayHighlight':true,
        });
       // commented by me  .datepicker("setDate",'today');

        // $('.js-example-basic-single').select2({
        //     placeholder: "Select",
        //     matcher: function(params, data) {
        //         return matchStart(params, data);
        //     },
        // });

        // $('b[role="presentation"]').hide();
        // $('.select2-selection__arrow').append('<span class="arrow-select2-ji"><span>');
        
    });
</script>
<script>
    // $('#enquirySearch .date').datepicker({
    //     // 'format': 'yyyy-mm-dd',
    //     'format': 'dd-mm-yyyy',
    //     'autoclose': true,
    // }).on('change', function(){
    //     $("#datecreatedto").focus();
    // });

    $('.js-example-basic-single').select2({ placeholder: "Select" });
    $('.js-example-basic-single-no-search').select2({ 
        placeholder: "Select", 
        minimumResultsForSearch: -1, 
        containerCssClass: "custom-container" 
    });

    $('b[role="presentation"]').hide();
    $('.select2-selection__arrow').append('<span class="arrow-select2-ji nrml-arw-slt"><span>');
</script>
<script>
    var GlbEnquiryId = '<?php echo @$a ?>';
    firstTableData = <?php echo $jsonFirstTbl ?>;
    var GlbId = '';
    var ps = ["<?php echo "" ?>"];
    var GarmentParts = <?php echo json_encode($GarmentParts) ?>;
    var draftstatus=<?php echo ($checkDraftorNot>0)?1:2 ?>;
    var lasturi='<?php echo $lastURI?>';
    
    if(lasturi!='addenquiry' && draftstatus==2){
        $('#enqsvbtn').hide();
        $('#savedraftbtn').hide();

        
    } else { 
          $('#savedraftbtn').show();
          $('#enqsvbtn').show();
    }
    function deleteFile(id, enquiry_id, filename)
    {
        if (confirm('Are you sure you want to delete the file')) {
            MakeAsynPostRequest(base_path + "merchant/enqDeleteFile", "&enquiry_id=" + enquiry_id + "&filename=" + filename, "json", function(data) {
            });
            document.getElementById("temp_file_" + id).remove();
        }
    }

    function setCurrency(thisvalue) {
        document.getElementById("frmBuyerCurrency").value = thisvalue;
        document.getElementById("frmConfirmCurrency").value = thisvalue;
    }
    function setUnit(thisvalue,toattribute) { // onkeyup of no of component data set to unit dropdowns
        if(thisvalue!=''){
            if(thisvalue==1){
                $("#"+toattribute).val('1');
            }else{
                 $("#"+toattribute).val('2');
            }
        }else{
            $("#"+toattribute).val('');
        }
    }
    function fnShowSubChartInfoChange(VarMasterChartId) {
        $('#frmOrderSizeChartList').css("border", "1px solid #d2d6de");
        $("#ErrOrderSizeChartList").text('');
        MakePostRequest(base_path + 'orderentryvtwo/getSubChartInfo', "sc=" + VarMasterChartId, 'json', fnShowSubChartRes);
        return false
    }

    function fnShowSubChartRes(data) {
        if (data != '') {
            if (data.errcode != undefined) {
                if (data.errcode == '404') {
                    fnCallSessionExpire();
                    return false;
                } else {
                    $("#divSubChartList").html(data.ss);
                }
            }
        }
    }

    // ************ VALIDATE DECIMAL POINT FOR INPUT FIELDS ********************* //
    function validateFloatKeyPress(el) {
        var v = parseFloat(el.value);
        el.value = (isNaN(v)) ? '' : v.toFixed(2);
    }
</script>
