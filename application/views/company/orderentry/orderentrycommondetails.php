<?php $VarCurrentPosition = 0; $this->load->view(CNFCOMPANY."orderentry/pagination_links"); ?>
<table id="newOrderEntrycommonTbl" class="table  table-responsive">
    <tr>
        <td style="width: 300px; padding: 5px">
            <table class="table  table-responsive">
                <tr>
                    <td class="pinkHeading" style="padding-left: 10px"><strong><?php echo $ArrCommonHeaderData['companyName'] ?></strong></td>
                </tr>
                <tr>
                    <td style="padding-left: 10px"><?php echo $ArrCommonHeaderData['companyAddress']; ?></td>
                </tr>

            </table>
        </td>
        <td style="padding: 5px">
            <table class="table  table-responsive">
                <tr>
                    <td class="secondheading" style="padding-left: 10px">Merch. Name</td>
                    <td style="padding-left: 10px">
                        <?php echo @$ArrCommonHeaderData['merchantName']; ?>
                    </td>
                    <td class="secondheading" style="padding-left: 10px">Team Name</td>
                    <td style="padding-left: 10px"><?php echo @$ArrCommonHeaderData['ArrTeam']['contactname'] //echo '<pre>'; print_r($ArrTeam); die('die'); ?></td>
                </tr>
                <tr>
                    <td class="secondheading" style="padding-left: 10px">Merch. Code</td>
                    <td id="merchantCode" style="padding-left: 10px">
                        <?php echo $ArrCommonHeaderData['merchantCode']; ?>
                    </td>
                    <td class="secondheading" style="padding-left: 10px">Team Code</td>
                    <td style="padding-left: 10px"><?php echo @$ArrCommonHeaderData['ArrTeam']['code'] ?></td>
                </tr>
                <tr>
                    <td class="secondheading" style="padding-left: 10px">Contact No.</td>
                    <td id="merchantContactNo" style="padding-left: 10px"><?php echo $ArrCommonHeaderData['merchantMobile'] ?></td>
                    <td class="secondheading" style="padding-left: 10px">Contact No.</td>
                    <td style="padding-left: 10px"><?php echo @$ArrCommonHeaderData['ArrTeam']['mobile'] ?></td>
                </tr>
                <tr>
                    <td class="secondheading" style="padding-left: 10px">E-Mail Id</td>
                    <td id="merchantEmail" style="padding-left: 10px"><?php echo $ArrCommonHeaderData['merchantEmail'] ?></td>
                    <td class="secondheading" style="padding-left: 10px">E-Mail Id</td>
                    <td style="padding-left: 10px"><?php echo @$ArrCommonHeaderData['ArrTeam']['username'] ?></td>
                </tr>

            </table>
        </td>
        <td style="padding: 5px">
            <table class="table  table-responsive">
                <tr>
                    <td colspan="4" align="center" class="pinkHeading"><strong>INTERNAL REFERENCE NO.</strong></td>
                </tr>
				<?php $ArrISRIORText = unserialize(ARRISRIOR);
				if(!empty($ArrCommonHeaderData['ArrEnquiryDetails']['reqforisrior'])) {
					if ($ArrCommonHeaderData['ArrEnquiryDetails']['reqforisrior'] >= 1) { ?>
                        <tr>
                            <td class="secondheading" style="padding-left: 10px">WIP No.</td>
                            <td id="frmIorNumber" style="padding-left: 10px" colspan="3">
								<?php echo $ArrCommonHeaderData['ArrEnquiryDetails']['isriorcode']; ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="secondheading" style="padding-left: 10px">
                                Date & Time
                            </td>
                            <td style="padding-left: 10px" colspan="3">
								<?php
								echo isset($ArrCommonHeaderData['ArrCommonData']->datecreated) ? date('d-m-Y H:i:s', strtotime($ArrCommonHeaderData['ArrCommonData']->datecreated)) : date('d-m-Y H:i:s');
								?>
                            </td>
                        </tr>
					<?php }
				}
				?>
                <tr>
                    <td class="secondheading" style="width: 75px; padding-left: 10px">Exc. Rate - Static</td>

                    <td style="width: 60px; padding-left: 10px">
						<?php if(!empty($ArrCommonHeaderData['ArrCommonData']->orderbookingrate)) echo $ArrCommonHeaderData['ArrCommonData']->orderbookingrate ?>
                    </td>

                    <td class="secondheading" style="width: 75px; padding-left: 10px">Dynamic</td>

                    <td style="width: 60px; padding-left: 10px">
						<?php if(!empty($ArrCommonHeaderData['ArrCommonData']->orderrealization)) echo $ArrCommonHeaderData['ArrCommonData']->orderrealization ?>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>

<div style="background-color: rgb(210 253 249); padding-top: 10px; padding-left: 10px"><strong>ORDER DETAILS</strong></div>

