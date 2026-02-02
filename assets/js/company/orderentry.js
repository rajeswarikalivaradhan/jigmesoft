var GlbTeamId       = '', GlbParam        = 'rfrom=1', PcsOrSet     = [{"id":"1","name":"Pcs."},{"id":"2","name":"Set"}];
var ArrPcsOrSet     = ["Pcs.","Set"], authority       = JSON.parse(GlbAuthority), unitMeasure     = JSON.parse(GlbUnitmeasure), GlbPoNos = [], GlbPoQty = [], GlbPcsOrSet = [];
var GlbPoQty = [], GlbSizeSpec = [], ArrSplittedConent = [], ArrAllSplited=[], GlbAllComponentArr = [], GlbComboArr = [], GlbAllComboArr = [];
var GlbComponentArr     = [], GlbDyeingType = ["FD", "YD", "SDB"];
var GlbPonoId           = 0, GlbPoQtyId = 0, GlbPcsOrSetId = 0;
var GlbSizeSpecFitArr     = [];
var GlbColorArr         = []; var NewColor = [];
var GlbAllColorArr      = [];
var GlbAllSizeCodeFit = [];
var GlbItemizedQty = 0, ArrItemTotal = [], GlbUniqueSplitComponent = [], GlbUniqueSplitColors = [], GlbPonoComboColorPoqty = [], GlbQtyForEachSet = [];
var compDropdowncommon = function (instance, cell, c, r, source) {
    var ObjId = $(instance).prop('id');
    var comboName = $("#" + ObjId).jexcel('getValue', '0-' + r);
    var ComboId = jQuery.inArray(comboName, GlbComboArr);
    return GlbComponentArr[ComboId].split("|#|");
}

var colorDropdowncommon = function (instance, cell, c, r, source) {
    var ObjId = $(instance).prop('id');
    var comboName = $("#" + ObjId).jexcel('getValue', '0-' + r);
    var compName = $("#" + ObjId).jexcel('getValue', '1-' + r);
    var Color = $("#" + ObjId).jexcel('getValue', '2-' + r);

    var ComboId = jQuery.inArray(comboName, GlbComboArr);
    var CompId = jQuery.inArray(compName, GlbAllComponentArr);
    //console.log(GlbColorArr,'GlbColorArr');
    /*console.log(CalcPoQty,'CalcPoQty in filter');
    console.log(GlbQtyForEachSet,'GlbQtyForEachSet in filter');
    console.log(GlbQtyForEachSet[comboName+"#"+compName+"#"+Color],'qyu val in filter');
    var qty = GlbQtyForEachSet[comboName+"#"+compName+"#"+Color];*/
    return GlbColorArr[ComboId + "-" + CompId].split("|#|");
}

var FilterSplittedColors = function (instance, cell, c, r, source) {
    var ObjId     = $(instance).prop('id');
    var comboName = $("#" + ObjId).jexcel('getValue', '0-' + r);
    var compName  = $("#" + ObjId).jexcel('getValue', '1-' + r);
    var ComboId   = jQuery.inArray(comboName, GlbComboArr);
    var CompId    = jQuery.inArray(compName, GlbUniqueSplitComponent);
    console.log(ComboId,'ComboId'); console.log(CompId,'CompId');
    return NewColor[ComboId + "-" + CompId].split('|#|');
}

var PosDropdowncommon = function (instance, cell, c, r, source) {
    var ObjId = $(instance).prop('id');
    var sizeSpecCodeFit = $("#" + ObjId).jexcel('getValue', '3-' + r);
    return GlbSizeSpecFitArr[sizeSpecCodeFit].split("|#|");
}

var PosDropdowncommon2 = function (instance, cell, c, r, source) {
    var ObjId = $(instance).prop('id');
    var sizeSpecCodeFit = $("#" + ObjId).jexcel('getValue', '0-' + r);
    return GlbSizeSpecFitArr[sizeSpecCodeFit].split("|#|");
}

var GlbComboItems = '';
var GlbItemPoNoList = [], GlbAllPOList = [];
var GlbItemPoNoQtyList = new Array();
var ArrFnlPOWiseInfo = new Array();

var GlbReadOnlyInfo = new Array();
var compArrNew = [], GlbPcsSetFilter = [];

var GlbCuttingRatioData = [];
divComboColorFirstTableChange = function (obj, cell, val) {
    var data = $('#divComboColorFirstTable').jexcel('getData');
    var ComboName = '', CompName = '', ColorName = '', GlbComboId = 0, ComboColorTableQty = 0, ComboColorTablePackType = '';
    GlbComboArr = []; GlbAllComboArr = []; GlbComponentArr = []; GlbAllComponentArr = []; GlbColorArr = []; GlbAllColorArr = [];
    GlbComboItems = "<option value=''></option>";
    for (var i = 0; i < data.length; i++) {
        if (jsTrim(data[i][0]) == "" && GlbComboId==0) {
            var ComboName = data[i][0];
            var ComboId = jQuery.inArray(ComboName, GlbComboArr);//array_search(ComboName,GlbComboArr);
            if(ComboName!="") {
                GlbComboArr.push(ComboName);
            } else if(ComboId<0) {
                GlbComboArr.push(ComboName);
            }
        } else if(jsTrim(data[i][0]) != "") {
            var ComboName = data[i][0];

            if(ComboName=="") {
                ComboId = GlbComboId;
            } else {
                var ComboId = jQuery.inArray(ComboName, GlbComboArr);//array_search(ComboName,GlbComboArr);
                GlbComboId = ComboId;
            }
            if(ComboId<0) {
                GlbComboArr.push(ComboName);
            }
        }
        if (jsTrim(data[i][1]) != "") {
            var ComboId = jQuery.inArray(ComboName, GlbComboArr);//array_search(ComboName,GlbComboArr);
            var CompName = jsTrim(data[i][1]);
            if (typeof (GlbComponentArr[ComboId]) != "undefined") {
                if(ComboName!="") {
                    GlbComponentArr[ComboId] = GlbComponentArr[ComboId] + "|#|" + CompName;
                } else if(ComboName=="") {
                    var CompId = jQuery.inArray(CompName, GlbAllComponentArr);//array_search(ComboName,GlbComboArr);
                    if(CompId=="-1") {
                        GlbComponentArr[ComboId] = GlbComponentArr[ComboId] + "|#|" + CompName;
                    }
                }
            } else {
                GlbComponentArr[ComboId] = CompName;
            }
            if(ComboName=="") {
                var CompId = jQuery.inArray(CompName, GlbAllComponentArr);//array_search(ComboName,GlbComboArr);
                if(CompId=="-1") {
                    GlbAllComponentArr.push(CompName);
                }
            } else {
                GlbAllComponentArr.push(CompName);
            }
            var CompId = jQuery.inArray(CompName, GlbAllComponentArr);
        }
        if (jsTrim(data[i][2]) != "") {
            var Color = jsTrim(data[i][2]);
            GlbAllColorArr.push(data[i][2]);
            if (typeof (GlbColorArr[ComboId + "-" + CompId]) != "undefined") {
                GlbColorArr[ComboId + "-" + CompId] = GlbColorArr[ComboId + "-" + CompId] + "|#|" + data[i][2];
            } else {
                GlbColorArr[ComboId + "-" + CompId] = data[i][2];
            }
            GlbComboItems = GlbComboItems + "<option value='" + ComboName + "#" + CompName + "#" + data[i][2] + "'>" + data[i][2] + "(" + ComboName + "/" + CompName + ")" + "</option>"
        }
        ComboColorTableQty = ComboColorTableQty + parseInt(data[i][3]);
        ComboColorTablePackType = data[i][4];
    }
    $("#divComboColorFirstTableTotal").text(ComboColorTableQty);
    $("#divComboColorFirstTablePackType").text(ComboColorTablePackType);
    //Calculate Cumulative Info.
    var SumComboSetQtyVal                           = '';
    var ArrSetComboArr                              = [];
    for (var i = 0; i < data.length; i++) {
        if(jsTrim(data[i][0]) != "" && jsTrim(data[i][1]) != "" && jsTrim(data[i][2]) != "" && jsTrim(data[i][4]) == 2 && parseInt(jsTrim(data[i][4]))>=1) {
            var ComboId                             = jQuery.inArray(data[i][0], ArrSetComboArr);
            if(ComboId=='-1') {
                ArrSetComboArr.push(data[i][0]);
                SumComboSetQtyVal = SumComboSetQtyVal + "<div><div class='sumcombosetqty1'>Quantity Per Combo (" + data[i][0] + "):</div><div class='sumcombosetqty2'>"+data[i][3]+"</div><div class='sumcombosetqty3'>&nbsp;</div></div><div class='clearfix'></div>";
            }
        }
        //GlbQtyForEachSet[QtySet] = fnPopulateValueArray()
        //GlbQtyForEachSet.push(data[i][0]+"#"+data[i][1]+"#"+data[i][2]+"-"+data[i][3]);
    }
    //console.log(GlbComboArr.length,'start');
    if (parseInt(GlbComboArr.length) >= 1) {
        fnPoWiseQtyBrkUpSecondTbl();
        fnCuttingRatioSixthTbl();

    }
    //console.log(GlbQtyForEachSet,'in change');
}

$('#divComboColorFirstTable').jexcel({
    data: {},
    colHeaders: ['Combo', 'Component', 'Colour', 'Qty.', 'Pcs. / Set'],
    colWidths: [200, 200, 200, 100, 100],
    allowInsertColumn: false,
    columns: [
        {type: 'text', wordWrap: true},
        {type: 'text', wordWrap: true},
        {type: 'text', wordWrap: true},
        {type: 'numeric', wordWrap: true},
        {type: 'dropdown', source: ArrPcsOrSet}
    ],
    onchange : divComboColorFirstTableChange
});

var GlbCCCArr = [], GlbDeliveryData = [], GlbDeliveryPoQty = [], GlbDeliveryPcsOrSet = [], CalcPoQty = [];
fnPoWiseQtyBrkUpSecondTblChange = function (obj, cell, val) {
    var divComboColorFirstTblData = $('#divComboColorFirstTable').jexcel('getData');
    for(var a = 0; a < divComboColorFirstTblData.length; a++) {
        GlbQtyForEachSet[divComboColorFirstTblData[a][0]+'#'+divComboColorFirstTblData[a][1]+'#'+divComboColorFirstTblData[a][2]] = divComboColorFirstTblData[a][3];
    }
    var cellName = $(obj).jexcel('getColumnNameFromId', $(cell).prop('id'));
    var GlbPoQtyTwo = [], PoWiseBrkUpTotal = 0, CalcPoQty = [];
    var PoWiseQtyBrkUpData = $('#PoWiseQtyBrkUpSecondTbl').jexcel('getData');
    GlbPonoId = 0; GlbPoQtyId = 0; GlbPcsOrSetId = 0; GlbPcsOrSet = []; GlbPoNos = []; GlbPoQty = []; GlbDeliveryData = []; GlbDeliveryPoQty = []; GlbDeliveryPcsOrSet = [];
    for(var i = 0; i < PoWiseQtyBrkUpData.length; i++) {
        var Combo       = jsTrim(PoWiseQtyBrkUpData[i][0]);
        var Componenet        = jsTrim(PoWiseQtyBrkUpData[i][1]);
        var Color        = jsTrim(PoWiseQtyBrkUpData[i][2]);
        var Pono        = jsTrim(PoWiseQtyBrkUpData[i][3]);
        var PoQty       = jsTrim(PoWiseQtyBrkUpData[i][4]);
        var PcsOrSet    = PoWiseQtyBrkUpData[i][5];
        PoWiseBrkUpTotal= PoWiseBrkUpTotal + parseInt(PoWiseQtyBrkUpData[i][4]);
        var PackType = PoWiseQtyBrkUpData[i][5];
        //pono push
        if (Pono == "" && GlbPonoId == 0) {
            var PonoId = jQuery.inArray(Pono, GlbPoNos);
            if(Pono!="") {
                GlbPoNos.push(Pono);
            } else if(PonoId<0) {
                GlbPoNos.push(Pono);
            }
        } else if(Pono != "") {
            Pono = jsTrim(PoWiseQtyBrkUpData[i][3]);
            if(Pono=="") {
                var PonoId = GlbPonoId;
            } else {
                var PonoId = jQuery.inArray(Pono, GlbPoNos);
                GlbPonoId = PonoId;
            }
            if(PonoId < 0) {
                GlbPoNos.push(Pono);
            }
            if(PonoId >= 0) {
                GlbDeliveryData[Pono] = GlbDeliveryData[Pono]+'|#|'+Combo;
            }
            else if (PonoId < 0) {
                GlbDeliveryData[Pono] = Combo;
            }
            GlbDeliveryPoQty[Pono+"|#|"+Combo]    = PoQty;
            GlbDeliveryPcsOrSet[Pono+"|#|"+Combo] = PackType;
        }
        //pono push
        //console.log(cellName.indexOf('E'),'cellName.indexOf()');
        if(cellName.indexOf('E') == 0) {
            CalcPoQty[Combo+"#"+Componenet+"#"+Color] += '-'+PoQty;
            console.log(CalcPoQty,'CalcPoQty in loop in E');
            var powiseqtys = CalcPoQty[Combo+"#"+Componenet+"#"+Color].split('-');
            console.log(powiseqtys,'powiseqtys');
            powiseqtys.shift();
            var totalpoqty = powiseqtys.reduce(function (a,b) { return Number(a) + Number(b) },0);
            console.log(totalpoqty,'totalpoqty'); console.log(typeof totalpoqty,'totalpoqty type');
            console.log(GlbQtyForEachSet,'GlbQtyForEachSet in loop in E');
            console.log(GlbQtyForEachSet[Combo+"#"+Componenet+"#"+Color],'GlbQtyForEachSet in E');
            var PoQtyLimit = GlbQtyForEachSet[Combo+"#"+Componenet+"#"+Color];
            console.log(typeof PoQtyLimit,'poqtylimit typeof');
            if(totalpoqty > PoQtyLimit) {
                $(cell).text('');
            }
        }
    }
    $("#PoQtySampleQtyTotal").text(PoWiseBrkUpTotal);
    $("#PoWiseQtyBrkUpPackType").text(PackType);
    //Split Component and color with / separate STARTS
    var secondTblData = $('#PoWiseQtyBrkUpSecondTbl').jexcel('getData');
    GlbCCCArr = [];
    for(var i = 0; i < secondTblData.length; i++) {
        GlbCCCArr.push(secondTblData[i][0]+"|#|"+secondTblData[i][1]+"|#|"+secondTblData[i][2]+"|#|"+secondTblData[i][3]+"|#|"+secondTblData[i][4]+"|#|"+secondTblData[i][5]);
    }
    //Split Component and color with / separate ENDS
}

