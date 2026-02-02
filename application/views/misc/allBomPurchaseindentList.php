<?php $this->load->view(CNFCOMPANY.'template/pageheader');?>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/css/datatables.min.css">
<style type="text/css">
    /* Dropdown Button */
    .dropbtn {
        border: none;
    }

    /* The container <div> - needed to position the dropdown content */
    .dropdown {
        position: relative;
        display: inline-block;
    }

    /* Dropdown Content (Hidden by Default) */
    .dropdown-content {
        display: none;
        position: relative;
        background-color: #f1f1f1;
        box-shadow: 0px 8px 16px 0px rgba(0, 0, 0, 0.2);
        z-index: 1;
    }

    /* Links inside the dropdown */
    .dropdown-content a {
        color: black;
        padding: 5px;
        text-decoration: none;
        display: block;
    }

    /* Change color of dropdown links on hover */
    .dropdown-content a:hover {
        background-color: #ddd;
    }

    /* Show the dropdown menu on hover */
    .dropdown:hover .dropdown-content {
        display: block;
    }

</style>
    <body class="sidebar-mini skin-black wysihtml5-supported sidebar-collapse">
<div class="wrapper">
    <?php $this->load->view(CNFCOMPANY.'template/templateheader');?>
    <aside class="main-sidebar">
        <?php $this->load->view(CNFCOMPANY.'template/templateleftmenu');?>
    </aside>
    <div class="content-wrapper">
        <section class="content-header">
            <div class="col-md-6" style="padding-left: 20px">
                <h1 style="margin: 0; font-size: 20px; font-weight: 700">P.I. LIST</h1>
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
                    <!--<div class="box" style="border-top-color: #4747d1">-->
                    <div class="box box-info">
                        <div class="box-body no-padding">
                            <?php
                            if($loggedUserType == 8) {}
                                ?>
                                <table id="mBomPurchaseIndentList" class="table table-bordered table-hover">
                                    <thead>
                                    <tr>
                                        <th></th>
                                        <th  id="0">WIP Ref. No.</th>
                                        <th  id="2">Vendor</th>
                                        <th  id="2">P.I. No.</th>
                                        <th  id="3">Requirement</th>
                                        <th  id="5">Expt. Delivery Date & Time</th>
                                        <th  id="5">Cutoff <br />Date & Time</th>
                                        <th  id="7">Approved By Name</th>
                                        <th  id="6">Merchant Name / Code</th>
                                        <th  id="9">Current <br />Status</th>
                                        <th  id="10">Recent <br />Update </th>
                                        <th  id="11">Active / Inactive Status</th>
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
<script src="<?php echo base_url();?>assets/js/loadingoverlay.min.js"></script>
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
        dataTbl = $('#mBomPurchaseIndentList').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": "<?php echo site_url('dashboard/bompurchaseindentlist') ?>",
                "type": "POST",
                "data": {"rfrom":1}
            },
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
                        dataTbl.ajax.url("<?php echo base_url('dashboard/bompurchaseindentlist') ?>").load();

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
<?php $this->load->view(CNFCOMPANY.'template/pagefooter');?>