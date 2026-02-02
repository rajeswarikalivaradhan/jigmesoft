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


var ArrCCColor = [];
var ArrCCSizeSpec = [];
$("#fabricCalcCCFull").jexcel({
    colHeaders: ["Combo", "Component", "Color", "Size Spec Code / Fit", "P.O. No."],
    colWidths: [260, 260, 260, 260, 260],
    columns: [
        {type: 'text', wordWrap : true},
        {type: 'text', wordWrap : true},
        {type: 'text', wordWrap : true},
        {type: 'text', wordWrap : true},
        {type: 'text', wordWrap : true}
    ],
    colAlignments: [ 'left', 'left', 'left', 'left', 'left' ],
    data : [
        ["Combo - 1","Top","Blue","Code - A","PO - 1234"],
        ["Combo - 1","Top","Blue","Code - A","PO - 1235"],
        ["Combo - 1","Top","Blue","Code - B","PO - 1236"],
        ["Combo - 2","Top","Olive","Code - A","PO - 1234"],
        ["Combo - 2","Top","Olive","Code - A","PO - 1235"],
        ["Combo - 2","Top","Olive","Code - B","PO - 1236"],
        ["Combo - 1","Bottom","Navy / Melange","Code - C","PO - 1234"],
        ["Combo - 1","Bottom","Navy / Melange","Code - C","PO - 1235"],
        ["Combo - 1","Bottom","Navy / Melange","Code - D","PO - 1236"],
        ["Combo - 2","Bottom","Brown / Beige - Brown","Code - C","PO - 1234"],
        ["Combo - 2","Bottom","Brown / Beige - Brown","Code - C","PO - 1235"],
        ["Combo - 2","Bottom","Brown / Beige - Brown","Code - D","PO - 1236"]

    ],
    onchange: function (instance,cell,value) {
        //var ccData = $("#fabricCalcCC").jexcel('getData');
        //var cellid = $(cell).attr('id'); var cellidarr = $(cell).attr('id').split('-'); var row = cellidarr[1]; var col = cellidarr[2];
    }
});

var testdata = []; testdata[0] = 'combo'; testdata[1] = 'Top'; var seconfdata = []; seconfdata[0] = 'sec'; seconfdata[1] = 'Bottom';

function fnListData() {
    MakePostRequest(base_path + GlbCompanyFdr + 'fabricprogram/newfabricjsondata', 'rfrom=1', 'json', fnListDataRes);
}

