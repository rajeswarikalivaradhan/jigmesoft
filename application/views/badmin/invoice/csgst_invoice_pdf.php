<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<style>
@media print {
  @page {
    size: landscape;
  }
}
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
$trowss='';
$trows='';
$j=1;
foreach($ArrDetResults as $k=>$v){
     $qty=intval($v['qty']);
     $trows .="<tr><td class='text-center'>$j</td>"
     
               . "<td class=''>$v[description]</td>"
               
               . "<td class=''>$v[detail]</td>"   

               . "<td class='text-right'>$v[unit_id]</td>" 

               . "<td class='text-center'>$qty</td>"
               
               . "<td class='text-right'>$v[amount]</td>"   

               . "<td class='text-right'>$v[sgst_percent]</td>" 

               . "<td class='text-right'>$v[cgst_percent]</td>"
               
               . "<td class='text-right'>$v[sgst_amount]</td>"
                
                . "<td class='text-right'>$v[cgst_amount]</td>"
                
                . "<td class='text-right'>$v[subtotal]</td>"

               . "</tr>" ;
               
               $j++;
}
for ($i=1;$i<3;$i++){
   $trowss .="<tr><td class='detail_td'></td><td class='detail_td'></td>"

               . "<td class='detail_td'></td>"   

               . "<td class='detail_td'></td>" 

               . "<td class='detail_td'></td>"
               
               . "<td class='detail_td'></td>"   

               . "<td class='detail_td'></td>" 

               . "<td class='detail_td'></td>"
               
               . "<td class='detail_td'></td>"
                
                . "<td class='detail_td'></td>"
                
                . "<td class='detail_td'></td>"
                 
               
               . "</tr>" ;
}
?>
<table  width="100%">
 <tr>
        <td align="" style="border:1px solid black;padding:5px;">
           <b style="font-size:18px;" >Azibo Infotech Private Limited </b><span style="float:right;"> No.88, Block - B, Bose Garden Layout, Saravanampatti, Coimbatore - 641035.</span></br>  
           <span style="float:right;"> Mobile: 9943931113, E-mail: jigmesoft@gmail.com,</span></br></br>
           <span style="float:right;"> GST NO. :33ABCCA1637R1ZH </span></br></td>
         </tr>
         <!--<tr>-->
         <!--   <td align="" style="border:1px solid black;padding:2px;">-->
         <!--      <b style="font-size:18px;">Jigme Soft Solutions Private Limited </b>-->
         <!--      <p class="text-right"> No.88, Block - B, Bose Garden Layout, Saravanampatti, Coimbatore - 641035.</p>-->
         <!--      <p class="text-right"> Mobile: 9943931113, E-mail: jigmesoft@gmail.com,</p>-->
         <!--      <p class="text-right"> GST NO. :33AAFCJ2474F1ZR </p>-->
         <!--  </td>-->
         <!--</tr>-->
</table>
<table cellspacing="0" cellpadding="3" border="0" width="100%">
        <tr>
         <td align="center" style="border-bottom:1px solid #151515;font-size:18px"><b>Proforma Invoice</b></td>
         </tr>
       </table>
<table style="padding:1px;" border="0">
        <tr>
        <td width="333" style="padding-left :5px"> <b>To</b>  </td>
        <td width="340" align="center">  <b>Proforma Reference </b> </td>
        </tr>
