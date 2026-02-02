<?php
$this->load->view(CNFCOMPANY.'template/pageheader');
?>
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/jexcel4/jsuites.css"/>
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/jexcel4/jexcel.css"/>
    <body class="sidebar-mini skin-black wysihtml5-supported sidebar-collapse">
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
                                    <strong>FABRIC CONSUMPTION CALCULATION (Qty. * Piece Wgt.)</strong>
                                    </div>
                                </div>
                                <div class="col-sm-12 table-responsive" style="padding: 5px !important">
                                <div id="six"></div>
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
    const tableId = '<?php echo $VarTableId ?>';
    const GlbParam = 'rFrom=1';
    unsaved = false;
    function fnPopulateValueArray(ArrName, KeyValue, InsertVal) {
        if (jQuery.inArray(KeyValue, ArrName)) {
            ArrName[KeyValue] = ArrName[KeyValue]+"|-|"+InsertVal;
        }
        return ArrName;
    }
    MakeAsynPostRequest(base_path+"fabricprogram/ajaxData","rFrom=1&enqId="+enquiryId+"&pid="+pageId,"json",function (data) {
        console.log(data, 'data');
        ArrSizes = data.ArrSizeChart;
        var sixJxl = []; var consumptionCalc = []; var objPieceWeight = {};
        let xsQtyJxlSizeColPos = ArrSizes.length + 7;
        var tempArr = [];
        //let itemizedQtyCol = ArrSizes.length + 6;
        let objExcessQtyGroup = {};
        if (data.jsonPartsAndExcessQty != "") {
            let ArrFirstFive = [];
            var json3in4pJxl = JSON.parse(data.jsonPartsAndExcessQty);
            console.log(json3in4pJxl, 'json3in4pJxl');
            for (let ii = 0; ii < json3in4pJxl.length; ii++) {
                let xsQty = Number(json3in4pJxl[ii][6]);
                //console.log(itemizedQtyCol,'itemizedQtyCol');
                let itemizedQty = 0;
                ArrFirstFive.push([json3in4pJxl[ii][0],json3in4pJxl[ii][1],json3in4pJxl[ii][2],json3in4pJxl[ii][3],json3in4pJxl[ii][4],
                    json3in4pJxl[ii][5],json3in4pJxl[ii][6]]);
                for(let ij = 7; ij < xsQtyJxlSizeColPos; ij++) {
                    let eachSizeCalc = (xsQty / 100) * Number(json3in4pJxl[ii][ij]) + Number(json3in4pJxl[ii][ij]);
                    if(json3in4pJxl[ii][3].indexOf('-') === -1) {
                        objExcessQtyGroup = fnPopulateValueArray(objExcessQtyGroup,json3in4pJxl[ii][0]+"|#|"+json3in4pJxl[ii][1]+"|#|"+
                            json3in4pJxl[ii][2]+"|#|"+json3in4pJxl[ii][3]+"|#|"+json3in4pJxl[ii][4]+"|#|"+json3in4pJxl[ii][5]+"|#|"+xsQty+"|#|"+itemizedQty,eachSizeCalc);
                    }
                    else {
                        let partsArr = json3in4pJxl[ii][3].split('-');
                        for(let jj = 0; jj < partsArr.length; jj++) {
                            objExcessQtyGroup = fnPopulateValueArray(objExcessQtyGroup,
                                json3in4pJxl[ii][0]+"|#|"+json3in4pJxl[ii][1]+"|#|"+json3in4pJxl[ii][2]+"|#|"+partsArr[jj]+"|#|"+
                                json3in4pJxl[ii][4]+"|#|"+json3in4pJxl[ii][5]+"|#|"+xsQty+"|#|"+itemizedQty,eachSizeCalc);
                        }
                    }
                }
            }
            for (let kk = 0; kk < json3in4pJxl.length; kk++) {
                let xsQty = Number(json3in4pJxl[kk][6]);
                for(var ij = 7; ij < xsQtyJxlSizeColPos; ij++) {
                    let result = (xsQty / 100) * Number(json3in4pJxl[kk][ij]) + Number(json3in4pJxl[kk][ij]);
                    ArrFirstFive[kk].push(result);
                }
            }

            console.log(objExcessQtyGroup,'objExcessQtyGroup');
        }
        let pieceWeight = [];
        if(data.jsonFabProPieceWeight != "") {
            let objFiveJxl = {};
            for(let ii = 0; ii < json3in4pJxl.length; ii++) {
                let com = json3in4pJxl[ii][1];
                let parts = json3in4pJxl[ii][3];
                let ssc = json3in4pJxl[ii][5];
                if(parts.indexOf('-') === -1) {
                    if(jQuery.inArray(com+"|#|"+parts+"|#|"+ssc,tempArr) === -1) {
                        tempArr.push(com+"|#|"+parts+"|#|"+ssc);
                    }
                    //objFiveJxl[com+"|#|"+parts+"|#|"+ssc] = [com,parts,ssc];
                }
                else {
                    let ArrParts = parts.split('-');
                    for(let jj = 0; jj < ArrParts.length; jj++) {
                        //objFiveJxl[com+"|#|"+ArrParts[jj]+"|#|"+ssc] = [com,ArrParts[jj],ssc];
                        if(jQuery.inArray(com+"|#|"+ArrParts[jj]+"|#|"+ssc,tempArr) === -1) {
                            tempArr.push(com+"|#|"+ArrParts[jj]+"|#|"+ssc);
                        }
                    }
                }
            }
            //console.log(objFiveJxl,'objFiveJxl');
            /*for(let prop in objFiveJxl) {
                pieceWeight.push(objFiveJxl[prop]);
            }*/

            if(data.jsonFabProPieceWeight != '') {
                console.log(pieceWeight,'pieceWeight');
                let savedData = JSON.parse(data.jsonFabProPieceWeight);
                console.log(savedData,'savedData');
                /*for(let c = 0; c < pieceWeight.length; c++) {
                    for(let s = 0; s <= savedData.length; s++) {
                        pieceWeight[c].push(savedData[c][s]);
                    }
                }*/
                for(let c = 0; c < tempArr.length; c++) {
                    let ini = tempArr[c].split('|#|');
                    console.log(ini,'ini');
                    console.log(savedData[c],'savedData[c]');
                    if(savedData[c]) {
                        //ini.push(savedData[c]);
                        let newArr = ini.concat(savedData[c]);
                        console.log(newArr,'newArr');
                        //console.log(ini,'AFTTER PUSH');
                        pieceWeight.push(newArr);
                    }
                }
            }
        }
        let pcsWeightJxlSizeColPos = ArrSizes.length + 5;
        for(let ii = 0; ii < pieceWeight.length; ii++) {
            for(let kk = 5; kk < pcsWeightJxlSizeColPos; kk++) {
                objPieceWeight = fnPopulateValueArray(objPieceWeight,pieceWeight[ii][0]+"|#|"+pieceWeight[ii][1]+"|#|"+pieceWeight[ii][2],pieceWeight[ii][kk]);
            }
        }
        console.log(objPieceWeight,'objPieceWeight');
        for(let prop in objExcessQtyGroup) {
            let propSplitted = prop.split('|#|');
            let component = propSplitted[1];
            let parts = propSplitted[3];
            let spc = propSplitted[5];
            let col = propSplitted[2];
            let ArrExcessQty = objExcessQtyGroup[prop].split('|-|');
            console.log(component+"|#|"+parts+"|#|"+spc);
            console.log(objPieceWeight[component+"|#|"+parts+"|#|"+spc],'objPieceWeight[component+"|#|"+parts+"|#|"+spc]');
            let objPieceWeightString = objPieceWeight[component+"|#|"+parts+"|#|"+spc];
            if(objPieceWeightString !== undefined) {
                console.log(objPieceWeightString,'objPieceWeightString');
                let ArrPieceWeight = objPieceWeightString.split('|-|');
                console.log(ArrPieceWeight,'ArrPieceWeight');
                let sum = ArrExcessQty.map(function (val,idx) {
                    if(val != "undefined" && ArrPieceWeight[idx] != "undefined") {
                        if(val !== undefined && ArrPieceWeight[idx] !== undefined) {
                            console.log(val+"val "+ArrPieceWeight[idx]+"ArrPieceWeight[idx]");
                            let res = Number(val) * Number(ArrPieceWeight[idx]);
                            console.log(res,'result');
                            return res.toFixed(3);
                        }
                    }
                });
                console.log(sum,'sum');
                sum = sum.filter(myFunc);
                function myFunc(arr) {
                    return arr !== undefined;
                }
                console.log(sum,'sum AFTER Filter');
                sixJxl.push([propSplitted[0],component,col,parts,propSplitted[4],spc,""]);
                consumptionCalc.push(sum);
            }
        }
        console.log(consumptionCalc,'consumptionCalc');
        for(let cc = 0; cc < sixJxl.length; cc++) {
            //console.log(xsQtyJxlSizeColPos,'xsQtyJxlSizeColPos');
            for(let a = 0; a < ArrSizes.length; a++) {
                //console.log(consumptionCalc[cc][a],'consumptionCalc[cc][a]');
                //console.log(sixJxl[cc],'sixJxl[cc]');
                sixJxl[cc].push(consumptionCalc[cc][a]);
            }
        }
        console.log(sixJxl,'sixJxl');
        if(data.currentJxl != "") {
            sixJxl = JSON.parse(data.currentJxl);
        }
        else {

        }
        columnsForJxl = [
            {title:'Combo',width:120, readOnly: true},
            {title:'Component',readOnly: true, width:120},
            {title:'Color',readOnly: true, width:120},
            {title:'Garment Parts', width:120, readOnly: true},
            {title:'P.O. No / Enq. Ref. No.',readOnly: true, width:120},
            {title:'Size Spec. Code / Fit', width:130, readOnly: true},
            {title:'Processing<br/>Loss (%)', width:65}
        ];

        for(let ij = 0; ij < ArrSizes.length; ij++) {
            columnsForJxl.push({title: ArrSizes[ij], type: 'numeric', width: 70, readOnly: true, align: 'right'});
        }
        console.log(columnsForJxl,'columnsForJxl');
        jexcel(document.getElementById("six"),{
            columns: columnsForJxl,
            data: sixJxl,
            onchange:function () {
                unsaved = true;
            }
        });


    });

    function cmnSaveChanges() {
        let d = $("#six").jexcel('getData');
        MakeAsynPostRequest(base_path+"fabricprogram/saveSixJxl","rFrom=1&enqId="+enquiryId+"&d="+JSON.stringify(d)+
            "&pid="+pageId+"&tid="+tableId,"json",function (data) {
            if (typeof(Storage) !== "undefined") {
                localStorage.setItem("locStr_sevenJxl","");
                localStorage.setItem("locStr_sevenAJxl","");
            } else {
                // Sorry! No Web Storage support..
            }
            if(data.errCode === 1) {
                unsaved = false;
                $("#divCmnSuccessMsg").removeClass('hide');
                $("#divCmnSuccessMsg").text('Saved Successfully');
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