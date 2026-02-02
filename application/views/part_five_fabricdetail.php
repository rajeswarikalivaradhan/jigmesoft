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
                                    <button class="btn btn-info pull-right addrights" type="button" onclick="FnYarnRequirementDetailsFinalTbl()">YARN PURCHASE DETAILS</button>
                                </div>
                                <div class="form-group">
                                    <div class="mainheading"><strong>YARN PURCHASE DETAILS</strong></div>
                                    <div id="YarnRequirementDetailsFinalTbl"></div>
                                </div>

                                <div class="form-group">
                                    <button class="btn btn-info pull-right addrights" type="button" onclick="saveYarnReqDetails()">Save YARN PURCHASE DETAILS</button>
                                </div>
                                <div class="form-group">
                                    <button class="btn btn-info pull-right addrights" type="button" onclick="fabricdyeingprogram()">Fabric Dyeing Program</button>
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
    var GlbArrKnitData = [], GlbConsolidatedReqData= [], GlbItemizedyarnWgtCalc = [], GlbArrYarnDyeing = [], GlbArrItemYarnPgm = [], GlbArrYarnPurchaseType = [], GlbArrConsCountWiseYarnReq = [], GlbArrYarnRequirementDetails = [], GlbFabricDyeingPgm = [];

    var enquiryid = '<?php echo @$VarEnquiryId ?>';
    var HashEnquiryId = '<?php echo @$VarHashEnquiryId ?>';
    var GlbUnitsOfMeasureArr = ["%","Nos.","Gms.","Kgs.","%","Inches.","Cms."], GlbYarnBlendPercent = ["100 %","90 %"];
    var YarnContentArr = ["Yarn Content 1","Yarn Content 2","Yarn Content 3"];
    MakePostRequest(base_path+'fabricprogramvtwo/getreqdetails',GlbParam+"&enqid="+enquiryid,'json',getReqDetailsRes);

    function fnPopulateValueArray(ArrName, KeyValue, InsertVal) {
        if (jQuery.inArray(KeyValue, ArrName)) {
            ArrName[KeyValue] = ArrName[KeyValue]+"-"+InsertVal;
        }
        else {
            //ArrName[KeyValue] = '';
        }
        return ArrName;
    }

    function fnGroupArrayValue(ArrSizeVal) {
        if (ArrSizeVal != "" && typeof ArrSizeVal != "undefined") {
            var SumVal = [];
            var ArrName = ArrSizeVal.split("-");
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

    function fnSumSizeArrayValue(ArrSizeVal) {
        if (ArrSizeVal != "") {
            var SumVal = 0;
            var ArrName = ArrSizeVal.split("|-|");
            for (var i = 0; i < ArrName.length; i++) {
                if (isFinite(ArrName[i])) {
                    SumVal = Number(ArrName[i]) + SumVal;
                }
            }
            return SumVal;
        }
        else {
            return 0;
        }
    }

    function getReqDetailsRes(data) {
        console.log(data,'data');
        console.log(data.re,'data re');
        GlbConsolidatedReqData = data.re;
        GlbArrKnitData = data.ArrKnitData;
        GlbItemizedyarnWgtCalc = data.ItemizedyarnWgtCalc;
        GlbArrYarnDyeing = data.ArrYarnDyeing;
        GlbArrItemYarnPgm = data.ArrItemYarnPgm;
        GlbArrYarnPurchaseType = data.ArrYarnPurchaseType;
        GlbArrConsCountWiseYarnReq = data.ArrConsCountWiseYarnReq;
        GlbArrYarnRequirementDetails = data.ArrYarnRequirementDetails;

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

    function FnYarnRequirementDetailsFinalTbl() {
        try {
            var CountWiseYarnReqQty = GlbArrConsCountWiseYarnReq;
            CountWiseYarnReqQty.pop();
            console.log(CountWiseYarnReqQty,'CountWiseYarnReqQty');
            var YarnRequirementDetailsFinalData = [], PlanFabWgtTotal = 0, LycraWgtTotal = 0, ReqYarnWgtTotal = 0;
            for(var i = 0; i < CountWiseYarnReqQty.length; i++) {
                PlanFabWgtTotal += Number(CountWiseYarnReqQty[i][7]);
                LycraWgtTotal += Number(CountWiseYarnReqQty[i][8]);
                ReqYarnWgtTotal += Number(CountWiseYarnReqQty[i][9]);
                YarnRequirementDetailsFinalData.push(["",CountWiseYarnReqQty[i][1],CountWiseYarnReqQty[i][2],CountWiseYarnReqQty[i][3],CountWiseYarnReqQty[i][4],"",
                    CountWiseYarnReqQty[i][5],CountWiseYarnReqQty[i][6],"","",CountWiseYarnReqQty[i][7],CountWiseYarnReqQty[i][8],CountWiseYarnReqQty[i][9]]);
            }
            YarnRequirementDetailsFinalData.push(["","","","","","","","","","Total",PlanFabWgtTotal,LycraWgtTotal,ReqYarnWgtTotal]);
            //console.log(YarnRequirementDetailsFinalData,'YarnRequirementDetailsFinalData');

            if(GlbArrYarnRequirementDetails != "") {
                var savedYarnRequirementDetailsFinalData = GlbArrYarnRequirementDetails;
            }
            if(savedYarnRequirementDetailsFinalData.length > 0) {
                YarnRequirementDetailsFinalData = savedYarnRequirementDetailsFinalData;
            }

            $("#YarnRequirementDetailsFinalTbl").jexcel({
                colHeaders: ["Yarn - Vendor /<br/>Brand Details","Yarn Count","Yarn Blend<br/>(%)","Yarn Content","Yarn Spl.<br/>Request<br/>If Any","Yarn Grade",
                    "Yarn Purchase<br/>Type","Yarn Color","Yarn Product<br/>Code (vendor)","Yarn Color<br/>Code (vendor)","Plan Fab. Wgt.<br/>Consol. (Kgs.)","Lycra Wgt.<br/>(kgs.)",
                    "Req. Yarn Wgt.<br/>(Kgs.)"],
                colWidths: [120,80,80,90,80,90,100,100,100,100,100,100,100],
                columns: [
                    {type: "dropdown", source: ["KPR","Amarjothi","market Source"], wordWrap: true},
                    {type: "text", wordWrap: true},
                    {type: "text", wordWrap: true},
                    {type: "text", wordWrap: true},
                    {type: "text", wordWrap: true},
                    {type: "dropdown", source: ["Combed - Red Label","Combed"], wordWrap: true},
                    {type: "text", wordWrap: true},
                    {type: "text", wordWrap: true},
                    {type: "text", wordWrap: true},
                    {type: "text", wordWrap: true},
                    {type: "text", wordWrap: true},
                    {type: "text", wordWrap: true},
                    {type: "text", wordWrap: true},
                ],
                data: YarnRequirementDetailsFinalData
            });

            MakePostRequest(base_path+'fabricprogramvtwo/saveCountWiseYarnReqQty',GlbParam+"&countwiseyarnreqqty="+JSON.stringify(CountWiseYarnReqQty)+"&enqid="+enquiryid
                ,'json',fnSaveCountWiseYarnReqQtyRes);

            function fnSaveCountWiseYarnReqQtyRes(data) {
                console.log(data,'data');
            }
        }
        catch (e) {
            console.log(e,'catch exception');
        }
    }

    function saveYarnReqDetails() {
        var yarnReqDetails = $("#YarnRequirementDetailsFinalTbl").jexcel('getData');
        MakePostRequest(base_path+'fabricprogramvtwo/saveYarnReqDetails',GlbParam+"&yarnreqdetails="+JSON.stringify(yarnReqDetails)+"&enqid="+enquiryid,'json',saveYarnReqDetailsRes);

    }

    function multiDimensionalUnique(arr) {
        var uniques = [];
        var itemsFound = {};
        for(var i = 0, l = arr.length; i < l; i++) {
            var stringified = JSON.stringify(arr[i]);
            if(itemsFound[stringified]) { continue; }
            uniques.push(arr[i]);
            itemsFound[stringified] = true;

        }
        return uniques;
    }


    function saveYarnReqDetailsRes(data) {
        console.log(data,'data');
    }

    function fabricdyeingprogram() {
        fnRedirectPageTimeOut(base_path +'fabricprogramvtwo/part_six_fabricdetail/' +HashEnquiryId);
    }
</script>
<style>
    .table > tbody > tr > td, .table > tbody > tr > th, .table > tfoot > tr > td, .table > tfoot > tr > th, .table > thead > tr > td, .table > thead > tr > th {padding:5px;}
    .jexcel>thead>tr>td {font-size:12px !important;}
    table {font-size:12px;}

    .jexcel>tbody>tr>td {white-space: normal !important; word-wrap:break-word !important;}
</style>
<?php $this->load->view(CNFCOMPANY.'template/pagefooter'); ?>