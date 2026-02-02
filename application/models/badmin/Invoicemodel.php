<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
class Invoicemodel extends CI_Model {
    function fnGetInfo($VarData, $VarStatus, $VarId = '',$VarCompanyId='') {
        $this->db->select('se.*,DATE_FORMAT(se.reqdatetime,\'%d-%m-%Y\ %H:%i %p\') as reqdatetime,DATE_FORMAT(se.status_updated_datetime,\'%d-%m-%Y\ %H:%i %p\') as status_updated_datetime,u.contactname as request_raised_by,bau.contactname as status_updatedby')->from(KN_SUBSCRIBERENQUIRY . ' AS se');
        $this->db->join(KN_USERS . ' AS u', 'u.id = se.mrkt_dept_userid','LEFT');
        $this->db->join(KN_USERS . ' AS bau', 'bau.id = se.badmin_userid','LEFT');
        $this->db->join(KN_PROFORMAINVOICE . ' AS pi', 'pi.subscriber_id = se.id','LEFT');
        if ($VarData <> '') {
            $ArrWhere['companyname'] = trim($VarData);
        }
        // if ($VarStatus <> '') {
        //     $this->db->where_in('status', array($VarStatus));
        // } else {
        //     $this->db->where_in('status', array(1, 2));
        // }
        if ($VarId <> '') {
            $ArrWhere['pi.id'] = $VarId;
        }
        if ($VarCompanyId <> '') {
            $ArrWhere['companyid'] = $VarCompanyId;
        }
        
        if (!empty($ArrWhere)) {
            $this->db->where($ArrWhere);
        }
        return $this->db->get()->result();
    }
    function fnGetInvoiceInfo($VarData, $VarStatus, $VarId = '',$VarCompanyId='') {
        $this->db->select('se.*,pi.id as pid,pi.subscriber_id,pi.mobile_no as invomobile_no,pi.package_id as invopackageid,pi.remarks as invoremarks,pi.email_id as invoemail_id,pi.designation as invodesignation,pi.contactperson as invocontactperson,pi.businesstype as invobusinesstype,pi.purchasetype as invopurchasetype,pi.proforma_type,pi.additional_users as invo_additional_users,pi.data_storage_limit as invo_data_storage_limit,pi.file_storage_limit as invo_file_storage_limit,pi.companyname as invocmpnyname,pi.address as invoaddress,pi.city as invocity,pi.state as invostate,pi.pincode as invopincode,pi.gst_no as invogst_no,pi.IECODE as ie_code,pi.country as invocountry,pi.invoice_refno,pi.invoice_type,pi.save_status,pi.paymentstatus,pi.payment_from,pi.transaction_no,DATE_FORMAT(pi.transaction_date,\'%d-%m-%Y\')as transaction_date,pi.payment_mode,pi.transaction_value,pi.invoice_validity,pi.terms_and_condition,pi.total,pi.subtotal,pi.cgst_amount,pi.igst_amount,pi.sgst_amount,pi.subscription_period,DATE_FORMAT(pi.invoice_datetime,\'%d-%m-%Y\') as invoice_datetime,DATE_FORMAT(se.reqdatetime,\'%d-%m-%Y\ %H:%i %p\') as reqdatetime,DATE_FORMAT(se.status_updated_datetime,\'%d-%m-%Y\ %H:%i %p\') as status_updated_datetime,u.contactname as request_raised_by,bau.contactname as status_updatedby')->from(KN_SUBSCRIBERENQUIRY . ' AS se');
        $this->db->join(KN_USERS . ' AS u', 'u.id = se.mrkt_dept_userid','LEFT');
        $this->db->join(KN_USERS . ' AS bau', 'bau.id = se.badmin_userid','LEFT');
        $this->db->join(KN_PROFORMAINVOICE . ' AS pi', 'pi.subscriber_id = se.id','LEFT');
        if ($VarData <> '') {
            $ArrWhere['companyname'] = trim($VarData);
        }
        // if ($VarStatus <> '') {
        //     $this->db->where_in('status', array($VarStatus));
        // } else {
        //     $this->db->where_in('status', array(1, 2));
        // }
        if ($VarId <> '') {
            $ArrWhere['pi.id'] = $VarId;
        }
        if ($VarCompanyId <> '') {
            $ArrWhere['companyid'] = $VarCompanyId;
        }
        
        if (!empty($ArrWhere)) {
            $this->db->where($ArrWhere);
        }
      // echo $this->db->get_compiled_select();die;
        return $this->db->get()->result();
    }

