<?php 
    $requestData = $requestData[0];
    @$draftData = $draftDetails[0];
    @$surplusDatas = $surplusData[0];
    $subcompany_datas = $subcompany_data[0];
    $ArrProfileInfo = fnGetUserLoggedInfo(1);
    //echo "<pre>"; print_r($ArrCommonHeaderData); exit;
    
    if($surplusDatas['mode'] == 'within') {
        $mode = 'WITHIN STATE';
    } else if($surplusDatas['mode'] == 'inter') {
        $mode = 'INTER STATE';
    } else {
        $mode = 'IMPORTS';
    }
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

.box-header {
    padding: 0px!important;
}

.form-group {
    margin-bottom: 5px!important;
}

.box-body {
    padding: 0 10px!important;
}

.address-col {
    min-height: 62px;
    overflow: hidden auto;
}

.bg-g {
    background: #f3f3f3;
}

.form-horizontal .control-label {
    padding: 5px!important;
}

.col-sm-8 {
    padding: 5px;
}

.form-horizontal .form-group {
    margin-left: -10px;
}

.r-15 {
    right: 15px;
}

.f-20 {
    font-size: 20px;
}

.cus-input-cb {
    height: 20px;
    width: 20px;
}

</style>

<body class="hold-transition layout-top-nav">
    <div class="wrapper">
        <?php $this->load->view(CNFCOMPANY . 'template/templateheader'); ?>
        <div class="content px-4">

            <section class="content" style="padding-top: 0px;">
                <div class="box box-info">

                    
                   
                    
                    <div class="box-div">
                          <p class="text-center cus-dc-p mb-0">STOCK TRASFER MEMO</p>
                    </div>
                   <hr style="border: 1px solid #ccc; margin: 2px 0;">
                    <div class="row mb-4">
                        <div class="col-sm-4">
                             <div class="card" id="internalForm">
                                <div class="card-body">
                                    <h4 class="card-title text-center mb-2"><strong>COMPANY DETAILS</strong></h4>
                                    <form class="form-horizontal">
                                       <div class="box-body" >
                                              <div class="form-group  mb-0">
                                                <label class="col-sm-4 control-label bg-g">Company Name:</label>
                                                <div class="col-sm-8">
                                                    <?php echo $subcompany_datas['companyname']; ?>
                                                </div>
                                            </div>
                                            <div class="form-group  mb-0">
                                                <label class="col-sm-4 control-label bg-g address-col">Address:</label>
                                                <div class="col-sm-8 address-col">
                                                   <?php echo $subcompany_datas['address'];?>, <?php echo $subcompany_datas['city'];?> - <?php echo $subcompany_datas['pincode'];?>.
        <?php echo $subcompany_datas['state'];?>, <?php echo $subcompany_datas['country'];?>.
                                                </div>
                                            </div>

                                            <div class="form-group mb-0">
                                                <label class="col-sm-4 control-label bg-g">Contact No:</label>
                                                <div class="col-sm-8">
                                                    <?php echo $subcompany_datas['mobile_no']; ?>
                                                </div>
                                            </div>
                                            <div class="form-group mb-0">
                                                <label class="col-sm-4 control-label bg-g">e-mail ID:</label>
                                                <div class="col-sm-8">
                                                    <?php echo $subcompany_datas['email_id']; ?>
                                                </div>
                                            </div>
                                            <div class="form-group mb-0">
                                                <label class="col-sm-4 control-label bg-g">GST No:</label>
                                                <div class="col-sm-8">
                                                   <?php echo $subcompany_datas['gst_no']; ?>
                                                </div>
                                            </div>
                                            <div class="form-group mb-0" >
                                                <label class="col-sm-4 control-label bg-g">IE Code:</label>
                                                <div class="col-sm-8 mb3" >
                                                   <?php echo $subcompany_datas['IECODE']; ?>
                                                </div>
                                            </div>
                                            
                                            
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-sm-4">
                            <div class="card" id="internalForm">
                                <div class="card-body">
                                    <h4 class="card-title text-center mb-2"><strong>TRANSFER DETAILS</strong></h4>
                                    <form class="form-horizontal">
                                        <div class="box-body">
                                            <div class="form-group mb-0">
                                                <label class="col-sm-4 control-label bg-g">Transfer From:</label>
                                                <div class="col-sm-8">Surplus Stock List</div>
                                            </div>
                                            <div class="form-group mb-0">
                                                <label class="col-sm-4 control-label bg-g">Transfer To:</label>
                                                <div class="col-sm-8">Order Stock List</div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-sm-4 control-label bg-g">Transfer Category:</label>
                                                <div class="col-sm-8">
                                                    <?php echo $surplusDatasspi[0]['transfer_category']; ?>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                
                            </div>
                            
                        </div>
                        
                        <div class="col-sm-4">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title text-center mb-2"><strong>S.T.M. REFERENCE</strong></h4>
                                    <form class="form-horizontal">
                                        <div class="box-body">
                                            <div class="form-group mb-0">
                                                <label class="col-sm-4 control-label bg-g">S.T.M.Ref.No:</label>
                                                <div class="col-sm-8">
                                                    <?php echo $surplusDatasspi[0]['stm_ref_no']; ?>
                                                </div>
                                            </div>
                                            <div class="form-group mb-0">
                                                <label   class="col-sm-4 control-label bg-g">Date & Time:</label>
                                                <div class="col-sm-8">
                                                    <?php echo $surplusDatasspi[0]['stm_date_time']; ?>
                                                </div>
                                            </div>

                                            <div class="form-group mb-0">
                                                <label class="col-sm-4 control-label bg-g">Cutoff Date & Time:</label>
                                                <div class="col-sm-8">
                                                    <?php echo $surplusDatasspi[0]['cutoff_date'] ?>
                                                </div>
                                            </div>
                                             <div class="form-group mt-0 mb-0">
                                                <label class="col-sm-4 control-label bg-g">WIP No:</label>
                                                <div class="col-sm-8">
                                                    <?php echo $ArrCommonHeaderData['ArrEnquiryDetails']['isriorcode']; ?>
                                                </div>
                                            </div>
                                            
                                            <div class="form-group mb-0">
                                                <label class="col-sm-4 control-label bg-g">Queue No:</label>
                                                <div class="col-sm-8 "><?php echo  $surplusDatas['ref_queue_no']; ?> </div>
                                            </div>
                                            
                                            <div class="form-group">
                                                <label class="col-sm-4 control-label bg-g">P.I.Ref.No:</label>
                                                <div class="col-sm-8 "> <?php echo $surplusDatas['pi_ref_queue_no']; ?> </div>
                                            </div>

                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                       <hr style="border: 1px solid #ccc; margin: 10px 0px;">
                    <div class="box-header with-border mb-5">
                        <h5 class="box-title pull-left mb-3" style="padding-top: 10px;padding-left: 5px;">Material Issued Details:</h5>
                        <div id="materialIssueDetails" class="mb-4"></div>
                    </div>
                    
                    <div class="row" style="padding-bottom:20px;padding-left: 25px;">
                        <div class="d-flex align-flex-center">
                        <div class="">
                            <h5><b>Amount in words</b>&nbsp;&nbsp;:</h5>
                        </div>
                        <div class="mar-l-10">
                            <input type="text" id="amount_in_words" class="w100-input input-bb-sty" autocomplete="off" readonly value="<?php echo $surplusDatas['amount_in_words'];?>">
                        </div>
                    </div>
                    <div class="d-flex align-flex-center">
                        <div class="">
                            <h5><b>Payment Terms</b> &nbsp;&nbsp;&nbsp;&nbsp;:</h5>
                        </div>
                        <div class="mar-l-10">
                            <input type="text" id="payment_terms" class="w100-input input-bb-sty " autocomplete="off" readonly value="<?php echo $surplusDatas['payment_terms'];?>">
                        </div>
                    </div>
                    </div>
                     <div class="d-flex">
                        <div class="col-md-12">
                            <h5 class="text-right">
                                <p><span style="font-size:11px;">For</span> <b>Azibo Infotech Private Limited</b></p>
                            </h5>
                        </div>
                    </div>


                    <div class="box-body">

                        <div id="bomIndJxl"></div>
                        <div class="card">
                            <div class="card-body">
                                <div class="col-md-2">
                                    <!--<label class="control-label">Merchant <br /> Raised By:</label>-->
                                    <br/>
                                    <div class="h-60" id="dcNoHere">
                                        <p class="dc-name"><?php echo @$ArrCommonHeaderData['merchantName'] ?></p>
                                    </div>
                                    <label class="control-label">Request Raised By</label>
                                    <br/>

                                </div>
                                <div class="col-md-2">
                                    <!--<label class="control-label">Management <br /> Authorization By:</label>-->
                                    <br/>
                                    <div class="h-60" id="dcNoHere">
                                        <p class="dc-name"><?php echo @$ArrCommonHeaderData['ArrMgmt']['contactname'] ?></p>
                                    </div>
                                    <label class="control-label">Request Authorized By:</label>
                                    <br/>

                                </div>
                                <div class="col-md-3">
                                    <?php echo $surplusDatas[0]?>
                                    <!--<label class="control-label">P.I. Prepared By:</label><br/>-->
                                    
                                    <div class="h-70" id="dcNoHere">
                                        <p class="dc-name"> <?php echo $surplusData[0]['pi_req_name']; ?></p>
                                    </div>
                                    <label class="control-label" style="padding-top:15px;">P.I. Prepared By:</label>
                                    <br/>
                                </div>
                                <div class="col-md-3">
                                    <br/>
                                    <div class="h-70">
                                        <p class="dc-name"><?php echo $surplusData[0]['pi_appr_name']; ?></p>
                                    </div>
                                    <label class="control-label">P.I. Approved By:</label>
                                    <br/>
                                </div>
                                <div class="col-md-2 text-right">
                                    <br/>
                                    <div class="h-70" id="dcNoHere">
                                        <p class="dc-name"></p>
                                    </div>
                                    <label class="control-label" style="font-weight: normal;">Authorized Signature</label>
                                    <br/>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <?php 
                    $stm_ref_no1 = base64_encode($stm_ref_no);
                    
                    ?>
                    <div class="box-header with-border"><h3></h3></div>
                    <div class="box-body mt-4">
                        <div class="card">
                            <div class="card-body">
                                <div class="pull-right">
                                    <!--<a type="button" class="btn btn-info ml-5" href="<?php echo base_url(); ?>request/Bomrequest/orderstockdetails/<?php echo $enqId;?>/reqId/<?php echo $req_id;?>/itemcode/<?php echo $item_code;?>/pId/<?php echo $p_id;?>">Back</a>-->
                                </div>
                            </div>
                        </div>

                    </div>

                    <div style="display: flex; justify-content: flex-end; align-items: center; gap: 10px; " >          
                       
                       <div>
        <form method="post" action="<?php echo base_url();?>request/Bomrequest/StockTransferDetails_print">
          <input type="hidden" name="enquiry_id" value="<?php echo $VarEnqId; ?>">
         
          <input type="hidden" name="stm_ref_no" value="<?php echo $stm_ref_no; ?>">
          
          <button type="submit" class="btn btn-info" id="print">Print</button>
        </form>
      </div>

      <!-- 3️⃣ Generate Button -->
      <div>
        <form method="post" action="<?php echo base_url();?>request/Bomrequest/StockTransferDetails_pdf">
          <input type="hidden" name="enquiry_id" value="<?php echo $VarEnqId; ?>">
         
          <input type="hidden" name="stm_ref_no" value="<?php echo $stm_ref_no; ?>">
          <button type="submit" class="btn btn-info" id="generate">Generate PDF</button>
        </form>
      </div>

                    </div>
                    

                </div>
            </section>
        </div>
        <?php $this->load->view(CNFCOMPANY . 'template/templatefooter'); ?>
        <div class="control-sidebar-bg"></div>
    </div>
    <!-- <script src="<?php echo base_url();?>assets/js/datatables.min.js"></script> -->
    <?php $this->load->view(CNFCOMPANY . 'template/pagefooter'); ?>
    <script>
        var stm_ref_no = '<?php echo $stm_ref_no1; ?>';
        
    </script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/jquery.uploadfile.min.js?s=<?php echo str_rand(5) ?>"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/bootstrap-datepicker.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/loadingoverlay.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/ace/node_modules/sweetalert2/dist/sweetalert2.all.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/custom/request/bom/store/stocktransferdetails.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/custom/datepair.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/select2.min.js"></script>