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
$miDetails=$miDetails[0];
$samplelogin_datas = $samplelogin_data[0];  
$subcompany_datas = $subcompany_data[0]; 

$datas = $data[0];
$ArrProfileInfo = fnGetUserLoggedInfo(1);

?>
    <table  width="100%"> 
 <tr>
        <td align="" style="border:1px solid black;padding:5px;">
           <b style="font-size:18px;" ><?php echo $subcompany_datas['companyname'];?> </b>
           <span style="float:right;"> <?php echo $subcompany_datas['address'];?>, <?php echo $subcompany_datas['city'];?> - <?php echo $subcompany_datas['pincode'];?>.
           <?php echo $subcompany_datas['state'];?>, <?php echo $subcompany_datas['country'];?>
        </span></br>  
           <span style="float:right;">e-mail ID: <?php echo $subcompany_datas['email_id']?>, Mobile: <?php echo $subcompany_datas['mobile_no'];?></span></br></br>
           <span style="float:right;"> GST No: <?php echo $subcompany_datas['gst_no']?> /  IE Code: <?php echo $subcompany_datas['gst_no']?></span></br></td>
         </tr>
        
</table>

<table cellspacing="0" cellpadding="3" border="0" width="100%" style="margin-top:10px">
        <tr>
         <td align="center" style="border-bottom:1px solid #151515;font-size:18px;padding-bottom:10px"><b> MATERIAL  INDENT </b></td>
         </tr>
       </table>
<table style="margin-top:2px" border="0" width="100%">
        <tr>
        <td width="50%" style="padding-bottom:0px"><b>From</b>  </td>
        <td width="50%" style="padding-bottom:0px" align="center"> <b> M.I. REFERENCE <b> </td>
        </tr>
