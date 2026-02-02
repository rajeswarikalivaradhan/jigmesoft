<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/plugins/datepicker/datepicker3.css">
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/css/datatables.min.css">
<?php $this->load->view(CNFCOMPANY . 'template/pageheader'); ?>
<body class="sidebar-mini skin-black wysihtml5-supported sidebar-collapse">
<div class="wrapper">
    <?php $this->load->view(CNFCOMPANY . 'template/templateheader'); ?>
    <aside class="main-sidebar">
        <?php $this->load->view(CNFCOMPANY . 'template/templateleftmenu'); ?>
    </aside>
    <div class="content-wrapper">
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box" style="border-top-color: #4747d1">
                        <div class="box-header with-border">
                            <h3 class="box-title">INDENT RECEIVED LIST</h3>
                        </div><!-- /.box-header -->
                        <div class="box-body table-responsive no-padding">
                            <div class="col-sm-3 col-xs-12 pull-right mpull-left no-padding">
                                <div id="ResResult"></div>
                                <div class="col-sm-9 col-xs-9 no-padding">
                                    <select name="frmItemStatus" id="frmItemStatus" class="form-control wd80">
                                        <option value="">Select</option>
                                        <option value="1">Activate</option>
                                        <option value="2">Deactivate</option>
                                    </select>
                                    <div class="herr" id="ErrItemStatus"></div>
                                </div>
                                <div class="col-sm-3  col-xs-3 pdr0">
                                    <input type="button" name="btnChangeStatus" id="btnChangeStatus"
                                           style="color: #000000" class="btn btn-info pull-right" value="Update">
                                </div>
                            </div>
                            <table id="mCadIndentList" class="display" cellspacing="0" width="100%">
                                <thead>
                                <tr>
                                    <th></th>
                                    <th class="sortable asc" id="0">WIP Ref. No.</th>
                                    <th class="sortable asc" id="1">Brand / Buyer</th>
                                    <th class="sortable asc" id="2">Queue No.</th>
                                    <th class="sortable asc" id="3">Material Indent To</th>
                                    <th class="sortable asc" id="4">Material Indent Ref. No.</th>
                                    <?php

                                    ?>
                                    <th class="sortable asc" id="5">Request <br />Date & Time</th>
                                    <th class="sortable asc" id="6">Cutoff <br />Date & Time</th>
                                    <th class="sortable asc" id="7">Authorization <br />Type</th>
                                    <th class="sortable asc" id="8">Authorized By Name / Code</th>
                                    <th class="sortable asc" id="9">Current <br />Status</th>
                                    <th class="sortable asc" id="10">Recent <br />Update </th>
                                    <th class="sortable asc" id="11">Active Inactive Status</th>
                                </tr>
                                </thead>
                                <tbody>
                                </tbody>

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
<script src="<?php echo base_url();?>assets/js/moment.min.js"></script>
<script src="<?php echo base_url();?>assets/js/bootstrap-datetimepicker.js"></script>
<script src="<?php echo base_url(); ?>assets/js/loadingoverlay.min.js"></script>
<script src="<?php echo base_url();?>assets/js/datatables.min.js"></script>
<script>
    //fnListCadIndent();
    $(document).ajaxStart(function (a) {
        $.LoadingOverlay("show", {image: base_path + "assets/img/fullpage.gif"});
    });
    $(document).ajaxStop(function () {
        $.LoadingOverlay("hide");
    });

    $(document).ready(function() {
        $('#mCadIndentList').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": "<?php echo site_url('samplinguser/samindentlist_ajaxdata')?>",
                "type": "POST"
            },
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
                var indentTbls = ['cadindentdetails'];
                if(confirm('Do you want to '+StatusText+' this records?')) {
                    MakeAsynPostRequest(base_path+'dashboard/changeAllListActiveStatus',"cs=" + StatusOptSelVal +"&keyField=requestid&id="+
                        JSON.stringify(ObjChkSelVal)+"&tblname="+JSON.stringify(indentTbls),'json',fnChangeStatusRes);
                }
            }
        } else {
            $('#ErrItemStatus').text("Choose an Option");
        }
    });

</script>
<?php $this->load->view(CNFCOMPANY . 'template/pagefooter'); ?>