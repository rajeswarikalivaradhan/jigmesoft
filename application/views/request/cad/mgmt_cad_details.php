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
            <?php require('request_orderprocessing.php'); ?>
            <!-- *********************** ORDER PROCESSING ENDS HERE ************************-->

            <div class="card border-0">
                <div class="card-header p-3 border-0 bgc-white tbl-head mb-1">
                    <div class="card-title text-white f-14 text-500">CAD REQUEST</div>
                </div>
                <div id="cadRequest"></div>
            </div>
            
            <div class="card border-0 mar-t-3">
                <div class="card-header p-3 border-0 bgc-white tbl-head mb-1">
                    <div class="card-title text-white f-14 text-500">ATTACHMENT & REFERENCE DETAILS</div>
                </div>
                <div id="attachReference"></div>
            </div>

            <form class="row no-rad-form add-form-mar mar-t-3" id="reqOrderProcessingForm">
                <div class="row">
                    <!-- First Column -->
                    <div class="col-sm-4">
                        <div class="form-group row">
                            <div class="col-sm-4 col-form-label text-sm-right pr-0">
                                <label for="id-form-field-focus-1" class="mb-0">
                                    <span class="mandatory">*</span>Request Type
                                </label>
                            </div>
                            <div class="col-sm-8">
                                <select class="cus-sel form-control js-example-basic-single mgmt" id="req_type" name="req_type" readonly>
                                    <option value="" disabled hidden>Select</option>
                                    <option value="REGULAR">REGULAR</option>
                                    <option value="PRIORITY">PRIORITY</option>
                                    <option value="HIGH PRIORITY">HIGH PRIORITY</option>
                                    <option value="IMMEDIATE">IMMEDIATE</option>
                                </select>
                                <div class="herr" id="err_req_type"></div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-4 col-form-label text-sm-right pr-0">
                                <label for="id-form-field-focus-1" class="mb-0">
                                    <span class="mandatory">*</span>Request Date & Time
                                </label>
                            </div>
                            <div class="col-sm-8">
                                <input type="text" id="req_date" name="req_date" class="form-control" readonly value="" placeholder="Auto Update">
                                <div class="herr" id="err_req_date"></div>
                            </div>

                        </div>
                        <div class="form-group row">
                            <div class="col-sm-4 col-form-label text-sm-right pr-0">
                                <label for="id-form-field-focus-1" class="mb-0">
                                Cutoff Date & Time
                                </label>
                            </div>
                            <div class="col-sm-8">
                                <input type="text" id="cutoff_date" name="cutoff_date" class="form-control date" placeholder="Auto Update" value="" readonly>
                                <div class="herr" id="err_cutoff_date"></div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-4 col-form-label text-sm-right pr-0">
                                <label for="id-form-field-focus-1" class="mb-0">
                                    <span class="mandatory">*</span>Merchant Note
                                </label>
                            </div>
                            <div class="col-sm-8">
                                <textarea id="merchant_note" name="merchant_note" class="form-control h-90" placeholder="Auto Update" readonly></textarea>
                                <div class="herr" id="err_merchant_note"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Second Column -->
                    <div class="col-sm-4">
                        <div class="form-group row">
                            <div class="col-sm-4 col-form-label text-sm-right pr-0">
                                <label for="id-form-field-focus-1" class="mb-0">
                                    Authorization Status
                                </label>
                            </div>
                            <!-- <div class="col-sm-8">
                                <?php if($requestData[0]['mgmt_approval'] == 1 || $requestData[0]['mgmt_approval'] == '1' || $requestData[0]['mgmt_approval'] == 2) { ?>
                                <select class="cus-sel form-control js-example-basic-single mgmt" id="auth_status" name="auth_status">
                                    <option value="" disabled hidden>Select</option>
                                    <option value="0">PENDING</option>
                                    <option value="3">PENDING-RR</option>
                                    <option value="1">AUTHORIZED</option>
                                    <option value="2">DECLINED</option>
                                </select>
                                <?php } else { ?>
                                <select class="cus-sel form-control js-example-basic-single" id="auth_status" name="auth_status">
                                    <option value="" disabled hidden>Select</option>
                                    <option value="0">PENDING</option>
                                    <option value="3">PENDING-RR</option>
                                    <option value="1">AUTHORIZED</option>
                                    <option value="2">DECLINED</option>
                                </select>
                                <?php } ?>
                                <div class="herr" id="err_auth_status"></div>
                            </div> -->




                             <div class="col-sm-8">
                                <?php if($requestData[0]['auth_status']=="1" && $requestData[0]['mgmt_approval'] =="0" )  { ?>
                                    <select class="cus-sel form-control js-example-basic-single " id="auth_status" name="auth_status">
                                        <option value="" disabled hidden>Select</option>
                                        <option value="0" <?php if($requestData[0]['auth_status']=="0" || $requestData[0]['auth_status']=="") echo "selected=\"selected\""; ?> disabled>PENDING</option>
                                        <option value="1" <?php if($requestData[0]['auth_status']=="1") echo "selected=\"selected\""; ?>>AUTHORIZED</option>
                                        <option value="2" <?php if($requestData[0]['auth_status']=="2") echo "selected=\"selected\""; ?>>DECLINED</option>
                                    </select>
                                    <?php } else if($requestData[0]['auth_status']=="" && $requestData[0]['mgmt_approval'] =="0" )  { ?>
                                    <select class="cus-sel form-control js-example-basic-single " id="auth_status" name="auth_status">
                                        <option value="" disabled hidden>Select</option>
                                        <option value="0" <?php if($requestData[0]['auth_status']=="0" || $requestData[0]['auth_status']=="") echo "selected=\"selected\""; ?> disabled>PENDING</option>
                                        <option value="1" <?php if($requestData[0]['auth_status']=="1") echo "selected=\"selected\""; ?>>AUTHORIZED</option>
                                        <option value="2" <?php if($requestData[0]['auth_status']=="2") echo "selected=\"selected\""; ?>>DECLINED</option>
                                    </select>
                                    <?php } else if($requestData[0]['auth_status']=="3" && $requestData[0]['mgmt_approval'] =="3" ) { ?>
                                    <select class="cus-sel form-control js-example-basic-single " id="auth_status" name="auth_status">
                                        <option value="" disabled hidden>Select</option>
                                        <option value="3" <?php if($requestData[0]['auth_status']=="3") echo "selected=\"selected\""; ?> disabled>PENDING-RR</option>
                                        <option value="1" <?php if($requestData[0]['auth_status']=="1") echo "selected=\"selected\""; ?>>AUTHORIZED</option>
                                        <option value="2" <?php if($requestData[0]['auth_status']=="2") echo "selected=\"selected\""; ?>>DECLINED</option>
                                    </select>
                                <?php } else { ?>
                                    <select class="cus-sel form-control js-example-basic-single mgmt" id="auth_status" name="auth_status">
                                        <option value="" disabled hidden>Select</option>
                                        <option value="0" <?php if($requestData[0]['auth_status']=="0" || $requestData[0]['auth_status']=="") echo "selected=\"selected\""; ?>>PENDING</option>
                                        <option value="3" <?php if($requestData[0]['auth_status']=="3") echo "selected=\"selected\""; ?>>PENDING-RR</option>
                                        <option value="1" <?php if($requestData[0]['auth_status']=="1") echo "selected=\"selected\""; ?>>AUTHORIZED</option>
                                        <option value="2" <?php if($requestData[0]['auth_status']=="2") echo "selected=\"selected\""; ?>>DECLINED</option>
                                    </select>
                                <?php } ?>
                                <div class="herr" id="err_auth_status"></div>
                            </div>

                            
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-4 col-form-label text-sm-right pr-0">
                                <label for="id-form-field-focus-1" class="mb-0">
                                        Authorization Type
                                </label>
                            </div>
                            <div class="col-sm-8">
                                <?php if($requestData[0]['mgmt_approval'] == 1 || $requestData[0]['mgmt_approval'] == '1' || $requestData[0]['mgmt_approval'] == 2) { ?>
                                <select class="cus-sel form-control js-example-basic-single mgmt" id="auth_type" name="auth_type">
                                    <option value="" seleted>Select</option>
                                    <option value="REGULAR">REGULAR</option>
                                    <option value="PRIORITY">PRIORITY</option>
                                    <option value="HIGH PRIORITY" >HIGH PRIORITY</option>
                                    <option value="IMMEDIATE">IMMEDIATE</option>
                                </select>
                                <?php } else { ?>
                                <select class="cus-sel form-control js-example-basic-single" id="auth_type" name="auth_type">
                                    <option value="" seleted>Select</option>
                                    <option value="REGULAR">REGULAR</option>
                                    <option value="PRIORITY">PRIORITY</option>
                                    <option value="HIGH PRIORITY" >HIGH PRIORITY</option>
                                    <option value="IMMEDIATE">IMMEDIATE</option>
                                </select>
                                <?php } ?>
                                <div class="herr" id="err_auth_type"></div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-4 col-form-label text-sm-right pr-0">
                                <label for="id-form-field-focus-1" class="mb-0">
                                    Authorized By
                                </label>
                            </div>
                            <div class="col-sm-8">
                                <input type="text" class="form-control mgmt" id="auth_by" name="auth_by" autocomplete="off" placeholder="Auto Update">
                                <div class="herr" id="err_auth_by"></div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-4 col-form-label text-sm-right pr-0">
                                <label for="id-form-field-focus-1" class="mb-0">
                                        Authorized Date & Time
                                </label>
                            </div>
                            <div class="col-sm-8">
                                <input type="text" class="form-control mgmt" id="auth_date" name="auth_date" autocomplete="off" placeholder="Auto Update">
                                <div class="herr" id="err_auth_date"></div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-4 col-form-label text-sm-right pr-0">
                                <label for="id-form-field-focus-1" class="mb-0">
                                        Management Remarks
                                </label>
                            </div>
                            <div class="col-sm-8">
                                <?php if($requestData[0]['mgmt_approval'] == 1 || $requestData[0]['mgmt_approval'] == '1' || $requestData[0]['mgmt_approval'] == 2) { ?>
                                    <input type="text" class="form-control mgmt" id="mgmt_remark" name="mgmt_remark" autocomplete="off" placeholder="Free Text">
                                <?php } else { ?>
                                    <input type="text" class="form-control" id="mgmt_remark" name="mgmt_remark" autocomplete="off" placeholder="Free Text">
                                <?php } ?>
                                <div class="herr" id="err_mgmt_remark"></div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Third Column -->
                    <div class="col-sm-4">
                        <div class="form-group row">
                            <div class="col-sm-4 col-form-label text-sm-right pr-0">
                                <label for="id-form-field-focus-1" class="mb-0">
                                    <span class="mandatory">*</span>Request Status
                                </label>
                            </div>
                            <div class="col-sm-8">
                                <input type="text" class="form-control mgmt" id="req_status" name="req_status" autocomplete="off" value="" placeholder="Auto Update">
                                <div class="herr" id="err_req_status"></div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-4 col-form-label text-sm-right pr-0">
                                <label for="id-form-field-focus-1" class="mb-0">
                                    <span class="mandatory">*</span>Assigned CAD Queue No.
                                </label>
                            </div>
                            <div class="col-sm-8">
                                <input type="text" class="form-control mgmt" id="queue_no" name="queue_no" value="" placeholder="Auto Update">
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
                                <input type="text" class="form-control mgmt" id="que_assign_date" name="que_assign_date" value="" placeholder="Auto Update">
                                <div class="herr" id="err_que_assign_date"></div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-4 col-form-label text-sm-right pr-0">
                                <label for="id-form-field-focus-1" class="mb-0">
                                    <span class="mandatory">*</span>CAD Dept. Remarks
                                </label>
                            </div>
                            <div class="col-sm-8">
                                <textarea rows="5" id="dep_remarks" name="dep_remarks" class="form-control h-90 mgmt" placeholder="Auto Update"></textarea>
                                <div class="herr" id="err_dep_remarks"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 pr-3 py-3">
                        <label class="cus-label"> Attachment </label>
                    </div>
                    
                    <div class="row">
                        <ul class="upload-list-view ImageView" style="list-style: none;">
                        </ul>
                    </div>

                    <?php if($requestData[0]['mgmt_approval'] == 1 || $requestData[0]['mgmt_approval'] == '1' || $requestData[0]['mgmt_approval'] == 2) { } else { ?>
                        <div class="col-12 text-right pr-3 py-3">
                            <a class="btn btn-info btn-sm mar-l-5rem btnsavee" id="getValues">SAVE</a>
                        </div>
                    <?php } ?>
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
        var reqId = <?php echo $reqId; ?>;
    </script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/jquery.uploadfile.min.js?s=<?php echo str_rand(5) ?>"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/bootstrap-datepicker.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/loadingoverlay.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/ace/node_modules/sweetalert2/dist/sweetalert2.all.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/custom/request/cad/mgmt_cad_details.js"></script>
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