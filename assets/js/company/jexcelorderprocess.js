var GlbComboArr = [];
var GlbComponentArr = [];
var GlbColorArr = [];
var GlbCounter = 0;

var GlbPono = [];
var GlbTempCombo = '';
var inc = '1';
var from = ''; var to = '';
$(document).ready(function() {
    /*$("table").on("contextmenu",function(){
        return false;
    });*/
});


var GlbComboComponent={};

function fnPopulateValueArray(ArrName,KeyValue,InsertVal) {
    if(jQuery.inArray(KeyValue,ArrName)) {
        ArrName[KeyValue]=InsertVal+"-"+ArrName[KeyValue];
    }
    return ArrName;
}

function fnSumSizeArrayValue(ArrSizeVal) {
    if(typeof ArrSizeVal !== "undefined" && ArrSizeVal !== "") {
        var SumVal=0;
        var ArrName = ArrSizeVal.split("-");
        for(var i=0;i<ArrName.length;i++) {
            if(isNumber(ArrName[i])) {SumVal = parseInt(ArrName[i])+SumVal;}
        }
        return SumVal;

    }
    else { return 0; }
}

function fnCreateItemizedTable(IdVal,ReplaceBtn,ComboName,ComponentName,ColorCode) {
        var ArrGetTableLastVal = IdVal.split("-");
        console.log(ArrGetTableLastVal[1],'IdVal splited 1 ');
        if (ComboName !== "") {
            GlbComboArr[ArrGetTableLastVal[1]] = ComboName;
            GlbTempCombo = ComboName;
        } else {
            ComboName = GlbTempCombo;
            GlbComboArr[ArrGetTableLastVal[1]] = '';
        }
        GlbComponentArr[ArrGetTableLastVal[1]] = ComboName + '-' + ComponentName;
        GlbColorArr[ArrGetTableLastVal[1]] = ComboName + '-' + ComponentName + '-' + ColorCode;
        if (ArrGetTableLastVal[1] == 0) {
            $('#divComboColorTable').after("<div style='height:35px;background-color: #bffff9;'><strong>ITEMIZED P.O. WISE / SIZE WISE</strong></div>" + "<div id='Item-" + IdVal + "' class='pd0'></div><div id='itemizedTblEnd'></div>");
            if (ReplaceBtn != '') {
                $("#" + ReplaceBtn).html('');
            }
        }

        $("#Item-" + IdVal).jexcel({
            onchange: function (obj, itemcell, itemizedEntryData) {
/*                $('#Item-'+IdVal).jexcel('setValue', 'N2','=SUM(F2:M2)');*/
                var itemizedEntryTblId = $(itemcell).prop('id').split('-');
                var data = $('#Item-' + IdVal).jexcel('getData');
                var GlbItemXSSumVal = [];
                var GlbItemSSumVal = [];
                var GlbItemMSumVal = [];
                var GlbItemLSumVal = [];
                var GlbItemXLSumVal = [];
                var GlbItemXXLSumVal = [];
                var GlbItem3XLSumVal = [];
                var GlbItem4XLSumVal = [];
                var GlbItemPoQtySumVal = [];
                var ComboNewName = '';
                var datalength = data.length;
                for (var i = 0; i < datalength; i++) {
                    if (data[i][0] !== '') {
                        ComboNewName = data[i][0];
                    }
                    if (ComboNewName !== '') {
                        GlbItemXSSumVal = fnPopulateValueArray(GlbItemXSSumVal, ComboNewName, data[i][5]);
                        GlbItemSSumVal = fnPopulateValueArray(GlbItemSSumVal, ComboNewName, data[i][6]);
                        GlbItemMSumVal = fnPopulateValueArray(GlbItemMSumVal, ComboNewName, data[i][7]);
                        GlbItemLSumVal = fnPopulateValueArray(GlbItemLSumVal, ComboNewName, data[i][8]);
                        GlbItemXLSumVal = fnPopulateValueArray(GlbItemXLSumVal, ComboNewName, data[i][9]);
                        GlbItemXXLSumVal = fnPopulateValueArray(GlbItemXXLSumVal, ComboNewName, data[i][10]);
                        GlbItem3XLSumVal = fnPopulateValueArray(GlbItem3XLSumVal, ComboNewName, data[i][11]);
                        GlbItem4XLSumVal = fnPopulateValueArray(GlbItem4XLSumVal, ComboNewName, data[i][12]);
                        GlbItemPoQtySumVal = fnPopulateValueArray(GlbItemPoQtySumVal, ComboNewName, data[i][13]);
                    }
                }
                var lengthminusone = Number(datalength) - 1;
                var itemizedPn = data[lengthminusone][4];
                console.log(itemizedPn,'itemizedPn');
                var SumItemizedVal = '';
                for (var i = 0; i < GlbComboArr.length; i++) {
                    SumItemizedVal = SumItemizedVal + "<div class='cutratiocumdiv'>Cumulative Quantity Per Size (" + GlbComboArr[i] + "):</div>" +
                        "<div id='itemizedCummulative_"+IdVal+"'><div class='cutratiocumeachdiv'>" + fnSumSizeArrayValue(GlbItemXSSumVal[GlbComboArr[i]]) + "</div>" + "" +
                        "<div class='cutratiocumeachdiv'>" + fnSumSizeArrayValue(GlbItemSSumVal[GlbComboArr[i]]) + "</div>" +
                        "<div class='cutratiocumeachdiv'>" + fnSumSizeArrayValue(GlbItemMSumVal[GlbComboArr[i]]) + "</div>" + "" +
                        "<div class='cutratiocumeachdiv'>" + fnSumSizeArrayValue(GlbItemLSumVal[GlbComboArr[i]]) + "</div>" +
                        "<div class='cutratiocumeachdiv'>" + fnSumSizeArrayValue(GlbItemXLSumVal[GlbComboArr[i]]) + "</div>" + "" +
                        "<div class='cutratiocumeachdiv'>" + fnSumSizeArrayValue(GlbItemXXLSumVal[GlbComboArr[i]]) + "</div>" +
                        "<div class='cutratiocumeachdiv'>" + fnSumSizeArrayValue(GlbItem3XLSumVal[GlbComboArr[i]]) + "</div>" + "" +
                        "<div class='cutratiocumeachdiv'>" + fnSumSizeArrayValue(GlbItem4XLSumVal[GlbComboArr[i]]) + "</div></div>" +
                        "<div id='itemized_poqtyTotal_"+IdVal+"' class='cutratiocumpodiv'>" + fnSumSizeArrayValue(GlbItemPoQtySumVal[GlbComboArr[i]]) + "</div>" + "<div class='clearfix' class='pd0'></div>";
                }
                if ($("#ItemizedBkUp")) {
                    $("#ItemizedBkUp").html('');
                }
                $("#Item-" + IdVal).after('<div id="ItemizedBkUp">' + SumItemizedVal + '</div>');

                var itemizedPoQtyTotal = 0;
                $("#itemizedCummulative_"+IdVal+" div").each(function () {
                    itemizedPoQtyTotal += parseInt($(this).html());

                });
                $("#itemized_poqtyTotal_"+IdVal).text(itemizedPoQtyTotal);
                GlbPono[itemizedEntryTblId[1]] = itemizedPn;
                deliveryTbl(itemizedPn,itemizedEntryTblId,ComboNewName,datalength);
                console.log(data,'ponodata');
            },
            data:[
                ['','','','','','','','','','','','','','']
            ],
            colHeaders: ['Combo Name', 'Component Name', 'Colour', 'Size Spec Code/Fit', 'P.O.No.', 'XS', 'S', 'M', 'L', 'XL', 'XXL', '3XL', '4XL', 'P.O.Qty'],
            colWidths: [180, 180, 180, 90, 100, 40, 40, 40, 40, 40, 40, 40, 40, 90, 90],
            columns: [
                {type: 'dropdown', source: GlbComboArr},
                {type: 'dropdown', source: GlbComponentArr},
                {type: 'dropdown', source: GlbColorArr},
                {type: 'text', wordWrap: true},
                {type: 'text', wordWrap: true}
            ]
        });
}

