<?php $this->load->view(CNFCOMPANY . 'template/pageheader'); 
$ArrProfileInfo = fnGetUserLoggedInfo(1);
if($BasicSubInfo->invopurchasetype==2){
$confirmbtn='Renewal Confirmed';
}else if($BasicSubInfo->invopurchasetype==3){
$confirmbtn='Package Migration Confirmed';
}else if($BasicSubInfo->invopurchasetype==1){
$confirmbtn='Subscription Confirmed';
}

?>
<style>
.btn-light-lightgrey {
color: #011837!important;
background-color: #ebecec!important;
}
.btn-a-purple:not(:disabled):not(.disabled).active, .show > .btn.btn-a-purple.dropdown-toggle {
    color: #fff!important;
    background-color: #022b61 !important;
    
}
.radius-0 {
    border-radius: 0rem !important;
}
.navs {
    border-bottom: 0!important;
}
.btn-light-lightgrey:hover {
    color: #5a5d62!important;
    background-color: #dddfe1!important;
   
}
.nav-tabs > li.active > a, .nav-tabs > li.active > a:focus, .nav-tabs > li.active > a:hover{
    color: #fff!important;
    background-color: #022b61 !important;
}
.btn-royal-blue-submit , .btn-royal-blue-submit:hover{
    background-color: #008bfe!important;
    color: white!important;
    border-color: #008bfe!important;
    font-weight: 600 !important;
}
.btn-royal-blue {
    color: #022B61!important;
    background-color: #ebecec!important;
    border-color: #D0D1D1!important;
    transition: none !important;
}
.btn-royal-blue:hover {
    color: #fff!important;
    background-color: #011b3e!important;
    border-color: #00142f!important;
}
.btn-text-slide-x {
    position: relative!important;
    overflow: hidden!important;
}
.btn-text-slide-x .btn-text-2 .move-right {
    -webkit-transform: translateX(-100%)!important;
    transform: translateX(-100%)!important;
}
.btn-text-slide-x .btn-text-2 {
    opacity: 0!important;
    letter-spacing: -0.5rem!important;
    max-width: 0%!important;
    white-space: nowrap!important;
    word-break: normal!important;
    display: inline-block!important;
}

.text-120 {
    font-size: 1.2em !important;
}
.btn-text-slide-x .btn-text-2:hover{
    opacity: 1!important;
}
.f-16{
    font-size:16px!important;
}
</style>
   <style>
   .jexcel {
    border-right: 1px solid #f7f7f7 !important;
    border-bottom: 1px solid #f7f7f7 !important; 
    /*border-right: 1px solid #D9D9D9 !important;*/
   }
   
   .jexcel > tbody > tr > td:first-child,.jexcel > thead > tr > td,.jexcel > tfoot > tr > td {
      background-color:#D9D9D9!important;
   }
   .jexcel > thead > tr > td,.jexcel > tbody > tr > td,.jexcel > tfoot > tr > td {
    border: 0.01em solid #f7f7f7 !important;
   }
   .jexcel > tfoot > tr > td{
       height: 37px!important;
   }
   .b-0{
       border-top:none!important;
   }
   .table-responsive {
    overflow-x: unset !important;
}
.jdropdown-focus {
    position: inherit !important;
}
.content{
    padding-top:50px!important;
}
.ord-procs-cell {
    width: 25%;
}

.tbl-procs-border {
    border: 1px solid #ddd!important;
}
.table > tbody > tr > td {
    border-top:0px!important;
}
td.process-value,
td.process-title,
.process-main-value,
td.process-main-head {
    font-size: 12px;
}

td.process-main-heads {
    font-size: 12px;
}

td.process-title {
    background: #f3f3f3;
    width: 25% !important;
    text-align: right;
}
tfoot td:first-child, tfoot td:nth-child(2){
     display: table-cell!important; 
}
td.process-main-head {
    background: #022b61;
    color: #ffffff;
    text-align: center;
}

td.process-main-heads {
    background: #e8e8e8;
    color: #050505;
    text-align: left;
}
.tables{
    margin-bottom: 5px!important;
}
.card-body{
    margin:6px!important;
}
table.table.tbl-procs-border {
    margin-bottom: 0;
}
.table {
    background: #F7F7F7!important;
}
.swal2-content {
    font-size: 18px!important;
}
.swal2-titles {
    color: red!important;
    font-weight: 500!important;
}
.swal2-icon.swal2-warning{
    border-color: #FFCC00!important;
    color: #FFCC00!important;
    border: 2px solid #FFCC00!important;
}
/* .dataTables_paginate .paginate_button.disabled {
    display: none!important;
  } */
   </style>
    <!--<body class="sidebar-mini skin-black wysihtml5-supported sidebar-collapse">-->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.10.23/datatables.min.css"/>
    <body class="layout-top-nav">
