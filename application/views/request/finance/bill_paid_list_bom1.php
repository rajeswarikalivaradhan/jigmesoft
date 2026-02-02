<?php $this->load->view(CNFCOMPANY . 'template/pageheader'); ?>
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/css/datatables.min.css">
<body class="layout-top-nav">
     <style>
        /* Dropdown Button */
        .dropbtn {
            /*border: none;*/
        }

        /* The container <div> - needed to position the dropdown content */
        .dropdown {
            /*position: relative;
            display: inline-block;*/
        }

        /* Dropdown Content (Hidden by Default) */
        .dropdown-content {
            /*display: none;
            position: relative;
            background-color: #f1f1f1;
            box-shadow: 0px 8px 16px 0px rgba(0, 0, 0, 0.2);
            z-index: 1;*/
        }

        /* Links inside the dropdown */
        .dropdown-content a {
            /*color: black;
            padding: 5px;
            text-decoration: none;
            display: block;*/
        }

        /* Change color of dropdown links on hover */
        .dropdown-content a:hover {
            /*background-color: #ddd;*/
        }

        /* Show the dropdown menu on hover */
        .dropdown:hover .dropdown-content {
            /*display: block;*/

        }
        .tables > thead > tr > th{
          padding: 0px 12px!important;
        /*padding: 0px 12px!important;*/
        /*text-align: left!important;*/
        }
        .theadstyle{
          font-weight:normal!important; 
         
        }
        
        .form-control-feedback{
            top: 4px!important;
        }
        
    table.dataTable {
        margin: 15px auto;
    }
     table.dataTable thead th, table.dataTable thead td {
        border-bottom: 0 solid #111!important;
    }
    table.dataTable thead th, table.dataTable thead td {
        font-size: 13px!important;
    }
    table.dataTable thead th, table.dataTable tfoot th {
        font-weight: 500 !important;
    }
    table.dataTable tbody th, table.dataTable tbody td {
        padding: 15px 5px;
    }
    
    .dataTables_wrapper .dataTables_filter input:focus {
     border-color: #F59942 !important;
      border-color: #F59942 !important;
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
<div class="wrapper">
    <?php $this->load->view(CNFCOMPANY . 'template/templateheader'); ?>
    <div class="content-wrapper bgn-white">
        <div class="col-sm-12" style="display: block ; padding: 10px 25px;padding-top:110px">
            <span class="header-title"><b style="font-size: 20px !important; font-family: Arial">BOM (A1) BILL PAID LIST</b></span>
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
                <div class="btn-group mr-2v text-right" role="group" aria-label="Second group">
                    <button name="btnChangeStatus" id="btnChangeStatus" class="btn btn-sm btn-royal-blue">
                        Update
                    </button>
                </div>
                 <div class="btn-group mr-2v  text-right" role="group" aria-label="Second group" style="padding-left: 5px" >
                    <button name="btnChangeStatus" id="btn-active"  class="btn btn-sm btn-royal-blue">
                        Active
                    </button>
                </div>
                <div class="btn-group mr-2v text-right" role="group" aria-label="Second group" style="padding-left: 8px">
                    <button name="btnChangeStatus" id="btn-inactive"   class="btn btn-sm btn-royal-blue">
                        Inactive
</button>
                </div>
            </div>
        </div>
        <section class="content">
             <div class=" w-90" style=" width: 99.8%;">
                       
                </div>
                <section class="content-header p-0">
            <div class="col-sm-12 p-0" >
                <div class=" px-4 w-90" style="margin-left: 2px; width: 99.8%;">
                        
                </div>
                <div class="search_area col-sm-12 px-5 hide">
                    <div class="col-sm-12 text-royal-blue" style="padding: 10px 5px">
                       
                    </div>
                    <div class="col-sm-12 text-to-black" style="background-color: #f7f7f7;padding: 12px 0 5px 0px;">
                                <form  id="searchForm" method="POST">
                            <div class="col-sm-12 table-responsive">
                                <table class="table tables table-bordered" style="margin-bottom: 0px!important">
                                 <!--<thead style="background-color:#E0E0E0!important;color:#022B61!important;font-size:13px!important">-->
                                     <thead style="background-color:unset!important;font-size:13px!important">
                                      <tr >
                                        <th width="12%" class="theadstyle" style=" padding: 0px 15px!important;">WIP Ref. No.</th>
                                        <th width="12%" class="theadstyle" style=" padding: 0px 15px!important;">Brand</th>
                                         
                                         
                                        <th width="12%" class="theadstyle" style=" padding: 0px 15px!important;">P.I. Ref. No.</th>
                                        <th width="10%" class="theadstyle" style=" padding: 0px 15px!important;" >Vendor Name</th>
                                         <th width="10%" class="theadstyle" style=" padding: 0px 12px!important;">Invoice No.</th>
                                        
                                        <th width="10%" class="theadstyle" style=" padding: 0px 15px!important;">Invoice Date</th>
                                        <th width="10%" class="theadstyle" style=" padding: 0px 15px!important;">Invoice Date</th>
                                        
                                        <th width="10%" class="theadstyle" style=" padding: 0px 12px!important;">Invoice value</th>
                                        
                                        
                                         
                                        
                                      </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>
                                                <input class="input-sm form-control  mt-2" name="wip_ref_no" id="wip_ref_no" placeholder="Free Text">
                                            </td>
											
                                            <td width="12%">
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
                                                <input class="input-sm form-control  mt-2" name="pi_ref_no"  id="pi_ref_no" placeholder="Free Text">
                                            </td>
                                             <td>
                                                <input class="input-sm form-control  mt-2" name="vendor_name"  id="vendor_name" placeholder="Free Text">
                                            </td>
                                             <td>
                                                <input class="input-sm form-control  mt-2" name="Invoice_no"  id="Invoice_no" placeholder="Free Text">
                                            </td>
                                             <td>
                                                <p id="calenderSearch" class="input-group search-div">
                                                <input type="text" style="width:100%!important" placeholder="From" id="RequestFrom" name="RequestFrom" class="date input-sm form-control  mt-2 end search-date" />
                                                <span class="glyphicon glyphicon-calendar form-control-feedback"></span>
                                                </p>
                                            </td>
                                             <td>
                                                <p id="calenderSearch" class="input-group search-div">
                                                <input type="text" style="width:100%!important" placeholder="To" id="RequestTo" name="RequestTo" class="date input-sm form-control  mt-2 end search-date" />
                                                <span class="glyphicon glyphicon-calendar form-control-feedback"></span>
                                                </p>
                                            </td>
                                           
                                              
                                                   
                                             <td>
                                                <input class="input-sm form-control  mt-2" name="Invoice_value"  id="Invoice_value" placeholder="Free Text">
                                            </td>
                                                    
                                                    
                                                  
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
                <div class=" px-4 w-90" style="margin-left: 2px; width: 99.8%;">
                    <div class="col-sm-12 px-4" style="border-bottom: 1px solid #022B61;"></div>
                </div>
            </div>
        </section>
        
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-info">
                        <div class="box-body no-padding">
                            <table id="bomPurchaseReceivedListTbl" class="table table-bordered table-hover" data-page-length="50">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th id="0">WIP Ref. No. </th>
                                        <th id="1">Brand</th>
                                        <th id="2">P.I. Ref. No. </th>
                                        <th id="3">Vendor Name</th>
                                        <th id="4">Invoice No.</th>
                                        <th id="5">Invoice Date</th>
                                        <th id="6">Invoice Value</th>
                                        <th id="7">Currency</th>
                                        <th id="8">Current <br>Status</th>
                                        <th id="9">Recent <br />Update </th>
                                        <th id="10">Status</th>
                                    </tr>
                                </thead>
                            </table>
                        </div><!-- /.box-body -->
                    </div><!-- /.box -->
                </div><!-- /.col -->
            </div>
        </section>
    </div><!-- /.content-wrapper -->
    <?php $this->load->view(CNFCOMPANY . 'template/templatefooter'); ?>
    <div class="control-sidebar-bg"></div>
</div><!-- ./wrapper -->
<script src="<?php echo base_url(); ?>assets/js/loadingoverlay.min.js"></script>
<script src="<?php echo base_url();?>assets/js/datatables.min.js"></script>
<script src="<?php echo base_url();?>assets/custom/request/finance/bill_paid_list_bom1.js"></script>
<script src="<?php echo base_url(); ?>assets/plugins/daterangepicker/daterangepicker.js"></script>
<script src="<?php echo base_url(); ?>assets/plugins/datepicker/bootstrap-datepicker.js"></script>
<script src="<?= base_url() ?>assets/ace/node_modules/sweetalert2/dist/sweetalert2.all.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/select2.min.js"></script>
<script>
    $(document).ajaxStart(function (a) {
        $.LoadingOverlay("show", {image: base_path + "assets/img/fullpage.gif"});
    });
    $(document).ajaxStop(function () {
        $.LoadingOverlay("hide");
    });
</script>
<script>
   

    $('.date').datepicker({
    format: 'dd-mm-yyyy',
    autoclose: true
});
</script>
<?php $this->load->view(CNFCOMPANY . 'template/pagefooter'); ?>