function deliveryTbl(pono,cellid,comboname,rows) {
/*    var data = $("#deliverytable").jexcel('getData');
    console.log(rows);
    console.log(data.length);
    var deliverydata = ['', '', pono, comboname, '', '', '', '', '', '', '', ''];
    if(rows > data.length) {
        $("#deliverytable").jexcel('insertRow',deliverydata);
    }
    var label = parseInt(cellid[1]) + 1;
    if(pono !== "") {
        console.log(label,'pono label');
        $("#deliverytable").jexcel('setValue', 'C' + label,pono);
    }
    if(comboname !== "") {
        $("#deliverytable").jexcel('setValue', 'D'+label, comboname);
    }

    console.log(cellid,comboname,'cell id');

    console.log(GlbPono,'GlbPono');*/
}

function fnCreateCuttingRatioTable(IdVal,ReplaceBtn,ComboName,ComponentName,ColorCode) {

        var ArrGetTableLastVal = IdVal.split("-");
        var NewIdVal = ArrGetTableLastVal[0]+"-"+ArrGetTableLastVal[1]+"-"+ArrGetTableLastVal[2];
        if(ArrGetTableLastVal[1] == 0) {
            $('#divPOSWQTYBKP').before("<div style='height:35px;background-color: #bffff9;'><strong>P.O. WISE / SIZE WISE - CUTTING RATIO</strong></div>" +
                "<div id='Cutting-"+IdVal+"' class='pd0'><div class='clearfix'></div><div id='cuttingRatioEnd'></div></div>");

            $("#Cutting-"+IdVal).jexcel({
                colHeaders: [ 'Combo Name', 'Component Name', 'Colour', 'Size Spec Code/Fit', 'P.O.No.','XS','S','M','L','XL','XXL','3XL','4XL','P.O.Qty' ],
                colWidths: [180, 180, 180, 90, 100, 40, 40, 40, 40, 40, 40, 40, 40, 90, 90],
                columns: [
                    { type: 'dropdown', source:GlbComboArr },
                    { type: 'dropdown', source:GlbComponentArr },
                    { type: 'dropdown', source:GlbColorArr },
                    { type: 'text', wordWrap:true},
                    { type: 'dropdown', source:GlbPono },
                    { type: 'text', wordWrap:true},
                    { type: 'text', wordWrap:true},
                    { type: 'text', wordWrap:true},
                    { type: 'text', wordWrap:true},
                    { type: 'text', wordWrap:true},
                    { type: 'text', wordWrap:true},
                    { type: 'text', wordWrap:true},
                    { type: 'text', wordWrap:true},
                    { type: 'text', wordWrap:true},
                ],
                onchange: function (obj, cell, itemizedEntryData) {
                    var itemizedEntryTblId = $(cell).prop('id').split('-');
                    var data = $('#Cutting-' + IdVal).jexcel('getData');
                    var GlbItemXSSumVal = [];
                    var GlbItemSSumVal = [];
                    var GlbItemMSumVal = [];
                    var GlbItemLSumVal = [];
                    var GlbItemXLSumVal = [];
                    var GlbItemXXLSumVal = [];
                    var GlbItem3XLSumVal = [];
                    var GlbItem4XLSumVal = [];
                    var GlbItemPoQtySumVal = [];
                    var ComboNewName = '';
                    for (var i = 0; i < data.length; i++) {
                        if (data[i][0] !== '') {
                            ComboNewName = data[i][0];
                        }
                        if (ComboNewName !== '') {
                            GlbItemXSSumVal = fnPopulateValueArray(GlbItemXSSumVal, ComboNewName, data[i][5]);
                            GlbItemSSumVal = fnPopulateValueArray(GlbItemSSumVal, ComboNewName, data[i][6]);
                            GlbItemMSumVal = fnPopulateValueArray(GlbItemMSumVal, ComboNewName, data[i][7]);
                            GlbItemLSumVal = fnPopulateValueArray(GlbItemLSumVal, ComboNewName, data[i][8]);
                            GlbItemXLSumVal = fnPopulateValueArray(GlbItemXLSumVal, ComboNewName, data[i][9]);
                            GlbItemXXLSumVal = fnPopulateValueArray(GlbItemXXLSumVal, ComboNewName, data[i][10]);
                            GlbItem3XLSumVal = fnPopulateValueArray(GlbItem3XLSumVal, ComboNewName, data[i][11]);
                            GlbItem4XLSumVal = fnPopulateValueArray(GlbItem4XLSumVal, ComboNewName, data[i][12]);
                            GlbItemPoQtySumVal = fnPopulateValueArray(GlbItemPoQtySumVal, ComboNewName, data[i][13]);
                        }
                    }
                    var SumItemizedVal = '';
                    for (var i = 0; i < GlbComboArr.length; i++) {
                        SumItemizedVal = SumItemizedVal +
                            "<div class='cutratiocumdiv'>Cumulative Quantity Per Size (" + GlbComboArr[i] + "):</div>" +
                            "<div class='cutratiocumeachdiv'>" + fnSumSizeArrayValue(GlbItemXSSumVal[GlbComboArr[i]]) + "</div>" + "" +
                            "<div class='cutratiocumeachdiv'>" + fnSumSizeArrayValue(GlbItemSSumVal[GlbComboArr[i]]) + "</div>" +
                            "<div class='cutratiocumeachdiv'>" + fnSumSizeArrayValue(GlbItemMSumVal[GlbComboArr[i]]) + "</div>" + "" +
                            "<div class='cutratiocumeachdiv'>" + fnSumSizeArrayValue(GlbItemLSumVal[GlbComboArr[i]]) + "</div>" +
                            "<div class='cutratiocumeachdiv'>" + fnSumSizeArrayValue(GlbItemXLSumVal[GlbComboArr[i]]) + "</div>" + "" +
                            "<div class='cutratiocumeachdiv'>" + fnSumSizeArrayValue(GlbItemXXLSumVal[GlbComboArr[i]]) + "</div>" +
                            "<div class='cutratiocumeachdiv'>" + fnSumSizeArrayValue(GlbItem3XLSumVal[GlbComboArr[i]]) + "</div>" + "" +
                            "<div class='cutratiocumeachdiv'>" + fnSumSizeArrayValue(GlbItem4XLSumVal[GlbComboArr[i]]) + "</div>" +
                            "<div class='cutratiocumpodiv'>" + fnSumSizeArrayValue(GlbItemPoQtySumVal[GlbComboArr[i]]) + "</div>" + "" +
                            "<div class='clearfix' class='pd0'></div>";
                    }
                    if ($("#cuttingRatio")) {
                        $("#cuttingRatio").html('');
                    }
                    $("#Cutting-" + IdVal).after('<div id="cuttingRatio">' + SumItemizedVal + '</div>');

/*
                    var ponodata = $('#Cutting-' + IdVal).jexcel('getData');
                    var deliverydata = ['', '', itemizedEntryData, itemizedEntryData, '', '', '', '', '', '', '', ''];
                    if (itemizedEntryTblId[1] == 0) {
                        $("#deliverytable").jexcel({
                            data: [
                                ['', '', itemizedEntryData, itemizedEntryData],
                            ],
                            colHeaders: ['P.O Date', 'P.O Rec. Date', 'P.O No.', 'Combo Name', 'P.O Qty.', 'Mode of Shipment', 'Shipment Date', 'Ex-Factory Date', 'Departure Date', 'Loading Port', 'Destination Port', 'Country'],
                            colWidths: [90, 100, 100, 110, 90, 120, 110, 120, 110, 90, 110, 90],
                            columns: [
                                {type: 'calendar', options: {format: 'DD/MM/YYYY'}},
                                {type: 'calendar', options: {format: 'DD/MM/YYYY'}},
                                {type: 'text', wordWrap: true},
                                {type: 'text', wordWrap: true},
                                {type: 'text', wordWrap: true},
                                {type: 'text', wordWrap: true},
                                {type: 'text', wordWrap: true},
                                {type: 'text', wordWrap: true},
                                {type: 'text', wordWrap: true},
                                {type: 'text', wordWrap: true},
                                {type: 'text', wordWrap: true},
                                {type: 'text', wordWrap: true}
                            ]
                        });
                    }
*/
                },


            });
            if(ReplaceBtn!='') {
                $("#"+ReplaceBtn).html('');
            }
        }


}



