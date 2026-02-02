<?php $this->load->view(CNFCOMPANY.'template/pageheader');?>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.10.23/datatables.min.css"/>
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css" />
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <body class="hold-transition layout-top-nav">
<div class="wrapper">
    <?php $this->load->view(CNFCOMPANY . 'template/templateheader'); ?>
    <!-- Left side column. contains the logo and sidebar -->

    <div class="content-wrapper bgn-white">
        <div class="col-sm-12" style="display: block; padding: 10px 25px;margin-top: 100px;">
            <span class="header-title"><b style="font-size: 20px !important; font-family: Arial">Enquiry List - IOR</b></span>
             <?php if($checkDraftorNot > 0) { ?>
            <a class="btn btn-sm btn-royal-blue add_btn" href="<?php echo base_url('merchant/addenquiry/'.urlencode(base64_encode($checkDraftorNot->id))); ?>">
                View Draft
            </a>
            <?php } else { ?>
            <a class="btn btn-sm btn-royal-blue add_btn" href="<?php echo base_url('merchant/addenquiry') ?>">
                <i class="fa fa-plus " style="margin-right:10px;font-size: 10px!important"></i>Add New Enquiry
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
                <!-- <div class=" px-4 w-90" style="margin-left: 3px; width: 99.8%;">
                        <div class="col-sm-12 px-4" style="border-bottom: 1px solid #022B61;"></div>
                </div> -->
                <div class="search_area col-sm-12 px-4 hide">
                    <div class="col-sm-12 text-royal-blue" style="padding: 10px 5px">
                        <!--SEARCH-->
                    </div>
                    <div class="col-sm-12 text-to-black" style="background-color: #f7f7f7;padding: 12px 0 6px 0px;">
                        <form  id="searchForm" method="POST">
                            <div class="col-sm-12 table-responsive">
                                <table class="table tables table-bordered">
                                 <!--<thead style="background-color:#E0E0E0!important;color:#022B61!important;font-size:13px!important">-->
                                     <thead style="background-color:unset!important;font-size:13px!important">
                                      <tr>
                                        <th class="theadstyle">Order / Enquiry Ref. No.</th>
                                        <th class="theadstyle">Brand</th>
                                        <th class="theadstyle">Request Date</th>
                                        <th class="theadstyle">Request Date</th>
                                        <th class="theadstyle">Order / Enq. Type</th>
                                        <th class="theadstyle">No. of Components</th>
                                        <th class="theadstyle">No. of Combo / Colour</th>
                                        
                                      </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>
                                                <input class="input-sm form-control  mt-2" id ="order_enq_ref_no" name="order_enq_ref_no" placeholder="Free Text">
                                            </td>
                                            <td width="15%">
                                                <select class="input-sm form-control  mt-2 js-example-basic-single nrml-slt-inp" id="brandId" name="brandId">
                                                    <option value="">Select</option>
                                                    <?php
                                                       foreach ($brands as $brand) {
                                                         echo "<option value='".$brand->brandname."'>".$brand->brandname."</option>";
                                                       }                                          
                                                    ?>
                                                </select>
                                            </td>
                                            
                                            <td>
                                             <p id="enquirySearch" class="input-group search-div">
                                                <input type="text" style="width:100%!important" placeholder="From" id="RequestFrom" name="RequestFrom" class="date input-sm form-control  mt-2 start search-date" />
                                                <span class="glyphicon glyphicon-calendar form-control-feedback"></span>
                                             </p>
                                            </td>
                                            <td>
                                                <p id="enquirySearch" class="input-group search-div">
                                                <input type="text" style="width:100%!important" placeholder="To" id="RequestTo" name="RequestTo" class="date input-sm form-control  mt-2 end search-date" />
                                                <span class="glyphicon glyphicon-calendar form-control-feedback"></span>
                                                </p>
                                            </td>
                                            <td width="15%">
                                                 <!--<input class="input-sm form-control  mt-2" name="enquirytype" placeholder="Free Text">-->
                                                 <select class="input-sm form-control  mt-2 js-example-basic-single nrml-slt-inp" id ="enquirytype" name="enquirytype">
                                                    <option value="">Select</option>
                                                    <?php
                                                        foreach ($ArrEnqType as $item)
                                                        {
                                                            ?>
                                                            <option value="<?php echo $item ?>"><?php echo $item ?></option>
                                                            <?php
                                                        }
                                                        ?>
                                                </select>
                                            </td>
                                            <td>
                                                <input class="input-sm form-control  mt-2" id="totalcomponents" name="totalcomponents" placeholder="Free Text">
                                            </td>
                                            <td>
                                                <input class="input-sm form-control  mt-2" id="totalcombo" name="totalcombo" placeholder="Free Text">
                                            </td>
                                            
                                <!--            <td>                                <p id="enquirySearch" class="search-div">-->
                                <!--    <input type="text" placeholder="From" id="datecreatedfrom" name="datecreatedfrom" class="date input-sm form-control  mt-2 ml-2 start search-date" />-->
                                <!--    <input type="text" placeholder="To" id="datecreatedto" name="datecreatedto" class="date input-sm form-control  mt-2 end search-date" />-->
                                <!--</p></td>-->
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </form>
                    </div>
                    <div class="col-sm-12 " style="padding:13px 0">
                        <div class="btn-toolbar pull-right py-0" role="toolbar" aria-label="Toolbar with button groups">
                            <div class="btn-group mr-2 px-4 text-right" role="group" aria-label="First group">
                                <button class="btn btn-sm btn-royal-blue" id="searchButton">
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
                            <table id="orderEnquiryListTbl" class="table table-bordered table-hover" data-page-length="50" style="padding: 0 2px; border-bottom: 1px solid #022B61!important;">
                                <thead>
                                <tr>
                                    <th></th>
                                    <th id="1">Order /<br /> Enq. Ref. No</th>
                                    <th id="5">Style Ref. No. /<br /> Name</th>
                                    <th id="2">Brand</th>
                                    <th id="3">Request <br />For</th>
                                    <th id="1">Request <br />Date & Time</th>
                                    <th id="4">Enquiry Type</th>
                                    <th id="7">No. of <br />Components</th>
                                    <th id="6">No. of Combo / <br />Colour</th>
                                    <th id="8">Authorization <br />Status</th>
                                    <th id="9">Authorized<br />Date & Time</th>
                                    <th id="10">Status</th>
                                </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div><!-- /.col -->
            </div>
        </section>
    </div><!-- /.content-wrapper -->
    <?php $this->load->view(CNFCOMPANY.'template/templatefooter');?>
    <div class="control-sidebar-bg"></div>
</div><!-- ./wrapper -->


<script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.10.23/datatables.min.js"></script>

<script src="<?php echo base_url();?>assets/js/datatables.min.js"></script>
<script src="<?php echo base_url();?>assets/js/loadingoverlay.min.js"></script>
<script src="<?php echo base_url();?>assets/custom/enquiry_iorlist.js"></script>
<style>
    .tables > thead > tr > th{
      padding: 0px 12px!important;
    /*padding: 0px 12px!important;*/
    /*text-align: left!important;*/
    }
    .theadstyle{
      font-weight:normal!important;  
    }
    .dataTables_wrapper .dataTables_length select {
        height: 30px;
        padding: 5px 7px;
    }
    table.dataTable {
        margin: 15px auto;
    }
    .add_btn{
        padding: 5px 10px;margin-left: 35px;margin-top: 0;margin-bottom: 4px;
    }
    .input-small > input{
        width: 10px !important;
    }

    .navbar-nav > li > a {
        font-size: 14px;
        font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
    }
    table.dataTable thead th, table.dataTable thead td {
        border-bottom: 0 solid #111;
    }
    .box {
        border-top: 0;
        box-shadow: unset !important;
    }
    .header-title{
        margin-top: 2px;
        margin-bottom: 8px;
        margin-left: 3px;
    }
    
    /*.dropdown-menu {
        background-color: #ebecec;
    }*/
    .dropdown-menu > li > a {
        color: #022B61;
        background-color: #EBECEC;
        border: 1px solid #fff;
        font-size: 12px !important;
    }
    table.dataTable thead th, table.dataTable thead td {
        /*padding: 18px 2px !important;*/
        padding: 12px 2px !important;
        font-size: 13px;
    }
    table.dataTable thead th, table.dataTable tfoot th {
        font-weight: 500 !important;
    }
    table.dataTable tbody th, table.dataTable tbody td {
        padding: 15px 5px
    }
    .table-bordered > thead > tr > th, .table-bordered > tbody > tr > th, .table-bordered > tfoot > tr > th, .table-bordered > thead > tr > td, .table-bordered > tbody > tr > td, .table-bordered > tfoot > tr > td {
        border: 0 solid #f4f4f4;
    }
    .table-bordered {
        border: 0 solid #f4f4f4;
    }
    .dataTables_wrapper .dataTables_filter input:focus {
     border-color: #F59942 !important;
      border-color: #F59942 !important;
}
.boldfont{
    font-weight:600!important;
    font-size:14px!important;
    font-family:'Source Sans Pro', sans-serif!important;
    
}
.form-control-feedback{
    top: 4px!important;
}
.btn-green {
    color: #fff!important;
    background-color: #29916c!important;
    border-color: #29916c!important;
    font-size:15px!important;;
}
.btn-red {
    color: #fff!important;
    background-color: #eb4343!important;
    border-color: #eb4343!important;
    font-size:15px!important;
}
.swal2-title {
    font-size: 21px!important;
}

.btn.active {
    background-color: #022B61!important; /* green for active */
    color: #fff!important;
  }
</style>
<?php $this->load->view(CNFCOMPANY.'template/pagefooter');?>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/custom/datepair.js"></script>
<script src="<?= base_url() ?>assets/ace/node_modules/sweetalert2/dist/sweetalert2.all.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
      $('.date').datepicker({
    format: 'dd-mm-yyyy',
    autoclose: true
});

    $('.js-example-basic-single').select2({ placeholder: "Select" });
    $('.js-example-basic-single-no-search').select2({ 
        placeholder: "Select", 
        minimumResultsForSearch: -1, 
        containerCssClass: "custom-container" 
    });

    $('b[role="presentation"]').hide();
    $('.select2-selection__arrow').append('<span class="arrow-select2-ji nrml-arw-slt"><span>');
</script>
