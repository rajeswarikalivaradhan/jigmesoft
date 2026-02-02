<?php $this->load->view(CNFCOMPANY . 'template/pageheader'); ?>
<!--<body class="sidebar-mini skin-black wysihtml5-supported sidebar-collapse">-->
<body class="layout-top-nav">
<div class="wrapper">
    <?php $this->load->view(CNFCOMPANY . 'template/admintemplateheader');
    //$this->load->view(CNFCOMPANY . 'template/templateheader'); ?>
    <aside class="main-sidebar hide">
        <?php // $this->load->view(CNFCOMPANY . 'template/templateleftmenu'); ?>
    </aside>
    <div class="content-wrapper">
        <div class="col-md-12">
            <section class="content-header">
                <h1 class="firstHeading">
                    Type / Medium <?php if (empty($Edit) && !empty($VarId)) echo ''; else echo '- Add / Edit' ?></h1>
            </section>
        </div>
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div id="DivContBasicInfo">
                        <div class="box box-info">
                            <div class="box-header with-border">
                                <h3 class="box-title">Basic Information</h3>
                                <div class="box-tools pull-right"></div>
                            </div>
                            <form class="form-horizontal" name="frmBasicInfo" id="frmBasicInfo" method="post">
                                <div id="divSuccessBasicInfoMsg"
                                     class="alert alert-success alert-dismissable hide"></div>
                                <div class="box-body pdt20_pdb0">
                                    <?php
                                    $ArrStatus = unserialize(ARRSTATUS);
                                    if ($Edit == 'edit' || empty($VarId)) {
                                        ?>
                                        <div class="col-xs-12 col-md-6 col-sm-6 col-lg-6">
                                            <div class="form-group">
                                                <label for="frmBasicYarn"
                                                       class="col-sm-4 control-label">Type / Medium</label>
                                                <div class="col-sm-8">
                                                    <input type="text" name="frmBasicYarn" class="form-control"
                                                           id="frmBasicYarn" placeholder="Type / Medium"
                                                           value="<?php if (!empty($BasicInfo->type_medium)) echo $BasicInfo->type_medium; ?>">
                                                    <div class="herr" id="ErrfrmBasicYarn"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-md-6 col-sm-6 col-lg-6">
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
                                                                value="<?php echo $VarKey ?>" <?php if (!empty($BasicInfo->status)) if ($BasicInfo->status == $VarKey) echo "selected"; ?>>
                                                                <?php echo $VarStatus ?>
                                                            </option>
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
                                                <label for="" class="col-sm-4 control-label">Type / Medium</label>
                                                <div class="col-sm-8">
                                                    <?php if (!empty($BasicInfo->type_medium)) echo $BasicInfo->type_medium; ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-md-6 col-sm-6 col-lg-6">
                                            <div class="form-group">
                                                <label for="" class="col-sm-4 control-label">Status</label>
                                                <div class="col-sm-8">
                                                    <?php
                                                    if (!empty($BasicInfo->status)) echo $ArrStatus[$BasicInfo->status];
                                                    ?>
                                                </div>
                                            </div>
                                        </div>
                                        <?php
                                    }
                                    ?>
                                </div>
                                <div class="box-footer boxFooter_pd1025">
                                    <a href="<?php echo base_url(CNFCOMPANY . 'mtypemedium/manage'); ?>"
                                       class="btn btn-default pull-left">Back</a>
                                    <button type="button"
                                            class="btn btn-info pull-right <?php if ($Edit != 'edit' && !empty($VarId)) echo 'hide' ?>"
                                            onclick="return fnSave();">
                                        Save Changes
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
<script src="<?php echo base_url() ?>assets/js/<?php echo CNFCOMPANY ?>mtypemedium.js"></script>
<?php $this->load->view(CNFCOMPANY . 'template/pagefooter'); ?>