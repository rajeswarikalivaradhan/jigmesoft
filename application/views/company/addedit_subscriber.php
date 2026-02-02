<?php $this->load->view(CNFCOMPANY . 'template/pageheader'); ?>
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css" />
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
                <h2 class="page-header text-royal-black mx-4" style="border-bottom:0px"> <?php echo (empty($Edit) && empty($VarId)) ? 'Add New User Details':'View / Edit User Details' ?>
                  <div class="pull-right">
                                    <div class="ml-auto pr-3">
                                    <a href="<?php echo base_url(CNFCOMPANY . 'muser/manage') ?>" id="backbtn" class="btn custbtn btn-sm mx-3 btn-royal-blue btn-text-slide-x">Back</a>
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
                                    Department <span class="mandatory">*</span>
                                    </label>
                                <div class="col-sm-8">
                                    <input type="hidden" class="form-control" id="subscriber_id" value="<?php if(isset($subscriber_id) && !empty($subscriber_id)) { echo @$subscriber_id;} ?>" placeholder="Free Text">
                                    <input type="hidden" class="form-control" id="proforma_id" value="<?php if(isset($proforma_id) && !empty($proforma_id)) { echo @$proforma_id;} ?>">
                                    <select name="department_id" id="department_id" class="form-control">
											<option value="">Select</option>
											<?php  //$ArrUserTypes = unserialize(ARRUSERTYPE);
											if (!isset($Edit) && array_key_exists(1, $ARRSUBUSERTYPE)) {
                                                unset($ARRSUBUSERTYPE[1]);
                                            }
											foreach ($ARRSUBUSERTYPE as $Key => $value) { ?>
												<option
													value="<?php echo $Key ?>" <?php if (!empty($ArrBasicInfo['usertype']) && @$ArrBasicInfo['usertype'] == $Key) {
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
                                    <!--<input type="hidden" class="form-control" id="username" value="<?php echo @$ArrBasicInfo['username']; ?>" placeholder="Free Text">-->
                                    <div class="col-sm-6 pr-0">
                                        <input type="text" class="form-control" id="login_prefix" value="<?php if (isset($loginprefix) && !empty($loginprefix)){ echo $loginprefix; }?>"   placeholder="Free Text" >
                                        </div>
                                        <div class="col-sm-6 pl-0">
                                        <input type="text" class="form-control" id="login_suffix" value="<?php if (isset($loginsuffix)){ echo $loginsuffix; } ?>" disabled placeholder="Free Text" >
                                        </div>
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
                                    <input type="text" class="form-control" id="password"  onblur="validatePasswordForm(this.value)" value="<?php echo @$ArrBasicInfo['password']; ?>" placeholder="Free Text">
                                    <div class="herr" id="Errpassword"></div>
                                </div>
                            </div>
                        </div>
                         <div class="col-md-12">
                            <div class="form-group">
                                    <label for="id-form-field-focus-1" class="col-sm-4 control-label">
                                    Pin <span class="mandatory">*</span>
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
											Date of Joining 
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
											Current Salary Package
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
											Bank Name 
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
											Account Name 
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
											Account No 
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
											IFSC
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
											SWIFT 
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
    
    if(lasturi=='edit'){
        $('#enqsvbtn').hide();
        $("#custom_form input").prop("disabled", true);
        $("#custom_form select").prop("disabled", true);
        $("#custom_form textarea").prop("disabled", true);
    } else { 
       $('#enqsvbtn').show();
    }
    
   function validatePasswordForm(password) {
            // Get the password input
            //var password = document.getElementById("password").value;

            // Define the regular expressions for validation
            var minLength = 8;
            var maxLength = 40;
            var upperCaseRegex = /[A-Z]/;
            var lowerCaseRegex = /[a-z]/;
           // var alphaNumericRegex = /^[a-zA-Z0-9]+$/;
            var alphabeticCharacters = /[a-zA-Z]+/;  // Check if password contains at least one alphabetic character
            var specialCharRegex = /[!@#$%^&*()_+{}\[\]:;<>,.?~\\/-]/;
            const numericCharacters = /[0-9]+/; // Check if password contains at least one numeric character
            if(password.length>0){
            // Check if the password meets all criteria
            if (password.length < minLength || password.length > maxLength) {
                alert("Password must be between 8 and 40 characters.");
                return false;
            }

            if (!upperCaseRegex.test(password)) {
                alert("Password must contain at least one uppercase letter.");
                return false;
            }

            if (!lowerCaseRegex.test(password)) {
                alert("Password must contain at least one lowercase letter.");
                return false;
            }
            
            if (!alphabeticCharacters.test(password)) {
               alert("Password must contain at least one alphabetic character.");
                return false;
            }
            
            if (!numericCharacters.test(password)) {
                alert("Password must contain at least one numeric character.");
                return false;
            }
            
            // if (!alphaNumericRegex.test(password)) {
            //     alert("Password must be alphanumeric.");
            //     return false;
            // }

            if (!specialCharRegex.test(password)) {
                alert("Password must contain at least one special character.");
                return false;
            }
            
            // If all criteria are met, the form can be submitted
           // alert("Password is valid!");

           
            // $('#frmBasicConfirmPwd').prop( "disabled", false );
             return true;
        }
        }

        function validationpin(pin){
             var pinRegex = /^\d{4}$/;
             if (!alphabeticCharacters.test(pin)) {
               alert("Please enter a valid 4-digit PIN (numbers only).");
                return false;
            }
             
        }

</script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/custom/datepair.js"></script>
<script src="<?php echo base_url(); ?>assets/js/<?php echo CNFCOMPANY ?>msubscriberuser.js"></script>
<?php $this->load->view(CNFCOMPANY . 'template/pagefooter'); ?>