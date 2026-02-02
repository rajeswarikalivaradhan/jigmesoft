<link rel="stylesheet" href="<?php echo base_url();?>assets/css/jexcel/jquery.jexcel.min.css" type="text/css" />
<!--<link rel="stylesheet" type="text/css" href="<?php /*echo base_url();*/?>assets/css/jquery.fancybox.min.css">-->
<?php
$this->load->view(CNFCOMPANY.'template/pageheader');
$ArrLoggedUserInfo      = fnGetUserLoggedInfo(1);
$VarUserType            = $ArrLoggedUserInfo['usertype'];
$VarProfilePermission   = $ArrLoggedUserInfo['pp'];
$ArrUserDetails         = fnGetUserLoggedInfo();
?>
<body class="sidebar-mini skin-black wysihtml5-supported sidebar-collapse">
<style>
    td.readonly { color: #808080 !important; }
    .jexcel .jexcel_arrow {
        display:none;
    }
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
    .pd3 {
        padding:3px !important;
    }
    .form-control {
        padding: 3px 2px !important;
        font-size:12px;
    }
    .no-border{
        border:0px !important;
    }
    #divEditBasicInfo {
        list-style-type:none;
        white-space:nowrap;
        overflow-x:auto;
    }
</style>
<div class="wrapper">
    <?php $this->load->view(CNFCOMPANY.'template/templateheader');?>

    <aside class="main-sidebar">
        <?php $this->load->view(CNFCOMPANY.'template/templateleftmenu');?>
    </aside>

    <div class="content-wrapper">
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
                        <div class="mainheading"><strong>FABRIC DETAILS: KNIT</strong></div>
                        <div id="FabricDetails"></div><br/>
                        <div class="mainheading"><strong>P.O. WISE & SIZE WISE ITEMIZED QTY. BREAK-UP </strong></div>
                        <div id="SizeWiseQtyBrkUpFifthTbl"></div>
                        <br/><br/>
                        <div class="mainheading"><strong>CUMULATIVE QTY. AS PER SIZE SPEC CODE</strong></div>
                        <div id="CumulativeSixthATbl"></div>
                        <!--                        Select All
                                                <input type="checkbox" id="selectAllCheckbox" value="1">-->
                        <br/><br/>
                        <div class="mainheading" style="margin-bottom: -40px"><strong>FABRIC CONSUMPTION CALCULATION</strong></div><br/>
                        <div id="FabricProgramCalc"></div>
                        <br/>
                        <div class="mainheading"><strong>ITEMIZED FABRIC REQUIREMENT DETAILS COLOUR WISE & DIA WISE</strong></div>
                        <!--<button type="button" class="btn btn-info pull-right addrights" onclick="FnSaveCCSub()">Save</button>-->
                        <br/>
                        <button type="button" class="btn btn-info pull-right addrights" onclick="FnRequirementDetailsTbl()" id="">Requirement Details</button>
                        <br/><br/>
                        <div id="requirements"></div>

                        <div class="mainheading"><strong>CUMULATIVE FABRIC REQUIREMENT DETAILS COLOUR WISE & DIA WISE</strong></div>
                        <div id="ConsolidatedReqData"></div>
                        <br/><br/>
                        <button class="btn btn-info pull-right addrights" type="button" onclick="FnYarnDyeColorSplit_SDBSplit()">Yarn Dye Color Split & SDB Split</button>
                        <br/><br/>
                        <div class="mainheading"><strong>FABRIC WITH MORE THAN ONE CONTENT OR COUNT - ITEMIZED YARN WEIGHT CALCULATION</strong></div>
                        <div id="CountContentSDBSplit"></div>
                        <br/>
                        <div class="mainheading"><strong>YARN DYEING - ITEMIZED COLOUR WISE QTY. BREAK-UP DETAILS</strong></div>
                        <div id="YarnDyeColorSplit"></div>
                        <br/><br/>
                        <button class="btn btn-info pull-right addrights" type="button" onclick="FnItemizedYarnProgram()">ITEMIZED YARN PROGRAM</button>
                        <br/><br/>
                        <div class="mainheading"><strong>Itemized Yarn Program</strong></div>
                        <div id="ItemizedYarnProgram"></div>
                        <br/>
                        <div class="pull-right">
                            <strong style="float: left; width: 48px">Total: </strong>
                            <div id="PlanFabWgtSubtotalSum" style="float: left; width: 124px;"></div>
                            <div id="LycraWgtSum" style="float: left; width: 68px"></div>
                            <div id="ReqYarnWgtSum" style="float: left; width: 63px"></div>
                        </div>
                        <br/>
                        <button class="btn btn-info pull-right addrights" type="button" onclick="FnConsolidatedCountWiseYarnReq()">CONSOLIDATED COUNT WISE YARN REQUIREMENT QTY.</button>
                        <br/><br/>
                        <div class="mainheading"><strong>CONSOLIDATED COUNT WISE YARN REQUIREMENT QTY.</strong></div>
                        <div id="CountWiseYarnReqQty"></div>
                        <br/>
                        <button class="btn btn-info pull-right addrights" type="button" onclick="FnYarnRequirementDetailsFinalTbl()">YARN REQUIREMENT DETAILS</button>
                        <br/><br/>
                        <div class="mainheading"><strong>YARN REQUIREMENT DETAILS</strong></div>
                        <div id="YarnRequirementDetailsFinalTbl"></div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <?php $this->load->view(CNFCOMPANY.'template/templatefooter');?>
    <div class="control-sidebar-bg"></div>
</div>
<script src="<?php echo base_url();?>assets/js/ajax.js"></script>
<script src="<?php echo base_url();?>assets/js/jexcel/jquery.jexcel.js"></script>
<script src="<?php echo base_url();?>assets/js/jexcel/numeral.min.js"></script>
<script src="<?php echo base_url();?>assets/js/jexcel/excel-formula.min.js"></script>
<script src="<?php echo base_url();?>assets/js/commonfunctions.js"></script>
<script>
    var Glbfabricfinish = '<?php echo $fabricfinish ?>';
    var GlbfabricfinishStageForm = '<?php echo $fabricfinishStageForm ?>';
    var GlbYarnCount = '<?php echo $ArrYarnCount ?>';
    var GlbYarnPurchaseType = '<?php echo $ArrYarnPurchaseType ?>';
    //var GlbDyeingType = '<?php //echo unserialize('DYEINGTYPE') ?>';
    //console.log(GlbDyeingType,'GlbDyeingType');
</script>
<script src="<?php echo base_url();?>assets/js/<?php echo CNFCOMPANY ?>mfabricindex.js"></script>
<script>
    FnGetDropdownDatas();
</script>
<style>
    .table > tbody > tr > td, .table > tbody > tr > th, .table > tfoot > tr > td, .table > tfoot > tr > th, .table > thead > tr > td, .table > thead > tr > th {padding:5px;}
    .jexcel>thead>tr>td {font-size:12px !important;}
    table {font-size:12px;}
    .pd0{padding:0px;}
    .jexcel {white-space: normal !important;}
    .jexcel>tbody>tr>td {white-space: normal !important; word-wrap:break-word !important;}
</style>
<?php $this->load->view(CNFCOMPANY.'template/pagefooter'); ?>