<?php $this->load->view(CNFCOMPANY.'template/pageheader');?>
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/css/datatables.min.css">
<body class="sidebar-mini skin-black wysihtml5-supported sidebar-collapse">
<div class="wrapper">
    <?php $this->load->view(CNFCOMPANY.'template/templateheader');?>
    <aside class="main-sidebar">
        <?php $this->load->view(CNFCOMPANY.'template/templateleftmenu');?>
    </aside>
    <div class="content-wrapper">
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box" style="border-top-color: #4747d1">
                        <div class="box-header with-border">
                            <h3 class="box-title">REQUEST SENT LIST - (PAYMENT)</h3>
                        </div><!-- /.box-header -->
                        <div class="box-body no-padding">

                            <div class="col-sm-12 pdt10">

                                <div class="col-sm-3 col-xs-12 pull-right mpull-left no-padding">

                                    <div class="col-sm-9 col-xs-9 no-padding">

                                        <select name="frmItemStatus" id="frmItemStatus" class="form-control wd80">

                                            <option value="">Select</option>

                                            <option value="1">Activate</option>

                                            <option value="2">Deactivate</option>

                                        </select>

                                        <div class="herr" id="ErrItemStatus"></div>

                                    </div>

                                    <div class="col-sm-3  col-xs-3 pdr0">

                                        <input type="button" name="btnChangeStatus" id="btnChangeStatus" style="color: #000000" class="btn btn-info pull-right" value="Update">

                                    </div>

                                </div>

                            </div>
                            <table id="mBomPIMgmtApprovalList" class="table table-bordered table-hover">
                                <thead>
                                <tr>
                                    <th></th>
                                    <th  id="0">WIP Ref. No.</th>
                                    <th  id="1">Brand / Buyer</th>
                                    <th  id="2">Request</th>
                                    <th  id="3">Requirement</th>
                                    <th  id="4">Request <br />Date & Time</th>
                                    <th  id="5">Cutoff <br />Date & Time</th>
                                    <th  id="6">Approval Status</th>
                                    <th  id="7">Prepared By Name</th>
                                    <th  id="8">Current <br />Status</th>
                                    <th  id="9">Recent <br />Update </th>
                                    <th  id="10">Active / Inactive Status</th>
                                </tr>
                                </thead>
                            </table>
                        </div><!-- /.box-body -->
                    </div><!-- /.box -->
                </div><!-- /.col -->
            </div>
        </section>
    </div><!-- /.content-wrapper -->
    <?php $this->load->view(CNFCOMPANY.'template/templatefooter');?>
    <div class="control-sidebar-bg"></div>
</div><!-- ./wrapper -->
<script src="<?php echo base_url();?>assets/js/commonfunctions.js?r=<?php echo CNFJSCSSRANDNO?>"></script>
<script src="<?php echo base_url();?>assets/js/datatables.min.js"></script>
<script src="<?php echo base_url();?>assets/js/loadingoverlay.min.js"></script>
<!--<script src="<?php /*echo base_url();*/?>assets/js/management/paymentapprlist.js"></script>-->
<script>
    //fnList();
    var GlbUt = "<?php echo @$usertype ?>";

    $(document).ajaxStart(function (a) {
        $.LoadingOverlay("show", {image: base_path + "assets/img/fullpage.gif"});
    });
    $(document).ajaxStop(function () {
        $.LoadingOverlay("hide");
    });
    var dataTbl = '';
    $(document).ready(function() {
        dataTbl = $('#mBomPIMgmtApprovalList').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": "<?php echo base_url('dashboard/bompiapprovallist')?>",
                "type": "POST",
                "data":{"rfrom":1}
            },
            "order": [0,"desc"]
        });
    });

    $('#btnChangeStatus').on('click',function () {
        var StatusOptSelVal                         = $('#frmItemStatus').val();
        if(parseInt(StatusOptSelVal) > 0) {
            var ArrItemCheckBoxSel                  = commonCheckbox();
            var ObjChkSelVal                        = ArrItemCheckBoxSel[0];
            $('#ErrItemStatus').text("");
            if(parseInt(ArrItemCheckBoxSel[1]) == 0) {$('#ErrItemStatus').html("Choose a record");}
            if(parseInt(ArrItemCheckBoxSel[1]) >= 1) {
                $('#ErrItemStatus').html("");
                var StatusText                      = "Deactivate";
                if(StatusOptSelVal == '1') {
                    var StatusText                  = "Activate";
                }
                if(confirm('Do you want to '+StatusText+' this records?')) {
                    MakePostRequest(base_path+'dashboard/changeAllListActiveStatus',"cs=" + StatusOptSelVal +"&id="+
                        JSON.stringify(ObjChkSelVal)+"&tblname=kn_bom_purchaseindent",'json',fnChangeStatusRes);
                }
            }
        } else {
            $('#ErrItemStatus').text("Choose an Option");
        }
    });

    function fnChangeStatusRes(data) {
        dataTbl.ajax.url("<?php echo site_url('dashbaord/bompiapprovallistAjax') ?>").load();
        console.log(data,'data');
    }

</script>
<?php $this->load->view(CNFCOMPANY.'template/pagefooter');?>