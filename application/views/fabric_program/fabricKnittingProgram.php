<?php
$this->load->view(CNFCOMPANY.'template/pageheader');
?>
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/jexcel4/jsuites.css"/>
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/jexcel4/jexcel.css"/>
    <body class="sidebar-mini skin-black wysihtml5-supported sidebar-collapse">
<style>

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
        <div class="col-md-12">
            <section class="content-header">
                <h1 class="firstHeading">
                    Fabric Programme
                </h1>
            </section>
        </div>
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-info">
                        <div class="box-body" style="padding: 0">
                            <form class="form-horizontal" name="frmBasicInfo" id="frmOrderProcess" method="post">
                                <?php $this->load->view("fabric_program/fabricProPaginationLinks"); ?>
                                <div class="col-md-12 no-padding">
                                    <div style="background-color: rgb(210 253 249); padding-top: 10px; padding-left: 10px">
                                        <strong>CONSOLIDATED KNITTING PROGRAMME</strong>
                                    </div>
                                </div>

                                <div class="col-sm-12 table-responsive" style="padding: 5px !important">
                                    <div id="fourteen"></div>
                                </div>
                            </form>
                            <?php $this->load->view("fabric_program/fabricProFooterLinks"); ?>
                        </div><!-- /.box-body -->
                    </div>
                </div>
            </div>
        </section>
    </div><!-- /.content-wrapper -->
    <?php $this->load->view(CNFCOMPANY.'template/templatefooter');?>
    <div class="control-sidebar-bg"></div>
