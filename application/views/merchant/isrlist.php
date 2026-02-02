<?php $this->load->view(CNFCOMPANY.'template/pageheader');?>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.10.23/datatables.min.css"/>
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css" />
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
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

    </style>
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
    <body class="hold-transition layout-top-nav">
<div class="wrapper">
    <?php $this->load->view(CNFCOMPANY . 'template/templateheader'); ?>

    <div class="content-wrapper bgn-white">
        <div class="col-sm-12" style="display: block ; padding: 10px 25px;padding-top: 110px;">
            <span class="header-title"><b style="font-size: 20px !important; font-family: Arial">WORK IN PROCESS LIST - ISR</b></span>
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
                        <form  id="searchForm" method="POST">
                            <div class="col-sm-12 table-responsive">
                                <table class="table tables table-bordered">
                                 <!--<thead style="background-color:#E0E0E0!important;color:#022B61!important;font-size:13px!important">-->
                                     <thead style="background-color:unset!important;font-size:13px!important">
                                      <tr>
                                        <th width="10%" class="theadstyle">WIP Ref. No.</th>
                                        <th  width="11%"class="theadstyle">Style Ref. No. / Name</th>
                                        <th  width="11%" class="theadstyle">Brand</th>
                                        <th  width="11%" class="theadstyle">P.O. / Samp. Ref. No.</th>
                                        <th  width="11%" class="theadstyle">P.O. / Samp. Qty.</th>
                                          <th  width="8%" class="theadstyle">Pcs. / Set</th>
                                        <th  width="10%" class="theadstyle">Ship. / Submi. Date</th>
                                        <th  width="10%" class="theadstyle">Ship. / Submi. Date</th>
                                        
                                      </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>
                                                <input class="input-sm form-control  mt-2" id ="wip_ref_no" name="wip_ref_no" placeholder="Free Text">
                                            </td>
											<td>
                                                <input class="input-sm form-control  mt-2" id ="style_ref_no" name="style_ref_no" placeholder="Free Text">
                                            </td>
                                            <td width="11%">
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
                                                <input class="input-sm form-control  mt-2" id="po_sam_ref_no" name="po_sam_ref_no" placeholder="Free Text">
                                            </td>
											<td>
                                                <input class="input-sm form-control  mt-2" id ="po_sam_qty" name="po_sam_qty" placeholder="Free Text">
                                            </td>
                                            <td>
                                                <input class="input-sm form-control  mt-2" id ="pcs_set" name="pcs_set" placeholder="Free Text">
                                            </td>
                                           <td>
                                             <p id="RequestSearch" class="input-group search-div">
                                                <input type="text" style="width:100%!important" placeholder="From" id="RequestFrom" name="RequestFrom" class="date input-sm form-control  mt-2 start search-date" />
                                                <span class="glyphicon glyphicon-calendar form-control-feedback"></span>
                                             </p>
                                            </td>
                                            <td>
                                                <p id="RequestSearch" class="input-group search-div">
                                                <input type="text" style="width:100%!important" placeholder="To" id="RequestTo" name="RequestTo" class="date input-sm form-control  mt-2 end search-date" />
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
                <div class=" px-4 w-90" style="margin-left: 3px; width: 99.8%;">
                    <div class="col-sm-12 px-4" style="border-bottom: 0 solid #022B61;"></div>
                </div>
                <?php
                $ArrUserLoggedInfo = fnGetUserLoggedInfo('1');
                $ArrUt             = unserialize(ARRUSERTYPE);
                ?>
                <div class="col-md-12">
                    <div class="box box-info">
                        <div class="box-body no-padding">
                            <!--<table id="workInProgressTbl" class="table table-bordered table-hover" style="width:1410px; background-color: #fdc6d0 !important">-->
                            <table id="workInProgressTbl" class="table table-bordered table-hover" data-page-length="50" style="padding: 0 2px">
                                <thead>
                                <tr>
                                    <!-- <th style=""></th>
                                    <th style="">WIP Ref. No.</th>
                                    <th style="">Date</th>
                                    <th style="">Brand</th>
                                    <th style="">Style Ref. No. /<br /> Name</th>
                                    <th style="">Order / Enq. Ref. No.</th>
                                    <th style="">P.O. / Sample Ref. No.</th>
                                    <th style="">P.O. / Sam. Qty.</th>
                                    <th style="">Pcs. / Set</th>
                                    <th style="">Ship. / Subn. Date</th>
                                    <th style="">Current <br />Status</th>
                                    <th style="">Recent <br />Update </th>
                                    <th style="">Status</th> -->
                                    
                                    <th style=""></th>
                                    <th style="">WIP Ref. No.</th>
                                    <th style="">Date</th>
                                    <th style="">Order /<br> Enq. Ref. No.</th>
                                    <th style="">Style Ref. No. /<br> Name</th>
                                    <th style="">Brand</th>
                                    <th style="">P.O. /<br> Sample Ref. No.</th>
                                    <th style="">P.O. /<br> Sample Qty.</th>
                                    <th style="">Pcs. /<br> Set</th>
                                    <th style="">Shipment /<br/>Submission Date</th>
                                    <th style="">Current <br />Status</th>
                                    <th style="">Recent <br />Update </th>
                                    <th style="">Status</th>




                                    <!--<th style="width: 1px"></th>
                                    <th style="width: 100px">WIP Ref. No.</th>
                                    <th style="width: 60px">Date</th>
                                    <th style="width: 60px">Brand / Buyer</th>
                                    <th style="width: 80px">Style Ref. No. /<br /> Name</th>
                                    <th style="width: 80px">Order / Enq. Ref. No.</th>
                                    <th style="width: 125px">P.O. / Sample Ref. No.</th>
                                    <th style="width: 60px">P.O. / Sam. Qty.</th>
                                    <th style="width: 40px">Pcs. / Set</th>
                                    <th style="width: 70px">Ship. / Subn. Date</th>
                                    <?php
                                    /*                                    if ($ArrUserLoggedInfo['usertype'] == '3') {
                                                                            echo '<th class=" " id="9">Merchant Name / Code </th>';
                                                                        }
                                                                        */?>
                                    <th style="width: 80px">Current <br />Status</th>
                                    <th style="width: 70px">Recent <br />Update </th>
                                    <th style="width: 70px">Status</th>-->
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
<script src="<?php echo base_url();?>assets/js/datatables.min.js"></script>
<script type="text/javascript">
    /*$("tbody").find("tr").each(function() { //get all rows in table
        var tot = 0;
        var qty = $(this).find('td.poqty').text();
        alert(qty);
        var sum = qty + tot;
        console.log(sum);
    });*/
    var url = window.location;
    // Will only work if string in href matches with location
    $('ul.navbar-nav a[href="' + url + '"]').parent().addClass('active');
    // Will also work for relative and absolute hrefs
    $('ul.navbar-nav a').filter(function () {
        return this.href == url;
    }).parent().addClass('active');
    /!*menu handler*!/;
    $(function () {
        var url = window.location.pathname;
        //console.log(url,'url');
        var activePage = url.substring(url.lastIndexOf('/') + 1);
        //console.log(activePage, 'activePage');
        $('li.treeview a').each(function () {
            var currentPage = this.href.substring(this.href.lastIndexOf('/') + 1);
            //console.log(currentPage, 'currentPage9999');
            if (activePage == currentPage) {
                //console.log($(this).parent(), 'parent');
                $(this).parent().addClass('active');
            }
        });
    });

</script>
<?php $this->load->view(CNFCOMPANY . 'template/pagefooter'); ?>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
<script src="<?php echo base_url();?>assets/js/loadingoverlay.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/custom/isrlist.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/custom/datepair.js"></script>
<script src="<?= base_url() ?>assets/ace/node_modules/sweetalert2/dist/sweetalert2.all.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/select2.min.js"></script>
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