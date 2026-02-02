<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/plugins/datepicker/datepicker3.css">
<?php $this->load->view(CNFCOMPANY.'template/pageheader');?>
<body class="sidebar-mini skin-black wysihtml5-supported sidebar-collapse">
<div class="wrapper">
    <?php $this->load->view(CNFCOMPANY.'template/templateheader');?>
    <aside class="main-sidebar">
        <?php $this->load->view(CNFCOMPANY.'template/templateleftmenu');?>
    </aside>
    <div class="content-wrapper">
        <section class="content-header">
            <h1>Search
                <!--<a class="btn btn-default addBtnDetails" href="<?php /*echo base_url().CNFCOMPANY*/?>mcadrequest/addedit"><i class="fa fa-plus" style="margin-right: 10px"></i> ADD CAD REQUEST</a>-->
            </h1>
            <ol class="breadcrumb">
                <li><a href="<?php echo base_url().CNFCOMPANY?>dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
                <li></li><li class="active">REQUEST SENT LIST - (PAYMENT)</li>
            </ol>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-info" style="border-top-color: #4747d1">
                        <!--<div class="box-header with-border">
                            <h3 class="box-title">Search</h3>
                            <div class="box-tools pull-right">
                                <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                            </div>
                        </div>-->
                        <!-- /.box-header -->
                        <!-- form start -->
                        <?php
                        $ArrStatus  = unserialize(ARRSTATUS);
                        unset($ArrStatus[3]);
                        ?>
                        <form class="form-horizontal" name="frmNameSearchCadReq" id="frmNameSearchBomPayreq" action="#">
                            <div class="box-body">
                                <div class="col-xs-12 col-md-6 col-sm-6 col-lg-6">
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-4 control-label">WIP Ref. No.</label>
                                        <div class="col-sm-8">
                                            <input type="text" name="" class="form-control" id="frmSrchWipRefNo" placeholder="WIP Ref. No.">
                                            <input type="hidden" name="hidddenColumnId" id="hidddenColumnId">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-4 control-label">IOR / ISR</label>
                                        <div class="col-sm-8">
                                            <select name="frmNameSearchIsrIorType" id="frmNameSearchIsrIorType" class="form-control">
                                                <option value="">Select IOR / ISR</option>
                                                <?php
                                                $ArrIsrIor  = unserialize(ARRISRIOR);
                                                foreach($ArrIsrIor as $VarKey=>$Varval) { ?>
                                                    <option value="<?php echo $VarKey?>"><?php echo $Varval?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-4 control-label">Vendor</label>
                                        <div class="col-sm-8">
                                            <select class="form-control" id="frmSrchVendor">
                                                <option value="">Choose</option>
                                                <?php
                                                foreach ($ArrVendors as $key => $item) {
                                                    ?>
                                                    <option value="<?php echo $item->id ?>"><?php echo $item->vendorname ?></option>
                                                    <?php
                                                }
                                                ?>
                                            </select>
                                            <div class="herr" id="ErrfrmSrchMerchantName"></div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-4 control-label">P.I. No</label>
                                        <div class="col-sm-8">
                                            <select class="form-control" id="frmSrchPirefno">
                                                <option value="">Choose</option>
                                                <?php
                                                foreach ($ArrBomPurchaseIndentRefno as $key => $requests) {
                                                    echo '<option value="'.$requests->id.'">'.$requests->pireferenceno.'</option>';
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-md-6 col-sm-6 col-lg-6">
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-4 control-label">Requirement</label>
                                        <div class="col-sm-8">
                                            <select class="form-control" id="frmSrchRequirement">
                                                <option value="">Choose Requirement</option>
                                                <?php
                                                foreach ($ArrRequirements as $key => $item) {
                                                    ?>
                                                    <option value="<?php echo $key ?>"><?php echo $item ?></option>
                                                    <?php
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-4 control-label">Cut off Date</label>
                                        <div class="col-sm-4">
                                            <input type="text" name="" class="form-control" id="frmSrchCutOffDateFrom" placeholder="From">
                                        </div>
                                        <div class="col-sm-4">
                                            <input type="text" name="" class="form-control" id="frmSrchCutOffDateTo" placeholder="To">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-4 control-label">Approval Status</label>
                                        <div class="col-sm-8">
                                            <?php
                                            $ArrCStatus = unserialize(ARRSTATUS); unset($ArrCStatus[3]);
                                            ?>
                                            <select class="form-control" id="frmSrchCStatus">
                                                <option value="">Choose</option>
                                                <?php
                                                foreach ($ArrCStatus as $key => $item) {
                                                    ?>
                                                    <option value="<?php echo $key ?>"><?php echo $item?></option>
                                                    <?php
                                                }
                                                ?>
                                            </select>
                                            <div class="herr" id="ErrfrmSrchCStatus"></div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-4 control-label">Current Status</label>
                                        <div class="col-sm-8">
                                            <?php
                                            $ArrCStatus = unserialize(ARRSTATUS); unset($ArrCStatus[3]);
                                            ?>
                                            <select class="form-control" id="frmSrchCStatus">
                                                <option value="">Choose</option>
                                                <?php
                                                foreach ($ArrCStatus as $key => $item) {
                                                    ?>
                                                    <option value="<?php echo $key ?>"><?php echo $item?></option>
                                                    <?php
                                                }
                                                ?>
                                            </select>
                                            <div class="herr" id="ErrfrmSrchCStatus"></div>
                                        </div>
                                    </div>
                                </div>
                            </div><!-- /.box-body -->
                            <div class="box-footer">
                                <button type="button" class="btn btn-default" onclick="refreshPage('frmNameSearchBomPayreq');">Reset</button>
                                <button type="button" class="btn btn-info pull-right" style="color: #000000" onclick="fnListSearch();">Search</button>
                            </div><!-- /.box-footer -->
                        </form>
                    </div><!-- /.box -->
                    <div class="box" style="border-top-color: #4747d1">
                        <div class="box-header with-border">
                            <h3 class="box-title">REQUEST SENT LIST - (PAYMENT)</h3>
                        </div><!-- /.box-header -->
                        <div class="box-body table-responsive no-padding">
                            <span class="atozalphabetscaption">Select Brands by Alphabetss</span>
                            <div class="filterbyalpha">

                                <span class="alpha_all_filter">All</span>

                                <?php for($i=65;$i<=90;$i++) {?>

                                    <span class="alpha_filter"><?php echo "&#".$i.";";?></span>

                                <?php }?>

                            </div>

                            <div class="clearfix"></div>

                            <div class="col-sm-12 pdt10">

                                <div class="col-sm-9 col-xs-12 pdl0">

                                    <div id="DivTotalCntResult" class="pdt10"></div>

                                </div>

                                <div class="col-sm-3 col-xs-12 pull-right mpull-left no-padding">
                                    <div id="ResResult"></div>

                                    <div class="col-sm-9 col-xs-9 no-padding">

                                        <select name="frmItemStatus" id="frmItemStatus" class="form-control wd80">

                                            <option value="">Select</option>

                                            <option value="1">Activate</option>

                                            <option value="2">Deactivate</option>

                                        </select>

                                        <div class="herr" id="ErrItemStatus"></div>

                                    </div>

                                    <div class="col-sm-3  col-xs-3 pdr0">

                                        <input type="button" name="btnChangeStatus" id="btnChangeStatus" style="color: #000000" class="btn btn-info pull-right" value="Update">

                                    </div>

                                </div>

                            </div>
                            <div id="DivTotalCntResult" class="pd10"></div>
                            <table id="mBomPurchasePaymentsentList" class="table table-bordered table-hover">
                                <thead>
                                <tr>
                                    <th></th>
                                    <th class="sortable asc" id="0">WIP Ref. No.<i class="fa fa-fw fa-sort"></i></th>
                                    <th class="sortable asc" id="1">Vendor<i class="fa fa-fw fa-sort"></i></th>
                                    <th class="sortable asc" id="2">P.I. No.<i class="fa fa-fw fa-sort"></i></th>
                                    <th class="sortable asc" id="3">Requirement<i class="fa fa-fw fa-sort"></i></th>
                                    <th class="sortable asc" id="4">Request Date & Time<i class="fa fa-fw fa-sort"></i></th>
                                    <th class="sortable asc" id="5">Cutoff Date & Time<i class="fa fa-fw fa-sort"></i></th>
                                    <th class="sortable asc" id="6">Approval Status<i class="fa fa-fw fa-sort"></i></th>
                                    <th class="sortable asc" id="7">Prepared By Name<i class="fa fa-fw fa-sort"></i></th>
                                    <th class="sortable asc" id="8">Current Status<i class="fa fa-fw fa-sort"></i></th>
                                    <th class="sortable asc" id="9">Recent Update<i class="fa fa-fw fa-sort"></i></th>
                                    <th class="sortable asc" id="10">Active / Inactive Status<i class="fa fa-fw fa-sort"></i></th>
                                </tr>
                                </thead>
                            </table>
                            <div>
                                <section id="pagination_my" class="animated for_animate pdl15 ">
                                    <ul class="pagination m-b-none animated for_animate" id="ResPagination"></ul>
                                </section>
                            </div>
                        </div><!-- /.box-body -->
                    </div><!-- /.box -->
                </div><!-- /.col -->
            </div>
        </section>
    </div><!-- /.content-wrapper -->
    <?php $this->load->view(CNFCOMPANY.'template/templatefooter');?>
    <div class="control-sidebar-bg"></div>
</div><!-- ./wrapper -->
<!--<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.10.2/moment.min.js"></script>-->
<script src="<?php echo base_url();?>assets/js/commonfunctions.js?r=<?php echo CNFJSCSSRANDNO?>"></script>
<script src="<?php echo base_url();?>assets/plugins/datepicker/bootstrap-datepicker.js"></script>
<script src="<?php echo base_url();?>assets/js/loadingoverlay.min.js"></script>
<script>
    var GlbSearchParam = ''; var GlbFilterAlpha=''; var GlbSortOrder=''; var GlbColumnId='';
    function fnShowHideEndUserSub(VarType,VarDivShow) {
        var ArrProfileBasicList = ["divEditBasicInfo","divShowBasicInfo"];
        if(VarType==1) {
            var ArrFnalList	= ArrProfileBasicList;
        }
        //Remove Class
        for(i=0;i<ArrFnalList.length;i++) {
            $("#"+ArrFnalList[i]).removeClass('show');
            $("#"+ArrFnalList[i]).removeClass('hide');
        }
        //Add Class
        for(i=0;i<ArrFnalList.length;i++) {
            if(VarDivShow!=ArrFnalList[i]) {
                $("#"+ArrFnalList[i]).addClass('hide');
            }
        }
        $("#"+VarDivShow).addClass('show');
    }
    function fnQListSearch() {
        var WipRefNo     					        = $("#frmSrchWipRefNo").val();
        var IsrIorType     					                    = $("#frmNameSearchIsrIorType").val();
        var BB       					        = $("#frmSrchBB").val();
        var Pirefno     					                = $("#frmSrchPirefno").val();
        var Requirement     					                = $("#frmSrchRequirement").val();
        var Merchant     					                = $("#frmSrchMerchantName").val();
        var cutfrom     					                    = $("#frmSrchCutOffDateFrom").val();
        var CutOffTo     					                    = $("#frmSrchCutOffDateTo").val();
        var CStatus     					                = $("#frmSrchCStatus").val();
        var Vendor     					                = $("#frmSrchVendor").val();
        GlbFilterAlpha                                      = $('#hiddenAlpha').val();
        GlbFilterAlpha                                      = $('#hiddenAlpha').val();
        /*
            $clickedColumnId = xssclean($this->input->post('columnId'));
            $newsortorder    = xssclean($this->input->post('sortorder'));
            $VarAfilter      = xssclean($this->input->post('afilter'));
        */
        GlbSearchParam = "rfrom=1&wip="+WipRefNo+"&IsrIorType="+IsrIorType+"&bb="+BB+"&pirefno="+Pirefno+"&req="+Requirement+"&mer="+Merchant+"&cutfrom="+cutfrom+
            "&cutto="+CutOffTo+"&ven="+Vendor+"&cs="+CStatus;
        $("#DivTotalCntResult").html('');
        MakePostRequest(base_path+'dashboard/bompurchaseindentlist',GlbSearchParam,'json',fnQListRes);
    }
    function fnList() {
        $("#DivTotalCntResult").html('');
        GlbSearchParam = 'rfrom=1';
        MakeAsynPostRequest(base_path+'mfinance/paymentreceivedlist',GlbSearchParam,'json',fnListRes);
    }
    function fnListRes(data) {
        if(data!='') {
            if(data.errcode!=undefined) {
                if(data.errcode == '404') {
                    fnCallSessionExpire();
                    return false;
                } else {
                    var PageContent='';
                    if(data.cn>0) {
                        ListCount	= '<div style="font-weight:bold;">Number of Record(s) : '+data.cn+'</div>';
                        if(data.ct>0) {
                            $.each(data.re,function(index,value) {
                                PageContent=PageContent+'<tr><td><input type="checkbox" class="allcbox" id="'+value.id+'"></td>' +
                                    '<td><a href="#">'+value.wip+'</a></td>' +
                                    '<td>'+value.ven+'</td>' +
                                    '<td><a href="'+base_path+'mfinance/bomPurchasePayemntReqFinance/'+value.id+'">'+value.pino+'</a></td>' +
                                    '<td>'+value.req+'</td>' +
                                    '<td>'+value.reqdt+'</td>' +
                                    '<td>'+value.cutoff+'</td>' +
                                    '<td>'+value.apprstatus+'</td>' +
                                    '<td>'+value.apprby+'</td>' +
                                    '<td><a href="javascript:void(0)">'+value.cs+'</a></td>' +
                                    '<td>'+value.ru+'</td>'+
                                    '<td>'+value.s+'</td>';
                                PageContent=PageContent+'</tr>';
                            });
                        }
                        $("#DivTotalCntResult").html(ListCount);
                    } else {
                        PageContent	= PageContent+'<tr><td colspan="6" class="pdl15 herr text-center" style="padding-left:10px;">No Records(s) found</td></tr>';
                        $("#DivTotalCntResult").html('');
                    }
                    if(data.pa!=undefined) {
                        $("#ResPagination").html(base64_decode(data.pa));
                    }
                    $('tbody').empty();
                    $('#mBomPurchasePaymentsentList').append(PageContent);
                }
            }
        }
    }
    function fnChangeStatusRes(data) {
        if(data!='') {
            if(data.errcode!=undefined) {
                if(data.errcode == '404') {
                    fnCallSessionExpire();
                    return false;
                } else {
                    fnQList();
                }
            }
        }
    }
    $('#mBomPurchaseIndentList').on('click', 'th.sortable', function () {
        var ReturnVal							    = commonTableSorting(this);
        GlbSortOrder	  							= ReturnVal[1];
        GlbColumnId									= ReturnVal[0];
        GlbSearchParam = GlbSearchParam + "&columnId=" + GlbColumnId + "&sortorder=" + GlbSortOrder;
        console.log(GlbSearchParam);
        MakePostRequest(base_path+'dashboard/allqueuelist',GlbSearchParam,'json',fnListRes);
    });
    $('#btnChangeStatus').on('click',function () {
        var dropdownOpt                                 = $('#frmItemStatus').val();
        if(dropdownOpt > 0) {
            var SewTypeIdObject = commonCheckbox();
            var checkBoxLength = SewTypeIdObject[1];
            var cboxObj = SewTypeIdObject[0];
            $('#ErrItemStatus').html("");
            if(checkBoxLength == 0) {
                $('#ErrItemStatus').html("Choose a bill of material source");
            }
            if (checkBoxLength >= 1) {
                $('#ErrItemStatus').html("");
                var companyid_json = JSON.stringify(cboxObj);
                if (dropdownOpt == '1') { //Activate
                    if(confirm('Do you want to activate this bill of material source?')) {
                        GlbSearchParam							    = "rfrom=1&actdeactFabType=" + dropdownOpt + "&cid=" + companyid_json;
                        MakePostRequest(base_path+'dashboard/allqueuelistChangeStatus',GlbSearchParam,'json',fnChangeStatusRes);
                    }
                }
                else if (dropdownOpt == '2') { //Deactivate
                    if(confirm('Do you want to Deactivate this bill of material source?')) {
                        GlbSearchParam							    = "rfrom=1&actdeactFabType=" + dropdownOpt + "&cid=" + companyid_json;
                        MakePostRequest(base_path+'dashboard/allqueuelistChangeStatus',GlbSearchParam,'json',fnChangeStatusRes);
                    }
                }
            }
        }
        else {
            $('#ErrItemStatus').html("Select a Option");
        }
    });
    $('#frmSrchCutOffDateFrom').datepicker({
        format: 'dd-mm-yyyy',
        todayHighlight: true,
        autoclose: true
    });
    $('#frmSrchCutOffDateTo').datepicker({
        format: 'dd-mm-yyyy',
        todayHighlight: true,
        autoclose: true
    });
    function fnPaginationAllQList(VarURL) {
        var Parameters = GlbSearchParam;
        $("#DivTotalCntResult").html('');
        MakeAsynPostRequest(VarURL,Parameters,'json',fnQListRes);
    }
    $(document).ajaxStart(function(a){
        $.LoadingOverlay("show",{image: base_path+"assets/img/fullpage.gif"});
    });
    $(document).ajaxStop(function(){
        $.LoadingOverlay("hide");
    });
    fnList();
    var GlbUt = "<?php echo @$usertype ?>";
</script>
<?php $this->load->view(CNFCOMPANY.'template/pagefooter');?>