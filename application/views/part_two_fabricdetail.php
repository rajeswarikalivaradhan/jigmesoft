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
                                    <button class="btn btn-info pull-right addrights" type="button" onclick="FnYarnDyeColorSplit_SDBSplit()">Fabric With more than 1 Yarn Content or Count & Yarn Dyeing</button>
                                </div>
                                <div class="form-group">
                                    <div class="mainheading"><strong>FABRIC WITH MORE THAN ONE YARN COUNT OR CONTENT - ITEMIZED COUNT WISE & CONTENT WISE QTY. BREAK-UP DETAILS</strong></div>
                                    <div id="morethan1CountOrContentSplit"></div>
                                </div>


                                <div class="form-group">
                                    <div class="mainheading"><strong>YARN DYEING - ITEMIZED COLOUR WISE QTY. BREAK-UP DETAILS</strong></div>
                                    <div id="YarnDyeColorSplit"></div>
                                </div>

                                <div class="form-group">
                                    <button class="btn btn-info pull-right addrights" type="button" onclick="FnItemizedYarnProgram()">Itemized Yarn Program</button>
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
    var GlbArrKnitData = [], GlbItemizedyarnWgtCalc = [], GlbArrYarnDyeing = [], YarnDyeingItemizedColorWise = [], SavedYarnDyeingItemizedColorWise = [], savedMorethan1CountOrContentSplitData = [];
    var enquiryid = '<?php echo @$VarEnquiryId ?>';
    var HashEnquiryId = '<?php echo @$VarHashEnquiryId ?>';
    var GlbUnitsOfMeasureArr = ["%","Nos.","Gms.","Kgs.","%","Inches.","Cms."], GlbYarnBlendPercent = ["100 %","90 %"];
    var YarnContentArr = ["Yarn Content 1","Yarn Content 2","Yarn Content 3"];
    MakePostRequest(base_path+'fabricprogramvtwo/getreqdetails',GlbParam+"&enqid="+enquiryid,'json',getReqDetailsRes);

    function getReqDetailsRes(data) {
        console.log(data,'data');
        console.log(data.re,'data re');
        GlbArrKnitData = data.ArrKnitData;
        GlbItemizedyarnWgtCalc = data.ItemizedyarnWgtCalc;
        GlbArrYarnDyeing = data.ArrYarnDyeing;
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
            data: data.re
        });
    }

    function FnYarnDyeColorSplit_SDBSplit() {
        var Knitting = GlbArrKnitData; var FinishingGsmGroup = [];
        console.log(Knitting,'Knitting');
        for(var i = 0; i < Knitting.length; i++) {
            var GroupOne = Knitting[i][0]+"#"+Knitting[i][1]+"#"+Knitting[i][2]+"#"+Knitting[i][3]+"#"+Knitting[i][4]+"#"+Knitting[i][5]+"#"+Knitting[i][6];
            FinishingGsmGroup[GroupOne] = Knitting[i][7];
        }
        var ConsolidatedReqData = $("#ConsolidatedReqData").jexcel('getData');
        var YarnDyeRowId = 0, SDBRowId = 0, morethan1CountOrContentSplitData = [];
        for(var i = 0; i < ConsolidatedReqData.length; i++) {
            if (ConsolidatedReqData[i][9] === "YD") {
                YarnDyeRowId = i;
            }
            if (ConsolidatedReqData[i][9] === "SDB") {
                SDBRowId = i;
            }
            if(SDBRowId < ConsolidatedReqData.length) {
                SDBRowId = ConsolidatedReqData.length - 1;
            }
            else {
                SDBRowId++;
            }
            if(ConsolidatedReqData[SDBRowId][0] == "") {
                var PlanfabSubTotalSDB = ConsolidatedReqData[SDBRowId][13];
            }
            if(YarnDyeRowId < ConsolidatedReqData.length) {
                YarnDyeRowId = ConsolidatedReqData.length - 1;
            }
            else {
                YarnDyeRowId++;
            }
            if(ConsolidatedReqData[YarnDyeRowId][0] == "") {
                var PlanfabSubTotalYD = ConsolidatedReqData[YarnDyeRowId][13];
            }
            if (ConsolidatedReqData[i][7].indexOf('/') >= 0) {
                var yarncountSplitArr = ConsolidatedReqData[i][7].split('/');
                //console.log(yarncountSplitArr,'yarncountSplitArr');
                for (var y = 0; y < yarncountSplitArr.length; y++) {
                    console.log(yarncountSplitArr[y],'yarncountSplitArr y');
                    var ConsoliData = ConsolidatedReqData[i];
                    //console.log(ConsoliData, 'ConsoliData');
                    if (ConsoliData[2].indexOf(':') >= 0) {
                        var ArrYarnDyeColor = ConsoliData[2].split(':');
                        //console.log(FinishingGsmGroup,'FinishingGsmGroup');
                        var FinGsm = FinishingGsmGroup[ConsolidatedReqData[i][0] + "#" + ConsolidatedReqData[i][1] + "#" + ConsoliData[2] + "#" + ConsolidatedReqData[i][3] +
                        "#" + ConsolidatedReqData[i][4] + "#" + ConsolidatedReqData[i][5] + "#" + ConsolidatedReqData[i][6]];
                        console.log(ConsolidatedReqData,'ConsolidatedReqData');
                        for (var c = 0; c < ArrYarnDyeColor.length; c++) {
                            morethan1CountOrContentSplitData.push([ConsolidatedReqData[i][0], ConsolidatedReqData[i][1], ArrYarnDyeColor[c], ConsolidatedReqData[i][3],
                                ConsolidatedReqData[i][4], ConsolidatedReqData[i][5], ConsolidatedReqData[i][6],jsTrim(yarncountSplitArr[y]),ConsolidatedReqData[i][9], "","",
                                "",PlanfabSubTotalYD,""]);
                        }
                    }
                    else {
                        var FinGsm = FinishingGsmGroup[ConsolidatedReqData[i][0] + "#" + ConsolidatedReqData[i][1] + "#" + ConsoliData[2] + "#" + ConsolidatedReqData[i][3] +
                        "#" + ConsolidatedReqData[i][4]];
                        console.log(FinGsm,'FinGsm');
                        morethan1CountOrContentSplitData.push([ConsolidatedReqData[i][0], ConsolidatedReqData[i][1], ConsoliData[2], ConsolidatedReqData[i][3],
                            ConsolidatedReqData[i][4], ConsolidatedReqData[i][5], ConsolidatedReqData[i][6],jsTrim(yarncountSplitArr[y]),ConsolidatedReqData[i][9], "","",
                            "",PlanfabSubTotalYD,""]);
                    }

                }
            }
            //else {
            if(ConsolidatedReqData[i][9] == "YD") {
                if (ConsolidatedReqData[i][2].indexOf(':') >= 0) {
                    var ArrYarnDyeColor = ConsolidatedReqData[i][2].split(':');
                    //console.log(FinishingGsmGroup,'FinishingGsmGroup');
                    var FinGsm = FinishingGsmGroup[ConsolidatedReqData[i][0] + "#" + ConsolidatedReqData[i][1] + "#" + ConsolidatedReqData[i][2] + "#" +
                    ConsolidatedReqData[i][3] + "#" + ConsolidatedReqData[i][4] + "#" + ConsolidatedReqData[i][5] + "#" + ConsolidatedReqData[i][6]];
                    //console.log(FinGsm,'FinGsm');
                    for (var c = 0; c < ArrYarnDyeColor.length; c++) {
                        YarnDyeingItemizedColorWise.push([ConsolidatedReqData[i][0], ConsolidatedReqData[i][1], ArrYarnDyeColor[c], ConsolidatedReqData[i][3],
                            "","","Yarn Spl Req",ConsolidatedReqData[i][7],ConsolidatedReqData[i][9],"","","",PlanfabSubTotalYD,""]);
                    }
                }
                else {
                    var FinGsm = FinishingGsmGroup[ConsolidatedReqData[i][0] + "#" + ConsolidatedReqData[i][1] + "#" + ConsolidatedReqData[i][2] + "#" + ConsolidatedReqData[i][3] +
                    "#" + ConsolidatedReqData[i][4]];
                    //console.log(FinGsm,'FinGsm');
                    YarnDyeingItemizedColorWise.push([ConsolidatedReqData[i][0], ConsolidatedReqData[i][1], ConsolidatedReqData[i][2], ConsolidatedReqData[i][3],
                        "","","Yarn Spl Req",ConsolidatedReqData[i][7],ConsolidatedReqData[i][9],"","","",PlanfabSubTotalYD,""]);
                }
                console.log(YarnDyeingItemizedColorWise,'YarnDyeingItemizedColorWise');
                //YarnDyeingItemizedColorWise.push();
            }
            //}
        }

        if(GlbItemizedyarnWgtCalc != "") {
            //console.log(ItemizedyarnWgtCalc,'ItemizedyarnWgtCalc inside if');
            savedMorethan1CountOrContentSplitData = GlbItemizedyarnWgtCalc;
        }
        if(savedMorethan1CountOrContentSplitData.length > 0) {
            morethan1CountOrContentSplitData = GlbItemizedyarnWgtCalc;
        }
console.log(morethan1CountOrContentSplitData,'morethan1CountOrContentSplitData');
        $("#morethan1CountOrContentSplit").jexcel({
            //data:SDBItemizedColorWise, morethan1CountOrContentSplit
            data:morethan1CountOrContentSplitData,
            colHeaders: ["Combo","Component","Color","Garment<br/>Parts",
                "Yarn<br/>Blend<br/>(%)",
                "Yarn Content",
                "Yarn Spl. Request<br/>If any",
                "Yarn<br/>Count",
                "Dyeing<br/>Type",
                "No. of Feed.<br/>Per Repeat",
                "No. of Feed.<br/>per Y-Count",
                "Yarn<br/>Count (%)",
                "Plan Fab.<br/>Wgt. Sub<br/>total (Kgs.)",
                "Req. Yarn<br/>Wgt.<br/>(Kgs.)"],
            colWidths: [100,100,100,80,60,100,100,60,70,100,90,70,70,70],
            columns: [
                {type: 'text', wordWrap: true, readOnly: true},
                {type: 'text', wordWrap: true, readOnly: true},
                {type: 'text', wordWrap: true, readOnly: true},
                {type: 'text', wordWrap: true, readOnly: true},
                {type: 'dropdown', wordWrap: true, source: GlbYarnBlendPercent},
                {type: 'dropdown', wordWrap: true, source : YarnContentArr},
                {type: 'text', wordWrap: true, readOnly: true},
                {type: 'text', wordWrap: true, readOnly: true},
                {type: 'text', wordWrap: true, readOnly: true},
                {type: 'text', wordWrap: true},
                {type: 'text', wordWrap: true},
                {type: 'text', wordWrap: true, readOnly: true},
                {type: 'text', wordWrap: true},
                {type: 'text', wordWrap: true, readOnly: true}
            ]
        });
        var yarncountpercent = 0; var PlanFabWgt = 0, NoofFeederPerRepeat = 0, FeederPerYCount = 0;
        $("#morethan1CountOrContentSplit").jexcel('updateSettings',{
            table:function (instance, cell, col, row, val, id) {
                if(col == 9) {
                    NoofFeederPerRepeat = Number($(cell).html());
                }
                if(col == 10) {
                    FeederPerYCount = Number($(cell).html());
                }
                if(col == 11) {
                    var MulFeederPerYCount = FeederPerYCount * 100;
                    yarncountpercent = Number(MulFeederPerYCount) / NoofFeederPerRepeat;
                    $(cell).text(yarncountpercent);
                    //CountPercent = Number($(cell).html());
                }
                if(col == 12) {
                    PlanFabWgt = Number($(cell).html());
                }
                if(col == 13) {
                    var ColorPercentDivision = yarncountpercent / 100;
                    var ReqyarnWgt = PlanFabWgt * ColorPercentDivision;
                    $(cell).text(ReqyarnWgt.toFixed(3))
                }
            }
        });

        /*
                var moreThan1countYarnCountCol = $("#ConsolidatedReqData").jexcel('getColumnData',7);
                var moreThan1countDyeingCol = $("#ConsolidatedReqData").jexcel('getColumnData',9);
                MakePostRequest(base_path+'fabricprogramvtwo/saveFabReqDetails',GlbParam+"&enqid="+enquiryid+"&yc="+JSON.stringify(moreThan1countYarnCountCol)+
                    "&dt="+JSON.stringify(moreThan1countDyeingCol),'json',fnSaveMOrethanOneCountRes);

                function fnSaveMOrethanOneCountRes(data) {
                    console.log(data,'data');
                }
        */
        console.log(GlbArrYarnDyeing,'GlbArrYarnDyeing');

        if(GlbArrYarnDyeing != "") {
            SavedYarnDyeingItemizedColorWise = GlbArrYarnDyeing;
        }
        console.log(SavedYarnDyeingItemizedColorWise,'SavedYarnDyeingItemizedColorWise');
        if(SavedYarnDyeingItemizedColorWise.length > 0) {
            YarnDyeingItemizedColorWise = SavedYarnDyeingItemizedColorWise;
        }
        console.log(YarnDyeingItemizedColorWise,'YarnDyeingItemizedColorWise');
        $("#YarnDyeColorSplit").jexcel({
            data:YarnDyeingItemizedColorWise,
            colHeaders: ["Combo","Component","Color","Garment<br/>Parts",
                "Yarn<br/>Blend (%)",
                "Yarn<br/>Content",
                "Yarn<br/>Spl. Req.",
                "Yarn<br/>Count",
                "Dyeing<br/>Type",
                "No. of Feed.<br/>Per Repeat",
                "No. of Feed.<br/>per Color",
                "Yarn Count<br/>(%)",
                "Plan Fab.<br/>Wgt. Subtotal<br/>(Kgs.)",
                "Req. Yarn<br/>Wgt. (Kgs.)"],
            colWidths: [100,100,100,80,60,100,100,60,60,80,90,80,100,90],
            columns: [
                {type: 'text', wordWrap: true, readOnly: true},
                {type: 'text', wordWrap: true, readOnly: true},
                {type: 'text', wordWrap: true, readOnly: true},
                {type: 'text', wordWrap: true, readOnly: true},
                {type: 'dropdown', wordWrap: true, source : GlbYarnBlendPercent},
                {type: 'dropdown', wordWrap: true, source: YarnContentArr },
                {type: 'text', wordWrap: true, readOnly: true},
                {type: 'text', wordWrap: true, readOnly: true},
                {type: 'text', wordWrap: true, readOnly: true},
                {type: 'text', wordWrap: true},
                {type: 'text', wordWrap: true},
                {type: 'text', wordWrap: true},
                {type: 'text', wordWrap: true,readOnly: true},
                {type: 'text', wordWrap: true, readOnly: true},
            ]
        });
        var NoofFeedPerRepeat = 0; var NoofFeedPerYColor = 0; var PlanFabWgt = 0; var ReqYarnWgt = 0; var YarnCountPercent = 0;
        $("#YarnDyeColorSplit").jexcel('updateSettings',{
            table:function (instance, cell, col, row, val, id) {
                if(col == 0) {
                    NoofFeedPerRepeat = 0; NoofFeedPerYColor = 0; PlanFabWgt = 0; ReqYarnWgt = 0; YarnCountPercent = 0;
                }

                if(col == 9) {
                    NoofFeedPerRepeat = Number($(cell).html());
                }
                if(col == 10) {
                    NoofFeedPerYColor = Number($(cell).html());
                }
                if(col == 11) {
                    var MulNoofFeedPerYColor = NoofFeedPerYColor * 100;
                    YarnCountPercent = MulNoofFeedPerYColor / NoofFeedPerRepeat;
                    $(cell).text(YarnCountPercent.toFixed(3));
                }
                if(col == 12) {
                    PlanFabWgt = Number($(cell).html());
                    //console.log(PlanFabWgt,'PlanFabWgt');
                }

                if(col == 13) {
                    var res = PlanFabWgt * YarnCountPercent;
                    ReqYarnWgt = res / 100;
                    //console.log(ReqYarnWgt,'ReqYarnWgt');
                    $(cell).text(ReqYarnWgt.toFixed(3));
                }
            }
        });
    }

