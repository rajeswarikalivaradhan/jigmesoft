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
    #ConsolidatedReqData table td.readonly { color:#000; }
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
                                    <div class="mainheading"><strong>ITEMIZED FABRIC REQUIREMENT DETAILS COLOUR WISE & DIA WISE</strong></div>
                                    <div id="ConsolidatedReqData"></div>
                                </div>
                                <div class="form-group">
                                    <button class="btn btn-info pull-right addrights" type="button" onclick="FnItemizedYarnProgram()">Itemized Yarn Program</button>
                                </div>

                                <div class="form-group">
                                    <div class="mainheading"><strong>Itemized Yarn Program</strong></div>
                                    <div id="ItemizedYarnProgram"></div>
                                </div>

                                <div class="form-group">
                                    <button class="btn btn-info pull-right addrights" type="button" onclick="FnCountWiseYarnReq()">Count Wise Yarn Requirement</button>
                                </div>
                            </form>
                        </div><!-- /.box-body -->

                        <div class="box-footer nopadding"></div><!-- /.box-footer -->
                    </div>
                </div>
            </div>
        </section>
    </div><!-- /.content-wrapper -->
    <?php $this->load->view(CNFCOMPANY.'template/templatefooter');?>
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
    var GlbParam = "rfrom=1";
    var GlbArrKnitData = [], GlbConsolidatedReqData= [], GlbItemizedyarnWgtCalc = [], GlbArrYarnDyeing = [], GlbArrItemYarnPgm = [], GlbArrYarnPurchaseType = [];
    var enquiryid = '<?php echo @$VarEnquiryId ?>';
    var HashEnquiryId = '<?php echo @$VarHashEnquiryId ?>';
    var GlbUnitsOfMeasureArr = ["%","Nos.","Gms.","Kgs.","%","Inches.","Cms."], GlbYarnBlendPercent = ["100 %","90 %"];
    var YarnContentArr = ["Yarn Content 1","Yarn Content 2","Yarn Content 3"];
    MakePostRequest(base_path+'fabricprogramvtwo/getreqdetails',GlbParam+"&enqid="+enquiryid,'json',getReqDetailsRes);

    function getReqDetailsRes(data) {
        console.log(data,'data');
        console.log(data.re,'data re');
        GlbConsolidatedReqData = data.re;
        GlbArrKnitData = data.ArrKnitData;
        GlbItemizedyarnWgtCalc = data.ItemizedyarnWgtCalc;
        GlbArrYarnDyeing = data.ArrYarnDyeing;
        GlbArrItemYarnPgm = data.ArrItemYarnPgm;
        GlbArrYarnPurchaseType = data.ArrYarnPurchaseType;
        $("#ConsolidatedReqData").jexcel({
            colHeaders: ["Combo", "Component", "Color", "Garment<br/>Part", "Fabric<br/>Blend<br/>(%)", "Fabric Content", "Fabric Name", "Yarn<br/>Count", "Fin. GSM", "Dyeing Type", "Fin. DIA / DIM (W * H)", "Unit Of<br/>Measure", "Req. Fab. Wgt. (Kgs.)"
                , "Plan. Fab. Wgt. (Kgs.)"],
            colWidths: [100, 100, 100, 100, 60, 100, 100, 60, 80, 90, 70, 70, 100, 100],
            columns: [
                {type: 'text', wordWrap: true, readOnly: true},
                {type: 'text', wordWrap: true, readOnly: true},
                {type: 'text', wordWrap: true, readOnly: true},
                {type: 'text', wordWrap: true, readOnly: true},
                {type: 'text', wordWrap: true, readOnly: true},
                {type: 'text', wordWrap: true, readOnly: true},
                {type: 'text', wordWrap: true, readOnly: true},
                {type: 'text', wordWrap: true, readOnly: true},
                {type: 'text', wordWrap: true, readOnly: true},
                {type: 'text', wordWrap: true, readOnly: true},
                {type: 'text', wordWrap: true, readOnly: true},
                {type: 'dropdown', source: GlbUnitsOfMeasureArr, readOnly: true},
                {type: 'text', wordWrap: true, readOnly: true},
                {type: 'text', wordWrap: true, readOnly: true},
            ],
            data: GlbConsolidatedReqData
        });
    }


    function FnItemizedYarnProgram() {
        try {
            var yarnsplreq = [];
            var ConsolidatedReqData = GlbConsolidatedReqData;
            var PlanFabSubTotals = [], FirstEightGroup = [], ItemizedYarnProgramData = [], YDSubtotalandData = [];
            for (var i = 0; i < ConsolidatedReqData.length; i++) {
                if(ConsolidatedReqData[i][7] != "" && ConsolidatedReqData[i][8] != "" && ConsolidatedReqData[i][9] != "") {
                    FirstEightGroup.push(ConsolidatedReqData[i][0]+"#"+ConsolidatedReqData[i][1]+"#"+ConsolidatedReqData[i][2]+"#"+ConsolidatedReqData[i][3]+"#"+
                        ConsolidatedReqData[i][4]+"#"+ConsolidatedReqData[i][5]+"#"+ConsolidatedReqData[i][6]+"#"+ConsolidatedReqData[i][7]+"#"+ConsolidatedReqData[i][8]+
                        "#"+ConsolidatedReqData[i][9]);
                }
                if(ConsolidatedReqData[i][10] == "") {
                    PlanFabSubTotals.push(ConsolidatedReqData[i][13]);
                }
            }
            var YarnDyeingData = GlbArrYarnDyeing;
            var morethan1CountOrContentSplit = GlbItemizedyarnWgtCalc;
            for(var i = 0; i < YarnDyeingData.length; i++) {
                ItemizedYarnProgramData.push([YarnDyeingData[i][0],YarnDyeingData[i][1],YarnDyeingData[i][2],YarnDyeingData[i][3],YarnDyeingData[i][4],
                    YarnDyeingData[i][5],YarnDyeingData[i][6],YarnDyeingData[i][7],YarnDyeingData[i][8],YarnDyeingData[i][9],"",YarnDyeingData[i][10],"","",""]);
            }
            //console.log(ItemizedYarnProgramData,'ItemizedYarnProgramData yarndyeing');
            for(var j = 0; j < morethan1CountOrContentSplit.length; j++) {
                //var fgsm = morethan1CountOrContentSplit[j][9];
                ItemizedYarnProgramData.push([morethan1CountOrContentSplit[j][0],morethan1CountOrContentSplit[j][1],morethan1CountOrContentSplit[j][2],
                    morethan1CountOrContentSplit[j][3],morethan1CountOrContentSplit[j][4],morethan1CountOrContentSplit[j][5],morethan1CountOrContentSplit[j][6],
                    morethan1CountOrContentSplit[j][8],morethan1CountOrContentSplit[j][9],"","","","","",""]);
            }
            //console.log(ItemizedYarnProgramData,'ItemizedYarnProgramData more than 1 count');
            for(var i = 0; i < FirstEightGroup.length; i++) {
                var SplittedFirstEight = FirstEightGroup[i].split("#");
                //console.log(SplittedFirstEight,'SplittedFirstEight');
                if(SplittedFirstEight[9] === "FD") {
                    ItemizedYarnProgramData.push([SplittedFirstEight[0], SplittedFirstEight[1], SplittedFirstEight[2], SplittedFirstEight[3],
                        SplittedFirstEight[4], SplittedFirstEight[5], SplittedFirstEight[6], SplittedFirstEight[7], SplittedFirstEight[8],SplittedFirstEight[9],"",
                        PlanFabSubTotals[i], "", "", ""]);
                }
            }
            if(GlbArrItemYarnPgm != "") {
                var savedItemizedYarnProgramData = GlbArrItemYarnPgm;
            }
            if(savedItemizedYarnProgramData.length > 0) {
                ItemizedYarnProgramData = savedItemizedYarnProgramData;
            }
            //console.log(ItemizedYarnProgramData,'ItemizedYarnProgramData at last');
            //console.log(yarnsplreq,'yarnsplreq');
            $("#ItemizedYarnProgram").jexcel({
                colHeaders: ["Combo", "Component", "Color", "Garment<br/>Parts", "Fabric<br/>Blend<br/>(%)","Fabric Content","Fabric Name",
                    "Yarn<br/>Count",
                    "Dyeing Type",
                    "Yarn Spl. Req.<br/>If Any",
                    "Yarn <br/>Purchase<br/>Type",
                    "Plan Fab.<br/>Wgt. Subtotal<br/>(Kgs.)",
                    "Lycra<br/>(%)",
                    "Lycra Wgt.<br/>(Kgs.)",
                    "Req. Yarn<br/>Wgt.<br/>(Kgs.)"],
                colWidths: [100, 100, 100, 80, 60, 100, 100, 60, 80, 90, 70, 100, 50, 70, 70],
                columns: [
                    {type: "text", readOnly: true},
                    {type: "text", readOnly: true},
                    {type: "text", readOnly: true},
                    {type: "text", readOnly: true},
                    {type: "text", readOnly: true},
                    {type: "text", readOnly: true},
                    {type: "text", readOnly: true},
                    {type: "text", readOnly: true},
                    {type: "text", readOnly: true},
                    {type: "dropdown",source: yarnsplreq },
                    {type: "dropdown", source: GlbArrYarnPurchaseType},
                    {type: "text", readOnly: true},
                    {type: "text"},
                    {type: "text", readOnly: true},
                    {type: "text", readOnly: true},
                ],
                data: ItemizedYarnProgramData
            });
            var PlanFabWgt = 0, Lycra = 0, LycraWgt = 0, ReqYarnWgt = 0;
            $("#ItemizedYarnProgram").jexcel('updateSettings', {
                table: function (instance, cell, col, row, val, id) {
                    if (col == 0) {
                        PlanFabWgt = 0;
                        Lycra = 0;
                        LycraWgt = 0;
                        ReqYarnWgt = 0;
                    }
                    if (col == 11) PlanFabWgt = Number($(cell).html());
                    if (col == 12) Lycra = Number($(cell).html());
                    if (col == 13) {
                        var LycraPercent = Lycra / 100;
                        LycraWgt = PlanFabWgt * LycraPercent;
                        //console.log(LycraWgt, 'LycraWgt');
                        $(cell).text(LycraWgt.toFixed(3));
                    }
                    if (col == 14) { // Last
                        var ReqYarnWgt = PlanFabWgt - LycraWgt;
                        //console.log(ReqYarnWgt, 'ReqYarnWgt');
                        $(cell).text(ReqYarnWgt.toFixed(3));
                    }
                    var ArrPlanFabWgtSubtotalCol = $("#ItemizedYarnProgram").jexcel('getColumnData',11);
                    var ArrLycraWgtTotal = $("#ItemizedYarnProgram").jexcel('getColumnData',13);
                    var ArrReqYarnWgtSum = $("#ItemizedYarnProgram").jexcel('getColumnData',14);
                    //console.log(ArrPlanFabWgtSubtotalCol,'ArrPlanFabWgtSubtotalCol');
                    var PlanFabWgtSubtotalSum = ArrPlanFabWgtSubtotalCol.reduce(function (a,b) { return Number(a) + Number(b) },0);
                    var LycraWgtSum = ArrLycraWgtTotal.reduce(function (a,b) { return Number(a) + Number(b) },0);
                    var ReqYarnWgtSum = ArrReqYarnWgtSum.reduce(function (a,b) { return Number(a) + Number(b) },0);
                    //console.log(PlanFabWgtSubtotalSum,'PlanFabWgtSubtotalSum');
                    $("#PlanFabWgtSubtotalSum").text(PlanFabWgtSubtotalSum.toFixed(3));
                    $("#LycraWgtSum").text(LycraWgtSum.toFixed(3));
                    $("#ReqYarnWgtSum").text(ReqYarnWgtSum.toFixed(3));
                }
            });
        }
        catch (e) {
            console.log(e,'try catch');
        }
    }

    function FnCountWiseYarnReq() {
        fnRedirectPageTimeOut(base_path +'fabricprogramvtwo/part_four_fabricdetail/' +HashEnquiryId);
    }

</script>
<style>
    .table > tbody > tr > td, .table > tbody > tr > th, .table > tfoot > tr > td, .table > tfoot > tr > th, .table > thead > tr > td, .table > thead > tr > th {padding:5px;}
    .jexcel>thead>tr>td {font-size:12px !important;}
    table {font-size:12px;}

    .jexcel>tbody>tr>td {white-space: normal !important; word-wrap:break-word !important;}
</style>
<?php $this->load->view(CNFCOMPANY.'template/pagefooter'); ?>