<div class="wrapper">
    <?php $this->load->view(CNFCOMPANY . 'template/badmintemplateheader');
    // $this->load->view(CNFCOMPANY . 'template/templateheader'); ?>
    <aside class="main-sidebar hide">
        <?php // $this->load->view(CNFCOMPANY . 'template/templateleftmenu'); ?>
    </aside>
    <div class="content-wrapper">
        <section class="content">
            <section class="invoice form-horizontal">
                <div class="row"> 
                    <div class="col-xs-12">
                        <h2 class="page-header text-royal-black" style="border-bottom:0px"> Subscriber Ref.No: <?php if (!empty($BasicSubInfo->subscriber_refno)) echo $BasicSubInfo->subscriber_refno; ?>
                            <div class="pull-right">
                            <?php if (!empty($BasicSubInfo->proforma_status) && ($BasicSubInfo->proforma_status==3 || $BasicSubInfo->proforma_status==1)) { ?>
                                <button class="btn custbtn btn-sm btn-royal-blue btn-text-slide-x" id="confirmbtn" <?php if($BasicSubInfo->confirm_status==1) { echo 'disabled';}?>><?php echo $confirmbtn;?></button>
                                <?php } ?>
                             <a  id="backbtns" class="btn custbtn btn-sm mx-3 btn-royal-blue btn-text-slide-x">Back</a>
                            </div>
                        <div class="col-sm-12 " style="padding: 5px 25px;border-bottom: 1px solid #022B61;"></div>
                        </h2>
                        <h4 class="mr-2 py-2 text-royal-blue">
                        </h4>
                    </div><!-- /.col -->
                </div>
                <ul class="nav nav-tabs navs">
                  <li class="active"><a id="menu1_id" class="btn btn-light-lightgrey btn-a-purple border-0 radius-0 btn-sm f-13 " data-toggle="tab" href="#menu1">Subscriber Details</a></li>
                  <li><a id="menu2_id" class="btn btn-light-lightgrey btn-a-purple border-0 radius-0 btn-sm f-13 " data-toggle="tab" href="#menu2">Package Details</a></li>
                  <li><a id="menu3_id" class="btn btn-light-lightgrey btn-a-purple border-0 radius-0 btn-sm f-13 "  data-toggle="tab" href="#menu3">Package Wise User Dept. & User Count Allowed</a></li>
                  <li><a id="menu4_id" class="btn btn-light-lightgrey btn-a-purple border-0 radius-0 btn-sm f-13 "  data-toggle="tab" href="#menu4">Dept. Wise User Roles Allowed</a></li>
                  <li><a id="menu6_id" class="btn btn-light-lightgrey btn-a-purple border-0 radius-0 btn-sm f-13 "  data-toggle="tab" href="#menu6">Subscriber User List</a></li>
                  <li><a id="menu5_id" class="btn btn-light-lightgrey btn-a-purple border-0 radius-0 btn-sm f-13 "  data-toggle="tab" href="#menu5">Data Usage Limits</a></li>
                </ul>
        
                <div class="tab-content p-0 border-0">
                    <div id="menu1" class="tab-pane fade in active">
                        <div class="row"> 
                            <div class="col-xs-12">
                                <div class="mt-3 mb-0 pl-0 ml-2 text-royal-blue f-16">View / Edit Subscriber Details 
                                    <div class="pull-right">
                                        <input type="hidden" id="draftstatus" value="<?php if (!empty($BasicInfo->draft_status)) { echo $BasicInfo->draft_status;} else { echo 1; } ?>">
                                        <input type="hidden" id="mrkt_dept_userid" value="<?php if (!empty($BasicInfo->mrkt_dept_userid)) { echo $BasicInfo->mrkt_dept_userid;} else { echo $ArrProfileInfo['id']; } ?>">
                                        <input type="hidden" id="subscriber_id" value="<?php if (!empty($BasicInfo->id)) { echo $BasicInfo->id;} ?>">
                                        <input type="hidden" id="proforma_id" value="<?php if (!empty($BasicSubInfo->pid)) { echo $BasicSubInfo->pid;} ?>">
                                        <input type="hidden" id="proforma_status" value="<?php if (!empty($BasicSubInfo->proforma_status)) { echo $BasicSubInfo->proforma_status;} ?>">
                                        <div class="ml-auto pr-3">
                                        <?php if (!empty($BasicSubInfo->proforma_status) && ($BasicSubInfo->proforma_status==2 || $BasicSubInfo->confirm_status==1)) { ?>
                                        <a id="editEnabledisabled" class="btn custbtn btn-royal-blue btn-sm px-3" disabled>Edit</a>
                                        <?php }else { ?>
                                        <a id="editEnable" class="btn custbtn btn-royal-blue btn-sm px-3" onclick="$('#enqsvbtn').show()">Edit</a>
                                        <?php } ?>
                                    </div>
                                    </div>
                                </div>
                                <div class="col-sm-12 " style="padding:2px 0px;border-bottom: 1px solid #022B61;"></div>
                                <h4 class="mr-2 py-1 text-royal-blue"></h4>
                            </div><!-- /.col -->
                        </div>
                        <div class="row no-rad-form add-form-mar" id="custom_form" style="padding:15px!important; margin:0px!important;">
                                <div class="col-md-4">
                                    <div class="">
                                        <div class="form-group">
                                                <label for="id-form-field-focus-1" class="col-sm-5 control-label">
                                                    Subscriber Name <span class="mandatory">*</span>
                                                </label>
                                            <div class="col-sm-7">
                                                <input type="text" class="form-control" id="companyname" value="<?php if (!empty($BasicSubInfo->invocmpnyname)) echo $BasicSubInfo->invocmpnyname; ?>" placeholder="Free Text">
                                                <div class="herr" id="Errcompanyname"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="">
                                        <div class="form-group">
                                                <label for="id-form-field-focus-1" class="col-sm-5 control-label">
                                                    Business Type <span class="mandatory">*</span>
                                                </label>
                                            <div class="col-sm-7">
                                                <select class="cus-sel form-control js-example-basic-single" id="businesstype">
                                                        <option value="" selected disabled hidden>Select</option>
                                                        <?php
                                                        $business_type = unserialize(ARRBUSINESSTYPE);
                                                        foreach ($business_type as $key => $item)
                                                        {
                                                            ?>
                                                            <option value="<?php echo $key ?>" <?php if (@$BasicSubInfo->invobusinesstype == $key) { echo "selected";} ?>>
                                                                <?php echo $item ?>
                                                            </option>
                                                            <?php
                                                        }
                                                        ?>
                                                    </select> 
                                                <div class="herr" id="Errbusinesstype"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="">
                                        <div class="form-group">
                                            <label for="id-form-field-focus-1" class="col-sm-5 control-label">
                                               Contact Person <span class="mandatory">*</span>
                                            </label>
                                            <div class="col-sm-7">
                                                <input type="text" id="contactperson" name="contactperson" class="form-control" placeholder="Free Text" value="<?php if (!empty($BasicSubInfo->invocontactperson)) echo $BasicSubInfo->invocontactperson; ?>">
                                                <div class="herr" id="Errcontactperson"></div>
                                            </div>
                                        </div>
                                    </div>
                            		<div class="">
                                        <div class="form-group">
                                                <label for="id-form-field-focus-1" class="col-sm-5 control-label">
                                                   Designation <span class="mandatory">*</span>
                                                </label>
                                            
                                            <div class="col-sm-7">
                                                <input type="text" name="designation" id="designation" class="form-control" placeholder="Free Text" value="<?php if (!empty($BasicSubInfo->invodesignation)) echo $BasicSubInfo->invodesignation; ?>">
                                                <div class="herr" id="Errdesignation"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="">
                                        <div class="form-group">
                                               <label for="id-form-field-focus-1" class="col-sm-5 control-label">
                                                E-mail ID <span class="mandatory">*</span>
                                                </label>
                                            <div class="col-sm-7">
                                                 <input type="text" id="email_id" name="email_id" class="form-control" placeholder="Free Text" value="<?php if (!empty($BasicSubInfo->invoemail_id)) echo $BasicSubInfo->invoemail_id; ?>">
                                                <div class="herr" id="Erremail_id"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="">
                                        <div class="form-group">
                                               <label for="id-form-field-focus-1" class="col-sm-5 control-label">
                                                Registered Mobile No. <span class="mandatory">*</span>
                                                </label>
                                            <div class="col-sm-7">
                                                 <input type="text" id="mobile_no" name="mobile_no" onkeypress="return onlyNumbernodecimal(event);"  class="form-control" placeholder="Free Text" value="<?php if (!empty($BasicSubInfo->invomobile_no)) echo $BasicSubInfo->invomobile_no; ?>">
                                                <div class="herr" id="Errmobile_no"></div>
                                            </div>
                                        </div>
                                    </div>
                            		<div class="">
                                        <div class="form-group">
                                               <label for="id-form-field-focus-1" class="col-sm-5 control-label">
                                               GST No.<span class="mandatory">*</span>
                                                </label>
                                            <div class="col-sm-7">
                                                 <input type="text" id="gst_no" name="gst_no" onkeyup="CaseConvert(this.id,'all','upper')" class="form-control" placeholder="Free Text" value="<?php if (!empty($BasicSubInfo->invogst_no)) echo $BasicSubInfo->invogst_no; ?>">
                                                <div class="herr" id="Errgst_no"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="">
                                        <div class="form-group">
                                                <label for="id-form-field-focus-1" class="col-sm-5 control-label">
                                                    Address <span class="mandatory">*</span>
                                                </label>
                                            <div class="col-sm-7">
                                                <textarea id="address"  name="address" rows="5" class="form-control" placeholder="Free Text"><?php if (!empty($BasicSubInfo->invoaddress)) echo $BasicSubInfo->invoaddress; ?></textarea>
                                                <div class="herr" id="Erraddress"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="">
                                            <div class="form-group">
                                                <label for="id-form-field-focus-1" class="col-sm-6 control-label">
                                                    City <span class="mandatory">*</span>
                                                </label>
                                                <div class="col-sm-6">
                                                    <input type="text" id="city"  name="city" class="form-control" placeholder="Free Text" value="<?php if (!empty($BasicSubInfo->invocity)) echo $BasicSubInfo->invocity; ?>">
                                                    <div class="herr" id="Errcity"></div>
                                                </div>
                                            </div>
                                     </div>
                                    <div class="">
                                            <div class="form-group">
                                                <label for="id-form-field-focus-1" class="col-sm-6 control-label">
                                                    State <span class="mandatory">*</span>
                                                </label>
                                                <div class="col-sm-6">
                                                    <input type="text" id="state"  name="state" class="form-control" placeholder="Free Text" value="<?php if (!empty($BasicSubInfo->invostate)) echo $BasicSubInfo->invostate; ?>">
                                                    <div class="herr" id="Errstate"></div>
                                                </div>
                                            </div>
                                        </div>
                                    <div class="">
                                       <div class="form-group">
                                            <label for="id-form-field-focus-1" class="col-sm-6 control-label">
                                                Country <span class="mandatory">*</span>
                                            </label>
                                            <div class="col-sm-6">
                                                <input type="text" id="country"  name="country" class="form-control" placeholder="Free Text" value="<?php if (!empty($BasicSubInfo->invocountry)) echo $BasicSubInfo->invocountry; ?>">
                                                <div class="herr" id="Errcountry"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="">
                                        <div class="form-group">
                                            <label for="id-form-field-focus-1" class="col-sm-6 control-label">
                                                ZIP / Pin Code <span class="mandatory">*</span>
                                            </label>
                                            <div class="col-sm-6">
                                            <input type="text" id="pincode" name="pincode" onkeypress="return onlyNumbernodecimal(event);" class="form-control" placeholder="Free Text" value="<?php if (!empty($BasicSubInfo->invopincode)) echo $BasicSubInfo->invopincode; ?>">
                                            <div class="herr" id="Errpincode"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="">
                                        <div class="form-group">
                                            <label for="id-form-field-focus-1" class="col-sm-6 control-label">
                                                Subscription Category <span class="mandatory">*</span>
                                            </label>
                                            <div class="col-sm-6">
                                                <select class="cus-sel form-control js-example-basic-single" id="subscription_category">
                                                    <option value="" selected disabled hidden>Select</option>
                                                    <?php
                                                    $subscription_category = unserialize(ARRSUBSCCATEGORY);
                                                    foreach ($subscription_category as $key => $item)
                                                    {
                                                        ?>
                                                        <option value="<?php echo $key ?>" <?php if (@$BasicInfo->subscription_category == $key) { echo "selected";} ?>>
                                                            <?php echo $item ?>
                                                        </option>
                                                        <?php
                                                    }
                                                    ?>
                                                </select> 
                                                <div class="herr" id="Errsubscription_category"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="">
                                        <div class="form-group">
                                            <label for="id-form-field-focus-1" class="col-sm-6 control-label">
                                            Package Details <span class="mandatory">*</span>
                                            </label>
                                            <div class="col-sm-6">
                                                <select class="cus-sel form-control js-example-basic-single" id="package_id">
                                                    <option value="" selected disabled hidden>Select</option>
                                                    <?php
                                                    
                                                    foreach ($ArrPackage as $key => $item)
                                                    {
                                                        ?>
                                                         <option value="<?php echo $item['id'] ?>" <?php if (@$BasicSubInfo->invopackageid == $item['id']) { echo "selected";} ?>><?php echo $item['description'] ?></option>
                                                        <?php
                                                    }
                                                    ?>
                                                </select> 
                                                <div class="herr" id="Errpackage_id"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="">
                                         <div class="form-group">
                                            <label for="id-form-field-focus-1" class="col-sm-6 control-label">
                                                Purchase Type <span class="mandatory">*</span>
                                            </label>
                                            <div class="col-sm-6">
                                                <select class="cus-sel form-control js-example-basic-single" id="purchasetype">
                                                    <option value="" selected disabled hidden>Select</option>
                                                    <?php
                                                    $purchase_type = unserialize(ARRPURCHASETYPE);
                                                    foreach ($purchase_type as $key => $item)
                                                    {
                                                    ?>
                                                        <option value="<?php echo $key ?>" <?php if (@$BasicSubInfo->invopurchasetype == $key) { echo "selected";} ?>>
                                                            <?php echo $item ?>
                                                        </option>
                                                        <?php
                                                    }
                                                    ?>
                                                </select> 
                                                <div class="herr" id="Errpurchasetype"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="">
                                        <div class="form-group">
                                           <label for="id-form-field-focus-1" class="col-sm-6 control-label">
                                           No. of Users (Package)
                                            </label> 
                                            <div class="col-sm-6">
                                                <input type="hidden" id="no_of_users_chargeable" name="no_of_users_chargeable"  class="form-control" placeholder="Auto Update" value="<?php if (!empty($BasicSubInfo->no_of_users)) echo $BasicSubInfo->no_of_users; ?>">
                                                <input type="text" id="no_of_users" name="no_of_users"  class="form-control" placeholder="Auto Update" value="<?php if (!empty($BasicSubInfo->no_of_users)) echo $BasicSubInfo->no_of_users; ?>" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="">
                                        <div class="form-group">
                                           <label for="id-form-field-focus-1" class="col-sm-6 control-label">
                                          Data Storage Limit (Package)
                                            </label>
                                            <div class="col-sm-6">
                                                 <input type="text" id="data_limit" name="data_limit" class="form-control" placeholder="Auto Update" value="<?php if (!empty($BasicSubInfo->data_limit)) echo $BasicSubInfo->data_limit; ?>" readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                        <div class="">
                                            <div class="form-group">
                                                <label for="id-form-field-focus-1" class="col-sm-7 control-label">
                                                    File Storage Limit (Package)
                                                </label>
                                                <div class="col-sm-5">
                                                <input type="text" class="form-control" id="file_limit" readonly placeholder="Auto Update" value="<?php if (!empty($BasicSubInfo->file_limit)) echo $BasicSubInfo->file_limit; ?>">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="">
                                            <div class="form-group">
                                                <label for="id-form-field-focus-1" class="col-sm-7 control-label">
                                                    No. of Additional Users (Chargeable) <span class="mandatory">*</span>
                                                </label>
                                                <div class="col-sm-5">
                                                    <input type="hidden" class="form-control" id="additional_users_chargeable" value="<?php if (!empty($BasicSubInfo->invo_additional_users)) echo $BasicSubInfo->invo_additional_users; ?>">
                                                    <input type="text" class="form-control" id="additional_users"  placeholder="Free Text" value="<?php if (!empty($BasicSubInfo->invo_additional_users)) echo $BasicSubInfo->invo_additional_users; ?>">
                                                    <div class="herr" id="Erradditional_users"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="">
                                            <div class="form-group">
                                                <label for="id-form-field-focus-1" class="col-sm-7 control-label">
                                                    Add. Data Storage Limit (Chargeable) <span class="mandatory">*</span>
                                                </label>
                                                <div class="col-sm-5">
                                                    <select class="cus-sel form-control js-example-basic-single" id="data_storage_limit">
                                                        <option value="" selected disabled hidden>Select</option>
                                                        <?php
                                                        $data_storage_limit = unserialize(ARRFILESTORAGE);
                                                        foreach ($data_storage_limit as $key => $item) { ?>
                                                        <option value="<?php echo $key ?>" <?php if (@$BasicSubInfo->invo_data_storage_limit == $key) { echo "selected";} ?>><?php echo $item ?></option>
                                                        <?php } ?>
                                                    </select> 
                                                    <div class="herr" id="Errdata_storage_limit"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="">
                                            <div class="form-group">
                                                <label for="id-form-field-focus-1" class="col-sm-7 control-label">
                                                    Add. File Storage Limit (Chargeable) <span class="mandatory">*</span>
                                                </label>
                                                <div class="col-sm-5">
                                                    <select class="cus-sel form-control js-example-basic-single" id="file_storage_limit">
                                                        <option value="" selected disabled hidden>Select</option>
                                                        <?php
                                                        $file_storage_limit = unserialize(ARRFILESTORAGE);
                                                        foreach ($file_storage_limit as $key => $item)
                                                        {
                                                            ?>
                                                            <option value="<?php echo $key ?>" <?php if (@$BasicSubInfo->invo_file_storage_limit == $key) { echo "selected";} ?>>
                                                                <?php echo $item ?>
                                                            </option>
                                                            <?php
                                                        }
                                                        ?>
                                                    </select> 
                                                    <div class="herr" id="Errfile_storage_limit"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="">
                                            <div class="form-group">
                                                <label for="id-form-field-focus-1" class="col-sm-7 control-label">
                                                    Request Raised By
                                                </label>
                                                <div class="col-sm-5">
                                                    <input type="text" id="request_raised_by" name="request_raised_by" class="form-control" placeholder="Auto Update" value="<?php if (!empty($BasicInfo->request_raised_by) && ($BasicInfo->requeststatus!=0)) echo $BasicInfo->request_raised_by; ?>" readonly>
                                                    <div class="herr" id="Errrequest_raised_by"></div> 
                                                </div>
                                            </div>
                                        </div>
                                        <div class="">
                                            <div class="form-group">
                                                <label for="id-form-field-focus-1" class="col-sm-7 control-label">
                                                    Request Date & Time
                                                </label>
                                                <div class="col-sm-5">
                                                    <input type="text" id="request_datetime" name="request_datetime" class="form-control" placeholder="Auto Update" value="<?php if (!empty($BasicInfo->reqdatetime)) echo $BasicInfo->reqdatetime; ?>" readonly>
                                                    <div class="herr" id="Errrequest_datetime"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="">
                                            <div class="form-group">
                                                <label for="id-form-field-focus-1" class="col-sm-7 control-label">
                                                    Request Status
                                                </label>
                                                <div class="col-sm-5">
                                                     <input type="text" id="requeststatus" name="requeststatus" class="form-control" placeholder="Auto Update" value="<?php if (isset($BasicInfo->requeststatus)) echo $ArrReqStatus[$BasicInfo->requeststatus]; ?>" readonly>
                                                     <input type="hidden" id="request_status" name="request_status" class="form-control"  value="<?php if (isset($BasicInfo->requeststatus)) echo $BasicInfo->requeststatus; ?>">
                                                    <div class="herr" id="Errrequest_status"></div>
                                                </div>
                                            </div>
                                        </div>
                                       <div class="">
                                            <div class="form-group">
                                                <label for="id-form-field-focus-1" class="col-sm-7 control-label">
                                                    Status Updated By
                                                </label>
                                                <div class="col-sm-5">
                                                     <input type="text" id="status_updatedby" name="status_updatedby" class="form-control" placeholder="Auto Update" value="<?php if (!empty($BasicInfo->status_updatedby)) echo $BasicInfo->status_updatedby; ?>" readonly>
                                                    <div class="herr" id="Errstatus_updatedby"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="">
                                            <div class="form-group">
                                                <label for="id-form-field-focus-1" class="col-sm-7 control-label">
                                                   Status Updated Date & Time
                                                </label>
                                                <div class="col-sm-5">
                                                    <input type="text" id="status_updated_datetime" name="status_updated_datetime" class="form-control" placeholder="Auto Update" value="<?php if (!empty($BasicInfo->status_updated_datetime)) echo $BasicInfo->status_updated_datetime; ?>" readonly>
                                                    <div class="herr" id="Errstatus_updated_datetime"></div>
                                                </div>
                                            </div>
                                        </div>
                                </div>
                        </div>
                        <div class="col-12 bgc-white pt-3">
                            <div class="row">
                                <div class="col-sm-12 col-form-label text-sm-left">
                                    <label for="id-form-field-focus-1">
                                        Remarks
                                    </label>
                                </div>
                                <div class="col-sm-12">
                                    <textarea id="remarks" style="height: 76px !important; padding: 20px 22px;border-radius:0.125rem !important
                                              " name="remarks"   class="form-control" placeholder="Free Text"><?php echo (isset($BasicSubInfo->invoremarks) && $BasicSubInfo->invoremarks!='undefined') ? $BasicSubInfo->invoremarks : '' ?></textarea>
                                    <div class="herr" id="Errremarks"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12 py-3" style="padding-right:16px">
                                <button class="btn btn-info pull-right" id="enqsvbtn" onclick="return fnSave();">Save</button>
                            </div>
                        </div>
                        <div class="col-12 text-right pl-0 py-3" style="border-bottom: 1px solid #022B61;">
                            <button class="btn btn-sm btn-royal-blue btn-text-slide-x px-3 pag-active">1</button>
                            <span class="mar-lr-10 bf-af-pg">...</span>
                            <button role="tab" aria-controls="menu" onclick='$("#menu2_id").trigger("click")' href="#menu2" class="btn btn-sm btn-royal-blue btn-text-slide-x">
                                Next <i class="btn-text-2 move-right fa fa-arrow-right text-120 align-text-bottom mr-2"></i>
                            </button>
                        </div>
                        <div class="row">
                            <div class="col-xs-12 " style="padding-right:30px;margin: 20px 0px 20px 0px;"></div>
                        </div>    
                    </div>
                    <div id="menu2" class="tab-pane fade">
                        <div class="mt-3 mb-2 pl-0 ml-2 text-royal-blue f-16">Package Details</div>
                        <div class="p-0 w-100 package_details" id="package_details"></div>
                        <div class="col-12 text-right pl-2 py-3" style="border-bottom: 1px solid #022B61;">
                            <button role="tab" onclick='$("#menu1_id").trigger("click")' class="btn btn-sm btn-royal-blue btn-text-slide-x">
                                <i class="btn-text-2 move-left fa fa-arrow-left text-120 align-text-bottom ml-3"></i> Previous
                            </button>
                            <span class="mar-lr-10 bf-af-pg">...</span>
                            <button class="btn btn-sm btn-royal-blue btn-text-slide-x px-3 pag-active">2</button>
                            <span class="mar-lr-10 bf-af-pg">...</span>
                            <button role="tab" aria-controls="menu3" onclick='$("#menu3_id").trigger("click")' href="#menu3" class="btn btn-sm btn-royal-blue btn-text-slide-x">
                                Next <i class="btn-text-2 move-right fa fa-arrow-right text-120 align-text-bottom mr-2"></i>
                            </button>
                        </div>
                        <div class="row">
                            <div class="col-xs-12 " style="padding-right:30px;margin: 20px 0px 20px 0px;"></div>
                        </div>
                    </div>
                    <div id="menu3" class="tab-pane fade">
                            <div class="mt-3 mb-2 pl-0 ml-2 text-royal-blue f-16">Package Wise User Dept. & User Count Allowed
                                <div class="pull-right mb-3">
                                    <?php if(isset($UserwisepackInfo) && !empty($UserwisepackInfo)) { ?>
                                    <div class="ml-auto  pr-3">
                                    <?php if (!empty($BasicSubInfo->proforma_status) && ($BasicSubInfo->proforma_status==2||$BasicSubInfo->confirm_status==1)) { ?>
                                    <a id="editbtndisabled" class="btn custbtn btn-royal-blue btn-sm px-3" disabled>Edit</a>
                                    <?php } else {?>
                                    <a id="editbtn" class="btn custbtn btn-royal-blue btn-sm px-3">Edit</a>
                                    <?php }?>
                                    </div>
                                    <?php } ?>
                                    <input type="hidden" id="editpckvariable" value="<?php  echo (isset($UserwisepackInfo) && !empty($UserwisepackInfo)) ? '2' : '1';?>">
                                </div>
                            </div>
                            <div class="p-0 w-100 userwise_package_details" id="userwise_package_details"></div>
                            <div class="col-12 text-right pl-2 py-3" style="border-bottom: 1px solid #022B61;">
                                <button role="tab" onclick='$("#menu2_id").trigger("click")' class="btn btn-sm btn-royal-blue btn-text-slide-x">
                                    <i class="btn-text-2 move-left fa fa-arrow-left text-120 align-text-bottom ml-3"></i> Previous
                                </button>
                                <span class="mar-lr-10 bf-af-pg">...</span>
                                <button class="btn btn-sm btn-royal-blue btn-text-slide-x px-3 pag-active">3</button>
                                <span class="mar-lr-10 bf-af-pg">...</span>
                                <button role="tab" aria-controls="menu4" onclick='$("#menu4_id").trigger("click")' href="#menu4" class="btn btn-sm btn-royal-blue btn-text-slide-x">
                                    Next <i class="btn-text-2 move-right fa fa-arrow-right text-120 align-text-bottom mr-2"></i>
                                </button>
                                <button class="btn btn-sm btn-royal-blue-submit access_permission mar-l-5rem" id="userwise_packagebtn" disabled="true">Save</button>
                            </div>
                            <div class="row">
                                <div class="col-xs-12 " style="padding-right:30px;margin: 20px 0px 20px 0px;"></div>
                            </div>
                    </div>
                    <div id="menu4" class="tab-pane fade">
                        <div class="row"> 
                            <div class="col-xs-12">
                                <div class="mt-3 mb-0 pl-0 ml-2 text-royal-blue f-16">Dept. Wise User Roles Allowed
                                    <div class="pull-right" style="font-weight:normal">
                                        <div class="btn-group mr-2v text-right" role="group" aria-label="First group">
                                <!-- <i class="fa fa-search-plus btn  btn-royal-blue" style="padding: 6px 12px;font-size: 16px;" onclick="$(this).toggleClass('fa-search-plus fa-search');$('.search_area').toggleClass('hide');"></i> -->
                            </div>
                            <div class="btn-group px-3" role="group" aria-label="Third group">
                                <select name="frmdepItemStatus" title="activate / deactivate" id="frmdepItemStatus" class="input-sm form-control  js-example-basic-single-no-search nrml-slt-inp-sts" <?php if (!empty($BasicSubInfo->proforma_status) && ($BasicSubInfo->proforma_status==2 || $BasicSubInfo->confirm_status==1)) { echo 'disabled';} ?>>
                                    <option value="">Select</option>
                                    <option value="1">Active</option>
                                    <option value="2">Inactive</option>
                                </select>
                            </div>
                            <div class="btn-group mr-2v text-right" role="group" aria-label="Second group">
                                <button name="deptbtnChangeStatus" id="deptbtnChangeStatus" class="btn btn-sm btn-royal-blue" <?php if (!empty($BasicSubInfo->proforma_status) && ($BasicSubInfo->proforma_status==2 || $BasicSubInfo->confirm_status==1)) { echo 'disabled';} ?>>
                                    Update
                                </button>
                            </div>
                                    </div>
                                </div>
                                <div class="col-sm-12 " style="padding:2px 0px;"></div>
                            </div><!-- /.col -->
                        </div>
                        <!--<div class="col-sm-12" style="display: block; padding: 10px 20px 10px 20px;">-->
                        <!--<span class="header-title text-royal-blue" style="margin-left:0px!important"><b class="f-16" style="font-family: Arial">Dept. Wise User Roles Allowed</b></span>-->
                        <!--<div class="btn-toolbar pull-right " style="padding-top: 4px" role="toolbar" aria-label="Toolbar with button groups">-->
                        <!--    <div class="btn-group mr-2v text-right" role="group" aria-label="First group">-->
                        <!--        <i class="fa fa-search-plus btn  btn-royal-blue" style="padding: 6px 12px;font-size: 16px;" onclick="$(this).toggleClass('fa-search-plus fa-search');$('.search_area').toggleClass('hide');"></i>-->
                        <!--    </div>-->
                        <!--    <div class="btn-group px-3" role="group" aria-label="Third group">-->
                        <!--        <select name="frmdepItemStatus" title="activate / deactivate" id="frmdepItemStatus" class="input-sm form-control  js-example-basic-single-no-search nrml-slt-inp-sts">-->
                        <!--            <option value="">Select</option>-->
                        <!--            <option value="1">Active</option>-->
                        <!--            <option value="2">Inactive</option>-->
                        <!--        </select>-->
                        <!--    </div>-->
                        <!--    <div class="btn-group mr-2v text-right" role="group" aria-label="Second group">-->
                        <!--        <button name="deptbtnChangeStatus" id="deptbtnChangeStatus" class="btn btn-sm btn-royal-blue">-->
                        <!--            Update-->
                        <!--        </button>-->
                        <!--    </div>-->
                        <!--</div>-->
                        <!--</div>-->
                        <!--<div class="px-4 w-90" style="margin-left: 3px; width: 99.8%;">-->
                        <!--    <div class="col-sm-12 px-4" style="border-bottom: 1px solid #022B61;"></div>-->
                        <!--</div>-->
                        <div class="p-0 w-100 deptwise_role_detail" id="deptwise_role_detail" style="border-top: 1px solid #022B61!important;">
                                <div class="row">
                                <div class="col-md-12">
                                    <div class="box box-info">
                                    <div class="box-body no-padding">
                                        <table id="tableId" class="table table-bordered table-hover" data-page-length="50" style="padding: 0 2px; border-bottom: 1px solid #022B61!important;">
                                            <thead>
                                            <tr>
                                                <th  class="no-sort"></th>
                                                <th id="0">User Department</th>
                                                <th id="1">Status</th>
                                                <th >Department Wise User Roles Allowed </th>
                                            </tr>
                                            </thead>
                                        </table>
                                    </div>
                                </div>
                                </div><!-- /.col -->
                                <div class="col-12 text-right pl-2 py-3 mx-4" style="border-bottom: 1px solid #022B61;">
                                    <button role="tab"
                                            onclick='$("#menu3_id").trigger("click")'
                                            class="btn btn-sm btn-royal-blue btn-text-slide-x">
                                        <i class="btn-text-2 move-left fa fa-arrow-left text-120 align-text-bottom ml-3"></i> Previous
                                    </button>
                                    <span class="mar-lr-10 bf-af-pg">...</span>
                                    <button class="btn btn-sm btn-royal-blue btn-text-slide-x px-3 pag-active">4</button>
                                    <span class="mar-lr-10 bf-af-pg">...</span>
                                    <button role="tab" aria-controls="menu6"
                                            onclick='$("#menu6_id").trigger("click")'
                                            href="#menu6" class="btn btn-sm btn-royal-blue btn-text-slide-x">
                                        Next <i class="btn-text-2 move-right fa fa-arrow-right text-120 align-text-bottom mr-2"></i>
                                    </button>
                           
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12 " style="padding-right:30px;margin: 20px 0px 20px 0px;"></div>
                        </div>
                    </div>
                    <div id="menu6" class="tab-pane fade">
                        <div class="row"> 
                            <div class="col-xs-12">
                                <div class="mt-3 mb-0 pl-0 ml-2 text-royal-blue f-16">Subscriber User List
                                    <div class="pull-right" style="font-weight:normal">
                                        <div class="btn-group mr-2v text-right" role="group" aria-label="First group">
                                <!-- <i class="fa fa-search-plus btn  btn-royal-blue" style="padding: 6px 12px;font-size: 16px;" onclick="$(this).toggleClass('fa-search-plus fa-search');$('.search_area').toggleClass('hide');"></i> -->
                            </div>
                            <div class="btn-group px-3" role="group" aria-label="Third group">
                                <select name="frmuserItemStatus" title="activate / deactivate" id="frmuserItemStatus" <?php if (!empty($BasicSubInfo->proforma_status) && ($BasicSubInfo->proforma_status==2||$BasicSubInfo->confirm_status==1)) { echo 'disabled';} ?> class="input-sm form-control js-example-basic-single-no-search nrml-slt-inp-sts">
                                    <option value="">Select</option>
                                    <option value="1">Active</option>
                                    <option value="2">Inactive</option>
                                </select>
                            </div>
                            <div class="btn-group mr-2v text-right" role="group" aria-label="Second group">
                                <button name="userChangeStatus" id="userChangeStatus" class="btn btn-sm btn-royal-blue" <?php if (!empty($BasicSubInfo->proforma_status) && ($BasicSubInfo->proforma_status==2 || $BasicSubInfo->confirm_status==1)) { echo 'disabled';} ?>>
                                    Update
                                </button>
                            </div>
                                    </div>
                                </div>
                                <div class="col-sm-12 " style="padding:2px 0px;"></div>
                            </div><!-- /.col -->
                        </div>
                        
                        <div class="p-0 w-100 subscriber_user_list" id="subscriber_user_list" style="border-top: 1px solid #022B61!important;">
                                <div class="row">
                                <div class="col-md-12">
                                    <div class="box box-info">
                                    <div class="box-body no-padding">
                                        <table id="tableIds" class="table table-bordered table-hover" data-page-length="50" style="padding: 0 2px; border-bottom: 1px solid #022B61!important;">
                                            <thead>
                                            <tr>
                                                <th  class="no-sort"></th>
                                                <th id="0">Department</th>
                                                <th id="1">User Count</th>
                                                <th id="2">Designation</th>
                                                <th id="3">User Name</th>
                                                <th id="4">Log-in ID</th>
                                                <th id="5">Email ID</th>
                                                <th id="6">Mobile No.</th>
                                                <th id="7">User Admin Status</th>
                                                <th id="8">Business Admin Status</th>
                                                <th id="9">Updated By</th>
                                                <th id="10">Recent Update</th>
                                                <th>Action</th>
                                            </tr>
                                            </thead>
                                        </table>
                                    </div>
                                </div>
                                </div><!-- /.col -->
                                <div class="col-12 text-right pl-2 py-3 mx-4" style="border-bottom: 1px solid #022B61;">
                                    <button role="tab"
                                            onclick='$("#menu4_id").trigger("click")'
                                            class="btn btn-sm btn-royal-blue btn-text-slide-x">
                                        <i class="btn-text-2 move-left fa fa-arrow-left text-120 align-text-bottom ml-3"></i> Previous
                                    </button>
                                    <span class="mar-lr-10 bf-af-pg">...</span>
                                    <button class="btn btn-sm btn-royal-blue btn-text-slide-x px-3 pag-active">5</button>
                                    <span class="mar-lr-10 bf-af-pg">...</span>
                                    <button role="tab" aria-controls="menu4"
                                            onclick='$("#menu5_id").trigger("click")'
                                            href="#menu5" class="btn btn-sm btn-royal-blue btn-text-slide-x">
                                        Next <i class="btn-text-2 move-right fa fa-arrow-right text-120 align-text-bottom mr-2"></i>
                                    </button>
                           
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12 " style="padding-right:30px;margin: 20px 0px 20px 0px;"></div>
                        </div>
                    </div>
                    <div id="menu5" class="tab-pane fade">
                        <div class="mt-3 mb-2 pl-0 ml-2 text-royal-blue f-16">Data Usage Limits</div>
                        <div class="p-0 w-100 usage_limits" id="usage_limits"></div>
                        <div class="row">
                            <div class="col-xs-12 " style="padding-right:30px;margin: 20px 0px 20px 0px;"></div>
                        </div>
                    </div>
                </div>
        </section>     
    </section>
    </div>
    <?php $this->load->view(CNFCOMPANY . 'template/templatefooter'); ?>
    <div class="control-sidebar-bg"></div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/vue/2.6.10/vue.min.js"></script>
