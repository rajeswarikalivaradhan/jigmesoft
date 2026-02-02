var OEComboArr = '[{"id":"1","name":"Payjamas"},{"id":"2","name":"Shirt"}]';
var OECompNameArr = '[{"id":"1","name":"Top"},{"id":"2","name":"Bottom"}]';
var OEColorArr = '[{"id":"1","name":"Red"},{"id":"2","name":"White"},{"id":"3","name":"Black"}]';
var OESizeSpecJSON = '[{"id":"1","name":"CodeA"},{"id":"2","name":"CodeB"},{"id":"3","name":"CodeC"}]';
var OEPoJSON = '[{"id":"1","name":"PO123"},{"id":"2","name":"PO124"},{"id":"3","name":"PO125"},{"id":"4","name":"PO126"}]';
var ArrOrderEntryGp = '[{"id":"1","name":"Body"},{"id":"2","name":"Collor"},{"id":"3","name":"Cuff"}]';

var GlbSizeSpec = [];
var GlbComboArrParsedJson = JSON.parse(OEComboArr);
var compNameParsedJson = JSON.parse(OECompNameArr);
var colorArrParsedJson = JSON.parse(OEColorArr);
var sizespecParsedJson = JSON.parse(OESizeSpecJSON);
var GPartsParsedJson = JSON.parse(ArrOrderEntryGp);
var poParsedJson = JSON.parse(OEPoJSON);

