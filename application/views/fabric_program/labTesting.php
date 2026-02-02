<?php
$this->load->view(CNFCOMPANY.'template/pageheader');
/*$ArrLoggedUserInfo      = fnGetUserLoggedInfo(1);
$VarUserType            = $ArrLoggedUserInfo['usertype'];
$VarProfilePermission   = $ArrLoggedUserInfo['pp'];
$ArrUserDetails         = fnGetUserLoggedInfo();*/
?>
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/jexcel4/jsuites.css"/>
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/jexcel4/jexcel.css"/>
    <body class="sidebar-mini skin-black wysihtml5-supported sidebar-collapse">
<style>
    /*    td div {
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
        }*/
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
                                <!--<div class="col-md-12 no-padding">
                                    <div style="padding-top: 10px; padding-left: 10px">
                                        <strong>FABRIC DETAILS - KNIT: COLOUR WISE YARN BLEND (%) AND CONTENT</strong>
                                    </div>
                                </div>

                                <div class="col-sm-12 table-responsive" style="padding: 5px !important">
                                    <div id="fabKnitYarnBlendAndContent"></div>
                                </div>

                                <div class="col-md-12 no-padding">
                                    <div style="padding-top: 10px; padding-left: 10px">
                                        <strong>FABRIC DETAILS - KNIT: COLOUR WISE FABRIC BLEND (%) AND CONTENT</strong>
                                    </div>
                                </div>

                                <div class="col-sm-12 table-responsive" style="padding: 5px !important">
                                    <div id="fabDetailKnitColorWiseFabBlendAndContent"></div>
                                </div>-->

                                <div class="col-md-12 no-padding">
                                    <div style="background-color: rgb(210 253 249); padding-top: 10px; padding-left: 10px">
                                        <strong>LAB TESTING DETAILS</strong>
                                    </div>
                                </div>

                                <div class="col-sm-12" style="padding: 5px !important">
                                    <div id="labTesting"></div>
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
    var GlbTestingAuthority = ['SGS','Fac. Lab'];
    var GlbApprovingauthority = ['Buyer','Buyer Agent','Buyer Office','Buyer Office Agent','Third Party','Fac. Q.C.'];
    var apprStatus = ['Pending','Approved','Rejected'];
    unsaved = false;


    var GlbLabTestDesc = GlbAcceptanceLevel = GlbJsonNewFourth = currentTblData = [];
    var colorFilterGroup = {}; var GlbKnitFabricDetails = [];
    MakePostRequest(base_path+'fabricprogram/ajaxData',GlbParam+"&enqId="+enquiryId+"&pid="+pageId,'json',function (data) {
        console.log(data, 'data');
        GlbComboArr = []; GlbComponentArr = []; GlbColorArr = [];
        GlbLabTestDesc = data.ArrLabTestDesc;
        GlbAcceptanceLevel = data.ArrAcceptanceLevel;
        GlbArrFromB4Seventh = data.jsonFromB4Seventh;

        if(data.jsonNewFourth != '') {
            GlbJsonNewFourth = JSON.parse(data.jsonNewFourth);
        }
        if(data.jsonLabTesting != "") {
            currentTblData = JSON.parse(data.jsonLabTesting);
        }
        console.log(GlbArrFromB4Seventh,'GlbArrFromB4Seventh original');
        if(GlbArrFromB4Seventh != "") {
            let FromB4Seventh = JSON.parse(GlbArrFromB4Seventh);
            console.log(FromB4Seventh,'FromB4Seventh');
            console.log(FromB4Seventh.length,'FromB4Seventh len');
            for(let ii = 0; ii < FromB4Seventh.length; ii++) {
                console.log(FromB4Seventh[ii][4],'FromB4Seventh[ii][4]');
                GlbKnitFabricDetails.push(FromB4Seventh[ii][4]+" | "+FromB4Seventh[ii][5]+" | "+FromB4Seventh[ii][6]+" | "+FromB4Seventh[ii][7]);
            }
            GlbKnitFabricDetails.push("Finish Garment");
        }
        console.log(GlbKnitFabricDetails,'GlbKnitFabricDetails');
        console.log(GlbJsonNewFourth,'GlbJsonNewFourth');
        for(let ii = 0; ii < GlbJsonNewFourth.length; ii++) {
            GlbComboArr.push(GlbJsonNewFourth[ii][0]);
            GlbComponentArr.push(GlbJsonNewFourth[ii][1]);
            GlbColorArr.push(GlbJsonNewFourth[ii][2]);
            console.log(GlbComboArr,'GlbComboArrsdfsd');

            colorFilterGroup[GlbJsonNewFourth[ii][0]+"||"+GlbJsonNewFourth[ii][1]] = GlbJsonNewFourth[ii][2];
        }
        GlbComboArr = getUnique(GlbComboArr);
        GlbComponentArr = getUnique(GlbComponentArr);
        GlbColorArr = getUnique(GlbColorArr);
        //let GlbFabricProTwo = JSON.parse(data.jsonFabricTwoJxl);
        //let three = JSON.parse(data.jsonFabricThreeJxl);

/*        jexcel(document.getElementById('fabKnitYarnBlendAndContent'), {
            columns: [
                { title:'Combo', width:135, wordWrap: true, readOnly:true },
                { title:'Component', width:135, wordWrap: true, readOnly:true },
                { title:'Colour', width:140, wordWrap: true, readOnly:true },
                { title:'Garment Parts', width:135, wordWrap: true, readOnly:true },
                { title:'Fabric Blend (%)', width:135, wordWrap: true, readOnly:true },
                { title:'Fabric Content', width:135, wordWrap: true, readOnly:true },
                { type: 'numeric', title:'No. of Feeder', width:50, wordWrap: true, readOnly:true },
                { type: 'numeric', title:'Lycra (%)', width:50, wordWrap: true, readOnly:true },
                { title:'Fabric Name', width:140, wordWrap: true, readOnly:true },
                { title:'Finishing GSM', width:70, readOnly:true },
                { title:'Yarn Count', width:70, readOnly:true },
                { title:'Dyeing Type', width:70, readOnly:true },
                { title:'Yarn Spl. Req.', width:90, readOnly:true }

            ],
            allowInsertRow:false,
            allowInsertColumn:false,
            data : GlbFabricProTwo
        });

        jexcel(document.getElementById("fabDetailKnitColorWiseFabBlendAndContent"), {
            columns: [
                {title: 'Combo', width: 140, wordWrap: true, readOnly: true},
                {title: 'Component', width: 140, wordWrap: true, readOnly: true},
                {title: 'Colour', width: 140, wordWrap: true, readOnly: true},
                {title: 'Garment Parts', width: 130, wordWrap: true, readOnly: true},
                {title: 'Fabric Blend (%) | Lycra (%)', width: 130, wordWrap: true, readOnly: true},
                {title: 'Fabric Content', width: 130, wordWrap: true, readOnly: true},
                {title: 'Fabric Name', width: 130, wordWrap: true, readOnly: true},
                {title: 'Finishing GSM', width: 70, readOnly: true},
                {title: 'Yarn Count', width: 60, readOnly: true},
                {title: 'Dyeing Type', width: 60, readOnly: true},
                {title: 'Fabric Finish Wet Process', width: 110, wordWrap: true, readOnly: true},
                {title: 'Fabric Finish Dry Process', width: 110, wordWrap: true, readOnly: true}
            ],
            data: three,
            allowInsertRow: false,
            allowInsertColumn: false
        });*/

    });

    colorDropdownFilter = function(instance, cell, c, r, source) {
        var first  = instance.jexcel.getValueFromCoords(c - 2, r);
        var second = instance.jexcel.getValueFromCoords(c - 1, r);
        var keys = first+"||"+second;
        console.log(keys,'keys');
        console.log(colorFilterGroup[keys],'colorFilterGroup[keys]');
        if(colorFilterGroup[keys]) {
            return [colorFilterGroup[keys]];
        }
        else {
            return [];
        }
    };
    console.log(GlbComboArr,'GlbComboArr');
    $("#labTesting").jexcel({
        colHeaders: ['Combo', 'Component', 'Colour', 'Item Description', 'Lab Testing Details', 'Acceptable Tolerance<br/>Level', 'Testing Authority','Approval Status',
            'Approving By'],
        colWidths: [140, 140, 140, 200, 150, 150, 150, 145, 140],
        allowInsertColumn: false,
        data: currentTblData,
        minDimensions: [9,1],
        columns: [
            {type: 'dropdown', source: GlbComboArr},
            {type: 'dropdown', source: GlbComponentArr},
            {type: 'dropdown', source: GlbColorArr, filter: colorDropdownFilter},
            {type: 'dropdown', source: GlbKnitFabricDetails },
            {type: 'dropdown', source: GlbLabTestDesc },
            {type: 'dropdown', source: GlbAcceptanceLevel },
            {type: 'dropdown', source: GlbTestingAuthority},
            {type: 'dropdown', source: apprStatus},
            {type: 'dropdown', source: GlbApprovingauthority},
        ],
        onchange:function () {
            unsaved = true;
        }
    });

    function cmnSaveChanges() {
        var data = JSON.stringify($("#labTesting").jexcel('getData'));
        MakeAsynPostRequest(base_path+'fabricprogram/saveLabTesting',GlbParam+"&d="+data+
            "&enqId="+enquiryId+"&pid="+pageId,'json',fnSaveTableRes);
    }

    function fnSaveTableRes(data) {
        if(data!='') {
            if(data.errCode == 1) {
                unsaved = false;
                $("#divCmnSuccessMsg").removeClass('hide');
                $("#divCmnSuccessMsg").text("Success!");
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
<?php $this->load->view(CNFCOMPANY.'template/pagefooter'); ?>