<script src="<?= base_url() ?>assets/js/jexcelNew/jexcel.js"></script>
<script src="<?= base_url() ?>assets/js/jexcelNew/jsuites.js"></script>
<link rel="stylesheet" href="<?= base_url() ?>assets/css/jexcelNew/jspreadsheet.css" type="text/css" />
<link rel="stylesheet" href="<?= base_url() ?>assets/css/jexcelNew/jsuites.css" type="text/css" />
<script src="<?= base_url() ?>assets/ace/node_modules/interactjs/dist/interact.js"></script>
<script src="<?php echo base_url();?>assets/js/jexcel/numeral.min.js"></script>
<script src="<?php echo base_url() ?>assets/js/<?php echo CNFBADMIN ?>subscription.js"></script>
<script src="<?php echo base_url() ?>assets/js/<?php echo CNFBADMIN ?>subscription_userrole.js"></script>
<script src="<?php echo base_url() ?>assets/js/<?php echo CNFBADMIN ?>subscription_userlist.js"></script>

<script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.10.23/datatables.min.js"></script>
<script src="<?php echo base_url();?>assets/js/datatables.min.js"></script>

<script type="text/javascript">
    var GlbId = "<?php echo $VarId; ?>";
    var lasturi='<?php echo $Edit?>';
    var draftstatus=$('#draftstatus').val();
    var reqstatus=$('#request_status').val();
    var editpcksvar=$('#editpckvariable').val();
    if(editpcksvar==2){
        $('#userwise_packagebtn').hide();
    } else { 
       $('#userwise_packagebtn').show();
    }
    if(reqstatus==1){
        $('#editEnable').hide();
       
    }
    if(lasturi=='detviews' && draftstatus==2){
        $('#enqsvbtn').hide();
        $('#cleardraft').hide();
        $('#savedraft').hide();
        
        $("#custom_form input").prop("disabled", true);
        $("#custom_form select").prop("disabled", true);
        // $("#custom_form textarea").prop("disabled", true);
        $("textarea").prop("disabled", true);
    } else if(lasturi=='detviews' && draftstatus==1){ 
       $('#enqsvbtn').show();
       $('#cleardraft').show();
       $('#savedraft').show();
       $("#custom_form input").prop("disabled", false);
        $("#custom_form select").prop("disabled", false);
        // $("#custom_form textarea").prop("disabled", true);
        $("textarea").prop(false); 
       
    }else{
        
         $('#cleardraft').hide();
         $('#savedraft').hide();
    }
