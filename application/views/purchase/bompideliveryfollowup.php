<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/plugins/datepicker/datepicker3.css">
<style type="text/css">
</style>
<?php $this->load->view(CNFCOMPANY.'template/pageheader'); ?>
<body class="sidebar-mini skin-black wysihtml5-supported sidebar-collapse">
<div class="wrapper">
    <?php $this->load->view(CNFCOMPANY.'template/templateheader');?>
    <!-- Left side column. contains the logo and sidebar -->
    <aside class="main-sidebar">
        <?php $this->load->view(CNFCOMPANY.'template/templateleftmenu');?>
    </aside>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <section class="content">
            <div class="row">
                <div class="col-md-12" id="divCommonOrderEntryBasicInfo">
                    <?php $this->load->view('commonBasicInfoOrderEntry') ?>
                </div>
                <div class="col-md-12">
                    <div class="box box-primary" id="bomPIProcessTracking">
                        <div class="box-header with-border">
                            <h3 class="box-title">BOM P.I. DELIVERY FOLLOW-UP</h3>
                            <div class="box-tools pull-right"></div>
                        </div>
                        <div class="box-body">
                            <form class="form-horizontal" id="bompidelfollowupFrm" method="post" action="">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="" class="col-sm-2 control-label">First Follow-up Date & Time</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" id="" placeholder="">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="" class="col-sm-2 control-label">First Follow-up Remarks</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" id="" placeholder="">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="" class="col-sm-2 control-label">Second Follow-up Date & Time</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" id="" placeholder="">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="" class="col-sm-2 control-label">Second Follow-up Remarks</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control required" id="bom_pi_secondfupremarks" name="bom_pi_secondfupremarks">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="" class="col-sm-2 control-label">Expected Delivery Date</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control required" id="" name="">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="" class="col-sm-2 control-label">Delivery Cutoff Date</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" id="" name="">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="" class="col-sm-2 control-label">BOM Recd. Date (Full Qty.)</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" id="" name="">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="" class="col-sm-2 control-label">BOM Lot Approval Status</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" id="bom_pi_bomlotapprstatus" name="bom_pi_bomlotapprstatus">
                                        </div>
                                    </div>
                                </div>
                                <div class="box-footer nopadding">
                                    <div id="divSuccessBasicInfoMsg" class="alert alert-success alert-dismissable hide"></div>
                                    <div class="herr" id="frmReqErr"></div>
                                    <button class="btn btn-info pull-right" type="submit" onclick="return fnSaveBomPiDeliveryFollowup()">Save Cahnges</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div><!-- /.content-wrapper -->
    <?php $this->load->view(CNFCOMPANY.'template/templatefooter'); ?>
    <div class="control-sidebar-bg"></div>
</div><!-- ./wrapper -->
<script src="<?php echo base_url();?>assets/js/ajax.js"></script>
<script src="<?php echo base_url(); ?>assets/plugins/datepicker/bootstrap-datepicker.js"></script>
<script>
    function fnSaveBomPiDeliveryFollowup() {
        var Param = $("#bompidelfollowupFrm").serialize();
        //console.log($(".required").val().length,'len');
        if($(".required").val().length === 0) {
            $("#frmReqErr").text('Error');
            return false;
        }
        else {
            MakeAsynPostRequest(base_path + 'dashboard/updateBompideliveryfollowup', Param, 'json', fnSaveBomPiProcessTrackRes);
            return false;
        }
    }
    function fnSaveBomPiProcessTrackRes(data) {
        //console.log(data,'data');
        if(data != '') {
            if(data.errcode == 1) {
                $("#divSuccessBasicInfoMsg").removeClass('hide');
                $("#divSuccessBasicInfoMsg").text('BOM P.I. Process Tracking has been updated successfully!"');
            }
        }
    }
</script>
<?php $this->load->view(CNFCOMPANY.'template/pagefooter'); ?>