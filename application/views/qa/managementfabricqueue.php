<?php $this->load->view(CNFCOMPANY . 'template/pageheader'); ?>
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/css/datatables.min.css">
<body class="layout-top-nav">
<div class="wrapper">
    <?php $this->load->view(CNFCOMPANY . 'template/templateheader'); ?>
    <div class="content-wrapper bgn-white">
        <div class="col-sm-12" style="display: block ; padding: 10px 25px;padding-top: 110px;">
            <span class="header-title"><b style="font-size: 20px !important; font-family: Arial">FABRIC QUEUE LIST</b></span>
            <div class="btn-toolbar pull-right " style="padding-top: 4px" role="toolbar" aria-label="Toolbar with button groups">
                <div class="btn-group mr-2v text-right" role="group" aria-label="First group">
                    <i class="fa fa-search-plus btn  btn-royal-blue" style="padding: 8px 12px;font-size: 16px;"></i>
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
        <section class="content-header">
            <div class=" px-4 w-90" style="margin-left: 3px; width: 99.8%;">
               <div class="col-sm-12 px-4" style="border-bottom: 1px solid #022B61;"></div>
            </div>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-primary" style="border-top-color: #4747d1">
                        <div class="box-body no-padding">
                            <table id="merchantBomQueueTbl" class="table table-bordered table-hover" data-page-length="50">
                                <thead>
                                <tr>
                                    < <th></th>
                                    <th id="0">WIP Ref. No.</th>
                                    <th id="1"width="7%">Brand</th>
                                    <th id="2">Queue No</th>
                                    <th id="3">Request For</th>
                                    <th id="2"  width="11%">Requirement</th>
                                    <th id="4" >Request <br />Date & Time</th>
                                    <th id="5">Cutoff <br />Date & Time</th>
                                    <th id="6">Authorization <br />Type</th>
                                    <!-- <th id="7">Approved By</th> -->
                                    <th width="11%">Current <br />Status</th>
                                    <th id="9" width="11%">Recent <br />Update </th>
                                    <th id="10" width="5%">Status</th>
                            </table>
                            <div>
                            </div>
                        </div><!-- /.box-body -->
                    </div><!-- /.box -->
                </div><!-- /.col -->
            </div>
        </section>
    </div><!-- /.content-wrapper -->
    <style>
    .dataTables_wrapper .dataTables_filter input {
    border: 1px solid #aaa;
    border-radius: 3px;
    padding: 5px;
    background-color: transparent;
    margin-left: 3px;
    }
    .btn-green {
    color: #fff!important;
    background-color: #29916c!important;
    border-color: #29916c!important;
    font-size:15px!important;;
    }
    .btn-red {
        color: #fff!important;
        background-color: #eb4343!important;
        border-color: #eb4343!important;
        font-size:15px!important;
    }
    .swal2-title {
        font-size: 21px!important;
    }
</style>
    <?php $this->load->view(CNFCOMPANY . 'template/templatefooter'); ?>
    <div class="control-sidebar-bg"></div>
</div><!-- ./wrapper -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.10.2/moment.min.js"></script>
<script src="<?php echo base_url(); ?>assets/plugins/daterangepicker/daterangepicker.js"></script>
<script src="<?php echo base_url(); ?>assets/plugins/datepicker/bootstrap-datepicker.js"></script>
<script src="<?php echo base_url(); ?>assets/js/loadingoverlay.min.js"></script>
<script src="<?php echo base_url();?>assets/js/datatables.min.js"></script>
<script src="<?= base_url() ?>assets/ace/node_modules/sweetalert2/dist/sweetalert2.all.js"></script>
<script src="<?php echo base_url();?>assets/custom/request/fabric/mgmt/managementfabricqueue.js"></script>
<?php $this->load->view(CNFCOMPANY . 'template/pagefooter'); ?>