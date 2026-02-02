<table id="newOrderEntrycommonTbl" class="table  table-responsive">
    <tr>
        <td style="width: 300px; padding: 5px">
            <table class="table  table-responsive">
                <tr>
                    <td class="pinkHeading" style="padding-left: 10px"><strong><?php echo $ArrCompanyInfo[0]['companyname']; ?></strong></td>
                </tr>
                <tr>
                    <td style="padding-left: 10px"><?php echo $ArrCompanyInfo[0]['address']; ?></td>
                </tr>

            </table>
        </td>
        <td style="padding: 5px">
            <table class="table  table-responsive">
                <tr>
                    <td class="secondheading" style="padding-left: 10px">Merch. Name</td>
                    <td style="padding-left: 10px">
                        <?php echo $ArrMerchant['contactname'] ?>
                    </td>
                    <td class="secondheading" style="padding-left: 10px">Team Name</td>
                    <td style="padding-left: 10px"><?php echo @$ArrTeamInfo['contactname'] ?></td>
                </tr>
                <tr>
                    <td class="secondheading" style="padding-left: 10px">Merch. Code</td>
                    <td id="merchantCode" style="padding-left: 10px">
                        <?php echo $ArrMerchant['code']?>
                    </td>
                    <td class="secondheading" style="padding-left: 10px">Team Code</td>
                    <td style="padding-left: 10px"><?php echo @$ArrTeamInfo['code'] ?></td>
                </tr>
                <tr>
                    <td class="secondheading" style="padding-left: 10px">Contact No.</td>
                    <td id="merchantContactNo" style="padding-left: 10px"><?php echo $ArrMerchant['mobile'] ?></td>
                    <td class="secondheading" style="padding-left: 10px">Contact No.</td>
                    <td style="padding-left: 10px"><?php echo @$ArrTeamInfo['mobile'] ?></td>
                </tr>
                <tr>
                    <td class="secondheading" style="padding-left: 10px">E-Mail Id</td>
                    <td id="merchantEmail" style="padding-left: 10px"><?php echo $ArrMerchant['username'] ?></td>
                    <td class="secondheading" style="padding-left: 10px">E-Mail Id</td>
                    <td style="padding-left: 10px"><?php echo @$ArrTeamInfo['username']; ?></td>
                </tr>

            </table>
        </td>
        <td style="padding: 5px">
            <table class="table  table-responsive">
                <tr>
                    <td colspan="4" align="center" class="pinkHeading"><strong>INTERNAL REFERENCE NO.</strong></td>
                </tr>
                        <tr>
                            <td class="secondheading" style="padding-left: 10px">WIP No.</td>
                            <td id="frmIorNumber" style="padding-left: 10px" colspan="3">
                                <div id="frmBasicWipRefNo"><?php echo $ArrOrderEnqData['isriorcode']; ?></div>
                            </td>
                        </tr>
                        <tr>
                            <td class="secondheading" style="padding-left: 10px">
                                Date & Time
                            </td>
                            <td style="padding-left: 10px" colspan="3">
                                <?php
                                echo $ArrOrderEnqData['formattedDateUpdated'];
                                ?>
                            </td>
                        </tr>
                    <?php

                ?>
                <tr>
                    <td class="secondheading" style="width: 75px; padding-left: 10px">Exc. Rate - Static</td>

                    <td style="width: 60px; padding-left: 10px">
                        <?php if(!empty($ArrOrderCommonData['orderbookingrate'])) echo $ArrOrderCommonData['orderbookingrate'] ?>
                    </td>

                    <td class="secondheading" style="width: 75px; padding-left: 10px">Dynamic</td>

                    <td style="width: 60px; padding-left: 10px">
                        <?php if(!empty($ArrOrderCommonData['orderrealization'])) echo $ArrOrderCommonData['orderrealization'] ?>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>

<div style="background-color: rgb(210 253 249); padding-top: 10px; padding-left: 10px"><b style="font-size: 14px !important; font-weight: 600">ORDER DETAILS</b></div>

