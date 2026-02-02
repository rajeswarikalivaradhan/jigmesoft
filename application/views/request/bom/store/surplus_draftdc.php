<?php 
    // $requestData = $requestData[0];
    @$draftData = $draftDetails[0];
    @$surplusDatas = $surplusData[0];
    $ArrProfileInfo = fnGetUserLoggedInfo(1);
    //echo "<pre>"; print_r($ArrCommonHeaderData); exit;
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
.box-div {
    border: 1px solid #ddd0d0;
}

</style>

<body class="hold-transition layout-top-nav">
    <div class="wrapper">
        <?php $this->load->view(CNFCOMPANY . 'template/templateheader'); ?>
        <div class="content px-4">

            <section class="content">
                <div class="box box-info">

                    
                    <div class="box-header with-border">
                        <p class="text-center cus-dc-p mb-0"><?php echo $ArrCommonHeaderData['companyName']; ?></p>
                        <p class="text-center cus-dc-p mb-0" style="font-size:14px;"><?php echo $ArrCommonHeaderData['companyAddress']; ?> / <?php echo $ArrCommonHeaderData['companyMobile']; ?> / <?php echo $ArrCommonHeaderData['companyEmail']; ?></p>
                    </div>
                    
                    <div class="box-div">
                        <div class="col-sm-2">BOM STORE</div>
                        <div class="col-sm-8" style="text-align:center;">STOCK TRASFER MEMO - </div>
                        <div class="col-sm-2">BOM STORE</div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-sm-4">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title mb-2 mt-0"><strong>INTERNAL REFERENCE</strong></h4>
                                    <form class="form-horizontal">
                                        <div class="box-body">
                                            <div class="form-group mt-4 mb-0">
                                                <label class="col-sm-4 control-label bg-g">WIP NO:</label>
                                                <div class="col-sm-8">
                                                    <?php echo $ArrCommonHeaderData['ArrEnquiryDetails']['isriorcode']; ?>
                                                </div>
                                            </div>
                                            
                                            <div class="form-group mb-0">
                                                <label class="col-sm-4 control-label bg-g">QUEUE NO:</label>
                                                <div class="col-sm-8 "><?php echo  $draftData['ref_queue_no']; ?> </div>
                                            </div>
                                            
                                            <div class="form-group">
                                                <label class="col-sm-4 control-label bg-g">P.I.REF.No:</label>
                                                <div class="col-sm-8 "> <?php echo $surplusDatas['pi_ref_no']; ?> </div>
                                            </div>
                                            
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-sm-4">
                            <div class="card" id="internalForm">
                                <div class="card-body">
                                    <h4 class="card-title mb-2"><strong>TRANSFER DETAILS</strong></h4>
                                    <form class="form-horizontal">
                                        <div class="box-body">
                                            <div class="form-group mb-0">
                                                <label class="col-sm-4 control-label bg-g">TRANSFER FROM:</label>
                                                <div class="col-sm-8">Surplus Stock List</div>
                                            </div>
                                            <div class="form-group mb-0">
                                                <label class="col-sm-4 control-label bg-g">TRANSFER TO:</label>
                                                <div class="col-sm-8">Order Stock List</div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-sm-4 control-label bg-g">TRANSFER CATEGORY:</label>
                                                <div class="col-sm-8">
                                                    <?php echo @$surplusDatas['transfer_category']; ?>
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
                                                <label class="col-sm-4 control-label bg-g">S.T.M.REF.No:</label>
                                                <div class="col-sm-8">
                                                    <?php echo @$surplusDatas['stm_ref_no']; ?>
                                                </div>
                                            </div>
                                            <div class="form-group mb-0">
                                                <label   class="col-sm-4 control-label bg-g">DATE & TIME:</label>
                                                <div class="col-sm-8">
                                                    <?php echo @$surplusDatas['stm_date_time']; ?>
                                                </div>
                                            </div>

                                            <div class="form-group mb-0">
                                                <label class="col-sm-4 control-label bg-g">CUTTOFF DATE & TIME:</label>
                                                <div class="col-sm-8">
                                                    <?php echo @$draftData['cutoff_date'] ?>
                                                </div>
                                            </div>

                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    
                    <div class="box-header with-border mb-5">
                        <h5 class="box-title pull-left mb-3">Material Issued Details:</h5>
                        <div id="materialIssueDetails" class="mb-4"></div>
                    </div>
                    
                    
                    <div class="row" style="padding-bottom:20px;">
                        <div class="d-flex align-flex-center">
                        <div class="">
                            <h5>Amount in words&nbsp;&nbsp;:</h5>
                        </div>
                        <div class="mar-l-10">
                            <!--<h5 id="amount_in_words"></h5>-->
                            <input type="text" id="amount_in_words" class="w100-input input-bb-sty" autocomplete="off">
                        </div>
                    </div>
                    <div class="d-flex align-flex-center">
                        <div class="">
                            <h5>Payment Terms &nbsp;&nbsp;&nbsp;&nbsp;:</h5>
                        </div>
                        <div class="mar-l-10">
                            <!--<h5 id="payment_terms"></h5>-->
                            <input type="text" id="payment_terms" class="w100-input input-bb-sty " autocomplete="off">
                        </div>
                    </div>
                    </div>

                    <div class="box-body">
                        <div id="bomIndJxl"></div>
                        <div class="card">
                            <div class="card-body">
                                <div class="col-md-2">
                                    <!--<label class="control-label">Merchant <br /> Raised By:</label>-->
                                    <br/>
                                    <div class="h-70" id="dcNoHere">
                                        <p class="dc-name"><?php echo @$ArrCommonHeaderData['merchantName'] ?></p>
                                    </div>
                                    <label class="control-label">Request Raised By</label>
                                    <br/>

                                </div>
                                <div class="col-md-2">
                                    <!--<label class="control-label">Management <br /> Authorization By:</label>-->
                                    <br/>
                                    <div class="h-70" id="dcNoHere">
                                        <p class="dc-name"><?php echo @$ArrCommonHeaderData['ArrMgmt']['contactname'] ?></p>
                                    </div>
                                    <label class="control-label">Request Authorized By:</label>
                                    <br/>

                                </div>
                                <div class="col-md-3">
                                    <!--<label class="control-label">P.I. Prepared By:</label><br/>-->
                                    <div class="h-70" id="dcNoHere">
                                        <input placeholder="Free Text" type="text" class="dc-input" id="received_by">
                                        <div class="herr" id="err_received_by"></div>
                                    </div>
                                    <label class="control-label" style="padding-top:15px;">P.I. Prepared By:</label>
                                    <br/>
                                </div>
                                <div class="col-md-3">
                                    <!--<label class="control-label">P.I. Approved By:</label><br/>-->
                                    <div class="h-70">
                                        <p class="dc-name"></p>
                                    </div>
                                    <label class="control-label">P.I. Approved By:</label>
                                    <br/>
                                </div>
                                <div class="col-md-2 text-right">
                                    <!--<label class="control-label">Purchase Head <br /> Authorized By:</label><br/>-->
                                    <div class="h-70" id="dcNoHere">
                                        <p class="dc-name r-15"></p>
                                    </div>
                                    <label class="control-label">Authorized Signature</label>
                                    <br/>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php 
                    $enqId = base64_encode($VarEnqId);
                    $req_id = base64_encode($reqId);
                    $item_code = base64_encode($itemCode);
                    $p_id = base64_encode($pId);
                    
                    ?>
                    <div class="box-header with-border"><h3></h3></div>
                    <div class="box-body mt-4">
                        <div class="card">
                            <div class="card-body">
                                <div class="pull-right">
                                    <button type="button" class="btn btn-info" id="clearDraft">Clear Draft</button>
                                    <!--<a type="button" class="btn btn-info ml-5" href="<?php echo base_url(); ?>request/Bomrequest/orderstockdetails/<?php echo urlencode(base64_encode($VarEnqId));?>/reqId/<?php echo urlencode(base64_encode($reqId));?>/itemcode/<?php echo urlencode(base64_encode($itemCode));?>/pId/<?php echo urlencode(base64_encode($pId));?>">Back</a>-->
                                    <a type="button" class="btn btn-info ml-5" href="<?php echo base_url(); ?>request/Bomrequest/orderstockdetails/<?php echo $enqId;?>/reqId/<?php echo $req_id;?>/itemcode/<?php echo $item_code;?>/pId/<?php echo $p_id;?>">Back</a>
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
        var itemCode = <?php echo $itemCode; ?>;
        var pId = <?php echo $pId; ?>;
    </script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/jquery.uploadfile.min.js?s=<?php echo str_rand(5) ?>"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/bootstrap-datepicker.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/loadingoverlay.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/ace/node_modules/sweetalert2/dist/sweetalert2.all.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/custom/request/bom/store/surplus_draftdc.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/custom/datepair.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/select2.min.js"></script>