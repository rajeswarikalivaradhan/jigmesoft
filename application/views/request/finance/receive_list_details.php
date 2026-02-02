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
</style>

<body class="hold-transition layout-top-nav">
    <div class="wrapper">
        <?php $this->load->view(CNFCOMPANY . 'template/templateheader'); ?>
        <div class="content px-4">
            <!-- *********************** ORDER PROCESSING START HERE ************************-->
            <?php $this->load->view(CNFREQUEST . 'request_orderprocessing.php'); ?>
            <!-- *********************** ORDER PROCESSING ENDS HERE ************************-->

            <!-- <div class="row px-4 mb-4 mt-5">
                <div class="col-sm-2">
                    <div class="form-group row">
                        <div class="col-sm-12 dis-flx-cen">
                            <input type="radio" id="within" name="mode" value="within" class="cus-radio-btn">
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
            </div> -->


            <section class="content-header" style="padding-top: 0">
                <div class="card-header p-3 border-0 bgc-white tbl-head mb-1">
                    <div class="card-title text-white f-14 text-500">BOM (Art - 1) PURCHASE INDENT -</div>
                    <p class="card-title text-white f-14 text-500 mb-0 sub-tbl-head"><b id="mode"></b></p>
                </div>
                <div class="order-processing">
                    <table id="" class="table">
                        <tbody>
                            <tr>
                                <td class="ord-procs-cell">
                                    <table class="table tbl-procs-border">
                                    <tbody>
                                        <tr>
                                            <td class="process-main-head" colspan="4">
                                                <strong>From</strong>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="process-title">Name : </td>
                                            <td class="process-value" colspan="3">
                                            <?php echo @$ArrCommonHeaderData['merchantName']; ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="process-title">Address : </td>
                                            <td class="process-value" colspan="3">
                                            <?php echo @$ArrCommonHeaderData['merchantCode']; ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="process-title">Contact No : </td>
                                            <td class="process-value" colspan="3">
                                            <?php echo @$ArrCommonHeaderData['ArrTeam']['mobile']; ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="process-title">Email ID : </td>
                                            <td class="process-value" colspan="3">
                                            <?php echo @$ArrCommonHeaderData['merchantEmail']; ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="process-title">GST No. : </td>
                                            <td class="process-value" colspan="3">
                                            
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="process-title">IE Cpde : </td>
                                            <td class="process-value" colspan="3">
                                            
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
                                                    <strong>To</strong>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="process-title">Name : </td>
                                                <td class="process-value" colspan="3" id="vendorName">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="process-title">Address : </td>
                                                <td class="process-value" colspan="3" id="vendorAddress">
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
                                            <td class="process-title">SUPPLY LEAD TIME:</td>
                                            <td class="process-value" colspan="3" id="supply_lead_time">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="process-title">PAYMENT TERMS:</td>
                                            <td class="process-value" colspan="3" id="payment_terms">
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
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="process-title">QUEUE NO:</td>
                                            <td class="process-value" colspan="3">
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
                            <h4>Amount in words: </h4>
                        </div>
                        <div class="mar-l-10">
                            <input type="text">
                        </div>
                    </div>
                    <div class="d-flex">
                        <div class="col-md-3 bor-light-theme">
                            <h5 class="text-center"><b>Note:</b> If goods are supplied beyond the agreed date of supply, 
                                it shall be up to the discretion of the company management to accept or reject the goods. 
                                Terms & conditions as 
                            </h5>
                        </div>
                        <div style="flex: 1"></div>
                        <div class="col-md-3">
                            <h5 class="text-center">
                                <p>Company Name</p>
                            </h5>
                        </div>
                    </div>
                    <table class="table tbl-procs-border" style="margin-top: 20px;">
                        <tbody>
                            <tr>
                                <td class="process-title" style="width: 8% !important;">Expected Date<br> of Delivery </td>
                                <td class="pad-0" style="width: 10%;"></td>
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
                    </table>
                </div>
            </section>

            <div class="card border-0">
                <div class="card-header p-3 border-0 bgc-white tbl-head mb-1">
                    <div class="card-title text-white f-14 text-500">PAYMENT REQUEST - ADVANCE</div>
                </div>
                <div id="paymentRequest"></div>
            </div>
            
            <div class="card border-0 mar-t-3">
                <div class="card-header p-3 border-0 bgc-white tbl-head mb-1">
                    <div class="card-title text-white f-14 text-500">ADVANCE PAID DETAILS</div>
                </div>
                <div id="advancePaidDetails"></div>
            </div>

            <form class="row no-rad-form add-form-mar mar-t-3" id="reqOrderProcessingForm">
                <div class="row">
                    <!-- First Column -->
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

                    <!-- Second Column -->
                    <div class="col-sm-4">
                        <div class="form-group row">
                            <div class="col-sm-4 col-form-label text-sm-right pr-0">
                                <label for="id-form-field-focus-1" class="mb-0">
                                    P.I. Appl. Req. Date & Time
                                </label>
                            </div>
                            <div class="col-sm-8">
                                <input type="text" class="form-control mgmt" placeholder="AUTO - Upto Save Page">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-4 col-form-label text-sm-right pr-0">
                                <label for="id-form-field-focus-1" class="mb-0">
                                    P.I. Appl. Cutoff Date & Time
                                </label>
                            </div>
                            <div class="col-sm-8">
                                <input type="text" class="form-control date mgmt" id="pi_cutoff_dt" name="pi_cutoff_dt">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-4 col-form-label text-sm-right pr-0">
                                <label for="id-form-field-focus-1" class="mb-0">
                                Purchase Dept. Note
                                </label>
                            </div>
                            <div class="col-sm-8">
                                <textarea id="purchase_dept_note" name="purchase_dept_note" class="form-control h-140 mgmt" placeholder="Free Text"></textarea>
                                <div class="herr" id="err_purchase_dept_note"></div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Third Column -->
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
                </div>
                <div class="row">
                    <div class="col-12 pr-3 py-3">
                        <label class="cus-label"> Attachment </label>
                        <div id="bom1ImageUpload" class="pdt10"></div>
                    </div>
                    <div class="col-12 text-right pr-3 py-3">   
                        <a class="btn btn-info btn-sm mar-l-5rem" id="saveDetailsss">SAVE</a>
                    </div>
                </div>
            </form>
        </div>

        

        <?php $this->load->view(CNFCOMPANY . 'template/templatefooter'); ?>
        <div class="control-sidebar-bg"></div>
    </div>
    <!-- <script src="<?php echo base_url();?>assets/js/datatables.min.js"></script> -->
    <?php $this->load->view(CNFCOMPANY . 'template/pagefooter'); ?>
    <script>
        var enquiry_id = <?php echo $VarEnqId; ?>;
    </script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/jquery.uploadfile.min.js?s=<?php echo str_rand(5) ?>"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/bootstrap-datepicker.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/loadingoverlay.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/ace/node_modules/sweetalert2/dist/sweetalert2.all.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/custom/request/bom/finance/receive_list_details.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/custom/datepair.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/select2.min.js"></script>

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

            $('.date').datepicker({
                'format': 'yyyy-mm-dd',
                'autoclose': true,
                'orientation': "bottom",
            }).datepicker("setDate",'today');

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