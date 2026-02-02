<style>
    label{
    color:#022b61!important;
    font-size:12px!important;
    }
    .herr {
        color: red;
    }
    .mandatory{
        color: red;
    }
    .form-control[readonly]{
    background:#eee!important;
    color:#00050b!important;
    }
    .form-control[disabled]{
        color:#00050b!important;
    }
    .select2-container .select2-selection--single {
    font-size:12px!important;
}
    .form-control {
        /*height: 38px !important;*/
        border: 0.001em solid #cccaca;
        font-size:12px!important;
    }
    .jexcel tbody tr:nth-child(even) {
        background-color: #EEE9F1 !important;
    }
    .jexcel_overflow {
        width: 100% !important;
    }
    .jexcel{
        width: 100% !important;
        white-space: inherit !important;
    }
    .jdropdown-focus {
        position: inherit !important;
    }
    .jdropdown-default .jdropdown-item{
        padding: 2px !important
    }
    col:first-child {
        width: 3% !important;
    }
    .jexcel > thead > tr > td {
        padding: 1px !important;
        font-size: 12px;
        height: 50px;
    }
    .jexcel > tbody > tr > td.jexcel_dropdown {
        background-repeat: no-repeat;
        background-position: top 50% right -1px;
        background-image: url("data:image/svg+xml,%0A%3Csvg xmlns='http://www.w3.org/2000/svg' width='40' height='24' viewBox='0 0 10 20'%3E%3Cpath fill='none' d='M0 0h24v24H0V0z'/%3E%3Cpath d='M7 10l5 5 5-5H7z' fill='gray'/%3E%3C/svg%3E");
        text-overflow: ellipsis;
        overflow-x: hidden;
    }

    .jexcel > tbody > tr > td {
        height: 37px;
        color: black !important;
        font-size: 12px;
    }
    .jexcel > tfoot > tr > td {
        height: 37px;
        color: black !important;
        font-size: 12px;
    }
    .jexcel > col:first-child {
        width: 3% !important;
    }
    .jexcel_content {
        padding-right: 0 !important;
    }
    .nav-pills .nav-link.active, .nav-pills .show > .nav-link {
        color: #FFFFFF;
        background-color: #022b61;
    }
    .jexcel tbody tr:nth-child(2n) {
        background-color: #FFFFFF !important;
    }

    .bgc-tab-gray:hover {
        background-color: #dcdcdc !important;
    }

    .jexcel {
        border-right: 1px solid #f7f7f7 !important;
        border-bottom: 1px solid #f7f7f7 !important;
    }

    .jexcel > thead > tr > td {
        border: 0.01em solid #f7f7f7 !important;
    }
    .jexcel > tbody > tr > td {
        border: 0.01em solid #f7f7f7 !important;
    }
    .jexcel > tfoot > tr > td {
        border: 0.01em solid #f7f7f7 !important;
    }
    .nav-item-r-border{ 
        border-right: 3px solid #ffffff;
    }
    .btn-light-lightgrey {
        color: #011837 !important;
        background-color: #ebecec;
    }
    .btn-h-light-purple[class*="btn-light-"]:hover {
        color: #022b61;
        background-color: #dcdcdc;
        border-color: #afa8d5;
    }
    .btn-a-purple:not(:disabled):not(.disabled):active, .btn-a-purple:not(:disabled):not(.disabled).active, .show > .btn.btn-a-purple.dropdown-toggle {
        color: #fff !important;
        background-color: #022b61 !important;
        border-color: #695ea7;
    }
    .brc-royal-blue{
        border-color: #022b61;
    }
    textarea#frmBasicComments {
        height: 95px !important;
    }

    /* new css*/
    .ord-procs-cell {
        width: 25%;
    }
    .tbl-procs-border {
        border: 1px solid #eee;
    }

    td.process-value, td.process-title, .process-main-value, td.process-main-head {
        font-size: 13px;
    }

    td.process-title {
        background: #e8e8e8;
        width: 40% !important;
        text-align: right;
    }

    td.process-main-head {
        background: #022b61;
        color: #ffffff;
        text-align: center;
    }

    .h-121 {
        height: 121px;
    }

    .mar-b-0 {
        margin-bottom: 0px !important;
    }

    .pad-0 {
        padding: 0px !important;
    }

    .grn-bg {
        background: #e5fff5;
        padding: 3px;
    }
    
    .blu-bg {
        background: #c2f8fe;
        padding: 3px;
    }

    .pad-tb-5 {
        padding: 5px 0;
    }

    td.process-title.detail-title {
        width: 21% !important;
    }

    .inp-full-wd {
        width: 100%;
        padding: 0 12px;
        height: 40px;
        border: none;
    }

    input[type=number]::-webkit-inner-spin-button, 
    input[type=number]::-webkit-outer-spin-button { 
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
        margin: 0; 
    }
.cardhead{
    border-top: 1px solid rgb(0 0 0)!important;
    border-bottom:0px!important;
}
.text-black{
   color:#0036ae!important;
   font-size:18px!important;
}
 .no-attach-fnd{
         margin: 0px 0px 0px 0px!important;
    }
 .attach-fnd{
    border: 1px dotted #cecece;
    padding: 5px;  
    margin: 0px!important;
    }
.modal-footer{
    border-top-color: #ebecec!important;
    background-color: #ebecec!important;
}
.tborder{
    border:0.001em solid #cccaca;
    border-radius:3px;
    text-align:center;
    height:calc(1.5em + 0.75rem + 2px);
    padding:0.375rem 0.75rem;
}
.tborder:focus{
    box-shadow: 0 0 0 2px rgb(245 153 66 / 20%) !important;
    color: #696969;
    border-color: #F59942 !important;
    background-color: #fff;
    outline: none;
}
.modal-title{
    font-size:large!important;
    font-weight:500!important;
}
</style>
<?php // commented by me $this->load->view(CNFCOMPANY . 'template/header'); 
$this->load->view(CNFCOMPANY . 'customheader'); ?>
<?php 
    // $this->load->view('pre_costing/pre_costing_details', array('components' => $components, 'VarEnqId' => $VarEnqId)); 
