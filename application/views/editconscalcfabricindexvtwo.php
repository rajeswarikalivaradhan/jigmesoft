<!--<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jexcel/2.1.0/css/jquery.jexcel.min.css" type="text/css" />-->
<link rel="stylesheet" href="<?php echo base_url();?>assets/css/jexcel/jquery.jexcel.min.css" type="text/css" />
<!--<link rel="stylesheet" href="<?php /*echo base_url('assets/css/jexcel21/jquery.jexcel.css') */?>" type="text/css" />-->
<?php
$this->load->view(CNFCOMPANY.'template/pageheader');
$ArrLoggedUserInfo      = fnGetUserLoggedInfo(1);
$VarUserType            = $ArrLoggedUserInfo['usertype'];
$VarProfilePermission   = $ArrLoggedUserInfo['pp'];
$ArrUserDetails         = fnGetUserLoggedInfo();
?>
<body class="sidebar-mini skin-black wysihtml5-supported sidebar-collapse">
<style>
    td div {
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
    }
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
        <section class="content-header">
            <h1>
                Fabric Programme
                <!--                <small>Programme</small>-->
                <select class="" style="margin-left: 10px; color: crimson; font-size: smaller">
                    <option value="">BULK</option>
                    <option value="">SAMPLING</option>
                </select>

            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">Fabric Programme</li>
            </ol>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-info">
                        <div class="box-body">
                            <form class="form-horizontal" name="frmBasicInfo" id="frmOrderProcess" method="post">
                                <div class="alert alert-success alert-dismissable hide" id="divSuccessBasicInfoMsg"> </div>
                                <div class="alert alert-danger alert-dismissable hide" id="ErrOrderEntry"> </div>
                                <div class="form-group">
                                    <div class="mainheading"><strong>FABRIC DETAILS: KNIT</strong></div>
                                    <div id="FabricDetailsKnit"></div>
                                </div>
                                <div class="form-group">
                                    <!--<div class="mainheading"><strong>P.O. WISE & SIZE WISE ITEMIZED QTY. BREAK-UP </strong></div>-->
                                    <div class="mainheading"><strong>SIZE WISE ITEMIZED P.O. QTY. BREAK-UP - BULK / SAMPLING - DD</strong></div>
                                    <div id="SizeWiseQtyBrkUpFifthTbl"></div>
                                    <div id="SizeWiseQtyBrkUpFifthTbltest"></div>
                                </div>
                                <div class="form-group">
                                    <div class="mainheading"><strong>CUMULATIVE QTY. AS PER SIZE SPEC CODE</strong></div>
                                    <div id="CumulativeSixthATbltest"></div>
                                    <!--<button type="button" id="" class="btn btn-info pull-right addrights" onclick="fnMultipleIntakeQty()">Multiply Intake Qty.</button>-->
                                </div>
                                <div class="form-group">
                                    <div class="mainheading"><strong>ITEMIZED QTY. AS PER CUMULATIVE QTY. & INTAKE QTY.</strong></div>
                                    <div id="itemizedqtycumulativeqty_intakeqtytest"></div>
                                </div>

                                <div class="form-group">
                                    <div class="mainheading"><strong>FABRIC CONSUMPTION CALCULATION</strong></div>
                                    <div id="fabric_consumption_calc"></div>


                                </div>
                            </form>
                        </div><!-- /.box-body -->

                        <div class="box-footer nopadding">
                            <button type="button" id="" onclick="fnSaveConsCalc()" class="btn btn-info pull-right addrights">Next</button>
                        </div><!-- /.box-footer -->
                    </div>
                </div>
            </div>
        </section>
    </div><!-- /.content-wrapper -->
    <?php $this->load->view(CNFCOMPANY.'template/templatefooter'); ?>
    <div class="control-sidebar-bg"></div>
