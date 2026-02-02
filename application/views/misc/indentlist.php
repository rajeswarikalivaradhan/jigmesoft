<?php $this->load->view(CNFCOMPANY . 'template/pageheader'); ?>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/plugins/datepicker/datepicker3.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/css/datatables.min.css">
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
                            <h3 class="box-title">INDENT ISSUED LIST</h3>
                        </div><!-- /.box-header -->
                        <div class="box-body no-padding">
                            <div class="col-sm-12 pdt10">
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
                            </div>
                            <table id="mAllIndentList" class="table table-bordered table-hover">
                                <thead>
                                <tr>
                                    <th></th>
                                    <th>WIP Ref. No.</th>
                                    <th>Brand / Buyer</th>
                                    <th class=" " id="2">Queue No.</th>
                                    <th class=" " id="3">Material Indent To</th>
                                    <th class=" " id="4">Material Indent Ref. No.</th>
                                    <?php

                                    ?>

                                    <th class=" " id="5">Request <br />Date & Time</th>
                                    <th class=" " id="6">Cutoff <br />Date & Time</th>
                                    <th class=" " id="7">Authorization <br />Type</th>
                                    <th class=" " id="8">Authorized By Name / Code</th>
                                    <th class=" " id="9">Current <br />Status</th>
                                    <th class=" " id="10">Recent <br />Update </th>
                                    <th class=" " id="11">Active Inactive Status</th>
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
<script src="<?php echo base_url();?>assets/js/moment.min.js"></script>
<script src="<?php echo base_url();?>assets/js/bootstrap-datetimepicker.js"></script>
<script src="<?php echo base_url(); ?>assets/js/loadingoverlay.min.js"></script>
<script src="<?php echo base_url();?>assets/js/datatables.min.js"></script>
<script>

    $(document).ajaxStart(function (a) {
        $.LoadingOverlay("show", {image: base_path + "assets/img/fullpage.gif"});
    });
    $(document).ajaxStop(function () {
        $.LoadingOverlay("hide");
    });
var dataTbl = '';
    $(document).ready(function() {
        dataTbl = $('#mAllIndentList').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": "<?php echo site_url('dashboard/indentlist_ajaxdata')?>",
                "type": "POST"
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
                        JSON.stringify(ObjChkSelVal)+"&tblname=kn_sreq_indents",'json',fnChangeStatusRes);
                }
            }
        } else {
            $('#ErrItemStatus').text("Choose an Option");
        }
    });

    function fnChangeStatusRes(data) {
        dataTbl.ajax.url("<?php echo site_url('dashboard/indentlist_ajaxdata') ?>").load();
        console.log(data,'data');
    }

</script>
<?php $this->load->view(CNFCOMPANY . 'template/pagefooter'); ?>