function fnPoWiseQtyBrkUpSecondTbl() {
    var PoWiseQtyBrkUpData = [];
    //console.log($("table#PoWiseQtyBrkUpSecondTbl").length,'check len');
    if($("table#PoWiseQtyBrkUpSecondTbl").length) {
        var PoWiseQtyBrkUpData = $('#PoWiseQtyBrkUpSecondTbl').jexcel('getData');
    }
    else {
        var PoWiseQtyBrkUpData = [];
        //console.log('else');
    }
console.log(GlbAllColorArr,'GlbAllColorArr');
    //var PoWiseQtyBrkUpData = [];
    $("#PoWiseQtyBrkUpSecondTbl").jexcel({
        //onchange: fnPoWiseQtyBrkUpSecondTblChange,
        onchange: fnPoWiseQtyBrkUpSecondTblChange,
        data: PoWiseQtyBrkUpData,
        colHeaders: ['Combo', 'Component', 'Colour', 'P.O. No. /<br/>Enq. Ref. No.','P.O. Qty. / Sample Qty.','Pcs. / Set'],
        colWidths: [200, 200, 200, 200, 100, 100],
        columns: [
            {type: 'dropdown', source: GlbComboArr, wordWrap: true },
            {type: 'dropdown', source: GlbAllComponentArr, wordWrap: true},
            {type: 'dropdown', source: GlbAllColorArr, wordWrap: true, filter : colorDropdowncommon},
            {type: 'text', wordWrap: true},
            {type: 'numeric', wordWrap: true},
            {type: 'dropdown', source : ArrPcsOrSet}
        ],
    });
}
var IntakeQty = 0, PcsQty = 0, QtyType = 0, GlbthirdTblLength = 0, datas = [];
function fnItemizedQtyThirdTbl() {
    var itemizedQtyData = [];
    var itemizedQtyData = $("#PoWiseItemizedQtyThirdTbl").jexcel('getData');
    var secondTblData = $("#PoWiseQtyBrkUpSecondTbl").jexcel('getData');
    $("#PoWiseItemizedQtyThirdTbl").jexcel({
        colHeaders : ['Combo', 'Component', 'Colour','P.O. No. / Enq. Ref. No.','Itemized P.O. Qty. / Sample Qty.','Pcs. / Set','Intake Qty. (Nos.)','Itemized Qty. (Pcs.)'],
        colWidths : [200,200,200,200,100,100,100,100],
        columns : [
            {type: 'text', readOnly: true, wordWrap: true},
            {type: 'text', readOnly: true, wordWrap: true},
            {type: 'text', readOnly: true, wordWrap: true},
            {type: 'text', readOnly: true, wordWrap: true},
            {type: 'text', readOnly: true, wordWrap: true},
            {type: 'text', readOnly: true, wordwrap: true},
            {type: 'numeric', wordWrap: true},
            {type: 'numeric', wordWrap: true}
        ]
    });

    $("#PoWiseItemizedQtyThirdTbl").jexcel('updateSettings', {
        table: function (instance, cell, col, row, val, id) {
            //IntakeQty = 0;PcsQty = 0;
            GlbItemizedQty = 0;
            var cellid = $(cell).prop('id').split('-');
            if (col == 4) {PcsQty = jsTrim($(cell).text());}
            //if (col == 4) {QtyType = jsTrim($(cell).text());}
            if (col == 6) {IntakeQty = jsTrim($(cell).text());}
            if (col == 7) {
                var res = 0;
                res = parseInt(IntakeQty) * parseInt(PcsQty);
                $(cell).html(res);
            }
            var fnItemizedQtyData = $("#PoWiseItemizedQtyThirdTbl").jexcel('getData');
            var ItemizedQtyTotal = 0;
            for(var i = 0; i < fnItemizedQtyData.length; i++) {
                ItemizedQtyTotal += parseInt(jsTrim(fnItemizedQtyData[i][7]));
            }
            $("#ItemizedQtyTotal").text(ItemizedQtyTotal);
            if(col == 4) {
                //$(cell).html(GlbPoQty);
            }
            if(col == 5) {
                //console.log(GlbPcsSetFilter,'GlbPcsSetFilter');
                //$(cell).html(GlbPcsOrSet);
            }
        }
    });
    GlbthirdTblLength = $("#PoWiseItemizedQtyThirdTbl").jexcel('getData').length;
    GlbthirdTblLength = GlbthirdTblLength - 1;

    for(var i = 0; i < GlbCCCArr.length; i++) {
        var main = GlbCCCArr[i].split('|#|'); var com = main[1].split('/'); var col = main[2].split('/');
        for(var j = 0; j < com.length; j++) {
            if(com[j] && col[j]) {
                if(main[0] != "" && jsTrim(com[j]) != "" && jsTrim(col[j]) != "" && main[3] != "" && main[4] != "" && main[5] != "" && main[6] != "") {
                    $("#PoWiseItemizedQtyThirdTbl").jexcel('insertRow',[main[0],jsTrim(com[j]),jsTrim(col[j]),main[3],main[4],main[5],main[6]], GlbthirdTblLength);
                    GlbthirdTblLength++;
                }
            }
        }
    }
    var ThirdTblData = $("#PoWiseItemizedQtyThirdTbl").jexcel('getData'); var NewComboArr = []; var NewComp = [], NewUniquColor = [];
    for(var i = 0; i < ThirdTblData.length; i++) {
        var com = ThirdTblData[i][0]; var comp = ThirdTblData[i][1]; var col = ThirdTblData[i][2];
        if(com != "") {
            var NewComboId = jQuery.inArray(com,NewComboArr);
            if(NewComboId === -1) {
                NewComboArr.push(com);
            }
        }
        if(comp != "") {
            var NewCompId = jQuery.inArray(comp,NewComp);
            if(NewCompId === -1) {
                NewComp.push(comp);
            }
        }
        if(col != "") {
            var NewColId = jQuery.inArray(col,NewUniquColor);
            if(NewColId === -1) {
                NewUniquColor.push(col);
                var NewComboId = jQuery.inArray(com,NewComboArr); var NewCompId = jQuery.inArray(comp,NewComp);
                if(typeof NewColor[NewComboId+"-"+NewCompId] != "undefined") {
                    NewColor[NewComboId+"-"+NewCompId] = NewColor[NewComboId+"-"+NewCompId] + "|#|"+col;
                }
                else {
                    NewColor[NewComboId+"-"+NewCompId] = col;
                }
            }
            else {}
        }
    }
    if(typeof Storage !== "undefined") {
        localStorage.setItem('SpComponentArrLs',"");
        localStorage.setItem('SpComponentArrLs',JSON.stringify(NewComp));
        localStorage.setItem('NewUniquColorLs',""); localStorage.setItem('NewUniquColorLs',JSON.stringify(NewUniquColor));
    }

}
var testArr = [];
function fnPoWiseDeliverySchdFourthTbl() {
    var PoWiseQtyBkupDataInfo = [];
    var PoWiseQtyBkupDataInfo = $('#PoWiseDeliverySchdFourthTbl').jexcel('getData');
    var fnItemizedQtyData = $('#PoWiseQtyBrkUpSecondTbl').jexcel('getData');
    fnGetPONoList($('#PoWiseQtyBrkUpSecondTbl').jexcel('getData'), 3, 0, 1, 2, 4, 5);
    for (var i = 0; i < fnItemizedQtyData.length; i++) {
        ArrFnlPOWiseInfo[i] = fnItemizedQtyData[0];
    }
    var portData = JSON.parse(GlbPortdata);
    var portandcity = JSON.parse(GlbPortNameCity);

    $("#PoWiseDeliverySchdFourthTbl").jexcel({
        colHeaders: ['P.O. Date /<br/>Enq. Date', 'P.O. / Enq.<br/>Recd. Date', 'P.O. No. /<br/>Enq. Ref. No.', 'Combo /<br/>Colour', 'P.O. Qty. /<br/>Sample Qty.', 'Pcs. / Set','Mode of<br/>Shipment', 'Ship. Date /<br/>Subn. Date', 'Ex-Factory<br/>Date', 'Departure<br/>Date', 'Loading Port & City', 'Loading Country','Destination Port & City','Destination Country'],
        colWidths: [90, 90, 90, 100, 90, 50, 90,90, 90, 90, 90, 90, 90, 90],
        columns: [
            {type: 'calendar', options: {format: 'DD/MM/YYYY'}},
            {type: 'calendar', options: {format: 'DD/MM/YYYY'}},
            {type: 'text', wordWrap: true,readOnly:true},
            {type: 'text', wordWrap: true,readOnly:true},
            {type: 'text', wordWrap: true,readOnly:true},
            {type: 'text', wordWrap: true, readOnly:true},
            {type: 'dropdown', source: JSON.parse(GlbModeOfShipment)},
            {type: 'calendar', options: {format: 'DD/MM/YYYY'}},
            {type: 'calendar', options: {format: 'DD/MM/YYYY'}},
            {type: 'calendar', options: {format: 'DD/MM/YYYY'}},
            {type: 'dropdown', source: portandcity},
            {type: 'dropdown', source: portData.country},
            {type: 'dropdown', source: portandcity},
            {type: 'dropdown', source: portData.country}
        ],
        onchange : function (instance,cell,val) {

            var thirdTblData = $("#PoWiseItemizedQtyThirdTbl").jexcel('getData');
            var newArray = [];
            for(var i = 0; i < thirdTblData.length; i++) {
                newArray[i] = [thirdTblData[i][0],thirdTblData[i][1],thirdTblData[i][2],"",thirdTblData[i][3],"","","","","","","","","",thirdTblData[i][5],thirdTblData[i][6]]
            }
            $("#ItemizedSizeWiseQtyBrkUpFifthTbl").jexcel('setData',newArray);
        }
    });

}

function fnItemizedSizeWiseQtyBrkUpFifthTbl() {
    var ItemizedDataInfo = [];
    var ItemizedDataInfo = $('#ItemizedSizeWiseQtyBrkUpFifthTbl').jexcel('getData');
    var ColHeaders      = "";
    var ArrSizeChartHeader = GlbSelSizeChartText.split(",");
    var ArrColHeaderFinal   = ['Combo', 'Component', 'Colour', 'Size Spec<br/>Code/Fit', 'P.O. No. /<br/>Enq. Ref. No.'];
    var ArrReadOnlyInfo     =  new Array();
    for(var i=0;i<8;i++) {
        if(typeof (ArrSizeChartHeader[i])!="undefined") {
            ColHeaders = ColHeaders+ArrSizeChartHeader[i]+",";
            ArrColHeaderFinal.push(ArrSizeChartHeader[i]);
            ArrReadOnlyInfo[i] = false;
        } else {
            ColHeaders = ColHeaders+",";
            ArrColHeaderFinal.push('No Size');
            ArrReadOnlyInfo[i] = true;
        }
    }
    GlbReadOnlyInfo         = ArrReadOnlyInfo;
    ArrColHeaderFinal.push('Itemized P.O. Qty. / Sample Qty.'); ArrColHeaderFinal.push('Pcs. / Set'); ArrColHeaderFinal.push('Intake Qty.<br/>(Nos.)');
    ArrColHeaderFinal.push('Itemized Qty.<br/>(Pcs.)');
    $("#ItemizedSizeWiseQtyBrkUpFifthTbl").jexcel({
        onchange: function (obj, itemcell, itemizedEntryData) {
            var cellName = $(obj).jexcel('getColumnNameFromId', $(itemcell).prop('id'));
            if (cellName.indexOf('D') == 0) {
                //console.log(itemizedEntryData,'ss val');
                GlbSizeSpec.push(itemizedEntryData);
                //console.log(GlbSizeSpec,'GlbSizeSpecarr');
            }
            var data = $('#ItemizedSizeWiseQtyBrkUpFifthTbl').jexcel('getData');
            var GlbItemXSSumVal = [], GlbItemizedQtyTotal = [];
            var GlbItemSSumVal = [];
            var GlbItemMSumVal = [];
            var GlbItemLSumVal = [];
            var GlbItemXLSumVal = [];
            var GlbItemXXLSumVal = [];
            var GlbItem3XLSumVal = [];
            var GlbItem4XLSumVal = [];
            var GlbItemPoQtySumVal = [];
            GlbSizeSpecFitArr = []; GlbAllPOList=[];
            //GlbAllSizeCodeFit=[];
            var ComboNewName = '';
            var datalength = data.length;
            var GlbItemCodeVal  = [];
            for (var i = 0; i < datalength; i++) {
                if (data[i][1] !== '' && data[i][2] !== '' && data[i][3] !== '') {
                    ComboNewName                    = data[i][0]+"#"+data[i][1]+"#"+data[i][2]+"#"+data[i][3];
                    //groupName                       = data[i][0]+"#"+data[i][1]+"#"+data[i][2]+"#"+data[i][3];
                    //var groupNameId                 = jQuery.inArray(ComboNewName, groupName);
                    var ComboSizeCodeId             = jQuery.inArray(ComboNewName, GlbItemCodeVal);
                    if(ComboSizeCodeId === -1) {
                        GlbItemCodeVal.push(ComboNewName);
                    }
/*
                    if(ComboSizeCodeId >= 0) {
                        GlbGroupName.push(ComboNewName);
                    }
*/
                }
                if (ComboNewName !== '') {
                    GlbItemXSSumVal = fnPopulateValueArray(GlbItemXSSumVal, ComboNewName, data[i][5]);
                    //GlbPoNOVal = fnPopulatePOValueArray(GlbPoNOVal, ComboNewName, data[i][4]);
                    //console.log(GlbItemXSSumVal,'GlbItemXSSumVal after');
                    //console.log(GlbPoNOVal,'GlbPoNOVal after');
                    GlbItemSSumVal = fnPopulateValueArray(GlbItemSSumVal, ComboNewName, data[i][6]);
                    GlbItemMSumVal = fnPopulateValueArray(GlbItemMSumVal, ComboNewName, data[i][7]);
                    GlbItemLSumVal = fnPopulateValueArray(GlbItemLSumVal, ComboNewName, data[i][8]);
                    GlbItemXLSumVal = fnPopulateValueArray(GlbItemXLSumVal, ComboNewName, data[i][9]);
                    GlbItemXXLSumVal = fnPopulateValueArray(GlbItemXXLSumVal, ComboNewName, data[i][10]);
                    GlbItem3XLSumVal = fnPopulateValueArray(GlbItem3XLSumVal, ComboNewName, data[i][11]);
                    GlbItem4XLSumVal = fnPopulateValueArray(GlbItem4XLSumVal, ComboNewName, data[i][12]);
                    GlbItemPoQtySumVal = fnPopulateValueArray(GlbItemPoQtySumVal, ComboNewName, data[i][13]);
                    GlbItemizedQtyTotal = fnPopulateValueArray(GlbItemizedQtyTotal, ComboNewName, data[i][16]);
                }
                if (jsTrim(data[i][3]) != "" && jsTrim(data[i][4]) != "") {
                    var SizeCodeId             = jQuery.inArray(jsTrim(data[i][3]), GlbAllSizeCodeFit);
                    if(SizeCodeId=="-1") {
                        GlbAllSizeCodeFit.push(jsTrim(data[i][3]));
                    }


                    if (typeof (GlbSizeSpecFitArr[jsTrim(data[i][3])]) != "undefined") {


                        GlbSizeSpecFitArr[jsTrim(data[i][3])] = GlbSizeSpecFitArr[jsTrim(data[i][3])] + "|#|" + jsTrim(data[i][4]);
                    } else {
                        GlbSizeSpecFitArr[jsTrim(data[i][3])] = jsTrim(data[i][4]);
                    }
                }
                if(jsTrim(data[i][4]) != "") {
                    GlbAllPOList.push(jsTrim(data[i][4]));
                }
                //$("#SixthATbl").jexcel("insertRow", [ data[i][0],data[i][1],data[i][2],data[i][3],GlbSizePoNoSpecFitArr[data[i][0]+"#"+data[i][1]+"#"+data[i][2]+"#"+data[i][3]] ],i);
            }
            var SumItemizedVal = '';
            for (var i = 0; i < GlbItemCodeVal.length; i++) {
                var KeyVal                          = GlbItemCodeVal[i];
                console.log(GlbItemXSSumVal[KeyVal],'fabric test');
                SumItemizedVal = SumItemizedVal + "<div class='cutratiocumdiv'>Cumulative Quantity Per Size (" + KeyVal + "):</div>" +
                    "<div id='itemizedCummulative' class='pd0'>" +
                    "<div class='cutratiocumeachdiv' id='xxs_"+i+"'>" + fnSumSizeArrayValue(GlbItemXSSumVal[KeyVal]) + "</div>" + "" +
                    "<div class='cutratiocumeachdiv' id='xs_"+i+"'>" + fnSumSizeArrayValue(GlbItemSSumVal[KeyVal]) + "</div>" +
                    "<div class='cutratiocumeachdiv' id='s_"+i+"'>" + fnSumSizeArrayValue(GlbItemMSumVal[KeyVal]) + "</div>" + "" +
                    "<div class='cutratiocumeachdiv' id='m_"+i+"'>" + fnSumSizeArrayValue(GlbItemLSumVal[KeyVal]) + "</div>" +
                    "<div class='cutratiocumeachdiv' id='l_"+i+"'>" + fnSumSizeArrayValue(GlbItemXLSumVal[KeyVal]) + "</div>" + "" +
                    "<div class='cutratiocumeachdiv' id='xl_"+i+"'>" + fnSumSizeArrayValue(GlbItemXXLSumVal[KeyVal]) + "</div>" +
                    "<div class='cutratiocumeachdiv' id='xxl_"+i+"'>" + fnSumSizeArrayValue(GlbItem3XLSumVal[KeyVal]) + "</div>" + "" +
                    "<div class='cutratiocumeachdiv' id='2xl_"+i+"'>" + fnSumSizeArrayValue(GlbItem4XLSumVal[KeyVal]) + "</div></div>";
                var TotalVal        = fnSumSizeArrayValue(GlbItemXSSumVal[KeyVal])+fnSumSizeArrayValue(GlbItemSSumVal[KeyVal])+fnSumSizeArrayValue(GlbItemMSumVal[KeyVal])+fnSumSizeArrayValue(GlbItemLSumVal[KeyVal])+fnSumSizeArrayValue(GlbItemXLSumVal[KeyVal])+fnSumSizeArrayValue(GlbItemXXLSumVal[KeyVal])+fnSumSizeArrayValue(GlbItem3XLSumVal[KeyVal])+fnSumSizeArrayValue(GlbItem4XLSumVal[KeyVal]);
                SumItemizedVal = SumItemizedVal + "<div id='itemized_poqtyTotalbu_"+i+"' class='cutratiocumpodiv'>" + TotalVal + "</div><div class='cutratiocumpodiv' style='width: 69px'>&nbsp;</div><div class='cutratiocumpodiv' style='width: 72px'>&nbsp;</div><div class='cutratiocumpodiv' style='width: 99px' id='ItemizedQtyCummulative'>120000</div>"+"<div class='clearfix' class='pd0'></div>";
            }
            if ($("#ItemizedBkUp")) {
                $("#ItemizedBkUp").html('');
            }
            $("#ItemizedSizeWiseQtyBrkUpFifthTbl").after('<div id="ItemizedBkUp" class="pd0">' + SumItemizedVal + '</div>');
            /*for(var i = 0; i < datalength.length; i++) {
                $("#SixthATbl").jexcel("insertRow", [ data[i][0],data[i][1],data[i][2],data[i][3],GlbSizePoNoSpecFitArr[data[i][0]+"#"+data[i][1]+"#"+data[i][2]+"#"+data[i][3]] ],i);
            }*/
        },
        colHeaders: ArrColHeaderFinal,
        colWidths: [110, 110, 110, 90, 110, 45, 45, 45, 45, 45, 45, 45, 45, 100, 70, 70, 100 ],
        columns: [
            {type: 'text', wordWrap: true, readOnly: true},
            {type: 'text', wordWrap: true, readOnly: true},
            {type: 'text', wordWrap: true, readOnly: true},
            {type: 'text', wordWrap: true},
            {type: 'text', wordWrap: true, readOnly: true},
            {type: 'numeric', wordWrap: true,readOnly:ArrReadOnlyInfo[0]},
            {type: 'numeric', wordWrap: true,readOnly:ArrReadOnlyInfo[1]},
            {type: 'numeric', wordWrap: true,readOnly:ArrReadOnlyInfo[2]},
            {type: 'numeric', wordWrap: true,readOnly:ArrReadOnlyInfo[3]},
            {type: 'numeric', wordWrap: true,readOnly:ArrReadOnlyInfo[4]},
            {type: 'numeric', wordWrap: true,readOnly:ArrReadOnlyInfo[5]},
            {type: 'numeric', wordWrap: true,readOnly:ArrReadOnlyInfo[6]},
            {type: 'numeric', wordWrap: true,readOnly:ArrReadOnlyInfo[7]},
            {type: 'text', wordWrap: true},
            {type: 'text', wordWrap: true, readOnly: true},
            {type: 'text', wordWrap: true, readOnly: true},
            {type: 'text', wordWrap: true}
        ],
    });
    var colsVal = 0; var ItemizedPoQty = 0,IntakeQty = 0,total = 0;
    $("#ItemizedSizeWiseQtyBrkUpFifthTbl").jexcel('updateSettings', {
        table: function (instance, cell, col, row, val, id) {
            if (col == 0) {
                colsVal = 0;
            }
            if (col == 5 || col == 6 || col == 7 || col == 8 || col == 9 || col == 10 || col == 11 || col == 12) {
                if (jsTrim($(cell).text()) != "") {
                    colsVal = colsVal + parseInt($(cell).text());
                }
                $(cell).css('text-align', 'right');
            }
            if (col == 13) {
                $(cell).css('text-align', 'right');
                $(cell).html(colsVal);
                //fnPOWiseQtyBreakUp();
                fnCuttingRatioSixthTbl();
            }
            if(col == 13) {
                $(cell).css('text-align', 'right');
                ItemizedPoQty = parseInt($(cell).text());
            }
            if(col == 15) {
                IntakeQty = parseInt($(cell).text());
                total = ItemizedPoQty * IntakeQty;
                //console.log(total,'in16');
            }
            if(col == 16) {
                total = ItemizedPoQty * IntakeQty;
                //console.log(total,'tot');
                $(cell).html(total);
            }
        }
    });
}