</script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
    // var customStyles = `
    //   #home  {
    //     background-color: lightblue;
    //     color: darkblue;
    //   }
    // `;
     var customStyles = `#menu2 .jexcel > tbody > tr > td {
       
         background-color:#f3f3f3;
    }`;

    var styleElement = document.createElement('style');
    styleElement.type = 'text/css';
    styleElement.appendChild(document.createTextNode(customStyles));
    document.head.appendChild(styleElement);
  });
  document.addEventListener('DOMContentLoaded', function () {
    // Add event listener for Bootstrap tab change
    $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
        // Get all checkboxes within the current tab content
        const tabPane = $(e.target).attr('href'); // The ID of the activated tab
        deselectCheckboxes();
        deselectCheckboxess();
        $(tabPane).find($('#frmdepItemStatus').prop('selectedIndex',0));
        $(tabPane).find($('#frmuserItemStatus').prop('selectedIndex',0));
    });
});
//   $(function() { 
//     $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
//         localStorage.setItem('focustab', $(e.target).attr('href'));
//     });

//     var focustab = localStorage.getItem('focustab');
//     if (focustab) {
//         $('[href="' + focustab + '"]').tab('show');
//     }
// });
</script>
 <script>
    // Function to save the last active tab to localStorage
    function saveLastActiveTab(tabId) {
      localStorage.setItem('lastActiveTab', tabId);
    }

    // Function to restore the last active tab
    function restoreLastActiveTab() {
      const currentUrl = window.location.href; // Get the current URL
      const specificUrl = base_path + GlbBAdminFdr +  'msubscription/manage';// Define the specific URL

      if (currentUrl === specificUrl) {
        // If the URL matches, activate the first tab
        $('a[data-toggle="tab"]').first().tab('show');
      } else {
        // Otherwise, restore the last active tab
        const lastActiveTab = localStorage.getItem('lastActiveTab');
        if (lastActiveTab) {
          $('a[href="' + lastActiveTab + '"]').tab('show');
        } else {
          // Default to the first tab if no last active tab is found
          $('a[data-toggle="tab"]').first().tab('show');
        }
      }
    }

    // Track tab switching and save to localStorage
    $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
      const currentTab = e.target.getAttribute('href'); // Get the href of the current tab
      saveLastActiveTab(currentTab); // Save it to localStorage
    });

    // On page load, restore the last active tab or set the first tab active for the specific URL
    $(document).ready(function () {
      restoreLastActiveTab();
    });
      // Back button click handler
      $('#backbtns,#sublistbtns').on('click', function () {
      // Remove the last active tab from localStorage
      localStorage.removeItem('lastActiveTab');
      var urlsegment=encodeURIComponent(base64_encode('<?php echo $BasicSubInfo->subscriber_id;?>'));
      let redirectpath = base_path + GlbBAdminFdr +  'msubscription/detview/'+urlsegment;
                 window.location.href = redirectpath;

    });
  </script>