$("#fabricCalc").jexcel({
    colHeaders: ["Combo","Component","Color","Size Spec Code / Fit","P.O. No.","Piece Weight"],
    colWidths: [260, 260, 260, 260,260,88],
    columns: [
        { type: 'dropdown', source : GlbComboArrParsedJson },
        { type: 'dropdown', source : compNameParsedJson },
        { type: 'dropdown', source : colorArrParsedJson },
        { type: 'dropdown', source: sizespecParsedJson },
        { type: 'dropdown', source: poParsedJson },
        //{ type: 'text', source: ["Collor","Body","Cuff"] },
        //{ type: 'text', source: ["78% / knit con / knit lycra / knit fab","24% / Woven con / w lycra / fabric"] },
        { type: 'checkbox' }
    ],
    onchange: function (instance, cell, value) {
        var FabricPgmData = $("#fabricCalc").jexcel('getData');
        var cellName = $(instance).jexcel('getColumnNameFromId', $(cell).prop('id'));
        var ArrCellId = $(cell).prop('id').split('-'); console.log(ArrCellId,'ArrCellId'); var row = ArrCellId[1]; var col = ArrCellId[0];
        var sizespecid = FabricPgmData[row][3];
        if(col == 3 && value != "") {
            //if(GlbSizeSpec)
            if(jQuery.inArray(sizespecid,GlbSizeSpec) === -1) {
                GlbSizeSpec.push(sizespecid);
            }
            else {
            }
        }
        if(FabricPgmData[row][3] != "" && FabricPgmData[row][0] != "" && FabricPgmData[row][1] != "" && FabricPgmData[row][2] != "" && FabricPgmData[row][4] != "") {
            var poid = FabricPgmData[row][4];
            var comboid = FabricPgmData[row][0];
            var compNameid = FabricPgmData[row][1];
            var colorid = FabricPgmData[row][2];
            var checkbox = FabricPgmData[row][5];
            if(checkbox == '1' && value == true) {

            }
            else {
                $("#main_"+row).remove();
                $("#subchild_"+row).remove();

            }
            if(cellName.indexOf('F') != 0 && checkbox != '0') {
                $("#fabricCalc").jexcel('setValue','5-'+row,'0');
            }
            if(cellName.indexOf('F') == '0' && checkbox == '1') {
                if($("#main_"+row).html()) {
                    //console.log('isthere');
                    //$("#main_"+row).jexcel('destroy');
                    //$("#subchild_"+row).jexcel('destroy');
                }
                else {
                    //console.log('not there');
                    var maindiv = document.createElement('div');
                    maindiv.setAttribute('id','main_'+row);
                    $("#testid").append(maindiv);
                    //console.log($("#testid").find('main_'+row),'find'); console.log($("#main_"+row).html(),'html');
                }
                if($("#subchild_"+row).html()) {
                }
                else {
                    $("#testid").append('<div id="subchild_'+row+'" class="mtb25"></div><div id="totalpw_'+row+'"><div id="xspieceweightsum_'+row+'"></div></div>');
                }
                //for(var i = 0; i <= FabricPgmData.length; i++) {

                //}
                $("#main_"+row).jexcel({
                    colHeaders: ["Combo", "Component", "Color", "Size Spec Code / Fit", "P.O. No. /<br>Enq. Ref. No."],
                    colWidths: [283, 283, 283, 283, 300],
                    columns: [
                        {type: 'text', wordWrap: true},
                        {type: 'text', wordWrap: true},
                        {type: 'text', wordWrap: true},
                        {type: 'text', wordWrap: true},
                        {type: 'text', wordWrap: true},
                    ],
                    data : [[GlbComboArrParsedJson[comboid-1].name,compNameParsedJson[compNameid-1].name,colorArrParsedJson[colorid-1].name,sizespecParsedJson[sizespecid-1].name,
                        poParsedJson[poid-1].name]]
                });

                for(var i = 1; i <= GPartsParsedJson.length; i++) {
                    $("#subchild_"+row).append('<div id="pwgps_'+row+'_'+i+'" class="removethead"></div><div>&nbsp;</div>');
                    $("#pwgps_"+row+"_"+i).jexcel({
                        colHeaders: ["Garment Parts","(%) Blend / Content / Fabric / Lycra Feeder Type - Danier","Description","Unit of Measure","XS","S","M","L","XL","XXL","3XL","4XL"],
                        colWidths: [100, 505, 150,130, 70,70,70,70,70,70,70,70],
                        columns: [
                            { type: 'dropdown', source: GPartsParsedJson },
                            {type: 'text', wordWrap: true},
                            {type: 'text', wordWrap: true},
                            { type: 'dropdown', source: [{"id":"1","name":"Inch."},{"id":"2","name":"Cms."},{"id":"3","name":"Nos."},{"id":"4","name":"Grams"}] },
                            {type: 'numeric', wordWrap: true},
                            {type: 'numeric', wordWrap: true},
                            {type: 'numeric', wordWrap: true},
                            {type: 'numeric', wordWrap: true},
                            {type: 'numeric', wordWrap: true},
                            {type: 'numeric', wordWrap: true},
                            {type: 'numeric', wordWrap: true},
                            {type: 'numeric', wordWrap: true}
                        ],
                        data : [
                            ['','','Required Fin. Dia.','1'],
                            ['','','Width','2'],
                            ['','','Length / Height'],
                            ['','','Intake Qty.'],
                            ['','','Fin. GSM'],
                            ['','','<span style="color: green">Parts Piece Weight</span>','4']
                        ],
                        onchange : function (instance, cell, value) {
                            var subdivid = $(cell).closest('div').attr('id'); console.log(subdivid,'subdivid'); //fnRemoveHead(subdivid);
                            console.log(subdivid.substr(subdivid.indexOf('_')+1,1),'rowid');
                            var tablerowid = subdivid.substr(subdivid.indexOf('_')+1,1);

                            var oneData = $("#"+subdivid).jexcel('getData');
                            var cellid = $(cell).attr('id');
                            $("#"+subdivid+" #0-1").html('');
                            $("#"+subdivid+" #0-2").html('');
                            $("#"+subdivid+" #0-3").html('');
                            $("#"+subdivid+" #0-4").html('');
                            $("#"+subdivid+" #0-5").html('');
                            var arr = cellid.split('-');
                            var col = Number(arr[0]);
                            var row = Number(arr[1]);
                            if(row == 0 && col == 4) {
                                if(value != "") {
                                    var tablecellid = "#"+subdivid+" #4-1"; fnConvertTo(value,tablecellid);
                                }
                            }
                            if(row == 0 && col == 5) {
                                if(value != "") {
                                    var tablecellid = "#"+subdivid+" #5-1"; fnConvertTo(value,tablecellid);
                                }
                            }
                            if(row == 0 && col == 6) {
                                if(value != "") {
                                    var tablecellid = "#"+subdivid+" #6-1"; fnConvertTo(value,tablecellid);
                                }
                            }
                            if(row == 0 && col == 7) {
                                if(value != "") {
                                    var tablecellid = "#"+subdivid+" #7-1"; fnConvertTo(value,tablecellid);
                                }
                            }

                            fnPpwCalc(subdivid,4,'4-5');

                            //var pwscellid = "#"+subdivid+" #5-5";
                            fnPpwCalc(subdivid,5,'5-5');

                            //var pwscellid = "#"+subdivid+" #6-5";
                            fnPpwCalc(subdivid,6,'6-5');

                            fnXssumofpw(tablerowid,'pwgps_');
                            //fnSetValues();
                        },
                    });
                }
            }
        }

    }
});


