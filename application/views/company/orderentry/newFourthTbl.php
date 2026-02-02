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

                                                <div style="background-color: rgb(210 253 249); padding-top: 10px; padding-left: 10px">
                                                    <strong>COMPONENT INTAKE WISE - ITEMIZED QTY.</strong>
                                                </div>

                                    </div>
                                    <div class="col-md-12 table-responsive" style="padding: 5px !important">

                                            <div id="newFourthJxl"></div>

                                    </div>

                                    <div class="col-md-12" style="padding: 5px !important;">
                                        <div class="form-group" style="margin-bottom: 0">
                                            <label for="oeRemarks">Remarks</label>
                                            <textarea id="frmBasicRemarks" title="Remarks" class="form-control"></textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-12" style="padding: 5px">
                                        <?php $this->load->view(CNFCOMPANY . "orderentry/footerNavSave"); ?>
                                    </div>
                                    <div class="col-md-12">

                                        <div class="alert alert-success alert-dismissable hide" id="divSuccessBasicInfoMsg"> </div>
                                        <div class="alert alert-danger alert-dismissable hide" id="ErrOrderEntry"> </div>
                                    </div>
                                </form>
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
    var enquiryid = '<?php echo $VarEnquiryId ?>';
    var GlbParam = 'rfrom=1';
    var param = GlbParam+"&enqid="+enquiryid;
    var Glbdata = 0; var ArrSizes = []; unsaved = false;
    GlbJsonNewFourthTbl = '';


    MakePostRequest(base_path+'orderentryvtwo/newFourthTbl',param,'json',getSecondTblDataRes);
    console.log(Glbdata,'Glbdata before');
    function getSecondTblDataRes(data) {
        console.log(data,'data');
        if(data.remarks !== "") {
            document.getElementById("frmBasicRemarks").innerText = data.remarks;
        }
        console.log(data.ArrThirdTblGrid,'$ArrThirdTblGrid');

        ArrSizes  = data.ArrSizeChartData;
        currentData = [];
        if(data.jsonNewFourthTbl != "") {
            GlbJsonNewFourthTbl = data.jsonNewFourthTbl;
            console.log(GlbJsonNewFourthTbl,'GlbJsonNewFourthTbl in if');
        }
        else if(data.ArrThirdTblGrid != "") {
            GlbJsonNewFourthTbl = data.ArrThirdTblGrid;
            console.log(GlbJsonNewFourthTbl,'GlbJsonNewFourthTbl from third');
        }
        else {
            GlbJsonNewFourthTbl = [[]];
        }
    }
    jxlSizeColumnCount = 5;
    columnsForJxl = [
        {title:'Combo',width:150, readOnly: true},
        {title:'Component',width:150, readOnly: true},
        {title:'Colour',width:150, readOnly: true},
        {title:'Intake Qty. Per Comp. (Nos.)',width:100, readOnly: true},
        {title:'P.O. No / Enq. Ref. No.',width:135, readOnly: true},
        {title:'Size Spec. Code / Fit',width:135}
    ];
    for (let ii = 0; ii < ArrSizes.length; ii++) {
        columnsForJxl.push({title:ArrSizes[ii], type:'numeric', width:55, align:'right', readOnly: true});
        jxlSizeColumnCount++;
    }
    itemizedPoQtyColId = jxlSizeColumnCount + 1;
    columnsForJxl.push({ title:'Itemized Qty. (Pcs.)', width:90, align:'right', readOnly: true });
    console.log(Glbdata,'Glbdata after re');
    console.log(GlbJsonNewFourthTbl,'GlbJsonNewFourthTbl');

    // A custom method to SUM all the cells in the current column
    SUMCOL = function(instance, columnId) {
        var total = 0;
        for (var j = 0; j < instance.options.data.length; j++) {
            if (Number(instance.records[j][columnId-1].innerHTML)) {
                total += Number(instance.records[j][columnId-1].innerHTML);
            }
        }
        return total;
    };
    console.log(jxlSizeColumnCount,'jxlSizeColumnCount');
    console.log(ArrSizes,'ArrSizes');
    jxlTableFooter = [];
    for(let ii = 0; ii < jxlSizeColumnCount; ii++) {
        jxlTableFooter.push('');
    }
    jxlTableFooter.push('Total','=SUMCOL(TABLE(), COLUMN())');
    console.log(jxlTableFooter,'jxlTableFooter');
    console.log(GlbJsonNewFourthTbl,'GlbJsonNewFourthTbl');
    console.log(itemizedPoQtyColId,'itemizedPoQtyColId');
    jexcel(document.getElementById('newFourthJxl'), {
        columns: columnsForJxl,
        data: GlbJsonNewFourthTbl,
        onchange: function() {
            unsaved = true
        },
        updateTable:function(instance, cell, col, row, val, label, cellName) {
            if (col === 0) {
                colsVal = 0;
            }
            if(col === 5) {
                $(cell).html(jsTrim(val));
                instance.jexcel.options.data[row][col] = jsTrim(val);
            }
            if(col >= 6 && col <= jxlSizeColumnCount) {
                console.log(val,'val in between');
                console.log(typeof val,'val type');
                if (val !== "") {
                    colsVal = colsVal + parseInt(val);
                }
            }
            if (col === itemizedPoQtyColId) {
                $(cell).html(colsVal);
                console.log(colsVal,'test');
                instance.jexcel.options.data[row][col] = colsVal;
            }
        },
        footers: [jxlTableFooter],
        //footers: [['','','','','','','','','','','','','','Total Qty.','=SUMCOL(TABLE(), COLUMN())']],
        allowInsertRow:false,
        allowInsertColumn:false,
        columnDrag:true,
    });

    function fnSaveChanges () {
        let fullData = $("#newFourthJxl").jexcel('getData');
        let tblRemarks = $("#frmBasicRemarks").val();
        let sizeCount = ArrSizes.length;
        let param = GlbParam+"&fullData="+JSON.stringify(fullData)+"&enqid="+enquiryid+"&e="+encodeURIComponent(tblRemarks)+"&sizeCount="+sizeCount;
        MakeAsynPostRequest(base_path+'orderentryvtwo/saveNewFourthTbl',param,'json',fnSaveTableRes);
    }

    function fnSaveTableRes(data) {
        console.log(data,'data');
        if(data!='') {
            if(data.errcode=='-1'){
                $("#ErrOrderEntry").removeClass('hide');
                $('#ErrOrderEntry').text(data.msg);
                return false;
            } else if(data.errcode=='1') {
                unsaved = false;
                $("#divSuccessBasicInfoMsg").removeClass('hide');
                $("#divSuccessBasicInfoMsg").text("Saved Successfully");
                //fnRedirectPageTimeOut(base_path+'orderentryvtwo/fourthTbl/'+hashenquiryid);
            }
        }
    }
    function unloadPage() {
        if(unsaved){
            return "You have unsaved changes on this page. Do you want to leave this page and discard your changes or stay on this page?";
        }
    }
    window.onbeforeunload = unloadPage;
</script>
<?php $this->load->view(CNFCOMPANY . 'template/pagefooter'); ?>