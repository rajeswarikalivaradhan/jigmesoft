<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/plugins/datepicker/datepicker3.css">
<style type="text/css">
</style>
<?php $this->load->view(CNFCOMPANY . 'template/pageheader'); ?>
<body class="sidebar-mini skin-black wysihtml5-supported sidebar-collapse">
<div class="wrapper">
    <?php $this->load->view(CNFCOMPANY . 'template/templateheader'); ?>
    <!-- Left side column. contains the logo and sidebar -->
    <aside class="main-sidebar">
        <?php $this->load->view(CNFCOMPANY . 'template/templateleftmenu'); ?>
    </aside>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <section class="content-header">
            <h1>Search
                <!--<a class="btn btn-default addBtnDetails" href="<?php /*echo base_url().CNFCOMPANY*/ ?>mcadrequest/addedit"><i class="fa fa-plus" style="margin-right: 10px"></i> ADD CAD REQUEST</a>-->
            </h1>
            <ol class="breadcrumb">
                <li><a href="<?php echo base_url() . CNFCOMPANY ?>dashboard"><i class="fa fa-dashboard"></i> Home</a>
                </li>
                <li></li>
            </ol>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-info">
                        <div class="box-header with-border">
                            <h3 class="box-title">Search</h3>
                            <div class="box-tools pull-right">
                                <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <!-- /.box-header -->
                        <form class="form-horizontal" name="frmSearch" id="frmSearch" action="#">
                            <div class="box-body">
                                <div class="col-xs-12 col-md-6 col-sm-6 col-lg-6">
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-4 control-label">WIP Ref. No.</label>
                                        <div class="col-sm-8">
                                            <input type="text" name="" class="form-control" id="frmSrchWipRefNo" placeholder="WIP Ref. No.">
                                            <input type="hidden" name="hidddenColumnId" id="hidddenColumnId">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-4 control-label">IOR / ISR</label>
                                        <div class="col-sm-8">
                                            <select name="frmNameSearchIsrIorType" id="frmNameSearchIsrIorType" class="form-control">
                                                <option value="">Select IOR / ISR</option>
                                                <?php
                                                $ArrIsrIor  = unserialize(ARRISRIOR);
                                                foreach($ArrIsrIor as $VarKey=>$Varval) { ?>
                                                    <option value="<?php echo $VarKey?>"><?php echo $Varval?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-4 control-label">Brand / Buyer</label>
                                        <div class="col-sm-8">
                                            <select class="form-control" id="frmSrchBB">
                                                <option value="">Choose</option>
                                                <?php
                                                foreach ($ArrBB as $key => $item) {
                                                    ?>
                                                    <option value="<?php echo $item->id ?>"><?php echo $item->brandname .' / '. $item->buyername?></option>
                                                    <?php
                                                }
                                                ?>
                                            </select>
                                            <div class="herr" id="ErrfrmSrchBB"></div>
                                        </div>
                                    </div>
                                    <?php
                                    //echo '<pre>'; print_r($ArrBomPurchaseIndentRefno); die('');
                                    ?>
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-4 control-label">P.I. No</label>
                                        <div class="col-sm-8">
                                            <select class="form-control" id="frmSrchPirefno">
                                                <option value="">Choose</option>
                                                <?php
                                                foreach ($ArrBomPurchaseIndentRefno as $key => $requests) {
                                                    echo '<option value="'.$requests->id.'">'.$requests->pireferenceno.'</option>';
                                                }
                                                ?>
                                            </select>
                                        </div>

                                        <!--<div class="col-sm-4">
                                            <input type="text" name="" class="form-control" id="frmSrchReqDateFrom" placeholder="From">
                                        </div>
                                        <div class="col-sm-4">
                                            <input type="text" name="" class="form-control" id="frmSrchReqDateTo" placeholder="To">
                                        </div>-->
                                    </div>
                                </div>
                                <div class="col-xs-12 col-md-6 col-sm-6 col-lg-6">
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-4 control-label">Item Description</label>
                                        <div class="col-sm-8">
                                            <?php
                                            //                                            echo '<pre>'; print_r($ArrRequirements); die('');

                                            ?>
                                            <select class="form-control" id="frmSrchRequirement">
                                                <option value="">Choose</option>
                                                <?php
                                                ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group <?php if($usertype == 3 || $usertype == 8) echo ''; else echo 'hide' ?>">
                                        <!--<label for="inputEmail3" class="col-sm-4 control-label">Merchant Name</label>
                                        <div class="col-sm-8">
                                            <select class="form-control" id="frmSrchMerchantName">
                                                <option value="">Choose</option>
                                                <?php
/*                                                foreach ($ArrMerchant as $key => $item) {
                                                    */?>
                                                    <option value="<?php /*echo $item['id'] */?>"><?php /*echo $item['contactname'] . ' / ' . $item['code'] */?></option>
                                                    <?php
/*                                                }
                                                */?>
                                            </select>
                                            <div class="herr" id="ErrfrmSrchMerchantName"></div>
                                        </div>-->
                                    </div>
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-4 control-label">Indent Cutoff Date</label>
                                        <div class="col-sm-4">
                                            <input type="text" name="" class="form-control" id="frmSrchCutOffDateFrom" placeholder="From">
                                        </div>
                                        <div class="col-sm-4">
                                            <input type="text" name="" class="form-control" id="frmSrchCutOffDateTo" placeholder="To">
                                        </div>
                                    </div>
                                    <div class="form-group <?php //if($usertype == 3 || $usertype == 8) echo 'hide'; else echo '' ?>">
                                        <label for="inputEmail3" class="col-sm-4 control-label">Approval Status</label>
                                        <div class="col-sm-8">
                                            <select class="form-control" id="frmSrchVendor">
                                                <option value="">Choose</option>
                                                <?php

                                                ?>
                                            </select>
                                            <div class="herr" id="ErrfrmSrchMerchantName"></div>
                                        </div>
                                    </div>

                                    <?php
                                    if($usertype == 3) {
                                        ?>
                                        <!--
                                        <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-4 control-label">Merchant Name</label>
                                        <div class="col-sm-8">
                                            <select class="form-control" id="frmSrchMerchantName">
                                                <option>Choose</option>
                                            </select>
                                            <div class="herr" id="ErrfrmSrchMerchantName"></div>
                                        </div>
                                    </div>
                                    -->
                                        <?php
                                    }
                                    elseif ($usertype == 8) {
                                        ?>
                                        <!--                                        <div class="form-group">

                                                                                </div>-->
                                        <?php
                                    }
                                    else {
                                        ?>
                                        <?php
                                    }
                                    ?>
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-4 control-label">Current Status</label>
                                        <div class="col-sm-8">
                                            <?php
                                            $ArrCStatus = unserialize(ARRSTATUS); unset($ArrCStatus[3]);
                                            ?>
                                            <select class="form-control" id="frmSrchCStatus">
                                                <option value="">Choose</option>
                                                <?php
                                                foreach ($ArrCStatus as $key => $item) {
                                                    ?>
                                                    <option value="<?php echo $key ?>"><?php echo $item?></option>
                                                    <?php
                                                }
                                                ?>
                                            </select>
                                            <div class="herr" id="ErrfrmSrchCStatus"></div>
                                        </div>
                                    </div>
                                </div>
                            </div><!-- /.box-body -->
                            <div class="box-footer">
                                <button type="button" class="btn btn-default"
                                        onclick="refreshPage('frmNameSearchBomPayreq');">Reset
                                </button>
                                <button type="button" class="btn btn-info pull-right" style="color: #000000"
                                        onclick="fnListSearch();">Search
                                </button>
                            </div><!-- /.box-footer -->
                        </form>
                    </div>
                    <div class="box box-info">
                        <div class="box-header with-border">
                            <h3 class="box-title">BOM - NEW STOCK LIST</h3>
                        </div><!-- /.box-header -->
                        <div class="box-body table-responsive no-padding">
                            <span class="atozalphabetscaption">Select Brands by Alphabetss</span>
                            <div class="filterbyalpha">
                                <span class="alpha_all_filter">All</span>
                                <?php for ($i = 65; $i <= 90; $i++) { ?>
                                    <span class="alpha_filter"><?php echo "&#" . $i . ";"; ?></span>
                                <?php } ?>
                            </div>
                            <div class="clearfix"></div>
                            <div class="col-sm-12 pdt10">
                                <div class="col-sm-9 col-xs-12 pdl0">
                                    <div id="DivTotalCntResult" class="pdt10"></div>
                                </div>
                            </div>
                            <div id="DivTotalCntResult" class="pd10"></div>
                            <table id="mBomNewStockList" class="table table-bordered table-hover">
                                <thead>
                                <tr>
                                    <th></th>
                                    <th class="sortable asc" id="0">WIP Ref. No.<i class="fa fa-fw fa-sort"></i></th>
                                    <th class="sortable asc" id="1">Buyer / Brand<i class="fa fa-fw fa-sort"></i></th>
                                    <th class="sortable asc" id="2">P.I. No.<i class="fa fa-fw fa-sort"></i></th>
                                    <th class="sortable asc" id="3">Item Description / (%) Blend / Content / Material<i class="fa fa-fw fa-sort"></i></th>
                                    <th class="sortable asc" id="4">Item Code<i class="fa fa-fw fa-sort"></i></th>
                                    <th class="sortable asc" id="5">Indent Ref. No.<i class="fa fa-fw fa-sort"></i></th>
                                    <th class="sortable asc" id="6">Indent Cutoff Date & Time<i class="fa fa-fw fa-sort"></i></th>
                                    <th class="sortable asc" id="7">Authorization Type<i class="fa fa-fw fa-sort"></i></th>
                                    <!--<th class="sortable asc" id="8">Current Status<i class="fa fa-fw fa-sort"></i></th>
                                    <th class="sortable asc" id="9">Recent Update<i class="fa fa-fw fa-sort"></i></th>
                                    <th class="sortable asc" id="10">Active / Inactive Status<i class="fa fa-fw fa-sort"></i></th>-->
                                </tr>
                                </thead>
                            </table>
                            <div>
                                <section id="pagination_my" class="animated for_animate pdl15 ">
                                    <ul class="pagination m-b-none animated for_animate" id="ResPagination"></ul>
                                </section>
                            </div>
                        </div><!-- /.box-body -->
                    </div><!-- /.box -->
                </div>
            </div>
        </section>
    </div>
<?php $this->load->view(CNFCOMPANY . 'template/templatefooter'); ?>
<div class="control-sidebar-bg"></div>
</div><!-- ./wrapper -->
<script src="<?php echo base_url(); ?>assets/js/ajax.js"></script>
<script src="<?php echo base_url();?>assets/plugins/datepicker/bootstrap-datepicker.js"></script>
<script src="<?php echo base_url();?>assets/js/loadingoverlay.min.js"></script>
<script src="<?php echo base_url();?>assets/js/stores/newbomstocklist.js"></script>
<script>
    var GlbBomreqInvId = '<?php echo $VarBomreqInvId ?>'; var GlbOrderId = '<?php echo $VarOrderId ?>';
    fnList();
    $('#frmSrchCutOffDateFrom').datepicker({
        format: 'dd-mm-yyyy',
        todayHighlight: true,
        autoclose: true
    });
    $('#frmSrchCutOffDateTo').datepicker({
        format: 'dd-mm-yyyy',
        todayHighlight: true,
        autoclose: true
    });

</script>
<?php $this->load->view(CNFCOMPANY . 'template/pagefooter'); ?>