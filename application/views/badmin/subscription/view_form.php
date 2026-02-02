<?php $this->load->view(CNFCOMPANY . 'template/pageheader'); 
$ArrProfileInfo = fnGetUserLoggedInfo(1);
?>
    <!--<body class="sidebar-mini skin-black wysihtml5-supported sidebar-collapse">-->
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
                        <h2 class="page-header text-royal-black mx-4" style="border-bottom:0px"> <?php echo (empty($Edit) && empty($VarId)) ? 'Add New Subscriber Details':'View / Edit Subscriber Details' ?>
                          <div class="pull-right">
                                            <input type="hidden" id="draftstatus" value="<?php if (!empty($BasicInfo->draft_status)) { echo $BasicInfo->draft_status;} else { echo 1; } ?>">
                                            <input type="hidden" id="mrkt_dept_userid" value="<?php if (!empty($BasicInfo->mrkt_dept_userid)) { echo $BasicInfo->mrkt_dept_userid;} else { echo $ArrProfileInfo['id']; } ?>">
                                            <input type="hidden" id="subscriber_id" value="<?php if (!empty($BasicInfo->id)) { echo $BasicInfo->id;} ?>">
                                            <div class="ml-auto pr-3">
                                            <?php if($checkDraftorNot > 0) { ?>
                                            <a href="javascript:void(null)" id="backbtn" class="btn custbtn btn-sm mx-3 btn-royal-blue btn-text-slide-x">Back</a>
                                             <?php } else { ?>
                                             <a href="javascript:void(null)" id="backbtn" class="btn custbtn btn-sm mx-3 btn-royal-blue btn-text-slide-x">Back</a>
                                            <?php 
                                                if(!isset($Edit) && (empty($Edit))) { echo ''; } else {
                                            ?>
                                            
                                            <a id="editEnable" class="btn custbtn btn-royal-blue btn-sm px-3" onclick="$('#enqsvbtn').show()">Edit</a>
                                            <?php } }?>
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
                                            Subscriber Name <span class="mandatory">*</span>
                                        </label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control" id="companyname" value="<?php if (!empty($BasicInfo->companyname)) echo $BasicInfo->companyname; ?>" placeholder="Free Text">
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
                                                    <option value="<?php echo $key ?>" <?php if (@$BasicInfo->businesstype == $key) { echo "selected";} ?>>
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
                                        <input type="text" id="contactperson" name="contactperson" class="form-control" placeholder="Free Text" value="<?php if (!empty($BasicInfo->contactperson)) echo $BasicInfo->contactperson; ?>">
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
                                        <input type="text" name="designation" id="designation" class="form-control" placeholder="Free Text" value="<?php if (!empty($BasicInfo->designation)) echo $BasicInfo->designation; ?>">
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
                                         <input type="text" id="email_id" name="email_id" class="form-control" placeholder="Free Text" value="<?php if (!empty($BasicInfo->email_id)) echo $BasicInfo->email_id; ?>">
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
                                         <input type="text" id="mobile_no" name="mobile_no" onkeypress="return onlyNumbernodecimal(event);"  class="form-control" placeholder="Free Text" value="<?php if (!empty($BasicInfo->mobile_no)) echo $BasicInfo->mobile_no; ?>">
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
                                         <input type="text" id="gst_no" name="gst_no" onkeyup="CaseConvert(this.id,'all','upper')" class="form-control" placeholder="Free Text" value="<?php if (!empty($BasicInfo->gst_no)) echo $BasicInfo->gst_no; ?>">
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
                                        <textarea id="address"  name="address" rows="5" class="form-control" placeholder="Free Text"><?php if (!empty($BasicInfo->address)) echo $BasicInfo->address; ?></textarea>
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
                                            <input type="text" id="city"  name="city" class="form-control" placeholder="Free Text" value="<?php if (!empty($BasicInfo->city)) echo $BasicInfo->city; ?>">
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
                                            <input type="text" id="state"  name="state" class="form-control" placeholder="Free Text" value="<?php if (!empty($BasicInfo->state)) echo $BasicInfo->state; ?>">
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
                                            <input type="text" id="country"  name="country" class="form-control" placeholder="Free Text" value="<?php if (!empty($BasicInfo->country)) echo $BasicInfo->country; ?>">
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
                                        <input type="text" id="pincode" name="pincode" onkeypress="return onlyNumbernodecimal(event);" class="form-control" placeholder="Free Text" value="<?php if (!empty($BasicInfo->pincode)) echo $BasicInfo->pincode; ?>">
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
                                                     <option value="<?php echo $item['id'] ?>" <?php if (@$BasicInfo->package_id == $item['id']) { echo "selected";} ?>><?php echo $item['description'] ?></option>
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
                                                    <option value="<?php echo $key ?>" <?php if (@$BasicInfo->purchasetype == $key) { echo "selected";} ?>>
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
                                            <input type="text" class="form-control" id="additional_users"  placeholder="Free Text" value="<?php if (!empty($BasicInfo->additional_users)) echo $BasicInfo->additional_users; ?>">
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
                                                <option value="<?php echo $key ?>" <?php if (@$BasicInfo->data_storage_limit == $key) { echo "selected";} ?>><?php echo $item ?></option>
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
                                                    <option value="<?php echo $key ?>" <?php if (@$BasicInfo->file_storage_limit == $key) { echo "selected";} ?>>
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
                                              " name="remarks"   class="form-control" placeholder="Free Text"><?php echo (isset($BasicInfo->remarks) && $BasicInfo->remarks!='undefined') ? $BasicInfo->remarks : '' ?></textarea>
                                    <div class="herr" id="Errremarks"></div>
                                </div>
                            </div>.
                </div>
                <div class="row">
                    <div class="col-xs-12 py-4" style="padding-right:30px">
                        <button class="btn btn-info pull-right mx-2" id="enqsvbtn" onclick="return fnSave();">Save</button>
                        
                        <!--<div id="savedraft"><button class="btn btn-royal-blue pull-right mx-2" id="savedraftbtn" onclick="fnSaveEnquiryDraft(this.id);">Save as Draft</button></div>-->
                        <?php if($checkDraftorNot > 0) { ?>
                        <div id="cleardraft"><button class="btn btn-royal-blue pull-right mx-2" onclick="fncleardraft('<?php  echo base64_decode(urldecode($lastURI)) ?>')">Clear Draft</button></div>
                         <?php } ?>
                    </div>
                </div>
            </section>     
        </section>
    </div>
    <?php $this->load->view(CNFCOMPANY . 'template/templatefooter'); ?>
    <div class="control-sidebar-bg"></div>
</div>
<script type="text/javascript">
    var GlbId = "<?php echo $VarId; ?>";
    var lasturi='<?php echo $Edit?>';
    var draftstatus=$('#draftstatus').val();
    var reqstatus=$('#request_status').val();
    if(reqstatus==1){
        $('#editEnable').hide();
       
    }
    if(lasturi=='view' && draftstatus==2){
        $('#enqsvbtn').hide();
        $('#cleardraft').hide();
        $('#savedraft').hide();
        
        $("#custom_form input").prop("disabled", true);
        $("#custom_form select").prop("disabled", true);
        // $("#custom_form textarea").prop("disabled", true);
        $("textarea").prop("disabled", true);
    } else if(lasturi=='view' && draftstatus==1){ 
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
<script src="<?php echo base_url() ?>assets/js/<?php echo CNFBADMIN ?>subscription.js"></script>
<?php $this->load->view(CNFCOMPANY . 'template/pagefooter'); ?>