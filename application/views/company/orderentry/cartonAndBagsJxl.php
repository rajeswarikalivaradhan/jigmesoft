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

                                    <div class="col-md-12 pd0 no-padding">
                                        <?php
                                        $ArrPcsSet = unserialize(ARRPCSSET);
                                        $VarPcsOrSet = $ArrPcsSet[$ArrCommonHeaderData['ArrEnquiryDetails']['pcsorset']];
                                        $this->load->view(CNFCOMPANY."orderentry/orderentrycommondetails");
                                        ?>
                                        <div class="" style="background-color: rgb(210 253 249); padding-top: 10px; padding-left: 10px">
                                            <strong>CHOOSE MASTER BAG AND CARTON ASSORTMENT TYPE</strong>
                                        </div>
                                    </div>
                                <form class="form-horizontal" name="frmBasicInfo" id="frmOrderProcess" method="post">
                                    <div class="col-sm-12" style="padding: 5px !important">
                                        <div id="poNoTabTypeJxl"></div>
                                        <div class="" style="margin-left: 495px; margin-bottom: 10px">
                                            <button type="submit" class="btn btn-info" id="saveBtnSubmit">Save</button>
                                        </div>


                                        <div class="" style="background-color: rgb(210 253 249); padding-top: 10px; padding-left: 10px">
                                            <strong>P.O. No : </strong>
                                            <?php echo @$initialJxl['pono']; ?>
                                            <strong>Assortment Type : </strong>
                                            <?php
                                            $ArrTabType = BAGS_TYPE;
                                            $VarTableType = '';
                                            $VarTableTypeId = '';
                                            if(!empty($initialJxl['table_type_id'])) {
                                                $VarTableTypeId = $initialJxl['table_type_id'];
                                                $VarTableType = $ArrTabType[$VarTableTypeId];
                                            }
                                            echo $VarTableType;
                                            ?>
                                        </div>
                                    </div>

                                    <div class="col-sm-12" style="padding: 0 5px 10px 5px !important">
                                        <div id="jxl_<?php echo @$initialJxl['id']; ?>" class="jxl" data-tid="24"></div>
                                    </div>

