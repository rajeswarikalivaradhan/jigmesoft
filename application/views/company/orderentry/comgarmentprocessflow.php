<?php $this->load->view(CNFCOMPANY . 'template/pageheader'); ?>
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/jexcel4/jsuites.css"/>
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/jexcel4/jexcel.css"/>
<body class="sidebar-mini skin-black wysihtml5-supported sidebar-collapse">
<div class="wrapper" >
    <?php $this->load->view(CNFCOMPANY . 'template/templateheader'); ?>
    <aside class="main-sidebar">
        <?php $this->load->view(CNFCOMPANY . 'template/templateleftmenu'); ?>
    </aside>
    <div class="content-wrapper order-entry" >
        <div class="col-md-12">
            <section class="content-header">
                <h1 class="firstHeading">Order Entry</h1>
            </section>
        </div>
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div id="DivContBasicInfo">
                        <div class="box box-info">
                            <div class="box-header with-border">
                            </div>
                            <div class="box-body" style="padding: 0px;">
                                <form class="form-horizontal" name="frmBasicInfo" id="frmOrderProcess" method="post">
                                    <div class="col-md-12 pd0 no-padding">
                                        <?php $this->load->view(CNFCOMPANY."orderentry/orderentrycommondetails"); ?>
                                        <div style="background-color: rgb(210 253 249); padding-top: 10px; padding-left: 10px">
                                            <strong>COMPLETE GARMENT PROCESS FLOW: Cutting to Finishing</strong>
                                        </div>
                                    </div>

                                    <div class="col-md-12" style="padding: 5px !important">
                                        <div id="orderentryvtwocomgarmentprocess"></div>
                                    </div>

                                    <div class="col-md-12" style="padding: 5px !important;">
                                        <div class="form-group" style="margin-bottom: 0">
                                            <label class="">Remarks</label>
                                            <textarea id="frmBasicRemarks" title="Remarks" class="form-control"></textarea>
                                        </div>
                                    </div>
                                </form>
                                <div class="col-md-12" style="padding: 5px">
                                    <?php $this->load->view(CNFCOMPANY . "orderentry/footerNavSave"); ?>
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
    var enquiryid = '<?php echo @$VarEnquiryId ?>';
    var GlbParam = 'rfrom=1', unsaved = false;
    var GlbProcessFlow = '<?php echo $ArrProcessFlow ?>';
    var FifthTblData = []; var GlbComboArr = []; var GlbComponentArr = [];
    var GlbColorArr = []; var GlbSizeSpecCode = []; var poNumberFilterGroup = {};
    function fnPopulateValueArray(ArrName, KeyValue, InsertVal) {
        if(InsertVal) {
            if (jQuery.inArray(KeyValue, ArrName)) {
                if(InsertVal !== "") {
                    ArrName[KeyValue] = ArrName[KeyValue]+"|#|"+InsertVal;
                }
            }
            return ArrName;
        }
    }
    MakeAsynPostRequest(base_path+'orderentryvtwo/comgarmentprocessflow',GlbParam+"&enqid="+enquiryid,'json',getFourteenTblDataRes);
    function getFourteenTblDataRes(data) {
        console.log(data, 'data');
        if(data.remarks !== "") {
            document.getElementById("frmBasicRemarks").innerText = data.remarks;
        }
        if(data.jsonCuttingRatioData != '') {
            FifthTblData = JSON.parse(data.jsonCuttingRatioData);
        }
        console.log(FifthTblData,'FifthTblData');
        console.log(data.jsonCuttingRatioData,'jsonCuttingRatioData');
        var poNumbers = getUnique(data.ArrPoNumbers);
        console.log(poNumbers,'poNumbers');
        var uniques = [], uniqueForSpc = [], itemsFound = {}, itemsFoundForSpc = {};
        for (let ii = 0; ii < FifthTblData.length; ii++) {
            var combo = FifthTblData[ii][0];
            var component = FifthTblData[ii][1];
            var color = FifthTblData[ii][2];
            var spc = FifthTblData[ii][4];
            //FifthTblData[ii][2]
            GlbComboArr.push(combo);
            GlbComponentArr.push(component);
            GlbColorArr.push(color);
            GlbSizeSpecCode.push(spc);
        }
        GlbComboArr = getUnique(GlbComboArr);
        GlbComponentArr = getUnique(GlbComponentArr);
        GlbColorArr = getUnique(GlbColorArr);
        GlbSizeSpecCode = getUnique(GlbSizeSpecCode);
        console.log(GlbComboArr,'GlbComboArr');
        console.log(GlbComponentArr,'GlbComponentArr');
        console.log(GlbColorArr,'GlbColorArr');
        console.log(GlbSizeSpecCode,'GlbSizeSpecCode');
        for (let ii = 0; ii < FifthTblData.length; ii++) {
            var stringified5Tbl = JSON.stringify(FifthTblData[ii]);
            if (itemsFound[stringified5Tbl]) {
                continue;
            }
            uniques[FifthTblData[ii][0] + "||" + FifthTblData[ii][1]] = FifthTblData[ii][2];
            itemsFound[stringified5Tbl] = true;
        }
        console.log(uniques,'uniques');
        for (var j = 0; j < FifthTblData.length; j++) {
            var stringified5Tbl = JSON.stringify(FifthTblData[j]);
            if (itemsFoundForSpc[stringified5Tbl]) {
                continue;
            }
            uniqueForSpc[FifthTblData[j][0] + "||" + FifthTblData[j][1] + "||" + FifthTblData[j][2] + "||" + FifthTblData[j][3]] = FifthTblData[j][4];
            itemsFoundForSpc[stringified5Tbl] = true;
            //poNumberFilterGroup[FifthTblData[j][0] + "||" + FifthTblData[j][1] + "||" + FifthTblData[j][2]] = FifthTblData[j][3];
            poNumberFilterGroup = fnPopulateValueArray(poNumberFilterGroup,FifthTblData[j][0] + "||" + FifthTblData[j][1] + "||" + FifthTblData[j][2],FifthTblData[j][3]);
        }
        console.log(uniqueForSpc,'uniqueForSpc');
        garmentProcessFlowColorFilter = function (instance, cell, c, r, source) {
            let componentData = instance.jexcel.getValueFromCoords(c - 1, r);
            let comboData = instance.jexcel.getValueFromCoords(c - 2, r);
            let keys = comboData + "||" + componentData;
            console.log(keys,'keys');
            console.log(uniques[keys],'uniques[keys]');
            if(uniques[keys]) {
                let splitCol = uniques[keys];
                return [splitCol];
            }
            else {
                return [];
            }
            /*var splittedcol = splitcol.split('-');
            if(splittedcol.length >= 2) {
                var GlbColorArrFilterNew = [];
                for(var z = 0; z < splittedcol.length; z++) { GlbColorArrFilterNew.push(jsTrim(splittedcol[z])); }
                return GlbColorArrFilterNew;
            }
            else return [splitcol];*/

        };
        poNumberFilter = function(instance, cell, c, r, source) {
            console.log(source,'source');
            let cbo = instance.jexcel.getValueFromCoords(c - 3, r);
            let com = instance.jexcel.getValueFromCoords(c - 2, r);
            let col = instance.jexcel.getValueFromCoords(c - 1, r);
            let keys = cbo+"||"+com+"||"+col;
            console.log(poNumberFilterGroup,'poNumberFilterGroup');
            if(poNumberFilterGroup[keys]) {
                let filters = poNumberFilterGroup[keys].replace('undefined|#|','');
                console.log(filters,'filters');
                var ArrFilter = filters.split('|#|');
                console.log(ArrFilter,'ArrFilter');
            }
            if(ArrFilter) {
                return ArrFilter;
            }
            else {
                return [];
            }
        };

        var sizeSpecCodeFilter = function (instance, cell, c, r, source) {
            var colorData = instance.jexcel.getValueFromCoords(c - 2, r);
            var componentData = instance.jexcel.getValueFromCoords(c - 3, r);
            var comboData = instance.jexcel.getValueFromCoords(c - 4, r);
            var poNoData = instance.jexcel.getValueFromCoords(c - 1, r);

            var sizeSpecCodeKeys = comboData + "||" + componentData + "||" + colorData + "||" + poNoData;
            console.log(sizeSpecCodeKeys, 'sizeSpecCodeKeys');
            var filteredSpc = uniqueForSpc[sizeSpecCodeKeys];
            if(filteredSpc) {
                return [filteredSpc];
            }
            else {
                return [];
            }
        };

        if (data.allarr != "" && typeof data.allarr !== "undefined") {
            if (GlbProcessFlow != '') {
                GlbProcessFlow = JSON.parse(GlbProcessFlow);
            }
            console.log(GlbComboArr,'GlbComboArr in IF PART');
            jexcel(document.getElementById('orderentryvtwocomgarmentprocess'), {
                columns: [
                    {title: 'Combo', type: 'dropdown', source: GlbComboArr, width: 150},
                    {title: 'Component', type: 'dropdown', source: GlbComponentArr, width: 150},
                    {title: 'Colour', type: 'dropdown', source: GlbColorArr, width: 150, filter: garmentProcessFlowColorFilter},
                    {title: 'P.O. No. / Enq. Ref. No.', type: 'dropdown', source: poNumbers, width: 250, filter: poNumberFilter},
                    {title: 'Size Spec Code / Fit', type: 'dropdown', source: GlbSizeSpecCode, width: 250, filter: sizeSpecCodeFilter},
                    {title: 'Process Flow Description', type: 'dropdown', source: GlbProcessFlow, width: 408}
                ],
                data: data.allarr,
                onchange: function () {
                    unsaved = true;
                }
            });
        }
        else {
            if (GlbProcessFlow != '') {
                GlbProcessFlow = JSON.parse(GlbProcessFlow);
            }
            console.log(GlbComboArr,'GlbComboArr IN ELSE');
            console.log(color,'color');
            jexcel(document.getElementById('orderentryvtwocomgarmentprocess'), {
                columns: [
                    {title: 'Combo', type: 'dropdown', source: GlbComboArr, width: 150},
                    {title: 'Component', type: 'dropdown', source: GlbComponentArr, width: 150},
                    {title: 'Colour', type: 'dropdown', source: GlbColorArr, width: 150, filter: garmentProcessFlowColorFilter},
                    {title: 'P.O. No. / Enq. Ref. No.', type: 'dropdown', source: poNumbers, width: 250, filter: poNumberFilter},
                    {title: 'Size Spec Code / Fit', type: 'dropdown', source: GlbSizeSpecCode, width: 250, filter: sizeSpecCodeFilter},
                    {title: 'Process Flow Description', type: 'dropdown', source: GlbProcessFlow, width: 408}
                ],
                data: [
                    []
                ],
                onchange: function () {
                    unsaved = true;
                }
            });
        }
    }

    function fnSaveChanges() {
        unsaved = false;
        var data = JSON.stringify($("#orderentryvtwocomgarmentprocess").jexcel('getData'));
        let remarks = $("#frmBasicRemarks").val();
        MakeAsynPostRequest(base_path+'orderentryvtwo/savecomgarmentflow',GlbParam+"&d="+
            encodeURIComponent(data)+"&enqid="+enquiryid+"&e="+encodeURIComponent(remarks),'json',fnSaveTableRes);
    }

    function fnSaveTableRes(data) {
        if(data!='') {
            if(data.errcode==1) {
                unsaved = false;
                $("#divSuccessMsg").removeClass('hide');
                $("#divSuccessMsg").text("Saved Successfully");
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