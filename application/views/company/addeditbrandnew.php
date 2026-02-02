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
                <h2 class="page-header text-royal-black mx-4" style="border-bottom:0px"> <?php echo (empty($Edit) && empty($VarId)) ? 'Add New Brand Details':'View / Edit Brand Details' ?>
                  <div class="pull-right">
                                    <div class="ml-auto pr-3">
                                    <a href="<?php echo base_url(CNFCOMPANY . 'brand/manage') ?>" id="backbtn" class="btn custbtn btn-sm mx-3 btn-royal-blue btn-text-slide-x">Back</a>
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
                                    Brand Name  <span class="mandatory">*</span>
                                    </label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="brandname" value="<?php echo @$ArrBasicInfo['brandname']; ?>" placeholder="Free Text">
                                    <div class="herr" id="Errbrandname"></div>
                                </div>
                            </div>
                        </div>
						<div class="col-md-12">
                            <div class="form-group">
                                    <label for="id-form-field-focus-1" class="col-sm-4 control-label">
                                    Buyer Name  <span class="mandatory">*</span>
                                    </label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="buyername" value="<?php echo @$ArrBasicInfo['buyername']; ?>" placeholder="Free Text">
                                    <div class="herr" id="Errbuyername"></div>
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
                    </div>
                    <div class="col-md-6">
					
							<div class="col-md-12">
								<div class="form-group">
										<label for="id-form-field-focus-1" class="col-sm-3 control-label">
										Contact Person <span class="mandatory">*</span>
										</label>
									<div class="col-sm-8">
										<input type="text" class="form-control" id="contactperson" value="<?php echo @$ArrBasicInfo['contactperson']; ?>" placeholder="Free Text">
										<div class="herr" id="Errcontactperson"></div>
									</div>
								</div>
							</div>
							<div class="col-md-12">
								<div class="form-group">
										<label for="id-form-field-focus-1" class="col-sm-3 control-label">
										Brand Business Type <span class="mandatory">*</span>
										</label>
									<div class="col-sm-8">
										<input type="text" class="form-control" id="brand_businesstype" value="<?php echo @$ArrBasicInfo['brand_businesstype']; ?>" placeholder="Free Text">
										<div class="herr" id="Errbrand_businesstype"></div>
									</div>
								</div>
							</div>
							<div class="col-md-12">
								<div class="form-group">
										<label for="id-form-field-focus-1" class="col-sm-3 control-label">
										Brand Fashion Type  <span class="mandatory">*</span>
										</label>
									<div class="col-sm-8">
										<input type="text" class="form-control" id="brand_fashiontype" value="<?php echo @$ArrBasicInfo['brand_fashiontype']; ?>" placeholder="Free Text">
										<div class="herr" id="Errbrand_fashiontype"></div>
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
    <div class="content-wrapper hide">
        <div class="col-md-12">
            <section class="content-header">
                <h1 class="firstHeading">
                    Brand <?php if (empty($Edit) && !empty($VarId)) echo ''; else echo '- Add / Edit' ?></h1>
            </section>
        </div>
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div id="DivContBasicInfo">
                        <div class="box box-info">
                            <div class="box-header with-border">
                                <h3 class="box-title">Basic Information</h3>
                                <div class="box-tools pull-right">
                                </div>
                            </div>
                            <form class="form-horizontal" name="frmBasicInfo" id="frmBasicInfo" method="post">
                                <div class="box-body pdt20_pdb0">
                                    <?php
                                    $ArrStatus = unserialize(ARRSTATUS);
                                    if ($Edit == 'edit' || empty($VarId)) {
                                        ?>
                                        <div class="alert alert-success alert-dismissable hide"
                                             id="divSuccessBasicInfoMsg"></div>
                                        <div class="col-xs-12 col-md-6 col-sm-6 col-lg-6">
                                            <div class="form-group">
                                                <label for="" class="col-sm-4 control-label">Brand Name</label>
                                                <div class="col-sm-8">
                                                    <input type="text" name="frmBasicBrand" class="form-control"
                                                           id="frmBasicBrand" placeholder="Brand Name"
                                                           value="<?php echo @$brands[0]->brandname; ?>">
                                                    <div class="herr" id="ErrfrmBasicBrand"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-md-6 col-sm-6 col-lg-6">
                                            <div class="form-group">
                                                <label for="" class="col-sm-4 control-label">Select Buyer</label>
                                                <div class="col-sm-8">
                                                    <select name="frmBasicBuyerId" id="frmBasicBuyerId"
                                                            class="form-control">
                                                        <option value="">Select</option>
                                                        <?php
                                                        foreach ($ArrBuyers as $VarKey => $item) { ?>
                                                            <option
                                                                value="<?php echo $item->id ?>" <?php if (@$brands[0]->ref_buyerid == $item->id) {
                                                                echo "selected";
                                                            } ?>><?php echo $item->buyername ?></option>
                                                        <?php } ?>
                                                    </select>
                                                    <div class="herr" id="ErrfrmBasicBuyerId"></div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="frmBasicStatus"
                                                       class="col-sm-4 control-label">Status</label>
                                                <div class="col-sm-8">
                                                    <select name="frmBasicStatus" id="frmBasicStatus"
                                                            class="form-control">
                                                        <option value="">Select</option>
                                                        <?php
                                                        foreach ($ArrStatus as $VarKey => $VarStatus) { ?>
                                                            <option
                                                                value="<?php echo $VarKey ?>" <?php if (@$brands[0]->status == $VarKey) {
                                                                echo "selected";
                                                            } ?>><?php echo $VarStatus ?></option>
                                                        <?php } ?>
                                                    </select>
                                                    <div class="herr" id="ErrBasicStatus"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12 herr" id="AnyErrElse"></div>
                                        <?php
                                    } else {
                                        ?>
                                        <div class="col-xs-12 col-md-6 col-sm-6 col-lg-6">
                                            <div class="form-group">
                                                <label for="" class="col-sm-4 control-label">Brand Name</label>
                                                <?php echo @$brands[0]->brandname; ?>
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-md-6 col-sm-6 col-lg-6">
                                            <div class="form-group">
                                                <label for="" class="col-sm-4 control-label">Buyer</label>
                                                <div class="col-sm-8">
                                                    <?php
                                                    foreach ($ArrBuyers as $VarKey => $item) {
                                                        if (@$brands[0]->ref_buyerid == $item->id)
                                                            echo $item->buyername;
                                                    }
                                                    ?>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="" class="col-sm-4 control-label">Status</label>
                                                <div class="col-sm-8">
                                                    <?php
                                                    echo @$ArrStatus[$brands[0]->status];
                                                    ?>
                                                </div>
                                            </div>
                                        </div>
                                        <?php
                                    }
                                    ?>
                                </div>
                                <div class="box-footer boxFooter_pd1025">
                                    <a href="<?php echo base_url(CNFCOMPANY . 'brand/manage') ?>"
                                       class="btn btn-default">Back</a>
                                    <button type="button"
                                            class="btn btn-info pull-right <?php if ($Edit != 'edit' && !empty($VarId)) echo 'hide' ?>"
                                            onclick="return fnSave();">Save
                                        Changes
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
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
<script src="<?php echo base_url() ?>assets/js/<?php echo CNFCOMPANY ?>brandbuyer.js"></script>
<?php $this->load->view(CNFCOMPANY . 'template/pagefooter'); ?>