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
                                    <a href="<?php echo base_url(CNFCOMPANY . 'mbrand/manage') ?>"
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
</script>
<script src="<?php echo base_url() ?>assets/js/<?php echo CNFCOMPANY ?>brand.js"></script>
<?php $this->load->view(CNFCOMPANY . 'template/pagefooter'); ?>