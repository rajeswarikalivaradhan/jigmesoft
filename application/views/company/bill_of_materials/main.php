<!DOCTYPE html>
<html>
<?php $this->load->view(CNFCOMPANY . 'template/pheader'); ?>
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/jexcel4/jsuites.css"/>
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/jexcel4/jexcel.css"/>
<body class="hold-transition sidebar-mini sidebar-collapse">
<!-- Site wrapper -->
<div class="wrapper">
    <?php $this->load->view(CNFCOMPANY . 'template/nav_sidebar'); ?>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Collapsed Sidebar</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item"><a href="#">Layout</a></li>
                            <li class="breadcrumb-item active">Collapsed Sidebar</li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <!-- Default box -->
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Title</h3>
                                <div class="card-tools">
                                    <button type="button" class="btn btn-tool" data-card-widget="collapse" data-toggle="tooltip" title="Collapse">
                                        <i class="fas fa-minus"></i></button>
                                    <button type="button" class="btn btn-tool" data-card-widget="remove" data-toggle="tooltip" title="Remove">
                                        <i class="fas fa-times"></i></button>
                                </div>
                            </div>
                            <div class="card-body">
                                <table id="newOrderEntrycommonTbl" class="table  table-responsive">
                                    <tbody><tr>
                                        <td style="width: 300px; padding: 5px">
                                            <table class="table  table-responsive">
                                                <tbody><tr>
                                                    <td class="pinkHeading" style="padding-left: 10px"><strong>Majestic Exports</strong></td>
                                                </tr>
                                                <tr>
                                                    <td style="padding-left: 10px">Thudiyalur</td>
                                                </tr>

                                                </tbody></table>
                                        </td>
                                        <td style="padding: 5px">
                                            <table class="table  table-responsive">
                                                <tbody><tr>
                                                    <td class="secondheading" style="padding-left: 10px">Merch. Name</td>
                                                    <td style="padding-left: 10px">
                                                        Merchant 1                    </td>
                                                    <td class="secondheading" style="padding-left: 10px">Team Name</td>
                                                    <td style="padding-left: 10px">Management 1</td>
                                                </tr>
                                                <tr>
                                                    <td class="secondheading" style="padding-left: 10px">Merch. Code</td>
                                                    <td id="merchantCode" style="padding-left: 10px">
                                                        5_5ff56cb6c1236                    </td>
                                                    <td class="secondheading" style="padding-left: 10px">Team Code</td>
                                                    <td style="padding-left: 10px">6_5ff56cd96d52b</td>
                                                </tr>
                                                <tr>
                                                    <td class="secondheading" style="padding-left: 10px">Contact No.</td>
                                                    <td id="merchantContactNo" style="padding-left: 10px">9876543210</td>
                                                    <td class="secondheading" style="padding-left: 10px">Contact No.</td>
                                                    <td style="padding-left: 10px">9994227234</td>
                                                </tr>
                                                <tr>
                                                    <td class="secondheading" style="padding-left: 10px">E-Mail Id</td>
                                                    <td id="merchantEmail" style="padding-left: 10px">merchant1@mexports.com</td>
                                                    <td class="secondheading" style="padding-left: 10px">E-Mail Id</td>
                                                    <td style="padding-left: 10px">mgmt1@mexports.com</td>
                                                </tr>

                                                </tbody></table>
                                        </td>
                                        <td style="padding: 5px">
                                            <table class="table  table-responsive">
                                                <tbody><tr>
                                                    <td colspan="4" align="center" class="pinkHeading"><strong>INTERNAL REFERENCE NO.</strong></td>
                                                </tr>
                                                <tr>
                                                    <td class="secondheading" style="padding-left: 10px">WIP No.</td>
                                                    <td id="frmIorNumber" style="padding-left: 10px" colspan="3">
                                                        ISR-BSG4/0121                            </td>
                                                </tr>
                                                <tr>
                                                    <td class="secondheading" style="padding-left: 10px">
                                                        Date &amp; Time
                                                    </td>
                                                    <td style="padding-left: 10px" colspan="3">
                                                        01-02-2021 13:54:43                            </td>
                                                </tr>
                                                <tr>
                                                    <td class="secondheading" style="width: 75px; padding-left: 10px">Exc. Rate - Static</td>

                                                    <td style="width: 60px; padding-left: 10px">
                                                        0.00                    </td>

                                                    <td class="secondheading" style="width: 75px; padding-left: 10px">Dynamic</td>

                                                    <td style="width: 60px; padding-left: 10px">
                                                        0.00                    </td>
                                                </tr>
                                                </tbody></table>
                                        </td>
                                    </tr>
                                    </tbody></table>
                                <table class="table  table-responsive" style="margin: 5px">

                                    <tbody><tr>
                                        <td class="secondheading" style="width: 100px; padding-left: 10px; padding-top: 7px">Order Ref. No.</td>
                                        <td style="width: 235px; padding-left: 10px; padding-top: 7px">Order 002</td>

                                        <td class="secondheading" style="width: 50px; padding-left: 10px; padding-top: 7px">Brand</td>
                                        <td style="width: 170px; padding-left: 10px; padding-top: 7px">
                                            Zara        </td>

                                        <td class="secondheading" style="width: 50px; padding-left: 10px; padding-top: 7px">Season</td>
                                        <td style="width: 200px; padding-left: 10px; padding-top: 7px; padding-right: 8px">Season </td>

                                        <td class="secondheading" style="width: 70px; padding-left: 10px; padding-top: 7px">Class</td>
                                        <td style="width: 200px; padding-left: 10px; padding-top: 7px; padding-right: 8px">
                                            Set        </td>

                                        <td class="secondheading" style="width: 100px; padding-left: 10px; padding-top: 7px">Total Qty.</td>
                                        <td style="width: 200px; padding-left: 10px; padding-top: 7px; padding-right: 8px">
                                            24000&nbsp;&nbsp;&nbsp;Set        </td>
                                    </tr>

                                    <tr>
                                        <td class="secondheading" style="padding-left: 10px;  padding-top: 7px">Style Ref. No.</td>
                                        <td style="padding-left: 10px; padding-top: 7px">
                                            <input type="hidden" name="frmStyleRefNo" id="frmStyleRefNo" class="form-control" value="Style Ref. No. 2">
                                            Style Ref. No. 2        </td>

                                        <td class="secondheading" style="padding-left: 10px; padding-top: 7px">Buyer</td>
                                        <td style="padding-left: 10px; padding-top: 7px">Inditex</td>

                                        <td class="secondheading" style="padding-left: 10px; padding-top: 7px">Div./Dept.</td>
                                        <td style="width: 200px; padding-left: 10px; padding-top: 7px; padding-right: 8px">
                                            Dept. 1        </td>

                                        <td class="secondheading" style="padding-left: 10px; padding-top: 7px">Sub Class</td>
                                        <td style="width: 200px; padding-left: 10px; padding-top: 7px; padding-right: 8px">
                                            Sports Wear        </td>
                                        <td class="secondheading" style="padding-left: 10px; padding-top: 7px">
                                            Price Per Unit
                                        </td>

                                        <td style="padding-left: 10px; padding-top: 7px">
                                            5.50&nbsp;&nbsp;&nbsp;INR        </td>

                                    </tr>

                                    <tr>
                                        <td class="secondheading" style="padding-left: 10px; padding-top: 7px">Style Name</td>
                                        <td style="padding-left: 10px; padding-right: 7px" colspan="7">
                                            <div class="customcontrol" style="padding-left: 10px">
                                                Style Description 2            </div>

                                            <input type="hidden" name="frmStyleName" id="frmStyleName" class="form-control" value="Style Description 2">
                                        </td>

                                        <td class="secondheading" style="padding-left: 10px; padding-top: 7px">Pay. Terms</td>
                                        <td style="padding-left: 10px; padding-right: 10px; padding-top: 7px">
                                            LC at sight.
                                        </td>
                                    </tr>



                                    </tbody></table>
                                <!--Start creating your amazing application!-->
                                <div id="bom_article1"></div>

                            </div>
                            <!-- /.card-body -->
                            <div class="card-footer">
                                Footer
                            </div>
                            <!-- /.card-footer-->
                        </div>
                        <!-- /.card -->
                    </div>
                </div>
            </div>
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

    <?php $this->load->view(CNFCOMPANY . 'template/pfooter'); ?>

</div>
<!-- ./wrapper -->

</body>
<?php $this->load->view(CNFCOMPANY . 'template/footer_scripts'); ?>
<script src="<?php echo base_url(); ?>assets/js/jexcel4/jsuites.js"></script>
<script src="<?php echo base_url(); ?>assets/js/jexcel4/jexcel.js"></script>
<script type="text/javascript">
    jexcel(document.getElementById('bom_article1'), {
        columns: [
            {type: 'dropdown', title: 'Combo', width: 110, source: []},
            {type: 'dropdown', title: 'Component', width: 110, source: []},
            {type: 'dropdown', title: 'Colour', width: 110, source: []},
            {type: 'text', title: 'A', width: 110},
            {type: 'text', title: 'B', width: 110},
            {type: 'text', title: 'C', width: 110},
            {type: 'text', title: 'D', width: 110},
            {type: 'text', title: 'F', width: 110},
            {type: 'text', title: 'G', width: 110},
            {type: 'text', title: 'H', width: 110},
            {type: 'text', title: 'I', width: 110},
            { type:'calendar',title:'Ship. Date / Subn. Date', width:80 },
            {type: 'text', title: 'J', width: 110}
        ],
        data: [
            []
        ]
    });
</script>
</html>