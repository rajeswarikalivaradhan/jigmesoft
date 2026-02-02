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
            <div class="col-md-6" style="padding-left: 20px">
                <h1 style="margin: 0; font-size: 20px; font-weight: 700">CAD INDENT ISSUED LIST</h1>
            </div>
            <div class="col-md-6" style="padding-bottom: 10px">
                <div class="col-md-6"></div>
                <div class="col-md-6">
                    <div class="col-md-8">
                        <select name="frmItemStatus" title="activate / deactivate" id="frmItemStatus" class="form-control" style="">
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
                    <div class="box box-primary" style="border-top-color: #4747d1">
                        <div class="box-body no-padding">
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
                "url": "<?php echo base_url('dashboard/indentList')?>",
                "type": "POST",
                "data":{"rFrom":1}
            },
            'columnDefs': [{
                'targets': [0,6,7,8,9,10,11], // column index (start from 0)
                'orderable': false, // set orderable false for selected columns
            }],
            "order": [0,"desc"]
        });
    });

    $('#btnChangeStatus').on('click', function () {
        var dropdownOpt = $('#frmItemStatus').val();
        console.log(dropdownOpt,'dropdownOpt');
        var SelectedIdObject = commonCheckbox();
        var checkBoxLength   = SelectedIdObject[1];
        if (dropdownOpt > 0) {
            if (checkBoxLength >= 1) {
                var idJson = JSON.stringify(SelectedIdObject[0]);
                var StatusText = "Deactivate";
                if (dropdownOpt == 1) {
                    var StatusText = "Activate";
                }
                if (confirm('Do you want to ' + StatusText + ' this records?')) {
                    MakeAsynPostRequest(base_path + 'dashboard/changeAllListActiveStatus', 'id=' + idJson + '&cs=' + dropdownOpt +
                        '&tblname=kn_order_enquiry', 'json', function (data) {
                        console.log(data, 'data');
                        dataTbl.ajax.url("<?php echo base_url('dashboard/indentList') ?>").load();

                    });
                }
            }
        }
        else {
            alert('Select a option');
        }
        if(checkBoxLength == 0) {
            alert('Select a record');
        }
    });

</script>
<?php $this->load->view(CNFCOMPANY . 'template/pagefooter'); ?>