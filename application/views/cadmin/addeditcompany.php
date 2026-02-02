<?php $this->load->view(CNFCADMIN . 'template/pageheader'); ?>
    <body class="sidebar-mini skin-black wysihtml5-supported sidebar-collapse">
<div class="wrapper">
    <?php $this->load->view(CNFCADMIN . 'template/templateheader'); ?>
    <!-- Left side column. contains the logo and sidebar -->
    <aside class="main-sidebar">
        <?php $this->load->view(CNFCADMIN . 'template/templateleftmenu'); ?>
    </aside>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="col-md-12">
                <h1 class="firstHeading">
                    Company <?php if (empty($Edit) && !empty($VarId)) echo ''; else echo '- Add / Edit' ?></h1>
            </div>
        </section>
        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-info">
                        <div class="box-header with-border">
                            <h3 class="box-title">Basic Information</h3>
                        </div><!-- /.box-header -->
                        <div class="box-body pdt20_pdb0">
                            <form class="form-horizontal" name="frmBasicInfo" id="frmBasicInfo" method="post">
                                <?php
                                if ($Edit == 'edit' || empty($VarCompanyId)) {
                                    ?>
                                    <div class="col-xs-12 col-md-6 col-sm-6 col-lg-6">
                                        <div class="form-group">
                                            <label for="" class="col-sm-4 control-label">Company Name</label>
                                            <div class="col-sm-8">
                                                <input type="text" name="frmBasicCompanyName" class="form-control"
                                                       id="frmBasicCompanyName" placeholder="Company Name (Ex:Trends)"
                                                       value="<?php echo @$ArrCompanyBasicInfo['companyname']; ?>">
                                                <div class="herr" id="ErrBasicCompanyName"></div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="" class="col-sm-4 control-label">Contact Name</label>
                                            <div class="col-sm-8">
                                                <input type="text" name="frmBasicName" class="form-control"
                                                       id="frmBasicName" placeholder="Name"
                                                       value="<?php echo @$ArrCompanyBasicInfo['contactname']; ?>">
                                                <div class="herr" id="ErrBasicName"></div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="" class="col-sm-4 control-label">E-mail Id</label>
                                            <div class="col-sm-8">
                                                <input type="text" name="frmBasicCompanyEmail" class="form-control"
                                                       id="frmBasicCompanyEmail"
                                                       placeholder="Company E-mail Id" <?php if (!empty($ArrCompanyBasicInfo['username'])) echo 'readonly' ?>
                                                       value="<?php echo @$ArrCompanyBasicInfo['username']; ?>">
                                                <div class="herr" id="ErrfrmBasicCompanyEmail"></div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="" class="col-sm-4 control-label">Mobile No.</label>
                                            <div class="col-sm-8">
                                                <input type="text" name="frmBasicCompanyMobile" class="form-control"
                                                       id="frmBasicCompanyMobile" placeholder="Mobile No."
                                                       value="<?php echo @$ArrCompanyBasicInfo['mobile']; ?>">
                                                <div class="herr" id="ErrfrmBasicCompanyMobile"></div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="" class="col-sm-4 control-label">Business Type</label>
                                            <div class="col-sm-8">
                                                <select name="frmBasicBusinessType" id="frmBasicBusinessType"
                                                        class="form-control">
                                                    <option value="">Choose the Business Type</option>
                                                    <?php $ArrBusinessTypeList = unserialize(ARRCOMPANYBUSINESSTYPE);
                                                    foreach ($ArrBusinessTypeList as $VarBusinessId => $VarBusinessName) { ?>
                                                        <option
                                                            value="<?php echo $VarBusinessId ?>" <?php if ($VarBusinessId == @$ArrCompanyBasicInfo['businesstype']) echo "selected"; ?>>
                                                            <?php echo $VarBusinessName ?></option>
                                                    <?php } ?>
                                                </select>
                                                <div class="herr" id="ErrBasicBusinessType"></div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="" class="col-sm-4 control-label">Address</label>
                                            <div class="col-sm-8">
                                                <input type="text" name="frmBasicAddress" class="form-control"
                                                       id="frmBasicAddress" placeholder="Address"
                                                       value="<?php echo @$ArrCompanyBasicInfo['address']; ?>">
                                                <div class="herr" id="ErrBasicAddress"></div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="" class="col-sm-4 control-label">City</label>
                                            <div class="col-sm-8">
                                                <input type="text" name="frmBasicCity" class="form-control"
                                                       id="frmBasicCity" placeholder="City"
                                                       value="<?php echo @$ArrCompanyBasicInfo['city']; ?>">
                                                <div class="herr" id="ErrBasicCity"></div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="" class="col-sm-4 control-label">State</label>
                                            <div class="col-sm-8">
                                                <?php
                                                //echo '<pre>'; print_r($ArrStates); die('');
                                                ?>
                                                <select id="frmBasicState" name="frmBasicState" class="form-control">
                                                    <option value="0">State</option>
                                                    <?php
                                                    foreach ($ArrStates as $arrState) { ?>
                                                        <option
                                                            value="<?php echo $arrState['id'] ?>" <?php echo @$ArrCompanyBasicInfo['state'] == $arrState['id'] ? 'selected' : '' ?>>
                                                            <?php echo $arrState['statename'] ?>
                                                        </option>
                                                    <?php } ?>
                                                </select>
                                                <div class="herr" id="ErrBasicState"></div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="" class="col-sm-4 control-label">Country</label>
                                            <div class="col-sm-8">
                                                <select name="frmBasicCountry" id="frmBasicCountry"
                                                        class="form-control">
                                                    <option value="">Choose the Country</option>
                                                    <?php
                                                    $ArrCountryList = unserialize(ARRCOUNTRYLIST);
                                                    foreach ($ArrCountryList as $VarCountryId => $VarCountryName) { ?>
                                                        <option
                                                            value="<?php echo $VarCountryId ?>" <?php if ($VarCountryId == @$ArrCompanyBasicInfo['country']) {
                                                            echo "selected";
                                                        } ?>><?php echo $VarCountryName ?></option>
                                                    <?php } ?>
                                                </select>
                                                <div class="herr" id="ErrBasicCountry"></div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="" class="col-sm-4 control-label">ZIP/Pin Code</label>
                                            <div class="col-sm-8">
                                                <input type="text" name="frmBasicZipcode" class="form-control"
                                                       id="frmBasicZipcode" placeholder="Ex: 603020"
                                                       value="<?php echo @$ArrCompanyBasicInfo['zipcode']; ?>">
                                                <div class="herr" id="ErrBasicZipcode"></div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="" class="col-sm-4 control-label">Factory Ownership</label>
                                            <div class="col-sm-8">
                                                <select name="frmBasicOwnershipFactory" id="frmBasicOwnershipFactory"
                                                        class="form-control">
                                                    <option value="">Choose the Ownership Factory</option>
                                                    <?php
                                                    $ArrOwnerFactoryList = unserialize(ARRCOMPANYFACTORYOWNERSHIP);
                                                    foreach ($ArrOwnerFactoryList as $VarOwnershipId => $VarOwnershipName) { ?>
                                                        <option
                                                            value="<?php echo $VarOwnershipId ?>" <?php if ($VarOwnershipId == @$ArrCompanyBasicInfo['factoryownership']) {
                                                            echo "selected";
                                                        } ?>><?php echo $VarOwnershipName ?></option>
                                                    <?php } ?>
                                                </select>
                                                <div class="herr" id="ErrBasicOwnershipFactory"></div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="" class="col-sm-4 control-label">Year of Established</label>
                                            <div class="col-sm-8">
                                                <input type="text" name="frmBasicEstYear" class="form-control"
                                                       id="frmBasicEstYear" placeholder="Ex: 2012"
                                                       value="<?php echo @$ArrCompanyBasicInfo['yearofest']; ?>">
                                                <div class="herr" id="ErrBasicEstYear"></div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="" class="col-sm-4 control-label">Company Profile</label>
                                            <div class="col-sm-8">
                                                <textarea name="frmBasicProfile" id="frmBasicProfile"
                                                          class="form-control"><?php echo @$ArrCompanyBasicInfo['companyprofile']; ?></textarea>
                                                <div class="herr" id="ErrBasicProfile"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-md-6 col-sm-6 col-lg-6">
                                        <div class="form-group">
                                            <label for="" class="col-sm-4 control-label">Factory Size in Sq.ft</label>
                                            <div class="col-sm-8">
                                                <input type="text" name="frmBasicFactorySize" class="form-control"
                                                       id="frmBasicFactorySize" placeholder="Ex: 12"
                                                       value="<?php echo @$ArrCompanyBasicInfo['factorysize']; ?>">
                                                <div class="herr" id="ErrBasicFactorySize"></div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="" class="col-sm-4 control-label">No.Of Machine</label>
                                            <div class="col-sm-8">
                                                <input type="text" name="frmBasicNoOfMachine" class="form-control"
                                                       id="frmBasicNoOfMachine" placeholder="No.of Machine"
                                                       value="<?php echo @$ArrCompanyBasicInfo['noofmachine']; ?>">
                                                <div class="herr" id="ErrBasicNoOfMachine"></div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="" class="col-sm-4 control-label">Production Capacity Per Day
                                                (Single Shift)
                                            </label>
                                            <div class="col-sm-8">
                                                <input type="text" name="frmBasicProductCapacity" class="form-control"
                                                       id="frmBasicProductCapacity" placeholder="Production Capcity Per Day (Single Shift)"
                                                       value="<?php echo @$ArrCompanyBasicInfo['productioncapacity']; ?>">
                                                <div class="herr" id="ErrBasicProductCapacity"></div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="" class="col-sm-4 control-label">Annual Turnover (Last 3 Years)
                                            </label>
                                            <div class="col-sm-8">
                                                <input type="text" name="frmBasicAnnualTurnOver" class="form-control"
                                                       id="frmBasicAnnualTurnOver" placeholder="Annual Turnover"
                                                       value="<?php echo @$ArrCompanyBasicInfo['annualturnover']; ?>">
                                                <div class="herr" id="ErrBasicAnnualTurnOver"></div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="" class="col-sm-4 control-label">No. Of Employees (Permanent)
                                            </label>
                                            <div class="col-sm-8">
                                                <input type="text" name="frmBasicNoOfEmployee" class="form-control"
                                                       id="frmBasicNoOfEmployee" placeholder="No. Of Employees"
                                                       value="<?php echo @$ArrCompanyBasicInfo['noofemployee']; ?>">
                                                <div class="herr" id="ErrBasicNoOfEmployee"></div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="" class="col-sm-4 control-label">No. Of Contract Workers
                                            </label>
                                            <div class="col-sm-8">
                                                <input type="text" name="frmBasicContractWorker" class="form-control"
                                                       id="frmBasicContractWorker" placeholder=""
                                                       value="<?php echo @$ArrCompanyBasicInfo['noofcontract']; ?>">
                                                <div class="herr" id="ErrBasicContractWorker"></div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="" class="col-sm-4 control-label">Major Customer</label>
                                            <div class="col-sm-8">
                                                <textarea name="frmBasicMajorCustomer" class="form-control"
                                                          id="frmBasicMajorCustomer"
                                                          placeholder=""><?php echo @$ArrCompanyBasicInfo['majorcustomer']; ?></textarea>
                                                <div class="herr" id="ErrBasicMajorCustomer"></div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="" class="col-sm-4 control-label">Major Export Customer</label>
                                            <div class="col-sm-8">
                                                <textarea name="frmBasicMajorExportCustomer" class="form-control"
                                                          id="frmBasicMajorExportCustomer"
                                                          placeholder=""><?php echo @$ArrCompanyBasicInfo['exportcustomer']; ?></textarea>
                                                <div class="herr" id="ErrBasicMajorExportCustomer"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php
                                } else {
                                    ?>
                                    <div class="col-xs-12 col-md-6 col-sm-6 col-lg-6">
                                        <div class="form-group">
                                            <label for="" class="col-sm-4 text-right">Company Name</label>
                                            <div class="col-sm-8">
                                                <?php echo @$ArrCompanyBasicInfo['companyname'] ?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="" class="col-sm-4 text-right">Contact Name</label>
                                            <div class="col-sm-8">
                                                <?php echo @$ArrCompanyBasicInfo['contactname'] ?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="" class="col-sm-4 text-right">Company Code</label>
                                            <div class="col-sm-8">
                                                <?php echo @$ArrCompanyBasicInfo['companycode'] ?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="" class="col-sm-4 text-right">E-mail Id</label>
                                            <div class="col-sm-8">
                                                <?php echo @$ArrCompanyBasicInfo['username'] ?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="" class="col-sm-4 text-right">Mobile No.</label>
                                            <div class="col-sm-8">
                                                <?php echo @$ArrCompanyBasicInfo['mobile']; ?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="" class="col-sm-4 text-right">Business Type</label>
                                            <div class="col-sm-8">
                                                <?php echo @$ArrBusinessTypeList[$ArrCompanyBasicInfo['businesstype']] ?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="" class="col-sm-4 text-right">Address</label>
                                            <div class="col-sm-8">
                                                <?php echo @$ArrCompanyBasicInfo['address'] ?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="" class="col-sm-4 text-right">City</label>
                                            <div class="col-sm-8">
                                                <?php echo @$ArrCompanyBasicInfo['city'] ?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="" class="col-sm-4 text-right">State</label>
                                            <div class="col-sm-8">
                                                <?php echo @$state->statename ?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="" class="col-sm-4 text-right">Country</label>
                                            <div class="col-sm-8">
                                                <?php echo @$ArrCountryList[$ArrCompanyBasicInfo['country']] ?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="" class="col-sm-4 text-right">ZIP/Pin Code</label>
                                            <div class="col-sm-8">
                                                <?php echo @$ArrCompanyBasicInfo['zipcode'] ?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="" class="col-sm-4 text-right">Factory Ownership</label>
                                            <div class="col-sm-8">
                                                <?php echo @$ArrOwnerFactoryList[$ArrCompanyBasicInfo['factoryownership']] ?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="" class="col-sm-4 text-right">Year of Established</label>
                                            <div class="col-sm-8">
                                                <?php echo @$ArrCompanyBasicInfo['yearofest'] ?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="" class="col-sm-4 text-right">Major Export Customer</label>
                                            <div class="col-sm-8">
                                                <?php echo @$ArrCompanyBasicInfo['exportcustomer'] ?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="" class="col-sm-4 text-right">Company Profile</label>
                                            <div class="col-sm-8">
                                                <?php echo @$ArrCompanyBasicInfo['companyprofile'] ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-md-6 col-sm-6 col-lg-6">
                                        <div class="form-group">
                                            <label for="" class="col-sm-4 text-right">Factory Size in Sq.ft</label>
                                            <div class="col-sm-8">
                                                <?php echo @$ArrCompanyBasicInfo['factorysize'] ?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="" class="col-sm-4 text-right">No.Of Machine</label>
                                            <div class="col-sm-8">
                                                <?php echo @$ArrCompanyBasicInfo['noofmachine'] ?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="" class="col-sm-4 text-right">Production Capacity Per Day
                                                (Single
                                                Shift)
                                            </label>
                                            <div class="col-sm-8">
                                                <?php echo @$ArrCompanyBasicInfo['productioncapacity'] ?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="" class="col-sm-4 text-right">Annual Turnover (Last 3 Years)
                                            </label>
                                            <div class="col-sm-8">
                                                <?php echo @$ArrCompanyBasicInfo['annualturnover'] ?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="" class="col-sm-4 text-right">No. Of Employees (Permanent)
                                            </label>
                                            <div class="col-sm-8">
                                                <?php echo @$ArrCompanyBasicInfo['noofemployee'] ?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="" class="col-sm-4 text-right">No. Of Contract Workers
                                            </label>
                                            <div class="col-sm-8">
                                                <?php echo @$ArrCompanyBasicInfo['noofcontract'] ?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="" class="col-sm-4 text-right">Major Customer</label>
                                            <div class="col-sm-8">
                                                <?php echo @$ArrCompanyBasicInfo['majorcustomer'] ?>
                                            </div>
                                        </div>
                                    </div>
                                    <?php
                                }
                                ?>
                            </form>
                        </div><!-- /.box-body -->
                        <div class="box-footer boxFooter_pd1025">
                            <a href="<?php echo base_url(CNFCADMIN . 'company/managecompany') ?>"
                               class="btn btn-default">Back</a>
                            <button type="submit" class="btn btn-info pull-right <?php if ($Edit != 'edit' && !empty($VarCompanyId)) echo 'hide' ?>"
                                    onclick="return fnSaveCompanyBasicInfo();">Save Changes
                            </button>
                            <div class="herr" id="ErrBasicEmail"></div>
                        </div><!-- /.box-footer -->
                    </div><!-- /.box-body -->
                </div>
            </div>
        </section><!-- /.content -->
    </div><!-- /.content-wrapper -->
    <?php $this->load->view(CNFCADMIN . 'template/templatefooter'); ?>
    <div class="control-sidebar-bg"></div>
</div><!-- ./wrapper -->
<script>
    var GlbCompanyId = "<?php echo $VarCompanyId; ?>";
</script>
<script src="<?php echo base_url(); ?>assets/js/<?php echo CNFCADMIN ?>companyprofile.js"></script>
<?php $this->load->view(CNFCADMIN . 'template/pagefooter'); ?>