<?php $this->load->view(CNFCADMIN.'template/pageheader');?>
<body class="sidebar-mini skin-black wysihtml5-supported sidebar-collapse">
<div class="wrapper">
    <?php $this->load->view(CNFCADMIN.'template/templateheader');?>

    <aside class="main-sidebar">
        <?php $this->load->view(CNFCADMIN.'template/templateleftmenu');?>
    </aside>

    <div class="content-wrapper">

        <section class="content-header">
            <h1>
                Manage Employee(s)
                <a class="btn btn-default addBtnDetails" href="<?php echo base_url().CNFCADMIN?>profile/addeditemployee"><i class="fa fa-plus" style="margin-right: 10px"></i> ADD EMPLOYEE</a>
            </h1>
            <ol class="breadcrumb">
                <li><a href="<?php echo base_url().CNFCADMIN?>dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
                <li>Manage Employee</li>
                <li class="active">Employee(s) List</li>
            </ol>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-info">
                        <div class="box-header with-border">
                            <h3 class="box-title">Search</h3>
                            <div class="box-tools pull-right">
                                <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                            </div>
                        </div>
                        <form class="form-horizontal" name="frmNameSearchEmployee" id="frmNameSearchEmployee">
                            <div class="box-body">
                                <div class="col-xs-12 col-md-6 col-sm-6 col-lg-6">
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-4 control-label">Employee Name</label>
                                        <div class="col-sm-8">
                                            <input type="text" name="frmSrchContactName" class="form-control" id="frmSrchContactName" placeholder="Employee Name">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-md-6 col-sm-6 col-lg-6">
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-4 control-label">E-Mail Id</label>
                                        <div class="col-sm-8">
                                            <input type="text" name="frmSrchEmailId" class="form-control" id="frmSrchEmailId" placeholder="E-Mail Id">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-md-6 col-sm-6 col-lg-6">
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-4 control-label">Mobile No</label>
                                        <div class="col-sm-8">
                                            <input type="text" name="frmSrchMobileNo" class="form-control" id="frmSrchMobileNo" placeholder="Mobile No">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-md-6 col-sm-6 col-lg-6">
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-4 control-label">Profile Role</label>
                                        <div class="col-sm-8">
                                           <select name="frmSrchProfileRole" id="frmSrchProfileRole" class="form-control">
                                               <option value="">Choose the Profile Role</option>
                                               <?php
                                               $ArrProfileRoleList  = unserialize(ARRPROFILEPERMISSION);
                                               foreach($ArrProfileRoleList as $VarKey=>$VarProfileName) {?>
                                                    <option value="<?php echo $VarKey?>"><?php echo $VarProfileName?></option>
                                               <?php }?>
                                           </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="box-footer">
                                <button type="button" class="btn btn-default"
                                        onclick="refreshPage('frmNameSearchEmployee');">Reset
                                </button>
                                <button type="button" class="btn btn-info pull-right" onclick="fnSearchEmployee();">Search</button>
                            </div>
                        </form>
                    </div>
                    <div class="box box-info">
                        <div class="box-header with-border">
                            <h3 class="box-title">Employee List</h3>
                        </div>
                        <div class="box-body table-responsive no-padding">
                            <div id="ResResult">
                                <table id="example1" class="table table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>E-Mail Id</th>
                                            <th>Password</th>
                                            <th>Designation</th>
                                            <th>Mobile No</th>
                                            <th>Recent <br />Update </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><a href="<?php echo base_url().CNFCADMIN?>/profile/addeditemployee/MQ%3D%3D/">Administartor</a></td>
                                            <td>admin@knit2020.com</td>
                                            <td>Password12345</td>
                                            <td></td>
                                            <td>99877544</td>
                                            <td>13-06-2017 22:21:00</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <?php $this->load->view(CNFCADMIN.'template/templatefooter');?>
    <div class="control-sidebar-bg"></div>
</div>
<script src="<?php echo base_url();?>assets/js/commonfunctions.js?r=<?php echo CNFJSCSSRANDNO?>"></script>
<script language="javascript">fnListEmployee();</script>
<script src="<?php echo base_url();?>assets/plugins/datatables/jquery.dataTables.min.js?r=<?php echo CNFJSCSSRANDNO?>"></script>
<script src="<?php echo base_url();?>assets/plugins/datatables/dataTables.bootstrap.min.js?r=<?php echo CNFJSCSSRANDNO?>"></script>
<?php $this->load->view(CNFCADMIN.'template/pagefooter');?>