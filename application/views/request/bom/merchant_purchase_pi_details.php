<?php 
    $requestData = $requestData[0];
     $purchasetype=$requestData['purchase_type'];
 
     $subcompany_datas = $subcompany_data[0];
      $bomstorelogin_datas = $bomstorelogin_data[0];
       $purchaselogin_datas = $purchaselogin_data[0];
      $ArrProfileInfo = fnGetUserLoggedInfo(1);

      
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

.no-web {
    display: none;
}
.text-left {
    text-align: left!important;
}
@media print {
    .no-print {
        display: none;
    }
    .no-web {
        display: block;
    }

    .content-header
    {
        display: block;
    }
    
    .col-sm-1, .col-sm-2, .col-sm-3, .col-sm-4, .col-sm-5, .col-sm-6,
    .col-sm-7, .col-sm-8, .col-sm-9, .col-sm-10, .col-sm-11, .col-sm-12 {
        float: left;               
    }

    .col-sm-12 {
        width: 100%;
    }

    .col-sm-11 {
        width: 91.66666666666666%;
    }

    .col-sm-10 {
        width: 83.33333333333334%;
    }

    .col-sm-9 {
        width: 75%;
    }

    .col-sm-8 {
        width: 66.66666666666666%;
    }

    .col-sm-7 {
        width: 58.333333333333336%;
    }

    .col-sm-6 {
        width: 50%;
    }

    .col-sm-5 {
        width: 41.66666666666667%;
    }

    .col-sm-4 {
        width: 33.33333333333333%;
    }

    .col-sm-3 {
        width: 25%;
    }

    .col-sm-2 {
            width: 16.666666666666664%;
    }

    .col-sm-1 {
            width: 8.333333333333332%;
    }
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
                        <b style="font-size: 20px !important; padding-left: 5px; margin-left: 3px;color: #333">BOM <?php echo($requesttypedata) ?> PURCHASE INDENT - </b> <b style="font-size: 20px !important;" id="mode"></b>
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
                                            <td class="process-title">Company Name : </td>
                                            <td class="process-value" colspan="3">
                                            <?php echo $subcompany_datas['companyname']; ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="process-title">Address : </td>
                                            <td class="process-value" colspan="3" style="height: 70px;">
                                            <?php echo $subcompany_datas['address'];?>, <?php echo $subcompany_datas['city'];?> - <?php echo $subcompany_datas['pincode'];?>.
           <?php echo $subcompany_datas['state'];?>, <?php echo $subcompany_datas['country'];?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="process-title">Contact No : </td>
                                            <td class="process-value" colspan="3">
                                            <?php echo $subcompany_datas['mobile_no']; ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="process-title">e-mail ID : </td>
                                            <td class="process-value" colspan="3">
                                            <?php echo $subcompany_datas['email_id']; ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="process-title">GST No. : </td>
                                            <td class="process-value" colspan="3">
                                             <?php echo $subcompany_datas['gst_no']; ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="process-title">IE Code : </td>
                                            <td class="process-value" colspan="3">
                                            <?php echo $subcompany_datas['IECODE']; ?>
                                            </td>
                                        </tr>
                                    </tbody>
                                    </table>
                                </td>
                                
                                <td class="ord-procs-cell">
                                    <table class="table tbl-procs-border">
                                        <?php  if($purchasetype == 'NEW PURCHAE'){?>
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
                                                <td class="process-value" colspan="3" id="vendorAddress" style="height:70px;">
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
                                        <?php } else {?>
                                             <tbody>
                                           
                                            <tr>
                                                <td class="process-main-head text-left" colspan="4">
                                                    <strong>From</strong>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="process-title">Dept. Name : </td>
                                                <td class="process-value" colspan="3" >
                                                 PURCHASE DEPT.
                                                </td>
                                                
                                            </tr>
                                            <tr>
                                                <td class="process-title">Cont. Person : </td>
                                                <td class="process-value" colspan="3"  >
                                                    <?php echo $purchaselogin_datas['contactname'];; ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="process-title">Contact No : </td>
                                                <td class="process-value" colspan="3" >
                                                    <?php echo $purchaselogin_datas['mobile']; ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="process-main-head text-left" colspan="4">
                                                    <strong>To</strong>
                                                </td>
                                            </tr>
                                             <tr>
                                                <td class="process-title">Dept. Name : </td>
                                                <td class="process-value" colspan="3"  >
                                                      BOM STORE
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="process-title">Cont. Person : </td>
                                                <td class="process-value" colspan="3" >
                                                  
                                                    <?php echo $bomstorelogin_datas['contactname']; ?>                                                </td>
                                            </tr>
                                             <tr>
                                                <td class="process-title">Contact No : </td>
                                                <td class="process-value" colspan="3" >
                                                    <?php echo $bomstorelogin_datas['mobile']; ?>

                                                </td>
                                            </tr>
                                            
                                        </tbody>  
                                        <?php } ?>
                                       
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
                                            <td class="process-title">P.I. Ref .No:</td>
                                            <td class="process-value" colspan="3" id="pi_ref_no">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="process-title">Date & Time:</td>
                                            <td class="process-value" colspan="3" id="pi_dt">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="process-title">Purchase Type:</td>
                                            <td class="process-value" colspan="3" id="purchase_type">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="process-title">Expted. Date OF Delivery:</td>
                                            <td class="process-value" colspan="3" id="exp_dod">
                                            </td>
                                        </tr>

                                        <tr>
                                            <td class="process-main-head" colspan="4">
                                                <strong>INTERNAL REFERENCE</strong>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="process-title">WIP No:</td>
                                            <td class="process-value" colspan="3">
                                            <?php echo $ArrCommonHeaderData['ArrEnquiryDetails']['isriorcode']; ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="process-title">Queue No:</td>
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
                </div>

            </section>

            <section style="margin-bottom: 20px;">
                <div class="card border-0">
                    <div class="d-flex align-flex-center">
                        <div class="">
                            <h5><b>Amount in words</b>&nbsp;&nbsp;: </h5>
                        </div>
                        <div class="mar-l-10">
                            <h5 id="amount_in_words"></h5>
                        </div>
                    </div>
                    <div class="d-flex align-flex-center">
                        <div class="">
                            <h5><b>Payment Terms</b> &nbsp;&nbsp;&nbsp;&nbsp;:</h5>
                        </div>
                        <div class="mar-l-10">
                            <h5 id="payment_terms"></h5>
                        </div>
                    </div>
                    <div class="d-flex">
                        <div class="col-md-12">
                            <h5 class="text-right">
                                <p><span style="font-size:11px;">For&nbsp;</span><b>Azibo Infotech Private Limited</b></p>
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
                                <p class="dc-name"><?php echo $requestData['pi_appr_name']; ?></p>
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

            <div class="card border-0 no-print">
                <div class="card-header p-3 border-0 bgc-white tbl-head mb-1">
                    <div class="card-title text-white f-14 text-500">PAYMENT REQUEST - ADVANCE</div>
                </div>
                <div id="paymentRequest"></div>
                
                <!-- <div class="col-12 text-right pr-3 py-3">          
                        <a class="btn btn-info btn-sm mar-l-5rem" id="saveAdvance">SAVE</a>
                </div> -->
            </div>
            
            <div class="card border-0 no-print">
                <div class="card-header p-3 border-0 bgc-white tbl-head mb-1">
                    <div class="card-title text-white f-14 text-500">PAYMENT REQUEST - OTHERS</div>
                </div>
                <div id="paymentRequestOthers"></div>
                <!-- <div class="col-12 text-right pr-3 py-3">          
                        <a class="btn btn-info btn-sm mar-l-5rem" id="saveOthers">SAVE</a>
                </div> -->
            </div>

            <div class="card border-0 mar-t-3 no-print">
                <div class="card-header p-3 border-0 bgc-white tbl-head mb-1">
                    <div class="card-title text-white f-14 text-500">PAYMENT REQUEST - BILL / INVOICE</div>
                </div>
                <div id="paymentRequestBill"></div>
                <!-- <div class="col-12 text-right pr-3 py-3">          
                        <a class="btn btn-info btn-sm mar-l-5rem" id="saveBill">SAVE</a>
                </div> -->
            </div>
            
            <div class="card border-0 mar-t-3">
                <div class="card-header p-3 border-0 bgc-white tbl-head mb-1">
                    <div class="card-title text-white f-14 text-500">CREDIT / CREDIT NOTE / WRITE-OFF DETAILS</div>
                </div>
                <div id="creditNoteDetails"></div>
            </div>
            
            <div class="card border-0 mar-t-3 no-print">
                <div class="card-header p-3 border-0 bgc-white tbl-head mb-1">
                    <div class="card-title text-white f-14 text-500">PAYMENT PAID DETAILS</div>
                </div>
                <div id="paymentPaidDetails"></div>
            </div>
            
            <div class="card border-0 mar-t-3 no-print">
                <div class="card-header p-3 border-0 bgc-white tbl-head mb-1">
                    <div class="card-title text-white f-14 text-500">REQUEST & PAYMENT LOG</div>
                </div>
                <div id="requestPaymentLog"></div>
            </div>

            <form class="row no-rad-form add-form-mar mar-t-3 no-print" id="reqOrderProcessingForm">
                <div class="row">
                    
                     <div class="col-12 pr-3">
                        <label class="cus-label"> Merchant Attachment </label>
                    </div>

                    <div class="row mb-5">
                        <ul class="upload-list-view ImageView" style="list-style: none;">
                        </ul>
                    </div>

                    
                    <div class="col-12 pr-3">
                        <label class="cus-label"> Purchase Attachment </label>
                    </div>

                    <div class="row mb-5">
                        <ul class="upload-list-view purchaseImageView" style="list-style: none;">
                        </ul>
                    </div>

                    <div class="col-12 text-right pr-3 py-3">          
                         <a class="btn btn-info btn-sm mar-l-5rem" id="print_val" target="_blank">PRINT</a>
                        <a class="btn btn-info btn-sm mar-l-5rem" id="pdf_val" >GENERATE PDF</a>
                        <!-- <a class="btn btn-info btn-sm mar-l-5rem" id="getValues">PAYMENT REQ.</a> -->
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
        var reqId = <?php echo $request_id; ?>;
        var pId = <?php echo $pId; ?>;
    </script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/jquery.uploadfile.min.js?s=<?php echo str_rand(5) ?>"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/bootstrap-datepicker.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/loadingoverlay.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/ace/node_modules/sweetalert2/dist/sweetalert2.all.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/custom/request/purchase/purchase_indent_details.js"></script>
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