?>
<div class="content px-3">
<!-- *********************** ORDER PROCESSING START HERE ************************-->

<!-- <section class="content-header" style="padding-top: 0">
    <div class="card-header p-3 border-0 bgc-white tbl-head mb-1">
        <h1 class="card-title text-white f-14 text-500">ORDER PROCESSING</h1>
    </div>
    <div class="order-processing">
        <table id="" class="table">
            <tbody>
                <tr>
                    <td class="ord-procs-cell">
                        <table class="table tbl-procs-border">
                        <tbody>
                            <tr>
                                <td class="process-main-head">
                                    <strong><?php echo $ArrCommonHeaderData['companyName']; ?></strong>
                                </td>
                            </tr>
                            <tr class="h-121">
                                <td class="process-main-value"><?php echo $ArrCommonHeaderData['companyAddress']; ?></td>
                            </tr>
                        </tbody>
                        </table>
                    </td>
                    
                    <td class="ord-procs-cell">
                        <table class="table tbl-procs-border">
                        <tbody>
                            <tr>
                                <td class="process-title">Merch. Name: </td>
                                <td class="process-value"> <?php echo @$ArrCommonHeaderData['merchantName'] ?></td>
                            </tr>
                            <tr>
                                <td class="process-title">Merch. Code:</td>
                                <td class="process-value"> <?php echo @$ArrCommonHeaderData['merchantCode'] ?></td>
                            </tr>
                            <tr>
                                <td class="process-title">Contact No:</td>
                                <td class="process-value"> <?php echo @$ArrCommonHeaderData['merchantMobile'] ?></td>
                            </tr>
                            <tr>
                                <td class="process-title">e-mail ID:</td>
                                <td class="process-value"> <?php echo @$ArrCommonHeaderData['merchantEmail'] ?></td>
                            </tr>
                        </tbody>
                        </table>
                    </td>
                    
                    <td class="ord-procs-cell">
                        <table class="table tbl-procs-border">
                        <tbody>
                            <tr>
                                <td class="process-title">Team Name:</td>
                                <td class="process-value"><?php echo @$ArrCommonHeaderData['ArrTeam']['contactname'] ?></td>
                            </tr>
                            <tr>
                                <td class="process-title">Team Code:</td>
                                <td class="process-value"><?php echo @$ArrCommonHeaderData['ArrTeam']['code'] ?></td>
                            </tr>
                            <tr>
                                <td class="process-title">Contact No:</td>
                                <td class="process-value"><?php echo @$ArrCommonHeaderData['ArrTeam']['mobile'] ?></td>
                            </tr>
                            <tr>
                                <td class="process-title">e-mail ID:</td>
                                <td class="process-value"><?php echo @$ArrCommonHeaderData['ArrTeam']['username'] ?></td>
                            </tr>
                        </tbody>
                        </table>
                    </td>
                    
                    <td class="ord-procs-cell">
                        <table class="table tbl-procs-border">
                        <tbody>
                            <tr>
                                <td class="process-main-head" colspan="4">
                                    <strong>INTERNAL REFERENCE NO.</strong>
                                </td>
                            </tr>
                            <tr>
                                <td class="process-title">WIP No</td>
                                <td class="process-value" colspan="3">
                                <?php echo $ArrCommonHeaderData['ArrEnquiryDetails']['isriorcode']; ?>
                                </td>
                            </tr>
                            <tr>
                                <td class="process-title">Date & Time:</td>
                                <td class="process-value" colspan="3">
                                <?php
                                    echo isset($ArrCommonData->datecreated) ? date('d-m-Y H:i:s', strtotime($ArrCommonData->datecreated)) : date('d-m-Y H:i:s');
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <td class="process-title">Total Order Qty.</td>
                                <td class="process-value pad-0">
                                    <input class="inp-full-wd" type="text" value="" placeholder="Free Text" />
                                </td>
                                <td class="pad-0">
                                <table class="table mar-b-0">
                                    <tbody>
                                        <tr>
                                            <td class="process-title">UOM</td>
                                            <td class="process-value pad-0">
                                            <input class="inp-full-wd" type="text" value="" placeholder="Free Text" />
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                </td>
                            </tr>
                        </tbody>
                        </table>
                    </td>

                </tr>
            </tbody>
        </table>
    </div>

    <div class="card-header p-3 border-0 bgc-white tbl-head mb-1">
        <h1 class="card-title text-white f-14 text-500">ORDER DETAILS</h1>
    </div>

    <div class="order-details">
        <table id="" class="table">
            <tbody>
                <tr>
                    <td class="ord-procs-cell">
                        <table class="table tbl-procs-border">
                        <tbody>
                            <tr>
                                <td class="process-title detail-title">Order Ref. No:</td>
                                <td class="process-value detail-value">
                                    <?php if (!empty($ArrCommonHeaderData['ArrEnquiryDetails']['orderenqrefno'])) echo $ArrCommonHeaderData['ArrEnquiryDetails']['orderenqrefno'] ?>
                                </td>
                                <td class="process-title detail-title">Brand:</td>
                                <td class="process-value detail-value">
                                    <?php echo @$ArrCommonHeaderData['ArrEnquiryDetails']['brandname']; ?>
                                </td>
                            </tr>
                            <tr>
                                <td class="process-title detail-title">Style Ref. No:</td>
                                <td class="process-value detail-value"> IOR/0521/BSG-21</td>
                                <td class="process-title detail-title">Buyer:</td>
                                <td class="process-value detail-value"> IOR/0521/BSG-21</td>
                            </tr>
                            <tr>
                                <td class="process-title detail-title">Style Description:</td>
                                <td class="process-value detail-value" colspan="3"> IOR/0521/BSG-21</td>
                            </tr>
                        </tbody>
                        </table>
                    </td>
                    
                    <td class="ord-procs-cell">
                        <table class="table tbl-procs-border">
                        <tbody>
                            <tr>
                                <td class="process-title detail-title">Season:</td>
                                <td class="process-value detail-value pad-0">
                                    <input class="inp-full-wd" type="text" 
                                    value="<?php if (!empty($ArrCommonHeaderData['ArrCommonData']->season))
                                                echo $ArrCommonHeaderData['ArrCommonData']->season
                                            ?>" 
                                    placeholder="Free Text" />
                                </td>
                                <td class="process-title detail-title">Class:</td>
                                <td class="process-value detail-value pad-0">
                                    <input class="inp-full-wd" type="text" 
                                    value="<?php if (!empty($ArrCommonHeaderData['ArrCommonData']->class))
                                                    echo $ArrCommonHeaderData['ArrCommonData']->class
                                            ?>" 
                                    placeholder="Free Text" />
                                </td>
                            </tr>
                            <tr>
                                <td class="process-title detail-title">Divi. /Dept:</td>
                                <td class="process-value detail-value"> IOR/0521/BSG-21</td>
                                <td class="process-title detail-title">Sub Class:</td>
                                <td class="process-value detail-value"> IOR/0521/BSG-21</td>
                            </tr>
                            <tr>
                                <td class="process-title detail-title">Size Range:</td>
                                <td class="process-value detail-value pad-0" colspan="3">
                                    <input class="inp-full-wd" type="text" value="" placeholder="Free Text" />
                                </td>
                            </tr>
                        </tbody>
                        </table>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

