<?php
$this->load->view(CNFCOMPANY.'template/pageheader');
?>
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/jexcel4/jsuites.css"/>
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/jexcel4/jexcel.css"/>
<body class="sidebar-mini skin-black wysihtml5-supported sidebar-collapse">
<style>
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
                        <div class="box-body" style="padding: 0px;">
                            <form class="form-horizontal" name="frmBasicInfo" id="frmOrderProcess" method="post">
                                <?php $this->load->view("fabric_program/fabricProPaginationLinks"); ?>
                                <div class="col-md-12 pd0 no-padding">
                                    <div style="background-color: rgb(210 253 249); padding-top: 10px; padding-left: 10px">
                                        <strong>FABRIC DETAILS - KNIT : COLOUR WISE GARMENT PARTS ENTRY</strong>
                                    </div>
                                </div>

                                <div class="col-md-12 table-responsive" style="padding: 5px !important;">
                                    <div id="garmentPartsJxl"></div>
                                        <button type="button" style="margin-bottom: 10px; margin-right: 30px" class="btn btn-info pull-right" onclick="saveGarmentParts()">Save</button>
                                </div>

                                <div class="col-md-12" style="padding: 5px !important;">
                                    <div class="alert alert-success alert-dismissible hide" id="divSuccessMsg1"></div>

                                </div>
                                <div class="col-md-12 pd0 no-padding">
                                    <div style="background-color: rgb(210 253 249); padding-top: 10px; padding-left: 10px">
                                        <strong>FABRIC DETAILS - KNIT : COLOUR WISE YARN BLEND (%) AND CONTENT</strong>
                                    </div>
                                </div>
                                <div class="col-sm-12 table-responsive" style="padding: 5px !important">
                                    <div id="fabPro1A"></div>
                                </div>
                            </form>
                            <?php $this->load->view("fabric_program/fabricProFooterLinks"); ?>
                        </div>
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
    unsaved = false;
    const enquiryId = '<?php echo $VarEnquiryId ?>';
    const HashEnquiryId = '<?php echo $VarHashEnquiryId ?>';
    const table1Id = '<?php echo $VarTable1Id ?>';
    const table1AId = '<?php echo $VarTable1AId ?>';
    const pageId = '<?php echo $VarPageId ?>';
    let GlbGarmentParts = [];
    let GlbFirstTbl = '';
    let separators = ['-', '/', ':'];
    GlbJsonGarmentPartsJxl = [];
    GlbYarnBlend = []; GlbKnitFabricName = []; GlbYarnSplReq = [];
    GlbYarnCount = []; GlbYarnContent = []; ArrKnitFabricName = []; DyeingType = [];
    fabricOneJxlData = [];
    fabricTwoJxlData = [];
    function saveGarmentParts() {
        let garmentPartsJxl = $("#garmentPartsJxl").jexcel('getData');
        let ArrBeforeSeventh = [];
        for(let ii = 0; ii < garmentPartsJxl.length; ii++) {
            let garmentParts = garmentPartsJxl[ii][3].split(";");
            for(let jj = 0; jj < garmentParts.length; jj++) {
                ArrBeforeSeventh.push([garmentPartsJxl[ii][0],garmentPartsJxl[ii][1],garmentPartsJxl[ii][2],garmentParts[jj]]);
            }
        }
        MakeAsynPostRequest(base_path+"fabricprogram/saveOneJxl","rFrom=1&enqId="+enquiryId+"&tid="+table1Id+
            "&d="+JSON.stringify(garmentPartsJxl)+"&b4Seven="+JSON.stringify(ArrBeforeSeventh),"json",function (data) {
            console.log(data,'data');
            unsaved = false;
            $("#divSuccessMsg1").removeClass('hide');
            $("#divSuccessMsg1").text('Saved Successfully');
            //location.reload();
            fnRedirectPageTimeOut(base_path+'fabricprogram/home/'+HashEnquiryId);
        });
    }
    function cmnSaveChanges() {
        let fabPro1A = $("#fabPro1A").jexcel('getData');
        let param = "rFrom=1&enqId="+enquiryId+"&d="+JSON.stringify(fabPro1A)+"&tid="+table1AId;
        MakePostRequest(base_path+"fabricprogram/saveOneAJxl",param,"json",function (data) {
            console.log(data,'data two jxl');
            unsaved = false;
            $("#divCmnSuccessMsg").removeClass('hide');
            $("#divCmnSuccessMsg").text('Saved Successfully');
        });
    }
    MakeAsynPostRequest(base_path+'fabricprogram/ajaxData',"rFrom=1&enqId="+enquiryId+"&pid="+pageId,'json',function (data) {
        console.log(data, 'data');
        if (data != '') {
            GlbGarmentParts = data.ArrGarmentParts;
            GlbFirstTbl = data.ArrFromFirstTbl;
            GlbYarnBlend = data.ArrYarnBlend;
            GlbYarnContent = data.ArrYarnContent;
            GlbYarnCount = data.ArrYarnCount;
            GlbYarnSplReq = data.ArrYarnSplReq;
            GlbKnitFabricName = data.ArrKnitFabricName;
            DyeingType = data.ArrDyeingType;
                if(data.savedFabricOneJxl != '') {
                    fabricOneJxlData = JSON.parse(data.savedFabricOneJxl);
                    console.log(fabricOneJxlData,'fabricOneJxlData');
                    savedFabricOneJxl = JSON.parse(data.savedFabricOneJxl);
                    let fabricTwoJxl = [];
                    for(let ii = 0; ii < savedFabricOneJxl.length; ii++) {
                        let garmentParts = savedFabricOneJxl[ii][3].split(";");
                        for(let jj = 0; jj < garmentParts.length; jj++) {
                            fabricTwoJxl.push([savedFabricOneJxl[ii][0],savedFabricOneJxl[ii][1],savedFabricOneJxl[ii][2],garmentParts[jj]]);
                        }
                    }
                    if(data.savedFabricOneAJxl == '') {
                        fabricTwoJxlData = fabricTwoJxl;
                    }
                    else {
                        fabricTwoJxlData = JSON.parse(data.savedFabricOneAJxl);
                    }
                }
                else {
                    var splitCompData = []; let colorsMixed = [];

                    for(let ii = 0; ii < GlbFirstTbl.length; ii++) {
                        let comboData = GlbFirstTbl[ii][0];
                        let component = GlbFirstTbl[ii][1];
                        let color = GlbFirstTbl[ii][2];
                        console.log(component.indexOf('/'),'component.indexOf(\'/\')');
                        if(component.indexOf('/') !== -1) {
                            splitCompData = component.split('/');
                            splitCompData = jsTrimArr(splitCompData);
                            console.log(splitCompData,'splitCompData IF HAS /');
                        }
                        else {
                            splitCompData = component;
                        }
                        if(color.indexOf('/') !== -1) {
                            splitColData = color.split('/');
                            splitColData = jsTrimArr(splitColData);
                            console.log(splitColData,'splitColDatasplitColDatasplitColData');
                        }
                        else {
                            splitColData = color;
                        }
                        if(Array.isArray(splitColData) && Array.isArray(splitCompData)) {
                            for(let i = 0; i < splitColData.length; i++) {
                                if(splitCompData[i]) {
                                    console.log(splitColData[i],'splitColData[i]');
                                    console.log(new RegExp(separators.join('|'),'g'),'new RegExp(separators.join');
                                    colorsMixed.push(comboData+"|#|"+splitCompData[i]+"|#|"+splitColData[i].split(new RegExp(separators.join('|'),'g')));
                                }
                            }
                        }
                        else {
                            colorsMixed.push(comboData+"|#|"+splitCompData+"|#|"+splitColData);
                        }
                        console.log(colorsMixed,'colorsMixed');
                    }
                    for(let i = 0; i < colorsMixed.length; i++) {
                        var d = colorsMixed[i].split('|#|');
                        let each = colorsMixed[i];
                        let col = each.substr(each.lastIndexOf('|#|')+3);
                        console.log(col,'col');
                        let colArr = col.split(',');
                        colArr = jsTrimArr(colArr);
                        for(let ii = 0; ii < colArr.length; ii++) {
                            fabricOneJxlData.push([d[0],d[1],colArr[ii]]);
                        }
                    }
                    console.log(fabricOneJxlData,'fabricOneJxlData push');
                }
        }
        else {
            fnCallSessionExpire();
            return false;
        }
        //jexcel.destroy(document.getElementById('BeforeSeventh'));
        jexcel(document.getElementById("garmentPartsJxl"), {
            columns:[
                { title:'Combo', width:350, wordWrap: true, readOnly: true },
                { title:'Component', width:350, wordWrap: true, readOnly: true },
                { title:'Colour', width:350, wordWrap: true, readOnly: true },
                { title:'Garment<br/>Parts', type: 'dropdown', source: GlbGarmentParts, width:307, multiple: true },
            ],
            data: fabricOneJxlData,
            allowInsertRow:false,
            allowInsertColumn:false,
            onchange :function() {
                unsaved = true;
            }
        });
        jexcel(document.getElementById("fabPro1A"),{
            columns: [
                { title:'Combo', width:150, wordWrap: true, readOnly: true },
                { title:'Component', width:150, wordWrap: true, readOnly: true },
                { title:'Colour', width:150, wordWrap: true, readOnly: true },
                { title:'Garment Parts', width:140, multiple: true, wordWrap: true, readOnly: true },
                { title:'Yarn Blend (%)', type: 'dropdown', source: GlbYarnBlend, width:150 },
                { title:'Yarn Content', type: 'dropdown', source: GlbYarnContent, width:150, wordWrap: true },
                { title:'Fabric Name', type: 'dropdown', source: GlbKnitFabricName, width:150, wordWrap: true },
                { title:'Finishing GSM', width:75 },
                { title:'Yarn Count', type: 'dropdown', source: GlbYarnCount, width:73 },
                { title:'Dyeing Type', type: 'dropdown', source: DyeingType, width:70 },
                { title:'Yarn Special Request', type: 'dropdown', source: GlbYarnSplReq, width:100, wordWrap: true }

            ],
            data: fabricTwoJxlData,
            allowInsertRow:false,
            allowInsertColumn:false,
            onchange :function() {
                unsaved = true;
                /*if (c == 5) {
                    console.log(value,'val');
                    if(value.indexOf(':') === -1) {
                    }
                    else {
                        $('#myModal').modal('show');
                    }
                }*/
            }
            //updateTable:function(instance, cell, col, row, val, label, cellName) {}
        });
    });
    function unloadPage() {
        if (unsaved) {
            return "You have unsaved changes on this page. Do you want to leave this page and discard your changes or stay on this page?";
        }
    }
    window.onbeforeunload = unloadPage;
</script>
<?php $this->load->view(CNFCOMPANY.'template/pagefooter'); ?>