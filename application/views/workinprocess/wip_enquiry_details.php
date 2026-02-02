<?php
/*
 * @var $ArrEnquiryInfo array
 * @var $ArrEnquiryType array
 * */
?>
<!--<link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>assets/plugins/datepicker/datepicker3.css">-->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/uploadfile-order.css"/>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.10.23/datatables.min.css"/>
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css" />
<script src="<?php echo base_url(); ?>assets/js/merchant/addeditenquiry.js"></script>

<style>
    #frmManagementRemarks{
        /* height: 155px !important; */
        height: 90px !important;
    }
    #enquiry_view_form{
        margin-top: 20px;
    }
    .herr {
        color: red;
    }
    .mandatory{
        color: red;
    }
    .table-condensed > tbody > tr > td,
    .table-condensed > tbody > tr > th,
    .table-condensed > tfoot > tr > td,
    .table-condensed > tfoot > tr > th,
    .table-condensed > thead > tr > td,
    .table-condensed > thead > tr > th {
        padding: 5px !important;
    }
label{
    color:#022b61!important;
    font-size:12px!important;
}
.form-control[readonly]{
    background:#eee!important;
    color:#00050b!important;
}
.form-control[disabled]{
    color:#00050b!important;
}
.form-control{
    font-size:12px!important;
}
.card-header:first-child{
    border-radius:0px!important;
}
.select2-container .select2-selection--single {
    font-size:12px!important;
}
.cardhead{
    border-top: 1px solid rgb(0 0 0)!important;
    border-bottom:0px!important;
}
.text-black{
   color:#0036ae!important;
   font-size:18px!important;
}
.ajax-file-upload-red{
    margin:0px 10px 18px!important;
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
.attach-fnd{
    border: 1px dotted #cecece;
    padding: 5px;  
    margin: 0px!important;
    }
 .no-attach-fnd{
     margin: 0px 0px 0px 0px!important;
}
</style>

<div class="card border-0 bgc-white pt-0" draggable="false" id="">
    <div class="card-header cardhead border-0 p-3 bgc-white  mb-1">
        <div  style="border-bottom: 1px solid #022B61;"></div>
        <div class="card-title text-black f-14 text-600">
            Enquiry Details
        </div>
        <div class="card-toolbar pr-3 d-none">
            <div class="dropdown">
                <ul class="nav nav-pills ml-auto">
                </ul>
            </div>
            <a href="#" data-action="expand" class="card-toolbar-btn text-orange-d3 d-style" draggable="false">
                <i class="fa fa-expand d-n-active pr-3"></i>
                <i class="fa fa-compress d-active pr-3"></i>
            </a>
            <a href="#" data-action="reload" class="card-toolbar-btn text-green" draggable="false">
                <i class="fas fa-sync-alt pr-3"></i>
            </a>
            <a href="#" data-action="toggle" class="card-toolbar-btn text-grey-d1" draggable="false">
                <i class="fa fa-chevron-up pr-3"></i>
            </a>
        </div>
    </div>
    <div class="card-body border-0 p-0 m-0 collapse show">
        <div class="col-12 p-0 m-0" style="background-color: #f7f7f7">
            <div class="row border-0 p-0 m-0 no-rad-form" id="enquiry_view_form">
                <input type="hidden" value="<?php echo $ArrEnquiryInfo[0]->id ?>" id="enquiryFormId" />
                <!-- First Column -->
                <div class="col-4 pt-4 pb-2">
                    <div class="form-group row">
                        <div class="col-4 px-120 col-form-label text-sm-right pr-0 my-1 py-1">
                            <label for="id-form-field-focus-1" class="mb-0">
                                Order / Enq. Ref. No <span class="mandatory">*</span>
                            </label>
                        </div>
                        <div class="col-sm-8">
                            <input type="text" readonly placeholder="Free Text" class="form-control" id="frmOrderEnqRefNo" value="<?= !empty($ArrEnquiryInfo[0]->orderenqrefno) ? $ArrEnquiryInfo[0]->orderenqrefno : '' ?>">
                            <div class="herr" id="ErrfrmOrderEnqRefNo"></div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-4 px-120 col-form-label text-sm-right pr-0 my-1 py-1">
                            <label for="id-form-field-focus-1" class="mb-0">
                                Order / Enq. Date <span class="mandatory">*</span>
                            </label>
                        </div>
                        <div class="col-sm-8">
                            <!-- <input type="date" id="frmBasicEnqDate" name="frmBasicEnquiryDate" class="form-control" 
                                   placeholder="Select Date from Calender"
                                   value="<?php //echo date('d-m-Y', strtotime($ArrEnquiryInfo[0]->enquirydate)) ?>"> -->
                            <input readonly type="text" id="frmBasicEnqDate" name="frmBasicEnquiryDate" class="form-control date" 
                                   placeholder="Select Date from Calender"
                                   value="<?php echo $ArrEnquiryInfo[0]->enquirydate ?>">
                            <div class="herr" id="ErrfrmBasicEnqDate"></div>
                        </div>

                    </div>
                    <div class="form-group row">
                        <div class="col-4 px-120 col-form-label text-sm-right pr-0 my-1 py-1">
                            <label for="id-form-field-focus-1" class="mb-0">
                                Style Ref. No. / Name
                            </label>
                        </div>
                        <div class="col-sm-8">
                            <input readonly type="text" placeholder="Free Text" id="frmBasicStyleRefNo" name="frmBasicStyleRefNo" class="form-control"
                                   value="<?php echo @$ArrEnquiryInfo[0]->stylenamerefno ?>">
                            <div class="herr" id="ErrfrmBasicStyleRefNo"></div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-4 px-120 col-form-label text-sm-right pr-0 my-0 py-1">
                            <label for="id-form-field-focus-1" class="mb-0">
                                Style Description <span class="mandatory">*</span>
                            </label>
                        </div>
                        <div class="col-sm-8">
                            <textarea readonly class="my-0 py-2 mb-2 form-control" id="frmBasicStyleDesc" style="height: 85px !important;" name="frmBasicStyleDesc"  placeholder="Free Text"><?php echo @$ArrEnquiryInfo[0]->styledesc ?></textarea>
                            <div class="herr" id="ErrfrmBasicStyleDesc"></div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-4 px-120 col-form-label text-sm-right pr-0 mt-0 my-1 py-1">
                            <label for="id-form-field-focus-1" class="mb-0">
                                Order / Enq. Type <span class="mandatory">*</span>
                            </label>
                        </div>
                        <div class="col-sm-8">
                            <select readonly class="cus-sel form-control js-example-basic-single" id="frmBasicEType">
                                <option value="" disabled hidden>Select</option>
                                <?php
                                foreach ($ArrEnquiryType as $item)
                                {
                                    echo '<option value="' . $item . '" ';
                                    echo ($item == $ArrEnquiryInfo[0]->enquirytype) ? 'selected' : '';
                                    echo '>' . $item . '</option>';
                                }
                                ?>
                            </select>
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
                            <?php
                            if (empty($ArrModeType))
                            {
                                die('add mode of enquiry');
                            }
                            else
                            {
                                ?>
                                <select readonly class="cus-sel form-control js-example-basic-single" id="frmBasicMoE" name="frmBasicME">
                                    <option value="" disabled hidden>Select</option>
                                    <?php
                                    foreach ($ArrModeType as $item)
                                    {
                                        echo '<option value="' . $item['id'] . '" ';
                                        echo ($item['id'] == $ArrEnquiryInfo[0]->modeofenquiryid) ? 'selected' : '';
                                        echo '>' . $item['name'] . '</option>';
                                    }
                                    ?>
                                </select>
                                <div class="herr" id="ErrfrmBasicMoE"></div>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-4 px-120 col-form-label text-sm-right pr-0 my-1 py-1">
                            <label for="id-form-field-focus-1" class="mb-0">
                                Brand <span class="mandatory">*</span>
                            </label>
                        </div>
                        <div class="col-sm-8">
                            <select readonly class="cus-sel form-control js-example-basic-single" id="frmBasicBrand" name="frmBasicBrand">
                                <option value="" disabled hidden>Select</option>
                                <?php
                                foreach ($ArrBrand as $item)
                                {
                                    echo '<option value="' . $item['id'] . '" ';
                                    echo ($item['id'] == $ArrEnquiryInfo[0]->brandId) ? 'selected' : '';
                                    echo '>' . $item['brandname'] . '</option>';
                                }
                                ?>
                            </select>
                            <div class="herr" id="ErrBasicBrand"></div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-4 px-120 col-form-label text-sm-right pr-0 my-1 py-1">
                            <label for="id-form-field-focus-1" class="mb-0">
                                Buyer <span class="mandatory">*</span>
                            </label>
                        </div>
                        <div class="col-sm-8">
                            <input readonly type="text" placeholder="Auto Update" id="frmBasicBuyer" name="frmBasicBuyer" class="form-control"
                                   value="<?php echo !empty($ArrEnquiryInfo[0]->buyername)?@$ArrEnquiryInfo[0]->buyername:""; ?>">
                            <!--<select readonly class="cus-sel form-control placeholder-color app-none" id="frmBasicBuyer" disabled="disabled" name="frmBasicBuyer">
                                <option value="" disabled hidden>Auto Populate</option>
                                <?php
                                foreach ($ArrBuyer as $item)
                                {
                                    echo '<option value="' . $item['id'] . '" ';
                                    echo ($item['id'] == $ArrEnquiryInfo[0]->buyerId) ? 'selected' : '';
                                    echo '>' . $item['buyername'] . '</option>';
                                }
                                ?>
                            </select>-->
                            <div class="herr" id="ErrBasicBuyer"></div>
                        </div>
                    </div>

                </div>
                <!-- Second Column -->
                <div class="col-4 pt-4 pb-2">
                    <div class="form-group row">
                        <div class="col-4 px-120 col-form-label text-sm-right pr-0 my-1 py-1">
                            <label for="id-form-field-focus-1" class="mb-0">
                                Country <span class="mandatory">*</span>
                            </label>
                        </div>
                        <div class="col-sm-8">
                            <input readonly type="text" placeholder="Auto Update" id="frmBasicCountry" name="frmBasicCountry" class="form-control"
                                   value="<?php echo !empty($ArrEnquiryInfo[0]->country)?@$ArrEnquiryInfo[0]->country:""; ?>">
                            <!-- <select readonly class="cus-sel form-control js-example-basic-single" id="frmBasicCountry">
                                <option value="" disabled hidden>Select</option>
                                <?php
                                foreach ($ArrCountries as $key => $item)
                                {
                                    echo '<option value="' . $key . '" ';
                                    echo ($key == $ArrEnquiryInfo[0]->countryid) ? 'selected' : '';
                                    echo '>' . $item . '</option>';
                                }
                                ?>
                            </select> -->
                            <div class="herr" id="ErrfrmBasicCountry"></div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-4 px-120 col-form-label text-sm-right pr-0 my-1 py-1">
                            <label for="id-form-field-focus-1" class="mb-0">
                                Total Order Qty. <span class="mandatory">*</span>
                            </label>
                        </div>
                        <div class="col-4 px-120">
                            <input readonly id="frmBasicPqty" name="frmBasicPqty" class="form-control" type="text" placeholder="Free Text" value="<?php echo $ArrEnquiryInfo[0]->exporderqty ?>">
                            <div class="herr" id="ErrfrmBasicPqty"></div>
                        </div>
                        <div class="col-4 px-120">
                            <?php $ArrPcsOrSet = unserialize(ARRPCSSET); ?>
                            <select class="cus-sel form-control js-example-basic-single" id="frmBasicPs">
                                <option value="" disabled hidden>Select</option>
                                <?php
                                foreach ($ArrPcsOrSet as $key => $item)
                                {
                                    echo '<option value="' . $key . '" ';
                                    echo ($key == $ArrEnquiryInfo[0]->pcsorset) ? 'selected' : '';
                                    echo '>' . $item . '</option>';
                                }
                                ?>
                            </select>
                            <div class="herr" id="ErrfrmBasicPs"></div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-4 px-120 col-form-label text-sm-right pr-0 my-1 py-1">
                            <label for="id-form-field-focus-1" class="mb-0">No. of Components <span class="mandatory">*</span></label>
                        </div>
                        <div class="col-sm-8">
                            <input readonly type="text" placeholder="Free Text" id="frmComponents" name="frmComponents" class="form-control"
                                   value="<?php echo isset($ArrEnquiryInfo[0]->totalcomponents) ? $ArrEnquiryInfo[0]->totalcomponents : ''; ?>">
                            <div class="herr" id="ErrfrmfrmComponents"></div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-4 px-120 col-form-label text-sm-right px-0 my-1 py-1">
                            <label for="id-form-field-focus-1" class="mb-0">
                                No. of Combo / Colour <span class="mandatory">*</span>
                            </label>
                        </div>
                        <div class="col-sm-8">
                            <input readonly type="text" placeholder="Free Text" id="frmcombo" name="frmcombo" class="form-control"
                                   value="<?php echo isset($ArrEnquiryInfo[0]->totalcombo) ? $ArrEnquiryInfo[0]->totalcombo : '' ?>">
                            <div class="herr" id="Errfrmfrmcombo"></div>
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
                                <!-- <input type="date" class="form-control" id="frmShipmentDate" name="frmShipmentDate" value="<?php //echo date('d-m-Y', strtotime($ArrEnquiryInfo[0]->shipmentdate)) ?>"> -->
                                <input readonly type="text" class="form-control date" id="frmShipmentDate" name="frmShipmentDate" value="<?php echo $ArrEnquiryInfo[0]->shipmentdate ?>">
                                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                            </div>
                            <div class="herr" id="ErrfrmShipmentDate"></div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-4 px-120 col-form-label text-sm-right pr-0 my-1 py-1">
                            <label for="id-form-field-focus-1" class="mb-0">
                                Price Quoted For <span class="mandatory">*</span>
                            </label>
                        </div>
                        <div class="col-sm-8">
                            <select readonly class="cus-sel form-control js-example-basic-single" id="frmPriceQuotedFor">
                                <option value="" disabled hidden>Select</option>
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
                    <div class="form-group row">
                        <div class="col-4 px-120 col-form-label text-sm-right pr-0 my-1 py-1">
                            <label for="id-form-field-focus-1" class="mb-0">
                                Quoted Price <span class="mandatory">*</span>
                            </label>
                        </div>
                        <div class="col-4 px-120">
                            <input readonly id="frmBasicQprice" name="frmBasicQprice" class="form-control" type="text" placeholder="Free Text"
                                   value="<?php echo $ArrEnquiryInfo[0]->quotedprice ?>" onchange="validateFloatKeyPress(this);">
                            <div id="herr" class="ErrfrmBasicQprice"></div>
                        </div>
                        <div class="col-4 px-120">
                            <select readonly class="cus-sel form-control js-example-basic-single" id="frmBasicCurrency" onchange="setCurrency(this.value)">
                                <option disabled hidden>Select</option>
                                <?php
                                foreach ($ArrCurrency as $key => $item)
                                {
                                    echo '<option value="' . $key . '" ';
                                    echo ($key == $ArrEnquiryInfo[0]->currency) ? 'selected' : '';
                                    echo '>' . $item . '</option>';
                                }
                                ?>
                            </select>
                            <div class="herr" id="ErrfrmBasicCurrency"></div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-4 px-120 col-form-label text-sm-right pr-0 my-1 py-1">
                            <label for="id-form-field-focus-1" class="mb-0">Buyer's Price <span class="mandatory">*</span> </label>
                        </div>
                        <div class="col-4 px-120">
                            <input readonly id="frmBasicBprice" name="frmBasicBprice" class="form-control" type="text" placeholder="Free Text"
                                   value="<?php echo $ArrEnquiryInfo[0]->buyerprice ?>" onchange="validateFloatKeyPress(this);">
                        </div>
                        <div class="col-4 px-120">
                            <select class="cus-sel form-control placeholder-color app-none" id="frmBuyerCurrency" disabled="disabled" readonly>
                                <?php
                                foreach ($ArrCurrency as $key => $item)
                                {
                                    echo '<option value="' . $key . '" ';
                                    echo ($key == $ArrEnquiryInfo[0]->currency) ? 'selected' : '';
                                    echo '>' . $item . '</option>';
                                }
                                ?>
                            </select>
                            <div class="herr" id="ErrfrmBasicCurrency"></div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-4 px-120 col-form-label text-sm-right pr-0 my-1 py-1">
                            <label for="id-form-field-focus-1" class="mb-0">Confirmed Price <span class="mandatory">*</span></label>
                        </div>
                        <div class="col-4 px-120">
                            <input readonly id="frmBasicCprice" name="frmBasicCprice" class="form-control" type="text" placeholder="Free Text"
                                   value="<?php echo $ArrEnquiryInfo[0]->confirmprice ?>" onchange="validateFloatKeyPress(this);">
                            <div class="herr" id="ErrfrmBasicCprice"></div>
                        </div>
                        <div class="col-4 px-120">
                            <select readonly class="cus-sel form-control placeholder-color app-none" id="frmConfirmCurrency" disabled="disabled" readonly>
                                <option>Select</option>
                                <?php
                                foreach ($ArrCurrency as $key => $item)
                                {
                                    echo '<option value="' . $key . '" ';
                                    echo ($key == $ArrEnquiryInfo[0]->currency) ? 'selected' : '';
                                    echo '>' . $item . '</option>';
                                }
                                ?>
                            </select>
                            <div class="herr" id="ErrfrmBasicCurrency"></div>
                        </div>
                    </div>
                </div>

                <!-- Third Column -->
                <div class="col-4 pt-4 pb-2">
                    <div class="form-group row">
                        <div class="col-4 px-120 col-form-label text-sm-right pr-0 my-1 py-1">
                            <label for="id-form-field-focus-1" class="mb-0">
                               Request For  <span class="mandatory">*</span>
                            </label>
                        </div>
                        <div class="col-sm-8">
                            <?php $ArrIsrIor = unserialize(ARRISRIOR); ?>
                            <select class="cus-sel form-control js-example-basic-single requestTypeSelecting" id="frmBasicRType">
                                <option value="" disabled hidden>Select</option>
                                <?php
                                foreach ($ArrIsrIor as $key => $item)
                                {
                                    echo '<option value="' . $key . '" ';
                                    echo ($key == $ArrEnquiryInfo[0]->reqforisrior) ? 'selected' : '';
                                    echo '>' . $item . '</option>';
                                }
                                ?>
                            </select>
                            <div class="herr" id="ErrfrmBasicRType"></div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-4 px-120 col-form-label text-sm-right pr-0 my-1 py-1">
                            <label for="id-form-field-focus-1" class="mb-0">
                                Request Date & Time
                            </label>
                        </div>
                        <div class="col-sm-8">
                            <input readonly type="text" class="form-control" id="frmBasicReqDT" readonly placeholder="Auto Populate" value="<?php echo $ArrEnquiryInfo[0]->reqdatetime ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-4 px-120 col-form-label text-sm-right pr-0 my-1 py-1">
                            <label for="id-form-field-focus-1" class="mb-0">
                                ISR Ref. If Any
                            </label>
                        </div>
                        <div class="col-sm-8">
                            <input readonly type="text" class="form-control" id="frmBasicISRany" placeholder="Auto Populate" value="<?php print_r($ArrEnquiryInfo[0]->isrrefany) ?>">
                        </div>
                    </div>
                    
                    <div class="form-group row">
                        <div class="col-4 px-120 col-form-label text-sm-right pr-0 my-1 py-1">
                            <label for="id-form-field-focus-1" class="mb-0"> Merchant Name </label>
                        </div>
                        <div class="col-sm-8">
                            <input readonly type="text" class="form-control" id="frmmercentname" readonly placeholder="Auto Populate"
                                   value="<?php echo $ArrEnquiryInfo[0]->merchantname ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-4 px-120 col-form-label text-sm-right pr-0 my-1 py-1">
                            <label for="id-form-field-focus-1" class="mb-0">
                                Authorization Status
                            </label>
                        </div>
                        <div class="col-sm-8">
                            <select class="cus-sel form-control placeholder-color app-none" id="frmAuthoriaztionStatus" readonly>
                                <option disabled hidden>Select</option>
                                <option value="" selected=""><?php echo $ArrOrderStatus[$ArrEnquiryInfo[0]->orderstatus] ?></option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-4 px-120 col-form-label text-sm-right pr-0 my-1 py-1">
                            <label for="id-form-field-focus-1" class="mb-0">
                                Authorized By
                            </label>
                        </div>
                        <div class="col-sm-8">
                            <input readonly type="text" id="frmAuthorizedBy" name="frmAuthorizedBy" class="form-control" placeholder="Auto Populate"
                                   value="<?php echo $ArrEnquiryInfo[0]->authorizedby ?>" readonly>
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
                            <input readonly type="text" id="frmAuthorizedDate" name="frmAuthorizedDate" class="form-control" placeholder="Auto Populate"
                                   value="<?php echo $ArrEnquiryInfo[0]->formattedDateAuthorized ?>" readonly>
                            <div class="herr" id="ErrfrmAuthorizedDate"></div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-4 px-120 col-form-label text-sm-right px-0 my-1 py-1">
                            <label for="id-form-field-focus-1" class="mb-0">
                                Management Remarks
                            </label>
                        </div>
                        <div class="col-sm-8">
                            <textarea readonly id="frmManagementRemarks" class="form-control" readonly placeholder="Free Text"><?php echo isset($ArrEnquiryInfo[0]->comments) ? $ArrEnquiryInfo[0]->comments : '' ?></textarea>
                            <div class="herr" id="ErrfrmManagementRemarks"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div>
                <input type="hidden" id="enquiryid" name="enquiryid" value="<?php echo @$VarEnqId; ?>">
            </div>
        </div>
        <div class="col-12 bgc-white pt-3 px-3">
            <div class="row">
                <div class="col-sm-12 col-form-label text-sm-left pr-0">
                    <label for="id-form-field-focus-1" class="mb-0">
                    Merchant Remarks
                    </label>
                </div>
                <div class="col-sm-12">
                    <textarea readonly id="frmBasicMnote" style="height: 76px !important; padding: 20px 22px;border-radius:0.125rem !important
                              " name="frmBasicMnote" class="form-control" placeholder="Free Text"><?php echo isset($ArrEnquiryInfo[0]->merchantnote) ? $ArrEnquiryInfo[0]->merchantnote : '' ?></textarea>
                    <div class="herr" id="ErrfrmBasicMnote"></div>
                </div>
            </div>.
        </div>
        <div class="col-12 bgc-white pt-0 px-3">
            <div class="form-group row">
                <div class="col-12 col-form-label pr-0" id="uploadimglabel">
                    <label for="id-form-field-focus-1" style="" class="mb-0">
                        Attachment
                    </label>
                </div>
                <div class="col-12 px-0">
                    <div class="col-12 px-3" style="">

                        <div class="row attach-fnd">
                        <ul class="upload-list-view mb-0 p-0" style="list-style: none;">
                            <?php
                              $subscriber_id = 'Sub_Id_'.$ArrEnquiryInfo[0]->subscriberid;
                            $enquid_subids = $ArrEnquiryInfo[0]->enquid_subid;

                              $VarFdr = UPLOADS_SLASH . "orderenquiry". DIRECTORY_SEPARATOR . $subscriber_id .DIRECTORY_SEPARATOR . $enquid_subids . DIRECTORY_SEPARATOR;
                            //
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
                                //echo 'No attachments';
                                 echo '<p class="no-attach-fnd">No attachments</p>';
                            }
                            ?>
                        </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
<!--<script src="<?php echo base_url(); ?>assBUDGETED COSTets/plugins/jQuery/jquery-2.2.3.min.js?rn=<?php echo CNFJSCSSRANDNO ?>"></script>-->
<script src="<?php echo base_url(); ?>assets/js/ajax.js?rn=<?php echo CNFJSCSSRANDNO ?>"></script>
<script src="<?php echo base_url(); ?>assets/js/commonfunctions.js?rn=<?php echo CNFJSCSSRANDNO ?>"></script>
<script>
    var GlbEnquiryId = '<?php echo @$VarEnqId ?>';
    function deleteFile(id, enquiry_id, filename)
    {
        if (confirm('Are you sure you want to delete the file')) {
            MakeAsynPostRequest(base_path + "merchant/enqDeleteFile", "&enquiry_id=" + enquiry_id + "&filename=" + filename, "json", function(data) {
            });
            document.getElementById("temp_file_" + id).remove();
        }
    }

    // ************ VALIDATE DECIMAL POINT FOR INPUT FIELDS ********************* //
    function validateFloatKeyPress(el) {
        var v = parseFloat(el.value);
        el.value = (isNaN(v)) ? '' : v.toFixed(2);
    }
</script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/custom/datepair.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>

        $('.date').datepicker({
            'format': 'yyyy-mm-dd',
            'autoclose': true,
            'orientation': "bottom",
        });
        //.datepicker("setDate",'today');

        $('.js-example-basic-single').select2({
            placeholder: "Select",
            disabled:'readonly'
        });
        
        $('b[role="presentation"]').hide();
        $('.select2-selection__arrow').append('<span class="arrow-select2-ji"><span>');
        $(document).ready(function() {
        $('#uploadimglabel').hide();
        });
        
</script>
