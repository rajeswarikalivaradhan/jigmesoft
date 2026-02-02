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
                                        <?php
                                        $ArrPcsSet = unserialize(ARRPCSSET);
                                        $VarPcsOrSet = $ArrPcsSet[$ArrCommonHeaderData['ArrEnquiryDetails']['pcsorset']];
                                        $this->load->view(CNFCOMPANY."orderentry/orderentrycommondetails"); ?>
                                        <div class="" style="background-color: rgb(210 253 249); padding-top: 10px; padding-left: 10px">
                                            <strong>PACKING DETAILS</strong>
                                        </div>
                                    </div>

                                    <div class="col-sm-12" style="padding: 5px !important">
                                        <div id="packingDetails"></div>

                                    </div>

                                    <div class="col-md-12" style="padding: 5px">

                                            <label class="">Remarks</label>
                                            <textarea id="frmBasicRemarks" title="Remarks" class="form-control"></textarea>

                                    </div>
                                    <div class="col-md-12" style="padding: 5px">
                                        <?php $this->load->view(CNFCOMPANY . "orderentry/footerNavSave"); ?>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="alert alert-success alert-dismissable hide" id="divSuccessMsg"> </div>
                                    </div>
                                </form>
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
    var GlbParam = 'rFrom=1'; unsaved = false;
    var ArrPcsOrSet = '<?php echo json_encode(unserialize(JXL_PCS_SET)) ?>';
    var GlbjsonThirdTbl, GlbJsonFourthTbl = [];
    var currentJxlTbl = [];
    var comboPoNumberGroup = {};

    MakePostRequest(base_path+"orderentryvtwo/packingdetails","rFrom=1&enqId="+enquiryId,"json",fnRes);
    function fnRes(data) {
        console.log(data,'data');
        if(data.remarks !== "") {
            document.getElementById("frmBasicRemarks").innerText = data.remarks;
        }
        GlbPackingCode = data.ArrPackingCode;
        GlbPackingMaterial = data.ArrPackingMaterial;
        //["Pac - 10","Pac - 11"];
        if(data.jsonNewFourthTbl != "") {
            GlbJsonFourthTbl = JSON.parse(data.jsonNewFourthTbl);
            for(let ii = 0; ii < GlbJsonFourthTbl.length; ii++) {
                currentJxlTbl.push([GlbJsonFourthTbl[ii][0],GlbJsonFourthTbl[ii][1],GlbJsonFourthTbl[ii][2],GlbJsonFourthTbl[ii][3],
                    GlbJsonFourthTbl[ii][4],GlbJsonFourthTbl[ii][5]]);
            }
        }
        if(data.jsonPackingDetails != "") {
            currentJxlTbl = JSON.parse(data.jsonPackingDetails);
        }
    }
    console.log(currentJxlTbl,'currentJxlTbl');
    jexcel(document.getElementById("packingDetails"),{
        columns: [
            {title: 'Combo', width: 150, readOnly: true},
            {title: 'Component', width: 150, readOnly: true},
            {title: 'Colour', width: 150, readOnly: true},
            {title: 'Intake', width: 70, wordWrap: true, readOnly: true},
            {title: 'P.O. No. / Enq. Ref. No.', width: 150, wordWrap: true, readOnly: true},
            {title: 'Size Spec Code / Fit Component Wise', width: 150, wordWrap: true, readOnly: true},
            {type: 'dropdown', source: GlbPackingCode, title: 'Packing Code<br/>Component Wise', width: 150, wordWrap: true},
            {type: 'dropdown', source: JSON.parse(ArrPcsOrSet), title: 'Packing Type', width: 70, wordWrap: true},
            {type: 'dropdown', source: GlbPackingMaterial, title: 'Packing Material Component Wise', width: 317, wordWrap: true, multiple: true},
        ],
        data: currentJxlTbl,
        allowInsertColumn: false,
        onchange:function () {
            unsaved = true;
        }
    });
    function fnSaveChanges() {
        const packingDetails = $("#packingDetails").jexcel('getData');
        let remarks = $("#frmBasicRemarks").val();
        MakePostRequest(base_path+"orderentryvtwo/savePackingDetails",GlbParam+"&enqId="+enquiryId+
            "&d="+JSON.stringify(packingDetails)+"&e="+encodeURIComponent(remarks),"json",fnSaveTableRes);
    }
    function fnSaveTableRes(data) {
        console.log(data,'data');
        if(data != '') {
            if(data.errCode == 1) {
                unsaved = false;
                $("#divSuccessMsg").removeClass("hide");
                $("#divSuccessMsg").text("Saved Successfully");
            }
        }
    }
    function unloadPage() {
        if(unsaved) {
            return "You have unsaved changes on this page. Do you want to leave this page and discard your changes or stay on this page?";
        }
    }
    window.onbeforeunload = unloadPage;
</script>
<?php $this->load->view(CNFCOMPANY . 'template/pagefooter'); ?>