var fnCreateFabKTsble = function(IdVal,ReplaceBtn,ComboName,ComponentName,ColorCode) {
    var ArrGetTableLastVal = IdVal.split("-");
    if(ArrGetTableLastVal[1] == 0) {
        $('#divPOSWQTYBKP').after("<div style='height:35px;background-color: #bffff9;'><strong>Fabric Knit P.O. WISE / SIZE WISE - QUANTITY BREAK-UP</strong></div><div id='FabricKnit-"+IdVal+"' class='pd0'></div>" +
            "<div class=\'clearfix\'></div><div id='fabricKnitRemarks'>Remarks</div><div id='fabricKnitDropbox'>Dropbox</div>");

        $("#FabricKnit-"+IdVal).jexcel({
            colHeaders: [ 'Combo Name', 'Component Name', 'Colour','Garment Parts','Fabric Details', 'Finishing GSM','Yarn Special Request','Fabric Finish Wet Process','Dry Process'],
            colWidths: [ 120, 120, 80, 150, 120, 150, 150, 250, 150],
            columns: [
                { type: 'dropdown', source:GlbComboArr },
                { type: 'dropdown', source:GlbComponentArr },
                { type: 'dropdown', source:GlbColorArr },
                { type: 'text', wordWrap:true},
                { type: 'text', wordWrap:true},
                { type: 'text', wordWrap:true},
                { type: 'text', wordWrap:true},
                { type: 'text', wordWrap:true},
                { type: 'text', wordWrap:true},
            ],
            onchange:function (obj,cell,fabricValue) {
                var itemizedEntryTblId = $(cell).prop('id').split('-');
                var data = $('#FabricKnit-' + IdVal).jexcel('getData');
                var GlbItemXSSumVal = [];
                var GlbItemSSumVal = [];
                var GlbItemMSumVal = [];
                var GlbItemLSumVal = [];
                var GlbItemXLSumVal = [];
                var GlbItemXXLSumVal = [];
                var GlbItem3XLSumVal = [];
                var GlbItem4XLSumVal = [];
                var ComboNewName = '';
                for (var i = 0; i < data.length; i++) {
                    console.log(data[i][0], 'FabricKnit fdfd');
                    if (data[i][0] != '') { ComboNewName = data[i][0]; }
                    if (ComboNewName != '') {
                        GlbItemXSSumVal = fnPopulateValueArray(GlbItemXSSumVal, ComboNewName, data[i][5]);
                        GlbItemSSumVal = fnPopulateValueArray(GlbItemSSumVal, ComboNewName, data[i][6]);
                        GlbItemMSumVal = fnPopulateValueArray(GlbItemMSumVal, ComboNewName, data[i][7]);
                        GlbItemLSumVal = fnPopulateValueArray(GlbItemLSumVal, ComboNewName, data[i][8]);
                        GlbItemXLSumVal = fnPopulateValueArray(GlbItemXLSumVal, ComboNewName, data[i][9]);
                        GlbItemXXLSumVal = fnPopulateValueArray(GlbItemXXLSumVal, ComboNewName, data[i][10]);
                        GlbItem3XLSumVal = fnPopulateValueArray(GlbItem3XLSumVal, ComboNewName, data[i][11]);
                        GlbItem4XLSumVal = fnPopulateValueArray(GlbItem4XLSumVal, ComboNewName, data[i][12]);
                    }
                }
                var SumItemizedVal = '';
                for (var i = 0; i < GlbComboArr.length; i++) {
                    SumItemizedVal = SumItemizedVal + "<div class='cutratiocumdiv'>Cumulative Quantity Per Size (" + GlbComboArr[i] + "):</div>" +
                        "<div class='cutratiocumeachdiv'>" + fnSumSizeArrayValue(GlbItemXSSumVal[GlbComboArr[i]]) + "</div>" + "" +
                        "<div class='cutratiocumeachdiv'>" + fnSumSizeArrayValue(GlbItemSSumVal[GlbComboArr[i]]) + "</div>" +
                        "<div class='cutratiocumeachdiv'>" + fnSumSizeArrayValue(GlbItemMSumVal[GlbComboArr[i]]) + "</div>" + "" +
                        "<div class='cutratiocumeachdiv'>" + fnSumSizeArrayValue(GlbItemLSumVal[GlbComboArr[i]]) + "</div>" +
                        "<div class='cutratiocumeachdiv'>" + fnSumSizeArrayValue(GlbItemXLSumVal[GlbComboArr[i]]) + "</div>" + "" +
                        "<div class='cutratiocumeachdiv'>" + fnSumSizeArrayValue(GlbItemXXLSumVal[GlbComboArr[i]]) + "</div>" +
                        "<div class='cutratiocumeachdiv'>" + fnSumSizeArrayValue(GlbItem3XLSumVal[GlbComboArr[i]]) + "</div>" + "" +
                        "<div class='cutratiocumeachdiv'>" + fnSumSizeArrayValue(GlbItem4XLSumVal[GlbComboArr[i]]) + "</div><div class='cutratiocumpodiv'>PO.QTY</div>" + "<div class='clearfix' class='pd0'></div>";
                }
                if ($("#fabricKnitCumQtyPerSize")) {
                    $("#fabricKnitCumQtyPerSize").html('');
                }
                $("#FabricKnit-" + IdVal).after('<div id="fabricKnitCumQtyPerSize">' + SumItemizedVal + '</div>');
            }
        });
        $('#fabricKnitRemarks').jexcel({
            colHeaders: ['Remarks'],
            colWidths: [1000],
            columns: [ {type: 'text'}, ]
        });

    }

};


