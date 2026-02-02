<?php $this->load->view(CNFCOMPANY . 'template/pageheader'); ?>
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/uploadfile-order.css"/>
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/jexcel4/jsuites.css"/>
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/jexcel4/jexcel.css"/>
    <body class="sidebar-mini skin-black wysihtml5-supported sidebar-collapse">
<div class="wrapper">
    <?php $this->load->view(CNFCOMPANY . 'template/templateheader'); ?>
    <aside class="main-sidebar">
        <?php $this->load->view(CNFCOMPANY . 'template/templateleftmenu'); ?>
    </aside>
    <div class="content-wrapper" >
        <div class="col-md-12">
            <section class="content-header">
                <h1 class="firstHeading">BOM Program</h1>
            </section>
        </div>
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div id="DivContBasicInfo">
                        <div class="box box-info">
                            <div class="box-body" style="padding: 0">
                                <form class="form-horizontal" name="frmBasicInfo" id="frmOrderProcess" method="post">
                                    <div class="col-md-12 pd0 no-padding">
                                        <?php $this->load->view(CNFCOMPANY . "bill_of_materials/common_details"); ?>
                                    </div>

                                    <div class="col-sm-12" style="padding: 5px !important">
                                            <strong>BILL OF MATERIALS: Article - 1</strong>
                                            <div id="bom_article1"></div>
                                    </div>

                                    <div class="col-md-12" style="padding: 5px !important;">
                                            <label for="tblRemarks">Remarks</label>
                                            <textarea id="tblRemarks" name="tblRemarks" rows="2" cols="50" class="form-control"></textarea>

                                    </div>
                                </form>
                                <div class="box-footer pull-right" style="width: 350px; position: relative; top: -2px">
                                    <div class="bottomNav">
                                        <?php
                                        $ArrBomPages = ARR_BOM_PAGES;
                                        $VarCurrentPage = $this->uri->segment(2);
                                        $VarKi = array_search($VarCurrentPage,$ArrBomPages);
                                        $VarNextKey = $VarKi+1;
                                        ?>
                                        <div class="" style="width: 90px; float: left; font-size: 18px; text-align: justify">
                                            <a href="javascript:void(0)" style="color: grey; cursor: default; color: #bdbdbd">
                                                <span style="position: relative; bottom: 5px; top: 0"><b>PREV.</b></span>
                                                <i class="fa fa-arrow-left" style="font-size: 14px"></i>
                                            </a>
                                        </div>
                                        <div class="pageNoBox">
                                            <?php echo $VarKi ?>
                                        </div>
                                        <div class="" style="width: 108px; float: left; padding-left: 0; font-size: 18px; text-align: justify">
                                            <a href="<?php echo base_url('billofmaterials') .'/'.$ArrBomPages[$VarNextKey].'/'.$ArrCommonHeaderData['VarHashEnquiryId'] ?>" style="color: grey">
                                                <span style="position: relative; bottom: 5px; top: 0"><b>NEXT</b></span>
                                                <i class="fa fa-arrow-right" style="font-size: 14px"></i>
                                            </a>
                                        </div>
                                    </div>
                                    <div class="saveEditBtn">
                                        <?php
                                        if($ArrCommonHeaderData['ArrEnquiryDetails']['editaccess'] == 1) {
                                            ?>
                                            <button type="button" class="btn btn-info oeSaveEditBtn" onclick="return fnSaveTable()">Save</button>
                                            <?php
                                        }
                                        ?>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="alert alert-success alert-dismissable hide" id="divSuccessMsg"> </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <?php $this->load->view(CNFCOMPANY . 'template/templatefooter'); ?>
    <div class="control-sidebar-bg"></div>