function fnConvertTo(value,tableidcellid) {
    var res = Number(value) * 2.54;
    //console.log(res,'res');
    //console.log(tableidcellid);
    $(tableidcellid).text(res.toFixed(2));
}

function fnPpwCalc(subdivid,id,cellid) {
    var ArrXSVal = []; var selector = "#"+subdivid+" #"+cellid; console.log(selector);
    var data = $("#"+subdivid).jexcel('getData');
    for(var j = 1; j < 5; j++) {
        var sizes = jsTrim(data[j][id]);
        ArrXSVal.push(Number(sizes));
    }
    var Multiply = ArrXSVal.reduce(function (a,b) {
        return a * b;
    });
    var tot = Number(Multiply) / 10000;
    $(selector).text(tot.toFixed(3));
}

function fnXssumofpw(rowid,pwtableid) {
    var ArrXsSum = [];
    $("#totalpw_"+rowid).html('<div style="float: left; width: 915px; color: green; font-weight: bold">SUM of Weight</div><div style="float: left; width: 70px" id="xspieceweightsum_'+rowid+'"></div>');
    for(var i = 1; i <= GPartsParsedJson.length; i++) {
        var tid = pwtableid+rowid+'_'+i; //console.log(tid,'tid');

        var subData = $("#"+tid).jexcel('getValue','E6');
        ArrXsSum.push(Number(subData));
    }
    if(ArrXsSum.length >= 1) {
        var xssum = ArrXsSum.reduce(function (a,b) {
            return a + b;
        });
        $("#xspieceweightsum_"+rowid).text(xssum.toFixed(3));
    }
    return false;
}

var consCalcTblCount = 0;
var OEccComboArr = ["Combo - 1","Combo - 2"];
var OEccCompNameArr = ["Top","Bottom"];
var OEccColorArr = ["Blue","Olive","Navy / Melange","Brown / Beige - Brown"];
var OEccSizeSpecJSON = ["Code - A","Code - B","Code - C","Code - D"];
var OEccPoJSON = ["PO - 1234","PO - 1235","PO - 1236"];
var GccPartsParsedJson = ["Body","Collor","Cuff"];

var GlbconsumptionHashed = []; var GlbPono = []; var GlbFilterPono = []; var fullCCData = []; var GlbFilterSizeSpec = []; var GlbFilterColor = {};
function consumptionData() {
    MakePostRequest(base_path + GlbCompanyFdr + 'fabricprogram/consumptionData', 'rfrom=1', 'json', consumptionDataRes);
}

function consumptionDataRes(data) {
    GlbFilterPono = data.ArrFilterPono;
    GlbFilterSizeSpec = data.ArrSizeSpec;
    GlbFilterColor = data.ArrColor;
}
var filterCCPono = function (instance, cell, c, r, source) {
    var filterhashed = $(instance).jexcel('getValue','0-'+r)+'#'+$(instance).jexcel('getValue','1-'+r)+'#'+$(instance).jexcel('getValue','2-'+r)+'#'+$(instance).jexcel('getValue','3-'+r);
    var ponoOnly = GlbFilterPono[filterhashed];
    var ArrSeperate = [];
    for(var prop in ponoOnly) {
        ArrSeperate = ArrSeperate.concat(ponoOnly[prop])
    }
    return ArrSeperate;
}

