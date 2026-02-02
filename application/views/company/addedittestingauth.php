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
                <h1 class="firstHeading">Testing
                    Authority <?php if (empty($Edit) && !empty($VarId)) echo ''; else echo '- Add / Edit' ?></h1>
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
                            <div class="box-body pdt20_pdb0">
                                <form class="form-horizontal" name="frmBasicInfo" id="frmBasicInfo" method="post">
                                    <?php
                                    $ArrStatus = unserialize(ARRSTATUS);
                                    if ($Edit == 'edit' || empty($VarId)) {
                                        ?>
                                        <div class="alert alert-success alert-dismissable hide"
                                             id="divSuccessBasicInfoMsg"></div>
                                        <div class="col-xs-12 col-md-6 col-sm-6 col-lg-6">
                                            <div class="form-group">
                                                <label for=""
                                                       class="col-sm-4 control-label">Contact Name</label>
                                                <div class="col-sm-8">
                                                    <input type="text" class="form-control" id="frmBasicName"
                                                           placeholder="Contact Name"
                                                           value="<?php echo @$ArrBasicInfo['contact_name']; ?>">
                                                    <div class="herr" id="ErrName"></div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="" class="col-sm-4 control-label">E-mail</label>
                                                <div class="col-sm-8">
                                                    <input type="text" class="form-control" id="frmBasicEmail"
                                                           placeholder="E-mail"
                                                           value="<?php echo @$ArrBasicInfo['email']; ?>">
                                                    <div class="herr" id="ErrEmail"></div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for=""
                                                       class="col-sm-4 control-label">Phone No.</label>
                                                <div class="col-sm-8">
                                                    <input type="text" class="form-control" id="frmBasicPhoneNo"
                                                           placeholder="Phone No."
                                                           value="<?php echo @$ArrBasicInfo['phone']; ?>">
                                                    <div class="herr" id="ErrPhone"></div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for=""
                                                       class="col-sm-4 control-label">Mobile No.</label>
                                                <div class="col-sm-8">
                                                    <input type="text" class="form-control" id="frmBasicMobileNo"
                                                           placeholder="Mobile No."
                                                           value="<?php echo @$ArrBasicInfo['mobile']; ?>">
                                                    <div class="herr" id="ErrMobile"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-md-6 col-sm-6 col-lg-6">
                                            <div class="form-group">
                                                <label for="" class="col-sm-4 control-label">Testing Authority</label>
                                                <div class="col-sm-8">
                                                    <input type="text" class="form-control" id="frmBasicTestingA" placeholder="Testing Authority" value="<?php echo @$ArrBasicInfo['test_auth_name']; ?>">
                                                    <div class="herr" id="ErrTestingA"></div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for=""
                                                       class="col-sm-4 control-label">Address</label>
                                                <div class="col-sm-8">
                                                    <textarea id="frmBasicAddress" class="form-control" placeholder="Address" title="Address"><?php echo @$ArrBasicInfo['address'] ?></textarea>
                                                    <div class="herr" id="ErrAddress"></div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label for=""
                                                       class="col-sm-4 control-label">GST No.</label>
                                                <div class="col-sm-8">
                                                    <input type="text" class="form-control" id="frmBasicGstNo"
                                                           placeholder="GST No."
                                                           value="<?php echo @$ArrBasicInfo['gst']; ?>">
                                                    <div class="herr" id="ErrGstNo"></div>
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
                                                                value="<?php echo $VarKey ?>" <?php if (@$ArrBasicInfo['status'] == $VarKey) echo "selected"; ?>>
                                                                <?php echo $VarStatus ?>
                                                            </option>
                                                        <?php } ?>
                                                    </select>
                                                    <div class="herr" id="ErrBasicStatus"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12 herr" id="AnyOtherErr"></div>
                                        <?php
                                    } else {
                                        ?>
                                        <div class="col-xs-12 col-md-6 col-sm-6 col-lg-6">
                                            <div class="form-group">
                                                <label for=""
                                                       class="col-sm-4 text-right">Testing Authority</label>
                                                <div class="col-sm-8" id="divDispBasicName">
                                                    <?php echo @$ArrBasicInfo['test_auth_name']; ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-md-6 col-sm-6 col-lg-6">
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
                                    }
                                    ?>
                                </form>
                            </div>
                            <div class="box-footer boxFooter_pd1025">
                                <a href="<?php echo base_url(CNFCOMPANY . 'mauth/managetauth') ?>"
                                   class="btn btn-default">Back</a>
                                <button type="button"
                                        class="btn btn-info pull-right <?php if ($Edit != 'edit' && !empty($VarId)) echo 'hide' ?>"
                                        onclick="return fnSaveTAuth();">Save Changes
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <?php $this->load->view(CNFCOMPANY . 'template/templatefooter'); ?>
    <div class="control-sidebar-bg"></div>
</div>
<script>
    var GlbId = "<?php echo $VarId ?>";
</script>
<script src="<?php echo base_url(); ?>assets/js/<?php echo CNFCOMPANY ?>mauth.js"></script>
<?php $this->load->view(CNFCOMPANY . 'template/pagefooter'); ?>