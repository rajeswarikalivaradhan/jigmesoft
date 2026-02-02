<script src="<?= base_url() ?>assets/ace/node_modules/sweetalert2/dist/sweetalert2.all.js"></script>
<script src="<?= base_url() ?>assets/ace/node_modules/interactjs/dist/interact.js"></script>
<script src="<?php echo base_url(); ?>assets/js/jexcel/numeral.min.js"></script>

<style>
    .form-control {
        height: 38px !important;
        border: 0.001em solid #cccaca;
    }
    .jexcel tbody tr:nth-child(even) {
        background-color: #EEE9F1 !important;
    }
    .jexcel_overflow {
        width: 100% !important;
    }
    .jexcel{
        width: 100% !important;
        white-space: inherit !important;
    }
    .jdropdown-focus {
        position: inherit !important;
    }
    .jdropdown-default .jdropdown-item{
        padding: 2px !important
    }
    col:first-child {
        width: 3% !important;
    }
    .jexcel > thead > tr > td {
        padding: 1px !important;
        font-size: 12px;
        height: 50px;
    }
    .jexcel > tbody > tr > td.jexcel_dropdown {
        background-repeat: no-repeat;
        background-position: top 50% right -1px;
        background-image: url("data:image/svg+xml,%0A%3Csvg xmlns='http://www.w3.org/2000/svg' width='40' height='24' viewBox='0 0 10 20'%3E%3Cpath fill='none' d='M0 0h24v24H0V0z'/%3E%3Cpath d='M7 10l5 5 5-5H7z' fill='gray'/%3E%3C/svg%3E");
        text-overflow: ellipsis;
        overflow-x: hidden;
    }

    .jexcel > tbody > tr > td {
        height: 37px;
        color: black !important;
        font-size: 12px;
    }
    .jexcel > tfoot > tr > td {
        height: 37px;
        color: black !important;
        font-size: 12px;
    }
    .jexcel > col:first-child {
        width: 3% !important;
    }
    .jexcel_content {
        padding-right: 0 !important;
    }
    .nav-pills .nav-link.active, .nav-pills .show > .nav-link {
        color: #FFFFFF;
        background-color: #022b61;
    }
    .jexcel tbody tr:nth-child(2n) {
        background-color: #FFFFFF !important;
    }

    .bgc-tab-gray:hover {
        background-color: #dcdcdc !important;
    }

    .jexcel {
        border-right: 1px solid #f7f7f7 !important;
        border-bottom: 1px solid #f7f7f7 !important;
    }

    .jexcel > thead > tr > td {
        border: 0.01em solid #f7f7f7 !important;
    }
    .jexcel > tbody > tr > td {
        border: 0.01em solid #f7f7f7 !important;
    }
    .jexcel > tfoot > tr > td {
        border: 0.01em solid #f7f7f7 !important;
    }
    .nav-item-r-border{
        border-right: 3px solid #ffffff;
    }

    /*.btn-light-lightgrey {
        color: #022b61;
        background-color: #ebecec;
    }*/
    .btn-light-lightgrey {
        color: #011837;
        background-color: #ebecec;
    }

    .btn-h-light-purple[class*="btn-light-"]:hover {
        color: #022b61;
        background-color: #dcdcdc;
        border-color: #afa8d5;
    }

    .btn-a-purple:not(:disabled):not(.disabled):active, .btn-a-purple:not(:disabled):not(.disabled).active, .show > .btn.btn-a-purple.dropdown-toggle {
        color: #fff;
        background-color: #022b61 !important;
        border-color: #695ea7;
    }

    .brc-royal-blue{
        border-color: #022b61;
    }
    /*.btn-light-lightgrey {
        background-color: #022b61;
    }*/
    .table th, .table td{
        padding: 6px !important;
    }
    .first-heading{
        background-color: rgb(231,236,236) !important;
    }
    .secondheading {
        background-color: #f3f3f3;
    }
    .table{
        font-size: 13px !important;
    }
    .table > thead > tr > th, .table > tbody > tr > th, .table > tfoot > tr > th, .table > thead > tr > td, .table > tbody > tr > td, .table > tfoot > tr > td {
        border-top: 1px solid #f4f4f4;
    }
</style>
<?php $this->load->view(CNFCOMPANY . 'template/header'); ?>

<div class="card-header pt-2 pb-3 bgc-white border-0 " style="">
    <div class="card-title f-20">
        <b style="font-size: 20px; padding-left: 5px; margin-left: 3px;color: #333">Order Entry</b>
    </div>
</div>
<div class="col-12 pb-3 px-0">
    <div class="col-12 px-0" style="border-top: 1px solid #022b61;"></div>
</div>

