<?php
$this->load->view(CNFCOMPANY . 'template/pageheader');
?>
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/jexcel4/jsuites.css"/>
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/jexcel4/jexcel.css"/>
<body class="sidebar-mini skin-black wysihtml5-supported sidebar-collapse">
<div class="wrapper" >
    <?php $this->load->view(CNFCOMPANY . 'template/templateheader'); ?>

    <aside class="main-sidebar">
        <?php $this->load->view(CNFCOMPANY . 'template/templateleftmenu'); ?>
    </aside>

    <div class="content-wrapper order-entry" >

        <div class="col-md-12">
            <section class="content-header">
                <h1 class="firstHeading">Order Entry</h1>
            </section>
        </div>

        <section class="content">
            <div class="dropdown"></div>
            <div class="row">
                <div class="col-md-12">
                    <div id="DivContBasicInfo">
                        <div class="box box-info">
                            <div class="box-header with-border"></div>
                            <div class="box-body" style="padding: 0px;">
                                <form class="form-horizontal" name="frmBasicInfo" id="frmOrderProcess" method="post">
                                    <div class="col-md-12 pd0 no-padding">
                                        <?php
                                        $this->load->view(CNFCOMPANY."orderentry/orderentrycommondetails");
                                        $ArrPcsSet = unserialize(ARRPCSSET);
                                        $VarPcsOrSet = $ArrPcsSet[$ArrCommonHeaderData['ArrEnquiryDetails']['pcsorset']];
                                        ?>
                                        <div style="background-color: rgb(210 253 249); padding-top: 10px; padding-left: 10px"><strong>SIZE WISE QTY. BREAK-UP</strong></div>
                                    </div>
                                    <div class="col-sm-12 table-responsive" style="padding: 5px !important">
                                        <div class="form-group">
                                            <div id="thirdtbl"></div>

                                        </div>
                                    </div>
                                    <div class="col-md-12" style="padding: 5px">
                                        <div class="form-group" style="margin-bottom: 0">
                                            <label for="tblRemarks">Remarks</label>
                                            <textarea id="frmBasicRemarks" name="frmBasicRemarks" title="Remarks" class="form-control"></textarea>
                                        </div>
                                    </div>
                                </form>
                                <div class="col-md-12" style="padding: 5px">
                                    <?php $this->load->view(CNFCOMPANY . "orderentry/footerNavSave"); ?>
                                </div>

                                <div class="col-md-12">
                                    <div class="alert alert-success alert-dismissable hide" id="divSuccessBasicInfoMsg"> </div>
                                    <div class="alert alert-danger alert-dismissable hide" id="ErrOrderEntry"> </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </section>
    </div>
    <?php $this->load->view(CNFCOMPANY . 'template/templatefooter'); ?>
    <div class="control-sidebar-bg"></div>
