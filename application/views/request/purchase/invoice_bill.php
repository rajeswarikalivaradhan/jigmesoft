

<?php
//print_r($pi_data); exit;
$company_datas = $company_data[0];  
$vendor_datas = $vendor_data[0];  
$pi_datas = $pi_data[0];
?>
<table width="750" align="center" style="border-collapse:collapse;border-top:1px solid black;border-bottom:1px solid black;border-right:1px solid black;border-left:1px solid black;">
        <tr>
        <td scope="row" rowspan="5" width="3"></td>
          <td align="center"><b><?php echo $company_datas['companyname']; ?></b></td>
          
        </tr>
        <tr>
          <td align="center"><?php echo $company_datas['address']; ?></td>
          
        </tr>
        <tr>
          <td align="center"><b><?php echo $company_datas['mobile']; ?> / <?php echo $company_datas['emailid']; ?> / GST No. / IE Code </td>   
        </tr>

      </table>

      <table width="750" align="center" style="border-collapse:collapse;border-bottom:1px solid black;border-right:1px solid black;border-left:1px solid black; ">
          <tr>
              <td align="center" style="padding: 10px;"><b>PURCHASE INDENT </td>   
          </tr>
      </table>
     
      <table width="750" align="center" style="border-collapse:collapse;border-right:1px solid black;border-left:1px solid black;">
        <tr>
          <td colspan="2"  align="left"><b style="font-size:16px;">To</b></td>
          <td colspan="2" align="center"  style="font-size:16px;padding:3px;"><b>PURCHASE REFERENCE</b></td>
        </tr>

        <tr>
          <td  align="right" width="80" style="border-top:1px solid black;border-right:1px solid black;border-left:1px solid black;font-size:14px;">Name:</td>
          <td  align="left" width="280" style="border-top:1px solid black;border-right:1px solid black;font-size:14px;padding:3px;"><b><?php echo $vendor_datas['vendorname'];?></b></td>
          <td  align="right" width="120" style="border-top:1px solid black;border-right:1px solid black;font-size:14px;padding:3px;">P.I. Ref.No: </td>
          <td  align="left" width="240" style="border-top:1px solid black;border-right:1px solid black;font-size:14px;padding:3px;"><b><?php echo $pi_datas['pi_ref_queue_no']; ?></b></td>
        </tr>

        <tr>
          <td  align="right" width="80" style="border-top:1px solid black;border-right:1px solid black;border-left:1px solid black;font-size:14px;">Address:</td>
          <td  align="left" rowspan="2" width="280" style="border-top:1px solid black;border-right:1px solid black;font-size:14px;padding:3px;"><b><?php echo $vendor_datas['address'];?></b></td>
          <td  align="right" width="120" style="border-top:1px solid black;border-right:1px solid black;font-size:14px;padding:3px;">Date & Time: </td>
          <td  align="left" width="240" style="border-top:1px solid black;border-right:1px solid black;font-size:14px;padding:3px;"><b><?php echo $pi_datas['pi_dt']; ?></b></td>
        </tr>

        <tr>
          <td  align="right" width="80" style="border-top:1px solid black;border-right:1px solid black;border-left:1px solid black;font-size:14px;"></td>
          <!--<td  align="left" width="280" style="border-top:1px solid black;border-right:1px solid black;font-size:14px;padding:3px;"><b><?php echo $vendor_datas['address'];?></b></td>-->
          <td  align="right" width="120" style="border-top:1px solid black;border-right:1px solid black;font-size:14px;padding:3px;">PURCHASE TYPE: </td>
          <td  align="left" width="240" style="border-top:1px solid black;border-right:1px solid black;font-size:14px;padding:3px;"><b><?php echo $pi_datas['purchase_type']; ?></b></td>
        </tr>

        <tr>
          <td  align="right" width="80" style="border-top:1px solid black;border-right:1px solid black;border-left:1px solid black;font-size:14px;">Contact No:</td>
          <td  align="left" width="280" style="border-top:1px solid black;border-right:1px solid black;font-size:14px;padding:3px;"><b><?php echo $vendor_datas['mobile'];?></b></td>
          <td  align="right" width="140" style="border-top:1px solid black;border-right:1px solid black;font-size:14px;padding:3px;">Exp. Date of Delivery: </td>
          <td  align="left" width="240" style="border-top:1px solid black;border-right:1px solid black;font-size:14px;padding:3px;"><b><?php echo $pi_datas['exp_dod']; ?></b></td>
        </tr>

        <tr>
          <td  align="right" width="80" style="border-top:1px solid black;border-right:1px solid black;border-left:1px solid black;font-size:14px;">e-mail ID:</td>
          <td  align="left" width="280" style="border-top:1px solid black;border-right:1px solid black;font-size:16px;padding:3px;"><b><?php echo $vendor_datas['emailid'];?></b></td>
          <td  align="center" width="120" colspan="2" style="border-top:1px solid black;border-right:1px solid black;font-size:16px;padding:3px;">INTERNAL REFERENCE </td>
        </tr>

        <tr>
          <td  align="right" width="80" style="border-top:1px solid black;border-right:1px solid black;border-left:1px solid black;font-size:14px;">GST No:</td>
          <td  align="left" width="280" style="border-top:1px solid black;border-right:1px solid black;font-size:14px;padding:3px;"><b><?php echo $vendor_datas['gstno'];?></b></td>
          <td  align="right" width="120" style="border-top:1px solid black;border-right:1px solid black;font-size:14px;padding:3px;">WIP No: </td>
          <td  align="left" width="240" style="border-top:1px solid black;border-right:1px solid black;font-size:14px;padding:3px;"><b><?php echo $pi_datas['isriorcode']; ?></b></td>
        </tr>

        <tr>
          <td  align="right" width="80" style="border-top:1px solid black;border-right:1px solid black;border-left:1px solid black;font-size:14px;">IE Code:</td>
          <td  align="left" width="280" style="border-top:1px solid black;border-right:1px solid black;font-size:14px;padding:3px;"><b><?php echo $vendor_datas['iecode'];?></b></td>
          <td  align="right" width="120" style="border-top:1px solid black;border-right:1px solid black;font-size:14px;padding:3px;">Queue No: </td>
          <td  align="left" width="240" style="border-top:1px solid black;border-right:1px solid black;font-size:14px;padding:3px;"><b><?php echo @$pi_datas['ref_queue_no']; ?></b></td>
        </tr>
        
        <tr>
            <td colspan="4" style="border-bottom:1px solid black;border-right:1px solid black;border-left:1px solid black;"></td>
        </tr>
        <tr>
            <td colspan="4" style="padding-top:20px;">Herewith, we place an order for the following items :</td>
        </tr>

      </table>
      <table width="750"   height="560" align="center" style="border-bottom:1px solid black;border-top:1px solid black;border-right:1px solid black;border-left:1px solid black;border-collapse:collapse;">
        <tr>

          <td width="42" height="30" align="center" style="border-right:1px solid black;border-bottom:1px solid black;" scope="row">S.No</td>
          <td width="200" style="border-right:1px solid black;border-bottom:1px solid black;" align="left">Item Description / Blend (%) / Content / Material / Gar. Size </td>
          <td width="200" style="border-right:1px solid black;border-bottom:1px solid black;" align="center">Item Code / Item Colour Code / Size (L*W*H) / UOM </td>
          <td width="77" style="border-right:1px solid black;border-bottom:1px solid black;" align="right">Qty.</td>
          <td width="77" style="border-right:1px solid black;border-bottom:1px solid black;" align="right">UOM</td>
          <td width="77" style="border-right:1px solid black;border-bottom:1px solid black;" align="right">Unit Rate (Rs.)</td>
          <td width="77" style="border-right:1px solid black;border-bottom:1px solid black;" align="right">Amount (Rs.)</td>
          <td width="77" style="border-right:1px solid black;border-bottom:1px solid black;" align="right">GST (%)</td>
          <td width="77" style="border-right:1px solid black;border-bottom:1px solid black;" align="right">Sub Total (Rs.)</td>
     
          <!-- <td width="96" style="border-right:1px solid black;border-bottom:1px solid black;" align="right">Total Amt</td> -->
        </tr>


          
     <?php 
     $tot_amt = 0;
     $all_tot = 0;
     foreach($item_data as $r) { ?>
     
       <tr>
      <td height="29" align="center" style="border-right: 1px solid black;"><strong>1</strong></td>
      <td align="left" style="border-right: 1px solid black;"><strong><?php echo $r['item_desc'];?> / <?php echo $r['bcm'];?> / <?php echo $r['garment_size'];?></strong></td>
      <td align="center" style="border-right: 1px solid black;"><strong><?php echo $r['appr_item_code'];?> / <?php echo $r['appr_item_col_code'];?> / <?php echo $r['size_dim'];?> / <?php echo $r['uoms'];?></strong></td>     
      <td align="right" style="border-right:1px solid black;"><strong><?php echo $r['qty'];?></strong></td>
      <td align="right" style="border-right:1px solid black;"><strong><?php echo $r['requirement_uom'];?></strong></td>
      <td align="right" style="border-right:1px solid black;"><strong><?php echo $r['unit_rate'];?></strong></td>
      <td align="right" style="border-right:1px solid black;"><strong><?php echo $r['amount'];?></strong></td>
      <?php if($r['mode'] == 'within') { ?>
          <td align="right" style="border-right:1px solid black;"><strong><?php echo $r['gst'];?></strong></td>
      <?php } else if($r['mode']=='inter') { ?>
      <td align="right" style="border-right:1px solid black;"><strong><?php echo $r['igst'];?></strong></td>
      <?php } else { ?>
      <td align="right" style="border-right:1px solid black;"><strong><?php echo $r['igst'];?></strong></td>
      <?php } ?>
      <td align="right" style="border-right:1px solid black;"><strong><?php echo $r['sub_total'];?></strong></td>
      </tr>
  <?php     
       ?>
      
<?php 
        $tot_amt += $r['amount'];
        $all_tot += $r['sub_total'];
      } ?>
              <tr>
      <td height="22" style="border-right: 1px solid black;">&nbsp;</td>
      <td style="border-right: 1px solid black;">&nbsp;</td>
      <td style="border-right: 1px solid black;">&nbsp;</td>     
      <td align="center" style="border-right:1px solid black;"></td>
      <td align="right" style="border-right:1px solid black;">&nbsp;</td>
      <td align="center" style="border-right:1px solid black;"></td>
      <td align="right" style="border-right:1px solid black;">&nbsp;</td>
      <td align="center" style="border-right:1px solid black;"></td>
      <td align="right" style="border-right:1px solid black;">&nbsp;</td>
       </tr>

       <tr>
      <td height="21" style="border-right: 1px solid black;">&nbsp;</td>
      <td style="border-right: 1px solid black;">&nbsp;</td>
      <td style="border-right: 1px solid black;">&nbsp;</td>     
      <td align="center" style="border-right:1px solid black;"></td>
      <td align="right" style="border-right:1px solid black;">&nbsp;</td>
      <td align="center" style="border-right:1px solid black;"></td>
      <td align="right" style="border-right:1px solid black;">&nbsp;</td>
      <td align="center" style="border-right:1px solid black;"></td>
      <td align="right" style="border-right:1px solid black;">&nbsp;</td>      
  </tr>

       <tr>
      <td  style="border-right: 1px solid black;">&nbsp;</td>
      <td style="border-right: 1px solid black;">&nbsp;</td>
      <td style="border-right: 1px solid black;">&nbsp;</td>     
      <td align="center" style="border-right:1px solid black;"></td>
      <td align="right" style="border-right:1px solid black;">&nbsp;</td>
        <td align="center" style="border-right:1px solid black;"></td>
      <td align="right" style="border-right:1px solid black;">&nbsp;</td>
      <td align="center" style="border-right:1px solid black;"></td>
      <td align="right" style="border-right:1px solid black;">&nbsp;</td>     
    </tr>
            
         

      </table>
      <table width="750" border="0" align="center" style="border-bottom:1px solid black;border-right:1px solid black;border-left:1px solid black;border-collapse:collapse;">
        <tr>
  
          <td width="200" colspan="6" align="right" style="border-bottom: 1px dotted black;">Sub Total&nbsp;:&nbsp;</td>
          <td  align="right" style="border-bottom: 1px dotted black;">&nbsp;<?php echo number_format($tot_amt,2); ?></td>
          <td  align="right" style="border-bottom: 1px dotted black;">&nbsp;</td>
          <td  align="right" style="border-bottom: 1px dotted black;"><strong><?php echo number_format($all_tot,2); ?></strong></td>
        </tr>

      </table>
      
      <table width="750" height="108" align="center" style="border-collapse:collapse;border-bottom:1px solid black;border-right:1px solid black;border-left:1px solid black;">
        <tr>
          <td width="150" height="20"><b style="font-size:11px;">&nbsp;&nbsp;Amount In Words</small> :</td>
          <td width="600" colspan="2" style="font-size:12px;"><strong><?php echo $pi_datas['amount_in_words'];?></strong></td>
          </tr>
        
        <tr>
          <td width="150" height="20"><b style="font-size:11px;">&nbsp;&nbsp;Payment Terms:</small> :</td>
          <td width="600" align="left" colspan="2" style="font-size:12px;"><b><?php echo $pi_datas['payment_terms'];?></b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
        </tr>

        <tr>
          <td width="150" height="20"></td>
          <td width="600" align="right" colspan="2" ><b>For Company Name</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
        </tr>


        <tr>
          <td width="150" height="20" align="center"><?php echo $pi_datas['pi_req_name'];?></td>
          <td width="150" height="20" align="center"><?php echo $pi_datas['pi_appr_name'];?></td>
          <td width="150" height="20" align="center"></td>
        </tr>

        <tr>
          <td width="150" height="20" align="center">P.I. Prepared by</td>
          <td width="150" height="20" align="center">P.I. Approved by</td>
          <td width="150" height="20" align="right">Authorized Signatory.</td>
        </tr>
        
        <tr>
            <td colspan="3" height="60"align="center">Terms & Conditions as agreed upon.</td>
        </tr>


      
    
</table>


 <!-- <script type="text/javascript" src="<?php //echo base_url();?>assets/js/jquery.min.js"></script> -->
 <script type="text/javascript">
    // window.print();
 </script>