<table class="table  table-responsive" style="margin: 5px">

    <tr>
        <td class="secondheading" style="width: 100px; padding-left: 10px; padding-top: 7px">Order Ref. No.</td>
        <td style="width: 235px; padding-left: 10px; padding-top: 7px"><?php if(!empty($ArrCommonHeaderData['ArrEnquiryDetails']['orderenqrefno'])) echo $ArrCommonHeaderData['ArrEnquiryDetails']['orderenqrefno'] ?></td>

        <td class="secondheading" style="width: 50px; padding-left: 10px; padding-top: 7px">Brand</td>
        <td style="width: 170px; padding-left: 10px; padding-top: 7px">
			<?php		
			echo $ArrCommonHeaderData['ArrEnquiryDetails']['brandname'];
			?>
        </td>

        <td class="secondheading" style="width: 50px; padding-left: 10px; padding-top: 7px">Season</td>
        <td style="width: 200px; padding-left: 10px; padding-top: 7px; padding-right: 8px"><?php if(!empty($ArrCommonHeaderData['ArrCommonData']->season)) echo $ArrCommonHeaderData['ArrCommonData']->season ?></td>

        <td class="secondheading" style="width: 70px; padding-left: 10px; padding-top: 7px">Class</td>
        <td style="width: 200px; padding-left: 10px; padding-top: 7px; padding-right: 8px">
            <?php if (!empty($ArrCommonHeaderData['ArrCommonData']->class)) echo $ArrCommonHeaderData['ArrCommonData']->class ?>
        </td>

        <td class="secondheading" style="width: 100px; padding-left: 10px; padding-top: 7px">Total Qty.</td>
        <td style="width: 200px; padding-left: 10px; padding-top: 7px; padding-right: 8px">
			<?php
			$ArrPcsSet = unserialize(ARRPCSSET);
			if($ArrPcsSet[$ArrCommonHeaderData['ArrEnquiryDetails']['pcsorset']]) {
				$VarPcsOrSet = $ArrPcsSet[$ArrCommonHeaderData['ArrEnquiryDetails']['pcsorset']];
			}
			else {
				$VarPcsOrSet = 0;
			}
			echo $ArrCommonHeaderData['ArrEnquiryDetails']['exporderqty'] . '&nbsp;'.'&nbsp;'.'&nbsp;' . $VarPcsOrSet;
			?>
        </td>
    </tr>

    <tr>
        <td class="secondheading" style="padding-left: 10px;  padding-top: 7px">Style Ref. No.</td>
        <td style="padding-left: 10px; padding-top: 7px">
            <input type="hidden" name="frmStyleRefNo"
                   id="frmStyleRefNo"
                   class="form-control"
                   value="<?php echo $ArrCommonHeaderData['ArrEnquiryDetails']['stylenamerefno'] ?>">
			<?php echo $ArrCommonHeaderData['ArrEnquiryDetails']['stylenamerefno'] ?>
        </td>

        <td class="secondheading" style="padding-left: 10px; padding-top: 7px">Buyer</td>
        <td style="padding-left: 10px; padding-top: 7px"><?php echo $ArrCommonHeaderData['ArrEnquiryDetails']['buyername']; ?></td>

        <td class="secondheading" style="padding-left: 10px; padding-top: 7px">Div./Dept.</td>
        <td style="width: 200px; padding-left: 10px; padding-top: 7px; padding-right: 8px">
                   <?php if (!empty($ArrCommonHeaderData['ArrCommonData']->divdept)) echo $ArrCommonHeaderData['ArrCommonData']->divdept ?>
        </td>

        <td class="secondheading" style="padding-left: 10px; padding-top: 7px">Sub Class</td>
        <td style="width: 200px; padding-left: 10px; padding-top: 7px; padding-right: 8px">
            <?php if (!empty($ArrCommonHeaderData['ArrCommonData']->sclass)) echo $ArrCommonHeaderData['ArrCommonData']->sclass ?>
        </td>
        <td class="secondheading" style="padding-left: 10px; padding-top: 7px">
            Price Per Unit
        </td>

        <td style="padding-left: 10px; padding-top: 7px">
			<?php
			$ArrPcsSet = unserialize(ARRPCSSET);
			if($ArrPcsSet[$ArrCommonHeaderData['ArrEnquiryDetails']['pcsorset']]) {
				$VarPcsOrSet = $ArrPcsSet[$ArrCommonHeaderData['ArrEnquiryDetails']['pcsorset']];
			}
			else {
				$VarPcsOrSet = 0;
			}
			//echo $VarPcsOrSet;

			$ArrCurrency = unserialize(ARRCURRENCYLIST);
			echo $ArrCommonHeaderData['ArrEnquiryDetails']['confirmprice'].'&nbsp;'.'&nbsp;'.'&nbsp;'.$ArrCurrency[$ArrCommonHeaderData['ArrEnquiryDetails']['currency']];
			?>
        </td>

    </tr>

    <tr>
        <td class="secondheading" style="padding-left: 10px; padding-top: 7px">Style Descript.</td>
        <td style="padding-left: 10px; padding-right: 7px" colspan="7">
            <div class="customcontrol" style="padding-left: 10px">
				<?php echo $ArrCommonHeaderData['ArrEnquiryDetails']['styledesc'] ?>
            </div>

            <input type="hidden" name="frmStyleName"
                   id="frmStyleName"
                   class="form-control"
                   value="<?php echo $ArrCommonHeaderData['ArrEnquiryDetails']['styledesc'] ?>">
        </td>

        <td class="secondheading" style="padding-left: 10px; padding-top: 7px">Pay. Terms</td>
        <td style="padding-left: 10px; padding-right: 10px; padding-top: 7px">
			<?php if(!empty($ArrCommonHeaderData['ArrCommonData']->payterms)) echo $ArrCommonHeaderData['ArrCommonData']->payterms ?>

        </td>
    </tr>



</table>

