
<link rel="stylesheet" href="<?php echo base_url();?>assets/css/style.css?rn=<?php echo CNFJSCSSRANDNO?>">
<?php
//print_r($pi_data); exit;
$company_datas = $company_data[0];  
$vendor_datas = $vendor_data[0];  
$pi_datas = $pi_data[0];
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

if($miDetails['bom_dept']=='SAMPLE DEPT.') {
    $sam_data = $this->db->where('usertype',5)->get(KN_USERS)->row();
    $contactname = $sam_data->contactname;
    $address = $sam_data->address;
    $mobile = $sam_data->mobile;
    $email = $sam_data->username;
    $gst = '';
    $iecode = '';
                                                
    } else {
        $contactname = '';
        $address = '';
        $mobile = '';
        $email = '';
        $gst = '';
        $iecode = '';
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
              <td align="center" style="padding: 10px; border-right:1px solid black;" ><b>BOM STORE</td> 
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
          <td  align="right" width="80" style="border-top:1px solid black;border-right:1px solid black;border-left:1px solid black;font-size:12px;">Dept Name:</td>
          <td  align="left" width="280" style="border-top:1px solid black;border-right:1px solid black;font-size:12px;padding:3px;">BOM STORE</td>
          <td  align="right" width="120" style="border-top:1px solid black;border-right:1px solid black;font-size:12px;padding:3px;">D.C. No: </td>
          <td  align="left" width="240" style="border-top:1px solid black;border-right:1px solid black;font-size:12px;padding:3px;"><?php echo $DCNO; ?></td>
        </tr>

        <tr>
          <td  align="right" width="80" style="border-top:1px solid black;border-right:1px solid black;border-left:1px solid black;font-size:12px;">Cont. Person:</td>
          <td  align="left"  width="280" style="border-top:1px solid black;border-right:1px solid black;font-size:12px;padding:3px;"><?php echo @$ArrProfileInfo['name']; ?></td>
          <td  align="right" width="120" style="border-top:1px solid black;border-right:1px solid black;font-size:12px;padding:3px;">Date & Time: </td>
          <td  align="left" width="240" style="border-top:1px solid black;border-right:1px solid black;font-size:12px;padding:3px;"><?php echo $miDetails['dc_dt']; ?></td>
        </tr>

        <tr>
          <td  align="right" width="80" style="border-top:1px solid black;border-right:1px solid black;border-left:1px solid black;font-size:12px;">Cont. No:</td>
          <td  align="left" width="280" style="border-top:1px solid black;border-right:1px solid black;font-size:12px;padding:3px;"><?php echo @$ArrProfileInfo['mobile']; ?></td>
          <td  align="right" width="120" style="border-top:1px solid black;border-right:1px solid black;font-size:12px;padding:3px;">Cutoff Date & Time: </td>
          <td  align="left" width="240" style="border-top:1px solid black;border-right:1px solid black;font-size:12px;padding:3px;"><?php echo $miDetails['cad_cutoff_date'] ?></td>
        </tr>

        <tr>
          <td  align="right" width="80" style="border-top:1px solid black;border-left:1px solid black;font-size:14px; text-align:left;"><b>To</b></td>
          <td  align="left" width="280" style="border-top:1px solid black;border-right:1px solid black;font-size:12px;padding:3px;"></td>
          <td  align="right" width="140" style="border-top:1px solid black;border-right:1px solid black;font-size:12px;padding:3px;">WIP No: </td>
          <td  align="left" width="240" style="border-top:1px solid black;border-right:1px solid black;font-size:12px;padding:3px;"><?php echo $ArrCommonHeaderData['ArrEnquiryDetails']['isriorcode']; ?></td>
        </tr>

        <tr>
          <td  align="right" width="80" style="border-top:1px solid black;border-right:1px solid black;border-left:1px solid black;font-size:12px;">Dept Name:</td>
          <td  align="left" width="280" style="border-top:1px solid black;border-right:1px solid black;font-size:12px;padding:3px;"><?php echo $miDetails['bom_dept']; ?></td>
          <td  align="right" width="120" style="border-top:1px solid black;border-right:1px solid black;font-size:12px;padding:3px;">Queue No: </td>
          <td  align="left" width="240" style="border-top:1px solid black;border-right:1px solid black;font-size:12px;padding:3px;"><?php echo @$miDetails['ref_queue_no'];?></td>
        </tr>

        <tr>
          <td  align="right" width="80" style="border-top:1px solid black;border-right:1px solid black;border-left:1px solid black;font-size:12px;">Cont. Person:</td>
          <td  align="left" width="280" style="border-top:1px solid black;border-right:1px solid black;font-size:12px;padding:3px;"><?php echo @$contactname; ?></td>
          <td  align="right" width="120" style="border-top:1px solid black;border-right:1px solid black;font-size:12px;padding:3px;">M.I. Ref. No: </td>
          <td  align="left" width="240" style="border-top:1px solid black;border-right:1px solid black;font-size:12px;padding:3px;"><?php echo $miDetails['bom_ref_no']; ?></td>
        </tr>
        
        <tr>
          <td  align="right" width="80" style="border-top:1px solid black;border-right:1px solid black;border-left:1px solid black;font-size:12px;">Cont. No:</td>
          <td  align="left" width="280" style="border-top:1px solid black;border-right:1px solid black;font-size:12px;padding:3px;"><?php echo @$mobile; ?></td>
          <td  align="right" width="120" style="border-top:1px solid black;border-right:1px solid black;font-size:12px;padding:3px;">Item Received Status: </td>
          <td  align="left" width="240" style="border-top:1px solid black;border-right:1px solid black;font-size:12px;padding:3px;"><?php echo $item_received_status; ?> &nbsp;<p><?php echo @$itemDetails['item_sta_upt_dt']; ?></p></td>
        </tr>
        <?php } else { ?>
        <tr>
          <td colspan="2"  align="left"><b style="font-size:16px;">To</b></td>
          <td colspan="2" align="center"  style="font-size:16px;padding:3px;"><b>D.C. REFERENCE</b></td>
        </tr>
        
        <tr>
          <td  align="right" width="80" style="border-top:1px solid black;border-right:1px solid black;border-left:1px solid black;font-size:14px;">Company Name:</td>
          <td  align="left" width="280" style="border-top:1px solid black;border-right:1px solid black;font-size:14px;padding:3px;"><?php echo $miDetails['bom_dept']; ?></td>
          <td  align="right" width="120" style="border-top:1px solid black;border-right:1px solid black;font-size:14px;padding:3px;">D.C. No: </td>
          <td  align="left" width="240" style="border-top:1px solid black;border-right:1px solid black;font-size:14px;padding:3px;"><?php echo $miDetails['dc_ref_queue_no']; ?></td>
        </tr>

        <tr>
          <td  align="right" width="80" style="border-top:1px solid black;border-right:1px solid black;border-left:1px solid black;font-size:12px;">Address:</td>
          <td  align="left" rowspan="2" width="280" style="border-top:1px solid black;border-right:1px solid black;font-size:12px;padding:3px;"><b><?php echo $address;?></b></td>
          <td  align="right" width="120" style="border-top:1px solid black;border-right:1px solid black;font-size:12px;padding:3px;">Date & Time: </td>
          <td  align="left" width="240" style="border-top:1px solid black;border-right:1px solid black;font-size:12px;padding:3px;"><b><?php echo $miDetails['dc_dt']; ?></b></td>
        </tr>

        <tr>
          <td  align="right" width="80" style="border-top:1px solid black;border-right:1px solid black;border-left:1px solid black;font-size:12px;"></td>
          <!--<td  align="left" width="280" style="border-top:1px solid black;border-right:1px solid black;font-size:14px;padding:3px;"><b><?php echo $address;?></b></td>-->
          <td  align="right" width="120" style="border-top:1px solid black;border-right:1px solid black;font-size:12px;padding:3px;">Cutoff Date & Time: </td>
          <td  align="left" width="240" style="border-top:1px solid black;border-right:1px solid black;font-size:12px;padding:3px;"><?php echo $miDetails['cad_cutoff_date'] ?></td>
        </tr>

        <tr>
          <td  align="right" width="80" style="border-top:1px solid black;border-right:1px solid black;border-left:1px solid black;font-size:14px;">Contact No:</td>
          <td  align="left" width="280" style="border-top:1px solid black;border-right:1px solid black;font-size:14px;padding:3px;"><?php echo $mobile;?></td>
          <td  align="right" width="140" style="border-top:1px solid black;border-right:1px solid black;font-size:14px;padding:3px;">WIP No: </td>
          <td  align="left" width="240" style="border-top:1px solid black;border-right:1px solid black;font-size:14px;padding:3px;"><?php echo $ArrCommonHeaderData['ArrEnquiryDetails']['isriorcode']; ?></td>
        </tr>

        <tr>
          <td  align="right" width="80" style="border-top:1px solid black;border-right:1px solid black;border-left:1px solid black;font-size:12px;">e-mail ID:</td>
          <td  align="left" width="280" style="border-top:1px solid black;border-right:1px solid black;font-size:12px;padding:3px;"><?php echo $email;?></td>
          <td  align="right" width="120" style="border-top:1px solid black;border-right:1px solid black;font-size:12px;padding:3px;">Queue No: </td>
          <td  align="left" width="240" style="border-top:1px solid black;border-right:1px solid black;font-size:12px;padding:3px;"><?php echo @$miDetails['ref_queue_no'];?></td>
        </tr>

        <tr>
          <td  align="right" width="80" style="border-top:1px solid black;border-right:1px solid black;border-left:1px solid black;font-size:14px;">GST No:</td>
          <td  align="left" width="280" style="border-top:1px solid black;border-right:1px solid black;font-size:14px;padding:3px;"><?php echo $gstno;?></td>
          <td  align="right" width="120" style="border-top:1px solid black;border-right:1px solid black;font-size:14px;padding:3px;">M.I. Ref. No: </td>
          <td  align="left" width="240" style="border-top:1px solid black;border-right:1px solid black;font-size:14px;padding:3px;"><?php echo $miDetails['bom_ref_no']; ?></td>
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

          <td width="42" height="30" align="center" style="border-right:1px solid black;border-bottom:1px solid black;font-size:12px;" scope="row">S.No.</td>
          <td width="100" height="30" align="center" style="border-right:1px solid black;border-bottom:1px solid black;font-size:12px;" scope="row">Sample<br>Ref. No.</td>
          
          <td width="200" style="border-right:1px solid black;border-bottom:1px solid black;font-size:12px;" align="center">
  Item Description  / <br>Garment Size
</td>
          <td width="150" style="border-right:1px solid black;border-bottom:1px solid black;font-size:12px;" align="center">Item Code / Item Colour Code </td>
          <td width="150" style="border-right:1px solid black;border-bottom:1px solid black;font-size:12px;" align="center">Size (L*W*H) </td>
          <td width="80" style="border-right:1px solid black;border-bottom:1px solid black;font-size:12px;" align="center"> UOM </td>
          <td width="80" style="border-right:1px solid black;border-bottom:1px solid black;font-size:12px;" align="center">Qty.</td>
          <td width="80" style="border-right:1px solid black;border-bottom:1px solid black;font-size:12px;" align="center">UOM</td>
        </tr>


          
     <?php 
     $i = 1;
     //print_r($data);
     foreach($data as $r) { ?>
     
      <tr>
      <td height="29" align="center" style="border-right: 1px solid black;font-size:12px;"><?php echo $i; ?></td>
      <td height="29" align="left" style="border-right: 1px solid black;font-size:12px;"><?php echo $r['mi_serial_no']; ?></td>
      
      <td align="left" style="border-right: 1px solid black;font-size:12px;"><?php echo $r['item_desc'];?> /  <?php echo $r['gar_size'];?></td>
      <td align="left" style="border-right: 1px solid black;font-size:12px;"><?php echo $r['item_code'];?> / <?php echo $r['item_color_code'];?> </td>     
      <td align="center" style="border-right: 1px solid black;font-size:12px;"> <?php echo $r['size_dim'];?> </td>
      <td align="center" style="border-right: 1px solid black;font-size:12px;">  <?php echo $r['ind_uom'];?></td>
      <td align="right" style="border-right:1px solid black;font-size:12px;"><?php echo $r['issued_qty'];?></td>
      <td align="center" style="border-right:1px solid black;font-size:12px;"><?php echo $r['ind_uom'];?></td>

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
       </tr>

       <tr>
      <td height="21" style="border-right: 1px solid black;">&nbsp;</td>
      <td style="border-right: 1px solid black;">&nbsp;</td>
      <td style="border-right: 1px solid black;">&nbsp;</td>     
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
    </tr>
            
         

      </table>
      
      
      <table width="750" height="108" align="center" style="border-collapse:collapse;border-bottom:1px solid black;border-right:1px solid black;border-left:1px solid black;">

        <tr>
          <td width="150" height="20"></td>
          <!-- <td width="600" align="right" colspan="3" style="font-size:14px;" ><b>For <?php echo $company_datas['companyname']; ?></b>&nbsp;&nbsp;</td> -->
       <td width="600" align="right" colspan="3" style="font-size:14px;" ><b>For Azibo Infotech Private Limited</b>&nbsp;&nbsp;</td>
       
        </tr>


        <tr>
          <td width="100" height="30" align="center" style="font-size:12px;"><?php echo @$ArrCommonHeaderData['merchantName'] ?></td>
          <td width="100" height="30" align="center" style="font-size:12px;"><?php echo @$ArrCommonHeaderData['ArrMgmt']['contactname'] ?></td>
          <td width="100" height="30" align="center" style="font-size:12px;"><?php echo @$miDetails['received_name']; ?></td>
          <td width="170" height="30" align="center"></td>
        </tr>

        <tr>
          <td width="100" height="10" align="center" style="font-size:12px;margin-top: 0px;">M.I. Raised by</td>
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