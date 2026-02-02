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
                    <div class="box box-primary">
                        <div class="box-body" style="padding: 0">
                            <form class="form-horizontal" name="frmBasicInfo" id="frmOrderProcess" method="post">
                                <?php $this->load->view("fabric_program/fabricProPaginationLinks"); ?>
                                <div class="col-sm-12 no-padding">
                                    <div style="background-color: rgb(210 253 249); padding-top: 10px; padding-left: 10px">
                                    <strong>SIZE WISE PIECE WEIGHT PER UNIT</strong>
                                    </div>
                                </div>

                                <div class="col-sm-12 table-responsive" style="padding: 5px !important">
                                    <div id="five"></div>
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
    const tableId = '<?php echo $VarTableId ?>';
    const GlbParam = 'rFrom=1';
    unsaved = false;
    MakeAsynPostRequest(base_path+"fabricprogram/ajaxData","rFrom=1&enqId="+enquiryId+"&pid="+pageId,"json",function (data) {
        console.log(data,'data');
        var GlbReadOnlyInfo = [];
        var objFiveJxl = {};
        var currentJxl = []; var json3in4pJxl = '';
        var tempArr = [];

        ArrSizes = data.ArrSizeChart;
        console.log(ArrSizes,'ArrSizes');
        //let GlbFabricProTwo = JSON.parse(data.jsonFabricTwoJxl);
        //let three = JSON.parse(data.jsonFabricThreeJxl);
        if(data.jsonExcessQty != '')
            json3in4pJxl = JSON.parse(data.jsonExcessQty);
        else {
            json3in4pJxl = [];
        }
        console.log(json3in4pJxl,'three');
        console.log(json3in4pJxl,'json3in4pJxl');
        for(let ii = 0; ii < json3in4pJxl.length; ii++) {
            let com = json3in4pJxl[ii][1];
            let parts = json3in4pJxl[ii][3];
            let ssc = json3in4pJxl[ii][5];
            if(parts.indexOf('-') === -1) {
                if(jQuery.inArray(com+"|#|"+parts+"|#|"+ssc,tempArr) === -1) {
                    tempArr.push(com+"|#|"+parts+"|#|"+ssc);
                }

                //objFiveJxl[com+"|#|"+parts+"|#|"+ssc] = [com,parts,ssc];
            }
            else {
                let ArrParts = parts.split('-');
                for(let jj = 0; jj < ArrParts.length; jj++) {
                    //objFiveJxl[com+"|#|"+ArrParts[jj]+"|#|"+ssc] = [com,ArrParts[jj],ssc];
                    if(jQuery.inArray(com+"|#|"+ArrParts[jj]+"|#|"+ssc,tempArr) === -1) {
                        tempArr.push(com+"|#|"+ArrParts[jj]+"|#|"+ssc);
                    }

                }
            }
        }
        console.log(tempArr,'tempArr');
        console.log(objFiveJxl,'objFiveJxl');
        if(data.fabricProCurrent != '') {
            var savedData = JSON.parse(data.fabricProCurrent);
        }
        //for(let prop in objFiveJxl) {
            //if (objFiveJxl.hasOwnProperty(prop)) {
                //console.log(objFiveJxl[prop],'objFiveJxl[prop]');
                //let comPartsSsc = objFiveJxl[prop];
                //currentJxl.push(comPartsSsc);
                //for(let s = 0; s < savedData.length; s++) {

                    //currentJxl.push(savedData[s]);
                //}

            //}

        //}
        objFiveJxl = {}; var initial = [];
        if(data.fabricProCurrent != '') {
            //console.log(currentJxl,'currentJxl IF data.fabricProCurrent NOT EMPTY');
            //let savedData = JSON.parse(data.fabricProCurrent);
            //console.log(savedData,'savedData');
            //console.log(savedData.length,'savedData LEN');
            //console.log(currentJxl.concat(savedData),'CONCAT');
            for(let c = 0; c < tempArr.length; c++) {
                //for(let s = 0; s < savedData.length; s++) {
                    //console.log(tempArr[c],'tempArr[c]');
                    ini = tempArr[c].split('|#|');
                    //console.log(ini,'ini');
                    console.log(savedData[c],'savedData[c]');
                    if(savedData[c]) {
                        //ini.push(savedData[c]);
                        let newArr = ini.concat(savedData[c]);
                        console.log(newArr,'newArr');
                        //console.log(ini,'AFTTER PUSH');
                        currentJxl.push(newArr);
                    }
                //}
            }
            //currentJxl = ini;
        }
        else {
            for(let t = 0; t < tempArr.length; t++) {
                let ini = tempArr[t].split('|#|');
                initial.push(ini);
            }
            currentJxl = initial;
        }
        console.log(currentJxl,'currentJxl AFTER push');
        //FIVE
        columnsForJxl = [{title:'Component',readOnly: true,width:200}, {title:'Garment Parts', width:200, readOnly: true},
            {title:'Size Spec. Code / Fit', width:200, readOnly: true}, {title:'Description', width:100, type:'dropdown', source: ["Piece Wgt.","Con. Per Piece"]},
            {title:'Unit of Measure', width:90, type:'dropdown', source: ["Gms.","Kgs.","Meters"]}];

        for(let ij = 0; ij < ArrSizes.length; ij++) {
            columnsForJxl.push({title: ArrSizes[ij], width: 70});
        }
        console.log(columnsForJxl,'columnsForJxl');
        jexcel(document.getElementById("five"),{
            columns: columnsForJxl,
            data: currentJxl,
            allowInsertColumn:false,
            allowInsertRow: false,
            onchange:function () {
                unsaved = true;
            }
        });
    });

    function cmnSaveChanges() {
        let d = $("#five").jexcel('getData');
        let dd = [];
        for(let ii = 0; ii < d.length; ii++) {
            dd.push([d[ii][3],d[ii][4]]);
            console.log(ArrSizes.length + 5,'ArrSizes.length + 5');
            for(let ss = 5; ss < ArrSizes.length + 5; ss++) {
                let makeNum = Number(d[ii][ss]);
                dd[ii].push(makeNum.toFixed(3));
            }
        }
        console.log(dd,'dd');
        MakeAsynPostRequest(base_path+"fabricprogram/saveFiveJxl",GlbParam+"&enqId="+enquiryId+"&d="+JSON.stringify(dd)+"&tid="+tableId,"json",function (data) {
            console.log(data,'data');
            if(data.errCode === 1) {
                unsaved = false;
                $("#divCmnSuccessMsg").removeClass('hide');
                $("#divCmnSuccessMsg").text('Saved Successfully');
            }
        });
    }

    /*function fnSaveFive() {
        const fiveJxl = constFiveJxl.getData();
        console.log(fiveJxl,'fiveJxl');
        MakePostRequest(base_path+"fabricprogram/saveFabricProgramFiveJxl","rFrom=1&enqId="+enquiryId+"&fiveJxl="+JSON.stringify(fiveJxl),"json",fnRes);
        function fnRes(data) {
            if(data != '') {
                if(data.errCode == -1){
                    $("#divFailureMsg").removeClass("hide");
                    $("#divFailureMsg").text("Error");
                } else if(data.errCode == 1) {
                    $("#divSuccessMsg").removeClass("hide");
                    $("#divSuccessMsg").text("saved SIZE WISE PIECE WEIGHT PER UNIT");
                }
            }
        }

    }

    */

    function unloadPage() {
        if (unsaved) {
            return "You have unsaved changes on this page. Do you want to leave this page and discard your changes or stay on this page?";
        }
    }
    window.onbeforeunload = unloadPage;

</script>
<?php $this->load->view(CNFCOMPANY.'template/pagefooter'); ?>