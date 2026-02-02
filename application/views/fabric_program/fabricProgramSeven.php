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
                                        <strong>FABRIC CONSUMPTION CALCULATION (Qty. * Piece Wgt. * Processing Loss)</strong>
                                    </div>
                                </div>
                                <div class="col-sm-12 table-responsive" style="padding: 5px !important">
                                    <div id="seven"></div>
                                </div>
                                <div class="col-md-12 no-padding">
                                    <div style="background-color: rgb(210 253 249); padding-top: 10px; padding-left: 10px">
                                        <strong>FABRIC CONSUMPTION CALCULATION - Cumulative (Size Spec. Code Wise)</strong>
                                    </div>
                                </div>
                                <div class="col-sm-12 table-responsive" style="padding: 5px !important">
                                    <div id="sevenA"></div>
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
    unsaved = false;
    var sixJxl = []; var sevenJxl = []; var sevenAJxl = [];
    // A custom method to SUM all the cells in the current column
    SUMCOL = function(instance, columnId) {
        let total = 0;
        for (let j = 0; j < instance.options.data.length; j++) {
            if (Number(instance.records[j][columnId-1].innerHTML)) {
                total += Number(instance.records[j][columnId-1].innerHTML);
            }
        }
        return total.toFixed(3);
    };

    MakeAsynPostRequest(base_path+"fabricprogram/ajaxData","rFrom=1&enqId="+enquiryId+"&pid="+pageId,"json",function (data) {
        console.log(data, 'data');
        ArrSizes = data.ArrSizeChart;
        if(data.jsonProCcProcessLoss != "") {
            sixJxl = JSON.parse(data.jsonProCcProcessLoss);
        }
        console.log(sixJxl,'sixJxl');
        let loopingSizeWithPrev = ArrSizes.length + 7;
        var eachSizeProcessLoss = [];
        for(let ii = 0; ii < sixJxl.length; ii++) {
            sevenJxl.push([sixJxl[ii][0],sixJxl[ii][1],sixJxl[ii][2],sixJxl[ii][3],sixJxl[ii][4],sixJxl[ii][5]]);
            let processLoss = Number(sixJxl[ii][6]);
            for(let s = 7; s < loopingSizeWithPrev; s++) {
                eachSizeProcessLoss = (processLoss / 100) * Number(sixJxl[ii][s]) + Number(sixJxl[ii][s]);
                sevenJxl[ii].push(eachSizeProcessLoss.toFixed(3));
            }
        }
        console.log(sevenJxl,'sevenJxl');
        columnsForJxl = [
            {title:'Combo',width:150, readOnly: true},
            {title:'Component',readOnly: true, width:150},
            {title:'Color',readOnly: true, width:145},
            {title:'Garment Parts', width:130, readOnly: true},
            {title:'P.O. No / Enq. Ref. No.',readOnly: true, width:130},
            {title:'Size Spec. Code / Fit', width:130, readOnly: true}
        ];

        for(let s = 0; s < ArrSizes.length; s++) {
            columnsForJxl.push({title: ArrSizes[s], width: 65, readOnly: true, align: 'right'});
        }
        //
        columnsForJxl.push({title: 'Total', width: 80, readOnly: true, align: 'right'});
        let totalColumnFirstTable = ArrSizes.length + 6;
        console.log(totalColumnFirstTable,'totalColumnFirstTable');
        jxlTableFooter = [];
        for(let ii = 0; ii < totalColumnFirstTable - 1; ii++) {
            jxlTableFooter.push('');
        }
        jxlTableFooter.push('Total','=SUMCOL(TABLE(), COLUMN())');
        console.log(jxlTableFooter,'jxlTableFooter');
        //
        jexcel(document.getElementById("seven"), {
            columns: columnsForJxl,
            data: sevenJxl,
            footers: [jxlTableFooter],
            onchange:function () {
                unsaved = true;
            },
            updateTable:function(instance, cell, col, row, val, label, cellName) {
                if(col === 0) {
                    totalVal = 0;
                }
                if(col >= 6 && col < totalColumnFirstTable) {
                    totalVal = parseFloat(totalVal) + parseFloat(val);
                    console.log(totalVal,'totalValALL');
                }
                if (col === totalColumnFirstTable) {
                    totalVal = totalVal.toFixed(3);
                    $(cell).text(totalVal);
                    instance.jexcel.options.data[row][col] = totalVal;
                }
            },
        });
        function fnSumSizeArrayValue(ArrSizeVal) {
            var SumVal = 0;
            var ArrName = ArrSizeVal.split("-");
            if(ArrName.length > 1) {
                for (var i = 0; i < ArrName.length; i++) {
                    if(ArrName[i] !== "undefined" && ArrName[i] != undefined) {
                        if (!isNaN(ArrName[i])) {
                            SumVal = parseFloat(ArrName[i]) + SumVal;
                        }
                    }
                }
            }
            return SumVal;
        }
        function fnPopulateValueArray(ArrName, KeyValue, InsertVal) {
            if (jQuery.inArray(KeyValue, ArrName)) {
                ArrName[KeyValue] = ArrName[KeyValue]+"-"+InsertVal;
            }
            return ArrName;
        }
        var sevenJxlGroup = {};
        let sizeColumns = ArrSizes.length + 6;
        GlbSizeValGroup = {};
        for(let ii = 0; ii < sevenJxl.length; ii++) {
            for(let s = 6; s < sizeColumns; s++) {
                GlbSizeValGroup = fnPopulateValueArray(GlbSizeValGroup,sevenJxl[ii][0] + "##" + sevenJxl[ii][1] + "##" + sevenJxl[ii][2] + "##" + sevenJxl[ii][3] + "##" +
                    sevenJxl[ii][5]+"##"+s,sevenJxl[ii][s]);
            }
        }
        var GlbItemSumVal = {};
        for(let prop in GlbSizeValGroup) {
            let leftSide = prop.split('##');
            GlbItemSumVal[leftSide[0]+"##"+leftSide[1]+"##"+leftSide[2]+"##"+leftSide[3]+"##"+leftSide[4]+"##"+leftSide[5]] = fnSumSizeArrayValue(GlbSizeValGroup[prop]);
        }

        for(let prop in GlbItemSumVal) {
            var leftSide = prop.split('##');
            let cbo = leftSide[0]; let com = leftSide[1]; let col = leftSide[2]; let parts = leftSide[3]; let ssc = leftSide[4];
            let sizeSum = GlbItemSumVal[prop];
            sevenJxlGroup = fnPopulateValueArray(sevenJxlGroup,cbo+"##"+com+"##"+col+"##"+parts+"##"+ssc,sizeSum);
        }
        console.log(sevenJxlGroup,'sevenJxlGroup');
        var ss = 0;
        for(let prop in sevenJxlGroup) {
            let leftSide = prop.split('##');
            let cbo = leftSide[0]; let com = leftSide[1]; let col = leftSide[2]; let parts = leftSide[3]; let ssc = leftSide[4];
            let sizeStr = sevenJxlGroup[prop];
            sizeStr = sizeStr.replace('undefined-','');
            let ArrSizeVal = sizeStr.split('-');
            sevenAJxl.push([cbo,com,col,parts,ssc]);
            for(let s = 0; s < ArrSizeVal.length; s++) {
                sevenAJxl[ss].push(ArrSizeVal[s]);
            }
            ss++;
        }
        console.log(sevenAJxl,'sevenAJxl');
        if (typeof(Storage) !== "undefined") {
            // Code for localStorage/sessionStorage.
            localStorage.setItem("locStr_sevenJxl",JSON.stringify(sevenJxl));
            localStorage.setItem("locStr_sevenAJxl",JSON.stringify(sevenAJxl));
        } else {
            // Sorry! No Web Storage support..
        }
        columnsForAJxl = [
            {title:'Combo',width:145, readOnly: true},
            {title:'Component',readOnly: true, width:135},
            {title:'Color',readOnly: true, width:140},
            {title:'Garment Parts', width:145, readOnly: true},
            {title:'Size Spec. Code / Fit', width:145, readOnly: true}
        ];

        for(let s = 0; s < ArrSizes.length; s++) {
            columnsForAJxl.push({title: ArrSizes[s], width: 70, readOnly: true, align: 'right'});
        }
        columnsForAJxl.push({title: 'Total', width: 80, readOnly: true, align: 'right'});
        let totalColumnIdx = ArrSizes.length + 5;
        console.log(totalColumnIdx,'totalColumnIdx');
        jxlTableFooter = [];
        for(let ii = 0; ii < totalColumnIdx - 1; ii++) {
            jxlTableFooter.push('');
        }
        jxlTableFooter.push('Total','=SUMCOL(TABLE(), COLUMN())');
        console.log(jxlTableFooter,'jxlTableFooter');
        /**Integrate Total column**/
        jexcel(document.getElementById("sevenA"), {
            columns : columnsForAJxl,
            data: sevenAJxl,
            onchange:function () {
                unsaved = true;
            },
            updateTable:function(instance, cell, col, row, val, label, cellName) {
                if(col === 0) {
                    totalVal = 0;
                }
                if(col >= 5 && col < totalColumnIdx) {
                    totalVal = parseFloat(totalVal) + parseFloat(val);
                    console.log(totalVal,'totalValALL');
                }
                if (col === totalColumnIdx) {
                    totalVal = totalVal.toFixed(3);
                    $(cell).text(totalVal);
                    instance.jexcel.options.data[row][col] = totalVal;
                }
            },
            footers:[jxlTableFooter]
        });

    });

    function unloadPage() {
        if (unsaved) {
            return "You have unsaved changes on this page. Do you want to leave this page and discard your changes or stay on this page?";
        }
    }
    window.onbeforeunload = unloadPage;
</script>
<?php $this->load->view(CNFCOMPANY.'template/pagefooter'); ?>