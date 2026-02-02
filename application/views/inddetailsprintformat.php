<link rel="stylesheet" href="<?php echo base_url();?>assets/css/jexcel/jquery.jexcel.min.css" />
<?php $this->load->view(CNFCOMPANY.'template/pageheader');?>
<body class="sidebar-mini skin-black wysihtml5-supported sidebar-collapse">
<div class="wrapper">
    <?php $this->load->view(CNFCOMPANY.'template/templateheader');?>
    <aside class="main-sidebar">
        <?php $this->load->view(CNFCOMPANY.'template/templateleftmenu');?>
    </aside>
    <div class="content-wrapper" style="background-color: #bffff9">
        <section class="content-header">
            <h1>

                <!--<a class="btn btn-default addBtnDetails" href="<?php /*echo base_url().CNFCOMPANY*/?>mSAMPLErequest/addedit"><i class="fa fa-plus" style="margin-right: 10px"></i> ADD SAMPLE REQUEST</a>-->
            </h1>
            <ol class="breadcrumb">
                <!--<li><a href="<?php /*echo base_url().CNFCOMPANY*/?>dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
                <li>SAMPLE Dept. - SAMPLE REQUEST RECEIVED LIST</li>
                <li class="active">SAMPLE Dept. - SAMPLE REQUEST RECEIVED LIST</li>-->
            </ol>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-info">
                        <!--<div class="box-header with-border">

                            <div class="box-tools pull-right"></div>
                        </div>-->
                        <!-- /.box-header -->
                        <div class="box-body">
                            <div class="row invoice-info">
                                <div class="col-sm-2">
                                    <div class="pull-left" style="padding-left: 5px;">
                                        <div class="text-right" style="padding: 5px 0;"><strong>Indent Ref. No :</strong></div>



                                        <div class="text-right" style="padding: 5px 0;"><strong>Cutoff Date & Time : </strong></div>
                                        <div class="text-right" style="padding: 5px 0;"><strong>Issued Date & Time : </strong></div>
                                    </div>
                                </div>
                                <div class="col-sm-2" style="position: relative;right: 72px;">
                                    <div class="" style="padding: 5px 0;"><?php echo $ArrBasicInfo->indentrefno ?></div>
                                    <div class="" style="padding: 5px 0;"><?php echo $ArrBasicInfo->cutoffdatetime ?></div>
                                    <div class="" style="padding: 5px 0;"><?php echo $ArrBasicInfo->cutoffdatetime ?></div>
                                </div>

                                <!-- /.col -->
                                <div class="col-sm-4 text-center">
                                    <h4 class="text-center" style="margin-bottom: 0"><strong>INDENT</strong></h4>
                                    <small>MATERIAL ISSUED DETAILS</small>
                                </div>
                                <!-- /.col -->
                                <div class="col-sm-2 invoice-col">
                                    <div class="pull-right" style="">
                                        <div class="text-right" style="padding: 5px 0;"><strong>From : </strong></div>





                                        <div class="text-right" style="padding: 5px 0;"><strong>To : </strong></div>

                                    </div>

                                    <!--From <span><?php /*echo '' */?></span>-->
                                </div>
                                <div class="col-sm-2 invoice-col" style="position: relative;right: 15px;">
                                    <div class="pull-left">
                                        <div class="text-left" style="padding: 5px 0;">CAD Dept.</div>





                                        <div class="text-left" style="padding: 5px 0;">SAMPLE Dept.</div>

                                    </div>

                                    <!--From <span><?php /*echo '' */?></span>-->
                                </div>

<!--                                <div class="col-sm-2 invoice-col">


                                    To <span><?php /*echo '' */?></span>


                                </div>
