<?php $this->load->view(CNFCOMPANY . 'template/pageheader'); ?>
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/css/datatables.min.css">
<body class="sidebar-mini skin-black wysihtml5-supported sidebar-collapse">
<div class="wrapper">
    <?php $this->load->view(CNFCOMPANY . 'template/templateheader'); ?>
    <aside class="main-sidebar">
        <?php $this->load->view(CNFCOMPANY . 'template/templateleftmenu'); ?>
    </aside>
    <div class="content-wrapper">
        <section class="content-header">
            <div class="col-md-6">
                <h1 style="margin: 0; font-size: 20px; font-weight: 600">PURCHASE INDENT LIST</h1>
            </div>
            <div class="col-md-6" style="padding-bottom: 10px">
                <div class="col-md-6"></div>
                <div class="col-md-6 updateBtnInList">
                    <div class="col-md-8">
                        <select name="frmItemStatus" title="activate / deactivate" id="frmItemStatus" class="form-control" style="">
                            <option value="">Select</option>
                            <?php
                            $ArrStatus = unserialize(ARRSTATUS);
                            foreach ($ArrStatus as $key => $status) {
                                echo '<option value="'.$key.'">'.$status.'</option>';
                            }
                            ?>
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
                    <div class="box box-info">
                        <div class="box-body no-padding">
                            <table id="bomPurchaseReceivedListTbl" class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th id="0">WIP Ref. No. </th>
                                        <th id="1">Brand</th>
                                        <th id="2">Request For</th>
                                        <th id="2">Requirement</th>
                                        <th id="3">Vendor Name</th>
                                        <th id="2">P.I. Ref. No.</th>
                                        <th id="5">Cutoff <br />Date & Time</th>
                                        <th id="4">P.I. Date</th>
                                        <th id="3">Expected <br />Date Of Delivery</th>
                                        <th id="11">Current <br>Status</th>
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
<script src="<?php echo base_url(); ?>assets/js/loadingoverlay.min.js"></script>
<script src="<?php echo base_url();?>assets/js/datatables.min.js"></script>
<script src="<?php echo base_url();?>assets/custom/request/bom/purchase_pi_list.js"></script>
<script>
    $(document).ajaxStart(function (a) {
        $.LoadingOverlay("show", {image: base_path + "assets/img/fullpage.gif"});
    });
    $(document).ajaxStop(function () {
        $.LoadingOverlay("hide");
    });
</script>
<?php $this->load->view(CNFCOMPANY . 'template/pagefooter'); ?>