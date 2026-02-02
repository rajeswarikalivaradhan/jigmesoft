<?php
$this->load->view(CNFCOMPANY . 'template/pageheader');
?>
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/jexcel4/jsuites.css"/>
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/jexcel4/jexcel.css"/>
<body class="sidebar-mini skin-black wysihtml5-supported sidebar-collapse">
<div class="wrapper">
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
            <div class="dropdown">

            </div>
            <div class="row">
                <div class="col-md-12">
                    <div id="DivContBasicInfo">
                        <div class="box box-info">
                            <div class="box-body" style="padding: 0px;">
                                <form class="form-horizontal" name="frmBasicInfo" id="frmOrderProcess" method="post">
                                    <div class="col-md-12 pd0  no-padding">
                                        <?php $this->load->view(CNFCOMPANY."orderentry/orderentrycommondetails"); ?>
                                                <div style="background-color: rgb(210 253 249); padding-top: 10px; padding-left: 10px">
                                                    <strong>SIZE WISE CUTTING RATIO</strong>
                                                </div>
                                    </div>
                                    <div class="col-sm-12 table-responsive" style="padding: 5px !important;">

                                            <div id="cuttingRatioFifthJxl"></div>

                                    </div>
                                    <div class="col-md-12" style="padding: 5px">
                                            <label for="tblRemarks">Remarks</label>
                                            <textarea id="frmBasicRemarks" name="tblRemarks" title="Remarks" class="form-control"></textarea>
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
    var enquiryid = '<?php echo $VarEnquiryId ?>';
    var GlbParam = 'rfrom=1';
    ArrSizes = [];
    unsaved = false; var GlbCuttingRatioData = '';
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
    MakePostRequest(base_path+'orderentryvtwo/cuttingRatioTbl',GlbParam+"&enqid="+enquiryid,'json',getDataRes);
    function getDataRes(data) {
        console.log(data,'data');
        if(data.remarks !== "") {
            document.getElementById("frmBasicRemarks").innerText = data.remarks;
        }
        ArrSizes = data.ArrSizeChartData;
        if(data.cuttingRatioData != "") {
            GlbCuttingRatioData = JSON.parse(data.cuttingRatioData);
        }
        else if(data.ArrFromNewFourthTbl != "") {
            GlbCuttingRatioData = data.ArrFromNewFourthTbl;
        }
        else {
            GlbCuttingRatioData = [[]];
        }
        console.log(GlbCuttingRatioData,'GlbCuttingRatioData');
    }

    columnsForJxl = [
        {title:'Combo',width:150, readOnly: true},
        {title:'Component',width:150, readOnly: true},
        {title:'Colour',width:150, readOnly: true},
        {title:'P.O. No. / Enq. Ref. No.',width:100, readOnly: true},
        {title:'Size Spec Code/Fit',width:135, readOnly: true}];
    jxlSizeColumnCount = 5;
    for (let ii = 0; ii < ArrSizes.length; ii++) {
        columnsForJxl.push({title:ArrSizes[ii], type:'numeric', width:55, readOnly: true});
        jxlSizeColumnCount++;
    }
    console.log(jxlSizeColumnCount,'jxlSizeColumnCount');
    qtyPerRatio = jxlSizeColumnCount + 1;
    console.log(qtyPerRatio,'qtyPerRatio');
    //new

    //After Size pushed to columns
    columnsForJxl.push( {title: 'Qty. Per Ratio (Pcs.)', width: 100, type: 'numeric', readOnly: true} );
    columnsForJxl.push( {title: 'Itemized Qty. (Pcs.)', width: 100, readOnly: true} );

    jxlTableFooter = [];
    for(let ii = 0; ii < jxlSizeColumnCount; ii++) {
        jxlTableFooter.push('');
    }
    jxlTableFooter.push('Total','=SUMCOL(TABLE(), COLUMN())','Pie');

    jexcel(document.getElementById('cuttingRatioFifthJxl'), {
        columns: columnsForJxl,
        footers: [jxlTableFooter],
        allowInsertRow:false,
        allowInsertColumn:false,
        onchange: function() {
            unsaved = true;
        },
        data: GlbCuttingRatioData,
        updateTable:function(instance, cell, col, row, val) {
            if (col === 0) {
                colsVal = 0;
            }
            if(col >= 5 && col < jxlSizeColumnCount) {
                if(val >= 0)
                    colsVal = colsVal + val;
            }
            if(col === jxlSizeColumnCount) {
                $(cell).text(colsVal);
                instance.jexcel.options.data[row][col] = colsVal;
            }
        }
    });

    function fnSaveChanges() {
        let data = $("#cuttingRatioFifthJxl").jexcel('getData');
        let tblRemarks = $("#frmBasicRemarks").val();
        MakeAsynPostRequest(base_path+'orderentryvtwo/saveCuttingRatioFifthTbl',GlbParam+"&d="+JSON.stringify(data)+
            "&enqid="+enquiryid+"&e="+encodeURIComponent(tblRemarks),'json',fnSaveTableRes);
    }

    function fnSaveTableRes(data) {
        console.log(data);
        if(data!='') {
            if(data.errcode==-1) {
                $("#ErrOrderEntry").removeClass('hide');
                $('#ErrOrderEntry').text('Error');
                return false;
            } else if(data.errcode==1) {
                unsaved = false;
                $("#divSuccessBasicInfoMsg").removeClass('hide');
                $('#divSuccessBasicInfoMsg').text("Saved Successfully");
                //fnRedirectPageTimeOut(base_path+'orderentryvtwo/beforeSeventhtbl/'+hashenquiryid);
                //fnRedirectPageTimeOut(base_path+'orderentryvtwo/seventhtbl/'+hashenquiryid);
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