</section> -->

<!-- *********************** ORDER PROCESSING START HERE ************************-->

<!-- *********************** PRE-COSTING PAGE START HERE ************************-->

<?php $requestFor = isset($ArrEnquiryInfo[0]->reqforisrior) ? $ArrEnquiryInfo[0]->reqforisrior : ''; ?>
<?php $this->load->view('pre_costing/pre_costing_details',array('components' => $components, 'VarEnqId' => $VarEnqId , 'requestFor' => $requestFor, 'accessPermission' => true));?>

<!-- *********************** PRE-COSTING PAGE ENS HERE ************************-->

<!-- <section class="content-header" style="padding-top: 0"> -->
    <!-- <div class="col-md-12">
        <h1 style="font-size: 20px; font-weight: 600">MANAGEMENT - ENQUIRY AUTHORIZATION</h1>
    </div>
    <div class="col-md-3">
        <div class="box box-solid">
            <div class="box-body row" style="padding: 9px">
                <div class="col-md-6" style="border-right: 1px solid black; padding-left: 10px; padding-right: 10px">
                    <a href="javascript:void(0);" style="color: #000" onclick="fnShowEnqInfo();">
                        <i id="basicInfoCircle" class="fa fa-circle" style="padding-right: 5px"></i> Basic Information</a>
                </div>
                <div class="col-md-4" style="padding-left: 10px; padding-right: 10px">
                    <a href="javascript:void(0);" style="color: #000" onclick="fnShowEnqLog('divLog', 'divBasicInfo');">
                        <i id="logcircle" class="fa fa-circle-o" style="padding-right: 5px"></i> Logs List</a>
                </div>
            </div>
        </div>
    </div> -->
<!--    <div class="col-md-2">
        <a class="btn btn-default pull-right" style="font-weight: 600; padding: 7px 12px !important"
           href="javascript:history.back()">Back</a> 
    </div>-->
<!-- </section> -->


