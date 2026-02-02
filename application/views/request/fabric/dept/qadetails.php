<?php

    $requestData = $requestData[0];
    $this->load->view(CNFCOMPANY.'template/pageheader');

?>
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

            <div class="col-12 pb-3 px-0">
                <div class="col-12 px-0" style="border-top: 1px solid #022b61;"></div>
            </div>

            <ul class="nav nav-pills main-head pt-2 px-3">
                <li class="active upper-case"><a data-toggle="tab" href="#fabric-tbl">Fabric</a></li>
                <li class="upper-case"><a data-toggle="tab" href="#yarn">Yarn</a></li>
                <li class="upper-case"><a data-toggle="tab" href="#knitting">Knitting</a></li>
                <li class="upper-case"><a data-toggle="tab" href="#dyeing">Dyeing</a></li>
                <li class="upper-case"><a data-toggle="tab" href="#compacting">Compacting</a></li>
                <li class="upper-case"><a data-toggle="tab" href="#lab">Lab</a></li>
            </ul>

            <div class="tab-content mt-1">
                <div id="fabric-tbl" class="tab-pane fade in active">
                    <?php $this->load->view(CNFREQUEST . 'fabric/fabric.php'); ?>
                </div>
                <div id="yarn" class="tab-pane fade">
                    <?php $this->load->view(CNFREQUEST . 'fabric/yarn.php'); ?>
                </div>
                <div id="knitting" class="tab-pane fade">
                    <?php $this->load->view(CNFREQUEST . 'fabric/knitting.php'); ?>
                </div>
                <div id="dyeing" class="tab-pane fade">
                    <?php $this->load->view(CNFREQUEST . 'fabric/dyeing.php'); ?>
                </div>
                <div id="compacting" class="tab-pane fade">
                    <?php $this->load->view(CNFREQUEST . 'fabric/compacting.php'); ?>
                </div>
                <div id="lab" class="tab-pane fade">
                    <?php $this->load->view(CNFREQUEST . 'fabric/lab.php'); ?>
                </div>
            </div>

            <form class="row no-rad-form add-form-mar mar-t-3" id="reqOrderProcessingForm">
                <div class="row">
                    <!-- First Column -->
                    <div class="col-sm-4">
                        <div class="form-group row">
                            <div class="col-sm-4 col-form-label text-sm-right pr-0">
                                <label for="id-form-field-focus-1" class="mb-0">
                                    Request Type
                                </label>
                            </div>
                            <div class="col-sm-8">
                                <select class="cus-sel form-control js-example-basic-single mgmt" id="req_type" name="req_type">
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
                                    Request Date & Time
                                </label>
                            </div>
                            <div class="col-sm-8">
                                <input type="text" id="req_date" name="req_date" class="form-control mgmt" readonly value="<?php echo $requestData["req_date"]?>" placeholder="Auto Update">
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
                                <input type="text" id="cutoff_date" name="cutoff_date" class="form-control date mgmt" placeholder="Free Select" value="<?php echo $requestData["cutoff_date"]?>">
                                <div class="herr" id="err_cutoff_date"></div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-4 col-form-label text-sm-right pr-0">
                                <label for="id-form-field-focus-1" class="mb-0">
                                    Merchant Note
                                </label>
                            </div>
                            <div class="col-sm-8">
                                <textarea id="merchant_note" name="merchant_note" class="form-control h-90 mgmt" placeholder="Auto Update"><?php echo $requestData["merchant_note"]?></textarea>
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
                            <div class="col-sm-8">
                                <select class="cus-sel form-control js-example-basic-single mgmt" id="auth_status" name="auth_status">
                                    <option value="" disabled hidden>Select</option>
                                    <option value="0" <?php if($requestData['auth_status']=="0") echo "selected=\"selected\""; ?>>REQ. PENDING</option>
                                    <option value="1" <?php if($requestData['auth_status']=="1") echo "selected=\"selected\""; ?>>AUTHORIZED</option>
                                    <option value="2" <?php if($requestData['auth_status']=="2") echo "selected=\"selected\""; ?>>AUTH. DECLINED</option>
                                </select>
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
                                <select class="cus-sel form-control js-example-basic-single mgmt" id="auth_type" name="auth_type">
                                    <option value="" disabled hidden>Select</option>
                                    <option value="REGULAR" <?php if($requestData['auth_type']=="REGULAR") echo "selected=\"selected\""; ?> >REGULAR</option>
                                    <option value="PRIORITY" <?php if($requestData['auth_type']=="PRIORITY") echo "selected=\"selected\""; ?> >PRIORITY</option>
                                    <option value="HIGH PRIORITY" <?php if($requestData['auth_type']=="HIGH PRIORITY") echo "selected=\"selected\""; ?> >HIGH PRIORITY</option>
                                    <option value="IMMEDIATE" <?php if($requestData['auth_type']=="IMMEDIATE") echo "selected=\"selected\""; ?> >IMMEDIATE</option>
                                </select>
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
                                <input type="text" class="form-control mgmt" id="auth_by" name="auth_by" autocomplete="off" value="<?php echo $requestData["contactname"]?>" placeholder="Auto Update">
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
                                <input type="text" class="form-control mgmt" id="auth_date" name="auth_date" autocomplete="off" value="<?php echo $requestData["auth_date"]?>" placeholder="Auto Update">
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
                                <input type="text" class="form-control mgmt" id="mgmt_remark" name="mgmt_remark" autocomplete="off" value="<?php echo $requestData["mgmt_remark"]?>" placeholder="Auto Update">
                                <div class="herr" id="err_mgmt_remark"></div>
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
                                <select class="cus-sel form-control js-example-basic-single mgmt" id="req_status" name="req_status">
                                    <option value="" disabled hidden>Select</option>
                                    <option value="0" <?php if($requestData['req_status']=="0") echo "selected=\"selected\""; ?>>REQ. PENDING</option>
                                    <option value="1" <?php if($requestData['req_status']=="1") echo "selected=\"selected\""; ?>>REQ. ACCEPTED</option>
                                    <option value="2" <?php if($requestData['req_status']=="2") echo "selected=\"selected\""; ?>>REQ. DECLINED</option>
                                </select>
                                <div class="herr" id="err_req_status"></div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-4 col-form-label text-sm-right pr-0">
                                <label for="id-form-field-focus-1" class="mb-0">
                                    Assigned FABRIC Queue No.
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
                                <input type="text" class="form-control mgmt" id="que_assign_date" name="que_assign_date" value="<?php echo $requestData['que_assign_date'] ?>" placeholder="Auto Update">
                                <div class="herr" id="err_que_assign_date"></div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-4 col-form-label text-sm-right pr-0">
                                <label for="id-form-field-focus-1" class="mb-0">
                                    FABRIC Dept. Remarks
                                </label>
                            </div>
                            <div class="col-sm-8">
                                <textarea rows="5" id="dep_remarks" name="dep_remarks" class="form-control h-90" placeholder="Free Text"><?php echo $requestData['dep_remarks'] ?></textarea>
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
                        <!-- <a class="btn btn-info btn-sm mr-4">VIEW DRAFT</a>
                        <a class="btn btn-info btn-sm mr-4">DRAFT P.I.</a> -->
                        <a class="btn btn-info btn-sm mr-4" id="getValues">SAVE</a>
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
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/custom/request/fabric/dept/qadetails.js"></script>
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

            // *************** knitting tab panel previous and next trigger *************** 
            $('.knitting.btnNext').click(function () {
                $('.nav-tabs.knitting > .active').next('li').find('a').trigger('click');
            });

            $('.knitting.btnPrevious').click(function () {
                $('.nav-tabs.knitting > .active').prev('li').find('a').trigger('click');
            });
            
            // *************** dyeing tab panel previous and next trigger *************** 
            $('.dyeing.btnNext').click(function () {
                $('.nav-tabs.dyeing > .active').next('li').find('a').trigger('click');
            });

            $('.dyeing.btnPrevious').click(function () {
                $('.nav-tabs.dyeing > .active').prev('li').find('a').trigger('click');
            });
            
            // *************** compacting tab panel previous and next trigger *************** 
            $('.compacting.btnNext').click(function () {
                $('.nav-tabs.compacting > .active').next('li').find('a').trigger('click');
            });

            $('.compacting.btnPrevious').click(function () {
                $('.nav-tabs.compacting > .active').prev('li').find('a').trigger('click');
            });

            // *************** lab tab panel previous and next trigger *************** 
            $('.lab.btnNext').click(function () {
                $('.nav-tabs.lab > .active').next('li').find('a').trigger('click');
            });

            $('.lab.btnPrevious').click(function () {
                $('.nav-tabs.lab > .active').prev('li').find('a').trigger('click');
            });
            
        });
    </script>