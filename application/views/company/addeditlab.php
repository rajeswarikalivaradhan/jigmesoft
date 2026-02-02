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
						<h2 class="page-header text-royal-black mx-4" style="border-bottom:0px"> <?php echo (empty($Edit) && empty($VarId)) ? 'Add New Lab Parameter':'View / Edit Lab Parameter' ?>
						  <div class="pull-right">
											<div class="ml-auto pr-3">
											<a href="<?php echo base_url(CNFCOMPANY.'mlab/managelab') ?>" id="backbtn" class="btn custbtn btn-sm mx-3 btn-royal-blue btn-text-slide-x">Back</a>
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
                                    Lab Parameter <span class="mandatory">*</span>
                                    </label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="frmBasicLabName" value="<?php echo  @$ArrBasicInfo['labname'];?>" placeholder="Free Text">
                                    <div class="herr" id="ErrBasicLabName"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
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
									<div class="herr" id="ErrBasicStatus"></div>
								</div>
							</div>
						</div>
                    </div>
                </div>
				<div class="row">
					<div class="col-xs-12 py-4" style="padding-right:30px">
					<button class="btn btn-info pull-right mx-2" id="enqsvbtn" onclick="return fnSaveLabInfo();">Save</button>
					</div>
				</div>
			</section>     
		</section>
    </div>
    <div class="content-wrapper hide">
        <div class="col-md-12">
            <section class="content-header">
                <h1 class="firstHeading">
                    Lab <?php if (empty($Edit) && !empty($VarId)) echo ''; else echo '- Add / Edit' ?></h1>
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
                                    $VarController = $this->uri->segment(2);
                                    if ($Edit == 'edit' || empty($VarId)) {
                                        ?>
                                        <div class="alert alert-success alert-dismissable hide"
                                             id="divSuccessBasicInfoMsg"></div>
                                        <div class="col-xs-12 col-md-6 col-sm-6 col-lg-6">
                                            <div class="form-group">
                                                <label for="" class="col-sm-4 control-label">Lab</label>
                                                <div class="col-sm-8">
                                                    <input type="text" name="frmBasicLabName" class="form-control"
                                                           id="frmBasicLabName" placeholder="Lab"
                                                           value="<?php echo @$ArrBasicInfo['labname']; ?>">
                                                    <div class="herr" id="ErrBasicLabName"></div>
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
                                                                value="<?php echo $VarKey ?>" <?php if (@$ArrBasicInfo['status'] == $VarKey) {
                                                                echo "selected";
                                                            } ?>><?php echo $VarStatus ?></option>
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
                                                <label for="" class="col-sm-2 text-right">Lab</label>
                                                <div class="col-sm-10" id="divDispBasicName">
                                                    <?php echo @$ArrBasicInfo['labname']; ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-md-6 col-sm-6 col-lg-6">
                                            <div class="form-group">
                                                <label for="" class="col-sm-2 text-right">Status</label>
                                                <div class="col-sm-10" id="divDispStatus">
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
                                <a href="<?php echo base_url(CNFCOMPANY . $VarController . '/managelab') ?>"
                                   class="btn btn-default">Back</a>
                                <button type="submit" class="btn btn-info pull-right  addrights"
                                        onclick="return fnSaveLabInfo();">Save Changes
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
    var lasturi='<?php echo $Edit?>';
    if(lasturi=='edit'){
        $('#enqsvbtn').hide();
        $("#custom_form input").prop("disabled", true);
        $("#custom_form select").prop("disabled", true);
    } else { 
       $('#enqsvbtn').show();
    }
</script>
<script
    src="<?php echo base_url(); ?>assets/js/<?php echo CNFCOMPANY ?>mlab.js?r=<?php echo CNFJSCSSRANDNO ?>"></script>
<?php $this->load->view(CNFCOMPANY . 'template/pagefooter'); ?>