<?php 
    $requestData = $requestData[0];
?>

<?php $this->load->view(CNFCOMPANY.'template/pageheader');?>
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/css/datatables.min.css">
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/css/bootstrap-datepicker.min.css" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/css/select2.min.css" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/css/uploadfile-order.css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.css"  />
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/css/wip.css" />

<link rel="stylesheet" href="<?php echo base_url();?>assets/plugins/date-time/css/bootstrap-datetimepicker.min.css">

<!-- *********************** JEXCEL CSS LOADS HERE ************************-->
<link rel="stylesheet" type="text/css" href="<?= base_url() ?>assets/css/jexcelNew/jspreadsheet.css" />
<link rel="stylesheet" type="text/css" href="<?= base_url() ?>assets/css/jexcelNew/jsuites.css" />

<!-- *********************** JEXCEL SCRIPTS LOADS HERE ************************-->
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/ajax.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/vue.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/jexcelNew/jexcel.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/jexcelNew/jsuites.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/jexcel/numeral.min.js"></script>

<!-- *************** CUSTOM STYLE HERE ********* -->
<style>
table.table.tbl-procs-border {
    margin-bottom: 0;
}
.text-left {
    text-align: left!important;
}
.type-align {
    border: 1px solid #f4f4f4;
    padding-top: 15px;
    margin-left: 23px;
}

</style>

<body class="hold-transition layout-top-nav">
    <div class="wrapper">
        <?php $this->load->view(CNFCOMPANY . 'template/templateheader'); ?>
        <div class="content px-4">
            <!-- *********************** ORDER PROCESSING START HERE ************************-->
            <?php $this->load->view(CNFREQUEST . 'request_orderprocessing.php'); ?>
            <!-- *********************** ORDER PROCESSING ENDS HERE ************************-->

            
            <div class="row">
                <p><span style="padding-left:30px; width: 500px;">Select any one:</span> <span style="padding-left:565px;text-align: right;">Select any one:</span> </p>
                <div class="type-align col-sm-4">
                <div class="col-sm-4">
                    <div class="form-group row">
                        <div class="col-sm-12 dis-flx-cen">
                            <input type="radio" id="within" name="mode" value="within" class="cus-radio-btn" >
                            <label for="within" class="mb-0 f-14" style="padding-top:2px;"> WITHIN STATE </label>
                        </div>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group row">
                        <div class="col-sm-12 dis-flx-cen">
                            <input type="radio" id="inter" name="mode" value="inter" class="cus-radio-btn">
                            <label for="inter" class="mb-0 f-14" style="padding-top:2px;"> INTER STATE </label>
                        </div>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group row">
                        <div class="col-sm-12 dis-flx-cen">
                            <input type="radio" id="imports" name="mode" value="imports" class="cus-radio-btn">
                            <label for="imports" class="mb-0 f-14" style="padding-top:2px;"> IMPORTS </label>
                        </div>
                    </div>
                </div>
                </div>

                
                <div class="type-align col-sm-3">
                    <div class="col-sm-6">
                    <div class="form-group row">
                        <div class="col-sm-12 dis-flx-cen">
                            <input type="radio" id="new_purchase" name="p_type" value="new_purchase" class="cus-radio-btn">
                            <label for="new_purchase" class="mb-0 f-14" style="padding-top:2px;"> NEW PURCHASE </label>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group row">
                        <div class="col-sm-12 dis-flx-cen">
                            <input type="radio" id="surplus" name="p_type" value="surplus" class="cus-radio-btn">
                            <label for="surplus" class="mb-0 f-14" style="padding-top:2px;"> SURPLUS STOCK </label>
                        </div>
                    </div>
                </div>
                </div>
            </div>

            <!-- <div class="row px-4 mb-4 mt-5 " >
               <div class="row type-align" >
                <div class="col-sm-2">
                    <div class="form-group row">
                        <div class="col-sm-12 dis-flx-cen">
                            <input type="radio" id="within" name="mode" value="within" class="cus-radio-btn" >
                            <label for="within" class="mb-0 f-14"> WITHIN STATE </label>
                        </div>
                    </div>
                </div>
                <div class="col-sm-2">
                    <div class="form-group row">
                        <div class="col-sm-12 dis-flx-cen">
                            <input type="radio" id="inter" name="mode" value="inter" class="cus-radio-btn">
                            <label for="inter" class="mb-0 f-14"> INTER STATE </label>
                        </div>
                    </div>
                </div>
                <div class="col-sm-2">
                    <div class="form-group row">
                        <div class="col-sm-12 dis-flx-cen">
                            <input type="radio" id="imports" name="mode" value="imports" class="cus-radio-btn">
                            <label for="imports" class="mb-0 f-14"> IMPORTS </label>
                        </div>
                    </div>
                </div>
                </div>

                <div class="col-sm-2">
                    <div class="form-group row">
                        <div class="col-sm-12 dis-flx-cen">
                            <input type="radio" id="new_purchase" name="p_type" value="new_purchase" class="cus-radio-btn">
                            <label for="new_purchase" class="mb-0 f-14"> NEW PURCHASE </label>
                        </div>
                    </div>
                </div>
                <div class="col-sm-2">
                    <div class="form-group row">
                        <div class="col-sm-12 dis-flx-cen">
                            <input type="radio" id="surplus" name="p_type" value="surplus" class="cus-radio-btn">
                            <label for="surplus" class="mb-0 f-14"> SURPLUS STOCK </label>
                        </div>
                    </div>
                </div>

            </div> -->

