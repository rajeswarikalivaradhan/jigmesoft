<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
class Reqrecivedmodel extends CI_Model {
    
    function fnCount($Varcmpny, $Varcty, $Varpckdetid, $Varpurchtype, $Varreqraisedby, $Varreqstatus) {
    $filters = [];
    $params = [];
    
    if (!empty($Varcmpny)) {
        $filters[] = "b.companyname = ?";
        $params[] = $Varcmpny;
    }
    if (!empty($Varcty)) {
        $filters[] = "b.city = ?";
        $params[] = $Varcty;
    }
    if (!empty($Varpckdetid)) {
        $filters[] = "b.package_id = ?";
        $params[] = $Varpckdetid;
    }
    if (!empty($Varpurchtype)) {
        $filters[] = "b.purchasetype = ?";
        $params[] = $Varpurchtype;
    }
    if (!empty($Varreqraisedby)) {
        $filters[] = "u.contactname = ?";
        $params[] = $Varreqraisedby;
    }
    if (!empty($Varreqstatus)) {
        $filters[] = "b.requeststatus = ?";
        $params[] = $Varreqstatus;
    }
    
    // Add default conditions
    $filters[] = "b.requeststatus NOT IN (0,2) AND b.draft_status = 2";

    $whereClause = "";
    if (!empty($filters)) {
        $whereClause = "WHERE " . implode(" AND ", $filters);
    }

    $sql = "SELECT COUNT(1) AS trec 
            FROM " . KN_SUBSCRIBERENQUIRY . " AS b 
            LEFT JOIN " . KN_USERS . " AS u ON b.mrkt_dept_userid = u.id 
            $whereClause";

    $query = $this->db->query($sql, $params);
    $result = $query->row();
    return $result->trec;
}

    function fnList($Varcmpny, $Varcty,$Varpckdetid,$Varpurchtype,$Varreqraisedby,$Varreqstatus,$VarLimit = 10, $offset = 0, $VarSortBy, $VarSortOrder) {
        $VarSortOrder = ($VarSortOrder == 'desc') ? 'desc' : 'asc';
        $VarSortCols = array('companyname', 'b.status', 'b.id');
        //$VarSortBy = (in_array($VarSortBy, $VarSortCols)) ? $VarSortBy : 'b.id';
         $VarSortBy = (in_array($VarSortBy, $VarSortCols)) ? $VarSortBy : 'b.log';
        $VarLimitInfo = $VarLimit;
        if ($offset >= 1) {
            $VarLimitInfo = $offset . "," . $VarLimit;
        }
        $ArrWhere = array();
        if ($Varcmpny <> '') {
            $ArrWhere[] = "b.companyname=" . "'$Varcmpny'";
        }
        if ($Varcty <> '') {
            $ArrWhere[] = "b.city=" . "'$Varcty'";
        } 
        if ($Varpckdetid <> '') {
            $ArrWhere[] = "b.package_id=" . "'$Varpckdetid'";
        }
        if ($Varpurchtype <> '') {
            $ArrWhere[] = "b.purchasetype=" . "'$Varpurchtype'";
        }
        if ($Varreqraisedby <> '') {
            $ArrWhere[] = "u.contactname=" . "'$Varreqraisedby'";
        } 
        if ($Varreqstatus <> '') {
            $ArrWhere[] = "b.requeststatus=" . "'$Varreqstatus'";
        }
        $ArrUserLoggedInfo = fnGetUserLoggedInfo('1');
        $ArrWhere[] = "b.requeststatus NOT IN (0, 2) AND b.draft_status=2";
        $VarWhere = '';
        if (!empty($ArrWhere)) {
            $VarWhere = 'WHERE ' . implode(" and ", $ArrWhere);
        }
        
         $VarSqlLab = "SELECT b.id,b.companyname,b.city,b.state,b.package_id,b.purchasetype,u.contactname as request_raised_by,DATE_FORMAT(b.reqdatetime,\"%d/%m/%Y %h:%i %p\") as reqdatetime,b.requeststatus,DATE_FORMAT(b.log,\"%d/%m/%Y %h:%i %p\")  as recent_updated_datetime,b.status
                      FROM " . KN_SUBSCRIBERENQUIRY . " AS b LEFT JOIN " . KN_USERS . " AS u ON b.mrkt_dept_userid = u.id " . $VarWhere . " order by " . $VarSortBy . " " . $VarSortOrder;
                    $ObjResult = $this->db->query($VarSqlLab);
                    return $ObjResult;
    }
    
