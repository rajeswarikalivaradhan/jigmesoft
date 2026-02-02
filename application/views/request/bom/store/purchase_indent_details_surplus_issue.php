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
.text-left {
    text-align: left!important;
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
                <div class="card-header pb-3 bgc-white border-0 " style="padding-top: 35px;">
                    <div class="card-title f-20">
                        <b style="font-size: 20px !important; padding-left: 5px; margin-left: 3px;color: #333"> PURCHASE INDENT - </b> <b style="font-size: 20px !important;" id="mode"></b>
                    </div>
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
                                            <?php echo @$ArrCommonHeaderData['merchantName']; ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="process-title">Address : </td>
                                            <td class="process-value" colspan="3" style="height: 70px;">
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
                                                <td class="process-main-head text-left" colspan="4">
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
                                                <td class="process-value" colspan="3" id="vendorAddress" style="height: 70px;">
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
                                            <td class="process-value" colspan="3" id="pi_ref_no">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="process-title">DATE & TIME:</td>
                                            <td class="process-value" colspan="3" id="pi_dt">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="process-title">PURCHASE TYPE:</td>
                                            <td class="process-value" colspan="3" id="purchase_type">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="process-title">EXP. DATE OF DELIVERY:</td>
                                            <td class="process-value" colspan="3" id="exp_dod">
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

                <!-- <div class="card border-0 mb-5">
                    <div id="withinStateDetails"></div>
                    
                </div> -->

                  <div class="card border-0 mar-t-3">
                <div class="card-header p-3 border-0 bgc-white tbl-head mb-1 " style="overflow-x: auto; white-space: nowrap;">
                    <div class="card-title text-white f-14 text-500">BOM(Art - 1) MATERIAL ISSUED DETAILS</div>
                </div>
                <div id="materialIssuedDetails"></div>
               
            </div>
                 <!-- BOM MATERIAL INDENT ENDS HERE -->
            <form class="row no-rad-form add-form-mar mar-t-3" id="reqOrderProcessingForm">
               
                <div class="row">
                   
                     <div class="col-12 text-right pr-3 py-3">
                        
                         <div class="col-12 text-right pr-3 py-3">
                         <a class="btn btn-info btn-sm mar-l-5rem" id="back" href="<?php echo base_url();?>request/Bomrequest/surpluspurchaseindentdetailspiref/<?php echo urlencode(base64_encode($VarEnqId))?>/reqId/<?php echo urlencode(base64_encode($request_id)) ?>/<?php echo urlencode(base64_encode($pId)) ?>">BACK</a> 
                        <!-- <a class="btn btn-info btn-sm mar-l-5rem" id="back" href="<?php echo base_url();?>company/Mstoreuser/surpluspurchaseindentlist">BACK</a> -->
                        
                        <!-- <a class="btn btn-info btn-sm mar-l-5rem" id="clear_data">CLEAR DRAFT</a>
                         <a class="btn btn-info btn-sm mar-l-5rem" id="save1">SAVE AS DRAFT</a>
                        <a class="btn btn-info btn-sm mar-l-5rem" id="save">SAVE AS DRAFT </a>     -->
                          <?php if (count($savestatus) > 0 || count($saveasstatus) > 0  ) { ?>
                      <a class="btn btn-info btn-sm mar-l-5rem" id="clear_data">CLEAR DRAFT</a>
                   <?php } else { ?>
                 <a class="btn btn-info btn-sm mar-l-5rem" id="save1">SAVE AS DRAFT</a>
                <?php } ?>

                <?php if ( count($saveasstatus) > 0  ) { ?>
                   <a class="btn btn-info btn-sm mar-l-5rem" id="save">  EDIT/SAVE </a>
                <?php } else { ?>
             <a class="btn btn-info btn-sm mar-l-5rem" id="save">SAVE </a>
                <?php } ?>
                                                </div>
                                                 </div>
                </div>
            </form>

            </section>

           
          
        </div>

        

        <?php $this->load->view(CNFCOMPANY . 'template/templatefooter'); ?>
        <div class="control-sidebar-bg"></div>
    </div>
    <!-- <script src="<?php echo base_url();?>assets/js/datatables.min.js"></script> -->
    <?php $this->load->view(CNFCOMPANY . 'template/pagefooter'); ?>
    <script>
        var enquiry_id = <?php echo $VarEnqId; ?>;
        var reqId = <?php echo $request_id; ?>;
        var pId = <?php echo $pId; ?>;
    </script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/jquery.uploadfile.min.js?s=<?php echo str_rand(5) ?>"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/bootstrap-datepicker.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/loadingoverlay.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/ace/node_modules/sweetalert2/dist/sweetalert2.all.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/custom/request/bom/store/purchase_indent_details_surplus_issue.js"></script>
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