<div class="card-body border-0 p-0 m-0 collapse show" id="divBasicInfo">
    <div class="box-title cardhead text-black card-title f-14 text-600" style="padding: 10px;">Enquiry Authorization</div> 
    <div class="col-12 p-0 m-0" style="background-color: #f7f7f7">
        <div class="row border-0 p-0 m-0 no-rad-form" id="enquiry_view_form">
            <div class="col-4 pt-4 pb-2">
                <div class="form-group row">
                    <div class="col-4 px-120 col-form-label text-sm-right pr-0 my-1 py-1">
                        <label for="id-form-field-focus-1" class="mb-0">
                            Order / Enq. Ref. No.<span class="mandatory">*</span>
                        </label>
                    </div>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" readonly value="<?php if (!empty($ObjEnquiry->orderenqrefno)) echo $ObjEnquiry->orderenqrefno ?>">
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-4 px-120 col-form-label text-sm-right pr-0 my-1 py-1">
                        <label for="id-form-field-focus-1" class="mb-0">
                            Order / Enq. Date <span class="mandatory">*</span>
                        </label>
                    </div>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" readonly value="<?php echo dateHelp($ObjEnquiry->enquirydate, false) ?>">
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-4 px-120 col-form-label text-sm-right pr-0 my-1 py-1">
                        <label for="id-form-field-focus-1" class="mb-0">
                            Style Ref. No. / Name
                        </label>
                    </div>
                    <div class="col-sm-8">
                        <input type="text" placeholder="Free Text" id="frmBasicStyleRefNo" name="frmBasicStyleRefNo" class="form-control"
                               readonly value="<?php if (!empty($ObjEnquiry->stylenamerefno)) echo $ObjEnquiry->stylenamerefno ?>">
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-4 px-120 col-form-label text-sm-right pr-0 my-0 py-1">
                        <label for="id-form-field-focus-1" class="mb-0">
                            Style Description <span class="mandatory">*</span>
                        </label>
                    </div>
                    <div class="col-sm-8">
                        <textarea class="my-0 py-2 mb-2 form-control" id="frmBasicStyleDesc" style="height: 85px !important;" name="frmBasicStyleDesc"  placeholder="Free Text" readonly><?php if (!empty($ObjEnquiry->styledesc)) echo $ObjEnquiry->styledesc ?></textarea>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-4 px-120 col-form-label text-sm-right pr-0 mt-0 my-1 py-1">
                        <label for="id-form-field-focus-1" class="mb-0">
                            Order / Enq. Type <span class="mandatory">*</span>
                        </label>
                    </div>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" readonly value="<?php echo $ObjEnquiry->enquirytype ?>">
                        <div class="herr" id="ErrfrmBasicEType"></div>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-4 px-120 col-form-label text-sm-right pr-0 my-1 py-1">
                        <label for="id-form-field-focus-1" class="mb-0">
                            Mode of Enquiry <span class="mandatory">*</span>
                        </label>
                    </div>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" readonly value="<?php echo $ObjEnquiry->modeofenquiry ?>">
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-4 px-120 col-form-label text-sm-right pr-0 my-1 py-1">
                        <label for="id-form-field-focus-1" class="mb-0">
                            Brand <span class="mandatory">*</span>
                        </label>
                    </div>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" readonly  id="frmBasicBrand" value="<?php echo $ArrBrand[$ObjEnquiry->brandId] ?>">
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-4 px-120 col-form-label text-sm-right pr-0 my-1 py-1">
                        <label for="id-form-field-focus-1" class="mb-0">
                            Buyer <span class="mandatory">*</span>
                        </label>
                    </div>
                    <div class="col-sm-8">
                        <!--<input type="text" class="form-control" readonly id="frmBasicBrand" value="<?php //echo $ArrBuyer[$ObjEnquiry->buyerId] ?>">-->
                         <input type="text" class="form-control" placeholder="Auto Update" readonly id="frmBasicBrand" value="<?php echo !empty($ObjEnquiry->buyername)?$ObjEnquiry->buyername:""; ?>">
                    </div>
                </div>
            </div>
            <div class="col-4 pt-4 pb-2">
                <div class="form-group row">
                    <div class="col-4 px-120 col-form-label text-sm-right pr-0 my-1 py-1">
                        <label for="id-form-field-focus-1" class="mb-0">
                            Country <span class="mandatory">*</span>
                        </label>
                    </div>
                    <div class="col-sm-8">
                        <!--<input type="text" class="form-control" readonly value="<?php echo $VarCountry ?>">-->
                        <input type="text" class="form-control" placeholder="Auto Update" readonly value="<?php echo !empty($ObjEnquiry->country)?$ObjEnquiry->country:""; ?>">
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-4 px-120 col-form-label text-sm-right pr-0 my-1 py-1">
                        <label for="id-form-field-focus-1" class="mb-0">
                            Total Order Qty. <span class="mandatory">*</span>
                        </label>
                    </div>
                    <div class="col-4 px-120">
                        <input type="text" class="form-control" readonly value="<?php echo $ObjEnquiry->exporderqty ?>">
                        <div class="herr" id="ErrfrmBasicPqty"></div>
                    </div>
                    <div class="col-4 px-120">
                        <input type="text" class="form-control" readonly value="<?php echo $VarPcsorSet ?>">
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-4 px-120 col-form-label text-sm-right pr-0 my-1 py-1">
                        <label for="id-form-field-focus-1" class="mb-0"> No. of Components <span class="mandatory">*</span> </label>
                    </div>
                    <div class="col-sm-8">
                        <input type="text" placeholder="Free Text" id="frmComponents" name="frmComponents" class="form-control" readonly value="<?php echo isset($ObjEnquiry->totalcomponents) ? $ObjEnquiry->totalcomponents : ''; ?>">
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-4 px-120 col-form-label text-sm-right px-0 my-1 py-1">
                        <label for="id-form-field-focus-1" class="mb-0">
                            No. of Combo / Colour <span class="mandatory">*</span>
                        </label>
                    </div>
                    <div class="col-sm-8">
                        <input type="text" placeholder="Free Text" id="frmcombo" name="frmcombo" class="form-control" readonly value="<?php echo isset($ObjEnquiry->totalcombo) ? $ObjEnquiry->totalcombo : '' ?>">
                    </div>
                </div>
                
                <div class="form-group row">
                    <div class="col-4 px-120 col-form-label text-sm-right px-0 my-1 py-1">
                        <label for="id-form-field-focus-1" class="mb-0">
                            Shipment / Submission Date <span class="mandatory">*</span>
                        </label>
                    </div>
                    <div class="col-sm-8">
                        <div class="input-group date">
                            <input class="form-control" id="frmShipmentDate" name="frmShipmentDate" readonly value="<?php echo date('d-m-Y', strtotime($ObjEnquiry->shipmentdate)) ?>">
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-4 px-120 col-form-label text-sm-right pr-0 my-1 py-1">
                        <label for="id-form-field-focus-1" class="mb-0">
                            Price Quoted For <span class="mandatory">*</span>
                        </label>
                    </div>
                    <div class="col-sm-8">
                        <?php $ArrPriceQuotedFor = PRICEQUOTEDFOR ?>
                        <input type="text" class="form-control" readonly
                               value="<?php echo $ArrPriceQuotedFor[@$ObjEnquiry->pricequotedfor] ?>">
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-4 px-120 col-form-label text-sm-right pr-0 my-1 py-1">
                        <label for="id-form-field-focus-1" class="mb-0">
                            Quoted Price <span class="mandatory">*</span>
                        </label>
                    </div>
                    <div class="col-4 px-120">
                        <input type="text" class="form-control" readonly value="<?php echo $ObjEnquiry->quotedprice ?>">
                    </div>
                    <div class="col-4 px-120">
                        <input type="text" class="form-control" readonly value="<?php echo $VarCurrency ?>">
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-4 px-120 col-form-label text-sm-right pr-0 my-1 py-1">
                        <label for="id-form-field-focus-1" class="mb-0">Buyer's Price <span class="mandatory">*</span> </label>
                    </div>
                    <div class="col-4 px-120">
                        <input type="text" class="form-control" readonly value="<?php echo $ObjEnquiry->buyerprice ?>">
                    </div>
                    <div class="col-4 px-120">
                        <input type="text" class="form-control" readonly value="<?php echo $VarCurrency ?>">
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-4 px-120 col-form-label text-sm-right pr-0 my-1 py-1">
                        <label for="id-form-field-focus-1" class="mb-0">Confirmed Price <span class="mandatory">*</span></label>
                    </div>
                    <div class="col-4 px-120">
                        <input type="text" class="form-control" readonly value="<?php echo $ObjEnquiry->confirmprice ?>">
                    </div>
                    <div class="col-4 px-120">
                        <input type="text" class="form-control" readonly value="<?php echo $VarCurrency ?>">
                    </div>
                </div>
            </div>
            <div class="col-4 pt-4 pb-2">
                <div class="form-group row">
                    <div class="col-4 px-120 col-form-label text-sm-right pr-0 my-1 py-1">
                        <label for="id-form-field-focus-1" class="mb-0">
                            Request For <span class="mandatory">*</span>
                        </label>
                    </div>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" readonly value="<?php echo $VarIsrIor ?>">
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-4 px-120 col-form-label text-sm-right pr-0 my-1 py-1">
                        <label for="id-form-field-focus-1" class="mb-0">
                            Request Date & Time
                        </label>
                    </div>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" readonly value="<?php echo $ObjEnquiry->reqdatetime; ?>">
                    </div>
                </div>
                <div class="form-group row">
                        <div class="col-4 px-120 col-form-label text-sm-right pr-0 my-1 py-1">
                            <label for="id-form-field-focus-1" class="mb-0">
                                ISR Ref. If Any
                            </label>
                        </div>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" readonly id="frmBasicISRany" placeholder="Auto Update" value="<?php echo $ObjEnquiry->isrrefany ?>">
                        </div>
                    </div>
                <div class="form-group row">
                    <div class="col-4 px-120 col-form-label text-sm-right pr-0 my-1 py-1">
                        <label for="id-form-field-focus-1" class="mb-0"> Merchant Name </label>
                    </div>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" readonly value="<?php echo $VarMerUser['contactname'] ?>">
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-4 px-120 col-form-label text-sm-right pr-0 my-1 py-1">
                        <label for="id-form-field-focus-1" class="mb-0">
                            Authorization Status <span class="mandatory">*</span>
                        </label>
                    </div>
                    <div class="col-sm-8">
                        <?php
                        $ArrAppRejStatus   = unserialize(ORDERENQUIRYSTATUS);
                        unset($ArrAppRejStatus[0]);
                        unset($ArrAppRejStatus[1]);
                        unset($ArrAppRejStatus[4]);
                        //$VarSty = 'style="background-color: green"';
                        ?>
                        <select class="form-control" id="frmBasicOrderStatus" name="frmBasicOrderStatus" <?php  if ($ObjEnquiry->orderstatus == "3") { echo 'disabled'; } ?>>
                            <option value="">Select Status</option>
                            <?php
                            foreach ($ArrAppRejStatus as $key => $item)
                            {
                                echo '<option value="' . $key . '" ';
                                echo ($key == $ObjEnquiry->orderstatus) ? 'selected' : '';
                                echo '>' . $item . '</option>';
                            }
                            ?>
                        </select>
                        <div class="herr" id="ErrfrmBasicPs"></div>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-4 px-120 col-form-label text-sm-right pr-0 my-1 py-1">
                        <label for="id-form-field-focus-1" class="mb-0">
                            Authorized By
                        </label>
                    </div>
                    <div class="col-sm-8">
                        <input type="text" id="frmAuthorizedBy" name="frmAuthorizedBy" class="form-control" placeholder="Auto Update"
                               value="<?php echo $ObjEnquiry->authorizedby; ?>" readonly>
                        <div class="herr" id="ErrfrmAuthorizedBy"></div>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-4 px-120 col-form-label text-sm-right px-0 my-1 py-1">
                        <label for="id-form-field-focus-1" class="mb-0">
                            Authorized Date & Time
                        </label>
                    </div>
                    <div class="col-sm-8">
                        <input type="text" id="frmAuthorizedDate" name="frmAuthorizedDate" class="form-control" placeholder="Auto Update"
                               value="<?php echo ($ObjEnquiry->formattedDateAuthorized!='00-00-0000 00:00:00')? $ObjEnquiry->formattedDateAuthorized:''; ?>" readonly>
                        <div class="herr" id="ErrfrmAuthorizedDate"></div>
                    </div>
                </div>
                <!--<div class="form-group row">-->
                <!--    <div class="col-4 px-120 col-form-label text-sm-right px-0 my-1 py-1">-->
                <!--        <label for="id-form-field-focus-1" class="mb-0">-->
                <!--            Authorized Date & Time-->
                <!--        </label>-->
                <!--    </div>-->
                <!--    <div class="col-sm-8">-->
                <!--        <input type="text" id="frmReqDT" class="form-control" readonly value="<?php echo $ObjEnquiry->formattedDateAuthorized ?>">-->
                <!--    </div>-->
                <!--</div>-->
                <div class="form-group row">
                    <div class="col-4 px-120 col-form-label text-sm-right px-0 my-1 py-1">
                        <label for="id-form-field-focus-1" class="mb-0">
                            Management Remarks <span class="mandatory">*</span>
                        </label>
                    </div>
                    <div class="col-sm-8">
                        <textarea style="" id="frmBasicComments" placeholder="Free Text" <?php  if ($ObjEnquiry->orderstatus == "3") { echo 'readonly'; } ?> name="frmBasicComments" class="form-control"><?php echo @$ObjEnquiry->comments ?></textarea>
                        <div class="herr" id="ErrfrmBasicComments"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 bgc-white pt-3 px-3">
        <div class="form-group row">
            <div class="col-sm-12 col-form-label text-sm-left pr-0">
                <label for="id-form-field-focus-1" class="mb-0"> Merchant Remarks </label>
            </div>
            <div class="col-sm-12">
                <textarea id="frmBasicMnote" style="height: 76px !important; padding: 20px 22px;border-radius:0.125rem !important;" readonly name="frmBasicMnote" class="form-control" placeholder="Free Text"><?php echo (isset($ObjEnquiry->merchantnote) && $ObjEnquiry->merchantnote!='undefined') ? $ObjEnquiry->merchantnote : '' ?></textarea>
            </div>
        </div>
    </div>

    <div class="col-12 bgc-white pt-0 px-3">
        <div class="form-group row">
            <!--<div class="col-12 col-form-label pr-0">-->
            <!--    <label for="id-form-field-focus-1" style="" class="mb-0">-->
            <!--        Attachment-->
            <!--    </label>-->
            <!--</div>-->
            <div class="col-12 px-0">
                <div class="col-12 px-3">
                    <div class="row attach-fnd">
                        <ul class="upload-list-view mb-0 p-0" style="list-style: none;">
                            <?php

                              $subscriber_id = 'Sub_Id_'.$ObjEnquiry->subscriberid;
                                $enquid_subids = $ObjEnquiry->enquid_subid;

                            $VarFdr = UPLOADS_SLASH . "orderenquiry". DIRECTORY_SEPARATOR . $subscriber_id .DIRECTORY_SEPARATOR . $enquid_subids . DIRECTORY_SEPARATOR;
                            
                            //$VarFdr           = UPLOADS_SLASH . "orderenquiry" . DIRECTORY_SEPARATOR . $VarEnqId . DIRECTORY_SEPARATOR;
                            $ArrDwnExtensions = DWN_FILE_EXTENSIONS;
                            $enquiry_id       = urlencode(base64_encode($VarEnqId));
                            if (file_exists($VarFdr))
                            {
                                if ($dh = opendir($VarFdr))
                                {
                                    $i    = 1;
                                    while (($file = readdir($dh)) !== false)
                                    {
                                        if (is_file($VarFdr . $file))
                                        {
                                            ?>
                                            <li class="file-viwer-jig">
                                                <div style="padding: 10px 0;" id="temp_file_<?php echo $i ?>">
                                                <div class="ajax-file-upload-filename">
                                                    <?php
                                                    $VarFileExt = pathinfo($file, PATHINFO_EXTENSION);
                                                    echo $file . ' ';
                                                    ?>
                                                </div>
                                                <div class="upload-action-btn">
                                                    <a href="<?php echo base_url() . "merchant/enqFileDownload?id=" . $enquiry_id . "&fileName=" . urlencode($file) ?>" title="Download">
                                                        <i class="fa fa-download fa-lg" aria-hidden="true"></i>
                                                    </a>
                                                    <?php
                                                    if (in_array($VarFileExt, $ArrDwnExtensions))
                                                    {
                                                        
                                                    }
                                                    else
                                                    {
                                                        ?>
                                                        <a href="<?php echo base_url() . "merchant/enqOpenFile?id=" . $enquid_subids . "&fileName=" . urlencode($file) ?>" target="_blank" title="Open in New Tab">
                                                            <i class="fa fa-file fa-lg" aria-hidden="true"></i>
                                                        </a>
                                                        <?php
                                                    }
                                                    ?>
                                                    <!--<a href="javascript:void(0);" title="Delete" onclick="deleteFile('<?php echo $i ?>', '<?php echo $enquiry_id ?>', '<?php echo urlencode($file) ?>')">-->
                                                    <!--    <i class="fa fa-trash fa-lg" aria-hidden="true"></i>-->
                                                    <!--</a>-->
                                                </div>
                                                </div>
                                            </li>
                                            <?php
                                        }
                                        $i++;
                                    }
                                    closedir($dh);
                                }
                                ?>
                                <?php
                            }
                            else
                            {
                                //  echo '<p class="no-attach-fnd">No attachments</p>';
                            }
                            ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 text-right p-3">
        <?php
        if ($ObjEnquiry->status == 1)
        {
            if ($ObjEnquiry->orderstatus == "1" || $ObjEnquiry->orderstatus == "4")
            {
                ?>

                <button class="btn btn-sm btn-royal-blue-submit" id="saveapprenqbutton" onclick="return fnSaveEnquiryApproval();">Save</button>
                <?php
            }
        }
        ?>
    </div>
