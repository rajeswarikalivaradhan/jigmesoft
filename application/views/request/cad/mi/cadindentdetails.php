<?php 
    $this->load->view(CNFCOMPANY.'template/pageheader');
    $miDetails = $miDetails[0];
?>
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
</style>

<body class="hold-transition layout-top-nav">
    <div class="wrapper">
        <?php $this->load->view(CNFCOMPANY . 'template/templateheader'); ?>
        <div class="content px-4">
            <!-- *********************** ORDER PROCESSING START HERE ************************-->
            <?php $this->load->view(CNFREQUEST . 'request_orderprocessing.php'); ?>
            <!-- *********************** ORDER PROCESSING ENDS HERE ************************-->

            <!-- *********************** MATERIAL INDENT START HERE ************************-->

            <!-- CAD MATERIAL INDENT STARTS HERE -->
            <div class="card border-0 mar-t-3">
                <div class="card-header p-3 border-0 bgc-white tbl-head mb-1">
                    <div class="card-title text-white f-14 text-500">CAD - MATERIAL INDENT</div>
                </div>

                <form class="row no-rad-form add-form-mar mt-5" id="cad_mi_form">
                    <div class="row">
                        <div class="col-sm-3">
                            <div class="form-group row">
                                <div class="col-sm-12 col-form-label text-sm-center  pb-3">
                                    <label for="id-form-field-focus-1" class="mb-0">
                                        Material Indent Ref. No. <span class="mandatory">*</span>
                                    </label>
                                </div>
                                <div class="col-sm-12  mb-3">
                                    <input type="text" id="cad_ref_no" name="cad_ref_no" class="form-control mgmt  "  value="<?php echo $miDetails['cad_ref_no']; ?>" readonly placeholder="Auto Update">
                                    <div class="herr" id="err_cad_ref_no"></div>
                                </div>
                            </div>
                        </div>   
                        
                        <div class="col-sm-3">
                            <div class="form-group row">
                                <div class="col-sm-12 col-form-label text-sm-right pb-3 ">
                                    <label for="id-form-field-focus-1" class="mb-0 ">
                                        Issue to Department <span class="mandatory">*</span>
                                    </label>
                                </div>
                                <div class="col-sm-12">
                                    <select class="cus-sel form-control js-example-basic-single mgmt" id="cad_dept" name="cad_dept">
                                        <option value="" <?php if($miDetails['cad_dept']=="") echo "selected=\"selected\""; ?>>Select</option>
                                        <option value="OUTSIDE VENDOR" <?php if($miDetails['cad_dept']=="OUTSIDE VENDOR") echo "selected=\"selected\""; ?> >OUTSIDE VENDOR</option>
                                        <option value="SAMPLE DEPT." <?php if($miDetails['cad_dept']=="SAMPLE DEPT.") echo "selected=\"selected\""; ?> >SAMPLE DEPT.</option>
                                        <option value="PRODUCTION DEPT." <?php if($miDetails['cad_dept']=="PRODUCTION DEPT.") echo "selected=\"selected\""; ?> >PRODUCTION DEPT.</option>
                                    </select>
                                    <div class="herr" id="err_cad_dept"></div>
                                </div>
                            </div>
                        </div>  

                        <div class="col-sm-3">
                            <div class="form-group row">
                                <div class="col-sm-12 col-form-label text-sm-right pb-3">
                                    <label for="id-form-field-focus-1" class="mb-0">
                                        Request Date & Time <span class="mandatory">*</span>
                                    </label>
                                </div>
                                <div class="col-sm-12">
                                    <input type="text" id="cad_req_date" name="cad_req_date" class="form-control date mgmt" value="<?php echo $miDetails['cad_req_date']; ?>" placeholder="Auto Update">
                                    <div class="herr" id="err_cad_req_date"></div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-sm-3">
                            <div class="form-group row">
                                <div class="col-sm-12 col-form-label text-sm-right pb-3">
                                    <label for="id-form-field-focus-1" class="mb-0">
                                        Cutoff Date & Time <span class="mandatory">*</span>
                                    </label>
                                </div>
                                <div class="col-sm-12">
                                    <input type="text" id="cad_cutoff_date" name="cad_cutoff_date" class="form-control date mgmt" value="<?php echo $miDetails['cad_cutoff_date']; ?>" placeholder="Auto Update">
                                    <div class="herr" id="err_cad_cutoff_date"></div>
                                </div>
                            </div>
                        </div>   
                    </div>
                </form>
                <div id="cadMaterialIndent"></div>
            </div>
            
            <!-- CAD MATERIAL INDENT ENDS HERE -->
            
            <!-- *********************** MATERIAL INDENT ENDS HERE ************************-->
            <form class="row no-rad-form add-form-mar mar-t-3">
                <div class="row">
                    <div class="col-12 text-right pr-3 py-3">

                        <?php if($login_id == 4) { if($dcStatus > 0) { ?>
                            <a class="btn btn-info btn-sm mar-l-5rem" href="<?php echo base_url(); ?>request/Cadrequest/dclist/<?php echo urlencode(base64_encode($VarEnqId)) ?>/reqId/<?php echo urlencode(base64_encode($reqId)) ?>/miId/<?php echo urlencode(base64_encode($miId)) ?>">DRAFT D.C.</a>
                        <?php } ?>
                        <a class="btn btn-info btn-sm mar-l-5rem" id="getValues">SAVE</a>
                        <?php } ?>
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
        var miId = <?php echo $miId; ?>;
    </script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/jquery.uploadfile.min.js?s=<?php echo str_rand(5) ?>"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/bootstrap-datepicker.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/loadingoverlay.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/ace/node_modules/sweetalert2/dist/sweetalert2.all.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/custom/mi/cadindentdetails.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/custom/datepair.js"></script>
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