<table class="table  table-responsive" style="margin: 5px">

    <tr>
        <td class="secondheading" style="width: 100px; padding-left: 10px; padding-top: 7px">Order Ref. No.</td>
        <td style="width: 235px; padding-left: 10px; padding-top: 7px"><?php echo "Order " . $ArrOrderEnqData['id']; ?></td>

        <td class="secondheading" style="width: 50px; padding-left: 10px; padding-top: 7px">Brand</td>
        <td style="width: 170px; padding-left: 10px; padding-top: 7px">
            <?php
            echo $brandName;
            ?>
        </td>

        <td class="secondheading" style="width: 50px; padding-left: 10px; padding-top: 7px">Season</td>
        <td style="width: 200px; padding-left: 10px; padding-top: 7px; padding-right: 8px"><?php echo $ArrOrderCommonData['season'] ?></td>

        <td class="secondheading" style="width: 70px; padding-left: 10px; padding-top: 7px">Class</td>
        <td style="width: 200px; padding-left: 10px; padding-top: 7px; padding-right: 8px">
            <?php echo $ArrOrderCommonData['class'] ?>
        </td>

        <td class="secondheading" style="width: 100px; padding-left: 10px; padding-top: 7px">Total Qty.</td>
        <td style="width: 200px; padding-left: 10px; padding-top: 7px; padding-right: 8px">
            <?php $VarPcsOrSet = unserialize(ARRPCSSET); ?>
            <?php echo $ArrOrderEnqData['exporderqty'] . ' ' . $VarPcsOrSet[$ArrOrderEnqData['pcsorset']] ?>
        </td>
    </tr>

    <tr>
        <td class="secondheading" style="padding-left: 10px;  padding-top: 7px">Style Ref. No.</td>
        <td style="padding-left: 10px; padding-top: 7px">
            <input type="hidden" name="frmStyleRefNo"
                   id="frmStyleRefNo"
                   class="form-control"
                   value="<?php echo $ArrOrderEnqData['stylenamerefno'] ?>">
            <?php echo $ArrOrderEnqData['stylenamerefno'] ?>
        </td>

        <td class="secondheading" style="padding-left: 10px; padding-top: 7px">Buyer</td>
        <td style="padding-left: 10px; padding-top: 7px"><?php echo $buyerName ?></td>

        <td class="secondheading" style="padding-left: 10px; padding-top: 7px">Div./Dept.</td>
        <td style="width: 200px; padding-left: 10px; padding-top: 7px; padding-right: 8px">
            <?php echo $ArrOrderCommonData['divdept'] ?>
        </td>

        <td class="secondheading" style="padding-left: 10px; padding-top: 7px">Sub Class</td>
        <td style="width: 200px; padding-left: 10px; padding-top: 7px; padding-right: 8px">
            <?php echo $ArrOrderCommonData['sclass'] ?>
        </td>
        <td class="secondheading" style="padding-left: 10px; padding-top: 7px">
            Price Per Unit
        </td>

        <td style="padding-left: 10px; padding-top: 7px">
            <?php
            $ArrCurrency = unserialize(ARRCURRENCYLIST);
            echo $ArrOrderEnqData['confirmprice'] .' ' . $ArrCurrency[$ArrOrderEnqData['currency']];
            ?>
        </td>

    </tr>

    <tr>
        <td class="secondheading" style="padding-left: 10px; padding-top: 7px">Style Descript.</td>
        <td style="padding-left: 10px; padding-right: 7px" colspan="7">
            <div class="customcontrol" style="padding-left: 10px">
                <?php echo $ArrOrderEnqData['styledesc'] ?>
            </div>

            <input type="hidden" name="frmStyleName"
                   id="frmStyleName"
                   class="form-control"
                   value="<?php echo $ArrOrderEnqData['styledesc'] ?>">
        </td>

        <td class="secondheading" style="padding-left: 10px; padding-top: 7px">Pay. Terms</td>
        <td style="padding-left: 10px; padding-right: 10px; padding-top: 7px">
            <?php
            echo $ArrOrderCommonData['payterms'];
            ?>

        </td>
    </tr>

</table>