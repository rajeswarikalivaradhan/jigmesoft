<link rel="stylesheet" href="<?php echo base_url();?>assets/css/jexcel/jquery.jexcel.min.css" type="text/css" />
<!--<link rel="stylesheet" type="text/css" href="<?php /*echo base_url();*/?>assets/css/jquery.fancybox.min.css">-->
<?php
$this->load->view(CNFCOMPANY.'template/pageheader');
$ArrLoggedUserInfo      = fnGetUserLoggedInfo(1);
$VarUserType            = $ArrLoggedUserInfo['usertype'];
$VarProfilePermission   = $ArrLoggedUserInfo['pp'];
$ArrUserDetails         = fnGetUserLoggedInfo();
?>
<body class="sidebar-mini skin-black wysihtml5-supported sidebar-collapse">
<style>
    .mainheading {
        background-color: #bffff9;
    }
    table#sumofpw {
        color: green;
    }
    td.sizes {
        width: 60px !important; text-align: right;
    }
    .mtb25 {
        margin: 25px 0;
    }
     .box_likeformcontrol {
         display: block;
         width: 90px;
         height: 34px;
         padding: 6px 12px;
         font-size: 14px;
         line-height: 1.42857143;
         color: #555;
         background-color: #fff;
         background-image: none;
         border: 1px solid #ccc;
         border-radius: 4px;
         -webkit-box-shadow: inset 0 1px 1px rgba(0, 0, 0, .075);
         box-shadow: inset 0 1px 1px rgba(0, 0, 0, .075);
         -webkit-transition: border-color ease-in-out .15s, -webkit-box-shadow ease-in-out .15s;
         -o-transition: border-color ease-in-out .15s, box-shadow ease-in-out .15s;
         transition: border-color ease-in-out .15s, box-shadow ease-in-out .15s;

     }

    td.readonly { color: #808080 !important; }
</style>
<div class="wrapper">
    <?php $this->load->view(CNFCOMPANY.'template/templateheader');?>
    <aside class="main-sidebar">
        <?php $this->load->view(CNFCOMPANY.'template/templateleftmenu');?>
    </aside>
    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                Fabric Programme
<!--                <small>Programme</small>-->
            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">Fabric Programme</li>
            </ol>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-info">
<!--                        <div class="box-header with-border">
                            <h3 class="box-title">Tables</h3>
                            <div class="box-tools pull-right"></div>
                        </div>
-->                        <div class="box-body">
<!--<div id="log">log:</div>
                            <a href="//docs.google.com/gview?url=http://www.picssel.com/demos/downloads/Fancybox.doc&embedded=true" class="word">Clcik to open</a>-->
                            <form class="form-horizontal" id="frmFabricPgm" method="post">
                                <select id="frmOrderType">
                                    <option value="1">BULK</option>
                                    <option value="2">SAMPLING</option>
                                </select>
<!--                                <table class="table table-bordered table-responsive">
                                    <tr class="mainheading">
                                        <td class="text-left">
                                            <div><strong>FABRIC PIECE WISE CALCULATION</strong></div>
                                        </td>
                                    </tr>
                                </table>-->
                                <div id="fabricCalc"></div>
                                <!--<button id="calculatebtn" class="btn btn-info pull-right" type="submit" onclick="return fnTableCreate()">Calculation Table</button>-->
                                <table id="pieceweightheading" class="table table-bordered table-responsive">
                                    <tr class="mainheading"><td><strong>FABRIC PIECE WEIGHT CALCULATION</strong></td></tr>
                                </table>
                                <div id="testid"></div>
                                <table id="sumofpw" class="table table-bordered table-responsive hide">
                                    <tr>
                                        <td style="text-align: right; width:560px">SUM of Parts Piece Weight :</td>
                                        <td style="width: 60px;">Grams</td>
                                        <td class="sizes" id="xspieceweightsum"></td>
                                        <td class="sizes" id="spieceweight"></td>
                                        <td class="sizes">M</td>
                                        <td class="sizes">L</td>
                                        <td class="sizes">XL</td>
                                        <td class="sizes">XXL</td>
                                        <td class="sizes">3XL</td>
                                        <td class="sizes">4XL</td>
                                    </tr>
                                </table>
                                <button type="button" id="sumallpw" class="btn btn-info pull-right hide" onclick="return sumformula()">Sum</button>
                                <div class="box-footer nopadding">
                                    <!--<button type="button" class="btn btn-default" onclick="fnShowHideEndUserSub(1,'divShowBasicInfo');">Cancel</button>
                                    <button type="submit" class="btn btn-info pull-right  addrights" onclick="return fnSaveEnquiry();">Save Changes</button>-->
                                    <!--<button onclick="return fnCcCalc()" type="submit" class="btn btn-info pull-right">Consumption</button>-->
                                </div>
                            </form>
                        </div>


                        <div class="box-body">
                                <table class="table table-bordered table-responsive">
                                    <tr class="mainheading"><td><strong>FABRIC CONSUMPTION CALCULATION - P.O. / ENQ. Details</strong></td></tr>
                                </table>

                            <div id="fulldataforsample"></div>
                            <!--<button class="btn btn-info pull-right" onclick="fnCalcTable()" id="cCalcbtn">Calc Table</button>-->

                        </div>
                </div>
            </div>
            </div>
        </section>
    </div>
    <?php $this->load->view(CNFCOMPANY.'template/templatefooter');?>
    <div class="control-sidebar-bg"></div>
</div>
<script src="<?php echo base_url();?>assets/js/ajax.js"></script>
<script src="<?php echo base_url();?>assets/js/jexcel/jquery.jexcel.js"></script>
<script src="<?php echo base_url();?>assets/js/jexcel/numeral.min.js"></script>
<script src="<?php echo base_url();?>assets/js/jexcel/excel-formula.min.js"></script>
<script src="<?php echo base_url();?>assets/js/commonfunctions.js"></script>
<script>
    var GlbYarnCount = '[{"id":"1","name":"30\'s"},{"id":"2","name":"20\'s"}]';
</script>
<script src="<?php echo base_url()."assets/js/".CNFCOMPANY."mfabricprogramfinal.js"?>"></script>
<script>
    consumptionData();
</script>

<style>
    .table > tbody > tr > td, .table > tbody > tr > th, .table > tfoot > tr > td, .table > tfoot > tr > th, .table > thead > tr > td, .table > thead > tr > th {padding:5px;}
    .pd0{padding:0px;}
    .jexcel {white-space: normal !important;}
    .jexcel>tbody>tr>td {white-space: normal !important;text-align:left;word-wrap:break-word !important;}
</style>
<?php $this->load->view(CNFCOMPANY.'template/pagefooter');?>