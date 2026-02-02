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
                                <div class="col-md-12 no-padding">
                                    <div style="background-color: rgb(210 253 249); padding-top: 10px; padding-left: 10px">
                                        <strong>DYEING COLOUR DETAILS & Fabric Finishing Requirements</strong>
                                    </div>
                                </div>
                                <div class="col-sm-12 table-responsive" style="padding: 5px !important">
                                    <div id="dyeingdetailsninthtbl"></div>
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
    dyeingColDetail = [];
    MakeAsynPostRequest(base_path + 'fabricprogram/ajaxData', GlbParam + "&enqId=" + enquiryId+"&pid="+pageId, 'json', function (data) {
        console.log(data, 'data');
        let GlbWetDryProcess = data.wetDryProcess;
        GlbKnitFabricContent = data.knitFabricContent;
        GlbColorMatchStd = data.colorMatchStd;
        GlbDyeingSplReq = data.dyeingSplReq;
        let ArrFabProThree = data.ArrFabProThree;
        let currentJxlData = '';
        if(data.currentTbl != '') {
            if(ArrFabProThree.length > 0) {
                currentJxlData = JSON.parse(data.currentTbl);
                console.log(currentJxlData,'currentJxlData');
                console.log(ArrFabProThree,'ArrFabProThree');
                if(ArrFabProThree.length > 0) {
                    for(let ii = 0; ii < ArrFabProThree.length; ii++) {
                        let col = ArrFabProThree[ii][2];
                        let yarnCount = ArrFabProThree[ii][6];
                        console.log(yarnCount,'yarnCount');
                        //let ArrYarnCount = yarnCount.split('/');
                        //console.log(ArrYarnCount,'ArrYarnCount');
                        console.log(col,'col');
                        if(currentJxlData[ii]) {
                            if(col.indexOf(':') !== -1) {
                                let ArrCol = col.split(':');
                                for(let a = 0; a < ArrCol.length; a++) {
                                    dyeingColDetail.push([ArrFabProThree[ii][0],ArrFabProThree[ii][1],jsTrim(ArrCol[a]),ArrFabProThree[ii][3],ArrFabProThree[ii][4],
                                        ArrFabProThree[ii][5],yarnCount,ArrFabProThree[ii][7],currentJxlData[ii][0],currentJxlData[ii][1],
                                        currentJxlData[ii][2],currentJxlData[ii][3]]);
                                }
                            }
                            else {
                                dyeingColDetail.push([ArrFabProThree[ii][0],ArrFabProThree[ii][1],col,ArrFabProThree[ii][3],ArrFabProThree[ii][4],
                                    ArrFabProThree[ii][5],yarnCount,ArrFabProThree[ii][7],currentJxlData[ii][0],currentJxlData[ii][1],
                                    currentJxlData[ii][2],currentJxlData[ii][3]]);
                            }
                            console.log(ii,'ii');
                        }

                    }
                }
            }
        }
        else {
            //currentJxlData = [[]];
            if(ArrFabProThree.length > 0) {
                //currentJxlData = ArrFabProThree;
            }
            else {
                //currentJxlData = [[]];
            }
        }
        console.log(dyeingColDetail.length,'dyeingColDetail LEN');
        console.log(dyeingColDetail.length === 0,'dyeingColDetail.length === 0');
        if(dyeingColDetail.length === 0) {
            currentJxlData = ArrFabProThree;
            let tempJxl = [];
            for(let ii = 0; ii < ArrFabProThree.length; ii++) {
                let col = ArrFabProThree[ii][2];
                let yarnCount = ArrFabProThree[ii][6];
                console.log(yarnCount,'yarnCount');
                console.log(col,'col');
                if(currentJxlData[ii]) {
                    if(col.indexOf(':') !== -1) {
                        let ArrCol = col.split(':');
                        //let ArrYarnCount = yarnCount.split('/');
                        //console.log(ArrYarnCount,'ArrYarnCount');
                        for(let a = 0; a < ArrCol.length; a++) {
                            console.log(yarnCount,'ArrYarnCount[a]');
                            tempJxl.push([ArrFabProThree[ii][0],ArrFabProThree[ii][1],jsTrim(ArrCol[a]),ArrFabProThree[ii][3],ArrFabProThree[ii][4],
                                ArrFabProThree[ii][5],yarnCount,ArrFabProThree[ii][7]]);
                        }
                    }
                    else {
                        tempJxl.push([ArrFabProThree[ii][0],ArrFabProThree[ii][1],ArrFabProThree[ii][2],ArrFabProThree[ii][3],ArrFabProThree[ii][4],
                            ArrFabProThree[ii][5],yarnCount,ArrFabProThree[ii][7]]);
                    }
                    console.log(ii,'ii');
                }
            }
            currentJxlData = tempJxl;
        }
        else {
            console.log(dyeingColDetail,'dyeingColDetail ELSE PART OF LEN === 0');

            currentJxlData = dyeingColDetail;
        }
        //console.log(currentJxlData,'currentJxlData');
        $("#dyeingdetailsninthtbl").jexcel({
            data: currentJxlData,
            columns: [
                {title: 'Combo', width: 120, wordWrap: true, readOnly: true},
                {title: 'Component', width: 120, wordWrap: true, readOnly: true},
                {title: 'Colour', width: 120, wordWrap: true, readOnly: true},
                {title: 'Garment Parts', width: 100, wordWrap: true, readOnly: true},
                {title: 'Fabric Name', width: 100, wordWrap: true, readOnly: true},
                {title: 'Finishing GSM', width: 70, wordWrap: true, readOnly: true},
                {title: 'Yarn Count', width: 70, wordWrap: true, readOnly: true},
                {title: 'Dying Type', width: 70, wordWrap: true, readOnly: true},
                {title: 'Pantone No. / Swatch Ref. Details', width: 120, wordWrap: true},
                {title: 'Blended Fab. Color Matching Content', type: 'dropdown', width: 120, wordWrap: true, source: GlbKnitFabricContent, multiple: true},
                {title: 'Color Mat.<br/>Std.', width: 80, type: 'dropdown', source: GlbColorMatchStd, wordWrap: true},
                {title: 'Fabric Finish Wet & Dry Process', width: 137, type: 'dropdown', source: GlbWetDryProcess, multiple:true, wordWrap: true},
                {title: 'Dyeing Spl. Req. If Any', type: 'dropdown', width: 130, wordWrap: true, source: GlbDyeingSplReq, multiple: true}
            ],
            allowInsertColumn: false,
            onchange:function () {
                unsaved = true;
            }
        });
    });

    function cmnSaveChanges() {
        var dyeingColorDetailsJxl = [];
        let d = $("#dyeingdetailsninthtbl").jexcel('getData');
        console.log(d,'d');
        for(let ii = 0; ii < d.length; ii++) {
            dyeingColorDetailsJxl.push([d[ii][8],d[ii][9],d[ii][10],d[ii][11]]);
        }
        console.log(dyeingColorDetailsJxl,'dyeingColorDetailsJxl');
        MakeAsynPostRequest(base_path + 'fabricprogram/saveThreeAJxl','rFrom=1&enqId='+enquiryId+'&tid='+tableId+'&d='+JSON.stringify(dyeingColorDetailsJxl),'json',
            function (data) {
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