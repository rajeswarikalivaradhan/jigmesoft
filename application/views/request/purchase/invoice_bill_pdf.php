<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<style>

    

body {
    font-family: "Helvetica Neue",Helvetica,Arial,sans-serif;
    font-size:12px;
    font-weight:normal;
}
.b-0{
   border-top:none!important;
}
.table-responsive {
overflow-x: unset !important;
}

.ord-procs-cell {
    width: 25%;
    padding-top:2px!important;
    padding-left:4px!important;
    padding-right:4px!important;
    padding-bottom:4px!important;
}

.tbl-procs-border {
    border: 1px solid #ddd!important;
}
.table > tbody > tr > td {
    /*border-top:0px!important;*/
    
}
td.process-value,
td.process-title,
.process-titles,
.process-main-heads,
.process-main-value,
td.process-main-head {
    font-size: 11px;
   padding:0px 1px 1px 1px!important;
}
td.process-title {
    background: #f3f3f3;
    width: 30% !important;
    text-align: right;
    padding:0px 0px 0px 0px!important;
}
td.process-titles {
    background: #f3f3f3;
    width: 30% !important;
    text-align: right;
    color:#f3f3f3;
    padding:3px 0px!important;
}

td.process-main-head {
    background: #022b61;
    color: #ffffff;
    text-align: center;
}

td.process-main-heads {
    background: #e8e8e8;
    color: #050505;
    text-align: left;
}
/*.tables{*/
/*    margin-bottom: 5px!important;*/
/*}*/
.card-body{
    margin:6px!important;
}
table.table.tbl-procs-border {
    margin-bottom: 0;
}
.table {
    /*background: #F7F7F7!important;*/
}

td .process-valuemid{
 text-align:left;
 width:3%;
}
td .process-valuemids{
 text-align:left;
 width:3%;
 color:#f3f3f3;
}
.text-right{
    text-align:right;
}
.text-center{
    text-align:center;
}




.details >tr > td {
    /* Remove this to prevent full borders on all td */
    /* border:1px solid black; */
}

table.details {
    width: 100%;
    border-collapse: collapse;
    border: 1px solid black; /* Outer border */
}

table.details thead tr th {
    border: 1px solid black; /* Full border on header cells */
    text-align: center;
}

table.details tbody tr td {
    border-left: 1px solid black;
    border-right: 1px solid black;
    vertical-align: middle;
}

table.details tbody tr td:first-child {
    border-left: none;
}

table.details tbody tr td:last-child {
    border-right: none;
}
</style>
<body>

<?php
$company_datas = $company_data[0]; 
$subcompany_datas = $subcompany_data[0];  
$vendor_datas = $vendor_data[0];  
$pi_datas = $pi_data[0];
$bomstorelogin_data = $bomstorelogin_data[0]; 

$ArrProfileInfo = fnGetUserLoggedInfo(1);
?>
      <table  width="100%"> 
 <tr>
        <td align="" style="border:1px solid black;padding:5px;">
           <b style="font-size:18px;" ><?php echo $subcompany_datas['companyname'];?> </b>
           <span style="float:right;"> <?php echo $subcompany_datas['address'];?>, <?php echo $subcompany_datas['city'];?> - <?php echo $subcompany_datas['pincode'];?>.
           <?php echo $subcompany_datas['state'];?>, <?php echo $subcompany_datas['country'];?>
        </span></br>  
           <span style="float:right;">E-mail: <?php echo $subcompany_datas['email_id']?>, Mobile: <?php echo $subcompany_datas['mobile_no'];?></span></br></br>
           <span style="float:right;"> GST NO: <?php echo $subcompany_datas['gst_no']?> /  IE Code: <?php echo $subcompany_datas['gst_no']?> </span></br></td>
         </tr>
        
</table>

<table cellspacing="0" cellpadding="3" border="0" width="100%" style="margin-top:10px">
   
        <tr>
         <td align="center" style="border-bottom:1px solid #151515;font-size:18px;padding-bottom:10px"><b> PURCHASE INDENT</b></td>
         </tr>
       </table>
