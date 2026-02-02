<?php $this->load->view(CNFCOMPANY.'template/pageheader');?><?php $this->load->view(CNFCOMPANY.'template/pageheader');?>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.10.23/datatables.min.css"/>
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css" />
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <body class="hold-transition layout-top-nav">
<div class="wrapper bgn-white">
    <?php $this->load->view(CNFCOMPANY.'template/templateheader');?>
    <div class="content-wrapper" style="background-color: white;">
        <div class="col-sm-12" style="display: block ruby; padding: 10px 25px">
        <?php
                $userInfo = fnGetUserLoggedInfo('1');
                $userType = $userInfo['usertype'];
            ?>

            <span class="header-title bgn-white"><b style="font-size: 20px !important; font-family: Arial">
                <?php if($userType != 2) echo 'BOM (A2) REQUEST SENT LIST'; else echo 'BOM (A2) AUTHORIZATION LIST'; ?>
            </b></span>
                        <div class="btn-toolbar pull-right " style="padding-top: 4px" role="toolbar" aria-label="Toolbar with button groups">
                <div class="btn-group mr-2v text-right" role="group" aria-label="First group">
                    <i class="fa fa-search-plus btn  btn-royal-blue" style="padding: 6px 12px;font-size: 16px;" onclick="
                            $(this).toggleClass('fa-search-plus fa-search')
                            $('.search_area').toggleClass('hide');"></i>
                </div>
                <div class="btn-group px-3" role="group" aria-label="Third group">
                    <select name="frmItemStatus" title="activate / deactivate" id="frmItemStatus" class="input-sm form-control" style="">
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
                        <div class="col-sm-2 text-royal-blue">
                            &nbsp;&nbsp;Order / Enquiry Ref. No.<br>
                            <input class="input-sm form-control form-control-sm mt-2 pt-3">
                        </div>
                        <div class="col-sm-2">
                            &nbsp;&nbsp;Request Date & Time<br>
                            <input class="input-sm form-control form-control-sm mt-2 pt-3">
                        </div>
                        <div class="col-sm-2">
                            &nbsp;&nbsp;Brand<br>
                            <input class="input-sm form-control form-control-sm mt-2 pt-3">
                        </div>
                        <div class="col-sm-2">
                            &nbsp;&nbsp;Style Ref. No. / Name
                            <input class="input-sm form-control form-control-sm mt-2 pt-3">
                        </div>
                        <div class="col-sm-2">&nbsp;&nbsp;Auth. Status<br>
                            <input class="input-sm form-control form-control-sm mt-2 pt-3">
                        </div>
                        <div class="col-sm-2">
                            &nbsp;&nbsp;Auth. Date & Time<br>
                            <input class="input-sm form-control form-control-sm mt-2 pt-3">
                        </div>
                        <div class="col-sm-8">&nbsp;</div>
                    </div>
                    <div class="col-sm-12 " style="padding:13px 0">
                        <div class="btn-toolbar pull-right py-0" role="toolbar" aria-label="Toolbar with button groups">
                            <div class="btn-group mr-2 px-4 text-right" role="group" aria-label="First group">
                                <button class="btn btn-sm btn-royal-blue">
                                    <i class="fa fa-search"></i> Search
                                </button>
                            </div>
                            <div class="btn-group" role="group" aria-label="Third group">
                                <button class="btn btn-sm btn-royal-blue">
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
                            <table id="MerAllReqSentAllList" class="table table-bordered table-hover"><thead><tr>
                                    <th></th>
                                    <th class="" id="0">WIP Ref. No.</th>
                                    <th class="" id="1">Brand</th>
                                    <th class="" id="3">Request For</th>
                                    <th class="" id="3">Requirement</th>
                                    <th class="" id="3">Request <br />Type</th>
                                    <th class="" id="3">Request <br />Date & Time</th>
                                    <th class="" id="7">Cutoff <br />Date & Time</th>
                                    <th class="" id="8">Authorization <br />Type</th>
                                    <th class="" id="8">Authorized By</th>
                                    <th class="" id="10">Current <br />Status</th>
                                    <th class="" id="11">Recent <br />Update </th>
                                    <th class="" id="11">Status</th>
                            </table>
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
<script src="<?php echo base_url();?>assets/plugins/datepicker/bootstrap-datepicker.js"></script>
<script src="<?php echo base_url();?>assets/js/datatables.min.js"></script>
<script src="<?php echo base_url();?>assets/js/loadingoverlay.min.js"></script>
<script src="<?php echo base_url();?>assets/custom/requestsent/bom2.js"></script>
<script>

    $(document).ajaxStart(function(a) {
        $.LoadingOverlay("show",{image: base_path+"assets/img/fullpage.gif"});
    });

    $(document).ajaxStop(function() {
        $.LoadingOverlay("hide");
    });


</script>
<?php $this->load->view(CNFCOMPANY.'template/pagefooter');?>