</div>

<!--- Enquiry Log List --->
<!-- <div class="col-md-12" id="divLog">
    <div class="box box-info">
        <div class="box-header with-border">
            <div class="box-title card-title text-royal-blue f-14 text-500" style="padding: 10px;">Logs List</div>
        </div>
        <div class="box-body">
            <table id="tableLogList" class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>Order Enq. Ref. No.</th>
                        <th>Request Date Time</th>
                        <th>Brand</th>
                        <th>Merchant Remarks</th>
                        <th>Management Remarks</th>
                        <th>Status</th>
                        <th>Recent <br />Update </th>
                    </tr>
                </thead>
            </table>
            <div id="DivTotalCntResult"></div>
        </div>
    </div>
</div> -->


<!--- Modal Popup --->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header" style="display: block;">
                <h4 class="modal-title" id="myModalLabel">Enter PIN</h4>
            </div>
            <div class="modal-body text-center">
                <form class="form-horizontal col-md-12 mb-0" method="post" id="frmPinformId">
                    <div id="divOuter">
                        <div id="divInner otp-input">
                            <!-- <input id="frmPin" type="password" maxlength="4" /> -->
                            <input type="text" class="tborder" id="numberone" maxlength="1" size="1" min="0" max="9" pattern="[0-9]{1}" />
                            <input type="text" class="tborder" id="numbertwo" maxlength="1" size="1" min="0" max="9" pattern="[0-9]{1}" />
                            <input type="text" class="tborder" id="numberthree" maxlength="1" size="1" min="0" max="9" pattern="[0-9]{1}" />
                            <input type="text" class="tborder" id="numberfour" maxlength="1" size="1" min="0" max="9" pattern="[0-9]{1}" />

                            <!-- <div class="herr pull-right" id="ErrfrmPin"></div> -->
                            <div class="herr " style="padding-top:10px;" id="ErrfrmPin"></div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-danger" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-sm btn-success" onclick="return fnCheckPin()">Continue</button>
                <!--<button type="button" class="btn btn-sm btn-blue-submit" data-dismiss="modal">Close</button>-->
                <!--<button type="submit" class="btn btn-sm btn-royal-blue-submit" onclick="return fnCheckPin()">Continue</button>-->
            </div>
        </div>
    </div>
