<?php
/*
 * @var $ArrEnquiryInfo array
 * @var $ArrEnquiryType array
 * */
$userInfo = fnGetUserLoggedInfo('1');
$userType = $userInfo['usertype'];

?>
<!--<link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>assets/plugins/datepicker/datepicker3.css">-->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<!--<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/uploadfile-order.css"/>-->
<!--    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.10.23/datatables.min.css"/>-->
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css" />
<script>
    $(function() {
        'use strict';

        var body = $('.pin_no');
        // var body = $('body');

        function goToNextInput(e) {
            // console.log('hi')
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

        // $(".pin_no input").keyup(function(){
        //     goToNextInput();
        // });

        body.on('keyup', 'input', goToNextInput);
        body.on('keydown', 'input', onKeyDown);
        body.on('click', 'input', onFocus);

        })
</script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <style>
    .custom-loader {
      margin: 20px auto;
      width: 40px;
      height: 40px;
      border: 4px solid #f3f3f3;
      border-top: 4px solid #3498db;
      border-radius: 50%;
      animation: spin 1s linear infinite;
    }

    @keyframes spin {
      0%   { transform: rotate(0deg); }
      100% { transform: rotate(360deg); }
    }

    .loader-spinner {
  border: 6px solid #f3f3f3;
  border-top: 6px solid #3498db; /* Loader color */
  border-radius: 50%;
  width: 60px;
  height: 60px;
  animation: spin 1s linear infinite;
  margin: auto;
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}
  </style>
<style>
    

    .btn.btn-info{
    font-size:14px!important;
    }
    .btn-green {
    font-size:15px!important;;
    }
    .btn-red {
        font-size:15px!important;
    }
    .swal2-title {
        font-size: 21px!important;
    }
    .no-attach-fnd{
         margin: 0px 0px 0px 0px!important;
    }
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
</style>

<div class="card border-0 bgc-white pt-0" draggable="false" id="">
    <div class="card-header cardhead border-0 p-3 bgc-white  mb-1">
     <div  style="border-bottom: 1px solid #022B61;"></div>
        <div class="card-title text-black f-14 text-600">
            Enquiry Details
        </div>
        <div class="card-tools d-none">
            <div class="dropdown">
                <ul class="nav nav-pills ml-auto">
                </ul>
            </div>
            <a href="#" data-action="expand" class="card-toolbar-btn text-white d-style" draggable="false">
                <i class="fa fa-expand d-n-active pr-3"></i>
                <i class="fa fa-compress d-active pr-3"></i>
            </a>
            <a href="#" data-action="reload" class="card-toolbar-btn text-white" draggable="false">
                <i class="fas fa-sync-alt pr-3"></i>
            </a>
            <a href="#" data-action="toggle" class="card-toolbar-btn text-white" draggable="false">
                <i class="fa fa-chevron-up pr-3"></i>
            </a>
        </div>
    </div>
    <div class="card-body border-0 p-0 m-0 collapse show">
        <div class="col-12 p-0 m-0" style="background-color: #f7f7f7">
            <div class="row border-0 p-0 m-0 no-rad-form" id="enquiry_view_form">
              
                <input type="hidden" value="<?php echo $ArrEnquiryInfo[0]->id ?>" id="enquiryFormId" />
                  <input type="hidden" value="<?php echo $ArrEnquiryInfo[0]->enquid_subid ?>" id="enqui_subid" />
                  <input type="hidden" value="<?php echo $ArrEnquiryInfo[0]->subscriberid ?>" id="enqui_subid" />
                <!-- First Column -->
                <div class="col-4 pt-4 pb-2">
                    <div class="form-group row">
                        <div class="col-4 px-120 col-form-label text-sm-right pr-0 my-1 py-1">
                            <label for="id-form-field-focus-1" class="mb-0">
                                Order / Enq. Ref. No <span class="mandatory">*</span>
                            </label>
                        </div>
                        <div class="col-sm-8">
                            <input type="text" placeholder="Free Text" class="form-control" id="frmOrderEnqRefNo" value="<?= !empty($ArrEnquiryInfo[0]->orderenqrefno) ? $ArrEnquiryInfo[0]->orderenqrefno : '' ?>">
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
                            <input type="text" id="frmBasicEnqDate" name="frmBasicEnquiryDate" class="form-control date" 
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
                            <input type="text" placeholder="Free Text" id="frmBasicStyleRefNo" name="frmBasicStyleRefNo" class="form-control"
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
                            <textarea class="my-0 py-2 mb-2 form-control" id="frmBasicStyleDesc" style="height: 85px !important;" name="frmBasicStyleDesc"  placeholder="Free Text"><?php echo @$ArrEnquiryInfo[0]->styledesc ?></textarea>
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
                            <select class="cus-sel form-control js-example-basic-single" id="frmBasicEType">
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
                                die('Add Mode of Enquiry');
                            }
                            else
                            {
                                ?>
                                <select class="cus-sel form-control js-example-basic-single" id="frmBasicMoE" name="frmBasicME">
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
                            <select class="cus-sel form-control js-example-basic-single" id="frmBasicBrand" name="frmBasicBrand">
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
                            <input type="text" placeholder="Auto Update" id="frmBasicBuyer" name="frmBasicBuyer" class="form-control" value="<?php echo !empty($ArrEnquiryInfo[0]->buyername)? @$ArrEnquiryInfo[0]->buyername:"" ; ?>">
                            <!-- <select class="cus-sel form-control placeholder-color app-none" id="frmBasicBuyer" disabled="disabled" name="frmBasicBuyer">
                                <option value="" disabled hidden>Auto Update</option>
                                <?php
                                foreach ($ArrBuyer as $item)
                                {
                                    echo '<option value="' . $item['id'] . '" ';
                                    echo ($item['id'] == $ArrEnquiryInfo[0]->buyerId) ? 'selected' : '';
                                    echo '>' . $item['buyername'] . '</option>';
                                }
                                ?>-->
                            </select>
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
                            <input type="text" placeholder="Auto Update" id="frmBasicCountry" name="frmBasicCountry" class="form-control" value="<?php echo !empty($ArrEnquiryInfo[0]->country)? @$ArrEnquiryInfo[0]->country:"" ; ?>">
                            <!--<select class="cus-sel form-control js-example-basic-single" id="frmBasicCountry" disabled="disabled" >
                                <option value="" selected disabled hidden>Auto Update</option>
                                <!--<option value="" disabled hidden>Select</option>
                                <?php
                                foreach ($ArrCountries as $key => $item)
                                {
                                    echo '<option value="' . $key . '" ';
                                    echo ($key == $ArrEnquiryInfo[0]->countryid) ? 'selected' : '';
                                    echo '>' . $item . '</option>';
                                }
                                ?>
                            </select>-->
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
                            <input id="frmBasicPqty" name="frmBasicPqty" class="form-control" type="text" onkeypress="return onlyNumbernodecimal(event);" placeholder="Free Text" value="<?php echo $ArrEnquiryInfo[0]->exporderqty ?>">
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
                            <label for="id-form-field-focus-1" class="mb-0"> No. of Components <span class="mandatory">*</span> </label>
                        </div>
                        <div class="col-sm-8">
                            <input type="text" placeholder="Free Text" onkeypress="return onlyNumbernodecimal(event);"  id="frmComponents" name="frmComponents" class="form-control"
                                   value="<?php echo isset($ArrEnquiryInfo[0]->totalcomponents) ? $ArrEnquiryInfo[0]->totalcomponents : ''; ?>">
                            <div class="herr" id="ErrfrmfrmComponents"></div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-4 px-120 col-form-label text-sm-right px-0 my-1 py-1">
                            <label for="id-form-field-focus-1" class="mb-0" style="color:#022b61!important;">
                                No. of Combo / Colour <span class="mandatory">*</span>
                            </label>
                        </div>
                        <div class="col-sm-8">
                            <input type="text" placeholder="Free Text" id="frmcombo" onkeypress="return onlyNumbernodecimal(event);"  name="frmcombo" class="form-control"
                                   value="<?php echo isset($ArrEnquiryInfo[0]->totalcombo) ? $ArrEnquiryInfo[0]->totalcombo : '' ?>">
                            <div class="herr" id="Errfrmfrmcombo"></div>
                        </div>
                    </div>
                    
                    <div class="form-group row">
                        <div class="col-4 px-120 col-form-label text-sm-right px-0 my-1 py-1">
                            <label for="id-form-field-focus-1" class="mb-0" style="color:#022b61!important;">
                                Shipment / Submission Date <span class="mandatory">*</span>
                            </label>
                        </div>
                        <div class="col-sm-8">
                            <div class="input-group date">
                                <!-- <input type="date" class="form-control" id="frmShipmentDate" name="frmShipmentDate" value="<?php //echo date('d-m-Y', strtotime($ArrEnquiryInfo[0]->shipmentdate)) ?>"> -->
                                <input type="text" class="form-control date" id="frmShipmentDate" name="frmShipmentDate" value="<?php echo $ArrEnquiryInfo[0]->shipmentdate ?>">
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
                            <select class="cus-sel form-control js-example-basic-single" id="frmPriceQuotedFor">
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
                            <input id="frmBasicQprice" name="frmBasicQprice" class="form-control" type="text" placeholder="Free Text"
                                   value="<?php echo $ArrEnquiryInfo[0]->quotedprice ?>" onkeypress="return isNumber_or_isDecimal_Key(event)">
                            <div id="herr" class="ErrfrmBasicQprice"></div>
                        </div>
                        <div class="col-4 px-120">
                            <select class="cus-sel form-control js-example-basic-single" id="frmBasicCurrency" onchange="setCurrency(this.value)">
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
                            <label for="id-form-field-focus-1" class="mb-0">Buyer's Price <span class="mandatory">*</span></label>
                        </div>
                        <div class="col-4 px-120">
                            <input id="frmBasicBprice" name="frmBasicBprice" class="form-control" type="text" placeholder="Free Text"
                                   value="<?php echo $ArrEnquiryInfo[0]->buyerprice ?>" onkeypress="return isNumber_or_isDecimal_Key(event)">
                        </div>
                        <div class="col-4 px-120">
                            <select class="cus-sel form-control placeholder-color app-none" id="frmBuyerCurrency" disabled="disabled" >
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
                            <input id="frmBasicCprice" name="frmBasicCprice" class="form-control" type="text" placeholder="Free Text"
                                   value="<?php echo $ArrEnquiryInfo[0]->confirmprice ?>" onkeypress="return isNumber_or_isDecimal_Key(event)">
                            <div class="herr" id="ErrfrmBasicCprice"></div>
                        </div>
                        <div class="col-4 px-120">
                            <select class="cus-sel form-control placeholder-color app-none" id="frmConfirmCurrency" disabled="disabled" >
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
                                Request For <span class="mandatory">*</span>
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
                            <label for="id-form-field-focus-1" class="mb-0" style="color:#022b61!important;">
                                Request Date & Time
                            </label>
                        </div>
                        <div class="col-sm-8">
                            <!-- commented by me regards reqdate and datime <input type="text" class="form-control" id="frmBasicReqDT" readonly placeholder="Auto Update" value="<?php echo $ArrEnquiryInfo[0]->formattedDateCreated ?>">-->
                            <input type="text" class="form-control" id="frmBasicReqDT" readonly placeholder="Auto Update" value="<?php echo ((!empty($ArrEnquiryInfo[0]->reqdatetime) && $ArrEnquiryInfo[0]->reqdatetime!='0000-00-00 00:00:00')? $ArrEnquiryInfo[0]->reqdatetime:''); ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-4 px-120 col-form-label text-sm-right pr-0 my-1 py-1">
                            <label for="id-form-field-focus-1" class="mb-0">
                                ISR Ref. If Any
                            </label>
                        </div>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="frmBasicISRany" placeholder="Auto Update" value="<?php print_r($ArrEnquiryInfo[0]->isrrefany) ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-4 px-120 col-form-label text-sm-right pr-0 my-1 py-1">
                            <label for="id-form-field-focus-1" class="mb-0" style="color:#022b61!important;"> Merchant Name </label>
                        </div>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="frmmercentname" readonly placeholder="Auto Update"
                                   value="<?php echo $ArrEnquiryInfo[0]->merchantname ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-4 px-120 col-form-label text-sm-right pr-0 my-1 py-1">
                            <label for="id-form-field-focus-1" class="mb-0" style="color:#022b61!important;">
                                Authorization Status
                            </label>
                        </div>
                        <div class="col-sm-8">
                            <select class="cus-sel form-control placeholder-color app-none" id="frmAuthoriaztionStatus" readonly>
                                <option disabled hidden>Select</option>
                                <option value="" selected=""><?php echo $ArrOrderStatus[$ArrEnquiryInfo[0]->orderstatus]; ?></option>
                                <input type="hidden" id="frmBasicOrderStatus" value="<?php echo $ArrOrderStatus[$ArrEnquiryInfo[0]->orderstatus] ?>" />
                                <input type="hidden" id="order_status" value="<?php echo $ArrEnquiryInfo[0]->orderstatus; ?>" />
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-4 px-120 col-form-label text-sm-right pr-0 my-1 py-1">
                            <label for="id-form-field-focus-1" class="mb-0" style="color:#022b61!important;">
                                Authorized By
                            </label>
                        </div>
                        <div class="col-sm-8">
                            <input type="text" id="frmAuthorizedBy" name="frmAuthorizedBy" class="form-control" placeholder="Auto Update"
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
                            <input type="text" id="frmAuthorizedDate" name="frmAuthorizedDate" class="form-control" placeholder="Auto Update"
                                   value="<?php echo ($ArrEnquiryInfo[0]->formattedDateAuthorized!='00-00-0000 00:00:00') ? $ArrEnquiryInfo[0]->formattedDateAuthorized :'' ?>" readonly>
                            <div class="herr" id="ErrfrmAuthorizedDate"></div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-4 px-120 col-form-label text-sm-right px-0 my-1 py-1">
                            <label for="id-form-field-focus-1" class="mb-0" style="color:#022b61!important;">
                                Management Remarks
                            </label>
                        </div>
                        <div class="col-sm-8">
                            <textarea id="frmManagementRemarks" class="form-control" readonly placeholder="Auto Update"><?php echo isset($ArrEnquiryInfo[0]->comments) ? $ArrEnquiryInfo[0]->comments : '' ?></textarea>
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
                    <textarea id="frmBasicMnote" style="height: 76px !important; padding: 20px 22px;border-radius:0.125rem !important
                              " name="frmBasicMnote"  disabled class="form-control" placeholder="Free Text"><?php echo (isset($ArrEnquiryInfo[0]->merchantnote) && $ArrEnquiryInfo[0]->merchantnote!='undefined') ? $ArrEnquiryInfo[0]->merchantnote : '' ?></textarea>
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
                  <?php if($ArrEnquiryInfo[0]->orderstatus==0 || ($ArrEnquiryInfo[0]->orderstatus==3 && $userType!=2)) { ?>
                <div class="col-sm-12" id="uploadimg">
                    <div id="uploadBusinessImg" class="pdt10" style="width: 100%;"></div>
                    <div class="herr" id="Errupload-list-view"></div>
                     <input type="hidden" id="uploadedfile" value="1">
                </div>
                <?php } ?>
                <div class="col-12 px-0">
                    <div class="col-12 px-3" style="">
                        <div class="row ajax-file-upload-container attach-fnd">
                        <ul class="upload-list-view mb-0 p-0" style="list-style: none;">
                            <?php
                              
                                $subscriber_id = 'Sub_Id_'.$ArrEnquiryInfo[0]->subscriberid;
                                $enquid_subids = $ArrEnquiryInfo[0]->enquid_subid;

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
                                            <li class="file-viwer-jig" id="temp_list_<?php echo $i ?>">
                                                <div style="padding: 10px 0;" id="temp_file_<?php echo $i ?>">
                                                <div class="ajax-file-upload-filename">
                                                    <?php
                                                    $VarFileExt = pathinfo($file, PATHINFO_EXTENSION);
                                                    echo $file . ' ';
                                                   
                                                    ?>
                                                     <script>document.getElementById("uploadedfile").value=2;</script>
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
                                                    <?php if($ArrEnquiryInfo[0]->orderstatus==0 || ($ArrEnquiryInfo[0]->orderstatus==3 && $userType!=2)) { ?>
                                                    <a href="javascript:void(0);" title="Delete" onclick="deleteFile('<?php echo $i ?>', '<?php echo $enquiry_id ?>', '<?php echo urlencode($file) ?>')">
                                                        <i class="fa fa-trash fa-lg" aria-hidden="true"></i>
                                                    </a>
                                                    <?php } ?>
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
                                // echo '<p class="no-attach-fnd">No attachments</p>';
                            }
                            ?>
                        </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 text-right p-3">
            <?php if($ArrEnquiryInfo[0]->orderstatus==0 || ($ArrEnquiryInfo[0]->orderstatus==3 && $userType!=2)) { ?>
            <button class="btn btn-sm btn-royal-blue" id="submitAuthRequest" >Auth. Request</button> &nbsp;&nbsp;&nbsp;
            <button class="btn btn-sm btn-royal-blue-submit" id="fnSaveEnquiry" disabled onclick="return fnSaveEnquiry();" >Save</button>
            <?php } ?>
            
    </div>
