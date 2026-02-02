<?php 
    $requestData = $requestData[0];
    $miDetails = $miDetails[0];
    $ArrProfileInfo = fnGetUserLoggedInfo(1);
    $subcompany_datas = $subcompany_data[0];
     $samplelogin_datas = $samplelogin_data[0];  
//echo "<pre>"; print_r($miDetails); exit;
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
    word-break: break-word;
}

.form-horizontal .form-group {
    margin-left: -10px;
}

.r-15 {
    right: 15px;
}

.no-web {
    display: none;
}

@media print{

    .no-web {
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

    .no-print {
        display: none!important;
    }

    .bg-g {
        background: #f3f3f3;
    }

    .h-320 {
        height: 320px;
    }

    .d-in-tbl {
        display: inline-table;
    }

}

</style>

<body class="hold-transition layout-top-nav">
    <div class="wrapper">
        <?php $this->load->view(CNFCOMPANY . 'template/templateheader'); ?>
        <div class="content px-4">

            <section class="content" style="padding-top: 0px;">
                <div class="box box-info" >

                    <div class="">
                      
                       
                        <p class="text-center cus-dc-p mb-0">DELIVERY CHALLAN</p>
                    </div>
                    <hr style="border: 1px solid #ccc; margin: 2px 0;">

                    <div class="row mb-4">
                        <div class="col-sm-4">
                            <div class="card">
                                <div class="card-body" >
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
                            <div class="card" id="internalForm">
                                <div class="card-body" >
                                    <h4 class="card-title mb-2"><strong>From</strong></h4>
                                    <form class="form-horizontal">
                                        <div class="box-body">
                                            <div class="form-group mb-0">
                                                <label class="col-sm-4 control-label bg-g">Dept. Name:</label>
                                                <div class="col-sm-8">
                                                    CAD Department
                                                </div>
                                            </div>
                                            <div class="form-group mb-0">
                                                <label class="col-sm-4 control-label bg-g">Cont. Person:</label>
                                                <div class="col-sm-8">
                                                    <?php echo @$ArrProfileInfo['name']; ?>
                                                </div>
                                            </div>

                                            <div class="form-group" style="padding-bottom: 10px;">
                                                <label class="col-sm-4 control-label bg-g">Cont. No:</label>
                                                <div class="col-sm-8">
                                                    <?php echo @$ArrProfileInfo['mobile']; ?>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <div class="card-body" >
                                    <h4 class="card-title mb-2 mt-0"><strong>To</strong></h4>
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
                                                    <?php echo $samplelogin_datas['contactname']; ?>
                                                </div>
                                            </div>
                                            <div class="form-group" style="padding-bottom: 15px;">
                                                <label class="col-sm-4 control-label bg-g">Cont. No:</label>
                                                <div class="col-sm-8">
                                                    <?php echo $samplelogin_datas['mobile']; ?>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <?php 
                            if($miDetails['type'] == 'EXTERNAL') {
                                $cad_data = $this->db->where('id',$miDetails['cad_dept'])->get('kn_master_bom_vendor')->row_array();
                            }
                            ?>
                            <div class="card" id="externalForm">
                                <div class="card-body" >
                                    <h4 class="card-title mb-0 mt-2"><strong>To</strong></h4>
                                    <form class="form-horizontal">
                                        <div class="box-body">
                                            <div class="form-group mt-2 mb-0">
                                                <label class="col-sm-4 control-label bg-g">Dept. Name:</label>
                                                <div class="col-sm-8">
                                                   
                                                      <?php echo !empty($cad_data['vendorname']) ? $cad_data['vendorname'] : ''; ?>
                                                </div>
                                            </div>
                                            <div class="form-group mb-0">
                                                <label class="col-sm-4 control-label bg-g address-col">Address:</label>
                                                <div class="col-sm-8 address-col">
                                                  
                                                     <?php echo !empty($cad_data['address']) ? $cad_data['address'] : ''; ?>
                                                </div>
                                            </div>

                                            <div class="form-group mb-0">
                                                <label class="col-sm-4 control-label bg-g">Contact No:</label>
                                                <div class="col-sm-8">
                                                   
                                                      <?php echo !empty($cad_data['mobile']) ? $cad_data['mobile'] : ''; ?>
                                                </div>
                                            </div>
                                            <div class="form-group mb-0">
                                                <label class="col-sm-4 control-label bg-g">e-mail ID:</label>
                                                <div class="col-sm-8">
                                                  
                                                      <?php echo !empty($cad_data['emailid']) ? $cad_data['emailid'] : ''; ?>
                                                    
                                                </div>
                                            </div>
                                            <div class="form-group mb-0">
                                                <label class="col-sm-4 control-label bg-g">GST No:</label>
                                                <div class="col-sm-8">
                                                   
                                                      <?php echo !empty($cad_data['gstno']) ? $cad_data['gstno'] : ''; ?>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-4 control-label bg-g">IE Code:</label>
                                                <div class="col-sm-8">

                                                      <?php echo !empty($cad_data['iecode']) ? $cad_data['iecode'] : ''; ?>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="card">
                                <div class="card-body" >
                                    <h4 class="card-title text-center mb-2"><strong>D.C. REFERENCE</strong></h4>
                                    <form class="form-horizontal">
                                        <div class="box-body">
                                            <div class="form-group mb-0">
                                                <label class="col-sm-4 control-label bg-g">D.C. No:</label>
                                                <div class="col-sm-8">
                                                    <?php echo $miDetails['dc_ref_queue_no']; ?>
                                                </div>
                                            </div>
                                            <div class="form-group mb-0">
                                                <label   class="col-sm-4 control-label bg-g">Date & Time:</label>
                                                <div class="col-sm-8">
                                                    <?php echo $miDetails['dc_dt']; ?>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-sm-4 control-label bg-g">Cutoff Date & Time:</label>
                                                <div class="col-sm-8">
                                                    <?php echo $miDetails['cad_cutoff_date'] ?>
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
                                                    <?php echo $requestData['sam_queue_no'];?>
                                                </div>
                                            </div>
                                            <div class="form-group mb-0">
                                                <label   class="col-sm-4 control-label bg-g">M.I. Ref. No:</label>
                                                <div class="col-sm-8">
                                                    <?php echo $miDetails['cad_ref_no'];?>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                 
                                                <label class="col-sm-4 control-label bg-g">Item Recd. Status:</label>
                                               
                                                 <?php if($miDetails['item_received_status'] != "1") { ?>
                                                    <?php if ($usertype == 4) { ?>
                                                       <div class="col-md-3 col-sm-6 pr-2 pt-2">
                                                    <?php 
                                                        if($miDetails['item_received_status']=="0") echo "PENDING"; 
                                                        else if($miDetails['item_received_status']=="1") echo "RECEIVED"; 
                                                        else if($miDetails['item_received_status']=="2") echo "DISCREPANCY"; 
                                                        else if($miDetails['item_received_status']=="3") echo "MISSING"; 
                                                    ?>
                                                    <p class="mb-0 no-web"><?php echo $miDetails['item_sta_upt_dt']; ?></p>
                                                     </div>
                                      <?php } ?>
                                       <?php if ($usertype == 5) { ?>
                                     <div class="col-md-3 col-sm-6 pr-2 pt-2">
                                                            <select class="cus-sel form-control js-example-basic-single cus-dc-dd" id="item_received_status" name="item_received_status">
                                                                <option value="" disabled hidden>Select</option>
                                                                <option value="0" <?php if($miDetails['item_received_status']=="0") echo "selected=\"selected\""; ?> >PENDING</option>
                                                                <option value="1" <?php if($miDetails['item_received_status']=="1") echo "selected=\"selected\""; ?> >RECEIVED</option>
                                                                <option value="2" <?php if($miDetails['item_received_status']=="2") echo "selected=\"selected\""; ?> >DISCREPANCY</option>
                                                                <option value="3" <?php if($miDetails['item_received_status']=="3") echo "selected=\"selected\""; ?> >MISSING</option>
                                                            </select>
                                                             
                                                        </div>
                                                         <p class="mb-0 no-web"><?php echo $miDetails['item_sta_upt_dt']; ?></p>
                                      <?php } ?>
                                                        
                                                    <?php } 

                                                    else { echo '<div class="col-sm-3 pr-2 pt-2">RECEIVED</div>'; } ?>
                                                <div class="col-sm-5 pt-2 no-print" style=" padding-left: 0px;">
                                                    <?php
                                                        $date=$miDetails['item_sta_upt_dt'];
                                                        if($miDetails['item_sta_upt_dt'] == '')
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
                    <div class="box-header with-border mb-5" >
                        <h5 class="box-title pull-left mb-3" style="padding-top: 10px;padding-left: 5px;">Material Issued Details:</h5>
                        <div id="materialIssueDetails" class="mb-4 h-320"></div>
                    </div>

                    <div class="box-body">
                        <div id="bomIndJxl"></div>
                        <div class="card">
                            <div class="card-body">
                                <div class="col-sm-3">
                                    <!-- <label class="control-label">Material Indent <br /> Raised By:</label> -->
                                    <br/>
                                    <div class="h-60" id="dcNoHere">
                                        <p class="dc-name"><?php echo @$ArrCommonHeaderData['merchantName'] ?></p>
                                    </div>
                                    <label class="control-label">M.I. Raised By:</label>
                                    <br/>

                                </div>
                                <div class="col-sm-3">
                                    <!-- <label class="control-label">Material Indent <br /> Authorized By:</label> -->
                                    <br/>
                                    <div class="h-60" id="dcNoHere">
                                        <p class="dc-name"><?php echo @$ArrCommonHeaderData['ArrMgmt']['contactname'] ?></p>
                                    </div>
                                    <label class="control-label">M.I. Authorized By:</label>
                                    <br/>

                                </div>
                                <div class="col-sm-3">
                                    <!-- <label class="control-label">Material <br /> Received By:</label> -->
                                    <br/>
                                    <div class="h-60" id="dcNoHere">
                                        <p class="dc-name" id="material_received_by"><?php echo $miDetails['material_received_by']; ?></p>
                                    </div>
                                    <label class="control-label">Material Received By:</label>
                                    <br/>
                                </div>
                                <div class="col-sm-3 text-right">
                                    <!-- <label class="control-label">Material <br /> Issued By:</label> -->
                                    <br/>
                                    <div class="h-60" id="dcNoHere">
                                        <p class="dc-name r-15"><?php echo $requestData['sam_name']; ?></p>
                                    </div>
                                    <label class="control-label">Material Issued By:</label>
                                    <br/>
                                </div>
                                <input type="hidden" id="type" name="type" value="<?php echo $miDetails['type']; ?>">
                            </div>
                        </div>
                    </div>
                    <?php //echo $dc; exit;?>
                    <div class="box-header with-border no-print"><h3></h3></div>
                    <div class="box-body mt-4 no-print">
                        <div class="card">
                            <div class="card-body">
                                <div class="pull-right">
                                    <a type="button" class="btn btn-info ml-5" href="<?php echo base_url(); ?>company/mcaduser/caddclist">Back</a>
                                    
                                      <?php if ($usertype == 5) { ?>
                                       <button type="button" class="btn btn-info" id="getValues">Save</button>&nbsp;&nbsp;&nbsp;&nbsp;
                                      <?php } ?>
                                   
                                    <a type="button" class="btn btn-info" id="print">Print</a>

                                    <a type="button" class="btn btn-info" id="printpdf">Generate PDF</a>
                                  
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
        var dc = <?php echo $dc; ?>;
    </script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/jquery.uploadfile.min.js?s=<?php echo str_rand(5) ?>"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/bootstrap-datepicker.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/loadingoverlay.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/ace/node_modules/sweetalert2/dist/sweetalert2.all.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/custom/request/cad/caddclist.js">
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/custom/datepair.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/select2.min.js"></script>