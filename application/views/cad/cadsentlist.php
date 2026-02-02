<?php $this->load->view(CNFCOMPANY . 'template/pageheader'); ?>
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/plugins/datepicker/datepicker3.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.10.23/datatables.min.css"/>
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
    </style>
<div class="wrapper">
<style>
        /*table.dataTable tbody th, table.dataTable tbody td */
        /*{*/
        /*    padding:7px !important;*/
        /*}*/
        /*table.dataTable thead th, table.dataTable thead td {*/
        /*    padding: 14px 2px !important;*/
        /*    font-size: 13px;*/
        /*}*/
    </style>
    <?php $this->load->view(CNFCOMPANY . 'template/templateheader'); ?>
    <div class="content-wrapper bgn-white">
       
        <div class="col-sm-12" style="display: block ; padding: 10px 25px;padding-top:110px;">
            <span class="header-title"><b style="font-size: 20px !important; font-family: Arial">REQUEST SENT LIST</b></span>
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
                    <button name="btnChangeStatus" id="btnChangeStatus" class="btn btn-sm btn-royal-blue"style="padding-left: 5px;padding-right:5px">
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
                                         
                                        <th width="10%" class="theadstyle" style=" padding: 0px 15px!important;">Requirement </th>
                                        <th width="10%" class="theadstyle" style=" padding: 0px 15px!important;" >Req. Date & Time</th>
                                        <th width="10%" class="theadstyle" style=" padding: 0px 15px!important;">Req. Date & Time</th>
                                        <th width="10%" class="theadstyle" style=" padding: 0px 12px!important;">Cutoff Date & Time</th>
                                         <th width="10%" class="theadstyle"  style=" padding: 0px 12px!important;">Cutoff Date & Time</th>
                                         <th width="12%" class="theadstyle" style=" padding: 0px 15px!important;">Merchant Name</th>
                                         
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
                                                <input class="input-sm form-control  mt-2" name="requirement"  id="requirement" placeholder="Free Text">
                                            </td>
											 <td>
                                                <p id="calenderSearch" class="input-group search-div">
                                                <input type="text" style="width:100%!important" placeholder="From" id="RequestFrom" name="RequestFrom" class="date input-sm form-control  mt-2 end search-date" />
                                                <span class="glyphicon glyphicon-calendar form-control-feedback"></span>
                                                </p>
                                            </td>
                                            <td>
                                              
                                                   
                                             <p id="calenderSearch" class="input-group search-div">
                                                <input type="text" style="width:100%!important" placeholder="To" id="RequestTo" name="RequestTo" class="date input-sm form-control  mt-2 start search-date" />
                                                <span class="glyphicon glyphicon-calendar form-control-feedback"></span>
                                             </p>
                                                    
                                                    
                                                  
                                            </td>
                                            <td>
                                                <p id="calenderSearch2" class="input-group search-div">
                                                <input type="text" style="width:100%!important" placeholder="From" id="CutoffFrom" name="CutoffFrom" class="date input-sm form-control  mt-2 end search-date" />
                                                <span class="glyphicon glyphicon-calendar form-control-feedback"></span>
                                                </p>
                                            </td>
                                             <td>
                                                <p id="calenderSearch2" class="input-group search-div">
                                                <input type="text" style="width:100%!important" placeholder="To" id="CutoffTo" name="CutoffTo" class="date input-sm form-control  mt-2 end search-date" />
                                                <span class="glyphicon glyphicon-calendar form-control-feedback"></span>
                                                </p>
                                            </td>
                                            <td>
                                                <input class="input-sm form-control  mt-2" name="merchantname"  id="merchantname" placeholder="Free Text">
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
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box" style="border-top-color: #4747d1">
                        <div class="box-body no-padding">
                            <table id="mCadQueueList" class="table table-bordered" data-page-length="50">
                                <thead>
                                <tr>
                                    <th></th>
                                    <th id="0">WIP Ref. No. </th>
                                    <th id="1">Brand</th>
                                    <th id="8">Request For </th>
                                    <th id="8">Requirement </th>
                                    <th id="4">Request <br />Date & Time </th>
                                    <th id="5">Cutoff <br />Date & Time </th>
                                    <th id="7">Merchant <br />Name</th>
                                    <th id="7">Authorization <br />Type</th>
                                    <!--<th id="7">Authorized By </th>-->
                                    <th id="7">CAD <br />User Name </th>
                                    <th id="9">Current <br />Status</th>
                                    <th id="10">Recent <br />Update </th>
                                    <th id="11">Status</th>
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
    <style>
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
</div><!-- ./wrapper -->
<!--<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.10.2/moment.min.js"></script>-->
<script src="<?php echo base_url(); ?>assets/js/commonfunctions.js?r=<?php echo CNFJSCSSRANDNO ?>"></script>
<script src="<?php echo base_url(); ?>assets/plugins/datepicker/bootstrap-datepicker.js"></script>
<script src="<?php echo base_url(); ?>assets/js/loadingoverlay.min.js"></script>
<script src="<?php echo base_url();?>assets/js/datatables.min.js"></script>
<script src="<?= base_url() ?>assets/ace/node_modules/sweetalert2/dist/sweetalert2.all.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/select2.min.js"></script>