</div>

</div>
<?php $this->load->view(CNFCOMPANY . 'template/footer'); ?>
<div class="control-sidebar-bg"></div>
<script src="<?php echo base_url(); ?>assets/js/commonfunctions.js?rn=<?php echo CNFJSCSSRANDNO ?>"></script>
<script src="<?php echo base_url(); ?>assets/js/loadingoverlay.min.js"></script>
<script>
                    var GlbEnquiryId = '<?php echo @$VarEnqId; ?>';
                    var GlbIsrIor = '<?php echo @$VarIsrIorId ?>';

                    $(document).ajaxStart(function(a) {
                        $.LoadingOverlay("hide", {image: base_path + "assets/img/fullpage.gif"});
                    });
                    $(document).ajaxStop(function() {
                        $.LoadingOverlay("hide");
                    });

                    var GlbAuthStatus = "";
                    var GlbRefType = "";
                    var GlbPcsSet = '';
                    var GlbComments = '';
                    var obj = document.getElementById('frmPin');
                    obj.addEventListener("keydown", stopCarret);
                    obj.addEventListener("keyup", stopCarret);
                    function stopCarret() {
                        if (obj.value.length > 3) {
                            setCaretPosition(obj, 3);
                        }
                    }

                    function setCaretPosition(elem, caretPos) {
                        if (elem != null) {
                            if (elem.createTextRange) {
                                var range = elem.createTextRange();
                                range.move('character', caretPos);
                                range.select();
                            }
                            else {
                                if (elem.selectionStart) {
                                    elem.focus();
                                    elem.setSelectionRange(caretPos, caretPos);
                                }
                                else
                                    elem.focus();
                            }
                        }
                    }

                    function fnSaveEnquiryApproval() {
                        $(".herr").text('');
                        $(".form-control").css("border", "1px solid #cccccc");
                        try {
                            GlbComments = $("#frmBasicComments").val();
                            GlbAuthStatus = $("#frmBasicOrderStatus").val();
                            if (jsTrim(GlbComments) == "" && GlbAuthStatus == "3") {
                                $('#ErrfrmBasicComments').html("Enter Management Remarks");
                                $('#frmBasicComments').focus();
                                $('#frmBasicComments').css("border", "1px solid #ff0000");
                                return false;
                            }
                            if (GlbAuthStatus != "") {
                                $('#myModal').modal('show');
                            }
                            else {
                                $('#frmBasicOrderStatus').css("border", "1px solid #ff0000");
                                $('#frmBasicOrderStatus').focus();
                                $('#ErrfrmBasicPs').text("Please Select Approved or Declined");
                                return false;
                            }
                        } catch (e) {
                            alert(e);
                        }
                    }

                    function fnCheckPin() {
                        $(".herr").text('');
                        try {
                            // var pw = $("#frmPin").val();
                            var one = $("#numberone").val();
                            var two = $("#numbertwo").val();
                            var three = $("#numberthree").val();
                            var four = $("#numberfour").val();
                            var val = one+two+three+four;
                            // console.log(val);
                            if (jsTrim(one) == "" && jsTrim(two) == "" && jsTrim(three) == "" && jsTrim(four) == "") {
                                $("#ErrfrmPin").text('Enter PIN');
                                return false;
                            }
                            MakeAsynPostRequest(base_path + 'management/fnCheckPin', "rfrom=1&i=" + val + "&enqid=" + GlbEnquiryId + "&s=" +
                                    GlbAuthStatus + "&c=" + encodeURIComponent(GlbComments) + "&ty=" + GlbIsrIor, 'json', fnAuthRes);
                            return false;



            function fnAuthRes(data) {
              
                if (data != '') {
                    if (data.errcode == '404') {
                        fnCallSessionExpire();
                        return false;
                    } else if (data.errcode == '-1') {
                        $('#ErrfrmPin').text(data.msg);
                        return false;
                    } else if (data.errcode == '1') {
                        $('#myModal').modal('hide');
                        $("#divSuccessBasicInfoDiv").removeClass('hide');
                        $("#divSuccessBasicInfoMsg").text(data.msg);
                        
                        let swalWithBootstrapButtons = Swal.mixin({buttonsStyling: false});
                        swalWithBootstrapButtons.fire(
                            {
                                title: 'Are you sure you want to save these details?',
                                type: 'warning',
                                showCancelButton: true,
                                scrollbarPadding: false,
                                confirmButtonText: 'Yes',
                                cancelButtonText: 'No',
                                reverseButtons: true,
                                customClass: {'confirmButton': 'btn btn-green mx-2 px-3',  'cancelButton': 'btn btn-red mx-2 px-3'}
                            }
                        ).then(function(result) {
                            if (result.value) {
                                $('#myModal').modal('hide');
                                
                                let idValue = $('#enquiryFormId').val();
                                let orderStatus =($("#order_status").val()==3)?4:1;
                                let status = 1;
                               MakeAsynPostRequest(base_path + "management/submitAuthRequest", 
    "rfrom=1&enqid=" + GlbEnquiryId + "&s=" + GlbAuthStatus + "&c=" + encodeURIComponent(GlbComments) + "&ty=" + GlbIsrIor, 
    "json", function (data) {  
                                    if(data.statusCode == "200") {
                                        swalWithBootstrapButtons.fire({
                                            title: 'Saved!',
                                            icon: 'success',
                                            customClass: {'confirmButton': 'btn btn-info px-5'}
                                        }).then((result) => {
                                            let enquiryListPath = "management/manageWip";
                                            enquiryListPath = base_path+enquiryListPath;
                                           
                                            window.location.href = enquiryListPath;
                                        });
                                        
                                    }
                                   
                                    else {
                                        swalWithBootstrapButtons.fire({title: 'Not saved!',text: data.message,type: 'error',icon: 'error',customClass: {'confirmButton': 'btn btn-info px-5'}});
                                    }
                                });

                                
                            } 
                           
                        }); 
                    }
                }
            }

                        } catch (e) {
                            alert(e);
                        }
                    }

        //             function fnAuthRes(data) {
        //                 if (data != '') {
        //                     if (data.errcode == '404') {
        //                         fnCallSessionExpire();
        //                         return false;
        //                     } else if (data.errcode == '-1') {
        //                         $('#ErrfrmPin').text(data.msg);
        //                         return false;
        //                     } else if (data.errcode == '1') {
        //                         $('#myModal').modal('hide');
        //                         $("#divSuccessBasicInfoDiv").removeClass('hide');
        //                         $("#divSuccessBasicInfoMsg").text(data.msg);
        //                         //console.log(base_path+data.redirectUrl,'redirectUrl');
        //                         fnRedirectPageTimeOut(base_path + data.redirectUrl);
        //                     }
        //                      else if (data.errcode == '1') {
        //     // Success case
        //     $('#myModal').modal('hide');
        //     $("#divSuccessBasicInfoDiv").removeClass('hide');
        //     $("#divSuccessBasicInfoMsg").text(data.msg);

        //     // Show SweetAlert2 success message
        //     swalWithBootstrapButtons.fire({
        //         title: data.msg,  // Success message from the server
        //         icon: 'success',
        //         customClass: {'confirmButton': 'btn btn-info px-5'}
        //     }).then((result) => {
        //         // Redirect after the user clicks "OK" on the success alert
        //         let redirectUrl = base_path + data.redirectUrl;
        //         window.location.href = redirectUrl;  // Perform the redirect
        //     });
        // }
        //                 }
        //             }

                    $("#divLog").hide();
                    var GlbSearchParam = '';
                    function fnShowEnqInfo() {
                        $("#divBasicInfo").show();
                        $("#basicInfoCircle").removeClass('fa fa-circle-o');
                        $("#basicInfoCircle").addClass('fa fa-circle');
                        $("#logcircle").removeClass('fa fa-circle');
                        $("#logcircle").addClass('fa fa-circle-o');
                        $("#divLog").hide();
                    }

                    $(function() {
                        $("#frmPin").keypress(function(e) {
                            var key = e.which;
                            if (key == 13) {
                                fnCheckPin();
                                return false;
                            }
                        });
                    });

                    function fnShowEnqLog(showdivid, hidedivid) {
                        $("#logcircle").removeClass('fa fa-circle-o');
                        $("#logcircle").addClass('fa fa-circle');
                        $("#basicInfoCircle").removeClass('fa fa-circle');
                        $("#basicInfoCircle").addClass('fa fa-circle-o');
                        $("#" + showdivid).show();
                        $("#" + hidedivid).hide();
                        fnLogList();
                    }

                    function fnLogList() {
                        MakeAsynPostRequest(base_path + 'enquiryLog/enquiryLogList', "rFrom=1&enquiryId=" + GlbEnquiryId, 'json', fnListEnqLogRes);
                    }

                    function fnListEnqLogRes(data) {
                        if (data != '') {
                            if (data.errCode != undefined) {
                                if (data.errCode == '404') {
                                    fnCallSessionExpire();
                                    return false;
                                } else {
                                    console.log(data, 'data');
                                    var PageContent, ListCount = '';
                                    if (data.cn > 0) {
                                        ListCount = '<div style="font-weight:bold;">Number of Record(s) : ' + data.cn + '</div>';

                                        $.each(data.re, function(index, value) {
                                            console.log(index, 'index');
                                            console.log(value, 'value');
                                            PageContent = PageContent + '<tr>' +
                                                    '<td>' +
                                                    '<a href="' + base_path + 'enquiryLog/enquiryLogDetail/' + encodeURIComponent(base64_encode(value.id)) + '">' + value.orderEnqRefNo + '' +
                                                    '</a>' +
                                                    '</td>' +
                                                    '<td>' + value.reqDateTime + '</td>' +
                                                    '<td>' + value.brn + '</td>' +
                                                    '<td>' + value.merchantRemarks + '</td>' +
                                                    '<td>' + value.manRemarks + '</td>' +
                                                    '<td>' + value.order_status_value + '</td>' +
                                                    '<td>' + value.dateupdated + '</td>';
                                            PageContent = PageContent + '</tr>';
                                        });

                                        $("#DivTotalCntResult").html(ListCount);
                                    } else {
                                        PageContent = PageContent + '<tr><td colspan="6" class="pdl15 herr text-center" style="padding-left:10px;">No Records(s) found</td></tr>';
                                        $("#DivTotalCntResult").html('');
                                    }
                                    $("tbody").empty();
                                    $("#tableLogList").append(PageContent);
                                }
                            }
                        }
                    }
</script>
<script src="<?php echo base_url() . "assets/js/jquery.fancybox.min.js" ?>"></script>
<script>
    $(function() {
        'use strict';

        var body = $('body');

        function goToNextInput(e) {
            var key = e.which,
            t = $(e.target),
            sib = t.next('input');

            // if (key != 9 && (key < 48 || key > 57)) {
            //     console.log('yes')
            //     e.preventDefault();
            //     return false;
            // }

            if (key === 9) {
                return true;
            }

            if (!sib || !sib.length) {
                sib = body.find('input').eq(0);
            }
            sib.select().focus();
        }

        function onKeyDown(e) {
            var key = e.which;
            if (key === 9 || (key == 96 || key == 97 || key == 98 || key == 99 || key == 100 || key == 101 || key == 102 || key == 103 || key == 104 || key == 105 || key >= 48 && key <= 57)) {
                return true;
            }

            e.preventDefault();
            return false;
        }
        
        function onFocus(e) {
            $(e.target).select();
        }

        body.on('keyup', 'input', goToNextInput);
        body.on('keydown', 'input', onKeyDown);
        body.on('click', 'input', onFocus);

        })
</script>
<style>
     .btn-sm {
        font-weight:400!important;
    }
</style>