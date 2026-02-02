var OEComboArr = '[{"id":"1","name":"Combo - 1"},{"id":"2","name":"Combo - 2"}]';
var OECompNameArr = '[{"id":"1","name":"Top"},{"id":"2","name":"Bottom"}]';
var OEColorArr = '[{"id":"1","name":"Blue"},{"id":"2","name":"Olive"},{"id":"3","name":"Navy / Melange"},{"id":"4","name":"Brown / Beige - Brown"}]';
var OESizeSpecJSON = '[{"id":"1","name":"Code - A"},{"id":"2","name":"Code - B"},{"id":"3","name":"Code - C"},{"id":"4","name":"Code - D"}]';
var OEPoJSON = '[{"id":"1","name":"PO1234"},{"id":"2","name":"PO1235"},{"id":"3","name":"PO1236"},{"id":"4","name":"PO1237"}]';
var ArrOrderEntryGp = '[{"id":"0","name":""},{"id":"1","name":"Body"},{"id":"2","name":"Collor"},{"id":"3","name":"Cuff"}]';
var OEFabPwDetails = ["","100% / Cotton / Nil / Single Jersey","95% - 05% / Cotton - Lycra / Alt -20D / Flat Back Rib","95% - 05% / Cotton - Lycra / Alt -20D / Flat Back Rib"];

/*var OEComboArr = '[{"id":"1","name":"Payjamas"},{"id":"2","name":"Shirt"}]';
var OECompNameArr = '[{"id":"1","name":"Top"},{"id":"2","name":"Bottom"}]';
var OEColorArr = '[{"id":"1","name":"Red"},{"id":"2","name":"White"},{"id":"3","name":"Black"}]';
var OESizeSpecJSON = '[{"id":"1","name":"CodeA"},{"id":"2","name":"CodeB"},{"id":"3","name":"CodeC"}]';
var OEPoJSON = '[{"id":"1","name":"PO123"},{"id":"2","name":"PO124"},{"id":"3","name":"PO125"},{"id":"4","name":"PO126"}]';
var ArrOrderEntryGp = '[{"id":"1","name":"Body"},{"id":"2","name":"Collor"},{"id":"3","name":"Cuff"}]';*/

