<?php $this->load->view(CNFCOMPANY . 'template/pageheader'); ?>
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/css/datatables.min.css">
<body class="layout-top-nav">
<div class="wrapper">
    <?php $this->load->view(CNFCOMPANY . 'template/templateheader'); ?>
    <div class="content-wrapper bgn-white">
        <div class="col-sm-12" style="display: block ruby; padding: 10px 25px">
            <span class="header-title"><b style="font-size: 20px !important; font-family: Arial">BOM - ORDER STOCK LIST</b></span>
            <div class="btn-toolbar pull-right " style="padding-top: 4px" role="toolbar" aria-label="Toolbar with button groups">
                <div class="btn-group mr-2v text-right" role="group" aria-label="First group">
                    <i class="fa fa-search-plus btn  btn-royal-blue" style="padding: 6px 12px;font-size: 16px;" onclick="
                            $(this).toggleClass('fa-search-plus fa-search')
                            $('.search_area').toggleClass('hide');"></i>
                </div>
                <div class="btn-group px-3" role="group" aria-label="Third group">
                    <select name="frmItemStatus" title="activate / deactivate" id="frmItemStatus" class="input-sm form-control  js-example-basic-single-no-search nrml-slt-inp-sts">
                        <option value="">Select</option>
                        <option value="1">Active</option>
                        <option value="2">Inactive</option>
                    </select>
                </div>
                <div class="btn-group mr-2v text-right" role="group" aria-label="Second group">
                    <button name="btnChangeStatus" id="btnChangeStatus" class="btn btn-sm btn-royal-blue">
                        Update
                    </button>
                </div>
            </div>
        </div>
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
                                        <th id="2">Item <br /> Description</th>
                                        <!--<th id="3">Blend (%) / Content <br /> / Material</th>-->
                                        <th id="2">Garment <br /> Size</th>
                                        <th id="2">Item Code</th>
                                        <th id="5">Item Colour <br /> Code</th>
                                        <th id="3">Size / Dim. <br /> (L*W*H)</th>
                                        <th id="3">UOM</th>
                                        <th id="3">Available<br>Qty.</th>
                                        <th id="3">UOM</th>
                                        <th id="3">M.I. Ref. No.</th>
                                        <th id="3">Cutoff <br> Date & Time</th>
                                        <th id="3">M.I. Status</th>
                                        <!--<th id="11">Current <br /> Status</th>-->
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
<script src="<?php echo base_url();?>assets/custom/request/bom/store/order_issued_list.js"></script>
<script>
    $(document).ajaxStart(function (a) {
        $.LoadingOverlay("show", {image: base_path + "assets/img/fullpage.gif"});
    });
    $(document).ajaxStop(function () {
        $.LoadingOverlay("hide");
    });
</script>
<?php $this->load->view(CNFCOMPANY . 'template/pagefooter'); ?>