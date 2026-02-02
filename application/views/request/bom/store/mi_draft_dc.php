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
            
            <!-- BOM MATERIAL INDENT STARTS HERE -->

            <div class="card border-0 mar-t-3">
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
                                <div class="col-sm-12">
                                    <select class="cus-sel form-control js-example-basic-single mgmt" id="bom_dept" name="bom_dept">
                                        <option value="" disabled hidden>Select</option>
                                        <option value="SAMPLE DEPT." <?php if($miDetails['bom_dept']=="SAMPLE DEPT.") echo "selected=\"selected\""; ?> >SAMPLE DEPT.</option>
                                        <option value="PRODUCTION DEPT." <?php if($miDetails['bom_dept']=="PRODUCTION DEPT.") echo "selected=\"selected\""; ?> >PRODUCTION DEPT.</option>
                                    </select>
                                    <!-- <input type="text" id="bom_dept" name="bom_dept" class="form-control" readonly value="" placeholder="Auto Update"> -->
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
                                    <input type="text" id="bom_cutoff_date" name="bom_cutoff_date" class="form-control date mgmt" value="<?php echo $miDetails['bom_cutoff_date']; ?>" placeholder="Auto Update">
                                    <div class="herr" id="err_bom_cutoff_date"></div>
                                </div>
                            </div>
                        </div>   
                    </div>
                </form>

                <?php 
                  $savedata=0;
                    foreach ($bomMITableData as $key => $value) 
                    { 

                        if($key == $draf_id) 
                        {
                             $savedata=1;
                ?>
                    <div class="mb-3 pl-0 ml-2 text-royal-blue f-14 mb-3 mt-5">
                        <span class="text-bold text-royal-blue" style='font-size:14px !important'>Sample Ref. No: <?php echo $key+1; ?> / P.O. No: </span><?php echo $value['po_enq_ref_id']; ?> 
                        / <?php echo $value['combo_id'] ?> / <?php echo $value['component_id'] ?>
                    </div>
                    <!--<div class="mb-5" id="bomMaterialIndent<?php //echo $value['sample_requirement_id']; ?>"></div>-->
                  
                     <div class="mb-5" id="bomMaterialIndent"></div>
                    
                    
                     <?php }} ?>

                     <input type="hidden" id ="draf_id" name="draf_id" value="<?php echo $draf_id?>">
                       <input type="hidden" id ="urllink" name="draf_id" value="<?php echo $draf_id?>">

            </div>
            <div class="col-sm-3" style="text-align: left;">
                <!-- <span> <input placeholder="Free Text" type="text" class="" id="received_by" style="width: 200px;height: 35px"></span><br><br> -->
                <input placeholder="Free Text" type="text" class="" id="received_by" style="width: 200px; height: 35px" value="<?php echo !empty($mIDraftData[0]['received_name']) ? $mIDraftData[0]['received_name'] : ' '; ?>"></br></br>

                <label >Received By</label>
            </div>

            <!-- BOM MATERIAL INDENT ENDS HERE -->
            <form class="row no-rad-form add-form-mar mar-t-3" id="reqOrderProcessingForm">
                <div class="row">
                    
                    <div class="col-12 text-right pr-3 py-3">
                        <a class="btn btn-info btn-sm mar-l-5rem" id="back" href="<?php echo base_url();?>request/Bomrequest/mireceiveddetails/<?php echo urlencode(base64_encode($VarEnqId))?>/reqId/<?php echo urlencode(base64_encode($request_id)) ?>">BACK</a>
                        <?php if (count($savestatus) > 0 || count($saveasstatus) > 0  ) { ?>
                   <a class="btn btn-info btn-sm mar-l-5rem" id="clearDraft">CLEAR DRAFT</a>
                <?php } else { ?>
             <a class="btn btn-info btn-sm mar-l-5rem" id="getValues1">SAVE AS DRAFT</a>
                <?php } ?>

                <?php if ( count($saveasstatus) > 0  ) { ?>
                   <a class="btn btn-info btn-sm mar-l-5rem" id="getValues">  EDIT/SAVE </a>
                <?php } else { ?>
             <a class="btn btn-info btn-sm mar-l-5rem" id="getValues">SAVE </a>
                <?php } ?>
                        <!-- <a class="btn btn-info btn-sm mar-l-5rem" id="getValues" >SAVE </a> -->
                       
                        <!-- <?php  if($movedc >0){?>
                        <a class="btn btn-info btn-sm mar-l-5rem" id="movedc">MOVE TO DC <?php echo $movedc; ?></a>
                        <?php } else { ?>

                           
                              <a class="btn btn-info btn-sm mar-l-5rem" id="" disabled>MOVE TO DC</a>
                              
                        
                          <?php } ?> -->
                         <!-- <a class="btn btn-info btn-sm mar-l-5rem" id="movedc">MOVE TO DC <?php echo $movedc; ?></a> -->
                        <!--<a class="btn btn-info btn-sm mar-l-5rem ml-4" id="surplusStockList">BOM - SURPLUS STOCK LIST</a>-->
                    </div>
                </div>
            </form>
            
        </div>

        

        <?php $this->load->view(CNFCOMPANY . 'template/templatefooter'); ?>
        <div class="control-sidebar-bg"></div>
    </div>
    <!-- <script src="<?php echo base_url();?>assets/js/datatables.min.js"></script> -->
       <!-- <script src="<?php echo base_url();?>assets/js/datatables.min.js"></script> -->
    <?php $this->load->view(CNFCOMPANY . 'template/pagefooter'); ?>
    <script>
        var enquiry_id = <?php echo $VarEnqId; ?>;
        var request_id = <?php echo $request_id; ?>;
        var draf_id = <?php echo $draf_id; ?>;
        
    </script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/jquery.uploadfile.min.js?s=<?php echo str_rand(5) ?>"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/bootstrap-datepicker.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/loadingoverlay.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/ace/node_modules/sweetalert2/dist/sweetalert2.all.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/custom/request/bom/store/mi_draft_dc.js"></script>
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