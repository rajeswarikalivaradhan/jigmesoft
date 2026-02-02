<?php $this->load->view(CNFCOMPANY.'template/pageheader');?>
    <body class="sidebar-mini skin-black wysihtml5-supported sidebar-collapse">
<div class="wrapper">
    <?php $this->load->view(CNFCOMPANY.'template/templateheader');?>
    <aside class="main-sidebar">
        <?php $this->load->view(CNFCOMPANY.'template/templateleftmenu');?>
    </aside>
    <div class="content-wrapper">
        <div class="col-md-12">
            <section class="content-header">
                <h1 class="firstHeading">
                    Logistics <?php if (empty($Edit) && !empty($VarId)) echo ''; else echo '- Add / Edit' ?></h1>
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
                                        <div class="alert alert-success alert-dismissable hide" id="divSuccessBasicInfoMsg"></div>
                                        <div class="col-xs-12 col-md-6 col-sm-6 col-lg-6">
                                            <div class="form-group">
                                                <label for="inputEmail3" class="col-sm-4 control-label">Consignor /
                                                    Shipper / Exporter</label>
                                                <div class="col-sm-8">
                                                    <input type="text" name="" class="form-control" id="frmBasicCse" placeholder="Consignor / Shipper / Exporter" value="<?php echo @$ArrBasicInfo['consignorexporter'];?>">
                                                    <div class="herr" id="ErrfrmBasicCse"></div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="inputEmail3" class="col-sm-4 control-label">Forwarding
                                                    Agent</label>
                                                <div class="col-sm-8">
                                                    <input type="text" name="" class="form-control" id="frmBasicFAgent" placeholder="Forwarding Agent" value="<?php echo @$ArrBasicInfo['forwardingagent'];?>">
                                                    <div class="herr" id="ErrfrmBasicFAgent"></div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="inputEmail3" class="col-sm-4 control-label">Clearing
                                                    Agent</label>
                                                <div class="col-sm-8">
                                                    <input type="text" name="" class="form-control" id="frmBasicCAgent" placeholder="Clearing Agent" value="<?php echo @$ArrBasicInfo['clearingagent'];?>">
                                                    <div class="herr" id="ErrfrmBasicCAgent"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-md-6 col-sm-6 col-lg-6">
                                            <div class="form-group">
                                                <label for="inputEmail3" class="col-sm-4 control-label">Importer</label>
                                                <div class="col-sm-8">
                                                    <input type="text" name="" class="form-control" id="frmBasicImporter" placeholder="Importer" value="<?php echo @$ArrBasicInfo['importer'];?>">
                                                    <div class="herr" id="ErrfrmBasicCAgent"></div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="inputEmail3"
                                                       class="col-sm-4 control-label">Consignee</label>
                                                <div class="col-sm-8">
                                                    <input type="text" name="" class="form-control" id="frmBasicConsignee" placeholder="Consignee" value="<?php echo @$ArrBasicInfo['consignee'];?>">
                                                    <div class="herr" id="ErrfrmBasicConsignee"></div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="inputEmail3" class="col-sm-4 control-label">Status</label>
                                                <div class="col-sm-8">
                                                    <select name="frmBasicStatus" id="frmBasicStatus" class="form-control">
                                                        <option value="">Select</option>
                                                        <?php
                                                        $ArrStatus  = unserialize(ARRSTATUS);
                                                        unset($ArrStatus[3]);
                                                        foreach($ArrStatus as $VarKey=>$VarStatus) {?>
                                                            <option value="<?php echo $VarKey?>" <?php if(@$ArrBasicInfo['status']==$VarKey) {echo "selected";}?>><?php echo $VarStatus?></option>
                                                        <?php }?>
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
                                                <label for="inputEmail3" class="col-sm-4 text-right">Consignor / Shipper
                                                    / Exporter</label>
                                                <div class="col-sm-8" id="divDispBasicName">
                                                    <?php echo @$ArrBasicInfo['consignorexporter'];?>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="inputEmail3" class="col-sm-4 text-right">Forwarding
                                                    Agent</label>
                                                <div class="col-sm-8" id="divDispBasicName">
                                                    <?php echo @$ArrBasicInfo['forwardingagent'];?>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="inputEmail3" class="col-sm-4 text-right">Clearing
                                                    Agent</label>
                                                <div class="col-sm-8" id="divDispBasicName">
                                                    <?php echo @$ArrBasicInfo['clearingagent'];?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-md-6 col-sm-6 col-lg-6">
                                            <div class="form-group">
                                                <label for="inputEmail3" class="col-sm-4 text-right">Importer</label>
                                                <div class="col-sm-8" id="divDispBasicName">
                                                    <?php echo @$ArrBasicInfo['importer'];?>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="inputEmail3" class="col-sm-4 text-right">Consignee</label>
                                                <div class="col-sm-8" id="divDispBasicName">
                                                    <?php echo @$ArrBasicInfo['consignee'];?>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="inputEmail3" class="col-sm-4 text-right">Status</label>
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
                                <a href="<?php echo base_url(CNFCOMPANY . 'mlogistics/manage') ?>"
                                   class="btn btn-default">Back</a>
                                <button type="submit"
                                        class="btn btn-info pull-right <?php if ($Edit != 'edit' && !empty($VarId)) echo 'hide' ?>"
                                        onclick="return fnSaveLogisticsInfo();">Save Changes
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <?php $this->load->view(CNFCOMPANY.'template/templatefooter');?>
    <div class="control-sidebar-bg"></div>
</div>
<script>
    var GlbId = "<?php echo $VarId ?>";
</script>
<script src="<?php echo base_url();?>assets/js/<?php echo CNFCOMPANY?>mlogistics.js"></script>
<?php $this->load->view(CNFCOMPANY.'template/pagefooter');?>