    function fnCount($Varcmpny,$Varpckdetid,$Varpurchtype,$Varreqraisedby,$Varfromdate,$Vartodate) {
        $ArrWhere = array();
        if ($Varcmpny <> '') {
            $ArrWhere[] = "se.companyname=" . "'$Varcmpny'";
        }
        if($Varfromdate != '' && $Vartodate != '')
        {
            $startDate = $this->changeReverseDate($Varfromdate);
            $endDate = $this->changeReverseDate($Vartodate);
            $startDate = $startDate. ' ' . '00:00:00';
            $endDate = $endDate. ' ' . '23:59:59';
            
             $ArrWhere[] = "pi.invoice_datetime >=" . "'$startDate'";
             $ArrWhere[] = "pi.invoice_datetime <=" . "'$endDate'";
        }
        else if($Varfromdate != '')
        {
            $startDate = $this->changeReverseDate($Varfromdate);
            $startDate = $startDate;
            
             $ArrWhere[] = "DATE_FORMAT(pi.invoice_datetime,\"%Y-%m-%d\") =" . "'$startDate'";
        }
        else if($Vartodate != '')
        {
              $endDate = $this->changeReverseDate($Vartodate);
              $endDate = $endDate;
              $ArrWhere[] = "DATE_FORMAT(pi.invoice_datetime,\"%Y-%m-%d\") =" . "'$endDate'";
        }
        if ($Varpckdetid <> '') {
            $ArrWhere[] = "se.package_id=" . "'$Varpckdetid'";
        }
        if ($Varpurchtype <> '') {
            $ArrWhere[] = "se.purchasetype=" . "'$Varpurchtype'";
        }
        if ($Varreqraisedby <> '') {
            $ArrWhere[] = "u.contactname=" . "'$Varreqraisedby'";
        } 
        
        $ArrUserLoggedInfo = fnGetUserLoggedInfo('1');
        $ArrWhere[] = "se.requeststatus=2";
        $VarWhere = '';
        if (!empty($ArrWhere)) {
            $VarWhere = 'WHERE ' . implode(" and ", $ArrWhere);
        }
        
        $VarSqlLab = "SELECT count(1) as trec  FROM " . KN_PROFORMAINVOICE . " AS pi LEFT JOIN  ".KN_SUBSCRIBERENQUIRY." As se ON pi.subscriber_id=se.id LEFT JOIN " . KN_USERS . " AS u ON se.mrkt_dept_userid = u.id " . $VarWhere;
        $ObjRows = $this->db->query($VarSqlLab)->row();
        return $ObjRows->trec;
    }
    function fnList($Varcmpny,$Varpckdetid,$Varpurchtype,$Varreqraisedby,$Varfromdate,$Vartodate,$VarLimit = 10, $offset = 0, $VarSortBy, $VarSortOrder) {
        $VarSortOrder = ($VarSortOrder == 'desc') ? 'desc' : 'asc';
        $VarSortCols = array('se.companyname', 'pi.status', 'pi.id');
       // $VarSortBy = (in_array($VarSortBy, $VarSortCols)) ? $VarSortBy : 'pi.id';
         $VarSortBy = (in_array($VarSortBy, $VarSortCols)) ? $VarSortBy : 'pi.log';
        $VarLimitInfo = $VarLimit;
        if ($offset >= 1) {
            $VarLimitInfo = $offset . "," . $VarLimit;
        }
        $ArrWhere = array();
        if ($Varcmpny <> '') {
            $ArrWhere[] = "se.companyname=" . "'$Varcmpny'";
        }if($Varfromdate != '' && $Vartodate != '')
        {
            $startDate = $this->changeReverseDate($Varfromdate);
            $endDate = $this->changeReverseDate($Vartodate);
            $startDate = $startDate. ' ' . '00:00:00';
            $endDate = $endDate. ' ' . '23:59:59';
            
             $ArrWhere[] = "pi.invoice_datetime >=" . "'$startDate'";
             $ArrWhere[] = "pi.invoice_datetime <=" . "'$endDate'";
        }
        else if($Varfromdate != '')
        {
            $startDate = $this->changeReverseDate($Varfromdate);
            $startDate = $startDate;
            
             $ArrWhere[] = "DATE_FORMAT(pi.invoice_datetime,\"%Y-%m-%d\") =" . "'$startDate'";
        }
        else if($Vartodate != '')
        {
              $endDate = $this->changeReverseDate($Vartodate);
              $endDate = $endDate;
              $ArrWhere[] = "DATE_FORMAT(pi.invoice_datetime,\"%Y-%m-%d\") =" . "'$endDate'";
        }
        
        if ($Varpckdetid <> '') {
            $ArrWhere[] = "se.package_id=" . "'$Varpckdetid'";
        }
        if ($Varpurchtype <> '') {
            $ArrWhere[] = "se.purchasetype=" . "'$Varpurchtype'";
        }
        if ($Varreqraisedby <> '') {
            $ArrWhere[] = "u.contactname=" . "'$Varreqraisedby'";
        } 
        
        $ArrUserLoggedInfo = fnGetUserLoggedInfo('1');
        $ArrWhere[] = "se.requeststatus = 2 AND pi.invoice_status=1";
        $VarWhere = '';
        if (!empty($ArrWhere)) {
            $VarWhere = 'WHERE ' . implode(" and ", $ArrWhere);
        }
        
         $VarSqlLab = "SELECT pi.id,se.companyname,pi.package_id,pi.purchasetype,u.contactname as request_raised_by,pi.invoice_refno,DATE_FORMAT(se.reqdatetime,\"%d/%m/%Y %h:%i %p\") as reqdatetime,DATE_FORMAT(pi.invoice_datetime,\"%d/%m/%Y %h:%i %p\") as invoice_datetime,pi.paymentstatus,DATE_FORMAT(pi.log,\"%d/%m/%Y %h:%i %p\") as recent_updated_datetime,pi.status
                      FROM " . KN_PROFORMAINVOICE . " AS pi LEFT JOIN  ".KN_SUBSCRIBERENQUIRY." As se ON pi.subscriber_id=se.id LEFT JOIN " . KN_USERS . " AS u ON se.mrkt_dept_userid = u.id " . $VarWhere . " order by " . $VarSortBy . " " . $VarSortOrder;
                    $ObjResult = $this->db->query($VarSqlLab);
                    return $ObjResult;
    }
    
