<?php $this->load->view(CNFCOMPANY . 'template/pageheader'); ?>
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/plugins/datepicker/datepicker3.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.10.23/datatables.min.css"/>
<body class="layout-top-nav">
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
        <div class="col-sm-12" style="display: block ruby; padding: 10px 25px;padding-top:100px;">
            <span class="header-title"><b style="font-size: 20px !important; font-family: Arial">QUEUE LIST</b></span>
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
                        <div class="col-sm-12 px-4" style="border-bottom: 1px solid #022B61;"></div>
                </div>
                <div class="search_area col-sm-12 px-5 hide">
                    <div class="col-sm-12 text-royal-blue" style="padding: 10px 5px">
                        SEARCH
                    </div>

                    <div class="col-sm-12 text-royal-blue" style="background-color: #f7f7f7;padding: 12px 0 6px 0px;">
                        <form id="searchForm" method="POST">
                            <div class="col-sm-2 text-royal-blue">
                                &nbsp;&nbsp;Order / Enquiry Ref. No.<br>
                                <input class="input-sm form-control form-control-sm mt-2" name="order_enq_ref_no">
                            </div>
                            <div class="col-sm-2">
                                &nbsp;&nbsp;Brand<br>
                                <select class="input-sm form-control form-control-sm mt-2 js-example-basic-single nrml-slt-inp" name="brandId">
                                    <option value="">Select</option>
                                    <?php
                                       foreach ($brands as $brand) {
                                         echo "<option value='".$brand->id."'>".$brand->brandname."</option>";
                                       }                                          
                                    ?>
                                </select>
                            </div>
                            <div class="col-sm-2">
                                &nbsp;&nbsp;Request Date & Time<br>
                                <p id="enquirySearch" class="search-div">
                                    <input type="text" placeholder="From" id="datecreatedfrom" name="datecreatedfrom" class="date input-sm form-control form-control-sm mt-2 ml-2 start search-date" />
                                    <input type="text" placeholder="To" id="datecreatedto" name="datecreatedto" class="date input-sm form-control form-control-sm mt-2 end search-date" />
                                </p>
                                <!-- <div class="d-flex">
                                    <input type="date" id="datecreatedfrom" name="datecreatedfrom" class="form-control mt-2 ml-2">
                                    <input type="date" id="datecreatedto" name="datecreatedto" class="form-control mt-2">
                                </div> -->
                            </div>
                            <div class="col-sm-2">
                                &nbsp;&nbsp;Enquiry Type
                                <input class="input-sm form-control form-control-sm mt-2" name="enquirytype">
                            </div>
                            <div class="col-sm-2">
                                &nbsp;&nbsp;No. of Combo / Colour
                                <input class="input-sm form-control form-control-sm mt-2" name="totalcombo">
                            </div>
                            <div class="col-sm-2">
                                &nbsp;&nbsp;No. of Component
                                <input class="input-sm form-control form-control-sm mt-2" name="totalcomponents">
                            </div>
                            <!-- <div class="col-sm-2">
                                &nbsp;&nbsp;Style Ref. No. / Name
                                <input class="input-sm form-control form-control-sm mt-2" name="stylenamerefno">
                            </div>
                            <div class="col-sm-2">&nbsp;&nbsp;Auth. Status<br>
                                <select class="input-sm form-control form-control-sm mt-2" name="status">
                                    <option value="">Select</option>
                                    <option value="1">Active</option>
                                    <option value="2">Inactive</option>
                                </select>
                            </div>
                            <div class="col-sm-3 mt-3 mb-3">
                                &nbsp;&nbsp;Auth. Date & Time<br>
                                <div class="d-flex">
                                    <input type="date" id="dateauthorizedfrom" name="dateauthorizedfrom" class="form-control mt-2 ml-2">
                                    <input type="date" id="dateauthorizedto" name="dateauthorizedto" class="form-control mt-2">
                                </div>
                            </div> -->
                            <div class="col-sm-8">&nbsp;</div>
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
                    <div class="box" style="border-top-color: #4747d1">
                        <div class="box-body no-padding">
                            <table id="mCadQueueList" class="table table-bordered" data-page-length="50">
                                <thead>
                                <tr>
                                    <th></th>
                                    <th id="0">WIP Ref. No.</th>
                                    <th id="1">Brand</th>
                                    <th id="3">Queue No.</th>
                                    <th id="3">Requirement</th>
                                    <th id="3">Request <br />Date & Time</th>
                                    <th id="7">Cutoff <br />Date & Time</th>
                                    <th id="8">Merchant <br> Name</th>
                                    <th id="8">Authorization <br />Type</th>
                                    <th id="8">Authorized By</th>
                                    <th id="10" style="width:110px;">Current <br>Status</th>
                                    <th id="11">Recent <br>Update</th>
                                    <th id="12">Status</th>
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
<!--<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.10.2/moment.min.js"></script>-->
<script src="<?php echo base_url(); ?>assets/js/commonfunctions.js?r=<?php echo CNFJSCSSRANDNO ?>"></script>
<script src="<?php echo base_url(); ?>assets/plugins/datepicker/bootstrap-datepicker.js"></script>
<script src="<?php echo base_url(); ?>assets/js/loadingoverlay.min.js"></script>
<script src="<?php echo base_url();?>assets/js/datatables.min.js"></script>
<script>

    $(document).ready(function() {

        var reqRequestJSON;
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
                url: base_path+'company/mcaduser/getcadQueuelist',
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
                    { "mDataProp": "isriorcode" },	
                    { "mDataProp": "brandname" },	
                    { 
                        "mDataProp": function ( data, type, full, meta) {
                            return '<a class="bold" href="' + base_path +'request/Cadrequest/cadDeptQueueDetails/'+ encodeURIComponent(btoa(data.enquiry_id)) + '/reqId/' + encodeURIComponent(btoa(data.request_id)) + '">' + data.ref_queue_no + '</a>';
                        }
                    },
                    { "mDataProp": "cad_requirement" },
                    { 
                        "mDataProp": function ( data, type, full, meta) {
                            // var d = new Date(data.req_date); 
                            // var time = d.toLocaleString('en-US', { hour: '2-digit', minute: '2-digit', hour12: true });
                            // var dFormat = ("0" + (d.getDate())).slice(-2) + '/' + ("0" + (d.getMonth() + 1)).slice(-2) + '/' + d.getFullYear();
                            return data.req_date;
                        }
                    },							
                    { 
                        "mDataProp": function ( data, type, full, meta) {
                            // var d = new Date(data.cutoff_date); 
                            // var time = d.toLocaleString('en-US', { hour: '2-digit', minute: '2-digit', hour12: true });
                            // var dFormat = ("0" + (d.getDate())).slice(-2) + '/' + ("0" + (d.getMonth() + 1)).slice(-2) + '/' + d.getFullYear();
                            return data.cutoff_date;
                        }
                    },
                    { "mDataProp": "merchant_name" },
                    { "mDataProp": "auth_type" },
                    { "mDataProp": "auth_name" },
                    { 
                        "mDataProp": function ( data, type, full, meta) {
                            return data.job_status;
                        }
                    },
                    // { 
                    //     "mDataProp": function ( data, type, full, meta) {
                    //         var d = new Date(data.recent_update); 
                    //         var time = d.toLocaleString('en-US', { hour: '2-digit', minute: '2-digit', hour12: true });
                    //         var dFormat = ("0" + (d.getDate())).slice(-2) + '/' + ("0" + (d.getMonth() + 1)).slice(-2) + '/' + d.getFullYear() + ' ' +time;
                    //         return dFormat;
                    //     }
                    // },
                    { 
                        "mDataProp": function ( data, type, full, meta) {
                            return data.recent_update;
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
    });

</script>
<?php $this->load->view(CNFCOMPANY . 'template/pagefooter'); ?>