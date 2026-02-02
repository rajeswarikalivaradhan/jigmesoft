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

    <div class="content-wrapper order-entry" >

        <div class="col-md-12">
            <section class="content-header">

                <h1 class="firstHeading">Order Entry</h1>

            </section>
        </div>

        <section class="content">
            <div class="dropdown">
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div id="DivContBasicInfo">
                        <div class="box box-info">
                            <div class="box-header with-border">
                            </div>
                            <div class="box-body table-responsive" style="padding: 0px;">
                                <form class="form-horizontal" name="frmBasicInfo" id="frmOrderProcess" method="post">
                                    <div class="col-md-12 pd0 no-padding">
                                        <?php
                                            $this->load->view(CNFCOMPANY . "orderentry/orderentrycommondetails");
                                        ?>

                                    <div style="background-color: rgb(210 253 249); padding-top: 10px; padding-left: 10px"><strong>P.O. WISE QTY. BREAK-UP</strong></div>

                                    </div>
                                    <div class="col-sm-12" style="padding: 5px !important">
                                        <div class="form-group">
                                            <div id="secondtbl"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-12" style="padding: 5px">
                                        <div class="form-group" style="margin-bottom: 0">
                                            <label for="tblRemarks">Remarks</label>
                                            <textarea id="tblRemarks" name="tblRemarks" rows="2" cols="50" class="form-control"><?php echo $VarRemarks ?></textarea>
                                        </div>
                                    </div>
                                </form>
                                <div class="col-md-12" style="padding: 5px">
                                    <div class="form-group">
                                        <div id="uploadBusinssImg" class="pdt10">
                                            <div class="ajax-upload-dragdrop" style="vertical-align:top;width:100%">
                                                <div class="ajax-file-upload"
                                                     style="position: relative; overflow: hidden; cursor: default;">
                                                    Upload
                                                    <form method="POST" enctype="multipart/form-data"
                                                          style="margin: 0px; padding: 0px;">
                                                        <input type="file" id="19" name="bimage[]" accept="*"
                                                               multiple=""
                                                               style="position: absolute; cursor: pointer; top: 0px; width: 100%; height: 100%; left: 0px; z-index: 100; opacity: 0;">
                                                    </form>
                                                </div>
                                                <span><b>Drag &amp; Drop Files</b></span>
                                                <div class="ajax-file-upload-container"></div>
                                            </div>
                                        </div>
                                        <ul style="list-style: none;">
                                            <?php
                                            $VarFdr = UPLOADS_SLASH . "orderentry" . DIRECTORY_SEPARATOR . $VarEnquiryId . DIRECTORY_SEPARATOR . "poNo" . DIRECTORY_SEPARATOR;
                                            $ArrDwnExtensions = DWN_FILE_EXTENSIONS;
                                            if (file_exists($VarFdr)) {
                                                if ($dh = opendir($VarFdr)) {
                                                    while (($file = readdir($dh)) !== false) {
                                                        if(is_file($VarFdr.$file)) {
                                                            ?>
                                                            <li>
                                                                <div style="padding: 10px 0;">
                                                                    <?php
                                                                    $VarFileExt = pathinfo($file, PATHINFO_EXTENSION);
                                                                    echo $file . ' '; ?>&nbsp;
                                                                    <a href="<?php echo base_url() . "orderentryvtwo/oeFileDownload?id=".urlencode(base64_encode($VarEnquiryId))."&fileName=".urlencode($file)."&page=poNo" ?>">
                                                                        <i class="fa fa-download fa-lg"
                                                                           aria-hidden="true"></i>
                                                                    </a>&nbsp;&nbsp;
                                                                    <?php
                                                                    if(in_array($VarFileExt,$ArrDwnExtensions)) {
                                                                    }
                                                                    else {
                                                                        ?>
                                                                        <a href="<?php echo base_url()."orderentryvtwo/oeOpenFile?id=".$VarEnquiryId."&fileName=".$file."&page=poNo" ?>" target="_blank">
                                                                            <i class="fa fa-file fa-lg" aria-hidden="true"></i>
                                                                        </a>
                                                                        <?php
                                                                    }
                                                                    ?>
                                                                </div>
                                                            </li>
                                                            <?php
                                                        }
                                                    }
                                                    closedir($dh);
                                                }
                                                ?>
                                                <?php
                                            } else {
                                                //echo 'No attachments';
                                            }
                                            ?>
                                        </ul>
                                        <?php
                                        $ArrPcsSet = unserialize(ARRPCSSET);
                                        //echo @$ArrPcsSet[@$ArrCommonData->pcsorset];
                                        $VarPcsOrSet = $ArrPcsSet[$ArrCommonHeaderData['ArrEnquiryDetails']['pcsorset']];
                                        ?>
                                    </div>
                                </div>
                                <?php $this->load->view(CNFCOMPANY . "orderentry/footerNavSave"); ?>
                                <div class="col-md-12">
                                    <div class="alert alert-success alert-dismissable hide" id="divSuccessBasicInfoMsg"></div>
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
<script src="<?php echo base_url(); ?>assets/js/jquery.uploadfile.min.js?s=<?php echo str_rand(5) ?>"></script>
<script type="text/javascript">
    var hashenquiryid = '<?php echo @$VarHashEnquiryId ?>';
    var enquiryid = '<?php echo @$VarEnquiryId ?>';

    var GlbParam = 'rfrom=1', GlbComboArr = [], GlbComponentArr = [], GlbColorArr = [], GlbColorArrFilter = [];
    var GlbFirstTableData = []; var GlbIntake = []; var GlbCccForFilter = [];
    GlbComponentFilter = {};
    var GlbSecondTbl = <?php echo $jsonSecondTbl ?>;
    var ps = ["<?php echo $VarPcsOrSet ?>"];
    function fnRemoveImg(fn) {
        if (confirm("Are you sure want to remove this image?")) {
            MakeAsynPostRequest(base_path + 'orderentryvtwo/deleteimg', GlbParam + "&enqid=" + enquiryid + "&fn=" + fn, 'json', fnRemoveImgRes);
        }
    }
    function fnRemoveImgRes(data) {
        console.log(data, 'data');
        window.location.href = base_path + 'orderentryvtwo/secondtbl/' + hashenquiryid;
        //fnRedirectPageTimeOut(base_path+'orderentryvtwo/secondtbl/'+hashenquiryid);
    }
    //
    var param = GlbParam + "&enqid=" + enquiryid;
    MakePostRequest(base_path + 'orderentryvtwo/getFirstTbl', param, 'json', getSecondTblForEditRes);
    function getSecondTblForEditRes(data) {
        console.log(data,'data');
        GlbComboArr = data.comboarr;
        GlbComponentArr = data.component;
        GlbColorArr = data.colorarr;
        GlbFirstTableData = data.firstTableData;
    }

    //console.log(GlbFirstTableData,'GlbFirstTableData');
    for (var a = 0; a < GlbFirstTableData.length; a++) {
        GlbComponentFilter[GlbFirstTableData[a][0]] = GlbFirstTableData[a][1];
        GlbColorArrFilter[GlbFirstTableData[a][0] + "||" + GlbFirstTableData[a][1]] = GlbFirstTableData[a][2];
        GlbIntake.push(GlbFirstTableData[a][3]);
        GlbCccForFilter[GlbFirstTableData[a][0] + "||" + GlbFirstTableData[a][1]+"||"+GlbFirstTableData[a][2]] = GlbFirstTableData[a][3];
        //console.log(GlbComponentFilter,'GlbComponentFilter');
    }
    //console.log(GlbSecondTbl,'GlbSecondTbl');
    //console.log(GlbColorArrFilter,'GlbColorArrFilter');

    var componentFilter = function (instance, cell, c, r, source) {
        let firstValue = instance.jexcel.getValueFromCoords(c - 1, r);
        //console.log(firstValue,'firstValue');
        //console.log(GlbComponentFilter[firstValue],'qqqqqqqqqqqqqqqqqq');
        if(GlbComponentFilter[firstValue])
        return [GlbComponentFilter[firstValue]];
        else
            return [];
    };

    var colorDropdowncommon = function (instance, cell, c, r, source) {
        //console.log(source, 'source');
        var second = instance.jexcel.getValueFromCoords(c - 1, r);
        var firstvalue = instance.jexcel.getValueFromCoords(c - 2, r);

        //console.log(firstvalue, 'firstvalue');
        //var second = $('#secondtbl').jexcel('getValue', c - 1 + '-' + r);
        //console.log(second, 'second');
        var keys = firstvalue + "||" + second;
        //console.log(keys,'keys');
        //console.log(GlbColorArrFilter,'GlbColorArrFilter');
        if(GlbColorArrFilter[keys]) {
            return [GlbColorArrFilter[keys]];
        }
        else {
            return [];
        }

    };

    intakeQtyFilter = function (instance, cell, c, r, source) {
        var color = instance.jexcel.getValueFromCoords(c - 1, r);
        var component = instance.jexcel.getValueFromCoords(c - 2, r);
        var combo = instance.jexcel.getValueFromCoords(c - 3, r);

        var keys = combo +"||"+component+"||"+color;

        //console.log(keys,'keys');
        //console.log(GlbCccForFilter,'GlbCccForFilter');
        //console.log(GlbCccForFilter[keys]);
        if(GlbCccForFilter[keys]) {
            return [GlbCccForFilter[keys]];
        }
        else {
            return [];
        }

    };
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
    unsaved = false;
    var table = jexcel(document.getElementById('secondtbl'), {
        columns:[
            { type:'dropdown',title:'Combo', width:280,source: GlbComboArr },
            { type:'dropdown',title:'Component', width:280,source: GlbComponentArr, filter: componentFilter },
            { type:'dropdown',title:'Colour', width:280,source: GlbColorArr, filter: colorDropdowncommon },
            { type:'dropdown',title:'Intake Qty. Per Comp. (Nos.)', width:100,source: GlbIntake, filter: intakeQtyFilter },
            { type:'text',title:'P.O. No. / Enq. Ref. No.', width:215 },
            { type:'numeric',title:'P.O. Qty. / Sample Qty.', width:100, align: 'right' },
            { type: 'dropdown', title:'Pcs. / Set', width:100, source: ps },
        ],
        data:GlbSecondTbl,
        onchange: function(instance, cell, x, y, value) {
            unsaved = true;
        },
        columnDrag:true,
        allowInsertColumn: false,
        minDimensions: [7,1],
        footers: [['','','','','Total','=SUMCOL(TABLE(), COLUMN())',["<?php echo $VarPcsOrSet ?>"]]],
        updateTable:function(instance, cell, col, row, val, label, cellName) {
            console.log(row,'row');
            if(col === 4) {
                $(cell).text(jsTrim(val));
                instance.jexcel.options.data[row][col] = jsTrim(val);
            }
        }
    });

    function fnGetCurrency(id) {
        var Parameters = GlbParam + "&currencycode=" + id;
        MakePostRequest(base_path + 'orderentryvtwo/getstaticcurrency', Parameters, 'json', fnGetCurrencyRes);
    }
    var GlbCurrencyRate = '';
    function fnGetCurrencyRes(data) {
        if (data != '') {
            if (data.errcode != undefined) {
                if (data.errcode == '404') {
                    fnCallSessionExpire();
                    return false;
                }
                else if (data.errcode == 1) {
                    console.log(data.VarExchangeRate, 'data');
                    GlbCurrencyRate = Math.round(data.VarExchangeRate.inramount);
                    $("#frmStaticExRate").text(data.VarExchangeRate.inramount);
                }
            }
        }
    }
    function fnSaveChanges() {
        let secondTblData = $('#secondtbl').jexcel('getData');
        let tblRemarks = $('#tblRemarks').val();
        console.log(secondTblData,'secondTblData');
        var param = GlbParam + "&d=" + JSON.stringify(secondTblData) + "&enqid=" + enquiryid+"&rem="+encodeURIComponent(tblRemarks);
        MakeAsynPostRequest(base_path + 'orderentryvtwo/saveSecondTbl', param, 'json', fnSaveSecondTableRes);
    }
    function fnSaveSecondTableRes(data) {
        console.log(data, 'data');
        if (data != '') {
            if (data.errcode == -1) {
                $("#ErrOrderEntry").removeClass('hide');
                $('#ErrOrderEntry').text('Error');
                return false;
            } else if (data.errcode == 1) {
                unsaved = false;
                console.log(extraObj, 'extraObj in res');
                extraObj.startUpload();
                //GlbId       = data.id;
                $("#divSuccessBasicInfoMsg").removeClass('hide');
                $("#divSuccessBasicInfoMsg").text("Saved Successfully");
                //fnRedirectPageTimeOut(base_path + 'orderentryvtwo/thirdTbl/' + hashenquiryid);
            }
        }
    }
    $(document).ready(function () {
        extraObj = $("#uploadBusinssImg").uploadFile({
            dragDrop: true,
            multiple: true,
            url: base_path + 'orderentryvtwo/oeFileUpload',
            returnType: "json",
            fileName: "myFile",
            dynamicFormData: function () {
                return {'id': enquiryid, 'page':'poNo'};
            },
            autoSubmit: false
        });
        console.log(extraObj, 'extraObj');
    });
    function unloadPage() {
        if(unsaved){
            return "You have unsaved changes on this page. Do you want to leave this page and discard your changes or stay on this page?";
        }
    }
    window.onbeforeunload = unloadPage;
</script>
<?php $this->load->view(CNFCOMPANY . 'template/pagefooter'); ?>