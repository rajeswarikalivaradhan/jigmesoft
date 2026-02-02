<?php 
    $requestData = $requestData[0];
     $subcompany_datas = $subcompany_data[0];

?>

<?php $this->load->view(CNFCOMPANY.'template/pageheader');?>
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/css/datatables.min.css">
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/css/select2.min.css" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/css/uploadfile-order.css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.css"  />
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/css/wip.css" />

<!-- *********************** JEXCEL CSS LOADS HERE ************************-->
<link rel="stylesheet" type="text/css" href="<?= base_url() ?>assets/css/jexcelNew/jspreadsheet.css" />
<link rel="stylesheet" type="text/css" href="<?= base_url() ?>assets/css/jexcelNew/jsuites.css" />

<link rel="stylesheet" href="<?php echo base_url();?>assets/plugins/date-time/css/bootstrap-datetimepicker.min.css">

<!-- *********************** JEXCEL SCRIPTS LOADS HERE ************************-->
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/ajax.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/vue.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/jexcelNew/jexcel.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/jexcelNew/jsuites.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/jexcel/numeral.min.js"></script>

<!-- *************** CUSTOM STYLE HERE ********* -->
<style>
.text-left {
    text-align: left!important;
}
table.table.tbl-procs-border {
    margin-bottom: 0;
}
.inp-full-wds {
    width: 100%;
    padding: 0 4px;
    height: 23.5px;
    border : none;
    /* border: 1px solid #b4b0b0; */
}
.t-height {
    height: 38px;
}

</style>

