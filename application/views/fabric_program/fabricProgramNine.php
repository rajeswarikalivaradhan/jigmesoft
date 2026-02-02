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
    /*    td div {
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
        }*/
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
                        <div class="box-body" style="padding: 0;">
                            <form class="form-horizontal" name="frmBasicInfo" id="frmOrderProcess" method="post">
                                <?php $this->load->view("fabric_program/fabricProPaginationLinks"); ?>
                                <div class="col-md-12 no-padding">
                                    <div style="background-color: rgb(210 253 249); padding-top: 10px; padding-left: 10px">
                                        <strong>ITEMIZED FABRIC REQUIREMENT DETAILS COLOUR WISE & DIA WISE</strong>
                                    </div>
                                </div>
                                <div class="col-sm-12 table-responsive" style="padding: 5px !important">
                                    <div id="nine"></div>
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
    const pageId = '<?php echo $VarPageId ?>';
    const GlbParam = 'rFrom=1';
    var GlbSeparators = ['-', '##']; var sevenAJxl = {};
    var objAllSizesDiaDim = {}; var GlbCurrentJxlTbl = []; var gapJxlArr = []; var joinedVal = [];
    // A custom method to SUM all the cells in the current column
    var SUMCOL = function(instance, columnId) {
        var total = 0;
        for (var j = 0; j < instance.options.data.length; j++) {
            //console.log(instance.records[j][columnId-1].innerHTML,'instance.records[j][columnId-1].innerHTML');
            if (Number(instance.records[j][columnId-1].innerHTML)) {
                total += Number(instance.records[j][columnId-1].innerHTML);
            }
        }
        return total.toFixed(3);
    };
    var diaDimension = [];

    MakeAsynPostRequest(base_path+"fabricprogram/ajaxData",GlbParam+"&enqId="+enquiryId+"&pid="+pageId,"json",function (data) {
        console.log(data, 'data');
        ArrSizes = data.ArrSizeChart;
        let ArrProcessLoss = [];
        let conCalcProcessLoss = ''; let objEightJxl = {};
        if (data.jsonConCalcProcessLoss != "") {
            conCalcProcessLoss = JSON.parse(data.jsonConCalcProcessLoss);
            console.log(conCalcProcessLoss,'conCalcProcessLoss');
            for (let ii = 0; ii < conCalcProcessLoss.length; ii++) {
                let com = conCalcProcessLoss[ii][1];
                let parts = conCalcProcessLoss[ii][3];
                let ssc = conCalcProcessLoss[ii][5];
                if(jQuery.inArray(com+"|#|"+parts+"|#|"+ssc,ArrProcessLoss) === -1) {
                    ArrProcessLoss.push(jsTrim(com)+"|#|"+parts+"|#|"+ssc);
                }
                //objEightJxl[conCalcProcessLoss[ii][1] + "|#|" + conCalcProcessLoss[ii][3] + "|#|" + conCalcProcessLoss[ii][5]] = [conCalcProcessLoss[ii][1], conCalcProcessLoss[ii][3], conCalcProcessLoss[ii][5]];
            }
        }
        /*for (let prop in objEightJxl) {
            diaDimension.push(objEightJxl[prop]);
        }*/
        if (data.jsonConCalcProcessLoss != "") {
            let savedData = JSON.parse(data.savedDiaDimension);
            console.log(savedData,'savedData');
            for (let ii = 0; ii < ArrProcessLoss.length; ii++) {
                //for (let s = 0; s < savedData.length; s++) {
                    //diaDimension[ii].push(savedData[ii][s]);
                //}
                let ini = ArrProcessLoss[ii].split('|#|');
                if(savedData[ii]) {
                    //ini.push(savedData[c]);
                    let newArr = ini.concat(savedData[ii]);
                    console.log(newArr,'newArr');
                    diaDimension.push(newArr);
                }
            }
        }
        console.log(diaDimension,'diaDimension');
        //
        let GlbFabricFinish = JSON.parse(data.jsonFabricFinish);
        var objDiaDimensionGroup = []; var newObjDiaDimensionGroup = [];
        let prevColPlusSize = ArrSizes.length + 5;
        console.log(prevColPlusSize,'prevColPlusSize');
            for(let ii = 0; ii < diaDimension.length; ii++) {
                for(let a = 5; a < prevColPlusSize; a++) {
                    objDiaDimensionGroup = fnPopulateValueArray(objDiaDimensionGroup,
                        diaDimension[ii][0]+"##"+diaDimension[ii][1]+"##"+diaDimension[ii][2],diaDimension[ii][a]);
                    newObjDiaDimensionGroup = fnPopulateValueArray(newObjDiaDimensionGroup,diaDimension[ii][0]+"##"+diaDimension[ii][1],
                        diaDimension[ii][a]);
                }
            }

            console.log(objDiaDimensionGroup,'objDiaDimensionGroup');
            console.log(newObjDiaDimensionGroup,'newObjDiaDimensionGroup');
            for(let prop in objDiaDimensionGroup) {
                //let newStr = objDiaDimensionGroup[prop].split(new RegExp(GlbSeparators.join('|'),'g'));
                let allDiaDimStr = objDiaDimensionGroup[prop].replace('undefined-','');
                let newStr = allDiaDimStr.split('-');
                console.log(newStr,'newStr');
                let uniqueSizes = getUnique(newStr);
                console.log(uniqueSizes,'uniqueSizes');
                for(let ii = 0; ii < uniqueSizes.length; ii++) {
                    objAllSizesDiaDim[prop] = uniqueSizes;
                }
            }
            console.log(objAllSizesDiaDim,'objAllSizesDiaDim Check');

        var ArrDiaPositions = {}; var objUnitOfMeasure = {};
        for(let ii = 0; ii < diaDimension.length; ii++) {
            let diaDimensionIi = diaDimension[ii];
            let diaArr = objAllSizesDiaDim[diaDimensionIi[0]+"##"+diaDimensionIi[1]+"##"+diaDimensionIi[2]];
            console.log(diaArr,'diaArr');
            for(let kk = 0; kk < diaArr.length; kk++) {
                let comp = diaDimensionIi[0];
                let parts = diaDimensionIi[1];
                let uom = diaDimensionIi[4];
                /*For finding indexes of dia in JXL
                * Example 20 is in 5th COL and 22 s in 9th COL*/
                console.log(diaDimensionIi,'diaDimensionIi');
                console.log(diaArr[kk],'diaArr[kk]');
                let res = getAllIndexes(diaDimensionIi,diaArr[kk]);
                console.log(res,'res');
                //if(res.length > 0) {
                    ArrDiaPositions[diaDimensionIi[0]+"##"+diaDimensionIi[1]+"##"+diaDimensionIi[2]+"##"+diaArr[kk]] = res;
                    objUnitOfMeasure[comp+"|#|"+parts] = uom;
                //}
            }
        }
        console.log(ArrDiaPositions,'ArrDiaPositions');
        if(data.sevenAJxl != "") {
            sevenAJxl = JSON.parse(data.sevenAJxl);
            console.log(sevenAJxl,'sevenAJxl');
            var SumAllDiaMixed = {};
            for(let ii = 0; ii < sevenAJxl.length; ii++) {
                for(let prop in ArrDiaPositions) {
                    let leftSide = prop.split('##');
                    let com = jsTrim(sevenAJxl[ii][1]);
                    //console.log(sevenAJxl[ii][1]+"##"+sevenAJxl[ii][3]+"##"+sevenAJxl[ii][4],'sevenAJxl[ii][1]+"##"+sevenAJxl[ii][3]+"##"+sevenAJxl[ii][4]');
                    //console.log(leftSide[0]+"##"+leftSide[1]+"##"+leftSide[2],'leftSide[0]+"##"+leftSide[1]+"##"+leftSide[2]');
                    console.log(sevenAJxl[ii][1]+"##"+sevenAJxl[ii][3]+"##"+sevenAJxl[ii][4],leftSide[0]+"##"+leftSide[1]+"##"+leftSide[2],'SAME');
                    if(com+"##"+sevenAJxl[ii][3]+"##"+sevenAJxl[ii][4] == leftSide[0]+"##"+leftSide[1]+"##"+leftSide[2]) {
                        let ArrPosition = ArrDiaPositions[prop];
                        console.log(ArrPosition,'ArrPosition');
                        var sumDiaSize = 0;
                        for(let jj = 0; jj < ArrPosition.length; jj++) {
                            let originalPos = ArrPosition[jj];
                            console.log(originalPos,'originalPos');
                            console.log(sevenAJxl[ii][originalPos],'sevenAJxl[ii][originalPos]');
                            sumDiaSize += Number(sevenAJxl[ii][originalPos]);
                            console.log(sumDiaSize.toFixed(3),'sumDiaSize 3 fixed');
                            console.log(com+"##"+sevenAJxl[ii][3]+"##"+sevenAJxl[ii][4]+"##"+leftSide[3],
                                'sevenAJxl[ii][1]+"##"+sevenAJxl[ii][3]+"##"+sevenAJxl[ii][4]+"##"+leftSide[3]');
                            SumAllDiaMixed[com+"##"+sevenAJxl[ii][3]+"##"+sevenAJxl[ii][4]+"##"+leftSide[3]] = sumDiaSize.toFixed(3);

                        }
                    }
                }
            }
            console.log(SumAllDiaMixed,'SumAllDiaMixed');
            for(let prop in SumAllDiaMixed) {
                let keys = prop.split('##');
                //console.log(keys[0]+"##"+keys[1]+"##"+keys[3],'keys[0]+"##"+keys[1]+"##"+keys[3]');
                var uniqueStr = keys[0]+"##"+keys[1]+"##"+keys[3];
                joinedVal = fnPopulateValueArray(joinedVal, uniqueStr,SumAllDiaMixed[prop]);
            }
            console.log(joinedVal,'joinedVal');
        }

        var newObjAllSizesDiaDim = {};
        for(let prop in newObjDiaDimensionGroup) {
            let newStr = newObjDiaDimensionGroup[prop].split(new RegExp(GlbSeparators.join('|'),'g'));
            newStr = newStr.filter(function (val) {
                return val !== "undefined";
            });
            //console.log(newStr,'newStr Num');
            let newUniqueSizes = getUnique(newStr);

            for(let ii = 0; ii < newUniqueSizes.length; ii++) {
                newObjAllSizesDiaDim[prop] = newUniqueSizes;
            }
        }
        console.log(GlbFabricFinish,'GlbFabricFinish');
        console.log(newObjAllSizesDiaDim,'newObjAllSizesDiaDim');
        var subTotal = 0; var planFabWgt = 0;
        var ArrPlanFabWgtSubTotal = {};
        for(let ii = 0; ii < GlbFabricFinish.length; ii++) {
            subTotal = 0;
            console.log(GlbFabricFinish[ii][1]+"##"+GlbFabricFinish[ii][3],
                'GlbFabricFinish[ii][1]+"##"+GlbFabricFinish[ii][3]');
            let diaDimArr = newObjAllSizesDiaDim[GlbFabricFinish[ii][1]+"##"+GlbFabricFinish[ii][3]];
            console.log(diaDimArr,'diaDimArr');
            if(diaDimArr) {
                console.log('test ok');
                if (diaDimArr.length > 0) {
                    for (let jj = 0; jj < diaDimArr.length; jj++) {
                        console.log(objUnitOfMeasure,'objUnitOfMeasure');
                        let comp = GlbFabricFinish[ii][1];
                        console.log(comp,'comp CHECK SPACE');
                        let parts = GlbFabricFinish[ii][3];
                        console.log(comp+"|#|"+parts,'comp+"|#|"+parts');
                        let uom = objUnitOfMeasure[comp+"|#|"+parts];
                        planFabWgt = fnSumSizeArrayValue(joinedVal[GlbFabricFinish[ii][1] + "##" + GlbFabricFinish[ii][3] + "##" + diaDimArr[jj]]);
                        console.log(planFabWgt,'planFabWgt');
                        let yy = GlbFabricFinish[ii][1] + "##" + GlbFabricFinish[ii][2] + "##" + GlbFabricFinish[ii][3];
                        if (jQuery.inArray(yy, gapJxlArr) === -1) {
                            gapJxlArr.push(GlbFabricFinish[ii][1] + "##" + GlbFabricFinish[ii][2] + "##" + GlbFabricFinish[ii][3]);
                            GlbCurrentJxlTbl.push([GlbFabricFinish[ii][0], GlbFabricFinish[ii][1], GlbFabricFinish[ii][2],
                                GlbFabricFinish[ii][3], GlbFabricFinish[ii][4], GlbFabricFinish[ii][5],
                                GlbFabricFinish[ii][6], GlbFabricFinish[ii][7], GlbFabricFinish[ii][8],
                                GlbFabricFinish[ii][9], diaDimArr[jj], uom, planFabWgt, ""]);
                        }
                        else {
                            if (diaDimArr[jj])
                                GlbCurrentJxlTbl.push(["", "", "", "", "", "", "", "", "", "", diaDimArr[jj], uom, planFabWgt, ""]);
                        }
                        subTotal += Number(planFabWgt);
                    }
                    GlbCurrentJxlTbl.push(["", "", "", "", "", "", "", "", "", "", "", "", "", subTotal.toFixed(3)]);
                }
                ArrPlanFabWgtSubTotal[GlbFabricFinish[ii][0]+"##"+GlbFabricFinish[ii][1]+"##"+GlbFabricFinish[ii][2]+"##"+
                GlbFabricFinish[ii][3]+"##"+GlbFabricFinish[ii][9]] = subTotal.toFixed(3);
            }
        }
        console.log(ArrPlanFabWgtSubTotal,'ArrPlanFabWgtSubTotal');

        MakePostRequest(base_path+"fabricprogram/saveFabRequirement","rFrom=1&enqId="+enquiryId+"&pId="+pageId+"&d="+
            JSON.stringify(ArrPlanFabWgtSubTotal),"json",function (saveFabReqResData) {
            console.log(saveFabReqResData,'saveFabReqResData');
        });

        console.log(GlbCurrentJxlTbl,'GlbCurrentJxlTbl');
        jexcel(document.getElementById("nine"),{
            columns:[
                { title:'Combo', width:110, readOnly: true },
                { title:'Component', width:110, readOnly: true },
                { title:'Color', width:110, readOnly: true },
                { title:'Garment Parts', width:110, readOnly: true },
                { title:'Fabric Blend (%)', width:100, readOnly: true },
                { title:'Fabric Content', width:110, readOnly: true },
                { title:'Fabric Name', width:110, readOnly: true },
                { title:'Finishing GSM', width:70, readOnly: true },
                { title:'Yarn Count', width:70, readOnly: true },
                { title:'Dyeing Type', width:70, readOnly: true },
                { title:'Fin. DIA / DIM\n(W * H)', width:100, readOnly: true },
                { title:'Unit of Measure', width:80, readOnly: true },
                { title:'Plan. Fab. Wgt. (Kgs.)', width: 100, readOnly: true, align: 'right' },
                //{ type: 'hidden'},
                { title:'Plan. Fab. Wgt. Subtotal (Kgs)', width: 100, readOnly: true, align: 'right' },
            ],
            footers: [['','','','','','','','','','','','','Total:','=SUMCOL(TABLE(), COLUMN())']],
            data: GlbCurrentJxlTbl,
            columnDrag:true,
            allowInsertRow:false,
            allowInsertColumn:false
        });
    });

    function fnPopulateValueArray(ArrName, KeyValue, InsertVal) {
        if (jQuery.inArray(KeyValue, ArrName)) {
            ArrName[KeyValue] = ArrName[KeyValue]+"-"+InsertVal;
        }
        return ArrName;
    }

    function fnSumSizeArrayValue(ArrSizeVal) {
        if(ArrSizeVal) {
            let newSizeVal = ArrSizeVal.substr(10);
            //console.log(newSizeVal,'newSizeVal');
            let SumVal = 0;
            let ArrName = newSizeVal.split("-");
            if(ArrName.length > 0) {
                //console.log(ArrName,'ArrName');
                for (let i = 0; i < ArrName.length; i++) {
                    //console.log(ArrName[i],'ArrName[i]');
                    SumVal = parseFloat(ArrName[i]) + SumVal;
                }
            }
            return SumVal.toFixed(3);
        }

    }

    function getAllIndexes(arr, val) {
        let indexes = [], i = -1;
        //console.log(arr,'arr');
        //console.log(val,'val');
        //console.log(typeof arr,'arr typeof');
        //console.log(typeof val,'val typeof');
        //console.log(arr.indexOf(val, i+1),'arr.indexOf(val, i+1)');
        //console.log((i = arr.indexOf(val, i+1)),'(i = arr.indexOf(val, i+1))');
        while ((i = arr.indexOf(val, i+1)) !== -1) {
            indexes.push(i);
        }
        return indexes;
    }
</script>
<?php $this->load->view(CNFCOMPANY.'template/pagefooter'); ?>