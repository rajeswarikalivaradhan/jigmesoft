<?php $this->load->view(CNFCOMPANY . 'template/pageheader'); ?>
    <!--<body class="sidebar-mini skin-black wysihtml5-supported sidebar-collapse">-->
    <body class="layout-top-nav">
<div class="wrapper">
    <?php $this->load->view(CNFCOMPANY . 'template/badmintemplateheader');
    // $this->load->view(CNFCOMPANY . 'template/templateheader'); ?>
    <aside class="main-sidebar hide">
        <?php // $this->load->view(CNFCOMPANY . 'template/templateleftmenu'); ?>
    </aside>
     <div class="content-wrapper">
        <section class="content">
        <section class="invoice form-horizontal">
                <div class="row"> 
            <div class="col-xs-12">
                <h2 class="page-header text-royal-black mx-4" style="border-bottom:0px"> <?php echo (empty($Edit) && empty($VarId)) ? 'Add New Package Details':'View / Edit Package Details' ?>
                  <div class="pull-right">
                                    <div class="ml-auto pr-3">
                                    <a href="<?php echo base_url(CNFBADMIN . 'mpackage/manage') ?>" id="backbtn" class="btn custbtn btn-sm mx-3 btn-royal-blue btn-text-slide-x">Back</a>
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
                                     Package Details <span class="mandatory">*</span>
                                    </label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control" id="frmDescp"  value="<?php if (!empty($BasicInfo->description)) echo $BasicInfo->description; ?>" placeholder="Free Text">
                                    <div class="herr" id="ErrfrmDescp"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                    <label for="id-form-field-focus-1" class="col-sm-4 control-label">
                                     No. of Users (Package) <span class="mandatory">*</span>
                                    </label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control" id="no_of_users"  value="<?php if (!empty($BasicInfo->no_of_users)) echo $BasicInfo->no_of_users; ?>" placeholder="Free Text">
                                    <div class="herr" id="Errfrm_noofusers"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                    <label for="id-form-field-focus-1" class="col-sm-4 control-label">
                                     Data Storage Limit(Package) <span class="mandatory">*</span>
                                    </label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control" id="data_limit"  value="<?php if (!empty($BasicInfo->data_limit)) echo $BasicInfo->data_limit; ?>" placeholder="Free Text">
                                    <div class="herr" id="Errfrm_data_limit"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                            <div class="col-md-12">
                                <div class="form-group">
                                        <label for="id-form-field-focus-1" class="col-sm-4 control-label">
                                        File Storage Limit (Package) <span class="mandatory">*</span>
                                        </label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control" id="file_limit"  value="<?php if (!empty($BasicInfo->file_limit)) echo $BasicInfo->file_limit; ?>" placeholder="Free Text">
                                        <div class="herr" id="Errfrm_file_limit"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="id-form-field-focus-1" class="col-sm-4 control-label">
                                        Status <span class="mandatory">*</span>
                                    </label>
                                    <div class="col-sm-7">
                                        <select name="frmBasicStatus" id="frmBasicStatus" class="form-control">
                                            <option value="">Select</option>
                                            <?php  $ArrStatus = unserialize(ARRSTATUS);
                                            foreach ($ArrStatus as $VarKey => $VarStatus) { ?>
                                                <option value="<?php echo $VarKey ?>" <?php if (@$BasicInfo->status == $VarKey) { echo "selected";} ?>>
                                                <?php echo $VarStatus ?></option>
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
    } else { 
       $('#enqsvbtn').show();
    }
</script>
<script src="<?php echo base_url() ?>assets/js/<?php echo CNFBADMIN ?>packagedet.js"></script>
<?php $this->load->view(CNFCOMPANY . 'template/pagefooter'); ?>