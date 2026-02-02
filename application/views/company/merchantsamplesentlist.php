<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/plugins/datepicker/datepicker3.css">
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
                Merchant Dept. - SAMPLE REQUEST SENT LIST
                <!--<a class="btn btn-default addBtnDetails" href="<?php /*echo base_url().CNFCOMPANY*/?>mcadrequest/addedit"><i class="fa fa-plus" style="margin-right: 10px"></i> ADD CAD REQUEST</a>-->
            </h1>
            <ol class="breadcrumb">
                <li><a href="<?php echo base_url().CNFCOMPANY?>dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
                <li>MERCHANT CAD REQUEST</li>
                <li class="active">Merchant Dept. - SAMPLE REQUEST SENT LIST</li>
            </ol>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-info" style="border-top-color: #4747d1">
                        <div class="box-header with-border">
                            <h3 class="box-title">Search</h3>
                            <div class="box-tools pull-right">
                                <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                            </div>
                        </div>
                        <?php
                        $ArrStatus  = unserialize(ARRSTATUS);
                        unset($ArrStatus[3]);
                        ?>
                        <form class="form-horizontal" name="frmNameSearchCadReq" id="frmNameSearchCadReq">
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
                                        <label for="inputEmail3" class="col-sm-4 control-label">Buyer / Brand</label>
                                        <div class="col-sm-8">
                                            <select class="form-control" id="frmSrchBB">
                                                <option value="">Choose</option>
                                                <?php
                                                foreach ($ArrBB as $key => $item) {
                                                    ?>
                                                    <option value="<?php echo $item->buyername .'/'. $item->brandname?>"><?php echo $item->buyername .'/'. $item->brandname?></option>
                                                    <?php
                                                }
                                                ?>
                                            </select>
                                            <div class="herr" id="ErrfrmSrchBB"></div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-4 control-label">Cut off Date</label>
                                        <div class="col-sm-4">
                                            <!--<input type="text" name="" class="form-control" id="" placeholder="From">-->
                                            <div class='input-group date' id='datetimepicker3'>
                                                <input type='text' class="form-control" id="frmSrchCutOffDateFrom" value="<?php if(isset($ArrBasicInfo->cutoffdatetime)) echo date('d-m-Y H:i:s',strtotime($ArrBasicInfo->cutoffdatetime)) ?>" />
                                                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <!--<input type="text" name="" class="form-control" id="" placeholder="To">-->
                                            <div class='input-group date' id='datetimepicker4'>
                                                <input type='text' class="form-control" id="frmSrchCutOffDateTo" value="<?php if(isset($ArrBasicInfo->cutoffdatetime)) echo date('d-m-Y H:i:s',strtotime($ArrBasicInfo->cutoffdatetime)) ?>" />
                                                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-4 control-label">Request Date</label>
                                        <div class="col-sm-4">
                                            <div class='input-group date' id='datetimepicker1'>
                                                <input type='text' class="form-control" id="frmSrchReqDateFrom" value="<?php if(isset($ArrBasicInfo->cutoffdatetime)) echo date('d-m-Y H:i:s',strtotime($ArrBasicInfo->cutoffdatetime)) ?>" />
                                                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                            </div>
                                            <!--<input type="text" name="" class="form-control" id="frmSrchReqDateFrom" placeholder="From">-->
                                        </div>
                                        <div class="col-sm-4">
                                            <!--<input type="text" name="" class="form-control" id="frmSrchReqDateTo" placeholder="To">-->
                                            <div class='input-group date' id='datetimepicker2'>
                                                <input type='text' class="form-control" id="frmSrchReqDateTo" value="<?php if(isset($ArrBasicInfo->cutoffdatetime)) echo date('d-m-Y H:i:s',strtotime($ArrBasicInfo->cutoffdatetime)) ?>" />
                                                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-md-6 col-sm-6 col-lg-6">
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-4 control-label">Requirement</label>
                                        <div class="col-sm-8">
                                            <input type="text" name="" class="form-control" id="frmSrchRequirement" placeholder="Requirement">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-4 control-label">Request Type</label>
                                        <div class="col-sm-8">
                                            <select class="form-control" id="frmSrchReqType">
                                                <option value="">Choose</option>
                                                <?php
                                                foreach ($ArrReqType as $key => $item) {
                                                    ?>
                                                    <option value="<?php echo $key?>"><?php echo $item?></option>
                                                    <?php
                                                }
                                                ?>
                                            </select>
                                            <div class="herr" id="ErrfrmSrchReqType"></div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-4 control-label">Merchant Name</label>
                                        <div class="col-sm-8">
                                            <select class="form-control" id="frmSrchMerchantName">
                                                <option value="">Choose</option>
                                                <?php
                                                foreach ($ArrMerchant as $key => $item) {
                                                    ?>
                                                    <option value="<?php echo $item->code?>"><?php echo $item->code?></option>
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
                                            $ArrCStatus = unserialize(ORDERENQUIRYSTATUS); unset($ArrCStatus[4]);
                                            ?>
                                            <select class="form-control" id="frmSrchCStatus">
                                                <option value="">Choose</option>
                                                <?php
                                                foreach ($ArrCStatus as $key => $item) {
                                                    ?>
                                                    <option value="<?php echo $key?>"><?php echo $item?></option>
                                                    <?php
                                                }
                                                ?>
                                            </select>
                                            <div class="herr" id="ErrfrmSrchCStatus"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="box-footer">
                                <button type="button" class="btn btn-default"
                                        onclick="refreshPage('frmNameSearchCadReq');">Reset
                                </button>
                                <button type="button" class="btn btn-info pull-right" style="color: #000000" onclick="fnSearchCadReq();">Search</button>
                            </div>
                        </form>
                    </div>
                    <div class="box" style="border-top-color: #4747d1">
                        <div class="box-header with-border">
                            <h3 class="box-title">List</h3>
                        </div>
                        <div class="box-body table-responsive no-padding">


                            <div class="clearfix"></div>

                            <div class="col-sm-12 pdt10">

                                <div class="col-sm-9 col-xs-12 pdl0">

                                    <div id="DivTotalCntResult" class="pdt10"></div>

                                </div>

                                <div class="col-sm-3 col-xs-12 pull-right mpull-left no-padding">
                                    <div id="ResResult"></div>

                                    <div class="col-sm-9 col-xs-9 no-padding">
                                        <select name="frmItemStatus" id="frmItemStatus" class="form-control ">

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

                            <table id="mCadRequestList" class="table table-bordered table-hover"><thead><tr>
                                    <th></th>
                                    <th class="sortable asc" id="0">WIP Ref. No.<i class="fa fa-fw fa-sort"></i></th>
                                    <th class="sortable asc" id="1">Buyer / Brand<i class="fa fa-fw fa-sort"></i></th>
                                    <th class="sortable asc" id="3">Requirement<i class="fa fa-fw fa-sort"></i></th>
                                    <th class="sortable asc" id="3">Request Type<i class="fa fa-fw fa-sort"></i></th>
                                    <th class="sortable asc" id="3">Request Date & Time<i class="fa fa-fw fa-sort"></i></th>
                                    <th class="sortable asc" id="7">Cutoff Date & Time<i class="fa fa-fw fa-sort"></i></th>
                                    <th class="sortable asc" id="8">Merchant Name / Code<i class="fa fa-fw fa-sort"></i></th>
                                    <th class="sortable asc" id="8">Approval Type<i class="fa fa-fw fa-sort"></i></th>
                                    <th class="sortable asc" id="8">Approved By<i class="fa fa-fw fa-sort"></i></th>
                                    <th class="sortable asc" id="10">Current Status<i class="fa fa-fw fa-sort"></i></th>
                                    <th class="sortable asc" id="11">Recent Update<i class="fa fa-fw fa-sort"></i></th>
                                    <th class="sortable asc" id="11">Active Inactive Status<i class="fa fa-fw fa-sort"></i></th>
                            </table>
                            <div>
                                <section id="pagination_my" class="animated for_animate pdl15 ">
                                    <ul class="pagination m-b-none animated for_animate" id="ResPagination"></ul>
                                </section>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <?php $this->load->view(CNFCOMPANY.'template/templatefooter');?>
    <div class="control-sidebar-bg"></div>
</div>
<script src="<?php echo base_url();?>assets/js/moment.min.js"></script>
<script src="<?php echo base_url();?>assets/js/bootstrap-datetimepicker.js"></script>
<!--<script src="<?php /*echo base_url();*/?>assets/plugins/daterangepicker/daterangepicker.js"></script>-->
<script src="<?php echo base_url();?>assets/js/commonfunctions.js?r=<?php echo CNFJSCSSRANDNO?>"></script>
<script src="<?php echo base_url();?>assets/plugins/datepicker/bootstrap-datepicker.js"></script>
<script src="<?php echo base_url();?>assets/js/<?php echo CNFCOMPANY?>merchantsamplesentlist.js"></script>
<script src="<?php echo base_url();?>assets/js/loadingoverlay.min.js"></script>
<script>
    fnListMerchantSampleSentList();
    $(document).ajaxStart(function(a){
        $.LoadingOverlay("show",{image: base_path+"assets/img/fullpage.gif"});
    });

    $(document).ajaxStop(function(){
        $.LoadingOverlay("hide");
    });

</script>

<?php $this->load->view(CNFCOMPANY.'template/pagefooter');?>