    public function getQuotedetailsGrid_cgst_sgst($enqId,$invotype)
    {
        
        $details = $this->db->select('id,description as name')->get_where(KN_MASTER_DETAILS, array('status' => 1))->result_array();
        $description = $this->db->select('id,description as name')->get_where(KN_MASTER_DESCRIPTION, array('status' => 1))->result_array();
        $unit_rate = $this->db->select('description as id,description as name')->get_where(KN_MASTER_UNITRATE, array('status' => 1))->result_array(); // because need to save it's value
        $results = [
            'column' => [
                ['title' => 'Description', 'width' => '28%', 'type' => 'dropdown','source'=>$description,'align' => 'left'],
                ['title' => 'Details', 'width' => '20%', 'type' => 'dropdown','source'=>$details,'align' => 'left'],
                ['title' => 'Unit Rate'. PHP_EOL . '(Rs)', 'width' => '9%', 'type' => 'dropdown','source'=>$unit_rate,'align' => 'center'],
                ['title' => 'Qty.' . PHP_EOL . '(Nos)', 'width' => '5%',  'align' => 'center'],
                ['title' => 'Amount' . PHP_EOL . '(Rs)', 'width' => '8%',  'align' => 'right','readOnly'=> true],
                ['title' => 'SGST' . PHP_EOL . '(%)', 'width' => '4%',  'align' => 'center'],
                ['title' => 'CGST' . PHP_EOL . '(%)', 'width' => '4%',  'align' => 'center'],
                ['title' => 'SGST Value' . PHP_EOL . '(Rs)', 'width' => '8%',  'align' => 'right','readOnly'=> true],
                ['title' => 'CGST Value' . PHP_EOL . '(Rs)', 'width' => '8%',  'align' => 'right','readOnly'=> true],
                ['title' => 'Sub Total' . PHP_EOL . '(Rs)', 'width' => '10%',  'align' => 'right','readOnly'=> true]
            ],
            'data'   => $this->getQuotedetailsData($enqId,$invotype)
        ];

        // echo json_encode($results);
        // exit();
        return $results;
    }
    public function getQuotedetailsGrid_igst($enqId,$invotype)
    {
        
        $details = $this->db->select('id,description as name')->get_where(KN_MASTER_DETAILS, array('status' => 1))->result_array();
        $description = $this->db->select('id,description as name')->get_where(KN_MASTER_DESCRIPTION, array('status' => 1))->result_array();
        $unit_rate = $this->db->select('description as id,description as name')->get_where(KN_MASTER_UNITRATE, array('status' => 1))->result_array(); // because need to save it's value
        $results = [
            'column' => [
                ['title' => 'Description', 'width' => '28%', 'type' => 'dropdown','source'=>$description,'align' => 'left'],
                ['title' => 'Details', 'width' => '23%', 'type' => 'dropdown','source'=>$details,'align' => 'left'],
                ['title' => 'Unit Rate'. PHP_EOL . '(Rs)', 'width' => '10%', 'type' => 'dropdown','source'=>$unit_rate,'align' => 'center'],
                ['title' => 'Qty.' . PHP_EOL . '(Nos)', 'width' => '8%',  'align' => 'center'],
                ['title' => 'Amount' . PHP_EOL . '(Rs)', 'width' => '8%',  'align' => 'right','readOnly'=> true],
                ['title' => 'IGST' . PHP_EOL . '(%)', 'width' => '5%',  'align' => 'center'],
                ['title' => 'IGST Value' . PHP_EOL . '(Rs)', 'width' => '8%',  'align' => 'right','readOnly'=> true],
                ['title' => 'Sub Total' . PHP_EOL . '(Rs)', 'width' => '10%',  'align' => 'right','readOnly'=> true]
            ],
            'data'   => $this->getQuotedetailsDataigst($enqId,$invotype)
        ];

        // echo json_encode($results);
        // exit();
        return $results;
    }
     function getQuotedetailsData($enqId,$invotype)
    {
        
        $query  = "SELECT pid.description_id,pid.detail_id,pid.unit_id,pid.qty,pid.amount,pid.sgst_percent,pid.cgst_percent
                     FROM " . KN_PROFORMAINVOICE . " AS pi
                     LEFT JOIN " . KN_PROFORMAINVOICEDET . " AS pid ON pid.proforma_id=pi.id
                     LEFT JOIN " . KN_SUBSCRIBERENQUIRY . " AS se ON se.id=pi.subscriber_id
                     WHERE pi.invoice_type='$invotype' and se.id = " . $enqId;
        $data   = $this->db->query($query)->result_array();
        $result = [];
        foreach ($data as $key => $value)
        {
            $result[$key] = [$value['description_id'], $value['detail_id'], $value['unit_id'], $value['qty'], $value['amount'],$value['sgst_percent'],$value['cgst_percent']];
        }
        
        return $result;
    }
     function getQuotedetailsDataigst($enqId,$invotype)
    {
        
        $query  = "SELECT pid.description_id,pid.detail_id,pid.unit_id,pid.qty,pid.amount,pid.igst_percent
                     FROM " . KN_PROFORMAINVOICE . " AS pi
                     LEFT JOIN " . KN_PROFORMAINVOICEDET . " AS pid ON pid.proforma_id=pi.id
                     LEFT JOIN " . KN_SUBSCRIBERENQUIRY . " AS se ON se.id=pi.subscriber_id
                     WHERE pi.invoice_type='$invotype' and se.id = " . $enqId;
        $data   = $this->db->query($query)->result_array();
        $result = [];
        foreach ($data as $key => $value)
        {
            $result[$key] = [$value['description_id'], $value['detail_id'], $value['unit_id'], $value['qty'], $value['amount'],$value['igst_percent']];
        }
        
        return $result;
    }
    
