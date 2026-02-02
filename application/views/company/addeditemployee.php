<?php $this->load->view(CNFCOMPANY.'template/pageheader');?>
<body class="sidebar-mini skin-black wysihtml5-supported sidebar-collapse">
<div class="wrapper">
    <?php $this->load->view(CNFCOMPANY.'template/templateheader');?>
    <aside class="main-sidebar">
        <?php $this->load->view(CNFCOMPANY.'template/templateleftmenu');?>
    </aside>
    <div class="content-wrapper">
        <section class="content-header">
            <h1>Add/Edit Employee</h1>
            <ol class="breadcrumb">
                <li><a href="<?php echo base_url().CNFADMIN?>dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
                <li><a href="<?php echo base_url().CNFADMIN?>profile/manageemployee/">Manage Employee</a></li>
                <li class="active">Add/Edit Employee</li>
            </ol>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-md-3">
                    <div class="box box-solid">
                        <div class="box-header with-border">
                            <h3 class="box-title">Profile Info.</h3>
                            <div class="box-tools">
                                <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                            </div>
                        </div>
                        <div class="box-body no-padding">
                            <ul class="nav nav-pills nav-stacked">
                                <li><a href="javascript:void(0);" onclick="fnShowProfileCont('DivContBasicInfo');"><i class="fa fa-circle-o text-yellow"></i> Basic Information</a></li>
                                <?php if($VarNewUser==0) {?>
                                    <li><a href="javascript:void(0);" onclick="fnShowProfileCont('DivContPwdInfo');"><i class="fa fa-circle-o text-blue"></i> Change Password</a></li>
                                <?php }?>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-md-9">
                    <div id="DivContBasicInfo">
                        <div class="box box-info">
                            <div class="box-header with-border">
                                <h3 class="box-title">Basic Information</h3>
                                <div class="box-tools pull-right">
                                    <?php if($VarNewUser==0) {?>
                                        <a class="btn btn-default btn-s addrights" href="javascript:void(0);" onclick="fnShowHideEndUserSub(1,'divEditBasicInfo');"><i class="fa fa-edit"></i> Edit</a>
                                    <?php }?>
                                </div>
                            </div>
                            <div class="box-body ">
                                <form class="form-horizontal" name="frmBasicInfo" id="frmBasicInfo" method="post">
                                    <div id="divEditBasicInfo" class="<?php if($VarNewUser==1) {?>show<?php } else {?>hide<?php }?>">
                                        <div class="alert alert-success alert-dismissable hide" id="divSuccessBasicInfoMsg"></div>
                                        <div class="form-group">
                                            <label for="inputEmail3" class="col-sm-2 control-label">Name</label>
                                            <div class="col-sm-10">
                                                <input type="text" name="frmBasicContactName" class="form-control" id="frmBasicContactName" placeholder="Name (Ex:Raja)" value="<?php echo @$ArrUserBasicInfo['contactname'];?>">
                                                <div class="herr" id="ErrBasicContactName"></div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="inputEmail3" class="col-sm-2 text-right">Gender</label>
                                            <div class="col-sm-10">
                                                <label><input type="radio" class="minimal" name="frmBasicGender" id ="frmBasicGender1" value="1" <?php if(@$ArrUserBasicInfo['gender']==1 || @$ArrUserBasicInfo['gender']=="") {echo "checked";}?>> Male</label>
                                                &nbsp;&nbsp;
                                                <label><input type="radio" class="minimal" name="frmBasicGender" id ="frmBasicGender2" value="2" <?php if(@$ArrUserBasicInfo['gender']==2) {echo "checked";}?>> Female</label>
                                                <div class="herr" id="ErrBasicGender"></div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="inputEmail3" class="col-sm-2 control-label">E-Mail Id</label>
                                            <div class="col-sm-10">
                                                <input type="text" name="frmBasicEmail" class="form-control" id="frmBasicEmail" placeholder="E-Mail (Ex:raja@gmail.com)" value="<?php echo @$ArrUserBasicInfo['emailid'];?>">
                                                <div class="herr" id="ErrBasicEmail"></div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="inputEmail3" class="col-sm-2 control-label">Mobile</label>
                                            <div class="col-sm-10">
                                                <div !class="input-group">
                                                    <input type="text" name="frmBasicMobile" class="form-control" id="frmBasicMobile" placeholder="Mobile Number (Ex:9876878877)"  value="<?php echo @$ArrUserBasicInfo['mobileno'];?>">
                                                </div>
                                                <div class="herr" id="ErrBasicMobile"></div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="inputEmail3" class="col-sm-2 control-label">WeChat Id</label>
                                            <div class="col-sm-10">
                                                <div !class="input-group">
                                                    <input type="text" name="frmBasicWeChatId" class="form-control" id="frmBasicWeChatId" placeholder="WeChat Id"  value="<?php echo @$ArrUserBasicInfo['wechatid'];?>">
                                                </div>
                                                <div class="herr" id="ErrBasicWeChatId"></div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="inputEmail3" class="col-sm-2 control-label">Skype Id</label>
                                            <div class="col-sm-10">
                                                <div !class="input-group">
                                                    <input type="text" name="frmBasicSkypeId" class="form-control" id="frmBasicSkypeId" placeholder="Skype Id"  value="<?php echo @$ArrUserBasicInfo['skypeid'];?>">
                                                </div>
                                                <div class="herr" id="ErrBasicSkypeId"></div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="inputEmail3" class="col-sm-2 control-label">Designation</label>
                                            <div class="col-sm-10">
                                                <input type="text" name="frmBasicDesignation" class="form-control" id="frmBasicDesignation" placeholder="Designation" value="<?php echo @$ArrUserBasicInfo['designation'];?>">
                                                <div class="herr" id="ErrBasicDesignation"></div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="inputEmail3" class="col-sm-2 control-label">Profile Role</label>
                                            <div class="col-sm-10">
                                                <select name="frmBasicProfilePermission" id="frmBasicProfilePermission" class="form-control">
                                                    <option value="">Choose the Profile Role</option>
                                                    <?php
                                                    $ArrProfilePermission   = unserialize(ARRPROFILEPERMISSION);
                                                    foreach($ArrProfilePermission as $VarKey=>$VarVal) {?>
                                                        <option value="<?php echo $VarKey;?>" <?php if($VarKey==@$ArrUserBasicInfo['profilepermission']) {echo "selected";}?>><?php echo $VarVal;?></option>
                                                    <?php }?>
                                                </select>
                                                <div class="herr" id="ErrBasicProfilePermission"></div>
                                            </div>
                                        </div>
                                        <div class="box-footer nopadding">
                                            <button type="button" class="btn btn-default" onclick="fnShowHideEndUserSub(1,'divShowBasicInfo');">Cancel</button>
                                            <button type="submit" class="btn btn-info pull-right  addrights" onclick="return fnSaveEmployee();">Save Changes</button>
                                        </div>
                                    </div>
                                    <div id="divShowBasicInfo" class="<?php if($VarNewUser==1) {?>hide<?php }?>">
                                        <div class="form-group">
                                            <label for="inputEmail3" class="col-sm-2 text-right">Name</label>
                                            <div class="col-sm-10" id="divDispBasicName">
                                                <?php echo $ArrUserBasicInfo['contactname'];?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="inputEmail3" class="col-sm-2 text-right">Gender</label>
                                            <div class="col-sm-10" id="divDispBasiGender">
                                                <?php
                                                $ArrGender	= unserialize(ARRGENDER);
                                                echo @$ArrGender[$ArrUserBasicInfo['gender']];
                                                ?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="inputEmail3" class="col-sm-2 text-right">E-Mail Id</label>
                                            <div class="col-sm-10" id="divDispBasicEmail">
                                                <?php echo $ArrUserBasicInfo['emailid'];?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="inputEmail3" class="col-sm-2 text-right">Mobile No</label>
                                            <div class="col-sm-10" id="divDispBasicMobile">
                                                <?php echo $ArrUserBasicInfo['mobileno'];?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="inputEmail3" class="col-sm-2 text-right">Designation</label>
                                            <div class="col-sm-10" id="divDispBasicDesignation">
                                                <?php echo $ArrUserBasicInfo['designation'];?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="inputEmail3" class="col-sm-2 text-right">Profile Role</label>
                                            <div class="col-sm-10"  id="divDispBasicProfilePermission">
                                                <?php
                                                $ArrProfilePermission   = unserialize(ARRPROFILEPERMISSION);
                                                echo $ArrProfilePermission[$ArrUserBasicInfo['profilepermission']];
                                                ?>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div id="DivContPwdInfo" class="hide">
                        <div class="box box-info">
                            <div class="box-header with-border">
                                <h3 class="box-title">Password</h3>
                                <div class="box-tools pull-right"></div>
                            </div>
                            <div class="box-body">
                                <form class="form-horizontal" name="frmDispPasswordInfo" id="frmDispPasswordInfo" method="post">
                                    <div class="alert alert-success alert-dismissable hide" id="divSuccessPasswordInfoMsg"></div>
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-2 text-right">E-Mail : </label>
                                        <div class="col-sm-10">
                                            <?php echo @$ArrUserBasicInfo['emailid'];?>
                                            admin@knit2020.com
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-2 text-right">Password : </label>
                                        <div class="col-sm-10">
                                            <a href="javascript:void(0);" onclick="fnEmployeeResetPassword();"><i class="fa fa-key"></i> Reset Password</a>
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
<script src="<?php echo base_url();?>assets/plugins/datatables/jquery.dataTables.min.js?r=<?php echo CNFJSCSSRANDNO?>"></script>
<script src="<?php echo base_url();?>assets/plugins/datatables/dataTables.bootstrap.min.js?r=<?php echo CNFJSCSSRANDNO?>"></script>
<script language="javascript">
    var GlbNewUser		= "<?php echo @$VarNewUser;?>";
    var GlbUserId   	= "<?php echo @$VarUserId;?>";
</script>
<script src="<?php echo base_url();?>assets/js/employee.js?r=<?php echo CNFJSCSSRANDNO?>"></script>
<script src="<?php echo base_url();?>assets/js/commonfunctions.js?r=<?php echo CNFJSCSSRANDNO?>"></script>
<?php $this->load->view(CNFCOMPANY.'template/pagefooter');?>