</table>
<table width="100%"  class="table">
    <tr>
        <td width="45%" >
  <table class="table" width="100%" style="border:1px solid black;">
      <tbody>
                                          <tr>
                                                <td class="process-title">Dept. Name</td>
                                                 <td class="process-valuemid">:</td>
                                                <td class="process-value">Bom store</td>
                                            </tr>
                                             <tr>
                                                <td class="process-title">Cont. Person</td>
                                                 <td class="process-valuemid">:</td>
                                                <td class="process-value"><?php echo @$ArrProfileInfo['name']; ?></td>
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
        <tr><td width="45%" class="process-value"><b>To</b></td></tr>
                </tbody>
        </table>
  <table class="table" width="100%" style="border:1px solid black;">
      <tbody>
                                          <tr>
                                                <td class="process-title">Dept. Name</td>
                                                 <td class="process-valuemid">:</td>
                                                <td class="process-value">Sample deprt.</td>
                                            </tr>
                                             <tr>
                                                <td class="process-title">Cont. Person</td>
                                                 <td class="process-valuemid">:</td>
                                                <td class="process-value"><?php echo $samplelogin_datas['contactname']; ?></td>
                                            </tr>
                                            <tr>
                                                <td class="process-title">Cont. No</td>
                                                 <td class="process-valuemid">:</td>
                                                <td class="process-value"><?php echo $samplelogin_datas['mobile']; ?></td>
                                            </tr>
            </tbody>
  </table>
        </td>
         <td width="2%" style="border:0px "></td>
          <td width="52%" style="border:1px solid black;">
            <table class="table" width="100%">
                                        <tbody>
                                          <tr>
                                                <td class="process-title">M.I. Ref.No</td>
                                                 <td class="process-valuemid">:</td>
                                                <td class="process-value"><?php echo $miDetails['bom_ref_no']; ?></td>
                                            </tr>
                                             <tr>
                                                <td class="process-title">Date & Time</td>
                                                 <td class="process-valuemid">:</td>
                                                <td class="process-value"><?php echo $miDetails['bom_req_date']; ?></td>
                                            </tr>
                                             <tr>
                                                <td class="process-title">Cutoff Date & Time</td>
                                                 <td class="process-valuemid">:</td>
                                                <td class="process-value"><?php echo $miDetails['bom_cutoff_date'] ?></td>
                                            </tr>
                                            <tr>
                                                <td class="process-title">Req. Type</td>
                                                 <td class="process-valuemid">:</td>
                                                <td class="process-value"><?php echo $miDetails['type']; ?></td>
                                            </tr>
                                             <tr>
                                                <td class="process-title">WIP No</td>
                                                 <td class="process-valuemid">:</td>
                                                <td class="process-value"><?php echo $company_data['ArrEnquiryDetails']['isriorcode']; ?></td>
                                            </tr>
                                            
                                             
                                             
                                             
                                           
                                          </tbody>
                                          </table>
          </td>
      </tr>
      </table>
      <table " width="100%"  >
       
         <td align="left" style="border-bottom:1px solid font-size:20px; padding-bottom: 5px;padding-top: 5px;"><b> Material Indent Details:</b></td>
         </tr>
       </table>
     <table class="details"  width="100%" style="table-layout: fixed;" >
  <thead>
    <tr style="height:50px"  width="100%" >
      <th width="3%">S.No.</th>
      <th width="14%">Item Description</th>
      <th width="16%">Blend (%) / Content / Material  </th>
      <th width="7%">Garment Size</th>
      <th width="13%">Item Code </th>
      <th width="13%">Item Colour Code </th>
      <th width="7%">Size / Dim (L*W*H)</th>
      <th width="5%">UOM</th>
      <th width="6%">Indent Qty.</th>
      <th width="5%">UOM</th>
      <th width="6%">Issued Qty.</th>
       <th width = "5%" >UOM</th>
        
    </tr>
  </thead>
  <tbody >
    
 <?php if (!empty($mi_data['bom_mi_tbl_data'])): ?>
    <?php $i = 1; ?>
    <?php foreach ($mi_data['bom_mi_tbl_data'] as $outer): ?>
        <?php foreach ($outer as $row):    if($row[14]=='PENDING') {
             $status_date ='-';
        }else{
            $status_date = $row[15];
        }
            ?>
            <tr width="100%" style="padding-top: 5px !important;padding-bottom: 5px !important">
                <td style="text-align:center;"><?php echo $i++; ?></td>
                <td style="padding-left: 4px;padding-bottom: 5px;padding-top: 5px"><?php echo htmlspecialchars($row[3]); ?></td>
                <td style="padding-left: 4px;padding-bottom: 5px;padding-top: 5px"><?php echo htmlspecialchars($row[4]); ?></td>
                <td style="padding-left: 4px;padding-bottom: 5px;padding-top: 5px"><?php echo htmlspecialchars($row[5]); ?></td>
                <td style="padding-left: 4px;padding-bottom: 5px;padding-top: 5px"><?php echo htmlspecialchars($row[6]); ?></td>
                <td style="padding-left: 4px;padding-bottom: 5px;padding-top: 5px"><?php echo htmlspecialchars($row[7]); ?></td>
                <td style="padding-left: 4px;padding-bottom: 5px;padding-top: 5px"><?php echo htmlspecialchars($row[8]); ?></td>
                <td style="text-align:center;padding-left: 4px;padding-bottom: 5px;padding-top: 5px"><?php echo htmlspecialchars($row[9]); ?></td>
                <td style="text-align:right;padding-right: 4px;padding-bottom: 5px;padding-top: 5px"><?php echo htmlspecialchars($row[10]); ?></td>
                <td style="text-align:center;"><?php echo htmlspecialchars($row[11]); ?></td>
                <td style="text-align:right;padding-right: 4px;padding-bottom: 5px;padding-top: 5px"><?php echo htmlspecialchars($row[12]); ?></td>
                <td style="text-align:center;"><?php echo htmlspecialchars($row[13]); ?></td>
                
                
            </tr>
        <?php endforeach; ?>
    <?php endforeach; ?>
<?php else: ?>
    <tr><td colspan="15">No data found</td></tr>
<?php endif; ?>

        </tbody>
</table>
 

<table width="100%" style="border-collapse: collapse; border: none; border-right: 1px solid black; border-left: 1px solid black;">
 
  <tr>
    
    <td width="100%" height="30" align="right" style=" padding-top: 10px; padding-bottom: 10px;padding-right:5px;">
      For<b> Azibo Infotech Private Limited</b>
    </td>
  </tr>
</table>
<table width="100%" height="50"  style="border-collapse:collapse;border-bottom:1px solid; solid black;border-right:1px solid black;border-left:1px solid black;">
 
<tr>
          <td width="50" height="15" align="left" style="padding-left:10px"><?php echo $company_data['merchantName']; ?></td>
          <td width="50" height="15" align="left" style=""><?php echo $company_data['ArrMgmt']['contactname']; ?></td>
          
          <td width="50" height="15" align="left"><?php echo $company_data['merchantName']; ?></td>
        <td width="50" height="15" align="right"></td>
        
        </tr>

        <tr>
          <td width="50" height="10" align="left" style="padding-left:10px;padding-bottom: 5px;"><b>Request Raised By</b></td>
          <td width="50" height="10" align="left" style="padding-bottom: 5px;" ><b>Request Authorized By</b></td>
           <td width="50" height="10" align="left"  style="padding-bottom: 5px;" ><b>P.I. Approved By</b></td>
          <td width="50" height="10" align="right" style="padding-right:5px;padding-bottom: 5px;" >Authorized Signatory</td>
        </tr>
        </table>


      </table>
      
</body>
</html>
 