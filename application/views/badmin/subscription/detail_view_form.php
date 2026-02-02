<?php $this->load->view(CNFCOMPANY . 'template/pageheader'); ?>
<style>
    .form-control-feedback{
    top: 4px!important;
}
.f-16{
    font-size:16px!important;
}
</style>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.10.23/datatables.min.css"/>
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css" />
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
            <span class="header-title"><b style="font-size: 20px !important; font-family: Arial">Subscriber Ref.No: <span class="header-titles"></span></b></span>
            <div class="pull-right">
            <button class="proformainv-btn btn custbtn btn-sm  btn-royal-blue btn-text-slide-x" id="raiseproformabtn">Raise Pro. Inv.</button>
            <a href="<?php echo base_url(CNFBADMIN . 'msubscription/manage') ?>"  class="btn custbtn btn-sm mx-3 btn-royal-blue btn-text-slide-x">Back</a>
            </div>    
            <div class="btn-toolbar pull-right hide" style="padding-top: 4px" role="toolbar" aria-label="Toolbar with button groups">
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
        <section class="content-header p-0 ">
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
                                        <th class="theadstyle">Subscriber Name</th>
                                        <th class="theadstyle">City</th>
                                        <th class="theadstyle">Registered e-mail ID</th>
                                        <th class="theadstyle">Registered Mobile No.</th>
                                        <th class="theadstyle">Subscription End Date</th>
                                        <th class="theadstyle">Subscription End Date</th>
                                      </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td width="10%">
                                                <input type="text" name="frmSrchCmpny" class="form-control" id="frmSrchCmpny" placeholder="Free Text">
                                                <input type="hidden" name="hidddenColumnId" id="hidddenColumnId">
                                            </td>
                                             <td width="10%">
                                               <input type="text" name="frmSrchCity" class="form-control" id="frmSrchCity" placeholder="Free Text">
                                            </td>
                                            <td width="10%">
                                             <input type="text" name="frmSrchEmailid" class="form-control" id="frmSrchEmailid" placeholder="Free Text">
                                            </td>
                                            <td width="10%">
                                                <input type="text" name="frmSrchMobno" class="form-control" id="frmSrchMobno" placeholder="Free Text">
                                            </td>
                                             
                                            <td width="10%">
                                                <p id="enquirySearch" class="input-group search-div">
                                                <input type="text" name="fromdate" id="fromdate" placeholder="From" style="width:100%!important" class="date form-control start search-date">
                                                <span class="glyphicon glyphicon-calendar form-control-feedback"></span>
                                                </p>
                                            </td>
                                           
                                            <td width="10%">
                                                <p id="enquirySearch" class="input-group search-div">
                                                <input type="text" name="todate" id="todate" placeholder="To"  style="width:100%!important" class="date form-control end search-date">
                                                <span class="glyphicon glyphicon-calendar form-control-feedback"></span>
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
                   <div class="mt-3 mb-2 pl-0 mr-4 text-royal-blue f-16">Payment & Invoice Details
                   </div>
                    <div class="box box-info">
                        <div class="box-body no-padding">
                            <table id="tableId" class="table table-bordered table-hover" data-page-length="50" style="padding: 0 2px; border-bottom: 1px solid #022B61!important;">
                                <thead>
                                <tr>
                                    <th  class="no-sort"></th>
                                    <th id="0">Pro. Invoice No.</th>
                                    <th id="1">Pro. Invoice Date</th>
                                    <th id="2">Pro. Invoice <br> Value (Rs)</th>
                                    <th id="3">Supplementary <br>Pro. Invoice No.</th>
                                    <th id="4">Mode of<br>Payment</th>
                                    <th id="5">Transaction ID / <br>Cheque No.</th>
                                    <th id="6">Transaction / <br>Cheque Date</th>
                                    <th id="7">Transaction<br>Value (Rs)</th>
                                    <th id="8">Invoice No.</th>
                                    <th id="9">Invoice Date</th>
                                    <th id="10">Invoice<br>Value (Rs)</th>
                                    <th id="11">Supplementary<br>Invoice No.</th>
                                    <th id="12">Invoice<br>Status</th>
                                    <!--<th id="13">Raise<br>Invoice</th>-->
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
<script src="<?php echo base_url(); ?>assets/js/<?php echo CNFBADMIN ?>subscriptiondet.js"></script>
<!--  -->
<script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.10.23/datatables.min.js"></script>
<script src="<?php echo base_url();?>assets/js/datatables.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/loadingoverlay.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
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