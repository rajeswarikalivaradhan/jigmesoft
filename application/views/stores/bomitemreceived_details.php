<?php $this->load->view(CNFCOMPANY . 'template/pageheader'); ?>
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/jexcel4/jsuites.css"/>
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/jexcel4/jexcel.css"/>
    <style type="text/css">
        #frmPin {
            padding-left: 15px;
            letter-spacing: 42px;
            border: 0;
            background-image: linear-gradient(to left, black 70%, rgba(255, 255, 255, 0) 0%);
            background-position: bottom;
            background-size: 50px 1px;
            background-repeat: repeat-x;
            background-position-x: 35px;
            width: 220px;
            min-width: 220px;
        }

        #divInner {
            left: 0;
            position: sticky;
        }

        #divOuter {
            width: 190px;
            overflow: hidden
        }

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

        table, .control-label {
            margin-bottom: 0px !important;
            font-size: 12px;
        }

        .form-control {
            height: 25px;
            padding: 3px 2px !important;
            font-size: 12px;
        }

        .mainheading {
            background-color: #bffff9;
        }

        .secondheading {
            background-color: #e3f9f7;
            height: 27px;
        }

        .table, .secondheading {
            background-color: #ecf0f5;
        }

        .table td.secondheading {
            padding-top: 15px;
        }

        .bomReceivedItemsDivLoop .box-header {
            background-color: #E7E7E7;
        }

        th, .verticalThead {
            background-color: #ffefef;
        }
    </style>
    <body class="sidebar-mini skin-black wysihtml5-supported sidebar-collapse">
<div class="wrapper">
    <?php $this->load->view(CNFCOMPANY . 'template/templateheader'); ?>
    <!-- Left side column. contains the logo and sidebar -->
    <aside class="main-sidebar">
        <?php $this->load->view(CNFCOMPANY . 'template/templateleftmenu'); ?>
    </aside>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <section class="content-header">
            <h1>BOM RECEIVED DETAILS - ITEMIZED</h1>
            <ol class="breadcrumb">
                <!--<li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                <li><a href="#">Examples</a></li>
                <li class="active">User profile</li>-->
            </ol>
        </section>

        <section class="content">
            <?php $this->load->view('commonBasicInfoOrderEntry');
            //$one = unserialize($_COOKIE['lotappr_itready_status_0']);
            //$two = unserialize($_COOKIE['lotappr_itready_status_1']);
            //echo '<pre>'; print_r($one);
            //echo '<pre>'; print_r($two); die('die');
            ?>
            <div class="box box-info">
                <div class="box-header with-border"><h4>P.I. DETAILS</h4></div>
                <div class="box-tools pull-right"></div>
                <div class="box-body">

                    <?php
                    $VarSerialNo = $VarItemRefNo + 1;
                    $Sno = 1;
                    //echo '<pre>'; print_r($ArrPiItems); die('die');
                    ?>
                    <table class="table table-hover">
                        <tr>
                            <th>S.No</th>
                            <th>Item Description / (%) Blend / Content / Material</th>
                            <th>Garment Size</th>
                            <th>Item Code</th>
                            <th>Item Color Code</th>
                            <th>Size or Dim (W*L*H)</th>
                            <th>Unit of Measure</th>
                            <th>P.I. No</th>
                            <th>P.I. Qty. Itemized</th>
                            <th>Unit of Measure</th>
                        </tr>
                        <tr>
                            <td><?php echo $VarSerialNo ?></td>
                            <?php