<script>

let satusval="1";
var reqRequestJSON;
    const activeBtn = document.getElementById('btn-active');
    const inactiveBtn = document.getElementById('btn-inactive');  

    $(document).ready(function() {
          
      $('.date').datepicker({
    format: 'dd-mm-yyyy',
    autoclose: true
});

 if (sessionStorage.getItem('keepSearchOpen') === 'true') {
        $('.search_area').removeClass('hide'); // show search div
        $('.fa-search-plus').removeClass('fa-search-plus').addClass('fa-search');
        sessionStorage.removeItem('keepSearchOpen'); // clear flag
    }
        //toggleStatus('active');

       // var reqRequestJSON;
        $.when(getReqRequestList()).done(function(){
            dispDetails(reqRequestJSON);		
        });

        $(document).ajaxStart(function(a){
            $.LoadingOverlay("show",{image: "../assets/img/fullpage.gif"});
        });
        $(document).ajaxStop(function(){
            $.LoadingOverlay("hide");
        });

        function getReqRequestList()
        {
            return $.ajax({
                url: base_path+'company/mcaduser/getcadSentlist',
                type:'POST',
                success:function(data){
                    reqRequestJSON = $.parseJSON(data);
                },		
                error: function() {
                    console.log("Error");  
                }
            });
        }

        function dispDetails(reqRequestJSON)
        {

        
            if ( $.fn.DataTable.isDataTable('#mCadQueueList') ) {
                $('#mCadQueueList').DataTable().destroy();
            }
            var i = 1;
            $('#mCadQueueList tbody').empty();	
            $("#mCadQueueList").dataTable({
                "aaData": reqRequestJSON,
                "aaSorting": [],
                "aoColumns": [		
                    {
                        "mDataProp": function(data, type, full, meta) {
                            return '<input type="checkbox" class="allcbox" id="'+data.request_id+'">';
                        }
                    },
                    { 
                        "mDataProp": function ( data, type, full, meta) {
                            return '<a class="bold" href="' + base_path +'request/Cadrequest/cadDeptSentDetails/'+ encodeURIComponent(btoa(data.enquiry_id)) + '/reqId/' + encodeURIComponent(btoa(data.request_id)) + '/cadId/' + encodeURIComponent(btoa(data.qa_req_ids)) + ' ">' + data.isriorcode + '</a>';
                        }
                    },
                    { "mDataProp": "brandname" },	
                    { 
                        "mDataProp": function ( data, type, full, meta) {
                            return 'CAD Q.A.';
                        }
                    },
                    { 
                        "mDataProp": function ( data, type, full, meta) {
                            return data.item;
                        }
                    },
                    { 
                        "mDataProp": function ( data, type, full, meta) {
                            // var d = new Date(data.req_date); 
                            // var time = d.toLocaleString('en-US', { hour: '2-digit', minute: '2-digit', hour12: true });
                            // var dFormat = ("0" + (d.getDate())).slice(-2) + '/' + ("0" + (d.getMonth() + 1)).slice(-2) + '/' + d.getFullYear();
                            return data.qa_req_date;
                        }
                    },							
                    { 
                        "mDataProp": function ( data, type, full, meta) {
                            // var d = new Date(data.cutoff_date); 
                            // var time = d.toLocaleString('en-US', { hour: '2-digit', minute: '2-digit', hour12: true });
                            // var dFormat = ("0" + (d.getDate())).slice(-2) + '/' + ("0" + (d.getMonth() + 1)).slice(-2) + '/' + d.getFullYear();
                            return data.qa_cutoff_date;
                        }
                    },
                    { "mDataProp": "merchant_name" },
                    { 
                        "mDataProp": function ( data, type, full, meta) {
                            if(data.auth_type == '')
                            return '-';
                            else
                            return data.auth_type;
                        }
                    },	
                    //{ "mDataProp": "auth_name" },
                    { "mDataProp": "cad_name" },
                    { "mDataProp": "cad_status" },
                    // { 
                    //     "mDataProp": function ( data, type, full, meta) {
                    //         if(data.qa_status == "0" || data.qa_status == 0)
                    //         {
                    //             return '<span class="text-light knOrangeColor bg-dark"><strong>PENDING</strong></span>';
                    //         }
                    //         else if(data.qa_status == "1" || data.qa_status == 1) {
                    //             return '<span class="text-light knGreenColor bg-dark"><strong>ACCEPTED</strong></span>';
                    //         } else {
                    //             return '<span class="text-light knOrangeColor bg-dark"><strong>PENDING-RR</strong></span>';
                    //         }
                    //     }
                    // },
                    { 
                        "mDataProp": function ( data, type, full, meta) {
                            // var d = new Date(data.recent_update); 
                            // var time = d.toLocaleString('en-US', { hour: '2-digit', minute: '2-digit', hour12: true });
                            // var dFormat = ("0" + (d.getDate())).slice(-2) + '/' + ("0" + (d.getMonth() + 1)).slice(-2) + '/' + d.getFullYear() + ' ' +time;
                            // return dFormat;
                            
                            return data.logs;
                        }
                    },
                    { 
                        "mDataProp": function ( data, type, full, meta) {
                            if(data.flag == "1")
                            return 'Active';
                            else if(data.flag == "2")
                            return 'Inactive';
                            else
                            return 'Active';
                        }
                    },
                ]  						
            });
        }

        $('#refreshBtn').on('click', function () {
        location.reload();
	     });

        
         let swalWithBootstrapButtons = Swal.mixin({buttonsStyling: false});


        $('#btnChangeStatus').on('click', function () {
        //alert("btnChangeStatus");

         //alert("btnChangeStatus");
         activeBtn.classList.remove('active');
        inactiveBtn.classList.remove('active');
        var dropdownOpt = $('#frmItemStatus').val();
        console.log(dropdownOpt,'dropdownOpt');
        var SelectedIdObject = commonCheckbox();
        var checkBoxLength   = SelectedIdObject[1];
        if (dropdownOpt > 0) {
           // alert("btnChangeStatus11");
            if (checkBoxLength >= 1) {
                var idJson = JSON.stringify(SelectedIdObject[0]);
                var StatusText = "Deactivate";
                if (dropdownOpt == 1) {
                    var StatusText = "Activate";
                }
                swalWithBootstrapButtons.fire(
                            {
                               
                                title: 'Do you want to ' + StatusText + ' this record ?',
                                type: 'warning',
                                showCancelButton: true,
                                scrollbarPadding: false,
                                confirmButtonText: 'Yes',
                                cancelButtonText: 'No',
                                reverseButtons: true,
                                width:460,
                                customClass: {'confirmButton': 'btn btn-green mx-2 px-3',  'cancelButton': 'btn btn-red mx-2 px-3'}
                            }
				).then(function(result) {
					if (result.value) {
                        
						MakeAsynPostRequest(base_path + 'dashboard/changeReqStatus', 'id=' + idJson + '&cs=' + dropdownOpt +
                        '&tblname=tbl_cad_requirement'+'&idName=request_id', 'json', function (data) {
                            $.when(getReqRequestList()).done(function(){
                               dispDetails(reqRequestJSON);  
                               //window.location.reload();      
                            });
                        });
					} 
                    if (result.value) {
                        
						MakeAsynPostRequest(base_path + 'dashboard/changeReqStatus', 'id=' + idJson + '&cs=' + dropdownOpt +
                        '&tblname=tbl_request'+'&idName=request_id', 'json', function (data) {
                            $.when(getReqRequestList()).done(function(){
                               dispDetails(reqRequestJSON);  
                               //window.location.reload();      
                            });
                        });
					} 
                    
                }); 
                
               
            }
        }
        else {
            // alert('Select a option');
            swalWithBootstrapButtons.fire({
                title: 'Select a option!',
                type: 'error',
                icon: 'error',
                customClass: {'confirmButton': 'btn btn-info px-5'}
            });
        }
        if(checkBoxLength == 0) {
            // alert('Select a record');
            swalWithBootstrapButtons.fire({
                title: 'Select a record!',
                type: 'error',
                icon: 'error',
                customClass: {'confirmButton': 'btn btn-info px-5'}
            });
        }
      });

      
      $('#btn-active').on('click', function () {
            satusval="1";
            activeBtn.classList.add('active');
            inactiveBtn.classList.remove('active');;
            const reqRequestJSON1 = reqRequestJSON.filter(item => item.flag === satusval);
            dispDetails(reqRequestJSON1);
	     });
         $('#btn-inactive').on('click', function () {
            satusval="2";
            inactiveBtn.classList.add('active');
            activeBtn.classList.remove('active');
            const reqRequestJSON1 = reqRequestJSON.filter(item => item.flag === satusval);
            dispDetails(reqRequestJSON1);
           
	     });

$('#refreshBtn').on('click', function () {
     sessionStorage.setItem('keepSearchOpen', 'true'); // remember user preference
    location.reload(); // reload the page
	});
	 $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {

    // Helper: parse table date format "dd/mm/yyyy hh:mm am/pm"
    function parseTableDate(str) {
        if (!str) return null;
        var parts = str.split(' ')[0].split('/'); // ["dd","mm","yyyy"]
        var timePart = str.split(' ')[1];         // "hh:mm"
        var ampm = str.split(' ')[2];             // "am" or "pm"

        var hours = 0, minutes = 0;
        if (timePart) {
            var timeParts = timePart.split(':');
            hours = parseInt(timeParts[0], 10);
            minutes = parseInt(timeParts[1], 10);
            if (ampm && ampm.toLowerCase() === 'pm' && hours < 12) hours += 12;
            if (ampm && ampm.toLowerCase() === 'am' && hours === 12) hours = 0;
        }

        return new Date(parts[2], parts[1] - 1, parts[0], hours, minutes);
    }

    // Helper: parse input date format "dd-mm-yyyy"
    function parseInputDate(str) {
        if (!str) return null;
        var parts = str.split('-'); // ["dd","mm","yyyy"]
        return new Date(parts[2], parts[1] - 1, parts[0]);
    }

    // --- Request Date filter ---
    var requestFrom = parseInputDate($('#RequestFrom').val());
    var requestTo   = parseInputDate($('#RequestTo').val());
    var requestDate = parseTableDate(data[5]); // column index 6

    if (requestFrom) requestFrom.setHours(0,0,0,0);
    if (requestTo) requestTo.setHours(23,59,59,999);
    if (requestDate) requestDate.setHours(0,0,0,0);

    if (requestFrom && (!requestDate || requestDate < requestFrom)) return false;
    if (requestTo && (!requestDate || requestDate > requestTo)) return false;

    // --- Cutoff Date filter ---
    var cutoffFrom  = parseInputDate($('#CutoffFrom').val());
    var cutoffTo    = parseInputDate($('#CutoffTo').val());
    var cutoffDate  = parseTableDate(data[6]); // column index 7

    if (cutoffFrom) cutoffFrom.setHours(0,0,0,0);
    if (cutoffTo) cutoffTo.setHours(23,59,59,999);
    if (cutoffDate) cutoffDate.setHours(0,0,0,0);

    if (cutoffFrom && (!cutoffDate || cutoffDate < cutoffFrom)) return false;
    if (cutoffTo && (!cutoffDate || cutoffDate > cutoffTo)) return false;

    return true;
});


  $('#searchButton').on('click', function() {


    var fromDate = $('#RequestFrom').val().trim();
    var toDate = $('#RequestTo').val().trim();
    var cutfromDate = $('#CutoffFrom').val().trim();
    var cuttoDate = $('#CutoffTo').val().trim();

    // Helper function to parse dd-mm-yyyy
    function parseDate(str) {
        var parts = str.split('-'); // ["dd", "mm", "yyyy"]
        return new Date(parts[2], parts[1] - 1, parts[0]); // year, month (0-based), day
    }

    // 1️⃣ Check RequestFrom / RequestTo
    if(fromDate!==''){
    if (fromDate === '' || toDate === '') {
        swalWithBootstrapButtons.fire({
            title: 'Select both From and To dates!',
            icon: 'error',
            customClass: { 'confirmButton': 'btn btn-info px-5' }
        });
        return false;
    }
}

    if(fromDate!=='' && toDate!==''){
        
   
    var from = parseDate(fromDate);
    var to = parseDate(toDate);

    if (from >= to) {
        swalWithBootstrapButtons.fire({
            title: 'Invalid date range. From date cannot be later than To date.',
            icon: 'error',
            customClass: { 'confirmButton': 'btn btn-info px-5' }
        });
        return false;
    }
 }
    // 2️⃣ Check CutoffFrom / CutoffTo
    if(cutfromDate!==''){
    if (cutfromDate === '' || cuttoDate === '') {
        swalWithBootstrapButtons.fire({
            title: 'Select both Cutoff From and To dates!',
            icon: 'error',
            customClass: { 'confirmButton': 'btn btn-info px-5' }
        });
        return false;
    }
}
  if(cutfromDate!=='' && cuttoDate!==''){
    var cutFrom = parseDate(cutfromDate);
    var cutTo = parseDate(cuttoDate); // ✅ make sure this matches your input ID exactly: CutoffTo

    if (cutFrom >= cutTo) {
        swalWithBootstrapButtons.fire({
            title: 'Invalid cutoff date range. From date cannot be later than To date.',
            icon: 'error',
            customClass: { 'confirmButton': 'btn btn-info px-5' }
        });
        return false;
    }
  }
    var table = $('#mCadQueueList').DataTable();

    var wip_ref_no = $('#wip_ref_no').val().toLowerCase();
    var brandId    = $('#brandId').val().toLowerCase();
     var requirement   = $('#requirement').val().toLowerCase();
	var merchantname = $('#merchantname').val().toLowerCase();
   
   

    table
      .column(1).search(wip_ref_no)
      .column(2).search(brandId)
      .column(4).search(requirement)
      .column(7).search(merchantname)
     
      .draw(); // ✅ redraw triggers custom filter
});




      
    });

</script>
<?php $this->load->view(CNFCOMPANY . 'template/pagefooter'); ?>