var fnCreateFabWTsble = function(IdVal,ReplaceBtn,ComboName,ComponentName,ColorCode) {
    var AddBut = 'Qty-Breakup-' + IdVal + '-Add';

    var ArrGetTableLastVal = IdVal.split("-");

    var NewIdVal = ArrGetTableLastVal[0] + "-" + ArrGetTableLastVal[1] + "-" + ArrGetTableLastVal[2] + "-" + Number(ArrGetTableLastVal[3]) + 1;
    if(ArrGetTableLastVal[1] == 0) {
        $('#fabricKnitDropbox').after("<div style='height:35px;background-color: #bffff9;'><strong>Fabric Woven P.O. WISE / SIZE WISE - QUANTITY BREAK-UP</strong></div><div id='divFabricWoven' class='pd0'></div>" +
            "<div class=\'clearfix\'></div><div id='fabricWovenRemarks'>Remarks</div><div id='fabricWovenDropbox'>Dropbox</div>");

        $("#divFabricWoven").jexcel({
            colHeaders: ['Combo Name', 'Component Name', 'Colour', 'Fabric Details', 'Sort No', 'Product No.', 'Weight', 'Unit of Measure', 'Yarn Count', 'Fabric Construction', 'Width (Inches)', 'Fabric Finish'],
            colWidths: [120, 120, 80, 150, 120, 150, 150, 100, 150, 100, 100, 100],
            columns: [
                { type: 'dropdown', source:GlbComboArr },
                { type: 'dropdown', source:GlbComponentArr },
                { type: 'dropdown', source:GlbColorArr },
                {type: 'text', wordWrap: true},
                {type: 'text', wordWrap: true},
                {type: 'text', wordWrap: true},
                {type: 'text', wordWrap: true},
                {type: 'text', wordWrap: true},
                {type: 'text', wordWrap: true},
                {type: 'text', wordWrap: true},
                {type: 'text', wordWrap: true},
                {type: 'text', wordWrap: true},
            ]
        });

        $('#fabricWovenRemarks').jexcel({
            colHeaders: ['Remarks'],
            colWidths: [1000],
            columns: [ {type: 'text'}, ]
        });

    }
};

/*functions*/

var fnCreateDyingDetails = function(IdVal,ReplaceBtn,ComboName,ComponentName,ColorCode) {
    var AddBut = 'Qty-Breakup-' + IdVal + '-Add';

    var ArrGetTableLastVal = IdVal.split("-");

    var NewIdVal = ArrGetTableLastVal[0] + "-" + ArrGetTableLastVal[1] + "-" + ArrGetTableLastVal[2] + "-" + parseInt(ArrGetTableLastVal[3]) + 1;

    $('#fabricWovenDropbox').after("<div style='height:35px;background-color: #bffff9;'><strong>Dying Details</strong></div><div id='divDyingDetails' class='pd0'></div>" +
        "<div class=\'clearfix\'></div><div id='dyingDetailRemarks'></div>");

    if (ComboName != '' && ComponentName != '' && ColorCode != '') {
        var newdata = [
            [ComboName, ComponentName, ColorCode, '', '', '', '', '', 'Last',]
        ];

    }

    $("#divDyingDetails").jexcel({
        data: newdata,
        colHeaders: ['Combo Name', 'Component Name', 'Colour','Garment Parts', 'Fabric Details', 'Dying Type','Pantone' ,'Dyeing Special Request','Col. Mat. Lig. Cab. Std.'],
        colWidths: [120, 120, 80, 150, 120, 150, 150, 100, 150],
        columns: [
            { type: 'dropdown', source:GlbComboArr },
            { type: 'dropdown', source:GlbComponentArr },
            { type: 'dropdown', source:GlbColorArr },

            {type: 'text', wordWrap: true},
            {type: 'text', wordWrap: true},
            {type: 'text', wordWrap: true},
            {type: 'text', wordWrap: true},
            {type: 'text', wordWrap: true},
            {type: 'text', wordWrap: true},
            {type: 'text', wordWrap: true},
            {type: 'text', wordWrap: true},
            {type: 'text', wordWrap: true},
        ]
    });

    $('#dyingDetailRemarks').jexcel({
        colHeaders: ['Remarks'],
        colWidths: [1000],
        columns: [ {type: 'text'}, ]
    });
};

var fnCreateEmbell = function(IdVal,ReplaceBtn,ComboName,ComponentName,ColorCode) {
    var AddBut = 'Qty-Breakup-' + IdVal + '-Add';

    var ArrGetTableLastVal = IdVal.split("-");

    var NewIdVal = ArrGetTableLastVal[0] + "-" + ArrGetTableLastVal[1] + "-" + ArrGetTableLastVal[2] + "-" + parseInt(ArrGetTableLastVal[3]) + 1;

    $('#dyingDetailRemarks').after("<div id='embell_heading' style='height:35px;background-color: #bffff9;'><strong>Embellishment</strong></div><div id='divEmbellishment' class='pd0'></div>" +
        "<div class=\'clearfix\'></div><div id='fabricEmbellRemarks'></div>");

    if (ComboName != '' && ComponentName != '' && ColorCode != '') {
        data = [
            [ComboName, ComponentName, ColorCode, '', '', '', '', '', '', '', 'Test']
        ];

    }

    $("#divEmbellishment").jexcel({
        data: data,
        colHeaders: ['Combo Name', 'Component Name', 'Colour', 'Artwork Name / Code', 'Embellishment Type','Medium / Material','Item Code','Embellishment Colour Name','Shade','Size OR Dim.','Unit of Measure'],
        colWidths: [120, 120, 80, 150, 120, 150, 150, 100, 150, 100, 100],
        columns: [
            { type: 'dropdown', source:GlbComboArr },
            { type: 'dropdown', source:GlbComponentArr },
            { type: 'dropdown', source:GlbColorArr },

            {type: 'text', wordWrap: true},
            {type: 'text', wordWrap: true},
            {type: 'text', wordWrap: true},
            {type: 'text', wordWrap: true},
            {type: 'text', wordWrap: true},
            {type: 'text', wordWrap: true},
            {type: 'text', wordWrap: true},
            {type: 'text', wordWrap: true},

        ]
    });

    $('#fabricEmbellRemarks').jexcel({
        colHeaders: ['Remarks'],
        colWidths: [1000],
        columns: [ {type: 'text'}, ]
    });
};

