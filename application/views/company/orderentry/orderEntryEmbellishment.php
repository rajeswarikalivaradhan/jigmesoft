<?php $this->load->view(CNFCOMPANY . 'template/pageheader'); ?>
    <link rel="stylesheet" href="<?php echo base_url();?>assets/css/uploadfile-order.css"/>
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
            <div class="row">
                <div class="col-md-12">
                    <div id="DivContBasicInfo">
                        <div class="box box-info">
                            <div class="box-header with-border"> </div>
                            <div class="box-body" style="padding: 0px;">
                                <form class="form-horizontal" name="frmBasicInfo" id="frmOrderProcess" method="post">
                                    <div class="col-md-12 pd0 no-padding">
                                        <?php $this->load->view(CNFCOMPANY."orderentry/orderentrycommondetails"); ?>
                                        <div style="background-color: rgb(210 253 249); padding-top: 10px; padding-left: 10px">
                                            <strong>EMBELLISHMENT DETAILS</strong>
                                            <small>If each component has more than one embellishment work, under "Artwork Name / Code" - one entry per row is only allowed.</small>
                                        </div>

                                    </div>

                                    <div class="col-sm-12" style="padding: 5px !important">
                                        <div id="embellishmentTenthTbl"></div>
                                    </div>
                                    <button type="button" class="btn btn-info pull-right" onclick="fnSaveArtworkCode()"
                                            style="position:relative; right: 40px; bottom: 10px">Save</button>
                                    <div class="col-md-12 pd0 no-padding">
                                        <div style="background-color: rgb(210 253 249); padding-top: 10px; padding-left: 10px">
                                            <strong></strong>
                                        </div>
                                    </div>
                                    <div class="col-md-12 pd0 no-padding">
                                        <div style="background-color: rgb(210 253 249); padding-top: 10px; padding-left: 10px">
                                            <strong>EMBELLISHMENT APPROVAL DETAILS</strong>
                                        </div>
                                    </div>

                                    <div class="col-sm-12" style="padding: 5px !important">
                                        <div id="embellSplitArtwork"></div>
                                    </div>
                                    <div class="col-md-12">
                                        <label class="control-label">ATTACHMENTS</label>
                                    </div>

                                    <div class="col-sm-12">
                                        <div id="uploadArtWorkFile" class="pdt10"></div>

                                        <ul style="list-style: none;">
                                            <?php
                                            $VarFdr = UPLOADS_SLASH . "orderentry" . DIRECTORY_SEPARATOR . $VarEnquiryId . DIRECTORY_SEPARATOR . "emb" . DIRECTORY_SEPARATOR;
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
                                                                    <a href="<?php echo base_url() . "orderentryvtwo/oeFileDownload?id=".urlencode(base64_encode($VarEnquiryId))."&fileName=".urlencode($file)."&page=emb" ?>">
                                                                        <i class="fa fa-download fa-lg"
                                                                           aria-hidden="true"></i>
                                                                    </a>&nbsp;&nbsp;
                                                                    <?php
                                                                    if(in_array($VarFileExt,$ArrDwnExtensions)) {
                                                                    }
                                                                    else {
                                                                        ?>
                                                                        <a href="<?php echo base_url()."orderentryvtwo/oeOpenFile?id=".$VarEnquiryId."&fileName=".$file."&page=emb" ?>" target="_blank">
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
                                    </div>

                                    <div class="col-md-12" style="padding: 5px">
                                        <div class="form-group" style="margin-bottom: 0">
                                            <label for="tblRemarks">Remarks</label>
                                            <textarea id="frmBasicRemarks" name="tblRemarks" title="Remarks" class="form-control"></textarea>
                                        </div>
                                    </div>
                                </form>
                                <div class="col-md-12" style="padding: 5px">
                                    <?php $this->load->view(CNFCOMPANY . "orderentry/footerNavSave"); ?>
                                </div>
                                <div class="col-md-12" style="padding: 5px">
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
<script src="<?php echo base_url(); ?>assets/js/jquery.uploadfile.min.js?s=<?php echo str_rand(5) ?>"></script>
<script type="text/javascript">
    var enquiryid = '<?php echo $VarEnquiryId ?>';
    var GlbParam = 'rfrom=1', unsaved = false;
    var separators = ['-', '/', ':'];
    var GlbComboArr = []; var GlbComponentArr = []; var GlbColorArr = []; var groupForColorFilter = {};
    var currentTbldata = [[]];
    var embellSplitArtwork = []; var embellSplitArtworkSaved = '';

    var GlbEmbellishmentType = []; var GlbMediumMaterial = [];
    MakePostRequest(base_path+'orderentryvtwo/emblishmenttenthtbl',GlbParam+"&enqid="+enquiryid,'json',getTenthTblDataRes);

    function getTenthTblDataRes(data) {
        console.log(data,'data');
        if(data.remarks !== "") {
            document.getElementById("frmBasicRemarks").innerText = data.remarks;
        }
        if(data.jsonCurrentTbl != '') {
            currentTbldata = JSON.parse(data.jsonCurrentTbl);
            embellSplitArtworkSaved = data.jsonFromArtworkCode;
        }
        else {
            currentTbldata = [[]];
        }
        var itemsFound = {};
        ArrSizes = data.ArrSizes;
        console.log(ArrSizes,'ArrSizes');
        GlbEmbellishmentType = data.embellishmentType;
        GlbMediumMaterial = data.ArrMediumMaterial;
        var ArrFromFirstTbl = data.ArrFromFirstTbl;
        var fromNewFourth = data.ArrFromNewFourth;
        console.log(fromNewFourth,'fromNewFourth');
        console.log(GlbComboArr,'GlbComboArr');
        console.log(ArrFromFirstTbl,'ArrFromFirstTbl');
        groupForColorFilter = {};
        for(var ii = 0; ii < fromNewFourth.length; ii++) {
            groupForColorFilter[fromNewFourth[ii][0]+"||"+fromNewFourth[ii][1]] = fromNewFourth[ii][2];
            GlbColorArr.push(fromNewFourth[ii][2]);
        }
        GlbColorArr = getUnique(GlbColorArr);
        console.log(GlbColorArr,'GlbColorArr');

        for(var ii = 0; ii < ArrFromFirstTbl.length; ii++) {
            GlbComboArr.push(ArrFromFirstTbl[ii][0]);
            var comp = ArrFromFirstTbl[ii][1].split(new RegExp(separators.join('|'),'g'));
            GlbComponentArr.push(comp);
            //GlbColorArr.push(ArrFromFirstTbl[ii][2]);
        }
        GlbComponentArr = [].concat.apply([],GlbComponentArr);
        GlbComponentArr = getUnique(GlbComponentArr);
        GlbComponentArr = jsTrimArr(GlbComponentArr);
    }

    var colorDropdownFilter = function(instance, cell, c, r, source) {
        var first  = instance.jexcel.getValueFromCoords(c - 2, r);
        var second = instance.jexcel.getValueFromCoords(c - 1, r);
        var keys = first+"||"+second;
        console.log(keys,'keys');
        console.log(groupForColorFilter,'groupForColorFilter');
        console.log(groupForColorFilter[keys],'groupForColorFilter[keys]');
        var colorRes = groupForColorFilter[keys];
        console.log(colorRes,'colorRes');
        if(colorRes) {
            return [colorRes];
        }
        else {
            return [];
        }
    };

    console.log(GlbColorArr,'GlbColorArr');
    console.log(currentTbldata,'currentTbldata');
    if(embellSplitArtworkSaved == '') {
        embellSplitArtwork = [];
    }
    else {
        embellSplitArtwork = JSON.parse(embellSplitArtworkSaved);
        console.log(embellSplitArtwork,'embellSplitArtworkembellSplitArtworkembellSplitArtworkembellSplitArtworkembellSplitArtworkembellSplitArtworkembellSplitArtwork');
        jexcel(document.getElementById('embellSplitArtwork'), {
            data: embellSplitArtwork,
            columns: [
                {type: 'text', title: 'Combo', width: 130, readOnly: true},
                {type: 'text', title: 'Component', width: 130, readOnly: true},
                {type: 'text', title: 'Colour', width: 130, readOnly: true},
                {title: 'Artwork Name / Code', width: 196, wordWrap: true, readOnly: true},
                {title: 'Option', width: 80, wordWrap: true},
                {title: 'Embellishment Type', type: 'dropdown' , source: GlbEmbellishmentType, multiple: true, width: 130, wordWrap: true},
                {title: 'Medium /<br/>Material', type: 'dropdown' , source: GlbMediumMaterial, multiple: true, width: 130, wordWrap: true},
                {type: 'dropdown', title: 'Approval Status', width: 100, source: ["Approved","Pending","Dropped"], wordWrap: true},
                {title: 'App. Sample / Strike Off Reference Details', width: 150, wordWrap: true},
                {type: 'dropdown', title: 'Approved By', width: 100, wordWrap: true, source: ['Buyer','Buyer\'s Agent','Buying Office','Buying Office Agent','Third Party']},
                {type: 'calendar', title: 'Approved Date', width: 80}
            ],
            footers:[['']],
            onchange:function () {
                unsaved = true;
            }
        });
    }
    jexcel(document.getElementById('embellishmentTenthTbl'), {
        data: currentTbldata,
        columns: [
            {type: 'dropdown', title: 'Combo', width: 130, source: GlbComboArr},
            {type: 'dropdown', title: 'Component', width: 130, source: GlbComponentArr},
            {type: 'dropdown', title: 'Colour', width: 130, source: GlbColorArr, filter: colorDropdownFilter},
            {title: 'Artwork Name / Code', width: 250, wordWrap: true},
            {title: 'Embellishment<br/>Grading Details', type:'text', width: 130, wordWrap: true},
            {title: 'Size Group', type: 'dropdown', source: ArrSizes, width: 130, wordWrap: true, multiple: true},
            {title: 'Remarks', width: 450, wordWrap: true}
        ],
        onchange:function () {
            unsaved = true;
        }
    });
    //new ends
    function fnSaveArtworkCode() {
        let data = $("#embellishmentTenthTbl").jexcel('getData');
        MakeAsynPostRequest(base_path+'orderentryvtwo/saveEmblishmentTenthTbl',GlbParam+"&d="+JSON.stringify(data)+"&enqid="+enquiryid,'json',fnSaveTableRes);
        embellSplitArtwork = [];
        for(let ii = 0; ii < data.length; ii++) {
            //let artworkCodeMix = data[ii][3];
            //if(artworkCodeMix.indexOf('/')) {
            //let artworkCode = artworkCodeMix.split('/');
            //if(artworkCode.length > 0) {
            //for(let ij = 0; ij < artworkCode.length; ij++) {*/
            //console.log(artworkCode,'artworkCode');
            embellSplitArtwork.push([data[ii][0],data[ii][1],data[ii][2],data[ii][3]]);
            //}
            //}
            //}
        }
        console.log(embellSplitArtwork,'embellSplitArtwork sdasdasdsa sadsadsadsadsad');
        jexcel.destroy(document.getElementById('embellSplitArtwork'));

        jexcel(document.getElementById('embellSplitArtwork'), {
            data: embellSplitArtwork,
            columns: [
                {type: 'text', title: 'Combo', width: 130, readOnly: true},
                {type: 'text', title: 'Component', width: 130, readOnly: true},
                {type: 'text', title: 'Colour', width: 130, readOnly: true},
                {title: 'Artwork Name / Code', width: 196, readOnly: true, wordWrap: true},
                {title: 'Option', width: 80, wordWrap: true},
                {title: 'Embellishment Type', type: 'dropdown' , source: GlbEmbellishmentType, multiple: true, width: 130, wordWrap: true},
                {title: 'Medium /<br/>Material', type: 'dropdown' , source: GlbMediumMaterial, multiple: true, width: 130, wordWrap: true},
                {type: 'dropdown', title: 'Approval Status', width: 100, source: ["Approved","Pending","Dropped"], wordWrap: true},
                {title: 'App. Sample / Strike Off Reference Details', width: 150, wordWrap: true},
                {type: 'dropdown', title: 'Approved By', width: 100, wordWrap: true, source: ['Buyer','Buyer\'s Agent','Buying Office','Buying Office Agent','Third Party']},
                {type: 'calendar', title: 'Approved Date', width: 80}
            ],
            onchange:function () {
                unsaved = true;
            }
        });

    }
    function fnSaveTableRes(data) {
        console.log(data,'data');
        if(data!='') {
            if(data.errcode==1) {
                unsaved = false;
                extraObj.startUpload();
                //$("#divSuccessMsg").removeClass('hide');
                //$('#divSuccessMsg').text("Saved Successfully");

            }
        }
    }

    function fnSaveChanges() {
        let artworkCodeData = $("#embellSplitArtwork").jexcel('getData');
        let tblRemarks = $("#frmBasicRemarks").val();
        MakeAsynPostRequest(base_path+'orderentryvtwo/saveArtworkCode',GlbParam+"&d="+JSON.stringify(artworkCodeData)+
            "&enqid="+enquiryid+"&e="+encodeURIComponent(tblRemarks),'json', function (data) {
            console.log(data,'data');
            if(data!='') {
                if(data.errcode==1) {
                    unsaved = false;
                    extraObj.startUpload();
                    $("#divSuccessMsg").removeClass('hide');
                    $('#divSuccessMsg').text("Saved Successfully");
                }
            }
        });
    }
    $(document).ready(function() {
        extraObj     = $("#uploadArtWorkFile").uploadFile({
            dragDrop: true,
            multiple:true,
            url:base_path+'orderentryvtwo/oeFileUpload',
            returnType: "json",
            fileName:"myFile",
            dynamicFormData:function () {
                return {'id': enquiryid, 'page':'emb'};
            },
            autoSubmit:false
        });
    });
    function unloadPage() {
        if(unsaved){
            return "You have unsaved changes on this page. Do you want to leave this page and discard your changes or stay on this page?";
        }
    }
    window.onbeforeunload = unloadPage;
</script>
<?php $this->load->view(CNFCOMPANY . 'template/pagefooter'); ?>