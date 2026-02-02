<?php $this->load->view(CNFCOMPANY . 'template/pageheader'); ?>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.10.23/datatables.min.css"/>
    <!--<body class="sidebar-mini skin-black wysihtml5-supported sidebar-collapse">-->
<body class="layout-top-nav">
<div class="wrapper">
    <?php $this->load->view(CNFCOMPANY . 'template/badmintemplateheader');
          // $this->load->view(CNFCOMPANY . 'template/templateheader'); ?>
    <aside class="main-sidebar hide">
        <?php // $this->load->view(CNFCOMPANY . 'template/templateleftmenu'); ?>
    </aside>
    <div class="content-wrapper bgn-white">
        <div class="col-sm-12" style="display: block; padding: 10px 25px;margin-top: 100px;">
            <span class="header-title"><b style="font-size: 20px !important; font-family: Arial">Subscriber Enquiry  List</b></span>
            <?php if(isset($checkDraftorNot) && !empty($checkDraftorNot)) { ?>
            <a class="btn btn-sm btn-royal-blue add_btn" href="<?php echo base_url(CNFBADMIN.'msubscriber/addedit/'.urlencode(base64_encode($checkDraftorNot->id)).'/edit'); ?>">
               View Draft
            </a>
             <?php } else { ?>
             <a class="btn btn-sm btn-royal-blue add_btn" href="<?php echo base_url() . CNFBADMIN ?>msubscriber/addedit">
                <i class="fa fa-plus " style="margin-right:10px;font-size: 10px!important"></i>Add New 
            </a>
             <?php } ?>
            <div class="btn-toolbar pull-right " style="padding-top: 4px" role="toolbar" aria-label="Toolbar with button groups">
                <div class="btn-group mr-2v text-right" role="group" aria-label="First group">
                    <i class="fa fa-search-plus btn  btn-royal-blue" style="padding: 8px 12px;font-size: 16px;" onclick="
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
                <div class="btn-group mr-2v text-right" role="group" aria-label="Second group" style="padding-left: 5px;padding-right: 5px" >
                    <button name="btnChangeStatus" id="btnChangeStatus" class="btn btn-sm btn-royal-blue">
                        Update  
                    </button>
                    </div>
                <div class="btn-group mr-2v text-right" role="group" aria-label="Second group" >
                <div style="width: 2px; background: #022B61; height: 200%;color:white;padding-top: 10px;padding-left: 2px;">s</div>
                    </div>
             
            <div class="btn-group mr-2v  text-right" role="group" aria-label="Second group" style="padding-left: 5px" >
                    <button name="btnChangeStatus" id="btn-active" onclick="toggleStatus('active')" class="btn btn-sm btn-royal-blue">
                        Active
                    </button>
                </div>
                <div class="btn-group mr-2v text-right" role="group" aria-label="Second group" style="padding-left: 8px">
                    <button name="btnChangeStatus" id="btn-inactive" onclick="toggleStatus('inactive')"  class="btn btn-sm btn-royal-blue">
                        Inactive
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
                                        <th class="theadstyle">Company Name</th>
                                        <th class="theadstyle">Contact Person</th>
                                        <th class="theadstyle">Mobile No.</th>
                                        <th class="theadstyle">City</th>
                                        <th class="theadstyle">Package Details</th>
                                        <th class="theadstyle">Proforma Invoice Request Status</th>
                                        <th class="theadstyle">Recent Update <br>Date & Time</th>
                                        <th class="theadstyle">Recent Update <br> Date & Time</th>
                                      </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td width="10%">
                                                <input type="text" name="frmSrchCmpny" class="form-control" id="frmSrchCmpny" placeholder="Free Text">
                                                <input type="hidden" name="hidddenColumnId" id="hidddenColumnId">
                                            </td>
                                            <td width="10%">
                                                <input type="text" name="frmSrchCntPerson" class="form-control" id="frmSrchCntPerson" placeholder="Free Text">
                                            </td>
                                            <td width="10%">
                                                <input type="text" name="frmSrchMobno" class="form-control" id="frmSrchMobno" placeholder="Free Text">
                                            </td>
                                            <td width="10%">
                                                <input type="text" name="frmSrchCity" class="form-control" id="frmSrchCity" placeholder="Free Text">
                                            </td>
                                             <td width="10%">
                                               <select name="frmSrchPckgDet" id="frmSrchPckgDet" class="form-control">
                                                <option value="">Select</option>
                                                <?php
                                                foreach($ArrPackage as $k=>$value) {?>
                                                    <option value="<?php echo $value['description']?>" ><?php echo $value['description']?></option>
                                                <?php }?>
                                            </select>
                                            </td>
                                            <td width="10%">
                                                <select name="frmSrchReqStatus" id="frmSrchReqStatus" class="form-control">
                                                <option value="">Select</option>
                                                <?php
                                                foreach($ArrReqStatus as $VarKey=>$VarStatus) {?>
                                                    <option value="<?php echo $VarStatus?>" ><?php echo $VarStatus?></option>
                                                <?php }?>
                                            </select>
                                            </td>
                                            <td width="10%">
                                                <p id="enquirySearch" class="input-group search-div">
                                                <input type="text" name="fromdate" id="RequestFrom" placeholder="RequestFrom" style="width:100%!important" class="date form-control start search-date">
                                                <span class="glyphicon glyphicon-calendar form-control-feedback" style="line-height: 32px ! important"></span>
                                                </p>
                                            </td>
                                           
                                            <td width="10%">
                                                <p id="enquirySearch" class="input-group search-div">
                                                <input type="text" name="RequestTo" id="RequestTo" placeholder="To"  style="width:100%!important" class="date form-control end search-date">
                                                <span class="glyphicon glyphicon-calendar form-control-feedback" style="line-height: 32px ! important"></span>
                                                </p>
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
                                <button class="btn btn-sm btn-royal-blue" id="searchButton"  onclick="fnSearch();">
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
                                    <th id="0">Company Name</th>
                                    <th id="1">Contact Person</th>
                                    <th id="2">E-mail ID</th>
                                    <th id="3">Mobile No.</th>
                                    <th id="4">City</th>
                                    <th id="5">State</th>
                                    <th id="6">Package <br> Details</th>
                                    <th id="7">Proforma Invoice <br> Request Status</th>
                                    <th id="8">Recent Update <br> Date & Time</th>
                                    <th id="9">Status</th>
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
    <?php $this->load->view(CNFCOMPANY . 'template/templatefooter'); ?>
    <div class="control-sidebar-bg"></div>