var filterCCSizeSpecName = function(instance, cell, c, r, source) {
    var uniqueSizeSpec = []; var FilterSizeSpec = []; var ArrSeparate = [];
    var ssFilter = $(instance).jexcel('getValue','0-'+r)+'#'+$(instance).jexcel('getValue','1-'+r)+'#'+$(instance).jexcel('getValue','2-'+r);
    if(consCalcTblCount >= 1) {
        var prevTblId = consCalcTblCount - 1;
        var preTblData = $("#fabConsumptionCalc_"+prevTblId).jexcel('getData');
        var prevRowSizeSpec = preTblData[0][0]+'#'+preTblData[0][1]+'#'+preTblData[0][2];
        console.log(prevRowSizeSpec,'press');
        var arrss = GlbFilterSizeSpec[ssFilter]; console.log(arrss,'arrss');
        for(var i = 0; i < arrss.length; i++) {
            ArrSeparate = ArrSeparate.concat(arrss[i]);
        }
        $.each(ArrSeparate, function(i, el){
            if(jQuery.inArray(el, uniqueSizeSpec) === -1) uniqueSizeSpec.push(el);
        });
        console.log(uniqueSizeSpec,'uniqueSizeSpec');
        uniqueSizeSpec.forEach(function (value) {
            console.log(value);
            var prevRowSizeSpecArr = preTblData[0][0]+'#'+preTblData[0][1]+'#'+preTblData[0][2];
            var preThird = preTblData[0][3];
            if(prevRowSizeSpecArr == prevRowSizeSpec) {
                if(preThird != value) {
                    FilterSizeSpec.push(value);
                }
            }
            //if(value != prevRowSizeSpec) FilterSizeSpec.push(value);
        });
        return FilterSizeSpec;
    }
    else {
        var arrss = GlbFilterSizeSpec[ssFilter];
        for(var i = 0; i < arrss.length; i++) {
            ArrSeparate = ArrSeparate.concat(arrss[i]);
        }
        $.each(ArrSeparate, function(i, el){
            if(jQuery.inArray(el, uniqueSizeSpec) === -1) uniqueSizeSpec.push(el);
        });
        return uniqueSizeSpec;
    }
}

var filterCCColor = function(instance, cell, c, r, source) {
    var uniqueColor = []; var ArrSeparate = []; var FilterColor = [];
    var cFilter = $(instance).jexcel('getValue','0-'+r)+'#'+$(instance).jexcel('getValue','1-'+r);
    if(consCalcTblCount >= 1) {
        var prevTblId = consCalcTblCount - 1;
        var preTblData = $("#fabConsumptionCalc_"+prevTblId).jexcel('getData');
        var prevDatas = preTblData[0][0]+'#'+preTblData[0][1];
        var cColors = GlbFilterColor[cFilter]; console.log(cColors,'cColors');
        for(var i = 0; i < cColors.length; i++) {
            ArrSeparate = ArrSeparate.concat(cColors[i]);
        }
        $.each(ArrSeparate, function(i, el){
            if(jQuery.inArray(el, uniqueColor) === -1) uniqueColor.push(el);
        });
        uniqueColor.forEach(function (value) {
            if(value != prevDatas) FilterColor.push(value);
        });
        return FilterColor;
    }
    else {
        var cColors = GlbFilterColor[cFilter]; console.log(cColors,'cColors');
        for(var i = 0; i < cColors.length; i++) {
            ArrSeparate = ArrSeparate.concat(cColors[i]);
        }
        return ArrSeparate;
    }
}

var filterCCCompName = function(instance, cell, c, r, source) {
    if(r == 1) {
        var cCompName = $(instance).jexcel('getValue','0-'+r)+'#'+$(instance).jexcel('getValue','1-'+r);
        var cPrevCompName = $(instance).jexcel('getValue','1-'+r);

        if(consCalcTblCount >= 1) {

        }
        else {
            console.log(OEccCompNameArr);
            var indexId = jQuery.inArray(cPrevCompName,OEccCompNameArr);
            console.log(cPrevCompName,'cPrevCompName');
            return cPrevCompName;

        }

    }
    else {
        var cCompName = $(instance).jexcel('getValue','1-'+r);
        console.log(cCompName);
        return cCompName;
    }
}

