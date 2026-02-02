<?php $this->load->view(CNFCOMPANY . 'template/pageheader');
$ArrStatus = unserialize(ARRSTATUS); ?>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.10.23/datatables.min.css"/>
    <!--<body class="sidebar-mini skin-black wysihtml5-supported sidebar-collapse">-->
<body class="layout-top-nav">
<div class="wrapper">
    <?php $this->load->view(CNFCOMPANY . 'template/admintemplateheader');
    // $this->load->view(CNFCOMPANY.'template/templateheader');?>
    <aside class="main-sidebar hide">
        <?php // $this->load->view(CNFCOMPANY.'template/templateleftmenu');?>
    </aside>
    <div class="content-wrapper bgn-white">
        <div class="col-sm-12" style="display: block; padding: 10px 25px;margin-top: 100px;">
            <span class="header-title"><b style="font-size: 20px !important; font-family: Arial">Importer List</b></span>
            
            <a class="btn btn-sm btn-royal-blue add_btn" href="<?php echo base_url() . CNFCOMPANY ?>Magent/importaddedit">
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
                                        <th class="theadstyle">Importer Name</th>
                                        <th class="theadstyle">Email ID</th>
                                        <th class="theadstyle">Mobile No</th>
										<th class="theadstyle">Contact Person</th>										
										<th class="theadstyle">Clearing Agent Category</th>
										<th class="theadstyle">Represent Brand</th>
										<th class="theadstyle">Status</th>
                                      </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>
                                                <input type="text" name="frmSrchagentname" class="form-control" id="frmSrchagentname" placeholder="Free Text">
                                                <input type="hidden" name="hidddenColumnId" id="hidddenColumnId">
                                            </td>
                                            <td>
                                                <input type="text" name="frmSrchemailid" class="form-control" id="frmSrchemailid" placeholder="Free Text">
                                            </td>
                                            <td>
                                                <input type="text" name="frmSrchmobno" class="form-control" id="frmSrchmobno" placeholder="Free Text">
                                            </td>
                                            <td>
                                                <input type="text" name="frmSrchcontperson" class="form-control" id="frmSrchcontperson" placeholder="Free Text">
                                            </td>
											<td>
                                                <select name="frmSrchagent_category" id="frmSrchagent_category" class="form-control">
                                                <option value="">Select</option>
                                                <?php  $ArrStatus = unserialize(ARRVENDORCATEGORY);
                                                foreach($ArrStatus as $VarKey=>$VarStatus) {?>
                                                    <option value="<?php echo $VarKey?>" ><?php echo $VarStatus?></option>
                                                <?php }?>
                                            </select>
                                            </td>
											<td>
                                                <select name="frmSrchbrand" id="frmSrchbrand" class="form-control">
                                                <option value="">Select</option>
                                                    <?php  
                                                    foreach($brand_data as $key=>$value) {?>
                                                        <option value="<?php echo $value->id?>" ><?php echo $value->name?></option>
                                                    <?php }?>
                                                </select>
                                            </td>
											<td>
                                                <select name="frmSrchstatus" id="frmSrchstatus" class="form-control">
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
                                <button class="btn btn-sm btn-royal-blue" id="searchButton"  onclick="fnSearchImporter();">
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
                                    <th id="0">Importer <br> Name</th>
                                    <th id="1">Email ID</th>
                                    <th id="2">Mobile No</th>
                                    <th id="3">Contact Person</th>
									<th id="4">Importer <br> Category</th>
									<th id="5">Represent Brand</th>
									<th id="6">Status</th>
									<th id="7">Updated By</th>
									<th id="8">Recent Update</th>
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
    <?php $this->load->view(CNFCOMPANY.'template/templatefooter');?>
    <div class="control-sidebar-bg"></div>
</div>
<script src="<?php echo base_url(); ?>assets/js/<?php echo CNFCOMPANY ?>importer.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.10.23/datatables.min.js"></script>
<script src="<?php echo base_url();?>assets/js/datatables.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/loadingoverlay.min.js"></script>
<script type="text/javascript">
    fnList();
    $(document).ajaxStart(function (a) {
        $.LoadingOverlay("show", {image: base_path + "assets/img/fullpage.gif"});
    });
    $(document).ajaxStop(function () {
        $.LoadingOverlay("hide");
    });
</script>
<?php $this->load->view(CNFCOMPANY.'template/pagefooter');?>