function fnSixthATbl() {
    var ColHeaders      = "";
    var ArrSizeChartHeader = GlbSelSizeChartText.split(",");
    var ArrColHeaderFinal   = ['Combo', 'Component', 'Colour', 'Size Spec<br/>Code/Fit', 'P.O. No. /<br/>Enq. Ref. No.'];
    var ArrReadOnlyInfo     =  new Array();
    for(var i=0;i<8;i++) {
        if(typeof (ArrSizeChartHeader[i])!="undefined") {
            ColHeaders = ColHeaders+ArrSizeChartHeader[i]+",";
            ArrColHeaderFinal.push(ArrSizeChartHeader[i]);
            ArrReadOnlyInfo[i] = false;
        } else {
            ColHeaders = ColHeaders+",";
            ArrColHeaderFinal.push('No Size');
            ArrReadOnlyInfo[i] = true;
        }
    }
    GlbReadOnlyInfo         = ArrReadOnlyInfo;
    ArrColHeaderFinal.push('Itemized P.O. Qty. / Sample Qty.'); ArrColHeaderFinal.push('Pcs. / Set','Intake Qty. (Nos.)','Itemized Qty. (Pcs.)');
    $("#SixthATbl").jexcel({
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
        ]
    });
}

//var GlbsumofsizecRatio = 0;
function fnCuttingRatioSixthTbl() {
    var CuttingRatioDataInfo = [];
    if($("table#CuttingRatioSixthTbl").length) {
        CuttingRatioDataInfo = $('#CuttingRatioSixthTbl').jexcel('getData');
    }
    //Column Headers
    var ColHeaders      = "";
    var ArrSizeChartHeader = GlbSelSizeChartText.split(",");
    //console.log(GlbSelSizeChartText,'GlbSelSizeChartText');
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
    ArrColHeaderFinal.push('No of Pcs.<br/>Per Ratio'); ArrColHeaderFinal.push('Itemized Qty. <br/>(Pcs.)'); ArrColHeaderFinal.push('Total No<br/>of Ratios');

    $("#CuttingRatioSixthTbl").jexcel({
        onchange: function (obj, itemcell, itemizedEntryData) {
            /*var cellName = $(obj).jexcel('getColumnNameFromId',$(itemcell).prop('id'));
            console.log(cellName,'cellName');
            if(cellName.indexOf('F') == 0 || cellName.indexOf('G') == 0 || cellName.indexOf('H') == 0) {
                console.log(itemizedEntryData,'val');
                GlbsumofsizecRatio += itemizedEntryData;
            }*/

            var data = $('#CuttingRatioSixthTbl').jexcel('getData');
            GlbCuttingRatioData = data;
            var GlbItemXSSumVal = [];
            var GlbItemSSumVal = [];
            var GlbItemMSumVal = [];
            var GlbItemLSumVal = [];
            var GlbItemXLSumVal = [];
            var GlbItemXXLSumVal = [];
            var GlbItem3XLSumVal = [];
            var GlbItem4XLSumVal = [];
            var GlbItemPoQtySumVal = [];
            var GlbItemPoQtyVal = [];
            var ComboNewName = '';
            var GlbItemCodeVal = [];
            var datalength = data.length;
            for (var i = 0; i < datalength; i++) {
                if (data[i][1] !== '' && data[i][2] !== '' && data[i][3] !== '') {
                    ComboNewName                    = data[i][1]+"#"+data[i][2]+"#"+data[i][3];
                    var ComboSizeCodeId             = jQuery.inArray(ComboNewName, GlbItemCodeVal);
                    if(ComboSizeCodeId=="-1") {
                        GlbItemCodeVal.push(ComboNewName);
                    }
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
                    var SumAllVal=0;
                    for(var jk=5;jk<=12;jk++) {
                        if(jsTrim(data[i][jk])!="") {
                            SumAllVal =  SumAllVal+parseInt(jsTrim(data[i][jk]));
                        }
                    }
                    if(jsTrim(data[i][14])!="" && SumAllVal>=1) {
                        GlbItemPoQtyVal = fnPopulateValueArray(GlbItemPoQtyVal, ComboNewName, parseInt(data[i][14])*SumAllVal);
                    }
                }
            }
        },
        colHeaders: ArrColHeaderFinal,
        allowInsertColumn: false,
        colWidths: [110, 110, 110, 140, 100, 45, 45, 45, 45, 45, 45, 45, 45, 100, 110, 90],
        columns: [
            {type: 'text', wordWrap: true, readOnly:true},
            {type: 'text', wordWrap: true, readOnly:true},
            {type: 'text', wordWrap: true, readOnly:true},
            {type: 'text', wordWrap: true, readOnly:true},
            {type: 'text', wordWrap: true, readOnly:true},
            {type: 'numeric', wordWrap: true,readOnly:GlbReadOnlyInfo[0]},
            {type: 'numeric', wordWrap: true,readOnly:GlbReadOnlyInfo[1]},
            {type: 'numeric', wordWrap: true,readOnly:GlbReadOnlyInfo[2]},
            {type: 'numeric', wordWrap: true,readOnly:GlbReadOnlyInfo[3]},
            {type: 'numeric', wordWrap: true,readOnly:GlbReadOnlyInfo[4]},
            {type: 'numeric', wordWrap: true,readOnly:GlbReadOnlyInfo[5]},
            {type: 'numeric', wordWrap: true,readOnly:GlbReadOnlyInfo[6]},
            {type: 'numeric', wordWrap: true,readOnly:GlbReadOnlyInfo[7]},
            {type: 'numeric', wordWrap: true,readOnly:true},
            {type: 'numeric', wordWrap: true,readOnly:true},
            {type: 'text', wordWrap: true}
        ]

    });

    var colsVal = 0;var colsItemPOSumVal = 0; var tnoofratio = 0; var ftItemizedQty = 0;
    $("#CuttingRatioSixthTbl").jexcel('updateSettings', {
        table: function (instance, cell, col, row, val, id) {
            if (col == 0) {
                colsVal = 0;colsItemPOSumVal=0; tnoofratio = 0;
            }
            if (col == 5 || col == 6 || col == 7 || col == 8 || col == 9 || col == 10 || col == 11 || col == 12 || col == 13 || col == 14 || col == 15) {
                //console.log(colsVal,'colsVal in sizes');
                if (jsTrim($(cell).text()) != "" && (col == 5 || col == 6 || col == 7 || col == 8 || col == 9 || col == 10 || col == 11 || col == 12)) {
                    colsVal = colsVal + parseInt($(cell).text());
                }
                $(cell).css('text-align', 'right');
            }
            if(col == 13) {
                //console.log(colsVal,'colsVal');
                $(cell).html(colsVal);
            }
            if(col == 14) {
                ftItemizedQty = parseInt($(cell).text());
                //console.log(ftItemizedQty,'ftItemizedQty');
            }
            if(col == 15) {
                //console.log(ftItemizedQty,'ftItemizedQty in 15');
                //console.log(colsVal,'colsVal 15');
                tnoofratio = parseInt(ftItemizedQty) / colsVal;
                //console.log(tnoofratio);
                $(cell).html(tnoofratio);
            }

        }
    });

}

function fnPopulateValueArray(ArrName, KeyValue, InsertVal) {
    if (jQuery.inArray(KeyValue, ArrName)) {
        ArrName[KeyValue] = InsertVal + "-" + ArrName[KeyValue];
    }
    return ArrName;
}

function fnSumSizeArrayValue(ArrSizeVal) {
    if (ArrSizeVal != "" && typeof ArrSizeVal != "undefined") {
        var SumVal = 0;
        var ArrName = ArrSizeVal.split("-");
        for (var i = 0; i < ArrName.length; i++) {
            if (isNumber(ArrName[i])) {
                SumVal = parseInt(ArrName[i]) + SumVal;
            }
        }
        return SumVal;
    }
    else {
        return 0;
    }
}

function fnGetPONoList(ObjTableInfo, VarColsName, VarComboName, VarComponent, VarColor, VarQtyCols, VarPcsOrSet) {
    GlbItemPoNoList = [];
    GlbItemPoNoQtyList = [];
    for (var i = 0; i < ObjTableInfo.length; i++) {
        //console.log(ObjTableInfo[i][VarColsName],'varcolsnameinloop');
        if (jsTrim(ObjTableInfo[i][VarColsName]) != "") {
            if(jsTrim(ObjTableInfo[i][VarComboName]) == "") {
                //console.log(ObjTableInfo[i][VarColor],'colorinloop');
                ObjTableInfo[i][VarComboName] = ObjTableInfo[i][VarColor];
            }
            GlbItemPoNoList.push(ObjTableInfo[i][VarColsName] + "#" + ObjTableInfo[i][VarComboName] + "#" + ObjTableInfo[i][VarComponent] + "#" + ObjTableInfo[i][VarColor]+"#"+ObjTableInfo[i][VarQtyCols]+"#"+ObjTableInfo[i][VarPcsOrSet]);
            GlbItemPoNoQtyList.push();
            GlbItemPoNoQtyList[ObjTableInfo[i][VarColsName] + "#" + ObjTableInfo[i][VarComboName]] = ObjTableInfo[i][VarQtyCols];
        }
    }
}

function fnTeamDetails(thisobj) {
    GlbTeamId = thisobj;
    var Parameters = GlbParam + "&id=" + thisobj.value + "&type=2";
    MakePostRequest(base_path + GlbCompanyFdr + 'orderentry/entry', Parameters, 'json', fnTeamRes);
}

function fnTeamRes(data) {
    if (data != '') {
        if (data.errcode != undefined) {
            if (data.errcode == '404') {
                fnCallSessionExpire();
                return false;
            } else {
                $("#teamcode").text(data.ArrTeamById.code);
                $("#mobileNo").text(data.ArrTeamById.phoneno);
                $("#emailId").text(data.ArrTeamById.emailid);
            }
        }
    }
}

var GlbComponentArrTwo = [];
var GlbColorArrTwo = [];
var compDropdownTwo = function (instance, cell, c, r, source) {
    var ObjId = $(instance).prop('id');
    var comboName = $("#" + ObjId).jexcel('getValue', '1-' + r);
    var ComboId = jQuery.inArray(comboName, GlbComboArr);
    return GlbComponentArrTwo[ComboId].split("|#|");
}

var colorDropdownTwo = function (instance, cell, c, r, source) {
    var ObjId = $(instance).prop('id');
    var comboName = $("#" + ObjId).jexcel('getValue', '1-' + r);
    var compName = $("#" + ObjId).jexcel('getValue', '2-' + r);
    var ComboId = jQuery.inArray(comboName, GlbComboArr);
    var CompId = jQuery.inArray(compName, GlbAllComponentArr);
    return GlbColorArrTwo[ComboId + "-" + CompId].split("|#|");
}

