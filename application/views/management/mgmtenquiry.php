<link rel="stylesheet" href="<?php echo base_url();?>assets/plugins/select2/select2.min.css">
<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>assets/plugins/datepicker/datepicker3.css">
<?php $this->load->view(CNFCOMPANY.'template/pageheader');?>
<body class="sidebar-mini skin-black wysihtml5-supported sidebar-collapse">
<div class="wrapper">
    <?php $this->load->view(CNFCOMPANY.'template/templateheader');?>
    <aside class="main-sidebar">
        <?php $this->load->view(CNFCOMPANY.'template/templateleftmenu');?>
    </aside>
    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                MANAGEMENT - ENQUIRY
            </h1>
            <ol class="breadcrumb">
                <li><a href="<?php echo base_url().CNFCOMPANY?>dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
                <li>Manage Enquiry</li>
                <li class="active">Enquiry List(s)</li>
            </ol>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-info">
                        <div class="box-header with-border">
                            <h3 class="box-title">Search</h3>
                            <div class="box-tools pull-right">
                                <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                            </div>
                        </div><!-- /.box-header -->
                        <!-- form start -->
                        <form class="form-horizontal" name="frmNameSearchEn" id="frmNameSearchEn" autocomplete="off">
                            <div class="box-body">
                                <div class="col-xs-12 col-md-6 col-sm-6 col-lg-6">
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-4 control-label">Enquiry Type</label>
                                        <div class="col-sm-8">
                                            <input type="text" name="frmNameSearchEnType" class="form-control" id="frmNameSearchEnType" placeholder="Enter Enquiry Type">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-4 control-label">Style Ref. No. / Name</label>
                                        <div class="col-sm-8">
                                            <input type="text" name="frmNameSearchStyleRef" class="form-control" id="frmNameSearchStyleRef" placeholder="Enter Style Ref. No.">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-4 control-label">Request Date</label>
                                        <div class="col-sm-4">
                                            <input type="text" class="form-control" id="frmNameSearchReqDtFrom" placeholder="From">
                                        </div>
                                        <div class="col-sm-4">
                                            <input type="text" class="form-control" id="frmNameSearchReqDtTo" placeholder="To">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-4 control-label">Request Type IOR No. / ISR No.</label>
                                        <div class="col-sm-8">
                                            <select class="form-control" id="frmNameSearchSR" name="frmNameSearchSR">
                                                <option value="">Select IOR / ISR</option>
                                                <?php
                                                $ArrIsrIor  = unserialize(ARRISRIOR);
                                                foreach($ArrIsrIor as $VarKey=>$Varval) { ?>
                                                    <option value="<?php echo $VarKey?>"><?php echo $Varval?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-md-6 col-sm-6 col-lg-6">
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-4 control-label">Brand / Buyer</label>
                                        <div class="col-sm-8">
                                            <select class="form-control selectbb" multiple="multiple" data-placeholder="Select Brand / Buyer" style="width: 100%;" id="frmNameSearchBB">
                                                <?php
                                                foreach ($ArrBB as $key => $item) {
                                                    ?>
                                                    <option value="<?php echo $item->id ?>">
                                                        <?php echo $item->brandname . ' / ' . $item->buyername ?>
                                                    </option>
                                                    <?php
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-4 control-label">Merchant Name / Code</label>
                                        <div class="col-sm-8">
                                            <select name="frmNameSearchMName" id="frmNameSearchMName" multiple="multiple" class="form-control selectmerchant" data-placeholder="Select Merchant Name">
                                                <?php
                                                foreach ($ArrMerchants as $merchant) {
                                                    ?>
                                                    <option value="<?php echo $merchant->id ?>"><?php echo $merchant->contactname . ' / ' .$merchant->code ?></option>
                                                    <?php
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-4 control-label">Active / Not Active</label>
                                        <div class="col-sm-8">
                                            <select name="frmSrchStatus" id="frmSrchStatus" class="form-control">
                                                <option value="">Select Status</option>
                                                <option value="">Select All</option>
                                                <?php
                                                $ArrStatus  = unserialize(ARRSTATUS);
                                                unset($ArrStatus[3]);
                                                foreach($ArrStatus as $VarKey=>$VarStatus) {?>
                                                    <option value="<?php echo $VarKey?>"><?php echo $VarStatus?></option>
                                                <?php }?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div><!-- /.box-body -->
                            <div class="box-footer">
                                <button type="button" class="btn btn-default" onclick="refreshPage('frmNameSearchEn');">Reset</button>
                                <button type="button" class="btn btn-info pull-right" onclick="fnSearchEn();">Search</button>
                            </div><!-- /.box-footer -->
                        </form>
                    </div><!-- /.box -->
                    <div class="box box-info">
                        <div class="box-header with-border">
                            <!--<h3 class="box-title">Enquiry List</h3>-->
                        </div><!-- /.box-header -->
                        <div class="box-body table-responsive">
                            <label class="control-label">Select brand by Alphabets</label>
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

                                    <div class="col-sm-9 col-xs-9 no-padding">

                                        <select name="frmItemStatus" id="frmItemStatus" class="form-control wd80">

                                            <option value="">Select Status</option>

                                            <option value="1">Activate</option>

                                            <option value="2">Deactivate</option>

                                        </select>

                                        <div class="herr" id="ErrItemStatus"></div>

                                    </div>

                                    <div class="col-sm-3  col-xs-3 pdr0">

                                        <input type="button" name="btnChangeStatus" id="btnChangeStatus" class="btn btn-info pull-right" value="Update">

                                    </div>

                                </div>

                            </div>

                            <table id="enListTbl" class="table table-bordered table-hover">
                                <thead>
                                <!--<thead style="background-color: #cce0ff">-->
                                <tr>
                                    <th></th>
                                    <th class="sortable asc" id="3">Order / Enquiry Ref. No.<i class="fa fa-fw fa-sort"></i></th>
                                    <th class="sortable asc" id="3">Request Date & Time<i class="fa fa-fw fa-sort"></i></th>
                                    <th class="sortable asc" id="5">Brand / Buyer<i class="fa fa-fw fa-sort"></i></th>
                                    <th class="sortable asc" id="2">Request Type<i class="fa fa-fw fa-sort"></i></th>
                                    <th class="sortable asc" id="1">Enquiry Type<i class="fa fa-fw fa-sort"></i></th>
                                    <th class="sortable asc" id="4">Style Ref. No. / Name<i class="fa fa-fw fa-sort"></i></th>
                                    <th class="sortable asc" id="6">Confirmed Price<i class="fa fa-fw fa-sort"></i></th>
                                    <th class="sortable asc" id="7">Currency<i class="fa fa-fw fa-sort"></i></th>
                                    <th class="sortable asc" id="8">Merchant Name / Code<i class="fa fa-fw fa-sort"></i></th>
                                    <th class="sortable asc" id="9">Current Status<i class="fa fa-fw fa-sort"></i></th>
                                    <th class="sortable asc" id="10">Recent Update<i class="fa fa-fw fa-sort"></i></th>
                                    <th class="sortable asc" id="11">Active / Not Active<i class="fa fa-fw fa-sort"></i></th>
                                </tr>
                                </thead>
                            </table>
                            <div>
                                <section id="pagination_my" class="animated for_animate pdl15 "><ul class="pagination m-b-none animated for_animate" id="ResPagination"></ul></section>
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
<script src="<?php echo base_url();?>assets/plugins/datepicker/bootstrap-datepicker.js"></script>
<script src="<?php echo base_url();?>assets/js/commonfunctions.js?r=<?php echo CNFJSCSSRANDNO?>"></script>
<!-- Select2 -->
<script src="<?php echo base_url();?>assets/plugins/select2/select2.full.min.js"></script>
<script src="<?php echo base_url();?>assets/js/mgmtenquiry.js"></script>
<script src="<?php echo base_url();?>assets/js/loadingoverlay.min.js"></script>
<script type="text/javascript">
    fnEnList();
    $(document).ajaxStart(function(a){
        $.LoadingOverlay("show",{image: base_path+"assets/img/fullpage.gif"});
    });

    $(document).ajaxStop(function(){
        $.LoadingOverlay("hide");
    });

</script>
<?php $this->load->view(CNFCOMPANY.'template/pagefooter');?>