</div>
<script src="<?php echo base_url(); ?>assets/js/jexcel4/jsuites.js"></script>
<script src="<?php echo base_url(); ?>assets/js/jexcel4/jexcel.js"></script>
<script type="text/javascript">
    var enquiryid = '<?php echo $VarEnquiryId ?>';
    var GlbParam = 'rFrom=1'; var GlbComboArr = []; var GlbComponentArr = []; var GlbColorArr = [];
    var newFourthData = []; itemizedQtyGrp = []; var currenttbldata = []; unsaved = false;
    var GlbPono = '<?php echo @$ArrPonumbers ?>';
    var GlbSizeSpecCode = '<?php echo @$ArrSizeSpecCode ?>';
    var UnitofMeasure = '<?php echo @$ArrUnitMeasure ?>';
    var GarmentSizes = []; var separators = []; var uniqueForSpc = []; var uniques = [];
    var GlbBomArticle = '';
    MakePostRequest(base_path + 'billofmaterials/article_1', GlbParam + "&enqId=" + enquiryid, 'json', function (data) {
        separators = ['-', '/', ':'];
        console.log(data,'data');
        if(data.remarks != '') {
            document.getElementById("tblRemarks").innerText = data.remarks;
        }
        GlbBomArticle = data.jsonFromBomArticle;
        if(GlbBomArticle == '') {
            GlbBomArticle = [
                []
            ];
        }
        else {
            GlbBomArticle = JSON.parse(GlbBomArticle);
        }
        GarmentSizes = data.GlbGarmentSizes;
        if(data.jsonFromNewFourthTbl != '') {
            newFourthData = JSON.parse(data.jsonFromNewFourthTbl);
        }
        console.log(newFourthData,'newFourthData');
        //filter
        uniques = []; var itemsFound = {}, itemsFoundForSpc = {};
        for (var ii = 0, l = newFourthData.length; ii < l; ii++) {
            var stringified5Tbl = JSON.stringify(newFourthData[ii]);
            if (itemsFound[stringified5Tbl]) {
                continue;
            }
            uniques[newFourthData[ii][0] + "||" + newFourthData[ii][1]] = newFourthData[ii][2];
            itemsFound[stringified5Tbl] = true;
        }
        console.log(uniques,'uniques');
        for (var j = 0; j < newFourthData.length; j++) {
            var stringified5Tbl = JSON.stringify(newFourthData[j]);
            if (itemsFoundForSpc[stringified5Tbl]) {
                continue;
            }
            uniqueForSpc[newFourthData[j][0] + "||" + newFourthData[j][1] + "||" + newFourthData[j][2] + "||" + newFourthData[j][4]] = newFourthData[j][5];
            itemsFoundForSpc[stringified5Tbl] = true;
        }
        for (let j = 0; j < newFourthData.length; j++) {
            console.log(newFourthData[j].length,'newFourthData[j]');
            let lastCol = newFourthData[j].length - 1;
            GlbComboArr.push(newFourthData[j][0]);
            GlbComponentArr.push(newFourthData[j][1]);
            GlbColorArr.push(newFourthData[j][2]);
            if (newFourthData[j][0] != "" && newFourthData[j][1] != "" && newFourthData[j][2] != "" && newFourthData[j][4] != "" &&
                newFourthData[j][5] != "" && newFourthData[j][lastCol] != "") {
                console.log(lastCol,'lastCol');
                console.log(newFourthData[j][lastCol],'newFourthData[j][lastCol]');
                itemizedQtyGrp[newFourthData[j][0] + "#" + newFourthData[j][1] + "#" + newFourthData[j][2] + "#" + newFourthData[j][4] + "#" +
                newFourthData[j][5]] = newFourthData[j][lastCol];
            }
        }
        console.log(itemizedQtyGrp,'itemizedQtyGrp');
        if(data.jsonFromEleventh) {
            currenttbldata = JSON.parse(data.jsonFromEleventh);
        }
        else {
            currenttbldata = [[]];
        }
        console.log(currenttbldata,'currenttbldata');

    });
    var GlbBom = '<?php echo @$ArrBom ?>';
    if(GlbBom == '') {
        GlbBom = [];
    }
    else {
        GlbBom = JSON.parse(GlbBom);
    }

    GlbComboArr = getUnique(GlbComboArr);
    GlbComponentArr = getUnique(GlbComponentArr);
    GlbColorArr = getUnique(GlbColorArr);

    console.log(GlbComboArr,'GlbComboArr');
    console.log(GlbComponentArr,'GlbComponentArr');
    console.log(GlbColorArr,'GlbColorArr');
    console.log(newFourthData,'newFourthData');

    colorDropdowncommon = function (instance, cell, c, r, source) {
        var firstvalue = instance.jexcel.getValueFromCoords(c - 2, r);
        var second = instance.jexcel.getValueFromCoords(c - 1, r);
        var keys = firstvalue + "||" + second;
        var splitcol = uniques[keys];
        if(splitcol) {
            return [splitcol];
        }
        else {
            return [];
        }
    };

    sizeSpecCodeFilter = function (instance, cell, c, r, source) {
        var comboData = instance.jexcel.getValueFromCoords(c - 4, r);
        var componentData = instance.jexcel.getValueFromCoords(c - 3, r);
        var colorData = instance.jexcel.getValueFromCoords(c - 2, r);
        var ponoData = instance.jexcel.getValueFromCoords(c - 1, r);
        var sizeSpecCodekeys = comboData + "||" + componentData + "||" + colorData + "||" + ponoData;
        console.log(sizeSpecCodekeys, 'sizeSpecCodekeys');
        console.log(uniqueForSpc,'uniqueForSpc');
        var splitcol2 = uniqueForSpc[sizeSpecCodekeys];
        if(splitcol2) {
            console.log(splitcol2,'splitcol2');
            return [splitcol2];
        }
    };
    console.log(GlbBomArticle,'GlbBomArticle');
    jexcel(document.getElementById('bom_article1'), {
        columns:[
            { type:'dropdown', title:'Combo', width:110, source : GlbComboArr },
            { type:'dropdown', title:'Component', width:110, source: GlbComponentArr },
            { type:'dropdown', title:'Colour', width:110, source: GlbColorArr, filter: colorDropdowncommon },
            { type:'dropdown', title:'P.O. No. / Enq. Ref. No.', width:90, source: getUnique(JSON.parse(GlbPono)) },
            { type:'dropdown', title:'Size Spec<br/>Code Fit', width:100,source: JSON.parse(GlbSizeSpecCode), filter: sizeSpecCodeFilter },
            { type:'dropdown', title:'Item Description / Blend (%) / Content / Material', width:158, source: GlbBom, wordWrap: true },
            { type:'dropdown', title:'Garment Size', width:60, source: GarmentSizes },
            { type:'text', title:'Item Code', width:80, wordWrap: true },
            { type:'text', title:'Item Colour Code', width:80, wordWrap: true },
            { type:'text', title:'Size / Dim.<br/>(L*W*H)', width:90, wordWrap: true },
            { type:'dropdown', title:'Unit of Measure', width:60,source: JSON.parse(UnitofMeasure), wordWrap: true },
            { type:'text', title:'Itemized Qty. (Pcs.)', width:90, wordWrap: true, readOnly: true, align:'right' },
            { type:'numeric', title:'BOM Intake', width:60, wordWrap: true },
            { type:'text', title:'Reqd.<br/>BOM Qty.', width:90, wordWrap: true, readOnly: true, align:'right' },
            { type:'dropdown', title:'Unit of Measure', width:70,source: JSON.parse(UnitofMeasure) }
        ],
        data: GlbBomArticle,
        onchange:function() {
            unsaved = true;
        },
        updateTable:function(instance, cell, col, row, val, label, cellName) {
            if (col == 0) {
                BOMQty = 0;
            }
            if (col == 0) zero = $(cell).text();
            if (col == 1) one = jsTrim($(cell).text());
            if (col == 2) two = jsTrim($(cell).text());
            if (col == 3) three = $(cell).text();
            if (col == 4) {
                let four = $(cell).text();
                joined = zero + "#" + one + "#" + two + "#" + three + "#" + four;
            }
            if (col == 11) {
                console.log(itemizedQtyGrp,'itemizedQtyGrp');
                $(cell).html(itemizedQtyGrp[joined]);
                instance.jexcel.options.data[row][col] = itemizedQtyGrp[joined];
                BOMQty = Number(itemizedQtyGrp[joined]);
            }

            if (col == 12) {
                BOMIntake = Number($(cell).text());
                $(cell).text(BOMIntake.toFixed(3));
                instance.jexcel.options.data[row][col] = BOMIntake.toFixed(3);
            }
            if (col == 13) {
                if (BOMQty != '' && BOMIntake != '') {
                    //console.log(BOMQty,'BOMQty');
                    //console.log(BOMIntake,'BOMIntake');
                    let multiplyRes = BOMQty * BOMIntake;
                    let ans = parseInt(multiplyRes);
                    $(cell).text(ans.toFixed(3));
                    instance.jexcel.options.data[row][col] = ans.toFixed(3);
                } else {
                    $(cell).text(0);
                }
            }
        }
    });

    function fnSaveTable() {
        let data = $("#bom_article1").jexcel('getData');
        console.log(data,'data');
        let tblRemarks = $("#tblRemarks").val();
        MakeAsynPostRequest(base_path + 'billofmaterials/saveBomArticle', GlbParam + "&d=" + JSON.stringify(data) +
            "&enqId=" + enquiryid + "&aid=1&e="+encodeURIComponent(tblRemarks), 'json', fnSaveTableRes);
    }

    function fnSaveTableRes(data) {
        if (data != '') {
            if (data.errCode === 1) {
                unsaved = false;
                $("#divSuccessMsg").removeClass('hide');
                $('#divSuccessMsg').text(cmnSuccessMsg);
            }
        }
    }
    function unloadPage() {
        if(unsaved){
            return "You have unsaved changes on this page. Do you want to leave this page and discard your changes or stay on this page?";
        }
    }
    window.onbeforeunload = unloadPage;
</script>
<?php $this->load->view(CNFCOMPANY . 'template/pagefooter'); ?>