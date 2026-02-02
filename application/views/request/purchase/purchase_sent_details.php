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
                                    <span class="mandatory">*</span>P.I. Appl. Req. Date & Time
                                </label>
                            </div>
                            <div class="col-sm-8">
                                <select class="cus-sel form-control js-example-basic-single mgmt" id="auth_status" readonly> 
                                    <option value="" selected disabled hidden>Select</option>
                                    <option value="1" <?php if($requestData['auth_status']=="1") echo "selected=\"selected\""; ?>>AUTHORIZED</option>
                                    <option value="2" <?php if($requestData['auth_status']=="2") echo "selected=\"selected\""; ?>>AUTH. DECLINED</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-4 col-form-label text-sm-right pr-0">
                                <label for="id-form-field-focus-1" class="mb-0">
                                    <span class="mandatory">*</span>Authorization Type
                                </label>
                            </div>
                            <div class="col-sm-8">
                                <select class="cus-sel form-control js-example-basic-single mgmt" readonly>
                                <option value="" disabled hidden>Select</option>
                                    <option value="REGULAR" <?php if($requestData['req_type']=="REGULAR") echo "selected=\"selected\""; ?> >REGULAR</option>
                                    <option value="PRIORITY" <?php if($requestData['req_type']=="PRIORITY") echo "selected=\"selected\""; ?> >PRIORITY</option>
                                    <option value="HIGH PRIORITY" <?php if($requestData['req_type']=="HIGH PRIORITY") echo "selected=\"selected\""; ?> >HIGH PRIORITY</option>
                                    <option value="IMMEDIATE" <?php if($requestData['req_type']=="IMMEDIATE") echo "selected=\"selected\""; ?> >IMMEDIATE</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-4 col-form-label text-sm-right pr-0">
                                <label for="id-form-field-focus-1" class="mb-0">
                                Authorized By
                                </label>
                            </div>
                            <div class="col-sm-8">
                                <input type="text" class="form-control mgmt" readonly autocomplete="off" value="<?php echo $requestData["auth_by"]?>" placeholder="Free Text">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-4 col-form-label text-sm-right pr-0">
                                <label for="id-form-field-focus-1" class="mb-0">
                                    Authorized Date & Time
                                </label>
                            </div>
                            <div class="col-sm-8">
                                <input type="text" class="form-control mgmt" readonly autocomplete="off" value="<?php echo $requestData["auth_date"]?>" placeholder="Free Text">
                                <div class="herr" id="err_auth_date"></div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-4 col-form-label text-sm-right pr-0">
                                <label for="id-form-field-focus-1" class="mb-0">
                                <span class="mandatory">*</span>Management Remarks
                                </label>
                            </div>
                            <div class="col-sm-8">
                                <input type="text" class="form-control mgmt" readonly autocomplete="off" value="<?php echo $requestData["mgmt_remark"]?>" placeholder="Free Text">
                            </div>
                        </div>
                    </div>
                    
                    <!-- Third Column -->
                    <div class="col-sm-4">
                        <div class="form-group row">
                            <div class="col-sm-4 col-form-label text-sm-right pr-0">
                                <label for="id-form-field-focus-1" class="mb-0">
                                    >Request Status
                                </label>
                            </div>
                            <div class="col-sm-8">
                                <select class="cus-sel form-control js-example-basic-single mgmt" id="deprt_approval" name="deprt_approval">
                                    <option value="" disabled hidden>Select</option>
                                    <option value="1" <?php if($requestData['deprt_approval']=="1") echo "selected=\"selected\""; ?>>REQ. ACCEPTED</option>
                                    <option value="2" <?php if($requestData['deprt_approval']=="1") echo "selected=\"selected\""; ?>>REQ. DECLINED</option>
                                </select>
                                <div class="herr" id="err_req_status"></div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-4 col-form-label text-sm-right pr-0">
                                <label for="id-form-field-focus-1" class="mb-0">
                                    Assigned BOM Queue No.
                                </label>
                            </div>
                            <div class="col-sm-8">
                                <input type="text" class="form-control mgmt" id="queue_no" name="queue_no" value="<?php echo $requestData["queue_no"]?>" placeholder="AUTO - Upon Save Page">
                                <div class="herr" id="err_queue_no"></div>
                            </div>

                        </div>
                        <div class="form-group row">
                            <div class="col-sm-4 col-form-label text-sm-right pr-0">
                                <label for="id-form-field-focus-1" class="mb-0">
                                Q. No. Assigned Date & Time
                                </label>
                            </div>
                            <div class="col-sm-8">
                                <input type="text" class="form-control mgmt" id="que_assign_date" name="que_assign_date" value="<?php echo $requestData["que_assign_date"]?>" placeholder="AUTO - Upon Save Page">
                                <div class="herr" id="err_que_assign_date"></div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-4 col-form-label text-sm-right pr-0">
                                <label for="id-form-field-focus-1" class="mb-0">
                                    Purchase Dept. Remarks
                                </label>
                            </div>
                            <div class="col-sm-8">
                                <textarea rows="5" id="dep_remarks" name="dep_remarks" class="form-control h-90 mgmt" placeholder="Free Text"><?php echo $requestData["dep_remarks"]?></textarea>
                                <div class="herr" id="err_dep_remarks"></div>
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
                    
                        <a class="btn btn-info btn-sm mar-l-5rem" id="getValues">SAVE</a>
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
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/custom/request/purchase/draftpi.js"></script>
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