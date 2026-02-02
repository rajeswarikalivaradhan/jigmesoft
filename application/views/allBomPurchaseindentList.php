<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/plugins/datepicker/datepicker3.css">
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
<?php $this->load->view(CNFCOMPANY.'template/pageheader');?>
    <body class="sidebar-mini skin-black wysihtml5-supported sidebar-collapse">
<div class="wrapper">
    <?php $this->load->view(CNFCOMPANY.'template/templateheader');?>
    <aside class="main-sidebar">
        <?php $this->load->view(CNFCOMPANY.'template/templateleftmenu');?>
    </aside>
    <div class="content-wrapper">
        <section class="content-header">
            <h1>Search
                <!--<a class="btn btn-default addBtnDetails" href="<?php /*echo base_url().CNFCOMPANY*/?>mcadrequest/addedit"><i class="fa fa-plus" style="margin-right: 10px"></i> ADD CAD REQUEST</a>-->
            </h1>
            <ol class="breadcrumb">
                <li><a href="<?php echo base_url().CNFCOMPANY?>dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
                <li></li><li class="active">Purchase Indent LIST</li>
            </ol>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-info" style="border-top-color: #4747d1">
                        <!--<div class="box-header with-border">
                            <h3 class="box-title">Search</h3>
                            <div class="box-tools pull-right">
                                <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                            </div>
                        </div>-->
                        <!-- /.box-header -->
                        <!-- form start -->
                        <?php
                        $ArrStatus  = unserialize(ARRSTATUS);
                        unset($ArrStatus[3]);
                        ?>
                        <form class="form-horizontal" name="frmNameSearchCadReq" id="frmNameSearchCadReq" action="#">
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
                                        <label for="inputEmail3" class="col-sm-4 control-label">Requirement</label>
                                        <div class="col-sm-8">
                                            <?php