</table>
<table width="100%"  class="table">
    <tr>
         <td width="48%" style="border:1px solid black;">
        <table class="table" width="100%">
                                        <tbody>
                                             <?php $addr=(!empty($BasicInfo->invoaddress)) ?$BasicInfo->invoaddress:'';
                                                $city= (!empty($addr && $BasicInfo->invocity)) ?','.$BasicInfo->invocity:''; 
                                                $state= (!empty($city && $BasicInfo->invostate))? ','.$BasicInfo->invostate:''; 
                                                $country=(!empty($state && $BasicInfo->invocountry))?','.$BasicInfo->invocountry:''; 
                                                $pincode=(!empty($country && $BasicInfo->invopincode))? ', Pincode:'.$BasicInfo->invopincode:''; 
                                                $addressstr=$addr.$city.$state.$country.$pincode; ?>
                                            <tr>
                                                <td class="process-title">Name</td>
                                                <td class="process-valuemid">:</td>
                                                <td class="process-value"><?php if (!empty($BasicInfo->invocmpnyname)) echo $BasicInfo->invocmpnyname; ?></td>
                                            </tr>
                                            <tr>
                                                <td class="process-title" style="vertical-align:top;">Address</td>
                                                <td class="process-valuemid" style="vertical-align:top;padding:2px 2px 2px 2px">:</td>
                                                <td class="process-value" <?php if (!empty($addressstr)  && (strlen($addressstr)>=50)) { ?> style="padding:2px 2px 2px 2px!important;"  <?php } else { ?> style="padding:2px 2px 45px 2px!important;" <?php } ?>>  <?php 
                                                
                                                if (!empty($addressstr)) { echo wordwrap($addressstr, 50, "<br/>"); } ?></td>
                                            </tr>
                                            
                                            <!--<tr>-->
                                            <!--    <td class="process-titles">Address</td>-->
                                            <!--    <td class="process-valuemids">:</td>-->
                                            <!--    <td class="process-value"><?php if (!empty($BasicInfo->invocity)) echo $BasicInfo->invocity; ?>,<?php if (!empty($BasicInfo->invostate)) echo $BasicInfo->invostate; ?>,<?php if (!empty($BasicInfo->invocountry)) echo $BasicInfo->invocountry; ?></td>-->
                                            <!--</tr>-->
                                            <!--<tr>-->
                                            <!--    <td class="process-titles">Address</td>-->
                                            <!--    <td class="process-valuemids">:</td>-->
                                            <!--    <td class="process-value"><?php if (!empty($BasicInfo->invopincode)) echo 'Pincode:'.$BasicInfo->invopincode; ?></td>-->
                                            <!--</tr>-->
                                            <tr>
                                                <td class="process-title">GST No</td>
                                                <td class="process-valuemid">:</td>
                                                <td class="process-value"><?php if (!empty($BasicInfo->invogst_no)) echo $BasicInfo->invogst_no; ?></td>
                                            </tr>
                                        </tbody>
                                        </table>
        </td>
       <td width="2%" style="border:0px "></td>
          <td width="50%" style="border:1px solid black;">
          <table class="" width="100%">
                                        <tbody>
                                            <tr>
                                                <td class="process-title">Prof. Invoice Ref. No</td>
                                                 <td class="process-valuemid">:</td>
                                                <td class="process-value"><?php if (!empty($BasicInfo)) echo $BasicInfo->invoice_refno; ?></td>
                                            </tr>
                                            <tr>
                                                <td class="process-title">Date</td>
                                                 <td class="process-valuemid">:</td>
                                                <td class="process-value"><?php if (!empty($BasicInfo)) echo $BasicInfo->invoice_datetime; ?></td>
                                            </tr>
                                            <tr>
                                                <td class="process-title">Prof. Invoice Validity</td>
                                                 <td class="process-valuemid">:</td>
                                                <td class="process-value"><?php if (!empty($BasicInfo)) echo $BasicInfo->invoice_validity; ?></td>
                                            </tr>
                                            <tr>
                                                <td class="process-title">Subscription Period</td>
                                                 <td class="process-valuemid">:</td>
                                                <td class="process-value"><?php $subscription_period = unserialize(ARRSUBSCRIPTIONPERIOD);if (!empty($BasicInfo)) echo $subscription_period[$BasicInfo->subscription_period]; ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="process-title">Purchase Type</td>
                                                <td class="process-valuemid">:</td>
                                                <td class="process-value"><?php if (!empty($purchase_type)) echo $purchase_type; ?></td>
                                            </tr>
                                        </tbody>
                                        </table>
        </td>
        </tr>
</table>
<table cellspacing="0" cellpadding="3" border="0">
    <tr>
        <td align="left" ><b>Herewith, we quote for the following services:</b></td>
    </tr>
</table>
<table  class="details" cellspacing="0" cellpadding="4" border="1" width="100%" >
       <tr style="font-weight:bold;">
       <td width="5%" align="center">S.No.</td>
       <td width="17%" align="center">Description</td>
       <td width="17%" align="center">Details</td>
       <td width="9%" align="center">Unit Rate </br> (Rs)</td>
       <td width="6%" align="center">Qty. (Nos)</td>
       <td width="8%" align="center">Amount </br> (Rs)</td>
       <td width="6%" align="center">SGST </br> (%)</td>
       <td width="6%" align="center">CGST </br> (%)</td>
       <td width="8%" align="center">SGST </br>Value (Rs)</td>
       <td width="8%" align="center">CGST </br> Value (Rs)</td>
       <td width="10%" align="center">Sub Total </br> (Rs)</td>
       </tr>
       <?php echo $trows ?>
       <?php //echo $trowss ?>
        <tr style="font-weight:bold;">
        <td style="border-right:1px solid #ffffff"></td>
        <td style="border-right:1px solid #ffffff"></td>
        <td style="border-right:1px solid #ffffff"></td>
        <td></td>
        <td align="right">Total :</td>
        <td class='text-right'><?php if (!empty($BasicInfo)) echo number_format ($BasicInfo->total,2); //$BasicInfo->total; ?></td>
        <td></td>
        <td></td>
        <td class='text-right'><?php if (!empty($BasicInfo)) echo number_format ($BasicInfo->sgst_amount,2); //$BasicInfo->sgst_amount; ?></td>
        <td class='text-right'><?php if (!empty($BasicInfo)) echo number_format ($BasicInfo->cgst_amount,2); //$BasicInfo->cgst_amount; ?></td>
        <td class='text-right'><?php if (!empty($BasicInfo)) echo number_format($BasicInfo->subtotal,2); //$BasicInfo->subtotal; ?></td>
        </tr>
</table>
<table style="padding:3px;" border="0">
       
</table>
<table cellspacing="0"  border="0"  width="100%" style="align:left" >
    <tr>
        <td  width="16%" style="padding-top:10px" align="left"><b>Amount in words : </b></td>
        <td width="50%" style="padding-top:10px" align="left" > <?php echo ucwords($amount);?></td>
       
    </tr>
    <tr >
        <td align="left" style="padding-top:20px;" ><b> Terms & Conditions : </b></td>
        <td align="left" style="padding-top:20px; " > <?php if (!empty($BasicInfo)) echo $BasicInfo->terms_and_condition; ?></td>
        <td align="right" style="padding-top:20px;" ></td>
         <td align="left"></td>
    </tr>
</table>
<table cellspacing="0"  border="0" style="align:right;padding-left:5px"   width="100%" >
   
    <tr>
        
        <td width="40%"   align="right">For <b>Azibo Infotech Private Limited</b></td>
    </tr>
 
    <tr>
       
        <td width="16%"  align="right" style="padding-top:45px;" >Authorized Signatory</td>
    </tr>
</table>
</body>
</html>

<Script>
window.print();
</Script>