    public function getQuotedetailsGrid_cgst_sgst($enqId,$invotype,$proformatype,$profromaId)
    {
        if(!empty($profromaId)){
        $details = $this->db->select('id,description as name')->get(KN_MASTER_DETAILS)->result_array();
        $description = $this->db->select('id,description as name')->get_where(KN_MASTER_DESCRIPTION)->result_array();
        $unit_rate = $this->db->select('description as id,description as name')->get_where(KN_MASTER_UNITRATE)->result_array(); // because need to save it's value 
        }else{
        $details = $this->db->select('id,description as name')->get_where(KN_MASTER_DETAILS, array('status' => 1))->result_array();
        $description = $this->db->select('id,description as name')->get_where(KN_MASTER_DESCRIPTION, array('status' => 1))->result_array();
        $unit_rate = $this->db->select('description as id,description as name')->get_where(KN_MASTER_UNITRATE, array('status' => 1))->result_array(); // because need to save it's value
        }
        
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
            'data'   => $this->getQuotedetailsData($enqId,$invotype,$proformatype,$profromaId)
        ];

        // echo json_encode($results);
        // exit();
        return $results;
    }
    public function getsupplQuotedetailsGrid_cgst_sgst($enqId,$invotype,$proformatype,$purchasetype)
    {
        if(!empty($purchasetype) && ($purchasetype==4 || $purchasetype==5 || $purchasetype==6)){
        $description = $this ->db->select('id, description as name')
                             ->from(KN_MASTER_DESCRIPTION)
                             ->where('status', 1)
                             ->where_in('id', array(5, 6, 7, 8))
                             ->get()
                             ->result_array();
        }else{
         $description = $this->db->select('id,description as name')->get_where(KN_MASTER_DESCRIPTION, array('status' => 1))->result_array();      
        }
      
        //var_dump($description);
        $details = $this->db->select('id,description as name')->get_where(KN_MASTER_DETAILS, array('status' => 1))->result_array();
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
            'data'   => []
        ];

        // echo json_encode($results);
        // exit();
        return $results;
    }
    public function getQuotedetailsGrid_igst($enqId,$invotype,$proformatype,$profromaId)
    {
        
        if(!empty($profromaId)){
        $details = $this->db->select('id,description as name')->get(KN_MASTER_DETAILS)->result_array();
        $description = $this->db->select('id,description as name')->get_where(KN_MASTER_DESCRIPTION)->result_array();
        $unit_rate = $this->db->select('description as id,description as name')->get_where(KN_MASTER_UNITRATE)->result_array(); // because need to save it's value 
        }else{
        $details = $this->db->select('id,description as name')->get_where(KN_MASTER_DETAILS, array('status' => 1))->result_array();
        $description = $this->db->select('id,description as name')->get_where(KN_MASTER_DESCRIPTION, array('status' => 1))->result_array();
        $unit_rate = $this->db->select('description as id,description as name')->get_where(KN_MASTER_UNITRATE, array('status' => 1))->result_array(); // because need to save it's value
        }
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
            'data'   => $this->getQuotedetailsDataigst($enqId,$invotype,$proformatype,$profromaId)
        ];

        // echo json_encode($results);
        // exit();
        return $results;
    } 
    public function getsupplQuotedetailsGrid_igst($enqId,$invotype,$proformatype,$purchasetype)
    {
        
        $details = $this->db->select('id,description as name')->get_where(KN_MASTER_DETAILS, array('status' => 1))->result_array();
        if(!empty($purchasetype) && ($purchasetype==4 || $purchasetype==5 || $purchasetype==6)){
        $description = $this ->db->select('id, description as name')
                             ->from(KN_MASTER_DESCRIPTION)
                             ->where('status', 1)
                             ->where_in('id', array(5, 6, 7, 8))
                             ->get()
                             ->result_array();
        }else{
         $description = $this->db->select('id,description as name')->get_where(KN_MASTER_DESCRIPTION, array('status' => 1))->result_array();      
        }
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
            'data'   => []
        ];

        // echo json_encode($results);
        // exit();
        return $results;
    } 
     function getQuotedetailsData($enqId,$invotype,$proformatype,$profromaId)
    {
        $result = [];
        if(!empty($profromaId)){
            $query  = "SELECT pid.description_id,pid.detail_id,pid.unit_id,pid.qty,pid.amount,pid.sgst_percent,pid.cgst_percent
                     FROM " . KN_PROFORMAINVOICE . " AS pi
                     LEFT JOIN " . KN_PROFORMAINVOICEDET . " AS pid ON pid.proforma_id=pi.id
                     LEFT JOIN " . KN_SUBSCRIBERENQUIRY . " AS se ON se.id=pi.subscriber_id
                     WHERE pi.invoice_type='$invotype' and pi.id='$profromaId' and pi.proforma_type='$proformatype' and se.id = " . $enqId;
            $data   = $this->db->query($query)->result_array();
            foreach ($data as $key => $value)
            {
                $result[$key] = [$value['description_id'], $value['detail_id'], $value['unit_id'], $value['qty'], $value['amount'],$value['sgst_percent'],$value['cgst_percent']];
            }
        }
        // for showing empty value in supplementary invoice form changes made like above , below one is backup
        // $query  = "SELECT pid.description_id,pid.detail_id,pid.unit_id,pid.qty,pid.amount,pid.sgst_percent,pid.cgst_percent
        //              FROM " . KN_PROFORMAINVOICE . " AS pi
        //              LEFT JOIN " . KN_PROFORMAINVOICEDET . " AS pid ON pid.proforma_id=pi.id
        //              LEFT JOIN " . KN_SUBSCRIBERENQUIRY . " AS se ON se.id=pi.subscriber_id
        //              WHERE pi.invoice_type='$invotype' and pi.proforma_type='$proformatype' and se.id = " . $enqId;
        // $data   = $this->db->query($query)->result_array();
        // $result = [];
        // foreach ($data as $key => $value)
        // {
        //     $result[$key] = [$value['description_id'], $value['detail_id'], $value['unit_id'], $value['qty'], $value['amount'],$value['sgst_percent'],$value['cgst_percent']];
        // }
        
        return $result;
    } 
     
     function getQuotedetailsDataigst($enqId,$invotype,$proformatype,$profromaId)
    {
        $result = [];
        if(!empty($profromaId)){
            $query  = "SELECT pid.description_id,pid.detail_id,pid.unit_id,pid.qty,pid.amount,pid.igst_percent
                     FROM " . KN_PROFORMAINVOICE . " AS pi
                     LEFT JOIN " . KN_PROFORMAINVOICEDET . " AS pid ON pid.proforma_id=pi.id
                     LEFT JOIN " . KN_SUBSCRIBERENQUIRY . " AS se ON se.id=pi.subscriber_id
                     WHERE pi.invoice_type='$invotype' and pi.id='$profromaId' and pi.proforma_type='$proformatype' and se.id = " . $enqId;
            $data   = $this->db->query($query)->result_array();
                foreach ($data as $key => $value)
                {
                    $result[$key] = [$value['description_id'], $value['detail_id'], $value['unit_id'], $value['qty'], $value['amount'],$value['igst_percent']];
                }
        }
        // $query  = "SELECT pid.description_id,pid.detail_id,pid.unit_id,pid.qty,pid.amount,pid.igst_percent
        //              FROM " . KN_PROFORMAINVOICE . " AS pi
        //              LEFT JOIN " . KN_PROFORMAINVOICEDET . " AS pid ON pid.proforma_id=pi.id
        //              LEFT JOIN " . KN_SUBSCRIBERENQUIRY . " AS se ON se.id=pi.subscriber_id
        //              WHERE pi.invoice_type='$invotype' and pi.proforma_type='$proformatype' and se.id = " . $enqId;
        // $data   = $this->db->query($query)->result_array();
        // $result = [];
        // foreach ($data as $key => $value)
        // {
        //     $result[$key] = [$value['description_id'], $value['detail_id'], $value['unit_id'], $value['qty'], $value['amount'],$value['igst_percent']];
        // }
        
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
     public function getproformadata($id)
    {
        $result = $this->db->from(KN_PROFORMAINVOICE)->where('subscriber_id', $id)->get()->row();
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
     public function get_renewal_or_pckgmigration_proforma($purchasetype,$subscriber_id)
    {  
        if($purchasetype==2 || $purchasetype==3){
          $result = $this->db->from(KN_PROFORMAINVOICE)->where('subscriber_id', $subscriber_id)->where_in('purchasetype ', array(2,3))->where('invoice_status ', '1')->where('proforma_type', 'NPI')->where('paymentstatus <>', 3)->get()->row();  
          if(!empty($result)){
            $ArrResult['success']					    = 1;  
            $ArrResult['purchasetype']					= $result->purchasetype;  
          }else{
            $ArrResult['success']					    = 0;
            $ArrResult['purchasetype']					= '';
          }
            
        }else{
           $ArrResult['success']					    = 0;
           $ArrResult['purchasetype']					= '';
        }
        
        return $ArrResult;
    }
    public function get_recently_active_proforma($subscriber_id)
    {  
        return $this->db->select('*')
                    ->where('subscriber_id', $subscriber_id)
                    ->where('status', 1)
                    ->where('proforma_type','NPI')
                    ->where('invoice_status', 2)
                    ->order_by('dateupdated', 'DESC')  // Get the most recent record
                    ->limit(1)  // Ensure only one record is retrieved
                    ->get(KN_PROFORMAINVOICE)
                    ->row();
    }
}