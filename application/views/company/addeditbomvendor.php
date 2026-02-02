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
                <h2 class="page-header text-royal-black mx-4" style="border-bottom:0px"> <?php echo (empty($Edit) && empty($VarId)) ? 'Add New BOM Vendor Details':'View / Edit BOM Vendor Details' ?>
                  <div class="pull-right">
                                    <div class="ml-auto pr-3">
                                    <a href="<?php echo base_url(CNFCOMPANY . 'mbomvendor/managebomvendor') ?>" id="backbtn" class="btn custbtn btn-sm mx-3 btn-royal-blue btn-text-slide-x">Back</a>
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
                                    BOM Vendor Name  <span class="mandatory">*</span>
                                    </label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="frmBasicVendor" value="<?php echo @$ArrBasicInfo['vendorname']; ?>" placeholder="Free Text">
                                    <div class="herr" id="ErrfrmBasicVendor"></div>
                                </div>
                            </div>
                        </div>
						<div class="col-md-12">
                            <div class="form-group">
                                    <label for="id-form-field-focus-1" class="col-sm-4 control-label">
                                    Address  <span class="mandatory">*</span>
                                    </label>
                                <div class="col-sm-8">
                                    <textarea class="form-control" id="frmBasicSAddr" style="height: 89px;" placeholder="Free Text"><?php echo @$ArrBasicInfo['address']; ?></textarea>
                                    <div class="herr" id="ErrfrmBasicSAddr"></div>
                                </div>
                            </div>
                        </div>
						<div class="col-md-12">
                            <div class="form-group">
                                    <label for="id-form-field-focus-1" class="col-sm-4 control-label">
                                    Contact Person <span class="mandatory">*</span>
                                    </label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="frmBasicContactname" value="<?php echo @$ArrBasicInfo['contactpersonname']; ?>" placeholder="Free Text">
                                    <div class="herr" id="ErrfrmBasicContactname"></div>
                                </div>
                            </div>
                        </div>
						<div class="col-md-12">
                            <div class="form-group">
                                    <label for="id-form-field-focus-1" class="col-sm-4 control-label">
                                    E-mail Id <span class="mandatory">*</span>
                                    </label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="frmBasicEmailId"  value="<?php echo @$ArrBasicInfo['emailid']; ?>" placeholder="Free Text">
                                    <div class="herr" id="ErrfrmBasicEmailId"></div>
                                </div>
                            </div>
                        </div>
						<div class="col-md-12">
                            <div class="form-group">
                                    <label for="id-form-field-focus-1" class="col-sm-4 control-label">
                                    Phone No 
                                    <!--<span class="mandatory">*</span>-->
                                    </label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" onkeypress="return onlyNumbernodecimal(event);" id="frmBasicPhone" value="<?php echo @$ArrBasicInfo['phone']; ?>" placeholder="Free Text">
                                    <!--<div class="herr" id="ErrfrmBasicPhone"></div>-->
                                </div>
                            </div>
                        </div>
						<div class="col-md-12">
                            <div class="form-group">
                                    <label for="id-form-field-focus-1" class="col-sm-4 control-label">
                                    Mobile No <span class="mandatory">*</span>
                                    </label>
                                <div class="col-sm-8">
                                    <input type="text" onkeypress="return onlyNumbernodecimal(event);" class="form-control" id="frmBasicMobile" value="<?php echo @$ArrBasicInfo['mobile']; ?>" placeholder="Free Text">
                                    <div class="herr" id="ErrfrmBasicMobile"></div>
                                </div>
                            </div>
                        </div>
						<div class="col-md-12">
                            <div class="form-group">
                                    <label for="id-form-field-focus-1" class="col-sm-4 control-label">
                                    Vendor Category  <span class="mandatory">*</span>
                                    </label>
                                <div class="col-sm-8">
                                    <select name="frmvendor_categoryid" id="frmvendor_categoryid" class="form-control">
											<option value="">Select</option>
											<?php  $ArrVendorCategory = unserialize(ARRVENDORCATEGORY);
											foreach ($ArrVendorCategory as $VarKey => $VarStatus) { ?>
												<option
													value="<?php echo $VarKey ?>" <?php if (@$ArrBasicInfo['vendor_category_id'] == $VarKey) {
													echo "selected";
												} ?>><?php echo $VarStatus ?></option>
											<?php } ?>
										</select>
                                    <div class="herr" id="Errfrmvendor_categoryid"></div>
                                </div>
                            </div>
                        </div>
						<div class="col-md-12">
                            <div class="form-group">
                                    <label for="id-form-field-focus-1" class="col-sm-4 control-label">
                                    Primary Product Line  <span class="mandatory">*</span>
                                    </label>
                                <div class="col-sm-8">
                                    <input type="text"  class="form-control" id="frmprimary_pdtline" value="<?php echo @$ArrBasicInfo['primary_pdtline']; ?>" placeholder="Free Text">
                                    <div class="herr" id="Errfrmprimary_pdtline"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
							   <div class="col-md-12">
									<div class="form-group">
										<label for="id-form-field-focus-1" class="col-sm-3 control-label">
											Secondary Product Line 
											<!--<span class="mandatory">*</span>-->
										</label>
										<div class="col-sm-8">
										<input type="text"  class="form-control" id="frmsecondary_pdtline" value="<?php echo @$ArrBasicInfo['secondary_pdtline']; ?>" placeholder="Free Text">
										</div>
									</div>
								</div>
								<div class="col-md-12">
									<div class="form-group">
											<label for="id-form-field-focus-1" class="col-sm-3 control-label">
											GST No <span class="mandatory">*</span>
											</label>
										<div class="col-sm-8">
											<input type="text" class="form-control" id="frmBasicGstno" value="<?php echo @$ArrBasicInfo['gstno']; ?>" placeholder="Free Text">
											<div class="herr" id="ErrfrmBasicGstno"></div>
										</div>
									</div>
								</div>
								<div class="col-md-12">
									<div class="form-group">
											<label for="id-form-field-focus-1" class="col-sm-3 control-label">
											IE Code <span class="mandatory">*</span>
											</label>
										<div class="col-sm-8">
											<input type="text" class="form-control" id="frmBasicIecode" value="<?php echo @$ArrBasicInfo['iecode']; ?>" placeholder="Free Text">
											<div class="herr" id="ErrfrmBasicIecode"></div>
										</div>
									</div>
								</div>
								<div class="col-md-12">
									<div class="form-group">
											<label for="id-form-field-focus-1" class="col-sm-3 control-label">
											Bank Name <span class="mandatory">*</span>
											</label>
										<div class="col-sm-8">
											<input type="text" class="form-control" id="frmBasicBankname" value="<?php echo @$ArrBasicInfo['bankname']; ?>" placeholder="Free Text">
											<div class="herr" id="ErrfrmBasicBankname"></div>
										</div>
									</div>
								</div>
								<div class="col-md-12">
									<div class="form-group">
											<label for="id-form-field-focus-1" class="col-sm-3 control-label">
											Account Name <span class="mandatory">*</span>
											</label>
										<div class="col-sm-8">
											<input type="text" class="form-control" id="frmBasicAccountname" value="<?php echo @$ArrBasicInfo['accountname']; ?>" placeholder="Free Text">
											<div class="herr" id="ErrfrmBasicAccountname"></div>
										</div>
									</div>
								</div>
								<div class="col-md-12">
									<div class="form-group">
											<label for="id-form-field-focus-1" class="col-sm-3 control-label">
											Account No <span class="mandatory">*</span>
											</label>
										<div class="col-sm-8">
											<input type="text" class="form-control" id="frmBasicAccountno" value="<?php echo @$ArrBasicInfo['accountno']; ?>" placeholder="Free Text">
											<div class="herr" id="ErrfrmBasicAccountno"></div>
										</div>
									</div>
								</div>
								<div class="col-md-12">
									<div class="form-group">
											<label for="id-form-field-focus-1" class="col-sm-3 control-label">
											IFSC <span class="mandatory">*</span>
											</label>
										<div class="col-sm-8">
											<input type="text" class="form-control" id="frmBasicIfscode" value="<?php echo @$ArrBasicInfo['ifscode']; ?>" placeholder="Free Text">
											<div class="herr" id="ErrfrmBasicIfscode"></div>
										</div>
									</div>
								</div>
								<div class="col-md-12">
									<div class="form-group">
											<label for="id-form-field-focus-1" class="col-sm-3 control-label">
											SWIFT <span class="mandatory">*</span>
											</label>
										<div class="col-sm-8">
											<input type="text" class="form-control" id="frmBasicSwiftcode" value="<?php echo @$ArrBasicInfo['swiftcode']; ?>" placeholder="Free Text">
											<div class="herr" id="ErrfrmBasicSwiftcode"></div>
										</div>
									</div>
								</div>
                                <div class="col-md-12">
                                <div class="form-group">
										<label for="id-form-field-focus-1" class="col-sm-3 control-label">
											Status <span class="mandatory">*</span>
										</label>
										<div class="col-sm-8">
											<select name="frmBasicStatus" id="frmBasicStatus" class="form-control">
												<option value="">Select</option>
												<?php  $ArrStatus = unserialize(ARRSTATUS);
												foreach ($ArrStatus as $VarKey => $VarStatus) { ?>
													<option
														value="<?php echo $VarKey ?>" <?php if (@$ArrBasicInfo['status'] == $VarKey) {
														echo "selected";
													} ?>><?php echo $VarStatus ?></option>
												<?php } ?>
											</select>
											<div class="herr" id="ErrfrmBasicStatus"></div>
										</div>
                                </div>
                            </div>
                    </div>
                </div>
               <div class="row">
        <div class="col-xs-12 py-4" style="padding-right:30px">
        <button class="btn btn-info pull-right mx-2" id="enqsvbtn" onclick="return fnSaveBomVendorInfo();">Save</button>
    </div></div>
        </section>     
     </section>
    </div>
    <div class="content-wrapper hide">
        <div class="col-md-12">
            <section class="content-header">
                <h1 class="firstHeading">Bill of Material
                    Vendor <?php if (empty($Edit) && !empty($VarId)) echo ''; else echo '- Add / Edit' ?></h1>
            </section>
        </div>
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div id="DivContBasicInfo">
                        <div class="box box-info">
                            <div class="box-header with-border">
                                <h3 class="box-title">Basic Information</h3>
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
                                                <label for="" class="col-sm-4 control-label">Vendor</label>
                                                <div class="col-sm-8">
                                                    <input type="text" name="frmBasicVendor" class="form-control"
                                                           id="frmBasicVendor" placeholder="Vendor Name"
                                                           value="<?php echo @$ArrBasicInfo['vendorname']; ?>">
                                                    <div class="herr" id="ErrfrmBasicVendor"></div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="" class="col-sm-4 control-label">Contact Name</label>
                                                <div class="col-sm-8">
                                                    <input type="text" name="frmBasicContactname" class="form-control"
                                                           id="frmBasicContactname" placeholder="Contact Name"
                                                           value="<?php echo @$ArrBasicInfo['contactpersonname']; ?>">
                                                    <div class="herr" id="ErrfrmBasicContactname"></div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="" class="col-sm-4 control-label">Address</label>
                                                <div class="col-sm-8">
                                                    <textarea id="frmBasicSAddr" class="form-control"
                                                              placeholder="Address"><?php echo @$ArrBasicInfo['address'] ?></textarea>
                                                    <div class="herr" id="ErrfrmBasicSAddr"></div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="" class="col-sm-4 control-label">E-mail Id</label>
                                                <div class="col-sm-8">
                                                    <input type="text" name="frmBasicEmailId" class="form-control"
                                                           id="frmBasicEmailId" placeholder="E-mail Id"
                                                           value="<?php echo @$ArrBasicInfo['emailid']; ?>">
                                                    <div class="herr" id="ErrfrmBasicEmailId"></div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="" class="col-sm-4 control-label">Phone No.</label>
                                                <div class="col-sm-8">
                                                    <input type="text" name="frmBasicPhone" class="form-control"
                                                           id="frmBasicPhone" placeholder="Phone No."
                                                           value="<?php echo @$ArrBasicInfo['phone']; ?>">
                                                    <div class="herr" id="ErrfrmBasicPhone"></div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="" class="col-sm-4 control-label">Mobile No</label>
                                                <div class="col-sm-8">
                                                    <input type="text" name="" class="form-control" id="frmBasicMobile"
                                                           placeholder="Mobile No."
                                                           value="<?php echo @$ArrBasicInfo['mobile']; ?>">
                                                    <div class="herr" id="ErrfrmBasicMobile"></div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="" class="col-sm-4 control-label">GST No</label>
                                                <div class="col-sm-8">
                                                    <input type="text" id="frmBasicGstno" class="form-control"
                                                           placeholder="GST No."
                                                           value="<?php echo @$ArrBasicInfo['gstno'] ?>">
                                                    <div class="herr" id="ErrfrmBasicGstno"></div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="" class="col-sm-4 control-label">IE Code</label>
                                                <div class="col-sm-8">
                                                    <input type="text" id="frmBasicIecode" class="form-control"
                                                           placeholder="IE Code"
                                                           value="<?php echo @$ArrBasicInfo['iecode'] ?>">
                                                    <div class="herr" id="ErrfrmBasicIecode"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-md-6 col-sm-6 col-lg-6">
                                            <div class="form-group">
                                                <label for="" class="col-sm-4 control-label">Bank Name</label>
                                                <div class="col-sm-8">
                                                    <input type="text" name="" class="form-control"
                                                           id="frmBasicBankname" placeholder="Bank Name"
                                                           value="<?php echo @$ArrBasicInfo['bankname']; ?>">
                                                    <div class="herr" id="ErrfrmBasicBankname"></div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="" class="col-sm-4 control-label">Account Name</label>
                                                <div class="col-sm-8">
                                                    <input type="text" name="" class="form-control"
                                                           id="frmBasicAccountname" placeholder="Account Name"
                                                           value="<?php echo @$ArrBasicInfo['accountname']; ?>">
                                                    <div class="herr" id="ErrfrmBasicAccountname"></div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="" class="col-sm-4 control-label">Account No.</label>
                                                <div class="col-sm-8">
                                                    <input type="text" name="" class="form-control"
                                                           id="frmBasicAccountno" placeholder="Account No."
                                                           value="<?php echo @$ArrBasicInfo['accountno']; ?>">
                                                    <div class="herr" id="ErrfrmBasicAccountno"></div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="" class="col-sm-4 control-label">IFS Code</label>
                                                <div class="col-sm-8">
                                                    <input type="text" name="" class="form-control" id="frmBasicIfscode"
                                                           placeholder="IFS Code"
                                                           value="<?php echo @$ArrBasicInfo['ifscode']; ?>">
                                                    <div class="herr" id="ErrfrmBasicIfscode"></div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="" class="col-sm-4 control-label">RTGS</label>
                                                <div class="col-sm-8">
                                                    <input type="text" name="" class="form-control" id="frmBasicRtgs"
                                                           placeholder="RTGS"
                                                           value="<?php echo @$ArrBasicInfo['rtgs']; ?>">
                                                    <div class="herr" id="ErrfrmBasicRtgs"></div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="" class="col-sm-4 control-label">SWIFT Code</label>
                                                <div class="col-sm-8">
                                                    <input type="text" name="" class="form-control"
                                                           id="frmBasicSwiftcode" placeholder="SWIFT Code"
                                                           value="<?php echo @$ArrBasicInfo['swiftcode']; ?>">
                                                    <div class="herr" id="ErrfrmBasicSwiftcode"></div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="" class="col-sm-4 control-label">IBAN</label>
                                                <div class="col-sm-8">
                                                    <input type="text" name="" class="form-control" id="frmBasicIban"
                                                           placeholder="IBAN"
                                                           value="<?php echo @$ArrBasicInfo['iban']; ?>">
                                                    <div class="herr" id="ErrfrmBasicIban"></div>
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
                                                                value="<?php echo $VarKey ?>" <?php if (@$ArrBasicInfo['status'] == $VarKey) {
                                                                echo "selected";
                                                            } ?>><?php echo $VarStatus ?></option>
                                                        <?php } ?>
                                                    </select>
                                                    <div class="herr" id="ErrfrmBasicStatus"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12 herr" id="AnyErrElse"></div>
                                        <?php
                                    } else {
                                        ?>
                                        <div class="col-xs-12 col-md-6 col-sm-6 col-lg-6">
                                            <div class="form-group">
                                                <label for="" class="col-sm-4 text-right">Vendor</label>
                                                <div class="col-sm-8" id="divDispBasicName">
                                                    <?php echo $ArrBasicInfo['vendorname']; ?>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="" class="col-sm-4 text-right">Contact Name</label>
                                                <div class="col-sm-8" id="divDispBasicName">
                                                    <?php echo @$ArrBasicInfo['contactpersonname']; ?>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="" class="col-sm-4 text-right">Address</label>
                                                <div class="col-sm-8" id="divDispBasicName">
                                                    <?php echo @$ArrBasicInfo['address']; ?>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="" class="col-sm-4 text-right">E-mail Id</label>
                                                <div class="col-sm-8" id="divDispBasicName">
                                                    <?php echo @$ArrBasicInfo['emailid']; ?>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="" class="col-sm-4 text-right">Phone No</label>
                                                <div class="col-sm-8" id="divDispBasicName">
                                                    <?php echo @$ArrBasicInfo['phone']; ?>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="" class="col-sm-4 text-right">Mobile No</label>
                                                <div class="col-sm-8" id="divDispBasicName">
                                                    <?php echo @$ArrBasicInfo['mobile']; ?>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="" class="col-sm-4 text-right">GST No</label>
                                                <div class="col-sm-8" id="">
                                                    <?php echo @$ArrBasicInfo['gstno']; ?>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="" class="col-sm-4 text-right">IE Code</label>
                                                <div class="col-sm-8" id="">
                                                    <?php echo @$ArrBasicInfo['iecode']; ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-md-6 col-sm-6 col-lg-6">
                                            <div class="form-group">
                                                <label for="" class="col-sm-4 text-right">Bank Name</label>
                                                <div class="col-sm-8" id="">
                                                    <?php echo @$ArrBasicInfo['bankname']; ?>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="" class="col-sm-4 text-right">Account Name</label>
                                                <div class="col-sm-8" id="">
                                                    <?php echo @$ArrBasicInfo['accountname']; ?>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="" class="col-sm-4 text-right">Account No</label>
                                                <div class="col-sm-8" id="">
                                                    <?php echo @$ArrBasicInfo['accountno']; ?>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="" class="col-sm-4 text-right">IFS Code</label>
                                                <div class="col-sm-8" id="">
                                                    <?php echo @$ArrBasicInfo['ifscode']; ?>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="" class="col-sm-4 text-right">RTGS</label>
                                                <div class="col-sm-8" id="">
                                                    <?php echo @$ArrBasicInfo['rtgs']; ?>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="" class="col-sm-4 text-right">SWIFT Code</label>
                                                <div class="col-sm-8" id="">
                                                    <?php echo @$ArrBasicInfo['swiftcode']; ?>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="" class="col-sm-4 text-right">IBAN</label>
                                                <div class="col-sm-8" id="">
                                                    <?php echo @$ArrBasicInfo['iban']; ?>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="" class="col-sm-4 text-right">Status</label>
                                                <div class="col-sm-8" id="divDispStatus">
                                                    <?php
                                                    echo @$ArrStatus[$ArrBasicInfo['status']];
                                                    ?>
                                                </div>
                                            </div>
                                        </div>
                                        <?php
                                    } ?>
                                </div>
                                <div class="box-footer boxFooter_pd1025">
                                    <a href="<?php echo base_url(CNFCOMPANY . 'mbomvendor/managebomvendor') ?>"
                                       class="btn btn-default">Back</a>
                                    <button type="button"
                                            class="btn btn-info pull-right <?php if ($Edit != 'edit' && !empty($VarId)) echo 'hide' ?>"
                                            onclick="return fnSaveBomVendorInfo();">Save Changes
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
    var GlbId = "<?php echo $VarId ?>";
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
<script src="<?php echo base_url(); ?>assets/js/<?php echo CNFCOMPANY ?>mbomvendor.js"></script>
<?php $this->load->view(CNFCOMPANY . 'template/pagefooter'); ?>