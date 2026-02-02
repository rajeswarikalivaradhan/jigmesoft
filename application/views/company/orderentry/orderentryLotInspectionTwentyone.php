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
                                        <?php $this->load->view(CNFCOMPANY . "orderentry/orderentrycommondetails"); ?>
                                        <div style="background-color: rgb(210 253 249); padding-top: 10px; padding-left: 10px">
                                            <strong>FINAL INSPECTION DETAILS</strong>
                                        </div>
                                    </div>

                                    <div class="col-md-12" style="padding: 5px !important">
                                        <div id="orderentryLotInspectionTwentyone"></div>
                                    </div>

                                    <div class="col-md-12" style="padding: 5px !important;">
                                            <label class="">Remarks</label>
                                            <textarea id="frmBasicRemarks" title="Remarks" class="form-control"></textarea>

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
    unsaved = false;
    var GlbParam = 'rFrom=1', ArrPcsOrSet = ["Pcs.", "Set"];
    var LotSize = ['2 to 8', '9 to 15', '16 to 25', '26 to 50', '51 to 90', '91 to 150', '151 to 280', '281 to 500', '501 to 1200', '1201 to 3200', '3201 to 10000', '10001 to 35000',
        '35001 to 150000', '150001 to 500000', '500001 and over'];
    var gilevels = ['-', 'I', 'II', 'III','S1', 'S2', 'S3', 'S4'];
    //var SiLevels = ['-', ];
    var SampleSizeCl = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'J', 'K', 'L', 'M', 'N', 'P', 'Q', 'R'];
    const sscL_sammplS = ['A2', 'B3', 'C5', 'D8', 'E13', 'F20', 'G32', 'H50', 'J80', 'K125', 'L200', 'M315', 'N500', 'P800', 'Q1250', 'R2000'];
    const gi = {};
    var SampleSize = [2, 3, 5, 8, 13, 20, 32, 50, 80, 125, 200, 315, 500, 800, 1250, 2000];
    var Aql = ['0.065', '0.10', '0.15', '0.25', '0.40', '0.65', '1.0', '1.5', '2.5', '4.0', '6.5'];
    const SampleSizeCl_SampleSizeGrp = {};
    var AcceptRejNo = {};
    const AcRejObj = {};
    const aqlGroup = {};
    gi[LotSize[0] + 'I'] = 'A';
    gi[LotSize[1] + 'I'] = 'A';
    gi[LotSize[2] + 'I'] = 'B';
    gi[LotSize[3] + 'I'] = 'C';
    gi[LotSize[4] + 'I'] = 'C';
    gi[LotSize[5] + 'I'] = 'D';
    gi[LotSize[6] + 'I'] = 'E';
    gi[LotSize[7] + 'I'] = 'F';
    gi[LotSize[8] + 'I'] = 'G';
    gi[LotSize[9] + 'I'] = 'H';
    gi[LotSize[10] + 'I'] = 'J';
    gi[LotSize[11] + 'I'] = 'K';
    gi[LotSize[12] + 'I'] = 'L';
    gi[LotSize[13] + 'I'] = 'M';
    gi[LotSize[14] + 'I'] = 'N';
    gi[LotSize[0] + 'II'] = 'A';
    gi[LotSize[1] + 'II'] = 'B';
    gi[LotSize[2] + 'II'] = 'C';
    gi[LotSize[3] + 'II'] = 'D';
    gi[LotSize[4] + 'II'] = 'E';
    gi[LotSize[5] + 'II'] = 'F';
    gi[LotSize[6] + 'II'] = 'G';
    gi[LotSize[7] + 'II'] = 'H';
    gi[LotSize[8] + 'II'] = 'J';
    gi[LotSize[9] + 'II'] = 'K';
    gi[LotSize[10] + 'II'] = 'L';
    gi[LotSize[11] + 'II'] = 'M';
    gi[LotSize[12] + 'II'] = 'N';
    gi[LotSize[13] + 'II'] = 'P';
    gi[LotSize[14] + 'II'] = 'Q';
    gi[LotSize[0] + 'III'] = 'B';
    gi[LotSize[1] + 'III'] = 'C';
    gi[LotSize[2] + 'III'] = 'D';
    gi[LotSize[3] + 'III'] = 'E';
    gi[LotSize[4] + 'III'] = 'F';
    gi[LotSize[5] + 'III'] = 'G';
    gi[LotSize[6] + 'III'] = 'H';
    gi[LotSize[7] + 'III'] = 'J';
    gi[LotSize[8] + 'III'] = 'K';
    gi[LotSize[9] + 'III'] = 'L';
    gi[LotSize[10] + 'III'] = 'M';
    gi[LotSize[11] + 'III'] = 'N';
    gi[LotSize[12] + 'III'] = 'P';
    gi[LotSize[13] + 'III'] = 'Q';
    gi[LotSize[14] + 'III'] = 'R';
    gi[LotSize[0] + 'S1'] = 'A';
    gi[LotSize[1] + 'S1'] = 'A';
    gi[LotSize[2] + 'S1'] = 'A';
    gi[LotSize[3] + 'S1'] = 'A';
    gi[LotSize[4] + 'S1'] = 'B';
    gi[LotSize[5] + 'S1'] = 'B';
    gi[LotSize[6] + 'S1'] = 'B';
    gi[LotSize[7] + 'S1'] = 'B';
    gi[LotSize[8] + 'S1'] = 'C';
    gi[LotSize[9] + 'S1'] = 'C';
    gi[LotSize[10] + 'S1'] = 'C';
    gi[LotSize[11] + 'S1'] = 'C';
    gi[LotSize[12] + 'S1'] = 'D';
    gi[LotSize[13] + 'S1'] = 'D';
    gi[LotSize[14] + 'S1'] = 'D';
    gi[LotSize[0] + 'S2'] = 'A';
    gi[LotSize[1] + 'S2'] = 'A';
    gi[LotSize[2] + 'S2'] = 'A';
    gi[LotSize[3] + 'S2'] = 'B';
    gi[LotSize[4] + 'S2'] = 'B';
    gi[LotSize[5] + 'S2'] = 'B';
    gi[LotSize[6] + 'S2'] = 'C';
    gi[LotSize[7] + 'S2'] = 'C';
    gi[LotSize[8] + 'S2'] = 'C';
    gi[LotSize[9] + 'S2'] = 'D';
    gi[LotSize[10] + 'S2'] = 'D';
    gi[LotSize[11] + 'S2'] = 'D';
    gi[LotSize[12] + 'S2'] = 'E';
    gi[LotSize[13] + 'S2'] = 'E';
    gi[LotSize[14] + 'S2'] = 'E';
    gi[LotSize[0] + 'S3'] = 'A';
    gi[LotSize[1] + 'S3'] = 'A';
    gi[LotSize[2] + 'S3'] = 'B';
    gi[LotSize[3] + 'S3'] = 'B';
    gi[LotSize[4] + 'S3'] = 'C';
    gi[LotSize[5] + 'S3'] = 'C';
    gi[LotSize[6] + 'S3'] = 'D';
    gi[LotSize[7] + 'S3'] = 'D';
    gi[LotSize[8] + 'S3'] = 'E';
    gi[LotSize[9] + 'S3'] = 'E';
    gi[LotSize[10] + 'S3'] = 'F';
    gi[LotSize[11] + 'S3'] = 'F';
    gi[LotSize[12] + 'S3'] = 'G';
    gi[LotSize[13] + 'S3'] = 'G';
    gi[LotSize[14] + 'S3'] = 'H';
    gi[LotSize[0] + 'S4'] = 'A';
    gi[LotSize[1] + 'S4'] = 'A';
    gi[LotSize[2] + 'S4'] = 'B';
    gi[LotSize[3] + 'S4'] = 'C';
    gi[LotSize[4] + 'S4'] = 'C';
    gi[LotSize[5] + 'S4'] = 'D';
    gi[LotSize[6] + 'S4'] = 'E';
    gi[LotSize[7] + 'S4'] = 'E';
    gi[LotSize[8] + 'S4'] = 'F';
    gi[LotSize[9] + 'S4'] = 'G';
    gi[LotSize[10] + 'S4'] = 'G';
    gi[LotSize[11] + 'S4'] = 'H';
    gi[LotSize[12] + 'S4'] = 'J';
    gi[LotSize[13] + 'S4'] = 'J';
    gi[LotSize[14] + 'S4'] = 'K';
    SampleSizeCl_SampleSizeGrp['A'] = 2;
    SampleSizeCl_SampleSizeGrp['B'] = 3;
    SampleSizeCl_SampleSizeGrp['C'] = 5;
    SampleSizeCl_SampleSizeGrp['D'] = 8;
    SampleSizeCl_SampleSizeGrp['E'] = 13;
    SampleSizeCl_SampleSizeGrp['F'] = 20;
    SampleSizeCl_SampleSizeGrp['G'] = 32;
    SampleSizeCl_SampleSizeGrp['H'] = 50;
    SampleSizeCl_SampleSizeGrp['J'] = 80;
    SampleSizeCl_SampleSizeGrp['K'] = 125;
    SampleSizeCl_SampleSizeGrp['L'] = 200;
    SampleSizeCl_SampleSizeGrp['M'] = 315;
    SampleSizeCl_SampleSizeGrp['N'] = 500;
    SampleSizeCl_SampleSizeGrp['P'] = 800;
    SampleSizeCl_SampleSizeGrp['Q'] = 1250;
    SampleSizeCl_SampleSizeGrp['R'] = 2000;

    AcRejObj['A|#|2|#|6.5'] = '0 / 1';
    AcRejObj['B|#|3|#|4.0'] = '0 / 1';
    AcRejObj['C|#|5|#|2.5'] = '0 / 1';
    AcRejObj['D|#|8|#|1.5'] = '0 / 1';
    AcRejObj['D|#|8|#|6.5'] = '1 / 2';
    AcRejObj['E|#|13|#|1.0'] = '0 / 1';
    AcRejObj['E|#|13|#|4.0'] = '1 / 2';
    AcRejObj['E|#|13|#|6.5'] = '2 / 3';
    AcRejObj['F|#|20|#|2.5'] = '1 / 2';
    AcRejObj['F|#|20|#|4.0'] = '2 / 3';
    AcRejObj['F|#|20|#|6.5'] = '3 / 4';
    AcRejObj['G|#|32|#|1.5'] = '1 / 2';
    AcRejObj['G|#|32|#|2.5'] = '2 / 3';
    AcRejObj['G|#|32|#|4.0'] = '3 / 4';
    AcRejObj['G|#|32|#|6.5'] = '5 / 6';
    AcRejObj['H|#|50|#|1.0'] = '1 / 2';
    AcRejObj['H|#|50|#|1.5'] = '2 / 3';
    AcRejObj['H|#|50|#|2.5'] = '3 / 4';
    AcRejObj['H|#|50|#|4.0'] = '5 / 6';
    AcRejObj['H|#|50|#|6.5'] = '7 / 8';
    AcRejObj['J|#|80|#|1.0'] = '2 / 3';
    AcRejObj['J|#|80|#|1.5'] = '3 / 4';
    AcRejObj['J|#|80|#|2.5'] = '5 / 6';
    AcRejObj['J|#|80|#|4.0'] = '7 / 8';
    AcRejObj['J|#|80|#|6.5'] = '10 / 11';
    AcRejObj['K|#|125|#|1.0'] = '3 / 4';
    AcRejObj['K|#|125|#|1.5'] = '5 / 6';
    AcRejObj['K|#|125|#|2.5'] = '7 / 8';
    AcRejObj['K|#|125|#|4.0'] = '10 / 11';
    AcRejObj['K|#|125|#|6.5'] = '14 / 15';
    AcRejObj['L|#|200|#|1.0'] = '5 / 6';
    AcRejObj['L|#|200|#|1.5'] = '7 / 8';
    AcRejObj['L|#|200|#|2.5'] = '10 / 11';
    AcRejObj['L|#|200|#|4.0'] = '14 / 15';
    AcRejObj['L|#|200|#|6.5'] = '21 / 22';
    AcRejObj['M|#|315|#|1.0'] = '7 / 8';
    AcRejObj['M|#|315|#|1.5'] = '10 / 11';
    AcRejObj['M|#|315|#|2.5'] = '14 / 15';
    AcRejObj['M|#|315|#|4.0'] = '21 / 22';
    AcRejObj['N|#|500|#|1.0'] = '10 / 11';
    AcRejObj['N|#|500|#|1.5'] = '14 / 15';
    AcRejObj['N|#|500|#|2.5'] = '21 / 22';
    AcRejObj['P|#|800|#|1.0'] = '14 / 15';
    AcRejObj['P|#|800|#|1.5'] = '21 / 22';
    AcRejObj['Q|#|1250|#|1.0'] = '21 / 22';
    //
    for (let a = 0; a < sscL_sammplS.length; a++) {
        for (let b = 0; b < 11; b++) {
            if (b == 10 && a == 1) {
                AcceptRejNo[sscL_sammplS[a] + '||' + Aql[b]] = '1 / 2';
            }
            else if ((b == 9 || b == 10) && a == 2) {
                AcceptRejNo[sscL_sammplS[a] + '||' + Aql[b]] = '1 / 2';
            }
            else if ((b == 9 || b == 10) && a == 2) {
                AcceptRejNo[sscL_sammplS[a] + '||' + Aql[b]] = '1 / 2';
            }
            else if ((b == 8 || b == 9 || b == 10) && a == 3) {
                AcceptRejNo[sscL_sammplS[a] + '||' + Aql[b]] = '1 / 2';
            }
            else if ((b == 7 || b == 8 || b == 9) && a == 4) {
                AcceptRejNo[sscL_sammplS[a] + '||' + Aql[b]] = '1 / 2';
            }
            else if (b == 10 && a == 4) {
                AcceptRejNo[sscL_sammplS[a] + '||' + Aql[b]] = '2 / 3';
            }
            else if ((b == 6 || b == 7 || b == 8) && a == 5) {
                AcceptRejNo[sscL_sammplS[a] + '||' + Aql[b]] = '1 / 2';
            }
            else if (b == 9 && a == 5) {
                AcceptRejNo[sscL_sammplS[a] + '||' + Aql[b]] = '2 / 3';
            }
            else if (b == 10 && a == 5) {
                AcceptRejNo[sscL_sammplS[a] + '||' + Aql[b]] = '3 / 4';
            }
            else if ((b == 5 || b == 6 || b == 7) && a == 6) {
                AcceptRejNo[sscL_sammplS[a] + '||' + Aql[b]] = '1 / 2';
            }
            else if (b == 8 && a == 6) {
                AcceptRejNo[sscL_sammplS[a] + '||' + Aql[b]] = '2 / 3';
            }
            else if (b == 9 && a == 6) {
                AcceptRejNo[sscL_sammplS[a] + '||' + Aql[b]] = '3 / 4';
            }
            else if (b == 10 && a == 6) {
                AcceptRejNo[sscL_sammplS[a] + '||' + Aql[b]] = '5 / 6';
            }
            else if ((b == 4 || b == 5 || b == 6) && a == 7) {
                AcceptRejNo[sscL_sammplS[a] + '||' + Aql[b]] = '1 / 2';
            }
            else if (b == 7 && a == 7) {
                AcceptRejNo[sscL_sammplS[a] + '||' + Aql[b]] = '2 / 3';
            }
            else if (b == 8 && a == 7) {
                AcceptRejNo[sscL_sammplS[a] + '||' + Aql[b]] = '3 / 4';
            }
            else if (b == 9 && a == 7) {
                AcceptRejNo[sscL_sammplS[a] + '||' + Aql[b]] = '5 / 6';
            }
            else if (b == 10 && a == 7) {
                AcceptRejNo[sscL_sammplS[a] + '||' + Aql[b]] = '7 / 8';
            }
            else if ((b == 3 || b == 4 || b == 5) && a == 8) {
                AcceptRejNo[sscL_sammplS[a] + '||' + Aql[b]] = '1 / 2';
            }
            else if (b == 6 && a == 8) {
                AcceptRejNo[sscL_sammplS[a] + '||' + Aql[b]] = '2 / 3';
            }
            else if (b == 7 && a == 8) {
                AcceptRejNo[sscL_sammplS[a] + '||' + Aql[b]] = '3 / 4';
            }
            else if (b == 8 && a == 8) {
                AcceptRejNo[sscL_sammplS[a] + '||' + Aql[b]] = '5 / 6';
            }
            else if (b == 9 && a == 8) {
                AcceptRejNo[sscL_sammplS[a] + '||' + Aql[b]] = '7 / 8';
            }
            else if (b == 10 && a == 8) {
                AcceptRejNo[sscL_sammplS[a] + '||' + Aql[b]] = '10 / 11';
            }
            else if ((b == 2 || b == 3 || b == 5) && a == 9) {
                AcceptRejNo[sscL_sammplS[a] + '||' + Aql[b]] = '10 / 11';
            }
            else if (b == 5 && a == 9) {
                AcceptRejNo[sscL_sammplS[a] + '||' + Aql[b]] = '2 / 3';
            }
            else if (b == 6 && a == 9) {
                AcceptRejNo[sscL_sammplS[a] + '||' + Aql[b]] = '3 / 4';
            }
            else if (b == 7 && a == 9) {
                AcceptRejNo[sscL_sammplS[a] + '||' + Aql[b]] = '5 / 6';
            }
            else if (b == 8 && a == 9) {
                AcceptRejNo[sscL_sammplS[a] + '||' + Aql[b]] = '7 / 8';
            }
            else if (b == 9 && a == 9) {
                AcceptRejNo[sscL_sammplS[a] + '||' + Aql[b]] = '10 / 11';
            }
            else if (b == 10 && a == 9) {
                AcceptRejNo[sscL_sammplS[a] + '||' + Aql[b]] = '14 / 15';
            }
            else if ((b == 1 || b == 2 || b == 3) && a == 10) {
                AcceptRejNo[sscL_sammplS[a] + '||' + Aql[b]] = '1 / 2';
            }
            else if(b == 4 && a == 10) {
                AcceptRejNo[sscL_sammplS[a] + '||' + Aql[b]] = '2 / 3';
            }
            else if(b == 5 && a == 10) {
                AcceptRejNo[sscL_sammplS[a] + '||' + Aql[b]] = '3 / 4';
            }
            else if(b == 6 && a == 10) {
                AcceptRejNo[sscL_sammplS[a] + '||' + Aql[b]] = '5 / 6';
            }
            else if(b == 7 && a == 10) {
                AcceptRejNo[sscL_sammplS[a] + '||' + Aql[b]] = '7 / 8';
            }
            else if(b == 8 && a == 10) {
                AcceptRejNo[sscL_sammplS[a] + '||' + Aql[b]] = '10 / 11';
            }
            else if(b == 9 && a == 10) {
                AcceptRejNo[sscL_sammplS[a] + '||' + Aql[b]] = '14 / 15';
            }
            else if(b == 10 && a == 10) {
                AcceptRejNo[sscL_sammplS[a] + '||' + Aql[b]] = '21 / 22';
            }
            else if((b == 0 || b == 1 || b == 2) && a == 11) {
                AcceptRejNo[sscL_sammplS[a] + '||' + Aql[b]] = '1 / 2';
            }
            else if(b == 3 && a == 11) {
                AcceptRejNo[sscL_sammplS[a] + '||' + Aql[b]] = '2 / 3';
            }
            else if(b == 4 && a == 11) {
                AcceptRejNo[sscL_sammplS[a] + '||' + Aql[b]] = '3 / 4';
            }
            else if(b == 5 && a == 11) {
                AcceptRejNo[sscL_sammplS[a] + '||' + Aql[b]] = '5 / 6';
            }
            else if(b == 6 && a == 11) {
                AcceptRejNo[sscL_sammplS[a] + '||' + Aql[b]] = '7 / 8';
            }
            else if(b == 7 && a == 11) {
                AcceptRejNo[sscL_sammplS[a] + '||' + Aql[b]] = '10 / 11';
            }
            else if(b == 8 && a == 11) {
                AcceptRejNo[sscL_sammplS[a] + '||' + Aql[b]] = '14 / 15';
            }
            else if(b == 9 && a == 11) {
                AcceptRejNo[sscL_sammplS[a] + '||' + Aql[b]] = '21 / 22';
            }
            else if(b == 10 && a == 11) {
                AcceptRejNo[sscL_sammplS[a] + '||' + Aql[b]] = '21 / 22';
            }
            else if((b == 0 || b == 1) && a == 12) {
                AcceptRejNo[sscL_sammplS[a] + '||' + Aql[b]] = '1 / 2';
            }
            else if(b == 2 && a == 12) {
                AcceptRejNo[sscL_sammplS[a] + '||' + Aql[b]] = '2 / 3';
            }
            else if(b == 3 && a == 12) {
                AcceptRejNo[sscL_sammplS[a] + '||' + Aql[b]] = '3 / 4';
            }
            else if(b == 4 && a == 12) {
                AcceptRejNo[sscL_sammplS[a] + '||' + Aql[b]] = '5 / 6';
            }
            else if(b == 5 && a == 12) {
                AcceptRejNo[sscL_sammplS[a] + '||' + Aql[b]] = '7 / 8';
            }
            else if(b == 6 && a == 12) {
                AcceptRejNo[sscL_sammplS[a] + '||' + Aql[b]] = '10 / 11';
            }
            else if(b == 7 && a == 12) {
                AcceptRejNo[sscL_sammplS[a] + '||' + Aql[b]] = '14 / 15';
            }
            else if((b == 8 || b == 9 || b == 10) && a == 12) {
                AcceptRejNo[sscL_sammplS[a] + '||' + Aql[b]] = '21 / 22';
            }
            else if(b == 0 && a == 13) { //P
                AcceptRejNo[sscL_sammplS[a] + '||' + Aql[b]] = '1 / 2';
            }
            else if(b == 1 && a == 13) { //P
                AcceptRejNo[sscL_sammplS[a] + '||' + Aql[b]] = '2 / 3';
            }
            else if(b == 2 && a == 13) { //P
                AcceptRejNo[sscL_sammplS[a] + '||' + Aql[b]] = '3 / 4';
            }
            else if(b == 3 && a == 13) { //P
                AcceptRejNo[sscL_sammplS[a] + '||' + Aql[b]] = '5 / 6';
            }
            else if(b == 4 && a == 13) { //P
                AcceptRejNo[sscL_sammplS[a] + '||' + Aql[b]] = '7 / 8';
            }
            else if(b == 5 && a == 13) { //P
                AcceptRejNo[sscL_sammplS[a] + '||' + Aql[b]] = '10 / 11';
            }
            else if(b == 6 && a == 13) { //P
                AcceptRejNo[sscL_sammplS[a] + '||' + Aql[b]] = '14 / 15';
            }
            else if((b == 7 || b == 8 || b == 9 || b == 10) && a == 13) { //P
                AcceptRejNo[sscL_sammplS[a] + '||' + Aql[b]] = '21 / 22';
            }
            else if(b == 0 && a == 14) { //Q
                AcceptRejNo[sscL_sammplS[a] + '||' + Aql[b]] = '2 / 3';
            }
            else if(b == 1 && a == 14) { //Q
                AcceptRejNo[sscL_sammplS[a] + '||' + Aql[b]] = '3 / 4';
            }
            else if(b == 2 && a == 14) { //Q
                AcceptRejNo[sscL_sammplS[a] + '||' + Aql[b]] = '5 / 6';
            }
            else if(b == 3 && a == 14) { //Q
                AcceptRejNo[sscL_sammplS[a] + '||' + Aql[b]] = '7 / 8';
            }
            else if(b == 4 && a == 14) { //Q
                AcceptRejNo[sscL_sammplS[a] + '||' + Aql[b]] = '10 / 11';
            }
            else if(b == 5 && a == 14) { //Q
                AcceptRejNo[sscL_sammplS[a] + '||' + Aql[b]] = '14 / 15';
            }
            else if((b == 6 || b == 7 || b == 8 || b == 9 || b == 10) && a == 14) { //Q
                AcceptRejNo[sscL_sammplS[a] + '||' + Aql[b]] = '21 / 22';
            }
            else if(b == 0 && a == 15) { //R
                AcceptRejNo[sscL_sammplS[a] + '||' + Aql[b]] = '3 / 4';
            }
            else if(b == 1 && a == 15) { //R
                AcceptRejNo[sscL_sammplS[a] + '||' + Aql[b]] = '5 / 6';
            }
            else if(b == 2 && a == 15) { //R
                AcceptRejNo[sscL_sammplS[a] + '||' + Aql[b]] = '7 / 8';
            }
            else if(b == 3 && a == 15) { //R
                AcceptRejNo[sscL_sammplS[a] + '||' + Aql[b]] = '10 / 11';
            }
            else if(b == 4 && a == 15) { //R
                AcceptRejNo[sscL_sammplS[a] + '||' + Aql[b]] = '14 / 25';
            }
            else if((b == 5 || b == 6 || b == 7 || b == 8 || b == 9 || b == 10) && a == 15) { //R
                AcceptRejNo[sscL_sammplS[a] + '||' + Aql[b]] = '21 / 22';
            }
            else {
                AcceptRejNo[sscL_sammplS[a] + '||' + Aql[b]] = '0 / 1';
            }
        }
    }
    //console.log(AcceptRejNo, 'AcceptRejNo');
    AcRejObj['B|#|3|#|4.0'] = '0 / 1';
    AcRejObj['C|#|5|#|2.5'] = '0 / 1';
    AcRejObj['D|#|8|#|1.5'] = '0 / 1';
    AcRejObj['D|#|8|#|6.5'] = '1 / 2';
    AcRejObj['E|#|13|#|1.0'] = '0 / 1';
    AcRejObj['E|#|13|#|4.0'] = '1 / 2';
    AcRejObj['E|#|13|#|6.5'] = '2 / 3';
    AcRejObj['F|#|20|#|2.5'] = '1 / 2';
    AcRejObj['F|#|20|#|4.0'] = '2 / 3';
    AcRejObj['F|#|20|#|6.5'] = '3 / 4';
    AcRejObj['G|#|32|#|1.5'] = '1 / 2';
    AcRejObj['G|#|32|#|2.5'] = '2 / 3';
    AcRejObj['G|#|32|#|4.0'] = '3 / 4';
    AcRejObj['G|#|32|#|6.5'] = '5 / 6';
    AcRejObj['H|#|50|#|1.0'] = '1 / 2';
    AcRejObj['H|#|50|#|1.5'] = '2 / 3';
    AcRejObj['H|#|50|#|2.5'] = '3 / 4';
    AcRejObj['H|#|50|#|4.0'] = '5 / 6';
    AcRejObj['H|#|50|#|6.5'] = '7 / 8';
    AcRejObj['J|#|80|#|1.0'] = '2 / 3';
    AcRejObj['J|#|80|#|1.5'] = '3 / 4';
    AcRejObj['J|#|80|#|2.5'] = '5 / 6';
    AcRejObj['J|#|80|#|4.0'] = '7 / 8';
    AcRejObj['J|#|80|#|6.5'] = '10 / 11';
    AcRejObj['K|#|125|#|1.0'] = '3 / 4';
    AcRejObj['K|#|125|#|1.5'] = '5 / 6';
    AcRejObj['K|#|125|#|2.5'] = '7 / 8';
    AcRejObj['K|#|125|#|4.0'] = '10 / 11';
    AcRejObj['K|#|125|#|6.5'] = '14 / 15';
    AcRejObj['L|#|200|#|1.0'] = '5 / 6';
    AcRejObj['L|#|200|#|1.5'] = '7 / 8';
    AcRejObj['L|#|200|#|2.5'] = '10 / 11';
    AcRejObj['L|#|200|#|4.0'] = '14 / 15';
    AcRejObj['L|#|200|#|6.5'] = '21 / 22';
    AcRejObj['M|#|315|#|1.0'] = '7 / 8';
    AcRejObj['M|#|315|#|1.5'] = '10 / 11';
    AcRejObj['M|#|315|#|2.5'] = '14 / 15';
    AcRejObj['M|#|315|#|4.0'] = '21 / 22';
    AcRejObj['N|#|500|#|1.0'] = '10 / 11';
    AcRejObj['N|#|500|#|1.5'] = '14 / 15';
    AcRejObj['N|#|500|#|2.5'] = '21 / 22';
    AcRejObj['P|#|800|#|1.0'] = '14 / 15';
    AcRejObj['P|#|800|#|1.5'] = '21 / 22';
    AcRejObj['Q|#|1250|#|1.0'] = '21 / 22';
    //