    function saveproformadraftinfo($ArrSaveData,$ArrSavedetaildata) {
        $VarId = $ArrSaveData['id'];
        $invotype = $ArrSaveData['invoice_type'];
      // var_dump($ArrSaveData);die;
        $sgstpercent='0.00';
        $cgstpercent='0.00';
        $igstpercent='0.00';
        $igstvalue='0.00';
        $cgstvalue='0.00';
        $sgstvalue='0.00';
        $total='0.00';
        $subtotal='0.00';
        $ArrResult = [];
        if ($VarId == "") {
            unset($ArrSaveData['id']);
             if ($this->db->insert(KN_PROFORMAINVOICE, $ArrSaveData)){
                $VarInsId = $this->db->insert_id();
                if(!empty($VarInsId)){
                    foreach ($ArrSavedetaildata as $key => $value)
                    {   
                        if($invotype=='within'){
                            $total +=$value[4];
                            $sgstpercent +=!empty($value[5])?$value[5]:'0.00';
                            $cgstpercent +=!empty($value[6])?$value[6]:'0.00';
                            $sgstvalue +=$value[7];
                            $cgstvalue +=$value[8];
                            $subtotal +=$value[9];
                            
                            $this->db->insert(KN_PROFORMAINVOICEDET, [
                            'proforma_id' => $VarInsId,
                            'description_id' => $value[0],
                            'detail_id' => $value[1],
                            'unit_id' => $value[2],
                            'qty' => $value[3],
                            'amount' => $value[4],
                            'sgst_percent' => $value[5],
                            'cgst_percent' => $value[6],
                            'sgst_amount' => $value[7],
                            'cgst_amount' => $value[8],
                            'subtotal' => $value[9],
                        ]);
                        }else{
                            $total +=$value[4];
                            $igstpercent +=!empty($value[5])?$value[5]:'0.00';
                            $igstvalue +=$value[6];
                            $subtotal +=$value[7];
                            
                            $this->db->insert(KN_PROFORMAINVOICEDET, [
                            'proforma_id' => $VarInsId,
                            'description_id' => $value[0],
                            'detail_id' => $value[1],
                            'unit_id' => $value[2],
                            'qty' => $value[3],
                            'amount' => $value[4],
                            'igst_percent' => $value[5],
                            'igst_amount' => $value[6],
                            'subtotal' => $value[7],
                        ]);
                        }
                        
                    }
                    $this->db->update(KN_PROFORMAINVOICE, [
                            'sgst_percent' => $sgstpercent,
                            'cgst_percent' => $cgstpercent,
                            'sgst_amount' => $sgstvalue,
                            'cgst_amount' => $cgstvalue,
                            'igst_percent' => $igstpercent,
                            'igst_amount' => $igstvalue,
                            'total' => $total,
                            'subtotal' => $subtotal,
                        ],['id' => $VarInsId]);
                }
                $ArrResult['errcode'] = 1;
                $ArrResult['msg'] = '';
                $ArrResult['id'] = $VarInsId;
                $ArrResult['eid'] = urlencode(base64_encode($VarInsId));
             }  else {
                $ArrResult['errcode'] = -1;
                $ArrResult['msg'] = 'Invalid Data';
            }
            
        } else {
            if ($this->db->update(KN_PROFORMAINVOICE, $ArrSaveData, array('id' => $VarId))) {
                if($this->db->delete(KN_PROFORMAINVOICEDET, array('proforma_id' => $VarId))){
                    foreach ($ArrSavedetaildata as $key => $value)
                    {   
                        if($invotype=='within'){
                            $total +=$value[4];
                            $sgstpercent +=!empty($value[5])?$value[5]:'0.00';
                            $cgstpercent +=!empty($value[6])?$value[6]:'0.00';
                            $sgstvalue +=$value[7];
                            $cgstvalue +=$value[8];
                            $subtotal +=$value[9];
                            
                            $this->db->insert(KN_PROFORMAINVOICEDET, [
                            'proforma_id' => $VarId,
                            'description_id' => $value[0],
                            'detail_id' => $value[1],
                            'unit_id' => $value[2],
                            'qty' => $value[3],
                            'amount' => $value[4],
                            'sgst_percent' => $value[5],
                            'cgst_percent' => $value[6],
                            'sgst_amount' => $value[7],
                            'cgst_amount' => $value[8],
                            'subtotal' => $value[9],
                        ]);
                        }else{
                            $total +=$value[4];
                            $igstpercent +=!empty($value[5])?$value[5]:'0.00';
                            $igstvalue +=$value[6];
                            $subtotal +=$value[7];
                            
                            $this->db->insert(KN_PROFORMAINVOICEDET, [
                            'proforma_id' => $VarId,
                            'description_id' => $value[0],
                            'detail_id' => $value[1],
                            'unit_id' => $value[2],
                            'qty' => $value[3],
                            'amount' => $value[4],
                            'igst_percent' => $value[5],
                            'igst_amount' => $value[6],
                            'subtotal' => $value[7],
                        ]);
                        }
                        
                    }
                     $this->db->update(KN_PROFORMAINVOICE, [
                            'sgst_percent' => $sgstpercent,
                            'cgst_percent' => $cgstpercent,
                            'sgst_amount' => $sgstvalue,
                            'cgst_amount' => $cgstvalue,
                            'igst_percent' => $igstpercent,
                            'igst_amount' => $igstvalue,
                            'total' => $total,
                            'subtotal' => $subtotal,
                        ],['id' => $VarId]);
                }
                 
                $ArrResult['errcode'] = 1;
                $ArrResult['msg'] = '';
                $ArrResult['id'] = $VarId;
                $ArrResult['eid'] = urlencode(base64_encode($VarId));
            } else {
                $ArrResult['errcode'] = -1;
                $ArrResult['msg'] = 'Invalid Data';
            }

        }
        return $ArrResult;
    }
    function saveproformainfo($ArrSaveData,$ArrSavedetaildata) {
        $VarId = $ArrSaveData['id'];
        $invotype = $ArrSaveData['invoice_type'];
        $subscriberid= $ArrSaveData['subscriber_id'];
        $ArrUserLoggedInfo = fnGetUserLoggedInfo('1');
        $status_updated_by = $ArrUserLoggedInfo['id'];
      // var_dump($ArrSaveData);die;
        $sgstpercent='0.00';
        $cgstpercent='0.00';
        $igstpercent='0.00';
        $igstvalue='0.00';
        $cgstvalue='0.00';
        $sgstvalue='0.00';
        $total='0.00';
        $subtotal='0.00';
        $ArrResult = [];
        if ($VarId == "") {
            unset($ArrSaveData['id']);
             if ($this->db->insert(KN_PROFORMAINVOICE, $ArrSaveData)){
                $VarInsId = $this->db->insert_id();
                if(!empty($VarInsId)){
                    foreach ($ArrSavedetaildata as $key => $value)
                    {   
                        if($invotype=='within'){
                            $total +=$value[4];
                            $sgstpercent +=!empty($value[5])?$value[5]:'0.00';
                            $cgstpercent +=!empty($value[6])?$value[6]:'0.00';
                            $sgstvalue +=$value[7];
                            $cgstvalue +=$value[8];
                            $subtotal +=$value[9];
                            
                            $this->db->insert(KN_PROFORMAINVOICEDET, [
                            'proforma_id' => $VarInsId,
                            'description_id' => $value[0],
                            'detail_id' => $value[1],
                            'unit_id' => $value[2],
                            'qty' => $value[3],
                            'amount' => $value[4],
                            'sgst_percent' => $value[5],
                            'cgst_percent' => $value[6],
                            'sgst_amount' => $value[7],
                            'cgst_amount' => $value[8],
                            'subtotal' => $value[9],
                        ]);
                        }else{
                            $total +=$value[4];
                            $igstpercent +=!empty($value[5])?$value[5]:'0.00';
                            $igstvalue +=$value[6];
                            $subtotal +=$value[7];
                            
                            $this->db->insert(KN_PROFORMAINVOICEDET, [
                            'proforma_id' => $VarInsId,
                            'description_id' => $value[0],
                            'detail_id' => $value[1],
                            'unit_id' => $value[2],
                            'qty' => $value[3],
                            'amount' => $value[4],
                            'igst_percent' => $value[5],
                            'igst_amount' => $value[6],
                            'subtotal' => $value[7],
                        ]);
                        }
                        
                    }
                    $this->db->update(KN_PROFORMAINVOICE, [
                            'sgst_percent' => $sgstpercent,
                            'cgst_percent' => $cgstpercent,
                            'sgst_amount' => $sgstvalue,
                            'cgst_amount' => $cgstvalue,
                            'igst_percent' => $igstpercent,
                            'igst_amount' => $igstvalue,
                            'total' => $total,
                            'subtotal' => $subtotal,
                        ],['id' => $VarInsId]);
                        $this->db->update(KN_SUBSCRIBERENQUIRY, [
                            'requeststatus' => 2,
                            'badmin_userid'=>$status_updated_by,
                            'status_updated_datetime'=> date('Y-m-d H:i:s')
                        ],['id' => $subscriberid]);
                }
                $ArrResult['errcode'] = 1;
                $ArrResult['msg'] = '';
                $ArrResult['id'] = $VarInsId;
                $ArrResult['eid'] = urlencode(base64_encode($VarInsId));
             }  else {
                $ArrResult['errcode'] = -1;
                $ArrResult['msg'] = 'Invalid Data';
            }
            
        } else {
            if ($this->db->update(KN_PROFORMAINVOICE, $ArrSaveData, array('id' => $VarId))) {
                if($this->db->delete(KN_PROFORMAINVOICEDET, array('proforma_id' => $VarId))){
                    foreach ($ArrSavedetaildata as $key => $value)
                    {   
                        if($invotype=='within'){
                            $total +=$value[4];
                            $sgstpercent +=!empty($value[5])?$value[5]:'0.00';
                            $cgstpercent +=!empty($value[6])?$value[6]:'0.00';
                            $sgstvalue +=$value[7];
                            $cgstvalue +=$value[8];
                            $subtotal +=$value[9];
                            
                            $this->db->insert(KN_PROFORMAINVOICEDET, [
                            'proforma_id' => $VarId,
                            'description_id' => $value[0],
                            'detail_id' => $value[1],
                            'unit_id' => $value[2],
                            'qty' => $value[3],
                            'amount' => $value[4],
                            'sgst_percent' => $value[5],
                            'cgst_percent' => $value[6],
                            'sgst_amount' => $value[7],
                            'cgst_amount' => $value[8],
                            'subtotal' => $value[9],
                        ]);
                        }else{
                            $total +=$value[4];
                            $igstpercent +=!empty($value[5])?$value[5]:'0.00';
                            $igstvalue +=$value[6];
                            $subtotal +=$value[7];
                            
                            $this->db->insert(KN_PROFORMAINVOICEDET, [
                            'proforma_id' => $VarId,
                            'description_id' => $value[0],
                            'detail_id' => $value[1],
                            'unit_id' => $value[2],
                            'qty' => $value[3],
                            'amount' => $value[4],
                            'igst_percent' => $value[5],
                            'igst_amount' => $value[6],
                            'subtotal' => $value[7],
                        ]);
                        }
                        
                    }
                     $this->db->update(KN_PROFORMAINVOICE, [
                            'sgst_percent' => $sgstpercent,
                            'cgst_percent' => $cgstpercent,
                            'sgst_amount' => $sgstvalue,
                            'cgst_amount' => $cgstvalue,
                            'igst_percent' => $igstpercent,
                            'igst_amount' => $igstvalue,
                            'total' => $total,
                            'subtotal' => $subtotal,
                        ],['id' => $VarId]);
                        
                        $this->db->update(KN_SUBSCRIBERENQUIRY, [
                            'requeststatus' => 2,
                            'badmin_userid'=>$status_updated_by,
                            'status_updated_datetime'=> date('Y-m-d H:i:s')
                        ],['id' => $subscriberid]);
                }
                 
                $ArrResult['errcode'] = 1;
                $ArrResult['msg'] = '';
                $ArrResult['id'] = $VarId;
                $ArrResult['eid'] = urlencode(base64_encode($VarId));
            } else {
                $ArrResult['errcode'] = -1;
                $ArrResult['msg'] = 'Invalid Data';
            }

        }
        return $ArrResult;
    }
    public function checkDraftorNot($id)
    {
        $result = $this->db->from(KN_PROFORMAINVOICE)->where('subscriber_id', $id)->where('draft_status', 1)->get()->num_rows();
        return $result;
    }
    public function getdraftdata()
    {
        $result = $this->db->from(KN_PROFORMAINVOICE)->where('draft_status', 1)->get()->row();
        return $result;
    }
    
