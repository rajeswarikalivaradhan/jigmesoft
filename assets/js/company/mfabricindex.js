//var GlbYarnCount = ["20s","30s","20s / 30s"];
//var GlbYarnCount = [];
console.log(GlbYarnCount,'GlbYarnCount');
var SizeChart = localStorage.getItem('SelSizeChartTextLs');
var ItemizedSizeWiseQtyBrkUpFifthTblLs = localStorage.getItem('ItemizedSizeWiseQtyBrkUpFifthTblLs');
var SixthATblCumulative = JSON.parse(localStorage.getItem('SixthATblCumulative'));
var KnitTblLs = localStorage.getItem('FabricKnitTblLs');
var GlbDyeingType = ["FD","YD","SDB"];

//var Glbfabricfinish = [{"id":"1","name":"Nos."}];
//var GlbfabricfinishStageForm = [{"id":"1","name":"Nos."}];
function FnGetDropdownDatas() {
    //MakeAsynPostRequest(base_path+GlbCompanyFdr+'fabricprogram/index',"rfrom=1",'json',FnGetDropdownDatasRes);
}

function FnGetDropdownDatasRes(data) {
    //console.log(data,'data');
    //console.log(data.ArrYarnCount,'data');
    //GlbYarnCount = data.ArrYarnCount;
    //console.log(GlbYarnCount,'GlbYarnCount');
    //console.log(JSON.parse(Glbfabricfinish),'again parse Glbfabricfinish');
}

//console.log(KnitTblLs,'KnitTblLs');
//console.log(Glbfabricfinish,'Glbfabricfinish');

$("#FabricDetails").jexcel({
    colHeaders: ['Combo', 'Component', 'Colour', 'Garment<br/>Parts', 'Fabric Blend (%)','Fabric Content','Fabric Name', 'Finishing<br/>GSM', 'Yarn Special<br/>Request', 'Fabric Finish', 'Fabric Finish<br/>Stage / Form'],
    colWidths: [105, 105, 100, 120, 123,123,123, 100, 100, 110, 120],
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
        var cellName = $(instance).jexcel('getColumnNameFromId', $(cell).prop('id'));
        //console.log(cellName);
        if(cellName.indexOf('R') == 0) {
            if(val === true) {
                var ArrRowId = $(cell).prop('id').split('-');
                FnCCTbl(instance, cell, val, ArrRowId[1]);
            }
        }
    }
});