/*    aqlGroup['A'] = ['6.5'];
    aqlGroup['B'] = ['4.0'];
    aqlGroup['C'] = ['2.5'];
    aqlGroup['D'] = ['1.5', '6.5'];
    aqlGroup['E'] = ['1.0', '4.0', '6.5'];
    aqlGroup['F'] = ['2.5', '4.0', '6.5'];
    aqlGroup['G'] = ['1.5', '2.5', '4.0', '6.5'];
    aqlGroup['H'] = ['1.0', '1.5', '2.5', '4.0', '6.5'];
    aqlGroup['J'] = ['1.0', '1.5', '2.5', '4.0', '6.5'];
    aqlGroup['K'] = ['1.0', '1.5', '2.5', '4.0', '6.5'];
    aqlGroup['L'] = ['1.0', '1.5', '2.5', '4.0', '6.5'];
    aqlGroup['M'] = ['1.0', '1.5', '2.5', '4.0'];
    aqlGroup['N'] = ['1.0', '1.5', '2.5'];
    aqlGroup['P'] = ['1.0', '1.5'];
    aqlGroup['Q'] = ['1.0'];*/


    //AlGroup['D'] = 2.5;
    var currentJxlTbl = [];
    var GlbApprovingauthority = ['Buyer', 'Buyer Agent', 'Buyer Office', 'Buyer Office Agent', 'Third Party'];
    var GlbSixthTbl = [];
    MakePostRequest(base_path + 'orderentryvtwo/lotinspectiontwentyone', GlbParam + "&enqid=" + enquiryid, 'json', getTwentyoneTblDataRes);

    function getTwentyoneTblDataRes(data) {
        console.log(data,'data');
        if(data.ArrFromSecondTbl != "") {
            currentJxlTbl = data.ArrFromSecondTbl;
            console.log(currentJxlTbl,'currentJxlTbl');
        }
        if(data.remarks !== "") {
            document.getElementById("frmBasicRemarks").innerText = data.remarks;
        }
        if(data.jsonLotInspection != "") {
            currentJxlTbl = JSON.parse(data.jsonLotInspection);
        }
        $("#orderentryLotInspectionTwentyone").jexcel({
            data: currentJxlTbl,
            allowInsertColumn: false,
            //colWidths: [90,90,90, 80, 60, 90, 90, 90, 90, 80, 80, 80, 80, 70],
            columns: [
                {title: 'Combo / Colour', width: 120, readOnly: true},
                {title: 'P.O. No. / Enq. Ref. No.', width: 120, readOnly: true},
                {title: 'P.O. Qty. / Sample Qty.', width: 80, readOnly: true},
                {title: 'Pcs. / Set', width: 60, readOnly: true},
                {type: 'dropdown', title: 'Lot / Batch Size', source: LotSize, width: 120},
                {type: 'dropdown', title: 'Gen. / Spe.<br/>Insp. Level', source: gilevels, width: 80},
                //{type: 'dropdown', source: SiLevels},
                {type: 'text', title: 'Sample Size<br/>Code Letter', width: 80},
                {type: 'text', title: 'Sample<br/>Size', width: 67},
                {type: 'dropdown', title: 'Critical<br/>AQL', source: Aql, width: 70},
                //{title: 'Critical<br/>Acc / Rej',width: 70},
                {type: 'dropdown', title: 'Major<br/>AQL', source: Aql, width: 70},
                //{title: 'Major<br/>Acc / Rej',width: 70},
                {type: 'dropdown', title: 'Minor<br/>AQL', source: Aql, width: 70},
                //{title: 'Minor<br/>Acc / Rej',width: 70},
                {type: 'dropdown', title: 'Inspection<br/>Authority', source: GlbApprovingauthority, width: 120},
                //{type: 'dropdown', source: GlbApprovingauthority, width: 90},
                {type: 'calendar', title: 'F.I. Date', options: {format: 'DD/MM/YYYY'}, width: 90},
                {type: 'text', title: 'Remarks', width: 210},
            ],
            onchange:function () {
                unsaved = true;
            },
            updateTable:function(instance, cell, col, row, val, label, cellName) {
                if (col == 4) {
                    lotsizeUs = val;
                }
                if (col == 5) {
                    giLevelUs = val;
                }
                /*if (col == 6) {
                    siLevelUs = val;
                }*/
                if (col == 6) {
                    /*if (siLevelUs == '-') {
                        ssclUs = gi[lotsizeUs + giLevelUs];
                        $(cell).text(ssclUs);
                        instance.jexcel.options.data[row][col] = ssclUs;
                    }
                    else {

                    }*/
                    console.log(lotsizeUs,'lotsizeUs');
                    //console.log(siLevelUs,'siLevelUs');
                    console.log(gi,'gi');
                    sampleSizeCodeLetter = gi[lotsizeUs + giLevelUs];
                    $(cell).text(sampleSizeCodeLetter);
                    instance.jexcel.options.data[row][col] = sampleSizeCodeLetter;
                }
                if(col == 7) {
                    console.log(SampleSizeCl_SampleSizeGrp[sampleSizeCodeLetter]);
                    sampleSize = SampleSizeCl_SampleSizeGrp[sampleSizeCodeLetter];
                    $(cell).text(SampleSizeCl_SampleSizeGrp[sampleSizeCodeLetter]);
                    instance.jexcel.options.data[row][col] = SampleSizeCl_SampleSizeGrp[sampleSizeCodeLetter];
                }


            }

        });

    }
    function fnSaveChanges() {
        let data = JSON.stringify($("#orderentryLotInspectionTwentyone").jexcel('getData'));
        let remarks = $("#frmBasicRemarks").val();
        MakeAsynPostRequest(base_path + 'orderentryvtwo/saveLotInspection', GlbParam + "&d=" +
            encodeURIComponent(data) + "&enqid=" + enquiryid+"&e="+encodeURIComponent(remarks), 'json', fnSaveTableRes);
    }
    function fnSaveTableRes(data) {
        if (data != '') {
            console.log(data, 'data');
            if (data.errcode == 1) {
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