/*Table Functions */
var getGarmentparts = [], GlbFabricDetails = [], AllColorJoined = [];
function fnCreateFabricKnitTable() {
    var FabricKnitDataInfo = []; var Colors = [];
    if (jsTrim($('#divFabricKnitTable').html()) != '') {
        //console.log('if knit html');
        var FabricKnitDataInfo = $('#divFabricKnitTable').jexcel('getData');
        $('#divFabricKnitTable').html('');
    }
    if(JSON.parse(localStorage.getItem('SpComponentArrLs'))) {
        var SpComponentArrLs = JSON.parse(localStorage.getItem('SpComponentArrLs'));
    }
    else {
        var GlbUniqueSplitComponent = [];
        var SplitComponentFromThirdTbl = $("#PoWiseItemizedQtyThirdTbl").jexcel('getColumnData',1);
        jQuery.each(SplitComponentFromThirdTbl,function (i,el) {
            if(jQuery.inArray(el,GlbUniqueSplitComponent) === -1) if(el) GlbUniqueSplitComponent.push(el);
        });
        var SpComponentArrLs = GlbUniqueSplitComponent;
    }

        //console.log(JSON.parse(localStorage.getItem('NewUniquColorLs')),'NewUniquColorLs localsto');

        var SplitColorFromThirdTbl = $("#PoWiseItemizedQtyThirdTbl").jexcel('getColumnData',2);
    //console.log(SplitColorFromThirdTbl,'SplitColorFromThirdTbl');
        jQuery.each(SplitColorFromThirdTbl,function (i,el) {
            if(jQuery.inArray(el,Colors) === -1) if(el) Colors.push(el);
        });
    //console.log(Colors,'Colors');
        //var NewUniquColorLs = GlbUniqueSplitColors;

        var SecondLevelColor = [], TrimmedSecondLevelColor = [];
        for(var i = 0; i < Colors.length; i++) {
            var splitcolor = Colors[i].split(';');
            if(splitcolor.length >= 2) SecondLevelColor.push(splitcolor);
            else SecondLevelColor.push(Colors[i]);
        }
        var rslt = [].concat.apply([],SecondLevelColor);
        for(var i = 0; i < rslt.length; i++) TrimmedSecondLevelColor.push(jsTrim(rslt[i]));
        //

    $("#divFabricKnitTable").jexcel({
        colHeaders: ['Combo', 'Component', 'Colour', 'Garment<br/>Parts', 'Fabric Blend (%)','Fabric Content','Fabric Name', 'Finishing<br/>GSM', 'Yarn Special<br/>Request', 'Fabric Finish', 'Fabric Finish<br/>Stage / Form'],
        colWidths: [105, 105, 100, 120, 123,123,123, 100, 100, 110, 120],
        allowInsertColumn: false,
        columns: [
            {type: 'dropdown', source: GlbComboArr},
            {type: 'dropdown', source: SpComponentArrLs},
            {type: 'dropdown', source: TrimmedSecondLevelColor},
            {type: 'dropdown', source: JSON.parse(GlbGpdata)},
            {type: 'dropdown', source: JSON.parse(GlbKnitFabricBlend)},
            {type: 'dropdown', source: JSON.parse(GlbKnitFabricContent)},
            {type: 'dropdown', source: JSON.parse(GlbKnitFabricName)},
            {type: 'text', wordWrap: true},
            {type: 'dropdown', source: JSON.parse(GlbYarnReqData) },
            {type: 'dropdown', source: JSON.parse(GlbFabricFinish)},
            {type: 'dropdown', source: JSON.parse(GlbFabricFFStageForm)},
        ],
        onchange: function (instance, cell, value) {
            var cellid = $(cell).prop('id');
            if (cellid.indexOf('3') == 0) {
                getGarmentparts.push(value);
            }
            if (cellid.indexOf('4') == 0) {
                GlbFabricDetails.push(value);
            }
        }
    });
}

function fnCreateFabricWovenTable() {
    if (jsTrim($('#divFabricWoven').html()) != '') {
        var FabricWovenDataInfo = $('#divFabricWoven').jexcel('getData');
        $('#divFabricWoven').html('');
    }
    var SpComponentArrLs = JSON.parse(localStorage.getItem('SpComponentArrLs'));
    var NewUniquColorLs  = JSON.parse(localStorage.getItem('NewUniquColorLs'));
    $("#divFabricWoven").jexcel({
        colHeaders: ['Combo', 'Component', 'Colour', 'Garment<br/>parts', 'Fabric Blend (%)','Fabric Content','Fabric Name', 'Sort No', 'Product No.', 'Weight', 'Unit of Measure', 'Yarn Count', 'Fabric Construction', 'Width (Inches)',  'Fabric Finish','Fabric Finish<br/>Stage / Form'],
        colWidths: [90, 90, 80, 100, 86,86,86, 60, 60, 60, 60, 60, 90, 60, 80, 80],
        allowInsertColumn: false,
        columns: [
            {type: 'dropdown', source: GlbComboArr},
            {type: 'dropdown', source: SpComponentArrLs},
            {type: 'dropdown', source: NewUniquColorLs, wordWrap: true, filter: FilterSplittedColors},
            {type: 'dropdown', source: JSON.parse(GlbGpdata)},
            {type: 'dropdown', source: JSON.parse(GlbKnitFabricBlend)},
            {type: 'dropdown', source: JSON.parse(GlbKnitFabricContent)},
            {type: 'dropdown', source: JSON.parse(GlbKnitFabricName)},
            {type: 'text', wordWrap: true},
            {type: 'text', wordWrap: true},
            {type: 'text', wordWrap: true},
            {type: 'dropdown', source: unitMeasure},
            {type: 'dropdown', source: JSON.parse(GlbYarnCount) },
            {type: 'text', wordWrap: true},
            {type: 'text', wordWrap: true},
            {type: 'dropdown', source : JSON.parse(GlbFabricFinish) },
            {type: 'dropdown', source : JSON.parse(GlbFabricFFStageForm) }
        ],
        onchange: function (instance, cell, value) {
            var cellid = $(cell).prop('id');
            if (cellid.indexOf('4') == 0) {
                GlbFabricDetails.push(value);
            }
        }
    });
}


function fnCreateDyingDetailsTable() {
    var dyingDetailsDataInfo = [];
    if (jsTrim($('#divDyingDetails').html()) != '') {
        var dyingDetailsDataInfo = $('#divDyingDetails').jexcel('getData');
        $('#divDyingDetails').html('');
    }
    if($("#PoWiseItemizedQtyThirdTbl").jexcel('getData').length) {
        GlbUniqueSplitColors = []; GlbUniqueSplitComponent = [];
        var SplitComponentFromThirdTbl = $("#PoWiseItemizedQtyThirdTbl").jexcel('getColumnData',1);
        var SplitColorFromThirdTbl = $("#PoWiseItemizedQtyThirdTbl").jexcel('getColumnData',2);

        jQuery.each(SplitColorFromThirdTbl,function (i,el) {
            if(jQuery.inArray(el,GlbUniqueSplitColors) === -1) if(el) GlbUniqueSplitColors.push(el);
        });
        var SecondLevelColor = [], TrimmedSecondLevelColor = [];
        for(var i = 0; i < GlbUniqueSplitColors.length; i++) {
            var splicolor = GlbUniqueSplitColors[i].split(';');
            if(splicolor.length >= 2) SecondLevelColor.push(splicolor);
                else SecondLevelColor.push(GlbUniqueSplitColors[i]);
        }
        var rslt = [].concat.apply([],SecondLevelColor);
        for(var i = 0; i < rslt.length; i++) TrimmedSecondLevelColor.push(jsTrim(rslt[i]));
        jQuery.each(SplitComponentFromThirdTbl,function (i,el) {
            if(jQuery.inArray(el,GlbUniqueSplitComponent) === -1) if(el) GlbUniqueSplitComponent.push(el);
        });
    }
    $("#divDyingDetails").jexcel({
        data: dyingDetailsDataInfo,
        colHeaders: ['Combo', 'Component', 'Colour', 'Dying<br/>Type','Garment<br/>Parts', 'Fabric Blend (%)','Fabric Content','Fabric Name', 'Pantone / Swatch<br/>Ref. Details', 'Dyeing Special<br/>Request', 'Color<br/>Mat. Std.'],
        colWidths: [100, 100, 100, 100, 120, 120,120,120, 130, 120, 100],
        allowInsertColumn: false,
        columns: [
            {type: 'dropdown', source: GlbComboArr},
            {type: 'dropdown', source: GlbUniqueSplitComponent},
            {type: 'dropdown', source: TrimmedSecondLevelColor, wordWrap: true},
            {type: 'dropdown', source: GlbDyeingType},
            {type: 'dropdown', source: JSON.parse(GlbGpdata) },
            {type: 'dropdown', source: JSON.parse(GlbKnitFabricBlend)},
            {type: 'dropdown', source: JSON.parse(GlbKnitFabricContent)},
            {type: 'dropdown', source: JSON.parse(GlbKnitFabricName)},
            {type: 'text', wordWrap: true},
            {type: 'dropdown', source: JSON.parse(GlbDsr)},
            {type: 'dropdown', source: JSON.parse(GlbColormatchstd) },
        ]
    });
    $('#dyingDetailRemarks').jexcel({
        colHeaders: ['Dying Remarks'],
        colWidths: [1000],
        columns: [{type: 'text'},]
    });
}

var spectrumEditor = {
    // Methods
    closeEditor : function(cell, save) {
        // Get value
        var value = $(cell).find('.editor').spectrum('get').toHexString();

        // Set visual value
        $(cell).html(value);
        $(cell).css('color', value);

        // Close edition
        $(cell).removeClass('edition');

        // Save history
        return value;
    },
    openEditor : function(cell) {
        // Get current content
        var html = $(cell).html();

        // Create the editor
        var editor = document.createElement('div');
        $(cell).html(editor);
        $(editor).prop('class', 'editor');

        // Create the instance of the plugin
        $(editor).spectrum({ color:html, preferredFormat:'hex', hide: function(color) {
                // Close editor through jexcel
                $('#' + $.fn.jexcel.current).jexcel('closeEditor', $(cell), true);
            }});

        // Run
        $(editor).spectrum('show');
    },
    getValue : function(cell) {
        return $(cell).html();
    },
    setValue : function(cell, value) {
        $(cell).html(value);
        $(cell).css('color', value);

        return true;
    }
}

function fnCreateEmbell() {
    if (jsTrim($('#divEmbellishment').html()) != '') {
        var embelDataInfo = $('#divEmbellishment').jexcel('getData');
        $('#divEmbellishment').html('');
    }
    var embell = JSON.parse(GlbEmbell);
    if($("#PoWiseItemizedQtyThirdTbl").jexcel('getData').length) {
        GlbUniqueSplitColors = []; GlbUniqueSplitComponent = [];
        var SplitComponentFromThirdTbl = $("#PoWiseItemizedQtyThirdTbl").jexcel('getColumnData',1);
        var SplitColorFromThirdTbl = $("#PoWiseItemizedQtyThirdTbl").jexcel('getColumnData',2);
        jQuery.each(SplitColorFromThirdTbl,function (i,el) {
            if(jQuery.inArray(el,GlbUniqueSplitColors) === -1) if(el) GlbUniqueSplitColors.push(el);
        });
        jQuery.each(SplitComponentFromThirdTbl,function (i,el) {
            if(jQuery.inArray(el,GlbUniqueSplitComponent) === -1) if(el) GlbUniqueSplitComponent.push(el);
        });
    }
    $("#divEmbellishment").jexcel({
        colHeaders: ['Combo', 'Component', 'Colour', 'Artwork<br/>Code', 'Option', 'Embellishment<br/>Type', 'Medium / Material', 'Size / Dim.', 'Unit of<br/>Measure', 'Item<br/>Code', 'Item Colour<br/>Code', 'Shade'],
        colWidths: [110, 110, 110, 120, 100, 100, 100, 100, 100, 100, 100, 80],
        allowInsertColumn: false,
        columns: [
            {type: 'dropdown', source: GlbComboArr},
            {type: 'dropdown', source: GlbUniqueSplitComponent},
            {type: 'dropdown', source: GlbUniqueSplitColors},
            {type: 'text', wordWrap: true},
            {type: 'text', wordWrap: true},
            {type: 'dropdown', source: embell.embellname},
            {type: 'dropdown', source: embell.medium},
            {type: 'text', wordWrap: true},
            {type: 'dropdown', source: unitMeasure},
            {type: 'text', wordWrap: true},
            {type: 'text', wordWrap: true},
            {type: 'text', wordWrap: true, editor:spectrumEditor}

        ]
    });
    $('#fabricEmbellRemarks').jexcel({
        colHeaders: ['Embell Remarks'],
        colWidths: [1000],
        columns: [{type: 'text'},]
    });
};

var GlbBomQty = 0;var GlbBomIntake = 0;var GlbBomSubtotal = 0;var GlbCellnamenumber = '';var Glbsubtot = 0;
var GlbItemDesc = [];var GlbItemCode = []; var FifthTblGroup = [];
var fnBomDetails = function () {
    var FifthTblData = $("#ItemizedSizeWiseQtyBrkUpFifthTbl").jexcel('getData'); FifthTblGroup = [];
    for(var i = 0; i < FifthTblData.length; i++) {
        if(FifthTblData[i][0] != "" && FifthTblData[i][1] != "" && FifthTblData[i][2] != "" && FifthTblData[i][3] != "" && FifthTblData[i][4] != "" && FifthTblData[i][16] != "") {
            FifthTblGroup[FifthTblData[i][0]+"#"+FifthTblData[i][1]+"#"+FifthTblData[i][2]+"#"+FifthTblData[i][3]+"#"+FifthTblData[i][4]] = FifthTblData[i][16]
        }

    }
    if($("#PoWiseItemizedQtyThirdTbl").jexcel('getData').length) {
        GlbUniqueSplitColors = []; GlbUniqueSplitComponent = [];
        var SplitComponentFromThirdTbl = $("#PoWiseItemizedQtyThirdTbl").jexcel('getColumnData',1);
        var SplitColorFromThirdTbl = $("#PoWiseItemizedQtyThirdTbl").jexcel('getColumnData',2);
        jQuery.each(SplitColorFromThirdTbl,function (i,el) {
            if(jQuery.inArray(el,GlbUniqueSplitColors) === -1) if(el) GlbUniqueSplitColors.push(el);
        });
        jQuery.each(SplitComponentFromThirdTbl,function (i,el) {
            if(jQuery.inArray(el,GlbUniqueSplitComponent) === -1) if(el) GlbUniqueSplitComponent.push(el);
        });
    }

    var SizeSpecCodeFromFifthTbl = $("#ItemizedSizeWiseQtyBrkUpFifthTbl").jexcel('getColumnData',3); var UniqueSizeSpecCode = [];
    var PoNoFromFifthTbl = $("#ItemizedSizeWiseQtyBrkUpFifthTbl").jexcel('getColumnData',4); var UniquePoNo = [];
    jQuery.each(SizeSpecCodeFromFifthTbl,function (i,el) {
        if(jQuery.inArray(el,UniqueSizeSpecCode) === -1) if(el) UniqueSizeSpecCode.push(el);
    });
    jQuery.each(PoNoFromFifthTbl,function (i,el) {
        if(jQuery.inArray(el,UniquePoNo) === -1) if(el) UniquePoNo.push(el);
    });
    if(typeof Storage !== "undefined") {
        localStorage.setItem('UniqueSizeSpecCodeLs',""); localStorage.setItem('UniqueSizeSpecCodeLs',JSON.stringify(UniqueSizeSpecCode));
        localStorage.setItem('UniquePoNoLs',""); localStorage.setItem('UniquePoNoLs',JSON.stringify(UniquePoNo));
        var UniqueSizeSpecCodeLs = localStorage.getItem('UniqueSizeSpecCodeLs');
        var UniquePoNoLs = localStorage.getItem('UniquePoNoLs');
    }

    if (jsTrim($('#divBomDetails').html()) != '') {
        var bomDataInfo = $('#divBomDetails').jexcel('getData');
        $('#divBomDetails').html('');
    }
    $("#divBomDetails").jexcel({
        colHeaders: ['Combo', 'Component', 'Colour', 'Size Spec<br/>Code Fit','P.O. No. / Enq. Ref. No.', 'Item Description / (%)Blend / Content / Material', 'Item Code', 'Item Colour<br/>Code', 'Size / Dim.<br/>(W*L*H)', 'Unit of<br/>Measure','Itemized Qty. (Pcs.)', 'BOM Intake','Reqd. Qty.','Unit of<br/>Measure'],
        colWidths: [90, 90, 70, 90, 110, 200, 90, 90, 80, 60, 70, 70, 60, 60],
        allowInsertColumn: false,
        columns: [
            {type: 'dropdown', source: GlbComboArr},
            {type: 'dropdown', source: GlbUniqueSplitComponent},
            {type: 'dropdown', source: GlbUniqueSplitColors},
            {type: 'dropdown', source: JSON.parse(UniqueSizeSpecCodeLs)},
            {type: 'dropdown', source: JSON.parse(UniquePoNoLs)},
            {type: 'dropdown', source: JSON.parse(GlbBom)},
            {type: 'text', wordWrap: true},
            {type: 'text', wordWrap: true},
            {type: 'text', wordWrap: true},
            {type: 'dropdown', source: unitMeasure},
            {type: 'text', wordWrap: true},
            {type: 'text', wordWrap: true},
            {type: 'text', wordWrap: true},
            {type: 'dropdown', source: unitMeasure},
        ]
    });
    var BOMQty=0; var BOMIntake=0;
    var one = "", two = "", three = "", four = "", five = "", joined = "";
    $("#divBomDetails").jexcel('updateSettings', {
        table: function (instance, cell, col, row, val, id) {
            if(col==0) {BOMQty=0;}
            if(col == 0) one    = $(cell).text();
            if(col == 1) two    = jsTrim($(cell).text());
            if(col == 2) three  = jsTrim($(cell).text());
            if(col == 3) four   = $(cell).text();
            if(col == 4) {
                five = $(cell).text();
                joined = one+"#"+two+"#"+three+"#"+four+"#"+five;

            }
            if(col==10) { $(cell).html(FifthTblGroup[joined]); BOMQty      = parseInt(FifthTblGroup[joined]) };
            if(col==11) {BOMIntake   = parseInt(jsTrim($(cell).text()));}
            if(col==12) {
                if(BOMQty>=1 && BOMIntake>=1) {
                    $(cell).text(BOMQty*BOMIntake);
                } else {
                    $(cell).text(0);
                }
            }
        }
    });
};