//echo '<pre>'; print_r($ArrPiItems); die('die');
                            /*echo $ArrPiData[$VarItemRefNo][''].'|#|'.$ArrPiData[$VarItemRefNo][''].'|#|'.
                            $ArrPiData[$VarItemRefNo][''].'|#|'.$ArrPiData[$VarItemRefNo][''].'|#|'.
                            $ArrPiData[$VarItemRefNo][''].'|#|'.$ArrPiData[$VarItemRefNo][''].'|#|'.
                            $ArrPiData[$VarItemRefNo][''];*/
                            ?>
                            <td><?php echo $ArrPiItems[$VarItemRefNo]['itemdesc'] ?></td>
                            <td><?php echo $ArrPiItems[$VarItemRefNo]['garmentsize'] ?></td>
                            <td><?php echo $ArrPiItems[$VarItemRefNo]['itemcode'] ?></td>
                            <td><?php echo $ArrPiItems[$VarItemRefNo]['itemcolorcode'] ?></td>
                            <td><?php echo $ArrPiItems[$VarItemRefNo]['sizeordim'] ?></td>
                            <td><?php echo $ArrPiItems[$VarItemRefNo]['uom1'] ?></td>
                            <td><?php echo $PurchaseIndentNo; ?></td>
                            <td><?php echo $ArrPiItems[$VarItemRefNo]['planbomqty'] ?></td>
                            <td><?php echo $ArrPiItems[$VarItemRefNo]['uom2'] ?></td>
                        </tr>
                    </table>
                    <?php
                    /*$ItemToBeSentToNewList = $ArrPiItems[$VarItemRefNo][0] . "|#|" . $ArrPiItems[$VarItemRefNo][1] . "|#|" .
                        $ArrPiItems[$VarItemRefNo][2] . "|#|" . $ArrPiItems[$VarItemRefNo][3] . "|#|" .
                        $ArrPiItems[$VarItemRefNo][4] . "|#|" . $ArrPiItems[$VarItemRefNo][5];*/
                    ?>
                    <!--<input type="hidden" id="bomitemtosend" value="<?php /*echo $ItemToBeSentToNewList */?>">-->
                    <h4>INVOICE DETAILS</h4>
                    <div class="">
                        <div id="bomStoresReceivedInvoiceJxl"></div>
                        <button type="submit" title="Click after entering each Invoice Details Grid row"
                                onclick="return fnSaveBomItemrecdInvoiceDetails('<?php echo $Sno ?>')"
                                class="btn btn-primary pull-right" style="margin: 0 10px;">SAVE INVOICE DETAILS
                        </button>

                    </div>

                        <h4>LOT APPROVAL STATUS</h4>
                        <!--<h3 class="box-title"></h3></div>-->
                    <div class="box-body no-padding">
                        <!--<div class="box-header with-border">-->
                        <table class="table table-hover" id="lotApprovalTable">
                            <tr>
                                <th>Description</th>
                                <th><span class="authTitle">Item Verified Status (Merchant)</span></th>
                                <th><span class="authTitle">Quantity Verified Status (Stores)</span></th>
                                <th><span id="authTitle3">Quality Audit Status (Q.A. Dept.)</span></th>
                                <th><span id="authTitle4">Invoice Verified Status (Purchase)</span></th>
                                <th><span id="authTitle5">Lot Approval Status (Management)</span></th>
                                <th><span id="authTitle6">Stores Item Ready Status (Stores)</span></th>
                            </tr>
                        </table>
                        <?php
                        $VarInvoiceRefNo = $VarInvoiceCount;
                        if ($VarInvoiceCount > 0) {
                            $CookiePurIndId = $VarBomPurIndentId;
                            if(!empty($_COOKIE['lotappr_qnv_status_'.$VarItemRefNo])) {
                                $QuantityVerifyCookie = unserialize($_COOKIE['lotappr_qnv_status_'.$VarItemRefNo]);
                                //echo '<pre>'; print_r($QuantityVerifyCookie);
                                $QuantityVerifyStoresBy = $this->commonmodel->getUserInfo($QuantityVerifyCookie['userId']);
                                $QtyVerifiedCookieInvoiceId = $QuantityVerifyCookie['invoiceRefNo'];
                                $QtyCookieItemId = $QuantityVerifyCookie['itemRefNo'];
                                $QtyCookieDatetime = date_format(date_create($QuantityVerifyCookie['datetime']),'d-m-Y H:i:s');
                                $QtyApproveRejectStatus = $QuantityVerifyCookie['approveReject'];
                            }
                            elseif(!empty($ArrLotApprovalInfo->qtyverifyauthstatus)) {
                                $QuantityVerifyCookie = unserialize($ArrLotApprovalInfo->qtyverifyauthstatus);
                                //echo '<pre>'; print_r($QuantityVerifyCookie);
                                $QuantityVerifyStoresBy = $this->commonmodel->getUserInfo($QuantityVerifyCookie['userId']);
                                $QtyVerifiedCookieInvoiceId = $QuantityVerifyCookie['invoiceRefNo'];
                                $QtyCookieItemId = $QuantityVerifyCookie['itemRefNo'];
                                $QtyCookieDatetime = date_format(date_create($QuantityVerifyCookie['datetime']),'d-m-Y H:i:s');
                                $QtyApproveRejectStatus = $QuantityVerifyCookie['approveReject'];
                            }
                            ?>
                            <table class="table" id="lotApprovalStatus_1">
                                <?php
                                if ($VarInvoiceCount > 0) {
                                    for($in = 1; $in <= $VarInvoiceCount; $in++) {
                                        ?>
                                        <tr>
                                            <td class="verticalThead" style="width: 70px;">STATUS</td>
                                            <td>
                                                <?php
                                                $CookiePurIndId = $CookieInvoiceId = $CookieItemRefNo = '';
                                                if(!empty($_COOKIE['lotappr_itv_status_'.$VarItemRefNo])) {
                                                    $ArrItemVerifyCookie = unserialize($_COOKIE['lotappr_itv_status_'.$VarItemRefNo]);
                                                    //echo '<pre>'; print_r($ArrItemVerifyCookie);
                                                    $ItemVerifiedMerchant = $this->commonmodel->getUserInfo($ArrItemVerifyCookie['userId']);
                                                    $CookiePurIndId = $ArrItemVerifyCookie['bomPurIndentId'];
                                                    $CookieInvoiceId = $ArrItemVerifyCookie['invoiceRefNo'];
                                                    $CookieItemRefNo = $ArrItemVerifyCookie['itemRefNo'];
                                                    $CookieApproveRejectStatus = $ArrItemVerifyCookie['approveReject'];
                                                }
                                                elseif(!empty($ArrLotApprovalInfo->itemverifyauthstatus)) {
                                                    $ArrItemVerifyCookie = unserialize($ArrLotApprovalInfo->itemverifyauthstatus);
                                                    $ItemVerifiedMerchant = $this->commonmodel->getUserInfo($ArrItemVerifyCookie['userId']);
                                                    $CookiePurIndId = $ArrItemVerifyCookie['bomPurIndentId'];
                                                    $CookieInvoiceId = $ArrItemVerifyCookie['invoiceRefNo'];
                                                    //echo '<pre>'; print_r($CookieInvoiceId);
                                                    $CookieItemRefNo = $ArrLotApprovalCookies['itemRefNo'];
                                                    $CookieItemVerifiedDatetime = date_format(date_create($ArrItemVerifyCookie['datetime']), 'd-m-Y H:i:s');
                                                    $CookieApproveRejectStatus = $ArrItemVerifyCookie['approveReject'];
                                                }
                                                ?>
                                                <select class="form-control" onchange="allAuthStatusChange(this,'itemverifyauthstatus',<?php echo $in ?>,<?php echo $VarItemRefNo ?>)">
                                                    <option value="">Choose</option>
                                                    <?php
                                                    $VarStatusSelected = '';
                                                    foreach ($ArrStatus1 as $itemVerifyStatusKeyId => $item) {
                                                        if ($VarItemRefNo == $CookieItemRefNo && $in == $CookieInvoiceId && $VarBomPurIndentId == $CookiePurIndId)
                                                            if (!empty($CookieApproveRejectStatus)) if ($CookieApproveRejectStatus == $itemVerifyStatusKeyId) $VarStatusSelected = 'selected'; else $VarStatusSelected = '';
                                                        echo '<option value="' . $itemVerifyStatusKeyId . '" ' . $VarStatusSelected . '>' . $item . '</option>';
                                                    }
                                                    ?>
                                                </select>
                                            </td>
                                            <td>
                                                <select class="form-control" onchange="allAuthStatusChange(this,'qtyverifyauthstatus',<?php echo $in ?>,<?php echo $VarItemRefNo ?>)">
                                                    <option value="">Choose</option>
                                                    <?php
                                                    foreach ($ArrStatus1 as $itemVerifyStatusKeyId => $item) {
                                                        if ($VarItemRefNo == $CookieItemRefNo && $in == $CookieInvoiceId && $VarBomPurIndentId == $CookiePurIndId)
                                                            if (!empty($QtyApproveRejectStatus)) if ($QtyApproveRejectStatus == $itemVerifyStatusKeyId) $VarStatusSelected = 'selected'; else $VarStatusSelected = '';
                                                        echo '<option value="' . $itemVerifyStatusKeyId . '" ' . $VarStatusSelected . '>' . $item . '</option>';
                                                    }
                                                    ?>
                                                </select>
                                            </td>
                                            <td>
                                                <?php
                                                //Quality audit
                                                if(!empty($_COOKIE['lotappr_qau_status_'.$VarItemRefNo])) {
                                                    $ArrItemVerifyCookie = unserialize($_COOKIE['lotappr_qau_status_'.$VarItemRefNo]);
                                                    $CookieApproveRejectStatus = $ArrItemVerifyCookie['approveReject'];
                                                }
                                                elseif(!empty($ArrLotApprovalInfo->qualityanaauthstatus)) {
                                                    $ArrItemVerifyCookie = unserialize($ArrLotApprovalInfo->qualityanaauthstatus);
                                                    $CookieApproveRejectStatus = $ArrItemVerifyCookie['approveReject'];
                                                }
                                                //Quality audit ENDS
                                                ?>
                                                <select class="form-control" onchange="allAuthStatusChange(this,'qualityanaauthstatus',<?php echo $in ?>,<?php echo $VarItemRefNo ?>)">
                                                    <option value="">Choose</option>
                                                    <?php
                                                    foreach ($ArrStatus1 as $itemVerifyStatusKeyId => $item) {
                                                        if ($VarItemRefNo == $CookieItemRefNo && $in == $CookieInvoiceId && $VarBomPurIndentId == $CookiePurIndId)
                                                            if (!empty($CookieApproveRejectStatus)) if ($CookieApproveRejectStatus == $itemVerifyStatusKeyId) $VarStatusSelected = 'selected'; else $VarStatusSelected = '';
                                                        echo '<option value="' . $itemVerifyStatusKeyId . '" ' . $VarStatusSelected . '>' . $item . '</option>';
                                                    }
                                                    ?>
                                                </select>
                                            </td>
                                            <td>
                                                <?php
                                                /**Invoice Verify*/
                                                $CookiePurIndId = $CookieInvoiceId = $CookieItemRefNo = '';
                                                if(!empty($_COOKIE['lotappr_pur_status_'.$VarItemRefNo])) {
                                                    $ArrItemVerifyCookie = unserialize($_COOKIE['lotappr_pur_status_'.$VarItemRefNo]);
                                                    //echo '<pre>'; print_r($ArrItemVerifyCookie);
                                                    $CookiePurIndId = $ArrItemVerifyCookie['bomPurIndentId'];
                                                    $CookieInvoiceId = $ArrItemVerifyCookie['invoiceRefNo'];
                                                    $CookieItemRefNo = $ArrItemVerifyCookie['itemRefNo'];
                                                    $CookieApproveRejectStatus = $ArrItemVerifyCookie['approveReject'];
                                                }
                                                elseif(!empty($ArrLotApprovalInfo->invoiceverifyauthstatus)) {
                                                    $ArrItemVerifyCookie = unserialize($ArrLotApprovalInfo->invoiceverifyauthstatus);
                                                    $CookiePurIndId = $ArrItemVerifyCookie['bomPurIndentId'];
                                                    $CookieInvoiceId = $ArrItemVerifyCookie['invoiceRefNo'];
                                                    $CookieItemRefNo = $ArrItemVerifyCookie['itemRefNo'];
                                                    $CookieApproveRejectStatus = $ArrItemVerifyCookie['approveReject'];
                                                }
                                                /**Invoice Verify ENDS*/
                                                ?>
                                                <select class="form-control" onchange="allAuthStatusChange(this,'invoiceverifyauthstatus',<?php echo $in ?>,<?php echo $VarItemRefNo ?>)">
                                                    <option value="">Choose</option>
                                                    <?php
                                                    $VarStatusSelected = '';
                                                    foreach ($ArrStatus1 as $itemVerifyStatusKeyId => $item) {
                                                        if ($VarItemRefNo == $CookieItemRefNo && $in == $CookieInvoiceId && $VarBomPurIndentId == $CookiePurIndId)
                                                            if (!empty($CookieApproveRejectStatus)) if ($CookieApproveRejectStatus == $itemVerifyStatusKeyId) $VarStatusSelected = 'selected'; else $VarStatusSelected = '';
                                                        echo '<option value="' . $itemVerifyStatusKeyId . '" ' . $VarStatusSelected . '>' . $item . '</option>';
                                                    }
                                                    ?>
                                                </select>
                                            </td>
                                            <td>
                                                <select class="form-control" id="mgmtauthstauts_<?php echo $Sno ?>"
                                                        name="mgmtauthstauts_<?php echo $Sno ?>">
                                                    <?php
                                                    foreach ($ArrMgmtStatus as $mgmtauthstatusKey => $item) {
                                                        echo '<option value="' . $mgmtauthstatusKey . '">' . $item . '</option>';
                                                    }
                                                    ?>
                                                </select>
                                            </td>
                                            <td>
                                                <?php
                                                $CookiePurIndId = $CookieInvoiceId = $CookieItemRefNo = '';
                                                if(!empty($_COOKIE['lotappr_itready_status_'.$VarItemRefNo])) {
                                                    $ArrItemVerifyCookie = unserialize($_COOKIE['lotappr_itready_status_'.$VarItemRefNo]);
                                                    $ItemVerifiedMerchant = $this->commonmodel->getUserInfo($ArrItemVerifyCookie['userId']);
                                                    $CookiePurIndId = $ArrItemVerifyCookie['bomPurIndentId'];
                                                    $CookieInvoiceId = $ArrItemVerifyCookie['invoiceRefNo'];
                                                    $CookieItemRefNo = $ArrItemVerifyCookie['itemRefNo'];
                                                    $CookieApproveRejectStatus = $ArrItemVerifyCookie['approveReject'];
                                                }
                                                elseif(!empty($ArrLotApprovalInfo->itemreadyauthstatus)) {
                                                    $ArrItemVerifyCookie = unserialize($ArrLotApprovalInfo->itemreadyauthstatus);
                                                    $ItemVerifiedMerchant = $this->commonmodel->getUserInfo($ArrItemVerifyCookie['userId']);
                                                    $CookiePurIndId = $ArrItemVerifyCookie['bomPurIndentId'];
                                                    $CookieInvoiceId = $ArrItemVerifyCookie['invoiceRefNo'];
                                                    $CookieItemRefNo = $ArrLotApprovalCookies['itemRefNo'];
                                                    $CookieItemVerifiedDatetime = date_format(date_create($ArrItemVerifyCookie['datetime']), 'd-m-Y H:i:s');
                                                    $CookieApproveRejectStatus = $ArrItemVerifyCookie['approveReject'];
                                                }
                                                ?>
                                                <select class="form-control" onchange="allAuthStatusChange(this,'itemreadyauthstatus',<?php echo $in ?>,<?php echo $VarItemRefNo ?>)">
                                                    <option value="">Choose</option>
                                                    <?php
                                                    $VarStatusSelected = '';
                                                    foreach ($ArrStatus1 as $itemVerifyStatusKeyId => $item) {
                                                        if ($VarItemRefNo == $CookieItemRefNo && $in == $CookieInvoiceId && $VarBomPurIndentId == $CookiePurIndId)
                                                            if (!empty($CookieApproveRejectStatus)) if ($CookieApproveRejectStatus == $itemVerifyStatusKeyId) $VarStatusSelected = 'selected'; else $VarStatusSelected = '';
                                                        echo '<option value="' . $itemVerifyStatusKeyId . '" ' . $VarStatusSelected . '>' . $item . '</option>';
                                                    }
                                                    ?>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="verticalThead">DONE BY</td>
                                            <td>
                                                <div id="itemverifyauthstatus<?php echo $in ?>_<?php echo $VarItemRefNo ?>">
                                                    <?php
                                                    if(isset($CookieItemRefNo) && $CookieItemRefNo >= 0) {
                                                        if ($VarItemRefNo == $CookieItemRefNo && $in == $CookieInvoiceId && $VarBomPurIndentId == $CookiePurIndId)
                                                            echo $ItemVerifiedMerchant[0]->contactname;
                                                    }
                                                    ?>
                                                </div>
                                            </td>
                                            <td>
                                                <div id="qtyverifyauthstatus<?php echo $in ?>_<?php echo $VarItemRefNo ?>">
                                                    <?php
                                                    if(isset($QtyCookieItemId) && $QtyCookieItemId >= 0) {
                                                        if ($VarItemRefNo == $CookieItemRefNo && $in == $CookieInvoiceId && $VarBomPurIndentId == $CookiePurIndId)
                                                            echo $QuantityVerifyStoresBy[0]->contactname;
                                                    }
                                                    ?>
                                                </div>
                                            </td>
                                            <td>
                                                <div id="qualityanaauthstatus<?php echo $in ?>_<?php echo $VarItemRefNo ?>">
                                                    <?php
                                                    /**Quality audit*/
                                                    $CookiePurIndId = $CookieInvoiceId = $CookieItemRefNo = '';
                                                    if(!empty($_COOKIE['lotappr_qau_status_'.$VarItemRefNo])) {
                                                        $ArrItemVerifyCookie = unserialize($_COOKIE['lotappr_qau_status_'.$VarItemRefNo]);
                                                        $CookieDoneBy = $this->commonmodel->getUserInfo($ArrItemVerifyCookie['userId']);
                                                        $CookiePurIndId = $ArrItemVerifyCookie['bomPurIndentId'];
                                                        $CookieInvoiceId = $ArrItemVerifyCookie['invoiceRefNo'];
                                                        $CookieItemRefNo = $ArrItemVerifyCookie['itemRefNo'];
                                                        $CookieItemVerifiedDatetime = date_format(date_create($ArrItemVerifyCookie['datetime']),'d-m-Y H:i:s');
                                                    }
                                                    elseif(!empty($ArrLotApprovalInfo->qualityanaauthstatus)) {
                                                        $ArrItemVerifyCookie = unserialize($ArrLotApprovalInfo->qualityanaauthstatus);
                                                        $CookieDoneBy = $this->commonmodel->getUserInfo($ArrItemVerifyCookie['userId']);
                                                        $CookiePurIndId = $ArrItemVerifyCookie['bomPurIndentId'];
                                                        $CookieInvoiceId = $ArrItemVerifyCookie['invoiceRefNo'];
                                                        $CookieItemRefNo = $ArrItemVerifyCookie['itemRefNo'];
                                                        $CookieItemVerifiedDatetime = date_format(date_create($ArrItemVerifyCookie['datetime']), 'd-m-Y H:i:s');
                                                    }
                                                    /**Quality audit ENDS*/
                                                    if(isset($CookieItemRefNo) && $CookieItemRefNo >= 0) {
                                                        if ($VarItemRefNo == $CookieItemRefNo && $in == $CookieInvoiceId && $VarBomPurIndentId == $CookiePurIndId)
                                                            echo $CookieDoneBy[0]->contactname;
                                                    }
                                                    ?>
                                                </div>
                                            </td>
                                            <td>
                                                <div id="invoiceverifyauthstatus<?php echo $in ?>_<?php echo $VarItemRefNo ?>">
                                                    <?php
                                                    /**Invoice Verify*/
                                                    $CookiePurIndId = $CookieInvoiceId = $CookieItemRefNo = '';
                                                    if(!empty($_COOKIE['lotappr_pur_status_'.$VarItemRefNo])) {
                                                        $ArrItemVerifyCookie = unserialize($_COOKIE['lotappr_pur_status_'.$VarItemRefNo]);
                                                        $CookieDoneBy = $this->commonmodel->getUserInfo($ArrItemVerifyCookie['userId']);
                                                        $CookiePurIndId = $ArrItemVerifyCookie['bomPurIndentId'];
                                                        $CookieInvoiceId = $ArrItemVerifyCookie['invoiceRefNo'];
                                                        $CookieItemRefNo = $ArrItemVerifyCookie['itemRefNo'];
                                                        $CookieItemVerifiedDatetime = date_format(date_create($ArrItemVerifyCookie['datetime']),'d-m-Y H:i:s');
                                                    }
                                                    elseif(!empty($ArrLotApprovalInfo->invoiceverifyauthstatus)) {
                                                        $ArrItemVerifyCookie = unserialize($ArrLotApprovalInfo->invoiceverifyauthstatus);
                                                        $CookieDoneBy = $this->commonmodel->getUserInfo($ArrItemVerifyCookie['userId']);
                                                        $CookiePurIndId = $ArrItemVerifyCookie['bomPurIndentId'];
                                                        $CookieInvoiceId = $ArrItemVerifyCookie['invoiceRefNo'];
                                                        $CookieItemRefNo = $ArrItemVerifyCookie['itemRefNo'];
                                                        $CookieItemVerifiedDatetime = date_format(date_create($ArrItemVerifyCookie['datetime']), 'd-m-Y H:i:s');
                                                    }
                                                    /**Invoice Verify ENDS*/
                                                    if(isset($CookieItemRefNo) && $CookieItemRefNo >= 0) {
                                                        if ($VarItemRefNo == $CookieItemRefNo && $in == $CookieInvoiceId && $VarBomPurIndentId == $CookiePurIndId)
                                                            echo $CookieDoneBy[0]->contactname;
                                                    }
                                                    ?>
                                                </div>
                                            </td>
                                            <td>
                                                <div id="mgmtauthstatus<?php echo $in ?>_<?php echo $VarItemRefNo ?>"></div>
                                            </td>
                                            <td>
                                                <div id="itemreadyauthstatus<?php echo $in ?>_<?php echo $VarItemRefNo ?>">
                                                    <?php
                                                    if(isset($CookieItemRefNo) && $CookieItemRefNo >= 0) {
                                                        if ($VarItemRefNo == $CookieItemRefNo && $in == $CookieInvoiceId && $VarBomPurIndentId == $CookiePurIndId)
                                                            echo $ItemVerifiedMerchant[0]->contactname;
                                                    }
                                                    ?>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="verticalThead"> Date & Time</td>
                                            <td>
                                                <div id="itemverifyauthstatusDatetime_<?php echo $in ?>_<?php echo $VarItemRefNo ?>">
                                                    <?php
                                                    //echo '<pre>'; print_r($CookieItemRefNo); die('die');
                                                    if(isset($CookieItemRefNo) && $CookieItemRefNo >= 0) {
                                                        if ($VarItemRefNo == $CookieItemRefNo && $in == $CookieInvoiceId && $VarBomPurIndentId == $CookiePurIndId)
                                                            echo $CookieItemVerifiedDatetime;
                                                    }
                                                    ?>
                                                </div>
                                            </td>
                                            <td>
                                                <div id="qtyverifyauthstatusDatetime_<?php echo $in ?>_<?php echo $VarItemRefNo ?>">
                                                    <?php
                                                    if(isset($CookieItemRefNo) && $CookieItemRefNo >= 0) {
                                                        if ($VarItemRefNo == $CookieItemRefNo && $in == $CookieInvoiceId && $VarBomPurIndentId == $CookiePurIndId)
                                                            echo $CookieItemVerifiedDatetime;
                                                    }
                                                    ?>
                                                </div>
                                            </td>
                                            <td>
                                                <div id="qualityanaauthstatusDatetime_<?php echo $in ?>_<?php echo $VarItemRefNo ?>">
                                                    <?php
                                                    //Quality audit
                                                    if(!empty($_COOKIE['lotappr_qau_status_'.$VarItemRefNo])) {
                                                        $ArrItemVerifyCookie = unserialize($_COOKIE['lotappr_qau_status_'.$VarItemRefNo]);
                                                        $CookieItemVerifiedDatetime = date_format(date_create($ArrItemVerifyCookie['datetime']),'d-m-Y H:i:s');
                                                    }
                                                    elseif(!empty($ArrLotApprovalInfo->qualityanaauthstatus)) {
                                                        $ArrItemVerifyCookie = unserialize($ArrLotApprovalInfo->qualityanaauthstatus);
                                                        $CookieItemVerifiedDatetime = date_format(date_create($ArrItemVerifyCookie['datetime']), 'd-m-Y H:i:s');
                                                    }
                                                    //Quality audit ENDS
                                                    if(isset($CookieItemRefNo) && $CookieItemRefNo >= 0) {
                                                        if ($VarItemRefNo == $CookieItemRefNo && $in == $CookieInvoiceId && $VarBomPurIndentId == $CookiePurIndId)
                                                            echo $CookieItemVerifiedDatetime;
                                                    }
                                                    ?>
                                                </div>
                                            </td>
                                            <td>
                                                <div id="invoiceverifyauthstatusDatetime_<?php echo $in ?>_<?php echo $VarItemRefNo ?>">
                                                    <?php
                                                    if(isset($CookieItemRefNo) && $CookieItemRefNo >= 0) {
                                                        if ($VarItemRefNo == $CookieItemRefNo && $in == $CookieInvoiceId && $VarBomPurIndentId == $CookiePurIndId)
                                                            echo $CookieItemVerifiedDatetime;
                                                    }
                                                    ?>
                                                </div>
                                            </td>
                                            <td>
                                                <div id="mgmtauthstatusDatetime_<?php echo $in ?>_<?php echo $VarItemRefNo ?>"></div>
                                            </td>
                                            <td>
                                                <div id="itemreadyauthstatusDatetime_<?php echo $in ?>_<?php echo $VarItemRefNo ?>">
                                                    <?php
                                                    if(isset($CookieItemRefNo) && $CookieItemRefNo >= 0) {
                                                        if ($VarItemRefNo == $CookieItemRefNo && $in == $CookieInvoiceId && $VarBomPurIndentId == $CookiePurIndId)
                                                            echo $CookieItemVerifiedDatetime;
                                                    }
                                                    ?>
                                                </div>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                }
                                ?>
                            </table>
                            <?php
                        }

                        ?>
                        <div id="moreLotApprovalTbl"></div>
                        <div class="box-footer">
                            <ul class="pagination pagination-sm pull-right">
                            <?php
                            $VarCurrentItemId = $this->uri->segment(4);

								for($i = 0; $i < count($ArrPiItems); $i++) {
									$VarActive = '';
								    if($VarCurrentItemId == $i) {
										$VarActive = 'active';
                                    }
									$pageId = $i + 1;
									echo '<li class="'.$VarActive.'"><a href="'.base_url('storesuser/bomitem_received_details').'/'.urlencode(base64_encode($VarBomPurIndentId)).'/'.$i.'">'.$pageId.'</a></li>';
								}

                            ?>
                            </ul>
                            <?php
                            if (count($ArrPiItems) > $VarItemRefNo) {
                                if (count($ArrPiItems) != $VarSerialNo) {
                                    ?>
                                    <a href="javascript:void(0)" class="btn pull-right"
                                       onclick="fnNextPrevItemRefNo(true)">Next</a>
                                    <?php
                                }
                            }
                            if ($VarItemRefNo > 0) {
                                ?>
                                <a href="javascript:void(0)" class="btn pull-right"
                                   onclick="fnNextPrevItemRefNo(false)">Previous</a>
                                <?php
                            }
                            ?>

                            <div id="frmErr_<?php echo $VarItemRefNo ?>" class="herr"></div>
                            <button type="submit" id="" onclick="return fnNewItemList()"
                                    class="btn btn-primary pull-right" style="margin: 0 10px">SEND INV. QTY. TO BOM - NEW ITEM LIST
                            </button>


                        </div>
                        <!-- /.box-footer -->

                    </div>
                </div>
                <div class="col-md-12">
                    <div class="alert alert-success alert-dismissable hide" id="divSuccessBasicInfoMsg"></div>
                </div>
            </div>
        </section>
    </div>

<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"><i class="fa fa-times"></i></span>
                    </button>

                <h4 class="modal-title" id="userType"></h4>
                <!--<h4 class="modal-title" id="myModalLabel">Enter Authentication</h4>-->
            </div>
            <div class="modal-body">
                <form class="form-horizontal" method="post" id="frmPinformId">
                    <input id="frmUid" type="text" placeholder="E-mail Id">
                    <input id="frmPwd" type="password" placeholder="Password">
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary" onclick="return fnCheckPin()">Continue</button>
                <div class="herr pull-left" id="ErrfrmModalAuth"></div>
            </div>
        </div>
    </div>
</div>
<!-- /.content-wrapper -->
<?php $this->load->view(CNFCOMPANY . 'template/templatefooter'); ?>
<div class="control-sidebar-bg"></div>
</div><!-- ./wrapper -->
<script src="<?php echo base_url(); ?>assets/js/ajax.js"></script>
<script src="<?php echo base_url(); ?>assets/js/jexcel4/jsuites.js"></script>
<script src="<?php echo base_url(); ?>assets/js/jexcel4/jexcel.js"></script>
<script>
    var GlbUserAuthType = ''; var GlbAllDropdownStatus = 0; var GlbBomPurIndentNo = [];
    var GlbPiRefId = '<?php echo urlencode(base64_encode($VarBomPurIndentId)) ?>';
    var GlbItemRefno = '<?php echo $VarItemRefNo ?>';
    var GlbOrderId = '<?php echo $VarOrderId ?>';
    var GlbBomItemId = '<?php echo $bomItemId ?>';
    console.log(GlbBomItemId,'GlbBomItemId');
    var GlbRequestId = '<?php echo $VarBomPurReqId ?>';
    var GlbCurretUrl = '<?php echo current_url() ?>';
    //var GlbRowNumber = $("#bomStoresReceivedInvoiceJxl").find("tbody tr").length;
    //var GlbItemizedBomRecdInvoiceJxl = [];
    var GlbItemizedBomRecdInvoiceJxl = '<?php echo $ArrItemizedBomRecdInvoiceJxl ?>';
    console.log(GlbItemizedBomRecdInvoiceJxl,'GlbItemizedBomRecdInvoiceJxl');
    var GlbPriKey = '<?php echo $invoiceTblPrimaryKey ?>';
    var GlbUom = '<?php echo $jsonUnitMeasure ?>';
    console.log(GlbUom, 'GlbUom');
    $('#basicInvDate').datepicker({
        format: 'dd-mm-yyyy',
        todayHighlight: true,
        autoclose: true
    });
    $('#basicReceivedDate').datepicker({
        format: 'dd-mm-yyyy',
        todayHighlight: true,
        autoclose: true
    });

    function allAuthStatusChange(thisobj, userauthtype, invoicerefno, itemrefno) {
        GlbAllDropdownStatus = thisobj.value;
        GlbUserAuthType = userauthtype;
        GlbInv = invoicerefno; GlbItemRefno = itemrefno; let userType = '';
        if (GlbUserAuthType == 'itemverifyauthstatus') {
            uatypeid = 4;
            userType = 'Merchant authentication';
        }
        else if (GlbUserAuthType == 'qtyverifyauthstatus') {
            uatypeid = 9;
            userType = 'BOM Stores authentication';
        }
        else if (GlbUserAuthType == 'qualityanaauthstatus') {
            uatypeid = 12;
            userType = 'Quality Audit authentication';
        }
        else if (GlbUserAuthType == 'invoiceverifyauthstatus') {
            uatypeid = 8;
            userType = 'Purchase Department authentication';
        }
        else if (GlbUserAuthType == 'itemreadyauthstatus') {
            uatypeid = 1;
            userType = 'BOM Stores authentication';
        }
        if (GlbAllDropdownStatus >= 1) {
            $('#myModal').modal('show');
        }
        $("#userType").text(userType);
    }

    function fnCheckPin() {
        var Emailid = $("#frmUid").val();
        var Pwd = $("#frmPwd").val();
        console.log(GlbUserAuthType, 'GlbUserAuthType');

        MakePostRequest(base_path + 'dashboard/commonLotApprovalAuth', "e=" + Emailid + "&p=" + Pwd +
            "&apprrejectstatus="+GlbAllDropdownStatus + "&pinorefid=" + GlbPiRefId + "&itemrefno=" + GlbItemRefno +
            "&invoicerefno=" + GlbInv + "&userauthtypeid=" + uatypeid, 'json', fnCheckPinRes);
        return false;
    }

    function fnCheckPinRes(data) {
        if (data != '') {
            if (data.errcode == 1) {
                $("#" + GlbUserAuthType + GlbInv + '_' + GlbItemRefno).text(data.cn);
                $("#" + GlbUserAuthType + "Datetime_" + GlbInv + '_' + GlbItemRefno).text(data.dt);
                var rownumber = $(".jexcel tr").length;
                $("#myModal").modal("hide");
                if (GlbUserAuthType == 'itemreadyauthstatus') {
                    console.log(rownumber,'rownumber');
//                    console.log(GlbRowNumber,'GlbRowNumber');
                    MakePostRequest(base_path + 'dashboard/updateBomLotApprCookies', "pinorefid=" +
                        GlbPiRefId + "&itemrefno=" + GlbItemRefno + "&invoicerefno=" +
                        GlbInv, 'json', saveCookiestoDbRes);
                }
            }
            else {
                $("#ErrfrmModalAuth").text('Invalid E-mail Id / Password');
            }
        }
    }

    function saveCookiestoDbRes(data) {
        //console.log(data,'saveCookiestoDbRes data');
    }

    console.log(GlbItemRefno, 'GlbItemRefno');
    console.log(GlbPiRefId, 'GlbPiRefId');
    console.log(GlbItemRefno, 'GlbItemRefno');

    function fnNextPrevItemRefNo(nextprev) {
        if (nextprev) {
            //console.log('itemCount != itemno');
            //console.log(itemCount,'itemCount');
            GlbItemRefno++;
            //console.log(GlbItemRefno,'GlbItemRefno');
            window.location.href = base_path + 'storesuser/bomitem_received_details/' + GlbPiRefId + '/' + GlbItemRefno;
        }
        else {
            if (GlbItemRefno == 1) {
                GlbItemRefno = '';
                console.log(GlbItemRefno, 'GlbItemRefno if == 2');
                window.location.href = base_path + 'storesuser/bomitem_received_details/' + GlbPiRefId + '/' + GlbItemRefno;
            }
            else if (GlbItemRefno > 1) {
                GlbItemRefno--;
                console.log(GlbItemRefno, 'GlbItemRefno decremanet');
                window.location.href = base_path + 'storesuser/bomitem_received_details/' + GlbPiRefId + '/' + GlbItemRefno;
            }
        }
    }

    function fnNewItemList() {
        //var bomItemToSend = $("#bomitemtosend").val();
        //console.log(bomItemToSend,'bomItemToSend');
        var Param = "invoicesJxl="+JSON.stringify(bomStoresReceivedInvoiceJxl.getData())+"&pirefno=" + GlbPiRefId + "&itemrefno=" +GlbItemRefno+
            "&oid="+GlbOrderId+ "&bomItemId="+GlbBomItemId+"&requestId="+GlbRequestId;
        MakePostRequest(base_path + 'storesuser/updateNewItemList', Param, 'json', function (data) {
            console.log(data, 'data');
            if(data != '') {
                if(data.errcode == 1) {
                    $("#divSuccessBasicInfoMsg").text('Sent Successfully');
                    //window.location.href = base_path + 'storesuser/newbomstocklist/' + GlbRequestId + '/' + GlbPiRefId;
                }
                else {
                    alert('Error');
                }
            }
            else {
                alert('Error');
            }

        });
        return false;
    }

    MakePostRequest(base_path + "storesuser/bomitem_received_details", "rfrom=1&oid=" + GlbOrderId + "&bomPurRequestId=" + GlbRequestId, "json", function (data) {
        if (data.errcode == 1) {
            GlbBomPurIndentNo = data.purIndentNo;
            console.log(GlbBomPurIndentNo, 'GlbBomPurIndentNo');
        }
    });

    var insertedRow = function (instance) {
        //console.log($("#bomStoresReceivedInvoiceJxl").find("tbody tr").length, 'le');

        //var GlbJexcelTr = $(".jexcel tr").length;
        var GlbJexcelTr = $("#bomStoresReceivedInvoiceJxl").find("tbody tr").length;
        console.log(GlbJexcelTr,'GlbJexcelTr');

        var lotApprovalStatusEle = '<table class="table" id="lotApprovalStatus_'+GlbJexcelTr+'">\n' +
            '                                <tbody><tr id="">\n' +
            '                                    <td class="verticalThead" style="width: 70px;">STATUS</td>\n' +
            '                                    <td>\n' +
            '                                                                                <select class="form-control" onchange="allAuthStatusChange(this,\'itemverifyauthstatus\','+GlbJexcelTr+',0)">\n' +
            '                                            <option value="">Choose</option>\n' +
            '                                            <option value="1">Pending</option><option value="2">Accept</option><option value="3">Discrepancy</option><option value="4">Reject</option>                                        </select>\n' +
            '                                    </td>\n' +
            '                                    <td>\n' +
            '                                        <select class="form-control" onchange="allAuthStatusChange(this,\'qtyverifyauthstatus\','+GlbJexcelTr+',0)">\n' +
            '                                            <option value="">Choose</option>\n' +
            '                                            <option value="1">Pending</option><option value="2">Accept</option><option value="3">Discrepancy</option><option value="4">Reject</option>                                        </select>\n' +
            '                                    </td>\n' +
            '                                    <td>\n' +
            '                                                                                <select class="form-control" onchange="allAuthStatusChange(this,\'qualityanaauthstatus\','+GlbJexcelTr+',0)">\n' +
            '                                            <option value="">Choose</option>\n' +
            '                                            <option value="1">Pending</option><option value="2">Accept</option><option value="3">Discrepancy</option><option value="4">Reject</option>                                        </select>\n' +
            '                                    </td>\n' +
            '                                    <td>\n' +
            '                                                                                <select class="form-control" onchange="allAuthStatusChange(this,\'invoiceverifyauthstatus\','+GlbJexcelTr+',0)">\n' +
            '                                            <option value="">Choose</option>\n' +
            '                                            <option value="1">Pending</option><option value="2">Accept</option><option value="3">Discrepancy</option><option value="4">Reject</option>                                        </select>\n' +
            '                                    </td>\n' +
            '                                    <td>\n' +
            '                                        <select class="form-control" id="mgmtauthstauts_'+GlbJexcelTr+'" name="mgmtauthstauts_'+GlbJexcelTr+'">\n' +
            '                                            <option value="1">Pending</option><option value="2">Approved</option><option value="3">Declined</option>                                        </select>\n' +
            '                                    </td>\n' +
            '                                    <td>\n' +
            '                                                                                <select class="form-control" onchange="allAuthStatusChange(this,\'itemreadyauthstatus\','+GlbJexcelTr+',0)">\n' +
            '                                            <option value="">Choose</option>\n' +
            '                                            <option value="1">Pending</option><option value="2">Accept</option><option value="3">Discrepancy</option><option value="4">Reject</option>                                        </select>\n' +
            '                                    </td>\n' +
            '                                </tr>\n' +
            '                                <tr>\n' +
            '                                    <td class="verticalThead">DONE BY</td>\n' +
            '                                    <td>\n' +
            '                                        <div id="itemverifyauthstatus'+GlbJexcelTr+'_0">\n' +
            '                                                                                    </div>\n' +
            '                                    </td>\n' +
            '                                    <td>\n' +
            '                                        <div id="qtyverifyauthstatus'+GlbJexcelTr+'_0">\n' +
            '                                                                                    </div>\n' +
            '                                    </td>\n' +
            '                                    <td>\n' +
            '                                        <div id="qualityanaauthstatus'+GlbJexcelTr+'_0">\n' +
            '                                                                                    </div>\n' +
            '                                    </td>\n' +
            '                                    <td>\n' +
            '                                        <div id="invoiceverifyauthstatus'+GlbJexcelTr+'_0">\n' +
            '                                                                                    </div>\n' +
            '                                    </td>\n' +
            '                                    <td>\n' +
            '                                        <div id="mgmtauthstatus'+GlbJexcelTr+'_0"></div>\n' +
            '                                    </td>\n' +
            '                                    <td>\n' +
            '                                        <div id="itemreadyauthstatus'+GlbJexcelTr+'_0">\n' +
            '                                                                                    </div>\n' +
            '                                    </td>\n' +
            '                                </tr>\n' +
            '                                <tr>\n' +
            '                                    <td class="verticalThead"> Date &amp; Time</td>\n' +
            '                                    <td>\n' +
            '                                        <div id="itemverifyauthstatusDatetime_'+GlbJexcelTr+'_0">\n' +
            '                                                                                    </div>\n' +
            '                                    </td>\n' +
            '                                    <td>\n' +
            '                                        <div id="qtyverifyauthstatusDatetime_'+GlbJexcelTr+'_0">\n' +
            '                                                                                    </div>\n' +
            '                                    </td>\n' +
            '                                    <td>\n' +
            '                                        <div id="qualityanaauthstatusDatetime_'+GlbJexcelTr+'_0">\n' +
            '                                                                                    </div>\n' +
            '                                    </td>\n' +
            '                                    <td>\n' +
            '                                        <div id="invoiceverifyauthstatusDatetime_'+GlbJexcelTr+'_0">\n' +
            '                                                                                    </div>\n' +
            '                                    </td>\n' +
            '                                    <td>\n' +
            '                                        <div id="mgmtauthstatusDatetime_'+GlbJexcelTr+'_0"></div>\n' +
            '                                    </td>\n' +
            '                                    <td>\n' +
            '                                        <div id="itemreadyauthstatusDatetime_'+GlbJexcelTr+'_0">\n' +
            '                                                                                    </div>\n' +
            '                                    </td>\n' +
            '                                </tr>\n' +
            '                            </tbody></table>';
        $("#moreLotApprovalTbl").append('<table id="lotApprovalStatus_' + GlbJexcelTr + '" class="table">' + lotApprovalStatusEle + '</table>');
    }

    var deletedRow = function (instance) {
        $("#moreLotApprovalTbl").remove();
    }


    // A custom method to SUM all the cells in the current column
    var SUMCOL = function (instance, columnId) {
        var total = 0;
        for (var j = 0; j < instance.options.data.length; j++) {
            if (Number(instance.records[j][columnId - 1].innerHTML)) {
                total += Number(instance.records[j][columnId - 1].innerHTML);
            }
        }
        return total;
    }
    var SupplyStatus = ["-", "Pending", "Received - Part Qty.", "Received - Full Qty.", "Received - Short Qty.","Received - Excess Qty.", "Not Received - P.I. Cancelled"];

    if(GlbItemizedBomRecdInvoiceJxl == 0) {
        GlbItemizedBomRecdInvoiceJxl = [
            []
        ];
    }
    else {
        GlbItemizedBomRecdInvoiceJxl = JSON.parse(GlbItemizedBomRecdInvoiceJxl);
    }
    bomStoresReceivedInvoiceJxl = jexcel(document.getElementById("bomStoresReceivedInvoiceJxl"), {
        columns: [
            /*{type: 'dropdown', title: 'P.I. No.', width: 210, source: GlbBomPurIndentNo, wordWrap: true},*/
            {type: 'text', title: 'Invoice No.', width: 300},
            {type: 'calendar', title: 'Invoice Date', width: 120},
            {type: 'text', title: 'Invoice Qty.', width: 120},
            {type: 'dropdown', title: 'Unit Of Measure', width: 100, source: JSON.parse(GlbUom)},
            {type: 'calendar', title: 'Material Received Date', width: 100},
            {type: 'text', title: 'Material Received Qty.', width: 120},
            {type: 'dropdown', title: 'Unit Of Measure', width: 100, source: JSON.parse(GlbUom)},
            {type: 'dropdown', title: 'Supply Status', width: 280, source: SupplyStatus, wordWrap: true},
            {type: 'hidden'},
            //{type: 'checkbox', title: 'Select', width: 80}
        ],
        allowInsertColumn: false,
        footers: [['', 'Total', '=SUMCOL(TABLE(), COLUMN())', '', '', '=SUMCOL(TABLE(), COLUMN())']],
        data: GlbItemizedBomRecdInvoiceJxl,
        oninsertrow: insertedRow,
        ondeleterow: deletedRow,
    });

    //let presentRows = $("#bomStoresReceivedInvoiceJxl").find("tbody tr").length;
    //console.log(presentRows,'presentRows');

    function fnSaveBomItemrecdInvoiceDetails(ids) {
        var Param = "rfrom=1&oid=" + GlbOrderId + "&bomPurRequestId=" + GlbRequestId + "&bomStoresReceivedInvoiceJxl=" +
            JSON.stringify(bomStoresReceivedInvoiceJxl.getData()) + "&bomPurIndentId=" + GlbPiRefId + "&itemrefno=" + GlbItemRefno+
            "&priKey="+GlbPriKey+"&bomItemId="+GlbBomItemId;
        MakePostRequest(base_path + 'dashboard/updateBomItemRecdInvoiceDetails', Param, 'json', fnSaveBomItemRecdInvoiceDetailsRes);
        return false;
    }

    function fnSaveBomItemRecdInvoiceDetailsRes(data) {
        if (data != '') {
            if (data.errcode == 1) {
                console.log(data);
                if(data.id) {
                    fnRedirectPageTimeOut(GlbCurretUrl);
                }
            }
        }
    }

</script>
<?php $this->load->view(CNFCOMPANY . 'template/pagefooter'); ?>