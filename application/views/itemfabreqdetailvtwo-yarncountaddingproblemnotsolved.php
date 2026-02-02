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
                                    <button type="button" class="btn btn-info pull-right addrights" onclick="FnRequirementDetailsTbl()" id="">Requirement Details</button>
                                </div>
                                <div class="form-group">
                                    <div id="requirements"></div>
                                </div>
                                <div class="form-group">
                                    <div id="ConsolidatedReqData"></div>
                                </div>
                            </form>
                        </div><!-- /.box-body -->

                        <div class="box-footer nopadding">

                        </div><!-- /.box-footer -->
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
    var GlbParam = "rfrom=1", GlbGarmentParts = [], fabricConsumptionTblRowCount = 0;
    var enquiryid = '<?php echo @$VarEnquiryId ?>';
    var HashEnquiryId = '<?php echo @$VarHashEnquiryId ?>';
    var ArrKnitData = '<?php echo @$ArrKnitData ?>';
    var ArrFifthTbl = '<?php echo @$ArrFifthTbl ?>';
    var ArrSizeChartData = '<?php echo @$ArrSizeChartData ?>';
    var ArrSixTh_atbl = '<?php echo @$ArrSixTh_atbl ?>';
    var GlbSubChildData = '';
    var GlbYarnCount = '<?php echo @$ArrYarnCount ?>';
    var GlbUnitsOfMeasureArr = ["%","Nos.","Gms.","Kgs.","%","Inches.","Cms."];
    var GlbDyeingType = ["FD", "YD", "SDB"];
    //fnMultipleIntakeQty();
    var GlbIschecked = 0; var GlbCheckBoxRowId = 0;
    //function fnMultipleIntakeQty() {
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
    ArrColHeaderFinal.push('Itemized P.O. Qty.<br/>/ Sample Qty.','Pcs. / Set','Intake Qty.','Itemized Qty.<br/>(Pcs)');
    console.log(ArrSixTh_atbl,'ArrSixTh_atbl');
    $("#CumulativeSixthATbl").jexcel({
        colHeaders: ArrColHeaderFinal,
        colWidths: [110, 110, 100, 90, 100, 45, 45, 45, 45, 45, 45, 45, 45, 120, 70, 70, 100],
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
            {type: 'numeric', wordWrap: true},
            {type: 'text', wordWrap: true, readOnly: true},
        ],
        data: ArrSixTh_atbl
    });
    var dataItemizedCummlativeIntakeqty = $("#CumulativeSixthATbl").jexcel('getData');
    var reducedItemizedCummlativeIntakeqtyData = [];
    for(var i = 0; i < dataItemizedCummlativeIntakeqty.length; i++) {
        //console.log(dataItemizedCummlativeIntakeqty[i],'vignesh');
        reducedItemizedCummlativeIntakeqtyData.push([dataItemizedCummlativeIntakeqty[i][0],dataItemizedCummlativeIntakeqty[i][1],dataItemizedCummlativeIntakeqty[i][2],
            dataItemizedCummlativeIntakeqty[i][3],dataItemizedCummlativeIntakeqty[i][4],dataItemizedCummlativeIntakeqty[i][5],dataItemizedCummlativeIntakeqty[i][6],
            dataItemizedCummlativeIntakeqty[i][7],dataItemizedCummlativeIntakeqty[i][8],dataItemizedCummlativeIntakeqty[i][9],dataItemizedCummlativeIntakeqty[i][10],
            dataItemizedCummlativeIntakeqty[i][11],dataItemizedCummlativeIntakeqty[i][12],dataItemizedCummlativeIntakeqty[i][13]]);
    }

    //console.log(reducedItemizedCummlativeIntakeqtyData,'reducedItemizedCummlativeIntakeqtyData');

    var onc = function (instance, cell, val) {
        var ArrRowId = $(cell).prop('id').split('-');
        var CellId = ArrRowId[1];
        GlbCheckBoxRowId = CellId;
        var cellName = $(instance).jexcel('getColumnNameFromId', $(cell).prop('id'));
        if(cellName.indexOf('O') == 0) {
            if(val === true) {
                if($(cell).children().prop('checked') === true) {
                    GlbIschecked++;
                }
                if(GlbIschecked == 1) {
                    //console.log(newArray,'newArray');
                }
            }
        }
        var fabricConsumptionFullData = $("#itemizedqtycumulativeqty_intakeqtytest").jexcel('getData');
        fabricConsumptionTblRowCount = fabricConsumptionFullData.length;
        //console.log(GlbIschecked,'GlbIschecked');
        //if(GlbIschecked > 1) {
        //var hashcellid = encodeURIComponent(CellId);
        //fnRedirectPageTimeOut(base_path+'fabricprogramvtwo/fabricdetail/'+HashEnquiryId+'/'+hashcellid);
        //window.location.href = 'http://www.google.com';
        //}
        //else {
        var fabricConsumptionData = $("#itemizedqtycumulativeqty_intakeqtytest").jexcel('getRowData',CellId);
        fabricConsumptionData.length = 14;
        //console.log(fabricConsumptionData,'fabricConsumptionData');
        $("#fabric_consumption_calc").jexcel({
            data: [fabricConsumptionData],
            colHeaders: fabricConsumptionCalcHeader,
            colWidths: [100, 100, 100, 100, 130, 45, 45, 45, 45, 45, 45, 45, 45,100],
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
                {type: 'text', wordWrap: true, readOnly: true},
                {type: 'text', wordWrap: true, readOnly: true}
            ]
        });
        var Knitting = $("#FabricDetailsKnit").jexcel('getData');
        var KnitGarmentParts = [];
        for(var i = 0; i < Knitting.length; i++) {
            var GroupOne = Knitting[i][0]+"#"+Knitting[i][1]+"#"+Knitting[i][2];
            KnitGarmentParts = fnPopulateValueArray(KnitGarmentParts,GroupOne,Knitting[i][3]+"#"+Knitting[i][4]+"#"+Knitting[i][5]+"#"+Knitting[i][6]+"#"+Knitting[i][8]);
        }
        var OSixthATblCumulative = GlbArrSixth_aTbl;
        var UnSplittedColor = OSixthATblCumulative[CellId][2]; var SplitGarmentParts = []; var GarmentParts = [];
        console.log(UnSplittedColor,'UnSplittedColor');
        if(UnSplittedColor.indexOf(':') >= 0) {
            var ArrSecondLevelColor = UnSplittedColor.split(':');
            for(var i = 0; i < ArrSecondLevelColor.length; i++) {
                var CurrentGroup = OSixthATblCumulative[CellId][0]+"#"+OSixthATblCumulative[CellId][1]+"#"+jsTrim(ArrSecondLevelColor[i]);
                SplitGarmentParts.push(fnGroupArrayValue(KnitGarmentParts[CurrentGroup]));
            }
            console.log(SplitGarmentParts,'SplitGarmentParts in if');
        }
        else {
            var CurrentGroup        = OSixthATblCumulative[CellId][0]+"#"+OSixthATblCumulative[CellId][1]+"#"+OSixthATblCumulative[CellId][2];
            GarmentParts        = fnGroupArrayValue(KnitGarmentParts[CurrentGroup]);
            console.log(GarmentParts,'GarmentParts in else');
        }
        if(SplitGarmentParts.length > 0) {
            var rslt = [].concat.apply([], SplitGarmentParts);
            if (rslt) {
                GlbGarmentParts = [];
                for (var r = 0; r < rslt.length; r++) GlbGarmentParts.push(rslt[r]);
            }
        }
        else if(GarmentParts.length > 0) {
            var rslt = [].concat.apply([], GarmentParts);
            if (rslt) {
                GlbGarmentParts = [];
                for (var r = 0; r < rslt.length; r++) GlbGarmentParts.push(rslt[r]);
            }
        }

        console.log(GlbGarmentParts,'GlbGarmentParts');
        var ConscalcColHeaders = ""; var ArrReadOnlyInfo =  new Array();
        var ArrHeader = ["Garment Part","Fabric Blend (%)","Fabric Content","Fabric Name","Finishing GSM","Description","Unit Of Measure"];
        for(var i=0;i<8;i++) {
            if(typeof (ArrSizeChartHeader[i])!="undefined") {
                ConscalcColHeaders = ConscalcColHeaders+ArrSizeChartHeader[i]+",";
                ArrHeader.push(ArrSizeChartHeader[i]);
                ArrReadOnlyInfo[i] = false;
            } else {
                ConscalcColHeaders = ConscalcColHeaders+",";
                ArrHeader.push('No Size');
                ArrReadOnlyInfo[i] = true;
            }
        }

        ArrHeader.push('Total');
        console.log(GlbGarmentParts,'GarmentParts');
        for(var i = 0 ; i < GlbGarmentParts.length; i++) {
            var GpBlendContentName  = GlbGarmentParts[i].split('#');
            var Garmentpart = GpBlendContentName[0];
            var FabricBlend = GpBlendContentName[1];
            var FabricContent = GpBlendContentName[2];
            var FabricName = GpBlendContentName[3];
            var FinGsm = GpBlendContentName[4];
            $("#fabric_consumption_calc").append('<div id="subchild_'+i+'" style="margin-top: 20px" class="consCalcTblCls"></div>');
            $("#subchild_" + i).jexcel({
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
                data: [
                    [Garmentpart, FabricBlend, FabricContent, FabricName, FinGsm, 'Excess Qty.', '%'],
                    ['', '', '', '', '', 'Plan. Qty.', 'Nos.'],
                    ['', '', '', '', '', 'Piece Wgt.', 'Gms.'],
                    ['', '', '', '', '', 'Req. Fab. Wgt.', 'Kgs.'],
                    ['', '', '', '', '', 'Processing Loss', '%'],
                    ['', '', '', '', '', 'Plan. Fab. Wgt.', 'Kgs.'],
                    ['', '', '', '', '', 'Fin. DIA. / DIM. (W * H)', 'Inches.']
                ]
            });
        }
        //}
    }

    var ColHeaders = '';
    var itemizedqtycumuqty_intakeqtytesthead = ['Combo', 'Component', 'Colour', 'Size Spec<br/>Code/Fit', 'P.O. No. /<br/>Enq. Ref. No.'];
    for(var i=0;i<8;i++) {
        if(typeof (ArrSizeChartHeader[i])!="undefined") {
            ColHeaders = ColHeaders+ArrSizeChartHeader[i]+",";
            itemizedqtycumuqty_intakeqtytesthead.push(ArrSizeChartHeader[i]);
        } else {
            ColHeaders = ColHeaders+",";
            itemizedqtycumuqty_intakeqtytesthead.push('No Size');
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
                ArrColHeaderFinal.push('No Size');
            }
        }
        ArrColHeaderFinal.push('Itemized P.O. Qty. / Sample Qty.'); ArrColHeaderFinal.push('Pcs. / Set');
        ArrColHeaderFinal.push('Intake Qty.<br/>(Nos.)');
        ArrColHeaderFinal.push('Itemized Qty.<br/>(Pcs.)');
        var Knitting = $("#FabricDetailsKnit").jexcel('getData'); var KnitGarmentParts = []; var Fabrics = [];
        for(var i = 0; i < Knitting.length; i++) {
            var GroupOne = Knitting[i][0]+"#"+Knitting[i][1]+"#"+Knitting[i][2];
            KnitGarmentParts = fnPopulateValueArray(KnitGarmentParts,GroupOne,Knitting[i][3]+"#"+Knitting[i][4]+"#"+Knitting[i][5]+"#"+Knitting[i][6]+"#"+Knitting[i][7]);
        }
        var OSixthATblCumulative = $("#CumulativeSixthATbl").jexcel('getData');
        var TableSno = 'Table No: '; TableSno += Number(CellId) + 1;
        $("#FabricProgramCalc").append('<br/><br/><div class="mainheading box box-primary"><h4>'+TableSno+'</h4> </div> <div id="fabricprogrammain_'+CellId+'"></div>');
        $("#fabricprogrammain_"+CellId).jexcel({
            colHeaders: ArrColHeaderFinal,
            colWidths: [100, 100, 100, 100, 130, 45, 45, 45, 45, 45, 45, 45, 45, 100, 70, 70, 100],
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

        if(UnSplittedColor.indexOf(':') >= 0) {
            var ArrSecondLevelColor = UnSplittedColor.split(':');
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
        var ArrHeader           = ["Garment Part","Fabric Blend (%)","Fabric Content","Fabric Name","Finishing GSM","Description","Unit Of Measure"];
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
        if (jQuery.inArray(KeyValue, ArrName)) {
            ArrName[KeyValue] = ArrName[KeyValue]+"-"+InsertVal;
        }
        else {
            //ArrName[KeyValue] = '';
        }
        return ArrName;
    }
    function fnGroupArrayValue(ArrSizeVal) {
        if (ArrSizeVal != "" && typeof ArrSizeVal != "undefined") {
            var SumVal = [];
            var ArrName = ArrSizeVal.split("-");
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
            var ArrName = ArrSizeVal.split("-");
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
        console.log(data.ArrSixth_aTbl,'ArrSixth_aTbl');
        GlbArrSixth_aTbl = data.ArrSixth_aTbl;
    }

    ArrColHeaderFinal.push('Tick');
    function FnRequirementDetailsTbl() {
        var CumulativeSixthATbl = $("#CumulativeSixthATbl").jexcel('getData');
        var Knitting = $("#FabricDetailsKnit").jexcel('getData'); var KnitGarmentPartsCount = [];
        for(var i = 0; i < Knitting.length; i++) {
            var GroupOne = Knitting[i][0]+"#"+Knitting[i][1]+"#"+Knitting[i][2];
            KnitGarmentPartsCount = fnPopulateValueArray(KnitGarmentPartsCount,GroupOne,Knitting[i][3]+"#"+
                Knitting[i][4]+"#"+Knitting[i][5]+"#"+Knitting[i][6]+"#"+Knitting[i][7]);
        }
        console.log(KnitGarmentPartsCount,'KnitGarmentPartsCount');
        var FinishingGsmGroup = []; var RequirementDetailsTbl = [];
        for(var i = 0; i < Knitting.length; i++) {
            var GroupOne = Knitting[i][0]+"#"+Knitting[i][1]+"#"+Knitting[i][2];
            FinishingGsmGroup[GroupOne+"#"+Knitting[i][3]+"#"+Knitting[i][4]+"#"+Knitting[i][5]+"#"+Knitting[i][6]] = Knitting[i][8];
        }
        console.log(FinishingGsmGroup,'FinishingGsmGroup');
        var GarmentParts = [];
        for(var i = 0; i < CumulativeSixthATbl.length; i++) {
            var maindata = $("#fabricprogrammain_"+i).jexcel('getRowData');
            console.log(CumulativeSixthATbl[i],'CumulativeSixthATbl i');
            var UnSplittedColor = CumulativeSixthATbl[i][2]; var SplitGarmentParts = []; var ArrSecondLevelColor = [];
            console.log(UnSplittedColor,'UnSplittedColor');
            if(UnSplittedColor.indexOf(':') >= 0) {
                ArrSecondLevelColor = UnSplittedColor.split(':');
                for(var c = 0; c < ArrSecondLevelColor.length; c++) {
                    var CurrentGroup        = CumulativeSixthATbl[i][0]+"#"+CumulativeSixthATbl[i][1]+"#"+
                        jsTrim(ArrSecondLevelColor[c]);
                    SplitGarmentParts = fnGroupArrayValue(KnitGarmentPartsCount[CurrentGroup]);
                    var Units = $("#subchild_"+i+"_"+c).jexcel('getColumnData',6);
                    console.log(Units,'Units');
                    var ReqFabWgt = $("#subchild_"+i+"_"+c).jexcel('getRowData',3).splice(7,8);
                    console.log(ReqFabWgt,'ReqFabWgt');
                    var PlanFabWgt = $("#subchild_"+i+"_"+c).jexcel('getRowData',5).splice(7,8);
                    console.log(PlanFabWgt,'PlanFabWgt');
                    var FinDia = $("#subchild_"+i+"_"+c).jexcel('getRowData',6).splice(7,8);
                    console.log(FinDia,'FinDia');
                    console.log(SplitGarmentParts,'SplitGarmentParts');
                    var GpandFabricdetails = SplitGarmentParts[0].split('#');
                    var part = GpandFabricdetails[0];
                    var fabricblend = GpandFabricdetails[1];
                    var fabriccontent = GpandFabricdetails[2];
                    var fabricname = GpandFabricdetails[3];
                    var yarncount = GpandFabricdetails[4];
                    for(var f = 0; f < FinDia.length; f++) {
                        if (FinDia[f] && ReqFabWgt[f] && PlanFabWgt[f]) {
                            console.log(Units[6],'units 6');
                            RequirementDetailsTbl.push([maindata[0], maindata[1], jsTrim(ArrSecondLevelColor[c]),
                                maindata[3], part, fabricblend,fabriccontent,fabricname, yarncount,FinDia[f], Units[6],
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
                    var yarncount = GpandFabricdetails[4];
                    for (var f = 0; f < FinDia.length; f++) {
                        if (FinDia[f] && ReqFabWgt[f] && PlanFabWgt[f]) {
                            RequirementDetailsTbl.push([maindata[0], maindata[1], maindata[2], maindata[3], part,
                                fabricblend,fabriccontent,fabricname, yarncount,FinDia[f], Units[6], ReqFabWgt[f],
                                PlanFabWgt[f]]);
                        }
                    }
                }
                var CccGroup = CumulativeSixthATbl[i][0] + "#" + CumulativeSixthATbl[i][1] + "#" +
                    CumulativeSixthATbl[i][2];

            }
        }

        $("#requirements").jexcel({
            colHeaders: ["Combo","Component","Color","Size Spec Code<br/>/Fit","Garment<br/>Part","Fabric<br/>Blend<br/>(%)",
                "Fabric<br/>Content","Fabric<br/>Name",
                "Yarn<br/>Count",
                "Fin. DIA<br/>/<br/>DIM (W * H)",
                "Unit<br/>Of<br/>Measure",
                "Req. Fab.<br/>Wgt.<br/>(Kgs.)",
                "Plan. Fab. Wgt. (Kgs.)"],
            colWidths: [100,100,100,100,100,60,100,100,60,90,60,100,100],
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
                {type: 'dropdown', source: GlbUnitsOfMeasureArr, readOnly: true},
                {type: 'text', wordWrap: true, readOnly: true},
                {type: 'text', wordWrap: true, readOnly: true},
            ],
            data: RequirementDetailsTbl
        });

        var Two = [], GroupsArr = [], FinalDataCumulative = [], FininhingDia = [], GlbUniqueGroup = [], GroupFive = [], UnitOfMeasure = [];
        var One = {};
        jQuery.each(RequirementDetailsTbl,function (index,el) {
            var GroupSix = el[0]+"#"+el[1]+"#"+el[2]+"#"+el[4]+"#"+el[5]+"#"+el[6]+"#"+el[7]+"#"+el[9]+"#"+el[10];
            var GroupFiveWoutSizeSpec = el[0]+"#"+el[1]+"#"+el[2]+"#"+el[4]+"#"+el[5]+"#"+el[6]+"#"+el[7];
            var GroupId             = jQuery.inArray(GroupSix, GroupsArr);
            if(GroupId === -1) {
                GroupsArr.push(GroupSix);
            }
            One = fnPopulateValueArray(One,GroupSix,el[11]);
            Two = fnPopulateValueArray(Two,GroupSix,el[12]);
            FininhingDia = fnPopulateValueArray(FininhingDia,GroupSix,el[9]);
            UnitOfMeasure[GroupFiveWoutSizeSpec] = el[10];
        });

        for(var b = 0; b < GroupsArr.length; b++) {
            var KeyVal = GroupsArr[b];
            var Oneresu = fnSumSizeArrayValue(One[KeyVal]);
            var Tworesu = fnSumSizeArrayValue(Two[KeyVal]);
            var data = KeyVal.split('#');
            console.log(data[7],'data 7');
            console.log(data,'data');
            FinalDataCumulative.push([data[0],data[1],data[2],data[3],data[4],data[5],data[6],data[7],"","",data[8],"",Oneresu,Tworesu]);
        }

        var FilterFinal = {}; var FinalDataCumulativeNew = [], UniqueFirstFive = [];
        jQuery.each(FinalDataCumulative,function (index,el) {
            console.log(el,'el in each');
            FilterFinal = fnPopulateValueArray(FilterFinal,el[0]+"#"+el[1]+"#"+el[2]+"#"+el[3]+"#"+el[4]+"#"+el[5]+"#"+el[6],el[11]+"|#|"+el[13]+"|#|"+el[14]);
        });

        jQuery.each(FilterFinal,function (index,el) {
            console.log(index,'index');
            console.log(el,'el');
            var SplitAll = el.split('-'); var ReqFabTotal = 0; var ReqPlanTotal = 0;
            for(var i = 0; i < SplitAll.length; i++) {
                var CccGarmentpart = index.split('#');
                console.log(SplitAll[i],'splitall');
                console.log(CccGarmentpart,'CccGarmentpart before if');
                if(SplitAll[i] != "undefined" && SplitAll[i] != "") {
                    console.log(CccGarmentpart,'CccGarmentpart first');
                    var Remaining = SplitAll[i].split('|#|');
                    var FirstFive = CccGarmentpart[0]+"#"+CccGarmentpart[1]+"#"+CccGarmentpart[2]+"#"+CccGarmentpart[3]+"#"+CccGarmentpart[4]+"#"+CccGarmentpart[5]+"#"+CccGarmentpart[6];
                    var FinGsm = FinishingGsmGroup[FirstFive];
                    ReqFabTotal += Number(Remaining[1]);
                    ReqPlanTotal += Number(Remaining[2]);
                    if(jQuery.inArray(FirstFive,UniqueFirstFive) === -1) {
                        var rfw = Number(Remaining[1]);
                        var pfw = Number(Remaining[2]);
                        console.log(Remaining[1],'Remaining 1');
                        console.log(Remaining[0],'Remaining 0');
                        console.log(CccGarmentpart[6],'CccGarmentpart 6');
                        console.log(CccGarmentpart[7],'CccGarmentpart 7');
                        var um = UnitOfMeasure[CccGarmentpart[0]+"#"+CccGarmentpart[1]+"#"+CccGarmentpart[2]+"#"+CccGarmentpart[3]+"#"+CccGarmentpart[4]+"#"+CccGarmentpart[5]+"#"+CccGarmentpart[6]];
                        console.log(um,'um');
                        UniqueFirstFive.push(FirstFive);
                        FinalDataCumulativeNew.push([CccGarmentpart[0] ,CccGarmentpart[1],CccGarmentpart[2], CccGarmentpart[3],CccGarmentpart[4],CccGarmentpart[5],CccGarmentpart[6],CccGarmentpart[7],FinGsm,"",Remaining[0],um,rfw.toFixed(3),pfw.toFixed(3)]);
                    }
                    else {
                        console.log(Remaining[1],'Remaining 1');
                        var rfw = Number(Remaining[1]);
                        var pfw = Number(Remaining[2]);
                        console.log(Remaining[0],'Remaining 0 in else');
                        FinalDataCumulativeNew.push(["","","","","","","","","","",Remaining[0],"",rfw.toFixed(3),pfw.toFixed(3)]);
                    }
                }
            }
            FinalDataCumulativeNew.push(["","","","","","","","","","","","",ReqFabTotal.toFixed(3),ReqPlanTotal.toFixed(3)]);
        });
console.log(FinalDataCumulativeNew,'FinalDataCumulativeNew');
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
                {type: 'text', wordWrap: true, readOnly: true},
                {type: 'text', wordWrap: true, readOnly: true},
                {type: 'dropdown', source: GlbDyeingType, wordWrap: true},
                {type: 'text', wordWrap: true, readOnly: true},
                {type: 'dropdown', source: GlbUnitsOfMeasureArr, readOnly: true},
                {type: 'text', wordWrap: true, readOnly: true},
                {type: 'text', wordWrap: true, readOnly: true},
            ],
            data: FinalDataCumulativeNew
        });
    }

</script>
<style>
    .table > tbody > tr > td, .table > tbody > tr > th, .table > tfoot > tr > td, .table > tfoot > tr > th, .table > thead > tr > td, .table > thead > tr > th {padding:5px;}
    .jexcel>thead>tr>td {font-size:12px !important;}
    table {font-size:12px;}

    .jexcel>tbody>tr>td {white-space: normal !important; word-wrap:break-word !important;}
</style>
<?php $this->load->view(CNFCOMPANY.'template/pagefooter'); ?>