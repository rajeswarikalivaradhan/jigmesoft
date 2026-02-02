<?php $this->load->view(CNFCOMPANY . 'template/pageheader'); 
$ArrProfileInfo = fnGetUserLoggedInfo(1); $usertype=$ArrProfileInfo['usertype'];
$paymentstatus = unserialize(ARRPAYMENTSTATUS);?>
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css" />
    <!--<body class="sidebar-mini skin-black wysihtml5-supported sidebar-collapse">-->
    <style>
        
element.style {
}
#custom_forms {
    background: #F7F7F7;
    /* padding: 18px; */
    padding: 18px 18px 3px 18px;
}
.form-control-feedback{
        width:62px;
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
.swal2-texts{
    font-size:18px!important;
}   
    </style>
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
                        <h2 class="page-header text-royal-black mx-4" style="border-bottom:0px"> <?php echo (empty($Edit) && empty($VarId)) ? 'Add New Subscriber Enquiry Details':'View / Edit Subscriber Enquiry Details' ?>
                          <div class="pull-right">
                                            <input type="hidden" id="mrkt_dept_userid" value="<?php if (!empty($BasicInfo->mrkt_dept_userid)) { echo $BasicInfo->mrkt_dept_userid;} else { echo $ArrProfileInfo['id']; } ?>">
                                            <input type="hidden" id="draftstatus" value="<?php if (!empty($BasicInfo->draft_status)) { echo $BasicInfo->draft_status;} else { echo 1; } ?>">
                                            <input type="hidden" id="subscriber_id" value="<?php if (!empty($BasicInfo->id)) { echo $BasicInfo->id;} ?>">
                                            <input type="hidden" id="subscriber_refno" value="<?php if (isset($subscriber_refno) && !empty($subscriber_refno)) { echo $subscriber_refno;} ?>">
                                            <input type="hidden" id="invoiceno" value="<?php if (isset($invoiceno) && !empty($invoiceno)) { echo $invoiceno;} ?>">
                                            <input type="hidden" id="subscription_period" value="<?php if (isset($subscription_period)) { echo $subscription_period;} ?>">
                                            <input type="hidden" id="proforma_type" value="<?php if (!empty($BasicInfo->proforma_type)) { echo $BasicInfo->proforma_type;} ?>">
                                            <input type="hidden" id="invopurchasetype" value="<?php if (!empty($BasicInfo->invopurchasetype)) { echo $BasicInfo->invopurchasetype;} ?>">
                                            <input type="hidden" id="proforma_id" value="<?php if (!empty($VarId)) { echo $VarId;} ?>">
                                            <input type="hidden" id="pckg_saved_status" value="<?php if (!empty($recent_subsrnpckginfo)) { echo $recent_subsrnpckginfo;} ?>">
                                            <input type="hidden" id="dept_saved_status" value="<?php if (!empty($recent_subsrndeptroleinfo)) { echo $recent_subsrndeptroleinfo;} ?>">
                                            <input type="hidden" id="user_saved_status" value="<?php if (!empty($recent_subsrnuserinfo)) { echo $recent_subsrnuserinfo;} ?>">
                                            <div class="ml-auto pr-3">
                                             <a href="#" id="backbtn" class="btn custbtn btn-sm mx-3 btn-royal-blue btn-text-slide-x">Back</a>
                                             <a id="editsubscinfo" class="btn custbtn btn-royal-blue btn-sm px-3 <?php if (@$BasicInfo->paymentstatus == 3) { echo "hide";} ?>" >Edit</a>
                                            </div>
                                        </div>
                        <div class="col-sm-12 " style="padding: 7px 25px;border-bottom: 1px solid #022B61;"></div>
                        </h2>
                        <h4 class="mr-2 py-2 text-royal-blue">
                        </h4>
                    </div><!-- /.col -->
                </div>
                <div class="row no-rad-form add-form-mar" id="custom_form">
                        <div class="col-md-4">
                            <div class="">
                                <div class="form-group">
                                        <label for="id-form-field-focus-1" class="col-sm-5 control-label">
                                            Company Name <span class="mandatory">*</span>
                                        </label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control" id="companyname" value="<?php if (!empty($BasicInfo->invocmpnyname)) echo $BasicInfo->invocmpnyname; ?>" placeholder="Free Text">
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
                                                    <option value="<?php echo $key ?>" <?php if (@$BasicInfo->invobusinesstype == $key) { echo "selected";} ?>>
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
                                        <input type="text" id="contactperson" name="contactperson" class="form-control" placeholder="Free Text" value="<?php if (!empty($BasicInfo->invocontactperson)) echo $BasicInfo->invocontactperson; ?>">
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
                                        <input type="text" name="designation" id="designation" class="form-control" placeholder="Free Text" value="<?php if (!empty($BasicInfo->invodesignation)) echo $BasicInfo->invodesignation; ?>">
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
                                         <input type="text" id="email_id" name="email_id" class="form-control" placeholder="Free Text" value="<?php if (!empty($BasicInfo->invoemail_id)) echo $BasicInfo->invoemail_id; ?>">
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
                                         <input type="text" id="mobile_no" name="mobile_no" onkeypress="return onlyNumbernodecimal(event);"  class="form-control" placeholder="Free Text" value="<?php if (!empty($BasicInfo->invomobile_no)) echo $BasicInfo->invomobile_no; ?>">
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
                                         <input type="text" id="gst_no" name="gst_no" onkeyup="CaseConvert(this.id,'all','upper')" class="form-control" placeholder="Free Text" value="<?php if (!empty($BasicInfo->invogst_no)) echo $BasicInfo->invogst_no; ?>">
                                        <div class="herr" id="Errgst_no"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="">
                                <div class="form-group">
                                       <label for="id-form-field-focus-1" class="col-sm-5 control-label">
                                       IE Code <span class="mandatory">*</span>
                                        </label>
                                    <div class="col-sm-7">
                                         <input type="text" id="iecode_no" name="iecode_no" onkeyup="CaseConvert(this.id,'all','upper')" class="form-control" placeholder="Free Text" value="<?php if (!empty($BasicInfo->ie_code)) echo $BasicInfo->ie_code; ?>">
                                        <div class="herr" id="Erriecode_no"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="">
                                <div class="form-group">
                                        <label for="id-form-field-focus-1" class="col-sm-5 control-label">
                                            Address <span class="mandatory">*</span>
                                        </label>
                                    <div class="col-sm-7">
                                        <textarea id="address"  name="address" rows="5" class="form-control" placeholder="Free Text"><?php if (!empty($BasicInfo->invoaddress)) echo $BasicInfo->invoaddress; ?></textarea>
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
                                            <input type="text" id="city"  name="city" class="form-control" placeholder="Free Text" value="<?php if (!empty($BasicInfo->invocity)) echo $BasicInfo->invocity; ?>">
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
                                            <input type="text" id="state"  name="state" class="form-control" placeholder="Free Text" value="<?php if (!empty($BasicInfo->invostate)) echo $BasicInfo->invostate; ?>">
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
                                            <input type="text" id="country"  name="country" class="form-control" placeholder="Free Text" value="<?php if (!empty($BasicInfo->invocountry)) echo $BasicInfo->invocountry; ?>">
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
                                        <input type="text" id="pincode" name="pincode" onkeypress="return onlyNumbernodecimal(event);" class="form-control" placeholder="Free Text" value="<?php if (!empty($BasicInfo->invopincode)) echo $BasicInfo->invopincode; ?>">
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
                                            <select class="cus-sel form-control js-example-basic-single" id="subscription_category" disabled>
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
                                        <input type="hidden" id="invopackagedet" value="<?php $index = array_search($BasicInfo->invopackageid, array_column($ArrPackage, 'id'));if (!empty($BasicInfo->invopackageid)) { echo $ArrPackage[$index]['description'];} ?>">
                                            <select class="cus-sel form-control js-example-basic-single" id="package_id" disabled>
                                                <option value="" selected disabled hidden>Select</option>
                                                <?php
                                                
                                                foreach ($ArrPackage as $key => $item)
                                                {
                                                    ?>
                                                     <option value="<?php echo $item['id'] ?>" <?php if (@$BasicInfo->invopackageid == $item['id']) { echo "selected";} ?>><?php echo $item['description'] ?></option>
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
                                            <select class="cus-sel form-control js-example-basic-single" id="purchasetype" disabled>
                                                <option value="" selected disabled hidden>Select</option>
                                                <?php
                                                $purchase_type = unserialize(ARRPURCHASETYPE);
                                                foreach ($purchase_type as $key => $item)
                                                {
                                                ?>
                                                    <option value="<?php echo $key ?>" <?php if (@$BasicInfo->invopurchasetype == $key) { echo "selected";} ?>>
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
                                             <input type="text" id="no_of_users" name="no_of_users"  class="form-control" placeholder="Auto Update" value="<?php if (!empty($BasicInfo->no_of_users)) echo $BasicInfo->no_of_users; ?>" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="">
                                    <div class="form-group">
                                       <label for="id-form-field-focus-1" class="col-sm-6 control-label">
                                      Data Storage Limit (Package)
                                        </label>
                                        <div class="col-sm-6">
                                             <input type="text" id="data_limit" name="data_limit" class="form-control" placeholder="Auto Update" value="<?php if (!empty($BasicInfo->data_limit)) echo $BasicInfo->data_limit; ?>" readonly>
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
                                        <input type="text" class="form-control" id="file_limit" readonly placeholder="Auto Update" value="<?php if (!empty($BasicInfo->file_limit)) echo $BasicInfo->file_limit; ?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="">
                                    <div class="form-group">
                                        <label for="id-form-field-focus-1" class="col-sm-7 control-label">
                                            No. of Additional Users (Chargeable) <span class="mandatory">*</span>
                                        </label>
                                        <div class="col-sm-5">
                                            <input type="text" class="form-control" id="additional_users" onkeypress="return onlyNumbernodecimal(event);"  placeholder="Free Text" value="<?php if (!empty($BasicInfo->invo_additional_users)) echo $BasicInfo->invo_additional_users; ?>">
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
                                                <option value="<?php echo $key ?>" <?php if (@$BasicInfo->invo_data_storage_limit == $key) { echo "selected";} ?>><?php echo $item ?></option>
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
                                                    <option value="<?php echo $key ?>" <?php if (@$BasicInfo->invo_file_storage_limit == $key) { echo "selected";} ?>>
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
                <div class="col-12 bgc-white pt-3 px-4">
                            <div class="row">
                                <div class="col-sm-12 col-form-label text-sm-left">
                                    <label for="id-form-field-focus-1">
                                        Remarks
                                    </label>
                                </div>
                                <div class="col-sm-12">
                                    <textarea id="remarks" style="height: 76px !important; padding: 20px 22px;border-radius:0.125rem !important
                                              " name="remarks"   class="form-control" placeholder="Free Text"><?php echo (isset($BasicInfo->invoremarks) && $BasicInfo->invoremarks!='undefined') ? $BasicInfo->invoremarks : '' ?></textarea>
                                    <div class="herr" id="Errremarks"></div>
                                </div>
                            </div>.
                            <div class="row" style="padding-right:16px">
                                <button class="btn btn-info pull-right mx-2" id="savesubscinfo" onclick="return fnSavesubscinfo()">Save</button>
                            </div>
                             <div class="row"> 
                                <div class="col-xs-12">
                                    <h2 class="page-header text-royal-black" style="border-bottom:0px;background: #F7F7F7;padding: 50px 18px 3px 50px;"></h2>
                                </div><!-- /.col -->
                            </div>
                </div>
                  <div class="row"> 
                    <div class="col-xs-12">
                        <h2 class="page-header text-royal-black mx-4" style="border-bottom:0px">Payment Received Details
                          <div class="pull-right">
                                            <div class="ml-auto pr-3">
                                            <?php 
                                                if (@$BasicInfo->save_status == 1 && $usertype==0) { 
                                            ?>
                                            <a id="editEnable" class="btn custbtn btn-royal-blue btn-sm px-3 <?php if (@$BasicInfo->paymentstatus == 3) { echo "hide";} ?>" >Edit</a>
                                            <?php } ?> 
                                            </div>
                                        </div>
                        <div class="col-sm-12 " style="padding:5px 0px;border-bottom: 1px solid #022B61;"></div>
                        </h2>
                    </div><!-- /.col -->
                </div>
                <div class="row no-rad-form add-form-mar my-4" id="custom_forms">
                    <div class="col-md-4">
                        <div class="">
                            <div class="form-group">
                                    <label for="id-form-field-focus-1" class="col-sm-5 control-label">
                                        Proforma Invoice No. <span class="mandatory">*</span>
                                    </label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control" id="invoice_no" disabled value="<?php  if (!empty($BasicInfo->invoice_refno)) echo $BasicInfo->invoice_refno; ?>" placeholder="Auto Update">
                                </div>
                            </div>
                        </div>
                        
                        <div class="">
                            <div class="form-group">
                                <label for="id-form-field-focus-1" class="col-sm-5 control-label">
                                   Proforma Invoice Date <span class="mandatory">*</span>
                                </label>
                                <div class="col-sm-7">
                                    <input type="text" id="invoice_date" name="invoice_date" class="form-control" disabled placeholder="Auto Update" value="<?php  if (!empty($BasicInfo->invoice_datetime)) echo $BasicInfo->invoice_datetime; ?>">
                                    <div class="herr" id="Errcontactperson"></div>
                                </div>
                            </div>
                        </div>
                		<div class="">
                            <div class="form-group">
                                    <label for="id-form-field-focus-1" class="col-sm-5 control-label">
                                       Proforma Invoive Value (Rs) <span class="mandatory">*</span>
                                    </label>
                                
                                <div class="col-sm-7">
                                    <input type="text" name="invoice_value" id="invoice_value" disabled class="form-control" placeholder="Free Text" value="<?php if (!empty($BasicInfo->subtotal)) echo $BasicInfo->subtotal; ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                         <div class="">
                                <div class="form-group">
                                    <label for="id-form-field-focus-1" class="col-sm-6 control-label">
                                        Payment Received From <span class="mandatory">*</span>
                                    </label>
                                    <div class="col-sm-6">
                                        <input type="text" id="payment_from"  name="payment_from" class="form-control" placeholder="Free Text" value="<?php  if (!empty($BasicInfo->payment_from)) echo $BasicInfo->payment_from; ?>">
                                        <div class="herr" id="Errpayment_from"></div>
                                    </div>
                                </div>
                         </div>
                         <div class="">
                                <div class="form-group">
                                    <label for="id-form-field-focus-1" class="col-sm-6 control-label">
                                        Transaction ID / Cheque No. <span class="mandatory">*</span>
                                    </label>
                                    <div class="col-sm-6">
                                        <input type="text" id="transaction_no"  name="transaction_no" class="form-control" placeholder="Free Text" value="<?php  if (!empty($BasicInfo->transaction_no)) echo $BasicInfo->transaction_no; ?>">
                                        <div class="herr" id="Errtransaction_no"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="">
                               <div class="form-group">
                                    <label for="id-form-field-focus-1" class="col-sm-6 control-label">
                                        Transaction / Cheque Date  <span class="mandatory">*</span>
                                    </label>
                                    <div class="col-sm-6">
                                        <input type="text" id="transaction_date"  name="transaction_date" class="form-control date" autocomplete="off" placeholder="Select" value="<?php  if (!empty($BasicInfo->transaction_date)) echo $BasicInfo->transaction_date; ?>">
                                        <span class="glyphicon glyphicon-calendar form-control-feedback"></span>
                                        <div class="herr" id="Errtransaction_date"></div>
                                    </div>
                                </div>
                            </div>
                    </div>
                    <div class="col-md-4">
                            <div class="">
                                <div class="form-group">
                                    <label for="id-form-field-focus-1" class="col-sm-7 control-label">
                                        Mode of Payment <span class="mandatory">*</span> 
                                    </label>
                                    <div class="col-sm-5">
                                       <select class="cus-sel form-control js-example-basic-single" id="payment_mode">
                                                <option value="" selected disabled hidden>Select</option>
                                                <?php
                                                $paymentmode = unserialize(ARRPAYMENTMODE);
                                                foreach ($paymentmode as $key => $item)
                                                {
                                                    ?>
                                                    <option value="<?php echo $key ?>" <?php if (@$BasicInfo->payment_mode == $key) { echo "selected";} ?>>
                                                        <?php echo $item ?>
                                                    </option>
                                                    <?php
                                                }
                                                ?>
                                        </select> 
                                        <div class="herr" id="Errpayment_mode"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="">
                                <div class="form-group">
                                    <label for="id-form-field-focus-1" class="col-sm-7 control-label">
                                        Transaction Value (Rs) <span class="mandatory">*</span>
                                    </label>
                                    <div class="col-sm-5">
                                        <input type="text" class="form-control" id="transaction_value"  onblur="validateTransaction()" onkeypress="return isNumber_or_isDecimal_Key(event);"  placeholder="Free Text" value="<?php  if (!empty($BasicInfo->transaction_value)) echo $BasicInfo->transaction_value; ?>">
                                        <div class="herr" id="Errtransaction_value"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="">
                                <div class="form-group">
                                    <label for="id-form-field-focus-1" class="col-sm-7 control-label">
                                       Payment Confirmation Status
                                    </label>
                                    <div class="col-sm-5">
                                        <?php if($usertype==16) { ?>
                                        <input type="hidden" id="payment_status" name="payment_status" class="form-control" placeholder="Auto Update" value="<?php  if (!empty($BasicInfo->paymentstatus)) echo $BasicInfo->paymentstatus; ?>">
                                        <input type="text" id="paymentstatus" name="paymentstatus" disabled class="form-control" placeholder="Auto Update" value="<?php  if (!empty($BasicInfo->paymentstatus)) echo $paymentstatus[$BasicInfo->paymentstatus]; ?>">
                                        <?php } else { ?>
                                        <select class="cus-sel form-control js-example-basic-single" id="payment_status">
                                                <option value="" selected disabled hidden>Select</option>
                                                <?php
                                                foreach ($paymentstatus as $key => $item)
                                                {
                                                    ?>
                                                    <option value="<?php echo $key ?>" <?php if (@$BasicInfo->paymentstatus == $key) { echo "selected";} ?>>
                                                        <?php echo $item ?>
                                                    </option>
                                                    <?php
                                                }
                                                ?>
                                        </select> 
                                        <?php }  ?>
                                        <div class="herr" id="Errpayment_status"></div> 
                                    </div>
                                </div>
                            </div>
                    </div> 
                </div>
                <div class="row"><div class="col-xs-12 mb-3" style="padding-right:30px">
                        <button class="btn btn-info pull-right mx-2" id="paymentsvbtn" onclick="return fnSave()" >Save</button>
                        <?php  if (@$BasicInfo->save_status == 1 && $usertype==0 && @$BasicInfo->paymentstatus == 2) {  ?>
                        <?php if(@$BasicInfo->invopurchasetype == 1 && @$BasicInfo->proforma_type=='NPI') { ?>
                        <button class="btn btn-royal-blue pull-right mx-2" id="subscrnbtn" onclick="return fnSaveSubscription()">Add New Subscription</button>
                        <?php  } else if((@$BasicInfo->invopurchasetype == 2 && @$BasicInfo->proforma_type=='NPI')){ ?>
                        <button class="btn btn-royal-blue pull-right mx-2" id="subscrnbtn" onclick="return fnSaveSubscription()">Update Renewal</button>
                        <?php  } else if((@$BasicInfo->invopurchasetype == 3 && @$BasicInfo->proforma_type=='NPI')){ ?>
                        <button class="btn btn-royal-blue pull-right mx-2" id="subscrnbtn" onclick="return fnSaveSubscription()">Update Package Migration </button>
                        <?php  }else {  ?>
                        <button class="btn btn-royal-blue pull-right mx-2" id="subscrnbtn" onclick="return fnSaveSubscription()">Update</button>
                        <?php  }  }?>
                        </div></div>
            </section>     
        </section>
    </div>
    <?php $this->load->view(CNFCOMPANY . 'template/templatefooter'); ?>
    <div class="control-sidebar-bg"></div>
</div>
<script type="text/javascript">
    
    var GlbId = "<?php echo $VarId; ?>";
    var lasturi='<?php echo $Edit?>';
    var usertype='<?php echo $usertype;?>';
    var save_status='<?php echo (isset($BasicInfo->save_status)) ?  $BasicInfo->save_status:"";?>';
    var paymentstatus='<?php echo (isset($BasicInfo->paymentstatus)) ?  $BasicInfo->paymentstatus:"";?>';
    var purchasetype=$("#invopurchasetype").val();
 
    $(document).ready(function() {
    $('.date').datepicker({
            'format': 'dd-mm-yyyy',
            'autoclose': true,
            'orientation': "top",
            'todayHighlight':true,
        });
    });

    $(document).on('click', '#backbtn', function(e) {
        e.preventDefault();
       
        let redirectpath = base_path + 'invoice/manage';
        window.location.href = redirectpath;
    });
    if(lasturi=='view'){
        $("#custom_form input").prop("disabled", true);
        $("#custom_form select").prop("disabled", true);
        // $("#custom_form textarea").prop("disabled", true);
        $("textarea").prop("disabled", true);
        $("#savesubscinfo").hide();
    }
    if(paymentstatus!='' && paymentstatus==3){
        $("#custom_forms input").prop("disabled", true);
        $("#custom_forms select").prop("disabled", true);
        $("#paymentsvbtn").hide();
    }
    if(usertype=='16' && save_status==1){
        $("#custom_forms input").prop("disabled", true);
        $("#custom_forms select").prop("disabled", true);
        $("#paymentsvbtn").hide();
        // $("#custom_form textarea").prop("disabled", true);
        ///$("textarea").prop("disabled", true);
    }else if(usertype=='0' && save_status==1){
        $("#custom_forms input").prop("disabled", true);
        $("#custom_forms select").prop("disabled", true);
        $("#paymentsvbtn").hide();
        // $("#custom_form textarea").prop("disabled", true);
        ///$("textarea").prop("disabled", true);
    }else{
         $("#paymentsvbtn").show();
    }
    
    $('#editEnable').on('click', function() {
        if($("#custom_forms input").prop("disabled")==true &&  $("#custom_forms select").prop("disabled")==true){
           $("#custom_forms input").prop("disabled", false); 
           $("#custom_forms select").prop("disabled", false);
           $('#paymentsvbtn').show();
        }else{
            $("#custom_forms input").prop("disabled", true);
            $("#custom_forms select").prop("disabled", true);
             $('#paymentsvbtn').hide();
        }
        //////// newly included ///////////////
         $("#invoice_no").prop("disabled", true);
         $("#invoice_date").prop("disabled", true);
         $("#invoice_value").prop("disabled", true);
          ///////////////////////// /////////////
    });
    
    $('#editsubscinfo').on('click', function() {
    let $form = $("#custom_form");  
    let isDisabled = $form.find("input, select").prop("disabled");  

    // Toggle all form inputs, selects, and textareas
    $form.find("input, select, textarea").prop("disabled", !isDisabled);

    // Toggle visibility of the save button
    $('#savesubscinfo').toggle(isDisabled);

    // Toggle subscription button (#subscrnbtn)
    $('#subscrnbtn').prop("disabled", isDisabled);

    // Handle package_id toggle along with purchasetype logic
    if (isDisabled) {  // Only when enabling fields
        $("#package_id").prop("disabled", !(purchasetype == 2 || purchasetype == 3));
    } else {
        $("#package_id").prop("disabled", true);
    }

    // Always disable these fields
    $("#purchasetype, #subscription_category").prop("disabled", true);
});


    function validateTransaction() {
    var proformainvval =  ($('#invoice_value').val());
    var transactionInput = document.getElementById("transaction_value");
    var transactionval = parseFloat(transactionInput.value);

    if (isNaN(transactionval) || transactionval < proformainvval) {
        //alert("Transaction value must be greater than or equal to " + proformainvval);
        $('#Errtransaction_value').text("Incorrect Transaction Value.");
        $('#transaction_value').focus();
        $('#transaction_value').css("border", "1px solid #B94A48");
        transactionInput.value = ""; // Clear the input field
    }else{
        $('#Errtransaction_value').text("");
        $('#transaction_value').css("border", "1px solid #D0D1D1");
    }
}


</script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/custom/datepair.js"></script>
<script src="<?php echo base_url() ?>assets/js/<?php echo CNFBADMIN ?>invoice.js"></script>
<script src="<?php echo base_url() ?>assets/js/<?php echo CNFBADMIN ?>subscriberinfosave.js"></script>
<?php $this->load->view(CNFCOMPANY . 'template/pagefooter'); ?>