</div>
<script src="<?php echo base_url(); ?>assets/js/jexcel4/jsuites.js"></script>
<script src="<?php echo base_url(); ?>assets/js/jexcel4/jexcel.js"></script>
<script type="text/javascript">
    let enquiryId = '<?php echo $VarEnquiryId ?>';
    let GlbParam = 'rfrom=1';
    unsaved = false;
    let GlbArrSecondTbl = [];
    let param = GlbParam+"&enqid="+enquiryId; let ArrSizeChartHeader = [];
    let GlbComboArr, GlbComponentArr, GlbColorArr, GlbArrIntake,GlbArrPoNumber = [];
    let GlbArrPcsSet = []; let GlbData = [[]];
    SUMCOL = function(instance, columnId) {
        let total = 0;
        for (let j = 0; j < instance.options.data.length; j++) {
            if (Number(instance.records[j][columnId-1].innerHTML)) {
                total += Number(instance.records[j][columnId-1].innerHTML);
            }
        }
        return total;
    };

    MakePostRequest(base_path+'orderentryvtwo/thirdTbl',param,'json',getSecondTblDataRes);
    function getSecondTblDataRes(data) {
        console.log(data,'data');
        if(data.remarks !== "") {
            document.getElementById("frmBasicRemarks").innerText = data.remarks;
        }
        if(data.ArrSecondTbl != '') {
            GlbArrSecondTbl = data.ArrSecondTbl;
        }
        ArrSizeChartHeader = data.ArrSizeChartData;
        console.log(data.ArrSecondTbl,'ArrSecondTbl');
        if(data.jsonThirdTbl !== "") {
            sizeDatas = JSON.parse(data.jsonThirdTbl);
            console.log(sizeDatas,'sizeDatas saved');
            console.log(sizeDatas.length,'sizeDatas LEN');
            if(GlbArrSecondTbl != "") {
                console.log(GlbArrSecondTbl,'GlbArrSecondTbl INI');
                for(let ii = 0; ii < GlbArrSecondTbl.length; ii++) {
                    for(let jj = 0; jj < sizeDatas[ii].length; jj++) {
                        console.log(sizeDatas[ii][jj],'sizeDatas[ii][jj]');
                        GlbArrSecondTbl[ii].push(sizeDatas[ii][jj]);
                    }
                }
                console.log(GlbArrSecondTbl,'GlbArrSecondTbl FULL');
            }
            GlbData = GlbArrSecondTbl;
        }
        else {
            GlbData = GlbArrSecondTbl;
        }
        console.log(GlbData,'GlbData SET');

        GlbArrPcsSet = data.ArrPcsSet;
        GlbComboArr  = data.comboarr;
        GlbComponentArr = data.components;
        GlbColorArr     = data.colorarr;
        GlbArrIntake    = data.ArrIntake;
        GlbArrPoNumber  = data.ArrPoNumber;

    }
    GlbComboArr = getUnique(GlbComboArr);
    GlbComponentArr = getUnique(GlbComponentArr);
    GlbColorArr = getUnique(GlbColorArr);
    GlbArrIntake = getUnique(GlbArrIntake);
    GlbArrPoNumber = getUnique(GlbArrPoNumber);
    console.log(GlbData,'parse Glbdata ');
    // A custom method to SUM all the cells in the current column

    console.log(GlbArrPcsSet,'GlbArrPcsSet');

    columnsForJxl = [
        {title:'Combo',readOnly: true,width:150},
        {title:'Component',readOnly: true,width:150},
        {title:'Colour',readOnly: true,width:150},
        {title:'Intake Qty. Per Comp. (Nos.)',readOnly: true,width: 100},
        {title:'P.O. No / Enq. Ref. No.',width:150, readOnly: true}
    ];

    jxlSizeColumnCount = 4;
    for (let i = 0; i < ArrSizeChartHeader.length; i++) {
        console.log(ArrSizeChartHeader[i]);
        if (ArrSizeChartHeader[i]) {
            columnsForJxl.push({title:ArrSizeChartHeader[i], type:'numeric',width:55, align: 'right'});
        }
        jxlSizeColumnCount++;
    }
    poQtyColId = jxlSizeColumnCount + 1;
    pcsSetFilter = function() {
        console.log(GlbArrPcsSet,'GlbArrPcsSet');
        return GlbArrPcsSet;
    };
    columnsForJxl.push(
        {title:'P.O. Qty. / Sample Qty.',type:'numeric',width:100, readOnly: true, align: 'right'},
        {title:'Pcs./ Set', type:'dropdown', source: ["Pcs.","Set"], width:80, filter: pcsSetFilter }
    );
    jxlTableFooter = [];
    for(let ii = 0; ii < jxlSizeColumnCount; ii++) {
        jxlTableFooter.push('');
    }
    console.log(jxlSizeColumnCount,'jxlSizeColumnCount');
    let pcsSetCol = jxlSizeColumnCount + 2;
    let pcsSet = '<?php echo @$VarPcsOrSet ?>';
    jxlTableFooter.push('Total','=SUMCOL(TABLE(), COLUMN())','<?php echo $VarPcsOrSet ?>');
    console.log(jxlTableFooter,'jxlTableFooter');
    jexcel(document.getElementById('thirdtbl'), {
        columns: columnsForJxl,
        data: GlbData,
        onchange: function() {
            unsaved = true;
        },
        updateTable:function(instance, cell, col, row, val, label, cellName) {
            //console.log(label,'label');
            if (col === 0) {
                colsVal = 0;
            }
            if (col >= 5 && col <= jxlSizeColumnCount) {
                colsVal = colsVal + parseInt(val);
            }
            if(col === poQtyColId) {
                $(cell).text(colsVal);
                instance.jexcel.options.data[row][col] = colsVal;
            }
            if(col === pcsSetCol) {
                $(cell).text(pcsSet);
                instance.jexcel.options.data[row][col] = pcsSet;
            }
        },
        //footers: [['','','','','','','','','','','','','Total Qty.','=SUMCOL(TABLE(), COLUMN())','<?php //echo $VarPcsOrSet ?>']],
        footers: [jxlTableFooter],
        columnDrag:true,
        allowInsertRow:false,
        allowInsertColumn:false
    });

    let GlbthirdTblLength = $("#thirdtbl").jexcel('getData').length;
    GlbthirdTblLength = GlbthirdTblLength - 1;

    function fnSaveChanges() {
        var sizeData = [];
        /*console.log(ArrSizeChartHeader,'ArrSizeChartHeader');
        console.log(ArrSizeChartHeader.length);
        for(let ii = 0; ii < ArrSizeChartHeader.length; ii++) {
            let sizeColStarts = 5;
            let sizeCols = sizeColStarts + ii;
            let sizeColData = $("#thirdtbl").jexcel('getColumnData',sizeCols);
            console.log(sizeColData,'sizeColData');
            sizeData.push(sizeColData);
        }*/
        let fullData = $("#thirdtbl").jexcel('getData');
        let sizeColStarts = 5;
        for(let ii = 0; ii < fullData.length; ii++) {
            sizeData[ii] = [];
            for(let jj = 0; jj < ArrSizeChartHeader.length; jj++) {
                let sizeCols = sizeColStarts + jj;
                console.log(fullData[ii][sizeCols],'fullData[ii][sizeCols]');
                sizeData[ii].push(Number(fullData[ii][sizeCols]));
            }
        }
        console.log(sizeData,'sizeData');
        let tblRemarks = $("#frmBasicRemarks").val();
        let param = GlbParam+"&d="+JSON.stringify(sizeData)+"&enqId="+enquiryId+"&e="+encodeURIComponent(tblRemarks);
        MakeAsynPostRequest(base_path+'orderentryvtwo/saveThirdTbl',param,'json',fnSaveTableRes);
        /*let tblRemarks = $("#frmBasicRemarks").val();
        let param = GlbParam+"&d="+JSON.stringify(fulldata)+"&enqId="+enquiryId+"&e="+encodeURIComponent(tblRemarks);
        MakeAsynPostRequest(base_path+'orderentryvtwo/saveThirdTbl',param,'json',fnSaveTableRes);*/
    }
    function fnSaveTableRes(data) {
        console.log(data,'data');
        if(data!='') {
            if(data.errcode=='-1'){
                $("#ErrOrderEntry").removeClass('hide');
                $('#ErrOrderEntry').text(data.msg);
                return false;
            } else if(data.errcode==1) {
                unsaved = false;
                $("#divSuccessBasicInfoMsg").removeClass('hide');
                $("#divSuccessBasicInfoMsg").text("Saved Successfully");
                //fnRedirectPageTimeOut(base_path+'orderentryvtwo/fourthTbl/'+hashenquiryid);
            }
        }
    }
    function unloadPage() {
        if(unsaved) {
            return "You have unsaved changes on this page. Do you want to leave this page and discard your changes or stay on this page?";
        }
    }
    window.onbeforeunload = unloadPage;
</script>
<?php $this->load->view(CNFCOMPANY . 'template/pagefooter'); ?>