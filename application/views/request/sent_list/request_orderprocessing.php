 
    <!-- *********************** ORDER PROCESSING START HERE ************************-->
    
    <section class="content-header" style="padding-top: 0">
        <!-- <div class="card-header p-3 border-0 bgc-white tbl-head mb-1">
            <h1 class="card-title text-white cus-h text-500">ORDER PROCESSING</h1>
        </div> -->  
          <?php 
    $subcompany_datas = $subcompany_data[0];

    //print_r($subcompany_datas);
?>      
        <div class="order-processing">
            <table id="" class="table">
                <tbody>
                    <tr>
                        <td class="ord-procs-cell">
                            <table class="table tbl-procs-border">
                            <tbody>
                                <tr>
                                    <td class="process-main-head">
                                        <strong><?php echo $subcompany_datas['companyname']; ?></strong>
                                    </td>
                                </tr>
                                <tr >
                                    <td class="process-main-value"><b style="font-size: 16px !Important">Address:</b> <?php echo $subcompany_datas['address'];?>, <?php echo $subcompany_datas['city'];?> - <?php echo $subcompany_datas['pincode'];?>.
           <?php echo $subcompany_datas['state'];?>, <?php echo $subcompany_datas['country'];?>.</td>
                                </tr>
                                 <tr >
                                    <td class="process-main-value"><b style="font-size: 16px !Important" >GST No: </b><?php echo $subcompany_datas['gst_no']; ?>.<br><b style="font-size: 16px !Important"> IE Code:</b> <?php echo $subcompany_datas['IECODE']; ?>.</td>
                                </tr>

                                 
                            </tbody>
                            </table>
                        </td>
                        
                        <td class="ord-procs-cell">
                            <table class="table tbl-procs-border">
                            <tbody>
                                <tr>
                                    <td class="process-title">Merch. Name: </td>
                                    <td class="process-value"> <?php echo @$ArrCommonHeaderData['merchantName'] ?></td>
                                </tr>
                                <tr>
                                    <td class="process-title">Merch. Code:</td>
                                    <td class="process-value"> <?php echo @$ArrCommonHeaderData['merchantCode'] ?></td>
                                </tr>
                                <tr>
                                    <td class="process-title">Contact No:</td>
                                    <td class="process-value"> <?php echo @$ArrCommonHeaderData['merchantMobile'] ?></td>
                                </tr>
                                <tr>
                                    <td class="process-title">e-mail ID:</td>
                                    <td class="process-value"> <?php echo @$ArrCommonHeaderData['merchantEmail'] ?></td>
                                </tr>
                            </tbody>
                            </table>
                        </td>
                        
                        <td class="ord-procs-cell">
                            <table class="table tbl-procs-border">
                            <tbody>
                                <tr>
                                    <td class="process-title">Team Name:</td>
                                    <td class="process-value"><?php echo @$ArrCommonHeaderData['ArrTeam']['contactname'] ?></td>                                </tr>
                                <tr>
                                    <td class="process-title">Team Code:</td>
                                    <td class="process-value"><?php echo @$ArrCommonHeaderData['ArrTeam']['code'] ?></td>
                                </tr>
                                <tr>
                                    <td class="process-title">Contact No:</td>
                                    <td class="process-value"><?php echo @$ArrCommonHeaderData['ArrTeam']['mobile'] ?></td>
                                </tr>
                                <tr>
                                    <td class="process-title">e-mail ID:</td>
                                    <td class="process-value"><?php echo @$ArrCommonHeaderData['ArrTeam']['username'] ?></td>
                                </tr>
                            </tbody>
                            </table>
                        </td>
                        
                        <td class="ord-procs-cell">
                            <table class="table tbl-procs-border">
                            <tbody>
                                <tr>
                                    <td class="process-main-head" colspan="4">
                                        <strong>INTERNAL REFERENCE NO.</strong>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="process-title">WIP No</td>
                                    <td class="process-value" colspan="3">
                                    <?php echo $ArrCommonHeaderData['ArrEnquiryDetails']['isriorcode']; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="process-title">Date & Time:</td>
                                    <td class="process-value" colspan="3">
                                    <?php
                                        echo isset($ArrCommonData->datecreated) ? date('d-m-Y H:i:s', strtotime($ArrCommonData->datecreated)) : date('d-m-Y H:i:s');
                                    ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="process-title">Total Order Qty.</td>
                                    <td class="process-value" colspan="2">
                                        <?php echo $ArrCommonHeaderData['ArrEnquiryDetails']['total_order_qty']; ?>
                                    </td>
                                    <td class="pad-0">
                                    <table class="table mar-b-0">
                                        <tbody>
                                            <tr>
                                                <td class="process-title">Pcs. / Set</td>
                                                <td class="process-value" colspan="3">
                                                    <?php echo $ArrCommonHeaderData['ArrEnquiryDetails']['uom']; ?>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    </td>
                                </tr>
                            </tbody>
                            </table>
                        </td>

                    </tr>
                </tbody>
            </table>
        </div>

        <div class="card-header pb-3 bgc-white border-0 " style="">
            <div class="card-title f-20">
                <b style="font-size: 20px !important; padding-left: 5px; margin-left: 3px;color: #333">ORDER DETAILS</b>
            </div>
        </div>
        <div class="col-12 pb-3 px-0">
            <div class="col-12 px-0" style="border-top: 1px solid #022b61;"></div>
        </div>

        <div class="order-details">
            <table id="" class="table">
                <tbody>
                    <tr>
                        <td class="ord-procs-cell">
                            <table class="table tbl-procs-border">
                            <tbody>
                                <tr>
                                    <td class="process-title detail-title">Order Ref. No:</td>
                                    <td class="process-value detail-value">
                                        <?php if (!empty($ArrCommonHeaderData['ArrEnquiryDetails']['orderenqrefno'])) echo $ArrCommonHeaderData['ArrEnquiryDetails']['orderenqrefno'] ?>
                                    </td>
                                    <td class="process-title detail-title">Brand:</td>
                                    <td class="process-value detail-value">
                                        <?php echo @$ArrCommonHeaderData['ArrEnquiryDetails']['brandname']; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="process-title detail-title">Style Ref. No:</td>
                                    <td class="process-value detail-value"> <?php echo @$ArrCommonHeaderData['ArrEnquiryDetails']['stylenamerefno']; ?></td>
                                    <td class="process-title detail-title">Buyer:</td>
                                    <td class="process-value detail-value"> <?php echo @$ArrCommonHeaderData['ArrEnquiryDetails']['buyername']; ?></td>
                                </tr>
                                <tr>
                                    <td class="process-title detail-title">Style Description:</td>
                                    <td class="process-value detail-value" colspan="3"> <?php echo @$ArrCommonHeaderData['ArrEnquiryDetails']['styledesc']; ?></td>
                                </tr>
                            </tbody>
                            </table>
                        </td>
                        
                        <td class="ord-procs-cell">
                            <table class="table tbl-procs-border">
                            <tbody>
                                <tr>
                                    <td class="process-title detail-title">Season:</td>
                                    <td class="process-value detail-value" colspan="3">
                                        <?php echo $ArrCommonHeaderData['ArrEnquiryDetails']['season']; ?>
                                    </td>
                                    <td class="process-title detail-title">Class:</td>
                                    <td class="process-value detail-value" colspan="3">
                                        <?php echo $ArrCommonHeaderData['ArrEnquiryDetails']['class']; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="process-title detail-title">Divi. /Dept:</td>
                                    <td class="process-value detail-value" colspan="3">
                                        
                                        <?php echo $ArrCommonHeaderData['ArrEnquiryDetails']['divi_dept']; ?>
                                    <td class="process-title detail-title">Sub Class:</td>
                                    <td class="process-value detail-value" colspan="3">
                                        <?php echo $ArrCommonHeaderData['ArrEnquiryDetails']['sub_class']; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="process-title detail-title">Size Range:</td>
                                    <td class="process-value detail-value" colspan="3">
                                        <?php echo $ArrCommonHeaderData['sizeValue']; ?>
                                    </td>
                                </tr>
                            </tbody>
                            </table>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

    </section>

    <!-- *********************** ORDER PROCESSING START HERE ************************-->
    