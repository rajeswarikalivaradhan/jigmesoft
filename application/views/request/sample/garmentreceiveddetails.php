<?php 
    $requestData = $requestData[0];
    $miDetails = $miDetails[0];
    $ArrProfileInfo = fnGetUserLoggedInfo(1);
     $samplelogin_datas = $samplelogin_data[0];  
    $subcompany_datas = $subcompany_data[0];
    
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

.form-control {
    height: 24px!important;
    padding: 3px 7px;
    font-size: 11px;
    border-radius: 10px!important;
}

.r-15 {
    right: 15px;
}
</style>

<body class="hold-transition layout-top-nav">
    <div class="wrapper">
        <?php $this->load->view(CNFCOMPANY . 'template/templateheader'); ?>
        <div class="content px-4">

            <section class="content" style="padding-top: 0px;">
                <div class="box box-info">

                    <div class="">
                      
                        <p class="text-center cus-dc-p mb-0">DELIVERY CHALLAN</p>
                    </div>
                      <hr style="border: 1px solid #ccc; margin: 2px 0;">
                    <div class="row mb-4">
                        <div class="col-sm-4">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title text-center mb-2"><strong>Company Details</strong></h4>
                                    
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
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title mb-2"><strong>From</strong></h4>
                                    <form class="form-horizontal">
                                        <div class="box-body">
                                            <div class="form-group mb-0">
                                                <label class="col-sm-4 control-label bg-g">Dept. Name:</label>
                                                <div class="col-sm-8">
                                                    Sample Department
                                                </div>
                                            </div>
                                            <div class="form-group mb-0">
                                                <label class="col-sm-4 control-label bg-g">Cont. Person:</label>
                                                <div class="col-sm-8">
                                                    <?php echo @$ArrProfileInfo['name']; ?>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-sm-4 control-label bg-g">Cont. No:</label>
                                                <div class="col-sm-8">
                                                    <?php echo @$ArrProfileInfo['mobile']; ?>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <div class="card-body">
                                    <h4 class="card-title mb-2 mt-0"><strong>To</strong></h4>
                                    <form class="form-horizontal">
                                        <div class="box-body">
                                            <div class="form-group mb-0">
                                                <label class="col-sm-4 control-label bg-g">Dept. Name:</label>
                                                <div class="col-sm-8">
                                                    Merchant Department
                                                </div>
                                            </div>
                                            <div class="form-group mb-0">
                                                <label class="col-sm-4 control-label bg-g">Merchant Name:</label>
                                                <div class="col-sm-8">
                                                    <?php echo @$ArrCommonHeaderData['merchantName'] ?>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-4 control-label bg-g">Cont. No:</label>
                                                <div class="col-sm-8">
                                                    <?php echo @$ArrCommonHeaderData['merchantMobile'] ?>
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
                                    <input type="hidden" id="gdc_no" value="<?php echo $requestData['dc_ref_queue_no']; ?>">
                                    
                                    <h4 class="card-title text-center mb-2"><strong>G.D.C. REFERENCE</strong></h4>
                                    <form class="form-horizontal">
                                        <div class="box-body">
                                            <div class="form-group mb-0">
                                                <label class="col-sm-4 control-label bg-g">G.D.C. No:</label>
                                                <div class="col-sm-8">
                                                    <?php echo $requestData['dc_ref_queue_no']; ?>
                                                </div>
                                            </div>
                                            <div class="form-group mb-0">
                                                <label   class="col-sm-4 control-label bg-g">Date & Time:</label>
                                                <div class="col-sm-8">
                                                    <?php 
                                                        echo $requestData['dc_dt'];
                                                    ?>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-sm-4 control-label bg-g">Cutoff Date & Time:</label>
                                                <div class="col-sm-8">
                                                    <?php echo $requestData['cutoff_date'] ?>
                                                </div>
                                            </div>

                                        </div>
                                    </form>
                                    <h4 class="card-title text-center mt-0 mb-2"><strong>INTERNAL REFERENCE</strong></h4>
                                    <form class="form-horizontal">
                                        <div class="box-body">
                                            
                                            <div class="form-group mb-0">
                                                <label   class="col-sm-4 control-label bg-g">WIP No:</label>
                                                <div class="col-sm-8">
                                                    <?php echo $ArrCommonHeaderData['ArrEnquiryDetails']['isriorcode']; ?>
                                                </div>
                                            </div>
                                            <div class="form-group mb-0">
                                                <label   class="col-sm-4 control-label bg-g">Queue No:</label>
                                                <div class="col-sm-8">
                                                    <?php echo $requestData['ref_queue_no'];?>
                                                     
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label   class="col-sm-4 control-label bg-g">Item Recd. Status:</label>
                                                    <?php if($requestData['item_received_status'] != "1") { ?>
                                                        <div class="col-sm-3 pr-2">
                                                            <select class="cus-sel form-control js-example-basic-single cus-dc-dd" id="item_received_status" name="item_received_status">
                                                                <option value="" disabled hidden>Select</option>
                                                                <option value="0" <?php if($requestData['item_received_status']=="0") echo "selected=\"selected\""; ?> >PENDING</option>
                                                                <option value="1" <?php if($requestData['item_received_status']=="1") echo "selected=\"selected\""; ?> >RECEIVED</option>
                                                                <option value="2" <?php if($requestData['item_received_status']=="2") echo "selected=\"selected\""; ?> >DISCREPANCY</option>
                                                                <option value="3" <?php if($requestData['item_received_status']=="3") echo "selected=\"selected\""; ?> >MISSING</option>
                                                            </select>
                                                        </div>  
                                                    <?php } else { echo '
                                                        <div class="col-sm-3 pr-2 pt-2">RECEIVED</div>'; } ?>
                                                <!-- <div class="col-md-5 pt-2"> -->
                                                <div class="col-sm-5 pt-2 pr-0" style=" padding-left: 0px;">
                                                    <?php 
                                                        $date=$requestData['item_sta_upt_dt'];
                                                        if($requestData['item_sta_upt_dt'] == '')
                                                        {
                                                            echo '';
                                                        }
                                                        else {
                                                            echo $date;
                                                        }
                                                    ?>
                                                </div>
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

                    <div class="box-body">
                        <div id="bomIndJxl"></div>
                        <div class="card">
                            <div class="card-body">
                                <div class="col-md-3">
                                    <!-- <label class="control-label">Sample Request Raised By:</label> -->
                                    <br/>
                                    <div class="h-70" id="dcNoHere">
                                        <p class="dc-name"><?php echo @$ArrCommonHeaderData['merchantName'] ?></p>
                                    </div>
                                    <label class="control-label">Request Raised By:</label>
                                    <br/>

                                </div>
                                <div class="col-md-3">
                                    <!-- <label class="control-label">Sample Request Authorized By:</label> -->
                                    <br/>
                                    <div class="h-70" id="dcNoHere">
                                        <p class="dc-name"><?php echo @$ArrCommonHeaderData['ArrMgmt']['contactname'] ?></p>
                                    </div>
                                    <label class="control-label">Request Authorized By:</label>
                                    <br/>

                                </div>
                                <div class="col-md-3">
                                    <!-- <label class="control-label">Sample Received By:</label> -->
                                    <br/>
                                    <div class="h-70" id="dcNoHere">
                                        <p class="dc-name"><?php echo $requestData['dc_received_by'];?></p>
                                    </div>
                                    <label class="control-label">Sample Received By:</label>
                                    <br/>
                                </div>
                                <div class="col-md-3 text-right">
                                    <!-- <label class="control-label">Sample Issued By:</label> -->
                                    <br/>
                                    <div class="h-70" id="dcNoHere">
                                        <p class="dc-name r-15"><?php echo @$ArrProfileInfo['name']; ?></p>
                                    </div>
                                    <label class="control-label">Sample Issued By:</label>
                                    <br/>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="box-header with-border"><h3></h3></div>
                    <div class="box-body mt-4">
                        <div class="card">
                            <div class="card-body">
                                <div class="pull-right">
                                    <a type="button" class="btn btn-info ml-5" href="<?php echo base_url(); ?>/merchant/garmentreceivedlist">Back</a>
                                    <button type="button" class="btn btn-info" id="getValues">Save</button>
                                </div>
                            </div>
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
        var enquiry_id = <?php echo $VarEnqId; ?>;
        var request_id = <?php echo $reqId; ?>;
         var gdcNo = document.getElementById('gdc_no').value;;
    </script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/jquery.uploadfile.min.js?s=<?php echo str_rand(5) ?>"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/bootstrap-datepicker.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/loadingoverlay.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/ace/node_modules/sweetalert2/dist/sweetalert2.all.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/custom/request/sample/garmentreceiveddetails.js">
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/custom/datepair.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/select2.min.js"></script>