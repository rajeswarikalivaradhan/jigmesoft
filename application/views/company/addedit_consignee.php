<?php $this->load->view(CNFCOMPANY . 'template/pageheader'); ?>
    <!--<body class="sidebar-mini skin-black wysihtml5-supported sidebar-collapse">-->
<body class="layout-top-nav">
<div class="wrapper">
    <?php $this->load->view(CNFCOMPANY . 'template/admintemplateheader');
    // $this->load->view(CNFCOMPANY . 'template/templateheader'); ?>
    <aside class="main-sidebar hide">
        <?php // $this->load->view(CNFCOMPANY . 'template/templateleftmenu'); ?>
    </aside>
    <div class="content-wrapper">
        <section class="content">
        <section class="invoice form-horizontal">
                <div class="row"> 
            <div class="col-xs-12">
                <h2 class="page-header text-royal-black mx-4" style="border-bottom:0px"> <?php echo (empty($Edit) && empty($VarId)) ? 'Add New Consignee Details':'View / Edit Consignee Details' ?>
                  <div class="pull-right">
                                    <div class="ml-auto pr-3">
                                    <a href="<?php echo base_url(CNFCOMPANY . 'Magent/consignee') ?>" id="backbtn" class="btn custbtn btn-sm mx-3 btn-royal-blue btn-text-slide-x">Back</a>
                                    <?php 
                                        if(!isset($Edit) && (empty($Edit))) { echo ''; } else {
                                    ?>
                                    <a id="editEnable" class="btn custbtn btn-royal-blue btn-sm px-3" onclick="$('#enqsvbtn').show()">Edit</a>
                                    <?php } ?>
                                    </div>
                                </div>
                <div class="col-sm-12 " style="padding: 7px 25px;border-bottom: 1px solid #022B61;"></div>
                </h2>
                <h4 class="mr-2 py-2 text-royal-blue">
                </h4>
            </div><!-- /.col -->
        </div>
                <div class="row no-rad-form add-form-mar" id="custom_form">
                    <div class="col-md-6">
                        <div class="col-md-12">
                            <div class="form-group">
                                    <label for="id-form-field-focus-1" class="col-sm-4 control-label">
                                   Consignee Name  <span class="mandatory">*</span>
                                    </label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="agentname" value="<?php echo @$ArrBasicInfo['agentname']; ?>" placeholder="Free Text">
                                    <div class="herr" id="Erragentname"></div>
                                </div>
                            </div>
                        </div>
						<div class="col-md-12">
                            <div class="form-group">
                                    <label for="id-form-field-focus-1" class="col-sm-4 control-label">
                                    Address  <span class="mandatory">*</span>
                                    </label>
                                <div class="col-sm-8">
                                    <textarea class="form-control" id="address" style="height: 89px;" placeholder="Free Text"><?php echo @$ArrBasicInfo['address']; ?></textarea>
                                    <div class="herr" id="Erraddress"></div>
                                </div>
                            </div>
                        </div>
						<div class="col-md-12">
                            <div class="form-group">
                                    <label for="id-form-field-focus-1" class="col-sm-4 control-label">
                                    City <span class="mandatory">*</span>
                                    </label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="city" value="<?php echo @$ArrBasicInfo['city']; ?>" placeholder="Free Text">
                                    <div class="herr" id="Errcity"></div>
                                </div>
                            </div>
                        </div>
						<div class="col-md-12">
                            <div class="form-group">
                                    <label for="id-form-field-focus-1" class="col-sm-4 control-label">
                                    State <span class="mandatory">*</span>
                                    </label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="state"  value="<?php echo @$ArrBasicInfo['state']; ?>" placeholder="Free Text">
                                    <div class="herr" id="Errstate"></div>
                                </div>
                            </div>
                        </div>
						<div class="col-md-12">
                            <div class="form-group">
                                    <label for="id-form-field-focus-1" class="col-sm-4 control-label">
                                    Country <span class="mandatory">*</span>
                                    </label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="country" value="<?php echo @$ArrBasicInfo['country']; ?>" placeholder="Free Text">
                                    <div class="herr" id="Errcountry"></div>
                                </div>
                            </div>
                        </div>
						<div class="col-md-12">
                            <div class="form-group">
                                    <label for="id-form-field-focus-1" class="col-sm-4 control-label">
                                    ZIP / Pin Code <span class="mandatory">*</span>
                                    </label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="pincode" value="<?php echo @$ArrBasicInfo['pincode']; ?>" placeholder="Free Text">
                                    <div class="herr" id="Errpincode"></div>
                                </div>
                            </div>
                        </div>
						<div class="col-md-12">
                            <div class="form-group">
                                    <label for="id-form-field-focus-1" class="col-sm-4 control-label">
                                    Email ID <span class="mandatory">*</span>
                                    </label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="emailid" value="<?php echo @$ArrBasicInfo['emailid']; ?>" placeholder="Free Text">
                                    <div class="herr" id="Erremailid"></div>
                                </div>
                            </div>
                        </div>
						<div class="col-md-12">
                            <div class="form-group">
                                    <label for="id-form-field-focus-1" class="col-sm-4 control-label">
                                    Phone No 
                                    </label>
                                <div class="col-sm-8">
                                    <input type="text" onkeypress="return onlyNumbernodecimal(event);" class="form-control" id="phone" value="<?php echo @$ArrBasicInfo['phone']; ?>" placeholder="Free Text">
                                </div>
                            </div>
                        </div>
						<div class="col-md-12">
                            <div class="form-group">
                                    <label for="id-form-field-focus-1" class="col-sm-4 control-label">
                                    Mobile No <span class="mandatory">*</span>
                                    </label>
                                <div class="col-sm-8">
                                    <input type="text" onkeypress="return onlyNumbernodecimal(event);" class="form-control" id="mobile" value="<?php echo @$ArrBasicInfo['mobile']; ?>" placeholder="Free Text">
                                    <div class="herr" id="Errmobile"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
								<div class="form-group">
										<label for="id-form-field-focus-1" class="col-sm-4 control-label">
										Contact Person <span class="mandatory">*</span>
										</label>
									<div class="col-sm-8">
										<input type="text" class="form-control" id="contactperson" value="<?php echo @$ArrBasicInfo['contactperson']; ?>" placeholder="Free Text">
										<div class="herr" id="Errcontactperson"></div>
									</div>
								</div>
							</div>
                    </div>
                    <div class="col-md-6">
					
							<div class="col-md-12">
								<div class="form-group">
										<label for="id-form-field-focus-1" class="col-sm-3 control-label">
										Consignee Category  <span class="mandatory">*</span>
										</label>
									<div class="col-sm-8">
									    <select name="agent_categoryid" id="agent_categoryid" class="form-control">
											<option value="">Select</option>
											<?php  $ArrVendorCategory = unserialize(ARRVENDORCATEGORY);
											foreach ($ArrVendorCategory as $VarKey => $VarStatus) { ?>
												<option
													value="<?php echo $VarKey ?>" <?php if (@$ArrBasicInfo['agent_categoryid'] == $VarKey) {
													echo "selected";
												} ?>><?php echo $VarStatus ?></option>
											<?php } ?>
										</select>
										<div class="herr" id="Erragent_categoryid"></div>
									</div>
								</div>
							</div>
							<div class="col-md-12">
								<div class="form-group">
										<label for="id-form-field-focus-1" class="col-sm-3 control-label">
										Represent Brand <span class="mandatory">*</span>
										</label>
									<div class="col-sm-8">
										<select name="brand_id" id="brand_id" class="form-control">
											<option value="">Select</option>
											<?php  
											foreach ($branddata as $key => $value) { ?>
												<option
													value="<?php echo $value['id'] ?>" <?php if (@$ArrBasicInfo['brand_id'] == $value['id']) {
													echo "selected";
												} ?>><?php echo $value['name'] ?></option>
											<?php } ?>
										</select>
										<div class="herr" id="Errbrand_id"></div>
									</div>
								</div>
							</div>
							<div class="col-md-12">
								<div class="form-group">
										<label for="id-form-field-focus-1" class="col-sm-3 control-label">
										Represent Buyer <span class="mandatory">*</span>
										</label>
									<div class="col-sm-8">
										<input type="text" class="form-control" disabled id="buyername" value="<?php echo @$ArrBasicInfo['buyername']; ?>" placeholder="Auto Populate">
									</div>
								</div>
							</div>
								<div class="col-md-12">
									<div class="form-group">
											<label for="id-form-field-focus-1" class="col-sm-3 control-label">
											GST No <span class="mandatory">*</span>
											</label>
										<div class="col-sm-8">
											<input type="text" class="form-control" id="gstno" value="<?php echo @$ArrBasicInfo['gstno']; ?>" placeholder="Free Text">
											<div class="herr" id="Errgstno"></div>
										</div>
									</div>
								</div>
								<div class="col-md-12">
									<div class="form-group">
											<label for="id-form-field-focus-1" class="col-sm-3 control-label">
											IE Code <span class="mandatory">*</span>
											</label>
										<div class="col-sm-8">
											<input type="text" class="form-control" id="iecode" value="<?php echo @$ArrBasicInfo['iecode']; ?>" placeholder="Free Text">
											<div class="herr" id="Erriecode"></div>
										</div>
									</div>
								</div>
								<div class="col-md-12">
									<div class="form-group">
											<label for="id-form-field-focus-1" class="col-sm-3 control-label">
											Bank Name <span class="mandatory">*</span>
											</label>
										<div class="col-sm-8">
											<input type="text" class="form-control" id="bankname" value="<?php echo @$ArrBasicInfo['bankname']; ?>" placeholder="Free Text">
											<div class="herr" id="Errbankname"></div>
										</div>
									</div>
								</div>
								<div class="col-md-12">
									<div class="form-group">
											<label for="id-form-field-focus-1" class="col-sm-3 control-label">
											Account Name <span class="mandatory">*</span>
											</label>
										<div class="col-sm-8">
											<input type="text" class="form-control" id="accountname" value="<?php echo @$ArrBasicInfo['accountname']; ?>" placeholder="Free Text">
											<div class="herr" id="Erraccountname"></div>
										</div>
									</div>
								</div>
								<div class="col-md-12">
									<div class="form-group">
											<label for="id-form-field-focus-1" class="col-sm-3 control-label">
											Account No <span class="mandatory">*</span>
											</label>
										<div class="col-sm-8">
											<input type="text" class="form-control" id="accountno" value="<?php echo @$ArrBasicInfo['accountno']; ?>" placeholder="Free Text">
											<div class="herr" id="Erraccountno"></div>
										</div>
									</div>
								</div>
								<div class="col-md-12">
									<div class="form-group">
											<label for="id-form-field-focus-1" class="col-sm-3 control-label">
											IFSC <span class="mandatory">*</span>
											</label>
										<div class="col-sm-8">
											<input type="text" class="form-control" id="ifsccode" value="<?php echo @$ArrBasicInfo['ifsccode']; ?>" placeholder="Free Text">
											<div class="herr" id="Errifsccode"></div>
										</div>
									</div>
								</div>
								<div class="col-md-12">
									<div class="form-group">
											<label for="id-form-field-focus-1" class="col-sm-3 control-label">
											SWIFT <span class="mandatory">*</span>
											</label>
										<div class="col-sm-8">
											<input type="text" class="form-control" id="swiftcode" value="<?php echo @$ArrBasicInfo['swiftcode']; ?>" placeholder="Free Text">
											<div class="herr" id="Errswiftcode"></div>
										</div>
									</div>
								</div>
                                <div class="col-md-12">
                                <div class="form-group">
										<label for="id-form-field-focus-1" class="col-sm-3 control-label">
											Status <span class="mandatory">*</span>
										</label>
										<div class="col-sm-8">
											<select name="status" id="status" class="form-control">
												<option value="">Select</option>
												<?php  $ArrStatus = unserialize(ARRSTATUS);
												foreach ($ArrStatus as $VarKey => $VarStatus) { ?>
													<option
														value="<?php echo $VarKey ?>" <?php if (@$ArrBasicInfo['status'] == $VarKey) {
														echo "selected";
													} ?>><?php echo $VarStatus ?></option>
												<?php } ?>
											</select>
											<div class="herr" id="Errstatus"></div>
										</div>
                                </div>
                            </div>
                    </div>
                </div>
               <div class="row">
        <div class="col-xs-12 py-4" style="padding-right:30px">
        <button class="btn btn-info pull-right mx-2" id="enqsvbtn" onclick="return fnSave();">Save</button>
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
    
    if(lasturi=='edit'){
        $('#enqsvbtn').hide();
        $("#custom_form input").prop("disabled", true);
        $("#custom_form select").prop("disabled", true);
        $("#custom_form textarea").prop("disabled", true);
    } else { 
       $('#enqsvbtn').show();
    }
    
</script>
<script src="<?php echo base_url() ?>assets/js/<?php echo CNFCOMPANY ?>consignee.js"></script>
<?php $this->load->view(CNFCOMPANY . 'template/pagefooter'); ?>