$("#fabConsumptionCalc_"+consCalcTblCount).jexcel({
    colHeaders: ["Combo", "Component", "Color", "Size Spec Code / Fit", "P.O. No. /<br>Enq. Ref. No.","Size","XS","X","M", "L", "XL", "XXL", "3XL", "4XL", "Total"],
    colWidths: [120, 120, 120, 120, 120, 100, 90, 90, 90, 90, 90, 90, 90, 90, 110],
    columns: [
        {type:'dropdown', source : OEccComboArr},
        {type:'dropdown', source : OEccCompNameArr },
        {type:'dropdown', source : OEccColorArr, filter: filterCCColor},
        {type:'dropdown', source : OEccSizeSpecJSON, filter : filterCCSizeSpecName},
        {type:'dropdown', source : OEccPoJSON, filter : filterCCPono },
        {type:'text'},
        {type:'numeric'},
        {type:'numeric'},
        {type:'numeric'},
        {type:'numeric'},
        {type:'numeric'},
        {type:'numeric'},
        {type:'numeric'},
        {type:'numeric'},
        {type:'numeric'}
    ],
    onchange : function (instance, cell, value) {
        fnConsUpdateSizes(consCalcTblCount);
    }
});
$("#fabConsumptionCalc_"+consCalcTblCount).after('<div id="sumOfConsSizes_'+consCalcTblCount+'" class="d-flex" style="width: 100%; display: flex; padding: 10px 0">' +
    '<div style="width: 730px; text-align: right" class="box_likeformcontrol"><b>SUM (Pcs.)</b></div> ' +
    '<div id="xs_'+consCalcTblCount+'" class="box_likeformcontrol">400</div>' +
    '<div id="s_'+consCalcTblCount+'" class="box_likeformcontrol">800</div>' +
/*
    '<div id="s_'+consCalcTblCount+'" class="box_likeformcontrol">1200</div>' +
    '<div id="s_'+consCalcTblCount+'" class="box_likeformcontrol">1600</div>' +
    '<div id="s_'+consCalcTblCount+'" class="box_likeformcontrol">1600</div>' +
    '<div id="s_'+consCalcTblCount+'" class="box_likeformcontrol">1200</div>' +
    '<div id="s_'+consCalcTblCount+'" class="box_likeformcontrol">800</div>' +
    '<div id="s_'+consCalcTblCount+'" class="box_likeformcontrol">400</div>' +
    '<div id="s_'+consCalcTblCount+'" class="box_likeformcontrol" style="width: 120px">8000</div>' +
*/
    '</div>');

$("#sumOfConsSizes_"+consCalcTblCount).after('<button onclick="consCalcButton(this)">Consumption Garment Parts</button><div id="consGarmentPartsTbl_'+consCalcTblCount+'"></div>')
function consCalcButton(thisobject) {

    for(var i = 0; i < GccPartsParsedJson.length; i++) {
        $("#consGarmentPartsTbl_"+consCalcTblCount).append('<div id="fabConsCalcSub_'+consCalcTblCount+'_'+i+'" class="clsGarmentPartsTbl"></div>');
        $("#fabConsCalcSub_"+consCalcTblCount+"_"+i).jexcel({
            allowInsertRow: false,
            colHeaders: ["Garment Part", "Fabric Details", "Finishing GSM", "Description", "Unit Of Measure", "XS", "S", "M", "L", "XL", "XXL", "3XL", "4XL", "Total"],
            colWidths: [80, 350, 80, 120, 70, 90, 90, 90, 90, 90, 90, 90, 90, 110],
            columns: [
                {type:'text'},
                {type: 'text'},
                {type: 'text'},
                {type: 'text'},
                {type: 'text'},
                {type: 'text'},
                {type: 'text'},
                {type: 'text'},
                {type: 'text'},
                {type: 'text'},
                {type: 'text'},
                {type: 'text'},
                {type: 'text'},
                {type: 'text'}
            ],
            data: [
                [GccPartsParsedJson[i], '100% / Cotton / Nil / Single Jersey', '', 'Parts Intake', 'Nos.', ],
                ['', '', '', 'Excess Qty.', '%'],
                ['', '', '', 'Plan. Qty.', 'Nos.',],
                ['', '', '', '<span class="text-danger">Piece Wgt.</span>', '<span class="text-danger">Gms.</span>'],
                ['', '', '', 'Req. Fab. Wgt.', 'Kgs.'],
                ['', '', '', 'Processing Loss', '%'],
                ['', '', '', 'Plan. Fab. Wgt.', 'Kgs.']
            ],
            onchange: function (instance, cell, value) {
                fnCalculation(instance,'5', 'xs_'+consCalcTblCount,cell,value);
            }
        });
        $("#fabConsCalcSub_"+consCalcTblCount+"_"+i).after('<div class="clsFinDiaDim" style="width: 100%; display: flex; padding: 10px 0">' +
            '<div class="box_likeformcontrol" style="width: 727px; text-align: right"><span>Fin. DIA. / DIM. (W * H)</span> <select class="d-flex"><option>Inch.</option><option>Cms.</option></select></div>' +
            '<input type="text" style="width: 90px" id="xsfDiaDim_'+consCalcTblCount+'_'+i+'" class="form-control">' +
            '<input type="text" style="width: 90px" id="sfinishingDiaDim_'+consCalcTblCount+'_'+i+'" class="form-control">' +
            '<input type="text" style="width: 90px" id="mfinishingDiaDim_'+consCalcTblCount+'_'+i+'" class="form-control">' +
            '<input type="text" style="width: 90px" id="finishingDiaDim_'+consCalcTblCount+'_'+i+'" class="form-control">' +
            '<input type="text" style="width: 90px" id="finishingDiaDim_'+consCalcTblCount+'_'+i+'" class="form-control">' +
            '<input type="text" style="width: 90px" id="finishingDiaDim_'+consCalcTblCount+'_'+i+'" class="form-control">' +
            '<input type="text" style="width: 90px" id="finishingDiaDim_'+consCalcTblCount+'_'+i+'" class="form-control">' +
            '<input type="text" style="width: 90px" id="finishingDiaDim_'+consCalcTblCount+'_'+i+'" class="form-control">' +
            '<input type="text" style="width: 110px" id="finishingDiaDimTotal_'+consCalcTblCount+'_'+i+'" class="form-control">' +
            '</div>' +
            '</div>');
    }
    $(thisobject).hide();
    $("#consGarmentPartsTbl_"+consCalcTblCount).after('<button onclick="nxtTbl(this)">Next Table</button>');
}


