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
                                        <strong>FABRIC DETAILS - KNIT: COLOUR WISE FABRIC BLEND (%) AND CONTENT</strong>
                                    </div>
                                </div>
                                <div class="col-sm-12 table-responsive" style="padding: 5px !important">
                                    <div id="fabDetailKnitColorWiseFabBlendAndContent"></div>
                                </div>

                                <div class="col-md-12 no-padding">
                                    <div style="background-color: rgb(210 253 249); padding-top: 10px; padding-left: 10px">
                                        <strong>ITEMIZED QTY. * EXCESS QTY. (%)</strong>
                                    </div>
                                </div>
                                <div class="col-sm-12 table-responsive" style="padding: 5px !important">
                                    <div id="three"></div>

                                </div>

                            </form>

                            <?php $this->load->view("fabric_program/fabricProFooterLinks"); ?>
                        </div><!-- /.box-body -->
                        <div class="box-body" style="padding: 0">
                            <div class="col-md-12 no-padding">
                                <div style="background-color: rgb(210 253 249); padding-top: 10px; padding-left: 10px">
                                    <strong>PLANNED QTY.</strong>
                                </div>
                            </div>
                            <div class="col-sm-12 table-responsive" style="padding: 5px !important">
                                <div id="excessQtyTbl"></div>
                            </div>
                        </div>

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
    const tableId = '<?php echo $VarTableId ?>';
    const GlbParam = 'rFrom=1';
    // A custom method to SUM all the cells in the current column
    SUMCOL = function(instance, columnId) {
        let total = 0;
        for (let j = 0; j < instance.options.data.length; j++) {
            if (Number(instance.records[j][columnId-1].innerHTML)) {
                total += Number(instance.records[j][columnId-1].innerHTML);
            }
        }
        return total;
    };

    function fnPopulateValueArray(ArrName, KeyValue, InsertVal) {
        if (jQuery.inArray(KeyValue, ArrName)) {
            ArrName[KeyValue] = ArrName[KeyValue]+"|-|"+InsertVal;
        }
        return ArrName;
    }
    function fnPopulateColValue(ArrName, KeyValue, InsertVal) {
        if (jQuery.inArray(KeyValue, ArrName)) {
            ArrName[KeyValue] = ArrName[KeyValue]+"-"+InsertVal;
        }
        return ArrName;
    }
    unsaved = false; excessQtyJxl = []; ArrFirstFive = [];
    MakeAsynPostRequest(base_path+"fabricprogram/ajaxData","rFrom=1&enqId="+enquiryId+"&pid="+pageId,"json",function (data) {
        console.log(data, 'data');
        ArrSizes = data.ArrSizeChart;
        let fabFinish = ''; let lastCol = 0;
        let colGrp = {}; let FilterSameCol = []; let objPartsGrp = {};
        if(data.jsonFabricFinishTbl != '') {
            fabFinish = JSON.parse(data.jsonFabricFinishTbl);
            for(let ii = 0; ii < fabFinish.length; ii++) {
                let cbo = fabFinish[ii][0];
                let com = fabFinish[ii][1];
                let col = fabFinish[ii][2];
                let parts = fabFinish[ii][3];
                //console.log(col,'col');
                if(jQuery.inArray(col,FilterSameCol) !== -1) {
                }
                else {
                    colGrp = fnPopulateColValue(colGrp,cbo+"##"+com,col);
                }
                FilterSameCol.push(col);
                console.log(cbo+"##"+com+"##"+col,'cbo+"##"+com+"##"+col IN POPULATE');
                objPartsGrp = fnPopulateColValue(objPartsGrp,cbo+"##"+com+"##"+col,parts);
            }
            console.log(objPartsGrp,'objPartsGrp');
            console.log(colGrp,'colGrp');
        }
        console.log(fabFinish,'fabFinish');
        let objNewFourth = {}; let objPono = {}; let objSizes = {}; let objItemizedQty = {};
        if (data.jsonFabProCurrent != '') {
            excessQtyJxl = JSON.parse(data.jsonFabProCurrent);
            /************************** EXCESS QTY CALC RESULT JXL**************************/
                console.log(excessQtyJxl,'excessQtyJxl');
                let sizesColPosition = ArrSizes.length + 7;
                for (let ii = 0; ii < excessQtyJxl.length; ii++) {
                    ArrFirstFive.push([excessQtyJxl[ii][0],excessQtyJxl[ii][1],excessQtyJxl[ii][2],excessQtyJxl[ii][3],excessQtyJxl[ii][4],
                        excessQtyJxl[ii][5],excessQtyJxl[ii][6]]);
                }
                for (let kk = 0; kk < excessQtyJxl.length; kk++) {
                    xsQty = Number(excessQtyJxl[kk][6]);
                    for(var ij = 7; ij < sizesColPosition; ij++) {
                        let result = (xsQty / 100) * Number(excessQtyJxl[kk][ij]) + Number(excessQtyJxl[kk][ij]);
                        console.log(result,'result');
                        ArrFirstFive[kk].push(result);
                    }
                }
            console.log(ArrFirstFive,'ArrFirstFive');
        }
        else {
            if(data.initialData != '') {
                let oeSscJxl = data.initialData;
                console.log(oeSscJxl,'oeSscJxl');
                let sizeColumnsLen = ArrSizes.length + 7;
                console.log(sizeColumnsLen,'sizeColumnsLen');
                //console.log(oeSscJxl,'oeSscJxl');
                for(let ii = 0; ii < oeSscJxl.length; ii++) {
                    lastCol = oeSscJxl[ii].length - 1;
                    console.log(lastCol,'lastCol');
                    let cbo = oeSscJxl[ii][0];
                    let com = jsTrim(oeSscJxl[ii][1]);
                    console.log(oeSscJxl[ii][2],'oeSscJxl[ii][2]');
                    let col = jsTrim(oeSscJxl[ii][2]);
                    let poNo = oeSscJxl[ii][4];
                    let ssc = oeSscJxl[ii][5];
                    console.log(col,'colTEST');
                    objPono = fnPopulateValueArray(objPono,cbo+"##"+com+"##"+col,poNo);
                    objNewFourth = fnPopulateValueArray(objNewFourth,cbo+"##"+com+"##"+col,poNo);
                    console.log(cbo+"##"+com+"##"+col,'cbo+"##"+com+"##"+col');
                    let objPartsGrpStr = objPartsGrp[cbo+"##"+com+"##"+col];
                    console.log(objPartsGrpStr,'objPartsGrpStr');
                    parts = '';
                    if(objPartsGrpStr) {
                        parts = objPartsGrpStr.replace('undefined-','');
                    }
                    excessQtyJxl.push([cbo,com,col,parts,poNo,ssc,""]);
                    for(let s = 7; s < sizeColumnsLen; s++) {
                        //console.log(oeSscJxl[ii][7],'oeSscJxl[ii][s]');
                        objSizes = fnPopulateValueArray(objSizes,cbo+"##"+com+"##"+col+"##"+poNo,oeSscJxl[ii][s]);
                    }
                    objItemizedQty = fnPopulateValueArray(objItemizedQty,cbo+"##"+com+"##"+col+"##"+poNo,oeSscJxl[ii][lastCol]);
                }
                console.log(objItemizedQty,'objItemizedQty');
                console.log(objPono,'objPono');
                console.log(objNewFourth,'objNewFourth');
                console.log(objSizes,'objSizes');
                //Split the - (Hyphen) in Color If any
                /*for(let prop in objNewFourth) {
                    console.log(objNewFourth[prop],'objNewFourth[prop]');
                    let ArrCboCom = prop.split('##');
                    let cbo = ArrCboCom[0];
                    let com = ArrCboCom[1];
                    let col = jsTrim(ArrCboCom[2]);
                    console.log(colGrp[cbo+"##"+com],'colGrp[cbo+"##"+com]');
                    console.log(cbo,com,ArrFilterCol[c],filteredParts,poNo,'ccc');
                }*/
                console.log(excessQtyJxl,'excessQtyJxl One');
                var newsizes = '';
                for(let f = 0; f < excessQtyJxl.length; f++) {
                    let cbo = excessQtyJxl[f][0];
                    let com = excessQtyJxl[f][1];
                    let col = excessQtyJxl[f][2];
                    let poNo = excessQtyJxl[f][4];
                    let sizesStr = objSizes[cbo+"##"+com+"##"+col+"##"+poNo];
                    console.log(sizesStr,'sizesStr');
                    if(sizesStr) {
                        newsizes = sizesStr.replace('undefined|-|','');
                        if(newsizes.indexOf('|-|') !== -1) {
                            let ArrSizeData = newsizes.split('|-|');
                            for(let s = 0; s < ArrSizes.length; s++) {
                                excessQtyJxl[f].push(ArrSizeData[s]);
                            }
                        }
                    }
                }
                for(let f = 0; f < excessQtyJxl.length; f++) {
                    let cbo = excessQtyJxl[f][0];
                    let com = excessQtyJxl[f][1];
                    let col = excessQtyJxl[f][2];
                    let poNo = excessQtyJxl[f][4];
                    let itemizedQtyStr = objItemizedQty[cbo+"##"+com+"##"+col+"##"+poNo];
                    console.log(itemizedQtyStr,'itemizedQtyStr');
                    if(itemizedQtyStr) {
                        let itemizedQty = itemizedQtyStr.replace('undefined|-|','');
                        console.log(itemizedQty,'itemizedQty');
                        excessQtyJxl[f].push(itemizedQty);
                    }
                    else {

                    }
                }
                console.log(excessQtyJxl,'excessQtyJxl');
            }
        }

        jexcel(document.getElementById("fabDetailKnitColorWiseFabBlendAndContent"), {
            columns: [
                {title: 'Combo', width: 140, wordWrap: true, readOnly: true},
                {title: 'Component', width: 140, wordWrap: true, readOnly: true},
                {title: 'Colour', width: 140, wordWrap: true, readOnly: true},
                {title: 'Garment Parts', width: 130, wordWrap: true, readOnly: true},
                {title: 'Fabric Blend (%) | Lycra (%)', width: 130, wordWrap: true, readOnly: true},
                {title: 'Fabric Content', width: 135, wordWrap: true, readOnly: true},
                {title: 'Fabric Name', width: 135, wordWrap: true, readOnly: true},
                {title: 'Finishing GSM', width: 70, readOnly: true},
                {title: 'Yarn Count', width: 70, readOnly: true},
                {title: 'Dyeing Type', width: 68, readOnly: true},
                {title: 'Fabric Finish Wet Process', width: 100, wordWrap: true, readOnly: true},
                {title: 'Fabric Finish Dry Process', width: 100, wordWrap: true, readOnly: true}
            ],
            data: fabFinish
        });
        let itemizedQtyIdx = ArrSizes.length + 7;
        console.log(itemizedQtyIdx,'itemizedQtyIdx');
        firstJxlTableFooter = [];
        for(let ii = 0; ii < itemizedQtyIdx - 1; ii++) {
            firstJxlTableFooter.push('');
        }
        firstJxlTableFooter.push('');
        console.log(firstJxlTableFooter,'firstJxlTableFooter');
        columnsForJxl = [
            {title:'Combo',readOnly: true,width:130},
            {title:'Component',readOnly: true,width:130},
            {title:'Colour',readOnly: true,width:130},
            {title:'Garment Parts', width:100, readOnly: true},
            {title:'P.O. No / Enq. Ref. No.',width:135, readOnly: true},
            {title:'Size Spec. Code / Fit', width:100, readOnly: true},
            {title:'Ex. Qty (%)', width: 50}
        ];
        for(let ii = 0; ii < ArrSizes.length; ii++) {
            columnsForJxl.push({title:ArrSizes[ii], width: 60, readOnly: true});
        }
        columnsForJxl.push({title:'Itemized Qty. (Pcs.)', type:'numeric', width:100, readOnly: true, align: 'right'});

        jexcel(document.getElementById("three"), {
            columns: columnsForJxl,
            data: excessQtyJxl,
            allowInsertRow: false,
            footers: [firstJxlTableFooter],
            onchange:function () {
                unsaved = true;
            }
        });

        columnsForAJxl = [
            {title:'Combo',readOnly: true,width:130},
            {title:'Component',readOnly: true,width:130},
            {title:'Colour',readOnly: true,width:130},
            {title:'Garment Parts', width:100, multiple: true, readOnly: true},
            {title:'P.O. No / Enq. Ref. No.',width:130, readOnly: true},
            {title:'Size Spec. Code / Fit', width:120, readOnly: true},
            {title:'Ex. Qty (%)', width: 50, readOnly: true}
        ];

        for(let ii = 0; ii < ArrSizes.length; ii++) {
            columnsForAJxl.push({title:ArrSizes[ii], width: 60, readOnly: true});
        }
        let lastColIdx = ArrSizes.length + 7;
        console.log(lastColIdx,'lastColIdx');

        columnsForAJxl.push({title:'Itemized Qty. (Pcs.)', type:'numeric', width:80, readOnly: true, align: 'right'});

        jxlTableFooter = [];
        for(let ii = 0; ii < lastColIdx - 1; ii++) {
            jxlTableFooter.push('');
        }
        jxlTableFooter.push('');
        console.log(jxlTableFooter,'jxlTableFooter');
        jexcel(document.getElementById("excessQtyTbl"),{
            columns: columnsForAJxl,
            data: ArrFirstFive,
            allowInsertRow: false,
            footers:[jxlTableFooter],
            updateTable:function(instance, cell, col, row, val, label, cellName) {
                if(col == 0) {
                    totalVal = 0;
                }
                if(col >= 7 && col < lastColIdx) {
                    totalVal = parseFloat(totalVal) + parseFloat(val);
                    //console.log(totalVal,'totalValALL');
                }
                if (col === lastColIdx) {
                    totalVal = Math.round(totalVal);
                    $(cell).text(totalVal);
                    instance.jexcel.options.data[row][col] = totalVal;
                }
            }
        });
        /************************** EXCESS QTY CALC RESULT JXL ends **************************/
    });

    function cmnSaveChanges() {
        let d = $("#three").jexcel('getData');
        MakeAsynPostRequest(base_path+"fabricprogram/saveExcessQtyJxl",GlbParam+"&enqId="+enquiryId+"&saveJxl="+JSON.stringify(d),"json",function (data) {
            console.log(data,'data');
            if(data.errCode === 1) {
                unsaved = false;
                $("#divCmdSuccessMsg").removeClass('hide');
                $("#divCmnSuccessMsg").text('Saved Successfully');
                fnRedirectPageTimeOut(base_path+"fabricprogram/partsXsQty/"+HashEnquiryId);
            }
        });
    }
    function unloadPage() {
        if (unsaved) {
            return "You have unsaved changes on this page. Do you want to leave this page and discard your changes or stay on this page?";
        }
    }
    window.onbeforeunload = unloadPage;
</script>
<?php $this->load->view(CNFCOMPANY.'template/pagefooter'); ?>