<table style="padding:1px;margin-top:2px" border="0" width="100%">
        <tr>
             <?php if(!empty($pi_datas['purchase_type']=='SURPLUS STOCK')){?>
        <td width="50%" style="padding-bottom:0px" ><b> From</b>  </td>
        <?php } else {?>
             <td width="50%" style="padding-bottom:0px"><b> To</b>  </td>
        <?php } ?>
        <td width="50%" style="padding-bottom:0px" align="center"> <b>P.I. REFERENCE</b>  </td>
        </tr>
</table>
<table width="100%"  class="table" >
    <tr>
         <?php if(!empty($pi_datas['purchase_type']=='SURPLUS STOCK')){ ?>
        <td width="48%" >
  <table class="table" width="100%" style="border:1px solid black;">
      <tbody>
                                          <tr>
                                                <td class="process-title">Dept. Name</td>
                                                 <td class="process-valuemid">:</td>
                                                <td class="process-value">PURCHASE DEPT</td>
                                            </tr>
                                             <tr>
                                                <td class="process-title">Cont. Person</td>
                                                 <td class="process-valuemid">:</td>
                                                <td class="process-value"><?php echo @$ArrProfileInfo['name'];; ?></td>
                                            </tr>
                                            <tr>
                                                <td class="process-title">Cont. No</td>
                                                 <td class="process-valuemid">:</td>
                                                <td class="process-value"><?php echo @$ArrProfileInfo['mobile']; ?></td>
                                            </tr>
            </tbody>
  </table>
   <table class="table" width="100%" >
      <tbody>
        <tr><td class="process-value"><b>To</b></td></tr>
                </tbody>
        </table>
  <table class="table" width="100%" style="border:1px solid black;">
      <tbody>
                                          <tr>
                                                <td class="process-title">Dept. Name</td>
                                                 <td class="process-valuemid">:</td>
                                                <td class="process-value">BOM STORE</td>
                                            </tr>
                                             <tr>
                                                <td class="process-title">Cont. Person</td>
                                                 <td class="process-valuemid">:</td>
                                                <td class="process-value"><?php echo @$bomstorelogin_data['contactname']; ?></td>
                                            </tr>
                                            <tr>
                                                <td class="process-title">Cont. No</td>
                                                 <td class="process-valuemid">:</td>
                                                <td class="process-value"><?php echo @$bomstorelogin_data['mobile'] ?></td>
                                            </tr>
            </tbody>
  </table>

        </td>
        <?php  } else { ?>

            <td width="48%" >
  <table class="table" width="100%" style="border:1px solid black;">
      <tbody>
                                          <tr>
                                                <td class="process-title">Name</td>
                                                 <td class="process-valuemid">:</td>
                                                <td class="process-value"><?php echo $vendor_data['vendorname'] ?></td>
                                            </tr>
                                             <tr>
                                                <td class="process-title">Address</td>
                                                 <td class="process-valuemid">:</td>
                                                <td class="process-value"><?php echo  $vendor_data['address'] ?></td>
                                            </tr>
                                            <tr>
                                                <td class="process-title">Contact No</td>
                                                 <td class="process-valuemid">:</td>
                                                <td class="process-value"><?php echo $vendor_data['mobile'] ?></td>
                                            </tr>
                                             <tr>
                                                <td class="process-title">e-mail ID</td>
                                                 <td class="process-valuemid">:</td>
                                                <td class="process-value"><?php echo $vendor_data['emailid'] ?></td>
                                            </tr>
                                             <tr>
                                                <td class="process-title">GST No</td>
                                                 <td class="process-valuemid">:</td>
                                                <td class="process-value"><?php echo $vendor_data['gstno'] ?></td>
                                            </tr>
                                            <tr>
                                                <td class="process-title">IE Code</td>
                                                 <td class="process-valuemid">:</td>
                                                <td class="process-value"><?php echo $vendor_data['iecode'] ?></td>
                                            </tr>
            </tbody>
  </table>
   
    
        </td>
            <?php  } ?>
         <td width="2%" style="border:0px "></td>
          <td width="50%" style="border:1px solid black;">
            <table class="table" width="100%">
                                        <tbody>
                                          <tr>
                                                <td class="process-title">P.I. Ref. No</td>
                                                 <td class="process-valuemid">:</td>
                                                <td class="process-value"><?php echo $pi_datas['pi_ref_queue_no']; ?></td>
                                            </tr>
                                             <tr>
                                                <td class="process-title">Date & Time</td>
                                                 <td class="process-valuemid">:</td>
                                                <td class="process-value"><?php echo $pi_datas['pi_dt']; ?></td>
                                            </tr>
                                             <tr>
                                               <td class="process-title">Purchase Type</td>
                                                 <td class="process-valuemid">:</td>
                                                <td class="process-value"><?php echo $pi_datas['purchase_type'] ?></td>
                                            </tr>
                                             <tr>
                                                <td class="process-title">Exptd. Date of Delivery</td>
                                                 <td class="process-valuemid">:</td>
                                                <td class="process-value"><?php echo $pi_datas['exp_dod']; ?></td>
                                            </tr>
                                            <tr>
                                                <td class="process-title">WIP No</td>
                                                 <td class="process-valuemid">:</td>
                                                <td class="process-value"><?php echo $pi_datas['isriorcode'];?></td>
                                            </tr>
                                            <tr>
                                                <td class="process-title">Queue No</td>
                                                 <td class="process-valuemid">:</td>
                                                <td class="process-value"><?php echo @$pi_datas['ref_queue_no']; ?></td>
                                            </tr>
                                             
                                           
                                          </tbody>
                                          </table>
          </td>
      </tr>
      </table>
      <table  width="100%"  >
       
         <td align="left" style="border-bottom:1px solid font-size:20px; padding-bottom: 5px;padding-top: 5px;"><b> We hereby place an order for the following items:</b></td>
         </tr>
       </table>
     <table class="details"   width="100%" >
  <thead>
    <tr style="height:50px">
     <th width="3%">S.No.</th>
       <th width="10%">Item Description</th>
      <th width="13%">Blend (%) <br>/ Content / Material</th>
      <th width="5%"> Garment Size</th>
      <th width="8%">Item Code <br> </th>
       <th width="8%">Item Colour Code<br> </th>
      <th width="6%"> Size / Dim (L*W*H)</th>
    
      <th width="5%">UOM</th>
    
    
      <th width="6%">Qty.</th>
        <th width="5%">UOM</th>
      <th width="7%">Unit Rate (Rs.)</th>
    
      <th width="8%">Amount (Rs.) </th>
        <th width="7%">GST (%)</th>
      <th width="8%">Sub Total (Rs.)</th>
      
      
    </tr>
  </thead>
  <tbody >
    <!-- <pre><?php print_r($sample_data); ?></pre> -->
     <?php 
     $tot_amt = 0;
     $all_tot = 0;
      $i = 1; // initialize counter
     foreach($item_data as $r) { ?>
     
       <tr width="100%">
      <td height="29" align="center" style="border-right: 1px solid black;"><?php echo $i;?></td>
      <td align="left" style="padding-left: 4px;border-right:1px solid black;"><?php echo $r['item_desc'];?></td>
      <td align="left" style="padding-left: 4px;border-right: 1px solid black;"><?php echo $r['bcm'];?> </td>
       <td align="left" style="padding-left: 4px;border-right:1px solid black;"><?php echo $r['garment_size'];?></td>
      <td align="left" style="padding-left: 4px;border-right:1px solid black;"><?php echo $r['appr_item_code'];?></td>
      <td align="left" style="padding-left: 4px;border-right: 1px solid black;"><?php echo $r['appr_item_col_code'];?>  </td>     
      
      <td align="left" style="padding-left: 4px;border-right:1px solid black;"><?php echo $r['size_dim'];?></td>
      <td align="Center" style="padding-left: 4px;border-right:1px solid black;"><?php echo $r['uoms'];?></td>
      
      <td align="right" style="padding-left: 4px;border-right:1px solid black;"><?php echo number_format($r['qty']);?></td>
      <td align="center" style="padding-left: 4px;border-right:1px solid black;"><?php echo $r['requirement_uom'];?></td>
      <td align="right" style="padding-right: 4px;border-right:1px solid black;"><?php echo number_format($r['unit_rate'],2);?></td>
      <td align="right" style="padding-right: 4px;border-right:1px solid black;"><?php echo number_format($r['amount'],2);?></td>
      
      <?php if($r['mode'] == 'within') { ?>
          <td align="right" style="padding-right: 4px;border-right:1px solid black;"><?php echo number_format($r['gst'],2);?></td>
      <?php } else if($r['mode']=='inter') { ?>
      <td align="right" style="padding-right: 4px;border-right:1px solid black;"><?php echo number_format($r['igst'],2);?></td>
      <?php } else { ?>
      <td align="right" style="padding-right: 4px;border-right:1px solid black;"><?php echo number_format($r['igst'],2);?></td>
      <?php } ?>
      <td align="right" style="padding-right: 4px;border-right:1px solid black;"><?php echo number_format($r['sub_total'],2);?></td>
      </tr>
  <?php     
       ?>
      
<?php 
        $tot_amt += $r['amount'];
        $all_tot += $r['sub_total'];
         $i++;
      } ?>

        </tbody>