var fnBomDetails = function (IdVal,ReplaceBtn,ComboName,ComponentName,ColorCode) {
    var AddBut = 'Qty-Breakup-' + IdVal + '-Add';

    var ArrGetTableLastVal = IdVal.split("-");

    var NewIdVal = ArrGetTableLastVal[0] + "-" + ArrGetTableLastVal[1] + "-" + ArrGetTableLastVal[2] + "-" + parseInt(ArrGetTableLastVal[3]) + 1;

    $('#fabricEmbellRemarks').after("<div style='height:35px;background-color: #bffff9;'><strong>BOM Details</strong></div><div id='divBomDetails' class='pd0'></div>" +
        "<div class=\'clearfix\'></div><div id='bomRemarks'></div><div id='bomDetailsEnds'></div>");

    if (ComboName != '' && ComponentName != '' && ColorCode != '') {
        data = [
            [ComboName, ComponentName, ColorCode, '', '', '', '', '', '', '', 'Test']
        ];

    }

    $("#divBomDetails").jexcel({
        data: data,
        colHeaders: ['Combo Name', 'Component Name', 'Colour', 'Artwork Name / Code', 'Embellishment Type','Medium / Material','Item Code','Embellishment Colour Name','Shade','Size OR Dim.','Unit of Measure'],
        colWidths: [120, 120, 80, 150, 120, 150, 150, 100, 150, 100, 100],
        columns: [
            { type: 'dropdown', source:GlbComboArr },
            { type: 'dropdown', source:GlbComponentArr },
            { type: 'dropdown', source:GlbColorArr },

            {type: 'text', wordWrap: true},
            {type: 'text', wordWrap: true},
            {type: 'text', wordWrap: true},
            {type: 'text', wordWrap: true},
            {type: 'text', wordWrap: true},
            {type: 'text', wordWrap: true},
            {type: 'text', wordWrap: true},
            {type: 'text', wordWrap: true},

        ]
    });

    $('#bomRemarks').jexcel({
        colHeaders: ['Remarks'],
        colWidths: [1000],
        columns: [ {type: 'text'}, ]
    });
};


var fnBomConsolidated = function (IdVal,ReplaceBtn,ComboName,ComponentName,ColorCode) {
    var AddBut = 'Qty-Breakup-' + IdVal + '-Add';

    var ArrGetTableLastVal = IdVal.split("-");

    var NewIdVal = ArrGetTableLastVal[0] + "-" + ArrGetTableLastVal[1] + "-" + ArrGetTableLastVal[2] + "-" + parseInt(ArrGetTableLastVal[3]) + 1;

    $('#bomDetailsEnds').after("<div style='height:35px;background-color: #bffff9;'><strong>BOM CONSOLIDATED</strong></div><div id='divBomConsolidated' class='pd0'></div>" +
        "<div class=\'clearfix\'></div><div id='bomConsolidatedRemarks'></div>");

    if (ComboName != '' && ComponentName != '' && ColorCode != '') {
        data = [
            [ComboName, ComponentName, ColorCode, '', '', '', '', '', '', '']
        ];

    }

    $("#divBomConsolidated").jexcel({
        data: data,
        colHeaders: ['Combo Name', 'Component Name', 'Colour', 'PO No.', 'Item Desc.','Item Code','Color Code','Size','Unit of Measure','Consolidated BOM Qty.'],
        colWidths: [120, 120, 80, 150, 120, 150, 150, 100, 150, 100, 100],
        columns: [
            { type: 'dropdown', source:GlbComboArr },
            { type: 'dropdown', source:GlbComponentArr },
            { type: 'dropdown', source:GlbColorArr },
            {type: 'text', wordWrap: true},
            {type: 'text', wordWrap: true},
            {type: 'text', wordWrap: true},
            {type: 'text', wordWrap: true},
            {type: 'text', wordWrap: true},
            {type: 'text', wordWrap: true},
            {type: 'text', wordWrap: true},
            {type: 'text', wordWrap: true},

        ]
    });

    $('#bomConsolidatedRemarks').jexcel({
        colHeaders: ['Remarks'],
        colWidths: [1000],
        columns: [ {type: 'text'}, ]
    });
};

var fnBomSrcAppDetails = function (IdVal,ReplaceBtn,ComboName,ComponentName,ColorCode) {
    var AddBut = 'Qty-Breakup-' + IdVal + '-Add';

    var ArrGetTableLastVal = IdVal.split("-");

    var NewIdVal = ArrGetTableLastVal[0] + "-" + ArrGetTableLastVal[1] + "-" + ArrGetTableLastVal[2] + "-" + parseInt(ArrGetTableLastVal[3]) + 1;

    $('#bomConsolidatedRemarks').after("<div style='height:35px;background-color: #bffff9;'><strong>BOM SOURCING & APPROVAL DETAILS</strong></div><div id='divBomSrcApproveDetails' class='pd0'></div>" +
        "<div class=\'clearfix\'></div><div id='bomConsolidatedRemarks'></div>");

    if (ComboName != '' && ComponentName != '' && ColorCode != '') {
        data = [
            [ComboName, ComponentName, ColorCode, '', '']
        ];

    }

    $("#divBomSrcApproveDetails").jexcel({
        data: data,
        colHeaders: ['Item Description', 'Category', 'Sourcing Details', 'If nominated', 'BOM Approval'],
        colWidths: [120, 120, 80, 150, 120],
        columns: [
            { type: 'dropdown', source:GlbComboArr },
            { type: 'dropdown', source:GlbComponentArr },
            { type: 'dropdown', source:GlbColorArr },

            {type: 'text', wordWrap: true},
            {type: 'text', wordWrap: true},
        ]
    });

    $('#bomRemarks').jexcel({
        colHeaders: ['Remarks'],
        colWidths: [1000],
        columns: [ {type: 'text'}, ]
    });
};

