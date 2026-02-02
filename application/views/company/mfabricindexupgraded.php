<link rel="stylesheet" href="<?php echo base_url();?>assets/css/jexcel20/jquery.jexcel.css" type="text/css" />
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
                        <!--<div class="mainheading"><strong>ITEMIZED FABRIC REQUIREMENT DETAILS COLOUR WISE & DIA WISE</strong></div>-->
                        <button type="button" class="btn btn-info pull-right addrights" onclick="FnSaveCCSubAndCumulative()">Save and Cumulative</button>
                        <!--<div id="requirements"></div>-->
                        <br/>
                        <div class="mainheading"><strong>CUMULATIVE FABRIC REQUIREMENT DETAILS COLOUR WISE & DIA WISE</strong></div>
                        <div id="ConsolidatedReqData"></div>
                        <!--<button type="button" class="btn btn-info pull-right addrights" onclick="FnRequirementDetailsTbl()" id="">Requirement Details</button>-->
                        <br/>
                        <button type="button" class="btn btn-info pull-right addrights" onclick="FnYarnDyeingItemizedColorWiseQtyBreakUp()" id="">Itemized Yarn Weight</button>
                        <br/>
                        <div class="clearfix"></div>
                        <div class="mainheading"><strong>YARN DYEING - ITEMIZED COLOUR WISE QTY. BREAK-UP DETAILS</strong></div>
                        <div id="YarnDyeingItemizedColorWiseQtyBreakUp"></div>
                        <?php


                        ?>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <?php $this->load->view(CNFCOMPANY.'template/templatefooter');?>
    <div class="control-sidebar-bg"></div>