<?php //print_r($piData); exit; ?>
            <input type="hidden" id="purchase_indent_id" class="w100-input input-bb-sty" value="<?php echo @$piData[0]['purchase_indent_id']; ?>">
            <section class="content-header" style="padding-top: 0">
                <!-- <div class="card-header p-3 border-0 bgc-white tbl-head mb-1">
                    <div class="card-title text-white f-14 text-500">BOM (Art - 1) PURCHASE INDENT -</div>
                    <p class="card-title text-white f-14 text-500 mb-0 sub-tbl-head"><b id="mode"></b></p>
                </div> -->
                <div class="card-header pb-3 bgc-white border-0 " style="padding-top: 35px;">
                    <div class="card-title f-20">
                        <b style="font-size: 20px !important; padding-left: 5px; margin-left: 3px;color: #333">BOM  PURCHASE INDENT</b>
                    </div>
                </div>
                <div class="col-12 pb-3 px-0">
                    <div class="col-12 px-0" style="border-top: 1px solid #022b61;"></div>
                </div>
                <div class="order-processing">
                    <table id="" class="table">
                        <tbody>
                            <tr>
                                <td class="ord-procs-cell">
                                    <table class="table tbl-procs-border">
                                    <tbody>
                                        <tr>
                                            <td class="process-main-head text-left" colspan="4">
                                                <strong>From</strong>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="process-title">Name : </td>
                                            <td class="process-value" colspan="3">
                                            <?php echo @$ArrCommonHeaderData['companyName']; ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="process-title">Address : </td>
                                            <td class="process-value" colspan="3" style="height:68px;">
                                            <?php echo @$ArrCommonHeaderData['companyAddress']; ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="process-title">Contact No : </td>
                                            <td class="process-value" colspan="3">
                                            <?php echo @$ArrCommonHeaderData['companyMobile']; ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="process-title">Email ID : </td>
                                            <td class="process-value" colspan="3">
                                            <?php echo @$ArrCommonHeaderData['companyEmail']; ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="process-title">GST No. : </td>
                                            <td class="process-value" colspan="3">
                                            
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="process-title">IE Code : </td>
                                            <td class="process-value" colspan="3">
                                            
                                            </td>
                                        </tr>
                                    </tbody>
                                    </table>
                                </td>
                                
                                <td class="ord-procs-cell to">
                                    <table class="table tbl-procs-border">
                                        <tbody>
                                            <tr>
                                                <td class="process-main-head text-left" colspan="4">
                                                    <strong>To</strong>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="process-title">Name : </td>
                                                <td class="pad-0" colspan="3">
                                                    <select class="form-control bor-0" id="vendorOption">
                                                    </select>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="process-title">Address : </td>
                                                <td class="process-value" colspan="3" id="vendorAddress" style="height:68px;">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="process-title">Contact No : </td>
                                                <td class="process-value" colspan="3" id="vendorContact">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="process-title">Email ID : </td>
                                                <td class="process-value" colspan="3" id="vendorEmail">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="process-title">GST No. : </td>
                                                <td class="process-value" colspan="3" id="vendorGst">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="process-title">IE Code : </td>
                                                <td class="process-value" colspan="3" id="vendorIeCode">
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>

                                <td class="ord-procs-cell surplus">
                                    <table class="table tbl-procs-border">
                                        <tbody>
                                            <tr>
                                                <td class="process-main-head text-left" colspan="4">
                                                    <strong>TO</strong>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="process-title">Name : </td>
                                                <td>BOM - SURPLUS STOCK</td>
                                            </tr>
                                            <tr>
                                                <td class="process-title">Address : </td>
                                                <td class="process-value" colspan="3" id="vendorAddress" style="height:68px;">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="process-title">Contact No : </td>
                                                <td class="process-value" colspan="3" id="vendorContact">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="process-title">Email ID : </td>
                                                <td class="process-value" colspan="3" id="vendorEmail">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="process-title">GST No. : </td>
                                                <td class="process-value" colspan="3" id="vendorGst">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="process-title">IE Code : </td>
                                                <td class="process-value" colspan="3" id="vendorIeCode">
                                                </td>
                                            </tr>
                                            
                                        </tbody>
                                    </table>
                                </td>
                                
                                <td class="ord-procs-cell">
                                    <table class="table tbl-procs-border">
                                    <tbody>
                                        <tr>
                                            <td class="process-main-head" colspan="4">
                                                <strong>PURCHASE REFERENCE</strong>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="process-title">P.I. REF.NO:</td>
                                            <td class="process-value" colspan="3">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="process-title">DATE & TIME:</td>
                                            <td class="process-value" colspan="3">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="process-title">PURCHASE TYPE:</td>
                                            <td class="process-value pad-0">
                                                <input class="inp-full-wd" id="purchase_type" type="text" value="<?php echo @$piData[0]['purchase_type']; ?>"  readonly autocomplete="off" />
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="process-title">EXP. DATE OF DELIVERY:</td>
                                            <td class="process-value pad-0">
                                                <?php if(@$piData[0]['exp_dod'] != '') { ?>
                                                    <input class="inp-full-wd date" id="exp_dod" type="text" value="<?php echo @$piData[0]['exp_dod']; ?>" placeholder="Free Text" />
                                                <?php } else { ?>
                                                    <input class="inp-full-wd date" id="exp_dod" type="text" value="" placeholder="Free Text" />
                                                <?php }  ?>
                                                
                                            </td>
                                        </tr>

                                        <tr>
                                            <td class="process-main-head" colspan="4">
                                                <strong>INTERNAL REFERENCE</strong>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="process-title">WIP NO:</td>
                                            <td class="process-value" colspan="3">
                                            <?php echo $ArrCommonHeaderData['ArrEnquiryDetails']['isriorcode']; ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="process-title">QUEUE NO:</td>
                                            <td class="process-value" colspan="3">
                                            <?php echo $requestData['ref_queue_no']; ?>
                                            </td>
                                        </tr>
                                    </tbody>
                                    </table>
                                </td>

                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="card border-0 mb-5">
                    <div id="withinStateDetails"></div>
                    <div id="interStateDetails"></div>
                    <div id="importsStateDetails"></div>
                </div>

            </section>

            

            <section style="margin-bottom: 20px;">
                <div class="card border-0">
                    <div class="d-flex align-flex-center">
                        <div class="">
                            <h5>Amount in words&nbsp;&nbsp;: </h5>
                        </div>
                        <div class="mar-l-10">
                            <input type="text" id="amount_in_words" class="w100-input input-bb-sty" value="<?php echo @$piData[0]['amount_in_words']; ?>">
                        </div>
                    </div>
                    <div class="d-flex align-flex-center">
                        <div class="">
                            <h5>Payment Terms &nbsp;&nbsp;&nbsp;&nbsp;:</h5>
                        </div>
                        <div class="mar-l-10">
                            <input type="text" id="payment_terms" class="w100-input input-bb-sty" value="<?php echo @$piData[0]['payment_terms']; ?>">
                        </div>
                    </div>
                    <div class="d-flex">
                        <div class="col-md-12">
                            <h5 class="text-right">
                                <p><span style="font-size:11px;">For</span> <?php echo $ArrCommonHeaderData['companyName']; ?></p>
                            </h5>
                        </div>
                    </div>
                    <div class="d-flex">
                        <div class="col-md-3">
                            <div class="h-70" id="dcNoHere">
                                <p class="dc-name"><?php echo $requestData['req_name']; ?></p>
                            </div>
                            <label class="control-label">Request Raised By:</label>
                            <br/>
                        </div>
                        <div class="col-md-3">
                            <div class="h-70" id="dcNoHere">
                                <p class="dc-name"><?php echo $requestData['auth_name']; ?></p>
                            </div>
                            <label class="control-label">Request Authorized By:</label>
                            <br/>
                        </div>
                        <div class="col-md-3">
                            <div class="h-70" id="dcNoHere">
                                <p class="dc-name"></p>
                            </div>
                            <label class="control-label">P.I. Prepared By:</label>
                            <br/>
                        </div>
                        <div class="col-md-3">
                            <div class="h-70" id="dcNoHere">
                                <p class="dc-name"></p>
                            </div>
                            <label class="control-label">P.I. Approved By:</label>
                            <br/>
                        </div>
                        <div class="col-md-3 text-right">
                            <div class="h-70" id="dcNoHere">
                                <p class="dc-name bom-p-r"></p>
                            </div>
                            <label class="control-label">Authorized Signatory.</label>
                            <br/>
                        </div>
                    </div>
                    <!-- <table class="table tbl-procs-border" style="margin-top: 20px;">
                        <tbody>
                            <tr>
                                <td class="process-title" style="width: 8% !important;">Request <br>Raised By:</td>
                                <td class="pad-0" style="width: 10%;"></td>
                                <td class="process-title" style="width: 8% !important;">Request <br>Authorized By:</td>
                                <td class="pad-0" style="width: 10%;"></td>
                                <td class="process-title" style="width: 8% !important;">P.I. <br>Prepared By:</td>
                                <td class="pad-0" style="width: 10%;"></td>
                                <td class="process-title" style="width: 8% !important;">P.I. <br>Approved By:</td>
                                <td class="pad-0" style="width: 10%;"></td>
                                <td class="process-title" style="width: 8% !important;">Authorized Signatory</td>
                                <td class="pad-0" style="width: 10%;"></td>
                            </tr>
                        </tbody>
                    </table> -->
                </div>
            </section>

            <div class="card border-0">
                <div class="card-header p-3 border-0 bgc-white tbl-head mb-1">
                    <div class="card-title text-white f-14 text-500">PAYMENT REQUEST - ADVANCE</div>
                </div>
                <div id="paymentRequest"></div>
            </div>

            <!-- <div class="card border-0">
                <div class="card-header p-3 border-0 bgc-white tbl-head mb-1 mar-t-3">
                    <div class="card-title text-white f-14 text-500">PAYMENT REQUEST - BILL / INVOICE</div>
                </div>
                <div id="paymentRequestBill"></div>
            </div> -->
            
            <!-- <div class="card border-0 mar-t-3">
                <div class="card-header p-3 border-0 bgc-white tbl-head mb-1">
                    <div class="card-title text-white f-14 text-500">PAYMENT PAID DETAILS</div>
                </div>
                <div id="paymentPaidDetails"></div>
            </div> -->
            
            <div class="card border-0 mar-t-3">
                <div class="card-header p-3 border-0 bgc-white tbl-head mb-1">
                    <div class="card-title text-white f-14 text-500">REQUEST & PAYMENT LOG</div>
                </div>
                <div id="requestPaymentLog"></div>
            </div>

            <form class="row no-rad-form add-form-mar mar-t-3" id="reqOrderProcessingForm">
                <!-- <div class="row">
                    First Column
                    <div class="col-sm-4">
                        <div class="form-group row">
                            <div class="col-sm-4 col-form-label text-sm-right pr-0">
                                <label for="id-form-field-focus-1" class="mb-0">
                                    Merchant Note
                                </label>
                            </div>
                            <div class="col-sm-8">
                                <select class="cus-sel form-control js-example-basic-single mgmt" id="req_type" name="req_type" readonly>
                                    <option value="" disabled hidden>Select</option>
                                    <option value="REGULAR" <?php if($requestData['req_type']=="REGULAR") echo "selected=\"selected\""; ?> >REGULAR</option>
                                    <option value="PRIORITY" <?php if($requestData['req_type']=="PRIORITY") echo "selected=\"selected\""; ?> >PRIORITY</option>
                                    <option value="HIGH PRIORITY" <?php if($requestData['req_type']=="HIGH PRIORITY") echo "selected=\"selected\""; ?> >HIGH PRIORITY</option>
                                    <option value="IMMEDIATE" <?php if($requestData['req_type']=="IMMEDIATE") echo "selected=\"selected\""; ?> >IMMEDIATE</option>
                                </select>
                                <div class="herr" id="err_req_type"></div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-4 col-form-label text-sm-right pr-0">
                                <label for="id-form-field-focus-1" class="mb-0">
                                    Authorization Type
                                </label>
                            </div>
                            <div class="col-sm-8">
                                <input type="text" class="form-control mgmt" readonly value="<?php echo $requestData["req_date"]?>" placeholder="Auto Update">
                            </div>

                        </div>
                        <div class="form-group row">
                            <div class="col-sm-4 col-form-label text-sm-right pr-0">
                                <label for="id-form-field-focus-1" class="mb-0">Authorized By</label>
                            </div>
                            <div class="col-sm-8">
                                <input type="text"  class="form-control date mgmt" readonly placeholder="Free Select" value="<?php echo $requestData["cutoff_date"]?>">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-4 col-form-label text-sm-right pr-0">
                                <label for="id-form-field-focus-1" class="mb-0">
                                    Management Remarks
                                </label>
                            </div>
                            <div class="col-sm-8">
                                <textarea class="form-control h-90 mgmt" readonly placeholder="Free Text"><?php echo $requestData["merchant_note"]?></textarea>
                            </div>
                        </div>
                    </div>

                    Second Column
                    <div class="col-sm-4">
                        <div class="form-group row">
                            <div class="col-sm-4 col-form-label text-sm-right pr-0">
                                <label for="id-form-field-focus-1" class="mb-0">
                                    <span class="mandatory">*</span>P.I. Appl. Req. Date & Time
                                </label>
                            </div>
                            <div class="col-sm-8">
                                <input type="text" class="form-control mgmt" placeholder="AUTO Update">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-4 col-form-label text-sm-right pr-0">
                                <label for="id-form-field-focus-1" class="mb-0">
                                    <span class="mandatory">*</span>P.I. Appl. Cutoff Date & Time
                                </label>
                            </div>
                            <div class="col-sm-8">
                                <input type="text" class="form-control date" id="pi_cutoff_dt" name="pi_cutoff_dt">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-4 col-form-label text-sm-right pr-0">
                                <label for="id-form-field-focus-1" class="mb-0">
                                Purchase Dept. Note
                                </label>
                            </div>
                            <div class="col-sm-8">
                                <textarea id="purchase_dept_note" name="purchase_dept_note" class="form-control h-140" placeholder="Free Text"></textarea>
                                <div class="herr" id="err_purchase_dept_note"></div>
                            </div>
                        </div>
                    </div>
                    
                    Third Column
                    <div class="col-sm-4">
                        <div class="form-group row">
                            <div class="col-sm-4 col-form-label text-sm-right pr-0">
                                <label for="id-form-field-focus-1" class="mb-0">
                                    Request Status
                                </label>
                            </div>
                            <div class="col-sm-8">
                                <select class="cus-sel form-control js-example-basic-single mgmt" id="deprt_approval" name="deprt_approval">
                                    <option value="" disabled hidden>Select</option>
                                    <option value="1" <?php if($requestData['deprt_approval']=="1") echo "selected=\"selected\""; ?>>REQ. ACCEPTED</option>
                                    <option value="2" <?php if($requestData['deprt_approval']=="1") echo "selected=\"selected\""; ?>>REQ. DECLINED</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-4 col-form-label text-sm-right pr-0">
                                <label for="id-form-field-focus-1" class="mb-0">
                                    Management Appl. Remarks
                                </label>
                            </div>
                            <div class="col-sm-8">
                                <textarea class="form-control h-200 mgmt" placeholder="Free Text"></textarea>
                            </div>

                        </div>
                    </div>
                </div> -->
                <div class="row">
                    <!-- <div class="col-12 pr-3">
                        <label class="cus-label"> Merchant Attachment </label>
                    </div>                    

                    <div class="row mb-5">
                        <ul class="upload-list-view ImageView" style="list-style: none;">
                        </ul>
                    </div> -->

                    <div class="col-12 pr-3">
                        <label class="cus-label"> Purchase Attachment </label>
                        <ul class="upload-list-view ImageView" >
                        </ul>
                        <div id="purchaseImageUpload" class="pdt10"></div>
                    </div>

                    <div class="col-12 text-right pr-3 py-3">  
                            <a class="btn btn-info btn-sm mar-l-5rem" id="cleardraft">CLEAR DRAFT</a>
                            <a class="btn btn-info btn-sm mar-l-5rem" id="saveasdraft">SAVE AS DRAFT</a>
                        <a class="btn btn-info btn-sm mar-l-5rem" id="getValues">SAVE</a>
                    </div>
                </div>
            </form>
        </div>

        

        <?php $this->load->view(CNFCOMPANY . 'template/templatefooter'); ?>
        <div class="control-sidebar-bg"></div>
    </div>
    <script src="<?php echo base_url();?>assets/js/datatables.min.js"></script><script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/app.min.js?rn=<?php echo CNFJSCSSRANDNO ?>"></script>
    <script src="<?php echo base_url(); ?>assets/js/demo.js?rn=<?php echo CNFJSCSSRANDNO ?>"></script>
  </body>
