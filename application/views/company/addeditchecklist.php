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
                <h2 class="page-header text-royal-black mx-4" style="border-bottom:0px"> <?php echo (empty($Edit) && empty($VarId)) ? 'Add New Checklist Details':'View / Edit Checklist Details' ?>
                  <div class="pull-right">
                                    <div class="ml-auto pr-3">
                                    <a href="<?php echo base_url(CNFCOMPANY . 'mastersetup/managechecklist') ?>" id="backbtn" class="btn custbtn btn-sm mx-3 btn-royal-blue btn-text-slide-x">Back</a>
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
                    <div class="col-md-12">
                        <div class="col-md-6">
                            <div class="form-group">
                                    <label for="id-form-field-focus-1" class="col-sm-3 control-label">
                                      Description Name  <span class="mandatory">*</span>
                                    </label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="frmBasicDesc" value="<?php echo @$ArrRes['description']; ?>" placeholder="Free Text">
                                    <div class="herr" id="ErrfrmBasicDesc"></div>
                                </div>
                            </div>
                        </div>
						<div class="col-md-6">
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
													value="<?php echo $VarKey ?>" <?php if (@$ArrRes['status'] == $VarKey) {
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
        <button class="btn btn-info pull-right mx-2" id="enqsvbtn" onclick="return fnSave();">Save</button>
    </div></div>
        </section>     
     </section>
    </div>
    <?php $this->load->view(CNFCOMPANY . 'template/templatefooter'); ?>
    <div class="control-sidebar-bg"></div>
</div>
<script type="text/javascript">
    var GlbId = "<?php echo $VarId; ?>";
    var lasturi='<?php echo $Edit?>';
    
    if(lasturi=='edit'){
        $('#enqsvbtn').hide();
        $("#custom_form input").prop("disabled", true);
        $("#custom_form select").prop("disabled", true);
        $("#custom_form textarea").prop("disabled", true);
    } else { 
       $('#enqsvbtn').show();
    }
    
</script>
<script src="<?php echo base_url() ?>assets/js/<?php echo CNFCOMPANY ?>managechecklist.js"></script>
<?php $this->load->view(CNFCOMPANY . 'template/pagefooter'); ?>