var consCalcTblCount = 0; var exceldata = [];
var consCalcTblPono = [];
var ponoEachTable = []; var ponovalue = []; var FinalData = [];
function fnListDataRes(data) {
    $.each(data.re.ArrVignesh,function (index,value) {
    exceldata = index.split('#');
        value.forEach(function (val,foreachindex) {
            console.log(foreachindex,'foreachindex');
            console.log(index,'index');
            if(foreachindex > 1) {
                console.log(foreachindex);
            }
            var second = [];
            exceldata.push(val);
            console.log(exceldata);
            second.push('','',val); console.log(second,'second');

            $("#fabricCalcCC").append('<div id="maincc_'+consCalcTblCount+'"></div>');
            $("#maincc_"+consCalcTblCount).jexcel({
                colHeaders: ["Combo", "Component", "Color", "Size Spec Code / Fit", "P.O. No.","Size","XS","S","M","L","XL","XXL","3XL","4XL","Total"],
                colWidths: [150, 150, 150, 150, 150,120,70,70,70,70,70,70,70,70,120],
                columns: [
                    {type: 'text',readOnly:true},
                    {type: 'text',readOnly:true},
                    {type: 'text',readOnly:true},
                    {type: 'text',readOnly:true},
                    {type: 'text'},
                    {type: 'text'},
                    {type: 'text',readOnly:true},
                    {type: 'text',readOnly:true},
                    {type: 'text',readOnly:true},
                    {type: 'text',readOnly:true},
                    {type: 'text',readOnly:true},
                    {type: 'text',readOnly:true},
                    {type: 'text',readOnly:true},
                    {type: 'text',readOnly:true},
                    {type: 'text',readOnly:true}
                ],
                data: [
                    //exceldata,['','','','',val]
                ]
            });
        });
        ponoEachTable.push($("#maincc_"+consCalcTblCount).jexcel('getRowData', 1));
        consCalcTblCount++;
    });
    console.log(ponoEachTable,'ponoEachTable');
    $.each(ponoEachTable,function (index,value) {

        if(index >= 1) {
            $("#maincc_"+index).hide();
        }
        else {
            $("#maincc_"+index).after('<div id="sumOfConsSizes_'+index+'" class="d-flex" style="width: 100%; display: flex; padding: 10px 0">' +
                '<div style="width: 730px; text-align: right" class="box_likeformcontrol"><b>SUM (Pcs.)</b></div> ' +
                '<div id="xs_'+index+'" class="box_likeformcontrol">400</div>' +
                '<div id="s_'+index+'" class="box_likeformcontrol">800</div>' +
                /*
                    '<div id="s_'+consCalcTblCount+'" class="box_likeformcontrol">1200</div>' +
                    '<div id="s_'+consCalcTblCount+'" class="box_likeformcontrol">1600</div>' +
                    '<div id="s_'+consCalcTblCount+'" class="box_likeformcontrol">1600</div>' +
                    '<div id="s_'+consCalcTblCount+'" class="box_likeformcontrol">1200</div>' +
                    '<div id="s_'+consCalcTblCount+'" class="box_likeformcontrol">800</div>' +
                    '<div id="s_'+consCalcTblCount+'" class="box_likeformcontrol">400</div>' +
                    '<div id="s_'+consCalcTblCount+'" class="box_likeformcontrol" style="width: 120px">8000</div>' +
                */
                '</div><div id="appendgp_'+index+'"></div>');

            for(var ind = 0; ind < GPartsParsedJson.length; ind++) {
                $("#appendgp_"+index).append('<div id="subGp_'+index+'_'+ind+'" class="clsGPartsTbl"></div>');
                $("#subGp_"+index+"_"+ind).jexcel({
                    colHeaders: ["Garment Part", "Fabric Details", "Finishing GSM", "Description", "Unit Of Measure", "XS", "S", "M", "L", "XL", "XXL", "3XL", "4XL", "Total"],
                    colWidths: [150, 300, 150, 150, 120, 70, 70, 70, 70, 70, 70, 70, 70, 120],
                    columns: [
                        {type: 'dropdown', source: GPartsParsedJson},
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
                        {type: 'text'},
                        {type: 'text'},
                        {type: 'text'},
                        {type: 'text'}
                    ],
                    data: [
                        ['', '100% / Cotton / Nil / Single Jersey', '', 'Parts Intake', 'Nos.', '', '', '', '', '', '', '', ''],
                        ['', '', '', 'Excess Qty.', '%'],
                        ['', '', '', 'Plan. Qty.', 'Nos.'],
                        ['', '', '', '<span class="text-danger">Piece Wgt.</span>', '<span class="text-danger">Gms.</span>'],
                        ['', '', '', 'Req. Fab. Wgt.', 'Kgs.'],
                        ['', '', '', 'Processing Loss', '%'],
                        ['', '', '', 'Plan. Fab. Wgt.', 'Kgs.'],
                        ['', '', '', 'Fin. DIA. / DIM. (W * H)', 'Inches']
                    ]
                });
            }

            $('.clsGPartsTbl').last().after('<button id="nxt_'+index+'" onclick="fnShowRemainingCC('+index+')">Next Table</button>');

            //console.log(index,'asas'); console.log(value,'val');
            consCalcTblPono.push(value);
//            $("#maincc_"+index).jexcel('setValue','E1',consCalcTblPono[index][index]);
  //z          $("#maincc_"+index).jexcel('deleteRow', 1);
            //$("#maincc_"+index).jexcel('setValue','E2',consCalcTblPono[index-1][index]);
            $("#maincc_"+index).jexcel('updateSettings',{
                table : function (instance, cell, col, row, val, id) {
                    if(col == 4) {
                        if(val != "") {
                            console.log(val,'val');

                        }
                    }
                }
            });

        }

    });
}