var fnCgpf = function (IdVal,ReplaceBtn,ComboName,ComponentName,ColorCode) {
    var AddBut = 'Qty-Breakup-' + IdVal + '-Add';

    var ArrGetTableLastVal = IdVal.split("-");

    var NewIdVal = ArrGetTableLastVal[0] + "-" + ArrGetTableLastVal[1] + "-" + ArrGetTableLastVal[2] + "-" + parseInt(ArrGetTableLastVal[3]) + 1;

    $('#bomConsolidatedRemarks').after("<div style='height:35px;background-color: #bffff9;'><strong>Complete Garment Process Flow (Cutting to Finishing)</strong></div><div id='divCgpf' class='pd0'></div>" +
        "<div class=\'clearfix\'></div><div id='cgpfEnd'></div>");

    if (ComboName != '' && ComponentName != '' && ColorCode != '') {
        data = [
            [ComboName, ComponentName, ColorCode, '', '']
        ];

    }

    $("#divCgpf").jexcel({
        data: data,
        colHeaders: ['Combo Name', 'Component Name', 'Colour', 'Description', 'Remarks'],
        colWidths: [120, 120, 80, 150, 120, 150, 150, 100, 150, 100, 100],
        columns: [
            { type: 'dropdown', source:GlbComboArr },
            { type: 'dropdown', source:GlbComponentArr },
            { type: 'dropdown', source:GlbColorArr },

            {type: 'text', wordWrap: true},
            {type: 'text', wordWrap: true},
        ]
    });

};

var fnSamplingDetails = function (IdVal,ReplaceBtn,ComboName,ComponentName,ColorCode) {
    var AddBut = 'Qty-Breakup-' + IdVal + '-Add';

    var ArrGetTableLastVal = IdVal.split("-");

    var NewIdVal = ArrGetTableLastVal[0] + "-" + ArrGetTableLastVal[1] + "-" + ArrGetTableLastVal[2] + "-" + parseInt(ArrGetTableLastVal[3]) + 1;

    $('#cgpfEnd').after("<div style='height:35px;background-color: #bffff9;'><strong>Sampling Details</strong></div><div id='divSamplingDetails' class='pd0'></div>" +
        "<div class=\'clearfix\'></div><div id='bomSampleReamrks'></div><div id='samplingDropbox'></div>");

    if (ComboName != '' && ComponentName != '' && ColorCode != '') {
        data = [
            [ComboName, ComponentName, ColorCode, '', '', '', '', '', '', '', 'Test']
        ];

    }

    $("#divSamplingDetails").jexcel({
        data: data,
        colHeaders: ['Combo Name', 'Component Name', 'Colour', 'Sample Description','Size','Buyer','Buying Off.','Total No of samples','Submission Date','Buyers Weekly Sample App. Days','Approving Authrity','Proceed'],
        colWidths: [120, 120, 80, 150, 120, 150, 150, 100, 150, 100, 100],
        columns: [
            { type: 'dropdown', source:GlbComboArr },
            { type: 'dropdown', source:GlbComponentArr },
            { type: 'dropdown', source:GlbColorArr },

            {type: 'text', wordWrap: true},
            {type: 'text', wordWrap: true},
            {type: 'text', wordWrap: true},
            {type: 'text', wordWrap: true},
            {type: 'text', wordWrap: true},
            {type: 'text', wordWrap: true},
            {type: 'text', wordWrap: true},
            {type: 'text', wordWrap: true},

        ]
    });

    $('#bomSampleReamrks').jexcel({
        colHeaders: ['Remarks'],
        colWidths: [1000],
        columns: [ {type: 'text'}, ]
    });
};

var fnLabTestDetails = function (IdVal,ReplaceBtn,ComboName,ComponentName,ColorCode) {
    var AddBut = 'Qty-Breakup-' + IdVal + '-Add';

    var ArrGetTableLastVal = IdVal.split("-");

    var NewIdVal = ArrGetTableLastVal[0] + "-" + ArrGetTableLastVal[1] + "-" + ArrGetTableLastVal[2] + "-" + parseInt(ArrGetTableLastVal[3]) + 1;

    $('#samplingDropbox').after("<div style='height:35px;background-color: #bffff9;'><strong>Lab Testing Details</strong></div><div id='divLabTest' class='pd0'></div>" +
        "<div class=\'clearfix\'></div><div id='bomLabTest'></div>");

    if (ComboName != '' && ComponentName != '' && ColorCode != '') {
        data = [
            [ComboName, ComponentName, ColorCode, '', '', '', '', '', '', '']
        ];

    }

    $("#divLabTest").jexcel({
        data: data,
        colHeaders: ['Combo Name', 'Component Name', 'Colour', 'Garment Parts','Fabric','Lab Description','Acceptable Level','Testing Authority','Approval Authrity','Procees To Processing'],
        colWidths: [120, 120, 80, 150, 120, 150, 150, 100, 150, 100, 100],
        columns: [
            { type: 'dropdown', source:GlbComboArr },
            { type: 'dropdown', source:GlbComponentArr },
            { type: 'dropdown', source:GlbColorArr },

            {type: 'text', wordWrap: true},
            {type: 'text', wordWrap: true},
            {type: 'text', wordWrap: true},
            {type: 'text', wordWrap: true},
            {type: 'text', wordWrap: true},
            {type: 'text', wordWrap: true},
            {type: 'text', wordWrap: true},
            {type: 'text', wordWrap: true},

        ]
    });

};

var fnPackingDetails = function (IdVal,ReplaceBtn,ComboName,ComponentName,ColorCode) {
    var AddBut = 'Qty-Breakup-' + IdVal + '-Add';

    var ArrGetTableLastVal = IdVal.split("-");

    var NewIdVal = ArrGetTableLastVal[0] + "-" + ArrGetTableLastVal[1] + "-" + ArrGetTableLastVal[2] + "-" + parseInt(ArrGetTableLastVal[3]) + 1;

    $('#bomLabTest').after("<div style='height:35px;background-color: #bffff9;'><strong>Packing Details</strong></div><div id='divPackingDetails' class='pd0'></div>" +
        "<div class=\'clearfix\'></div><div id='packingDetailsEnd'></div>");

    if (ComboName != '' && ComponentName != '' && ColorCode != '') {
        data = [
            [ComboName, ComponentName, ColorCode, '', '', '', '', '', '', '', 'Test']
        ];

    }

    $("#divPackingDetails").jexcel({
        data: data,
        colHeaders: ['P.O. No.','Combo Name', 'Component Name', 'Colour', 'Packing Code','Packing Type','Intake','Comp. per bag','Pcs. per bag','XS','S','M','L','XL','XXL','3XL','4XL','Total'],
        colWidths: [120, 120, 80, 150, 120, 150, 150, 100, 150, 100, 100],
        columns: [
            { type: 'dropdown', source:GlbComboArr },
            { type: 'dropdown', source:GlbComponentArr },
            { type: 'dropdown', source:GlbColorArr },

            {type: 'text', wordWrap: true},
            {type: 'text', wordWrap: true},
            {type: 'text', wordWrap: true},
            {type: 'text', wordWrap: true},
            {type: 'text', wordWrap: true},
            {type: 'text', wordWrap: true},
            {type: 'text', wordWrap: true},
            {type: 'text', wordWrap: true},

        ]
    });

};