</div>
<style>
    .btn.active {
    background-color: #022B61!important; /* green for active */
    color: #fff!important;
  }
    </style>

<!--  -->
<script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.10.23/datatables.min.js"></script>
<script src="<?php echo base_url();?>assets/js/datatables.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/loadingoverlay.min.js"></script>
<script src="<?php echo base_url(); ?>assets/plugins/daterangepicker/daterangepicker.js"></script>
<script src="<?php echo base_url(); ?>assets/plugins/datepicker/bootstrap-datepicker.js"></script>
<script src="<?php echo base_url(); ?>assets/js/<?php echo CNFBADMIN ?>subscriber.js"></script>
<script type="text/javascript">
    fnList();
    $(document).ajaxStart(function (a) {
        $.LoadingOverlay("show", {image: base_path + "assets/img/fullpage.gif"});
    });
    $(document).ajaxStop(function () {
        $.LoadingOverlay("hide");
    });
     $('#enquirySearch .date').datepicker({
        // 'format': 'yyyy-mm-dd',
        'format': 'dd-mm-yyyy',
        'todayHighlight':true,
        'autoclose': true,
    }).on('change', function(){
        $("#todate").focus();
    });
</script>
<?php $this->load->view(CNFCOMPANY . 'template/pagefooter'); ?>