function nxtTbl(thisobject) {
    consCalcTblCount++;
    console.log(consCalcTblCount,'consCalcTblCount');
    $(".clsFinDiaDim").last().after('<br/><br/><div id="fabConsumptionCalc_'+consCalcTblCount+'"></div>');
    $("#fabConsumptionCalc_"+consCalcTblCount).jexcel({
        colHeaders: ["Combo", "Component", "Color", "Size Spec Code / Fit", "P.O. No. /<br>Enq. Ref. No.","Size","XS","X","M", "L", "XL", "XXL", "3XL", "4XL", "Total"],
        colWidths: [120, 120, 120, 120, 120, 100, 90, 90, 90, 90, 90, 90, 90, 90, 130],
        columns: [
            {type:'dropdown', source : OEccComboArr},
            {type:'dropdown', source : OEccCompNameArr},
            {type:'dropdown', source : OEccColorArr, filter: filterCCColor},
            {type:'dropdown', source : OEccSizeSpecJSON, filter : filterCCSizeSpecName},
            {type:'dropdown', source : OEccPoJSON, filter : filterCCPono},
            {type:'text'},
            {type:'nember',mask:'#.00'},
            {type:'numeric'},
            {type:'numeric'},
            {type:'numeric'},
            {type:'numeric'},
            {type:'numeric'},
            {type:'numeric'},
            {type:'numeric'},
            {type:'numeric'},
            {type:'numeric'},
            {type:'numeric'}
        ],
        onchange : function (instance, cell, value) {
            fnConsUpdateSizes(consCalcTblCount);
        }
    });
    $("#fabConsumptionCalc_"+consCalcTblCount).after('<div id="sumOfConsSizes_'+consCalcTblCount+'" class="d-flex" style="width: 100%; display: flex; padding: 10px 0">' +
        '<div style="width: 730px; text-align: right"><b>SUM (Pcs.)</b></div> ' +
        '<div id="xs_'+consCalcTblCount+'">400</div>' +
        '<div id="s_'+consCalcTblCount+'">800</div>' +
        '</div>');
    $("#sumOfConsSizes_"+consCalcTblCount).after('<button onclick="consCalcButton(this)">Consumption Garment Parts</button><div id="consGarmentPartsTbl_'+consCalcTblCount+'"></div>')
    $(thisobject).hide();
}

