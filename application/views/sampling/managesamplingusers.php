<?php $this->load->view(CNFCOMPANY . 'template/pageheader'); ?>
    <body class="sidebar-mini skin-black wysihtml5-supported sidebar-collapse">
<div class="wrapper">
    <?php $this->load->view(CNFCOMPANY . 'template/templateheader'); ?>
    <aside class="main-sidebar">
        <?php $this->load->view(CNFCOMPANY . 'template/templateleftmenu'); ?>
    </aside>
    <div class="content-wrapper">
        <section class="content-header">
            <h1>Sampling User List<a class="btn btn-default addBtnDetails"
                             href="<?php echo base_url()."msampling/addeditsamplinguser" ?>"><i class="fa fa-plus" style="margin-right: 10px"></i> ADD
                    Sampling Users</a></h1>
            <ol class="breadcrumb">
                <li><a href="<?php echo base_url("samplinguser") ?>dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
                <li>Sampling User List</li><li class="active">Sampling User List(s)</li>
            </ol>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-info">
                        <div class="box-header with-border">
                            <h3 class="box-title">Search</h3>
                            <div class="box-tools pull-right">
                                <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                                </button>
                            </div>
                        </div><!-- /.box-header -->
                        <!-- form start -->
                        <form class="form-horizontal" name="frmNameSearchSampling" id="frmNameSearchSampling">
                            <div class="box-body">
                                <div class="col-xs-12 col-md-6 col-sm-6 col-lg-6">
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-4 control-label">Name</label>
                                        <div class="col-sm-8">
                                            <input type="text" name="frmSrchMname" class="form-control"
                                                   id="frmSrchMname" placeholder="Name">
                                            <input type="hidden" name="hidddenColumnId" id="hidddenColumnId">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-4 control-label">E-mail Id</label>
                                        <div class="col-sm-8">
                                            <input type="text" name="frmSrchEmail" class="form-control"
                                                   id="frmSrchEmail" placeholder="E-mail Id">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-md-6 col-sm-6 col-lg-6">
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-4 control-label">Status</label>
                                        <div class="col-sm-8">
                                            <select name="frmSrchMStatus" id="frmSrchMStatus" class="form-control">
                                                <option value="">Select</option>
                                                <?php
                                                $ArrStatus = unserialize(ARRSTATUS);
                                                unset($ArrStatus[3]);
                                                foreach ($ArrStatus as $VarKey => $VarStatus) { ?>
                                                    <option value="<?php echo $VarKey ?>"><?php echo $VarStatus ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div><!-- /.box-body -->
                            <div class="box-footer">
                                <button type="button" class="btn btn-default" onclick="refreshPage('frmNameSearchSampling');">
                                    Reset
                                </button>
                                <button type="button" class="btn btn-info pull-right" onclick="fnSearch();">Search
                                </button>
                            </div><!-- /.box-footer -->
                        </form>
                    </div><!-- /.box -->
                    <div class="box box-info">
                        <div class="box-header with-border">
                            <h3 class="box-title">Sampling User List</h3>
                        </div><!-- /.box-header -->
                        <div class="box-body table-responsive no-padding">
                            <div class="filterbyalpha">
                                <span class="alpha_all_filter">All</span>
                                <?php for ($i = 65; $i <= 90; $i++) { ?>
                                    <span class="alpha_filter"><?php echo "&#" . $i . ";"; ?></span>
                                <?php } ?>
                            </div>
                            <div class="clearfix"></div>
                            <div class="col-sm-12 pdt10">
                                <div class="col-sm-9 col-xs-12 pdl0">
                                    <div id="DivTotalCntResult" class="pdt10"></div>
                                </div>
                                <div class="col-sm-3 col-xs-12 pull-right mpull-left no-padding">
                                    <div class="col-sm-9 col-xs-9 no-padding">
                                        <select name="frmItemStatus" id="frmItemStatus" class="form-control wd80">
                                            <option value="">Select</option>
                                            <option value="1">Activate</option>
                                            <option value="2">Deactivate</option>
                                        </select>
                                        <div class="herr" id="ErrItemStatus"></div>
                                    </div>
                                    <div class="col-sm-3  col-xs-3 pdr0">
                                        <input type="button" name="btnChangeStatus" id="btnChangeStatus" class="btn btn-info pull-right" value="Update">
                                    </div>
                                </div>
                            </div>
                            <div id="DivTotalCntResult" class="pd10"></div>
                            <table id="tableSamplingUserList" class="table table-bordered table-hover">
                                <thead>
                                <tr>
                                    <th></th>
                                    <th class="sortable asc" id="0">Name<i class="fa fa-fw fa-sort"></i></th>
                                    <th class="sortable asc" id="1">E-mail Id<i class="fa fa-fw fa-sort"></i></th>
                                    <th class="sortable asc" id="2">Mobile No<i class="fa fa-fw fa-sort"></i></th>
                                    <th class="sortable asc" id="3">Status<i class="fa fa-fw fa-sort"></i></th>
                                    <th class="sortable asc" id="4">Updated By<i class="fa fa-fw fa-sort"></i></th>
                                    <th class="sortable asc" id="5">Recent Update<i class="fa fa-fw fa-sort"></i></th>
                                    <th>Action</th>
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
    <?php $this->load->view(CNFCOMPANY . 'template/templatefooter'); ?>
    <div class="control-sidebar-bg"></div>
</div><!-- ./wrapper -->
<script src="<?php echo base_url(); ?>assets/js/loadingoverlay.min.js"></script>
<script type="text/javascript">
    $(document).ajaxStart(function (a) {
        $.LoadingOverlay("show", {image: base_path + "assets/img/fullpage.gif"});
    });
    $(document).ajaxStop(function () {
        $.LoadingOverlay("hide");
    });
    fnList();
    var GlbSearchParam = '';

    var GlbSortOrder = '';
    var GlbColumnId = '';

    function fnSearch() {
        var MName = $("#frmSrchMname").val();
        var Email = $("#frmSrchEmail").val();
        var Status = $("#frmSrchMStatus").val();
        GlbSearchParam = "rfrom=1&n=" + MName + "&e=" + Email + "&s=" + Status + "&columnId=" + GlbColumnId + "&sortorder=" + GlbSortOrder;
        $("#DivTotalCntResult").html('');
        MakePostRequest(base_path +'msampling/managesamplingusers', GlbSearchParam, 'json', fnListRes);
    }

    function fnList() {
        GlbSearchParam = "rfrom=1";
        $("#DivTotalCntResult").html('');
        MakePostRequest(base_path + 'msampling/managesamplingusers', GlbSearchParam, 'json', fnListRes);
    }

    function fnListRes(data) {
        if (data != '') {
            if (data.errcode != undefined) {
                if (data.errcode == '404') {
                    fnCallSessionExpire();
                    return false;
                } else {
                    var PageContent = '';
                    if (data.cn > 0) {
                        ListCount = '<div style="font-weight:bold;">Number of Record(s) : ' + data.cn + '</div>';
                        if (data.ct > 0) {
                            $.each(data.re, function (index, value) {
                                PageContent = PageContent + '<tr><td><input type="checkbox" class="allcbox" id="' + value.id + '"></td>' +
                                    '<td><a href="' + base_path + 'msampling/addeditsamplinguser/' + encodeURIComponent(base64_encode(value.id)) + '/">' + value.n + '</a></td>' +
                                    '<td>' + value.e + '</td>' +
                                    '<td>' + value.m + '</td>' +
                                    '<td>' + value.s + '</td>' +
                                    '<td>' + value.ub + '</td>' +
                                    '<td>' + value.du + '</td>' +
                                    '<td><i class="fa fa-trash-o"></i>&nbsp;&nbsp;<a href="javascript:void(0);" onclick="fnDeleteSamplinguser(' + value.id + ')">Delete</a></td>';
                                PageContent = PageContent + '</tr>';
                            });
                        }
                        $("#DivTotalCntResult").html(ListCount);
                    } else {
                        PageContent = PageContent + '<tr><td colspan="6" class="pdl15 herr text-center" style="padding-left:10px;">No Records(s) found</td></tr>';
                        $("#DivTotalCntResult").html('');
                    }
                    if (data.pa != undefined) {
                        $("#ResPagination").html(base64_decode(data.pa));
                    }
                    $('tbody').empty();
                    $('#tableSamplingUserList').append(PageContent);
                }
            }
        }
    }

    function fnPaginationSamplinguser(VarURL) {
        var Parameters = GlbSearchParam;
        $("#DivTotalCntResult").text('');
        MakePostRequest(VarURL, Parameters, 'json', fnListRes);
    }

    function fnDeleteSamplinguser(Id) {
        if (confirm("Are you want to delete this record?")) {
            var Parameters = "id=" + Id;
            MakePostRequest(base_path +'msampling/deletedata', Parameters, 'json', fnListRes);
        }
    }

    $('#btnChangeStatus').on('click', function () {
        var StatusOptSelVal = $('#frmItemStatus').val();
        if (parseInt(StatusOptSelVal) > 0) {
            var ArrItemCheckBoxSel = commonCheckbox();
            var ObkChkSelVal = ArrItemCheckBoxSel[0];
            if (parseInt(ArrItemCheckBoxSel[1]) == 0) {
                $('#ErrItemStatus').text("Select the Checkbox");
            }
            if (parseInt(ArrItemCheckBoxSel[1]) >= 1) {
                $('#ErrItemStatus').text("");
                var StatusText = "Deactivate";
                if (StatusOptSelVal == '1') {
                    var StatusText = "Activate";
                }
                if (confirm('Do you want to ' + StatusText + ' this records?')) {
                    MakePostRequest(base_path +'msampling/changemStatus', GlbSearchParam + "&actdeact=" + StatusOptSelVal + "&cid=" + JSON.stringify(ObkChkSelVal), 'json', fnSearch);
                }
            }
        } else {
            $('#ErrOption').html("Select a Option");
        }
    });

    $('#tableSamplingUserList').on('click', 'th.sortable', function () {
        var ReturnVal							    = commonTableSorting(this);

        GlbSortOrder	  							= ReturnVal[1];
        GlbColumnId									= ReturnVal[0];

        var MName = $("#frmSrchMname").val();
        var Email = $("#frmSrchEmail").val();
        var Status = $("#frmSrchMStatus").val();


        GlbSearchParam = "rfrom=1&n=" + MName + "&e=" + Email + "&s=" + Status + "&columnId=" + GlbColumnId + "&sortorder=" + GlbSortOrder;
        MakePostRequest(base_path + 'msampling/managesamplingusers', GlbSearchParam, 'json', fnListRes);
    });
    function fnChangeStatusRes(data) {
        if (data != '') {
            if (data.errcode != undefined) {
                if (data.errcode == '404') {
                    fnCallSessionExpire();
                    return false;
                } else {
                    fnListRes();
                }
            }
        }
    }
    
    function fnDelete(Id) {
        if(confirm("Are you want to delete?")) {
            MakePostRequest(base_path + 'msampling/deletedata', "rfrom=1&id="+Id, 'json', fnSearch);
        }
    }
</script>
<?php $this->load->view(CNFCOMPANY . 'template/pagefooter'); ?>