</div><!-- ./wrapper -->
<script src="<?php echo base_url(); ?>assets/js/jexcel4/jsuites.js"></script>
<script src="<?php echo base_url(); ?>assets/js/jexcel4/jexcel.js"></script>
<script>
    const enquiryId = '<?php echo $VarEnquiryId ?>';
    const HashEnquiryId = '<?php echo $VarHashEnquiryId ?>';
    const pageId = '<?php echo $VarPageId ?>';
    unsaved = false;
    const GlbParam = 'rFrom=1';
    GlbYarnProgram = '';
    // A custom method to SUM all the cells in the current column
    SUMCOL = function(instance, columnId) {
        var total = 0;
        for (var j = 0; j < instance.options.data.length; j++) {
            if (Number(instance.records[j][columnId-1].innerHTML)) {
                total += Number(instance.records[j][columnId-1].innerHTML);
            }
        }
        return total.toFixed(3);
    };
    function fnPopulateValueArray(ArrName, KeyValue, InsertVal) {
        if (jQuery.inArray(KeyValue, ArrName)) {
            ArrName[KeyValue] = ArrName[KeyValue]+"-"+InsertVal;
        }
        return ArrName;
    }
    GlbFabRequirementTbl = [];
    //Fab Requirement Data
    MakePostRequest(base_path+"fabricprogram/ajaxData",GlbParam+"&enqId="+enquiryId+"&pid=fabRequirement","json",function (data) {
        console.log(data,'data');
        var GlbSeparators = ['-', '##']; var sevenAJxl = {};
        var objAllSizesDiaDim = {}; var GlbCurrentJxlTbl = []; var gapJxlArr = []; var joinedVal = [];
        ArrSizes = data.ArrSizeChart;
        let GlbFabricFinish = JSON.parse(data.jsonFabricFinish);
        var objDiaDimensionGroup = []; var newObjDiaDimensionGroup = [];
        let prevColPlusSize = ArrSizes.length + 5;
        var objEightJxl = {}; var diaDimension = [];
        let ArrProcessLoss = [];
        if (data.jsonConCalcProcessLoss != "") {
            conCalcProcessLoss = JSON.parse(data.jsonConCalcProcessLoss);
            console.log(conCalcProcessLoss,'conCalcProcessLoss');
            for (let ii = 0; ii < conCalcProcessLoss.length; ii++) {
                let com = conCalcProcessLoss[ii][1];
                let parts = conCalcProcessLoss[ii][3];
                let ssc = conCalcProcessLoss[ii][5];
                if(jQuery.inArray(com+"|#|"+parts+"|#|"+ssc,ArrProcessLoss) === -1) {
                    ArrProcessLoss.push(com+"|#|"+parts+"|#|"+ssc);
                }

            }
            if (data.savedDiaDimension != "") {
                let savedData = JSON.parse(data.savedDiaDimension);
                for (let ii = 0; ii < ArrProcessLoss.length; ii++) {
                    let ini = ArrProcessLoss[ii].split('|#|');
                    if(savedData[ii]) {
                        let newArr = ini.concat(savedData[ii]);
                        console.log(newArr,'newArr');
                        diaDimension.push(newArr);
                    }
                }
            }
        }

        for(let ii = 0; ii < diaDimension.length; ii++) {
            for(let a = 5; a < prevColPlusSize; a++) {
                objDiaDimensionGroup = fnPopulateValueArray(objDiaDimensionGroup,
                    diaDimension[ii][0]+"##"+diaDimension[ii][1]+"##"+diaDimension[ii][2],diaDimension[ii][a]);
                newObjDiaDimensionGroup = fnPopulateValueArray(newObjDiaDimensionGroup,diaDimension[ii][0]+"##"+diaDimension[ii][1],
                    diaDimension[ii][a]);
            }
        }
        for(let prop in objDiaDimensionGroup) {
            let newStr = objDiaDimensionGroup[prop].split(new RegExp(GlbSeparators.join('|'),'g'));
            newStr = newStr.filter(function (val) {
                return val !== "undefined";
            });
            let uniqueSizes = getUnique(newStr);
            for(let ii = 0; ii < uniqueSizes.length; ii++) {
                objAllSizesDiaDim[prop] = uniqueSizes;
            }
        }

        var ArrDiaPositions = {}; var objUnitOfMeasure = {};
        for(let ii = 0; ii < diaDimension.length; ii++) {
            let oneDimArrEightJxl = diaDimension[ii];
            let diaArr = objAllSizesDiaDim[diaDimension[ii][0]+"##"+diaDimension[ii][1]+"##"+diaDimension[ii][2]];
            for(let kk = 0; kk < diaArr.length; kk++) {
                let comp = oneDimArrEightJxl[0];
                let parts = oneDimArrEightJxl[1];
                let uom = oneDimArrEightJxl[4];
                let res = getAllIndexes(oneDimArrEightJxl,diaArr[kk]);
                if(res.length > 0) {
                    ArrDiaPositions[diaDimension[ii][0]+"##"+diaDimension[ii][1]+"##"+diaDimension[ii][2]+"##"+diaArr[kk]] = res;
                    objUnitOfMeasure[comp+"|#|"+parts] = uom;
                }
            }
        }
        if(data.sevenAJxl != "") {
            sevenAJxl = JSON.parse(data.sevenAJxl);
            var SumAllDiaMixed = {};
            for(let ii = 0; ii < sevenAJxl.length; ii++) {
                for(let prop in ArrDiaPositions) {
                    let leftSide = prop.split('##');
                    if(sevenAJxl[ii][1]+"##"+sevenAJxl[ii][3]+"##"+sevenAJxl[ii][4] == leftSide[0]+"##"+leftSide[1]+"##"+leftSide[2]) {
                        let ArrPosition = ArrDiaPositions[prop];
                        var sumDiaSize = 0;
                        for(let jj = 0; jj < ArrPosition.length; jj++) {

                            let originalPos = ArrPosition[jj];
                            sumDiaSize += parseFloat(sevenAJxl[ii][originalPos]);
                            SumAllDiaMixed[sevenAJxl[ii][1]+"##"+sevenAJxl[ii][3]+"##"+sevenAJxl[ii][4]+"##"+leftSide[3]] = sumDiaSize;
                        }
                    }
                }
            }
            for(let prop in SumAllDiaMixed) {
                let keys = prop.split('##');
                var uniqueStr = keys[0]+"##"+keys[1]+"##"+keys[3];
                joinedVal = fnPopulateValueArray(joinedVal, uniqueStr,SumAllDiaMixed[prop]);
            }
        }

        var newObjAllSizesDiaDim = {};
        for(let prop in newObjDiaDimensionGroup) {
            let newStr = newObjDiaDimensionGroup[prop].split(new RegExp(GlbSeparators.join('|'),'g'));
            newStr = newStr.filter(function (val) {
                return val !== "undefined";
            });
            let newUniqueSizes = getUnique(newStr);

            for(let ii = 0; ii < newUniqueSizes.length; ii++) {
                newObjAllSizesDiaDim[prop] = newUniqueSizes;
            }
        }
        var subTotal = 0; var planFabWgt = 0;
        for(let ii = 0; ii < GlbFabricFinish.length; ii++) {
            subTotal = 0;
            let diaDimArr = newObjAllSizesDiaDim[GlbFabricFinish[ii][1]+"##"+GlbFabricFinish[ii][3]];
            if(diaDimArr) {
                if (diaDimArr.length > 0) {
                    for (let jj = 0; jj < diaDimArr.length; jj++) {
                        let comp = GlbFabricFinish[ii][1];
                        let parts = GlbFabricFinish[ii][3];
                        let uom = objUnitOfMeasure[comp+"|#|"+parts];
                        planFabWgt = fnSumSizeArrayValue(joinedVal[GlbFabricFinish[ii][1] + "##" + GlbFabricFinish[ii][3] + "##" + diaDimArr[jj]]);
                        let yy = GlbFabricFinish[ii][1] + "##" + GlbFabricFinish[ii][2] + "##" + GlbFabricFinish[ii][3];
                        if (jQuery.inArray(yy, gapJxlArr) === -1) {
                            gapJxlArr.push(GlbFabricFinish[ii][1] + "##" + GlbFabricFinish[ii][2] + "##" + GlbFabricFinish[ii][3]);
                            GlbFabRequirementTbl.push([GlbFabricFinish[ii][0], GlbFabricFinish[ii][1], GlbFabricFinish[ii][2],
                                GlbFabricFinish[ii][3], GlbFabricFinish[ii][4], GlbFabricFinish[ii][5],
                                GlbFabricFinish[ii][6], GlbFabricFinish[ii][7], GlbFabricFinish[ii][8],
                                GlbFabricFinish[ii][9], diaDimArr[jj], uom, planFabWgt, "", ""]);
                        }
                        else {
                            if (diaDimArr[jj])
                                GlbFabRequirementTbl.push(["", "", "", "", "", "", "", "", "", "", diaDimArr[jj], uom, planFabWgt, "", ""]);
                        }
                        subTotal += Number(planFabWgt);
                    }
                    GlbFabRequirementTbl.push(["", "", "", "", "", "", "", "", "", "", "", "", "", GlbFabricFinish[ii][0] + "|#|" + GlbFabricFinish[ii][1] + "|#|" + GlbFabricFinish[ii][3],
                        subTotal.toFixed(3)]);
                }
            }
        }
        console.log(GlbFabRequirementTbl,'GlbFabRequirementTbl');

    });

    function fnSumSizeArrayValue(ArrSizeVal) {
        if(ArrSizeVal) {
            //console.log(ArrSizeVal,'ArrSizeVal');
            let newSizeVal = ArrSizeVal.substr(10);
            let SumVal = 0;
            let ArrName = newSizeVal.split("-");
            if(ArrName.length > 0) {
                for (let i = 0; i < ArrName.length; i++) {
                    SumVal = parseFloat(ArrName[i]) + SumVal;
                }
            }
            return SumVal.toFixed(3);
        }

    }
    function getAllIndexes(arr, val) {
        let indexes = [], i = -1;
        while ((i = arr.indexOf(val, i+1)) != -1){
            indexes.push(i);
        }
        return indexes;
    }
    //Fab Requirement Data ENDS
    MakeAsynPostRequest(base_path+"fabricprogram/ajaxData",GlbParam+"&enqId="+enquiryId+"&pid="+pageId,"json",function (data) {
        console.log(data, 'data');
        if(data.yarnPgm != '') {
            console.log(data.yarnPgm,'data.yarnPgm');
            GlbYarnProgram = JSON.parse(data.yarnPgm);
        }
        console.log(GlbYarnProgram,'GlbYarnProgram');
        var currentJxlTbl = [];
        var GlbKnitMachineMake = ["-","Mayer & Cie","Pailung","Terrot","KH-112 F","KH-323 D","KH-323 DJ"];
        var GlbGauge = ["14","16","18","20","24","26","28"];
        var GlbKnittingType = ["PF - Tubular","PF - Open Width","FS - Tubular","FS - Open Width","AS - Tubular","AS - Open Width","JQ - Tubular","JQ - Open Width"];


        var otherArr = [];
        var nineJxlFilter = [];
        var objNineFdJxl = {};
        var finalCurrentJxlTbl = [];

        if(GlbFabRequirementTbl != "") {
            let nineJxl = GlbFabRequirementTbl;
            //var otherTempArr = [];
            let finDiaDimPlanFabWgtArr = [];
            for(let ii = 0; ii < nineJxl.length; ii++) {
                if(nineJxl[ii][10] !== "" && nineJxl[ii][12] !== "") {
                    finDiaDimPlanFabWgtArr.push(nineJxl[ii][10]+"##"+nineJxl[ii][12]);
                }
                else {
                    otherArr.push(finDiaDimPlanFabWgtArr);
                    finDiaDimPlanFabWgtArr = [];
                }
                if(nineJxl[ii][0] != "") {
                    nineJxlFilter.push(nineJxl[ii][0]+"##"+nineJxl[ii][1]+"##"+nineJxl[ii][2]+"##"+nineJxl[ii][3]+"##"+
                        nineJxl[ii][4]+"##"+nineJxl[ii][5]+"##"+nineJxl[ii][6]+"##"+nineJxl[ii][7]+"##"+nineJxl[ii][8]+"##"+nineJxl[ii][9]+"##"+nineJxl[ii][11]);
                }
            }
            console.log(finDiaDimPlanFabWgtArr,'finDiaDimPlanFabWgtArr');
            console.log(otherArr,'otherArr');
            console.log(nineJxlFilter,'nineJxlFilter');
            for(let ii = 0; ii < nineJxlFilter.length; ii++) {
                let main = nineJxlFilter[ii].split('##');
                objNineFdJxl[main[0]+"##"+main[1]+"##"+main[2]+"##"+main[3]+"##"+main[4]+"##"+main[5]+"##"+
                main[6]+"##"+main[7]+"##"+main[8]+"##"+main[9]+"##"+main[10]] = otherArr[ii];
            }
            //console.log(objNineFdJxl,'objNineFdJxl');
            var nineJxlFilterAll = []; var diaDimArr = [];
            for(let prop in objNineFdJxl) {
                let leftSide = prop.split('##');
                let values = objNineFdJxl[prop];
                //console.log(values,'values');
                diaDimArr = [];
                for(let ii = 0; ii < values.length; ii++) {
                    let diaDim = values[ii].substr(0,values[ii].indexOf('##'));
                    //console.log(diaDim,'diaDim');
                    let planedFabWeight = values[ii].substr(values[ii].indexOf('##')+2);
                    //console.log(planedFabWeight,'planedFabWeight');
                    diaDimArr.push(diaDim);
                    nineJxlFilterAll.push([leftSide[0],leftSide[1],leftSide[2],leftSide[3],leftSide[4],leftSide[5],
                        leftSide[6],leftSide[7],leftSide[8],leftSide[9],leftSide[10],diaDim,planedFabWeight,diaDimArr]);

                }
            }
            console.log(nineJxlFilterAll,'nineJxlFilterAll');
            var GlbFDKnit = {}; var GlbFDKnitEnds = {};
            var GlbSDBKnit = {}; var GlbSDBKnitEnds = {};
            var GlbYDKnit = {}; var GlbYDKnitEnds = {};
            var GlbYDJKnit = {}; var GlbYDJKnitEnds = {};
            for(let ii = 0; ii < nineJxlFilterAll.length; ii++) {
                let fReq = nineJxlFilterAll[ii];
                let dyeingType = fReq[9];
                let colors = fReq[2];
                console.log(colors,'colors');
                let ArrColor = colors.split(':');
                if(ArrColor.length > 0) {
                    for(let cc = 0; cc < ArrColor.length; cc++) {
                        splitedColor = jsTrim(ArrColor[cc]);
                        if(dyeingType === "FD") {
                            console.log(GlbYarnProgram,'GlbYarnProgram FD');
                            console.log(fReq[0]+"##"+fReq[1]+"##"+splitedColor+"##"+fReq[3]+"##"+fReq[9],'fReq[0]+"##"+fReq[1]+"##"+splitedColor+"##"+fReq[3]+"##"+fReq[9]');
                            let yarnColor = GlbYarnProgram[fReq[0]+"##"+fReq[1]+"##"+splitedColor+"##"+fReq[3]+"##"+fReq[9]];
                            console.log(yarnColor,'yarnColor FD ArrColor.length > 0');
                            GlbFDKnit = fnPopulateValueArray(GlbFDKnit,
                                yarnColor+"##"+fReq[3]+"##"+fReq[4]+"##"+fReq[5]+"##"+fReq[6]+"##"+fReq[7]+"##"+fReq[8]+
                                "##"+fReq[9]+"##"+fReq[10]+"##"+fReq[11],
                                fReq[12]
                            );

                            GlbFDKnitEnds[yarnColor+"##"+fReq[3]+"##"+fReq[4]+"##"+
                            fReq[5]+"##"+fReq[6]+"##"+fReq[7]+"##"+fReq[8]+"##"+fReq[9]+"##"+fReq[10]+"##"+fReq[11]] = fReq[13];
                        }

                        else if(dyeingType === "SDB") {
                            let yarnColor = GlbYarnProgram[fReq[0]+"##"+fReq[1]+"##"+splitedColor+"##"+fReq[3]+"##"+fReq[9]];
                            console.log(yarnColor,'yarnColor SDB');
                            GlbSDBKnit = fnPopulateValueArray(GlbSDBKnit,
                                yarnColor+"##"+fReq[3]+"##"+fReq[4]+"##"+fReq[5]+"##"+fReq[6]+"##"+fReq[7]+"##"+fReq[8]+
                                "##"+fReq[9]+"##"+fReq[10]+"##"+fReq[11],
                                fReq[12]
                            );

                            GlbSDBKnitEnds[yarnColor+"##"+fReq[3]+"##"+fReq[4]+"##"+
                            fReq[5]+"##"+fReq[6]+"##"+fReq[7]+"##"+fReq[8]+"##"+fReq[9]+"##"+fReq[10]+"##"+fReq[11]] = fReq[13];
                        }
                        else {
                        }
                    }
                }
                else {
                    //Single splitedColor in Yarn Program splitedColor Col
                    if(dyeingType === "FD") {
                        console.log(GlbYarnProgram,'GlbYarnProgram FD');
                        let yarnColor = GlbYarnProgram[fReq[0]+"##"+fReq[1]+"##"+colors+"##"+fReq[3]+"##"+fReq[9]];
                        console.log(yarnColor,'yarnColor FD SINGLE splitedColor');
                        GlbFDKnit = fnPopulateValueArray(GlbFDKnit,yarnColor+"##"+fReq[3]+"##"+fReq[4]+"##"+
                            fReq[5]+"##"+fReq[6]+"##"+fReq[7]+"##"+fReq[8]+"##"+fReq[9]+"##"+fReq[10]+"##"+
                            fReq[11],fReq[12]);

                        GlbFDKnitEnds[yarnColor+"##"+fReq[3]+"##"+fReq[4]+"##"+
                        fReq[5]+"##"+fReq[6]+"##"+fReq[7]+"##"+fReq[8]+"##"+fReq[9]+"##"+fReq[10]+"##"+fReq[11]] = fReq[13];
                    }

                    else if(dyeingType === "SDB") {
                        let yarnColor = GlbYarnProgram[fReq[0]+"##"+fReq[1]+"##"+colors+"##"+fReq[3]+"##"+fReq[9]];
                        console.log(yarnColor,'yarnColor SDB SINGLE splitedColor');
                        GlbSDBKnit = fnPopulateValueArray(GlbSDBKnit,yarnColor+"##"+fReq[3]+"##"+fReq[4]+"##"+
                            fReq[5]+"##"+fReq[6]+"##"+fReq[7]+"##"+fReq[8]+"##"+fReq[9]+"##"+fReq[10]+"##"+
                            fReq[11],fReq[12]);

                        GlbSDBKnitEnds[yarnColor+"##"+fReq[3]+"##"+fReq[4]+"##"+
                        fReq[5]+"##"+fReq[6]+"##"+fReq[7]+"##"+fReq[8]+"##"+fReq[9]+"##"+fReq[10]+"##"+fReq[11]] = fReq[13];
                    }
                    else {
                    }

                }
                if(dyeingType === "YDS") {
                    GlbYDKnit=fnPopulateValueArray(GlbYDKnit,
                        colors+"##"+fReq[3]+"##"+fReq[4]+"##"+fReq[5]+"##"+fReq[6]+"##"+fReq[7]+
                        "##"+fReq[8]+"##"+fReq[9]+"##"+fReq[10]+"##"+fReq[11],
                        fReq[12]
                    );

                    GlbYDKnitEnds[colors+"##"+fReq[3]+"##"+fReq[4]+"##"+
                    fReq[5]+"##"+fReq[6]+"##"+fReq[7]+"##"+fReq[8]+"##"+fReq[9]+"##"+fReq[10]+"##"+fReq[11]] = fReq[13];
                }
                if(dyeingType === "YDJ") {
                    GlbYDJKnit = fnPopulateValueArray(GlbYDJKnit,
                        colors+"##"+fReq[3]+"##"+fReq[4]+"##"+fReq[5]+"##"+fReq[6]+"##"+fReq[7]+"##"+fReq[8]+
                        "##"+fReq[9]+"##"+fReq[10]+"##"+fReq[11],
                        fReq[12]
                    );

                    GlbYDJKnitEnds[colors+"##"+fReq[3]+"##"+fReq[4]+"##"+
                    fReq[5]+"##"+fReq[6]+"##"+fReq[7]+"##"+fReq[8]+"##"+fReq[9]+"##"+fReq[10]+"##"+fReq[11]] = fReq[13];
                }
            }
            //console.log(nineJxlFilterAll,'nineJxlFilterAll');
            console.log(GlbFDKnit,'GlbFDKnit MDV');
            console.log(GlbSDBKnit,'GlbSDBKnit MDV');
            console.log(GlbFDKnitEnds,'GlbFDKnitEnds');
            console.log(GlbYDKnit,'GlbYDKnit');
            for(let prop in GlbFDKnit) {
                let leftSide  = prop.split('##');
                console.log(leftSide,'leftSide');
                let rightSide = GlbFDKnit[prop];
                var pfWeight = 0;
                if(rightSide) {
                    var newSizeVal = rightSide.substr(10);
                    var SumVal = 0;
                    var ArrName = newSizeVal.split("-");
                    if(ArrName.length > 0) {
                        for (var i = 0; i < ArrName.length; i++)
                            SumVal = parseFloat(ArrName[i]) + SumVal;
                    }
                    pfWeight = SumVal.toFixed(3);
                }
                let diaArr = GlbFDKnitEnds[prop];
                let yarnColorFromYarnPro = leftSide[0];

                currentJxlTbl.push([leftSide[1],leftSide[2],leftSide[3],leftSide[4],leftSide[5],leftSide[6],leftSide[7],yarnColorFromYarnPro,leftSide[9],
                    leftSide[8],pfWeight,diaArr]);
            }
            for(let sdbProp in GlbSDBKnit) {
                let leftSide = sdbProp.split('##');
                console.log(leftSide,'leftSide');
                let rightSide = GlbSDBKnit[sdbProp];
                let pfWeight = 0;
                if(rightSide) {
                    let planFabWgtStr = rightSide.replace('undefined-','');
                    let sumVal = 0;
                    let Arr = planFabWgtStr.split("-");
                    if(Arr.length > 0) {
                        for(let a = 0; a < Arr.length; a++)
                            sumVal = parseFloat(Arr[a]) + sumVal;
                    }
                    pfWeight = sumVal.toFixed(3);
                }
                let diaArr = GlbSDBKnitEnds[sdbProp];
                let yarnColorFromYarnPro = leftSide[0];
                currentJxlTbl.push([leftSide[1],leftSide[2],leftSide[3],leftSide[4],leftSide[5],leftSide[6],leftSide[7],yarnColorFromYarnPro,
                    leftSide[9],leftSide[8],pfWeight,diaArr]);
            }
            for(let prop in GlbYDKnit) {
                let leftSide = prop.split('##');
                let rightSide = GlbYDKnit[prop];
                var pfWeight = 0; subTotal = 0;
                if(rightSide) {
                    var newSizeVal = rightSide.substr(10);
                    var SumVal = 0;
                    var ArrName = newSizeVal.split("-");
                    if(ArrName.length > 0) {
                        for (var i = 0; i < ArrName.length; i++) {
                            SumVal = parseFloat(ArrName[i]) + SumVal;
                        }
                    }
                    pfWeight = SumVal.toFixed(3);
                    //console.log(pfWeight,'pfWeight');
                }
                let diaArr = GlbYDKnitEnds[prop];
                currentJxlTbl.push([leftSide[1],leftSide[2],leftSide[3],leftSide[4],leftSide[5],leftSide[6],leftSide[7],leftSide[0],
                    leftSide[9],leftSide[8],pfWeight,diaArr]);
                //console.log(currentJxlTbl,'currentJxlTbl TEST');
            }
            console.log(GlbYDJKnit,'GlbYDJKnit');
            for(let propYDJ in GlbYDJKnit) {
                let leftSide = propYDJ.split('##');
                let rightSide = GlbYDJKnit[propYDJ];
                var pfWeight = 0; subTotal = 0;
                if(rightSide) {
                    var newSizeVal = rightSide.substr(10);
                    var SumVal = 0;
                    var ArrName = newSizeVal.split("-");
                    if(ArrName.length > 0) {
                        for (var i = 0; i < ArrName.length; i++) {
                            SumVal = parseFloat(ArrName[i]) + SumVal;
                        }
                    }
                    pfWeight = SumVal.toFixed(3);
                    //console.log(pfWeight,'pfWeight');
                }
                let diaArr = GlbYDJKnitEnds[propYDJ];
                currentJxlTbl.push([leftSide[1],leftSide[2],leftSide[3],leftSide[4],leftSide[5],leftSide[6],leftSide[7],leftSide[0],
                    leftSide[9],leftSide[8],pfWeight,diaArr]);
                //console.log(currentJxlTbl,'currentJxlTbl TEST');
            }
            var TestSum = 0; var ArrFilter = [];
            console.log(currentJxlTbl,'currentJxlTbl SECOND');
            for(let ii = 0; ii < currentJxlTbl.length; ii++) {
                console.log(currentJxlTbl[ii],'currentJxlTbl[ii]');
                let arr = currentJxlTbl[ii][11];
                console.log(arr,'arr');
                var len = arr.length;
                console.log(len,'arr LEN');
                console.log(currentJxlTbl[ii][8],'currentJxlTbl[ii][8]');
                console.log(ii,'ii');
                var yyy = currentJxlTbl[ii][0]+"##"+currentJxlTbl[ii][1]+"##"+currentJxlTbl[ii][2]+"##"+
                    currentJxlTbl[ii][3]+"##"+currentJxlTbl[ii][4];
                console.log(yyy,'yyy TEST');
                if(jQuery.inArray(yyy,ArrFilter) === -1)
                    ArrFilter.push(yyy);

                finalCurrentJxlTbl.push([currentJxlTbl[ii][0],currentJxlTbl[ii][1],currentJxlTbl[ii][2],
                    currentJxlTbl[ii][3],currentJxlTbl[ii][4],currentJxlTbl[ii][5],currentJxlTbl[ii][6],currentJxlTbl[ii][7],"","","",currentJxlTbl[ii][8],
                    currentJxlTbl[ii][9],currentJxlTbl[ii][10]]);

                TestSum += Number(currentJxlTbl[ii][10]);
                if(currentJxlTbl[ii][8] == arr[len - 1]) {
                    finalCurrentJxlTbl.push(["","","","","","","","","","","","","","",TestSum.toFixed(3)]);
                    TestSum = 0;
                }
                else {

                }

            }
        }
        if(data.consKnittingProgram != "") {
            finalCurrentJxlTbl = JSON.parse(data.consKnittingProgram);
        }

        jexcel(document.getElementById("fourteen"),{
            columns:[
                { title:'Garment Parts', width:110, readOnly: true },
                { title:'Fabric Blend (%)', width:110, readOnly: true },
                { title:'Fabric Content', width:105, readOnly: true },
                { title:'Fabric Name', width:100, readOnly: true },
                { title:'Fin. GSM', width:100, readOnly: true },
                { title:'Yarn Count', width:70, readOnly: true },
                { title:'Dyeing Type', width:70, readOnly: true },
                { title:'Yarn Color', width:70, readOnly: true },
                { type: 'dropdown', source: GlbKnitMachineMake, title:'Pref. Knitting Machine Make', width:100 },
                { type: 'dropdown', source: GlbGauge, title:'Gauge', width:70 },
                { type: 'dropdown', source: GlbKnittingType, title:'Knitting Type', width:70 },
                { title:'Fin. DIA / DIM\n(W * H)', width:100, readOnly: true },
                { title:'Unit of Measure', width:80, readOnly: true },
                { title:'Plan. Fab. Wgt. (Kgs.)', width: 100, readOnly: true },
                { title:'Plan. Fab. Wgt. - Subtotal (Kgs.)', width: 100, readOnly: true },
            ],
            footers: [['','','','','','','','','','','','','','Total','=SUMCOL(TABLE(), COLUMN())']],
            data: finalCurrentJxlTbl,
            updateTable:function(instance, cell, col, row, val, label, cellName) {
                if (col == 0) {
                    combo = $(cell).text();
                }
                if (col == 1) {
                    comp = $(cell).text();
                }
                if (col == 2) {
                    colors = $(cell).text();
                }
                if (col == 3) {
                    gParts = $(cell).text();
                }
                if (col == 10) {
                    planFabWgt = val;
                }
                if (col == 11) {
                    lycraPercent = val;
                }
                if (col == 12) {
                    /*lycraWgt = planFabWgt * (Number(lycraPercent) / 100)
                    $(cell).text(lycraWgt.toFixed(3));
                    instance.jexcel.options.data[row][col] = lycraWgt.toFixed(3);*/
                }
                if (col == 13) {
                    /*reqFabWgt = Number(planFabWgt) - Number(lycraWgt);
                    $(cell).text(reqFabWgt.toFixed(3));
                    instance.jexcel.options.data[row][col] = reqFabWgt.toFixed(3);*/
                }
            },
            columnDrag:true,
            allowInsertRow:false,
            allowInsertColumn:false,
            minDimensions:[15,1],
            onchange:function () {
                unsaved = true;
            }
        });
    });


    function cmnSaveChanges() {
        const jxlData = $("#fourteen").jexcel('getData');
        MakePostRequest(base_path+"fabricprogram/saveKnittingProgram","rFrom=1&enqId="+enquiryId+
            "&d="+encodeURIComponent(JSON.stringify(jxlData))+"&pid="+pageId,"json",fnSaveRes);
        function fnSaveRes(data) {
            console.log(data,'data');
            if(data.errCode == 1) {
                unsaved = false;
                $("#divCmnSuccessMsg").removeClass("hide");
                $("#divCmnSuccessMsg").text("saved Successfully");
            }
        }
    }
    function unloadPage() {
        if (unsaved) {
            return "You have unsaved changes on this page. Do you want to leave this page and discard your changes or stay on this page?";
        }
    }
    window.onbeforeunload = unloadPage;
</script>
<?php $this->load->view(CNFCOMPANY.'template/pagefooter'); ?>