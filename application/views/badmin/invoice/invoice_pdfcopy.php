<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<style>
  
   .b-0{
       border-top:none!important;
   }
   .table-responsive {
    overflow-x: unset !important;
}

.ord-procs-cell {
    width: 25%;
}

.tbl-procs-border {
    border: 1px solid #ddd!important;
}
.table > tbody > tr > td {
    border-top:0px!important;
}

td.process-title {
    background: #f3f3f3;
    width: 30% !important;
    text-align: right;
}
td.process-titles {
    background: #f3f3f3;
    width: 30% !important;
    text-align: right;
    color:#f3f3f3;
}
tfoot td:first-child, tfoot td:nth-child(2){
     display: table-cell!important; 
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
.tables{
    margin-bottom: 5px!important;
}
.card-body{
    margin:6px!important;
}
table.table.tbl-procs-border {
    margin-bottom: 0;
}
.table {
    background: #F7F7F7!important;
}
.details >tr > td{
    /*padding:15px;*/
    border:1px solid black;
}
.detail_td{
   padding:13px; 
}

</style>
<body>
<?php
$trowss='';
for ($i=1;$i<3;$i++){
   $trowss .="<tr><td class='detail_td'></td><td class='detail_td'></td>"

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
<table  style="border:1px solid #151515;padding:5px;background: #F7F7F7!important;" width="100%">
 <tr>
        <td align="" >
           <b style="font-size:18px;" >Jigme Soft Solutions Private Limited </b> <br>  
           No.88, Block - B, Bose Garden Layout, Saravanampatti, Coimbatore - 641035.<br> 
           Mobile: 9943931113, E-mail: jigmesoft@gmail.com,<br> 
           GST NO. :33AAFCJ2474F1ZR</td>
         </tr>
</table>
<table cellspacing="0" cellpadding="4" border="0" width="100%">
        <tr>
         <td align="center" style="border-bottom:1px solid #151515;font-size:18px"><b>Proforma Invoice</b></td>
         </tr>
       </table>
<table style="padding:5px;" border="0">
        <tr>
        <td width="333"> To  </td>
        <td width="340" align="center">  Proforma Reference  </td>
        </tr>
</table>
<table id="" class="tables table" width="100%">
                            <tbody>
                                <tr>
                                    <td class="ord-procs-cell">
                                        <table class="table tbl-procs-border" width="100%">
                                        <tbody>
                                            <tr>
                                                <td class="process-title">Name</td>
                                                <td class="process-value"><?php if (!empty($BasicInfo->companyname)) echo $BasicInfo->companyname; ?></td>
                                            </tr>
                                            <tr>
                                                <td class="process-title">Address</td>
                                                <td class="process-value" ><?php if (!empty($BasicInfo->address)) echo $BasicInfo->address; ?></td>
                                            </tr>
                                            
                                            <tr>
                                                <td class="process-titles">Address</td>
                                                <td class="process-value"><?php if (!empty($BasicInfo->city)) echo $BasicInfo->city; ?>,<?php if (!empty($BasicInfo->state)) echo $BasicInfo->state; ?>,<?php if (!empty($BasicInfo->country)) echo $BasicInfo->country; ?></td>
                                            </tr>
                                            <tr>
                                                <td class="process-titles">Address</td>
                                                <td class="process-value"><?php if (!empty($BasicInfo->pincode)) echo 'Pincode:'.$BasicInfo->pincode; ?></td>
                                            </tr>
                                            <tr>
                                                <td class="process-title">GST No</td>
                                                <td class="process-value"><?php if (!empty($BasicInfo->gst_no)) echo $BasicInfo->gst_no; ?></td>
                                            </tr>
                                        </tbody>
                                        </table>
                                    </td>
                                    
                                    <td class="ord-procs-cell">
                                        <table class="table tbl-procs-border" width="100%">
                                        <tbody>
                                            <tr>
                                                <td class="process-title">Prof. Invoice. Ref. No</td>
                                                <td class="process-value"><?php if (!empty($BasicInfo)) echo $BasicInfo->invoice_refno; ?></td>
                                            </tr>
                                            <tr>
                                                <td class="process-title">Date & Time</td>
                                                <td class="process-value"><?php if (!empty($BasicInfo)) echo $BasicInfo->invoice_datetime; ?></td>
                                            </tr>
                                            <tr>
                                                <td class="process-title">Prof. Invoice. Validity</td>
                                                <td class="process-value"></td>
                                            </tr>
                                            <tr>
                                                <td class="process-title">Subscription Period</td>
                                                <td class="process-value">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="process-title">Purchase Type</td>
                                                <td class="process-value"><?php if (!empty($purchase_type)) echo $purchase_type; ?></td>
                                            </tr>
                                        </tbody>
                                        </table>
                                    </td>
                                    
                                </tr>
                            </tbody>
                        </table>
<table cellspacing="0" cellpadding="4" border="0">
    <tr>
        <td align="left" >Herewith, we quote for the following services:</td>
    </tr>
</table>
<table  class="details" cellspacing="0" cellpadding="4" border="1">
       <tr>
       <td width="25" align="center">S.No</td>
       <td width="200" align="center">Description</td>
       <td width="170" align="center">Details</td>
       <td width="55" align="center">Unit Rate (Rs)</td>
       <td width="41" align="center">Qty. (Nos)</td>
       <td width="58" align="center">Amt. (Rs)</td>
       <td width="40" align="center">IGST (%)</td>
       <td width="60" align="center">IGST Value (Rs)</td>
       <td width="60" align="center">Sub Total (Rs)</td>
       </tr>
       <?php echo $trowss ?>
        <tr>
        <td style="border-right:1px solid #ffffff"></td>
        <td style="border-right:1px solid #ffffff"></td>
        <td style="border-right:1px solid #ffffff"></td>
        <td style="border-right:1px solid #ffffff"></td>
        <td style="border-right:1px solid #ffffff"></td>
        <td></td>
        <td align="right">Total:</td>
        <td></td>
        <td></td>
        </tr>
</table>
<table style="padding:5px;" border="0">
       
</table>
<table  cellspacing="0" cellpadding="4"  style="border:none">
     <tr>
        <td width="325"></td>
        <td width="435" align="right">
        <table class="details" cellspacing="0" cellpadding="4" border="1" align="right">
           <tr> 
               <td width="40" align="center">IGST (%)</td>
               <td width="60" align="center">IGST Value (Rs)</td>
               <td width="60" align="center">Sub Total (Rs)</td>
           </tr>
             <tr> 
               <td class='detail_td'></td>
               <td class='detail_td'></td>
               <td class='detail_td'></td>
           </tr>
       </table> 
       </td>
        </tr>
</table>
<table cellspacing="0" cellpadding="4" border="0">
    <tr>
        <td align="left">Amount in words</td>
        <td align="left">:</td>
        <td align="left"></td>
    </tr>
    <tr>
        <td align="left" >Terms & Conditions</td>
        <td align="left">:</td>
        <td align="left"></td>
    </tr>
     <tr>
        <td width="115"></td>
        <td width="430"></td>
        <td align="330">For <b>Jigme Soft Solutions Private Limited</b></td>
    </tr>
    <tr>
        <td width="115"></td>
        <td width="430"></td>
        <td align="330" style="padding:14px!important" align="right"></td>
    </tr>
    <tr>
        <td width="115"></td>
        <td width="430"></td>
        <td align="330"  align="right"></td>
    </tr>
    <tr>
        <td width="115"></td>
        <td width="430"></td>
        <td align="330" align="right">Authorized Signatory</td>
    </tr>
</table>
</body>
</html>