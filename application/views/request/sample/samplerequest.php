<?php 
    error_reporting(0);
    $this->load->view(CNFCOMPANY.'template/pageheader');
    $miDetails = $miDetails[0];
    //echo "<pre>"; print_r($miDetails); exit;
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
.f-20 {
    font-size: 20px;
}
.cus-input-cb {
    height: 20px;
    width: 20px;
}
.type-align {
    border: 1px solid #f4f4f4;
    padding-top: 15px;
    margin-left: 23px;

}
.cus-check-btn {
        height: 18px;
        width: 42px;
}
.cus-radio-btn1 {
    height: 20px;
    width: 20px;
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
                    <div class="card-title text-white f-14 text-500">SAMPLE REQUEST</div>
                </div>
                <div id="sampleRequest"></div>
            </div>
            
            <div class="card border-0 mar-t-3">
                <div class="card-header p-3 border-0 bgc-white tbl-head mb-1">
                    <div class="card-title text-white f-14 text-500">ATTACHMENT & REFERENCE DETAILS</div>
                </div>
                <div id="attachReference"></div>
            </div>
            
            <div class="col-12 text-right pr-3 py-3">                
                <?php if($checkDraftorNot > 0) { ?>
                    <a class="btn btn-info btn-sm mar-l-5rem mr-4" id="clearRequestDetails">CLEAR DATA</a>
                <?php } ?>
                <!-- <a class="btn btn-info btn-sm mar-l-5rem" id="saveasdraft">SAVE AS DRAFT</a> -->
                <a class="btn btn-info btn-sm mar-l-5rem" id="saveRequestDetails">SAVE</a>
            </div>

            <!-- *********************** MATERIAL INDENT START HERE ************************-->
                
            <?php 
            $issued_type = explode(',',$miDetails['issued_type']);
            
            ?>
            
            <!--<div class="row mar-t-2 mb-3" style="padding-left:10px;">-->
            <!--    <div class="col-md-4 text-left" style="border:1px solid;padding:4px;">-->
            <!--        <input type="radio" class="cus-input-cb" name="issued_to" id="internal" value="INTERNAL" />-->
            <!--        <label for="internal" class="f-20">INTERNAL</label> &nbsp;&nbsp;&nbsp;-->
            <!--        <input type="radio" class="cus-input-cb" name="issued_to" id="external" value="EXTERNAL" />-->
            <!--        <label for="external" class="f-20">EXTERNAL</label>-->
            <!--    </div>-->
                
            <!--    <div class="col-md-6" style="border-top:1px solid;border-bottom:1px solid;border-left:1px solid;padding-top:3px;padding-bottom:3px;padding-left:10px;">-->
            <!--        <input type="checkbox" class="cus-input-cb issued_type" name="issued_type" id="cad" multiple value="CAD" <?php if(in_array("CAD",$issued_type)) { echo 'checked'; } else { } ?> />-->
            <!--        <label for="cad" class="f-20" style="padding-right: 10px;">CAD</label> &nbsp;&nbsp;&nbsp;-->
            <!--        <input type="checkbox" class="cus-input-cb issued_type" name="issued_type" id="fabric" multiple value="FABRIC" <?php if(in_array("FABRIC",$issued_type)) { echo 'checked'; } else { } ?> />-->
            <!--        <label for="fabric" class="f-20" style="padding-right: 14px;">FABRIC</label>-->
            <!--        <input type="checkbox" class="cus-input-cb issued_type" name="issued_type" id="bom" multiple value="BOM" <?php if(in_array("BOM",$issued_type)) { echo 'checked'; } else { } ?> />-->
            <!--        <label for="bom" class="f-20">BOM</label>-->
            <!--    </div>-->
            <!--</div>-->
            
            
            
            <div class="row">
                <p><span style="padding-left:30px; width: 500px;">Select any one:</span> <span style="padding-left:410px;text-align: right;">Select Requirement:</span> </p>
                <div class="type-align col-sm-3">
                <div class="col-sm-4">
                    <div class="form-group row issued_to">
                        <div class="col-sm-12 dis-flx-cen">
                            <input type="radio" id="internal" name="issued_to" value="INTERNAL" class="cus-radio-btn1" <?php if($miDetails['type'] == 'INTERNAL') { echo 'checked'; } else { } ?> >
                            <label for="internal" class="mb-0 f-14"> INTERNAL </label>
                        </div>
                    </div>
                    <!--<div class="err_issue"></div>-->
                </div>
                <div class="col-sm-4">
                    <div class="form-group row issued_to">
                        <div class="col-sm-12 dis-flx-cen">
                            <input type="radio" id="external" name="issued_to" value="EXTERNAL" class="cus-radio-btn1" <?php if($miDetails['type'] == 'EXTERNAL') { echo 'checked'; } else { } ?> >
                            <label for="external" class="mb-0 f-14"> EXTERNAL </label>
                        </div>
                    </div>
                    <div class="err_issue"></div>
                </div>
                
                </div>

                
                <div class="type-align col-sm-4">
                    <div class="col-sm-4">
                    <div class="form-group row">
                        <div class="col-sm-12 dis-flx-cen">
                            <input type="checkbox" id="cad " name="issued_type" value="CAD" class="cus-check-btn issued_type" <?php if(in_array("CAD",$issued_type)) { echo 'checked'; } else { } ?> />
                            <label for="cad" class="mb-0 f-14" style="padding-top:5px;"> CAD </label>
                        </div>
                    </div>
                </div>
                
                <div class="col-sm-4">
                    <div class="form-group row">
                        <div class="col-sm-12 dis-flx-cen">
                            <input type="checkbox" id="fabric" name="issued_type" value="FABRIC" class="cus-check-btn issued_type" <?php if(in_array("FABRIC",$issued_type)) { echo 'checked'; } else { } ?> />
                            <label for="fabric" class="mb-0 f-14" style="padding-top:5px;"> FABRIC </label>
                        </div>
                    </div>
                </div>
                
                <div class="col-sm-4">
                    <div class="form-group row">
                        <div class="col-sm-12 dis-flx-cen">
                            <input type="checkbox" id="bom" name="issued_type" value="BOM" class="cus-check-btn issued_type" <?php if(in_array("BOM",$issued_type)) { echo 'checked'; } else { } ?> />
                            <label for="bom" class="mb-0 f-14" style="padding-top:5px;"> BOM </label>
                        </div>
                    </div>
                </div>
                
                </div>
            </div>
            
            <!--<div class="row mar-t-2 mb-3">-->
            <!--    <div class="col-md-12 text-left">-->
                    
            <!--    </div>-->
            <!--</div>-->

            <!-- CAD MATERIAL INDENT STARTS HERE -->
            <div class="card border-0" id="CAD">
                <div class="card-header p-3 border-0 bgc-white tbl-head mb-1">
                    <div class="card-title text-white f-14 text-500">CAD - MATERIAL INDENT</div>
                </div>

                <form class="row no-rad-form add-form-mar mt-5" id="cad_mi_form">
                    <div class="row">
                        <div class="col-sm-3">
                            <div class="form-group row">
                                <div class="col-sm-12 col-form-label text-sm-right pb-3">
                                    <label for="id-form-field-focus-1" class="mb-0">
                                        Material Indent Ref. No.<span class="mandatory">*</span>
                                    </label>
                                </div>
                                <div class="col-sm-12">
                                    <input type="text" id="cad_ref_no" name="cad_ref_no" class="form-control mgmt" value="" readonly placeholder="Auto Update">
                                    <div class="herr" id="err_cad_ref_no"></div>
                                </div>
                            </div>
                        </div>   
                        
                        <div class="col-sm-3">
                            <div class="form-group row">
                                <div class="col-sm-12 col-form-label text-sm-right pb-3">
                                    <label for="id-form-field-focus-1" class="mb-0">
                                        Issue to Department<span class="mandatory">*</span>
                                    </label>
                                </div>
                                <div class="col-sm-12">
                                    <!--<select class="cus-sel form-control js-example-basic-single" id="cad_dept" name="cad_dept">-->
                                    <select class="cus-sel form-control" id="cad_dept" name="cad_dept">
                                        <option value="">Select</option>
                                        <option value="SAMPLE DEPT.">SAMPLE DEPT.</option>
                                        <option value="PRODUCTION DEPT.">PRODUCTION DEPT.</option>
                                    </select>
                                    <div class="herr" id="err_cad_dept"></div>
                                </div>
                            </div>
                        </div>  

                        <div class="col-sm-3">
                            <div class="form-group row">
                                <div class="col-sm-12 col-form-label text-sm-right pb-3">
                                    <label for="id-form-field-focus-1" class="mb-0">
                                        Request Date & Time<span class="mandatory">*</span>
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
                                        Cutoff Date & Time<span class="mandatory">*</span>
                                    </label>
                                </div>
                                <div class="col-sm-12">
                                    <input type="text" id="cad_cutoff_date" name="cad_cutoff_date" class="form-control date" value="<?php echo $miDetails['cad_cutoff_date']; ?>" placeholder="Auto Update">
                                    <div class="herr" id="err_cad_cutoff_date"></div>
                                </div>
                            </div>
                        </div>   
                    </div>
                </form>
                <div id="cadMaterialIndent"></div>
            </div>
            
            <!-- CAD MATERIAL INDENT ENDS HERE -->

            <!-- FABRIC MATERIAL INDENT STARTS HERE -->
            
            <div class="card border-0 mar-t-3" id="FABRIC">
                <div class="card-header p-3 border-0 bgc-white tbl-head mb-1">
                    <div class="card-title text-white f-14 text-500">FABRIC - MATERIAL INDENT</div>
                </div>

                <form class="row no-rad-form add-form-mar mt-5" id="fab_mi_form">
                    <div class="row">
                        <div class="col-sm-3">
                            <div class="form-group row">
                                <div class="col-sm-12 col-form-label text-sm-right pb-3">
                                    <label for="id-form-field-focus-1" class="mb-0">
                                        Material Indent Ref. No.<span class="mandatory">*</span>
                                    </label>
                                </div>
                                <div class="col-sm-12">
                                    <input type="text" id="fab_ref_no" name="fab_ref_no" class="form-control mgmt" readonly value="" placeholder="Auto Update">
                                    <div class="herr" id="err_fab_ref_no"></div>
                                </div>
                            </div>
                        </div>   
                        
                        <div class="col-sm-3">
                            <div class="form-group row">
                                <div class="col-sm-12 col-form-label text-sm-right  pb-3">
                                    <label for="id-form-field-focus-1" class="mb-0">
                                        Issue to Department<span class="mandatory">*</span>
                                    </label>
                                </div>
                                <div class="col-sm-12">
                                    <select class="cus-sel form-control" id="fab_dept" name="fab_dept">
                                        <option value="">Select</option>
                                        <option value="SAMPLE DEPT.">SAMPLE DEPT.</option>
                                        <option value="PRODUCTION DEPT.">PRODUCTION DEPT.</option>
                                    </select>
                                    <div class="herr" id="err_fab_dept"></div>
                                </div>
                            </div>
                        </div>  

                        <div class="col-sm-3">
                            <div class="form-group row">
                                <div class="col-sm-12 col-form-label text-sm-right pb-3">
                                    <label for="id-form-field-focus-1" class="mb-0">
                                        Request Date & Time<span class="mandatory">*</span>
                                    </label>
                                </div>
                                <div class="col-sm-12">
                                    <input type="text" id="fab_req_date" name="fab_req_date" readonly class="form-control date mgmt" value="<?php echo $miDetails['fab_req_date']; ?>" placeholder="Auto Update">
                                    <div class="herr" id="err_fab_req_date"></div>
                                </div>
                            </div>
                        </div>   
                        
                        <div class="col-sm-3">
                            <div class="form-group row">
                                <div class="col-sm-12 col-form-label text-sm-right pb-3">
                                    <label for="id-form-field-focus-1" class="mb-0">
                                        Cutoff Date & Time<span class="mandatory">*</span>
                                    </label>
                                </div>
                                <div class="col-sm-12">
                                    <input type="text" id="fab_cutoff_date" name="fab_cutoff_date" class="form-control date" value="<?php echo $miDetails['fab_cutoff_date']; ?>" placeholder="Auto Update">
                                    <div class="herr" id="err_fab_cutoff_date"></div>
                                </div>
                            </div>
                        </div>   
                    </div>
                </form>
                <?php 
                    foreach ($fabricMITableData as $key => $value) 
                    { 
                ?>
                    <div class="mb-3 pl-0 ml-2 text-royal-blue f-14 mb-3 mt-5">
                        <span class="text-bold text-royal-blue"style='font-size:14px !important'>Sample Ref. No: <?php echo $key+1; ?> / P.O. No: </span><?php echo $value['po_enq_ref_id']; ?> 
                        / <?php echo $value['combo_id'] ?> / <?php echo $value['component_id'] ?>
                    </div>
                    <div class="mb-5" id="fabricMaterialIndent<?php echo $value['sample_requirement_id']; ?>"></div>
                <?php } ?>
            </div>
            
            <!-- FABRIC MATERIAL INDENT ENDS HERE -->
            
            <!-- BOM MATERIAL INDENT STARTS HERE -->

            <div class="card border-0 mar-t-3" id="BOM">
                <div class="card-header p-3 border-0 bgc-white tbl-head mb-1">
                    <div class="card-title text-white f-14 text-500">BOM - MATERIAL INDENT</div>
                </div>

                <form class="row no-rad-form add-form-mar mt-5" id="bom_mi_form">
                    <div class="row">
                        <div class="col-sm-3">
                            <div class="form-group row">
                                <div class="col-sm-12 col-form-label text-sm-right pb-3">
                                    <label for="id-form-field-focus-1" class="mb-0">
                                        Material Indent Ref. No.<span class="mandatory">*</span>
                                    </label>
                                </div>
                                <div class="col-sm-12">
                                    <input type="text" id="bom_ref_no" name="bom_ref_no" class="form-control mgmt" readonly value="" placeholder="Auto Update">
                                    <div class="herr" id="err_bom_ref_no"></div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-sm-3">
                            <div class="form-group row">
                                <div class="col-sm-12 col-form-label text-sm-right pb-3">
                                    <label for="id-form-field-focus-1" class="mb-0">
                                        Issue to Department<span class="mandatory">*</span>
                                    </label>
                                </div>
                                <div class="col-sm-12">
                                    <!--<select class="cus-sel js-example-basic-single" id="bom_dept" name="bom_dept">-->
                                    <select class="cus-sel form-control" id="bom_dept" name="bom_dept">
                                        <option value="">Select</option>
                                        <option value="SAMPLE DEPT.">SAMPLE DEPT.</option>
                                        <option value="PRODUCTION DEPT.">PRODUCTION DEPT.</option>
                                    </select>
                                    <div class="herr" id="err_bom_dept"></div>
                                </div>
                            </div>
                        </div>  

                        <div class="col-sm-3">
                            <div class="form-group row">
                                <div class="col-sm-12 col-form-label text-sm-right pb-3">
                                    <label for="id-form-field-focus-1" class="mb-0">
                                        Request Date & Time<span class="mandatory">*</span>
                                    </label>
                                </div>
                                <div class="col-sm-12">
                                    <input type="text" id="bom_req_date" name="bom_req_date" class="form-control date mgmt" readonly value="<?php echo $miDetails['bom_req_date']; ?>" placeholder="Auto Update">
                                    <div class="herr" id="err_bom_req_date"></div>
                                </div>
                            </div>
                        </div>   
                        
                        <div class="col-sm-3">
                            <div class="form-group row">
                                <div class="col-sm-12 col-form-label text-sm-right pb-3">
                                    <label for="id-form-field-focus-1" class="mb-0">
                                        Cutoff Date & Time<span class="mandatory">*</span>
                                    </label>
                                </div>
                                <div class="col-sm-12">
                                    <input type="text" id="bom_cutoff_date" name="bom_cutoff_date" class="form-control date" value="<?php echo $miDetails['bom_cutoff_date']; ?>" placeholder="Auto Update">
                                    <div class="herr" id="err_bom_cutoff_date"></div>
                                </div>
                            </div>
                        </div>   
                    </div>
                </form>

                <?php 
                    foreach ($bomMITableData as $key => $value) 
                    {
                ?>  
                    <div class="mb-3 pl-0 ml-2 text-royal-blue f-14 mb-3 mt-5">
                        <span class="text-bold text-royal-blue" style='font-size:14px !important'>Sample Ref. No: <?php echo $key+1; ?> / P.O. No: </span><?php echo $value['po_enq_ref_id']; ?> 
                        / <?php echo $value['combo_id'] ?> / <?php echo $value['component_id'] ?> / <?php echo $value['color_id'] ?> / <?php echo $value['spec_code_id'] ?>
                    </div>
                    <div class="mb-5" id="bomMaterialIndent<?php echo $value['sample_requirement_id']; ?>"></div>
                <?php } ?>

                 
            </div>

            <!-- BOM MATERIAL INDENT ENDS HERE -->
            
            <!-- *********************** MATERIAL INDENT ENDS HERE ************************-->
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
                                <select class="cus-sel form-control js-example-basic-single" id="req_type" name="req_type">
                                    <option value="">Select</option>
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
                                <input type="text" id="cutoff_date" name="cutoff_date" class="form-control date" placeholder="Select" value="">
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
                                <textarea id="merchant_note" name="merchant_note" class="form-control h-90" placeholder="Free Text"></textarea>
                                <div class="herr" id="err_merchant_note"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Second Column -->
                    <div class="col-sm-4">
                        <div class="form-group row">
                            <div class="col-sm-4 col-form-label text-sm-right pr-0">
                                <label for="id-form-field-focus-1" class="mb-0">
                                    <span class="mandatory">*</span>Authorization Status
                                </label>
                            </div>
                            <div class="col-sm-8">
                                <input type="text" class="form-control mgmt" id="auth_status" name="auth_status" autocomplete="off" value="" placeholder="Auto Update">
                                <div class="herr" id="err_auth_status"></div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-4 col-form-label text-sm-right pr-0">
                                <label for="id-form-field-focus-1" class="mb-0">
                                    <span class="mandatory">*</span>Authorization Type
                                </label>
                            </div>
                            <div class="col-sm-8">
                                <input type="text" class="form-control mgmt" id="auth_type" name="auth_type" autocomplete="off" value="" placeholder="Auto Update">
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
                                <input type="text" class="form-control mgmt" id="auth_by" name="auth_by" autocomplete="off" value="" placeholder="Auto Update">
                                <div class="herr" id="err_auth_by"></div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-4 col-form-label text-sm-right pr-0">
                                <label for="id-form-field-focus-1" class="mb-0">
                                    <span class="mandatory">*</span>Authorized Date & Time
                                </label>
                            </div>
                            <div class="col-sm-8">
                                <input type="text" class="form-control mgmt" id="auth_date" name="auth_date" autocomplete="off" value="" placeholder="Auto Update">
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
                                <input type="text" class="form-control mgmt" id="mgmt_remark" name="mgmt_remark" autocomplete="off" value="" placeholder="Auto Update">
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
                                    <span class="mandatory">*</span>Assigned Sample Queue No.
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
                                    <span class="mandatory">*</span>Sample Dept. Remarks
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
                        <label class="cus-label"> Attachment</label>
                        <div id="samReqImageUpload" class="pdt10"></div>
                    </div>
                    
                    <div class="row">
                        <ul class="upload-list-view ImageView" style="list-style: none;">
                        </ul>
                    </div>

                    <div class="col-12 text-right pr-3 py-3">   
                                     
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
    </script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/jquery.uploadfile.min.js?s=<?php echo str_rand(5) ?>"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/loadingoverlay.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/ace/node_modules/sweetalert2/dist/sweetalert2.all.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/custom/request/sample/sample.js"></script>
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

            //var today = moment().format('D MMM, YYYY 00:00 ');
            
            $('.date').datetimepicker({
                
                //defaultDate: new Date(),
                format:'DD/MM/YYYY hh:mm A',
                minDate:new Date()
            });

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