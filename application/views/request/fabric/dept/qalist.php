<?php $this->load->view(CNFCOMPANY . 'template/pageheader'); ?>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/css/datatables.min.css">
<body class="sidebar-mini skin-black wysihtml5-supported sidebar-collapse">
<div class="wrapper">
    <?php $this->load->view(CNFCOMPANY . 'template/templateheader'); ?>
    <aside class="main-sidebar">
        <?php $this->load->view(CNFCOMPANY . 'template/templateleftmenu'); ?>
    </aside>
    <div class="content-wrapper" style="background-color: #bffff9">
        <section class="content-header">
            <div class="col-md-6" style="padding-left: 20px">
                <h1 style="margin: 0; font-size: 20px; font-weight: 600">FABRIC QUEUE LIST</h1>
            </div>
            <div class="col-md-6" style="padding-bottom: 10px">
                <div class="col-md-6"></div>
                <div class="col-md-6">
                    <div class="col-md-8">
                        <select name="frmItemStatus" id="frmItemStatus" class="form-control" title="activate / deactivate" style="">
                            <option value="">Select</option>
                            <option value="1">Active</option>
                            <option value="2">Inactive</option>
                        </select>
                    </div>
                    <div class="col-md-2" style="padding-right: 0; float: right;">
                        <input type="button" name="btnChangeStatus" id="btnChangeStatus" class="btn btn-info pull-right" value="Update">
                    </div>
                </div>

            </div>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box" style="border-top-color: #4747d1">
                        <div class="box-body table-responsive no-padding">
                            <table id="mAuthorizationList" class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th id="0">WIP Ref. No.</th>
                                        <th id="1">Brand</th>
                                        <th id="2">Queue No.</th>
                                        <th id="4">Requirement</th>
                                        <th id="5">Request <br />Date & Time</th>
                                        <th id="6">Cutoff <br />Date & Time</th>
                                        <th id="3">Merchant <br />Name</th>
                                        <th id="7">Authorization <br />Type</th>
                                        <th id="8">Authorized By</th>
                                        <th>Current <br />Status</th>
                                        <th id="9">Recent <br />Update </th>
                                        <th id="10">Status</th>
                                    </tr>
                                </thead>
                            </table>
                        </div><!-- /.box-body -->
                    </div><!-- /.box -->
                </div><!-- /.col -->
            </div>
        </section>
    </div><!-- /.content-wrapper -->
    <?php $this->load->view(CNFCOMPANY . 'template/templatefooter'); ?>
    <div class="control-sidebar-bg"></div>
</div><!-- ./wrapper -->
<!--<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.10.2/moment.min.js"></script>-->
<script src="<?php echo base_url(); ?>assets/js/moment.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/bootstrap-datetimepicker.js"></script>
<script src="<?php echo base_url(); ?>assets/plugins/datepicker/bootstrap-datepicker.js"></script>
<script src="<?php echo base_url(); ?>assets/js/loadingoverlay.min.js"></script>
<script src="<?php echo base_url();?>assets/js/datatables.min.js"></script>
<script src="<?php echo base_url();?>assets/custom/request/fabric/dept/qalist.js"></script>

<?php $this->load->view(CNFCOMPANY . 'template/pagefooter'); ?>