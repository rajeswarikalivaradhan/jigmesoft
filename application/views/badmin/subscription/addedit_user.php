<?php $this->load->view(CNFCOMPANY . 'template/pageheader'); ?>
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css" />
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
                <h2 class="page-header text-royal-black mx-4" style="border-bottom:0px"> <?php echo (empty($Edit) && empty($VarId)) ? 'Add New User Details':'View Subscriber User Details' ?>
                  <div class="pull-right">
                                    <div class="ml-auto pr-3">
                                    <a  id="backbtn" class="btn custbtn btn-sm mx-3 btn-royal-blue btn-text-slide-x">Back</a>
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
                                    Department <span class="mandatory">*</span>
                                    </label>
                                <div class="col-sm-8">
                                    <select name="department_id" id="department_id" class="form-control">
											<option value="">Select</option>
											<?php  $ArrUserTypes = unserialize(ARRUSERTYPE); 
											foreach ($ArrUserTypes as $Key => $value) { ?>
												<option
													value="<?php echo $Key ?>" <?php if (isset($ArrBasicInfo['usertype']) && @$ArrBasicInfo['usertype'] == $Key) {
													echo "selected";
												} ?>><?php echo $value ?></option>
											<?php } ?>
										</select>
                                    <div class="herr" id="Errdepartment_id"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                    <label for="id-form-field-focus-1" class="col-sm-4 control-label">
                                    User Count  <span class="mandatory">*</span>
                                    </label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="dept_usercount" disabled value="<?php echo @$ArrBasicInfo['dept_usercount']; ?>" placeholder="Auto Populate">
                                    <div class="herr" id="Errdept_usercount"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                    <label for="id-form-field-focus-1" class="col-sm-4 control-label">
                                    Designation <span class="mandatory">*</span>
                                    </label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="designation" value="<?php echo @$ArrBasicInfo['designation']; ?>" placeholder="Free Text">
                                    <div class="herr" id="Errdesignation"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                    <label for="id-form-field-focus-1" class="col-sm-4 control-label">
                                    User Name <span class="mandatory">*</span>
                                    </label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="contactname" value="<?php echo @$ArrBasicInfo['contactname']; ?>" placeholder="Free Text">
                                    <div class="herr" id="Errcontactname"></div>
                                </div>
                            </div>
                        </div>
						<div class="col-md-12">
                            <div class="form-group">
                                    <label for="id-form-field-focus-1" class="col-sm-4 control-label">
                                    Communication Address  <span class="mandatory">*</span>
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
                                    Log-in ID <span class="mandatory">*</span>
                                    </label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="username" value="<?php echo @$ArrBasicInfo['username']; ?>" placeholder="Free Text">
                                    <div class="herr" id="Errusername"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                    <label for="id-form-field-focus-1" class="col-sm-4 control-label">
                                    Password <span class="mandatory">*</span>
                                    </label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="password"  value="<?php echo @$ArrBasicInfo['password']; ?>" placeholder="Free Text">
                                    <div class="herr" id="Errpassword"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                    <label for="id-form-field-focus-1" class="col-sm-4 control-label">
                                    pin <span class="mandatory">*</span>
                                    </label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="merchantpin"  value="<?php echo @$ArrBasicInfo['pin']; ?>" placeholder="Free Text">
                                    <div class="herr" id="Errpin"></div>
                                </div>
                            </div>
                        </div>
						<div class="col-md-12">
                            <div class="form-group">
                                    <label for="id-form-field-focus-1" class="col-sm-4 control-label">
                                    E-mail Id <span class="mandatory">*</span>
                                    </label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="emailid"  value="<?php echo @$ArrBasicInfo['email_id']; ?>" placeholder="Free Text">
                                    <div class="herr" id="Erremailid"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
							   <div class="col-md-12">
                                    <div class="form-group">
                                            <label for="id-form-field-focus-1" class="col-sm-3 control-label">
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
											<label for="id-form-field-focus-1" class="col-sm-3 control-label">
											Date of Joining <span class="mandatory">*</span>
											</label>
										<div class="col-sm-8">
											<input type="text" class="form-control date" id="doj" value="<?php echo (isset($ArrBasicInfo['doj']) && $ArrBasicInfo['doj']!='0000-00-00')?  date('d-m-Y', strtotime($ArrBasicInfo['doj'])):''; ?>" placeholder="Free Text">
											<div class="herr" id="Errdoj"></div>
										</div>
									</div>
								</div>
								<div class="col-md-12">
									<div class="form-group">
											<label for="id-form-field-focus-1" class="col-sm-3 control-label">
											Current Salary Package <span class="mandatory">*</span>
											</label>
										<div class="col-sm-8">
											<input type="text" class="form-control" id="curr_salarypackage" value="<?php echo @$ArrBasicInfo['curr_salarypackage']; ?>" placeholder="Free Text">
											<div class="herr" id="Errcurr_salarypackage"></div>
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
	$(document).ready(function() {
        $('.date').datepicker({
            'format': 'dd-mm-yyyy',
            'autoclose': true,
            'orientation': "bottom",
            'todayHighlight':true,
        });
       
    });
    var GlbId = "<?php echo $VarId ?>";
    var lasturi='<?php echo $Edit?>';
    var Glbsubscriber_id='<?php echo $VarsubscriberId?>';
    var Glbproforma_id='<?php echo $Varproforma_id?>';
    
    if(lasturi=='edit'){
        $('#enqsvbtn').hide();
        $("#custom_form input").prop("disabled", true);
        $("#custom_form select").prop("disabled", true);
        $("#custom_form textarea").prop("disabled", true);
    } else { 
       $('#enqsvbtn').show();
    }
    
    $('#backbtn').on('click', function() {

let redirectpath = base_path + GlbBAdminFdr +  'msubscription/detviews/' + encodeURIComponent(base64_encode(Glbsubscriber_id)) + '/' + encodeURIComponent(base64_encode(Glbproforma_id));
             window.location.href = redirectpath;

});
$('#sublistbtns').on('click', function () {
    if (localStorage.getItem('lastActiveTab') !== null) {
    // Remove the last active tab from localStorage
    localStorage.removeItem('lastActiveTab');
    }
 });
</script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/custom/datepair.js"></script>
<script src="<?php echo base_url(); ?>assets/js/<?php echo CNFBADMIN ?>muser.js"></script>
<?php $this->load->view(CNFCOMPANY . 'template/pagefooter'); ?>