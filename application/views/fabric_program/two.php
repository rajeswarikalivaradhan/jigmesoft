<?php
$this->load->view(CNFCOMPANY . 'template/pageheader');
?>
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/jexcel4/jsuites.css"/>
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/jexcel4/jexcel.css"/>
    <body class="sidebar-mini skin-black wysihtml5-supported sidebar-collapse">
<style>
</style>
<div class="wrapper">
    <?php $this->load->view(CNFCOMPANY . 'template/templateheader'); ?>
    <!-- Left side column. contains the logo and sidebar -->
    <aside class="main-sidebar">
        <?php $this->load->view(CNFCOMPANY . 'template/templateleftmenu'); ?>
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
                                <div class="col-md-12 pd0 no-padding">
                                    <div style="background-color: rgb(210 253 249); padding-top: 10px; padding-left: 10px">
                                        <strong>FABRIC DETAILS - KNIT : COLOR WISE LYCRA (%)</strong>
                                    </div>
                                </div>
                                <div class="col-sm-12 table-responsive" style="padding: 5px !important">
                                    <div id="twoLycraJxl"></div>
                                </div>
                            </form>
                            <?php $this->load->view("fabric_program/fabricProFooterLinks"); ?>
                        </div>

                    </div>
                </div>
            </div>
        </section>
    </div><!-- /.content-wrapper -->
    <?php $this->load->view(CNFCOMPANY . 'template/templatefooter'); ?>
    <div class="control-sidebar-bg"></div>