fnBomConsolidated = function () {
    var bomConsolidatedDataInfo = [];
    if (jsTrim($('#divBomConsolidated').html()) != '') {
        bomConsolidatedDataInfo = $('#divBomConsolidated').jexcel('getData');
        $('#divBomConsolidated').html('');
    }
    //fnGetPONoList($('#divBomDetails').jexcel('getData'), 4, 0, 1, 2, 3, 13);
    $("#divBomConsolidated").jexcel({
        allowInsertColumn: false,
        colHeaders: ['Item Description', 'Item Code', 'Item Color Code', 'Size / Dim. (W*L*H)', 'Unit of Measure', 'Consld. BOM Reqd. Qty.','Unit of Measure','Ex. Qty.(%)','Ex. Qty. (Nos.)','Plan. BOM Qty.'],
        colWidths: [300, 110, 110, 100, 100, 100, 100, 100, 100, 100],
        columns: [
            {type: 'text', readOnly:true},
            {type: 'text', wordWrap: true,readOnly:true},
            {type: 'text', wordWrap: true,readOnly:true},
            {type: 'text', wordWrap: true,readOnly:true},
            {type: 'dropdown', source: unitMeasure,readOnly:true},
            {type: 'text', wordWrap: true,readOnly:true},
            {type: 'dropdown', source: unitMeasure},
            {type: 'text', wordWrap: true},
            {type: 'text', wordWrap: true,readOnly:true},
            {type: 'text', wordWrap: true,readOnly:true}
        ]
    });

};

var fnBomSrcAppDetails = function () {
    var bomSrcAppDataInfo = [];
    if (jsTrim($('#divBomSrcApproveDetails').html()) != '') {
        var bomSrcAppDataInfo = $('#divBomSrcApproveDetails').jexcel('getData');
        $('#divBomSrcApproveDetails').html('');
    }
    $("#divBomSrcApproveDetails").jexcel({
        colHeaders: ['Item Description', 'Item Code','Item color Code','Category', 'Sourcing<br/>Details', 'Supplier<br/>Name', 'User ID', 'Password', 'P.W. Exp.<br/>Date', 'BOM<br/>Approval', 'Approving<br/>Authority', 'Sample<br/>Size', 'Total No Of<br/>Samples'],
        allowInsertColumn: false,
        colWidths: [300, 110, 110, 70, 70, 80, 80, 70, 80, 70, 70, 60, 60],
        columns: [
            //{type: 'dropdown', source: JSON.parse(GlbBom)},
            {type: 'text', readOnly: true},
            {type: 'text', readOnly: true},
            {type: 'text', readOnly: true},
            {type: 'dropdown', source: JSON.parse(GlbBomSupplier) },
            {type: 'dropdown', source: JSON.parse(GlbBomSupplier) },
            {type: 'text', wordWrap: true},
            {type: 'text', wordWrap: true},
            {type: 'text', wordWrap: true},
            {type: 'calendar', options: {format: 'DD/MM/YYYY'}},
            {type: 'dropdown', source: authority.approving},
            {type: 'dropdown', source: authority.approving},
            {type: 'text', wordWrap: true},
            {type: 'text', wordWrap: true}
        ]
    });

    var ArrBomApproveData                               = bomSrcAppDataInfo;
    var ArrBomConsolidateDataInfo                       = $('#divBomConsolidated').jexcel('getData');
    for (var i = 0; i < ArrBomConsolidateDataInfo.length; i++) {
        if(ArrBomConsolidateDataInfo[i][0]!="") {
            $('#divBomSrcApproveDetails').jexcel('insertRow', [ArrBomConsolidateDataInfo[i][0], ArrBomApproveData[i][1], ArrBomApproveData[i][2], ArrBomApproveData[i][3], ArrBomApproveData[i][4],ArrBomApproveData[i][5],ArrBomApproveData[i][6],ArrBomApproveData[i][7],ArrBomApproveData[i][8],ArrBomApproveData[i][9],ArrBomApproveData[i][10]],0);
        }
    }
};


var fnCgpf = function () {
    var cgpfDataInfo = [];
    if (jsTrim($('#divCgpf').html()) != '') {
        var cgpfDataInfo = $('#divCgpf').jexcel('getData');
        $('#divCgpf').html('');
    }
    if($("#PoWiseItemizedQtyThirdTbl").jexcel('getData').length) {
        GlbUniqueSplitColors = []; GlbUniqueSplitComponent = [];
        var SplitComponentFromThirdTbl = $("#PoWiseItemizedQtyThirdTbl").jexcel('getColumnData',1);
        var SplitColorFromThirdTbl = $("#PoWiseItemizedQtyThirdTbl").jexcel('getColumnData',2);

        jQuery.each(SplitColorFromThirdTbl,function (i,el) {
            if(jQuery.inArray(el,GlbUniqueSplitColors) === -1) if(el) GlbUniqueSplitColors.push(el);
        });

        jQuery.each(SplitComponentFromThirdTbl,function (i,el) {
            if(jQuery.inArray(el,GlbUniqueSplitComponent) === -1) if(el) GlbUniqueSplitComponent.push(el);
        });
    }
    $("#divCgpf").jexcel({
        data: cgpfDataInfo,
        colHeaders: ['Combo', 'Component', 'Colour', 'Process Flow Description'],
        colWidths: [170, 170, 170, 720],
        allowInsertColumn: false,
        columns: [
            {type: 'dropdown', source: GlbComboArr},
            {type: 'dropdown', source: GlbUniqueSplitComponent},
            {type: 'dropdown', source: GlbUniqueSplitColors},
            {type: 'dropdown', source: JSON.parse(GlbProcessFlow)}
        ]
    });
};

function fnSamplingDetails() {
    if (jsTrim($('#divSamplingDetails').html()) != '') {
        var samplingDataInfo = $('#divSamplingDetails').jexcel('getData');
        $('#divSamplingDetails').html('');
    }
    if($("#PoWiseItemizedQtyThirdTbl").jexcel('getData').length) {
        GlbUniqueSplitColors = []; GlbUniqueSplitComponent = [];
        var SplitComponentFromThirdTbl = $("#PoWiseItemizedQtyThirdTbl").jexcel('getColumnData',1);
        var SplitColorFromThirdTbl = $("#PoWiseItemizedQtyThirdTbl").jexcel('getColumnData',2);

        jQuery.each(SplitColorFromThirdTbl,function (i,el) {
            if(jQuery.inArray(el,GlbUniqueSplitColors) === -1) if(el) GlbUniqueSplitColors.push(el);
        });

        jQuery.each(SplitComponentFromThirdTbl,function (i,el) {
            if(jQuery.inArray(el,GlbUniqueSplitComponent) === -1) if(el) GlbUniqueSplitComponent.push(el);
        });
    }
    var UniquePoNoLs = localStorage.getItem('UniquePoNoLs');
    var UniqueSizeSpecCodeLs = localStorage.getItem('UniqueSizeSpecCodeLs');
    $("#divSamplingDetails").jexcel({
        colHeaders: ['Combo', 'Component', 'Colour', 'Size Spec<br/>Code / Fit', 'P.O. No./<br/>Enq. Ref. No.', 'Requirement', 'Reqd.<br/>Sample Size', 'Reqd. No of<br/>Samples', 'Submission<br/>Date', 'Buyers Weekly<br/>Sample App. Days', 'Approving<br/>Authority'],
        colWidths: [125, 125, 125, 100, 110, 120, 100, 110, 100, 115, 100],
        allowInsertColumn: false,
        columns: [
            {type: 'dropdown', source: GlbComboArr},
            {type: 'dropdown', source: GlbUniqueSplitComponent},
            {type: 'dropdown', source: GlbUniqueSplitColors},
            {type: 'dropdown', source: JSON.parse(UniqueSizeSpecCodeLs), wordWrap: true},
            {type: 'dropdown', source: JSON.parse(UniquePoNoLs)},
            {type: 'dropdown', source: JSON.parse(GlbSamplingReq) },
            {type: 'text', wordWrap: true},
            {type: 'text', wordWrap: true},
            {type: 'calendar', options: {format: 'DD/MM/YYYY'}},
            {type: 'text', wordWrap: true},
            {type: 'dropdown', source: authority.approving}
        ]
    });
};

var fnLabTestDetails = function () {
    var labTestDataInfo = [];
    if (jsTrim($('#divLabTest').html()) != '') {
        var labTestDataInfo = $('#divLabTest').jexcel('getData');
        $('#divLabTest').html('');
    }
    if($("#PoWiseItemizedQtyThirdTbl").jexcel('getData').length) {
        GlbUniqueSplitColors = []; GlbUniqueSplitComponent = [];
        var SplitComponentFromThirdTbl = $("#PoWiseItemizedQtyThirdTbl").jexcel('getColumnData',1);
        var SplitColorFromThirdTbl = $("#PoWiseItemizedQtyThirdTbl").jexcel('getColumnData',2);

        jQuery.each(SplitColorFromThirdTbl,function (i,el) {
            if(jQuery.inArray(el,GlbUniqueSplitColors) === -1) if(el) GlbUniqueSplitColors.push(el);
        });
        var SecondLevelColor = [], TrimedSecondLevelColor = [];
        for(var i = 0; i < GlbUniqueSplitColors.length; i++) {
            var splicolor = GlbUniqueSplitColors[i].split(';');
            if(splicolor.length >= 2) SecondLevelColor.push(splicolor);
            else SecondLevelColor.push(GlbUniqueSplitColors[i]);
        }
        var rslt = [].concat.apply([],SecondLevelColor);
        for(var i = 0; i < rslt.length; i++) TrimedSecondLevelColor.push(jsTrim(rslt[i]));

        jQuery.each(SplitComponentFromThirdTbl,function (i,el) {
            if(jQuery.inArray(el,GlbUniqueSplitComponent) === -1) if(el) GlbUniqueSplitComponent.push(el);
        });
    }
    $("#divLabTest").jexcel({
        data: labTestDataInfo,
        colHeaders: ['Combo', 'Component', 'Colour', 'Item Description', 'Lab Description', 'Acceptable<br/>Level', 'Testing Authority', 'Approving Authority'],
        colWidths: [135, 135, 135, 200, 195, 150, 150, 130],
        allowInsertColumn: false,
        columns: [
            {type: 'dropdown', source: GlbComboArr},
            {type: 'dropdown', source: GlbUniqueSplitComponent},
//          {type: 'dropdown', source: GlbUniqueSplitComponent, filter: compDropdowncommon},
//          {type: 'dropdown', source: TrimedSecondLevelColor, wordWrap: true, filter: colorDropdowncommon},
            {type: 'dropdown', source: TrimedSecondLevelColor},
            {type: 'dropdown', source: JSON.parse(GlbDyeFabricDetails) },
            {type: 'dropdown', source: JSON.parse(GlbLabTestDesc) },
            {type: 'dropdown', source: JSON.parse(GlbLabAcceptLevel) },
            {type: 'dropdown', source: authority.testing},
            {type: 'dropdown', source: authority.approving}
        ]
    });

};
var fnLabTestDetailsFreeText = function () {
    var labTestDataInfo = [];
    if (jsTrim($('#divLabTestFreeText').html()) != '') {
        var labTestDataInfo = $('#divLabTest').jexcel('getData');
        $('#divLabTest').html('');
    }
    if($("#PoWiseItemizedQtyThirdTbl").jexcel('getData').length) {
        GlbUniqueSplitColors = []; GlbUniqueSplitComponent = [];
        var SplitComponentFromThirdTbl = $("#PoWiseItemizedQtyThirdTbl").jexcel('getColumnData',1);
        var SplitColorFromThirdTbl = $("#PoWiseItemizedQtyThirdTbl").jexcel('getColumnData',2);

        jQuery.each(SplitColorFromThirdTbl,function (i,el) {
            if(jQuery.inArray(el,GlbUniqueSplitColors) === -1) if(el) GlbUniqueSplitColors.push(el);
        });
        var SecondLevelColor = [], TrimedSecondLevelColor = [];
        for(var i = 0; i < GlbUniqueSplitColors.length; i++) {
            var splicolor = GlbUniqueSplitColors[i].split(';');
            if(splicolor.length >= 2) SecondLevelColor.push(splicolor);
            else SecondLevelColor.push(GlbUniqueSplitColors[i]);
        }
        var rslt = [].concat.apply([],SecondLevelColor);
        for(var i = 0; i < rslt.length; i++) TrimedSecondLevelColor.push(jsTrim(rslt[i]));

        jQuery.each(SplitComponentFromThirdTbl,function (i,el) {
            if(jQuery.inArray(el,GlbUniqueSplitComponent) === -1) if(el) GlbUniqueSplitComponent.push(el);
        });
    }
    $("#divLabTestFreeText").jexcel({
        data: labTestDataInfo,
        colHeaders: ['Combo', 'Component', 'Colour', 'Item Description', 'Lab Description', 'Acceptable<br/>Level', 'Testing Authority', 'Approving Authority'],
        colWidths: [135, 135, 135, 200, 195, 150, 150, 130],
        allowInsertColumn: false,
        columns: [
            {type: 'dropdown', source: GlbComboArr},
            {type: 'dropdown', source: GlbUniqueSplitComponent},
            {type: 'dropdown', source: TrimedSecondLevelColor, wordWrap: true},
            {type: 'text'},
            {type: 'dropdown', source: JSON.parse(GlbLabTestDesc) },
            {type: 'dropdown', source: JSON.parse(GlbLabAcceptLevel) },
            {type: 'dropdown', source: authority.testing},
            {type: 'dropdown', source: authority.approving}
        ]
    });

};
var GlbComponentArrTwo = [];
var GlbColorArrTwo = [];
var compDropdownTwo = function (instance, cell, c, r, source) {
    var ObjId = $(instance).prop('id');
    var comboName = $("#" + ObjId).jexcel('getValue', '1-' + r);
    var ComboId = jQuery.inArray(comboName, GlbComboArr);
    return GlbComponentArrTwo[ComboId].split("|#|");
}

var colorDropdownTwo = function (instance, cell, c, r, source) {
    var ObjId = $(instance).prop('id');
    var comboName = $("#" + ObjId).jexcel('getValue', '1-' + r);
    var compName = $("#" + ObjId).jexcel('getValue', '2-' + r);
    var ComboId = jQuery.inArray(comboName, GlbComboArr);
    var CompId = jQuery.inArray(compName, GlbAllComponentArr);
    return GlbColorArrTwo[ComboId + "-" + CompId].split("|#|");
}

