<?php
$this->load->view(CNFCOMPANY . 'template/pageheader');
?>
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
                                        <strong>BOM SOURCING DETAILS: ARTICLE - 1</strong>
                                        <div id="bomSourcingDetailArticle1"></div>
                                    </div>
                                    <div class="col-md-12" style="padding: 5px !important;">
                                            <label class="">Remarks</label>
                                            <textarea id="frmBasicRemarks" title="Remarks" class="form-control"></textarea>

                                    </div>
                                </form>
                                <div class="col-md-12" style="padding: 5px !important;">
                                    <?php $this->load->view(CNFCOMPANY . "bill_of_materials/bomFooterNavSave"); ?>
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
<script>
    var enquiryId = '<?php echo @$VarEnquiryId ?>';
    var GlbParam = 'rFrom=1', unsaved = false;
    var GlbSourcingAdvice = ["Buyer Nominated", "Buying office Nominated", "Factory Source"];
    //var GlbSourcingAdvice = ["Buyer Nominated", "Agent Nominated", "Factory Source"];
    var GlbBomVendorLoc = ["Local", "Within State", "Inland", "Overseas"];
    var GlbBomSourcingDetailArticle1 = [[]];

    MakeAsynPostRequest(base_path + 'billofmaterials/sourcingDetailsArticle_1', GlbParam + "&enqId=" + enquiryId, 'json', function (data) {
        console.log(data, 'data');
        if(data.remarks != "") {
            document.getElementById("frmBasicRemarks").innerText = data.remarks;
        }
        GlbBomVendor = data.ArrBomVendor;
        GlbBomVendorGst = data.ArrBomVendorGst;
        GlbBomVendorIecode = data.ArrBomVendorIecode;
        GlbBomVendorContact = data.ArrBomVendorContact;
        if(data.bomSourcingDetailArticle1 != '') {
            GlbBomSourcingDetailArticle1 = JSON.parse(data.bomSourcingDetailArticle1);
            console.log(GlbBomSourcingDetailArticle1,'GlbBomSourcingDetailArticle1');
        }
        else if(data.ArrBomConsolidated != '') {
            GlbBomSourcingDetailArticle1 = [data.ArrBomConsolidated];
            console.log(GlbBomSourcingDetailArticle1,'GlbBomSourcingDetailArticle1');
        }
        else {
            GlbBomSourcingDetailArticle1 = [[]];
        }
        console.log(GlbBomSourcingDetailArticle1,'GlbBomSourcingDetailArticle1');
        console.log(GlbBomVendor,'GlbBomVendor');
        console.log(GlbBomVendorGst,'GlbBomVendorGst');
        jexcel(document.getElementById("bomSourcingDetailArticle1"), {
            allowInsertColumn: false,
            columns: [
                {title: 'Item Description / Blend (%) / Content / Material', width: 180, wordWrap: true, readOnly:true},
                {type: 'dropdown', title: 'Sourcing<br/>Advice', width: 100, wordWrap: true, source: GlbSourcingAdvice},
                {type: 'dropdown', title: 'Vendor<br/>Location', width: 100, wordWrap: true, source: GlbBomVendorLoc},
                {type: 'dropdown', title: "Vendor's Name", width: 140, wordWrap: true, source: GlbBomVendor },
                {type: 'text', title: 'GST / IE Code<br/>Details', width: 127, wordWrap: true, readOnly: true },
                {type: 'text', title: 'If On-line Ordering System: Website / User ID / Password', width: 170},
                {type: 'calendar', title: 'P.W. Expiry Date', width: 80},
                {type: 'text', title: 'Contact: Person / Email ID / Phone / Mobile', width: 150, wordWrap: true, readOnly: true }
            ],
            data: GlbBomSourcingDetailArticle1,
            onchange: function () {
                unsaved = true;
            },
            updateTable: function (instance, cell, col, row, val, label, cellName) {
                if(col == 3) {
                    gstIecode = ''; contactDetails = '';
                    console.log(val,'val');
                    if(GlbBomVendorGst[val] && GlbBomVendorIecode[val])
                        gstIecode = GlbBomVendorGst[val] +' / '+GlbBomVendorIecode[val];
                    if(GlbBomVendorContact[val]) {
                        contactDetails = GlbBomVendorContact[val];
                        console.log(contactDetails,'contactDetails');
                    }
                }
                if(col == 4) {
                    console.log(gstIecode,'gstIecode');
                    $(cell).html(gstIecode);
                    instance.jexcel.options.data[row][col] = gstIecode;
                }
                if(col == 7) {
                    $(cell).html(contactDetails);
                    instance.jexcel.options.data[row][col] = contactDetails;
                }
            }
        });

    });

    function fnSaveTable() {
        let bomSourcingData = $("#bomSourcingDetailArticle1").jexcel('getData');
        let remarks = $("#frmBasicRemarks").val();
        MakeAsynPostRequest(base_path + 'billofmaterials/saveSourcingDetails', GlbParam + "&d=" + JSON.stringify(bomSourcingData) +
            "&enqId=" +enquiryId + "&aid=1&e="+encodeURIComponent(remarks), 'json', fnSaveTableRes);
    }

    function fnSaveTableRes(data) {
        if (data != '') {
            if (data.errcode == 1) {
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