<body class="hold-transition layout-top-nav">
    <div class="wrapper">
        <?php $this->load->view(CNFCOMPANY . 'template/templateheader'); ?>
        <div class="content px-4">
            <!-- *********************** ORDER PROCESSING START HERE ************************-->
            <?php $this->load->view(CNFREQUEST . 'request_orderprocessing.php'); ?>
            <!-- *********************** ORDER PROCESSING ENDS HERE ************************-->

            <section class="content-header" style="padding-top: 0">
                <div class="card-header pb-3 bgc-white border-0 " style="padding-top: 35px;">
                    <div class="card-title f-20">
                        <b style="font-size: 20px !important; padding-left: 5px; margin-left: 3px;color: #333">BOM <?php echo $requesttypedata ?> PURCHASE INDENT - </b> <b style="font-size: 20px !important;" id="mode"></b>
                    </div>
                </div>
                <div class="col-12 pb-3 px-0">
                    <div class="col-12 px-0" style="border-top: 1px solid #022b61;"></div>
                </div>
                <?php //print_r($requestData); exit; ?>
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
                                            <?php echo $subcompany_datas['companyname']; ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="process-title">Address : </td>
                                            <td class="process-value" colspan="3" style="height:68px;">
                                           <?php echo $subcompany_datas['address'];?>, <?php echo $subcompany_datas['city'];?> - <?php echo $subcompany_datas['pincode'];?>.
           <?php echo $subcompany_datas['state'];?>, <?php echo $subcompany_datas['country'];?>
                                            </td>
                                        </tr>
                                        <tr class="t-height">
                                            <td class="process-title">Contact No : </td>
                                            <td class="process-value" colspan="3">
                                            <?php echo $subcompany_datas['mobile_no']; ?>
                                            </td>
                                        </tr>
                                        <tr class="t-height">
                                            <td class="process-title">Email ID : </td>
                                            <td class="process-value" colspan="3">
                                            <?php echo $subcompany_datas['email_id']; ?>
                                            </td>
                                        </tr>
                                        <tr class="t-height">
                                            <td class="process-title">GST No. : </td>
                                            <td class="process-value" colspan="3">
                                                  <?php echo $subcompany_datas['gst_no']; ?>
                                            
                                            </td>
                                        </tr>
                                        <tr class="t-height">
                                            <td class="process-title">IE Cpde : </td>
                                            <td class="process-value" colspan="3">
                                                  <?php echo $subcompany_datas['IECODE']; ?>
                                            
                                            </td>
                                        </tr>
                                    </tbody>
                                    </table>
                                </td>

                                <?php if($requestData['p_type'] == 'new_purchase') { ?>
                                
                                <td class="ord-procs-cell to">
                                    <table class="table tbl-procs-border">
                                        <tbody>
                                            <tr>
                                                <td class="process-main-head text-left" colspan="4">
                                                    <strong>To</strong>
                                                </td>
                                            </tr>
                                            <tr class="t-height">
                                                <td class="process-title">Name : </td>
                                                <td class="process-value" colspan="3" id="vendorName">
                                                </td>
                                            </tr>
                                            <tr class="t-height">
                                                <td class="process-title">Address : </td>
                                                <td class="process-value" colspan="3" id="vendorAddress" style="height:67px;">
                                                </td>
                                            </tr>
                                            <tr class="t-height">
                                                <td class="process-title">Contact No : </td>
                                                <td class="process-value" colspan="3" id="vendorContact">
                                                </td>
                                            </tr>
                                            <tr class="t-height">
                                                <td class="process-title">Email ID : </td>
                                                <td class="process-value" colspan="3" id="vendorEmail">
                                                </td>
                                            </tr>
                                            <tr class="t-height">
                                                <td class="process-title">GST No. : </td>
                                                <td class="process-value" colspan="3" id="vendorGst">
                                                </td>
                                            </tr>
                                            <tr class="t-height">
                                                <td class="process-title">IE Code : </td>
                                                <td class="process-value" colspan="3" id="vendorIeCode">
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            <?php } else { ?>
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
                                <?php } ?>
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
                                        <!--pi_decline-->
                                        <tr>
                                            <td class="process-title">PURCHASE TYPE:</td>
                                            <td class="process-value" colspan="3" >
                                            <input class="inp-full-wds" id="purchase_type" type="text"  readonly autocomplete="off" />
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="process-title">EXP. DATE OF DELIVERY:</td>
                                            <td class="process-value" colspan="3">
                                                <input class="inp-full-wds  date" id="exp_dod" type="text" value="" />
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
                    <input type="text" id="p_mode" hidden value="<?php echo $requestData['mode'];?>">
                </div>

            </section>

            <section style="margin-bottom: 20px;">
                <div class="card border-0">
                    <div class="d-flex align-flex-center">
                        <div class="">
                            <h5><b>Amount in words</b>&nbsp;&nbsp;:</h5>
                        </div>
                        <?php if($_SESSION['UI']['usertype'] == 7 && @$requestData['appr_status'] =="2") { ?>
                        
                            <div class="mar-l-10">
                            <!--<h5 id="amount_in_words"></h5>-->
                            <input type="text" id="amount_in_words" class="w100-input input-bb-sty ">
                        </div>
                        
                        <?php } else { ?>
                            <div class="mar-l-10">
                            <!--<h5 id="amount_in_words"></h5>-->
                            <input type="text" id="amount_in_words" class="w100-input input-bb-sty " readonly>
                        </div>
                        <?php } ?>
                        
                    </div>
                    <div class="d-flex align-flex-center">
                        <div class="">
                            <h5><b>Payment Terms</b> &nbsp;&nbsp;&nbsp;&nbsp;:</h5>
                        </div>
                        <?php if($_SESSION['UI']['usertype'] == 7 && @$requestData['appr_status'] =="2") { ?>
                        
                            <div class="mar-l-10">
                            <!--<h5 id="payment_terms"></h5>-->
                            <input type="text" id="payment_terms" class="w100-input input-bb-sty " >
                        </div>
                        
                        <?php } else { ?>
                        
                            <div class="mar-l-10">
                            <!--<h5 id="payment_terms"></h5>-->
                            <input type="text" id="payment_terms" class="w100-input input-bb-sty " readonly >
                        </div>
                        <?php } ?>
                        
                    </div>
                    <div class="d-flex">
                        <div class="col-md-12">
                            <h5 class="text-right">
                                <p><span style="font-size:11px;">For</span> <b>Azibo Infotech Private Limited</b></p>
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
                                <p class="dc-name"><?php echo $requestData['pi_req_name']; ?></p>
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
                            <label class="control-label" style="font-weight: normal;">Authorized Signatory</label>
                            <br/>
                        </div>
                    </div>
                </div>
            </section>

            <div class="card border-0">
                <div class="card-header p-3 border-0 bgc-white tbl-head mb-1">
                    <div class="card-title text-white f-14 text-500">PAYMENT REQUEST - ADVANCE</div>
                </div>
                <div id="paymentRequest"></div>
            </div>

            <!-- <div class="card border-0 mar-t-3">
                <div class="card-header p-3 border-0 bgc-white tbl-head mb-1">
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
                <div class="row">
                    <div class="col-12 pr-3">
                        <label class="cus-label"> Merchant Attachment </label>
                    </div>                    

                    <div class="row mb-5">
                        <ul class="upload-list-view ImageView" style="list-style: none;">
                        </ul>
                    </div>

                    <div class="col-12 pr-3">
                        <label class="cus-label"> Purchase Attachment</label>
                    </div> 
                     <div class="row mb-5">
                        <ul class="upload-list-view purchaseImageView" style="list-style: none;">
                        </ul>
                    </div>
                    
                    
                    
                    <?php //print_r($requestData); exit; ?>
                    <?php if($_SESSION['UI']['usertype'] == 7 && @$requestData['appr_status'] =="2") { ?>
                    <div class="col-12 text-right pr-3 py-3">  
                        <a class="btn btn-info btn-sm mr-3" id="getValues2" >CANCEL P.I.</a>              
                        <a class="btn btn-info btn-sm mar-l-5rem" id="getValues">SAVE</a>
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
        var pId = <?php echo $pId; ?>;
        var usertype = <?php echo $_SESSION['UI']['usertype']; ?>;
        var appr_status = <?php echo @$requestData['appr_status']; ?>;
    </script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/jquery.uploadfile.min.js?s=<?php echo str_rand(5) ?>"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/bootstrap-datepicker.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/loadingoverlay.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/ace/node_modules/sweetalert2/dist/sweetalert2.all.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/custom/request/purchase/request_sent_details.js"></script>
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
            
            $('.date').datetimepicker({
                // defaultDate: moment(),
                format:'DD/MM/YYYY hh:mm A',
                minDate:new Date()
            });
            $('.date').val(moment().format('DD/MM/YYYY hh:mm A'));

            // $('.date').datepicker({
            //     'format': 'yyyy-mm-dd',
            //     'autoclose': true,
            //     'orientation': "bottom",
            // }).datepicker("setDate",'today');

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