var fnPackingDetails = function () {
    if (jsTrim($('#divPackingDetails').html()) != '') {
        $('#divPackingDetails').html('');
    }
    if($("#PoWiseItemizedQtyThirdTbl").jexcel('getData').length) {
        GlbUniqueSplitColors = []; GlbUniqueSplitComponent = [];
        var SplitComponentFromThirdTbl = $("#PoWiseItemizedQtyThirdTbl").jexcel('getColumnData',1);
        var SplitColorFromThirdTbl = $("#PoWiseItemizedQtyThirdTbl").jexcel('getColumnData',2);

        jQuery.each(SplitColorFromThirdTbl,function (i,el) {
            if(jQuery.inArray(el,GlbUniqueSplitColors) === -1) if(el) GlbUniqueSplitColors.push(el);
        });
        jQuery.each(SplitComponentFromThirdTbl,function (i,el) {
            if(jQuery.inArray(el,GlbUniqueSplitComponent) === -1) if(el) GlbUniqueSplitComponent.push(el);
        });
    }

    var UniqueSizeSpecCodeLs = localStorage.getItem('UniqueSizeSpecCodeLs'); var UniquePoNoLs = localStorage.getItem('UniquePoNoLs');
    $("#divPackingDetails").jexcel({
        //data: packingDataInfo,
        colHeaders: ['Size Spec<br/>Code / Fit','P.O. No./<br/>Enq. Ref. No.', 'Combo', 'Component', 'Colour', 'Packing Code', 'Packing<br/>Type', 'Intake<br/>Per Bag', 'Packing Materials / Other Details'],
        colWidths: [105, 100, 120, 120, 120, 80, 80, 80, 425],
        allowInsertColumn: false,
        columns: [
            {type: 'dropdown', source: JSON.parse(UniqueSizeSpecCodeLs), wordWrap: true},
            {type: 'dropdown', source: JSON.parse(UniquePoNoLs), wordWrap: true},
            {type: 'dropdown', source: GlbComboArr},
            {type: 'dropdown', source: GlbUniqueSplitComponent, wordWrap: true},
            {type: 'dropdown', source: GlbUniqueSplitColors, wordWrap: true},
            {type: 'dropdown', source: JSON.parse(GlbPackCode) },
            {type: 'dropdown', source: JSON.parse(GlbPackingtype)},
            {type: 'text', wordWrap: true},
            {type: 'dropdown', source: JSON.parse(GlbPackingMaterials)}
        ]
    });
    var packingsizessum = 0;
    $("#divPackingDetails").jexcel('updateSettings',
        {
            table: function (instance, cell, col, row, val, id) {
                if (col == 0) {
                    packingsizessum = 0;
                }
                if (col == 9 || col == 10 || col == 11 || col == 12 || col == 13 || col == 14 || col == 15 || col == 16) {
                    packingsizessum = packingsizessum + parseInt(val);
                }
                if (col == 17) {
                    $(cell).text(packingsizessum);
                }
            }
        }
    );
};

var GlbCarSizesSum = 0;
var GlbQtyPerCarton = 0;
var GlbTotNoofCartons = 0;
var fnCartonAssortmentRatio = function () {
    var divCartonAssortmentRatio = [];
    if (jsTrim($('#divCartonAssortmentRatio').html()) != '') {
        var divCartonAssortmentRatio = $('#divCartonAssortmentRatio').jexcel('getData');
        $('#divCartonAssortmentRatio').html('');
    }
    var PoQty = $("#PoWiseDeliverySchdFourthTbl").jexcel('getColumnData',4);
    var UniquePoNoLs = localStorage.getItem('UniquePoNoLs');
    //Column Headers
    var ColHeaders      = "";
    var ArrSizeChartHeader = GlbSelSizeChartText.split(",");
    var ArrColHeaderFinal = ['P.O. No./<br/>Enq. Ref. No.', 'Combo / Colour'];
    for(var i=0;i<8;i++) {
        if(typeof (ArrSizeChartHeader[i])!="undefined") {
            ColHeaders = ColHeaders+ArrSizeChartHeader[i]+",";
            ArrColHeaderFinal.push(ArrSizeChartHeader[i]);
        } else {
            ColHeaders = ColHeaders+",";
            ArrColHeaderFinal.push('No Size');
        }
    }
    ArrColHeaderFinal.push('SUM');
    //ArrColHeaderFinal.push('Qty. Per<br/>Carton'); ArrColHeaderFinal.push('P.O. Qty.'); ArrColHeaderFinal.push('Pcs. / Set'); ArrColHeaderFinal.push('Total No Of<br/>Cartons');
    $("#divCartonAssortmentRatio").jexcel({
        colHeaders: ArrColHeaderFinal,
        allowInsertColumn: false,
        colWidths: [110, 110, 70, 70, 70, 70, 70, 70, 70, 70, 90],
        //colWidths: [110, 110, 70, 70, 70, 70, 70, 70, 70, 70, 90, 100, 80, 90, 90],
        columns: [
            {type: 'dropdown', source: JSON.parse(UniquePoNoLs)},
            {type: 'dropdown', source: GlbComboArr},
            {type: 'numeric', wordWrap: true,readOnly:GlbReadOnlyInfo[0]},
            {type: 'numeric', wordWrap: true,readOnly:GlbReadOnlyInfo[1]},
            {type: 'numeric', wordWrap: true,readOnly:GlbReadOnlyInfo[2]},
            {type: 'numeric', wordWrap: true,readOnly:GlbReadOnlyInfo[3]},
            {type: 'numeric', wordWrap: true,readOnly:GlbReadOnlyInfo[4]},
            {type: 'numeric', wordWrap: true,readOnly:GlbReadOnlyInfo[5]},
            {type: 'numeric', wordWrap: true,readOnly:GlbReadOnlyInfo[6]},
            {type: 'numeric', wordWrap: true,readOnly:GlbReadOnlyInfo[7]},
            {type: 'numeric', wordWrap: true},
        ]
    });

    var colsVal = 0;var colsItemPOSumVal = 0; var PoQty = 0; var QtyPerCarton = 0;
    $("#divCartonAssortmentRatio").jexcel('updateSettings', {
        table: function (instance, cell, col, row, val, id) {
            if (col == 0) {
                colsVal = 0;colsItemPOSumVal=0; PoQty = 0; QtyPerCarton - 0;
            }
            if (col == 2 || col == 3 || col == 4 || col == 5 || col == 6 || col == 7 || col == 8 || col == 9) {
                if(parseInt($(cell).text())>0) {
                    colsVal = colsVal + parseInt($(cell).text());
                }
                $(cell).css('text-align', 'right');
            }
            if (col == 10) {
                $(cell).html(colsVal);
            }
            if(col == 11) QtyPerCarton = $(cell).text();
            if(col == 12) PoQty = $(cell).text();
            if(col == 14) $(cell).html(parseInt(PoQty) / parseInt(QtyPerCarton) );
        }
    });
};

var fnMasterAssortmentRatio = function () {
    var divMasterBagAssortmentRatio = [];
    if (jsTrim($('#divMasterBagAssortmentRatio').html()) != '') {
        var divMasterBagAssortmentRatio = $('#divMasterBagAssortmentRatio').jexcel('getData');
        $('#divMasterBagAssortmentRatio').html('');
    }
    var UniquePoNoLs = localStorage.getItem('UniquePoNoLs');
    //Column Headers
    var ColHeaders      = "";
    var ArrSizeChartHeader = GlbSelSizeChartText.split(",");
    var ArrColHeaderFinal = ['P.O. No./<br/>Enq. Ref. No.', 'Combo / Colour'];
    for(var i=0;i<8;i++) {
        if(typeof (ArrSizeChartHeader[i])!="undefined") {
            ColHeaders = ColHeaders+ArrSizeChartHeader[i]+",";
            ArrColHeaderFinal.push(ArrSizeChartHeader[i]);
        } else {
            ColHeaders = ColHeaders+",";
            ArrColHeaderFinal.push('No Size');
        }
    }
    ArrColHeaderFinal.push('No. of Pcs.<br/>Per M-Bag');ArrColHeaderFinal.push('No. of M-Bags<br/>Per Carton');

    $("#divMasterBagAssortmentRatio").jexcel({
        colHeaders: ArrColHeaderFinal,
        colWidths: [190, 240, 75, 75, 75, 75, 75, 75, 75, 75,75, 120],
        allowInsertColumn: false,
        columns: [
            {type: 'dropdown', source: JSON.parse(UniquePoNoLs)},
            {type: 'dropdown', source: GlbComboArr},
            {type: 'text', wordWrap: true,readOnly:GlbReadOnlyInfo[0]},
            {type: 'text', wordWrap: true,readOnly:GlbReadOnlyInfo[1]},
            {type: 'text', wordWrap: true,readOnly:GlbReadOnlyInfo[2]},
            {type: 'text', wordWrap: true,readOnly:GlbReadOnlyInfo[3]},
            {type: 'text', wordWrap: true,readOnly:GlbReadOnlyInfo[4]},
            {type: 'text', wordWrap: true,readOnly:GlbReadOnlyInfo[5]},
            {type: 'text', wordWrap: true,readOnly:GlbReadOnlyInfo[6]},
            {type: 'text', wordWrap: true,readOnly:GlbReadOnlyInfo[7]},
            {type: 'text', wordWrap: true,readOnly:true},
        ]
    });

    var colsVal = 0;var colsItemPOSumVal = 0;
    $("#divMasterBagAssortmentRatio").jexcel('updateSettings', {
        table: function (instance, cell, col, row, val, id) {
            if (col == 0) {
                colsVal = 0;colsItemPOSumVal=0;
            }
            if (col == 2 || col == 3 || col == 4 || col == 5 || col == 6 || col == 7 || col == 8 || col == 9) {
                if(parseInt($(cell).text())>0) {
                    colsVal = colsVal + parseInt($(cell).text());
                }

                $(cell).css('text-align', 'right');
            }
            if (col == 10) {
                $(cell).html(colsVal);
            }
        }
    });

};

var fnLotInspectionDetails = function () {
    var lotInspectionDataInfo = [];
    if (jsTrim($('#divLotInspection').html()) != '') {
        var lotInspectionDataInfo = $('#divLotInspection').jexcel('getData');
        $('#divLotInspection').html('');
    }
    var inspection = JSON.parse(GlbInspection); var UniquePoNoLs = localStorage.getItem('UniquePoNoLs');
    var PoQty = $("#PoWiseDeliverySchdFourthTbl").jexcel('getColumnData',4);
    $("#divLotInspection").jexcel({
        data: lotInspectionDataInfo,
        colHeaders: ['P.O.No./<br/>Enq. Ref. No.', 'Combo / Colour', 'P.O. Qty.', 'Pcs. / Set', 'Level', 'Code Letter', 'Sample Size', 'AQL', 'Inspection Authority', 'FI Date', 'Remarks'],
        allowInsertColumn: false,
        colWidths: [120, 120, 100, 70, 120, 120, 100, 100, 90, 90, 200],
        columns: [
            {type: 'dropdown', source: JSON.parse(UniquePoNoLs)},
            {type: 'dropdown', source: GlbComboArr},
            {type: 'dropdown', source: PoQty},
            {type: 'dropdown', source: ArrPcsOrSet},
            {type: 'dropdown', source: inspection.level},
            {type: 'dropdown', source: inspection.codeletter},
            {type: 'text', wordWrap: true},
            {type: 'dropdown', source: inspection.aql},
            {type: 'dropdown', source: authority.inspection},
            {type: 'calendar', options: {format: 'DD/MM/YYYY'}},
            {type: 'text', wordWrap: true},
        ]
    });
};

var fnInvoiceAndDocumentation = function () {
    var invoiceAndDocDataInfo = [];
    if (jsTrim($('#divInvoiceAndDoc').html()) != '') {
        var invoiceAndDocDataInfo = $('#divInvoiceAndDoc').jexcel('getData');
        $('#divInvoiceAndDoc').html('');
    }
    var UniquePoNoLs = localStorage.getItem('UniquePoNoLs'); var PoQty = $("#PoWiseDeliverySchdFourthTbl").jexcel('getColumnData',4); console.log(PoQty,'PoQty');
    $("#divInvoiceAndDoc").jexcel({
        data: invoiceAndDocDataInfo,
        colHeaders: ['P.O. No./<br/>Enq. Ref. No.', 'Combo / Colour', 'P.O. Qty.', 'Pcs. / Set', 'Consignor / Shipper / Exporter', 'Forwarding Agent', 'Clearing Agent', 'Importer - If other than<br/>Consignee', 'Consignee'],
        colWidths: [100, 130, 70, 70, 200, 180, 180, 180, 120],
        allowInsertColumn: false,
        columns: [
            {type: 'dropdown', source: JSON.parse(UniquePoNoLs)},
            {type: 'dropdown', source: GlbComboArr},
            {type: 'dropdown', source: PoQty},
            {type: 'dropdown', source: ArrPcsOrSet},
            {type: 'dropdown', source: JSON.parse(GlbArrConsignor) },
            {type: 'dropdown', source: JSON.parse(GlbArrForwarding) },
            {type: 'dropdown', source: JSON.parse(GlbArrClearing) },
            {type: 'dropdown', source: JSON.parse(GlbArrImporter) },
            {type: 'dropdown', source: JSON.parse(GlbArrConsignee) },

        ]
    });
};

var fnMerchantCheckList = function () {
    //console.log(GlbChecklist,'GlbChecklist');
    $("#divMerchantCheckList").jexcel({
        colHeaders: ['Combo', 'Component', 'Colour', 'Checklist<br/>Description', 'N.A.', 'N.R.', 'P.R.', 'Recd.', 'Details<br/>Recd. Date', 'Request<br/>for Details', 'Request<br/>Date', 'Remainder<br/>Cycle', 'Remainder<br/>Count', 'Recent<br/>Update'],
        colWidths: [120, 120, 110, 220, 45, 45, 45, 45, 80, 80, 60, 80, 80, 100],
        allowInsertColumn: false,
        columns: [
            {type: 'text', wordWrap: true},
            {type: 'text', wordWrap: true},
            {type: 'text', wordWrap: true},
            {type: 'dropdown', source: JSON.parse(GlbChecklist)},
            {type: 'checkbox'},
            {type: 'checkbox'},
            {type: 'checkbox'},
            {type: 'checkbox'},
            {type: 'text', wordWrap: true},
            {type: 'text', wordWrap: true},
            {type: 'calendar', options: {format: 'DD/MM/YYYY'}},
            {type: 'text', wordWrap: true},
            {type: 'text', wordWrap: true},
            {type: 'text', wordWrap: true}


        ]
    });
};

/*Table Functions End*/

function fnGetCurrency(id) {

    var Parameters = GlbParam + "&currencycode=" + id;
    MakePostRequest(base_path + GlbCompanyFdr + 'orderentry/entry', Parameters, 'json', fnGetCurrencyRes);
}

var GlbCurrencyRate = '';

function fnGetCurrencyRes(data) {
    if (data != '') {
        if (data.errcode != undefined) {
            if (data.errcode == '404') {
                fnCallSessionExpire();
                return false;
            } else {
                GlbCurrencyRate = Math.round(data.VarExchangeRate.inramount);
                $("#frmStaticExRate").text(data.VarExchangeRate.inramount);
            }
        }
    }
}

function fnShowSubChartInfo(VarMasterChartId) {
    MakePostRequest(base_path + GlbCompanyFdr + 'orderentry/getSubChartInfo', "sc="+VarMasterChartId, 'json', fnShowSubChartRes);
    return false
}