function fnPopulateValueArray(ArrName, KeyValue, InsertVal) {
    var indexPlace = jQuery.inArray(KeyValue, ArrName);
    if (indexPlace) {
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
            var firstMul = parseInt(sizeid) * $(currtableid+" #" + zerorow).html();
            //console.log(firstMul,'firstMul');
            var percent = parseInt($(currtableid+" #" + firstrow).text()) / 100;
            //console.log(percent,'percent');
            planqty = firstMul * percent + parseInt(sizeid) * $(currtableid+" #"+zerorow).html();
            //console.log(parseInt(sizeid) * $(currtableid+" #"+zerorow).html(),'sizeid * $(currtableid+" #"+zerorow).html()');
            //console.log(planqty,'planqty'); console.log(currtableid+" #" + secondrow,'currtableid+" #" + secondrow in 1');
            $(currtableid+" #" + secondrow).html(planqty);
        }
    }
    if ($(cell).prop('id') == thirdrow) {
        if (value != "") {
            reqfabricwgt = 0;
            //console.log(planqty,'planqty');
            planqty = Number($(currtableid+" #"+secondrow).text());
            //console.log(reqfabricwgt,'reqfabricwgt'); console.log($(currtableid+" #" + thirdrow).text(),'$(currtableid+" #" + thirdrow).text()');
            reqfabricwgt = planqty * Number($(currtableid+" #" + thirdrow).text());
            //console.log(reqfabricwgt.toFixed(3),'reqfabricwgt.toFixed(3) in 3');
            $(currtableid+" #" + fourthrow).html(reqfabricwgt.toFixed(3));
        }

    }
    if ($(cell).prop('id') == fifthrow) {
        if (value != "") {
            planfabricwgt = 0;
            reqfabricwgt = Number($(currtableid+" #"+ fourthrow).text());
            //console.log(reqfabricwgt,'reqfabricwgt in 5'); console.log($(currtableid+" #" + fifthrow).text(),'$(currtableid+" #" + fifthrow).text()');
            planfabricwgt = reqfabricwgt * parseInt($(currtableid+" #" + fifthrow).text());
            planfabricwgt = planfabricwgt / 100;
            planfabricwgt = planfabricwgt + Number($(currtableid+" #" + fourthrow).text());
            //console.log(planfabricwgt,'planfabricwgt'); console.log($(currtableid+" #" + fourthrow).text(),'$(currtableid+" #" + fourthrow).text() in 5');
            $(currtableid+" #" + sixthrow).html(planfabricwgt.toFixed(3));
        }
    }
    $(currtableid+" #15-2").text(Number($(currtableid+" #7-2").text()) + Number($(currtableid+" #8-2").text()) + Number($(currtableid+" #9-2").text())
        + Number($(currtableid+" #10-2").text()) + Number($(currtableid+ " #11-2").text()) + Number($(currtableid+ " #12-2").text()) +
        Number($(currtableid+" #13-2").text()) + Number($(currtableid+" #14-2").text()));

    var ReqFabWgtTotal = Number($(currtableid+" #7-4").text()) + Number($(currtableid+" #8-4").text()) + Number($(currtableid+" #9-4").text())
        + Number($(currtableid+" #10-4").text()) + Number($(currtableid+" #11-4").text()) + Number($(currtableid+" #12-4").text()) +
        Number($(currtableid+" #13-4").text()) + Number($(currtableid+" #14-4").text());
    $(currtableid+" #15-4").html(ReqFabWgtTotal.toFixed(3));

    var total = Number($(currtableid+" #7-6").text()) + Number($(currtableid+" #8-6").text()) + Number($(currtableid+" #9-6").text())
        + Number($(currtableid+" #10-6").text()) + Number($(currtableid+" #11-6").text()) + Number($(currtableid+" #12-6").text()) +
        Number($(currtableid+" #13-6").text()) + Number($(currtableid+" #14-6").text());
    $(currtableid+" #15-6").html(total.toFixed(3));

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
        KnitGarmentParts = fnPopulateValueArray(KnitGarmentParts,GroupOne,Knitting[i][3]+"#"+Knitting[i][4]+"#"+Knitting[i][5]+"#"+Knitting[i][6]+"#"+Knitting[i][7]);
    }
    console.log(KnitGarmentParts,'KnitGarmentParts checkdkjdj');
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
            //console.log(CurrentGroup,'CurrentGroup');
            SplitGarmentParts.push(fnGroupArrayValue(KnitGarmentParts[CurrentGroup]));
        }
        var rslt = [].concat.apply([],SplitGarmentParts);
        //console.log(rslt,'rslt');
        //GarmentParts = [];
    }
    else {
        var CurrentGroup        = OSixthATblCumulative[CellId][0]+"#"+OSixthATblCumulative[CellId][1]+"#"+OSixthATblCumulative[CellId][2];
        GarmentParts        = fnGroupArrayValue(KnitGarmentParts[CurrentGroup]);
    }
    //console.log(GarmentParts,'GarmentParts before push');
    if(rslt) for(var r = 0; r < rslt.length; r++) GarmentParts.push(rslt[r]);
    //console.log(GarmentParts,'GarmentParts');
    //console.log(KnitGarmentParts[CurrentGroup],'KnitGarmentParts[CurrentGroup]');

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
    var UnitsArrJson = [{"id":"1","name":"Nos."},{"id":"2","name":"%"},{"id":"3","name":"Gms."},{"id":"4","name":"Kgs."},{"id":"5","name":"Inches."},{"id":"6","name":"Cms."}];
    ArrHeader.push('Total');
    console.log(GarmentParts,'GarmentParts sfdsaf dafadfa');
    for(var i = 0; i < GarmentParts.length; i++) {
        console.log(GarmentParts[i],'GarmentParts[i]');
        var GpBlendContentName  = GarmentParts[i].split('#');
        var Garmentpart = GpBlendContentName[0];
        var FabricBlend = GpBlendContentName[1];
        var FabricContent = GpBlendContentName[2];
        var FabricName = GpBlendContentName[3];
        var FinGsm = GpBlendContentName[4];

/*
        var Fabric  = GpEach.substring(GpEach.indexOf('#')+1,GpEach.lastIndexOf('#'));
        var Gp      = GpEach.substring(0,GpEach.indexOf('#'));
        var FinGsm  = GpEach.substring(GpEach.lastIndexOf('#')+1);
*/

        $("#FabricProgramCalc").append('<div id="subchild_'+CellId+'_'+i+'" style="margin-top: 20px" class="SubTbl"></div>');
        $("#subchild_"+CellId+"_"+i).jexcel({
            colHeaders: ArrHeader,
            colWidths: [80, 50,70,70, 65, 170, 70, 70, 70, 70, 70, 70, 70, 70, 70, 90],
            columns: [
                {type: 'text', wordWrap: true, readOnly: true},
                {type: 'text', wordWrap: true, readOnly: true},
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
                [Garmentpart,FabricBlend,FabricContent,FabricName,FinGsm,'Intake Qty.','1'],
                ['','','','','','Excess Qty.','2'],
                ['','','','','','Plan. Qty.','1'],
                ['','','','','','Piece Wgt.','3'],
                ['','','','','','Req. Fab. Wgt.','4'],
                ['','','','','','Processing Loss','2'],
                ['','','','','','Plan. Fab. Wgt.','4'],
                ['','','','','','Fin. DIA. / DIM. (W * H)','5']
            ],
            onchange: function (instance, cell, value) {
                var start = 5; var SizeColumnsStarts = 7;
                for(var i = 0; i < ArrSizeChartHeader.length; i++) {
                    //console.log(OSixthATblCumulative[CellId][start],'OSixthATblCumulative[CellId][start]');
                    fnCalculation(instance,SizeColumnsStarts, OSixthATblCumulative[CellId][start],cell,value);
                    SizeColumnsStarts++;
                }
            }
        });
        $("#subchild_"+CellId+"_"+i).jexcel('updateSettings',{
            table: function (instance, cell, col, row, val, id) {
                var start = 7;
                for(var i = 0; i < ArrSizeChartHeader.length; i++) {
                    if(col == start) {
                        if(row == 0) {
                            if($(cell).is('[readOnly]')) $(cell).html('');
                            else {
                                $(cell).html(OSixthATblCumulative[CellId][15]);
                                $(cell).addClass('readonly');
                            }
                        }
                    }
                    start++;
                }
                if(col == 6) {
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

function FnRequirementDetailsTbl() {
    //Save To LS
    var CumulativeSixthATbl = $("#CumulativeSixthATbl").jexcel('getData');
    var Knitting = JSON.parse(localStorage.getItem('FabricKnitTblLs')); var KnitGarmentPartsCount = [];
    for(var i = 0; i < Knitting.length; i++) {
        var GroupOne = Knitting[i][0]+"#"+Knitting[i][1]+"#"+Knitting[i][2];
        KnitGarmentPartsCount = fnPopulateValueArray(KnitGarmentPartsCount,GroupOne,Knitting[i][3]+"#"+Knitting[i][4]+"#"+Knitting[i][5]+"#"+Knitting[i][6]+"#"+Knitting[i][7]);
    }
    for(var i = 0; i < CumulativeSixthATbl.length; i++) {
        var UnSplittedColor = CumulativeSixthATbl[i][2];
        if(UnSplittedColor.indexOf(';') >= 0) {
            var ArrSecondLevelColor = UnSplittedColor.split(';');
            for(var c = 0; c < ArrSecondLevelColor.length; c++) {
                var subdata = $("#subchild_"+i+"_"+c).jexcel('getData');
                localStorage.setItem("subchild_"+i+"_"+c,JSON.stringify(subdata));
            }
        }
        else {
            var CccGroup = CumulativeSixthATbl[i][0] + "#" + CumulativeSixthATbl[i][1] + "#" + CumulativeSixthATbl[i][2];
            var GarmentParts = [];
            GarmentParts    = fnGroupArrayValue(KnitGarmentPartsCount[CccGroup]);
            for(var x = 0; x < GarmentParts.length; x++) {
                var subdata = $("#subchild_"+i+"_"+x).jexcel('getData');
                localStorage.setItem("subchild_"+i+"_"+x,JSON.stringify(subdata));
            }
        }
    }
    //Save To LS

    var UnitsArrJson = [{"id":"1","name":"Nos."},{"id":"2","name":"%"},{"id":"3","name":"Gms."},{"id":"4","name":"Kgs."},{"id":"5","name":"Inches."},{"id":"6","name":"Cms."}];

    var FinishingGsmGroup = []; var RequirementDetailsTbl = [];
    for(var i = 0; i < Knitting.length; i++) {
        var GroupOne = Knitting[i][0]+"#"+Knitting[i][1]+"#"+Knitting[i][2];
        FinishingGsmGroup[GroupOne+"#"+Knitting[i][3]+"#"+Knitting[i][4]+"#"+Knitting[i][5]+"#"+Knitting[i][6]] = Knitting[i][7];

    }
    var GarmentParts = [];
    for(var i = 0; i < CumulativeSixthATbl.length; i++) {
        var maindata = $("#fabricprogrammain_"+i).jexcel('getRowData');
        var UnSplittedColor = CumulativeSixthATbl[i][2]; var SplitGarmentParts = []; var ArrSecondLevelColor = [];
        if(UnSplittedColor.indexOf(';') >= 0) {
            ArrSecondLevelColor = UnSplittedColor.split(';');
            for(var c = 0; c < ArrSecondLevelColor.length; c++) {
                var CurrentGroup        = CumulativeSixthATbl[i][0]+"#"+CumulativeSixthATbl[i][1]+"#"+jsTrim(ArrSecondLevelColor[c]);
                SplitGarmentParts = fnGroupArrayValue(KnitGarmentPartsCount[CurrentGroup]);
                var Units = $("#subchild_"+i+"_"+c).jexcel('getColumnData',6);
                var ReqFabWgt = $("#subchild_"+i+"_"+c).jexcel('getRowData',4).splice(7,8);
                var PlanFabWgt = $("#subchild_"+i+"_"+c).jexcel('getRowData',6).splice(7,8);
                var FinDia = $("#subchild_"+i+"_"+c).jexcel('getRowData',7).splice(7,8);
                console.log(SplitGarmentParts,'SplitGarmentParts');
                var GpandFabricdetails = SplitGarmentParts[0].split('#');
                var part = GpandFabricdetails[0];
                var fabricblend = GpandFabricdetails[1];
                var fabriccontent = GpandFabricdetails[2];
                var fabricname = GpandFabricdetails[3];
                //var part   = SplitGarmentParts[0].substring(0,SplitGarmentParts[0].indexOf('#'));
                //var fabric = SplitGarmentParts[0].substring(SplitGarmentParts[0].indexOf('#')+1,SplitGarmentParts[0].lastIndexOf('#'));
                for(var f = 0; f < FinDia.length; f++) {
                    if (FinDia[f] && ReqFabWgt[f] && PlanFabWgt[f]) {
                        RequirementDetailsTbl.push([maindata[0], maindata[1], jsTrim(ArrSecondLevelColor[c]), maindata[3], part, fabricblend,fabriccontent,fabricname,
                            FinDia[f], Units[7], ReqFabWgt[f], PlanFabWgt[f]]);
                    }
                }
            }
            GarmentParts = [];
        }
        else {
            var CurrentGroup = CumulativeSixthATbl[i][0] + "#" + CumulativeSixthATbl[i][1] + "#" + CumulativeSixthATbl[i][2];
            GarmentParts = fnGroupArrayValue(KnitGarmentPartsCount[CurrentGroup]);
            for (var x = 0; x < GarmentParts.length; x++) {
                var Units = $("#subchild_" + i + "_" + x).jexcel('getColumnData', 6);
                var ReqFabWgt = $("#subchild_" + i + "_" + x).jexcel('getRowData', 4).splice(7, 8);
                var PlanFabWgt = $("#subchild_" + i + "_" + x).jexcel('getRowData', 6).splice(7, 8);
                var FinDia = $("#subchild_" + i + "_" + x).jexcel('getRowData', 7).splice(7, 8);
                var GpandFabricdetails = GarmentParts[x].split('#');
                var part = GpandFabricdetails[0];
                var fabricblend = GpandFabricdetails[1];
                var fabriccontent = GpandFabricdetails[2];
                var fabricname = GpandFabricdetails[3];
                //var part   = GarmentParts[x].substring(0,GarmentParts[x].indexOf('#'));
                //var fabric = GarmentParts[x].substring(GarmentParts[x].indexOf('#')+1,GarmentParts[x].lastIndexOf('#'));
                for (var f = 0; f < FinDia.length; f++) {
                    if (FinDia[f] && ReqFabWgt[f] && PlanFabWgt[f]) {
                        RequirementDetailsTbl.push([maindata[0], maindata[1], maindata[2], maindata[3], part, fabricblend,fabriccontent,fabricname, FinDia[f], Units[7], ReqFabWgt[f], PlanFabWgt[f]]);
                    }
                }
            }
            var CccGroup = CumulativeSixthATbl[i][0] + "#" + CumulativeSixthATbl[i][1] + "#" + CumulativeSixthATbl[i][2];
        }
    }

    $("#requirements").jexcel({
        colHeaders: ["Combo","Component","Color","Size Spec Code / Fit","Garment Part","Fabric Blend (%)","Fabric Content","Fabric Name","Fin. DIA / DIM (W * H)","Unit Of Measure","Req. Fab. Wgt. (Kgs.)"
            ,"Plan. Fab. Wgt. (Kgs.)"],
        colWidths: [100,100,100,100,100,60,100,100,90,60,80,80],
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
            {type: 'dropdown', source: UnitsArrJson, readOnly: true},
            {type: 'text', wordWrap: true, readOnly: true},
            {type: 'text', wordWrap: true, readOnly: true},
        ],
        data: RequirementDetailsTbl
    });

    var Two = [], GroupsArr = [], FinalDataCumulative = [], FininhingDia = [], GlbUniqueGroup = [], GroupFive = [], UnitOfMeasure = [], One = {};

    jQuery.each(RequirementDetailsTbl,function (index,el) {
        var GroupSix = el[0]+"#"+el[1]+"#"+el[2]+"#"+el[4]+"#"+el[5]+"#"+el[6]+"#"+el[7]+"#"+el[8]+"#"+el[9];
        var GroupFiveWoutSizeSpec = el[0]+"#"+el[1]+"#"+el[2]+"#"+el[4]+"#"+el[5]+"#"+el[6]+"#"+el[7];
        var GroupId             = jQuery.inArray(GroupSix, GroupsArr);

        if(GroupId === -1) {
            GroupsArr.push(GroupSix);
        }

        One = fnPopulateValueArray(One,GroupSix,el[10]);
        Two = fnPopulateValueArray(Two,GroupSix,el[11]);
        FininhingDia = fnPopulateValueArray(FininhingDia,GroupSix,el[8]);

        UnitOfMeasure[GroupFiveWoutSizeSpec] = el[9];
        console.log(UnitOfMeasure,'UnitOfMeasure');
    });

    console.log(One,'One'); console.log(Two,'Two'); console.log(FininhingDia,'FininhingDia');
    console.log(GroupsArr,'GroupsArr');
    console.log(UnitOfMeasure,'UnitOfMeasure');

    for(var b = 0; b < GroupsArr.length; b++) {
        var KeyVal = GroupsArr[b];
        var Oneresu = fnSumSizeArrayValue(One[KeyVal]);

        var Tworesu = fnSumSizeArrayValue(Two[KeyVal]);
        var data = KeyVal.split('#');
        FinalDataCumulative.push([data[0],data[1],data[2],data[3],data[4],data[5],data[6],"","","",data[7],"",Oneresu,Tworesu]);
    }

    var FilterFinal = {}; var FinalDataCumulativeNew = [], UniqueFirstFive = [];
    jQuery.each(FinalDataCumulative,function (index,el) {
        FilterFinal = fnPopulateValueArray(FilterFinal,el[0]+"#"+el[1]+"#"+el[2]+"#"+el[3]+"#"+el[4]+"#"+el[5]+"#"+el[6],el[10]+"|#|"+el[12]+"|#|"+el[13]);
    });

    console.log(FilterFinal,'FilterFinal');

    jQuery.each(FilterFinal,function (index,el) {
        var SplitAll = el.split('-'); var ReqFabTotal = 0; var ReqPlanTotal = 0;
        for(var i = 0; i < SplitAll.length; i++) {
            console.log(index,'index');
            var CccGarmentpart = index.split('#');
            if(SplitAll[i] != "undefined" && SplitAll[i] != "") {
                var Remaining = SplitAll[i].split('|#|');
                console.log(Remaining,'Remaining');
                //console.log(FinishingGsmGroup,'FinishingGsmGroup');
                var FirstFive = CccGarmentpart[0]+"#"+CccGarmentpart[1]+"#"+CccGarmentpart[2]+"#"+CccGarmentpart[3]+"#"+CccGarmentpart[4]+"#"+CccGarmentpart[5]+
                    "#"+CccGarmentpart[6];

                var FinGsm = FinishingGsmGroup[FirstFive];
                console.log(Remaining[1],'Remaining[1]');
                ReqFabTotal += Number(Remaining[1]);
                ReqPlanTotal += Number(Remaining[2]);
                if(jQuery.inArray(FirstFive,UniqueFirstFive) === -1) {
                    //console.log(FinGsm,'FinGsm'); console.log(UnitOfMeasure,'UnitOfMeasure');
                    var um = UnitOfMeasure[CccGarmentpart[0]+"#"+CccGarmentpart[1]+"#"+CccGarmentpart[2]+"#"+CccGarmentpart[3]+"#"+CccGarmentpart[4]+"#"+
                    CccGarmentpart[5]+"#"+CccGarmentpart[6]];

                    console.log(um,'um');

                    UniqueFirstFive.push(FirstFive);
                    FinalDataCumulativeNew.push([CccGarmentpart[0],CccGarmentpart[1],CccGarmentpart[2],CccGarmentpart[3],CccGarmentpart[4],CccGarmentpart[5],
                        CccGarmentpart[6],"",FinGsm,"",Remaining[0],um,Remaining[1],Remaining[2]]);
                }
                else {
                    FinalDataCumulativeNew.push(["","","","","","","","","","",Remaining[0],"",Remaining[1],Remaining[2]]);
                }

            }
        }
        FinalDataCumulativeNew.push(["","","","","","","","","","","","",ReqFabTotal.toFixed(3),ReqPlanTotal.toFixed(3)]);
    });

    //console.log(FinalDataCumulativeNew,'FinalDataCumulativeNew');
    $("#ConsolidatedReqData").jexcel({
        colHeaders: ["Combo","Component","Color","Garment Part","Fabric Blend (%)","Fabric Content","Fabric Name","Yarn Count","Fin. GSM","Dyeing Type","Fin. DIA / DIM (W * H)","Unit Of Measure","Req. Fab. Wgt. (Kgs.)"
            ,"Plan. Fab. Wgt. (Kgs.)"],
        colWidths: [100,100,100,80,60,100,100,60,80,90,70,70,80,80],
        columns: [
            {type: 'text', wordWrap: true, readOnly: true},
            {type: 'text', wordWrap: true, readOnly: true},
            {type: 'text', wordWrap: true, readOnly: true},
            {type: 'text', wordWrap: true, readOnly: true},
            {type: 'text', wordWrap: true, readOnly: true},
            {type: 'text', wordWrap: true, readOnly: true},
            {type: 'text', wordWrap: true, readOnly: true},
            {type: 'dropdown', source: JSON.parse(GlbYarnCount), wordWrap: true},
            {type: 'text', wordWrap: true, readOnly: true},
            {type: 'dropdown', source: GlbDyeingType, wordWrap: true},
            {type: 'text', wordWrap: true, readOnly: true},
            {type: 'dropdown', source: UnitsArrJson, readOnly: true},
            {type: 'text', wordWrap: true, readOnly: true},
            {type: 'text', wordWrap: true, readOnly: true},
        ],
        data: FinalDataCumulativeNew,
        /*onchange: function (obj,cell,val) {
            console.log(typeof val,'type val');
            if(val == "FD") {
                var cellid = $(cell).prop('id').split('-');
                var rowid = cellid[1];
                if($(obj).jexcel("td#8-"+rowid).text() != "") {
                    rowid++;
                    console.log($(obj).jexcel("td#11-"+rowid).text(),'fd plan req subtotal')
                    var planfabwgt = $(obj).jexcel("td#11-"+rowid).text();

                }
            }
        }*/
    });

    var ConsolidatedReqData = $("#ConsolidatedReqData").jexcel('getData');
    //var requirementsTblData = $("#requirements").jexcel('getData');

    if(typeof Storage !== "undefined") {
        localStorage.setItem('ConsolidatedReqDataLs','');
        localStorage.setItem('ConsolidatedReqDataLs',JSON.stringify(ConsolidatedReqData));

        /*localStorage.setItem('requirementsTblLs','');
        localStorage.setItem('requirementsTblLs',JSON.stringify(requirementsTblData));*/
    }
}

function FnYarnDyeColorSplit_SDBSplit() {
    var Knitting = JSON.parse(localStorage.getItem('FabricKnitTblLs')); var FinishingGsmGroup = [];
    for(var i = 0; i < Knitting.length; i++) {
        var GroupOne = Knitting[i][0]+"#"+Knitting[i][1]+"#"+Knitting[i][2]+"#"+Knitting[i][3]+"#"+Knitting[i][4]+"#"+Knitting[i][5]+"#"+Knitting[i][6];
        FinishingGsmGroup[GroupOne] = Knitting[i][7];
    }
    var ConsolidatedReqData = $("#ConsolidatedReqData").jexcel('getData');
    var YarnDyeRowId = 0, SDBRowId = 0, YarnDyeingItemizedColorWise = [], SDBItemizedColorWise = [];
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
/*        if (ConsolidatedReqData[i][8].indexOf('/') >= 0) {

        }*/
        if(YarnDyeRowId < ConsolidatedReqData.length) {
            YarnDyeRowId = ConsolidatedReqData.length - 1;

        }
        else {
            YarnDyeRowId++;
        }
        if(ConsolidatedReqData[YarnDyeRowId][0] == "") {

            var PlanfabSubTotalYD = ConsolidatedReqData[YarnDyeRowId][13];

        }
        if (ConsolidatedReqData[i][9] === "YD") {
            var YarnDye = ConsolidatedReqData[i];
            if (YarnDye[2].indexOf('-') >= 0) {
                var ArrYarnDyeColor = YarnDye[2].split('-');
                var FinGsm = FinishingGsmGroup[ConsolidatedReqData[i][0]+"#"+ConsolidatedReqData[i][1]+"#"+YarnDye[2]+"#"+ConsolidatedReqData[i][3]+"#"+ConsolidatedReqData[i][4]+"#"+ConsolidatedReqData[i][5]+"#"+ConsolidatedReqData[i][6]];
                for(var c = 0; c < ArrYarnDyeColor.length; c++) {
                    YarnDyeingItemizedColorWise.push([ConsolidatedReqData[i][0], ConsolidatedReqData[i][1], ArrYarnDyeColor[c], ConsolidatedReqData[i][3],
                        ConsolidatedReqData[i][4], ConsolidatedReqData[i][5],ConsolidatedReqData[i][6],ConsolidatedReqData[i][7],FinGsm,ConsolidatedReqData[i][9],PlanfabSubTotalYD,"","",""]);
                }
            }
            else {
                var FinGsm = FinishingGsmGroup[ConsolidatedReqData[i][0]+"#"+ConsolidatedReqData[i][1]+"#"+YarnDye[2]+"#"+ConsolidatedReqData[i][3]+"#"+ConsolidatedReqData[i][4]];
                YarnDyeingItemizedColorWise.push([ConsolidatedReqData[i][0], ConsolidatedReqData[i][1], YarnDye[2], ConsolidatedReqData[i][3],
                    ConsolidatedReqData[i][4], ConsolidatedReqData[i][5],ConsolidatedReqData[i][6],ConsolidatedReqData[i][7],FinGsm,ConsolidatedReqData[i][9],PlanfabSubTotalYD,"","",""]);
            }
        }
        if (ConsolidatedReqData[i][9] === "SDB") {
            var SDB = ConsolidatedReqData[i];
            if (SDB[2].indexOf('-') >= 0) {
                if(SDB[7].indexOf('/') >= 0) {
                    var ArrSDBColor = SDB[2].split('-'); var ArrYarnCount = SDB[7].split('/');
                    var FinGsm = FinishingGsmGroup[ConsolidatedReqData[i][0]+"#"+ConsolidatedReqData[i][1]+"#"+SDB[2]+"#"+ConsolidatedReqData[i][3]+"#"+ConsolidatedReqData[i][4]+"#"+ConsolidatedReqData[i][5]+"#"+ConsolidatedReqData[i][6]];
                    for (var c = 0; c < ArrSDBColor.length; c++) {
                        SDBItemizedColorWise.push([ConsolidatedReqData[i][0], ConsolidatedReqData[i][1], ArrSDBColor[c], ConsolidatedReqData[i][3],
                            ConsolidatedReqData[i][4], ConsolidatedReqData[i][5], ConsolidatedReqData[i][6],ArrYarnCount[c], FinGsm, ConsolidatedReqData[i][9], PlanfabSubTotalSDB,"", "", ""]);
                    }
                }
            }
        }
    }
    $("#CountContentSDBSplit").jexcel({
        data:SDBItemizedColorWise,
        colHeaders: ["Combo","Component","Color","Garment Part","Fabric Blend (%)","Fabric Content","Fabric Name","Yarn Count","Finishing GSM","Dyeing Type","Plan Fab. Wgt. Subtotal (Kgs.)","No. of Feed. Per Repeat","No. of Feed. per Color"
            ,"Color (%)","Req. Yarn Wgt. (Kgs.)"],
        colWidths: [100,100,100,80,60,100,100,60,70,60,70,70,50,90,90],
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
            {type: 'text', wordWrap: true},
            {type: 'text', wordWrap: true},
            {type: 'text', wordWrap: true},
            {type: 'text', wordWrap: true, readOnly: true},
            {type: 'text', wordWrap: true},
        ]
    });
    var NoofFeedPerRepeat = 0; var NoofFeedPerColor = 0; var ColorPercent = 0; var PlanFabWgt = 0; var ReqYarnWgt = 0; var CalcColor = 0;
    $("#CountContentSDBSplit").jexcel('updateSettings',{
        table:function (instance, cell, col, row, val, id) {
            if(col == 0) {
                NoofFeedPerRepeat = 0; NoofFeedPerColor = 0; ColorPercent = 0; PlanFabWgt = 0; ReqYarnWgt = 0; CalcColor = 0;
            }
            if(col == 10) {
                PlanFabWgt = Number($(cell).html());
                //console.log(PlanFabWgt,'PlanFabWgt');
            }

            if(col == 11) {
                NoofFeedPerRepeat = Number($(cell).html());
            }
            if(col == 12) {
                NoofFeedPerColor = Number($(cell).html());
            }
            if(col == 13) {
                var multiplyHundred = Number(NoofFeedPerColor) * 100;
                ColorPercent = multiplyHundred / NoofFeedPerRepeat;
                $(cell).text(ColorPercent.toFixed(3));
                CalcColor = Number(ColorPercent) / 100;
                ReqYarnWgt = PlanFabWgt * CalcColor;
            }
            if(col == 14) {
                //console.log(ReqYarnWgt,'ReqYarnWgt');
                $(cell).text(ReqYarnWgt.toFixed(3));
            }
        }
    });

    $("#YarnDyeColorSplit").jexcel({
        data:YarnDyeingItemizedColorWise,
        colHeaders: ["Combo","Component","Color","Garment Part",
            "Fabric Blend (%)","Fabric Content","Fabric Name",
            "Yarn Count",
            "Finishing GSM",
            "Dyeing Type",
            "Plan Fab. Wgt. Subtotal (Kgs.)",
            "No. of Feed. Per Repeat",
            "No. of Feed. per Color",
            "Color (%)",
            "Req. Yarn Wgt. (Kgs.)"],
        colWidths: [100,100,100,80,60,100,100,60,70,60,70,70,50,90,90],
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
            {type: 'text', wordWrap: true},
            {type: 'text', wordWrap: true},
            {type: 'text', wordWrap: true},
            {type: 'text', wordWrap: true,readOnly: true},
            {type: 'text', wordWrap: true},
        ]
    });
    var NoofFeedPerRepeat = 0; var NoofFeedPerColor = 0; var ColorPercent = 0; var PlanFabWgt = 0; var ReqYarnWgt = 0; var CalcColor = 0;
    $("#YarnDyeColorSplit").jexcel('updateSettings',{
        table:function (instance, cell, col, row, val, id) {
            if(col == 0) {
                NoofFeedPerRepeat = 0; NoofFeedPerColor = 0; ColorPercent = 0; PlanFabWgt = 0; ReqYarnWgt = 0; CalcColor = 0;
            }
            if(col == 10) {
                PlanFabWgt = Number($(cell).html());
                //console.log(PlanFabWgt,'PlanFabWgt');
            }

            if(col == 11) {
                NoofFeedPerRepeat = Number($(cell).html());
            }
            if(col == 12) {
                NoofFeedPerColor = Number($(cell).html());
            }
            if(col == 13) {
                var multiplyHundred = Number(NoofFeedPerColor) * 100;
                ColorPercent = multiplyHundred / NoofFeedPerRepeat;
                $(cell).text(ColorPercent.toFixed(3));
                CalcColor = Number(ColorPercent) / 100;
                ReqYarnWgt = PlanFabWgt * CalcColor;
            }
            if(col == 14) {
                //console.log(ReqYarnWgt,'ReqYarnWgt');
                $(cell).text(ReqYarnWgt.toFixed(3));
            }
        }
    });
}

function FnItemizedYarnProgram() {
    try {
        //var YarnPurchType = ["Cot. Greige", "Cot. Melange", "Cot. Coloured","Poly. Greige"];
        var ConsolidatedReqData = $("#ConsolidatedReqData").jexcel('getData');
        var PlanFabSubTotals = [], FirstEightGroup = [], ItemizedYarnProgramData = [], YDSubtotalandData = [];
        /*var DyeingTypeRow = $("#").jexcel('getColumnData',7);
        var FinDiaRow = $("#").jexcel('getColumnData',8);
        var PlanFabWgtSubTotalRow = $("#").jexcel('getColumnData',11);
        for(var i = 0; i < PlanFabWgtSubTotalRow.length; i++) {
            if(DyeingTypeRow[i] == "FD") {
                PlanFabWgtSubTotalRow[i]
            }
        }*/
        //var ConsolSubTotal = ""; var GroupSetForFD = []; var GroupFDPlanFabWgt = [];
        for (var i = 0; i < ConsolidatedReqData.length; i++) {
            /*
                            if(ConsolidatedReqData[i][7] === "FD") {
                                FDRowId++
                                if (ConsolidatedReqData[i][8] === "") {
                                    PlanFabSubTotals.push(ConsolidatedReqData[i][11]);
                                }

                            }
            */
            /*                ConsolSubTotal = ConsolidatedReqData[i][7]+"#"+ConsolidatedReqData[i][8]+"#"+ConsolidatedReqData[i][11];
                            console.log(ConsolSubTotal,'ConsolSubTotal');
                            var DyeingType = ConsolSubTotal.substring(0,ConsolSubTotal.indexOf('#'));
                            var FinDia = ConsolSubTotal.substring(ConsolSubTotal.indexOf('#'),ConsolSubTotal.lastIndexOf('#'));
                            var PlanFabWgt = ConsolSubTotal.substring(ConsolSubTotal.indexOf('##')+1);
                            var RealDyeingType = "";
                            if(DyeingType == "FD") {
                                RealDyeingType = DyeingType;
                            }
                            else if(DyeingType == "") {
                                FilterFDPlanFabWgt.push(RealDyeingType,PlanFabWgt);
                            }

                            console.log(FilterFDPlanFabWgt,'FilterFDPlanFabWgt');*/

            if(ConsolidatedReqData[i][7] != "" && ConsolidatedReqData[i][8] != "" && ConsolidatedReqData[i][9] != "") {
                FirstEightGroup.push(ConsolidatedReqData[i][0]+"#"+ConsolidatedReqData[i][1]+"#"+ConsolidatedReqData[i][2]+"#"+ConsolidatedReqData[i][3]+"#"+
                    ConsolidatedReqData[i][4]+"#"+ConsolidatedReqData[i][5]+"#"+ConsolidatedReqData[i][6]+"#"+ConsolidatedReqData[i][7]+"#"+ConsolidatedReqData[i][8]+"#"+ConsolidatedReqData[i][9]);
            }
            if(ConsolidatedReqData[i][10] == "") {
                PlanFabSubTotals.push(ConsolidatedReqData[i][13]);
            }
        }

        //console.log(FirstEightGroup,'FirstEightGroup'); console.log(PlanFabSubTotals,'PlanFabSubTotals');

        var YarnDyeingData = $("#YarnDyeColorSplit").jexcel('getData');
        var CountContentSDBSplit = $("#CountContentSDBSplit").jexcel('getData');
        for(var i = 0; i < YarnDyeingData.length; i++) {
            ItemizedYarnProgramData.push([YarnDyeingData[i][0],YarnDyeingData[i][1],YarnDyeingData[i][2],YarnDyeingData[i][3],YarnDyeingData[i][4],YarnDyeingData[i][5],YarnDyeingData[i][6],YarnDyeingData[i][7],YarnDyeingData[i][8],YarnDyeingData[i][9],"",YarnDyeingData[i][14]]);
        }

        for(var i = 0; i < CountContentSDBSplit.length; i++) {
            ItemizedYarnProgramData.push([CountContentSDBSplit[i][0],CountContentSDBSplit[i][1],CountContentSDBSplit[i][2],CountContentSDBSplit[i][3],CountContentSDBSplit[i][4],CountContentSDBSplit[i][5],CountContentSDBSplit[i][6],CountContentSDBSplit[i][7],CountContentSDBSplit[i][8],CountContentSDBSplit[i][9],"",CountContentSDBSplit[i][14]]);
        }

        for(var i = 0; i < FirstEightGroup.length; i++) {
            var SplittedFirstEight = FirstEightGroup[i].split("#");
            if(SplittedFirstEight[9] === "FD") {
                ItemizedYarnProgramData.push([SplittedFirstEight[0], SplittedFirstEight[1], SplittedFirstEight[2], SplittedFirstEight[3],
                    SplittedFirstEight[4], SplittedFirstEight[5], SplittedFirstEight[6], SplittedFirstEight[7], SplittedFirstEight[8],SplittedFirstEight[9],"", PlanFabSubTotals[i], "", "", ""]);
            }
        }
        /*
                    for(var i = 0; i < ItemizedYarnProgramData.length; i++) {
                        PlanFabWgtSubTotalsSum += Number(ItemizedYarnProgramData[i][9]);
                    }
                    console.log(PlanFabWgtSubTotalsSum,'PlanFabWgtSubTotalsSum');
                    console.log(typeof PlanFabWgtSubTotalsSum,'type PlanFabWgtSubTotalsSum');
                    ItemizedYarnProgramData.push(["", "", "", "", "","", "","", "",PlanFabWgtSubTotalsSum.toFixed(3), "","", ""]);
        */
        //ItemizedYarnProgramData.push(["", "", "", "", "","", "","", "",PlanFabWgtSubTotalsSum.toFixed(3), "","", ""]);
        //ItemizedYarnProgramData.push(["", "", "", "", "","", "","", "","", "","", ""]);
        //var lastrowno = ItemizedYarnProgramData.length;
        //var totalformula = "=SUM(J1:J"+lastrowno+")";
        //console.log(totalformula,'totalformula');
        //ItemizedYarnProgramData.push(["", "", "", "", "", "", "", "", "", "=SUM(J1:J12)", "", "", ""]);
        //console.log(ItemizedYarnProgramData,'ItemizedYarnProgramData');
        //console.log(GroupSetForFD,'GroupSetForFD'); console.log(GroupFDPlanFabWgt,'GroupFDPlanFabWgt');

        /*
                    for (var i = 0; i < ConsolidatedReqData.length; i++) {
                        if (ConsolidatedReqData[i][0] !== "") {
                            ExceptPlanFabSubTotals.push([ConsolidatedReqData[i][0], ConsolidatedReqData[i][1], ConsolidatedReqData[i][2], ConsolidatedReqData[i][3], ConsolidatedReqData[i][4],
                                ConsolidatedReqData[i][5], ConsolidatedReqData[i][6], ConsolidatedReqData[i][7]]);
                        }
                        if (ConsolidatedReqData[i][8] === "") {
                            PlanFabSubTotals.push(ConsolidatedReqData[i][11]);
                        }
                    }
        */

        /*for (var i = 0; i < ExceptPlanFabSubTotals.length; i++) {
            ItemizedYarnProgramData.push([ExceptPlanFabSubTotals[i][0], ExceptPlanFabSubTotals[i][1], ExceptPlanFabSubTotals[i][2], ExceptPlanFabSubTotals[i][3],
                ExceptPlanFabSubTotals[i][4], ExceptPlanFabSubTotals[i][5], ExceptPlanFabSubTotals[i][6], ExceptPlanFabSubTotals[i][7], "", PlanFabSubTotals[i], "", "", ""]);
        }*/

        $("#ItemizedYarnProgram").jexcel({
            colHeaders: ["Combo", "Component", "Color", "Garment Part", "Fabric Blend (%)","Fabric Content","Fabric Name", "Yarn Count", "Finishing GSM", "Dyeing Type", "Yarn Purchase Type", "Plan Fab. Wgt. Subtotal (Kgs.)", "Lycra (%)"
                , "Lycra Wgt. (Kgs.)", "Req. Yarn Wgt. (Kgs.)"],
            colWidths: [100, 100, 100, 80, 60, 100, 100, 60, 80, 90, 70, 70, 50, 70, 70],
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
                {type: "text", readOnly: true},
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

    }
    catch (e) {
        console.log(e,'try catch');
    }
}

function FnConsolidatedCountWiseYarnReq() {
    //try {
    var ItemizedYarnPgm = $("#ItemizedYarnProgram").jexcel('getData');
    ItemizedYarnPgm.pop();
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
    /*
                console.log(ArrRowNo,'ArrRowNo');
                console.log(typeof ArrRowNo,'typeof');
                console.log(YarnPurchaseTypePopulate,'YarnPurchaseTypePopulate');
                console.log(YarnColorPopulate,'YarnColorPopulate');
                console.log(ConsolForCountWiseYarnPlanFabWgt,'ConsolForCountWiseYarnPlanFabWgt');
    */
    var PlanFabWgtTotal = 0; var LycraWgtTotal = 0; var ReqYarnWgtTotal = 0;
    jQuery.each(ArrRowNo,function (index,rowsnos) {
//                console.log(rowsnos,'rowsnos');
        var ActualRow = rowsnos.substring(rowsnos.indexOf('-')+1);
        var YarnPurchType = index.substring(index.indexOf('#')+1);
        //var YarnSingleColor = index.substring(index.lastIndexOf('#')+1);
        var YarnCount = index.substring(0,index.indexOf('#'));
        //console.log(typeof YarnPurchType[1],'typeof YarnPurchType');
        //console.log(index,'index');
        //console.log(ConsolForCountWiseYarnPlanFabWgt,'ConsolForCountWiseYarnPlanFabWgt');
        var PlanFabWgt = ConsolForCountWiseYarnPlanFabWgt[index];
        //console.log(ConsolForCountWiseYarnLycraWgt,'ConsolForCountWiseYarnLycraWgt');
        var LycraWgt = ConsolForCountWiseYarnLycraWgt[index];
        //console.log(ConsolForCountWiseYarnReqYarnWgt,'ConsolForCountWiseYarnReqYarnWgt');
        var ReqYarnWgt = ConsolForCountWiseYarnReqYarnWgt[index];
        //console.log(PlanFabWgt,'PlanFabWgt');
        //console.log(LycraWgt,'LycraWgt');
        //console.log(ReqYarnWgt,'ReqYarnWgt');
        var PlanFabWgtConsol = fnSumSizeArrayValue(PlanFabWgt);
        var LycraWgtConsol = fnSumSizeArrayValue(LycraWgt);
        var ReqYarnWgtConsol = fnSumSizeArrayValue(ReqYarnWgt);
        //console.log(PlanFabWgtConsol,'PlanFabWgtConsol');
        if(YarnPurchType === "Cot. Greige") {
            var YarnColor = "Greige";
        }
        else {
            var YarnColor = YarnColorArr[YarnCount+"#"+YarnPurchType];
        }
        PlanFabWgtTotal += PlanFabWgtConsol;
        LycraWgtTotal += LycraWgtConsol;
        ReqYarnWgtTotal += ReqYarnWgtConsol;
        CountWiseYarnReqQtyData.push([ActualRow,YarnCount,"","","",YarnPurchType,YarnColor,PlanFabWgtConsol.toFixed(3),LycraWgtConsol.toFixed(3),ReqYarnWgtConsol.toFixed(3)]);
    });
    CountWiseYarnReqQtyData.push(["","","","","","","Total",PlanFabWgtTotal.toFixed(3),LycraWgtTotal.toFixed(3),ReqYarnWgtTotal.toFixed(3)]);
    /*
            for(var i = 0; i < ArrRowNo.length; i++) {
                ActualRow,ArrRowNo[i]
            }
    */
    $("#CountWiseYarnReqQty").jexcel({
        colHeaders: ["Itemd. Yarn Prog. Consolidated S.No.", "Yarn Count", "Yarn Blend (%)", "Yarn Content", "Yarn Special Request If Any", "Yarn Purchase Type", "Yarn Color", "Plan Fab. Wgt. Consol. (Kgs.)", "Lycra Wgt. (Kgs.)", "Yarn Req. Wgt. (Kgs.)"],
        colWidths: [170, 90, 80, 130, 120, 100, 190, 120, 100, 100],
        columns: [
            {type: "text"},
            {type: "text"},
            {type: "dropdown", source: ["90 %","100 %"]},
            {type: "dropdown", source: ["Cotton","Polyester"]},
            {type: "dropdown", source: ["Yes","No"]},
            {type: "text"},
            {type: "text"},
            {type: "text"},
            {type: "text"},
            {type: "text"},
        ],
        data: CountWiseYarnReqQtyData
    });

    //}
    //catch (e) {
    //    alert(e);
    //}
}

function FnYarnRequirementDetailsFinalTbl() {
    try {
        var CountWiseYarnReqQty = $("#CountWiseYarnReqQty").jexcel('getData');
        var YarnRequirementDetailsFinalData = [], PlanFabWgtTotal = 0, LycraWgtTotal = 0, ReqYarnWgtTotal = 0;
        //CountWiseYarnReqQty.pop();
        //console.log(CountWiseYarnReqQty,'CountWiseYarnReqQty');
        for(var i = 0; i < CountWiseYarnReqQty.length; i++) {
            PlanFabWgtTotal += Number(CountWiseYarnReqQty[i][7]);
            LycraWgtTotal += Number(CountWiseYarnReqQty[i][8]);
            ReqYarnWgtTotal += Number(CountWiseYarnReqQty[i][9]);
            YarnRequirementDetailsFinalData.push(["",CountWiseYarnReqQty[i][1],CountWiseYarnReqQty[i][2],CountWiseYarnReqQty[i][3],CountWiseYarnReqQty[i][4],"",CountWiseYarnReqQty[i][5],CountWiseYarnReqQty[i][6],"","",CountWiseYarnReqQty[i][7],CountWiseYarnReqQty[i][8],CountWiseYarnReqQty[i][9]]);
        }
        //YarnRequirementDetailsFinalData.push(["","","","","","","","","","Total",PlanFabWgtTotal.toFixed(3),LycraWgtTotal.toFixed(3),ReqYarnWgtTotal.toFixed(3)]);
        $("#YarnRequirementDetailsFinalTbl").jexcel({
            colHeaders: ["Yarn - Vendor / Brand Details","Yarn Count","Yarn Blend (%)","Yarn Content","yarn Special Request If Any","Yarn Grade","Yarn Purchase Type","Yarn Color","Yarn Product Code (vendor)","Yarn Color Code (vendor)","Plan Fab. Wgt. Consol. (Kgs.)","Lycra Wgt. (kgs.)","Req. Yarn Wgt. (Kgs.)"],
            colWidths: [120,80,80,90,80,90,100,100,100,100,100,100,100],
            columns: [
                {type: "dropdown", source: ["KPR","Amarjothi","market Source"]},
                {type: "text"},
                {type: "text"},
                {type: "text"},
                {type: "text"},
                {type: "dropdown", source: ["Combed - Red Label","Combed"]},
                {type: "text"},
                {type: "text"},
                {type: "text"},
                {type: "text"},
                {type: "text"},
                {type: "text"},
                {type: "text"},
            ],
            data: YarnRequirementDetailsFinalData
        });
    }
    catch (e) {
        console.log(e,'catch exception');
    }
}

/*
    function FnSaveCCSub() {
        var CumulativeSixthATbl = $("#CumulativeSixthATbl").jexcel('getData');
        //console.log(CumulativeSixthATbl,'CumulativeSixthATbl');
        var Knitting = JSON.parse(localStorage.getItem('FabricKnitTblLs')); var KnitGarmentPartsCount = [];
        for(var i = 0; i < Knitting.length; i++) {
            var GroupOne = Knitting[i][0]+"#"+Knitting[i][1]+"#"+Knitting[i][2];
            KnitGarmentPartsCount = fnPopulateValueArray(KnitGarmentPartsCount,GroupOne,Knitting[i][3]+"#"+Knitting[i][4]+"#"+Knitting[i][5]);
        }
        for(var i = 0; i < CumulativeSixthATbl.length; i++) {
            //
            var UnSplittedColor = CumulativeSixthATbl[i][2];
            //console.log(UnSplittedColor,'UnSplittedColor');
            if(UnSplittedColor.indexOf(';') >= 0) {
                var ArrSecondLevelColor = UnSplittedColor.split(';');
                //console.log(ArrSecondLevelColor,'ArrSecondLevelColor');
                //console.log(ArrSecondLevelColor.length,'ArrSecondLevelColor length');

                for(var c = 0; c < ArrSecondLevelColor.length; c++) {
                    //var GarmentParts = [];
                    //var CurrentGroup = CumulativeSixthATbl[i][0] + "#" + CumulativeSixthATbl[i][1] + "#" + jsTrim(ArrSecondLevelColor[c]);
                    //GarmentParts    = fnGroupArrayValue(KnitGarmentPartsCount[CurrentGroup]);
                    //console.log(GarmentParts,'GarmentParts');
                    //for(var x = 0; x < GarmentParts.length; x++) {
                        var subdata = $("#subchild_"+i+"_"+c).jexcel('getData');
                        //console.log(i,'i',c,'c in Save first');
                        localStorage.setItem("subchild_"+i+"_"+c,JSON.stringify(subdata));
                    //}
                }
            }
            else {
                var CccGroup = CumulativeSixthATbl[i][0] + "#" + CumulativeSixthATbl[i][1] + "#" + CumulativeSixthATbl[i][2];
                var GarmentParts = [];
                GarmentParts    = fnGroupArrayValue(KnitGarmentPartsCount[CccGroup]);
                //console.log(GarmentParts,'GarmentParts');
                for(var x = 0; x < GarmentParts.length; x++) {
                    //console.log(i,'i',x,'x in Save else cond.)');
                    var subdata = $("#subchild_"+i+"_"+x).jexcel('getData');
                    localStorage.setItem("subchild_"+i+"_"+x,JSON.stringify(subdata));
                }
            }
            //
            //var CccGroup        = CumulativeSixthATbl[i][0]+"#"+CumulativeSixthATbl[i][1]+"#"+CumulativeSixthATbl[i][2];



        }
    }
*/

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

var GarmentPartsOnload = [];
$(function() {
    if(typeof Storage !== "undefined") {
        if (localStorage.getItem('ConsolidatedReqDataLs')) {
            //console.log(GlbYarnCount,'in ready');
                //var GlbYarnCount = ["20s", "30s", "20s/30s"];
            var GlbDyeingType = ["FD", "YD", "SDB"];
            var UnitsArrJson = [{"id": "1", "name": "Nos."}, {"id": "2", "name": "%"}, {
                "id": "3",
                "name": "Gms."
            }, {"id": "4", "name": "Kgs."}, {"id": "5", "name": "Inches."}, {"id": "6", "name": "Cms."}];

            var CumulativeSixthATbl = JSON.parse(localStorage.getItem('SixthATblCumulative'));
            console.log(CumulativeSixthATbl, 'CumulativeSixthATbl');
            //Feting and placing Saved data from local storage to GRID
            var ColHeaders = "";
            var SizeChart = localStorage.getItem('SelSizeChartTextLs');
            var finalres = 0;
            var ArrSizeChartHeader = SizeChart.split(",");
            var ArrColHeaderFinal = ['Combo', 'Component', 'Colour', 'Size Spec<br/>Code / Fit', 'P.O. No. /<br/>Enq. Ref. No.'];
            for (var i = 0; i < 8; i++) {
                if (typeof (ArrSizeChartHeader[i]) != "undefined") {
                    ColHeaders = ColHeaders + ArrSizeChartHeader[i] + ",";
                    ArrColHeaderFinal.push(ArrSizeChartHeader[i]);
                } else {
                    ColHeaders = ColHeaders + ",";
                    ArrColHeaderFinal.push('No Size');
                }
            }
            ArrColHeaderFinal.push('Itemized P.O. Qty. / Sample Qty.');
            ArrColHeaderFinal.push('Pcs. / Set');
            ArrColHeaderFinal.push('Intake Qty.<br/>(Nos.)');
            ArrColHeaderFinal.push('Itemized Qty.<br/>(Pcs.)');
            var Knitting = JSON.parse(localStorage.getItem('FabricKnitTblLs'));
            var KnitGarmentParts = [];
            var Fabrics = [];
            for (var i = 0; i < Knitting.length; i++) {
                var GroupOne = Knitting[i][0] + "#" + Knitting[i][1] + "#" + Knitting[i][2];
                KnitGarmentParts = fnPopulateValueArray(KnitGarmentParts, GroupOne, Knitting[i][3]+"#"+Knitting[i][4]+"#"+Knitting[i][5]+"#"+Knitting[i][69]+"#"+Knitting[i][7]);
            }
            console.log(KnitGarmentParts, 'KnitGarmentParts');
            for (var MainTbl = 0; MainTbl < CumulativeSixthATbl.length; MainTbl++) {
                var TableSno = 'Table No: ';
                TableSno += Number(MainTbl) + 1;
                $("#FabricProgramCalc").append('<br/><br/><div class="mainheading box box-primary"><h4>' + TableSno + '</h4> </div> <div id="fabricprogrammain_' + MainTbl + '"></div>');
                $("#fabricprogrammain_" + MainTbl).jexcel({
                    colHeaders: ArrColHeaderFinal,
                    colWidths: [100, 100, 100, 100, 130, 45, 45, 45, 45, 45, 45, 45, 45, 100, 70, 70, 100],
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
                    data: [CumulativeSixthATbl[MainTbl]]
                });

                var UnSplittedColor = CumulativeSixthATbl[MainTbl][2];
                console.log(UnSplittedColor, 'UnSplittedColor');
                var SplitGarmentParts = [];
                if (UnSplittedColor.indexOf(';') >= 0) {
                    var ArrSecondLevelColor = UnSplittedColor.split(';');
                    for (var i = 0; i < ArrSecondLevelColor.length; i++) {
                        var CurrentGroup = CumulativeSixthATbl[MainTbl][0] + "#" + CumulativeSixthATbl[MainTbl][1] + "#" + jsTrim(ArrSecondLevelColor[i]);
                        console.log(CurrentGroup, 'CurrentGroup');
                        SplitGarmentParts.push(fnGroupArrayValue(KnitGarmentParts[CurrentGroup]));
                    }
                    var rslt = [].concat.apply([], SplitGarmentParts);
                    if (rslt) {
                        GarmentPartsOnload = [];
                        for (var r = 0; r < rslt.length; r++) GarmentPartsOnload.push(rslt[r]);
                    }
                }
                else {
                    var CurrentGroup = CumulativeSixthATbl[MainTbl][0] + "#" + CumulativeSixthATbl[MainTbl][1] + "#" + CumulativeSixthATbl[MainTbl][2];
                    GarmentPartsOnload = fnGroupArrayValue(KnitGarmentParts[CurrentGroup]);
                    //console.log(GarmentPartsOnload, 'GarmentPartsOnload GarmentPartsOnload');
                }
                //console.log(GarmentPartsOnload, 'GarmentParts before push');

                //console.log(GarmentPartsOnload, 'GarmentParts');
                var ColHeaders = "";
                var ArrReadOnlyInfo = new Array();
                var ArrHeader = ["Garment Part", "Fabric Blend (%)","Fabric Content","Fabric Name", "Finishing GSM", "Description", "Unit Of Measure"];
                for (var i = 0; i < 8; i++) {
                    if (typeof (ArrSizeChartHeader[i]) != "undefined") {
                        ColHeaders = ColHeaders + ArrSizeChartHeader[i] + ",";
                        ArrHeader.push(ArrSizeChartHeader[i]);
                        ArrReadOnlyInfo[i] = false;
                    } else {
                        ColHeaders = ColHeaders + ",";
                        ArrHeader.push('No Size');
                        ArrReadOnlyInfo[i] = true;
                    }
                }
                var UnitsArrJson = [{"id": "1", "name": "Nos."}, {"id": "2", "name": "%"}, {"id": "3","name": "Gms."}, {"id": "4", "name": "Kgs."}, {"id": "5", "name": "Inches."}, {"id": "6", "name": "Cms."}];
                ArrHeader.push('Total');
                //console.log(GarmentPartsOnload, 'GarmentPartsOnload');
                for (var i = 0; i < GarmentPartsOnload.length; i++) {
                    var GpEach = GarmentPartsOnload[i];
                    var Fabric = GpEach.substring(GpEach.indexOf('#') + 1, GpEach.lastIndexOf('#'));
                    var Gp = GpEach.substring(0, GpEach.indexOf('#'));
                    var FinGsm = GpEach.substring(GpEach.lastIndexOf('#') + 1);
                    $("#FabricProgramCalc").append('<div id="subchild_' + MainTbl + '_' + i + '" style="margin-top: 20px" class="SubTbl"></div>');
                    var setsubdata = JSON.parse(localStorage.getItem('subchild_' + MainTbl + '_' + i));
                    //console.log(setsubdata, 'setsubdata');
                    $("#subchild_" + MainTbl + "_" + i).jexcel({
                        data: setsubdata,
                        colHeaders: ArrHeader,
                        colWidths: [80, 50,70,70, 65, 170, 70, 70, 70, 70, 70, 70, 70, 70, 70, 90],
                        columns: [
                            {type: 'text', wordWrap: true, readOnly: true},
                            {type: 'text', wordWrap: true, readOnly: true},
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
                        onchange: function (instance, cell, value) {
                            var start = 5; var SizeColumnsStarts = 7;
                            for(var sizes = 0; sizes < ArrSizeChartHeader.length; sizes++) {
                                //console.log(CumulativeSixthATbl[MainTbl-1],'CumulativeSixthATbl[MainTbl][start]');
                                //console.log(MainTbl-1,start,'MainTbl','start');
                                //console.log(OSixthATblCumulative[CellId][start],'OSixthATblCumulative[CellId][start]');
                                fnCalculation(instance,SizeColumnsStarts, CumulativeSixthATbl[MainTbl-1][start],cell,value);
                                SizeColumnsStarts++;
                            }
                        }
                    });

                }
            }
            if (localStorage.getItem('ConsolidatedReqDataLs')) {
                var ConsolidatedReqData = JSON.parse(localStorage.getItem('ConsolidatedReqDataLs'));
            }
/*            if (localStorage.getItem('requirementsTblLs')) {
                var requirementsTblData = JSON.parse(localStorage.getItem('requirementsTblLs'));
            }
            $("#requirements").jexcel({
                colHeaders: ["Combo","Component","Color","Size Spec Code / Fit","Garment Part","Fabric Blend (%)","Fabric Content","Fabric Name","Fin. DIA / DIM (W * H)","Unit Of Measure","Req. Fab. Wgt. (Kgs.)"
                    ,"Plan. Fab. Wgt. (Kgs.)"],
                colWidths: [100,100,100,100,100,60,100,100,90,60,80,80],
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
                    {type: 'dropdown', source: UnitsArrJson, readOnly: true},
                    {type: 'text', wordWrap: true, readOnly: true},
                    {type: 'text', wordWrap: true, readOnly: true},
                ],
                data: requirementsTblData
            });*/

            //console.log(ConsolidatedReqData, 'ConsolidatedReqData');
            //console.log(ConsolidatedReqData.length,'ConsolidatedReqData');
            if (ConsolidatedReqData) {
                $("#ConsolidatedReqData").jexcel({
                    colHeaders: ["Combo", "Component", "Color", "Garment Part", "Fabric Blend (%)","Fabric Content","Fabric Name", "Yarn Count", "Fin. GSM", "Dyeing Type", "Fin. DIA / DIM (W * H)", "Unit Of Measure", "Req. Fab. Wgt. (Kgs.)"
                        , "Plan. Fab. Wgt. (Kgs.)"],
                    colWidths: [100, 100, 100, 80, 60, 100,100,60, 80, 90, 70, 70, 80, 80],
                    columns: [
                        {type: 'text', wordWrap: true, readOnly: true},
                        {type: 'text', wordWrap: true, readOnly: true},
                        {type: 'text', wordWrap: true, readOnly: true},
                        {type: 'text', wordWrap: true, readOnly: true},
                        {type: 'text', wordWrap: true, readOnly: true},
                        {type: 'text', wordWrap: true, readOnly: true},
                        {type: 'text', wordWrap: true, readOnly: true},
                        {type: 'dropdown', source: JSON.parse(GlbYarnCount), wordWrap: true},
                        {type: 'text', wordWrap: true, readOnly: true},
                        {type: 'dropdown', source: GlbDyeingType, wordWrap: true},
                        {type: 'text', wordWrap: true, readOnly: true},
                        {type: 'dropdown', source: UnitsArrJson, readOnly: true},
                        {type: 'text', wordWrap: true, readOnly: true},
                        {type: 'text', wordWrap: true, readOnly: true},
                    ],
                    data: ConsolidatedReqData
                });

            }

        }
    }


    //Feting and placing Saved data from local storage to GRID

    /*var Knitting = JSON.parse(localStorage.getItem('FabricKnitTblLs')); var KnitGarmentPartsCount = [];
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
    ArrHeader.push('Total');
    for(var i = 0; i < CumulativeSixthATbl.length; i++) {
        $("#FabricProgramCalc").append('<br/><br/><div id="fabricprogrammain_'+i+'"></div>');
        var UnsplitColor = CumulativeSixthATbl[i][2]; var SplitGarmentParts = []; var ArrSecondLevelColor = [];
        if(UnsplitColor.indexOf(';') >= 0) {
            ArrSecondLevelColor = UnsplitColor.split(';');
            for(var s = 0; s < ArrSecondLevelColor.length; s++) {
                var CurrentGroup        = CumulativeSixthATbl[i][0]+"#"+CumulativeSixthATbl[i][1]+"#"+jsTrim(ArrSecondLevelColor[s]);
                SplitGarmentParts.push(fnGroupArrayValue(KnitGarmentPartsCount[CurrentGroup]));
            }
            var rslt = [].concat.apply([],SplitGarmentParts);
        }
        else {
            var CurrentGroup        = CumulativeSixthATbl[i][0]+"#"+CumulativeSixthATbl[i][1]+"#"+CumulativeSixthATbl[i][2];
            GarmentPartsOnload    = fnGroupArrayValue(KnitGarmentPartsCount[CurrentGroup]);
        }


        if(rslt) for(var r = 0; r < rslt.length; r++) GarmentPartsOnload.push(rslt[r]);
        console.log(GarmentPartsOnload,'GarmentPartsOnload in dom ready');
        $("#fabricprogrammain_"+i).jexcel({
            colHeaders: ArrColHeaderFinal,
            colWidths: [110, 110, 110, 90, 110, 45, 45, 45, 45, 45, 45, 45, 45, 100, 70, 70, 100],
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
            data: [CumulativeSixthATbl[i]]

        });
        for(var x = 0; x < GarmentPartsOnload.length; x++) {
            //console.log(localStorage.getItem('subchild_'+i+'_'+x),'ls');
            var setsubdata = localStorage.getItem('subchild_'+i+'_'+x);
            //console.log(JSON.parse(setsubdata));
            //
            $("#FabricProgramCalc").append('<div id="subchild_'+i+'_'+x+'" class="SubTbl"></div>');
            $("#subchild_"+i+"_"+x).jexcel({
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
        }
    }*/
    //var _lsTotal=0,_xLen,_x;for(_x in localStorage){ if(!localStorage.hasOwnProperty(_x)){continue;} _xLen= ((localStorage[_x].length + _x.length)* 2);_lsTotal+=_xLen; console.log(_x.substr(0,50)+" = "+ (_xLen/1024).toFixed(2)+" KB")};console.log("Total = " + (_lsTotal / 1024).toFixed(2) + " KB");
});
