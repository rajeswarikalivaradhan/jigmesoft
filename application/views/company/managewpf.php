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
                Manage Wet Processing - Finishing
                <a class="btn btn-default addBtnDetails" href="<?php echo base_url().CNFCADMIN?>mastersetup/addeditwpf"><i class="fa fa-plus" style="margin-right: 10px"></i> ADD WET PROCESSING - FINISHING</a>
            </h1>
            <ol class="breadcrumb">
                <li><a href="<?php echo base_url().CNFCADMIN?>dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
                <li>Manage Wet Processing - Finishing</li>
                <li class="active">Wet Processing - Finishing List(s)</li>
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
                        <form class="form-horizontal" name="frmNameSearchWPF" id="frmNameSearchWPF">
                            <div class="box-body">
                                <div class="col-xs-12 col-md-6 col-sm-6 col-lg-6">
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-4 control-label">Wet Processing - Finishing</label>
                                        <div class="col-sm-8">
                                            <input type="text" name="frmSrchWPF" class="form-control" id="frmSrchWPF" placeholder="Wet Processing - Finishing">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="box-footer">
                                <button type="button" class="btn btn-default"
                                        onclick="refreshPage('frmNameSearchWPF');">Reset
                                </button>
                                <button type="button" class="btn btn-info pull-right" onclick="fnSearchWPF();">Search</button>
                            </div>
                        </form>
                    </div>
                    <div class="box box-info">
                        <div class="box-header with-border">
                            <h3 class="box-title">Wet Processing - Finishing List</h3>
                        </div>
                        <div class="box-body table-responsive no-padding">
                            <div id="ResResult">
                                <table id="example1" class="table table-bordered table-hover">
                                    <thead>
                                    <tr>
                                        <th>Wet Processing - Finishing</th>
                                        <th>Recent <br />Update </th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td><a href="<?php echo base_url().CNFCADMIN?>mastersetup/addeditwpf/">Wet Processing - Finishing 1</a></td>
                                        <td>13-06-2017 22:21:00</td>
                                        <td><i class="fa fa-edit"></i>&nbsp;<a href="<?php echo base_url().CNFCADMIN?>mastersetup/addeditwpf/">Edit</a>&nbsp;&nbsp;|&nbsp;&nbsp;<i class="fa fa-edit"></i>&nbsp;<a href="javascript:void(0);" onclick="fnDelsizerange();">Delete</a></td>
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
<?php $this->load->view(CNFCADMIN.'template/pagefooter');?>