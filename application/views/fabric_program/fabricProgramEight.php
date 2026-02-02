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

                                <div class="col-sm-12 no-padding">
                                    <div style="background-color: rgb(210 253 249); padding-top: 10px; padding-left: 10px">
                                    <strong>SIZE WISE KNITTING DIA / DIMENSION</strong>
                                    </div>
                                </div>
                                <div class="col-sm-12 table-responsive" style="padding: 5px !important">
                                <div id="eight"></div>
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
    const HashEnquiryId = '<?php echo $VarHashEnquiryId ?>';
    const pageId = '<?php echo $VarPageId ?>';
    const GlbParam = 'rFrom=1';
    unsaved = false;
    diaDimension = [];
    MakeAsynPostRequest(base_path+"fabricprogram/ajaxData",GlbParam+"&enqId="+enquiryId+"&pid="+pageId,"json",function (data) {
        console.log(data, 'data');
        ArrSizes = data.ArrSizeChart;
        let objEightJxl = {};
        let ArrProcessLoss = [];
        if (data.jsonConCalcProcessLoss != "") {
            processLoss = JSON.parse(data.jsonConCalcProcessLoss);
        }
console.log(processLoss,'processLoss');
        for (let ii = 0; ii < processLoss.length; ii++) {
            let com = processLoss[ii][1];
            let parts = processLoss[ii][3];
            let ssc = processLoss[ii][5];
            if(jQuery.inArray(com+"|#|"+parts+"|#|"+ssc,ArrProcessLoss) === -1) {
                ArrProcessLoss.push(com+"|#|"+parts+"|#|"+ssc);
            }
            //objEightJxl[processLoss[ii][1] + "|#|" + processLoss[ii][3] + "|#|" + processLoss[ii][5]] = [processLoss[ii][1], processLoss[ii][3], processLoss[ii][5]];
        }
        /*for (let prop in objEightJxl) {
            diaDimension.push(objEightJxl[prop]);
        }
        console.log(diaDimension,'diaDimension');*/
        if (data.currentJxl != "") {
            let savedData = JSON.parse(data.currentJxl);
            //console.log(diaDimension,'diaDimension');
            //console.log(savedData,'savedData');
            /*for(let ii = 0; ii < diaDimension.length; ii++) {
                for (let s = 0; s < savedData.length; s++) {
                    diaDimension[ii].push(savedData[ii][s]);
                }
            }*/
            for(let ii = 0; ii < ArrProcessLoss.length; ii++) {
                let ini = ArrProcessLoss[ii].split('|#|');
                console.log(ini,'ini');
                if(savedData[ii]) {
                    console.log(savedData[ii],'savedData[ii]');
                    let newArr = ini.concat(savedData[ii]);
                    diaDimension.push(newArr);
                }
            }
        }
        else {
            for(let ii = 0; ii < ArrProcessLoss.length; ii++) {
                let ini = ArrProcessLoss[ii].split('|#|');
                diaDimension.push(ini);
            }

        }
        columnsForJxl = [
            {title:'Component',readOnly: true,width:170},
            {title:'Garment Parts',readOnly: true,width:160},
            {title:'Size Spec. Code / Fit',readOnly: true,width:170},
            {title:'Description', width: 85, type: 'dropdown', source: ["DIA", "DIM (W * H)"]},
            {title:'Unit Of Measure', width: 85, type: 'dropdown', source: ["Inches", "Cms."]},
        ];

        for(let s = 0; s < ArrSizes.length; s++) {
            columnsForJxl.push({title: ArrSizes[s], width: 85, type: 'numeric'});
        }
        console.log(diaDimension,'diaDimension');
        jexcel(document.getElementById("eight"), {
            columns: columnsForJxl,
            data: diaDimension,
            columnDrag: true,
            allowInsertRow: false,
            allowInsertColumn: false,
            onchange:function () {
                unsaved = true;
            }
        });
    });


    function cmnSaveChanges() {
        let d = $("#eight").jexcel('getData');
        let dd = [];
        for(let ii = 0; ii < d.length; ii++) {
            dd.push([d[ii][3],d[ii][4]]);
            for(let ss = 5; ss < ArrSizes.length + 5; ss++) {
                dd[ii].push(d[ii][ss]);
            }
        }
        console.log(dd,'dd');
        /* Saving seven and seven A (Previous Table)
     * Since Save button is removed in seven (Previous table)
    */
        if (typeof(Storage) !== "undefined") {
            var locStr_sevenAJxl = localStorage.getItem("locStr_sevenAJxl");
        }
        MakePostRequest(base_path+"fabricprogram/saveEightJxl","rFrom=1&enqId="+enquiryId+"&d="+JSON.stringify(dd)+"&pid="+
            pageId+"&cummuConCalc="+locStr_sevenAJxl,"json",fnSaveEightRes);
        function fnSaveEightRes(data) {
            console.log(data,'data');
            //fnRedirectPageTimeOut(base_path+"fabricprogram/nine/"+HashEnquiryId);
            if(data.errCode === 1) {
                unsaved = false;
                $("#divCmnSuccessMsg").removeClass('hide');
                $("#divCmnSuccessMsg").text('Saved Successfully');
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