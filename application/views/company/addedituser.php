<?php $this->load->view(CNFCOMPANY.'template/pageheader');?>
    <body class="sidebar-mini skin-black wysihtml5-supported sidebar-collapse">
<div class="wrapper">
    <?php $this->load->view(CNFCOMPANY.'template/templateheader');?>
    <aside class="main-sidebar">
        <?php $this->load->view(CNFCOMPANY.'template/templateleftmenu');?>
    </aside>
    <div class="content-wrapper">
        <section class="content-header">
            <h1>Add/Edit User</h1>
            <ol class="breadcrumb">
                <li><a href="<?php echo base_url().CNFCOMPANY?>dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
                <li><a href="<?php echo base_url().CNFCOMPANY?>profile/manageusers/">Manage User(s)</a></li>
                <li class="active">Add/Edit User</li>
            </ol>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-md-2">
                    <div class="box box-solid">
                        <div class="box-header with-border">
                            <h3 class="box-title">Basic Info.</h3>
                            <div class="box-tools">
                                <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                            </div>
                        </div>
                        <div class="box-body no-padding">
                            <ul class="nav nav-pills nav-stacked">
                                <li><a href="javascript:void(0);" onclick="fnShowProfileCont('DivContBasicInfo');"><i class="fa fa-circle-o text-yellow"></i> Basic Information</a></li>
                                <li><a href="javascript:void(0);" onclick="fnShowProfileCont('DivContContactInfo');"><i class="fa fa-circle-o text-yellow"></i> Contact Details</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-md-10">

                    <div id="DivContBasicInfo">
                        <div class="box box-info">
                            <div class="box-header with-border">
                                <h3 class="box-title">Basic Information</h3>
                            </div>
                            <div class="box-body ">
                                <form class="form-horizontal" name="frmBasicInfo" id="frmBasicInfo" method="post">
                                    <div id="divEditBasicInfo">
                                        <div class="alert alert-success alert-dismissable hide" id="divSuccessBasicInfoMsg"></div>
                                        <div class="form-group">
                                            <label for="inputEmail3" class="col-sm-2 control-label">Name</label>
                                            <div class="col-sm-10">
                                                <input type="text" name="frmBasicName" class="form-control" id="frmBasicName" placeholder="Name (Ex:Raja)" value="<?php echo @$ArrUserBasicInfo['profilename'];?>">
                                                <div class="herr" id="ErrBasicName"></div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="inputEmail3" class="col-sm-2 control-label">E-Mail Id</label>
                                            <div class="col-sm-10">
                                                <input type="text" name="frmBasicEmailId" class="form-control" id="frmBasicEmailId" placeholder="E-mail Id(Ex: employee@gmail.com)" value="<?php echo @$ArrUserBasicInfo['emailid'];?>">
                                                <div class="herr" id="ErrBasicEmailId"></div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="inputEmail3" class="col-sm-2 control-label">Mobile</label>
                                            <div class="col-sm-10">
                                                <input type="text" name="frmBasicMobile" class="form-control" id="frmBasicMobile" placeholder="Mobile(Ex: 937366323)" value="<?php echo @$ArrUserBasicInfo['mobile'];?>">
                                                <div class="herr" id="ErrBasicMobile"></div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="inputEmail3" class="col-sm-2 control-label">Designation</label>
                                            <div class="col-sm-10">
                                                <input type="text" name="frmBasicDesignation" class="form-control" id="frmBasicDesignation" placeholder="Designation" value="<?php echo @$ArrUserBasicInfo['designation'];?>">
                                                <div class="herr" id="ErrBasicDesignation"></div>
                                            </div>
                                        </div>

                                        <div class="col-xs-12 col-md-6 col-sm-6 col-lg-6 pdl0">
                                            <div class="form-group">
                                                <label for="inputEmail3" class="col-sm-4 text-right">Department</label>
                                                <div class="col-sm-8">
                                                    <select name="frmBasicDepartment" id="frmBasicDepartment" class="form-control">
                                                        <option value="">Choose the Department</option>
                                                        <option value="1">Department 1</option>
                                                    </select>
                                                    <div class="herr" id="ErrBasicDepartment"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-md-6 col-sm-6 col-lg-6 pdl0">
                                            <div class="form-group">
                                                <label for="inputEmail3" class="col-sm-4 control-label">Role</label>
                                                <div class="col-sm-8">
                                                    <select name="frmBasicRoles" id="frmBasicRoles" class="form-control">
                                                        <option value="">Choose the Role</option>
                                                        <option value="1">Role 1</option>
                                                    </select>
                                                    <div class="herr" id="ErrBasicRoles"></div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="clearfix"></div>
                                        <div class="box-footer nopadding">
                                            <button type="button" class="btn btn-default" >Cancel</button>
                                            <button type="submit" class="btn btn-info pull-right  addrights" onclick="return fnSaveUser();">Save Changes</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div id="DivContContactInfo" class="hide">
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
<link rel="stylesheet" href="<?php echo base_url();?>assets/plugins/select2/select2.min.css">
<script src="<?php echo base_url();?>assets/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url();?>assets/plugins/datatables/dataTables.bootstrap.min.js"></script>
<script src="<?php echo base_url();?>assets/plugins/select2/select2.full.min.js"></script>
<script language="javascript">
    $(document).ready(function() {
        $(".select2").select2();
    });
    var GlbNewUser		= "<?php echo @$VarNewUser;?>";
    var GlbUserId   	= "<?php echo @$VarUserId;?>";
</script>
<script src="<?php echo base_url();?>assets/js/companyprofile.js?r=<?php echo CNFJSCSSRANDNO?>"></script>
<script src="<?php echo base_url();?>assets/js/commonfunctions.js?r=<?php echo CNFJSCSSRANDNO?>"></script>
<?php $this->load->view(CNFCOMPANY.'template/pagefooter');?>