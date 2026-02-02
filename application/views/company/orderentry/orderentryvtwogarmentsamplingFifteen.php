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
                            <div class="box-body" style="padding: 0px;">
                                <form class="form-horizontal" name="frmBasicInfo" id="frmOrderProcess" method="post">
                                    <div class="col-md-12 pd0 no-padding">
                                        <?php $this->load->view(CNFCOMPANY."orderentry/orderentrycommondetails"); ?>
                                        <div style="background-color: rgb(210 253 249); padding-top: 10px; padding-left: 10px">
                                            <strong>GARMENT SAMPLING DETAILS</strong>
                                        </div>
                                    </div>

                                    <div class="col-md-12" style="padding: 5px !important">
                                        <div id="orderentryvtwogarmentsamplingFifteen"></div>
                                    </div>

                                    <div class="col-md-12" style="padding: 5px !important;">
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
    var enquiryid = '<?php echo @$VarEnquiryId ?>';
    var GlbParam = 'rfrom=1', unsaved = false;
    var GlbSamplingReq = ["Proto Sample","Dev. Sample","Fit Sample","Sales Mam Sample","Photo Shoot Sample","PP Sample","Size Set","TOP"];
    var weekdays = ['SUN','MON','TUE','WED','THU','FRI','SAT'];
    var GlbComboArr = []; var GlbComponentArr = []; var GlbColorArr = []; var GlbPoNumbers = [];
    var GlbSizeSpecCode = []; var GlbNewFourth = [];
    var colorFilterGroup = {}; var sizeSpecCodeFilterGroup = {}; var poNumberFilterGroup = {};
    var currentTblData = [[]]; var GlbArrFinalSizes = [];
    MakePostRequest(base_path+'orderentryvtwo/garmentsamplingfifteen',GlbParam+"&enqid="+enquiryid,'json',getFifteenTblDataRes);
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
    function getFifteenTblDataRes(data) {
        console.log(data,'data');
        if(data.remarks !== "") {
            document.getElementById("frmBasicRemarks").innerText = data.remarks;
        }
        if(data.ArrFinalSizes != "") {
            GlbArrFinalSizes = data.ArrFinalSizes;
        }
        GlbArrFinalSizes.push("All","Running Size");
        if(data.jsonNewFourth != '') {
            GlbNewFourth = JSON.parse(data.jsonNewFourth);
        }
        if(data.jsonFromFifteen != "") {
            currentTblData = JSON.parse(data.jsonFromFifteen);
        }

        for(var ii = 0; ii < GlbNewFourth.length; ii++) {
            GlbComboArr.push(GlbNewFourth[ii][0]);
            GlbComponentArr.push(GlbNewFourth[ii][1]);
            GlbColorArr.push(GlbNewFourth[ii][2]);
            GlbPoNumbers.push(GlbNewFourth[ii][4]);
            GlbSizeSpecCode.push(GlbNewFourth[ii][5]);

            colorFilterGroup[GlbNewFourth[ii][0]+"||"+GlbNewFourth[ii][1]] = GlbNewFourth[ii][2];
            //poNumberFilterGroup[GlbNewFourth[ii][0]+"||"+GlbNewFourth[ii][1]+"||"+GlbNewFourth[ii][2]] = GlbNewFourth[ii][4];
            poNumberFilterGroup = fnPopulateValueArray(poNumberFilterGroup,GlbNewFourth[ii][0]+"||"+GlbNewFourth[ii][1]+"||"+GlbNewFourth[ii][2],
                GlbNewFourth[ii][4]);
            sizeSpecCodeFilterGroup[GlbNewFourth[ii][0]+"||"+GlbNewFourth[ii][1]+"||"+GlbNewFourth[ii][2]+"||"+GlbNewFourth[ii][4]] = GlbNewFourth[ii][5];
        }

        GlbComboArr = getUnique(GlbComboArr);
        GlbComponentArr = getUnique(GlbComponentArr);
        GlbColorArr = getUnique(GlbColorArr);
        GlbPoNumbers = getUnique(GlbPoNumbers);
        GlbSizeSpecCode = getUnique(GlbSizeSpecCode);
    }

    colorDropdownFilter = function(instance, cell, c, r, source) {
        var first  = instance.jexcel.getValueFromCoords(c - 2, r);
        var second = instance.jexcel.getValueFromCoords(c - 1, r);
        var keys = first+"||"+second;
        console.log(keys,'keys');
        console.log(colorFilterGroup[keys],'colorFilterGroup[keys]');
        if(colorFilterGroup[keys]) {
            return [colorFilterGroup[keys]];
        }
        else {
            return [];
        }
    };

    sizeSpecCodeFilter = function(instance, cell, c, r, source) {
        let first  = instance.jexcel.getValueFromCoords(c - 4, r);
        let second = instance.jexcel.getValueFromCoords(c - 3, r);
        let third = instance.jexcel.getValueFromCoords(c - 2, r);
        let fourth = instance.jexcel.getValueFromCoords(c - 1, r);
        let keys = first+"||"+second+"||"+third+"||"+fourth;
        if(sizeSpecCodeFilterGroup[keys]) {
            return [sizeSpecCodeFilterGroup[keys]];
        }
        else {
            return [];
        }
    };

    poNumberFilter = function(instance, cell, c, r, source) {
        console.log(source,'source');
        let cbo = instance.jexcel.getValueFromCoords(c - 3, r);
        let com = instance.jexcel.getValueFromCoords(c - 2, r);
        let col = instance.jexcel.getValueFromCoords(c - 1, r);
        let keys = cbo+"||"+com+"||"+col;
        let filters = poNumberFilterGroup[keys].replace('undefined|#|','');
        console.log(filters,'filters');
        let ArrFilter = filters.split('|#|');
        console.log(ArrFilter,'ArrFilter');
        if(ArrFilter) {
            return ArrFilter;
        }
        else {
            return [];
        }
    };
