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
//print_r($pi_data); exit;
$company_datas = $company_data[0];  
$surplusDatass = $surplusDatas[0];  
$subcompany_datas = $subcompany_data[0]; 

$surplusDatasspi_data=$surplusDatasspi[0];
$datas = $data[0];
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
    <td align="center" style="border-bottom:1px solid #151515; font-size:18px; padding-bottom:10px;">
      <b>STOCK TRANSFER MEMO</b>
    </td>
  </tr>
</table>

<!-- ======= Reference Section ======= -->
<table style="margin-top:2px" border="0" width="100%">
  <tr>
    <td width="50%" style="padding-bottom:0px"><b>TRANSFER DETAILS</b></td>
    <td width="50%" style="padding-bottom:0px" align="center"><b>S.T.M. REFERENCE</b></td>
  </tr>
</table>
<table width="100%"  class="table">
    <tr>
        <td width="48%" height="100">
  
   
   <table width="100%" style="border:1px solid black;padding-top:40px;padding-bottom:40px" >
      <tbody>
                                          <tr >
                                                <td class="process-title">Transfer From</td>
                                                 <td class="process-valuemid">:</td>
                                                <td class="process-value">Surplus Stock List</td>
                                            </tr>
                                             <tr>
                                                <td class="process-title">Transfer To</td>
                                                 <td class="process-valuemid">:</td>
                                                <td class="process-value">Order Stock List</td>
                                            </tr>
                                            <tr>
                                                <td class="process-title">Transfer Category</td>
                                                 <td class="process-valuemid">:</td>
                                                <td class="process-value"><?php echo $surplusDatasspi_data['transfer_category'] ?></td>
                                            </tr>
            </tbody>
  </table>

        </td>
         <td width="2%" style="border:0px "></td>
          <td width="50%" style="border:1px solid black;">
            <table class="table" width="100%">
                                        <tbody>
                                          <tr>
                                                <td class="process-title">S.T.M. Ref.No</td>
                                                 <td class="process-valuemid">:</td>
                                                <td class="process-value"><?php echo $surplusDatasspi_data['stm_ref_no']; ?></td>
                                            </tr>
                                             <tr>
                                                <td class="process-title">Date & Time</td>
                                                 <td class="process-valuemid">:</td>
                                                <td class="process-value"><?php echo $surplusDatasspi_data['stm_date_time']; ?></td>
                                            </tr>
                                             <tr>
                                                <td class="process-title">Cutoff Date & Time</td>
                                                 <td class="process-valuemid">:</td>
                                                <td class="process-value"><?php echo $surplusDatasspi_data['cutoff_date'] ?></td>
                                            </tr>
                                             <tr>
                                                <td class="process-title">WIP No</td>
                                                 <td class="process-valuemid">:</td>
                                                <td class="process-value">SAMPLE DEPT</td>
                                            </tr>
                                             <tr>
                                                <td class="process-title">Queue No</td>
                                                 <td class="process-valuemid">:</td>
                                                <td class="process-value"><?php echo  $surplusDatass['ref_queue_no'] ?></td>
                                            </tr>
                                            <tr>
                                                <td class="process-title">P.I.Ref.No</td>
                                                 <td class="process-valuemid">:</td>
                                                <td class="process-value"><?php echo $surplusDatass['pi_ref_queue_no']; ?></td>
                                            </tr>
                                             
                                             
                                           
                                          </tbody>
                                          </table>
          </td>
      </tr>
      </table>
      <table cellspacing="0" cellpadding="3" border="0" width="100%"  style="table-layout: fixed;">
       
         <td align="left" style="border-bottom:1px solid font-size:20px; padding-bottom: 5px;padding-top: 5px;"><b> Material Issued Details:</b></td>
         </tr>
       </table>
     <table class="details"   width="100%" style="table-layout: fixed;">
  <thead>
    <tr style="height:50px" width="100%">
    <th width="3%">S.No.</th>
      <th width="10%">Original P.I. Ref. No.</th>
      <th width="7%">Original Invoice No.</th>
    
      <th width="7%">Item Description</th>
      <th width="7%">Blend (%) Content / Material  </th>
      <th width="5%">Garment Size</th>
    
      <th width="5%">Item Code </th>
       <th width="6%">Item Colour Code </th>
        <th width="5%">Size / Dim (L*W*H)</th>
      <th width="4%">UOM</th>
      <th width="6%">Item Lot /Batch Ref No.</th>
       <th width="5%">Qty</th>
      <th width="4%">UOM.</th>
     <th width="7%">Unit Rate (Rs.)</th>
       <th width="7%">Amount (Rs.)</th>
        <th width="5%">GST(%)</th>
        <th width="7%">Sub Total (Rs.)</th>
      
    </tr>
  </thead>
  <tbody >
   
    
 <?php if (!empty($surplus_data['draftData'])): ?>
    <?php $i = 1; // counter starts from 1 ?>
    <?php foreach ($surplus_data['draftData'] as $row):    
        $total_amount = $row[13] * $row[15]; 
        $sub_total = round($total_amount+$total_amount * $row[17] /100);

         $tot_amt += $total_amount;
         $tot_qty += $row['13'];
         $all_tot += $sub_total;
        
        ?>
        <tr width="100%">
        <td><?php echo $i++; ?></td>
        <td style="text-align:left !important; padding:4px !important;"><?php echo htmlspecialchars($row[3]); ?></td>
        <td style="text-align:left !important; padding:4px !important;"><?php echo htmlspecialchars($row[4]); ?></td>
        <td style="text-align:left !important; padding:4px !important;" ><?php echo htmlspecialchars($row[5]); ?></td>
        <td style="text-align:left !important; padding:4px !important;"><?php echo htmlspecialchars($row[6]); ?></td>
        <td style="text-align:left !important; padding:4px !important;" ><?php echo htmlspecialchars($row[7]); ?></td>
        <td style="text-align:left !important; padding:4px !important;" ><?php echo htmlspecialchars($row[8]); ?></td>
        <td style="text-align:left !important; padding:4px !important;" ><?php echo htmlspecialchars($row[9]); ?></td>
        <td style="text-align:left !important; padding:4px !important;" ><?php echo htmlspecialchars($row[10]); ?></td>
        <td style="text-align:center;" ><?php echo htmlspecialchars($row[11]); ?></td>
        <td style="text-align:left !important; padding:4px !important;"><?php echo htmlspecialchars($row[12]); ?></td>
        <td style="text-align:right !important; padding:4px !important;" ><?php echo number_format($row[13]); ?></td>
        <td><?php echo htmlspecialchars($row[14]); ?></td>
        <td style="text-align:right !important; padding:4px !important;" ><?php echo number_format($row[15],2); ?></td>
        <td style="text-align:right !important; padding:4px !important;" ><?php echo number_format(($row[13] * $row[15]),2); ?></td>
        <td style="text-align:right !important; padding:4px !important;" ><?php echo number_format($row[17],2); ?></td>
        <td style="text-align:right !important; padding:4px !important;"><?php echo number_format($sub_total,2); ?></td>
      </tr>
    <?php endforeach; ?>