function fnShowSubChartRes(data) {
    if (data != '') {
        if (data.errcode != undefined) {
            if (data.errcode == '404') {
                fnCallSessionExpire();
                return false;
            } else {
                $("#divSubChartList").html(data.ss);
            }
        }
    }
}
$(document).ready(function () {
    extraObj = $("#uploadBusinssImg").uploadFile({
        dragDrop: true,
        url: base_path + GlbCompanyFdr + 'orderentry/dropbox',
        fileName: "bimage",
        returnType: "json",
        showFileCounter: false,
        maxFileCount: 3,
        extraHTML: function () {
            var html = "<div>";
            html += "<select name='frmSetAsDefault' id='frmSetAsDefault'>" + GlbComboItems + "</select>";
            html += "</div>";
            return html;
        },
        formData: {"lid": ''},
        autoSubmit: false,
        afterUploadAll: function (obj) {
            fnRedirectPageTimeOut(base_path + GlbCompanyFdr + 'orderentry/entry/' + GlbListingEncId);
        }
    });
    var secondTblLs = localStorage.getItem('secondTblLs');
    var firstTblLs = localStorage.getItem('firstTblLs');
    if(secondTblLs && firstTblLs) {
        var SelSizeChartText = localStorage.getItem('SelSizeChartTextLs');
        var OrderSChartId = localStorage.getItem('OrderSizeChartIdLs');
        $("#divDispFinalChartInfo").text(SelSizeChartText);
        $("#divDispMasterChartType").text(OrderSChartId);
        $("#divComboColorFirstTable").jexcel('setData',firstTblLs);
        divComboColorFirstTableChange();
        $("#PoWiseQtyBrkUpSecondTbl").jexcel('setData',secondTblLs);
        fnShowHideEndUserSub(1,'divNextOrderEntryInfo');
        if(GlbSelSizeChartText == "") {
            GlbSelSizeChartText = SelSizeChartText;
        }
    }
    var PoWiseItemizedQtyThirdTblLs = localStorage.getItem('PoWiseItemizedQtyThirdTblLs');
    if(PoWiseItemizedQtyThirdTblLs) {
        fnItemizedQtyThirdTbl();
        $("#PoWiseItemizedQtyThirdTbl").jexcel('setData',PoWiseItemizedQtyThirdTblLs);

        //comp color
        var ThirdTblData = $("#PoWiseItemizedQtyThirdTbl").jexcel('getData'); var NewComboArr = []; var NewComp = [], NewUniquColor = [];
        for(var i = 0; i < ThirdTblData.length; i++) {
            var com = ThirdTblData[i][0]; var comp = ThirdTblData[i][1]; var col = ThirdTblData[i][2];
            if(com != "") {
                var NewComboId = jQuery.inArray(com,NewComboArr);
                if(NewComboId === -1) {
                    NewComboArr.push(com);
                }
            }
            if(comp != "") {
                var NewCompId = jQuery.inArray(comp,NewComp);
                if(NewCompId === -1) {
                    NewComp.push(comp);
                }
            }
            if(col != "") {
                var NewColId = jQuery.inArray(col,NewUniquColor);
                if(NewColId === -1) {
                    NewUniquColor.push(col);
                    var NewComboId = jQuery.inArray(com,NewComboArr); var NewCompId = jQuery.inArray(comp,NewComp);
                    if(typeof NewColor[NewComboId+"-"+NewCompId] != "undefined") {
                        NewColor[NewComboId+"-"+NewCompId] = NewColor[NewComboId+"-"+NewCompId] + "|#|"+col;
                    }
                    else {
                        NewColor[NewComboId+"-"+NewCompId] = col;
                    }
                }
                else {}
            }
        }
        if(typeof Storage !== "undefined") {
            localStorage.setItem('SpComponentArrLs',"");
            localStorage.setItem('SpComponentArrLs',JSON.stringify(NewComp));
            localStorage.setItem('NewUniquColorLs',""); localStorage.setItem('NewUniquColorLs',JSON.stringify(NewUniquColor));
        }
        //comp color
    }
    var PoWiseDeliverySchdFourthTblLs = localStorage.getItem('PoWiseDeliverySchdFourthTblLs');
    if(PoWiseDeliverySchdFourthTblLs) {
        fnPoWiseDeliverySchdFourthTbl();
        /*var len = $("#PoWiseDeliverySchdFourthTbl").jexcel('getData').length;
        len = len - 1; console.log(GlbPoNos,'GlbPoNos');
        for(var i = 0; i < GlbPoNos.length; i++) {
            var comarr = GlbDeliveryData[GlbPoNos[i]].split('|#|');
            for(var j = 0; j < comarr.length; j++) {
                $("#PoWiseDeliverySchdFourthTbl").jexcel('insertRow', [ "","",GlbPoNos[i], comarr[j], GlbDeliveryPoQty[GlbPoNos[i]+"|#|"+comarr[j]], GlbDeliveryPcsOrSet[GlbPoNos[i]+"|#|"+comarr[j]] ], len);
                len++;
            }
        }*/
        fnItemizedSizeWiseQtyBrkUpFifthTbl();
        $("#PoWiseDeliverySchdFourthTbl").jexcel('setData',PoWiseDeliverySchdFourthTblLs)
    }

    var ItemizedSizeWiseQtyBrkUpFifthTblLs = localStorage.getItem('ItemizedSizeWiseQtyBrkUpFifthTblLs');
    if(ItemizedSizeWiseQtyBrkUpFifthTblLs) {
        $("#ItemizedSizeWiseQtyBrkUpFifthTbl").jexcel('setData',ItemizedSizeWiseQtyBrkUpFifthTblLs)
    }
    var SixthATblCumulative = localStorage.getItem('SixthATblCumulative');
    if(SixthATblCumulative) {
        fnSixthATbl();
        $("#SixthATbl").jexcel('setData',SixthATblCumulative);
    }
    var CuttingRatioSixthTblLs = localStorage.getItem('CuttingRatioSixthTblLs');
    if(CuttingRatioSixthTblLs) {
        $("#CuttingRatioSixthTbl").jexcel('setData',CuttingRatioSixthTblLs)
    }

    if(localStorage.getItem('FabricKnitTblLs')) {
        fnCreateFabricKnitTable();
        var KnitTblLs = localStorage.getItem('FabricKnitTblLs');
        $("#divFabricKnitTable").jexcel('setData',KnitTblLs);
    }
    if(localStorage.getItem('FabrivWovenTblLs')) {
        fnCreateFabricWovenTable();
        var WovenTblLs = localStorage.getItem('FabrivWovenTblLs');
        $("#divFabricWoven").jexcel('setData',WovenTblLs);
    }
    if(localStorage.getItem('DyeingDetailsLs')) {
        fnCreateDyingDetailsTable();
        var DyeingTblLs = localStorage.getItem('DyeingDetailsLs');
        $("#divDyingDetails").jexcel('setData',DyeingTblLs);
    }
    var _lsTotal=0,_xLen,_x;for(_x in localStorage){ if(!localStorage.hasOwnProperty(_x)){continue;} _xLen= ((localStorage[_x].length + _x.length)* 2);_lsTotal+=_xLen; console.log(_x.substr(0,50)+" = "+ (_xLen/1024).toFixed(2)+" KB")};console.log("Total = " + (_lsTotal / 1024).toFixed(2) + " KB");
});

function fnShowHideEndUserSub(VarType,VarDivShow) {
    var ArrProfileBasicList = ["divSizeOrderEntryInfo","divNextOrderEntryInfo"];
    if(VarType==1) {
        var ArrFnalList	= ArrProfileBasicList;
    }
    //Remove Class
    for(i=0;i<ArrFnalList.length;i++) {
        $("#"+ArrFnalList[i]).removeClass('show');
        $("#"+ArrFnalList[i]).removeClass('hide');
    }
    //Add Class
    for(i=0;i<ArrFnalList.length;i++) {
        if(VarDivShow!=ArrFnalList[i]) {
            $("#"+ArrFnalList[i]).addClass('hide');
        }
    }
    $("#"+VarDivShow).addClass('show');
}

var GlbSelSizeChart = [];
var GlbSelSizeChartText = ''; var GlbMasterSizeChartId = '';
function fnGotoOrderEntry(VarType,ValDivName) {
    var SelChkBoxText           = '';
    GlbSelSizeChart             = [];
    GlbMasterSizeChartId    = $("#frmOrderSizeChartList").val();

    if(GlbMasterSizeChartId!=4) {
        $("input:checkbox[name='frmSubChartSelection']:checked").each(function(){
            GlbSelSizeChart.push($(this).val());
            SelChkBoxText       = SelChkBoxText+$(this).next("label").text()+",";
        });
    } else {
        $('input[name="frmSubChartCustomSelection[]"]').each(function() {
            if(jsTrim($(this).val())!='') {
                SelChkBoxText       = SelChkBoxText+jsTrim($(this).val())+",";
            }
        });
    }
    GlbSelSizeChartText         = SelChkBoxText.substring(0,SelChkBoxText.length - 1);
    $("#divDispFinalChartInfo").html(GlbSelSizeChartText);
    GlbOrderSCLOSel = $("#frmOrderSizeChartList option:selected").text()
    $("#divDispMasterChartType").html($("#frmOrderSizeChartList option:selected").text());
    fnShowHideEndUserSub(VarType,ValDivName);
}

function fnFirstSavePoWiseQtyBkpUp() {
    fnItemizedQtyThirdTbl();
    //Split Component and color with / separate ENDS
    fnPoWiseDeliverySchdFourthTbl();
    var len = $("#PoWiseDeliverySchdFourthTbl").jexcel('getData').length;
    len = len - 1; GlbPonoComboColorPoqty = [];
    console.log(GlbPoNos,'GlbPoNos');
    for(var i = 0; i < GlbPoNos.length; i++) {
        var comarr = GlbDeliveryData[GlbPoNos[i]].split('|#|');
        for(var j = 0; j < comarr.length; j++) {
            $("#PoWiseDeliverySchdFourthTbl").jexcel('insertRow', [ "","",GlbPoNos[i], comarr[j], GlbDeliveryPoQty[GlbPoNos[i]+"|#|"+comarr[j]], GlbDeliveryPcsOrSet[GlbPoNos[i]+"|#|"+comarr[j]] ], len);
            len++;
        }
    }
    fnItemizedSizeWiseQtyBrkUpFifthTbl();
    if(typeof Storage !== "undefined") {
        localStorage.setItem('secondTblLs','');
        localStorage.setItem('firstTblLs','');
        var SecondTblData = $("#PoWiseQtyBrkUpSecondTbl").jexcel('getData');
        var FirstTblData = $("#divComboColorFirstTable").jexcel('getData');
        localStorage.setItem('secondTblLs',JSON.stringify(SecondTblData));
        localStorage.setItem('firstTblLs',JSON.stringify(FirstTblData));
        localStorage.setItem('SelSizeChartTextLs',GlbSelSizeChartText);
        console.log(GlbMasterSizeChartId,'GlbMasterSizeChartId');
        localStorage.setItem('OrderSizeChartIdLs',GlbMasterSizeChartId);
    }
    else {
        alert('No local storage');
    }
}

function SizeWiseQtyBreakupFifthTbl() {
    var sizeWiseTblData = $("#ItemizedSizeWiseQtyBrkUpFifthTbl").jexcel('getData');
    //console.log(sizeWiseTblData,'sizeWiseTblData');
    var fillcuttingRatio = [];
    for(var i = 0; i < sizeWiseTblData.length; i++) {
        fillcuttingRatio[i] = [sizeWiseTblData[i][0],sizeWiseTblData[i][1],sizeWiseTblData[i][2],sizeWiseTblData[i][3],sizeWiseTblData[i][4],"","","","","","","","","",sizeWiseTblData[i][16]];
    }
    $("#CuttingRatioSixthTbl").jexcel('setData',fillcuttingRatio);
}

function fnSaveItemizedQtyBPDeShSizeWiseQtyBP() {
    if(typeof Storage !== "undefined") {
        var PoWiseItemizedQtyThirdTblData = $("#PoWiseItemizedQtyThirdTbl").jexcel('getData');

        var PoWiseDeliverySchdFourthTblData = $("#PoWiseDeliverySchdFourthTbl").jexcel('getData');
        var ItemizedSizeWiseQtyBrkUpFifthTblData = $("#ItemizedSizeWiseQtyBrkUpFifthTbl").jexcel('getData');
        var CuttingRatioSixthTblData = $("#CuttingRatioSixthTbl").jexcel('getData');
        localStorage.setItem('PoWiseItemizedQtyThirdTblLs',JSON.stringify(PoWiseItemizedQtyThirdTblData));
        localStorage.setItem('PoWiseDeliverySchdFourthTblLs',JSON.stringify(PoWiseDeliverySchdFourthTblData));
        localStorage.setItem('ItemizedSizeWiseQtyBrkUpFifthTblLs',JSON.stringify(ItemizedSizeWiseQtyBrkUpFifthTblData));
        localStorage.setItem('CuttingRatioSixthTblLs',JSON.stringify(CuttingRatioSixthTblData));
    }
    else {

    }
    fnCreateFabricKnitTable();
    fnCreateFabricWovenTable();
    fnCreateDyingDetailsTable();
}

var pono = [], PcsSet = "", IntakeQty = "", ItemizedIntakeQty = [];
function getAllIndexes(arr, val) {
    pono = [], PcsSet = "", IntakeQty = "", ItemizedIntakeQty = [];
    for(var i = 0; i < arr.length; i++) {
        if (arr[i] === val) {
            PcsSet = $("#ItemizedSizeWiseQtyBrkUpFifthTbl td#14-"+i).text();
            IntakeQty = $("#ItemizedSizeWiseQtyBrkUpFifthTbl td#15-"+i).text();
            ItemizedIntakeQty.push(parseInt($("#ItemizedSizeWiseQtyBrkUpFifthTbl td#16-"+i).text()));
            pono.push($("#ItemizedSizeWiseQtyBrkUpFifthTbl td#4-"+i).text());
        }
    }
}

function fnNewCummulative() {
    fnSixthATbl();
    var GlbEachGroupSet = $("#ItemizedSizeWiseQtyBrkUpFifthTbl").jexcel('getData');
    var GlbGrp = [], uniqueNames = [], OtherData = [];
    for(var i = 0; i < GlbEachGroupSet.length; i++) {
        GlbGrp.push(GlbEachGroupSet[i][0]+"#"+GlbEachGroupSet[i][1]+"#"+GlbEachGroupSet[i][2]+"#"+GlbEachGroupSet[i][3]);
        OtherData.push(GlbEachGroupSet[i][14]+"#"+GlbEachGroupSet[i][15]+"#"+GlbEachGroupSet[i][16]);
        //var PcsSet = GlbEachGroupSet[i][14];
    }
    $.each(GlbGrp, function(i, el) {
        if($.inArray(el, uniqueNames) === -1) { uniqueNames.push(el); }
    });
    for(var i = 0; i < uniqueNames.length; i++) {
        var GroupName = uniqueNames[i].split('#');
        getAllIndexes(GlbGrp,uniqueNames[i]);
        var SplittedOtherData = OtherData[i].split('#');
        //var sum  = ItemizedIntakeQty.reduce()
        var arrSum = ItemizedIntakeQty.reduce(function (a,b) { return a + b },0);
        // arrSum([20, 10, 5, 10]) -> 45
        var xxs = $("#xxs_"+i).text(); var xs = $("#xs_"+i).text(); var s = $("#s_"+i).text(); var m = $("#m_"+i).text(); var l = $("#l_"+i).text(); var xl = $("#xl_"+i).text(); var xxl = $("#xxl_"+i).text(); var xl2 = $("#2xl_"+i).text();
        var CummulativeTotal = $("#itemized_poqtyTotalbu_"+i).text();
        $("#SixthATbl").jexcel("insertRow", [ GroupName[0],GroupName[1],GroupName[2],GroupName[3],pono,xxs,xs,s,m,l,xl,xxl,xl2,CummulativeTotal,PcsSet,IntakeQty,arrSum ] , i);
    }
    $("#ItemizedBkUp").hide(); console.log(GlbEachGroupSet,'GlbEachGroupSet'); console.log(typeof GlbEachGroupSet,'type');
    //MakeAsynPostRequest(base_path+GlbCompanyFdr+"orderentry/savefifthtbl",JSON.stringify(GlbEachGroupSet),"json",FnSaveFifthTblRes);
}

/*function FnSaveFifthTblRes(data) {
    console.log(data,'res');
}*/