</div><!-- ./wrapper -->
<script src="<?php echo base_url(); ?>assets/js/jexcel4/jsuites.js"></script>
<script src="<?php echo base_url(); ?>assets/js/jexcel4/jexcel.js"></script>
<script>
    const enquiryId = '<?php echo $VarEnquiryId ?>';
    const pageId = '<?php echo $VarPageId ?>';
    const tableId = '<?php echo $VarTableId ?>';
    const GlbParam = 'rFrom=1';
    unsaved = false;
    function jQueryInArrInsert(ArrName, KeyValue, InsertVal) {
        if (jQuery.inArray(KeyValue, ArrName)) {
            ArrName[KeyValue] = ArrName[KeyValue]+"|#|"+InsertVal;
        }
        return ArrName;
    }
    const reducer = ( accumulator, currentValue) => parseInt(accumulator) + parseInt(currentValue);
    MakeAsynPostRequest(base_path + 'fabricprogram/ajaxData', GlbParam + "&enqId=" + enquiryId+"&pid="+pageId, 'json', function (data) {
        console.log(data,'data');
        console.log(data.ArrFabricOneAJxlMergedColor,'data.ArrFabricOneAJxlMergedColor');
        if(data.ArrFabricOneAJxlMergedColor != '') {
            jsonFabOneA = data.ArrFabricOneAJxlMergedColor;
        }
        if(data.fabricProTwoJxl != '') {
            jsonFabOneA = JSON.parse(data.fabricProTwoJxl);
        }
        jexcel(document.getElementById('twoLycraJxl'), {
            columns: [
                {title: 'Combo', width: 133, wordWrap: true, readOnly: true},
                {title: 'Component', width: 133, wordWrap: true, readOnly: true},
                {title: 'Colour', width: 135, wordWrap: true, readOnly: true},
                {title: 'Garment Parts', width: 135, wordWrap: true, readOnly: true},
                {title: 'Yarn Blend (%)', width: 135, wordWrap: true, readOnly: true},
                {title: 'Yarn Content', width: 135, wordWrap: true, readOnly: true},
                {title: 'No. of Feeder / Colour (%)', width: 80, wordWrap: true},
                {type: 'numeric', title: 'Lycra (%)', width: 50, wordWrap: true},
                {title: 'Fabric Name', width: 130, wordWrap: true, readOnly: true},
                {title: 'Finishing GSM', width: 70, readOnly: true},
                {title: 'Yarn Count', width: 70, readOnly: true},
                {title: 'Dyeing Type', width: 70, readOnly: true},
                {title: 'Yarn Spl. Req.', width: 100, readOnly: true},
            ],
            allowInsertRow: false,
            allowInsertColumn: false,
            data: jsonFabOneA,
            onchange:function () {
                unsaved = true;
            }
        });
    });
    //let GlbSeparators1 = [':','%'];
    function cmnSaveChanges() {
        let d = $("#twoLycraJxl").jexcel('getData');
        let objFabBlendResult = {};
        for (let ii = 0; ii < d.length; ii++) {
            let cbo = d[ii][0];
            console.log(cbo+"##"+d[ii][1]+"##"+d[ii][3],'d[ii][0]+"##"+d[ii][1]+"##"+d[ii][3]');
            let com = d[ii][1];
            let parts = d[ii][3];
            let yarnBlend = d[ii][4];
            let yarnContent = d[ii][5];
            let noOfFeeder = d[ii][6];
            let lycra = Number(d[ii][7]);
            /* First split with slash with Lycra */
            if(yarnBlend.indexOf('/') !== -1 && yarnContent.indexOf('/') !== -1 && noOfFeeder.indexOf('/') !== -1) {
                console.log(yarnBlend,' ',yarnContent,' ',noOfFeeder,' ', lycra);
                let ArrYarnBlend = yarnBlend.split('/');
                let ArrYarnContent = yarnContent.split('/');
                let ArrNoOfFeeder = noOfFeeder.split('/');
                let feederTotal = ArrNoOfFeeder.reduce(reducer);
                for (let yb = 0; yb < ArrYarnBlend.length; yb++) {
                    /* two values inside 1 yarn
                     * Example: 85 : 15 / 100
                    * */
                    if(ArrYarnBlend[yb].indexOf(':') !== -1) {
                        let Arr2YarnBlend = ArrYarnBlend[yb].split(':');
                        for (let yb2 = 0; yb2 < Arr2YarnBlend.length; yb2++) {
                            //parseInt(Arr2YarnBlend[yb2]);
                            let noOfFeederFinal = parseInt(ArrNoOfFeeder[yb]);
                            let fabBlendPercent = (noOfFeederFinal * 100) / parseInt(feederTotal);
                            let fabBlendPercentFinal = fabBlendPercent.toFixed(2);
                            let lycraPercent = fabBlendPercentFinal * (lycra / 100);
                            let lycraPercentFinal = lycraPercent.toFixed(2);
                            let fabBlendWithLycra = fabBlendPercentFinal - lycraPercentFinal;
                            let fabBlendWithLycraFinal = fabBlendWithLycra.toFixed(2);
                            let fabBlend = fabBlendWithLycraFinal * (parseInt(Arr2YarnBlend[yb2]) / 100);
                            objFabBlendResult = jQueryInArrInsert(objFabBlendResult,cbo+"##"+com+"##"+parts+"##"+lycra,
                                jsTrim(ArrYarnContent[yb])+"##"+parseInt(ArrYarnBlend[yb2])+"##"+noOfFeederFinal+"##"+fabBlendPercentFinal+"##"+
                                lycraPercentFinal+"##"+fabBlend.toFixed(2));
                        }
                    }
                    else {
                        let noOfFeederFinal = parseInt(ArrNoOfFeeder[yb]);
                        let fabBlendPercent = (noOfFeederFinal * 100) / parseInt(feederTotal);
                        let fabBlendPercentFinal = fabBlendPercent.toFixed(2);
                        let lycraPercent = fabBlendPercentFinal * (lycra / 100);
                        let lycraPercentFinal = lycraPercent.toFixed(2);
                        let fabBlendWithLycra = fabBlendPercentFinal - lycraPercentFinal;
                        let fabBlendWithLycraFinal = fabBlendWithLycra.toFixed(2);
                        objFabBlendResult = jQueryInArrInsert(objFabBlendResult,cbo+"##"+com+"##"+parts+"##"+lycra,
                            jsTrim(ArrYarnContent[yb])+"##"+parseInt(ArrYarnBlend[yb])+"##"+noOfFeederFinal+"##"+fabBlendPercentFinal+"##"+
                            lycraPercentFinal+"##"+fabBlendWithLycraFinal);
                    }
                }
            }
            else if(yarnBlend.indexOf(':') !== -1 && yarnContent.indexOf(':') !== -1) {
                console.log(yarnBlend,' ',yarnContent,' ',noOfFeeder,' ', lycra);
                let ArrYarnBlend = yarnBlend.split(':');
                let ArrYarnContent = yarnContent.split(':');
                for (let yb = 0; yb < ArrYarnBlend.length; yb++) {
                    let fabBlendPercentFinal = parseInt(ArrYarnBlend[yb]);
                    let lycraPercent = fabBlendPercentFinal * (lycra / 100);
                    let lycraPercentFinal = lycraPercent.toFixed(2);
                    let fabBlendWithLycra = fabBlendPercentFinal - lycraPercentFinal;
                    let fabBlendWithLycraFinal = fabBlendWithLycra.toFixed(2);
                    console.log(fabBlendWithLycraFinal,'fabBlendWithLycraFinal IN TEST');
                    objFabBlendResult = jQueryInArrInsert(objFabBlendResult,cbo+"##"+com+"##"+parts+"##"+lycra,
                        jsTrim(ArrYarnContent[yb])+"##"+jsTrim(ArrYarnBlend[yb])+"##"+0+"##"+fabBlendPercentFinal+"##"+
                        lycraPercentFinal+"##"+fabBlendWithLycraFinal);
                }
            }
            else {
                console.log(yarnBlend,' ',yarnContent,' ',noOfFeeder,' ', lycra);
                let fabBlendPercentFinal = parseInt(yarnBlend);
                let lycraPercentFinal = 0;
                let fabBlendWithLycra = fabBlendPercentFinal - lycraPercentFinal;
                let fabBlendWithLycraFinal = fabBlendWithLycra.toFixed(2);
                objFabBlendResult = jQueryInArrInsert(objFabBlendResult,cbo+"##"+com+"##"+parts+"##"+lycra,
                    yarnContent+"##"+parseInt(yarnBlend)+"##"+0+"##"+fabBlendPercentFinal+"##"+
                    lycraPercentFinal+"##"+fabBlendWithLycraFinal);
            }
        }
        console.log(objFabBlendResult,'objFabBlendResult');
        console.log(jsonFabOneA,'jsonFabOneA');
        let jxlData = []; var objJxlData = {};
        for (let ii = 0; ii < jsonFabOneA.length; ii++) {
            let cbo = jsonFabOneA[ii][0];
            let col = jsonFabOneA[ii][2];
            let com = jsonFabOneA[ii][1];
            let parts = jsonFabOneA[ii][3];
            let yarnBlend = jsonFabOneA[ii][4];
            let yarnContent = jsonFabOneA[ii][5];
            let lycra = Number(jsonFabOneA[ii][7]);
            console.log(yarnBlend,'yarnBlend');
            console.log(lycra,'lycra');
            let objResultStr = objFabBlendResult[cbo+"##"+com+"##"+parts+"##"+lycra];
            let ArrFabricBlend = [];
            if(objResultStr) {
                let objResult = objResultStr.replace('undefined|#|','');
                console.log(objResult,'objResult');
                let ArrResult = objResult.split('|#|');
                console.log(ArrResult,'ArrResult');
                for(let ij = 0 ; ij < ArrResult.length; ij++) {
                    subArrResult = ArrResult[ij].split('##');
                    console.log(subArrResult[5],'subArrResult[5]');
                    ArrFabricBlend.push(subArrResult[5]);
                }
                let fabricBlendFinal = ArrFabricBlend.join(' / ');
                console.log(fabricBlendFinal,'fabricBlendFinal');
                if(lycra) {
                    var fabContentFinal = yarnContent+" | "+lycra;
                }
                else {
                    var fabContentFinal = yarnContent;
                }
                objJxlData[cbo+"##"+com+"##"+col+"##"+parts] = fabricBlendFinal+"|#|"+fabContentFinal+"|#|"+jsonFabOneA[ii][8]+"|#|"+jsonFabOneA[ii][9]
                    +"|#|"+jsonFabOneA[ii][10]+"|#|"+jsonFabOneA[ii][11];
            }
        }
        console.log(objJxlData,'objJxlData');
        console.log(jxlData,'jxlData');
        for(let jxlProp in objJxlData) {
            let rightSide = objJxlData[jxlProp];
            let rhs = rightSide.split('|#|');
            let lhs = jxlProp.split('##');
            jxlData.push([lhs[0],lhs[1],lhs[2],lhs[3],rhs[0],rhs[1],rhs[2],rhs[3],rhs[4],rhs[5]]);
        }
        let param = "rFrom=1&enqId="+enquiryId+"&tid="+tableId+"&twoData="+JSON.stringify(d)+"&threeData="+JSON.stringify(jxlData);
        MakeAsynPostRequest(base_path+'fabricprogram/saveTwoJxl',param,"json",function (data) {
            if(data.errCode === 1) {
                unsaved = false;
                $("#divCmnSuccessMsg").removeClass('hide');
                $("#divCmnSuccessMsg").text('Saved Successfully');
            }
        });
    }

    function unloadPage() {
        if (unsaved) {
            return "You have unsaved changes on this page. Do you want to leave this page and discard your changes or stay on this page?";
        }
    }
    window.onbeforeunload = unloadPage;
</script>
<?php $this->load->view(CNFCOMPANY . 'template/pagefooter'); ?>