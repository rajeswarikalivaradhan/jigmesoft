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
                <h1 style="margin: 0; font-size: 20px; font-weight: 700">BOM INDENT RECEIVED LIST</h1>
            </div>
            <div class="col-md-6" style="padding-bottom: 10px">
                <div class="col-md-6"></div>
                <div class="col-md-6">
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
                    <div class="box box-primary" style="border-top-color: #4747d1">
                        <div class="box-body table-responsive no-padding">
                            <table id="mBomIndentList" class="table table-bordered table-hover">
                                <thead>
                                <tr>
                                    <th></th>
                                    <th>WIP Ref. No.</th>
                                    <th>Brand</th>
                                    <th  id="2">Queue No.</th>
                                    <th  id="3">Material Indent To</th>
                                    <th  id="4">Material Indent Ref. No.</th>
                                    <?php
                                    ?>
                                    <th  id="5">Request <br />Date & Time</th>
                                    <th  id="6">Cutoff <br />Date & Time</th>
                                    <th  id="7">Authorization <br />Type</th>
                                    <th  id="8">Authorized By Name / Code</th>
                                    <th  id="9">Current <br />Status</th>
                                    <th  id="10">Recent <br />Update </th>
                                    <th  id="11">Status</th>
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
        dataTbl = $('#mBomIndentList').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": "<?php echo base_url(CNFCOMPANY.'mstoreuser/bomindentlist')?>",
                "type": "POST",
                "data":{"rfrom":1}
            },
            'columnDefs': [{
                'targets': [0], // column index (start from 0)
                'orderable': false, // set orderable false for selected columns
            }],
            "order": [3,"desc"]
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
                if (confirm('Do you want to ' + GlbStatusForMaster[dropdownOpt] + ' this records?')) {
                    MakeAsynPostRequest(base_path + 'dashboard/cmnChangeStatus', 'idField=requestid&ckId=' + idJson + '&cs=' + dropdownOpt +
                        '&tblName=kn_merchant_sample_bom_indent', 'json', function (data) {
                        console.log(data, 'data');
                        dataTbl.ajax.url("<?php echo base_url(CNFCOMPANY.'mstoreuser/bomindentlist') ?>").load();

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