<table class="table table-light">
    <tr>
        <td style="width: 300px">
            <table class="table">
                <tr>
                    <td class="first-heading">
                        <strong><?php echo $ArrCommonHeaderData['companyName']; ?></strong>
                    </td>
                </tr>
                <tr>
                    <td style="padding: 0 10px"><?php echo $ArrCommonHeaderData['companyAddress']; ?></td>
                </tr>
            </table>
        </td>
        <td>
            <table class="table">
                <tr>
                    <td class="secondheading"
                        style="width: 75px; padding-left: 10px; padding-top: 7px">
                        Merch. Name
                    </td>
                    <td style="width: 75px; padding-left: 10px; padding-top: 7px">
                        <?php
                        echo @$ArrCommonHeaderData['merchantName']
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
            <table class="table">
                <tr>
                    <td colspan="4" align="center" class="first-heading"><b>INTERNAL
                            REFERENCE NO.</b></td>
                </tr>
                <?php
                $ArrISRIORText = unserialize(ARRISRIOR);
                if (!empty($ArrCommonHeaderData['ArrEnquiryDetails']['reqforisrior']))
                {
                    if ($ArrCommonHeaderData['ArrEnquiryDetails']['reqforisrior'] >= 1)
                    {
                        ?>
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
                        <?php
                    }
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
                               style="height: 24px !important; padding-left: 10px !important; padding-top: 7px"
                               value="<?php if (!empty($ArrCommonData->orderbookingrate)) echo $ArrCommonData->orderbookingrate ?>">
                    </td>
                    <td class="secondheading"
                        style="width: 75px; padding-left: 10px; padding-top: 7px; padding-right: 10px">
                        Dynamic
                    </td>
                    <td style="width: 60px; padding-left: 10px; padding-right: 6px">
                        <input type="text" id="frmExcRateOrderRealization"
                               class="form-control"
                               style="height: 24px !important; padding-left: 10px !important; padding-top: 7px;"
                               value="<?php if (!empty($ArrCommonData->orderrealization)) echo $ArrCommonData->orderrealization ?>">
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>

<table class="table">
    <tr>
    <div
        style="padding-top: 10px; padding-left: 10px">
        <strong>ORDER DETAILS</strong>
    </div>
</tr>
</table>

<table class="table" style="margin: 5px">
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
                   value="<?php
                   if (!empty($ArrCommonHeaderData['ArrCommonData']->season))
                       echo $ArrCommonHeaderData['ArrCommonData']->season
                       ?>"
                   id="frmOrderSeason"
                   class="form-control"
                   style="padding-left: 10px !important; height: 24px !important;">
            <div class="herr"
                 id="ErrfrmOrderSeason"></div>
        </td>
        <td class="secondheading"
            style="width: 70px; padding-left: 10px; padding-top: 10px">Class
        </td>
        <td style="width: 200px; padding-left: 10px; padding-top: 7px; padding-right: 8px">
            <input type="text" name="frmOrderClass"
                   value="<?php
                   if (!empty($ArrCommonHeaderData['ArrCommonData']->class))
                       echo $ArrCommonHeaderData['ArrCommonData']->class
                       ?>"
                   id="frmOrderClass"
                   class="form-control"
                   style="padding-left: 10px !important; height: 24px !important;">
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
            if ($ArrPcsSet[$ArrCommonHeaderData['ArrEnquiryDetails']['pcsorset']])
            {
                $VarPcsOrSet = $ArrPcsSet[$ArrCommonHeaderData['ArrEnquiryDetails']['pcsorset']];
            }
            else
            {
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
                   style="padding-left: 10px !important; height: 24px !important;">
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
                   style="padding-left: 10px !important; height: 24px !important;">
            <div class="herr"
                 id="ErrfrmOrderSubClass"></div>
        </td>
        <td class="secondheading" style="padding-left: 10px; padding-top: 7px">
            Price Per Unit
        </td>
        <td style="padding-left: 10px; padding-top: 7px">
            <?php
            $ArrPcsSet = unserialize(ARRPCSSET);
            if ($ArrPcsSet[$ArrCommonHeaderData['ArrEnquiryDetails']['pcsorset']])
            {
                $VarPcsOrSet = $ArrPcsSet[$ArrCommonHeaderData['ArrEnquiryDetails']['pcsorset']];
            }
            else
            {
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
                   style="padding-left: 10px !important; height: 24px !important;"
                   value="<?php if (!empty($ArrCommonHeaderData['ArrCommonData']->payterms)) echo $ArrCommonHeaderData['ArrCommonData']->payterms ?>">
        </td>
    </tr>
</table>



<div class="content px-3" style="">
    <?php $requestFor  = isset($ArrEnquiryInfo[0]->reqforisrior) ? $ArrEnquiryInfo[0]->reqforisrior : ''; ?>
    <?php $this->load->view('pre_costing/pre_costing_details', array('components' => $components, 'VarEnqId' => $VarEnqId, 'requestFor' => $requestFor,'accessPermission' => false)); ?>
</div>
<?php $this->load->view(CNFCOMPANY . 'template/footer'); ?>