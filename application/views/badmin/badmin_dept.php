<?php $this->load->view(CNFCOMPANY . 'template/pageheader'); ?>
<body class="layout-top-nav">
<div class="wrapper">
    <?php $this->load->view(CNFCOMPANY . 'template/badmintemplateheader');?>
    <div class="content-wrapper">
        <section class="content">
            <section class="invoice form-horizontal">
                <div class="row"> 
                    <div class="col-xs-12">
                <h2 class="page-header text-royal-black mx-4" style="border-bottom:0px"> View / Edit User Roles
                   <div class="pull-right">
                                    <div class="ml-auto pr-3">
                                    <a href="<?php echo base_url(CNFBADMIN . 'muser/manage') ?>" id="backbtn" class="btn custbtn btn-sm mx-3 btn-royal-blue btn-text-slide-x">Back</a>
                                    <?php  
                                        if(count($ArrRoleInfo)==1 && in_array("", $ArrRoleInfo)) { echo ''; } else {
                                    ?>
                                    <a id="editEnable" class="btn custbtn btn-royal-blue btn-sm px-3" >Edit</a>
                                    <?php } ?>
                                    </div>
                    </div>
                <div class="col-sm-12 " style="padding: 7px 25px;border-bottom: 1px solid #022B61;"></div>
                </h2>
                <h4 class="mr-2 py-2 text-royal-blue">
                </h4>
            </div><!-- /.col -->
                </div>
                <div class="row no-rad-form add-form-mar"> 
                  <h4 class="mr-2 py-2 mt-0 text-heading" ><?php echo $ArrUserType[$ArrBasicInfo['usertype']].' - '.$ArrBasicInfo['dept_usercount'].' | '.$ArrBasicInfo['contactname'].' | '.$ArrBasicInfo['designation'];?></h4>
                </div>
                <div class="row no-rad-form add-form-mar" id="custom_form">
                    <input type="hidden" id="userid" value="<?php echo $ArrBasicInfo['id'];?>">
                    <input type="hidden" id="editvariable" value="<?php  echo (count($ArrRoleInfo)==1 && (in_array("", $ArrRoleInfo))) ? '1' : '2';?>">
                    <div class="row">
                        <div class="col-md-2">
                            <div class="form-group">
                                    <label for="id-form-field-focus-1" class="col-sm-10 control-label labelclr">
                                    Manage Master Data
                                    </label>
                                <div class="col-sm-2">
                                   <input type="checkbox" name="title" value="Manage Master Data" <?php if(count($ArrRoleInfo)>0 && in_array('Manage Master Data',$ArrRoleInfo)) { echo 'checked';}?>>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                    <label for="id-form-field-focus-1" class="col-sm-10 control-label labelclr">
                                    Manage Department & User
                                    </label>
                                <div class="col-sm-2">
                                   <input type="checkbox" name="title" value="Manage Department & User" <?php if(count($ArrRoleInfo)>0 && in_array('Manage Department & User',$ArrRoleInfo)) { echo 'checked';}?>>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                    <label for="id-form-field-focus-1" class="col-sm-10 control-label labelclr">
                                    Request Received List
                                    </label>
                                <div class="col-sm-2">
                                   <input type="checkbox" name="title" value="Request Received List" <?php if(count($ArrRoleInfo)>0 && in_array('Request Received List',$ArrRoleInfo)) { echo 'checked';}?>>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                    <label for="id-form-field-focus-1" class="col-sm-10 control-label labelclr">
                                    Proforma Invoice List
                                    </label>
                                <div class="col-sm-2">
                                   <input type="checkbox" name="title" value="Proforma Invoice List" <?php if(count($ArrRoleInfo)>0 && in_array('Proforma Invoice List',$ArrRoleInfo)) { echo 'checked';}?>>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                    <label for="id-form-field-focus-1" class="col-sm-10 control-label labelclr">
                                    Subscriber List
                                    </label>
                                <div class="col-sm-2">
                                   <input type="checkbox" name="title" value="Subscriber List" <?php if(count($ArrRoleInfo)>0 && in_array('Subscriber List',$ArrRoleInfo)) { echo 'checked';}?>>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 py-4" style="padding-right:30px">
                        <button class="btn btn-info pull-right mx-2" id="svbtn" onclick="fnSave();" disabled="true">Save</button>
                    </div>
                </div>
        </section>     
        </section>
    </div>
    <?php $this->load->view(CNFCOMPANY . 'template/templatefooter'); ?>
    <div class="control-sidebar-bg"></div>
</div>
<?php $this->load->view(CNFCOMPANY . 'template/pagefooter'); ?>
<script src="<?php echo base_url().'assets/js/'.CNFBADMIN; ?>userpermission.js"></script>