function FnItemizedYarnProgram() {
        var morethan1CountOrContentSplitData = $("#morethan1CountOrContentSplit").jexcel('getData');
        var YarnDyeColorSplitData = $("#YarnDyeColorSplit").jexcel('getData');
    MakePostRequest(base_path+'fabricprogramvtwo/saveItemizedyarnWgtCalc',GlbParam+"&yarnwgtcalc="+JSON.stringify(morethan1CountOrContentSplitData)+"&yarndyeing="+
        JSON.stringify(YarnDyeColorSplitData)+"&enqid="+enquiryid,'json',fnSaveYarnDyeingRes);
}

function fnSaveYarnDyeingRes(data) {
    fnRedirectPageTimeOut(base_path +'fabricprogramvtwo/part_three_fabricdetail/' +HashEnquiryId);
}

</script>
<style>
    .table > tbody > tr > td, .table > tbody > tr > th, .table > tfoot > tr > td, .table > tfoot > tr > th, .table > thead > tr > td, .table > thead > tr > th {padding:5px;}
    .jexcel>thead>tr>td {font-size:12px !important;}
    table {font-size:12px;}

    .jexcel>tbody>tr>td {white-space: normal !important; word-wrap:break-word !important;}
</style>
<?php $this->load->view(CNFCOMPANY.'template/pagefooter'); ?>