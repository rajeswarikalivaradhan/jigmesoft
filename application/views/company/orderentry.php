<link rel="stylesheet" href="<?php echo base_url();?>assets/css/uploadfile-order.css" />
<?php
$this->load->view(CNFCOMPANY . 'template/pageheader');
function fnConvertObjIntoArrayKeyVal($VarKeyName,$VarValueName,$ArrSourceInfo) {
    $ArrFinalInfo   = array();
    foreach($ArrSourceInfo as $VarKey=>$ObjInfo) {
        $ArrFinalInfo[$ObjInfo->$VarKeyName] = $ObjInfo->$VarValueName;
    }
    return $ArrFinalInfo;
}
?>
<body class="sidebar-mini skin-black wysihtml5-supported sidebar-collapse">
<style>
    td div {
        font-family: Verdana, Geneva, sans-serif;
        font-size: 12px;
        line-height: 15px;
        /*padding: 5px 2px;*/
    }
    td {
        font-family: Verdana, Geneva, sans-serif;
        align: top;
    }
    table {
        margin-bottom: 0px !important;
    }
    .form-control {
        height: 25px;
    }
    .mainheading {
        background-color: #bffff9;
    }
    .secondheading {
        background-color: #e3f9f7;
        height:27px;
    }
    .pd3 {
        padding:3px !important;
    }
    .form-control {
        padding: 3px 2px !important;
        font-size:12px;
    }
    .no-border{
        border:0px !important;
    }
    #divEditBasicInfo {
        list-style-type:none;
        white-space:nowrap;
        overflow-x:auto;
    }
    .wdtp75{
        width:75%;
    }
    .wdtp25{
        width:25%;
    }

    .table > tbody > tr > td, .table > tbody > tr > th, .table > tfoot > tr > td, .table > tfoot > tr > th, .table > thead > tr > td, .table > thead > tr > th {padding:5px;}
    .cutratiocumdiv {width:561px;float:left;padding:5px;text-align: right;border:1px solid #eee;height:30px;}
    .cutratiocumeachdiv {width:45px;float:left;padding:5px;float:left;text-align: right;border:1px solid #eee;height:30px;}
    .cutratiocumpodiv {width:99px;float:left;padding:5px;float:left;text-align: right;border:1px solid #eee;height:30px;}
    .cutratiospecdiv {width:90px;float:left;padding:5px;float:left;text-align: right;border:1px solid #eee;height:30px;}

    .itemizedCutCummulative {width:591px;float:left;padding:5px;text-align: right;border:1px solid #eee;height:30px;}
    .itemizedCutRatio {width:91px;float:left;padding:5px;float:left;text-align: right;border:1px solid #eee;height:30px;}
    .itemizedCutPOQTY {width:120px;float:left;padding:5px;float:left;text-align: right;border:1px solid #eee;height:30px;}
    .sumcombosetqty1{width:763px;float:left;padding:5px;float:left;text-align: right;border:1px solid #eee;height:30px;}
    .sumcombosetqty2 {width:120px;float:left;padding:5px;float:left;text-align: right;border:1px solid #eee;height:30px;}
    .sumcombosetqty3 {width:380px;float:left;padding:5px;float:left;text-align: center;border:1px solid #eee;height:30px;}

    .jexcel>thead>tr>td {font-size:12px !important;}
    table {font-size:12px;}
    .pd0{padding:0px;}
    .jexcel {white-space: normal !important;}
    .jexcel>tbody>tr>td {white-space: normal !important; word-wrap:break-word !important;}
</style>
<div class="wrapper" >
    <?php $this->load->view(CNFCOMPANY . 'template/templateheader'); ?>
    <aside class="main-sidebar">
        <?php $this->load->view(CNFCOMPANY . 'template/templateleftmenu'); ?>
    </aside>
    <div class="content-wrapper" >
        <section class="content-header">
            <h1>Order Entry</h1>
            <ol class="breadcrumb">
                <li>
                    <a href="<?php echo base_url() . CNFCOMPANY ?>dashboard"><i class="fa fa-dashboard"></i>Home</a>                            </li>
                <li>
                    <a href="<?php echo base_url() . CNFCOMPANY ?>orderprocess/entry/">Manage Order Entry</a>
                </li>
                <li class="active">Add/Edit Orders Info.</li>
            </ol>
        </section>
        <section class="content">
            <div class="dropdown">
                <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Next Stage
                </button>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <?php //echo $VarOrderId;
                    if($VarOrderId >= 1) { ?>
                        <a class="dropdown-item" style="padding: 0 10px" href="<?php echo base_url().CNFCOMPANY."fabricprogram" ?>">Fabric Program</a>
                        <a class="dropdown-item" style="padding: 0 10px" href="<?php echo base_url().CNFCOMPANY."mcadrequest/addcadrequest/".urlencode(base64_encode($VarOrderId)) ?>">Add CAD Request</a>
                        <a class="dropdown-item" style="padding: 0 10px" href="<?php echo base_url().CNFCOMPANY."msamplerequest/addeditmerchantsample/".urlencode(base64_encode($VarOrderId)) ?>">Add Sample Request</a>
                        <?php
                    }
                    ?>
                    <a class="dropdown-item" style="padding: 0 10px" href="#">Something else here</a>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div id="DivContBasicInfo">
                        <div class="box box-info">
                            <div class="box-header with-border">
                                <h3 class="box-title">Order Information</h3>
                                <div class="box-tools pull-right">
                                    <?php if ($VarNewOrder == 0) { ?>
                                        <a class="btn btn-default btn-s addrights" href="javascript:void(0);" onclick="fnShowHideEndUserSub(1,'divEditBasicInfo');">
                                            <i class="fa fa-edit"></i>
                                            Edit
                                        </a>
                                    <?php } ?>
                                </div>
                            </div>
                            <div class="box-body" style="padding: 0px;">
                                <form class="form-horizontal" name="frmBasicInfo" id="frmOrderProcess" method="post">
                                    <div id="divEditBasicInfo" class="<?php if ($VarNewOrder == 1) { ?>show<?php } else { ?>hide<?php } ?>">
                                        <div class="alert alert-success alert-dismissable hide"      id="divSuccessBasicInfoMsg"> </div>
                                        <div class="alert alert-danger alert-dismissable hide"      id="ErrOrderEntry"> </div>
                                        <div class="col-md-12 pd0  no-padding">
                                            <table id="tableList" class="table table-bordered table-responsive">
                                                <tr>
                                                    <td colspan="9" class="mainheading no-padding">
                                                        <table id="tableList" class="table table-bordered table-responsive">
                                                            <tr>
                                                                <td width="25%" class="no-padding">
                                                                    <div class="mainheading" style="font-size: 14px">
                                                                        <strong><?php echo $ArrCompanyInfo['companyname'] ?></strong>
                                                                    </div>
                                                                    <p style="font-size: 12px">
                                                                        <?php echo $ArrCompanyInfo['address'] ?>
                                                                    </p>
                                                                </td>
                                                                <td width="50%" class="no-padding">
                                                                    <table id="tableList" class="table table-bordered table-responsive">
                                                                        <tr>
                                                                            <td class="secondheading">Merch. Name</td>
                                                                            <td>
                                                                                <input name="frmMerchantName" id="frmMerchantName" type="hidden" value="<?php echo $ArrMerchantDetailsById['id']?>" >
                                                                                <?php echo $ArrMerchantDetailsById['contactname']?>
                                                                            </td>
                                                                            <td class="secondheading">Team Name</td>
                                                                            <td>
                                                                                <select name="frmTeamName" class="form-control" id="frmTeamName" onchange="fnTeamDetails(this)">
                                                                                    <option value="">Choose Team</option>
                                                                                    <?php
                                                                                    foreach ($ArrTeamsData as $id => $team) {
                                                                                        ?>
                                                                                        <option value="<?php echo $team['id'] ?>"><?php echo $team['contactname']; ?></option>
                                                                                        <?php
                                                                                    }
                                                                                    ?>
                                                                                </select>
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td class="secondheading">Merch. Code</td>
                                                                            <td id="merchantCode">
                                                                                <?php echo $ArrMerchantDetailsById['code']?>
                                                                            </td>
                                                                            <td class="secondheading">Team Code</td>
                                                                            <td id="teamcode"></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td class="secondheading">Contact No.</td>
                                                                            <td id="merchantContactNo">
                                                                                <?php echo $ArrMerchantDetailsById['mobileno']?>
                                                                            </td>
                                                                            <td class="secondheading">Contact No.</td>
                                                                            <td id="mobileNo">&nbsp;</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td class="secondheading">E-Mail Id</td>
                                                                            <td id="merchantEmail">
                                                                                <?php echo $ArrMerchantDetailsById['emailid']?>
                                                                            </td>
                                                                            <td class="secondheading">E-Mail Id</td>
                                                                            <td id="emailId">&nbsp;</td>
                                                                        </tr>


                                                                    </table>
                                                                </td>
                                                                <td width="25%" class="no-padding">
                                                                    <table id="tableList" class="table table-bordered table-responsive">
                                                                        <tr>
                                                                            <td class="mainheading text-center" colspan="4" style="height:36px;"><strong>INTERNAL REFERENCE</strong></td>
                                                                        </tr>
                                                                        <?php
                                                                        $ArrISRIORText = unserialize(ARRISRIOR);
                                                                        if($ArrEnquiryDetails['reqforisrior']>=1) {?>
                                                                            <tr>
                                                                                <td class="secondheading">
                                                                                    <input type="hidden" id="frmOrderType" value="<?php echo $ArrEnquiryDetails['reqforisrior'] ?>">
                                                                                    <?php echo $ArrISRIORText[$ArrEnquiryDetails['reqforisrior']]?> No.
                                                                                </td>
                                                                                <td >
                                                                                    <div id="frmIorNumber"><?php echo $ArrEnquiryDetails['isriorcode']; ?></div>
                                                                                </td>

                                                                            </tr>
                                                                            <tr>
                                                                                <td class="secondheading">
                                                                                    Date & Time
                                                                                </td>
                                                                                <td >
                                                                                    <div><?php echo date('d-m-Y H:i:s') ?></div>
                                                                                </td>
                                                                            </tr>
                                                                        <?php } ?>
                                                                        <tr>
                                                                            <td class="mainheading" width="28%">Exchange Rate</td>
                                                                            <td>
                                                                                <label id="frmStaticExRate" style="padding: 3px 2px !important; width: 48.5%; border: 1px solid #ccc;">Static</label>
                                                                                <label id="frmDynamicExRate" style="padding: 3px 2px !important; width: 48.5%; border: 1px solid #ccc;">Dynamic</label>
                                                                            </td>
                                                                            <!--<td style="width: 32%;">1</td>
                                                                            <td style="width: 32%;">2</td></tr>-->
                                                                    </table>
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="9" class="mainheading">
                                                        <div><strong>ORDER DETAILS</strong></div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="9" class="no-padding">
                                                        <table id="tableList" class="table table-bordered table-responsive">
                                                            <tr>
                                                                <td class="no-padding wdtp75">
                                                                    <table id="tableList" class="table table-bordered table-responsive">
                                                                        <tr>
                                                                            <td class="secondheading">Order Ref. No.</td>
                                                                            <td>
                                                                                <?php echo $ArrEnquiryDetails['enquiryrefpono']?>
                                                                                <input type="hidden" name="frmOrderRefNo" id="frmOrderRefNo" class="form-control" value="<?php echo $ArrEnquiryDetails['enquiryrefpono']?>">
                                                                            </td>
                                                                            <td class="secondheading">Brand</td>
                                                                            <td>
                                                                                <?php
                                                                                $ArrBrandInfo = fnConvertObjIntoArrayKeyVal('id','brandname',$ArrBrands);
                                                                                echo $ArrBrandInfo[$ArrEnquiryDetails['brandbuyerid']]; ?>
                                                                                <input type="hidden" name="frmOrderBrands" id="frmOrderBrands" value="<?php echo $ArrEnquiryDetails['brandbuyerid']?>">
                                                                            </td>

                                                                            <td class="secondheading">Season</td>
                                                                            <td>
                                                                                <input type="text" name="frmOrderSeason" id="frmOrderSeason" class="form-control">
                                                                            </td>

                                                                            <td class="secondheading">Class</td>
                                                                            <td>
                                                                                <input type="text" name="frmOrderClass" id="frmOrderClass" class="form-control">
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td class="secondheading">Style Ref. No.</td>
                                                                            <td>
                                                                                <input type="hidden" name="frmStyleRefNo" id="frmStyleRefNo" class="form-control" value="<?php echo $ArrEnquiryDetails['stylenamerefno']?>">
                                                                                <?php echo $ArrEnquiryDetails['stylenamerefno']?>
                                                                            </td>
                                                                            <td class="secondheading">Buyer</td>
                                                                            <td>
                                                                                <input type="hidden" name="frmBuyerId" id="frmBuyerId" class="form-control">
                                                                                <?php echo $VarBuyerName;?>
                                                                            </td>
                                                                            <td class="secondheading">Div./Dept.</td>
                                                                            <td>
                                                                                <input type="text" name="frmOrderDivDept" id="frmOrderDivDept" class="form-control">
                                                                            </td>
                                                                            <td class="secondheading">Sub Class</td>
                                                                            <td>
                                                                                <input type="text" name="frmOrderSubClass" id="frmOrderSubClass" class="form-control">
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td class="secondheading">Style Name</td>
                                                                            <td colspan="7" style="padding:9px;">
                                                                                <input type="hidden" name="frmStyleName" id="frmStyleName" class="form-control" value="<?php echo $ArrEnquiryDetails['styledesc']?>">

                                                                            </td>
                                                                        </tr>
                                                                    </table>
                                                                </td>
                                                                <td class="no-padding wdtp25" >
                                                                    <table id="tableList" class="table table-bordered table-responsive">
                                                                        <tr>
                                                                            <td class="secondheading" colspan="2" style="height:36px;">Total Qty.</td>
                                                                            <td class="secondheading" colspan="2">Price Per Unit</td>
                                                                            <!--<td class="secondheading">Ex. Rate</td>-->
                                                                        </tr>
                                                                        <tr>
                                                                            <td width="20%">
                                                                                <input type="text" name="frmOrderTotalQty" id="frmOrderTotalQty" class="form-control">
                                                                            </td>
                                                                            <td width="27%">
                                                                                <select name="frmOrderPieceSet" id="frmOrderPieceSet" class="form-control ">
                                                                                    <option value="">Choose</option>
                                                                                    <option value="1">Pcs.</option>
                                                                                    <option value="2">Set</option>
                                                                                </select>
                                                                            </td>
                                                                            <td width="20%">
                                                                                <input type="text" name="frmOrderPricingUnit" id="frmOrderPricingUnit" class="form-control">
                                                                            </td>
                                                                            <td width="27%">
                                                                                <select name="frmOrderCurrency" id="frmOrderCurrency" onchange="fnGetCurrency(this.value)"  class="form-control">
                                                                                    <option value="">Choose</option>
                                                                                    <?php
                                                                                    foreach($ArrCurrencylist as $key => $currency) {
                                                                                        ?>
                                                                                        <option value="<?php echo $key ?>"><?php echo $currency ?></option>
                                                                                        <?php
                                                                                    }
                                                                                    ?>
                                                                                </select>
                                                                            </td>
                                                                            <!--<td width="20%">
                                                                                <label id="frmStaticExRate"></label>
                                                                                <label id="frmDynamicExRate"></label>
                                                                            </td>-->
                                                                        </tr>
                                                                        <tr>
                                                                            <td class="secondheading" width="26%">Payment Terms</td>
                                                                            <td colspan="3">
                                                                                <input type="text" name="frmPaymentTerms" id="frmPaymentTerms" class="form-control">
                                                                            </td>
                                                                        </tr>
                                                                    </table>
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="9"><div><strong>ORDER ENTRY</strong></div></td>
                                                </tr>
                                            </table>

                                            <div id="divSizeOrderEntryInfo">
                                                <table id="tableList" class="table table-bordered table-responsive no-border">
                                                    <tr class="mainheading">
                                                        <td colspan="9" class="text-left">
                                                            <div><strong>Configure Size Chart</strong></div>
                                                        </td>
                                                    </tr>
                                                    <?php
                                                    $ArrMasterChartInfo = array("1"=>"Size Chart 1","2"=>"Size Chart 2","3"=>"Size Chart 3","4"=>"Custom Size Chart");
                                                    $ArrSizeChartDetails   = array("1"=>array("1"=>"XXS","2"=>"XS","3"=>"S","4"=>"M","5"=>"L","6"=>"XL","7"=>"XXL","8"=>"2XL","9"=>"3XL","10"=>"4XL"),"2"=>array("1"=>"P44","2"=>"P50","3"=>"PR","4"=>"NB","5"=>"3M","6"=>"6M","7"=>"9M","8"=>"12M","9"=>"18M","10"=>"24M","11"=>"3T","12"=>"4T"),"3"=>array("1"=>"1","2"=>"2","3"=>"3","4"=>"4","5"=>"5","6"=>"6","7"=>"6X","8"=>"7","9"=>"8","10"=>"9","11"=>"10","12"=>"11","13"=>"12"),'4'=>array("1"=>"13","2"=>"14","3"=>"15","4"=>"16","5"=>"18","6"=>"20","7"=>"22","8"=>"24","9"=>"26","10"=>"28","11"=>"30","12"=>"32","13"=>"34"),'5'=>array("1"=>"36","2"=>"38","3"=>"40","4"=>"42","5"=>"44","6"=>"46","7"=>"48","8"=>"50","9"=>"52","10"=>"54","11"=>"56","12"=>"58","13"=>"60"),'6'=>array("1"=>"70","2"=>"80","3"=>"90","4"=>"100","5"=>"110","6"=>"120","7"=>"130","8"=>"140","9"=>"150","10"=>"160","11"=>"170"));
                                                    ?>
                                                    <tr>
                                                        <td colspan="9" class="text-left">
                                                            <div class="col-sm-12">
                                                                <div class="form-group">
                                                                    <label for="inputEmail3" class="col-sm-2 control-label">Chart Type</label>
                                                                    <div class="col-sm-10">
                                                                        <select name="frmOrderSizeChartList" id="frmOrderSizeChartList" class="form-control" onchange="fnShowSubChartInfo(this.value);">
                                                                            <option value="">Choose the Size Chart</option>
                                                                            <?php foreach($ArrMasterChartInfo as $VarMasterChartId=>$VarMasterChartName) {?>
                                                                                <option value="<?php echo $VarMasterChartId?>"><?php echo $VarMasterChartName?></option>
                                                                            <?php }?>
                                                                        </select>
                                                                        <div class="herr" id="ErrOrderSizeChartList"></div>
                                                                    </div>
                                                                </div>

                                                                <div class="form-group">
                                                                    <label for="inputEmail3" class="col-sm-2 control-label">Chart List</label>
                                                                    <div class="col-sm-10" id="divSubChartList">

                                                                    </div>
                                                                </div>

                                                                <div class="box-footer nopadding">
                                                                    <button type="button" id="sizeChartContinueBtn" class="btn btn-info pull-right addrights" onclick="fnGotoOrderEntry(1,'divNextOrderEntryInfo');">Continue</button>
                                                                </div>

                                                            </div>

                                                        </td>
                                                    </tr>
                                                </table>
                                            </div>

                                            <div id="divNextOrderEntryInfo" class="hide">
                                                <table id="tableList" class="table table-bordered table-responsive">
                                                    <tr class="mainheading">
                                                        <td colspan="9" class="text-left">
                                                            <div><strong>Size Chart Info</strong></div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="9">
                                                            <div class="col-sm-12">
                                                                <div class="form-group">
                                                                    <label for="inputEmail3" class="col-sm-2 text-right">Chart Type</label>
                                                                    <div class="col-sm-10" id="divDispMasterChartType">
                                                                    </div>
                                                                </div>

                                                                <div class="form-group">
                                                                    <label for="inputEmail3" class="col-sm-2 text-right">Chart List</label>
                                                                    <div class="col-sm-10" id="divDispFinalChartInfo">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr class="mainheading">
                                                        <td colspan="9" class="text-left">
                                                            <div><strong>COMBO WISE / COLOUR WISE QTY. BREAK-UP</strong></div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="9" class="no-padding no-border">
                                                            <div id="divComboColorFirstTable" class="pd0"></div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="9" class="pd3 no-border text-left">
                                                            <div style="float: left; padding: 4px; text-align: right; width: 628px"><strong>Total Qty.:</strong></div>
                                                            <strong><div id="divComboColorFirstTableTotal" style="border: 1px solid #ccc; float: left; padding: 4px; text-align: right; width: 100px">&nbsp;</div></strong>
                                                            <strong><div id="divComboColorFirstTablePackType" style="border: 1px solid #ccc; float: left; padding: 4px; text-align: right; width: 100px">&nbsp;</div></strong>
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <td colspan="9" class="pd3 no-border">
                                                            <label>Remarks :</label>
                                                            <textarea id="comboColorWiseRemarks" class="form-control"></textarea>
                                                        </td>
                                                    </tr>
                                                    <tr class="mainheading">
                                                        <td colspan="9" class="text-left">
                                                            <div><strong>P.O. WISE QTY. BREAK-UP</strong></div>
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <td colspan="9" class="no-padding no-border">
                                                            <div id="PoWiseQtyBrkUpSecondTbl" class="pd0"></div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="pd3 no-border text-left">
                                                            <div id="PoWiseQtyBrkUpTotal" style="float: left; padding: 5px; text-align: right; width: 834px"><strong>Total Qty.:</strong></div>
                                                            <strong><div id="PoQtySampleQtyTotal" style="border: 1px solid #ccc; float: left; padding: 4px; text-align: right; width: 93px">&nbsp;</div></strong>
                                                            <strong><div id="PoWiseQtyBrkUpPackType" style="border: 1px solid #ccc; float: left; padding: 4px; text-align: right; width: 100px">&nbsp;</div></strong>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="9" class="pd3 no-border text-left">
                                                            <div id="uploadBusinssImg" class="pdt10"></div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-left">
                                                            <!--<div id="loading"><img src="<?php /*echo base_url()."assets/img/fullpage.gif" */?>" alt="loading.."></div>-->
                                                            <button type="button" class="btn btn-info pull-right addrights" onclick="fnFirstSavePoWiseQtyBkpUp()">Save</button>
                                                        </td>
                                                    </tr>

                                                    <tr class="mainheading">
                                                        <td colspan="9" class="text-left">
                                                            <div style='background-color: #bffff9;'><strong>P.O. WISE ITEMIZED QTY. BREAK-UP</strong></div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="9" class="no-padding no-border">
                                                            <div id="PoWiseItemizedQtyThirdTbl" class="pd0"></div>
                                                            <!--<div id="divItemizedEntryTable" class="pd0"></div>-->
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="9" class="no-padding no-border">
                                                            <div style="float: left; padding: 5px; text-align: right; width: 1130px"><strong>Total Itemized Qty.:</strong></div>
                                                            <strong><div id="ItemizedQtyTotal" style="border: 1px solid #ccc; float: left; padding: 4px; text-align: right; width: 93px">&nbsp;</div></strong>
                                                        </td>
                                                    </tr>
                                                    <tr class="mainheading">
                                                        <td colspan="9" class="text-left">
                                                            <div><strong> P.O. WISE DELIVERY SCHEDULE </strong></div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="9" class="no-padding">
                                                            <div id="PoWiseDeliverySchdFourthTbl" style="overflow-x: scroll; width: 1265px;"></div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="9" class="no-padding">
                                                            <label>Remarks:</label>
                                                            <textarea id="comboColorWiseRemarks" class="form-control"></textarea>

                                                        </td>
                                                    </tr>
                                                    <tr class="mainheading">
                                                        <td class="text-left">
                                                            <div style="">
                                                                <strong>ITEMIZED P.O. WISE / SIZE WISE: QUANTITY BREAK-UP</strong>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="no-padding no-border">
                                                            <div id="ItemizedSizeWiseQtyBrkUpFifthTbl"></div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="no-padding no-border">
                                                            <label>Remarks:</label>
                                                            <textarea id="SizeWiseQtyBreakupReamrks" class="form-control"></textarea>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="no-padding no-border">
                                                            <button class="btn btn-info pull-right addrights" type="button" onclick="fnNewCummulative()">Fill Cumulative</button>
                                                        </td>
                                                    </tr>
                                                    <tr class="mainheading">
                                                        <td class="text-left">
                                                            <div style="">
                                                                <strong>CUMULATIVE - SIZE SPEC CODE / FIT</strong>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="no-padding no-border">
                                                            <div id="SixthATbl"></div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="9" class="no-padding no-border">
                                                            <button class="btn btn-info pull-right addrights" type="button" onclick="SizeWiseQtyBreakupFifthTbl()">Fill CuttingRatio</button>
                                                        </td>
                                                    </tr>
                                                    <tr class="mainheading">
                                                        <td class="text-left">
                                                            <div style="">
                                                                <strong>ITEMIZED P.O. WISE / SIZE WISE: CUTTING RATIO</strong>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="no-padding no-border">
                                                            <div id="CuttingRatioSixthTbl"></div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="no-padding no-border">
                                                            <label>Remarks:</label>
                                                            <textarea id="divCuttingRatioTableRemarks" class="form-control"></textarea>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="no-padding no-border">
                                                            <button type="button" class="btn btn-info pull-right addrights" onclick="fnSaveItemizedQtyBPDeShSizeWiseQtyBP()">Save</button>
                                                        </td>
                                                    </tr>

                                                    <tr class="mainheading">
                                                        <td colspan="9" class="text-left">
                                                            <div><strong>FABRIC DETAILS : KNIT</strong></div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="9" class="no-padding">
                                                            <div id="divFabricKnitTable"></div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="9" class="no-padding">
                                                            <label>Remarks:</label>
                                                            <textarea id="comboColorWiseRemarks" class="form-control"></textarea>

                                                        </td>
                                                    </tr>
                                                    <tr class="mainheading">
                                                        <td colspan="9" class="text-left">
                                                            <div><strong>FABRIC DETAILS : WOVEN</strong></div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="9" class="no-padding">
                                                            <div id="divFabricWoven"></div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="9" class="no-padding">
                                                            <label>Remarks:</label>
                                                            <textarea id="comboColorWiseRemarks" class="form-control"></textarea>

                                                        </td>
                                                    </tr>
                                                    <tr class="mainheading">
                                                        <td colspan="9" class="text-left">
                                                            <div><strong>DYEING DETAILS</strong></div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="9" class="no-padding">
                                                            <div id="divDyingDetails"></div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="9" class="no-padding">
                                                            <label>Remarks:</label>
                                                            <textarea id="dyingRemarks" name="dyingRemarks" class="form-control"></textarea>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="9" class="text-left">
                                                            <div id="uploadBusinssImg" class="pdt10"><div class="ajax-upload-dragdrop" style="vertical-align:top;width:100%"><div class="ajax-file-upload" style="position: relative; overflow: hidden; cursor: default;">Upload<form method="POST" action="http://app.garmenplus.com/company/orderprocess1/dropbox" enctype="multipart/form-data" style="margin: 0px; padding: 0px;"><input type="file" id="ajax-upload-id-1542449500019" name="bimage[]" accept="*" multiple="" style="position: absolute; cursor: pointer; top: 0px; width: 100%; height: 100%; left: 0px; z-index: 100; opacity: 0;"></form></div><span><b>Drag &amp; Drop Files</b></span></div><div></div></div><div class="ajax-file-upload-container"></div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="no-padding no-border">
                                                            <button type="button" class="btn btn-info pull-right addrights" onclick="FnSaveKnitWovenDyeingTbl()">Save</button>
                                                        </td>
                                                    </tr>
                                                    <tr class="mainheading">
                                                        <td colspan="9" class="text-left">
                                                            <div><strong>EMBELLISHMENT DETAILS</strong></div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="9" class="no-padding">
                                                            <div id="divEmbellishment"></div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="9" class="no-padding">
                                                            <label>Remarks:</label>
                                                            <textarea id="embellRemarks" name="embellRemarks" class="form-control"></textarea>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="9" class="text-left">
                                                            <div id="uploadBusinssImg" class="pdt10"><div class="ajax-upload-dragdrop" style="vertical-align:top;width:100%"><div class="ajax-file-upload" style="position: relative; overflow: hidden; cursor: default;">
                                                                        Upload
                                                                        <form method="POST" action="http://app.garmenplus.com/company/orderprocess1/dropbox" enctype="multipart/form-data" style="margin: 0px; padding: 0px;">
                                                                            <input type="file" id="ajax-upload-id-1542449500019" name="bimage[]" accept="*" multiple="" style="position: absolute; cursor: pointer; top: 0px; width: 100%; height: 100%; left: 0px; z-index: 100; opacity: 0;">
                                                                        </form>
                                                                    </div><span><b>Drag &amp; Drop Files</b></span></div><div></div></div><div class="ajax-file-upload-container"></div>
                                                        </td>
                                                    </tr>
                                                    <tr class="mainheading">
                                                        <td colspan="9" class="text-left">
                                                            <div><strong>BOM DETAILS</strong></div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="9" class="no-padding">
                                                            <div id="divBomDetails"></div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="9" class="no-padding">
                                                            <label>Remarks:</label>
                                                            <textarea id="bomRemarks" name="bomRemarks" class="form-control"></textarea>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="no-padding no-border">
                                                            <button type="button" class="btn btn-info pull-right addrights" onclick="FnFillBomConsolidatedTbl()">Fill Bom Consolidated</button>
                                                        </td>
                                                    </tr>

                                                    <tr class="mainheading">
                                                        <td colspan="9" class="text-left">
                                                            <div><strong>BOM CONSOLIDATED</strong></div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="9" class="no-padding no-border">
                                                            <div id="divBomConsolidated" style="overflow: auto"></div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="9" class="pd3 no-border ">
                                                            <div><strong>Remarks</strong></div>
                                                            <textarea id="bomConsolidatedRemarks" name="bomConsolidatedRemarks" class="form-control"></textarea>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="no-padding no-border">
                                                            <button type="button" class="btn btn-info pull-right addrights" onclick="FnFillBomSampleSourcingApprovalTbl()">Fill Bom Sampling,Sourcing and approval</button>
                                                        </td>
                                                    </tr>

                                                    <tr class="mainheading">
                                                        <td colspan="9" class="text-left">
                                                            <div><strong>BOM SAMPLE, SOURCING & APPROVAL DETAILS</strong></div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="9" class="no-padding">
                                                            <div id="divBomSrcApproveDetails"></div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="9" class="no-padding">
                                                            <label>Remarks:</label>
                                                            <textarea id="divBomSrcApproveRemarks" name="divBomSrcApproveRemarks" class="form-control"></textarea>
                                                        </td>
                                                    </tr>
                                                    <tr class="mainheading">
                                                        <td colspan="9" class="text-left">
                                                            <div><strong>COMPLETE GARMENT PROCESS FLOW (Cutting to Finishing)</strong></div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="9" class="no-padding">
                                                            <div id="divCgpf"></div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="9" class="no-padding">
                                                            <label>Remarks:</label>
                                                            <textarea id="divCgpfRemarks" name="divCgpfRemarks" class="form-control"></textarea>
                                                        </td>
                                                    </tr>
                                                    <tr class="mainheading">
                                                        <td colspan="9" class="text-left">
                                                            <div><strong>GARMENT SAMPLING DETAILS</strong></div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="9" class="no-padding">
                                                            <div id="divSamplingDetails"></div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="9" class="no-padding">
                                                            <label>Remarks:</label>
                                                            <textarea id="SamplingDetailsRemarks" name="SamplingDetailsRemarks" class="form-control"></textarea>
                                                        </td>
                                                    </tr>
                                                    <tr class="mainheading">
                                                        <td colspan="9" class="text-left">
                                                            <div><strong>LAB TESTING DETAILS : ARTICLE 1</strong></div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="9" class="no-padding">
                                                            <div id="divLabTest"></div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="9" class="no-padding">
                                                            <label>Remarks:</label>
                                                            <textarea id="bomLabRemarks" name="bomLabRemarks" class="form-control"></textarea>
                                                        </td>
                                                    </tr>
                                                    <tr class="mainheading">
                                                        <td class="text-left">
                                                            <div><strong>LAB TESTING DETAILS : ARTICLE 2</strong></div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="no-padding">
                                                            <div id="divLabTestFreeText"></div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="no-padding">
                                                            <label>Remarks:</label>
                                                            <textarea id="bomLabRemarks" name="bomLabRemarks" class="form-control"></textarea>
                                                        </td>
                                                    </tr>

                                                    <tr class="mainheading">
                                                        <td>
                                                            <div><strong>PACKING DETAILS</strong></div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="9" class="no-padding">
                                                            <div id="divPackingDetails"></div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="9" class="no-padding">
                                                            <label>Remarks:</label>
                                                            <textarea id="bomLabRemarks" name="bomLabRemarks" class="form-control"></textarea>
                                                        </td>
                                                    </tr>

                                                    <tr class="mainheading">
                                                        <td colspan="9" class="text-left">
                                                            <div><strong>MASTER BAG ASSORTMENT RATIO</strong></div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="9" class="no-padding">
                                                            <div id="divMasterBagAssortmentRatio"></div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="9" class="no-padding">
                                                            <label>Remarks:</label>
                                                            <textarea id="bomLabRemarks" name="bomLabRemarks" class="form-control"></textarea>
                                                        </td>
                                                    </tr>
                                                    <tr class="mainheading">
                                                        <td colspan="9" class="text-left">
                                                            <div><strong>CARTON ASSORTMENT RATIO</strong></div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="9" class="no-padding">
                                                            <div id="divCartonAssortmentRatio"></div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="9" class="no-padding">
                                                            <label>Remarks:</label>
                                                            <textarea id="bomLabRemarks" name="bomLabRemarks" class="form-control"></textarea>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="no-padding no-border">
                                                            <button type="button" class="btn btn-info pull-right addrights" onclick="FnQtyPerCarton()">Fill Quantity Per Carton</button>
                                                        </td>
                                                    </tr>
                                                    <tr class="mainheading">
                                                        <td colspan="9" class="text-left">
                                                            <div><strong>Quantity Per Carton</strong></div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="9" class="no-padding">
                                                            <div id="QtyperCarton"></div>
                                                        </td>
                                                    </tr>

                                                    <tr class="mainheading">
                                                        <td colspan="9" class="text-left">
                                                            <div><strong>LOT FINAL INSPECTION DETAILS</strong></div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="9" class="no-padding">
                                                            <div id="divLotInspection"></div>
                                                        </td>
                                                    </tr>
                                                    <tr class="mainheading">
                                                        <td colspan="9" class="text-left">
                                                            <div><strong>DOCUMENTATION & LOGISTICS DETAILS</strong></div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="9" class="no-padding">
                                                            <div id="divInvoiceAndDoc"></div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="9" class="no-padding">
                                                            <label>Remarks:</label>
                                                            <textarea id="bomInvoiceRemarks" name="bomInvoiceRemarks" class="form-control"></textarea>
                                                        </td>
                                                    </tr>
                                                    <tr class="mainheading">
                                                        <td colspan="9" class="text-left">
                                                            <div><strong>MERCHANT CHECKLIST</strong></div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="9" class="no-padding">
                                                            <div id="divMerchantCheckList"></div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="9" class="no-padding">
                                                            <label>Remarks:</label>
                                                            <textarea id="bomMercRemarks" name="bomMercRemarks" class="form-control"></textarea>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </div>

                                        </div>
                                        <div class="clearfix"></div>
                                        <div id="ErrMsg" class="text-danger"></div>
                                        <div class="box-footer nopadding" id="divSaveOrderBtn">
                                            <button type="button" class="btn btn-default" onclick="fnShowHideEndUserSub(1,'divShowBasicInfo');">Cancel</button>
                                            <button type="submit" class="btn btn-info pull-right addrights" onclick="return fnSaveOrderEntry();">Save Changes</button>
                                        </div>
                                    </div>
                                    <!--<div id="divShowBasicInfo" class="<?php /*if ($VarNewOrder == 1) { */?>hide<?php /*} */?>"> &nbsp;</div>-->
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
<script src="<?php echo base_url();?>assets/js/ajax.js"></script>
<script src="<?php echo base_url();?>assets/js/jexcel/spectrum.min.js"></script>
<link rel="stylesheet" href="<?php echo base_url();?>assets/css/jexcel/spectrum.min.css" type="text/css" />
<script src="<?php echo base_url();?>assets/js/jexcel/jquery.jexcel.js"></script>
<link rel="stylesheet" href="<?php echo base_url();?>assets/css/jexcel/jquery.jexcel.min.css" type="text/css" />
<script src="<?php echo base_url();?>assets/js/jexcel/jquery.jcalendar.js"></script>
<link rel="stylesheet" href="<?php echo base_url();?>assets/css/jexcel/jquery.jcalendar.min.css" type="text/css" />
<style>
    .table > tbody > tr > td, .table > tbody > tr > th, .table > tfoot > tr > td, .table > tfoot > tr > th, .table > thead > tr > td, .table > thead > tr > th {padding:5px;}
    .cutratiocumdiv {width:561px;float:left;padding:5px;text-align: right;border:1px solid #eee;height:30px;}
    .cutratiocumeachdiv {width:45px;float:left;padding:5px;float:left;text-align: right;border:1px solid #eee;height:30px;}
    .cutratiocumpodiv {width:99px;float:left;padding:5px;float:left;text-align: right;border:1px solid #eee;height:30px;}
    .cutratiospecdiv {width:90px;float:left;padding:5px;float:left;text-align: right;border:1px solid #eee;height:30px;}

    .itemizedCutCummulative {width:591px;float:left;padding:5px;text-align: right;border:1px solid #eee;height:30px;}
    .itemizedCutRatio {width:91px;float:left;padding:5px;float:left;text-align: right;border:1px solid #eee;height:30px;}
    .itemizedCutPOQTY {width:120px;float:left;padding:5px;float:left;text-align: right;border:1px solid #eee;height:30px;}
    .sumcombosetqty1{width:763px;float:left;padding:5px;float:left;text-align: right;border:1px solid #eee;height:30px;}
    .sumcombosetqty2 {width:120px;float:left;padding:5px;float:left;text-align: right;border:1px solid #eee;height:30px;}
    .sumcombosetqty3 {width:380px;float:left;padding:5px;float:left;text-align: center;border:1px solid #eee;height:30px;}

    .jexcel>thead>tr>td {font-size:12px !important;}
    table {font-size:12px;}
    .pd0{padding:0px;}
    .jexcel {white-space: normal !important;}
    .jexcel>tbody>tr>td {white-space: normal !important; word-wrap:break-word !important;}
</style>
<script src="<?php echo base_url();?>assets/js/jquery.uploadfile-order.js?s=<?php echo str_rand(5)?>"></script>

<script src="<?php echo base_url();?>assets/plugins/excelspectrum/spectrum.min.js"></script>
<link rel="stylesheet" href="<?php echo base_url();?>assets/plugins/excelspectrum/spectrum.min.css" type="text/css" />
<script type="text/javascript">
    var GlbPortdata = '<?php echo $ArrPortsData ?>';
    var GlbPortNameCity = '<?php echo $ArrPortNameCity ?>';
    //var GlbKnit = '<?php //echo $Knit ?>';
    var GlbKnitFabricBlend = '<?php echo $ArrKnitFabricBlend ?>';
    var GlbKnitFabricContent = '<?php echo $ArrKnitFabricContent ?>';
    var GlbKnitFabricName = '<?php echo $ArrKnitFabricName ?>';
    var GlbWoven = '<?php echo $Woven ?>';
    var GlbDsr = '<?php echo $ArrDsr ?>';
    var GlbEmbell = '<?php echo $ArrEmbell ?>';
    var GlbBom = '<?php echo $ArrBom ?>';
    var GlbGpdata = '<?php echo $ArrGarmentpartsInfo ?>';
    var GlbUnitmeasure = '<?php echo $ArrUnitMeasure ?>';
    var GlbYarnReqData = '<?php echo $ArrYarnReqData ?>';
    var GlbYarnCount = '<?php echo stripslashes($ArrYarnCount) ?>';
    var GlbLabTestDesc = '<?php echo $ArrLabTestDesc ?>';
    var GlbLabAcceptLevel = '<?php echo $ArrLabAcceptLevel ?>';
    var GlbChecklist = '<?php echo $ArrChecklistDetails ?>';
    var GlbProcessFlow = '<?php echo $ArrProcessFlow ?>';
    var GlbPackingtype = '<?php echo $ArrPackingtype ?>';
    var GlbPackCode = '<?php echo $ArrPackingCode ?>';
    var GlbInspection = '<?php echo $ArrInspectionData ?>';
    var GlbModeOfShipment = '<?php echo $ArrModeOfShipment ?>';
    var GlbBomSrcCat = '<?php echo $ArrBomSrcCat ?>';
    var GlbBomSrcDetails = '<?php echo $ArrBomSrcDetails ?>';
    var GlbBomSupplier = '<?php echo $ArrBomSupplier ?>';
    var GlbBomApproval = '<?php echo $ArrYesNo ?>';
    //var GlbDyeingType = '<?php //echo $ArrDyeingType ?>';
    var GlbArrConsignor = '<?php echo $ArrConsignor ?>';
    var GlbArrForwarding = '<?php echo $ArrForwarding ?>';
    var GlbArrClearing = '<?php echo $ArrClearing ?>';
    var GlbArrImporter = '<?php echo $ArrImporter ?>';
    var GlbArrConsignee = '<?php echo $ArrConsignee ?>';
    var GlbFabricFinish = '<?php echo $ArrFabricFinish ?>';
    var GlbFabricFFStageForm = '<?php echo $ArrFFStageForm ?>';
    var GlbDyeFabricDetails = '<?php echo $ArrDyeFabricDetails ?>';
    var GlbPackingMaterials = '<?php echo $ArrPackingMaterials ?>';
    var GlbSamplingReq = '<?php echo $ArrSamplingReq ?>';
    var GlbColormatchstd = '<?php echo $ArrColourMatch ?>';
    var GlbAuthority = '<?php echo $ArrAuthorities ?>';
</script>
<script src="<?php echo base_url();?>assets/js/commonfunctions.js"></script>
<!--<script src="<?php /*echo base_url();*/?>assets/js/loadingoverlay.min.js"></script>-->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.3/jquery.mask.min.js"></script>
<script src="<?php echo base_url();?>assets/js/<?php echo CNFCOMPANY ?>orderentry.js?rn=<?php echo str_rand(5)?>"></script>
<?php $this->load->view(CNFCOMPANY . 'template/pagefooter'); ?>