</div>
<script src="<?php echo base_url();?>assets/js/ajax.js"></script>
<script src="<?php echo base_url();?>assets/js/jexcel20/jquery.jexcel.js"></script>
<script src="<?php echo base_url();?>assets/js/jexcel/numeral.min.js"></script>
<script src="<?php echo base_url();?>assets/js/jexcel20/excel-formula.min.js"></script>
<script src="<?php echo base_url();?>assets/js/commonfunctions.js"></script>
<script>
    var GlbYarnCount = <?php echo json_encode($ArrYarnCount) ?>;
    console.log(GlbYarnCount);
    var SizeChart = localStorage.getItem('SelSizeChartTextLs');
    var ItemizedSizeWiseQtyBrkUpFifthTblLs = localStorage.getItem('ItemizedSizeWiseQtyBrkUpFifthTblLs');
    var SixthATblCumulative = JSON.parse(localStorage.getItem('SixthATblCumulative'));
    var KnitTblLs = localStorage.getItem('FabricKnitTblLs');
    var Glbfabricfinish = '<?php echo $fabricfinish ?>';
    var GlbfabricfinishStageForm = '<?php echo $fabricfinishStageForm ?>';
    var GlbDyeingType = ["Fabric Dye","Yarn Dye"];
    $("#FabricDetails").jexcel({
        colHeaders: ['Combo', 'Component', 'Colour', 'Garment<br/>Parts', 'Fabric Detail<br/>(%) Blend / Content / Lyc. Feed. Type - Danier / Fabric', 'Finishing<br/>GSM', 'Yarn Special<br/>Request', 'Fabric Finish', 'Fabric Finish<br/>Stage / Form'],
        colWidths: [105, 105, 100, 120, 370, 100, 100, 110, 120],
        allowInsertColumn: false,
        columns: [
            {type: 'text', wordWrap: true},
            {type: 'text', wordWrap: true},
            {type: 'text', wordWrap: true},
            {type: 'text', wordWrap: true},
            {type: 'text', wordWrap: true},
            {type: 'text', wordWrap: true},
            {type: 'text', wordWrap: true},
            {type: 'dropdown', source: JSON.parse(Glbfabricfinish), wordWrap: true},
            {type: 'dropdown', source: JSON.parse(GlbfabricfinishStageForm), wordWrap: true},
        ],
        data:KnitTblLs
    });

    var WovenTblLs = localStorage.getItem('FabrivWovenTblLs');

    var ColHeaders      = "";
    var ArrSizeChartHeader = SizeChart.split(",");
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
    ArrColHeaderFinal.push('Itemized P.O. Qty. / Sample Qty.'); ArrColHeaderFinal.push('Pcs. / Set'); ArrColHeaderFinal.push('Intake Qty.<br/>(Nos.)');
    ArrColHeaderFinal.push('Itemized Qty.<br/>(Pcs.)');

    $("#SizeWiseQtyBrkUpFifthTbl").jexcel({
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
        data: ItemizedSizeWiseQtyBrkUpFifthTblLs
    });

    var CumulativeHeader = ArrColHeaderFinal;
    CumulativeHeader.push('Tick');
    //console.log(SixthATblCumulative,'SixthATblCumulative');
    /*for(var a = 0; a < SixthATblCumulative.length; a++) {
        SixthATblCumulative[a]
    }*/

    $("#CumulativeSixthATbl").jexcel({
        colHeaders: CumulativeHeader,
        colWidths: [110, 110, 100, 90, 100, 45, 45, 45, 45, 45, 45, 45, 45, 80, 70, 70, 80, 60],
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
            {type: 'text', wordWrap: true, readOnly: true},
            {type: 'checkbox'}
        ],
        data: SixthATblCumulative,
        onchange: function (instance,cell,val) {
            console.log(instance,cell,val);
            var cellName = $(instance).jexcel('getColumnNameFromId', $(cell).prop('id'));
            console.log(cellName,'cellName');
            if(cellName.indexOf('R') == 0) {
                if(val === true) {
                    var ArrRowId = $(cell).prop('id').split('-');
                    FnCCTbl(instance, cell, val, ArrRowId[1]);
                }
            }
        }
    });
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

    var planqty = 0; var reqfabricwgt = 0; var planfabricwgt = 0;
    function fnCalculation(instance,colId,sizeid,cell,value) {
        var currtableid = '#'+$(instance).prop('id');
        var zerorow = colId + '-0';
        var firstrow = colId + '-1';
        var secondrow = colId + '-2';
        var thirdrow = colId + '-3';
        var fourthrow = colId + '-4';
        var fifthrow = colId + '-5';
        var sixthrow = colId + '-6';

        if ($(cell).prop('id') == firstrow) {
            if (value != "") {
                var firstMul = parseInt(sizeid) * $(currtableid+" #" + zerorow).html(); console.log(firstMul,'firstMul');
                var percent = parseInt($(currtableid+" #" + firstrow).text()) / 100; console.log(percent,'percent');
                planqty = firstMul * percent + parseInt(sizeid) * $(currtableid+" #"+zerorow).html(); console.log(parseInt(sizeid) * $(currtableid+" #"+zerorow).html(),'sizeid * $(currtableid+" #"+zerorow).html()');
                console.log(planqty,'planqty'); console.log(currtableid+" #" + secondrow,'currtableid+" #" + secondrow in 1');
                $(currtableid+" #" + secondrow).html(planqty);
            }
        }
        if ($(cell).prop('id') == thirdrow) {
            if (value != "") {
                reqfabricwgt = 0;
                console.log(planqty,'planqty');
                planqty = Number($(currtableid+" #"+secondrow).text());
                console.log(reqfabricwgt,'reqfabricwgt'); console.log($(currtableid+" #" + thirdrow).text(),'$(currtableid+" #" + thirdrow).text()');
                reqfabricwgt = planqty * Number($(currtableid+" #" + thirdrow).text()); console.log(reqfabricwgt.toFixed(3),'reqfabricwgt.toFixed(3) in 3');
                $(currtableid+" #" + fourthrow).html(reqfabricwgt.toFixed(3));
            }

        }
        if ($(cell).prop('id') == fifthrow) {
            if (value != "") {
                planfabricwgt = 0;
                reqfabricwgt = Number($(currtableid+" #"+ fourthrow).text());
                console.log(reqfabricwgt,'reqfabricwgt in 5'); console.log($(currtableid+" #" + fifthrow).text(),'$(currtableid+" #" + fifthrow).text()');
                planfabricwgt = reqfabricwgt * parseInt($(currtableid+" #" + fifthrow).text());
                planfabricwgt = planfabricwgt / 100;
                planfabricwgt = planfabricwgt + Number($(currtableid+" #" + fourthrow).text());
                console.log(planfabricwgt,'planfabricwgt'); console.log($(currtableid+" #" + fourthrow).text(),'$(currtableid+" #" + fourthrow).text() in 5');
                $(currtableid+" #" + sixthrow).html(planfabricwgt.toFixed(3));
            }
        }
        $(currtableid+" #13-2").text(Number($(currtableid+" #5-2").text()) + Number($(currtableid+" #6-2").text()) + Number($(currtableid+" #7-2").text())
            + Number($(currtableid+" #8-2").text()) + Number($(currtableid+ " #9-2").text()) + Number($(currtableid+ " #10-2").text()) +
            Number($(currtableid+" #11-2").text()) + Number($(currtableid+" #12-2").text()));

        var ReqFabWgtTotal = Number($(currtableid+" #5-4").text()) + Number($(currtableid+" #6-4").text()) + Number($(currtableid+" #7-4").text())
            + Number($(currtableid+" #8-4").text()) + Number($(currtableid+" #9-4").text()) + Number($(currtableid+" #10-4").text()) +
            Number($(currtableid+" #11-4").text()) + Number($(currtableid+" #12-4").text());
        $(currtableid+" #13-4").html(ReqFabWgtTotal.toFixed(3));

        var total = Number($(currtableid+" #5-6").text()) + Number($(currtableid+" #6-6").text()) + Number($(currtableid+" #7-6").text())
            + Number($(currtableid+" #8-6").text()) + Number($(currtableid+" #9-6").text()) + Number($(currtableid+" #10-6").text()) +
            Number($(currtableid+" #11-6").text()) + Number($(currtableid+" #12-6").text());
        $(currtableid+" #13-6").html(total.toFixed(3));

    }

    function FnCCTbl(instance,cell,val,CellId) {
        var ColHeaders      = ""; var SizeChart           = localStorage.getItem('SelSizeChartTextLs');  var finalres = 0;
        var ArrSizeChartHeader = SizeChart.split(",");
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
        ArrColHeaderFinal.push('Itemized P.O. Qty. / Sample Qty.'); ArrColHeaderFinal.push('Pcs. / Set'); ArrColHeaderFinal.push('Intake Qty.<br/>(Nos.)');
        ArrColHeaderFinal.push('Itemized Qty.<br/>(Pcs.)');
        var Knitting = JSON.parse(localStorage.getItem('FabricKnitTblLs')); var KnitGarmentParts = []; var Fabrics = [];
        for(var i = 0; i < Knitting.length; i++) {
            var GroupOne = Knitting[i][0]+"#"+Knitting[i][1]+"#"+Knitting[i][2];
            KnitGarmentParts = fnPopulateValueArray(KnitGarmentParts,GroupOne,Knitting[i][3]+"#"+Knitting[i][4]+"#"+Knitting[i][5]);
        }
        //console.log(KnitGarmentParts,'KnitGarmentParts');
        var OSixthATblCumulative = JSON.parse(localStorage.getItem('SixthATblCumulative'));
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
        //console.log(UnSplittedColor.indexOf(';'),'UnSplittedColor.indexOf'); console.log(KnitGarmentParts,'KnitGarmentParts');
        if(UnSplittedColor.indexOf(';') >= 0) {
            var ArrSecondLevelColor = UnSplittedColor.split(';');
            for(var i = 0; i < ArrSecondLevelColor.length; i++) {
                var CurrentGroup        = OSixthATblCumulative[CellId][0]+"#"+OSixthATblCumulative[CellId][1]+"#"+jsTrim(ArrSecondLevelColor[i]);
                console.log(CurrentGroup,'CurrentGroup');
                SplitGarmentParts.push(fnGroupArrayValue(KnitGarmentParts[CurrentGroup]));
            }
            var rslt = [].concat.apply([],SplitGarmentParts);
            //console.log(rslt,'rslt');
        }
        else {
            var CurrentGroup        = OSixthATblCumulative[CellId][0]+"#"+OSixthATblCumulative[CellId][1]+"#"+OSixthATblCumulative[CellId][2];
            GarmentParts        = fnGroupArrayValue(KnitGarmentParts[CurrentGroup]);
        }
        console.log(GarmentParts,'GarmentParts before push');
        if(rslt) for(var r = 0; r < rslt.length; r++) GarmentParts.push(rslt[r]);
        console.log(GarmentParts,'GarmentParts');
        console.log(KnitGarmentParts[CurrentGroup],'KnitGarmentParts[CurrentGroup]');

        var ColHeaders          = ""; var ArrReadOnlyInfo     =  new Array();
        var ArrHeader           = ["Garment Part","Fabric Details","Finishing GSM","Description","Unit Of Measure"];
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
        var UnitsArrJson = [{"id":"1","name":"Nos."},{"id":"2","name":"%"},{"id":"3","name":"Gms."},{"id":"4","name":"Kgs."},{"id":"5","name":"Inches."},{"id":"6","name":"Cms."}];
        ArrHeader.push('Total');
        for(var i = 0; i < GarmentParts.length; i++) {
            var GpEach  = GarmentParts[i];
            var Fabric  = GpEach.substring(GpEach.indexOf('#')+1,GpEach.lastIndexOf('#'));
            var Gp      = GpEach.substring(0,GpEach.indexOf('#'));
            var FinGsm  = GpEach.substring(GpEach.lastIndexOf('#')+1);

            $("#FabricProgramCalc").append('<div id="subchild_'+CellId+'_'+i+'" style="margin-top: 20px" class="SubTbl"></div>');
            $("#subchild_"+CellId+"_"+i).jexcel({
                colHeaders: ArrHeader,
                colWidths: [80, 195, 65, 170, 70, 70, 70, 70, 70, 70, 70, 70, 70, 90],
                columns: [
                    {type: 'text', wordWrap: true, readOnly: true},
                    {type: 'text', wordWrap: true, readOnly: true},
                    {type: 'text', wordWrap: true, readOnly: true},
                    {type: 'text', wordWrap: true, readOnly: true},
                    {type: 'dropdown', source: UnitsArrJson, wordWrap: true},
                    {type: 'text', wordWrap: true,readOnly:ArrReadOnlyInfo[0]},
                    {type: 'text', wordWrap: true,readOnly:ArrReadOnlyInfo[1]},
                    {type: 'text', wordWrap: true,readOnly:ArrReadOnlyInfo[2]},
                    {type: 'text', wordWrap: true,readOnly:ArrReadOnlyInfo[3]},
                    {type: 'text', wordWrap: true,readOnly:ArrReadOnlyInfo[4]},
                    {type: 'text', wordWrap: true,readOnly:ArrReadOnlyInfo[5]},
                    {type: 'text', wordWrap: true,readOnly:ArrReadOnlyInfo[6]},
                    {type: 'text', wordWrap: true,readOnly:ArrReadOnlyInfo[7]},
                    {type: 'text', wordWrap: true}
                ],
                data: [
                    [Gp,Fabric,FinGsm,'Intake Qty.','1'],
                    ['','','','Excess Qty.','2'],
                    ['','','','Plan. Qty.','1'],
                    ['','','','Piece Wgt.','3'],
                    ['','','','Req. Fab. Wgt.','4'],
                    ['','','','Processing Loss','2'],
                    ['','','','Plan. Fab. Wgt.','4'],
                    ['','','','Fin. DIA. / DIM. (W * H)','5']
                ],
                onchange: function (instance, cell, value) {
                    var start = 5;
                    for(var i = 0; i < ArrSizeChartHeader.length; i++) {
                        console.log(OSixthATblCumulative[CellId][start],'OSixthATblCumulative[CellId][start]');
                        fnCalculation(instance,start, OSixthATblCumulative[CellId][start],cell,value);
                        start++;
                    }
                }
            });
            $("#subchild_"+CellId+"_"+i).jexcel('updateSettings',{
                table: function (instance, cell, col, row, val, id) {
                    var start = 5;
                    for(var i = 0; i < ArrSizeChartHeader.length; i++) {
                        if(col == start) {
                            if(row == 0) {
                                if($(cell).is('[readOnly]')) $(cell).html('');
                                else $(cell).html(OSixthATblCumulative[CellId][15]);
                            }
                        }
                        start++;
                    }
                    if(col == 4) {
                        if(row == 7) {

                        }else {
                            $(cell).addClass('readonly');
                        }
                    }
                }
            });
        }
    }

    var GlbUnits = [];
    function FnSaveCCSubAndCumulative() {
        var CumulativeSixthATbl = $("#CumulativeSixthATbl").jexcel('getData');
        var Knitting = JSON.parse(localStorage.getItem('FabricKnitTblLs')); var KnitGarmentPartsCount = [];
        for(var i = 0; i < Knitting.length; i++) {
            var GroupOne = Knitting[i][0]+"#"+Knitting[i][1]+"#"+Knitting[i][2];
            KnitGarmentPartsCount = fnPopulateValueArray(KnitGarmentPartsCount,GroupOne,Knitting[i][3]+"#"+Knitting[i][4]+"#"+Knitting[i][5]);
        }

        for(var i = 0; i < CumulativeSixthATbl.length; i++) {
            var CccGroup        = CumulativeSixthATbl[i][0]+"#"+CumulativeSixthATbl[i][1]+"#"+CumulativeSixthATbl[i][2];
            var GarmentParts    = fnGroupArrayValue(KnitGarmentPartsCount[CccGroup]);

            for(var x = 0; x < GarmentParts.length; x++) {
                var subdata = $("#subchild_"+i+"_"+x).jexcel('getData');

                localStorage.setItem("subchild_"+i+"_"+x,JSON.stringify(subdata));
            }

        }
        var UnitsArrJson = [{"id":"1","name":"Nos."},{"id":"2","name":"%"},{"id":"3","name":"Gms."},{"id":"4","name":"Kgs."},{"id":"5","name":"Inches."},{"id":"6","name":"Cms."}];
        var CumulativeSixthATbl = $("#CumulativeSixthATbl").jexcel('getData');

        var Knitting = JSON.parse(localStorage.getItem('FabricKnitTblLs')); var KnitGarmentPartsCount = [];

        var FinishingGsmGroup = []; var newdata = [];
        for(var i = 0; i < Knitting.length; i++) {
            var GroupOne = Knitting[i][0]+"#"+Knitting[i][1]+"#"+Knitting[i][2];
            FinishingGsmGroup = fnPopulateValueArray(FinishingGsmGroup,GroupOne+"#"+Knitting[i][3]+"#"+Knitting[i][4],Knitting[i][5]);
            KnitGarmentPartsCount = fnPopulateValueArray(KnitGarmentPartsCount,GroupOne,Knitting[i][3]+"#"+Knitting[i][4]+"#"+Knitting[i][5]);
        }
        var GarmentParts = [];
        for(var i = 0; i < CumulativeSixthATbl.length; i++) {

            var UnSplittedColor = CumulativeSixthATbl[i][2]; var SplitGarmentParts = []; var ArrSecondLevelColor = [];

            if(UnSplittedColor.indexOf(';') >= 0) {
                ArrSecondLevelColor = UnSplittedColor.split(';');
                for(var s = 0; s < ArrSecondLevelColor.length; s++) {
                    var CurrentGroup        = CumulativeSixthATbl[i][0]+"#"+CumulativeSixthATbl[i][1]+"#"+jsTrim(ArrSecondLevelColor[s]);
                    SplitGarmentParts.push(fnGroupArrayValue(KnitGarmentPartsCount[CurrentGroup]));
                }
                var rslt = [].concat.apply([],SplitGarmentParts);
            }
            else {
                var CurrentGroup        = CumulativeSixthATbl[i][0]+"#"+CumulativeSixthATbl[i][1]+"#"+CumulativeSixthATbl[i][2];
                GarmentParts        = fnGroupArrayValue(KnitGarmentPartsCount[CurrentGroup]);
            }
            if(rslt) for(var r = 0; r < rslt.length; r++) GarmentParts.push(rslt[r]);
            var CccGroup        = CumulativeSixthATbl[i][0]+"#"+CumulativeSixthATbl[i][1]+"#"+CumulativeSixthATbl[i][2];
            for(var x = 0; x < GarmentParts.length; x++) {
                var maindata = $("#fabricprogrammain_"+i).jexcel('getRowData');
                var Units = $("#subchild_"+i+"_"+x).jexcel('getColumnData',4);
                var ReqFabWgt = $("#subchild_"+i+"_"+x).jexcel('getRowData',4).splice(5,8);
                var PlanFabWgt = $("#subchild_"+i+"_"+x).jexcel('getRowData',6).splice(5,8);
                var FinDia = $("#subchild_"+i+"_"+x).jexcel('getRowData',7).splice(5,8);
                var part   = GarmentParts[x].substring(0,GarmentParts[x].indexOf('#'));
                var fabric = GarmentParts[x].substring(GarmentParts[x].indexOf('#')+1,GarmentParts[x].lastIndexOf('#'));
                for(var f = 0; f < FinDia.length; f++) {
                    if(FinDia[f] && ReqFabWgt[f] && PlanFabWgt[f])
                        newdata.push([maindata[0], maindata[1], maindata[2], maindata[3], part, fabric, FinDia[f], Units[7], ReqFabWgt[f], PlanFabWgt[f]]);
                }
            }

        }
        console.log(newdata,'newdata');
        $("#requirements").jexcel({
            colHeaders: ["Combo","Component","Color","Size Spec Code / Fit","Garment Part","Fabric Detail","Fin. DIA / DIM (W * H)","Unit Of Measure","Req. Fab. Wgt. (Kgs.)"
            ,"Plan. Fab. Wgt. (Kgs.)"],
            colWidths: [100,100,100,100,100,300,90,60,80,80],
            columns: [
                {type: 'text', wordWrap: true, readOnly: true},
                {type: 'text', wordWrap: true, readOnly: true},
                {type: 'text', wordWrap: true, readOnly: true},
                {type: 'text', wordWrap: true, readOnly: true},
                {type: 'text', wordWrap: true, readOnly: true},
                {type: 'text', wordWrap: true, readOnly: true},
                {type: 'text', wordWrap: true, readOnly: true},
                {type: 'dropdown', source: UnitsArrJson, readOnly: true},
                {type: 'text', wordWrap: true, readOnly: true},
                {type: 'text', wordWrap: true, readOnly: true},
            ],
            data: newdata
        });
        var requirementsTbl = $("#requirements").jexcel('getData');

        var JoinReqFabWgt = []; var JoinPlanFabWgt = []; var NewJoinReqFabWgt = [];

        jQuery.each(requirementsTbl,function (i,el) {
            if(el[6] != "")
                var group = el[0]+"#"+el[1]+"#"+el[2]+"#"+el[4]+"#"+el[5]+"#"+el[6];


            if(el[8] != "")
                JoinReqFabWgt = fnPopulateValueArray(JoinReqFabWgt,group,el[8]);
            if(el[9] != "")
                JoinPlanFabWgt = fnPopulateValueArray(JoinPlanFabWgt,group,el[9]);
            GlbUnits[group] = el[7];
        });

        console.log(JoinReqFabWgt,'JoinReqFabWgt'); console.log(JoinPlanFabWgt,'JoinPlanFabWgt');

        var ConsolidatedReqData = [];
        var NewTest = []; var AnotherNew = [];
        console.log(FinishingGsmGroup,'FinishingGsmGroup');
        for (var key in JoinReqFabWgt) {
            console.log(key,'key');
            var ReqFW = fnSumSizeArrayValue(JoinReqFabWgt[key]);
            var PlanFab = fnSumSizeArrayValue(JoinPlanFabWgt[key]);
            var FinDiaAlone = key.substring(key.lastIndexOf('#')+1);
            var GetColor = key.split('#');
            var ArrSecondLevelColor = GetColor[2].split(';');
            var SecondLevelColorJoined = key.replace('/;/g','#');
            console.log(SecondLevelColorJoined,'SecondLevelColorJoined');
            for(var ss = 0; ss < ArrSecondLevelColor.length; ss++) {
                console.log(ArrSecondLevelColor[ss],'s colors');
                //GetColor[0]+"#"+GetColor[1]+"#"+
            }

            var FgGroupKey = key.substring(0,key.lastIndexOf('#'));
            console.log(FgGroupKey,'FgGroupKey');
            var FgGroup = FinishingGsmGroup[SecondLevelColorJoined];
            console.log(FgGroup,'FgGroup');
            //var FinishingGsm = FgGroup.substring(FgGroup.indexOf('-')+1);
            var FinishingGsm = "0.120";
            //console.log(FgGroupKey,'FgGroupKey');
            var RemovedHash = FgGroupKey.split('#');
            /*            ConsolidatedReqData.push([RemovedHash[0],RemovedHash[1],RemovedHash[2],RemovedHash[3],RemovedHash[4]],
                            ["","","","","",FinishingGsm,"",FinDiaAlone,"",ReqFW.toFixed(3),PlanFab.toFixed(3)]);*/
            ConsolidatedReqData.push([RemovedHash[0],RemovedHash[1],RemovedHash[2],RemovedHash[3],RemovedHash[4],"",FinishingGsm,"",FinDiaAlone,"",ReqFW.toFixed(3),PlanFab.toFixed(3)]);
        }
        //console.log(ConsolidatedReqData,'ConsolidatedReqData');
        //console.log(find_duplicate_in_array(ConsolidatedReqData),'oo');
        var UniqueConsolidateFab = []; var ArrFirstFive = {}; var FinGsm = [], RemainingData = [], Req = [], Plan = [];
        jQuery.each(ConsolidatedReqData,function(i,el) {
            var firstfour = el[0]+"#"+el[1]+"#"+el[2]+"#"+el[3]+"#"+el[4];
            if(el[6] != "" && el[8] != "" && el[10] != "" && el[11] != "") {
                ArrFirstFive = fnPopulateValueArray(ArrFirstFive,firstfour,el[8]);
                FinGsm = fnPopulateValueArray(FinGsm,firstfour,el[6]);
                Req = fnPopulateValueArray(FinGsm,firstfour+"#"+el[8],el[10]);
                Plan = fnPopulateValueArray(FinGsm,firstfour+"#"+el[8],el[11]);
            }
        });
        var FinalConsolidate = [];
        console.log(ArrFirstFive,'ArrFirstFive');
        jQuery.each(ArrFirstFive,function (index,el) {
            var keys = index.split('#');
            var SortArrFinDia = el.split('-');
            var fg = FinGsm[index].split('-');
            //console.log(SortArrFinDia.sort(function (a,b) {return a - b }),'sort');
            var ArrFinDia = SortArrFinDia.sort(function (a,b) {return a - b });
            var TotalPlanFabWgt = 0; var TotalReqFabWgt = 0; var SplitGarmentPartsOne = []; var SplitGarmentPartsTwo = [];
            for(var i = 0; i < ArrFinDia.length; i++) {
                if(ArrFinDia[i] != "undefined" && ArrFinDia[i] != "") {
                    console.log(Req[index+"#"+ArrFinDia[i]],'Req[index+"#"+ArrFinDia[i]]');
                    var ReqAndPlanFabWgt = Req[index+"#"+ArrFinDia[i]].split('-');
                    console.log(FinGsm,'FinGsm');
                    console.log(Req,'Req');
                    console.log(index+"#"+ArrFinDia[i],'index+"#"+ArrFinDia[i]');
                    console.log(Plan,'Plan');
                    var PlanFabWgt = Plan[index+"#"+ArrFinDia[i]].split('-');
                    console.log(PlanFabWgt,'PlanFabWgt');
                    var Um = GlbUnits[index+"#"+ArrFinDia[i]];
                    console.log(GlbUnits,'GlbUnits');
                    if(fg[i] != "undefined") {
                        if(ReqAndPlanFabWgt[1] != "undefined" && ReqAndPlanFabWgt[2] != "undefined") {
                            TotalPlanFabWgt += Number(ReqAndPlanFabWgt[2]);
                            TotalReqFabWgt += Number(ReqAndPlanFabWgt[1]);
                            var UnSplittedColor = keys[2];
                            if(UnSplittedColor.indexOf(';') >= 0) {
                                var ArrSecondLevelColor = UnSplittedColor.split(';');
                                for(var ss = 0; ss < ArrSecondLevelColor.length; ss++) {
                                    var CurrentGroup        = keys[0]+"#"+keys[1]+"#"+jsTrim(ArrSecondLevelColor[ss]);
                                    console.log(CurrentGroup,'CurrentGroup');
                                    console.log(KnitGarmentPartsCount,'KnitGarmentPartsCount');
                                    SplitGarmentParts = KnitGarmentPartsCount[CurrentGroup];
                                    console.log(SplitGarmentParts,'SplitGarmentParts');
                                    console.log(SplitGarmentParts.substring(SplitGarmentParts.indexOf('-')+1),'remove - ');
                                    var HypenRemovedPartFabricFinGsm = SplitGarmentParts.substring(SplitGarmentParts.indexOf('-')+1);
                                    var PartFabricFinGsm = HypenRemovedPartFabricFinGsm.split('#');
                                    console.log(PartFabricFinGsm[0],'',PartFabricFinGsm[1],'',PartFabricFinGsm[2]);
                                    FinalConsolidate.push([keys[0], keys[1], jsTrim(ArrSecondLevelColor[ss]), PartFabricFinGsm[0], PartFabricFinGsm[1], "", PartFabricFinGsm[2], "", ArrFinDia[i], Um, ReqAndPlanFabWgt[1], ReqAndPlanFabWgt[2]]);
                                }
                            }
                            else {
                                FinalConsolidate.push([keys[0], keys[1], keys[2], keys[3], keys[4], "", fg[i], "", ArrFinDia[i], Um, ReqAndPlanFabWgt[1], ReqAndPlanFabWgt[2]]);
                            }
                        }
                        /*
                        if(ReqAndPlanFabWgt[1] != "undefined" && ReqAndPlanFabWgt[2] != "undefined") {
                            TotalPlanFabWgt += Number(ReqAndPlanFabWgt[2]);
                            TotalReqFabWgt += Number(ReqAndPlanFabWgt[1]);
                            var UnSplittedColor = keys[2];
                            if(UnSplittedColor.indexOf(';') >= 0) {
                                var ArrSecondLevelColor = UnSplittedColor.split(';');
                                var CurrentGroupone        = keys[0]+"#"+keys[1]+"#"+jsTrim(ArrSecondLevelColor[0]);
                                SplitGarmentPartsOne = KnitGarmentPartsCount[CurrentGroupone];
                                var HypenRemovedPartFabricFinGsmOne = SplitGarmentPartsOne.substring(SplitGarmentPartsOne.indexOf('-')+1);
                                var PartFabricFinGsmOne = HypenRemovedPartFabricFinGsmOne.split('#');
                                FinalConsolidate.push([keys[0], keys[1], jsTrim(ArrSecondLevelColor[0]), PartFabricFinGsmOne[0], PartFabricFinGsmOne[1], "", PartFabricFinGsmOne[2], "", ArrFinDia[i], Um, ReqAndPlanFabWgt[1], ReqAndPlanFabWgt[2]]);
                            }

                            FinalConsolidate.push([keys[0], keys[1], keys[2], keys[3], keys[4], "", fg[i], "", ArrFinDia[i], Um, ReqAndPlanFabWgt[1], ReqAndPlanFabWgt[2]]);

                            if(UnSplittedColor.indexOf(';') >= 0) {
                                var ArrSecondLevelColor = UnSplittedColor.split(';');
                                var CurrentGrouptwo        = keys[0]+"#"+keys[1]+"#"+jsTrim(ArrSecondLevelColor[1]);
                                SplitGarmentPartsTwo = KnitGarmentPartsCount[CurrentGrouptwo];
                                var HypenRemovedPartFabricFinGsmTwo = SplitGarmentPartsTwo.substring(SplitGarmentPartsTwo.indexOf('-')+1);
                                var PartFabricFinGsmTwo = HypenRemovedPartFabricFinGsmTwo.split('#');
                                FinalConsolidate.push([keys[0], keys[1], jsTrim(ArrSecondLevelColor[1]), PartFabricFinGsmTwo[0], PartFabricFinGsmTwo[1], "", PartFabricFinGsmTwo[2], "", ArrFinDia[i], Um, ReqAndPlanFabWgt[1], ReqAndPlanFabWgt[2]]);
                            }
                        }
                        */
                    }
                }
            }
            FinalConsolidate.push(["","","","","","","","","Total","",TotalReqFabWgt.toFixed(3),TotalPlanFabWgt.toFixed(3)]);
        });

        var UnitsArrJson = [{"id":"1","name":"Nos."},{"id":"2","name":"%"},{"id":"3","name":"Gms."},{"id":"4","name":"Kgs."},{"id":"5","name":"Inches."},{"id":"6","name":"Cms."}];
        $("#ConsolidatedReqData").jexcel({
            colHeaders: ["Combo","Component","Color","Garment Part","Fabric Detail","Yarn Count","Fin. GSM","Dyeing Type","Fin. DIA / DIM (W * H)","Unit Of Measure","Req. Fab. Wgt. (Kgs.)"
                ,"Plan. Fab. Wgt. (Kgs.)"],
            colWidths: [100,100,100,80,300,60,80,90,70,70,80,80],
            columns: [
                {type: 'text', wordWrap: true, readOnly: true},
                {type: 'text', wordWrap: true, readOnly: true},
                {type: 'text', wordWrap: true, readOnly: true},
                {type: 'text', wordWrap: true, readOnly: true},
                {type: 'text', wordWrap: true, readOnly: true},
                {type: 'dropdown', source: GlbYarnCount, wordWrap: true},
                {type: 'text', wordWrap: true, readOnly: true},
                {type: 'dropdown', source: GlbDyeingType, wordWrap: true},
                {type: 'text', wordWrap: true, readOnly: true},
                {type: 'dropdown', source: UnitsArrJson, readOnly: true},
                {type: 'text', wordWrap: true, readOnly: true},
                {type: 'text', wordWrap: true, readOnly: true},
            ],
            //data: ConsolidatedReqData
            data: FinalConsolidate
        });
        var a = 0;

    }

    $("#selectAllCheckbox").click(function () {
        $("#CumulativeSixthATbl").jexcel('updateSettings',{
            table : function (instance, cell, col, row, val, id) {
                if(col == 17) {
                    console.log(col,'col');
                    console.log(val,'val');
                    $(cell).children().prop('checked',true);
                }
            }
        });
        //$("#CumulativeSixthATbl").jexcel('setValue',R1,'1');
    });

    function CheckAll() {

    }

    $(function() {
        /*
        var CumulativeSixthATbl = JSON.parse(localStorage.getItem('SixthATblCumulative'));
        //var CumulativeSixthATbl = $("#CumulativeSixthATbl").jexcel('getData');
        var Knitting = JSON.parse(localStorage.getItem('FabricKnitTblLs')); var KnitGarmentPartsCount = [];
        for(var i = 0; i < Knitting.length; i++) {
            var GroupOne = Knitting[i][0]+"#"+Knitting[i][1]+"#"+Knitting[i][2];
            KnitGarmentPartsCount = fnPopulateValueArray(KnitGarmentPartsCount,GroupOne,Knitting[i][3]+"#"+Knitting[i][4]+"#"+Knitting[i][5]);
        }
        var ColHeaders      = ""; var SizeChart           = localStorage.getItem('SelSizeChartTextLs');  var finalres = 0;
        var ArrSizeChartHeader = SizeChart.split(",");
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
        ArrColHeaderFinal.push('Itemized P.O. Qty. / Sample Qty.'); ArrColHeaderFinal.push('Pcs. / Set'); ArrColHeaderFinal.push('Intake Qty.<br/>(Nos.)');
        ArrColHeaderFinal.push('Itemized <br/>Qty. (Pcs.)');
        var ColHeaders          = ""; var ArrReadOnlyInfo     =  new Array();
        var ArrHeader           = ["Garment Part","Fabric Details","Finishing GSM","Description","Unit Of Measure"];
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
        var UnitsArrJson = [{"id":"1","name":"Nos."},{"id":"2","name":"%"},{"id":"3","name":"Gms."},{"id":"4","name":"Kgs."},{"id":"5","name":"Inches."},{"id":"6","name":"Cms."}];
        ArrHeader.push('Total'); var GarmentParts = [];

        for(var i = 0; i < CumulativeSixthATbl.length; i++) {
            var TableSno = 'Table No: ';
            TableSno += Number(i) + 1;
            $("#FabricProgramCalc").append('<br/><br/><div class="mainheading box box-primary"><h4>' + TableSno + '</h4> </div><div id="fabricprogrammain_' + i + '"></div>');

            //
            var UnSplittedColor = CumulativeSixthATbl[i][2];
            var SplitGarmentParts = [];
            var ArrSecondLevelColor = [];
            var rslt = [];
            console.log(UnSplittedColor.indexOf(';'), 'UnSplittedColor.indexOf');

            if (UnSplittedColor.indexOf(';') >= 0) {
                ArrSecondLevelColor = UnSplittedColor.split(';');
                for (var s = 0; s < ArrSecondLevelColor.length; s++) {
                    var CurrentGroup = CumulativeSixthATbl[i][0] + "#" + CumulativeSixthATbl[i][1] + "#" + jsTrim(ArrSecondLevelColor[s]);
                    console.log(CurrentGroup, 'CurrentGroup in spliting loop');
                    SplitGarmentParts.push(fnGroupArrayValue(KnitGarmentPartsCount[CurrentGroup]));
                }
                rslt = [].concat.apply([], SplitGarmentParts);
                GarmentParts = [];
            }
            else {
                var CurrentGroup = CumulativeSixthATbl[i][0] + "#" + CumulativeSixthATbl[i][1] + "#" + CumulativeSixthATbl[i][2];
                GarmentParts = fnGroupArrayValue(KnitGarmentPartsCount[CurrentGroup]);
            }
            if (rslt) for (var r = 0; r < rslt.length; r++) GarmentParts.push(rslt[r]);

            //
            //var CccGroup        = CumulativeSixthATbl[i][0]+"#"+CumulativeSixthATbl[i][1]+"#"+CumulativeSixthATbl[i][2];
            //var GarmentParts    = fnGroupArrayValue(KnitGarmentPartsCount[CccGroup]);

            $("#fabricprogrammain_" + i).jexcel({
                colHeaders: ArrColHeaderFinal,
                colWidths: [110, 110, 110, 90, 110, 45, 45, 45, 45, 45, 45, 45, 45, 100, 70, 70, 100],
                columns: [
                    {type: 'text', wordWrap: true, readOnly: true},
                    {type: 'text', wordWrap: true, readOnly: true},
                    {type: 'text', wordWrap: true, readOnly: true},
                    {type: 'text', wordWrap: true, readOnly: true},
                    {type: 'text', wordWrap: true, readOnly: true},
                    {type: 'numeric', wordWrap: true, readOnly: true},
                    {type: 'numeric', wordWrap: true, readOnly: true},
                    {type: 'numeric', wordWrap: true, readOnly: true},
                    {type: 'numeric', wordWrap: true, readOnly: true},
                    {type: 'numeric', wordWrap: true, readOnly: true},
                    {type: 'numeric', wordWrap: true, readOnly: true},
                    {type: 'numeric', wordWrap: true, readOnly: true},
                    {type: 'numeric', wordWrap: true, readOnly: true},
                    {type: 'text', wordWrap: true, readOnly: true},
                    {type: 'text', wordWrap: true, readOnly: true},
                    {type: 'text', wordWrap: true, readOnly: true},
                    {type: 'text', wordWrap: true, readOnly: true}
                ],
                data: [CumulativeSixthATbl[i]]
            });
            for (var x = 0; x < GarmentParts.length; x++) {
                //console.log(localStorage.getItem('subchild_'+i+'_'+x),'ls');
                var setsubdata = localStorage.getItem('subchild_' + i + '_' + x);
                //console.log(JSON.parse(setsubdata));
                if (localStorage.getItem('subchild_' + i + '_' + x)) {
                    $("#FabricProgramCalc").append('<div id="subchild_' + i + '_' + x + '" style="margin-top: 20px" class="SubTbl"></div>');
                    $("#subchild_" + i + "_" + x).jexcel({
                        onchange: function (instance, cell, value) {
                            var start = 5;
                            for (var i = 0; i < ArrSizeChartHeader.length; i++) {
                                console.log(CumulativeSixthATbl[i][start], 'CumulativeSixthATbl[i][start]');
                                fnCalculation(instance, start, CumulativeSixthATbl[i][start], cell, value);
                                start++;
                            }
                        },
                        colHeaders: ArrHeader,
                        colWidths: [80, 200, 65, 100, 60, 70, 70, 70, 70, 70, 70, 70, 70, 90],
                        columns: [
                            {type: 'text', wordWrap: true, readOnly: true},
                            {type: 'text', wordWrap: true, readOnly: true},
                            {type: 'text', wordWrap: true, readOnly: true},
                            {type: 'text', wordWrap: true, readOnly: true},
                            {type: 'dropdown', source: UnitsArrJson, wordWrap: true},
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
                        data: JSON.parse(setsubdata)
                        //
                    });
                    $("#subchild_" + i + "_" + x).jexcel('updateSettings', {
                        table: function (instance, cell, col, row, val, id) {
                            var start = 5;
                            for (var i = 0; i < ArrSizeChartHeader.length; i++) {
                                if (col == start) {
                                    if (row == 0) {
                                        if ($(cell).is('[readOnly]')) $(cell).html('');
                                        else $(cell).html(CumulativeSixthATbl[i][15]);
                                    }
                                }
                                start++;
                            }
                            if (col == 4) {
                                if (row == 7) {

                                } else {
                                    $(cell).addClass('readonly');
                                }
                            }
                        }
                    });
                }
            }
        }
        */
        var _lsTotal=0,_xLen,_x;for(_x in localStorage){ if(!localStorage.hasOwnProperty(_x)){continue;} _xLen= ((localStorage[_x].length + _x.length)* 2);_lsTotal+=_xLen; console.log(_x.substr(0,50)+" = "+ (_xLen/1024).toFixed(2)+" KB")};console.log("Total = " + (_lsTotal / 1024).toFixed(2) + " KB");
    });


    function FnYarnDyeingItemizedColorWiseQtyBreakUp() {
        var ConsolidatedReqData = $("#ConsolidatedReqData").jexcel('getData');
        console.log(ConsolidatedReqData,'ConsolidatedReqData');
        var YarnDyeingItemizedColorWiseQtyBreakUp = [];
        for(var i = 0; i < ConsolidatedReqData.length; i++) {
            console.log(ConsolidatedReqData[i][7],'ConsolidatedReqData[i][7]');
            if(ConsolidatedReqData[i][7] == "Yarn Dye") {
                var YarnDyeText = "Yarn Dye";
                console.log(i,' i value');
                console.log(ConsolidatedReqData[i][2],'ConsolidatedReqData[i][2]');
                var YarnDyeRow = ConsolidatedReqData[i];
                console.log(YarnDyeRow,'YarnDyeRow');
                if(YarnDyeRow[2].indexOf('-') >= 0) {
                    var ArrYarnDueColor = YarnDyeRow[2].split('-');
                    for(var x = 0; x < ArrYarnDueColor.length; x++) {
                        YarnDyeingItemizedColorWiseQtyBreakUp.push(
                            [ConsolidatedReqData[i][0],ConsolidatedReqData[i][1],ArrYarnDueColor[x],ConsolidatedReqData[i][3],ConsolidatedReqData[i][4],ConsolidatedReqData[i][5]
                            ,ConsolidatedReqData[i][6],YarnDyeText,"","","","",ConsolidatedReqData[i][11]]);
                    }
                }
                else {
                    YarnDyeingItemizedColorWiseQtyBreakUp.push();
                }
            }
            else {
                // Other Dyes
            }
        }
        console.log(YarnDyeingItemizedColorWiseQtyBreakUp,'YarnDyeingItemizedColorWiseQtyBreakUp');
        $("#YarnDyeingItemizedColorWiseQtyBreakUp").jexcel({
            colHeaders:["Combo","Component","Color","Garment Parts","Fabric Details","Yarn Count","Fin. GSM","Dyeing Type","No. Of Feed. Per Repeat","No. Of Feed. Per Color","Color (%)","Plan. Fab. Wgt. Subtotal","Req. Yarn Wgt. (Kgs.)"],
            colWidths:[100,100,100,100,200,80,80,80,80,80,80,80,80],
            columns:[
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
                {type: 'text', wordWrap: true},
            ],
            data: YarnDyeingItemizedColorWiseQtyBreakUp
        });
        var nooffeedperrepeat = 0; var colorpercent = 0;
        $("#YarnDyeingItemizedColorWiseQtyBreakUp").jexcel('updateSettings',{
            table:function (instance, cell, col, row, val, id) {
                if(col == 0) { nooffeedperrepeat = 0; colorpercent = 0; }
                if (col == 8) {
                    nooffeedperrepeat = Number($(cell).text());
                    console.log(nooffeedperrepeat,'nooffeedperrepeat in first');
                }
                if (col == 9) {
                    var nooffeedpercolor = Number($(cell).text()) * 100;
                    console.log(nooffeedpercolor,'nooffeedpercolor');
                    console.log(nooffeedperrepeat,'nooffeedperrepeat');
                    colorpercent = nooffeedpercolor / nooffeedperrepeat;

                }
                if (col == 10) {
                    console.log(colorpercent,'colorpercent');
                    $(cell).text(colorpercent);
                }
            }
        });

    }
</script>
<style>
    .table > tbody > tr > td, .table > tbody > tr > th, .table > tfoot > tr > td, .table > tfoot > tr > th, .table > thead > tr > td, .table > thead > tr > th {padding:5px;}
    .jexcel>thead>tr>td {font-size:12px !important;}
    table {font-size:12px;}
    .pd0{padding:0px;}
    .jexcel {white-space: normal !important;}
    .jexcel>tbody>tr>td {white-space: normal !important; word-wrap:break-word !important;}
</style>
<?php $this->load->view(CNFCOMPANY.'template/pagefooter');?>