<?php
$this->load->view(CNFCOMPANY . 'template/pageheader');
?>
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
                            <div class="box-body" style="padding: 0">
                                <form class="form-horizontal" name="frmBasicInfo" id="frmOrderProcess" method="post">
                                    <div class="col-md-12 pd0 no-padding">
                                        <?php $this->load->view(CNFCOMPANY."orderentry/orderentrycommondetails"); ?>
                                        <div style="background-color: rgb(210 253 249); padding-top: 10px; padding-left: 10px">
                                            <strong>DOCUMENTATION & LOGISTICS DETAILS</strong>
                                        </div>
                                    </div>

                                    <div class="col-md-12" style="padding: 5px !important">
                                        <div id="orderentryDocandLogisticsTwentytwo"></div>
                                    </div>

                                    <div class="col-md-12" style="padding: 5px !important;">
                                            <label class="">Remarks</label>
                                            <textarea id="frmBasicRemarks" title="Remarks" class="form-control"></textarea>
                                    </div>

                                    <div class="box-footer pull-right" style="width: 350px; position: relative; top: -2px">
										<?php
										$ArrPages = unserialize(ARRORDERENTRYPAGES);
										$VarCurrentPage = $this->uri->segment(2);
										$VarKi = array_search($VarCurrentPage,$ArrPages);


										$VarPrevKey = $VarKi-1;
										?>
                                        <div class="bottomNav">
                                            <div class="" style="width: 90px; float: left; font-size: 18px; text-align: justify">
                                                <a href="<?php echo base_url('orderentryvtwo').'/'.$ArrPages[$VarPrevKey].'/'.$ArrCommonHeaderData['VarHashEnquiryId'] ?>" style="color: grey">
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
if($this->saveAccess) {
											if($ArrCommonHeaderData['ArrEnquiryDetails']['editaccess'] == 1) {
												?>
                                                <button type="button"
                                                        class="btn btn-info oeSaveEditBtn" style="" onclick="return fnSaveTable()">Save
                                                </button>
												<?php
											}
											}
											?>
                                        </div>
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
    var enquiryid = '<?php echo @$VarEnquiryId ?>';
    var unsaved = false;
    var GlbParam = 'rfrom=1', ArrPcsOrSet = ["Pcs.","Set"];

    var GlbArrForwarding = '<?php echo @$ArrForwarding ?>';
    var GlbArrClearing = '<?php echo @$ArrClearing ?>';
    var GlbArrImporter = '<?php echo @$ArrImporter ?>';
    var GlbCompanyName = '<?php echo COMPANYNAME ?>';
    var currentJxlTbl = [];

    MakePostRequest(base_path+'orderentryvtwo/docandlogisticstwentytwo',GlbParam+"&enqid="+enquiryid,'json',getTwentytwoTblDataRes);
    function getTwentytwoTblDataRes(data) {
        console.log(data, 'data');
        if(data.remarks !== "") {
            document.getElementById("frmBasicRemarks").innerText = data.remarks;
        }
        if(data.ArrSecondTbl != "") {
            let secondTbl = data.ArrSecondTbl;
            for(let ii = 0; ii < secondTbl.length; ii++) {
                currentJxlTbl.push([secondTbl[ii][0],secondTbl[ii][1],secondTbl[ii][2],secondTbl[ii][3],GlbCompanyName]);
            }
        }
        GlbArrBuyer = data.AllBuyers;
        if(data.jsonDocandLogistics != "") {
            currentJxlTbl = JSON.parse(data.jsonDocandLogistics);
        }
        $("#orderentryDocandLogisticsTwentytwo").jexcel({
            data: currentJxlTbl,
            allowInsertColumn: false,
            columns: [
                {title: 'Combo / Colour', width: 130, readOnly: true},
                {title: 'P.O. No. / Enq. Ref. No.', width: 130, readOnly: true},
                {title: 'P.O. Qty. / Sample Qty.', width: 87, readOnly: true},
                {title: 'Pcs. / Set', width: 60, readOnly: true},
                {type: 'text', title: 'Consignor / Shipper / Exporter', width: 190, readOnly:true},
                {type: 'dropdown', source: JSON.parse(GlbArrForwarding), title: 'Forwarding Agent', width: 190},
                {type: 'dropdown', source: JSON.parse(GlbArrClearing), title: 'Clearing Agent', width: 190},
                {type: 'dropdown', source: JSON.parse(GlbArrImporter), title: 'Importer - If other than<br/>Consignee', width: 190},
                {type: 'dropdown', title: 'Consignee', source: GlbArrBuyer, width: 190},
            ],
            onchange:function () {
                unsaved = true;
            },
            //updateTable:function(instance, cell, col, row, val, label, cellName) {
            //}
        });
    }

    function fnSaveTable() {
        if (confirm("To confirm click OK, else CANCEL")) {
            var data = JSON.stringify($("#orderentryDocandLogisticsTwentytwo").jexcel('getData'));
            let remarks = $("#frmBasicRemarks").val();
            console.log(data,'data');
            MakeAsynPostRequest(base_path+'orderentryvtwo/savedocandlogisticstwentytwo',GlbParam+"&d="+data+"&enqid="+
                enquiryid+"&e="+encodeURIComponent(remarks),'json',fnSaveTableRes);
        }
    }
    function fnSaveTableRes(data) {
        console.log(data);
        if(data!='') {
            if(data.errCode === 1) {
                unsaved = false;
                $("#divSuccessMsg").removeClass('hide');
                $("#divSuccessMsg").text("Success!");
                fnRedirectPageTimeOut(window.location.href);
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