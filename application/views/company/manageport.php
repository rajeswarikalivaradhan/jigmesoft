<?php $this->load->view(CNFCOMPANY . 'template/pageheader'); ?>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.10.23/datatables.min.css"/>
<!--<body class="sidebar-mini skin-black wysihtml5-supported sidebar-collapse">-->
<body class="layout-top-nav">
    
<div class="wrapper">
    <?php $this->load->view(CNFCOMPANY . 'template/admintemplateheader');
    // $this->load->view(CNFCOMPANY . 'template/templateheader'); ?>
    <aside class="main-sidebar hide">
        <?php // $this->load->view(CNFCOMPANY . 'template/templateleftmenu'); ?>
    </aside>
    <div class="content-wrapper bgn-white">
        <div class="col-sm-12" style="display: block; padding: 10px 25px;margin-top: 100px;">
            <span class="header-title"><b style="font-size: 20px !important; font-family: Arial">Port List</b></span>
            
            <a class="btn btn-sm btn-royal-blue add_btn" href="<?php echo base_url() . CNFCOMPANY ?>mport/addedit">
                <i class="fa fa-plus " style="margin-right:10px;font-size: 10px!important"></i>Add New 
            </a>
            <div class="btn-toolbar pull-right " style="padding-top: 4px" role="toolbar" aria-label="Toolbar with button groups">
                <div class="btn-group mr-2v text-right" role="group" aria-label="First group">
                    <i class="fa fa-search-plus btn  btn-royal-blue" style="padding: 6px 12px;font-size: 16px;" onclick="
                            $(this).toggleClass('fa-search-plus fa-search')
                            $('.search_area').toggleClass('hide');"></i>
                </div>
                <div class="btn-group px-3" role="group" aria-label="Third group">
                    <select name="frmItemStatus" title="activate / deactivate" id="frmItemStatus" class="input-sm form-control  js-example-basic-single-no-search nrml-slt-inp-sts">
                        <option value="">Select</option>
                        <option value="1">Active</option>
                        <option value="2">Inactive</option>
                    </select>
                </div>
                <div class="btn-group mr-2v text-right" role="group" aria-label="Second group">
                    <button name="btnChangeStatus" id="btnChangeStatus" class="btn btn-sm btn-royal-blue">
                        Update
                    </button>
                </div>
            </div>
        </div>
        <section class="content-header p-0">
            <div class="col-sm-12 p-0" >
                <div class=" px-4 w-90" style="margin-left: 3px; width: 99.8%;">
                        <!--<div class="col-sm-12 px-4" style="border-bottom: 1px solid #022B61;"></div>-->
                </div>
                <div class="search_area col-sm-12 px-4 hide">
                    <div class="col-sm-12 text-royal-blue" style="padding: 10px 5px">
                        <!--SEARCH-->
                    </div>
                    <div class="col-sm-12 text-to-black" style="background-color: #f7f7f7;padding: 12px 0 6px 0px;">
                        <form  id="searchForm" method="POST" name="frmNameSearch" id="frmNameSearch">
                            <div class="col-sm-12 table-responsive">
                                <table class="table tables table-bordered">
                                 <!--<thead style="background-color:#E0E0E0!important;color:#022B61!important;font-size:13px!important">-->
                                     <thead style="background-color:unset!important;font-size:13px!important">
                                      <tr>
                                        <th class="theadstyle">Port Name</th>
										<th class="theadstyle">City</th>
										<th class="theadstyle">Country</th>
										<th class="theadstyle">Status</th>
                                      </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td width="10%">
                                                <input type="text" name="frmSrchPortName" class="form-control" id="frmSrchPortName" placeholder="Free Text">
                                                <input type="hidden" name="hidddenColumnId" id="hidddenColumnId">
                                            </td>
											<td width="10%">
                                                <input type="text" name="frmSrchPortCity" class="form-control" id="frmSrchPortCity" placeholder="Free Text">
                                            </td>
											<td width="10%">
                                                <select name="frmSrchPortCountry" id="frmSrchPortCountry" class="form-control">
                                                <option value="">Select</option>
													<?php
													$ArrCountryList = unserialize(ARRCOUNTRYLIST);
													foreach ($ArrCountryList as $VarCountryId => $VarCountryName) { ?>
														<option value="<?php echo $VarCountryId ?>"><?php echo $VarCountryName ?></option>
													<?php } ?>
												</select>
                                            </td>
											<td width="10%">
                                                <select name="frmSrchPortStatus" id="frmSrchPortStatus" class="form-control">
                                                <option value="">Select</option>
                                                <?php  $ArrStatus = unserialize(ARRSTATUS);
                                                foreach($ArrStatus as $VarKey=>$VarStatus) {?>
                                                    <option value="<?php echo $VarKey?>" ><?php echo $VarStatus?></option>
                                                <?php }?>
                                            </select>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </form>
                    </div>
                    <div class="col-sm-12 " style="padding:13px 0">
                        <div class="btn-toolbar pull-right py-0" role="toolbar" aria-label="Toolbar with button groups">
                            <div class="btn-group mr-2 px-4 text-right" role="group" aria-label="First group">
                                <button class="btn btn-sm btn-royal-blue" id="searchButton"  onclick="fnSearchPort();">
                                    <i class="fa fa-search"></i> Search
                                </button>
                            </div>
                            <div class="btn-group" role="group" aria-label="Third group">
                                <button class="btn btn-sm btn-royal-blue" id="refreshBtn">
                                    <i class="fa fa-refresh"></i> Refresh
                                </button>
                            </div>

                        </div>
                    </div>
                </div>
                <div class=" px-4 w-90" style="margin-left: 3px; width: 99.8%;">
                    <div class="col-sm-12 px-4" style="border-bottom: 1px solid #022B61;"></div>
                </div>
            </div>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-info">
                        <div class="box-body no-padding">
                            <table id="tableId" class="table table-bordered table-hover" data-page-length="50" style="padding: 0 2px; border-bottom: 1px solid #022B61!important;">
                                <thead>
                                <tr>
                                    <th  class="no-sort"></th>
                                    <th id="0">Port Name</th>
									<th id="1">City</th>
                                    <th id="2">Country</th>
                                    <th id="3">Status</th>
									<th id="4">Updated By</th>
									<th id="5">Recent Update</th>
									<th>Action</th>
                                </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div><!-- /.col -->
            </div>
        </section>
    </div>
    <div class="content-wrapper hide">
        <div class="col-md-12">
            <section class="content-header">
                <h1 class="firstHeading">
                    Port<a class="btn btn-default addBtnDetails"
                           href="<?php echo base_url() . CNFCOMPANY ?>mport/addedit">
                        <i class="fa fa-plus" style="margin-right: 10px"></i>ADD</a>
                </h1>
            </section>
        </div>
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
                        <form class="form-horizontal" name="frmNameSearchPort" id="frmNameSearchPort">
                            <div class="box-body">
                                <div class="col-xs-12 col-md-6 col-sm-6 col-lg-6">
                                    <div class="form-group">
                                        <label for="" class="col-sm-4 control-label">Port</label>
                                        <div class="col-sm-8">
                                            <input type="text" name="frmSrchPortName" class="form-control"
                                                   id="frmSrchPortName" placeholder="Port">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="" class="col-sm-4 control-label">City</label>
                                        <div class="col-sm-8">
                                            <input type="text" name="frmSrchPortCity" class="form-control"
                                                   id="frmSrchPortCity" placeholder="City">
                                            <input type="hidden" name="hidddenColumnId" id="hidddenColumnId">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-md-6 col-sm-6 col-lg-6">
                                    <div class="form-group">
                                        <label for="frmSrchPortCountry" class="col-sm-4 control-label">Country</label>
                                        <div class="col-sm-8">
                                            <select name="frmSrchPortCountry" id="frmSrchPortCountry"
                                                    class="form-control">
                                                <option value="">Select</option>
                                                <?php
                                                $ArrCountryList = unserialize(ARRCOUNTRYLIST);
                                                foreach ($ArrCountryList as $VarCountryId => $VarCountryName) { ?>
                                                    <option
                                                        value="<?php echo $VarCountryId ?>"><?php echo $VarCountryName ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="frmSrchPortStatus" class="col-sm-4 control-label">Status</label>
                                        <div class="col-sm-8">
                                            <select name="frmSrchPortStatus" id="frmSrchPortStatus"
                                                    class="form-control">
                                                <option value="">Select</option>
                                                <?php
                                                $ArrStatus = unserialize(ARRSTATUS);
                                                foreach ($ArrStatus as $VarKey => $VarStatus) { ?>
                                                    <option
                                                        value="<?php echo $VarKey ?>"><?php echo $VarStatus ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="box-footer boxFooter_pd1025">
                                <button type="button" class="btn btn-default"
                                        onclick="refreshPage('frmNameSearchPort');">Reset
                                </button>
                                <button type="button" class="btn btn-info pull-right" onclick="fnSearchPort();">Search
                                </button>
                            </div>
                        </form>
                    </div>
                    <div class="box box-info">
                        <div class="box-header with-border">
                            <h3 class="box-title">List</h3>
                        </div>
                        <div class="box-body table-responsive">
                            <div class="col-sm-12" style="padding: 10px 0 20px 0">
                                <div class="col-sm-9 col-xs-12 no-padding">
                                    <div id="DivTotalCntResult"></div>
                                </div>
                                <div class="col-sm-3 col-xs-12 pull-right no-padding">
                                    <div class="col-sm-8 col-xs-8">
                                        <select name="frmItemStatus" title="change Status" id="frmItemStatus"
                                                class="form-control ">
                                            <option value="">Select</option>
                                            <?php
                                            foreach ($ArrStatus as $VarKey => $VarStatus) { ?>
                                                <option value="<?php echo $VarKey ?>"><?php echo $VarStatus ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="col-sm-4  col-xs-4">
                                        <input type="button" name="btnChangeStatus" id="btnChangeStatus"
                                               class="btn btn-info pull-right" value="Update">
                                    </div>
                                </div>
                            </div>
                            <table id="tableList" class="table table-bordered table-hover">
                                <thead>
                                <tr>
                                    <th></th>
                                    <th class="sortable asc" id="0">Port Name<i class="fa fa-fw fa-sort"></i></th>
                                    <th class="sortable asc" id="1">Port City<i class="fa fa-fw fa-sort"></i></th>
                                    <th class="sortable asc" id="2">Country<i class="fa fa-fw fa-sort"></i></th>
                                    <th class="sortable asc" id="3">Status<i class="fa fa-fw fa-sort"></i></th>
                                    <th class="sortable asc" id="4">Updated By<i class="fa fa-fw fa-sort"></i></th>
                                    <th class="sortable asc" id="5">Recent Update<i class="fa fa-fw fa-sort"></i></th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                            </table>
                            <section id="pagination_my" class="animated for_animate pdl15 ">
                                <ul class="pagination m-b-none animated for_animate" id="ResPagination"></ul>
                            </section>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <?php $this->load->view(CNFCOMPANY . 'template/templatefooter'); ?>
    <div class="control-sidebar-bg"></div>
</div>
<script src="<?php echo base_url(); ?>assets/js/<?php echo CNFCOMPANY ?>mport.js?r=<?php echo CNFJSCSSRANDNO ?>"></script>
<script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.10.23/datatables.min.js"></script>
<script src="<?php echo base_url();?>assets/js/datatables.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/loadingoverlay.min.js"></script>
<script>
    fnListPort();
    $(document).ajaxStart(function (a) {
        $.LoadingOverlay("show", {image: base_path + "assets/img/fullpage.gif"});
    });
    $(document).ajaxStop(function () {
        $.LoadingOverlay("hide");
    });
</script>
<?php $this->load->view(CNFCOMPANY . 'template/pagefooter'); ?>