function FnSaveKnitWovenDyeingTbl() {
    fnBomDetails();
    var FabricKnitTblData   = $("#divFabricKnitTable").jexcel('getData');
    var FabrivWovenTblData  = $("#divFabricWoven").jexcel('getData');
    var DyeingDetailsData   = $("#divDyingDetails").jexcel('getData');
    var SixthATblCumulative = $("#SixthATbl").jexcel('getData');
    if(typeof Storage !== "undefined") {
        localStorage.setItem('SixthATblCumulative',JSON.stringify(SixthATblCumulative));
        localStorage.setItem('FabricKnitTblLs',JSON.stringify(FabricKnitTblData));
        localStorage.setItem('FabrivWovenTblLs',JSON.stringify(FabrivWovenTblData));
        localStorage.setItem('DyeingDetailsLs',JSON.stringify(DyeingDetailsData));
    }
    fnCreateEmbell();
    fnBomSrcAppDetails();
    /*
    fnCgpf();
    fnSamplingDetails();
    fnLabTestDetails();
    fnLabTestDetailsFreeText();
    fnPackingDetails();
    fnMasterAssortmentRatio();
    fnCartonAssortmentRatio();
    fnLotInspectionDetails();
    fnInvoiceAndDocumentation();
    fnMerchantCheckList();
    */
}

function FnFillBomConsolidatedTbl() {
    var BomData = $('#divBomDetails').jexcel('getData'); var GlbBomGroup = [], BomConsolidatedGroup = [];
    for(var i = 0; i < BomData.length; i++) {
        if (BomData[i][5] !== '' && BomData[i][6] !== '' && BomData[i][7] !== '' && BomData[i][8] != '' && BomData[i][9] != '') {
            var BomGroup               = BomData[i][5]+"#"+BomData[i][6]+"#"+BomData[i][7]+"#"+BomData[i][8]+"#"+BomData[i][9];
            var BomGroupId             = jQuery.inArray(BomGroup, GlbBomGroup);

            if(BomGroupId === -1) {

                GlbBomGroup.push(BomGroup);
            }
            else {
                //console.log(i,'in else');
            }
            BomConsolidatedGroup = fnPopulateValueArray(BomConsolidatedGroup,BomGroup,BomData[i][12]);

        }
    }
    //fnSumSizeArrayValue
    fnBomConsolidated();

    for(var i = 0; i < GlbBomGroup.length; i++) {
        //console.log(i);
        //console.log(' ');
        var KeyVal = GlbBomGroup[i];
        //console.log(GlbBomGroup[i],'GlbBomGroup[i]');
        //console.log(BomConsolidatedGroup[KeyVal],'Test');
        var sum = fnSumSizeArrayValue(BomConsolidatedGroup[KeyVal]);
        //console.log(sum,'sum');
        var ConsolidatedData = KeyVal.split('#');
        $("#divBomConsolidated").jexcel("insertRow", [ConsolidatedData[0],ConsolidatedData[1],ConsolidatedData[2],ConsolidatedData[3],ConsolidatedData[4],sum], i)
    }
}

function FnFillBomSampleSourcingApprovalTbl() {
    var BomConsolidatedTbl = $("#divBomConsolidated").jexcel('getData');

    var BomSamplingSourcingApproval = [];
    for(var i = 0; i < BomConsolidatedTbl.length; i++) {
        if(BomConsolidatedTbl[i][0] != "" && BomConsolidatedTbl[i][1] != "" && BomConsolidatedTbl[i][2] != "") {
            BomSamplingSourcingApproval.push( [BomConsolidatedTbl[i][0],BomConsolidatedTbl[i][1],BomConsolidatedTbl[i][2]] );
        }
    }
    $("#divBomSrcApproveDetails").jexcel('setData',BomSamplingSourcingApproval,false);
    //fnCreateEmbell();
    //fnBomSrcAppDetails();
    fnCgpf();
    fnSamplingDetails();
    fnLabTestDetails();
    fnLabTestDetailsFreeText();
    fnPackingDetails();
    fnMasterAssortmentRatio();
    fnCartonAssortmentRatio();
    fnLotInspectionDetails();
    fnInvoiceAndDocumentation();
    fnMerchantCheckList();
}

function FnQtyPerCarton() {
    var QtyperCartonData = [];
    if (jsTrim($('#QtyperCarton').html()) != '') {
        var QtyperCartonData = $('#QtyperCarton').jexcel('getData');
        $('#QtyperCarton').html('');
    }
    var PoQty = $("#PoWiseDeliverySchdFourthTbl").jexcel('getColumnData',4);
    var UniquePoNoLs = localStorage.getItem('UniquePoNoLs');
    //Column Headers
    var ColHeaders      = "";
    var ArrSizeChartHeader = GlbSelSizeChartText.split(",");
    var ArrColHeaderFinal = ['P.O. No./<br/>Enq. Ref. No.', 'Combo / Colour'];
    for(var i=0;i<8;i++) {
        if(typeof (ArrSizeChartHeader[i])!="undefined") {
            ColHeaders = ColHeaders+ArrSizeChartHeader[i]+",";
            ArrColHeaderFinal.push(ArrSizeChartHeader[i]);
        } else {
            ColHeaders = ColHeaders+",";
            ArrColHeaderFinal.push('No Size');
        }
    }
    var FourthTblData = JSON.parse(localStorage.getItem('PoWiseDeliverySchdFourthTblLs')); var FourthTblDataGroup = []; var FourthTblDataPcsSetGroup = [];
    for(var i = 0; i < FourthTblData.length; i++) {
        FourthTblDataGroup[FourthTblData[i][2]+"-"+FourthTblData[i][3]] = FourthTblData[i][4];
        FourthTblDataPcsSetGroup[FourthTblData[i][2]+"-"+FourthTblData[i][3]] = FourthTblData[i][5];
    }
    ArrColHeaderFinal.push('SUM'); ArrColHeaderFinal.push('Qty. Per<br/>Carton'); ArrColHeaderFinal.push('P.O. Qty.'); ArrColHeaderFinal.push('Pcs. / Set'); ArrColHeaderFinal.push('Total No Of<br/>Cartons');
    $("#QtyperCarton").jexcel({
    colHeaders: ArrColHeaderFinal,
        colWidths: [110, 110, 70, 70, 70, 70, 70, 70, 70, 70, 90, 100, 80, 90, 90],
        columns: [
            {type: 'dropdown', source: JSON.parse(UniquePoNoLs), readOnly: true},
            {type: 'dropdown', source: GlbComboArr, readOnly: true},
            {type: 'numeric', wordWrap: true, readOnly: true },
            {type: 'numeric', wordWrap: true, readOnly: true },
            {type: 'numeric', wordWrap: true, readOnly: true },
            {type: 'numeric', wordWrap: true, readOnly: true },
            {type: 'numeric', wordWrap: true, readOnly: true },
            {type: 'numeric', wordWrap: true, readOnly: true },
            {type: 'numeric', wordWrap: true, readOnly: true },
            {type: 'numeric', wordWrap: true, readOnly: true },
            {type: 'numeric', wordWrap: true, readOnly: true},
            {type: 'numeric', wordWrap: true, readOnly: true},
            {type: 'text', readOnly: true },
            {type: 'text', readOnly: true },
            {type: 'numeric', wordWrap: true, readOnly: true}
        ]
    });
    var CartonAssortmentData = $("#divCartonAssortmentRatio").jexcel('getData'); var PonosArr = []; var TotalCartons = 0; var SumVal = []; var PonoPosition = [];
    for(var i = 0; i < CartonAssortmentData.length; i++) {
        var Pono = CartonAssortmentData[i][0]; var SizesSum = CartonAssortmentData[i][10];
        SumVal = fnPopulateValueArray(SumVal, Pono, SizesSum);
        PonosArr.push(Pono);
    }
    for(var i in PonosArr) {
        PonoPosition[PonosArr[i]] = i;
    }
    for(var i = 0; i < CartonAssortmentData.length; i++) {
        var Pono = CartonAssortmentData[i][0]; var SizesSum = CartonAssortmentData[i][10]; var Combo = CartonAssortmentData[i][1];
        var Xxs = CartonAssortmentData[i][2]; var Xs = CartonAssortmentData[i][3]; var S = CartonAssortmentData[i][4]; var M = CartonAssortmentData[i][5];
        var L = CartonAssortmentData[i][6]; var Xl = CartonAssortmentData[i][7]; var Xxl = CartonAssortmentData[i][8]; var Xl2 = CartonAssortmentData[i][9];
        if(i == PonoPosition[Pono]) {
            var Poqty = FourthTblDataGroup[Pono+"-"+Combo]; var Cumulative = fnSumSizeArrayValue(SumVal[Pono]);
            TotalCartons = parseInt(Poqty) / parseInt(Cumulative);
            $("#QtyperCarton").jexcel("insertRow",[ Pono,Combo,Xxs,Xs,S,M,L,Xl,Xxl,Xl2,SizesSum,Cumulative,Poqty,FourthTblDataPcsSetGroup[Pono+"-"+Combo],TotalCartons ], i);
        }
        else {
            $("#QtyperCarton").jexcel("insertRow",[ Pono,Combo,Xxs,Xs,S,M,L,Xl,Xxl,Xl2,SizesSum,"","","","" ], i);
        }
    }
}


function fnSaveOrderEntry() {
    try {
        var MerchantId = $("#frmMerchantName").val();
        var frmIsrior = $("#frmIorNumber").text();
        var frmOrderType = $("#frmOrderType").val();
        var frmOrderRefNo = $("#frmOrderRefNo").val();
        console.log(frmOrderRefNo,'frmOrderRefNo');

        var frmStyleRefNo = $("#frmStyleRefNo").val();
        console.log(frmStyleRefNo,'frmStyleRefNo');
        var frmStyleName = $("#frmStyleName").val();
        var frmBrandId = $("#frmOrderBrands").val();
        var frmBuyerId = $("#frmBuyerId").val();
        var frmSeason = $("#frmOrderSeason").val();
        var frmDivision = $("#frmOrderDivDept").val();
        var frmClass = $("#frmOrderClass").val();
        var frmSubclass = $("#frmOrderSubClass").val();
        var frmTotalQty = $("#frmOrderTotalQty").val();
        var frmPcsset = $("#frmOrderPieceSet").val();
        var frmPricePerUnit = $("#frmOrderPricingUnit").val();
        var frmCurrency = $("#frmOrderCurrency").val();
        var frmPaymentTerms = $("#frmPaymentTerms").val();
        var SizeChartType = GlbMasterSizeChartId;
        var SizeChartList = $("#divDispFinalChartInfo").text();

        var SplittedComponent = [], UniqueComponent = [], UniqueColor = [], SplittedColor = [];
        var ComboOnetbl = $("#divComboColorFirstTable").jexcel('getColumnData',0);
        var CompOnetbl = $("#divComboColorFirstTable").jexcel('getColumnData',1);
        var ColorOnetbl = $("#divComboColorFirstTable").jexcel('getColumnData',2);
        for(var i = 0; i < CompOnetbl.length; i++) {
            if(jQuery.inArray(CompOnetbl[i],UniqueComponent) === -1) UniqueComponent.push(CompOnetbl[i]);
        }
        for(var i = 0; i < ColorOnetbl.length; i++) {
            if(jQuery.inArray(ColorOnetbl[i],UniqueColor) === -1) UniqueColor.push(ColorOnetbl[i]);
        }

        for(var i = 0; i < UniqueComponent.length; i++) {
            if(UniqueComponent[i].indexOf('/') >= 0) {
                var SplitComponent = UniqueComponent[i].split('/');
                SplittedComponent.push(SplitComponent);
            }
        }
/*
        console.log(SplittedComponent,'SplittedComponent');
        console.log(UniqueComponent,'UniqueComponent');
*/
        var allcomponent = [].concat.apply(UniqueComponent,SplittedComponent);
//        console.log(allcomponent,'allcomponent');

        for(var i = 0; i < UniqueColor.length; i++) {
            if(UniqueColor[i].indexOf('/') >= 0) {
                var SplitColor = UniqueColor[i].split('/');
                SplittedColor.push(SplitColor);
            }
        }

        var allcolor = [].concat.apply(UniqueColor,SplittedColor);
        var PonoFromTwo = $("#PoWiseQtyBrkUpSecondTbl").jexcel('getColumnData',3);
        var SizespeccodeFromFifth = $("#ItemizedSizeWiseQtyBrkUpFifthTbl").jexcel('getColumnData',3);
        var OeneTbl = $("#divComboColorFirstTable").jexcel('getData');
        var TwoTbl = $("#PoWiseQtyBrkUpSecondTbl").jexcel('getData');
        var ThreeTbl = $("#PoWiseItemizedQtyThirdTbl").jexcel('getData');
        var FourTbl = $("#PoWiseDeliverySchdFourthTbl").jexcel('getData');
        var FiveTbl = $("#ItemizedSizeWiseQtyBrkUpFifthTbl").jexcel('getData');
        /*var ArrfilterdFiveTbl = [];
        $.each(FiveTbl, function(key, value){
            var filterFiveTbl = value.filter(Boolean);
            //console.log(filterFiveTbl,'filterFiveTbl');
            ArrfilterdFiveTbl.push(filterFiveTbl);
        });
        console.log(ArrfilterdFiveTbl,'ArrfilterdFiveTbl');*/
        var SixATbl = $("#SixthATbl").jexcel('getData');
        var SixthCratioTbl = $("#CuttingRatioSixthTbl").jexcel('getData');
        var SevenKnitTbl = $("#divFabricKnitTable").jexcel('getData');
        var SevenKnitTbl = $("#divFabricKnitTable").jexcel('getData');

        /*console.log(ComboOnetbl,'ComboOnetbl');
        console.log(typeof ComboOnetbl,'type');
        console.log(JSON.stringify(ComboOnetbl),'combo');
        console.log(typeof JSON.stringify(ComboOnetbl),'combo typ');*/
        var Param = GlbParam+"&tid="+GlbTeamId+"&mid="+MerchantId+"&isriorno="+frmIsrior+"&isriortype="+frmOrderType+"&orefno="+frmOrderRefNo+"&stylerefno="+frmStyleRefNo+"&stylename="+
            frmStyleName+"&brandid="+frmBrandId+"&buyerid="+frmBuyerId+"&season="+frmSeason+"&division="+frmDivision+"&class="+frmClass+"&subclass="+frmSubclass+"&totalqty="+frmTotalQty+"&pcsset="+
            frmPcsset+"&ppunit="+frmPricePerUnit+"&currency="+frmCurrency+"&pterms="+frmPaymentTerms+"&sizectype="+SizeChartType+"&sizeclist="+SizeChartList+"&cbo="+
            JSON.stringify(ComboOnetbl)+"&comp="+JSON.stringify(allcomponent)+"&colr="+JSON.stringify(allcolor)+"&oe_one="+JSON.stringify(OeneTbl)+"&oe_ponoenqrefno="+
            JSON.stringify(PonoFromTwo)+"&oe_sizespeccode="+JSON.stringify(SizespeccodeFromFifth)+"&oe_two="+JSON.stringify(TwoTbl)+"&oe_three="+JSON.stringify(ThreeTbl)+
            "&oe_four="+JSON.stringify(FourTbl)+"&oe_five="+JSON.stringify(FiveTbl)+"&oe_sixthatbl="+JSON.stringify(SixATbl)+"&oe_sixthCratioTbl="+
            JSON.stringify(SixthCratioTbl)+"&oe_sevenknitting="+JSON.stringify(SevenKnitTbl);
        //MakePostRequest(base_path+GlbCompanyFdr+'/msizerange/managesizerange',GlbSearchParam,'json',fnListSizeRangeRes);
        //MakeAsynPostRequest(base_path+GlbCompanyFdr+'orderentry/updateinfo',Param,'json',fnSaveOrderEntryRes);
        $.ajax({
            url 		: base_path+GlbCompanyFdr+'orderentry/updateinfo',
            data        : Param,
            type        : 'POST',
            success     : function(data, textStatus, jqXHR){
                data = JSON.parse(data);
                fnSaveOrderEntryRes(data);
            }
        });
        return false;
    }
    catch (e) {
        alert(e,'catch err');
    }

}

function fnSaveOrderEntryRes(data) {
    if(data!='') {
        if(data.errcode == '404') {
            fnCallSessionExpire();
            return false;
        } else if(data.errcode==-1){
            $("#ErrOrderEntry").removeClass('hide');
            $('#ErrOrderEntry').text(data.msg);
            return false;
        } else if(data.errcode==1) {
            GlbId       = data.id;
            $("#divSuccessBasicInfoMsg").removeClass('hide');
            $("#divSuccessBasicInfoMsg").text("ORDER ENTRY has been updated successfully!");
            //fnRedirectPageTimeOut(base_path+GlbCompanyFdr+'dashboard');
        }
    }
}