function fnShowRemainingCC(tableid) {
    var nextTableID = tableid + 1;
    $("#maincc_"+nextTableID).show();
    $("#maincc_"+nextTableID).after('<div id="sumOfConsSizes_'+nextTableID+'" class="d-flex" style="width: 100%; display: flex; padding: 10px 0">' +
        '<div style="width: 730px; text-align: right" class="box_likeformcontrol"><b>SUM (Pcs.)</b></div> ' +
        '<div id="xs_'+nextTableID+'" class="box_likeformcontrol">400</div>' +
        '<div id="s_'+nextTableID+'" class="box_likeformcontrol">800</div>' +
        /*
            '<div id="s_'+consCalcTblCount+'" class="box_likeformcontrol">1200</div>' +
            '<div id="s_'+consCalcTblCount+'" class="box_likeformcontrol">1600</div>' +
            '<div id="s_'+consCalcTblCount+'" class="box_likeformcontrol">1600</div>' +
            '<div id="s_'+consCalcTblCount+'" class="box_likeformcontrol">1200</div>' +
            '<div id="s_'+consCalcTblCount+'" class="box_likeformcontrol">800</div>' +
            '<div id="s_'+consCalcTblCount+'" class="box_likeformcontrol">400</div>' +
            '<div id="s_'+consCalcTblCount+'" class="box_likeformcontrol" style="width: 120px">8000</div>' +
        */
        '</div><div id="appendgp_'+nextTableID+'"></div>');

    for(var ind = 0; ind < GPartsParsedJson.length; ind++) {
        $("#appendgp_"+nextTableID).append('<div id="subGp_'+nextTableID+'_'+ind+'" class="clsGPartsTbl"></div>');
        $("#subGp_"+nextTableID+"_"+ind).jexcel({
            colHeaders: ["Garment Part", "Fabric Details", "Finishing GSM", "Description", "Unit Of Measure", "XS", "S", "M", "L", "XL", "XXL", "3XL", "4XL", "Total"],
            colWidths: [150, 300, 150, 150, 120, 70, 70, 70, 70, 70, 70, 70, 70, 120],
            columns: [
                {type: 'dropdown', source: GPartsParsedJson},
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
                {type: 'text'},
                {type: 'text'},
                {type: 'text'},
                {type: 'text'}
            ],
            data: [
                ['', '100% / Cotton / Nil / Single Jersey', '', 'Parts Intake', 'Nos.', '', '', '', '', '', '', '', ''],
                ['', '', '', 'Excess Qty.', '%'],
                ['', '', '', 'Plan. Qty.', 'Nos.'],
                ['', '', '', '<span class="text-danger">Piece Wgt.</span>', '<span class="text-danger">Gms.</span>'],
                ['', '', '', 'Req. Fab. Wgt.', 'Kgs.'],
                ['', '', '', 'Processing Loss', '%'],
                ['', '', '', 'Plan. Fab. Wgt.', 'Kgs.'],
                ['', '', '', 'Fin. DIA. / DIM. (W * H)', 'Inches']
            ]
        });
    }
    $('.clsGPartsTbl').last().after('<button id="nxt_'+nextTableID+'" onclick="fnShowRemainingCC('+nextTableID+')">Next Table</button>');
    //
    $("#nxt_"+tableid).hide();
}

    /*console.log(index);
    console.log(value,'val');
    $("#maincc_"+consCalcTblCountpono).jexcel('updateSettings',{
        table : function (instance, cell, col, row, val, id) {
            $(instance).jexcel('setValue','E1','Test')
        }
    });
    consCalcTblCountpono++;*/