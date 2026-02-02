<?php $this->load->view(CNFCOMPANY.'template/pageheader');?>
    <body class="sidebar-mini skin-black wysihtml5-supported sidebar-collapse">
<div class="wrapper">
    <?php $this->load->view(CNFCOMPANY.'template/templateheader');?>

    <aside class="main-sidebar">
        <?php $this->load->view(CNFCOMPANY.'template/templateleftmenu');?>
    </aside>

    <div class="content-wrapper">

        <section class="content-header">
            <h1>Add/Edit Lot Inspection</h1>
            <ol class="breadcrumb">
                <li><a href="<?php echo base_url().CNFCOMPANY?>dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
                <li><a href="<?php echo base_url().CNFCOMPANY?>mlotinspection/manage/">Lot Inspection</a></li>
                <li class="active">Add/Edit Lot Inspection</li>
            </ol>
        </section>

        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div id="DivContBasicInfo">
                        <div class="box box-info">
                            <div class="box-header with-border">
                                <h3 class="box-title">Basic Information</h3>
                                <div class="box-tools pull-right">
                                    <?php if($VarNew==0) {?>
                                        <a class="btn btn-default btn-s addrights" href="javascript:void(0);" onclick="fnShowHideEndUserSub(1,'divEditBasicInfo');"><i class="fa fa-edit"></i> Edit</a>
                                    <?php }?>
                                </div>
                            </div>
                            <div class="box-body ">
                                <form class="form-horizontal" name="frmBasicInfo" id="frmBasicInfo" method="post">
                                    <div id="divEditBasicInfo" class="<?php if($VarNew==1) {?>show<?php } else {?>hide<?php }?>">
                                        <div class="alert alert-success alert-dismissable hide" id="divSuccessBasicInfoMsg"></div>
                                        <div class="col-xs-12 col-md-6 col-sm-6 col-lg-6">
                                            <div class="form-group">
                                                <label for="inputEmail3" class="col-sm-2 control-label">Level</label>
                                                <div class="col-sm-10">
                                                    <input type="text" name="frmBasicLevel" class="form-control" id="frmBasicLevel" placeholder="Level" value="<?php echo @$ArrBasicInfo['level'];?>">
                                                    <div class="herr" id="ErrfrmBasicLevel"></div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="inputEmail3" class="col-sm-2 control-label">Code Letter</label>
                                                <div class="col-sm-10">
                                                    <input type="text" name="frmBasicCodeLetter" class="form-control" id="frmBasicCodeLetter" placeholder="Code Letter" value="<?php echo @$ArrBasicInfo['codeletter'];?>">
                                                    <div class="herr" id="ErrfrmBasicCodeLetter"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-md-6 col-sm-6 col-lg-6">
                                            <div class="form-group">
                                                <label for="inputEmail3" class="col-sm-2 control-label">AQL</label>
                                                <div class="col-sm-10">
                                                    <input type="text" name="frmBasicAql" class="form-control" id="frmBasicAql" placeholder="AQL" value="<?php echo @$ArrBasicInfo['aql'];?>">
                                                    <div class="herr" id="ErrfrmBasicAql"></div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="inputEmail3" class="col-sm-2 control-label">Sample Size</label>
                                                <div class="col-sm-10">
                                                    <input type="text" name="frmBasicSampleSize" class="form-control" id="frmBasicSampleSize" placeholder="Sample Size" value="<?php echo @$ArrBasicInfo['samplesize'];?>">
                                                    <div class="herr" id="ErrfrmBasicSampleSize"></div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="inputEmail3" class="col-sm-2 control-label">Status</label>
                                                <div class="col-sm-10">
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

                                        <div class="box-footer nopadding">
                                            <button type="button" class="btn btn-default" onclick="fnShowHideEndUserSub(1,'divShowBasicInfo');">Cancel</button>
                                            <button type="submit" class="btn btn-info pull-right  addrights" onclick="return fnSaveLotInfo();">Save Changes</button>
                                        </div>
                                    </div>
                                    <div id="divShowBasicInfo" class="<?php if($VarNew==1) {?>hide<?php }?>">
                                        <div class="col-xs-12 col-md-6 col-sm-6 col-lg-6">
                                            <div class="form-group">
                                                <label for="inputEmail3" class="col-sm-2 text-right">Level</label>
                                                <div class="col-sm-10" id="divDispBasicName">
                                                    <?php echo @$ArrBasicInfo['level'];?>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="inputEmail3" class="col-sm-2 text-right">Code Letter</label>
                                                <div class="col-sm-10" id="divDispBasicName">
                                                    <?php echo @$ArrBasicInfo['codeletter'];?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-md-6 col-sm-6 col-lg-6">
                                            <div class="form-group">
                                                <label for="inputEmail3" class="col-sm-2 text-right">AQL</label>
                                                <div class="col-sm-10" id="divDispBasicName">
                                                    <?php echo @$ArrBasicInfo['aql'];?>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="inputEmail3" class="col-sm-2 text-right">Sample Size</label>
                                                <div class="col-sm-10" id="divDispBasicName">
                                                    <?php echo @$ArrBasicInfo['samplesize'];?>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="inputEmail3" class="col-sm-2 text-right">Status</label>
                                                <div class="col-sm-10"  id="divDispStatus">
                                                    <?php
                                                    echo @$ArrStatus[$ArrBasicInfo['status']];
                                                    ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
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
<script language="javascript">
    var GlbNewUser		        = "<?php echo @$VarNew;?>";
    var GlbId          	        = "<?php echo @$VarId;?>";
</script>
<script src="<?php echo base_url();?>assets/js/<?php echo CNFCOMPANY?>mlotinspection.js"></script>
<script src="<?php echo base_url();?>assets/js/commonfunctions.js?r=<?php echo CNFJSCSSRANDNO?>"></script>
<?php $this->load->view(CNFCOMPANY.'template/pagefooter');?>