</div><!-- ./wrapper -->
<script src="<?php echo base_url();?>assets/js/ajax.js"></script>
<!--<script src="https://cdnjs.cloudflare.com/ajax/libs/jexcel/2.1.0/js/jquery.jexcel.min.js"></script>-->
<script src="<?php echo base_url();?>assets/js/jexcel/jquery.jexcel.js"></script>
<script src="<?php echo base_url();?>assets/js/jexcel/numeral.min.js"></script>
<script src="<?php echo base_url();?>assets/js/jexcel/excel-formula.min.js"></script>
<!--<script src="<?php /*echo base_url('assets/js/jexcel21/jquery.jexcel.js') */?>"></script>-->
<script src="<?php echo base_url();?>assets/js/commonfunctions.js"></script>
<script>
    //New data
    var GlbParam = "rfrom=1", GlbGarmentParts = [];
    var enquiryid = '<?php echo @$VarEnquiryId ?>';
    var HashEnquiryId = '<?php echo @$VarHashEnquiryId ?>';
    var ArrKnitData = '<?php echo @$ArrKnitData ?>';
    var ArrFifthTbl = '<?php echo @$ArrFifthTbl ?>';
    var ArrSizeChartData = '<?php echo @$ArrSizeChartData ?>';
    var ArrSixTh_atbl = '<?php echo @$ArrSixTh_atbl ?>';
    var VarCheckedCheckBox = '<?php echo @$VarCheckedCheckBox ?>';

    var VarCellId = '<?php echo @$VarCellId ?>';
    var GlbIschecked = 0; var GlbCheckBoxRowId = 0;
    //function fnMultipleIntakeQty() {
    var ColHeaders = '';
    var ArrSizeChartHeader = JSON.parse(ArrSizeChartData);
    //console.log(ArrSizeChartHeader,'ArrSizeChartHeader');
    var ArrColHeaderFinal = ['Combo', 'Component', 'Colour', 'Size Spec<br/>Code/Fit', 'P.O. No. /<br/>Enq. Ref. No.'];
    for(var i=0;i<8;i++) {
        if(typeof (ArrSizeChartHeader[i])!="undefined") {
            ColHeaders = ColHeaders+ArrSizeChartHeader[i]+",";
            ArrColHeaderFinal.push(ArrSizeChartHeader[i]);
        } else {
            ColHeaders = ColHeaders+",";
            ArrColHeaderFinal.push('No Size');
        }
    }
    ArrColHeaderFinal.push('Itemized P.O. Qty.<br/>/ Sample Qty.','Pcs. / Set','Intake Qty.','Itemized Qty.<br/>(Pcs)');

    $("#CumulativeSixthATbltest").jexcel({
        colHeaders: ArrColHeaderFinal,
        colWidths: [110, 110, 100, 90, 100, 45, 45, 45, 45, 45, 45, 45, 45, 120, 70, 70, 100],
        columns: [
            {type: 'text', wordWrap: true, readOnly: true},
            {type: 'text', wordWrap: true, readOnly: true},
            {type: 'text', wordWrap: true, readOnly: true},
            {type: 'text', wordWrap: true, readOnly: true},
            {type: 'text', wordWrap: true, readOnly: true},
            {type: 'numeric', wordWrap: true},
            {type: 'numeric', wordWrap: true},
            {type: 'numeric', wordWrap: true},
            {type: 'numeric', wordWrap: true},
            {type: 'numeric', wordWrap: true},
            {type: 'numeric', wordWrap: true},
            {type: 'numeric', wordWrap: true},
            {type: 'numeric', wordWrap: true},
            {type: 'text', wordWrap: true, readOnly: true},
            {type: 'text', wordWrap: true, readOnly: true},
            {type: 'numeric', wordWrap: true},
            {type: 'text', wordWrap: true, readOnly: true},
        ],
        data: ArrSixTh_atbl
    });
    var dataItemizedCummlativeIntakeqty = $("#CumulativeSixthATbltest").jexcel('getData');
    var reducedItemizedCummlativeIntakeqtyData = [];
    for(var i = 0; i < dataItemizedCummlativeIntakeqty.length; i++) {
        var size1 = dataItemizedCummlativeIntakeqty[i][5] * dataItemizedCummlativeIntakeqty[i][15];
        var size2 = dataItemizedCummlativeIntakeqty[i][6] * dataItemizedCummlativeIntakeqty[i][15];
        var size3 = dataItemizedCummlativeIntakeqty[i][7] * dataItemizedCummlativeIntakeqty[i][15];
        var size4 = dataItemizedCummlativeIntakeqty[i][8] * dataItemizedCummlativeIntakeqty[i][15];
        var size5 = dataItemizedCummlativeIntakeqty[i][9] * dataItemizedCummlativeIntakeqty[i][15];
        var size6 = dataItemizedCummlativeIntakeqty[i][10] * dataItemizedCummlativeIntakeqty[i][15];
        var size7 = dataItemizedCummlativeIntakeqty[i][11] * dataItemizedCummlativeIntakeqty[i][15];
        var size8 = dataItemizedCummlativeIntakeqty[i][12] * dataItemizedCummlativeIntakeqty[i][15];
        reducedItemizedCummlativeIntakeqtyData.push([dataItemizedCummlativeIntakeqty[i][0],dataItemizedCummlativeIntakeqty[i][1],
            dataItemizedCummlativeIntakeqty[i][2],dataItemizedCummlativeIntakeqty[i][3],dataItemizedCummlativeIntakeqty[i][4],
            size1,size2,size3,size4,size5,size6,size7,size8,dataItemizedCummlativeIntakeqty[i][13]]);
    }
    var ColHeaders = '';
    var itemizedqtycumuqty_intakeqtytesthead = ['Combo', 'Component', 'Colour', 'Size Spec<br/>Code/Fit', 'P.O. No. /<br/>Enq. Ref. No.'];
    for(var i=0;i<8;i++) {
        if(typeof (ArrSizeChartHeader[i])!="undefined") {
            ColHeaders = ColHeaders+ArrSizeChartHeader[i]+",";
            itemizedqtycumuqty_intakeqtytesthead.push(ArrSizeChartHeader[i]);
        } else {
            ColHeaders = ColHeaders+",";
            itemizedqtycumuqty_intakeqtytesthead.push('No Size');
        }
    }
    itemizedqtycumuqty_intakeqtytesthead.push('Itemized Qty. (Pcs.)','Tick');
    $("#itemizedqtycumulativeqty_intakeqtytest").jexcel({
        colHeaders: itemizedqtycumuqty_intakeqtytesthead,
        colWidths: [110, 110, 100, 90, 100, 45, 45, 45, 45, 45, 45, 45, 60, 150,60],
        columns: [
            {type: 'text', wordWrap: true, readOnly: true},
            {type: 'text', wordWrap: true, readOnly: true},
            {type: 'text', wordWrap: true, readOnly: true},
            {type: 'text', wordWrap: true, readOnly: true},
            {type: 'text', wordWrap: true, readOnly: true},
            {type: 'numeric', wordWrap: true},
            {type: 'numeric', wordWrap: true},
            {type: 'numeric', wordWrap: true},
            {type: 'numeric', wordWrap: true},
            {type: 'numeric', wordWrap: true},
            {type: 'numeric', wordWrap: true},
            {type: 'numeric', wordWrap: true},
            {type: 'numeric', wordWrap: true},
            {type: 'text', wordWrap: true, readOnly: true},
            {type: 'checkbox'},
        ],
        data: reducedItemizedCummlativeIntakeqtyData
    });
    if(VarCellId >= 0) {
        $("#itemizedqtycumulativeqty_intakeqtytest").jexcel('updateSettings', {
            table: function (instance, cell, col, row, val, id) {
                var cellid = $(cell).prop('id').split('-');
                if(col == 14) {
                    if(row == VarCellId) {
                        $(cell).children().prop('checked',true);
                    }
                }
            }
        });
    }
    $("#FabricDetailsKnit").jexcel({
        colHeaders: ['Combo', 'Component', 'Colour', 'Garment<br/>Parts', 'Fabric Blend (%)', 'Fabric Content', 'Fabric Name','Yarn<br/>Count', 'Finishing<br/>GSM',
            'Dyeing<br/>Type','Yarn Special<br/>Request', 'Fabric Finish'],
        //colHeaders: ['Combo', 'Component', 'Colour', 'Garment<br/>Parts', 'Fabric Blend (%)', 'Fabric Content', 'Fabric Name','Yarn Count', 'Finishing<br/>GSM','Dyeing Type','Yarn Special<br/>Request', 'Fabric Finish', 'Fabric Finish<br/>Stage / Form'],
        colWidths: [105, 105, 100, 120, 123, 123, 123, 80, 100, 60, 100,100],
        allowInsertColumn: false,
        columns: [
            {type: 'text', wordWrap: true},
            {type: 'text', wordWrap: true},
            {type: 'text', wordWrap: true},
            {type: 'text', wordWrap: true},
            {type: 'text', wordWrap: true},
            {type: 'text', wordWrap: true},
            {type: 'text', wordWrap: true},
            {type: 'text', wordWrap: true},
            {type: 'text', wordWrap: true},
            {type: 'text', wordWrap: true},
            {type: 'text', wordWrap: true},
            {type: 'text', wordWrap: true},
        ],
        data: ArrKnitData
    });

    var ColHeaders = '';
    var ArrSizeChartHeader = JSON.parse(ArrSizeChartData);
    var ArrColHeaderFinal = ['Combo', 'Component', 'Colour', 'Size Spec<br/>Code/Fit', 'P.O. No. /<br/>Enq. Ref. No.'];
    for(var i=0;i<8;i++) {
        if(typeof (ArrSizeChartHeader[i])!="undefined") {
            ColHeaders = ColHeaders+ArrSizeChartHeader[i]+",";
            ArrColHeaderFinal.push(ArrSizeChartHeader[i]);
        } else {
            ColHeaders = ColHeaders+",";
            ArrColHeaderFinal.push('No Size');
        }
    }
    ArrColHeaderFinal.push('Itemized P.O. Qty. /<br/>Sample Qty.'); ArrColHeaderFinal.push('Pcs. / Set');
    ArrColHeaderFinal.push('Intake Qty.<br/>(Nos.)');
    ArrColHeaderFinal.push('Itemized Qty.<br/>(Pcs.)');
    $("#SizeWiseQtyBrkUpFifthTbltest").jexcel({
        colHeaders: ArrColHeaderFinal,
        colWidths: [110, 110, 110, 90, 110, 45, 45, 45, 45, 45, 45, 45, 45, 100, 70, 70, 100 ],
        columns: [
            {type: 'text', wordWrap: true, readOnly: true},
            {type: 'text', wordWrap: true, readOnly: true},
            {type: 'text', wordWrap: true, readOnly: true},
            {type: 'text', wordWrap: true, readOnly: true},
            {type: 'text', wordWrap: true, readOnly: true},
            {type: 'numeric', wordWrap: true,readOnly: true},
            {type: 'numeric', wordWrap: true,readOnly: true},
            {type: 'numeric', wordWrap: true,readOnly: true},
            {type: 'numeric', wordWrap: true,readOnly: true},
            {type: 'numeric', wordWrap: true,readOnly: true},
            {type: 'numeric', wordWrap: true,readOnly: true},
            {type: 'numeric', wordWrap: true,readOnly: true},
            {type: 'numeric', wordWrap: true,readOnly: true},
            {type: 'text', wordWrap: true, readOnly: true},
            {type: 'text', wordWrap: true, readOnly: true},
            {type: 'text', wordWrap: true, readOnly: true},
            {type: 'text', wordWrap: true, readOnly: true}
        ],
        data: ArrFifthTbl
    });
    var ColHeaders      = "";
    var ArrSizeChartHeader = JSON.parse(ArrSizeChartData);
    var ArrColHeaderFinal   = ['Combo', 'Component', 'Colour', 'Size Spec<br/>Code/Fit', 'P.O. No. /<br/>Enq. Ref. No.'];
    for(var i=0;i<8;i++) {
        if(typeof (ArrSizeChartHeader[i])!="undefined") {
            ColHeaders = ColHeaders+ArrSizeChartHeader[i]+",";
            ArrColHeaderFinal.push(ArrSizeChartHeader[i]);
        } else {
            ColHeaders = ColHeaders+",";
            ArrColHeaderFinal.push('No Size');
        }
    }
    ArrColHeaderFinal.push('Itemized Qty. /<br/>(Pcs.)');
    //
    var Datafornext = [];
    var getDataCumulativeSixthATbl = $("#CumulativeSixthATbltest").jexcel('getData');
    for(var i = 0; i < getDataCumulativeSixthATbl.length; i++) {
        var s1 = Number(getDataCumulativeSixthATbl[i][5]) * Number(getDataCumulativeSixthATbl[i][15]);
        var s2 = Number(getDataCumulativeSixthATbl[i][6]) * Number(getDataCumulativeSixthATbl[i][15]);
        var s3 = Number(getDataCumulativeSixthATbl[i][7]) * Number(getDataCumulativeSixthATbl[i][15]);
        var s4 = Number(getDataCumulativeSixthATbl[i][8]) * Number(getDataCumulativeSixthATbl[i][15]);
        var s5 = Number(getDataCumulativeSixthATbl[i][9]) * Number(getDataCumulativeSixthATbl[i][15]);
        var s6 = Number(getDataCumulativeSixthATbl[i][10]) * Number(getDataCumulativeSixthATbl[i][15]);
        var s7 = Number(getDataCumulativeSixthATbl[i][11]) * Number(getDataCumulativeSixthATbl[i][15]);
        var s8 = Number(getDataCumulativeSixthATbl[i][12]) * Number(getDataCumulativeSixthATbl[i][15]);
        //var s2 = getDataCumulativeSixthATbl[i][6] * getDataCumulativeSixthATbl[i][15];
        Datafornext.push([getDataCumulativeSixthATbl[i][0],getDataCumulativeSixthATbl[i][1],getDataCumulativeSixthATbl[i][2],getDataCumulativeSixthATbl[i][3]
            ,getDataCumulativeSixthATbl[i][4],s1,s2,s3,s4,s5,s6,s7,s8,getDataCumulativeSixthATbl[i][16]]);
    }
    //console.log(Datafornext,'Datafornext');
    function fnPopulateValueArray(ArrName, KeyValue, InsertVal) {
        if (jQuery.inArray(KeyValue, ArrName)) {
            ArrName[KeyValue] = ArrName[KeyValue]+"|-|"+InsertVal;
        }
        else {
            //ArrName[KeyValue] = '';
        }
        return ArrName;
    }
    function fnGroupArrayValue(ArrSizeVal) {
        if (ArrSizeVal != "" && typeof ArrSizeVal != "undefined") {
            var SumVal = [];
            var ArrName = ArrSizeVal.split("|-|");
            for (var i = 0; i < ArrName.length; i++) {
                if(ArrName[i] != "undefined")
                    SumVal.push(ArrName[i]);
            }
            return SumVal;
        }
        else {
            return 0;
        }
    }
    var planqty = 0; var reqfabricwgt = 0; var planfabricwgt = 0;
    function fnCalculation(instance,colId,sizeid,cell,value) {
        var currtableid = '#'+$(instance).prop('id');
        var zerorow = colId + '-0';
        var firstrow = colId + '-1';
        var secondrow = colId + '-2';
        var thirdrow = colId + '-3';
        var fourthrow = colId + '-4';
        var fifthrow = colId + '-5';
        var sixthrow = colId + '-6';
        if ($(cell).prop('id') == zerorow) {
            if (value != "") {
                var firstMul = Number(sizeid);
                console.log(zerorow,'zerorow');
                console.log(firstMul,'firstMul');
                console.log(Number($(currtableid+" #" + zerorow).text()),'$(currtableid+" #" + zerorow).text()');
                var percent = firstMul + Number($(currtableid+" #" + zerorow).text()) / 100 * firstMul;
                console.log(percent,'percent');
                //planqty = percent + parseInt(sizeid) * $(currtableid+" #"+zerorow).html();
                //console.log(parseInt(sizeid) * $(currtableid+" #"+zerorow).html(),'sizeid * $(currtableid+" #"+zerorow).html()');
                //console.log(planqty,'planqty'); console.log(currtableid+" #" + secondrow,'currtableid+" #" + secondrow in 1');
                $(currtableid+" #" + firstrow).html(percent.toFixed(3));
            }
        }
        if ($(cell).prop('id') == secondrow) {
            if (value != "") {
                reqfabricwgt = 0;
                //console.log(planqty,'planqty');
                planqty = Number($(currtableid+" #"+firstrow).text());
                //console.log(reqfabricwgt,'reqfabricwgt'); console.log($(currtableid+" #" + thirdrow).text(),'$(currtableid+" #" + thirdrow).text()');
                reqfabricwgt = planqty * Number($(currtableid+" #" + secondrow).text());
                //console.log(reqfabricwgt.toFixed(3),'reqfabricwgt.toFixed(3) in 3');
                $(currtableid+" #" + thirdrow).html(reqfabricwgt.toFixed(3));
            }
        }
        if ($(cell).prop('id') == fourthrow) {
            if (value != "") {
                planfabricwgt = 0;
                reqfabricwgt = Number($(currtableid+" #"+ thirdrow).text());
                //console.log(reqfabricwgt,'reqfabricwgt in 5'); console.log($(currtableid+" #" + fifthrow).text(),'$(currtableid+" #" + fifthrow).text()');
                planfabricwgt = reqfabricwgt * parseInt($(currtableid+" #" + fourthrow).text());
                planfabricwgt = planfabricwgt / 100;
                planfabricwgt = planfabricwgt + Number($(currtableid+" #" + thirdrow).text());
                //console.log(planfabricwgt,'planfabricwgt'); console.log($(currtableid+" #" + fourthrow).text(),'$(currtableid+" #" + fourthrow).text() in 5');
                $(currtableid+" #" + fifthrow).html(planfabricwgt.toFixed(3));
            }
        }
        $(currtableid+" #15-1").text(Number($(currtableid+" #7-1").text()) + Number($(currtableid+" #8-1").text()) + Number($(currtableid+" #9-1").text())
            + Number($(currtableid+" #10-1").text()) + Number($(currtableid+ " #11-1").text()) + Number($(currtableid+ " #12-1").text()) +
            Number($(currtableid+" #13-1").text()) + Number($(currtableid+" #14-1").text()));

        var ReqFabWgtTotal = Number($(currtableid+" #7-3").text()) + Number($(currtableid+" #8-3").text()) + Number($(currtableid+" #9-3").text())
            + Number($(currtableid+" #10-3").text()) + Number($(currtableid+" #11-3").text()) + Number($(currtableid+" #12-3").text()) +
            Number($(currtableid+" #13-3").text()) + Number($(currtableid+" #14-3").text());
        $(currtableid+" #15-3").html(ReqFabWgtTotal.toFixed(3));

        var total = Number($(currtableid+" #7-5").text()) + Number($(currtableid+" #8-5").text()) + Number($(currtableid+" #9-5").text())
            + Number($(currtableid+" #10-5").text()) + Number($(currtableid+" #11-5").text()) + Number($(currtableid+" #12-5").text()) +
            Number($(currtableid+" #13-5").text()) + Number($(currtableid+" #14-5").text());
        $(currtableid+" #15-5").html(total.toFixed(3));

    }
    getOrderentry_sixthatbl();
    function getOrderentry_sixthatbl() {
        MakeAsynPostRequest(base_path+'fabricprogramvtwo/orderentry_sixthatbl',GlbParam+"&enqid="+enquiryid,'json',getOrderentry_sixthatblRes);
    }
    var GlbArrSixth_aTbl = [];
    function getOrderentry_sixthatblRes(data) {
        GlbArrSixth_aTbl = data.ArrSixth_aTbl;
        console.log(GlbArrSixth_aTbl,'GlbArrSixth_aTbl');
    }

    ArrColHeaderFinal.push('Tick');
    var itemizedqtycumulativeqty_intakeqtytestdata = $("#itemizedqtycumulativeqty_intakeqtytest").jexcel('getRowData',0);

    var fabricConsumptionCalcColHeaders = '';
    var fabricConsumptionCalcHeader = ['Combo', 'Component', 'Colour', 'Size Spec<br/>Code / Fit', 'P.O. No. /<br/>Enq. Ref. No.'];
    for(var i=0;i<8;i++) {
        if(typeof (ArrSizeChartHeader[i])!="undefined") {
            fabricConsumptionCalcColHeaders = ColHeaders+ArrSizeChartHeader[i]+",";
            fabricConsumptionCalcHeader.push(ArrSizeChartHeader[i]);
        } else {
            fabricConsumptionCalcColHeaders = ColHeaders+",";
            fabricConsumptionCalcHeader.push('No Size');
        }
    }
    fabricConsumptionCalcHeader.push('Itemized Qty.<br/>(Pcs.)');
    //console.log(fabricConsumptionCalcHeader,'fabricConsumptionCalcHeader');
    function fnSaveConsCalc() {
        //var consCalcTblId = $(".consCalcTblCls").attr('id');
        //var ArrconsCalcTblId = [];
        //var checkboxCol = $("#itemizedqtycumulativeqty_intakeqtytest").jexcel('getColumnData',16);
        //console.log(checkboxCol,'checkboxCol');
        var maintblid = GlbCheckBoxRowId;
        $('.consCalcTblCls').each(function (i,v) {
            var consCalcTblData = $("#subchild_"+i).jexcel('getData');
            var FirstRowconsCalcTblData = $("#subchild_"+i).jexcel('getRowData',0);
            MakeAsynPostRequest(base_path+'fabricprogramvtwo/saveconscalc',GlbParam+"&enqid="+enquiryid+"&d="+JSON.stringify(consCalcTblData)+
                "&firstrow="+JSON.stringify(FirstRowconsCalcTblData)+"&maintblid="+VarCheckedCheckBox+"&gparts="+i+"&editmode=1",'json',fnSaveConsCalcRes);

            function fnSaveConsCalcRes(data) {

                var hashcellid = encodeURIComponent(GlbCheckBoxRowId);
                //fnRedirectPageTimeOut(base_path+'fabricprogramvtwo/fabricdetail/'+HashEnquiryId+'/'+hashcellid);

            }
        });

    }
    //

    var id = VarCellId;
    var fabricConsumptionData = $("#itemizedqtycumulativeqty_intakeqtytest").jexcel('getRowData',id);
    fabricConsumptionData.length = 14;
    $("#fabric_consumption_calc").jexcel({
        data: [fabricConsumptionData],
        colHeaders: fabricConsumptionCalcHeader,
        colWidths: [100, 100, 100, 100, 130, 45, 45, 45, 45, 45, 45, 45, 45,100],
        columns: [
            {type: 'text', wordWrap: true, readOnly: true},
            {type: 'text', wordWrap: true, readOnly: true},
            {type: 'text', wordWrap: true, readOnly: true},
            {type: 'text', wordWrap: true, readOnly: true},
            {type: 'text', wordWrap: true, readOnly: true},
            {type: 'numeric', wordWrap: true,readOnly: true},
            {type: 'numeric', wordWrap: true,readOnly: true},
            {type: 'numeric', wordWrap: true,readOnly: true},
            {type: 'numeric', wordWrap: true,readOnly: true},
            {type: 'numeric', wordWrap: true,readOnly: true},
            {type: 'numeric', wordWrap: true,readOnly: true},
            {type: 'numeric', wordWrap: true,readOnly: true},
            {type: 'text', wordWrap: true, readOnly: true},
            {type: 'text', wordWrap: true, readOnly: true}
        ]
    });

    var Knitting = $("#FabricDetailsKnit").jexcel('getData');
    var KnitGarmentParts = [];
    for(var i = 0; i < Knitting.length; i++) {
        var GroupOne = Knitting[i][0]+"#"+Knitting[i][1]+"#"+Knitting[i][2];
        KnitGarmentParts = fnPopulateValueArray(KnitGarmentParts,GroupOne,Knitting[i][3]+"#"+Knitting[i][4]+"#"+Knitting[i][5]+"#"+Knitting[i][6]+"#"+Knitting[i][8]);
    }
    var OSixthATblCumulative = JSON.parse(ArrSixTh_atbl);
    var UnSplittedColor = OSixthATblCumulative[id][2]; var SplitGarmentParts = []; var GarmentParts = [];
    if(UnSplittedColor.indexOf('-') >= 0) {
        var ArrSecondLevelColor = UnSplittedColor.split('-');
        console.log(ArrSecondLevelColor,'ArrSecondLevelColor');
        for(var i = 0; i < ArrSecondLevelColor.length; i++) {
            console.log(id,'id');
            var CurrentGroup = OSixthATblCumulative[id][0]+"#"+OSixthATblCumulative[id][1]+"#"+jsTrim(ArrSecondLevelColor[i]);
            console.log(CurrentGroup,'CurrentGroup');
            SplitGarmentParts.push(fnGroupArrayValue(KnitGarmentParts[CurrentGroup]));
        }
        console.log(SplitGarmentParts,'SplitGarmentParts in if');
    }
    else {
        var CurrentGroup        = OSixthATblCumulative[id][0]+"#"+OSixthATblCumulative[id][1]+"#"+OSixthATblCumulative[id][2];
        GarmentParts        = fnGroupArrayValue(KnitGarmentParts[CurrentGroup]);
        console.log(GarmentParts,'GarmentParts in else');
    }
    if(SplitGarmentParts.length > 0) {
        var rslt = [].concat.apply([], SplitGarmentParts);
        if (rslt) {
            GlbGarmentParts = [];
            for (var r = 0; r < rslt.length; r++) GlbGarmentParts.push(rslt[r]);
        }
    }
    else if(GarmentParts.length > 0) {
        var rslt = [].concat.apply([], GarmentParts);
        if (rslt) {
            GlbGarmentParts = [];
            for (var r = 0; r < rslt.length; r++) GlbGarmentParts.push(rslt[r]);
        }
    }
    var ConscalcColHeaders = ""; var ArrReadOnlyInfo =  new Array();
    var ArrHeader           = ["Garment<br/>Parts","Fabric<br/>Blend<br/>(%)","Fabric<br/>Content","Fabric<br/>Name","Finishing<br/>GSM","Description","Unit Of<br/>Measure"];
    for(var i=0;i<8;i++) {
        if(typeof (ArrSizeChartHeader[i])!="undefined") {
            ConscalcColHeaders = ConscalcColHeaders+ArrSizeChartHeader[i]+",";
            ArrHeader.push(ArrSizeChartHeader[i]);
            ArrReadOnlyInfo[i] = false;
        } else {
            ConscalcColHeaders = ConscalcColHeaders+",";
            ArrHeader.push('No Size');
            ArrReadOnlyInfo[i] = true;
        }
    }
    //var UnitsArrJson = [{"id":"1","name":"Nos."},{"id":"2","name":"%"},{"id":"3","name":"Gms."},{"id":"4","name":"Kgs."},{"id":"5","name":"Inches."},{"id":"6","name":"Cms."}];
    var UnitsArrJson = ["%","Nos.","Gms.","Kgs.","%","Inches.","Cms."];
    ArrHeader.push('Total');
    console.log(GlbGarmentParts,'GlbGarmentParts');
    for(var ij = 0 ; ij < GlbGarmentParts.length; ij++) {
        console.log(GlbGarmentParts[ij],'GlbGarmentParts[]');
        var GpBlendContentName  = GlbGarmentParts[ij].split('#');
        var Garmentpart = GpBlendContentName[0];
        var FabricBlend = GpBlendContentName[1];
        var FabricContent = GpBlendContentName[2];
        var FabricName = GpBlendContentName[3];
        var FinGsm = GpBlendContentName[4];

        var gpartid = Number(ij) + 1;
        var mtb = Number(VarCellId) + 1;
        MakePostRequest(base_path+'fabricprogramvtwo/editconscalcfabricdetail',GlbParam+"&enqid="+enquiryid+"&gpartid="+gpartid+"&maintblid="+mtb,'json',fnRes);
        function fnRes(datacc) {
            console.log(datacc,'datacc');
            $("#fabric_consumption_calc").append('<div id="subchild_'+ij+'" style="margin-top: 20px" class="consCalcTblCls"></div>');
            $("#subchild_" + ij).jexcel({
                data: datacc.ArrCC,
                colHeaders: ArrHeader,
                colWidths: [80, 50, 70, 70, 65, 170, 70, 70, 70, 70, 70, 70, 70, 70, 70, 90],
                columns: [
                    {type: 'text', wordWrap: true, readOnly: true},
                    {type: 'text', wordWrap: true, readOnly: true},
                    {type: 'text', wordWrap: true, readOnly: true},
                    {type: 'text', wordWrap: true, readOnly: true},
                    {type: 'text', wordWrap: true, readOnly: true},
                    {type: 'text', wordWrap: true, readOnly: true},
                    {type: 'dropdown', source: UnitsArrJson, wordWrap: true},
                    {type: 'text', wordWrap: true, readOnly: ArrReadOnlyInfo[0]},
                    {type: 'text', wordWrap: true, readOnly: ArrReadOnlyInfo[1]},
                    {type: 'text', wordWrap: true, readOnly: ArrReadOnlyInfo[2]},
                    {type: 'text', wordWrap: true, readOnly: ArrReadOnlyInfo[3]},
                    {type: 'text', wordWrap: true, readOnly: ArrReadOnlyInfo[4]},
                    {type: 'text', wordWrap: true, readOnly: ArrReadOnlyInfo[5]},
                    {type: 'text', wordWrap: true, readOnly: ArrReadOnlyInfo[6]},
                    {type: 'text', wordWrap: true, readOnly: ArrReadOnlyInfo[7]},
                    {type: 'text', wordWrap: true}
                ],
                onchange: function (instance, cell, value) {
                    var ccArrRowId = $(cell).prop('id').split('-');
                    var id_cc = ccArrRowId[0];
                    var start = Number(id_cc) - 2;
                    for (var ii = 0; ii < ArrSizeChartHeader.length; ii++) {
                        fnCalculation(instance, id_cc, OSixthATblCumulative[id][start], cell, value);
                    }
                }
            });
        }
    }

</script>
<style>
    .table > tbody > tr > td, .table > tbody > tr > th, .table > tfoot > tr > td, .table > tfoot > tr > th, .table > thead > tr > td, .table > thead > tr > th {padding:5px;}
    .jexcel>thead>tr>td {font-size:12px !important;}
    table {font-size:12px;}

    .jexcel>tbody>tr>td {white-space: normal !important; word-wrap:break-word !important;}
</style>
<?php $this->load->view(CNFCOMPANY.'template/pagefooter'); ?>