<link rel="stylesheet" href="<?php echo base_url();?>assets/css/jexcel/jquery.jexcel.min.css" />
<style type="text/css">
    #frmPin {
        padding-left: 15px;
        letter-spacing: 42px;
        border: 0;
        background-image: linear-gradient(to left, black 70%, rgba(255, 255, 255, 0) 0%);
        background-position: bottom;
        background-size: 50px 1px;
        background-repeat: repeat-x;
        background-position-x: 35px;
        width: 220px;
        min-width:220px;
    }
    #divInner{
        left: 0;
        position: sticky;
    }
    #divOuter{
        width:190px;
        overflow:hidden
    }
    td div {
        font-family: Verdana, Geneva, sans-serif;
        font-size: 12px;
        line-height: 15px;
        /*padding: 5px 2px;*/
    }
    td {
        font-family: Verdana, Geneva, sans-serif;
        align: top;
    }
    table, .control-label {
        margin-bottom: 0px !important;
        font-size: 12px;
    }
    .form-control {
        height: 25px;
    }
    .mainheading {
        background-color: #bffff9;
    }
    .secondheading {
        background-color: #e3f9f7;
        height:27px;
    }
    .customcontrol {
        border-radius: 0;
        box-shadow: none;
        display: block;
        width: 100%;
        height: 34px;
        padding: 6px 12px;
        font-size: 12px;
        line-height: 1.42857143;
        color: #555;
        background-color: #fff;
        background-image: none;
        border: 1px solid #ccc;
    }
    .form-control {
        padding: 3px 2px !important;
        font-size:12px;
    }
    .wdtp75{
        width:75%;
    }
    .table, .secondheading { background-color: #ecf0f5; }
    .table td.secondheading { padding-top: 15px; }
</style>
<?php $this->load->view(CNFCOMPANY . 'template/pageheader'); ?>
    <body class="sidebar-mini skin-black wysihtml5-supported sidebar-collapse">
<div class="wrapper">
    <?php $this->load->view(CNFCOMPANY . 'template/templateheader'); ?>
    <!-- Left side column. contains the logo and sidebar -->
    <aside class="main-sidebar">
        <?php $this->load->view(CNFCOMPANY . 'template/templateleftmenu'); ?>
    </aside>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                Log List
            </h1>
            <ol class="breadcrumb">
                <li><a href="<?php echo base_url() . CNFCOMPANY ?>dashboard"><i class="fa fa-dashboard"></i> Home</a>
                </li>
                <!--<li><a href="<?php /*echo base_url().CNFCOMPANY*/ ?>management/managemanagement/">Management</a></li>
                <li class="active">Add/Edit Management</li>-->
            </ol>
        </section>
        <!-- Main content -->
        <section class="content">
            <?php
            //echo '<pre>'; print_r($logList); die('');
            ?>
            <div class="row">
                <div class="col-md-12">
                    <div id="DivContBasicInfo">
                        <div class="box box-info">
                            <div class="box-header with-border">
                                <a href="#" onclick="window.history.back()">Go back</a>
                                <div class="box-tools pull-right"></div>
                            </div>
                            <!-- /.box-header -->
                            <div class="box-body">
                                <table id="cadLogList" class="table table-bordered table-hover">
                                    <thead>
                                    <tr>
                                        <th class="sortable asc" id="1">Date<i class="fa fa-fw fa-sort"></i></th>
                                        <th class="sortable asc" id="2">Current Status<i class="fa fa-fw fa-sort"></i>
                                        </th>
                                        <th class="sortable asc" id="3">Comments<i class="fa fa-fw fa-sort"></i></th>
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
                        </div><!-- /. box -->
                    </div>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </section><!-- /.content -->
    </div><!-- /.content-wrapper -->
    <?php $this->load->view(CNFCOMPANY . 'template/templatefooter'); ?>
    <div class="control-sidebar-bg"></div>
</div><!-- ./wrapper -->
<script src="<?php echo base_url();?>assets/js/ajax.js"></script>
<script src="<?php echo base_url();?>assets/js/moment.min.js"></script>
<script src="<?php echo base_url();?>assets/js/bootstrap-datetimepicker.js"></script>
<script src="<?php echo base_url();?>assets/js/jexcel/jquery.jexcel.js"></script>
<script src="<?php echo base_url();?>assets/js/jexcel/numeral.min.js"></script>
<script type="text/javascript">
    fnCadLogList();

    function fnCadLogList() {
        var url = $(location).attr('href');
        var lasturlpart = url.substr(url.lastIndexOf('/') + 1);
        MakePostRequest(base_path + 'dashboard/cadloglist', "rfrom=1" + "&id=" + lasturlpart, 'json', fnCadLogListRes);
    }
    function fnCadLogListRes(data) {
        if (data != '') {
            console.log(data, 'data');
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
                                PageContent = PageContent + '<tr><td>' + value.du + '</td>' +
                                    '<td>' + value.cs + '</td>' +
                                    '<td>' + value.rem + '</td>' +
                                    '<td><a href="' + base_path + 'dashboard/cadreqLogDetail/' + encodeURIComponent(base64_encode(value.id)) + '">View</a></td>';
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
                    //$("tbody").empty();
                    $("#cadLogList").append(PageContent);
                }
            }
        }
    }

    var GlbId          	        = "<?php echo @$VarId;?>";

</script>
<?php $this->load->view(CNFCOMPANY . 'template/pagefooter'); ?>