</html>

    <script>
        var enquiry_id = <?php echo $VarEnqId; ?>;
        var reqId = <?php echo $reqId; ?>;
    </script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/jquery.uploadfile.min.js?s=<?php echo str_rand(5) ?>"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/loadingoverlay.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/ace/node_modules/sweetalert2/dist/sweetalert2.all.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/custom/request/purchase/drafpi111.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/select2.min.js"></script>

    <script src="<?php echo base_url(); ?>assets/bootstrap/moment.min.js" type="text/javascript"></script>
    <script src="<?php echo base_url(); ?>assets/plugins/date-time/js/bootstrap-datetimepicker.min.js"></script>

    <script>
        $(document).ready(function() {

            $(".mgmt").prop('disabled', true);

            function matchStart(params, data) {
                params.term = params.term || '';
                if (data.text.toUpperCase().indexOf(params.term.toUpperCase()) == 0) {
                    return data;
                }
                return false;
            }

            $('.date').datetimepicker({
                // defaultDate: moment(),
                format:'DD/MM/YYYY hh:mm A',
                minDate:new Date()
            });

           // $('.date').val(moment().format('DD/MM/YYYY hh:mm A'));

            $('.js-example-basic-single').select2({
                placeholder: "Select",
                matcher: function(params, data) {
                    return matchStart(params, data);
                },
            });

            $('b[role="presentation"]').hide();
            $('.select2-selection__arrow').append('<span class="arrow-select2-ji"><span>');
            
        });
    </script>