var GlbSizeSpec = [];
var GlbComboArrParsedJson = JSON.parse(OEComboArr);
var compNameParsedJson = JSON.parse(OECompNameArr);
var colorArrParsedJson = JSON.parse(OEColorArr);
var sizespecParsedJson = JSON.parse(OESizeSpecJSON);
var poParsedJson = JSON.parse(OEPoJSON);
var GPartsParsedJson = JSON.parse(ArrOrderEntryGp);

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
                        {type: 'text', readOnly : true, wordWrap: true},
                        {type: 'text', readOnly : true, wordWrap: true},
                        {type: 'text', readOnly : true, wordWrap: true},
                        {type: 'text', readOnly : true, wordWrap: true},
                        {type: 'text', readOnly : true, wordWrap: true},
                    ],
                    data : [[GlbComboArrParsedJson[comboid-1].name,compNameParsedJson[compNameid-1].name,colorArrParsedJson[colorid-1].name,sizespecParsedJson[sizespecid-1].name,
                        poParsedJson[poid-1].name]]
                });

                for(var i = 1; i <= GPartsParsedJson.length; i++) {
                    $("#subchild_"+row).append('<div id="pwgps_'+row+'_'+i+'" class="removethead"></div><div>&nbsp;</div>');
                    $("#pwgps_"+row+"_"+i).jexcel({
                        colHeaders: ["Garment Parts","Fabric Details<br/>(%) Blend / Content / Fabric / Lycra Feeder Type - Danier / Fabric","Description","Unit of Measure","XS","S","M","L","XL","XXL","3XL","4XL"],
                        colWidths: [100, 505, 150,130, 70,70,70,70,70,70,70,70],
                        columns: [
                            {type: 'text', readOnly : true},
                            {type: 'text', readOnly : true,wordWrap: true},
                            {type: 'text', readOnly : true, wordWrap: true},
                            {type: 'dropdown', source: [{"id":"1","name":"Inch."},{"id":"2","name":"Cms."},{"id":"3","name":"Nos."},{"id":"4","name":"Grams"}] },
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
                            [GPartsParsedJson[i].name,OEFabPwDetails[i],'Required Fin. Dia.','1'],
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
    console.log(res,'res');
    console.log(tableidcellid);
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

var OEFinishingGsm = ["0.160","0.220","0.220"];
var OEFabDetails = ["100% / Cotton / Nil / Single Jersey","95% - 05% / Cotton - Lycra / Alt -20D / Flat Back Rib","95% - 05% / Cotton - Lycra / Alt -20D / Flat Back Rib"];
//var fullCCData = []; var GlbExcelArr = [];
function consumptionData() {
    MakePostRequest(base_path + GlbCompanyFdr + 'fabricprogram/fabricfinalData', 'rfrom=1', 'json', consumptionDataRes);
}

/*Ganesh
 *
 Filter Fabric Excel
*/
var ArrFullData = []; var GlbData = []; var GlbNextData = []; var resetCheckBox = [];
function consumptionDataRes(data) {
    ArrFullData = data.re;
    console.log(ArrFullData,'ArrFullData');
    $("#fulldataforsample").jexcel({
        colHeaders: ["Combo", "Component", "Color", "Size Spec Code / Fit", "P.O. No.", "CC"],
        colWidths: [260, 260, 260, 260, 260, 100],
        columns: [
            {type: 'text', readOnly: true, wordWrap: true},
            {type: 'text', readOnly: true, wordWrap: true},
            {type: 'text', readOnly: true, wordWrap: true},
            {type: 'text', readOnly: true, wordWrap: true},
            {type: 'text', readOnly: true, wordWrap: true},
            {type: 'checkbox'}
        ],
        data: ArrFullData,
        onchange: function (instance, cell, value) {
            console.log(typeof value,'checkboxvaluetype');
            if (value === true) {
                var cellId = $(cell).prop('id').split('-');
                var rowid = cellId[1];
                var data = $(instance).jexcel('getData');
                GlbData[rowid] = [data[rowid][0], data[rowid][1], data[rowid][2], data[rowid][3], data[rowid][4], 'Qty. Breakup','200','400','600','800','800','600','400','200','4000'];
                if(clickCalcTbl > 0) {
                    GlbNextData[rowid] = [data[rowid][0], data[rowid][1], data[rowid][2], data[rowid][3], data[rowid][4], 'Qty. Breakup','200','400','600','800','800','600','400','200','4000'];
                }
                resetCheckBox.push(rowid);
            }
            else {
                var cellId = $(cell).prop('id').split('-');
                var rowid = cellId[1];
                console.log(rowid, 'uncheck');
                GlbData.splice(rowid, 1);
                console.log(GlbData,'elseGlbData');
            }
        }
    });
    $("#fulldataforsample").after('<button class="btn btn-info pull-right" onclick="fnCalcTable()" id="cCalcbtn">Calc Table</button>');
}

var clickCalcTbl = 0;
function fnCalcTable() {
    var prevClickCalcTbl = clickCalcTbl - 1;
    if(clickCalcTbl > 0) {
        $("#gPartsTbl_"+prevClickCalcTbl).after('<br/><br/><br/><div id="nextTbl_'+clickCalcTbl+'"></div><div id="gPartsTbl_'+clickCalcTbl+'"></div>');
        var filtered = GlbNextData.filter(function (el) {
            return el != null;
        });

        $("#nextTbl_" + clickCalcTbl).jexcel({
            colHeaders: ["Combo", "Component", "Color", "Size Spec Code / Fit", "P.O. No. /<br>Enq. Ref. No.","Size","XS","X","M", "L", "XL", "XXL", "3XL", "4XL", "Total"],
            colWidths: [120, 120, 120, 120, 120, 100, 90, 90, 90, 90, 90, 90, 90, 90, 130],
            columns: [
                {type: 'text', readOnly: true, wordWrap: true},
                {type: 'text', readOnly: true, wordWrap: true},
                {type: 'text', readOnly: true, wordWrap: true},
                {type: 'text', readOnly: true, wordWrap: true},
                {type: 'text', readOnly: true, wordWrap: true},
                {type: 'text', readOnly: true, wordWrap: true},
                {type: 'numeric'},
                {type: 'numeric'},
                {type: 'numeric'},
                {type: 'numeric'},
                {type: 'numeric'},
                {type: 'numeric'},
                {type: 'numeric'},
                {type: 'numeric'},
                {type: 'numeric'}
            ],
            data: filtered
        });

        $("#nextTbl_"+clickCalcTbl).after('<div id="sumOfConsSizes_'+clickCalcTbl+'" class="d-flex" style="width: 100%; display: flex; padding: 10px 0">' +
            '<div style="width: 730px; text-align: right" class="box_likeformcontrol"><b>SUM (Pcs.)</b></div> ' +
            '<div id="xs_'+clickCalcTbl+'" class="box_likeformcontrol">400</div>' +
            '<div id="s_'+clickCalcTbl+'" class="box_likeformcontrol">800</div>' +
            '<div id="m_'+clickCalcTbl+'" class="box_likeformcontrol">1200</div>' +
            '<div id="l_'+clickCalcTbl+'" class="box_likeformcontrol">1600</div>' +
            '<div id="xl_'+clickCalcTbl+'" class="box_likeformcontrol">1600</div>' +
            '<div id="xxl_'+clickCalcTbl+'" class="box_likeformcontrol">1200</div>' +
            '<div id="3xl_'+clickCalcTbl+'" class="box_likeformcontrol">800</div>' +
            '<div id="4xl_'+clickCalcTbl+'" class="box_likeformcontrol">400</div>' +
            '<div id="totalsize'+clickCalcTbl+'" class="box_likeformcontrol" style="width: 120px">8000</div>' +
            '</div><div id="appendgp_'+clickCalcTbl+'"></div><div id="gPartsTbl_'+clickCalcTbl+'"></div>');

        for (var i = 0; i < GPartsParsedJson.length; i++) {
            $("#gPartsTbl_" + clickCalcTbl).append('<div id="consCalcGarPartsGrid_' + clickCalcTbl + '_' + i + '"></div>');
            $("#consCalcGarPartsGrid_" + clickCalcTbl + "_" + i).jexcel({
                colHeaders: ["Garment Parts", "Fabric Details<br/>(%) Blend / Content / Fabric / Lycra Feeder Type - Danier / Fabric", "Finishing GSM", "Description", "Unit Of Measure", "XS", "S", "M", "L", "XL", "XXL", "3XL", "4XL", "Total"],
                colWidths: [100, 350, 80, 120, 70, 90, 90, 90, 90, 90, 90, 90, 90, 110],
                columns: [
                    {type: 'text', readOnly: true},
                    {type: 'text', readOnly: true},
                    {type: 'text', readOnly: true},
                    {type: 'text', readOnly: true},
                    {type: 'text', readOnly: true},
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
                    [GPartsParsedJson[i].name, OEFabDetails[i], OEFinishingGsm[i], 'Parts Intake', 'Nos.'],
                    ['', '', '', 'Excess Qty.', '%'],
                    ['', '', '', 'Plan. Qty.', 'Nos.'],
                    ['', '', '', '<span class="text-danger">Piece Wgt.</span>', '<span class="text-danger">Gms.</span>'],
                    ['', '', '', 'Req. Fab. Wgt.', 'Kgs.'],
                    ['', '', '', 'Processing Loss', '%'],
                    ['', '', '', 'Plan. Fab. Wgt.', 'Kgs.']
                ],
                onchange: function (instance, cell, value) {
                    var calcTbl = clickCalcTbl - 1;
                    fnCalculation(instance,'5', 'xs_'+calcTbl,cell,value);
                    fnCalculation(instance,'6', 's_'+calcTbl, cell, value);
                    fnCalculation(instance,'7', 'm_'+calcTbl, cell, value);
                    fnCalculation(instance,'8', 'l_'+calcTbl, cell, value);
                    fnCalculation(instance,'9', 'xl_'+calcTbl, cell, value);
                    fnCalculation(instance,'10', 'xxl_'+calcTbl, cell, value);
                    fnCalculation(instance,'11', '3xl_'+calcTbl, cell, value);
                    fnCalculation(instance,'12', '4xl_'+calcTbl, cell, value);
                }
            });
            $("#consCalcGarPartsGrid_"+clickCalcTbl+"_"+i).after('<div class="clsFinDiaDim" style="width: 100%; display: flex; padding: 10px 0">' +
                '<div class="box_likeformcontrol" style="width: 727px; text-align: right"><span>Fin. DIA. / DIM. (W * H)</span> <select class="d-flex"><option>Inch.</option><option>Cms.</option></select></div>' +
                '<input type="text" style="width: 103px" id="xsfDiaDim_'+clickCalcTbl+'_'+i+'" class="form-control">' +
                '<input type="text" style="width: 103px" id="sfinishingDiaDim_'+clickCalcTbl+'_'+i+'" class="form-control">' +
                '<input type="text" style="width: 103px" id="mfinishingDiaDim_'+clickCalcTbl+'_'+i+'" class="form-control">' +
                '<input type="text" style="width: 103px" id="finishingDiaDim_'+clickCalcTbl+'_'+i+'" class="form-control">' +
                '<input type="text" style="width: 103px" id="finishingDiaDim_'+clickCalcTbl+'_'+i+'" class="form-control">' +
                '<input type="text" style="width: 103px" id="finishingDiaDim_'+clickCalcTbl+'_'+i+'" class="form-control">' +
                '<input type="text" style="width: 103px" id="finishingDiaDim_'+clickCalcTbl+'_'+i+'" class="form-control">' +
                '<input type="text" style="width: 103px" id="finishingDiaDim_'+clickCalcTbl+'_'+i+'" class="form-control">' +
                '</div>');

        }
        GlbData.forEach(function (val, ind) {
            $("#fulldataforsample #5-" + ind).find('input[type=checkbox]').css("display", "none");
        });
        GlbNextData = [];
    }
    else {
        var filtered = GlbData.filter(function (el) {
            return el != null;
        });
        $("#cCalcbtn").after('<br/><br/><br/><div id="consCalcGrid_' + clickCalcTbl + '"></div>');

        //$("#consCalcGrid_" + clickCalcTbl).after('<div id=""></div><div id="gPartsTbl_' + clickCalcTbl + '"></div>');

        $("#consCalcGrid_" + clickCalcTbl).jexcel({
            colHeaders: ["Combo", "Component", "Color", "Size Spec Code / Fit", "P.O. No. /<br>Enq. Ref. No.","Size","XS","X","M", "L", "XL", "XXL", "3XL", "4XL", "Total"],
            colWidths: [120, 120, 120, 120, 120, 100, 90, 90, 90, 90, 90, 90, 90, 90, 130],
            columns: [
                {type: 'text', readOnly: true, wordWrap: true},
                {type: 'text', readOnly: true, wordWrap: true},
                {type: 'text', readOnly: true, wordWrap: true},
                {type: 'text', readOnly: true, wordWrap: true},
                {type: 'text', readOnly: true, wordWrap: true},
                {type: 'text', readOnly: true, wordWrap: true},
                {type: 'numeric'},
                {type: 'numeric'},
                {type: 'numeric'},
                {type: 'numeric'},
                {type: 'numeric'},
                {type: 'numeric'},
                {type: 'numeric'},
                {type: 'numeric'},
                {type: 'numeric'}
            ],
            data: filtered
        });
        $("#consCalcGrid_"+clickCalcTbl).after('<div id="sumOfConsSizes_'+clickCalcTbl+'" class="d-flex" style="width: 100%; display: flex; padding: 10px 0">' +
            '<div style="width: 730px; text-align: right" class="box_likeformcontrol"><b>SUM (Pcs.)</b></div> ' +
            '<div id="xs_'+clickCalcTbl+'" class="box_likeformcontrol">400</div>' +
            '<div id="s_'+clickCalcTbl+'" class="box_likeformcontrol">800</div>' +
            '<div id="m_'+clickCalcTbl+'" class="box_likeformcontrol">1200</div>' +
            '<div id="l_'+clickCalcTbl+'" class="box_likeformcontrol">1600</div>' +
            '<div id="xl_'+clickCalcTbl+'" class="box_likeformcontrol">1600</div>' +
            '<div id="xxl_'+clickCalcTbl+'" class="box_likeformcontrol">1200</div>' +
            '<div id="3xl_'+clickCalcTbl+'" class="box_likeformcontrol">800</div>' +
            '<div id="4xl_'+clickCalcTbl+'" class="box_likeformcontrol">400</div>' +
            '<div id="totals_'+clickCalcTbl+'" class="box_likeformcontrol" style="width: 120px">8000</div>' +
            '</div><div id="appendgp_'+clickCalcTbl+'"></div><div id="gPartsTbl_'+clickCalcTbl+'"></div>');

        for (var i = 0; i < GPartsParsedJson.length; i++) {
            $("#gPartsTbl_" + clickCalcTbl).append('<div id="consCalcGarPartsGrid_' + clickCalcTbl + '_' + i + '"></div>');
            $("#consCalcGarPartsGrid_" + clickCalcTbl + "_" + i).jexcel({
                colHeaders: ["Garment Part", "Fabric Details<br/>(%) Blend / Content / Fabric / Lycra Feeder Type - Danier / Fabric", "Finishing GSM", "Description", "Unit Of Measure", "XS", "S", "M", "L", "XL", "XXL", "3XL", "4XL", "Total"],
                colWidths: [100, 350, 80, 120, 70, 90, 90, 90, 90, 90, 90, 90, 90, 110],
                columns: [
                    {type: 'text', readOnly: true},
                    {type: 'text', readOnly: true},
                    {type: 'text', readOnly: true},
                    {type: 'text', readOnly: true},
                    {type: 'text', readOnly: true},
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
                    [GPartsParsedJson[i].name, OEFabDetails[i], OEFinishingGsm[i], 'Parts Intake', 'Nos.'],
                    ['', '', '', 'Excess Qty.', '%'],
                    ['', '', '', 'Plan. Qty.', 'Nos.'],
                    ['', '', '', '<span class="text-danger">Piece Wgt.</span>', 'Gms.'],
                    ['', '', '', 'Req. Fab. Wgt.', 'Kgs.'],
                    ['', '', '', 'Processing Loss', '%'],
                    ['', '', '', '<span class="text-danger">Plan. Fab. Wgt.</span>', 'Kgs.'],
                ],
                onchange: function (instance, cell, value) {
                    var calcTbl = clickCalcTbl - 1;
                    fnCalculation(instance,'5', 'xs_'+calcTbl, cell, value);
                    fnCalculation(instance,'6', 's_'+calcTbl, cell, value);
                    fnCalculation(instance,'7', 'm_'+calcTbl, cell, value);
                    fnCalculation(instance,'8', 'l_'+calcTbl, cell, value);
                    fnCalculation(instance,'9', 'xl_'+calcTbl, cell, value);
                    fnCalculation(instance,'10', 'xxl_'+calcTbl, cell, value);
                    fnCalculation(instance,'11', '3xl_'+calcTbl, cell, value);
                    fnCalculation(instance,'12', '4xl_'+calcTbl, cell, value);
                }
            });
            $("#consCalcGarPartsGrid_"+clickCalcTbl+"_"+i).after('<div class="clsFinDiaDim" style="width: 100%; display: flex; padding: 10px 0">' +
                '<div class="box_likeformcontrol" style="width: 727px; text-align: right"><span>Fin. DIA. / DIM. (W * H)</span> <select class="d-flex"><option>Inch.</option><option>Cms.</option></select></div>' +
                '<input type="text" style="width: 103px" id="xsfDiaDim_'+clickCalcTbl+'_'+i+'" class="form-control">' +
                '<input type="text" style="width: 103px" id="sfinishingDiaDim_'+clickCalcTbl+'_'+i+'" class="form-control">' +
                '<input type="text" style="width: 103px" id="mfinishingDiaDim_'+clickCalcTbl+'_'+i+'" class="form-control">' +
                '<input type="text" style="width: 103px" id="finishingDiaDim_'+clickCalcTbl+'_'+i+'" class="form-control">' +
                '<input type="text" style="width: 103px" id="finishingDiaDim_'+clickCalcTbl+'_'+i+'" class="form-control">' +
                '<input type="text" style="width: 103px" id="finishingDiaDim_'+clickCalcTbl+'_'+i+'" class="form-control">' +
                '<input type="text" style="width: 103px" id="finishingDiaDim_'+clickCalcTbl+'_'+i+'" class="form-control">' +
                '<input type="text" style="width: 103px" id="finishingDiaDim_'+clickCalcTbl+'_'+i+'" class="form-control">' +
                '</div>');
        }

        GlbData.forEach(function (val, ind) {
            $("#fulldataforsample #5-" + ind).find('input[type=checkbox]').css("display", "none");
            /*var r = Number(ind)+ 1;
            var cellname = 'F'+r;
            $("#fulldataforsample").jexcel('setValue', cellname, 0);*/
        });
    }
    clickCalcTbl++;
}


function fnResetTbl(clickCalcId) {
    console.log(GlbData,'GlbData');
    GlbData.forEach(function (val,ind) {
        //$("#fulldataforsample #5-"+ind).find('input[type=checkbox]').css("display","block");
    });
    console.log(resetCheckBox,'resetCheckBox');
    $("#consCalcGrid_"+clickCalcId).jexcel('destroy');
    $("#consCalcGrid_"+clickCalcId).remove();
    $("#gPartsTbl_"+clickCalcId).remove();
    GlbData.forEach(function (val, ind) {
        $("#fulldataforsample #5-" + ind).find('input[type=checkbox]').attr('checked',false).css("display","block");
    });
    $("#resetBtn_"+clickCalcId).remove();
    resetCheckBox = [];
    GlbData = [];
}

var planqty = 0; var reqfabricwgt = 0; var planfabricwgt = 0;
function fnCalculation(instance,colId, sizeid,cell,value) {
    console.log(sizeid,'sizeid');
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
            console.log($("#" + sizeid).html(),'sizeid');
            console.log($("#" + sizeid).html() * $(currtableid+" #" + zerorow).html(),'test');
            var firstMul = $("#" + sizeid).html() * $(currtableid+" #" + zerorow).html();
            console.log(firstMul,'firstMul');
            var percent = $(currtableid+" #" + firstrow).html() / 100;
            console.log(percent,'percent');
            planqty = firstMul * percent + $("#" + sizeid).html() * $(currtableid+" #"+zerorow).html();
            console.log(planqty,'check planqty');
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

    var total = Number($(currtableid+" #5-6").html()) + Number($(currtableid+" #6-6").html()) + Number($(currtableid+" #7-6").html())
        + Number($(currtableid+" #8-6").html()) + Number($(currtableid+" #9-6").html()) + Number($(currtableid+" #10-6").html()) +
        Number($(currtableid+" #11-6").html()) + Number($(currtableid+" #12-6").html());
    $(currtableid+" #13-6").html(total.toFixed(3));

}