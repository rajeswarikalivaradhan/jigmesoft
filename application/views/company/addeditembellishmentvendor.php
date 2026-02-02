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
        <div class="col-md-12">
            <section class="content-header">
                <h1 class="firstHeading">Embellishment
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
                                    <a href="<?php echo base_url(CNFCOMPANY . 'membellishmentvendor/manageembellishmentvendor') ?>"
                                       class="btn btn-default">Back</a>
                                    <button type="button"
                                            class="btn btn-info pull-right <?php if ($Edit != 'edit' && !empty($VarId)) echo 'hide' ?>"
                                            onclick="return fnSaveembellishmentvendorInfo();">Save Changes
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
</script>
<script src="<?php echo base_url(); ?>assets/js/<?php echo CNFCOMPANY ?>membellishmentvendor.js"></script>
<?php $this->load->view(CNFCOMPANY . 'template/pagefooter'); ?>