var fnCartonAssortmentRatio = function (IdVal,ReplaceBtn,ComboName,ComponentName,ColorCode) {
    var AddBut = 'Qty-Breakup-' + IdVal + '-Add';

    var ArrGetTableLastVal = IdVal.split("-");

    var NewIdVal = ArrGetTableLastVal[0] + "-" + ArrGetTableLastVal[1] + "-" + ArrGetTableLastVal[2] + "-" + parseInt(ArrGetTableLastVal[3]) + 1;

    $('#packingDetailsEnd').after("<div style='height:35px;background-color: #bffff9;'><strong>Carton Assortment Ratio</strong></div><div id='divCartonAssortmentRatio' class='pd0'></div>" +
        "<div class=\'clearfix\'></div><div id='cartonAssortmentRatioEnd'></div>");

    if (ComboName != '' && ComponentName != '' && ColorCode != '') {
        data = [
            [ComboName, ComponentName, ColorCode, '', '', '', '', '', '', '', 'Test']
        ];

    }

    $("#divCartonAssortmentRatio").jexcel({
        data: data,
        colHeaders: ['P.O. No.','Combo Name','XS','S','M','L','XL','XXL','3XL','4XL','Qty. Per Ratio','No. of Ratio Per Carton.','Qty. Per Carton','Total No. of Cartons','Combo / Colour wise Qty.','P.O. Qty.'],
        colWidths: [120, 120, 80, 150, 120, 150, 150, 100, 150, 100, 100],
        columns: [
            { type: 'dropdown', source:GlbComboArr },
            { type: 'dropdown', source:GlbComponentArr },
            { type: 'dropdown', source:GlbColorArr },
            {type: 'text', wordWrap: true},
            {type: 'text', wordWrap: true},
            {type: 'text', wordWrap: true},
            {type: 'text', wordWrap: true},
            {type: 'text', wordWrap: true},
            {type: 'text', wordWrap: true},
            {type: 'text', wordWrap: true},
            {type: 'text', wordWrap: true},

        ]
    });

};

var fnLotInspectionDetails = function (IdVal,ReplaceBtn,ComboName,ComponentName,ColorCode) {
    var AddBut = 'Qty-Breakup-' + IdVal + '-Add';

    var ArrGetTableLastVal = IdVal.split("-");

    var NewIdVal = ArrGetTableLastVal[0] + "-" + ArrGetTableLastVal[1] + "-" + ArrGetTableLastVal[2] + "-" + parseInt(ArrGetTableLastVal[3]) + 1;

    $('#cartonAssortmentRatioEnd').after("<div style='height:35px;background-color: #bffff9;'><strong>Lot and Inspection Details</strong></div><div id='divLotInspection' class='pd0'></div>" +
        "<div class=\'clearfix\'></div><div id='lotInspectionEnd'></div>");

    if (ComboName != '' && ComponentName != '' && ColorCode != '') {
        data = [
            [ComboName, ComponentName, ColorCode, '', '', '', '', '', '']
        ];

    }

    $("#divLotInspection").jexcel({
        data: data,
        colHeaders: ['P.O.No.', 'P.O. Qty.','Level','Code Letter','Sample Size','AQL','Inspection Authority','FI Date','Remarks'],
        colWidths: [120, 120, 80, 150, 120, 150, 150, 100, 150],
        columns: [
            { type: 'dropdown', source:GlbComboArr },
            { type: 'dropdown', source:GlbComponentArr },
            { type: 'dropdown', source:GlbColorArr },
            {type: 'text', wordWrap: true},
            {type: 'text', wordWrap: true},
            {type: 'text', wordWrap: true},
            {type: 'text', wordWrap: true},
            {type: 'text', wordWrap: true},
            {type: 'text', wordWrap: true},

        ]
    });

};

var fnInvoiceAndDocumentation = function (IdVal,ReplaceBtn,ComboName,ComponentName,ColorCode) {
    var AddBut = 'Qty-Breakup-' + IdVal + '-Add';

    var ArrGetTableLastVal = IdVal.split("-");

    var NewIdVal = ArrGetTableLastVal[0] + "-" + ArrGetTableLastVal[1] + "-" + ArrGetTableLastVal[2] + "-" + parseInt(ArrGetTableLastVal[3]) + 1;

    $('#lotInspectionEnd').after("<div style='height:35px;background-color: #bffff9;'><strong>Invoice And Documentation</strong></div><div id='divInvoiceAndDoc' class='pd0'></div>" +
        "<div class=\'clearfix\'></div><div id='lotInspectionEnd'></div>");

    if (ComboName != '' && ComponentName != '' && ColorCode != '') {
        data = [
            [ComboName, ComponentName, ColorCode, '', '', '', '', '', '']
        ];

    }

    $("#divInvoiceAndDoc").jexcel({
        data: data,
        colHeaders: ['Combo Name', 'Component Name', 'Colour', 'P.O. Qty.', 'Consignor / Shipper / Exporter','Consignee','Importer - If other than Consignee','Forwarding Agent','Clearing Agent'],
        colWidths: [120, 120, 80, 150, 120, 150, 150, 100, 150],
        columns: [
            { type: 'dropdown', source:GlbComboArr },
            { type: 'dropdown', source:GlbComponentArr },
            { type: 'dropdown', source:GlbColorArr },
            {type: 'text', wordWrap: true},
            {type: 'text', wordWrap: true},
            {type: 'text', wordWrap: true},
            {type: 'text', wordWrap: true},
            {type: 'text', wordWrap: true},
            {type: 'text', wordWrap: true},
        ]
    });

};

var fnMerchantCheckList = function (IdVal,ReplaceBtn,ComboName,ComponentName,ColorCode) {
    var AddBut = 'Qty-Breakup-' + IdVal + '-Add';

    var ArrGetTableLastVal = IdVal.split("-");

    var NewIdVal = ArrGetTableLastVal[0] + "-" + ArrGetTableLastVal[1] + "-" + ArrGetTableLastVal[2] + "-" + parseInt(ArrGetTableLastVal[3]) + 1;

    $('#lotInspectionEnd').after("<div style='height:35px;background-color: #bffff9;'><strong>Merchant CheckList</strong></div><div id='divMerchantCheckList' class='pd0'></div>" +
        "<div class=\'clearfix\'></div><div id='checkListEnd'></div>");

    $("#divMerchantCheckList").jexcel({
        data: [
            ['Artwork with style description','','','',''],
            ['Measurement chart graded (latest)','','','',''],
            ['How to measure details','','','',''],
            ['Sewing details with SPI & Thread Tkt.','','','',''],
            ['Fabric details (Body - 1)','','','',''],
            ['Fabric details (Body - 2)','','','',''],
            ['Fabric details (Shell - 1)','','','',''],
            ['Fabric details (Shell - 2)','','','',''],
            ['Dying Color Details','','','',''],
            ['Fabric Processing Details','','','',''],
            ['Trimming Details','','','',''],
            ['Trimming Placement Details','','','',''],
            ],
        colHeaders: ['Desc','NA','NR','PR','Rec.'],
        colWidths: [220,100,100,100,100],
        columns: [
            {type: 'text', wordWrap: true},

        ]
    });
};
/*functions ends*/

