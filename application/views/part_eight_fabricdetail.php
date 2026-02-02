<!--<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jexcel/2.1.0/css/jquery.jexcel.min.css" type="text/css" />-->
<link rel="stylesheet" href="<?php echo base_url();?>assets/css/jexcel/jquery.jexcel.min.css" type="text/css" />
<!--<link rel="stylesheet" href="<?php /*echo base_url('assets/css/jexcel21/jquery.jexcel.css') */?>" type="text/css" />-->
<?php
$this->load->view(CNFCOMPANY.'template/pageheader');
$ArrLoggedUserInfo      = fnGetUserLoggedInfo(1);
$VarUserType            = $ArrLoggedUserInfo['usertype'];
$VarProfilePermission   = $ArrLoggedUserInfo['pp'];
$ArrUserDetails         = fnGetUserLoggedInfo();
?>
<body class="sidebar-mini skin-black wysihtml5-supported sidebar-collapse">
<style>
    td div {
        font-family: Verdana, Geneva, sans-serif;
        font-size: 12px;
        line-height: 15px;
    }
    td {
        font-family: Verdana, Geneva, sans-serif;
        align: top;
    }
    table {
        margin-bottom: 0px !important;
    }
    .mainheading {
        background-color: #bffff9;
    }
</style>
<div class="wrapper">
    <?php $this->load->view(CNFCOMPANY.'template/templateheader');?>
    <!-- Left side column. contains the logo and sidebar -->
    <aside class="main-sidebar">
        <?php $this->load->view(CNFCOMPANY.'template/templateleftmenu');?>
    </aside>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                Fabric Programme
                <!--                <small>Programme</small>-->
                <select class="" style="margin-left: 10px; color: crimson; font-size: smaller">
                    <option value="">BULK</option>
                    <option value="">SAMPLING</option>
                </select>

            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">Fabric Programme</li>
            </ol>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-info">
                        <div class="box-body">
                            <form class="form-horizontal" name="frmBasicInfo" id="frmOrderProcess" method="post">
                                <div class="alert alert-success alert-dismissable hide" id="divSuccessBasicInfoMsg"> </div>
                                <div class="alert alert-danger alert-dismissable hide" id="ErrOrderEntry"> </div>

                                <div class="form-group">
                                    <div class="mainheading"><strong>WASHING & OTHER FINISHING PROCESS PROGRAMME - COLOUR WISE</strong></div>
                                </div>

                                <div class="form-group">
                                    <div id="washingnOtherFinishingProcessPgmTbl"></div>
                                </div>

                                <div class="form-group">
                                    <button class="btn btn-info pull-right addrights" type="button" onclick="fnGotoNine()">Next</button>
                                </div>
                            </form>
                        </div><!-- /.box-body -->

                        <div class="box-footer nopadding"></div><!-- /.box-footer -->
                    </div>
                </div>
            </div>
        </section>
    </div><!-- /.content-wrapper -->
    <?php $this->load->view(CNFCOMPANY.'template/templatefooter');?>
    <div class="control-sidebar-bg"></div>
</div><!-- ./wrapper -->
<script src="<?php echo base_url();?>assets/js/ajax.js"></script>
<!--<script src="https://cdnjs.cloudflare.com/ajax/libs/jexcel/2.1.0/js/jquery.jexcel.min.js"></script>-->
<script src="<?php echo base_url();?>assets/js/jexcel/jquery.jexcel.js"></script>
<script src="<?php echo base_url();?>assets/js/jexcel/numeral.min.js"></script>
<script src="<?php echo base_url();?>assets/js/jexcel/excel-formula.min.js"></script>
<!--<script src="<?php /*echo base_url('assets/js/jexcel21/jquery.jexcel.js') */?>"></script>-->
<script src="<?php echo base_url();?>assets/js/commonfunctions.js"></script>
<script>
    //New data
    var GlbParam = "rfrom=1";

    var enquiryid = '<?php echo @$VarEnquiryId ?>';
    var HashEnquiryId = '<?php echo @$VarHashEnquiryId ?>';

    $("#washingnOtherFinishingProcessPgmTbl").jexcel({
        colHeaders: [
            "Combo",
            "Component",
            "Color",
            "Garment<br/>Part",
            "Fabric<br/>Blend<br/>(%)",
            "Fabric<br/>Content",
            "Fabric<br/>Name",
            "Fin. GSM",
            "Req.<br/>Finishing<br/>Process -<br/>Wet",
            "Req.<br/>Finishing<br/>Process -<br/>Dry",
            "Finishing<br/>Job<br/>Worker's<br/>Name",
            "Fin. DIA /<br/>DIM<br/>(W * H)",
            "Unit of<br/>Measure",
            "Plan. Fab.<br/>Wgt. (Kgs.)",
            "Plan. Fab.<br/>Wgt. -<br/>Subtotal (Kgs.)"
        ],
        colWidths: [100, 100, 100, 100, 50, 80, 80, 60, 80, 90, 70, 70, 60, 100,100],
        columns: [
            {type: 'text', wordWrap: true, readOnly: true},
            {type: 'text', wordWrap: true, readOnly: true},
            {type: 'text', wordWrap: true, readOnly: true},
            {type: 'text', wordWrap: true, readOnly: true},
            {type: 'text', wordWrap: true, readOnly: true},
            {type: 'text', wordWrap: true, readOnly: true},
            {type: 'text', wordWrap: true, readOnly: true},
            {type: 'text', wordWrap: true, readOnly: true},
            {type: 'text', wordWrap: true, readOnly: true},
            {type: 'text', wordWrap: true, readOnly: true},
            {type: 'text', wordWrap: true, readOnly: true},
            {type: 'text', wordWrap: true, readOnly: true},
            {type: 'text', wordWrap: true, readOnly: true},
            {type: 'text', wordWrap: true, readOnly: true},
            {type: 'text', wordWrap: true, readOnly: true},
            {type: 'text', wordWrap: true, readOnly: true},
        ]
    });

    function fnGotoNine() {
        fnRedirectPageTimeOut(base_path+'fabricprogramvtwo/part_nine_fabricdetail');
    }

</script>
<style>
    .table > tbody > tr > td, .table > tbody > tr > th, .table > tfoot > tr > td, .table > tfoot > tr > th, .table > thead > tr > td, .table > thead > tr > th {padding:5px;}
    .jexcel>thead>tr>td {font-size:12px !important;}
    table {font-size:12px;}

    .jexcel>tbody>tr>td {white-space: normal !important; word-wrap:break-word !important;}
</style>
<?php $this->load->view(CNFCOMPANY.'template/pagefooter'); ?>