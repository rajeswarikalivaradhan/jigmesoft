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
                                        <strong>FABRIC DETAILS - KNIT: COLOUR WISE FABRIC BLEND (%) AND CONTENT</strong>
                                    </div>
                                </div>
                                <div class="col-sm-12 table-responsive" style="padding: 5px !important">

                                    <div id="fabFinishProcess"></div>

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

    MakeAsynPostRequest(base_path + 'fabricprogram/ajaxData', GlbParam + "&enqId=" + enquiryId+"&pid="+pageId, 'json', function (data) {
        console.log(data,'data');
        let GlbCurrentTbl = [[]];
        if(data.jsonFabricThreeJxl != '') {
            GlbCurrentTbl = JSON.parse(data.jsonFabricThreeJxl);
        }
        let GlbWetProcess = data.WetProcess;
        let GlbDryProcess = data.DryProcess;
        jexcel(document.getElementById("fabFinishProcess"), {
            columns: [
                { type: 'text',title:'Combo', width:140, wordWrap: true, readOnly:true },
                { type: 'text',title:'Component', width:140, wordWrap: true, readOnly:true },
                { type: 'text',title:'Colour', width:140, wordWrap: true, readOnly:true },
                { type: 'text',title:'Garment Parts', width:130, wordWrap: true, readOnly:true },
                { type: 'text',title:'Fabric Blend (%) | Lycra (%)', width:140, wordWrap: true, readOnly:true },
                { type: 'text',title:'Fabric Content', width:140, wordWrap: true, readOnly:true },
                { type: 'text',title:'Fabric Name', width:130, wordWrap: true, readOnly:true },
                { type: 'text',title:'Finishing GSM', width:70, readOnly:true },
                { type: 'text',title:'Yarn Count', width:70, readOnly:true },
                { type: 'text',title:'Dyeing Type', width:70, readOnly:true },
                { type: 'dropdown',title:'Fabric Finish Wet Process', width:100,wordWrap: true, source: GlbWetProcess },
                { type: 'dropdown',title:'Fabric Finish Dry Process', width:100,wordWrap: true, source: GlbDryProcess }
            ],
            data : GlbCurrentTbl,
            allowInsertRow:false,
            allowInsertColumn:false,
            onchange:function () {
                unsaved = true;
            }
        });
    });

    function cmnSaveChanges() {
        let d = $("#fabFinishProcess").jexcel('getData');
        MakeAsynPostRequest(base_path+"fabricprogram/saveThreeJxl",GlbParam+"&enqId=" + enquiryId+"&tableId="+tableId+"&d="+
            JSON.stringify(d)+"&tid="+tableId,"json",function (data) {
            console.log(data,'data');
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