-->
                                <!-- /.col -->

                                <!--<form class="form-horizontal" name="frmBasicInfo" id="frmBasicInfo" method="post">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="inputEmail3" class="col-sm-4 control-label">SNO
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="inputEmail3" class="col-sm-4 control-label">CAD Ref. No.
                                            </label>
                                            <div class="col-sm-8">

                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="inputEmail3" class="col-sm-4 control-label">Description
                                            </label>
                                            <div class="col-sm-8">

                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="inputEmail3" class="col-sm-4 control-label">Issued Size(s)
                                            </label>
                                            <div class="col-sm-8">

                                            </div>
                                        </div>

                                    </div>

                                </form>-->

                            </div>

                            <div class="" style="margin-top: 10px">
                                <div id="gridCadIndent">

                                </div>
                                <!--<form class="">
                                    <div class="form-row">
                                        <div class="form-group col-md-1">
                                            <label for="inputEmail4" class="">S No.</label>
                                        </div>
                                        <div class="form-group col-md-1">
                                            <label for="inputPassword4">CAD Ref. No.</label>

                                        </div>
                                        <div class="form-group col-md-2">
                                            <label for="inputAddress">Description</label>

                                        </div>
                                        <div class="form-group col-md-1">
                                            <label for="inputAddress">Issued Size(s)</label>

                                        </div>

                                        <div class="form-group col-md-1">
                                            <label for="inputAddress">Issued Qty.</label>

                                        </div>

                                        <div class="form-group col-md-1">
                                            <label for="inputAddress">Unit of
                                                Measure</label>

                                        </div>
                                        <div class="form-group col-md-1">
                                            <label for="inputAddress">Issued No. of
                                                Parts Per Size</label>

                                        </div>
                                        <div class="form-group col-md-2">
                                            <label for="inputAddress">Returnable /
                                                Non-Returnable</label>

                                        </div>
                                        <div class="form-group col-md-2">
                                            <label for="inputAddress">Returnable
                                                Status</label>

                                        </div>

                                    </div>
                                        <div class="form-row">
                                            <div class="form-group col-md-1">
                                                <div class="customcontrol">1</div>
                                            </div>
                                            <div class="form-group col-md-2">
                                                <div class="customcontrol">1</div>

                                            </div>
                                            <div class="form-group col-md-1">
                                                <div class="customcontrol">1</div>

                                            </div>
                                            <div class="form-group col-md-1">
                                                <div class="customcontrol">1</div>

                                            </div>

                                            <div class="form-group col-md-1">
                                                <div class="customcontrol">1</div>

                                            </div>

                                            <div class="form-group col-md-1">
                                                <div class="customcontrol">1</div>

                                            </div>
                                            <div class="form-group col-md-1">
                                                <div class="customcontrol">1</div>

                                            </div>
                                            <div class="form-group col-md-2">
                                                <div class="customcontrol">1</div>

                                            </div>
                                            <div class="form-group col-md-2">
                                                <div class="customcontrol">1</div>

                                            </div>
                                        </div>


                                        <!--    <div class="customcontrol"></div>
                                        </div>
                                        <div class="form-group col-md-3">

                                            <div class="customcontrol"></div>
                                        </div>
                                        <div class="form-group col-md-2">
                                            <label for="inputAddress2">Issued Size(s)</label>
                                            <div class="customcontrol"></div>
                                        </div>
                                        <div class="form-group col-md-2">
                                            <label for="inputCity">Issued Qty.</label>
                                            <div class="customcontrol"></div>
                                        </div>
                                        <div class="form-group col-md-2">
                                            <label for="inputState" class="control-label">Unit of
                                                Measure</label>
                                            <div class="customcontrol"></div>
                                        </div>
                                        <div class="form-group col-md-1">
                                            <label for="inputZip">Issued No. of
                                                Parts Per Size</label>
                                            <div class="customcontrol"></div>
                                        </div>

                                        <div class="form-group col-md-1">
                                            <label for="inputZip">                                        Returnable /
                                                Non-Returnable
                                            </label>
                                            <div class="customcontrol"></div>
                                        </div>-->

                                <!--</form>-->
                            </div>


                        </div>

                        <div class="box-body">

                                <div class="row">
                                    <div class="col-md-6">
                                        <label class="">Material Issued by</label>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="">Material Received by</label>
                                    </div>

                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label class="control-label">NAME</label>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="control-label">NAME</label>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="row">
                                        <label class="col-md-4">Name</label>

                                        <label class="col-md-4">Signature</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="row">
                                        <label class="col-md-4">Name</label>

                                        <label class="col-md-4">Signature</label>
                                        </div>
                                    </div>
                                </div>
                        </div>
                    </div>
                </div>
                <!-- /.col -->
            </div>
        </section>
    </div><!-- /.content-wrapper -->
    <?php $this->load->view(CNFCOMPANY.'template/templatefooter');?>
    <div class="control-sidebar-bg"></div>
</div><!-- ./wrapper -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.10.2/moment.min.js"></script>
<script src="<?php echo base_url();?>assets/js/jexcel/jquery.jexcel.js"></script>
<script src="<?php echo base_url();?>assets/js/jexcel/numeral.min.js"></script>

<script src="<?php echo base_url();?>assets/js/loadingoverlay.min.js"></script>
<script>
    $('#gridCadIndent').jexcel({
        colHeaders: ['CAD Ref. No.', 'Description', 'Size(s)', 'Issued Qty.', 'Unit of <br/> Measure', 'No. of Parts<br/>per Size', 'Returnable / <br/> Non-Returnable',
            'Returnable<br/>Status'],
        colWidths: [150, 300, 135, 135, 100, 100, 150, 150],
        allowInsertColumn: false,
        columns: [
            {type: 'text',readOnly: true },
            {type: 'text',readOnly: true },
            {type: 'text',readOnly: true},
            {type: 'text', readOnly: true},
            {type: 'text', readOnly: true},
            {type: 'text', readOnly: true},
            {type: 'text', readOnly: true},
            {type: 'text', readOnly: true},
        ]
    });
</script>

<?php $this->load->view(CNFCOMPANY.'template/pagefooter');?>