</table>
 <table width="100%" border="0" align="center" style="border-bottom:1px solid black;border-right:1px solid black;border-left:1px solid black;border-collapse:collapse;">
        <tr>
  
         <td width="76%"  align="right" style="border-bottom: 1px dotted black;padding-top:5px;padding-bottom:5px;"><strong> Total:</strong> </td>
          
          
          <td  width="8%" align="right" style="border-bottom: 1px dotted black;padding-left:15px;adding-top:5px;padding-bottom:5px;padding-right: 4px;"><strong><?php echo number_format($tot_amt,2); ?></strong></td>
         

          <td  width="7%" style="border-bottom: 1px dotted black;padding-left:15px;padding-top:5px;padding-bottom:5px;"></td>
          
          <td  width="8%" align="right" style="border-bottom: 1px dotted black;padding-right:3px;adding-top:5px;padding-bottom:5px;padding-right: 4px;"><strong><?php echo number_format($all_tot,2); ?></strong></td>
         
        </tr>

      </table>


<table width="100%" height="60" align="center" style="border-collapse:collapse;border-bottom:none;border-right:1px solid black;border-left:1px solid black;">
 <tr>
          <td width="12%" height="10" style="padding-top: 12px;"><b style="font-size:11px;padding-left: 5px;">&nbsp;&nbsp;Amount In Words:</small></td>
          <td width="30%"  height="10" style="padding-top: 12px;"><?php echo $pi_datas['amount_in_words'];?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
          <td width="40%" height="10" style="padding-top: 12px;" align="right"><b >&nbsp;&nbsp;<b></b></small> </td>
          
        </tr>