</div>

<!--- Modal Popup --->
<div class="modal fade" data-backdrop="static" data-keyboard="false" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header" style="display: block;">
                <h4 class="modal-title" id="myModalLabel">Enter PIN</h4>
            </div>
            <div class="modal-body text-center">
                <form class="form-horizontal col-md-12 mb-0" method="post" id="frmPinformId">
                    <div id="divOuter">
                        <div id="divInner" class="otp-input pin_no">
                            <!-- <input id="frmPin" type="password" maxlength="4" /> -->
                            <input type="text" class="tborder" id="numberone" maxlength="1" size="1" min="0" max="9" pattern="[0-9]{1}" />
                            <input type="text" class="tborder" id="numbertwo" maxlength="1" size="1" min="0" max="9" pattern="[0-9]{1}" />
                            <input type="text" class="tborder" id="numberthree" maxlength="1" size="1" min="0" max="9" pattern="[0-9]{1}" />
                            <input type="text" class="tborder" id="numberfour" maxlength="1" size="1" min="0" max="9" pattern="[0-9]{1}" />
                             
                            <!-- <div class="herr pull-left" id="ErrfrmPin"></div> -->
                             <div class="herr " style="padding-top:10px;" id="ErrfrmPin"></div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <!--<button type="button" class="btn btn-sm btn-blue-submit btn-danger" data-dismiss="modal">Close</button>-->
                <!--<button type="submit" class="btn btn-sm btn-royal-blue-submit" id="submitRequest">Continue</button>-->
                <button type="button" class="btn btn-sm btn-danger" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-sm btn-success" id="submitRequest">Continue</button>
            </div>
        </div>
    </div>
