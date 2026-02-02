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

    <div class="content-wrapper">

        <div class="col-md-12">
            <section class="content-header">
                <h1 class="firstHeading">BOM Program</h1>
            </section>
        </div>
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div id="DivContBasicInfo">
                        <div class="box box-info">
                            <div class="box-body" style="padding: 0px;">
                                <form class="form-horizontal" name="frmBasicInfo" id="frmOrderProcess" method="post">
                                    <div class="col-md-12 pd0 no-padding">
                                        <?php $this->load->view(CNFCOMPANY."bill_of_materials/common_details"); ?>

                                    </div>

                                    <div class="col-sm-12" style="padding: 5px !important">
                                        <strong>BOM CONSOLIDATED: Article - 2</strong>
                                    <!--<div id="bomconslidatedtwelfthtbl"></div>-->
                                        <div id="bom2_consolidated"></div>
                                    </div>

                                    <div class="col-md-12" style="padding: 5px !important;">
                                            <label class="">Remarks</label>
                                            <textarea id="consolidatedRemarks" title="Remarks" class="form-control"></textarea>

                                    </div>
                                </form>
                                <div class="col-md-12" style="padding: 5px !important;">
                                    <?php $this->load->view(CNFCOMPANY . "bill_of_materials/bomFooterNavSave"); ?>
                                </div>

                                <div class="col-md-12">
                                    <div class="alert alert-success alert-dismissable hide" id="divSuccessMsg"> </div>
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
    var GlbParam = 'rFrom=1', unsaved = false;
     var GlbBomConsolidated = '';
    MakeAsynPostRequest(base_path+'billofmaterials/consolidated_2',GlbParam+"&enqId="+enquiryid,'json',function (data) {
        console.log(data, 'data');
        if(data.remarks != "") {
            document.getElementById("consolidatedRemarks").innerText = data.remarks;
        }
        GlbBomConsolidated = data.jsonBomConsolidated;
        var GlbBomArticle = data.jsonFromBomArticle;
        if(GlbBomArticle == '') {
            GlbBomConsolidated = [[]];
        }
        else {
            GlbBomArticle = JSON.parse(GlbBomArticle);
            var GlbBomGroup = [], BomConsolidatedGroup = [];
            for (var i = 0; i < GlbBomArticle.length; i++) {
                if (GlbBomArticle[i][5] != '' && GlbBomArticle[i][6] != '' && GlbBomArticle[i][7] != '' && GlbBomArticle[i][8] != ''
                    && GlbBomArticle[i][9] != '' && GlbBomArticle[i][10] != '') {
                    var BomGroup = GlbBomArticle[i][5] + "#" + GlbBomArticle[i][6] + "#" + GlbBomArticle[i][7] + "#" +
                        GlbBomArticle[i][8] + "#" + GlbBomArticle[i][9]+"#"+GlbBomArticle[i][10]+"#"+GlbBomArticle[i][14];
                    var BomGroupId = jQuery.inArray(BomGroup, GlbBomGroup);
                    if (BomGroupId === -1) {
                        GlbBomGroup.push(BomGroup);
                    }
                    else {

                    }
                    BomConsolidatedGroup = fnPopulateValueArray(BomConsolidatedGroup, BomGroup, GlbBomArticle[i][13]);
                }
            }
            console.log(BomConsolidatedGroup,'BomConsolidatedGroup');
            if(GlbBomConsolidated == '') {
                GlbBomConsolidated = [];
                for (var i = 0; i < GlbBomGroup.length; i++) {
                    var KeyVal = GlbBomGroup[i];
                    console.log(KeyVal,'KeyVal');
                    console.log(BomConsolidatedGroup[KeyVal],'sd');
                    var sum = fnSumSizeArrayValue(BomConsolidatedGroup[KeyVal]);
                    var ConsolidatedData = KeyVal.split('#');
                    GlbBomConsolidated.push([ConsolidatedData[0], ConsolidatedData[1], ConsolidatedData[2], ConsolidatedData[3],ConsolidatedData[4],
                        ConsolidatedData[5],sum,"","","",ConsolidatedData[6]]);
                }
            }
            else {
                GlbBomConsolidated = JSON.parse(GlbBomConsolidated);
            }

        }
        $("#bom2_consolidated").jexcel({
            data: GlbBomConsolidated,
            allowInsertColumn: false,
            allowManualInsertRow: false,
            colHeaders: ['Item Description / Blend (%) / Content / Material', 'Garment Size','Item Code', 'Item Color Code', 'Size / Dim.<br/>(L*W*H)',
                'Unit of Measure', 'Consld. Reqd BOM Qty.','Ex. Qty.<br/>(%)', 'Ex. Qty.', 'Plan.<br/>BOM Qty.','Unit of Measure'],
            colWidths: [377, 70,140, 140, 120, 70, 105, 80, 80, 105, 70],
            columns: [
                {type: 'text', readOnly: true},
                {type: 'text', readOnly: true},
                {type: 'text', readOnly: true},
                {type: 'text', readOnly: true},
                {type: 'text', readOnly: true},
                {type: 'text', readOnly: true},
                {type: 'text', wordWrap: true, readOnly: true},
                {type: 'text'},
                {type: 'text', readOnly: true},
                {type: 'text', readOnly: true},
                {type: 'text', readOnly: true}
            ],
            onchange:function () {
                unsaved = true;
            },
            updateTable:function(instance, cell, col, row, val, label, cellName) {
                if(col==6) {
                    cell.style.textAlign = "right";
                    ConsBOMReqQty = parseFloat(val);
                }
                if(col==7) {
                    ExQty = parseFloat(val);
                    console.log(ExQty,'ExQty');
                }
                if(col==8) {
                    cell.style.textAlign = "right";
                    ExQtyNos=0;
                    console.log(ExQty,'ExQty');
                    if(ExQty>0) {
                        var ExQtyPercent = ExQty / 100;
                        ExQtyNos = ConsBOMReqQty * ExQtyPercent;
                        console.log(ExQtyNos,'ExQtyNos');
                        instance.jexcel.options.data[row][col] = parseInt(ExQtyNos);
                        $(cell).text(parseInt(ExQtyNos));
                    }
                }
                if(col==9) {
                    cell.style.textAlign = "right";
                    if(ExQtyNos>0) {
                        instance.jexcel.options.data[row][col] = parseInt(ExQtyNos)+ConsBOMReqQty;
                        $(cell).text(parseInt(ExQtyNos)+ConsBOMReqQty);
                    }
                }
            }
        });
    });

    function fnPopulateValueArray(ArrName, KeyValue, InsertVal) {
        if (jQuery.inArray(KeyValue, ArrName)) {
            ArrName[KeyValue] = InsertVal + "-" + ArrName[KeyValue];
        }
        return ArrName;
    }
    function fnSumSizeArrayValue(ArrSizeVal) {
        if (ArrSizeVal) {
            let SumVal = 0;
            console.log(ArrSizeVal,'ArrSizeVal');
            let ArrName = ArrSizeVal.split("-");
            for (let i = 0; i < ArrName.length; i++) {
                if(ArrName[i] !== "undefined") {
                    SumVal = Number(ArrName[i]) + SumVal;
                }

            }
            return SumVal;
        }
        else {
            return 0;
        }
    }
    function fnSaveTable() {
        let data = $("#bom2_consolidated").jexcel('getData');
        let remarks = $("#consolidatedRemarks").val();
        MakeAsynPostRequest(base_path+'billofmaterials/saveConsolidated',GlbParam+"&d="+JSON.stringify(data)+
            "&enqId="+enquiryid+"&aid=2&e="+encodeURIComponent(remarks),'json',fnSaveTableRes);
    }

    function fnSaveTableRes(data) {
        if(data!='') {
            if(data.errCode==1) {
                unsaved = false;
                $("#divSuccessMsg").removeClass('hide');
                $("#divSuccessMsg").text("Saved Successfully");
                //fnRedirectPageTimeOut(base_path+'orderentryvtwo/bomsssapprovalthirteenthart1/'+hashenquiryid);
                //fnRedirectPageTimeOut(base_path+'orderentryvtwo/bom_sampling13Approval_article1/'+hashenquiryid);
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