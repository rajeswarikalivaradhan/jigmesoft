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
                                        <strong>YARN PROGRAMME</strong>
                                    </div>
                                </div>
                                <div class="col-sm-12 table-responsive" style="padding: 5px !important">
                                    <div id="thirteen"></div>
                                </div>
                                <button type="button" class="btn btn-info pull-right" style="margin-bottom: 10px; margin-right: 30px" onclick="fnSaveChanges()">Save</button>
                                <div class="col-md-12 no-padding">
                                    <div style="background-color: rgb(210 253 249); padding-top: 10px; padding-left: 10px">
                                        <strong>CONSOLIDATED YARN REQUIREMENT QTY.</strong>
                                    </div>
                                </div>
                                <div class="col-sm-12 table-responsive" style="padding: 5px !important">
                                    <div id="consolidated13"></div>
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
    const hashEnquiryId = '<?php echo $VarHashEnquiryId ?>';
    const pageId = '<?php echo $VarPageId ?>';
    const GlbParam = 'rFrom=1';
    unsaved = false;
    GlbThirteenConsolidatedJxl = ''; yarnReqQtyConslidated = '';
    fourteenJxl = [];
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

    MakeAsynPostRequest(base_path+"fabricprogram/ajaxData",GlbParam+"&enqId="+enquiryId+"&pid="+pageId,"json",function (data) {
        yarnProgramData = '';
        console.log(data, 'data');
        if(data.yarnProgram != "") {
            yarnProgramData = JSON.parse(data.yarnProgram);
        }
        planFabWeightSubTotal = '';
        if(data.planFabWeightSubTotal != '') {
            planFabWeightSubTotal = data.planFabWeightSubTotal;
        }
        var currentJxlTbl = [];
        var lycraGroup = {};
        if(data.jsonFeederLycra != '') {
            //console.log(planFabWeightSubTotal,'planFabWeightSubTotal');
            feederLycra = JSON.parse(data.jsonFeederLycra);
            for(let ii = 0; ii < feederLycra.length; ii++) {
                if(feederLycra[ii][2].indexOf(' : ') >= 0) {
                    let colorArr = feederLycra[ii][2].split(' : ');
                    for(let jj = 0; jj < feederLycra.length; jj++) {
                        lycraGroup[feederLycra[ii][0] + "##" + feederLycra[ii][1] + "##" +colorArr[jj]+ "##" + feederLycra[ii][3]] = feederLycra[ii][7];
                    }
                }
                else {
                    lycraGroup[feederLycra[ii][0] + "##" + feederLycra[ii][1] + "##" +feederLycra[ii][2]+ "##" + feederLycra[ii][3]] = feederLycra[ii][7];
                }
            }
            //console.log(lycraGroup,'lycraGroup');
            //console.log(feederLycra,'feederLycra');
            for(let ii = 0; ii < feederLycra.length; ii++) {
                let fL = feederLycra[ii];
                let colors = fL[2];
                let blend = fL[4];
                let content = fL[5];
                let feeder = fL[6];
                let lycra = fL[7];
                let yarnCount = fL[10];
                let dyeingType = fL[11];
                let splRequest = fL[12];
                if(dyeingType === 'FD') {
                    if(colors.indexOf(' : ') !== -1) {
                        let colorArr = colors.split(' : ');
                        let ArrBlend = blend.split(' / ');
                        let ArrContent = content.split(' / ');
                        let ArrFeeder = feeder.split(' / ');
                        let feederSum = ArrFeeder.reduce(function (a,b) {
                            return Number(a) + Number(b);
                        });
                        let ArrSplRequest = splRequest.split(' / ');
                        for(let cc = 0; cc < colorArr.length; cc++) {
                            let yarnCountArr = yarnCount.split(' / ');
                            let planFabWeight = planFabWeightSubTotal[fL[0]+"##"+fL[1]+"##"+colorArr[cc]+"##"+fL[3]+"##"+dyeingType];
                            //console.log(planFabWeight,'planFabWeight');
                            let colorPercent = (Number(ArrFeeder[cc]) / Number(feederSum)) * 100;
                            let yarnWeight = Number((colorPercent / 100)) * Number(planFabWeight);
                            currentJxlTbl.push([fL[0],fL[1],colorArr[cc],fL[3],ArrBlend[cc],ArrContent[cc],ArrSplRequest[cc],yarnCountArr[cc],
                                dyeingType,feederSum,ArrFeeder[cc],colorPercent.toFixed(3),planFabWeight,yarnWeight.toFixed(3)]);
                        }
                    }
                    else {
                        let planFabWeight = planFabWeightSubTotal[fL[0]+"##"+fL[1]+"##"+colors+"##"+fL[3]+"##"+dyeingType];
                        //console.log(planFabWeight,'planFabWeight IN ELSE');

                        if(yarnProgramData != '') {
                            yarnPurchaseType = yarnProgramData[fL[0]+"##"+fL[1]+"##"+colors+"##"+fL[3]+"##"+dyeingType];
                            //console.log(yarnPurchaseType,'yarnPurchaseType');
                        }
                        else
                            yarnPurchaseType = "";
                        //let lycraData = lycraGroup[tenJxlTbl[ii][0]+"##"+tenJxlTbl[ii][1]+"##"+tenJxlTbl[ii][2]+"##"+tenJxlTbl[ii][3]];
                        //console.log(yarnPurchaseType,'yarnPurchaseType');
                        currentJxlTbl.push([fL[0],fL[1],colors,fL[3],blend,content,splRequest,yarnPurchaseType,
                            yarnCount,dyeingType,planFabWeight,lycra]);
                    }
                }
            }
        }

        if(data.singleDyeBath != "") { //SDB
            let tenJxlTbl = JSON.parse(data.singleDyeBath);
            for(let ii = 0; ii < tenJxlTbl.length; ii++) {
                let lycraData = lycraGroup[tenJxlTbl[ii][0]+"##"+tenJxlTbl[ii][1]+"##"+tenJxlTbl[ii][2]+"##"+tenJxlTbl[ii][3]];
                if(yarnProgramData != '')
                    yarnPurchaseType = yarnProgramData[tenJxlTbl[ii][0]+"##"+tenJxlTbl[ii][1]+"##"+tenJxlTbl[ii][2]+"##"+tenJxlTbl[ii][3]+"##"+tenJxlTbl[ii][8]];
                else
                    yarnPurchaseType = "";
                currentJxlTbl.push([tenJxlTbl[ii][0],tenJxlTbl[ii][1],tenJxlTbl[ii][2],tenJxlTbl[ii][3],jsTrim(tenJxlTbl[ii][4]),jsTrim(tenJxlTbl[ii][5]),
                    jsTrim(tenJxlTbl[ii][6]),yarnPurchaseType,tenJxlTbl[ii][7],tenJxlTbl[ii][8],tenJxlTbl[ii][13],lycraData]);
            }
        }
        if(data.yarnDyeStrips != "") { //YDS
            let elevenJxl = JSON.parse(data.yarnDyeStrips);
            //console.log(elevenJxl,'YDS YDS');
            for(let ii = 0; ii < elevenJxl.length; ii++) {
                let lycraData = lycraGroup[elevenJxl[ii][0]+"##"+elevenJxl[ii][1]+"##"+elevenJxl[ii][2]+"##"+elevenJxl[ii][3]];
                if(yarnProgramData != '')
                    yarnPurchaseType = yarnProgramData[elevenJxl[ii][0]+"##"+elevenJxl[ii][1]+"##"+elevenJxl[ii][2]+"##"+elevenJxl[ii][3]+"##"+elevenJxl[ii][8]];
                else
                    yarnPurchaseType = "";
                currentJxlTbl.push([elevenJxl[ii][0],elevenJxl[ii][1],elevenJxl[ii][2],elevenJxl[ii][3],jsTrim(elevenJxl[ii][4]),elevenJxl[ii][5],jsTrim(elevenJxl[ii][6]),
                    yarnPurchaseType,elevenJxl[ii][7],elevenJxl[ii][8],elevenJxl[ii][13],lycraData]);
            }
        }
        if(data.jsonJacquard != "") { //YDJ
            twelveJxl = JSON.parse(data.jsonJacquard);
            for(let ii = 0; ii < twelveJxl.length; ii++) {
                let lycraData = lycraGroup[twelveJxl[ii][0]+"##"+twelveJxl[ii][1]+"##"+twelveJxl[ii][2]+"##"+twelveJxl[ii][3]];
                if(yarnProgramData != '')
                    yarnPurchaseType = yarnProgramData[twelveJxl[ii][0]+"##"+twelveJxl[ii][1]+"##"+twelveJxl[ii][2]+"##"+twelveJxl[ii][3]+"##"+twelveJxl[ii][8]];
                else
                    yarnPurchaseType = "";
                currentJxlTbl.push([twelveJxl[ii][0],twelveJxl[ii][1],twelveJxl[ii][2],twelveJxl[ii][3],jsTrim(twelveJxl[ii][4]),twelveJxl[ii][5],
                    jsTrim(twelveJxl[ii][6]),yarnPurchaseType,twelveJxl[ii][7],twelveJxl[ii][8],twelveJxl[ii][11],lycraData]);
            }
        }
        console.log(currentJxlTbl,'currentJxlTbl before jexcel ');

        jexcel(document.getElementById("thirteen"),{
            columns:[
                { title:'Combo', width:120, readOnly: true },
                { title:'Component', width:120, readOnly: true },
                { title:'Color', width:120, readOnly: true },
                { title:'Garment Parts', width:110, readOnly: true },
                { title:'Yarn Blend (%)', width:115, readOnly: true },
                { title:'Yarn Content', width:115, readOnly: true },
                { title:'Yarn Special Request If Any', width:100, readOnly: true },
                { title:'Yarn Purchase Type', type: "dropdown", source: ["Greige","Melange","Coloured"], width:70 },
                { title:'Yarn Count', width:70, readOnly: true },
                { title:'Dyeing Type', width:70, readOnly: true },
                { type:'numeric', title:'Plan. Fab. Wgt. - Subtotal (Kgs.)', width: 100,align:'right', readOnly: true },
                { title:'Lycra (%)', width:70, readOnly: true },
                { title:'Lycra Wgt. (Kgs.)', width:70,align:'right', readOnly: true },
                { title:'Req. Yarn Wgt. (Kgs)', width: 100,align:'right', readOnly: true },
            ],
            footers: [['','','','','','','','','','Total','=SUMCOL(TABLE(), COLUMN())','','=SUMCOL(TABLE(), COLUMN())','=SUMCOL(TABLE(), COLUMN())']],
            data: currentJxlTbl,
            onchange:function() {
                unsaved = true;
            },
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
                    lycraWgt = planFabWgt * (Number(lycraPercent) / 100);
                    $(cell).text(lycraWgt.toFixed(3));
                    instance.jexcel.options.data[row][col] = lycraWgt.toFixed(3);
                }
                if (col == 13) {
                    reqFabWgt = Number(planFabWgt) - Number(lycraWgt);
                    $(cell).text(reqFabWgt.toFixed(3));
                    instance.jexcel.options.data[row][col] = reqFabWgt.toFixed(3);
                }
            },
            columnDrag:true,
            allowInsertRow:false,
            allowInsertColumn:false
        });

        if(data.yarnProgram != "") {
            let yarnProgramData = JSON.parse(data.yarnProgram);
            console.log(currentJxlTbl,'currentJxlTbl merge yarn purchase type');
            yarnProgramJxl = [];
            for(let ii = 0; ii < currentJxlTbl.length; ii++) {
                let cbo = currentJxlTbl[ii][0];
                let com = currentJxlTbl[ii][1];
                let col = currentJxlTbl[ii][2];
                let parts = currentJxlTbl[ii][3];
                let blend = currentJxlTbl[ii][4];
                let content = currentJxlTbl[ii][5];
                let splReq = currentJxlTbl[ii][6];
                let dyeingType = currentJxlTbl[ii][9];
                let yarnPurchaseType = yarnProgramData[cbo+"##"+com+"##"+col+"##"+parts+"##"+dyeingType];
                yarnProgramJxl.push([cbo,com,col,parts,blend,content,splReq,yarnPurchaseType,currentJxlTbl[ii][8],currentJxlTbl[ii][9],
                    currentJxlTbl[ii][10],currentJxlTbl[ii][11],currentJxlTbl[ii][12],currentJxlTbl[ii][13]]);
            }
            currentJxlTbl = yarnProgramJxl;
            //console.log(currentJxlTbl,'currentJxlTbl');
            var fourteenJxl = [];
            let thirteenJxl = currentJxlTbl;
            let planFabWgtGreigeMelange = {}; let lycraWgtGreigeMelange = {}; let reqYarnWgtGreigeMelange = {};
            let planFabWgtColoured = {}; let lycraWgtColoured = {}; let reqYarnWgtColoured = {};
            console.log(thirteenJxl,'thirteenJxl');
            for(let ii = 0; ii < thirteenJxl.length; ii++) {
                let yarnColor = thirteenJxl[ii][2];
                let blend = thirteenJxl[ii][4];
                let content = thirteenJxl[ii][5];
                let yarnSplReq = thirteenJxl[ii][6];
                let yarnCount = thirteenJxl[ii][8];
                let dyeingType = thirteenJxl[ii][9];
                let planFabWgt = thirteenJxl[ii][10];
                console.log(thirteenJxl[ii],'thirteenJxl[ii]');
                let lycraWgt = thirteenJxl[ii][12];
                let reqYarnWgt = thirteenJxl[ii][13];
                let yarnPurType = thirteenJxl[ii][7];

                if(yarnPurType == "Greige" || yarnPurType == "Melange") {
                    planFabWgtGreigeMelange = fnPopulateValueArray(planFabWgtGreigeMelange,blend+"##"+content+"##"+yarnSplReq+"##"+yarnPurType+"##"+yarnCount,
                        planFabWgt);
                    console.log(lycraWgt,'lycraWgt');
                    lycraWgtGreigeMelange = fnPopulateValueArray(lycraWgtGreigeMelange,blend+"##"+content+"##"+yarnSplReq+"##"+yarnPurType+"##"+yarnCount,
                        lycraWgt);
                    reqYarnWgtGreigeMelange = fnPopulateValueArray(reqYarnWgtGreigeMelange,blend+"##"+content+"##"+yarnSplReq+"##"+yarnPurType+"##"+yarnCount,
                        reqYarnWgt);
                }
                if(yarnPurType == "Coloured") {
                    console.log(yarnPurType,'yarnPurType');
                    planFabWgtColoured = fnPopulateValueArray(planFabWgtColoured,yarnColor+"##"+blend+"##"+content+"##"+yarnSplReq+"##"+yarnPurType+"##"+yarnCount,
                        planFabWgt);
                    lycraWgtColoured = fnPopulateValueArray(lycraWgtColoured,yarnColor+"##"+blend+"##"+content+"##"+yarnSplReq+"##"+yarnPurType+"##"+yarnCount,lycraWgt);
                    reqYarnWgtColoured = fnPopulateValueArray(reqYarnWgtColoured,yarnColor+"##"+blend+"##"+content+"##"+yarnSplReq+"##"+yarnPurType+"##"+yarnCount,reqYarnWgt)

                }
            }

            console.log(planFabWgtGreigeMelange,'planFabWgtGreigeMelange');
            console.log(lycraWgtGreigeMelange,'lycraWgtGreigeMelange');
            console.log(reqYarnWgtGreigeMelange,'reqYarnWgtGreigeMelange');
            for(let prop in planFabWgtGreigeMelange) {
                let leftSide = prop.split('##');
                let blend = leftSide[0];
                let content = leftSide[1];
                let yarnSplReq = leftSide[2];
                let yarnPurType = leftSide[3];
                let yarnCount = leftSide[4];
                let planFabWgtGreigeMelangeData = planFabWgtGreigeMelange[prop];
                planFabWgtGreigeMelangeData = planFabWgtGreigeMelangeData.replace('undefined-','');
                console.log(planFabWgtGreigeMelangeData,'planFabWgtGreigeMelangeData after replace',typeof planFabWgtGreigeMelangeData,'typeof planFabWgtGreigeMelangeData');
                let planFabWgtCons = fnSumSizeArrayValue(planFabWgtGreigeMelangeData);
                let yarnColor = yarnPurType;
                let lycraWgt = lycraWgtGreigeMelange[prop];
                console.log(lycraWgt,'lycraWgt');
                let lycraWgtGreigeMelangeResult = fnSumSizeArrayValue(lycraWgt);
                let reqYarnWgt = reqYarnWgtGreigeMelange[prop];
                let reqYarnWgtResult = fnSumSizeArrayValue(reqYarnWgt);
                fourteenJxl.push([blend,content,yarnSplReq,yarnPurType,yarnColor,yarnCount,
                    planFabWgtCons,lycraWgtGreigeMelangeResult,reqYarnWgtResult]);
            }

            console.log(planFabWgtColoured,'planFabWgtColoured');
            console.log(lycraWgtColoured,'lycraWgtColoured');
            for(let prop in planFabWgtColoured) {
                let leftSide = prop.split('##');
                console.log(leftSide,'leftSide');
                let yarnColor = leftSide[0];
                let blend = leftSide[1];
                let content = leftSide[2];
                let yarnSplReq = leftSide[3];
                let yarnPurType = leftSide[4];
                let yarnCount = leftSide[5];
                let planFabWgtColouredData = planFabWgtColoured[prop];
                console.log(planFabWgtColouredData,'planFabWgtColouredData');
                planFabWgtColouredData = planFabWgtColouredData.replace('undefined-','');
                console.log(planFabWgtColouredData,'planFabWgtColouredData after replace');
                let planFabWgtCons = fnSumSizeArrayValue(planFabWgtColouredData);
                let lycraWgt = lycraWgtColoured[prop];
                lycraWgt = lycraWgt.replace('undefined-','');
                console.log(lycraWgt,'lycraWgt');
                if(lycraWgt.indexOf('-') !== -1) {
                    lycraWgtColouredResult = fnSumSizeArrayValue(lycraWgt);
                }
                else {
                    lycraWgtColouredResult = lycraWgt;
                }
                let reqYarnWgt = reqYarnWgtColoured[prop];
                reqYarnWgt = reqYarnWgt.replace('undefined-','');
                if(reqYarnWgt.indexOf('-') !== -1) {
                    reqYarnWgtResult = fnSumSizeArrayValue(reqYarnWgt);
                }
                else {
                    reqYarnWgtResult = reqYarnWgt;
                }
                fourteenJxl.push([blend,content,yarnSplReq,yarnPurType,yarnColor,yarnCount,
                    planFabWgtCons,lycraWgtColouredResult,reqYarnWgtResult]);
            }
            jexcel(document.getElementById("consolidated13"),{
                columns:[
                    { title:'Yarn Blend (%)', width:200, readOnly: true },
                    { title:'Yarn Content', width:200, readOnly: true },
                    { title:'Yarn Special Request If Any', width:200, readOnly: true },
                    { title:'Yarn Purchase Type', width: 175, readOnly: true },
                    { title:'Yarn Color', width:120, readOnly: true },
                    { title:'Yarn Count', width:100, readOnly: true },
                    { title:'Plan. Fab. Wgt. Consolidated(Kgs.)', width: 120, readOnly: true },
                    { title:'Lycra Wgt. Consolidated (Kgs.)', width:120, readOnly: true },
                    { title:'Req. Yarn Wgt. Consolidated (Kgs)', width: 120, readOnly: true },
                ],
                data: fourteenJxl,
                footers: [['','','','','','Total','=SUMCOL(TABLE(), COLUMN())','=SUMCOL(TABLE(), COLUMN())','=SUMCOL(TABLE(), COLUMN())']]
            });

        }

    });

    function fnPopulateValueArray(ArrName, KeyValue, InsertVal) {
        if (jQuery.inArray(KeyValue, ArrName)) {
            //console.table(KeyValue+"!!"+ArrName+"!!"+InsertVal,'TAB');
            //console.log(KeyValue+"!!"+ArrName+"!!"+InsertVal,'log');
            //alert(KeyValue+"!!"+ArrName+"!!"+InsertVal);
            ArrName[KeyValue] = ArrName[KeyValue]+"-"+InsertVal;
        }
        return ArrName;
    }

    function fnSumSizeArrayValue(hyphenValue) {
        let SumVal = 0;
        if(hyphenValue.indexOf('-') !== -1) {
            let ArrValue = hyphenValue.split("-");
            if(ArrValue.length >= 2) {
                for (let i = 0; i < ArrValue.length; i++) {
                    console.log(ArrValue[i],'ArrValue[i]');
                    if(ArrValue[i] > 0) {
                        SumVal = parseFloat(ArrValue[i]) + SumVal;
                    }
                }
            }
        }
        else {
            SumVal = parseFloat(hyphenValue);
        }
        return SumVal.toFixed(3);
    }
    console.log(fourteenJxl,'fourteenJxl');

    function fnSaveChanges() {
        let yarnProgram = $("#thirteen").jexcel('getData');
        let yarnProgramData = {};
        for(let d = 0; d < yarnProgram.length; d++) {
            let cbo = yarnProgram[d][0];
            let com = yarnProgram[d][1];
            let col = yarnProgram[d][2];
            let parts = yarnProgram[d][3];
            let dyeingType = yarnProgram[d][9];
            let yarnPurchaseType = yarnProgram[d][7];
            console.log(cbo+"##"+com+"##"+col+"##"+parts+"##"+dyeingType+"####"+yarnPurchaseType,'parts+"##"+dyeingType');
            yarnProgramData[cbo+"##"+com+"##"+col+"##"+parts+"##"+dyeingType] = yarnPurchaseType;
        }
        console.log(yarnProgram,'yarnProgram');
        //saveThirteen
        MakePostRequest(base_path+"fabricprogram/saveYarnProgram",GlbParam+"&enqId="+enquiryId+"&d="+JSON.stringify(yarnProgramData),"json",fnSaveRes);
        function fnSaveRes(data) {
            console.log(data,'data');
            if(data.errCode === 1) {
                unsaved = false;
                $("#divCmnSuccessMsg").removeClass("hide");
                $("#divCmnSuccessMsg").text("saved Successfully");
                fnRedirectPageTimeOut(base_path+"fabricprogram/thirteen/"+hashEnquiryId);
            }
        }

    }

    console.log(yarnReqQtyConslidated,'yarnReqQtyConslidated');
    function cmnSaveChanges() {

    }
    function unloadPage() {
        if (unsaved) {
            return "You have unsaved changes on this page. Do you want to leave this page and discard your changes or stay on this page?";
        }
    }
    window.onbeforeunload = unloadPage;
</script>
<?php $this->load->view(CNFCOMPANY.'template/pagefooter'); ?>