<?php $this->load->view(CNFCOMPANY . 'template/pageheader'); ?>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/plugins/datepicker/datepicker3.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/datatables.min.css">
    <body class="sidebar-mini skin-black wysihtml5-supported sidebar-collapse">
<div class="wrapper">
    <?php $this->load->view(CNFCOMPANY . 'template/templateheader'); ?>
    <aside class="main-sidebar">
        <?php $this->load->view(CNFCOMPANY . 'template/templateleftmenu'); ?>
    </aside>
    <div class="content-wrapper">
        <section class="content-header">
            <div class="col-md-6" style="padding-left: 20px">
                <h1 style="margin: 0; font-size: 20px; font-weight: 700">QUEUE LIST</h1>
            </div>
            <div class="col-md-6" style="padding-bottom: 10px">
                <div class="col-md-6"></div>
                <div class="col-md-6">
                    <div class="col-md-8">
                        <select name="frmItemStatus" id="frmItemStatus" class="form-control" style="">
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
                    <div class="box" style="border-top-color: #4747d1">
                        <div class="box-body no-padding">
                            <?php
                            if ($usertype == 3 || $usertype == 15) {
                                ?>
                                <table id="mCadQueueList" class="table table-bordered table-hover">
                                    <thead>
                                    <tr>
                                        <th></th>
                                        <th id="0">WIP Ref. No.
                                        </th>
                                        <th id="1">Brand / Buyer
                                        </th>
                                        <th id="8">QUEUE NO.</th>
                                        <th id="2">Request</th>
                                        <th id="3">Requirement</th>
                                        <th id="2">Request Date & Time<i
                                                class=" "></i></th>
                                        <th id="5">Cutoff Date & Time<i
                                                class=" "></i></th>
                                        <th id="7">Authorization Type<i
                                                class=" "></i></th>
                                        <th id="6">Merchant Name / Code<i
                                                class=" "></i></th>
                                        <th id="9">Current Status
                                        </th>
                                        <th id="10">Recent Update
                                        </th>
                                        <th id="11">Active Inactive Status<i
                                                class=" "></i></th>
                                    </tr>
                                    </thead>
                                </table>
                                <?php
                            } else {
                                if ($usertype == 4) {
                                    ?>
                                    <table id="mCadQueueList" class="table table-bordered table-hover display stripe">
                                        <thead>
                                        <tr>
                                            <th></th>
                                            <th id="0">WIP Ref. No.</th>
                                            <th id="1">Brand / Buyer</th>
                                            <th id="8">QUEUE NO.</th>
                                            <th id="3">Request</th>
                                            <th id="3">Requirement</th>
                                            <th id="2">Request <br />Date & Time</th>
                                            <th id="5">Cutoff <br />Date & Time</th>
                                            <th id="7">Authorization <br />Type</th>
                                            <th id="7">Authorized By Name / Code</th>
                                            <th id="9">Current <br />Status</th>
                                            <th id="10">Recent <br />Update </th>
                                            <th id="11">Active Inactive Status</th>
                                        </tr>
                                        </thead>
                                    </table>
                                    <?php
                                } else {
                                    ?>
                                    <table id="mCadQueueList" class="table table-bordered table-hover display stripe">
                                        <thead>
                                        <tr>
                                            <th></th>
                                            <th id="0">WIP Ref. No.
                                            </th>
                                            <th id="1">Brand / Buyer
                                            </th>
                                            <th id="8">QUEUE NO.</th>
                                            <th id="3">Requirement</th>
                                            <th id="2">Request <br />Date & Time</th>
                                            <th id="5">Cutoff <br />Date & Time</th>
                                            <th id="7">Authorization <br />Type</th>
                                            <th id="7">Authorized By Name / Code</th>
                                            <th id="6">Merchant Name / Code</th>
                                            <th id="9">Current <br />Status</th>
                                            <th id="10">Recent <br />Update </th>
                                            <th id="11">Active Inactive Status</th>
                                        </tr>
                                        </thead>
                                    </table>
                                    <?php
                                }
                            }
                            ?>
                        </div><!-- /.box-body -->
                    </div><!-- /.box -->
                </div><!-- /.col -->
            </div>
        </section>
    </div><!-- /.content-wrapper -->
    <?php $this->load->view(CNFCOMPANY . 'template/templatefooter'); ?>
    <div class="control-sidebar-bg"></div>
</div><!-- ./wrapper -->
<!--<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.10.2/moment.min.js"></script>-->
<script src="<?php echo base_url(); ?>assets/js/commonfunctions.js?r=<?php echo CNFJSCSSRANDNO ?>"></script>
<script src="<?php echo base_url(); ?>assets/plugins/datepicker/bootstrap-datepicker.js"></script>
<script src="<?php echo base_url(); ?>assets/js/loadingoverlay.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/datatables.min.js"></script>
<script>
    var dataTbl = '';
    $(document).ready(function () {
        dataTbl = $('#mCadQueueList').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": "<?php echo base_url('dashboard/allqueuelist') ?>",
                "type": "POST",
                "data": {"rFrom": 1}
            },
            'columnDefs': [ {
                'targets': [0,8], // column index (start from 0)
                'orderable': false, // set orderable false for selected columns
            }],
            "order": [3, "desc"]
        });
    });

    $('#btnChangeStatus').on('click', function () {
        var dropdownOpt = $('#frmItemStatus').val();
        if (dropdownOpt > 0) {
            var SelectedIdObject = commonCheckbox();
            var checkBoxLength   = SelectedIdObject[1];
            if (checkBoxLength >= 1) {
                var idJson = JSON.stringify(SelectedIdObject[0]);
                var StatusText                      = "Deactivate";
                if(dropdownOpt == 1) {
                    var StatusText                  = "Activate";
                }
                if(confirm('Do you want to '+StatusText+' this records?')) {
                    MakeAsynPostRequest(base_path+'dashboard/changeAllListActiveStatus','id='+idJson+'&cs='+dropdownOpt+
                        '&tblname=kn_allrequest','json',function (data) {
                        console.log(data,'data');
                        dataTbl.ajax.url("<?php echo base_url('dashboard/allqueuelist') ?>").load();

                    });
                }
            }
            else {
                alert('Select an option');
            }
        }
        else {
            alert('Select a record');
        }
    });

</script>
<?php $this->load->view(CNFCOMPANY . 'template/pagefooter'); ?>