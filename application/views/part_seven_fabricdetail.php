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
                                    <button class="btn btn-info pull-right addrights" type="button" onclick="yarndyeingprogram()">Yarn Dyeing Program</button>
                                </div>

                                <div class="form-group">
                                    <div id="yarndyeingprogramGroupTbl"></div>
                                </div>

                                <div class="form-group">
                                    <div class="mainheading"><strong>Yarn DYEING PROGRAMME - COLOUR WISE</strong></div>
                                    <div id="yarndyeingprogramMainTbl"></div>
                                </div>

                                <div class="form-group">
                                    <button class="btn btn-info pull-right addrights" type="button" onclick="fnGotoEight()">Next</button>
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

    function multiDimensionalUnique(arr) {
        var uniques = [];
        var itemsFound = {};
        //console.log(arr,'arr');
        for(var i = 0, l = arr.length; i < l; i++) {
            var stringified = JSON.stringify(arr[i]);
            //console.log(stringified,'stringified');
            //console.log(itemsFound[stringified],'if itemsFound stringified conti ');
            if(itemsFound[stringified]) { continue; }
            uniques.push(arr[i]);
            itemsFound[stringified] = true;
            //console.log(itemsFound[stringified],'itemsFound stringified assign true');
        }
        return uniques;
    }

    function yarndyeingprogram() {
        var fabricDyeingProgramGroupfinal = [], fabricDyeingProgram = [];
        //console.log(GlbArrKnitData,'GlbArrKnitData');
        for(var b = 0; b < GlbArrYarnDyeing.length; b++) {
            //console.log(Knitting[b][9],'Knitting');
            //console.log(colorscolonsplitarr[c],'colorscolonsplitarr');
            var aa = [GlbArrYarnDyeing[b][0],GlbArrYarnDyeing[b][1],GlbArrYarnDyeing[b][2]];
            //console.log(group,'group');
            fabricDyeingProgramGroupfinal.push(aa);
        }

        console.log(fabricDyeingProgramGroupfinal,'fabricDyeingProgramGroupfinal');
        //var fabricDyeingProgramGroupData = multiDimensionalUnique(fabricDyeingProgramGroupfinal);
console.log(GlbArrYarnDyeing,'GlbArrYarnDyeing');
        for(var h = 0; h < GlbArrYarnDyeing.length; h++) {
            GlbFabricDyeingPgm = fnPopulateValueArray(GlbFabricDyeingPgm, GlbArrYarnDyeing[h][0] + "#" + GlbArrYarnDyeing[h][1] + "#" +GlbArrYarnDyeing[h][2],
                [GlbArrYarnDyeing[h][3], GlbArrYarnDyeing[h][7], GlbArrYarnDyeing[h][4],GlbArrYarnDyeing[h][5],GlbArrYarnDyeing[h][6], "", "", "", GlbArrYarnDyeing[h][13]]);
        }

        console.log(GlbFabricDyeingPgm,'GlbFabricDyeingPgm');

        $("#yarndyeingprogramGroupTbl").jexcel({
            colHeaders:["Combo","Component","Colour","Checkbox"],
            colWidths:[100,100,100,100],
            columns:[
                {type: "text", wordWrap: true},
                {type: "text", wordWrap: true},
                {type: "text", wordWrap: true},
                {type: "checkbox", wordWrap: true},
            ],
            data : fabricDyeingProgramGroupfinal,
            onchange: function (instance,cell,val) {
                var cellName = $(instance).jexcel('getColumnNameFromId', $(cell).prop('id'));
                if(cellName.indexOf('D') == 0) {
                    if(val === true) {
                        var ArrRowId = $(cell).prop('id').split('-');
                        var CellId = ArrRowId[1];
                        var ki = fabricDyeingProgramGroupfinal[CellId][0]+"#"+fabricDyeingProgramGroupfinal[CellId][1]+"#"+jsTrim(fabricDyeingProgramGroupfinal[CellId][2]);
                        var TableSno = 'Table No: '; TableSno += Number(CellId) + 1;

                        $("#yarndyeingprogramMainTbl").append('<br/><div class="mainheading box box-primary"><h4>'+TableSno+'</h4> </div><div id="yarndyeingprogramTbl_' + CellId+'" style="margin-top: 20px" class="SubTbl"></div>');
                        $("#yarndyeingprogramTbl_"+CellId).jexcel({
                            colHeaders: ["Combo","Component","Colour","Dyeing<br/>Type","Pantone No.<br/>/Swatch Ref.<br/>Details","Approved<br/>Lab Dip<br/>No.",
                                "Dyeing Job<br/>Worker's Name","Dyeing Special Request If Any","Other Special<br/>Req. If Any.","Colour Mat.<br/>Std."],
                            colWidths: [100,100,100,100,100,100,100,100,100,100],
                            columns: [
                                {type: "text", wordWrap: true},
                                {type: "text", wordWrap: true},
                                {type: "text", wordWrap: true},
                                {type: "text", wordWrap: true},
                                {type: "text", wordWrap: true},
                                {type: "text", wordWrap: true},
                                {type: "text", wordWrap: true},
                                {type: "text", wordWrap: true},
                                {type: "text", wordWrap: true},
                                {type: "text", wordWrap: true},
                            ],
                            data : [
                                [fabricDyeingProgramGroupfinal[CellId][0],fabricDyeingProgramGroupfinal[CellId][1],fabricDyeingProgramGroupfinal[CellId][2],
                                    "YD","","","","","",""]
                            ]
                        });

                        $("#yarndyeingprogramMainTbl").append('<div id="fabdyeingsubchild_' + CellId+'" style="margin-top: 20px" class="SubTbl"></div>');
                        var tt = GlbFabricDyeingPgm[ki];
                        var datas = tt.substr(tt.indexOf('-')+1);
                        //console.log(datas,'datas');
                        var arrdatas = datas.split("-");

                        //console.log(arrdatas,'arrdatas');

                        $("#fabdyeingsubchild_"+CellId).jexcel({
                            colHeaders: [
                                "Garment<br/>Parts",
                                "Yarn<br/>Count",
                                "Yarn<br>Blend<br/>(%)",
                                "Yarn<br/>Content",
                                "Yarn Special<br/>Category<br/>If Any<br/>",
                                "Yarn Grade",
                                "Blended Yarn<br/>Col. Match<br/>Cont.",
                                "Winding<br/>Req. Cone<br/>Wgt.",
                                "Req. Yarn<br/>Wgt.<br/>(Kgs.)",
                            ],
                            colWidths: [100,100,100,100,100,100,100,100,100,100,100],
                            columns: [
                                {type: "text", wordWrap: true},
                                {type: "text", wordWrap: true},
                                {type: "text", wordWrap: true},
                                {type: "text", wordWrap: true},
                                {type: "text", wordWrap: true},
                                {type: "text", wordWrap: true},
                                {type: "text", wordWrap: true},
                                {type: "text", wordWrap: true},
                                {type: "text", wordWrap: true},
                                {type: "text", wordWrap: true},
                                {type: "text", wordWrap: true}
                            ]
                        });

                        for(var g = 0; g < arrdatas.length; g++) {
                            var finalsplit = arrdatas[g].split(',');
                            $("#fabdyeingsubchild_"+CellId).jexcel('insertRow',finalsplit,g);
                        }
                        var tbllen = $("#fabdyeingsubchild_"+CellId).jexcel('getColumnData');
                        var delrow = tbllen.length - 1;
                        $("#fabdyeingsubchild_"+CellId).jexcel('deleteRow',delrow);
                    }
                }
            }
        });
    }

    function fnGotoEight() {
        fnRedirectPageTimeOut(base_path+'fabricprogramvtwo/part_eight_fabricdetail');
    }

</script>
<style>
    .table > tbody > tr > td, .table > tbody > tr > th, .table > tfoot > tr > td, .table > tfoot > tr > th, .table > thead > tr > td, .table > thead > tr > th {padding:5px;}
    .jexcel>thead>tr>td {font-size:12px !important;}
    table {font-size:12px;}

    .jexcel>tbody>tr>td {white-space: normal !important; word-wrap:break-word !important;}
</style>
<?php $this->load->view(CNFCOMPANY.'template/pagefooter'); ?>