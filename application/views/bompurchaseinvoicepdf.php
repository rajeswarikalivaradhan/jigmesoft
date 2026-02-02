<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="<?php echo base_url();?>assets/css/jexcel/jquery.jexcel.min.css" />
<style type="text/css">
table#gridtbl th {
    background-color: #ccc;
    margin: 0 10px;
    padding: 0 10px;
}
</style>
</head>
<body>
<table>
    <tr><td style="text-align: center">PURCAHSE INDENT</td></tr>
    <tr><td>
            <table>
                <tr>
                    <td><b>FROM:</b></td>
                    <td><b>TO:</b></td>
                    <td style="text-align: center"><b>PURCHASE REFERENCE</b></td>
                </tr>
                <tr>
                    <td><b>NAME:</b>
                    <?php echo $ArrFromCompanyInfo['companyname'] ?></td>
                    <td><b>NAME:</b>
                        <?php echo '$VarTo' ?></td>
                    <td><b>P.I. REF. NO:</b> <?php echo @$ArrBasicInfo->pireferenceno ?></td>
                </tr>
                <tr>
                    <td>ADDRESS:
                    <?php echo $ArrFromCompanyInfo['address'] ?></td>
                    <td>ADDRESS:
                        <?php echo @$ArrBasicInfo->address ?></td>
                    <td>DATE & TIME: <?php echo date('d-m-Y',strtotime($ArrBasicInfo->datecreated)); ?></td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td>AGREED SUPPLY DATE: <?php if(empty($ArrBasicInfo->agrsupplydate)) echo '-'; else echo date('d-m-Y',strtotime($ArrBasicInfo->agrsupplydate)) ?></td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td>SUPPLY CUTOFF DATE: <?php if(empty($ArrBasicInfo->datecreated)) echo date('d-m-Y'); else echo date('d-m-Y',strtotime($ArrBasicInfo->datecreated)) ?></td>
                </tr>
                <tr>
                    <td>CONTACT NO:<?php echo $ArrFromCompanyInfo['mobile'] ?></td>
                    <td>CONTACT NO:<?php echo $ArrBasicInfo->phone ?></td>
                    <td>PAYMENT TERMS:<?php if(!empty($ArrBasicInfo->paymentterms)) echo $ArrBasicInfo->paymentterms ?></td>
                </tr>
                <tr>
                    <td>E-MAIL ID:<?php echo $ArrFromCompanyInfo['username'] ?></td>
                    <td>E-MAIL ID:<?php echo $ArrBasicInfo->emailid ?></td>
                </tr>
                <tr>
                    <td>GST NO: </td>
                    <td>GST NO: <?php echo @$ArrBasicInfo->gstno ?></td>
                    <td style="text-align: center"><b>INTERNAL REFERENCE</b></td>
                </tr>
                <tr>
                    <td>IE CODE:</td>
                    <td>IE CODE: <?php echo @$ArrBasicInfo->iecode ?></td>
                    <td>WIP NO: <?php if(!empty($isiorcode)) echo $isiorcode; else echo $ArrBasicInfo->isriorcode ?></td>
                </tr>
            </table>
        </td>
    </tr>
    <tr><td>&nbsp;</td></tr>
    <tr>
        <td>Herewith, we place an order for the following items:<?php
        //echo '<pre>'; print_r($invoicegrid); die('');
        ?>
        </td>
    </tr>
    <tr><td>&nbsp;</td></tr>
    <tr>
        <td>
            <table id="gridtbl">
                <tr>
                    <th>S.NO</th>
                    <th>Item Description / (%) Blend / Content / Material</th>
                    <th>Item Code</th>
                    <th>Item Colour Code</th>
                    <th>Size or Dim (W*L*H)</th>
                    <th>Unit of Measure</th>
                    <th>Quantity</th>
                    <th>Unit Rate</th>
                    <th>Amount</th>
                </tr>
                <?php
                $i=1;
                foreach ($ArrInvoiceTbl as $data) {
                    ?>
                    <tr>
                        <td><?php echo $i ?></td>
                        <td><?php echo $data[0] ?></td>
                        <td><?php echo $data[1] ?></td>
                        <td><?php echo $data[2] ?></td>
                        <td><?php echo $data[3] ?></td>
                        <td><?php echo $data[4] ?></td>
                        <td><?php echo $data[5] ?></td>
                        <td><?php echo $data[6] ?></td>
                        <td><?php echo $data[7] ?></td>
                    </tr>
                    <?php
                    $i++;
                }
                ?>
            </table>
        </td>
    </tr>
    <tr>
        <td>
            <table style="text-align: right">
                <tr>
                    <td><b>Sub Total:</b> <?php echo $ArrBasicInfo->subtotal ?></td>
                </tr>
                <tr>
                    <td>
                        <table>
                            <tr>
                                <td><b>SGST: </b> </td>
                                <td><?php if($ArrBasicInfo->stategst > 0) {
                                        $sgstpercent = $ArrBasicInfo->stategst / $ArrBasicInfo->subtotal;
                                        echo $sgstpercent * 100;
                                    } ?> </td>
                                <td>&#37;</td>
                                <td><?php echo $ArrBasicInfo->stategst; ?></td>
                            </tr>
                            <tr>
                                <td><b>CGST</b> </td>
                                <td><?php
                                    if($ArrBasicInfo->centralgst > 0) {
                                        $cgstpercent = $ArrBasicInfo->centralgst / $ArrBasicInfo->subtotal;
                                        echo $cgstpercent * 100;
                                    }
                                    ?> </td>
                                <td>&#37;</td>
                                <td><?php echo $ArrBasicInfo->centralgst; ?></td>
                            </tr>
                            <tr>
                                <td><b>IGST</b> </td>
                                <td><?php
                                    if($ArrBasicInfo->integratedgst > 0) {
                                        $igstpercent = $ArrBasicInfo->integratedgst / $ArrBasicInfo->subtotal;
                                        echo $igstpercent * 100;
                                    }
                                ?> </td>
                                <td>&#37;</td>
                                <td><?php echo $ArrBasicInfo->integratedgst; ?></td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td><b>Grand Total:</b>
                        <?php echo $ArrBasicInfo->grandtotal ?>
                    </td>
                </tr>
                <tr>
                    <td style="text-align: left"><b>Rupees in words: </b> <?php echo '' ?></td>
                </tr>
                <tr><td>&nbsp;</td></tr>
                <tr>
                    <td style="text-align: left"><b>Note:</b> If goods are supplied beyond cutoff date, it is up to the discretion of the company to accept or reject the goods. Terms and conditions as agreed upon.</td>
                </tr>
                <tr><td>&nbsp;</td></tr>
                <tr>
                    <td>
                        <table>
                            <tr>
                                <td>
                                    <table>
                                        <tr>
                                            <th>PURCHASER CONTACT DETAILS</th>
                                            <th>VENDOR CONTACT DETAILS</th>
                                        </tr>
                                        <tr>
                                            <td>
                                            NAME:
                                            </td>
                                            <td>
                                                NAME:
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                MOBILE::
                                            </td>
                                            <td>
                                                MOBILE::
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                E-MAIL ID::
                                            </td>
                                            <td>
                                                E-MAIL ID::
                                            </td>
                                        </tr>
                                    </table>
                                </td>

                                <td>
                                    <table>
                                        <tr>
                                            <td>Prepared by:</td>
                                            <td>Authorized by:</td>
                                            <td>Approved by:</td>
                                        </tr>
                                        <tr><td></td></tr>
                                        <tr><td></td></tr>
                                        <tr><td></td></tr>
                                        <tr>
                                            <td>Name & Signature</td>
                                            <td>Name & Signature</td>
                                            <td>Name & Signature</td>

                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
<!-- Optional JavaScript -->
</body>
</html>