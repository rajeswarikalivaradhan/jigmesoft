<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/plugins/datepicker/datepicker3.css">
<?php $this->load->view(CNFCOMPANY . 'template/pageheader'); ?>
<body class="sidebar-mini skin-black wysihtml5-supported sidebar-collapse">
<div class="wrapper">
    <?php $this->load->view(CNFCOMPANY . 'template/templateheader'); ?>
    <aside class="main-sidebar">
        <?php $this->load->view(CNFCOMPANY . 'template/templateleftmenu'); ?>
    </aside>
    <div class="content-wrapper" style="background-color: #bffff9">
        <section class="content-header">
            <h1>
                Search
                <!--<a class="btn btn-default addBtnDetails" href="<?php /*echo base_url().CNFCOMPANY*/ ?>mSAMPLErequest/addedit"><i class="fa fa-plus" style="margin-right: 10px"></i> ADD SAMPLE REQUEST</a>-->
            </h1>
            <ol class="breadcrumb">
                <li><a href="<?php echo base_url() . CNFCOMPANY ?>dashboard"><i class="fa fa-dashboard"></i> Home</a>
                </li>
                <li><?php echo $VarUserType ?> Dept. - <?php echo $VarUserType ?> REQUEST RECEIVED LIST</li>
                <li class="active"><?php echo $VarUserType ?> Dept. - <?php echo $VarUserType ?> REQUEST RECEIVED LIST</li>
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
                        <?php $ArrStatus = unserialize(ARRSTATUS);
                        unset($ArrStatus[3]); ?>
                        <form class="form-horizontal" name="frmNameSearchCadReq" id="frmNameSearchCadReq">
                            <div class="box-body">
                                <div class="col-xs-12 col-md-6 col-sm-6 col-lg-6">
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-4 control-label">WIP Ref. No.</label>
                                        <div class="col-sm-8">
                                            <input type="text" name="" class="form-control" id="frmSrchWipRefNo"
                                                   placeholder="WIP Ref. No.">
                                            <input type="hidden" name="hidddenColumnId" id="hidddenColumnId">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-4 control-label">IOR / ISR</label>
                                        <div class="col-sm-8">
                                            <select name="frmNameSearchIsrIorType" id="frmNameSearchIsrIorType"
                                                    class="form-control">
                                                <option value="">Select IOR / ISR</option>
                                                <?php
                                                $ArrIsrIor = unserialize(ARRISRIOR);
                                                foreach ($ArrIsrIor as $VarKey => $Varval) { ?>
                                                    <option value="<?php echo $VarKey ?>"><?php echo $Varval ?></option>
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
                                                    <option value="<?php echo $item->buyername . '/' . $item->brandname ?>"><?php echo $item->buyername . '/' . $item->brandname ?></option>
                                                    <?php
                                                }
                                                ?>
                                            </select>
                                            <div class="herr" id="ErrfrmSrchBB"></div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-4 control-label">Requirement</label>
                                        <div class="col-sm-8">
                                            <select class="form-control" id="frmSrchRequirement">
                                                <option value="">Choose</option>
                                                <?php
                                                foreach ($ArrRequirements as $key => $item) {
                                                    ?>
                                                    <option value="<?php echo $item['id'] ?>"><?php echo $item['requirement'] ?></option>
                                                    <?php
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-md-6 col-sm-6 col-lg-6">
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-4 control-label">Cut off Date</label>
                                        <div class="col-sm-4">
                                            <div class='input-group date' id=''>
                                                <input type='text' class="form-control" placeholder="From"
                                                       id="frmSrchCutOffDateFrom">
                                                <span class="input-group-addon"><span
                                                            class="glyphicon glyphicon-calendar"></span></span>
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class='input-group date' id=''>
                                                <input type='text' class="form-control" placeholder="To"
                                                       id="frmSrchCutOffDateTo">
                                                <span class="input-group-addon"><span
                                                            class="glyphicon glyphicon-calendar"></span></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-4 control-label">Approval Type</label>
                                        <div class="col-sm-8">
                                            <select class="form-control" id="frmSrchApprovalType">
                                                <option value="">Choose</option>
                                                <?php
                                                foreach ($ArrReqType as $key => $item) {
                                                    ?>
                                                    <option value="<?php echo $key ?>"><?php echo $item ?></option>
                                                    <?php
                                                }
                                                ?>
                                            </select>
                                            <div class="herr" id="ErrfrmSrchReqType"></div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-4 control-label">Merchant Name /
                                            Code</label>
                                        <div class="col-sm-8">
                                            <?php
                                            //echo '<pre>'; print_r($ArrMerchant); die('');
                                            ?>
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
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-4 control-label">Current Status</label>
                                        <div class="col-sm-8">
                                            <?php
                                            $ArrCStatus = unserialize(ORDERENQUIRYSTATUS);
                                            unset($ArrCStatus[1]);
                                            unset($ArrCStatus[4]);
                                            ?>
                                            <select class="form-control" id="frmSrchCStatus">
                                                <option value="">Choose</option>
                                                <?php
                                                foreach ($ArrCStatus as $key => $item) {
                                                    ?>
                                                    <option value="<?php echo $key ?>"><?php echo $item ?></option>
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
                                        onclick="refreshPage('frmNameSearchCadReq');">Reset
                                </button>
                                <button type="button" class="btn btn-info pull-right" style="color: #000000"
                                        onclick="fnSearchSamReceivedList();">Search
                                </button>
                            </div><!-- /.box-footer -->
                        </form>
                    </div><!-- /.box -->
                    <div class="box" style="border-top-color: #4747d1">
                        <div class="box-header with-border">
                            <h3 class="box-title">REQUEST RECEIVED LIST</h3>
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
                                        <input type="button" name="btnChangeStatus" id="btnChangeStatus"
                                               style="color: #000000" class="btn btn-info pull-right" value="Update">
                                    </div>
                                </div>
                            </div>
                            <div id="DivTotalCntResult" class="pd10"></div>
                            <table id="SamReceivedListTbl" class="table table-bordered table-hover">
                                <thead>
                                <tr>
                                    <th></th>
                                    <th class="sortable asc" id="0">WIP Ref. No.<i class="fa fa-fw fa-sort"></i></th>
                                    <th class="sortable asc" id="1">Brand / Buyer<i class="fa fa-fw fa-sort"></i></th>
                                    <th class="sortable asc" id="2">Requirement<i class="fa fa-fw fa-sort"></i></th>
                                    <th class="sortable asc" id="3">Request Type<i class="fa fa-fw fa-sort"></i></th>
                                    <th class="sortable asc" id="4">Request Date & Time<i class="fa fa-fw fa-sort"></i>
                                    </th>
                                    <th class="sortable asc" id="5">Cutoff Date & Time<i class="fa fa-fw fa-sort"></i>
                                    </th>
                                    <th class="sortable asc" id="6">Approval Type<i class="fa fa-fw fa-sort"></i></th>
                                    <th class="sortable asc" id="7">Approved By<i class="fa fa-fw fa-sort"></i></th>
                                    <th class="sortable asc" id="8">Merchant Name / Code<i class="fa fa-fw fa-sort"></i>
                                    </th>
                                    <th>Current <br />Status</th>
                                    <th class="sortable asc" id="9">Recent Update<i class="fa fa-fw fa-sort"></i></th>
                                    <th class="sortable asc" id="10">Active Inactive Status<i
                                                class="fa fa-fw fa-sort"></i></th>
                            </table>
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
    <?php $this->load->view(CNFCOMPANY . 'template/templatefooter'); ?>
    <div class="control-sidebar-bg"></div>
</div><!-- ./wrapper -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.10.2/moment.min.js"></script>
<script src="<?php echo base_url(); ?>assets/plugins/daterangepicker/daterangepicker.js"></script>
<script src="<?php echo base_url(); ?>assets/js/commonfunctions.js?r=<?php echo CNFJSCSSRANDNO ?>"></script>
<script src="<?php echo base_url(); ?>assets/plugins/datepicker/bootstrap-datepicker.js"></script>
<script src="<?php echo base_url(); ?>assets/js/fabricuser/reqreceivedlist.js"></script>
<script src="<?php echo base_url(); ?>assets/js/loadingoverlay.min.js"></script>
<script>
    fnRecdList();
    $(document).ajaxStart(function (a) {
        $.LoadingOverlay("show", {image: base_path + "assets/img/fullpage.gif"});
    });
    $(document).ajaxStop(function () {
        $.LoadingOverlay("hide");
    });
</script>
<?php $this->load->view(CNFCOMPANY . 'template/pagefooter'); ?>