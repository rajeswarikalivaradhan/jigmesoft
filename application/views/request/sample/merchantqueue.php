<?php 
    $requestData = $requestData[0];
    $miDetails = $miDetails[0];
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
            

            <!-- *********************** MATERIAL INDENT START HERE ************************-->

            <!-- CAD MATERIAL INDENT STARTS HERE -->
            <div class="card border-0 mar-t-3" id="cadDiv">
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
                                    <input type="text" id="cad_ref_no" name="cad_ref_no" class="form-control mgmt" value="<?php echo $miDetails['cad_ref_no']; ?>" readonly placeholder="Auto Update">
                                    <div class="herr" id="err_cad_ref_no"></div>
                                </div>
                            </div>
                        </div>   
                        
                        <div class="col-sm-3">
                            <div class="form-group row">
                                <div class="col-sm-12 col-form-label text-sm-right pb-3">
                                    <label for="id-form-field-focus-1" class="mb-0">
                                        <span class="mandatory">*</span>Issue to Department
                                    </label>
                                </div>
                                <?php if($miDetails['type'] == 'INTERNAL') { ?>
                                <div class="col-sm-12">
                                    <select class="cus-sel form-control js-example-basic-single mgmt" id="cad_dept" name="cad_dept">
                                        <option value="" disabled hidden>Select</option>
                                        <option value="SAMPLE DEPT." <?php if($miDetails['cad_dept']=="SAMPLE DEPT.") echo "selected=\"selected\""; ?> >SAMPLE DEPT.</option>
                                        <option value="PRODUCTION DEPT." <?php if($miDetails['cad_dept']=="PRODUCTION DEPT.") echo "selected=\"selected\""; ?> >PRODUCTION DEPT.</option>
                                    </select>
                                    <div class="herr" id="err_cad_dept"></div>
                                </div>
                                <?php } else { ?>
                                    <div class="col-sm-12">
                                    <select class="cus-sel form-control js-example-basic-single mgmt" id="cad_dept" name="cad_dept">
                                        <option value="" disabled hidden>Select</option>
                                        <?php foreach($vendorDetails as $row) { ?>
                                        <option value="<?php echo $row['id'];?>" ><?php echo $row['name'];?></option>
                                        <?php } ?>
                                    </select>
                                    <div class="herr" id="err_cad_dept"></div>
                                    </div>
                                <?php } ?>
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

            <!-- FABRIC MATERIAL INDENT STARTS HERE -->
            
            <div class="card border-0 mar-t-3" id="fabDiv">
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
                                    <input type="text" id="fab_ref_no" name="fab_ref_no" class="form-control mgmt" readonly value="<?php echo $miDetails['fab_ref_no']; ?>" placeholder="Auto Update">
                                    <div class="herr" id="err_fab_ref_no"></div>
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
                                <?php if($miDetails['type'] == 'INTERNAL') { ?>
                                <div class="col-sm-12">
                                    <select class="cus-sel form-control js-example-basic-single mgmt" id="fab_dept" name="fab_dept">
                                        <option value="" disabled hidden>Select</option>
                                        <option value="SAMPLE DEPT." <?php if($miDetails['fab_dept']=="SAMPLE DEPT.") echo "selected=\"selected\""; ?> >SAMPLE DEPT.</option>
                                        <option value="PRODUCTION DEPT." <?php if($miDetails['fab_dept']=="PRODUCTION DEPT.") echo "selected=\"selected\""; ?> >PRODUCTION DEPT.</option>
                                    </select>
                                    <!-- <input type="text" id="fab_dept" name="fab_dept" class="form-control" readonly value="" placeholder="Auto Update"> -->
                                    <div class="herr" id="err_fab_dept"></div>
                                </div>
                                <?php } else { ?>
                                    <div class="col-sm-12">
                                    <select class="cus-sel form-control js-example-basic-single mgmt" id="cad_dept" name="cad_dept">
                                        <option value="" disabled hidden>Select</option>
                                        <?php foreach($vendorDetails as $row) { ?>
                                        <option value="<?php echo $row['id'];?>" ><?php echo $row['name'];?></option>
                                        <?php } ?>
                                    </select>
                                    <div class="herr" id="err_cad_dept"></div>
                                    </div>
                                <?php } ?>
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
                                        <span class="mandatory">*</span>Cutoff Date & Time
                                    </label>
                                </div>
                                <div class="col-sm-12">
                                    <input type="text" id="fab_cutoff_date" name="fab_cutoff_date" class="form-control date mgmt" value="<?php echo $miDetails['fab_cutoff_date']; ?>" placeholder="Auto Update">
                                    <div class="herr" id="err_fab_cutoff_date"></div>
                                </div>
                            </div>
                        </div>   
                    </div>
                </form>
                <?php 
                    foreach ($bomMITableData as $key => $value) 
                    { 
                ?>
                    <div class="mb-3 pl-0 ml-2 text-royal-blue f-14 mb-1">
                        <span class="text-bold text-royal-blue" style='font-size:14px !important'>Sample Ref. No: <?php echo $key+1; ?> / P.O. No: </span><?php echo $value['po_enq_ref_id']; ?> 
                        / <?php echo $value['combo_id'] ?> / <?php echo $value['component_id'] ?>
                    </div>
                    <div class="mb-5" id="fabricMaterialIndent<?php echo $value['sample_requirement_id']; ?>"></div>
                <?php } ?>
            </div>
            
            <!-- FABRIC MATERIAL INDENT ENDS HERE -->
            
            <!-- BOM MATERIAL INDENT STARTS HERE -->

            <div class="card border-0 mar-t-3" id="bomDiv">
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
                                    <input type="text" id="bom_ref_no" name="bom_ref_no" class="form-control mgmt" readonly value="<?php echo $miDetails['bom_ref_no']; ?>" placeholder="Auto Update">
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
                                <?php if($miDetails['type'] == 'INTERNAL') { ?>
                                <div class="col-sm-12">
                                    <select class="cus-sel form-control js-example-basic-single mgmt" id="bom_dept" name="bom_dept">
                                        <option value="" disabled hidden>Select</option>
                                        <option value="SAMPLE DEPT." <?php if($miDetails['bom_dept']=="SAMPLE DEPT.") echo "selected=\"selected\""; ?> >SAMPLE DEPT.</option>
                                        <option value="PRODUCTION DEPT." <?php if($miDetails['bom_dept']=="PRODUCTION DEPT.") echo "selected=\"selected\""; ?> >PRODUCTION DEPT.</option>
                                    </select>
                                    <!-- <input type="text" id="bom_dept" name="bom_dept" class="form-control" readonly value="" placeholder="Auto Update"> -->
                                    <div class="herr" id="err_bom_dept"></div>
                                </div>
                                <?php } else { ?>
                                    <div class="col-sm-12">
                                    <select class="cus-sel form-control js-example-basic-single mgmt" id="cad_dept" name="cad_dept">
                                        <option value="" disabled hidden>Select</option>
                                        <?php foreach($vendorDetails as $row) { ?>
                                        <option value="<?php echo $row['id'];?>" ><?php echo $row['name'];?></option>
                                        <?php } ?>
                                    </select>
                                    <div class="herr" id="err_cad_dept"></div>
                                    </div>
                                <?php } ?>
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
                                    <input type="text" id="bom_cutoff_date" name="bom_cutoff_date" class="form-control date mgmt" value="<?php echo $miDetails['bom_cutoff_date']; ?>" placeholder="Auto Update">
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
                    <div class="mb-3 pl-0 ml-2 text-royal-blue f-14 mb-2 mt-5">
                        <span class="text-bold text-royal-blue" style='font-size:14px !important'>Sample Ref. No: <?php echo $key+1; ?> / P.O. No: </span><?php echo $value['po_enq_ref_id']; ?> 
                        / <?php echo $value['combo_id'] ?> / <?php echo $value['component_id'] ?>
                    </div>
                    <div class="mb-5" id="bomMaterialIndent<?php echo $value['sample_requirement_id']; ?>"></div>
                <?php } ?>
            </div>

            <!-- BOM MATERIAL INDENT ENDS HERE -->

            <div class="card border-0 mar-t-3">
                <div class="card-header p-3 border-0 bgc-white tbl-head mb-1">
                    <div class="card-title text-white f-14 text-500">Q.A. STATUS UPDATES</div>
                </div>
                <div id="qaStatusReference"></div>
            </div>
            
            <div class="card border-0 mar-t-3">
                <div class="card-header p-3 border-0 bgc-white tbl-head mb-1">
                    <div class="card-title text-white f-14 text-500">JOB STATUS UPDATES</div>
                </div>
                <div id="jobStatusReference"></div>
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
                                    <span class="mandatory">*</span>Request Date & Time
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
                                <input type="text" id="cutoff_date" name="cutoff_date" class="form-control date mgmt" placeholder="Free Select" value="<?php echo $requestData["r_cutoff_date"]?>" readonly>
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
                                <textarea id="merchant_note" name="merchant_note" class="form-control h-90" placeholder="Auto Update" readonly><?php echo $requestData["merchant_note"]?></textarea>
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
                                <!-- <input type="text" class="form-control mgmt" id="auth_status" name="auth_status" autocomplete="off" placeholder="Auto Update" value="<?php echo $requestData['auth_status']; ?>"> -->
                                <select class="cus-sel form-control js-example-basic-single mgmt" id="auth_status" name="auth_status">
                                    <option value="" disabled hidden>Select</option>
                                    <option value="0" <?php if($requestData['auth_status']=="0" || $requestData['auth_status']=="") echo "selected=\"selected\""; ?>>PENDING</option>
                                    <option value="1" <?php if($requestData['auth_status']=="1") echo "selected=\"selected\""; ?>>AUTHORIZED</option>
                                    <option value="2" <?php if($requestData['auth_status']=="2") echo "selected=\"selected\""; ?>>DECLINED</option>
                                </select>
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
                                <select class="cus-sel form-control js-example-basic-single mgmt" id="auth_type" name="auth_type">
                                    <option value="" seleted>Select</option>
                                    <option value="REGULAR" <?php if($requestData['auth_type']=="REGULAR") echo "selected=\"selected\""; ?>>REGULAR</option>
                                    <option value="PRIORITY" <?php if($requestData['auth_type']=="PRIORITY") echo "selected=\"selected\""; ?>>PRIORITY</option>
                                    <option value="HIGH PRIORITY"  <?php if($requestData['auth_type']=="HIGH PRIORITY") echo "selected=\"selected\""; ?>>HIGH PRIORITY</option>
                                    <option value="IMMEDIATE" <?php if($requestData['auth_type']=="IMMEDIATE") echo "selected=\"selected\""; ?>>IMMEDIATE</option>
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
                                <input type="text" class="form-control mgmt" id="auth_by" name="auth_by" autocomplete="off" placeholder="Auto Update" value="<?php if(isset($requestData['auth_name'])) echo $requestData['auth_name']; else echo '-'; ?>">
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
                                <input type="text" class="form-control mgmt" id="auth_date" name="auth_date" autocomplete="off" placeholder="Auto Update" value="<?php echo $requestData['auth_date']; ?>">
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
                                <input type="text" class="form-control mgmt" id="mgmt_remark" name="mgmt_remark" autocomplete="off" placeholder="Free Text" value="<?php echo $requestData['mgmt_remark']; ?>">
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
                                <!-- <input type="text" class="form-control" id="req_status" name="req_status" autocomplete="off" placeholder="Auto Update" value="<?php echo $requestData['req_status']; ?>"> -->
                                <select class="cus-sel form-control js-example-basic-single mgmt" id="req_status" name="req_status">
                                    <option value="" disabled hidden>Select</option>
                                    <option value="0" <?php if($requestData['req_status']=="0" || $requestData['req_status']=="") echo "selected=\"selected\""; ?>>PENDING</option>
                                    <option value="1" <?php if($requestData['req_status']=="1") echo "selected=\"selected\""; ?>>ACCEPTED</option>
                                    <option value="2" <?php if($requestData['req_status']=="2") echo "selected=\"selected\""; ?>>DECLINED</option>
                                </select>
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
                                <input type="text" class="form-control mgmt" id="queue_no" name="queue_no" placeholder="Auto Update" value="<?php echo $requestData['ref_queue_no']; ?>">
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
                                <input type="text" class="form-control mgmt" id="que_assign_date" name="que_assign_date" placeholder="Auto Update" value="<?php echo $requestData['qno_assign_dt']; ?>">
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
                                <textarea rows="5" id="dep_remarks" name="dep_remarks" class="form-control h-90 mgmt" placeholder="Free Text"><?php echo $requestData['dep_remarks']; ?></textarea>
                                <div class="herr" id="err_dep_remarks"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 pr-3 pt-3">
                        <label class="cus-label"> Merchant Attachment </label>
                    </div>

                    <div class="row">
                        <ul class="upload-list-view ImageView" style="list-style: none;">
                        </ul>
                    </div>
                     <div class="col-12 pr-3 pt-3">
                        <label class="cus-label"> Sample Attachment </label>
                    </div>

                    <div class="row">
                        <ul class="upload-list-view sampleQAImageView" style="list-style: none;">
                        </ul>
                    </div>

                    <div class="col-12 pr-3 pt-3">
                        <label class="cus-label"> Q.A. Attachment </label>
                        <div id="QAImageUpload" class="pdt10"></div>
                    </div>

                    <div class="row">
                        <ul class="upload-list-view QAImageView" style="list-style: none;">
                        </ul>
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
        var request_id = <?php echo $reqId; ?>;
        // var samId = <?php echo $samId; ?>;
    </script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/jquery.uploadfile.min.js?s=<?php echo str_rand(5) ?>"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/bootstrap-datepicker.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/loadingoverlay.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/ace/node_modules/sweetalert2/dist/sweetalert2.all.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/custom/request/sample/merchantqueue.js"></script>
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