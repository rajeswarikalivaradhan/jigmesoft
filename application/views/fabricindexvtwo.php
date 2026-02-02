<?php
$this->load->view(CNFCOMPANY.'template/pageheader');
/*$ArrLoggedUserInfo      = fnGetUserLoggedInfo(1);
$VarUserType            = $ArrLoggedUserInfo['usertype'];
$VarProfilePermission   = $ArrLoggedUserInfo['pp'];
$ArrUserDetails         = fnGetUserLoggedInfo();*/
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
                <!--<small>Programme</small>-->
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
                                    <!--<button type="button" id="" class="btn btn-info pull-right addrights" onclick="fnMultipleIntakeQty()">Multiply Intake Qty.</button>-->
                                </div>
                                <div class="form-group">
                                    <div class="mainheading"><strong>ITEMIZED QTY. AS PER CUMULATIVE QTY. & INTAKE QTY.</strong></div>
                                    <div id="itemizedqtycumulativeqty_intakeqtytest"></div>
                                </div>
                                <div class="form-group">
                                    <div class="pull-right">
                                        <?php foreach ($ArrCheckBoxesMainTblId as $item) {
                                            if($item->maintableid >= 1) $VarCheckedCellRow = $item->maintableid - 1; ?>
                                            <!--<a href="<?php /*echo base_url('fabricprogramvtwo/editConsCalcfabricdetail') . '/'.$VarHashEnquiryId.'/'.$VarCheckedCellRow */?>">
                                                Edit
                                            </a>-->
                                        <?php } ?>
                                    </div>
                                </div>
                                <?php
                                //echo '<pre>'; print_r($ArrKnitData); die('die');
                                ?>

                                <div class="form-group">
                                    <div class="mainheading"><strong>FABRIC CONSUMPTION CALCULATION</strong></div>
                                    <div id="fabric_consumption_calc"></div>
                                </div>
                                <!--<div class="form-group">
                                    <div class="mainheading"><strong>ITEMIZED FABRIC REQUIREMENT DETAILS COLOUR WISE & DIA WISE</strong></div>
                                    <button type="button" class="btn btn-info pull-right addrights" onclick="FnRequirementDetailsTbl()" id="">Requirement Details</button>
                                    <br/>
                                </div>-->
                                <div class="form-group">
                                    <a href="<?php echo base_url('fabricprogramvtwo/itemfabreqdetail') . '/' . $VarHashEnquiryId?>" class="pad">
                                        ITEMIZED FABRIC REQUIREMENT DETAILS COLOUR WISE & DIA WISE
                                    </a>
                                </div>
                            </form>
                        </div><!-- /.box-body -->

                        <div class="box-footer nopadding">
                            <button type="button" id="" onclick="fnSaveConsCalc()" class="btn btn-info pull-right addrights">Next</button>
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
<script src="<?php echo base_url(); ?>assets/js/jexcel4/jsuites.js"></script>
<script src="<?php echo base_url(); ?>assets/js/jexcel4/jexcel.js"></script>
<script src="<?php echo base_url();?>assets/js/commonfunctions.js"></script>
<script>
    //New data
    var GlbParam = "rfrom=1", GlbGarmentParts = [], fabricConsumptionTblRowCount = 0;
    var enquiryid = '<?php echo @$VarEnquiryId ?>';
    var HashEnquiryId = '<?php echo @$VarHashEnquiryId ?>';
    var ArrKnitData = '<?php echo @$ArrKnitData ?>';

    var ArrSizeChartData = '<?php echo $ArrSizeChartData ?>';
    var ArrFifthTbl = '<?php echo @$ArrFifthTbl ?>';

    var ArrSixTh_atbl = '<?php echo $ArrATbl ?>';
    console.log(ArrSixTh_atbl,'ArrSixTh_atbl');
    console.log(ArrKnitData,'ArrKnitData');


    $("#FabricDetailsKnit").jexcel({
        colHeaders: ['Combo', 'Component', 'Colour', 'Garment<br/>Parts', 'Fabric Blend (%)', 'Fabric Content', 'Fabric Name','Yarn<br/>Count', 'Finishing<br/>GSM',
            'Dyeing<br/>Type','Yarn Special<br/>Request', 'Fabric Finish'],
        colWidths: [105, 105, 100, 120, 123, 123, 123, 80, 100, 60, 100,100],
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
        data: JSON.parse(ArrKnitData)
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
        data: JSON.parse(ArrFifthTbl)
    });


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
        onchange: onc,
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

    var dataItemizedCummlativeIntakeqty = $("#CumulativeSixthATbl").jexcel('getData');
    var reducedItemizedCummlativeIntakeqtyData = [];
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
    }

    var onc = function (instance, cell, val) {
        var ArrRowId = $(cell).prop('id').split('-');
        var CellId = ArrRowId[1];
        GlbCheckBoxRowId = CellId;
        var test = $("#itemizedqtycumulativeqty_intakeqtytest").jexcel('getColumnData',14);
        console.log(test,'test');
        var cellName = $(instance).jexcel('getColumnNameFromId', $(cell).prop('id'));
        if(cellName.indexOf('O') == 0) {
            if(val === true) {
                console.log(GlbCheckBoxRowId,'GlbCheckBoxRowId');
                if($(cell).children().prop('checked') === true) {
                    GlbIschecked++;
                }
                if(GlbIschecked == 1) {
                    //console.log(newArray,'newArray');
                }
                console.log(GlbIschecked,'GlbIschecked');
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
        if(UnSplittedColor.indexOf('-') >= 0) {
            var ArrSecondLevelColor = UnSplittedColor.split('-');
            for(var j = 0; j < ArrSecondLevelColor.length; j++) {
                var CurrentGroup = OSixthATblCumulative[CellId][0]+"#"+OSixthATblCumulative[CellId][1]+"#"+jsTrim(ArrSecondLevelColor[j]);
                var sgp = fnGroupArrayValue(KnitGarmentParts[CurrentGroup]);
                if(sgp != "")
                    SplitGarmentParts.push(sgp);
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
                for (var r = 0; r < rslt.length; r++) {
                    if(rslt[r] != "")
                        GlbGarmentParts.push(rslt[r]);
                }
            }
        }
        else if(GarmentParts.length > 0) {
            var rslt = [].concat.apply([], GarmentParts);
            if (rslt) {
                GlbGarmentParts = [];
                for (var r = 0; r < rslt.length; r++) {
                    if(rslt[r] != "") {
                        GlbGarmentParts.push(rslt[r]);
                    }
                }
            }
        }
        console.log(GlbGarmentParts,'GlbGarmentParts');
        var ConscalcColHeaders = ""; var ArrReadOnlyInfo =  new Array();
        var ArrHeader           = ["Garment<br/>Parts","Fabric<br/>Blend<br/>(%)","Fabric<br/>Content","Fabric<br/>Name","Finishing<br/>GSM","Description","Unit Of<br/>Measure"];
        //var ArrHeader = ["Garment Part","Fabric Blend (%)","Fabric Content","Fabric Name","Finishing GSM","Description","Unit Of Measure"];
        for(var k = 0; k < 8; k++) {
            if(typeof (ArrSizeChartHeader[k])!="undefined") {
                ConscalcColHeaders = ConscalcColHeaders+ArrSizeChartHeader[k]+",";
                ArrHeader.push(ArrSizeChartHeader[k]);
                ArrReadOnlyInfo[k] = false;
            } else {
                ConscalcColHeaders = ConscalcColHeaders+",";
                ArrHeader.push('No Size');
                ArrReadOnlyInfo[k] = true;
            }
        }
        ArrHeader.push('Total');
        console.log(GlbGarmentParts,'GlbGarmentParts');
        for(var l = 0 ; l < GlbGarmentParts.length; l++) {
            console.log(GlbGarmentParts[l],'GlbGarmentParts[l]');
            if(GlbGarmentParts[l].indexOf('#') !== -1) {
                var GpBlendContentName = GlbGarmentParts[l].split('#');
                var Garmentpart = GpBlendContentName[0];
                var FabricBlend = GpBlendContentName[1];
                var FabricContent = GpBlendContentName[2];
                var FabricName = GpBlendContentName[3];
                var FinGsm = GpBlendContentName[4];
                $("#fabric_consumption_calc").append('<div id="subchild_' + l + '" style="margin-top: 20px" class="consCalcTblCls"></div>');
                $("#subchild_" + l).jexcel({
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
                    ],
                    onchange: function (instance, cell, value) {
                        var ccArrRowId = $(cell).prop('id').split('-');
                        var CellId_cc = ccArrRowId[0];
                        var start = Number(CellId_cc) - 2;
                        for (var i = 0; i < ArrSizeChartHeader.length; i++) {
                            fnCalculation(instance, CellId_cc, OSixthATblCumulative[CellId][start], cell, value);
                        }
                    }
                });
            }
        }
    }
</script>
<style>
    .table > tbody > tr > td, .table > tbody > tr > th, .table > tfoot > tr > td, .table > tfoot > tr > th, .table > thead > tr > td, .table > thead > tr > th {padding:5px;}
    .jexcel>thead>tr>td {font-size:12px !important;}
    table {font-size:12px;}

    .jexcel>tbody>tr>td {white-space: normal !important; word-wrap:break-word !important;}
</style>
<?php $this->load->view(CNFCOMPANY.'template/pagefooter'); ?>