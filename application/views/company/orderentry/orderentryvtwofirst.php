<?php $this->load->view(CNFCOMPANY . 'template/pageheader'); ?>
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/uploadfile-order.css"/>
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/jexcel4/jsuites.css"/>
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/jexcel4/jexcel.css"/>
    <style type="text/css">
    </style>
    <body class="sidebar-mini skin-black wysihtml5-supported sidebar-collapse">
<div class="wrapper">
    <?php $this->load->view(CNFCOMPANY . 'template/templateheader'); ?>
    <aside class="main-sidebar">
        <?php $this->load->view(CNFCOMPANY . 'template/templateleftmenu'); ?>
    </aside>
    <div class="content-wrapper order-entry">
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
                            <div class="box-body" style="padding: 0px;">
                                <form class="form-horizontal" name="frmBasicInfo" id="frmOrderProcess" method="post">
                                    <div class="col-md-12 pd0 no-padding">
                                        <?php $this->load->view(CNFCOMPANY . "orderentry/pagination_links"); ?>
                                        <!--new design-->
                                        <table id="newOrderEntrycommonTbl" class="table table-responsive">
                                            <tr>
                                                <td style="width: 300px">
                                                    <table class="table table-responsive">
                                                        <tr>
                                                            <td class="pinkHeading">
                                                                <strong><?php echo $ArrCommonHeaderData['companyName']; ?></strong>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td style="padding: 0 10px"><?php echo $ArrCommonHeaderData['companyAddress']; ?></td>
                                                        </tr>
                                                    </table>
                                                </td>
                                                <td>
                                                    <table class="table table-responsive">
                                                        <tr>
                                                            <td class="secondheading"
                                                                style="width: 75px; padding-left: 10px; padding-top: 7px">
                                                                Merch. Name
                                                            </td>
                                                            <td style="width: 75px; padding-left: 10px; padding-top: 7px">
                                                                <?php echo @$ArrCommonHeaderData['merchantName']
                                                                //echo '<pre>'; print_r($ArrTeamsData); die('');
                                                                ?>
                                                            </td>
                                                            <td class="secondheading"
                                                                style="width: 75px; padding-left: 10px; padding-top: 7px">
                                                                Team Name
                                                            </td>
                                                            <td style="width: 75px; padding-left: 10px">
                                                                <?php echo @$ArrCommonHeaderData['ArrTeam']['contactname']; ?>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="secondheading" style="padding-left: 10px">Merch.
                                                                Code
                                                            </td>
                                                            <td id="merchantCode"
                                                                style="width: 75px; padding-left: 10px">
                                                                <?php echo @$ArrCommonHeaderData['merchantCode'] ?>
                                                            </td>
                                                            <td class="secondheading" style="padding-left: 10px">Team
                                                                Code
                                                            </td>
                                                            <td id="teamcode"
                                                                style="padding-left: 10px"><?php echo @$ArrCommonHeaderData['ArrTeam']['code'] ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="secondheading" style="padding-left: 10px">Contact
                                                                No.
                                                            </td>
                                                            <td id="merchantContactNo"
                                                                style="width: 75px; padding-left: 10px">
                                                                <?php echo @$ArrCommonHeaderData['merchantMobile'] ?>
                                                            </td>
                                                            <td class="secondheading" style="padding-left: 10px">Contact
                                                                No.
                                                            </td>
                                                            <td id="mobileNo" style="padding-left: 10px">
                                                                <?php echo @$ArrCommonHeaderData['ArrTeam']['mobile']; ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="secondheading" style="padding-left: 10px">E-Mail
                                                                Id
                                                            </td>
                                                            <td id="merchantEmail" style="padding-left: 10px">
                                                                <?php echo @$ArrCommonHeaderData['merchantEmail'] ?>
                                                            </td>
                                                            <td class="secondheading" style="padding-left: 10px">E-Mail
                                                                Id
                                                            </td>
                                                            <td id="emailId"
                                                                style="padding-left: 10px"><?php echo @$ArrCommonHeaderData['ArrTeam']['username']; ?></td>
                                                        </tr>
                                                    </table>
                                                </td>
                                                <td>
                                                    <table class="table table-responsive">
                                                        <tr>
                                                            <td colspan="4" align="center" class="pinkHeading"><b>INTERNAL
                                                                    REFERENCE NO.</b></td>
                                                        </tr>
                                                        <?php $ArrISRIORText = unserialize(ARRISRIOR);
                                                        if (!empty($ArrCommonHeaderData['ArrEnquiryDetails']['reqforisrior'])) {
                                                            if ($ArrCommonHeaderData['ArrEnquiryDetails']['reqforisrior'] >= 1) { ?>
                                                                <tr>
                                                                    <td class="secondheading"
                                                                        style="width: 75px; padding-left: 10px">WIP No.
                                                                    </td>
                                                                    <td id="frmIorNumber" colspan="3"
                                                                        style="width: 75px; padding-left: 10px">
                                                                        <?php echo $ArrCommonHeaderData['ArrEnquiryDetails']['isriorcode']; ?>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td class="secondheading"
                                                                        style="width: 75px; padding-left: 10px">
                                                                        Date & Time
                                                                    </td>
                                                                    <td style="width: 75px; padding-left: 10px"
                                                                        colspan="3">
                                                                        <?php
                                                                        echo isset($ArrCommonData->datecreated) ? date('d-m-Y H:i:s', strtotime($ArrCommonData->datecreated)) : date('d-m-Y H:i:s');
                                                                        ?>
                                                                    </td>
                                                                </tr>
                                                            <?php }
                                                        }
                                                        ?>
                                                        <tr>
                                                            <td class="secondheading"
                                                                style="width: 75px; padding-left: 10px; padding-top: 7px">
                                                                Exc. Rate - Static
                                                            </td>
                                                            <td style="width: 60px; padding-left: 10px; padding-right: 10px">
                                                                <input type="text" id="frmExcRateAtBooking"
                                                                       class="form-control"
                                                                       style="height: 24px; padding-left: 10px !important; padding-top: 7px"
                                                                       value="<?php if (!empty($ArrCommonData->orderbookingrate)) echo $ArrCommonData->orderbookingrate ?>">
                                                            </td>
                                                            <td class="secondheading"
                                                                style="width: 75px; padding-left: 10px; padding-top: 7px; padding-right: 10px">
                                                                Dynamic
                                                            </td>
                                                            <td style="width: 60px; padding-left: 10px; padding-right: 6px">
                                                                <input type="text" id="frmExcRateOrderRealization"
                                                                       class="form-control"
                                                                       style="height: 24px; padding-left: 10px !important; padding-top: 7px;"
                                                                       value="<?php if (!empty($ArrCommonData->orderrealization)) echo $ArrCommonData->orderrealization ?>">
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                        </table>
                                        <table class="table table-responsive">
                                            <tr>
                                                <div
                                                    style="background-color: rgb(210 253 249); padding-top: 10px; padding-left: 10px">
                                                    <strong>ORDER DETAILS</strong>
                                                </div>
                                            </tr>
                                        </table>
                                        <table class="table table-responsive" style="margin: 5px">
                                            <tr>
                                                <td class="secondheading"
                                                    style="width: 100px; padding-left: 10px; padding-top: 10px">Order
                                                    Ref. No.
                                                </td>
                                                <td style="width: 235px; padding-left: 10px; padding-top: 10px"><?php if (!empty($ArrCommonHeaderData['ArrEnquiryDetails']['orderenqrefno'])) echo $ArrCommonHeaderData['ArrEnquiryDetails']['orderenqrefno'] ?></td>
                                                <td class="secondheading"
                                                    style="width: 50px; padding-left: 10px; padding-top: 10px">Brand
                                                </td>
                                                <td style="width: 170px; padding-left: 10px; padding-top: 10px">
                                                    <?php
                                                    echo @$ArrCommonHeaderData['ArrEnquiryDetails']['brandname'];
                                                    ?>
                                                </td>
                                                <td class="secondheading"
                                                    style="width: 50px; padding-left: 10px; padding-top: 10px">Season
                                                </td>
                                                <td style="width: 200px; padding-left: 10px; padding-top: 7px; padding-right: 8px">
                                                    <input type="text" name="frmOrderSeason"
                                                           value="<?php if (!empty($ArrCommonHeaderData['ArrCommonData']->season))
                                                               echo $ArrCommonHeaderData['ArrCommonData']->season ?>"
                                                           id="frmOrderSeason"
                                                           class="form-control"
                                                           style="padding-left: 10px !important; height: 24px;">
                                                    <div class="herr"
                                                         id="ErrfrmOrderSeason"></div>
                                                </td>
                                                <td class="secondheading"
                                                    style="width: 70px; padding-left: 10px; padding-top: 10px">Class
                                                </td>
                                                <td style="width: 200px; padding-left: 10px; padding-top: 7px; padding-right: 8px">
                                                    <input type="text" name="frmOrderClass"
                                                           value="<?php if (!empty($ArrCommonHeaderData['ArrCommonData']->class))
                                                               echo $ArrCommonHeaderData['ArrCommonData']->class ?>"
                                                           id="frmOrderClass"
                                                           class="form-control"
                                                           style="padding-left: 10px !important; height: 24px;">
                                                    <div class="herr"
                                                         id="ErrfrmOrderClass"></div>
                                                </td>
                                                <td class="secondheading"
                                                    style="width: 100px; padding-left: 10px; padding-top: 10px">Total
                                                    Qty.
                                                </td>
                                                <td style="width: 194px; padding-left: 10px; padding-right: 10px; padding-top: 7px">
                                                    <?php
                                                    $ArrPcsSet = unserialize(ARRPCSSET);
                                                    if ($ArrPcsSet[$ArrCommonHeaderData['ArrEnquiryDetails']['pcsorset']]) {
                                                        $VarPcsOrSet = $ArrPcsSet[$ArrCommonHeaderData['ArrEnquiryDetails']['pcsorset']];
                                                    } else {
                                                        $VarPcsOrSet = 0;
                                                    }
                                                    echo $ArrCommonHeaderData['ArrEnquiryDetails']['exporderqty'] . '&nbsp;' . '&nbsp;' . '&nbsp;' . $VarPcsOrSet;
                                                    ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="secondheading" style="padding-left: 10px;  padding-top: 7px">
                                                    Style Ref. No.
                                                </td>
                                                <td style="padding-left: 10px; padding-top: 7px">
                                                    <input type="hidden" name="frmStyleRefNo"
                                                           id="frmStyleRefNo"
                                                           class="form-control"
                                                           value="<?php echo $ArrCommonHeaderData['ArrEnquiryDetails']['stylenamerefno'] ?>">
                                                    <?php echo $ArrCommonHeaderData['ArrEnquiryDetails']['stylenamerefno'] ?>
                                                </td>
                                                <td class="secondheading" style="padding-left: 10px; padding-top: 7px">
                                                    Buyer
                                                </td>
                                                <td style="padding-left: 10px; padding-top: 7px"><?php echo @$ArrCommonHeaderData['ArrEnquiryDetails']['buyername'] ?></td>
                                                <td class="secondheading" style="padding-left: 10px; padding-top: 7px">
                                                    Div./Dept.
                                                </td>
                                                <td style="padding-left: 10px; padding-right: 8px">
                                                    <input type="text" name="frmOrderDivDept"
                                                           value="<?php if (!empty($ArrCommonHeaderData['ArrCommonData']->divdept)) echo $ArrCommonHeaderData['ArrCommonData']->divdept ?>"
                                                           id="frmOrderDivDept"
                                                           class="form-control"
                                                           style="padding-left: 10px !important; height: 24px;">
                                                    <div class="herr"
                                                         id="ErrfrmOrderDivDept"></div>
                                                </td>
                                                <td class="secondheading" style="padding-left: 10px; padding-top: 7px">
                                                    Sub Class
                                                </td>
                                                <td style="padding-left: 10px; padding-right: 8px">
                                                    <input type="text" name="frmOrderSubClass"
                                                           value="<?php if (!empty($ArrCommonHeaderData['ArrCommonData']->sclass)) echo $ArrCommonHeaderData['ArrCommonData']->sclass ?>"
                                                           id="frmOrderSubClass"
                                                           class="form-control"
                                                           style="padding-left: 10px !important; height: 24px;">
                                                    <div class="herr"
                                                         id="ErrfrmOrderSubClass"></div>
                                                </td>
                                                <td class="secondheading" style="padding-left: 10px; padding-top: 7px">
                                                    Price Per Unit
                                                </td>
                                                <td style="padding-left: 10px; padding-top: 7px">
                                                    <?php
                                                    $ArrPcsSet = unserialize(ARRPCSSET);
                                                    if ($ArrPcsSet[$ArrCommonHeaderData['ArrEnquiryDetails']['pcsorset']]) {
                                                        $VarPcsOrSet = $ArrPcsSet[$ArrCommonHeaderData['ArrEnquiryDetails']['pcsorset']];
                                                    } else {
                                                        $VarPcsOrSet = 0;
                                                    }
                                                    //echo $VarPcsOrSet;
                                                    $ArrCurrency = unserialize(ARRCURRENCYLIST);
                                                    echo $ArrCommonHeaderData['ArrEnquiryDetails']['confirmprice'] . '&nbsp;' . '&nbsp;' . '&nbsp;' . $ArrCurrency[$ArrCommonHeaderData['ArrEnquiryDetails']['currency']];
                                                    ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="secondheading" style="padding-left: 10px; padding-top: 7px">
                                                    Style Descript.
                                                </td>
                                                <td style="padding-left: 10px; padding-right: 7px" colspan="7">
                                                    <div class="customcontrol" style="padding-left: 10px">
                                                        <?php echo $ArrCommonHeaderData['ArrEnquiryDetails']['styledesc'] ?>
                                                    </div>
                                                    <input type="hidden" name="frmStyleName"
                                                           id="frmStyleName"
                                                           class="form-control"
                                                           value="<?php echo $ArrCommonHeaderData['ArrEnquiryDetails']['styledesc'] ?>">
                                                </td>
                                                <td class="secondheading" style="padding-left: 10px; padding-top: 7px">
                                                    Pay. Terms
                                                </td>
                                                <td style="padding-left: 10px; padding-right: 15px;">
                                                    <input type="text" id="frmPaymentTerms" class="form-control"
                                                           style="padding-left: 10px !important; height: 24px"
                                                           value="<?php if (!empty($ArrCommonHeaderData['ArrCommonData']->payterms)) echo $ArrCommonHeaderData['ArrCommonData']->payterms ?>">
                                                </td>
                                            </tr>
                                        </table>
                                        <div
                                            style="background-color: rgb(210 253 249); padding-top: 10px; padding-left: 10px">
                                            <strong>COMBO WISE / COLOUR WISE QTY. BREAK-UP</strong>
                                        </div>
                                    </div>
                                    <?php

                                    $ArrMasterChartInfo = ARR_SIZE_CHART;
                                    $ArrSizeChartDetails = ARR_STD_SIZE;
                                    ?>
                                    <div class="col-sm-12">
                                        <form class="form-horizontal">
                                            <div class="box-body" style="padding-top: 18px">
                                                <div class="form-group">
                                                    <label for="inputEmail3" class="col-sm-1 control-label"
                                                           style="padding-top: 4px !important;">Size Chart</label>
                                                    <div class="col-sm-5">
                                                        <select name="frmOrderSizeChartList" id="frmOrderSizeChartList"
                                                                class="form-control"
                                                                onchange="fnShowSubChartInfoChange(this.value);">
                                                            <option value="">Choose the Size Chart</option>
                                                            <?php foreach ($ArrMasterChartInfo as $VarMasterChartId => $VarMasterChartName) { ?>
                                                                <option
                                                                    value="<?php echo $VarMasterChartId ?>" <?php if (!empty($ArrSizeChartData->sizecharttype)) echo ($ArrSizeChartData->sizecharttype == $VarMasterChartId) ? 'selected' : '' ?>>
                                                                    <?php echo $VarMasterChartName ?></option>
                                                            <?php } ?>
                                                        </select>
                                                        <div class="herr" id="ErrOrderSizeChartList"></div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="inputEmail3" class="col-sm-1 control-label">Size
                                                        Range</label>
                                                    <div class="col-sm-11" id="divSubChartList"
                                                         style="padding-left: 22px !important;">
                                                        <?php
                                                        if (!empty($ArrSizeChartData->sizechartvalue)) {
                                                            echo $ArrSizeChartData->sizechartvalue;
                                                        }
                                                        ?>
                                                    </div>
                                                    <div class="herr" id="ErrdivSubChartList">
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="col-sm-12 table-responsive" style="padding: 0 5px !important">
                                        <div id="firstTable"></div>
                                    </div>
                                    <div class="col-md-12" style="padding: 5px">
                                        <div class="form-group">
                                            <label for="firstTableRemarks">Remarks</label>
                                            <textarea id="firstTableRemarks" name="firstTableRemarks" rows="4" cols="50" class="form-control"><?php echo $VarRemarks ?></textarea>
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
                                    </div>
                                    <ul style="list-style: none;">
                                        <?php
                                        $VarFdr = UPLOADS_SLASH . "orderentry" . DIRECTORY_SEPARATOR . $VarEnquiryId . DIRECTORY_SEPARATOR . "spec" . DIRECTORY_SEPARATOR;
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
                                                                <a href="<?php echo base_url() . "orderentryvtwo/oeFileDownload?id=".urlencode(base64_encode($VarEnquiryId))."&fileName=".urlencode($file)."&page=spec" ?>">
                                                                    <i class="fa fa-download fa-lg"
                                                                       aria-hidden="true"></i>
                                                                </a>&nbsp;&nbsp;
                                                                <?php
                                                                if(in_array($VarFileExt,$ArrDwnExtensions)) {
                                                                }
                                                                else {
                                                                    ?>
                                                                    <a href="<?php echo base_url()."orderentryvtwo/oeOpenFile?id=".$VarEnquiryId."&fileName=".$file."&page=spec" ?>" target="_blank">
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
                                <div class="box-footer pull-right" style="width: 350px; position: relative; top: -9px">
                                    <?php
                                    $ArrPages = unserialize(ARRORDERENTRYPAGES);
                                    $VarCurrentPage = $this->uri->segment(2);
                                    $VarKi = array_search($VarCurrentPage, $ArrPages);
                                    $VarNextKey = $VarKi + 1;
                                    ?>
                                    <div class="bottomNav">
                                        <div class=""
                                             style="width: 90px; float: left; font-size: 18px; text-align: justify">
                                            <a href="javascript:void(0)" style="color: #bdbdbd; cursor:default">
                                                <i class="fa fa-arrow-left" style="font-size: 14px"></i>
                                                <span
                                                    style="position: relative; bottom: 0; left: 5px"><b>PREV.</b></span>
                                            </a>
                                        </div>
                                        <div class="pageNoBox"><?php echo $VarKi ?></div>
                                        <div class=""
                                             style="width: 108px; float: left; padding-left: 0; font-size: 18px; text-align: justify">
                                            <a href="<?php echo base_url('orderentryvtwo') . '/' . $ArrPages[$VarNextKey] . '/' . $VarHashEnquiryId ?>"
                                               style="color: grey">
                                                <span style="position: relative; bottom: 5px; top: 0"><b>NEXT</b></span>
                                                <i class="fa fa-arrow-right" style="font-size: 14px"></i>
                                            </a>
                                        </div>
                                    </div>
                                    <div class="saveEditBtn">
                                        <?php
                                        //echo '<pre>'; print_r($this->saveAccess); die('die');
                                        if($this->saveAccess) {
                                            if ($ArrCommonHeaderData['ArrEnquiryDetails']['editaccess'] == 1) {
                                                ?>
                                                <button type="button" id="sizeChartContinueBtn"
                                                        class="btn btn-info oeSaveEditBtn"
                                                        onclick="return fnSaveFirstTable()">Save
                                                </button>
                                                <?php
                                            }
                                        }
                                        ?>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="alert alert-success alert-dismissable hide"
                                         id="divSuccessBasicInfoMsg"></div>
                                    <div class="alert alert-danger alert-dismissable hide" id="ErrOrderEntry"></div>
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
    var hashenquiryid = '<?php echo $VarHashEnquiryId ?>';
    var enquiryid = '<?php echo $VarEnquiryId ?>';
    var GlbTotal = '<?php echo $ArrCommonHeaderData['ArrEnquiryDetails']['exporderqty'] ?>';
    var GlbParam = 'rfrom=1', firstTableData = <?php echo $jsonFirstTbl ?>;
    console.log(firstTableData, 'firstTableData');
    console.log(typeof firstTableData, 'firstTableData typeof');
    //firstTableData = [["Combo - 1","Top / Bottom","White / Grey Melange - Navy ; Grey Melange","100","Set"],["Combo - 2","Top / Bottom","Brown / Beige - Brown ; Beige","100","Set"]];
    var ps = ["<?php echo $VarPcsOrSet ?>"];
    // A custom method to SUM all the cells in the current column
    SUMCOL = function (instance, columnId) {
        var total = 0;
        for (var j = 0; j < instance.options.data.length; j++) {
            if (Number(instance.records[j][columnId - 1].innerHTML)) {
                total += Number(instance.records[j][columnId - 1].innerHTML);
            }
        }
        return total;
    };
    jexcel(document.getElementById('firstTable'), {
        columns: [
            {title: 'Combo', width: 350},
            {title: 'Component', width: 350},
            {title: 'Colour', width: 350},
            {title: 'Intake Qty. Per Comp. (Nos.)', width: 100},
            {title: 'Qty', type: 'numeric', width: 100, align: 'right'},
            {type: 'dropdown', title: 'Pcs. / Set', width: 105, source: ps},
        ],
        data: firstTableData,
        minDimensions: [6, 1],
        columnDrag: true,
        allowInsertColumn: false,
        footers: [['', '', '', 'Total', '=SUMCOL(TABLE(), COLUMN())', ["<?php echo $VarPcsOrSet ?>"]]],
        updateTable: function (instance, cell, col, row, val, label, cellName) {
            console.log(row, 'row');
            if (col === 0) {
                $(cell).text(jsTrim(val));
                instance.jexcel.options.data[row][col] = jsTrim(val);
            }
            if (col === 1) {
                $(cell).text(jsTrim(val));
                instance.jexcel.options.data[row][col] = jsTrim(val);
            }
            if (col === 2) {
                $(cell).text(jsTrim(val));
                instance.jexcel.options.data[row][col] = jsTrim(val);
            }

        }
    });
    //new jexcel ENDS
    function fnShowSubChartInfoChange(VarMasterChartId) {
        $('#frmOrderSizeChartList').css("border", "1px solid #d2d6de");
        $("#ErrOrderSizeChartList").text('');
        MakePostRequest(base_path + 'orderentryvtwo/getSubChartInfo', "sc=" + VarMasterChartId, 'json', fnShowSubChartRes);
        return false
    }
    function fnShowSubChartRes(data) {
        if (data != '') {
            if (data.errcode != undefined) {
                if (data.errcode == '404') {
                    fnCallSessionExpire();
                    return false;
                } else {
                    $("#divSubChartList").html(data.ss);
                }
            }
        }
    }

    //var GlbSelSizeChartText = '';
    //var GlbMasterSizeChartId = '';
    function fnSaveFirstTable() {
        $('.form-control').css('border', '1px solid #d2d6de');
        $('.herr').text('');
        var isriorcode = $("#frmIorNumber").text();
        var Season = $("#frmOrderSeason").val();
        var DivDept = $("#frmOrderDivDept").val();
        var Class = $("#frmOrderClass").val();
        var SubClass = $("#frmOrderSubClass").val();
        var PcsOrSet = $("#frmOrderPieceSet").val();
        var PricePerUnit = $("#frmOrderPricingUnit").val();
        var firstTableRemarks = $("#firstTableRemarks").val();
        var frmPaymentTerms = $("#frmPaymentTerms").val();
        let firstData = $('#firstTable').jexcel('getData', false);
        let strFirstData = JSON.stringify(firstData);
        //var SelChkBoxText = '';
        GlbSelSizeChart = [];
        GlbMasterSizeChartId = $("#frmOrderSizeChartList").val();
        console.log(GlbMasterSizeChartId,'GlbMasterSizeChartId');
        if (GlbMasterSizeChartId == 1) {
            $("input:checkbox[name='frmSubChartSelection']:checked").each(function () {
                let sizeVal = $("label[for='"+$(this).attr("id")+"']").text();
                GlbSelSizeChart.push(sizeVal);
                //SelChkBoxText = SelChkBoxText + $(this).next("label").text() + ",";
            });
        } else {
            $('input[name="frmSubChartCustomSelection[]"]').each(function () {
                if ($(this).val() !== '') {
                    //SelChkBoxText = SelChkBoxText + jsTrim($(this).val()) + ",";
                    GlbSelSizeChart.push(jsTrim($(this).val()));
                }
            });
        }
        console.log(GlbSelSizeChart,'GlbSelSizeChart');
        //console.log(SelChkBoxText,'SelChkBoxText');
        //GlbSelSizeChartText = SelChkBoxText.substring(0, SelChkBoxText.length - 1);
        //console.log(GlbSelSizeChartText,'GlbSelSizeChartText');
        //$("#divDispFinalChartInfo").html(GlbSelSizeChartText);
        $("#divDispMasterChartType").html($("#frmOrderSizeChartList option:selected").text());
        if (Season == "") {
            $('#ErrfrmOrderSeason').html("Please fill the Season");
            $('#frmOrderSeason').focus();
            $('#frmOrderSeason').css("border", "1px solid #ff0000");
            return false;
        }
        if (Class == "") {
            $('#ErrfrmOrderClass').html("Please fill the Class");
            $('#frmOrderClass').focus();
            $('#frmOrderClass').css("border", "1px solid #ff0000");
            return false;
        }
        if (DivDept == "") {
            $('#ErrfrmOrderDivDept').html("Please fill the Div. / Dept.");
            $('#frmOrderDivDept').focus();
            $('#frmOrderDivDept').css("border", "1px solid #ff0000");
            return false;
        }
        if (SubClass == "") {
            $('#ErrfrmOrderSubClass').html("Please fill the Subclass");
            $('#frmOrderSubClass').focus();
            $('#frmOrderSubClass').css("border", "1px solid #ff0000");
            return false;
        }
        if (frmPaymentTerms == "") {
            $('#ErrfrmPaymentTerms').html("Please fill the Payment terms");
            $('#frmPaymentTerms').focus();
            $('#frmPaymentTerms').css("border", "1px solid #ff0000");
            return false;
        }
        if (GlbMasterSizeChartId == "") {
            $('#ErrOrderSizeChartList').html("Select Size Chart Type");
            $('#frmOrderSizeChartList').focus();
            $('#frmOrderSizeChartList').css("border", "1px solid #ff0000");
            return false;
        }
        var RateAtBooking = $("#frmExcRateAtBooking").val();
        var RateOrderRealization = $("#frmExcRateOrderRealization").val();
        var selectedsize = $("#divSubChartList").text();
        console.log(GlbSelSizeChart, 'GlbSelSizeChart');
        console.log(GlbSelSizeChart.length,'GlbSelSizeChart LEN');
        console.log(selectedsize, 'selectedsize');
        if (GlbSelSizeChart.length && selectedsize == "") {
            $('#ErrdivSubChartList').text("Select a Size");
            $('#divSubChartList').focus();
            $('#divSubChartList').css("border", "1px solid #ff0000");
            return false;
        }
        else if(GlbSelSizeChart.length > 20) {
            $('#ErrdivSubChartList').text("Only Twenty Sizes allowed");
            $('#divSubChartList').focus();
            $('#divSubChartList').css("border", "1px solid #ff0000");
            return false;
        }
        MakeAsynPostRequest(base_path + 'orderentryvtwo/saveFirstTable', "enqid=" + enquiryid + "&d=" + strFirstData + "&season=" + Season + "&div=" +
            DivDept + "&cls=" + Class + "&subcls=" + SubClass + "&sizecharttype=" + GlbMasterSizeChartId + "&sizechartvalue=" + GlbSelSizeChart + "&frmPaymentTerms=" +
            frmPaymentTerms + "&rem=" + encodeURIComponent(firstTableRemarks) + "&isriorcode=" + isriorcode + "&ratebooking=" + RateAtBooking + "&ordrealization=" + RateOrderRealization, 'json',
            fnSaveFirstTableRes);
    }
    function fnSaveFirstTableRes(data) {
        console.log(data, 'data');
        if (data != '') {
            if (data.errcode == -1) {
                $("#ErrOrderEntry").removeClass('hide');
                $('#ErrOrderEntry').text(data.msg);
                return false;
            } else if (data.errcode == 1) {
                //GlbId       = data.id;
                extraObj.startUpload();
                $("#divSuccessBasicInfoMsg").removeClass('hide');
                $("#divSuccessBasicInfoMsg").text("Saved Successfully");
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
                return {'id': enquiryid,'page':'spec'};
            },
            autoSubmit: false
        });
        //console.log(extraObj,'extraObj');
    });
</script>
<?php $this->load->view(CNFCOMPANY . 'template/pagefooter'); ?>