<?php else: ?>
    <tr><td colspan="11">No data found</td></tr>
<?php endif; ?>

        </tbody>
</table>
 <table width="100%" style="table-layout: fixed;" style="border-bottom:1px solid black;border-right:1px solid black;border-left:1px solid black;border-collapse:collapse;">
       <tr  >
   <td width="81%"  align="right" style="border-bottom: 1px dotted black;"><strong> Total:</strong> </td>
          
          
          <td  width="7%" align="right" style="border-bottom: 1px dotted black;padding-right:4px;"><strong><?php echo number_format($tot_amt,2); ?></strong></td>
          <td  width="5%" align="right" style="border-bottom: 1px dotted black;padding-left:15px;"><strong></strong></td>
          
          <td  width="7%" align="right" style="border-bottom: 1px dotted black;padding-right:4px;"><strong><?php echo number_format($all_tot,2); ?></strong></td>
         
        </tr>
      </table>


<table width="100%" height="60" align="center" style="border-collapse:collapse;border-bottom:none;border-right:1px solid black;border-left:1px solid black;">
 <tr>
          <td width="10%" height="10" style="padding-top: 12px;"><b style="font-size:11px;padding-left: 5px;">&nbsp;&nbsp;Amount In Words:</small></td>
          <td width="30%"  height="10" style="padding-top: 12px;"><?php echo $surplusDatass['amount_in_words'];;?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
          <td width="40%" height="10" style="padding-top: 12px;" align="right"><b >&nbsp;&nbsp;<b></b></small> </td>
          
        </tr>
<tr>
          <td width="10%" height="30" style="padding-bottom: 10px;"><b style="font-size:11px;padding-left: 5px">&nbsp;&nbsp;Payment Terms:</small> </td>
          <td width="30%"  height="30" style="padding-bottom: 10px;"><?php echo $surplusDatass['payment_terms'];;?></td>
          <td width="40%" height="20" style="padding-bottom: 10px;" align="right">For <b style="padding-right:5px;"><b> Azibo Infotech Private Limited</b></small> </td>
          
          
        </tr>

        

</table>
<table width="100%" height="50"  style="border-collapse:collapse;border-bottom:1px solid; solid black;border-right:1px solid black;border-left:1px solid black;">

        <tr>
          <td width="50" height="15" align="left" style="padding-left:10px"><?php echo $company_data['merchantName']; ?></td>
          <td width="50" height="15" align="left" ><?php echo $company_data['ArrMgmt']['contactname']; ?></td>
          
          <td width="50" height="15" align="left"><?php echo $surplusDatass['pi_req_name'];  ?></td>
          <td width="50" height="15" align="left"><?php echo $surplusDatass['pi_appr_name'];; ?></td>
        <td width="50" height="15" align="right"></td>
        
        </tr>

        <tr>
          <td width="50" height="10" align="left" style="padding-left:10px"><b>Request Raised By</b></td>
          <td width="50" height="10" align="left" ><b>Request Authorized By</b></td>
           <td width="50" height="10" align="left" ><b>P.I. Prepared By</b></td>
            <td width="50" height="10" align="left" ><b>P.I. Approved By</b></td>
          <td width="50" height="10" align="right "style="padding-right:5px" >Authorized Signatory</td>
        </tr>

      
    
</table>
      </table>
      
</body>
</html>
 