handler = function(obj, cell, val) {
    var id                  = $(cell).prop('id').split('-');
    var data                = $('#divComboColorTable').jexcel('getData');
    if(typeof(GlbComboComponent[id[1]])=="undefined") {
        GlbComboComponent[id[1]]=[];
        for(var i=0;i<=5;i++) {
            GlbComboComponent[id[1]][i] = data[id[1]][i];
        }
        if(GlbComboComponent[id[1]][0]!='' && GlbComboComponent[id[1]][1]!='' && GlbComboComponent[id[1]][2]!='') {
            if($("#Item-2-0").length == 1) {
            }
            fnCreateCuttingRatioTable($(cell).prop('id'),'',GlbComboComponent[id[1]][0],GlbComboComponent[id[1]][1],GlbComboComponent[id[1]][2]);
            fnCreateItemizedTable($(cell).prop('id'),'',GlbComboComponent[id[1]][0],GlbComboComponent[id[1]][1],GlbComboComponent[id[1]][2]);
        }

    } else {
        for(var i=0;i<=5;i++) {
            GlbComboComponent[id[1]][i] = data[id[1]][i];
        }
        if(GlbComboComponent[id[1]][0]!='' && GlbComboComponent[id[1]][1]!='' && GlbComboComponent[id[1]][2]!='') {

            if(!$("#Cutting-"+$(cell).prop('id')).length) {fnCreateCuttingRatioTable($(cell).prop('id'),'',GlbComboComponent[id[1]][0],GlbComboComponent[id[1]][1],GlbComboComponent[id[1]][2]);}

            if(!$("#Item-"+$(cell).prop('id')).length) {fnCreateItemizedTable($(cell).prop('id'),'',GlbComboComponent[id[1]][0],GlbComboComponent[id[1]][1],GlbComboComponent[id[1]][2]);}
/*

            fnCreateFabKTsble($(cell).prop('id'),'',GlbComboComponent[id[1]][0],GlbComboComponent[id[1]][1],GlbComboComponent[id[1]][2]);


            fnCreateFabWTsble($(cell).prop('id')+"-1",'',GlbComboComponent[id[1]][0],GlbComboComponent[id[1]][1],GlbComboComponent[id[1]][2]);
            fnCreateDyingDetails($(cell).prop('id')+"-1",'',GlbComboComponent[id[1]][0],GlbComboComponent[id[1]][1],GlbComboComponent[id[1]][2]);

            fnCreateEmbell($(cell).prop('id')+"-1",'',GlbComboComponent[id[1]][0],GlbComboComponent[id[1]][1],GlbComboComponent[id[1]][2]);
            fnBomDetails($(cell).prop('id')+"-1",'',GlbComboComponent[id[1]][0],GlbComboComponent[id[1]][1],GlbComboComponent[id[1]][2]);

            fnBomConsolidated($(cell).prop('id')+"-1",'',GlbComboComponent[id[1]][0],GlbComboComponent[id[1]][1],GlbComboComponent[id[1]][2]);

            fnBomSrcAppDetails($(cell).prop('id')+"-1",'',GlbComboComponent[id[1]][0],GlbComboComponent[id[1]][1],GlbComboComponent[id[1]][2]);

            fnCgpf($(cell).prop('id')+"-1",'',GlbComboComponent[id[1]][0],GlbComboComponent[id[1]][1],GlbComboComponent[id[1]][2]);

            fnSamplingDetails($(cell).prop('id')+"-1",'',GlbComboComponent[id[1]][0],GlbComboComponent[id[1]][1],GlbComboComponent[id[1]][2]);

            fnLabTestDetails($(cell).prop('id')+"-1",'',GlbComboComponent[id[1]][0],GlbComboComponent[id[1]][1],GlbComboComponent[id[1]][2]);
            fnPackingDetails($(cell).prop('id')+"-1",'',GlbComboComponent[id[1]][0],GlbComboComponent[id[1]][1],GlbComboComponent[id[1]][2]);
            fnCartonAssortmentRatio($(cell).prop('id')+"-1",'',GlbComboComponent[id[1]][0],GlbComboComponent[id[1]][1],GlbComboComponent[id[1]][2]);
            fnLotInspectionDetails($(cell).prop('id')+"-1",'',GlbComboComponent[id[1]][0],GlbComboComponent[id[1]][1],GlbComboComponent[id[1]][2]);
            fnInvoiceAndDocumentation($(cell).prop('id')+"-1",'',GlbComboComponent[id[1]][0],GlbComboComponent[id[1]][1],GlbComboComponent[id[1]][2]);
            fnMerchantCheckList($(cell).prop('id'),'',GlbComboComponent[id[1]][0],GlbComboComponent[id[1]][1],GlbComboComponent[id[1]][2]);
*/


        }

    }

};

var data=[];
$('#divComboColorTable').jexcel({
    data:data,
    colHeaders: [ 'Combo Name', 'Component Name', 'Colour', 'Intake Qty', 'Order Qty','Itemized Qty.'],
    colWidths: [ 210, 210, 220, 220, 180, 180],
    onchange:handler,
    columns: [
        { type: 'text', wordWrap:true},
        { type: 'text', wordWrap:true},
        { type: 'text', wordWrap:true},
        { type: 'text', wordWrap:true},
        { type: 'text', wordWrap:true},
        { type: 'text', wordWrap:true}
    ]
});

var html = '<thead class="jexcel_label">'+'<tr>'+'<td class="jexcel_label" width="30"></td>'+'<td width="200" align="center">Group 2</td>'+'<td colspan="2" width="400" align="center">Fabric Details</td>'+'</tr>'+'</thead>';
$('#FabricKnit-2-0-1').find('thead').before('<span>Testst</span>');

$("#deliverytable").jexcel({
    colHeaders: ['P.O Date', 'P.O Rec. Date', 'P.O No.', 'Combo Name', 'P.O Qty.', 'Mode of Shipment', 'Shipment Date', 'Ex-Factory Date', 'Departure Date', 'Loading Port', 'Destination Port', 'Country'],
    colWidths: [90, 100, 100, 110, 90, 120, 110, 120, 110, 90, 110, 90],
    columns: [
        {type: 'calendar', options: {format: 'DD/MM/YYYY'}},
        {type: 'calendar', options: {format: 'DD/MM/YYYY'}},
        {type: 'dropdown', source: GlbPono},
        {type: 'text', wordWrap: true},
        {type: 'text', wordWrap: true},
        {type: 'text', wordWrap: true},
        {type: 'text', wordWrap: true},
        {type: 'text', wordWrap: true},
        {type: 'text', wordWrap: true},
        {type: 'text', wordWrap: true},
        {type: 'text', wordWrap: true},
        {type: 'text', wordWrap: true}
    ]
});