<tr>
          <td width="12%" height="30" style="padding-bottom: 10px;"><b style="font-size:11px;padding-left: 5px">&nbsp;&nbsp;Payment Terms:</small> </td>
          <td width="30%"  height="30" style="padding-bottom: 10px;"><?php echo $pi_datas['payment_terms'];?></td>
          <td width="40%" height="20" style="padding-bottom: 10px;" align="right">For <b style="padding-right:5px;"><b> Azibo Infotech Private Limited</b></small> </td>
          
          
        </tr>

        

</table>
<table width="100%" height="50"  style="border-collapse:collapse;border-bottom:1px solid; solid black;border-right:1px solid black;border-left:1px solid black;">

        <tr>
          <td width="50" height="15" align="left" style="padding-left:10px"><?php echo $pi_datas['pi_req_name'];; ?></td>
          <td width="50" height="15" align="left" ></td>
          
          <td width="50" height="15" align="left"><?php echo $pi_datas['pi_appr_name']; ?></td>
        <td width="50" height="15" align="right"></td>
        
        </tr>

        <tr>
          <td width="50" height="10" align="left" style="padding-left:10px"><b>P.I. Prepared by</b></td>
          <td width="50" height="10" align="left" ><b></b></td>
           <td width="50" height="10" align="left" ><b>P.I. Approved by</b></td>
          <td width="50" height="10" align="right "style="padding-right:5px" >Authorized Signatory</td>
        </tr>

      
    
</table>

      </table>
      
</body>
</html>
 