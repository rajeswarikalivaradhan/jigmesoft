<?php 
    // $requestData = $requestData[0];
    @$miDetails = $miDetails[0];
    $ArrProfileInfo = fnGetUserLoggedInfo(1);

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

            <section class="content">
                <div class="box box-info">

                    <div class="row">
                        <div class="col-md-6"></div>
                        <div class="col-md-6 text-right">
                            <!--<input type="radio" class="cus-input-cb" name="dc_type" id="internal" value="INTERNAL" />-->
                            <!--<label for="internal" class="f-20">INTERNAL</label> &nbsp;&nbsp;&nbsp;-->
                            <!--<input type="radio" class="cus-input-cb" name="dc_type" id="external" value="EXTERNAL" />-->
                            <!--<label for="external" class="f-20">EXTERNAL</label>-->
                        </div>
                    </div>

                    <div class="box-header with-border">
                        <p class="pull-left cus-dc-p mb-0">BOM STORE</p>
                        <p class="pull-right cus-dc-p mb-0" id="dc_type"></p>
                        <p class="text-center cus-dc-p mb-0">DELIVERY CHALLAN</p>
                    </div>

                    <div class="row mb-4">
                        <div class="col-sm-4">
                            <div class="card">
                                <div class="card-body">
                                    <form class="form-horizontal">
                                        <div class="box-body">
                                            <div class="form-group mt-4 mb-0">
                                                <label class="col-sm-4 control-label bg-g">Company Name:</label>
                                                <div class="col-sm-8">
                                                    <?php echo $ArrCommonHeaderData['companyName']; ?>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-4 control-label bg-g">Address:</label>
                                                <div class="col-sm-8 address-col">
                                                    <?php echo $ArrCommonHeaderData['companyAddress']; ?>
                                                </div>
                                            </div>

                                            <div class="form-group mb-0">
                                                <label class="col-sm-4 control-label bg-g">Contact No:</label>
                                                <div class="col-sm-8">
                                                    <?php echo $ArrCommonHeaderData['companyMobile']; ?>
                                                </div>
                                            </div>
                                            <div class="form-group mb-0">
                                                <label class="col-sm-4 control-label bg-g">e-mail ID:</label>
                                                <div class="col-sm-8">
                                                    <?php echo $ArrCommonHeaderData['companyEmail']; ?>
                                                </div>
                                            </div>
                                            <div class="form-group mb-0">
                                                <label class="col-sm-4 control-label bg-g">GST No:</label>
                                                <div class="col-sm-8">
                                                    -
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-4 control-label bg-g">IE Code:</label>
                                                <div class="col-sm-8">
                                                    -
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-sm-4">
                            <?php  if($miDetails['type'] == 'INTERNAL') { ?>
                            <div class="card" id="internalForm">
                                <div class="card-body">
                                    <h4 class="card-title mb-2"><strong>From</strong></h4>
                                    <form class="form-horizontal">
                                        <div class="box-body">
                                            <div class="form-group mb-0">
                                                <label class="col-sm-4 control-label bg-g">Dept. Name:</label>
                                                <div class="col-sm-8">
                                                    BOM STORE
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
                                                    <?php echo $miDetails['bom_dept']; ?>
                                                </div>
                                            </div>
                                            <?php if($miDetails['bom_dept']=='SAMPLE DEPT.') {
                                                $sam_data = $this->db->where('usertype',5)->get(KN_USERS)->row();
                                                $contactname = $sam_data->contactname;
                                                $address = $sam_data->address;
                                                $mobile = $sam_data->mobile;
                                                $email = $sam_data->username;
                                                
                                            } else {
                                                $contactname = '';
                                                $address = '';
                                                $mobile = '';
                                                $email = '';
                                            }
                                            
                                            ?>
                                            <div class="form-group mb-0">
                                                <label class="col-sm-4 control-label bg-g">Cont. Person:</label>
                                                <div class="col-sm-8">
                                                    <?php echo @$contactname; ?>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-4 control-label bg-g">Cont. No:</label>
                                                <div class="col-sm-8">
                                                    <?php echo @$mobile; ?>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <?php  } else { ?>
                            <div class="card" id="externalForm">
                                <div class="card-body">
                                    <h4 class="card-title mb-0 mt-2"><strong>To</strong></h4>
                                    <form class="form-horizontal">
                                        <div class="box-body">
                                            <div class="form-group mt-2 mb-0">
                                                <label class="col-sm-4 control-label bg-g">Company. Name:</label>
                                                <div class="col-sm-8">
                                                    <?php echo $miDetails['bom_dept']; ?>
                                                </div>
                                            </div>
                                            <?php if($miDetails['bom_dept']=='SAMPLE DEPT.') {
                                                $sam_data = $this->db->where('usertype',5)->get(KN_USERS)->row();
                                                $contactname = $sam_data->contactname;
                                                $address = $sam_data->address;
                                                $mobile = $sam_data->mobile;
                                                $email = $sam_data->username;
                                                
                                            } else {
                                                $contactname = '';
                                                $address = '';
                                                $mobile = '';
                                                $email = '';
                                            }
                                            
                                            ?>
                                            <div class="form-group">
                                                <label class="col-sm-4 control-label bg-g">Address:</label>
                                                <div class="col-sm-8 address-col">
                                                    <?php echo @$address; ?>
                                                </div>
                                            </div>

                                            <div class="form-group mb-0">
                                                <label class="col-sm-4 control-label bg-g">Contact No:</label>
                                                <div class="col-sm-8">
                                                    <?php echo @$mobile; ?>
                                                </div>
                                            </div>
                                            <div class="form-group mb-0">
                                                <label class="col-sm-4 control-label bg-g">e-mail ID:</label>
                                                <div class="col-sm-8">
                                                    <?php echo @$email; ?>
                                                </div>
                                            </div>
                                            <div class="form-group mb-0">
                                                <label class="col-sm-4 control-label bg-g">GST No:</label>
                                                <div class="col-sm-8">
                                                    -
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-4 control-label bg-g">IE Code:</label>
                                                <div class="col-sm-8">
                                                    -
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <?php  } ?>
                        </div>
                        <?php  //print_r($miDetails);  ?>
                        <div class="col-sm-4">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title text-center mb-2"><strong>D.C. REFERENCE</strong></h4>
                                    <form class="form-horizontal">
                                        <div class="box-body">
                                            <div class="form-group mb-0">
                                                <label class="col-sm-4 control-label bg-g">D.C. No:</label>
                                                <div class="col-sm-8">
                                                    <?php echo @$miDetails['dc_ref_queue_no']; ?>
                                                </div>
                                            </div>
                                            <div class="form-group mb-0">
                                                <label   class="col-sm-4 control-label bg-g">Date & Time:</label>
                                                <div class="col-sm-8">
                                                    <?php echo @$miDetails['dc_dt']; ?>
                                                </div>
                                            </div>

                                            <div class="form-group mb-0">
                                                <label class="col-sm-4 control-label bg-g">Cutoff Date & Time:</label>
                                                <div class="col-sm-8">
                                                    <?php echo @$miDetails['cad_cutoff_date'] ?>
                                                </div>
                                            </div>
                                            
                                            <div class="form-group mb-0">
                                                <label class="col-sm-4 control-label bg-g">WIP No.:</label>
                                                <div class="col-sm-8">
                                                    <?php echo @$ArrCommonHeaderData['ArrEnquiryDetails']['isriorcode']; ?>
                                                </div>
                                            </div>

                                    
                                            <div class="form-group mb-0">
                                                <label   class="col-sm-4 control-label bg-g">Queue No:</label>
                                                <div class="col-sm-8">
                                                    <?php echo @$piDetails[0]['pi_ref_queue_no'];?>
                                                </div>
                                            </div>
                                            
                                            <div class="form-group mb-0">
                                                <label   class="col-sm-4 control-label bg-g">M.I. Ref. No.:</label>
                                                <div class="col-sm-8">
                                                    <?php echo @$miDetails['bom_ref_no'];?>
                                                </div>
                                            </div>
                                            
                                            <div class="form-group">
                                                <label   class="col-sm-4 control-label bg-g">Item Received Status:</label>
                                                <div class="col-sm-8">
                                                    <!--MI<?php echo @$miDetails['mi_id'];?>-->
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

                    <div class="box-body">
                        <div id="bomIndJxl"></div>
                        <div class="card">
                            <div class="card-body">
                                <div class="col-md-3">
                                    <!--<label class="control-label">Material Indent <br /> Raised By:</label>-->
                                    <br/>
                                    <div class="h-70" id="dcNoHere">
                                        <p class="dc-name"><?php echo @$ArrCommonHeaderData['merchantName'] ?></p>
                                    </div>
                                    <label class="control-label">M.I. Raised By:</label>
                                    <br/>

                                </div>
                                <div class="col-md-3">
                                    <!--<label class="control-label">Material Indent <br /> Authorized By:</label>-->
                                    <br/>
                                    <div class="h-70" id="dcNoHere">
                                        <p class="dc-name"><?php echo @$ArrCommonHeaderData['ArrMgmt']['contactname'] ?></p>
                                    </div>
                                    <label class="control-label">M.I. Authorized By:</label>
                                    <br/>

                                </div>
                                <div class="col-md-3">
                                    <!--<label class="control-label">Material <br /> Received By:</label><br/>-->
                                    <div class="h-70" id="dcNoHere">
                                        <input placeholder="Free Text" type="text" class="dc-input" id="received_by">
                                        <div class="herr" id="err_received_by"></div>
                                    </div>
                                    <label class="control-label">Material Received By:</label>
                                    <br/>
                                </div>
                                <div class="col-md-3 text-right">
                                    <!--<label class="control-label">Material <br /> Issued By:</label><br/>-->
                                    <div class="h-70" id="dcNoHere">
                                        <p class="dc-name r-15"><?php echo @$ArrProfileInfo['name']; ?></p>
                                    </div>
                                    <label class="control-label">Material Issued By:</label>
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
        var miId = <?php echo $miId; ?>;
        var itemCode = <?php echo $itemCode; ?>;
        
    </script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/jquery.uploadfile.min.js?s=<?php echo str_rand(5) ?>"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/bootstrap-datepicker.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/loadingoverlay.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/ace/node_modules/sweetalert2/dist/sweetalert2.all.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/custom/request/bom/draftdc.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/custom/datepair.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/select2.min.js"></script>