    public function cleardraft($id)
    {   
        if($this->db->delete(KN_PROFORMAINVOICE,array('id' => $id))){
        $this->db->delete(KN_PROFORMAINVOICEDET,array('proforma_id' => $id));
        $ArrResult['success']					    = 1;
        }else{
        $ArrResult['success']					    = 0;
        }
        return $ArrResult;
    }
    public function changeReverseDate($date)
    {
        $array=explode("-",$date);
        $rev=array_reverse($array);
        $date=implode("-",$rev);
        return $date;
    }
    function fnChangeComStat($checkboxids, $optvalue) {
        $Arrids = json_decode($checkboxids, true);
        $this->db->where_in('id', $Arrids);
        if ($this->db->update(KN_PROFORMAINVOICE, array('status' => $optvalue))) {
            return true;
        }
        return false;
    }
    function getproformadetails($Id)
    {
        
        // $query  = "SELECT pi.id,mdet.description as detail,TRIM(SUBSTRING_INDEX(mdet.description, 'GB', 1)) as detpart,CASE 
        //             WHEN mdesc.description LIKE '%Package%' 
        //             THEN TRIM(SUBSTRING_INDEX(mdesc.description, 'Package', 1)) 
        //             ELSE '' 
        //             END AS descpart,mdesc.description as description,pid.description_id,pid.detail_id,pid.unit_id,pid.qty,pid.amount,pid.sgst_percent,pid.cgst_percent,pid.igst_percent,pid.igst_amount,pid.sgst_amount,pid.cgst_amount,pid.subtotal
        //              FROM " . KN_PROFORMAINVOICE . " AS pi
        //              LEFT JOIN " . KN_PROFORMAINVOICEDET . " AS pid ON pid.proforma_id=pi.id
        //              LEFT JOIN " . KN_MASTER_DETAILS . " AS mdet ON mdet.id=pid.detail_id
        //              LEFT JOIN " . KN_MASTER_DESCRIPTION . " AS mdesc ON mdesc.id=pid.description_id
        //              WHERE pi.id = " . $Id;
        // $result   = $this->db->query($query)->result_array();

         $query  = "SELECT pi.id,mdet.description as detail,TRIM(SUBSTRING_INDEX(mdet.description, 'GB', 1)) as detpart,CASE 
                    WHEN mdesc.description LIKE '%Package%' 
                    THEN TRIM(SUBSTRING_INDEX(mdesc.description, 'Package', 1)) 
                    ELSE '' 
                    END AS descpart,mdesc.description as description,pid.description_id,pid.detail_id,pid.unit_id,pid.qty,pid.amount,pid.sgst_percent,pid.cgst_percent,pid.igst_percent,pid.igst_amount,pid.sgst_amount,pid.cgst_amount,pid.subtotal
                     FROM " . KN_PROFORMAINVOICE . " AS pi
                     LEFT JOIN " . KN_PROFORMAINVOICEDET . " AS pid ON pid.proforma_id=pi.id
                     LEFT JOIN " . KN_MASTER_DETAILS . " AS mdet ON mdet.id=pid.detail_id
                     LEFT JOIN " . KN_MASTER_DESCRIPTION . " AS mdesc ON mdesc.id=pid.description_id
                     WHERE pi.id = " . $Id;
        $result   = $this->db->query($query)->result_array();
        foreach ($result as &$row) {
    $row['unit_id']           = number_format($row['unit_id'], 2);
    $row['qty']           = number_format($row['qty'], 2);
    $row['amount']        = number_format($row['amount'], 2);
    $row['sgst_percent']  = number_format($row['sgst_percent'], 2);
    $row['cgst_percent']  = number_format($row['cgst_percent'], 2);
    $row['igst_percent']  = number_format($row['igst_percent'], 2);
    $row['sgst_amount']   = number_format($row['sgst_amount'], 2);
    $row['cgst_amount']   = number_format($row['cgst_amount'], 2);
    $row['igst_amount']   = number_format($row['igst_amount'], 2);
    $row['subtotal']      = number_format($row['subtotal'], 2);
}
        return $result;
    }
}