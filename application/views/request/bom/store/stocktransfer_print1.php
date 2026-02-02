<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Stock Transfer Memo</title>
  <style>
    @page {
  margin: 0mm;
}

@page { size: landscape; }

    body {
      font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
      font-size: 12px;
      font-weight: normal;
      margin: 0;
      padding: 0;
    }

    table {
      border-collapse: collapse;
      border-spacing: 0;
      width: 100%;
    }

    .b-0 { border-top: none !important; }

    .table-responsive { overflow-x: unset !important; }

    .ord-procs-cell {
      width: 25%;
      padding: 2px 4px !important;
    }

    .tbl-procs-border { border: 1px solid #ddd !important; }

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

    .text-right { text-align: right; }
    .text-center { text-align: center; }

    /* ---------- Fixed Material Details Table ---------- */
    table.details {
      width: 100%;
      border: 1px solid black;
      border-collapse: collapse;
      table-layout: fixed; /* ✅ keeps it within 100% */
    }

    table.details th, table.details td {
      border: 1px solid black;
      padding: 4px;
      text-align: center;
      vertical-align: middle;
      font-size: 12px;
      word-wrap: break-word;
      white-space: normal;
    }

    /* Optional width helpers if you want fixed ratios */
    .w-2 { width: 2%; }
    .w-3 { width: 3%; }
    .w-5 { width: 5%; }
    .w-6 { width: 6%; }
    .w-7 { width: 7%; }
    .w-8 { width: 8%; }
    .w-9 { width: 9%; }
    .w-10 { width: 10%; }
    .w-11 { width: 11%; }
    
     

    /* ---------- Print Layout ---------- */
    @media print {
      body { margin: 0; padding: 0; }
      table.details { max-width: 100%; overflow: hidden; }
      @page { margin: 10mm; }
    }

    table.fixed {
  table-layout: fixed;
  width: 100%;
}
table.fixed td {
  word-wrap: break-word;
  white-space: normal;
}
  </style>
</head>
<body>

<?php

$surplusDatass = $surplusDatas[0];
$subcompany_datas = $subcompany_data[0]; 
$surplusDatasspi_data = $surplusDatasspi[0];
$datas = $data[0];


?>

<!-- ======= Header ======= -->
 <div class="col-md-12" style="padding:20px 20px 10px 20px !important;">
      <table  width="100%"> 
<table  width="100%"> 
 <tr>
        <td align="" style="border:1px solid black;padding:5px;">
           <b style="font-size:18px;" ><?php echo $subcompany_datas['companyname'];?> </b>
           <span style="float:right;"> <?php echo $subcompany_datas['address'];?>, <?php echo $subcompany_datas['city'];?> - <?php echo $subcompany_datas['pincode'];?>.
           <?php echo $subcompany_datas['state'];?>, <?php echo $subcompany_datas['country'];?>
        </span></br>  
           <span style="float:right;">E-mail: <?php echo $subcompany_datas['email_id']?>, Mobile: <?php echo $subcompany_datas['mobile_no'];?></span></br></br>
           <span style="float:right;"> GST No: <?php echo $subcompany_datas['gst_no']?> /  IE Code: <?php echo $subcompany_datas['gst_no']?></span></br></td>
         </tr>
        
</table>

<!-- ======= Title ======= -->
<table cellspacing="0" cellpadding="3" border="0" width="100%" style="margin-top:10px">
  <tr>
    <td align="center" style="border-bottom:1px solid #151515; font-size:18px; padding-bottom:10px;">
      <b>STOCK TRANSFER MEMO</b>
    </td>
  </tr>
</table>

<!-- ======= Reference Section ======= -->
<table style="padding:1px;margin-top:4px" border="0" width="100%" >
  <tr>
    <td width="50%" style="padding-bottom:4px"><b>TRANSFER DETAILS</b></td>
    <td width="50%" style="padding-bottom:4px" align="center"><b>S.T.M. REFERENCE</b></td>
  </tr>
</table>

<table width="100%">
  <tr>
    <td width="48%">
      <!-- LEFT TABLE -->
       <table width="100%" style="border:1px solid black;padding-top:2px" height="100">
        <tr><td class="process-title">Transfer From</td><td>:</td><td class="process-value">Surplus Stock List</td></tr>
        <tr><td class="process-title">Transfer To</td><td>:</td><td class="process-value">Order Stock List</td></tr>
        <tr><td class="process-title">Transfer Category</td><td>:</td><td class="process-value"><?php echo $surplusDatasspi_data['transfer_category']; ?></td></tr>
      </table>
    </td>

    <td width="2%"></td>

    <td width="50%" style="border:1px solid black;">
      <table width="100%">
        <tr><td class="process-title">S.T.M. Ref. No</td><td>:</td><td class="process-value"><?php echo $surplusDatasspi_data['stm_ref_no']; ?></td></tr>
        <tr><td class="process-title">Date & Time</td><td>:</td><td class="process-value"><?php echo $surplusDatasspi_data['stm_date_time']; ?></td></tr>
        <tr><td class="process-title">Cutoff Date & Time</td><td>:</td><td class="process-value"><?php echo $surplusDatasspi_data['cutoff_date']; ?></td></tr>
       <tr><td class="process-title">WIP No</td><td>:</td><td class="process-value"><?php echo $company_data['ArrEnquiryDetails']['isriorcode']; ?></td></tr>
        <tr><td class="process-title">Queue No</td><td>:</td><td class="process-value"><?php echo $surplusDatass['ref_queue_no']; ?></td></tr>
        <tr><td class="process-title">P.I. Ref. No</td><td>:</td><td class="process-value"><?php echo $surplusDatass['pi_ref_queue_no']; ?></td></tr>
  
      </table>
    </td>
  </tr>
</table>

<!-- ======= Material Issued Details ======= -->
<table style="margin-top:5px; width:100%;">
  <tr><td><b>Material Issued Details:</b></td></tr>
</table>

<table class="details"  width="100%">
  <thead>
    <tr>
      <th width="4%">S.No.</th>
      <th width="7%">Original P.I. Ref. No.</th>
      <th width="7%">Original Invoice No.</th>
    
      <th width="8%">Item Description</th>
      <th width="7%">Blend(%) / Content / Material  </th>
      <th width="6%">Garment Size</th>
    
      <th width="5%">Item Code </th>
       <th width="6%">Item Colour Code </th>
        <th width="6%">Size / Dim (L*W*H)</th>
      <th width="4%">UOM</th>
      <th width="6%">Item Lot / Batch Ref No.</th>
       <th width="6%">Qty.</th>
      <th width="4%">UOM</th>
      <th width="5%">Unit Rate (Rs.)</th>
       <th width="7%">Amount (Rs.)</th>
        <th width="5%">GST (%)</th>
        <th width="7%">Sub Total (Rs.)</th>
      
    </tr>
  </thead>
  <tbody>
    <?php if (!empty($surplus_data['draftData'])): ?>
      <?php $i = 1; $tot_amt = 0; $tot_qty = 0; $all_tot = 0; ?>
      <?php foreach ($surplus_data['draftData'] as $row):    
          $total_amount = $row[13] * $row[15]; 
          $sub_total = round($total_amount + $total_amount * $row[17] / 100);
          $tot_amt += $total_amount;
          $tot_qty += $row[13];
          $all_tot += $sub_total;
      ?>
      <tr>
        <td><?php echo $i++; ?></td>
        <td style="text-align:left;padding-right:4px"><?php echo htmlspecialchars($row[3]); ?></td>
        <td style="text-align:left;padding-right:4px"><?php echo htmlspecialchars($row[4]); ?></td>
        <td style="text-align:left;padding-right:4px" ><?php echo htmlspecialchars($row[5]); ?></td>
        <td style="text-align:left;padding-right:4px"><?php echo htmlspecialchars($row[6]); ?></td>
        <td style="text-align:left;padding-right:4px" ><?php echo htmlspecialchars($row[7]); ?></td>
        <td style="text-align:left;padding-right:4px" ><?php echo htmlspecialchars($row[8]); ?></td>
        <td style="text-align:left;padding-right:4px" ><?php echo htmlspecialchars($row[9]); ?></td>
        <td style="text-align:left;padding-right:4px" ><?php echo htmlspecialchars($row[10]); ?></td>
        <td style="text-align:center;" ><?php echo htmlspecialchars($row[11]); ?></td>
        <td style="text-align:left;padding-right:4px"><?php echo htmlspecialchars($row[12]); ?></td>
        <td style="text-align:right;" ><?php echo number_format($row[13]); ?></td>
        <td><?php echo htmlspecialchars($row[14]); ?></td>
        <td style="text-align:right;" ><?php echo number_format($row[15],2); ?></td>
        <td style="text-align:right;" ><?php echo number_format(($row[13] * $row[15]),2); ?></td>
        <td style="text-align:right;" ><?php echo number_format($row[17],2); ?></td>
        <td style="text-align:right;"><?php echo number_format($sub_total,2); ?></td>
      </tr>
      <?php endforeach; ?>
    <?php else: ?>
      <tr><td colspan="17">No data found</td></tr>
    <?php endif; ?>
  </tbody>
</table>

<!-- ======= Totals ======= -->

<table width="100%" border="0"  height="30" align="center" style="border-bottom:1px solid black;border-right:1px solid black;border-left:1px solid black;border-collapse:collapse;padding-bottom: 20px;">
        <tr  width="100%" >
  <td width="64%"  align="right" style="border-bottom: 1px dotted black;"><strong> Total:</strong> </td>
          
          
          <td  width="6%" align="right" style="border-bottom: 1px dotted black;padding-left:15px;"><strong><?php echo number_format($tot_qty); ?></strong></td>
          <td width="4%"   style="border-bottom: 1px dotted black;"></td>
          <td  width="5%" align="right" style="border-bottom: 1px dotted black;padding-left:15px;"></td>
          <td width="7%"   align="right" style="border-bottom: 1px dotted black;"><strong><?php echo number_format($tot_amt,2); ?></strong></td>
          <td  width="5%" align="right" style="border-bottom: 1px dotted black;padding-left:15px;"><strong></strong></td>
          
          <td  width="7%" align="right" style="border-bottom: 1px dotted black;padding-right:3px;"><strong><?php echo number_format($all_tot,2); ?></strong></td>
         
        </tr>

      </table>



<!-- ======= Footer ======= -->


        <table width="100%" style="border-collapse: collapse; border: none; border-right: 1px solid black; border-left: 1px solid black;">
  <tr>
    <td width="12%" height="20" style="padding-top: 13px;  ">
      <b>&nbsp;&nbsp;Amount In Words:</b>
    </td>
    <td width="50%" height="20" style=" padding-top: 13px; ">
      <?php echo $surplusDatass['amount_in_words'];; ?>
    </td>
  </tr>
  <tr>
    <td width="12%" height="30" style=" padding-top: 1px; padding-bottom: 10px;">
      <b>&nbsp;&nbsp;Payment Terms:</b>
    </td>
    <td width="30%" height="30" style=" padding-top: 1px; padding-bottom: 10px;">
      <?php echo $surplusDatass['payment_terms'];?>
    </td>
    <td width="30%" height="30" align="right" style=" padding-top: 10px; padding-bottom: 10px;padding-right:5px">
      For<b> Azibo Infotech Private Limited</b>
    </td>
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

</div>

<script>
  window.print();
</script>

</body>
</html>
