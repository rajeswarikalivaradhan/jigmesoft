<?php
$this->load->view(CNFCOMPANY.'template/pageheader');
$ArrLoggedUserInfo      = fnGetUserLoggedInfo(1);
$VarUserType            = $ArrLoggedUserInfo['usertype'];
$VarProfilePermission   = $ArrLoggedUserInfo['pp'];
$ArrUserDetails         = fnGetUserLoggedInfo();
?>
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/jexcel4/jsuites.css"/>
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/jexcel4/jexcel.css"/>
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
    #ConsolidatedReqData table td.readonly { color:#000; }
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
                                    <div class="mainheading"><strong>FABRIC DETAILS: KNIT</strong></div>
                                    <div id="FabricDetailsKnit"></div>
                                </div>
                                <div class="form-group">
                                    <!--<div class="mainheading"><strong>P.O. WISE & SIZE WISE ITEMIZED QTY. BREAK-UP </strong></div>-->
                                    <div class="mainheading"><strong>SIZE WISE ITEMIZED P.O. QTY. BREAK-UP - BULK / SAMPLING - DD</strong></div>
                                    <div id="SizeWiseQtyBrkUpFifthTbl"></div>
                                    <div id="SizeWiseQtyBrkUpFifthTbltest"></div>
                                </div>
                                <div class="form-group">
                                    <div class="mainheading"><strong>CUMULATIVE QTY. AS PER SIZE SPEC CODE</strong></div>
                                    <div id="CumulativeSixthATbl"></div>
                                </div>

                                <div class="form-group">
                                    <div class="mainheading"><strong>ITEMIZED QTY. AS PER CUMULATIVE QTY. & INTAKE QTY.</strong></div>
                                    <div id="itemizedqtycumulativeqty_intakeqtytest"></div>
                                </div>

                                <div class="form-group">
                                    <div class="mainheading"><strong>FABRIC CONSUMPTION CALCULATION</strong></div>
                                    <div id="FabricProgramCalc"></div>
                                </div>

                                <div class="form-group">
                                    <button type="button" class="btn btn-info pull-right addrights" onclick="FnRequirementDetailsTbl()" id="">Itemized Fabric Requirement Details Colour wise & Dia Wise</button>
                                </div>

                                <!--<div class="form-group">
                                    <div id="requirements"></div>
                                </div>-->

                                <div class="form-group">
                                    <div class="mainheading"><strong>ITEMIZED FABRIC REQUIREMENT DETAILS COLOUR WISE & DIA WISE</strong></div>
                                    <div id="ConsolidatedReqData"></div>
                                </div>
                                <!--http://devapp.garmenplus.com/fabricprogramvtwo/fabricdetail/OQ%3D%3D-->
                                <div class="form-group">
                                    <button type="button" class="btn btn-info pull-right addrights" onclick="fnGotoEdit()" id="">Edit</button>
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
<script src="<?php echo base_url(); ?>assets/js/jexcel4/jsuites.js"></script>
<script src="<?php echo base_url(); ?>assets/js/jexcel4/jexcel.js"></script>
<script src="<?php echo base_url();?>assets/js/commonfunctions.js"></script>
<script>
    //New data
    var GlbParam = "rfrom=1", GlbGarmentParts = [], fabricConsumptionTblRowCount = 0;
    var enquiryid = '<?php echo @$VarEnquiryId ?>';
    var HashEnquiryId = '<?php echo @$VarHashEnquiryId ?>';
    var ArrKnitData = '<?php echo @$ArrKnitData ?>';
    var ArrFifthTbl = '<?php echo @$ArrFifthTbl ?>';
    var ArrSizeChartData = '<?php echo @$ArrSizeChartData ?>';
    var ArrSixTh_atbl = '<?php echo @$ArrSixTh_atbl ?>';
    var ItemizedyarnWgtCalc = '<?php echo @$ArrItemizedyarnWgtCalc ?>';
    var ArrYarnDyeing = '<?php echo @$ArrYarnDyeing ?>';
    var ArrItemYarnPgm = '<?php echo @$ArrItemYarnPgm ?>';
    var ArrConsCountWiseYarnReq = '<?php echo @$ArrConsCountWiseYarnReq ?>';
    var ArrYarnRequirementDetails = '<?php echo @$ArrYarnRequirementDetails ?>';
    var GlbSubChildData = '';
    var GlbFabricDyeingPgm = [];
    var GlbYarnPurchaseType = '<?php echo $ArrYarnPurchaseType ?>';
    var GlbYarnCount = '<?php echo @$ArrYarnCount ?>';
    var GlbUnitsOfMeasureArr = ["%","Nos.","Gms.","Kgs.","%","Inches.","Cms."], GlbDyeingType = ["FD", "YD", "SDB"], GlbYarnBlendPercent = ["100 %","90 %"];
    var YarnContentArr = ["Yarn Content 1","Yarn Content 2","Yarn Content 3"];
    var GlbIschecked = 0; var GlbCheckBoxRowId = 0;
    var ColHeaders = '';
    var ArrSizeChartHeader = JSON.parse(ArrSizeChartData);
    //console.log(ItemizedyarnWgtCalc,'ItemizedyarnWgtCalc');
    var ArrColHeaderFinal = ['Combo', 'Component', 'Colour', 'Size Spec<br/>Code/Fit', 'P.O. No. /<br/>Enq. Ref. No.'];
    for(var i=0;i<8;i++) {
        if(typeof (ArrSizeChartHeader[i])!="undefined") {
            ColHeaders = ColHeaders+ArrSizeChartHeader[i]+",";
            ArrColHeaderFinal.push(ArrSizeChartHeader[i]);
        } else {
            ColHeaders = ColHeaders+",";
            ArrColHeaderFinal.push('No<br/>Size');
        }
    }
    ArrColHeaderFinal.push('Itemized P.O. Qty.<br/>/ Sample Qty.','Pcs. /<br/>Set','Intake Qty.','Itemized Qty.<br/>(Pcs)');

    $("#CumulativeSixthATbl").jexcel({
        colHeaders: ArrColHeaderFinal,
        colWidths: [110, 110, 100, 90, 100, 45, 45, 45, 45, 45, 45, 45, 45, 120, 60, 80, 100],
        columns: [
            {type: 'text', wordWrap: true, readOnly: true},
            {type: 'text', wordWrap: true, readOnly: true},
            {type: 'text', wordWrap: true, readOnly: true},
            {type: 'text', wordWrap: true, readOnly: true},
            {type: 'text', wordWrap: true, readOnly: true},
            {type: 'numeric', wordWrap: true},
            {type: 'numeric', wordWrap: true},
            {type: 'numeric', wordWrap: true},
            {type: 'numeric', wordWrap: true},
            {type: 'numeric', wordWrap: true},
            {type: 'numeric', wordWrap: true},
            {type: 'numeric', wordWrap: true},
            {type: 'numeric', wordWrap: true},
            {type: 'text', wordWrap: true, readOnly: true},
            {type: 'text', wordWrap: true, readOnly: true},
            {type: 'numeric', wordWrap: true,readOnly: true},
            {type: 'text', wordWrap: true, readOnly: true},
        ],
        data: ArrSixTh_atbl
    });
    var dataItemizedCummlativeIntakeqty = $("#CumulativeSixthATbl").jexcel('getData');
    var reducedItemizedCummlativeIntakeqtyData = [], firstThreeColFabricDyeingProgramGroup = [];
    for(var i = 0; i < dataItemizedCummlativeIntakeqty.length; i++) {
        var size1 = dataItemizedCummlativeIntakeqty[i][5] * dataItemizedCummlativeIntakeqty[i][15];
        var size2 = dataItemizedCummlativeIntakeqty[i][6] * dataItemizedCummlativeIntakeqty[i][15];
        var size3 = dataItemizedCummlativeIntakeqty[i][7] * dataItemizedCummlativeIntakeqty[i][15];
        var size4 = dataItemizedCummlativeIntakeqty[i][8] * dataItemizedCummlativeIntakeqty[i][15];
        var size5 = dataItemizedCummlativeIntakeqty[i][9] * dataItemizedCummlativeIntakeqty[i][15];
        var size6 = dataItemizedCummlativeIntakeqty[i][10] * dataItemizedCummlativeIntakeqty[i][15];
        var size7 = dataItemizedCummlativeIntakeqty[i][11] * dataItemizedCummlativeIntakeqty[i][15];
        var size8 = dataItemizedCummlativeIntakeqty[i][12] * dataItemizedCummlativeIntakeqty[i][15];
        reducedItemizedCummlativeIntakeqtyData.push([dataItemizedCummlativeIntakeqty[i][0],dataItemizedCummlativeIntakeqty[i][1],
            dataItemizedCummlativeIntakeqty[i][2],dataItemizedCummlativeIntakeqty[i][3],dataItemizedCummlativeIntakeqty[i][4],
            size1,size2,size3,size4,size5,size6,size7,size8,dataItemizedCummlativeIntakeqty[i][13]]);

        firstThreeColFabricDyeingProgramGroup.push([dataItemizedCummlativeIntakeqty[i][0],dataItemizedCummlativeIntakeqty[i][1],
            dataItemizedCummlativeIntakeqty[i][2]]);
    }

    var ColHeaders = '';
    var itemizedqtycumuqty_intakeqtytesthead = ['Combo', 'Component', 'Colour', 'Size Spec<br/>Code/Fit', 'P.O. No. /<br/>Enq. Ref. No.'];
    for(var i=0;i<8;i++) {
        if(typeof (ArrSizeChartHeader[i])!="undefined") {
            ColHeaders = ColHeaders+ArrSizeChartHeader[i]+",";
            itemizedqtycumuqty_intakeqtytesthead.push(ArrSizeChartHeader[i]);
        } else {
            ColHeaders = ColHeaders+",";
            itemizedqtycumuqty_intakeqtytesthead.push('No<br/>Size');
        }
    }
    itemizedqtycumuqty_intakeqtytesthead.push('Itemized Qty. (Pcs.)','Tick');
    $("#itemizedqtycumulativeqty_intakeqtytest").jexcel({
        onchange: function (instance,cell,val) {
            var cellName = $(instance).jexcel('getColumnNameFromId', $(cell).prop('id'));
            //console.log(cellName);
            if(cellName.indexOf('O') == 0) {
                if(val === true) {
                    var ArrRowId = $(cell).prop('id').split('-');
                    FnCCTbl(instance, cell, val, ArrRowId[1]);
                }
            }
        },
        colHeaders: itemizedqtycumuqty_intakeqtytesthead,
        colWidths: [110, 110, 100, 90, 100, 45, 45, 45, 45, 45, 45, 45, 60, 150,60],
        columns: [
            {type: 'text', wordWrap: true, readOnly: true},
            {type: 'text', wordWrap: true, readOnly: true},
            {type: 'text', wordWrap: true, readOnly: true},
            {type: 'text', wordWrap: true, readOnly: true},
            {type: 'text', wordWrap: true, readOnly: true},
            {type: 'numeric', wordWrap: true},
            {type: 'numeric', wordWrap: true},
            {type: 'numeric', wordWrap: true},
            {type: 'numeric', wordWrap: true},
            {type: 'numeric', wordWrap: true},
            {type: 'numeric', wordWrap: true},
            {type: 'numeric', wordWrap: true},
            {type: 'numeric', wordWrap: true},
            {type: 'text', wordWrap: true, readOnly: true},
            {type: 'checkbox'},
        ],
        data: reducedItemizedCummlativeIntakeqtyData
    });


    function FnCCTbl(instance,cell,val,CellId) {
        var ColHeaders      = ""; var finalres = 0;

        var ArrColHeaderFinal   = ['Combo', 'Component', 'Colour', 'Size Spec<br/>Code / Fit', 'P.O. No. /<br/>Enq. Ref. No.'];
        for(var i=0;i<8;i++) {
            if(typeof (ArrSizeChartHeader[i])!="undefined") {
                ColHeaders = ColHeaders+ArrSizeChartHeader[i]+",";
                ArrColHeaderFinal.push(ArrSizeChartHeader[i]);
            } else {
                ColHeaders = ColHeaders+",";
                ArrColHeaderFinal.push('No<br/>Size');
            }
        }
        ArrColHeaderFinal.push('Itemized P.O. Qty.<br/>/ Sample Qty.'); ArrColHeaderFinal.push('Pcs. /<br/>Set');
        ArrColHeaderFinal.push('Intake Qty.<br/>(Nos.)');
        ArrColHeaderFinal.push('Itemized Qty.<br/>(Pcs.)');
        var Knitting = $("#FabricDetailsKnit").jexcel('getData'); var KnitGarmentParts = []; var Fabrics = [];
        for(var i = 0; i < Knitting.length; i++) {
            var GroupOne = Knitting[i][0]+"#"+Knitting[i][1]+"#"+Knitting[i][2];
            KnitGarmentParts = fnPopulateValueArray(KnitGarmentParts,GroupOne,Knitting[i][3]+"#"+Knitting[i][4]+"#"+Knitting[i][5]+"#"+Knitting[i][6]+"#"+Knitting[i][7]);
        }
        var OSixthATblCumulative = $("#CumulativeSixthATbl").jexcel('getData');
        var TableSno = 'Table No: '; TableSno += Number(CellId) + 1;
        $("#FabricProgramCalc").append('<br/><div class="mainheading box box-primary"><h4>'+TableSno+'</h4> </div> <div id="fabricprogrammain_'+CellId+'"></div>');
        $("#fabricprogrammain_"+CellId).jexcel({
            colHeaders: ArrColHeaderFinal,
            colWidths: [100, 100, 100, 100, 130, 45, 45, 45, 45, 45, 45, 45, 45, 120, 50, 75, 100],
            columns: [
                {type: 'text', wordWrap: true, readOnly: true},
                {type: 'text', wordWrap: true, readOnly: true},
                {type: 'text', wordWrap: true, readOnly: true},
                {type: 'text', wordWrap: true, readOnly: true},
                {type: 'text', wordWrap: true, readOnly: true},
                {type: 'numeric', wordWrap: true,readOnly: true},
                {type: 'numeric', wordWrap: true,readOnly: true},
                {type: 'numeric', wordWrap: true,readOnly: true},
                {type: 'numeric', wordWrap: true,readOnly: true},
                {type: 'numeric', wordWrap: true,readOnly: true},
                {type: 'numeric', wordWrap: true,readOnly: true},
                {type: 'numeric', wordWrap: true,readOnly: true},
                {type: 'numeric', wordWrap: true,readOnly: true},
                {type: 'text', wordWrap: true, readOnly: true},
                {type: 'text', wordWrap: true, readOnly: true},
                {type: 'text', wordWrap: true, readOnly: true},
                {type: 'text', wordWrap: true, readOnly: true}
            ],
            data: [OSixthATblCumulative[CellId]]
        });

        var UnSplittedColor = OSixthATblCumulative[CellId][2]; var SplitGarmentParts = []; var GarmentParts = [];

        if(UnSplittedColor.indexOf('-') >= 0) {
            var ArrSecondLevelColor = UnSplittedColor.split('-');
            for(var i = 0; i < ArrSecondLevelColor.length; i++) {
                var CurrentGroup        = OSixthATblCumulative[CellId][0]+"#"+OSixthATblCumulative[CellId][1]+"#"+jsTrim(ArrSecondLevelColor[i]);
                SplitGarmentParts.push(fnGroupArrayValue(KnitGarmentParts[CurrentGroup]));
            }
            var rslt = [].concat.apply([],SplitGarmentParts);
        }
        else {
            var CurrentGroup        = OSixthATblCumulative[CellId][0]+"#"+OSixthATblCumulative[CellId][1]+"#"+OSixthATblCumulative[CellId][2];
            GarmentParts        = fnGroupArrayValue(KnitGarmentParts[CurrentGroup]);
        }

        if(rslt) for(var r = 0; r < rslt.length; r++) GarmentParts.push(rslt[r]);

        var ColHeaders          = ""; var ArrReadOnlyInfo     =  new Array();
        var ArrHeader           = ["Garment<br/>Parts","Fabric<br/>Blend<br/>(%)","Fabric<br/>Content","Fabric<br/>Name","Finishing<br/>GSM","Description","Unit Of<br/>Measure"];
        for(var i=0;i<8;i++) {
            if(typeof (ArrSizeChartHeader[i])!="undefined") {
                ColHeaders = ColHeaders+ArrSizeChartHeader[i]+",";
                ArrHeader.push(ArrSizeChartHeader[i]);
                ArrReadOnlyInfo[i] = false;
            } else {
                ColHeaders = ColHeaders+",";
                ArrHeader.push('No Size');
                ArrReadOnlyInfo[i] = true;
            }
        }

        ArrHeader.push('Total');
        GlbGarmentParts.push(GarmentParts);

        for(var i = 0; i < GarmentParts.length; i++) {
            var gpartid = Number(i) + 1;
            var mtb = Number(CellId) + 1;
            MakePostRequest(base_path+'fabricprogramvtwo/editconscalcfabricdetail',GlbParam+"&enqid="+enquiryid+"&gpartid="+gpartid+"&maintblid="+mtb,'json',fnRes);
            function fnRes(datacc) {
                GlbSubChildData = datacc.ArrCC;
            }

            if(GarmentParts[i]) {
                var GpBlendContentName = GarmentParts[i].split('#');
                var Garmentpart = GpBlendContentName[0];
                var FabricBlend = GpBlendContentName[1];
                var FabricContent = GpBlendContentName[2];
                var FabricName = GpBlendContentName[3];
                var FinGsm = GpBlendContentName[4];
                $("#FabricProgramCalc").append('<div id="subchild_' + CellId + '_' + i + '" style="margin-top: 20px" class="SubTbl"></div>');
                $("#subchild_" + CellId + "_" + i).jexcel({
                    colHeaders: ArrHeader,
                    colWidths: [80, 50, 70, 70, 65, 170, 70, 70, 70, 70, 70, 70, 70, 70, 70, 90],
                    columns: [
                        {type: 'text', wordWrap: true, readOnly: true},
                        {type: 'text', wordWrap: true, readOnly: true},
                        {type: 'text', wordWrap: true, readOnly: true},
                        {type: 'text', wordWrap: true, readOnly: true},
                        {type: 'text', wordWrap: true, readOnly: true},
                        {type: 'text', wordWrap: true, readOnly: true},
                        {type: 'dropdown', source: GlbUnitsOfMeasureArr, wordWrap: true},
                        {type: 'text', wordWrap: true, readOnly: ArrReadOnlyInfo[0]},
                        {type: 'text', wordWrap: true, readOnly: ArrReadOnlyInfo[1]},
                        {type: 'text', wordWrap: true, readOnly: ArrReadOnlyInfo[2]},
                        {type: 'text', wordWrap: true, readOnly: ArrReadOnlyInfo[3]},
                        {type: 'text', wordWrap: true, readOnly: ArrReadOnlyInfo[4]},
                        {type: 'text', wordWrap: true, readOnly: ArrReadOnlyInfo[5]},
                        {type: 'text', wordWrap: true, readOnly: ArrReadOnlyInfo[6]},
                        {type: 'text', wordWrap: true, readOnly: ArrReadOnlyInfo[7]},
                        {type: 'text', wordWrap: true}
                    ],
                    data: GlbSubChildData
                });
            }
        }
    }

    $("#FabricDetailsKnit").jexcel({
        colHeaders: ['Combo', 'Component', 'Colour', 'Garment<br/>Parts', 'Fabric Blend (%)', 'Fabric Content', 'Fabric Name','Yarn<br/>Count', 'Finishing<br/>GSM',
            'Dyeing<br/>Type','Yarn Special<br/>Request', 'Fabric Finish'],
        //colHeaders: ['Combo', 'Component', 'Colour', 'Garment<br/>Parts', 'Fabric Blend (%)', 'Fabric Content', 'Fabric Name','Yarn Count', 'Finishing<br/>GSM','Dyeing Type','Yarn Special<br/>Request', 'Fabric Finish', 'Fabric Finish<br/>Stage / Form'],
        colWidths: [105, 105, 100, 120, 123, 123, 123, 80, 100, 60, 100,100],
        allowInsertColumn: false,
        columns: [
            {type: 'text', wordWrap: true},
            {type: 'text', wordWrap: true},
            {type: 'text', wordWrap: true},
            {type: 'text', wordWrap: true},
            {type: 'text', wordWrap: true},
            {type: 'text', wordWrap: true},
            {type: 'text', wordWrap: true},
            {type: 'text', wordWrap: true},
            {type: 'text', wordWrap: true},
            {type: 'text', wordWrap: true},
            {type: 'text', wordWrap: true},
            {type: 'text', wordWrap: true},
        ],
        data: ArrKnitData
    });
    var ColHeaders = '';
    var ArrSizeChartHeader = JSON.parse(ArrSizeChartData);

    var ArrColHeaderFinal = ['Combo', 'Component', 'Colour', 'Size Spec<br/>Code/Fit', 'P.O. No. /<br/>Enq. Ref. No.'];
    for(var i=0;i<8;i++) {
        if(typeof (ArrSizeChartHeader[i])!="undefined") {
            ColHeaders = ColHeaders+ArrSizeChartHeader[i]+",";
            ArrColHeaderFinal.push(ArrSizeChartHeader[i]);
        } else {
            ColHeaders = ColHeaders+",";
            ArrColHeaderFinal.push('No Size');
        }
    }
    ArrColHeaderFinal.push('Itemized P.O. Qty. /<br/>Sample Qty.'); ArrColHeaderFinal.push('Pcs. / Set');
    ArrColHeaderFinal.push('Intake Qty.<br/>(Nos.)');
    ArrColHeaderFinal.push('Itemized Qty.<br/>(Pcs.)');
    $("#SizeWiseQtyBrkUpFifthTbltest").jexcel({
        colHeaders: ArrColHeaderFinal,
        colWidths: [110, 110, 110, 90, 110, 45, 45, 45, 45, 45, 45, 45, 45, 100, 70, 70, 100 ],
        columns: [
            {type: 'text', wordWrap: true, readOnly: true},
            {type: 'text', wordWrap: true, readOnly: true},
            {type: 'text', wordWrap: true, readOnly: true},
            {type: 'text', wordWrap: true, readOnly: true},
            {type: 'text', wordWrap: true, readOnly: true},
            {type: 'numeric', wordWrap: true,readOnly: true},
            {type: 'numeric', wordWrap: true,readOnly: true},
            {type: 'numeric', wordWrap: true,readOnly: true},
            {type: 'numeric', wordWrap: true,readOnly: true},
            {type: 'numeric', wordWrap: true,readOnly: true},
            {type: 'numeric', wordWrap: true,readOnly: true},
            {type: 'numeric', wordWrap: true,readOnly: true},
            {type: 'numeric', wordWrap: true,readOnly: true},
            {type: 'text', wordWrap: true, readOnly: true},
            {type: 'text', wordWrap: true, readOnly: true},
            {type: 'text', wordWrap: true, readOnly: true},
            {type: 'text', wordWrap: true, readOnly: true}
        ],
        data: ArrFifthTbl
    });
    var ColHeaders      = "";
    var ArrSizeChartHeader = JSON.parse(ArrSizeChartData);
    var ArrColHeaderFinal   = ['Combo', 'Component', 'Colour', 'Size Spec<br/>Code/Fit', 'P.O. No. /<br/>Enq. Ref. No.'];
    for(var i=0;i<8;i++) {
        if(typeof (ArrSizeChartHeader[i])!="undefined") {
            ColHeaders = ColHeaders+ArrSizeChartHeader[i]+",";
            ArrColHeaderFinal.push(ArrSizeChartHeader[i]);
        } else {
            ColHeaders = ColHeaders+",";
            ArrColHeaderFinal.push('No Size');
        }
    }
    ArrColHeaderFinal.push('Itemized Qty. /<br/>(Pcs.)');
    //
    var Datafornext = [];
    var getDataCumulativeSixthATbl = $("#CumulativeSixthATbl").jexcel('getData');
    for(var i = 0; i < getDataCumulativeSixthATbl.length; i++) {
        var s1 = Number(getDataCumulativeSixthATbl[i][5]) * Number(getDataCumulativeSixthATbl[i][15]);
        var s2 = Number(getDataCumulativeSixthATbl[i][6]) * Number(getDataCumulativeSixthATbl[i][15]);
        var s3 = Number(getDataCumulativeSixthATbl[i][7]) * Number(getDataCumulativeSixthATbl[i][15]);
        var s4 = Number(getDataCumulativeSixthATbl[i][8]) * Number(getDataCumulativeSixthATbl[i][15]);
        var s5 = Number(getDataCumulativeSixthATbl[i][9]) * Number(getDataCumulativeSixthATbl[i][15]);
        var s6 = Number(getDataCumulativeSixthATbl[i][10]) * Number(getDataCumulativeSixthATbl[i][15]);
        var s7 = Number(getDataCumulativeSixthATbl[i][11]) * Number(getDataCumulativeSixthATbl[i][15]);
        var s8 = Number(getDataCumulativeSixthATbl[i][12]) * Number(getDataCumulativeSixthATbl[i][15]);
        //var s2 = getDataCumulativeSixthATbl[i][6] * getDataCumulativeSixthATbl[i][15];
        Datafornext.push([getDataCumulativeSixthATbl[i][0],getDataCumulativeSixthATbl[i][1],
            getDataCumulativeSixthATbl[i][2],getDataCumulativeSixthATbl[i][3]
            ,getDataCumulativeSixthATbl[i][4],s1,s2,s3,s4,s5,s6,s7,s8,getDataCumulativeSixthATbl[i][16]]);
    }
    //console.log(Datafornext,'Datafornext');
    function fnPopulateValueArray(ArrName, KeyValue, InsertVal) {
        //console.log(ArrName,'ar',KeyValue,'ki');
        //console.log('test');
        //console.log(jQuery.inArray(KeyValue, ArrName),'test if');
        //console.log(ArrName[KeyValue],'qwe');
        if (jQuery.inArray(KeyValue, ArrName)) {
            //if(typeof ArrName[KeyValue] != 'undefined') {
                ArrName[KeyValue] = ArrName[KeyValue]+"|-|"+InsertVal;
            //}
            //else {

            //}
        }
        else {
            //ArrName[KeyValue] = '';
        }
        return ArrName;
    }
    function fnGroupArrayValue(ArrSizeVal) {
        if (ArrSizeVal != "" && typeof ArrSizeVal != "undefined") {
            var SumVal = [];
            var ArrName = ArrSizeVal.split("|-|");
            for (var i = 0; i < ArrName.length; i++) {
                if(ArrName[i] != "undefined")
                    SumVal.push(ArrName[i]);
            }
            return SumVal;
        }
        else {
            return 0;
        }
    }
    function fnSumSizeArrayValue(ArrSizeVal) {
        if (ArrSizeVal != "") {
            var SumVal = 0;
            var ArrName = ArrSizeVal.split("|-|");
            for (var i = 0; i < ArrName.length; i++) {
                if (isFinite(ArrName[i])) {
                    SumVal = Number(ArrName[i]) + SumVal;
                }
            }
            return SumVal;
        }
        else {
            return 0;
        }
    }
    getOrderentry_sixthatbl();
    function getOrderentry_sixthatbl() {
        MakeAsynPostRequest(base_path+'fabricprogramvtwo/orderentry_sixthatbl',GlbParam+"&enqid="+enquiryid,'json',getOrderentry_sixthatblRes);
    }
    var GlbArrSixth_aTbl = [];
    function getOrderentry_sixthatblRes(data) {
        //console.log(data.ArrSixth_aTbl,'ArrSixth_aTbl');
        GlbArrSixth_aTbl = data.ArrSixth_aTbl;

    }

    ArrColHeaderFinal.push('Tick'); var GlbYarnCountDyeingType = [];
    function FnRequirementDetailsTbl() {
        var CumulativeSixthATbl = $("#CumulativeSixthATbl").jexcel('getData');
        var Knitting = $("#FabricDetailsKnit").jexcel('getData'); var KnitGarmentPartsCount = [];
        console.log(Knitting,'Knitting');
        for(var i = 0; i < Knitting.length; i++) {
            var GroupOne = Knitting[i][0]+"#"+Knitting[i][1]+"#"+Knitting[i][2];
            KnitGarmentPartsCount = fnPopulateValueArray(KnitGarmentPartsCount,GroupOne,Knitting[i][3]+"#"+
                Knitting[i][4]+"#"+Knitting[i][5]+"#"+Knitting[i][6]+"#"+Knitting[i][7]);
            GlbYarnCountDyeingType[Knitting[i][0]+"#"+Knitting[i][1]+"#"+Knitting[i][2]+"#"+Knitting[i][3]] = Knitting[i][7]+"|#|"+Knitting[i][9];
        }
        //console.log(GlbYarnCountDyeingType,'GlbYarnCountDyeingType');
        //console.log(KnitGarmentPartsCount,'KnitGarmentPartsCount');
        var FinishingGsmGroup = []; var RequirementDetailsTbl = [];
        for(var i = 0; i < Knitting.length; i++) {
            var GroupOne = Knitting[i][0]+"#"+Knitting[i][1]+"#"+Knitting[i][2];
            FinishingGsmGroup[GroupOne+"#"+Knitting[i][3]+"#"+Knitting[i][4]+"#"+Knitting[i][5]+"#"+Knitting[i][6]] = Knitting[i][8];
        }
        //console.log(FinishingGsmGroup,'FinishingGsmGroup');
        var GarmentParts = [];
        for(var i = 0; i < CumulativeSixthATbl.length; i++) {
            var maindata = $("#fabricprogrammain_"+i).jexcel('getRowData');
            //console.log(CumulativeSixthATbl[i],'CumulativeSixthATbl i');
            var UnSplittedColor = CumulativeSixthATbl[i][2]; var SplitGarmentParts = []; var ArrSecondLevelColor = [];
            //console.log(UnSplittedColor,'UnSplittedColor');
            if(UnSplittedColor.indexOf('-') >= 0) {
                ArrSecondLevelColor = UnSplittedColor.split('-');
                for(var c = 0; c < ArrSecondLevelColor.length; c++) {
                    var CurrentGroup        = CumulativeSixthATbl[i][0]+"#"+CumulativeSixthATbl[i][1]+"#"+
                        jsTrim(ArrSecondLevelColor[c]);
                    SplitGarmentParts = fnGroupArrayValue(KnitGarmentPartsCount[CurrentGroup]);
                    var Units = $("#subchild_"+i+"_"+c).jexcel('getColumnData',6);
                    //console.log(Units,'Units');
                    var ReqFabWgt = $("#subchild_"+i+"_"+c).jexcel('getRowData',3).splice(7,8);
                    //console.log(ReqFabWgt,'ReqFabWgt');
                    var PlanFabWgt = $("#subchild_"+i+"_"+c).jexcel('getRowData',5).splice(7,8);
                    //console.log(PlanFabWgt,'PlanFabWgt');
                    var FinDia = $("#subchild_"+i+"_"+c).jexcel('getRowData',6).splice(7,8);
                    console.log(FinDia,'FinDia in if cond.');
                    console.log("#subchild_"+i+"_"+c,'iandc');
                    //console.log(SplitGarmentParts,'SplitGarmentParts');
                    var GpandFabricdetails = SplitGarmentParts[0].split('#');
                    var part = GpandFabricdetails[0];
                    var fabricblend = GpandFabricdetails[1];
                    var fabriccontent = GpandFabricdetails[2];
                    var fabricname = GpandFabricdetails[3];
                    for(var f = 0; f < FinDia.length; f++) {
                        if (FinDia[f] != "" && Number(ReqFabWgt[f]) && Number(PlanFabWgt[f])) {
                            //console.log(Units[6],'units 6');
                            RequirementDetailsTbl.push([maindata[0], maindata[1], jsTrim(ArrSecondLevelColor[c]),
                                maindata[3], part, fabricblend,fabriccontent,fabricname, FinDia[f], Units[6],
                                ReqFabWgt[f], PlanFabWgt[f]]);
                        }
                    }
                }
                GarmentParts = [];
            }
            else {
                var CurrentGroup = CumulativeSixthATbl[i][0] + "#" + CumulativeSixthATbl[i][1] + "#" +
                    CumulativeSixthATbl[i][2];
                GarmentParts = fnGroupArrayValue(KnitGarmentPartsCount[CurrentGroup]);
                for (var x = 0; x < GarmentParts.length; x++) {
                    var Units = $("#subchild_" + i + "_" + x).jexcel('getColumnData', 6);
                    //console.log(Units,'Units in else');
                    var ReqFabWgt = $("#subchild_" + i + "_" + x).jexcel('getRowData', 3).splice(7, 8);
                    var PlanFabWgt = $("#subchild_" + i + "_" + x).jexcel('getRowData', 5).splice(7, 8);
                    var FinDia = $("#subchild_" + i + "_" + x).jexcel('getRowData', 6).splice(7, 8);
                    console.log(FinDia,'FinDia in else ');
                    var GpandFabricdetails = GarmentParts[x].split('#');
                    var part = GpandFabricdetails[0];
                    var fabricblend = GpandFabricdetails[1];
                    var fabriccontent = GpandFabricdetails[2];
                    var fabricname = GpandFabricdetails[3];
                    for (var f = 0; f < FinDia.length; f++) {
                        if (FinDia[f] != "" && Number(ReqFabWgt[f]) && Number(PlanFabWgt[f])) {
                            RequirementDetailsTbl.push([maindata[0], maindata[1], maindata[2], maindata[3], part,
                                fabricblend,fabriccontent,fabricname, FinDia[f], Units[6], ReqFabWgt[f],
                                PlanFabWgt[f]]);
                        }
                    }
                }
                var CccGroup = CumulativeSixthATbl[i][0] + "#" + CumulativeSixthATbl[i][1] + "#" +
                    CumulativeSixthATbl[i][2];

            }
        }

        console.log(RequirementDetailsTbl,'RequirementDetailsTbl');
        var Two = [], GroupsArr = [], FinalDataCumulative = [], FininhingDia = [], GlbUniqueGroup = [], GroupFive = [], UnitOfMeasure = [];
        var One = {};
        jQuery.each(RequirementDetailsTbl,function (index,el) {
            var GroupSix = el[0]+"#"+el[1]+"#"+el[2]+"#"+el[4]+"#"+el[5]+"#"+el[6]+"#"+el[7]+"#"+el[8]+"#"+el[9];
            var GroupFiveWoutSizeSpec = el[0]+"#"+el[1]+"#"+el[2]+"#"+el[4]+"#"+el[5]+"#"+el[6]+"#"+el[7];
            var GroupId             = jQuery.inArray(GroupSix, GroupsArr);
            //console.log(GroupId,'GroupId');
            if(GroupId === -1) {
                GroupsArr.push(GroupSix);
            }
            //console.log(GroupsArr,'GroupsArr');
            console.log(GroupSix,'GroupSix');
            console.log(el[10],'el 10');
            One = fnPopulateValueArray(One,GroupSix,el[10]);
            Two = fnPopulateValueArray(Two,GroupSix,el[11]);
            FininhingDia = fnPopulateValueArray(FininhingDia,GroupSix,el[8]);
            UnitOfMeasure[GroupFiveWoutSizeSpec] = el[9];
        });
        console.log(One,'One'); console.log(GroupsArr,'GroupsArr');
        for(var b = 0; b < GroupsArr.length; b++) {
            console.log(b,'b in GroupsArr loop ');
            var KeyVal = GroupsArr[b];
            console.log(KeyVal,'KeyVal');
            console.log(One[KeyVal],'One[KeyVal]');
            var Oneresu = fnSumSizeArrayValue(One[KeyVal]);
            var Tworesu = fnSumSizeArrayValue(Two[KeyVal]);
            var data = KeyVal.split('#');
            console.log(Oneresu,'Oneresu'); console.log(Tworesu,'Tworesu');
            FinalDataCumulative.push([data[0],data[1],data[2],data[3],data[4],data[5],data[6],"","","",data[7],"",Oneresu,Tworesu]);
        }

        console.log(FinalDataCumulative,'FinalDataCumulative');

        var FilterFinal = {}; var FinalDataCumulativeNew = [], UniqueFirstFive = [];
        jQuery.each(FinalDataCumulative,function (index,el) {
            console.log(el[12],'el 12');
            console.log(el[13],'el 13');
            FilterFinal = fnPopulateValueArray(FilterFinal,el[0]+"#"+el[1]+"#"+el[2]+"#"+el[3]+"#"+el[4]+"#"+el[5]+"#"+el[6],el[10]+"|#|"+el[12]+"|#|"+el[13]);
        });

        console.log(FilterFinal,'FilterFinal');
        jQuery.each(FilterFinal,function (index,el) {
            console.log(el,'el splitting sep');
            var SplitAll = el.split('|-|'); var ReqFabTotal = 0; var ReqPlanTotal = 0;
            //console.log(SplitAll,'SplitAll');
            for(var i = 0; i < SplitAll.length; i++) {
                var CccGarmentpart = index.split('#');
                //console.log(SplitAll[i],'SplitAll [i]');
                if(SplitAll[i] != "undefined" && SplitAll[i] != "") {
                    var Remaining = SplitAll[i].split('|#|');
                    //console.log(Remaining,'Remaining');
                    var FirstFive = CccGarmentpart[0]+"#"+CccGarmentpart[1]+"#"+CccGarmentpart[2]+"#"+CccGarmentpart[3]+"#"+CccGarmentpart[4]+"#"+CccGarmentpart[5]+
                        "#"+CccGarmentpart[6];
                    var FinGsm = FinishingGsmGroup[FirstFive];
                    ReqFabTotal += Number(Remaining[1]);
                    ReqPlanTotal += Number(Remaining[2]);
                    //console.log(UniqueFirstFive,'UniqueFirstFive',i,'i');
                    //console.log(FirstFive,'FirstFive',i,'i');
                    var ycdtarr = GlbYarnCountDyeingType[CccGarmentpart[0]+"#"+CccGarmentpart[1]+"#"+CccGarmentpart[2]+"#"+CccGarmentpart[3]];
                    var ycdtsplit = ycdtarr.split('|#|');
                    var yc = ycdtsplit[0];
                    var dt = ycdtsplit[1];
                    //console.log(yc,'yc',dt,'dt');
                    if(jQuery.inArray(FirstFive,UniqueFirstFive) === -1) {
                        var rfw = Number(Remaining[1]);
                        var pfw = Number(Remaining[2]);
                        //console.log(CccGarmentpart[5],'CccGarmentpart 5');
                        var um = UnitOfMeasure[CccGarmentpart[0]+"#"+CccGarmentpart[1]+"#"+CccGarmentpart[2]+"#"+CccGarmentpart[3]+"#"+CccGarmentpart[4]+"#"+
                        CccGarmentpart[5]+"#"+CccGarmentpart[6]];
                        UniqueFirstFive.push(FirstFive);
                        console.log(Remaining[0],'Remaining[0] in if');

                        FinalDataCumulativeNew.push([CccGarmentpart[0] ,CccGarmentpart[1],CccGarmentpart[2],CccGarmentpart[3],CccGarmentpart[4],
                            CccGarmentpart[5],CccGarmentpart[6],yc,FinGsm,dt,Remaining[0],um,rfw.toFixed(3),pfw.toFixed(3)]);

                        if(CccGarmentpart[2].indexOf(':') >= 0) {
                            var colorsplitforfabric = CccGarmentpart[2].split(':');
                            for (var cs = 0; cs < colorsplitforfabric.length; cs++) {
                                GlbFabricDyeingPgm = fnPopulateValueArray(GlbFabricDyeingPgm, CccGarmentpart[0] + "#" + CccGarmentpart[1] + "#" +jsTrim(colorsplitforfabric[cs]),
                                    [CccGarmentpart[3], CccGarmentpart[4], CccGarmentpart[5], CccGarmentpart[6], FinGsm, "","","",Remaining[0], um, pfw.toFixed(3)]);
                            }
                        }
                        else {
                            GlbFabricDyeingPgm = fnPopulateValueArray(GlbFabricDyeingPgm, CccGarmentpart[0] + "#" + CccGarmentpart[1] + "#" +CccGarmentpart[2],
                                [CccGarmentpart[3], CccGarmentpart[4], CccGarmentpart[5], CccGarmentpart[6], FinGsm, "","","",Remaining[0], um, pfw.toFixed(3)]);
                        }
                    }
                    else {
                        var rfw = Number(Remaining[1]);
                        var pfw = Number(Remaining[2]);
                        console.log(Remaining[0],'Remaining[0] in else');
                        FinalDataCumulativeNew.push(["","","","","","","","","","",Remaining[0],"",rfw.toFixed(3),pfw.toFixed(3)]);

                        if(CccGarmentpart[2].indexOf(':') >= 0) {
                            var colorsplitforfabricelse = CccGarmentpart[2].split(':');
                            for (var s = 0; s < colorsplitforfabricelse.length; s++) {
                                GlbFabricDyeingPgm = fnPopulateValueArray(GlbFabricDyeingPgm,CccGarmentpart[0]+"#"+CccGarmentpart[1]+"#"+jsTrim(colorsplitforfabricelse[s]),
                                    ["","","","","","","","",Remaining[0],"",pfw.toFixed(3)]);
                            }
                        }
                        else {
                            GlbFabricDyeingPgm = fnPopulateValueArray(GlbFabricDyeingPgm,CccGarmentpart[0]+"#"+CccGarmentpart[1]+"#"+CccGarmentpart[2],
                                ["","","","","","","","",Remaining[0],"",pfw.toFixed(3)]);
                        }
                    }
                }
            }

            //fabricDyeingPgm.push(["","","","","","","","","","","","",ReqFabTotal.toFixed(3),ReqPlanTotal.toFixed(3)]);
            FinalDataCumulativeNew.push(["","","","","","","","","","","","",ReqFabTotal.toFixed(3),ReqPlanTotal.toFixed(3)]);
        });

        $("#ConsolidatedReqData").jexcel({
            colHeaders: ["Combo","Component","Color","Garment<br/>Part","Fabric<br/>Blend<br/>(%)","Fabric Content","Fabric Name","Yarn<br/>Count","Fin. GSM","Dyeing Type","Fin. DIA / DIM (W * H)","Unit Of<br/>Measure","Req. Fab. Wgt. (Kgs.)"
                ,"Plan. Fab. Wgt. (Kgs.)"],
            colWidths: [100,100,100,100,60,100,100,60,80,90,70,70,100,100],
            columns: [
                {type: 'text', wordWrap: true, readOnly: true},
                {type: 'text', wordWrap: true, readOnly: true},
                {type: 'text', wordWrap: true, readOnly: true},
                {type: 'text', wordWrap: true, readOnly: true},
                {type: 'text', wordWrap: true, readOnly: true},
                {type: 'text', wordWrap: true, readOnly: true},
                {type: 'text', wordWrap: true, readOnly: true},
                //{type: 'dropdown', source: JSON.parse(GlbYarnCount), wordWrap: true},
                {type: 'text', wordWrap: true, readOnly: true},
                {type: 'text', wordWrap: true, readOnly: true},
                {type: 'text', wordWrap: true, readOnly: true},
                //{type: 'dropdown', source: GlbDyeingType, wordWrap: true},
                {type: 'text', wordWrap: true, readOnly: true},
                {type: 'dropdown', source: GlbUnitsOfMeasureArr, readOnly: true},
                {type: 'text', wordWrap: true, readOnly: true},
                {type: 'text', wordWrap: true, readOnly: true},
            ],
            data: FinalDataCumulativeNew
        });

        MakePostRequest(base_path+'fabricprogramvtwo/saveItemizedFabRequirementDetails',GlbParam+"&d="+JSON.stringify(FinalDataCumulativeNew)+"&enqid="+enquiryid,
        'json',fnSaveItemizedFabRequirementDetailsRes);

    }

    function fnSaveItemizedFabRequirementDetailsRes(data) {
        console.log(data,'data');
        if (data != '') {
            if (data.errcode == '404') {
                fnCallSessionExpire();
                return false;
            } else if (data.errcode == -1) {
                //$('#ErrfrmBasicTestingA').text(data.msg);
                return false;
            } else if (data.errcode == 1) {

                fnRedirectPageTimeOut(base_path +'fabricprogramvtwo/part_two_fabricdetail/' +HashEnquiryId);
            }
        }
    }

    function FnYarnDyeColorSplit_SDBSplit() {
        var Knitting = $("#FabricDetailsKnit").jexcel('getData'); var FinishingGsmGroup = [];
        for(var i = 0; i < Knitting.length; i++) {
            var GroupOne = Knitting[i][0]+"#"+Knitting[i][1]+"#"+Knitting[i][2]+"#"+Knitting[i][3]+"#"+Knitting[i][4]+"#"+Knitting[i][5]+"#"+Knitting[i][6];
            FinishingGsmGroup[GroupOne] = Knitting[i][7];
        }
        var ConsolidatedReqData = $("#ConsolidatedReqData").jexcel('getData');
        var YarnDyeRowId = 0, SDBRowId = 0, YarnDyeingItemizedColorWise = [], morethan1CountOrContentSplitData = [];
        for(var i = 0; i < ConsolidatedReqData.length; i++) {
            if (ConsolidatedReqData[i][9] === "YD") {
                YarnDyeRowId = i;
            }
            if (ConsolidatedReqData[i][9] === "SDB") {
                SDBRowId = i;
            }
            if(SDBRowId < ConsolidatedReqData.length) {
                SDBRowId = ConsolidatedReqData.length - 1;
            }
            else {
                SDBRowId++;
            }
            if(ConsolidatedReqData[SDBRowId][0] == "") {
                var PlanfabSubTotalSDB = ConsolidatedReqData[SDBRowId][13];
            }
            if(YarnDyeRowId < ConsolidatedReqData.length) {
                YarnDyeRowId = ConsolidatedReqData.length - 1;
            }
            else {
                YarnDyeRowId++;
            }
            if(ConsolidatedReqData[YarnDyeRowId][0] == "") {
                var PlanfabSubTotalYD = ConsolidatedReqData[YarnDyeRowId][13];
            }
            if (ConsolidatedReqData[i][7].indexOf('/') >= 0) {
                var yarncountSplitArr = ConsolidatedReqData[i][7].split('/');
                //console.log(yarncountSplitArr,'yarncountSplitArr');
                for (var y = 0; y < yarncountSplitArr.length; y++) {
                    console.log(yarncountSplitArr[y],'yarncountSplitArr y');
                    var ConsoliData = ConsolidatedReqData[i];
                    //console.log(ConsoliData, 'ConsoliData');
                    if (ConsoliData[2].indexOf(':') >= 0) {
                        var ArrYarnDyeColor = ConsoliData[2].split(':');
                        //console.log(FinishingGsmGroup,'FinishingGsmGroup');
                        var FinGsm = FinishingGsmGroup[ConsolidatedReqData[i][0] + "#" + ConsolidatedReqData[i][1] + "#" + ConsoliData[2] + "#" + ConsolidatedReqData[i][3] +
                        "#" + ConsolidatedReqData[i][4] + "#" + ConsolidatedReqData[i][5] + "#" + ConsolidatedReqData[i][6]];
                        console.log(ConsolidatedReqData,'ConsolidatedReqData');
                        for (var c = 0; c < ArrYarnDyeColor.length; c++) {
                            morethan1CountOrContentSplitData.push([ConsolidatedReqData[i][0], ConsolidatedReqData[i][1], ArrYarnDyeColor[c], ConsolidatedReqData[i][3],
                                ConsolidatedReqData[i][4], ConsolidatedReqData[i][5], ConsolidatedReqData[i][6],jsTrim(yarncountSplitArr[y]),ConsolidatedReqData[i][9], "","",
                                "",PlanfabSubTotalYD,""]);
                        }
                    }
                    else {
                        var FinGsm = FinishingGsmGroup[ConsolidatedReqData[i][0] + "#" + ConsolidatedReqData[i][1] + "#" + ConsoliData[2] + "#" + ConsolidatedReqData[i][3] +
                        "#" + ConsolidatedReqData[i][4]];
                        console.log(FinGsm,'FinGsm');
                        morethan1CountOrContentSplitData.push([ConsolidatedReqData[i][0], ConsolidatedReqData[i][1], ConsoliData[2], ConsolidatedReqData[i][3],
                            ConsolidatedReqData[i][4], ConsolidatedReqData[i][5], ConsolidatedReqData[i][6],jsTrim(yarncountSplitArr[y]),ConsolidatedReqData[i][9], "","",
                            "",PlanfabSubTotalYD,""]);
                    }
                    /*                  var SDB = ConsolidatedReqData[i];
                                        if (SDB[2].indexOf('-') >= 0) {
                                                var ArrSDBColor = SDB[2].split('-');
                                                var FinGsm = FinishingGsmGroup[ConsolidatedReqData[i][0] + "#" + ConsolidatedReqData[i][1] + "#" + SDB[2] + "#" + ConsolidatedReqData[i][3] +
                                                "#" + ConsolidatedReqData[i][4] + "#" + ConsolidatedReqData[i][5] + "#" + ConsolidatedReqData[i][6]];
                                                for (var sdbc = 0; sdbc < ArrSDBColor.length; sdbc++) {
                                                    morethan1CountOrContentSplitData.push([ConsolidatedReqData[i][0], ConsolidatedReqData[i][1], ArrSDBColor[sdbc], ConsolidatedReqData[i][3],
                                                        ConsolidatedReqData[i][4], ConsolidatedReqData[i][5], ConsolidatedReqData[i][6], ArrYarnCount[c], FinGsm, ConsolidatedReqData[i][9],
                                                        PlanfabSubTotalSDB, "", "", ""]);
                                                }
                                        }*/
                }
            }
            //else {
            if(ConsolidatedReqData[i][9] == "YD") {
                if (ConsolidatedReqData[i][2].indexOf(':') >= 0) {
                    var ArrYarnDyeColor = ConsolidatedReqData[i][2].split(':');
                    //console.log(FinishingGsmGroup,'FinishingGsmGroup');
                    var FinGsm = FinishingGsmGroup[ConsolidatedReqData[i][0] + "#" + ConsolidatedReqData[i][1] + "#" + ConsolidatedReqData[i][2] + "#" +
                    ConsolidatedReqData[i][3] + "#" + ConsolidatedReqData[i][4] + "#" + ConsolidatedReqData[i][5] + "#" + ConsolidatedReqData[i][6]];
                    //console.log(FinGsm,'FinGsm');
                    for (var c = 0; c < ArrYarnDyeColor.length; c++) {
                        YarnDyeingItemizedColorWise.push([ConsolidatedReqData[i][0], ConsolidatedReqData[i][1], ArrYarnDyeColor[c], ConsolidatedReqData[i][3],
                            "","","Yarn Spl Req",ConsolidatedReqData[i][7],ConsolidatedReqData[i][9],"","","",PlanfabSubTotalYD,""]);
                    }
                }
                else {
                    var FinGsm = FinishingGsmGroup[ConsolidatedReqData[i][0] + "#" + ConsolidatedReqData[i][1] + "#" + ConsolidatedReqData[i][2] + "#" + ConsolidatedReqData[i][3] +
                    "#" + ConsolidatedReqData[i][4]];
                    //console.log(FinGsm,'FinGsm');
                    YarnDyeingItemizedColorWise.push([ConsolidatedReqData[i][0], ConsolidatedReqData[i][1], ConsolidatedReqData[i][2], ConsolidatedReqData[i][3],
                        "","","Yarn Spl Req",ConsolidatedReqData[i][7],ConsolidatedReqData[i][9],"","","",PlanfabSubTotalYD,""]);
                }
                console.log(YarnDyeingItemizedColorWise,'YarnDyeingItemizedColorWise');
                //YarnDyeingItemizedColorWise.push();
            }
            //}
        }
        if(ItemizedyarnWgtCalc != "") {
            //console.log(ItemizedyarnWgtCalc,'ItemizedyarnWgtCalc inside if');
            var savedMorethan1CountOrContentSplitData = JSON.parse(ItemizedyarnWgtCalc);
        }
        //console.log(morethan1CountOrContentSplitData,'morethan1CountOrContentSplitData');
        //console.log(savedMorethan1CountOrContentSplitData,'savedMorethan1CountOrContentSplitData');
        if(savedMorethan1CountOrContentSplitData.length > 0) {
            morethan1CountOrContentSplitData = JSON.parse(ItemizedyarnWgtCalc);
        }
        console.log(morethan1CountOrContentSplitData,'morethan1CountOrContentSplitData');
        $("#morethan1CountOrContentSplit").jexcel({
            //data:SDBItemizedColorWise, morethan1CountOrContentSplit
            data:morethan1CountOrContentSplitData,
            colHeaders: ["Combo","Component","Color","Garment<br/>Parts",
                "Yarn<br/>Blend<br/>(%)",
                "Yarn Content",
                "Yarn Spl. Request<br/>If any",
                "Yarn<br/>Count",
                "Dyeing<br/>Type",
                "No. of Feed.<br/>Per Repeat",
                "No. of Feed.<br/>per Y-Count",
                "Yarn<br/>Count (%)",
                "Plan Fab.<br/>Wgt. Sub<br/>total (Kgs.)",
                "Req. Yarn<br/>Wgt.<br/>(Kgs.)"],
            colWidths: [100,100,100,80,60,100,100,60,70,100,90,70,70,70],
            columns: [
                {type: 'text', wordWrap: true, readOnly: true},
                {type: 'text', wordWrap: true, readOnly: true},
                {type: 'text', wordWrap: true, readOnly: true},
                {type: 'text', wordWrap: true, readOnly: true},
                {type: 'dropdown', wordWrap: true, source: GlbYarnBlendPercent},
                {type: 'dropdown', wordWrap: true, source : YarnContentArr},
                {type: 'text', wordWrap: true, readOnly: true},
                {type: 'text', wordWrap: true, readOnly: true},
                {type: 'text', wordWrap: true, readOnly: true},
                {type: 'text', wordWrap: true},
                {type: 'text', wordWrap: true},
                {type: 'text', wordWrap: true, readOnly: true},
                {type: 'text', wordWrap: true},
                {type: 'text', wordWrap: true, readOnly: true}
            ]
        });
        var yarncountpercent = 0; var PlanFabWgt = 0, NoofFeederPerRepeat = 0, FeederPerYCount = 0;
        $("#morethan1CountOrContentSplit").jexcel('updateSettings',{
            table:function (instance, cell, col, row, val, id) {
                if(col == 9) {
                    NoofFeederPerRepeat = Number($(cell).html());
                }
                if(col == 10) {
                    FeederPerYCount = Number($(cell).html());
                }
                if(col == 11) {
                    var MulFeederPerYCount = FeederPerYCount * 100;
                    yarncountpercent = Number(MulFeederPerYCount) / NoofFeederPerRepeat;
                    $(cell).text(yarncountpercent);
                    //CountPercent = Number($(cell).html());
                }
                if(col == 12) {
                    PlanFabWgt = Number($(cell).html());
                }
                if(col == 13) {
                    var ColorPercentDivision = yarncountpercent / 100;
                    var ReqyarnWgt = PlanFabWgt * ColorPercentDivision;
                    $(cell).text(ReqyarnWgt.toFixed(3))
                }
            }
        });

        /*
                var moreThan1countYarnCountCol = $("#ConsolidatedReqData").jexcel('getColumnData',7);
                var moreThan1countDyeingCol = $("#ConsolidatedReqData").jexcel('getColumnData',9);
                MakePostRequest(base_path+'fabricprogramvtwo/saveFabReqDetails',GlbParam+"&enqid="+enquiryid+"&yc="+JSON.stringify(moreThan1countYarnCountCol)+
                    "&dt="+JSON.stringify(moreThan1countDyeingCol),'json',fnSaveMOrethanOneCountRes);

                function fnSaveMOrethanOneCountRes(data) {
                    console.log(data,'data');
                }
        */
        if(ArrYarnDyeing != "") {
            var SavedYarnDyeingItemizedColorWise = JSON.parse(ArrYarnDyeing);
        }
        if(SavedYarnDyeingItemizedColorWise.length > 0) {
            YarnDyeingItemizedColorWise = SavedYarnDyeingItemizedColorWise;
        }
        console.log(YarnDyeingItemizedColorWise,'YarnDyeingItemizedColorWise');
        $("#YarnDyeColorSplit").jexcel({
            data:YarnDyeingItemizedColorWise,
            colHeaders: ["Combo","Component","Color","Garment<br/>Parts",
                "Yarn<br/>Blend (%)",
                "Yarn<br/>Content",
                "Yarn<br/>Spl. Req.",
                "Yarn<br/>Count",
                "Dyeing<br/>Type",
                "No. of Feed.<br/>Per Repeat",
                "No. of Feed.<br/>per Color",
                "Yarn Count<br/>(%)",
                "Plan Fab.<br/>Wgt. Subtotal<br/>(Kgs.)",
                "Req. Yarn<br/>Wgt. (Kgs.)"],
            colWidths: [100,100,100,80,60,100,100,60,60,80,90,80,100,90],
            columns: [
                {type: 'text', wordWrap: true, readOnly: true},
                {type: 'text', wordWrap: true, readOnly: true},
                {type: 'text', wordWrap: true, readOnly: true},
                {type: 'text', wordWrap: true, readOnly: true},
                {type: 'dropdown', wordWrap: true, source : GlbYarnBlendPercent},
                {type: 'dropdown', wordWrap: true, source: YarnContentArr },
                {type: 'text', wordWrap: true, readOnly: true},
                {type: 'text', wordWrap: true, readOnly: true},
                {type: 'text', wordWrap: true, readOnly: true},
                {type: 'text', wordWrap: true},
                {type: 'text', wordWrap: true},
                {type: 'text', wordWrap: true},
                {type: 'text', wordWrap: true,readOnly: true},
                {type: 'text', wordWrap: true, readOnly: true},
            ]
        });
        var NoofFeedPerRepeat = 0; var NoofFeedPerYColor = 0; var PlanFabWgt = 0; var ReqYarnWgt = 0; var YarnCountPercent = 0;
        $("#YarnDyeColorSplit").jexcel('updateSettings',{
            table:function (instance, cell, col, row, val, id) {
                if(col == 0) {
                    NoofFeedPerRepeat = 0; NoofFeedPerYColor = 0; PlanFabWgt = 0; ReqYarnWgt = 0; YarnCountPercent = 0;
                }

                if(col == 9) {
                    NoofFeedPerRepeat = Number($(cell).html());
                }
                if(col == 10) {
                    NoofFeedPerYColor = Number($(cell).html());
                }
                if(col == 11) {
                    var MulNoofFeedPerYColor = NoofFeedPerYColor * 100;
                    YarnCountPercent = MulNoofFeedPerYColor / NoofFeedPerRepeat;
                    $(cell).text(YarnCountPercent.toFixed(3));
                }
                if(col == 12) {
                    PlanFabWgt = Number($(cell).html());
                    //console.log(PlanFabWgt,'PlanFabWgt');
                }

                if(col == 13) {
                    var res = PlanFabWgt * YarnCountPercent;
                    ReqYarnWgt = res / 100;
                    //console.log(ReqYarnWgt,'ReqYarnWgt');
                    $(cell).text(ReqYarnWgt.toFixed(3));
                }
            }
        });
    }

    function FnItemizedYarnProgram() {
        try {
            var yarnsplreq = $("#FabricDetailsKnit").jexcel('getColumnData',10);
            var ConsolidatedReqData = $("#ConsolidatedReqData").jexcel('getData');
            var PlanFabSubTotals = [], FirstEightGroup = [], ItemizedYarnProgramData = [], YDSubtotalandData = [];
            for (var i = 0; i < ConsolidatedReqData.length; i++) {
                if(ConsolidatedReqData[i][7] != "" && ConsolidatedReqData[i][8] != "" && ConsolidatedReqData[i][9] != "") {
                    FirstEightGroup.push(ConsolidatedReqData[i][0]+"#"+ConsolidatedReqData[i][1]+"#"+ConsolidatedReqData[i][2]+"#"+ConsolidatedReqData[i][3]+"#"+
                        ConsolidatedReqData[i][4]+"#"+ConsolidatedReqData[i][5]+"#"+ConsolidatedReqData[i][6]+"#"+ConsolidatedReqData[i][7]+"#"+ConsolidatedReqData[i][8]+
                        "#"+ConsolidatedReqData[i][9]);
                }
                if(ConsolidatedReqData[i][10] == "") {
                    PlanFabSubTotals.push(ConsolidatedReqData[i][13]);
                }
            }
            var YarnDyeingData = $("#YarnDyeColorSplit").jexcel('getData');
            var morethan1CountOrContentSplit = $("#morethan1CountOrContentSplit").jexcel('getData');
            for(var i = 0; i < YarnDyeingData.length; i++) {
                ItemizedYarnProgramData.push([YarnDyeingData[i][0],YarnDyeingData[i][1],YarnDyeingData[i][2],YarnDyeingData[i][3],YarnDyeingData[i][4],
                    YarnDyeingData[i][5],YarnDyeingData[i][6],YarnDyeingData[i][7],YarnDyeingData[i][8],YarnDyeingData[i][9],"",YarnDyeingData[i][10],"","",""]);
            }
            //console.log(ItemizedYarnProgramData,'ItemizedYarnProgramData yarndyeing');
            for(var j = 0; j < morethan1CountOrContentSplit.length; j++) {
                //var fgsm = morethan1CountOrContentSplit[j][9];
                ItemizedYarnProgramData.push([morethan1CountOrContentSplit[j][0],morethan1CountOrContentSplit[j][1],morethan1CountOrContentSplit[j][2],
                    morethan1CountOrContentSplit[j][3],morethan1CountOrContentSplit[j][4],morethan1CountOrContentSplit[j][5],morethan1CountOrContentSplit[j][6],
                    morethan1CountOrContentSplit[j][8],morethan1CountOrContentSplit[j][9],"","","","","",""]);
            }
            //console.log(ItemizedYarnProgramData,'ItemizedYarnProgramData more than 1 count');
            for(var i = 0; i < FirstEightGroup.length; i++) {
                var SplittedFirstEight = FirstEightGroup[i].split("#");
                //console.log(SplittedFirstEight,'SplittedFirstEight');
                if(SplittedFirstEight[9] === "FD") {
                    ItemizedYarnProgramData.push([SplittedFirstEight[0], SplittedFirstEight[1], SplittedFirstEight[2], SplittedFirstEight[3],
                        SplittedFirstEight[4], SplittedFirstEight[5], SplittedFirstEight[6], SplittedFirstEight[7], SplittedFirstEight[8],SplittedFirstEight[9],"",
                        PlanFabSubTotals[i], "", "", ""]);
                }
            }
            if(ArrItemYarnPgm != "") {
                var savedItemizedYarnProgramData = JSON.parse(ArrItemYarnPgm);
            }
            if(savedItemizedYarnProgramData.length > 0) {
                ItemizedYarnProgramData = savedItemizedYarnProgramData;
            }
            //console.log(ItemizedYarnProgramData,'ItemizedYarnProgramData at last');
            //console.log(yarnsplreq,'yarnsplreq');
            $("#ItemizedYarnProgram").jexcel({
                colHeaders: ["Combo", "Component", "Color", "Garment<br/>Parts", "Fabric<br/>Blend<br/>(%)","Fabric Content","Fabric Name",
                    "Yarn<br/>Count",
                    "Dyeing Type",
                    "Yarn Spl. Req.<br/>If Any",
                    "Yarn <br/>Purchase<br/>Type",
                    "Plan Fab.<br/>Wgt. Subtotal<br/>(Kgs.)",
                    "Lycra<br/>(%)",
                    "Lycra Wgt.<br/>(Kgs.)",
                    "Req. Yarn<br/>Wgt.<br/>(Kgs.)"],
                colWidths: [100, 100, 100, 80, 60, 100, 100, 60, 80, 90, 70, 100, 50, 70, 70],
                columns: [
                    {type: "text", readOnly: true},
                    {type: "text", readOnly: true},
                    {type: "text", readOnly: true},
                    {type: "text", readOnly: true},
                    {type: "text", readOnly: true},
                    {type: "text", readOnly: true},
                    {type: "text", readOnly: true},
                    {type: "text", readOnly: true},
                    {type: "text", readOnly: true},
                    {type: "dropdown",source: yarnsplreq },
                    {type: "dropdown", source: JSON.parse(GlbYarnPurchaseType)},
                    {type: "text", readOnly: true},
                    {type: "text"},
                    {type: "text", readOnly: true},
                    {type: "text", readOnly: true},
                ],
                data: ItemizedYarnProgramData
            });
            var PlanFabWgt = 0, Lycra = 0, LycraWgt = 0, ReqYarnWgt = 0;
            $("#ItemizedYarnProgram").jexcel('updateSettings', {
                table: function (instance, cell, col, row, val, id) {
                    if (col == 0) {
                        PlanFabWgt = 0;
                        Lycra = 0;
                        LycraWgt = 0;
                        ReqYarnWgt = 0;
                    }
                    if (col == 11) PlanFabWgt = Number($(cell).html());
                    if (col == 12) Lycra = Number($(cell).html());
                    if (col == 13) {
                        var LycraPercent = Lycra / 100;
                        LycraWgt = PlanFabWgt * LycraPercent;
                        //console.log(LycraWgt, 'LycraWgt');
                        $(cell).text(LycraWgt.toFixed(3));
                    }
                    if (col == 14) { // Last
                        var ReqYarnWgt = PlanFabWgt - LycraWgt;
                        //console.log(ReqYarnWgt, 'ReqYarnWgt');
                        $(cell).text(ReqYarnWgt.toFixed(3));
                    }
                    var ArrPlanFabWgtSubtotalCol = $("#ItemizedYarnProgram").jexcel('getColumnData',11);
                    var ArrLycraWgtTotal = $("#ItemizedYarnProgram").jexcel('getColumnData',13);
                    var ArrReqYarnWgtSum = $("#ItemizedYarnProgram").jexcel('getColumnData',14);
                    //console.log(ArrPlanFabWgtSubtotalCol,'ArrPlanFabWgtSubtotalCol');
                    var PlanFabWgtSubtotalSum = ArrPlanFabWgtSubtotalCol.reduce(function (a,b) { return Number(a) + Number(b) },0);
                    var LycraWgtSum = ArrLycraWgtTotal.reduce(function (a,b) { return Number(a) + Number(b) },0);
                    var ReqYarnWgtSum = ArrReqYarnWgtSum.reduce(function (a,b) { return Number(a) + Number(b) },0);
                    //console.log(PlanFabWgtSubtotalSum,'PlanFabWgtSubtotalSum');
                    $("#PlanFabWgtSubtotalSum").text(PlanFabWgtSubtotalSum.toFixed(3));
                    $("#LycraWgtSum").text(LycraWgtSum.toFixed(3));
                    $("#ReqYarnWgtSum").text(ReqYarnWgtSum.toFixed(3));
                }
            });
            //console.log(morethan1CountOrContentSplit,'morethan1CountOrContentSplit');
            var YarnDyeColorSplit = $("#YarnDyeColorSplit").jexcel('getData');
            //console.log(YarnDyeColorSplit,'YarnDyeColorSplit');
            MakePostRequest(base_path+'fabricprogramvtwo/saveItemizedyarnWgtCalc',GlbParam+"&yarnwgtcalc="+JSON.stringify(morethan1CountOrContentSplit)+"&yarndyeing="+
                JSON.stringify(YarnDyeColorSplit)+"&enqid="+enquiryid,'json',fnItemizedyarnWgtCalcRes);
            function fnItemizedyarnWgtCalcRes(data) {
                //console.log(data,'data');
            }
        }
        catch (e) {
            //console.log(e,'try catch');
        }
    }

    function FnCountWiseYarnReq() {
        var ItemizedYarnPgm = $("#ItemizedYarnProgram").jexcel('getData');
        //console.log(ItemizedYarnPgm,'ItemizedYarnPgm');
        //ItemizedYarnPgm.pop();
        var ActualRowId = 0, ArrRowNo = {}, YarnColorPopulate = [], YarnPurchaseTypePopulate = [], CountWiseYarnReqQtyData = [], ConsolForCountWiseYarnPlanFabWgt = [];
        var ConsolForCountWiseYarnLycraWgt = [], ConsolForCountWiseYarnReqYarnWgt = [], YarnColorArr = [];
        for(var i = 0; i < ItemizedYarnPgm.length; i++) {
            ActualRowId = i + 1;
            ArrRowNo = fnPopulateValueArray(ArrRowNo,ItemizedYarnPgm[i][7]+"#"+ItemizedYarnPgm[i][10],ActualRowId);
            ConsolForCountWiseYarnPlanFabWgt = fnPopulateValueArray(ConsolForCountWiseYarnPlanFabWgt,ItemizedYarnPgm[i][7]+"#"+ItemizedYarnPgm[i][10],ItemizedYarnPgm[i][11]);
            ConsolForCountWiseYarnLycraWgt = fnPopulateValueArray(ConsolForCountWiseYarnLycraWgt,ItemizedYarnPgm[i][7]+"#"+ItemizedYarnPgm[i][10],ItemizedYarnPgm[i][13]);
            ConsolForCountWiseYarnReqYarnWgt = fnPopulateValueArray(ConsolForCountWiseYarnReqYarnWgt,ItemizedYarnPgm[i][7]+"#"+ItemizedYarnPgm[i][10],ItemizedYarnPgm[i][14]);
            YarnPurchaseTypePopulate = fnPopulateValueArray(YarnPurchaseTypePopulate,ActualRowId,ItemizedYarnPgm[i][10]);
            YarnColorPopulate = fnPopulateValueArray(YarnColorPopulate,ItemizedYarnPgm[i][7],ActualRowId);
            YarnColorArr[ItemizedYarnPgm[i][7]+"#"+ItemizedYarnPgm[i][10]] = ItemizedYarnPgm[i][2];
        }
        //console.log(ArrRowNo,'ArrRowNo');
        var PlanFabWgtTotal = 0; var LycraWgtTotal = 0; var ReqYarnWgtTotal = 0;
        jQuery.each(ArrRowNo,function (index,rowsnos) {
            var ActualRow = rowsnos.substring(rowsnos.indexOf('-')+1);
            var YarnPurchType = index.substring(index.indexOf('#')+1);
            var YarnCount = index.substring(0,index.indexOf('#'));
            var PlanFabWgt = ConsolForCountWiseYarnPlanFabWgt[index];
            var LycraWgt = ConsolForCountWiseYarnLycraWgt[index];
            var ReqYarnWgt = ConsolForCountWiseYarnReqYarnWgt[index];
            var PlanFabWgtConsol = fnSumSizeArrayValue(PlanFabWgt);
            var LycraWgtConsol = fnSumSizeArrayValue(LycraWgt);
            var ReqYarnWgtConsol = fnSumSizeArrayValue(ReqYarnWgt);
            if(YarnPurchType === "Cot. Greige") {
                var YarnColor = "Greige";
            }
            else {
                var YarnColor = YarnColorArr[YarnCount+"#"+YarnPurchType];
            }
            PlanFabWgtTotal += PlanFabWgtConsol;
            LycraWgtTotal += LycraWgtConsol;
            ReqYarnWgtTotal += ReqYarnWgtConsol;
            //console.log(ActualRow,'ActualRow');
            CountWiseYarnReqQtyData.push([ActualRow,YarnCount,"","","",YarnPurchType,YarnColor,PlanFabWgtConsol.toFixed(3),LycraWgtConsol.toFixed(3),ReqYarnWgtConsol.toFixed(3)]);
        });
        CountWiseYarnReqQtyData.push(["","","","","","","Total",PlanFabWgtTotal.toFixed(3),LycraWgtTotal.toFixed(3),ReqYarnWgtTotal.toFixed(3)]);
        if(ArrConsCountWiseYarnReq != "") {
            var savedCountWiseYarnReqQtyData = JSON.parse(ArrConsCountWiseYarnReq);
        }
        if(savedCountWiseYarnReqQtyData.length > 0) {
            CountWiseYarnReqQtyData = savedCountWiseYarnReqQtyData;
        }
        $("#CountWiseYarnReqQty").jexcel({
            colHeaders: ["Itemd. Yarn Prog.<br/>Consolidated S.No.", "Yarn Count", "Yarn Blend<br/>(%)", "Yarn Content", "Yarn Special<br/>Request If Any", "Yarn Purchase<br/>Type",
                "Yarn Color", "Plan Fab. Wgt.<br/>Consol. (Kgs.)", "Lycra Wgt.<br/>(Kgs.)", "Yarn Req. Wgt.<br/>(Kgs.)"],
            colWidths: [170, 90, 80, 130, 120, 100, 190, 120, 100, 100],
            columns: [
                {type: "text", wordWrap: true},
                {type: "text", wordWrap: true},
                {type: "dropdown", source: ["90 %","100 %"], wordWrap: true},
                {type: "dropdown", source: ["Cotton","Polyester"], wordWrap: true},
                {type: "dropdown", source: ["Yes","No"], wordWrap: true},
                {type: "text", wordWrap: true},
                {type: "text", wordWrap: true},
                {type: "text", wordWrap: true},
                {type: "text", wordWrap: true},
                {type: "text", wordWrap: true},
            ],
            data: CountWiseYarnReqQtyData
        });

        MakePostRequest(base_path+'fabricprogramvtwo/saveItemizedyarnPgm',GlbParam+"&itemyarnpgm="+JSON.stringify(ItemizedYarnPgm)+"&enqid="+enquiryid,'json',
            fnSaveItemizedyarnPgmRes);

        function fnSaveItemizedyarnPgmRes(data) {
            console.log(data,'data');
        }

    }

    function FnYarnRequirementDetailsFinalTbl() {
        try {
            var CountWiseYarnReqQty = $("#CountWiseYarnReqQty").jexcel('getData');
            CountWiseYarnReqQty.pop();
            console.log(CountWiseYarnReqQty,'CountWiseYarnReqQty');
            var YarnRequirementDetailsFinalData = [], PlanFabWgtTotal = 0, LycraWgtTotal = 0, ReqYarnWgtTotal = 0;
            for(var i = 0; i < CountWiseYarnReqQty.length; i++) {
                PlanFabWgtTotal += Number(CountWiseYarnReqQty[i][7]);
                LycraWgtTotal += Number(CountWiseYarnReqQty[i][8]);
                ReqYarnWgtTotal += Number(CountWiseYarnReqQty[i][9]);
                YarnRequirementDetailsFinalData.push(["",CountWiseYarnReqQty[i][1],CountWiseYarnReqQty[i][2],CountWiseYarnReqQty[i][3],CountWiseYarnReqQty[i][4],"",
                    CountWiseYarnReqQty[i][5],CountWiseYarnReqQty[i][6],"","",CountWiseYarnReqQty[i][7],CountWiseYarnReqQty[i][8],CountWiseYarnReqQty[i][9]]);
            }
            YarnRequirementDetailsFinalData.push(["","","","","","","","","","Total",PlanFabWgtTotal,LycraWgtTotal,ReqYarnWgtTotal]);
            //console.log(YarnRequirementDetailsFinalData,'YarnRequirementDetailsFinalData');

            if(ArrYarnRequirementDetails != "") {
                var savedYarnRequirementDetailsFinalData = JSON.parse(ArrYarnRequirementDetails);
            }
            if(savedYarnRequirementDetailsFinalData.length > 0) {
                YarnRequirementDetailsFinalData = savedYarnRequirementDetailsFinalData;
            }

            $("#YarnRequirementDetailsFinalTbl").jexcel({
                colHeaders: ["Yarn - Vendor /<br/>Brand Details","Yarn Count","Yarn Blend<br/>(%)","Yarn Content","Yarn Spl.<br/>Request<br/>If Any","Yarn Grade",
                    "Yarn Purchase<br/>Type","Yarn Color","Yarn Product<br/>Code (vendor)","Yarn Color<br/>Code (vendor)","Plan Fab. Wgt.<br/>Consol. (Kgs.)","Lycra Wgt.<br/>(kgs.)",
                    "Req. Yarn Wgt.<br/>(Kgs.)"],
                colWidths: [120,80,80,90,80,90,100,100,100,100,100,100,100],
                columns: [
                    {type: "dropdown", source: ["KPR","Amarjothi","market Source"], wordWrap: true},
                    {type: "text", wordWrap: true},
                    {type: "text", wordWrap: true},
                    {type: "text", wordWrap: true},
                    {type: "text", wordWrap: true},
                    {type: "dropdown", source: ["Combed - Red Label","Combed"], wordWrap: true},
                    {type: "text", wordWrap: true},
                    {type: "text", wordWrap: true},
                    {type: "text", wordWrap: true},
                    {type: "text", wordWrap: true},
                    {type: "text", wordWrap: true},
                    {type: "text", wordWrap: true},
                    {type: "text", wordWrap: true},
                ],
                data: YarnRequirementDetailsFinalData
            });

            MakePostRequest(base_path+'fabricprogramvtwo/saveCountWiseYarnReqQty',GlbParam+"&countwiseyarnreqqty="+JSON.stringify(CountWiseYarnReqQty)+"&enqid="+enquiryid
                ,'json',fnSaveCountWiseYarnReqQtyRes);

            function fnSaveCountWiseYarnReqQtyRes(data) {
                console.log(data,'data');
            }
        }
        catch (e) {
            console.log(e,'catch exception');
        }
    }

    function saveYarnReqDetails() {
        var yarnReqDetails = $("#YarnRequirementDetailsFinalTbl").jexcel('getData');
        MakePostRequest(base_path+'fabricprogramvtwo/saveYarnReqDetails',GlbParam+"&yarnreqdetails="+JSON.stringify(yarnReqDetails)+"&enqid="+enquiryid,'json',saveYarnReqDetailsRes);
    }

    function saveYarnReqDetailsRes(data) {
        console.log(data,'data');
    }
    function multiDimensionalUnique(arr) {
        var uniques = [];
        var itemsFound = {};
        //console.log(arr,'arr');
        for(var i = 0, l = arr.length; i < l; i++) {
            var stringified = JSON.stringify(arr[i]);
            //console.log(stringified,'stringified');
            //console.log(itemsFound[stringified],'if itemsFound stringified conti ');
            if(itemsFound[stringified]) { continue; }
            uniques.push(arr[i]);
            itemsFound[stringified] = true;
            //console.log(itemsFound[stringified],'itemsFound stringified assign true');
        }
        return uniques;
    }

    function fabricdyeingprogram() {
        var fabricDyeingProgramGroupfinal = [], fabricDyeingProgram = [];
        var Knitting = $("#FabricDetailsKnit").jexcel('getData');
        console.log(Knitting,'Knitting');
        for(var b = 0; b < Knitting.length; b++) {
            if(Knitting[b][2].indexOf(':') >= 0) {
                var colorscolonsplitarr = Knitting[b][2].split(':');
                for(var c = 0; c < colorscolonsplitarr.length; c++) {
                    var group = [Knitting[b][0],Knitting[b][1],colorscolonsplitarr[c]];
                    fabricDyeingProgramGroupfinal.push(group);
                }
            }
            else {
                fabricDyeingProgramGroupfinal.push([Knitting[b][0],Knitting[b][1],Knitting[b][2]]);
            }
        }

        var fabricDyeingProgramGroupData = multiDimensionalUnique(fabricDyeingProgramGroupfinal);

        for(var dt = 0; dt < fabricDyeingProgramGroupData.length; dt++) {
            console.log(fabricDyeingProgramGroupData[dt],'dt');
            console.log(fabricDyeingProgramGroupData[dt][0],'0 dt');
            console.log(fabricDyeingProgramGroupData[dt][1],'1 dt');
        }

        var ConsolidatedReqData = $("#ConsolidatedReqData").jexcel('getData');
        for(var a = 0; a < ConsolidatedReqData.length; a++) {
            fabricDyeingProgram = fnPopulateValueArray(fabricDyeingProgram,ConsolidatedReqData[a][0] + "#" + ConsolidatedReqData[a][1] + "#" + ConsolidatedReqData[a][2],
                ConsolidatedReqData[a][3]);
            //console.log(ConsolidatedReqData[a][8],'f gsm');
            //console.log(ConsolidatedReqData[a][10],'fin dia');
            //if(ConsolidatedReqData[a][3] != "") {
                /*fabricDyeingProgram = fnPopulateValueArray(fabricDyeingProgram,ConsolidatedReqData[a][0] + "#" + ConsolidatedReqData[a][1] + "#" + ConsolidatedReqData[a][2],
                    ConsolidatedReqData[a][3] + "#" + ConsolidatedReqData[a][4] + "#" + ConsolidatedReqData[a][5] + "#" + ConsolidatedReqData[a][6] + "#" +
                    ConsolidatedReqData[a][8] + "#" + ConsolidatedReqData[a][10] + "#" + ConsolidatedReqData[a][11] + "#" + ConsolidatedReqData[a][13]);*/

                /*fabricDyeingProgram.push(ConsolidatedReqData[a][3] + "#" + ConsolidatedReqData[a][4] + "#" + ConsolidatedReqData[a][5] + "#" + ConsolidatedReqData[a][6] + "#" +
                    ConsolidatedReqData[a][8] + "#" + ConsolidatedReqData[a][10] + "#" + ConsolidatedReqData[a][11] + "#" + ConsolidatedReqData[a][13]);*/
            //}
            //else {
                /*fabricDyeingProgram.push("" + "#" + "" + "#" + "" + "#" + "" + "#" + "" + "#" + ConsolidatedReqData[a][10] + "#" + ConsolidatedReqData[a][11] + "#" +
                    ConsolidatedReqData[a][13]);*/
            //}
        }

        console.log(GlbFabricDyeingPgm,'GlbFabricDyeingPgm');

        //for(var d = 0; d < GlbFabricDyeingPgm.length; d++) {
            /*if(typeof GlbFabricDyeingPgm[d] == "string") {
                var dd = GlbFabricDyeingPgm[d].split('#');
                //console.log(dd,'dd');
            }
            else {
                var newdd = GlbFabricDyeingPgm[d];
            }*/
            //console.log(newdd,'newdd');
            //console.log(GlbFabricDyeingPgm[d],'GlbFabricDyeingPgm in loop');
            //console.log(typeof GlbFabricDyeingPgm[d],'typeof GlbFabricDyeingPgm');
            /*$("#fabricdyeingprogramTbl").append('<div id="fabdyeingmain_'+d+'" style="margin-top: 20px" class="fabMainTbl"></div>' +
                '<div id="fabdyeingsubchild_'+d+'" style="margin-top: 20px" class="fabSubTbl"></div>');*/
            //$("#fabricdyeingprogramsubChildTbl").append('');

/*            $("#fabdyeingmain_"+d).jexcel({
                colHeaders: ["Combo","Component","Colour","Dyeing<br/>Type","Pantone No.<br/>/Swatch Ref.<br/>Details","Approved<br/>Lab Dip<br/>No.","Dyeing Job<br/>Worker's Name"
                    ,"Dyeing Special Request If Any","Other Special<br/>Req. If Any.","Colour Mat.<br/>Std."],
                colWidths: [100,100,100,100,100,100,100,100,100,100],
                columns: [
                    {type: "text", wordWrap: true},
                    {type: "text", wordWrap: true},
                    {type: "text", wordWrap: true},
                    {type: "text", wordWrap: true},
                    {type: "text", wordWrap: true},
                    {type: "text", wordWrap: true},
                    {type: "text", wordWrap: true},
                    {type: "text", wordWrap: true},
                    {type: "text", wordWrap: true},
                    {type: "text", wordWrap: true},
                ],
                data: [ dd ]
            });

            $("#fabdyeingsubchild_"+d).jexcel({
                colHeaders: ["Garment<br/>Parts","Fabric<br>Blend<br/>(%)","Fabric<br/>Content","Fabric<br/>Name","Fin. GSM","Fabric Finish<br/>Wet Process",
                    "Fabric Finish<br/>Dry Process","Blended Fab. Col.<br/>Match Cont.","Fin. DIA /<br/>DIM<br/>(W * H)","Unit fo<br/>Measure",
                    "Plan. Fab.<br/>Wgt.<br/>(Kgs.)","Plan. Fab.<br/>Wgt. <br/>-Subtotal<br/>(Kgs.)"],
                colWidths: [100,100,100,100,100,100,100,100,100,100,100,100],
                columns: [
                    {type: "text", wordWrap: true},
                    {type: "text", wordWrap: true},
                    {type: "text", wordWrap: true},
                    {type: "text", wordWrap: true},
                    {type: "text", wordWrap: true},
                    {type: "text", wordWrap: true},
                    {type: "text", wordWrap: true},
                    {type: "text", wordWrap: true},
                    {type: "text", wordWrap: true},
                    {type: "text", wordWrap: true},
                    {type: "text", wordWrap: true},
                    {type: "text", wordWrap: true},
                ],
                data: []
            });*/

        //}

        $("#fabricdyeingprogramGroupTbl").jexcel({
            colHeaders:["Combo","Component","Colour","Checkbox"],
            colWidths:[100,100,100,100],
            columns:[
                {type: "text", wordWrap: true},
                {type: "text", wordWrap: true},
                {type: "text", wordWrap: true},
                {type: "checkbox", wordWrap: true},
            ],
            data : fabricDyeingProgramGroupData,
            onchange: function (instance,cell,val) {
                var cellName = $(instance).jexcel('getColumnNameFromId', $(cell).prop('id'));
                if(cellName.indexOf('D') == 0) {
                    if(val === true) {
                        var Knitting = $("#FabricDetailsKnit").jexcel('getData');
                        //console.log($(cell).children(),'test');
                        //if($(cell).children().prop('checked') === true) {
                            var ArrRowId = $(cell).prop('id').split('-');
                            //console.log(ArrRowId,'ArrRowId');
                            var CellId = ArrRowId[1];
                            //console.log(CellId,'CellId');
                            //console.log(fabricDyeingProgram,'fabricDyeingProgram');
                        //}
                        /*console.log(fabricDyeingProgramGroupData[CellId][0],fabricDyeingProgramGroupData[CellId][1],fabricDyeingProgramGroupData[CellId][2],
                            fabricDyeingProgramGroupData[CellId][9],'tete');*/


                        /*const FabricDyeingPgm = Object.entries(GlbFabricDyeingPgm);
                        $.each(FabricDyeingPgm,function ( index, value) {
                            console.log( index + " and it's " + value );
                        });*/
                        var ki = fabricDyeingProgramGroupData[CellId][0]+"#"+fabricDyeingProgramGroupData[CellId][1]+"#"+jsTrim(fabricDyeingProgramGroupData[CellId][2]);
                        //var garmentpartsArr = fnGroupArrayValue(fabricDyeingProgram[ki]);

                        //console.log(GlbFabricDyeingPgm[ki],'get val');
                        /*var GlbFabricDyeingPgmFinal = [];
                        for (var key in GlbFabricDyeingPgm) {
                            if (GlbFabricDyeingPgm.hasOwnProperty(key)) {
                                console.log(key + " -> " + GlbFabricDyeingPgm[key]);
                                var arrvaluearr = GlbFabricDyeingPgm[key].split('-');
                                console.log(arrvaluearr,'arrvaluearr');
                                var splittedkey = key.split('#');
                                var color = splittedkey[2];
                                if(color.indexOf(':') >= 0) {
                                    var colorarr = color.split(':');
                                    console.log(colorarr,'colorarr');

                                    for(var f = 0; f < colorarr.length; f++) {
                                        GlbFabricDyeingPgmFinal = fnPopulateValueArray(GlbFabricDyeingPgmFinal,splittedkey[0]+"#"+splittedkey[1]+"#"+jsTrim(colorarr[f]),
                                            GlbFabricDyeingPgm[key]);
                                    }

                                }
                                else {
                                    GlbFabricDyeingPgmFinal = fnPopulateValueArray(GlbFabricDyeingPgmFinal,splittedkey[0]+"#"+splittedkey[1]+"#"+splittedkey[2],
                                        GlbFabricDyeingPgm[key]);
                                }
                            }
                        }
                        console.log(GlbFabricDyeingPgmFinal,'GlbFabricDyeingPgmFinal');*/

                        //console.log(garmentpartsArr,'garmentpartsArr');
                        //var realdatas = [];
                        $("#fabricdyeingprogramMainTbl").append('<div id="fabricdyeingprogramTbl_' + CellId+'" style="margin-top: 20px" class="SubTbl"></div>');
                        $("#fabricdyeingprogramTbl_"+CellId).jexcel({
                            colHeaders: ["Combo","Component","Colour","Dyeing<br/>Type","Pantone No.<br/>/Swatch Ref.<br/>Details","Approved<br/>Lab Dip<br/>No.","Dyeing Job<br/>Worker's Name"
                                ,"Dyeing Special Request If Any","Other Special<br/>Req. If Any.","Colour Mat.<br/>Std."],
                            colWidths: [100,100,100,100,100,100,100,100,100,100],
                            columns: [
                                {type: "text", wordWrap: true},
                                {type: "text", wordWrap: true},
                                {type: "text", wordWrap: true},
                                {type: "text", wordWrap: true},
                                {type: "text", wordWrap: true},
                                {type: "text", wordWrap: true},
                                {type: "text", wordWrap: true},
                                {type: "text", wordWrap: true},
                                {type: "text", wordWrap: true},
                                {type: "text", wordWrap: true},
                            ],
                            data : [
                                [fabricDyeingProgramGroupData[CellId][0],fabricDyeingProgramGroupData[CellId][1],fabricDyeingProgramGroupData[CellId][2],
                                    Knitting[CellId][9],"","","","","",""]
                            ]
                        });
                        //for(var e = 0; e < garmentpartsArr.length; e++) {

                            $("#fabricdyeingprogramMainTbl").append('<div id="fabdyeingsubchild_' + CellId+'" style="margin-top: 20px" class="SubTbl"></div>');
                            //console.log(GlbFabricDyeingPgm[ki+"#"+garmentpartsArr[e]],'mdv');
                        console.log(ki,'ki');
                            var tt = GlbFabricDyeingPgm[ki];
                            console.log(tt,'tt');
                            var datas = tt.substr(tt.indexOf('-')+1);
                            console.log(datas,'datas');
                            var arrdatas = datas.split("-");
                            console.log(arrdatas,'arrdatas');
                        $("#fabdyeingsubchild_"+CellId).jexcel({
                            colHeaders: ["Garment<br/>Parts","Fabric<br>Blend<br/>(%)","Fabric<br/>Content","Fabric<br/>Name","Fin. GSM","Fabric Finish<br/>Wet Process",
                                "Fabric Finish<br/>Dry Process","Blended Fab. Col.<br/>Match Cont.","Fin. DIA /<br/>DIM<br/>(W * H)","Unit fo<br/>Measure",
                                "Plan. Fab.<br/>Wgt.<br/>(Kgs.)"],
                            colWidths: [100,100,100,100,100,100,100,100,100,100,100],
                            columns: [
                                {type: "text", wordWrap: true},
                                {type: "text", wordWrap: true},
                                {type: "text", wordWrap: true},
                                {type: "text", wordWrap: true},
                                {type: "text", wordWrap: true},
                                {type: "text", wordWrap: true},
                                {type: "text", wordWrap: true},
                                {type: "text", wordWrap: true},
                                {type: "text", wordWrap: true},
                                {type: "text", wordWrap: true},
                                {type: "text", wordWrap: true}
                            ]
                        });

                        for(var g = 0; g < arrdatas.length; g++) {
                            var finalsplit = arrdatas[g].split(',')
                            console.log(finalsplit,'finalsplit');
                            //realdatas.push([finalsplit]);
                            //$("#fabdyeingsubchild_"+CellId).jexcel('insertRow');
                            $("#fabdyeingsubchild_"+CellId).jexcel('insertRow',finalsplit,g);
                        }
                            //console.log(realdatas,'realdatas');
                            //console.log(realdatas,'realdatas');
                        //}
                    }
                }
            },
        });
    }

    function fnGotoEdit() {
        MakePostRequest(base_path+'fabricprogramvtwo/deleteconscalc',GlbParam+"&enqid="+enquiryid,'json',fnGotoEditRes);
    }

    function fnGotoEditRes(data) {
        console.log(data,'data');
        fnRedirectPageTimeOut(base_path+'fabricprogramvtwo/fabricdetail/'+HashEnquiryId);
    }

</script>
<style>
    .table > tbody > tr > td, .table > tbody > tr > th, .table > tfoot > tr > td, .table > tfoot > tr > th, .table > thead > tr > td, .table > thead > tr > th {padding:5px;}
    .jexcel>thead>tr>td {font-size:12px !important;}
    table {font-size:12px;}

    .jexcel>tbody>tr>td {white-space: normal !important; word-wrap:break-word !important;}
</style>
<?php $this->load->view(CNFCOMPANY.'template/pagefooter'); ?>