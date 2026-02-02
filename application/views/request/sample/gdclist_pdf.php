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
error_reporting(0);
//print_r($pi_data); exit;
$requestData = $requestData[0];
$company_datas = $company_data[0];  
$miDetails = $miDetails[0];
$itemDetails = $itemDetails[0];
$ArrProfileInfo = fnGetUserLoggedInfo(1);
$subcompany_datas = $subcompany_data[0]; 

if($miDetails['item_received_status']=="0") {
  $item_received_status = "PENDING"; 
} else if($miDetails['item_received_status']=="1") {
    $item_received_status = "RECEIVED";
} else if($miDetails['item_received_status']=="2") {
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

       <table  width="100%"> 
 <tr>
        <td align="" style="border:1px solid black;padding:5px;">
           <b style="font-size:18px;" ><?php echo $subcompany_datas['companyname'];?> </b>
           <span style="float:right;"> <?php echo $subcompany_datas['address'];?>, <?php echo $subcompany_datas['city'];?> - <?php echo $subcompany_datas['pincode'];?>.
           <?php echo $subcompany_datas['state'];?>, <?php echo $subcompany_datas['country'];?>
        </span></br>  
           <span style="float:right;">e-mail ID: <?php echo $subcompany_datas['email_id']?>, Mobile: <?php echo $subcompany_datas['mobile_no'];?></span></br></br>
           <span style="float:right;"> GST No: <?php echo $subcompany_datas['gst_no']?> /  IE Code: <?php echo $subcompany_datas['IECODE']?> </span></br></td>
         </tr>
        
</table>
<table style="margin-top:2px" border="0" width="100%">
        <tr>
        <td width="50%" style="padding-bottom:0px"><b>From</b>  </td>
        <td width="50%" style="padding-bottom:0px" align="center"> <b>  G.D.C. REFERENCE <b> </td>
        </tr>
</table>

<table width="100%"  class="table">
    <tr>
        <td width="48%" >
  <table class="table" width="100%" style="border:1px solid black;">
      <tbody>
                                          <tr>
                                                <td class="process-title">Dept. Name</td>
                                                 <td class="process-valuemid">:</td>
                                                <td class="process-value">SAMPLE DEPT.</td>
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
                                                <td class="process-value">MERCHANT DEPT.</td>
                                            </tr>
                                             <tr>
                                                <td class="process-title">Cont. Person</td>
                                                 <td class="process-valuemid">:</td>
                                                <td class="process-value"><?php echo @$ArrCommonHeaderData['merchantName']; ?></td>
                                            </tr>
                                            <tr>
                                                <td class="process-title">Cont. No</td>
                                                 <td class="process-valuemid">:</td>
                                                <td class="process-value"><?php echo @$ArrCommonHeaderData['merchantMobile'] ?></td>
                                            </tr>
            </tbody>
  </table>

        </td>
         <td width="2%" style="border:0px "></td>
          <td width="50%" style="border:1px solid black;">
            <table class="table" width="100%">
                                        <tbody>
                                          <tr>
                                                <td class="process-title">G.D.C. No</td>
                                                 <td class="process-valuemid">:</td>
                                                <td class="process-value"><?php echo $miDetails['dc_ref_queue_no']; ?></td>
                                            </tr>
                                             <tr>
                                                <td class="process-title">Date & Time</td>
                                                 <td class="process-valuemid">:</td>
                                                <td class="process-value"><?php echo $miDetails['dc_dt']; ?></td>
                                            </tr>
                                             <tr>
                                                <td class="process-title">Cutoff Date & Time</td>
                                                 <td class="process-valuemid">:</td>
                                                <td class="process-value"><?php echo $miDetails['cutoff_date'] ?></td>
                                            </tr>
                                             <tr>
                                                <td class="process-title">WIP No</td>
                                                 <td class="process-valuemid">:</td>
                                                <td class="process-value"><?php echo $ArrCommonHeaderData['ArrEnquiryDetails']['isriorcode']; ?></td>
                                            </tr>
                                            <tr>
                                                <td class="process-title">Queue No</td>
                                                 <td class="process-valuemid">:</td>
                                                <td class="process-value"><?php echo @$miDetails['ref_queue_no'];?></td>
                                            </tr>
                                            <tr>
                                                <td class="process-title">Sample Queue No</td>
                                                 <td class="process-valuemid">:</td>
                                                <td class="process-value"><?php echo $miDetails['sam_queue_no']; ?></td>
                                            </tr>
                                             <tr>
                                                <td class="process-title">Item Reced. Status</td>
                                                 <td class="process-valuemid">:</td>
                                                <td class="process-value"><?php echo $item_received_status; ?> &nbsp;<?php echo @$miDetails['item_sta_upt_dt']; ?></td>
                                            </tr>
                                           
                                          </tbody>
                                          </table>
          </td>
      </tr>
      </table>
      <table cellspacing="0" cellpadding="3" border="0" width="100%"  >
       
         <td align="left" style="border-bottom:1px solid font-size:20px; padding-bottom: 5px;padding-top: 5px;"><b> Material Issued Details:</b></td>
         </tr>
       </table>
     <table class="details" cellspacing="0" cellpadding="4"  >
  <thead>
    <tr>
      <th width="5%">S.No.</th>
      <th width="10%">P.O. No.</th>
      <th width="10%">Combo</th>
    
      <th width="10%">Component</th>
        <th width="10%">Colour</th>
      <th width="10%">Size Spec Code</th>
    
      <th width="10%">Item Issued </th>
        <th width="15%">Sample Ref. No.</th>
      <th width="10%">Issued Size(s)</th>
      <th width="10%">Total No. of Parts Issued</th>
    </tr>
  </thead>
  <tbody >
    <!-- <pre><?php print_r($sample_data); ?></pre> -->
      <?php if (!empty($sample_data['data'])): ?>
    <?php $i = 1; // counter starts from 1 ?>
    <?php foreach ($sample_data['data'] as $row): ?>
        <tr>
            <td style="text-align:center;"><?php echo $i++; ?></td>
            <td style="padding-left: 4px" ><?php echo htmlspecialchars($row[1]); ?></td>
            <td style="padding-left: 4px" ><?php echo htmlspecialchars($row[2]); ?></td>
            <td style="padding-left: 4px" ><?php echo htmlspecialchars($row[3]); ?></td>
            <td style="padding-left: 4px" ><?php echo htmlspecialchars($row[4]); ?></td>
            <td style="padding-left: 4px" ><?php echo htmlspecialchars($row[5]); ?></td>
            <td style="padding-left: 4px" ><?php echo htmlspecialchars($row[6]); ?></td>
            <td style="padding-left: 4px" ><?php echo htmlspecialchars($row[7]); ?></td>
            <td style="padding-left: 4px" ><?php echo htmlspecialchars($row[8]); ?></td>
            <td style="text-align:right;padding-right: 4px"><?php echo htmlspecialchars($row[9]); ?></td>
     
        </tr>
    <?php endforeach; ?>
<?php else: ?>
    <tr><td colspan="11">No data found</td></tr>
<?php endif; ?>

        </tbody>
</table>

<table width="100%" height="108" align="center" style="border: none; border-collapse:collapse;border-right:1px solid black;border-left:1px solid black;">

        <tr>
          <td width="150" height="20"></td>
          <!-- <td width="600" align="right" colspan="3" style="font-size:14px;" ><b>For <?php echo $company_datas['companyname']; ?></b>&nbsp;&nbsp;</td> -->
       <td width="600" align="right" colspan="3" style="font-size:12px;padding-right: 5px" height="50">For<b> Azibo Infotech Private Limited</b></td>
       
       

      
    
</table>
<table width="100%" height="50"  style="border-collapse:collapse;border-bottom:1px solid; solid black;border-right:1px solid black;border-left:1px solid black;">
<tr>
             <td width="100" height="10" align="left" style="padding-left: 15px"><?php echo @$ArrCommonHeaderData['merchantName'] ?></td>
          <td width="100" height="10" align="left" style=""><?php echo @$ArrCommonHeaderData['ArrMgmt']['contactname'] ?></td>
          <td width="100" height="10" align="left" style=""><?php echo @$requestData['dc_received_by']; ?></td>
          <td width="170" height="10" align="right"></td>
        
        </tr>

        <tr>
          <td width="100" height="10" align="left" style="padding-left: 15px;padding-bottom: 5px"><b>Request Raised By</td>
          <td width="100" height="10" align="left" style="padding-bottom: 5px" ><b>Request Authorized By</b></td>
          <td width="100" height="10" align="left" style="padding-bottom: 5px"><b>P.I. Approved By</b></td>
         <td width="170" height="10" align="right" style="padding-right: 5px;padding-bottom: 5px ">Authorized Signatory</td>
        </tr>
        </table>

      </table>
      
</body>
</html>