</div>



<script>
    var GlbEnquiryId = '<?php echo @$VarEnqId ?>';
    var GlbIsrIor = '<?php echo @$VarIsrIorId ?>';
    

    // ************ VALIDATE DECIMAL POINT FOR INPUT FIELDS ********************* //
    function validateFloatKeyPress(el) {
        var v = parseFloat(el.value);
        el.value = (isNaN(v)) ? '' : v.toFixed(2);
    }
    
    function setCurrency(thisvalue) {
        document.getElementById("frmBuyerCurrency").value = thisvalue;
        document.getElementById("frmConfirmCurrency").value = thisvalue;
    }
</script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/custom/datepair.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>

       var mnote="<?php echo (isset($ArrEnquiryInfo[0]->merchantnote) && ($ArrEnquiryInfo[0]->merchantnote!='undefined')) ? $ArrEnquiryInfo[0]->merchantnote : '' ?>";
       var attachments=document.getElementById('uploadedfile').value;
       var order_Status= $("#order_status").val();
       var quotedprice= $("#frmBasicQprice").val(); console.log('q'+quotedprice);
       var currency= $("#frmBasicCurrency").val(); console.log('cy'+currency);
       var buyerprice= $("#frmBasicBprice").val(); console.log('bp'+buyerprice);
       var confirmprice= $("#frmBasicCprice").val();console.log('cp'+confirmprice);
       if(mnote!='' && attachments==2 && (order_Status==0 || order_Status==3) && (quotedprice!='0.00') && (currency!='0') && (buyerprice!='0.00') && (confirmprice!='0.00')){
           $("#submitAuthRequest").removeAttr("disabled");
       }else{
            $("#submitAuthRequest").attr( "disabled", "disabled" );
       }
      
        $('.date').datepicker({
            // 'format': 'yyyy-mm-dd',
            'format': 'dd-mm-yyyy',
            'autoclose': true,
            'orientation': "bottom"
        });
        //.datepicker("setDate",'today');

        $('.js-example-basic-single').select2({
            placeholder: "Select"
        });
        
        $('b[role="presentation"]').hide();
        $('.select2-selection__arrow').append('<span class="arrow-select2-ji"><span>');
         $("#enquiry_view_form input").prop("disabled", true);
        $("#enquiry_view_form select").prop("disabled", true);
        $("#enquiry_view_form textarea").prop("disabled", true);
        $(document).ready(function() {
        // $('#uploadimg').hide();
        // $('#uploadimglabel').hide();
        });
        
</script>
