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
                        <div class="box-body" style="padding: 0;">
                            <form class="form-horizontal" name="frmBasicInfo" id="frmOrderProcess" method="post">
                                <?php $this->load->view("fabric_program/fabricProPaginationLinks"); ?>
                                <div class="col-md-12 no-padding">
                                    <div style="background-color: rgb(210 253 249); padding-top: 10px; padding-left: 10px">
                                    <strong>YARN DYEING JACQUARD - COLOR WISE QTY. BREAK UP DETAILS</strong>
                                </div>
                                </div>

                                <div class="col-sm-12 table-responsive" style="padding: 5px !important">
                                <div id="twelve"></div>
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
    unsaved = false;
    // A custom method to SUM all the cells in the current column
    SUMCOL = function(instance, columnId) {
        var total = 0;
        for (var j = 0; j < instance.options.data.length; j++) {
            if (Number(instance.records[j][columnId-1].innerHTML)) {
                total += Number(instance.records[j][columnId-1].innerHTML);
            }
        }
        return total.toFixed(3);
    };
    MakeAsynPostRequest(base_path+"fabricprogram/ajaxData",GlbParam+"&enqId="+enquiryId+"&pid="+pageId,"json",function (data) {
        console.log(data, 'data');
        var currentJxlTbl = [];
        planFabWeightSubTotal = '';
        if(data.planFabWeightSubTotal != '') {
            planFabWeightSubTotal = data.planFabWeightSubTotal;
        }
        if(data.jsonFeederLycra != '') {
            console.log(planFabWeightSubTotal,'planFabWeightSubTotal');
            feederLycra = JSON.parse(data.jsonFeederLycra);
            console.log(feederLycra,'feederLycra');
            for(let ii = 0; ii < feederLycra.length; ii++) {
                let fL = feederLycra[ii];
                let colors = fL[2];
                let blend = fL[4];
                let content = fL[5];
                let feeder = fL[6];
                let yarnCount = fL[10];
                let dyeingType = fL[11];
                let splRequest = fL[12];
                if(dyeingType === 'YDJ') {
                    console.log(colors,'colors');
                    if(colors.indexOf(' : ') !== -1) {
                        let colorArr = colors.split(' : ');
                        let ArrBlend = blend.split(' / ');
                        let ArrContent = content.split(' / ');
                        let ArrFeeder = feeder.split(' / ');
                        let ArrSplRequest = splRequest.split(' / ');
                        for(let cc = 0; cc < colorArr.length; cc++) {
                            let yarnCountArr = yarnCount.split(' / ');
                            let planFabWeight = planFabWeightSubTotal[fL[0]+"##"+fL[1]+"##"+colorArr[cc]+"##"+fL[3]+"##"+dyeingType];
                            console.log(planFabWeight,'planFabWeight');
                            let colorPercent = ArrFeeder[cc];
                            let yarnWeight = Number((colorPercent / 100)) * Number(planFabWeight);
                            currentJxlTbl.push([fL[0],fL[1],colorArr[cc],fL[3],ArrBlend[cc],ArrContent[cc],ArrSplRequest[cc],yarnCountArr[cc],
                                dyeingType,planFabWeight,colorPercent,yarnWeight.toFixed(3)]);
                        }
                    }
                    else {
                        let planFabWeight = planFabWeightSubTotal[fL[0]+"##"+fL[1]+"##"+colors+"##"+fL[3]+"##"+dyeingType];
                        let colorPercent = feeder;
                        let yarnWeight = Number((colorPercent / 100)) * Number(planFabWeight);
                        currentJxlTbl.push([fL[0],fL[1],colors,fL[3],blend,content,splRequest,yarnCount,
                            dyeingType]);
                    }
                }
            }
        }
        if(data.jsonJacquard != "") {
            currentJxlTbl = JSON.parse(data.jsonJacquard);
        }
        console.log(currentJxlTbl,'currentJxlTbl');
        jexcel(document.getElementById("twelve"),{
            columns:[
                { title:'Combo', width:140, readOnly: true },
                { title:'Component', width:140, readOnly: true },
                { title:'Color', width:140, readOnly: true },
                { title:'Garment Parts', width:140, readOnly: true },
                { title:'Yarn Blend (%)', width:140, readOnly: true },
                { title:'Yarn Content', width:140, readOnly: true },
                { title:'Yarn Special Request If Any', width:100, readOnly: true },
                { title:'Yarn Count', width:70, readOnly: true },
                { title:'Dyeing Type', width:70, readOnly: true },
                { title:'Plan. Fab. Wgt. - Subtotal (Kgs.)', width: 100, readOnly: true },
                { title:'Color (%)', width:70, readOnly: true },
                { title:'Req. Yarn Wgt. (Kgs)', width: 100, readOnly: true },
            ],
            footers: [['','','','','','','','','','','Total','=SUMCOL(TABLE(), COLUMN())']],
            data: currentJxlTbl,
            columnDrag:true,
            allowInsertRow:false,
            allowInsertColumn:false,
            onchange:function () {
                unsaved = true;
            }
        });

    });


    function cmnSaveChanges() {
        const twelveJxlData = $("#twelve").jexcel('getData');
        let param = "rFrom=1&enqId="+enquiryId+"&d="+JSON.stringify(twelveJxlData);
        MakePostRequest(base_path+"fabricprogram/saveJacquard",param,"json",fnSaveRes);
        function fnSaveRes(data) {
            console.log(data,'data');
            if(data.errCode == 1) {
                unsaved = false;
                $("#divCmnSuccessMsg").removeClass("hide");
                $("#divCmnSuccessMsg").text("saved Successfully");
            }
        }
    }
    function unloadPage() {
        if (unsaved) {
            return "You have unsaved changes on this page. Do you want to leave this page and discard your changes or stay on this page?";
        }
    }
    window.onbeforeunload = unloadPage;
</script>
<?php $this->load->view(CNFCOMPANY.'template/pagefooter'); ?>