console.log(GlbArrFinalSizes,'GlbArrFinalSizes');
    $("#orderentryvtwogarmentsamplingFifteen").jexcel({
        colHeaders: ['Combo', 'Component', 'Colour', 'P.O. No./ Enq. Ref. No.', 'Size Spec<br/>Code / Fit','Requirement', 'Reqd. Size',
            'Reqd. No of Samples', 'Pcs. / Set', 'Sample Sub. Date', 'Buyer\'s Weekly Sample App. Days', 'Approval Status','Approved By'],
        colWidths: [120, 120, 120, 120, 120, 120, 80, 80, 70, 80, 120,100,107],
        allowInsertColumn: false,
        data: currentTblData,
        columns: [
            {type: 'dropdown', source: GlbComboArr},
            {type: 'dropdown', source: GlbComponentArr},
            {type: 'dropdown', source: GlbColorArr, filter: colorDropdownFilter},
            {type: 'dropdown', source: GlbPoNumbers, filter: poNumberFilter},
            {type: 'dropdown', source: GlbSizeSpecCode, filter: sizeSpecCodeFilter},
            {type: 'dropdown', source: GlbSamplingReq},
            {type: 'dropdown', source: GlbArrFinalSizes},
            {type: 'text'},
            {type: 'dropdown', source: ["Pcs.","Set"] },
            {type: 'calendar', options: {format: 'DD/MM/YYYY'}},
            {type: 'dropdown', source: weekdays, wordWrap: true, multiple: true},
            {type: 'dropdown', source: ['Pending','Approved','Rework','Rejected']},
            {type: 'dropdown', source: ['Buyer','Buyer\'s Agent','Buying Office','Buying Office Agent','Third Party']},
        ],
        onchange:function () {
            unsaved = true;
        }
    });

    function fnSaveChanges() {
        var data = JSON.stringify($("#orderentryvtwogarmentsamplingFifteen").jexcel('getData'));
        let remarks = $("#frmBasicRemarks").val();
        MakeAsynPostRequest(base_path+'orderentryvtwo/savegarmentsamplingfifteen',GlbParam+"&d="+data+
            "&enqid="+enquiryid+"&e="+encodeURIComponent(remarks),'json',fnSaveTableRes);
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
        if(unsaved) {
            return "You have unsaved changes on this page. Do you want to leave this page and discard your changes or stay on this page?";
        }
    }
    window.onbeforeunload = unloadPage;
</script>
<?php $this->load->view(CNFCOMPANY . 'template/pagefooter'); ?>