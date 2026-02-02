
<link rel="stylesheet" href="<?php echo base_url();?>assets/css/style.css?rn=<?php echo CNFJSCSSRANDNO?>">
<?php
error_reporting(0);
//print_r($pi_data); exit;
$company_datas = $company_data[0];  
$miDetails = $miDetails[0];
$itemDetails = $itemDetails[0];
$ArrProfileInfo = fnGetUserLoggedInfo(1);

if($itemDetails['item_received_status']=="0") {
  $item_received_status = "PENDING"; 
} else if($itemDetails['item_received_status']=="1") {
    $item_received_status = "RECEIVED";
} else if($itemDetails['item_received_status']=="2") {
    $item_received_status = "DISCREPANCY";
} 

//$item_received_status = '';

if($miDetails['type']=='INTERNAL') {
    $sam_data = $this->db->where('usertype',5)->get(KN_USERS)->row();
    $vendor_name = $miDetails['cad_dept'];
    $contactname = $sam_data->contactname;
    $address = $sam_data->address;
    $mobile = $sam_data->mobile;
    $email = $sam_data->username;
    $gst = '';
    $iecode = '';
                                                
    } else {
        $cad_data = $this->db->where('id',$miDetails['cad_dept'])->get('kn_master_bom_vendor')->row();
        $vendor_name = $cad_data->vendorname;
        $address = $cad_data->address;
        $mobile = $cad_data->mobile;
        $email = $cad_data->email_id;
        $gst = $cad_data->gstno;
        $iecode = $cad_data->iecode;
    }
        
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
              <td align="center" style="padding: 10px; border-right:1px solid black;" ><b>CAD DEPT</td> 
              <td align="center" style="padding: 10px;border-right:1px solid black;"><b>DELIVERY CHALLAN</td>   
              <td align="center" style="padding: 10px;"><b><?php echo $miDetails['type']; ?></td> 
          </tr>
      </table>
     
      <table width="750" align="center" style="border-collapse:collapse;border-right:1px solid black;border-left:1px solid black;">
          
        <?php if($miDetails['type'] == 'INTERNAL') { ?>
        <tr>
          <td colspan="2"  align="left"><b style="font-size:16px;">From</b></td>
          <td colspan="2" align="center"  style="font-size:16px;padding:3px;"><b>D.C. REFERENCE</b></td>
        </tr>
        
        <tr>
          <td  align="right" width="80" style="border-top:1px solid black;border-right:1px solid black;border-left:1px solid black;font-size:14px;">Dept Name:</td>
          <td  align="left" width="280" style="border-top:1px solid black;border-right:1px solid black;font-size:14px;padding:3px;"><b>CAD DEPT</b></td>
          <td  align="right" width="120" style="border-top:1px solid black;border-right:1px solid black;font-size:14px;padding:3px;">D.C. No: </td>
          <td  align="left" width="240" style="border-top:1px solid black;border-right:1px solid black;font-size:14px;padding:3px;"><b><?php echo $miDetails['dc_ref_queue_no']; ?></b></td>
        </tr>

        <tr>
          <td  align="right" width="80" style="border-top:1px solid black;border-right:1px solid black;border-left:1px solid black;font-size:14px;">Cont. Person:</td>
          <td  align="left"  width="280" style="border-top:1px solid black;border-right:1px solid black;font-size:14px;padding:3px;"><b><?php echo @$ArrProfileInfo['name']; ?></b></td>
          <td  align="right" width="120" style="border-top:1px solid black;border-right:1px solid black;font-size:14px;padding:3px;">Date & Time: </td>
          <td  align="left" width="240" style="border-top:1px solid black;border-right:1px solid black;font-size:14px;padding:3px;"><b><?php echo $miDetails['dc_dt']; ?></b></td>
        </tr>

        <tr>
          <td  align="right" width="80" style="border-top:1px solid black;border-right:1px solid black;border-left:1px solid black;font-size:14px;">Cont. No:</td>
          <td  align="left" width="280" style="border-top:1px solid black;border-right:1px solid black;font-size:14px;padding:3px;"><b><?php echo @$ArrProfileInfo['mobile']; ?></b></td>
          <td  align="right" width="120" style="border-top:1px solid black;border-right:1px solid black;font-size:14px;padding:3px;">Cutoff Date & Time: </td>
          <td  align="left" width="240" style="border-top:1px solid black;border-right:1px solid black;font-size:14px;padding:3px;"><b><?php echo $miDetails['cad_cutoff_date'] ?></b></td>
        </tr>

        <tr>
          <td  align="right" width="80" style="border-top:1px solid black;border-left:1px solid black;font-size:14px; text-align:left;"><b>To</b></td>
          <td  align="left" width="280" style="border-top:1px solid black;border-right:1px solid black;font-size:12px;padding:3px;"><b></b></td>
          <td  align="right" width="140" style="border-top:1px solid black;border-right:1px solid black;font-size:12px;padding:3px;">WIP No: </td>
          <td  align="left" width="240" style="border-top:1px solid black;border-right:1px solid black;font-size:12px;padding:3px;"><b><?php echo $ArrCommonHeaderData['ArrEnquiryDetails']['isriorcode']; ?></b></td>
        </tr>

        <tr>
          <td  align="right" width="80" style="border-top:1px solid black;border-right:1px solid black;border-left:1px solid black;font-size:12px;">Dept Name:</td>
          <td  align="left" width="280" style="border-top:1px solid black;border-right:1px solid black;font-size:12px;padding:3px;"><b><?php echo $vendor_name; ?></b></td>
          <td  align="right" width="120" style="border-top:1px solid black;border-right:1px solid black;font-size:12px;padding:3px;">Queue No: </td>
          <td  align="left" width="240" style="border-top:1px solid black;border-right:1px solid black;font-size:12px;padding:3px;"><b><?php echo @$miDetails['ref_queue_no'];?></b></td>
        </tr>

        <tr>
          <td  align="right" width="80" style="border-top:1px solid black;border-right:1px solid black;border-left:1px solid black;font-size:12px;">Cont. Person:</td>
          <td  align="left" width="280" style="border-top:1px solid black;border-right:1px solid black;font-size:12px;padding:3px;"><b><?php echo @$contactname; ?></b></td>
          <td  align="right" width="120" style="border-top:1px solid black;border-right:1px solid black;font-size:12px;padding:3px;">M.I. Ref. No: </td>
          <td  align="left" width="240" style="border-top:1px solid black;border-right:1px solid black;font-size:12px;padding:3px;"><b><?php echo $miDetails['bom_ref_no']; ?></b></td>
        </tr>
        
        <tr>
          <td  align="right" width="80" style="border-top:1px solid black;border-right:1px solid black;border-left:1px solid black;font-size:12px;">Cont. No:</td>
          <td  align="left" width="280" style="border-top:1px solid black;border-right:1px solid black;font-size:12px;padding:3px;"><b><?php echo @$mobile; ?></b></td>
          <td  align="right" width="120" style="border-top:1px solid black;border-right:1px solid black;font-size:12px;padding:3px;">Item Received Status: </td>
          <td  align="left" width="240" style="border-top:1px solid black;border-right:1px solid black;font-size:12px;padding:3px;"><b><?php echo $item_received_status; ?> &nbsp;<p><?php echo @$itemDetails['item_sta_upt_dt']; ?></p></b></td>
        </tr>
        <?php } else { ?>
        <tr>
          <td colspan="2"  align="left"><b style="font-size:16px;">To</b></td>
          <td colspan="2" align="center"  style="font-size:16px;padding:3px;"><b>D.C. REFERENCE</b></td>
        </tr>
        
        <tr>
          <td  align="right" width="80" style="border-top:1px solid black;border-right:1px solid black;border-left:1px solid black;font-size:12px;">Company Name:</td>
          <td  align="left" width="280" style="border-top:1px solid black;border-right:1px solid black;font-size:12px;padding:3px;"><?php echo $vendor_name; ?></td>
          <td  align="right" width="120" style="border-top:1px solid black;border-right:1px solid black;font-size:12px;padding:3px;">D.C. No: </td>
          <td  align="left" width="240" style="border-top:1px solid black;border-right:1px solid black;font-size:12px;padding:3px;"><?php echo $miDetails['dc_ref_queue_no']; ?></td>
        </tr>

        <tr>
          <td  align="right" width="80" style="border-top:1px solid black;border-right:1px solid black;border-left:1px solid black;font-size:12px;">Address:</td>
          <td  align="left" rowspan="2" width="280" style="border-top:1px solid black;border-right:1px solid black;font-size:12px;padding:3px;"><?php echo $address;?></td>
          <td  align="right" width="120" style="border-top:1px solid black;border-right:1px solid black;font-size:12px;padding:3px;">Date & Time: </td>
          <td  align="left" width="240" style="border-top:1px solid black;border-right:1px solid black;font-size:12px;padding:3px;"><?php echo $miDetails['dc_dt']; ?></td>
        </tr>

        <tr>
          <td  align="right" width="80" style="border-top:1px solid black;border-right:1px solid black;border-left:1px solid black;font-size:12px;"></td>
          <!--<td  align="left" width="280" style="border-top:1px solid black;border-right:1px solid black;font-size:12px;padding:3px;"><b><?php echo $address;?></b></td>-->
          <td  align="right" width="120" style="border-top:1px solid black;border-right:1px solid black;font-size:12px;padding:3px;">Cutoff Date & Time: </td>
          <td  align="left" width="240" style="border-top:1px solid black;border-right:1px solid black;font-size:12px;padding:3px;"><?php echo $miDetails['cad_cutoff_date'] ?></td>
        </tr>

        <tr>
          <td  align="right" width="80" style="border-top:1px solid black;border-right:1px solid black;border-left:1px solid black;font-size:12px;">Contact No:</td>
          <td  align="left" width="280" style="border-top:1px solid black;border-right:1px solid black;font-size:12px;padding:3px;"><?php echo $mobile;?></td>
          <td  align="right" width="140" style="border-top:1px solid black;border-right:1px solid black;font-size:12px;padding:3px;">WIP No: </td>
          <td  align="left" width="240" style="border-top:1px solid black;border-right:1px solid black;font-size:12px;padding:3px;"><?php echo $ArrCommonHeaderData['ArrEnquiryDetails']['isriorcode']; ?></td>
        </tr>

        <tr>
          <td  align="right" width="80" style="border-top:1px solid black;border-right:1px solid black;border-left:1px solid black;font-size:12px;">e-mail ID:</td>
          <td  align="left" width="280" style="border-top:1px solid black;border-right:1px solid black;font-size:12px;padding:3px;"><?php echo $email;?></td>
          <td  align="right" width="120" style="border-top:1px solid black;border-right:1px solid black;font-size:12px;padding:3px;">Queue No: </td>
          <td  align="left" width="240" style="border-top:1px solid black;border-right:1px solid black;font-size:12px;padding:3px;"><?php echo @$miDetails['ref_queue_no'];?></td>
        </tr>

        <tr>
          <td  align="right" width="80" style="border-top:1px solid black;border-right:1px solid black;border-left:1px solid black;font-size:12px;">GST No:</td>
          <td  align="left" width="280" style="border-top:1px solid black;border-right:1px solid black;font-size:12px;padding:3px;"><?php echo $gstno;?></td>
          <td  align="right" width="120" style="border-top:1px solid black;border-right:1px solid black;font-size:12px;padding:3px;">M.I. Ref. No: </td>
          <td  align="left" width="240" style="border-top:1px solid black;border-right:1px solid black;font-size:12px;padding:3px;"><?php echo $miDetails['bom_ref_no']; ?></td>
        </tr>
        
        <tr>
          <td  align="right" width="80" style="border-top:1px solid black;border-right:1px solid black;border-left:1px solid black;font-size:12px;">IE Code:</td>
          <td  align="left" width="280" style="border-top:1px solid black;border-right:1px solid black;font-size:12px;padding:3px;"><?php echo $iecode;?></td>
          <td  align="right" width="120" style="border-top:1px solid black;border-right:1px solid black;font-size:12px;padding:3px;">Item Received Status: </td>
          <td  align="left" width="240" style="border-top:1px solid black;border-right:1px solid black;font-size:12px;padding:3px;"><?php echo $item_received_status; ?> &nbsp;<p><?php echo @$itemDetails['item_sta_upt_dt']; ?></p></td>
        </tr>
        <?php } ?>
        
        <tr>
            <td colspan="4" style="border-bottom:1px solid black;border-right:1px solid black;border-left:1px solid black;"></td>
        </tr>
        <tr>
            <td colspan="4" style="padding-top:20px;">Material Issued Details:</td>
        </tr>

      </table>
      <table width="750"   height="640" align="center" style="border-bottom:1px solid black;border-top:1px solid black;border-right:1px solid black;border-left:1px solid black;border-collapse:collapse;">
        <tr>

          <td width="42" height="37" align="center" style="border-right:1px solid black;border-bottom:1px solid black;font-size:12px;" scope="row">S.No</td>
          <td width="200" style="border-right:1px solid black;border-bottom:1px solid black;font-size:12px;" align="center">P.O. No. </td>
          <td width="150" style="border-right:1px solid black;border-bottom:1px solid black;font-size:12px;" align="center">Combo / Colour </td>
          <td width="110" style="border-right:1px solid black;border-bottom:1px solid black;font-size:12px;" align="center">Component </td>
          <td width="100" style="border-right:1px solid black;border-bottom:1px solid black;font-size:12px;" align="center"> Size Spec Code </td>
          <td width="150" style="border-right:1px solid black;border-bottom:1px solid black;font-size:12px;" align="center">CAD Ref. No.</td>
          <td width="100" style="border-right:1px solid black;border-bottom:1px solid black;font-size:12px;" align="center">Item <br> Issued</td>
          <td width="80" style="border-right:1px solid black;border-bottom:1px solid black;font-size:12px;" align="center">Issued <br> Size(s)</td>
          <td width="120" style="border-right:1px solid black;border-bottom:1px solid black;font-size:12px;" align="center">Total No. Of &nbsp;<br> Parts Issued &nbsp;</td>
        </tr>


          
     <?php 
     $i = 1;
     foreach($data as $r) { 
     $item = [ '', 'Bit Marker', 'Pattern', 'Pattern (Size Set)', 'Lay Marker', 'Others' ];
     $item_issued = $r['item_issued'];
     ?>
     
      <tr>
      <td height="29" align="left" style="border-right: 1px solid black;font-size:12px;">&nbsp;<?php echo $i; ?></td>
      <td align="left" style="border-right: 1px solid black;font-size:12px;" >&nbsp;<?php echo $r['po_no'];?> </td>
      <td align="left" style="border-right: 1px solid black;font-size:12px;">&nbsp;<?php echo $r['combo'];?>  </td>     
      <td align="left" style="border-right: 1px solid black;font-size:12px;"> &nbsp;<?php echo $r['component'];?> </td>
      <td align="left" style="border-right: 1px solid black;font-size:12px;">  &nbsp;<?php echo $r['spec_code'];?></td>
      <td align="left" style="border-right: 1px solid black;font-size:12px;">&nbsp;<?php echo $r['ref_queue_no'];?></td>
      <td align="left" style="border-right: 1px solid black;font-size:12px;">&nbsp;<?php echo $item[$item_issued];?></td>
      <td align="left" style="border-right: 1px solid black;font-size:12px;">&nbsp;<?php echo $r['size_name'];?></td>
      <td align="right" style="border-right: 1px solid black;font-size:12px;"><?php echo $r['parts_issued'];?>&nbsp;</td>

      </tr>
  
      
<?php $i++; } ?>
              <tr>
      <td height="22" style="border-right: 1px solid black;">&nbsp;</td>
      <td style="border-right: 1px solid black;">&nbsp;</td>
      <td style="border-right: 1px solid black;">&nbsp;</td>     
      <td align="center" style="border-right:1px solid black;"></td>
      <td align="right" style="border-right:1px solid black;">&nbsp;</td>
      <td align="center" style="border-right:1px solid black;"></td>
      <td align="right" style="border-right:1px solid black;">&nbsp;</td>
      <td align="right" style="border-right:1px solid black;">&nbsp;</td>
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
      <td align="right" style="border-right:1px solid black;">&nbsp;</td>
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
      <td align="right" style="border-right:1px solid black;">&nbsp;</td>
      <td align="right" style="border-right:1px solid black;">&nbsp;</td>
      
    </tr>
            
         

      </table>
      
      
      <table width="750" height="108" align="center" style="border-collapse:collapse;border-bottom:1px solid black;border-right:1px solid black;border-left:1px solid black;">

        <tr>
          <td width="150" height="20"></td>
          <td width="600" align="right" colspan="3" style="font-size:14px;" ><b>For <?php echo $company_datas['companyname']; ?></b>&nbsp;&nbsp;</td>
        </tr>


        <tr>
          <td width="100" height="30" align="center" style="font-size:12px;"><?php echo @$ArrCommonHeaderData['merchantName'] ?></td>
          <td width="100" height="30" align="center" style="font-size:12px;"><?php echo @$ArrCommonHeaderData['ArrMgmt']['contactname'] ?></td>
          <td width="100" height="30" align="center" style="font-size:12px;"><?php echo @$miDetails['material_received_by']; ?></td>
          <td width="170" height="30" align="center"></td>
        </tr>

        <tr>
          <td width="100" height="10" align="center" style="font-size:12px;">M.I. Raised by</td>
          <td width="100" height="10" align="center" style="font-size:12px;">M.I. Authorized by</td>
          <td width="100" height="10" align="center" style="font-size:12px;">Material Received by</td>
          <td width="170" height="10" align="right" style="font-size:12px;">Authorized Signatory.</td>
        </tr>
        
        <!--<tr>-->
        <!--    <td colspan="4" height="60"align="center">Terms & Conditions as agreed upon.</td>-->
        <!--</tr>-->


      
    
</table>



 <!-- <script type="text/javascript" src="<?php //echo base_url();?>assets/js/jquery.min.js"></script> -->
 <script type="text/javascript">
     window.print();
 </script>