<br/>
                                    <div class="col-sm-12" style="padding: 5px !important">
                                        <div id="addHere_<?php echo @$initialJxl['id']; ?>"></div>
                                    </div>

                                    <?php
                                    if($VarTableTypeId == 1 || $VarTableTypeId == 6 || $VarTableTypeId == 7 || $VarTableTypeId == 8) {
                                        ?>
                                        <div class="" style="margin-left: 5px">
                                            <button type="button" id="<?php echo @$initialJxl['id']; ?>" class="btn btn-info jxlAddBtn" onclick="addExtra(this.id)">
                                                Add
                                            </button>
                                        </div>

                                        <?php
                                    }
                                    ?>
                                </form>
                                <div class="col-md-12" style="padding: 5px">
                                    <?php $this->load->view(CNFCOMPANY . "orderentry/footerNavSave"); ?>
                                </div>
                                <div class="col-md-12">
                                    <div class="alert alert-success alert-dismissable hide" id="divSuccessBasicInfoMsg"> </div>
                                    <div class="alert alert-danger alert-dismissable hide" id="ErrOrderEntry"> </div>
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
    var enquiryId = '<?php echo @$VarEnquiryId ?>';
    var GlbParam = 'rfrom=1';
    var hashEnquiryId = '<?php echo $VarHashEnquiryId ?>';
    var GlbPoNumbers = '<?php echo @$ArrPoNumbers ?>';
    var ArrSizes = '<?php echo $ArrFinalSizes ?>';
    addTblCount = 0;
    GlbArrComboColor = ''; tableTypeId = ''; priId = '';
    ArrSizes = JSON.parse(ArrSizes);
    mBagAndTableType = '';
    mainJxl = [[]]; extraJxlData = '';
    poNoTabTypeJxlData = [];
    let PoNumbers = ['PO - 001','PO - 002','PO - 003','PO - 004','PO - 005','PO - 006','PO - 007','PO - 008'];
    console.log(PoNumbers,'PoNumbers');
    for(let ii = 0; ii < PoNumbers.length; ii++) {
        let poNum = PoNumbers[ii];
        poNoTabTypeJxlData.push([poNum]);
    }
    // A custom method to SUM all the cells in the current column
    SUMCOL = function(instance, columnId) {
        var total = 0;
        for (var j = 0; j < instance.options.data.length; j++) {
            if (Number(instance.records[j][columnId-1].innerHTML)) {
                total += Number(instance.records[j][columnId-1].innerHTML);
            }
        }
        return total;
    };
    const tableTypes = [
        {"id":"1","name":"Solid Colour Solid Size - MPB & CAR"},{"id":"2","name":"Solid Color Solid Size - CAR"},
        {"id":"3","name":"Solid Colour Assorted Size - MPB & CAR"},{"id":"4","name":"Solid Color Assorted Size - CAR"},
        {"id":"5","name":"Assorted Colour Solid Size - MPB & CAR"},{"id":"6","name":"Assorted Colour Solid Size - CAR"},
        {"id":"7","name":"Assorted Colour Assorted Size - MPB & CAR"},{"id":"8","name":"Assorted Color Assorted Size - CAR"}
    ];
    /*console.log(tableTypes,'tableTypes');
    console.log(tableTypes.length,'tableTypes LEN');*/


    var thisUrl = $(location).attr('href');
    //console.log(thisUrl,'thisUrl');
    lastURLSegment = thisUrl.substr(thisUrl.lastIndexOf('/')+1);
    //console.log(lastURLSegment,'lastURLSegment');
    let param = GlbParam+"&enqId="+enquiryId+"&lastURLSegment="+lastURLSegment;
    MakePostRequest(base_path+"orderentryvtwo/cartonAndBags",param,'json',function (data) {
        if (data.ArrComboColor != "") {
            GlbArrComboColor = data.ArrComboColor;
            GlbArrComboColor = getUnique(GlbArrComboColor);
            console.log(GlbArrComboColor, 'GlbArrComboColor');
        }
        if (data.mBagAndTableType != '') {
            mBagAndTableType = data.mBagAndTableType;
        }
        if (data.initialJxl != "") {
            let initialJxl = data.initialJxl;
            priId = initialJxl.id;
            tableTypeId = initialJxl.table_type_id;
        }
        if(lastURLSegment > 0) {
            //EDIT
            if (data.ArrJxlResult != "") {
                ArrJxlResult = data.ArrJxlResult;
                addTblCount  = ArrJxlResult['extra_tables'];
                console.log(addTblCount,'addTblCount IN RESPONSE');
                console.log(typeof addTblCount,'typeof addTblCount');
                //console.log(ArrJxlResult['jsondatagrid'], 'ArrJxlResult['jsondatagrid']');
                console.log(tableTypeId,'tableTypeId');
                console.log(typeof tableTypeId,'typeof tableTypeId');
                if (ArrJxlResult['jsondatagrid'] != "")
                    mainJxl = JSON.parse(ArrJxlResult['jsondatagrid']);
                if (ArrJxlResult['jsondatagridextra'] != "")
                    extraJxlData = JSON.parse(ArrJxlResult['jsondatagridextra']);
                columnsForJxl = [
                    {title: 'Combo / Color', type: 'dropdown', source: GlbArrComboColor, width: 150}
                ];
                // if (isOdd(tableTypeId)) {
                    if(tableTypeId == 1) {
                        jxlSizeColumnCount = 1;
                        for (let i = 0; i < ArrSizes.length; i++) {
                            columnsForJxl.push({title: ArrSizes[i], type: 'numeric', width: 80});
                            jxlSizeColumnCount++;
                        }

                        jxlTableFooter = [];
                        for (let ii = 0; ii < jxlSizeColumnCount; ii++) {
                            jxlTableFooter.push('');
                        }
                        columnsForJxl.push(
                            {title: 'No. of Pcs. / Master Bag', readOnly: true, width: 100},
                            {title: 'No. of Master Bag / Carton', width: 100},
                            {title: 'No. of Pcs. / Carton', readOnly: true, width: 90}
                        );
                        jxlTableFooter.push('Total', '=SUMCOL(TABLE(), COLUMN())','=SUMCOL(TABLE(), COLUMN())');
                    }
                    else if(tableTypeId == 2) {
                        jxlSizeColumnCount = 1;
                        for (let i = 0; i < ArrSizes.length; i++) {
                            columnsForJxl.push({title: ArrSizes[i], type: 'numeric', width: 80});
                            jxlSizeColumnCount++;
                        }

                        jxlTableFooter = [];
                        for (let ii = 0; ii < jxlSizeColumnCount; ii++) {
                            jxlTableFooter.push('');
                        }
                        columnsForJxl.push(
                            {title: 'No. of Pcs. / Master Bag', readOnly: true, width: 100},
                            {title: 'No. of Master Bag / Carton', width: 100},
                            {title: 'No. of Pcs. / Carton', readOnly: true, width: 90}
                        );

                    }
                    else if(tableTypeId == 3) {
                        jxlSizeColumnCount = 1;
                        for (let i = 0; i < ArrSizes.length; i++) {
                            columnsForJxl.push({title: ArrSizes[i], type: 'numeric', width: 80});
                            jxlSizeColumnCount++;
                        }

                        jxlTableFooter = [];
                        for (let ii = 0; ii < jxlSizeColumnCount; ii++) {
                            jxlTableFooter.push('');
                        }
                        columnsForJxl.push(
                            {title: 'No. of Pcs. / Master Bag', readOnly: true, width: 100},
                            {title: 'No. of Master Bag / Carton', width: 100},
                            {title: 'No. of Pcs. / Carton', readOnly: true, width: 90}
                        );
                    }
                    else if(tableTypeId == 4) {
                        jxlSizeColumnCount = 1;
                        for (let i = 0; i < ArrSizes.length; i++) {
                            columnsForJxl.push({title: ArrSizes[i], type: 'numeric', width: 80});
                            jxlSizeColumnCount++;
                        }

                        jxlTableFooter = [];
                        for (let ii = 0; ii < jxlSizeColumnCount; ii++) {
                            jxlTableFooter.push('');
                        }
                        columnsForJxl.push(
                            {title: 'No. of Pcs. / Master Bag', readOnly: true, width: 100},
                            {title: 'No. of Master Bag / Carton', width: 100},
                            {title: 'No. of Pcs. / Carton', readOnly: true, width: 90}
                        );
                    }
                    else if(tableTypeId == 5) {
                        jxlSizeColumnCount = 0;
                        console.log(columnsForJxl,'columnsForJxl 5th');
                        for (let i = 0; i < ArrSizes.length; i++) {
                            console.log(ArrSizes[i],'ArrSizes[i]');
                            columnsForJxl.push({title: ArrSizes[i], type: 'numeric', width: 80});
                            jxlSizeColumnCount++;
                        }
                        console.log(columnsForJxl,'columnsForJxl 5th AFTER');
                        jxlTableFooter = [];
                        for (let ii = 0; ii < jxlSizeColumnCount; ii++) {
                            jxlTableFooter.push('');
                        }
                        console.log(jxlTableFooter,'jxlTableFooter 5th');
                        columnsForJxl.push(
                            {title: 'No. of Pcs. / Master Bag', readOnly: true, width: 100},
                            {title: 'No. of Master Bag / Carton', width: 100, readOnly: true},
                            {title: 'No. of Pcs. / Carton', readOnly: true, width: 90}
                        );
                        jxlTableFooter.push('Total','=SUMCOL(TABLE(), COLUMN())');
                        console.log(columnsForJxl,'columnsForJxlcolumnsForJxlcolumnsForJxlcolumnsForJxlcolumnsForJxlcolumnsForJxlcolumnsForJxlcolumnsForJxlcolumnsForJxl');
                    }
                    else if(tableTypeId == 6) {
                        jxlSizeColumnCount = 1;
                        for (let i = 0; i < ArrSizes.length; i++) {
                            columnsForJxl.push({title: ArrSizes[i], type: 'numeric', width: 80});
                            jxlSizeColumnCount++;
                        }
                        jxlTableFooter = [];
                        for (let ii = 0; ii < jxlSizeColumnCount; ii++) {
                            jxlTableFooter.push('');
                        }
                        columnsForJxl.push(
                            {title: 'No. of Pcs. / Carton', readOnly: true, width: 90}
                        );
                        jxlTableFooter.push('');
                    }
                    else if(tableTypeId == 7) {
                        jxlSizeColumnCount = 1;
                        for (let i = 0; i < ArrSizes.length; i++) {
                            columnsForJxl.push({title: ArrSizes[i], type: 'numeric', width: 80});
                            jxlSizeColumnCount++;
                        }
                        jxlTableFooter = [];
                        for (let ii = 0; ii < jxlSizeColumnCount; ii++) {
                            jxlTableFooter.push('');
                        }
                        columnsForJxl.push(
                            {title: 'No. of Pcs. / Master Bag', readOnly: true, width: 100},
                            {title: 'No. of Master Bag / Carton', width: 100},
                            {title: 'No. of Pcs. / Carton', readOnly: true, width: 90}
                        );
                        jxlTableFooter.push('Total','=SUMCOL(TABLE(), COLUMN())','=SUMCOL(TABLE(), COLUMN())');
                    }
                    else if(tableTypeId == 8) {
                        jxlSizeColumnCount = 1;
                        for (let i = 0; i < ArrSizes.length; i++) {
                            columnsForJxl.push({title: ArrSizes[i], type: 'numeric', width: 80});
                            jxlSizeColumnCount++;
                        }
                        jxlTableFooter = [];
                        for (let ii = 0; ii < jxlSizeColumnCount; ii++) {
                            jxlTableFooter.push('');
                        }
                        columnsForJxl.push(
                            {title: 'No. of Pcs. / Carton', readOnly: true, width: 90}
                        );
                        jxlTableFooter.push('Total','=SUMCOL(TABLE(), COLUMN())');
                    }

                // }
                // else {
                    /*columnsForJxl.push(
                        {title: 'No. of Pcs. / Carton', readOnly: true, width: 90}
                    );*/
                // }

                console.log(columnsForJxl, 'columnsForJxl');
                console.log(tableTypeId, 'tableTypeId');
                loop = "1";
                for (let jxlProp in extraJxlData) {
                    console.log(extraJxlData[jxlProp], 'ssd');
                    loopData = extraJxlData[jxlProp];
                    console.log(priId, 'priIdpriIdpriId');
                    $("#addHere_" + priId).append('<div id="jxlExtra_' + loop + '_priId_' + priId + '" class="jxl">');
                    jexcel(document.getElementById("jxlExtra_" + loop + "_priId_" + priId), {
                        columns: columnsForJxl,
                        data: loopData,
                        updateTable: function (instance, cell, col, row, val, label, cellName) {
                            if (col == 0) {
                                noOfPcsPerMasterBag = 0;
                            }
                            if (col >= 1 && col < jxlSizeColumnCount) {
                                console.log(val,'val IN addHere');
                                if(val === '') val = 0;
                                noOfPcsPerMasterBag = noOfPcsPerMasterBag + parseInt(val);
                                //console.log(noOfPcsPerMasterBag,'noOfPcsPerMasterBag');
                            }
                            if (col === jxlSizeColumnCount) {
                                $(cell).text(noOfPcsPerMasterBag);
                                instance.jexcel.options.data[row][col] = noOfPcsPerMasterBag;
                            }
                            if (col === jxlSizeColumnCount + 1) {
                                noOfMasterBagperCarton = val;
                            }
                            if (col === jxlSizeColumnCount + 2) {
                                let res = noOfPcsPerMasterBag * Number(noOfMasterBagperCarton);
                                $(cell).text(res);
                                instance.jexcel.options.data[row][col] = res;
                            }
                        },
                        allowInsertColumn: false,
                        footers : [jxlTableFooter]
                    });
                    $("#addHere_"+priId).append('<button type="button" id="' + loop + '_priId_' + priId + '" style="margin-bottom: 30px" class="btn btn-info" onclick="removeExtra(this.id)">Remove</button>');
                    loop++;
                }
            }
            /*columnsForJxl = [
                {title: 'Combo / Color', type: 'dropdown', source: GlbArrComboColor, width: 150}
            ];
            //jxlSizeColumnCount = 1;
            for (let i = 0; i < ArrSizes.length; i++) {
                columnsForJxl.push({title: ArrSizes[i], type: 'numeric', width: 80});
                //jxlSizeColumnCount++;
            }
            console.log(isOdd(tableTypeId), 'isOdd(tableTypeId)');
            if (isOdd(tableTypeId)) {
                columnsForJxl.push(
                    {title: 'No. of Pcs. / Master Bag', readOnly: true, width: 100},
                    {title: 'No. of Master Bag / Carton', width: 100},
                    {title: 'No. of Pcs. / Carton', readOnly: true, width: 90}
                );
            }
            else {
                columnsForJxl.push(
                    {title: 'No. of Pcs. / Carton', readOnly: true, width: 90}
                );
            }*/
            /*for (let ii = 0; ii < jxlSizeColumnCount; ii++) {
                jxlTableFooter.push('');
            }
            console.log(jxlTableFooter,'jxlTableFooter TEST');
            jxlTableFooter.push('Total', '=SUMCOL(TABLE(), COLUMN())','=SUMCOL(TABLE(), COLUMN())');*/
            console.log(columnsForJxl, 'columnsForJxl');
            console.log(tableTypeId, 'tableTypeId');
            jexcel(document.getElementById("jxl_" + priId), {
                columns: columnsForJxl,
                data: mainJxl,
                footers: [jxlTableFooter],
                updateTable: function (instance, cell, col, row, val, label, cellName) {
                    if (col == 0) {
                        noOfPcsPerMasterBag = 0;
                    }
                    if (col >= 1 && col < jxlSizeColumnCount) {
                        if(val === '') val = 0;
                        noOfPcsPerMasterBag = noOfPcsPerMasterBag + parseInt(val);
                        console.log(noOfPcsPerMasterBag, 'noOfPcsPerMasterBag');
                    }
                    if (col === jxlSizeColumnCount) {
                        $(cell).text(noOfPcsPerMasterBag);
                        instance.jexcel.options.data[row][col] = noOfPcsPerMasterBag;
                    }
                    if (col === jxlSizeColumnCount + 1) {
                        noOfMasterBagperCarton = val;
                    }
                    if (col === jxlSizeColumnCount + 2) {
                        let res = noOfPcsPerMasterBag * Number(noOfMasterBagperCarton);
                        $(cell).text(res);
                        instance.jexcel.options.data[row][col] = res;
                    }
                },
                allowInsertColumn: false
            });
        }
    });

    //EDIT
    if(mBagAndTableType != '') {
        poNoTabTypeJxlData = [];
        console.log(mBagAndTableType,'mBagAndTableType');
        for(let ii = 0; ii < mBagAndTableType.length; ii++) {
            console.log(typeof mBagAndTableType[ii].table_type_id,'mBagAndTableType[ii].table_type_id');
            if(mBagAndTableType[ii].table_type_id != 0)
                var poNoUrl = '<a href="' + base_path + "orderentryvtwo/cartonAndBags/" + hashEnquiryId+"/"+mBagAndTableType[ii].id + '">' + mBagAndTableType[ii].pono + '</a>';
            else
                var poNoUrl = mBagAndTableType[ii].pono;
            poNoTabTypeJxlData.push([poNoUrl,mBagAndTableType[ii].table_type_id,mBagAndTableType[ii].id]);
        }
        //poNoTabTypeJxlData = mBagAndTableType;
        console.log(poNoTabTypeJxlData,'poNoTabTypeJxlData FROM DB');
    }
    console.log(poNoTabTypeJxlData,'poNoTabTypeJxlData');
    jxlData = jexcel(document.getElementById("poNoTabTypeJxl"), {
        data: poNoTabTypeJxlData,
        //data: [['<a href="https://www.google.com/">TESTLINK</a>']],
        columns:[
            {title:'P.O. No', type:'html', name:"poNoId", width:200 },
            {title: 'Assortment Type', type: 'dropdown', name: "tabType", source: tableTypes, width: 300},
            {type: "hidden", name:"hdn"}
        ]
    });

    $(document).ready(function() {
        $("#saveBtnSubmit").click(function(e) {
            const isHTML = (str) => !(str || '')
            // replace html tag with content
                .replace(/<([^>]+?)([^>]*?)>(.*?)<\/\1>/ig, '')
                // remove remaining self closing tags
                .replace(/(<([^>]+)>)/ig, '')
                // remove extra space at start and end
                .trim();
            e.preventDefault();
            let poNoTypeJxl = jxlData.getJson();
            console.log(poNoTypeJxl,'poNoTypeJxl');
            let iniData = []; let ArrPoNo = []; let ArrTabType = [];
            for(let prop in poNoTypeJxl) {
                //console.log(prop,'prop');
                let poNo = poNoTypeJxl[prop].poNoId;
                let tableType = poNoTypeJxl[prop].tabType;
                console.log(poNo,'poNo');
                let id = 0;
                let key = poNoTypeJxl[prop].hdn;
                if(isHTML(poNo)) {
                    let anchor = document.createElement('anchor');
                    anchor.innerHTML = poNo;
                    let hrefVal = poNo.match(/href="([^"]*)/)[1];
                    console.log(hrefVal,'hrefVal');
                    id = hrefVal.substr(hrefVal.lastIndexOf('/')+1);
                    console.log(id,'id');
                    console.log(anchor.textContent,'P.O. No');
                    console.log(tableType,'tableType');
                    iniData.push([anchor.textContent,tableType,key]);
                    ArrPoNo.push(anchor.textContent);
                }
                else {
                    iniData.push([poNo,tableType,key]);
                    ArrPoNo.push(poNo);
                }
                ArrTabType.push(tableType);
                //alert(isHTML(poNo));
                //alert(poNo);
            }
            $.ajax({
                url: base_path+"orderentryvtwo/savePoNoAndTabType",
                dataType: "json",
                type: 'POST',
                data: "jxl="+JSON.stringify(iniData)+"&enqId="+enquiryId,
                success: function(data) {
                    if(data.errCode === 1)
                        location.reload();
                }
            });
        });
    });

    function fnSaveChanges() {
        console.log(priId,'priId');
        let mainJxl = $("#jxl_"+priId).jexcel('getData');
        console.log(mainJxl,'mainJxl');
        console.log(addTblCount,'addTblCount');
        ArrAdditionJxl = [];
        for(let ii = 1; ii <= addTblCount; ii++) {
            console.log("jxlExtra_"+ii+"_priId_"+priId);
            additionJxlData = $("#jxlExtra_"+ii+"_priId_"+priId).jexcel('getData');
            console.log(additionJxlData,'additionJxlData');
            ArrAdditionJxl.push(additionJxlData);
        }
        console.log(ArrAdditionJxl,'ArrAdditionJxl');
        let param = "tableTypeId="+tableTypeId+"&priId="+priId+"&d="+JSON.stringify(mainJxl)+
            "&dataExtra="+JSON.stringify(ArrAdditionJxl)+"&extra_tables="+addTblCount;
        MakePostRequest(base_path+"orderentryvtwo/saveBagAndCartons",param,"json",function (data) {
            console.log(data,'data');
            $("#divSuccessBasicInfoMsg").removeClass('hide');
            $("#divSuccessBasicInfoMsg").text("Saved Successfully");
        });

    }

    function addExtra(thisId) {
        addTblCount++;
        console.log(thisId,'thisId');
        console.log(tableTypes,'tableTypes');
        console.log(addTblCount,'addTblCount IN ADD EXTRA BTN');
        let tblId = addTblCount+"_priId_"+priId;
        $("#addHere_"+thisId).append('<div id="jxlExtra_'+tblId+'" class="jxl">');
        jexcel(document.getElementById("jxlExtra_"+tblId),{
            columns: columnsForJxl,
            data: [
                []
            ],
            footers: [jxlTableFooter],
            updateTable:function(instance, cell, col, row, val, label, cellName) {
                if(col == 0) {
                    noOfPcsPerMasterBag = 0;
                }
                if (col >= 1 && col < jxlSizeColumnCount) {
                    if(val === '') val = 0;
                    console.log(val,'val ADD JXL CARTON BAG');
                    noOfPcsPerMasterBag = noOfPcsPerMasterBag + parseInt(val);
                    //console.log(noOfPcsPerMasterBag,'noOfPcsPerMasterBag');
                }
                if(col === jxlSizeColumnCount) {
                    $(cell).text(noOfPcsPerMasterBag);
                    instance.jexcel.options.data[row][col] = noOfPcsPerMasterBag;
                }
                if(col === jxlSizeColumnCount + 1) {
                    noOfMasterBagperCarton = val;
                }
                if(col === jxlSizeColumnCount + 2) {
                    let res = noOfPcsPerMasterBag * Number(noOfMasterBagperCarton);
                    $(cell).text(res);
                    instance.jexcel.options.data[row][col] = res;
                }
            },
            allowInsertColumn: false
        });
        $("#addHere_"+thisId).append('<button type="button" id="'+tblId+'" style="margin-bottom: 30px" class="btn btn-info" onclick="removeExtra(this.id)">Remove</button>');
    }

    function removeExtra(thisId) {
        console.log(thisId,'thisId');
        jexcel.destroy(document.getElementById("jxlExtra_"+thisId));
        $("#"+thisId).remove();
        console.log(addTblCount,'addTblCount BEFORE');
        addTblCount--;
        console.log(addTblCount,'addTblCount AFTER');
    }
</script>
<?php $this->load->view(CNFCOMPANY . 'template/pagefooter'); ?>