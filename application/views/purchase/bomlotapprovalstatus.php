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
                        <div class="box-header with-border"><h3 class="box-title">BOM LOT APPROVAL STATUS</h3><div class="box-tools pull-right"></div></div>
                        <div class="box-body">
                            <form class="form-horizontal" id="bomlotApprStatusFrm" method="post" action="">
                                <div class="col-md-6">

                                </div>
                                <div class="col-md-6">

                                </div>
                                <div class="box-footer nopadding">
                                    <div id="divSuccessBasicInfoMsg" class="alert alert-success alert-dismissable hide"></div>
                                    <div class="herr" id="frmReqErr"></div>
                                    <button class="btn btn-info pull-right" type="submit" onclick="return fnSaveBomLotApprStatus()">Save Cahnges</button>
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
<script src="<?php echo base_url(); ?>assets/js/ajax.js"></script>
<script src="<?php echo base_url(); ?>assets/plugins/datepicker/bootstrap-datepicker.js"></script>
<script>
    function fnSaveBomLotApprStatus() {
        var Param = $("#bomlotApprStatusFrm").serialize();
        //console.log($(".required").val().length,'len');
        if($(".required").val().length === 0) {
            $("#frmReqErr").text('Error');
            return false;
        }
        else {
            MakeAsynPostRequest(base_path + 'dashboard/updateBomLotApprStatus', Param, 'json', fnSaveBomPiProcessTrackRes);
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