//                                            echo '<pre>'; print_r($ArrRequirements); die('');

                                            ?>
                                            <select class="form-control" id="frmSrchRequirement">
                                                <option value="">Choose Requirement</option>
                                                <?php
                                                foreach ($ArrRequirements as $key => $item) {
                                                    ?>
                                                    <option value="<?php echo $key ?>"><?php echo $item ?></option>
                                                    <?php
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>

                                    <!--<div class="form-group">
                                        <label for="inputEmail3" class="col-sm-4 control-label">Authorization Type</label>
                                        <div class="col-sm-8">
                                            <select class="form-control" id="frmSrchapprovaltypeType">
                                                <option value="">Choose</option>
                                                <?php
/*                                                foreach ($ArrReqType as $key => $item) {
                                                    */?>
                                                    <option value="<?php /*echo $key*/?>"><?php /*echo $item*/?></option>
                                                    <?php
/*                                                }
                                                */?>
                                            </select>
                                            <div class="herr" id="ErrfrmSrchapprovaltypeType"></div>
                                        </div>
                                    </div>-->

                                        <div class="form-group <?php if($usertype == 3 || $usertype == 15 || $usertype == 8) echo ''; else echo 'hide' ?>">
                                            <label for="inputEmail3" class="col-sm-4 control-label">Merchant Name</label>
                                            <div class="col-sm-8">
                                                <select class="form-control" id="frmSrchMerchantName">
                                                    <option value="">Choose</option>
                                                    <?php
                                                    foreach ($ArrMerchant as $key => $item) {
                                                        ?>
                                                        <option value="<?php echo $item['id'] ?>"><?php echo $item['contactname'] . ' / ' . $item['code'] ?></option>
                                                        <?php
                                                    }
                                                    ?>
                                                </select>
                                                <div class="herr" id="ErrfrmSrchMerchantName"></div>
                                            </div>
                                        </div>
                                        <div class="form-group <?php if($usertype == 3 || $usertype == 15 || $usertype == 8) echo 'hide'; else echo '' ?>">
                                            <label for="inputEmail3" class="col-sm-4 control-label">Vendor</label>
                                            <div class="col-sm-8">
                                                <select class="form-control" id="frmSrchVendor">
                                                    <option value="">Choose</option>
                                                    <?php
                                                    foreach ($ArrVendors as $key => $item) {
                                                        ?>
                                                        <option value="<?php echo $item->id ?>"><?php echo $item->vendorname ?></option>
                                                        <?php
                                                    }
                                                    ?>
                                                </select>
                                                <div class="herr" id="ErrfrmSrchMerchantName"></div>
                                            </div>
                                        </div>
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-4 control-label">Cut off Date</label>
                                        <div class="col-sm-4">
                                            <input type="text" name="" class="form-control" id="frmSrchCutOffDateFrom" placeholder="From">
                                        </div>
                                        <div class="col-sm-4">
                                            <input type="text" name="" class="form-control" id="frmSrchCutOffDateTo" placeholder="To">
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
                                <button type="button" class="btn btn-default" onclick="refreshPage('frmNameSearchCadReq');">Reset</button>
                                <button type="button" class="btn btn-info pull-right" style="color: #000000" onclick="fnQListSearch();">Search</button>
                            </div><!-- /.box-footer -->
                        </form>
                    </div><!-- /.box -->
                    <div class="box" style="border-top-color: #4747d1">
                        <div class="box-header with-border">
                            <h3 class="box-title">P.I. LIST</h3>
                        </div><!-- /.box-header -->
                        <div class="box-body table-responsive no-padding">
                            <span class="atozalphabetscaption">Select Brands by Alphabetss</span>
                            <div class="filterbyalpha">

                                <span class="alpha_all_filter">All</span>

                                <?php for($i=65;$i<=90;$i++) {?>

                                    <span class="alpha_filter"><?php echo "&#".$i.";";?></span>

                                <?php }?>

                            </div>

                            <div class="clearfix"></div>

                            <div class="col-sm-12 pdt10">

                                <div class="col-sm-9 col-xs-12 pdl0">

                                    <div id="DivTotalCntResult" class="pdt10"></div>

                                </div>

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

                                        <input type="button" name="btnChangeStatus" id="btnChangeStatus" style="color: #000000" class="btn btn-info pull-right" value="Update">

                                    </div>

                                </div>

                            </div>
                            <div id="DivTotalCntResult" class="pd10"></div>
                            <?php
//                            if($usertype == 3) {
                                ?>
                                <table id="mBomPurchaseIndentList" class="table table-bordered table-hover">
                                    <thead>
                                    <tr>
                                        <th></th>
                                        <th class="sortable asc" id="0">WIP Ref. No.<i class="fa fa-fw fa-sort"></i></th>
                                        <th class="sortable asc" id="1">Brand / Buyer<i class="fa fa-fw fa-sort"></i></th>
                                        <th class="sortable asc" id="2">P.I. No.<i class="fa fa-fw fa-sort"></i></th>
                                        <th class="sortable asc" id="3">Requirement<i class="fa fa-fw fa-sort"></i></th>
                                        <th class="sortable asc" id="2">Vendor<i class="fa fa-fw fa-sort"></i></th>
                                        <th class="sortable asc" id="5">Expt. Delivery Date & Time<i class="fa fa-fw fa-sort"></i></th>
                                        <th class="sortable asc" id="5">Cutoff Date & Time<i class="fa fa-fw fa-sort"></i></th>
                                        <th class="sortable asc" id="7">Approved By Name<i class="fa fa-fw fa-sort"></i></th>
                                        <th class="sortable asc" id="6">Merchant Name / Code<i class="fa fa-fw fa-sort"></i></th>
                                        <th class="sortable asc" id="9">Current Status<i class="fa fa-fw fa-sort"></i></th>
                                        <th class="sortable asc" id="10">Recent Update<i class="fa fa-fw fa-sort"></i></th>
                                        <th class="sortable asc" id="11">Active / Inactive Status<i class="fa fa-fw fa-sort"></i></th>
                                    </tr>
                                    </thead>
                                </table>
                                <?php
                            //}
                            //else {
                                ?>
                                <!--<table id="mCadQueueList" class="table table-bordered table-hover">
                                    <thead>
                                    <tr>
                                        <th></th>
                                        <th class="sortable asc" id="0">WIP Ref. No.<i class="fa fa-fw fa-sort"></i></th>
                                        <th class="sortable asc" id="1">Brand / Buyer<i class="fa fa-fw fa-sort"></i></th>
                                        <th class="sortable asc" id="8">QUEUE NO.<i class="fa fa-fw fa-sort"></i></th>
                                        <th class="sortable asc" id="3">Requirement<i class="fa fa-fw fa-sort"></i></th>
                                        <th class="sortable asc" id="2">Request Date & Time<i class="fa fa-fw fa-sort"></i></th>
                                        <th class="sortable asc" id="5">Cutoff Date & Time<i class="fa fa-fw fa-sort"></i></th>
                                        <th class="sortable asc" id="7">Authorization Type<i class="fa fa-fw fa-sort"></i></th>
                                        <th class="sortable asc" id="7">Authorized By Name / Code<i class="fa fa-fw fa-sort"></i></th>
                                        <th class="sortable asc" id="6">Merchant Name / Code<i class="fa fa-fw fa-sort"></i></th>
                                        <th class="sortable asc" id="9">Current Status<i class="fa fa-fw fa-sort"></i></th>
                                        <th class="sortable asc" id="10">Recent Update<i class="fa fa-fw fa-sort"></i></th>
                                        <th class="sortable asc" id="11">Active Inactive Status<i class="fa fa-fw fa-sort"></i></th>
                                    </tr>
                                    </thead>
                                </table>-->
                                <?php
                            //}
                            ?>
                            <div>
                                <section id="pagination_my" class="animated for_animate pdl15 ">
                                    <ul class="pagination m-b-none animated for_animate" id="ResPagination"></ul>
                                </section>
                            </div>
                        </div><!-- /.box-body -->
                    </div><!-- /.box -->
                </div><!-- /.col -->
            </div>
        </section>
    </div><!-- /.content-wrapper -->
    <?php $this->load->view(CNFCOMPANY.'template/templatefooter');?>
    <div class="control-sidebar-bg"></div>
</div><!-- ./wrapper -->
<!--<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.10.2/moment.min.js"></script>-->
<script src="<?php echo base_url();?>assets/js/commonfunctions.js?r=<?php echo CNFJSCSSRANDNO?>"></script>
<script src="<?php echo base_url();?>assets/plugins/datepicker/bootstrap-datepicker.js"></script>
<script src="<?php echo base_url();?>assets/js/loadingoverlay.min.js"></script>
<script src="<?php echo base_url();?>assets/js/allbompurchaseindentlist.js"></script>
<script>
    fnList();
    localStorage.removeItem('forInvoiceGridLs');
    localStorage.removeItem('forSavingIssuePIDynamicTbl');
    var GlbUt = "<?php echo @$usertype ?>";
</script>
<?php $this->load->view(CNFCOMPANY.'template/pagefooter');?>