<script type="text/javascript">

var GlbsubscriberId = "<?php if (!empty($BasicSubInfo->subscriber_id)) echo $BasicSubInfo->subscriber_id; ?>";
var GlbproformaId = "<?php if (!empty($BasicSubInfo->pid)) echo $BasicSubInfo->pid; ?>";
 showpackagedetail(GlbsubscriberId);
 showuserwisepackagedetail(GlbsubscriberId);
 function showuserwisepackagedetail(subscriber_id){
      $("#userwise_package_details").html("");
        MakeAsynPostRequest(base_path + "badmin/msubscription/showpackagewiseuserdetails", "subscriber_id=" + GlbsubscriberId + "&proforma_id=" + GlbproformaId  ,  "json", function(data) {
           
        let min_dimensions = data.column.length;
        let statuschanged=false;
        var changed = function(instance, cell, x, y, value) {
          document.getElementById('userwise_packagebtn').disabled=false;
          statuschanged=true;
        };
        
        var editpcksvarcond=$('#editpckvariable').val();
        let options = {
                data: data.data,
                editable:true,
                columns: data.column,
                minDimensions: [min_dimensions, 1],
                allowDeleteColumn: false,
                allowInsertRow: true,
                allowInsertColumn: false,
                footers:[[ 'Total : ','=SUMCOL(TABLE(), COLUMN())','=SUMCOL(TABLE(), COLUMN())','=SUMCOL(TABLE(), COLUMN())']],
                updateTable: function (instance, cell, col, row, val, label, cellName) { 
                    
                                    if ((col==0 || col == 1 || col == 2) && editpcksvarcond==2) {
                                        if(!statuschanged){
                                            cell.classList.add('readonly');
                                        }
                                        
                                    } 
                                     if(col === 1)
                                    { 
                                        var txtValue = numeral(val).format('0');
                                        txtValue  = (txtValue > 0) ? txtValue : '';
                                        $(cell).text(txtValue);
                                        instance.jexcel.options.data[row][col] = txtValue;
                                        alloweduser = txtValue;
                                    }
                                    if(col === 2)
                                    {   
                                        var txtValue = val!=''?(numeral(val).format('0')):val;
                                        txtValue  = (txtValue >= 0) ? txtValue : '';
                                        $(cell).text(txtValue);
                                        instance.jexcel.options.data[row][col] = txtValue;
                                        additionaluser = txtValue;
                                    }
                                    if(col === 3)
                                    {   
                                        totaluser = parseInt(alloweduser) + parseInt(additionaluser);
                                        totaluser = numeral(totaluser).format('0');
                                        totaluser  = (totaluser > 0) ? totaluser : '';
                                        $(cell).text(totaluser);
                                        instance.jexcel.options.data[row][col] = totaluser;
                                    }
                                    
                },
                onchange: changed
            };
            
             let k = new Vue({
                    el: '#userwise_package_details',
                    mounted: function () {
                        let spreadsheet = jspreadsheet(this.$el, options);
                        Object.assign(this, spreadsheet);
                    },
                    methods: {
                        submitData: function () {
                            let data = this.getData();
                           
                            MakeAsynPostRequest(base_path + GlbBAdminFdr + "msubscription/updatedeptcountInfo",
                            "rfrom=1" + 
                            "&subscriber_id=" + GlbsubscriberId +  "&proforma_id=" + GlbproformaId + "&edit_status=" + statuschanged + "&object=" + JSON.stringify(data), 
                            "json",function (data) {
                                    if (data != '') {
                                        if (data.errcode == '404') {
                                        fnCallSessionExpire();
                                        return false;
                                    } else if (data.errcode == -1) {
                                        swalWithBootstrapButtons.fire({
                                            title: data.msg,type: 'warning',
                                            icon: 'warning',
                                            customClass: {'confirmButton': 'btn btn-info'}
                                        });
                                        return false;
                                    } else if (data.errcode == 1) {
                                            swalWithBootstrapButtons.fire({
                                                        title: 'Saved!',type: 'success',
                                                        icon: 'success',
                                                        customClass: {'confirmButton': 'btn btn-info'}
                                            }).then((result) => {
                                                // let redirectpath = base_path + GlbBAdminFdr + 'msubscription/detviews/'+ encodeURIComponent(base64_encode(GlbsubscriberId));
                                                // window.location.href = redirectpath;
                                                fnList();
                                                statuschanged=false;
                                                document.getElementById('userwise_packagebtn').disabled=true;
                                               
                                            });
                                        
                                    }
                                }
                            });
                            
                        },
                        
                    }
                });
                
            $('#userwise_packagebtn').click(function (){
                    //alert($('#no_of_users').val());
                   
                        let no_of_users=$('#no_of_users_chargeable').val();
                        let total_no_of_users=SUMCOL(k, 2);
                        let adnl_of_users=($('#additional_users_chargeable').val()=='Nil' || $('#additional_users_chargeable').val()=='nil')?0:$('#additional_users_chargeable').val();
                        let total_adnlno_of_users=SUMCOL(k, 3);
                        
                        let validate = 0;
                        let data = k.getData();
                        validate = validateFiled('userwise_package_details', data);
                        let precheck=[];
                        for(i=0;i<data.length;i++){ // garment parts id value in seperate array from data array of zero th column
                              if(data[i][0]){
                                  precheck[i]=data[i][0];
                              }
                            }
                          //console.log('precheck'+precheck);  
                          const hasDuplicatesResult = hasDuplicates(precheck);
                         // console.log(hasDuplicatesResult); // Output: true
                          
                            
                            if(hasDuplicatesResult==true){
                                swalWithBootstrapButtons.fire({
                                title: 'Warning',
                                html: "Each department can be selected only once.",
                                icon: 'warning',
                                width:460,
                                confirmButtonText: 'OK',
                                customClass: {
                                    'confirmButton': 'btn btn-info',
                                    'title':'swal2-titles'
                                }
                            }).then(function(result) {
                                    if (result.value) {
                                        if(editpcksvar==2){
                                            showuserwisepackagedetail(GlbsubscriberId);
                                        }
                                    }
                            });
                            return false;
                            }
                            if((total_no_of_users>no_of_users) || (total_no_of_users!=no_of_users)){
                                swalWithBootstrapButtons.fire({
                                title: 'Warning',
                                html: "Total No. of Users Allowed (Package) should be equal to " + no_of_users,
                                icon: 'warning',
                                width:460,
                                confirmButtonText: 'OK',
                                customClass: {
                                    'confirmButton': 'btn btn-info',
                                    'title':'swal2-titles'
                                }
                            }).then(function(result) {
                                    if (result.value) {
                                        if(editpcksvar==2){
                                            showuserwisepackagedetail(GlbsubscriberId);
                                        }
                                    }
                            });
                            return false;
                            }else if((total_adnlno_of_users>adnl_of_users) || (total_adnlno_of_users!=adnl_of_users)){
                                 swalWithBootstrapButtons.fire({
                                title: 'Warning',
                                html: "Total No. of Add. Users Allowed (Chargeable) should be equal to " + adnl_of_users,
                                icon: 'warning',
                                width:460,
                                confirmButtonText: 'OK',
                                customClass: {
                                    'confirmButton': 'btn btn-info',
                                    'title':'swal2-titles'
                                }
                            }).then(function(result) {
                                    if (result.value) {
                                        if(editpcksvar==2){
                                            showuserwisepackagedetail(GlbsubscriberId);
                                        }
                                    }
                            });
                            return false;
                            }
                    if(validate == 0){
                    swalWithBootstrapButtons.fire(
                        {
                            title: 'Do you want to save the details ?',
                            type: 'warning',
                            showCancelButton: true,
                            scrollbarPadding: false,
                            confirmButtonText: 'Yes',
                            cancelButtonText: 'No',
                            reverseButtons: true,
                             width:460,
                            customClass: {'confirmButton': 'btn btn-green mx-2 px-3',  'cancelButton': 'btn btn-red mx-2 px-3'}
                        }
                    ).then(function(result) {
                        if (result.value) {
                            k.submitData();
                        }else{
                            
                            if(editpcksvar==2){
                                showuserwisepackagedetail(GlbsubscriberId);
                            }
                                    
                        }
                        // commented by me on 20/03/23
                       // else if (result.dismiss === Swal.DismissReason.cancel) {
                            // commented by myself regards to retain last state 
                            // WithinStateGrid(enquiry_id)
                            
                       // }
                    });
                }else{
                    swalWithBootstrapButtons.fire({
                                title: 'Warning',
                                text: "Please fill all the fields to continue.",
                                icon: 'warning',
                                width:460,
                                confirmButtonText: 'OK',
                                customClass: {
                                    'confirmButton': 'btn btn-info',
                                    'title':'swal2-titles'
                                }
                            }).then(function(result) {
                                    if (result.value) {
                                        if(editpcksvar==2){
                                            showuserwisepackagedetail(GlbsubscriberId);
                                        }
                                    }
                            });
                }
                });   
            $('#editbtn').click(function () { // Enable readonly columns
                // Get the total number of columns in the jExcel table
                var totalColumns = k.options.data[0].length; // Assuming there is at least one row
            
                // Get all the rows in the jExcel table
                var rows = k.table.rows;
            
                // Loop through each row and remove the 'readonly' class from all cells
                for (var i = 0; i < rows.length; i++) {
                    for (var j = 0; j < totalColumns; j++) {
                        var cell = k.table.rows[i].cells[j];
                        cell.classList.remove('readonly');
                    }
                }

                // Now all cells in all columns should be editable
               
                $('#userwise_packagebtn').show();
               
                });  
           // editbtn
        });

 }
 var SUMCOL = function(instance, columnId) {
        var total = 0;
        for (var j = 0; j < instance.options.data.length; j++) {
            if (Number(instance.records[j][columnId - 1].innerHTML)) {
                total += Number(instance.records[j][columnId - 1].innerHTML);
            }
        }
        total = numeral(total).format('0');
//        total  = parseFloat(total).toFixed(2);
        total = (total >= 0) ? total : ''
        return total;
    }
 function validateFiled(grid_name,data) {
        let validate_filed = [];
       
        if(grid_name == 'userwise_package_details') {
            validate_filed = [0,1,2,3];
        }

        validate = validateForm(validate_filed, data);
        return validate;
     
    }
 function validateForm(validateField, dataValue) {
      // console.log(dataValue)
        let errorCount = 0;
        for (let i = 0; i < dataValue.length; i++) {
            for(let j = 0; j < validateField.length; j++) {
                let col = validateField[j];
                if(dataValue[i][col] == "") {
                    errorCount++;
                }
            }
        }
        return errorCount;
    }
 function showpackagedetail(subscriber_id)
 {    
       
        $("#package_details").html("");
        MakeAsynPostRequest(base_path + "badmin/msubscription/showpackagedetails", "subscriber_id=" + GlbsubscriberId + "&proforma_id=" + GlbproformaId , "json", function(data) {
         //   console.log("draft" + data.data);
        let min_dimensions = data.column.length;
        let options = {
                data: data.data,
                editable:false,
                columns: data.column,
                minDimensions: [min_dimensions, 1],
                allowDeleteColumn: false,
                allowInsertRow: false,
                allowInsertColumn: false,
                //footers:[['', '', '','','','', 'Total : ','=SUMCOL(TABLE(), COLUMN())','=SUMCOL(TABLE(), COLUMN())','=SUMCOL(TABLE(), COLUMN())']],
                updateTable: function (instance, cell, col, row, val, label, cellName) { 
                    
            
                }
            };
            
             let k = new Vue({
                    el: '#package_details',
                    mounted: function () {
                        let spreadsheet = jspreadsheet(this.$el, options);
                        Object.assign(this, spreadsheet);
                    },
                    methods: {
                        submitData: function () {
                            
                        },
                      
                    }
                });
                
        });
        
    }
 function hasDuplicates(arr) { // duplicate checking inan array function
      for (let i = 0; i < arr.length; i++) {
        if (arr.includes(arr[i], i + 1)) {
          return true;
        }
      }
      return false;
    }
    function getUrlParameter(name) {
    name = name.replace(/[\[]/, '\\[').replace(/[\]]/, '\\]');
    var regex = new RegExp('[\\?&]' + name + '=([^&#]*)');
    var results = regex.exec(location.href);
    return results === null ? '' : decodeURIComponent(results[1].replace(/\+/g, '    '));
};
if(getUrlParameter('role')=='dept'){
    
}
</script>
<?php $this->load->view(CNFCOMPANY . 'template/pagefooter'); ?>