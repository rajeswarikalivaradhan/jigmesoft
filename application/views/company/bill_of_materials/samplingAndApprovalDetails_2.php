<?php
$this->load->view(CNFCOMPANY . 'template/pageheader');
?>
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/jexcel4/jsuites.css"/>
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/jexcel4/jexcel.css"/>
    <!--<style>
        table {
            table-layout: fixed;
        }

        td {
            word-wrap: break-word
        }
    </style>-->
    <body class="sidebar-mini skin-black wysihtml5-supported sidebar-collapse">
<div class="wrapper">
	<?php $this->load->view(CNFCOMPANY . 'template/templateheader'); ?>
    <aside class="main-sidebar">
		<?php $this->load->view(CNFCOMPANY . 'template/templateleftmenu'); ?>
    </aside>
    <div class="content-wrapper order-entry" >
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
                                        <strong>BOM SAMPLING & APPROVAL DETAILS: Article - 2</strong>
                                        <div id="bomSamplingAndApprovalDetailsArticle2"></div>
                                    </div>
                                    <div class="col-md-12" style="padding: 5px !important;">
                                            <label class="">Remarks</label>
                                            <textarea id="frmBasicRemarks" title="Remarks" class="form-control"></textarea>

                                    </div>
                                        <div class="box-footer pull-right" style="width: 350px; position: relative; top: -2px">
                                            <?php
                                            $ArrBomPages = ARR_BOM_PAGES;
                                            $VarCurrentPage = $this->uri->segment(2);
                                            $VarKi = array_search($VarCurrentPage,$ArrBomPages);

                                            $VarPrevKey = $VarKi-1;
                                            $VarNextKey = $VarKi+1;
                                            ?>
                                            <div class="bottomNav">
                                                <div class="" style="width: 90px; float: left; font-size: 18px; text-align: justify">
                                                    <a href="<?php echo base_url('billofmaterials').'/'.$ArrBomPages[$VarPrevKey].'/'.$ArrCommonHeaderData['VarHashEnquiryId'] ?>" style="color: grey">
                                                        <i class="fa fa-arrow-left" style="font-size: 14px"></i>
                                                        <span style="position: relative; bottom: 0; left: 5px"><b>PREV.</b></span>
                                                    </a>
                                                </div>
                                                <div class="pageNoBox">
                                                    <?php echo $VarKi ?>
                                                </div>
                                                <div class="" style="width: 108px; float: left; padding-left: 0; font-size: 18px; text-align: justify">
                                                    <a href="javascript:void(0)" style="color: grey; cursor: default; color: #bdbdbd">

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
                                            <div class="alert alert-success alert-dismissable hide" id="divSuccessMsg"></div>
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
    var GlbParam = 'rFrom=1', unsaved = false;
    var GlbYesNo = ["Yes", "No"];
    var GlbApprovalStatus = ["Pending", "Approved", "Declined"];
    var GlbBomCategory = ["New", "In-line", "Revised"];

    var GlbBomCons = '<?php echo @$ArrFromBomConsolidated ?>';
    var ArrBomCons = JSON.parse(GlbBomCons);
    console.log(ArrBomCons, 'ArrBomCons');
    var GlbBomSamplingDetails = [[]];
    function fnPopulateValueArray(ArrName, KeyValue, InsertVal) {
        if (jQuery.inArray(KeyValue, ArrName)) {
            ArrName[KeyValue] = ArrName[KeyValue] + '|#|' + InsertVal;
        }
        return ArrName;
    }

    var ArrItemDescDd = []; var makeGarmentSizeUnique = {};
    for (var a = 0; a < ArrBomCons.length; a++) {
        ArrItemDescDd = fnPopulateValueArray(ArrItemDescDd, ArrBomCons[a][0], ArrBomCons[a][2]);

    }

    for (var aa = 0; aa < ArrBomCons.length; aa++) {
        makeGarmentSizeUnique[ArrBomCons[aa][1]] = ArrBomCons[aa][1];
    }

    console.log(makeGarmentSizeUnique, 'makeGarmentSizeUnique');
    GlbGarmentSize = Object.values(makeGarmentSizeUnique);
    console.log(GlbGarmentSize,'GlbGarmentSize');
    var itemDesc = [];
    for (var keyb in ArrItemDescDd) {
        if (ArrItemDescDd.hasOwnProperty(keyb)) {
            itemDesc.push(keyb);
        }
    }

    MakePostRequest(base_path + 'billofmaterials/samplingAndApprovalDetails_2', GlbParam + "&enqId=" + enquiryId, 'json', fnRes);

    function fnRes(data) {
        console.log(data, 'data');
        if(data.remarks != "") document.getElementById("frmBasicRemarks").innerText = data.remarks;
        console.log(data.samplingAndApprovalDetails,'samplingAndApprovalDetails');
        if(data.samplingAndApprovalDetails != '') {
            GlbBomSamplingDetails = JSON.parse(data.samplingAndApprovalDetails);
        }
        console.log(GlbBomSamplingDetails,'GlbBomSamplingDetails');
        jexcel(document.getElementById("bomSamplingAndApprovalDetailsArticle2"), {
            allowInsertColumn: false,
            columns: [
                {type: 'dropdown', title: 'Item Description / Blend (%) / Content / Material', width: 220, wordWrap: true, source: itemDesc},
                {type: 'dropdown', title: 'Category', width: 110, wordWrap: true, source: GlbBomCategory},
                {type: 'dropdown', title: 'Sample Sub.\nfor Approval', width: 125, wordWrap: true, source: GlbYesNo},
                {type: 'dropdown', title: 'Sample\nSub. Size', width: 80, source: GlbGarmentSize, wordWrap: true },
                {title: 'Reqd. No.\nof Samples', width: 80, wordWrap: true },
                {type: 'calendar', title: 'Sample Sub. Date', width: 80, wordWrap: true},
                {type: 'dropdown', title: 'Approval\nStatus', width: 130, wordWrap: true, source: GlbApprovalStatus },
                {title: 'Approved Sample /\nItem Code', width: 150, wordWrap: true },
                {title: 'Approved Sample /\nItem Color Code', width: 150, wordWrap: true },
                {type: 'dropdown', title: 'Approved By', source: ['Buyer','Buyer\'s Agent','Buying Office','Buying Office Agent','Third Party'], width: 150},
                {type: 'calendar', title: 'Approved Date', width: 80}
            ],
            data: GlbBomSamplingDetails,
            onchange: function () {
                unsaved = true;
            },
            updateTable: function (instance, cell, col, row, val, label, cellName) {

            }
        });
    }

    function fnSaveTable() {
        let d = $("#bomSamplingAndApprovalDetailsArticle2").jexcel('getData');
        let remarks = $("#frmBasicRemarks").val();
        MakeAsynPostRequest(base_path + 'billofmaterials/saveSamplingAndApprovalDetails', GlbParam+
            "&d=" + JSON.stringify(d) +"&enqId=" +enquiryId + "&aid=2&e="+encodeURIComponent(remarks), 'json', fnSaveTableRes);
    }

    function fnSaveTableRes(data) {
        if (data != '') {
            if (data.errCode == 1) {
                //GlbId       = data.id;
                unsaved = false;
                $("#divSuccessMsg").removeClass('hide');
                $("#divSuccessMsg").text("Saved Successfully");
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
<?php $this->load->view(CNFCOMPANY . 'template/pagefooter'); ?>