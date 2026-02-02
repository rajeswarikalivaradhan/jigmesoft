<!DOCTYPE html>
<html>
<?php $this->load->view(CNFCOMPANY . 'template/pheader'); ?>
<body class="hold-transition sidebar-mini">
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
                        <h1 class="firstHeading">Bill of Materials Article 1</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active">Blank Page</li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="box box-info">
                <div class="box-header with-border">
                </div>
                <div class="box-body" style="padding: 0px;">
                    <form class="form-horizontal" name="frmBasicInfo" id="frmOrderProcess" method="post">
                        <div class="alert alert-success alert-dismissible hide"
                             id="divSuccessBasicInfoMsg"></div>
                        <div class="alert alert-danger alert-dismissible hide" id="divErrMsg"></div>
                        <div class="col-md-12 pd0 no-padding">
                            <?php $this->load->view(CNFCOMPANY . "orderentry/orderentrycommondetails"); ?>
                            <div style="background-color: rgb(210 253 249); padding-top: 10px; padding-left: 10px">
                                <strong>BOM DETAILS: Article - 1</strong>
                            </div>
                        </div>
                        <div class="col-sm-12" style="padding: 5px !important">
                            <div class="form-group">
                                <div id="bom_article1"></div>
                            </div>
                        </div>

                        <div class="col-md-12" style="padding: 5px !important;">
                            <div class="form-group" style="margin-bottom: 0">
                                <label for="tblRemarks">Remarks</label>
                                <textarea id="tblRemarks" name="tblRemarks" rows="2" cols="50" class="form-control"></textarea>
                            </div>
                        </div>
                    </form>
                    <div class="box-footer pull-right" style="width: 350px; position: relative; top: -2px">

                        <div class="bottomNav">
                            <div class="" style="width: 90px; float: left; font-size: 18px; text-align: justify">

                            </div>

                            <div class="" style="width: 108px; float: left; padding-left: 0; font-size: 18px; text-align: justify">
                                <a href="<?php echo base_url('billofmaterials/consolidated').'/'.$ArrCommonHeaderData['VarHashEnquiryId'] ?>" style="color: grey">
                                    <span style="position: relative; bottom: 5px; top: 0"><b>NEXT</b></span>
                                    <i class="fa fa-arrow-right" style="font-size: 14px"></i>
                                </a>


                            </div>
                        </div>
                        <div class="saveEditBtn">
                            <?php
                            if($ArrCommonHeaderData['ArrEnquiryDetails']['editaccess'] == 1) {
                                ?>
                                <button type="button" class="btn btn-info oeSaveEditBtn" onclick="return fnSaveTable()">Edit</button>
                                <?php
                            }
                            else {
                                ?>
                                <button type="button" <?php echo $ArrCommonHeaderData['ArrEnquiryDetails']['completestatus'] == 1 ? 'disabled' : '' ?>
                                        class="btn btn-info oeSaveEditBtn" style="" onclick="return fnSaveTable()">Save
                                </button>
                                <?php
                            }
                            ?>
                        </div>
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
</html>