var PrevCol = ''; var PrevSizeSpec = '';
function fnConsUpdateSizes(tableid) {
    $("#fabConsumptionCalc_"+tableid).jexcel('updateSettings',{
        table: function (instance, cell, col, row, val, id) {
            if(row == 0 && col == 1) {
                PrevCol = val;
            }
            if(row == 1 && col == 1) {
                console.log(PrevCol,'PrevCol');
                console.log(val,'val');
                if(val != PrevCol)
                $(cell).html('');
            }
            if(row == 0 && col == 3) {
                PrevSizeSpec = val;
            }
            if(row == 1 && col == 3) {
                console.log(PrevSizeSpec,'PrevSizeSpec');
                console.log(val,'val');
                if(val != PrevSizeSpec)
                    $(cell).html('');
            }
/*            if(row == 0 && col == 3) {

                        var prevRowSizeSpecArr = [];
                        for(var i = tableid; i >= 0; i--) {
                            var preTblData = $("#fabConsumptionCalc_"+i).jexcel('getData');
                            prevRowSizeSpecArr.push(preTblData[0][0]+'#'+preTblData[0][1]+'#'+preTblData[0][2]);
                        }
                prevRowSizeSpecArr.forEach(function (ele) {
                    if(ele == )
                });


            }*/
            if (col == 5) {
                // Update cell value
                $(cell).html('Qty. Breakup');
            }
            if (col == 6) {

                // Update cell value
                $(cell).html('200');
            }
            if (col == 7) {
                $(cell).html('400');
            }

        }
    });
}
var planqty = 0; var reqfabricwgt = 0; var planfabricwgt = 0;
function fnCalculation(instance,colId, sizeid,cell,value) {
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
            planqty = Number($("#" + sizeid).html()) * Number($(currtableid+" #" + zerorow).html()) * Number($(currtableid+" #" + firstrow).html());
            console.log(planqty, 'planqty final',zerorow,'zerorow',$("#"+sizeid).html());
            planqty = planqty / 100;
            planqty = planqty + Number($("#" + sizeid).html());
            console.log(currtableid+ " #" + zerorow, 'qq');
            planqty * Number($(currtableid+" #" + zerorow).html());

            $(currtableid+" #" + secondrow).html(planqty);
        }
    }
    if ($(cell).prop('id') == thirdrow) {
        if (value != "") {
            console.log(planqty, 'planqty final');
            reqfabricwgt = planqty * Number($(currtableid+" #" + thirdrow).html());
            console.log(reqfabricwgt, 'reqfabricwgt');
            $(currtableid+" #" + fourthrow).html(reqfabricwgt.toFixed(3));
        }

    }
    if ($(cell).prop('id') == fifthrow) {
        if (value != "") {
            planfabricwgt = reqfabricwgt * Number($(currtableid+" #" + fifthrow).html());
            planfabricwgt = planfabricwgt / 100;
            planfabricwgt = planfabricwgt + Number($(currtableid+" #" + fourthrow).html());
            $(currtableid+" #" + sixthrow).html(planfabricwgt.toFixed(3));
        }
    }
    $(currtableid+" #13-2").html(Number($(currtableid+" #5-2").html()) + Number($(currtableid+" #6-2").html()) + Number($(currtableid+" #7-2").html())
        + Number($(currtableid+" #8-2").html()) + Number($(currtableid+ " #9-2").html()) + Number($(currtableid+ " #10-2").html()) +
        Number($(currtableid+" #11-2").html()) + Number($(currtableid+" #12-2").html()));

    $(currtableid+" #13-4").html(Number($(currtableid+" #5-4").html()) + Number($(currtableid+" #6-4").html()) + Number($(currtableid+" #7-4").html())
        + Number($(currtableid+" #8-4").html()) + Number($(currtableid+" #9-4").html()) + Number($(currtableid+" #10-4").html()) +
        Number($(currtableid+" #11-4").html()) + Number($(currtableid+" #12-4").html()));

    $(currtableid+" #13-6").html(Number($(currtableid+" #5-6").html()) + Number($(currtableid+" #6-6").html()) + Number($(currtableid+" #7-6").html())
        + Number($(currtableid+" #8-6").html()) + Number($(currtableid+" #9-6").html()) + Number($(currtableid+" #10-6").html()) +
        Number($(currtableid+" #11-6").html()) + Number($(currtableid+" #12-6").html()));

}

